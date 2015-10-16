<?php

class DashboardController extends BaseController {

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
		$following_sql = "SELECT 	a.id, a.name, a.thumbnail, 
								(CASE WHEN AVG(b.mark) IS NULL THEN 0 ELSE AVG(b.mark) END) AS review,
								(select COUNT(user_id) from prayer_follow where project_id = a.id) AS follow_count,
								a.created_date,
								'prayer' AS project_type
							FROM 	prayer a
								LEFT JOIN prayer_review b ON a.id = b.project_id
								LEFT JOIN prayer_follow c ON a.id = c.project_id,
								prayer_follow d, region_manager e, region_allocate f
							WHERE 	a.status = 1
								AND e.id = f.region_id
								AND f.project_id = a.id
								AND f.project_type = 'prayer'
								AND a.id = d.project_id
								AND d.user_id = " . Auth::user() -> id . "
							GROUP BY a.id
							UNION
							SELECT 	a.id, a.name, a.thumbnail, 
								(CASE WHEN AVG(b.mark) IS NULL THEN 0 ELSE AVG(b.mark) END) AS review,
								(select COUNT(user_id) from impact_follow where project_id = a.id) AS follow_count,
								a.created_date,
								'impact' AS project_type
							FROM 	impact a
								LEFT JOIN impact_review b ON a.id = b.project_id
								LEFT JOIN impact_follow c ON a.id = c.project_id,
								impact_follow d, region_manager e, region_allocate f
							WHERE 	a.status = 1
								AND e.id = f.region_id
								AND f.project_id = a.id
								AND f.project_type = 'impact'
								AND a.id = d.project_id
								AND d.user_id = " . Auth::user() -> id . "
							GROUP BY a.id
							UNION
							SELECT 	a.id, a.name, a.thumbnail, 
								(CASE WHEN AVG(b.mark) IS NULL THEN 0 ELSE AVG(b.mark) END) AS review,
								(select COUNT(user_id) from nationalreport_follow where project_id = a.id) AS follow_count,
								a.created_date,
								'nationalreport' AS project_type
							FROM 	nationalreport a
								LEFT JOIN nationalreport_review b ON a.id = b.project_id
								LEFT JOIN nationalreport_follow c ON a.id = c.project_id,
								nationalreport_follow d, region_manager e, region_allocate f
							WHERE 	a.status = 1
								AND e.id = f.region_id
								AND f.project_id = a.id
								AND f.project_type = 'nationalreport'
								AND a.id = d.project_id
								AND d.user_id = " . Auth::user() -> id . "
							GROUP BY a.id
							UNION
							SELECT 	a.id, a.name, a.thumbnail, 
								(CASE WHEN AVG(b.mark) IS NULL THEN 0 ELSE AVG(b.mark) END) AS review,
								(select COUNT(user_id) from regionalreport_follow where project_id = a.id) AS follow_count,
								a.created_date,
								'regionalreport' AS project_type
							FROM 	regionalreport a
								LEFT JOIN regionalreport_review b ON a.id = b.project_id
								LEFT JOIN regionalreport_follow c ON a.id = c.project_id,
								regionalreport_follow d, region_manager e, region_allocate f
							WHERE 	a.status = 1
								AND e.id = f.region_id
								AND f.project_id = a.id
								AND f.project_type = 'regionalreport'
								AND a.id = d.project_id
								AND d.user_id = " . Auth::user() -> id . "
							GROUP BY a.id
							UNION
							SELECT 	a.id, a.name, a.thumbnail, 
								(CASE WHEN AVG(b.mark) IS NULL THEN 0 ELSE AVG(b.mark) END) AS review,
								(select COUNT(user_id) from teaching_follow where project_id = a.id) AS follow_count,
								a.created_date,
								'teaching' AS project_type
							FROM 	teaching a
								LEFT JOIN teaching_review b ON a.id = b.project_id
								LEFT JOIN teaching_follow c ON a.id = c.project_id,
								teaching_follow d, region_manager e, region_allocate f
							WHERE 	a.status = 1
								AND e.id = f.region_id
								AND f.project_id = a.id
								AND f.project_type = 'teaching'
								AND a.id = d.project_id
								AND d.user_id = " . Auth::user() -> id . "
							GROUP BY a.id
							ORDER BY review DESC, created_date DESC";

		$facilitating_sql = "SELECT 	a.id, a.name, a.thumbnail, 
								(CASE WHEN AVG(b.mark) IS NULL THEN 0 ELSE AVG(b.mark) END) AS review,
								(select COUNT(user_id) from prayer_follow where project_id = a.id) AS follow_count,
								a.created_date,
								'prayer' AS project_type
							FROM 	prayer a
								LEFT JOIN prayer_review b ON a.id = b.project_id
								LEFT JOIN prayer_follow c ON a.id = c.project_id,
								region_manager e, region_allocate f
							WHERE 	a.status = 1
								AND e.id = f.region_id
								AND f.project_id = a.id
								AND f.project_type = 'prayer'
								AND a.user_id = " . Auth::user() -> id . "
							GROUP BY a.id
							UNION
							SELECT 	a.id, a.name, a.thumbnail, 
								(CASE WHEN AVG(b.mark) IS NULL THEN 0 ELSE AVG(b.mark) END) AS review,
								(select COUNT(user_id) from impact_follow where project_id = a.id) AS follow_count,
								a.created_date,
								'impact' AS project_type
							FROM 	impact a
								LEFT JOIN impact_review b ON a.id = b.project_id
								LEFT JOIN impact_follow c ON a.id = c.project_id,
								region_manager e, region_allocate f
							WHERE 	a.status = 1
								AND e.id = f.region_id
								AND f.project_id = a.id
								AND f.project_type = 'impact'
								AND a.user_id = " . Auth::user() -> id . "
							GROUP BY a.id
							UNION
							SELECT 	a.id, a.name, a.thumbnail, 
								(CASE WHEN AVG(b.mark) IS NULL THEN 0 ELSE AVG(b.mark) END) AS review,
								(select COUNT(user_id) from nationalreport_follow where project_id = a.id) AS follow_count,
								a.created_date,
								'nationalreport' AS project_type
							FROM 	nationalreport a
								LEFT JOIN nationalreport_review b ON a.id = b.project_id
								LEFT JOIN nationalreport_follow c ON a.id = c.project_id,
								region_manager e, region_allocate f
							WHERE 	a.status = 1
								AND e.id = f.region_id
								AND f.project_id = a.id
								AND f.project_type = 'nationalreport'
								AND a.user_id = " . Auth::user() -> id . "
							GROUP BY a.id
							UNION
							SELECT 	a.id, a.name, a.thumbnail, 
								(CASE WHEN AVG(b.mark) IS NULL THEN 0 ELSE AVG(b.mark) END) AS review,
								(select COUNT(user_id) from regionalreport_follow where project_id = a.id) AS follow_count,
								a.created_date,
								'regionalreport' AS project_type
							FROM 	regionalreport a
								LEFT JOIN regionalreport_review b ON a.id = b.project_id
								LEFT JOIN regionalreport_follow c ON a.id = c.project_id,
								region_manager e, region_allocate f
							WHERE 	a.status = 1
								AND e.id = f.region_id
								AND f.project_id = a.id
								AND f.project_type = 'regionalreport'
								AND a.user_id = " . Auth::user() -> id . "
							GROUP BY a.id
							UNION
							SELECT 	a.id, a.name, a.thumbnail, 
								(CASE WHEN AVG(b.mark) IS NULL THEN 0 ELSE AVG(b.mark) END) AS review,
								(select COUNT(user_id) from teaching_follow where project_id = a.id) AS follow_count,
								a.created_date,
								'teaching' AS project_type
							FROM 	teaching a
								LEFT JOIN teaching_review b ON a.id = b.project_id
								LEFT JOIN teaching_follow c ON a.id = c.project_id,
								region_manager e, region_allocate f
							WHERE 	a.status = 1
								AND e.id = f.region_id
								AND f.project_id = a.id
								AND f.project_type = 'teaching'
								AND a.user_id = " . Auth::user() -> id . "
							GROUP BY a.id
							ORDER BY review DESC, created_date DESC";

		$joined = DB::select("SELECT 	a.id AS project_id, a.name AS project_title, b.id AS event_id, b.title AS event_title, b.address, b.city, b.state, b.zip_code, b.country, b.event_date, 'prayer' as type
								FROM 	prayer a, prayer_event b, prayer_event_join c, region_manager e, region_allocate f
								WHERE 	a.id = b.project_id AND b.id = c.event_id
									AND e.id = f.region_id
									AND f.project_id = a.id
									AND f.project_type = 'prayer'
									AND b.id = c.event_id
									AND c.user_id = " . Auth::user() -> id . "
									and a.status = 1 and b.status = 1 AND c.status = 1
								GROUP BY b.id
							UNION
							SELECT 	a.id AS project_id, a.name AS project_title, b.id AS event_id, b.title AS event_title, b.address, b.city, b.state, b.zip_code, b.country, b.event_date, 'impact' as type
								FROM 	impact a, impact_event b, impact_event_join c, region_manager e, region_allocate f
								WHERE 	a.id = b.project_id AND b.id = c.event_id
									AND e.id = f.region_id
									AND f.project_id = a.id
									AND f.project_type = 'impact'
									AND b.id = c.event_id
									AND c.user_id = " . Auth::user() -> id . "
									and a.status = 1 and b.status = 1 AND c.status = 1
								GROUP BY b.id
							UNION
							SELECT 	a.id AS project_id, a.name AS project_title, b.id AS event_id, b.title AS event_title, b.address, b.city, b.state, b.zip_code, b.country, b.event_date, 'regionalreport' as type
								FROM 	regionalreport a, regionalreport_event b, regionalreport_event_join c, region_manager e, region_allocate f
								WHERE 	a.id = b.project_id AND b.id = c.event_id
									AND e.id = f.region_id
									AND f.project_id = a.id
									AND f.project_type = 'regionalreport'
									AND b.id = c.event_id
									AND c.user_id = " . Auth::user() -> id . "
									and a.status = 1 and b.status = 1 AND c.status = 1
								GROUP BY b.id
							UNION
							SELECT 	a.id AS project_id, a.name AS project_title, b.id AS event_id, b.title AS event_title, b.address, b.city, b.state, b.zip_code, b.country, b.event_date, 'nationalreport' as type
								FROM 	nationalreport a, nationalreport_event b, nationalreport_event_join c, region_manager e, region_allocate f
								WHERE 	a.id = b.project_id AND b.id = c.event_id
									AND e.id = f.region_id
									AND f.project_id = a.id
									AND f.project_type = 'nationalreport'
									AND b.id = c.event_id
									AND c.user_id = " . Auth::user() -> id . "
									and a.status = 1 and b.status = 1 AND c.status = 1
								GROUP BY b.id
							UNION
							SELECT 	a.id AS project_id, a.name AS project_title, b.id AS event_id, b.title AS event_title, b.address, b.city, b.state, b.zip_code, b.country, b.event_date, 'teaching' as type
								FROM 	teaching a, teaching_event b, teaching_event_join c, region_manager e, region_allocate f
								WHERE 	a.id = b.project_id AND b.id = c.event_id
									AND e.id = f.region_id
									AND f.project_id = a.id
									AND f.project_type = 'teaching'
									AND b.id = c.event_id
									AND c.user_id = " . Auth::user() -> id . "
									and a.status = 1 and b.status = 1 AND c.status = 1
								GROUP BY b.id
							ORDER BY event_date DESC");

		$users = DB::table("users") -> where("status", 1) -> where("permission", 100) -> where("id", "!=", Auth::user() -> id) -> orderby("first_name") -> orderby("last_name") -> get();

		switch(Auth::user() -> permission) {
			case -1 :
				return View::make("frontend/overall/dashboard") -> with(array("active" => "dashboard"));
				break;
			case -2 :
				return View::make("frontend/region/dashboard") -> with(array("active" => "dashboard", "users" => $users, "events" => $joined, "followings" => DB::select($following_sql), "facilitatings" => DB::select($facilitating_sql)));
				break;
			case -3 :
				$owners = DB::select("SELECT 	a.id, a.first_name, a.last_name
							FROM 	(SELECT DISTINCT user_id FROM impact WHERE STATUS = 1
								UNION DISTINCT 
								SELECT DISTINCT user_id FROM prayer WHERE STATUS = 1
								UNION DISTINCT 
								SELECT DISTINCT user_id FROM teaching WHERE STATUS = 1
								UNION DISTINCT 
								SELECT DISTINCT user_id FROM nationalreport WHERE STATUS = 1
								UNION DISTINCT 
								SELECT DISTINCT user_id FROM regionalreport WHERE STATUS = 1) src, users a
							WHERE a.id = src.user_id
							ORDER BY a.first_name, a.last_name");
		
				return View::make("frontend/general/dashboard") -> with(array("active" => "dashboard", "owners" => $owners));
				break;
			case 100 :
				return View::make("frontend/user/dashboard") -> with(array("active" => "dashboard", "users" => $users, "events" => $joined, "followings" => DB::select($following_sql), "facilitatings" => DB::select($facilitating_sql)));
				break;
			default :
				break;
		}
	}

	public function project_view($type, $id) {
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$action = Input::get("action");

			switch($action) :
				case 'following' :
					$flag = Input::get("following");
					if ($flag == -1) {// unfollowing
						DB::table($type . "_follow") -> where("project_id", $id) -> where("user_id", Auth::user() -> id) -> delete();
					} else {// following
						DB::table($type . "_follow") -> insert(array("project_id" => $id, "user_id" => Auth::user() -> id));
						$this -> send_following_email($type, $id);
					}
					break;
				case 'feedback' :
					$mark1 = Input::get("feedback_score1") > 0 ? Input::get("feedback_score1") : 0;
					$mark2 = Input::get("feedback_score2") > 0 ? Input::get("feedback_score2") : 0;
					$mark3 = Input::get("feedback_score3") > 0 ? Input::get("feedback_score3") : 0;
					$mark4 = Input::get("feedback_score4") > 0 ? Input::get("feedback_score4") : 0;
					$mark5 = Input::get("feedback_score5") > 0 ? Input::get("feedback_score5") : 0;

					$mark = round(($mark1 + $mark2 + $mark3 + $mark4 + $mark5) / 5, 1);
					$text = "$mark1,$mark2,$mark3,$mark4,$mark5";

					DB::table($type . "_review") -> insert(array("id" => null, "project_id" => $id, "name" => Auth::user() -> first_name . " " . Auth::user() -> last_name, "email" => Auth::user() -> email, "comment" => $text, "mark" => $mark, "user_id" => Auth::user() -> id, "created_date" => date("Y-m-d H:i:s")));
					break;
				case 'communication' :
					$text = Input::get("communication_text");

					DB::table($type . "_communication") -> insert(array("id" => null, "project_id" => $id, "name" => Auth::user() -> first_name . " " . Auth::user() -> last_name, "email" => Auth::user() -> email, "text" => $text, "picture" => Auth::user() -> picture, "created_date" => date("Y-m-d H:i:s")));

					$project = DB::table($type) -> where("id", $id) -> first();
					$target = DB::select("select a.first_name, a.last_name, a.email from users a, " . $type . "_follow b where a.id = b.user_id and b.project_id = $id");

					$mail = new PHPMailer;
					$mail -> setFrom(Config::get("app.support_email"));

					foreach ($target as $one) :
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
						<h4>Someone posted new message.</h4>
						<table>
							<tr>
								<td>Project Name: </td>
								<td><a href='" . Config::get("app.url") . "/search/project/" . $type . "/view/" . $project -> id . "' target='_blank'>" . $project -> name . "</a></td>
							</tr>
							<tr>
								<td valign='top'>Message: </td>
								<td>" . $text . "</td>
							</tr>
						</table>";

					$mail -> Subject = "Christian Response: Project facilitator posted a news.";
					$mail -> msgHTML($body);
					$mail -> AltBody = $body;
					$mail -> send();
					break;
				case 'hug' :
					$text = Input::get("hug_comment");

					DB::table($type . "_hug") -> insert(array("id" => null, "project_id" => $id, "name" => Auth::user() -> first_name . " " . Auth::user() -> last_name, "email" => Auth::user() -> email, "text" => $text, "picture" => Auth::user() -> picture, "created_date" => date("Y-m-d H:i:s")));

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
									<td>" . Auth::user() -> first_name . " " . Auth::user() -> last_name . "</td>
								</tr>
								<tr>
									<td>Email Address: </td>
									<td>" . Auth::user() -> email . "</td>
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
					break;
				case 'join' :
					$event_id = Input::get("selected_event_id");
					$comment = Input::get("event-join-comment");

					$event = DB::table($type . "_event") -> where("id", $event_id) -> first();

					if ($event -> cost == 0) {
						DB::table($type . "_event_join") -> insertGetId(array("id" => null, "event_id" => $event_id, "user_id" => Auth::user() -> id, "comment" => $comment, "created_date" => date("Y-m-d H:i:s"), "status" => "1"));
					} else {
						include ("include/paypal/paypal.php");

						$overall_email = Config::get("app.paypal_email");
						$owner = DB::table($type) -> where("id", $id) -> first();
						$owner_email = $owner -> paypal_number;

						if ($overall_email != "" && filter_var($overall_email, FILTER_VALIDATE_EMAIL) && $owner_email != "" && filter_var($owner_email, FILTER_VALIDATE_EMAIL)) {
							$transactonid = "TS-EV-" . $this -> generate_rand(32);
							DB::table($type . "_event_join") -> insertGetId(array("id" => null, "event_id" => $event_id, "user_id" => Auth::user() -> id, "comment" => $comment, "created_date" => date("Y-m-d H:i:s"), "status" => "-100"));
							DB::table($type . "_event_transaction") -> insert(array("id" => $transactonid, "event_id" => $event_id, "amount" => $event -> cost * 0.925, "user_id" => Auth::user() -> id, "name" => Auth::user() -> first_name . " " . Auth::user() -> last_name, "email" => Auth::user() -> email, "status" => -100, "created_date" => date("Y-m-d H:i:s")));
							DB::table("overall_event_transaction") -> insert(array("id" => null, "related_transaction_id" => $transactonid, "project_id" => $id, "event_id" => $event_id, "project_type" => $type, "amount" => $event -> cost * 0.075, "user_id" => Auth::user() -> id, "name" => Auth::user() -> first_name . " " . Auth::user() -> last_name, "email" => Auth::user() -> email, "status" => -100, "created_date" => date("Y-m-d H:i:s"), ));

							$return_url = Config::get("app.url") . "/dashboard/project/" . $type . "/" . $id . "/event/" . $event_id . "/join/success/" . $transactonid;
							$cancel_url = Config::get("app.url") . "/dashboard/project/" . $type . "/" . $id . "/event/" . $event_id . "/join/cancel/" . $transactonid;

							$paypal = new Paypal;
							$receiver = array( array("amount" => $event -> cost * 0.925, "email" => $owner_email), array("amount" => $event -> cost * 0.075, "email" => $overall_email));
							$item = array( array("name" => "Join event", "identifier" => "p1", "price" => $event -> cost * 0.925, "itemPrice" => $event -> cost * 0.925, "itemCount" => 1), array("name" => "Response for joining event", "identifier" => "p2", "price" => $event -> cost * 0.075, "itemPrice" => $event -> cost * 0.075, "itemCount" => 1));
							$receiverOptions = array( array("receiver" => array("email" => $owner_email), "invoiceData" => array("item" => array( array("name" => "Join event", "price" => $event -> cost * 0.925, "identifire" => "p1")))), array("receiver" => array("email" => $overall_email), "invoiceData" => array("item" => array( array("name" => "Responsive for joining event", "price" => $event -> cost * 0.075, "identifire" => "p2")))));
							$paypal -> splitPay($receiver, $item, $return_url, $cancel_url, $receiverOptions);
							exit ;
						} else {
							$error = "<div class='alert alert-danger alert-dismissable'>
			                            <button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button>
			                            Paypal address is not set yet.
			                        </div>";
							Session::set("error", $error);
						}
					}
					break;
				case 'donation' :
					$amount = Input::get("amount");
					$this -> project_donation($type, $id, $amount, "dashboard");
					break;
			endswitch;

			return Redirect::to("/dashboard/project/view/" . $type . "/" . $id);
		}

		$sql = "SELECT 	a.*,
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
		$basic = DB::select($sql);
		
		$feedback = DB::table($type . "_review") -> where("user_id", Auth::user() -> id) -> where("project_id", $id) -> first();

		$sql = "select count(*) as count from " . $type . "_review where project_id = " . $id;
		$total_review = DB::select($sql);

		$sql = "SELECT 	a.id, a.first_name, a.last_name, a.email, b.address, b.city, b.state, b.zip_code, b.country, b.phone_number, a.picture,
					(SELECT COUNT(*) FROM prayer WHERE user_id = a.id and status = 1) + 
					(SELECT COUNT(*) FROM impact WHERE user_id = a.id and status = 1) + 
					(SELECT COUNT(*) FROM nationalreport WHERE user_id = a.id and status = 1) + 
					(SELECT COUNT(*) FROM regionalreport WHERE user_id = a.id and status = 1) + 
					(SELECT COUNT(*) FROM teaching WHERE user_id = a.id and status = 1) AS total_project,
					(SELECT COUNT(*) FROM prayer_follow WHERE user_id = a.id) + 
					(SELECT COUNT(*) FROM impact_follow WHERE user_id = a.id) + 
					(SELECT COUNT(*) FROM nationalreport_follow WHERE user_id = a.id) + 
					(SELECT COUNT(*) FROM regionalreport_follow WHERE user_id = a.id) + 
					(SELECT COUNT(*) FROM teaching_follow WHERE user_id = a.id) AS total_following
				FROM 	users a, user_profile b
				WHERE 	a.id = b.user_id
					AND a.id = " . $basic[0] -> user_id;
		$owner = DB::select($sql);

		$region_manager = DB::select("select count(*) as count 
										from region_manager a, region_allocate b 
										where a.id = b.region_id
											and b.project_id = " . $id . "
											and b.project_type = '" . $type . "'
											and a.user_id = " . Auth::user() -> id);

		$communications = DB::table($type . "_communication") -> where("project_id", $id) -> orderby("created_date", "desc") -> get();

		$sql = "SELECT 	a.id, a.title, a.event_date, CASE WHEN b.user_id IS NULL THEN 0 ELSE 1 END AS is_joined
				FROM 	" . $type . "_event a
					LEFT JOIN " . $type . "_event_join b ON a.id = b.event_id AND b.user_id = " . Auth::user() -> id . " and b.status = 1
				WHERE	a.project_id = " . $id . " and a.status = 1
				GROUP BY a.id";
		$events = DB::select($sql);

		return View::make("frontend/user/" . $type . "_view") -> with(array("active" => "dashboard", "is_region_manager" => $region_manager[0] -> count, "project_id" => $id, "feedback" => $feedback, "basic" => $basic[0], "owner" => $owner[0], "total_review" => $total_review[0], "communications" => $communications, "events" => $events, "redirect_url" => "/dashboard", "picture" => $basic[0] -> thumbnail != "" ? $basic[0] -> thumbnail : Config::get("app.url") . "/images/facebook/global_compact-icon-1365540187.png", "share_link" => Config::get("app.url") . "/project/view/" . $type . "/" . $id));
	}

	public function search($type) {
		$key = $_POST["key"];
		$result = array();

		if ($type == "following") {
			$sql = "SELECT 	a.id, a.name, a.thumbnail, 
						(CASE WHEN AVG(b.mark) IS NULL THEN 0 ELSE AVG(b.mark) END) AS review,
						(select COUNT(user_id) from prayer_follow where project_id = a.id) AS follow_count,
						a.created_date,
						'prayer' AS project_type
					FROM 	prayer a
						LEFT JOIN prayer_review b ON a.id = b.project_id
						LEFT JOIN prayer_follow c ON a.id = c.project_id,
						prayer_follow d
					WHERE 	a.status = 1
						AND a.id = d.project_id
						AND d.user_id = " . Auth::user() -> id . "
						AND (a.name like '%$key%' or a.description like '%$key%')
					GROUP BY a.id
					UNION
					SELECT 	a.id, a.name, a.thumbnail, 
						(CASE WHEN AVG(b.mark) IS NULL THEN 0 ELSE AVG(b.mark) END) AS review,
						(select COUNT(user_id) from impact_follow where project_id = a.id) AS follow_count,
						a.created_date,
						'impact' AS project_type
					FROM 	impact a
						LEFT JOIN impact_review b ON a.id = b.project_id
						LEFT JOIN impact_follow c ON a.id = c.project_id,
						impact_follow d
					WHERE 	a.status = 1
						AND a.id = d.project_id
						AND d.user_id = " . Auth::user() -> id . "
						AND (a.name like '%$key%' or a.description like '%$key%')
					GROUP BY a.id
					UNION
					SELECT 	a.id, a.name, a.thumbnail, 
						(CASE WHEN AVG(b.mark) IS NULL THEN 0 ELSE AVG(b.mark) END) AS review,
						(select COUNT(user_id) from nationalreport_follow where project_id = a.id) AS follow_count,
						a.created_date,
						'nationalreport' AS project_type
					FROM 	nationalreport a
						LEFT JOIN nationalreport_review b ON a.id = b.project_id
						LEFT JOIN nationalreport_follow c ON a.id = c.project_id,
						nationalreport_follow d
					WHERE 	a.status = 1
						AND a.id = d.project_id
						AND d.user_id = " . Auth::user() -> id . "
						AND (a.name like '%$key%' or a.description like '%$key%')
					GROUP BY a.id
					UNION
					SELECT 	a.id, a.name, a.thumbnail, 
						(CASE WHEN AVG(b.mark) IS NULL THEN 0 ELSE AVG(b.mark) END) AS review,
						(select COUNT(user_id) from regionalreport_follow where project_id = a.id) AS follow_count,
						a.created_date,
						'regionalreport' AS project_type
					FROM 	regionalreport a
						LEFT JOIN regionalreport_review b ON a.id = b.project_id
						LEFT JOIN regionalreport_follow c ON a.id = c.project_id,
						regionalreport_follow d
					WHERE 	a.status = 1
						AND a.id = d.project_id
						AND d.user_id = " . Auth::user() -> id . "
						AND (a.name like '%$key%' or a.description like '%$key%')
					GROUP BY a.id
					UNION
					SELECT 	a.id, a.name, a.thumbnail, 
						(CASE WHEN AVG(b.mark) IS NULL THEN 0 ELSE AVG(b.mark) END) AS review,
						(select COUNT(user_id) from teaching_follow where project_id = a.id) AS follow_count,
						a.created_date,
						'teaching' AS project_type
					FROM 	teaching a
						LEFT JOIN teaching_review b ON a.id = b.project_id
						LEFT JOIN teaching_follow c ON a.id = c.project_id,
						teaching_follow d
					WHERE 	a.status = 1
						AND a.id = d.project_id
						AND d.user_id = " . Auth::user() -> id . "
						AND (a.name like '%$key%' or a.description like '%$key%')
					GROUP BY a.id
					ORDER BY review DESC, created_date DESC";
		} elseif ($type == "facilitating") {
			$sql = "SELECT 	a.id, a.name, a.thumbnail, 
						(CASE WHEN AVG(b.mark) IS NULL THEN 0 ELSE AVG(b.mark) END) AS review,
						(select COUNT(user_id) from prayer_follow where project_id = a.id) AS follow_count,
						a.created_date,
						'prayer' AS project_type
					FROM 	prayer a
						LEFT JOIN prayer_review b ON a.id = b.project_id
						LEFT JOIN prayer_follow c ON a.id = c.project_id
					WHERE 	a.status = 1
						AND a.user_id = " . Auth::user() -> id . "
						AND (a.name like '%$key%' or a.description like '%$key%')
					GROUP BY a.id
					UNION
					SELECT 	a.id, a.name, a.thumbnail, 
						(CASE WHEN AVG(b.mark) IS NULL THEN 0 ELSE AVG(b.mark) END) AS review,
						(select COUNT(user_id) from impact_follow where project_id = a.id) AS follow_count,
						a.created_date,
						'impact' AS project_type
					FROM 	impact a
						LEFT JOIN impact_review b ON a.id = b.project_id
						LEFT JOIN impact_follow c ON a.id = c.project_id
					WHERE 	a.status = 1
						AND a.user_id = " . Auth::user() -> id . "
						AND (a.name like '%$key%' or a.description like '%$key%')
					GROUP BY a.id
					UNION
					SELECT 	a.id, a.name, a.thumbnail, 
						(CASE WHEN AVG(b.mark) IS NULL THEN 0 ELSE AVG(b.mark) END) AS review,
						(select COUNT(user_id) from nationalreport_follow where project_id = a.id) AS follow_count,
						a.created_date,
						'nationalreport' AS project_type
					FROM 	nationalreport a
						LEFT JOIN nationalreport_review b ON a.id = b.project_id
						LEFT JOIN nationalreport_follow c ON a.id = c.project_id
					WHERE 	a.status = 1
						AND a.user_id = " . Auth::user() -> id . "
						AND (a.name like '%$key%' or a.description like '%$key%')
					GROUP BY a.id
					UNION
					SELECT 	a.id, a.name, a.thumbnail, 
						(CASE WHEN AVG(b.mark) IS NULL THEN 0 ELSE AVG(b.mark) END) AS review,
						(select COUNT(user_id) from regionalreport_follow where project_id = a.id) AS follow_count,
						a.created_date,
						'regionalreport' AS project_type
					FROM 	regionalreport a
						LEFT JOIN regionalreport_review b ON a.id = b.project_id
						LEFT JOIN regionalreport_follow c ON a.id = c.project_id
					WHERE 	a.status = 1
						AND a.user_id = " . Auth::user() -> id . "
						AND (a.name like '%$key%' or a.description like '%$key%')
					GROUP BY a.id
					UNION
					SELECT 	a.id, a.name, a.thumbnail, 
						(CASE WHEN AVG(b.mark) IS NULL THEN 0 ELSE AVG(b.mark) END) AS review,
						(select COUNT(user_id) from teaching_follow where project_id = a.id) AS follow_count,
						a.created_date,
						'teaching' AS project_type
					FROM 	teaching a
						LEFT JOIN teaching_review b ON a.id = b.project_id
						LEFT JOIN teaching_follow c ON a.id = c.project_id
					WHERE 	a.status = 1
						AND a.user_id = " . Auth::user() -> id . "
						AND (a.name like '%$key%' or a.description like '%$key%')
					GROUP BY a.id
					ORDER BY review DESC, created_date DESC";
		}

		$result = DB::select($sql);
		echo json_encode(array("result" => $result));
	}

}
