@extends('layout.user_dashboard')
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-10">
		<h2><?php echo $project_title?> - Hug</h2>
		<ol class="breadcrumb">
			<li>
				<a>Projects</a>
			</li>
			<li>
				<a href="/projects/report"><?php echo $project_title?></a>
			</li>
			<li class="active">
				<strong>Hugs</strong>
			</li>
		</ol>
	</div>
</div>
<div class="wrapper wrapper-content  animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox">
				<div class="ibox-title" style="display:table;width:100%;text-align: right">
					<h5>Hug List</h5>
					<button type="button" name="delete_selected" class="btn btn-danger">
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
									<th>Name</th>
									<th>Email</th>
									<th>Text</th>
									<th>Created Date</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<?php $i = 1; foreach($hugs as $one) :?>
									<tr>
										<td nowrap=""><input type="checkbox" name="chk[]" value="{{$one->id}}"></td>
										<td nowrap="">{{$i}}</td>
										<td nowrap="">{{$one->name}}</td>
										<td nowrap="">{{$one->email}}</td>
										<td nowrap="">{{$one->text}}</td>
										<td nowrap="">{{$one->created_date}}</td>
										<td nowrap="" align="right" width="70px">
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
<form name="frmHugs" method="POST">
	<input type="hidden" name="selected_id" value="">
</form>
<script>
	$(document).ready(function() {
		$("button[name='delete_selected']").click(function() {
			var $id = "";
			$("input[name='chk[]']").each(function() {
				if($(this).is(":checked")) {
					$id += $id == "" ? $(this).val() : "," + $(this).val();
				}
			});
			
			if($id == "") {
				alert("Please select hugs.");
				return;
			}
			
			if(!confirm("Are you sure you wish delete data?")) {
				return;
			}
			
			$("form[name='frmHugs'] input[name='selected_id']").val($id);
			$("form[name='frmHugs']").submit();
		});
		
		$("button[name='delete-row']").click(function() {
			if(!confirm("Are you sure you wish delete data?")) {
				return;
			}
			
			var $id = $(this).attr("data-id");
			$("form[name='frmHugs'] input[name='selected_id']").val($id);
			
			$("form[name='frmHugs']").submit();
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