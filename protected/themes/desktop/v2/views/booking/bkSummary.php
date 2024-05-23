<style>
    .sidebar{
		background: none;
	}
    .sidenav{
		background: #0f264d;
		margin-bottom: 5px;
		border-radius: 5px;
		height: auto;
	}
    .sidebar{
		padding-top: 10px;
		padding-bottom: 10px;
	}
    .dropdown-container{
		background: #f7f7f7;
		border: #e7e7e7 1px solid;
		padding: 15px;
	}
    .sidenav .active{
		background: #0f264d;
	}
    .sidenav a, .dropdown-btn{
		padding: 12px 8px 12px 16px;
		font-size: 18px;
		color: #fff;
		font-weight: 500;
		border-bottom: none;
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
	        prmObj.applyPromo(0, '<?= $pModel->prm_code; ?>'); // Bydefault Promo NOV19 will be applied.	
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
$row			 = 'row';
$version		 = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/promo.js?v=' . $version);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/promotion.js?v=' . $version);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/handleUI.js?v=' . $version);
$walletBalance	 = UserWallet::model()->getBalance(UserInfo::getUserId());
$scvVctId		 = SvcClassVhcCat::model()->getCatIdBySvcid($model->bkg_vehicle_type_id);

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
				<?php
			}

			if ($model->bkgInvoice->bkg_advance_amount < 1 && ($model->bkg_agent_id != NULL || $model->bkg_agent_id != ''))
			{
				?>
				<div class=" text-center mb20 alert alert-danger">RECONFIRMATION & PAYMENT NEEDED: Make payment of atleast 15% below to reconfirm booking. Booking will be auto-cancelled if not reconfirmed by <?= date('jS M Y (l)', strtotime($model->bkg_pickup_date)); ?> , <?= date('h:i A', strtotime($model->bkg_pickup_date)); ?></div>
				<div class=" text-center mb20">
					<?= CHtml::radioButtonList('bkg_reconfirm_flag', 1, array(1 => 'Yes, confirm this booking as soon as payment is received', 3 => 'No, I didnt request this booking. Please cancel it'), array('id' => "reconfirm", 'separator' => "    ", 'onclick' => "reconfirmUpdate()")); ?>
				</div>
				<?php
			}
			else if ($model->bkgInvoice->bkg_advance_amount < 1 && $model->bkgTrail->bkg_platform == 2)
			{
				?>
				<div class=" text-center mb20 alert alert-danger">RECONFIRMATION & PAYMENT NEEDED: Make payment of atleast 15% below to reconfirm booking. Booking will be auto-cancelled if not reconfirmed </div>
				<div class=" text-center mb20">
					<?= CHtml::radioButtonList('bkg_reconfirm_flag', 1, array(1 => 'Yes, confirm this booking as soon as payment is received', 3 => 'No, I didnt request this booking.Please cancel it'), array('id' => "reconfirm", 'separator' => "    ", 'onclick' => "reconfirmUpdate()")); ?>
				</div>
				<?php
			}

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

<div class="container mt30">
	<div class="<?= $row ?> detailsWidjet">
		<div class="col-sm-7">
			<div class="row">
				<?php
				$this->renderPartial("bkSummaryTravellerInfo", ["model" => $model], false, false);

				if (($model->bkg_status == 5 || $model->bkg_status == 6) && $showUserInfoPickup && $model->bkgBcb->bcb_driver_phone != '')
				{
					?>
					<div class="col-12 mb20">
						<div class="heading-part mb10">Trip Information</div>
						<div class="main_time border-blueline  ">
							<div class="row">
								<div class="col-12 col-sm-6">
									Driver Name: <span class="black-color"><?= $model->bkgBcb->bcb_driver_name ?></span><br>

									Driver Phone: <span class="black-color"><?= Yii::app()->params['customerToDriver'] ?></span><br>
								</div>
								<div class="col-12 col-sm-6">
									Cab Number: <span class="black-color"><?= $model->bkgBcb->bcb_cab_number; ?></span><br>
									Cab Model: <span class="black-color"><?= $model->bkgBcb->bcbCab->vhcType->vct_desc; ?></span> 
								</div>

							</div>
						</div>
					</div>
					<?php
				}
				?>

				<div class="col-12 mb20">
					<div class=" bg-white-box">
						<div class="row">
							<div class="col-9 font-20 mb10 text-uppercase"><b>UPDATE YOUR PICKUP & DROP ADDRESSES</b></div>							
						</div>


						<div class="row">
							<div class="col-12"><?php
								$this->renderPartial('pickupLocationWidget', ['model' => $model], false, false);
								?>		
								<input type="hidden" value="<? echo (($model->bkgFromCity->cty_garage_address == $model->bkg_pickup_address || $model->bkg_pickup_address == '' || $model->bkgToCity->cty_garage_address == $model->bkg_drop_address || $model->bkg_drop_address == '' ) && $model->bkg_booking_type != 4) ? '0' : '1' ?>" class="isPickupAdrsCls" name="isPickupAdrsCls">
							</div>
						</div>
						<div class="row">	
							<div class="col-9 heading-part mb10"></div>
							<div class="col-3 heading-part mb10">
								<button type="button" id="saveNewAddreses" class="btn btn-effect-ripple btn-success p5 mt10" name="saveNewAddreses" onclick="saveAddressesByRoutes();">Save Addresses</button>
							</div>
						</div>
					</div>
				</div>
				<?php
				if ($model->bkg_booking_type == 5)
				{
					$packName = Package::getNamebyId($model->bkg_package_id);
					?>
					<div class="col-12 mb20">

						<div class="heading-part ">Package: <strong><?= $packName ?></strong></div>

					</div>
					<?php
				}
				$this->renderPartial("bkSummaryTripPlan", ["model" => $model], false, false);

				$addinfo = $model->getFullInstructions();
				$addinfo = 0;
				if ($addinfo)
				{
					?>
					<div class="col-12 mb20">
						<div class="heading-part mb10">Additional Request and Instruction</div>
						<div class="main_time border-blueline  ">
							<span class="black-color"><?= $addinfo ?></span>

						</div>								
					</div>	
				<?php } ?>
			</div>
			<?php
			$cancelTimes_new = CancellationPolicy::initiateRequest($model);
			$this->renderPartial("bkCanInfo", ["model" => $model, "cancelTimes_new" => $cancelTimes_new], false, false);
			if ($model->bkg_booking_type != 7)
			{
				$this->renderPartial("bkSummaryAdditionalDetail", ["model" => $model, 'isredirct' => $isredirct], false);
			}
			?>
		</div>
		<div class="col-sm-5">
			<div class="row">
				<?php
				$this->renderPartial("bkPayBox", ["model" => $model, "cancelTimes_new" => $cancelTimes_new], false, false);
				$this->renderPartial("bkSummaryBilling", ["model" => $model, "isredirct" => $isredirct, "refcode" => $refcode, "whatappShareLink" => $whatappShareLink], false, false);

				$arrPromoTemplateData						 = array();
				$arrPromoTemplateData['model']				 = $model;
				$arrPromoTemplateData['isPromoApplicable']	 = $isPromoApplicable;
				$arrPromoTemplateData['creditVal']			 = $creditVal['credits'];
				$arrPromoTemplateData['userCreditStatus']	 = $userCreditStatus;
				$arrPromoTemplateData['gozocoinApply']		 = $gozocoinApply;
				$arrPromoTemplateData['promoArr']			 = $promoArr;
				$arrPromoTemplateData['classPromo']			 = '';
				$arrPromoTemplateData['walletBalance']		 = $walletBalance;

// Promo start here
				if (($isPromoApplicable || ($creditVal > 0 && ($model->bkgInvoice->bkg_credits_used == '' || $model->bkgInvoice->bkg_credits_used == 0 || $userCreditStatus == 1))) && $model->bkg_flexxi_type != 2)
				{
					$classPromo							 = " main_time border-blueline ";
					$arrPromoTemplateData['classPromo']	 = ' main_time border-blueline ';
				}

				if (($model->bkgInvoice->bkg_credits_used == '' || $model->bkgInvoice->bkg_credits_used == 0 || $userCreditStatus == 1) && ($model->bkgInvoice->bkg_advance_amount == 0 || $gozocoinApply == 1) && ($model->bkg_status < 6 || $model->bkg_status == 15) && $model->bkg_booking_type != 7)
				{
					if ($model->bkg_agent_id == NULL && $model->bkg_booking_type != 7)
					{
						$this->renderPartial("bkSummaryPromo", $arrPromoTemplateData, false, false);
					}
				}
				if ($applicableAddons)
				{
					$this->renderPartial("bkSummaryAddons", ["model" => $model, 'applicableAddons' => $applicableAddons, 'routeRatesArr' => $routeRatesArr], false, false);
				}
				if (($model->bkgUserInfo->bkg_phone_verified == 0 && $model->bkgUserInfo->bkg_email_verified == 0) && ($model->bkg_agent_id == '' || $model->bkgTrail->bkg_platform == 5 || ($model->bkg_agent_id != '' && $model->bkgTrail->bkg_platform == 4 && $model->bkgInvoice->bkg_corporate_remunerator != 2)))
				{
					?>
					<div class="row">
						<div class="panel-body">
							<?php
							$form = $this->beginWidget('CActiveForm', array(
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

							$this->endWidget();
							?>
						</div> 
					</div>
				<?php } ?>
			</div>
			<input type="hidden" name="confBtns" id="confPayNow" value="p1">
		</div>
	</div>
</div>
<div class="container text-justify">
	<div class="row">
		<?php
		$this->renderPartial("paywidget", ["model" => $model, 'isredirct' => $isredirct, 'walletBalance' => $walletBalance], false);
		$this->renderPartial("bookingInfo", ["model"				 => $model, "bookingtype"		 => $model->bkg_booking_type,
			'bkg_trip_distance'	 => $model->bkg_trip_distance, 'cab_type_id'		 => $model->bkg_vehicle_type_id, 'note'				 => $note], false);
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
                        box.css('display', 'none');
                        $('.modal-backdrop').remove();
                        $("body").removeClass("modal-open");
                    }
                }).removeClass('fade').css('display', 'block');
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
                        box.css('display', 'none');
                        $("body").removeClass("modal-open");
                    }
                }).removeClass('fade').css('display', 'block');
            }
        });
    }

    function showTcGozoCoins2()
    {
        var href1 = '<?= Yii::app()->createUrl('index/cashbackadv') ?>';
        jQuery.ajax({type: 'GET', url: href1,
            success: function (data)
            {
                boxtc = bootbox.dialog({
                    message: data,
                    title: '',
                    size: 'large',
                    onEscape: function ()
                    {
                        boxtc.modal('hide');
                        boxtc.css('display', 'none');
                        $('.modal-backdrop').remove();
                        $("body").removeClass("modal-open");

                    }
                }).removeClass('fade').css('display', 'block');
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
                } else
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
            } else
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
