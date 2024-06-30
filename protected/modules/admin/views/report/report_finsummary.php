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
					$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
						'id'					 => 'finalcialReportForm',
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
					<div class="col-xs-12 col-sm-3">
						<div>
							<?php
							//$daterang = date('F d, Y') . " - " . date('F d, Y');
							$daterang	 = "Pickup Date Range";
							$createdate1 = ($model->bkg_create_date1 == '') ? '' : $model->bkg_create_date1;
							$createdate2 = ($model->bkg_create_date2 == '') ? '' : $model->bkg_create_date2;
							if ($createdate1 != '' && $createdate2 != '')
							{
								$daterang = date('F d, Y', strtotime($createdate1)) . " - " . date('F d, Y', strtotime($createdate2));
							}
							?>
							<label  class="control-label">Pickup Date Range</label>
							<div id="bkgCreateDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
								<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
								<span><?= $daterang ?></span> <b class="caret"></b>
							</div>
							<?php
							echo $form->hiddenField($model, 'bkg_create_date1');
							echo $form->hiddenField($model, 'bkg_create_date2');
							?>
						</div>
					</div>
					<div class="col-xs-12 col-sm-6 pt20">
						<div style="display: inline-block">
							<?php echo $form->checkboxGroup($model, 'b2cbookings', array('label' => 'B2C')) ?>
						</div>
						<div style="display: inline-block">
							<?php echo $form->checkboxGroup($model, 'mmtbookings', array('label' => 'MMT')) ?>
						</div>
						<div style="display: inline-block">
							<?php echo $form->checkboxGroup($model, 'otherAPIPartner', array('label' => 'Other API Partners')) ?>
						</div>
						<div style="display: inline-block">
							<?php echo $form->checkboxGroup($model, 'nonAPIPartner', array('label' => 'Non API Partners')) ?>
						</div>
						<div style="display: inline-block">
							<?php echo $form->checkboxGroup($model, 'restrictToDate', array('label' => 'Restrict to Selected Day')) ?>
						</div>
					</div>
                    <div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-1 col-md-1 text-center mt20 p5">   
						<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary full-width')); ?>
					</div>
					<?php $this->endWidget(); ?>
                </div>

				<?php
				$flg = ($model->b2cbookings + $model->mmtbookings + $model->nonAPIPartner);
				$flg = 0;
				if (count($data2) > 0)
				{
					?>  
					<div>
						<div class="panel panel-primary  compact" id="yw0">
							<div class="panel-heading">
								<div class="row m0">
									<div class="col-xs-5 col-sm-2 pt5">By pickup date</div>
									<div class="col-xs-4 col-sm-5 pt5">
										<div class="summary">Total <?php echo count($data2) ?> results.</div></div>
									<div class="col-xs-3 col-sm-5 pr0"></div>
								</div></div>
							<div class="panel-body table-responsive" style="overflow: auto"><table class="table table-striped table-bordered dataTable mb0 table">
									<thead>
										<tr>
											<th>Date</th>
											<th class="text-right">Created</th>
											<th class="text-right">Cancelled</th>
											<th class="text-right">Completed</th>
											<th class="text-right">Completed Amount</th>
											<th class="text-right">Partner Commission</th>
											<th class="text-right">Gozo Amount</th>
											<th class="text-right">Gozo (%)</th>
											<th class="text-right">Cancel Charges</th>
											<th class="text-right">Operator Penalty</th>
											<th class="text-right">Operator Compensation</th>
											<th class="text-right">Partner Compensation</th>
											<th class="text-right">Net Income</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$i = 0;
										foreach ($data2 as $data)
										{
											$netIncome = $data['gozoAmount'] + $data['cancelCharge'] + ($flg > 0 ? 0 : ($arrPenalty[$data['date']]['operatorPenalty'] - $arrPenalty[$data['date']]['partnerCompensation'] - $arrPenalty[$data['date']]['operatorCompensation']));
											?>
											<tr class="<?php echo $i == 0 ? 'even' : 'odd' ?>">
												<td><?php echo $data['date']; ?></td>
												<td class="text-right"><?php echo number_format($data['cntCreated']); ?></td>
												<td class="text-right"><?php echo number_format($data['cancelled']); ?></td>
												<td class="text-right"><?php echo number_format($data['completed']); ?></td>
												<td class="text-right"><?php echo number_format($data['completedAmount']); ?></td>
												<td class="text-right"><?php echo number_format($data['partnerCommission']); ?></td>
												<td class="text-right"><?php echo number_format($data['gozoAmount']); ?></td>
												<td class="text-right"><?php echo number_format((($data['gozoAmount'] / $data['totalBaseFare']) * 100), 2); ?></td>
												<td class="text-right"><?php echo number_format($data['cancelCharge']); ?></td>
												<td class="text-right"><?php echo ($flg > 0 ? '-' : number_format($arrPenalty[$data['date']]['operatorPenalty'])); ?></td>
												<td class="text-right"><?php echo ($flg > 0 ? '-' : '(' . number_format($arrPenalty[$data['date']]['operatorCompensation']) . ')' ); ?></td>
												<td class="text-right"><?php echo '(' . number_format($arrPenalty[$data['date']]['partnerCompensation']) . ')'; ?></td>
												<td class="text-right"><?php echo number_format($netIncome); ?></td>
											</tr>
											<?php
											$i++;
										}
										?>
									</tbody>
								</table></div>
							<div class="panel-footer"><div class="row m0"><div class="col-xs-12 col-sm-6 p5"><div class="summary">Total <?php echo count($data2) ?> results.</div></div><div class="col-xs-12 col-sm-6 pr0"></div></div></div><div class="keys" style="display:none" title="/aaohome/report/Financial"><span></span><span></span><span></span><span></span><span></span><span></span><span></span></div>
							<div>
								#Gozo Amount: Gozo Amount - Credit Used - Partner Commission<br>
								#Cancel Charge: Advance Amount - Refund Amount<br>
								#Net Income: Gozo Amount + Cancel Charge + Operator Penalty - Operator Compensation - Partner Compensation<br>
								<!--#OtherAPIPartner (Mobisign, EMT, Transfers, MYN, SugarBox, GlobalNRI, Upcurve etc)<br>
								#NonAPIPartner (Booking created from Admin Panel, Agent Panel, Kayak)<br>-->
							</div>
						</div>
					</div>

				<?php } ?>
            </div>  

        </div>  
    </div>
</div>
<script>
	$(document).ready(function()
	{

		var start = '<?= date('d/m/Y', strtotime($model->bkg_create_date1)); ?>';
		var end = '<?= date('d/m/Y', strtotime($model->bkg_create_date2)); ?>';

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
						'Last 30 Days': [moment().subtract(29, 'days'), moment()],
						'This Month': [moment().startOf('month'), moment().endOf('month')],
						'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
						'Last 6 Month': [moment().subtract(6, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
					}
				}, function(start1, end1)
		{
			$('#Booking_bkg_create_date1').val(start1.format('YYYY-MM-DD'));
			$('#Booking_bkg_create_date2').val(end1.format('YYYY-MM-DD'));

			$('#bkgCreateDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
		});
		$('#bkgCreateDate').on('cancel.daterangepicker', function(ev, picker)
		{
			$('#bkgCreateDate span').html('Select Date Range');
			$('#Booking_bkg_create_date1').val('');
			$('#Booking_bkg_create_date2').val('');

		});
	});

</script> 