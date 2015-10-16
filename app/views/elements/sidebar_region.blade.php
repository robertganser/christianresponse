<script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>
<script src="/js/jquery.least.js"></script>
<link href="/css/jquery.least.css" rel="stylesheet" type="text/css" />
<script>
	var locations = new Array();
	var bounds = new google.maps.LatLngBounds(); 
</script>
<style>
	address {
		font-size: 10px;
	}
</style>
<br><br>
<div class="sidebar">
	<!--<div class="gadget">
		<a href="/share/region/{{$param}}/step" name="show_memo"><img src="/images/donation-region.png" style="width:100%;border:0px"></a>
	</div>-->
	<?php if($region_info->memo != "") :?>
		<a href="#" name="show_memo"><img src="/images/b220f6270a0d028a01fbfbf95b8a3c09.png" style="width:100%;border:0px"></a>
		<div id="modal_memo" class="lightbox">
			<div style="padding: 20px">
				<div style="width:100%">
					{{$region_info->memo}}
				</div>
			</div>
		</div>
	<?php endif;?>
	<?php if(count($annual_event) > 0) :?>
		<div class="gadget">
			<h2 class="star">Region Event</h2>
			<ul class="ex_menu events">
				<li>
					<a href="javascript:void(0)" data-id="{{$annual_event[0]->id}}" data-type="annual">{{$annual_event[0]->title}}</a>
					<br />
					<address>{{$annual_event[0]->address}}, {{$annual_event[0]->city}}, {{$annual_event[0]->zip_code}}</address>
				</li>
			</ul>
		</div>
	<?php endif;?>
	<?php if(count($events) > 0) :?>
		<div class="gadget">
			<h2 class="star">Related Event</h2>
			<ul class="ex_menu events">
				<?php $i = 1; foreach($events as $one) :?>
					<li>
						<a href="javascript:void(0)" title="{{$one->project_title}}" data-id="{{$one->event_id}}" data-type="{{$one->type}}">{{$one->event_title}}</a>
						<br />
						<address>{{$one->address}}, {{$one->city}}, {{$one->zip_code}}</address>
					</li>
					<script>
						rows = ['<b>{{$one->event_title}}</b><br>- {{$one->project_title}} -<br>{{$one->address}}, {{$one->city}}, {{$one->state}} {{$one->zip_code}}, {{$one->country}}<br>{{$one->event_date}}', {{$one->latitude}}, {{$one->longitude}}, {{$i}}];
						locations.push(rows);
						bounds.extend(new google.maps.LatLng({{$one->latitude}}, {{$one->longitude}}));
					</script>
				<?php $i ++; endforeach;?>
			</ul>
		</div>
	<?php endif;?>
	<?php if($region_info->related_report > 0) :?>
		<div class="gadget">
			<h2 class="star">Related Report</h2>
			<ul class="ex_menu">
				<li>
					<a href="/project/view/regionalreport/{{$related_report[0]->id}}">{{$related_report[0]->name}}</a>
					<br />
					<address>{{$related_report[0]->address}}, {{$related_report[0]->city}}, {{$related_report[0]->zip_code}}</address>
				</li>
			</ul>
		</div>
	<?php endif;?>
</div>
<div id="modal_event" class="lightbox">
	<div style="padding: 10px">
		<div class="row">
			<div class="col-md-12">
				<br>
				<div class="portlet" id="event_thumb" style="width:250px;margin:0 auto"></div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<h2 align="center"><div id="event_title" align="center"></div></h2>
				<h3 align="center"><img src="/images/map_icon.png" style="width:20px"><span id="event_location"></span></h3>
				<div style="padding:10px;background: #f8fafb;border-top:1px solid #f0f0f0">
					<h3>Event Time: <span id="event_date"></span></h3>
					<h4>Cost: <span id="event_cost"></span></h4>
					<b>Description:</b>
					<p id="event_description"></p>
					<br>
					<b>Contact details for more information:</b>
					<p id="event_contact_details"></p>
					<br>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
$(document).ready(function() {
	$(".events li a").click(function() {
		var $id = $(this).attr("data-id");
		var $type = $(this).attr("data-type");

		$.get("/ajax/project/"+$type+"/events/get/"+$id, {}, function(response) {
			if(response.success) {
				info = response.info;
				console.log(info);
				$("#modal_event #event_title").html(info.title);
				$("#modal_event #event_location").html(info.address + ", " + info.city + ", " + info.state + " " + info.zip_code + ", " + info.country);
				$("#modal_event #event_date").html(info.event_date);
				$("#modal_event #event_description").html(info.description);
				$("#modal_event #event_cost").html("$" + info.cost);
				$("#modal_event #event_contact_details").html(info.contact_details.replace(/(?:\r\n|\r|\n)/g, "<br>"));
				
				if(info.thumbnail != null && info.thumbnail != "") {
					$("#modal_event #event_thumb").html("<img src='" + info.thumbnail + "' width='250px'>");
				}
				
				$("#modal_event").lightbox_me();
			}
		}, "json");
		
	});
	
	$("a[name='show_memo']").click(function() {
		$("#modal_memo").lightbox_me();
	});
});
</script>