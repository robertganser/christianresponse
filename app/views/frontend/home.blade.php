@extends('layout.blank')
@section('content')

<div class="row">
	<?php if($homepage_video != "") :?>
		<div class="col-md-6">
			<embed width="100%" height="280" src="https://www.youtube.com/embed/{{$homepage_video}}">
		</div>
	<?php endif;?>
	<div class="col-md-{{$homepage_video!=''?'6':'12'}}">
		<h2>Project News</h2>
		<div class="post_feed">
			<ul class="feed_list">
				<?php foreach($news as $one) :?>
					<li data-id="{{$one->id}}" data-type="{{$one->post_type}}" data-date="{{$one->created_date}}">
						<div style="display:table;width:100%">
							<div class="feed_title">
								<a href="/project/view/{{$one->post_type}}/{{$one->id}}">{{$one->title}}</a>
							</div>
							<div class="feed_desc">
								{{date("F d, Y", strtotime($one->created_date))}}
							</div>
						</div>
					</li>
				<?php endforeach;?>
			</ul>
		</div>
	</div>
</div>
<?php if(count($posts) > 0) :?>
	<?php foreach($posts as $one) :?>
		<br><br>
		<div class="intro-area">
			<h2>{{$one->title}}</h2>
			<div>{{str_replace("\r\n", "<br>", $one->content)}}</div>
		</div>
	<?php endforeach;?>
<?php endif;?>
<br>
<br>
<div class="seperator"></div>
<h2 style="text-align: center">Popular Project</h2>
<div class="row">
	<?php foreach($projects as $one) :?>
		<div class="col-md-3">
			<div class="portlet">
				<div class="portlet_wrapper">
					<div class="portlet_thumb" style="height:90px;"><div class="thumb_img" style="background: url('<?php echo $one->thumbnail?>') center center"></div></div>
					<div class="portlet_title"><h3 style="color: #d18022;text-align: left"><?php echo $one->name?></h3></div>
					<div style="width:100%;float:left">
						<div class="portlet_mark"><div id="project-rating-<?php echo $one->id?>" class="rating-mark" data-score="<?php echo $one->review?>"></div></div>
						<div class="portlet_follow">
							<img src="/images/like.png" style="width:15px"> <span><?php echo $one->follow_count?></span>
						</div>
					</div>
					<div style="width:100%;float:left">
						<div class="portlet_link"><a href="/project/view/<?php echo $one->project_type?>/<?php echo $one->id?>">View</a></div>
						<div class="portlet_comment"><?php echo date("F d, Y", strtotime($one->created_date))?></div>
					</div>
				</div>
			</div>
		</div>
	<?php endforeach;?>
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
		
		var intervalID = window.setInterval(function() {
			$.get("/ajax/news", {}, function(response) {
				var $news = response.news;
				var $length = $('.feed_list li').length;
				var flag = true;
				
				if($news.id) {
					$('.feed_list li').each(function() {
						if($(this).attr("data-id") == $news.id && $(this).attr("data-type") == $news.post_type && $(this).attr("data-date") == $news.original_date) {
							flag = false;
							return false;
						}
					});
					
					if(!flag)
						return;
						
					$('.feed_list li:eq(0)').before('<li data-id="'+$news.id+'" data-type="'+$news.post_type+'" data-date="'+$news.original_date+'">' + 
						'<div style="display:table;width:100%">' + 
						'	<div class="feed_title">' + 
						'' + ($news.post_type == "" ? '<a href="/post/view/'+$news.post_type+'">'+$news.title+'</a>' : '<a href="/project/view/'+$news.post_type+'/'+$news.id+'">'+$news.title+'</a>') +  
						'	</div>' + 
						'	<div class="feed_desc">' + 
						'		Posted at '+$news.created_date + 
						'	</div>' + 
						'</div>' + 
					'</li>');
					
					if($length > 4) {
						$('.feed_list li:eq(5)').remove();
					}
				}
			}, "json");
		}, 2000);
		
		resize_portlet();
		
		function resize_portlet() {
			$(".portlet").each(function() {
				width = $(this).parent().width();
				$(this).find(".portlet_title h3").css({
					'width' : (width - 15)+ 'px',
					'overflow' : 'hidden',
					'text-overflow' : 'ellipsis',
					'white-space' : 'nowrap'
				});
			});
		}
		
		$(window).resize(function() {
			resize_portlet();
		});
	});
</script>
@stop