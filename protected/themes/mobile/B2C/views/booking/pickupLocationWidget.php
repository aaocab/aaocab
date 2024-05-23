<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/fontawesome-web/css/all.min.css?v0.6');
?>

<?
//$api	 = Yii::app()->params['googleBrowserApiKey'];
?>
<? $api	 = Config::getGoogleApiKey('browserapikey'); ?>
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key=<?= $api ?>&libraries=places&"></script>


<div class="content-boxed-widget p0 accordion-path pickupLocation">
    <div class="accordion accordion-style-0">
		<div class="accordion-border">
			<a href="javascript:void(0)" class="font18" data-accordion="accordion-60"><span class="uppercase">Update your pickup & drop addresses</span><i class="fa fa-plus"></i></a>
			<div class="accordion-content" id="accordion-60" style="display: none;">

				<style type="text/css">

					.checkbox-inline {
						padding-top: 0 !important;
						padding-left: 30px;
						margin-top: -5px !important;
					}

					.selectize-dropdown-content {
						overflow-y: auto;
						max-height: 200px;
					}

					.selectize-dropdown, .selectize-dropdown.form-control {
						border-radius: 0;
						-webkit-box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
						box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
					}

					.selectize-dropdown [data-selectable], .selectize-dropdown .optgroup-header {
						padding: 6px 12px;
						border-bottom: solid 1px #aaa;
					}
					.nav-tabs>li.active ,.nav-tabs>li.active>a, li.active,.nav-tab li:active{
						color: #fff !important;
						/*background: #f13016 !important;*/
						background: #ff4f00 !important;
					}
					.timer-control {
						min-width: 100%;
					}
					.home-search,.home-search1{

					}
					.search-form-panel label{
						margin-bottom: 0;
						font-weight: normal;
					}
					.selectize-dropdown-content{
						padding: 0;
					}
					.datepicker {
						padding: 5px
					}
					.btn-rounded.active{
						background-color: #4fb9a7;
						color:#fff;
					}
					.autoMarkerLoc{
						font-size: 30px;
						color:red;
						cursor: pointer;
					}
					.selectize-control.single .selectize-input, .selectize-control.single .selectize-input input{
					}
					.widget-section .yii-selectize{
						margin-bottom: 0;
					}
					.widget-section .accordion a{
						margin: 0;
					}
					.widget-section .PAWToggleLink{
						margin-top: -14px;
					}
					.selectize-input{
						min-height: 40px;
					}
				</style>
				<div class="widget-section">
					<?php
					$form	 = $this->beginWidget('CActiveForm', array(
						'id'					 => 'newAddressForm',
						'enableClientValidation' => true,
						'clientOptions'			 => array(
							'validateOnSubmit'	 => true,
							'errorCssClass'		 => 'has-error'
						),
						'enableAjaxValidation'	 => false,
						'errorMessageCssClass'	 => 'help-block',
						'htmlOptions'			 => array(
							'class' => 'form-horizontal',
						),
					));
					/* @var $form CActiveForm */

					/** @var Booking $model */
					$brtRoutes = $model->bookingRoutes;

					if ($model instanceof Booking)
					{
						$user = $model->bkgUserInfo->bkg_user_id;
					}
					else if ($model instanceof BookingTemp)
					{
						$user = $model->bkg_user_id;
					}
					if ($user == "")
					{
						$user = UserInfo::getUserId();
					}

					$i				 = 0;
					$requiredFields	 = [];
					if ($model->bkgPref->bkg_is_gozonow == 1)
					{
						$brtRoute			 = $brtRoutes[0];
						?>
						<div class="color-gray">Provide street address in <?= $brtRoute->brtFromCity->cty_display_name ?></div>
						<?php
						$requiredFields[]	 = CHtml::activeId($brtRoute, "[$i]from_place");
						$this->widget('application.widgets.PlaceAddress',
								['model' => $brtRoute, 'attribute' => "[$i]from_place", 'city' => $brtRoute->brt_from_city_id, "user" => $user]);
						?>
						<div class="hide">
							<div class="color-gray  ">Provide street address in <?= $brtRoute->brtToCity->cty_display_name ?></div>
							<?php
							$this->widget('application.widgets.PlaceAddress',
									['model'		 => $brtRoute, 'attribute'	 => "[$i]to_place",
										'city'		 => $brtRoute->brt_to_city_id, "user"		 => $user]);
							$i++;
							?></div> 
						<button type = "button" id = "saveNewAddreses" class = "color-blue bg-none bolder pt10 pb10 float-right mt0 uppercase" name = "saveNewAddreses" onclick = "saveAddressesByRoutes();">Proceed</button>
					<? 
					}
					else
					{
						foreach ($brtRoutes as $brtRoute)
						{
							if ($i == 0)
							{
								?>
								<div class="color-gray">Provide street address in <?= $brtRoute->brtFromCity->cty_display_name ?></div>
								<?php
								$requiredFields[] = CHtml::activeId($brtRoute, "[$i]from_place");
								$this->widget('application.widgets.PlaceAddress',
										['model' => $brtRoute, 'attribute' => "[$i]from_place", 'city' => $brtRoute->brt_from_city_id, "user" => $user]);
							}
							?>
							<div class="color-gray">Provide street address in <?= $brtRoute->brtToCity->cty_display_name ?></div>
							<?php
							$this->widget('application.widgets.PlaceAddress',
									['model'		 => $brtRoute, 'attribute'	 => "[$i]to_place",
										'city'		 => $brtRoute->brt_to_city_id, "user"		 => $user]);
							$i++;
						}

						$requiredFields[] = CHtml::activeId($brtRoute, "[" . ($i - 1) . "]to_place");
						?>
						<button type = "button" id = "saveNewAddreses" class = "color-blue bg-none bolder pt10 pb10 float-right mt0 uppercase" name = "saveNewAddreses" onclick = "saveAddressesByRoutes();">Save Addresses</button>
					<? }
					?>

					<input type="radio" name="payChk" value="0" checked="checked" class="mt5 clsPayChk hide">
					<?= $form->hiddenField($model, "bkg_booking_type"); ?>
					<?= $form->hiddenField($model, "bkg_id"); ?>
					<?= $form->hiddenField($model, "hash", ['value' => Yii::app()->shortHash->hash($model->bkg_id)]); ?>
					<?= $form->hiddenField($model, "bkg_status"); ?>

<?php $this->endWidget(); ?>
				</div>

				<script type="text/javascript">
                    function saveAddressesByRoutes()
                    {
                        var success = validateRoute();
                        //var success = validateAddresses();
                        if (success)
                        {
                            var frmcityid = $('#BookingRoute_0_from_place').val();
                            $.ajax({
                                "type": "POST",
                                "url": '<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/updateRouteAddress')) ?>',
                                "data": $('#newAddressForm').serialize(),
                                "dataType": "html",
                                "success": function (data)
                                {
                                    data = jQuery.parseJSON(data);
                                    if (data.success)
                                    {
                                        refreshAddress();
                                        alert("Addresses saved successfully.");
                                        $('.isPickupAdrsCls').val(1);
                                        $('#saveNewAddreses').attr("disabled", "disabled");
                                        $('#newAddreses').attr("disabled", "disabled");
                                        if (data.data.additional_km > 0)
                                        {
                                            updateAfterAddressSaved(data);
                                            var additionalData = $('.clsAdditionalParams').val();
                                            var bkgStatus = $("#Booking_bkg_status").val();
                                            if (additionalData != '' && additionalData != undefined && (bkgStatus == 15 || bkgStatus == 1))
                                            {
                                                var additionalDataObj = JSON.parse(additionalData);
                                                if (additionalDataObj.wallet > 0)
                                                {
                                                    prmObj.applyPromo(5, additionalDataObj.wallet);
                                                }
                                                if (additionalDataObj.code != '')
                                                {
                                                    prmObj.applyPromo(1, additionalDataObj.code);
                                                }
                                                if (additionalDataObj.coins > 0)
                                                {
                                                    prmObj.applyPromo(3, additionalDataObj.coins);
                                                }
                                            }
                                        }
                                    }
                                }

                            });
                        }
                    }

                    function refreshAddress()
                    {
                        $href = "<?php echo Yii::app()->createUrl('booking/refreshAddressWidget') ?>";
                        var bkg_id = $('#Booking_bkg_id').val();
                        var hash = $('#Booking_hash').val();
                        jQuery.ajax({
                            global: false,
                            type: 'GET',
                            url: $href,
                            data: {"booking_id": bkg_id, "hash": hash},

                            success: function (data1)
                            {
                                $(".addressWidget").html(data1);
                                $("#bidList").show();
                                setTimer();
                            }
                        });
                    }
                    function updateAfterAddressSaved(data)
                    {
                        if (data.data.fare.customerPaid == 0) {
                            $('.clsPayChk').checked = true;
                        }
                        if (huiObj == null || huiObj == undefined) {
                            huiObj = new HandleUI();
                        }
                        huiObj.bkgId = '<?= $model->bkg_id ?>';
                        huiObj.updateInvoice(data);
                        $('.extrachargeDiv').removeClass('hide');
                        $('.additionalKmVal').html(data.data.additional_km);
                        $('.extraChargeVal').html(data.data.extra_charge);
                        $('.oldBasefareDiv').removeClass('hide');
                        $('.txtBaseFareOld').html(data.data.oldBaseFare);
                    }

                    function validateRoute()
                    {
                        var reqFields = <?= CJavaScript::encode($requiredFields) ?>;
                        var success = true;
                        $.each(reqFields, function (key, value)
                        {
                            var PAWObject = AWObject.get(value);
                            var PAWVal = PAWObject.model.id;
                            if (PAWObject && !PAWObject.hasData())
                            {
                                success = false;
                                alert("Pickup and Drop locations are mandatory");
                                PAWObject.focus();
                            } else if ($('#' + PAWVal).val() == '')
                            {
                                success = false;
                                alert("Please enter proper address");
                                PAWObject.focus();
                            }
                            return success;
                        });

                        return success;
                    }

				</script>

			</div>
		</div>
	</div>
</div>