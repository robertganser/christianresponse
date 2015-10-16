<?php

class AccountController extends BaseController {

	/*
	 |--------------------------------------------------------------------------
	 | Default Home Controller
	 |--------------------------------------------------------------------------
	 |
	 | You may wish to use controllers instead of, or in addition to, Closure
	 | based routes. That's great! Here is an example controller method to
	 | get you started. To route to this controller, just add the route:
	 |
	 |	Route::get('/', 'AccountController@showWelcome');
	 |
	 */

	public function login_check() {
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$username = Input::get("username");
			$password = Input::get("password");

			session_start();
			if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
				if (!Auth::attempt(array('email' => $username, 'password' => $password), true)) {
					echo json_encode(array("success" => false, "error" => $this -> responsebox("Email address or password is not correct.")));
				} else {
					if (Auth::user() -> status == -2) {
						echo json_encode(array("success" => false, "error" => $this -> responsebox("Your account is temporally blocked. Please contact support team about this.")));
					} elseif (Auth::user() -> status == -1) {
						echo json_encode(array("success" => false, "error" => $this -> responsebox("Your account is permanently removed. If you have some questions please contact support team.")));
					} elseif (Auth::user() -> status == -100) {
						echo json_encode(array("success" => false, "error" => $this -> responsebox("Your account is active yet. Please check your email to verify account.")));
					} elseif (Auth::user() -> status == -99) {
						echo json_encode(array("success" => false, "error" => $this -> responsebox("Your account must be approved by administrator")));
					} elseif (Auth::user() -> status == 1) {
						echo json_encode(array("success" => true));
					}
				}
			} else {
				if (!Auth::attempt(array('username' => $username, 'password' => $password), true)) {
					echo json_encode(array("success" => false, "error" => $this -> responsebox("Username or password is not correct.")));
				} else {
					if (Auth::user() -> status == -2) {
						echo json_encode(array("success" => false, "error" => $this -> responsebox("Your account is temporally blocked. Please contact support team about this.")));
					} elseif (Auth::user() -> status == -1) {
						echo json_encode(array("success" => false, "error" => $this -> responsebox("Your account is permanently removed. If you have some questions please contact support team.")));
					} elseif (Auth::user() -> status == -100) {
						echo json_encode(array("success" => false, "error" => $this -> responsebox("Your account is active yet. Please check your email to verify account.")));
					} elseif (Auth::user() -> status == -99) {
						echo json_encode(array("success" => false, "error" => $this -> responsebox("Your account must be approved by administrator")));
					} elseif (Auth::user() -> status == 1) {
						echo json_encode(array("success" => true));
					}
				}
			}
		}
	}

	public function preload() {
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$username = Input::get("username");
			$password = Input::get("password");

			session_start();
			if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
				Auth::attempt(array('email' => $username, 'password' => $password), true);
				DB::table("users") -> where("email", $username) -> update(array("last_login_date" => date("Y-m-d H:i:s")));
				Auth::login(Auth::user(), true);
				return Redirect::to("/dashboard");
			} else {
				Auth::attempt(array('username' => $username, 'password' => $password), true);
				DB::table("users") -> where("username", $username) -> update(array("last_login_date" => date("Y-m-d H:i:s")));
				Auth::login(Auth::user(), true);
				return Redirect::to("/dashboard");
			}
		}
	}

	public function register() {
		$response = "";
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$first_name = Input::get("register_first_name");
			$last_name = Input::get("register_last_name");
			$gender = Input::get("register_gender");
			$birthday_y = Input::get("register_birthday_y");
			$birthday_m = Input::get("register_birthday_m");
			$birthday_d = Input::get("register_birthday_d");
			$email = Input::get("register_email");
			$username = Input::get("register_username");
			$password = Input::get("register_password");

			$check = DB::table("users") -> where("email", $email) -> first();
			if (!empty($check)) {
				$response = $this -> responsebox("'$email' address is already registered");
			}
			$check = DB::table("users") -> where("username", $username) -> first();
			if (!empty($check)) {
				$response = $this -> responsebox("'$username' address is already registered");
			}

			$token = $this -> generate_rand(32);
			$newid = DB::table("users") -> insertGetId(array("id" => null, "first_name" => $first_name, "last_name" => $last_name, "gender" => $gender, "birthday" => $birthday_y . "-" . $birthday_m . "-" . $birthday_d, "email" => $email, "picture" => Config::get("app.url") . "/res/profile/default-user.png", "username" => $username, "password" => Hash::make($password), "permission" => 100, "token" => $token, "created_date" => date("Y-m-d H:i:s"), "last_login_date" => null, "status" => -100));
			DB::table("user_profile") -> insert(array("user_id" => $newid, "phone_number" => null, "address" => null, "city" => null, "state" => null, "zip_code" => null, "country" => null, "testimony" => null, "mission_statement" => null, "skill_gifts" => null, "goals" => null, "ministry_interests" => null));

			$mail = new PHPMailer;
			$mail -> setFrom(Config::get("app.support_email"));
			$mail -> addAddress($email);

			$body = "<style>
					* {
						font-family: Arial;
					}
					table {
						font-size: 12px;
					}
				</style>
				<h4>Plase click the link bellow to verify your account.</h4>
				<table>
					<tr>
						<td><a href='" . Config::get("app.url") . "/account/verify/" . $token . "'>Click here.</a></td>
					</tr>
				</table>";

			$mail -> Subject = "Christian Response: You need to do something.";
			$mail -> msgHTML($body);
			$mail -> AltBody = $body;
			$mail -> send();

			$response = $this -> responsebox("Thank you for your joining us.<br>Please check your email to verify account.", "success");
			/*
			 session_start();
			 Auth::attempt(array('email' => $email, 'password' => $password), true);

			 return Redirect::to("/dashboard");
			 *
			 */
		}

		$top_projects = DB::table("topproject") -> get();
		$about = DB::table("about") -> first();
		$about_content = !empty($about) ? $about -> content : "";

		$contact = DB::table("contact_us") -> first();

		if (empty($contact)) {
			$contact = array("content" => "", "phone_number" => "", "address" => "", "email" => "");
			$contact = json_decode(json_encode($contact), FALSE);
		}

		return View::make("frontend/register") -> with(array("key" => "home", "response" => $response, "top_projects" => $top_projects, "about_content" => $about_content, "contact" => $contact));
	}

	public function logout() {
		Auth::logout();
		Session::flush();
		return Redirect::to("/");
	}

	public function profile() {
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

			$check = DB::table("users") -> where("email", $email) -> whereNotIn("id", array(Auth::user() -> id)) -> first();
			if (!empty($check)) {
				$message = "<div class='alert alert-danger alert-dismissable'>
		                        <button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button>
		                        Email address is already registered by another user.
		                    </div>";
			} else {
				DB::table("users") -> where("id", Auth::user() -> id) -> update(array("first_name" => $first_name, "last_name" => $last_name, "gender" => $gender, "birthday" => $birthday, "email" => $email));
				DB::table("user_profile") -> where("user_id", Auth::user() -> id) -> update(array("phone_number" => $phone_number, "address" => $address, "city" => $city, "state" => $state, "zip_code" => $zip_code, "country" => $country, "testimony" => $testimony, "mission_statement" => $mission_statement, "skill_gifts" => $skill_gifts, "goals" => $goals, "ministry_interests" => $ministry_interests));

				$message = "<div class='alert alert-success alert-dismissable'>
	                        <button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button>
	                        Your profile is updated successfully.
	                    </div>";
				if (isset($_FILES["avatar"]) && $_FILES["avatar"]["name"] != "") {
					$filename = $this -> generate_rand(16);
					if (move_uploaded_file($_FILES["avatar"]["tmp_name"], public_path()."/res/profile/" . $filename)) {
						DB::table("users") -> where("id", Auth::user() -> id) -> update(array("picture" => Config::get("app.url") . "/res/profile/" . $filename));
					} else {
						$message = "<div class='alert alert-danger alert-dismissable'>
			                        <button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button>
			                        Updating profile error.
			                    </div>";
					}
				}
			}

			Session::set("message", $message);
			return Redirect::to("/settings/profile");
		}

		$profile = DB::select("select a.*, b.* from users a left join user_profile b on a.id = b.user_id where a.id = ?", array(Auth::user() -> id));

		$video = DB::table("video_setting") -> first();
		if (empty($video)) {
			$video = array("homepage_video" => "", "teaching_video" => "", "testimony_video" => "", "mission_video" => "", "gifts_video" => "", "goals_video" => "", "interests_video" => "");
			$video = json_decode($video, TRUE);
		}

		return View::make("frontend/" . $this -> _permission[Auth::user() -> permission] . "/settings/profile") -> with(array("active" => "settings", "profile" => $profile[0], "message" => $message, "video" => $video));
	}

	public function security() {
		$message = "";
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$current = Input::get("current_password");
			$new = Input::get("new_password");

			if (!Hash::check($current, Auth::user() -> password)) {
				$message = "<div class='alert alert-danger alert-dismissable'>
                                <button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button>
                                Current password is not correct.
                            </div>";
			} else {
				DB::table("users") -> where("id", Auth::user() -> id) -> update(array("password" => Hash::make($new)));
				$message = "<div class='alert alert-success alert-dismissable'>
                                <button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button>
                                Your security info is updated successfully.
                            </div>";
			}

			Session::set("message", $message);
			return Redirect::to("/settings/security");
		}

		return View::make("frontend/" . $this -> _permission[Auth::user() -> permission] . "/settings/security") -> with(array("active" => "settings"));
	}

	public function oauth($prefix) {
		switch($prefix) :
			case 'google' :
				return $this -> process_google();
				break;
			case 'facebook' :
				return $this -> process_facebook();
				break;
		endswitch;
	}

	private function process_facebook() {
		try {
			include_once "include/facebook/facebook.php";
		} catch(Exception $e) {
			error_log($e);
		}
		// Create our application instance
		$facebook = new Facebook( array('appId' => Config::get("facebook.app_id"), 'secret' => Config::get("facebook.app_secret")));

		// Get User ID
		$user = $facebook -> getUser();
		// We may or may not have this data based
		// on whether the user is logged in.
		// If we have a $user id here, it means we know
		// the user is logged into
		// Facebook, but we don�t know if the access token is valid. An access
		// token is invalid if the user logged out of Facebook.

		if ($user) {
			//==================== Single query method ======================================
			try {
				// Proceed knowing you have a logged in user who's authenticated.
				$user_profile = $facebook -> api('/me');
			} catch(FacebookApiException $e) {
				error_log($e);
				$user = NULL;
			}
			//==================== Single query method ends =================================
		}

		if ($user) {
			// Get logout URL
			$logoutUrl = $facebook -> getLogoutUrl();
		} else {
			// Get login URL
			$loginUrl = $facebook -> getLoginUrl(array('scope' => 'read_stream, publish_stream, user_birthday, user_location, user_work_history, user_hometown, user_photos', 'redirect_uri' => Config::get("facebook.redirect_url"), ));
		}

		if ($user) {
			// Save your method calls into an array
			$queries = array( array('method' => 'GET', 'relative_url' => '/' . $user), array('method' => 'GET', 'relative_url' => '/' . $user . '/home?limit=50'), array('method' => 'GET', 'relative_url' => '/' . $user . '/friends'), array('method' => 'GET', 'relative_url' => '/' . $user . '/photos?limit=6'), );

			// POST your queries to the batch endpoint on the graph.
			try {
				$batchResponse = $facebook -> api('?batch=' . json_encode($queries), 'POST');
			} catch(Exception $o) {
				error_log($o);
			}

			$user_info = json_decode($batchResponse[0]['body'], TRUE);

			$id = $user_info["id"];
			$first_name = $user_info["first_name"];
			$last_name = $user_info["last_name"];
			$birthday = isset($user_info["birthday"]) ? date("Y-m-d", strtotime($user_info["birthday"])) : null;
			$gender = strtolower($user_info["gender"]) == "male" ? 1 : 2;
			$picture = "//graph.facebook.com/$id/picture";
			$username = $id;
			$password = Hash::make($id);

			$check = DB::table("users") -> where("username", $id) -> get();
			if (!empty($check)) {// already exist
				DB::table("users") -> where("username", $id) -> update(array("first_name" => $first_name, "last_name" => $last_name, "birthday" => $birthday, "gender" => $gender, "picture" => $picture));
				if (Auth::attempt(array('username' => $id, 'password' => $id), true)) {
					Auth::login(Auth::user(), true);
					DB::table("users") -> where("username", $id) -> update(array("last_login_date" => date("Y-m-d H:i:s")));
					return Redirect::to("/dashboard");
				} else {
					return Redirect::to("/");
				}
			} else {// first login
				$newid = DB::table("users") -> insertGetId(array("id" => null, "first_name" => $first_name, "last_name" => $last_name, "birthday" => $birthday, "gender" => $gender, "username" => $id, "password" => $password, "picture" => $picture, "permission" => 100, "token" => null, "created_date" => date("Y-m-d H:i:s"), "last_login_date" => null, "status" => 1));
				DB::table("user_profile") -> insert(array("user_id" => $newid));

				if (Auth::attempt(array('username' => $id, 'password' => $id), true)) {
					Auth::login(Auth::user(), true);
					DB::table("users") -> where("username", $id) -> update(array("last_login_date" => date("Y-m-d H:i:s")));
					return Redirect::to("/dashboard");
				} else {
					return Redirect::to("/");
				}
			}

			exit ;
		} else {
			echo "<script>location.href = '$loginUrl';</script>";
		}
	}

	private function process_google() {
		include ('include/google/Google_Client.php');
		include ('include/google/contrib/Google_Oauth2Service.php');

		session_start();

		$gClient = new Google_Client();
		$gClient -> setApplicationName('Login for christian starter.');
		$gClient -> setClientId(Config::get("google.client_id"));
		$gClient -> setClientSecret(Config::get("google.client_secret"));
		$gClient -> setRedirectUri(Config::get("google.redirect_url"));
		$gClient -> setDeveloperKey(Config::get("google.developer_key"));

		$google_oauthV2 = new Google_Oauth2Service($gClient);

		if (isset($_REQUEST['reset'])) {
			unset($_SESSION['token']);
			$gClient -> revokeToken();
			header('Location: ' . filter_var(Config::get("google.redirect_url"), FILTER_SANITIZE_URL));
			//redirect user back to page
		}

		//If code is empty, redirect user to google authentication page for code.
		//Code is required to aquire Access Token from google
		//Once we have access token, assign token to session variable
		//and we can redirect user back to page and login.
		if (isset($_GET['code'])) {
			$gClient -> authenticate($_GET['code']);
			$_SESSION['token'] = $gClient -> getAccessToken();
			header('Location: ' . filter_var(Config::get("google.redirect_url"), FILTER_SANITIZE_URL));
			return;
		}

		if (isset($_SESSION['token'])) {
			$gClient -> setAccessToken($_SESSION['token']);
		}

		if ($gClient -> getAccessToken()) {
			//For logged in user, get details from google using access token
			$user = $google_oauthV2 -> userinfo -> get();
			$email = $user["email"];
			$first_name = $user["given_name"];
			$last_name = $user["family_name"];
			$picture = $user["picture"];
			$gender = strtolower($user["gender"]) == "male" ? 1 : 2;
			$id = $user["id"];
			$password = Hash::make($id);

			$check = DB::table("users") -> where("email", $email) -> first();
			if (!empty($check)) {// already exist
				DB::table("users") -> where("email", $email) -> update(array("first_name" => $first_name, "last_name" => $last_name, "gender" => $gender, "picture" => $picture));
				if (Auth::attempt(array('email' => $email, 'password' => $id), true)) {
					Auth::login(Auth::user(), true);
					DB::table("users") -> where("email", $email) -> update(array("last_login_date" => date("Y-m-d H:i:s")));
					return Redirect::to("/dashboard");
				} else {
					return Redirect::to("/");
				}
			} else {// first login
				$newid = DB::table("users") -> insertGetId(array("id" => null, "first_name" => $first_name, "last_name" => $last_name, "gender" => $gender, "email" => $email, "username" => $id, "password" => $password, "picture" => $picture, "permission" => 100, "token" => null, "created_date" => date("Y-m-d H:i:s"), "last_login_date" => null, "status" => 1));
				DB::table("user_profile") -> insert(array("user_id" => $newid));

				if (Auth::attempt(array('email' => $email, 'password' => $id), true)) {
					Auth::login(Auth::user(), true);
					DB::table("users") -> where("email", $email) -> update(array("last_login_date" => date("Y-m-d H:i:s")));
					return Redirect::to("/dashboard");
				} else {
					return Redirect::to("/");
				}
			}
		} else {
			//For Guest user, get google login url
			$authUrl = $gClient -> createAuthUrl();
		}

		if (isset($authUrl)) {
			echo "<script>location.href = '$authUrl'</script>";
		} else {
			$email = $user["email"];
			$first_name = $user["given_name"];
			$last_name = $user["family_name"];
			$picture = $user["picture"];
			$gender = strtolower($user["gender"]) == "male" ? 1 : 2;
			$id = $user["id"];
			$password = Hash::make($id);

			$check = DB::table("users") -> where("email", $email) -> first();
			if (!empty($check)) {// already exist
				DB::table("users") -> where("email", $email) -> update(array("first_name" => $first_name, "last_name" => $last_name, "gender" => $gender, "picture" => $picture));
				if (Auth::attempt(array('email' => $email, 'password' => $id), true)) {
					Auth::login(Auth::user(), true);
					return Redirect::to("/dashboard");
				} else {
					return Redirect::to("/");
				}
			} else {// first login
				$newid = DB::table("users") -> insertGetId(array("id" => null, "first_name" => $first_name, "last_name" => $last_name, "gender" => $gender, "email" => $email, "username" => $id, "password" => $password, "picture" => $picture, "permission" => 100, "status" => 1));
				DB::table("user_profile") -> insert(array("user_id" => $newid));

				if (Auth::attempt(array('email' => $email, 'password' => $id), true)) {
					Auth::login(Auth::user(), true);
					return Redirect::to("/dashboard");
				} else {
					return Redirect::to("/");
				}
			}
		}
	}

	public function verify($token) {
		$check = DB::table("users") -> where("token", $token) -> first();
		$top_projects = DB::table("topproject") -> get();
		$about = DB::table("about") -> first();
		$about_content = !empty($about) ? $about -> content : "";

		$contact = DB::table("contact_us") -> first();

		if (empty($contact)) {
			$contact = array("content" => "", "phone_number" => "", "address" => "", "email" => "");
			$contact = json_decode(json_encode($contact), FALSE);
		}

		if (empty($check) || $token == "") {
			return Redirect::to("/account/register");
		}

		DB::table("users") -> where("token", $token) -> update(array("status" => -99, "token" => null));
		$response = $this -> responsebox("Your account has been confirmed. Thanks!", "success");

		return View::make("frontend/register") -> with(array("key" => "home", "response" => $response, "top_projects" => $top_projects, "about_content" => $about_content, "contact" => $contact));
	}

	public function forgot_password() {
		$email = $_POST["email"];

		$check = DB::table("users") -> where("email", $email) -> first();
		if (empty($check)) {
			echo json_encode(array("success" => false, "error" => $this -> responsebox("This email address is not registered in system.")));
			exit ;
		}

		$mail = new PHPMailer;
		$mail -> setFrom(Config::get("app.support_email"));
		$mail -> addAddress($email);

		$password = $this -> generate_rand(12);
		DB::table("users") -> where("email", $email) -> update(array("password" => Hash::make($password)));

		$body = "<style>
					* {
						font-family: Arial;
					}
					table {
						font-size: 12px;
					}
				</style>
				<h4>Your password has been updated.</h4>
				<table>
					<tr>
						<td>Password is " . $password . "</td>
					</tr>
				</table>";

		$mail -> Subject = "Christian Response: Forgot Password.";
		$mail -> msgHTML($body);
		$mail -> AltBody = $body;
		$mail -> send();

		echo json_encode(array("success" => true, "message" => $this -> responsebox("Please check your inbox.", "success")));
	}

}
