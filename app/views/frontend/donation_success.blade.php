@extends('layout.blank')
@section('content')
<div style="min-height: 600px">
	<br>
	<div class="row" style="width:100%">
		<div class="col-md-1"></div>
		<div class="col-md-2">
			<img src="/images/NkRfU31irDKzdiqcyPXx.jpg" width="100px">
		</div>
		<div class="col-md-7">
			<h2>Your donation completed successfully!</h2>
			<h3>Thank you for your support this project.</h3>
			<a href="{{$redirect_url}}">Go to {{$type}}</a>
		</div>
	</div>
</div>
@stop