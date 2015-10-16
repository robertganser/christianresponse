@extends('layout.overall_dashboard')
@section('content')
<link href="/css/dashboard/plugins/chosen/chosen.css" rel="stylesheet">
<script src="/js/dashboard/plugins/chosen/chosen.jquery.js"></script>

<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-10">
		<h2>Manage Region</h2>
	</div>
	<div class="col-lg-2">
		<div class="widget style1 lazur-bg" style="margin-bottom:0px">
			<div class="row">
				<div class="col-xs-4">
					<i class="fa fa-globe fa-4x"></i>
				</div>
				<div class="col-xs-8 text-right">
					<span> Total Regions </span>
					<h2 class="font-bold">{{count($regions)}}</h2>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal inmodal" id="modal_video" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content animated flipInY">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
				</button>
				<div class="row">
					<div class="col-lg-4"></div>
					<div class="col-lg-4" id="event_thumb">
						<i class="fa fa-video-camera modal-icon"></i>
					</div>
					<div class="col-lg-4"></div>
				</div>
				<h4 class="modal-title">Help Video</h4>
			</div>
			<div class="modal-body">
				<p align="center"></p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-white" data-dismiss="modal">
					Close
				</button>
			</div>
		</div>
	</div>
</div>
<form name="frmUserPermission" method="POST" action="">
	<div class="wrapper wrapper-content  animated fadeInRight">
		<div class="row">
			<div class="col-lg-12">
				<div class="ibox">
					<div class="ibox-title" style="display:table;width:100%">
						<div class="col-lg-9">
							<h5>List of regions</h5>
						</div>
						<div class="col-lg-3">
							<select name="country" class="form-control chosen-select" id="country" data-placeholder="Choose a Country...">
								<option value="" {{$country == "" ? "selected" : ""}}>-</option>
								<?php foreach($countries as $one) :?>
									<option value="{{$one->country}}" {{$country == $one->country ? "selected" : ""}}>{{$one->country}}</option>
								<?php endforeach;?>
							</select>
						</div>
					</div>
					<div class="ibox-content">
						<div class="table-responsive m-t">
							<table class="table">
								<thead>
									<tr>
										<th>No.</th>
										<th>Country</th>
										<th>State</th>
										<th>Show Days</th>
										<th>Help Video</th>
										<th>Region Manager</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<?php $i = 1; foreach($regions as $one) : ?>
									<tr>
										<td nowrap="">{{$i}}</td>
										<td nowrap="">{{$one->country}}</td>
										<td nowrap="">{{$one->state}}</td>
										<td nowrap="">{{$one->show_days}}</td>
										<td nowrap="">
											<?php if($one->help_video != "") :?>
												<button type="button" name="show_video" class="btn btn-white" data-code="{{$one->help_video}}"><i class="fa fa-video-camera"></i></button>
											<?php endif;?>
										</td>
										<td nowrap="">{{$one->username}}</td>
										<td nowrap="" width="50px">
											<a href="/manages/region/edit/{{$one->country}}/{{$one->state}}/{{$one->id}}">
												<button type="button" name="edit-row" class="btn btn-xs btn-outline btn-primary">
													<i class="fa fa-edit"></i> Edit Region
												</button>
											</a>
										</td>
									</tr>
									<?php $i++; endforeach; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
<script>
$(document).ready(function() {
	$(".chosen-select").chosen();
	
	$("select[name='country']").change(function() {
		var $country = $(this).val();
		location.href = "/manages/region/" + $country;
	});
	
	$("button[name='show_video']").click(function() {
		var $code = $(this).attr("data-code");
		
		$("#modal_video").modal();
		$("#modal_video .modal-body p").html('<embed width="100%" height="300" src="http://www.youtube.com/v/'+$code+'"></embed>');
	});
	
	$('#modal_video').on('hidden.bs.modal', function () {
	    $("#modal_video .modal-body p").html("");
	});
});
</script>
@stop
