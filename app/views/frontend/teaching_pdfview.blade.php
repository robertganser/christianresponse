@extends('layout.blank')
@section('content')

<div style="width:100%;margin:0 auto">
	<!--<iframe src="/res/teaching/{{$filename}}" style="width:100%;height:600px"></iframe>-->
	<embed src="{{$filename}}" width="100%" height="800px"></embed>
</div>
@stop