@extends('layout.overall_dashboard')
@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-10">
		<h2>Manage Contacts</h2>
	</div>
	<div class="col-lg-2">
		<div class="widget style1 lazur-bg" style="margin-bottom:0px">
			<div class="row">
				<div class="col-xs-4">
					<i class="fa fa-user fa-4x"></i>
				</div>
				<div class="col-xs-8 text-right">
					<span> Total Contacts </span>
					<h2 class="font-bold">{{count($contacts)}}</h2>
				</div>
			</div>
		</div>
	</div>
</div>
<form name="frmContacts" method="post" action="">
	<div class="wrapper wrapper-content  animated fadeInRight">
		<div class="row">
			<div class="col-lg-12">
				<div class="ibox">
					<div class="ibox-title" style="display:table;width:100%">
						<div class="col-lg-6">
							<h5>List of regions</h5>
						</div>
						<div class="col-lg-6" align="right">
							<button type="button" name="delete" class="btn btn-danger" style="display:none">Delete Selected</button>
						</div>
					</div>
					<div class="ibox-content">
						<div class="table-responsive m-t">
							<table class="table">
								<thead>
									<tr>
										<th><input type="checkbox" name="chkall"></th>
										<th>Name</th>
										<th>Email Address</th>
										<th>Submitted Date</th>
										<th></th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<?php $i = 1; foreach($contacts as $one) : ?>
									<tr>
										<td nowrap=""><input type="checkbox" name="chk[]" value="{{$one->id}}"></td>
										<td nowrap="">{{$one->name}}</td>
										<td nowrap="">{{$one->email}}</td>
										<td nowrap="">{{$one->created_date}}</td>
										<td nowrap="">
											{{$one->status == 100 ? "<span class='label label-danger'>waiting</span>" : ""}}
										</td>
										<td nowrap="" width="50px">
											<button type="button" name="edit-row" class="btn btn-xs btn-outline btn-primary" onclick="javascript:location.href='/contacts/view/{{$one->id}}'">
												<i class="fa fa-edit"></i> View & Respond
											</button>
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
	$("input[name='chkall']").click(function() {
		if ($(this).is(":checked")) {
			$("input[name='chk[]']").prop("checked", true);
			$("button[name='delete']").show();
		} else {
			$("input[name='chk[]']").prop("checked", false);
			$("button[name='delete']").hide();
		}
	});
	
	$("input[name='chk[]']").click(function() {
		var $checked = 0;
		$("input[name='chk[]']:checked").each(function() {
			$checked ++;
		});
		
		if($checked > 0) {
			$("button[name='delete']").show();
		} else {
			$("button[name='delete']").hide();
		}
	});
	
	$("button[name='delete']").click(function() {
		if(!confirm("Are you sure you wish delete data?")) {
			return;
		}
		
		$("form[name='frmContacts']").submit();
	});
});
</script>
@stop
