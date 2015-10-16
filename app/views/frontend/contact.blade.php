@extends('layout.default')
@section('content')

<div class="mainbar">
	<div class="article">
		<h2>Contact</h2>
		<p>
			{{$contact->content}}
		</p>
	</div>
	<div class="article">
		<h2>Send us mail</h2>
		<form name="frmContact" action="#" method="post" id="sendemail">
			<ol>
				<li>
					<label for="name">Name (required)</label>
					<input id="name" name="name" class="text" style="width:100%" />
				</li>
				<li>
					<label for="email">Email Address (required)</label>
					<input id="email" name="email" class="text" style="width:100%" />
				</li>
				<li>
					<label for="message">Your Message</label>
					<textarea id="message" name="message" rows="8" style="width:100%"></textarea>
				</li>
				<li>
					<br>
					<a href="javascript:void(0)" name="submit_contact" class="button gray">Submit</a>
					<div class="clr"></div>
				</li>
			</ol>
		</form>
	</div>
</div>
<script>
	$(document).ready(function() {
		$("a[name='submit_contact']").click(function() {
			if ($("input[name='name']").val() == "") {
				$("input[name='name']").focus();
				return;
			}
			if ($("input[name='email']").val() == "" || !validateEmail($("input[name='email']").val())) {
				$("input[name='email']").focus();
				$("input[name='email']").select();
				return;
			}
			if ($("textarea[name='message']").val() == "") {
				$("textarea[name='message']").focus();
				return;
			}

			$("form[name='frmContact']").submit();
		});
	}); 
</script>
@stop