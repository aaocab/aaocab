<div class="row">
    <div class="col-xs-12">
		<?php
		/* @var $model Vendors */
		$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'financialForm', 'enableClientValidation' => true,
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
			<?=
			$form->datePickerGroup($model, 'bkg_pickup_date1', array('label'			 => 'From Date',
				'widgetOptions'	 => array('options'		 => array(
						'autoclose'	 => true, 'startDate'	 => date(), 'format'	 => 'dd/mm/yyyy'),
					'htmlOptions'	 => array('placeholder' => 'From Date', 'value'=>$from_date)),
				'prepend'		 => '<i class="fa fa-calendar"></i>'));
			?>
        </div>
        <div class="col-xs-12 col-sm-4 col-md-3"> 
			<?=
			$form->datePickerGroup($model, 'bkg_pickup_date2', array('label'			 => 'To Date',
				'widgetOptions'	 => array('options'		 => array(
						'autoclose'	 => true, 'startDate'	 => date(), 'format'	 => 'dd/mm/yyyy'),
					'htmlOptions'	 => array('placeholder' => 'To Date', 'value'=>$to_date)),
				'prepend'		 => '<i class="fa fa-calendar"></i>'));
			?>
        </div>
        <div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-2 col-md-2 text-center mt20" style="padding: 4px;">
            <button class="btn btn-primary" type="submit" style="width: 185px;"  name="zoneSearch">Search</button> 
        </div>
		<?php $this->endWidget(); ?>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
		<?php
		$checkExportAccess	 = Yii::app()->user->checkAccess("Export");
		if ($checkExportAccess)
		{
		?>
			<?= CHtml::beginForm(Yii::app()->createUrl('admin/report/financialReport'), "post", ['style' => "margin-bottom: 10px; margin-top: 10px; margin-left: 20px;"]); ?>
			<input type="hidden" id="export1" name="export1" value="true"/>
			<input type="hidden" id="export_from" name="export_from" value="<?= $from_date ?>"/>
			<input type="hidden" id="export_to" name="export_to" value="<?= $to_date; ?>"/>
			<button class="btn btn-default" type="submit" style="width: 185px;">Export Below Table</button>
			<?= CHtml::endForm() ?>
			<?php
		}
		if (!empty($dataProvider))
		{
			$this->widget('booster.widgets.TbGridView', array(
				'responsiveTable'	 => true,
				'dataProvider'		 => $dataProvider,
				'template'			 => "<div class='panel-heading'>
                                        <div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div>
                                            <div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                        </div>
                                    </div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'>
                                        <div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 p5'>{summary}</div>
                                            <div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                        </div>
                                    </div>",
				'itemsCssClass'		 => 'table table-striped table-bordered mb0',
				'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
				'columns'			 => array(
					array('name' => 'pickupDateRange', 'value' => $data['pickupDateRange'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Year - Month'),
					array('name' => 'tripCreated', 'value' => $data['tripCreated'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Bookings created'),
					array('name' => 'tripCancelled', 'value' => $data['tripCancelled'], 'sortable' => false, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Cancellations'),
					array('name' => 'tripCompleted', 'value' => $data['tripCompleted'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Trips completed'),
					array('name' => 'matchTripCompleted', 'value' => $data['matchTripCompleted'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Match Trip completed'),
					array('name' => 'B2cCompleted', 'value' => $data['B2cCompleted'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'B2C Count<br>(Completed)'),
					array('name' => 'B2bCompleted', 'value' => $data['B2bCompleted'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'B2B Count<br>(Completed)'),
					array('name' => 'gmv_created', 'value' => $data['gmv_created'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-right'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Bookings created amount'),
					array('name' => 'gmv_completed', 'value' => $data['gmv_completed'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Bookings completed amount'),
					array('name' => 'vendorAmount', 'value' => $data['vendorAmount'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Total vendor amount'),
					array('name' => 'matchVendorAmount', 'value' => $data['matchVendorAmount'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Vendor amount matched trips'),
					array('name' => 'unMatchVendorAmount', 'value' => $data['unMatchVendorAmount'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Vendor amount unmatched trips'),
					array('name' => 'matchGozoAmount', 'value' => $data['matchGozoAmount'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Gozo amount matched trips'),
					array('name' => 'unmatchGozoAmount', 'value' => $data['unmatchGozoAmount'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Gozo amount unmatched trips'),
					array('name' => 'gozoAmount', 'value' => $data['gozoAmount'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Total Gozo amount'),
					array('name' => 'B2CgozoAmount', 'value' => $data['B2CgozoAmount'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'B2C Gozo amount'),
					array('name' => 'B2BgozoAmount', 'value' => $data['B2BgozoAmount'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'B2B Gozo amount'),
					
			)));
		}
		?>
    </div>
</div>