<style>
    .hide{
		display: none;
	}
    .link-list-1 a{
        width: 100px !important;
    }


</style>
<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/fontawesome-web/css/all.min.css?v0.6');
?>
<?php
#$quotes		 = $model->getQuote(null, true);
/* @var $model BookingTemp */
$quoteModel = $model->quotes;
$serviceClass = [];
$cabData = SvcClassVhcCat::getVctSvcList("allDetail");
$categories = [];
$classes = [];
$categoryServiceClasses = [];

$filterList = array_keys($quotes);
$svcVctResult = SvcClassVhcCat::mapAllCategoryServiceClass($categoryServiceClasses, $classes, $categories, $filterList);
$totIncludedClass = count($classes);
$totCategory = count($categories);

if ($totIncludedClass == 4)
{
    $classCol = "col-sm-4";
    $categoryCol = "col-sm-2";
}
if ($totIncludedClass == 3)
{
    $classCol = "col-sm-4";
    $categoryCol = "col-sm-2";
}
if ($totIncludedClass == 2)
{
    $classCol = "col-sm-6";
    $categoryCol = "col-sm-3";
}
if ($totIncludedClass == 1)
{
    $classCol = "col-sm-12";
    $categoryCol = "col-sm-6";
}

// Special Remarks
$rtInfoArr = $model->getRoutesInfobyId();
$specialRemarks = $rtInfoArr[0]['rut_special_remarks'];
$isFlexxiExcluded = false;
$excludeCabType = BookingSub::getexcludedCabTypes($model->bkg_from_city_id, $model->bkg_to_city_id);
if (in_array(11, $excludeCabType))
{
    $isFlexxiExcluded = true;
}

/** @var CActiveForm $form */
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'cabrate-form13',
    'enableClientValidation' => true,
    'clientOptions' => array(),
    'enableAjaxValidation' => false,
    'errorMessageCssClass' => 'help-block',
    'htmlOptions' => array(
        'class' => 'form-horizontal',
        'autocomplete' => 'off'
    ),
        ));
?>

<?= $form->hiddenField($model, 'bkg_id', ['id' => 'bkg_id3', 'class' => 'clsBkgID']); ?>
<?= $form->hiddenField($model, 'hash', ['id' => 'hash3', 'class' => 'clsHash', 'value' => $model->getHash()]); ?>
<?= $form->hiddenField($model, 'bkg_from_city_id'); ?>
<?php
if ($quotes)
{
    ?>

    <div class="container top-10" id="p_clist">
		<div class="content-boxed-widget mb5 list-view-panel p0">
			<div>
				<div>
					<a href="<?php echo Yii::app()->createUrl("/terms/doubleback");?>" target="_blank"><img src="/images/doubleback_fares_banner.png" alt="" width="100%" class="img-responsive"></a>
				</div>
			</div>
		</div>
        <?php $this->renderPartial("bkRouteHeader" . $this->layoutSufix, ['prevStep' => 1, 'model' => $model, 'quoteModel' => $quoteModel]); ?>   
		<?php
		if (strlen(trim($specialRemarks)) > 0)
		{
			?>
            <div class="content-boxed-widget2 mb5 list-view-panel p5 pl15">
			<a href="#" data-menu="extra-charge-routes" class="header-icon header-icon-2">Includes extra charges for some routes <i class="fas fa-question-circle" style="-webkit-transform:rotate(0deg);transform:rotate(0deg);"></i></a>
<!--        <a href="javascript:void(0);" class="font13 color-red-dark" data-accordion="accordion-32">Includes extra charges for some routes <i class="fas fa-question-circle" style="-webkit-transform:rotate(0deg);transform:rotate(0deg);"></i></a>
            <div class="accordion-content" id="accordion-32" style="display: none;">
                    <div class="accordion-texts">
                        <div class="content p0 bottom-0 pl0 ul-panel2">
                            <?php //echo trim($specialRemarks); ?>
                        </div>
                    </div>
                </div>-->
				<div id="extra-charge-routes" data-selected="menu-components" data-width="300" data-height="300" class="menu-box menu-modal">
					<div class="menu-title">
						<h2>Special Notes</h2>
						<a href="#" class="menu-hide pt0 line-height42"><i class="fa fa-times"></i></a>					
					</div>         
					<div class="p10"><?php echo trim($specialRemarks); ?></div>    
				</div>
            </div>
			
        <?php } ?>
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

                    $promoDiscount = $quotePackage->routeRates->discount;
                    $discBaseAmount[] = $quotePackage->routeRates->baseAmount - $promoDiscount;
                    $quotePackagesSorted[$quotePackage->routeRates->baseAmount][$packid][$key] = $quotePackage;
                }
            }
            ksort($quotePackagesSorted);
            ?>
            <div class="content-boxed-widget btn-orange font-13 wrapword line-height16" id="pckShowBtn" style="display: "><?php //= $noPackages                                                               ?>Packages from <?= $quoteModel->routeDistance->routeDesc[0] ?> to <?= $quoteModel->routeDistance->routeDesc[1] ?> starts from <span>₹</span> <b><?= min($discBaseAmount) ?></b>. <br>Check Now</br></div>
            <?php
        }
        ?>

		<div>
        <?php
        $j = 0;
        $k = 0;
			foreach ($categories as $category => $rank)
			{
            $categoryInfo = VehicleCategory::model()->cache(5 * 24 * 60 * 60, new CacheDependency(CacheDependency::Type_CabTypes))->findByPk($category);
            $quote = $quotes[0];
			$luggageCapacity = Stub\common\LuggageCapacity::init($category, 1,1);
            $j++;
            $shareBooking = false;
				if ($model->bkg_booking_type == 1 && !$isFlexxiExcluded && $key == VehicleCategory::SEDAN_ECONOMIC)
				{
                $shareBooking = true;
            }
            $flexxRates = $quote->flexxiRates;
            /* @var $quote Quote */
            $cab = $cabData[$category];

            // Fare Breakup Tooltip

            $tolltax_value = $quote->routeRates->tollTaxAmount | 0;
            $tolltax_flag = $quote->routeRates->isTollIncluded | 0;
            $statetax_value = $quote->routeRates->stateTax | 0;
            $statetax_flag = $quote->routeRates->isStateTaxIncluded | 0;

				if (($tolltax_flag == 1 && $tolltax_value == 0) && ($statetax_flag == 1 && $statetax_value == 0))
				{
                $taxStr = 'Toll Tax and State Tax included';
				}
				else if ($tolltax_flag == 0 && $statetax_flag == 0)
				{
                $taxStr = 'Toll and State taxes extra as applicable';
            }
            // print'<pre>';print_r($categoryInfo);
            ?>

            <div class="content-padding content-boxed-widget mb0 pb0 p0 list-view-panel clsCategory<?php echo $category;?>" style="overflow: hidden; position: relative;">
                <div class="content-padding p15 pb0 pt10 border-top" onclick="showCabDetails('<?php echo $category;?>');">
                    <div class="one-third mt10 mr0 p5 pl0 bottom-10">
                        <img src="<?= Yii::app()->baseUrl . '/' . $categoryInfo['vct_image'] ?>" alt="" width="150" class="preload-image responsive-image mb0">
                    </div>
                    <div class="one-sixth mt5">
                    <div class="font-14 uppercase line-height18 color-blue"><b><?= $categoryInfo['vct_label'] ?></b></div>
                    <div class="font-12 line-height14 mb5 color-gray-dark"><?= $categoryInfo['vct_desc'] ?></div>
                    <div class="font-12 line-height14 mb5 color-gray-dark"><?= $categoryInfo['vct_capacity'] ?> Seats | AC</div>
<!--                    <div class="font-13 line-height16 color-gray-dark mb10">
                        <?//= $categoryInfo['vct_capacity'] ?> Seats | AC
                    </div>-->
				    </div>

                     <div class="clear"></div>
                </div>
                <div class="widget-options" onclick="showCabDetails('<?php echo $category;?>');">View Options</div>
                <div>
                    <ul class="ml20 bottom-10 font-12">
                        <li class="bottom-0 line-height18"><?= $quoteModel->routeDistance->tripDistance ?> KM in Quote</li>
                        <li class="bottom-0 line-height18">
						<?=(($luggageCapacity->largeBag !=0)?$luggageCapacity->largeBag. ' Big bags or':'') ?>
						<?=(($luggageCapacity->smallBag !=0)?$luggageCapacity->smallBag. ' Small bags ':'') ?>
						<!--<?//= $luggageCapacity->largeBag ?><?//= $categoryInfo['vct_big_bag_capacity'] ?> Big / <?//=$luggageCapacity->smallBag?><?//= $categoryInfo['vct_small_bag_capacity'] ?> Small bags-->
						</li>
                    </ul>
						<?php
												$showcngcap = false;
						foreach ($classes as $class => $clsRank)
						{
												  $sccId = $class;
												  $relKey = $class . '|' . $sccId;
												  $classInfo = ServiceClass::model()->cache(5 * 24 * 60 * 60, new CacheDependency(CacheDependency::Type_CabTypes))->findByPk($class);
							if ($classInfo['scc_is_cng'] == 1)
							{
														$scvId = $categoryServiceClasses[$category][$sccId];
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
								if ($quote->success)
								{
														  $showcngcap =true;
														  break;
														}	
													}
												}													
						if ($showcngcap)
						{
						?>
							<div class="font-9 line-height14 ml20 color-gray-dark">*capacity shown is for CNG cab</div>
						<?php }?>
					
                </div>
                
                <div class="">
                        <div class="content p0 bottom-0 text-center content-widget-accordion">
                    <?php
                    $cabPriceArray = array();
							foreach ($classes as $class => $clsRank)
							{
                        $sccId = $class;
                        $relKey = $class . '|' . $sccId;
                        $scvId = $categoryServiceClasses[$category][$sccId];

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
								if ($quote->success)
								{
                            //$details = $this->renderPartial("bkFareBreakup", ['quote' => $quote, 'scvid' => $scvId], true);

                            $promoDiscount = $quote->routeRates->discount;
                            $discBaseAmount = $quote->routeRates->baseAmount - $promoDiscount;
							array_push($cabPriceArray,$discBaseAmount);
                            //$datamenu = ($sccId == 4) ? 'data-menu="sidebar-right-over-booknow"' : ""
								}
								else
								{
                            ?>
                        <a href="#" class="bg-blue<?= $sccId ?>">
                            <img src="/images/no_cabs_available.png" alt="No Cabs Available" class="responsive-image">
                        </a>
                        <?php
                    }
                    
								$k++;
                }
                ?>
            </div>
                    </div>
					<?
					$hide			 = ($quote->gozoNow == 1) ? 'hide' : '';
					?>
					<div class="widget-price line-height14 <?= $hide ?> "><span class="bolder">&#x20B9;<?php echo min($cabPriceArray); ?></span><br><span class="font-12 color-gray border-none">Onwards</span></div>

        </div>

        <div class="content-padding content-boxed-widget mb10 pb0 p0 pt0" style="overflow: hidden;">
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
						<div class="headding-part1 text-center"><?= $bestPriceRange ?></div>
                <?php
            }
            ?>
        </div>

        <div class="clear"></div>
        <?php
    }
    ?>
    </div>
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
    $(document).ready(function ()
    {
<?php
if ($model->bkg_booking_type == 4 && $model->bktyp == 1 && $stepOver == 1)
{
    ?>
            setTimeout(function () {
                bookNow.showInfoMsg("The distance of travel is too small for a one-way trip, we're switching the trip type to Airport transfer (Local rental)");
            }, 1000);
    <?php
}
else if ($model->bkg_booking_type == 1 && $model->bktyp == 4 && $stepOver == 1)
{
    ?>
            setTimeout(function () {
                bookNow.showInfoMsg("The distance of travel is too long for a local rental (Airport Transfer), we're switching the trip type to a One-way trip (Outstation rental)");
            }, 1000);
<?php } ?>
        bookNow.bkQuoteReady($bkgId, $hash);
        //hyperModel.initializeplAirport();
    });


    $('#bdate').html('<?= date('\O\N jS M Y \<\b\r/>\A\T h:i A', strtotime($model->bkg_pickup_date)) ?>');
   
    $(document).on('click', '.menu-hide', function () {
        $("#menu-hider").trigger("click");
    });
    $(function () {
        $(".preload-search-image").lazyload({threshold: 0});
    });


	function showCabDetails(catId){
		$.ajax({
            type: 'POST',
            url: $baseUrl + "/booking/details",
            data: {'catid': catId, 'bkg_id': $bkgId, 'YII_CSRF_TOKEN': $('input[name="YII_CSRF_TOKEN"]').val()},
			//data:data,
            beforeSend: function ()
            {
                ajaxindicatorstart("");
            },
            complete: function ()
            {
                ajaxindicatorstop();

            },
            success: function (data)
            {	//debugger;
                $("#menuDetails").html(data);
                if (bookNow.isMobile() || screen.width < 900)
                {
                    $("html,body").animate({scrollTop: 0}, "slow");
                    $("#menuQuote").hide();
					$("#menuDetails").show();
                } else
                {
                    $("#menuQuote").removeClass("active");
                    $("#menuQuote").addClass("fade");
                }
               // trackPage("/booking/details");
            },
            error: function (data)
            {
                alert("Error occured.please try again");
                alert(data);
            },
            dataType: 'html'
        });
	}
</script>
