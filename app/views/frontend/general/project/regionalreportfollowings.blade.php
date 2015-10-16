@extends('layout.general_dashboard')
@section('content')
<script src="/js/jquery.highlight.js"></script>
<style>
.highlight {
	font-weight: bold;
	border-bottom: 1px dotted #A0A0A0;
	color: #18a689;
}
</style>
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-10">
		<h2><?php echo $project_title?> - Followings</h2>
		<ol class="breadcrumb">
			<li>
				<a>Projects</a>
			</li>
			<li>
				<a href="/{{$active=='manages'?'manages/':''}}projects/report"><?php echo $project_title?></a>
			</li>
			<li class="active">
				<strong>Followings</strong>
			</li>
		</ol>
		<br>
		<span class="label label-info">Report Project</span>
	</div>
	<div class="col-lg-2">
		<div class="widget style1 lazur-bg" style="margin-bottom:0px">
            <div class="row">
                <div class="col-xs-4">
                    <i class="fa fa-envelope-o fa-3x"></i>
                </div>
                <div class="col-xs-8 text-right">
                    <span> Total Followings </span>
                    <h2 class="font-bold">{{count($users)}}</h2>
                </div>
            </div>
        </div>
	</div>
</div>
<div class="wrapper wrapper-content  animated fadeInRight">
<div class="row">
	<?php foreach($users as $one) : ?>
	<div class="col-lg-3">
		<div class="contact-box" style="padding:10px 0px">
			<a href="javascript:void(0)" name="user-row" data-id="{{$one->id}}" data-name="{{$one->first_name}} {{$one->last_name}}">
				<div class="col-sm-4">
					<div class="text-center">
						<img alt="image" class="img-circle" src="<?php echo $one->picture == "" ? "/images/default-user.png" : $one->picture?>" width="100px" height="100px">
					</div>
				</div>
				<div class="col-sm-8">
					<h3 class="hilight_name"><strong><?php echo $one->first_name?> <?php echo $one->last_name?></strong></h3>
					<p>
						<i class="fa fa-map-marker"></i> {{$one->address}}, {{$one->city}}
					</p>
					<address style="margin:0px">
						<br>
						{{$one->state}} {{$one->zip_code}}, {{$one->country}}
						<br>
						<abbr title="Email">E:</abbr> {{$one->email}}
						<br>
						<abbr title="Phone">P:</abbr> (123) 456-7890
					</address>
				</div> 
				<div class="clearfix"></div> 
			</a>
		</div>
	</div>
	<?php endforeach; ?>
</div>
@include("elements.send_email_following")
<script>
	$(document).ready(function() {
		var $selected_id = 0;
		
		$('.contact-box').each(function() {
			animationHover(this, 'pulse');
		});
		
		$("a[name='user-row']").click(function() {
			$selected_id = $(this).attr("data-id");
			var $name = $(this).attr("data-name");
			
			$("#modal-sendmail-following #following_name").html($name);
			$("#modal-sendmail-following").modal();
		});
		
		$("button[name='send_email']").click(function() {
			if($("input[name='subject']").val() == "") {
				$("input[name='subject']").focus();
				return;
			}
			if($("textarea[name='message']").val() == "") {
				$("textarea[name='message']").focus();
				return;
			}
			
			$.post("/ajax/sendmail", {
				user_id : $selected_id,
				subject : $("input[name='subject']").val(),
				message : $("textarea[name='message']").val()
			}, function(response) {
				if(response) {
					alert("Email is sent successfully");
				} else {
					alert("Send mail error.");
				}
				$selected_id = 0;
				$("input[name='subject']").val("");
				$("textarea[name='message']").val("");
				$('#modal-sendmail-following').modal('hide');
			}, "json");
		});
	}); 
</script>
@stop