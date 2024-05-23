<?
/* @var $model Booking */

$status			 = Booking::model()->getBookingStatus();
$reconfirmStatus = Booking::model()->getReconfirmStatus();
$carType		 = VehicleTypes::model()->getCarType();
$cttId			 = $model->bkgBcb->bcbVendor->vnd_contact_id;
$number			 = ContactPhone::model()->getContactPhoneById($cttId);

$Arrmultivendor = [];

foreach ($models as $key => $value)
{
	if ($value->bkgBcb->bcbVendor)
	{
		$Arrmultivendor[$value->bkgBcb->bcbVendor->vnd_id] = $value->bkgBcb->bcbVendor->vnd_name . ' ( Rating - ' . $value->bkgBcb->bcbVendor->vendorStats->vrs_vnd_overall_rating . ' )';
	}
}
//$Arrmultivendor[0] = 'Select from list';
?>
<input type="hidden" value="<?= $bsmModel->bsm_id ?>" name="bsmId">
<div class="panel panel-default">
    <div class="panel-body">
		<?
		if ($cabmodel->hasErrors())
		{
			?>
			<div class="alert alert-danger h4 text-center">
				<?= CHtml::errorSummary($cabmodel); ?>
			</div>
			<?
		}
		else
		{
			?>
			<div class="row mb20">
				<div class="col-xs-12 bordered pb10 pt10 ">
					<?php
					$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
						'id'					 => 'match-form', 'enableClientValidation' => TRUE,
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
							'class' => 'form-horizontal',
						),
					));
					/* @var $form TbActiveForm */
					?>
					<?= CHtml::errorSummary($cabmodel);
					?>
					<div class="row">
						<div class="col-xs-12 pb10">
							<div class="row">
								<div class="col-xs-6 col-md-3 text-center mt10">
									Total Booking Amount: <b><i class="fa fa-inr"></i><?php
										//$bsmModel->bsm_trip_amount 
										$totAmt	 = 0;
										foreach ($models as $key => $model)
										{
											$totAmt += $model->bkgInvoice->bkg_total_amount;
										}
										echo $totAmt;
										?></b>
								</div>
								<div class="col-xs-6 col-md-3 text-center mt10">
									Total Vendor Amount: <b><i class="fa fa-inr"></i><?php
										//$bsmModel->bsm_vendor_amt_original
										$vndAmt = 0;
										foreach ($models as $key => $model)
										{
											$vndAmt += $model->bkgInvoice->bkg_vendor_amount;
										}
										echo $vndAmt;
										?></b>
								</div>
								<div class="col-xs-6 col-md-3 text-center mt10">
									Bookings Vendor Amount: <b><i class="fa fa-inr"></i><?php
										//$models[0]->bkgInvoice->bkg_vendor_amount.','.$models[1]->bkgInvoice->bkg_vendor_amount
										foreach ($models as $key => $model)
										{
											if ($key != 0)
											{
												$vendorAmts .= ' ,';
											}
											$vendorAmts .= $model->bkgInvoice->bkg_vendor_amount;
										}
										echo $vendorAmts;
										?></b>
								</div>
								<div class="col-xs-6 col-md-3 text-center mt10">
									Proposed Vendor Amount: <b><i class="fa fa-inr"></i><?= $bsmModel->bsm_vendor_amt_matched ?></b>
								</div>
							</div>
						</div>
						<div class="col-xs-12 pt10 ">
							<div class="row">
								<div class="col-md-5 col-xs-12 ">

									<?
									if ($Arrmultivendor)
									{
										?>
										<div class="pl50">
											<?= $form->radioButtonListGroup($cabmodel, 'bcb_assign_id', array('label' => 'Select Vendors', 'widgetOptions' => array('data' => $Arrmultivendor), 'inline' => false)) ?>
										</div>
									<? } ?>

									<div id = "vendorlist" style="<?= $vendordisplay; ?>;">
										<div class="row">
											<div class="col-xs-4 pt10 text-right">
												Other Assign Vendor: </div> 
											<div class="col-xs-6  text-left">
												<div class="form-group">
													<?php
													$data = Vendors::model()->getAssigningJSON();
													$this->widget('booster.widgets.TbSelect2', array(
														'model'			 => $cabmodel,
														'attribute'		 => 'bcb_vendor_id',
														'val'			 => $cabmodel->bcb_vendor_id,
														'asDropDownList' => FALSE,
														'options'		 => array('data' => new CJavaScriptExpression($data)),
														'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Vendor')
													));
													?>
													<?= $form->error($cabmodel, 'bcb_vendor_id') ?>

												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-4 col-xs-12 ">
									<div class="row">
										<div class="col-xs-5 col-md-6 pt10 text-right">
											<nobr>Set Vendor Amount:</nobr> </div> 
										<div class="col-xs-5 text-left">
											<?= $form->numberFieldGroup($cabmodel, 'bcb_vendor_amount', array('label' => '', 'htmlOptions' => array('placeholder' => 'Vendor Amount'))) ?>


										</div>
									</div>
								</div>



								<div class="col-md-3 col-xs-12 text-center">
									<?php echo CHtml::submitButton($isNew, array('class' => 'btn btn-primary', 'value' => 'Match Booking')); ?>
								</div> 
							</div> 
						</div>
					</div>
					<?php $this->endWidget(); ?>
				</div>
			</div>



		<? } ?>
        <div class="row">
            <div class="col-xs-12">
				<?
				foreach ($models as $model)
				{
					?>
					<div class="row mb20">
						<div class="col-xs-12 bordered ">
							<div class="row">
								<div class="col-xs-12 text-center h2 mb5">
									<label for="type" class="control-label"><span style="font-weight: normal; font-size: 30px;">Booking Id:</span> </label>
									<b><?= $model->bkg_booking_id ?></b>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-12 col-md-12 col-lg-6 new-booking-list">
									<div class="row p20 pt0 pr10">
										<div class="col-xs-12 heading_box">Booking Information</div>
										<div class="col-xs-12 main-tab1-minheight">

											<div class="row new-tab-border-b">
												<div class="col-xs-12 col-sm-6 new-tab-border-r">
													<div class="row new-tab1">
														<div class="col-xs-5"><b>Booking Type:</b></div>
														<div class="col-xs-7"><?= Booking::model()->getBookingType($model->bkg_booking_type); ?></div>
													</div>
												</div>
												<div class="col-xs-12 col-sm-6">
													<div class="row new-tab1">
														<div class="col-xs-5"><b>Booking Status:</b></div>
														<div class="col-xs-7"><?= $status[$model->bkg_status] ?> <?php
															if ($model->bkg_status != '9' || $model->bkg_status != '8')
															{
																echo '(' . $reconfirmStatus[$model->bkg_reconfirm_flag] . ')';
															}
															?></div>
													</div>
												</div>
											</div>
											<div class="row new-tab-border-b">
												<div class="col-xs-12 col-sm-6 new-tab-border-r">
													<div class="row new-tab1">
														<div class="col-xs-5"><b>Route:</b></div>
														<div class="col-xs-7"><?= $model->bkgFromCity->cty_name . ' to ' . $model->bkgToCity->cty_name; ?></div>
													</div>
												</div>
												<div class="col-xs-12 col-sm-6">
													<div class="row new-tab1">
														<div class="col-xs-5"><b>Cab Type:</b></div>
<!--														<div class="col-xs-7"><?//= $model->bkgVehicleType->getCabType() ?></div>-->
														<div class="col-xs-7"><?= SvcClassVhcCat::model()->getVctSvcList('string','',$model->bkgSvcClassVhcCat->scv_vct_id) ?></div>
													</div>
												</div>
											</div>
											<div class="row new-tab-border-b">
												<div class="col-xs-12 col-sm-6 new-tab-border-r">
													<div class="row new-tab1">
														<div class="col-xs-5"><b>Trip Distance:</b></div>
														<div class="col-xs-7"><?= ($model->bkg_trip_distance != '') ? $model->bkg_trip_distance . " Km" : "&nbsp;" ?></div>
													</div>
												</div>
												<div class="col-xs-12 col-sm-6">
													<div class="row new-tab1">
														<div class="col-xs-5"><b>Trip Duration:</b></div>
														<div class="col-xs-7"><?= Filter::getDurationbyMinute($model->bkg_trip_duration) ?></div>
													</div>
												</div>
											</div>

											<div class="row new-tab-border-b">
												<div class="col-xs-12 col-sm-6 new-tab-border-r">
													<div class="row new-tab1">
														<div class="col-xs-5"><b>Pickup Date:</b></div>
														<div class="col-xs-7"><?= date('d/m/Y', strtotime($model->bkg_pickup_date)); ?></div>
													</div>
												</div>
												<div class="col-xs-12 col-sm-6">
													<div class="row new-tab1">
														<div class="col-xs-5"><b>Pickup Time:</b></div>
														<div class="col-xs-7"><?= date('h:i A', strtotime($model->bkg_pickup_date)); ?></div>
													</div>
												</div>

											</div>
											<?
											if ($model->bkg_return_date != '' && $model->bkg_booking_type == '2')
											{
												?>
												<div class="row new-tab-border-b">
													<div class="col-xs-12 col-sm-6 new-tab-border-r">
														<div class="row new-tab1">
															<div class="col-xs-5"><b>Return Date:</b></div>
															<div class="col-xs-7"><?= date('d/m/Y', strtotime($model->bkg_return_date)); ?></div>
														</div>
													</div>
													<div class="col-xs-12 col-sm-6">
														<div class="row new-tab1">
															<div class="col-xs-5"><b>Return Time:</b></div>
															<div class="col-xs-7"><?= date('h:i A', strtotime($model->bkg_return_date)); ?></div>
														</div>
													</div>
												</div>
											<? } ?>
											<div class="row new-tab-border-b">
												<div class="col-xs-12 col-sm-6 new-tab-border-r">
													<div class="row new-tab1">
														<div class="col-xs-5"><b>Info source:</b></div>
														<div class="col-xs-7"><?= ( $model->bkgAddInfo->bkg_info_source != '') ? $model->bkgAddInfo->bkg_info_source : "&nbsp;" ?></div>
													</div>
												</div>
												<div class="col-xs-12 col-sm-6 new-tab-border-r">
													<div class="row new-tab1">
														<div class="col-xs-5"><b>Vendor Name:</b></div>
														<div class="col-xs-7"><?= $model->bkgBcb->bcbVendor->vnd_name ?>
														</div>
													</div>
												</div>

												<?
												if (($model->bkg_status == 8 || $model->bkg_status == 9) && $model->bkg_cancel_delete_reason != '')
												{
													?>
													<?
													$reason = '';
													if ($model->bkg_status == 8)
													{
														$reason = 'Delete';
													}
													if ($model->bkg_status == 9)
													{
														$reason = 'Cancel';
													}
													?>
													<div class="col-xs-12 col-sm-6">
														<div class="row new-tab1">
															<div class="col-xs-5"><b><?= $reason ?> Reason:</b></div>
															<div class="col-xs-7"><?= $model->bkg_cancel_delete_reason ?></div>
														</div>
													</div>
												<? } ?>
											</div>

											<div class="row new-tab-border-b">
												<div class="col-xs-12 col-sm-6 new-tab-border-r">
													<div class="row new-tab1">
														<div class="col-xs-5"><b>Vendor Contact Number:</b></div>
														<div class="col-xs-7"><?= $number?>
															<?//= $model->bkgBcb->bcbVendor->vnd_phone ?>
														</div>
													</div>
												</div>
											</div>

											<?
											if ($model->bkgBcb->bcbDriver->drv_name != '')
											{
												?>
												<div class="row new-tab-border-b">
													<div class="col-xs-12 col-sm-6 new-tab-border-r">
														<div class="row new-tab1">
															<div class="col-xs-5"><b>Driver Name:</b></div>
															<div class="col-xs-7"><?= $model->bkgBcb->bcbDriver->drv_name ?>
															</div>
														</div>
													</div>
												</div>
											<? } ?>

											<div class="row">
												<div class="col-xs-12 p0">
													<div class="hostory_leftdeep mt0">
														<div class="col-xs-12 col-sm-6 col-md-4">
															<div class="row p5">
																<div class="col-xs-6 col-sm-12"><b>Pickup Location</b></div>
																<div class="col-xs-6 col-sm-12"><?= $model->bkg_pickup_address; ?></div>
															</div>
														</div>
														<div class="col-xs-12 col-sm-6 col-md-4">
															<div class="row p5">
																<div class="col-xs-6 col-sm-12"><b>Dropoff Location</b></div>
																<div class="col-xs-6 col-sm-12"><?= $model->bkg_drop_address; ?></div>
															</div>
														</div>
														<div class="col-xs-12 col-sm-6 col-md-4">
															<div class="row p5 pl0 pr0">
																<div class="col-xs-6 col-sm-12"><b>Additional Information</b></div>
																<div class="col-xs-6 col-sm-12"><?= ($spclInstruction != "") ? $spclInstruction : "&nbsp;" ?></div>
															</div>
														</div>
													</div>
												</div>
											</div>

										</div>
									</div>
								</div>
								<div class="col-xs-12 col-md-12 col-lg-6 new-booking-list">
									<div class="row p20 pt0 pl10">
										<div class="col-xs-12 heading_box">Billing Information</div>
										<?
										$this->renderPartial('accountsdetail', ['model' => $model, 'cabmodel' => $cabmodel, 'minheight' => 'minheight']);
										?>
									</div>
								</div>

							</div>
						</div>
					</div>
					<?
				}
				?>

            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function ()
    {
		$('.bootbox').removeAttr('tabindex');

        $("#BookingCab_bcb_assign_id_1").click(function ()
        {
			var val = $("#BookingCab_bcb_assign_id_1").val();
			selectVendor(val);
		});

        function selectVendor(type)
        {
            if (type == '0')
            {
				$("#vendorlist").show();
			}
		}


	});
</script>