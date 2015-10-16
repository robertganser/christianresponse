<!DOCTYPE html>
<html itemscope itemtype="http://schema.org/Article">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<title>User Dashboard</title>

		<script src="/js/dashboard/jquery-2.1.1.js"></script>
		<script src="/js/dashboard/bootstrap.min.js"></script>
		<script src="/js/jquery-loadmask.js"></script>

		<link href="/css/dashboard/bootstrap.min.css" rel="stylesheet">
		<link href="/css/dashboard/font-awesome/css/font-awesome.css" rel="stylesheet">

		<!-- Datepicker -->
		<link href="/css/dashboard/plugins/datapicker/datepicker3.css" rel="stylesheet">

		<link href="/css/dashboard/animate.css" rel="stylesheet">
		<link href="/css/dashboard/style.css" rel="stylesheet">
		<link href="/css/loadmask.css" rel="stylesheet">

	</head>

	<body>
		<div id="wrapper">
			<nav class="navbar-default navbar-static-side" role="navigation">
				<div class="sidebar-collapse">
					<ul class="nav" id="side-menu">
						<li class="nav-header">
							<div class="dropdown profile-element">
								<span> <img alt="image" class="img-circle" src="<?php echo Auth::user()->picture?>" width="48px" height="48px"/> </span>
								<span class="block m-t-xs"> <strong class="font-bold" style="color: white"><?php echo Auth::user()->first_name?> <?php echo Auth::user()->last_name?></strong> </span>
							</div>
							<div class="logo-element">
								IN+
							</div>
						</li>
						<li <?php echo $active == "dashboard" ? "class='active'" : ""?>>
							<a href="/dashboard"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboard</span> <span class="label label-primary pull-right">HOME</span></a>
						</li>
						<li <?php echo $active == "manages" ? "class='active'" : ""?>>
							<a href="#"><i class="fa fa-laptop"></i> <span class="nav-label">Manages</span> <span class="fa arrow"></span></a>
							<ul class="nav nav-second-level">
								<li>
									<a href="/manages/users">Users</a>
								</li>
								<li {{isset($sub_active) && $sub_active == "manages-projects" ? "class='active'" : ""}}>
									<a href="#"><span class="nav-label">Projects</span> <span class="fa arrow"></span></a>
									<ul class="nav nav-third-level collapse {{isset($sub_active) && $sub_active == 'manages-projects' ? 'class=\'active\'' : ''}}">
										<li>
											<a href="/manages/projects/prayer">Prayer</a>
										</li>
										<li>
											<a href="/manages/projects/impact">Impact</a>
										</li>
										<li>
											<a href="/manages/projects/report">Report</a>
										</li>
										<li>
											<a href="/manages/projects/teaching">Teaching</a>
										</li>
									</ul>
								</li>
							</ul>
						</li>
						<li <?php echo $active == "settings" ? "class='active'" : ""?>>
							<a href="#"><i class="fa fa-cogs"></i> <span class="nav-label">Settings</span> <span class="fa arrow"></span></a>
							<ul class="nav nav-second-level">
								<li>
									<a href="/settings/profile">Profile</a>
								</li>
								<li >
									<a href="/settings/security">Security</a>
								</li>
							</ul>
						</li>
					</ul>
				</div>
			</nav>

			<div id="page-wrapper" class="gray-bg dashbard-1">
				<div class="row border-bottom">
					<nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
						<div class="navbar-header">
							<a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
						</div>
						<ul class="nav navbar-top-links navbar-right">
							<li>
								<span class="m-r-sm text-muted welcome-message">Welcome to Christian Response.</span>
							</li>
							<li>
								<a href="/account/logout"> <i class="fa fa-sign-out"></i> Log out </a>
							</li>
						</ul>

					</nav>
				</div>
				@yield('content')
			</div>
		</div>

		<!-- Mainly scripts -->
		<script src="/js/dashboard/bootstrap.min.js"></script>
		<script src="/js/dashboard/plugins/validate/jquery.validate.min.js"></script>
		<script src="/js/dashboard/plugins/metisMenu/jquery.metisMenu.js"></script>
		<script src="/js/dashboard/plugins/slimscroll/jquery.slimscroll.min.js"></script>
		<!-- Custom and plugin javascript -->
		<script src="/js/dashboard/inspinia.js"></script>
		<script src="/js/dashboard/plugins/pace/pace.min.js"></script>
		<!-- Datepicker -->
		<script src="/js/dashboard/plugins/datapicker/bootstrap-datepicker.js"></script>
	</body>
</html>