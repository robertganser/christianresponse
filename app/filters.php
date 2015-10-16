<?php
/*
 |--------------------------------------------------------------------------
 | Application & Route Filters
 |--------------------------------------------------------------------------
 |
 | Below you will find the "before" and "after" events for the application
 | which may be used to do any work before or after a request into your
 | application. Here you may also register your custom route filters.
 |
 */
App::before(function($request) {
	//
});
App::after(function($request, $response) {
	//
});
/*
 |--------------------------------------------------------------------------
 | Authentication Filters
 |--------------------------------------------------------------------------
 |
 | The following filters are used to verify that the user of the current
 | session is logged into this application. The "basic" filter easily
 | integrates HTTP Basic authentication for quick, simple checking.
 |
 */
Route::filter('auth', function() {
	if (!Auth::check())
		return Redirect::to("/");

	if (Auth::user() -> status == -1) :
		Auth::logout();
		Session::flush();
		return View::make("elements/error/account_removed");
	elseif (Auth::user() -> status == -2) :
		Auth::logout();
		Session::flush();
		return View::make("elements/error/account_blocked");
	endif;
});

Route::filter('general-overall-region-administrator', function() {
	if (!Auth::check())
		return Redirect::to("/");
	
	if (Auth::user() -> permission != -1 && Auth::user() -> permission != -2 && Auth::user() -> permission != -3)
		return View::make("elements/error/permission-fail") -> with(array("active" => ""));
});

Route::filter('overall-administrator', function() {
	if (!Auth::check())
		return Redirect::to("/");
	
	if (Auth::user() -> permission != -1)
		return View::make("elements/error/permission-fail") -> with(array("active" => ""));
});

Route::filter('region-administrator', function() {
	if (!Auth::check())
		return Redirect::to("/");
	
	if (Auth::user() -> permission != -2)
		return View::make("elements/error/permission-fail") -> with(array("active" => ""));
});

Route::filter('general-administrator', function() {
	if (!Auth::check())
		return Redirect::to("/");
		
	if (Auth::user() -> permission != -3)
		return View::make("elements/error/permission-fail") -> with(array("active" => ""));
});
