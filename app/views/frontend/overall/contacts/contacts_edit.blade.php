@extends('layout.overall_dashboard')
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-10">
		<h2>View & Respond</h2>
		<ol class="breadcrumb">
			<li>
				<a href="/contacts">Contacts</a>
			</li>
			<li class="active">
				<strong>View & Repond</strong>
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
			<?php endif;?>
			<div class="ibox">
				<div class="ibox-content">
					<form name="frmContacts" method="post" class="form-horizontal" enctype="multipart/form-data">
						<div class="form-group">
							<label for="name" class="col-sm-4 control-label">Name</label>
							<div class="col-sm-5">
								<input type="text" name="name" id="name" value="{{$info->name}}" class="form-control" readonly="">
							</div>
						</div>
						<div class="form-group">
							<label for="email" class="col-sm-4 control-label">Email Address</label>
							<div class="col-sm-5">
								<input type="text" name="email" id="email" value="{{$info->email}}" class="form-control" readonly="">
							</div>
						</div>
						<div class="form-group">
							<label for="text" class="col-sm-4 control-label">Message</label>
							<div class="col-sm-5">
								<textarea name="text" id="text" class="form-control" readonly="" rows="8">{{$info->text}}</textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="response" class="col-sm-4 control-label">Response Message</label>
							<div class="col-sm-5">
								<textarea name="response" id="response" class="form-control" rows="8">{{$info->response_text}}</textarea>
							</div>
						</div>
						<div class="hr-line-dashed"></div>
						<div class="form-group">
							<div class="col-sm-4 col-sm-offset-4">
								<?php if($info->status == 100) :?>
									<button class="btn btn-primary" type="submit" name="save">
										Submit Reponse
									</button>
								<?php endif;?>
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
			location.href = "/contacts";
		});
		
		$("form[name='frmContacts']").submit(function() {
			if($("textarea[name='response']").val() == "") {
				$("textarea[name='response']").focus();
				return false;
			}
			
			return true;
		});
	});
</script>
@stop