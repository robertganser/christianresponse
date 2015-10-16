@extends('layout.blank')
@section('content')

<div class="article">
	<h2>Create Account</h2>
	<div>
		<h3>Registration Understanding</h3>
		<p>
			1) I have read the ABOUT US section and understand and agree with the purpose of this site.
			<br>
			2) I agree and follow the general Christian teachings outlined in the site.
			<br>
			3) I will fill out the registration details honestly.
			<br>
			4) I understand that an administrator may delete my registration at any time for any reason.
			<br>
			5) Response may seek further identification as you become more deeply involved.
			<br>
		</p>
	</div>
	<div class="row">
		<div class="col-lg-8">
			<?php if(isset($response) && $response != ""):?>
			<?php echo $response; ?>
			<?php endif; ?>
			<form action="#" method="post" name="frmRegister">
				<ol>
					<li>
						<label for="register_first_name">Name (required)</label>
						<input id="register_first_name" name="register_first_name" class="text" style="width:45%" placeholder="First" />
						<input id="register_last_name" name="register_last_name" class="text" style="width:45%" placeholder="Last"/>
						<div class="error">
							Please enter a Name
						</div>
					</li>
					<li>
						<label for="register_gender">Gender</label>
						<select id="register_gender" name="register_gender" style="width:45%">
							<option value="1" selected="">Male</option>
							<option value="2">Female</option>
						</select>
					</li>
					<li>
						<label for="register_birthday_y">Date of Birth (required)</label>
						<input id="register_birthday_y" name="register_birthday_y" class="text" placeholder="yyyy" style="width:60px" maxlength="4" />
						<select id="register_birthday_m" name="register_birthday_m" style="width:50px">
							<option value="0">-</option>
							<?php for($i = 1; $i <= 12; $i ++) :?>
								<option value="{{sprintf('%02d', $i)}}">{{$i}}</option>
							<?php endfor; ?>
						</select>
						<select id="register_birthday_d" name="register_birthday_d" style="width:50px">
							<option value="0">-</option>
							<?php for($i = 1; $i <= 31; $i ++) :?>
								<option value="{{sprintf('%02d', $i)}}">{{$i}}</option>
							<?php endfor; ?>
						</select>
						<div class="error">
							Please enter a Date of Birth correctly.
						</div>
					</li>
					<li>
						<label for="register_email">Email Address (required)</label>
						<input id="register_email" name="register_email" class="text" style="width:90%" />
						<div class="error">
							Please enter a Email Address correctly.
						</div>
					</li>
					<li>
						<label for="register_username">Username (required)</label>
						<input id="register_username" name="register_username" class="text" style="width:90%" />
						<div class="error">
							Please enter a Username.
						</div>
					</li>
					<li>
						<label for="register_password">Password (required)</label>
						<input type="password" id="register_password" name="register_password" class="text" style="width:90%" autocomplete="off" />
						<div class="error">
							Please enter a Password.
						</div>
					</li>
					<li>
						<label for="register_confirm_password">Confirm Password (required)</label>
						<input type="password" id="register_confirm_password" name="register_confirm_password" class="text" style="width:90%" autocomplete="off" />
						<div class="error">
							Please enter a Confirm Password correctly.
						</div>
					</li>
					<li>
						<br>
						<input type="checkbox" name="agree">
						<b class="terms">I agree to abide by the terms and Spirit of this site.</b>
					</li>
					<li>
						<br>
						<img src="/images/register.png" class="btn_register">
						<br>
					</li>
				</ol>
			</form>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		$(".btn_register").click(function() {
			var validate = true;
			$(".error").hide();

			if ($("input[name='register_first_name']").val() == "") {
				$("input[name='register_first_name']").parent().find(".error").show();
				validate = false;
			}
			if ($("input[name='register_last_name']").val() == "") {
				$("input[name='register_last_name']").parent().find(".error").show();
				validate = false;
			}
			if ($("input[name='register_birthday_y']").val() == "" || $("input[name='register_birthday_y']").val() == 0 || isNaN($("input[name='register_birthday_y']").val())) {
				$("input[name='register_birthday_y']").parent().find(".error").show();
				validate = false;
			}
			if ($("select[name='register_birthday_m']").val() == "0") {
				$("select[name='register_birthday_m']").parent().find(".error").show();
				validate = false;
			}
			if ($("select[name='register_birthday_d']").val() == "0") {
				$("select[name='register_birthday_d']").parent().find(".error").show();
				validate = false;
			}
			if ($("input[name='register_email']").val() == "" || !validateEmail($("input[name='register_email']").val())) {
				$("input[name='register_email']").parent().find(".error").show();
				validate = false;
			}
			if ($("input[name='register_username']").val() == "") {
				$("input[name='register_username']").parent().find(".error").show();
				validate = false;
			}
			if ($("input[name='register_password']").val() == "") {
				$("input[name='register_password']").parent().find(".error").show();
				validate = false;
			}
			if ($("input[name='register_password']").val() != $("input[name='register_confirm_password']").val()) {
				$("input[name='register_confirm_password']").parent().find(".error").show();
				validate = false;
			}
			if(!$("input[name='agree']").is(":checked")) {
				$(".terms").css("color", "red");
				validate = false;
			}
			
			if (validate) {
				$("form[name='frmRegister']").submit();
			}
		});

		$("select[name='register_birthday_m']").change(function() {
			set_date();
		});

		$("input[name='register_birthday_y']").blur(function() {
			set_date();
		});

		function set_date() {
			var $year = $("input[name='register_birthday_y']").val();
			var $month = $("select[name='register_birthday_m']").val();

			if ($year == "" || isNaN($year)) {
				$("input[name='register_birthday_y']").focus();
				$("input[name='register_birthday_y']").select();
				return;
			}
			if ($month == 0) {
				$("select[name='register_birthday_m']").focus();
				return;
			}

			days = getNumberOfDays($year, $month);
			$("select[name='register_birthday_d']").html("");
			$("select[name='register_birthday_d']").append($('<option>', {
				value : 0,
				text : "-"
			}));
			for ( i = 1; i <= days; i++) {
				$("select[name='register_birthday_d']").append($('<option>', {
					value : i,
					text : i
				}));
			}
		}

		function getNumberOfDays(year, month) {
			var isLeap = ((year % 4) == 0 && ((year % 100) != 0 || (year % 400) == 0));
			var days = [31, ( isLeap ? 29 : 28), 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

			return (days[month - 1]);
		}

	}); 
</script>
@stop