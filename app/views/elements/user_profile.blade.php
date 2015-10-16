<script src="/js/jquery.raty.js"></script>
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-10">
		<br>
		<small>- User Profile -</small>
		<h2><?php echo $profile->first_name?> <?php echo $profile->last_name?></h2>
	</div>
	<div class="col-lg-2">
		<div class="widget style1 lazur-bg" style="margin-bottom:0px">
			<div class="row">
				<div class="col-xs-4">
					<i class="fa fa-newspaper-o fa-3x"></i>
				</div>
				<div class="col-xs-8 text-right">
					<span> Total Projects </span>
					<h2 class="font-bold">{{count($projects)}}</h2>
				</div>
			</div>
		</div>
	</div>
</div>
<br>
<div class="row">
	<div class="row">
		<div class="col-lg-2">
			<div class="avatar"><img src="<?php echo $profile->picture == "" ? "/images/default-user.png" : $profile->picture ?>" width="100%">
			</div>
		</div>
		<div class="col-lg-4">
			<div class="ibox">
				<div class="ibox-title">
					Personal Information
				</div>
				<div class="ibox-content">
					<div class="row">
						<div class="col-lg-12">
							<form class="form-horizontal">
								<div class="form-group">
									<label class="col-sm-3">Name</label>
									<div class="col-sm-9">
										<?php echo $profile->first_name?> <?php echo $profile->last_name?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3">Gender</label>
									<div class="col-sm-9">
										<?php echo $profile->gender == 1 ? "Male" : "Female"?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3">Birthday</label>
									<div class="col-sm-9">
										<?php echo $profile->birthday?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3">Email Address</label>
									<div class="col-sm-9">
										<?php echo $profile->email?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3">Phone Number</label>
									<div class="col-sm-9">
										<?php echo $profile->phone_number?>
									</div>
								</div>
								<div class="hr-line-dashed"></div>
								<div class="form-group">
									<label class="col-sm-12">Testimony: </label>
									<div class="col-sm-12">
										<p style="word-break: break-all;">
											<?php echo $profile->testimony?>
										</p>
									</div>
								</div>
								<div class="hr-line-dashed"></div>
								<div class="form-group">
									<label class="col-sm-12">Mission Statement: </label>
									<div class="col-sm-12">
										<p style="word-break: break-all;">
											<?php echo $profile->mission_statement?>
										</p>
									</div>
								</div>
								<div class="hr-line-dashed"></div>
								<div class="form-group">
									<label class="col-sm-12">Skill / Gifts: </label>
									<div class="col-sm-12">
										<p style="word-break: break-all;">
											<?php echo $profile->skill_gifts?>
										</p>
									</div>
								</div>
								<div class="hr-line-dashed"></div>
								<div class="form-group">
									<label class="col-sm-12">Goals: </label>
									<div class="col-sm-12">
										<p style="word-break: break-all;">
											<?php echo $profile->goals?>
										</p>
									</div>
								</div>
								<div class="hr-line-dashed"></div>
								<div class="form-group">
									<label class="col-sm-12">Ministry Interests: </label>
									<div class="col-sm-12">
										<p style="word-break: break-all;">
											<?php echo $profile->ministry_interests?>
										</p>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-6">
			<div class="ibox">
				<div class="ibox-title">
					Posted Projects
					<div class="pull-right">
						<span class="label label-danger">Followers</span>
						<span class="label label-primary">Reviews</span>
						<span class="label label-warning">Events</span>
					</div>
				</div>
				<div class="ibox-content">
					<div class="table-responsive m-t">
						<table class="table table-hover">
							<tbody>
								<?php foreach($projects as $one) :?>
									<tr>
										<td nowrap="">
											<a href="/search/project/{{$one->type}}/view/{{$one->id}}" style="color:#1c84c6"><h4>{{$one->name}}</h4></a>
											<small class="text-muted"> - {{$one->type}} - </small></td>
										<td nowrap="" align="right">
											<div id="project-rating-{{$one->type}}-{{$one->id}}" class="rating-mark" data-score="{{$one->review}}"></div>
										</td>
										<td nowrap="" align="right">
											{{$one->follow_count > 0 ? "<span class='label label-danger'>".$one->follow_count."</span>" : ""}}
											{{$one->review_count > 0 ? "<span class='label label-primary'>".$one->review_count."</span>" : ""}}
											{{$one->event_count > 0 ? "<span class='label label-warning'>".$one->event_count."</span>" : ""}}
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function() {
		$(".rating-mark").each(function() {
			score = $(this).attr("data-score");
			$(this).raty({
				readOnly : true,
				score : score
			});
		});
	}); 
</script>