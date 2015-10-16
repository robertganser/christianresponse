@extends('layout.region')
@section('content')

<div class="mainbar" style="min-height:600px">
	<div class="article">
		<?php if(Session::get("message") != "") :?>
			{{Session::get("message")}}
		<?php endif;?>
		<h2>{{$region_info->title}}</h2>
		<span>{{$region_info->country}}, {{$region_info->state}}</span>
		<?php if($region_info->intro_video != "") :?>
			<div style="display:table;width:100%">
				<embed width="100%" height="350" src="https://www.youtube.com/embed/{{$region_info->intro_video}}">
			</div>
		<?php endif;?>
		<br>
		<?php if(count($events) > 0) :?>
			<div id="map" style="width:100%;height:350px;"></div>
		<?php endif;?>
	</div>
</div>
<script>
	$(document).ready(function() {
		var map = new google.maps.Map(document.getElementById('map'), {
			mapTypeId : google.maps.MapTypeId.TERRAIN
		});
	
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
	}); 
</script>
<?php Session::set("message", "")?>
@stop