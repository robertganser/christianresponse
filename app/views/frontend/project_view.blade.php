@extends('layout.project_view')
@section('content')
<div class="mainbar">
	<?php if(Session::get("error") != "") :?>
		{{Session::get("error")}}
	<?php endif;?>
	<div class="article">
		<h2><span itemprop="name">{{$info->name}}</span></h2>
		<h3>{{$project_type}} Project</h3>
		- Posted at {{date("F d, Y h:i A", strtotime($info->created_date))}}<br>
		- Location: {{$info->address}}, {{$info->city}}, {{$info->state}} {{$info->zip_code}}, {{$info->country}}
	</div>
	<div class="article">
		<?php if($info->intro_video != "") :?>
			<div style="display:table;width:100%">
				<embed width="100%" height="350" src="https://www.youtube.com/embed/{{$info->intro_video}}">
				<!--<video width="100%" height="350" controls>
					<source src="{{$info->intro_video}}">
					Your browser does not support the video tag.
				</video>-->
			</div>
			<br>
		<?php endif; ?>
		<p itemprop="description">
			<span itemprop="description">{{$info->description}}</span>
		</p>
		<div>
			<?php if(strtolower($project_type) == "regionalreport") :?>
				<div class="row">
					<div class="col-md-12"><strong>How would you define where the region is?</strong> <i>{{$info->defined_region}}</i></div>
				</div><br>
				<div class="row">
					<div class="col-md-12"><strong>Who has compiled this regional report?</strong> <i>{{$info->report_owner}}</i></div>
				</div><br>
				<div class="row">
					<div class="col-md-12"><strong>Does the report align with the national report?</strong> <i>{{$info->report_align_option==1?"Yes":""}}</i></div>
				</div><br>
				<div class="row">
					<div class="col-md-12"><strong>How do you want to communicate this local report in your region?</strong> 
						<i>
							<?php if($info->communication_type==1):?>
								Regional function
							<?php elseif($info->communication_type==2) :?>
								Website
							<?php elseif($info->communication_type==3) :?>
								Local churches
							<?php elseif($info->communication_type==4) :?>
								Other
							<?php endif;?>
						</i>
					</div>
				</div><br>
				<div class="row">
					<div class="col-md-12"><strong>What are some faith/vision statements or prayers for your region?</strong> <i>{{$info->vision_statement}}</i></div>
				</div><br>
				<div class="row">
					<div class="col-md-12"><strong>How does the church use the media/internet for his purposes?</strong> 
						<i>
							<?php if($info->curch_use_type==1):?>
								Internet
							<?php elseif($info->curch_use_type==2) :?>
								Facebook
							<?php elseif($info->curch_use_type==3) :?>
								Mobile Apps
							<?php elseif($info->curch_use_type==4) :?>
								Twitter
							<?php elseif($info->curch_use_type==5) :?>
								Print
							<?php elseif($info->curch_use_type==6) :?>
								TV
							<?php endif;?>
						</i>
					</div>
				</div><br>
				<div class="row">
					<div class="col-md-12"><strong>What has happened of significance in your region in the church historically?</strong> <i>{{$info->significance_happen}}</i></div>
				</div><br>
				<div class="row">
					<div class="col-md-12"><strong>Are there Christian Spiritual ancestors that you want to respect and recognise in this region?</strong> <i>{{$info->ancestor}}</i></div>
				</div><br>
				<div class="row">
					<div class="col-md-12"><strong>What is your goal and strategy for achieving this goal in your region?</strong> <i>{{$info->goal_strategy}}</i></div>
				</div><br>
				<div class="row">
					<div class="col-md-12"><strong>Population</strong> <i>{{$info->population}}</i></div>
				</div><br>
				<div class="row">
					<div class="col-md-12"><strong>Number of christians</strong> <i>{{$info->christian_number}}</i></div>
				</div><br>
				<div class="row">
					<div class="col-md-12"><strong>Who are the living Spiritual fathers?</strong> <i>{{$info->spiritual_father}}</i></div>
				</div><br>
				<div class="row">
					<div class="col-md-12"><strong>Is there a national report?</strong> <i>{{$info->national_report_option==1?"Yes":"No"}}</i></div>
				</div><br>
				<div class="row">
					<div class="col-md-12"><strong>If yes to what is the link to it?</strong> <i>{{$info->link}}</i></div>
				</div><br>
				<div class="row">
					<div class="col-md-12"><strong>What is the national vision?</strong> <i>{{$info->national_vision}}</i></div>
				</div><br>
				<div class="row">
					<div class="col-md-12"><strong>What are the areas of greatest social need in your region?</strong> <i>{{$info->social_area}}</i></div>
				</div><br>
				<div class="row">
					<div class="col-md-12"><strong>Describe the economy both historically and at present.</strong> <i>{{$info->description_economy}}</i></div>
				</div><br>
				<div class="row">
					<div class="col-md-12"><strong>What churches do you have in your region and describe them?</strong> <i>{{$info->churches}}</i></div>
				</div><br>
				<div class="row">
					<div class="col-md-12"><strong>Quantitatively state what the church do in your region to help?</strong> <i>{{$info->quantitatively_state}}</i></div>
				</div><br>
				<div class="row">
					<div class="col-md-12"><strong>How many people get saved each year?</strong> <i>{{$info->yearly_people_count}}</i></div>
				</div><br>
				<div class="row">
					<div class="col-md-12"><strong>Describe crime in the area?</strong> <i>{{$info->description_crime}}</i></div>
				</div><br>
				<div class="row">
					<div class="col-md-12"><strong>What is the suicide rate?</strong> <i>{{$info->suicide_rate}}</i></div>
				</div><br>
				<div class="row">
					<div class="col-md-12"><strong>Do schools have a Christian witness?</strong> <i>{{$info->has_christian_witness}}</i></div>
				</div><br>
				<div class="row">
					<div class="col-md-12"><strong>Does business help the community?</strong> <i>{{$info->help_community}}</i></div>
				</div><br>
				<div class="row">
					<div class="col-md-12"><strong>Is there significant occult or anti Christian activity?</strong> <i>{{$info->occult_activity}}</i></div>
				</div><br>
				<div class="row">
					<div class="col-md-12"><strong>Where and when are the prayer meetings?</strong> <i>{{$info->prayer_meeting}}</i></div>
				</div><br>
				<div class="row">
					<div class="col-md-12"><strong>What evangelism programs are there in your region?</strong> <i>{{$info->evangelism_program}}</i></div>
				</div><br>
			<?php elseif(strtolower($project_type) == "nationalreport") :?>
				<div class="row">
					<div class="col-md-12"><strong>Are you going to organize an event to present your report?</strong> <i>{{$info->organize_option_report==1?"Yes":"No"}}</i></div>
				</div><br>
				<div class="row">
					<div class="col-md-12"><strong>When is the national day of prayer and fasting?</strong> <i>{{$info->national_date}}</i></div>
				</div><br>
				<div class="row">
					<div class="col-md-12"><strong>Are you going to organize an event for this day?</strong> <i>{{$info->organize_option_day==1?"Yes":"No"}}</i></div>
				</div><br>
				<div class="row">
					<div class="col-md-12"><strong>Write some prayers for the nation</strong>: <i>{{$info->nation_prayers}}</i></div>
				</div><br>
				<div class="row">
					<div class="col-md-12"><strong>Link to operation world information about the nation</strong>: <i>{{$info->world_link}}</i></div>
				</div><br>
				<div class="row">
					<div class="col-md-12"><strong>What great Christian leaders have there been in the past and what is their story?</strong> <i>{{$info->past_story}}</i></div>
				</div><br>
				<div class="row">
					<div class="col-md-12"><strong>Relevant facts about the Spiritual condition of the nation</strong>: <i>{{$info->relevant_fact}}</i></div>
				</div><br>
				<div class="row">
					<div class="col-md-12"><strong>What is needed in the nation?</strong> <i>{{$info->nation_need}}</i></div>
				</div><br>
			<?php endif;?>
		</div>
		<div class="article" style="width:100%">
			<h3>News</h3>
			<div id="communication">
				<?php foreach($communications as $one) :?>
				<div class="communication-row">
					<table width="100%">
						<tr>
							<td rowspan="2" width="32px" valign="top"><img src="{{$one->picture}}" width="30px"></td>
							<td valign="top"><b>{{$one->name}}</b></td>
							<td align="right"><span style="color:#a0a0a0;font-size:11px">{{date("F d, Y h:i A", strtotime($one->created_date))}}</span></td>
						</tr>
						<tr>
							<td colspan="2">
							<div class="communication-content">
								{{$one->text}}
							</div></td>
						</tr>
					</table>
				</div>
				<?php endforeach; ?>
			</div>
		</div>
		<!--<div style="display:table;width:100%">
			<form name="frmPostFeedback" action="" method="post">
				<h3>Give a feedback</h3>
				<div style="margin:5px auto">
					<div class="give_rating-mark" data-score="0"></div>
				</div>
				<ol>
					<li>
						<label for="name">Name (required)</label>
						<input id="name" name="feedback_name" class="text" style="width:300px">
					</li>
					<li>
						<label for="email">Email (required)</label>
						<input id="name" name="feedback_email" class="text" style="width:300px">
					</li>
					<li>
						<label for="email">Comment</label>
						<textarea name="feedback_comment" style="height:60px;min-width:300px"></textarea>
					</li>
					<li>
						<a href="javascript:void()" class="button gray" name="post_feedback" style="margin-top:10px">Submit</a>
					</li>
				</ol>
				<input type="hidden" name="action" value="feedback">
			</form>
		</div>-->
	</div>
</div>
<script>
	$(document).ready(function() {
		$(".give_rating-mark").each(function() {
			score = $(this).attr("data-score");
			$(this).raty({
				readOnly : false,
				score : score,
				scoreName: 'feedback_score'
			});
		});

		$("a[name='post_feedback']").click(function() {
			if ($("input[name='feedback_name']").val() == "") {
				$("input[name='feedback_name']").focus();
				return;
			}
			if ($("input[name='feedback_email']").val() == "") {
				$("input[name='feedback_email']").focus();
				return;
			}
			if (!validateEmail($("input[name='feedback_email']").val())) {
				$("input[name='feedback_email']").focus();
				$("input[name='feedback_email']").select();
				return;
			}
			if ($("textarea[name='feedback_comment']").val() == "") {
				$("textarea[name='feedback_comment']").focus();
				return;
			}
			if($("input[name='feedback_score']").val() == 0) {
				alert("Please give a feedback marks as five star.:)");
				return;
			}

			$("form[name='frmPostFeedback']").submit();
		});
	}); 
</script>
<?php Session::set("error", ""); ?>
@stop