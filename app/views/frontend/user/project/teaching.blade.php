@extends('layout.user_dashboard')
@section('content')
<script src="/js/jquery.raty.js" language="JavaScript"></script>

<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-12">
		<h2>Teaching Project</h2>
		<ol class="breadcrumb">
			<li>
				<a>Projects</a>
			</li>
			<li>
				<a>Teaching</a>
			</li>
			<li class="active">
				<strong>Project List</strong>
			</li>
		</ol>
	</div>
</div>
<div class="wrapper wrapper-content  animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox">
				<div class="ibox-title" style="display:table;width:100%;text-align: right">
					<h5>List of Project</h5>
					<button type="button" name="new" class="btn btn-primary">
						<i class="fa fa-newspaper-o"></i>&nbsp;New Teaching Project
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
									<th>Project Title</th>
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
								<?php $i = 1; foreach($teaching as $one) :?>
									<tr>
										<td nowrap=""><input type="checkbox" name="chk[]" value="{{$one->id}}"></td>
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
										<td nowrap=""><?php echo $i?></td>
										<td nowrap=""><?php echo $one->name?></td>
										<td nowrap=""><?php echo $one->created_date?></td>
										<td nowrap=""><?php echo $one->updated_date?></td>
										<td nowrap="">
											<a href="/projects/teaching/{{$one->id}}/reviews">
												<div class="rating-mark" data-score="<?php echo $one->review?>"></div>
											</a>
										</td>
										<td nowrap="">
											<?php if($one->total_donation_amount > 0) :?>
												<a href="/projects/teaching/{{$one->id}}/transactions"><i class="fa fa-dollar"></i> {{number_format($one->total_donation_amount, 2)}}</a>
											<?php endif;?>
										</td>
										<td nowrap="" width="100px">
											<?php if($one->event_count > 0) :?>
												<a href="/projects/teaching/{{$one->id}}/events"><span class="label label-info"><?php echo $one->event_count?></span></a>
											<?php else:?>
												<a href="/projects/teaching/{{$one->id}}/events"><span class="label label-default">manage</span></a>
											<?php endif;?>
										</td>
										<td nowrap="" width="100px">
											<?php if($one->follow_count > 0) :?>
												<a href="/projects/teaching/{{$one->id}}/followings"><span class="label label-danger"><?php echo $one->follow_count?></span></a>
											<?php endif;?>
										</td>
										<td nowrap="" width="100px">
											<?php if($one->hug_count > 0) :?>
												<a href="/projects/teaching/{{$one->id}}/hugs"><span class="label label-warning">{{$one->hug_count}}</span></a>
											<?php endif;?>
										</td>
										<td nowrap="" align="right" width="70px">
											<?php if($one->status != -2) : ?>
												<a href="/projects/teaching/edit/{{$one->id}}">
													<button type="button" name="edit-row" class="btn btn-xs btn-outline btn-primary">
														<i class="fa fa-edit"></i>
													</button>
												</a>
											<?php endif;?>
											<button type="button" name="delete-row" data-id="<?php echo $one->id?>" class="btn btn-xs btn-outline btn-danger">
												<i class="fa fa-trash-o"></i>
											</button>
										</td>
									</tr>
								<?php $i ++; endforeach;?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<form name="frmTeaching" method="POST">
	<input type="hidden" name="selected_id" value="">
</form>
@include("elements.project_agreement")
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
				alert("Please select project.");
				return;
			}
			
			if(!confirm("Are you sure you wish delete data?")) {
				return;
			}
			
			$("form[name='frmTeaching'] input[name='selected_id']").val($id);
			$("form[name='frmTeaching']").submit();

		});
		
		$("button[name='delete-row']").click(function() {
			if(!confirm("Are you sure you wish delete data?")) {
				return;
			}
			
			var $id = $(this).attr("data-id");
			$("form[name='frmTeaching'] input[name='selected_id']").val($id);
			$("form[name='frmTeaching']").submit();
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

		$("input[name='chkall']").click(function() {
			if ($(this).is(":checked")) {
				$("input[name='chk[]']").prop("checked", true);
			} else {
				$("input[name='chk[]']").prop("checked", false);
			}
		});
		
		$("button[name='agree']").click(function() {
			location.href = "/projects/teaching/edit";
		});
	}); 
</script>
@stop