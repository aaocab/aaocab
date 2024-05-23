<style>
    .hide{
		display: none;
	}
    .link-list-1 a{
        width: 100px !important;
    }
div{ position: relative;}

</style>
<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/fontawesome-web/css/all.min.css?v0.6');
?>
<?php 
//$model->getRoutes();
//$quotes		 = $model->getQuote();
/* @var $model BookingTemp */
$quoteModel = $model->quotes;

$serviceClass			 = [];
$cabData				 = SvcClassVhcCat::getVctSvcList("allDetail");
$categories				 = [];
$classes				 = [];
$categoryServiceClasses	 = [];

$filterList		 = array_keys($quotes);
$svcVctResult	 = SvcClassVhcCat::mapAllCategoryServiceClass($categoryServiceClasses, $classes, $categories, $filterList);

$isFlexxiExcluded	 = false;
$excludeCabType		 = BookingSub::getexcludedCabTypes($model->bkg_from_city_id, $model->bkg_to_city_id);
if (in_array(11, $excludeCabType))
{
	$isFlexxiExcluded = true;
}

/** @var CActiveForm $form */
$form = $this->beginWidget('CActiveForm', array(
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
<?= $form->hiddenField($model, 'bkg_from_city_id'); ?>
<?= $form->hiddenField($model, 'bkg_vht_id'); ?>
<input type="hidden" id="step2" name="step" value="2">
<input type="hidden" id="islogin" name="islogin" value="0">

<?php
if ($quotes)
{
	?>

	<div class="top-10" id="p_clist">
		<?php $this->renderPartial("bkRouteHeader" . $this->layoutSufix, ['prevStep' => 2, 'model' => $model, 'quoteModel' => $quoteModel]); ?>   

		<?php
		$quotePackagesSorted = [];
		if (count($quotePackages) > 0)
		{

			$noPackages = count($quotePackages);
			?>
			<?php
			foreach ($quotePackages as $packid => $quotePackageData)
			{
				foreach ($quotePackageData as $key => $quotePackage)
				{
					if (!$quotePackage->success)
					{
						$i = 1;
						continue;
					}
					$cab = $cabData[$key];

					$promoDiscount																 = $quotePackage->routeRates->discount;
					$discBaseAmount[]															 = $quotePackage->routeRates->baseAmount - $promoDiscount;
					$quotePackagesSorted[$quotePackage->routeRates->baseAmount][$packid][$key]	 = $quotePackage;
				}
			}
			ksort($quotePackagesSorted);
			?>
			<div class="content-boxed-widget btn-orange font-13 wrapword line-height16" id="pckShowBtn" style="display: "><?php //= $noPackages                   ?>Packages from <?= $quoteModel->routeDistance->routeDesc[0] ?> to <?= $quoteModel->routeDistance->routeDesc[1] ?> starts from <span>₹</span> <b><?= min($discBaseAmount) ?></b>. <br>Check Now</br></div>
			<?php
		}
		?>
		<div id="sidebar-right-over-package" data-selected="menu-components" class="menu-box menu-sidebar-right-over" style="transition: all 300ms ease 0s;">
			<div class="menu-title">
				<h1 class="mt10">Packages</h1>
				<a href="#" class="menu-hide mt10" style="margin-top: -2px;"><i class="fa fa-times"></i></a>
			</div>
			<div id="packageQuotes">

			</div>
		</div> 

		<div><a href="#" data-menu="sidebar-right-over-cabsearch" class="header-icon header-icon-2 hide"></a></div>
		<div id="sidebar-right-over-cabsearch" data-selected="menu-components" class="menu-box menu-bottom menu-list-bottom" style="transition: all 300ms ease 0s;">
			<div class="menu-title">
				<h1 class="mt10">Filter By</h1>
			</div>
			<div id="cabSearch"> </div>
		</div> 

		<div class="clear"></div>
		<?php
		$k					 = 0;
		$categoryInfo		 = VehicleCategory::model()->cache(5 * 24 * 60 * 60, new CacheDependency(CacheDependency::Type_CabTypes))->findByPk($category);
		$quote				 = $quotes[0];
		/* @var $quote Quote */
		$cab				 = $cabData[$category];
		?>

		<div class="content-padding content-boxed-widget mb0 pb0 p0 list-view-panel" style="overflow: hidden;">
			<div class="content-padding p15 pb0 pt10 border-top">
				<div class="one-third mt10 mr0 p5 pl0 bottom-10">
					<img src="<?= Yii::app()->baseUrl . '/' . $categoryInfo['vct_image'] ?>" alt="" width="150" class="preload-image responsive-image mb0">
				</div>
				<div class="one-fourth mt5">
					<div class="font-14 uppercase line-height18 color-blue"><b><?= $categoryInfo['vct_label'] ?></b></div>
					<div class="font-13 line-height16 color-gray-dark mb10">
						<?= $categoryInfo['vct_capacity'] ?> Seats + Driver | AC
					</div>
					<div class="font-13 line-height16 mb5 color-gray-dark"><?= $categoryInfo['vct_desc'] ?></div>
					<!--                    <div class="font-13 line-height16 color-gray-dark mb10">
					<?= $categoryInfo['vct_capacity'] ?> Seats + Driver, <?= $categoryInfo['vct_big_bag_capacity'] ?> Big bags + <?= $categoryInfo['vct_small_bag_capacity'] ?> Small bag, AC, KM in Quote <?= $quoteModel->routeDistance->tripDistance ?> Km
										</div>-->
				</div>

				<div class="clear"></div>
			</div>

			<div class="">
				<div class="content p0 bottom-0 text-center content-widget-accordion">
					<?php
					$countQtNotAvailable = 0;
					//foreach ($arrServiceClass as $sccId => $arrServiceCls)
					$cabPriceArray		 = array();
					foreach ($classes as $class => $clsRank)
					{
						$sccId			 = $class;
						$classInfo		 = ServiceClass::model()->cache(5 * 24 * 60 * 60, new CacheDependency(CacheDependency::Type_CabTypes))->findByPk($class);
						$relKey			 = $class . '|' . $sccId;
						$scvId			 = $categoryServiceClasses[$category][$sccId];
						$luggageCapacity = Stub\common\LuggageCapacity::init($category, $sccId, 1);

						/* @var $quote Quote */
						$quote = false;
						if (array_key_exists($scvId, $quotes))
						{
							$quote = $quotes[$scvId];
						}
						else
						{
							continue;
						}

						if ($sccId == 4 && $quote->success)
						{
							$carModelsSelectTier = ServiceClassRule::model()->getCabByClassId($sccId, $quote->routeRates->baseAmount, $categoryInfo->vct_id);
							if (!$carModelsSelectTier)
							{
								continue;
							}
						}

						if ($quote->success)
						{
							//$details = $this->renderPartial("bkFareBreakup", ['quote' => $quote, 'scvid' => $scvId], true);

							$promoDiscount	 = $quote->routeRates->discount;
							$discBaseAmount	 = $quote->routeRates->baseAmount - $promoDiscount;
							array_push($cabPriceArray, $discBaseAmount);
							//$datamenu = ($sccId == 4) ? 'data-menu="sidebar-right-over-booknow"' : ""
							?>

                            <div class="content m10 p0" style="border: #ededed 1px solid; border-radius: 5px; position: relative;">    
								<div class="content-widget-link mb0 pb5" onclick="return showDesc('<?= $sccId ?>', '<?= $scvId ?>', '<?= $categoryInfo->vct_id ?>');">
									<input id="box1-fac-radio-full<?= $scvId ?>" class="qouteRadio ml10 mt15" type="radio"  name="qouteRadio" value="<?= $scvId ?>">
									<a href="#" class="<?= $sccId ?> mb5" value="<?= $scvId ?>">
										<div class="one-half font-18 text-left mr0 pt15 pb10">
											<b><?= $classInfo['scc_label'] ?></b> <span style="position: absolute;line-height: 11px;top: 20px;margin-left: 5px;" class="label-orange font-9 color-white"><?= ($classInfo['scc_is_cng'] == 1) ? 'CNG' : 'Diesel / Petrol'; ?></span>

										</div>
									</a>
									<input type="hidden" value="<?= ($promoDiscount > 0) ? $promoDiscount : 0; ?>" id="cabDiscountPromo<?= $scvId ?>">
									<input type="hidden" value="<?= ($quote->routeRates->baseAmount > 0) ? $quote->routeRates->baseAmount : 0; ?>" id="cabBaseAmt<?= $scvId ?>">
									<?php
									if ($quote->routeRates->baseAmount > $discBaseAmount)
									{
										?>
										<span class="font-18 color-gray weight400 float-right pr10 mt5">
											<span>&#x20b9</span><strike class="clsBaseAmt<?= $scvId ?>"><?= $quote->routeRates->baseAmount; ?></strike>
										</span>
										<?php
									}
									?>

									<?php
									if ($quote->gozoNow)
									{
										?>
										<div class="one-half last-column text-right style-2 pr15 pt0 pb10" style="position: absolute; top: 15px; right: 10px;">
											<h4 class="mt0 mb0 pr10" style="line-height: 0.8em"><span class="font16"><span>&#x20b9</span><b class="clsBaseAmtDisc<?= $scvId ?>"><?php echo $quote->routeRates->minBaseAmount . " - <span>&#x20b9</span>" . $quote->routeRates->maxBaseAmount ?> </b></span> <span class="font14">Estimated</span></h4>
										</div>
										<?php
									}
									else
									{
										?>
										<div class="one-half last-column text-right style-2 pr15 pt0 pb10" style="position: absolute; top: 24px; right: 10px;">

											<h4 class="mt0 mb0 pr10"><span class="font14"><span>&#x20b9</span><b class="clsBaseAmtDisc<?= $scvId ?>"><?= $discBaseAmount; ?></b></span></h4>
											<div class="accordion">

												<div class="accordion-path">
                                        <div class="accordion accordion-style-0 box-text-8 p0 font-11" style="position: relative; height: 7px;">
                                            <a href="javascript:void(0);" class="widget-link mt10" style="line-height: 12px; height: 18px!important; font-size: 11px!important;" onclick="showFareBreakup('<?= $k ?>');">  <img src="/images/info.png?v=0.3" alt="" width="20"></a>
													</div>
												</div>                     
											</div>
										</div>
										<?
									}
									?>




									<div class="clear"></div>
								</div>
								<div class="m20 mt20 mb0">
									<p class="text-left bottom-10 bolder">What do you get in <?= $classInfo['scc_label']; ?>? <a href="javascript:void(0);" onclick="viewMoreInfo('<?php echo $k + 1; ?>');">View more</a></p>
									<div class="accordion-texts font-11">
										<div class="pl0 ul-panel text-left" style="display: flex; flex-wrap: wrap; ">
											<?php
											$routeRates1 = $quote->routeRates;
											$srvDesc	 = json_decode($classInfo->scc_desc);
											//print'<pre>';print_r($srvDesc);
											foreach ($srvDesc as $key => $value)
											{
												?>
												<div class="one-half pb20 mr10" style="display: flex;">
													<div class="icon-styles">
                                        <span><?php echo $value;?></span>
													</div>
												</div>
											<?php } ?>
											<div class="one-half pb20 mr10" style="display: flex;">
												<div class="icon-styles">
                                        <span> 
														<?= (($luggageCapacity->largeBag != 0) ? $luggageCapacity->largeBag . ' Big bags /' : '') ?>
														<?= (($luggageCapacity->smallBag != 0) ? $luggageCapacity->smallBag . ' Small bag ' : '') ?>
												<!-- <? //=$luggageCapacity->largeBag                  ?><? //= $categoryInfo['vct_big_bag_capacity']                  ?> Big bags / <? //=$luggageCapacity->smallBag                 ?> <? //= $categoryInfo['vct_small_bag_capacity']                  ?> Small bag-->
													</span>
												</div>
											</div>

											<div class="clear"></div>
											<div><a href="#" data-menu="sidebar-right-over-view<?= $k + 1; ?>" class="header-icon header-icon-2 hide"></a></div>
											<div id="sidebar-right-over-view<?= $k + 1; ?>" data-selected="menu-components" data-width="300" data-height="400" class="menu-box menu-modal">
												<div class="menu-title">
													<h1 class="mt10">Inclusions & Exclusions</h1>
													<a href="#" class="menu-hide pt0 line-height42"><i class="fa fa-times"></i></a>
												</div>
												<div id="viewmore" class="p15"> 
													<p class="uppercase mb0"><b>included</b></p>
													<ul>
														<li>Upto <?= $quote->routeDistance->tripDistance ?> kms for the exact itinerary listed below</li>
														<li>NO route deviations allowed unless listed in itinerary</li> 
														<?php
														if ($routeRates1->isNightPickupIncluded > 0 && $routeRates1->includeNightAllowance > 0)
														{
															?>
															<li>Night pickup allowance included (pickup time is between 10pm and 6am).</li>
														<?php } ?>
														<?php
														if ($routeRates1->isNightDropIncluded > 0)
														{
															?>
															<li>Night dropoff allowance included (drop off time is between 10am and 6am).</li>
														<?php } ?>
														<li>GST</li>
														<?php
														if ($routeRates1->isTollIncluded > 0)
														{
															?>
															<li>Toll Tax (Included)</li>
														<?php } ?>
														<?php
														if ($routeRates1->isStateTaxIncluded > 0)
														{
															?>
															<li>State Tax (Included)</li>
														<?php } ?>
													</ul>
													<p class="uppercase mb0"><b>excluded</b></p>
													<ul>
														<?php
														if ($routeRates1->isTollIncluded <= 0)
														{
															?>
															<li>Toll Tax (Excluded)</li>
														<?php } ?>
														<?php
														if ($routeRates1->isStateTaxIncluded <= 0)
														{
															?>
															<li>State Tax (Excluded)</li>
														<?php } ?>
														<?php
														if ($routeRates1->isNightPickupIncluded <= 0)
														{
															?>
															<li>Night pickup allowance excluded.</li>
														<?php } ?>
														<?php
														if ($routeRates1->isNightDropIncluded <= 0)
														{
															?>
															<li>Night dropoff allowance	excluded.</li>
														<?php } ?>
													</ul>

												</div>
											</div> 

										</div>
									</div>
								</div>
							</div>
							<div class="clear"></div>
							<?php
							if ($sccId == 4)
							{
								?>
								<div class="content p0 m0 text-left pl10 pr10">
									<div id="modelsSelectClass<?= $scvId ?>" class="modelsSelectClass hide">
										<?php echo $this->renderPartial("showVehicleModel", ['quotes' => $quotes, 'model' => $model, 'scvId' => $scvId, 'sccId' => $sccId, 'carModelsSelectTier' => $carModelsSelectTier], true); ?>
									</div>
								</div>
							<?php } ?>
							<div><a href="#" data-menu="sidebar-right-over-view1<?= $k ?>" class="header-icon header-icon-2 hide"></a></div>
							<div id="sidebar-right-over-view1<?= $k ?>" data-selected="menu-components" data-width="300" data-height="300" class="menu-box menu-modal">
								<div class="menu-title">
									<h1 class="mt10">Detailed Fare Breakup</h1>
									<a href="#" class="menu-hide pt0 line-height42"><i class="fa fa-times"></i></a>	
								</div>
								<div class="accordion-text text-left p15 pt10" id="accFareBreakup<?= $scvId; ?>">
									<?php echo $this->renderPartial("bkFareBreakup", ['quote' => $quote, 'scvId' => $scvId], true); ?>
								</div>

							</div>

							<?php
						}
						else
						{
							?>
							<a href="#" class="bg-blue<?= $sccId ?>">
								<img src="/images/no_cabs_available.png" alt="No Cabs Available" class="responsive-image">
							</a>
							<?php
						}
						?>	

						<div class="accordion-content accordion-style-0 box-text-8" id="accordion-2<?= $k + 1; ?>" style="display: none;">
							<p class="text-left uppercase bolder">What do you get in <?= $classInfo['scc_label']; ?> ?</p>

						</div>
						<input type="hidden" id="desc_<?= $sccId ?>" value='<?= $classInfo['scc_desc'] ?>'>
						<input type="hidden" id="booknow_<?= $scvId ?>" value="<?= $scvId ?>">
						<input type="hidden" id="packagenow_<?= $scvId ?>" value="<?= $scvId ?>">
						<?php
						$k++;
					}


					//print'<pre>';print_r($cabPriceArray);
					?>
				</div>
			</div>

																	<!--                <div class="widget-price bolder"> &#x20B9;<?php //echo min($cabPriceArray);                ?></div>-->


			<div class="content-padding p0 pt10 pb10 fixed-widget-content">
				<?php $logincls		 = (Yii::app()->user->getId() == 0) ? '' : 'hide'; ?>
				<a href="javascript:void(0);" id="booknowcablogin<?= $categoryInfo->vct_id ?>" data-value="<?= $scvId ?>" kmr="<?= $quote->routeRates->ratePerKM ?>" pckid ="<? echo ($model->bkg_package_id > 0) ? $model->bkg_package_id : 0; ?>"
				   kms="<?= $quote->routeDistance->tripDistance ?>" duration="<?= $quote->routeDuration->totalMinutes ?>" data-cabtype="<?= $categoryInfo->vct_label ?>" 
				   data-serviceclass = "<?= $cab['scc_id'] ?>" name="bookButton" type="button"  class="nav-link login bg-green-dark font-12 bottom-10 p5 <?= $logincls ?> text-center" 
				   onclick="validateForm1(this);" class="nav-link login bg-green-dark font-12 bottom-10 p5" onclick="validateForm1(this);">Logged in users save upto 20%. Tap to Login</a>
				<div class="one-half mr10 pl15"><Span class="font-16 color-gray"><?= $categoryInfo['vct_label'] ?></span><b><span class="font-16" id="serveclassname"></span></b><br><span class="font-18 clsBaseFare"></span></div>
				<div class="one-half last-column text-right">
					<button id="booknowcab<?= $categoryInfo->vct_id ?>" value="<?= $scvId ?>" kmr="<?= $quote->routeRates->ratePerKM ?>" pckid ="<? echo ($model->bkg_package_id > 0) ? $model->bkg_package_id : 0; ?>"
							kms="<?= $quote->routeDistance->tripDistance ?>" duration="<?= $quote->routeDuration->totalMinutes ?>" data-cabtype="<?= $categoryInfo->vct_label ?>" 
							data-serviceclass = "<?= $cab['scc_id'] ?>" name="bookButton" type="button"  class="uppercase btn-2 mr5 font-14" 
							onclick="validateForm1(this);">
						Book Now
					</button>
				</div>

			</div>

		</div>

		<div class="content-padding content-boxed-widget mb10 pb0 p0 pt0" style="overflow: hidden;">
			<?php
			$bestPriceRange	 = '';
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
				?>					<div class="headding-part1 text-center"><?= $bestPriceRange ?></div>
				<?php
			}
			?>
		</div>

		<div class="clear"></div>
		<?php ?>
		<?php ?>
	</div>     
	<?php
}

$this->endWidget();
?>
<script>
    $bkgId = '<?= $model->bkg_id ?>';
    $hash = '<?= $model->getHash() ?>';
    var bookNow = new BookNow();
    var data = {};

    function showPackageDetails(id, key)
    {
        //alert(id+"***"+key);
        $href = '<?= Yii::app()->createUrl('booking/showPackage', ['listshow' => true, 'pck_id' => '']) ?>' + id;
        jQuery.ajax({type: 'GET', url: $href,
            success: function (data) {

                $("#dataDetails" + key).html(data);
            }
        });
    }

    $('#bdate').html('<?= date('\O\N jS M Y \<\b\r/>\A\T h:i A', strtotime($model->bkg_pickup_date)) ?>');
    function validateForm1(obj)
    {

        $('#BookingTemp_bkg_package_id').val(0);
        $('#bkg_package_id1').val(0);
        //var btnVal = obj.value;
        var isLogin = $(obj).hasClass("login");
        if (isLogin == true) {
            $('#islogin').val(1);
            var btnVal = obj.dataset.value;
        } else {
            $('#islogin').val(0);
            var btnVal = obj.value;
        }

        var cls = obj.dataset.serviceclass;
        if (cls == 4 && ($("#<?= CHtml::activeId($model, "bkg_vht_id") ?>").val() == "0" || $("#<?= CHtml::activeId($model, "bkg_vht_id") ?>").val() == undefined || $("#<?= CHtml::activeId($model, "bkg_vht_id") ?>").val() == "" || $("#<?= CHtml::activeId($model, "bkg_vht_id") ?>").val() == null)) {
            setTimeout(function () {
                bookNow.showErrorMsg("Please select atleast one car model of your choice.");
            }, 1000);
            event.stopPropagation();
            return false;
        }
        if (cls != 4)
        {
            $("#<?= CHtml::activeId($model, "bkg_vht_id") ?>").val("0");
        }
        $("#<?= CHtml::activeId($model, "bkg_vehicle_type_id") ?>").val(btnVal);

        if ($(".qouteRadio:checked").val() != btnVal)
        {
            setTimeout(function () {
                bookNow.showErrorMsg("Please select atleast one cab.");
            }, 1000);
            event.stopPropagation();
            return false;
        } else
        {
            var pckid = $(obj).attr("pckid");
            if (pckid > 0) {
                $('#BookingTemp_bkg_package_id').val(pckid);
                $('#bkg_package_id1').val(pckid);
            }
            data.extraRate = "<?= CHtml::activeId($model, "bkg_rate_per_km_extra") ?>";
            data.vehicleTypeId = "<?= CHtml::activeId($model, "bkg_vehicle_type_id") ?>";
            data.flexiUrl = "<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/additionaldetail/flexi/2')) ?>";
            data.bkgTripDistance = "<?= CHtml::activeId($model, "bkg_trip_distance") ?>";
            data.bkgTripDuration = "<?= CHtml::activeId($model, "bkg_trip_duration") ?>";
            bookNow.data = data;
            bookNow.validateQuote(obj);
            $('#sidebar-right-over-booknow').removeClass('menu-box-active');
            $('#menu-hider').removeClass('menu-hider-active');
            $("#menu-hider").trigger("click");
            event.stopPropagation();
        }

    }
    $(document).on('click', '.menu-hide', function () {
        $("#menu-hider").trigger("click");
    });
    $(function () {
        $(".preload-search-image").lazyload({threshold: 0});
    });

    function showPackageList(obj)
    {
        var isChecked = $('.qouteRadio:checked').val();
        var key = $(obj).attr("cab-type");
        if (isChecked == key)
        {
            $.ajax({
                type: "POST",
                url: "<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/packageQuote')) ?>",
                data: {'bkgid': $bkgId, 'cab': key, 'YII_CSRF_TOKEN': $('input[name="YII_CSRF_TOKEN"]').val()},
                success: function (data1)
                {
                    $('#packageQuotes').html(data1);
                },
                error: function (error)
                {
                    console.log(error);
                }
            });
        } else
        {
            event.stopPropagation();
            setTimeout(function () {
                bookNow.showInfoMsg("Please select atleast one cab price.");
            }, 1000);
        }
        return false;
    }

    $('input[type="radio"]').on('click', function () {
        //var scvIdVal = $(this).val();
        var scvIdVal = $('.qouteRadio:checked').val();
        $('a[value="' + scvIdVal + '"]').click();
    })


    $("a[data-accordion]").click(function () { //debugger;
        var dataValue = $(this).data('accordion');
        var IsScvid = $('#' + dataValue).children().attr("id");
        if (IsScvid) {
            var childValue = IsScvid.match(/\d+/);
            $("#box1-fac-radio-full" + childValue).attr('checked', true).trigger('click');
            //$("#accFareBreakup" + childValue).removeClass("hide");
        }
    });

    function showDesc(cabId, scvId, vctId)
    {	//debugger;
        $("#box1-fac-radio-full" + scvId).prop('checked', true);
		$(".content-widget-link").removeAttr( "style" );
		$(".content-widget-link").find('a:first').removeAttr("style");
		$(".content-widget-link").find(".accordion-style-0 a").removeAttr("style");
		$("#box1-fac-radio-full"+scvId).parent().css('background','#0d4da7');
		$("#box1-fac-radio-full"+scvId).parent().css('color','#fff');
	    $("#box1-fac-radio-full"+scvId).next('a').css('color','#fff');
		$("#box1-fac-radio-full"+scvId).parent().find(".accordion-style-0 a").attr('style','color:#fff !important');
        $('#booknowcab' + vctId)[0].value = "";
        $('#booknowcab' + vctId)[0].dataset.serviceclass = "";
        $('#booknowcablogin' + vctId)[0].dataset.value = "";
        $('#booknowcablogin' + vctId)[0].dataset.serviceclass = "";
        var desc1 = JSON.parse($('#desc_' + cabId).val());
        var bookNow = $('#booknow_' + scvId).val();
        var bookNow1 = $('#packagenow_' + scvId).val();
        $('#showPackage' + vctId).attr('cab-type', scvId);
        $(".serviceDesc").empty();
        $.each(desc1, function (i, val) {
            if (i != 0)
            {
                $(".serviceDesc").append(' + ');
            }
            $(".serviceDesc").append(val);
        });
        $('#booknowcab' + vctId)[0].value = bookNow;
        $('#booknowcab' + vctId)[0].dataset.serviceclass = cabId;
        $('#booknowcablogin' + vctId)[0].dataset.value = bookNow;
        $('#booknowcablogin' + vctId)[0].dataset.serviceclass = cabId;
        $('input:radio[class=qouteRadio][id=box1-fac-radio-full' + scvId + ']').prop('checked', true);
        var isChecked = $('.qouteRadio:checked').val();

        if (isChecked == scvId)
        {
            //$("#accFareBreakup" + scvId).removeClass("hide");
            var FrBrkAccId = $("#accFareBreakup" + scvId).parent().attr("id");
            // $('#'+FrBrkAccId).removeAttr('style');  
            $('#' + FrBrkAccId).css('display', 'block');
            var IncExcAccId = $('#' + FrBrkAccId).next('div').attr("id");
            $('#' + IncExcAccId).css('display', 'none');
            var className = (cabId == 1) ? ', Value' : (cabId == 2) ? ', Value+' : (cabId == 6) ? ', Value(CNG)' : ', Select';
            $('#serveclassname').html(className);
            var basefare = $('.clsBaseAmtDisc' + scvId).text();
            $('.clsBaseFare').html('<b>₹' + basefare + '</b>');
        }

        if (cabId == 4)
        {
            $('#modelsSelectClass' + scvId).removeClass('hide');
            $('#modelsSelectClass' + scvId).show();
        } else {
            $('.modelsSelectClass').hide();
            $('input:radio[name="service_class_area"]').prop('checked', false);
            $("#<?= CHtml::activeId($model, "bkg_vht_id") ?>").val("0");
        }
        event.stopPropagation();
        return false;
    }

    function serachByCab() {
        //debugger;
        var passedArray = <?php echo json_encode($categories); ?>;
        $.ajax({
            type: "POST",
            url: "<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/cabsearch')) ?>",
            data: {'catgories': passedArray, 'bkgid': $bkgId, 'YII_CSRF_TOKEN': $('input[name="YII_CSRF_TOKEN"]').val()},
            success: function (data1)
            {
                $('#cabSearch').html(data1);
                $('a[data-menu="sidebar-right-over-cabsearch"]').click();
            },
            error: function (error)
            {
                console.log(error);
            }
        });
    }

    function viewMoreInfo(vmno) {
        $('a[data-menu="sidebar-right-over-view' + vmno + '"]').click();
    }

    function showFareBreakup(fbno) {
        $('a[data-menu="sidebar-right-over-view1' + fbno + '"]').click();
    }
</script>

