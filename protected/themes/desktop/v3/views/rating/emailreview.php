<style>
	.box-design1 ul{ list-style-type: none;}
	.box-design1 ul li{ list-style-type: none;}
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://apis.google.com/js/platform.js" async defer></script>

<?php
/* @var $model Ratings */
$bkg_booking_id	 = Filter::formatBookingId($bkmodel->bkg_booking_id);
$link			 = 'http://www.aaocab.com/invite/' . $refCode;
$mailBody		 = "Dear Friend,%0D%0DI traveled with Gozocabs and and loved it. Try Gozo with the URL below and both you and I will get Rs. 200 credit for our next trip.%0D$link %0DHere is my review from my trip "
		. $bkg_booking_id . ':%0D "'
		. $bkmodel->ratings[0]['rtg_customer_review'] . '"%0D%0DRegards%0D' . $bkmodel->bkgUserInfo->getUsername();
?>
<?php
$show_number	 = 4;
/* @var $bkmodel Booking */
$cabmodel		 = $bkmodel->getBookingCabModel();
$vehicleModel	 = $cabmodel->bcbCab->vhcType->vht_model;
if ($cabmodel->bcbCab->vhc_type_id === Config::get('vehicle.genric.model.id'))
{
	$vehicleModel = OperatorVehicle::getCabModelName($cabmodel->bcb_vendor_id, $cabmodel->bcb_cab_id);
}
/* @var $cabmodel BookingCab */
$bookingRouteModel	 = BookingRoute::model()->findAll('brt_bkg_id=:id', ['id' => $bkmodel->bkg_id]);
/* @var $bookingRouteModel BookingRoute */
$routeDetail		 = '';
$response			 = Contact::referenceUserData($model->rtgBooking->bkgUserInfo->bui_id, 2);
if ($response->getStatus())
{
	$contactNo	 = $response->getData()->phone['number'];
	$countryCode = $response->getData()->phone['ext'];
	$fname		 = $response->getData()->phone['firstName'];
	$lname		 = $response->getData()->phone['lastName'];
}
foreach ($bookingRouteModel as $key => $bookingRoute)
{
	$routeDetail .= '<div class="col-6 padded">
                Source City : <span class="h5">' . $bookingRoute->brtFromCity->cty_name . '</span>
        </div>
        <div class="col-6 padded">
                Destination City : <span class="h5">' . $bookingRoute->brtToCity->cty_name . '</span>
        </div>';
}

$bookingInfo = '<div class="card mb20">
	<div class="card-body p5">
			<!--<h4 class="mb0 mt10 pl15">Booking Info</h4>-->
<div class="row ml10 mr10">
			<div class="col-6 col-lg-4 padded mb10 p5">
				<span class="font-12 text-muted">Picked up on :</span> <br><span class="font-14">' . DateTimeFormat::DateTimeToLocale($bkmodel->bkg_pickup_date) . '</span>
			</div>
			<div class="col-6 col-lg-4 padded mb10 p5">
				<span class="font-12 text-muted">Booking Time :</span> <br><span class="font-14">' . DateTimeFormat::DateTimeToLocale($bkmodel->bkg_create_date) . '</span>
			</div>
			<div class="col-6 col-lg-4 padded mb10 p5">
				<span class="font-12 text-muted">Route :</span> <br><span class="font-14">' . $bkmodel->bkgFromCity->cty_name . "-" . $bkmodel->bkgToCity->cty_name . '</span>
			</div>
			<div class="col-6 col-lg-4 padded mb10 p5">
				<span class="font-12 text-muted">Driver :</span><br> <span class="font-14">' . $cabmodel->bcbDriver->drv_name . '</span>
			</div>
			<div class="col-6 col-lg-4 padded mb10 p5">
				<span class="font-12 text-muted">Cab :</span><br> <span class="font-14">' . $vehicleModel . " " . $cabmodel->bcbCab->vhc_number . '</span>
			</div>
                        <div class="col-6 col-lg-4 padded mb10 p5">
				<span class="font-12 text-muted">Trip Type :</span><br> <span class="font-14">' . Booking::model()->getBookingType($bkmodel->bkg_booking_type) . '</span>
			</div>
                        </div>
	</div>
</div>
</div>';
$hash		 = Yii::app()->shortHash->hash($bkmodel->bkg_id);
?>
<?php
if ($diffDays > 30)
{
	?>
	<div class="container">
		<div id="dialog" title="Review Link">
			<div class="row d-flex justify-content-center">
				<div class="col-12 col-xl-8">
					<div class="alert alert-danger h4 text-center pt10">
						<strong>Your review link has been expired </strong>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
}
else
{
	?>
	<div class="container">
		<div class="row">
			<div id="section1" class="col-12">
				<div class="row title-widget mb10">
					<div class="col-12 col-lg-6 offset-lg-3">
						<h2 class="heading-line mb0">How did we do on <?= $bookingcode ?></h2>
					</div>
				</div>
				<?php
				if ($ifReviewExist)
				{
					?>
					<div id="row" class="row">
						<div class="col-12 col-lg-6 offset-lg-3">
							<div class="panel" >
								<!--<div class='panel-heading h3 text-danger text-center p0'></div>-->
								<div class="panel-body p0">
									<div class="panel-scroll1">
										<?php
										if ($ifReviewExist)
										{
											if ($linkExpire == 1)
											{
												?>
												<div class="row">
													<div class="col-12 mb30 mt30 col-lg-10 offset-lg-1">
														<div class="card">
															<div class="card-body">
																<h3 class="color-green">Thanks for your feedback</h3>
																The review period for this trip has expired since the trip was completed more than 30days ago.<br>We are capturing your feedback and like all reviews we get, we will also use this information to learn & improve our services.<br>
																However, since the accounts for this trip have already been settled with the taxi operator, our ability to take corrective action (if any) in this particular case may be severely limited.
																In the future, please provide your trips review feedback soon after trip completion.
															</div>
														</div>
													</div>
												</div>

												<?php
												goto skip;
											}
											?>
											<div class="row">
												<div class="col-12" style="color:#666666">
													<?php
													if ($status == 'success')
													{
														?>
															<?php
															if ($model->rtg_customer_overall >= 4)
															{
																?>
																<p>Thank you so much for your recent 5-star review! We're thrilled to hear that you had such a positive experience with us. Your satisfaction is our top priority, and we truly appreciate your feedback.</p>
																<p>Your review helps us to stand out from the competition and attract new customers. It also helps us to improve our services and products so that we can continue to provide you with the best possible experience.</p> 
																<p>If you have a few minutes, we would be grateful if you could leave us a review on Google or TripAdvisor. You can find the links to our pages below:</p>
					<!--															<div class="p15 rounded"><img src="<//?= Yii::app()->createAbsoluteUrl('images/email/local_rental/local_rental.png?v1.2') ?>" class="p0"></div>-->
																<div class="row text-center justify-content-center">
																	<div class="col-12 text-center">
																		<a href="<?= $reviewLinkList['googleShareLink']; ?>" target="_blank">
																			<img src="/images/img-2022/google_review.png?v=0.2" alt="Google Review" title="Google Review" width="121" height="101">
																		</a>
																		<a href="<?= $reviewLinkList['tripAdviserLink']; ?>" target="_blank" class="pl10">
																			<img src="/images/img-2022/trpadvisor2.png?v=0.2" alt="Tripadvisor Review" title="Tripadvisor Review" width="100" height="101">
																		</a>
																	</div>

																</div>
																<div style="border: #B4E4F9 1px solid; background: #F5FCFF;" class="p15 rounded mt20">
																	<p>We would also like to extend our gratitude by inviting you to share our referral link with your friends and family. By doing so, you'll not only be helping them discover our exceptional services but also earning a special referring promo for yourself. Simply click the link below to get started and spread the word about our amazing offerings. Once every new user you refer completes their travel, we'll credit you up to an additional 10% back in fully redeemable coins on the last trip you took. They get 10% off their first trip too! It's a real win-win!</p> 
																	<a href="<?= $reviewLinkList['reviewLink']; ?>" target="_blank"><span style="word-wrap: break-word;"><?= $reviewLinkList['reviewLink']; ?></span></a> <br>
																	"<?= $model->rtg_customer_review; ?>" <br><br>
																	Join Gozo with my referral link. You will get 250 off your first trip. I'll get 20% cashback. Once you travel, you can refer others and get 20% of your money back too. No limit on how many friends you can refer.<br>
																	Join by clicking <a href="<?php echo $reviewLinkList['inviteLink']; ?>" target="_blank"><span style="word-wrap: break-word;"><?php echo $reviewLinkList['inviteLink']; ?></span></a><br>
																</div><br>
																<div class="text-center">
																<a href="<?= $reviewLinkList['whatappShareLink']; ?>" target="_blank"><img src="../images/review_whatsapp.png" alt="Review on Whatsapp" width="150" style="width:150px;" class="mb10"></a>
																<a href='MAILTO:?subject=Gozo Referral&body=<?= $mailBody ?>'><img src="../images/email-icon.png" alt="Send Mail" width="106" class="mb10"></a>
																<a href="<?= Yii::app()->createAbsoluteUrl('users/fbShareLink', ['refcode' => $refCode, 'hash' => $hash, 'id' => $bkmodel->bkg_id]); ?>" target="_blank" class="social-1" rel="nofollow">
																	<img src="../images/facebook_share.png" width="148" alt="Share On Facebook" class="mb10">
																</a>
																</div>
															<?php
														}
														else
														{
															?>
															Thank you for your feedback. It appears that you were not happy with the service. We would like to understand how we can do better and we will be contacting you about this soon.
															<?php
														}
													}
													else
													{
														$route = ' <b>From :</b> ' . $model->rtgBooking->bkgFromCity->cty_name . '  <b>To :</b> ' . $model->rtgBooking->bkgToCity->cty_name;
														echo ' <b>Overall Rating :</b> ' . $model->rtg_customer_overall . "<br>";
														echo ' <b>Customer Recomendation :</b> ' . $model->rtg_customer_recommend . "<br><br>";
														echo $route . "<br>";
														if ($model->rtg_customer_overall >= 4)
														{
															if ($model->rtg_customer_review != '')
															{
																$user_name = ucfirst($fname);
																if ($lname != '')
																{
																	$user_name .= " " . ucfirst($lname);
																}
																echo $model->rtg_customer_review, " - " . ucfirst($fname) . " " . ucfirst($lname);
															}
														}
														else
														{
															echo 'Thank you for your feedback. It appears that you were not happy with the service. We would like to understand how we can do better and we will be contacting you about this soon.';
														}
													}
													?> 
												</div>
											</div>
											<?php
											skip:
										}
										?>		               
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php
				}
				else
				{
					$form = $this->beginWidget('CActiveForm', array(
						'id'					 => 'rating-form',
						'enableAjaxValidation'	 => false,
						'errorMessageCssClass'	 => 'help-block',
						'enableClientValidation' => true,
						'clientOptions'			 => array(
							'validateOnSubmit'	 => true,
							'errorCssClass'		 => 'has-error',
							'afterValidate'		 => 'js:function(form,data,hasError){                                        
                                                validateForm();
                                                return false;
                                        }'
						),
					));
					?>
					<?= $form->errorSummary($model); ?>
					<?= $form->hiddenField($model, 'rtg_booking_id');
					?>

						<div class="row">
							<div class="col-12 col-lg-10 offset-lg-1">
								<!--<div class='panel-heading h3 text-danger text-center p0 mb0'><?= $bookingcode ?></div>-->
								<?php
								if (!$ifReviewExist)
								{
									echo $bookingInfo;
								}
								?>

								<div class="col-12 text-center">
									<span style="border: #d9d9d9 1px solid; display: inline-block"><img src="<?= Yii::app()->createAbsoluteUrl('images/email/local_rental/local_rental.png?v1.2') ?>" class="p0 img-fluid"></span>
								</div>
									<div class="col-12 mt20">
										<div class="row">
											<div class="col-12 col-lg-8">
												<div class="row">
													<div class="col-12 pb10"><h3 class="heading-line-2"><strong><?= $model->getAttributeLabel('rtg_customer_recommend') ?></strong></h3></div>
												</div>
												<div class="row">
													<div class="col-12 pb10"><div class="d-flex justify-content-between rating-widget-heading"><span><b>Never</b></span>
															<span><b>Absolutely</b></span></div></div>
													<div class="col-12 pb10 text-center">
														<?php
//											$this->widget('CStarRating', array(
//												'model'		 => $model,
//												'attribute'	 => 'rtg_customer_recommend',
//												'callback'	 => 'function(){checkRecRating($(this))}',
//												'minRating'	 => 1,
//												'maxRating'	 => 10,
//												'starCount'	 => 10,
//											));
														?> 
														<div class="rating-widget">
															<?php
															$disabled = '';

															if ($model->rtg_customer_recommend > 0)
															{
																$disabled = "disabled";
															}

															for ($x = 10; $x >= 1; $x--)
															{
																?>	
																<input onclick="checkRecRating($(this))" type="radio" name="Ratings[rtg_customer_recommend]" value="<?= $x ?>" <?= $disabled ?> id="rate<?= $x ?>"><label for="rate<?= $x ?>"><?= $x ?></label>
															<?php } ?>
														</div>
													</div> 

												</div>
												<div id="recommendErr" class="review star-review">Please rate how would you like to recommend Gozo to your friends and family.</div>
												<div class="row mt30">
													<div class="col-12">
														<h3 class="heading-line-2"><strong><?= $model->getAttributeLabel('rtg_customer_overall') ?></strong></h3>
													</div>
												</div>
												<div class="row">
													<div class="col-12 col-lg-8 pb10">
														<div class="d-flex justify-content-between rating-widget-heading">
															<span><b>Horrible</b></span><span><b>Loved It!</b></span></div>
													</div>
													<div class="col-12 pb10 mb40 mt10">
														
														<div class="star-widget">
															<?php
															$disabled	 = '';
															$starRate	 = '';
															if ($model->rtg_customer_overall > 0)
															{
																$disabled	 = "disabled";
																$starRate	 = 'star-rating-on';
															}
															for ($y = 5; $y >= 1; $y--)
															{
																?>	
																<input onclick="checkrating($(this))" type="radio" name="Ratings[rtg_customer_overall]" value="<?= $y ?>" <?= $disabled ?> id="star<?= $y ?>"><label for="star<?= $y ?>" class="bx bxs-star <?= $starRate ?>"></label>
															<?php } ?>
														</div>
													</div>
												</div>
												<div id="allErr" class="review star-review">Please rate our Overall Service.</div>  
												<div id="otherrating">
													<div class="row">
														<div class="col-12 mb30"><b><?= $model->getAttributeLabel('rtg_customer_driver') ?></b></div>
														<div class="col-12 mb30">
															
															<div class="star-widget">
																<?php
																for ($drv = 5; $drv >= 1; $drv--)
																{
																	?>	
																	<input onclick="checkdvrrating($(this))" type="radio" name="Ratings[rtg_customer_driver]" value="<?= $drv ?>" id="customerdriver<?= $drv ?>"><label for="customerdriver<?= $drv ?>" class="bx bxs-star"></label>
																<?php } ?>
															</div>
														</div>
													</div>
													<div id="dvrErr" class="review star-review">Please rate our Driver.</div>  

													<div class="row" id="driverRatingBox">
														<div class="col-12 table_none pb10">
															<!--//-->
															<div class="col-12">
																<div class="row">
																	<div class="col-12 p0">
																		<div class="panel panel-default">
																			<div class="panel-body p0">
																				<div class="row">
																					<div class="col-6 col-sm-6 box-design1a pt0">
																						<div class="row">
																							<div class="col-12 box-design1 border-new-b"><b style="color: #008000;">What was good? <img src="/images/icon/happy-smiley.png" alt="" width="18" height="18"></b></div>
																							<div class="col-12 box-design1 mb20">
																								<ul class="mt10 mb0 pl0">
																									<?php
																									$count_driver_good_attr = count($data_array['driver']['good']);
																									foreach ($data_array['driver']['good'] as $key => $vb)
																									{
																										if (isset($vb['ratt_name']) && $vb['ratt_name'] != '')
																										{
																											?>
																											<li id="Ratings_driver_good_li<?= $key ?>" class="">                                                                                                       
																												<span class="">
																													<label class="containers">                                                                                                                   
																														<input type="checkbox" name="Ratings_driver_good[]" id="Ratings_driver_good_<?= $key ?>" value="<?= $vb['ratt_id'] ?>" onclick="checkGoodBox('<?= $key ?>', 'Driver')" > <?= $vb['ratt_name'] ?>
																														<span class="checkmark"></span>
																													</label>
																												</span>
																											</li>
																											<?php
																										}
																										if ($key == $show_number && $count_driver_good_attr > $show_number)
																										{
																											if (count($data_array['driver']['good']) > $show_number)
																											{
																												echo '</ul>';
																												echo '<a href="JavaScript:void(0)" onclick=atr_all("driverGood") id="showGoodDriver" class="btn btn-primary btn-sm">Show more</a>';
																												echo '</ul><ul class="hide pl0" id="show_all_driver">';
																											}
																										}
																									}
																									?>
																								</ul>
																							</div>
																						</div>
																					</div>
																					<div class="col-6 col-sm-6 box-design2a pt0">
																						<div class="row">
																							<div class="col-12 box-design2 border-new-b"><b style="color: #DC143C;">What was not? <img src="/images/icon/sad-icon.png" alt="" width="18" height="18"></b></div>
																							<div class="col-12 box-design1">
																								<ul class="mt10 mb0 pl0">
																									<?php
																									$count_driver_bad_attr = count($data_array['driver']['good']);
																									foreach ($data_array['driver']['good'] as $key => $vb)
																									{
																										if (isset($vb['ratt_name_bad']) && $vb['ratt_name_bad'] != '')
																										{
																											?>
																											<li id="Ratings_driver_bad_li<?= $key ?>" class="">
																												<span class="">
																													<label class="containers">                                                                                                                   
																														<input type="checkbox" class="" name="Ratings_driver_bad[]" value="<?= $vb['ratt_id'] ?>" id="Ratings_driver_bad_<?= $key ?>" onclick="checkBadBox('<?= $key ?>', 'Driver')" > <?= $vb['ratt_name_bad'] ?>    
																														<span class="checkmark2"></span>
																													</label>
																												</span>

																											</li>
																											<?php
																										}
																										if ($key == $show_number && $count_driver_bad_attr > $show_number)
																										{
																											echo '</ul><a href="JavaScript:void(0)" onclick=atr_all("driverBad") id="showBadDriver" class="btn btn-primary btn-sm">Show more</a></ul><ul class="hide pl0" id="show_all_bad_driver">';
																										}
																										?>
																									<?php } ?>
																								</ul>
																							</div>
																						</div>
																					</div>

																				</div>

																				<div class="row">
																					<div class="col-6 col-sm-6"></div>
																					<div class="col-3 col-sm-6 options text-center label-tab">
																					</div>
																				</div>
																				<div class="row mb20">
																					<div class="col-12">
																						<div class="form-group">
																							<?= $form->textArea($model, 'rtg_driver_cmt', array('class' => 'form-control', 'placeholder' => 'Any other comments about Driver')) ?>
																							<?php echo $form->error($model, 'rtg_driver_cmt', ['class' => 'help-block error']); ?>
																						</div>
																						<div>You have <span id="charleftcount1">1000 characters left.</span> (Maximum characters: 1000)</div>
																						<div id="errcharleftcount1" class="review hide">Max 1000 characters.</div>

																					</div>

																				</div>
																			</div>
																		</div>
																	</div>
																</div>
															</div>


														</div>
													</div>



													<div class="row pt10">
														<div class="col-12 mb20"><b><?= $model->getAttributeLabel('rtg_customer_csr') ?></b></div>
														<div class="col-12 mb20">
															
															<div class="star-widget">
																<?php
																for ($csr = 5; $csr >= 1; $csr--)
																{
																	?>	
																	<input onclick="checkcsrrating($(this))" type="radio" name="Ratings[rtg_customer_csr]" value="<?= $csr ?>" id="customercsr<?= $csr ?>"><label for="customercsr<?= $csr ?>" class="bx bxs-star"></label>
																<?php }
																?>
															</div>
														</div>

													</div>
													<div id="csrErr" class="review star-review">Please rate our Customer Support.</div>  
													<div class="row" id="csrRatingBox">
														<div class="col-12 table_none pb10">
															<div class="col-12">
																<div class="row">
																	<div class="col-12 p0">
																		<div class="row mb20">
																			<div class="col-6 box-design1 pt0">
																				<div class="row box-design1a">
																					<div class="col-12 box-design1 border-new-b"><b style="color: #008000;">What was good? <img src="/images/icon/happy-smiley.png" alt="" width="18" height="18"></b></div>
																					<div class="col-12">
																						<ul class="pl0 mb0">
																							<?php
																							$count_csr_good = count($data_array['csr']['good']);
																							foreach ($data_array['csr']['good'] as $key => $vb)
																							{
																								if (isset($vb['ratt_name']) && $vb['ratt_name'] != '')
																								{
																									?>
																									<li id="Ratings_csr_good_li<?= $key ?>" class="">
																										<span class="">
																											<label class="containers">                                                                                                                   
																												<input type="checkbox" name="Ratings_csr_good[]" id="Ratings_csr_good_<?= $key ?>"  value="<?= $vb['ratt_id'] ?>" onclick="checkGoodBox('<?= $key ?>', 'Csr')"> <?= $vb['ratt_name'] ?><span class="checkmark"></span>
																											</label>
																										</span>

																									</li>
																									<?php
																								}
																								if ($key == $show_number && $count_csr_good > $show_number)
																								{
																									echo '</ul><a href="JavaScript:void(0)" onclick=atr_all("csrGood") id="showGoodCsr" class="btn btn-primary btn-sm">Show more</a></ul><ul class="hide pl0" id="show_all_good_csr">';
																								}
																								?>
																							<?php } ?>
																						</ul>
																					</div>
																				</div>
																			</div>
																			<div class="col-6 box-design1 pt0">
																				<div class="row">
																					<div class="col-12 box-design2 border-new-b"><b style="color: #DC143C;">What was not? <img src="/images/icon/sad-icon.png" alt="" width="18" height="18"></b></div>
																					<div class="col-12">
																						<ul class="pl0 mb0">
																							<?php
																							$count_csr_bad = count($data_array['csr']['good']);
																							foreach ($data_array['csr']['good'] as $key => $vb)
																							{
																								if (isset($vb['ratt_name_bad']) && $vb['ratt_name_bad'] != '')
																								{
																									?>
																									<li id="Ratings_csr_bad_li<?= $key ?>" class="">
																										<span class="">
																											<label class="containers">                                                                                                                  
																												<input type="checkbox" name="Ratings_csr_bad[]" id="Ratings_csr_bad_<?= $key ?>" value="<?= $vb['ratt_id'] ?>" onclick="checkBadBox('<?= $key ?>', 'Csr')"> <?= $vb['ratt_name_bad'] ?><span class="checkmark2"></span>
																											</label>
																										</span>
																									</li>
																									<?php
																								}
																								if ($key == $show_number && $count_csr_bad > $show_number + 1)
																								{
																									//echo $key;
																									echo '</ul><a href="JavaScript:void(0)" onclick=atr_all("csrBad") id="showBadCsr" class="btn btn-primary btn-sm">Show more</a></ul><ul class="hide pl0" id="show_all_bad_csr">';
																								}
																								?>
																							<?php } ?>
																						</ul>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="row">
																			<div class="col-12">
																				<div class="form-group">
																					<?= $form->textArea($model, 'rtg_csr_cmt', array('class' => 'form-control', 'placeholder' => 'Any other comments about Customer service')) ?>
																					<?php echo $form->error($model, 'rtg_csr_cmt', ['class' => 'help-block error']); ?>
																				</div>
																				<div>You have <span id="charleftcount3">1000 characters left.</span> (Maximum characters: 1000)</div>
																				<div id="errcharleftcount3" class="review hide">Max 1000 characters.</div> 

																			</div>

																		</div>
																	</div>
																</div>
															</div>
														</div>

													</div>
													
													<div class="row pt10">
													<div class="col-12 mb20"><b><?= $model->getAttributeLabel('rtg_customer_car') ?></b></div>
													<div class="col-12 mb20 mt10">
														
														<div class="star-widget">
															<?php
															for ($car = 5; $car >= 1; $car--)
															{
																?>	
																<input onclick="checkcarrating($(this))" type="radio" name="Ratings[rtg_customer_car]" value="<?= $car ?>" id="customercar<?= $car ?>"><label for="customercar<?= $car ?>" class="bx bxs-star"></label>
															<?php } ?>
														</div>
													</div>

												</div>
												<div id="carErr" class="review star-review">Please rate our Car Quality.</div>  

												<div class="row" id="carRatingBox">

													<div class="col-12">

														<div class="row">
															<div class="col-12">
																<div class="row">
																	<div class="col-6 box-design1 pt0">
																		<div class="row">
																			<div class="col-12 box-design1 border-new-b"><b style="color: #008000;">What was good? <img src="/images/icon/happy-smiley.png" alt="" width="18" height="18"></b></div>
																			<div class="col-12 mb20">
																				<ul class="pl0 mb0">
																					<?php
																					foreach ($data_array['car']['good'] as $key => $vb)
																					{
																						if (isset($vb['ratt_name']) && $vb['ratt_name'] != '')
																						{
																							?>
																							<li id="Ratings_car_good_li<?= $key ?>" class="">
																								<span class="">
																									<label class="containers">                                                                                                                     
																										<input type="checkbox" name="Ratings_car_good[]" value="<?= $vb['ratt_id'] ?>" id="Ratings_car_good_<?= $key ?>" onclick="checkGoodBox('<?= $key ?>', 'Car')"> <?= $vb['ratt_name'] ?>
																										<span class="checkmark"></span>
																									</label>

																								</span>

																							</li>
																							<?php
																						}
																						if ($key == $show_number)
																						{
																							echo '</ul><a href="JavaScript:void(0)" onclick=atr_all("carGood") id="showGoodCar" class="btn btn-primary btn-sm">Show more</a></ul><ul class="hide pl0" id="show_all_car">';
																						}
																						?>
																						<?php
																					}
																					?>
																				</ul>
																			</div>
																		</div>
																	</div>

																	<div class="col-6 box-design1 pt0">
																		<div class="row">
																			<div class="col-12 box-design2 border-new-b"><b style="color: #DC143C;">What was not? <img src="/images/icon/sad-icon.png" alt="" width="18" height="18"></b></div>
																			<div class="col-12">
																				<ul class="pl0 mb0">
																					<?php
																					$count_bad_car = count($data_array['car']['good']);
																					foreach ($data_array['car']['good'] as $key => $vb)
																					{
																						if (isset($vb['ratt_name_bad']) && $vb['ratt_name_bad'] != '')
																						{
																							?>
																							<li id="Ratings_car_bad_li<?= $key ?>" class="">
																								<span class="">
																									<label class="containers">                                                                                                                    
																										<input type="checkbox" name="Ratings_car_bad[]"  value="<?= $vb['ratt_id'] ?>" id="Ratings_car_bad_<?= $key ?>" onclick="checkBadBox('<?= $key ?>', 'Car')"> <?= $vb['ratt_name_bad'] ?>
																										<span class="checkmark2"></span>
																									</label>
																								</span>

																							</li>
																							<?php
																						}
																						if ($key == $show_number && $count_bad_car > $show_number)
																						{

																							echo '</ul><a href="JavaScript:void(0)" onclick=atr_all("carBad") id="showBadCar" class="btn btn-primary btn-sm">Show more</a></ul><ul class="hide pl0" id="show_all_bad_car">';
																						}
																						?>
																					<?php } ?>
																				</ul>
																			</div>
																		</div>
																	</div>

																</div>
																<div class="row">
																	<div class="col-12">
																		<div class="form-group">
																			<?= $form->textArea($model, 'rtg_car_cmt', array('class' => 'form-control', 'placeholder' => 'Any other comments about Car experience')) ?>
																			<?php echo $form->error($model, 'rtg_car_cmt', ['class' => 'help-block error']); ?>
																		</div>
																		<div>You have <span id="charleftcount2">1000 characters left.</span> (Maximum characters: 1000)</div>
																		<div id="errcharleftcount2" class="review hide">Max 1000 characters.</div>
																	</div>
																</div> 
															</div>
														</div>
													</div>
													<div class="col-12">

													</div>

													<div class="col-12 table_none pb10">

													</div>
												</div>

												</div>



												

												<div class="row">
													<div class="col-12 mt20">
														<div class="card mb20">
															<div class="card-body p15">
																<label class="checkbox-inline check-box">I want Gozo team to contact me
																	<?php echo $form->checkBox($model->rtgBooking->bkgPref, 'bkg_contact_gozo'); ?>
																	<span class="checkmark-box"></span>
																</label>
																<div class="row">
																	<div class="col-12 mt10">Did you find website & app easy</div>
																	<div class="col-12 mb10">
																		<label class="radio2-style mb0 mr10">
																			<input id="Ratings_rtg_platform_exp_0" value="1" type="radio" name="Ratings[rtg_platform_exp]" class="rtg_platform_exp"> Yes	
																			<span class="checkmark-2"></span>
																		</label>
																		<label class="radio2-style mb0 mr10">
																			<input id="Ratings_rtg_platform_exp_1" value="0" type="radio" name="Ratings[rtg_platform_exp]" class="rtg_platform_exp"> No	
																			<span class="checkmark-2"></span>
																		</label>
																		<label class="radio2-style mb0 mr10">
																			<input id="Ratings_rtg_platform_exp_2" value="2" type="radio" name="Ratings[rtg_platform_exp]" class="rtg_platform_exp"> Didn't use	
																			<span class="checkmark-2"></span>
																		</label>
																	</div>
																	<div class="col-12 col-lg-12">
																		<div class="form-group">
																			<?= $form->textArea($model, 'rtg_platform_exp_cmt', array('class' => 'form-control', 'placeholder' => 'Comments about our website & app')) ?>
																			<?php echo $form->error($model, 'rtg_platform_exp_cmt', ['class' => 'help-block error']); ?>
																		</div>
																		<div class="mt15 n color-gray font-12">You have <span id="charleftcount4">1000 characters left.</span> (Maximum characters: 1000)</div>
																		<div id="errcharleftcount4" class="review hide">Max 1000 characters.</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>

												<div class="row">
													<div class="col-12">
														<div class="row pt10">
															<div id="reviewErr" class="col-12 review star-review hide">At least say just a few words. Encouragement feels good. And your feedback helps us to improve!</div>  
															<!--<div class="col-12"><?= $model->getAttributeLabel('rtg_customer_review') ?></div>-->
															<div class="col-12">
																<div class="form-group">
																	<?= $form->textArea($model, 'rtg_customer_review', array('class' => 'form-control', 'placeholder' => 'Overall trip comments')) ?>
																	<?php echo $form->error($model, 'rtg_customer_review', ['class' => 'help-block error']); ?>
																</div>
																<div class="mt15 n color-gray">You have <span id="charleftcount">1000 characters left.</span> (Maximum characters: 1000)</div>
															</div>
															<div id="revErr" class="review col-12 hide">Please give your feedback.</div>
															<div id="overErr" class="review col-12 hide">Max 1000 characters.</div>
														</div>
													</div>
												</div>

												<div class="col-12 p15 text-center " id="DivSubmitRate">
													<button class="btn btn-primary text-uppercase gradient-green-blue border-none mt15" type="submit" value="Rate" tabindex="2" >Submit Review</button>
												</div>
												<input type="hidden" name="uniqueId" id="uniqueId" value="<?= $uniqueid; ?>">
											</div>

											<div class="col-12 col-lg-4 mb-1 qrCode p5">
												<div class="row">
													<div class="col-12 col-lg-10 offset-lg-1">
														<div class="mb30" style="border: #f36e32 10px solid;">
															<div class="text-center font-36 bg-orange color-white pb10"><b>Need a cab?</b></div>
															<div class="text-center font-11 weight500 pt5"><b>Chauffeur driven AC cabs at the best possible prices</b></div>
															<div class="text-center font-14 pt10"><b>Local - Airport transfers & Daily rentals<br>
																	Outstation - One-way, Round Trips & more</b></div>
															<div class="card-body" style="margin: auto; position: relative;">
																<?php
																$userId		 = $bkmodel->bkgUserInfo->bkg_user_id;
																$path		 = Users::getUserPathById($userId);
																$qrModel	 = QrCode::model()->find('qrc_ent_id = :userid', array('userid' => $userId));
																$qrcode		 = ($qrModel) ? $qrModel->qrc_code : ''; //AttachmentProcessing::ImagePath($modelUser->usr_qr_code_path); 
																?>
																<a href="<?= Yii::app()->createAbsoluteUrl('/users/GetQRCode') ?>"><img src='<?= $path ?>' alt='' style='width: 2.4in;'/></a>
																<div style="position: absolute; top: 18px;right: 0px; font-weight: bold;width: 4px;letter-spacing: 1px;font-weight: 700;font-size: 11px;color: #1e1e1e;margin: 0 0 10px 0;line-height: 26px;writing-mode: vertical-rl!important;-webkit-writing-mode: vertical-rl!important;transform-origin: 0 0!important;text-align: center;margin-top: 81px;"><?= $qrcode; ?></div>
															</div>
															<?php
															$usrModel	 = Users::model()->findByPk($bkmodel->bkgUserInfo->bkg_user_id);
															$filePath	 = Yii::app()->basePath . DIRECTORY_SEPARATOR . $usrModel->usr_qr_code_path;
															?>
															<div class="row m0">
																<div class="col-3 p5"><img src="/images/google-5-Stars.jpg" alt="Google review" width="55"></div>
																<div class="col-6 p5"><img src="/images/gozo-white-cabs.svg" alt="Gozocabs:India's leader in inter-city taxi | Great service. Price guarantee. Awesome reviews." title="Gozocabs:India's leader in inter-city taxi | Great service. Price guarantee. Awesome reviews." width="130"></div>
																<div class="col-3 p5 text-right"><img src="/images/tripadvisor-certificato.jpg" alt="Tripadvisor Certificato" width="55"></div>

																<div class="col-12 text-center p0 pt10 font-11 bg-orange color-white pb0">
																	<b>30+ million kilometres each year<br>Easy | Reliable | Affordable | Safe | Everywhere in India</b>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
							</div>
						</div>

					<?php
					$this->endWidget();
				}
				?>
			</div>
		</div>
	</div>
<?php } ?>

<script>
    $(document).ready(function () {
        $('.rating-cancel').addClass('hide');

        $('#otherrating').hide();
        $('#driverRatingBox').hide();
        $('#csrRatingBox').hide();
        $('#carRatingBox').hide();

        $('#Ratings_rtg_customer_driver_3').click(function () {

        });


        //checkUncheckDriverBox('1');
        var customerrecommend = '<?= $model->rtg_customer_recommend ?>';
        var customeraverall = '<?= $model->rtg_customer_overall ?>';

        for (var $i = customerrecommend; $i >= 1; $i--)
        {
            //$('#rate' + $i).addClass('star-rating-on');
            $('#rate' + customerrecommend).prop('checked', true);
        }

        for (var $i = customeraverall; $i >= 1; $i--)
        {
            //$('#star'+$i).addClass('star-rating-on');
            $('#star' + customeraverall).prop('checked', true);
        }

    });


    function findAndReplace(string, target, replacement)
    {
        var i = 0, length = string.length;
        for (i; i < length; i++)
        {
            string = string.replace(target, replacement);

        }
        return string;
    }


    function validateForm() {
        //debugger;
        var $error = 0;
        /*
         if ($('#Ratings_rtg_customer_review').val() = '') {
         $error += 1;
         $('#reviewErr').show();
         } else {
         $error += 0;
         $('#reviewErr').hide();
         }*/
        if (!$('#rate1').hasClass('star-rating-on')) {
            $('#recommendErr').show();
            $error += 1;
        } else {
            $('#recommendErr').hide();
            $error += 0;
        }

        if (!$('#star1').hasClass('star-rating-on')) {
            $('#allErr').show();
            $error += 1;
        } else {
            $('#allErr').hide();
            $error += 0;
        }


//        var custReview = $('#Ratings_rtg_customer_review').val();
//        custReview = findAndReplace(custReview, " ", "");
//        if (custReview != '' && custReview.length > 1) {
//            $('#reviewErr').hide();
//            $error += 0;
//
//        } else
//        {
//            $('#reviewErr').show();
//            $error += 1;
//        }

        if (!$('#Ratings_rtg_customer_overall_3').hasClass('star-rating-on')) {
            if ($('#customerdriver1').hasClass('star-rating-on')) {

                $('#dvrErr').hide();
                $error += 0;
            } else {
                $('#dvrErr').show();
                $error += 1;
            }
            if ($('#customercsr1').hasClass('star-rating-on')) {
                $('#csrErr').hide();
                $error += 0;
            } else {
                $('#csrErr').show();
                $error += 1;
            }
            if ($('#customercar1').hasClass('star-rating-on')) {
                $('#carErr').hide();
                $error += 0;
            } else {
                $('#carErr').show();
                $error += 1;

            }
        } else {
            $('#dvrErr').hide();
            $('#carErr').hide();
            $('#csrErr').hide();
            $error += 0;
        }

        if ($error == 0) {
            $("#DivSubmitRate").hide();
        } else {
            $("#DivSubmitRate").show();
        }

        if ($error == 0)
        {
            $.ajax({
                type: 'POST',
                "dataType": "json",
                "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('rating/ajaxverify')) ?>",
                "data": $('#rating-form').serialize(),
                "beforeSend": function ()
                {
                    ajaxindicatorstart("");
                },
                "complete": function ()
                {
                    ajaxindicatorstop();
                },
                success: function (data1)
                {
                   // debugger;
                    if (data1.result == true)
                    {
                        var uniqueId = data1.uniqueId;
                        var status = data1.result;
                        var returnUrl = <?= CJavaScript::encode(Yii::app()->createUrl('rating/bookingreview', ['uniqueid' => $uniqueid, 'status' => 'success'])) ?>;
                        if (window.opener)
                        {
                            if (returnUrl)
                            {
                                window.opener.location.href = returnUrl;
                            } else
                            {
                                window.opener.location.reload();
                            }
                            window.close();
                        } else
                        {
                            window.location.href = returnUrl ? returnUrl : '/';
                        }
                    }

                }
            });
        }
    }

    function checkrating(obj) {
        $rate = obj.val();
        var recratingid = '<?= $model->rtg_id ?>';
        if (recratingid != '')
        {
            return false;
        }
        if ($rate > 0)
        {
            $('#otherrating').show();
            $("input[name='Ratings[rtg_customer_overall]']").removeClass('star-rating-on');
            for (var $i = $rate; $i >= 1; $i--)
            {
                $('#star' + $i).addClass('star-rating-on');
            }
        } else
        {
            $('#dvrErr').hide();
            $('#carErr').hide();
            $('#csrErr').hide();
        }
        if ($rate == '')
        {
            $('#allErr').show();
        } else {
            $('#allErr').hide();
        }
    }
    function checkRecRating(obj) {
        $rate = obj.val();
        if ($rate == '') {
            $('#recommendErr').show();
        } else {
            $("input[name='Ratings[rtg_customer_recommend]']").removeClass('star-rating-on');
            for (var $i = $rate; $i >= 1; $i--)
            {
                $('#rate' + $i).addClass('star-rating-on');
            }
            $('#recommendErr').hide();
        }
    }
    function checkcarrating(obj) {
        $rate = obj.val();
        if ($rate != '')
        {
            $("input[name='Ratings[rtg_customer_car]']").removeClass('star-rating-on');
            if ($rate > 0)
            {
                for (var $i = $rate; $i >= 1; $i--)
                {
                    $('#customercar' + $i).addClass('star-rating-on');
                }
            }
            if ($rate < 5)
            {
                $('#carRatingBox').show();
            } else
            {
                $('#carRatingBox').hide();
            }
            $('#carErr').hide();
        } else
        {
            $('#carErr').show();
        }
    }
    function checkcsrrating(obj) {
        $rate = obj.val();
        if ($rate != '')
        {
            $("input[name='Ratings[rtg_customer_csr]']").removeClass('star-rating-on');
            if ($rate > 0)
            {
                for (var $i = $rate; $i >= 1; $i--)
                {
                    $('#customercsr' + $i).addClass('star-rating-on');
                }
            }
            if ($rate < 5)
            {
                $('#csrRatingBox').show();
            } else
            {
                $('#csrRatingBox').hide();
            }
            $('#csrErr').hide();
        } else
        {
            $('#csrErr').show();
        }
    }

    function checkdvrrating(obj) {
        $rate = obj.val();
        if ($rate != '')
        {
            $("input[name='Ratings[rtg_customer_driver]']").removeClass('star-rating-on');
            if ($rate > 0)
            {
                for (var $i = $rate; $i >= 1; $i--)
                {
                    $('#customerdriver' + $i).addClass('star-rating-on');
                }
            }
            if ($rate < 5)
            {
                $('#driverRatingBox').show();
            } else
            {
                $('#driverRatingBox').hide();
            }
            $('#dvrErr').hide();
        } else {
            $('#dvrErr').show();
        }
    }

    var numLength, levelText1, levelText2, levelText3;
    function checkCharacterCount(numLength, levelText1) {
        levelText2 = '#' + levelText1;
        levelText3 = '#err' + levelText1;
        if (numLength > 1000)
        {
            $(levelText2).text('entered ' + (numLength - 1000) + ' characters  extra.');
            $(levelText3).show();
        } else
        {
            $(levelText2).text((1000 - numLength) + ' characters  left.');
            $(levelText3).hide();
        }
    }





    $('#Ratings_rtg_customer_review').keyup(function () {
        rev = $('#Ratings_rtg_customer_review').val();
        revlength = rev.length;
        if (revlength > 1000) {
            $('#overErr').show();
            $('#charleftcount').text('entered ' + (revlength - 1000) + ' characters  extra.');
        } else {
            $('#overErr').hide();
            $('#charleftcount').text((1000 - revlength) + ' characters  left.');
        }
    });

    $('#Ratings_rtg_customer_review').change(function () {
        rev = $('#Ratings_rtg_customer_review').val();
        if (rev.length > 1000) {
            $('#overErr').show();
        } else {
            $('#overErr').hide();
        }
    });


    $('#Ratings_rtg_driver_cmt').keyup(function ()
    {
        cmtLength = $('#Ratings_rtg_driver_cmt').val().length;
        checkCharacterCount(cmtLength, 'charleftcount1');
    });

    $('#Ratings_rtg_car_cmt').keyup(function ()
    {
        cmtLength = $('#Ratings_rtg_car_cmt').val().length;
        checkCharacterCount(cmtLength, 'charleftcount2');
    });


    $('#Ratings_rtg_csr_cmt').keyup(function ()
    {
        cmtLength = $('#Ratings_rtg_csr_cmt').val().length;
        checkCharacterCount(cmtLength, 'charleftcount3');
    });

    $('#Ratings_rtg_platform_exp_cmt').keyup(function ()
    {
        cmtLength = $('#Ratings_rtg_platform_exp_cmt').val().length;
        checkCharacterCount(cmtLength, 'charleftcount4');
    });
    function atr_all(sectiontype)
    {
        switch (sectiontype) {
            case 'carGood':
                $('#show_all_car').removeClass('hide');
                $('#showGoodCar').addClass('hide');
                break;
            case 'carBad':
                $('#show_all_bad_car').removeClass('hide');
                $('#showBadCar').addClass('hide');
                break;
            case 'csrGood':
                $('#show_all_good_csr').removeClass('hide');
                $('#showGoodCsr').addClass('hide');
                break;
            case 'csrBad':
                $('#show_all_bad_csr').removeClass('hide');
                $('#showBadCsr').addClass('hide');
                break;
            case 'driverGood':
                $('#show_all_driver').removeClass('hide');
                $('#showGoodDriver').addClass('hide');
                break;
            case 'driverBad':
                $('#show_all_bad_driver').removeClass('hide');
                $('#showBadDriver').addClass('hide');
                break;
            default:
                exit;

        }
    }

</script>


<script>
    function checkGoodBox(number, type)
    {

        if (type == 'Driver')
        {
            var driver_good = '#Ratings_driver_good_' + number;
            var driver_good_li = '#Ratings_driver_good_li' + number;
            var driver_bad = '#Ratings_driver_bad_' + number;
            var driver_bad_li = '#Ratings_driver_bad_li' + number;
            if ($(driver_good).is(':checked'))
            {
                $(driver_good).attr('checked');
                $(driver_bad).removeAttr('checked');
                $(driver_good_li).addClass('active');
                $(driver_bad_li).removeClass('active');
            } else
            {
                $(driver_good_li).removeClass('active');
                $(driver_good).removeAttr('checked');
            }
        }

        if (type == 'Csr')
        {
            var csr_good = '#Ratings_csr_good_' + number;
            var csr_good_li = '#Ratings_csr_good_li' + number;
            var csr_bad = '#Ratings_csr_bad_' + number;
            var csr_bad_li = '#Ratings_csr_bad_li' + number;
            if ($(csr_good).is(':checked'))
            {
                $(csr_good).attr('checked');
                $(csr_bad).removeAttr('checked');
                $(csr_good_li).addClass('active');
                $(csr_bad_li).removeClass('active');
            } else
            {
                $(csr_good_li).removeClass('active');
                $(csr_good).removeAttr('checked');
            }
        }

        if (type == 'Car')
        {
            var car_good = '#Ratings_car_good_' + number;
            var car_good_li = '#Ratings_car_good_li' + number;
            var car_bad = '#Ratings_car_bad_' + number;
            var car_bad_li = '#Ratings_car_bad_li' + number;
            if ($(car_good).is(':checked'))
            {
                $(car_good).attr('checked');
                $(car_bad).removeAttr('checked');
                $(car_good_li).addClass('active');
                $(car_bad_li).removeClass('active');
            } else
            {
                $(car_good_li).removeClass('active');
                $(car_good).removeAttr('checked');
            }
        }
    }

    function checkBadBox(number, type)
    {
        if (type == 'Driver')
        {
            var driver_good = '#Ratings_driver_good_' + number;
            var driver_good_li = '#Ratings_driver_good_li' + number;
            var driver_bad = '#Ratings_driver_bad_' + number;
            var driver_bad_li = '#Ratings_driver_bad_li' + number;

            if ($(driver_bad).is(':checked'))
            {
                $(driver_bad).attr('checked');
                $(driver_good).removeAttr('checked');
                $(driver_bad_li).addClass('active');
                $(driver_good_li).removeClass('active');
            } else
            {
                $(driver_bad_li).removeClass('active');
                $(driver_bad).removeAttr('checked');
            }
        }
        if (type == 'Csr')
        {
            var csr_good = '#Ratings_csr_good_' + number;
            var csr_good_li = '#Ratings_csr_good_li' + number;
            var csr_bad = '#Ratings_csr_bad_' + number;
            var csr_bad_li = '#Ratings_csr_bad_li' + number;

            if ($(csr_bad).is(':checked'))
            {
                $(csr_bad).attr('checked');
                $(csr_good).removeAttr('checked');
                $(csr_bad_li).addClass('active');
                $(csr_good_li).removeClass('active');
            } else
            {
                $(csr_bad_li).removeClass('active');
                $(csr_bad).removeAttr('checked');
            }

        }

        if (type == 'Car')
        {
            var car_good = '#Ratings_car_good_' + number;
            var car_good_li = '#Ratings_car_good_li' + number;
            var car_bad = '#Ratings_car_bad_' + number;
            var car_bad_li = '#Ratings_car_bad_li' + number;
            if ($(car_bad).is(':checked'))
            {
                $(car_bad).attr('checked');
                $(car_good).removeAttr('checked');
                $(car_bad_li).addClass('active');
                $(car_good_li).removeClass('active');
            } else
            {
                $(car_bad_li).removeClass('active');
                $(car_bad).removeAttr('checked');
            }
        }
    }



    var checkbox2button = {

        init: function (selector) {
            $(selector).each(function () {
                var $parent = $(this);
                var $input = $parent.find('input[type="checkbox"]');
                if ($input.length == 1) {
                    var value = $input.attr('value');
                    var name = $input.attr('name');
                    var checked = false;
                    if ($input.attr('checked')) {
                        checked = true;
                    }
                    var html = '<span><input type="hidden" name="' + name + '" value="" />';
                    html += '<a class="btn btn-default btn-sm btn-checkbox2btn" data-value="' + value + '" onclick=checkUncheckDriverBox("' + value + '")><span class="glyphicon glyphicon-unchecked"></span> ' + $parent.text() + '</a></span>';
                    $parent.html(html);
                    if (checked) {
                        checkbox2button.checkCheckbox($parent.find('a'));
                    }
                }
                var $input = $parent.find('input[type="radio"]');
                if ($input.length == 1) {
                    var value = $input.attr('value');
                    var name = $input.attr('name');
                    var checked = false;
                    if ($input.attr('checked')) {
                        checked = true;
                    }
                    var html = '';
                    if (!$('input[type="hidden"][name="' + name + '"]').length) {
                        html = '<input type="hidden" name="' + name + '" value="" />';
                    }
                    html += '<a class="btn btn-default btn-sm btn-radio2btn" data-name="' + name + '" data-value="' + value + '"><span class="glyphicon glyphicon-unchecked"></span> ' + $parent.text() + '</a>';
                    $parent.html(html);
                    if (checked) {
                        checkbox2button.checkRadioCheckbox($parent.find('a'));
                    }
                }
            });

            $('body').on('click', '.btn-checkbox2btn', function (event) {
                event.preventDefault();
                if ($(event.target).hasClass('btn-checkbox-checked')) {
                    checkbox2button.unCheckCheckbox($(event.target));
                } else {
                    checkbox2button.checkCheckbox($(event.target));
                }
                return false;
            });

            $('body').on('click', '.btn-checkbox2btn .glyphicon', function (event) {
                event.preventDefault();
                target = $(event.target).parent('.btn-checkbox2btn');
                if (target) {
                    if ($(target).hasClass('btn-checkbox-checked')) {
                        checkbox2button.unCheckCheckbox($(target));
                    } else {
                        checkbox2button.checkCheckbox($(target));
                    }
                }
                return false;
            });



        },

        checkCheckbox: function (element)
        {
            var val = ($(element).attr('data-value'));
            $(element).siblings('input').val(val);
            $(element).addClass('btn-checkbox-checked');
            $(element).find('.glyphicon').removeClass('glyphicon-unchecked');
            $(element).find('.glyphicon').addClass('glyphicon-check');

            checkUncheckDriverBox(val);
        },

        unCheckCheckbox: function (element)
        {
            $(element).siblings('input').val('');
            $(element).removeClass('btn-checkbox-checked');
            $(element).find('.glyphicon').addClass('glyphicon-unchecked');
            $(element).find('.glyphicon').removeClass('glyphicon-check');
        },

        checkRadioCheckbox: function (element) {
            $('.btn-radio2btn[data-name="' + $(element).attr('data-name') + '"]').removeClass('btn-checkbox-checked');
            $('.btn-radio2btn[data-name="' + $(element).attr('data-name') + '"] .glyphicon').removeClass('glyphicon-uncheck');
            $('.btn-radio2btn[data-name="' + $(element).attr('data-name') + '"] .glyphicon').addClass('glyphicon-unchecked');
            $('input[type="hidden"][name="' + $(element).attr('data-name') + '"]').val($(element).attr('data-value'));
            $(element).addClass('btn-checkbox-checked');
            $(element).find('.glyphicon').removeClass('glyphicon-unchecked');
            $(element).find('.glyphicon').addClass('glyphicon-check');
        },

        unCheckRadioCheckbox: function (element) {
            $('.btn-radio2btn[data-name="' + $(element).attr('data-name') + '"]').removeClass('btn-checkbox-checked');
            $('.btn-radio2btn[data-name="' + $(element).attr('data-name') + '"] .glyphicon').removeClass('glyphicon-uncheck');
            $('.btn-radio2btn[data-name="' + $(element).attr('data-name') + '"] .glyphicon').addClass('glyphicon-unchecked');
            $('input[type="hidden"][name="' + $(element).attr('data-name') + '"]').val('');
        }


    }


    $('document').ready(function () {
        checkbox2button.init('.checkbox2button');
    });
</script>								 