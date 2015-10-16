<?php

class TeachController extends BaseController {

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
		$teaching_video = "";
		$videos = DB::table("video_setting") -> first();
		if (!empty($videos)) {
			$teaching_video = $videos -> teaching_video;
		}

		$courses = DB::table("teaching_course") -> orderby("order") -> get();

		$top_projects = DB::table("topproject") -> get();
		$about = DB::table("about") -> first();
		$about_content = !empty($about) ? $about -> content : "";
		
		$contact = DB::table("contact_us") -> first();

		if (empty($contact)) {
			$contact = array("content" => "", "phone_number" => "", "address" => "", "email" => "");
			$contact = json_decode(json_encode($contact), FALSE);
		}

		return View::make("/frontend/teaching") -> with(array("key" => "teaching", "teaching_video" => $teaching_video, "top_projects" => $top_projects, "about_content" => $about_content, "courses" => $courses, "contact" => $contact));
	}

	public function pdfview($id) {
		$course = DB::table("teaching_course") -> where("id", $id) -> first();

		$top_projects = DB::table("topproject") -> get();
		$about = DB::table("about") -> first();
		$about_content = !empty($about) ? $about -> content : "";
		
		$contact = DB::table("contact_us") -> first();

		if (empty($contact)) {
			$contact = array("content" => "", "phone_number" => "", "address" => "", "email" => "");
			$contact = json_decode(json_encode($contact), FALSE);
		}

		return View::make("/frontend/teaching_pdfview") -> with(array("key" => "teaching", "filename" => $course -> pdf, "top_projects" => $top_projects, "about_content" => $about_content, "contact" => $contact));
	}

}
