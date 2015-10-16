@extends('layout.blank')
@section('content')
<div style="min-height: 600px">
	<br>
	<div class="row" style="width:100%">
		<div class="col-md-1"></div>
		<div class="col-md-2">
			<img src="/images/cancel.png" width="100px">
		</div>
		<div class="col-md-7">
			<h2>Donation Cancelled.</h2>
			<h3>Your donation is cancelled.</h3>
			<a href="/project/view/{{$type}}/{{$id}}">Go to Project</a>
		</div>
	</div>
</div>
@stop