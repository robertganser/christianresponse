<?php

class TestimonyController extends BaseController {

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

	public function index($search = "") {
		$result = DB::select("select a.*, b.* from users a, user_profile b where a.permission != -1 and a.id = b.user_id and b.testimony like '%$search%' order by a.first_name, a.last_name");
		$top_projects = DB::table("topproject") -> get();
		$about = DB::table("about") -> first();
		$about_content = !empty($about) ? $about -> content : "";
		
		$contact = DB::table("contact_us") -> first();

		if (empty($contact)) {
			$contact = array("content" => "", "phone_number" => "", "address" => "", "email" => "");
			$contact = json_decode(json_encode($contact), FALSE);
		}
		
		return View::make("/frontend/testimonies") -> with(array("key" => "testimonies", "search" => $search, "result" => $search == "" ? array() : $result, "top_projects" => $top_projects, "about_content" => $about_content, "contact" => $contact));
	}

}
