<style>
    .profile-imgbox{
        -webkit-border-radius: 100px;
        -moz-border-radius: 100px;
        border-radius: 100px;
        width: 32px;
        height: 32px;
        overflow: hidden;
        display: inline-block;
    }
    .profile-imgbox img{ width: 100%;}
	.not-available-msg{
        font-variant: small-caps;
        color: #d46767;
		text-align: center;
    }
	.create-fs-msg {
		font-variant: small-caps;
        color: #fff;
	}
	.flexxi-time{ border:#0d47a1 1px solid; padding: 8px; height: 130px;float: left; margin:0 5px 10px 5px; border-radius: 3px; background-color: #f9f9f9;
				  color: #000;}
	.flexxi-left-panel{ background: #346bc2; padding: 15px; min-height: 560px;}
	.flexxi-right-panel{ background: #fa7c46; padding: 15px; color: #fff; min-height: 560px;}
	.flexxi-right-panel label{ color: #fff;}
	.flexxi-right-panel .form-control{ border: none;}
	</style>
	<?php
	$ptime	 = date('h:i A', strtotime('6am'));
//$model->bkg_pickup_date_time = $ptime;
	$timeArr = Filter::getTimeDropArr($ptime);
	$count	 = -1;
	?>
	<?php
	$form	 = $this->beginWidget('booster.widgets.TbActiveForm', array(
		'id'					 => 'flexxiSearch-form1',
		'enableClientValidation' => true,
		'clientOptions'			 => array(
			'validateOnSubmit'	 => true,
			'errorCssClass'		 => 'has-error',
			'afterValidate'		 => 'js:function(form,data,hasError){
				if(!hasError){
					$.ajax({
						"type":"POST",

						"dataType":"json",
						"url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('booking/bookflexislots')) . '",
						"data":form.serialize(),
                                                "beforeSend": function(){
                                     //               ajaxindicatorstart("");
                                                },
                                                "complete": function(){
                          //                          ajaxindicatorstop();
                                                },
						"success":function(data2){

							if(data2.success){
                                bookNow.sendQuoteToInfo(114);
								trackPage(\'' . CHtml::normalizeUrl(Yii::app()->createUrl('booking/additionaldetail')) . '\');
							}
							else
							{
								var errors = data2.errors;
								settings=form.data(\'settings\');
								$.each (settings.attributes, function (i) {
									$.fn.yiiactiveform.updateInput (settings.attributes[i], errors, form);
								});
								$.fn.yiiactiveform.updateSummary(form, errors);
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
		'enableAjaxValidation'	 => false,
		'errorMessageCssClass'	 => 'help-block',
		'htmlOptions'			 => array(
			//			'onsubmit' => "return false;", /* Disable normal form submit */
			//			'onkeypress' => "validateForm1();",
			'class'			 => 'form-horizontal',
			'autocomplete'	 => 'off',
		),
	));

	/* @var $form TbActiveForm */
	$form->attributes = $model->attributes;
	?>
	<?= $form->errorSummary($model); ?>
	<?= CHtml::errorSummary($model); ?>
	<div class="panel">            
    <div class="panel-body pt0 pb0 p0">   
		<?= $form->hiddenField($model, 'bkg_from_city_id') ?>
		<?= $form->hiddenField($model, 'bkg_to_city_id') ?>
		<?= $form->hiddenField($model, 'bkg_id'); ?>
		<?= $form->hiddenField($model, 'hash'); ?>
		<?= $form->hiddenField($model, 'bkg_flexxi_type'); ?>    
		<?= $form->hiddenField($model, 'bkg_vehicle_type_id') ?>
		<?= $form->hiddenField($model, 'bkg_no_person') ?>
		<?= $form->hiddenField($model, 'bkg_num_large_bag') ?>
		<?= $form->hiddenField($model, 'bkg_num_small_bag') ?>
		<?= $form->hiddenField($model, 'bkg_fp_id') ?>
        <input type="hidden" id="usr_gender">
        <input type="hidden" name="step" value="3">
		<input type="hidden" name="pickUpTime" id="pickUpTime"/>
		<input type="hidden" name="pickUpDateTime" id="pickUpDateTime"/>
		<input type="hidden" name="timeslot" id="timeSlot"/>
    </div>
</div>
<?
if ($results != [])
{

	$exist = false;
	foreach ($results as $cab)
	{
		if ($cab['bkg_fp_id'] == '' || $cab['bkg_fp_id'] == null)
		{
			if (in_array($cab['bkg_flexxi_type'], [1, 2]))
			//if ($cab['bkg_flexxi_type'] != 2)
			{
				$exist			 = true;
				?>
				<div class="col-xs-12 search-cabs-box mb30 hidden-xs">
					<div class="row">
						<div class="col-xs-12 col-sm-3 border-rightnew">
							<div class=""></div>
							<div class="car-style"><?= $cab['vct_label'] ?></div>
							<div class="car_box"><img src="<?= Yii::app()->baseUrl . '/' . $cab['vct_image'] ?>" alt=" " ></div>
							<p class="text-center" style="line-height:16px;"><?= $cab['vct_desc'] ?></p>
						</div>
						<div class="col-xs-12 col-sm-9">
							<div class="row p10">
								<div class="col-xs-12 col-sm-9 mobile-view-p border-lt">
									<div class="search-icon-box">
										<img src="/images/calendar_icon.png" alt="DateTime" title="Date And Time"><br>
										Leaving On <br><?= DateTimeFormat::DateTimeToDatePicker($cab['bkg_pickup_date']) . "<br>" . DateTimeFormat::DateTimeToTimePicker($cab['bkg_pickup_date']) ?> 
									</div>
									<div class="search-icon-box">
										<img src="/images/search_icon_1.png" alt="Capacity" title="Capacity"><br>
										<?= $cab['remainingSeats'] ?> Seats Left
									</div>
									<div class="search-icon-box">
										<img src="/images/search_icon_2.png" alt="Luggage Capacity" title="Luggage Capacity"><br>
										<?= $cab['remainingLargebags'] ?> big bags + <?= $cab['remainingSmallbags'] ?> small bags left
									</div>

									<div class="search-icon-box">
										<img src="/images/search_icon_5.png" alt="KM in Quote" title="KM in Quote"><br>
										KM in Quote <br> <?= $cab['bkg_trip_distance'] ?> Km
									</div>
									<div class="row">
										<div class="col-xs-12 font11">
											<?= $taxStr ?>
										</div>
									</div>
									<div class="row">
										<div class="col-xs-12 font11">
											<?
											$userProfilePic	 = $cab['usr_profile_pic'];
											if ($cab['usr_profile_pic_path'] != '')
											{
												$userProfilePic = $cab['usr_profile_pic_path'];
											}
											?>
											You will be Sharing with <span class="profile-imgbox"><img src="<?= $userProfilePic ?>"></span><b><span style="font-size: 13px;color: #009688"><?= $cab['custname'] ?></span><span style="font-size: 13px;color: #F00"><?
													if ($cab['gender'] != "")
													{
														if ($cab['gender'] == 1)
														{
															echo ", this FLEXXI share is offered to Male passengers only";
														}
														else
														{
															echo ", this FLEXXI share is offered to Female passengers only";
														}
													}
													?></span></b>	 
										</div>
									</div>
								</div>

								<div class="col-xs-12 col-sm-3 search-icon-box2 pl0 pr0">

									<div class="row pt10">
										<div class="col-xs-12">
											<h4 class="m0 text-uppercase">Base Fare</h4>
											<span style="font-size: 26px; color: #2458aa; line-height: normal; font-weight: bold;">
												<i class="fa fa-inr"></i><?= $cab['subs_fare'] ?>
											</span><br>
											<span class=" small_text hide">(Approx.)</span>
											<button type="button" booktype="flexxi" gender="<?= $cab['gender'] ?>" vct_id="<?= $cab['bkg_vehicle_type_id'] ?>" pickupdate="<?= $cab['bkg_pickup_date'] ?>" remainingseats="<?= $cab['remainingSeats'] ?>" remainingbigbags="<?= $cab['remainingLargebags'] ?>" remainingsmallbags="<?= $cab['remainingSmallbags'] ?>" value="<?= $cab['bkg_id'] ?>" kmr="<?= $quote->routeRates->ratePerKM; ?>" pickupTime="" timeslot="" name="bookButton" class="btn next3-btn mt10" onclick="validateForm2(this);"><b>Book</b></button><!--working BOOK button !-->
										</div>
									</div>

								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xs-12 search-cabs-box mb30 hidden-sm hidden-md hidden-lg">
					<div class="row">
						<div class="car-style"><?= $cab['vct_label'] ?></div>
						<div class="col-xs-6">
							<div class="car_box"><img src="<?= Yii::app()->baseUrl . '/' . $cab['vct_image'] ?>" alt="" ></div>
							<p class="text-center" style="line-height:16px;"><?= $cab['vct_desc'] ?></p>
						</div>

						<div class="col-xs-6 search-icon-box2 pl0">
							<?
							if ($quote->routeRates->discFare != '' && $isPromo)
							{
								?>
								<span style="font-size: 13px; color: #7c7c7c;">Base Fare: <del><i class="fa fa-inr"></i><?= $quote->routeRates->baseAmount ?></del></span><br>
								<span style="font-size: 24px; line-height: normal; font-weight: bold;"><?= $quote->routeRates->discFare; ?></span><br>
								<?
							}
							else
							{
								?>
								<div class="row pt10">
									<div class="col-xs-12">
										<h4 class="m0 text-uppercase">Base Fare</h4>
										<span style="font-size: 26px; color: #2458aa; line-height: normal; font-weight: bold;">
											<i class="fa fa-inr"></i><?= $cab['subs_fare']; ?>
										</span><br>
										<span class=" small_text hide">(Approx.)</span>
									</div>

								</div>
							<? } ?>


							<button type="button" booktype='flexxi' gender="<?= $cab['gender'] ?>" vct_id="<?= $cab['bkg_vehicle_type_id'] ?>" remainingseats="<?= $cab['remainingSeats'] ?>" remainingbigbags="<?= $cab['remainingLargebags'] ?>" remainingsmallbags="<?= $cab['remainingSmallbags'] ?>" value="<?= $cab['bkg_id'] ?>" kmr="<?= $quote->routeRates->ratePerKM; ?>" pickupTime="" timeslot="" name="bookButton" class="btn next3-btn mt10" onclick="validateForm2(this);"><b>Book</b></button>


							<div class="col-xs-12 col-sm-9 border-lefttnew">
								<div class="row sch-in-bxmain">
									<ul>
										<li class="col-xs-3 search-icon-boxview">
											<span class="font-styles"><?= $cab['remainingSeats'] ?></span><br>Seats Left
										</li>
										<li class="col-xs-3 search-icon-boxview">
											<span class="font-styles"><?= $cab['remainingLargebags'] ?> + <?= $cab['remainingSmallbags'] ?></span><br>Big + Small Bag Left
										</li>
										<li class="col-xs-3 search-icon-boxview">
											<span class="font-styles">AC</span>
										</li>
										<li class="col-xs-3 search-icon-boxview">
											<span class="font-styles"><?= $quote->routeDistance->quotedDistance ?> </span><br>KM in Quote
										</li>
									</ul>

									<div class="col-xs-12 col-sm-9 list-views">
										<div class="col-xs-12 font11">
											You will be Sharing with <b><?= $cab['custname'] ?></b> 
										</div>
									</div>


								</div>
							</div>
						</div>
					</div>
				</div>
				<?
			}
		}
	}
		if (!$exist)
		{
			?>
			<div class="row">
				<div class="col-xs-12">
					<h4 class="text-uppercase text-center" id="primaryTxt">There are no existing riders who have offered to share their taxi on this route. You have 2 options</h4>
					<div class="row mt40">
						<div class="col-xs-12 col-sm-8 mb10 pl0 text-center" id="slotDiv">
							<h4 class="create-fs-msg text-left pl10 mt0" id="secondryTxt">Book your cab for any of the time slots below. If no co-rider is found, we will auto-cancel your booking for ZERO CANCELLATION FEES.</h4>
							<?php
										/** @var BookingTemp $model */
							$fromDate		 = DateTimeFormat::DatePickerToDate($model->locale_from_date) . " " . date('H:i:s', strtotime($model->locale_from_time));
							$fromDateTime	 = date('Y-m-d H:i:s', strtotime($fromDate));
							$toDate			 = DateTimeFormat::DatePickerToDate($model->locale_to_date) . " " . date('H:i:s', strtotime($model->locale_to_time));
							$toDateTime		 = date('Y-m-d H:i:s', strtotime($toDate));
							$hourdiff		 = round((strtotime($toDateTime) - strtotime($fromDateTime)) / 3600, 1);
							if ($hourdiff >= 0)
							{
								$count = 0;
								for ($i = $hourdiff >= 0 && $hourdiff < 3 ? $hourdiff : 0; $i <= $hourdiff; $i += 3)
								{
									$j = 3;
									if ($hourdiff >= 0 && $hourdiff < 3)
									{
										$i			 = 0;
										$hourdiff	 = -1;
									}
									$firstDate		 = date('Y-m-d', strtotime($fromDateTime . '+' . $i . 'hour'));
									$secondDate		 = date('Y-m-d', strtotime($fromDateTime . '+' . ($i + 24) . 'hour'));
									$firstDateTime	 = $firstDate . " 21:00:00";
									$secondDateTime	 = $secondDate . " 06:00:00";
									if (strtotime($fromDateTime) <= strtotime($firstDateTime) && strtotime($fromDateTime . '+' . $i . 'hour') >= strtotime($firstDate . " 06:00:00") && strtotime($fromDateTime) < strtotime($secondDateTime) && strtotime($fromDateTime . '+' . $i . 'hour') <= strtotime($firstDateTime) && $timeSlot != '18:00:00-21:00:00')
									{
										if (strtotime($fromDateTime . '+' . ($i + $j) . ' hour') > strtotime($firstDateTime) && strtotime($fromDateTime) <= strtotime($firstDateTime) && strtotime($toDateTime) > strtotime($firstDateTime . '-3 hour'))
										{
											$fromDateTimeNight	 = $firstDate . ' 18:00:00';
											$pickDate			 = date('Y-m-d', strtotime($fromDateTimeNight));
											$daydiff			 = round((strtotime($fromDateTimeNight . '+3 hour') - strtotime($fromDateTimeNight)) / 2, 1);
											$selectedTime		 = date('H:i:s', strtotime($fromDateTimeNight . '+' . $daydiff . ' SECOND'));
											$time				 = date('h:i A', strtotime('18:00:00')) . '-' . date('h:i A', strtotime('21:00:00'));
											$timeSlot			 = date('H:i:s', strtotime('18:00:00')) . '-' . date('H:i:s', strtotime('21:00:00'));
											$pickupDate			 = $pickDate . ' ' . $selectedTime;
										}
										else if (strtotime($fromDateTime . '+' . ($i + $j) . ' hour') <= strtotime($firstDateTime) && strtotime($fromDateTime . '+' . $i . 'hour') >= strtotime($firstDate . " 06:00:00"))
										{
											$pickDate		 = date('Y-m-d', strtotime($fromDateTime . '+' . $i . ' hour'));
											$selectedTime	 = date('H:i:s', strtotime($fromDateTime . '+' . (($i * 60) + 90) . ' MINUTE'));
											$time			 = date('h:i A', strtotime($fromDateTime . '+' . $i . ' hour')) . '-' . date('h:i A', strtotime($fromDateTime . '+' . ($i + $j) . ' hour'));
											$timeSlot		 = date('H:i:s', strtotime($fromDateTime . '+' . $i . ' hour')) . '-' . date('H:i:s', strtotime($fromDateTime . '+' . ($i + $j) . ' hour'));
											$pickupDate		 = $pickDate . ' ' . $selectedTime;
										}
										?>
										<div class="text-center pl5 pr5">
											<div class="flexxi-time">
												<p style="font-weight:bold;font-size: 10px;/*padding: 12px 0px;*/color: #62656b; margin-bottom: 0;"><?= date('d-m-Y', strtotime($pickDate)) ?></p>
												<p style="font-weight:bold; font-size: 13px; padding: 4px 0px; color: #000;"><?= $time ?></p>
												<button type="button" booktype='flexxi' gender="" vct_id="" remainingseats="" remainingbigbags="" remainingsmallbags="" value="" kmr="<?= $quote->routeRates->ratePerKM; ?>" pickupTime="<?= $selectedTime ?>" timeslot="<?= $timeSlot ?>" pickupDate="<?= $pickupDate ?>" name="bookButton" class="btn next3-btn" onclick="validateForm2(this);">Book Now</button>
											</div>
										</div>
										<?php
										$count += 1;
									}
									else
									{
										$timeSlot = '';
									}
								}
								?>
							</div>
							<div class="col-xs-12 col-sm-4">
								<div class="flexxi-right-panel">
									<span class="h4 white-color">
										We will notify you if anyone is looking for a co-rider between your dates of interest on this route.
									</span>
									<div class="row">
										<div class="col-xs-12 col-sm-12">
											<label for="inputName" class="control-label">First Name:</label>
											<?= $form->textFieldGroup($model, 'bkg_user_name', array('label' => '', 'placeholder' => "First Name", 'class' => 'form-control', 'groupOptions' => ['style' => 'margin-left:0px;margin-right:0px;'])) ?>
										</div>
										<div class="col-xs-12 col-sm-12">
											<label for="inputName" class="control-label">Last Name:</label>
											<?= $form->textFieldGroup($model, 'bkg_user_lname', array('label' => '', 'placeholder' => "Last Name", 'class' => 'form-control', 'groupOptions' => ['style' => 'margin-left:0px;margin-right:0px;'])) ?>
										</div>
										<div class="col-xs-12">
											<label for="inputEmail" class="control-label">Email address:</label>
											<?= $form->emailFieldGroup($model, 'bkg_user_email', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Email Address", 'id' => CHtml::activeId($model, "bkg_user_email2")]), 'groupOptions' => ['class' => '', 'style' => 'margin-left:0px;margin-right:0px;'])) ?>                      
										</div>
										<div class="col-xs-12 col-sm-12">
											<h5>Departing Between:</h5>
											<div class="col-xs-6">
												<?
												$defaultDate	 = date('Y-m-d H:i:s', strtotime('+7 days'));
												$defaultRDate	 = date('Y-m-d H:i:s', strtotime('+8 days'));
												$minDate		 = date('Y-m-d H:i:s', strtotime('+4 hour'));
												$pdate			 = ($model->locale_from_date == '') ? DateTimeFormat::DateTimeToDatePicker($defaultDate) : $model->locale_from_date;
												$pdate			 = DateTimeFormat::DatePickerToDate($pdate);
												$currentDate	 = date('Y-m-d');
												$daydiff		 = round(round((strtotime($pdate) - strtotime($currentDate)) / (3600 * 24), 1) / 2);
												if ($pdate <= $currentDate)
												{
													$pdate = date('d/m/Y', strtotime($pdate));
												}
												else if ($model->bkg_flexxi_quick_booking == 1)
												{
													$pdate = date('d/m/Y', strtotime($pdate));
												}
												else
												{
													$pdate = date('d/m/Y', strtotime($pdate . '-1 days'));
												}
												?>
												<?=
												$form->datePickerGroup($model, 'bkg_pickup_date_date', array('label'			 => '', 'widgetOptions'	 => array('options'		 => array('autoclose'	 => true, 'startDate'	 => $minDate,
															'format'	 => 'dd/mm/yyyy'), 'htmlOptions'	 => ['placeholder' => "Departure Date", 'value' => $pdate, 'id' => "BookingTemp_bkg_pickup_date_date_mf12", 'required'])))
												?>    


											</div>
											<div class="col-xs-6">
												<? //= $form->timePickerGroup($model, 'bkg_pickup_date_time', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Departure Time", 'id' => "BookingTemp_bkg_pickup_date_time_mf1"]))) ?>
												<div class="input-group timer-control">
													<?
													echo $form->dropDownList($model, 'bkg_pickup_date_time', $timeArr, ['id' => 'bkg_pickup_date_time_mf12', 'class' => 'form-control', 'required']);
													?> 
												</div>
											</div>
										</div>
										<div class="col-xs-12 col-sm-12">
											<div class="col-xs-6">
												<?
												$defaultDate	 = date('Y-m-d H:i:s', strtotime('+7 days'));
												$defaultRDate	 = date('Y-m-d H:i:s', strtotime('+8 days'));
												$minDate		 = date('Y-m-d H:i:s', strtotime('+4 hour'));
												$pdate			 = ($model->locale_from_date == '') ? DateTimeFormat::DateTimeToDatePicker($defaultDate) : $model->locale_from_date;
												$pdate			 = DateTimeFormat::DatePickerToDate($pdate);
												$currentDate	 = date('Y-m-d');
												$daydiff		 = round(round((strtotime($pdate) - strtotime($currentDate)) / (3600 * 24), 1) / 2);
												if ($model->bkg_flexxi_quick_booking == 1)
												{
													$pdate = date('d/m/Y', strtotime($pdate));
												}
												else
												{
													$pdate = date('d/m/Y', strtotime($pdate . '+1 days'));
												}
												?>
												<?=
												$form->datePickerGroup($model, 'bkg_pickup_date_date1', array('label'			 => '', 'widgetOptions'	 => array('options'		 => array('autoclose'	 => true, 'startDate'	 => $minDate,
															'format'	 => 'dd/mm/yyyy'), 'htmlOptions'	 => ['placeholder' => "Departure Date", 'value' => $pdate, 'id' => "BookingTemp_bkg_pickup_date_date_mf22", 'required'])))
												?>  

											</div>
											<div class="col-xs-6">
												<div class="input-group timer-control">
													<?
													echo $form->dropDownList($model, 'bkg_pickup_date_time1', $timeArr, ['id' => 'bkg_pickup_date_time_mf22', 'class' => 'form-control', 'required']);
													?> 
												</div>
											</div>
										</div>
										<div class="col-xs-12 col-sm-12">
											<label class="control-label">Source City:</label>
											<label class="control-label"><?= Cities::getDisplayName($model->bkg_from_city_id) ?></label>
										</div>
										<div class="col-xs-12 col-sm-12">
											<label class="control-label">Destination City:</label>
											<label class="control-label"><?= Cities::getDisplayName($model->bkg_to_city_id) ?></label>
										</div>
										<div class="col-xs-12 col-sm-12 text-center mt10">
											<?= CHtml::button('Create Alert', array('class' => 'btn next-btn', 'onclick' => 'notify()')); ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>   
				<?
			}
		}
	}
	else
	{
		if ($model->bkg_id != '')
		{
			?>

			<div class="row">
				<div class="col-xs-12">
					<h4 class="text-uppercase text-center" id="primaryTxt">There are no existing riders who have offered to share their taxi on this route. You have 2 options</h4>
					<div class="row mt40">
						<div class="col-xs-12 col-sm-8 mb10 pl0 text-center flexxi-left-panel" id="slotDiv">
							<h4 class="create-fs-msg text-left pl10 mt0" id="secondryTxt">Book your cab for any of the time slots below. If no co-rider is found, we will auto-cancel your booking for ZERO CANCELLATION FEES.</h4>
							<?php
							$fromDate		 = DateTimeFormat::DatePickerToDate($model->locale_from_date) . " " . date('H:i:s', strtotime($model->locale_from_time));
							$fromDateTime	 = date('Y-m-d H:i:s', strtotime($fromDate));
							$toDate			 = DateTimeFormat::DatePickerToDate($model->locale_to_date) . " " . date('H:i:s', strtotime($model->locale_to_time));
							$toDateTime		 = date('Y-m-d H:i:s', strtotime($toDate));
							$hourdiff		 = round((strtotime($toDateTime) - strtotime($fromDateTime)) / 3600, 1);
							if ($hourdiff >= 0)
							{
								$count = 0;
								for ($i = $hourdiff >= 0 && $hourdiff < 3 ? $hourdiff : 0; $i <= $hourdiff; $i += 3)
								{
									$j = 3;
									if ($hourdiff >= 0 && $hourdiff < 3)
									{
										$i			 = 0;
										$hourdiff	 = -1;
									}
									$firstDate		 = date('Y-m-d', strtotime($fromDateTime . '+' . $i . 'hour'));
									$secondDate		 = date('Y-m-d', strtotime($fromDateTime . '+' . ($i + 24) . 'hour'));
									$firstDateTime	 = $firstDate . " 21:00:00";
									$secondDateTime	 = $secondDate . " 06:00:00";
									if (strtotime($fromDateTime) <= strtotime($firstDateTime) && strtotime($fromDateTime . '+' . $i . 'hour') >= strtotime($firstDate . " 06:00:00") && strtotime($fromDateTime) < strtotime($secondDateTime) && strtotime($fromDateTime . '+' . $i . 'hour') <= strtotime($firstDateTime) && $timeSlot != '18:00:00-21:00:00')
									{
										if (strtotime($fromDateTime . '+' . ($i + $j) . ' hour') > strtotime($firstDateTime) && strtotime($fromDateTime) <= strtotime($firstDateTime) && strtotime($toDateTime) > strtotime($firstDateTime . '-3 hour'))
										{
											$fromDateTimeNight	 = $firstDate . ' 18:00:00';
											$pickDate			 = date('Y-m-d', strtotime($fromDateTimeNight));
											$daydiff			 = round((strtotime($fromDateTimeNight . '+3 hour') - strtotime($fromDateTimeNight)) / 2, 1);
											$selectedTime		 = date('H:i:s', strtotime($fromDateTimeNight . '+' . $daydiff . ' SECOND'));
											$time				 = date('h:i A', strtotime('18:00:00')) . '-' . date('h:i A', strtotime('21:00:00'));
											$timeSlot			 = date('H:i:s', strtotime('18:00:00')) . '-' . date('H:i:s', strtotime('21:00:00'));
											$pickupDate			 = $pickDate . ' ' . $selectedTime;
										}
										else if (strtotime($fromDateTime . '+' . ($i + $j) . ' hour') <= strtotime($firstDateTime) && strtotime($fromDateTime . '+' . $i . 'hour') >= strtotime($firstDate . " 06:00:00"))
										{
											$pickDate		 = date('Y-m-d', strtotime($fromDateTime . '+' . $i . ' hour'));
											$selectedTime	 = date('H:i:s', strtotime($fromDateTime . '+' . (($i * 60) + 90) . ' MINUTE'));
											$time			 = date('h:i A', strtotime($fromDateTime . '+' . $i . ' hour')) . '-' . date('h:i A', strtotime($fromDateTime . '+' . ($i + $j) . ' hour'));
											$timeSlot		 = date('H:i:s', strtotime($fromDateTime . '+' . $i . ' hour')) . '-' . date('H:i:s', strtotime($fromDateTime . '+' . ($i + $j) . ' hour'));
											$pickupDate		 = $pickDate . ' ' . $selectedTime;
										}
										?>
										<div class="text-center pl5 pr5">
											<div class="flexxi-time">
												<p style="font-weight:bold;font-size: 10px;/*padding: 12px 0px;*/color: #62656b; margin-bottom: 0;"><?= date('d-m-Y', strtotime($pickDate)) ?></p>
												<p style="font-weight:bold; font-size: 13px; padding: 4px 0px; color: #000;"><?= $time ?></p>
												<button type="button" booktype='flexxi' gender="" vct_id="" remainingseats="" remainingbigbags="" remainingsmallbags="" value="" kmr="<?= $quote->routeRates->ratePerKM; ?>" pickupTime="<?= $selectedTime ?>" timeslot="<?= $timeSlot ?>" pickupDate="<?= $pickupDate ?>" name="bookButton" class="btn next3-btn" onclick="validateForm2(this);">Book Now</button>
											</div>
										</div>
										<?php
										$count += 1;
									}
									else
									{
										$timeSlot = '';
									}
								}
								?>
							</div>
							<div class="col-xs-12 col-sm-4">
								<div class="flexxi-right-panel">
									<span class="h4 white-color">
										We will notify you if anyone is looking for a co-rider between your dates of interest on this route.
									</span>
									<div class="row">
										<div class="col-xs-12 col-sm-12">
											<label for="inputName" class="control-label">First Name:</label>
											<?= $form->textFieldGroup($model, 'bkg_user_name', array('label' => '', 'placeholder' => "First Name", 'class' => 'form-control', 'groupOptions' => ['style' => 'margin-left:0px;margin-right:0px;'])) ?>
										</div>
										<div class="col-xs-12 col-sm-12">
											<label for="inputName" class="control-label">Last Name:</label>
											<?= $form->textFieldGroup($model, 'bkg_user_lname', array('label' => '', 'placeholder' => "Last Name", 'class' => 'form-control', 'groupOptions' => ['style' => 'margin-left:0px;margin-right:0px;'])) ?>
										</div>
										<div class="col-xs-12">
											<label for="inputEmail" class="control-label">Email address:</label>
											<?= $form->emailFieldGroup($model, 'bkg_user_email', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Email Address", 'id' => CHtml::activeId($model, "bkg_user_email2")]), 'groupOptions' => ['class' => '', 'style' => 'margin-left:0px;margin-right:0px;'])) ?>                      
										</div>
										<div class="col-xs-12 col-sm-12">
											<h5>Departing Between:</h5>
											<div class="col-xs-6">
												<?
												$defaultDate	 = date('Y-m-d H:i:s', strtotime('+7 days'));
												$defaultRDate	 = date('Y-m-d H:i:s', strtotime('+8 days'));
												$minDate		 = date('Y-m-d H:i:s', strtotime('+4 hour'));
												$pdate			 = ($model->bkg_pickup_date_date == '') ? DateTimeFormat::DateTimeToDatePicker($defaultDate) : $model->bkg_pickup_date_date;
												$pdate			 = DateTimeFormat::DatePickerToDate($pdate);
												$currentDate	 = date('Y-m-d');
												$daydiff		 = round(round((strtotime($pdate) - strtotime($currentDate)) / (3600 * 24), 1) / 2);
												if ($pdate <= $currentDate)
												{
													$pdate = date('d/m/Y', strtotime($pdate));
												}
												else if ($model->bkg_flexxi_quick_booking == 1)
												{
													$pdate = date('d/m/Y', strtotime($pdate));
												}
												else
												{
													$pdate = date('d/m/Y', strtotime($pdate . '-1 days'));
												}
												?>
												<?=
												$form->datePickerGroup($model, 'bkg_pickup_date_date', array('label'			 => '', 'widgetOptions'	 => array('options'		 => array('autoclose'	 => true, 'startDate'	 => $minDate,
															'format'	 => 'dd/mm/yyyy'), 'htmlOptions'	 => ['placeholder' => "Departure Date", 'value' => $pdate, 'id' => "BookingTemp_bkg_pickup_date_date_mf12", 'required'])))
												?>    


											</div>
											<div class="col-xs-6">
												<? //= $form->timePickerGroup($model, 'bkg_pickup_date_time', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Departure Time", 'id' => "BookingTemp_bkg_pickup_date_time_mf1"]))) ?>
												<div class="input-group timer-control">
													<?
													echo $form->dropDownList($model, 'bkg_pickup_date_time', $timeArr, ['id' => 'bkg_pickup_date_time_mf12', 'class' => 'form-control', 'required']);
													?> 
												</div>
											</div>
										</div>
										<div class="col-xs-12 col-sm-12">
											<div class="col-xs-6">
												<?
												$defaultDate	 = date('Y-m-d H:i:s', strtotime('+7 days'));
												$defaultRDate	 = date('Y-m-d H:i:s', strtotime('+8 days'));
												$minDate		 = date('Y-m-d H:i:s', strtotime('+4 hour'));
												$pdate			 = ($model->bkg_pickup_date_date == '') ? DateTimeFormat::DateTimeToDatePicker($defaultDate) : $model->bkg_pickup_date_date;
												$pdate			 = DateTimeFormat::DatePickerToDate($pdate);
												$currentDate	 = date('Y-m-d');
												$daydiff		 = round(round((strtotime($pdate) - strtotime($currentDate)) / (3600 * 24), 1) / 2);
												if ($model->bkg_flexxi_quick_booking == 1)
												{
													$pdate = date('d/m/Y', strtotime($pdate));
												}
												else
												{
													$pdate = date('d/m/Y', strtotime($pdate . '+1 days'));
												}
												?>
												<?=
												$form->datePickerGroup($model, 'bkg_pickup_date_date1', array('label'			 => '', 'widgetOptions'	 => array('options'		 => array('autoclose'	 => true, 'startDate'	 => $minDate,
															'format'	 => 'dd/mm/yyyy'), 'htmlOptions'	 => ['placeholder' => "Departure Date", 'value' => $pdate, 'id' => "BookingTemp_bkg_pickup_date_date_mf22", 'required'])))
												?>  

											</div>
											<div class="col-xs-6">
												<div class="input-group timer-control">
													<?
													echo $form->dropDownList($model, 'bkg_pickup_date_time1', $timeArr, ['id' => 'bkg_pickup_date_time_mf22', 'class' => 'form-control', 'required']);
													?> 
												</div>
											</div>
										</div>
										<div class="col-xs-12 col-sm-12">
											<label class="control-label">Source City:</label>
											<label class="control-label"><?= Cities::getDisplayName($model->bkg_from_city_id) ?></label>
										</div>
										<div class="col-xs-12 col-sm-12">
											<label class="control-label">Destination City:</label>
											<label class="control-label"><?= Cities::getDisplayName($model->bkg_to_city_id) ?></label>
										</div>
										<div class="col-xs-12 col-sm-12 text-center mt10">
											<?= CHtml::button('Create Alert', array('class' => 'btn next-btn', 'onclick' => 'notify()')); ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?
			}
		}
	}
	?>
	<input type="hidden" value="<?= $count ?>" id="txtChange">
	<?php $this->endWidget(); ?>
	<script type="text/javascript">
	    var fbHtml = "";
	    var fbFlag = 1;
	    function validateForm2(obj)
	    {
	        var promoterid = $(obj).attr("value");
	        var kmr = $(obj).attr("kmr");
	        var booktype = $(obj).attr("booktype");
	        var remainseat = $(obj).attr("remainingseats");
	        var remainbigbags = $(obj).attr("remainingbigbags");
	        var remainsmallbags = $(obj).attr("remainingsmallbags");
	        var gender = $(obj).attr("gender");
	        var pickdate = $(obj).attr("pickupdate");
	        var vehicleTypeId = $(obj).attr("vct_id") != '' ? $(obj).attr("vct_id") : 114;
	        var pickupTime = $(obj).attr("pickupTime");
	        var timeslot = $(obj).attr("timeslot");
	        $('#pickUpTime').val(pickupTime);
	        $('#timeSlot').val(timeslot);
	        $('#pickUpDateTime').val(pickdate);
	        var noperson = $('#<?= CHtml::activeId($model, 'bkg_no_person') ?>').val();
	        var nobigbags = $('#<?= CHtml::activeId($model, 'bkg_num_large_bag') ?>').val();
	        var nosmallbags = $('#<?= CHtml::activeId($model, 'bkg_num_small_bag') ?>').val();
	        if (pickdate != '')
	        {
	            var diff = $(obj).attr("diff");
	            var cu = new Date();
	            var pic = new Date(pickdate);
	            var picM = pic.getTime();
	            var cuM = cu.getTime();
	            var diff = picM - cuM;
	            var chk = 8 * 3600 * 1000;
	            if (diff < chk)
	            {
	                alert('Departure time should be at least 8 hours hence for Flexxi share booking');
	                return false;
	            }
	        }
	        if (booktype == 'flexxi')
	        {
	<?
	if (!Users::model()->getFbLogin($model->bkg_user_id, $model->bkg_user_email, $model->bkg_contact_no))
	{
		?>
		            if (fbFlag == 1)
		            {
		                fbHtml = "<div class='col-xs-5 fbook-btn mt20' id='fbHtml'>" +
		                        "<a href='#'><span class='btn btn-xs btn-social btn-facebook pl5 pr5' onclick='openFbDialog();'><i class='fa fa-facebook pr5' style='font-size: 22px;'></i> Login with Facebook</span></a>" +
		                        "</div>";
		            }

	<? } ?>

	            boxFlexi = bootbox.dialog({
	                message: "<div class='panel'><div class='panel panel-body'>" +
	                        "<div class='row'>" + fbHtml +
	                        "<div class='col-xs-6'>" +
	                        "<input type='hidden' id='promoterid' name='promoid' value='" + promoterid + "'>" +
	                        "<input type='hidden' id='vct_id' name='vehicletype' value='" + vehicleTypeId + "'>" +
	                        "<input type='hidden' id='gender' name='gender' value='" + gender + "'>" +
	                        "<label>How many seats do you need :</label><br><input class='form-control' min=1 id='noofseats' type='number' onchange='baggage_info()' placeholder='No.of seats' value='" + noperson + "' required='required' max='" + remainseat + "'>\n\
						 </div></div></div></div>",
	                title: 'Your Reqirements:',
	                size: 'medium',

	                onEscape: function ()
	                {
	                    boxFlexi.modal('hide');
	                },
	                buttons: {
	                    ok: {
	                        label: "Submit",
	                        className: 'btn-info',
	                        callback: function ()
	                        {
	                            if ($('#noofseats').val() == '' || $('#noofseats').val() == 0)
	                            {

	                                box1 = bootbox.alert({
	                                    message: "No of seats is mandatory",
	                                    title: 'Error',
	                                    size: 'medium',
	                                });

	                                return false;

	                            }
	                            else if (remainseat != '' && $('#noofseats').val() > remainseat)
	                            {
	                                box1 = bootbox.alert({
	                                    message: "No of seats cannot exceed Remaining no of seats",
	                                    title: 'Error',
	                                    size: 'medium',
	                                });

	                                return false;

	                            }
	                            else
	                            {
	                                $('input[name="BookingTemp[bkg_no_person]"]').val($('#noofseats').val());
	                                $('#<?= CHtml::activeId($model, 'bkg_num_small_bag') ?>').val($('#noofsmallbags').val());
	                                $('#<?= CHtml::activeId($model, 'bkg_num_large_bag') ?>').val($('#noofbigbags').val());
	                                $('#<?= CHtml::activeId($model, 'bkg_fp_id') ?>').val($('#promoterid').val());
	                                $('#<?= CHtml::activeId($model, 'bkg_vehicle_type_id') ?>').val($('#vct_id').val());
	                                $('#<?= CHtml::activeId($model, 'bkg_flexxi_type') ?>').val(2);


	                                jQuery.ajax({type: 'get', url: '<?= Yii::app()->createUrl('users/validategenderflexxi') ?>',
	                                    "dataType": "json", data: {'fpId': $('#promoterid').val(), 'fsId': $('#BookingTemp_bkg_id').val(), 'hash': $('#BookingTemp_hash').val()},
	                                    success: function (data)
	                                    {
	                                        if (data.success)
	                                        {
	                                            $('#flexxiSearch-form1').submit();
	                                            bootbox.hideAll();
	                                        }
	                                        else
	                                        {
	                                            alert(data.message);
	                                            return false;
	                                        }
	                                    }
	                                });
	                                return false;

	                            }
	                        }
	                    }
	                }
	            });

                boxFlexi.on('hidden.bs.modal', function (e) {
					$('body').addClass('modal-open');
                    $(this).data('bs.modal', null);
                });


	        }
	    }
	    function baggage_info()
	    {
	        var seat = $('#<?= CHtml::activeId($model, "bkg_no_person") ?>').val();
	        if (seat == 0 || seat == '')
	        {
	            $('#bagunit').text(0);
	        }
	        else if (seat == 1)
	        {
	            $('#bagunit').text(1);
	        }
	        else if (seat == 2)
	        {
	            $('#bagunit').text(2);
	        }
	        else
	        {
	            $('#bagunit').text(3);
	        }
	    }



	    function openFbDialog()
	    {
	        var href = '<?= Yii::app()->createUrl('users/oauth', array('provider' => 'Facebook', 'isFlexxi' => true)); ?>';
	        var fbWindow = window.open(href, 'Gozocabs', 'left=20,top=20,width=500,height=500,toolbar=1,resizable=0');
	    }
	    function updateLogin()
	    {
	        $href = '<?= Yii::app()->createUrl('users/refreshuserdata') ?>';
	        jQuery.ajax({type: 'get', url: $href,
	            "dataType": "json",
	            success: function (data1)
	            {
	                $('#userdiv').hide();
	                $('#navbar_sign').html(data1.rNav);
	                fillUserform2(data1.userData);
	                fillUserform13(data1.userData);
	                $('.btn-facebook').hide();
	                $('#usr_gender').val(data1.userData.usr_gender);
	                $('#fbHtml').hide();
	                fbFlag = 0;
	                fbHtml = "";
	                alert('Facebook login success');
	            }
	        });
	    }
	    function fillUserform2(data)
	    {

	        if ($('#BookingTemp_bkg_user_name').val() == '' && $('#BookingTemp_bkg_user_lname').val() == '')
	        {
	            $('#BookingTemp_bkg_user_name').val(data.usr_name);
	            $('#BookingTemp_bkg_user_lname').val(data.usr_lname);
	        }
	        if (data['usr_mobile'] != '')
	        {
	            if ($('#BookingTemp_bkg_contact_no').val() == '')
	            {
	                $('#BookingTemp_bkg_contact_no').val(data.usr_mobile);
	            }
	            else if ($('#BookingTemp_bkg_contact_no').val() != '' && $('#BookingTemp_bkg_contact_no').val() != data.usr_mobile)
	            {
	                $('#BookingTemp_bkg_alternate_contact').val(data.usr_mobile);
	            }
	        }
	        if (data.usr_email != '')
	        {
	            if ($('#BookingTemp_bkg_user_email1').val() == '')
	            {
	                $('#BookingTemp_bkg_user_email1').val(data.usr_email);
	            }
	            if ($('#BookingTemp_bkg_user_email2').val() == '')
	            {
	                $('#BookingTemp_bkg_user_email2').val(data.usr_email);
	            }
	        }

	    }
	    function fillUserform13(data)
	    {
	        if ($('#Booking_bkg_user_name').val() == '' && $('#Booking_bkg_user_lname').val() == '')
	        {
	            $('#Booking_bkg_user_name').val(data.usr_name);
	            $('#Booking_bkg_user_lname').val(data.usr_lname);
	        }
	        if (data.usr_mobile != '')
	        {
	            if ($('#Booking_bkg_contact_no').val() == '')
	            {
	                $('#Booking_bkg_contact_no').val(data.usr_mobile);
	            }
	            else if ($('#Booking_bkg_contact_no').val() != '' && $('#Booking_bkg_contact_no').val() != data.usr_mobile)
	            {
	                $('#Booking_bkg_alternate_contact').val(data.usr_mobile);
	            }
	        }
	        if (data.usr_email != '')
	        {
	            if ($('#Booking_bkg_user_email1').val() == '')
	            {
	                $('#Booking_bkg_user_email1').val(data.usr_email);
	            }
	            if ($('#Booking_bkg_user_email2').val() == '')
	            {
	                $('#Booking_bkg_user_email2').val(data.usr_email);
	            }
	        }


	    }

	    function notify()
	    {
	        var firstName = $('#BookingTemp_bkg_user_name').val();
	        var lastName = $('#BookingTemp_bkg_user_lname').val();
	        var email = $('#BookingTemp_bkg_user_email2').val();
	        var fromDate = $('#BookingTemp_bkg_pickup_date_date_mf12').val();
	        var toDate = $('#BookingTemp_bkg_pickup_date_date_mf22').val();
	        var fromTime = $('#bkg_pickup_date_time_mf12').val();
	        var toTime = $('#bkg_pickup_date_time_mf22').val();
	        var fromCity = '<?= $model->bkg_from_city_id ?>';
	        var toCity = '<?= $model->bkg_to_city_id ?>';
	        var bookingId = '<?= $model->bkg_id ?>';

	        $href = '<?= Yii::app()->createUrl('booking/alert') ?>';
	        jQuery.ajax({type: 'get', url: $href,
	            "dataType": "json",
	            "data": {'firstName': firstName, 'lastName': lastName, 'email': email, 'fromDate': fromDate, 'toDate': toDate, 'fromTime': fromTime, 'toTime': toTime, 'fromCity': fromCity, 'toCity': toCity, 'bookingId': bookingId},
	            success: function (data)
	            {
	                if (data.success == true)
	                {
	                    alert('Your details successfully saved, If any booking found on these day we will notify you.');
	                    window.location.reload(true);
	                }
	                else
	                {
	                    var data1 = JSON.parse(data.result);
	                    if (data1.hasOwnProperty('BookingAlert_alr_email'))
	                    {
	                        $('#BookingTemp_bkg_user_email2').parent().addClass('has-error');
	                        $('#BookingTemp_bkg_user_email2').parent().find('.help-block').css('display', 'block');
	                        $('#BookingTemp_bkg_user_email2').parent().find('.help-block').text(data1.BookingAlert_alr_email[0]);
	                    }
	                    if (data1.hasOwnProperty('BookingAlert_alr_name'))
	                    {
	                        $('#BookingTemp_bkg_user_name').parent().addClass('has-error');
	                        $('#BookingTemp_bkg_user_name').parent().find('.help-block').css('display', 'block');
	                        $('#BookingTemp_bkg_user_name').parent().find('.help-block').text(data1.BookingAlert_alr_name[0]);
	                    }
	                }
	            },
	            error: function (err)
	            {
	                alert('error');
	            }
	        });
	    }

	    $('#txtChange').ready(function ()
	    {
	        var count = <?= $count ?>;
        if (count == 0)
        {
            $('#primaryTxt').text('There are no existing riders who have offered to share their taxi on this route.');
            $('#secondryTxt').text('');
            $('#slotDiv').hide();
        }
        else if (count == -1)
        {
            $('#primaryTxt').text('Please select correct date time.');
            $('#secondryTxt').hide();
        }
        else
        {
            $('#slotDiv').show();
        }
    });

</script>