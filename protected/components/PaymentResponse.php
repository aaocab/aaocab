<?php

class PaymentResponse extends CComponent
{

	const modeList = [
		1	 => 'CC', // – Credit Card 		
		2	 => 'DC', // – Debit Card 		 
		3	 => 'NB', // – NetBanking 	
		4	 => 'UPI', // – UPI		 
		5	 => 'CASH', // – Cash Card 		 
		6	 => 'EMI', // – EMI 				
		7	 => 'IVR', // – IVR 				
		8	 => 'COD', // – Cash On Delivery 	
		9	 => 'CLEMI', //	– Cardless EMI 	 
		10	 => 'WALLET', // – WALLET	 
	];
	const TYPE_CC		 = 1;
	const TYPE_DC		 = 2;
	const TYPE_NB		 = 3;
	const TYPE_UPI	 = 4;
	const TYPE_CASH	 = 5;
	const TYPE_EMI	 = 6;
	const TYPE_IVR	 = 7;
	const TYPE_COD	 = 8;
	const TYPE_CLEMI	 = 9;
	const TYPE_WALLET	 = 10;

	public $payment_status;
	public $payment_status_type;
	public $payment_type;
	public $message;
	public $mode;
	public $fullResponse;
	public $response;
	public $response_code;
	public $transaction_code;
	public $payment_code;

}
