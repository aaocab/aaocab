<?php

if (!empty(${"dataProvider" . $bid}))
{
	$this->widget('booster.widgets.TbGridView', array(
		'id' => 'bookingTab' . $bid,
		'responsiveTable' => true,
		'dataProvider' => ${"dataProvider" . $bid},
		'template' => "<div class='panel-heading'><div class='row m0'>
                                <div class='col-xs-12 col-sm-6 pt5'>{summary}</div>
                                <div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                </div></div>
                                <div class='panel-body table-responsive'>{items}</div>
                                <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
		'itemsCssClass' => 'table table-striped table-bordered dataTable mb0',
		'htmlOptions' => array('class' => 'panel panel-primary  compact'),
		// 'ajaxType' => 'POST',
		'columns' => array(
			array('name' => 'bkg_booking_id', 'type' => 'raw', 'value' => 'CHtml::link($data->bkg_booking_id, Yii::app()->createUrl("rcsr/booking/view",["id"=>$data->bkg_id]),["class"=>"viewBooking", "onclick"=>"return viewBooking(this)"])', 'sortable' => true, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'Booking ID'),
			array('name' => 'bkg_user_name', 'value' => '$data->bkg_user_name." ".$data->bkg_user_lname', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Name'),
			array('name' => 'bkg_contact_no',
				'value' => function ($data) {
					if ($data->bkg_contact_no != '')
					{
						return '+' . $data->bkg_country_code . $data->bkg_contact_no;
					}
				},
				'sortable' => false, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'Phone'),
			array('name' => 'bkg_user_email', 'value' => '$data->bkg_user_email', 'sortable' => false, 'headerHtmlOptions' => array(), 'htmlOptions' => array(), 'header' => 'Email'),
			array('name' => 'bkgFromCity.cty_name', 'value' => '$data->bkgFromCity->cty_name', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'From City'),
			array('name' => 'bkgToCity.cty_name', 'value' => '$data->bkgToCity->cty_name', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'To City'),
			array('name' => 'bkg_amount', 'value' => '$data->bkg_amount', 'sortable' => true, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'Amount'),
			array('name' => 'bkg_platform', 'value' => '$data->getPlatform()', 'sortable' => false, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'Source'),
			array('name' => 'bkg_create_date',
				'value' => function ($data) {
					return DateTimeFormat::DateTimeToLocale($data->bkg_create_date);
				}, 'sortable' => true, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'Booking Date/Time'),
			//   array('name' => 'bkg_pickup_address', 'value' => '$data->bkg_pickup_address', 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Pickup Address'),
			array('name' => 'bkg_pickup_date',
				'value' => function ($data) {
					return DateTimeFormat::DateTimeToLocale($data->bkg_pickup_date);
				},
				'sortable' => true, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'Pickup Date/Time'),
			array('name' => 'bkgVendor.vnd_name', 'value' => '$data->bkgVendor->vnd_name', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Vendor Name'),
			array('name' => 'bkg_vehicle_id', 'value' => '$data->bkgSvcClassVhcCat->scc_VehicleCategory->vct_desc', 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Cab Type'),
			array('name' => 'Action', 'type' => 'raw', 'value' => '$data->getActionButton()', 'sortable' => false, 'headerHtmlOptions' => array('style' => 'min-width:150px;'), 'header' => 'Action'),
	)));
}
?>