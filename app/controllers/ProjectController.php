<?php

class ProjectController extends BaseController {

	/*
	 |--------------------------------------------------------------------------
	 | Default Home Controller
	 |--------------------------------------------------------------------------
	 |
	 | You may wish to use controllers instead of, or in addition to, Closure
	 | based routes. That's great! Here is an example controller method to
	 | get you started. To route to this controller, just add the route:
	 |
	 |	Route::get('/', 'HomeController@showWelcome');
	 |
	 */

	public function index() {
		$top_projects = DB::table("topproject") -> get();
		$about = DB::table("about") -> first();
		$about_content = !empty($about) ? $about -> content : "";

		$contact = DB::table("contact_us") -> first();

		if (empty($contact)) {
			$contact = array("content" => "", "phone_number" => "", "address" => "", "email" => "");
			$contact = json_decode(json_encode($contact), FALSE);
		}

		return View::make("/frontend/project_category") -> with(array("key" => "project", "top_projects" => $top_projects, "about_content" => $about_content, "contact" => $contact));
	}

	public function project_view($type, $id) {
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$action = Input::get("action");
			if ($action == "hug") {
				$name = Input::get("hug_name");
				$email = Input::get("hug_email");
				$text = Input::get("hug_text");

				DB::table($type . "_hug") -> insert(array("id" => null, "project_id" => $id, "name" => $name, "email" => $email, "text" => $text, "picture" => Config::get("app.url") . "/res/profile/default-user.png", "created_date" => date("Y-m-d H:i:s")));

				$project = DB::table($type) -> where("id", $id) -> first();
				$owner = DB::select("select a.email from users a, " . $type . " b where a.id = b.user_id and b.id = $id");

				if ($owner[0] -> email != "") {
					$body = "<style>
								* {
									font-family: Arial;
								}
								table {
									font-size: 12px;
								}
							</style>
							<h4>Someone sent you new hug.</h4>
							<table>
								<tr>
									<td>Project Name: </td>
									<td><a href='" . Config::get("app.url") . "/projects/" . $type . "/" . $project -> id . "/hugs' target='_blank'>" . $project -> name . "</a></td>
								</tr>
							</table>
							<br>
							<p>Personal Information: </p>
							<table>
								<tr>
									<td>Name: </td>
									<td>" . $name . "</td>
								</tr>
								<tr>
									<td>Email Address: </td>
									<td>" . $email . "</td>
								</tr>
								<tr>
									<td valign='top'>Comment: </td>
									<td>" . $text . "</td>
								</tr>
							</table>";
					$mail = new PHPMailer;
					$mail -> setFrom(Config::get("app.support_email"));
					$mail -> addAddress($owner[0] -> email);
					$mail -> Subject = "Christian Response: Someone sent an hug.";
					$mail -> msgHTML($body);
					$mail -> AltBody = $body;
					$mail -> send();
				}
			} elseif ($action == "feedback") {
				$name = Input::get("feedback_name");
				$email = Input::get("feedback_email");
				$text = Input::get("feedback_comment");
				$mark = Input::get("feedback_score");

				DB::table($type . "_review") -> insert(array("id" => null, "project_id" => $id, "name" => $name, "email" => $email, "comment" => $text, "mark" => $mark, "user_id" => 0, "created_date" => date("Y-m-d H:i:s")));
			}
			return Redirect::to("/project/view/" . $type . "/" . $id);
		}

		$info = DB::select("SELECT 	a.*, d.first_name, d.last_name, d.email, e.phone_number,
								(CASE WHEN AVG(b.mark) IS NULL THEN 0 ELSE AVG(b.mark) END) AS review,
								COUNT(c.user_id) AS follow_count
							FROM 	" . $type . " a
								LEFT JOIN " . $type . "_review b ON a.id = b.project_id
								LEFT JOIN " . $type . "_follow c ON a.id = c.project_id,
								users d left join user_profile e on d.id = e.user_id
							WHERE 	a.id = $id
								and a.user_id = d.id
							GROUP BY a.id");

		$project = DB::table($type) -> where("id", $id) -> first();
		$communications = DB::table($type . "_communication") -> where("project_id", $id) -> orderBy("created_date", "desc") -> get();
		$events = DB::table($type . "_event") -> where("project_id", $id) -> where("status", 1) -> orderby("title", "asc") -> get();
		$top_projects = DB::table("topproject") -> get();
		$about = DB::table("about") -> first();
		$about_content = !empty($about) ? $about -> content : "";

		$contact = DB::table("contact_us") -> first();

		if (empty($contact)) {
			$contact = array("content" => "", "phone_number" => "", "address" => "", "email" => "");
			$contact = json_decode(json_encode($contact), FALSE);
		}

		return View::make("/frontend/project_view") -> with(array("key" => "project", "type" => $type, "project_type" => ucfirst($type), "info" => $info[0], "communications" => $communications, "events" => $events, "share_link" => Config::get("app.url") . $_SERVER["REQUEST_URI"], "redirect_url" => Config::get("app.url") . $_SERVER["REQUEST_URI"], "picture" => $project -> thumbnail != "" ? $project -> thumbnail : Config::get("app.url") . "/images/facebook/global_compact-icon-1365540187.png", "caption" => $project -> name, "description" => $project -> description, "top_projects" => $top_projects, "about_content" => $about_content, "contact" => $contact));
	}

	public function projects_of_category($category) {
		$projects = array();
		switch($category) :
			case 'report' :
				//national & regional
				$projects = DB::select("SELECT 	a.id, a.name, a.thumbnail, 
											(CASE WHEN AVG(b.mark) IS NULL THEN 0 ELSE AVG(b.mark) END) AS review,
											COUNT(c.user_id) AS follow_count,
											a.created_date,
											'nationalreport' as project_type
										FROM 	nationalreport a
											LEFT JOIN nationalreport_review b ON a.id = b.project_id
											LEFT JOIN nationalreport_follow c ON a.id = c.project_id,
											region_allocate d
										WHERE 	a.status = 1
											AND a.id = d.project_id and d.project_type = 'nationalreport'
										GROUP BY a.id
										UNION
										SELECT 	a.id, a.name, a.thumbnail, 
											(CASE WHEN AVG(b.mark) IS NULL THEN 0 ELSE AVG(b.mark) END) AS review,
											COUNT(c.user_id) AS follow_count,
											a.created_date,
											'regionalreport' as project_type
										FROM 	regionalreport a
											LEFT JOIN regionalreport_review b ON a.id = b.project_id
											LEFT JOIN regionalreport_follow c ON a.id = c.project_id,
											region_allocate d
										WHERE 	a.status = 1
											AND a.id = d.project_id and d.project_type = 'regionalreport'
										GROUP BY a.id
										order by review desc, created_date desc");
				break;
			default :
				$projects = DB::select("SELECT 	a.id, a.name, a.thumbnail, 
											(CASE WHEN AVG(b.mark) IS NULL THEN 0 ELSE AVG(b.mark) END) AS review,
											COUNT(c.user_id) AS follow_count,
											a.created_date,
											'" . $category . "' as project_type
										FROM 	" . $category . " a
											LEFT JOIN " . $category . "_review b ON a.id = b.project_id
											LEFT JOIN " . $category . "_follow c ON a.id = c.project_id,
											region_allocate d
										WHERE 	a.status = 1
											AND a.id = d.project_id and d.project_type = '" . $category . "'
										GROUP BY a.id
										order by review desc, created_date desc");
				break;
		endswitch;

		$top_projects = DB::table("topproject") -> get();
		$about = DB::table("about") -> first();
		$about_content = !empty($about) ? $about -> content : "";

		$contact = DB::table("contact_us") -> first();

		if (empty($contact)) {
			$contact = array("content" => "", "phone_number" => "", "address" => "", "email" => "");
			$contact = json_decode(json_encode($contact), FALSE);
		}

		return View::make("/frontend/projects_of_category") -> with(array("key" => "project", "category_name" => ucfirst($category), "projects" => $projects, "top_projects" => $top_projects, "about_content" => $about_content, "contact" => $contact));
	}

	public function get_event($type, $id) {
		if ($type == 'annual') {
			$info = DB::table("region_annual_event") -> where("id", $id) -> first();
		} else {
			$info = DB::table($type . "_event") -> where("id", $id) -> first();
		}

		$check = null;

		if (Auth::check()) {
			if ($type == 'annual') {
				$check = DB::table("region_annual_event_join") -> where("event_id", $id) -> where("user_id", Auth::user() -> id) -> first();
			} else {
				$check = DB::table($type . "_event_join") -> where("event_id", $id) -> where("user_id", Auth::user() -> id) -> first();
			}
		}

		echo json_encode(array("success" => true, "info" => $info, "join_info" => $check, "joined" => empty($check) ? 0 : 1));
	}

	public function withdraw($type, $id) {
		if ($type == "annual") :
			DB::table("region_annual_event_join") -> where("event_id", $id) -> where("user_id", Auth::user() -> id) -> delete();
		else :
			DB::table($type . "_event_join") -> where("event_id", $id) -> where("user_id", Auth::user() -> id) -> delete();
		endif;

		echo json_encode(array("success" => true));
	}

	public function join($prefix, $type, $project_id, $event_id, $action, $transactionid) {
		$error = "";
		switch($action) :
			case 'success' :
				DB::table($type . "_event_join") -> where("event_id", $event_id) -> where("user_id", Auth::user() -> id) -> update(array("status" => 1));
				DB::table($type . "_event_transaction") -> where("id", $transactionid) -> update(array("status" => 1));
				DB::table("overall_event_transaction") -> where("related_transaction_id", $transactionid) -> update(array("status" => 1));

				$owner = DB::select("select a.email from users a, " . $type . " b where a.id = b.user_id and b.id = " . $project_id);
				if ($owner[0] -> email != "") {
					$project = DB::table($type) -> where("id", $project_id) -> first();
					$event = DB::table($type . "_event") -> where("id", $event_id) -> first();

					$body = "<style>
					 		* {font-family: Arial;}
							table {font-size: 12px;}
					 	</style>
					 	<h4>Someone joins an event.</h4>
					 	<table>
					 		<tr>
					 			<td>Project Name: </td>
					 			<td><a href='" . Config::get("app.url") . "/search/project/" . $type . "/view/" . $project -> id . "' target='_blank'>" . $project -> name . "</a></td>
					 		</tr>
					 		<tr>
					 			<td>Event Title: </td>
					 			<td>" . $event -> title . "</td>
					 		</tr>
					 	</table>
					 	<br>
					 	<p>Personal Information: </p>
					 	<table>
					 		<tr>
					 			<td>Name: </td>
					 			<td><a href='" . Config::get("app.url") . "/search/facilitator/single/view/" . Auth::user() -> id . "' target='_blank'>" . Auth::user() -> first_name . " " . Auth::user() -> last_name . "</a></td>
					 		</tr>
					 		<tr>
					 			<td>Email Address: </td>
					 			<td>" . Auth::user() -> email . "</td>
					 		</tr>
					 		<tr>
					 			<td>Phone Number: </td>
					 			<td>" . Auth::user() -> phone_number . "</td>
					 		</tr>
					 	</table>";

					$mail = new PHPMailer;
					$mail -> setFrom(Config::get("app.support_email"));
					$mail -> addAddress($owner[0] -> email);
					$mail -> Subject = "Christian Response: Someone joins an event.";
					$mail -> msgHTML($body);
					$mail -> AltBody = $body;
					$mail -> send();

					$error = "<div class='alert alert-success alert-dismissable'>
	                            <button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button>
	                            You have joined in project event successfully.
	                        </div>";
				}
				break;
			case 'cancel' :
				DB::table($type . "_event_join") -> where("event_id", $event_id) -> where("user_id", Auth::user() -> id) -> delete();
				DB::table($type . "_event_transaction") -> where("id", $transactionid) -> delete();
				DB::table("overall_event_transaction") -> where("related_transaction_id", $transactionid) -> delete();

				$error = "<div class='alert alert-danger alert-dismissable'>
                            <button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button>
                            You have cancelled to join in project event.
                        </div>";
				break;
		endswitch;

		Session::set("error", $error);

		if ($prefix == "search") {
			return Redirect::to("/search/project/" . $type . "/view/" . $project_id);
		} elseif ($prefix == "dashboard") {
			return Redirect::to("/dashboard/project/view/" . $type . "/" . $project_id);
		}
	}

	/*
	 public function join($type, $id) {
	 $comment = Input::get("comment");

	 if ($type == 'annual') {
	 DB::table("region_annual_event_join") -> insert(array("id" => null, "event_id" => $id, "user_id" => Auth::user() -> id, "comment" => $comment, "created_date" => date("Y-m-d H:i:s")));

	 $owner = DB::select("select a.email from users a, region_manager b, region_annual_event c
	 where a.id = b.user_id and b.id = c.region_id and c.id = ?", array($id));
	 if ($owner[0] -> email != "") {
	 $body = "<style>
	 * {
	 font-family: Arial;
	 }

	 table {
	 font-size: 12px;
	 }
	 </style>
	 <h4>Someone joins an annual event.</h4>
	 <p>Personal Information: </p>
	 <table>
	 <tr>
	 <td>Name: </td>
	 <td><a href='" . Config::get("app.url") . "/search/facilitator/single/view/" . Auth::user() -> id . "' target='_blank'>" . Auth::user() -> first_name . " " . Auth::user() -> last_name . "</a></td>
	 </tr>
	 <tr>
	 <td>Email Address: </td>
	 <td>" . Auth::user() -> email . "</td>
	 </tr>
	 <tr>
	 <td>Phone Number: </td>
	 <td>" . Auth::user() -> phone_number . "</td>
	 </tr>
	 <tr>
	 <td valign='top'>Join Comment: </td>
	 <td>" . $comment . "</td>
	 </tr>
	 </table>";

	 $mail = new PHPMailer;
	 $mail -> setFrom('contact@' . Config::get("app.host_uri"));
	 $mail -> addAddress($owner[0] -> email);
	 $mail -> Subject = "Christian Response: Someone joins annual event.";
	 $mail -> msgHTML($body);
	 $mail -> AltBody = $body;
	 $mail -> send();
	 }
	 } else {
	 DB::table($type . "_event_join") -> insert(array("id" => null, "event_id" => $id, "user_id" => Auth::user() -> id, "comment" => $comment, "created_date" => date("Y-m-d H:i:s")));

	 $owner = DB::select("select a.email from users a, " . $type . " b, " . $type . "_event c
	 where a.id = b.user_id and b.id and c.project_id and c.id = $id group by a.email");
	 if ($owner[0] -> email != "") {
	 $person = DB::select("select a.first_name, a.last_name, a.email, b.phone_number from users a
	 left join user_profile b on a.id = b.user_id
	 where a.id = " . Auth::user() -> id);
	 $project = DB::select("select distinct a.id, a.name from " . $type . " a where a.id in (select b.project_id from " . $type . "_event b where b.id = $id)");
	 $event = DB::table($type . "_event") -> where("id", $id) -> first();

	 $body = "<style>
	 * {
	 font-family: Arial;
	 }

	 table {
	 font-size: 12px;
	 }
	 </style>
	 <h4>Someone joins an event.</h4>
	 <table>
	 <tr>
	 <td>Project Name: </td>
	 <td><a href='" . Config::get("app.url") . "/search/project/" . $type . "/view/" . $project[0] -> id . "' target='_blank'>" . $project[0] -> name . "</a></td>
	 </tr>
	 <tr>
	 <td>Event Name: </td>
	 <td>" . $event -> title . "</td>
	 </tr>
	 </table>
	 <br>
	 <p>Personal Information: </p>
	 <table>
	 <tr>
	 <td>Name: </td>
	 <td><a href='" . Config::get("app.url") . "/search/facilitator/single/view/" . Auth::user() -> id . "' target='_blank'>" . $person[0] -> first_name . " " . $person[0] -> last_name . "</a></td>
	 </tr>
	 <tr>
	 <td>Email Address: </td>
	 <td>" . $person[0] -> email . "</td>
	 </tr>
	 <tr>
	 <td>Phone Number: </td>
	 <td>" . $person[0] -> phone_number . "</td>
	 </tr>
	 <tr>
	 <td valign='top'>Join Comment: </td>
	 <td>" . $comment . "</td>
	 </tr>
	 </table>";

	 $mail = new PHPMailer;
	 $mail -> setFrom('contact@' . Config::get("app.host_uri"));
	 $mail -> addAddress($owner[0] -> email);
	 $mail -> Subject = "Christian Response: Someone joins an event.";
	 $mail -> msgHTML($body);
	 $mail -> AltBody = $body;
	 $mail -> send();
	 }
	 }

	 echo json_encode(array("success" => true));
	 }
	 */
	public function invite($type, $id) {
		$users = $_POST["user_ids"];
		$message = $_POST["message"];

		$project = DB::table($type) -> where("id", $id) -> first();
		$users = DB::table("users") -> whereIn("id", explode(",", $users)) -> get();

		$mail = new PHPMailer;
		$mail -> setFrom(Config::get("app.support_email"));

		foreach ($users as $one) :
			if ($one -> email != "") {
				$mail -> addAddress($one -> email);
			}
		endforeach;

		$body = "<style>
					* {
						font-family: Arial;
					}
					
					table {
						font-size: 12px;
					}
				</style>
				<h4>You're invited on a project in " . $project -> name . ".</h4>
				<table>
					<tr>
						<td>Project Name: </td>
						<td><a href='" . Config::get("app.url") . "/search/project/" . $type . "/view/" . $project -> id . "' target='_blank'>" . $project -> name . "</a></td>
					</tr>
					<tr>
						<td valign='top'>Message: </td>
						<td>" . $message . "</td>
					</tr>
				</table>";

		$mail -> Subject = "Christian Response: You're invited on a project.";
		$mail -> msgHTML($body);
		$mail -> AltBody = $body;
		$mail -> send();

		echo json_encode(array("success" => true));
	}

	public function invite_event($type, $id, $event_id) {
		$project = DB::table($type) -> where("id", $id) -> first();
		$project_event = DB::table($type . "_event") -> where("id", $event_id) -> first();
		$users = DB::table("users") -> where("permission", 100) -> where("email", "!=", "") -> where("id", "!=", Auth::user() -> id) -> get();

		$mail = new PHPMailer;
		$mail -> setFrom(Config::get("app.support_email"));

		foreach ($users as $one) :
			if ($one -> email != "") {
				$mail -> addAddress($one -> email);
			}
		endforeach;

		$body = "<style>
					* {
						font-family: Arial;
					}
					
					table {
						font-size: 12px;
					}
				</style>
				<h4>You're invited on a project event in " . $project_event -> title . ".</h4>
				<table>
					<tr>
						<td>Project Name: </td>
						<td><a href='" . Config::get("app.url") . "/search/project/" . $type . "/view/" . $project -> id . "' target='_blank'>" . $project -> name . "</a></td>
					</tr>
					<tr>
						<td>Event Title: </td>
						<td>" . $project_event -> title . "</td>
					</tr>
					<tr>
						<td valign='top'>Description: </td>
						<td>" . $project_event -> description . "</td>
					</tr>
					<tr>
						<td>Cost: </td>
						<td>" . $project_event -> cost . "</td>
					</tr>
					<tr>
						<td>Event Location: </td>
						<td>" . $project_event -> address . ", " . $project_event -> city . ", " . $project_event -> state . " " . $project_event -> zip_code . ", " . $project_event -> country . "</td>
					</tr>
					<tr>
						<td>Contact Details: </td>
						<td>" . $project_event -> contact_details . "</td>
					</tr>
					<tr>
						<td>Event Date: </td>
						<td>" . $project_event -> event_date . "</td>
					</tr>
				</table>";

		$mail -> Subject = "Christian Response: You're invited on a event in " . $project_event -> title . ".";
		$mail -> msgHTML($body);
		$mail -> AltBody = $body;
		$mail -> send();

		echo json_encode(array("success" => true));
	}

	public function donate($type, $id) {
		$message = "";
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$project = DB::table($type) -> where("id", $id) -> first();

			if ($project -> paypal_number != "" && filter_var($project -> paypal_number, FILTER_VALIDATE_EMAIL)) :

				include ("include/paypal/paypal.php");
				$project = DB::table($type) -> where("id", $id) -> first();

				$amount = Input::get("amount");
				$name = Input::get("donator_name");
				$email = Input::get("donator_email");

				$owner_email = $project -> paypal_number;
				$owner_amount = $amount * 0.925;

				$overall_email = Config::get("app.paypal_email");
				$overall_amount = $amount * 0.075;

				$transactionid = "TS-" . $this -> generate_rand(32);

				$return_url = Config::get("app.url") . "/project/" . $type . "/" . $id . "/donation/success/" . $transactionid . "/dashboard";
				$cancel_url = Config::get("app.url") . "/project/" . $type . "/" . $id . "/donation/cancel/" . $transactionid . "/dashboard";

				DB::table($type . "_transaction") -> insert(array("id" => $transactionid, "project_id" => $id, "amount" => $owner_amount, "user_id" => 0, "name" => $name, "email" => $email, "status" => -100, "created_date" => date("Y-m-d H:i:s")));
				DB::table("overall_transaction") -> insert(array("id" => null, "related_transaction_id" => $transactionid, "project_id" => $id, "project_type" => $type, "amount" => $overall_amount, "user_id" => 0, "name" => $name, "email" => $email, "status" => -100, "created_date" => date("Y-m-d H:i:s")));

				$paypal = new Paypal;
				$receiver = array( array("amount" => $owner_amount, "email" => $owner_email), array("amount" => $overall_amount, "email" => $overall_email));
				$item = array( array("name" => "Donation for " . $project -> name, "identifier" => "p1", "price" => $owner_amount, "itemPrice" => $owner_amount, "itemCount" => 1), array("name" => "Response for donation", "identifier" => "p2", "price" => $overall_amount, "itemPrice" => $overall_amount, "itemCount" => 1));
				$receiverOptions = array( array("receiver" => array("email" => $owner_email), "invoiceData" => array("item" => array( array("name" => "Donation for " . $project -> name, "price" => $owner_amount, "identifire" => "p1")))), array("receiver" => array("email" => $overall_email), "invoiceData" => array("item" => array( array("name" => "Responsive for donation", "price" => $overall_amount, "identifire" => "p2")))));
				$paypal -> splitPay($receiver, $item, $return_url, $cancel_url, $receiverOptions);
				exit ;
			else :
				$message = $this -> responsebox("Project payment source is not set yet.");
				Session::set("error", $message);
			endif;
		}
		$top_projects = DB::table("topproject") -> get();

		return Redirect::to("/project/view/" . $type . "/" . $id);
	}

	public function transactions($type, $id) {
		$project = DB::table($type) -> where("id", $id) -> first();
		$transactions = DB::table($type . "_transaction") -> where("project_id", $id) -> where("status", 1) -> orderby("created_date") -> get();

		$total = 0;
		foreach ($transactions as $one) :
			$total += $one -> amount;
		endforeach;

		return View::make("/frontend/" . $this -> _permission[Auth::user() -> permission] . "/project/transactions") -> with(array("active" => "projects", "project_title" => $project -> name, "type" => $type, "transactions" => $transactions, "total" => $total));
	}

	public function report_transactions($prefix, $id) {
		$project = DB::table($prefix) -> where("id", $id) -> first();
		$transactions = DB::table($prefix . "_transaction") -> where("project_id", $id) -> where("status", 1) -> orderby("created_date") -> get();

		$total = 0;
		foreach ($transactions as $one) :
			$total += $one -> amount;
		endforeach;

		return View::make("/frontend/" . $this -> _permission[Auth::user() -> permission] . "/project/transactions") -> with(array("active" => "projects", "project_title" => $project -> name, "type" => "report", "transactions" => $transactions, "total" => $total));
	}

	public function reviews($type, $id) {
		$project = DB::table($type) -> where("id", $id) -> first();
		$reviews = DB::table($type . "_review") -> where("project_id", $id) -> orderby("created_date") -> get();

		return View::make("/frontend/" . $this -> _permission[Auth::user() -> permission] . "/project/reviews") -> with(array("active" => "projects", "project_title" => $project -> name, "type" => $type, "reviews" => $reviews));
	}

	public function report_reviews($prefix, $id) {
		$project = DB::table($prefix) -> where("id", $id) -> first();
		$reviews = DB::table($prefix . "_review") -> where("project_id", $id) -> orderby("created_date") -> get();

		return View::make("/frontend/" . $this -> _permission[Auth::user() -> permission] . "/project/reviews") -> with(array("active" => "projects", "project_title" => $project -> name, "type" => "report", "reviews" => $reviews));
	}

}
