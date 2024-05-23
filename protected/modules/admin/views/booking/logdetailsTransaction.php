<?php

$this->widget('booster.widgets.TbGridView', array(
	'id'				 => 'logDetailsGrid',
	'responsiveTable'	 => true,
	'dataProvider'		 => $dataProvider,
	'template'			 => "{items}",
	'itemsCssClass'		 => 'table table-striped table-bordered dataTable mb0',
	'htmlOptions'		 => array('class' => 'panel panel-primary  compact'),
	// 'ajaxType' => 'POST',
	'columns'			 => array(
//		array('name' => 'trans_ptp_id', 'value' => function($data) {
//				switch ($data['trans_ptp_id'])
//				{
//					case '1':
//						echo "Cash";
//						break;
//					case '2':
//						echo "Cheque";
//						break;
//					case '3':
//						echo "Paytm";
//						break;
//					case '4':
//						echo "Credit Card";
//						break;
//				}
//			}, 'sortable' => false, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Transaction Type'),
		array('name'	 => 'trans_ptp_id', 'value'	 => function($data) {
				if ($data['trans_ptp_id'] != '')
				{
					echo AccountTransDetails::model()->getPayment($data['trans_ptp_id']);
				}
				else
				{
					echo AccountLedger::model()->findByPk($data['adt_ledger_id'])->ledgerName;
				}
			}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'text-center'), 'htmlOptions'		 => array('style' => 'text-align: center;'), 'header'			 => 'Payment Type'),
		//array('name' => 'bkg_booking_id', 'value' => '$data["bkg_booking_id"]', 'sortable' => false, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Booking Id'),
		array('name' => 'trans_code', 'value' => '$data["trans_code"]', 'sortable' => false, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Code'),
		array('name'	 => 'trans_mode', 'value'	 => function($data) {
				if ($data['trans_mode'] == 1)
				{
					echo "Debit";
				}
				else if ($data['trans_mode'] == 2)
				{
					echo "Credit";
				}
			}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'text-center'), 'htmlOptions'		 => array('style' => 'text-align: center;'), 'header'			 => 'Mode'),
		array('name'	 => 'trans_amount', 'value'	 => function($data) {
				if ($data['trans_amount'] > 0)
				{
					echo '<i class="fa fa-inr"></i>' . $data['trans_amount'];
				}
				else if ($data['trans_mode'] == 1 && $data['trans_amount'] < 0)
				{
					echo '<i class="fa fa-inr"></i>' . abs($data['trans_amount']);
				}
			}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'text-center'), 'htmlOptions'		 => array('style' => 'text-align: center;'), 'header'			 => 'Amount'),
		array('name'	 => 'trans_status', 'value'	 => function($data) {
				if ($data['trans_status'] == '2')
				{
					echo "Failure";
				}
				if ($data['trans_status'] == '1')
				{
					echo "Success";
				}
				if ($data['trans_status'] == '0')
				{
					echo "Open";
				}
			}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'text-center'), 'htmlOptions'		 => array('style' => 'text-align: center;'), 'header'			 => 'Status'),
		array('name'	 => 'trans_response_message',
			//'value' => '$data["trans_response_message"]'
			'value'	 => function($data) {
				if ($data['trans_ptp_id'] == 1 || $data['trans_ptp_id'] == 7)
				{
					$message = json_decode($data['trans_response_details'], true);
					echo $message['DESCRIPTION'];
				}
				else
				{
					echo $data['trans_response_message'];
				}
			},
			'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'text-center'), 'htmlOptions'		 => array('style' => 'text-align: center;'), 'header'			 => 'Response Message'),
		array('name'	 => 'trans_complete_datetime', 'filter' => false, 'value'	 => function($data) {
				if ($data['trans_complete_datetime'] != NULL)
				{
					echo date("d/m/Y H:i:s", strtotime($data['trans_complete_datetime']));
				}
			}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Complete Date/Time'),
)));
