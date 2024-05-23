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
						'id'					 => 'stickyVendor-form', 'enableClientValidation' => true,
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
							$bkg_pickup_date1	 = ($model->bkg_pickup_date1 == '') ? '' : $model->bkg_pickup_date1;
							$bkg_pickup_date2	 = ($model->bkg_pickup_date2 == '') ? '' : $model->bkg_pickup_date2;
							if ($bkg_pickup_date1 != '' && $bkg_pickup_date2 != '')
							{
								$daterang = date('F d, Y', strtotime($bkg_pickup_date1)) . " - " . date('F d, Y', strtotime($bkg_pickup_date2));
							}
							?>
                            <div id="bkgPickupDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                <span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
                            </div>
							<?= $form->hiddenField($model, 'bkg_pickup_date1'); ?>
							<?= $form->hiddenField($model, 'bkg_pickup_date2'); ?>

                        </div>
					</div>
					<div class="col-xs-12 col-sm-4 col-md-4"> 
						<div class="form-group">
							<label>Zone: </label>
							<?php
							$datazone = Zones::model()->getZoneArrByFromBooking();
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'sourcezone',
								'val'			 => $model->sourcezone,
								'data'			 => $datazone,
								'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => 'multiple',
									'placeholder'	 => 'Select Zone')
							));
							?>
						</div>
					</div>
					<div class="col-xs-6  col-sm-4 col-md-3 col-lg-2">
						<div class="form-group">
							<label class="control-label">Region </label>
							<?php
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'bkg_region',
								'val'			 => $model->bkg_region,
								'data'			 => Vendors::model()->getRegionList(),
								'htmlOptions'	 => array('class'			 => 'p0', 'multiple'		 => 'multiple',
									'style'			 => 'width: 100%', 'placeholder'	 => 'Select Region')
							));
							?>
						</div>
					</div>
					<div class="col-xs-12 col-sm-4 col-md-4"> 
						<div class="form-group">
							<label>State: </label>
							<?php
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'bkg_state',
								'val'			 => $model->bkg_state,
								'data'			 => States::model()->getIndiaStateList1(),
								'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => 'multiple',
									'placeholder'	 => 'Select State')
							));
							?>
						</div>
					</div>
					<div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-2 col-md-2 text-center mt20 p4">   
						<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary full-width')); ?></div>
						<?php $this->endWidget(); ?>
					<div class="col-xs-1">
						<?php
						$checkExportAccess = false;
						if ($roles['rpt_export_roles'] != null)
						{
							$checkExportAccess = Filter::checkACL($roles['rpt_export_roles']);
						}
						if ($checkExportAccess)
						{
							echo CHtml::beginForm(Yii::app()->createUrl('report/area/StickyVendorCount'), "post", []);
							?>
							<input type="hidden" id="bkg_pickup_date1" name="bkg_pickup_date1" value="<?= $model->bkg_pickup_date1 ?>"/>
							<input type="hidden" id="bkg_pickup_date2" name="bkg_pickup_date2" value="<?= $model->bkg_pickup_date2 ?>"/>
							<input type="hidden" id="sourcezone" name="sourcezone" value="<?= implode(",", $model->sourcezone) ?>"/>
							<input type="hidden" id="bkg_region" name="bkg_region" value="<?= implode(",", $model->bkg_region) ?>"/>
							<input type="hidden" id="bkg_state" name="bkg_state" value="<?= implode(",", $model->bkg_state) ?>"/>
							<input type="hidden" id="export" name="export" value="true"/>
							<button class="btn btn-default btn-5x pr30 pl30 mt20" type="submit" style="width: 185px;">Export</button>
							<?php echo CHtml::endForm(); ?>	
						<?php } ?>
					</div>
                </div>

				<?php
				if (!empty($dataProvider))
				{
					$zones									 = States::model()->getRegionByZoneId();
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
							array('name' => 'vnd_name', 'value' => '$data[vnd_name]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'Vendor Name'),
							array('name'	 => 'Region', 'value'	 => function($data) use ($zones) {
									$zonid				 = array_search($data['vnp_home_zone'], array_column($zones, 'zon_id'));
									echo States::model()->findRegionName($zones[$zonid]['stt_zone'][0]);
									$GLOBALS['zon_name'] = $zones[$zonid]['zon_name'];
								},
								'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Region'),
							array('name'	 => 'vnp_home_zone', 'value'	 => function($data) use ($zones) {
									echo $GLOBALS['zon_name'];
									unset($GLOBALS['zon_name']);
								},
								'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => ' Home Zone'),
							array('name' => 'state', 'value' => '$data[state]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'State'),
							array('name' => 'vrs_approve_car_count', 'value' => '$data[vrs_approve_car_count]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Approved Cars'),
							array('name' => 'Count_Trips', 'value' => '$data[Count_Trips]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Completed Trips'),
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
    function setFilter(obj)
    {
        $('#export_filter1').val(obj.value);
    }
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
        $('#Booking_bkg_pickup_date1').val(start1.format('YYYY-MM-DD'));
        $('#Booking_bkg_pickup_date2').val(end1.format('YYYY-MM-DD'));
        $('#bkgPickupDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
    });
    $('#bkgPickupDate').on('cancel.daterangepicker', function (ev, picker) {
        $('#bkgPickupDate span').html('Select Pickup Date Range');
        $('#Booking_bkg_pickup_date1').val('');
        $('#Booking_bkg_pickup_date2').val('');
    });
</script>