<div class="row">
    <div class="col-xs-12">
		<?php
		$form					 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'volumeTrendForm', 'enableClientValidation' => true,
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

        <div class="col-xs-12 col-sm-3 col-md-3"> 
            <label class="control-label" style="margin-left:5px;">Zone</label>
			<?php
			$zoneListJson			 = Zones::model()->getJSON();
			$this->widget('booster.widgets.TbSelect2', array(
				'model'			 => $model,
				'attribute'		 => 'zon_id',
				'val'			 => $model->zon_id,
				'asDropDownList' => FALSE,
				'options'		 => array('data' => new CJavaScriptExpression($zoneListJson), 'allowClear' => true),
				'htmlOptions'	 => array('style' => 'width:100%;margin-left:5px;', 'placeholder' => 'Zone List')
			));
			?>
        </div>
        <div class="col-xs-12 col-sm-3 col-md-3"> 
            <label class="control-label" style="margin-left:5px;">Zone Type</label>
			<?php
			$source					 = Zones::model()->getSource();
			$flagInfo				 = VehicleTypes::model()->getJSON($source);
			$this->widget('booster.widgets.TbSelect2', array(
				'model'			 => $model,
				'attribute'		 => 'zon_info_source',
				'val'			 => $model->zon_info_source,
				'asDropDownList' => FALSE,
				'options'		 => array('data' => new CJavaScriptExpression($flagInfo), 'allowClear' => true),
				'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Source')
			));
			?>


        </div>
        <div class="col-xs-12 col-sm-3 col-md-3" style="">
            <div class="form-group">
                <label class="control-label">Date Range</label>
				<?php
				$daterang				 = "Select Date Range";
				$zon_bkg_create_date1	 = ($model->zon_bkg_create_date1 == '') ? '' : $model->zon_bkg_create_date1;
				$zon_bkg_create_date2	 = ($model->zon_bkg_create_date2 == '') ? '' : $model->zon_bkg_create_date2;
				if ($zon_bkg_create_date1 != '' && $zon_bkg_create_date2 != '')
				{
					$daterang = date('F d, Y', strtotime($zon_bkg_create_date1)) . " - " . date('F d, Y', strtotime($zon_bkg_create_date2));
				}
				?>
				<div id="bkgPickupDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
					<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
					<span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
				</div>
				<?= $form->hiddenField($model, 'zon_bkg_create_date1'); ?>
				<?= $form->hiddenField($model, 'zon_bkg_create_date2'); ?>

            </div>
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
		$checkExportAccess = Yii::app()->user->checkAccess("Export");
		if ($checkExportAccess)
		{
			?>
			<?= CHtml::beginForm(Yii::app()->createUrl('admin/zone/volumetrend'), "post", ['style' => "margin-bottom: 10px; margin-top: 10px; margin-left: 20px;"]); ?>
			<input type="hidden" id="export1" name="export1" value="true"/>
			<input type="hidden" id="export_zon_id" name="export_zon_id" value="<?= $model->zon_id ?>"/>
			<input type="hidden" id="export_zon_info_source" name="export_zon_info_source" value="<?= $model->zon_info_source ?>"/>
			<input type="hidden" id="export_zon_bkg_create_date1" name="export_zon_bkg_create_date1" value="<?= $model->zon_bkg_create_date1 ?>"/>
			<input type="hidden" id="export_zon_bkg_create_date2" name="export_zon_bkg_create_date2" value="<?= $model->zon_bkg_create_date2 ?>"/>
			<button class="btn btn-default" type="submit" style="width: 185px;">Export Below Table</button>
			<?= CHtml::endForm() ?>
			<?php
		}

		if (!empty($dataProvider))
		{
			$this->widget('booster.widgets.TbGridView', array(
				'responsiveTable'	 => true,
				'dataProvider'		 => $dataProvider,
				'template'			 => "<div class='panel-heading'><div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
				'itemsCssClass'		 => 'table table-striped table-bordered mb0',
				'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
				'columns'			 => array(
					array('name' => 'zon_name', 'value' => $data['zon_name'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2 text-left'), 'header' => 'Zone Name'),
					array('name' => 'count_completed', 'value' => $data['count_completed'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2 text-center'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Completed count'),
					array('name' => 'count_cancelled', 'value' => $data['count_cancelled'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2 text-center'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Cancelled count'),
					array('name'	 => 'gmv_amount', 'value'	 => function($data) {
							echo '<i class="fa fa-inr"></i>' . number_format($data['gmv_amount'], 2);
						}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'GMV'),
					array('name' => 'show_date', 'value' => $data['show_date'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-4 text-left'), 'header' => 'In month')
			)));
		}
		?>
    </div>
</div>

<script>
    var start = '<?= date('d/m/Y', strtotime('-1 month')); ?>';
    var end = '<?= date('d/m/Y'); ?>';

    $('#bkgPickupDate').daterangepicker(
            {
                locale: {
                    format: 'DD/MM/YYYY',
                    cancelLabel: 'Clear'
                },
                "showDropdowns": true,
                "alwaysShowCalendars": true,
                startDate: start,
                endDate: end,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 15 Days': [moment().subtract(15, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                }
            }, function (start1, end1) {
        $('#Zones_zon_bkg_create_date1').val(start1.format('YYYY-MM-DD'));
        $('#Zones_zon_bkg_create_date2').val(end1.format('YYYY-MM-DD'));
        $('#bkgPickupDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
    });
    $('#bkgPickupDate').on('cancel.daterangepicker', function (ev, picker) {
        $('#bkgPickupDate span').html('Select Pickup Date Range');
        $('#Zones_zon_bkg_create_date1').val('');
        $('#Zones_zon_bkg_create_date2').val('');
    });
</script>