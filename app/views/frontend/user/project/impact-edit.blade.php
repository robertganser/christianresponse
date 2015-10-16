@extends('layout.user_dashboard')
@section('content')
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true&libraries=places"></script>
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-10">
		<h2>Edit Impact Project Form</h2>
		<ol class="breadcrumb">
			<li>
				<a href="/projects/impact">Projects</a>
			</li>
			<li>
				<a href="/projects/impact">Impact</a>
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
			<div class="row">
				<div class="col-lg-1"></div>
				<div class="col-lg-10">
					<h3><b>Introduction:</b></h3>
					<p>
						Public impact meetings can have different focus. It may involve a time of worship. Public connection with God builds peoples faith and brings encouragement. It is hoped that in your region there is always a impact meeting you can attend morning afternoon and night.
					</p>
				</div>
				<div class="col-lg-1"></div>
			</div>
			<?php if($message) :?>
				<div class="row">
					<div class="col-lg-1"></div>
					<div class="col-lg-10">
						<?php echo $message?>
					</div>
					<div class="col-lg-1"></div>
				</div>
			<?php endif;?>
			<div class="ibox">
				<div class="ibox-content">
					<form name="frmProjectEdit" method="post" class="form-horizontal" enctype="multipart/form-data">
						<div class="form-group">
							<label for="name" class="col-sm-4 control-label">Name of extension</label>
							<div class="col-sm-5">
								<input type="text" name="name" id="name" value="{{$info->name}}" class="form-control">
								<label id="-error" class="error" for="" style="display:none">This field is required.</label>
							</div>
						</div>
						<div class="form-group">
							<label for="google_autocomplete" class="col-sm-4 control-label">Location</label>
							<div class="col-sm-5">
								<input type="text" name="google_autocomplete" id="google_autocomplete" placeholder="Address: " value="" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-4 control-label"></label>
							<div class="col-sm-5">
								<input type="text" name="address" id="address" value="{{$info->address}}" class="form-control" {{$info->address==""?"":"readonly"}}>
							</div>
						</div>
						<div class="form-group">
							<label for="city" class="col-sm-4 control-label">City</label>
							<div class="col-sm-5">
								<input type="text" name="city" id="city" value="{{$info->city}}" class="form-control" {{$info->city==""?"":"readonly"}}>
							</div>
						</div>
						<div class="form-group">
							<label for="state" class="col-sm-4 control-label">State</label>
							<div class="col-sm-5">
								<input type="text" name="state" id="state" value="{{$info->state}}" class="form-control" {{$info->state==""?"":"readonly"}}>
							</div>
						</div>
						<div class="form-group">
							<label for="zip_code" class="col-sm-4 control-label">Zip Code</label>
							<div class="col-sm-5">
								<input type="text" name="zip_code" id="zip_code" value="{{$info->zip_code}}" class="form-control" {{$info->zip_code==""?"":"readonly"}}>
							</div>
						</div>
						<div class="form-group">
							<label for="country" class="col-sm-4 control-label">Country</label>
							<div class="col-sm-5">
								<input type="text" name="country" id="country" value="{{$info->country}}" class="form-control" {{$info->country==""?"":"readonly"}}>
							</div>
						</div>
						<div class="form-group">
							<label for="faith_god" class="col-sm-4 control-label">What faith do you have and why do you think God wants you to facilitate it</label>
							<div class="col-sm-5">
								<input type="text" name="faith_god" id="faith_god" value="{{$info->faith_god}}" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label for="description" class="col-sm-4 control-label">Describe the project</label>
							<div class="col-sm-5">
								<textarea name="description" id="description" class="form-control" rows="6">{{$info->description}}</textarea>
								<label id="-error" class="error" for="" style="display:none">This field is required.</label>
								<span class="help-block m-b-none pull-right">maximum 200 words in here.</span>
							</div>
						</div>
						<div class="form-group">
							<label for="thumbnail" class="col-sm-4 control-label">Thumbnail</label>
							<div class="col-sm-5">
								<input type="file" name="thumbnail" id="thumbnail" class="form-control" accept="image/*">
								{{$info->thumbnail != '' ? "<div><a href='".$info->thumbnail."' target='_blank'>View Image</a></div>" : ""}}
							</div>
						</div>
						<div class="form-group">
							<label for="intro_video" class="col-sm-4 control-label">Intro Video</label>
							<div class="col-sm-5">
								<div class="input-group">
                                    <span class="input-group-addon" style="background: #f0f0f0">https://www.youtube.com/watch?v=</span>
                                    <input type="text" name="intro_video" id="intro_video" value="{{$info->intro_video}}" class="form-control">
                                </div>
                                <span class="help-block m-b-none">Only uploads videos from Youtube. Copy id from URL which is everything after the = sign in the URL</span>
                                <!--<input type="text" name="intro_video" id="intro_video" value="{{$info->intro_video}}" class="form-control">
								<input type="file" name="intro_video" id="intro_video" class="form-control" accept="video/*">
								<span class="help-block m-b-none">3 minute video of extension.</span>
								{{$info->intro_video != "" ? "<div><a href='".$info->intro_video."' target='_blank'>Download Video</a></div>" : ""}}-->
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">How often will you make a report</label>
							<div class="col-sm-5">
								<label style="font-weight: normal"><input type="radio" name="often_type" value="1" {{$info->often_type == 1 ? "checked" : ""}}>&nbsp;Daily</label>&nbsp;&nbsp;
								<label style="font-weight: normal"><input type="radio" name="often_type" value="2" {{$info->often_type == 2 ? "checked" : ""}}>&nbsp;Weekly</label>&nbsp;&nbsp;
								<label style="font-weight: normal"><input type="radio" name="often_type" value="3" {{$info->often_type == 3 ? "checked" : ""}}>&nbsp;Monthly</label>&nbsp;&nbsp;
								<label style="font-weight: normal"><input type="radio" name="often_type" value="4" {{$info->often_type == 4 ? "checked" : ""}}>&nbsp;Quarterly</label>&nbsp;&nbsp;
								<label style="font-weight: normal"><input type="radio" name="often_type" value="5" {{$info->often_type == 5 ? "checked" : ""}}>&nbsp;Halfyearly</label>&nbsp;&nbsp;
								<label style="font-weight: normal"><input type="radio" name="often_type" value="6" {{$info->often_type == 6 ? "checked" : ""}}>&nbsp;Yearly</label>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">Is the project linked to local report</label>
							<div class="col-sm-5">
								<label style="font-weight: normal"><input type="radio" name="liked_localreport" value="1" {{$info->liked_localreport == 1 ? "checked" : ""}}>&nbsp;Yes</label>&nbsp;&nbsp;
								<label style="font-weight: normal"><input type="radio" name="liked_localreport" value="-1" {{$info->liked_localreport == -1 ? "checked" : ""}}>&nbsp;No</label>&nbsp;&nbsp;
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">Is the project linked to regional report</label>
							<div class="col-sm-5">
								<label style="font-weight: normal"><input type="radio" name="liked_regionalreport" value="1" {{$info->liked_regionalreport == 1 ? "checked" : ""}}>&nbsp;Yes</label>&nbsp;&nbsp;
								<label style="font-weight: normal"><input type="radio" name="liked_regionalreport" value="-1" {{$info->liked_regionalreport == -1 ? "checked" : ""}}>&nbsp;No</label>&nbsp;&nbsp;
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">Is the project linked to national report</label>
							<div class="col-sm-5">
								<label style="font-weight: normal"><input type="radio" name="liked_nationalreport" value="1" {{$info->liked_nationalreport == 1 ? "checked" : ""}}>&nbsp;Yes</label>&nbsp;&nbsp;
								<label style="font-weight: normal"><input type="radio" name="liked_nationalreport" value="-1" {{$info->liked_nationalreport == -1 ? "checked" : ""}}>&nbsp;No</label>&nbsp;&nbsp;
							</div>
						</div>
						<div class="form-group">
							<label for="oversight" class="col-sm-4 control-label">Who has oversight of the project?</label>
							<div class="col-sm-5">
								<input type="text" name="oversight" id="oversight" value="{{$info->oversight}}" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label for="oversight_name" class="col-sm-4 control-label">How can we communicate with the person with oversight</label>
							<div class="col-sm-5">
								<input type="text" name="oversight_name" id="oversight_name" value="{{$info->oversight_name}}" class="form-control" placeholder="Name: ">
								<input type="text" name="oversight_email" id="oversight_email" value="{{$info->oversight_email}}" class="form-control" placeholder="Email Address: ">
								<input type="text" name="oversight_phone" id="oversight_phone" value="{{$info->oversight_phone}}" class="form-control" placeholder="Phone Number: ">
							</div>
						</div>
						<div class="form-group">
							<label for="paypal_number" class="col-sm-4 control-label">Paypal Address</label>
							<div class="col-sm-5">
								<input type="text" name="paypal_number" id="paypal_number" value="{{$info->paypal_number}}" class="form-control" placeholder="user@example.com">
							</div>
						</div>
						<div class="form-group">
							<label for="timeframe" class="col-sm-4 control-label">What timeframe are you committed to this project</label>
							<div class="col-sm-5">
								<input type="text" name="timeframe" id="timeframe" value="{{$info->timeframe}}" class="form-control">
							</div>
						</div>
						<div class="hr-line-dashed"></div>
						<div class="form-group">
							<div class="col-sm-4 col-sm-offset-4">
								<button class="btn btn-primary" type="submit" name="save">
									Save changes
								</button>
								<button class="btn btn-default" type="button" name="cancel">
									Cancel
								</button>
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
		$("button[name='cancel']").click(function() {
			location.href = "/projects/impact";
		});

		$("form[name='frmProjectEdit']").submit(function() {
			flag = true;
			$(".error").hide();
			if($("input[name='name']").val() == "") {
				$("input[name='name']").parent().find(".error").show();
				flag = false;
			}
			if($("textarea[name='description']").val() == "") {
				$("textarea[name='description']").parent().find(".error").show();
				flag = false;
			}

			if(!flag) {
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
			var latitude = place.geometry.location.k;
			var longitude = place.geometry.location.D;
			
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
					
					if(addressType == "street_number") {
						$("#address").val(val);
					} else if(addressType == "route") {
						prev = $("#address").val();
						$("#address").val(prev == "" ? val : prev + " " + val);
					} else if(addressType == "locality") {
						$("#city").val(val);
					} else if(addressType == "administrative_area_level_1") {
						$("#state").val(val);
					} else if(addressType == "country") {
						$("#country").val(val);
					} else if(addressType == "postal_code") {
						$("#zip_code").val(val);
					}
				}
			}
			
			_active();
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