<?php
/* @var $model BookingTemp */
$quoteModel				 = $model->quotes;
// Car Master Details
$categories				 = [];
$classes				 = [];
$categoryServiceClasses	 = [];

$filterList			 = array_keys($quotes);
$svcVctResult		 = SvcClassVhcCat::mapAllCategoryServiceClass($categoryServiceClasses, $classes, $categories, $filterList);
$totIncludedClass	 = count($classes);

//if ($totIncludedClass == 4)
//{
$categoryCol	 = "col-md-4";
$classCol		 = "col-md-6";
$classRatesCol	 = "col-md-3";
//}
if ($totIncludedClass == 3)
{
	$categoryCol	 = "col-md-5";
	$classCol		 = "col-md-6";
	$classRatesCol	 = "col-md-4";
}
if ($totIncludedClass == 2)
{
	$categoryCol	 = "col-md-6";
	$classCol		 = "col-md-6";
	$classRatesCol	 = "col-md-6";
}
if ($totIncludedClass == 1)
{
	$categoryCol	 = "col-md-8";
	$classCol		 = "col-md-6";
	$classRatesCol	 = "col-md-12";
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
<input  placeholder="Select Model" class="serviceClassModel" id="bkg_vht_id"  name="BookingTemp[bkg_vht_id]" type="hidden" value="">
<input type="hidden" id="step2" name="step" value="2">
<input type="hidden" id="islogin" name="islogin" value="0">
<div class="row mb20">
    <div class="col-12">
        <div class="container">
			<div class="row">
				<div class="col-md-12 pt30 pr0 banner-load">
<!--					<img src="/images/3.png?v=0.1" alt="" class="img-fluid">-->
				</div>
			</div>
            <div class="row">
                <div class="col-md-12">
                    <div id="error-border">
                        <div class="row mt20">
                            <div class="col-md-6 pt30">
								<?php
//This part renders the route headers
								if ($model->bkg_booking_type == 4)
								{
									$firstLocation	 = explode(',', $model->bookingRoutes[0]->brt_from_location);
									$secondLocation	 = explode(',', $model->bookingRoutes[0]->brt_to_location);
									?>									<h3 class="mb0 mt0">
										<span data-toggle="tooltip" title="<?= $model->bookingRoutes[0]->brt_from_location ?>"><?= $firstLocation[0] ?> - </span>
										<span data-toggle="tooltip" title="<?= $model->bookingRoutes[0]->brt_to_location ?>"><?= $secondLocation[0] ?></span>
									</h3>
									<?php
								}
								else
								{
									?>	<h2 class="mb0 mt0">
									<?php echo implode(" &rarr; ", $quoteModel->routeDistance->routeDesc);
									?>	</h2>
									<?php
								}
								//This renders the estimated type details in the view
								if ($quotes)
								{
									?>		
									<p>Estimated Distance: <b> <?= $quoteModel->routeDistance->tripDistance . " Km" ?></b>, 
										Estimated Time: <b><?= BookingRoute::model()->populateTripduration($quoteModel->routes); ?></b>
									</p>
									<?php
								}
								else
								{
									?>				
	<!--									<br/><p><b>Sorry cab is not available for this route.</b></p>-->
								<?php }
								?>
                            </div>
                            <div class="col-md-6">
                                <div class="row flex">
									<?php
									foreach ($classes as $class => $clsRank)
									{
										if ($class == 5)
										{
											continue;
										}
										$classInfo	 = ServiceClass::model()->cache(5 * 24 * 60 * 60, new CacheDependency(CacheDependency::Type_ServiceClass))->findByPk($class);
										$srvDesc	 = json_decode($classInfo->scc_desc);
										$serviceDesc = '';
										foreach ($srvDesc as $key => $value)
										{
											if ($key != 0)
											{
												$serviceDesc .= ' + ';
											}
											$serviceDesc .= $value;
										}
										?>
										<div class="<?= $classRatesCol ?>  eco-widget color-white bg-navy-<?= $class ?>">
											<p class="font-22 text-center text-uppercase mb0"><b><?= $classInfo->scc_label ?></b><sup> <a href="#"><i type="button" class="serviceinfo fas fa-info-circle font-12 color-black"  id="b<?= $class ?>" data-toggle="tooltip" data-placement="top" data-html="true"  title="<?= $serviceDesc ?>"></i></a></sup></p>
											<p class="text-center mb0 value-widget font-12"><?php
												$sccLabel = explode(",", $classInfo->scc_tag);
												echo $sccLabel[0]
												?><br><?= $sccLabel[1]; ?> </p>

											<p class="text-center value-widget2 font-12">
	<!--												(<? //= $classInfo->scc_title;            ?>)-->
												<?php ?>	
												<br>

											</p>
										</div>
										<?php
									}
									?>	

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div>
				<?php
				// print_r($model->bkgSvcClassVhcCat->scv_vct_id);print_r($categories);
				foreach ($categories as $category => $rank)
				{ //echo "2343dhere..==>". $category."<BR>";
					$categoryInfo	 = VehicleCategory::model()->cache(5 * 24 * 60 * 60, new CacheDependency(CacheDependency::Type_CabTypes))->findByPk($category);
					?>

					<?php
					list($class, $scvId) = each($categoryServiceClasses[$category]);
					$luggageCapacity = Stub\common\LuggageCapacity::init($category, $class, 1);
					$quote			 = $quotes[$scvId];

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
						<div class="col-lg-6 table-list">
							<div class="row rowCatTitle mb10"><div class="col-12 text-center"><b><?= $categoryInfo->vct_desc ?></b></div></div>

							<div class="row flex">
								<div class="col-md-6 col-lg-5 colLeft">
									<div class="row">
										<div class="col-lg-5 text-uppercase car-widget-3 pl0 pr0"><p><?= $categoryInfo->vct_label ?></p></div>
										<div class="col-lg-7 pl0"><img src="<?= Yii::app()->baseUrl . '/' . $categoryInfo->vct_image ?>" width="155" height="76" alt="" class="img-responsive"></div>

									</div>
								</div>
								<div class="col-lg-5 car-widteg-2 ul-style-b font-12">
									<ul>
										<li><img src="/images/team.svg" width="23" alt="Seats + Driver" class="mr5"> <?= $categoryInfo->vct_capacity ?> Seats + Driver</li>
										<li><img src="/images/briefcase.svg" width="23" alt="Seats + Driver" class="mr5">
											<?= (($luggageCapacity->largeBag != 0) ? $luggageCapacity->largeBag . ' Large bag(s) or' : '') ?>
											<?= (($luggageCapacity->smallBag != 0) ? $luggageCapacity->smallBag . ' Small bag(s) ' : '') ?>
											<!-- <? //=$luggageCapacity->largeBag   ?><? //= $categoryInfo->vct_big_bag_capacity    ?> Big bag(s) / <? //=$luggageCapacity->smallBag   ?><? //= $categoryInfo->vct_small_bag_capacity    ?> Small bag(s)-->
										</li>
										<li><img src="/images/air-conditioner.svg" width="23" alt="Seats + Driver" class="mr5"> AC</li>
										<li><img src="/images/speedometer.svg" width="23" alt="Seats + Driver" class="mr5"> KM in Quote <?= $quote->routeDistance->quotedDistance ?> Km</li>
									<?php 
												$showcngcap = false;
												foreach ($classes as $class => $clsRank) {
												  $sccId = $class;
												  $relKey = $class . '|' . $sccId;
												  $classInfo = ServiceClass::model()->cache(5 * 24 * 60 * 60, new CacheDependency(CacheDependency::Type_CabTypes))->findByPk($class);
													if($classInfo['scc_is_cng']==1){
														$scvId = $categoryServiceClasses[$category][$sccId];
														/* @var $quote Quote */
														$quote = false;
														if (array_key_exists($scvId, $quotes)) {
															$quote = $quotes[$scvId];
														} else {
															continue;
														}
														if ($quote->success) {
														  $showcngcap =true;
														  break;
														}	
													}
												  
												}											
											if($showcngcap){
										?>
											<li class="small color-gray-dark">*capacity shown is for CNG cab</li>
											<?php }?>
</ul>
								</div>

								<!--								<div class="col-lg-12 font-11 color-gray mt10">
																	*Note: Ext. Chrg. After <?= $quote->routeDistance->quotedDistance ?> Kms. as applicable.<br/>
								<?= $taxStr ?>
																</div>-->

							</div>
						</div>
						<div class="<?= $classCol ?> colClassRates mb15">
							<div class="row">
								<?php
								foreach ($classes as $class => $clsRank)
								{
									if ($class == 5)
									{
										continue;
									}
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
	</div>
</div>
<?php $this->endWidget(); ?>
<!--modal for win a day end-->
<div id="indexPackageDetails" class="modal fade bd-example-modal-lg" role="dialog">
	<div class="modal-dialog modal-dialog-centered modal-lg">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header pb5 pt5">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body mb10 user-review pt0 blue-color" id="indexPackageDetailsBody">

			</div>
		</div>
	</div>
</div>
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


        var images = ['b3.jpg', 'b4.jpg', 'b5.jpg', 'b6.jpg', 'b7.jpg', 'b8.jpg', 'b9.jpg'];
        $('<img class="img-fluid" src="images/' + images[Math.floor(Math.random() * images.length)] + '?v=0.2">').appendTo('.banner-load');
    });
    $('#bdate').html('<?= date('\O\N jS M Y \ , \A\T h:i A', strtotime($model->bkg_pickup_date)) ?>');
    function validateForm1(obj, sccId)
    {
        var isLogin = $(obj).hasClass("login");
        if (isLogin == true)
        {
            $('#islogin').val(1);
            var scvId = $(obj).data("value");
        } else
        {
            $('#islogin').val(0);
            var scvId = $(obj).attr("value");
        }
        if (sccId == 4)
        {
            var scvClassArea = $('#service_class_area' + scvId).val();

            if (scvClassArea == '' || scvClassArea == 0)
            {
                $('.srvclassarea' + scvId).html('Please Choose One Vehicle Model');
                $('.srvclassarea' + scvId).removeClass('hide');
                return false;
            } else
            {
                $('.srvclassarea' + scvId).addClass('hide');
            }
        }

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

    function serviceClassArea(obj, scvid)
    {
        if (obj.value != '')
        {
            //       debugger;
            $('#basefarelbl' + scvid).html('Base Fare');
            var scvModel = $('option:selected', obj).attr('scvModel');
            var baseFare = $('option:selected', obj).attr('baseAmount');
            var discount = $('option:selected', obj).attr('discAmount');
            var discBaseFare = baseFare - discount;
            if (discBaseFare < baseFare)
            {
                $(".clsBaseFare").show();
            } else
            {
                $(".clsBaseFare").hide();
            }
            $(".clsBaseFare .clsOriginalFare" + scvid).html(baseFare);
            $('#extraamount' + scvid).html(' &#x20B9;' + baseFare);
            calculateFareBreakUp(baseFare, discBaseFare, scvid);
            $('#discount' + scvid).html(' &#x20B9;' + discBaseFare);
            $('.serviceClassAreaModel').val(obj.value);
            $('.serviceClassModel').val(scvModel);
            $('.btnCabType' + scvid).val(obj.value);
            
            $('.srvclassarea' + scvid).addClass('hide');
            //	$('#b' + scvid).html('<i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="Fare Breakup" data-placement="botton"></i>');

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
                $('#indexPackageDetails').removeClass('fade');
                $('#indexPackageDetails').css('display', 'block');
                $('#indexPackageDetailsBody').html(data);
                $('#indexPackageDetails').modal('show');
            }
        });
    }
    function showPackageList(key)
    {
        var self = this;
        $.ajax({
            type: "POST",
            url: "<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/packageQuote')) ?>",
            data: {'bkgid': $bkgId, 'cab': key, 'YII_CSRF_TOKEN': $('input[name="YII_CSRF_TOKEN"]').val()},
            success: function (data1)
            {
//				$(".packageqt").attr("id","packageQuotes" + key);
//                $("#divCarList").css("border-bottom", "#e2e2e2 1px solid");
//                $('#packageQuotes' + key).html(data1);
//                $('#packageQuotes' + key).show('slow');
//                $('#btn-package-hide' + key).removeClass('hide');
//                $('#btn-package-show' + key).addClass('hide');
                $("#divCarList").css("border-bottom", "#e2e2e2 1px solid");
                $('#packageQuotes' + key).html(data1);
                $('#packageQuotes' + key).show('slow');
                $('#btn-package-hide' + key).removeClass('hide');
                $('#btn-package-show' + key).addClass('hide');
            },
            error: function (error)
            {
                console.log(error);
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
        var GST = Math.round((discountedBaseFare * parseFloat(gstRate) / 100));
        var totalAmt = grossBaseFare + GST;
        var html = "<ul class='list-unstyled'>" +
                "<li>Base Fare: <span class='float-right'>&#x20B9;" + baseFare + "</span></li>" +
                "<li class='text-danger '>Discount<sup>*</sup>(Apply GETGOZO):" +
                "<span class='float-right'>&#x20B9;" + discount + "</span></li>" +
                "<li>Driver Allowance:" +
                "<span class='float-right'>&#x20B9;" + da + "</span></li>" +
                "<li>Toll Tax (not payable by customer):" +
                "<span class='float-right'>&#x20B9;" + toll + "</span></li>" +
                "<li>State Tax (not payable by customer):" +
                "<span class='float-right'>&#x20B9;" + state + "</span></li>" +
                "<li>GST: <span class='float-right'>&#x20B9;" + GST + "</span></li>" +
                "<li>Total Payable: <span class='float-right'><i class='fa fa-inr'></i>" + totalAmt + "</span></li>" +
                "<li class='text-success '>Gozo Coins<sup>*</sup>(Apply GETGOZO):" +
                "<span class='float-right'>&#x20B9;" + discount + "</span></li>" +
                "</ul>";
        $('#b' + scvid).attr('data-content', html);
    }

//    $('.fair-breakup-modal').click(function (event) {
//        var id = $(event.currentTarget).data('target');
//        $('#bkCommonModelHeader').text('Fair Breakup Details');
//        $('#bkCommonModelBody').html($(id).html());
//        $('#bkCommonModelHeader').parent().removeClass('hide');
//        $('#bkCommonModel').modal('show');
//    });
//
//    $('.in-ex').click(function (event) {
//        var id = $(event.currentTarget).data('target');
//        $('#bkCommonModelHeader').text('Inclusions & Exclusions Details');
//        $('#bkCommonModelBody').html($(id).html());
//        $('#bkCommonModelHeader').parent().removeClass('hide');
//        $('#bkCommonModel').modal('show');
//    });



    $(".btnPackage").on("click", function ()
    {
        bookNow.togglePackage($(this));
    });
    /**************BOOTSTARP4 TOOLTIP START**************************/
    $(function ()
    {
        $('[data-toggle="tooltip"]').tooltip({
            template: '<div class="tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner large"></div></div>'
        })
    });
    $(".serviceinfo, .farebreakup")
            .on("mouseout", function ()
            {
                $(".tooltip").remove();
            });
    $('a[data-toggle="popover"]').on('click', function (event)
    {
        if ($('body').find('.popover').length > 0)
        {
            $(".popover").remove();
        }
    });
    /**************BOOTSTARP4 TOOLTIP END**************************/

</script>
