@extends('layout.overall_dashboard')
@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-10">
		<h2>Manage User Permission</h2>
	</div>
	<div class="col-lg-2">
		<div class="widget style1 lazur-bg" style="margin-bottom:0px">
			<div class="row">
				<div class="col-xs-4">
					<i class="fa fa-user fa-4x"></i>
				</div>
				<div class="col-xs-8 text-right">
					<span> Total Users </span>
					<h2 class="font-bold">{{count($users)}}</h2>
				</div>
			</div>
		</div>
	</div>
</div>
<form name="frmUserPermission" method="POST" action="">
	<div class="wrapper wrapper-content  animated fadeInRight">
		<div class="row">
			<div class="col-lg-12">
				<div class="ibox">
					<div class="ibox-title" style="display:table;width:100%;text-align: right">
						<div class="row">
							<div class="col-lg-8"><h5>List of Users</h5></div>
							<div class="col-lg-2">
								<select name="permission" class="form-control">
									<option value="" {{$permission == "" ? "selected" : ""}}>Select...</option>
									<option value="region" {{$permission == "region" ? "selected" : ""}}>Region Administrator</option>
									<option value="general" {{$permission == "general" ? "selected" : ""}}>General Administrator</option>
									<option value="default" {{$permission == "default" ? "selected" : ""}}>Default User</option>
								</select>
							</div>
							<div class="col-lg-2">
								<button type="submit" name="new" class="btn btn-primary">
									<i class="fa fa-edit"></i>&nbsp;Save changes
								</button>
							</div>
						</div>
					</div>
					<div class="ibox-content">
						<div class="table-responsive m-t">
							<table class="table">
								<thead>
									<tr>
										<th>No.</th>
										<th>First Name</th>
										<th>Last Name</th>
										<th>Gender</th>
										<th>Date of Birth</th>
										<th>Email</th>
										<th>Username</th>
										<th>Permission</th>
									</tr>
								</thead>
								<tbody>
									<?php $i = 1; foreach($users as $one) :
									?>
									<tr>
										<td nowrap="">{{$i}}</td>
										<td nowrap="">{{$one->first_name}}</td>
										<td nowrap="">{{$one->last_name}}</td>
										<td nowrap="">{{$one->gender == "1" ? "M" : "F"}}</td>
										<td nowrap="">{{$one->birthday}}</td>
										<td nowrap="">{{$one->email}}</td>
										<td nowrap="">{{$one->username}}</td>
										<td nowrap="">
											<select name="permission[{{$one->id}}]" class="form-control">
												<option value="-2" {{$one->permission == -2 ? "selected" : ""}}>Region Administrator</option>
												<option value="-3" {{$one->permission == -3 ? "selected" : ""}}>General Administrator</option>
												<option value="100" {{$one->permission == 100 ? "selected" : ""}}>Default User</option>
											</select>
										</td>
									</tr>
									<?php $i++;
										endforeach;
									?>
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
		$("select[name='permission']").change(function() {
			var $permission = $(this).val();
			
			if($permission == 0) {
				location.href = "/manages/user-permission";
			} else {
				location.href = "/manages/user-permission/" + $permission;
			}
		});
	});
</script>
@stop
