<?php

class OverallController extends BaseController {

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

	public function users() {
		$users = DB::table("users") -> where("status", "!=", -1) -> whereNotIn("permission", array(-1)) -> orderby("created_date", "desc") -> orderby("last_login_date", "desc") -> get();

		return View::make("frontend/overall/manage/users") -> with(array("active" => "manages", "users" => $users));
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
			$permission = Input::get("permission");

			$check = DB::table("users") -> where("email", $email) -> whereNotIn("id", array($id)) -> first();
			if (!empty($check)) {
				$message = "<div class='alert alert-danger alert-dismissable'>
		                        <button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button>
		                        Email address is already registered by another user.
		                    </div>";
			} else {
				DB::table("users") -> where("id", $id) -> update(array("first_name" => $first_name, "last_name" => $last_name, "gender" => $gender, "birthday" => $birthday, "email" => $email, "status" => $status, "permission" => $permission));
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

		return View::make("frontend/overall/manage/user_edit") -> with(array("active" => "manages", "profile" => $profile[0], "message" => $message, "back" => true, "back_url" => "/manages/users", "video" => $video));
	}

	public function user_permissions($permission = "") {
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$permissions = Input::get("permission");

			while (list($key, $value) = each($permissions)) :
				DB::table("users") -> where("id", $key) -> update(array("permission" => $value));
			endwhile;
			return Redirect::to("/manages/user-permission");
		}

		if ($permission != "") {
			$convert = array("region" => -2, "general" => -3, "default" => 100);
			$users = DB::table("users") -> whereIn("permission", array($convert[$permission])) -> orderby("created_date", "desc") -> get();
		} else {
			$users = DB::table("users") -> where("permission", "!=", -1) -> orderby("created_date", "desc") -> get();
		}

		return View::make("/frontend/overall/manage/user_permissions") -> with(array("active" => "manages", "users" => $users, "permission" => $permission));
	}

	public function region($country = "") {
		$regions = DB::select("SELECT 	tbl.*, (case when d.id is null then 0 else d.id end) as id, d.user_id, d.help_video,
									(select show_days from region_manager where country = tbl.country and state = tbl.state) as show_days,
									(SELECT concat(first_name, ' ', last_name) FROM users WHERE id = d.user_id) AS username
								FROM 	(SELECT DISTINCT country, state FROM user_profile WHERE country != '' AND state != ''
									UNION DISTINCT 
									SELECT DISTINCT country, state FROM impact_event WHERE country != '' AND state != ''
									UNION DISTINCT 
									SELECT DISTINCT country, state FROM prayer_event WHERE country != '' AND state != ''
									UNION DISTINCT 
									SELECT DISTINCT country, state FROM regionalreport_event WHERE country != '' AND state != ''
									UNION DISTINCT 
									SELECT DISTINCT country, state FROM nationalreport_event WHERE country != '' AND state != ''
									UNION DISTINCT 
									SELECT DISTINCT country, state FROM teaching_event WHERE country != '' AND state != ''
									UNION DISTINCT 
									SELECT DISTINCT country, state FROM impact WHERE country != '' AND state != ''
									UNION DISTINCT 
									SELECT DISTINCT country, state FROM prayer WHERE country != '' AND state != ''
									UNION DISTINCT 
									SELECT DISTINCT country, state FROM regionalreport WHERE country != '' AND state != ''
									UNION DISTINCT 
									SELECT DISTINCT country, state FROM nationalreport WHERE country != '' AND state != ''
									UNION DISTINCT 
									SELECT DISTINCT country, state FROM teaching WHERE country != '' AND state != ''
									ORDER BY country, state) tbl
									LEFT JOIN (SELECT 	b.*, c.intro_video
										FROM 	region_manager b
											LEFT JOIN region_page c ON b.id = c.region_id) d ON tbl.country = d.country AND tbl.state = d.state
								WHERE 1 = 1
									 " . ($country != "" ? " AND tbl.country = '" . $country . "'" : "") . "
								ORDER BY country, state");

		$countries = DB::select("SELECT DISTINCT country FROM user_profile WHERE country != ''
									UNION DISTINCT 
									SELECT DISTINCT country FROM impact_event WHERE country != ''
									UNION DISTINCT 
									SELECT DISTINCT country FROM prayer_event WHERE country != ''
									UNION DISTINCT 
									SELECT DISTINCT country FROM regionalreport_event WHERE country != ''
									UNION DISTINCT 
									SELECT DISTINCT country FROM nationalreport_event WHERE country != ''
									UNION DISTINCT 
									SELECT DISTINCT country FROM teaching_event WHERE country != ''
									UNION DISTINCT 
									SELECT DISTINCT country FROM impact WHERE country != ''
									UNION DISTINCT 
									SELECT DISTINCT country FROM prayer WHERE country != ''
									UNION DISTINCT 
									SELECT DISTINCT country FROM regionalreport WHERE country != ''
									UNION DISTINCT 
									SELECT DISTINCT country FROM nationalreport WHERE country != ''
									UNION DISTINCT 
									SELECT DISTINCT country FROM teaching WHERE country != ''
									ORDER BY country");

		return View::make("/frontend/overall/manage/region") -> with(array("active" => "manages", "regions" => $regions, "countries" => $countries, "country" => $country));
	}

	public function region_edit($country, $state, $id) {
		$message = "";
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$manager = Input::get("manager");
			$show_days = Input::get("show_days");
			$help_video = Input::get("help_video");

			if ($manager == 0) {
				DB::table("region_manager") -> where("id", $id) -> delete();
				$message = "<div class='alert alert-success alert-dismissable'>
	                            <button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button>
	                            Region manager is set successfully.
	                        </div>";
			} else {
				if ($id > 0) {
					$check = DB::table("region_manager") -> where("user_id", $manager) -> where("id", "!=", $id) -> first();
					if (!empty($check)) {
						$message = "<div class='alert alert-danger alert-dismissable'>
		                            <button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button>
		                            This user is already set in another one.
		                        </div>";
					} else {
						DB::table("region_manager") -> where("id", $id) -> update(array("user_id" => $manager, "show_days" => $show_days, "help_video" => $help_video));
						$message = "<div class='alert alert-success alert-dismissable'>
		                            <button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button>
		                            Region manager is set successfully.
		                        </div>";
					}
				} else {
					DB::table("region_manager") -> where("country", $country) -> where("state", $state) -> delete();
					$check = DB::table("region_manager") -> where("user_id", $manager) -> first();
					if (!empty($check)) {
						$message = "<div class='alert alert-danger alert-dismissable'>
		                            <button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button>
		                            This user is already set in another one.
		                        </div>";
					} else {
						DB::table("region_manager") -> insert(array("id" => null, "country" => $country, "state" => $state, "show_days" => $show_days, "help_video" => $help_video, "user_id" => $manager));
						$message = "<div class='alert alert-success alert-dismissable'>
		                            <button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button>
		                            Region manager is set successfully.
		                        </div>";
					}
				}
			}
		}

		$manager = "";
		$show_days = 5;
		$help_video = "";
		if ($id > 0) {
			$u = DB::table("region_manager") -> where("id", $id) -> first();
			$manager = !empty($u) ? $u -> user_id : "";
			$show_days = !empty($u) ? $u -> show_days : 5;
			$help_video = !empty($u) ? $u -> help_video : "";
		}

		$users = DB::table("users") -> where("permission", -2) -> where("status", 1) -> orderby("first_name") -> orderby("last_name") -> get();
		return View::make("/frontend/overall/manage/region-edit") -> with(array("active" => "manages", "country" => $country, "state" => $state, "show_days" => $show_days, "help_video" => $help_video, "manager" => $manager, "users" => $users, "message" => $message));
	}

	public function contacts() {
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$chk = Input::get("chk");

			if ($chk) :
				DB::table("contacts") -> whereIn("id", $chk) -> delete();
			endif;
		}

		$contacts = DB::table("contacts") -> orderby("status", "desc") -> orderby("created_date", "desc") -> get();
		return View::make("/frontend/overall/contacts/contacts") -> with(array("active" => "contacts", "contacts" => $contacts));
	}

	public function contacts_view($id) {
		$message = "";
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$response = Input::get("response");
			$info = DB::table("contacts") -> where("id", $id) -> first();

			//send mail
			if ($info -> email != "") {
				$mail = new PHPMailer;
				$mail -> setFrom(Config::get("app.support_email"));
				$mail -> addAddress($info -> email);

				$body = "<style>
						* {
							font-family: Arial;
						}
						table {
							font-size: 12px;
						}
					</style>
					<p>" . $response . "</p>";

				$mail -> Subject = "Christian Response: Reply your request.";
				$mail -> msgHTML($body);
				$mail -> AltBody = $body;

				if ($mail -> send()) {
					DB::table("contacts") -> where("id", $id) -> update(array("response_text" => $response, "status" => 1));

					$message = "<div class='alert alert-success alert-dismissable'>
                            <button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button>
                            Your response is submitted successfully.
                        </div>";
				}
			}
		}

		$info = DB::table("contacts") -> where("id", $id) -> first();
		return View::make("/frontend/overall/contacts/contacts_edit") -> with(array("active" => "contacts", "info" => $info, "message" => $message));
	}

	public function video_setting() {
		$message = "";
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$homepage_video = Input::get("homepage_video");
			$teaching_video = Input::get("teaching_video");
			$testimony_video = Input::get("testimony_video");
			$mission_video = Input::get("mission_video");
			$gifts_video = Input::get("gifts_video");
			$goals_video = Input::get("goals_video");
			$interests_video = Input::get("interests_video");

			DB::table("video_setting") -> delete();
			DB::table("video_setting") -> insert(array("homepage_video" => $homepage_video, "teaching_video" => $teaching_video, "testimony_video" => $testimony_video, "mission_video" => $mission_video, "gifts_video" => $gifts_video, "goals_video" => $goals_video, "interests_video" => $interests_video));

			$message = "<div class='alert alert-success alert-dismissable'>
                            <button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button>
                            Setting video is completed successfully.
                        </div>";
		}

		$video = DB::table("video_setting") -> first();
		if (empty($video)) {
			$video = array("homepage_video" => "", "teaching_video" => "", "testimony_video" => "", "mission_video" => "", "gifts_video" => "", "goals_video" => "", "interests_video" => "");
			$video = json_decode($video, TRUE);
		}

		return View::make("/frontend/overall/manage/video_setting") -> with(array("active" => "manages", "video" => $video, "message" => $message));
	}

	public function posts() {
		$posts = DB::table("posts") -> orderby("created_date", "desc") -> get();
		return View::make("/frontend/overall/manage/posts") -> with(array("active" => "manages", "posts" => $posts));
	}

	public function delete_posts($id) {
		$posts = DB::table("posts") -> where("id", $id) -> delete();
		return Redirect::to("/manages/posts");
	}

	public function posts_edit($id = 0) {
		$message = "";
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$title = Input::get("title");
			$content = Input::get("content");

			if ($id > 0) {
				DB::table("posts") -> where("id", $id) -> update(array("title" => $title, "content" => $content, "updated_date" => date("Y-m-d H:i:s")));
			} else {
				$newid = DB::table("posts") -> insertGetId(array("id" => null, "title" => $title, "content" => $content, "created_date" => date("Y-m-d H:i:s"), "updated_date" => null));
			}

			$message = "<div class='alert alert-success alert-dismissable'>
                            <button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button>
                            New post is saved successfully.
                        </div>";
		}

		$post = array("title" => "", "content" => "");
		$post = json_decode(json_encode($post), FALSE);

		if ($id > 0) {
			$post = DB::table("posts") -> where("id", $id) -> first();
		}

		return View::make("/frontend/overall/manage/posts-edit") -> with(array("active" => "manages", "post" => $post, "message" => $message));
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
						AND b.id = c.user_id AND a.status != -1
					UNION
					SELECT 	a.id, a.name, a.created_date, a.status, b.first_name, b.last_name, 'regionalreport' as type, d.region_id, a.country, a.state, a.address, a.city, a.zip_code,
						(select COUNT(id) from regionalreport_event where project_id = a.id and status = 1) AS event_count,
						(select COUNT(user_id) from regionalreport_follow where project_id = a.id) AS follow_count,
						(SELECT COUNT(id) FROM regionalreport_hug WHERE project_id = a.id) AS hug_count,
						(select sum(amount) from regionalreport_transaction where project_id = a.id and status = 1) as amount
					FROM 	regionalreport a
						LEFT JOIN region_allocate d on a.id = d.project_id and d.project_type = 'regionalreport', users b, user_profile c
					WHERE 	a.user_id = b.id
						AND b.id = c.user_id AND a.status != -1
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
						AND b.id = c.user_id AND a.status != -1
					ORDER BY created_date desc";
		}

		$projects = DB::select($sql);

		return View::make("/frontend/overall/manage/projects") -> with(array("active" => "manages", "sub_active" => "manages-projects", "type" => $type, "region" => $region, "projects" => $projects));
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

	public function aboutus() {
		$message = "";
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$content = Input::get("content");

			DB::table("about") -> delete();
			DB::table("about") -> insert(array("content" => $content));

			$message = "<div class='alert alert-success alert-dismissable'>
                            <button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button>
                            Content has been saved successfully.
                        </div>";
		}

		$content = "";
		$about = DB::table("about") -> first();

		if (!empty($about)) {
			$content = $about -> content;
		}

		return View::make("/frontend/overall/manage/about") -> with(array("active" => "manages", "sub_active" => "about", "content" => $content, "message" => $message));
	}

	public function contactus() {
		$message = "";
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$address = Input::get("address");
			$phone_number = Input::get("phone_number");
			$email = Input::get("email");
			$content = Input::get("content");

			DB::table("contact_us") -> delete();
			DB::table("contact_us") -> insert(array("content" => $content, "phone_number" => $phone_number, "address" => $address, "email" => $email));

			$message = "<div class='alert alert-success alert-dismissable'>
                            <button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button>
                            Content has been saved successfully.
                        </div>";
		}

		$contact = DB::table("contact_us") -> first();

		if (empty($contact)) {
			$contact = array("content" => "", "phone_number" => "", "address" => "", "email" => "");
			$contact = json_decode(json_encode($contact), FALSE);
		}

		return View::make("/frontend/overall/manage/contact_us") -> with(array("active" => "manages", "sub_active" => "", "contact" => $contact, "message" => $message));
	}

	public function teaching_course() {
		$course = DB::table("teaching_course") -> orderby("order") -> get();

		return View::make("/frontend/overall/manage/teaching-course") -> with(array("active" => "manages", "course" => $course));
	}

	public function delete_teaching_course($id) {
		DB::table("teaching_course") -> where("id", $id) -> delete();

		return Redirect::to("/manages/teaching-course");
	}

	public function change_order() {
		$id = $_POST["id"];
		$order = $_POST["order"];

		DB::table("teaching_course") -> where("id", $id) -> update(array("order" => $order));
		echo json_encode(array("success" => true));
	}

	public function teaching_course_edit($id = 0) {
		$message = "";
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$title = Input::get("title");
			$comment = Input::get("comment");
			$order = Input::get("order");

			if ($id > 0) {
				DB::table("teaching_course") -> where("id", $id) -> update(array("title" => $title, "comment" => $comment, "order" => $order));
			} else {
				$id = DB::table("teaching_course") -> insertGetId(array("id" => null, "title" => $title, "comment" => $comment, "thumbnail" => "", "pdf" => "", "order" => 1));
			}

			if ($_FILES["thumbnail"]["tmp_name"] != "") {
				$filename = $this -> generate_rand(32);
				if (move_uploaded_file($_FILES["thumbnail"]["tmp_name"], public_path() . "/res/teaching/" . $filename)) {
					$url = Config::get("app.url") . "/res/teaching/" . $filename;

					DB::table("teaching_course") -> where("id", $id) -> update(array("thumbnail" => $url));
				}
			}
			if ($_FILES["pdf"]["tmp_name"] != "") {
				$filename = $this -> generate_rand(32);
				if (move_uploaded_file($_FILES["pdf"]["tmp_name"], public_path() . "/res/teaching/" . $filename)) {
					$url = Config::get("app.url") . "/res/teaching/" . $filename;

					DB::table("teaching_course") -> where("id", $id) -> update(array("pdf" => $url));
				}
			}

			$message = "<div class='alert alert-success alert-dismissable'>
                            <button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button>
                            Teaching Course has been saved successfully.
                        </div>";
		}

		$info = array("id" => 0, "title" => "", "comment" => "", "thumbnail" => "", "pdf" => "", "order" => 1);
		$info = json_decode(json_encode($info), FALSE);
		if ($id > 0) {
			$info = DB::table("teaching_course") -> where("id", $id) -> first();
		}

		return View::make("/frontend/overall/manage/teaching-course-edit") -> with(array("active" => "manages", "info" => $info, "message" => $message));
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

	public function set_paypal() {
		$message = "";
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$paypal_address = Input::get("paypal_address");

			DB::table("overall_settings") -> where("param_name", "paypal-address") -> delete();
			DB::table("overall_settings") -> insert(array("param_name" => "paypal-address", "param_value" => $paypal_address));

			$message = "<div class='alert alert-success alert-dismissable'>
                            <button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button>
                            Paypal address is saved successfully.
                        </div>";
		}

		$paypal_address = "";

		return View::make("/frontend/overall/settings/paypal-address") -> with(array("active" => "settings", "sub_active" => "", "paypal_address" => $paypal_address));
	}
	
	public function search_users() {
		$name = $_POST["name"];
		$gender = $_POST["gender"];
		$permission = $_POST["permission"];

		$result = DB::select("select * from users 
								where (first_name like '%$name%' or last_name like '%$name%')
									and status != -1
									and permission not in (-1)
									" . ($gender > 0 ? " and gender = " . $gender : "") . "
									" . ($permission > 0 ? " and permission = " . $permission : "") . "
								order by last_login_date desc");

		echo json_encode(array("data" => $result));
	}
}
