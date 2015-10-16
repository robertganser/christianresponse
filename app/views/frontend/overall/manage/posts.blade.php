@extends('layout.overall_dashboard')
@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-10">
		<h2>Manage Post</h2>
	</div>
	<div class="col-lg-2">
		<div class="widget style1 lazur-bg" style="margin-bottom:0px">
			<div class="row">
				<div class="col-xs-4">
					<i class="fa fa-newspaper-o fa-4x"></i>
				</div>
				<div class="col-xs-8 text-right">
					<span> Total Posts </span>
					<h2 class="font-bold">{{count($posts)}}</h2>
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
						<h5>List of posts</h5>
						<button type="button" name="new" class="btn btn-primary" onclick="javascript:location.href='/manages/posts/edit'">
							<i class="fa fa-edit"></i>&nbsp;New Post
						</button>
					</div>
					<div class="ibox-content">
						<div class="table-responsive m-t">
							<table class="table">
								<thead>
									<tr>
										<th>No.</th>
										<th>Title</th>
										<th>Created Date</th>
										<th>Updated Date</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<?php $i = 1; foreach($posts as $one) :?>
										<tr>
											<td nowrap="">{{$i}}</td>
											<td nowrap="">{{$one->title}}</td>
											<td nowrap="">{{$one->created_date}}</td>
											<td nowrap="">{{$one->updated_date}}</td>
											<td align="right" width="70px" nowrap="">
												<button type="button" name="edit-row" class="btn btn-xs btn-outline btn-primary" onclick="javascript:location.href='/manages/posts/edit/{{$one->id}}'">
													<i class="fa fa-edit"></i>
												</button>
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
</form>
<script>
	$(document).ready(function() {
		$("button[name='delete-row']").click(function() {
			if(!confirm("Are you sure you wish delete post?")) {
				return;
			}
			var $id = $(this).attr("data-id");
			location.href = "/manages/posts/delete/" + $id;
		});
	});
</script>
@stop
