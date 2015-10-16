@extends('layout.region_dashboard')
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-10">
		<h2>Access Denied</h2>
	</div>
</div>
<div class="wrapper wrapper-content  animated fadeInRight">
	<div class="middle-box text-center animated fadeInDown">
		<h1>404</h1>
		<h3 class="font-bold">Page Not Found</h3>

		<div class="error-desc">
			Sorry, but the page you are looking for has note been found.<br> 
			You have no permission to access this page.
			<p>
				<a href="/dashboard" class="btn btn-primary m-t">Dashboard</a>
			</p>
		</div>
	</div>
</div>
@stop