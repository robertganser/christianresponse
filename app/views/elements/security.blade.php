<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-12">
		<h2>My Profile</h2>
	</div>
</div>
<br>
<div class="row">
	<div class="col-lg-4"></div>
	<div class="col-lg-4">
		<div class="ibox">
			<div class="ibox-title">
				Change Password
			</div>
			<div class="ibox-content">
				<?php if(Session::get("message") != "") :
				?>
				<div class="row">
					<div class="col-lg-12">
						<?php echo Session::get("message")?>
					</div>
				</div>
				<?php endif; ?>
				<form name="frmSecurity" method="get" class="form-horizontal">
					<div class="form-group m-b-lg">
						<label for="username" class="col-sm-4 control-label">Username</label>
						<div class="col-sm-8">
							<input type="text" name="username" id="username" value="<?php echo Auth::user()->username?>" class="form-control" readonly="">
						</div>
					</div>
					<div class="form-group m-b-lg">
						<label for="current_password" class="col-sm-4 control-label">Current Password</label>
						<div class="col-sm-8">
							<input type="password" name="current_password" id="current_password" class="form-control" placeholder="**********************" autocomplete="off">
						</div>
					</div>
					<div class="form-group m-b-lg">
						<label for="new_password" class="col-sm-4 control-label">New Password</label>
						<div class="col-sm-8">
							<input type="password" name="new_password" id="new_password" class="form-control" placeholder="**********************" autocomplete="off">
						</div>
					</div>
					<div class="form-group m-b-lg">
						<label for="confirm_password" class="col-sm-4 control-label">Confirm Password</label>
						<div class="col-sm-8">
							<input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="**********************" autocomplete="off">
						</div>
					</div>
					<div class="hr-line-dashed"></div>
					<div class="form-group" align="center">
						<button type="button" name="save" class="btn btn-primary">
							Save Changed
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="col-lg-4"></div>
</div>
<script>
	$(document).ready(function() {
		$("button[name='save']").click(function() {
			if($("input[name='current_password']").val() == "") {
				$("input[name='current_password']").focus();
				return;
			}
			if($("input[name='new_password']").val() == "") {
				$("input[name='new_password']").focus();
				return;
			}
			if($("input[name='new_password']").val() != $("input[name='confirm_password']").val()) {
				$("input[name='confirm_password']").focus();
				$("input[name='confirm_password']").select();
				return;
			}
			
			$("form[name='frmSecurity']").attr("method", "post").submit();
		});
	}); 
</script>
<?php Session::set("message", "")
?>