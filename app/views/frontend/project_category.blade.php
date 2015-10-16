@extends('layout.blank')
@section('content')

<h2 style="text-align: center">Our Projects Type</h2>
<div class="row">
	<div class="col-md-4"></div>
	<div class="col-md-1">
		<a href="/project/impact"><h3 align="center" style="color: #333333"><span class="label label-danger">Impact</span></h3></a>
	</div>
	<div class="col-md-1">
		<a href="/project/prayer"><h3 align="center" style="color: #333333"><span class="label label-danger">Prayer</span></h3></a>
	</div>
	<div class="col-md-1">
		<a href="/project/report"><h3 align="center" style="color: #333333"><span class="label label-danger">Report</span></h3></a>
	</div>
	<div class="col-md-1">
		<a href="/project/teaching"><h3 align="center" style="color: #333333"><span class="label label-danger">Teaching</span></h3></a>
	</div>
	<div class="col-md-4"></div>
</div>
<br>
<br>
<div class="row">
	<div class="col-md-6">
		<div class="project_category_img">
			<img src="/res/project/category_1.png">
		</div>
	</div>
	<div class="col-md-6">
		<h2>PRAYER</h2>
		<p style="font-size:150%">
			24/7 Prayer mapping in your region National Day of Prayer & Fasting
		</p>
		<a class="category_button" href="/project/prayer" style="width: 100%">Click me to browse all projects</a>
	</div>
</div>
<hr style="border:1px solid #f0f0f0;width:90%;margin:60px auto">
<div class="row">
	<div class="col-md-6" style="margin-bottom: 50px">
		<h2>REPORT</h2>
		<p style="font-size:150%">
			Making a Report on a nation/region and host an associated event
		</p>
		<a class="category_button" href="/project/report" style="width: 100%">Click me to browse all projects</a>
	</div>
	<div class="col-md-6">
		<div class="project_category_img">
			<img src="/res/project/category_2.png">
		</div>
	</div>
</div>
<hr style="border:1px solid #f0f0f0;width:90%;margin:60px auto">
<div class="row">
	<div class="col-md-6">
		<div class="project_category_img">
			<img src="/res/project/category_3.png">
		</div>
	</div>
	<div class="col-md-6">
		<h2>IMPACT</h2>
		<p style="font-size:150%">
			Making an Impact. Meeting needs in the community
		</p>
		<a class="category_button" href="/project/impact" style="width: 100%">Click me to browse all projects</a>
	</div>
</div>
<hr style="border:1px solid #f0f0f0;width:90%;margin:60px auto">
<div class="row">
	<div class="col-md-6" style="margin-bottom: 50px">
		<h2>TEACHING</h2>
		<p style="font-size:150%">
			Response Bible Diploma
		</p>
		<a class="category_button" href="/project/teaching" style="width: 100%">Click me to browse all projects</a>
	</div>
	<div class="col-md-6">
		<div class="project_category_img">
			<img src="/res/project/category_4.png">
		</div>
	</div>
</div>
@stop