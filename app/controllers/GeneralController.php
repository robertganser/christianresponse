<?php

class GeneralController extends BaseController {

	/*
	 |--------------------------------------------------------------------------
	 | Default Home Controller
	 |--------------------------------------------------------------------------
	 |
	 | You may wish to use controllers instead of, or in addition to, Closure
	 | based routes. That's great! Here is an example controller method to
	 | get you started. To route to this controller, just add the route:
	 |
	 |	Route::get('/', 'GeneralController@showWelcome');
	 |
	 */

	public function users() {
		$users = DB::table("users") -> where("status", "!=", -1) -> whereNotIn("permission", array(-1, -3)) -> orderby("created_date", "desc") -> get();
		return View::make("frontend/general/manage/users") -> with(array("active" => "manages", "users" => $users));
	}

	public function user_edit($id) {
		$message = "";
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$first_name = Input::get("first_name");
			$last_name = Input::get("last_name");
			$gender = Input::get("gender");
			$birthday = Input::get("birthday");
			$email = Input::get("email");
			$phone_number = Input::get("phone_number");
			$address = Input::get("address");
			$city = Input::get("city");
			$state = Input::get("state");
			$zip_code = Input::get("zip_code");
			$country = Input::get("country");
			$testimony = Input::get("testimony");
			$mission_statement = Input::get("mission_statement");
			$skill_gifts = Input::get("skill_gifts");
			$goals = Input::get("goals");
			$ministry_interests = Input::get("ministry_interests");
			$status = Input::get("status");

			$check = DB::table("users") -> where("email", $email) -> whereNotIn("id", array($id)) -> first();
			if (!empty($check)) {
				$message = "<div class='alert alert-danger alert-dismissable'>
		                        <button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button>
		                        Email address is already registered by another user.
		                    </div>";
			} else {
				DB::table("users") -> where("id", $id) -> update(array("first_name" => $first_name, "last_name" => $last_name, "gender" => $gender, "birthday" => $birthday, "email" => $email, "status" => $status));
				DB::table("user_profile") -> where("user_id", $id) -> update(array("phone_number" => $phone_number, "address" => $address, "city" => $city, "state" => $state, "zip_code" => $zip_code, "country" => $country, "testimony" => $testimony, "mission_statement" => $mission_statement, "skill_gifts" => $skill_gifts, "goals" => $goals, "ministry_interests" => $ministry_interests));

				$message = "<div class='alert alert-success alert-dismissable'>
	                        <button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button>
	                        User profile is updated successfully.
	                    </div>";
				if (isset($_FILES["avatar"]) && $_FILES["avatar"]["name"] != "") {
					$filename = $this -> generate_rand(16);
					if (move_uploaded_file($_FILES["avatar"]["tmp_name"], public_path() . "/res/profile/" . $filename)) {
						DB::table("users") -> where("id", $id) -> update(array("picture" => Config::get("app.url") . "/res/profile/" . $filename));
					} else {
						$message = "<div class='alert alert-danger alert-dismissable'>
			                        <button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button>
			                        Updating profile error.
			                    </div>";
					}
				}
			}

			Session::set("message", $message);
			return Redirect::to("/manages/users/edit/" . $id);
		}

		$video = DB::table("video_setting") -> first();
		if (empty($video)) {
			$video = array("homepage_video" => "", "teaching_video" => "", "testimony_video" => "", "mission_video" => "", "gifts_video" => "", "goals_video" => "", "interests_video" => "");
			$video = json_decode($video, TRUE);
		}
		$profile = DB::select("select a.*, b.* from users a left join user_profile b on a.id = b.user_id where a.id = ?", array($id));

		return View::make("frontend/general/manage/user_edit") -> with(array("active" => "manages", "profile" => $profile[0], "message" => $message, "back" => true, "back_url" => "/manages/users", "video" => $video));
	}

	public function manage_projects($type) {
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$project_status = Input::get("project_status");
			if ($type == "report") {
				$status_national = isset($project_status['nationalreport']) ? $project_status['nationalreport'] : array();
				$status_regional = isset($project_status['regionalreport']) ? $project_status['regionalreport'] : array();

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

			$project_region = Input::get("project_region");
			if ($type == "report") {
				$status_national = isset($project_region['nationalreport']) ? $project_region['nationalreport'] : array();
				$status_regional = isset($project_region['regionalreport']) ? $project_region['regionalreport'] : array();

				while (list($key, $value) = each($status_national)) :
					DB::table("region_allocate") -> where("project_id", $key) -> where("project_type", "nationalreport") -> delete();

					if ($value > 0)
						DB::table("region_allocate") -> insert(array("region_id" => $value, "project_id" => $key, "project_type" => "nationalreport"));
				endwhile;

				while (list($key, $value) = each($status_regional)) :
					DB::table("region_allocate") -> where("project_id", $key) -> where("project_type", "regionalreport") -> delete();

					if ($value > 0)
						DB::table("region_allocate") -> insert(array("region_id" => $value, "project_id" => $key, "project_type" => "regionalreport"));
				endwhile;
			} else {
				switch ($type) {
					case 'prayer' :
						$status = $project_region["prayer"];
						break;
					case 'impact' :
						$status = $project_region["impact"];
						break;
					case 'teaching' :
						$status = $project_region["teaching"];
						break;
				}

				while (list($key, $value) = each($status)) :
					DB::table("region_allocate") -> where("project_id", $key) -> where("project_type", $type) -> delete();

					if ($value > 0)
						DB::table("region_allocate") -> insert(array("region_id" => $value, "project_id" => $key, "project_type" => $type));
				endwhile;
			}

			return Redirect::to("/manages/projects/" . $type);
		}

		$region = DB::table("region_manager") -> orderby("country") -> orderby("state") -> get();
		if ($type == "report") {
			$sql = "SELECT 	a.id, a.name, a.created_date, a.status, b.first_name, b.last_name, 'nationalreport' as type, d.region_id, a.country, a.state, a.address, a.city, a.zip_code,
						(select COUNT(id) from nationalreport_event where project_id = a.id and status = 1) AS event_count,
						(select COUNT(user_id) from nationalreport_follow where project_id = a.id) AS follow_count,
						(SELECT COUNT(id) FROM nationalreport_hug WHERE project_id = a.id) AS hug_count,
						(select sum(amount) from nationalreport_transaction where project_id = a.id and status = 1) as amount
					FROM 	nationalreport a
						LEFT JOIN region_allocate d on a.id = d.project_id and d.project_type = 'nationalreport', 
						users b, user_profile c
					WHERE 	a.user_id = b.id
						AND b.id = c.user_id AND a.status = 1
					UNION
					SELECT 	a.id, a.name, a.created_date, a.status, b.first_name, b.last_name, 'regionalreport' as type, d.region_id, a.country, a.state, a.address, a.city, a.zip_code,
						(select COUNT(id) from regionalreport_event where project_id = a.id and status = 1) AS event_count,
						(select COUNT(user_id) from regionalreport_follow where project_id = a.id) AS follow_count,
						(SELECT COUNT(id) FROM regionalreport_hug WHERE project_id = a.id) AS hug_count,
						(select sum(amount) from regionalreport_transaction where project_id = a.id and status = 1) as amount
					FROM 	regionalreport a
						LEFT JOIN region_allocate d on a.id = d.project_id and d.project_type = 'regionalreport', users b, user_profile c
					WHERE 	a.user_id = b.id
						AND b.id = c.user_id AND a.status = 1
					ORDER BY created_date desc";
		} else {
			$sql = "SELECT 	a.id, a.name, a.created_date, a.status, b.first_name, b.last_name, '$type' as type, d.region_id, a.country, a.state, a.address, a.city, a.zip_code,
						(select COUNT(id) from " . $type . "_event where project_id = a.id and status = 1) AS event_count,
						(select COUNT(user_id) from " . $type . "_follow where project_id = a.id) AS follow_count,
						(SELECT COUNT(id) FROM " . $type . "_hug WHERE project_id = a.id) AS hug_count,
						(select sum(amount) from " . $type . "_transaction where project_id = a.id and status = 1) as amount
					FROM 	" . $type . " a
						LEFT JOIN region_allocate d on a.id = d.project_id and d.project_type = '" . $type . "', users b, user_profile c
					WHERE 	a.user_id = b.id
						AND b.id = c.user_id AND a.status = 1
					ORDER BY created_date desc";
		}

		$projects = DB::select($sql);

		return View::make("/frontend/general/manage/projects") -> with(array("active" => "manages", "sub_active" => "manages-projects", "type" => $type, "region" => $region, "projects" => $projects));
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
		return $this -> report_events_edit($prefix, $project_id, $event_id, "manages", "manages-projects");
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

	public function project_transactions($type, $id) {
		$project = DB::table($type) -> where("id", $id) -> first();
		$transactions = DB::table($type . "_transaction") -> where("project_id", $id) -> where("status", 1) -> orderby("created_date") -> get();

		$total = 0;
		foreach ($transactions as $one) :
			$total += $one -> amount;
		endforeach;

		return View::make("/frontend/" . $this -> _permission[Auth::user() -> permission] . "/project/transactions") -> with(array("active" => "manages", "sub_active" => "manages-projects", "project_title" => $project -> name, "type" => $type, "transactions" => $transactions, "total" => $total));
	}

	public function projectreport_transactions($prefix, $id) {
		$project = DB::table($prefix) -> where("id", $id) -> first();
		$transactions = DB::table($prefix . "_transaction") -> where("project_id", $id) -> where("status", 1) -> orderby("created_date") -> get();

		$total = 0;
		foreach ($transactions as $one) :
			$total += $one -> amount;
		endforeach;

		return View::make("/frontend/" . $this -> _permission[Auth::user() -> permission] . "/project/transactions") -> with(array("active" => "manages", "sub_active" => "manages-projects", "project_title" => $project -> name, "type" => "report", "transactions" => $transactions, "total" => $total));
	}

	public function search_project() {
		$project_name = $_POST["project_name"];
		$owner_id = $_POST["owner_id"];

		$result = DB::select("SELECT 	a.id, a.name, a.created_date, f.first_name, f.last_name, a.country, a.state, a.zip_code, a.city, a.address,
								'prayer' AS project_type, a.user_id
							FROM 	prayer a, region_allocate d, region_manager e, users f
							WHERE 	a.status = 1
								AND a.user_id = f.id
								AND a.id = d.project_id
								AND d.project_type = 'prayer'
								AND d.region_id = e.id
								AND a.name like '%$project_name%'
								" . ($owner_id > 0 ? " AND a.user_id = '$owner_id' " : "") . "
							GROUP BY a.id
							UNION
							SELECT 	a.id, a.name, a.created_date, f.first_name, f.last_name, a.country, a.state, a.zip_code, a.city, a.address,
								'impact' AS project_type, a.user_id
							FROM 	impact a, region_allocate d, region_manager e, users f
							WHERE 	a.status = 1
								AND a.user_id = f.id
								AND a.id = d.project_id
								AND d.project_type = 'impact'
								AND d.region_id = e.id
								AND a.name like '%$project_name%'
								" . ($owner_id > 0 ? " AND a.user_id = '$owner_id' " : "") . "
							GROUP BY a.id
							UNION
							SELECT 	a.id, a.name, a.created_date, f.first_name, f.last_name, a.country, a.state, a.zip_code, a.city, a.address,
								'teaching' AS project_type, a.user_id
							FROM 	teaching a, region_allocate d, region_manager e, users f
							WHERE 	a.status = 1
								AND a.user_id = f.id
								AND a.id = d.project_id
								AND d.project_type = 'teaching'
								AND d.region_id = e.id
								AND a.name like '%$project_name%'
								" . ($owner_id > 0 ? " AND a.user_id = '$owner_id' " : "") . "
							GROUP BY a.id
							UNION
							SELECT 	a.id, a.name, a.created_date, f.first_name, f.last_name, a.country, a.state, a.zip_code, a.city, a.address,
								'nationalreport' AS project_type, a.user_id
							FROM 	nationalreport a, region_allocate d, region_manager e, users f
							WHERE 	a.status = 1
								AND a.user_id = f.id
								AND a.id = d.project_id
								AND d.project_type = 'nationalreport'
								AND d.region_id = e.id
								AND a.name like '%$project_name%'
								" . ($owner_id > 0 ? " AND a.user_id = '$owner_id' " : "") . "
							GROUP BY a.id
							UNION
							SELECT 	a.id, a.name, a.created_date, f.first_name, f.last_name, a.country, a.state, a.zip_code, a.city, a.address,
								'regionalreport' AS project_type, a.user_id
							FROM 	regionalreport a, region_allocate d, region_manager e, users f
							WHERE 	a.status = 1
								AND a.user_id = f.id
								AND a.id = d.project_id
								AND d.project_type = 'regionalreport'
								AND d.region_id = e.id
								AND a.name like '%$project_name%'
								" . ($owner_id > 0 ? " AND a.user_id = '$owner_id' " : "") . "
							GROUP BY a.id
							ORDER BY created_date DESC");
		echo json_encode(array("data" => $result));
	}

	public function search_facilitator() {
		$facilitator_name = $_POST["facilitator_name"];
		$result = DB::select("SELECT 	a.id, a.username, a.email, a.first_name, a.last_name, b.address, b.city, b.state, b.zip_code, b.country, a.permission, a.created_date
								FROM 	users a
									LEFT JOIN user_profile b ON a.id = b.user_id
								WHERE 	a.status = 1
									AND a.permission IN (100, -2)
									AND (a.first_name like '%$facilitator_name%' or a.last_name like '%$facilitator_name%')
								GROUP BY a.id
								ORDER BY a.created_date DESC");
		echo json_encode(array("data" => $result));
	}

	public function search_users() {
		$name = $_POST["name"];
		$gender = $_POST["gender"];
		$permission = $_POST["permission"];

		$result = DB::select("select * from users 
								where (first_name like '%$name%' or last_name like '%$name%')
									and status != -1
									and permission not in (-1, -3)
									" . ($gender > 0 ? " and gender = " . $gender : "") . "
									" . ($permission > 0 ? " and permission = " . $permission : "") . "
								order by created_date desc");

		echo json_encode(array("data" => $result));
	}

}
