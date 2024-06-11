<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/fontawesome-web/css/all.min.css?v0.6');
?>
<?php
	$infosource = BookingAddInfo::model()->getInfosource('user');
	$hash = Yii::app()->request->getParam('hash');
	$otherExist = ($model->bkgAddInfo->bkg_spl_req_other != '') ? 'block' : 'none';
	$model->bkg_chk_others = ($model->bkgAddInfo->bkg_spl_req_other != '') ? 1 : 0;
	$model->hash			 = Yii::app()->shortHash->hash($model->bkg_id);
	$version		 = Yii::app()->params['siteJSVersion'];
	Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/bookNow.min.js?v=' . $version);
	$response	 = Contact::referenceUserData($model->bkgUserInfo->bui_id, 3);
	if ($response->getStatus())
	{
		$contactNo	 = $response->getData()->phone['number'];
		$countryCode	 = $response->getData()->phone['ext'];
		$email		 = $response->getData()->email['email'];
	}
	$refcode			 = "";
	$whatappShareLink	 = "";
	if ($model->bkgUserInfo->bkg_user_id > 0)
	{
		$users				 = Users::model()->findByPk($model->bkgUserInfo->bkg_user_id);
		$refcode			 = $users->usr_refer_code;
		$whatappShareLink	 = users::model()->whatsappShareTemplate($refcode);
	}
?>
<div class="content-boxed-widget p0 top-0">
	<div class="content-boxed-widget2 p15">
			<div class="content p0 mb10" style="position: relative;">
					<div class="caption-widget-1">
						<a href="whatsapp://send?text=<?= $whatappShareLink ?>" target="_blank" class="mr5"><img src="/images/whatsapp1.svg" width="40" alt="" class="mr5"></a> <a href="<?= Yii::app()->createAbsoluteUrl('users/FbShareTemplate', ['refcode' => $refcode]); ?>" target="_blank"><img src="/images/facebook1.svg" width="40" alt="" class="mr5"></a>
					</div>
					<div class="overlay-widget">
						<img src="/images/reap_the_reword.png" class="responsive-image bottom-0" alt="img">
					</div>
            </div>
            <div class="text-center">
                <img src="/images/checked-success.png" alt="" width="32" class=" text-center" style="display: initial!important;"><br/>
                <span class="font-18 color-green3-dark"><b>Successfully booked!</b></span>
            </div>
            <p class="text-center color-gray font-12 bottom-15 line-height18">Booking Id: <span class="label-orange color-white"><?= Filter::formatBookingId($model->bkg_booking_id)?></span> <br> (<?=ucwords($model->getBookingType($model->bkg_booking_type, 'Trip'))?>)</p>
				<?php
				if ($errorMsg != '')
				{
					?>
				<p class="text-center bottom-0 line-height16"><strong><?php echo $errorMsg ?></strong></p>
					<?php
				}
				else
				{

					if ($payment)
					{
						if ($succ == 'success')
						{
							?> 
				<p class="text-center bottom-10 line-height16">Transaction was successful. Thank you for your order. Your Transaction Id :<b> <?= $transid ?></b></p>
							<?
						}
						elseif ($succ == '')
						{
							?> 
				<p class="text-center bottom-10 line-height16"><b>Oh snap!</b> Something went wrong.</p>
							<?
						}
						else
						{
							?>
				<p class="text-center bottom-10 line-height16"><b>Oh snap!</b> Something went wrong. Transaction was not successful.</p>
							<?
						}
					}
				}
				if ($model->bkg_status == 2 && $showAdditional && $succ == 'success')
				{
				?>
				<p class="text-center bottom-10 line-height16">You will receive the cab details 3 hours before your scheduled pickup time.</p>
				<?php 
				}
				?>
	</div>
</div>
<div class="content p0 accordion-path bottom-0">
	<div class="accordion accordion-style-0 content-boxed-widget p0">
		<div class="accordion-border">
			<a href="javascript:void(0)" class="font18" data-accordion="accordion-1">TRAVELLER INFORMATION<i class="fa fa-plus"></i></a>
			<div class="accordion-content" id="accordion-1" style="display: block;">
				<div class="accordion-text line-height16">
					<div class="content p0 bottom-10">
						<span class="color-orange font-11">Passenger Name:</span><br>
						<?= $model->bkgUserInfo->bkg_user_fname . ' ' . $model->bkgUserInfo->bkg_user_lname ?>
					</div>
					<div class="content p0 bottom-10">
						<span class="color-orange font-11">Email:</span><br>
						<?= $email ?><?= ($model->bkgUserInfo->bkg_email_verified == 1) ? " <i class='fa fa-check btn-success ' title='verified'></i>" : " <i class='fa fa-remove btn-danger' title='not verified'></i>" ?>
					</div>
					<?php 
						if ($contactNo != '')
						{
					?>
					<div class="content p0 bottom-10">
						<span class="color-orange font-11">Primary Phone:</span><br>
						+<?= $countryCode ?><?= $contactNo ?><?= ($model->bkgUserInfo->bkg_phone_verified == 1) ? " <i class='fa fa-check btn-success' title='verified'></i>" : " <i class='fa fa-remove btn-danger' title='not verified'></i>" ?>
					</div>
					<?php 
						}
						if ($model->bkgUserInfo->bkg_alt_contact_no != '')
						{
					?>
					<div class="content p0 bottom-10">
						<span class="color-orange font-11">Alternate Phone:</span><br>
						+<?= $model->bkgUserInfo->bkg_alt_country_code ?><?= $model->bkgUserInfo->bkg_alt_contact_no ?>
					</div>
					<?php 
						}
					?>
					<div class="content p0 bottom-10">
						<span class="color-orange font-11">Cab Type</span><br>
						<?= $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label . ' ' . $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_desc ?>
					</div>
					<div class="content p0 bottom-10">
						<span class="color-orange font-11">Trip Type</span><br>
						<?= ucwords($model->getBookingType($model->bkg_booking_type, 'Trip')) ?>
					</div>
					<div class="content p0 bottom-10">
						<span class="color-orange font-11">Pickup</span><br>
						<?= date('jS M Y (l)', strtotime($model->bkg_pickup_date)) ?> 
					</div>
					<div class="content p0 bottom-10">
						<span class="color-orange font-11">Time</span><br>
						<?= date('h:i A', strtotime($model->bkg_pickup_date)) ?>
					</div>
					<div class="content p0 bottom-10">
						<span class="color-orange font-11">Status</span><br>
						<b class="uppercase color-green3-dark"><?php echo $model->getActiveBookingStatus($model->bkg_status) ?></b>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php $this->renderPartial("bkSummaryTripPlan", ["model" => $model], false, false); ?>

<div class="content p0 accordion-path bottom-0">
	<div class="accordion accordion-style-0 content-boxed-widget p0">
		<div class="accordion-border">
			<a href="javascript:void(0)" class="font18" data-accordion="accordion-2">BILLING DETAILS<i class="fa fa-plus"></i></a>
			<div class="accordion-content" id="accordion-2" style="display: none;">
				<div class="accordion-text">
					<div class="content p0 bottom-0">
						<div class="one-half">Base Fare:</div>
						<div class="one-half last-column text-right">&#x20B9;<?= $model->bkgInvoice->bkg_base_amount; ?></div>
						<div class="clear"></div>
					</div>
					<?php
						if ($model->bkgInvoice->bkg_discount_amount > 0)
						{
					?>
					<div class="content p0 bottom-0">
						<div class="one-half">Discount:</div>
						<div class="one-half last-column text-right red-text-color">&#x20B9;<?= $model->bkgInvoice->bkg_discount_amount; ?></div>
						<div class="clear"></div>
					</div>
					<?php } 
						if($model->bkgInvoice->bkg_addon_charges > 0){
					?>
					<div class="content p0 bottom-0">
						<div class="one-half">Add On Charge:</div>
						<div class="one-half last-column text-right">&#x20B9;<?= $model->bkgInvoice->bkg_addon_charges; ?></div>
						<div class="clear"></div>
					</div>
					<?php	}
					if ($model->bkgInvoice->bkg_additional_charge > 0 || $model->bkgInvoice->bkg_driver_allowance_amount > 0)
						{
					?>
					<div class="content p0 bottom-0">
						<div class="one-half">Additional Charge:</div>
						<div class="one-half last-column text-right">&#x20B9;<?= $model->bkgInvoice->bkg_additional_charge + $model->bkgInvoice->bkg_driver_allowance_amount; ?></div>
						<div class="clear"></div>
					</div>
					<?php 
						}
						if ($model->bkgInvoice->bkg_convenience_charge > 0)
						{
					?>
					<div class="content p0 bottom-0">
						<div class="one-half">Collect on delivery (COD) fee:</div>
						<div class="one-half last-column text-right">&#x20B9;<?= $model->bkgInvoice->bkg_convenience_charge ?></div>
						<div class="clear"></div>
					</div>
					<?php } ?>
					<div class="content p0 bottom-0">
						<div class="one-half">State Tax:</div>
						<div class="one-half last-column text-right">&#x20B9;<?= $model->bkgInvoice->bkg_state_tax; ?></div>
						<div class="clear"></div>
					</div>
					<div class="content p0 bottom-0">
						<div class="one-half">Toll Tax:</div>
						<div class="one-half last-column text-right">&#x20B9;<?= $model->bkgInvoice->bkg_toll_tax; ?></div>
						<div class="clear"></div>
					</div>
                    <div class="content p0 bottom-0">
						<div class="one-half">Airport Entry Fee:</div>
						<div class="one-half last-column text-right">&#x20B9;<?php echo $model->bkgInvoice->bkg_airport_entry_fee; ?></div>
						<div class="clear"></div>
					</div>
					<?php 
						//$staxrate = $model->bkgInvoice->getServiceTaxRate();
						$serviceTaxRate = BookingInvoice::getGstTaxRate($model->bkg_agent_id, $model->bkg_booking_type);
						$staxrate    = ($serviceTaxRate == 0)? 1 : $serviceTaxRate;
						$taxLabel = ($serviceTaxRate == 5) ? 'GST' : 'Service Tax ';
						if ($model->bkgInvoice->bkg_sgst > 0)
						{
					?>
					<div class="content p0 bottom-0">
						<div class="one-half">SGST (@<?= Yii::app()->params['sgst'] ?>%):</div>
						<div class="one-half last-column text-right">&#x20B9;<?= ((Yii::app()->params['sgst'] / $staxrate) * $model->bkgInvoice->bkg_service_tax)|0; ?></div>
						<div class="clear"></div>
					</div>
					<?php 
						}
						if ($model->bkgInvoice->bkg_cgst > 0)
						{
					?>
					<div class="content p0 bottom-0">
						<div class="one-half">CGST (@<?= Yii::app()->params['cgst'] ?>%):</div>
						<div class="one-half last-column text-right">&#x20B9;<?= ((Yii::app()->params['cgst'] / $staxrate) * $model->bkgInvoice->bkg_service_tax)|0; ?></div>
						<div class="clear"></div>
					</div>
					<?php 
						}
						if ($model->bkgInvoice->bkg_igst > 0)
						{
					?>
					<div class="content p0 bottom-0">
						<div class="one-half">IGST (@<?= Yii::app()->params['igst'] ?>%):</div>
						<div class="one-half last-column text-right">&#x20B9;<?= ((Yii::app()->params['igst'] / $staxrate) * $model->bkgInvoice->bkg_service_tax)|0; ?></div>
						<div class="clear"></div>
					</div>
					<?php
						}
						if ($serviceTaxRate != 5)
						{
					?>
					<div class="content p0 bottom-0">
						<div class="one-half"><?= $taxLabel ?>:</div>
						<div class="one-half last-column text-right">&#x20B9;<?= $model->bkgInvoice->bkg_service_tax; ?></div>
						<div class="clear"></div>
					</div>
					<?php 
						}
					?>
					<div class="content p0 bottom-0">
						<div class="one-half">Total Fare:</div>
						<div class="one-half last-column text-right">&#x20B9;<?= $model->bkgInvoice->bkg_total_amount; ?></div>
						<div class="clear"></div>
					</div>
					<?php
						if ($model->bkgInvoice->bkg_advance_amount > 0 || $model->bkgInvoice->bkg_credits_used > 0)
						{
							if ($model->bkgInvoice->bkg_advance_amount > 0)
							{
					?>
					<div class="content p0 bottom-0">
						<div class="one-half">Advance paid:</div>
						<div class="one-half last-column text-right">&#x20B9;<?= round($model->bkgInvoice->bkg_advance_amount) ?></div>
						<div class="clear"></div>
					</div>
					<?php 
							}
							if ($model->bkgInvoice->bkg_credits_used > 0)
							{
					?>
					<div class="content p0 bottom-0">
						<div class="one-half">Credits used:</div>
						<div class="one-half last-column text-right">&#x20B9;<?= $model->bkgInvoice->bkg_credits_used ?></div>
						<div class="clear"></div>
					</div>
					<?php 
							}
					?>
                                        <div class="border-gray-bottom mt10 mb10"></div>
					<div class="content pl0 pr0 bottom-0">
						<div class="one-half font-16"><b>Amount Due:</b></div>
						<div class="one-half last-column text-right font-16">&#x20B9;<b>
							<?
								if ($model->bkgInvoice->bkg_due_amount > 0)
								{
									echo round($model->bkgInvoice->bkg_due_amount);
								}
								else
								{
									echo '0';
								}
							?></b></div>
						<div class="clear"></div>
					</div>
				    <?php 
						}
					?>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
$form = $this->beginWidget('CActiveForm', array(
	'id'					 => 'booking-summaryform', 'enableClientValidation' => true,
	'clientOptions'			 => array(
		'validateOnSubmit'	 => true,
		'errorCssClass'		 => 'has-error',
		'afterValidate'		 => 'js:function(form,data,hasError){
						   if(!hasError){
                    $.ajax({
                        "type":"POST",
                        "dataType":"json",
                        "url":"' . CHtml::normalizeUrl(Yii::app()->request->url) . '",
							"data":form.serialize(),
							"success":function(data1){							
								if(data1.success){
								location.href="' . CHtml::normalizeUrl(Yii::app()->createUrl('booking/summary')) . '?id="+data1.id+"&hash="+data1.hash;
								}
								else{								
								settings=form.data(\'settings\');
								data2 = data1.error;
								$.each (settings.attributes, function (i) {
								$.fn.yiiactiveform.updateInput (settings.attributes[i], data2, form);
								});
								$.fn.yiiactiveform.updateSummary(form, data2);
								}                         
							},
                            });
                        }
                    }'
	),
	'errorMessageCssClass'	 => 'help-block',
	'htmlOptions'			 => array(
		'class'		 => '', 'enctype'	 => 'multipart/form-data'
	),
		));
/* @var $form CActiveForm */
if ($model->bkg_status == 2 && $model->bookingRoutes[0]->brt_from_pincode == '' && $showAdditional)
{					
?>

<?= $form->hiddenField($model, 'bkg_id', ['id' => 'bkg_id4']); ?>
<?= $form->hiddenField($model, 'hash', ['id' => 'hash4', 'class' => 'clsHash', 'value' => $hash]); ?>

<div class="content p0 accordion-path bottom-0">
	<div class="accordion accordion-style-0 content-boxed-widget p0">
		<div class="accordion-border">
			<a href="javascript:void(0)" class="font18 uppercase" data-accordion="accordion-3">Special Requests<i class="fa fa-plus"></i></a>
			<div class="accordion-content" id="accordion-3" style="display: none;">
				<div class="accordion-text" style="pointer-events: none;">
					<div class="content p0 bottom-10 uppercase">Additional information .</div>
					
						<div class="content p0 bottom-10">
							<?= $form->checkBox($model->bkgAddInfo, 'bkg_spl_req_senior_citizen_trvl',[],array("disabled" => "disabled")) ?>
							<?= 'Senior citizen traveling' ?>
						</div>
						<div class="content p0 bottom-10">
							<?= $form->checkBox($model->bkgAddInfo, 'bkg_spl_req_kids_trvl', [],array("disabled" => "disabled")) ?>
							<?= 'Kids on board' ?>
						</div>
						<div class="content p0 bottom-10">
							<?= $form->checkBox($model->bkgAddInfo, 'bkg_spl_req_woman_trvl', [],array("disabled" => "disabled")) ?>
							<?= 'Women traveling' ?>
						</div>
						<?php 
							$scvVctId = SvcClassVhcCat::model()->getCatIdBySvcid($model->bkg_vehicle_type_id); 
							if ($scvVctId != VehicleCategory::SEDAN_ECONOMIC)
							{
						?>
						<div class="content p0 bottom-10">
							
								<?php
										echo $form->checkBox($model->bkgAddInfo, 'bkg_spl_req_carrier', []);
										echo 'Carrier required';
								?>
							
						</div>
						<?php 
							}
						?>
						<div class="content p0 bottom-10">
							<?= $form->checkBox($model->bkgAddInfo, 'bkg_spl_req_driver_english_speaking', []) ?>
							<?= 'English-speaking driver required' ?>
						</div>
						<div class="content p0 bottom-10">
							<?= $form->checkBox($model, 'bkg_chk_others', []) ?>
							<?= 'Others' ?>
						</div>
						<div class="content p0 bottom-10" id="othreq" style="display: <?= $otherExist ?>">
								<?= $form->textArea($model->bkgAddInfo, 'bkg_spl_req_other', ['placeholder' => "Other Requests"]) ?>
						</div>
						<div class="content p0 bottom-10">
							<?= $form->checkBox($model, 'bkg_add_my_trip', []) ?>
							<?=  'Journey break (₹150/30mins). First 15min free.' ?>
						</div>
						<div class="content p0 bottom-10" id="addmytrip" style="display: <?= $otherExist ?>">
							
								<?= $form->dropDownList($model->bkgAddInfo, 'bkg_spl_req_lunch_break_time',['0' => 'Minutes', '30' => '30 Minutes', '60' => '60 Minutes', '90' => '90 Minutes', '120' => '120 Minutes', '150' => '150 Minutes', '180' => '180 Minutes']) ?>
							
						</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="content p0 accordion-path bottom-0">
	<div class="accordion accordion-style-0 content-boxed-widget p0" >
		<div class="accordion-border">
			<a href="javascript:void(0)" class="font18 uppercase" data-accordion="accordion-4">Additional Information<i class="fa fa-plus"></i></a>
			<div class="accordion-content" id="accordion-4" style="display: none; pointer-events: none;">
				<div class="accordion-text">
					<div class="content p0 bottom-10">
						<div class="bottom-30"><em>Personal Or Business Trip?</em>
								
									<?=
										$form->radioButtonList($model->bkgAddInfo, 'bkg_user_trip_type',Booking::model()->userTripList,['style' => 'display:block;']);
									?>
						</div>
						</div>
					</div>
					<?php
						$readOnly = [];
						if (in_array($model->bkg_flexxi_type, [1,2]))
						{
							$readOnly = ['readOnly' => 'readOnly'];
						}
					?>
					<div class="content p0 bottom-10">
						<div class="input-simple-1 has-icon input-blue bottom-30"><em>Number of Passengers</em>
							<?= $form->numberField($model->bkgAddInfo, 'bkg_no_person', ['placeholder' => "Number of Passengers", 'min' => 1, 'max' => $bdata['vht_capacity']] + $readOnly) ?>  
						</div>
					</div>
					<div class="content p0 bottom-10">
						<div class="input-simple-1 has-icon input-blue bottom-30"><em><?= $model->bkgAddInfo->getAttributeLabel('bkg_num_large_bag') ?></em>
							<?= $form->numberField($model->bkgAddInfo, 'bkg_num_large_bag', ['placeholder' => "Number of large suitcases", 'min' => 0, 'max' => $bdata['vht_big_bag_capacity']] + $readOnly) ?>
						</div>
					</div>
					<div class="content p0 bottom-10">
						<div class="input-simple-1 has-icon input-blue bottom-30"><em><?= $model->bkgAddInfo->getAttributeLabel('bkg_num_small_bag') ?></em>
							<?= $form->numberField($model->bkgAddInfo, 'bkg_num_small_bag', ['placeholder' => "Number of small bags", 'min' => 0, 'max' => $bdata['vht_bag_capacity']] + $readOnly) ?>
						</div>
					</div>
					<div class="content p0 bottom-10">
						<div class="select-box select-box-1 mt40">
							<em>How did you hear about Gozo cabs?</em>
							<?php
							$infosource = ['' => 'Select Infosource'] + $infosource;
                            echo $form->dropDownList($model->bkgAddInfo,"bkg_info_source",$infosource,['class'=> 'form-control','style' => 'width:100%;margin-bottom:10px','placeholder' => 'Select Infosource']);
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php } ?>

<div class="content p0 accordion-path">
	<div class="accordion accordion-style-0 content-boxed-widget p0">
		<div class="accordion-border">
			<a href="javascript:void(0)" class="font18 uppercase" data-accordion="accordion-6">Important info<i class="fa fa-plus"></i></a>
			<div class="accordion-content" id="accordion-6" style="display: none;">
				<div class="accordion-text">
					<div class="content p0 bottom-10">
						<ul>
							<li>Any changes to itinerary needs to be documented into the booking. Addition of pickup/drop points or way points or sightseeing may cause change in fare.</li>
							<li>You may cancel your Booking with us by logging in to our Mobile App or Website. All bookings cancelled less than 24 hours before the scheduled pickup time shall be subject to Cancellation & Refund Policy as laid down in our 
								<a href="http://www.aaocab.com/terms" target="_blank" class="link-two display-inline color-highlight">Terms & Conditions</a> page on our website.</li>
							<li>For additional luggage, request a cab with carrier (availability not guaranted )</li>
							<li>All parking charges are to be brone by the customer</li>
							<li>Toll, state taxes extra unless explicitly stated as included on itinerary.</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!--<div class="content p0 bottom-10 text-center mb30">
	<input type="submit" value="SUBMIT" class="uppercase btn-orange">
</div>-->
<?php $this->endWidget(); ?>

<div class="content p0 bottom-10 hide">
<a href="javascript:void(0);" data-menu="validation-list-modal" id="validationModal"><i class="far fa-envelope"></i></a>
</div>
<div id="validation-list-modal" data-selected="menu-components" data-width="300" data-height="380" class="menu-box menu-modal">
    <div class="menu-title"><a href="#" class="menu-hide pt0" id="menubox"><i class="fa fa-times"></i></a>
        <h1>Verification</h1>
    </div>
    <div class="menu-page p5">
		
		<div class="input-simple-1 has-icon input-blue bottom-10" style="text-align: center;">
			<span>Please enter the verification code you received on</span><br/>
			 <? if ($contactNo != '') { ?>Phone: +<?= $countryCode ?><?= $contactNo ?>
                <br>OR <br>
            <? } ?>
            Email: <?= ($email != "") ? $email : "" ?>
		</div>
		
        <div id="errorshow" class="input-simple-1 has-icon input-blue" style="display: none;text-align: center;">
            <span class="alert alert-block alert-danger error color-red-dark" id="moberrordiv">verification code did not match!</span>
        </div>
			<?php
                $form2 = $this->beginWidget('CActiveForm', array(
                    'id' => 'verify-form', 'enableClientValidation' => true,
                    'clientOptions' => array(
                        'validateOnSubmit' => true,
                        'errorCssClass' => 'has-error',
                        'afterValidate' => 'js:function(form,data,hasError){
                                  if(!hasError){
                                                $.ajax({
						"type":"POST",
						"dataType":"json",
						"url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('booking/confirmmobile')) . '",
						"data":form.serialize(),
						"success":function(data2){   
                                                        if(data2.success){
                                                                if(data2.manual=="manual"){
                                                                   location.reload();
                                                                }else{
                                                                  // openFinalBooking(data2.bkg_id,data2.hash);
                                                                  closeModal();
                                                                }
                                                        }else{
                                                                $("#errorshow").show();
                                                                $("#moberrordiv").html("Verification code did not match! Booking cannot be verified");

                                                        }

                                                },
                                                error: function (xhr, ajaxOptions, thrownError) 
                                                {
                                                                alert(xhr.status);
                                                                alert(thrownError);
                                                }
                                          });
                             }
                    }'
                    ),
                    'enableAjaxValidation' => false,
                    'errorMessageCssClass' => 'help-block',
                    'htmlOptions' => array(
                        'class' => 'form-inline',
                    ),
                ));
                /* @var $form CActiveForm */
                ?> 
                <?php echo CHtml::errorSummary($model); ?>
                <?= $form2->hiddenField($model->bkgUserInfo, 'bui_bkg_id') ?>
                <?= $form2->hiddenField($model->bkgUserInfo, 'hash',['value' => $model->hash]) ?>
                <input type="hidden" name="manual" value="<?= $manual ?>">
                <input type="hidden" name="ctype" value="<?=$ctype?>">
				<div class="content top-20">
					<div class="input-simple-1 has-icon input-blue bottom-10">
						<div class="from-right">
							<?= $form2->textField($model->bkgUserInfo, 'bkg_verification_code1', ['required' => TRUE, 'class' => 'form-control']) ?>
						</div>
						<div class="from-left text-center pt10">
							<button type="submit" class="btn-submit-orange">Apply</button>
						</div>
						<div class="clear"></div>
					</div>
				</div>
			<?php $this->endWidget(); ?>
				
				<div class="content text-center bottom-10">
					<button class="btn-submit-green" id="resendcode" type="button" onclick="resendCode(<?= $model->bkg_id ?>, '<?= $model->hash ?>');">Resend</button>			
					<button class="uppercase ultrabold button shadow-medium button-xs button button-blue pl5 pr5" id="confirmlater" style="display:none;" type="button">I will confirm later</button>

				</div>
		<div class="content bottom-10">
			<span class="alert line-height16">Did not receive the verification code? Wait a minute or you could call us on +91 90518-77-000 and we will manually verify the booking for you.</span>
		</div>
	</div>
</div>
<script>
	var booknow = new BookNow();
	
	$(document).ready(function(){
		<?php
		if ($model->bkgUserInfo->bkg_phone_verified != 1 && $model->bkgUserInfo->bkg_email_verified != 1 && $model->bkg_agent_id == '')
		{
		?>
				setTimeout(function(){$('#validationModal').click(); }, 3);
					
		<?php } ?>
	});
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
	
	$('#confirmlater').click(function(){
		closeModal();
	});
	
	function resendCode(bkgId, hash) {
        var href1 = '<?= Yii::app()->createUrl('booking/confirmmobile') ?>';
        jQuery.ajax({'type': 'GET', 'url': href1, 'dataType': 'json',
            'data': {'bid': bkgId, 'hsh': hash, 'resend': 'resend'},
            success: function (data) {
                if (!data.success) {
                    $('#resendcode').attr('disabled', 'disabled');
                    $('#confirmlater').show();
                } else {
                    booknow.showErrorMsg("code resend successfully.");
                }
            }
        });
    }
	
	function closeModal()
	{
		$('#validation-list-modal').removeClass('menu-box-active');
        $('#menu-hider').removeClass('menu-hider-active');
	}
</script>