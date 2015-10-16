@extends('layout.blank')
@section('content')
<?php if($teaching_video != "") :?>
	<div style="width:60%;margin:0 auto">
		<embed width="100%" height="350" src="https://www.youtube.com/embed/{{$teaching_video}}">
	</div>
	<br>
<?php endif;?>
<h2 style="text-align: center">Our Teaching</h2>
<section id="least">
	<ul class="least-gallery">
		<?php foreach($courses as $one) :?>
			<li>
				<a href="/teaching/view/{{$one->id}}" title="{{$one->title}}"> 
					<img src="{{$one->thumbnail}}" alt="Alt Image Text" /> 
				</a>
			</li>
		<?php endforeach;?>
	</ul>
</section>

<script>
	$(document).ready(function() {
		$('.least-gallery').least();
	}); 
</script>
@stop