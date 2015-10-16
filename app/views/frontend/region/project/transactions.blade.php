@extends('layout.region_dashboard')
@section('content')
<script src="/js/jquery.raty.js" language="JavaScript"></script>

<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-10">
		<h2>Transactions</h2>
		<h5> - {{$project_title}} - </h5>
		<ol class="breadcrumb">
			<li>
				<a>Projects</a>
			</li>
			<li>
				<a href="/{{$active=='manages'?'manages/':''}}projects/{{$type}}">{{$type}}</a>
			</li>
			<li class="active">
				<strong>Project List</strong>
			</li>
		</ol>
	</div>
	<div class="col-lg-2">
		<div class="widget style1 navy-bg">
            <div class="row">
                <div class="col-xs-4">
                    <i class="fa fa-dollar fa-4x"></i>
                </div>
                <div class="col-xs-8 text-right">
                    <span> Total </span>
                    <h2 class="font-bold">{{number_format($total, 1)}}</h2>
                </div>
            </div>
        </div>
	</div>
</div>
<div class="wrapper wrapper-content  animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox">
				<div class="ibox-title" style="display:table;width:100%;text-align: right">
					<h5>List of Transaction</h5>
				</div>
				<div class="ibox-content">
					<div class="table-responsive m-t">
						<table class="table">
							<thead>
								<tr>
									<th>No.</th>
									<th>Transaction ID</th>
									<th>Transaction Date</th>
									<th>Total Amount</th>
									<th>Name</th>
									<th>Email</th>
								</tr>
							</thead>
							<tbody>
								<?php $i = 1; foreach($transactions as $one) :?>
									<tr>
										<td>{{$i}}</td>
										<td>{{$one->id}}</td>
										<td>{{$one->created_date}}</td>
										<td><i class="fa fa-dollar"></i>&nbsp;{{number_format($one->amount, 2)}}</td>
										<td>{{$one->name}}</td>
										<td>{{$one->email}}</td>
									</tr>
								<?php $i ++;endforeach;?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@stop