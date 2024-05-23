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
#$quotes				 = $model->getQuote(null, true);
/* @var $model BookingTemp */
$quoteModel			 = $model->quotes;
$isFlexxiExcluded	 = false;
$excludeCabType		 = BookingSub::getexcludedCabTypes($model->bkg_from_city_id, $model->bkg_to_city_id);
if (in_array(11, $excludeCabType))
{
	$isFlexxiExcluded = true;
}
// Car Master Details
$cabData = VehicleTypes::model()->getMasterCarDetails();

/** @var TbActiveForm $form */
$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
	'id'					 => 'cabrate-form1',
	'enableClientValidation' => true,
	'clientOptions'			 => array(),
	'enableAjaxValidation'	 => false,
	'errorMessageCssClass'	 => 'help-block',
	'htmlOptions'			 => array(
		'class'			 => 'form-horizontal',
		'autocomplete'	 => 'off'
	),
		));
?>

<?= $form->hiddenField($model, 'bkg_id', ['id' => 'bkg_id3', 'class' => 'clsBkgID']); ?>
<?= $form->hiddenField($model, 'hash', ['id' => 'hash3', 'class' => 'clsHash', 'value' => $model->getHash()]); ?>
<?= $form->hiddenField($model, "bkg_flexxi_type"); ?>    
<?= $form->hiddenField($model, "bkg_vehicle_type_id"); ?>
<?= $form->hiddenField($model, "bkg_trip_distance"); ?>
<?= $form->hiddenField($model, "bkg_trip_duration"); ?>
<?= $form->hiddenField($model, 'bkg_no_person') ?>
<?= $form->hiddenField($model, 'bkg_num_large_bag') ?>
<?= $form->hiddenField($model, 'bkg_num_small_bag') ?>
<?= $form->hiddenField($model, 'bkg_rate_per_km_extra'); ?>
<?= $form->hiddenField($model, 'bkg_package_id'); ?>

<input type="hidden" id="step2" name="step" value="2">

<div class="panel">            
	<div class="panel-body pt0 pb0 p0">
		<div class="row">
			<div class="col-sm-12">
				<div class="row">
					<div id="error-border" style="<?= (CHtml::errorSummary($model) != '') ? "border:2px solid #a94442" : "" ?>" class="route-page1 mb0">

						<div class="row">
							<div class="col-xs-9 col-sm-8">
								<?php if($model->bkg_booking_type == 4) {?>
								<h3 class="mb0 mt0">
									<?php
										$firstLocation = explode(',', $model->bookingRoutes[0]->brt_from_location);
										$secondLocation = explode(',', $model->bookingRoutes[0]->brt_to_location);
									?> 
									<span data-toggle="tooltip" title="<?= $model->bookingRoutes[0]->brt_from_location ?>"><?= $firstLocation[0] ?> - </span>
									<span data-toggle="tooltip" title="<?= $model->bookingRoutes[0]->brt_to_location ?>"><?= $secondLocation[0] ?></span>
								</h3>
								<?php }else{ ?>
								<h2 class="mb0 mt0">
									<?php							
										echo implode(' &rarr; ', $quoteModel->routeDistance->routeDesc);
									?> 
								</h2>
								<?php }
								if ($quotes)
								{
									?>
									<p>Estimated Distance: <b> <?= $quoteModel->routeDistance->tripDistance . " Km" ?></b>,
										Estimated Time: <b>							
											<?= BookingRoute::model()->populateTripduration($quoteModel->routes); ?>							
										</b></p>
									<?php
								}
								else
								{
									?>
									<br/><p><b>Sorry cab is not available for this route.</b></p>
								<?php } ?>
								<!--<h5 class="hide">If there are any issues with your booking we will contact you. Please share your phone and email address below.</h5>-->			
							</div>

<?php
$dboApplicable = Filter::dboApplicable($model);
if ($dboApplicable)
{
?>
							<div class="col-sm-4 text-right pr0 mb20">
								<div class="row">

									<div class="col-sm-12"><img src="/images/doubleback_fares2.jpg" alt="" width="350"></div>
								</div>
							</div>      
<?php
}
?>

						</div>
						<!-- packages start -->
					</div>
				</div>

				<!--packages end-->
			</div>

			<div>
				<?
				$i = 0;
				foreach ($quotes as $key => $quote)
				{
					if (!$quote->success)
					{
						$i = 1;
						continue;
					}

					$shareBooking = false;
					if ($model->bkg_booking_type == 1 && !$isFlexxiExcluded && $key == 3)
					{
						$shareBooking = true;
					}
					$flexxRates	 = $quote->flexxiRates;
					/* @var $quote Quote */
					$cab		 = $cabData[$key];

					// Fare Breakup Tooltip
					$details = $this->renderPartial("bkFareBreakup", ['quote' => $quote], true);

					$promoDiscount	 = $quote->routeRates->discount;
					$discBaseAmount	 = $quote->routeRates->baseAmount - $promoDiscount;

					$tolltax_value	 = $quote->routeRates->tollTaxAmount | 0;
					$tolltax_flag	 = $quote->routeRates->isTollIncluded | 0;
					$statetax_value	 = $quote->routeRates->stateTax | 0;
					$statetax_flag	 = $quote->routeRates->isStateTaxIncluded | 0;

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
							<?php
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
					<div class="col-xs-12 search-cabs-box mb30 hidden-xs">
						<div class="row">
							<div class="col-xs-12 col-sm-3 border-rightnew">
								<div class="car-style"><?= $cab['vht_make'] ?></div>
								<div class="car_box"><img src="<?= Yii::app()->baseUrl . '/' . $cab['vht_image'] ?>" alt="" ></div>
								<p class="text-center" style="line-height:16px;"><?= $cab['vht_model'] ?></p>
							</div>
							<div class="col-xs-12 col-sm-9">

								<?php
								$bestPriceRange = '';
								if (isset($quote->pickupDate) && isset($quote->routeRates->bestRateDate))
								{
									$bestPriceRange = "You have found our best price";
									if (date("YmdHis", strtotime($quote->pickupDate)) != date("YmdHis", strtotime($quote->routeRates->bestRateDate)))
									{
										$bestPriceRange = "Get a lower price if you travel on " . date("d/m/y", strtotime($quote->routeRates->bestRateDate));
									}
								}
								if ($bestPriceRange != '')
								{
									?>
									<div class="col-xs-12 wrap-panel">
										<span><?= $bestPriceRange ?></span>
									</div>
								<?php } ?>
								<div class="row p10">
									<div class="col-xs-12 col-md-8 col-sm-7 mobile-view-p border-lt">
										<div class="search-icon-box">
											<img src="/images/search_icon_1.png" alt="Capacity" title="Capacity"><br>
											<?= $cab['vht_capacity'] ?> Seats + Driver
										</div>
										<div class="search-icon-box">
											<img src="/images/search_icon_2.png" alt="Luggage Capacity" title="Luggage Capacity"><br>
											<?= $cab['vht_big_bag_capacity'] ?> Big bag(s) + <?= $cab['vht_bag_capacity'] ?> Small bag(s)
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
												*Note: Ext. Chrg. After <?= $quote->routeDistance->quotedDistance ?> Kms. as applicable.
											</div>
										</div>
									</div>

									<div class="col-xs-12 col-md-4 col-sm-5 search-icon-box2 pl0 pr0">
										<div class="row">
											<div class="col-xs-12" style="line-height: 18px;">
												<span class="m0 text-uppercase text-muted" ><b>Base Fare</b></span><br>
												<?php
												if ($quote->routeRates->baseAmount > $discBaseAmount)
												{
													?>
													<span style="font-size: 16px; line-height: normal; font-weight: bold;">
														<i class="fa fa-inr"></i><strike style="font-weight: bold;"><?= $quote->routeRates->baseAmount; ?></strike><br>
													</span>
												<?php } ?>
												<span style="font-size: 22px; color: #2458aa; line-height: normal;font-weight: bold;">
													<i class="fa fa-inr"></i><?= $discBaseAmount ?><sup>*</sup><a data-toggle="popover" id="b<?= $cab['vht_id'] ?>"  data-placement="top" data-html="true" data-content="<?= $details ?>" style="font-size:15px;"><i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="Fair Breakup" data-placement="botton"></i></a>
												</span>
											</div>
										</div>
										<div class="row">
											
                                            <?php
                                             if ($model->bkg_booking_type == 1 || $model->bkg_booking_type == 2 || $model->bkg_booking_type == 3)
											 {
                                            ?>
											<div class="col-xs-12 col-md-5 col-sm-5">
												<button type="button" value="<?= $cab['vht_id'] ?>" kmr="<?= $quote->routeRates->ratePerKM ?>" kms="<?= $quote->routeDistance->tripDistance ?>" duration="<?= $quote->routeDuration->totalMinutes ?>" data-cabtype="<?= $cab['vht_make'] ?>" name="bookButton" class="btn next3-btn mt10 " onclick="validateForm1(this);">
													<b>Book Now</b><br><span class="subbtn">Full Cab</span>
												</button>
											</div>
											<div class="col-xs-12 col-md-7 col-sm-7" style="margin-top: 15px;"> 
												<a href="javascript:void(0)" onclick="showPackageList('<?= $key?>')" id="btn-package-show<?= $key ?>" class="btn btn-info">Show Package List</a>
												<a href="javascript:void(0)" onclick="hidePackageList('<?= $key?>')" id="btn-package-hide<?= $key ?>" class="btn btn-default hide">Hide Package List</a>
											</div>
                                            <?php
											 }else{
                                            ?>
                                            <button type="button" value="<?= $cab['vht_id'] ?>" kmr="<?= $quote->routeRates->ratePerKM ?>" kms="<?= $quote->routeDistance->tripDistance ?>" duration="<?= $quote->routeDuration->totalMinutes ?>" data-cabtype="<?= $cab['vht_make'] ?>" name="bookButton" class="btn next3-btn mt10 " onclick="validateForm1(this);">
													<b>Book Now</b><br><span class="subbtn">Full Cab</span>
											</button>
                                             <?php
											 }
                                             ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div id="packageQuotes<?=$key ?>" style="display:none">
							
					</div>

					<?php
				}
				?>
			</div>

			<?
			/* if (count($quotePackages) > 0)
			  {
			  $noPackages = count($quotePackages);
			  ?>
			  <div class=" btn btn-primary  col-sm-3 mb10" id="pckShowBtn" style="display: none" onclick="showPackageList()"><?= $noPackages ?> related packages found. Click to show</div>
			  <div class=" btn btn-success btn-circle  col-sm-3 mb10" id="pckHideBtn"    onclick="hidePackageList()"> Click to hide packages</div>
			  <?php
			  } */
			?>



			<?
			if ($i != 1)
			{//echo "<br/><p><b>Sorry cab is not available for this route.</b></p>";
			}
			?>
		</div>
	</div>
</div>

<?php $this->endWidget(); ?>
<script>
    $bkgId = '<?= $model->bkg_id ?>';
    $hash = '<?= $model->getHash() ?>';
    var bookNow = new BookNow();
    var data = {};
    $(document).ready(function ()
    {
        bookNow.bkQuoteReady($bkgId, $hash);
        hyperModel.initializeplAirport();
    });
    $('#bdate').html('<?= date('\O\N jS M Y \<\b\r/>\A\T h:i A', strtotime($model->bkg_pickup_date)) ?>');
    function validateForm1(obj)
    {
        var pckid = $(obj).attr("pckid");
        if (pckid > 0)
        {
            $('#BookingTemp_bkg_package_id').val(pckid);
        }
        data.extraRate = "<?= CHtml::activeId($model, "bkg_rate_per_km_extra") ?>";
        data.vehicleTypeId = "<?= CHtml::activeId($model, "bkg_vehicle_type_id") ?>";
        data.flexiUrl = "<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/additionaldetail/flexi/2')) ?>";
        data.bkgTripDistance = "<?= CHtml::activeId($model, "bkg_trip_distance") ?>";
        data.bkgTripDuration = "<?= CHtml::activeId($model, "bkg_trip_duration") ?>";
        bookNow.data = data;
        bookNow.validateQuote(obj);
    }

    function flexxiShare_sub()
    {
        data.extraRate = "<?= CHtml::activeId($model, "bkg_rate_per_km_extra") ?>";
        data.vehicleTypeId = "<?= CHtml::activeId($model, "bkg_vehicle_type_id") ?>";
        data.flexiUrl = "<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/additionaldetail/flexi/2')) ?>";
        bookNow.data = data;
        bookNow.searchFlexi('<?= $model->bkg_id ?>');
    }

    function flexiShare_promo(obj)
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
            data.extraRate = "<?= CHtml::activeId($model, "bkg_rate_per_km_extra") ?>";
            data.vehicleTypeId = "<?= CHtml::activeId($model, "bkg_vehicle_type_id") ?>";
            data.bkgTripDistance = "<?= CHtml::activeId($model, "bkg_trip_distance") ?>";
            data.bkgTripDuration = "<?= CHtml::activeId($model, "bkg_trip_duration") ?>";
            $('#<?= CHtml::activeId($model, 'bkg_no_person') ?>').val($('#noofseats').val());
            $('#<?= CHtml::activeId($model, 'bkg_num_small_bag') ?>').val(0);
            $('#<?= CHtml::activeId($model, 'bkg_num_large_bag') ?>').val(0);
            $('#<?= CHtml::activeId($model, 'bkg_flexxi_type') ?>').val(1);
            bookNow.data = data;
            bookNow.sendQuoteToInfo(obj);

            boxFlexxi.modal('hide');
        }
    }
    function showPackageDetails(id)
    {
        $href = '<?= Yii::app()->createUrl('booking/showPackage', ['listshow' => true, 'pck_id' => '']) ?>' + id;
        jQuery.ajax({type: 'GET', url: $href,
            success: function (data)
            {
                multicitybootbox = bootbox.dialog({
                    message: data,
                    size: 'small',
                    title: 'Package Info',
                    onEscape: function ()
                    {
                    },
                });
            }
        });
    }
    function showPackageList(key)
    {
		var self = this;
		$.ajax({
			type: "POST",
			url: "<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/packageQuote')) ?>",
			data: {'bkgid':$bkgId,'cab':key,'YII_CSRF_TOKEN':$('input[name="YII_CSRF_TOKEN"]').val()},
			success: function (data1)
			{
				$('#packageQuotes'+key).html(data1);
				$('#packageQuotes'+key).show('slow');
				$('#btn-package-hide'+key).removeClass('hide');
				$('#btn-package-show'+key).addClass('hide');
			},
			error: function (error)
			{
				console.log(error);
			}
		});
    }
    function hidePackageList(key)
    {
        $('#packageQuotes'+key).hide('slow');
		$('#btn-package-show'+key).removeClass('hide');
		$('#btn-package-hide'+key).addClass('hide');
    }

</script>
