<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-10">
		<h2>Related Region</h2>
	</div>
	<div class="col-lg-2">
		<h2>
		<select name="region_page" class="form-control">
			<option value="0" {{$region_id == 0 ? "selected" : ""}}></option>
			<?php foreach($regions as $one) :?>
				<option value="{{$one->region_id}}" {{$region_id == $one->region_id ? "selected" : ""}}>{{$one->title}}</option>
			<?php endforeach; ?>
		</select></h2>
	</div>
</div>
<div class="wrapper wrapper-content  animated fadeInRight">
	<div class="middle-box text-center animated fadeInDown">
		<h1 style="font-size:100px">Your Region</h1>
		<div class="error-desc">
			We hope to have this page operational for your region having, introductory video, regional report and events in your region when more people in your region join RESPONSE
			<p>
				<a href="/dashboard" class="btn btn-primary m-t">Dashboard</a>
			</p>
		</div>
	</div>
</div>
<script>
$(document).ready(function() {
	$("select[name='region_page']").change(function() {
		var $region_id = $(this).val();

		if ($region_id == 0) {
			location.href = "/region/{{$curr_date}}";
		} else {
			if ("{{$curr_date}}" == "") {
				location.href = "/region/{{date('Y-m-d')}}/" + $region_id;
			} else {
				location.href = "/region/{{$curr_date}}/" + $region_id;
			}
		}
	});

});
</script>