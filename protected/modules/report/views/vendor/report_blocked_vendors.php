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
        <div class="text-right">
        </div>    
        <div class="panel panel-default">
            <div class="panel-body">
				<div class="row"> 
					<?php
					$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
						'id'					 => 'blockedVnd-form', 'enableClientValidation' => true,
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
							$vlg_create_date1	 = ($model->vlg_create_date1 == '') ? '' : $model->vlg_create_date1;
							$vlg_create_date2	 = ($model->vlg_create_date2 == '') ? '' : $model->vlg_create_date2;
							if ($vlg_create_date1 != '' && $vlg_create_date2 != '')
							{
								$daterang = date('F d, Y', strtotime($vlg_create_date1)) . " - " . date('F d, Y', strtotime($vlg_create_date2));
							}
							?>
                            <div id="vlgCreateDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                <span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
                            </div>
							<?= $form->hiddenField($model, 'vlg_create_date1'); ?>
							<?= $form->hiddenField($model, 'vlg_create_date2'); ?>

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
					<?= CHtml::beginForm(Yii::app()->createUrl('report/vendor/blockedVendor'), "post", ['style' => "margin-bottom: 10px;"]); ?>
					<input type="hidden" id="export1" name="export1" value="true"/>
					<input type="hidden" id="export_from1" name="export_from1" value="<?= $model->vlg_create_date1 ?>"/>
					<input type="hidden" id="export_to1" name="export_to1" value="<?= $model->vlg_create_date2 ?>"/>
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
						//    'ajaxType' => 'POST',
						'columns'			 => array(
							array('name' => 'stt_zone', 'value' => '$data[Region]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'Region'),
							array('name' => 'vnd_name', 'value' => '$data[vnd_name]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'Vendor Name'),
							array('name' => 'joinDate', 'value' => '$data[joinDate]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Joining Date'),
							array('name' => 'vnd_avg_rating', 'value' => '$data[vnd_avg_rating]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Average Rating'),
							array('name' => 'blocked_date', 'value' => '$data[blocked_date]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Blocked Date'),
							array('name' => 'vlg_desc', 'value' => '$data[vlg_desc]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Remarks'),
					)));
				}
				?>
            </div>  

        </div>  
    </div>
</div>
<script>
    var start = '<?= date('d/m/Y', strtotime('-1 month')); ?>';
    var end = '<?= date('d/m/Y'); ?>';
    $('#vlgCreateDate').daterangepicker(
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
        $('#VendorsLog_vlg_create_date1').val(start1.format('YYYY-MM-DD'));
        $('#VendorsLog_vlg_create_date2').val(end1.format('YYYY-MM-DD'));
        $('#export_from1').val(start1.format('YYYY-MM-DD'));
        $('#export_to1').val(end1.format('YYYY-MM-DD'));


        $('#vlgCreateDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
    });
    $('#vlgCreateDate').on('cancel.daterangepicker', function (ev, picker) {
        $('#vlgCreateDate span').html('Select Date Range');
        $('#VendorsLog_vlg_create_date1').val('');
        $('#VendorsLog_vlg_create_date2').val('');
    });
</script>