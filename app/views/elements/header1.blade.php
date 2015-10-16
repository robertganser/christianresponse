<div class="header">
	<div class="header_resize">
		<div class="logo">
			<a href="/"><img src="/images/CRI_-_Name_-_Transparent.png"></a>
		</div>
		<div class="login_bar">
			<div style="float:left">
				Username / Email
				<br>
				<input type="text" name="username">
			</div>
			<div style="float:left;margin-left: 10px">
				Password
				<br>
				<input type="password" name="password">
			</div>
			<div style="float:left;margin-left: 10px">
				<br>
				<button type="button" name="login" class="login">
					Login
				</button>
			</div>
			<div style="float:left">
				<br>
				<button type="button" name="register" class="login" onclick="location.href='/account/register'">
					Register Now
				</button>
			</div>
		</div>
		<div class="menu_block_bottom">
			<div class="menu_nav">
				<ul>
					<li <?php echo isset($key) && $key == "home" ? "class='active'" : ""?>>
						<a href="/">Home</a>
					</li>
					<li <?php echo isset($key) && $key == "project" ? "class='active'" : ""?>>
						<a href="/project">Project</a>
					</li>
					<li <?php echo isset($key) && $key == "teaching" ? "class='active'" : ""?>>
						<a href="/teaching">Teaching</a>
					</li>
					<li <?php echo isset($key) && $key == "about" ? "class='active'" : ""?>>
						<a href="/about-us">About Us</a>
					</li>
					<li <?php echo isset($key) && $key == "contact" ? "class='active'" : ""?>>
						<a href="/contact">Contacts</a>
					</li>
				</ul>
			</div>
		</div>
		<div class="clr"></div>
	</div>
</div>