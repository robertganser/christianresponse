@extends('layout.overall_dashboard')
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-10">
		<h2>Financial Payments</h2>
	</div>
	<div class="col-lg-2">
		<div class="widget style1 lazur-bg" style="margin-bottom:0px">
            <div class="row">
                <div class="col-xs-4">
                    <i class="fa fa-usd fa-3x"></i>
                </div>
                <div class="col-xs-8 text-right">
                    <h2 class="font-bold">{{number_format($financial->total,2)}}</h2>
                </div>
            </div>
        </div>
	</div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-2">
			<div class="ibox">
				<div class="ibox-title">
					<span class="label label-primary pull-right">Impact</span>
				</div>
				<div class="ibox-content" style="display:table;width:100%">
					<div class="stat-percent font-bold text-navy pull-left">Donation</div>
					<h3 class="no-margins" align="right">
						<a href="/financial/project/impact/donation/details">
							<i class="fa fa-usd"></i>&nbsp;&nbsp;{{number_format($financial->impact_total,2)}}
						</a>
					</h3>
					<div class="hr-line-dashed"></div>
					<div class="stat-percent font-bold text-navy pull-left">Event</div>
					<h3 class="no-margins" align="right">
						<a href="financial/project/impact/event/details">
							<i class="fa fa-usd"></i>&nbsp;&nbsp;{{number_format($financial->impact_event_total,2)}}
						</a>
					</h3>
				</div>
			</div>
		</div>
		<div class="col-lg-2">
			<div class="ibox">
				<div class="ibox-title">
					<span class="label label-info pull-right">Prayer</span>
				</div>
				<div class="ibox-content" style="display:table;width:100%">
					<div class="stat-percent font-bold text-info pull-left">Donation</div>
					<h3 class="no-margins" align="right">
						<a href="/financial/project/prayer/donation/details">
							<i class="fa fa-usd"></i>&nbsp;&nbsp;{{number_format($financial->prayer_total,2)}}
						</a>
					</h3>
					<div class="hr-line-dashed"></div>
					<div class="stat-percent font-bold text-info pull-left">Event</div>
					<h3 class="no-margins" align="right">
						<a href="financial/project/prayer/event/details">
							<i class="fa fa-usd"></i>&nbsp;&nbsp;{{number_format($financial->prayer_event_total,2)}}
						</a>
					</h3>
				</div>
			</div>
		</div>
		<div class="col-lg-2">
			<div class="ibox">
				<div class="ibox-title">
					<span class="label label-danger pull-right">Report</span>
				</div>
				<div class="ibox-content" style="display:table;width:100%">
					<div class="stat-percent font-bold text-danger pull-left">Donation</div>
					<h3 class="no-margins" align="right">
						<a href="/financial/project/report/donation/details">
							<i class="fa fa-usd"></i>&nbsp;&nbsp;{{number_format($financial->report_total,2)}}
						</a>
					</h3>
					<div class="hr-line-dashed"></div>
					<div class="stat-percent font-bold text-danger pull-left">Event</div>
					<h3 class="no-margins" align="right">
						<a href="financial/project/report/event/details">
							<i class="fa fa-usd"></i>&nbsp;&nbsp;{{number_format($financial->report_event_total,2)}}
						</a>
					</h3>
				</div>
			</div>
		</div>
		<div class="col-lg-2">
			<div class="ibox">
				<div class="ibox-title">
					<span class="label label-success pull-right">Teaching</span>
				</div>
				<div class="ibox-content" style="display:table;width:100%">
					<div class="stat-percent font-bold text-success pull-left">Donation</div>
					<h3 class="no-margins" align="right">
						<a href="/financial/project/teaching/donation/details">
							<i class="fa fa-usd"></i>&nbsp;&nbsp;{{number_format($financial->teaching_total,2)}}
						</a>
					</h3>
					<div class="hr-line-dashed"></div>
					<div class="stat-percent font-bold text-success pull-left">Event</div>
					<h3 class="no-margins" align="right">
						<a href="financial/project/teaching/event/details">
							<i class="fa fa-usd"></i>&nbsp;&nbsp;{{number_format($financial->teaching_event_total,2)}}
						</a>
					</h3>
				</div>
			</div>
		</div>
		<div class="col-lg-4">
			<div class="ibox">
				<div class="ibox-title">
					<span class="label label-default pull-right">Region</span>
				</div>
				<div class="ibox-content" style="display:table;width:100%">
					<div class="stat-percent font-bold text-success pull-left">Donation</div>
					<h3 class="no-margins" align="right">
						<a href="/financial/region/donation/details">
							<i class="fa fa-usd"></i>&nbsp;&nbsp;{{number_format($financial->region_total,2)}}
						</a>
					</h3>
					<div class="hr-line-dashed"></div>
					<div class="stat-percent font-bold text-success pull-left">Event</div>
					<h3 class="no-margins" align="right">
						<a href="financial/region/event/details">
							<i class="fa fa-usd"></i>&nbsp;&nbsp;{{number_format($financial->region_event_total,2)}}
						</a>
					</h3>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-10">
		<h2>Overall Earning</h2>
	</div>
	<div class="col-lg-2">
		<div class="widget style1 lazur-bg" style="margin-bottom:0px">
            <div class="row">
                <div class="col-xs-4">
                    <i class="fa fa-usd fa-3x"></i>
                </div>
                <div class="col-xs-8 text-right">
                    <h2 class="font-bold">{{number_format($overall->total,2)}}</h2>
                </div>
            </div>
        </div>
	</div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-2">
			<div class="ibox">
				<div class="ibox-title">
					<span class="label label-primary pull-right">Impact</span>
				</div>
				<div class="ibox-content" style="display:table;width:100%">
					<div class="stat-percent font-bold text-navy pull-left">Donation</div>
					<h3 class="no-margins" align="right">
						<a href="/financial/project/impact/overall/donation/details">
							<i class="fa fa-usd"></i>&nbsp;&nbsp;{{number_format($overall->impact_total,2)}}
						</a>
					</h3>
					<div class="hr-line-dashed"></div>
					<div class="stat-percent font-bold text-navy pull-left">Event</div>
					<h3 class="no-margins" align="right">
						<a href="financial/project/impact/overall/event/details">
							<i class="fa fa-usd"></i>&nbsp;&nbsp;{{number_format($overall->impact_event_total,2)}}
						</a>
					</h3>
				</div>
			</div>
		</div>
		<div class="col-lg-2">
			<div class="ibox">
				<div class="ibox-title">
					<span class="label label-info pull-right">Prayer</span>
				</div>
				<div class="ibox-content" style="display:table;width:100%">
					<div class="stat-percent font-bold text-info pull-left">Donation</div>
					<h3 class="no-margins" align="right">
						<a href="/financial/project/prayer/overall/donation/details">
							<i class="fa fa-usd"></i>&nbsp;&nbsp;{{number_format($overall->prayer_total,2)}}
						</a>
					</h3>
					<div class="hr-line-dashed"></div>
					<div class="stat-percent font-bold text-info pull-left">Event</div>
					<h3 class="no-margins" align="right">
						<a href="financial/project/prayer/overall/event/details">
							<i class="fa fa-usd"></i>&nbsp;&nbsp;{{number_format($overall->prayer_event_total,2)}}
						</a>
					</h3>
				</div>
			</div>
		</div>
		<div class="col-lg-2">
			<div class="ibox">
				<div class="ibox-title">
					<span class="label label-danger pull-right">Report</span>
				</div>
				<div class="ibox-content" style="display:table;width:100%">
					<div class="stat-percent font-bold text-danger pull-left">Donation</div>
					<h3 class="no-margins" align="right">
						<a href="/financial/project/report/overall/donation/details">
							<i class="fa fa-usd"></i>&nbsp;&nbsp;{{number_format($overall->report_total,2)}}
						</a>
					</h3>
					<div class="hr-line-dashed"></div>
					<div class="stat-percent font-bold text-danger pull-left">Event</div>
					<h3 class="no-margins" align="right">
						<a href="financial/project/report/overall/event/details">
							<i class="fa fa-usd"></i>&nbsp;&nbsp;{{number_format($overall->report_event_total,2)}}
						</a>
					</h3>
				</div>
			</div>
		</div>
		<div class="col-lg-2">
			<div class="ibox">
				<div class="ibox-title">
					<span class="label label-success pull-right">Teaching</span>
				</div>
				<div class="ibox-content" style="display:table;width:100%">
					<div class="stat-percent font-bold text-success pull-left">Donation</div>
					<h3 class="no-margins" align="right">
						<a href="/financial/project/teaching/overall/donation/details">
							<i class="fa fa-usd"></i>&nbsp;&nbsp;{{number_format($overall->teaching_total,2)}}
						</a>
					</h3>
					<div class="hr-line-dashed"></div>
					<div class="stat-percent font-bold text-success pull-left">Event</div>
					<h3 class="no-margins" align="right">
						<a href="financial/project/teaching/overall/event/details">
							<i class="fa fa-usd"></i>&nbsp;&nbsp;{{number_format($overall->teaching_event_total,2)}}
						</a>
					</h3>
				</div>
			</div>
		</div>
		<div class="col-lg-4">
			<div class="ibox">
				<div class="ibox-title">
					<span class="label label-default pull-right">Region</span>
				</div>
				<div class="ibox-content" style="display:table;width:100%">
					<div class="stat-percent font-bold text-success pull-left">Donation</div>
					<h3 class="no-margins" align="right">
						<a href="/financial/region/overall/donation/details">
							<i class="fa fa-usd"></i>&nbsp;&nbsp;{{number_format($overall->region_total,2)}}
						</a>
					</h3>
					<div class="hr-line-dashed"></div>
					<div class="stat-percent font-bold text-success pull-left">Event</div>
					<h3 class="no-margins" align="right">
						<a href="/financial/region/overall/event/details">
							<i class="fa fa-usd"></i>&nbsp;&nbsp;{{number_format($overall->region_event_total,2)}}
						</a>
					</h3>
				</div>
			</div>
		</div>
	</div>
</div>
@stop