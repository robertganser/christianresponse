<?php
/*
 |--------------------------------------------------------------------------
 | Application Routes
 |--------------------------------------------------------------------------
 |
 | Here is where you can register all of the routes for an application.
 | It's a breeze. Simply tell Laravel the URIs it should respond to
 | and give it the Closure to execute when that URI is requested.
 |
 */

Route::get('/', "HomeController@index");

Route::get('/private-policy', "HomeController@private_policy");
Route::get('/share/region/{param}', "SharedController@region");
Route::match(array('GET', 'POST'), '/share/region/{param}/step', "SharedController@region_step");
Route::get('/share/region/{param}/donation/success/{transaction_id}', "SharedController@region_donation_success");
Route::get('/share/region/{param}/donation/cancel/{transaction_id}', "SharedController@region_donation_cancel");

Route::get('/post/view/{id}', "HomeController@view_post");
Route::get('/post/view/content/{id}', "HomeController@view_post_content");
Route::post('/ajax/sendmail', "BaseController@sendmail");
Route::get('/ajax/news', "HomeController@news");
Route::get('/project', "ProjectController@index");
Route::get('/project/{category}', "ProjectController@projects_of_category");
Route::match(array('GET', 'POST'), '/project/view/{type}/{project_id}', "ProjectController@project_view");
Route::match(array('GET', 'POST'), '/project/view/{type}/{project_id}/donate', "ProjectController@donate");
Route::get('/project/{type}/{id}/donation/success/{transactionid}/{redirect}', "DonationController@success");
Route::get('/project/{type}/{id}/donation/cancel/{transactionid}/{redirect}', "DonationController@cancel");

Route::get('/teaching', "TeachController@index");
Route::get('/teaching/view/{id}', "TeachController@pdfview");
Route::get('/testimonies', "TestimonyController@index");
Route::get('/testimonies/{search}', "TestimonyController@index");
Route::get('/about-us', "HomeController@about");
Route::match(array('GET', 'POST'), '/contact', "HomeController@contact");

Route::post('/account/login_check', "AccountController@login_check");
Route::post('/account/forgot_password', "AccountController@forgot_password");
Route::post('/preload', "AccountController@preload");
Route::match(array('GET', 'POST'), '/account/register', "AccountController@register");
Route::get('/account/oauth/{prefix}', "AccountController@oauth");
Route::get('/account/verify/{token}', "AccountController@verify");

Route::get('/ajax/project/{type}/events/get/{id}', "ProjectController@get_event");
Route::post('/ajax/project/{type}/events/withdraw/{id}', "ProjectController@withdraw");
Route::post('/ajax/project/{type}/events/join/{id}', "ProjectController@join");
Route::post('/ajax/project/{type}/{id}/invite', "ProjectController@invite");
Route::post('/ajax/project/{type}/{id}/{event_id}/invite', "ProjectController@invite_event");

Route::group(array("before" => 'auth'), function() {
	Route::get('/account/logout', "AccountController@logout");
	Route::get('/dashboard', "DashboardController@index");
	Route::get('/dashboard/project/view/{project_type}/{id}', "DashboardController@project_view");
	Route::post('/dashboard/project/view/{project_type}/{id}', "DashboardController@project_view");

	// donation
	Route::match(array('GET', 'POST'), '/project/{type}/{id}/donation', "DonationController@step");

	//project
	Route::post('/ajax/projects/{type}', "DashboardController@search");
	Route::post('/project/{type}/unfollowing/{id}', "UserController@unfollowing");

	Route::match(array('GET', 'POST'), '/projects/impact', "UserController@impact");
	Route::match(array('GET', 'POST'), '/projects/impact/edit', "UserController@impact_edit");
	Route::match(array('GET', 'POST'), '/projects/impact/edit/{project_id}', "UserController@impact_edit");
	Route::match(array('GET', 'POST'), '/projects/impact/{project_id}/events', "UserController@impact_events");
	Route::match(array('GET', 'POST'), '/projects/impact/{project_id}/events/edit', "UserController@impact_events_edit");
	Route::match(array('GET', 'POST'), '/projects/impact/{project_id}/events/edit/{event_id}', "UserController@impact_events_edit");
	Route::match(array('GET', 'POST'), '/projects/impact/{project_id}/hugs', "UserController@impact_hugs");
	Route::get('/projects/impact/{project_id}/followings', "UserController@impact_followings");
	Route::get('/projects/impact/{project_id}/event/{event_id}/joins', "UserController@impact_event_joins");

	Route::match(array('GET', 'POST'), '/projects/prayer', "UserController@prayer");
	Route::match(array('GET', 'POST'), '/projects/prayer/edit', "UserController@prayer_edit");
	Route::match(array('GET', 'POST'), '/projects/prayer/edit/{project_id}', "UserController@prayer_edit");
	Route::match(array('GET', 'POST'), '/projects/prayer/{project_id}/events', "UserController@prayer_events");
	Route::match(array('GET', 'POST'), '/projects/prayer/{project_id}/events/edit', "UserController@prayer_events_edit");
	Route::match(array('GET', 'POST'), '/projects/prayer/{project_id}/events/edit/{event_id}', "UserController@prayer_events_edit");
	Route::match(array('GET', 'POST'), '/projects/prayer/{project_id}/hugs', "UserController@prayer_hugs");
	Route::get('/projects/prayer/{project_id}/followings', "UserController@prayer_followings");
	Route::get('/projects/prayer/{project_id}/event/{event_id}/joins', "UserController@prayer_event_joins");

	Route::match(array('GET', 'POST'), '/projects/report', "UserController@report");
	Route::match(array('GET', 'POST'), '/projects/report/{prefix}/edit', "UserController@report_edit");
	Route::match(array('GET', 'POST'), '/projects/report/{prefix}/edit/{project_id}', "UserController@report_edit");
	Route::match(array('GET', 'POST'), '/projects/report/{prefix}/{project_id}/hugs', "UserController@report_hugs");
	Route::match(array('GET', 'POST'), '/projects/report/{prefix}/{project_id}/events', "UserController@report_events");
	Route::match(array('GET', 'POST'), '/projects/report/{prefix}/{project_id}/events/edit', "UserController@report_events_edit");
	Route::match(array('GET', 'POST'), '/projects/report/{prefix}/{project_id}/events/edit/{event_id}', "UserController@report_events_edit");
	Route::get('/projects/report/{prefix}/{project_id}/followings', "UserController@report_followings");
	Route::get('/projects/report/{prefix}/{project_id}/reviews', "ProjectController@report_reviews");
	Route::get('/projects/report/{prefix}/{project_id}/transactions', "ProjectController@report_transactions");
	Route::get('/projects/report/{prefix}/{project_id}/event/{event_id}/joins', "UserController@report_event_joins");

	Route::match(array('GET', 'POST'), '/projects/teaching', "UserController@teaching");
	Route::match(array('GET', 'POST'), '/projects/teaching/edit', "UserController@teaching_edit");
	Route::match(array('GET', 'POST'), '/projects/teaching/edit/{project_id}', "UserController@teaching_edit");
	Route::match(array('GET', 'POST'), '/projects/teaching/{project_id}/events', "UserController@teaching_events");
	Route::match(array('GET', 'POST'), '/projects/teaching/{project_id}/events/edit', "UserController@teaching_events_edit");
	Route::match(array('GET', 'POST'), '/projects/teaching/{project_id}/events/edit/{event_id}', "UserController@teaching_events_edit");
	Route::match(array('GET', 'POST'), '/projects/teaching/{project_id}/hugs', "UserController@teaching_hugs");
	Route::get('/projects/teaching/{project_id}/followings', "UserController@teaching_followings");
	Route::get('/projects/teaching/{project_id}/event/{event_id}/joins', "UserController@teaching_event_joins");

	Route::get('/projects/{type}/{project_id}/transactions', "ProjectController@transactions");
	Route::get('/projects/{type}/{project_id}/reviews', "ProjectController@reviews");

	Route::get('/{prefix}/project/{type}/{project_id}/event/{event_id}/join/{action}/{transactionid}', "ProjectController@join");

	// setting
	Route::match(array('GET', 'POST'), '/settings/profile', "AccountController@profile");
	Route::match(array('GET', 'POST'), '/settings/security', "AccountController@security");

	// search
	Route::match(array('GET', 'POST'), '/search/{prefix}', "UserController@search_for");
	Route::match(array('GET', 'POST'), '/search/{prefix}/{type}/view/{id}', "UserController@view");

	// region
	Route::match(array('GET', 'POST'), '/region', "RegionController@region");
	Route::match(array('GET', 'POST'), '/region/{date}', "RegionController@region");
	Route::match(array('GET', 'POST'), '/region/{date}/{region_id}', "RegionController@region");
	Route::match(array('GET', 'POST'), '/region/{region_id}/donation/step', "RegionController@donation_step");
	Route::match(array('GET', 'POST'), '/region/{region_id}/donation/success/{transaction_id}/{date}', "RegionController@success");
	Route::match(array('GET', 'POST'), '/region/{region_id}/donation/cancel/{transaction_id}/{date}', "RegionController@cancel");
	Route::get('/region/{date}/{region_id}/{type}/{project_id}/{event_id}/join/{action}/{transactionid}', "RegionController@join");
});

Route::group(array("before" => 'auth|general-overall-region-administrator'), function() {
	// projects

	if (!Auth::check()) {
		return Redirect::to("/");
		exit ;
	}

	$controller = "";
	switch(Auth::user()->permission) :
		case -1 :
			$controller = "OverallController";
			break;
		case -2 :
			$controller = "RegionController";
			break;
		case -3 :
			$controller = "GeneralController";
			break;
	endswitch;

	Route::match(array('GET', 'POST'), '/manages/projects/{type}', $controller . "@manage_projects");
	Route::match(array('GET', 'POST'), '/manages/projects/{type}/edit', $controller . "@project_edit");
	Route::match(array('GET', 'POST'), '/manages/projects/{type}/edit/{id}', $controller . "@project_edit");
	Route::match(array('GET', 'POST'), '/manages/projects/{type}/{project_id}/events', $controller . "@project_events");
	Route::match(array('GET', 'POST'), '/manages/projects/{type}/{project_id}/events/edit', $controller . "@project_events_edit");
	Route::match(array('GET', 'POST'), '/manages/projects/{type}/{project_id}/events/edit/{event_id}', $controller . "@project_events_edit");
	Route::match(array('GET', 'POST'), '/manages/projects/{type}/{project_id}/hugs', $controller . "@project_hugs");
	Route::get('/manages/projects/{type}/{project_id}/followings', $controller . "@project_followings");
	Route::get('/manages/projects/{type}/{project_id}/reviews', $controller . "@project_reviews");
	Route::get('/manages/projects/{type}/{project_id}/event/{event_id}/joins', $controller . "@project_event_joins");
	Route::get('/manages/projects/{type}/{project_id}/transactions', $controller . "@project_transactions");

	Route::match(array('GET', 'POST'), '/manages/projects/report/{prefix}/edit', $controller . "@projectreport_edit");
	Route::match(array('GET', 'POST'), '/manages/projects/report/{prefix}/edit/{project_id}', $controller . "@projectreport_edit");
	Route::match(array('GET', 'POST'), '/manages/projects/report/{prefix}/{project_id}/hugs', $controller . "@projectreport_hugs");
	Route::match(array('GET', 'POST'), '/manages/projects/report/{prefix}/{project_id}/events', $controller . "@projectreport_events");
	Route::match(array('GET', 'POST'), '/manages/projects/report/{prefix}/{project_id}/events/edit', $controller . "@projectreport_events_edit");
	Route::match(array('GET', 'POST'), '/manages/projects/report/{prefix}/{project_id}/events/edit/{event_id}', $controller . "@projectreport_events_edit");
	Route::get('/manages/projects/report/{prefix}/{project_id}/followings', $controller . "@projectreport_followings");
	Route::get('/manages/projects/report/{prefix}/{project_id}/reviews', $controller . "@projectreport_reviews");
	Route::get('/manages/projects/report/{prefix}/{project_id}/transactions', $controller . "@projectreport_transactions");
	Route::get('/manages/projects/report/{prefix}/{project_id}/event/{event_id}/joins', $controller . "@projectreport_event_joins");

	Route::get('/manages/users', $controller . "@users");
	Route::match(array('GET', 'POST'), '/manages/users/edit/{id}', $controller . "@user_edit");
});

Route::group(array("before" => 'auth|region-administrator'), function() {
	// region
	Route::match(array('GET', 'POST'), '/manages/region-page', "RegionController@manage_region");
});

Route::group(array("before" => 'auth|overall-administrator'), function() {
	// user permission
	Route::match(array('GET', 'POST'), '/manages/user-permission', "OverallController@user_permissions");
	Route::match(array('GET', 'POST'), '/manages/user-permission/{permission}', "OverallController@user_permissions");

	// set paypal address
	Route::match(array('GET', 'POST'), '/settings/paypal-address', "OverallController@set_paypal");

	// region
	Route::match(array('GET', 'POST'), '/manages/region', "OverallController@region");
	Route::match(array('GET', 'POST'), '/manages/region/{country}', "OverallController@region");
	Route::match(array('GET', 'POST'), '/manages/region/edit/{country}/{state}/{id}', "OverallController@region_edit");

	//contacts
	Route::match(array('GET', 'POST'), '/contacts', "OverallController@contacts");
	Route::match(array('GET', 'POST'), '/contacts/view/{id}', "OverallController@contacts_view");

	// posts
	Route::get('/manages/posts', "OverallController@posts");
	Route::match(array('GET', 'POST'), '/manages/posts/edit', "OverallController@posts_edit");
	Route::match(array('GET', 'POST'), '/manages/posts/edit/{id}', "OverallController@posts_edit");
	Route::match(array('GET', 'POST'), '/manages/posts/delete/{id}', "OverallController@delete_posts");

	// video setting
	Route::match(array('GET', 'POST'), '/manages/video-setting', "OverallController@video_setting");

	// about us
	Route::match(array('GET', 'POST'), '/manages/aboutus', "OverallController@aboutus");

	// contact us
	Route::match(array('GET', 'POST'), '/manages/contactus', "OverallController@contactus");

	//teaching course
	Route::get('/manages/teaching-course', "OverallController@teaching_course");
	Route::get('/manages/teaching-course/delete/{id}', "OverallController@delete_teaching_course");
	Route::match(array('GET', 'POST'), '/manages/teaching-course/edit', "OverallController@teaching_course_edit");
	Route::match(array('GET', 'POST'), '/manages/teaching-course/edit/{id}', "OverallController@teaching_course_edit");
	Route::post('/manages/teaching-course/change_order', "OverallController@change_order");

	//financial
	Route::get('/financial', "FinanceController@home");
	Route::get('/financial/project/{type}/donation/details', "FinanceController@project_donations");
	Route::get('/financial/project/{type}/event/details', "FinanceController@project_events");
	Route::get('/financial/project/{type}/overall/donation/details', "FinanceController@project_donation_overall");
	Route::get('/financial/project/{type}/overall/event/details', "FinanceController@project_event_overall");

	Route::get('/financial/region/donation/details', "FinanceController@region_donations");
	Route::get('/financial/region/event/details', "FinanceController@region_event");

	Route::get('/financial/region/overall/donation/details', "FinanceController@region_donation_overall");
	Route::get('/financial/region/overall/event/details', "FinanceController@region_event_overall");
	
	Route::post('/manages/users/search/o', "OverallController@search_users");
});

Route::group(array("before" => 'auth|general-administrator'), function() {
	Route::post('/dashboard/search/project', "GeneralController@search_project");
	Route::post('/dashboard/search/facilitator', "GeneralController@search_facilitator");
	Route::post('/manages/users/search/g', "GeneralController@search_users");
});
