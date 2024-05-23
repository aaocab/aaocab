<div class="row">
    <div class="col-xs-12">
        <div class="panel panel-default"> 
            <div class="panel-body">
                <div class="row"> 
					<div class="col-sm-10 col-xs-12 ">
						<div class="row ">
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

							<div class="col-xs-7 col-sm-5 col-md-5 col-lg-3 ">
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
							<div class="col-xs-5 col-sm-4 col-md-4 col-lg-3">
								<?= $form->textFieldGroup($model, 'bkg_booking_id', array('label' => 'Booking Id/Partner Ref code', 'htmlOptions' => array('placeholder' => 'Search By Booking Id/Agent Ref code', 'class' => "inputFilter"))) ?>
							</div>
							<div class="col-xs-12 col-sm-3 col-md-3 col-lg-2 mt20 pt5"> 
								<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary active col-xs-12')); ?>
							</div>
							<?php $this->endWidget(); ?>
						</div> 
					</div>
					<div class="col-xs-12 col-sm-2 col-md-2 col-lg-2 pull-right <?php echo ($showExport) ? '' : 'hide'; ?>">
						<div class="row ">
							<?php
							$form1 = $this->beginWidget('booster.widgets.TbActiveForm', array(
								'id'					 => 'tfr-export', 'enableClientValidation' => true,
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

							<?= $form1->hiddenField($model, 'bkg_pickup_date1', ['class' => 'pickupDate1', 'id' => 'bkg_pickup_date1']); ?>
							<?= $form1->hiddenField($model, 'bkg_pickup_date2', ['class' => 'pickupDate2', 'id' => 'bkg_pickup_date2']); ?>
							<?= $form1->hiddenField($model, 'bkg_booking_id', ['id' => 'bkg_booking_id']); ?>

							<input type="hidden" id="export" name="export" value="true"/>
							<div class="col-xs-12 mt20  pt5 "> 
								<?php echo CHtml::submitButton('Export', array('class' => 'btn btn-default active col-xs-12')); ?>
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
					'template'			 => "<div class='panel-heading'><div class='row'>
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
							'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'BookingId'),
						array('name' => 'bkg_agent_ref_code', 'value' => '$data[bkg_agent_ref_code]', 'sortable' => true, 'headerHtmlOptions' => array('class' => ''), 'header' => 'Partner Ref Code'),
						array('name' => 'bkg_create_date', 'value' => 'DateTimeFormat::DateTimeToLocale($data[bkg_create_date])', 'sortable' => true, 'headerHtmlOptions' => array('class' => ''), 'header' => 'Create Date<br>(A)'),
						array('name' => 'bkg_pickup_date', 'value' => 'DateTimeFormat::DateTimeToLocale($data[bkg_pickup_date])', 'sortable' => true, 'headerHtmlOptions' => array('class' => ''), 'header' => 'Pickup Date'),
						array('name' => 'btr_cancel_date', 'value' => 'DateTimeFormat::DateTimeToLocale($data[btr_cancel_date])', 'sortable' => true, 'headerHtmlOptions' => array('class' => ''), 'header' => 'Cancelled Date<br>(B)'),
						array('name' => 'cancelDateDiff', 'value' => '$data[cancelDateDiff]', 'sortable' => true, 'headerHtmlOptions' => array('class' => ''), 'header' => 'Duration <br>(Minutes)(B-A)'),
						array('name' => 'cancelReason', 'value' => '$data[bkg_cancel_delete_reason]', 'sortable' => true, 'headerHtmlOptions' => array('class' => ''), 'header' => 'Cancel Reason'),
						array('name'	 => 'cancelUser',
							'value'	 => function ($data) {
								switch ($data['bkg_cancel_user_type'])
								{
									case '4':
										echo $data['admName'];
										break;
									case '5':
										echo 'MMT';
										break;
									case '10':
										echo 'System';
										break;
									default:

										break;
								}
							},
							'sortable'								 => true, 'headerHtmlOptions'						 => array('class' => ''), 'header'								 => 'Cancelled By'),
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
		var start = (startDate == '') ? '<?= date('d/m/Y', strtotime('-1 week')); ?>' : startDate;
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


