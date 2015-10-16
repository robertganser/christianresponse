<div class="header">
	<div class="header_resize container">
		<div class="row" style="position:relative">
			<div class="login_bar">
				<a href="javascript:void(0)" name="sign_in">Sign In</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp; <a href="/account/register">Join Free</a>
			</div>
			<table width="100%" style="border-collapse: collapse">
				<tr>
					<td class="logo">
						<a href="/"><img src="/images/CRI_-_Name_-_Transparent.png" style="width:100%"></a>
					</td>
					<td valign="bottom" align="right">
						<div class="menu_nav" align="right">
							<ul>
								<li class="responsive_menu_ico">
									<a href="#" id="ico_responsive" style="background:url('/images/responsive_menu_ico.png') center center;background-size: cover">&nbsp;&nbsp;&nbsp;&nbsp;</a>
								</li>
								<li <?php echo isset($key) && $key == "contact" ? "class='active'" : ""?>>
									<a href="/contact">Contact Us</a>
								</li>
								<li <?php echo isset($key) && $key == "about" ? "class='active'" : ""?>>
									<a href="/about-us">About Us</a>
								</li>
								<li <?php echo isset($key) && $key == "testimonies" ? "class='active'" : ""?>>
									<a href="/testimonies">Testimonies</a>
								</li>
								<li <?php echo isset($key) && $key == "teaching" ? "class='active'" : ""?>>
									<a href="/teaching">Teaching</a>
								</li>
								<li <?php echo isset($key) && $key == "project" ? "class='active'" : ""?>>
									<a href="/project">Project</a>
								</li>
								<li <?php echo isset($key) && $key == "home" ? "class='active'" : ""?>>
									<a href="/">Home</a>
								</li>
							</ul>
						</div>
					</td>
				</tr>
			</table>
		</div>
		<div class="clr"></div>
		<div class="responsive_menu">
			<div>
				<ul>
					<li <?php echo isset($key) && $key == "home" ? "class='active'" : ""?> data-key="">Home</li>
					<li <?php echo isset($key) && $key == "project" ? "class='active'" : ""?> data-key="project">Project</li>
					<li <?php echo isset($key) && $key == "teaching" ? "class='active'" : ""?> data-key="teaching">Teaching</li>
					<li <?php echo isset($key) && $key == "testimonies" ? "class='active'" : ""?> data-key="testimonies">Testimonies</li>
					<li <?php echo isset($key) && $key == "about" ? "class='active'" : ""?> data-key="about-us">About Us</li>
					<li <?php echo isset($key) && $key == "contact" ? "class='active'" : ""?> data-key="contact">Contact Us</li>
				</ul>
			</div>
		</div>
	</div>
</div>
<div id="sign_in" class="lightbox">
	<form name="frmLogin" method="post">
		<br>
		<div id="frmLogin_area">
			<h2 align="center">Sign In</h2>
			<div id="signin_error" style="width:80%;margin:0 auto"></div>
			<div style="width:60%;margin:0 auto">
				<input type="text" name="username" placeholder="Email or Username">
			</div>
			<div style="width:60%;margin:0 auto">
				<input type="password" name="password" placeholder="*******************">
			</div>
			<div style="width:60%;margin:0 auto;">
				<div id="sign_in_btt" class="sign-in-btn">
					<a href="javascript:void(0)"> Sign In </a>
				</div>
			</div>
			<div style="width:60%;margin:0 auto;margin-top: 5px" align="right">
				<a href="#" name="lnk_forgot">Forgot Password</a>
			</div>
		</div>
		<div id="frmForgot_area" style="display:none">
			<h2 align="center">Forgot Password</h2>
			<div id="forgot_error" style="width:80%;margin:0 auto"></div>
			<div style="width:60%;margin:0 auto">
				<input type="text" name="forgot_email" placeholder="Email Address">
			</div>
			<div style="width:60%;margin:0 auto;">
				<div id="forgot_btt" class="sign-in-btn">
					<a href="javascript:void(0)"> Send mail </a>
				</div>
			</div>
			<div style="width:60%;margin:0 auto;margin-top: 5px" align="right">
				<a href="#" name="back_login">Back to Login</a>
			</div>
		</div>
		<div align="center" style="background:url('/images/login-sprite.png') center center;height:35px"></div>
		<br>
		<table width="100%">
			<tr>
				<td width="10%"></td>
				<td width="38%" align="right"><img src="/images/signin_facebook.png" id="login_facebook" style="width:98%;cursor:pointer"></td>
				<td width="4%"></td>
				<td width="38%" align="left"><img src="/images/signin_google.png" id="login_google" style="width:98%;cursor:pointer"></td>
				<td width="10%"></td>
			</tr>
		</table>
		<br>
		<div class="signin-bottom">
			Don't have an account? <a href="/account/register" style="color: #32489a"><b>Sign Up</b></a>
		</div>
	</form>
</div>
<script>
	$(document).ready(function() {
		$("a[name='lnk_forgot']").click(function() {
			$("#signin_error").html("");
			$("#forgot_error").html("");
			$("input[name='username']").val("");
			$("input[name='password']").val("");
			$("input[name='forgot_email']").val("");
			
			$("#frmLogin_area").hide();
			$("#frmForgot_area").show();
		});
		
		$("a[name='back_login']").click(function() {
			$("#signin_error").html("");
			$("#forgot_error").html("");
			$("input[name='username']").val("");
			$("input[name='password']").val("");
			$("input[name='forgot_email']").val("");

			$("#frmLogin_area").show();
			$("#frmForgot_area").hide();
		});
		
		$("#login_facebook").click(function() {
			location.href = "/account/oauth/facebook";
		});

		$("#login_google").click(function() {
			location.href = "/account/oauth/google";
		});
		
		$("#ico_responsive").click(function() {
			var $parent = $(this).parent();

			if($(".responsive_menu").is(":visible")) {
				$(".responsive_menu").hide();
			} else {
				$(".responsive_menu").show();
			}
		});
		
		$(".responsive_menu li").click(function() {
			var key = $(this).attr("data-key");
			$(".responsive_menu").hide();
			if(key == "") {
				location.href = "/";
			} else {
				location.href = "/" + key;
			}
		});
	}); 
</script>