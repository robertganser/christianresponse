@extends('layout.general_dashboard')
@section('content')

<link href="/css/dashboard/plugins/chosen/chosen.css" rel="stylesheet">
<link href="/css/loadmask.css" rel="stylesheet" type="text/css" />

<script src="/js/dashboard/plugins/chosen/chosen.jquery.js"></script>
<script type="text/javascript" src="/js/jquery-loadmask.js"></script>

<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-6">
		<h2>General Administrator Dashboard</h2>
	</div>
</div>

<div class="wrapper wrapper-content  animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox">
				<div class="ibox-title" style="display:table;width:100%">
					<div class="row">
						<div class="col-lg-3">
							<input type="text" name="project_name" placeholder="Project Name: " value="" class="form-control">
						</div>
						<div class="col-lg-3">
							<select name="owner_id" id="owner_id" class="form-control chosen-select" data-placeholder="Choose a Facilitator...">
								<option value="0">...</option>
								<?php foreach($owners as $one) :?>
									<option value="{{$one->id}}">{{$one->first_name}} {{$one->last_name}}</option>
								<?php endforeach;?>
							</select>
						</div>
						<div class="col-lg-3">
							<button type="button" name="search_project" class="btn btn-primary">Search Project</button>
						</div>
						<div class="col-lg-3">
							<div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                            </div>
						</div>
					</div>
				</div>
				<div class="ibox-content">
					<div class="table-responsive m-t">
						<table class="table" id="for_project">
							<thead>
								<tr>
									<th></th>
									<th>Project Name</th>
									<th>Location</th>
									<th>Owner</th>
									<th>Created Date</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="wrapper wrapper-content  animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox">
				<div class="ibox-title" style="display:table;width:100%">
					<div class="row">
						<div class="col-lg-3">
							<input type="text" name="facilitator_name" placeholder="Facilitator Name: " value="" class="form-control">
						</div>
						<div class="col-lg-3">
							<button type="button" name="search_facilitator" class="btn btn-primary">Search Facilitator</button>
						</div>
						<div class="col-lg-6">
							<div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                            </div>
						</div>
					</div>
				</div>
				<div class="ibox-content">
					<div class="table-responsive m-t">
						<table class="table" id="for_facilitator">
							<thead>
								<tr>
									<th>Facilitator Name</th>
									<th>Location</th>
									<th>Username</th>
									<th>Email Address</th>
									<th>Permission</th>
									<th>Created Date</th>
								</tr>
							</thead>
							<tbody>
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
		$(".chosen-select").chosen();
		
		$("button[name='search_project']").click(function() {
			$("body").mask("");
			
			$.post("/dashboard/search/project", {
				project_name : $("input[name='project_name']").val(),
				owner_id : $("select[name='owner_id']").val()
			}, function(response) {
				datas = response.data;
				$("#for_project tbody").html("");
				for(i = 0; i < datas.length; i ++) {
					type = '';
					link = '';
					
					if(datas[i].project_type == 'prayer') {
						type = '<span class="label label-primary" style="width:80px;float:left;font-size:12px">- prayer -</span>';
						link = 'manages/projects/prayer/edit/' + datas[i].id;
					} else if(datas[i].project_type == 'impact') {
						type = '<span class="label label-info" style="width:80px;float:left;font-size:12px">- impact -</span>';
						link = 'manages/projects/impact/edit/' + datas[i].id;
					} else if(datas[i].project_type == 'teaching') {
						type = '<span class="label label-warning" style="width:80px;float:left;font-size:12px">- teaching -</span>';
						link = 'manages/projects/teaching/edit/' + datas[i].id;
					} else {
						type = '<span class="label label-danger" style="width:80px;float:left;font-size:12px">- report -</span>';
						link = 'manages/projects/report/'+ datas[i].project_type +'/edit/' + datas[i].id;
					}
					
					html = '<tr>' + 
							'	<td>'+ type +'</td>' + 
							'	<td><a href="'+link+'">' + datas[i].name + '</a></td>' + 
							'	<td>'+datas[i].address + datas[i].city + ', ' + datas[i].state + ' ' + datas[i].zip_code +', '+ datas[i].country +'</td>' + 
							'	<td><a href="manages/users/edit/'+datas[i].user_id+'">'+ datas[i].first_name +' '+ datas[i].last_name +'</a></td>' + 
							'	<td>'+ datas[i].created_date +'</td>' + 
							'</tr>';
					
					$("#for_project tbody").append(html);
				}
				$("body").unmask();
			}, "json");
		});
		
		$("button[name='search_facilitator']").click(function() {
			$("body").mask("");
			
			$.post("/dashboard/search/facilitator", {
				facilitator_name : $("input[name='facilitator_name']").val()
			}, function(response) {
				datas = response.data;
				$("#for_facilitator tbody").html("");
				
				for(i = 0; i < datas.length; i ++) {
					html = '<tr>' + 
							'	<td><a href="manages/users/edit/'+datas[i].id+'">'+datas[i].first_name+' '+datas[i].last_name+'</td>' + 
							'	<td>'+datas[i].address + datas[i].city + ', ' + datas[i].state + ' ' + datas[i].zip_code +', '+ datas[i].country +'</td>' + 
							'	<td>'+datas[i].username+'</td>' + 
							'	<td>'+datas[i].email+'</td>' + 
							'	<td>'+(datas[i].permission==100?'Default':'Region Administrator')+'</td>' + 
							'	<td>'+datas[i].created_date+'</td>' + 
							'</tr>';
					$("#for_facilitator tbody").append(html);
				}
				
				$("body").unmask();
			}, "json");
		});
		
		$("input[name='project_name'], input[name='facilitator_name']").keypress(function(ev) {
			if(ev.keyCode == 13) {
				if($(this).attr("name") == 'project_name') {
					$("button[name='search_project']").click();
				} else {
					$("button[name='search_facilitator']").click();
				}
			}
		});
	}); 
</script>

@stop
