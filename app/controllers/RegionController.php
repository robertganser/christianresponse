<?php

class RegionController extends BaseController {

	/*
	 |--------------------------------------------------------------------------
	 | Default Home Controller
	 |--------------------------------------------------------------------------
	 |
	 | You may wish to use controllers instead of, or in addition to, Closure
	 | based routes. That's great! Here is an example controller method to
	 | get you started. To route to this controller, just add the route:
	 |
	 |	Route::get('/', 'RegionController@showWelcome');
	 |
	 */

	public function region($date = "", $region_id = 0) {
		$date = $date == "" ? date("Y-m-d") : $date;
		$regions = DB::select("SELECT 	a.id AS region_id, 
									CASE WHEN b.title IS NULL THEN CONCAT(a.country, ', ', a.state) ELSE b.title END AS title, b.intro_video
								FROM 	region_manager a
									LEFT JOIN region_page b ON a.id = b.region_id
								ORDER BY title");

		if (count($regions) == 0) {
			return View::make("/frontend/" . $this -> _permission[Auth::user() -> permission] . "/region/not_found") -> with(array("active" => "region"));
			exit ;
		}

		$me = DB::table("user_profile") -> where("user_id", Auth::user() -> id) -> first();

		$title = "Default Region";
		$video = "";
		$related_report_id = "";
		$memo = "";

		$my_region = DB::select("SELECT 	a.id AS region_id, 
									CASE WHEN b.title IS NULL THEN CONCAT(a.country, ', ', a.state) ELSE b.title END AS title, b.intro_video
								FROM 	region_manager a
									LEFT JOIN region_page b ON a.id = b.region_id
								WHERE	a.country = ? and a.state = ?
								ORDER BY title", array($me -> country, $me -> state));
		if ($region_id == 0) {
			if (!empty($my_region)) {
				$region_id = $my_region[0] -> region_id;
			} else {
				return View::make("/frontend/" . $this -> _permission[Auth::user() -> permission] . "/region/not_found") -> with(array("active" => "region", "regions" => $regions, "region_id" => 0, "curr_date" => $date));
				exit ;
			}
		}

		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$action = Input::get("action");

			switch($action) :
				case 'join' :
					$project_id = Input::get("selected_project_id");
					$event_id = Input::get("selected_event_id");
					$type = Input::get("selected_event_type");
					$comment = Input::get("event-join-comment");

					if ($type == "annual") {
						$event = DB::table("region_annual_event") -> where("id", $event_id) -> where("region_id", $region_id) -> first();

						if ($event -> cost == 0) {
							DB::table("region_annual_event_join") -> insertGetId(array("id" => null, "event_id" => $event_id, "user_id" => Auth::user() -> id, "comment" => $comment, "created_date" => date("Y-m-d H:i:s"), "status" => "1"));
						} else {
							include ("include/paypal/paypal.php");

							$overall_email = Config::get("app.paypal_email");
							$owner = DB::select("select a.email from users a, region_manager b where a.id = b.user_id and b.id = ?", array($region_id));
							$owner_email = $owner[0] -> email;

							if ($overall_email != "" && filter_var($overall_email, FILTER_VALIDATE_EMAIL) && $owner_email != "" && filter_var($owner_email, FILTER_VALIDATE_EMAIL)) {
								$transactonid = "TS-AN-" . $this -> generate_rand(32);
								DB::table("region_annual_event_join") -> insertGetId(array("id" => null, "event_id" => $event_id, "user_id" => Auth::user() -> id, "comment" => $comment, "created_date" => date("Y-m-d H:i:s"), "status" => "-100"));
								DB::table("region_annual_event_transaction") -> insert(array("id" => $transactonid, "event_id" => $event_id, "amount" => $event -> cost * 0.925, "user_id" => Auth::user() -> id, "name" => Auth::user() -> first_name . " " . Auth::user() -> last_name, "email" => Auth::user() -> email, "status" => -100, "created_date" => date("Y-m-d H:i:s")));
								DB::table("overall_event_transaction") -> insert(array("id" => null, "related_transaction_id" => $transactonid, "project_id" => $region_id, "event_id" => $event_id, "project_type" => $type, "amount" => $event -> cost * 0.075, "user_id" => Auth::user() -> id, "name" => Auth::user() -> first_name . " " . Auth::user() -> last_name, "email" => Auth::user() -> email, "status" => -100, "created_date" => date("Y-m-d H:i:s")));

								$return_url = Config::get("app.url") . "/region/" . $date . "/" . $region_id . "/" . $type . "/" . $project_id . "/" . $event_id . "/join/success/" . $transactonid;
								$cancel_url = Config::get("app.url") . "/region/" . $date . "/" . $region_id . "/" . $type . "/" . $project_id . "/" . $event_id . "/join/cancel/" . $transactonid;

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
					} else {
						$event = DB::table($type . "_event") -> where("id", $event_id) -> first();

						if ($event -> cost == 0) {
							DB::table($type . "_event_join") -> insertGetId(array("id" => null, "event_id" => $event_id, "user_id" => Auth::user() -> id, "comment" => $comment, "created_date" => date("Y-m-d H:i:s"), "status" => "1"));
						} else {
							include ("include/paypal/paypal.php");

							$overall_email = Config::get("app.paypal_email");
							$owner = DB::table($type) -> where("id", $event -> project_id) -> first();
							$owner_email = $owner -> paypal_number;
							if ($overall_email != "" && filter_var($overall_email, FILTER_VALIDATE_EMAIL) && $owner_email != "" && filter_var($owner_email, FILTER_VALIDATE_EMAIL)) {
								$transactonid = "TS-EV-" . $this -> generate_rand(32);
								DB::table($type . "_event_join") -> insertGetId(array("id" => null, "event_id" => $event_id, "user_id" => Auth::user() -> id, "comment" => $comment, "created_date" => date("Y-m-d H:i:s"), "status" => "-100"));
								DB::table($type . "_event_transaction") -> insert(array("id" => $transactonid, "event_id" => $event_id, "amount" => $event -> cost * 0.925, "user_id" => Auth::user() -> id, "name" => Auth::user() -> first_name . " " . Auth::user() -> last_name, "email" => Auth::user() -> email, "status" => -100, "created_date" => date("Y-m-d H:i:s")));
								DB::table("overall_event_transaction") -> insert(array("id" => null, "related_transaction_id" => $transactonid, "project_id" => $event -> project_id, "event_id" => $event_id, "project_type" => $type, "amount" => $event -> cost * 0.075, "user_id" => Auth::user() -> id, "name" => Auth::user() -> first_name . " " . Auth::user() -> last_name, "email" => Auth::user() -> email, "status" => -100, "created_date" => date("Y-m-d H:i:s"), ));

								$return_url = Config::get("app.url") . "/region/" . $date . "/" . $region_id . "/" . $type . "/" . $project_id . "/" . $event_id . "/join/success/" . $transactonid;
								$cancel_url = Config::get("app.url") . "/region/" . $date . "/" . $region_id . "/" . $type . "/" . $project_id . "/" . $event_id . "/join/cancel/" . $transactonid;

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
					}

					break;
				case 'donation' :
					$owner = DB::select("select a.email from users a, region_manager b where a.id = b.user_id and b.id = ?", array($region_id));

					if ($owner[0] -> email != "" && filter_var($owner[0] -> email, FILTER_VALIDATE_EMAIL) && Config::get("app.paypal_email") != "" && filter_var(Config::get("app.paypal_email"), FILTER_VALIDATE_EMAIL)) :
						$this -> request_donation($region_id, $date);
					else :
						$message = "<div class='alert alert-danger alert-dismissable'>
							 <button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button>
							 Paypal address is not set yet.
						 </div>";
					endif;
					break;
			endswitch;
		}

		$start = "";
		$end = "";

		if ($date == "") {
			$start = date("Y-m-d H:i:s");
			$end = date("Y-m-d H:i:s", strtotime("+5 days", strtotime($start)));
		} else {
			$start = date("Y-m-d H:i:s", strtotime($date));
			$end = date("Y-m-d H:i:s", strtotime("+5 days", strtotime($start)));
		}

		$region = DB::select("SELECT 	a.*, b.* FROM 	region_manager a LEFT JOIN region_page b ON a.id = b.region_id WHERE a.id = " . $region_id);
		$show_days = $region[0] -> show_days;
		$end = $end = date("Y-m-d H:i:s", strtotime("+" . $show_days . " days", strtotime($start)));
		$country = $region[0] -> country;
		$state = $region[0] -> state;
		$title = $region[0] -> title != "" ? $region[0] -> title : $region[0] -> country . ", " . $region[0] -> state;
		$video = $region[0] -> intro_video;
		$related_report_id = $region[0] -> related_report;
		$memo = $region[0] -> memo;

		$event_sql = "SELECT	a.id AS project_id, a.name AS project_title, b.id AS event_id, b.title AS event_title, b.cost,
							b.address, b.city, b.state, b.zip_code, b.country, b.longitude, b.latitude, b.event_date,
							CASE WHEN c.user_id IS NULL THEN 0 ELSE 1 END AS is_joined, 'prayer' as type
						FROM 	prayer a, prayer_event b
							LEFT JOIN prayer_event_join c ON b.id = c.event_id AND c.user_id = " . Auth::user() -> id . " and c.status = 1,
							region_allocate d
						WHERE 	a.id = b.project_id
							AND a.id = d.project_id and d.project_type = 'prayer'
							AND b.country = '" . $country . "' AND b.state = '" . $state . "'
							AND a.user_id != " . Auth::user() -> id . "
							AND a.status = 1 
							AND b.status = 1
							AND b.event_date BETWEEN '$start' AND '$end'
						UNION
						SELECT	a.id AS project_id, a.name AS project_title, b.id AS event_id, b.title AS event_title, b.cost,
							b.address, b.city, b.state, b.zip_code, b.country, b.longitude, b.latitude, b.event_date,
							CASE WHEN c.user_id IS NULL THEN 0 ELSE 1 END AS is_joined, 'impact' as type
						FROM 	impact a, impact_event b
							LEFT JOIN impact_event_join c ON b.id = c.event_id AND c.user_id = " . Auth::user() -> id . " and c.status = 1,
							region_allocate d
						WHERE 	a.id = b.project_id
							AND a.id = d.project_id and d.project_type = 'impact'
							AND b.country = '" . $country . "' AND b.state = '" . $state . "'
							AND a.user_id != " . Auth::user() -> id . "
							AND a.status = 1 
							AND b.status = 1
							AND b.event_date BETWEEN '$start' AND '$end'
						UNION
						SELECT	a.id AS project_id, a.name AS project_title, b.id AS event_id, b.title AS event_title, b.cost,
							b.address, b.city, b.state, b.zip_code, b.country, b.longitude, b.latitude, b.event_date,
							CASE WHEN c.user_id IS NULL THEN 0 ELSE 1 END AS is_joined, 'nationalreport' as type
						FROM 	nationalreport a, nationalreport_event b
							LEFT JOIN nationalreport_event_join c ON b.id = c.event_id AND c.user_id = " . Auth::user() -> id . " and c.status = 1,
							region_allocate d
						WHERE 	a.id = b.project_id
							AND a.id = d.project_id and d.project_type = 'nationalreport'
							AND b.country = '" . $country . "' AND b.state = '" . $state . "'
							AND a.user_id != " . Auth::user() -> id . "
							AND a.status = 1 
							AND b.status = 1
							AND b.event_date BETWEEN '$start' AND '$end'
						UNION
						SELECT	a.id AS project_id, a.name AS project_title, b.id AS event_id, b.title AS event_title, b.cost,
							b.address, b.city, b.state, b.zip_code, b.country, b.longitude, b.latitude, b.event_date,
							CASE WHEN c.user_id IS NULL THEN 0 ELSE 1 END AS is_joined, 'regionalreport' as type
						FROM 	regionalreport a, regionalreport_event b
							LEFT JOIN regionalreport_event_join c ON b.id = c.event_id AND c.user_id = " . Auth::user() -> id . " and c.status = 1,
							region_allocate d
						WHERE 	a.id = b.project_id
							AND a.id = d.project_id and d.project_type = 'regionalreport'
							AND b.country = '" . $country . "' AND b.state = '" . $state . "'
							AND a.user_id != " . Auth::user() -> id . "
							AND a.status = 1 
							AND b.status = 1
							AND b.event_date BETWEEN '$start' AND '$end'
						UNION
						SELECT	a.id AS project_id, a.name AS project_title, b.id AS event_id, b.title AS event_title, b.cost,
							b.address, b.city, b.state, b.zip_code, b.country, b.longitude, b.latitude, b.event_date,
							CASE WHEN c.user_id IS NULL THEN 0 ELSE 1 END AS is_joined, 'teaching' as type
						FROM 	teaching a, teaching_event b
							LEFT JOIN teaching_event_join c ON b.id = c.event_id AND c.user_id = " . Auth::user() -> id . " and c.status = 1,
							region_allocate d
						WHERE 	a.id = b.project_id
							AND a.id = d.project_id and d.project_type = 'teaching'
							AND b.country = '" . $country . "' AND b.state = '" . $state . "'
							AND a.user_id != " . Auth::user() -> id . "
							AND a.status = 1 
							AND b.status = 1
							AND b.event_date BETWEEN '$start' AND '$end'
						ORDER BY event_date";
		$events = DB::select($event_sql);
		$annual_event = DB::select("select 	a.region_id, '' as project_title, a.id, a.title, a.cost, 
										a.address, a.city, a.state, a.zip_code, a.country, a.longitude, a.latitude, a.event_date, 
										CASE WHEN b.user_id IS NULL THEN 0 ELSE 1 END AS is_joined, 'annual' as type
									from 	region_annual_event a
										LEFT JOIN region_annual_event_join b ON a.id = b.event_id AND b.user_id = " . Auth::user() -> id . " and b.status = 1,
										region_manager c
									where	a.region_id = " . $region_id . "
										AND a.region_id = c.id and c.user_id != " . Auth::user() -> id . "
									ORDER BY event_date");

		$key = base64_encode(($date == "" ? date("Y-m-d") : $date) . "/" . $region_id);
		$share_link = Config::get("app.url") . "/share/region/" . $key;

		$month_events = "SELECT event_date, SUM(ev) AS ev FROM (
						SELECT 	SUBSTR(a.event_date, 1, 10) AS event_date, COUNT(a.id) AS ev FROM impact_event a, impact b WHERE a.project_id = b.id and b.user_id != " . Auth::user() -> id . " and b.status = 1 and a.status = 1 AND a.country = '" . $country . "' AND a.state = '" . $state . "' AND SUBSTR(a.event_date, 1, 7) = '" . substr($date, 0, 7) . "' GROUP BY SUBSTR(a.event_date, 1, 10)
						UNION ALL 
						SELECT 	SUBSTR(a.event_date, 1, 10) AS event_date, COUNT(a.id) AS ev FROM prayer_event a, prayer b WHERE a.project_id = b.id and b.user_id != " . Auth::user() -> id . " and b.status = 1 and a.status = 1 AND a.country = '" . $country . "' AND a.state = '" . $state . "' AND SUBSTR(a.event_date, 1, 7) = '" . substr($date, 0, 7) . "' GROUP BY SUBSTR(a.event_date, 1, 10)
						UNION ALL 
						SELECT 	SUBSTR(a.event_date, 1, 10) AS event_date, COUNT(a.id) AS ev FROM nationalreport_event a, nationalreport b WHERE a.project_id = b.id and b.user_id != " . Auth::user() -> id . " and b.status = 1 and a.status = 1 AND a.country = '" . $country . "' AND a.state = '" . $state . "' AND SUBSTR(a.event_date, 1, 7) = '" . substr($date, 0, 7) . "' GROUP BY SUBSTR(a.event_date, 1, 10)
						UNION ALL 
						SELECT 	SUBSTR(a.event_date, 1, 10) AS event_date, COUNT(a.id) AS ev FROM regionalreport_event a, regionalreport b WHERE a.project_id = b.id and b.user_id != " . Auth::user() -> id . " and b.status = 1 and a.status = 1 AND a.country = '" . $country . "' AND a.state = '" . $state . "' AND SUBSTR(a.event_date, 1, 7) = '" . substr($date, 0, 7) . "' GROUP BY SUBSTR(a.event_date, 1, 10)
						UNION ALL 
						SELECT 	SUBSTR(a.event_date, 1, 10) AS event_date, COUNT(a.id) AS ev FROM teaching_event a, teaching b WHERE a.project_id = b.id and b.user_id != " . Auth::user() -> id . " and b.status = 1 and a.status = 1 AND a.country = '" . $country . "' AND a.state = '" . $state . "' AND SUBSTR(a.event_date, 1, 7) = '" . substr($date, 0, 7) . "' GROUP BY SUBSTR(a.event_date, 1, 10)
						ORDER BY  event_date) tbl
						GROUP BY event_date order by event_date";
		$month_events = DB::select($month_events);
		return View::make("/frontend/" . $this -> _permission[Auth::user() -> permission] . "/region/home") -> with(array("active" => "region", "region_id" => $region_id, "month_events" => $month_events, "title" => $title, "video" => $video, "curr_date" => $date, "regions" => $regions, "events" => $events, "annual_event" => $annual_event, "related_report_id" => $related_report_id, "date" => $date == "" ? date("Y-m-d") : $date, "share_link" => $share_link, "memo" => $memo, "redirect_url" => Config::get("app.url") . $_SERVER["REQUEST_URI"], "location" => $region[0] -> state . ", " . $region[0] -> country, "picture" => Config::get("app.url") . "/images/facebook/global_compact-icon-1365540187.png"));
	}

	public function manage_region() {
		$region = DB::select("SELECT 	a.id, a.country, a.state, b.*
								FROM 	region_manager a
									LEFT JOIN region_page b ON a.id = b.region_id
								WHERE 	a.user_id = ?", array(Auth::user() -> id));
		if (empty($region)) {
			return View::make("/frontend/region/manage/region-fail") -> with(array("active" => "manages"));
		}

		$message = "";
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$region_id = $region[0] -> id;
			$title = Input::get("region_title");
			$intro_video = Input::get("intro_video");
			$related_report = Input::get("related_report");
			$memo = Input::get("memo");

			DB::table("region_page") -> where("region_id", $region_id) -> delete();
			DB::table("region_page") -> insert(array("region_id" => $region_id, "title" => $title, "intro_video" => $intro_video, "related_report" => $related_report, "memo" => $memo));

			// annual event
			$has_event = Input::get("has_annual_event");
			if ($has_event == 1) {
				$event_id = Input::get("event_id");
				$title = Input::get("title");
				$address = Input::get("address");
				$city = Input::get("city");
				$state = Input::get("state");
				$zip_code = Input::get("zip_code");
				$country = Input::get("country");
				$event_date = Input::get("event_date");
				$event_hour = Input::get("event_hour");
				$event_minute = Input::get("event_minute");
				$description = Input::get("description");
				$cost = Input::get("cost");
				$contact_details = Input::get("contact_details");
				$longitude = Input::get("longitude");
				$latitude = Input::get("latitude");

				if ($event_id > 0) {
					DB::table("region_annual_event") -> where("id", $event_id) -> update(array("title" => $title, "description" => $description, "cost" => $cost, "address" => $address, "city" => $city, "state" => $state, "zip_code" => $zip_code, "country" => $country, "longitude" => $longitude, "latitude" => $latitude, "contact_details" => $contact_details, "event_date" => $event_date . " " . $event_hour . ":" . $event_minute . ":00"));
				} else {
					$event_id = DB::table("region_annual_event") -> insertGetId(array("id" => null, "region_id" => $region_id, "title" => $title, "description" => $description, "cost" => $cost, "address" => $address, "city" => $city, "state" => $state, "zip_code" => $zip_code, "country" => $country, "longitude" => $longitude, "latitude" => $latitude, "contact_details" => $contact_details, "event_date" => $event_date . " " . $event_hour . ":" . $event_minute . ":00", "created_date" => date("Y-m-d")));
				}

				if (isset($_FILES["thumbnail"]) && $_FILES["thumbnail"]["tmp_name"] != "") {
					$filename = $this -> generate_rand(32);
					if (move_uploaded_file($_FILES["thumbnail"]["tmp_name"], public_path() . "/res/project/thumb/" . $filename)) {
						$url = Config::get("app.url") . "/res/project/thumb/" . $filename;

						DB::table("region_annual_event") -> where("id", $event_id) -> update(array("thumbnail" => $url));
					}
				}
			} else {
				DB::table("region_annual_event") -> where("region_id", $region_id) -> delete();
			}

			$message = "<div class='alert alert-success alert-dismissable'>
                            <button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button>
                            Region information is saved successfully.
                        </div>";
		}

		$region = DB::select("SELECT 	a.id, a.country, a.state, a.help_video, b.*
								FROM 	region_manager a
									LEFT JOIN region_page b ON a.id = b.region_id
								WHERE 	a.user_id = ?", array(Auth::user() -> id));

		$reports = DB::select("SELECT 	DISTINCT b.id, b.name
								FROM 	region_allocate a, regionalreport b, region_manager c
								WHERE 	a.project_id = b.id
									AND c.id = a.region_id AND c.user_id = ?
								ORDER BY name", array(Auth::user() -> id));
		$related_report = $region[0] -> related_report;
		$memo = $region[0] -> memo;

		$annual = DB::table("region_annual_event") -> where("region_id", $region[0] -> id) -> first();
		$has_event = "1";
		if (empty($annual)) {
			$annual = array("id" => 0, "title" => "", "description" => "", "cost" => "", "address" => "", "city" => "", "state" => "", "zip_code" => "", "country" => "", "longitude" => 0, "latitude" => 0, "thumbnail" => "", "contact_details" => "", "event_date" => "", "event_hour" => "", "event_minute" => "");
			$annual = json_decode(json_encode($annual), FALSE);
			$has_event = "0";
		} else {
			$dd = $annual -> event_date;
			$annual -> event_date = date("Y-m-d", strtotime($dd));
			$annual -> event_hour = date("H", strtotime($dd));
			$annual -> event_minute = date("i", strtotime($dd));
		}

		$total = DB::select("SELECT SUM(amount) AS amount FROM region_transaction a WHERE region_id = ? and a.status = 1", array($region[0] -> id));
		$annual_joins = DB::select("select sum(b.amount) as amount from region_annual_event a, region_annual_event_transaction b 
									where a.id = b.event_id and a.region_id = " . $region[0] -> id);
		$transactions = DB::table("region_transaction") -> where("region_id", $region[0] -> id) -> get();
		return View::make("/frontend/region/manage/region") -> with(array("active" => "manages", "join_total" => $annual_joins[0], "has_event" => $has_event, "region" => $region[0], "message" => $message, "reports" => $reports, "related_report" => $related_report, "annual" => $annual, "memo" => $memo, "total" => $total[0], "transactions" => $transactions));
	}

	public function manage_projects($type) {
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$project_status = Input::get("project_status");
			if ($type == "report") {
				$status_national = $project_status['nationalreport'];
				$status_regional = $project_status['regionalreport'];

				while (list($key, $value) = each($status_national)) :
					DB::table("nationalreport") -> where("id", $key) -> update(array("status" => $value));
				endwhile;

				while (list($key, $value) = each($status_regional)) :
					DB::table("regionalreport") -> where("id", $key) -> update(array("status" => $value));
				endwhile;
			} else {
				switch ($type) {
					case 'prayer' :
						$status = $project_status["prayer"];
						break;
					case 'impact' :
						$status = $project_status["impact"];
						break;
					case 'teaching' :
						$status = $project_status["teaching"];
						break;
				}

				while (list($key, $value) = each($status)) :
					DB::table($type) -> where("id", $key) -> update(array("status" => $value));
				endwhile;
			}

			return Redirect::to("/manages/projects/" . $type);
		}

		if ($type == "report") {
			$sql = "SELECT 	a.id, a.name, a.created_date, a.status, b.first_name, b.last_name, 'nationalreport' as type,
						(select COUNT(id) from nationalreport_event where project_id = a.id and status = 1) AS event_count,
						(select COUNT(user_id) from nationalreport_follow where project_id = a.id) AS follow_count,
						(SELECT COUNT(id) FROM nationalreport_hug WHERE project_id = a.id) AS hug_count
					FROM 	nationalreport a, users b, user_profile c, region_manager d, region_allocate e
					WHERE 	a.user_id = b.id
						AND d.user_id = " . Auth::user() -> id . "
						AND d.id = e.region_id
						AND e.project_id = a.id
						AND e.project_type = 'nationalreport'
						AND b.id = c.user_id AND a.status != -1
					UNION
					SELECT 	a.id, a.name, a.created_date, a.status, b.first_name, b.last_name, 'regionalreport' as type,
						(select COUNT(id) from regionalreport_event where project_id = a.id and status = 1) AS event_count,
						(select COUNT(user_id) from regionalreport_follow where project_id = a.id) AS follow_count,
						(SELECT COUNT(id) FROM regionalreport_hug WHERE project_id = a.id) AS hug_count
					FROM 	regionalreport a, users b, user_profile c, region_manager d, region_allocate e
					WHERE 	a.user_id = b.id
						AND d.user_id = " . Auth::user() -> id . "
						AND d.id = e.region_id
						AND e.project_id = a.id
						AND e.project_type = 'regionalreport'
						AND b.id = c.user_id AND a.status != -1
					ORDER BY created_date";
		} else {
			$sql = "SELECT 	a.id, a.name, a.created_date, a.status, b.first_name, b.last_name, '$type' as type,
						(select COUNT(id) from " . $type . "_event where project_id = a.id and status = 1) AS event_count,
						(select COUNT(user_id) from " . $type . "_follow where project_id = a.id) AS follow_count,
						(SELECT COUNT(id) FROM " . $type . "_hug WHERE project_id = a.id) AS hug_count
					FROM 	" . $type . " a, users b, user_profile c, region_manager d, region_allocate e
					WHERE 	a.user_id = b.id
						AND d.user_id = " . Auth::user() -> id . "
						AND d.id = e.region_id
						AND e.project_id = a.id
						AND e.project_type = '" . $type . "'
						AND b.id = c.user_id AND a.status != -1
					ORDER BY created_date";
		}

		$projects = DB::select($sql);

		return View::make("/frontend/region/manage/projects") -> with(array("active" => "manages", "sub_active" => "manages-projects", "type" => $type, "projects" => $projects));
	}

	public function project_edit($type, $id = 0) {
		if ($type == "prayer") {
			return $this -> prayer_edit($id, "manages", "manages-projects");
		} elseif ($type == "impact") {
			return $this -> impact_edit($id, "manages", "manages-projects");
		} elseif ($type == "teaching") {
			return $this -> teaching_edit($id, "manages", "manages-projects");
		}
	}

	public function project_events($type, $id = 0) {
		if ($type == "prayer") {
			return $this -> prayer_events($id, "manages", "manages-projects");
		} elseif ($type == "impact") {
			return $this -> impact_events($id, "manages", "manages-projects");
		} elseif ($type == "teaching") {
			return $this -> teaching_events($id, "manages", "manages-projects");
		}
	}

	public function project_events_edit($type, $id, $event_id = 0) {
		if ($type == "prayer") {
			return $this -> prayer_events_edit($id, $event_id, "manages", "manages-projects");
		} elseif ($type == "impact") {
			return $this -> impact_events_edit($id, $event_id, "manages", "manages-projects");
		} elseif ($type == "teaching") {
			return $this -> teaching_events_edit($id, $event_id, "manages", "manages-projects");
		}
	}

	public function project_hugs($type, $id = 0) {
		if ($type == "prayer") {
			return $this -> prayer_hugs($id, "manages", "manages-projects");
		} elseif ($type == "impact") {
			return $this -> impact_hugs($id, "manages", "manages-projects");
		} elseif ($type == "teaching") {
			return $this -> teaching_hugs($id, "manages", "manages-projects");
		}
	}

	public function project_followings($type, $id = 0) {
		if ($type == "prayer") {
			return $this -> prayer_followings($id, "manages", "manages-projects");
		} elseif ($type == "impact") {
			return $this -> impact_followings($id, "manages", "manages-projects");
		} elseif ($type == "teaching") {
			return $this -> teaching_followings($id, "manages", "manages-projects");
		}
	}

	public function projectreport_edit($prefix, $project_id = 0) {
		return $this -> report_edit($prefix, $project_id, "manages", "manages-projects");
	}

	public function projectreport_hugs($prefix, $project_id = 0) {
		return $this -> report_hugs($prefix, $project_id, "manages", "manages-projects");
	}

	public function projectreport_events($prefix, $project_id = 0) {
		return $this -> report_events($prefix, $project_id, "manages", "manages-projects");
	}

	public function projectreport_events_edit($prefix, $project_id, $event_id = 0) {
		return $this -> report_events_edit($prefix, $project_id, "manages", "manages-projects");
	}

	public function projectreport_followings($prefix, $project_id) {
		return $this -> report_followings($prefix, $project_id, "manages", "manages-projects");
	}

	public function project_event_joins($type, $project_id, $event_id) {
		switch($type) :
			case 'impact' :
				return $this -> impact_event_joins($project_id, $event_id, "manages", "manages-projects");
				break;
			case 'prayer' :
				return $this -> prayer_event_joins($project_id, $event_id, "manages", "manages-projects");
				break;
			case 'teaching' :
				return $this -> teaching_event_joins($project_id, $event_id, "manages", "manages-projects");
				break;
		endswitch;
	}

	public function projectreport_event_joins($prefix, $project_id, $event_id) {
		return $this -> report_event_joins($prefix, $project_id, $event_id, "manages", "manages-projects");
	}

	public function donation_step($region_id) {
		$message = "";
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$owner = DB::select("select a.email from users a, region_manager b where a.id = b.user_id and b.id = ?", array($region_id));

			if ($owner[0] -> email != "" && filter_var($owner[0] -> email, FILTER_VALIDATE_EMAIL) && Config::get("app.paypal_email") != "" && filter_var(Config::get("app.paypal_email"), FILTER_VALIDATE_EMAIL)) :
				$this -> request_donation($region_id);
			else :
				$message = "<div class='alert alert-danger alert-dismissable'>
							 <button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button>
							 Paypal address is not set yet.
						 </div>";
			endif;
		}

		$region = DB::select("SELECT 	a.id, a.user_id, CASE WHEN b.title IS NULL THEN CONCAT(a.country, ', ', a.state) ELSE b.title END AS title, b.memo
							FROM	region_manager a
								LEFT JOIN region_page b ON a.id = b.region_id
							WHERE 	a.id = ?", array($region_id));
		$overall = DB::select("select a.email, b.phone_number from users a left join user_profile b on a.id = b.user_id where a.permission = -1");
		$owner = DB::select("SELECT 	a.first_name, a.last_name, a.email, b.phone_number, b.address, b.city, b.state, b.zip_code, b.country
							FROM 	users a
								LEFT JOIN user_profile b ON a.id = b.user_id
							WHERE 	a.id = ?", array($region[0] -> user_id));
		$total = DB::select("SELECT 	COUNT(email) AS user_count, CASE WHEN SUM(amount) IS NULL THEN 0 ELSE SUM(amount) END AS amount FROM region_transaction a WHERE region_id = ?", array($region_id));

		$transactions = DB::table("region_transaction") -> where("region_id", $region_id) -> where("status", 1) -> where("user_id", Auth::user() -> id) -> get();

		return View::make("/frontend/" . $this -> _permission[Auth::user() -> permission] . "/donation/region_step") -> with(array("active" => "region", "overall" => $overall[0], "region" => $region[0], "total" => $total[0], "owner" => $owner[0], "transactions" => $transactions, "message" => $message));
	}

	public function request_donation($region_id, $date) {
		include ("include/paypal/paypal.php");

		$overall = DB::table("users") -> where("permission", -1) -> first();
		$owner = DB::select("select a.email from users a, region_manager b where a.id = b.user_id and b.id = ?", array($region_id));

		$amount = Input::get("amount");
		$owner_email = $owner[0] -> email;
		$owner_amount = $amount * 0.925;

		$overall_email = Config::get("app.paypal_email");
		$overall_amount = $amount * 0.075;

		$transactionid = "TS-RG-" . $this -> generate_rand(32);

		$return_url = Config::get("app.url") . "/region/" . $region_id . "/donation/success/" . $transactionid . "/" . $date;
		$cancel_url = Config::get("app.url") . "/region/" . $region_id . "/donation/cancel/" . $transactionid . "/" . $date;

		DB::table("region_transaction") -> insert(array("id" => $transactionid, "region_id" => $region_id, "amount" => $owner_amount, "user_id" => Auth::user() -> id, "name" => Auth::user() -> first_name . " " . Auth::user() -> last_name, "email" => Auth::user() -> email, "status" => -100, "created_date" => date("Y-m-d H:i:s")));
		DB::table("overall_transaction") -> insert(array("id" => null, "related_transaction_id" => $transactionid, "project_id" => $region_id, "project_type" => "region", "amount" => $overall_amount, "user_id" => Auth::user() -> id, "name" => Auth::user() -> first_name . " " . Auth::user() -> last_name, "email" => Auth::user() -> email, "status" => -100, "created_date" => date("Y-m-d H:i:s")));

		$paypal = new Paypal;
		$receiver = array( array("amount" => $owner_amount, "email" => $owner_email), array("amount" => $overall_amount, "email" => $overall_email));
		$item = array( array("name" => "Donation for region", "identifier" => "p1", "price" => $owner_amount, "itemPrice" => $owner_amount, "itemCount" => 1), array("name" => "Response for donation", "identifier" => "p2", "price" => $overall_amount, "itemPrice" => $overall_amount, "itemCount" => 1));
		$receiverOptions = array( array("receiver" => array("email" => $owner_email), "invoiceData" => array("item" => array( array("name" => "Donation for region", "price" => $owner_amount, "identifire" => "p1")))), array("receiver" => array("email" => $overall_email), "invoiceData" => array("item" => array( array("name" => "Responsive for donation", "price" => $overall_amount, "identifire" => "p2")))));
		$paypal -> splitPay($receiver, $item, $return_url, $cancel_url, $receiverOptions);
		exit ;
	}

	public function success($region_id, $transactionid, $date) {
		DB::table("region_transaction") -> where("id", $transactionid) -> update(array("status" => 1));
		DB::table("overall_transaction") -> where("related_transaction_id", $transactionid) -> update(array("status" => 1));

		$region = DB::select("SELECT 	a.id, (case when b.title is null then concat(a.country, ', ', a.state) else b.title end) as title 
							FROM 	region_manager a LEFT JOIN region_page b ON a.id = b.region_id WHERE a.id = ?", array($region_id));
		$transaction = DB::table("region_transaction") -> where("id", $transactionid) -> first();
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

		if (Auth::check()) :
			Session::set("error", "<div class='alert alert-success alert-dismissable'>
			                            <button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button>
			                            Thank you for your support!
			                        </div>");
			return Redirect::to("/region/" . $date . "/" . $region_id);
		else :
			$top_projects = DB::table("topproject") -> get();
			$about = DB::table("about") -> first();
			$about_content = !empty($about) ? $about -> content : "";

			$contact = DB::table("contact_us") -> first();

			if (empty($contact)) {
				$contact = array("content" => "", "phone_number" => "", "address" => "", "email" => "");
				$contact = json_decode(json_encode($contact), FALSE);
			}

			return View::make("/frontend/donation_success") -> with(array("active" => "", "redirect_url" => "", "top_projects" => $top_projects, "type" => "Region", "about_content" => $about_content, "contact" => $contact));
		endif;
	}

	public function cancel($region_id, $transactionid, $date) {
		DB::table("region_transaction") -> where("id", $transactionid) -> delete();
		DB::table("overall_transaction") -> where("related_transaction_id", $transactionid) -> delete();

		if (Auth::check()) :
			Session::set("error", "<div class='alert alert-danger alert-dismissable'>
			                            <button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button>
			                            You have cancelled donation to this region.
			                        </div>");
			return Redirect::to("/region/" . $date . "/" . $region_id);
		else :
			$top_projects = DB::table("topproject") -> get();
			$about = DB::table("about") -> first();
			$about_content = !empty($about) ? $about -> content : "";

			$contact = DB::table("contact_us") -> first();

			if (empty($contact)) {
				$contact = array("content" => "", "phone_number" => "", "address" => "", "email" => "");
				$contact = json_decode(json_encode($contact), FALSE);
			}

			return View::make("/frontend/donation_cancel") -> with(array("active" => "", "redirect_url" => "", "top_projects" => $top_projects, "type" => "Region", "about_content" => $about_content, "contact" => $contact));
		endif;
	}

	public function join($date, $region_id, $type, $project_id, $event_id, $action, $transactionid) {
		$error = "";
		switch($action) :
			case 'success' :
				$owner_email = "";
				if ($type == "annual") :
					DB::table("region_annual_event_join") -> where("event_id", $event_id) -> where("user_id", Auth::user() -> id) -> update(array("status" => 1));
					DB::table("region_annual_event_transaction") -> where("id", $transactionid) -> update(array("status" => 1));
					DB::table("overall_event_transaction") -> where("related_transaction_id", $transactionid) -> update(array("status" => 1));

					$owner = DB::select("select a.email from users a, region_manager b where a.id = b.user_id and b.id = " . $region_id);
					$owner_email = $owner[0] -> email;
				else :
					DB::table($type . "_event_join") -> where("event_id", $event_id) -> where("user_id", Auth::user() -> id) -> update(array("status" => 1));
					DB::table($type . "_event_transaction") -> where("id", $transactionid) -> update(array("status" => 1));
					DB::table("overall_event_transaction") -> where("related_transaction_id", $transactionid) -> update(array("status" => 1));

					$owner = DB::select("select a.email from users a, " . $type . " b where a.id = b.user_id and b.id = " . $project_id);
					$owner_email = $owner[0] -> email;
				endif;

				if ($owner_email != "") {
					if ($type != "annual") :
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
					else :
						$body = "<style>
							 		* {font-family: Arial;}
									table {font-size: 12px;}
							 	</style>
							 	<h4>Someone joins in regional event.</h4>
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
							 		<tr>
							 			<td colspan='2'><a href='" . Config::get("app.url") . "/manages/region-page' target='_blank'>Go to Region</a></td>
							 		</tr>
							 	</table>";
					endif;

					$mail = new PHPMailer;
					$mail -> setFrom(Config::get("app.support_email"));
					$mail -> addAddress($owner_email);
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
				DB::table("region_annual_event_join") -> where("event_id", $event_id) -> where("user_id", Auth::user() -> id) -> delete();
				DB::table("region_annual_event_transaction") -> where("id", $transactionid) -> delete();
				DB::table("overall_event_transaction") -> where("related_transaction_id", $transactionid) -> delete();

				$error = "<div class='alert alert-danger alert-dismissable'>
                            <button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button>
                            You have cancelled to join in project event.
                        </div>";
				break;
		endswitch;

		Session::set("error", $error);

		return Redirect::to("/region/" . $date . "/" . $region_id);
	}

}
