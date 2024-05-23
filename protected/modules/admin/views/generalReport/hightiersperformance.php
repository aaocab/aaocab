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
                            <label class="control-label">Pickup date range</label>
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
							<label class="control-label">Service Type</label>
							<?php
							$returnType			 = "listClass";
							$vehicleList		 = SvcClassVhcCat::getVctSvcList($returnType);
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'bkg_service_class',
								'val'			 => $model->bkg_service_class,
								'data'			 => $vehicleList,
								'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => 'multiple',
									'placeholder'	 => 'Select Service Class')
							));
							?>
						</div>
					</div>


					<div class="col-xs-12 col-sm-4 col-md-3">
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
					<?= CHtml::beginForm(Yii::app()->createUrl('admin/generalReport/ServicePerformance'), "post", ['style' => "margin-bottom: 10px;"]); ?>
					<input type="hidden" id="export1" name="export1" value="true"/>
					<input type="hidden" id="export_from1" name="export_from1" value="<?= $model->bkg_pickup_date1 ?>"/>
					<input type="hidden" id="export_to1" name="export_to1" value="<?= $model->bkg_pickup_date2 ?>"/>
					<input type="hidden" id="bkg_service_class" name="bkg_service_class" value="<?= implode(",", $model->bkg_service_class) ?>"/>
					<input type="hidden" id="bkg_booking_type" name="bkg_booking_type" value="<?= implode(",", $model->bkgtypes) ?>"/>
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
							array('name'	 => 'bkg_id', 'value'	 => function($data) {
									echo CHtml::link($data['bkg_booking_id'], Yii::app()->createUrl("admin/booking/view", ["id" => $data['bkg_id']]), ["class" => "viewBooking", 'target' => '_blank']);
								},
								'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Booking ID'),
							array('name' => 'cabtype', 'value' => '$data[cabtype]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'Service Tier'),
							array('name'	 => 'bkg_booking_type', 'value'	 => function($data)
								{
									$bkgType = Booking::model()->getBookingType($data['bkg_booking_type']);
									echo $bkgType;
								}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Bkg Type'),
							array('name' => 'bkg_pickup_date', 'sortable' => true, 'value' => 'date("d/m/Y h:i A",strtotime($data[bkg_pickup_date]))', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Pickup Date'),
							array('name' => 'disposition_comments', 'value' => '$data[disposition_comments]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'On Trip follow up dispostion notes'),
							array('name' => 'post_disposition_comments', 'value' => '$data[post_disposition_comments]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'Post trip follow up call'),
							array('name'	 => 'rtg_customer_overall', 'value'	 => function($data)
								{
									echo $data['rtg_customer_overall'] != null ? $data['rtg_customer_overall'] : "NA";
								},'sortable'								 => true, 'headerHtmlOptions'						 => array('class' => 'col-xs-1'), 'header'								 => 'Rating star'),
							array('name' => 'ArrivedForPickupTime', 'sortable' => true, 'value' => 'date("d/m/Y h:i A ",strtotime($data["ArrivedForPickupTime"]))', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Actual pickup time'),
							array('name'		 => 'delay', 'sortable'	 => false, 'value'		 => function ($data) {
									echo $data['delay'] > 0 ? $data['delay'] : "No delay";
								},
								'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions'		 => array('style' => 'text-align: center;'), 'header'			 => 'Delay(min)'),
					)));
				}
				?>
            </div>  

        </div> 

	</div>
</div>
<script>
    var start = '<?= date('d/m/Y'); ?>';
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