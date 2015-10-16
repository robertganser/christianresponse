<?php

class SharedController extends BaseController {

	/*
	 |--------------------------------------------------------------------------
	 | Default Home Controller
	 |--------------------------------------------------------------------------
	 |
	 | You may wish to use controllers instead of, or in addition to, Closure
	 | based routes. That's great! Here is an example controller method to
	 | get you started. To route to this controller, just add the route:
	 |
	 |	Route::get('/', 'SharedController@showWelcome');
	 |
	 */

	public function region($param) {
		$dd = explode("/", base64_decode($param));
		$date = $dd[0];
		$region_id = $dd[1];
		$show_days = 5;

		if (Auth::check()) {
			echo "<script>location.href='/region/" . $date . "/" . $region_id . "'</script>";
			exit ;
		}

		$region_info = DB::select("SELECT 	a.country, a.state, a.show_days, b.region_id, b.intro_video, b.related_report, b.memo,
										CASE WHEN b.title IS NULL THEN CONCAT(a.state, ', ', a.country) ELSE b.title END AS title
									FROM 	region_manager a
										LEFT JOIN region_page b ON a.id = b.region_id
									WHERE	a.id = ?
									GROUP BY a.id", array($region_id));
		$country = $region_info[0] -> country;
		$state = $region_info[0] -> state;
		if ($region_info[0] -> show_days > 0) {
			$show_days = $region_info[0] -> show_days;
		}

		$start = $date . " 00:00:00";
		$end = date("Y-m-d 23:59:59", strtotime("+ " . $show_days . "days", strtotime($start)));

		$event_sql = "SELECT	a.id AS project_id, a.name AS project_title, b.id AS event_id, b.title AS event_title, b.cost,
							b.address, b.city, b.state, b.zip_code, b.country, b.longitude, b.latitude, b.event_date, 'prayer' as type
						FROM 	prayer a, prayer_event b, region_allocate d
						WHERE 	a.id = b.project_id
							AND a.id = d.project_id and d.project_type = 'prayer'
							AND b.country = '" . $country . "' AND b.state = '" . $state . "'
							AND a.status = 1 
							AND b.status = 1
							AND b.event_date BETWEEN '$start' AND '$end'
						UNION
						SELECT	a.id AS project_id, a.name AS project_title, b.id AS event_id, b.title AS event_title, b.cost,
							b.address, b.city, b.state, b.zip_code, b.country, b.longitude, b.latitude, b.event_date, 'impact' as type
						FROM 	impact a, impact_event b, region_allocate d
						WHERE 	a.id = b.project_id
							AND a.id = d.project_id and d.project_type = 'impact'
							AND b.country = '" . $country . "' AND b.state = '" . $state . "'
							AND a.status = 1 
							AND b.status = 1
							AND b.event_date BETWEEN '$start' AND '$end'
						UNION
						SELECT	a.id AS project_id, a.name AS project_title, b.id AS event_id, b.title AS event_title, b.cost,
							b.address, b.city, b.state, b.zip_code, b.country, b.longitude, b.latitude, b.event_date, 'nationalreport' as type
						FROM 	nationalreport a, nationalreport_event b, region_allocate d
						WHERE 	a.id = b.project_id
							AND a.id = d.project_id and d.project_type = 'nationalreport'
							AND b.country = '" . $country . "' AND b.state = '" . $state . "'
							AND a.status = 1 
							AND b.status = 1
							AND b.event_date BETWEEN '$start' AND '$end'
						UNION
						SELECT	a.id AS project_id, a.name AS project_title, b.id AS event_id, b.title AS event_title, b.cost,
							b.address, b.city, b.state, b.zip_code, b.country, b.longitude, b.latitude, b.event_date, 'regionalreport' as type
						FROM 	regionalreport a, regionalreport_event b, region_allocate d
						WHERE 	a.id = b.project_id
							AND a.id = d.project_id and d.project_type = 'regionalreport'
							AND b.country = '" . $country . "' AND b.state = '" . $state . "'
							AND a.status = 1 
							AND b.status = 1
							AND b.event_date BETWEEN '$start' AND '$end'
						UNION
						SELECT	a.id AS project_id, a.name AS project_title, b.id AS event_id, b.title AS event_title, b.cost,
							b.address, b.city, b.state, b.zip_code, b.country, b.longitude, b.latitude, b.event_date, 'teaching' as type
						FROM 	teaching a, teaching_event b, region_allocate d
						WHERE 	a.id = b.project_id
							AND a.id = d.project_id and d.project_type = 'teaching'
							AND b.country = '" . $country . "' AND b.state = '" . $state . "'
							AND a.status = 1 
							AND b.status = 1
							AND b.event_date BETWEEN '$start' AND '$end'
						ORDER BY event_date";
		$events = DB::select($event_sql);
		$annual_event = DB::select("select 	a.region_id, '' as project_title, a.id, a.title, a.cost, 
										a.address, a.city, a.state, a.zip_code, a.country, a.longitude, a.latitude, a.event_date, 'annual' as type
									from 	region_annual_event a
									where	a.region_id = " . $region_id . "
									ORDER BY event_date");

		$related_report = array();
		if ($region_info[0] -> related_report > 0) {
			$related_report = DB::table("regionalreport") -> where("id", $region_info[0] -> related_report) -> get();
		}

		$top_projects = DB::table("topproject") -> get();
		$about = DB::table("about") -> first();
		$about_content = !empty($about) ? $about -> content : "";
		
		$contact = DB::table("contact_us") -> first();

		if (empty($contact)) {
			$contact = array("content" => "", "phone_number" => "", "address" => "", "email" => "");
			$contact = json_decode(json_encode($contact), FALSE);
		}

		return View::make("/frontend/region") -> with(array("region_info" => $region_info[0], "events" => $events, "annual_event" => $annual_event, "related_report" => $related_report, "top_projects" => $top_projects, "param" => $param, "about_content" => $about_content, "contact" => $contact));
	}

	public function region_step($param) {
		$dd = explode("/", base64_decode($param));
		$date = $dd[0];
		$region_id = $dd[1];

		$message = "";
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$owner = DB::select("select a.email from users a, region_manager b where a.id = b.user_id and b.id = ?", array($region_id));
			if ($owner[0] -> email != "" && filter_var($owner[0] -> email, FILTER_VALIDATE_EMAIL) && Config::get("app.paypal_email") != "" && filter_var(Config::get("app.paypal_email"), FILTER_VALIDATE_EMAIL)) :
				include ("include/paypal/paypal.php");

				$name = Input::get("name");
				$email = Input::get("email");

				$overall = DB::table("users") -> where("permission", -1) -> first();
				$owner = DB::select("select a.email from users a, region_manager b where a.id = b.user_id and b.id = ?", array($region_id));

				$amount = Input::get("amount");
				$owner_email = $owner[0] -> email;
				$owner_amount = Input::get("row1_unit_price");

				$overall_email = Config::get("app.paypal_email");
				$overall_amount = Input::get("row2_unit_price");

				$fee = Input::get("row3_unit_price");

				$transactionid = "TS-RG-" . $this -> generate_rand(32);

				$return_url = Config::get("app.url") . "/share/region/" . $param . "/donation/success/" . $transactionid;
				$cancel_url = Config::get("app.url") . "/share/region/" . $param . "/donation/cancel/" . $transactionid;

				DB::table("region_transaction") -> insert(array("id" => $transactionid, "region_id" => $region_id, "amount" => $owner_amount, "user_id" => 0, "name" => $name, "email" => $email, "status" => -100, "created_date" => date("Y-m-d H:i:s")));
				DB::table("overall_transaction") -> insert(array("id" => null, "related_transaction_id" => $transactionid, "project_id" => $region_id, "project_type" => "region", "amount" => $overall_amount, "user_id" => 0, "name" => $name, "email" => $email, "status" => -100, "created_date" => date("Y-m-d H:i:s")));

				$paypal = new Paypal;
				$receiver = array( array("amount" => $owner_amount, "email" => $owner_email), array("amount" => $overall_amount, "email" => $overall_email));
				$item = array( array("name" => "Donation for region", "identifier" => "p1", "price" => $owner_amount, "itemPrice" => $owner_amount, "itemCount" => 1), array("name" => "Response for donation", "identifier" => "p2", "price" => $overall_amount, "itemPrice" => $overall_amount, "itemCount" => 1));
				$receiverOptions = array( array("receiver" => array("email" => $owner_email), "invoiceData" => array("item" => array( array("name" => "Donation for region", "price" => $owner_amount, "identifire" => "p1")))), array("receiver" => array("email" => $overall_email), "invoiceData" => array("item" => array( array("name" => "Responsive for donation", "price" => $overall_amount, "identifire" => "p2")))));
				$paypal -> splitPay($receiver, $item, $return_url, $cancel_url, $receiverOptions);
				exit ;
			else :
				$message = $this -> responsebox("Project paypal address is not set yet.");
			endif;
		}
		$top_projects = DB::table("topproject") -> get();
		$about = DB::table("about") -> first();
		$about_content = !empty($about) ? $about -> content : "";
		
		$contact = DB::table("contact_us") -> first();

		if (empty($contact)) {
			$contact = array("content" => "", "phone_number" => "", "address" => "", "email" => "");
			$contact = json_decode(json_encode($contact), FALSE);
		}

		return View::make("/frontend/region_donation") -> with(array("key" => "", "message" => $message, "top_projects" => $top_projects, "about_content" => $about_content, "contact" => $contact));
	}

	public function region_donation_success($param, $tansaction_id) {
		$dd = explode("/", base64_decode($param));
		$date = $dd[0];
		$region_id = $dd[1];

		DB::table("region_transaction") -> where("id", $tansaction_id) -> update(array("status" => 1));
		DB::table("overall_transaction") -> where("related_transaction_id", $tansaction_id) -> update(array("status" => 1));

		$region = DB::select("SELECT 	a.id, (case when b.title is null then concat(a.country, ', ', a.state) else b.title end) as title 
							FROM 	region_manager a LEFT JOIN region_page b ON a.id = b.region_id WHERE a.id = ?", array($region_id));
		$transaction = DB::table("region_transaction") -> where("id", $tansaction_id) -> first();
		$owner = DB::select("select a.email from users a, region_manager b where a.id = b.user_id and b.id = ?", array($region_id));
		$mail = new PHPMailer;
		$mail -> setFrom(Config::get("app.support_email"));
		$mail -> addAddress($owner[0] -> email);

		$body = "<style>
					* {
						font-family: Arial;
					}
					table {
						font-size: 12px;
					}
				</style>
				<h4>You have a new donation from your region.</h4>
				<table>
					<tr>
						<td>Region Name: </td>
						<td>" . $region[0] -> title . "</td>
					</tr>
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
						<td valign='top'>&nbsp;</td>
						<td><a href='" . Config::get("app.url") . "manages/region'>Click to go to region.</a></td>
					</tr>
				</table>";

		$mail -> Subject = "Christian Response: You have a new donation from your region.";
		$mail -> msgHTML($body);
		$mail -> AltBody = $body;
		$mail -> send();

		Session::set("message", $this -> responsebox("Thank you for your support in this region.", "success"));

		return Redirect::to("/share/region/" . $param);
	}

	public function region_donation_cancel($param, $tansaction_id) {
		$dd = explode("/", base64_decode($param));
		$date = $dd[0];
		$region_id = $dd[1];

		DB::table("region_transaction") -> where("id", $tansaction_id) -> delete();
		DB::table("overall_transaction") -> where("related_transaction_id", $tansaction_id) -> delete();

		Session::set("message", $this -> responsebox("Your donation is cancelled."));

		return Redirect::to("/share/region/" . $param);
	}

}
