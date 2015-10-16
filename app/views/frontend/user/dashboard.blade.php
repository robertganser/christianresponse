@extends('layout.user_dashboard')
@section('content')
<script src="/js/dashboard/plugins/chosen/chosen.jquery.js"></script>
<script src="/js/jquery.raty.js"></script>
<script type="text/javascript" src="/js/jquery.least.js"></script>
<script type="text/javascript" src="/js/jquery-loadmask.js"></script>

<link href="/css/dashboard/plugins/chosen/chosen.css" rel="stylesheet" type="text/css" />
<link href="/css/jquery.least.css" rel="stylesheet" type="text/css" />
<link href="/css/loadmask.css" rel="stylesheet" type="text/css" />

<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-6">
		<h2>User Dashboard</h2>
	</div>
	<!--<div class="col-lg-6" align="right">
		<h2><button class="btn btn-default" name="help">Agreement</button></h2>
	</div>-->
</div>
<br>
<div class="row">
	<div class="col-lg-4">
		<div class="portlet">
			<div class="portlet_wrapper">
				<section id="least" style="width:100%;height:100%;margin:0">
					<ul class="least-gallery" style="margin:0;margin-left:-40px;">
						<li style="width:100%;margin:0;padding:0">
							<a href="/region" title="RELATED STATE REGION PAGE" data-subtitle="Find what is happening in your state region." data-caption="<strong>Bold text</strong> normal caption text" style="width:100%"> <img src="/images/worldmap.png" alt="Alt Image Text" style="width:100%" /> </a>
						</li>
					</ul>
				</section>
			</div>
		</div>
		<br>
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h4><b>Event you have joined</b></h4>
			</div>
			<div class="ibox-content inspinia-timeline">
				<div class="list-group eventlist">
					<?php foreach($events as $one) : ?>
						<a class="list-group-item event-row" href="javascript:void(0)" id="event-{{$one->type}}-{{$one->event_id}}" data-id="{{$one->event_id}}" data-type="{{$one->type}}">
							<h4 class="list-group-item-heading"><span class="text-success">{{$one->event_title}}</span></h4>
							<p class="list-group-item-text">
								{{date("F d, Y", strtotime($one->event_date))}} <span class="text-navy">{{date("h:i A", strtotime($one->event_date))}}</span>
							</p>
							<div class="pull-right" style="position:absolute;top:10px;right:10px">
								<button class="btn btn-danger btn-xs" name="withdraw" data-id="{{$one->event_id}}" data-type="{{$one->type}}">Withdraw</button>
							</div>
						</a>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-8">
		<div class="ibox">
			<div class="ibox-title" style="display:table;width:100%">
				<div class="row">
					<div class="col-lg-6">
						<h4><b>Projects I am following</b></h4>
					</div>
					<div class="col-lg-6">
						<div class="input-group">
							<input type="text" class="form-control input-sm" name="txt_following" placeholder="Search Project">
							<div class="input-group-btn">
								<button type="button" name="search_following" class="btn btn-sm btn-primary">
									Search
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="ibox-content">
				<div class="row" id="following_container">
					<?php foreach($followings as $one) : ?>
					<div class="col-lg-3">
						<div class="portlet">
							<div class="portlet_wrapper" style="position:relative">
								<div class="portlet_thumb">
									<div class="thumb_img" style="background: url('{{$one->thumbnail}}') center center"></div>
								</div>
								<div class="portlet_title">
									<a href="/dashboard/project/view/{{$one->project_type}}/{{$one->id}}"><h5 style="color: #d18022;text-align: left"><b>
										{{$one->name}}
									</b></h5></a>
								</div>
								<div style="width:100%">
									<div id="project-rating-{{$one->project_type}}-{{$one->id}}" class="rating-mark" data-score="{{$one->review}}"></div>
								</div>
								<div style="width:100%;padding-top:10px">
									<img src="/images/like.png" style="width:15px"><span>{{$one->follow_count}}</span>
									<a href="javascript:void(0)" name="unfollowing" data-id="{{$one->id}}" data-type="{{$one->project_type}}"> <span class="pull-right label label-primary">REMOVE</span></a>
								</div>
							</div>
						</div>
					</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
		<br>
		<div class="ibox">
			<div class="ibox-title" style="display:table;width:100%">
				<div class="row">
					<div class="col-lg-6">
						<h4><b>Projects I am facilitating</b></h4>
					</div>
					<div class="col-lg-6">
						<div class="input-group">
							<input type="text" class="form-control input-sm" name="txt_facilitating" placeholder="Search Project">
							<div class="input-group-btn">
								<button type="button" name="search_facilitating" class="btn btn-sm btn-primary">
									Search
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="ibox-content">
				<div class="row" id="facilitating_container">
					<?php foreach($facilitatings as $one) :?>
					<div class="col-lg-3">
						<div class="portlet">
							<div class="portlet_wrapper" style="position:relative">
								<div class="portlet_thumb">
									<div class="thumb_img" style="background: url('{{$one->thumbnail}}') center center"></div>
								</div>
								<div class="portlet_title">
									<a href="/dashboard/project/view/{{$one->project_type}}/{{$one->id}}"><h5 style="color: #d18022;text-align: left"><b>
										{{$one->name}}
									</b></h5></a>
								</div>
								<div style="width:100%">
									<div id="project-rating-{{$one->project_type}}-{{$one->id}}" class="rating-mark" data-score="{{$one->review}}"></div>
								</div>
								<div style="width:100%;padding-top:10px">
									<img src="/images/like.png" style="width:15px"><span>{{$one->follow_count}}</span>
									<span class="pull-right label label-primary" name="invite" data-name="{{$one->name}}" data-id="{{$one->id}}" data-type="{{$one->project_type}}" style="cursor: pointer">INVITE</span>
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
<div class="modal inmodal" id="modal_event" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content animated bounceInRight">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
				</button>
				<div class="row">
					<div class="col-lg-4"></div>
					<div class="col-lg-4" id="event_thumb">
						<i class="fa fa-laptop modal-icon"></i>
					</div>
					<div class="col-lg-4"></div>
				</div>
				<h4 class="modal-title" id="event_title"></h4>
				<small class="font-bold"> <label class="label label-warning">Event Time</label> <span id="event_time"></span>&nbsp;&nbsp;&nbsp; <i class="fa fa-map-marker fa-2x"></i>&nbsp;&nbsp;<span id="event_location"></span> </small>
			</div>
			<div class="modal-body">
				<h4><strong>Description:</strong></h4>
				<p id="event_description"></p>
				<h4><strong>Contact details form more information:</strong></h4>
				<h4><strong>Cost:</strong></h4>
				<p id="event_cost"></p>
				<p id="event_contact_details"></p>
				<h4><strong>Comment:</strong></h4>
				<p id="event_join_comment">
					<textarea name="event-join-comment" class="form-control" rows="5" style="resize: none"></textarea>
				</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-white" data-dismiss="modal">
					Close
				</button>
			</div>
		</div>
		<input type="hidden" name="selected_event_id" value="0">
	</div>
</div>
<div class="modal inmodal" id="modal_invite" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content animated bounceInRight">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
				</button>
				<h4 class="modal-title">Invite People to <span id="project_title">asdasdas</span></h4>
			</div>
			<div class="modal-body">
				<p id="event_join_comment">
					<div class="form-group">
						<div class="input-group" style="width:100%">
							<select name="users" data-placeholder="Choose a Person..." class="chosen-select" multiple style="width:100%;" tabindex="4">
								<?php foreach($users as $one) :?>
								<option value="{{$one->id}}">{{$one->first_name}} {{$one->last_name}}</option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
					<textarea name="invite-comment" class="form-control" rows="5" placeholder="Message:" style="resize: none"></textarea>
				</p>
			</div>
			<div class="modal-footer">
				<button type="button" name="send_invite_email" class="btn btn-primary">
					Send Email
				</button>
				<button type="button" class="btn btn-white" data-dismiss="modal">
					Close
				</button>
			</div>
		</div>
	</div>
</div>
<div class="modal inmodal" id="modal_help" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content animated flipInY">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
				</button>
				<div class="row">
					<div class="col-lg-4"></div>
					<div class="col-lg-4" id="event_thumb">
						<i class="fa fa-user modal-icon"></i>
					</div>
					<div class="col-lg-4"></div>
				</div>
				<h4 class="modal-title">Facilitator Orientation</h4>
			</div>
			<div class="modal-body">
				<p align="center">
					Approve programs that link back to a local report.<br>
					If there is no local report then approve programs that would support a generic local report<br>
					CHecks that programs are current<br>
					Disengages project that is not fulfilling their agreement 
				</p>
			</div>
			<div class="modal-footer">
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

		$("a[name='unfollowing']").click(function() {
			var $id = $(this).attr("data-id");
			var $type = $(this).attr("data-type");
			var $this = $(this);

			if (!confirm("Are you sure you wish unfollowing this project?")) {
				return;
			}

			$.post("/project/" + $type + "/unfollowing/" + $id, {}, function(response) {
				if (response.success) {
					$this.parent().parent().parent().parent().fadeOut(300);
				}
			}, "json");
		});

		$("button[name='withdraw']").click(function() {
			var $id = $(this).attr("data-id");
			var $type = $(this).attr("data-type");

			if (!confirm("Are you sure you wish withdraw joined?")) {
				return;
			}

			$.post("/ajax/project/" + $type + "/events/withdraw/" + $id, {}, function(response) {
				if (response.success) {
					$("#event-" + $type + "-" + $id).fadeOut(300);
					$("#event-" + $type + "-" + $id).remove();
					$('#modal_event').modal('hide');
				}
			}, "json");
		});

		$(".event-row").click(function(ev) {
			if(ev.target.tagName.toLowerCase() == "button") {
				return;
			}
			
			$("body").mask("");
			
			var $id = $(this).attr("data-id");
			var $type = $(this).attr("data-type");
			
			$.get("/ajax/project/" + $type + "/events/get/" + $id, {}, function(response) {
				$("body").unmask();
				if (response.success) {
					info = response.info;
					$("#modal_event input[name='selected_event_id']").val($id);
					$("#modal_event #event_title").html(info.title);
					$("#modal_event #event_location").html(info.address + ", " + info.city + ", " + info.state + " " + info.zip_code + ", " + info.country);
					$("#modal_event #event_time").html(info.event_date);
					$("#modal_event #event_description").html(info.description);
					$("#modal_event #event_cost").html("$" + info.cost);
					$("#modal_event #event_contact_details").html(info.contact_details.replace(/(?:\r\n|\r|\n)/g, "<br>"));

					if (info.thumbnail != null && info.thumbnail != "") {
						$("#modal_event #event_thumb").html("<div class='portlet'><div class='portlet_wrapper'><img src='" + info.thumbnail + "' width='100%'></div></div>");
					}

					if (response.joined) {
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

		$("input[name='txt_following']").keypress(function(ev) {
			if (ev.keyCode == 13) {
				$("button[name='search_following']").click();
			}
		});

		$("input[name='txt_facilitating']").keypress(function(ev) {
			if (ev.keyCode == 13) {
				$("button[name='search_facilitating']").click();
			}
		});

		$("button[name='search_following']").click(function() {
			$key = $("input[name='txt_following']").val();
			$("#following_container").parent().parent().mask("Please wait...");

			$.post("/ajax/projects/following", {
				key : $key
			}, function(response) {
				$("#following_container").html("");
				var data = response.result;
				for ( i = 0; i < data.length; i++) {
					row = '<div class="col-lg-2">' + '<div class="portlet">' + '	<div class="portlet_wrapper" style="position:relative">' + '		<div class="portlet_thumb">' + '			<div class="thumb_img" style="background: url(\'' + data[i].thumbnail + '\') center center"></div>' + '		</div>' + '		<div class="portlet_title">' + '			<a href="/dashboard/project/view/' + data[i].project_type + '/' + data[i].id + '"><h5 style="color: #d18022;text-align: left"><b>' + data[i].name + '</b></h5></a>' + '		</div>' + '		<div style="width:100%">' + '			<div id="project-rating-' + data[i].project_type + '-' + data[i].id + '" class="rating-mark" data-score="' + data[i].review + '"></div>' + '		</div>' + '		<div style="width:100%;padding-top:10px">' + '			<img src="/images/like.png" style="width:15px"><span>' + data[i].follow_count + '</span>' + '			<a href="javascript:void(0)" name="unfollowing" data-id="' + data[i].id + '" data-type="' + data[i].project_type + '">' + '				<span class="pull-right label label-primary">REMOVE</span></a>' + '		</div>' + '	</div>' + '</div>' + '</div>';
					$("#following_container").append(row);
				}

				$(".rating-mark").each(function() {
					score = $(this).attr("data-score");
					$(this).raty({
						readOnly : true,
						score : score
					});
				});

				$("a[name='unfollowing']").click(function() {
					var $id = $(this).attr("data-id");
					var $type = $(this).attr("data-type");
					var $this = $(this);

					if (!confirm("Are you sure you wish unfollowing this project?")) {
						return;
					}

					$.post("/project/" + $type + "/unfollowing/" + $id, {}, function(response) {
						if (response.success) {
							$this.parent().parent().parent().parent().fadeOut(300);
						}
					}, "json");
				});

				$("#following_container").parent().parent().unmask();
			}, "json");
		});

		$("button[name='search_facilitating']").click(function() {
			$key = $("input[name='txt_facilitating']").val();
			$("#facilitating_container").parent().parent().mask("Please wait...");

			$.post("/ajax/projects/facilitating", {
				key : $key
			}, function(response) {
				$("#facilitating_container").html("");
				var data = response.result;

				for ( i = 0; i < data.length; i++) {
					row = '<div class="col-lg-2">' + '<div class="portlet">' + '	<div class="portlet_wrapper" style="position:relative">' + '		<div class="portlet_thumb">' + '			<div class="thumb_img" style="background: url(\'' + data[i].thumbnail + '\') center center"></div>' + '		</div>' + '		<div class="portlet_title">' + '			<a href="/dashboard/project/view/' + data[i].project_type + '/' + data[i].id + '"><h5 style="color: #d18022;text-align: left"><b>' + data[i].name + '</b></h5></a>' + '		</div>' + '		<div style="width:100%">' + '			<div id="project-rating-' + data[i].project_type + '-' + data[i].id + '" class="rating-mark" data-score="' + data[i].review + '"></div>' + '		</div>' + '		<div style="width:100%;padding-top:10px">' + '			<img src="/images/like.png" style="width:15px"><span>' + data[i].follow_count + '</span>' + '			<span class="pull-right label label-primary" name="send_invite" data-id="' + data[i].id + '" data-type="' + data[i].project_type + '" style="cursor: pointer">INVITE</span>' + '		</div>' + '	</div>' + '</div>' + '</div>';
					$("#facilitating_container").append(row);
				}

				$("span[name='send_invite']").click(function() {
					var $id = $(this).attr("data-id");
					var $type = $(this).attr("data-type");

					$.post("/ajax/project/" + $type + "/" + $id + "/invite", {}, function(response) {
						if (response.success) {
							alert("You invited on a project successfully.");
						}
					}, "json");
				});

				$(".rating-mark").each(function() {
					score = $(this).attr("data-score");
					$(this).raty({
						readOnly : true,
						score : score
					});
				});

				$("#facilitating_container").parent().parent().unmask();
			}, "json");
		});

		$(".chosen-select").chosen({
			width : "100%"
		});
		
		var $invite_project_id = 0;
		var $invite_project_type = "";
		
		$("span[name='invite']").click(function() {
			$invite_project_id = $(this).attr("data-id");
			$invite_project_type = $(this).attr("data-type");
			var $name = $(this).attr("data-name");

			$("#project_title").html($name);
			$("#modal_invite").modal();
			/*
			 $.post("/ajax/project/"+$type+"/"+$id+"/invite", {}, function(response) {
			 if(response.success) {
			 alert("You invited on a project successfully.");
			 }
			 }, "json");
			 */
		});

		$("button[name='send_invite_email']").click(function() {
			var $ids = "";
			$("select[name='users'] option:selected").each(function() {
				$ids += $ids == "" ? $(this).val() : "," + $(this).val()
			});

			if ($ids == "") {
				alert("please select peoples.");
				return;
			}
			if ($("textarea[name='invite-comment']").val() == "") {
				$("textarea[name='invite-comment']").focus();
				return;
			}
			
			$.post("/ajax/project/" + $invite_project_type + "/" + $invite_project_id + "/invite", {
				user_ids : $ids,
				message : $("textarea[name='invite-comment']").val()
			}, function(response) {
				if (response.success) {
					alert("You have sent invitation email on a project successfully.");
					$('#modal_invite').modal('hide');
					$(".chosen-select").val('').trigger('chosen:updated');
					$("textarea[name='invite-comment']").val("");
					$invite_project_id = 0;
					$invite_project_type = "";
				}
			}, "json");
		});
		
		$("button[name='help']").click(function() {
			$("#modal_help").modal();
		});
		
		resize_portlet();
		
		function resize_portlet() {
			$(".portlet").each(function() {
				width = $(this).parent().width();
				$(this).find(".portlet_title h5").css({
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
