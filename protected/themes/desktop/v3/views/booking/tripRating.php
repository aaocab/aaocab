<?php
$dataArray		 = RatingAttributes::model()->getRatingAttributes(1);
$link			 = [];

$model			 = Ratings::model()->getRatingbyBookingId($bkmodel->bkg_id);
$lnkexp     = Ratings::isLinkExpired($bkmodel->bkg_id);


$userId		 = $bkmodel->bkgUserInfo->bkg_user_id;
$modelUser	 = Users::model()->findByPk($userId);
if (!$model)
{
	$model = new Ratings('custRating');
}


if($model->rtg_customer_review != '' || $model->rtg_customer_review != null)
{ 
	$reviewStatus = "hide";
}

$model->rtg_booking_id	 = $bkmodel->bkg_id;
$showNumber			 = 4;
$hash		 = Yii::app()->shortHash->hash($bkmodel->bkg_id);
?>

<div class="row m0">
    <div id="section1" class="col-12 col-xl-8">
			<?php
		//if($model->rtg_id == null){

			$form	 = $this->beginWidget('CActiveForm', array(
				'id'					 => 'rating-form',
				'enableClientValidation' => true,
				'clientOptions'			 => array(
					'validateOnSubmit'	 => false,
					'errorCssClass'		 => 'has-error'
				),
				'enableAjaxValidation'	 => false,
				'errorMessageCssClass'	 => 'help-block',
				'htmlOptions'			 => array(
					'class'		 => 'form-horizontal',
					'onsubmit'	 => 'return customerRating();'
				),
					));
			?>
			<?= $form->errorSummary($model); ?>
			<?= $form->hiddenField($model, 'rtg_booking_id');
			?>

			<div class="col-12 custreview mt-1">
				<div class="row">
									<div class="col-12 mb20">
										<?php
										if($lnkexp==1)
										{
											$hide ="hide";
											$ratingexpMessage= 'Rating link expired';

										}
										?>
										<p class="danger"><b><?=$ratingexpMessage; ?></b></p>
										<p class="font-14"><b><?= $model->getAttributeLabel('rtg_customer_recommend') ?></b></p>
										
										<div class="d-flex justify-content-between rating-widget-heading <?= $reviewStatus ?>">
											<span>Never</span><span class="right-m">Absolutely</span>
										</div>
										<div class="rating-widget">
											<?php
											$disabled = '';
											
											if($model->rtg_customer_recommend > 0)
											{
											   $disabled	 = "disabled";
											}
											
											for ($x = 10; $x >= 1; $x--)
											{
												?>	
												<input onclick="checkRecRating($(this))" type="radio" name="Ratings[rtg_customer_recommend]" value="<?= $x ?>" <?= $disabled ?> id="rate<?= $x ?>"><label for="rate<?= $x ?>"><?= $x ?></label>
											<?php } ?>
										</div>
									</div>
									<div class="col-12">
										<p class="font-14"><b>Your rating's and comments<?//= $model->getAttributeLabel('rtg_customer_overall') ?></b></p>
										<div class="d-flex justify-content-between rating-widget-heading-2 <?= $reviewStatus ?>">
											<span>Horrible</span><span class="right-m">Loved It!</span>
										</div>
										<div class="col-12 mt20 mb40">
											<div class="star-widget">
												<?php
												$disabled = '';
												$starRate = '';
												if($model->rtg_customer_overall > 0)
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
									<div id="allErr" class="star-review pl15">Please rate our Overall Service.</div> 
									
									<div class="col-12" id="otherrating">

											<div class="row">
<div class="col-12 mt-2 mb10"><b><?= $model->getAttributeLabel('rtg_customer_driver') ?></b></div>
												<div class="col-12 mt20 mb20">
													<div class="star-widget">
														<?php
														for ($drv = 5; $drv >= 1; $drv--)
														{
															?>	
															<input onclick="checkdvrrating($(this))" type="radio" name="Ratings[rtg_customer_driver]" value="<?= $drv ?>" id="customerdriver<?= $drv ?>"><label for="customerdriver<?= $drv ?>" class="bx bxs-star"></label>
														<?php } ?>
													</div>
												</div>
												<div id="dvrErr" class="star-review pl15">Please rate our Driver.</div>  

														<div class="col-12" id="driverRatingBox">
																	<div class="row mt-1 list-7styled">
																		<div class="col-12 col-md-6 col-lg-6 mb20">
																			<div class="row">
																				<div class="col-12 box-design1 border-new-b"><b style="color: #8ACB9E;">What was good? <img src="/images/img-2022/happy-smiley.svg" alt="" width="20"></b></div>
																				<div class="col-12">
																					<ul class="mt10 mb0">
																						<?php
																						$countDriverGoodAttr = count($dataArray['driver']['good']);
																						foreach ($dataArray['driver']['good'] as $key => $vb)
																						{
																							if (isset($vb['ratt_name']) && $vb['ratt_name'] != '')
																							{
																								?>
																								<li id="Ratings_driver_good_li<?= $key ?>" class="">                                                                                                       
																									<span class="">
																										<div class="checkbox">
																										<label class="containers">
																											<input type="checkbox" name="Ratings_driver_good[]" class="checkbox-input" id="Ratings_driver_good_<?= $key ?>" value="<?= $vb['ratt_id'] ?>" onclick="checkGoodBox('<?= $key ?>', 'Driver')"><?= $vb['ratt_name'] ?>
																											<label for="checkbox1"></label>
																										</label>
																										</div>
																									</span>
																								</li>
																								<?php
																							}
																							if ($key == $showNumber && $countDriverGoodAttr > $showNumber)
																							{
																								if (count($dataArray['driver']['good']) > $showNumber)
																								{
																									echo '</ul>';
																									echo '<a href="JavaScript:void(0)" onclick=atr_all("driverGood") id="showGoodDriver" class="btn btn-primary font-12 mt5 pl10 pr10">Show more</a>';
																									echo '</ul><ul class="hide" id="show_all_driver">';
																								}
																							}
																						}
																						?>
																					</ul>
																				</div>
																			</div>
																		</div>
																		<div class="col-12 col-md-6 col-lg-6 mb20">
																			<div class="row">
																				<div class="col-12 box-design2 border-new-b"><b class="text-danger">What was not? <img src="/images/img-2022/sad.svg" alt="" width="20"></b></div>
																				<div class="col-12">
																					<ul class="mt10 mb0">
																						<?php
																						$countDriverBadAttr = count($dataArray['driver']['good']);
																						foreach ($dataArray['driver']['good'] as $key => $vb)
																						{
																							if (isset($vb['ratt_name_bad']) && $vb['ratt_name_bad'] != '')
																							{
																								?>
																								<li id="Ratings_driver_bad_li<?= $key ?>" class="">
																									<span class="">
																										<div class="checkbox">
																										<label class="containers">
																											<input type="checkbox" name="Ratings_driver_bad[]" class="checkbox-input" id="Ratings_driver_bad_<?= $key ?>" value="<?= $vb['ratt_id'] ?>" onclick="checkBadBox('<?= $key ?>', 'Driver')"><?= $vb['ratt_name_bad'] ?>
																											<label for="checkbox1"></label>
																										</label>
																										</div>
																									</span>

																								</li>
																								<?php
																							}
																							if ($key == $showNumber && $countDriverBadAttr > $showNumber)
																							{
																								echo '</ul><a href="JavaScript:void(0)" onclick=atr_all("driverBad") id="showBadDriver" class="btn btn-primary font-12 mt5 pl10 pr10">Show more</a></ul><ul class="hide" id="show_all_bad_driver">';
																							}
																							?>
																						<?php } ?>
																					</ul>
																				</div>
																			</div>
																		</div>
																	</div>
																	<div class="row m0 pt10 pb10">
																		<div class="col-6 col-sm-6"></div>
																		<div class="col-3 col-sm-6 options text-center label-tab">
																		</div>
																	</div>
																	<div class="row">
																		<div class="col-12">
																			<div class="form-group">
																				<?= $form->textArea($model, 'rtg_driver_cmt', array('class' => 'form-control', 'placeholder' => 'Any other comments about Driver')) ?>
																				<?php echo $form->error($model, 'rtg_driver_cmt', ['class' => 'help-block error']); ?>
																			</div>
																			<div class="text-muted">You have <span id="charleftcount1">1000 characters left.</span> (Maximum characters: 1000)</div>
																			<div id="errcharleftcount1" class="star-review text-muted">Max 1000 characters.</div>

																		</div>

																	</div>
														</div>

												<div class="col-12 mt-2 mb10"><b><?= $model->getAttributeLabel('rtg_customer_csr') ?></b></div>

												<div class="col-12 mt20 mb20">
													<div class="star-widget">
														<?php
														for ($csr = 5; $csr >= 1; $csr--)
														{
															?>	
															<input onclick="checkcsrrating($(this))" type="radio" name="Ratings[rtg_customer_csr]" value="<?= $csr ?>" id="customercsr<?= $csr ?>"><label for="customercsr<?= $csr ?>" class="bx bxs-star"></label>
														<?php } ?>
													</div>
												</div>
												<div id="csrErr" class="star-review pl15">Please rate our Customer Support.</div>  

												<div class="col-12" id="csrRatingBox">
													<div class="row mt-1 list-7styled">
														<div class="col-12 col-md-6 col-lg-6 mb20">
															<div class="row">
																<div class="col-12"><b style="color: #8ACB9E;">What was good? <img src="/images/img-2022/happy-smiley.svg" alt="" width="20"></b></div>
																<div class="col-12">
																	<ul>
																		<?php
																		$countCsrGood = count($dataArray['csr']['good']);
																		foreach ($dataArray['csr']['good'] as $key => $vb)
																		{
																			if (isset($vb['ratt_name']) && $vb['ratt_name'] != '')
																			{
																				?>
																				<li id="Ratings_csr_good_li<?= $key ?>" class="">
																					<div class="checkbox">
																						<label class="containers">
																							<input type="checkbox" name="Ratings_csr_good[]" class="checkbox-input" id="Ratings_csr_good_<?= $key ?>" value="<?= $vb['ratt_id'] ?>" onclick="checkGoodBox('<?= $key ?>', 'Csr')"><?= $vb['ratt_name'] ?>
																							<label for="checkbox1"></label>
																						</label>
																					</div>
																				</li>
																				<?php
																			}
																			if ($key == $showNumber && $countCsrGood > $showNumber)
																			{
																				echo '</ul><a href="JavaScript:void(0)" onclick=atr_all("csrGood") id="showGoodCsr" class="btn btn-primary font-12 mt5 pl10 pr10 " >Show more</a></ul><ul class="hide" id="show_all_good_csr">';
																			}
																			?>
																		<?php } ?>
																	</ul>
																</div>
															</div>
														</div>
														<div class="col-12 col-md-6 col-lg-6 mb20">
															<div class="row">
																<div class="col-12"><b class="text-danger">What was not? <img src="/images/img-2022/sad.svg" alt="" width="20"></b></div>
																<div class="col-12">
																	<ul>
																		<?php
																		$count_csr_bad = count($dataArray['csr']['good']);
																		foreach ($dataArray['csr']['good'] as $key => $vb)
																		{
																			if (isset($vb['ratt_name_bad']) && $vb['ratt_name_bad'] != '')
																			{
																				?>
																				<li id="Ratings_csr_bad_li<?= $key ?>" class="">
																					<div class="checkbox">
																						<label class="containers">
																							<input type="checkbox" name="Ratings_csr_bad[]" class="checkbox-input" id="Ratings_csr_bad<?= $key ?>" value="<?= $vb['ratt_id'] ?>" onclick="checkBadBox('<?= $key ?>', 'Csr')"><?= $vb['ratt_name_bad'] ?>
																							<label for="checkbox1"></label>
																						</label>
																					</div>
																				</li>
																				<?php
																			}
																			if ($key == $showNumber && $count_csr_bad > $showNumber + 1)
																			{
																				//echo $key;
																				echo '</ul><a href="JavaScript:void(0)" onclick=atr_all("csrBad") id="showBadCsr" class="btn btn-primary font-12 mt5 pl10 pr10">Show more</a></ul><ul class="hide" id="show_all_bad_csr">';
																			}
																			?>
																		<?php } ?>
																	</ul>
																</div>
															</div>
														</div>
														<div class="col-12 mt-1">
															<div class="form-group mb5">
																<?= $form->textArea($model, 'rtg_csr_cmt', array('class' => 'form-control', 'placeholder' => 'Any other comments about Customer service')) ?>
																<?php echo $form->error($model, 'rtg_csr_cmt', ['class' => 'help-block error']); ?>
															</div>
															<div class="text-muted">You have <span id="charleftcount3">1000 characters left.</span> (Maximum characters: 1000)</div>
															<div id="errcharleftcount3" class="star-review text-muted">Max 1000 characters.</div> 

														</div>
													</div>
												</div>



												<div class="col-12 mt-1 mb10"><b><?= $model->getAttributeLabel('rtg_customer_car') ?></b></div>

												<div class="col-12 mt20 mb40">
													<div class="star-widget">
														<?php
														for ($car = 5; $car >= 1; $car--)
														{
															?>	
															<input onclick="checkcarrating($(this))" type="radio" name="Ratings[rtg_customer_car]" value="<?= $car ?>" id="customercar<?= $car ?>"><label for="customercar<?= $car ?>" class="bx bxs-star"></label>
														<?php } ?>
													</div>
												</div>
												<div id="carErr" class="star-review pl15">Please rate our Car Quality.</div>  


												<div class="col-12" id="carRatingBox">

													<div class="row list-7styled mt-1">

														<div class="col-12 col-md-6 col-lg-6 mb20">
															<b style="color: #8ACB9E;">What was good? <img src="/images/img-2022/happy-smiley.svg" alt="" width="20"></b>
															<ul class="mb0">
																<?php
																foreach ($dataArray['car']['good'] as $key => $vb)
																{
																	if (isset($vb['ratt_name']) && $vb['ratt_name'] != '')
																	{
																		?>
																		<li id="Ratings_car_good_li<?= $key ?>" class="">
																			<div class="checkbox">
																				<label class="containers">
																					<input type="checkbox" name="Ratings_car_good[]" class="checkbox-input" id="Ratings_car_good<?= $key ?>" value="<?= $vb['ratt_id'] ?>" onclick="checkGoodBox('<?= $key ?>', 'Car')"><?= $vb['ratt_name'] ?>
																					<label for="checkbox1"></label>
																				</label>
																			</div>
																		</li>
																		<?php
																	}
																	if ($key == $showNumber)
																	{
																		echo '</ul><a href="JavaScript:void(0)" onclick=atr_all("carGood") id="showGoodCar" class="btn btn-primary font-12 mt5 pl10 pr10">Show more</a></ul><ul class="hide" id="show_all_car">';
																	}
																	?>
																	<?php
																}
																?>
															</ul>
														</div>

														<div class="col-12 col-md-6 col-lg-6 mb20">
															<b class="text-danger">What was not? <img src="/images/img-2022/sad.svg" alt="" width="20"></b>
															<ul class="mb0">
																<?php
																$countBadCar = count($dataArray['car']['good']);
																foreach ($dataArray['car']['good'] as $key => $vb)
																{
																	if (isset($vb['ratt_name_bad']) && $vb['ratt_name_bad'] != '')
																	{
																		?>
																		<li id="Ratings_car_bad_li<?= $key ?>" class="">
																			<div class="checkbox">
																				<label class="containers">
																					<input type="checkbox" name="Ratings_car_bad[]" class="checkbox-input" id="Ratings_car_bad<?= $key ?>" value="<?= $vb['ratt_id'] ?>" onclick="checkBadBox('<?= $key ?>', 'Car')"><?= $vb['ratt_name_bad'] ?>
																					<label for="checkbox1"></label>
																				</label>
																			</div>

																		</li>
																		<?php
																	}
																	if ($key == $showNumber && $countBadCar > $showNumber)
																	{

																		echo '</ul><a href="JavaScript:void(0)" onclick=atr_all("carBad") id="showBadCar" class="btn btn-primary font-12 mt5 pl10 pr10">Show more</a></ul><ul class="hide" id="show_all_bad_car">';
																	}
																	?>
																<?php } ?>
															</ul>
														</div>

														<div class="col-12 mt-1">
															<div class="form-group">
																<?= $form->textArea($model, 'rtg_car_cmt', array('class' => 'form-control', 'placeholder' => 'Any other comments about Car experience')) ?>
																<?php echo $form->error($model, 'rtg_car_cmt', ['class' => 'help-block error']); ?>
															</div>
															<div class="text-muted">You have <span id="charleftcount2">1000 characters left.</span> (Maximum characters: 1000)</div>
															<div id="errcharleftcount2" class="star-review text-muted">Max 1000 characters.</div>
														</div>
													</div>
												</div>
											</div>
										</div>

												<div id="reviewErr" class="star-review pl15">At least say just a few words. Encouragement feels good and your feedback helps us to improve!</div>
												<div class="col-12 mt-1">
													<div class=""><?//= $model->getAttributeLabel('rtg_customer_review') ?></div>
													<?php if($model->rtg_customer_review != '' || $model->rtg_customer_review != null){ ?>
														<p class="font-14"><?= $model->rtg_customer_review; ?></p>
													<?php
													}else{
													echo $form->textArea($model, 'rtg_customer_review', array('class' => 'form-control', 'label' => '', 'widgetOptions' => array('htmlOptions' => array())))
													?>
													<span class="text-muted font-12">You have 1000 characters left. (Maximum characters: 1000)</span>
													<?php
													}
													?>
													
													
												</div>
												<div class="col-12 p15 text-center" id="DivSubmitRate" >
													<button class="btn btn-primary btn-lg <?=$hide?>" type="submit" value="Rate" tabindex="2" >Submit Review</button>
												</div>
							</div>
			<?php
			$this->endWidget();?>
			 </div>
			<?php
		//}
		?>
		<div class="row">
			<div class="col-12 reviewexist">

				<?php
				if ($model->rtg_customer_overall > 3)
				{

					$qrLink   = Yii::app()->createAbsoluteUrl('rating/downloadQrCode', ['userId' => $bkmodel->bkgUserInfo->bkg_user_id]);
					$whatappShareLink	 = urlencode($qrLink);
					?>
					<div class="row m0">
						<div class="col-12">
							<b class="font-14">Next time, book with your personal Gozo QR code </b><br>
							<div>Also ask your friends and family to use it. You get ₹100 when your friends travel with Gozo using your QR code.
							<div class="col-12 text-center font-12 pb10">
								<a href="<?= Yii::app()->createUrl('rating/downloadQrCode?userId='.$bkmodel->bkgUserInfo->bkg_user_id); ?>" class="btn btn-primary font-12 pl10 pr10 mr10">Download QR code</a> OR <a href="https://web.whatsapp.com/send?text=<?= $whatappShareLink ?>" data-action="share/whatsapp/share" target="_blank" class="ml10"><img src="<?= Yii::app()->createAbsoluteUrl('images/whatsapp.svg') ?>" width="30" class="p0"></a>	
							</div>	
							 Some Gozo customers stick this QR code in their office, their apartment buildings and nearby locations so people can book a Gozo with their QR code
							</div>

						</div>
						
					</div>
					<?php
				}
				else
				{
					?>
					<div class="p10 p115">
						<b>Our team will be contacting you shortly. </b>
						<div class="p15 rounded">
							We work hard to get a 5 star rating on every trip. Since you've given us a lower rating, 
							we have asked our customer advocacy team to get in touch with you so they can learn from you how we can do better.
						</div>
					</div>
					<?php
				}
				?> 
			</div>
		</div>
</div>
	<div class="col-12 col-xl-4 mb-1 qrCode p5">
		<div class="card" style="border: #f36e32 10px solid;">
			<div class="text-center font-36 bg-orange color-white pb10"><b>Need a cab?</b></div>
<div class="text-center font-11 weight500 pt5">Chauffeur driven AC cabs at the best possible prices</div>
<div class="text-center font-14 weight600 pt10">Local - Airport transfers & Daily rentals<br>
Outstation - One-way, Round Trips & more</div>
			<div class="card-body" style="margin: auto; position: relative;">
			<?php  $path = Users::getUserPathById($userId);
				   $qrModel = QrCode::model()->find('qrc_ent_id = :userid', array('userid' => $userId));
				   $qrcode = ($qrModel)?$qrModel->qrc_code:'';//AttachmentProcessing::ImagePath($modelUser->usr_qr_code_path); ?>
				<a href="<?= Yii::app()->createAbsoluteUrl('/users/GetQRCode') ?>"><img src='<?= $path ?>' alt='' style='width: 2.5in;'/></a>
<div style="position: absolute; top: 18px;right: 0px; font-weight: bold;width: 4px;letter-spacing: 1px;font-weight: 700;font-size: 11px;color: #1e1e1e;margin: 0 0 10px 0;line-height: 26px;writing-mode: vertical-rl!important;-webkit-writing-mode: vertical-rl!important;transform-origin: 0 0!important;text-align: center;margin-top: 81px;"><?= $qrcode; ?></div>
			</div>
			<?php 
				$usrModel = Users::model()->findByPk($bkmodel->bkgUserInfo->bkg_user_id);
				$filePath = Yii::app()->basePath . DIRECTORY_SEPARATOR . $usrModel->usr_qr_code_path;
			?>
<div class="row m0">
<div class="col-3 p5"><img src="/images/google-5-Stars.jpg" alt="Google review" width="55"></div>
<div class="col-6 p5"><img src="/images/gozo-white-cabs.svg" alt="aaocab:India's leader in inter-city taxi | Great service. Price guarantee. Awesome reviews." title="aaocab:India's leader in inter-city taxi | Great service. Price guarantee. Awesome reviews." width="140"></div>
<div class="col-3 p5 text-right"><img src="/images/tripadvisor-certificato.jpg" alt="Tripadvisor Certificato" width="55"></div>

<div class="col-12 text-center p0 pt10 font-11 weight500 bg-orange color-white pb0">
30+ million kilometres each year<br>Easy | Reliable | Affordable | Safe | Everywhere in India
</div>
</div>
		</div>
	</div>
</div>
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
		var isReviewExist = '<?= $model->rtg_id ?>';
		if(isReviewExist == '')
		{
			$('.reviewexist').addClass('hide');
			$('.qrCode').hide();
		}
		else{
			$('.reviewexist').removeClass('hide');
			$('#DivSubmitRate').addClass('hide');
		}
		var customerrecommend = '<?= $model->rtg_customer_recommend ?>';
		var customeraverall	  = '<?= $model->rtg_customer_overall ?>';
		
		$('.qrCode').hide();
		if(customeraverall > 3)
		{
			 $('.qrCode').show();
		}
		for (var $i = customerrecommend; $i >= 1; $i--)
		{
			$('#rate'+$i).addClass('star-rating-on');
			 $('#rate'+customerrecommend).prop('checked', true);
		}
		
		for (var $i = customeraverall; $i >= 1; $i--)
		{
			//$('#star'+$i).addClass('star-rating-on');
			 $('#star'+customeraverall).prop('checked', true);
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
	
	function customerRating()
	{
		var error = validateForm();
		if (error == 0)
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
                    if (data1.result == true)
                    {
						location.reload(true);
                    }
                }
            });
        }
	}

    function validateForm() {
        var $error = 0;
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
		
        var custReview = $('#Ratings_rtg_customer_review').val();
        custReview = findAndReplace(custReview, " ", "");
        if (custReview != '' && custReview.length > 1) {
            $('#reviewErr').hide();
            $error += 0;

        } else
        {
            $('#reviewErr').show();
            $error += 1;
        }

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
		
		return $error;
    }

    function checkrating(obj) {
        $rate = obj.val();
		var recratingid = '<?= $model->rtg_id ?>'; 
		if(recratingid != '')
		{
			return false;
		}
        if ($rate > 0)
        {
            $('#otherrating').show();
			$("input[name='Ratings[rtg_customer_overall]']").removeClass('star-rating-on');
			for (var $i = $rate; $i >= 1; $i--)
			{
				$('#star'+$i).addClass('star-rating-on');
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
				$('#rate'+$i).addClass('star-rating-on');
			}
            $('#recommendErr').hide();
        }
    }
    function checkcarrating(obj) {
        $rate = obj.val();
        if ($rate != '')
        {
			$("input[name='Ratings[rtg_customer_car]']").removeClass('star-rating-on');
			if($rate > 0)
			{
				for (var $i = $rate; $i >= 1; $i--)
				{
					$('#customercar'+$i).addClass('star-rating-on');
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
			if($rate > 0)
			{
				for (var $i = $rate; $i >= 1; $i--)
				{
					$('#customercsr'+$i).addClass('star-rating-on');
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
			if($rate > 0)
			{
				for (var $i = $rate; $i >= 1; $i--)
				{
					$('#customerdriver'+$i).addClass('star-rating-on');
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

</script>								 