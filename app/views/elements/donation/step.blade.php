<script src="/js/jquery.raty.js"></script>

<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-8">
		<br>
		<small>- {{$project_info->project_type}} -</small>
		<?php if($project_info->is_following > 0) :
		?>
		<span class="label label-info">You have already followed.</span>
		<?php endif; ?>
		<h2>{{$project_info->name}}</h2>
	</div>
	<div class="col-lg-2">
		<div class="widget style1 lazur-bg" style="margin-bottom:0px">
			<div class="row">
				<div class="col-xs-4">
					<i class="fa fa-star fa-3x"></i>
				</div>
				<div class="col-xs-8 text-right">
					<span> Total Reviews </span>
					<h2 class="font-bold">{{$total_review->count}}</h2>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-2">
		<div class="widget style1 lazur-bg" style="margin-bottom:0px">
			<div class="row">
				<div class="col-xs-4">
					<i class="fa fa-heart fa-3x"></i>
				</div>
				<div class="col-xs-8 text-right">
					<span> Total Followers </span>
					<h2 class="font-bold">{{$project_info->follow_count}}</h2>
				</div>
			</div>
		</div>
	</div>
</div>
<?php if(count($transactions) > 0) :?>
	<div class="wrapper wrapper-content animated fadeInRight" style="padding-bottom: 0px">
		<div class="ibox">
			<div class="ibox-title">
				<h5>Donation Transactions</h5>
				<div class="ibox-tools">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                </div>
			</div>
			<div class="ibox-content">
				<div class="table-responsive m-t">
					<table class="table invoice-table">
						<thead>
							<tr>
								<th style="text-align: left">No.</th>
								<th style="text-align: left">Transaction ID</th>
								<th style="text-align: left">Total Amount</th>
								<th style="text-align: left">Transaction Date</th>
							</tr>
						</thead>
						<tbody>
							<?php $i = 1; $sum = 0;foreach($transactions as $one) :?>
								<tr>
									<td style="text-align: left">{{$i}}</td>
									<td style="text-align: left">{{$one->id}}</td>
									<td style="text-align: left">$ {{number_format($one->amount, 2)}}</td>
									<td style="text-align: left">{{date("F d, Y, H:i:s", strtotime($one->created_date))}}</td>
								</tr>
							<?php $i ++; $sum += $one->amount;endforeach;?>
						</tbody>
						<tfoot>
							<td colspan="4" style="text-align: right"><span>Total: $ </span> <b>{{number_format($sum, 2)}}</b></td>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>
<?php endif;?>
<form name="frmDonation" method="POST">
	<div class="wrapper wrapper-content animated fadeInRight">
		<div class="ibox">
			<div class="ibox-content p-xl">
				<?php if($message != "") :?>
					<div class="row"><div class="col-sm-12">{{$message}}</div></div>
				<?php endif?>
				<div class="row">
					<div class="col-sm-6">
						<div class="row" style="display:table;width:100%;margin:10px 0px">
							<div class="col-lg-3">
								<div class="input-group m-b">
									<span class="input-group-addon">$</span>
									<input type="text" name="amount" id="amount" class="form-control" maxlength="5">
									<span class="input-group-addon">.00</span>
								</div>
							</div>
						</div>
					</div>
	
					<div class="col-sm-6 text-right">
						<span>To:</span>
						<address>
							<strong>Christian Response.</strong>
							<br>
							<abbr title="Phone">P:</abbr> {{$overall->phone_number}}
							<br>
							<abbr title="Phone">E:</abbr> {{$overall->email}}
						</address>
						<p>
							<span>{{$project_info->name}}</span><br>
							<span>{{$owner->first_name}} {{$owner->last_name}}</span><br>
							<abbr title="Phone">A:</abbr> {{$owner->address}}, {{$owner->city}}, {{$owner->state}} {{$owner->zip_code}}, {{$owner->country}}</span><br>
							<abbr title="Phone">P:</abbr> {{$owner->phone_number}}<br>
							<abbr title="Phone">E:</abbr> {{$owner->email}}
						</p>
						<p>
							<span><strong>Donation Date:</strong> {{date("F d, Y")}}</span>
						</p>
					</div>
				</div>
	
				<div class="table-responsive m-t">
					<table class="table invoice-table">
						<thead>
							<tr>
								<th>Item List</th>
								<th>Quantity</th>
								<th>Unit Price</th>
								<th>Tax</th>
								<th>Total Price</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
								<div>
									<strong>Project will be received</strong>
								</div></td>
								<td>1</td>
								<td>$ <input type="text" name="row1_unit_price" id="row1_unit_price" value="0" style="width:40px;border:0" readonly=""></td>
								<td>$ 0</td>
								<td>$ <input type="text" name="row1_total_price" id="row1_total_price" value="0" style="width:40px;border:0" readonly=""></td>
							</tr>
							<tr>
								<td>
								<div>
									<strong>Response will be received</strong>
								</div></td>
								<td>1</td>
								<td>$ <input type="text" name="row2_unit_price" id="row2_unit_price" value="0" style="width:40px;border:0" readonly=""></td>
								<td>$ 0</td>
								<td>$ <input type="text" name="row2_total_price" id="row2_total_price" value="0" style="width:40px;border:0" readonly=""></td>
							</tr>
							<tr>
								<td>
								<div>
									<strong>Paypal will be received</strong>
								</div></td>
								<td>1</td>
								<td>$ <input type="text" name="row3_unit_price" id="row3_unit_price" value="0" style="width:40px;border:0" readonly=""></td>
								<td>$ 0</td>
								<td>$ <input type="text" name="row3_total_price" id="row3_total_price" value="0" style="width:40px;border:0" readonly=""></td>
							</tr>
						</tbody>
					</table>
				</div><!-- /table-responsive -->
	
				<table class="table invoice-total">
					<tbody>
						<tr>
							<td><strong>Sub Total :</strong></td>
							<td>$ <span id="subtotal_amount">0</span></td>
						</tr>
						<tr>
							<td><strong>TAX :</strong></td>
							<td>$ 0</td>
						</tr>
						<tr>
							<td><strong>TOTAL :</strong></td>
							<td>$ <span id="total_amount">0</span></td>
						</tr>
					</tbody>
				</table>
				<div class="text-right">
					<button class="btn btn-w-m btn-link" type="button" name="process">
						<!--<img src="/images/pay-paypal.gif">-->
						<img src="/images/paypal-donation.png" width="200px">
					</button>
				</div>
			</div>
		</div>
	</div>
</form>
<script>
	$(document).ready(function() {
		$("input[name='amount']").keypress(function(ev) {
			if (ev.keyCode == 13) {
				calc();
			}
		});
		
		$("input[name='amount']").blur(function() {
			if ($(this).val() > 0) {
				calc();
			} else {
				$(this).val(0);
				calc();
			}
		});
		
		$("button[name='process']").click(function() {
			if($("input[name='amount']").val() == 0) {
				$("input[name='amount']").focus();
				return;
			}
			
			$("form[name='frmDonation']").submit();
		});
	});
	
	
	function calc() {
		var $amount = $("input[name='amount']").val();
		if ($amount == "" || isNaN($amount)) {
			$("input[name='amount']").val(0);
			calc();
		}

		$row1_unit_price = round($amount * .905, 2);
		$row1_total_price = $row1_unit_price;

		$row2_unit_price = round($amount * .055, 2);
		$row2_total_price = $row2_unit_price;

		$row3_unit_price = round($amount * .04, 2);
		$row3_total_price = $row3_unit_price;

		$("#row1_unit_price").val($row1_unit_price);
		$("#row1_total_price").val($row1_total_price);
		$("#row2_unit_price").val($row2_unit_price);
		$("#row2_total_price").val($row2_total_price);
		$("#row3_unit_price").val($row3_unit_price);
		$("#row3_total_price").val($row3_total_price);
		
		$("#subtotal_amount").html(round($row1_unit_price + $row2_total_price + $row3_total_price, 2));
		$("#total_amount").html($("#subtotal_amount").html());
	}

	function round(value, exp) {
		if ( typeof exp === 'undefined' || +exp === 0)
			return Math.round(value);

		value = +value;
		exp = +exp;

		if (isNaN(value) || !( typeof exp === 'number' && exp % 1 === 0))
			return NaN;

		// Shift
		value = value.toString().split('e');
		value = Math.round(+(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp)));

		// Shift back
		value = value.toString().split('e');
		return +(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp));
	}
</script>