@extends('layout.region_dashboard')
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
	<div class="col-lg-12">
		<h2>Search Facilitator</h2>
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
			</div>
		</div>
	</div>
</div>
<div class="row">
	<?php foreach($result as $one) : ?>
	<div class="col-lg-3">
		<div class="contact-box" style="padding:10px 0px">
			<a href="/search/facilitator/single/view/<?php echo $one->id?>">
				<div class="row">
					<div class="col-sm-12">
						<div class="text-center">
							<img alt="image" class="img-circle" src="<?php echo $one->picture == "" ? "/images/default-user.png" : $one->picture?>" width="100px" height="100px">
						</div>
						<br>
						<h3 class="hilight_name text-success" align="center"><strong><?php echo $one->first_name?> <?php echo $one->last_name?></strong></h3>
					</div>
				</div>
				<!--<div class="col-sm-8">
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
				</div>--> 
				<div class="clearfix"></div> 
			</a>
		</div>
	</div>
	<?php endforeach; ?>
</div>
<script>
	$(document).ready(function() {
		$('.contact-box').each(function() {
			animationHover(this, 'pulse');
		});
	}); 
</script>
@stop
