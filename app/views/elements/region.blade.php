<script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>
<script src="/js/dashboard/plugins/calendar/responsive-calendar.js" type="text/javascript"></script>
<script src="/js/dashboard/plugins/chosen/chosen.jquery.js" type="text/javascript"></script>
<link href="/css/dashboard/plugins/calendar/responsive-calendar.css" rel="stylesheet">
<link href="/css/dashboard/plugins/chosen/chosen.css" rel="stylesheet">

<script>
	var locations = new Array();
	var bounds = new google.maps.LatLngBounds(); 
	var events = {};
	
	<?php foreach($month_events as $one) :?>
		events["{{$one->event_date}}"] = {"number" : {{$one->ev}}};
	<?php endforeach;?>
</script>
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-6">
		<h2>Related Region</h2>
	</div>
	<div class="col-lg-6">
		<div class="row">
			<div class="form-group" style="margin-top:20px">
				<label for="name" class="col-sm-3 control-label" style="text-align:right">State regions:</label>
				<div class="col-sm-9">
					<select name="region_page" class="form-control chosen-select" data-placeholder="Choose a Region...">
						<?php foreach($regions as $one) :?>
							<option value="{{$one->region_id}}" {{$region_id == $one->region_id ? "selected" : ""}}>{{$one->title}}</option>
						<?php endforeach; ?>
					</select>
				</div>
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
<br>
<div class="row">
	<div class="col-lg-8">
		<div class="ibox">
			<div class="ibox-content inspinia-timeline">
				<?php if(Session::get("error") != "") :?>
					{{Session::get("error")}}
				<?php endif;?>
				<div class="row">
					<div class="col-lg-4" align="cener">
						<div style="display:table;width:100%;padding-right:10px">
							<form name="frmShare" method="POST">
								<div class="row" style="margin-bottom: 4px">
									<div class="col-md-12" align="right">
										Share link via:&nbsp;&nbsp;
										<a href="https://www.facebook.com/dialog/feed?app_id={{Config::get('facebook.app_id')}}&picture={{$picture}}&redirect_uri={{$redirect_url}}&link={{$share_link}}&caption={{$title}}&description={{$location}}" style="width:100%;margin-top:2px" target="_blank">
											<img src="/images/share-facebook.png" style="width:20px">
										</a>
										<a href="https://plus.google.com/share?url={{$share_link}}" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;">
											<img src="/images/share-google.png" style="width:20px">
										</a>
									</div>
								</div>
								<input type="hidden" name="share_link" id="share_link" value="{{$share_link}}">
							</form>
						</div>
						<div class="row">
							<div class="col-lg-12" align="cener">
								<a href="javascript:void(0)" name="donation_region">
									<img src="/images/donation-region.png" style="width:100%">
								</a>
							</div>
						</div><br>
						<?php if($video != ""):?>
						<embed width="100%" height="260" src="http://www.youtube.com/v/{{$video}}"></embed><br>
						<?php endif; ?>
						<?php if($related_report_id > 0) :?>
							<br>
							<div class="row">
								<div class="col-lg-12">
									<a class="btn btn-info" href="/search/project/regionalreport/view/{{$related_report_id}}" style="width:100%">
										View State Regional Report
									</a>
								</div>
							</div>
						<?php endif;?>
						<?php if(count($annual_event) > 0) :?>
							<br>
							<div class="row">
								<div class="col-lg-12">
									<button class="btn btn-warning" name="view_annual_event" data-id="{{$annual_event[0]->id}}" style="width:100%">
										View State Regional Event
									</button>
								</div>
							</div>
							<script>
								rows = ['<b>{{$annual_event[0]->title}}</b><br>- Regional Event -<br>{{$annual_event[0]->address}}, {{$annual_event[0]->city}}, {{$annual_event[0]->state}} {{$annual_event[0]->zip_code}}, {{$annual_event[0]->country}}<br>{{date("F d, Y h:i A", strtotime($annual_event[0]->event_date))}}', {{$annual_event[0]->latitude}}, {{$annual_event[0]->longitude}}, 100];
								locations.push(rows);
								bounds.extend(new google.maps.LatLng({{$annual_event[0]->latitude}}, {{$annual_event[0]->longitude}}));
							</script>
						<?php endif;?>
						<?php if($memo != "") :?>
							<br>
							<div class="row">
								<div class="col-lg-12">
									<button class="btn btn-default" name="view_memo" style="width:100%">State News and needs</button>
									<div class="modal inmodal" id="modal_memo" tabindex="-1" role="dialog" aria-hidden="true">
										<div class="modal-dialog">
											<div class="modal-content animated flipInY">
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
													<h4 class="modal-title">Region Memo</h4>
												</div>
												<div class="modal-body">
													<h4><strong>Description:</strong></h4>
													<p>{{str_replace("\r\n", "<br>", $memo)}}</p>
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-white" data-dismiss="modal">
														Close
													</button>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						<?php endif;?>
					</div>
					<div class="col-lg-1"></div>
					<div class="col-lg-7">
						<h2><span itemprop="name">{{$title}}</span></h2>
						<img itemprop="image" src="/images/facebook/global_compact-icon-1365540187.png" style="position:fixed; left:-1000px;">
						<br>
						<div id="map" style="width:100%;height:350px;"></div>
						<br>
						<div class="portlet">
							<div class="portlet_wrapper">
								<div class="responsive-calendar">
							        <div class="controls">
										<a class="pull-left" data-go="prev"><div class="btn btn-primary">Prev</div></a>
										<h4><span data-head-year></span> <span data-head-month></span></h4>
										<a class="pull-right" data-go="next"><div class="btn btn-primary">Next</div></a>
							        </div><hr/>
							        <div class="day-headers">
										<div class="day header">Mon</div>
										<div class="day header">Tue</div>
										<div class="day header">Wed</div>
										<div class="day header">Thu</div>
										<div class="day header">Fri</div>
										<div class="day header">Sat</div>
										<div class="day header">Sun</div>
							        </div>
							        <div class="days" data-group="days"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-4">
		<div class="ibox">
			<div class="ibox-title">
				<h4><b>Related Region Event</b></h4>
			</div>
			<div class="ibox-content inspinia-timeline">
				<div class="list-group eventlist">
					<?php $i = 1; foreach($events as $one) : ?>
						<a class="list-group-item event-row" href="javascript:void(0)" id="event-{{$one->type}}-{{$one->event_id}}" data-id="{{$one->event_id}}" data-type="{{$one->type}}">
                            <h4 class="list-group-item-heading"><span class="text-success">{{$one->event_title}}</span></h4>
                            <p class="list-group-item-text">
                            	{{date("F d, Y", strtotime($one->event_date))}} <span class="text-navy">{{date("h:i A", strtotime($one->event_date))}}</span>
                            </p>
                            <?php if($one->is_joined == 1) :?>
	                            <div class="pull-right" style="position:absolute;top:10px;right:10px">
	                            	<button class="btn btn-danger btn-xs" name="withdraw" data-id="{{$one->event_id}}" data-type="{{$one->type}}">Withdraw</button>
	                            </div>
	                        <?php endif;?>
                        </a>
						<script>
							rows = ['<b>{{$one->event_title}}</b><br>- {{$one->project_title}} -<br>{{$one->address}}, {{$one->city}}, {{$one->state}} {{$one->zip_code}}, {{$one->country}}<br>{{date("F d, Y h:i A", strtotime($one->event_date))}}', {{$one->latitude}}, {{$one->longitude}}, {{$i}}];
							locations.push(rows);
							bounds.extend(new google.maps.LatLng({{$one->latitude}}, {{$one->longitude}}));
						</script>
					<?php $i++; endforeach; ?>
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
				<h4><strong>Cost:</strong></h4>
				<p id="event_cost"></p>
				<h4><strong>Contact details form more information:</strong></h4>
				<p id="event_contact_details"></p>
				<h4><strong>Comment:</strong></h4>
				<p id="event_join_comment">
					<form name="frmEvent" method="POST">
						<textarea name="event-join-comment" class="form-control" rows="5" style="resize: none"></textarea>
						<input type="hidden" name="selected_project_id" value="0">
						<input type="hidden" name="selected_event_id" value="0">
						<input type="hidden" name="selected_event_type" value="">
						<input type="hidden" name="action" value="join">
					</form>
				</p>
			</div>
			<div class="modal-footer" style="text-align: left">
				<div class="pull-left">
					<button type="button" class="btn btn-info" name="modal-project-link">
						Go to Project
					</button>
				</div>
				<div class="pull-right">
					<button type="button" class="btn btn-danger" name="modal-withdraw">
						Withdraw
					</button>
					<button type="button" class="btn btn-primary" name="modal-join" style="width:180px">
						Join to Event
					</button>
					<button type="button" class="btn btn-white" data-dismiss="modal">
						Close
					</button>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function() {
		$(".chosen-select").chosen();
		
		$(".responsive-calendar").responsiveCalendar({
			time: '{{$curr_date}}',
			events : events,
			onDayClick: function(events, d, m, y) { 
				var $date = y + "-" + (m > 9 ? m : "0" + m) + "-" + (d > 9 ? d : "0" + d);
				var $region_id = $("select[name='region_page']").val();

				if ($date == "") {
					$date = "{{$curr_date}}";
				}
	
				if ($region_id == 0) {
					location.href = "/region/" + $date;
				} else {
					location.href = "/region/" + $date + "/" + $region_id;
				}
			}
        });
        
		$("select[name='region_page']").change(function() {
			var $region_id = $(this).val();

			if ($region_id == 0) {
				location.href = "/region/{{$curr_date}}";
			} else {
				if ("{{$curr_date}}" == "") {
					location.href = "/region/{{date('Y-m-d')}}/" + $region_id;
				} else {
					location.href = "/region/{{$curr_date}}/" + $region_id;
				}
			}
		});

		$("#datepicker").on("changeDate", function(event) {
			var $date = $("#datepicker").datepicker('getFormattedDate');
			var $region_id = $("select[name='region_page']").val();

			if ($date == "") {
				$date = "{{$curr_date}}";
			}

			if ($region_id == 0) {
				location.href = "/region/" + $date;
			} else {
				location.href = "/region/" + $date + "/" + $region_id;
			}
		});

		$(".rating-mark").each(function() {
			score = $(this).attr("data-score");
			$(this).raty({
				readOnly : true,
				score : score
			});
		});
		
		$("button[name='view_memo']").click(function() {
			$("#modal_memo").modal();
		});
		
		$("button[name='view_annual_event']").click(function() {
			var $id = $(this).attr("data-id");
			var $type = "annual";
			
			$("button[name='modal-project-link']").hide();
			
			$.get("/ajax/project/" + $type + "/events/get/" + $id, {}, function(response) {
				if (response.success) {
					info = response.info;
					$("#modal_event input[name='selected_project_id']").val({{$region_id}});
					$("#modal_event input[name='selected_event_id']").val($id);
					$("#modal_event input[name='selected_event_type']").val($type);

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
		
		$(".event-row").click(function(ev) {
			if (ev.target.tagName.toLowerCase() == "button") {
				return;
			}
			
			$("body").mask("");
			
			var $id = $(this).attr("data-id");
			var $type = $(this).attr("data-type");
			
			$("button[name='modal-project-link']").show();
			
			$.get("/ajax/project/" + $type + "/events/get/" + $id, {}, function(response) {
				$("body").unmask();
				if (response.success) {
					info = response.info;
					$("#modal_event input[name='selected_project_id']").val(info.project_id);
					$("#modal_event input[name='selected_event_id']").val($id);
					$("#modal_event input[name='selected_event_type']").val($type);

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
		
		$("button[name='modal-project-link']").click(function() {
			var $id = $("#modal_event input[name='selected_project_id']").val();
			var $type = $("#modal_event input[name='selected_event_type']").val();
			
			location.href = "/search/project/"+$type+"/view/"+$id;
		});
		
		$("button[name='modal-join']").click(function() {
			$("form[name='frmEvent']").submit();
			/*
			var $id = $("#modal_event input[name='selected_event_id']").val();
			var $type = $("#modal_event input[name='selected_event_type']").val();
			
			$.post("/ajax/project/" + $type + "/events/join/" + $id, {
				comment : $("textarea[name='event-join-comment']").val()
			}, function(response) {
				if (response.success) {
					$("#event-" + $type + "-" + $id).append('<div class="pull-right" style="position:absolute;top:10px;right:10px">' + 
								                            	'<span class="btn btn-danger btn-xs" name="withdraw" data-id="' + $id + '" data-type="' + $type + '">Withdraw</span>' + 
								                            '</div>');
					$('#modal_event').modal('hide');

					$("span[name='withdraw']").click(function() {
						var $id_new = $(this).attr("data-id");
						var $type_new = $(this).attr("data-type");
						if (!confirm("Are you sure you wish withdraw joined?")) {
							return;
						}

						$.post("/ajax/project/" + $type_new + "/events/withdraw/" + $id_new, {}, function(response) {
							if (response.success) {
								$("#event-" + $type_new + "-" + $id_new + " .pull-right").remove();
							}
						}, "json");
					});
				}
			}, "json");
			*/
		});
		
		$("button[name='withdraw']").click(function() {
			var $id = $(this).attr("data-id");
			var $type = $(this).attr("data-type");
			if (!confirm("Are you sure you wish withdraw joined?")) {
				return;
			}
			
			$.post("/ajax/project/" + $type + "/events/withdraw/" + $id, {}, function(response) {
				if (response.success) {
					$("#event-" + $type + "-" + $id + " .pull-right").remove();
				}
			}, "json");
		});

		$("button[name='modal-withdraw']").click(function() {
			var $id = $("#modal_event input[name='selected_event_id']").val();
			var $type = $("#modal_event input[name='selected_event_type']").val();

			$.post("/ajax/project/" + $type + "/events/withdraw/" + $id, {}, function(response) {
				if (response.success) {
					$("#event-" + $type + "-" + $id + " .pull-right").remove();
					$('#modal_event').modal('hide');
				}
			}, "json");
		});
		
		$("a[name='donation_region']").click(function() {
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

	//var locations = [['<b>Event Title</b><br>- Project Title -<br>10 William Street, William Street, Sydney, New South Wales, Australia<br>2015-04-20 17:50', -33.890542, 151.274856, 4], ['<b>Event Title</b><br>- Project Title -<br>100 Lawson Street, Paddington, New South Wales, Australia<br>2015-04-20 17:50', -33.923036, 151.259052, 5], ['<b>Event Title</b><br>- Project Title -<br>10 William Street, William Street, Sydney, New South Wales, Australia<br>2015-04-20 17:50', -34.028249, 151.157507, 3], ['<b>Event Title</b><br>- Project Title -<br>100 Lawson Street, Paddington, New South Wales, Australia<br>2015-04-20 17:50', -33.80010128657071, 151.28747820854187, 2], ['<b>Event Title</b><br>- Project Title -<br>10 William Street, William Street, Sydney, New South Wales, Australia<br>2015-04-20 17:50', -33.950198, 151.259302, 1]];
	/*
	var map = new google.maps.Map(document.getElementById('map'), {
		mapTypeId : google.maps.MapTypeId.TERRAIN
	});
	*/
	var map = new google.maps.Map(document.getElementById('map'));

	map.fitBounds(bounds);

	var infowindow = new google.maps.InfoWindow();

	var marker, i;

	for ( i = 0; i < locations.length; i++) {
		marker = new google.maps.Marker({
			position : new google.maps.LatLng(locations[i][1], locations[i][2]),
			map : map
		});

		google.maps.event.addListener(marker, 'click', (function(marker, i) {
			return function() {
				infowindow.setContent(locations[i][0]);
				infowindow.open(map, marker);
			}
		})(marker, i));
	}
</script>
<?php Session::set("error", ""); ?>