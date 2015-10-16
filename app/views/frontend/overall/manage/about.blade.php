@extends('layout.overall_dashboard')
@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-10">
		<h2>About Us</h2>
		<ol class="breadcrumb">
			<li>
				<a href="/manages/about">Manage</a>
			</li>
			<li>
				<a href="/manages/about">About Us</a>
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
	<form name="frmAbout" method="POST">
		<div class="row">
			<div class="col-lg-2"></div>
			<div class="col-lg-8">
				<div class="ibox float-e-margins">
					<div class="ibox-content no-padding">
						<textarea name="content" class="form-control" rows="10">{{$content}}</textarea>
					</div>
				</div>
			</div>
			<div class="col-lg-2"></div>
		</div>
		<div class="row">
			<div class="col-lg-2"></div>
			<div class="col-lg-8" align="right">
				<button type="submit" class="btn btn-primary">
					Save Changes
				</button>
			</div>
			<div class="col-lg-2"></div>
		</div>
	</form>
</div>
<script>
	$(document).ready(function() {
		$("form[name='frmAbout']").submit(function() {
			if($("textarea[name='content']").val() == "") {
				$("textarea[name='content']").focus();
				return;
			}
			return true;
		});
	}); 
</script>
@stop
