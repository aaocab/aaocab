<?php
$this->widget('booster.widgets.TbGridView', array(
	'id' => 'logDetailsGrid',
	'responsiveTable' => true,
	'dataProvider' => $dataProvider,
	'template' => "{items}",
	'itemsCssClass' => 'table table-striped table-bordered dataTable mb0',
	'htmlOptions' => array('class' => 'panel panel-primary  compact'),
	// 'ajaxType' => 'POST',
	'columns' => array(
		array('name' => 'slg_type', 'value' => function($data) {
				echo SmsLog::model()->getSmsType($data['slg_type']);
			}, 'sortable' => false, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Sms For'),
		array('name' => 'number', 'value' => '$data["number"]', 'sortable' => false, 'visible' => '0', 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Number'),
		array('name' => 'body', 'value' => 'strip_tags($data["message"])', 'sortable' => false, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Message'),
                //array('name' => 'booking_id', 'value' => '$data["booking_id"]', 'sortable' => false, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'BookingId'),
                array('name' => 'recipient', 'value' => function($data) {
				echo SmsLog::model()->getRecipient($data['recipient']);
			}, 'sortable' => false, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Recipient'),
                array('name' => 'delivery_response',
                                'value' => function ($data) {
                                        if ($data['delivery_response'] != '')
                                        {
                                                return wordwrap($data['delivery_response'], 20, "<br />\n");
                                        }
                                },
                                'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Response'),
)));
