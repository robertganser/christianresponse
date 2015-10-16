<?php

class FinanceController extends BaseController {

	/*
	 |--------------------------------------------------------------------------
	 | Default Home Controller
	 |--------------------------------------------------------------------------
	 |
	 | You may wish to use controllers instead of, or in addition to, Closure
	 | based routes. That's great! Here is an example controller method to
	 | get you started. To route to this controller, just add the route:
	 |
	 |	Route::get('/', 'FinanceController@showWelcome');
	 |
	 */

	public function home() {
		$impact_total = DB::table('impact_transaction') -> where("status", 1) -> sum('amount');
		$impact_event_total = DB::table('impact_event_transaction') -> where("status", 1) -> sum('amount');

		$prayer_total = DB::table('prayer_transaction') -> where("status", 1) -> sum('amount');
		$prayer_event_total = DB::table('prayer_event_transaction') -> where("status", 1) -> sum('amount');

		$nationalreport_total = DB::table('nationalreport_transaction') -> where("status", 1) -> sum('amount');
		$nationalreport_event_total = DB::table('nationalreport_event_transaction') -> where("status", 1) -> sum('amount');

		$regionalreport_total = DB::table('regionalreport_transaction') -> where("status", 1) -> sum('amount');
		$regionalreport_event_total = DB::table('regionalreport_event_transaction') -> where("status", 1) -> sum('amount');

		$teaching_total = DB::table('teaching_transaction') -> where("status", 1) -> sum('amount');
		$teaching_event_total = DB::table('teaching_event_transaction') -> where("status", 1) -> sum('amount');

		$region_total = DB::table('region_transaction') -> where("status", 1) -> sum('amount');
		$region_event_total = DB::table('region_annual_event_transaction') -> where("status", 1) -> sum('amount');

		$financial = array("total" => $impact_total * 1 + $impact_event_total * 1 + $prayer_total * 1 + $prayer_event_total * 1 + $nationalreport_total * 1 + $nationalreport_event_total * 1 + $regionalreport_total * 1 + $regionalreport_event_total * 1 + $teaching_total * 1 + $teaching_event_total * 1 + $region_total * 1 + $region_event_total * 1, "impact_total" => $impact_total, "impact_event_total" => $impact_event_total, "prayer_total" => $prayer_total, "prayer_event_total" => $prayer_event_total, "report_total" => $nationalreport_total * 1 + $regionalreport_total * 1, "report_event_total" => $nationalreport_event_total * 1 + $regionalreport_event_total * 1, "teaching_total" => $teaching_total, "teaching_event_total" => $teaching_event_total, "region_total" => $region_total, "region_event_total" => $region_event_total);
		$financial = json_decode(json_encode($financial), FALSE);

		$impact_total = DB::table('overall_transaction') -> where("status", 1) -> where("project_type", "impact") -> sum('amount');
		$impact_event_total = DB::table('overall_event_transaction') -> where("status", 1) -> where("project_type", "impact") -> sum('amount');

		$prayer_total = DB::table('overall_transaction') -> where("status", 1) -> where("project_type", "prayer") -> sum('amount');
		$prayer_event_total = DB::table('overall_event_transaction') -> where("status", 1) -> where("project_type", "prayer") -> sum('amount');

		$report_total = DB::table('overall_transaction') -> where("status", 1) -> whereIn("project_type", array("nationalreport", "regionalreport")) -> sum('amount');
		$report_event_total = DB::table('overall_event_transaction') -> where("status", 1) -> whereIn("project_type", array("nationalreport", "regionalreport")) -> sum('amount');

		$teaching_total = DB::table('overall_transaction') -> where("status", 1) -> where("project_type", "teaching") -> sum('amount');
		$teaching_event_total = DB::table('overall_event_transaction') -> where("status", 1) -> where("project_type", "teaching") -> sum('amount');

		$region_total = DB::table('overall_transaction') -> where("status", 1) -> where("project_type", "region") -> sum('amount');
		$region_event_total = DB::table('overall_event_transaction') -> where("status", 1) -> where("project_type", "annual") -> sum('amount');

		$overall = array("total" => $impact_total * 1 + $impact_event_total * 1 + $prayer_total * 1 + $prayer_event_total * 1 + $report_total * 1 + $report_event_total * 1 + $teaching_total * 1 + $teaching_event_total * 1 + $region_total * 1 + $region_event_total * 1, "impact_total" => $impact_total, "impact_event_total" => $impact_event_total, "prayer_total" => $prayer_total, "prayer_event_total" => $prayer_event_total, "report_total" => $report_total, "report_event_total" => $report_event_total, "teaching_total" => $teaching_total, "teaching_event_total" => $teaching_event_total, "region_total" => $region_total, "region_event_total" => $region_event_total);
		$overall = json_decode(json_encode($overall), FALSE);

		return View::make("/frontend/overall/finance/home") -> with(array("active" => "financial", "financial" => $financial, "overall" => $overall));
	}

	public function project_donations($type) {
		if ($type == "report") {
			$list = DB::select("SELECT 	b.id, b.name, c.username, SUM(a.amount) AS amount, b.state, b.country, b.zip_code, 'nationalreport' AS type
								FROM 	nationalreport_transaction a, nationalreport b, users c
								WHERE 	a.status = 1
									AND a.project_id = b.id
									AND b.user_id = c.id
								GROUP BY b.id
								UNION
								SELECT 	b.id, b.name, c.username, SUM(a.amount) AS amount, b.state, b.country, b.zip_code, 'regionalreport' AS type
								FROM 	regionalreport_transaction a, regionalreport b, users c
								WHERE 	a.status = 1
									AND a.project_id = b.id
									AND b.user_id = c.id
								GROUP BY b.id
								ORDER BY type, id");
		} else {
			$list = DB::select("SELECT 	b.id, b.name, c.username, SUM(a.amount) AS amount, b.state, b.country, b.zip_code, '" . $type . "' as type
							FROM 	" . $type . "_transaction a, " . $type . " b, users c
							WHERE 	a.status = 1
								AND a.project_id = b.id
								AND b.user_id = c.id
							GROUP BY b.id
							ORDER BY b.id");
		}
		$total = 0;
		foreach ($list as $one) :
			$total += $one -> amount;
		endforeach;

		return View::make("/frontend/overall/finance/project_donation_details") -> with(array("active" => "financial", "type" => $type, "list" => $list, "total" => $total));
	}

	public function region_donations() {
		$list = DB::select("SELECT 	b.id, CASE WHEN c.title IS NULL THEN CONCAT(b.country, ', ', b.state) ELSE c.title END AS title, SUM(a.amount) AS amount, b.country, b.state, d.username
							FROM 	region_transaction a, region_manager b
								LEFT JOIN region_page c ON c.region_id = b.id,
								users d
							WHERE 	a.status = 1
								AND a.region_id = b.id
								AND b.user_id = d.id
							GROUP BY b.id");

		$total = 0;
		foreach ($list as $one) :
			$total += $one -> amount;
		endforeach;

		return View::make("/frontend/overall/finance/region_donation_details") -> with(array("active" => "financial", "list" => $list, "total" => $total));
	}

	public function region_donation_overall() {
		$list = DB::select("SELECT 	b.id, CASE WHEN c.title IS NULL THEN CONCAT(b.country, ', ', b.state) ELSE c.title END AS title, SUM(a.amount) AS amount, b.country, b.state, d.username
							FROM 	overall_transaction a, region_manager b
								LEFT JOIN region_page c ON c.region_id = b.id,
								users d
							WHERE 	a.status = 1
								AND a.project_id = b.id
								AND b.user_id = d.id
								AND a.project_type = 'region'
							GROUP BY b.id");

		$total = 0;
		foreach ($list as $one) :
			$total += $one -> amount;
		endforeach;

		return View::make("/frontend/overall/finance/region_overall_details") -> with(array("active" => "financial", "list" => $list, "total" => $total));
	}

	public function project_donation_overall($type) {
		if ($type == "report") {
			$list = DB::select("SELECT 	b.id, b.name, c.username, SUM(a.amount) AS amount, b.state, b.country, b.zip_code
								FROM 	overall_transaction a, nationalreport b, users c
								WHERE 	a.status = 1
									AND a.project_id = b.id
									AND b.user_id = c.id
									AND a.project_type = 'nationalreport'
								GROUP BY b.id
								UNION
								SELECT 	b.id, b.name, c.username, SUM(a.amount) AS amount, b.state, b.country, b.zip_code
								FROM 	overall_transaction a, regionalreport b, users c
								WHERE 	a.status = 1
									AND a.project_id = b.id
									AND b.user_id = c.id
									AND a.project_type = 'regionalreport'
								GROUP BY b.id");
		} else {
			$list = DB::select("SELECT 	b.id, b.name, c.username, SUM(a.amount) AS amount, b.state, b.country, b.zip_code
								FROM 	overall_transaction a, " . $type . " b, users c
								WHERE 	a.status = 1
									AND a.project_id = b.id
									AND b.user_id = c.id
									AND a.project_type = '" . $type . "'
								GROUP BY b.id");
		}
		$total = 0;
		foreach ($list as $one) :
			$total += $one -> amount;
		endforeach;

		return View::make("/frontend/overall/finance/project_overall_details") -> with(array("active" => "financial", "type" => $type, "list" => $list, "total" => $total));
	}

	public function project_events($type) {
		if ($type == "report") {
			$list = DB::select("SELECT 	c.id, c.name, b.id AS event_id, b.title, SUM(a.amount) AS amount, b.country, b.state, b.zip_code, d.username, b.cost
								FROM 	nationalreport_event_transaction a, nationalreport_event b, nationalreport c, users d
								WHERE 	a.event_id = b.id
									AND b.project_id = c.id
									AND c.user_id = d.id
									AND a.status = 1
								GROUP BY b.id
								UNION
								SELECT 	c.id, c.name, b.id AS event_id, b.title, SUM(a.amount) AS amount, b.country, b.state, b.zip_code, d.username, b.cost
								FROM 	regionalreport_event_transaction a, regionalreport_event b, regionalreport c, users d
								WHERE 	a.event_id = b.id
									AND b.project_id = c.id
									AND c.user_id = d.id
									AND a.status = 1
								GROUP BY b.id
								ORDER BY id");
		} else {
			$list = DB::select("SELECT 	c.id, c.name, b.id AS event_id, b.title, SUM(a.amount) AS amount, b.country, b.state, b.zip_code, d.username, b.cost
								FROM 	" . $type . "_event_transaction a, " . $type . "_event b, " . $type . " c, users d
								WHERE 	a.event_id = b.id
									AND b.project_id = c.id
									AND c.user_id = d.id
									AND a.status = 1
								GROUP BY b.id
								ORDER BY c.id");
		}
		$total = 0;
		foreach ($list as $one) :
			$total += $one -> amount;
		endforeach;

		return View::make("/frontend/overall/finance/project_event_details") -> with(array("active" => "financial", "type" => $type, "list" => $list, "total" => $total));
	}

	public function project_event_overall($type) {
		if ($type == "report") {
			$list = DB::select("SELECT 	a.project_id, c.name, a.event_id, b.title, SUM(a.amount) AS amount, b.country, b.state, b.zip_code, d.username, b.cost
								FROM 	overall_event_transaction a, nationalreport_event b, nationalreport c, users d
								WHERE 	a.event_id = b.id
									AND b.project_id = c.id
									AND a.project_id = c.id
									AND c.user_id = d.id
									AND a.project_type = 'nationalreport'
									AND a.status = 1
								GROUP BY c.id
								UNION
								SELECT 	a.project_id, c.name, a.event_id, b.title, SUM(a.amount) AS amount, b.country, b.state, b.zip_code, d.username, b.cost
								FROM 	overall_event_transaction a, regionalreport_event b, regionalreport c, users d
								WHERE 	a.event_id = b.id
									AND b.project_id = c.id
									AND a.project_id = c.id
									AND c.user_id = d.id
									AND a.project_type = 'regionalreport'
									AND a.status = 1
								GROUP BY c.id
								ORDER BY project_id");
		} else {
			$list = DB::select("SELECT 	a.project_id, c.name, a.event_id, b.title, SUM(a.amount) AS amount, b.country, b.state, b.zip_code, d.username, b.cost
								FROM 	overall_event_transaction a, " . $type . "_event b, " . $type . " c, users d
								WHERE 	a.event_id = b.id
									AND b.project_id = c.id
									AND a.project_id = c.id
									AND c.user_id = d.id
									AND a.project_type = '" . $type . "'
									AND a.status = 1
								GROUP BY c.id
								ORDER BY project_id");
		}

		$total = 0;
		foreach ($list as $one) :
			$total += $one -> amount;
		endforeach;

		return View::make("/frontend/overall/finance/project_overall_event_details") -> with(array("active" => "financial", "type" => $type, "list" => $list, "total" => $total));
	}

	public function region_event() {
		$list = DB::select("SELECT 	c.id, CASE WHEN d.title IS NULL THEN CONCAT(c.country, ', ', c.state) ELSE d.title END AS title,
								b.id AS event_id, b.title as event_title, sum(a.amount) AS amount, c.country, c.state, e.username, b.cost
							FROM 	region_annual_event_transaction a, region_annual_event b, region_manager c
								LEFT JOIN region_page d ON c.id = d.region_id, users e
							WHERE 	a.event_id = b.id
								AND b.region_id = c.id
								AND c.user_id = e.id
								AND a.status = 1
							GROUP BY c.id, a.event_id");

		$total = 0;
		foreach ($list as $one) :
			$total += $one -> amount;
		endforeach;

		return View::make("/frontend/overall/finance/region_event_details") -> with(array("active" => "financial", "type" => "", "list" => $list, "total" => $total));
	}
	
	public function region_event_overall() {
		$list = DB::select("SELECT 	c.id, CASE WHEN d.title IS NULL THEN CONCAT(c.country, ', ', c.state) ELSE d.title END AS title,
								b.id AS event_id, b.title AS event_title, SUM(a.amount) AS amount, c.country, c.state, e.username, b.cost
							FROM 	overall_event_transaction a, region_annual_event b, region_manager c
								LEFT JOIN region_page d ON d.region_id = c.id, users e
							WHERE 	a.status = 1
								AND a.project_id = c.id
								AND a.event_id = b.id
								AND b.region_id = c.id
								AND c.user_id = e.id
								AND a.project_type = 'annual'
							GROUP BY c.id, a.event_id");

		$total = 0;
		foreach ($list as $one) :
			$total += $one -> amount;
		endforeach;

		return View::make("/frontend/overall/finance/region_event_details") -> with(array("active" => "financial", "type" => "", "list" => $list, "total" => $total));
	}
}
