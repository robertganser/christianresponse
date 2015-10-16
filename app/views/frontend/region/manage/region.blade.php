@extends('layout.region_dashboard')
@section('content')
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true&libraries=places"></script>

<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-10">
		<h2>Edit Region</h2>
		<ol class="breadcrumb">
			<li>
				<a href="#">Manage</a>
			</li>
			<li class="active">
				<strong>Region</strong>
			</li>
		</ol>
	</div>
	<div class="col-lg-2">
		<div class="widget style1 lazur-bg" style="margin-bottom:0px">
			<div class="row">
				<div class="col-xs-4">
					<i class="fa fa-dollar fa-3x"></i>
				</div>
				<div class="col-xs-8 text-right">
					<span> Donation </span>
					<h2 class="font-bold">{{number_format($total->amount*1,2)}}</h2>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal inmodal" id="modal_video" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content animated flipInY">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
				</button>
				<div class="row">
					<div class="col-lg-4"></div>
					<div class="col-lg-4" id="event_thumb">
						<i class="fa fa-video-camera modal-icon"></i>
					</div>
					<div class="col-lg-4"></div>
				</div>
				<h4 class="modal-title">How to use?</h4>
			</div>
			<div class="modal-body">
				<p align="center"></p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-white" data-dismiss="modal">
					Close
				</button>
			</div>
		</div>
	</div>
</div>
<?php if(count($transactions) > 0) :?>
	<div class="wrapper wrapper-content animated fadeInRight" style="padding-bottom: 0px">
		<div class="ibox">
			<div class="ibox-title">
				<h5>Donation Transactions</h5>
				<div class="ibox-tools">
                    <a class="collapse-link"><i class="fa fa-chevron-down"></i></a>
                </div>
			</div>
			<div class="ibox-content" style="display:none">
				<div class="table-responsive m-t">
					<table class="table invoice-table">
						<thead>
							<tr>
								<th style="text-align: left">No.</th>
								<th style="text-align: left">Transaction ID</th>
								<th style="text-align: left">Name</th>
								<th style="text-align: left">Email</th>
								<th style="text-align: left">Total Amount</th>
								<th style="text-align: left">Transaction Date</th>
							</tr>
						</thead>
						<tbody>
							<?php $i = 1; $sum = 0;foreach($transactions as $one) :?>
								<tr>
									<td style="text-align: left">{{$i}}</td>
									<td style="text-align: left">{{$one->id}}</td>
									<td style="text-align: left">{{$one->name}}</td>
									<td style="text-align: left">{{$one->email}}</td>
									<td style="text-align: left">$ {{number_format($one->amount, 2)}}</td>
									<td style="text-align: left">{{date("F d, Y, H:i:s", strtotime($one->created_date))}}</td>
								</tr>
							<?php $i ++; $sum += $one->amount;endforeach;?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
<?php endif;?>
<form name="frmRegionEdit" method="post" class="form-horizontal" enctype="multipart/form-data">
	<div class="wrapper wrapper-content animated fadeInRight">
		<?php if($message) :?>
			<div class="row">
				<div class="col-lg-12">
					<?php echo $message?>
				</div>
			</div>
		<?php endif; ?>
		<div class="row">
			<div class="col-lg-12" align="right">
				<button class="btn btn-primary" type="submit" name="save">
					Save changes
				</button>
				<?php if($region->help_video != "") :?>
					<button type="button" class="btn btn-danger" name="show_helpvideo"><i class="fa fa-question-circle"></i></button>
				<?php endif;?>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<div class="ibox">
					<div class="ibox-title"><h5>Basic Info</h5></div>
					<div class="ibox-content">
						<div class="form-group">
							<label for="region_country" class="col-sm-2 control-label">Country</label>
							<div class="col-sm-10">
								<input type="text" id="region_country" value="{{$region->country}}" class="form-control" readonly="">
							</div>
						</div>
						<div class="form-group">
							<label for="region_state" class="col-sm-2 control-label">State</label>
							<div class="col-sm-10">
								<input type="text" id="region_state" value="{{$region->state}}" class="form-control" readonly="">
							</div>
						</div>
						<div class="form-group">
							<label for="region_title" class="col-sm-2 control-label">Region Title</label>
							<div class="col-sm-10">
								<input type="text" name="region_title" id="region_title" value="{{$region->title}}" class="form-control">
								<label id="-error" class="error" for="" style="display:none">This field is required.</label>
							</div>
						</div>
						<div class="form-group">
							<label for="intro_video" class="col-sm-2 control-label">Video</label>
							<div class="col-sm-10">
								<div class="input-group">
									<span class="input-group-addon" style="background: #f0f0f0">https://www.youtube.com/watch?v=</span>
									<input type="text" name="intro_video" id="intro_video" value="{{$region->intro_video}}" class="form-control">
								</div>
								<span class="help-block m-b-none">Only uploads videos from Youtube. Copy id from URL which is everything after the = sign in the URL</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<div class="ibox">
					<div class="ibox-title">
						<h5><input type="checkbox" name="has_annual_event" value="1" {{$has_event == 1 ? "checked" : ""}}>&nbsp;Regional Annual Event</h5>
						<div class="ibox-tools">
		                    <a class="collapse-link"><i class="fa fa-chevron-down"></i></a>
		                </div>
					</div>
					<div class="ibox-content" id="event_box" style="display:none">
						<input type="hidden" name="event_id" value="{{$annual->id}}">
						<div class="form-group">
							<label for="title" class="col-sm-4 control-label">Name of Event</label>
							<div class="col-sm-6">
								<input type="text" name="title" id="title" value="{{$annual->title}}" class="form-control">
								<label id="-error" class="error" for="" style="display:none">This field is required.</label>
							</div>
						</div>
						<div class="form-group">
							<label for="address" class="col-sm-4 control-label">Where</label>
							<div class="col-sm-6">
								<input type="text" name="google_autocomplete" id="google_autocomplete" value="" placeholder="Address: " class="form-control" ononFocus="geolocate()">
								<input type="text" name="address" id="address" value="{{$annual->address}}" class="form-control" readonly="">
								<label id="-error" class="error" for="" style="display:none">This field is required.</label>
								<input type="text" name="city" id="city" value="{{$annual->city}}" class="form-control" readonly="">
								<input type="text" name="state" id="state" value="{{$annual->state}}" class="form-control" readonly="">
								<input type="text" name="zip_code" id="zip_code" value="{{$annual->zip_code}}" class="form-control" readonly="">
								<input type="text" name="country" id="country" value="{{$annual->country}}" class="form-control" readonly="">
								<input type="hidden" name="longitude" id="longitude" value="{{$annual->longitude}}">
								<input type="hidden" name="latitude" id="latitude" value="{{$annual->latitude}}">
							</div>
						</div>
						<div class="form-group">
							<label for="event_date" class="col-sm-4 control-label">When</label>
							<div class="col-sm-6">
								<div class="input-group date">
									<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
									<input type="text" name="event_date" id="event_date" value="{{$annual->event_date}}" class="form-control datepick" placeholder="YYYY-MM-DD" style="width:120px;text-align: center">
									<input type="text" name="event_hour" value="{{$annual->event_hour}}" placeholder="00" class="form-control timepick" style="width:45px;text-align: center">
									<input type="text" name="event_minute" value="{{$annual->event_minute}}" placeholder="00" class="form-control timepick" style="width:45px;text-align: center">
								</div>
								<label id="-error" class="error" for="" style="display:none">This field is required.</label>
							</div>
						</div>
						<div class="form-group">
							<label for="description" class="col-sm-4 control-label">What is the event about</label>
							<div class="col-sm-6">
								<textarea name="description" id="description" class="form-control" rows="5">{{$annual->description}}</textarea>
								<label id="-error" class="error" for="" style="display:none">This field is required.</label>
							</div>
						</div>
						<div class="form-group">
							<label for="thumbnail" class="col-sm-4 control-label">Upload Image</label>
							<div class="col-sm-6">
								<input type="file" name="thumbnail" id="thumbnail" class="form-control" accept="image/*">
								<?php if($annual->thumbnail != "") :
								?>
								<a href="{{$annual->thumbnail}}" target="_blank">View Image</a>
								<?php endif; ?>
							</div>
						</div>
						<div class="form-group">
							<label for="cost" class="col-sm-4 control-label">Cost</label>
							<div class="col-sm-6">
								<div class="input-group m-b">
									<span class="input-group-addon">$</span>
									<input type="text" name="cost" id="cost" value="{{$annual->cost*1}}" class="form-control">
									<span class="input-group-addon">.00</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="contact_details" class="col-sm-4 control-label"> Contract person for more Information
								<br>
								<small class="text-navy">Name and Phone number </small> </label>
							<div class="col-sm-6">
								<textarea name="contact_details" id="contact_details" value="{{$annual->contact_details}}" class="form-control" rows="8">{{$annual->contact_details}}</textarea>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<div class="ibox">
					<div class="ibox-title">Related Regional Reports</div>
					<div class="ibox-content">
						<select name="related_report" class="form-control">
							<option value="0" {{$related_report == 0 ? "selected" : ""}}>Choose a regional report...</option>
			                <?php foreach($reports as $one) :?>
			                	<option value="{{$one->id}}" {{$one->id = $related_report ? "selected" : ""}}>{{$one->name}}</option>
			                <?php endforeach;?>
               			</select>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<div class="ibox float-e-margins">
					<div class="ibox-title">State News and needs</div>
					<div class="ibox-content">
						<textarea name="memo" class='form-control' rows="10">{{$memo}}</textarea>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
<script>
	$(document).ready(function() {
		//CKEDITOR.replace("vmemo");
		
		$('.datepick').datepicker({
			todayBtn : "linked",
			keyboardNavigation : false,
			forceParse : false,
			calendarWeeks : true,
			autoclose : true,
			format : 'yyyy-mm-dd'
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
			var latitude = place.geometry.location.A;
			var longitude = place.geometry.location.F;
			
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
		
		$("input[name='has_annual_event']").click(function() {
			if($(this).is(":checked")) {
				$("#event_box").show();
			} else {
				$("#event_box").hide();
			}
		});
		
		$("button[name='cancel']").click(function() {
			location.href = "/manages/region";
		});

		$("form[name='frmRegionEdit']").submit(function() {
			flag = true;
			$(".error").hide();
			if ($("input[name='region_title']").val() == "") {
				$("input[name='region_title']").parent().find(".error").show();
				flag = false;
			}
			if($("input[name='has_annual_event']").is(":checked")) {
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
			}

			if (!flag) {
				$(document).scrollTop(0);
			}
			
			return flag;
		});
		
		$("button[name='show_helpvideo']").click(function() {
			var $code = "{{$region->help_video}}";
		
			$("#modal_video").modal();
			$("#modal_video .modal-body p").html('<embed width="100%" height="300" src="http://www.youtube.com/v/'+$code+'"></embed>');
		});

		$('#modal_video').on('hidden.bs.modal', function () {
		    $("#modal_video .modal-body p").html("");
		});
	}); 
</script>
@stop