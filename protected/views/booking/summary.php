<style type="text/css">
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; 
        margin: 0; 
    }
    input[type="number"] {
        -moz-appearance: textfield;
    }
</style>
<?php
/* @var $model Booking */
$fcity					 = Cities::getName($model->bkg_from_city_id);
$tcity					 = Cities::getName($model->bkg_to_city_id);
//$infosource = Booking::model()->geactiontInfosource('user');
$infosource				 = BookingAddInfo::model()->getInfosource('user');
$routeCityList			 = $model->getTripCitiesListbyId();
$ct						 = implode(' &#10147 ', $routeCityList);
$action					 = Yii::app()->request->getParam('action');
$hash					 = Yii::app()->request->getParam('hash');
$otherExist				 = ($model->bkgAddInfo->bkg_spl_req_other != '') ? 'block' : 'none';
$model->bkg_chk_others	 = ($model->bkgAddInfo->bkg_spl_req_other != '') ? 1 : 0;
//echo $abc = ($showAdditional) ? 'True' : 'False';
$model->hash			 = Yii::app()->shortHash->hash($model->bkg_id);
$date					 = date_create($model->bkg_pickup_date);
$url					 = "https://" . $_SERVER['HTTP_HOST'] . "/bknw/$model->bkg_id/$model->hash";
$urlre1					 = "https://" . $_SERVER['HTTP_HOST'] . "/just1";
$text					 = 'I am going from ' . $fcity . ' to ' . $tcity . ' on ' . date_format($date, 'd/m/Y') . ' ' . date_format($date, 'g:i A') . ' and have a few empty seats in my taxi. Share the taxi with me and book your seat directly on ' . $url;
$textre1				 = 'I am going from ' . $fcity . ' to ' . $tcity . ' on ' . date_format($date, 'd/m/Y') . ' ' . date_format($date, 'g:i A') . ' for just ₹1/- . Share the taxi with me and book your seat on .  ' . $urlre1;
$scvVctId				 = SvcClassVhcCat::model()->getCatIdBySvcid($model->bkg_vehicle_type_id); 
$response = Contact::referenceUserData($model->bkgUserInfo->bui_id, 2);
if ($response->getStatus())
{
	$contactNo	 = $response->getData()->phone['number'];
	$countryCode	 = $response->getData()->phone['ext'];
	$userName		 = $response->getData()->phone['userName'];
	$fname			 = $response->getData()->phone['firstName'];
	$lname			 = $response->getData()->phone['lastName'];
}
?>
<?php
if ($platform != 3)
{
	?>
	<div class="row mb10 mt15">
		<?
	}
	else
	{
		?>
		<div class="mb10 mt15">
			<?
		}
		?>
		<div class="col-xs-12">
			<?
			if ($errorMsg != '')
			{
				?>
				<div role="alert" class="alert alert-danger"> 
					<strong><?php echo $errorMsg ?></strong>
				</div>
				<?php
			}
			else
			{


				if ($payment)
				{
					if ($succ == 'success')
					{
						?> 
						<div role="alert" class="alert alert-success"> 
							<strong>Transaction was successful. Thank you for your order. Your Transaction Id : <?= $transid ?></strong>
						</div>
						<?
					}
					elseif ($succ == '')
					{
						?>
						<div role="alert" class="alert alert-danger"> 
							<strong>Oh snap!</strong> Something went wrong.  
						</div>
						<?
					}
					else
					{
						?>
						<div role="alert" class="alert alert-danger"> 
							<strong>Oh snap!</strong> Something went wrong. Transaction was not successful. 
						</div>
						<?
					}
				}
				else
				{
					if ($model->bkgInvoice->bkg_advance_amount > 0)
					{
						echo '<h3 class="mb10 text-uppercase">BOOKING Confirmed</h3>';
					}
					else
					{
						echo '<h3 class="mb10 mt0 text-uppercase">BOOKING Created</h3>';
					}
					?>
					<?php
				}
			}





			if ($model->bkg_status == 2 && $showAdditional && $succ == 'success')
			{
				?>
				<p class="weight400"> Booking Successful! You will receive the cab details 3 hours before your scheduled pickup time.
				</p>
			<? } ?>
			<p class="weight400"> 
				Please review the details of your booking request.
			</p>
		</div>

		<?
		if ($model->bkg_flexxi_type == 1)
		{
			?>
			<div class="col-xs-12 text-right">
				Share Your cab with your friends with :<br>
				<a href="https://api.whatsapp.com/send?phone=<?= $countryCode . $contactNo ?>&text=<?= $text ?>" target="_blank"><img src="/images/whatsapp-share.png" alt="Share on Whatsapp"></a>
			</div>
			<div class="col-xs-6 hidden">
				<a href="<?= Yii::app()->createAbsoluteUrl('users/fbShareLink', ['id' => $model->bkg_id, 'hash' => Yii::app()->shortHash->hash($model->bkg_id), 'text' => $text]); ?>" target="_blank" class="social-1 hvr-push" style="color: #fff" rel="nofollow">
					<button class="btn btn-lg" type="button" style="color: #fff;background: #3b5a9b">
						<i class="fa fa-facebook mr15"></i>SHARE
					</button>
				</a>
			</div>
		<? } ?>
		<?
		if ($model->bkg_flexxi_type == 2 && $model->bkgInvoice->bkg_promo1_code == 'FLATRE1')
		{
			?>
			<div class="col-xs-12 text-right">
				<b>Share with your friends with :</b><br>
				<a href="https://api.whatsapp.com/send?phone=<?= $countryCode . $contactNo ?>&text=<?= $textre1 ?>" target="_blank"><img src="/images/whatsapp-share.png" alt="Share on Whatsapp"></a>
			</div>
		<? } ?>
		<div class="col-md-4 col-xs-12 pb30 title black-color">Booking ID: <span class="green-color"><?= Filter::formatBookingId($model->bkg_booking_id); ?> - ( <?= ucwords($model->getBookingType($model->bkg_booking_type, 'Trip')) ?>)</span></div>
		<div class="col-md-4 col-sm-6 pb30 title black-color">Route: <span class="routes_box"><?= $model->bkgFromCity->cty_name . '&#10147;' . $model->bkgToCity->cty_name ?></span></div>
		<div class="col-md-4 col-sm-6 pb30 title black-color">Status: <span class="green-color"><?= Booking::model()->getActiveBookingStatus($model->bkg_status) ?></span></div>


		<div class="col-xs-12 col-sm-7 col-md-7 book-panel">

			<div class="row">
				<div class="col-xs-12 mb20">
					<div class="heading-part mb10">Traveller Information </div>
					<div class="main_time border-blueline">
						<div class="row">
							<div class="col-xs-12 col-sm-6">
								<div class="col-xs-6">Passenger Name: </div> <div class="col-xs-6"><b><span class="black-color"><?= $fname . ' ' . $lname ?></span></b></div>
								<?
								if ($model->bkgUserInfo->bkg_user_email != '')
								{
								    $response = Contact::referenceUserData($model->bkgUserInfo->bui_id, 1);
									if ($response->getStatus())
									{
										$email	 = $response->getData()->email['email'];
									}
									?>
									<div class="col-xs-6">Email:</div><div class="col-xs-6"> <span class="black-color"><?= $email ?><?= ($model->bkgUserInfo->bkg_email_verified == 1) ? " <i class='fa fa-check btn-success ' title='verified'></i>" : " <i class='fa fa-remove btn-danger' title='not verified'></i>" ?></span></div>
								<? } ?>
								<?
								if ($contactNo != '')
								{
									?>
									<div class="col-xs-6">Primary Phone:</div> <div class="col-xs-6"><span class="black-color">+<?= $countryCode ?><?= $contactNo ?><?= ($model->bkgUserInfo->bkg_phone_verified == 1) ? " <i class='fa fa-check btn-success' title='verified'></i>" : " <i class='fa fa-remove btn-danger' title='not verified'></i>" ?></span></div>
								<? } ?>
								<?
								if ($model->bkgUserInfo->bkg_alt_contact_no != '')
								{
									?>
									<div class="col-xs-4">Alternate Phone: </div> <div class="col-xs-8"><span class="black-color">+<?= $model->bkgUserInfo->bkg_alt_country_code ?><?= $model->bkgUserInfo->bkg_alt_contact_no ?></span></div>

								<? } ?>
							</div>
							<div class="col-xs-12 col-sm-6">
								<div class="col-xs-4">Cab Type:</div><div class="col-xs-8"> <span class="black-color"><?= $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label. ' (' . $model->bkgSvcClassVhcCat->scc_ServiceClass->scc_label.')-'.$model->bkgVehicleType->vht_make.' '.$model->bkgVehicleType->vht_model?></span></div>
								<div class="col-xs-4">Trip Type:</div><div class="col-xs-8"> <span class="black-color"><?= ucwords($model->getBookingType($model->bkg_booking_type, 'Trip')) ?></span></div>
								<div class="col-xs-4">Pickup Time:</div><div class="col-xs-8"> <span class="black-color"><?= date('jS M Y (l)', strtotime($model->bkg_pickup_date)) ?> <?= date('h:i A', strtotime($model->bkg_pickup_date)) ?></span></div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xs-12 mb20">
					<div class="heading-part mb10">Your Trip Plan</div>
					<div class="main_time border-blueline p5">
						<div class="container-time pb15">
							<?
							$last	 = 0;
							$tdays	 = '';
							$cntBrt	 = count($model->bookingRoutes);
							foreach ($model->bookingRoutes as $k => $brt)
							{
								if(in_array($model->bkg_booking_type,[9,10,11])){
                                      $brt->brt_trip_distance = $model->bkg_trip_distance;
                                      $brt->brt_trip_duration = $model->bkg_trip_duration;
								}
								if ($k == 0)
								{
									$datediff1 = 0;
								}
								else
								{
									$datediff1 = strtotime($model->bookingRoutes[$k]->brt_pickup_datetime) - strtotime($model->bookingRoutes[$k - 1]->brt_pickup_datetime);
								}
								$tdays	 = floor(($datediff1 / 3600) / 24) + 1;
								$last	 = $k;
								?>

								<ul>
									<li><span></span>
										<div>
											<div class="title black-color"><?= ($brt->brt_from_location == '') ? $brt->brtFromCity->cty_name : $brt->brt_from_location ?>
												<span class="pull-right pr30">Day <?= $tdays ?></span></div>
											<div class="info hide"><?= ($brt->brt_from_location == '') ? $brt->brtFromCity->cty_name : $brt->brt_from_location ?></div>
											<div class="type"><?= ($brt->brt_trip_distance < $model1->bkg_trip_distance && $model1->bkg_booking_type == 1) ? $model1->bkg_trip_distance : $brt->brt_trip_distance ?><br>km</div>
										</div> <span class="number"><span class="black-color">
												<?= DateTimeFormat::DateTimeToDatePicker($brt->brt_pickup_datetime); ?><br><b class="gray-color bold-none">
													<?= DateTimeFormat::DateTimeToTimePicker($brt->brt_pickup_datetime); ?></b></span> 
											<span class="timeing-box"><?= round($brt->brt_trip_duration / 60) . ' hours'; ?></span></span>
									</li>
									<?
									if ($k == ($cntBrt - 1))
									{
										?>
										<?
										$expArrivedate = date('Y-m-d H:i:s', strtotime($model->bookingRoutes[$cntBrt - 1]->brt_pickup_datetime . ' + ' . $model->bookingRoutes[$cntBrt - 1]->brt_trip_duration . ' MINUTE'));
										?>
										<li>
											<div><span></span>
												<div class="title black-color"><?= ($brt->brt_to_location == '' ) ? $brt->brtToCity->cty_name : $brt->brt_to_location ?>
													<span class="pull-right pr30">Day <?= $tdays ?></span></div>
												<div class="info hide"><?= ($brt->brt_to_location == '' ) ? $brt->brtToCity->cty_name : $brt->brt_to_location ?></div>
												<div class="type hide"><?= ($brt->brt_trip_distance < $model1->bkg_trip_distance && $model1->bkg_booking_type == 1) ? $model1->bkg_trip_distance : $brt->brt_trip_distance ?><br>km</div>
											</div> <span class="number "><span class="black-color "><?= DateTimeFormat::DateTimeToDatePicker($expArrivedate); ?><br><b class="gray-color bold-none"><?= DateTimeFormat::DateTimeToTimePicker($expArrivedate); ?></b></span> 
												<span class="timeing-box hide"><?= round($brt->brt_trip_duration / 60) . ' hours'; ?></span></span>
										</li>
									<? } ?>

								</ul>

							<? } ?>
						</div>
					</div>
				</div>
				<?
				/* $addinfo = $model->getFullInstructions();
				  if ($addinfo)
				  {
				  ?>

				  <div class="col-xs-12 mb20">
				  <div class="heading-part mb10"></div>
				  <div class="main_time border-blueline  ">
				  <span class="black-color"><?= $addinfo ?></span>
				  </div>
				  </div>
				  <? } */
				?>

			</div>

			<div class="row">
				<div class="col-xs-12">
					<div class="heading-part mb10">Billing Details</div>
					<div class="main_time pb0 border-greenline mb20">
						<div class="row book-summary">
							<div class="col-xs-12 col-sm-12 col-md-12">
								<div class="row pt5 pb5">
									<div class="col-xs-7 m0">Base Fare:</div>
									<div class="col-xs-5 m0 text-right"><i class="fa fa-inr"></i> <?= $model->bkgInvoice->bkg_base_amount; ?></div>
								</div>
								<?
								if ($model->bkgInvoice->bkg_discount_amount > 0)
								{
									?>
									<div class="row pt5 pb5">
										<div class="col-xs-7 m0">Discount:</div>
										<div class="col-xs-5 m0 text-right red-text-color"><i class="fa fa-inr"></i> <?= $model->bkgInvoice->bkg_discount_amount; ?></div>
									</div>
									<?
								}
								if ($model->bkgInvoice->bkg_additional_charge > 0 || $model->bkgInvoice->bkg_driver_allowance_amount > 0)
								{
									?>
									<div class="row pt5 pb5">
										<div class="col-xs-7 m0">Additional Charge: </div>
										<div class="col-xs-5 m0 text-right"><i class="fa fa-inr"></i> <?= $model->bkgInvoice->bkg_additional_charge + $model->bkgInvoice->bkg_driver_allowance_amount; ?></div>
									</div>
								<? } ?>
								<?
								if ($model->bkgInvoice->bkg_convenience_charge > 0)
								{
									?>
									<div class="row pt5 pb5 discounttd text-danger hide">
										<div class="col-xs-7 m0">Collect on delivery (COD) fee: </div>
										<div class="col-xs-5 m0 text-right"><i class="fa fa-inr"></i> <?= $model->bkgInvoice->bkg_convenience_charge ?></div>
									</div>
								<? } ?>
								<div class="row pt5 pb5">
									<div class="col-xs-7 m0 sum-height">State Tax:</div>
									<div class="col-xs-5 m0 text-right sum-height"><i class="fa fa-inr"></i> <?= $model->bkgInvoice->bkg_state_tax; ?></div>
								</div>
								<div class="row pt5 pb5">
									<div class="col-xs-7 m0 sum-height">Toll Tax:</div>
									<div class="col-xs-5 m0 text-right sum-height"><i class="fa fa-inr"></i> <?= $model->bkgInvoice->bkg_toll_tax; ?></div>
								</div>
								
								<?
								$staxrate	 = $model->bkgInvoice->getServiceTaxRate();
								$taxLabel	 = ($staxrate == 5) ? 'GST' : 'Service Tax ';
								?>
								<?
								if ($model->bkgInvoice->bkg_sgst > 0)
								{
									?>
									<div class="row pt5 pb5 hide">
										<div class="col-xs-7 m0">SGST (@<?= Yii::app()->params['sgst'] ?>%): </div>
										<div class="col-xs-5 m0 text-right"><i class="fa fa-inr"></i> <?= ((Yii::app()->params['sgst'] / $staxrate) * $model->bkgInvoice->bkg_service_tax)|0; ?></div>
									</div>
								<? } ?>
								<?
								if ($model->bkgInvoice->bkg_cgst > 0)
								{
									?>
									<div class="row pt5 pb5 hide">
										<div class="col-xs-7 m0">CGST (@<?= Yii::app()->params['cgst'] ?>%):</div>
										<div class="col-xs-5 m0 text-right"><i class="fa fa-inr"></i> <?= ((Yii::app()->params['cgst'] / $staxrate) * $model->bkgInvoice->bkg_service_tax)|0; ?></div>
									</div>
								<? } ?>
								<?
								if ($model->bkgInvoice->bkg_igst > 0)
								{
									?>
									<div class="row pt5 pb5">
										<div class="col-xs-7 m0">IGST (@<?= Yii::app()->params['igst'] ?>%):</div>
										<div class="col-xs-5 text-right"><i class="fa fa-inr"></i> <?= ((Yii::app()->params['igst'] / $staxrate) * $model->bkgInvoice->bkg_service_tax)|0; ?></div>
									</div>
								<? } ?>
								<?
								if ($staxrate != 5)
								{
									?>
									<div class="row pt5 pb5">
										<div class="col-xs-7 m0"><?= $taxLabel ?>:</div>
										<div class="col-xs-5 m0 text-right"><i class="fa fa-inr"></i> <?= $model->bkgInvoice->bkg_service_tax; ?></div>
									</div>
								<? } ?>
								
								<div class="row pt5 pb5">
									<div class="col-xs-7 m0 sum-height">Total Fare:</div>
									<div class="col-xs-5 m0 text-right sum-height"><i class="fa fa-inr"></i> <?= $model->bkgInvoice->bkg_total_amount; ?></div>
								</div>
								<?
								if ($model->bkgInvoice->bkg_advance_amount > 0 || $model->bkgInvoice->bkg_credits_used > 0)
								{
									if ($model->bkgInvoice->bkg_advance_amount > 0)
									{
										?>
										<div class="row pt5 pb5">
											<div class="col-xs-7 m0">Advance paid:</div>
											<div class="col-xs-5 m0 text-right"><i class="fa fa-inr"></i> <?= round($model->bkgInvoice->bkg_advance_amount) ?></div>
										</div>
									<? } ?>
									<?
									if ($model->bkgInvoice->bkg_credits_used > 0)
									{
										?>
										<div class="row pt5 pb5">
											<div class="col-xs-7 m0">Credits used:</div>
											<div class="col-xs-5 m0 text-right"><i class="fa fa-inr"></i> <?= $model->bkgInvoice->bkg_credits_used ?></div>
										</div>
									<? } ?>
									<?
									if ($model->bkg_flexxi_type == 1)
									{
										?>

										<div class="row pt5 pb5">
											<div class="col-xs-7 m0">Maximum Amount Due:</div>
											<div class="col-xs-5 m0 text-right">
												<i class="fa fa-inr"></i> <?
												if ($model->bkgInvoice->bkg_due_amount > 0)
												{
													echo round($model->bkgInvoice->bkg_due_amount) . '*';
												}
												else
												{
													echo '0';
												}
												?>
											</div>
										</div>
										<?
									}
									//									else if ($model->bkg_trip_type == 1)
									//									{
									?>

									<div class="row pt5 pb5 green-radius">
										<div class="col-xs-7 h4 m0 sum-height">Amount Due:</div>
										<div class="col-xs-5 h4 m0 text-right">
											<i class="fa fa-inr"></i> <?
											if ($model->bkgInvoice->bkg_due_amount > 0)
											{
												echo round($model->bkgInvoice->bkg_due_amount);
											}
											else
											{
												echo '0';
											}
											?>
										</div>
									</div>
									<?
									//}
								}
								?>
							</div>
						</div>
					</div>
					<!--                    <div class="row">
											<div class="col-xs-12">
												<div class="col-xs-12 h4 text-center green-color"><b>Thank you for choosing Gozocabs!</b></div>
											</div>
										</div>-->
				</div>
			</div>

			<div class="thumbnail p10 border-radius">
				<div class="caption pl0 pr0">
					<ul class="pl20">
						<li>Any changes to itinerary needs to be documented into the booking. Addition of pickup/drop points or way points or sightseeing may cause change in fare.</li>
						<li>You may cancel your Booking with us by logging in to our Mobile App or Website. All bookings cancelled less than 24 hours before the scheduled pickup time shall be subject to Cancellation & Refund Policy as laid down in our 
							<a href="https://www.gozocabs.com/terms" target="_blank">Terms & Conditions</a> page on our website.</li>
						<li>For additional luggage, request a cab with carrier (availability not guaranted )</li>
						<li>All parking charges are to be brone by the customer</li>
						<li>Toll, state taxes extra unless explicitly stated as included on itinerary.</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="col-xs-12 col-sm-5 col-md-5">
			<?php
			if ($model->bkg_booking_type != 7)
			{
				$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
					'id'					 => 'booking-form121', 'enableClientValidation' => true,
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
				/* @var $form TbActiveForm */
				?>	

				<?
				if ($model->bkg_status == 2 && $model->bookingRoutes[0]->brt_from_pincode == '' && $showAdditional)
				{
					?>
					<div class="row">
						<div class="col-xs-12">
							<div class="heading-part mb10">Special Requests</div>
							<div class="main_time border-blueline" style="pointer-events: none;">
								<div class="heading-part mb10"></div>
								<div class="">   

									<?= $form->hiddenField($model, 'bkg_id', ['id' => 'bkg_id4']); ?>
									<?=
									$form->hiddenField($model, 'hash', ['id' => 'hash4', 'class' => 'clsHash', 'value' => $hash]);
									?>
									<div id="error_div" style="display: none" class="alert alert-block alert-danger"></div>
									<div class="row">
										<div class="col-xs-12">
											<div class="row">
												<div class="col-xs-6"><?= $form->checkboxGroup($model->bkgAddInfo, 'bkg_spl_req_senior_citizen_trvl', ['label' => 'Senior citizen traveling', 'groupOptions' => ["class" => ""]]) ?></div>
												<div class="col-xs-6"><?= $form->checkboxGroup($model->bkgAddInfo, 'bkg_spl_req_kids_trvl', []) ?></div>
											</div>
											<div class="row">
												<div class="col-xs-6"><?= $form->checkboxGroup($model->bkgAddInfo, 'bkg_spl_req_woman_trvl', []) ?></div>
												<div class="col-xs-6">
													<?
													if ($scvVctId != VehicleCategory::SEDAN_ECONOMIC)
													{
														echo $form->checkboxGroup($model->bkgAddInfo, 'bkg_spl_req_carrier', []);
													}
													?>
												</div>
											</div>
											<div class="row">
												<div class="col-xs-6"><?= $form->checkboxGroup($model->bkgAddInfo, 'bkg_spl_req_driver_english_speaking', ['label' => 'English-speaking driver required',]) ?></div>
												<div class="col-xs-6"><?= $form->checkboxGroup($model, 'bkg_chk_others', ['label' => 'Others']) ?></div>
											</div>
											<div class="row">
												<div class="col-xs-6"></div>
												<div class="col-xs-6"></div>
											</div>
											<div class="row">
												<div class="col-xs-12" id="othreq" style="display: <?= $otherExist ?>">
													<?= $form->textAreaGroup($model->bkgAddInfo, 'bkg_spl_req_other', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Other Requests"]))) ?>  
												</div>
											</div>
											<div class="row">
												<div class="col-xs-12">
													<? $otherExist ?>Journey Break:
													<div id="addmytrip" style="display: <?= $otherExist ?>">
														<?= $form->dropDownListGroup($model->bkgAddInfo, 'bkg_spl_req_lunch_break_time', ['label' => '', 'readonly', 'widgetOptions' => ['data' => ['0' => 'Minutes', '30' => '30 Minutes', '60' => '60 Minutes', '90' => '90 Minutes', '120' => '120 Minutes', '150' => '150 Minutes', '180' => '180 Minutes'], 'htmlOptions' => []]]) ?>
													</div>
												</div>
											</div> 

										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!--- Additional details --->
					<div class="heading-part mb10">Additional Details</div>
					<div class="border-blueline main_time col-xs-12" style="pointer-events: none;">
						<div class="heading-part mb10"></div>
						<div class="special_request">
							<div class="row mb10">
								<div class="col-sm-12">
									<div class="row">
										<div class="form-group">
											<label for="inputEmail" class="control-label col-xs-12">Personal Or Business Trip?</label>
											<div class="col-xs-12 pl0">
												<?=
												$form->radioButtonListGroup($model->bkgAddInfo, 'bkg_user_trip_type', array(
													'label'			 => '', 'widgetOptions'	 => array(
														'data'	 => Booking::model()->userTripList, 'class'	 => 'bkg_user_trip_type',
													), 'inline'		 => true,));
												?>
											</div>
										</div>
									</div>
								</div>
								<?php
								$readOnly = [];
								if (in_array($model->bkg_flexxi_type, [1, 2]))
								{
									$readOnly = ['readOnly' => 'readOnly'];
								}
								?>
								<div class="col-sm-6">
									<div class="row">
										<div class="form-group">
											<label for="inputEmail" class="control-label col-xs-12">Number of Passengers</label>
											<div class="col-xs-6">
												<?//= $form->numberFieldGroup($model->bkgAddInfo, 'bkg_no_person', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "0", 'min' => 1, 'max' => $bdata['vht_capacity']] + $readOnly), 'groupOptions' => [])) ?>                      
<?php
	$vct_Id			 = $model->bkgSvcClassVhcCat->scv_vct_id;
	$scc_Id			 = $model->bkgSvcClassVhcCat->scv_scc_id;
	$sbagRecord = VehicleCatSvcClass::smallbagBycategoryClass($model->bkgSvcClassVhcCat->scv_vct_id, $model->bkgSvcClassVhcCat->scv_scc_id);
	$lbag = floor($sbagRecord['vcsc_small_bag']/2);
	?>
	<select class="form-control" id="BookingAddInfo_bkg_num_large_bag" name="BookingAddInfo[bkg_num_large_bag]" onchange="luggage_info(this.value,<?php echo $vct_Id ?>,<?php echo $scc_Id ?>,<?php echo $sbagRecord['vcsc_small_bag']?>);">
	<?php for($i=0; $i<=$lbag; $i++) { ?>
		<option value="<?php echo $i ?>"><?php echo $i ?></option>
	<?php } ?>		
	</select>											

</div>
										</div>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="row">
										<div class="form-group">
											<label for="inputEmail" class="control-label col-xs-12"><?= $model->bkgAddInfo->getAttributeLabel('bkg_num_large_bag') ?></label>
											<div class="col-xs-6">
												<?//= $form->numberFieldGroup($model->bkgAddInfo, 'bkg_num_large_bag', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "large suitcases", 'min' => 0, 'max' => $bdata['vht_big_bag_capacity']] + $readOnly), 'groupOptions' => [])) ?>                      
											
<select class="form-control" id="BookingAddInfo_bkg_num_small_bag" name="BookingAddInfo[bkg_num_small_bag]">
	<?php for($i=1; $i<=$sbagRecord['vcsc_small_bag']; $i++) { ?>
		<option value="<?php echo $i ?>"><?php echo $i ?></option>
	<?php } ?>		
		</select>	
</div>
										</div>
									</div>
								</div>

							</div>
							<div class="row">
								<div class="col-sm-3">
									<div class="row">
										<div class="form-group">
											<label for="inputEmail" class="control-label col-xs-12"><?= $model->bkgAddInfo->getAttributeLabel('bkg_num_small_bag') ?></label>
											<div class="col-xs-6">
												<?= $form->numberFieldGroup($model->bkgAddInfo, 'bkg_num_small_bag', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "small bags", 'min' => 0, 'max' => $bdata['vht_bag_capacity']] + $readOnly), 'groupOptions' => [])) ?>                      
											</div>	
										</div>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="row">
										<div class="form-group">
											<label for="inputEmail" class="control-label col-xs-12">How did you hear about Gozo cabs? </label>
											<div class="col-xs-12">
												<?php
												$datainfo		 = VehicleTypes::model()->getJSON($infosource);
												$this->widget('booster.widgets.TbSelect2', array(
													'model'			 => $model->bkgAddInfo,
													'attribute'		 => 'bkg_info_source',
													'val'			 => "'" . $model->bkgAddInfo->bkg_info_source . "'",
													'asDropDownList' => FALSE,
													'options'		 => array('data' => new CJavaScriptExpression($datainfo)),
													'htmlOptions'	 => array('style' => 'width:100%;margin-bottom:10px', 'placeholder' => 'Select Infosource ')
												));
												?>
											</div>
										</div>
									</div>
								</div>
								<? $sourceDescShow	 = ($model->bkgAddInfo->bkg_info_source == 'Friend' || $model->bkgAddInfo->bkg_info_source == 'Other Media') ? '' : 'hide'; ?>
								<div class="col-sm-4">
									<div class="row">
										<div class="form-group <?= $sourceDescShow ?> " id="source_desc_show">
											<label for="inputEmail" class="control-label">&nbsp;</label>
											<div class="col-xs-12 mt20">
												<?= $form->textFieldGroup($model->bkgAddInfo, 'bkg_info_source_desc', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => ""]),)) ?>                      
											</div>
										</div>
									</div>
								</div>
							</div>
							<h3 class="hide">&nbsp;<br>Journey Details: </h3>
							<?
							$j				 = 0;
							$cntRt			 = sizeof($model->bookingRoutes);
							foreach ($model->bookingRoutes as $key => $brtRoute)
							{
								if ($j == 0)
								{
									?>       
									<div class="row hide">
										<div class = "form-group mb15">
											<label for="pickup_address" class="control-label col-xs-12 col-sm-5 pt10">Pickup Pincode for <?= $brtRoute->brtFromCity->cty_name ?></label>
											<div class="col-xs-12 col-sm-7">
												<?= $form->numberFieldGroup($brtRoute, "brt_from_pincode", array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Pincode (Optional)"]), 'groupOptions' => ['class' => ''])) ?>
											</div>
										</div>
									</div>
									<?
								}
								$j++;
								$opt = (($key + 1) == $cntRt) ? 'Required' : 'Optional';
								?>
								<div class = "row hide">
									<div class = "form-group mb15">
										<label for="pickup_address" class="control-label col-xs-12 col-sm-5 pt10">Drop Pincode for <?= $brtRoute->brtToCity->cty_name ?></label>
										<div class="col-xs-12 col-sm-7">
											<?= $form->numberFieldGroup($brtRoute, "brt_to_pincode", array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Pincode (Optional)"]))) ?>
										</div>
									</div>
								</div>
								<?
							}
							?>
						</div>
					</div>
				<?php }
				?>

				<?php $this->endWidget(); ?>
				<?	
			}
			?>
		</div>


		<?
		if (APPLICATION_ENV == 'production')
		{
			?>
			<!-- Google Code for Confirm Lead Conversion Page -->
			<script type="text/javascript">
				/* <![CDATA[ */
				var google_conversion_id = 937550432;
				var google_conversion_language = "en";
				var google_conversion_format = "3";
				var google_conversion_color = "ffffff";
				var google_conversion_label = "J39FCNvt1WYQ4MSHvwM";
				var google_remarketing_only = false;
				/* ]]> */
			</script>
			<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
			</script>
			<noscript>
			<div style="display:inline;">
				<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/937550432/?label=J39FCNvt1WYQ4MSHvwM&amp;guid=ON&amp;script=0"/>
			</div>
			</noscript>
			<?
		}
		?>

		<script type="text/javascript">
<?php
if ($model->bkgUserInfo->bkg_phone_verified != 1 && $model->bkgUserInfo->bkg_email_verified != 1 && $model->bkg_agent_id == '')
{
	?>
				confirmOTP();
<?php } ?>
			if ($('#<?= CHtml::activeId($model, "bkg_add_my_trip") ?>').is(':checked'))
			{
				$("#addmytrip").show();
			}

			function confirmOTP()
			{
				var bid = '<?= $model->bkg_id ?>';
				var hsh = '<?= $model->hash ?>';
				var ctype = '<?= $ctype ?>';
				var href1 = '<?= Yii::app()->createUrl('booking/confirmmobile') ?>';
				jQuery.ajax({'type': 'GET', 'url': href1,
					'data': {'bid': bid, 'hsh': hsh, 'ctype': ctype},
					success: function (data) {
						box = bootbox.dialog({
							message: data,
							title: '',
							size: 'medium',
							onEscape: function () {
							}
						});
					}
				});
			}


			function processBooking() {
				location.reload();
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
			$('form').on('focus', 'input[type=number]', function (e) {
				$(this).on('mousewheel.disableScroll', function (e) {
					e.preventDefault()
				})
				$(this).on("keydown", function (event) {
					if (event.keyCode === 38 || event.keyCode === 40) {
						event.preventDefault();
					}
				});
			});
			$('form').on('blur', 'input[type=number]', function (e) {
				$(this).off('mousewheel.disableScroll');
				$(this).off('keydown');
			});
					
			
function luggage_info(largebag,vcatid,sccid,smallbag)
{
	var largebag = largebag;
	var vcatid = vcatid;
	var sccid = sccid;
	var smallbag = 	smallbag;				
	var sbag = Math.floor(smallbag-(largebag*2));
	$("#BookingAddInfo_bkg_num_small_bag").empty();
	for( var i = 0; i<=sbag; i++)
		{
		var id = i;
		var name = i;               
		$("#BookingAddInfo_bkg_num_small_bag").append("<option value='"+id+"'>"+name+"</option>");
		}					
}	
		</script>