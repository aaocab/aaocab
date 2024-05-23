<div class="row">
    <div class="col-xs-12">
		<?php
		/* @var $model Vendors */
		$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'regionPerfForm', 'enableClientValidation' => true,
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
			$form->datePickerGroup($model, 'vnd_create_date1', array('label'			 => 'From Date',
				'widgetOptions'	 => array('options'		 => array(
						'autoclose'	 => true, 'startDate'	 => date(), 'format'	 => 'dd/mm/yyyy'),
					'htmlOptions'	 => array('placeholder' => 'From Date')),
				'prepend'		 => '<i class="fa fa-calendar"></i>'));
			?>
        </div>
        <div class="col-xs-12 col-sm-4 col-md-3"> 
			<?=
			$form->datePickerGroup($model, 'vnd_create_date2', array('label'			 => 'To Date',
				'widgetOptions'	 => array('options'		 => array(
						'autoclose'	 => true, 'startDate'	 => date(), 'format'	 => 'dd/mm/yyyy'),
					'htmlOptions'	 => array('placeholder' => 'To Date')),
				'prepend'		 => '<i class="fa fa-calendar"></i>'));
			?>
        </div>
        <div class="col-xs-12 col-sm-4 col-md-3">Select Region
			<?php
			$regionList			 = VehicleTypes::model()->getJSON(Vendors::model()->getRegionList());
			$this->widget('booster.widgets.TbSelect2', array(
				'model'			 => $model,
				'attribute'		 => 'vnd_region',
				'val'			 => $model->vnd_region,
				'asDropDownList' => FALSE,
				'options'		 => array('data' => new CJavaScriptExpression($regionList), 'allowClear' => true),
				'htmlOptions'	 => array('class' => 'p0', 'style' => 'width: 100%', 'placeholder' => 'Select Region')
			));
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
		$checkExportAccess	 = false;
		if ($roles['rpt_export_roles'] != null)
		{
			$checkExportAccess = Filter::checkACL($roles['rpt_export_roles']);
		}
		if ($checkExportAccess)
		{
			?>
			<?= CHtml::beginForm(Yii::app()->createUrl('report/area/regionperf'), "post", ['style' => "margin-bottom: 10px; mavendor/regionperfrgin-top: 10px; margin-left: 20px;"]); ?>
			<input type="hidden" id="export1" name="export1" value="true"/>
			<input type="hidden" id="export_from" name="export_from" value="<?= $model->vnd_create_date1 ?>"/>
			<input type="hidden" id="export_to" name="export_to" value="<?= $model->vnd_create_date2; ?>"/>
			<input type="hidden" id="export_region" name="export_region" value="<?= $model->vnd_region; ?>"/>
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
					array('name' => 'region', 'value' => $data['region'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Region'),
					array('name' => 'vnd_name', 'value' => $data['vnd_name'], 'sortable' => false, 'headerHtmlOptions' => array('class' => 'col-xs-2 text-center'), 'header' => 'Vendor'),
					array('name' => 'vnd_overall_rating', 'value' => $data['vnd_overall_rating'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Rating'),
					array('name' => 'bookings_assigned', 'value' => $data['bookings_assigned'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Bookings assigned'),
					array('name' => 'bookings_assigned_advance', 'value' => $data['bookings_assigned_advance'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Bookings assigned (advance paid)'),
					array('name' => 'bookings_assigned_cod', 'value' => $data['bookings_assigned_cod'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Bookings assigned (post-paid/COD)'),
					array('name' => 'bookings_cancelled', 'value' => $data['bookings_cancelled'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Bookings total cancellations'),
					array('name' => 'bookings_cancelled_advance', 'value' => $data['bookings_cancelled_advance'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Bookings Cancellations (advance)'),
					array('name' => 'booking_cancelled_cod', 'value' => $data['booking_cancelled_cod'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Bookings cancellations (COD)'),
					array('name'	 => 'booking_amount', 'value'	 => function ($data) {
							echo '<i class="fa fa-inr"></i>' . number_format($data['booking_amount'], 0);
						}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Amount'),
					array('name'	 => 'vendor_amount', 'value'	 => function ($data) {
							echo '<i class="fa fa-inr"></i>' . number_format($data['vendor_amount'], 0);
						}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Vendor Amount')
			)));
		}
		?>
    </div>
</div>