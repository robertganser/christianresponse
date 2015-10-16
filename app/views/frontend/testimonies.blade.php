@extends('layout.blank')
@section('content')
<style>
.highlight {
	font-weight: bold;
	border-bottom: 1px dotted #A0A0A0;
}
</style>
<script src="/js/jquery.highlight.js"></script>

<div style="min-height:600px;">
	<div class="row">
		<div class="col-lg-5">
			<div class="testimony_search">
				<input type="text" name="search" value="<?php echo $search?>" placeholder="Search for Testimonies">
				<input type="button" value="Search" name="search_result">
			</div>
		</div>
	</div>
	<?php if($search == "") :?>
	<div class="row">
		<div class="col-lg-12">
			<h3>No Result.</h3>
		</div>
	</div>
	<?php else : ?>
	<div class="row">
		<div class="col-lg-12">
			<?php foreach($result as $one) :?>
				<div class="hr-line-dashed"></div>
				<div class="row">
					<div class="col-lg-12">
						<div style="float:left"><b>{{$one->first_name}} {{$one->last_name}}</b></div>
						<div style="float:right">Country: <b>{{$one->country}}</b></div>
					</div>
				</div>
				<div class="testimony_row testimony_content">
					<?php echo $one->testimony?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
	<?php endif; ?>
</div>
<script>
	$(document).ready(function() {
		$("input[name='search_result']").click(function() {
			if ($("input[name='search']").val() == "") {
				$("input[name='search']").focus();
				return;
			}

			location.href = "/testimonies/" + $("input[name='search']").val();
		});

		$("input[name='search']").keypress(function(ev) {
			if (ev.keyCode == 13) {
				$("input[name='search_result']").click();
			}
		});
		
		if("<?php echo $search?>" != "") {
			$('.testimony_row').removeHighlight();
			$('.testimony_row').highlight_search( "<?php echo $search?>" );
		}
		
	}); 
</script>
@stop