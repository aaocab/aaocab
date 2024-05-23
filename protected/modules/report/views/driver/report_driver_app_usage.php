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
<?
$selectizeOptions = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];

$datazone			 = Zones::model()->getZoneArrByFromBooking();
?>
<div class="row m0">
    <div class="col-xs-12">
        <div class="text-right">
        </div>    
        <div class="panel panel-default">
            <div class="panel-body">
				<div class="row"> 
					<?php
					$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
						'id'					 => 'drvAppUsage-form', 'enableClientValidation' => true,
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

                        </div></div>
					<div class="col-xs-12 col-sm-4 col-md-3"> 
						<div class="form-group">
							<label class="control-label">Filter </label>
							<?php
							$filters	 = Drivers::model()->getDriverAppFilter();
							$dataPay	 = VehicleTypes::model()->getJSON($filters);
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'bkg_drv_app_filter',
								'val'			 => $model->bkg_drv_app_filter,
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($dataPay), 'allowClear' => true),
								'htmlOptions'	 => array('class' => 'p0', 'style' => 'width: 100%', 'placeholder' => 'Select Filter', 'onchange' => 'setFilter(this)')
							));
							?>
						</div>

					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-4 col-md-3"> 
							<div class="form-group">
								<label>Select Vendor: </label>
								<?php
								$this->widget('ext.yii-selectize.YiiSelectize', array(
									'model'				 => $dmodel,
									'attribute'			 => 'drv_vendor_id',
									'useWithBootstrap'	 => true,
									"placeholder"		 => "Select Vendor",
									'fullWidth'			 => false,
									'htmlOptions'		 => array('width' => '100%'),
									'defaultOptions'	 => $selectizeOptions + array(
								'onInitialize'	 => "js:function(){
                                  populateVendor(this, '{$dmodel->drv_vendor_id}');
                                }",
								'load'			 => "js:function(query, callback){
                                loadVendor(query, callback);
                                }",
								'render'		 => "js:{
                                option: function(item, escape){
                                return '<div><span class=\"\"><i class=\"fa fa-user mr5\"></i>' + escape(item.text) +'</span></div>';
                                },
                                option_create: function(data, escape){
                                return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
                                }
                                }",
									),
								));
								?>

							</div>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4"> 
							<div class="form-group">
								<label>Zone: </label>
								<?php
								$this->widget('booster.widgets.TbSelect2', array(
									'model'			 => $model,
									'attribute'		 => 'sourcezone',
									'val'			 => $model->sourcezone,
									'data'			 => $datazone,
									//'asDropDownList' => FALSE,
									//'options' => array('data' => new CJavaScriptExpression($datazone), 'allowClear' => true,),
									'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => 'multiple',
										'placeholder'	 => 'Zone')
								));
								?>
							</div>
						</div>
						<div class="col-xs-6  col-sm-4 col-md-3 col-lg-2">
							<div class="form-group">
								<label class="control-label">Region </label>
								<?php
								$regionList	 = VehicleTypes::model()->getJSON(Vendors::model()->getRegionList());
								$this->widget('booster.widgets.TbSelect2', array(
									'model'			 => $model,
									'attribute'		 => 'bkg_region',
									'val'			 => $model->bkg_region,
									//'asDropDownList' => FALSE,
									'data'			 => Vendors::model()->getRegionList(),
									//'options' => array('data' => new CJavaScriptExpression($regionList), 'allowClear' => true),
									'htmlOptions'	 => array('class'			 => 'p0', 'multiple'		 => 'multiple',
										'style'			 => 'width: 100%', 'placeholder'	 => 'Select Region')
								));
								?>
							</div></div>
						<div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-2 col-md-2 text-center mt20 p5">   
							<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary full-width')); ?></div>
						<?php $this->endWidget(); ?>
					</div>

					<div class="row" style="margin-top: 10px">
						<div class="col-xs-12 col-sm-8 col-md-6">        
							<table class="table table-bordered text-center">
								<thead>
									<tr style="color: black;background: whitesmoke">
										<th class="text-center"><u>Total Bookings Served</u></th>
										<th class="text-center"><u>Total Driver App Used</u></th>
										<th class="text-center"><u>% of Driver App Usage</u></th>
									</tr>
								</thead>
								<tbody id="count_booking_row">                         

									<?php
									if ($count != null)
									{
										?>

										<tr>
											<td class="text-center"><?= $count['total_booking_count'] ?></td>
											<td class="text-center"><?= $count['total_app_used_count'] ?></td>
											<td class="text-center"><?= round((($count['total_app_used_count'] / $count['total_booking_count']) * 100), 2) ?> %</td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
						<div class="col-xs-12 col-sm-8 col-md-6">        
							<table class="table table-bordered">
								<thead>
									<tr style="color: black;background: whitesmoke">
										<th class="text-center"><u>Total Bookings </u></th>
										<th class="text-center"><u>Driver App Start Count</u></th>
										<th class="text-center"><u>Driver App Stop Count</u></th>
									</tr>
								</thead>
								<tbody id="count_startstop_row">                         

									<?php
									if ($startCount != null)
									{
										?>

										<tr>
											<td class="text-center"><?= $startCount['total_booking'] ?></td>
											<td class="text-center"><?= $startCount['app_start'] ?></td>
											<td class="text-center"><?= $startCount['app_end'] ?></td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
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
						<?= CHtml::beginForm(Yii::app()->createUrl('report/driver/driverAppUsage'), "post", ['style' => "margin-bottom: 10px;"]); ?>
						<input type="hidden" id="export1" name="export1" value="true"/>
						<input type="hidden" id="export_from1" name="export_from1" value="<?= $model->bkg_pickup_date1 ?>"/>
						<input type="hidden" id="export_to1" name="export_to1" value="<?= $model->bkg_pickup_date2 ?>"/>
						<input type="hidden" id="export_filter1" name="export_filter1" value="<?= $model->bkg_drv_app_filter ?>"/>
						<input type="hidden" id="export_vendor" name="export_vendor" value="<?= $dmodel->drv_vendor_id ?>"/>
						<input type="hidden" id="export_zone" name="export_zone" value="<?= $model->sourcezone ?>"/>
						<input type="hidden" id="export_region" name="export_region" value="<?= $model->bkg_region ?>"/>
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
								array('name' => 'city_zone', 'value' => '$data[city_zones]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'Zone'),
								array('name' => 'drv_name', 'value' => '$data[drv_name]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Driver Name'),
								array('name' => 'drv_code', 'value' => '$data[drv_code]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Driver code'),
								array('name' => 'drs_drv_overall_rating', 'value' => '$data[drs_drv_overall_rating]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'Overall Rating'),
								array('name' => 'drv_created', 'value' => '$data[drv_created]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Date of Joining'),
								array('name' => 'drs_last_trip_date', 'value' => '$data[drs_last_trip_date]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Driver Last Trip'),
								array('name' => 'drs_last_logged_in', 'value' => '$data[drs_last_logged_in]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Driver Last Login'),
								array('name' => 'drv_phone', 'value' => '$data[phn_phone_no]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Driver Phone'),
								array('name' => 'booking_count', 'value' => '$data[booking_count]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Booking Count'),
								array('name' => 'app_used_count', 'value' => '$data[app_used_count]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'App Usage Count'),
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