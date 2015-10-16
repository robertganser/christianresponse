<?php

class BaseController extends Controller {

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */

	public $_permission = array("-1" => "overall", "-2" => "region", "-3" => "general", "100" => "user");

	public function __construct() {
		DB::statement("CREATE OR REPLACE VIEW topproject AS 
			SELECT 	a.id, a.name, a.thumbnail,
				(SELECT CASE WHEN COUNT(user_id) IS NULL THEN 0 ELSE COUNT(user_id) END FROM impact_follow WHERE project_id = a.id) AS follow_count,
				(SELECT CASE WHEN AVG(mark) IS NULL THEN 0 ELSE AVG(mark) END FROM impact_review WHERE project_id = a.id) AS review,
				'impact' AS project_type, a.created_date
			FROM 	impact a, region_allocate b
			WHERE 	a.status = 1 AND a.id = b.project_id AND b.project_type = 'impact'
			GROUP BY a.id
			UNION
			SELECT 	a.id, a.name, a.thumbnail,
				(SELECT CASE WHEN COUNT(user_id) IS NULL THEN 0 ELSE COUNT(user_id) END FROM prayer_follow WHERE project_id = a.id) AS follow_count,
				(SELECT CASE WHEN AVG(mark) IS NULL THEN 0 ELSE AVG(mark) END FROM prayer_review WHERE project_id = a.id) AS review,
				'prayer' AS project_type, a.created_date
			FROM 	prayer a, region_allocate b
			WHERE 	a.status = 1 AND a.id = b.project_id AND b.project_type = 'prayer'
			GROUP BY a.id
			UNION
			SELECT 	a.id, a.name, a.thumbnail,
				(SELECT CASE WHEN COUNT(user_id) IS NULL THEN 0 ELSE COUNT(user_id) END FROM nationalreport_follow WHERE project_id = a.id) AS follow_count,
				(SELECT CASE WHEN AVG(mark) IS NULL THEN 0 ELSE AVG(mark) END FROM nationalreport_review WHERE project_id = a.id) AS review,
				'nationalreport' AS project_type, a.created_date
			FROM 	nationalreport a, region_allocate b
			WHERE 	a.status = 1 AND a.id = b.project_id AND b.project_type = 'nationalreport'
			GROUP BY a.id
			UNION
			SELECT 	a.id, a.name, a.thumbnail,
				(SELECT CASE WHEN COUNT(user_id) IS NULL THEN 0 ELSE COUNT(user_id) END FROM regionalreport_follow WHERE project_id = a.id) AS follow_count,
				(SELECT CASE WHEN AVG(mark) IS NULL THEN 0 ELSE AVG(mark) END FROM regionalreport_review WHERE project_id = a.id) AS review,
				'regionalreport' AS project_type, a.created_date
			FROM 	regionalreport a, region_allocate b
			WHERE 	a.status = 1 AND a.id = b.project_id AND b.project_type = 'regionalreport'
			GROUP BY a.id
			UNION
			SELECT 	a.id, a.name, a.thumbnail,
				(SELECT CASE WHEN COUNT(user_id) IS NULL THEN 0 ELSE COUNT(user_id) END FROM teaching_follow WHERE project_id = a.id) AS follow_count,
				(SELECT CASE WHEN AVG(mark) IS NULL THEN 0 ELSE AVG(mark) END FROM teaching_review WHERE project_id = a.id) AS review,
				'teaching' AS project_type, a.created_date
			FROM 	teaching a, region_allocate b
			WHERE 	a.status = 1 AND a.id = b.project_id AND b.project_type = 'teaching'
			GROUP BY a.id
			ORDER BY review DESC, follow_count DESC, created_date desc
			LIMIT 6");
	}

	protected function setupLayout() {
		//DB::table("project") -> where("completion_date", "<=", date("Y-m-d")) -> update(array("is_archived" => 1));
		if (!is_null($this -> layout)) {
			$this -> layout = View::make($this -> layout);
		}
	}

	public function generate_rand($length = 8) {
		$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
		//length:36
		$final_rand = '';
		for ($i = 0; $i < $length; $i++) {
			$final_rand .= $chars[rand(0, strlen($chars) - 1)];
		}
		return $final_rand;
	}

	public function responsebox($message, $type = 'error') {
		$str = "<div class='response_" . $type . "_box'>
					<table width='100%'>
						<tr>
							<td valign='top' align='left' width='20px'>
								<img src='/images/response_" . $type . ".png'>
							</td>
							<td>$message</td>
						</tr>
					</table>
				</div>";

		return $str;
	}

	private function send_new_project_email($type, $project_id) {
		$overall = DB::table("users") -> where("permission", -1) -> first();
		$mail = new PHPMailer;
		$mail -> setFrom(Config::get("app.support_email"));
		$mail -> addAddress($overall -> email);

		$body = "<style>
					* {
						font-family: Arial;
					}
					table {
						font-size: 12px;
					}
				</style>
				<h4>Please check it in order to approve or allocate new project.</h4>
				<table>
					<tr>
						<td valign='top'>Project Link: </td>
						<td><a href='" . Config::get("app.url") . "/manages/projects/allocate/" . $type . "'>Click to go.</a></td>
					</tr>
				</table>";

		$mail -> Subject = "Christian Response: You need to do something.";
		$mail -> msgHTML($body);
		$mail -> AltBody = $body;
		$mail -> send();
	}

	public function send_following_email($type, $project_id) {
		$owner = DB::select("select b.email from " . $type . " a, users b where a.user_id = b.id and a.id = $project_id");

		$mail = new PHPMailer;
		$mail -> setFrom(Config::get("app.support_email"));
		$mail -> addAddress($owner[0] -> email);

		if ($type == "nationalreport" || $type == "regionalreport") {
			$type = "report/" . $type;
		}

		$body = "<style>
					* {
						font-family: Arial;
					}
					table {
						font-size: 12px;
					}
				</style>
				<h4>Someone followed your project.</h4>
				<table>
					<tr>
						<td valign='top'>Project Link: </td>
						<td><a href='" . Config::get("app.url") . "/projects/" . $type . "/" . $project_id . "/followings'>Click to go.</a></td>
					</tr>
					<tr>
						<td valign='top'>Personal Link: </td>
						<td><a href='" . Config::get("app.url") . "/search/facilitator/single/view/" . Auth::user() -> id . "'>Click to go.</a></td>
					</tr>
				</table>";

		$mail -> Subject = "Christian Response: Someone followed your project.";
		$mail -> msgHTML($body);
		$mail -> AltBody = $body;
		$mail -> send();
	}

	public function send_event_email($type, $project_id, $event_id) {
		$target = DB::select("select b.email from " . $type . "_follow a, users b where a.user_id = b.id and a.project_id = $project_id");
		$event = DB::table($type . "_event") -> where("id", $event_id) -> first();

		$mail = new PHPMailer;
		$mail -> setFrom(Config::get("app.support_email"));

		foreach ($target as $one) :
			$mail -> addAddress($one -> email);
		endforeach;

		$body = "<style>
					* {
						font-family: Arial;
					}
					table {
						font-size: 12px;
					}
				</style>
				<h4>New evnet is created.</h4>
				<table>
					<tr>
						<td>Tilte: </td>
						<td>" . $event -> title . "</td>
					</tr>
					<tr>
						<td>Description: </td>
						<td>" . $event -> description . "</td>
					</tr>
					<tr>
						<td>Location: </td>
						<td>" . $event -> address . ", " . $event -> city . ", " . $event -> state . " " . $event -> zip_code . ", " . $event -> country . "</td>
					</tr>
					<tr>
						<td>Event Time: </td>
						<td>" . $event -> event_date . "</td>
					</tr>
					<tr>
						<td valign='top'>Project Link: </td>
						<td><a href='" . Config::get("app.url") . "/search/project/" . ($type == "nationalreport" || $type == "regionalreport" ? "report/" . $type : $type) . "/view/" . $project_id . "'>Click to go.</a></td>
					</tr>
				</table>";

		$mail -> Subject = "Christian Response: New event is created.";
		$mail -> msgHTML($body);
		$mail -> AltBody = $body;
		$mail -> send();

		$region_administrator = DB::select("SELECT 	DISTINCT c.email
											FROM 	".$type."_event a, region_manager b, users c
											WHERE 	a.country = b.country AND a.state = b.state AND b.user_id = c.id AND c.email is not null AND a.id = ?", array($event_id));
		if (count($region_administrator) > 0) {
			if ($region_administrator[0] -> email != "") {
				$mail = new PHPMailer;
				$mail -> setFrom(Config::get("app.support_email"));
				$mail -> addAddress($region_administrator[0] -> email);

				$body = "<style>
							* {
								font-family: Arial;
							}
							table {
								font-size: 12px;
							}
						</style>
						<h4>New evnet is created in your region.</h4>
						<table>
							<tr>
								<td>Tilte: </td>
								<td>" . $event -> title . "</td>
							</tr>
							<tr>
								<td>Description: </td>
								<td>" . $event -> description . "</td>
							</tr>
							<tr>
								<td>Location: </td>
								<td>" . $event -> address . ", " . $event -> city . ", " . $event -> state . " " . $event -> zip_code . ", " . $event -> country . "</td>
							</tr>
							<tr>
								<td>Event Time: </td>
								<td>" . $event -> event_date . "</td>
							</tr>
							<tr>
								<td valign='top'>Project Link: </td>
								<td><a href='" . Config::get("app.url") . "/manages/projects/" . ($type == "nationalreport" || $type == "regionalreport" ? "report/" . $type : $type) . "/" . $project_id . "/events'>Click to go.</a></td>
							</tr>
						</table>";

				$mail -> Subject = "Christian Response: New event is created in your region.";
				$mail -> msgHTML($body);
				$mail -> AltBody = $body;
				$mail -> send();
			}
		}
	}

	public function sendmail() {
		$user_id = $_POST["user_id"];
		$subject = $_POST["subject"];
		$message = $_POST["message"];

		$user = DB::table("users") -> where("id", $user_id) -> first();
		$owner = DB::table("users") -> where("id", Auth::user() -> id) -> first();
		$email = $user -> email;

		if ($user -> email != "") {
			$mail = new PHPMailer;
			$mail -> setFrom($owner -> email);
			$mail -> addAddress($email);

			$body = "<style>
						* {
							font-family: Arial;
						}
						table {
							font-size: 12px;
						}
					</style>
					<h4>" . $subject . "</h4>
					<table>
						<tr>
							<td valign='top'>Message: </td>
							<td>" . $message . "</td>
						</tr>
					</table>";

			$mail -> Subject = "Christian Response: Facilitator sent you email.";
			$mail -> msgHTML($body);
			$mail -> AltBody = $body;
			$mail -> send();

			echo json_encode(array("success" => true));
		} else {
			echo json_encode(array("success" => false));
		}
	}

	public function impact() {
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$ids = Input::get("selected_id");
			DB::table("impact") -> whereIn('id', explode(",", $ids)) -> update(array("status" => -1));
			return Redirect::to("/projects/impact");
		}

		$sql = "SELECT 	a.id, a.name, 
					(CASE WHEN AVG(b.mark) IS NULL THEN 0 ELSE AVG(b.mark) END) AS review,
					(select COUNT(id) from impact_event where project_id = a.id and status = 1) AS event_count,
					(select COUNT(user_id) from impact_follow where project_id = a.id) AS follow_count,
					(SELECT COUNT(id) FROM impact_hug WHERE project_id = a.id) AS hug_count,
					a.created_date,
					a.updated_date,
					a.status,
					(case when c.region_id is null then 0 else 1 end) as is_allocated,
					(SELECT SUM(d.amount) FROM impact_transaction d WHERE d.project_id = a.id AND d.status = 1) AS total_donation_amount
				FROM 	impact a
					LEFT JOIN impact_review b ON a.id = b.project_id
					LEFT JOIN region_allocate c on a.id = c.project_id and c.project_type = 'impact'
				WHERE 	a.user_id = ? and a.status != -1
				GROUP BY a.id";
		$impacts = DB::select($sql, array(Auth::user() -> id));

		return View::make("/frontend/" . $this -> _permission[Auth::user() -> permission] . "/project/impact") -> with(array("active" => "projects", "impacts" => $impacts));
	}

	public function prayer() {
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$ids = Input::get("selected_id");
			DB::table("prayer") -> whereIn('id', explode(",", $ids)) -> update(array("status" => -1));
			return Redirect::to("/projects/prayer");
		}

		$sql = "SELECT 	a.id, a.name, 
					(CASE WHEN AVG(b.mark) IS NULL THEN 0 ELSE AVG(b.mark) END) AS review,
					(select COUNT(id) from prayer_event where project_id = a.id and status = 1) AS event_count,
					(select COUNT(user_id) from prayer_follow where project_id = a.id) AS follow_count,
					(SELECT COUNT(id) FROM prayer_hug WHERE project_id = a.id) AS hug_count,
					a.created_date,
					a.updated_date,
					a.status,
					(case when c.region_id is null then 0 else 1 end) as is_allocated,
					(SELECT SUM(d.amount) FROM prayer_transaction d WHERE d.project_id = a.id AND d.status = 1) AS total_donation_amount
				FROM 	prayer a
					LEFT JOIN prayer_review b ON a.id = b.project_id
					LEFT JOIN region_allocate c on a.id = c.project_id and c.project_type = 'prayer'
				WHERE 	a.user_id = ? and a.status != -1
				GROUP BY a.id";
		$prayers = DB::select($sql, array(Auth::user() -> id));

		return View::make("/frontend/" . $this -> _permission[Auth::user() -> permission] . "/project/prayer") -> with(array("active" => "projects", "prayers" => $prayers));
	}

	public function report() {
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$national_ids = Input::get("nationalreport_selected_id");
			$regional_ids = Input::get("regionalreport_selected_id");

			if ($national_ids != "") :
				DB::table("nationalreport") -> whereIn('id', explode(",", $national_ids)) -> update(array("status" => -1));
			endif;

			if ($regional_ids != "") :
				DB::table("regionalreport") -> whereIn('id', explode(",", $regional_ids)) -> update(array("status" => -1));
			endif;
			return Redirect::to("/projects/report");
		}

		$sql = "SELECT 	a.id, a.name, 
					(CASE WHEN AVG(b.mark) IS NULL THEN 0 ELSE AVG(b.mark) END) AS review,
					(select COUNT(id) from nationalreport_event where project_id = a.id and status = 1) AS event_count,
					(select COUNT(user_id) from nationalreport_follow where project_id = a.id) AS follow_count,
					(SELECT COUNT(id) FROM nationalreport_hug WHERE project_id = a.id) AS hug_count,
					a.created_date,
					a.updated_date,
					1 as project_type,
					a.status,
					(case when c.region_id is null then 0 else 1 end) as is_allocated,
					(SELECT SUM(d.amount) FROM nationalreport_transaction d WHERE d.project_id = a.id AND d.status = 1) AS total_donation_amount
				FROM 	nationalreport a
					LEFT JOIN nationalreport_review b ON a.id = b.project_id
					LEFT JOIN region_allocate c on a.id = c.project_id and c.project_type = 'nationalreport'
				WHERE 	a.user_id = ? and a.status != -1
				GROUP BY a.id
				UNION
				SELECT 	a.id, a.name, 
					(CASE WHEN AVG(b.mark) IS NULL THEN 0 ELSE AVG(b.mark) END) AS review,
					(select COUNT(id) from regionalreport_event where project_id = a.id and status = 1) AS event_count,
					(select COUNT(user_id) from regionalreport_follow where project_id = a.id) AS follow_count,
					(SELECT COUNT(id) FROM regionalreport_hug WHERE project_id = a.id) AS hug_count,
					a.created_date,
					a.updated_date,
					2 as project_type,
					a.status,
					(case when c.region_id is null then 0 else 1 end) as is_allocated,
					(SELECT SUM(d.amount) FROM regionalreport_transaction d WHERE d.project_id = a.id AND d.status = 1) AS total_donation_amount
				FROM 	regionalreport a
					LEFT JOIN regionalreport_review b ON a.id = b.project_id
					LEFT JOIN region_allocate c on a.id = c.project_id and c.project_type = 'regionalreport'
				WHERE 	a.user_id = ? and a.status != -1
				GROUP BY a.id";
		$reports = DB::select($sql, array(Auth::user() -> id, Auth::user() -> id));

		return View::make("/frontend/" . $this -> _permission[Auth::user() -> permission] . "/project/report") -> with(array("active" => "projects", "reports" => $reports));
	}

	public function teaching() {
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$ids = Input::get("selected_id");
			DB::table("teaching") -> whereIn('id', explode(",", $ids)) -> update(array("status" => -1));
			return Redirect::to("/projects/teaching");
		}

		$sql = "SELECT 	a.id, a.name, 
					(CASE WHEN AVG(b.mark) IS NULL THEN 0 ELSE AVG(b.mark) END) AS review,
					(select COUNT(id) from teaching_event where project_id = a.id and status = 1) AS event_count,
					(select COUNT(user_id) from teaching_follow where project_id = a.id) AS follow_count,
					(SELECT COUNT(id) FROM teaching_hug WHERE project_id = a.id) AS hug_count,
					a.created_date,
					a.updated_date,
					a.status,
					(case when c.region_id is null then 0 else 1 end) as is_allocated,
					(SELECT SUM(d.amount) FROM teaching_transaction d WHERE d.project_id = a.id AND d.status = 1) AS total_donation_amount
				FROM 	teaching a
					LEFT JOIN teaching_review b ON a.id = b.project_id
					LEFT JOIN region_allocate c on a.id = c.project_id and c.project_type = 'teaching'
				WHERE 	a.user_id = ? and a.status != -1
				GROUP BY a.id";
		$teaching = DB::select($sql, array(Auth::user() -> id));

		return View::make("/frontend/" . $this -> _permission[Auth::user() -> permission] . "/project/teaching") -> with(array("active" => "projects", "teaching" => $teaching));
	}

	public function impact_edit($project_id = 0, $active = "projects", $sub_active = "") {
		$message = "";
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$name = Input::get("name");
			$faith_god = Input::get("faith_god");
			$description = Input::get("description");
			$often_type = Input::get("often_type");
			$liked_localreport = Input::get("liked_localreport");
			$liked_regionalreport = Input::get("liked_regionalreport");
			$liked_nationalreport = Input::get("liked_nationalreport");
			$oversight = Input::get("oversight");
			$oversight_name = Input::get("oversight_name");
			$oversight_email = Input::get("oversight_email");
			$oversight_phone = Input::get("oversight_phone");
			$paypal_number = Input::get("paypal_number");
			$timeframe = Input::get("timeframe");
			$intro_video = Input::get("intro_video");
			$address = Input::get("address");
			$city = Input::get("city");
			$state = Input::get("state");
			$zip_code = Input::get("zip_code");
			$country = Input::get("country");

			if ($project_id == 0) {
				$project_id = DB::table("impact") -> insertGetId(array("id" => null, "name" => $name, "faith_god" => $faith_god, "description" => $description, "often_type" => $often_type, "liked_localreport" => $liked_localreport, "liked_regionalreport" => $liked_regionalreport, "liked_nationalreport" => $liked_nationalreport, "oversight" => $oversight, "oversight_name" => $oversight_name, "oversight_email" => $oversight_email, "oversight_phone" => $oversight_phone, "paypal_number" => $paypal_number, "timeframe" => $timeframe, "intro_video" => $intro_video, "address" => $address, "city" => $city, "state" => $state, "zip_code" => $zip_code, "country" => $country, "user_id" => Auth::user() -> id, "created_date" => date("Y-m-d H:i:s"), "updated_date" => null, "status" => 100));
				$this -> send_new_project_email("impact", $project_id);
			} else {
				DB::table("impact") -> where("id", $project_id) -> update(array("name" => $name, "faith_god" => $faith_god, "description" => $description, "often_type" => $often_type, "liked_localreport" => $liked_localreport, "liked_regionalreport" => $liked_regionalreport, "liked_nationalreport" => $liked_nationalreport, "oversight" => $oversight, "oversight_name" => $oversight_name, "oversight_email" => $oversight_email, "oversight_phone" => $oversight_phone, "paypal_number" => $paypal_number, "timeframe" => $timeframe, "intro_video" => $intro_video, "address" => $address, "city" => $city, "state" => $state, "zip_code" => $zip_code, "country" => $country, "updated_date" => date("Y-m-d H:i:s")));
			}

			if ($_FILES["thumbnail"]["tmp_name"] != "") {
				$filename = $this -> generate_rand(32);
				if (move_uploaded_file($_FILES["thumbnail"]["tmp_name"], public_path() . "/res/project/thumb/" . $filename)) {
					$url = Config::get("app.url") . "/res/project/thumb/" . $filename;

					DB::table("impact") -> where("id", $project_id) -> update(array("thumbnail" => $url));
				}
			}
			/*
			 if ($_FILES["intro_video"]["tmp_name"] != "") {
			 $filename = $this -> generate_rand(32);
			 if (move_uploaded_file($_FILES["intro_video"]["tmp_name"], public_path() . "/res/project/video/" . $filename)) {
			 $url = Config::get("app.url") . "/res/project/video/" . $filename;

			 DB::table("impact") -> where("id", $project_id) -> update(array("intro_video" => $url));
			 }
			 }
			 */
			$message = "<div class='alert alert-success alert-dismissable'>
                            <button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button>
                            Project information has been saved successfully.
                        </div>";
		}
		$info = array("name" => "", "faith_god" => "", "description" => "", "thumbnail" => "", "intro_video" => "", "often_type" => "1", "liked_localreport" => "1", "liked_regionalreport" => "1", "liked_nationalreport" => "1", "oversight" => "", "oversight_name" => "", "oversight_email" => "", "oversight_phone" => "", "address" => "", "city" => "", "state" => "", "zip_code" => "", "country" => "", "paypal_number" => "", "timeframe" => "");
		$info = json_decode(json_encode($info), FALSE);

		if ($project_id > 0) {
			$info = DB::table("impact") -> where("id", $project_id) -> first();
		}

		$params = array("active" => $active, "sub_active" => $sub_active, "project_id" => $project_id, "info" => $info, "message" => $message);
		return View::make("/frontend/" . $this -> _permission[Auth::user() -> permission] . "/project/impact-edit") -> with($params);
	}

	public function prayer_edit($project_id = 0, $active = "projects", $sub_active = "") {
		$message = "";
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$name = Input::get("name");
			$faith_god = Input::get("faith_god");
			$description = Input::get("description");
			$often_type = Input::get("often_type");
			$liked_localreport = Input::get("liked_localreport");
			$liked_regionalreport = Input::get("liked_regionalreport");
			$liked_nationalreport = Input::get("liked_nationalreport");
			$oversight = Input::get("oversight");
			$oversight_name = Input::get("oversight_name");
			$oversight_email = Input::get("oversight_email");
			$oversight_phone = Input::get("oversight_phone");
			$paypal_number = Input::get("paypal_number");
			$timeframe = Input::get("timeframe");
			$intro_video = Input::get("intro_video");
			$address = Input::get("address");
			$city = Input::get("city");
			$state = Input::get("state");
			$zip_code = Input::get("zip_code");
			$country = Input::get("country");

			if ($project_id == 0) {
				$project_id = DB::table("prayer") -> insertGetId(array("id" => null, "name" => $name, "faith_god" => $faith_god, "description" => $description, "often_type" => $often_type, "liked_localreport" => $liked_localreport, "liked_regionalreport" => $liked_regionalreport, "liked_nationalreport" => $liked_nationalreport, "oversight" => $oversight, "oversight_name" => $oversight_name, "oversight_email" => $oversight_email, "oversight_phone" => $oversight_phone, "paypal_number" => $paypal_number, "timeframe" => $timeframe, "intro_video" => $intro_video, "address" => $address, "city" => $city, "state" => $state, "zip_code" => $zip_code, "country" => $country, "user_id" => Auth::user() -> id, "created_date" => date("Y-m-d H:i:s"), "updated_date" => null, "status" => 100));
				$this -> send_new_project_email("prayer", $project_id);
			} else {
				DB::table("prayer") -> where("id", $project_id) -> update(array("name" => $name, "faith_god" => $faith_god, "description" => $description, "often_type" => $often_type, "liked_localreport" => $liked_localreport, "liked_regionalreport" => $liked_regionalreport, "liked_nationalreport" => $liked_nationalreport, "oversight" => $oversight, "oversight_name" => $oversight_name, "oversight_email" => $oversight_email, "oversight_phone" => $oversight_phone, "paypal_number" => $paypal_number, "timeframe" => $timeframe, "intro_video" => $intro_video, "address" => $address, "city" => $city, "state" => $state, "zip_code" => $zip_code, "country" => $country, "updated_date" => date("Y-m-d H:i:s")));
			}

			if ($_FILES["thumbnail"]["tmp_name"] != "") {
				$filename = $this -> generate_rand(32);
				if (move_uploaded_file($_FILES["thumbnail"]["tmp_name"], public_path() . "/res/project/thumb/" . $filename)) {
					$url = Config::get("app.url") . "/res/project/thumb/" . $filename;

					DB::table("prayer") -> where("id", $project_id) -> update(array("thumbnail" => $url));
				}
			}
			/*
			 if ($_FILES["intro_video"]["tmp_name"] != "") {
			 $filename = $this -> generate_rand(32);
			 if (move_uploaded_file($_FILES["intro_video"]["tmp_name"], public_path() . "/res/project/video/" . $filename)) {
			 $url = Config::get("app.url") . "/res/project/video/" . $filename;

			 DB::table("prayer") -> where("id", $project_id) -> update(array("intro_video" => $url));
			 }
			 }
			 */
			$message = "<div class='alert alert-success alert-dismissable'>
                            <button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button>
                            Project information has been saved successfully.
                        </div>";
		}
		$info = array("name" => "", "faith_god" => "", "description" => "", "thumbnail" => "", "intro_video" => "", "often_type" => "1", "liked_localreport" => "1", "liked_regionalreport" => "1", "liked_nationalreport" => "1", "oversight" => "", "oversight_name" => "", "oversight_email" => "", "oversight_phone" => "", "paypal_number" => "", "timeframe" => "", "address" => "", "city" => "", "state" => "", "zip_code" => "", "country" => "");
		$info = json_decode(json_encode($info), FALSE);

		if ($project_id > 0) {
			$info = DB::table("prayer") -> where("id", $project_id) -> first();
		}

		$params = array("active" => $active, "sub_active" => $sub_active, "project_id" => $project_id, "info" => $info, "message" => $message);
		return View::make("/frontend/" . $this -> _permission[Auth::user() -> permission] . "/project/prayer-edit") -> with($params);
	}

	public function report_edit($prefix, $project_id = 0, $active = "projects", $sub_active = "") {
		$message = "";
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			if ($prefix == "nationalreport") {
				$name = Input::get("name");
				$description = Input::get("description");
				$organize_option_report = Input::get("organize_option_report");
				$national_date = Input::get("national_date");
				$organize_option_day = Input::get("organize_option_day");
				$nation_prayers = Input::get("nation_prayers");
				$world_link = Input::get("world_link");
				$past_story = Input::get("past_story");
				$relevant_fact = Input::get("relevant_fact");
				$nation_need = Input::get("nation_need");
				$paypal_number = Input::get("paypal_number");
				$intro_video = Input::get("intro_video");
				$address = Input::get("address");
				$city = Input::get("city");
				$state = Input::get("state");
				$zip_code = Input::get("zip_code");
				$country = Input::get("country");

				if ($project_id == 0) {
					$project_id = DB::table("nationalreport") -> insertGetId(array("id" => null, "name" => $name, "description" => $description, "organize_option_report" => $organize_option_report, "national_date" => $national_date, "organize_option_day" => $organize_option_day, "nation_prayers" => $nation_prayers, "world_link" => $world_link, "past_story" => $past_story, "relevant_fact" => $relevant_fact, "nation_need" => $nation_need, "paypal_number" => $paypal_number, "type" => $prefix == "national" ? 1 : 2, "intro_video" => $intro_video, "address" => $address, "city" => $city, "state" => $state, "zip_code" => $zip_code, "country" => $country, "user_id" => Auth::user() -> id, "created_date" => date("Y-m-d H:i:s"), "updated_date" => null, "status" => 100));
					$this -> send_new_project_email("report", $project_id);
				} else {
					DB::table("nationalreport") -> where("id", $project_id) -> update(array("name" => $name, "description" => $description, "organize_option_report" => $organize_option_report, "national_date" => $national_date, "organize_option_day" => $organize_option_day, "nation_prayers" => $nation_prayers, "world_link" => $world_link, "past_story" => $past_story, "relevant_fact" => $relevant_fact, "nation_need" => $nation_need, "paypal_number" => $paypal_number, "intro_video" => $intro_video, "address" => $address, "city" => $city, "state" => $state, "zip_code" => $zip_code, "country" => $country, "updated_date" => date("Y-m-d H:i:s")));
				}

				if ($_FILES["thumbnail"]["tmp_name"] != "") {
					$filename = $this -> generate_rand(32);
					if (move_uploaded_file($_FILES["thumbnail"]["tmp_name"], public_path() . "/res/project/thumb/" . $filename)) {
						$url = Config::get("app.url") . "/res/project/thumb/" . $filename;

						DB::table("nationalreport") -> where("id", $project_id) -> update(array("thumbnail" => $url));
					}
				}
				/*
				 if ($_FILES["intro_video"]["tmp_name"] != "") {
				 $filename = $this -> generate_rand(32);
				 if (move_uploaded_file($_FILES["intro_video"]["tmp_name"], public_path() . "/res/project/video/" . $filename)) {
				 $url = Config::get("app.url") . "/res/project/video/" . $filename;

				 DB::table("nationalreport") -> where("id", $project_id) -> update(array("intro_video" => $url));
				 }
				 }*/
			} elseif ($prefix == "regionalreport") {
				$name = Input::get("name");
				$description = Input::get("description");
				$defined_region = Input::get("defined_region");
				$report_owner = Input::get("report_owner");
				$report_align_option = Input::get("report_align_option");
				$communication_type = Input::get("communication_type");
				$vision_statement = Input::get("vision_statement");
				$curch_use_type = Input::get("curch_use_type");
				$significance_happen = Input::get("significance_happen");
				$ancestor = Input::get("ancestor");
				$goal_strategy = Input::get("goal_strategy");
				$population = Input::get("population");
				$christian_number = Input::get("christian_number");
				$spiritual_father = Input::get("spiritual_father");
				$national_report_option = Input::get("national_report_option");
				$link = Input::get("link");
				$national_vision = Input::get("national_vision");
				$social_area = Input::get("social_area");
				$description_economy = Input::get("description_economy");
				$churches = Input::get("churches");
				$quantitatively_state = Input::get("quantitatively_state");
				$yearly_people_count = Input::get("yearly_people_count");
				$description_crime = Input::get("description_crime");
				$suicide_rate = Input::get("suicide_rate");
				$has_christian_witness = Input::get("has_christian_witness");
				$help_community = Input::get("help_community");
				$occult_activity = Input::get("occult_activity");
				$prayer_meeting = Input::get("prayer_meeting");
				$evangelism_program = Input::get("evangelism_program");
				$paypal_number = Input::get("paypal_number");
				$intro_video = Input::get("intro_video");
				$address = Input::get("address");
				$city = Input::get("city");
				$state = Input::get("state");
				$zip_code = Input::get("zip_code");
				$country = Input::get("country");

				if ($project_id == 0) {
					$project_id = DB::table("regionalreport") -> insertGetId(array("id" => null, "name" => $name, "description" => $description, "defined_region" => $defined_region, "report_owner" => $report_owner, "report_align_option" => $report_align_option, "communication_type" => $communication_type, "vision_statement" => $vision_statement, "curch_use_type" => $curch_use_type, "significance_happen" => $significance_happen, "ancestor" => $ancestor, "goal_strategy" => $goal_strategy, "population" => $population, "christian_number" => $christian_number, "spiritual_father" => $spiritual_father, "national_report_option" => $national_report_option, "link" => $link, "national_vision" => $national_vision, "social_area" => $social_area, "description_economy" => $description_economy, "churches" => $churches, "quantitatively_state" => $quantitatively_state, "yearly_people_count" => $yearly_people_count, "description_crime" => $description_crime, "suicide_rate" => $suicide_rate, "has_christian_witness" => $has_christian_witness, "help_community" => $help_community, "occult_activity" => $occult_activity, "prayer_meeting" => $prayer_meeting, "evangelism_program" => $evangelism_program, "paypal_number" => $paypal_number, "intro_video" => $intro_video, "address" => $address, "city" => $city, "state" => $state, "zip_code" => $zip_code, "country" => $country, "created_date" => date("Y-m-d H:i:s"), "updated_date" => null, "user_id" => Auth::user() -> id, "status" => 100));
					$this -> send_new_project_email("report", $project_id);
				} else {
					DB::table("regionalreport") -> where("id", $project_id) -> update(array("name" => $name, "description" => $description, "defined_region" => $defined_region, "report_owner" => $report_owner, "report_align_option" => $report_align_option, "communication_type" => $communication_type, "vision_statement" => $vision_statement, "curch_use_type" => $curch_use_type, "significance_happen" => $significance_happen, "ancestor" => $ancestor, "goal_strategy" => $goal_strategy, "population" => $population, "christian_number" => $christian_number, "spiritual_father" => $spiritual_father, "national_report_option" => $national_report_option, "link" => $link, "national_vision" => $national_vision, "social_area" => $social_area, "description_economy" => $description_economy, "churches" => $churches, "quantitatively_state" => $quantitatively_state, "yearly_people_count" => $yearly_people_count, "description_crime" => $description_crime, "suicide_rate" => $suicide_rate, "has_christian_witness" => $has_christian_witness, "help_community" => $help_community, "occult_activity" => $occult_activity, "prayer_meeting" => $prayer_meeting, "evangelism_program" => $evangelism_program, "paypal_number" => $paypal_number, "intro_video" => $intro_video, "address" => $address, "city" => $city, "state" => $state, "zip_code" => $zip_code, "country" => $country, "updated_date" => date("Y-m-d H:i:s")));
				}

				if ($_FILES["thumbnail"]["tmp_name"] != "") {
					$filename = $this -> generate_rand(32);
					if (move_uploaded_file($_FILES["thumbnail"]["tmp_name"], public_path() . "/res/project/thumb/" . $filename)) {
						$url = Config::get("app.url") . "/res/project/thumb/" . $filename;

						DB::table("regionalreport") -> where("id", $project_id) -> update(array("thumbnail" => $url));
					}
				}
				/*
				 if ($_FILES["intro_video"]["tmp_name"] != "") {
				 $filename = $this -> generate_rand(32);
				 if (move_uploaded_file($_FILES["intro_video"]["tmp_name"], public_path() . "/res/project/video/" . $filename)) {
				 $url = Config::get("app.url") . "/res/project/video/" . $filename;

				 DB::table("regionalreport") -> where("id", $project_id) -> update(array("intro_video" => $url));
				 }
				 }*/
			}

			$message = "<div class='alert alert-success alert-dismissable'>
                            <button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button>
                            Project information has been saved successfully.
                        </div>";
		}

		$info = array();

		if ($prefix == "nationalreport") {
			$info = array("name" => "", "description" => "", "country" => "", "organize_option_report" => 1, "national_date" => "", "organize_option_day" => 1, "nation_prayers" => "", "thumbnail" => "", "intro_video" => "", "world_link" => "", "past_story" => "", "relevant_fact" => "", "nation_need" => "", "paypal_number" => "", "address" => "", "city" => "", "state" => "", "zip_code" => "", "country" => "");
		} elseif ($prefix == "regionalreport") {
			$info = array("name" => "", "description" => "", "defined_region" => "", "report_owner" => "", "report_align_option" => 1, "communication_type" => 1, "vision_statement" => "", "curch_use_type" => 1, "significance_happen" => "", "ancestor" => "", "goal_strategy" => "", "population" => "", "christian_number" => "", "spiritual_father" => "", "national_report_option" => 1, "link" => "", "national_vision" => "", "social_area" => "", "description_economy" => "", "churches" => "", "quantitatively_state" => "", "yearly_people_count" => "", "description_crime" => "", "suicide_rate" => "", "has_christian_witness" => "", "help_community" => "", "occult_activity" => "", "prayer_meeting" => "", "evangelism_program" => "", "paypal_number" => "", "thumbnail" => "", "intro_video" => "", "address" => "", "city" => "", "state" => "", "zip_code" => "", "country" => "");
		}

		$info = json_decode(json_encode($info), FALSE);

		if ($project_id > 0) {
			$info = DB::table($prefix) -> where("id", $project_id) -> first();
		}

		$params = array("active" => $active, "sub_active" => $sub_active, "prefix" => $prefix, "project_id" => $project_id, "info" => $info, "message" => $message);
		return View::make("/frontend/" . $this -> _permission[Auth::user() -> permission] . "/project/" . $prefix . "-edit") -> with($params);
	}

	public function teaching_edit($project_id = 0, $active = "projects", $sub_active = "") {
		$message = "";
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$name = Input::get("name");
			$description = Input::get("description");
			$propose_option = Input::get("propose_option");
			$charge_option = Input::get("charge_option");
			$teaching_material = Input::get("teaching_material");
			$run_meeting = Input::get("run_meeting");
			$match_option = Input::get("match_option");
			$paypal_number = Input::get("paypal_number");
			$intro_video = Input::get("intro_video");
			$address = Input::get("address");
			$city = Input::get("city");
			$state = Input::get("state");
			$zip_code = Input::get("zip_code");
			$country = Input::get("country");

			if ($project_id == 0) {
				$project_id = DB::table("teaching") -> insertGetId(array("id" => null, "name" => $name, "description" => $description, "propose_option" => $propose_option, "charge_option" => $charge_option, "teaching_material" => $teaching_material, "run_meeting" => $run_meeting, "match_option" => $match_option, "paypal_number" => $paypal_number, "intro_video" => $intro_video, "address" => $address, "city" => $city, "state" => $state, "zip_code" => $zip_code, "country" => $country, "user_id" => Auth::user() -> id, "created_date" => date("Y-m-d H:i:s"), "updated_date" => null, "status" => 100));
				$this -> send_new_project_email("teaching", $project_id);
			} else {
				DB::table("teaching") -> where("id", $project_id) -> update(array("name" => $name, "description" => $description, "propose_option" => $propose_option, "charge_option" => $charge_option, "teaching_material" => $teaching_material, "run_meeting" => $run_meeting, "match_option" => $match_option, "paypal_number" => $paypal_number, "intro_video" => $intro_video, "address" => $address, "city" => $city, "state" => $state, "zip_code" => $zip_code, "country" => $country, "updated_date" => date("Y-m-d H:i:s")));
			}

			if ($_FILES["thumbnail"]["tmp_name"] != "") {
				$filename = $this -> generate_rand(32);
				if (move_uploaded_file($_FILES["thumbnail"]["tmp_name"], public_path() . "/res/project/thumb/" . $filename)) {
					$url = Config::get("app.url") . "/res/project/thumb/" . $filename;

					DB::table("teaching") -> where("id", $project_id) -> update(array("thumbnail" => $url));
				}
			}
			/*
			 if ($_FILES["intro_video"]["tmp_name"] != "") {
			 $filename = $this -> generate_rand(32);
			 if (move_uploaded_file($_FILES["intro_video"]["tmp_name"], public_path() . "/res/project/video/" . $filename)) {
			 $url = Config::get("app.url") . "/res/project/video/" . $filename;

			 DB::table("teaching") -> where("id", $project_id) -> update(array("intro_video" => $url));
			 }
			 }
			 */
			$message = "<div class='alert alert-success alert-dismissable'>
                            <button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button>
                            Project information has been saved successfully.
                        </div>";
		}

		$info = array("name" => "", "description" => "", "propose_option" => 1, "charge_option" => 1, "teaching_material" => "", "run_meeting" => "", "thumbnail" => "", "intro_video" => "", "match_option" => 1, "paypal_number" => "", "address" => "", "city" => "", "state" => "", "zip_code" => "", "country" => "");
		$info = json_decode(json_encode($info), FALSE);

		if ($project_id > 0) {
			$info = DB::table("teaching") -> where("id", $project_id) -> first();
		}

		$params = array("active" => $active, "sub_active" => $sub_active, "project_id" => $project_id, "info" => $info, "message" => $message);
		return View::make("/frontend/" . $this -> _permission[Auth::user() -> permission] . "/project/teaching-edit") -> with($params);
	}

	public function impact_events($project_id, $active = "projects", $sub_active = "") {
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$ids = Input::get("selected_id");
			DB::table("impact_event") -> whereIn('id', explode(",", $ids)) -> update(array("status" => -1));

			if (Auth::user() -> permission == 100) :
				return Redirect::to("/projects/impact/" . $project_id . "/events");
			else :
				return Redirect::to("/manages/projects/impact/" . $project_id . "/events");
			endif;
		}

		$project = DB::table("impact") -> where("id", $project_id) -> first();
		$sql = "select 	a.*, count(b.user_id) as joined_count 
				from 	impact_event a left join impact_event_join b on a.id = b.event_id 
				where 	a.project_id = $project_id and a.status = 1
				GROUP BY a.id
				order by event_date desc";
		$events = DB::select($sql);
		$params = array("active" => $active, "sub_active" => $sub_active, "project_id" => $project_id, "project_title" => $project -> name, "events" => $events);
		return View::make("/frontend/" . $this -> _permission[Auth::user() -> permission] . "/project/impactevents") -> with($params);
	}

	public function impact_events_edit($project_id, $event_id = 0, $active = "projects", $sub_active = "") {
		$message = "";
		$project = DB::table("impact") -> where("id", $project_id) -> first();
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
				DB::table("impact_event") -> where("id", $event_id) -> update(array("title" => $title, "description" => $description, "cost" => $cost, "address" => $address, "city" => $city, "state" => $state, "zip_code" => $zip_code, "country" => $country, "longitude" => $longitude, "latitude" => $latitude, "contact_details" => $contact_details, "event_date" => $event_date . " " . $event_hour . ":" . $event_minute . ":00"));
			} else {
				$event_id = DB::table("impact_event") -> insertGetId(array("id" => null, "project_id" => $project_id, "title" => $title, "description" => $description, "cost" => $cost, "address" => $address, "city" => $city, "state" => $state, "zip_code" => $zip_code, "country" => $country, "longitude" => $longitude, "latitude" => $latitude, "contact_details" => $contact_details, "event_date" => $event_date . " " . $event_hour . ":" . $event_minute . ":00", "created_date" => date("Y-m-d"), "status" => 1));
				$this -> send_event_email("impact", $project_id, $event_id);
			}

			if (isset($_FILES["thumbnail"]) && $_FILES["thumbnail"]["tmp_name"] != "") {
				$filename = $this -> generate_rand(32);
				if (move_uploaded_file($_FILES["thumbnail"]["tmp_name"], public_path() . "/res/project/thumb/" . $filename)) {
					$url = Config::get("app.url") . "/res/project/thumb/" . $filename;

					DB::table("impact_event") -> where("id", $event_id) -> update(array("thumbnail" => $url));
				}
			}

			$message = "<div class='alert alert-success alert-dismissable'>
                            <button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button>
                            Project event has been saved successfully.
                        </div>";
		}

		$info = array("title" => "", "description" => "", "cost" => "", "address" => "", "city" => "", "state" => "", "zip_code" => "", "country" => "", "longitude" => 0, "latitude" => 0, "thumbnail" => "", "contact_details" => "", "event_date" => "", "event_hour" => "", "event_minute" => "");
		$info = json_decode(json_encode($info), FALSE);

		if ($event_id > 0) {
			$info = DB::table("impact_event") -> where("id", $event_id) -> first();
			$dd = $info -> event_date;
			$info -> event_date = date("Y-m-d", strtotime($dd));
			$info -> event_hour = date("H", strtotime($dd));
			$info -> event_minute = date("i", strtotime($dd));
		}

		$params = array("active" => $active, "sub_active" => $sub_active, "project_id" => $project_id, "project_title" => $project -> name, "event_id" => $event_id, "info" => $info, "message" => $message);
		return View::make("/frontend/" . $this -> _permission[Auth::user() -> permission] . "/project/impactevents-edit") -> with($params);
	}

	public function prayer_events($project_id, $active = "projects", $sub_active = "") {
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$ids = Input::get("selected_id");
			DB::table("prayer_event") -> whereIn('id', explode(",", $ids)) -> update(array("status" => -1));

			if (Auth::user() -> permission == 100) :
				return Redirect::to("/projects/prayer/" . $project_id . "/events");
			else :
				return Redirect::to("/manages/projects/prayer/" . $project_id . "/events");
			endif;
		}

		$project = DB::table("prayer") -> where("id", $project_id) -> first();
		$sql = "select 	a.*, count(b.user_id) as joined_count 
				from 	prayer_event a left join prayer_event_join b on a.id = b.event_id 
				where 	a.project_id = $project_id and a.status = 1
				GROUP BY a.id
				order by event_date desc";
		$events = DB::select($sql);
		$params = array("active" => $active, "sub_active" => $sub_active, "project_id" => $project_id, "project_title" => $project -> name, "events" => $events);
		return View::make("/frontend/" . $this -> _permission[Auth::user() -> permission] . "/project/prayerevents") -> with($params);
	}

	public function prayer_events_edit($project_id, $event_id = 0, $active = "projects", $sub_active = "") {
		$message = "";
		$project = DB::table("prayer") -> where("id", $project_id) -> first();
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
				DB::table("prayer_event") -> where("id", $event_id) -> update(array("title" => $title, "description" => $description, "cost" => $cost, "address" => $address, "city" => $city, "state" => $state, "zip_code" => $zip_code, "country" => $country, "longitude" => $longitude, "latitude" => $latitude, "contact_details" => $contact_details, "event_date" => $event_date . " " . $event_hour . ":" . $event_minute . ":00"));
			} else {
				$event_id = DB::table("prayer_event") -> insertGetId(array("id" => null, "project_id" => $project_id, "title" => $title, "description" => $description, "cost" => $cost, "address" => $address, "city" => $city, "state" => $state, "zip_code" => $zip_code, "country" => $country, "longitude" => $longitude, "latitude" => $latitude, "contact_details" => $contact_details, "event_date" => $event_date . " " . $event_hour . ":" . $event_minute . ":00", "created_date" => date("Y-m-d"), "status" => 1));
				$this -> send_event_email("prayer", $project_id, $event_id);
			}

			if (isset($_FILES["thumbnail"]) && $_FILES["thumbnail"]["tmp_name"] != "") {
				$filename = $this -> generate_rand(32);
				if (move_uploaded_file($_FILES["thumbnail"]["tmp_name"], public_path() . "/res/project/thumb/" . $filename)) {
					$url = Config::get("app.url") . "/res/project/thumb/" . $filename;

					DB::table("prayer_event") -> where("id", $event_id) -> update(array("thumbnail" => $url));
				}
			}

			$message = "<div class='alert alert-success alert-dismissable'>
                            <button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button>
                            Project event has been saved successfully.
                        </div>";
		}

		$info = array("title" => "", "description" => "", "cost" => "", "address" => "", "city" => "", "state" => "", "zip_code" => "", "country" => "", "longitude" => 0, "latitude" => 0, "thumbnail" => "", "contact_details" => "", "event_date" => "", "event_hour" => "", "event_minute" => "");
		$info = json_decode(json_encode($info), FALSE);

		if ($event_id > 0) {
			$info = DB::table("prayer_event") -> where("id", $event_id) -> first();
			$dd = $info -> event_date;
			$info -> event_date = date("Y-m-d", strtotime($dd));
			$info -> event_hour = date("H", strtotime($dd));
			$info -> event_minute = date("i", strtotime($dd));
		}

		$params = array("active" => $active, "sub_active" => $sub_active, "project_id" => $project_id, "project_title" => $project -> name, "event_id" => $event_id, "info" => $info, "message" => $message);
		return View::make("/frontend/" . $this -> _permission[Auth::user() -> permission] . "/project/prayerevents-edit") -> with($params);
	}

	public function report_events($type, $project_id, $active = "projects", $sub_active = "") {
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$ids = Input::get("selected_id");
			DB::table($type . "_event") -> whereIn('id', explode(",", $ids)) -> update(array("status" => -1));

			if (Auth::user() -> permission == 100) :
				return Redirect::to("/projects/report/" . $type . "/" . $project_id . "/events");
			else :
				return Redirect::to("/manages/projects/report/" . $type . "/" . $project_id . "/events");
			endif;
		}

		$project = DB::table($type) -> where("id", $project_id) -> first();
		$sql = "select 	a.*, count(b.user_id) as joined_count 
				from 	" . $type . "_event a left join " . $type . "_event_join b on a.id = b.event_id 
				where 	a.project_id = $project_id and a.status = 1
				GROUP BY a.id
				order by event_date desc";
		$events = DB::select($sql);

		$params = array("active" => $active, "sub_active" => $sub_active, "type" => $type, "project_id" => $project_id, "project_title" => $project -> name, "events" => $events);
		return View::make("/frontend/" . $this -> _permission[Auth::user() -> permission] . "/project/reportevents") -> with($params);
	}

	public function report_events_edit($type, $project_id, $event_id = 0, $active = "projects", $sub_active = "") {
		$message = "";
		$project = DB::table($type) -> where("id", $project_id) -> first();
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
				DB::table($type . "_event") -> where("id", $event_id) -> update(array("title" => $title, "description" => $description, "cost" => $cost, "address" => $address, "city" => $city, "state" => $state, "zip_code" => $zip_code, "country" => $country, "longitude" => $longitude, "latitude" => $latitude, "contact_details" => $contact_details, "event_date" => $event_date . " " . $event_hour . ":" . $event_minute . ":00"));
			} else {
				$event_id = DB::table($type . "_event") -> insertGetId(array("id" => null, "project_id" => $project_id, "title" => $title, "description" => $description, "cost" => $cost, "address" => $address, "city" => $city, "state" => $state, "zip_code" => $zip_code, "country" => $country, "longitude" => $longitude, "latitude" => $latitude, "contact_details" => $contact_details, "event_date" => $event_date . " " . $event_hour . ":" . $event_minute . ":00", "created_date" => date("Y-m-d"), "status" => 1));
				$this -> send_event_email($type, $project_id, $event_id);
			}

			if (isset($_FILES["thumbnail"]) && $_FILES["thumbnail"]["tmp_name"] != "") {
				$filename = $this -> generate_rand(32);
				if (move_uploaded_file($_FILES["thumbnail"]["tmp_name"], public_path() . "/res/project/thumb/" . $filename)) {
					$url = Config::get("app.url") . "/res/project/thumb/" . $filename;

					DB::table($type . "_event") -> where("id", $event_id) -> update(array("thumbnail" => $url));
				}
			}

			$message = "<div class='alert alert-success alert-dismissable'>
                            <button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button>
                            Project event has been saved successfully.
                        </div>";
		}

		$info = array("title" => "", "description" => "", "cost" => "", "address" => "", "city" => "", "state" => "", "zip_code" => "", "country" => "", "longitude" => 0, "latitude" => 0, "thumbnail" => "", "contact_details" => "", "event_date" => "", "event_hour" => "", "event_minute" => "");
		$info = json_decode(json_encode($info), FALSE);

		if ($event_id > 0) {
			$info = DB::table($type . "_event") -> where("id", $event_id) -> first();
			$dd = $info -> event_date;
			$info -> event_date = date("Y-m-d", strtotime($dd));
			$info -> event_hour = date("H", strtotime($dd));
			$info -> event_minute = date("i", strtotime($dd));
		}

		$params = array("active" => $active, "sub_active" => $sub_active, "type" => $type, "project_id" => $project_id, "project_title" => $project -> name, "event_id" => $event_id, "info" => $info, "message" => $message);
		return View::make("/frontend/" . $this -> _permission[Auth::user() -> permission] . "/project/reportevents-edit") -> with($params);
	}

	public function teaching_events($project_id, $active = "projects", $sub_active = "") {
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$ids = Input::get("selected_id");
			DB::table("teaching_event") -> whereIn('id', explode(",", $ids)) -> update(array("status" => -1));

			if (Auth::user() -> permission == 100) :
				return Redirect::to("/projects/teaching/" . $project_id . "/events");
			else :
				return Redirect::to("/manages/projects/teaching/" . $project_id . "/events");
			endif;
		}

		$project = DB::table("teaching") -> where("id", $project_id) -> first();
		$sql = "select 	a.*, count(b.user_id) as joined_count 
				from 	teaching_event a left join teaching_event_join b on a.id = b.event_id 
				where 	a.project_id = $project_id and a.status = 1
				GROUP BY a.id
				order by event_date desc";
		$events = DB::select($sql);
		$params = array("active" => $active, "sub_active" => $sub_active, "project_id" => $project_id, "project_title" => $project -> name, "events" => $events);
		return View::make("/frontend/" . $this -> _permission[Auth::user() -> permission] . "/project/teachingevents") -> with($params);
	}

	public function teaching_events_edit($project_id, $event_id = 0, $active = "projects", $sub_active = "") {
		$message = "";
		$project = DB::table("teaching") -> where("id", $project_id) -> first();
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
				DB::table("teaching_event") -> where("id", $event_id) -> update(array("title" => $title, "description" => $description, "cost" => $cost, "address" => $address, "city" => $city, "state" => $state, "zip_code" => $zip_code, "country" => $country, "longitude" => 0, "latitude" => 0, "longitude" => $longitude, "latitude" => $latitude, "contact_details" => $contact_details, "event_date" => $event_date . " " . $event_hour . ":" . $event_minute . ":00"));
			} else {
				$event_id = DB::table("teaching_event") -> insertGetId(array("id" => null, "project_id" => $project_id, "title" => $title, "description" => $description, "cost" => $cost, "address" => $address, "city" => $city, "state" => $state, "zip_code" => $zip_code, "country" => $country, "longitude" => $longitude, "latitude" => $latitude, "contact_details" => $contact_details, "event_date" => $event_date . " " . $event_hour . ":" . $event_minute . ":00", "created_date" => date("Y-m-d"), "status" => 1));
				$this -> send_event_email("teaching", $project_id, $event_id);
			}

			if (isset($_FILES["thumbnail"]) && $_FILES["thumbnail"]["tmp_name"] != "") {
				$filename = $this -> generate_rand(32);
				if (move_uploaded_file($_FILES["thumbnail"]["tmp_name"], public_path() . "/res/project/thumb/" . $filename)) {
					$url = Config::get("app.url") . "/res/project/thumb/" . $filename;

					DB::table("teaching_event") -> where("id", $event_id) -> update(array("thumbnail" => $url));
				}
			}

			$message = "<div class='alert alert-success alert-dismissable'>
                            <button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button>
                            Project event has been saved successfully.
                        </div>";
		}

		$info = array("title" => "", "description" => "", "cost" => "", "address" => "", "city" => "", "state" => "", "zip_code" => "", "country" => "", "longitude" => 0, "latitude" => 0, "thumbnail" => "", "contact_details" => "", "event_date" => "", "event_hour" => "", "event_minute" => "");
		$info = json_decode(json_encode($info), FALSE);

		if ($event_id > 0) {
			$info = DB::table("teaching_event") -> where("id", $event_id) -> first();
			$dd = $info -> event_date;
			$info -> event_date = date("Y-m-d", strtotime($dd));
			$info -> event_hour = date("H", strtotime($dd));
			$info -> event_minute = date("i", strtotime($dd));
		}

		$params = array("active" => $active, "sub_active" => $sub_active, "project_id" => $project_id, "project_title" => $project -> name, "event_id" => $event_id, "info" => $info, "message" => $message);
		return View::make("/frontend/" . $this -> _permission[Auth::user() -> permission] . "/project/teachingevents-edit") -> with($params);
	}

	public function search_for($prefix) {
		$search_key = isset($_POST["search_key"]) ? $_POST["search_key"] : "";

		$country = Input::get("country");
		$state = Input::get("state");
		$city = Input::get("city");
		$zip_code = Input::get("zip_code");

		$params = array("active" => "search", "prefix" => $prefix, "title" => "Project Title", "search_key" => $search_key);

		if ($prefix == "testimony") {
			$result = $search_key == "" ? array() : DB::select("select a.*, b.* from users a, user_profile b where a.permission != -1 and a.id = b.user_id and b.testimony like '%$search_key%'");
			$params["result"] = $result;
			$params["total"] = count($result);
		} elseif ($prefix == "mission") {
			$result = $search_key == "" ? array() : DB::select("select a.*, b.* from users a, user_profile b where a.permission != -1 and a.id = b.user_id and b.mission_statement like '%$search_key%'");
			$params["result"] = $result;
			$params["total"] = count($result);
		} elseif ($prefix == "gifts") {
			$result = $search_key == "" ? array() : DB::select("select a.*, b.* from users a, user_profile b where a.permission != -1 and a.id = b.user_id and b.skill_gifts like '%$search_key%'");
			$params["result"] = $result;
			$params["total"] = count($result);
		} elseif ($prefix == "goals") {
			$result = $search_key == "" ? array() : DB::select("select a.*, b.* from users a, user_profile b where a.permission != -1 and a.id = b.user_id and b.goals like '%$search_key%'");
			$params["result"] = $result;
			$params["total"] = count($result);
		} elseif ($prefix == "facilitator") {
			$result = $search_key == "" ? array() : DB::select("select a.*, b.* from users a, user_profile b 
																where a.id = b.user_id and a.permission != -1 
																and (a.first_name like '%$search_key%' or a.last_name like '%$search_key%')");
			$params["result"] = $result;
			$params["total"] = count($result);
		} elseif ($prefix == "project") {
			$result = DB::select("SELECT 	a.id, a.name, a.thumbnail, 
								(CASE WHEN AVG(b.mark) IS NULL THEN 0 ELSE AVG(b.mark) END) AS review,
								(select COUNT(user_id) from prayer_follow where project_id = a.id) AS follow_count,
								a.created_date,
								'prayer' AS project_type
							FROM 	prayer a
								LEFT JOIN prayer_review b ON a.id = b.project_id
								LEFT JOIN prayer_follow c ON a.id = c.project_id,
								region_allocate d, region_manager e
							WHERE 	a.status = 1
								AND a.id = d.project_id
								AND d.project_type = 'prayer'
								AND d.region_id = e.id
								AND (a.name like '%$search_key%' or a.description like '%$search_key%')
								" . ($country != "" ? " AND e.country = '$country' " : "") . "
								" . ($state != "" ? " AND e.state = '$state' " : "") . "
								" . ($city != "" ? " AND a.city = '$city' " : "") . "
								" . ($zip_code != "" ? " AND a.zip_code = '$zip_code' " : "") . "
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
								region_allocate d, region_manager e
							WHERE 	a.status = 1
								AND a.id = d.project_id
								AND d.project_type = 'impact'
								AND d.region_id = e.id
								AND (a.name like '%$search_key%' or a.description like '%$search_key%')
								" . ($country != "" ? " AND e.country = '$country' " : "") . "
								" . ($state != "" ? " AND e.state = '$state' " : "") . "
								" . ($city != "" ? " AND a.city = '$city' " : "") . "
								" . ($zip_code != "" ? " AND a.zip_code = '$zip_code' " : "") . "
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
								region_allocate d, region_manager e
							WHERE 	a.status = 1
								AND a.id = d.project_id
								AND d.project_type = 'teaching'
								AND d.region_id = e.id
								AND (a.name like '%$search_key%' or a.description like '%$search_key%')
								" . ($country != "" ? " AND e.country = '$country' " : "") . "
								" . ($state != "" ? " AND e.state = '$state' " : "") . "
								" . ($city != "" ? " AND a.city = '$city' " : "") . "
								" . ($zip_code != "" ? " AND a.zip_code = '$zip_code' " : "") . "
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
								region_allocate d, region_manager e
							WHERE 	a.status = 1
								AND a.id = d.project_id
								AND d.project_type = 'nationalreport'
								AND d.region_id = e.id
								AND (a.name like '%$search_key%' or a.description like '%$search_key%')
								" . ($country != "" ? " AND e.country = '$country' " : "") . "
								" . ($state != "" ? " AND e.state = '$state' " : "") . "
								" . ($city != "" ? " AND a.city = '$city' " : "") . "
								" . ($zip_code != "" ? " AND a.zip_code = '$zip_code' " : "") . "
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
								region_allocate d, region_manager e
							WHERE 	a.status = 1
								AND a.id = d.project_id
								AND d.project_type = 'regionalreport'
								AND d.region_id = e.id
								AND (a.name like '%$search_key%' or a.description like '%$search_key%')
								" . ($country != "" ? " AND e.country = '$country' " : "") . "
								" . ($state != "" ? " AND e.state = '$state' " : "") . "
								" . ($city != "" ? " AND a.city = '$city' " : "") . "
								" . ($zip_code != "" ? " AND a.zip_code = '$zip_code' " : "") . "
							GROUP BY a.id
							ORDER BY review DESC, created_date DESC");

			$countries = DB::table('region_manager') -> select('country') -> distinct() -> get();
			$states = DB::table('region_manager') -> select('state') -> distinct() -> get();

			//city
			$cities = DB::select("select distinct city from impact where status = 1 AND city IS NOT NULL
								union select distinct city from prayer where status = 1 AND city IS NOT NULL
								union select distinct city from teaching where status = 1 AND city IS NOT NULL
								union select distinct city from nationalreport where status = 1 AND city IS NOT NULL
								union select distinct city from regionalreport where status = 1 AND city IS NOT NULL
								order by city");
			//zip_code
			$zip_codes = DB::select("select distinct zip_code from impact where status = 1 AND zip_code IS NOT NULL
									union select distinct zip_code from prayer where status = 1 AND zip_code IS NOT NULL
									union select distinct zip_code from teaching where status = 1 AND zip_code IS NOT NULL
									union select distinct zip_code from nationalreport where status = 1 AND zip_code IS NOT NULL
									union select distinct zip_code from regionalreport where status = 1 AND zip_code IS NOT NULL
									order by zip_code");

			$params["result"] = $result;
			$params["total"] = count($result);
			$params["redirect_url"] = "/search/project";
			$params["countries"] = $countries;
			$params["states"] = $states;
			$params["cities"] = $cities;
			$params["zip_codes"] = $zip_codes;
			$params["country"] = $country;
			$params["state"] = $state;
			$params["city"] = $city;
			$params["zip_code"] = $zip_code;
		} elseif ($prefix == "nationalreport") {
			$result = $search_key == "" ? array() : DB::select("SELECT 	a.id, a.name, a.thumbnail, 
								(CASE WHEN AVG(b.mark) IS NULL THEN 0 ELSE AVG(b.mark) END) AS review,
								(select COUNT(user_id) from prayer_follow where project_id = a.id) AS follow_count,
								a.created_date
							FROM 	nationalreport a
								LEFT JOIN nationalreport_review b ON a.id = b.project_id
								LEFT JOIN nationalreport_follow c ON a.id = c.project_id,
								region_allocate d, region_manager e
							WHERE 	a.status = 1
								AND a.id = d.project_id
								AND d.project_type = 'nationalreport'
								AND d.region_id = e.id
								AND (a.name like '%$search_key%' or a.description like '%$search_key%')
								" . ($country != "" ? " AND e.country = '$country' " : "") . "
								" . ($state != "" ? " AND e.state = '$state' " : "") . "
							GROUP BY a.id
							ORDER BY review DESC, created_date DESC");
			$params["result"] = $result;
			$params["total"] = count($result);
			$params["redirect_url"] = "/search/nationalreport";
		} elseif ($prefix == "regionalreport") {
			$result = $search_key == "" ? array() : DB::select("SELECT 	a.id, a.name, a.thumbnail, 
								(CASE WHEN AVG(b.mark) IS NULL THEN 0 ELSE AVG(b.mark) END) AS review,
								(select COUNT(user_id) from prayer_follow where project_id = a.id) AS follow_count,
								a.created_date
							FROM 	regionalreport a
								LEFT JOIN regionalreport_review b ON a.id = b.project_id
								LEFT JOIN regionalreport_follow c ON a.id = c.project_id,
								region_allocate d, region_manager e
							WHERE 	a.status = 1
								AND a.id = d.project_id
								AND d.project_type = 'regionalreport'
								AND d.region_id = e.id
								AND (a.name like '%$search_key%' or a.description like '%$search_key%')
								" . ($country != "" ? " AND e.country = '$country' " : "") . "
								" . ($state != "" ? " AND e.state = '$state' " : "") . "
							GROUP BY a.id
							ORDER BY review DESC, created_date DESC");
			$params["result"] = $result;
			$params["total"] = count($result);
			$params["redirect_url"] = "/search/regionalreport";
		}

		return View::make("/frontend/" . $this -> _permission[Auth::user() -> permission] . "/search/" . $prefix) -> with($params);
	}

	public function view($prefix, $type, $id) {
		$params = array("active" => "search", "prefix" => $prefix, "title" => "Project Title");
		$prefix_arr = array("testimony", "mission", "gifts", "goals", "facilitator");

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

					$project = DB::table($type) -> where("id", $id) -> first();

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
						<h4>Facilitator posted a news.</h4>
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

							$return_url = Config::get("app.url") . "/search/project/" . $type . "/" . $id . "/event/" . $event_id . "/join/success/" . $transactonid;
							$cancel_url = Config::get("app.url") . "/search/project/" . $type . "/" . $id . "/event/" . $event_id . "/join/cancel/" . $transactonid;

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
					$this -> project_donation($type, $id, $amount);
					break;
			endswitch;

			if (in_array($prefix, $prefix_arr)) {
				return Redirect::to("/search/" . $prefix . "/single/view/" . $id);
			} else {
				return Redirect::to("/search/" . $prefix . "/" . $type . "/view/" . $id);
			}
		}

		$is_region_manager = 0;
		if (in_array($prefix, $prefix_arr)) {
			$info = DB::select("select a.*, b.* from users a, user_profile b where a.id = b.user_id and a.id = ?", array($id));

			$sql = "SELECT 	a.id, a.name, 
						(CASE WHEN AVG(b.mark) IS NULL THEN 0 ELSE AVG(b.mark) END) AS review,
						(SELECT COUNT(user_id) FROM impact_follow WHERE project_id = a.id) AS follow_count,
						(SELECT COUNT(user_id) FROM impact_review WHERE project_id = a.id) AS review_count,
						(SELECT COUNT(id) FROM impact_event WHERE project_id = a.id AND STATUS = 1) AS event_count,
						'impact' as type
					FROM 	impact a
						LEFT JOIN impact_review b ON a.id = b.project_id, region_allocate c
					WHERE 	a.user_id = $id and a.status = 1
						AND a.id = c.project_id AND c.project_type = 'impact'
					GROUP BY a.id
					UNION
					SELECT 	a.id, a.name, 
						(CASE WHEN AVG(b.mark) IS NULL THEN 0 ELSE AVG(b.mark) END) AS review,
						(SELECT COUNT(user_id) FROM prayer_follow WHERE project_id = a.id) AS follow_count,
						(SELECT COUNT(user_id) FROM prayer_review WHERE project_id = a.id) AS review_count,
						(SELECT COUNT(id) FROM prayer_event WHERE project_id = a.id AND STATUS = 1) AS event_count,
						'prayer' as type
					FROM 	prayer a
						LEFT JOIN prayer_review b ON a.id = b.project_id, region_allocate c
					WHERE 	a.user_id = $id and a.status = 1
						AND a.id = c.project_id AND c.project_type = 'prayer'
					GROUP BY a.id
					UNION
					SELECT 	a.id, a.name, 
						(CASE WHEN AVG(b.mark) IS NULL THEN 0 ELSE AVG(b.mark) END) AS review,
						(SELECT COUNT(user_id) FROM nationalreport_follow WHERE project_id = a.id) AS follow_count,
						(SELECT COUNT(user_id) FROM nationalreport_review WHERE project_id = a.id) AS review_count,
						(SELECT COUNT(id) FROM nationalreport_event WHERE project_id = a.id AND STATUS = 1) AS event_count,
						'nationalreport' as type
					FROM 	nationalreport a
						LEFT JOIN nationalreport_review b ON a.id = b.project_id, region_allocate c
					WHERE 	a.user_id = $id and a.status = 1
						AND a.id = c.project_id AND c.project_type = 'nationalreport'
					GROUP BY a.id
					UNION
					SELECT 	a.id, a.name, 
						(CASE WHEN AVG(b.mark) IS NULL THEN 0 ELSE AVG(b.mark) END) AS review,
						(SELECT COUNT(user_id) FROM regionalreport_follow WHERE project_id = a.id) AS follow_count,
						(SELECT COUNT(user_id) FROM regionalreport_review WHERE project_id = a.id) AS review_count,
						(SELECT COUNT(id) FROM regionalreport_event WHERE project_id = a.id AND STATUS = 1) AS event_count,
						'regionalreport' as type
					FROM 	regionalreport a
						LEFT JOIN regionalreport_review b ON a.id = b.project_id, region_allocate c
					WHERE 	a.user_id = $id and a.status = 1
						AND a.id = c.project_id AND c.project_type = 'regionalreport'
					GROUP BY a.id
					UNION
					SELECT 	a.id, a.name, 
						(CASE WHEN AVG(b.mark) IS NULL THEN 0 ELSE AVG(b.mark) END) AS review,
						(SELECT COUNT(user_id) FROM teaching_follow WHERE project_id = a.id) AS follow_count,
						(SELECT COUNT(user_id) FROM teaching_review WHERE project_id = a.id) AS review_count,
						(SELECT COUNT(id) FROM teaching_event WHERE project_id = a.id AND STATUS = 1) AS event_count,
						'teaching' as type
					FROM 	teaching a
						LEFT JOIN teaching_review b ON a.id = b.project_id, region_allocate c
					WHERE 	a.user_id = $id and a.status = 1
						AND a.id = c.project_id AND c.project_type = 'teaching'
					GROUP BY a.id
					order by follow_count desc, review_count desc, review desc";
			$projects = DB::select($sql);
			$params["profile"] = $info[0];
			$params["projects"] = $projects;
			return View::make("/frontend/" . $this -> _permission[Auth::user() -> permission] . "/search/" . $prefix . "_view") -> with($params);
		} else {
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

			$communications = DB::table($type . "_communication") -> where("project_id", $id) -> orderby("created_date", "desc") -> get();

			$sql = "SELECT 	a.id, a.title, a.event_date, 
						CASE WHEN b.user_id IS NULL OR b.status != 1 THEN 0 ELSE 1 END AS is_joined
				FROM 	" . $type . "_event a
					LEFT JOIN " . $type . "_event_join b ON a.id = b.event_id AND b.user_id = " . Auth::user() -> id . " AND b.status = 1
				WHERE	a.project_id = " . $id . " and a.status = 1
				GROUP BY a.id";
			$events = DB::select($sql);

			$region_manager = DB::select("select count(*) as count 
										from region_manager a, region_allocate b 
										where a.id = b.region_id
											and b.project_id = " . $id . "
											and b.project_type = '" . $type . "'
											and a.user_id = " . Auth::user() -> id);

			$redirect_url = $type == "prayer" || $type == "impact" ? "/search/project" : "/search/" . $type;

			$params = array("active" => "search", "is_region_manager" => $region_manager[0] -> count, "project_id" => $id, "feedback" => $feedback, "basic" => $basic[0], "owner" => $owner[0], "total_review" => $total_review[0], "communications" => $communications, "events" => $events, "message" => "", "redirect_url" => $redirect_url, "picture" => $basic[0] -> thumbnail != "" ? $basic[0] -> thumbnail : Config::get("app.url") . "/images/facebook/global_compact-icon-1365540187.png", "share_link" => Config::get("app.url") . "/project/view/" . $type . "/" . $id);
			return View::make("/frontend/" . $this -> _permission[Auth::user() -> permission] . "/search/" . $type . "_view") -> with($params);
		}
	}

	public function unfollowing($type, $id) {
		DB::table($type . "_follow") -> where("project_id", $id) -> where("user_id", Auth::user() -> id) -> delete();
		echo json_encode(array("success" => true));
	}

	public function impact_hugs($project_id, $active = "projects", $sub_active = "") {
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$ids = Input::get("selected_id");

			DB::table("impact_hug") -> whereIn('id', explode(",", $ids)) -> delete();
		}

		$project = DB::table("impact") -> where("id", $project_id) -> first();
		$hugs = DB::table("impact_hug") -> orderby("created_date", "desc") -> get();

		$params = array("active" => $active, "sub_active" => $sub_active, "project_id" => $project_id, "project_title" => $project -> name, "hugs" => $hugs);
		return View::make("/frontend/" . $this -> _permission[Auth::user() -> permission] . "/project/impacthugs") -> with($params);
	}

	public function prayer_hugs($project_id, $active = "projects", $sub_active = "") {
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$ids = Input::get("selected_id");

			DB::table("prayer_hug") -> whereIn('id', explode(",", $ids)) -> delete();
		}

		$project = DB::table("prayer") -> where("id", $project_id) -> first();
		$hugs = DB::table("prayer_hug") -> orderby("created_date", "desc") -> get();

		$params = array("active" => $active, "sub_active" => $sub_active, "project_id" => $project_id, "project_title" => $project -> name, "hugs" => $hugs);
		return View::make("/frontend/" . $this -> _permission[Auth::user() -> permission] . "/project/prayerhugs") -> with($params);
	}

	public function teaching_hugs($project_id, $active = "projects", $sub_active = "") {
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$ids = Input::get("selected_id");

			DB::table("teaching_hug") -> whereIn('id', explode(",", $ids)) -> delete();
		}

		$project = DB::table("teaching") -> where("id", $project_id) -> first();
		$hugs = DB::table("teaching_hug") -> orderby("created_date", "desc") -> get();

		$params = array("active" => $active, "sub_active" => $sub_active, "project_id" => $project_id, "project_title" => $project -> name, "hugs" => $hugs);
		return View::make("/frontend/" . $this -> _permission[Auth::user() -> permission] . "/project/teachinghugs") -> with($params);
	}

	public function report_hugs($type, $project_id, $active = "projects", $sub_active = "") {
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$ids = Input::get("selected_id");

			DB::table($type . "_hug") -> whereIn('id', explode(",", $ids)) -> delete();
		}

		$project = DB::table($type) -> where("id", $project_id) -> first();
		$hugs = DB::table($type . "_hug") -> orderby("created_date", "desc") -> get();

		$params = array("active" => $active, "sub_active" => $sub_active, "type" => $type, "project_id" => $project_id, "project_title" => $project -> name, "hugs" => $hugs);
		return View::make("/frontend/" . $this -> _permission[Auth::user() -> permission] . "/project/reporthugs") -> with($params);
	}

	public function prayer_followings($project_id, $active = "projects", $sub_active = "") {
		$project = DB::table("prayer") -> where("id", $project_id) -> first();
		$users = DB::select("select a.*, c.* from users a left join user_profile c on a.id = c.user_id, prayer_follow b where a.id = b.user_id and b.project_id = $project_id");
		$params = array("active" => $active, "sub_active" => $sub_active, "project_id" => $project_id, "project_title" => $project -> name, "users" => $users);
		return View::make("/frontend/" . $this -> _permission[Auth::user() -> permission] . "/project/prayerfollowings") -> with($params);
	}

	public function impact_followings($project_id, $active = "projects", $sub_active = "") {
		$project = DB::table("impact") -> where("id", $project_id) -> first();
		$users = DB::select("select a.*, c.* from users a left join user_profile c on a.id = c.user_id, impact_follow b where a.id = b.user_id and b.project_id = $project_id");
		$params = array("active" => $active, "sub_active" => $sub_active, "project_id" => $project_id, "project_title" => $project -> name, "users" => $users);
		return View::make("/frontend/" . $this -> _permission[Auth::user() -> permission] . "/project/impactfollowings") -> with($params);
	}

	public function report_followings($prefix, $project_id, $active = "projects", $sub_active = "") {
		$project = DB::table($prefix) -> where("id", $project_id) -> first();
		$users = DB::select("select a.*, c.* from users a left join user_profile c on a.id = c.user_id, " . $prefix . "_follow b where a.id = b.user_id and b.project_id = $project_id");
		$params = array("active" => $active, "sub_active" => $sub_active, "project_id" => $project_id, "project_title" => $project -> name, "users" => $users);
		return View::make("/frontend/" . $this -> _permission[Auth::user() -> permission] . "/project/" . $prefix . "followings") -> with($params);
	}

	public function teaching_followings($project_id, $active = "projects", $sub_active = "") {
		$project = DB::table("teaching") -> where("id", $project_id) -> first();
		$users = DB::select("select a.*, c.* from users a left join user_profile c on a.id = c.user_id, teaching_follow b where a.id = b.user_id and b.project_id = $project_id");
		$params = array("active" => $active, "sub_active" => $sub_active, "project_id" => $project_id, "project_title" => $project -> name, "users" => $users);
		return View::make("/frontend/" . $this -> _permission[Auth::user() -> permission] . "/project/teachingfollowings") -> with($params);
	}

	public function impact_event_joins($project_id, $event_id, $active = "projects", $sub_active = "") {
		$project = DB::table("impact") -> where("id", $project_id) -> first();
		$event = DB::table("impact_event") -> where("project_id", $project_id) -> where("id", $event_id) -> first();
		$users = DB::select("SELECT 	a.*, b.*
								FROM 	users a
									LEFT JOIN user_profile b ON a.id = b.user_id,
									impact_event_join c, impact_event d
							WHERE 	a.id = c.user_id and c.event_id = d.id and d.project_id = $project_id and d.id = $event_id");
		$params = array("active" => $active, "sub_active" => $sub_active, "project_id" => $project_id, "project_title" => $project -> name, "users" => $users, "event" => $event);
		return View::make("/frontend/" . $this -> _permission[Auth::user() -> permission] . "/project/impactjoins") -> with($params);
	}

	public function prayer_event_joins($project_id, $event_id, $active = "projects", $sub_active = "") {
		$project = DB::table("prayer") -> where("id", $project_id) -> first();
		$event = DB::table("prayer_event") -> where("project_id", $project_id) -> where("id", $event_id) -> first();
		$users = DB::select("SELECT 	a.*, b.*
								FROM 	users a
									LEFT JOIN user_profile b ON a.id = b.user_id,
									prayer_event_join c, prayer_event d
							WHERE 	a.id = c.user_id and c.event_id = d.id and d.project_id = $project_id and d.id = $event_id");
		$params = array("active" => $active, "sub_active" => $sub_active, "project_id" => $project_id, "project_title" => $project -> name, "users" => $users, "event" => $event);
		return View::make("/frontend/" . $this -> _permission[Auth::user() -> permission] . "/project/prayerjoins") -> with($params);
	}

	public function teaching_event_joins($project_id, $event_id, $active = "projects", $sub_active = "") {
		$project = DB::table("teaching") -> where("id", $project_id) -> first();
		$event = DB::table("teaching_event") -> where("project_id", $project_id) -> where("id", $event_id) -> first();
		$users = DB::select("SELECT 	a.*, b.*
								FROM 	users a
									LEFT JOIN user_profile b ON a.id = b.user_id,
									teaching_event_join c, teaching_event d
							WHERE 	a.id = c.user_id and c.event_id = d.id and d.project_id = $project_id and d.id = $event_id");
		$params = array("active" => $active, "sub_active" => $sub_active, "project_id" => $project_id, "project_title" => $project -> name, "users" => $users, "event" => $event);
		return View::make("/frontend/" . $this -> _permission[Auth::user() -> permission] . "/project/teachingjoins") -> with($params);
	}

	public function report_event_joins($prefix, $project_id, $event_id, $active = "projects", $sub_active = "") {
		$project = DB::table($prefix) -> where("id", $project_id) -> first();
		$event = DB::table($prefix . "_event") -> where("project_id", $project_id) -> where("id", $event_id) -> first();
		$users = DB::select("SELECT 	a.*, b.*
								FROM 	users a
									LEFT JOIN user_profile b ON a.id = b.user_id,
									" . $prefix . "_event_join c, " . $prefix . "_event d
							WHERE 	a.id = c.user_id and c.event_id = d.id and d.project_id = $project_id and d.id = $event_id");
		$params = array("active" => $active, "sub_active" => $sub_active, "prefix" => $prefix, "project_id" => $project_id, "project_title" => $project -> name, "users" => $users, "event" => $event);
		return View::make("/frontend/" . $this -> _permission[Auth::user() -> permission] . "/project/reportjoins") -> with($params);
	}

	public function project_donation($type, $id, $amount, $a = "search") {
		$project = DB::table($type) -> where("id", $id) -> first();

		if ($project -> paypal_number != "" && filter_var($project -> paypal_number, FILTER_VALIDATE_EMAIL) && Config::get("app.paypal_email") != "" && filter_var(Config::get("app.paypal_email"), FILTER_VALIDATE_EMAIL)) :
			include ("include/paypal/paypal.php");
			$project = DB::table($type) -> where("id", $id) -> first();

			$owner_email = $project -> paypal_number;
			$owner_amount = $amount * 0.925;

			$overall_email = Config::get("app.paypal_email");
			$overall_amount = $amount * 0.075;

			$transactionid = "TS-PR-" . $this -> generate_rand(32);

			$return_url = Config::get("app.url") . "/project/" . $type . "/" . $id . "/donation/success/" . $transactionid . "/" . $a;
			$cancel_url = Config::get("app.url") . "/project/" . $type . "/" . $id . "/donation/cancel/" . $transactionid . "/" . $a;

			DB::table($type . "_transaction") -> insert(array("id" => $transactionid, "project_id" => $id, "amount" => $owner_amount, "user_id" => Auth::user() -> id, "name" => Auth::user() -> first_name . " " . Auth::user() -> last_name, "email" => Auth::user() -> email, "status" => -100, "created_date" => date("Y-m-d H:i:s")));
			DB::table("overall_transaction") -> insert(array("id" => null, "related_transaction_id" => $transactionid, "project_id" => $id, "project_type" => $type, "amount" => $overall_amount, "user_id" => Auth::user() -> id, "name" => Auth::user() -> first_name . " " . Auth::user() -> last_name, "email" => Auth::user() -> email, "status" => -100, "created_date" => date("Y-m-d H:i:s")));

			$paypal = new Paypal;
			$receiver = array( array("amount" => $owner_amount, "email" => $owner_email), array("amount" => $overall_amount, "email" => $overall_email));
			$item = array( array("name" => "Donation for " . $project -> name, "identifier" => "p1", "price" => $owner_amount, "itemPrice" => $owner_amount, "itemCount" => 1), array("name" => "Response for donation", "identifier" => "p2", "price" => $overall_amount, "itemPrice" => $overall_amount, "itemCount" => 1));
			$receiverOptions = array( array("receiver" => array("email" => $owner_email), "invoiceData" => array("item" => array( array("name" => "Donation for " . $project -> name, "price" => $owner_amount, "identifire" => "p1")))), array("receiver" => array("email" => $overall_email), "invoiceData" => array("item" => array( array("name" => "Responsive for donation", "price" => $overall_amount, "identifire" => "p2")))));
			$paypal -> splitPay($receiver, $item, $return_url, $cancel_url, $receiverOptions);
			exit ;
		else :
			$error = "<div class='alert alert-danger alert-dismissable'>
                        <button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button>
                        Paypal address is not set yet.
                    </div>";
			Session::set("error", $error);
		endif;
	}

}
