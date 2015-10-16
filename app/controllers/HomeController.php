<?php

class HomeController extends BaseController {

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
		$sql = "SELECT 	a.id, a.name, a.thumbnail, 
					(CASE WHEN AVG(b.mark) IS NULL THEN 0 ELSE AVG(b.mark) END) AS review,
					COUNT(c.user_id) AS follow_count,
					a.created_date,
					'prayer' as project_type
				FROM 	prayer a
					LEFT JOIN prayer_review b ON a.id = b.project_id
					LEFT JOIN prayer_follow c ON a.id = c.project_id,
					region_allocate d
				WHERE 	a.status = 1
					AND a.id = d.project_id and d.project_type = 'prayer'
				GROUP BY a.id
				UNION
				SELECT 	a.id, a.name, a.thumbnail, 
					(CASE WHEN AVG(b.mark) IS NULL THEN 0 ELSE AVG(b.mark) END) AS review,
					COUNT(c.user_id) AS follow_count,
					a.created_date,
					'impact' as project_type
				FROM 	impact a
					LEFT JOIN impact_review b ON a.id = b.project_id
					LEFT JOIN impact_follow c ON a.id = c.project_id,
					region_allocate d
				WHERE 	a.status = 1
					AND a.id = d.project_id and d.project_type = 'impact'
				GROUP BY a.id
				UNION
				SELECT 	a.id, a.name, a.thumbnail, 
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
				UNION
				SELECT 	a.id, a.name, a.thumbnail, 
					(CASE WHEN AVG(b.mark) IS NULL THEN 0 ELSE AVG(b.mark) END) AS review,
					COUNT(c.user_id) AS follow_count,
					a.created_date,
					'teaching' as project_type
				FROM 	teaching a
					LEFT JOIN teaching_review b ON a.id = b.project_id
					LEFT JOIN teaching_follow c ON a.id = c.project_id,
					region_allocate d
				WHERE 	a.status = 1
					AND a.id = d.project_id and d.project_type = 'teaching'
				GROUP BY a.id
				order by review desc, follow_count desc, created_date desc
				limit 20";
		$projects = DB::select($sql);

		$news_sql = "SELECT	DISTINCT a.id, CONCAT('News for ', ' ', a.name) AS title, b.created_date, 'prayer' AS post_type
					FROM	prayer a, prayer_communication b
					WHERE 	a.id = b.project_id
					UNION
					SELECT	DISTINCT a.id, CONCAT('News for ', ' ', a.name) AS title, b.created_date, 'impact' AS post_type
					FROM	impact a, impact_communication b
					WHERE 	a.id = b.project_id
					UNION
					SELECT	DISTINCT a.id, CONCAT('News for ', ' ', a.name) AS title, b.created_date, 'nationalreport' AS post_type
					FROM	nationalreport a, nationalreport_communication b
					WHERE 	a.id = b.project_id
					UNION
					SELECT	DISTINCT a.id, CONCAT('News for ', ' ', a.name) AS title, b.created_date, 'regionalreport' AS post_type
					FROM	regionalreport a, regionalreport_communication b
					WHERE 	a.id = b.project_id
					UNION
					SELECT	DISTINCT a.id, CONCAT('News for ', ' ', a.name) AS title, b.created_date, 'teaching' AS post_type
					FROM	teaching a, teaching_communication b
					WHERE 	a.id = b.project_id
					ORDER BY created_date DESC
					limit 5";
		$news = DB::select($news_sql);

		$posts = DB::table("posts") -> orderby("title") -> get();

		$homepage_video = "";
		$videos = DB::table("video_setting") -> first();
		if (!empty($videos)) {
			$homepage_video = $videos -> homepage_video;
		}

		$top_projects = DB::table("topproject") -> get();
		
		$about = DB::table("about") -> first();
		$about_content = !empty($about) ? $about -> content : "";
		
		$contact = DB::table("contact_us") -> first();

		if (empty($contact)) {
			$contact = array("content" => "", "phone_number" => "", "address" => "", "email" => "");
			$contact = json_decode(json_encode($contact), FALSE);
		}

		return View::make("/frontend/home") -> with(array("key" => "home", "projects" => $projects, "posts" => $posts, "news" => $news, "homepage_video" => $homepage_video, "top_projects" => $top_projects, "about_content" => $about_content, "contact" => $contact));
	}

	public function about() {
		$top_projects = DB::table("topproject") -> get();
		$about = DB::table("about") -> first();
		$about_content = !empty($about) ? $about -> content : "";
		
		$contact = DB::table("contact_us") -> first();

		if (empty($contact)) {
			$contact = array("content" => "", "phone_number" => "", "address" => "", "email" => "");
			$contact = json_decode(json_encode($contact), FALSE);
		}

		return View::make("/frontend/about") -> with(array("key" => "about", "top_projects" => $top_projects, "about_content" => $about_content, "contact" => $contact));
	}

	public function contact() {
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$name = Input::get("name");
			$email = Input::get("email");
			$message = Input::get("message");

			DB::table("contacts") -> insert(array("id" => null, "name" => $name, "email" => $email, "text" => $message, "created_date" => date("Y-m-d H:i:s"), "status" => 100));
		}

		$top_projects = DB::table("topproject") -> get();
		$about = DB::table("about") -> first();
		$about_content = !empty($about) ? $about -> content : "";
		
		$contact = DB::table("contact_us") -> first();

		if (empty($contact)) {
			$contact = array("content" => "", "phone_number" => "", "address" => "", "email" => "");
			$contact = json_decode(json_encode($contact), FALSE);
		}

		return View::make("/frontend/contact") -> with(array("key" => "contact", "top_projects" => $top_projects, "about_content" => $about_content, "contact" => $contact));
	}

	public function view_post($id) {
		$post = DB::table("posts") -> where("id", $id) -> first();
		$top_projects = DB::table("topproject") -> get();
		$about = DB::table("about") -> first();
		$about_content = !empty($about) ? $about -> content : "";
		
		$contact = DB::table("contact_us") -> first();

		if (empty($contact)) {
			$contact = array("content" => "", "phone_number" => "", "address" => "", "email" => "");
			$contact = json_decode(json_encode($contact), FALSE);
		}

		return View::make("/frontend/view_post") -> with(array("key" => "home", "post" => $post, "top_projects" => $top_projects, "about_content" => $about_content, "contact" => $contact));
	}

	public function view_post_content($id) {
		$post = DB::table("posts") -> where("id", $id) -> first();
		$top_projects = DB::table("topproject") -> get();
		$about = DB::table("about") -> first();
		$about_content = !empty($about) ? $about -> content : "";
		
		$contact = DB::table("contact_us") -> first();

		if (empty($contact)) {
			$contact = array("content" => "", "phone_number" => "", "address" => "", "email" => "");
			$contact = json_decode(json_encode($contact), FALSE);
		}

		return View::make("/frontend/view_post_content") -> with(array("key" => "home", "content" => $post -> content, "top_projects" => $top_projects, "about_content" => $about_content, "contact" => $contact));
	}

	public function news() {
		$temp = DB::select("SELECT	DISTINCT a.id, CONCAT('News for ', ' ', a.name) AS title, b.created_date, 'prayer' AS post_type
							FROM	prayer a, prayer_communication b
							WHERE 	a.id = b.project_id
							UNION
							SELECT	DISTINCT a.id, CONCAT('News for ', ' ', a.name) AS title, b.created_date, 'impact' AS post_type
							FROM	impact a, impact_communication b
							WHERE 	a.id = b.project_id
							UNION
							SELECT	DISTINCT a.id, CONCAT('News for ', ' ', a.name) AS title, b.created_date, 'nationalreport' AS post_type
							FROM	nationalreport a, nationalreport_communication b
							WHERE 	a.id = b.project_id
							UNION
							SELECT	DISTINCT a.id, CONCAT('News for ', ' ', a.name) AS title, b.created_date, 'regionalreport' AS post_type
							FROM	regionalreport a, regionalreport_communication b
							WHERE 	a.id = b.project_id
							UNION
							SELECT	DISTINCT a.id, CONCAT('News for ', ' ', a.name) AS title, b.created_date, 'teaching' AS post_type
							FROM	teaching a, teaching_communication b
							WHERE 	a.id = b.project_id
							ORDER BY created_date DESC
							LIMIT 1");

		$news = array();
		if (!empty($temp)) {
			$news["id"] = $temp[0] -> id;
			$news["title"] = $temp[0] -> title;
			$news["post_type"] = $temp[0] -> post_type;
			$news["created_date"] = date("F d, Y, H:i:s", strtotime($temp[0] -> created_date));
			$news["original_date"] = $temp[0] -> created_date;
		}

		echo json_encode(array("news" => $news));
		DB::table("news_temp") -> delete();
	}
	
	public function private_policy() {
		$top_projects = DB::table("topproject") -> get();
		$about = DB::table("about") -> first();
		$about_content = !empty($about) ? $about -> content : "";
		
		$contact = DB::table("contact_us") -> first();

		if (empty($contact)) {
			$contact = array("content" => "", "phone_number" => "", "address" => "", "email" => "");
			$contact = json_decode(json_encode($contact), FALSE);
		}
		
		return View::make("/frontend/private_policy") -> with(array("key" => "home", "top_projects" => $top_projects, "about_content" => $about_content, "contact" => $contact));
	}
}
