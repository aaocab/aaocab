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
		array('name'	 => 'elg_type', 'value'	 => function($data) {
				echo EmailLog::model()->getEmailType($data['elg_type']);
			}, 'sortable'			 => false, 'headerHtmlOptions'	 => array(), 'header'			 => 'Email For'),
		array('name' => 'address', 'value' => '$data["address"]', 'sortable' => false, 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'To Address'),
		array('name' => 'subject', 'value' => '$data["subject"]', 'sortable' => false, 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'Subject'),
		array('name' => 'body', 'value' => 'strip_tags($data["body"])', 'sortable' => false, 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'Email Body'),
)));
