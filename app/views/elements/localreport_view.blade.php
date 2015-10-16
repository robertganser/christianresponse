<script src="/js/jquery.raty.js"></script>

<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-8">
		<br>
		<small>- local Report -</small> <a href="#"><span class="label label-danger">Unfollowing</span></a>
		<h2>Project Title</h2>
	</div>
	<div class="col-lg-2">
		<div class="widget style1 lazur-bg" style="margin-bottom:0px">
			<div class="row">
				<div class="col-xs-4">
					<i class="fa fa-star fa-3x"></i>
				</div>
				<div class="col-xs-8 text-right">
					<span> Total Reviews </span>
					<h2 class="font-bold">74</h2>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-2">
		<div class="widget style1 lazur-bg" style="margin-bottom:0px">
			<div class="row">
				<div class="col-xs-4">
					<i class="fa fa-heart fa-3x"></i>
				</div>
				<div class="col-xs-8 text-right">
					<span> Total Followers </span>
					<h2 class="font-bold">260</h2>
				</div>
			</div>
		</div>
	</div>
</div>
<br>
<div class="row">
	<div class="col-lg-9">
		<div class="ibox-title">
			Project Information
			<div class="pull-right">
				<a href="/search/localreport" class="btn btn-default btn-xs">Back to List</a>
			</div>
		</div>
		<div class="ibox-content">
			<div class="row">
				<div class="col-lg-3">
					<div class="portlet">
						<div class="portlet_wrapper">
							<div class="portlet_thumb">
								<div class="thumb_img" style="background: url('/res/project/pix1.jpg')"></div>
							</div>
							<div class="portlet_title">
								<h4 style="color: #d18022"><b>New Project 1</b></h4>
							</div>
							<div style="width:100%;float:left">
								<div class="portlet_mark">
									<div class="rating-mark" data-score="5"></div>
								</div>
								<div class="portlet_follow">
									<img src="/images/like.png" style="width:15px"><span>150</span>
								</div>
							</div>
							<div style="width:100%;float:left;margin:5px 0px;">
								<div class="portlet_comment">
									September 20, 2015
								</div>
							</div>
						</div>
					</div>
					<button class="btn btn-danger btn-outline dim " type="button" style="text-transform: none;width:100%">
						<i class="fa fa-heart"></i>&nbsp;Follow this project
					</button>
				</div>
				<div class="col-lg-1"></div>
				<div class="col-lg-8">
					<h4><strong>Intro Video</strong></h4>
					<embed width="100%" height="450" src="http://www.youtube.com/v/XGSy3_Czz8k"></embed>
					<br><br>
					<h4><strong>Project Description</strong></h4>
					<p>
						Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.

						It was popularised in the 1960s with the release Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.

						There are many variations of passages of Lorem IpsumLorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of.
					</p>
					<br>
					<h4><strong>Project Events</strong></h4>
					<?php for($i = 1; $i <= 5; $i ++) :?>
						<div class="timeline-item">
							<div class="row">
								<div class="col-xs-3 date">
									<i class="fa fa-file-text"></i>
									Sep 25, 2014
									<br>
									<small class="text-navy">7:00 AM</small>
								</div>
								<div class="col-xs-9 content">
									<p class="m-b-xs">
										<h3><strong>Event Title</strong></h3>
									</p>
									<p>
										Location: Event Location
									</p>
									<p>
										<button type="button" name="view_event" data-id="<?php echo $i?>" class="btn btn-xs btn-default">
											View
										</button>
										<?php if($i % 2 == 0) :?>
											<button type="button" class="btn btn-xs btn-info">
												Join Event
											</button>
										<?php else :?>
											<button type="button" class="btn btn-xs btn-danger">
												Withdraw
											</button>
										<?php endif;?>
									</p>
								</div>
							</div>
						</div>
					<?php endfor; ?>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-3">
		<div class="widget-head-color-box navy-bg p-lg text-center">
			<div class="m-b-md">
				<h2 class="font-bold no-margins"> Alex Smith </h2>
			</div>
			<img src="/images/default-user.png" class="img-circle circle-border m-b-md" alt="profile" width="40%">
			<div>
				<span>100 Projects</span> | <span>350 Following</span></span>
			</div>
		</div>
		<div class="widget-text-box">
			<h4 class="media-heading">Alex Smith</h4>
			<ul class="list-unstyled m-t-md">
				<li>
					<span class="fa fa-envelope m-r-xs"></span>
					<label>Email:</label>
					mike@mail.com
				</li>
				<li>
					<span class="fa fa-home m-r-xs"></span>
					<label>Address:</label>
					Street 200, Avenue 10
				</li>
				<li>
					<span class="fa fa-phone m-r-xs"></span>
					<label>Contact:</label>
					(+121) 678 3462
				</li>
			</ul>
		</div>
		<br>
		<div class="row">
			<div class="col-lg-12">
				<div class="portlet" style="background: #ffffff">
					<div class="portlet_wrapper" style="width:95%;margin: 10px auto">
						<h2>Give a Feedback</h2>
						<div class="give_rating-mark" data-score="0"></div>
						<textarea name="" style="width:100%;height:60px;margin:5px auto;" class="form-control" placeholder="comment"></textarea>
						<div align="right">
							<button type="button" class="btn btn-sm btn-primary">Submit</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal inmodal" id="modal_event" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content animated bounceInRight">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
				</button>
				<i class="fa fa-laptop modal-icon"></i>
				<h4 class="modal-title">Event Title</h4>
				<small class="font-bold"> <label class="label label-info">Location</label> Riviera State 32/106&nbsp;&nbsp;&nbsp; <label class="label label-warning">Event Time</label> 2015-04-25 14:30:00 </small>
			</div>
			<div class="modal-body">
				<h4><strong>Description:</strong></h4>
				<p>
					text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown
					printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting,
					remaining essentially unchanged.
				</p>
				<h4><strong>Contact details form more information:</strong></h4>
				<p>
					Whiliam XXX
					<br>
					New York, NY 50210, United States of America
					<br>
					<br>
					Tel: +1 234 567 8910
					<br>
					E-mail: mail@yoursitename.com
				</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary">
					Withdraw
				</button>
				<button type="button" class="btn btn-white" data-dismiss="modal">
					Close
				</button>
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
		
		$(".give_rating-mark").each(function() {
			score = $(this).attr("data-score");
			$(this).raty({
				readOnly : false,
				score : score
			});
		});
		
		$("button[name='view_event']").click(function() {
			var $id = $(this).attr("data-id");

			$("#modal_event").modal();
		});
	}); 
</script>