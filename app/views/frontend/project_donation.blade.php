@extends('layout.blank')
@section('content')
<style>
	#donation_detail {
		border-collapse: collapse;
	}

	#donation_detail thead td {
		border-bottom: 2px solid #808080;
	}

	#donation_detail tbody td {
		border-bottom: 1px solid #d0d0d0;
		height: 30px;
	}

	input[type='text'] {
		color: #5f5f5f;
	}
</style>
<form name="frmDonation" method="post">
	<div style="min-height: 600px">
		<?php if($message != "") :?>
			{{$message}}
		<?php endif;?>
		<h2>Donation Details</h2>
		<div>
			<strong>How much are you going to donate?</strong>&nbsp;&nbsp;
			<input type="text" name="amount" class="text" style="width:100px">
		</div>
		<br>
		<div class="response_table">
			<table width="100%" id="donation_detail">
				<thead>
					<td>Item</td>
					<td>Quantity</td>
					<td>Price</td>
					<td>Tax</td>
					<td>Amount</td>
				</thead>
				<tbody>
					<tr>
						<td>Project will be received</td>
						<td>1</td>
						<td>$
						<input type="text" name="row1_unit_price" id="row1_unit_price" value="0" style="width:40px;border:0" readonly="">
						</td>
						<td>0</td>
						<td>$
						<input type="text" name="row1_total_price" id="row1_total_price" value="0" style="width:40px;border:0" readonly="">
						</td>
					</tr>
					<tr>
						<td>Response will be received</td>
						<td>1</td>
						<td>$
						<input type="text" name="row2_unit_price" id="row2_unit_price" value="0" style="width:40px;border:0" readonly="">
						</td>
						<td>0</td>
						<td>$
						<input type="text" name="row2_total_price" id="row2_total_price" value="0" style="width:40px;border:0" readonly="">
						</td>
					</tr>
					<tr>
						<td>Paypal will be received</td>
						<td>1</td>
						<td>$
						<input type="text" name="row3_unit_price" id="row3_unit_price" value="0" style="width:40px;border:0" readonly="">
						</td>
						<td>0</td>
						<td>$
						<input type="text" name="row3_total_price" id="row3_total_price" value="0" style="width:40px;border:0" readonly="">
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<br>
		<div align="right">
			<table width="35%">
				<tr>
					<td colspan="2" style="border-bottom: 1px solid #d0d0d0"><input type="text" name="name" placeholder="Name:" class="text" style="width: 100%;border:0"></td>
				</tr>
				<tr>
					<td colspan="2" style="border-bottom: 1px solid #d0d0d0"><input type="text" name="email" placeholder="Email:" class="text" style="width: 100%;border:0"></td>
				</tr>
				<tr><td colspan="2">&nbsp;</td></tr>
				<tr height="32px">
					<td width="40%"><strong>Sub Total :</strong></td>
					<td align="right" style="border-bottom: 1px solid #d0d0d0">$ <span id="subtotal_amount">0</span></td>
				</tr>
				<tr height="32px">
					<td><strong>TAX :</strong></td>
					<td align="right" style="border-bottom: 1px solid #d0d0d0">$ 0</td>
				</tr>
				<tr height="32px">
					<td><strong>TOTAL :</strong></td>
					<td align="right" style="border-bottom: 1px solid #d0d0d0">$ <span id="total_amount">0</span></td>
				</tr>
			</table>
			<br>
			<a href="javascript:void(0)" name="process"><img src="/images/paypal-donation.png" width="200px"></a>
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
		
		$("a[name='process']").click(function() {
			if($("input[name='amount']").val() == 0) {
				$("input[name='amount']").focus();
				return;
			}
			if($("input[name='name']").val() == 0) {
				$("input[name='name']").focus();
				return;
			}
			if($("input[name='email']").val() == "" || !validateEmail($("input[name='email']").val())) {
				$("input[name='email']").focus();
				$("input[name='email']").select();
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
@stop