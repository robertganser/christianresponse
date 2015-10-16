@extends('layout.region_dashboard')
@section('content')
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true&libraries=places"></script>
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-10">
		<h2>Edit Regional Report Form</h2>
		<ol class="breadcrumb">
			<li>
				<a href="/{{$active=='manages'?'manages/':''}}projects/report">Projects</a>
			</li>
			<li>
				<a href="/{{$active=='manages'?'manages/':''}}projects/report">Report</a>
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
				<div class="col-lg-11">
					<h3><b>Why do you need a local area report?</b></h3>
					<p>
						-	Communicate to the world the great things God is doing and continues to do bringing glory to him.
						<br>
						-	Communicate where people are at and where they should be.
						<br>
						-	Encourage corporate momentum and unity for the approaching year
						<br>
						-	Draws the line and sets goals for the year.
						<br>
						-	Finding and communicating Gods heart for the region.
						<br>
					</p>
				</div>
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
					<form name="frmRegionalEdit" method="post" class="form-horizontal" enctype="multipart/form-data">
						<div class="form-group">
							<label for="name" class="col-sm-5 control-label">Report Name</label>
							<div class="col-sm-6">
								<input type="text" name="name" id="name" value="{{$info->name}}" class="form-control">
								<label id="-error" class="error" for="" style="display:none">This field is required.</label>
							</div>
						</div>
						<div class="form-group">
							<label for="google_autocomplete" class="col-sm-5 control-label">Location</label>
							<div class="col-sm-6">
								<input type="text" name="google_autocomplete" id="google_autocomplete" placeholder="Address: " value="" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-5 control-label"></label>
							<div class="col-sm-6">
								<input type="text" name="address" id="address" value="{{$info->address}}" class="form-control" {{$info->address==""?"":"readonly"}}>
							</div>
						</div>
						<div class="form-group">
							<label for="city" class="col-sm-5 control-label">City</label>
							<div class="col-sm-6">
								<input type="text" name="city" id="city" value="{{$info->city}}" class="form-control" {{$info->city==""?"":"readonly"}}>
							</div>
						</div>
						<div class="form-group">
							<label for="state" class="col-sm-5 control-label">State</label>
							<div class="col-sm-6">
								<input type="text" name="state" id="state" value="{{$info->state}}" class="form-control" {{$info->state==""?"":"readonly"}}>
							</div>
						</div>
						<div class="form-group">
							<label for="zip_code" class="col-sm-5 control-label">Zip Code</label>
							<div class="col-sm-6">
								<input type="text" name="zip_code" id="zip_code" value="{{$info->zip_code}}" class="form-control" {{$info->zip_code==""?"":"readonly"}}>
							</div>
						</div>
						<div class="form-group">
							<label for="country" class="col-sm-5 control-label">Country</label>
							<div class="col-sm-6">
								<input type="text" name="country" id="country" value="{{$info->country}}" class="form-control" {{$info->country==""?"":"readonly"}}>
							</div>
						</div>
						<div class="form-group">
							<label for="description" class="col-sm-5 control-label">Describe the project</label>
							<div class="col-sm-6">
								<textarea name="description" id="description" class="form-control" rows="5">{{$info->description}}</textarea>
								<label id="-error" class="error" for="" style="display:none">This field is required.</label>
							</div>
						</div>
						<div class="form-group">
							<label for="defined_region" class="col-sm-5 control-label">How would you define where the region is?</label>
							<div class="col-sm-6">
								<input type="text" name="defined_region" id="defined_region" value="{{$info->defined_region}}" class="form-control">
								<span class="help-block m-b-none">It may be local government areas. It should be boundaries that don’t change.</span>
								<label id="-error" class="error" for="" style="display:none">This field is required.</label>
							</div>
						</div>
						<div class="form-group">
							<label for="report_owner" class="col-sm-5 control-label">Who has compiled this regional report?</label>
							<div class="col-sm-6">
								<input type="text" name="report_owner" id="report_owner" value="{{$info->report_owner}}" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-5 control-label">Does the report align with the national report?</label>
							<div class="col-sm-6">
								<input type="radio" name="report_align_option" value="1" {{$info->report_align_option == 1 ? "checked" : ""}}>
								Yes&nbsp;&nbsp;&nbsp;
								<input type="radio" name="report_align_option" value="-1" {{$info->report_align_option == -1 ? "checked" : ""}}>
								No
							</div>
						</div>
						<div class="form-group">
							<label for="communication_type" class="col-sm-5 control-label">How do you want to communicate this local report in your region?</label>
							<div class="col-sm-6">
								<select name="communication_type" id="communication_type" class="form-control">
									<option value="1" {{$info->communication_type == 1 ? "selected" : ""}}>Regional function</option>
									<option value="2" {{$info->communication_type == 2 ? "selected" : ""}}> Website</option>
									<option value="3" {{$info->communication_type == 3 ? "selected" : ""}}>Local churches</option>
									<option value="4" {{$info->communication_type == 4 ? "selected" : ""}}>Other</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="vision_statement" class="col-sm-5 control-label">What are some faith/vision statements or prayers for your region?</label>
							<div class="col-sm-6">
								<input type="text" name="vision_statement" id="vision_statement" value="{{$info->vision_statement}}" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label for="curch_use_type" class="col-sm-5 control-label">How does the church use the media/internet for his purposes?</label>
							<div class="col-sm-6">
								<select name="curch_use_type" id="curch_use_type" class="form-control">
									<option value="1" {{$info->curch_use_type == 1 ? "selected" : ""}}>Internet</option>
									<option value="2" {{$info->curch_use_type == 2 ? "selected" : ""}}>Facebook</option>
									<option value="3" {{$info->curch_use_type == 3 ? "selected" : ""}}>Mobile Apps</option>
									<option value="4" {{$info->curch_use_type == 4 ? "selected" : ""}}>Twitter</option>
									<option value="5" {{$info->curch_use_type == 5 ? "selected" : ""}}>Print</option>
									<option value="6" {{$info->curch_use_type == 6 ? "selected" : ""}}>TV</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="significance_happen" class="col-sm-5 control-label">What has happened of significance in your region in the church historically?</label>
							<div class="col-sm-6">
								<textarea name="significance_happen" id="significance_happen" class="form-control">{{$info->significance_happen}}</textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="ancestor" class="col-sm-5 control-label">Are there Christian Spiritual ancestors that you want to respect and recognise in this region?</label>
							<div class="col-sm-6">
								<textarea name="ancestor" id="ancestor" class="form-control">{{$info->ancestor}}</textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="goal_strategy" class="col-sm-5 control-label">What is your goal and strategy for achieving this goal in your region?</label>
							<div class="col-sm-6">
								<input type="text" name="goal_strategy" id="goal_strategy" value="{{$info->goal_strategy}}" class="form-control">
							</div>
						</div>
						<div class="hr-line-dashed"></div>
						<div class="form-group">
							<label for="population" class="col-sm-5 control-label">Population</label>
							<div class="col-sm-6">
								<input type="text" name="population" id="population" value="{{$info->population}}" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label for="christian_number" class="col-sm-5 control-label">Number of christians</label>
							<div class="col-sm-6">
								<input type="text" name="christian_number" id="christian_number" value="{{$info->christian_number}}" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label for="spiritual_father" class="col-sm-5 control-label">Who are the living Spiritual fathers?</label>
							<div class="col-sm-6">
								<input type="text" name="spiritual_father" id="spiritual_father" value="{{$info->spiritual_father}}" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-5 control-label">Is there a national report?</label>
							<div class="col-sm-6">
								<input type="radio" name="national_report_option" value="1" {{$info->national_report_option == 1 ? "checked" : ""}}>
								Yes&nbsp;&nbsp;&nbsp;
								<input type="radio" name="national_report_option" value="-1" {{$info->national_report_option == -1 ? "checked" : ""}}>
								No
							</div>
						</div>
						<div class="form-group">
							<label for="link" class="col-sm-5 control-label">If yes to what is the link to it?</label>
							<div class="col-sm-6">
								<input type="text" name="link" id="link" value="{{$info->link}}" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label for="national_vision" class="col-sm-5 control-label">What is the national vision?</label>
							<div class="col-sm-6">
								<textarea name="national_vision" id="national_vision" class="form-control">{{$info->national_vision}}</textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="social_area" class="col-sm-5 control-label">What are the areas of greatest social need in your region?</label>
							<div class="col-sm-6">
								<textarea name="social_area" id="social_area" class="form-control">{{$info->social_area}}</textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="description_economy" class="col-sm-5 control-label">Describe the economy both historically and at present.</label>
							<div class="col-sm-6">
								<textarea name="description_economy" id="description_economy" class="form-control">{{$info->description_economy}}</textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="churches" class="col-sm-5 control-label">What churches do you have in your region and describe them?</label>
							<div class="col-sm-6">
								<textarea name="churches" id="churches" class="form-control">{{$info->churches}}</textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="quantitatively_state" class="col-sm-5 control-label">Quantitatively state what the church do in your region to help?</label>
							<div class="col-sm-6">
								<textarea name="quantitatively_state" id="quantitatively_state" class="form-control">{{$info->quantitatively_state}}</textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="yearly_people_count" class="col-sm-5 control-label">How many people get saved each year?</label>
							<div class="col-sm-6">
								<input type="text" name="yearly_people_count" id="yearly_people_count" value="{{$info->yearly_people_count}}" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label for="description_crime" class="col-sm-5 control-label">Describe crime in the area?</label>
							<div class="col-sm-6">
								<textarea name="description_crime" id="description_crime" class="form-control">{{$info->description_crime}}</textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="suicide_rate" class="col-sm-5 control-label">What is the suicide rate?</label>
							<div class="col-sm-6">
								<input type="text" name="suicide_rate" id="suicide_rate" value="{{$info->suicide_rate}}" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label for="has_christian_witness" class="col-sm-5 control-label">Do schools have a Christian witness?</label>
							<div class="col-sm-6">
								<textarea name="has_christian_witness" id="has_christian_witness" class="form-control">{{$info->has_christian_witness}}</textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="help_community" class="col-sm-5 control-label">Does business help the community?</label>
							<div class="col-sm-6">
								<textarea name="help_community" id="help_community" class="form-control">{{$info->help_community}}</textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="occult_activity" class="col-sm-5 control-label">Is there significant occult or anti Christian activity?</label>
							<div class="col-sm-6">
								<textarea name="occult_activity" id="occult_activity" class="form-control">{{$info->occult_activity}}</textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="prayer_meeting" class="col-sm-5 control-label">Where and when are the prayer meetings?</label>
							<div class="col-sm-6">
								<textarea name="prayer_meeting" id="prayer_meeting" class="form-control">{{$info->prayer_meeting}}</textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="evangelism_program" class="col-sm-5 control-label">What evangelism programs are there in your region?</label>
							<div class="col-sm-6">
								<textarea name="evangelism_program" id="evangelism_program" class="form-control">{{$info->evangelism_program}}</textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="paypal_number" class="col-sm-5 control-label">PayPal Address</label>
							<div class="col-sm-6">
								<input type="text" name="paypal_number" id="paypal_number" value="{{$info->paypal_number}}" class="form-control" placeholder="user@example.com">
							</div>
						</div>
						<div class="form-group">
							<label for="thumb" class="col-sm-5 control-label">Thumbnail Image</label>
							<div class="col-sm-6">
								<input type="file" name="thumbnail" id="thumbnail" class="form-control" accept="image/*">
								{{$info->thumbnail != '' ? "<div><a href='".$info->thumbnail."' target='_blank'>View Image</a></div>" : ""}}
							</div>
						</div>
						<div class="form-group">
							<label for="intro_video" class="col-sm-5 control-label">Intro Video</label>
							<div class="col-sm-6">
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
						<div class="hr-line-dashed"></div>
						<div class="form-group">
							<div class="col-sm-5 col-sm-offset-5">
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
			location.href = "/{{$active=='manages'?'manages/':''}}projects/report";
		});

		$("form[name='frmRegionalEdit']").submit(function() {
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
			if($("input[name='defined_region']").val() == "") {
				$("input[name='defined_region']").parent().find(".error").show();
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