@extends('layout.general_dashboard')
@section('content')
<script src="/js/jquery.raty.js" language="JavaScript"></script>

<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-12">
		<h2>Project - Report</h2>
		<ol class="breadcrumb">
			<li>
				<a>Projects</a>
			</li>
			<li>
				<a>Report</a>
			</li>
			<li class="active">
				<strong>Report List</strong>
			</li>
		</ol>
	</div>
</div>
<div class="wrapper wrapper-content  animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox">
				<div class="ibox-title" style="display:table;width:100%;text-align: right">
					<h5>List of Report</h5>
					<button type="button" name="new" class="btn btn-primary">
						<i class="fa fa-newspaper-o"></i>&nbsp;New Report
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
									<th></th>
									<th>No.</th>
									<th>Type</th>
									<th>Report Name</th>
									<th>Created Date</th>
									<th>Updated Date</th>
									<th>&nbsp;</th>
									<th>Earning</th>
									<th>Events</th>
									<th>Followings</th>
									<th>Hugs</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<?php $i = 1; foreach($reports as $one) :?>
									<tr>
										<td><input type="checkbox" name="chk[]" value="{{$one->id}}" data-type="{{$one->project_type}}"></td>
										<td nowrap="">
											<?php if($one->is_allocated != 1) : ?>
												<span class="label label-danger">not allocated</span>
											<?php endif;?>
											<?php if($one->status == 1) : ?>
												<span class="label label-success">active</span>
											<?php elseif($one->status == 100) : ?>
												<small class="text-muted">- waiting -</small>
											<?php elseif($one->status == -2) : ?>
												<span class="label label-danger">suspend</span>
											<?php endif; ?>
										</td>
										<td nowrap="">{{$i}}</td>
										<td nowrap="">
											{{$one->project_type == 1 ? "<span class='label label-info'>National</span>" : "<span class='label label-warning'>Regional</span>"}}
										</td>
										<td nowrap="">{{$one->name}}</td>
										<td nowrap="">{{$one->created_date}}</td>
										<td nowrap="">{{$one->updated_date}}</td>
										<td nowrap=""><div class="rating-mark" data-score="{{$one->review}}"></div></td>
										<td nowrap="">
											<?php if($one->total_donation_amount > 0) :?>
												<a href="/{{$active=='manages'?'manages/':''}}projects/report/{{$one->project_type == 1 ? 'nationalreport' : 'regionalreport'}}/{{$one->id}}/transactions"><i class="fa fa-dollar"></i> {{number_format($one->total_donation_amount, 2)}}</a>
											<?php endif;?>
										</td>
										<td nowrap="" width="100px">
											<?php if($one->event_count > 0) :?>
												<a href="/{{$active=='manages'?'manages/':''}}projects/report/{{$one->project_type == 1 ? 'nationalreport' : 'regionalreport'}}/{{$one->id}}/events"><span class="label label-info">{{$one->event_count}}</span></a>
											<?php else:?>
												<a href="/{{$active=='manages'?'manages/':''}}projects/report/{{$one->project_type == 1 ? 'nationalreport' : 'regionalreport'}}/{{$one->id}}/events"><span class="label label-default">manage</span></a>
											<?php endif;?>
										</td>
										<td nowrap="" width="100px">
											<?php if($one->follow_count > 0) :?>
												<a href="/{{$active=='manages'?'manages/':''}}projects/report/{{$one->project_type == 1 ? 'nationalreport' : 'regionalreport'}}/{{$one->id}}/followings"><span class="label label-danger">{{$one->follow_count}}</span></a>
											<?php endif;?>
										</td>
										<td nowrap="" width="100px">
											<?php if($one->hug_count > 0) :?>
												<a href="/{{$active=='manages'?'manages/':''}}projects/report/{{$one->project_type == 1 ? 'nationalreport' : 'regionalreport'}}/{{$one->id}}/hugs"><span class="label label-warning">{{$one->hug_count}}</span></a>
											<?php endif;?>
										</td>
										<td nowrap="" align="right" width="70px">
											<?php if($one->status != -2) : ?>
												<a href="/{{$active=='manages'?'manages/':''}}projects/report/{{$one->project_type == 1 ? 'nationalreport' : 'regionalreport'}}/edit/{{$one->id}}">
													<button type="button" name="edit-row" data-id="{{$one->id}}" data-type="{{$one->project_type}}" class="btn btn-xs btn-outline btn-primary">
														<i class="fa fa-edit"></i>
													</button>
												</a>
											<?php endif;?>
											<button type="button" name="delete-row" data-id="{{$one->id}}" data-type="{{$one->project_type}}" class="btn btn-xs btn-outline btn-danger">
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
<form name="frmReport" method="POST">
	<input type="hidden" name="nationalreport_selected_id" value="">
	<input type="hidden" name="regionalreport_selected_id" value="">
</form>
@include("elements.project_agreement")
<div class="modal inmodal fade" id="modal-choice-report" tabindex="-1" role="dialog"  aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
				</button>
				<h4 class="modal-title">Choice between National or Regional</h4>
			</div>
			<div class="modal-body">
				<p align="center">
					<h3 align="center">Depending on what they choose will determine what form they get</h3>
				</p>
			</div>
			<div class="modal-footer" align="center" style="text-align: center">
				<a href="/{{$active=='manages'?'manages/':''}}projects/report/nationalreport/edit" class="btn btn-info" name="choice_nationalreport">
					National Report
				</a>
				<a href="/{{$active=='manages'?'manages/':''}}projects/report/regionalreport/edit" class="btn btn-warning" name="choice_regionalreport">
					Regional Report
				</a>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function() {
		$("button[name='delete']").click(function() {
			var $nationalreport_id = "";
			var $regionalreport_id = "";
			
			$("input[name='chk[]']").each(function() {
				if($(this).is(":checked")) {
					if($(this).attr("data-type") == "1") {
						$nationalreport_id += $nationalreport_id == "" ? $(this).val() : "," + $(this).val();
					} else {
						$regionalreport_id += $regionalreport_id == "" ? $(this).val() : "," + $(this).val();
					}
				}
			});
			
			if($nationalreport_id == "" && $regionalreport_id == "") {
				alert("Please select project.");
				return;
			}
			
			if(!confirm("Are you sure you wish delete data?")) {
				return;
			}
			
			$("form[name='frmReport'] input[name='nationalreport_selected_id']").val($nationalreport_id);
			$("form[name='frmReport'] input[name='regionalreport_selected_id']").val($regionalreport_id);
			$("form[name='frmReport']").submit();

		});
		
		$("button[name='delete-row']").click(function() {
			if(!confirm("Are you sure you wish delete data?")) {
				return;
			}
			
			var $id = $(this).attr("data-id");
			var $type = $(this).attr("data-type");
			
			if($type == 1) {
				$("form[name='frmReport'] input[name='nationalreport_selected_id']").val($id);
			} else {
				$("form[name='frmReport'] input[name='regionalreport_selected_id']").val($id);
			}
			
			$("form[name='frmReport']").submit();
		});
		
		$(".rating-mark").each(function() {
			score = $(this).attr("data-score");
			$(this).raty({
				readOnly : true,
				score : score
			});
		});

		$("button[name='new']").click(function() {
			$("#modal-project-agreement").modal();
		});

		$("button[name='agree']").click(function() {
			//location.href = "/projects/report/edit";
			$("#modal-project-agreement").modal("hide");
			$("#modal-choice-report").modal();
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