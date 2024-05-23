<?php

$this->widget('booster.widgets.TbGridView', array(
	'id' => 'bookingGrid',
	'responsiveTable' => true,
	'dataProvider' => $dataProvider,
	'template' => "{items}",
	'itemsCssClass' => 'table table-striped table-bordered dataTable mb0',
	'htmlOptions' => array('class' => 'panel panel-primary  compact'),
	// 'ajaxType' => 'POST',
	'columns' => array(
		array('name' => 'vhc_number', 'value' => '$data["vhc_number"]', 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Vehicle Number'),
		array('name' => 'last_city_to', 'value' => '$data["last_city_to"]', 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Last Drop Location'),
		array('name' => 'last_booking', 'type' => 'raw', 'value' => 'CHtml::link($data["last_booking"], Yii::app()->createUrl("rcsr/booking/view",["id"=>$data["last_bkg_id"]]),["class"=>"viewBooking", "onclick"=>"return viewBooking(this)"])', 'sortable' => false, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'LastBooking ID'),
		array('name' => 'last_pickup', 'value' => function ($data) {
				return ($data["last_pickup"] == "") ? "" : DateTimeFormat::DateTimeToLocale($data["last_pickup"]);
			}, 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Last Pickup Time'),
		array('name' => 'next_city_from', 'value' => '$data["next_city_from"]', 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Next Pickup Location'),
		array('name' => 'next_booking', 'type' => 'raw', 'value' => 'CHtml::link($data["next_booking"], Yii::app()->createUrl("rcsr/booking/view",["id"=>$data["next_bkg_id"]]),["class"=>"viewBooking", "onclick"=>"return viewBooking(this)"])', 'sortable' => false, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => ' Next Booking ID'),
		array('name' => 'next_pickup', 'value' => function ($data) {
				return ($data["next_pickup"] == "") ? "" : DateTimeFormat::DateTimeToLocale($data["next_pickup"]);
			}, 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Next Pickup Time'),
		array('name' => 'cav_date_time', 'value' => function ($data) {
				return ($data["cav_date_time"] == "") ? "" : DateTimeFormat::DateTimeToLocale($data["cav_date_time"]);
			}, 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Meter Down Time'),
		array(
			'header' => 'Action',
			'type' => 'raw', 'value' => 'CHtml::link("Assign", Yii::app()->createUrl("rcsr/booking/assigncabdriver", array("vhc" => $data["vhc_id"],"cav" => $data["cav_id"], "booking_id"=>' . $bkid . ', "agtid"=>' . $agtid . ')),["onClick" => "return assignCab(this)", "cav"=>$data["cav"], "class" => "btn btn-xs btn-info assignCab"])',
			'htmlOptions' => array('class' => 'center'),
		)
)));
