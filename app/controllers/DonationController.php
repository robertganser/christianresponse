<?php

class DonationController extends BaseController {

	/*
	 |--------------------------------------------------------------------------
	 | Default Home Controller
	 |--------------------------------------------------------------------------
	 |
	 | You may wish to use controllers instead of, or in addition to, Closure
	 | based routes. That's great! Here is an example controller method to
	 | get you started. To route to this controller, just add the route:
	 |
	 |	Route::get('/', 'DonationController@showWelcome');
	 |
	 */

	public function step($type, $id) {
		$message = "";
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$project = DB::table($type) -> where("id", $id) -> first();

			if ($project -> paypal_number != "" && filter_var($project -> paypal_number, FILTER_VALIDATE_EMAIL)) :
				$this -> request_donation($type, $id);
			else :
				$message = "<div class='alert alert-danger alert-dismissable'>
	                            <button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button>
	                            Project paypal address is not set yet.
	                        </div>";
			endif;
		}

		$sql = "SELECT 	a.user_id, a.id, a.name, a.intro_video, a.thumbnail, a.user_id, a.description,
					(case when a.user_id = " . Auth::user() -> id . " then 1 else 0 end) as is_mine,
					(CASE WHEN AVG(b.mark) IS NULL THEN 0 ELSE AVG(b.mark) END) AS review,
					(select COUNT(user_id) from " . $type . "_follow where project_id = " . $id . ") AS follow_count,
					a.created_date,
					'" . $type . "' AS project_type,
					sum((case when c.user_id = " . Auth::user() -> id . " then 1 else 0 end)) as is_following,
					sum((case when b.user_id = " . Auth::user() -> id . " then 1 else 0 end)) as is_feedback
				FROM 	$type a
					LEFT JOIN " . $type . "_review b ON a.id = b.project_id
					LEFT JOIN " . $type . "_follow c ON a.id = c.project_id
				WHERE	a.id = $id
				GROUP BY a.id";
		$project_info = DB::select($sql);
		$overall = DB::select("select a.email, b.phone_number from users a left join user_profile b on a.id = b.user_id where a.permission = -1");
		$owner = DB::select("SELECT 	a.first_name, a.last_name, a.email, b.phone_number, b.address, b.city, b.state, b.zip_code, b.country
							FROM 	users a
								LEFT JOIN user_profile b ON a.id = b.user_id
							WHERE 	a.id = ?", array($project_info[0] -> user_id));

		$sql = "select count(*) as count from " . $type . "_review where project_id = " . $id;
		$total_review = DB::select($sql);

		$transactions = DB::table($type . "_transaction") -> where("project_id", $id) -> where("status", 1) -> where("user_id", Auth::user() -> id) -> get();

		return View::make("/frontend/" . $this -> _permission[Auth::user() -> permission] . "/donation/step") -> with(array("active" => "", "project_info" => $project_info[0], "overall" => $overall[0], "owner" => $owner[0], "total_review" => $total_review[0], "transactions" => $transactions, "message" => $message));
	}

	public function request_donation($type, $id) {
		include ("include/paypal/paypal.php");
		$project = DB::table($type) -> where("id", $id) -> first();

		$amount = Input::get("amount");
		$owner_email = $project -> paypal_number;
		$owner_amount = Input::get("row1_unit_price");

		$overall_email = Config::get("app.paypal_email");
		$overall_amount = Input::get("row2_unit_price");

		$fee = Input::get("row3_unit_price");

		$transactionid = "TS-PR-" . $this -> generate_rand(32);

		$return_url = Config::get("app.url") . "/project/" . $type . "/" . $id . "/donation/success/" . $transactionid;
		$cancel_url = Config::get("app.url") . "/project/" . $type . "/" . $id . "/donation/cancel/" . $transactionid;

		DB::table($type . "_transaction") -> insert(array("id" => $transactionid, "project_id" => $id, "amount" => $owner_amount, "user_id" => Auth::user() -> id, "name" => Auth::user() -> first_name . " " . Auth::user() -> last_name, "email" => Auth::user() -> email, "status" => -100, "created_date" => date("Y-m-d H:i:s")));
		DB::table("overall_transaction") -> insert(array("id" => null, "related_transaction_id" => $transactionid, "project_id" => $id, "project_type" => $type, "amount" => $overall_amount, "user_id" => Auth::user() -> id, "name" => Auth::user() -> first_name . " " . Auth::user() -> last_name, "email" => Auth::user() -> email, "status" => -100, "created_date" => date("Y-m-d H:i:s")));

		$paypal = new Paypal;
		$receiver = array( array("amount" => $owner_amount, "email" => $owner_email), array("amount" => $overall_amount, "email" => $overall_email));
		$item = array( array("name" => "Donation for " . $project -> name, "identifier" => "p1", "price" => $owner_amount, "itemPrice" => $owner_amount, "itemCount" => 1), array("name" => "Response for donation", "identifier" => "p2", "price" => $overall_amount, "itemPrice" => $overall_amount, "itemCount" => 1));
		$receiverOptions = array( array("receiver" => array("email" => $owner_email), "invoiceData" => array("item" => array( array("name" => "Donation for " . $project -> name, "price" => $owner_amount, "identifire" => "p1")))), array("receiver" => array("email" => $overall_email), "invoiceData" => array("item" => array( array("name" => "Responsive for donation", "price" => $overall_amount, "identifire" => "p2")))));
		$paypal -> splitPay($receiver, $item, $return_url, $cancel_url, $receiverOptions);
		exit ;
	}

	public function success($type, $id, $transactionid, $a = "search") {
		DB::table($type . "_transaction") -> where("id", $transactionid) -> update(array("status" => 1));
		DB::table("overall_transaction") -> where("related_transaction_id", $transactionid) -> update(array("status" => 1));

		$transaction = DB::table($type . "_transaction") -> where("id", $transactionid) -> first();
		$owner = DB::table('users') -> join($type, $type . '.user_id', '=', 'users.id') -> select('users.email') -> first();
		$mail = new PHPMailer;
		$mail -> setFrom(Config::get("app.support_email"));
		$mail -> addAddress($owner -> email);

		$body = "<style>
					* {
						font-family: Arial;
					}
					table {
						font-size: 12px;
					}
				</style>
				<h4>You have a new donation from " . $transaction -> name . ".</h4>
				<table>
					<tr>
						<td>Name: </td>
						<td>" . $transaction -> name . "</td>
					</tr>
					<tr>
						<td>Email: </td>
						<td>" . $transaction -> email . "</td>
					</tr>
					<tr>
						<td>Amount: </td>
						<td>" . $transaction -> amount . "</td>
					</tr>
					<tr>
						<td valign='top'>Transaction : </td>
						<td><a href='" . Config::get("app.url") . "projects/" . $type . "/" . $id . "/transactions'>Click to go.</a></td>
					</tr>
				</table>";

		$mail -> Subject = "Christian Response: You have a new donation.";
		$mail -> msgHTML($body);
		$mail -> AltBody = $body;
		$mail -> send();

		if (Auth::check()) :
			//return View::make("/frontend/" . $this -> _permission[Auth::user() -> permission] . "/donation/success") -> with(array("active" => "", "redirect_url" => "/dashboard/project/view/" . $type . "/" . $id, "type" => "Project"));
			$error = "<div class='alert alert-success alert-dismissable'>
                        <button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button>
                        Thank you for your support in this project.
                    </div>";
			Session::set("error", $error);

			if ($a == "search") :
				return Redirect::to("/search/project/" . $type . "/view/" . $id);
			elseif ($a == "dashboard") :
				return Redirect::to("/dashboard/project/view/" . $type . "/" . $id);
			endif;
		else :
			Session::set("error", $this -> responsebox("Thank you for your support!.", "success"));
			return Redirect::to("/project/view/" . strtolower($type) . "/" . $id);
		endif;
	}

	public function cancel($type, $id, $transactionid, $a = "search") {
		DB::table($type . "_transaction") -> where("id", $transactionid) -> delete();
		DB::table("overall_transaction") -> where("related_transaction_id", $transactionid) -> delete();

		$redirect_url = "/dashboard/project/view/" . $type . "/" . $id;

		if (Auth::check()) :
			//return View::make("/frontend/" . $this -> _permission[Auth::user() -> permission] . "/donation/cancel") -> with(array("active" => "", "redirect_url" => "/dashboard/project/view/" . $type . "/" . $id, "type" => "Project"));
			$error = "<div class='alert alert-danger alert-dismissable'>
                        <button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button>
                        You have cancelled donation process.
                    </div>";
			Session::set("error", $error);
			if ($a == "search") :
				return Redirect::to("/search/project/" . $type . "/view/" . $id);
			elseif ($a == "dashboard") :
				return Redirect::to("/dashboard/project/view/" . $type . "/" . $id);
			endif;
		else :
			Session::set("error", $this -> responsebox("You have cancelled donation request."));
			return Redirect::to("/project/view/" . strtolower($type) . "/" . $id);
		endif;
	}

}
