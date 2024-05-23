<div class="">
	<div class="row">
		<span class="text-center mt0 text-danger errorMessage">

		</span>
	</div>
	 <input type="hidden" name="YII_CSRF_TOKEN" value= "<?=Yii::app()->request->csrfToken;?>">  
	<div class="row mb10">
		<div class="col-6  color-gray" style="white-space: nowrap;">New Pickup time: </div>
		<div class="col-6 text-right" style="white-space: nowrap;"><?php
			$date = date_create($newModel->bkg_pickup_date);
			echo date_format($date, 'd/m/Y') . ' ' . date_format($date, 'g:i A');
			?>
		</div>
	</div>
	<?php
	if ($prevModel->bkgPref->bkg_cancel_rule_id != $newModel->bkgPref->bkg_cancel_rule_id)
	{
		$cancelDescNew = CancellationPolicyDetails::model()->findByPk($newModel->bkgPref->bkg_cancel_rule_id)->cnp_desc;
		?>
		<div class="row mb10">
			<div class="col-6 color-gray" style="white-space: nowrap;">Cancellation rule: </div><div class="col-6 text-right" style="white-space: nowrap;"><?= CancellationPolicyDetails::getCodeById($newModel->bkgPref->bkg_cancel_rule_id); ?> 
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
		<div class="row mb10"><div  class="col-6 color-gray" style="white-space: nowrap;">New total amount: </div><div class="col-6 text-right" style="white-space: nowrap;"><?php echo Filter::moneyFormatter($newModel->bkgInvoice->bkg_total_amount - $newModel->bkgInvoice->bkg_extra_charge) ?></div></div>
	<? } ?>
	<?php
	if ($newModel->minPayExtra)
	{
		?>
		<div class="row mb10"><div class="col-6 color-gray" style="white-space: nowrap;">Minimum payment required: </div><div class="col-6 text-right" style="white-space: nowrap;"><?= Filter::moneyFormatter($newModel->minPay) ?></div></div>
	<? } ?>
	<div class="row text-danger mb10"><div class="col-6" style="white-space: nowrap;">Less: Refund from existing booking: </div><div class="col-6 text-right" style="white-space: nowrap;"><?= Filter::moneyFormatter($prevModel->bkgInvoice->bkg_advance_amount - $newModel->rescheduleCharge); ?></div><br>

	</div>

	<div class="row mb10 <?php echo ($newModel->minPayExtra > 0) ? '' : 'hide' ?>"><div class="col-6" style="white-space: nowrap;">Minimum payment due: </div><div class="col-6 text-right" style="white-space: nowrap;"><?= Filter::moneyFormatter($newModel->minPayExtra) ?></div></div>
	
	<div class="row  mt10">
		<?php if($newModel->bkg_id > 0){ ?>
		<div class="col-12 text-center">
			<div class="btn btn-info p5" onclick="cancelRescheduleRequest('<?=$newModel->bkg_id?>');"  style="white-space: nowrap;">Cancel Reschedule Request</div>
		</div>
		<?}?>
		<div class="col-12  text-center mt10 mb15">
			<div  class="btn btn-primary p5"   onclick="rescheduleBooking(1);"style="white-space: nowrap;"><?php echo ($newModel->minPayExtra > 0) ? "Proceed to pay " . Filter::moneyFormatter($newModel->minPayExtra) : "Proceed" ?></div>
		</div>
	</div>


</div>
<script>
function cancelRescheduleRequest(bkgId)
{
		$href = "<?php echo Yii::app()->createUrl('booking/canbooking') ?>";
		jQuery.ajax({type: 'POST',
			url: $href,
			dataType: 'json',
			data: {"bk_id": bkgId,'YII_CSRF_TOKEN': $('input[name="YII_CSRF_TOKEN"]').val(),'cancelReschedule':1},
			success: function (data)
			{
				if(data.success)
				{
					alert("Reschedule request cancelled successfully.");
					location.reload();
				}
			}
		});
}
</script>