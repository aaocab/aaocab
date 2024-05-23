<style type="text/css">
    .next4-btn{
        background: #f2f2f2;    
        text-transform: uppercase; font-size: 12px; font-weight: bold; border: none; padding: 4px 10px; color: #323232; border: #c5c5c5 1px solid;
        -webkit-border-radius: 2px;
        -moz-border-radius: 2px;
        border-radius: 2px;
        transition:all 0.5s ease-in-out 0s;
        -webkit-box-shadow: 0px 7px 8px -2px rgba(194,194,194,0.54);
        -moz-box-shadow: 0px 7px 8px -2px rgba(194,194,194,0.54);
        box-shadow: 0px 7px 8px -2px rgba(194,194,194,0.54);
    }
    .next4-btn:hover{ background: #f13016; color: #fff; border: #b72916 1px solid;}
	.next3-btn{

		text-transform: none!important;
	}

    .next5-btn{
        background: #00a388;    
        /*text-transform: uppercase; */
		font-size: 14px; font-weight: bold; border: none; padding: 7px 15px; color: #fff;
        -webkit-border-radius: 2px;
        -moz-border-radius: 2px;
        border-radius: 2px;
        transition:all 0.5s ease-in-out 0s;
        -webkit-box-shadow: 0px 7px 8px -2px rgba(194,194,194,0.54);
        -moz-box-shadow: 0px 7px 8px -2px rgba(194,194,194,0.54);
        box-shadow: 0px 7px 8px -2px rgba(194,194,194,0.54);
    }
    .next5-btn:hover{ background: #007d68; color: #fff;}
	.popover-content {		
		width:230px;
		font-size: 12px;
		font-family: arial
	}
	.detailTxt{
		text-decoration: none!important;
		cursor: pointer;
		border-bottom: 2px #1a4ea2 dashed}


    .search-cabs-box2{border: #f36c31 2px solid;}
    .search-cabs-box2 .car-style2{ background: #f36c31 url(../images/car_style_right_2.png) top right no-repeat; position: relative; top: 15px; left: -15px; color: #fff; font-size: 11px; font-weight: bold; padding: 5px 25px 5px 10px; display: table;}
	.subbtn{
		font-size: 0.75em!important;
	}
	.proceed-make-btn{
		display: none;
	}
	.wrap-panel{ font-size: 12px; color: #fff; line-height: 18px; text-align: right; padding:12px 10px;}
	.wrap-panel span{ 
		background: #ef9b08; padding:5px 10px; margin-bottom: 10px;
		-webkit-border-radius: 3px;
		-moz-border-radius: 3px;
		border-radius: 3px;
	}
	@media (max-width: 767px){ 
		.next3-btn{
			padding: 5px 7px;
			font-size: 13px!important;
		}
		.next5-btn{
			padding: 5px 7px;
			font-size: 13px!important;
		}
		.wrap-panel{ 
			word-wrap: break-word; display: flex; flex-wrap: wrap; word-break: keep-all; font-size: 12px; color: #fff; line-height: 18px; padding:5px 10px; background: #ef9b08; text-align: center;
background: rgba(0,153,242,1);
background: -moz-linear-gradient(45deg, rgba(0,153,242,1) 0%, rgba(26,78,162,1) 100%);
background: -webkit-gradient(left bottom, right top, color-stop(0%, rgba(0,153,242,1)), color-stop(100%, rgba(26,78,162,1)));
background: -webkit-linear-gradient(45deg, rgba(0,153,242,1) 0%, rgba(26,78,162,1) 100%);
background: -o-linear-gradient(45deg, rgba(0,153,242,1) 0%, rgba(26,78,162,1) 100%);
background: -ms-linear-gradient(45deg, rgba(0,153,242,1) 0%, rgba(26,78,162,1) 100%);
background: linear-gradient(45deg, rgba(0,153,242,1) 0%, rgba(26,78,162,1) 100%);
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#0099f2', endColorstr='#1a4ea2', GradientType=1 );}
	}
	
</style>
<?php
$isPromo = BookingSub::model()->getApplicable($model->bkg_from_city_id, $model->bkg_to_city_id, 1);

$isFlexxiExcluded	 = false;
$excludeCabType		 = BookingSub::getexcludedCabTypes($model->bkg_from_city_id, $model->bkg_to_city_id);
if (in_array(11, $excludeCabType))
{
	$isFlexxiExcluded = true;
}
$cabData = VehicleTypes::model()->getMasterCarDetails();
$arr1	 = array_values($cabratedata)[0];
if ($arr1['error'] != 0)
{
	?>
	<div class="panel">            
		<div class="panel-body pt0 pb0">   
			<h3>Some error occurred. Please Try again later</h3>
		</div>
	</div>
	<?php
}
/* @var $model Booking */
if ($arr1['error'] == 0)
{
	// $arrr = CJSON::decode($model->preData);
	$cityArr	 = $arrr['cityarr'];
	$cityNameArr = $arrr['cityNameArr'];
	$incArr		 = [0 => 'Excluded', 1 => 'Included'];

	// $model=  Booking::model()->findByPk(25157);
	//   $cabRate = Rate::model()->getCabDetailsbyCities($model->bkg_from_city_id, $model->bkg_to_city_id);
	$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
		'id'					 => 'cabrate-form1',
		'enableClientValidation' => true,
		'clientOptions'			 => array(
			'validateOnSubmit'	 => true,
			'errorCssClass'		 => 'has-error',
			'afterValidate'		 => 'js:function(form,data,hasError){
    if(!hasError){
    $.ajax({
    "type":"POST",
    "dataType":"html",
    "url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('booking/cabratedetail')) . '",
    "data":form.serialize(),
    "beforeSend": function(){
    ajaxindicatorstart("");
    },
    "complete": function(){
    ajaxindicatorstop();
    },
    "success":function(data2){
    var data = "";
    var isJSON = false;
    try {
    data = JSON.parse(data2);
    isJSON = true;
    } catch (e) {

    }
								if(!isJSON)
								{
    if($("#flexxiSearchCheck").val() == 1){
    openTab(data2,3);
	trackPage(\'' . CHtml::normalizeUrl(Yii::app()->createUrl('booking/additionaldetail')) . '/flexi/1\');
    }else{
    openTab(data2,4);
	trackPage(\'' . CHtml::normalizeUrl(Yii::app()->createUrl('booking/additionaldetail')) . '\');
    }
    disableTab(3);
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
	$form->attributes	 = $model->attributes;
	?>
	<?= $form->errorSummary($model); ?>
	<?= CHtml::errorSummary($model); ?>

	<div class="panel">            
		<div class="panel-body pt0 pb0 p0">   
			<input type="hidden" id="step" name="step" value="3">
			<input type="hidden" id="ckm_rate" name="ckm_rate" >
			<input type="hidden" id="flexxiSearchCheck" name="flexxi_check" value="0">
			<?= $form->hiddenField($model, 'bkg_id', ['id' => 'bkg_id3', 'class' => 'clsBkgID']); ?>
			<?= $form->hiddenField($model, 'hash', ['id' => 'hash3', 'class' => 'clsHash']); ?>
			<?= $form->hiddenField($model, "bkg_flexxi_type"); ?>    
			<?= $form->hiddenField($model, 'bkg_booking_type'); ?>  
			<?= $form->hiddenField($model, "bkg_vehicle_type_id"); ?>
			<?= $form->hiddenField($model, "bkg_rate_per_km_extra"); ?>
			<?= $form->hiddenField($model, 'bkg_no_person') ?>
			<?= $form->hiddenField($model, 'bkg_num_large_bag') ?>
			<?= $form->hiddenField($model, 'bkg_num_small_bag') ?>
			<?= $form->hiddenField($model, 'bkg_flexxi_quick_booking') ?>
			<?= $form->hiddenField($model, 'time1') ?>
			<?= $form->hiddenField($model, 'time2') ?>
			<?= $form->hiddenField($model, 'bkg_pickup_date_date') ?>

			<? $diff				 = floor((strtotime($model->bkg_pickup_date) - time()) / 3600); ?>
			<input type="hidden" id="diff" name="diff" value="<?= $diff ?>">
			<div id="error-border" style="<?= (CHtml::errorSummary($model) != '') ? "border:2px solid #a94442" : "" ?>" class="route-page1">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-offset-1 col-lg-offset-1 col-md-10 col-lg-10 ml0">
						<h2 class="mb0 mt0">

							<?
							//$ct = $model->getTripCitiesListbyId();
							$ct					 = implode(' &rarr; ', $quoteModel->routeDistance->routeDesc);
							echo $ct;
							?> </h2>
						<?
						if ($quotes)
						{
							?>
						<p>Estimated Distance: <b> <?= $quoteModel->routeDistance->tripDistance . " Km" ?></b>,
								Estimated Time: <b><?= $quoteModel->routeDuration->durationInWords ?> (+/- 30 mins for traffic)</b></p>
							<?
						}
						else
						{
							?>
							<br/><p><b>Sorry cab is not available for this route.</b></p>
						<? } ?>
						<? /*  Estimated Time: <b><?= $model->bkg_trip_duration_day ?></b></p> */ ?>
						<h5 class="hide">If there are any issues with your booking we will contact you. Please share your phone and email address below.</h5>
					</div> 
				</div>		
				<?
				if ($model->bkg_booking_type == 1 && !$isFlexxiExcluded)
				{
					?>    
					<div class="card col-xs-12 mb10 pt10 pb10 box-style" style="cursor:pointer;" onclick="flexxiShare_sub()">      
						<div class="card-header" style="font-size: 14px; line-height: normal; color: #ffffff;" >
							<div class="row">
								<div class="col-xs-12 col-sm-5 col-md-6 col-lg-8 text-uppercase">Flexible Travel Plans? Single Traveller?<br>
									Will Take a Shared ride whenever the price is right?
									<? $flexxiRate = $quote->flexxiRates ?>
									<div class="card-body pt20" style="font-size: 24px; line-height: normal; font-weight: bold; text-align: right;" >
										<div class="card-footer starting-panel"><span style="color: #ffffff; padding: 5px 10px; background: #0a2044;">Starting at <i class="fa fa-inr"></i><?= $quotes[3]->flexxiRates[1]['subsBaseAmount'] ?> per person</span></div>
									</div>
								</div>
								<div class="col-xs-12 col-sm-7 col-md-6 col-lg-4 mt10 mb10">
									<div class="row m0" style="border:#fff 4px solid; background: #f36c31;">
										<div class="col-xs-12 col-sm-7 pt5 box-style3" style="font-size: 13px;">
											Ride with someone who is selling an empty seat in their taxi.
											Find a Flexxi Shared Seat Now
										</div>
										<div class="col-xs-12 col-sm-5 text-center" style="background: #fff; min-height: 75px; padding-top: 20px;"><img src="/images/gozo-flexxi-logo.png" width="130px;"></div>
									</div>
								</div>
								<div class="col-xs-12 col-sm-7 col-md-6 col-lg-4 mt10 mb10">
									<a href="<?= Yii::app()->createUrl('index/flexxi') ?>" style="color: #ffffff" target="_BLANK" onclick="return true;"><u>Learn More...</u></a>
								</div>
							</div>
						</div>
					<? } ?>				 
				</div>
				<div class="row m0">
					<?
					if ($model->bkg_booking_type == 1 && !$isFlexxiExcluded)
					{
						?>
						<div class="col-xs-12 search-cabs-box search-cabs-box2 mb30 hidden-xs">
							<div class="row">
								<div class="col-xs-12 col-sm-3 border-rightnew">
									<div class="car-style2 text-uppercase">flexxi Shared</div>
									<div class="car_box"><img src="/images/cabs/car-etios.jpg" alt="" ></div>
									<p class="text-center" style="line-height:16px;">1 seat in a shared taxi.<br>
										Pickup from a fixed spot.<br>
										Drop to your destination address.
									</p>
								</div>
								<div class="col-xs-12 col-sm-9">
									<div class="row p10">
										<div class="col-xs-12 col-sm-9 mobile-view-p border-lt">
											<div class="search-icon-box">
												<img src="/images/search_icon_1.png" alt="Capacity" title="Capacity"><br>
												Price Per Seat
											</div>
											<div class="search-icon-box">
												<img src="/images/search_icon_2.png" alt="Luggage Capacity" title="Luggage Capacity"><br>
												1 Small bag
											</div>
											<div class="search-icon-box">
												<img src="/images/search_icon_3.png" alt="AC" title="AC"><br>
												AC
											</div>
											<div class="search-icon-box">
												<img src="/images/search_icon_5.png" alt="KM in Quote" title="KM in Quote"><br>
												KM in Quote <br> <?= $quoteModel->routeDistance->tripDistance . " Km" ?>
											</div>
											<div class="row">
												<div class="col-xs-12 font11">
													<?= $taxStr ?>
												</div>
											</div>
											<div class="row">
												<div class="col-xs-12 text-center" style="color:#1a4ea2; font-size: 15px; font-weight: bold;">
													<span class="text-uppercase">Do you have flexible travel dates?</span><br>
													Travel with someone who has unused seats in their taxi.
													<br><a href="<?= Yii::app()->createUrl('index/flexxi') ?>" style="color: #1a4ea2;" target="_BLANK"><u>Learn More...</u></a>
												</div>
											</div>
										</div>

										<div class="col-xs-12 col-sm-3 search-icon-box2 pl0 pr0">
											<?
											if ($quote->routeRates->discFare > 0 && $isPromo)
											{
												?>
												<span style="font-size: 16px;">Base Fare: <del><i class="fa fa-inr"></i><?= $quote->routeRates->baseAmount ?></del></span><br>
												<span style="font-size: 26px; color: #2458aa; line-height: normal; font-weight: bold;"><?= $quote->routeRates->baseAmount - PromoCalculation::model()->calculatePromoAmount($discMax, $discMin, 1, $discCond, $quote->routeRates->baseAmount); ?></span><br>
												<?
											}
											else
											{
												?>
												<div class="row">
													<div class="col-xs-12" style="line-height: 18px;">
														Its better than going by bus!<br>
														<b>shared seats available starting from</b><br>
														<!--<h4 class="m0 text-uppercase">Base Fare</h4> -->
														<span style="font-size: 26px; color: #2458aa; line-height: normal; font-weight: bold;">
															<i class="fa fa-inr"></i><?= $quotes[3]->flexxiRates[1]['subsBaseAmount'] ?>
														</span><br>
														<span class=" small_text hide">(Approx.)</span>
													</div>

												</div>
											<? } ?>
											<button type="button" value="<?= $cab['vht_id'] ?>" kmr="<?= $quote->routeRates->ratePerKM ?>" name="bookButton" class="btn next3-btn mt10" onclick="flexxiShare_sub()"><b>Find a flexxi shared seat</b></button>    
										</div>
									</div>
								</div>
							</div>
							<div id="flexxiCardView">
								<?
								//$this->renderPartial('flexxislots_cardview',['date'=>date('Y-m-d', strtotime($model->bkg_pickup_date))]);
								?>
							</div>
						</div>
						<div class="col-xs-12 search-cabs-box mb30 hidden-lg hidden-sm hidden-md">
							<div class="row">
								<div class="car-style">flexxi Shared</div>
								<div class="col-xs-12">
									<div class="car_box"><img src="/images/cabs/car-etios.jpg" alt="" ></div>
									<p class="text-center" style="line-height:16px;">1 seat in a shared taxi. Pickup from a fixed spot.<br>
										Drop to your destination address.</p>
								</div>
								<div class="col-xs-12 text-center">
									<?
											if ($quote->routeRates->discFare > 0 && $isPromo)
											{
												?>
												<span style="font-size: 16px;">Base Fare: <del><i class="fa fa-inr"></i><?= $quote->routeRates->baseAmount ?></del></span><br>
												<span style="font-size: 26px; color: #2458aa; line-height: normal; font-weight: bold;"><?= $quote->routeRates->baseAmount - PromoCalculation::model()->calculatePromoAmount($discMax, $discMin, 1, $discCond, $quote->routeRates->baseAmount); ?></span><br>
												<?
											}
											else
											{
												?>
												<div class="row">
													<div class="col-xs-12" style="line-height: 18px;">
														Its better than going by bus!<br>
														<b>shared seats available starting from</b><br>
														<!--<h4 class="m0 text-uppercase">Base Fare</h4> -->
														<span style="font-size: 26px; color: #2458aa; line-height: normal; font-weight: bold;">
															<i class="fa fa-inr"></i><?= $quotes[3]->flexxiRates[1]['subsBaseAmount'] ?>
														</span><br>
														<span class=" small_text hide">(Approx.)</span>
													</div>

												</div>
											<? } ?>
											<button type="button" value="<?= $cab['vht_id'] ?>" kmr="<?= $quote->routeRates->ratePerKM ?>" name="bookButton" class="btn next3-btn mt10 mb5" onclick="flexxiShare_sub()"><b>Find a flexxi shared seat</b></button>
								</div>
								<div class="col-xs-12 text-center" style="color:#1a4ea2; font-size: 13px; font-weight: bold;">
											<span class="text-uppercase">Do you have flexible travel dates?</span><br>
											Travel with someone who has unused seats in their taxi.
											<a href="<?= Yii::app()->createUrl('index/flexxi') ?>" style="color: #1a4ea2;" target="_BLANK"><u>Learn More...</u></a>
								</div>
								<div class="col-xs-12 col-sm-9 border-lefttnew">
										<div class="row sch-in-bxmain">
											<ul>
												<li class="col-xs-3 search-icon-boxview">Price Per Seat</li>
												<li class="col-xs-3 search-icon-boxview">1 Small bag</li>
												<li class="col-xs-3 search-icon-boxview">
													<span class="font-styles">AC</span>
												</li>
												<li class="col-xs-3 search-icon-boxview">
													KM in Quote <br> <?= $quoteModel->routeDistance->tripDistance . " Km" ?>
												</li>
											</ul>
											<div class="col-xs-12 col-sm-9 list-views">
												<ul>
													<li><?= $taxStr ?></li>
												</ul>
											</div>


										</div>
									</div>
								
							</div>
							<div id="flexxiCardView">
								<?
								//$this->renderPartial('flexxislots_cardview',['date'=>date('Y-m-d', strtotime($model->bkg_pickup_date))]);
								?>
							</div>
						</div>


						<?
						if ($isPromo)
						{
							?>
							<div class="col-xs-12 p5 mb5" style="font-size: 1.1em">*Get <?= $discCond[3] ?>% discount on minimum 15% advance payment.</div>
							<?
						}
					}
					?>
					<?
					$fromcity	 = Cities::getName($model->bkg_from_city_id);
					$tocity		 = Cities::getName($model->bkg_to_city_id);
					if ($quotes)
					{
						$i = 0;
						foreach ($quotes as $key => $quote)
						{
							if (!$quote->success)
							{
								continue;
							}
							$i++;
							/* @var $quote Quote */

							$cab = $cabData[$key];
							$tolltax_value	 = $quote->routeRates->tollTaxAmount | 0;
							$tolltax_flag	 = $quote->routeRates->isTollIncluded | 0;
							$statetax_value	 = $quote->routeRates->stateTax | 0;
							$statetax_flag	 = $quote->routeRates->isStateTaxIncluded | 0;
							$flexxRates		 = $quote->flexxiRates;
							$shareBooking	 = false;
							if (($tolltax_flag == 1 && $tolltax_value == 0) && ($statetax_flag == 1 && $statetax_value == 0))
							{
								$taxStr = 'Toll Tax and State Tax included';
							}
							else if ($tolltax_flag == 0 && $statetax_flag == 0)
							{
								$taxStr = 'Toll and State taxes extra as applicable';
							}
							?>

							<div id="flexxi_rates_<?= $cab['vht_id'] ?>" class="hidden" >
								<label>Normal One-Way Fare from <b><?= $fromcity ?></b> to <b><?= $tocity ?> <i class="fa fa-inr ml10"></i><?= $quote->routeRates->baseAmount ?></b></label><br>
								<label>Fare details with <font color='#ff2929'>FLEXXI SHARE:</font></label>
								<table align='center'>
									<tr>
										<th style='padding:8px; border: 1px solid black; font-size: 13px'>Seats You Use</th>   
										<th style='padding:8px; border: 1px solid black; font-size: 13px'>Seats You Share</th>
										<th style='padding:8px; border: 1px solid black; font-size: 13px'>You Pay</th>
										<th style='padding:8px; border: 1px solid black; font-size: 13px'>You Save</th>
										<th style='padding:8px; border: 1px solid black; font-size: 13px'>% savings </th>
									</tr>
									<?
									for ($i = 1; $i < $cab['vht_capacity']; $i++)
									{
										?>
										<tr>
											<td style='padding:8px; border: 1px solid black; font-size: 13px'><?= $i ?></td>    
											<td style='padding:8px; border: 1px solid black; font-size: 13px'><?= $cab['vht_capacity'] - $i ?></td>
											<td style='padding:8px; border: 1px solid black; font-size: 13px'>as low as <b><i class="fa fa-inr ml10"></i><?= $flexxRates[$i]['flexxiBaseAmount'] ?></b></td>
											<td style='padding:8px; border: 1px solid black; font-size: 13px'><i class="fa fa-inr ml10"></i><?= $flexxRates[$i]['fpsaved'] ?></td>
											<td style='padding:8px; border: 1px solid black; font-size: 13px'><?= round(($flexxRates[$i]['fpsaved'] / $quote->routeRates->baseAmount) * 100) ?>%</td>
										</tr>
									<? } ?>
								</table>

							</div>
							<?
							$promoDiscount										 = round(PromoCalculation::model()->calculatePromoAmount($discMax[$key], $discMin[$key], 1, $discCond[$key], $quote->routeRates->baseAmount));
//													echo $quote->routeRates->baseAmount;
							$discBaseAmount										 = $quote->routeRates->baseAmount - $promoDiscount;
							$baseAmount											 = $quote->routeRates->baseAmount;
							$driverAllowance									 = $quote->routeRates->driverAllowance | 0;
							$bkgDemoModel										 = new Booking();
							$bkgDemoInvoiceModel								 = new BookingInvoice();
							$bkgDemoInvoiceModel->bkg_base_amount				 = $discBaseAmount;
							$tTaxText											 = ($tolltax_flag == 1) ? 'Included' : 'Excluded';
							$sTaxText											 = ($statetax_flag == 1) ? 'Included' : 'Excluded';
							$bkgDemoInvoiceModel->bkg_toll_tax					 = $tolltax_value;
							$bkgDemoInvoiceModel->bkg_state_tax					 = $statetax_value;
							$bkgDemoInvoiceModel->bkg_driver_allowance_amount	 = $driverAllowance;
							$bkgDemoInvoiceModel->calculateTotal();
							$oldGst												 = $quote->routeRates->gst;
							$tolltax_value_inc									 = $tolltax_value;
							$statetax_value_inc									 = $statetax_value;
							$gst												 = $bkgDemoInvoiceModel->bkg_service_tax;
							$oldTotal											 = $quote->routeRates->totalAmount;
							$totalAmount										 = $bkgDemoInvoiceModel->bkg_total_amount;

							$dAllowanceBlock = "<div class='row'>
	<div class='col-xs-8'>Driver Allowance</div>
	<div class='col-xs-4 text-right'><i class='fa fa-inr'></i>$driverAllowance</div>
</div>";
							
							if($promoDiscount>0){
								$discount_txt ="<div class='row text-danger'>
												<div class='col-xs-8'>Discount<sup>*</sup> (Apply ".$discCode[$key]." )</div>
												<div class='col-xs-4  text-right'><i class='fa fa-inr'></i>$promoDiscount</div>
											</div> ";
							}
							else
							{
								$discount_txt ="";
							}
							
							$details		 = "
											<div class='row'>
<div class='col-xs-12'>
<div class='row'>
	<div class='col-xs-8'>Base Fare </div>
	<div class='col-xs-4 text-right'><i class='fa fa-inr'></i>$baseAmount</div>
											</div>  
											$discount_txt 

											<div class='row'>
	<div class='col-xs-8'>GST </div>
	<div class='col-xs-4 text-right'><i class='fa fa-inr'></i>$gst</div>
											</div>  
";
							if ($driverAllowance > 0)
							{
								$details .= $dAllowanceBlock;
							}

							$details		 .= "
											<div class='row'>
	<div class='col-xs-8'>Toll Tax ($tTaxText)</div>
	<div class='col-xs-4 text-right'><i class='fa fa-inr'></i>$tolltax_value_inc</div>
											</div>  
											<div class='row'>
	<div class='col-xs-8'>State Tax ($sTaxText)</div>
	<div class='col-xs-4 text-right'><i class='fa fa-inr'></i>$statetax_value_inc</div>
											</div>  


											<div class='row'><b>
	<div class='col-xs-8'>Total Payable </div>
	<div class='col-xs-4 text-right'><i class='fa fa-inr'></i>$totalAmount </div></b>
</div></div></div>";
							?>

							<?
							$durationRange	 = false;
							$fromCityId		 = $model->bkg_from_city_id;
							$toCityId		 = $model->bkg_to_city_id;
							$tripType		 = $model->bkg_booking_type;
							$pickupDate		 = $model->bkg_pickup_date;
							$cabType		 = $key;
							$durationRange	 = GoldenMarkup::model()->getLowestPriceDurationRange($fromCityId, $toCityId, $pickupDate, $tripType, $cabType);
							if ($durationRange)
							{
								$bestPriceRange = '';


								if ($durationRange['lowestPricing'])
								{
									$bestPriceRange = "You have found our best price";
								}
								else
								{
									$dateFrom		 = $durationRange['durationStart'];
									$dateTo			 = $durationRange['durationEnd'];
									$bestPriceRange	 = "Get a lower price if you travel between $dateFrom & $dateTo";
								}
							}
							?>


							<?
							if (($model->bkg_booking_type == 1) && ($cab['vht_capacity'] < 6 && $cab['vht_id'] != 28 && $cab['vht_id'] != 81 && $cab['vht_id'] == 30) && !$isFlexxiExcluded)
							{
								$shareBooking = true;
							}
							?> 
							<div class="col-xs-12 search-cabs-box mb30 hidden-xs">
								<div class="row">
									<div class="col-xs-12 col-sm-3 border-rightnew">
										<div class="car-style"><?= $cab['vht_make'] ?></div>
										<div class="car_box"><img src="<?= Yii::app()->baseUrl . '/' . $cab['vht_image'] ?>" alt="" ></div>
										<p class="text-center" style="line-height:16px;"><?= $cab['vht_model'] ?></p>
									</div>
									<div class="col-xs-12 col-sm-9">


										<?
										if ($durationRange)
										{
											?>

											<div class="col-xs-12 wrap-panel">
												<span><?= $bestPriceRange ?></span>
											</div>
										<? } ?>
										<div class="row p10">
											<div class="col-xs-12 col-md-8 col-sm-7 mobile-view-p border-lt">
												<div class="search-icon-box">
													<img src="/images/search_icon_1.png" alt="Capacity" title="Capacity"><br>
													<?= $cab['vht_capacity'] ?> Seats + Driver
												</div>
												<div class="search-icon-box">
													<img src="/images/search_icon_2.png" alt="Luggage Capacity" title="Luggage Capacity"><br>
													<?= $cab['vht_big_bag_capacity'] ?> Big bags + <?= $cab['vht_bag_capacity'] ?> Small bag
												</div>
												<div class="search-icon-box">
													<img src="/images/search_icon_3.png" alt="AC" title="AC"><br>
													AC
												</div>
												<div class="search-icon-box">
													<img src="/images/search_icon_5.png" alt="KM in Quote" title="KM in Quote"><br>
													KM in Quote <br> <?= $quote->routeDistance->quotedDistance ?> Km
												</div>
												<div class="row">
													<div class="col-xs-12 font11">
														<?= $taxStr ?>
													</div>
												</div>
												<div class="row">
													<div class="col-xs-12 font11">
														*Note: Ext. Chrg. After <?= $quote->routeDistance->quotedDistance ?> Kms. as applicable<? /* /?>= <i class="fa fa-inr"></i><?= $val['km_rate']; ?>/Km. <? */ ?>.
													</div>
												</div>
											</div>

											<div class="col-xs-12 col-md-4 col-sm-5 search-icon-box2 pl0 pr0">
												<?
												if ($quote->routeRates->discFare > 0 && $isPromo)
												{
													?>
													<span style="font-size: 16px;">Base Fare:<del><i class="fa fa-inr"></i><?= $quote->routeRates->baseAmount ?></del></span><br>
													<span style="font-size: 26px; color: #2458aa; line-height: normal; font-weight: bold;">
														<?= $quote->routeRates->baseAmount - PromoCalculation::model()->calculatePromoAmount($discMax, $discMin, 1, $discCond, $quote->routeRates->baseAmount); ?>
													</span><br>
													<?
												}
												else
												{
													?>
													<div class="row">
														<div class="col-xs-12" style="line-height: 18px;">
															<?php if($agent_id != ''){?>
															<span class="m0 text-uppercase text-muted" ><b>Base Fare</b></span><br>
															<br>														 
															<span style="font-size: 22px; color: #2458aa; line-height: normal;font-weight: bold;">
															<span style="font-size: 16px; line-height: normal; font-weight: bold;">
															<i class="fa fa-inr"></i><?= $quote->routeRates->baseAmount; ?>
															</span>
															<?}
															else
															{?>
															<span class="m0 text-uppercase text-muted" ><b>Base Fare</b></span><br>
															<?php
															//$discBaseAmount = $quote->routeRates->baseAmount;
															if($quote->routeRates->baseAmount!=$discBaseAmount)
															{?>
															<span style="font-size: 16px; line-height: normal; font-weight: bold;">
															<i class="fa fa-inr"></i><strike style="font-weight: bold;"><?= $quote->routeRates->baseAmount; ?> </strike>
															</span>												
															<br>
                                                            <?
															}
															
															}?>
															<span style="font-size: 22px; color: #2458aa; line-height: normal;font-weight: bold;">
																<i class="fa fa-inr"></i><?= $discBaseAmount ?><sup>*</sup><a data-toggle="popover" id="b<?= $cab['vht_id'] ?>"  data-placement="top" data-html="true" data-content="<?= $details ?>" style="font-size:15px;"><i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="Fair Breakup" data-placement="botton"></i></a>
															
															</span>												
														</div>
													</div>
												<? } ?>


												<button type="button" value="<?= $cab['vht_id'] ?>" kmr="<?= $quote->routeRates->ratePerKM ?>" 
														name="bookButton" class="btn next3-btn mt10 " onclick="validateForm1(this);">
													<b>Book Now</b><br><span class="subbtn">Full Cab</span></button>
												<?
												if ($shareBooking)
												{
													?>
													<button type="button" booktype="flexxi" 
															value="<?= $cab['vht_id'] ?>" 
															kmr="<?= $quote->routeRates->ratePerKM ?>" 
															name="flexxiButton" 
															capacity="<?= $cab['vht_capacity'] ?>" 
															bigbag="<?= $cab['vht_big_bag_capacity'] ?>" 
															smallbag="<?= $cab['vht_bag_capacity'] ?>" 
															class="btn next5-btn mt10 "
															onclick="validateForm1(this);">

														<b>Save <i class="fa fa-inr"></i><?= $flexxRates[1]['fpsaved'] ?></b>
														<br><span class="subbtn">Share your cab</span>
													</button>
												<? } if($promoDiscount>0){
													
													?>

													<br><div class="row mt10"><div class="col-xs-12">* Apply promo: <b><?=$discCode[$key]?></b></div></div>
												<?php
												}
												?>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xs-12 search-cabs-box mb30 hidden-sm hidden-md hidden-lg">
								<div class="row">
										<?
										if ($durationRange)
										{
											?>

											<div class="col-xs-12 text-center wrap-panel">
												<?= $bestPriceRange ?>
											</div>
										<? } ?>
									<div class="car-style"><?= $cab['vht_make'] ?></div>
									<?php if($promoDiscount>0){ ?>
									<div class="col-xs-12 text-right mb5">										
										<span class="label label-success mt10 n">*Apply promo: <b><?=$discCode[$key]?></b></span>
									</div>
									<?php } ?>   
									<div class="col-xs-6">
										<div class="car_box"><img src="<?= Yii::app()->baseUrl . '/' . $cab['vht_image'] ?>" alt="" ></div>
										<p class="text-center" style="line-height:16px;"><?= $cab['vht_model'] ?></p>
									</div>

									<div class="col-xs-6 search-icon-box2 mt0">
										
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
											<div class="row">
												<div class="col-xs-12 mb10">
													<span class="m0 text-uppercase text-muted" ><b>Base Fare</b></span><br>
													<span style="font-size: 16px; line-height: normal; font-weight: bold; color: #ababab;">
														<i class="fa fa-inr"></i>
														<strike><span><?= $quote->routeRates->baseAmount; ?> </span></strike>
													</span>	
													<br>														 
													<span style="font-size: 22px; color: #2458aa; line-height: normal;font-weight: bold;">
														<i class="fa fa-inr"></i><?= $discBaseAmount ?><sup>*</sup><a data-toggle="popover" id="b<?= $cab['vht_id'] ?>"  data-placement="top" data-html="true" data-content="<?= $details ?>" style="font-size:15px;"><i class="fa fa-info-circle" data-toggle="tooltip" title="Fair Breakup" data-placement="botton"></i></a>
													</span>	
												</div>

											</div>
										<? } ?>



									</div>
<div class="col-xs-6">
									<button type="button" value="<?= $cab['vht_id'] ?>" kmr="<?= $quote->routeRates->ratePerKM ?>" 
											name="bookButton" class="btn next3-btn mt10 float-right" onclick="validateForm1(this);">
										<b>Book Now</b><br><span class="subbtn">Full Cab</span></button></div>

									<?
									if ($shareBooking)
									{
										?>
										<div class="col-xs-6 pull-right">
										<button type="button" booktype="flexxi" 
												value="<?= $cab['vht_id'] ?>" 
												kmr="<?= $quote->routeRates->ratePerKM ?>" 
												name="flexxiButton" 
												capacity="<?= $cab['vht_capacity'] ?>" 
												bigbag="<?= $cab['vht_big_bag_capacity'] ?>" 
												smallbag="<?= $cab['vht_bag_capacity'] ?>" 
												class="btn next5-btn mt10 float-right"
												onclick="validateForm1(this);">

											<b>Save <i class="fa fa-inr"></i><?= $flexxRates[1]['fpsaved'] ?></b><br><span class="subbtn">Share your cab</span>
										</button>
									<? }?>
</div>
									<div class="col-xs-12 col-sm-9 border-lefttnew mt15">
										<div class="row sch-in-bxmain">
											<ul>
												<li class="col-xs-3 search-icon-boxview">
													<span class="font-styles"><?= $cab['vht_capacity'] ?></span><br>Seats + Driver
												</li>
												<li class="col-xs-3 search-icon-boxview">
													<span class="font-styles"><?= $cab['vht_big_bag_capacity'] ?> + <?= $cab['vht_bag_capacity'] ?></span><br>Big + Small Bag
												</li>
												<li class="col-xs-3 search-icon-boxview2">
													<span class="font-styles">AC</span>
												</li>
												<li class="col-xs-3 search-icon-boxview">
													<span class="font-styles"><?= $quote->routeDistance->quotedDistance ?> </span><br>KM in Quote
												</li>
											</ul>

											<div class="col-xs-12 col-sm-9 list-views">
												<ul>
													<li><?= $taxStr ?></li>
													<li>Note: Ext. Chrg. After <?= $quote->routeDistance->quotedDistance ?> Kms. as applicable<? /* /?>= <i class="fa fa-inr"></i><?= $val['km_rate']; ?>/Km. <? */ ?>.</li>
												</ul>
											</div>


										</div>
									</div>
								</div>
							</div>
							<?
						}
						if ($i == 0)
						{
							?>
							<div class="col-xs-12 summary-div border-none">
								<br/><p><b>Sorry cab is not available for this route.</b></p>
							</div>
							<?
						}
					}
					?>
				</div>
				<?
				$rtInfoArr = $model->getRoutesInfobyId();
				if (sizeof($rtInfoArr) > 0 && $rtInfoArr[0]['rut_special_remarks'])
				{
					?><div class="row">
						<div class="col-xs-12 ">
							<div class="bg bg-info p10 pl0">
								<ul style="list-style-type: square ;">
									<?
									foreach ($rtInfoArr as $info)
									{
										?>
										<li>
											<?= implode("</li><li>", array_filter(array_map("trim", explode("\n", $info['rut_special_remarks'])))) ?>
										</li>
									<? }
									?>
								</ul>
							</div>
						</div>
					</div>
				<? }
				?>

				<div class="p5">
					We require exact pickup and drop addresses to be provided for your itinerary before your vehicle and driver can be assigned. Once the pickup and drop addresses are provided, these may cause the above quotation to change.
				</div>
			</div> 
		</div>
		<?php $this->endWidget(); ?>
	<? } ?>
</div>


<div class="hide">
	<? //php print_r($GLOBALS['API']);                                        ?>
</div>
<script type="text/javascript">
	$(document).ready(function () {
		$('[data-toggle="popover"]').popover();
	});


	$('#bdate').html('<?= date('\O\N jS M Y \<\b\r/>\A\T h:i A', strtotime($model->bkg_pickup_date)) ?>');
	//  $("#Booking_bkg_tnc").attr('checked', 'checked');
	disableTab(3);
	$(".clsBkgID").val('<?= $model->bkg_id ?>');
	$(".clsHash").val('<?= Yii::app()->shortHash->hash($model->bkg_id) ?>');
	function validateForm1(obj)
	{
		var vht = $(obj).attr("value");
		var cabType = $(obj).attr('cabtype');
		var kmr = $(obj).attr("kmr");
		var booktype = $(obj).attr("booktype");
		var diff = $('#diff').val();

		if (booktype == 'flexxi' && diff < 8)
		{
			alert('Departure time should be at least 8 hours hence for Flexxi shared booking');
			return false;
		}
		if (vht > 0)
		{
			$('#<?= CHtml::activeId($model, "bkg_rate_per_km_extra") ?>').val(kmr);
			if (booktype == 'flexxi')
			{
				$('#<?= CHtml::activeId($model, "bkg_vehicle_type_id") ?>').val('<?= VehicleCategory::SHARED_SEDAN_ECONOMIC ?>');
				var vhtCapacity = $(obj).attr("capacity");
				trackPage('<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/additionaldetail/flexi/2')) ?>');
				box = bootbox.dialog({
					message: $('#flexxi_rates_' + vht).html() + "<br><div class='panel'><div class='panel panel-body'><div class='col-xs-12'><label align='left'>You will Use :</label><div class='row'><div class='col-xs-12'>\n\
					<div class='row'>\n\
								  <div class='col-xs-3'><label>No. of seats</label><br><input class='form-control' min=1 id='noofseats' type='number' placeholder='No.of seats' max='" + vhtCapacity + "' required></div>\n\
								  <div class='col-xs-4'><label></label><br><br><b>Allowed only <span id='bagunit'>0</span> Bag Units</b></div>\n\
								  <div class='col-xs-5'>\n\
								  <table>\n\
								  <tr><th style='padding:8px; border: 1px solid black; font-size: 13px'>Type of Bag</th>\n\
								  <th style='padding:8px; border: 1px solid black; font-size: 13px'>Bag Units</th></tr>\n\
								  <tr><td style='padding:8px; border: 1px solid black; font-size: 13px'>1 Backpack</td>\n\
								  <td style='padding:8px; border: 1px solid black; font-size: 13px'>1 Bag Unit</td></tr>\n\
								  <tr><td style='padding:8px; border: 1px solid black; font-size: 13px'>1 Small Bag</td>\n\
								  <td style='padding:8px; border: 1px solid black; font-size: 13px'>2 Bag Units</td></tr>\n\
								  <tr><td style='padding:8px; border: 1px solid black; font-size: 13px'>1 Big Bags</td>\n\
								  <td style='padding:8px; border: 1px solid black; font-size: 13px'>4 Bag Units</td></tr>\n\
								  </table>\n\
						  </div>\n\
						  <div class='col-xs-12 text-center mt20'><input type='button' class='btn btn-info' value='Submit' onclick='flexiShare_promo()'></div></div></div></div></div>",
					title: 'Rate Charts:',
					size: 'large',
					onEscape: function ()
					{
						box.modal('hide');
					}

				});
				
				$("#noofseats").bind("keyup click change", function (e)
				{

					var seat = $('#noofseats').val();
					if (seat == 0 || seat == '')
					{
						$('#bagunit').text(0);
					}
					else if (seat == 1)
					{
						$('#bagunit').text(3);
					}
					else if (seat == 2)
					{
						$('#bagunit').text(5);
					}
					else
					{
						$('#bagunit').text(6);
					}
				});
				
				
			}
			else
			{
				$('#<?= CHtml::activeId($model, "bkg_vehicle_type_id") ?>').val(vht);
				$('#cabrate-form1').submit();
			}
		}
	}

	function flexxiShare_sub()
	{
		$('#<?= CHtml::activeId($model, 'bkg_flexxi_type') ?>').val(2);
		$('#flexxiSearchCheck').val(1);
		$('#cabrate-form1').submit();
	}

	function flexxiShare_subQuick(obj)
	{
		var pickupdate = $(obj).attr('pickupdate');
		var time1 = $(obj).attr('time1');
		var time2 = $(obj).attr('time2');

		$('#<?= CHtml::activeId($model, 'bkg_flexxi_quick_booking') ?>').val(1);
		$('#<?= CHtml::activeId($model, 'bkg_pickup_date_date') ?>').val(pickupdate);
		$('#<?= CHtml::activeId($model, 'time1') ?>').val(time1);
		$('#<?= CHtml::activeId($model, 'time2') ?>').val(time2);
		$('#<?= CHtml::activeId($model, 'bkg_flexxi_type') ?>').val(2);
		$('#flexxiSearchCheck').val(1);
		$('#cabrate-form1').submit();


	}

	function flexiShare_promo()
	{
		if ($('#noofseats').val() == '' || $('#noofseats').val() == 0)
		{
			box1 = bootbox.dialog({
				message: 'No. of seat is mandatory',
				title: 'Input Error',
				size: 'medium',
				onEscape: function ()
				{
					return false;
				}
			});
		}
		else
		if (parseInt($('#noofseats').val()) > parseInt($('#noofseats').attr('max')))
		{
			box1 = bootbox.dialog({
				message: 'Number of seats can not be greater than ' + $('#noofseats').attr('max') + "<br>",
				title: 'Input Error',
				size: 'medium',
				onEscape: function ()
				{
					return false;
				}
			});

		}
		else
		{
			$('#<?= CHtml::activeId($model, 'bkg_no_person') ?>').val($('#noofseats').val());
			$('#<?= CHtml::activeId($model, 'bkg_num_small_bag') ?>').val(0);
			$('#<?= CHtml::activeId($model, 'bkg_num_large_bag') ?>').val(0);
			$('#<?= CHtml::activeId($model, 'bkg_flexxi_type') ?>').val(1);
			$('#cabrate-form1').submit();
			box.modal('hide');
		}
	}

	$('#flexxiCardView').ready(function ()
	{
		var date = '<?= $model->bkg_pickup_date ?>';
		var fromCity = '<?= $model->bkg_from_city_id ?>';
		var toCity = '<?= $model->bkg_to_city_id ?>';
		var href1 = '<?= Yii::app()->createUrl('booking/flexxiavailableslots') ?>';
		jQuery.ajax({'type': 'GET', 'url': href1, 'dataType': 'html',
			'data': {'pickupDate': date, 'fromCity': fromCity, 'toCity': toCity},
			success: function (data)
			{
				$('#flexxiCardView').html(data);
			}
		});
	});
</script>
<script type="text/javascript">
	
	
</script>