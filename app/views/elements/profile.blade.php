<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true&libraries=places"></script>
<style>
	textarea {
		resize: none;
	}
</style>
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-6">
		<h2>My Profile</h2>
	</div>
	<div class="col-lg-6" align="right">
		<h2>
		<button type="button" name="save" class="btn btn-primary">
			Save changed
		</button>
		<?php if(isset($back) && $back) :?>
			<button type="button" name="back" class="btn btn-default" onclick="javascript:location.href='{{$back_url}}'">
				Go to Back
			</button>
		<?php endif;?>
		</h2>
	</div>
</div>
<br>
<form name="frmProfile" id="frmProfile" method="get" action="#" class="form-horizontal" enctype="multipart/form-data">
	<div class="row">
		<div class="row">
			<div class="col-lg-2">
				<div class="avatar" target-file="avatar" style="cursor:pointer">
					<img src="<?php echo $profile->picture == "" ? "/images/default-user.png" : $profile->picture ?>" width="100%" id="avatar_img">
				</div>
			</div>
			<div class="col-lg-10">
				<div class="ibox">
					<div class="ibox-title">
						Edit Profile
					</div>
					<div class="ibox-content">
						<?php if(Session::get("message") != "") :?>
							<div class="row">
								<div class="col-lg-12">
									<?php echo Session::get("message")?>
								</div>
							</div>
						<?php endif;?>
						<div class="row">
							<div class="col-sm-6 b-r">
								<input type="file" name="avatar" id="avatar" accept="image/*" style="position:fixed; left:-1000px;" onchange="onFileSelected(event)">
								<div class="form-group">
									<label for="first_name" class="col-sm-3 control-label">First Name</label>
									<div class="col-sm-9">
										<input type="text" name="first_name" id="first_name" value="<?php echo $profile->first_name?>" class="form-control">
									</div>
								</div>
								<div class="form-group">
									<label for="last_name" class="col-sm-3 control-label">Last Name</label>
									<div class="col-sm-9">
										<input type="text" name="last_name" id="last_name" value="<?php echo $profile->last_name?>" class="form-control">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">Gender</label>
									<div class="col-sm-9">
										<div class="radio" style="float:left;width:100px">
											<label for="gender-male">
												<input type="radio" name="gender" value="1" <?php echo $profile->gender == 1 ? "checked" : ""?>
												id="gender-male" name="optionsRadios">
												Male</label>
										</div>
										<div class="radio" style="float:left;width:100px">
											<label for="gender-female">
												<input type="radio" name="gender" value="2" <?php echo $profile->gender == 2 ? "checked" : ""?>
												id="gender-female" name="optionsRadios">
												Female</label>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label for="birthday" class="col-sm-3 control-label">Birthday</label>
									<div class="col-sm-9">
										<div class="input-group date">
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
											<input type="text" name="birthday" id="birthday" value="<?php echo $profile->birthday?>" class="form-control datepick" placeholder="yyyy-mm-dd">
										</div>
									</div>
								</div>
								<div class="form-group">
									<label for="email" class="col-sm-3 control-label">Email Address</label>
									<div class="col-sm-9">
										<div class="input-group">
											<span class="input-group-addon">@</span>
											<input type="text" name="email" id="email" value="<?php echo $profile->email?>" class="form-control">
										</div>
									</div>
								</div>
								<div class="form-group">
									<label for="phone_number" class="col-sm-3 control-label">Phone Number</label>
									<div class="col-sm-9">
										<input type="text" name="phone_number" id="phone_number" value="<?php echo $profile->phone_number?>" class="form-control">
									</div>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label for="google_autocomplete" class="col-sm-3 control-label">Address</label>
									<div class="col-sm-9">
										<input type="text" name="google_autocomplete" id="google_autocomplete" placeholder="Address: " value="" class="form-control">
										<input type="text" name="address" id="address" value="<?php echo $profile->address?>" class="form-control" {{$profile->address=""?"":"readonly"}}>
									</div>
								</div>
								<div class="form-group">
									<label for="city" class="col-sm-3 control-label">City</label>
									<div class="col-sm-9">
										<input type="text" name="city" id="city" value="<?php echo $profile->city?>" class="form-control" placeholder="City:" {{$profile->city=""?"":"readonly"}}>
									</div>
								</div>
								<div class="form-group">
									<label for="state" class="col-sm-3 control-label">State</label>
									<div class="col-sm-9">
										<input type="text" name="state" id="state" value="<?php echo $profile->state?>" class="form-control" placeholder="State:" {{$profile->state=""?"":"readonly"}}>
									</div>
								</div>
								<div class="form-group">
									<label for="zip_code" class="col-sm-3 control-label">Zip Code</label>
									<div class="col-sm-9">
										<input type="text" name="zip_code" id="zip_code" value="<?php echo $profile->zip_code?>" class="form-control" placeholder="Zip Code:" {{$profile->zip_code=""?"":"readonly"}}>
									</div>
								</div>
								<div class="form-group">
									<label for="country" class="col-sm-3 control-label">Country</label>
									<div class="col-sm-9">
										<input type="text" name="country" id="country" value="<?php echo $profile->country?>" class="form-control" placeholder="Country:" {{$profile->country=""?"":"readonly"}}>
									</div>
								</div>
								<?php if(Auth::user()->permission == -1 || Auth::user()->permission == -3):?>
									<div class="form-group">
										<label for="status" class="col-sm-3 control-label">Status</label>
										<div class="col-sm-9">
											<select name="status" id="status" class="form-control">
												<option value="-99" {{$profile->status == -99 ? "selected" : ""}}>Waiting</option>
												<option value="1" {{$profile->status == 1 ? "selected" : ""}}>Approved</option>
												<option value="-2" {{$profile->status == -2 ? "selected" : ""}}>Blocked</option>
												<option value="-1" {{$profile->status == -1 ? "selected" : ""}}>Removed</option>
											</select>
										</div>
									</div>
								<?php endif;?>
								<?php if(Auth::user()->permission == -1):?>
									<div class="form-group">
										<label for="permission" class="col-sm-3 control-label">Permission</label>
										<div class="col-sm-9">
											<select name="permission" id="permission" class="form-control">
												<option value="-2" {{$profile->permission == -2 ? "selected" : ""}}>Region Administrator</option>
												<option value="-3" {{$profile->permission == -3 ? "selected" : ""}}>General Administrator</option>
												<option value="100" {{$profile->permission == 100 ? "selected" : ""}}>Default</option>
											</select>
										</div>
									</div>
								<?php endif;?>
							</div>
						</div>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-lg-6">
						<div class="ibox">
							<div class="ibox-title">Write your testimony</div>
							<div class="ibox-content">
								<div class="row">
									<div class="col-lg-5">
										<?php if($video->testimony_video != "") :?>
											<embed width="100%" height="200px" src="//www.youtube.com/embed/{{$video->testimony_video}}">
										<?php endif;?>
									</div>
									<div class="col-lg-7"><textarea name="testimony" id="testimony" class="form-control" placeholder="Testimony:" style="height:200px"><?php echo $profile->testimony?></textarea></div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="ibox">
							<div class="ibox-title">Write your mission statement</div>
							<div class="ibox-content">
								<div class="row">
									<div class="col-lg-5">
										<?php if($video->mission_video != "") :?>
											<embed width="100%" height="200px" src="//www.youtube.com/embed/{{$video->mission_video}}">
										<?php endif;?>
									</div>
									<div class="col-lg-7"><textarea name="mission_statement" id="mission_statement" class="form-control" placeholder="Mission Statement:" style="height:200px"><?php echo $profile->mission_statement?></textarea></div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="ibox">
							<div class="ibox-title">Write your skill / gifts</div>
							<div class="ibox-content">
								<div class="row">
									<div class="col-lg-5">
										<?php if($video->gifts_video != "") :?>
											<embed width="100%" height="200px" src="//www.youtube.com/embed/{{$video->gifts_video}}">
										<?php endif;?>
									</div>
									<div class="col-lg-7"><textarea name="skill_gifts" id="skill_gifts" class="form-control" placeholder="Skill / Gifts:" style="height:200px"><?php echo $profile->skill_gifts?></textarea></div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="ibox">
							<div class="ibox-title">Write your goals</div>
							<div class="ibox-content">
								<div class="row">
									<div class="col-lg-5">
										<?php if($video->goals_video != "") :?>
											<embed width="100%" height="200px" src="//www.youtube.com/embed/{{$video->goals_video}}">
										<?php endif;?>
									</div>
									<div class="col-lg-7"><textarea name="goals" id="goals" class="form-control" placeholder="Goals:" style="height:200px"><?php echo $profile->goals?></textarea></div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="ibox">
							<div class="ibox-title">Write your ministry interests</div>
							<div class="ibox-content">
								<div class="row">
									<div class="col-lg-5">
										<?php if($video->interests_video != "") :?>
											<embed width="100%" height="200px" src="//www.youtube.com/embed/{{$video->interests_video}}">
										<?php endif;?>
									</div>
									<div class="col-lg-7"><textarea name="ministry_interests" id="ministry_interests" class="form-control" placeholder="Ministry Interests:" style="height:200px"><?php echo $profile->ministry_interests?></textarea></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
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

		$(".avatar").click(function() {
			var $target = $(this).attr("target-file");
			$("#" + $target).click();
		});

		$("button[name='save']").click(function() {
			if($("input[name='first_name']").val() == "") {
				$("input[name='first_name']").focus();
				return;
			}
			if($("input[name='last_name']").val() == "") {
				$("input[name='last_name']").focus();
				return;
			}
			if($("input[name='birthday']").val() == "") {
				$("input[name='birthday']").focus();
				return;
			}
			if($("input[name='email']").val() == "") {
				$("input[name='email']").focus();
				return;
			}
			
			$("form[name='frmProfile']").attr("method", "post").submit();
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

	function onFileSelected(event) {
		var selectedFile = event.target.files[0];
		var reader = new FileReader();
		var img = document.getElementById("avatar_img");

		reader.onload = function(event) {
			img.src = reader.result;
		};
		var file = selectedFile;
		name = file.name;
		size = file.size;
		type = file.type;
		if (file.name.length < 1) {
			alert("File is none");
		} else if (file.size > 10000000) {
			alert("File is too big");
			return;
		}

		reader.readAsDataURL(selectedFile);
	}
</script>
<?php Session::set("message", "")?>