<?php 
class Paypal{
	var $apiUrl = "https://svcs.sandbox.paypal.com/AdaptivePayments/";
	var $paypalUrl = "https://www.sandbox.paypal.com/us/cgi-bin/webscr?cmd=_ap-payment&paykey="; 
	//every time we call this model
	function __construct(){
			$this->headers = array(
				"X-PAYPAL-SECURITY-USERID: ".Config::get("app.paypal_username"),
				"X-PAYPAL-SECURITY-PASSWORD: ".Config::get("app.paypal_password"),
				"X-PAYPAL-SECURITY-SIGNATURE: ".Config::get("app.paypal_signature"),
				"X-PAYPAL-REQUEST-DATA-FORMAT: JSON",
				"X-PAYPAL-RESPONSE-DATA-FORMAT: JSON",
				"X-PAYPAL-APPLICATION-ID: ".Config::get("app.paypal_appid")
			);
			$this->envelope = array("errorLanguage" => "en_US","detailLevel" => "ReturnAll");
		}//construct close
	//wrapper for getting payment details
	function getPaymentOptions($paykey){
			$packet = array(
				"requestEnvelope" => $this->envelope,
				"payKey" => $paykey
			);
			return $this->_paypalSend($packet,"GetPaymentOptions");
		}//get payment options close
	function executePayment($paykey){
			$packet = array(
				"actionType" => "CREATE",
				"requestEnvelope" => $this->envelope,
				"payKey" => $paykey
			);
			return $this->_paypalSend($packet,"ExecutePayment");
		}//get payment options close
	function paymentDetailsRequest($paykey,$tID,$trID){
			$packet = array(
				"requestEnvelope" => $this->envelope,
				"payKey" => $paykey,
				"transactionId" => $tID,
				"trackingId" => $trID
			);
			return $this->_paypalSend($packet,"PaymentDetailsRequest");
		}//get payment options close
	//curl wrapper for sending things to paypal
	function _paypalSend($data,$call){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->apiUrl.$call);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
		return json_decode(curl_exec($ch),TRUE);
		}// paypal send close
	function splitPay($receiver, $item, $return_url, $cancel_url, $receiverOptions){
		//create pay request
		$createPacet = array(
			"actionType" => "PAY",
			"currencyCode"=> "USD",
			"receiverList" => array("receiver" => $receiver),
			"returnUrl" => $return_url,
			"cancelUrl" => $cancel_url,
			"requestEnvelope" => $this->envelope
		);
		$response = $this->_paypalSend($createPacet,"Pay");
		if(!isset($response['payKey'])) {
			return false;
			exit;
		}
		
		$payKey = $response['payKey'];
		$payment_status = $response['paymentExecStatus'];
		//Set Payment Details
		$detailsPacket = array(
			"requestEnvelope" => $this->envelope,
			"payKey" => $payKey,
			"item" => $item,
			"receiverOptions" => $receiverOptions
		);
		$response = $this->_paypalSend($detailsPacket,"SetPaymentOptions");
		$dets = $this->getPaymentOptions($payKey);
		$detailsPacket = array(
				"requestEnvelope" => $this->envelope,
				"key" => $payKey
		);
		$response = $this->_paypalSend($detailsPacket,"GetShippingAddresses");
		header("Location: ".$this->paypalUrl.$payKey);
	}//split pay close
}