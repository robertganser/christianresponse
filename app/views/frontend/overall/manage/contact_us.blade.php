@extends('layout.overall_dashboard')
@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-10">
		<h2>Contact Us</h2>
		<ol class="breadcrumb">
			<li>
				<a href="/manages/about">Manage</a>
			</li>
			<li>
				<a href="/manages/about">Contact Us</a>
			</li>
		</ol>
	</div>
</div>
<div class="wrapper wrapper-content  animated fadeInRight">
	<?php if($message) :?>
		<div class="row">
			<div class="col-lg-2"></div>
			<div class="col-lg-8">
				{{$message}}
			</div>
			<div class="col-lg-2"></div>
		</div>
	<?php endif; ?>
	<form name="frmContact" method="POST" class="form-horizontal">
		<div class="form-group">
			<label for="address" class="col-sm-4 control-label">Address</label>
			<div class="col-sm-5">
				<input type="text" name="address" id="address" value="{{$contact->address}}" class="form-control">
				<label id="-error" class="error" for="" style="display:none">This field is required.</label>
			</div>
		</div>
		<div class="form-group">
			<label for="phone_number" class="col-sm-4 control-label">Telephone Number</label>
			<div class="col-sm-5">
				<input type="text" name="phone_number" id="phone_number" value="{{$contact->phone_number}}" class="form-control">
				<label id="-error" class="error" for="" style="display:none">This field is required.</label>
			</div>
		</div>
		<div class="form-group">
			<label for="email" class="col-sm-4 control-label">Email Address</label>
			<div class="col-sm-5">
				<input type="text" name="email" id="email" value="{{$contact->email}}" class="form-control">
				<label id="-error" class="error" for="" style="display:none">This field is required.</label>
			</div>
		</div>
		<div class="form-group">
			<label for="content" class="col-sm-4 control-label">Content</label>
			<div class="col-sm-5">
				<textarea name="content" class="form-control" rows="10">{{$contact->content}}</textarea>
				<label id="-error" class="error" for="" style="display:none">This field is required.</label>
			</div>
		</div>
		<div class="form-group">
			<label for="content" class="col-sm-4 control-label"></label>
			<div class="col-sm-5">
				<button type="submit" class="btn btn-primary">
					Save Changes
				</button>
			</div>
		</div>
	</form>
</div>
<script>
	$(document).ready(function() {
		$("form[name='frmContact']").submit(function() {
			flag = true;
			$(".error").hide();
			if($("input[name='address']").val() == "") {
				$("input[name='address']").parent().find(".error").show();
				flag = false;
			}
			if($("input[name='phone_number']").val() == "") {
				$("input[name='phone_number']").parent().find(".error").show();
				flag = false;
			}
			if($("input[name='email']").val() == "") {
				$("input[name='email']").parent().find(".error").show();
				flag = false;
			}
			if($("textarea[name='content']").val() == "") {
				$("textarea[name='content']").parent().find(".error").show();
				flag = false;
			}

			if(!flag) {
				$(document).scrollTop(0);
			}
			
			return flag;
		});
	}); 
</script>
@stop
