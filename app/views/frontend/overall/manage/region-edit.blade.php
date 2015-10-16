@extends('layout.overall_dashboard')
@section('content')
<link href="/css/dashboard/plugins/chosen/chosen.css" rel="stylesheet">
<script src="/js/dashboard/plugins/chosen/chosen.jquery.js"></script>

<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-10">
		<h2>Edit Region</h2>
		<ol class="breadcrumb">
			<li>
				<a href="/manage/region">Manage</a>
			</li>
			<li>
				<a href="/manage/region">Region</a>
			</li>
			<li class="active">
				<strong>Edit</strong>
			</li>
		</ol>
	</div>
</div>
<div class="wrapper wrapper-content  animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<?php if($message) :?>
				<div class="row">
					<div class="col-lg-1"></div>
					<div class="col-lg-10">
						<?php echo $message?>
					</div>
					<div class="col-lg-1"></div>
				</div>
			<?php endif;?>
			<div class="ibox">
				<div class="ibox-content">
					<form name="frmRegionEdit" method="post" class="form-horizontal" enctype="multipart/form-data">
						<div class="form-group">
							<label for="country" class="col-sm-4 control-label">Country</label>
							<div class="col-sm-5">
								<input type="text" name="country" id="country" value="{{$country}}" class="form-control" readonly="">
							</div>
						</div>
						<div class="form-group">
							<label for="state" class="col-sm-4 control-label">State</label>
							<div class="col-sm-5">
								<input type="text" name="state" id="state" value="{{$state}}" class="form-control" readonly="">
							</div>
						</div>
						<div class="form-group">
							<label for="state" class="col-sm-4 control-label">Show Days</label>
							<div class="col-sm-5">
								<div class="input-group">
									<input type="text" name="show_days" id="show_days" value="{{$show_days}}" class="form-control"> 
									<span class="input-group-addon">Days</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="help_video" class="col-sm-4 control-label">Help Video</label>
							<div class="col-sm-5">
								<div class="input-group">
									<span class="input-group-addon" style="background: #f0f0f0">https://www.youtube.com/watch?v=</span>
									<input type="text" name="help_video" id="help_video" value="{{$help_video}}" class="form-control">
								</div>
								<span class="help-block m-b-none">Only uploads videos from Youtube. Copy id from URL which is everything after the = sign in the URL</span>
							</div>
						</div>
						<div class="form-group">
							<label for="manager" class="col-sm-4 control-label">Region Manager</label>
							<div class="col-sm-5">
								<select name="manager" class="form-control chosen-select" data-placeholder="Choose a Region Administrator...">
									<option value="0"></option>
									<?php foreach($users as $one) :?>
										<option value="{{$one->id}}" {{$manager == $one->id ? "selected" : ""}}>{{$one->first_name}} {{$one->last_name}}</option>
									<?php endforeach;?>
								</select>
							</div>
						</div>
						<div class="hr-line-dashed"></div>
						<div class="form-group">
							<div class="col-sm-4 col-sm-offset-4">
								<button class="btn btn-primary" type="submit" name="save">
									Save changes
								</button>
								<button class="btn btn-default" type="button" name="cancel">
									Cancel
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function() {
		$(".chosen-select").chosen();
		
		$("button[name='cancel']").click(function() {
			location.href = "/manages/region";
		});
	}); 
</script>
@stop