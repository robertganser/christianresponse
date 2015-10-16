@extends('layout.region_dashboard')
@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-10">
		<h2>Manage Projects</h2>
		<small> - {{$type}} - </small>
	</div>
	<div class="col-lg-2">
		<div class="widget style1 lazur-bg" style="margin-bottom:0px">
			<div class="row">
				<div class="col-xs-4">
					<i class="fa fa-globe fa-4x"></i>
				</div>
				<div class="col-xs-8 text-right">
					<span> Total Projects </span>
					<h2 class="font-bold">{{count($projects)}}</h2>
				</div>
			</div>
		</div>
	</div>
</div>
<form name="frmProjects" method="POST" action="">
	<div class="wrapper wrapper-content  animated fadeInRight">
		<div class="row">
			<div class="col-lg-12">
				<div class="ibox">
					<div class="ibox-title" style="display:table;width:100%;text-align: right">
						<h5>List of project</h5>
						<button type="submit" name="save" class="btn btn-primary">
							<i class="fa fa-save"></i>&nbsp;&nbsp;Save Changed
						</button>
					</div>
					<div class="ibox-content">
						<div class="table-responsive m-t">
							<table class="table">
								<thead>
									<tr>
										<?php if($type == "report") :?><th>&nbsp;</th><?php endif;?>
										<th>No.</th>
										<th>Title</th>
										<th>Owner</th>
										<th>Created Date</th>
										<th>Events</th>
										<th>Following</th>
										<th>Hugs</th>
										<th></th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<?php $i = 1; foreach($projects as $one) :?>
										<?php
											$color = "white";
											if($one->status == -2) {
												$color = "#f8c4ca";
											} elseif($one->status == 100) {
												$color = "#cbf5f6";
											}
										?>
										<tr style="background-color: {{$color}}">
											<?php if($type == "report") :?>
												<td nowrap="">{{$one->type == "nationalreport" ? "<span class='label label-info'>national</span>" : "<span class='label label-warning'>regional</span>"}}</td>
											<?php endif;?>
											<td nowrap="">{{$i}}</td>
											<td nowrap="">{{$one->name}}</td>
											<td nowrap="">{{$one->first_name}} {{$one->last_name}}</td>
											<td nowrap="">{{$one->created_date}}</td>
											<td width="100px" nowrap="">
												<?php if($one->event_count > 0) :?>
													<?php if($one->type == "nationalreport" || $one->type == "regionalreport") :?>
														<a href="/manages/projects/report/{{$one->type}}/{{$one->id}}/events"><span class="label label-info"><?php echo $one->event_count?></span></a>
													<?php else :?>
														<a href="/manages/projects/{{$one->type}}/{{$one->id}}/events"><span class="label label-info"><?php echo $one->event_count?></span></a>
													<?php endif;?>
												<?php else:?>
													<?php if($one->type == "nationalreport" || $one->type == "regionalreport") :?>
														<a href="/manages/projects/report/{{$one->type}}/{{$one->id}}/events"><span class="label label-default">manage</span></a>
													<?php else :?>
														<a href="/manages/projects/{{$one->type}}/{{$one->id}}/events"><span class="label label-default">manage</span></a>
													<?php endif;?>
												<?php endif;?>
											</td>
											<td width="100px" nowrap="">
												<?php if($one->follow_count > 0) :?>
													<?php if($one->type == "nationalreport" || $one->type == "regionalreport") :?>
														<a href="/manages/projects/report/{{$one->type}}/{{$one->id}}/followings"><span class="label label-danger"><?php echo $one->follow_count?></span></a>
													<?php else :?>
														<a href="/manages/projects/{{$one->type}}/{{$one->id}}/followings"><span class="label label-danger"><?php echo $one->follow_count?></span></a>
													<?php endif;?>
												<?php endif;?>
											</td>
											<td width="100px" nowrap="">
												<?php if($one->hug_count > 0) :?>
													<?php if($one->type == "nationalreport" || $one->type == "regionalreport") :?>
														<a href="/manages/projects/report/{{$one->type}}/{{$one->id}}/hugs"><span class="label label-warning">{{$one->hug_count}}</span></a>
													<?php else :?>
														<a href="/manages/projects/{{$one->type}}/{{$one->id}}/hugs"><span class="label label-warning">{{$one->hug_count}}</span></a>
													<?php endif;?>
												<?php endif;?>
											</td>
											<td nowrap="" width="150px">
												<select name="project_status[{{$one->type}}][{{$one->id}}]" class="form-control" style="margin:0px;height:32px">
													<option value="100" {{$one->status == 100 ? "selected" : ""}}></option>
													<option value="1" {{$one->status == 1 ? "selected" : ""}}>Approve</option>
													<option value="-2" {{$one->status == -2 ? "selected" : ""}}>Suspend</option>
												</select>
											</td>
											<td width="70px" align="right">
												<?php if($one->type == "nationalreport" || $one->type == "regionalreport") :?>
													<button type="button" name="edit-row" class="btn btn-sm btn-outline btn-primary" onclick="javascript:location.href='/manages/projects/report/{{$one->type}}/edit/{{$one->id}}'">
														<i class="fa fa-edit"></i>&nbsp;&nbsp;Edit
													</button>
												<?php else :?>
													<button type="button" name="edit-row" class="btn btn-sm btn-outline btn-primary" onclick="javascript:location.href='/manages/projects/{{$one->type}}/edit/{{$one->id}}'">
														<i class="fa fa-edit"></i>&nbsp;&nbsp;Edit
													</button>
												<?php endif;?>
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
</form>
@stop