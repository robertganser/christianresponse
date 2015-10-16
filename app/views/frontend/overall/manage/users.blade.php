@extends('layout.overall_dashboard')
@section('content')
<script src="/js/jquery.raty.js" language="JavaScript"></script>

<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-12">
		<h2>Manage Users</h2>
		<ol class="breadcrumb">
			<li>
				<a>Manage</a>
			</li>
			<li>
				<a>Users</a>
			</li>
		</ol>
	</div>
</div>
<div class="wrapper wrapper-content  animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox">
				<div class="ibox-title" style="display:table;width:100%;text-align: right">
					<div class="col-lg-2">
						<input type="text" name="name" placeholder="Name:" class="form-control">
					</div>
					<div class="col-lg-1">
						<select name="gender" class="form-control">
							<option value="0">-</option>
							<option value="1">Male</option>
							<option value="2">Female</option>
						</select>
					</div>
					<div class="col-lg-2">
						<select name="permission" class="form-control">
							<option value="0">-</option>
							<option value="-2">Region Administrator</option>
							<option value="100">Default User</option>
						</select>
					</div>
					<div class="col-lg-3" align="left">
						<button type="button" class="btn btn-primary" name="search_user">Search User</button>
					</div>
				</div>
				<div class="ibox-content">
					<div class="table-responsive m-t">
						<table class="table" id="for_user">
							<thead>
								<tr>
									<th></th>
									<th>No.</th>
									<th>Name</th>
									<th>Gender</th>
									<th>Date of Birth</th>
									<th>Username</th>
									<th>Email Address</th>
									<th>Permission</th>
									<th>Last Logged In</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<?php $i = 1; foreach($users as $one) :?>
									<tr {{$one->status==1?"":"style='background:#f2dede'"}}>
										<td nowrap="">
											<?php if($one->status == -2) :?>
												<span class="label label-danger">blocked</span>
											<?php elseif($one->status == 1) :?>
												<span class="label label-default">approved</span>
											<?php elseif($one->status == -99) :?>
												<span class="label label-warning">waiting</span>
											<?php endif;?>
										</td>
										<td nowrap="">{{$i}}</td>
										<td nowrap="">{{$one->first_name}} {{$one->last_name}}</td>
										<td nowrap="">{{$one->gender == 1 ? "M" : "F"}}</td>
										<td nowrap="">{{$one->birthday}}</td>
										<td nowrap="">{{$one->username}}</td>
										<td nowrap="">{{$one->email}}</td>
										<td nowrap="">
											<?php if($one->permission == -2) :?>
												Region Administrator
											<?php elseif($one->permission == -3) :?>
												General Administrator
											<?php elseif($one->permission == 100) :?>
												Default
											<?php endif;?>
										</td>
										<td nowrap="">{{$one->last_login_date=="0000-00-00 00:00:00" ? "" : $one->last_login_date}}</td>
										<td nowrap="" width="80">
											<a href="/manages/users/edit/{{$one->id}}">
												<button type="button" name="edit-row" class="btn btn-xs btn-outline btn-primary">
													<i class="fa fa-edit"></i>&nbsp;&nbsp;Edit User
												</button>
											</a>
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
<script>
	$(document).ready(function() {
		$("button[name='search_user']").click(function() {
			$("body").mask("");
			
			$.post("/manages/users/search/o", {
				name : $("input[name='name']").val(),
				gender : $("select[name='gender']").val(),
				permission : $("select[name='permission']").val()
			}, function(response) {
				datas = response.data;
				$("#for_user tbody").html("");
				
				for(i = 0; i < datas.length; i ++) {
					html = '<tr '+(datas[i].status==1?'':'style="background:#f2dede"')+'>';
					
					if(datas[i].status == -2) {
						html += '<td nowrap=""><span class="label label-danger">blocked</span></td>';
					} else if(datas[i].status == 1) {
						html += '<td nowrap=""><span class="label label-default">approved</span></td>';
					} else if(datas[i].status == -99) {
						html += '<td nowrap=""><span class="label label-warning">waiting</span></td>';
					}
					
					html += '	<td nowrap="">'+(i + 1)+'</td>' + 
							'	<td nowrap="">'+datas[i].first_name+' '+datas[i].last_name+'</td>' + 
							'	<td nowrap="">'+(datas[i].gender==1?'M':'F')+'</td>' + 
							'	<td nowrap="">'+(datas[i].birthday!=null?datas[i].birthday:'')+'</td>' + 
							'	<td nowrap="">'+datas[i].username+'</td>' + 
							'	<td nowrap="">'+datas[i].email+'</td>' + 
							'	<td nowrap="">';
					if(datas[i].permission == -2) {
						html += 'Region Administrator';
					} else if(datas[i].permission == 100) {
						html += 'Default';
					} else if(datas[i].permission == -3) {
						html += 'General Administrator';
					}
					
					html += '	</td>' + 
							'	<td nowrap="">'+(datas[i].last_login_date=='0000-00-00 00:00:00'||datas[i].last_login_date==null ? '' : datas[i].last_login_date)+'</td>' + 
							'	<td nowrap="" width="80">' + 
							'		<a href="/manages/users/edit/'+datas[i].id+'">' + 
							'			<button type="button" name="edit-row" class="btn btn-xs btn-outline btn-primary">' + 
							'				<i class="fa fa-edit"></i>&nbsp;&nbsp;Edit User' + 
							'			</button>' + 
							'		</a>' + 
							'	</td>' + 
							'</tr>';
					$("#for_user tbody").append(html);
				}
				
				$("body").unmask();
			}, "json");
		});
	});
</script>
@stop