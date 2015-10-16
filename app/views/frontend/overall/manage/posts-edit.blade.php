@extends('layout.overall_dashboard')
@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-10">
		<h2>Edit Post</h2>
		<ol class="breadcrumb">
			<li>
				<a href="/manages/posts">Manage</a>
			</li>
			<li>
				<a href="/manages/posts">Post</a>
			</li>
			<li class="active">
				<strong>Edit</strong>
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
		</div>
	</div>
	<form name="frmPost" method="POST">
		<div class="row">
			<div class="col-lg-12">
				<div class="ibox float-e-margins">
					<div class="ibox-title" style="display:table;width:100%;padding-top:10px">
						<div class="row">
							<div class="col-lg-9">
								<input type="text" name="title" value="{{$post->title}}" placeholder="Post Title: " class="form-control">
							</div>
							<div class="col-lg-3" align="right">
								<button type="submit" class="btn btn-primary">
									Save
								</button>
								<button type="button" name="cancel" class="btn btn-default">
									Cancel
								</button>
							</div>
						</div>
					</div>
					<div class="ibox-content">
						<textarea name="content" class='form-control' rows="10">{{$post->content}}</textarea>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
<script>
	$(document).ready(function() {
		$("button[name='cancel']").click(function() {
			location.href = "/manages/posts";
		});

		$("form[name='frmPost']").submit(function() {
			if ($("input[name='title']").val() == "") {
				$("input[name='title']").focus();
				return false;
			}
			return true;
		});
	}); 
</script>
@stop
