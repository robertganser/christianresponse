@extends('layout.overall_dashboard')
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-10">
		<h2>Project Donation Details</h2>
		<span class="label label-primary" style="font-size:12px">- {{$type}} -</span>
	</div>
	<div class="col-lg-2">
		<div class="widget style1 lazur-bg" style="margin-bottom:0px">
            <div class="row">
                <div class="col-xs-4">
                    <i class="fa fa-usd fa-3x"></i>
                </div>
                <div class="col-xs-8 text-right">
                    <h2 class="font-bold">{{number_format($total, 2)}}</h2>
                </div>
            </div>
        </div>
	</div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="ibox">
		<div class="ibox-title">
			<h4>List of Projects</h4>
		</div>
		<div class="ibox-content">
			<div class="table-responsive m-t">
				<table class="table">
					<thead>
						<tr>
							<th>No.</th>
							<th>Project Title</th>
							<th>Project Location</th>
							<th>Project Owner</th>
							<th>Total Amount</th>
						</tr>
					</thead>
					<tbody>
						<?php $i = 1; foreach($list as $one) :?>
							<tr>
								<td>{{$i}}</td>
								<td>{{$one->name}}</td>
								<td>{{$one->state}} {{$one->zip_code}}, {{$one->country}}</td>
								<td>{{$one->username}}</td>
								<td><i class="fa fa-dollar"></i>&nbsp;{{number_format($one->amount, 2)}}</td>
							</tr>
						<?php $i ++; endforeach;?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

@stop