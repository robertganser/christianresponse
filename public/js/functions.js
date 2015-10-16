function validateEmail(email) {
	var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	return re.test(email);
}

$(document).ready(function() {
	$("a[name='sign_in']").click(function() {
		$("#sign_in #signin_error").html("");
		$("#sign_in input[name='username']").val("");
		$("#sign_in input[name='password']").val("");
		$("#sign_in").lightbox_me();
	});
	
	$("input[name='username']").keypress(function(ev) {
		if(ev.keyCode == 13) {
			$("#sign_in_btt").click();
		}
	});
	
	$("input[name='password']").keypress(function(ev) {
		if(ev.keyCode == 13) {
			$("#sign_in_btt").click();
		}
	});
	
	$("#forgot_btt").click(function() {
		if($("input[name='forgot_email']").val() == "" || !validateEmail($("input[name='forgot_email']").val())) {
			$("input[name='forgot_email']").focus();
			$("input[name='forgot_email']").select();
			return;
		}
		
		$("body").mask("");
		
		$.post("/account/forgot_password", {
			email : $("input[name='forgot_email']").val()
		}, function(res) {
			$("body").unmask();
			if(res.success) {
				$("#forgot_error").html(res.message);
			} else {
				error = res.error;
				$("#forgot_error").html(error);
			}
		}, "json");
	});
	
	$("#sign_in_btt").click(function() {
		$("#signin_error").html("");
		if ($("input[name='username']").val() == "") {
			$("input[name='username']").focus();
			return;
		}
		if ($("input[name='password']").val() == "") {
			$("input[name='password']").focus();
			return;
		}
		
		$("body").mask("");
		
		$.post("/account/login_check", {
			username : $("input[name='username']").val(),
			password : $("input[name='password']").val()
		}, function(res) {
			$("body").unmask();
			if(res.success) {
				$("form[name='frmLogin']").attr("action", "/preload").submit();
			} else {
				error = res.error;
				$("#signin_error").html(error);
			}
		}, "json");
	});
	
	$("button[name='login']").click(function() {
		if ($("input[name='username']").val() == "") {
			$("input[name='username']").focus();
			return;
		}
		if ($("input[name='password']").val() == "") {
			$("input[name='password']").focus();
			return;
		}

		$.post("/account/login", {
			username : $("input[name='username']").val(),
			password : $("input[name='password']").val()
		}, function(res) {
			if(res.success) {
				location.href = "/dashboard";
			} else {
				alert("Username or password is not correct. Please try again");
			}
		}, "json");
	});
});
