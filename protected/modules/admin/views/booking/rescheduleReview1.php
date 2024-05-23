
<style>
    .form-horizontal .form-group{
        margin: 0;
    }
    .datepicker.datepicker-dropdown.dropdown-menu,
    .bootstrap-timepicker-widget.dropdown-menu,
    .yii-selectize.selectize-dropdown
    {z-index: 9999 !important;}
    .selectize-input {
        min-width: 0px !important; 
        width: 100% !important;
    }
    .modal-body{
        padding-bottom: 0
    }
    .modal-header{
        display:block;
    }
    .modal-dialog{ width: 68%;}

    @media (min-width: 768px) and (max-width: 1200px) {
        .modal-dialog{ width: 68%;}
    }
    @media (min-width: 320px) and (max-width: 767px) {
        .modal-dialog{ width: 90%; margin: 0 auto;}
    }
	.ui-timepicker-container
	{
        z-index: 10000 !important;
    }
</style>
<div class=""  style="float: none; margin: auto">
	<div class="row">
		<span class="text-center mt0 mb20 text-danger errorMessage">

		</span>
	</div>
    <div class="row text-center">
		<span class="text-center mt0 text-success paymentLinkReschedule">

		</span>
	</div>
	<div class="row mt20" >
		<div class="col-xs-6 text-left">New Pickup time: </div>
		<div class="col-xs-6 text-right"><?php
			$date = date_create($newModel->bkg_pickup_date);
			echo date_format($date, 'd/m/Y') . ' ' . date_format($date, 'g:i A');
			?>
		</div>
	</div>
	<?php
	if ($prevModel->bkgPref->bkg_cancel_rule_id != $newModel->bkgPref->bkg_cancel_rule_id)
	{
		$cancelDescNew = CancellationPolicyDetails::model()->findByPk($newModel->bkgPref->bkg_cancel_rule_id)->cnp_desc;
		if($newModel->bkgPref->bkg_cancel_rule_id == CancellationPolicyDetails::NON_CANCELLABLE){
			$minPerc	 = Config::getMinAdvancePercent($prevModel->bkg_agent_id, $prevModel->bkg_booking_type, $prevModel->bkgSvcClassVhcCat->scc_ServiceClass->scc_id, $newModel->bkgPref->bkg_is_gozonow);
			$cancelDescNew =  str_replace($minPerc,"100",$cancelDescNew);
		}
		?>
		<div class="row">
			<div class="col-xs-6 text-left">Cancellation rule applicable: </div><div class="col-xs-6 text-right"><?= CancellationPolicyDetails::getCodeById($newModel->bkgPref->bkg_cancel_rule_id); ?> 
				<span class="cabInfoTooltip" data-toggle="tooltip" data-html="true" data-placement="top" title='<?php echo $cancelDescNew; ?>'>
					<img src="/images/bx-info-circle.svg" alt="img" width="14" height="14">
				</span>
			</div>
		</div>
	<? } ?>
	<?php
	if ($newModel->bkgInvoice->bkg_total_amount > $prevModel->bkgInvoice->bkg_total_amount)
	{
		?>
		<div class="row"><div  class="col-xs-6 text-left">New total amount: </div><div class="col-xs-6 text-right"><?php echo Filter::moneyFormatter($newModel->bkgInvoice->bkg_total_amount - $newModel->bkgInvoice->bkg_extra_charge) ?></div></div>
	<? } ?>
	<div class="row"><div class="col-xs-6 text-left">Minimum payment required: </div><div class="col-xs-6 text-right"><?= Filter::moneyFormatter($newModel->minPay) ?></div></div>
	<div class="row text-danger"><div class="col-xs-7 text-left">Less: Refund from existing booking: </div><div class="col-xs-5 text-right"><?= Filter::moneyFormatter($prevModel->bkgInvoice->bkg_advance_amount - $newModel->rescheduleCharge); ?></div><br></div>
	<div class="row <?php echo ($newModel->minPayExtra > 0) ? '' : 'hide' ?>"><div class="col-xs-6 text-left">Minimum payment due: </div><div class="col-xs-6 text-right"><?= Filter::moneyFormatter($newModel->minPayExtra) ?></div></div>
	<div class="row">
		<div class="col-12 text-center mt20 mb20">
				<?php if($newModel->minPayExtra > 0){ ?>
				
                <div  onclick="rescheduleBooking(1);" class="btn btn-primary" id="paymentlinkbtn">Ask customer to pay <?=Filter::moneyFormatter($extraPay)?><br>Click here to send payment link</div>
				<?}else{?>
				<div onclick="rescheduleBooking(1);" class="btn btn-primary">Proceed</div>
				<?}?>
<!--			<div onclick="rescheduleBooking(1);" class="btn btn-primary"><?php// echo ($newModel->minPayExtra > 0) ? "Proceed to pay " . Filter::moneyFormatter($extraPay) : "Proceed" ?></div>-->
		
		</div>
	</div>
</div>
