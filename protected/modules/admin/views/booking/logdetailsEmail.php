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
		array('name' => 'address', 'value' => '$data["elg_address"]', 'sortable' => false, 'visible' => '0', 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'To Address'),
		array('name' => 'subject', 'value' => '$data["elg_subject"]', 'sortable' => false, 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'Subject'),
		array('name' => 'body', 'value' => 'strip_tags($data["elg_content"])', 'sortable' => false, 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'Email Body'),
)));
