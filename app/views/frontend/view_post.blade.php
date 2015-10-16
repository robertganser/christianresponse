@extends('layout.blank')
@section('content')

<div style="width:90%;margin:0 auto;min-height:500px;">
	<div class="article">
		<h2>{{$post->title}}</h2>
		- Posted at {{date("F d, Y H:i", strtotime($post->created_date))}}
		<p>
			<div style="width:100%">
				<iframe id="post_content" style="border:0;width:100%;height:100%" src="/post/view/content/{{$post->id}}" onload="iframeLoaded()"></iframe>
			</div>
		</p>
	</div>
</div>
<script>
	function iframeLoaded() {
		var iFrameID = document.getElementById('post_content');
		if (iFrameID) {
			// here you can make the height, I delete it first, then I make it again
			iFrameID.height = "";
			iFrameID.style.height = iFrameID.contentWindow.document.body.scrollHeight + "px";
		}
	}
</script>
@stop