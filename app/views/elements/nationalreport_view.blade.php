<script src="/js/jquery.raty.js"></script>

<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-8">
		<br>
		<span class="label label-primary" style="font-size:12px">- National Report -</span>
		<h2>{{$basic->name}}</h2>
	</div>
	<div class="col-lg-2">
		<div class="widget style1 lazur-bg" style="margin-bottom:0px">
			<div class="row">
				<div class="col-xs-4">
					<i class="fa fa-star fa-3x"></i>
				</div>
				<div class="col-xs-8 text-right">
					<span>Review</span>
					<h2 class="font-bold">{{$total_review->count}}</h2>
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
					<span>Followers</span>
					<h2 class="font-bold">{{$basic->follow_count}}</h2>
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
				<!--<a href="{{$redirect_url}}" class="btn btn-default btn-xs">Back to List</a>-->
			</div>
		</div>
		<div class="ibox-content">
			<?php if(Session::get("error") != "") :?>
				{{Session::get("error")}}
			<?php endif;?>
			<div class="row">
				<div class="col-lg-4">
					<div class="row" style="margin-bottom: 4px">
						<div class="col-md-12" align="right">
							Share link via: &nbsp;&nbsp;&nbsp;
							<a href="https://www.facebook.com/dialog/feed?app_id={{Config::get('facebook.app_id')}}&redirect_uri={{Config::get('app.url').$_SERVER['REQUEST_URI']}}&picture={{$picture}}&link={{$share_link}}&caption={{$basic->name}}&description={{$basic->description}}" target="_blank">
								<img src="/images/share-facebook.png" style="width:20px">
							</a>
							<a href="https://plus.google.com/share?url={{$share_link}}" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;">
								<img src="/images/share-google.png" style="width:20px">
							</a>
						</div>
					</div>
					<div class="portlet">
						<div class="portlet_wrapper">
							<div class="portlet_thumb">
								<!--<div class="thumb_img" style="background: url('{{$basic->thumbnail}}') center center;height:120px"></div>-->
								<img itemprop="image" src="{{$basic->thumbnail}}" style="width:100%">
							</div>
							<div class="portlet_title">
								<h4 style="color: #d18022"><b><span itemprop="name">{{$basic->name}}</span></b></h4>
							</div>
							<div style="width:100%;float:left;margin:5px 0px;">
								<div class="portlet_mark">
									<div class="rating-mark" data-score="{{$basic->review}}"></div>
								</div>
								<div class="portlet_follow">
									<img src="/images/like.png" style="width:15px">&nbsp;&nbsp;<span>{{$basic->follow_count}}</span>
								</div>
							</div>
						</div>
					</div>
					<?php if($basic->is_mine == 0) :?>
						<form name="frmFollowing" method="post">
							<input type="hidden" name="action" value="following">
							<?php if($basic->is_following > 0) :?>
								<input type="hidden" name="following" value="-1">
								<button name="following_flag" class="btn btn-primary btn-outline dim " type="button" style="text-transform: none;width:100%">
									Unfollow this project
								</button>
							<?php else : ?>
								<input type="hidden" name="following" value="1">
								<button name="following_flag" class="btn btn-danger btn-outline dim " type="button" style="text-transform: none;width:100%">
									Follow this project
								</button>
							<?php endif; ?>
						</form>
						<div>
							<!--<button name="donation" class="btn btn-warning btn-outline dim" type="button" onclick="javascript:location.href='/project/impact/{{$project_id}}/donation'" style="text-transform: none;width:100%">
								<i class="fa fa-dollar"></i>&nbsp;Donate to project
							</button>-->
							<button name="donation" class="btn btn-warning btn-outline dim" type="button" style="text-transform: none;width:100%">
								<i class="fa fa-dollar"></i>upport this project
							</button>
						</div>
						<br>
					<?php endif;?>
					<div class="widget-head-color-box navy-bg p-lg text-center">
						<img src="{{$owner->picture}}" class="img-circle circle-border m-b-md" alt="profile" width="50%">
						<div>
							<span> - Facilitator - </span><br><br>
							<h2 class="font-bold no-margins"> {{$owner->first_name}} {{$owner->last_name}} </h2>
						</div>
					</div>
					<div class="widget-text-box">
						<ul class="list-unstyled m-t-md">
							<li style="word-break: break-all">
								<span class="fa fa-envelope m-r-xs"></span>
								{{$owner->email}}
							</li>
							<li style="word-break: break-all">
								<span class="fa fa-home m-r-xs"></span>
								{{$owner->address}}, {{$owner->city}}
							</li>
							<li style="word-break: break-all">
								<span class="fa fa-globe m-r-xs"></span>
								{{$owner->state}} {{$owner->zip_code}}, {{$owner->country}}
							</li>
							<li style="word-break: break-all">
								<span class="fa fa-phone m-r-xs"></span>
								{{$owner->phone_number}}
							</li>
						</ul>
					</div>
				</div>
				<div class="col-lg-8">
					<h4><strong>Intro Video</strong></h4>
					<?php if($basic->intro_video != "") :?>
					<div style="display:table;width:100%">
						<embed width="100%" height="450" src="https://www.youtube.com/embed/{{$basic->intro_video}}">
					</div>
					<?php endif; ?>
					<br>
					<br>
					<h4><strong>Project Description</strong></h4>
					<p itemprop="description">
						<span itemprop="description">{{$basic->description}}</span>
					</p>
					<div class="ibox float-e-margins">
						<div class="ibox-title">
							<h5>More Details</h5>
							<div class="ibox-tools">
								<a class="collapse-link"> <i class="fa fa-chevron-up"></i> </a>
							</div>
						</div>
						<div class="ibox-content">
							<div class="row">
								<div class="col-md-12"><strong class="text-success">Are you going to organize an event to present your report?</strong> <i>{{$basic->organize_option_report==1?"Yes":"No"}}</i></div>
							</div><br>
							<div class="row">
								<div class="col-md-12"><strong class="text-success">When is the national day of prayer and fasting?</strong> <i>{{$basic->national_date}}</i></div>
							</div><br>
							<div class="row">
								<div class="col-md-12"><strong class="text-success">Are you going to organize an event for this day?</strong> <i>{{$basic->organize_option_day==1?"Yes":"No"}}</i></div>
							</div><br>
							<div class="row">
								<div class="col-md-12"><strong class="text-success">Write some prayers for the nation</strong>: <i>{{$basic->nation_prayers}}</i></div>
							</div><br>
							<div class="row">
								<div class="col-md-12"><strong class="text-success">Link to operation world information about the nation</strong>: 
									<a href="{{$basic->world_link}}" target="_blank" class="link" style="text-decoration: underline"><i>{{$basic->world_link}}</i></a>
								</div>
							</div><br>
							<div class="row">
								<div class="col-md-12"><strong class="text-success">What great Christian leaders have there been in the past and what is their story?</strong> <i>{{$basic->past_story}}</i></div>
							</div><br>
							<div class="row">
								<div class="col-md-12"><strong class="text-success">Relevant facts about the Spiritual condition of the nation</strong>: <i>{{$basic->relevant_fact}}</i></div>
							</div><br>
							<div class="row">
								<div class="col-md-12"><strong class="text-success">What is needed in the nation?</strong> <i>{{$basic->nation_need}}</i></div>
							</div><br>
						</div>
					</div>
					<br>
					<div class="ibox float-e-margins border-bottom">
						<div class="ibox-title">
							<h5>News Feed</h5>
							<div class="ibox-tools">
								<a class="collapse-link"> <i class="fa fa-chevron-down"></i> </a>
							</div>
						</div>
						<div class="ibox-content" style="display:none">
							<div class="feed-activity-list">
								<?php foreach($communications as $one):?>
									<div class="feed-element">
										<a href="#" class="pull-left"> <img alt="image" class="img-circle" src="{{$one->picture}}"> </a>
										<div class="media-body ">
											<small class="pull-right">{{date("F d, Y h:i A", strtotime($one->created_date))}} - {{date("Y.m.d", strtotime($one->created_date))}}</small>
											<strong>{{$one->name}}</strong> posted message.
											<br>
											<?php if($one->email != "") :?>
												<small>{{$one->email}}</small>
											<?php endif;?>
											<div class="well">{{$one->text}}</div>
										</div>
									</div>
								<?php endforeach;?>
								<?php if($basic->user_id == Auth::user()->id || $is_region_manager != 0) :?>
									<div>
										<form name="frmCommunication" method="post" action="">
											<textarea name="communication_text" class="form-control" rows="3" placeholder="Message:"></textarea>
											<button type="button" name="send_message" class="btn btn-primary btn-sm btn-block pull-right" style="margin-top:5px;width:150px"><i class="fa fa-envelope"></i> Post Message</button>
											<input type="hidden" name="action" value="communication">
										</form>
									</div>
								<?php endif;?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-3">
		<?php if(count($events) > 0) : ?>
        	<div class="ibox">
        		<div class="ibox-title">
					<h5>Project Event List</h5>
					<div class="ibox-tools">
						<a class="collapse-link"> <i class="fa fa-chevron-up"></i> </a>
					</div>
				</div>
        		<div class="ibox-content">
	            	<div class="list-group eventlist" style="margin:0px">
					<?php foreach($events as $one) :?>
						<a class="list-group-item {{$one->is_joined == 1 ? 'active' : ''}}" href="javascript:void" id="event_{{$one->id}}" data-id="{{$one->id}}">
                            <h4 class="list-group-item-heading">{{$one->title}}</h4>
                            <p class="list-group-item-text">
                            	{{date("F d, Y h:i A", strtotime($one->event_date))}}
                            	<?php if($one->is_joined == 1) :?>
                            		<button name="withdraw_event" data-id="{{$one->id}}" class="btn btn-danger btn-xs pull-right">Withdraw</button>
                            	<?php endif;?>
                            	<!--
                            	<?php if($basic->is_mine == 1) :?>
                            		<button name="invite" data-id="{{$one->id}}" class="btn btn-danger btn-xs pull-right">Invite</button>
                            	<?php endif;?>-->
                            </p>
                        </a>
					<?php endforeach;?>
					</div>
				</div>
			</div>
			<br>
		<?php endif;?>
		<?php if($basic->is_mine == 0) :?>
			<div class="row">
				<div class="col-lg-12">
					<div class="portlet" style="background: #ffffff">
						<div class="portlet_wrapper" style="width:95%;margin: 10px auto">
							<form name="frmHug" method="post" action="">
								<h2>Send Hug</h2>
								<textarea name="hug_comment" style="width:100%;height:60px;margin:5px auto;" class="form-control" placeholder="comment"></textarea>
								<div align="right">
									<button type="button" name="send_hug" class="btn btn-sm btn-primary">Submit</button>
								</div>
								<input type="hidden" name="action" value="hug">
							</form>
						</div>
					</div>
				</div>
			</div>
			<br>
		<?php endif;?>
		<?php if($basic->is_mine == 0) :?>
			<?php if($basic->is_feedback > 0) :?>
				<div class="row">
					<div class="col-lg-12">
						<div class="portlet" style="background: #ffffff">
							<div class="portlet_wrapper" style="width:95%;margin: 10px auto">
								<div class="alert alert-success">
	                				You have already given a feedback for this. :)
	            				</div>
	            				<?php
	            					$marks = explode(",", $feedback->comment);
	            				?>
	            				<div class="hr-line-dashed"></div>
								<div class="give_rating-mark-readonly" data-score="{{$marks[0]}}" id="score1"></div>
								<p>It is  a God idea</p>
								<div class="give_rating-mark-readonly" data-score="{{$marks[1]}}" id="score2"></div>
								<p>The people all seem to work together well</p>
								<div class="give_rating-mark-readonly" data-score="{{$marks[2]}}" id="score3"></div>
								<p>They seem to be on track</p>
								<div class="give_rating-mark-readonly" data-score="{{$marks[3]}}" id="score4"></div>
								<p>The facilitator shows good leadership</p>
								<div class="give_rating-mark-readonly" data-score="{{$marks[4]}}" id="score5"></div>
								<p>There is good character and the fruits of the Spirit evident to all</p>
	            			</div>
	            		</div>
	            	</div>
	            </div>
			<?php else :?>
				<div class="row">
					<div class="col-lg-12">
						<div class="portlet" style="background: #ffffff">
							<div class="portlet_wrapper" style="width:95%;margin: 10px auto">
								<form name="frmFeedback" method="post" action="">
									<h2>Give a Feedback</h2>
									<p>Please note that you can only leave one feedback rating.</p>
									<p>Please use the following 5 criteria when giving your star rating.</p>
									<div class="hr-line-dashed"></div>
									<div class="give_rating-mark" data-score="0" id="score1"></div>
									<p>It is  a God idea</p>
									<div class="give_rating-mark" data-score="0" id="score2"></div>
									<p>The people all seem to work together well</p>
									<div class="give_rating-mark" data-score="0" id="score3"></div>
									<p>They seem to be on track</p>
									<div class="give_rating-mark" data-score="0" id="score4"></div>
									<p>The facilitator shows good leadership</p>
									<div class="give_rating-mark" data-score="0" id="score5"></div>
									<p>There is good character and the fruits of the Spirit evident to all</p>
									<div class="hr-line-dashed"></div>
									<!--<textarea name="feedback_comment" style="width:100%;height:60px;margin:5px auto;" class="form-control" placeholder="comment"></textarea>-->
									<div align="right">
										<button type="button" name="send_feedback" class="btn btn-sm btn-primary">Submit</button>
									</div>
									<input type="hidden" name="feedback_comment" value="">
									<input type="hidden" name="action" value="feedback">
								</form>
							</div>
						</div>
					</div>
				</div>
			<?php endif;?>
		<?php endif;?>
	</div>
</div>
<div class="modal inmodal" id="modal_event" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content animated bounceInRight">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
				</button>
				<div class="row">
					<div class="col-lg-4"></div>
					<div class="col-lg-4" id="event_thumb"><i class="fa fa-laptop modal-icon"></i></div>
					<div class="col-lg-4"></div>
				</div>
				<h4 class="modal-title" id="event_title"></h4>
				<small class="font-bold"> 
					<label class="label label-warning">Event Time</label> <span id="event_time"></span>&nbsp;&nbsp;&nbsp; 
					<i class="fa fa-map-marker fa-2x"></i>&nbsp;&nbsp;<span id="event_location"></span> 
				</small>
			</div>
			<div class="modal-body">
				<h4><strong>Description:</strong></h4>
				<p id="event_description"></p>
				<h4><strong>Cost:</strong></h4>
				<p id="event_cost"></p>
				<h4><strong>Contact details form more information:</strong></h4>
				<p id="event_contact_details"></p>
				<h4><strong>Comment:</strong></h4>
				<p id="event_join_comment">
					<form name="frmEvent" method="POST">
						<textarea name="event-join-comment" class="form-control" rows="5" style="resize: none"></textarea>
						<input type="hidden" name="selected_event_id" value="0">
						<input type="hidden" name="action" value="join">
					</form>
				</p>
			</div>
			<div class="modal-footer">
				<?php if($basic->is_mine == 0) :?>
					<button type="button" class="btn btn-danger" name="modal-withdraw">
						Withdraw
					</button>
					<button type="button" class="btn btn-primary" name="modal-join">
						Join
					</button>
				<?php endif;?>
				<button type="button" class="btn btn-white" data-dismiss="modal">
					Close
				</button>
			</div>
		</div>
	</div>
</div>
<div class="modal inmodal" id="modal_donation" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content animated bounceInRight">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
				</button>
				<div class="row">
					<div class="col-lg-4"></div>
					<div class="col-lg-4" id="event_thumb"><i class="fa fa-paypal fa-5x"></i></div>
					<div class="col-lg-4"></div>
				</div>
				<h4 class="modal-title text-info" id="event_title">Thank you for your support!</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-lg-12">
						<form name="frmDonation" method="POST">
							<div class="input-group m-b">
								<span class="input-group-addon">USD$</span>
								<input type="text" name="amount" id="amount" class="form-control">
								<span class="input-group-addon">.00</span>
								<input type="hidden" name="action" value="donation">
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success" name="modal-checkout">
					<i class="fa fa-paypal"></i>&nbsp;&nbsp;Checkout with PayPal
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
		
		$(".give_rating-mark-readonly").each(function() {
			score = $(this).attr("data-score");
			$(this).raty({
				readOnly : true,
				score : score
			});
		});
		
		$(".give_rating-mark").each(function() {
			score = $(this).attr("data-score");
			id = $(this).attr("id");
			$(this).raty({
				readOnly : false,
				score : score,
				scoreName: 'feedback_' + id
			});
		});
		
		$(".eventlist a").click(function(ev) {
			if(ev.target.tagName.toLowerCase() == "button") {
				return;
			}
			var $id = $(this).attr("data-id");
			$("body").mask("");
			$.get("/ajax/project/nationalreport/events/get/"+$id, {}, function(response) {
				$("body").unmask();
				if(response.success) {
					info = response.info;
					$("#modal_event input[name='selected_event_id']").val($id);
					$("#modal_event #event_title").html(info.title);
					$("#modal_event #event_location").html(info.address + ", " + info.city + ", " + info.state + " " + info.zip_code + ", " + info.country);
					$("#modal_event #event_time").html(info.event_date);
					$("#modal_event #event_description").html(info.description);
					$("#modal_event #event_cost").html("$" + info.cost);
					$("#modal_event #event_contact_details").html(info.contact_details.replace(/(?:\r\n|\r|\n)/g, "<br>"));
					
					if(info.thumbnail != null && info.thumbnail != "") {
						$("#modal_event #event_thumb").html("<div class='portlet'><div class='portlet_wrapper'><img src='" + info.thumbnail + "' width='100%'></div></div>");
					}
					
					if(response.joined) {
						$("textarea[name='event-join-comment']").val(response.join_info.comment);
						$("textarea[name='event-join-comment']").prop("readonly", true);
						$("button[name='modal-join']").hide();
						$("button[name='modal-withdraw']").show();
					} else {
						$("textarea[name='event-join-comment']").val("");
						$("textarea[name='event-join-comment']").prop("readonly", false);
						$("button[name='modal-join']").show();
						$("button[name='modal-withdraw']").hide();
					}
					
					$("#modal_event").modal();
				}
			}, "json");
		});
		
		$("button[name='modal-join']").click(function() {
			$("form[name='frmEvent']").submit();
			/*
			var $id = $("#modal_event input[name='selected_event_id']").val();
			
			$.post("/ajax/project/nationalreport/events/join/"+$id, {
				comment : $("textarea[name='event-join-comment']").val()
			}, function(response) {
				if(response.success) {
					$(".eventlist #event_"+$id).addClass("active");
					$(".eventlist #event_"+$id+" p").append("<button name='withdraw_event' data-id='"+$id+"' class='btn btn-danger btn-xs pull-right'>Withdraw</button>");
					$('#modal_event').modal('hide');
					
					$("button[name='withdraw_event']").click(function() {
						var $id = $(this).attr("data-id");
						if(!confirm("Are you sure you wish withdraw joined?")) {
							return;
						}
						
						$.post("/ajax/project/nationalreport/events/withdraw/"+$id, {}, function(response) {
							if(response.success) {
								$(".eventlist #event_"+$id).removeClass("active");
								$(".eventlist #event_"+$id+" button").remove();
								$('#modal_event').modal('hide');
							}
						}, "json");
					});
				}
			}, "json");
			*/
		});
		
		$("button[name='modal-withdraw']").click(function() {
			var $id = $("#modal_event input[name='selected_event_id']").val();
			
			$.post("/ajax/project/nationalreport/events/withdraw/"+$id, {}, function(response) {
				if(response.success) {
					$(".eventlist #event_"+$id).removeClass("active");
					$(".eventlist #event_"+$id+" button").remove();
					$('#modal_event').modal('hide');
				}
			}, "json");
		});
		
		$("button[name='withdraw_event']").click(function() {
			var $id = $(this).attr("data-id");

			if(!confirm("Are you sure you wish withdraw joined?")) {
				return;
			}
			
			$.post("/ajax/project/nationalreport/events/withdraw/"+$id, {}, function(response) {
				if(response.success) {
					$(".eventlist #event_"+$id).removeClass("active");
					$(".eventlist #event_"+$id+" button").remove();
					$('#modal_event').modal('hide');
				}
			}, "json");
		});
		
		$("button[name='following_flag']").click(function() {
			$("form[name='frmFollowing']").submit();
		});
		
		$("button[name='send_feedback']").click(function() {
			if($("input[name='feedback_score']").val() == 0 || $("input[name='feedback_score']").val() == "") {
				alert("Please give a feedback mark.");
				return;
			}
			
			$("form[name='frmFeedback']").submit();
		});
		
		$("button[name='send_message']").click(function() {
			if($("textarea[name='communication_text']").val() == "") {
				$("textarea[name='communication_text']").focus();
				return;
			}
			
			$("form[name='frmCommunication']").submit();
		});

		$("button[name='send_hug']").click(function() {
			if($("textarea[name='hug_comment']").val() == "") {
				$("textarea[name='hug_comment']").focus();
				return;
			}
			
			$("form[name='frmHug']").submit();
		});
		
		$("button[name='invite']").click(function() {
			var $id = $(this).attr("data-id");
			
			$.post("/ajax/project/nationalreport/{{$project_id}}/"+$id+"/invite", {}, function(response) {
				if(response.success) {
					alert("You invited peoples in a event successfully.");
				}
			}, "json");
		});

		$("button[name='donation']").click(function() {
			$("input[name='amount']").val("");
			$("#modal_donation").modal();
		});
		
		$("button[name='modal-checkout']").click(function() {
			if($("input[name='amount']").val() == "" || $("input[name='amount']").val() == 0 || isNaN($("input[name='amount']").val())) {
				$("input[name='amount']").focus();
				$("input[name='amount']").select();
				return;
			}
			
			$("form[name='frmDonation']").submit();
		});
	}); 
</script>
<?php Session::set("error", ""); ?>