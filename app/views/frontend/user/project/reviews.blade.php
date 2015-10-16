@extends('layout.user_dashboard')
@section('content')
<script src="/js/jquery.raty.js" language="JavaScript"></script>

<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-10">
		<h2>Feedbacks</h2>
		<h5> - {{$project_title}} - </h5>
		<ol class="breadcrumb">
			<li>
				<a>Projects</a>
			</li>
			<li>
				<a href="/projects/{{$type}}">{{$type}}</a>
			</li>
			<li class="active">
				<strong>Feedback List</strong>
			</li>
		</ol>
	</div>
	<div class="col-lg-2">
		<div class="widget style1 navy-bg">
            <div class="row">
                <div class="col-xs-4">
                    <i class="fa fa-user fa-4x"></i>
                </div>
                <div class="col-xs-8 text-right">
                    <span> Total </span>
                    <h2 class="font-bold">{{number_format(count($reviews))}}</h2>
                </div>
            </div>
        </div>
	</div>
</div>
<div class="wrapper wrapper-content  animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox">
				<div class="ibox-title" style="display:table;width:100%;text-align: right">
					<h5>List of Feedback</h5>
				</div>
				<div class="ibox-content">
					<div class="table-responsive m-t">
						<table class="table">
							<thead>
								<tr>
									<th>No.</th>
									<th>Name</th>
									<th>Email</th>
									<th>Marks</th>
									<th>Created Date</th>
								</tr>
							</thead>
							<tbody>
								<?php $i = 1; foreach($reviews as $one) :?>
									<tr>
										<td>{{$i}}</td>
										<td>{{$one->name}}</td>
										<td>{{$one->email}}</td>
										<td><div class="rating-mark" data-score="<?php echo $one->mark?>"></div></td>
										<td>{{$one->created_date}}</td>
									</tr>
								<?php $i ++;endforeach;?>
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
	$(".rating-mark").each(function() {
		score = $(this).attr("data-score");
		$(this).raty({
			readOnly : true,
			score : score
		});
	});
});
</script>
@stop