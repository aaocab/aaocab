<style type="text/css">
    .trip_plan table { 
        width: 100%; 
        border-collapse: collapse;
        font-size: 13px;
    }
    /* Zebra striping */
    .trip_plan tr:nth-of-type(odd) { 
        background: #f1f1f1; 
    }
    .trip_plan th { 
        background: #333; 
        color: white; 
        font-weight: bold; 
    }
    .trip_plan td { 
        padding: 6px; 
        border: 1px solid #ccc; 
        text-align: left; 
    }
    .trip_plan th { 
        padding: 6px; 
        border: 1px solid #ccc; 
        text-align: left; 
    }
    .border_box2{ background: #333333!important; color: #fff!important;}
    .border_box1{ background: #f1f1f1; color: #0858a0!important; border-bottom: #fff 1px solid;}
    .green-bg2{ background: #5cb85c; color: #fff;}
    @media (max-width: 767px)
    {

        /* Force table to not be like tables anymore */
        .trip_plan table, thead, tbody, th, td, tr { 
            display: block; 
        }

        /* Hide table headers (but not display: none;, for accessibility) */
        .trip_plan thead tr { 
            position: absolute;
            top: -9999px;
            left: -9999px;
        }

        .trip_plan tr{ border: 1px solid #ccc; }

        .trip_plan td{ 
            /* Behave  like a "row" */
            border: none;
            border-bottom: 1px solid #d5d5d5; 
            position: relative;
            padding-left: 50%; 
        }

        .trip_plan td:before { 
            /* Now like a table header */
            position: absolute;
            /* Top/left values mimic padding */
            top: 6px;
            left: 6px;
            width: 45%; 
            padding-right: 10px; 
            white-space: nowrap;
        }

        /*
        Label the data
        */
        .trip_plan td:nth-of-type(1):before { content: "From"; }
        .trip_plan td:nth-of-type(2):before { content: "To"; }
        .trip_plan td:nth-of-type(3):before { content: "Departure Date"; }
        .trip_plan td:nth-of-type(4):before { content: "Time"; }
        .trip_plan td:nth-of-type(5):before { content: "Distance"; }
        .trip_plan td:nth-of-type(6):before { content: "Duration"; }
        .trip_plan td:nth-of-type(7):before { content: "Days"; }

    }

    /* Smartphones (portrait and landscape) ----------- */

	.proceed-make-btn{
		display: none;
	}

</style>
<script>
    var huiObj = null;
    var prmObj = null;
    var pageInitialized = false;
    $(document).ready(function ()
    {
        if (pageInitialized)
            return;

        pageInitialized = true;
        huiObj = new HandleUI();
        huiObj.bkgId = '<?= $model->bkg_id ?>';
        prmObj = new Promotion(huiObj);
<?php
if ($isredirct != 1 && $model->bkg_flexxi_type != 2 && $promoId > 0)
{
	/* @var $pModel Promos */
	$pModel = Promos::model()->findByPk($promoId);
	?>
	        ajaxindicatorstart("");
	        prmObj.applyPromo(1, '<?= $pModel->prm_code; ?>'); // Bydefault Promo NOV19 will be applied.	
<?php } ?>		//
        bid = '<?= $model->bkg_id ?>';
        hsh = '<?= $model->hash ?>';
        $isRunningAjax = false;
        var promo = new Promo();
        //promo.payNowLater(bid,hsh); 
<?php
if ($isredirct != 1 && $model->bkg_flexxi_type != 2)
{
	?>
	        ajaxindicatorstop();
<?php } ?>
    });
</script>

<?php
/* @var $model Booking */
$detect = Yii::app()->mobileDetect;

// call methods
$isMobile			 = $detect->isMobile() && $detect->is("AndroidOS");
$hide				 = (($model->bkgInvoice->bkg_promo1_id != 0 && $model->bkgInvoice->bkg_discount_amount > 0) || $model->bkgInvoice->bkg_credits_used > 0) ? '' : 'hide';
$isPromoApplicable	 = BookingSub::model()->getApplicable($model->bkg_from_city_id, $model->bkg_to_city_id, 1);
if ($model->bkg_booking_type == 1 && $model->bkg_flexxi_type == 2)
{
	$isPromoApplicable = false;
}


$hidepromo		 = ($model->bkgInvoice->bkg_promo1_id != 0 || $model->bkgInvoice->bkg_discount_amount > 0) ? '' : 'hide';
$hide1			 = ($model->bkgInvoice->bkg_promo1_id != 0 || $model->bkgInvoice->bkg_discount_amount > 0) ? 'hide' : '';
$enableCOD		 = $model->enableCOD();
$minPayPercent	 = Config::getMinAdvancePercent($model->bkg_agent_id, $model->bkg_booking_type, $model->bkgSvcClassVhcCat->scc_ServiceClass->scc_id,$model->bkgPref->bkg_is_gozonow);
$row			 = 'row';
$version		 = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/promo.js?v=' . $version);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/promotion.js?v=' . $version);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/handleUI.js?v=' . $version);
$walletBalance	 = UserWallet::model()->getBalance(UserInfo::getUserId());
$scvVctId		 = SvcClassVhcCat::model()->getCatIdBySvcid($model->bkg_vehicle_type_id);
?>

<?php
if ($isredirct)
{
	if ($model->bkg_agent_id != '')
	{
		$row = '';
	}
	$routeCityList	 = $model->getTripCitiesListbyId();
	$ct				 = implode(' &#10147; ', $routeCityList);
	?>
	<div class="<?= $row ?>">
		<div class="col-xs-12">
			<?php
			if ($model->bkg_agent_id != '')
			{
				/* @var $agentModel Agents */
				$agentModel		 = Agents::model()->findByPk($model->bkg_agent_id);
				$acceptPayment	 = $agentModel->agt_use_gateway;
				?>
				<div class="row">
					<div class="col-xs-6 text-left"><h1><?= $agentModel->agt_company ?></h1></div>
					<div class="col-xs-6 text-right mt5"><img src="<?= Yii::app()->baseUrl ?>/images/logo4.png"/></div>
				</div>
				<h4 class=" text-center mb20"><?= $this->pageTitle ?></h4>
			<?php } ?>
			<?php
			if ($model->bkgInvoice->bkg_advance_amount < 1 && ($model->bkg_agent_id != NULL || $model->bkg_agent_id != ''))
			{
				?>
				<div class=" text-center mb20 alert alert-danger">RECONFIRMATION & PAYMENT NEEDED: Make payment of at least <?= $minPayPercent ?>% below to reconfirm booking. Booking will be auto-cancelled if not reconfirmed by <?= date('jS M Y (l)', strtotime($model->bkg_pickup_date)); ?> , <?= date('h:i A', strtotime($model->bkg_pickup_date)); ?></div>
				<div class=" text-center mb20">
					<?= CHtml::radioButtonList('bkg_reconfirm_flag', 1, array(1 => 'Yes, confirm this booking as soon as payment is received', 3 => 'No, I didnt request this booking. Please cancel it'), array('id' => "reconfirm", 'separator' => "    ", 'onclick' => "reconfirmUpdate()")); ?>
				</div>
				<?php
			}
			else if ($model->bkgInvoice->bkg_advance_amount < 1 && $model->bkgTrail->bkg_platform == 2)
			{
				?>
				<div class=" text-center mb20 alert alert-danger">RECONFIRMATION & PAYMENT NEEDED: Make payment of at least <?= $minPayPercent ?>% below to reconfirm booking. Booking will be auto-cancelled if not reconfirmed </div>
				<div class=" text-center mb20">
					<?= CHtml::radioButtonList('bkg_reconfirm_flag', 1, array(1 => 'Yes, confirm this booking as soon as payment is received', 3 => 'No, I didnt request this booking.Please cancel it'), array('id' => "reconfirm", 'separator' => "    ", 'onclick' => "reconfirmUpdate()")); ?>
				</div>
			<?php } ?>

			<?php
			$platform	 = Yii::app()->request->getParam('platform');
			$src		 = Yii::app()->request->getParam('src', 2);
			if ($src != 1 && $platform != 3)
			{
				$statusStr = '';
				if ($model->bkg_status > 5 && $model->bkg_status < 15)
				{
					$statusStr = '(' . Booking::model()->getActiveBookingStatus($model->bkg_status) . ')';
				}
				?>
				<div class="" id="bookingDetPayNow">

					<?php
					if ($paymentdone)
					{
						if ($succ == 'success')
						{
							?>
							<div role="alert" class="alert alert-success">
								<strong>Transaction was successful. Thank you for your order. Your Transaction Id : <?= $transid ?></strong>
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
					?>
				</div>
	<?php } ?>
		</div>
	</div>

<?php } ?>

<div class="<?= $row ?> detailsWidjet">
	<div class="col-xs-12">
		<!----Start new design------->
		<div class="row">
			<div class="col-xs-12 col-sm-7">
				<div class="row">

					<?php $this->renderPartial("bkSummaryTravellerInfo", ["model" => $model], false, false); ?>


					<?php
					if ((in_array($model->bkg_status, [5, 6])) && $showUserInfoPickup && $model->bkgBcb->bcb_driver_phone != '')
					{
						$driver_phone	  = BookingPref::getDriverNumber($model, $model->bkgBcb->bcb_driver_phone);	
						?>
						<div class="col-xs-12 mb20">
							<div class="heading-part mb10">Trip Information</div>
							<div class="main_time border-blueline  ">
								<div class="row">
									<div class="col-xs-12 col-sm-6">
										Driver Name: <span class="black-color"><?= $model->bkgBcb->bcb_driver_name ?></span><br>

										Driver Phone: <span class="black-color"><?= $driver_phone; ?><?//= Yii::app()->params['customerToDriver'] ?></span><br>
									</div>
									<div class="col-xs-12 col-sm-6">
										Cab Number: <span class="black-color"><?= $model->bkgBcb->bcb_cab_number; ?></span><br>
										Cab Model: <span class="black-color"><?= $model->bkgBcb->bcbCab->vhcType->vct_desc; ?></span> 
									</div>

								</div>
							</div>
						</div>
					<?php } ?>
					<?php
					if ($model->bkg_booking_type == 5)
					{
						$packName = Package::getNamebyId($model->bkg_package_id);
						?>
						<div class="col-xs-12 mb20">

							<div class="heading-part ">Package: <strong><?= $packName ?></strong></div>

						</div>
					<?php } ?>

					<?php $this->renderPartial("bkSummaryTripPlan", ["model" => $model], false, false); ?>



<?php if (($model->bkgFromCity->cty_garage_address == $model->bkg_pickup_address || $model->bkg_pickup_address == '' || $model->bkgToCity->cty_garage_address == $model->bkg_drop_address || $model->bkg_drop_address == '') && $model->bkg_booking_type != 4)
{ ?>
						<div class="col-xs-12">
							<div class="heading-part mb10"><b>UPDATE ADDRESSES</b></div>
							<div class="col-xs-12  main_time border-blueline pickuplocation mb20">
								<div class="col-xs-12"><?php
	$this->renderPartial('pickupLocationWidget', ['model' => $model], false, false);
	?>		</div>
							</div>
						</div>
						<input type="hidden" value="<?php echo ($model->bkgFromCity->cty_garage_address == $model->bkg_pickup_address || $model->bkg_pickup_address == '' || $model->bkgToCity->cty_garage_address == $model->bkg_drop_address || $model->bkg_drop_address == '') ? '0' : '1' ?>" class="isPickupAdrsCls" name="isPickupAdrsCls">
					<?php } ?>

					<?php
					$addinfo = $model->getFullInstructions();
					$addinfo = 0;
					if ($addinfo)
					{
						?>

						<div class="col-xs-12 mb20">
							<div class="heading-part mb10">Additional Request and Instruction</div>
							<div class="main_time border-blueline  ">
								<span class="black-color"><?= $addinfo ?></span>

							</div>								
						</div>	
<?php }
					?>


				</div>
				<?php
				if (!$isMobile)
				{
					$this->renderPartial("bkCanInfo", ["model" => $model], false, false);
					if ($model->bkg_booking_type != 7)
					{
						$this->renderPartial("bkSummaryAdditionalDetail", ["model" => $model, 'isredirct' => $isredirct], false);
					}
				}
				?>
			</div>
			<div class="col-xs-12 col-sm-5">
				<div class="row">
					<?php
					if (!$isMobile)
					{
						$this->renderPartial("bkPayBox", ["model" => $model], false, false);
					}
					?>
					<?php $this->renderPartial("bkSummaryBilling", ["model" => $model, "isredirct" => $isredirct, "refcode" => $refcode, "whatappShareLink" => $whatappShareLink], false, false); ?>

					<?php
					$arrPromoTemplateData						 = array();
					$arrPromoTemplateData['model']				 = $model;
					$arrPromoTemplateData['isPromoApplicable']	 = $isPromoApplicable;
					$arrPromoTemplateData['creditVal']			 = $creditVal['credits'];
					$arrPromoTemplateData['userCreditStatus']	 = $userCreditStatus;
					$arrPromoTemplateData['gozocoinApply']		 = $gozocoinApply;
					$arrPromoTemplateData['promoArr']			 = $promoArr;
					$arrPromoTemplateData['classPromo']			 = '';
					$arrPromoTemplateData['walletBalance']		 = $walletBalance;
					$arrPromoTemplateData['due_amount']			 = min([$model->bkgInvoice->bkg_total_amount, $model->bkgInvoice->bkg_due_amount]);
					;

										
					?>


					<!-- Promo start here-->
					<?php
					if (($isPromoApplicable || ($creditVal > 0 && ($model->bkgInvoice->bkg_credits_used == '' || $model->bkgInvoice->bkg_credits_used == 0 || $userCreditStatus == 1))) && $model->bkg_flexxi_type != 2)
					{
						$classPromo							 = " main_time border-blueline ";
						$arrPromoTemplateData['classPromo']	 = ' main_time border-blueline ';
					}

					if (($model->bkgInvoice->bkg_credits_used == '' || $model->bkgInvoice->bkg_credits_used == 0 || $userCreditStatus == 1) && ($model->bkgInvoice->bkg_advance_amount == 0 || $gozocoinApply == 1) && ($model->bkg_status < 6 || $model->bkg_status == 15) && $model->bkg_booking_type != 7)
					{
						if ($model->bkg_agent_id == NULL && $model->bkg_booking_type != 7)
						{
							?>
							<?php $this->renderPartial("bkSummaryPromo", $arrPromoTemplateData, false, false); ?>
							<?php
						}
						if ($model->bkg_booking_type == 1 && $scvVctId == VehicleCategory::SHARED_SEDAN_ECONOMIC && $model->bkg_flexxi_type == 1)
						{ // FOR FLEXXI
							?>
							<!--<div class="col-xs-12 red-color text-left">			
								<b>*Your Final fare will be between <i class="fa fa-inr"></i><?php #= $promomodel->totalAmount  ?> and <i class="fa fa-inr"></i><span class="bkgamtdetails111"><?php #= $model->bkgInvoice->bkg_total_amount  ?></span>  as shown depending on how many of the seats you have offered to share are purchased by other Flexxi shared riders.</b>
							</div>-->
							<?php
						}
					}
					?>
					<?php
					if (($model->bkgUserInfo->bkg_phone_verified == 0 && $model->bkgUserInfo->bkg_email_verified == 0) && ($model->bkg_agent_id == '' || $model->bkgTrail->bkg_platform == 5 || ($model->bkg_agent_id != '' && $model->bkgTrail->bkg_platform == 4 && $model->bkgInvoice->bkg_corporate_remunerator != 2)))
					{
						?>
						<div class="row">
							<div class="panel-body">
								<?php
								$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
									'id'					 => 'manualverifyotp', 'enableClientValidation' => FALSE,
									'clientOptions'			 => array(
										'validateOnSubmit'	 => true,
										'errorCssClass'		 => 'has-error'
									),
									'enableAjaxValidation'	 => false,
									'errorMessageCssClass'	 => 'help-block',
									'htmlOptions'			 => array(
										'class'			 => 'form-horizontal', 'enctype'		 => 'multipart/form-data', 'autocomplete'	 => "off",
									),
								));
								?>
								<?php $this->endWidget(); ?>
							</div> 
						</div>
				<?php } ?>
				</div>
				<?php
				if ($isMobile)
				{
					?>
					<div id="payment">				
					<?= $this->renderPartial("paywidget", ["model" => $model, 'isredirct' => $isredirct], false); ?>
					</div>
<?php }
?>
				<input type="hidden" name="confBtns" id="confPayNow" value="p1">

			</div>
		</div>
	</div>
</div>
<div class="row text-justify paymentWidjet">
	<div class="col-xs-12">
		<?php
		if (!$isMobile)
		{
			$this->renderPartial("paywidget", ["model" => $model, 'isredirct' => $isredirct], false);
		}
		$this->renderPartial("bookingInfo", ["model"=>$model, "bookingtype"		 => $model->bkg_booking_type,
			'bkg_trip_distance'	 => $model->bkg_trip_distance, 'cab_type_id'		 => $model->bkg_vehicle_type_id,'note'=>$note], false);
		?>
	</div>
</div>
<script type="text/javascript">
    $(document).ready(function ()
    {
        var urls = {"partialsignin": "<?= Yii::app()->createUrl('users/partialsignin') ?>",
            "refreshuserdata": "<?= Yii::app()->createUrl('users/refreshuserdata') ?>",
            "googleurl": "<?= Yii::app()->createUrl('users/oauth', array('provider' => 'Google', 'isFlexxi' => true)); ?>",
            "fburl": "<?= Yii::app()->createUrl('users/oauth', array('provider' => 'Facebook', 'isFlexxi' => true)); ?>",
        };
        $jsLogin = new Login();
        $jsLogin.updateLogin(urls);
        var bookintType =<?= $model['bkg_booking_type']; ?>;
        if (bookintType == 7)
        {
            $("#fullPayChk").prop("checked", true).trigger("click");
        }

    });

    function opentns()
    {
        $href = '<?= Yii::app()->createUrl('index/tns') ?>';
        jQuery.ajax({type: 'GET', url: $href,
            success: function (data)
            {
                box = bootbox.dialog({
                    message: data,
                    title: '',
                    size: 'large',
                    onEscape: function ()
                    {
                        box.modal('hide');
                    }
                });
            }
        });
    }
    function openflexxiterms()
    {
        $href = '<?= Yii::app()->createUrl('index/flexxiterms') ?>';
        jQuery.ajax({type: 'GET', url: $href,
            success: function (data)
            {
                box = bootbox.dialog({
                    message: data,
                    title: '',
                    size: 'large',
                    onEscape: function ()
                    {
                        box.modal('hide');
                    }
                });
            }
        });
    }
    function confirmOTP()
    {
        var bid = $('#bkg_id5').val();
        var hsh = $('#hash5').val();
        var href1 = '<?= Yii::app()->createUrl('booking/confirmmobile') ?>';
        jQuery.ajax({'type': 'GET', 'url': href1,
            'data': {'bid': bid, 'hsh': hsh},
            success: function (data)
            {
                box = bootbox.dialog({
                    message: data,
                    title: '',
                    size: 'medium',
                    onEscape: function ()
                    {
                    }
                });
            }
        });
    }
    function showTcGozoCoins1()
    {
        var href1 = '<?= Yii::app()->createUrl('index/discadv') ?>';
        jQuery.ajax({type: 'GET', url: href1,
            success: function (data)
            {
                box = bootbox.dialog({
                    message: data,
                    title: '',
                    size: 'large',
                    onEscape: function ()
                    {
                        box.modal('hide');
                    }
                });
            }
        });
    }

    function showTcGozoCoins()
    {
        var href1 = '<?= Yii::app()->createUrl('index/tnsgozocoins') ?>';
        jQuery.ajax({type: 'GET', url: href1,
            success: function (data)
            {
                box = bootbox.dialog({
                    message: data,
                    title: '',
                    size: 'large',
                    onEscape: function ()
                    {
                        box.modal('hide');
                    }
                });
            }
        });
    }

    function showTcGozoCoins2()
    {
        var href1 = '<?= Yii::app()->createUrl('index/cashbackadv') ?>';
        jQuery.ajax({type: 'GET', url: href1,
            success: function (data)
            {
                box = bootbox.dialog({
                    message: data,
                    title: '',
                    size: 'large',
                    onEscape: function ()
                    {
                        box.modal('hide');
                    }
                });
            }});
    }

    function showTcGozoCoins25()
    {
        var href1 = '<?= Yii::app()->createUrl('index/cashbackadv25') ?>';
        jQuery.ajax({type: 'GET', url: href1,
            success: function (data)
            {
                box = bootbox.dialog({
                    message: data,
                    title: '',
                    size: 'large',
                    onEscape: function ()
                    {
                        box.modal('hide');
                    }
                });
            }
        });
    }
    function showTcGozoCoins2p5()
    {
        var href1 = '<?= Yii::app()->createUrl('index/discadv2p5') ?>';
        jQuery.ajax({type: 'GET', url: href1,
            success: function (data)
            {
                box = bootbox.dialog({
                    message: data,
                    title: '',
                    size: 'large',
                    onEscape: function ()
                    {
                        box.modal('hide');
                    }
                });
            }
        });
    }

    function verifyOTP(bkgId, hash)
    {
        $.ajax({type: 'POST',
            url: '<?= Yii::app()->createUrl("booking/verifycontact"); ?>',
            data: {"otpvalue": $("#otpvalue").val(), 'id': bkgId, 'hash': hash, "YII_CSRF_TOKEN": $('input[name="YII_CSRF_TOKEN"]').val()},
            dataType: "json",
            success: function (data1)
            {
                if (data1.success)
                {
                    alert(data1.errors);
                    window.location.reload(true);
                }
                else
                {
                    alert(data1.errors);
                }
            },
        });
        return false;
    }
    function reconfirmUpdate()
    {

        var x = $("input[name='bkg_reconfirm_flag']:checked").val();
        if (x == 3)
        {
            if (confirm('Are you sure! you want to Cancel this booking?'))
            {
                $("#payment").hide();
                $("#promo").hide();
                $("input[name=bkg_reconfirm_flag]").attr('disabled', true);
                updateReconfirm();
            }
            else
            {
                $("#bkg_reconfirm_flag_0").prop('checked', true);
                $("#bkg_reconfirm_flag_0").click();
            }
        }
        if (x == 1)
        {
            $("#payment").show();

        }
    }

    function updateReconfirm()
    {
        $.ajax({type: 'POST',
            url: '<?= Yii::app()->createUrl("booking/updateReconfirm"); ?>',
            data: {'id': bid, "YII_CSRF_TOKEN": $('input[name="YII_CSRF_TOKEN"]').val()},
            dataType: "json",
            success: function (data)
            {
                if (data)
                {
                    box = bootbox.dialog({
                        message: data.message,
                        title: 'Cancellation Successful',
                        size: 'medium',
                        buttons: {
                            confirm: {
                                label: 'OK',

                            },

                        },
                        onEscape: function ()
                        {
                            box.modal('hide');
                        }
                    });
                }
            },
        });
    }


</script>
