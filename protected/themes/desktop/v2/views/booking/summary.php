
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
?>
<?php
if ($platform != 3)
{
	?>
	<div class="container">
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
	            <div class="col-12">

					<?php
					if ($errorMsg != '')
					{
						?>
						<div role="alert" class="alert alert-danger text-center"> 
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
								<div role="alert" class="alert alert-success text-center bg-green2 color-green2 border-none"> 
									<strong>Transaction was successful. Thank you for your order. Your Transaction Id : <?= $transid ?></strong>
								</div>
								<?php
							}
							elseif ($succ == '')
							{
								?>
								<div role="alert" class="alert alert-danger"> 
									<strong>Oh snap!</strong> Something went wrong.  
								</div>
								<?php
							}
							else
							{
								?>
								<div role="alert" class="alert alert-danger"> 
									<strong>Oh snap!</strong> Something went wrong. Transaction was not successful. 
								</div>
								<?php
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
						<p class="text-center mb5"> <b class="color-green">Booking Successful!</b> You will receive the cab details 3 hours before your scheduled pickup time.</p>
					<?php } ?>
	                <p class="text-center">Please review the details of your booking request.</p>
	            </div>
	            <div class="col-12 mb20">
	                <div class="bg-white-box">
	                    <div class="row">

							<?php
							if ($model->bkg_flexxi_type == 1)
							{
								?>
								<div class="col-12 text-right">
									Share Your cab with your friends with :<br>
									<a href="https://api.whatsapp.com/send?phone=<?= $model->bkgUserInfo->bkg_country_code . $model->bkgUserInfo->bkg_contact_no ?>&text=<?= $text ?>" target="_blank"><img src="/images/whatsapp-share.png" alt="Share on Whatsapp"></a>
								</div>
								<div class="col-6 hidden">
									<a href="<?= Yii::app()->createAbsoluteUrl('users/fbShareLink', ['id' => $model->bkg_id, 'hash' => Yii::app()->shortHash->hash($model->bkg_id), 'text' => $text]); ?>" target="_blank" class="social-1 hvr-push" style="color: #fff" rel="nofollow">
										<button class="btn btn-lg" type="button" style="color: #fff;background: #3b5a9b">
											<i class="fa fa-facebook mr15"></i>SHARE
										</button>
									</a>
								</div>
							<?php } ?>
							<?php
							if ($model->bkg_flexxi_type == 2 && $model->bkgInvoice->bkg_promo1_code == 'FLATRE1')
							{
								?>
								<div class="col-12 text-right">
									<b>Share with your friends with :</b><br>
									<a href="https://api.whatsapp.com/send?phone=<?= $model->bkgUserInfo->bkg_country_code . $model->bkgUserInfo->bkg_contact_no ?>&text=<?= $textre1 ?>" target="_blank"><img src="/images/whatsapp-share.png" alt="Share on Whatsapp"></a>
								</div>
							<?php } ?>
	                        <div class="col-md-4 col-sm-5 title">Booking ID: <span class="green-color"><b><?= $model->bkg_booking_id ?> - ( <?= ucwords($model->getBookingType($model->bkg_booking_type, 'Trip')) ?>)</b></span></div>
	                        <div class="col-md-6 col-sm-5 title">Route: <span class="bg-blue2 color-white radius-3 p5 pl10 pr10 font-16"><?= $model->bkgFromCity->cty_name . '&#10147;' . $model->bkgToCity->cty_name ?></span></div>
	                        <div class="col-md-2 col-sm-2 title text-right">Status: <span class="color-green"><b><?= Booking::model()->getActiveBookingStatus($model->bkg_status) ?></b> <i class="fas fa-check-circle"></i></span></div>

	                    </div>
	                </div>
	            </div>
	            <div class="col-12">
	                <div class="row">
	                    <div class="col-md-8">
	                        <div class="row">

	                            <div class="col-12 mb20">
	                                <div class="bg-white-box">
	                                    <div class="font-20 mb10 text-uppercase"><b>Traveller Information</b></div>
	                                    <div class="row">
	                                        <div class="col-sm-6">
	                                            <div class="row">
	                                                <div class="col-5 pb5 color-light-blue">Passenger Name: </div> <div class="col-7"><b><span class="black-color"><?= $model->bkgUserInfo->bkg_user_fname . ' ' . $model->bkgUserInfo->bkg_user_lname ?></span></b></div>
													<?php
													if ($model->bkgUserInfo->bkg_user_email != '')
													{
														?>
														<div class="col-5 pb5 color-light-blue">Email:</div><div class="col-7"> <span class="black-color"><?= $model->bkgUserInfo->bkg_user_email ?><?= ($model->bkgUserInfo->bkg_email_verified == 1) ? " <i class='fa fa-check btn-success ' title='verified'></i>" : " <i class='fa fa-times btn-danger' title='not verified'></i>" ?></span></div>
													<?php } ?>
													<?php
													if ($model->bkgUserInfo->bkg_contact_no != '')
													{
														?>
														<div class="col-5 pb5 color-light-blue">Primary Phone:</div> <div class="col-7"><span class="black-color">+<?= $model->bkgUserInfo->bkg_country_code ?><?= $model->bkgUserInfo->bkg_contact_no ?><?= ($model->bkgUserInfo->bkg_phone_verified == 1) ? " <i class='fa fa-check btn-success' title='verified'></i>" : " <i class='fa fa-times btn-danger' title='not verified'></i>" ?></span></div>
													<?php } ?>
													<?php
													if ($model->bkgUserInfo->bkg_alt_contact_no != '')
													{
														?>
														<div class="col-4">Alternate Phone: </div> <div class="col-8"><span class="black-color">+<?= $model->bkgUserInfo->bkg_alt_country_code ?><?= $model->bkgUserInfo->bkg_alt_contact_no ?></span></div>

													<?php } ?>
	                                            </div>
	                                        </div>
	                                        <div class="col-sm-6">
	                                            <div class="row">
	                                                <div class="col-4 pb5 color-light-blue">Cab Type:</div><div class="col-8 pb5"> <span class="black-color"><?= $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label . ' (' . $model->bkgSvcClassVhcCat->scc_ServiceClass->scc_label . ')' ?><?= ($model->bkgSvcClassVhcCat->scv_scc_id == 4 || $model->bkgSvcClassVhcCat->scv_scc_id == 5)? '-' . $model->bkgVehicleType->vht_make . ' ' . $model->bkgVehicleType->vht_model : "" ?></span></div>
	                                                <div class="col-4 pb5 color-light-blue">Trip Type:</div><div class="col-8 pb5"> <span class="black-color"><?= ucwords($model->getBookingType($model->bkg_booking_type, 'Trip')) ?></span></div>
	                                                <div class="col-4 pb5 color-light-blue">Pickup Time:</div><div class="col-8 pb5"> <span class="black-color"><?= date('jS M Y (l)', strtotime($model->bkg_pickup_date)) ?> <?= date('h:i A', strtotime($model->bkg_pickup_date)) ?></span></div>
	                                            </div>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>
	                            <div class="col-12 mb20">
	                                <div class="bg-white-box">
	                                    <div class="font-20 mb10 text-uppercase"><b>Your Trip Plan</b></div>
	                                    <div class="container-time pb15">
											<?php
											$last	 = 0;
											$tdays	 = '';
											$cntBrt	 = count($model->bookingRoutes);
											foreach ($model->bookingRoutes as $k => $brt)
											{
												if (in_array($model->bkg_booking_type, [9, 10, 11]))
												{
													$brt->brt_trip_distance	 = $model->bkg_trip_distance;
													$brt->brt_trip_duration	 = $model->bkg_trip_duration;
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
																<span class="pull-right radius-3 color-white p5 bg-red">Day <?= $tdays ?></span></div>
															<div class="info hide"><?= ($brt->brt_from_location == '') ? $brt->brtFromCity->cty_name : $brt->brt_from_location ?></div>
															<div class="type"><?= ($brt->brt_trip_distance < $model1->bkg_trip_distance && $model1->bkg_booking_type == 1) ? $model1->bkg_trip_distance : $brt->brt_trip_distance ?><br>km</div>
														</div> <span class="number"><span class="black-color pt10">
																<?= DateTimeFormat::DateTimeToDatePicker($brt->brt_pickup_datetime); ?><br><b class="gray-color bold-none">
																	<?= DateTimeFormat::DateTimeToTimePicker($brt->brt_pickup_datetime); ?></b></span> 
															<span class="timeing-box"><?= BookingRoute::model()->formatTripduration($brt->brt_trip_duration); ?></span></span>
													</li>
													<?php
													if ($k == ($cntBrt - 1))
													{
														?>
														<?php
														$expArrivedate = date('Y-m-d H:i:s', strtotime($model->bookingRoutes[$cntBrt - 1]->brt_pickup_datetime . ' + ' . $model->bookingRoutes[$cntBrt - 1]->brt_trip_duration . ' MINUTE'));
														?>
														<li>
															<div><span></span>
																<div class="title black-color"><?= ($brt->brt_to_location == '' ) ? $brt->brtToCity->cty_name : $brt->brt_to_location ?>
																	<span class="pull-right radius-3 color-white p5 bg-red">Day <?= $tdays ?></span></div>
																<div class="info hide"><?= ($brt->brt_to_location == '' ) ? $brt->brtToCity->cty_name : $brt->brt_to_location ?></div>
																<div class="type hide"><?= ($brt->brt_trip_distance < $model1->bkg_trip_distance && $model1->bkg_booking_type == 1) ? $model1->bkg_trip_distance : $brt->brt_trip_distance ?><br>km</div>
															</div> <span class="number "><span class="black-color "><?= DateTimeFormat::DateTimeToDatePicker($expArrivedate); ?><br><b class="gray-color bold-none"><?= DateTimeFormat::DateTimeToTimePicker($expArrivedate); ?></b></span> 
																<span class="timeing-box hide"><?= BookingRoute::model()->formatTripduration($brt->brt_trip_duration); ?></span></span>
														</li>
													<?php } ?>

												</ul>

											<?php } ?>
	                                    </div>
	                                </div>
	                            </div>
	                            <div class="col-12 mb20">
	                                <div class="bg-white-box pb0">
	                                    <div class="font-20 mb10 text-uppercase"><b>Billing Details</b></div>
	                                    <div class="row book-summary">
	                                        <div class="col-12 col-sm-12 col-md-12">
	                                            <div class="row pt5 pb5">
	                                                <div class="col-7 m0">Base Fare:</div>
	                                                <div class="col-5 m0 text-right"><span>&#x20B9</span><?= $model->bkgInvoice->bkg_base_amount; ?></div>
	                                            </div>
												<?php
												if ($model->bkgInvoice->bkg_discount_amount > 0)
												{
													?>
													<div class="row pt5 pb5">
														<div class="col-7 m0">Discount:</div>
														<div class="col-5 m0 text-right red-text-color"><span>&#x20B9</span><?= $model->bkgInvoice->bkg_discount_amount; ?></div>
													</div>
												<?php
												}
												if($model->bkgInvoice->bkg_extra_discount_amount > 0)
												{
												?>
													<div class="row pt5 pb5">
														<div class="col-7 m0">One-Time Price Adjustment:</div>
														<div class="col-5 m0 text-right red-text-color"><span>&#x20B9</span><?= $model->bkgInvoice->bkg_extra_discount_amount; ?></div>
													</div>
												<?php
												}
												if ($model->bkgInvoice->bkg_addon_charges > 0)
												{
													?>
													<div class="row pt5 pb5">
														<div class="col-7 m0">Addon Charge:</div>
														<div class="col-5 m0 text-right red-text-color"><span>&#x20B9</span><?= $model->bkgInvoice->bkg_addon_charges; ?></div>
													</div>
												<?php
												}
												if ($model->bkgInvoice->bkg_additional_charge > 0 || $model->bkgInvoice->bkg_driver_allowance_amount > 0)
												{
													?>
													<div class="row pt5 pb5">
														<div class="col-7 m0">Additional Charge: </div>
														<div class="col-5 m0 text-right"><span>&#x20B9</span><?= $model->bkgInvoice->bkg_additional_charge + $model->bkgInvoice->bkg_driver_allowance_amount; ?></div>
													</div>
												<?php } ?>
												<?php
												if ($model->bkgInvoice->bkg_convenience_charge > 0)
												{
													?>
													<div class="row pt5 pb5 discounttd text-danger hide">
														<div class="col-7 m0">Collect on delivery (COD) fee: </div>
														<div class="col-5 m0 text-right"><span>&#x20B9</span><?= $model->bkgInvoice->bkg_convenience_charge ?></div>
													</div>
												<?php } ?>
												<?php
												$staxrate	 = $model->bkgInvoice->getServiceTaxRate();
												$taxLabel	 = ($staxrate == 5) ? 'GST' : 'Service Tax ';
												?>
												<?php
												if ($model->bkgInvoice->bkg_sgst > 0)
												{
													?>
													<div class="row pt5 pb5 hide">
														<div class="col-7 m0">SGST (@<?= Yii::app()->params['sgst'] ?>%): </div>
														<div class="col-5 m0 text-right"><span>&#x20B9</span><?= ((Yii::app()->params['sgst'] / $staxrate) * $model->bkgInvoice->bkg_service_tax)|0; ?></div>
													</div>
												<?php } ?>
												<?
												if ($model->bkgInvoice->bkg_cgst > 0)
												{
												?>
												<div class="row pt5 pb5 hide">
													<div class="col-7 m0">CGST (@<?= Yii::app()->params['cgst'] ?>%):</div>
													<div class="col-5 m0 text-right"><span>&#x20B9</span><?= ((Yii::app()->params['cgst'] / $staxrate) * $model->bkgInvoice->bkg_service_tax)|0; ?></div>
												</div>
											<?php } ?>
											<?php
											if ($model->bkgInvoice->bkg_igst > 0)
											{
												?>
												<div class="row pt5 pb5">
													<div class="col-7 m0">IGST (@<?= Yii::app()->params['igst'] ?>%):</div>
													<div class="col-5 text-right"><span>&#x20B9</span><?= ((Yii::app()->params['igst'] / $staxrate) * $model->bkgInvoice->bkg_service_tax)|0; ?></div>
												</div>
											<?php } ?>
											<?php
											if ($staxrate != 5)
											{
												?>
												<div class="row pt5 pb5">
													<div class="col-7 m0"><?= $taxLabel ?>:</div>
													<div class="col-5 m0 text-right"><span>&#x20B9</span><?= $model->bkgInvoice->bkg_service_tax; ?></div>
												</div>
											<?php } ?>
                                            <div class="row pt5 pb5">
                                                <div class="col-7 m0 sum-height">State Tax:</div>
                                                <div class="col-5 m0 text-right sum-height"><span>&#x20B9</span><?= $model->bkgInvoice->bkg_state_tax; ?></div>
                                            </div>
                                            <div class="row pt5 pb5">
                                                <div class="col-7 m0 sum-height">Toll Tax:</div>
                                                <div class="col-5 m0 text-right sum-height"><span>&#x20B9</span><?= $model->bkgInvoice->bkg_toll_tax; ?></div>
                                            </div>
                                            <div class="row pt5 pb5">
                                                <div class="col-7 m0 sum-height">Airport Entry Fee:</div>
                                                <div class="col-5 m0 text-right sum-height"><span>&#x20B9</span><?php echo $model->bkgInvoice->bkg_airport_entry_fee; ?></div>
                                            </div>
                                            <div class="row pt5 pb5">
                                                <div class="col-7 m0 sum-height">Total Fare:</div>
                                                <div class="col-5 m0 text-right sum-height"><span>&#x20B9</span><?= $model->bkgInvoice->bkg_total_amount; ?></div>
                                            </div>
											<?php
											if ($model->bkgInvoice->bkg_advance_amount > 0 || $model->bkgInvoice->bkg_credits_used > 0)
											{
												if ($model->bkgInvoice->bkg_advance_amount > 0)
												{
													?>
													<div class="row pt5 pb5">
														<div class="col-7 m0">Advance paid:</div>
														<div class="col-5 m0 text-right"><span>&#x20B9</span><?= round($model->bkgInvoice->bkg_advance_amount) ?></div>
													</div>
												<?php } ?>
												<?php
												if ($model->bkgInvoice->bkg_credits_used > 0)
												{
												?>
												<div class="row pt5 pb5">
													<div class="col-7 m0">Credits used:</div>
													<div class="col-5 m0 text-right"><span>&#x20B9</span><?= $model->bkgInvoice->bkg_credits_used ?></div>
												</div>
											<?php } ?>
											<?php
											if ($model->bkg_flexxi_type == 1)
											{
											?>

											<div class="row pt5 pb5">
												<div class="col-7 m0">Maximum Amount Due:</div>
												<div class="col-5 m0 text-right">
													&#x20B9; <?php
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
											<?php
											}
											//									else if ($model->bkg_trip_type == 1)
											//									{
											?>

											<div class="row gradient-green-blue radius-bottom-5 font-20 mt10 pt5 pb5">
												<div class="col-7 m0 sum-height font-24">Amount Due:</div>
												<div class="col-5 m0 text-right font-24"><b>
														<span>&#x20B9</span><?php
														if ($model->bkgInvoice->bkg_due_amount > 0)
														{
														echo round($model->bkgInvoice->bkg_due_amount);
														}
														else
														{
														echo '0';
														}
														?>
													</b>
												</div>
											</div>
											<?php
											//}
											}
											?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="row">

							<?php
							if ($model->bkg_booking_type != 7)
							{
								$form = $this->beginWidget('CActiveForm', array(
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
								/* @var $form CActiveForm */
								?>	

								<?php
								if ($model->bkg_status == 2 && $model->bookingRoutes[0]->brt_from_pincode == '' && $showAdditional)
								{
								?>

								<div class="col-12 mb20">
									<div class="bg-white-box">
										<div class="heading-part mb10 text-uppercase font-weight-bold">Special Requests</div>
										<div class="" style="pointer-events: none;">
											<div class="heading-part mb10"></div>
											<div class="">   

												<?= $form->hiddenField($model, 'bkg_id', ['id' => 'bkg_id4']); ?>
												<?=
												$form->hiddenField($model, 'hash', ['id' => 'hash4', 'class' => 'clsHash', 'value' => $hash]);
												?>
												<div id="error_div" style="display: none" class="alert alert-block alert-danger"></div>
												<div class="row p10">
													<div class="col-12 col-md-12">
														<div class="row">
															<div class="col-12">
																<label class="checkbox-inline check-box">Senior citizen traveling
																	<?php echo $form->checkBox($model->bkgAddInfo, 'bkg_spl_req_senior_citizen_trvl'); ?>
																	<span class="checkmark-box"></span>
																</label>
															</div>
															<div class="col-12">

																<label class="checkbox-inline check-box">Kids on board
																	<?php echo $form->checkBox($model->bkgAddInfo, 'bkg_spl_req_kids_trvl'); ?>
																	<span class="checkmark-box"></span>
																</label>
															</div>
														</div>
														<div class="row">
															<div class="col-12">
																<label class="checkbox-inline check-box">Women traveling
																	<?php echo $form->checkBox($model->bkgAddInfo, 'bkg_spl_req_woman_trvl'); ?>
																	<span class="checkmark-box"></span>
																</label>
															</div>
															<div class="col-12">
																<?
																if ($scvVctId != VehicleCategory::SEDAN_ECONOMIC)
																{
																?>
																<label class="checkbox-inline check-box">Require vehicle with Carrier
																	<?= $form->checkBox($model->bkgAddInfo, 'bkg_spl_req_carrier'); ?>
																	<span class="checkmark-box"></span>
																</label>
																<?
																}
																?>
															</div>
														</div>
														<div class="row">
															<div class="col-12">
																<label class="checkbox-inline check-box">English-speaking driver required
																	<?= $form->checkBox($model->bkgAddInfo, 'bkg_spl_req_driver_english_speaking'); ?>
																	<span class="checkmark-box"></span>
																</label>
															</div>
															<div class="col-12">
																<label class="checkbox-inline check-box">Others
																	<?= $form->checkBox($model, 'bkg_chk_others'); ?>
																	<span class="checkmark-box"></span>
																</label>
															</div>
														</div>
														<div class="row">
															<div class="col-6 col-md-6"></div>
															<div class="col-6 col-md-6"></div>
														</div>
														<div class="row">
															<div class="col-12" id="othreq" style="display: <?= $otherExist ?>">
																<?= $form->textArea($model->bkgAddInfo, 'bkg_spl_req_other', ['placeholder' => "Other Requests", 'class' => 'form-control']) ?>  
															</div>
														</div>
														<div class="row">
															<div class="col-12 col-md-12">
																<? $otherExist ?>Journey Break:
																<div id="addmytrip" style="display: <?= $otherExist ?>">
																	<?= $form->dropDownList($model->bkgAddInfo, 'bkg_spl_req_lunch_break_time', ['0' => 'Minutes', '30' => '30 Minutes', '60' => '60 Minutes', '90' => '90 Minutes', '120' => '120 Minutes', '150' => '150 Minutes', '180' => '180 Minutes'], ['class' => 'form-control']) ?>
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
								<div class="col-12 mb20">
									<div class="bg-white-box">
										<div class="heading-part mb10 text-uppercase font-weight-bold">Additional Details</div>
										<div style="pointer-events: none;">
											<div class="heading-part mb10"></div>
											<div class="special_request">
												<div class="row mb10 p10">
													<div class="col-sm-12">
														<div class="row">
															<label for="inputEmail" class="control-label col-12">Personal Or Business Trip?</label>
															<div class="col-6 pl0">
																<label class="radio2-style mb0">
																	<input id="BookingAddInfo_bkg_user_trip_type_0" value="1" type="radio" name="BookingAddInfo[bkg_user_trip_type]" class="bkg_user_trip_type" <?php
																	if ($model->bkgAddInfo['bkg_user_trip_type'] == 1)
																	{
																		?>checked="checked"<?php } ?>>Personal	
																	<span class="checkmark-2"></span>
																</label>
															</div>
															<div class="col-6 pl0">
																<label class="radio2-style mb0">
																	<input id="BookingAddInfo_bkg_user_trip_type_1" value="2" type="radio" name="BookingAddInfo[bkg_user_trip_type]" class="bkg_user_trip_type" <?php
																	if ($model->bkgAddInfo['bkg_user_trip_type'] == 2)
																	{
																		?>checked="checked"<?php } ?>>Business	
																	<span class="checkmark-2"></span>
																</label>
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
																<label for="inputEmail" class="control-label col-12">Number of Passengers</label>
																<div class="col-8">
																	<?= $form->numberField($model->bkgAddInfo, 'bkg_no_person', ['placeholder' => "0", 'min' => 1, 'max' => $bdata['vht_capacity'], 'class' => 'form-control'] + $readOnly) ?>                      

																</div>
															</div>
														</div>
													</div>
													<div class="col-sm-6">
														<div class="row">
															<div class="form-group">
																<label for="inputEmail" class="control-label col-12"><?= $model->bkgAddInfo->getAttributeLabel('bkg_num_large_bag') ?></label>
																<div class="col-8">
																	<?= $form->numberField($model->bkgAddInfo, 'bkg_num_large_bag', ['placeholder' => "large suitcases", 'min' => 0, 'max' => $bdata['vht_big_bag_capacity'], 'class' => 'form-control'] + $readOnly) ?>

																</div>
															</div>
														</div>
													</div>

												</div>
												<div class="row p10">
													<div class="col-sm-3">
														<div class="row">
															<div class="form-group">
																<label for="inputEmail" class="control-label"><?= $model->bkgAddInfo->getAttributeLabel('bkg_num_small_bag') ?></label>
																<div class="col-12">
																	<?= $form->numberField($model->bkgAddInfo, 'bkg_num_small_bag', ['placeholder' => "small bags", 'min' => 0, 'max' => $bdata['vht_bag_capacity'], 'class' => 'form-control'] + $readOnly) ?>                      
																</div>	
															</div>
														</div>
													</div>
													<div class="col-sm-5">
														<div class="row">
															<div class="form-group">
																<label for="inputEmail" class="control-label col-12 col-sm-12">How did you hear about Gozo cabs? </label>
																<div class="col-12 col-sm-12">
																	<?php
																	$infosource = ['' => 'Select Infosource'] + $infosource;
																	echo $form->dropDownList($model->bkgAddInfo, "bkg_info_source", $infosource, ['class' => 'form-control', 'style' => 'width:100%;margin-bottom:10px', 'placeholder' => 'Select Infosource']);
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
																<div class="col-12 mt20">
																	<?= $form->textField($model->bkgAddInfo, 'bkg_info_source_desc', ['placeholder' => "", 'class' => 'form-control']) ?>                      
																</div>
															</div>
														</div>
													</div>
												</div>
												<h3 class="hide">&nbsp;<br>Journey Details: </h3>
												<?php
												$j				 = 0;
												$cntRt			 = sizeof($model->bookingRoutes);
												foreach ($model->bookingRoutes as $key => $brtRoute)
												{
												if ($j == 0)
												{
												?>       
												<div class="row hide">
													<div class = "form-group mb15">
														<label for="pickup_address" class="control-label col-12 col-sm-5 pt10">Pickup Pincode for <?= $brtRoute->brtFromCity->cty_name ?></label>
														<div class="col-12 col-sm-7">
															<?= $form->numberField($brtRoute, "brt_from_pincode", ['placeholder' => "Pincode (Optional)", 'class' => 'form-control']) ?>
														</div>
													</div>
												</div>
												<?php
												}
												$j++;
												$opt = (($key + 1) == $cntRt) ? 'Required' : 'Optional';
												?>
												<div class = "row hide">
													<div class = "form-group mb15">
														<label for="pickup_address" class="control-label col-12 col-sm-5 pt10">Drop Pincode for <?= $brtRoute->brtToCity->cty_name ?></label>
														<div class="col-12 col-sm-7">
															<?= $form->numberField($brtRoute, "brt_to_pincode", ['placeholder' => "Pincode (Optional)", 'class' => 'form-control']) ?>
														</div>
													</div>
												</div>
												<?php
												}
												?>
											</div>
										</div>
									</div>
								</div>
							<?php }
							?>

							<?php $this->endWidget(); ?>
							<?php
							}
							?>

                        </div>
                    </div>
                </div>

                <div class="row ul-style-b">
                    <div class="col-12">
                        <ul>
                            <li><i class="fas fa-check-circle color-green"></i> Any changes to itinerary needs to be documented into the booking. Addition of pickup/drop points or way points or sightseeing may cause change in fare.</li>
                            <li><i class="fas fa-check-circle color-green"></i> You may cancel your Booking with us by logging in to our Mobile App or Website. All bookings cancelled less than 24 hours before the scheduled pickup time shall be subject to Cancellation & Refund Policy as laid down in our 
                                <a href="http://www.aaocab.com/terms" target="_blank">Terms & Conditions</a> page on our website.</li>
                            <li><i class="fas fa-check-circle color-green"></i> For additional luggage, request a cab with carrier (availability not guaranted )</li>
                            <li><i class="fas fa-check-circle color-green"></i> All parking charges are to be brone by the customer</li>
                            <li><i class="fas fa-check-circle color-green"></i> Toll, state taxes extra unless explicitly stated as included on itinerary.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--    <div class="col-xs-12 col-sm-5 col-md-5">
					
            </div>-->
	<?php
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
	<?php
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
                success: function (data)
                {
                    box = bootbox.dialog({
                        message: data,
                        title: '',
                        size: 'medium',
                        onEscape: function ()
                        {
                            box.modal('hide');
                            box.css('display', 'none');
                            $('.modal-backdrop').remove();
                            $("body").removeClass("modal-open");
                        }
                    }).removeClass('fade').css('display', 'block');
                }
            });
        }


        function processBooking()
        {
            location.reload();
        }

        $('#<?= CHtml::activeId($model, "bkg_chk_others") ?>').change(function ()
        {
            if ($('#<?= CHtml::activeId($model, "bkg_chk_others") ?>').is(':checked'))
            {
                $("#othreq").show();
            }
            else
            {
                $("#othreq").hide();
            }
        });
        $('#<?= CHtml::activeId($model, "bkg_add_my_trip") ?>').change(function ()
        {
            if ($('#<?= CHtml::activeId($model, "bkg_add_my_trip") ?>').is(':checked'))
            {
                $("#addmytrip").show();
            }
            else
            {
                $("#addmytrip").hide();
            }
        });
        $('form').on('focus', 'input[type=number]', function (e)
        {
            $(this).on('mousewheel.disableScroll', function (e)
            {
                e.preventDefault()
            })
            $(this).on("keydown", function (event)
            {
                if (event.keyCode === 38 || event.keyCode === 40)
                {
                    event.preventDefault();
                }
            });
        });
        $('form').on('blur', 'input[type=number]', function (e)
        {
            $(this).off('mousewheel.disableScroll');
            $(this).off('keydown');
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