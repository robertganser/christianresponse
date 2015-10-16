@extends('layout.overall_dashboard')
@section('content')
<script src="/js/jquery.raty.js"></script>
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-12">
		<h2>Search Local Report</h2>
	</div>
</div>
<br>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-content">
				<h2> 2,160 results found for: <span class="text-navy">“<?php echo $search_key?>”</span></h2>

				<div class="search-form">
					<form action="" method="post">
						<div class="input-group">
							<input type="text" placeholder="Admin Theme" name="search_key" value="<?php echo $search_key?>" class="form-control input-lg">
							<div class="input-group-btn">
								<button class="btn btn-lg btn-primary" type="button" name="search">
									Search
								</button>
							</div>
						</div>
					</form>
				</div>
				<div class="hr-line-dashed"></div>
				<div class="row">
					<?php for($i = 0; $i <= 8; $i ++) :?>
					<div class="col-md-2">
						<div class="portlet">
							<div class="portlet_wrapper">
								<div class="portlet_thumb">
									<div class="thumb_img" style="background: url('/res/project/pix<?php echo (($i%4)+1)?>.jpg')"></div>
								</div>
								<div class="portlet_title">
									<h4 style="color: #d18022"><b>New Project 1</b></h4>
								</div>
								<div style="width:100%;float:left">
									<div class="portlet_mark">
										<div id="project-rating-<?php echo $i?>" class="rating-mark" data-score="<?php echo (($i%4)+1)?>"></div>
									</div>
									<div class="portlet_follow">
										<img src="/images/like.png" style="width:15px"><span>150</span>
									</div>
								</div>
								<div style="width:100%;float:left;margin:5px 0px;">
									<div class="portlet_link">
										<a href="/search/localreport/view/<?php echo $i?>">View</a>
									</div>
									<div class="portlet_comment">
										September 20, 2015
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php endfor; ?>
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
		
		$("button[name='search']").click(function() {
			var $key = $("input[name='search_key']").val();
			
			location.href = "/search/localreport/"+$key;
		});

		$("form").submit(function() {
			var $key = $("input[name='search_key']").val();

			$(this).attr("action", "/search/localreport/" + $key);
		});
	}); 
</script>
@stop
