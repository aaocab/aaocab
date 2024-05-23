<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/fontawesome-web/css/all.min.css?v0.6');
?>

<?php
$this->layout		 = 'column1';
$isPromoApplicable	 = BookingSub::model()->getApplicable($model->bkg_from_city_id, $model->bkg_to_city_id, 1);
if ($model->bkgInvoice->bkg_discount_amount == 0 && $model->bkg_status == 15 && $isPromoApplicable)
{
	$isPromoApplicable = true;
}
else
{
	$isPromoApplicable = false;
}
if (($model->bkg_booking_type == 1 && $model->bkg_flexxi_type == 2) || $model->bkg_cav_id > 0)
{
	$isPromoApplicable = false;
}


if ($model->bkgInvoice->bkg_promo1_id > 0)
{
	$promoId = $model->bkgInvoice->bkg_promo1_id;
}
$pModel = Promos::model()->findByPk($promoId);
?>
<script>
    var pageInitialized = false;
    var huiObj = null;
    var prmObj = null;
    $(document).ready(function ()
    {
        if (pageInitialized)
            return;
        pageInitialized = true;

        huiObj = new HandleUI();
        huiObj.bkgId = '<?= $model->bkg_id ?>';
<?php
if ($pModel->prm_code != '')
{
	?>
	        huiObj.additionalParams.code = '<?= $pModel->prm_code ?>';
	<?php
}
if ($model->bkgInvoice->bkg_temp_credits > 0)
{
	?>
	        huiObj.additionalParams.coins = '<?= $model->bkgInvoice->bkg_temp_credits ?>';
<?php }
?>
        prmObj = new Promotion(huiObj);
<?php
if ($isPromoApplicable)
{
	?>
	        ajaxindicatorstart("");
	<?php
	if ($model->bkgInvoice->bkg_promo1_id > 0 || $pModel != false)
	{
		?>
		        prmObj.applyPromo(0, '<?= $pModel->prm_code; ?>');
		<?php
	}
	if ($model->bkgInvoice->bkg_temp_credits > 0)
	{
		?>
		        prmObj.applyPromo(0, '<?= $model->bkgInvoice->bkg_temp_credits; ?>');
		<?php
	}
}
else
{
	?>
	        $(".disPromoType").addClass('hide');
	<?php
}
?>
        bid = '<?= $model->bkg_id ?>';
        hsh = '<?= $model->hash ?>';
        $isRunningAjax = false;
        var promo = new Promo();
<?php
if ($isPromoApplicable)
{
	?>
	        ajaxindicatorstop();
<?php } ?>
    });
</script>
<?php
$version = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/bookNow.min.js?v=' . $version);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/login.js?v=' . $version);

/* @var $model Booking */
$detect		 = Yii::app()->mobileDetect;
// call methods
$isMobile	 = $detect->isMobile() && $detect->is("AndroidOS");
$hide		 = (($model->bkgInvoice->bkg_promo1_id != 0 && $model->bkgInvoice->bkg_discount_amount > 0) || $model->bkgInvoice->bkg_credits_used > 0) ? '' : 'hide';

$hidepromo	 = ($model->bkgInvoice->bkg_promo1_id != 0 || $model->bkgInvoice->bkg_discount_amount > 0) ? '' : 'hide';
$hide1		 = ($model->bkgInvoice->bkg_promo1_id != 0 || $model->bkgInvoice->bkg_discount_amount > 0) ? 'hide' : '';
$enableCOD	 = $model->enableCOD();
$row		 = 'row';

Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/promo.js?v=' . $version);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/promotion.js?v=' . $version);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/mobile/handleUI.js?v=' . $version);
?>
<?php $this->renderPartial("bkSummaryTravellerInfo", ['isredirct' => $isredirct, 'prevStep' => 4, "model" => $model], false, false); ?>

<?php
$bookingType = Booking::model()->getBookingType($model->bkg_booking_type);

if ($model->quote->routeDistance->routeDesc != '')
{
	$routeDesc = implode(' &rarr; ', $model->quote->routeDistance->routeDesc);
}
else
{
	$routeDesc = $model->bkgFromCity->cty_name . ' &rarr; ' . $model->bkgToCity->cty_name;
}
if ($model->quote->routeDistance->tripDistance != '')
{
	$tripDistance = $model->quote->routeDistance->tripDistance;
}
else
{
	$tripDistance = $model->bkg_trip_distance;
}
if ($model->quote->routeDuration->durationInWords != '')
{
	$durationInWords = $model->quote->routeDuration->durationInWords;
}
else
{
	$durationInWords = round($model->bkg_trip_duration / 60) . ' hrs';
}
//$cabType = $model->bkgVehicleType->getCabType();
//$cabType = SvcClassVhcCat::model()->getVctSvcList('string', '', $model->bkgSvcClassVhcCat->scv_vct_id);
$cabDetails = $model->bkgSvcClassVhcCat;

$response = Contact::referenceUserData($model->bkgUserInfo->bui_id, 3);
if ($response->getStatus())
{
	$contactNo	 = $response->getData()->phone['number'];
	$countryCode = $response->getData()->phone['ext'];
	$email		 = $response->getData()->email['email'];
}
?>

<div class="content-boxed-widget2 p15">			
    <p class="mb0"><spna class="color-black font-16"><b><?= $routeDesc; ?></b></spna> <span class="color-blue mr5"><i class="far fa-caret-square-right ml10 mr10"></i><?= '' . $bookingType ?></span> <span class="label-orange color-white font-12"><b><?= $cabDetails->scc_VehicleCategory->vct_label . ' | ' . $cabDetails->scc_ServiceClass->scc_label; ?></b></span></p>			
<div class="line-height8 color-gray">
	<?= Filter::formatBookingId($model->bkg_booking_id); ?>   &nbsp;|&nbsp;   <?= $tripDistance . " Kms" ?> 
</div>	
<div class="mb20 line-height8 color-gray">
	<?= $durationInWords ?> (+/- 30 mins for traffic)
</div>	
<div>
	<div class="one-half mr5">
		<p class="color-gray line-height16 mb0 color-orange font-12">Pick up:</p>
		<p class="line-height16 mb20 font-14 color-black"><b><?= date('jS M Y', strtotime($model->bkg_pickup_date)) ?><br><?= date('l', strtotime($model->bkg_pickup_date)) ?><br><?= date('h:i A', strtotime($model->bkg_pickup_date)) ?></b></p>
	</div>
	<?php
	$cnt			 = count($model->bookingRoutes) - 1;
	$dropDateTime	 = date('Y-m-d H:i:s', strtotime($model->bookingRoutes[$cnt]->brt_pickup_datetime . ' + ' . $model->bookingRoutes[$cnt]->brt_trip_duration . ' MINUTE'));
	?> 
	<div class="one-half last-column">
		<p class="color-gray line-height16 mb0 color-orange font-12">Drop:</p>
		<p class="line-height16 mb20 font-14 color-black"><b><?= date('jS M Y', strtotime($dropDateTime)) ?><br/> <?= date('l', strtotime($dropDateTime)) ?><br><?= date('h:i A', strtotime($dropDateTime)) ?></b></p>
	</div>
</div>
<!--    <div class="one-half mr5">
		<p class="color-gray line-height16 mb0">Car Type:</p>
		<p class="line-height18 font-14 color-black mb20"><span class="label-orange color-white"><b><?= $cabDetails->scc_VehicleCategory->vct_label . ' | ' . $cabDetails->scc_ServiceClass->scc_label; ?></b></span></p>
	</div>-->
<div>
    <div class="one-half last-column">
        <p class="color-gray line-height16 mb0 color-orange font-12">Customer name:</p>
        <p class="line-height16 font-14 color-black mb20"><?= $model->bkgUserInfo->getUsername() ?></p>
    </div>
	<div class="clear"></div>
</div>
<div>
    <div class="one-half mr5">
        <p class="color-gray line-height16 mb0 color-orange font-12">Contact Number:</p>
		<p class="line-height16 font-14 color-black mb10">+<?= $countryCode ?><?= (count(Yii::app()->user->loadUser()) > 0 && (Yii::app()->user->loadUser()->user_id==$model->bkgUserInfo->bkg_user_id))?$contactNo:Filter::maskPhoneNumber($contactNo) ?> <i class="fas fa-check-circle color-green-dark"></i></p>
    </div>
    <div class="one-half last-column">
        <p class="color-gray line-height16 mb0 color-orange font-12">Email id:</p>
		<p class="line-height16 font-14 color-black mb10"><?= (count(Yii::app()->user->loadUser()) > 0 && (Yii::app()->user->loadUser()->user_id==$model->bkgUserInfo->bkg_user_id))?$email:Filter::maskEmalAddress($email) ?></p>
    </div>
	<div class="clear"></div>
</div>

</div>
<!--<div class="content-boxed-widget2 p15 text-center font-16">
	Do not board the cab if the cab or driver information does not match.
</div>-->
<!-- special Details/additional Details block start-->
<?php
//if(Yii::app()->controller->action->id == "paynow")
//{
if (($model->bkgFromCity->cty_garage_address == $model->bkg_pickup_address || $model->bkg_pickup_address == '' || $model->bkgToCity->cty_garage_address == $model->bkg_drop_address || $model->bkg_drop_address == '') && $model->bkg_booking_type != 4)
{
	// Update Addresses
	$this->renderPartial("pickupLocationWidget", ["model" => $model], false, false);
	?>
	<input type="hidden" value="<? echo ($model->bkgFromCity->cty_garage_address == $model->bkg_pickup_address || $model->bkg_pickup_address == '' || $model->bkgToCity->cty_garage_address == $model->bkg_drop_address || $model->bkg_drop_address == '') ? '0' : '1' ?>" class="isPickupAdrsCls" name="isPickupAdrsCls">
	<?php
}
//}
$infosource				 = BookingAddInfo::model()->getInfosource('user');
$action					 = Yii::app()->request->getParam('action');
$hash					 = Yii::app()->shortHash->hash($model->bkg_id);
$otherExist				 = ($model->bkgAddInfo->bkg_spl_req_other != '') ? 'block' : 'none';
$model->bkg_chk_others	 = ($model->bkgAddInfo->bkg_spl_req_other != '') ? 1 : 0;
$form					 = $this->beginWidget('CActiveForm', array(
	'id'					 => 'bookingadditionalinfo', 'enableClientValidation' => true,
	'clientOptions'			 => array(
		'validateOnSubmit'	 => true,
		'errorCssClass'		 => 'has-error',
		'afterValidate'		 => ''
	),
	'errorMessageCssClass'	 => 'help-block',
	'htmlOptions'			 => array(
		'class'		 => '', 'enctype'	 => 'multipart/form-data'
	),
		));
/* @var $form CActiveForm */
if ($model->bkg_booking_type != 7)
{
	?>
	<div class="content p0 accordion-path bottom-0 additionalinfo">
		<div class="accordion accordion-style-0 content-boxed-widget p0">
			<div class="accordion-border">
				<a href="javascript:void(0)" class="font18 uppercase" data-accordion="accordion-3">Special Requests/ Additional Details<i class="fa fa-plus"></i></a>
				<div class="accordion-content" id="accordion-3" style="display: none;">
					<div class="accordion-text mt15">
						<span class="bottom-10 font-16"><b>Special Requests:</b></span>
						<div class="content p0 bottom-10 line-height18 font-14 color-gray">Please provide additional information to help us to serve you better.</div>
						<?=
						$form->hiddenField($model, 'bkg_id', ['id' => 'Booking_bkg_id']);
						$form->hiddenField($model, 'hash', ['id' => 'hash4', 'class' => 'clsHash', 'value' => $hash]);
						?>
						<label class="checkbox-inline p0">
							<div class="content p0 bottom-10">
								<?= $form->checkbox($model->bkgAddInfo, 'bkg_spl_req_senior_citizen_trvl', []) ?> Senior citizen traveling
							</div>
						</label>
						<label class="checkbox-inline p0">
							<div class="content p0 bottom-10">
								<?= $form->checkbox($model->bkgAddInfo, 'bkg_spl_req_kids_trvl', []) ?> Kids on board
							</div>
						</label>
						<label class="checkbox-inline p0">
							<div class="content p0 bottom-10">
								<?= $form->checkbox($model->bkgAddInfo, 'bkg_spl_req_woman_trvl', []) ?> Women traveling
							</div>
						</label>
						<?php
						$scvVctId = SvcClassVhcCat::model()->getCatIdBySvcid($model->bkg_vehicle_type_id);
						if ($scvVctId != VehicleCategory::SEDAN_ECONOMIC)
						{
							?>
							<label class="checkbox-inline p0">
								<div class="content p0 bottom-10">

									<?php
									echo $form->checkbox($model->bkgAddInfo, 'bkg_spl_req_carrier', []);
									?>
									Carrier Required
								</div>
							</label>
							<?php
						}
						?>
						<label class="checkbox-inline p0">
							<div class="content p0 bottom-10">
								<?= $form->checkbox($model->bkgAddInfo, 'bkg_spl_req_driver_english_speaking') ?> 
								English-speaking driver required
							</div>
						</label>
						<label class="checkbox-inline p0">
							<div class="content p0 bottom-10">
								<?= $form->checkbox($model, 'bkg_chk_others') ?> Others
							</div>
						</label>
						<label class="checkbox-inline pt0 pr30">
							<div class="input-simple-2 textarea has-icon bottom-30" id="othreq" style="display: <?= $otherExist ?>">
								<?= $form->textArea($model->bkgAddInfo, 'bkg_spl_req_other', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => " Other Requests", "class" => "textarea-simple-2"]))) ?>
							</div></label>
						<label class="checkbox-inline p0">
							<div class="content p0 bottom-10 line-height18">
								<?= $form->checkbox($model, 'bkg_add_my_trip') ?> Add a journey break (<span>&#x20b9</span>150/30mins). First 15min free. Unplanned journey breaks are not allowed for one-way trips
							</div>
						</label>
						<div class="content p0 bottom-10 select-box select-box-1" id="addmytrip" style="display: <?= $otherExist ?>">

							<?= $form->dropDownList($model->bkgAddInfo, 'bkg_spl_req_lunch_break_time', ['0' => 'Minutes', '30' => '30', '60' => '60', '90' => '90', '120' => '120', '150' => '150', '180' => '180']) ?>

						</div>
						<!-- -->
					</div>

					<div class="accordion-text mt20">
						<span class="bottom-10 font-16"><b>Additional Details: </b></span>
						<div class="content p0 bottom-20">
							Personal Or Business Trip?
							<div class="one-half"><label class="checkbox-inline pl0"><input placeholder="Bkg User Trip Type" id="BookingAddInfo_bkg_user_trip_type_0" value="1" type="radio" name="BookingAddInfo[bkg_user_trip_type]" checked="checked"> Personal</label></div>	
							<div class="one-half last-column"><label class="checkbox-inline pl0"><input placeholder="Bkg User Trip Type" id="BookingAddInfo_bkg_user_trip_type_1" value="2" type="radio" name="BookingAddInfo[bkg_user_trip_type]"> Business</label></div>
							<div class="clear"></div>
						</div>
					</div>
					<?php
					$readOnly = [];
					if (in_array($model->bkg_flexxi_type, [1, 2]))
					{
						$readOnly = ['readOnly' => 'readOnly'];
					}
					?>
					<input type="hidden" id="request_status" value="">
					<div class="content p0 bottom-10">
						<div class="input-simple-1 has-icon input-blue bottom-30 color-gray">
							<?= $form->numberField($model->bkgAddInfo, 'bkg_no_person', array('label' => '', 'placeholder' => 'Number of Passengers', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Number of Passengers", 'min' => 1, 'max' => $bdata['vht_capacity']] + $readOnly), 'groupOptions' => [])) ?>  
						</div>
					</div>
					<div class="content p0 bottom-10">
						<div class="select-box select-box-1 bottom-30"><em class="color-gray mt20 n"><?= $model->bkgAddInfo->getAttributeLabel('bkg_num_large_bag') ?></em>
							<? //= $form->numberField($model->bkgAddInfo, 'bkg_num_large_bag', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Number of large suitcases", 'min' => 0, 'max' => $bdata['vht_big_bag_capacity']] + $readOnly), 'groupOptions' => [])) ?>
							<?php
							$vct_Id		 = $model->bkgSvcClassVhcCat->scv_vct_id;
							$scc_Id		 = $model->bkgSvcClassVhcCat->scv_scc_id;
							$sbagRecord	 = VehicleCatSvcClass::smallbagBycategoryClass($model->bkgSvcClassVhcCat->scv_vct_id, $model->bkgSvcClassVhcCat->scv_scc_id);
							$lbag		 = floor($sbagRecord['vcsc_small_bag'] / 2);
							?>
							<select class="form-control" id="BookingAddInfo_bkg_num_large_bag" name="BookingAddInfo[bkg_num_large_bag]" onchange="luggage_info(this.value,<?php echo $vct_Id ?>,<?php echo $scc_Id ?>,<?php echo $sbagRecord['vcsc_small_bag'] ?>);">
								<?php
								for ($i = 0; $i <= $lbag; $i++)
								{
									?>
									<option value="<?php echo $i ?>"><?php echo $i ?></option>
								<?php } ?>		
							</select>                      
						</div>
					</div>
					<div class="content p0 bottom-10">
						<div class="select-box select-box-1 bottom-30"><em class="color-gray mt20 n"><?= $model->bkgAddInfo->getAttributeLabel('bkg_num_small_bag') ?></em>
							<? //= $form->numberField($model->bkgAddInfo, 'bkg_num_small_bag', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Number of small bags", 'min' => 0, 'max' => $bdata['vht_bag_capacity']] + $readOnly), 'groupOptions' => []))    ?>

							<select class="form-control" id="BookingAddInfo_bkg_num_small_bag" name="BookingAddInfo[bkg_num_small_bag]">
								<?php
								for ($i = 1; $i <= $sbagRecord['vcsc_small_bag']; $i++)
								{
									?>
									<option value="<?php echo $i ?>"><?php echo $i ?></option>
								<?php } ?>		
							</select>	
						</div>
					</div>
					<div class="content p0">
						<div class="select-box select-box-1 bottom-30">
							<em>How did you hear about Gozo cabs?</em>
							<?php
							$infosource		 = ['' => 'Select Infosource'] + $infosource;
							echo $form->dropDownList($model->bkgAddInfo, "bkg_info_source", $infosource, ['class' => 'form-control', 'style' => 'width:90%;margin-bottom:10px', 'placeholder' => 'Select Infosource']);
							?>
						</div>
					</div>
					<? $sourceDescShow	 = ($model->bkgAddInfo->bkg_info_source == 'Friend' || $model->bkgAddInfo->bkg_info_source == 'Other Media') ? '' : 'hide'; ?>
					<div class="content p0">
						<div class="form-group <?= $sourceDescShow ?> " id="source_desc_show">
							<label for="inputEmail" class="control-label col-xs-5">&nbsp;</label>
							<div class="input-simple-1 has-icon input-blue bottom-30">
								<?= $form->textField($model->bkgAddInfo, 'bkg_info_source_desc', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => ""]),)) ?>                      
							</div>
						</div>

					</div>
					<input type="hidden" name="bkCSRFToken" value="<?= Yii::app()->request->csrfToken ?>">
					<div class="content p0 bottom-10 text-center">
						<button type="button" class="btn-submit-orange" id ="additiondetails">Save</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
}
$this->endWidget();
?>
<!-- special details / Additional Details block end -->

<?php
// Billing
//$this->renderPartial("bkSummaryBilling", ["model" => $model, "isredirct" => $isredirct, "refcode" => $refcode, "whatappShareLink" => $whatappShareLink], false, false);
//PayBox
if ($model->bkg_agent_id == 1249 || !$model->bkg_agent_id > 0)
{
	$this->renderPartial("bkPayBox", ["walletBalance" => $walletBalance, "model" => $model], false, false);
}
else
{
	?>
	<div class="content bottom-0">
		<div class="notification-small notification-blue bottom-10 top-10">
			<p  >This booking was created by a Gozo Agent Partner. For payment &amp; other inquiries, contact the Gozo Agent Partner.  </ p>
		</div>			
	</div>
<? } ?>
<?php
// Apply Addons
if (count($applicableAddons) > 0)
{
	$this->renderPartial("bkSummaryAddons", ["model" => $model, 'applicableAddons' => $applicableAddons, 'routeRatesArr' => $routeRatesArr], false, false);
}
// Trip Plan
$this->renderPartial("bkSummaryTripPlan", ["model" => $model], false, false);
?>
<?php
//Driver & cab details
if ($model->bkg_status == 5)
{
	$this->renderPartial("bkSummaryDriverCabInfo", ["model" => $model], false, false);
}
?>

<!--cancellation info block start -->
<div class="content-boxed-widget p0 accordion-path">
    <div class="accordion accordion-style-0">
        <div class="accordion-border">
            <a href="javascript:void(0);" class="font18 uppercase" data-accordion="accordion-7">Cancellation info<i class="fa fa-plus"></i></a>
            <div class="accordion-content" id="accordion-7" style="display: none;">
                <div class="accordion-text">
					<div class="pl0 ul-panel2 ">
						<?php
						$cancelTimes_new	 = CancellationPolicy::initiateRequest($model);
						?>

						<div class="col-12 mb5">
							<div class="bg-green3 color-white font-11 p5 bottom-10 r-5">
								<p class="color-white mb0 text-center"><b>Free cancellation period</b></p>
								<p class="color-white mb0"><?= date('d M Y H:i a', strtotime($model->bkg_create_date)); ?> <span class="pull-right"><?= date('d M Y H:i a', strtotime(array_keys($cancelTimes_new->slabs)[0])) ?></span></p>

							</div>

							<div class="bg-orange color-white font-11 p5 bottom-10 r-5">
								<p class="color-white mb0 text-center">
									<b>Cancellation Charge: &#x20B9;<?= array_values($cancelTimes_new->slabs)[1]; ?></b></p>

								<p class="color-white mb0"><?= date('d M Y H:i a', strtotime(array_keys($cancelTimes_new->slabs)[0])) ?> <span class="pull-right"><?= date('d M Y H:i a', strtotime(array_keys($cancelTimes_new->slabs)[1])) ?></span></p>
							</div>
							<?php //} ?>
							<div class="bg-red2 color-white font-11 p5 bottom-10 r-5">
								<p class="color-white mb0 text-center"><b>No Refund</b></p>
								<p class="color-white mb0"><?= date('d M Y H:i a', strtotime($model->bkg_pickup_date)); ?> <span class="pull-right">After this</span></p>
							</div>
						</div>



						<?php
						/* 	$cancelTimes = CancellationPolicyRule::getCancelationTimeRange($model->bkg_id, 1);

						  foreach ($cancelTimes as $key => $value)
						  {
						  ?>
						  <?php
						  if ($value['CancelCharge'] == 100)
						  {
						  ?>
						  <div class="bg-red2 color-white font-11 p5 bottom-10 r-5">
						  <p class="color-white mb0 text-center"><b>No Refund</b></p>
						  <p class="color-white mb0"><?= $value['Fromdate']; ?> <span class="pull-right"><?= $value['ToDate']; ?></span></p>
						  </div>
						  <?php
						  }
						  else if ($value['CancelCharge'] == 25)
						  {
						  ?>
						  <div class="bg-orange color-white font-11 p5 bottom-10 r-5">
						  <p class="color-white mb0 text-center"><b><?= $value['CancelCharge']; ?>% cancellation charge</b></p>
						  <p class="color-white mb0"><?= $value['Fromdate']; ?> <span class="pull-right"><?= $value['ToDate']; ?></span></p>
						  </div>
						  <?php
						  }
						  else if ($value['CancelCharge'] == 0)
						  {
						  ?>
						  <div class="bg-green3 color-white font-11 p5 bottom-10 r-5">
						  <p class="color-white mb0 text-center"><b>Free cancellation period</b></p>
						  <p class="color-white mb0"><?= $value['Fromdate']; ?> <span class="pull-right"><?= $value['ToDate']; ?></span></p>
						  </div>
						  <?php
						  }
						  else
						  {
						  ?>
						  <div class="bg-orange color-white font-11 p5 bottom-10 r-5">
						  <p class="color-white mb0 text-center"><b><?= $value['CancelCharge']; ?>% cancellation charge</b></p>
						  <p class="color-white mb0"><?= $value['Fromdate']; ?> <span class="pull-right"><?= $value['ToDate']; ?></span></p>
						  </div>
						  <?php
						  }
						  } */
						?> 
						<div style="margin-top: 10px; line-height: 20px;">
							<?php
							$cancellationPoints	 = TncPoints::model()->getTncDescription(TncPoints::TNC_TYPE_CUSTOMER, $model->bkg_booking_type, $model->bkgSvcClassVhcCat->scv_scc_id, TncPoints::TNC_CANCELLATION); //print_r($cancellationPoints);
							//echo TncPoints::TNC_TYPE_CUSTOMER."====".$model->bkg_booking_type."======". $model->bkgSvcClassVhcCat->scv_scc_id."=====". TncPoints::TNC_CANCELLATION;exit;
							if (count($cancellationPoints) > 0)
							{
								echo "<ol style='font-size:10px; line-height:15px;padding-left:25px;'>";
								foreach ($cancellationPoints as $c)
								{
									echo "<li style='list-style-type:  circle'>" . $c['tnp_text'] . "</li>";
								}
								echo "</ol>";
							}
							?>
						</div>
					</div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- cancellation info block end -->


<?php
// Booking Information
$this->renderPartial("bkCanInfo", ["model" => $model], false, false);

if ($model->bkgInvoice->bkg_advance_amount == 0)
{
// Promo
	$this->renderPartial("bkSummaryPromo", ["model" => $model, "walletBalance" => $walletBalance, "creditVal" => $creditVal], $arrPromoTemplateData, false, false);
}
// Pay
$this->renderPartial("paywidget", ["model" => $model, 'isredirct' => $isredirct], false);

$fcity	 = Cities::getName($model->bkg_from_city_id);
$tcity	 = Cities::getName($model->bkg_to_city_id);
?>
<?php ?>

<!--important info block here -->
<!--
<div class="content-boxed-widget p0 accordion-path">
    <div class="accordion accordion-style-0">
        <div class="accordion-border">
            <a href="javascript:void(0);" class="font18 uppercase" data-accordion="accordion-6">Important info<i class="fa fa-plus"></i></a>
            <div class="accordion-content" id="accordion-6" style="display: none;">
                <div class="accordion-text">
                    <div class="pl0 ul-panel2">
<?php /* 	$bookingTypeCheck	 = $model->bkg_booking_type;
  if ($bookingTypeCheck == 1)
  {
  ?>
  <ol type="1">
  <li>Your reservation is subject to Gozocabs terms and conditions. (http://www.gozocabs.com/terms)</li>
  <li>YOU HAVE BOOKED A ONE-WAY POINT TO POINT JOURNEY: Gozo is committed to punctuality of pickups and high quality of service for all our customers. We are able to offer one-way transfers at a very attractive price by scheduling our vehicles to serve the one-way transfer needs of multiple customers in a sequence. As an example, if you are going one-way from City  A to City B we have estimated your time of travel and have most likely scheduled our driver to pickup another customer in City B.
  <ol type="i">
  <li>
  ONE-WAY means pickup from your source-address and drop at your destination-address.
  </li>
  <li>
  <strong>PLEASE BE READY ON TIME FOR PICKUP:</strong> In order to ensure that all our customers receive timely service, we request you to be ready on time. Your trip may be cancelled if you are not ready to travel at the scheduled time.
  </li>
  <li>
  <strong>DRIVER WILL WAIT FOR MAX 15MIN AT PICKUP TIME:</strong> Driver will wait for you for a max of 15mins at the scheduled pickup time and place. Your trip may be canceled or rescheduled at your cost if you are not ready to leave on time.
  </li>
  <li>
  <strong>ON-JOURNEY STOPS OR WAYPOINTS ARE NOT ALLOWED UNLESS PART OF ITINERARY: </strong>If you plan to take a lunch break or stop during the journey, let us know before time so we can estimate the time of your ‘on journey’ break and avoid any scheduling issues for our vehicle. DRIVER WILL NOT STOP ON THE ROUTE UNLESS ITS PART OF ITINERARY AND WRITTEN IN THIS CONFIRMATION EMAIL.  A SINGLE 15-MINUTE COMPLIMENTARY ON JOURNEY BREAK IS INCLUDED FOR TRIPS LONGER THAN 4 HOURS.
  </li>
  <li>
  <strong>WAITING CHARGES: </strong>If the cab is requested to wait at any time along your trip(provided the cab driver is able to wait) you will be charged for waiting charges at the rate of Rs.300/hour rounded to the closest 30minutes.
  </li>
  <li>
  <strong>YOU ARE RESPONSIBLE FOR NIGHT DRIVING CHARGES (IF APPLICABLE): </strong>If your trip is scheduled to start between 10pm and 6am or if the trip is estimated to end post 10pm then night driving allowance charges of Rs. 250/- shall also be applicable. Check your confirmation email on whether driver allowance is included.
  </li>
  <li>
  <strong>REST FOR DRIVER & CHANGE OF VEHICLE:</strong> Our drivers are required to drive not longer than 4 hours continuously taking atleast a 30 minute break. Longer drives may involve a change of vehicle and driver for one-way transfers.
  </li>
  </ol>
  <li>Your quoted rate is applicable ONLY for the exact itinerary mentioned in this reservation. </li>
  <ol type="a">
  <li>YOUR QUOTATION is for a point to point drop only as specified by the pickup address, drop address and routing instructions (use of specified routes).</li>
  <li>Drivable <? ( $model->bkg_trip_distance ) ?> kms in your quote are estimates. You will be billed for the actual distance travelled by you. If you have not provided the exact address before your pickup or if the exact address could not be determined at time of providing you the booking amount estimate then your quote includes travel from city center to city center. Distance to be driven as quoted in the booking is only applicable for travel between the addresses, waypoints and locations as exactly specified in your itinerary.</li>
  <li>Driver will not agree to any changes to route or itinerary unless the changes are made and the updated itinerary is documented in your reservation. Any changes, additions of waypoints, pick-up points, drop points, halts, destination cities or sightseeing spots are ABSOLUTELY NOT AUTHORIZED unless they are added to your itinerary and confirmed in writing through a booking confirmation email. Changes to itinerary will lead to pricing changes.</li>
  <li>It is required that the entire itinerary be documented in your reservation. The quoted price will change based on multiple factors including but not limited to the itinerary, waypoints, driving terrain, local union fees, local restrictions and estimated distances to be driven.</li>
  </ol>
  <li>We require exact pickup and drop addresses to be provided for your itinerary before your vehicle and driver can be assigned. Unless we have these atleast 12hours before your trip the reservation will be subject to cancellation and all resulting cancellation charges are to be borne by you. Once the addresses are provided, these may cause the quotation to change.</li>
  <li>One day means one calendar day (12am midnight to 11.59pm next day).</li>
  <li>AT PICKUP TIME.
  <ol type="a">
  <li>
  You must CHECK IDENTIFICATION OF THE DRIVER AND CONFIRM THE LICENSE PLATE OF YOUR CAR AT THE START OF THE TRIP.
  </li>
  <li>
  DO NOT RIDE IF THE VEHICLE IS NOT COMMERCIALLY LICENSED. (YELLOW COLORED LICENSE PLATE WITH BLACK LETTERS).
  </li>
  <li>
  DO NOT RIDE IF THE VEHICLE & DRIVER INFORMATION DO NOT MATCH THE INFORMATION PROVIDED BY GOZO. WE SHALL NOT BE RESPONSIBLE OR LIABLE IN ANY MANNER IF YOU CHOOSE TO RIDE IN A VEHICLE THAT IS NOT COMMERCIALLY LICENSED OR RIDE WITH A DRIVER OTHER THAN THE ONE THAT WE HAVE ASSIGNED TO YOU.
  </li>
  <li>
  You may ask the driver for identification to ensure you are riding with the correct driver. Drivers may ask for your identification too. Failure to provide identification will make your booking subject to cancellation at your cost.
  </li>
  <li>
  PROVIDE THE ONE-TIME PASSWORD (OTP) TO THE DRIVER SO HE MAY START THE TRIP.
  </li>
  <li>
  <strong>YOU AGREE TO MAKE PAYMENTS FOR YOUR TRIP AS PER THE FOLLOWING PAYMENT SCHEDULE. </strong>
  <ol type="i">
  <li>
  <strong>Advance payment:</strong> You are required to pay in full or a minimum specified percent of total amount as advance to confirm your booking.
  </li>
  <li>
  <strong>50% of customers remaining payable amount </strong> shall be paid to the taxi operator while boarding the cab
  </li>
  <li>
  <strong>Daily part payments: </strong> You are required to pay the remaining the amount in equal parts and is payable per day during your remaining days of the trip. The customer must settle all payment atleast 24hours last day of the trip or as requested by the taxi operator.
  </li>
  </ol>
  </li>
  </ol>
  </li>
  <li>
  <strong>YOU ARE HIRING AN AC CAR. </strong>For drives in hilly regions, the air conditioning may be switched off to prevent engine overload.
  </li>
  <li>
  CANCELLATION: You may cancel your reservation by login to our Mobile App or Website. All bookings cancelled less than 24 hours before the scheduled pickup time shall be subject to Cancellation & Refund Policy as laid down in Terms & Conditions page on our website.
  </li>
  <li>
  INCLUSIONS AND EXCLUSIONS: Your reservation indicates the total number of people and the amount of luggage that the vehicle will accommodate. Please ensure you have clearly communicated the number of passengers and amount of luggage you will carry atleast 24hours before pickup . The driver will not allow any additional passengers or luggage beyond what is allowed in the category of vehicle stated in the reservation. Parking charges, Airport Entry fees or any other form of Entry fees are NOT INCLUDED in the quotation. The customer is responsible for all such additional charges incurred during the trip.
  </li>
  <li>
  When you book our Value or Economy services, we can only promise to provide a vehicle in a certain category but we cannot guarantee a specific model of vehicle. WE CANNOT GUARANTEE THE AVAILABILITY OF A SPECIFIC MODEL OR CONFIGURATION OF VEHICLE UNLESS YOU HAVE MADE RESERVATIONS AND PAID FOR A SERVICE TYPE THAT GUARANTEES A SPECIFIC VEHICLE MODEL.
  </li>
  </ol>

  <?php
  }
  else if ($bookingTypeCheck == 4)
  {
  ?>
  <ol type="1">
  <li>Your reservation is subject to Gozocabs terms and conditions. (http://www.gozocabs.com/terms)</li>
  <li>YOU HAVE BOOKED A LOCAL RENTAL FOR AIRPORT TRANSFER:Gozo is committed to punctuality of pickups and high quality of service for all our customers. We are able to offer low priced airport transfers at a very attractive price by scheduling our vehicles to serve the airport pickup and drop needs of multiple customers in a sequence. As an example, if you are going from City center to Airport we have estimated your time of travel and have most likely scheduled our driver to pickup another customer at the Airport.
  <ol type="a">
  <li>
  AIRPORT TRANSFER means pickup from your source-address and drop at your destination-address with at least one of the two locations being an airport.   </li>
  </li>
  <li>
  <strong>PLEASE BE READY ON TIME FOR PICKUP: </strong>In order to ensure that all our customers receive timely service, we request you to be ready on time. Your trip may be cancelled if you are not ready to travel at the scheduled time.
  </li>
  <li>
  <strong>WAITING CHARGES:</strong> If the cab is requested to wait at any time along your trip(provided the cab driver is able to wait) you will be charged for waiting charges at the rate of Rs.300/hour rounded to the closest 30minutes.
  </li>
  <li>
  <strong>YOU ARE RESPONSIBLE FOR NIGHT DRIVING CHARGES (IF APPLICABLE):</strong> If your trip is scheduled to start between 10pm and 6am or if the trip is estimated to end post 10pm then night driving allowance charges of Rs. 250/- shall also be applicable. Check your confirmation email on whether driver allowance is included.
  </li>
  </ol>
  </li>
  <li>
  Your quoted rate is applicable ONLY for the exact itinerary mentioned in this reservation.
  </li>
  <li>
  AT PICKUP TIME.
  <ol type="I">
  <li>
  You must CHECK IDENTIFICATION OF THE DRIVER AND CONFIRM THE LICENSE PLATE OF YOUR CAR AT THE START OF THE TRIP.
  </li>
  <li>
  DO NOT RIDE IF THE VEHICLE IS NOT COMMERCIALLY LICENSED. (YELLOW COLORED LICENSE PLATE WITH BLACK LETTERS). II.	DO NOT RIDE IF THE VEHICLE IS NOT COMMERCIALLY LICENSED. (YELLOW COLORED LICENSE PLATE WITH BLACK LETTERS).
  </li>
  <li>
  DO NOT RIDE IF THE VEHICLE & DRIVER INFORMATION DO NOT MATCH THE INFORMATION PROVIDED BY GOZO. WE SHALL NOT BE RESPONSIBLE OR LIABLE IN ANY MANNER IF YOU CHOOSE TO RIDE IN A VEHICLE THAT IS NOT COMMERCIALLY LICENSED OR RIDE WITH A DRIVER OTHER THAN THE ONE THAT WE HAVE ASSIGNED TO YOU.
  </li>
  <li>
  You may ask the driver for identification to ensure you are riding with the correct driver. Drivers may ask for your identification too. Failure to provide identification will make your booking subject to cancellation at your cost.
  </li>
  <li>
  PROVIDE THE ONE-TIME PASSWORD (OTP) TO THE DRIVER SO HE MAY START THE TRIP.
  </li>
  </ol>
  </li>
  <li>
  <strong>YOU ARE HIRING AN AC CAR.</strong> For drives in hilly regions, the air conditioning may be switched off to prevent engine overload.
  </li>
  <li>
  CANCELLATION: You may cancel your reservation by login to our Mobile App or Website. All bookings cancelled less than 24 hours before the scheduled pickup time shall be subject to Cancellation & Refund Policy as laid down in Terms & Conditions page on our website.
  </li>
  <li>
  INCLUSIONS AND EXCLUSIONS: Your reservation indicates the total number of people and the amount of luggage that the vehicle will accommodate. Please ensure you have clearly communicated the number of passengers and amount of luggage you will carry atleast 24hours before pickup . The driver will not allow any additional passengers or luggage beyond what is allowed in the category of vehicle stated in the reservation. Parking charges, Airport Entry fees or any other form of Entry fees are NOT INCLUDED in the quotation. The customer is responsible for all such additional charges incurred during the trip.
  </li>
  <li>
  When you book our Value or Economy services, we can only promise to provide a vehicle in a certain category but we cannot guarantee a specific model of vehicle. WE CANNOT GUARANTEE THE AVAILABILITY OF A SPECIFIC MODEL OR CONFIGURATION OF VEHICLE UNLESS YOU HAVE MADE RESERVATIONS AND PAID FOR A SERVICE TYPE THAT GUARANTEES A SPECIFIC VEHICLE MODEL.
  </li>
  </ol>

  <?php
  }
  else if ($bookingTypeCheck == 9 || $bookingTypeCheck == 10 || $bookingTypeCheck == 11 || $bookingTypeCheck == 2 || $bookingTypeCheck == 3)
  {
  ?>
  <ol type="1">
  <li>Your reservation is subject to Gozocabs terms and conditions.</li>
  <li>YOU HAVE BOOKED A TIME & DISTANCE BASED RENTAL
  <ol type="1">
  <li>
  <strong>TIME & DISTANCE BASED RENTAL:</strong> This quotation is for time-and-distance based rental with a exact itinerary as specified by your pickup & drop addresses, routing and cities listed on your itinerary. You may not direct the vehicle outside the bounds of the towns and cities listed in your itinerary.
  </li>
  <li>
  <strong>SIGHTSEEING PLAN MUST BE DOCUMENTED IN ITINERARY:</strong> Your rental DOES NOT INCLUDE ANY SIGHTSEEING UNLESS EXPLICITLY NOTED IN THE ITINERARY. Any sightseeing even when listed is limited to be within the city limits of the listed cities/towns on the itinerary. Please share your complete trip plan with Gozo so we can avoid any confusion or inconvenience to you by having the trip plan clearly listed in your planned itinerary.
  </li>
  <li>
  <strong>YOU SHALL BE BILLED FOR THE SUM OF OUR TIME & DISTANCE BASED ESTIMATE (ESTIMATED HOURS & KMS) AND ANY ADDITIONAL TIME OR DISTANCE USED BY YOU.</strong> As an example, For all time-and-distance based outstation rentals, a minimum of 250km (North India) or 300km (South India) charge will be billed per day of rental even if the vehicle is not utilized for those distances per day of rental. For all time-and-distance based local rentals, the included hours and kms is specified in your booking.
  </li>
  <li>
  <strong>YOU AGREE TO MAKE PAYMENTS FOR YOUR TRIP AS PER THE FOLLOWING PAYMENT SCHEDULE.</strong>
  <ol type="1">
  <li>
  <strong>Advance payment:</strong> You are required to pay in full or a minimum specified percent of total amount as advance to confirm your booking.
  </li>
  <li>
  <strong>50% of customers remaining payable amount </strong>shall be paid to the taxi operator while boarding the cab on the first day of trip
  </li>
  <li>
  <strong>Daily part payments:</strong> You are required to pay the remaining the amount in equal parts and is payable per day during your remaining days of the trip. The customer must settle all payment atleast 24hours last day of the trip or as requested by the taxi operator.
  </li>
  </ol>
  </li>
  <li>
  Driver will not agree to any changes to itinerary unless the changes are made and the updated itinerary is documented in your reservation. Any changes, additions of waypoints, pick-up points, drop points, halts, destination cities or sightseeing spots are ABSOLUTELY NOT AUTHORIZED unless they are added to your itinerary and confirmed in writing through a booking confirmation email. Changes to itinerary will lead to pricing changes
  </li>
  <li>
  <strong>YOU ARE RESPONSIBLE FOR DRIVERS DAY-TIME DRIVING ALLOWANCE & NIGHT DRIVING CHARGES (IF APPLICABLE): </strong>For all time-and-based rentals a Rs. 250/- daytime allowance is payable by the customer to the driver per day. If the driver is required to drive between 10pm and 6am on any day during the period of the rental then a NIGHT DRIVING ALLOWANCE of additional Rs. 250/- shall also be payable for the days when the driver was asked to drive at night. Your confirmation email clearly states whether driver allowance is included in your quotation or to be paid seperately.
  </li>
  <li>
  It is required that the entire itinerary be documented in your reservation. The quoted price will change based on multiple factors including but not limited to the itinerary, waypoints, driving terrain, local union fees, local restrictions and estimated distances to be driven
  </li>
  </ol>
  </li>
  <li>
  One day means one calendar day (12am midnight to 11.59pm next day). Time-and-distance based rentals where the customers usage exceeds the original estimate by longer than 4 hours are rounded up to a full additional day.
  </li>
  <li>
  We require exact pickup and drop addresses to be provided for your itinerary before your vehicle and driver can be assigned. Unless we have these atleast 12hours before your trip,the reservation will be subject to cancellation and all resulting cancellation charges are to be borne by you. Once the addresses are provided, these may cause the quotation to change.
  </li>
  <li>
  We take security seriously.
  <ol type="1">
  <li>
  You MUST CHECK identification of the driver and confirm the license plate of your car AT THE START OF THE TRIP. If the Driver name and ID do not match the name provided to you by Gozo, please DO NOT RIDE unless it has been OK’ed with a new SMS directly from Gozocabs first. Please ensure that the license plate of the car matches the information provided to you by Gozo. Gozo only provides you taxis that carry a commercial license permit (License plate is yellow with black letters).
  </li>
  <li>
  DO NOT RIDE IF THE VEHICLE & DRIVER INFORMATION DO NOT MATCH THE INFORMATION PROVIDED BY GOZO. WE SHALL NOT BE RESPONSIBLE OR LIABLE IN ANY MANNER IF YOU CHOOSE TO RIDE IN A VEHICLE THAT IS NOT COMMERCIALLY LICENSED OR RIDE WITH A DRIVER OTHER THAN THE ONE THAT WE HAVE ASSIGNED TO YOU.
  </li>
  <li>
  You may ask the driver for identification to ensure you are riding with the correct driver. Drivers may ask for your identification too. Failure to provide identification will make your booking subject to cancellation at your cost.
  </li>
  <li>
  PROVIDE THE ONE-TIME PASSWORD (OTP) TO THE DRIVER SO HE MAY START THE TRIP.
  </li>
  </ol>
  </li>
  <li>
  At pickup time, your cab will wait for a maximum of 30 minutes. If requested to wait longer (provided the cab driver is able to wait) you will be responsible for waiting charges at the rate of Rs.200/hour.
  </li>
  <li>
  <strong>YOU ARE HIRING AN AC CAR. </strong>For drives in hilly regions, the air conditioning may be switched off to prevent engine overload.
  </li>
  <li>
  CANCELLATION: You may cancel your  reservation by logging onto www.gozocabs.com and and cancelling your reservation directly. All bookings cancelled less than 24hours before a pickup shall be subject to a cancellation charge.
  </li>
  <li>
  INCLUSIONS AND EXCLUSIONS: Your reservation indicates the total number of people and the amount of luggage that the vehicle will accommodate. Please ensure you have clearly communicated the number of passengers and amount of luggage you will carry atleast 24hours before pickup . The driver will not allow any additional passengers or luggage beyond what is allowed in the category of vehicle stated in the reservation. Parking charges, Airport Entry fees or any other form of Entry fees are NOT INCLUDED in the quotation. The customer is responsible for all such additional charges incurred during the trip.
  </li>
  <li>
  When you book our Value or Economy services, we can only promise to provide a vehicle in a certain category but we cannot guarantee a specific model of vehicle. WE CANNOT GUARANTEE THE AVAILABILITY OF A SPECIFIC MODEL OR CONFIGURATION OF VEHICLE UNLESS YOU HAVE MADE RESERVATIONS AND PAID FOR A SERVICE TYPE THAT GUARANTEES A SPECIFIC VEHICLE MODEL.
  </li>
  </ol>
  <?
  }
  else
  {
  ?>
  <ol type="1">
  <li>Your reservation is subject to Gozocabs terms and conditions.  (http://www.gozocabs.com/terms)</li>
  <li>FLEXXI trips are always ONE-WAY trips.
  <ol type="i">
  <li>
  <strong>ONLY Flexxi Promoters are picked up from their specified source-address and dropped at their destination-address. All other Flexxi Riders must assemble at one of our pre-specified pickup points ahead of the trip start time,</strong> the car will pick them up from  that location and shall drop the Flexxi riders at their respective destination address.
  </li>
  <li>
  <strong> PLEASE BE READY ON TIME FOR PICKUP:</strong> In order to ensure that all our customers receive timely service, we request you to be ready on time. Your trip may be cancelled if you are not ready to travel at the scheduled time.
  </li>
  <li>
  <strong> DRIVER WILL WAIT FOR MAX 15MIN AT PICKUP TIME:</strong> Driver will wait for you for a max of 15mins at the scheduled pickup time and place. Your trip may be canceled or rescheduled at your cost if you are not ready to leave on time.
  </li>
  <li>
  <strong>ON-JOURNEY STOPS ARE NOT ALLOWED FOR FLEXXI TRIPS.</strong> A SINGLE 15-MINUTE COMPLIMENTARY ON JOURNEY BREAK IS INCLUDED FOR TRIPS LONGER THAN 4 HOURS. ALL TRAVELERS TAKE A COMMON 15 MINUTE STOP.
  </li>
  <li>
  <strong>YOU ARE RESPONSIBLE FOR NIGHT DRIVING CHARGES (IF APPLICABLE): </strong>If your trip is scheduled to start between 10pm and 6am or if the trip is estimated to end post 10pm then night driving allowance charges of Rs. 250/- shall be applicable and shall be distributed evenly across all riders who purchased a seat for the Flexxi trip. Check your confirmation email on whether driver allowance is included.
  </li>
  <li>
  <strong>REST FOR DRIVER & CHANGE OF VEHICLE:</strong> Our drivers are required to drive not longer than 4 hours continuously taking atleast a 30 minute break. Longer drives may involve a change of vehicle and driver for one-way transfers.
  </li>
  </ol>
  </li>
  <li>Your quoted rate is applicable ONLY for the exact itinerary mentioned in this reservation.
  <ol type="a">
  <li>
  YOUR QUOTATION is for a point to point drop only as specified by the pickup address, drop address and routing instructions (use of specified routes).
  </li>
  <li>
  Drivable (estimated) kms in your quote are estimates. You will be billed for the actual distance travelled by you. If you have not provided the exact address before your pickup or if the exact address could not be determined at time of providing you the booking amount estimate then your quote includes travel from city center to city center. Distance to be driven as quoted in the booking is only applicable for travel between the addresses, waypoints and locations as exactly specified in your itinerary.
  </li>
  <li>
  Driver will not agree to any changes to route or itinerary unless the changes are made and the updated itinerary is documented in your reservation. Any changes, additions of waypoints, pick-up points, drop points, halts, destination cities or sightseeing spots are ABSOLUTELY NOT AUTHORIZED unless they are added to your itinerary and confirmed in writing through a booking confirmation email. Changes to itinerary will lead to pricing changes.
  </li>
  <li>
  It is required that the entire itinerary be documented in your reservation. The quoted price will change based on multiple factors including but not limited to the itinerary, waypoints, driving terrain, local union fees, local restrictions and estimated distances to be driven.
  </li>
  </ol>
  </li>
  <li>
  One day means one calendar day (12am midnight to 11.59pm next day).
  </li>
  <li>AT PICKUP TIME.
  <ol type="a">
  <li>
  You must CHECK IDENTIFICATION OF THE DRIVER AND CONFIRM THE LICENSE PLATE OF YOUR CAR AT THE START OF THE TRIP.
  </li>
  <li>
  DO NOT RIDE IF THE VEHICLE IS NOT COMMERCIALLY LICENSED. (YELLOW COLORED LICENSE PLATE WITH BLACK LETTERS).
  </li>
  <li>
  DO NOT RIDE IF THE VEHICLE & DRIVER INFORMATION DO NOT MATCH THE INFORMATION PROVIDED BY GOZO. WE SHALL NOT BE RESPONSIBLE OR LIABLE IN ANY MANNER IF YOU CHOOSE TO RIDE IN A VEHICLE THAT IS NOT COMMERCIALLY LICENSED OR RIDE WITH A DRIVER OTHER THAN THE ONE THAT WE HAVE ASSIGNED TO YOU.
  </li>
  <li>
  You may ask the driver for identification to ensure you are riding with the correct driver. Drivers may ask for your identification too. Failure to provide identification will make your booking subject to cancellation at your cost.
  </li>
  <li>
  PROVIDE THE ONE-TIME PASSWORD (OTP) TO THE DRIVER SO HE MAY START THE TRIP.
  </li>
  </ol>
  </li>
  <li>
  <stong>YOU ARE HIRING AN AC CAR.</strong> For drives in hilly regions, the air conditioning may be switched off to prevent engine overload.
  </li>
  <li>
  <strong>YOU AGREE TO MAKE PAYMENTS FOR YOUR TRIP AS PER THE FOLLOWING PAYMENT SCHEDULE. </strong>
  <ol type="a">
  <li>
  <strong>Advance payment:</strong> You are required to pay in full or a minimum specified percent of total amount as advance to confirm your booking.
  </li>
  <li>
  <strong>100% of customers remaining payable amount </strong>shall be paid to the taxi operator while boarding the cab
  </li>
  </ol>
  </li>
  <li>
  CANCELLATION: You may cancel your reservation by login to our Mobile App or Website. All bookings cancelled less than 24 hours before the scheduled pickup time shall be subject to Cancellation & Refund Policy as laid down in Terms & Conditions page on our website.
  </li>
  <li>
  INCLUSIONS AND EXCLUSIONS: Your reservation indicates the total number of people and the amount of luggage that the vehicle will accommodate. Please ensure you have clearly communicated the number of passengers and amount of luggage you will carry atleast 24hours before pickup . The driver will not allow any additional passengers or luggage beyond what is allowed in the category of vehicle stated in the reservation. Parking charges, Airport Entry fees or any other form of Entry fees are NOT INCLUDED in the quotation. The customer is responsible for all such additional charges incurred during the trip.
  </li>
  <li>
  When you book our Value or Economy services, we can only promise to provide a vehicle in a certain category but we cannot guarantee a specific model of vehicle. WE CANNOT GUARANTEE THE AVAILABILITY OF A SPECIFIC MODEL OR CONFIGURATION OF VEHICLE UNLESS YOU HAVE MADE RESERVATIONS AND PAID FOR A SERVICE TYPE THAT GUARANTEES A SPECIFIC VEHICLE MODEL.
  </li>
  <li>
  Gozo is committed to punctuality of pickups and high quality of service for all our customers. We are able to offer one-way FLEXXI transfers at extremely low prices by pairing the requirements of multiple customers to travel together in the same taxi. Because multiple travelers are involved, you MUST BE READY ON TIME and travel per the scheduled time. Flexxi Promoters have confirmed travel plans and are looking to save money for their trip. Promoters book the car at full price and then offer unused seats in their car for Flexxi Riders to purchase. Flexxi Riders have flexible travel plans, they choose to buy unused seats in someone else's taxi.
  </li>
  <li>
  If you are a FLEXXI promoter, you have booked a car at the normal price and have offered to share some unused seats in your hired taxi with other Flexxi Riders. Gozo is helping find other riders to share the ride with you. If we are able to share other riders, they will share the cost of your trip and you will save money. You may cancel your trip for a full refund upto 24 hours in advance of the trip. If you cancel or reschedule within 24hours of your scheduled trip start time, your booking shall be subject to cancellation charges.
  </li>
  <li>
  If you are a FLEXXI rider, you have flexible plans and are looking to ride for a cheap fare in someone else's taxi. You are buying a seat in a taxi that someone else has hired. Gozo is merely acting as a facilitator and enabling you and the other party to save money by sharing the ride together. If the FLEXXI promoter cancels the trip, your trip may likely also be canceled. You may cancel upto 24hours in advance of the trip. If you cancel within 24hours of your scheduled trip start time, your booking shall be subject to cancellation charges.
  </li>
  </ol>
  <? } */ ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> -->
<!--special instruction block here -->
<?php
if (!empty($note))
{

	$this->renderPartial("bkSummaryNotes", ["note" => $note], false, false);
}
?>


<div class="clear"></div>

<script>
    $jsBookNow = new BookNow();
    var isAddonId = '<?= $model->bkgInvoice->bkg_addon_ids ?>';

    $(document).ready(function () {
        $("#BookingAddInfo_bkg_info_source").change(function () {
            var infosource = $("#BookingAddInfo_bkg_info_source").val();
            extraAdditionalInfo(infosource);
        });
        if ($('#<?= CHtml::activeId($model, "bkg_add_my_trip") ?>').is(':checked'))
        {
            $("#addmytrip").show();
        }
        $(".txtAddonLabel").html($(".adnlbltxt" + isAddonId).text());
		$("a[data-accordion='accordion-60']").click();
    });
    $(document).on('click', '.menu-hide', function () {
        $("#menu-hider").trigger("click");
        $("#errMsgPromo").html('');
    });

    $(document).on('click', '.termscls', function () {
        var href = '<?= Yii::app()->createUrl('index/tns') ?>';
        window.open(href, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=400,height=400");
        return false;
    });

    $('#additiondetails').click(function (event) {
        var noPerson = $('#BookingAddInfo_bkg_no_person').val();
        var smallBag = $('#BookingAddInfo_bkg_num_small_bag').val();
        var bigBag = $('#BookingAddInfo_bkg_num_large_bag').val();
        var vhCapacity = '<?= $capacity ?>';
        var smallbagCapacity = '<?= $bagCapacity ?>';
        var bigbagCapacity = '<?= $bigBagCapacity ?>';
        var href = '<?= Yii::app()->createUrl('booking/summaryadditionalinfo') ?>';
        var bkgid = '<?= $model->bkg_id ?>';
        var hash = '<?= $hash ?>';
        var noPassenger = $('#BookingAddInfo_bkg_no_person').val();

        var noLargeBag = $('#BookingAddInfo_bkg_num_large_bag').val();
        var noSmallBag = $('#BookingAddInfo_bkg_num_small_bag').val();
        var fromPincode = $('#BookingRoute_brt_from_pincode').val();
        var toPincode = $('#BookingRoute_brt_to_pincode').val();
        var infosource = $('#BookingAddInfo_bkg_info_source').val();
        var tripType = $('input[name="BookingAddInfo[bkg_user_trip_type]"]:checked').val();

        var seniorCitizen = $('#BookingAddInfo_bkg_spl_req_senior_citizen_trvl').is(":checked");
        var kidsTravel = $('#BookingAddInfo_bkg_spl_req_kids_trvl').is(":checked");
        var womanTravel = $('#BookingAddInfo_bkg_spl_req_woman_trvl').is(":checked");
        var carrierReq = $('#BookingAddInfo_bkg_spl_req_carrier').is(":checked");
        var engSepeakingDriver = $('#BookingAddInfo_bkg_spl_req_driver_english_speaking').is(":checked");
        var othersInfo = $('#Booking_bkg_chk_others').is(":checked");
        var addTrip = $('#Booking_bkg_add_my_trip').is(":checked");
        var discountAmount = $('.discountAmount').html();
        var walletUsed = $('.walletUsed').html();
        var creditUsed = $('.creditUsed').html();
        if (othersInfo == true)
        {
            var splreq = $.trim($("#BookingAddInfo_bkg_spl_req_other").val());
        }
        if (addTrip == true)
        {
            var breakTime = $('#BookingAddInfo_bkg_spl_req_lunch_break_time').val();
            if (breakTime == 0)
            {
                $jsBookNow.showErrorMsg('Please select journey break time');
                return false;
            }
        }

        if (noPassenger <= 0) {
            $jsBookNow.showErrorMsg('Please Enter number of Passenger');
            return false;
        } else if (noLargeBag < 0) {
            $jsBookNow.showErrorMsg('Please Enter number of large bag you want to take');
            return false;
        } else if (noSmallBag < 0) {
            $jsBookNow.showErrorMsg('Please Enter number of small bag you want to take');
            return false;
        }

        if ((infosource == '5') || (infosource == '6')) {
            var infosourcedesc = $('#BookingAddInfo_bkg_info_source_desc').val();
        }
        if (seniorCitizen == true)
        {
            seniorCitizen = 1;
        } else {
            seniorCitizen = 0;
        }

        if (kidsTravel == true)
        {
            kidsTravel = 1;
        } else {
            kidsTravel = 0;
        }

        if (womanTravel == true)
        {
            womanTravel = 1;
        } else {
            womanTravel = 0;
        }

        if (carrierReq == true)
        {
            carrierReq = 1;
        } else {
            carrierReq = 0;
        }

        if (engSepeakingDriver == true)
        {
            engSepeakingDriver = 1;
        } else {
            engSepeakingDriver = 0;
        }
        if (creditUsed > 0)
        {
            discountAmount = 0;
        }
        jQuery.ajax({type: 'GET',
            url: href,
            data: {id: bkgid, hash: hash, BookingAddInfo: {bkg_spl_req_senior_citizen_trvl: seniorCitizen, bkg_spl_req_kids_trvl: kidsTravel,
                    bkg_spl_req_woman_trvl: womanTravel, bkg_spl_req_carrier: carrierReq, bkg_spl_req_driver_english_speaking: engSepeakingDriver,
                    bkg_spl_req_other: splreq, bkg_spl_req_lunch_break_time: breakTime,
                    bkg_user_trip_type: tripType, bkg_no_person: noPassenger, bkg_num_large_bag: noLargeBag, bkg_num_small_bag: noSmallBag,
                    bkg_info_source: infosource, bkg_info_source_desc: infosourcedesc}, BookingRoute: {"<?= $brtRoute->brt_id ?>": {brt_from_pincode: fromPincode, brt_to_pincode: toPincode}}, discountamount: discountAmount, walletused: walletUsed, creditUsed: creditUsed},
            success: function (data)
            {
                obj = jQuery.parseJSON(data);
                if (!obj.success) {
                    var msg = "";
                    if ((parseInt(noPerson) > parseInt(vhCapacity)) && parseInt(vhCapacity) != '')
                    {
                        msg += 'Your selected cab can accomodate ' + vhCapacity + ' passengers<br/>';
                    }

//                    if ((parseInt(smallBag) > parseInt(smallbagCapacity)) && parseInt(smallbagCapacity) != '')
//                    {
//                        msg += 'The selected cab can accomodate ' + smallbagCapacity + ' small bags<br/>';
//                    }
//                    if ((parseInt(bigBag) > parseInt(bigbagCapacity)) && parseInt(bigbagCapacity) != '')
//                    {
//                        msg += 'The selected cab can accomodate ' + bigbagCapacity + ' big bags<br/>';
//                    }
                    $jsBookNow.showErrorMsg(msg);
                    return false;
                } else
                {
                    //$('.payBoxMinAmount').text(obj.minPay);
                    $('.payBoxDueAmount').text(obj.dueAmount);
                    $('.payBoxTotalAmount').text(obj.totalAmount);
                    $('.etcAmount').html(obj.totalAmount);
                    $('.taxAmount').text(obj.servicetax).change();
                    var calTotAmt = obj.totalAmount - obj.walletAmount - obj.creditUsed;

                    $('.bkgamtdetails111').html(calTotAmt);
                    //var minAmount = Math.round((calTotAmt * 15) / 100);
<?php
if (in_array($model->bkg_booking_type, [9, 10, 11]))
{
	?>
	                    var minAmount = Math.round((calTotAmt * 50) / 100);
	<?php
}
else
{
	?>
	                    var minAmount = Math.round((calTotAmt * 30) / 100);
<?php } ?>
                    $('#BookingInvoice_partialPayment').attr('max', calTotAmt);
                    //$('.bkgamtdetails111').html(obj.totalAmount);
                    $('#BookingInvoice_partialPayment').attr('min', minAmount);
                    $('#BookingInvoice_partialPayment').val(minAmount);
                    //$('#BookingInvoice_partialPayment').attr('max', obj.dueAmount);
                    //$('#BookingInvoice_partialPayment').attr('min', obj.minPay);
                    $('#max_amount').val(obj.dueAmount).change();
                    if (obj.additionalAmount != '' && obj.additionalAmount != '0') {
                        $(".additionalcharge").removeClass("hide");
                        $('.extracharge').html('<span>&#x20b9</span>' + obj.additionalAmount).change();
                    }
                    //$('.additionalinfo').hide();
                    $("#request_status").val('1');
                    $('#additiondetails').hide();

                    $('#accordion-1').css('display', 'block');
                    $('html, body').animate({scrollTop: 0}, 'slow');
                    $(".additionalinfo").css("border", "0");
                    if ($(".payChk:checked").val() == 1) {
                        $("#BookingInvoice_partialPayment").val(obj.dueAmount);
                        $("#minpayval").html(calTotAmt);
                    } else {
                        $("#minpayval").html(calTotAmt);
                    }
                    $jsBookNow.showSuccessMsg("Special requests added successfully.");
                }


            }

        });
    });
    function extraAdditionalInfo(infosource)
    {
        $("#source_desc_show").addClass('hide');
        if (infosource == '5') {
            $("#source_desc_show").removeClass('hide');
            $("#agent_show").addClass('hide');
            $("#BookingAddInfo_bkg_info_source_desc").attr('placeholder', "Friend's email please");
        } else if (infosource == '6') {
            $("#source_desc_show").removeClass('hide');
            $("#agent_show").addClass('hide');
            $("#BookingAddInfo_bkg_info_source_desc").attr('placeholder', "");
        }
    }
    $('#<?= CHtml::activeId($model, "bkg_chk_others") ?>').change(function () {
        if ($('#<?= CHtml::activeId($model, "bkg_chk_others") ?>').is(':checked'))
        {
            $("#othreq").show();
        } else {
            $("#othreq").hide();
        }
    });
    $('#<?= CHtml::activeId($model, "bkg_add_my_trip") ?>').change(function () {
        if ($('#<?= CHtml::activeId($model, "bkg_add_my_trip") ?>').is(':checked'))
        {
            $("#addmytrip").show();
        } else {
            $("#addmytrip").hide();
        }
    });

    $('select[name="BookingAddInfo[bkg_spl_req_lunch_break_time]"]').change(function (event) {
        var journeyBreakTime = $(event.currentTarget).val();
        brakCharges = journeyBreakTime * 5;
        //alert(brakCharges);
        if (brakCharges != 0) {
            $(".heading-journeybreak").removeClass('hide');
            $("#journeybreak").html(journeyBreakTime + " minutes break during journey (Rs." + brakCharges + "/-).");
            //$('#additiondetails').removeClass("hide");
        } else {
            $(".heading-journeybreak").addClass('hide');
            $("#journeybreak").html('');
        }
    });

    $(window).ready(function () {
        $('#<?= CHtml::activeId($model, "bkg_add_my_trip") ?>').attr('disabled', true);
    });



    function luggage_info(largebag, vcatid, sccid, smallbag)
    {
        var largebag = largebag;
        var vcatid = vcatid;
        var sccid = sccid;
        var smallbag = smallbag;
        var sbag = Math.floor(smallbag - (largebag * 2));
        $("#BookingAddInfo_bkg_num_small_bag").empty();
        for (var i = 0; i <= sbag; i++)
        {
            var id = i;
            var name = i;
            $("#BookingAddInfo_bkg_num_small_bag").append("<option value='" + id + "'>" + name + "</option>");
        }
    }

	
</script>