<style>
    .panel-body{
        padding-top: 0 ;
        padding-bottom: 0;
    }
    .table>tbody>tr>th
    {
        vertical-align: middle
    }

    .table>tbody>tr>td, .table>tbody>tr>th{
        padding: 7px;
        line-height: 1.5em;
    }
</style>
<div class="row m0">
    <div class="col-xs-12">
		<div class="panel panel-default">
            <div class="panel-body">
				<div class="row"> 
					<?php
					$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
						'id'					 => 'vendor-form',
						'enableClientValidation' => true,
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

                    <div class="col-xs-12 col-sm-4 col-md-4" style="">
                        <div class="form-group">
                            <label class="control-label">Date Range</label>
							<?php
							$daterang			 = "Select Date Range";
							$bkg_create_date1	 = ($model->bkg_create_date1 == '') ? date('Y-m-01') : $model->bkg_create_date1;
							$bkg_create_date2	 = ($model->bkg_create_date2 == '') ? date("Y-m-t") : $model->bkg_create_date2;
							if ($bkg_create_date1 != '' && $bkg_create_date2 != '')
							{
								$daterang = date('F d, Y', strtotime($bkg_create_date1)) . " - " . date('F d, Y', strtotime($bkg_create_date2));
							}
							?>
                            <div id="bkgCreateDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                <span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
                            </div>
							<?= $form->hiddenField($model, 'bkg_create_date1'); ?>
							<?= $form->hiddenField($model, 'bkg_create_date2'); ?>

                        </div></div>
					<div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-2 col-md-2 text-center mt20 p5">   
						<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary full-width')); ?></div>
					<?php $this->endWidget(); ?>
                </div>
				<?php
				$checkExportAccess = false;
				if ($roles['rpt_export_roles'] != null)
				{
					$checkExportAccess = Filter::checkACL($roles['rpt_export_roles']);
				}
				if ($checkExportAccess)
				{
					?>
					<?= CHtml::beginForm(Yii::app()->createUrl('report/vendor/vendorCancellation'), "post", ['style' => "margin-bottom: 10px;"]); ?>
					<input type="hidden" id="export1" name="export1" value="true"/>
					<input type="hidden" id="export_from1" name="export_from1" value="<?= $model->bkg_create_date1 ?>"/>
					<input type="hidden" id="export_to1" name="export_to1" value="<?= $model->bkg_create_date2 ?>"/>
					<button class="btn btn-default" type="submit" style="width: 185px;">Export Below Table</button>

					<?php
					echo CHtml::endForm();
				}
				if (!empty($dataProvider))
				{

					$params									 = array_filter($_REQUEST);
					$dataProvider->getPagination()->params	 = $params;
					$dataProvider->getSort()->params		 = $params;
					$this->widget('booster.widgets.TbGridView', array(
						'responsiveTable'	 => true,
						'dataProvider'		 => $dataProvider,
						'template'			 => "<div class='panel-heading'><div class='row m0'>
                                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                                    </div></div>
                                                    <div class='panel-body table-responsive'>{items}</div>
                                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
						'itemsCssClass'		 => 'table table-striped table-bordered dataTable mb0',
						'htmlOptions'		 => array('class' => 'panel panel-primary  compact'),
						'columns'			 => array(
							array('name' => 'vendor_id', 'value' => '$data[vendor_id]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'Vendor Id'),
							array('name' => 'vnd_name', 'value' => '$data[vendor_name]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'Vendor Name'),
							array('name' => 'total_vendor_assigned_count', 'value' => '$data[total_vendor_assigned_count]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Total Assigned'),
							array('name' => 'total_vendor_served_count', 'value' => '$data[total_vendor_served_count]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Total Served'),
							array('name'	 => 'total_vendor_cancel_count', 'value'	 => function ($data) {
									echo $data['total_vendor_cancel_count'] . " (" . round(($data['total_vendor_cancel_count'] * 100) / $data['total_vendor_assigned_count'], 2) . "%)";
								},
								'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Total Cancellations'),
					)));
				}
				?>
			</div>  

		</div>  
	</div>
</div>
<script>
    var start = '<?= ($model->bkg_create_date1 == '') ? date('01/m/Y') : date('d/m/Y', strtotime($model->bkg_create_date1)); ?>';
    var end = '<?= ($model->bkg_create_date2 == '') ? date("t/m/Y") : date('d/m/Y', strtotime($model->bkg_create_date2)); ?>';
    function setFilter(obj)
    {
        $('#export_filter1').val(obj.value);
    }
    $('#bkgCreateDate').daterangepicker(
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
        $('#Booking_bkg_create_date1').val(start1.format('YYYY-MM-DD'));
        $('#Booking_bkg_create_date2').val(end1.format('YYYY-MM-DD'));
        $('#bkgCreateDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
    });
    $('#bkgCreateDate').on('cancel.daterangepicker', function (ev, picker) {
        $('#bkgCreateDate span').html('Select Create Date Range');
        $('#Booking_bkg_create_date1').val('');
        $('#Booking_bkg_create_date2').val('');
    });
</script>