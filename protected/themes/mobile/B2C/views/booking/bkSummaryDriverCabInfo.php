<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/fontawesome-web/css/all.min.css?v0.6');
$vehicleModel = $model->bkgBcb->bcbCab->vhcType->vht_model;
if($model->bkgBcb->bcbCab->vhc_type_id === Config::get('vehicle.genric.model.id'))
{
	$vehicleModel = OperatorVehicle::getCabModelName($model->bkgBcb->bcb_vendor_id, $model->bkgBcb->bcb_cab_id);
}
?>

<!--driver and cab details block start -->
<div class="content-boxed-widget p0 accordion-path">
    <div class="accordion accordion-style-0">
        <div class="accordion-border">
            <a href="javascript:void(0);" class="font18 uppercase" data-accordion="accordion-7b">Driver & Car Details<i class="fa fa-plus"></i></a>
            <div class="accordion-content" id="accordion-7b" style="display: none;">
                <div class="accordion-text">
					<div class="content-boxed-widget" id="driverCabDetails">
						<? if($model->bkgTrack->btk_drv_details_viewed == 0){ ?>
						<div id="viewDrvDetails">
							<p class="font-12 mb5 uppercase"><span><b>Driver & Cab Details:</b></span></p>
							<div class="clear"></div>
							<div class="content mb10 mt0 text-center">                                    
								<button type="button" class="uppercase btn-orange shadow-medium" onclick="viewDriverContact(<?= $model->bkg_id ?>)">Click Here to View</button>
							</div>
							<p class="font-10"><font color="red">** Free cancellation period ends (Cancellation charges will apply) as soon as you view driver details</font></p>
						</div>
						<?	}else { ?>
						<div class="pl0 ul-panel2" id ="driverDetails">
							<div class="one-half">
								<p class="color-gray line-height16 mb0 color-orange font-12">Driver Name:</p>
								<p class="line-height16 mb20 font-14 color-black">

									<?php
									if ($model->bkgBcb->bcb_driver_id != '' && $model->bkgBcb->bcb_driver_id != NULL)
									{
										?>
										<?= $model->bkgBcb->bcbDriver->drv_name ?>
										<?php
									}
									else
									{
										echo "To be assigned.";
									}
									?> <i class="fas fa-check-circle"></i>
                                </p>
							</div>
							<div class="one-half last-column">
                                <p class="color-gray line-height16 mb0 color-orange font-12">Driver Phone:</p>
                                <p class="line-height16 mb20 font-14 color-black">
									<?php
									if ($model->bkgBcb->bcb_driver_id != '' && $model->bkgBcb->bcb_driver_id != NULL)
									{
										$drvContactId	 = $model->bkgBcb->bcbDriver->drv_contact_id;
										$driver_phone	 = $model->bkgBcb->bcbDriver->drvContact->getContactDetails($drvContactId);
										?>
										<?php
										echo '+' . $driver_phone['phn_phone_country_code'] . $driver_phone['phn_phone_no'];
										?>
										<?php
									}
									else
									{
										echo "To be assigned.";
									}
									?> <i class="fas fa-check-circle"></i>
                                </p>
							</div>

							<div class="one-half">    
                                <p class="color-gray line-height16 mb0 color-orange font-12">Car License Plate: </p>
                                <p class="line-height16 mb20 font-14 color-black"><?php //= $model->bkgUserInfo->bkg_country_code                       ?>
									<?php
									if ($model->bkgBcb->bcb_cab_id != '' && $model->bkgBcb->bcb_cab_id != NULL)
									{
										?>
										<?= $model->bkgBcb->bcbCab->vhc_number; ?>
										<?php
									}
									else
									{
										echo "To be assigned.";
									}
									?> <i class="fas fa-check-circle"></i>
                                </p>
							</div>
							<div class="one-half last-column">
								<p class="color-gray line-height16 mb0 color-orange font-12">Make:</p> 
								<p class="line-height16 mb20 font-14 color-black"><?php //= $model->bkgUserInfo->bkg_country_code                        ?>
									<?php
									if ($model->bkgBcb->bcb_cab_id != '' && $model->bkgBcb->bcb_cab_id != NULL)
									{
										?>
										<?= $model->bkgBcb->bcbCab->vhcType->vht_make; ?>
										<?php
									}
									else
									{
										echo "To be assigned.";
									}
									?> <i class="fas fa-check-circle"></i>
								</p>
							</div>
							<div class="one-half">
								<p class="color-gray line-height16 mb0 color-orange font-12">Model: </p>
								<p class="line-height16 mb10 font-14 color-black"><?php //= $model->bkgUserInfo->bkg_country_code                         ?>
									<?php
									if ($model->bkgBcb->bcb_cab_id != '' && $model->bkgBcb->bcb_cab_id != NULL)
									{
										?>
										<?= $vehicleModel; ?>
										<?php
									}
									else
									{
										echo "To be assigned.";
									}
									?>
								</p>
							</div>
							<div class="clear"></div>
							<div class="text-center bg-orange p5 color-white mb5 mt10">
								<b>Do not board the cab if the cab or driver information does not match.</b>
							</div>

<?php
if ($model->bkgPref->bkg_trip_otp_required == 1)
{
	?>
								<div class="text-center bg-green3 color-white p5 mb10 ">
									<b>Please use OTP: <?= $model->bkgTrack->bkg_trip_otp ?> at the time of pickup. Don't share OTP before boarding the cab.</b>
								</div>
<?php } ?>
                        </div>
						<? } ?>  
					</div>			

				</div>
            </div>
        </div>
    </div>
</div>
<!-- driver and cab details block ends -->
<script>
    function viewDriverContact(booking_id)
    {
        $href = "<?= Yii::app()->createUrl('booking/viewCustomerDetails') ?>";
        jQuery.ajax({type: 'GET',
            url: $href,
            data: {"booking_id": booking_id, "type": 1},
            success: function (data)
            {
                var obj = $.parseJSON(data);
                if (obj.success == true)
                {
                    $("#driverDetails").show();
                    $("#viewDrvDetails").hide();
                    $("#driverCabDetails").load(window.location.href + " #driverCabDetails");
                    //window.location.reload();
                }
            }
        });
    }
</script>
