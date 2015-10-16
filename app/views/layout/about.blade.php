<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--
Design by http://www.bluewebtemplates.com
Released for free under a Creative Commons Attribution 3.0 License
-->
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Christian Response</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<?php include("include/css.inc")
		?>
		<?php include("include/js.inc")
		?>
	</head>
	<body>
		<div class="main">
			@include('elements.header')

			<div class="content">
				<div class="content_resize container">
					@yield('content')
					<div class="clr"></div>
				</div>
			</div>

			<div class="footer">
				<div class="footer_resize container">
					<ul class="fmenu">
						<li <?php echo isset($key) && $key == "home" ? "class='active'" : ""?>>
							<a href="/">Home</a>
						</li>
						<li <?php echo isset($key) && $key == "project" ? "class='active'" : ""?>>
							<a href="/project">Project</a>
						</li>
						<li <?php echo isset($key) && $key == "teaching" ? "class='active'" : ""?>>
							<a href="/teaching">Teaching</a>
						</li>
						<li <?php echo isset($key) && $key == "testimonies" ? "class='active'" : ""?>>
							<a href="/testimonies">Testimonies</a>
						</li>
						<li <?php echo isset($key) && $key == "about" ? "class='active'" : ""?>>
							<a href="/about-us">About Us</a>
						</li>
						<li <?php echo isset($key) && $key == "contact" ? "class='active'" : ""?>>
							<a href="/contact">Contact Us</a>
						</li>
					</ul>
					<p class="lf">
						&copy; Copyright: Christian Response Inc  2015
					</p>
					<div class="clr"></div>
				</div>
			</div>
		</div>
	</body>
	<!--Start of Zopim Live Chat Script-->
	<script type="text/javascript">
		window.$zopim || (function(d,s){
			var z = $zopim = function(c){
				z._.push(c)
			},
			$ = z.s = d.createElement(s),e=d.getElementsByTagName(s)[0];
			z.set=function(o){
				z.set._.push(o);
			};
			z._=[];
			z.set._=[];
			$.async=!0;
			$.setAttribute("charset","utf-8");
			$.src="//v2.zopim.com/?33ajL3JxfLZCKv6E5rYLSYFY3jaZpVnl";
			z.t=+new Date;
			$.type="text/javascript";
			e.parentNode.insertBefore($,e);
		})(document,"script");
	</script>
	<!--End of Zopim Live Chat Script-->
</html>
