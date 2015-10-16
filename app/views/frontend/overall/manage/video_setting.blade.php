@extends('layout.overall_dashboard')
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-10">
		<h2>Video Setting</h2>
		<ol class="breadcrumb">
			<li>
				<a href="/manage/video-setting">Manage</a>
			</li>
			<li>
				<a href="/manage/region">Video Setting</a>
			</li>
		</ol>
	</div>
</div>
<div class="wrapper wrapper-content  animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<?php if($message) :
			?>
			<div class="row">
				<div class="col-lg-1"></div>
				<div class="col-lg-10">
					<?php echo $message
					?>
				</div>
				<div class="col-lg-1"></div>
			</div>
			<?php endif; ?>
			<div class="ibox">
				<div class="ibox-content">
					<form name="frmVideoSetting" method="post" class="form-horizontal" enctype="multipart/form-data">
						<div class="form-group">
							<div class="form-group">
								<label for="homepage_video" class="col-sm-4 control-label">Homepage Video</label>
								<div class="col-sm-5">
									<div class="input-group">
										<span class="input-group-addon" style="background: #f0f0f0">https://www.youtube.com/watch?v=</span>
										<input type="text" name="homepage_video" id="homepage_video" value="{{$video->homepage_video}}" class="form-control">
									</div>
									<span class="help-block m-b-none">Only uploads videos from Youtube. Copy id from URL which is everything after the = sign in the URL</span>
								</div>
							</div>
							<div class="form-group">
								<label for="teaching_video" class="col-sm-4 control-label">Teaching Video</label>
								<div class="col-sm-5">
									<div class="input-group">
										<span class="input-group-addon" style="background: #f0f0f0">https://www.youtube.com/watch?v=</span>
										<input type="text" name="teaching_video" id="teaching_video" value="{{$video->teaching_video}}" class="form-control">
									</div>
									<span class="help-block m-b-none">Only uploads videos from Youtube. Copy id from URL which is everything after the = sign in the URL</span>
								</div>
							</div>
							<div class="form-group">
								<label for="testimony_video" class="col-sm-4 control-label">Testimony Video</label>
								<div class="col-sm-5">
									<div class="input-group">
										<span class="input-group-addon" style="background: #f0f0f0">https://www.youtube.com/watch?v=</span>
										<input type="text" name="testimony_video" id="testimony_video" value="{{$video->testimony_video}}" class="form-control">
									</div>
									<span class="help-block m-b-none">Only uploads videos from Youtube. Copy id from URL which is everything after the = sign in the URL</span>
								</div>
							</div>
							<div class="form-group">
								<label for="mission_video" class="col-sm-4 control-label">Mission Statement Video</label>
								<div class="col-sm-5">
									<div class="input-group">
										<span class="input-group-addon" style="background: #f0f0f0">https://www.youtube.com/watch?v=</span>
										<input type="text" name="mission_video" id="mission_video" value="{{$video->mission_video}}" class="form-control">
									</div>
									<span class="help-block m-b-none">Only uploads videos from Youtube. Copy id from URL which is everything after the = sign in the URL</span>
								</div>
							</div>
							<div class="form-group">
								<label for="gifts_video" class="col-sm-4 control-label">Skill/Gifts Video</label>
								<div class="col-sm-5">
									<div class="input-group">
										<span class="input-group-addon" style="background: #f0f0f0">https://www.youtube.com/watch?v=</span>
										<input type="text" name="gifts_video" id="gifts_video" value="{{$video->gifts_video}}" class="form-control">
									</div>
									<span class="help-block m-b-none">Only uploads videos from Youtube. Copy id from URL which is everything after the = sign in the URL</span>
								</div>
							</div>
							<div class="form-group">
								<label for="goals_video" class="col-sm-4 control-label">Goals Video</label>
								<div class="col-sm-5">
									<div class="input-group">
										<span class="input-group-addon" style="background: #f0f0f0">https://www.youtube.com/watch?v=</span>
										<input type="text" name="goals_video" id="goals_video" value="{{$video->goals_video}}" class="form-control">
									</div>
									<span class="help-block m-b-none">Only uploads videos from Youtube. Copy id from URL which is everything after the = sign in the URL</span>
								</div>
							</div>
							<div class="form-group">
								<label for="interests_video" class="col-sm-4 control-label">Ministry Interests Video</label>
								<div class="col-sm-5">
									<div class="input-group">
										<span class="input-group-addon" style="background: #f0f0f0">https://www.youtube.com/watch?v=</span>
										<input type="text" name="interests_video" id="interests_video" value="{{$video->interests_video}}" class="form-control">
									</div>
									<span class="help-block m-b-none">Only uploads videos from Youtube. Copy id from URL which is everything after the = sign in the URL</span>
								</div>
							</div>
							<div class="hr-line-dashed"></div>
							<div class="col-sm-4 col-sm-offset-4">
								<button class="btn btn-primary" type="submit" name="save">
									Save changes
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
		$("form[name='frmVideoSetting']").submit(function() {
			if($("input[name='homepage_video']").val() == "") {
				$("input[name='homepage_video']").focus();
				return false;
			}
			if($("input[name='teaching_video']").val() == "") {
				$("input[name='teaching_video']").focus();
				return false;
			}
			return true;
		});
	}); 
</script>
@stop