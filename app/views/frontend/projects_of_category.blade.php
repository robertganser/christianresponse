@extends('layout.blank')
@section('content')

<div style="text-align: center;min-height: 500px">
	<h2 align="center">Project List for <?php echo $category_name?></h2>
	<div class="row">
		<?php foreach($projects as $one) :?>
			<div class="col-md-3">
				<div class="portlet">
					<div class="portlet_wrapper">
						<div class="portlet_thumb" style="height:90px;"><div class="thumb_img" style="background: url('<?php echo $one->thumbnail?>') center center"></div></div>
						<div class="portlet_title"><h3 style="color: #d18022;text-align: left"><?php echo $one->name?></h3></div>
						<div style="width:100%;float:left">
							<div class="portlet_mark"><div id="project-rating-<?php echo $one->id?>" class="rating-mark" data-score="<?php echo $one->review?>"></div></div>
							<div class="portlet_follow">
								<img src="/images/like.png" style="width:15px"> <span><?php echo $one->follow_count?></span>
							</div>
						</div>
						<div style="width:100%;float:left">
							<div class="portlet_link"><a href="/project/view/<?php echo $one->project_type?>/<?php echo $one->id?>">View</a></div>
							<div class="portlet_comment"><?php echo date("F d, Y", strtotime($one->created_date))?></div>
						</div>
					</div>
				</div>
			</div>
		<?php endforeach;?>
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
				$(this).find(".portlet_title h3").css({
					'width' : (width - 15)+ 'px',
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