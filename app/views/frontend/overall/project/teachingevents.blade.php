@extends('layout.overall_dashboard')
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-10">
		<h2><?php echo $project_title?> - Event</h2>
		<ol class="breadcrumb">
			<li>
				<a>Projects</a>
			</li>
			<li>
				<a href="/{{$active=='manages'?'manages/':''}}projects/teaching"><?php echo $project_title?></a>
			</li>
			<li class="active">
				<strong>Event List</strong>
			</li>
		</ol>
		<br>
		<span class="label label-info">Teaching Project</span>
	</div>
	<div class="col-lg-2">
		<div class="widget style1 lazur-bg" style="margin-bottom:0px">
            <div class="row">
                <div class="col-xs-4">
                    <i class="fa fa-envelope-o fa-3x"></i>
                </div>
                <div class="col-xs-8 text-right">
                    <span> Total Events </span>
                    <h2 class="font-bold">{{count($events)}}</h2>
                </div>
            </div>
        </div>
	</div>
</div>
<div class="wrapper wrapper-content  animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox">
				<div class="ibox-title" style="display:table;width:100%;text-align: right">
					<h5>Event List</h5>
					<button type="button" name="new" class="btn btn-primary">
						<i class="fa fa-newspaper-o"></i>&nbsp;New Event
					</button>
					&nbsp;
					<button type="button" name="delete" class="btn btn-danger">
						<i class="fa fa-trash-o"></i>&nbsp;Delete Selected
					</button>
				</div>
				<div class="ibox-content">
					<div class="table-responsive m-t">
						<table class="table">
							<thead>
								<tr>
									<th><input type="checkbox" name="chkall"></th>
									<th>No.</th>
									<th>Event Title</th>
									<th>Event Location</th>
									<th>Event Date & Time</th>
									<th>Event Cost</th>
									<th>Joined</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<?php $i = 1; foreach($events as $one) :?>
									<tr>
										<td nowrap=""><input type="checkbox" name="chk[]" value="{{$one->id}}"></td>
										<td nowrap="">{{$i}}</td>
										<td nowrap="">{{$one->title}}</td>
										<td nowrap="">{{$one->address}}, {{$one->city}}, {{$one->state}} {{$one->zip_code}}, {{$one->country}}</td>
										<td nowrap="">{{date("F d, Y h:i A", strtotime($one->event_date))}}</td>
										<td nowrap="">$ {{$one->cost}}</td>
										<td nowrap=""><a href="/{{$active=='manages'?'manages/':''}}projects/teaching/{{$project_id}}/event/{{$one->id}}/joins"><span class="label label-info">{{$one->joined_count}}</span></a></td>
										<td nowrap="" align="right" width="70px">
											<a href="/{{$active=='manages'?'manages/':''}}projects/teaching/{{$project_id}}/events/edit/{{$one->id}}">
												<button type="button" name="edit-row" class="btn btn-xs btn-outline btn-primary">
													<i class="fa fa-edit"></i>
												</button>
											</a>
											<button type="button" name="delete-row" data-id="{{$one->id}}" class="btn btn-xs btn-outline btn-danger">
												<i class="fa fa-trash-o"></i>
											</button>
										</td>
									</tr>
								<?php $i ++; endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<form name="frmTeachingEvents" method="POST">
	<input type="hidden" name="selected_id" value="">
</form>
@include("elements.event_agreement")
<script>
	$(document).ready(function() {
		$("button[name='delete']").click(function() {
			var $id = "";
			$("input[name='chk[]']").each(function() {
				if($(this).is(":checked")) {
					$id += $id == "" ? $(this).val() : "," + $(this).val();
				}
			});
			
			if($id == "") {
				alert("Please select events.");
				return;
			}
			
			if(!confirm("Are you sure you wish delete data?")) {
				return;
			}
			
			$("form[name='frmTeachingEvents'] input[name='selected_id']").val($id);
			$("form[name='frmTeachingEvents']").submit();

		});
		
		$("button[name='delete-row']").click(function() {
			if(!confirm("Are you sure you wish delete data?")) {
				return;
			}
			
			var $id = $(this).attr("data-id");
			$("form[name='frmTeachingEvents'] input[name='selected_id']").val($id);
			$("form[name='frmTeachingEvents']").submit();
		});
		
		$("button[name='new']").click(function() {
			$("#modal-project-agreement").modal();
		});

		$("button[name='agree']").click(function() {
			location.href = "/{{$active=='manages'?'manages/':''}}projects/teaching/{{$project_id}}/events/edit";
		});

		$("input[name='chkall']").click(function() {
			if ($(this).is(":checked")) {
				$("input[name='chk[]']").prop("checked", true);
			} else {
				$("input[name='chk[]']").prop("checked", false);
			}
		});
	});
</script>
@stop