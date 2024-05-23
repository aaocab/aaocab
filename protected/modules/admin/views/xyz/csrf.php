<div class="row">
	<?php
	$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
		'id'					 => 'admin-report', 'enableClientValidation' => true,
		'clientOptions'			 => array(
			'validateOnSubmit'	 => true,
			'errorCssClass'		 => 'has-error'
		),
		// Please note: When you enable ajax validation, make sure the corresponding
		// controller action is handling ajax validation correctly.
		// See class documentation of CActiveForm for details on this,
		// you need to use the performAjaxValidation()-method described there.
		'enableAjaxValidation'	 => false,
		'errorMessageCssClass'	 => 'help-block',
		'htmlOptions'			 => array(
			'class' => '',
		),
	));
	/* @var $form TbActiveForm */
	?>
    <div class="col-xs-12 col-sm-4 col-md-3">
		<?= $form->datePickerGroup($model, 'from_date', array('label' => 'From Date', 'widgetOptions' => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'From Date')), 'prepend' => '<i class="fa fa-calendar"></i>')); ?>
    </div>
    <div class="col-xs-12 col-sm-4 col-md-3">
		<?= $form->datePickerGroup($model, 'to_date', array('label' => 'To Date', 'widgetOptions' => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'To Date')), 'prepend' => '<i class="fa fa-calendar"></i>')); ?>
    </div>
    <div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-2 col-md-2 text-center mt20 p5">
		<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary full-width')); ?>
    </div>
	<?php $this->endWidget(); ?>
</div>
<?php
if (!empty($dataProvider))
{
	$params									 = array_filter($_REQUEST);
	$dataProvider->getPagination()->params	 = $params;
	$dataProvider->getSort()->params		 = $params;
	$this->widget('booster.widgets.TbExtendedGridView', array(
		'responsiveTable'	 => true,
		'fixedHeader'		 => true,
		'headerOffset'		 => 120,
		'dataProvider'		 => $dataProvider,
		'template'			 => "<div class='panel-heading'><div class='row m0'>
                                <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                        </div></div>
                        <div class='panel-body table-responsive' style='max-width: 100%;overflow-x: scroll;'>{items}</div>
                        <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
		'itemsCssClass'		 => 'table table-striped table-bordered dataTable mb0',
		'htmlOptions'		 => array('class' => 'panel panel-primary  compact'),
		'columns'			 => array(
			array('name' => 'adm_fname', 'value' => '$data[adm_fname]." ".$data[adm_lname]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Admin Name'),
			array('name' => 'cntBooking', 'value' => '$data[cntBooking]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Booking Count'),
			array('name' => 'activeBooking', 'value' => '$data[activeBooking]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Active Count'),
			array('name' => 'served_count', 'value' => '$data[served_count]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Served Count'),
			array('name' => 'advance_count', 'value' => '$data[advance_count]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Advance Count'),
			array('name' => 'unserved_advance_count', 'value' => '$data[unserved_advance_count]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Unserved Advance Count'),
			array('name' => 'noOfDiscount', 'value' => '$data[noOfDiscount]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'No Of Discount'),
			//array('name' => 'gozo_amount', 'value' => '$data[gozo_amount]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Gozo Amount'),
			//array('name' => 'service_tax', 'value' => '$data[service_tax]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Service Tax'),
			array('name' => 'total_amount', 'value' => '$data[total_amount]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Total Amount'),
			array('name' => 'totalDiscount', 'value' => '$data[totalDiscount]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Total Discount'),
			array('name' => 'net_gozo_amount', 'value' => '$data[net_gozo_amount]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Net Gozo Amount'),
			array('name' => 'served_gozo_amount', 'value' => '$data[served_gozo_amount]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Served Gozo Amount'),
			array('name' => 'unserved_gozo_amount', 'value' => '$data[unserved_gozo_amount]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'UnServed Adv. Gozo Amount'),
			array('name' => 'expected_gozo_amount', 'value' => '$data[expected_gozo_amount]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'expected Gozo Amount'),
			array('name' => 'marginPercent', 'value' => '$data[marginPercent]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Margin'),
			array('name' => 'new', 'value' => '$data[new1]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'New Calls Booking'),
			array('name' => 'verify', 'value' => '$data[verify]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Manually Verified Booking'),
			array('name' => 'uniqueUnverified', 'value' => '$data[uniqueUnverified]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Unverified Followup'),
			array('name' => 'newLead', 'value' => '$data[newLead]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'New Lead Booking'),
			array('name' => 'newLeadFollow', 'value' => '$data[newLeadFollow]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'New Lead Follow'),
			array('name' => 'nlConvPer', 'value' => '$data[nlConvPer]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'New Lead Conv %'),
			array('name' => 'oldLead', 'value' => '$data[oldLead]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Old Lead Booking'),
			array('name' => 'oldLeadFollow', 'value' => '$data[oldLeadFollow]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Old Lead Follow'),
			array('name' => 'olConvPer', 'value' => '$data[olConvPer]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Old Lead Conv %'),
			array('name' => 'cancelled', 'value' => '$data[cancelled]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Cancelled'),
			// array('name' => 'totalUnverified', 'value' => '$data[totalUnverified]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Total Unverified'),
			// array('name' => 'totalUnverifiedFollowup1', 'value' => '$data[totalUnverifiedFollowup1]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Total Unverified Followup'),
			array('name' => 'uniqueLead', 'value' => '$data[uniqueLead]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Lead Followup'),
			array('name' => 'totalFollowup', 'value' => '$data[totalFollowup]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Total Followup'),
		//array('name' => 'totalLead', 'value' => '$data[totalLead]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Total Lead'),
		//array('name' => 'totalLeadFollowup1', 'value' => '$data[totalLeadFollowup1]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Total Lead Followup'),
	)));
}
?>

