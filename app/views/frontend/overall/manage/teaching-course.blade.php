@extends('layout.overall_dashboard')
@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-10">
		<h2>Manage Teaching Courses</h2>
	</div>
	<!--<div class="col-lg-2">
		<div class="widget style1 lazur-bg" style="margin-bottom:0px">
			<div class="row">
				<div class="col-xs-4">
					<i class="fa fa-newspaper-o fa-4x"></i>
				</div>
				<div class="col-xs-8 text-right">
					<span> Total Courses </span>
					<h2 class="font-bold">{{count($course)}}</h2>
				</div>
			</div>
		</div>
	</div>-->
</div>
<form name="frmUserPermission" method="POST" action="">
	<div class="wrapper wrapper-content  animated fadeInRight">
		<div class="row">
			<div class="col-lg-12">
				<div class="ibox">
					<div class="ibox-title" style="display:table;width:100%;text-align: right">
						<h5>List of course</h5>
						<button type="button" name="new" class="btn btn-primary" onclick="javascript:location.href='/manages/teaching-course/edit'">
							<i class="fa fa-edit"></i>&nbsp;New Teaching Course
						</button>
					</div>
					<div class="ibox-content">
						<div class="table-responsive m-t">
							<table class="table">
								<thead>
									<tr>
										<th>Order.</th>
										<th>Title</th>
										<th>Comment</th>
										<th>Thumbnail</th>
										<th>Course File</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<?php $i = 1; foreach($course as $one) :?>
										<tr>
											<td nowrap=""><input type="text" name="order[]" value="{{$one->order}}" data-id="{{$one->id}}" class="form-control" style="text-align: center;width:60px"></td>
											<td nowrap="">{{$one->title}}</td>
											<td nowrap="">{{$one->comment}}</td>
											<td nowrap=""><a href="{{$one->thumbnail}}" target="_blank"><i class="fa fa-picture-o"></i></a></td>
											<td nowrap=""><a href="{{$one->pdf}}" target="_blank"><i class="fa fa-newspaper-o"></i></a></td>
											<td align="right" width="70px" nowrap="">
												<button type="button" name="edit-row" class="btn btn-xs btn-outline btn-primary" onclick="javascript:location.href='/manages/teaching-course/edit/{{$one->id}}'">
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
			if(!confirm("Are you sure you wish delete course data?")) {
				return;
			}
			var $id = $(this).attr("data-id");
			location.href = "/manages/teaching-course/delete/" + $id;
		});
		
		$("input[name='order[]']").change(function() {
			var $order = $(this).val();
			var $id = $(this).attr("data-id");
			
			if(isNaN($order) || $order == "" || $order == 0) {
				$(this).focus();
				$(this).select();
				return;
			}
			
			$.post("/manages/teaching-course/change_order", {
				id : $id,
				order : $order
			}, function(res) {
				if(res.success) {
					alert("Data order is changed successfully");
				}
			}, "json");
		});
	});
</script>
@stop