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
		array('name' => 'bkg_booking_id', 'type' => 'raw', 'value' => 'CHtml::link($data->bkg_booking_id, Yii::app()->createUrl("rcsr/booking/view",["id"=>$data->bkg_id]),["class"=>"viewBooking", "onclick"=>"return viewBooking(this)"])', 'sortable' => false, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'Booking ID'),
		array('name' => 'bkg_user_name', 'value' => '$data->bkg_user_name." ".$data->bkg_user_lname', 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Name'),
		array('name' => 'bkgFromCity.cty_name', 'value' => '$data->bkgFromCity->cty_name', 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'From City'),
		array('name' => 'bkgToCity.cty_name', 'value' => '$data->bkgToCity->cty_name', 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'To City'),
		array('name' => 'bkg_pickup_date',
			'value' => function ($data) {
				return DateTimeFormat::DateTimeToLocale($data->bkg_pickup_date);
			},
			'sortable' => true, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'Pickup Date/Time'),
		array('name' => 'bkg_vehicle_id', 'value' => '$data->bkgSvcClassVhcCat->scc_VehicleCategory->vct_desc', 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Cab Type'),
		array(
			'header' => 'Action',
			'type' => 'raw', 'value' => 'CHtml::link("Assign", Yii::app()->createUrl("rcsr/booking/assignvendor", array("ref_bkg_id"=>$data->bkg_id, "bkid"=>' . $bkid . ')),["class" => "btn btn-xs btn-info assignRefBooking", "onclick"=>"return vendorAssigned(this);"])',
			'htmlOptions' => array('class' => 'center'),
		)
)));
