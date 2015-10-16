@extends('layout.region_dashboard')
@section('content')
<link href="/css/dashboard/plugins/chosen/chosen.css" rel="stylesheet">

<script src="/js/jquery.raty.js"></script>
<script src="/js/dashboard/plugins/chosen/chosen.jquery.js"></script>

<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-12">
		<h2>Search Project</h2>
	</div>
</div>
<br>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-content">
				<form action="" method="post" name="frmSearch">
					<div class="row">
						<div class="col-lg-12">
							<h2> {{$total}} results found</h2>
						</div>
					</div>
					<div class="search-form">
						<div class="row">
							<div class="col-lg-2"><input type="text" placeholder="Project Name: " name="search_key" value="<?php echo $search_key?>" class="form-control"></div>
							<div class="col-lg-2">
								<select name="country" class="form-control chosen-select" data-placeholder="Choose a Country...">
									<option value="" {{$country == "" ? "selected" : ""}}>Choose a Country...</option>
									<?php foreach($countries as $one) :?>
										<option value="{{$one->country}}" {{$country == $one->country ? "selected" : ""}}>{{$one->country}}</option>
									<?php endforeach;?>
								</select>
							</div>
							<div class="col-lg-2">
								<select name="state" class="form-control chosen-select" data-placeholder="Choose a State...">
									<option value="" {{$state == "" ? "selected" : ""}}>Choose a State...</option>
									<?php foreach($states as $one) :?>
										<option value="{{$one->state}}" {{$state == $one->state ? "selected" : ""}}>{{$one->state}}</option>
									<?php endforeach;?>
								</select>
							</div>
							<div class="col-lg-2">
								<select name="city" class="form-control chosen-select" data-placeholder="Choose a City...">
									<option value="" {{$city == "" ? "selected" : ""}}>Choose a City...</option>
									<?php foreach($cities as $one) :?>
										<option value="{{$one->city}}" {{$city == $one->city ? "selected" : ""}}>{{$one->city}}</option>
									<?php endforeach;?>
								</select>
							</div>
							<div class="col-lg-2">
								<select name="zip_code" class="form-control chosen-select" data-placeholder="Choose a Zip Code...">
									<option value="" {{$zip_code == "" ? "selected" : ""}}>Choose a Zip Code...</option>
									<?php foreach($zip_codes as $one) :?>
										<option value="{{$one->zip_code}}" {{$zip_code == $one->zip_code ? "selected" : ""}}>{{$one->zip_code}}</option>
									<?php endforeach;?>
								</select>
							</div>
							<div class="col-lg-2" align="right">
								<button class="btn btn-primary" type="submit" name="search" style="width:100%">
									<i class="fa fa-search"></i>&nbsp;&nbsp;Search Result
								</button>
							</div>
						</div>
					</div>
				</form>
				<div class="hr-line-dashed"></div>
				<div class="row">
					<?php foreach($result as $one) :?>
					<div class="col-md-2">
						<div class="portlet">
							<div class="portlet_wrapper">
								<div class="portlet_thumb" style="height:120px">
									<div class="thumb_img" style="background: url('{{$one->thumbnail}}') center center"></div>
								</div>
								<div class="portlet_title">
									<h4 style="color: #d18022"><b>{{$one->name}}</b></h4>
								</div>
								<div style="width:100%;float:left">
									<div class="portlet_mark">
										<div id="project-rating-{{$one->project_type}}-{{$one->id}}" class="rating-mark" data-score="{{$one->review}}"></div>
									</div>
									<div class="portlet_follow">
										<img src="/images/like.png" style="width:15px"><span>{{$one->follow_count}}</span>
									</div>
								</div>
								<div style="width:100%;float:left;margin:5px 0px;">
									<div class="portlet_link">
										<a href="/search/project/{{$one->project_type}}/view/{{$one->id}}">View</a>
									</div>
									<div class="portlet_comment">{{date("F d, Y", strtotime($one->created_date))}}</div>
								</div>
							</div>
						</div>
					</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function() {
		$(".chosen-select").chosen();

		$(".rating-mark").each(function() {
			score = $(this).attr("data-score");
			$(this).raty({
				readOnly : true,
				score : score
			});
		});
		
		resize_portlet();
		
		function resize_portlet() {
			$(".portlet").each(function() {
				width = $(this).parent().width();
				$(this).find(".portlet_title h4").css({
					'width' : width + 'px',
					'overflow' : 'hidden',
					'text-overflow' : 'ellipsis',
					'white-space' : 'nowrap'
				});
			});
		}
		
		$(window).resize(function() {
			resize_portlet();
		});
	}); 
</script>
@stop
