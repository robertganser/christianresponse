@extends('layout.region_dashboard')
@section('content')
<script src="/js/jquery.raty.js"></script>
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-12">
		<h2>Search Regional Report</h2>
	</div>
</div>
<br>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-content">
				<form action="" method="post" name="frmSearch">
					<div class="row">
						<div class="col-lg-9">
							<h2> {{$total}} results found for: <span class="text-navy">“<?php echo $search_key?>”</span></h2>
						</div>
					</div>
					<div class="search-form">
						<div class="input-group">
							<input type="text" placeholder="" name="search_key" value="<?php echo $search_key?>" class="form-control input-lg">
							<div class="input-group-btn">
								<button class="btn btn-lg btn-primary" type="submit" name="search">
									Search
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
										<div id="project-rating-{{$one->id}}" class="rating-mark" data-score="{{$one->review}}"></div>
									</div>
									<div class="portlet_follow">
										<img src="/images/like.png" style="width:15px"><span>{{$one->follow_count}}</span>
									</div>
								</div>
								<div style="width:100%;float:left;margin:5px 0px;">
									<div class="portlet_link">
										<a href="/search/project/regionalreport/view/{{$one->id}}">View</a>
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
