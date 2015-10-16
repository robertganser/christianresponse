@extends('layout.overall_dashboard')
@section('content')
<script src="/js/jquery.highlight.js"></script>
<style>
.highlight {
	font-weight: bold;
	border-bottom: 1px dotted #A0A0A0;
}
</style>
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-12">
		<h2>Search Testimony</h2>
	</div>
</div>
<br>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-content">
				<h2> 
					<?php if($total == 0) :?>
						No result
					<?php else :?>
						<?php echo $total?> results found for: <span class="text-navy">“<?php echo $search_key?>”</span>					
					<?php endif;?>
				</h2>
				<div class="search-form">
					<form action="#" method="post">
						<div class="input-group">
							<input type="text" name="search_key" value="<?php echo $search_key?>" class="form-control input-lg">
							<div class="input-group-btn">
								<button class="btn btn-lg btn-primary" type="submit" name="search">
									Search
								</button>
							</div>
						</div>
					</form>
				</div>
				<div class="hr-line-dashed"></div>
				<?php foreach($result as $one) :?>
				<div class="search-result">
					<div class="row">
						<div class="col-lg-1">
							<div class="text-center" align="center">
								<img alt="image" class="img-circle" src="<?php echo $one->picture == "" ? "/images/default-user.png" : $one->picture?>" style="margin-top:-10px" width="107px" height="107px">
							</div>
						</div>
						<div class="col-lg-11">
							<h3><a href="/search/testimony/single/view/<?php echo $one->id?>"><strong><?php echo $one->first_name?> <?php echo $one->last_name?></strong></a></h3>
							<a href="#" class="search-link"><?php echo $one->email?></a>
							<p>
								<div class="testimony_row"><?php echo $one->testimony?></div>
							</p>
						</div>
					</div>
				</div>
				<div class="hr-line-dashed"></div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function() {
		if("<?php echo $search_key?>" != "") {
			$('.testimony_row').removeHighlight();
			$('.testimony_row').highlight_search("<?php echo $search_key?>");
		}
	}); 
</script>
@stop
