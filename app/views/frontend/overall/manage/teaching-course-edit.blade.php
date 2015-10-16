@extends('layout.user_dashboard')
@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-10">
		<h2>Edit Teaching Course</h2>
		<ol class="breadcrumb">
			<li>
				<a href="/manages/teaching-course">Manage</a>
			</li>
			<li>
				<a href="/manages/teaching-course">Teaching Course</a>
			</li>
			<li class="active">
				<strong>Edit</strong>
			</li>
		</ol>
	</div>
</div>
<div class="wrapper wrapper-content  animated fadeInRight">
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
	<div class="row">
		<div class="col-lg-2"></div>
		<div class="col-lg-8">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>Course Information</h5>
				</div>
				<div class="ibox-content">
					<form name="frmTeachingCourse" method="post" class="form-horizontal" enctype="multipart/form-data">
						<div class="form-group">
							<label for="title" class="col-sm-4 control-label">Course Title</label>
							<div class="col-sm-5">
								<input type="text" name="title" id="title" value="{{$info->title}}" class="form-control">
								<label id="-error" class="error" for="" style="display:none">This field is required.</label>
							</div>
						</div>
						<div class="form-group">
							<label for="comment" class="col-sm-4 control-label">Comment</label>
							<div class="col-sm-5">
								<input type="text" name="comment" id="comment" value="{{$info->comment}}" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label for="thumbnail" class="col-sm-4 control-label">Thumbnail</label>
							<div class="col-sm-5">
								<input type="file" name="thumbnail" id="thumbnail" accept="image/*" class="form-control">
								<?php if($info->thumbnail != "") :?>
									<a href="{{$info->thumbnail}}" target="_blank">View Thumbnail</a>
								<?php endif;?>
								<label id="-error" class="error" for="" style="display:none">This field is required.</label>
							</div>
						</div>
						<div class="form-group">
							<label for="pdf" class="col-sm-4 control-label">Course File</label>
							<div class="col-sm-5">
								<input type="file" name="pdf" id="pdf" accept="pdf/*" class="form-control">
								<?php if($info->pdf != "") :?>
									<a href="{{$info->pdf}}" target="_blank">View File</a>
								<?php endif;?>
								<label id="-error" class="error" for="" style="display:none">This field is required.</label>
							</div>
						</div>
						<div class="form-group">
							<label for="order" class="col-sm-4 control-label">Order Number</label>
							<div class="col-sm-5">
								<input type="text" name="order" id="order" value="{{$info->order}}" class="form-control">
							</div>
						</div>
						<div class="hr-line-dashed"></div>
						<div class="form-group" align="center">
							<button type="submit" class="btn btn-primary">
								Save Change
							</button>
							<button type="button" name="cancel" class="btn btn-default">
								Cancel
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="col-lg-2"></div>
	</div>
</div>
<script>
	$(document).ready(function() {
		$("button[name='cancel']").click(function() {
			location.href = "/manages/teaching-course";
		});

		$("form[name='frmTeachingCourse']").submit(function() {
			flag = true;
			$(".error").hide();
			if($("input[name='title']").val() == "") {
				$("input[name='title']").parent().find(".error").show();
				flag = false;
			}
			if( {{$info->id}} == 0 && $("input[name='thumbnail']").val() == "") {
				$("input[name='thumbnail']").parent().find(".error").show();
				flag = false;
			}
			if( {{$info->id}} == 0 && $("input[name='pdf']").val() == "") {
				$("input[name='pdf']").parent().find(".error").show();
				flag = false;
			}
			
			if(!flag) {
				$(document).scrollTop(0);
			}
			
			return flag;
		});
	}); 
</script>
@stop
