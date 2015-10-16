@extends('layout.general_dashboard')
@section('content')
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true&libraries=places"></script>
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-12">
		<h2><?php echo $project_title?>
		- Event Form</h2>
		<ol class="breadcrumb">
			<li>
				<a href="/{{$active=='manages'?'manages/':''}}projects/impact">Projects</a>
			</li>
			<li>
				<a href="/{{$active=='manages'?'manages/':''}}projects/impact/<?php echo $project_id?>/events">Events</a>
			</li>
			<li class="active">
				<strong>Edit</strong>
			</li>
		</ol>
	</div>
</div>
<div class="wrapper wrapper-content  animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<?php if($message) :?>
			<div class="row">
				<div class="col-lg-1"></div>
				<div class="col-lg-10">
					<?php echo $message?>
				</div>
				<div class="col-lg-1"></div>
			</div>
			<?php endif; ?>
			<div class="ibox">
				<div class="ibox-content">
					<form name="frmEvent" method="post" class="form-horizontal" enctype="multipart/form-data">
						<div class="form-group">
							<label for="title" class="col-sm-4 control-label">Name of Event</label>
							<div class="col-sm-6">
								<input type="text" name="title" id="title" value="{{$info->title}}" class="form-control">
								<label id="-error" class="error" for="" style="display:none">This field is required.</label>
							</div>
						</div>
						<div class="form-group">
							<label for="address" class="col-sm-4 control-label">Where</label>
							<div class="col-sm-6">
								<input type="text" name="google_autocomplete" id="google_autocomplete" value="" placeholder="Address: " class="form-control" ononFocus="geolocate()">
								<input type="text" name="address" id="address" value="{{$info->address}}" placeholder="" class="form-control" {{$info->address==""?"":"readonly"}}>
								<label id="-error" class="error" for="" style="display:none">This field is required.</label>
								<input type="text" name="city" id="city" value="{{$info->city}}" placeholder="City:" class="form-control" {{$info->city==""?"":"readonly"}}>
								<input type="text" name="state" id="state" value="{{$info->state}}" placeholder="State:" class="form-control" {{$info->state==""?"":"readonly"}}>
								<input type="text" name="zip_code" id="zip_code" value="{{$info->zip_code}}" placeholder="Zip Code:" class="form-control" {{$info->zip_code==""?"":"readonly"}}>
								<input type="text" name="country" id="country" value="{{$info->country}}" placeholder="Country:" class="form-control" {{$info->country==""?"":"readonly"}}>
								<input type="hidden" name="longitude" id="longitude" value="{{$info->longitude}}">
								<input type="hidden" name="latitude" id="latitude" value="{{$info->latitude}}">
							</div>
						</div>
						<div class="form-group">
							<label for="event_date" class="col-sm-4 control-label">When</label>
							<div class="col-sm-6">
								<div class="input-group date">
									<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
									<input type="text" name="event_date" id="event_date" value="{{$info->event_date}}" class="form-control datepick" placeholder="YYYY-MM-DD" style="width:120px;text-align: center">
									<input type="text" name="event_hour" value="{{$info->event_hour}}" placeholder="00" class="form-control timepick" style="width:45px;text-align: center">
									<input type="text" name="event_minute" value="{{$info->event_minute}}" placeholder="00" class="form-control timepick" style="width:45px;text-align: center">
								</div>
								<label id="-error" class="error" for="" style="display:none">This field is required.</label>
							</div>
						</div>
						<div class="form-group">
							<label for="description" class="col-sm-4 control-label">What is the event about</label>
							<div class="col-sm-6">
								<textarea name="description" id="description" class="form-control" rows="5">{{$info->description}}</textarea>
								<label id="-error" class="error" for="" style="display:none">This field is required.</label>
							</div>
						</div>
						<div class="form-group">
							<label for="thumbnail" class="col-sm-4 control-label">Upload Image</label>
							<div class="col-sm-6">
								<input type="file" name="thumbnail" id="thumbnail" class="form-control" accept="image/*">
								<?php if($info->thumbnail != "") :
								?>
								<a href="{{$info->thumbnail}}" target="_blank">View Image</a>
								<?php endif; ?>
							</div>
						</div>
						<div class="form-group">
							<label for="cost" class="col-sm-4 control-label">Cost</label>
							<div class="col-sm-6">
								<div class="input-group m-b">
									<span class="input-group-addon">$</span>
									<input type="text" name="cost" id="cost" value="{{$info->cost*1}}" class="form-control">
									<span class="input-group-addon">.00</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="contact_details" class="col-sm-4 control-label"> Contract person for more Information
								<br>
								<small class="text-navy">Name and Phone number </small> </label>
							<div class="col-sm-6">
								<textarea name="contact_details" id="contact_details" value="{{$info->contact_details}}" class="form-control" rows="8">{{$info->contact_details}}</textarea>
							</div>
						</div>
						<div class="hr-line-dashed"></div>
						<div class="form-group">
							<div class="col-sm-4 col-sm-offset-4">
								<button class="btn btn-primary" type="submit" name="save">
									Save changes
								</button>
								<a href="/{{$active=='manages'?'manages/':''}}projects/impact/{{$project_id}}/events">
								<button class="btn btn-default" type="button" name="cancel">
									Cancel
								</button> </a>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function() {
		$('.datepick').datepicker({
			todayBtn : "linked",
			keyboardNavigation : false,
			forceParse : false,
			calendarWeeks : true,
			autoclose : true,
			format : 'yyyy-mm-dd'
		});

		$("form[name='frmEvent']").submit(function() {
			flag = true;
			$(".error").hide();
			if ($("input[name='title']").val() == "") {
				$("input[name='title']").parent().find(".error").show();
				flag = false;
			}
			if ($("input[name='address']").val() == "") {
				$("input[name='address']").parent().find(".error").show();
				flag = false;
			}
			if ($("input[name='event_date']").val() == "") {
				$("input[name='event_date']").parent().parent().find(".error").show();
				flag = false;
			}
			if ($("input[name='event_hour']").val() == "") {
				$("input[name='event_hour']").parent().parent().find(".error").show();
				flag = false;
			}
			if ($("input[name='event_minute']").val() == "") {
				$("input[name='event_minute']").parent().parent().find(".error").show();
				flag = false;
			}
			if ($("textarea[name='description']").val() == "") {
				$("textarea[name='description']").parent().find(".error").show();
				flag = false;
			}
			if ($("input[name='longitude']").val() == "" || $("input[name='longitude']").val() == 0) {
				alert("Wrong address!");
				return false;
			}
			if ($("input[name='latitude']").val() == "" || $("input[name='latitude']").val() == 0) {
				alert("Wrong address!");
				return false;
			}

			if (!flag) {
				$(document).scrollTop(0);
			}

			return flag;
		});

		var placeSearch, autocomplete;
		var componentForm = {
			street_number : 'short_name',
			route : 'long_name',
			locality : 'long_name',
			administrative_area_level_1 : 'short_name',
			country : 'long_name',
			postal_code : 'short_name'
		};

		function initialize() {
			// Create the autocomplete object, restricting the search
			// to geographical location types.
			autocomplete = new google.maps.places.Autocomplete(
			/** @type {HTMLInputElement} */(document.getElementById('google_autocomplete')), {
				types : ['geocode']
			});
			// When the user selects an address from the dropdown,
			// populate the address fields in the form.
			google.maps.event.addListener(autocomplete, 'place_changed', function() {
				fillInAddress();
			});
		}

		function fillInAddress() {
			// Get the place details from the autocomplete object.
			var place = autocomplete.getPlace();
			var latitude = place.geometry.location.lat();
			var longitude = place.geometry.location.lng();

			$("#longitude").val(longitude);
			$("#latitude").val(latitude);

			$("#address").val("");
			$("#city").val("");
			$("#state").val("");
			$("#country").val("");
			$("#zip_code").val("");

			// Get each component of the address from the place details
			// and fill the corresponding field on the form.
			for (var i = 0; i < place.address_components.length; i++) {
				var addressType = place.address_components[i].types[0];
				if (componentForm[addressType]) {
					var val = place.address_components[i][componentForm[addressType]];
					console.log(addressType + " : " + val);

					if (addressType == "street_number") {
						$("#address").val(val);
					} else if (addressType == "route") {
						prev = $("#address").val();
						$("#address").val(prev == "" ? val : prev + " " + val);
					} else if (addressType == "locality") {
						$("#city").val(val);
					} else if (addressType == "administrative_area_level_1") {
						$("#state").val(val);
					} else if (addressType == "country") {
						$("#country").val(val);
					} else if (addressType == "postal_code") {
						$("#zip_code").val(val);
					}
				}
			}
			
			_active();
		}

		function geolocate() {
			if (navigator.geolocation) {
				navigator.geolocation.getCurrentPosition(function(position) {
					var geolocation = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
					var circle = new google.maps.Circle({
						center : geolocation,
						radius : position.coords.accuracy
					});
					autocomplete.setBounds(circle.getBounds());
				});
			}
		}

		initialize();
		
		function _active() {
			if($("#address").val() != "") {
				$("#address").prop("readonly", true);
			} else {
				$("#address").prop("readonly", false);
			}

			if($("#city").val() != "") {
				$("#city").prop("readonly", true);
			} else {
				$("#city").prop("readonly", false);
			}

			if($("#state").val() != "") {
				$("#state").prop("readonly", true);
			} else {
				$("#state").prop("readonly", false);
			}

			if($("#zip_code").val() != "") {
				$("#zip_code").prop("readonly", true);
			} else {
				$("#zip_code").prop("readonly", false);
			}

			if($("#country").val() != "") {
				$("#country").prop("readonly", true);
			} else {
				$("#country").prop("readonly", false);
			}
		}
	}); 
</script>
@stop