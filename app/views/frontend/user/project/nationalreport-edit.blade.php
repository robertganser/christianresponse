@extends('layout.user_dashboard')
@section('content')
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true&libraries=places"></script>
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-10">
		<h2>Edit National Report Form</h2>
		<ol class="breadcrumb">
			<li>
				<a href="/projects/report">Projects</a>
			</li>
			<li>
				<a href="/projects/report">Report</a>
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
					<h3><b>Instructions</b></h3>
					<p>
						A national report is a vision statement and is needed to guide the church of the living God as to where and how to build for him in that nation. It outlines the nation’s needs and what is happening and what should be happening for God . The national director compiles and edits the report. If you wish to make a contribution to the report or update information answer some or all of the questions below. Help inspire people to do something great in that nation. Note: Please include a source if you offer information. The national report is a fluid document continually being updated and improved and can be accessed in the national report search section.
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
					<form name="frmNationalEdit" method="post" class="form-horizontal" enctype="multipart/form-data">
						<div class="form-group">
							<label for="name" class="col-sm-4 control-label">Report Name</label>
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
							<label for="description" class="col-sm-4 control-label">Describe the project</label>
							<div class="col-sm-5">
								<textarea name="description" id="description" class="form-control" rows="5">{{$info->description}}</textarea>
								<label id="-error" class="error" for="" style="display:none">This field is required.</label>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">Are you going to organize an event to present your report</label>
							<div class="col-sm-5">
								<label style="font-weight: normal"><input type="radio" name="organize_option_report" value="1" {{$info->organize_option_report == 1 ? "checked" : ""}}> Yes</label>&nbsp;&nbsp;&nbsp;
								<label style="font-weight: normal"><input type="radio" name="organize_option_report" value="-1" {{$info->organize_option_report == -1 ? "checked" : ""}}> No</label>
							</div>
						</div>
						<div class="form-group">
							<label for="national_date" class="col-sm-4 control-label">When is the national day of prayer and fasting</label>
							<div class="col-sm-5">
								<div class="input-group date">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="text" name="national_date" id="national_date" value="{{$info->national_date}}" class="form-control datepick" placeholder="yyyy-mm-dd">
                                </div>
                                <label id="-error" class="error" for="" style="display:none">This field is required.</label>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">Are you going to organize an event for this day</label>
							<div class="col-sm-5">
								<label style="font-weight: normal"><input type="radio" name="organize_option_day" value="1" {{$info->organize_option_day == 1 ? "checked" : ""}}> Yes</label>&nbsp;&nbsp;&nbsp;
								<label style="font-weight: normal"><input type="radio" name="organize_option_day" value="-1" {{$info->organize_option_day == -1 ? "checked" : ""}}> No</label>
							</div>
						</div>
						<div class="form-group">
							<label for="nation_prayers" class="col-sm-4 control-label">Write some prayers for the nation</label>
							<div class="col-sm-5">
								<input type="text" name="nation_prayers" id="nation_prayers" value="{{$info->nation_prayers}}" class="form-control">
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
							<label for="intro_video" class="col-sm-4 control-label">Nation Video</label>
							<div class="col-sm-5">
								<div class="input-group">
                                    <span class="input-group-addon" style="background: #f0f0f0">https://www.youtube.com/watch?v=</span>
                                    <input type="text" name="intro_video" id="intro_video" value="{{$info->intro_video}}" class="form-control">
                                </div>
                                <span class="help-block m-b-none">Only uploads videos from Youtube. Copy id from URL which is everything after the = sign in the URL</span>
								<!--
								<input type="text" name="intro_video" id="intro_video" value="{{$info->intro_video}}" class="form-control">
								<input type="file" name="intro_video" id="intro_video" class="form-control" accept="video/*">
								<span class="help-block m-b-none">3 min video upload of prayer for the nation.</span>
								{{$info->intro_video != "" ? "<div><a href='".$info->intro_video."' target='_blank'>Download Video</a></div>" : ""}}-->
							</div>
						</div>
						<div class="form-group">
							<label for="world_link" class="col-sm-4 control-label">Link to operation world information about the nation</label>
							<div class="col-sm-5">
								<input type="text" name="world_link" id="world_link" value="{{$info->world_link}}" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label for="past_story" class="col-sm-4 control-label">What great Christian leaders have there been in the past and what is their story</label>
							<div class="col-sm-5">
								<input type="text" name="past_story" id="past_story" value="{{$info->past_story}}" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label for="relevant_fact" class="col-sm-4 control-label">Relevant facts about the Spiritual condition of the nation</label>
							<div class="col-sm-5">
								<input type="text" name="relevant_fact" id="relevant_fact" value="{{$info->relevant_fact}}" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label for="nation_need" class="col-sm-4 control-label">What is needed in the nation</label>
							<div class="col-sm-5">
								<input type="text" name="nation_need" id="nation_need" value="{{$info->nation_need}}" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label for="paypal_number" class="col-sm-4 control-label">Paypal Address</label>
							<div class="col-sm-5">
								<input type="text" name="paypal_number" id="paypal_number" value="{{$info->paypal_number}}" class="form-control" placeholder="user@example.com">
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
		$("#national_date").datepicker({
			format: 'yyyy-mm-dd'
		});
		
		$("button[name='cancel']").click(function() {
			location.href = "/projects/report";
		});

		$("form[name='frmNationalEdit']").submit(function() {
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
			if($("input[name='national_date']").val() == "") {
				$("input[name='national_date']").parent().parent().find(".error").show();
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