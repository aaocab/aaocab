<div class="row">
    <div class="col-xs-12">
        <div class="panel panel-default"> 		
			<div class="panel-body">
                <div class="row"> 
					<div class="col-xs-12 ">
						<div class="row">
							<?php
							$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
								'id'					 => 'tfr-form', 'enableClientValidation' => true,
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

							<div class="col-xs-7 col-sm-5 col-md-4 col-lg-3 ">
								<?php
								$daterang	 = "Select Date Range";
								$fromDate	 = ($model->bkg_pickup_date1 == '') ? '' : $model->bkg_pickup_date1;
								$toDate		 = ($model->bkg_pickup_date2 == '') ? '' : $model->bkg_pickup_date2;
								if ($fromDate != '' && $toDate != '')
								{
									$daterang = date('F d, Y', strtotime($fromDate)) . " - " . date('F d, Y', strtotime($toDate));
								}
								?>
								<label class="control-label">Pickup Date Range</label>
								<div id="bkgPickupDate" class="inputFilter" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
									<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
									<span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
								</div>	
								<?= $form->hiddenField($model, 'bkg_pickup_date1', ['class' => 'pickupDate1', 'value' => $model->bkg_pickup_date1]); ?>
								<?= $form->hiddenField($model, 'bkg_pickup_date2', ['class' => 'pickupDate2', 'value' => $model->bkg_pickup_date2]); ?>			
							</div>
							<div class="col-xs-12 col-sm-3 col-md-3">
								<div class="form-group">
									<label class="control-label">Booking Type</label>

									<?php
									$bookingTypesArr = $model->booking_type;
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

							<div class="col-xs-12 col-sm-3 col-md-3 col-lg-2 mt20 pt5"> 
								<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary active col-xs-12')); ?>
							</div>
							<?php $this->endWidget(); ?>
						</div> 
					</div>

				</div>
			</div>

			<?php
			if (!empty($dataProvider))
			{
				$params									 = array_filter($_REQUEST);
				$dataProvider->getPagination()->params	 = $params;
				$dataProvider->getSort()->params		 = $params;
				$this->widget('booster.widgets.TbGridView', array(
					'responsiveTable'	 => true,
					'dataProvider'		 => $dataProvider,
					'template'			 => "<div class='panel-heading'><div class='row '>
                                    <div class='col-xs-12 col-sm-6 pt5'>{summary}</div>
									<div class='col-xs-12 col-sm-6'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'><div class='table-responsive'>{items}</div></div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
					'itemsCssClass'		 => 'table-striped table-bordered dataTable mb0',
					'htmlOptions'		 => array('class' => 'panel panel-primary compact'),
					'columns'			 => array(
						array('name'	 => 'bkg_booking_id',
							'value'	 => function ($data) {
								echo CHtml::link($data['bkg_booking_id'], Yii::app()->createUrl("admin/booking/view", ["id" => $data['bkg_id']]), ["class" => "viewBooking", "onclick" => "", 'target' => '_blank']);
							},
							'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'BookingId'),
						array('name'				 => 'bkg_create_date', 'value'				 => 'DateTimeFormat::DateTimeToLocale($data[bkg_create_date])', 'sortable'			 => true,'htmlOptions'		 => array('class' => 'text-center'),
							'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'Create Date'),
						array('name'				 => 'bkg_pickup_date', 'value'				 => 'DateTimeFormat::DateTimeToLocale($data[bkg_pickup_date])', 'sortable'			 => true,'htmlOptions'		 => array('class' => 'text-center'),
							'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'Pickup Date'),
						array('name'				 => 'bkg_extra_km_charge', 'value'				 => 'Filter::moneyFormatter($data[bkg_extra_km_charge])', 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-right'),
							'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'Extra KM Charge'),
						array('name'				 => 'bkg_parking_charge', 'value'				 => 'Filter::moneyFormatter($data[bkg_parking_charge ])', 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-right'),
							'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'Parking Charge'),
						array('name'				 => 'bkg_extra_state_tax', 'value'				 => 'Filter::moneyFormatter($data[bkg_extra_state_tax])', 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-right'),
							'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'Extra State Tax Charge'),
						array('name'				 => 'bkg_extra_toll_tax', 'value'				 => 'Filter::moneyFormatter($data[bkg_extra_toll_tax])', 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-right'),
							'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'Extra Toll Tax Charge'),
						array('name'				 => 'bkg_extra_total_min_charge', 'value'				 => 'Filter::moneyFormatter($data[bkg_extra_total_min_charge])', 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-right'),
							'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'Extra Total Min Charge'),
				)));
			}
			?> 
		</div> 
	</div>  
</div>  
<script>
	$(document).ready(function ()
	{
		var startDate = '<?php echo ($model->bkg_pickup_date1) ? date('d/m/Y', strtotime($model->bkg_pickup_date1)) : ''; ?>';
		var endDate = '<?php echo ($model->bkg_pickup_date2) ? date('d/m/Y', strtotime($model->bkg_pickup_date2)) : ''; ?>';
		var start = (startDate == '') ? '<?= date('d/m/Y', strtotime('-1 day')); ?>' : startDate;
		var end = (endDate == '') ? '<?= date('d/m/Y'); ?>' : endDate;
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
						'Last 30 Days': [moment().subtract(29, 'days'), moment()],
						'This Month': [moment().startOf('month'), moment()],
						'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
					}
				}, function (start1, end1) {
			$('.pickupDate1').val(start1.format('YYYY-MM-DD'));
			$('.pickupDate2').val(end1.format('YYYY-MM-DD'));
			$('#bkgPickupDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));

		});
		$('#bkgPickupDate').on('cancel.daterangepicker', function (ev, picker) {
			$('#bkgPickupDate span').html('Select Date Range');
			$('.pickupDate1').val('');
			$('.pickupDate2').val('');
		});
	});
</script>