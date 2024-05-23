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
/* @var $model BookingTemp */
$quoteModel = $model->quotes;

// Car Master Details
$categories				 = [];
$classes				 = [];
$categoryServiceClasses	 = [];

$filterList			 = array_keys($quotes);
$svcVctResult		 = SvcClassVhcCat::mapAllCategoryServiceClass($categoryServiceClasses, $classes, $categories, $filterList);
$totIncludedClass	 = count($classes);

if ($totIncludedClass == 4)
{
	$categoryCol	 = "col-xs-12 col-sm-4 col-md-5";
	$classCol		 = "col-xs-12 col-sm-8 col-md-7";
	$classRatesCol	 = "col-sm-3";
}
if ($totIncludedClass == 3)
{
	$categoryCol	 = "col-xs-12 col-sm-5 col-md-6";
	$classCol		 = "col-xs-12 col-sm-7 col-md-6";
	$classRatesCol	 = "col-sm-4";
}
if ($totIncludedClass == 2)
{
	$categoryCol	 = "col-xs-12 col-sm-6 col-md-7";
	$classCol		 = "col-xs-12 col-sm-6 col-md-5";
	$classRatesCol	 = "col-sm-6";
}
if ($totIncludedClass == 1)
{
	$categoryCol	 = "col-xs-12 col-sm-8 col-md-9";
	$classCol		 = "col-xs-12 col-sm-4 col-md-3";
	$classRatesCol	 = "col-sm-12";
}



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
<?//= $form->hiddenField($model, 'bkg_vht_id'); ?>
<input type="hidden" id="step2" name="step" value="2">

<div class="panel">            
	<div class="panel-body">
		<div class="row">
			<?php
			$dboApplicable	 = Filter::dboApplicable($model);
			if ($dboApplicable)
			{
				?>	
				<div class="col-sm-4 text-right pr0 mb20">
				<div class="row">
						<div class="col-sm-12"><img src="/images/doubleback_fares2.jpg" alt="" width="350"></div>
					</div>
				</div>      
			<?php }
			?>	


			<div class="<?= $categoryCol ?> pt30 routeDescCol">
								<?php
								//This part renders the route headers
								if ($model->bkg_booking_type == 4)
								{
									$firstLocation	 = explode(',', $model->bookingRoutes[0]->brt_from_location);
									$secondLocation	 = explode(',', $model->bookingRoutes[0]->brt_to_location);
					?>
					<h3 class="mb0 mt0">
										<span data-toggle="tooltip" title="<?= $model->bookingRoutes[0]->brt_from_location ?>"><?= $firstLocation[0] ?> - </span>
										<span data-toggle="tooltip" title="<?= $model->bookingRoutes[0]->brt_to_location ?>"><?= $secondLocation[0] ?></span>
									</h3>
									<?php
								}
								else
								{
					?>
					<h2 class="mb0 mt0">
									<?php echo implode(" &rarr; ", $quoteModel->routeDistance->routeDesc);
						?>
					</h2>
										<?php
								}
								//This renders the estimated type details in the view
								if ($quotes)
								{
					?>
					<p>	Estimated Distance: <b> <?= $quoteModel->routeDistance->tripDistance . " Km" ?></b>, 
										Estimated Time: <b><?= BookingRoute::model()->populateTripduration($quoteModel->routes); ?></b>
									</p>
									<?php
								}
								else
								{
					?>									
					<br/><p><b>Sorry cab is not available for this route.</b></p>
								<?php }
								?>
							</div>
			<div class="<?= $classCol ?> classCol">
				<div class="row">
								<?php
								foreach ($classes as $class => $clsRank)
								{
									$classInfo		 = ServiceClass::model()->cache(5 * 24 * 60 * 60, new CacheDependency(CacheDependency::Type_CabTypes))->findByPk($class)
									?>
									<div class="<?= $classRatesCol ?>  eco-widget bg-navy-<?= $class ?> " style="min-height: 120px;">
										<p class="font18 text-center text-uppercase mb0"><img src="<?= $classInfo->scc_image; ?>" width="25"> <b><?= $classInfo->scc_label ?></b></p>
										<p class="text-center mb0" style="font-size: 11px; color: #242424; font-weight: bold; line-height: 16px;"><?= $classInfo->scc_tag ?></p>
										
										<p class="text-center" style="color: #242424; font-size: 11px; line-height: 12px;">
											(<?= $classInfo->scc_title; ?>)
											<?php
											$srvDesc = json_decode($classInfo->scc_desc);
											$serviceDesc	 = '';
											foreach ($srvDesc as $key => $value)
											{ 
												if($key != 0)
												{
													$serviceDesc .= ' + ';
												}
												$serviceDesc .=  $value; 
											}
											?>	
											<br>
											<a data-toggle="popover" id="b<?= $class ?>"  data-placement="top" data-html="true" data-content="<?= $serviceDesc ?>" style="font-size:24px;"><i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="Service Info" data-placement="botton"></i></a>
										</p>
									</div>	
									<?php
								}
								?>									

				</div>
							</div>

		</div>

			<?php
				foreach ($categories as $category => $rank)
				{
					$categoryInfo = VehicleCategory::model()->cache(5 * 24 * 60 * 60, new CacheDependency(CacheDependency::Type_CabTypes))->findByPk($category);
					?>

					<?php
					list($class, $scvId) = each($categoryServiceClasses[$category]);

					$quote = $quotes[$scvId];

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
				<div class=" row <?= $hide ?> rowCategory<?= $category ?> categoryCol">
					<div class="<?= $categoryCol ?> car-widteg-1 colCatDesc">
							<div class="row rowCatTitle"><div class="col-xs-12 text-center"><b><?= $categoryInfo->vct_desc ?></b></div></div>
							<div class="row flex  rowCatSubDesc">
								<div class="col-sm-6 col-md-5 colLeft">
									<div class="row">
										<div class="col-md-5 text-uppercase car-widget-3 pl0 pr0"><p><?= $categoryInfo->vct_label ?></p></div>
										<div class="col-md-7 pl0"><img src="<?= Yii::app()->baseUrl . '/' . $categoryInfo->vct_image ?>" alt="" class="img-responsive"></div>
										<div class="col-md-12 font11">
											*Note: Ext. Chrg. After <?= $quote->routeDistance->quotedDistance ?> Kms. as applicable.<br/>
											<?= $taxStr ?>
										</div>
									</div>
								</div>
								<div class="col-sm-6 col-md-5 car-widteg-2 colRight">
									<ul>
										<li><img src="/images/team.svg" width="23" alt="Seats + Driver"> <?= $categoryInfo->vct_capacity ?> Seats + Driver</li>
										<li><img src="/images/briefcase.svg" width="23" alt="Seats + Driver"> <?= $categoryInfo->vct_big_bag_capacity ?> Big bag(s) + <?= $categoryInfo->vct_small_bag_capacity ?> Small bag(s)</li>
										<li><img src="/images/air-conditioner.svg" width="23" alt="Seats + Driver"> AC</li>
										<li><img src="/images/speedometer.svg" width="23" alt="Seats + Driver"> KM in Quote <?= $quote->routeDistance->quotedDistance ?> Km</li>
									</ul>
								</div>
							</div>
						</div>
						<div class="<?= $classCol ?> colClassRates">
							<div class="row">
							<?php
							foreach ($classes as $class => $clsRank)
							{
								$arrTier['quotes']					 = $quotes;
								$arrTier['model']					 = $model;
								$arrTier['category']				 = $category;
								$arrTier['class']					 = $class;
								$arrTier['categoryServiceClasses']	 = $categoryServiceClasses;
								$arrTier['classRatesCol']			 = $classRatesCol;
								$arrTier['categoryInfo']			 = $categoryInfo;
								$this->renderPartial("bkQuoteTier", $arrTier, false, false);
							}
							?></div>
						</div>
					</div>
					<?php
					foreach ($arrServiceClass as $sccId => $arrServiceCls)
					{
						$relKey	 = $key . '|' . $sccId;
						$scvId	 = $arrSerVehRel[$relKey];
						?><div  id="packageQuotes<?= $scvId ?>" style="display:none"></div>
						<?
					}
					?>
					<?
				}
			?>


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
<?php
if ($model->bkg_booking_type == 4 && $model->bktyp == 1 && $stepOver == 1)
{
	?>
	        alert("The distance of travel is too small for a one-way trip, we're switching the trip type to Airport transfer (Local rental)");
	<?php
}
else if ($model->bkg_booking_type == 1 && $model->bktyp == 4 && $stepOver == 1)
{
	?>
	        alert("The distance of travel is too long for a local rental (Airport Transfer), we're switching the trip type to a One-way trip (Outstation rental)");
<?php } ?>
    });
    $('#bdate').html('<?= date('\O\N jS M Y \<\b\r/>\A\T h:i A', strtotime($model->bkg_pickup_date)) ?>');
    function validateForm1(obj)
    {
        var scvId = $(obj).attr("value");
        var scvClassArea = $('#service_class_area' + scvId).val();
        var pckid = $(obj).attr("pckid");
        if (pckid > 0)
        {
            $('#BookingTemp_bkg_package_id').val(pckid);
        }
        if (scvClassArea == '' || scvClassArea == 0)
        {
            $('.srvclassarea' + scvId).html('Please Choose One Vehicle Model');
            $('.srvclassarea' + scvId).removeClass('hide');
            return false;
				} else
        {
            $('.srvclassarea' + scvId).addClass('hide');
        }
        data.extraRate = "<?= CHtml::activeId($model, "bkg_rate_per_km_extra") ?>";
        data.vehicleTypeId = "<?= CHtml::activeId($model, "bkg_vehicle_type_id") ?>";
        data.flexiUrl = "<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/additionaldetail/flexi/2')) ?>";
        data.bkgTripDistance = "<?= CHtml::activeId($model, "bkg_trip_distance") ?>";
        data.bkgTripDuration = "<?= CHtml::activeId($model, "bkg_trip_duration") ?>";


        bookNow.data = data;
        bookNow.validateQuote(obj);
    }

    function serviceClassArea(obj, scvid)
    {
        if (this.value != '')
        {
            var srvClassModel = $(obj).attr("value");

            var baseAmount = $('#baseamount' + scvid).val();
            var discAmount = $('#discamount' + scvid).val();
            var firstLocation = <?= $model->bkg_from_city_id; ?>;
            $("#service_class_area" + scvid).empty();
            $href = "<?= Yii::app()->createUrl('booking/CalVehicleModelAmount') ?>";
            jQuery.ajax({type: 'GET',
                url: $href,
                dataType: 'html',
                data: {"city": firstLocation, "srvclassmodel": srvClassModel, "baseamount": baseAmount, "discamount": discAmount},
                success: function (data)
                {
                    obj = jQuery.parseJSON(data);
                    calculateFareBreakUp(obj.extraamount, obj.discount, scvid);
                    $('#basefarelbl' + scvid).html('Base Fare');
                    $('#extraamount' + scvid).html(' <i class="fa fa-inr"></i>' + obj.extraamount);
                    $('#discount' + scvid).html(' <i class="fa fa-inr"></i>' + obj.discount + '<sup>*</sup>');
                    $('.serviceClassAreaModel').val(srvClassModel);
                    $('#b' + scvid).html('<i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="Fare Breakup" data-placement="botton"></i>');
                }
            });
        }
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
				} else
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

				} else
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

    function hidePackageList(key)
    {
        $("#divCarList").removeAttr("style");
        $('#packageQuotes' + key).hide('slow');
        $('#btn-package-show' + key).removeClass('hide');
        $('#btn-package-hide' + key).addClass('hide');
    }


    function calculateFareBreakUp(baseFare, discountedBaseFare, scvid)
    {
        var statetax = $('#statetax' + scvid).val();
        var tolltax = $('#tolltax' + scvid).val();
        var driverallowance = $('#driverallowance' + scvid).val();
        var gstrate = $('#gstrate' + scvid).val();

        var toll = (tolltax == '' || tolltax == undefined) ? 0 : parseInt(tolltax);
        var state = (statetax == '' || statetax == undefined) ? 0 : parseInt(statetax);
        var da = (driverallowance == '' || driverallowance == undefined) ? 0 : parseInt(driverallowance);
        var gstRate = (gstrate == '' || gstrate == undefined) ? 0 : parseInt(gstrate);

        var discount = baseFare - discountedBaseFare;
        var grossBaseFare = (baseFare - discount) + toll + state + da;
        var GST = Math.round((grossBaseFare * parseFloat(gstRate) / 100));
        var totalAmt = grossBaseFare + GST;
        var html = "<ul class='list-unstyled'>" +
                "<li>Base Fare: <span class='float-right'><i class='fa fa-inr'></i>" + baseFare + "</span></li>" +
                "<li class='text-danger '>Discount<sup>*</sup>(Apply GETGOZO):" +
                "<span class='float-right'><i class='fa fa-inr'></i>" + discount + "</span></li>" +
                "<li>Driver Allowance:" +
                "<span class='float-right'><i class='fa fa-inr'></i>" + da + "</span></li>" +
                "<li>Toll Tax (Included):" +
                "<span class='float-right'><i class='fa fa-inr'></i>" + toll + "</span></li>" +
                "<li>State Tax (Included):" +
                "<span class='float-right'><i class='fa fa-inr'></i>" + state + "</span></li>" +
                "<li>GST: <span class='float-right'><i class='fa fa-inr'></i>" + GST + "</span></li>" +
                "<li>Total Payable: <span class='float-right'><i class='fa fa-inr'></i>" + totalAmt + "</span></li>" +
                "<li class='text-success '>Gozo Coins<sup>*</sup>(Apply GETGOZO):" +
                "<span class='float-right'><i class='fa fa-inr'></i>" + discount + "</span></li>" +
                "</ul>";
        $('#b' + scvid).attr('data-content', html);
    }

</script>
<script>
    $(".btnPackage").on("click", function ()
    {
        
				bookNow.togglePackage($(this));
    });

</script>