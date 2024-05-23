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

                    <div class="col-xs-6 col-sm-4 col-md-4" style="">
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

					<div class="col-xs-4 col-sm-4  col-md-3 col-lg-2">
						<div class="form-group">
							<label class="control-label">Cab Type</label>
							<?php
							$returnType			 = "list";
							$vehicleList		 = SvcClassVhcCat::getVctSvcList($returnType);
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'bkg_vehicle_type_id',
								'val'			 => $model->bkg_vehicle_type_id,
								'data'			 => $vehicleList,
								'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => 'multiple',
									'placeholder'	 => 'Select Car Type')
							));
							?>
						</div>
					</div>
                    <div class="col-xs-12 col-sm-4 col-md-3">Select Region
						<?php
						$regionList			 = VehicleTypes::model()->getJSON(Vendors::model()->getRegionList());
						$this->widget('booster.widgets.TbSelect2', array(
							'model'			 => $model,
							'attribute'		 => 'bkg_region',
							'val'			 => $model->bkg_region,
							'asDropDownList' => FALSE,
							'options'		 => array('data' => new CJavaScriptExpression($regionList), 'allowClear' => true),
							'htmlOptions'	 => array('class' => 'p0', 'style' => 'width: 100%', 'placeholder' => 'Select Region')
						));
						?>
                    </div>
					<div class="col-xs-12 col-sm-2 col-md-2">
						<div class="form-group">
							<label class="control-label">Booking Type</label>

							<?php
							$bookingTypesArr	 = $model->booking_type;
							unset($bookingTypesArr[2]);
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'bkgtypes',
								'val'			 => $model->bkgtypes,
								'data'			 => $bookingTypesArr,
								//'asDropDownList' => FALSE,
								//'options' => array('data' => new CJavaScriptExpression($datacity), 'allowClear' => true,),
								'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => 'multiple',
									'placeholder'	 => 'Booking Type')
							));
							?>
						</div>
					</div>
					<div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-2 col-md-2 text-center mt20 p5">   
						<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary full-width')); ?></div>
					<?php $this->endWidget(); ?>
                </div>
				<?php
				$checkExportAccess	 = Yii::app()->user->checkAccess("Export");
				if ($checkExportAccess)
				{
					?>
					<?= CHtml::beginForm(Yii::app()->createUrl('admin/generalReport/profitability'), "post", ['style' => "margin-bottom: 10px;"]); ?>
					<input type="hidden" id="export1" name="export1" value="true"/>
					<input type="hidden" id="export_from1" name="export_from1" value="<?= $model->bkg_pickup_date1 ?>"/>
					<input type="hidden" id="export_to1" name="export_to1" value="<?= $model->bkg_pickup_date2 ?>"/>
					<input type="hidden" id="bkg_vehicle_type_id" name="bkg_vehicle_type_id" value="<?= implode(",", $model->bkg_vehicle_type_id) ?>"/>
					<input type="hidden" id="bkg_booking_type" name="bkg_booking_type" value="<?= implode(",", $model->bkgtypes) ?>"/>
					<input type="hidden" id="export_bkg_region" name="export_bkg_region" value="<?= $model->bkg_region ?>"/>
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
							array('name' => 'Region', 'value' => '$data[Region]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'Region'),
							array('name' => 'fromZone', 'value' => '$data[fromZone]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'From Zone'),
							array('name' => 'toZone', 'value' => '$data[toZone]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'To Zone'),
							array('name' => 'CountBooking', 'value' => '$data[CountBooking]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Count of Completed Bookings'),
							array('name' => 'Profit', 'value' => 'round($data[Profit],2)', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Profit %'),
					)));
				}
				?>
            </div>  

        </div> 
		<div class="row" style="margin-top: 10px">
			<div class="col-xs-12 col-sm-7 col-md-5">        
				<label class="control-label"><u><b> Profit% by Cab Type: </b></u></label>
				<table class="table table-bordered">
					<thead>
						<tr style="color: black;background: whitesmoke">
							<?php
							foreach ($cabProfit as $key => $value)
							{
								?>

								<th><u><?= $value['vct_label'] ?></u></th>


							<?php } ?>
						</tr> 
					</thead>
					<tbody id="count_booking_row">  <tr>  
							<?php
							foreach ($cabProfit as $key => $pair)
							{
								?>
								<td><?= round($pair['Profit'], 2) ?></td>
							<?php } ?>
						</tr>
					</tbody></table>	
			</div>
		</div>
		<div class="row" style="margin-top: 10px">
			<div class="col-xs-12 col-sm-5 col-md-4">        
				<label class="control-label"><u><b> Profit% by Service Tier: </b></u></label>
				<table class="table table-bordered">
					<thead>
						<tr style="color: black;background: whitesmoke">
							<?php
							foreach ($serviceTier as $key => $value)
							{
								?>

								<th><u><?= $value['scc_label'] ?></u></th>
							<?php } ?>
						</tr> 
					</thead>
					<tbody id="count_booking_row">  <tr>  
							<?php
							foreach ($serviceTier as $key => $pair)
							{
								?>
								<td><?= round($pair['Profit'], 2) ?></td>
							<?php } ?>
						</tr>
					</tbody></table>	
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