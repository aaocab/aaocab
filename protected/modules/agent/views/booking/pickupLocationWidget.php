<style>

.border {
    border: 1px solid #1c201c;
}

</style>


<?php
$version = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/rap.js?v=' . $version, CClientScript::POS_HEAD);
$autoAddressJSVer = Yii::app()->params['autoAddressJSVer'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/js/gozo/bookingRoute.js?v=$autoAddressJSVer");
?>
<?php

$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
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
/* @var $form TbActiveForm */

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
//foreach ($brtRoutes as $brtRoute)
foreach ($brtRoutes as $key => $brtRoute)
{
	if ($brtRoute->brtFromCity->cty_is_airport == 1 || $brtRoute->brtToCity->cty_is_airport == 1)
	{
		if ($brtRoute->brtFromCity->cty_is_airport == 1)
		{
			echo $brtRoute->brtFromCity->cty_display_name . '-' . $brtRoute->brtFromCity->cty_alias_name;
		}
		else
		{
			echo $brtRoute->brtFromCity->cty_display_name . '-' . $brtRoute->brtFromCity->cty_alias_name . '-' . $brtRoute->brt_from_location;
		}
		echo "<br />To<br />";
		if ($brtRoute->brtToCity->cty_is_airport == 1)
		{
			echo $brtRoute->brtToCity->cty_display_name . '-' . $brtRoute->brtToCity->cty_alias_name;
		}
		else
		{
			echo $brtRoute->brtToCity->cty_display_name . '-' . $brtRoute->brtToCity->cty_alias_name . '-' . $brtRoute->brt_to_location;
		}
	}

else{

//$ctr = ($brtRoute->brt_id > 0) ? $brtRoute->brt_id : $i;




						$fplace = new \Stub\common\Location();
						$fplace->setData(null, null, $brtRoute->brt_from_location, $brtRoute->brt_from_latitude, $brtRoute->brt_from_longitude, null);

						$tplace = new \Stub\common\Location();
						$tplace->setData(null, null, $brtRoute->brt_to_location, $brtRoute->brt_to_latitude, $brtRoute->brt_to_longitude, null);
						$keyNew = $brtRoute->brt_id;
						?>
						<div class="row m0">
							<?php
							if ($i == 0)
							{
								?>
								<div class="col-12 col-xl-6 mb-1">
									<p class="weight500 mb5" for="iconLeft">
										Your pickup address in <?= $brtRoute->brtFromCity->cty_display_name ?>
									</p>
									<div class="pick mb10"><input type="hidden" value="<?=$fplace->address?>" name="<?=$brtRoute->brt_id.'_from_place'?>" id="<?=$brtRoute->brt_id.'_from_place_old'?>">

									<?php
									
																	if (!in_array($model->bkg_status, [9, 10]))
												{
													$addressVal = CJSON::encode($fplace);

													$fadrs = $fplace->address;
													if ($fplace->address != '')
													{
														$arrAdrs = explode(",", $fplace->address);
														$fadrs	 = $arrAdrs[0];
													}
													if (($brtRoute->brtFromCity->cty_name == $fplace->address || $brtRoute->brtFromCity->cty_name == $fadrs) && $fplace->address != '')
													{
														$addressVal = '';
													}

													$this->widget('application.widgets.SelectAddress', array(
														'model'			 => $brtRoute,
														"htmlOptions"	 => ["class" => "border border-light  p10 selectAddress  item"],
														'attribute'		 => "[{$brtRoute->brt_id}]from_place",
														"city"			 => "{$brtRoute->brt_from_city_id}",
														"value"			 => $addressVal,
														"modalId"		 => "addressModal",
														'viewUrl'		 => '/agent/booking/selectAddress',
														"brtId"			 => "$brtRoute->brt_id"
													));
												}
												?>
									</div>
					
								</div>
							<?php } ?>
							
							<div class="col-12 col-xl-6 mb-1">
								<p class="weight500 mb5" for="iconLeft">
									Your drop address in <?= $brtRoute->brtToCity->cty_display_name ?></p>
								<div class="drop mb10"  >
<input type="hidden" value="<?=$tplace->address?>" name="<?=$brtRoute->brt_id.'_to_place'?>" id="<?=$brtRoute->brt_id.'_to_place_old'?>">
									<?php
										
											if (!in_array($model->bkg_status, [9, 10]))
											{

												$toAddressVal	 = CJSON::encode($tplace);
												$tadrs			 = $tplace->address;
												if ($tplace->address != '')
												{
													$arrAdrs = explode(",", $tplace->address);
													$tadrs	 = $arrAdrs[0];
												}
												if (($brtRoute->brtToCity->cty_name == $tplace->address || $brtRoute->brtToCity->cty_name == $tadrs) && $tplace->address != '')
												{
													$toAddressVal = '';
												}


												$this->widget('application.widgets.SelectAddress', array(
													'model'			 => $brtRoute,
													"htmlOptions"	 => ["class" => "border border-light  p10 selectAddress item"],
													'attribute'		 => "[{$key}]to_place",
													"city"			 => "{$brtRoute->brt_to_city_id}",
													"value"			 => $toAddressVal,
													"modalId"		 => "addressModal",
													'viewUrl'		 => '/agent/booking/selectAddress',
													"brtId"			 => "$brtRoute->brt_id"
												));
											}
											?>
									
										
								</div>
							

							</div>
						</div>
						<?php
						$i++;
					
					

}
}
//$requiredFields[] = CHtml::activeId($brtRoute, "[" . ($ctr) . "]to_place");
?>

<?= $form->hiddenField($model, "bkg_id"); ?>
<?= $form->hiddenField($model, "hash", ['value' => Yii::app()->shortHash->hash($model->bkg_id)]); ?>
<!--<div class="col-12 text-center">
	<button type="button" id="saveNewAddreses" class="btn btn-effect-ripple btn-success p5 mt10" name="saveNewAddreses" onclick="saveAddressesByRoutes();">Save Addresses</button>
</div>-->
<?if($spotBooking){?>
   <div class="col-xs-12 mt30 pr30">
			<button type="submit" class="pull-left  btn btn-danger btn-lg pl25 pr25 pt30 pb30" name="step8ToStep7"><b> <i class="fa fa-arrow-left"></i> Previous</b></button><button type="submit" class="  pull-right btn btn-primary btn-lg pl50 pr50 pt30 pb30"  name="step8submit"><b>Next <i class="fa fa-arrow-right"></i></b></button>
        </div>
<?}?>
<?php $this->endWidget(); ?>
<a href="#" data-menu="map-marker" class="hide" id="booknow-map-marker"></a>

<script type="text/javascript">
	$(document).ready(function(){
		$('[data-toggle="tooltip"]').tooltip();   
	  });
//    function saveAddressesByRoutes()
//    {
//        var success = validateRoute();
//     
//        if (success)
//        {
//            var frmcityid = $('#Cities_form_cty_id').val();
//            $.ajax({
//                "type": "POST",
//                "url": '<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/updateRouteAddress')) ?>',
//                "data": $('#newAddressForm').serialize(),
//                "dataType": "html",
//                "success": function (data)
//                {
//                    data = jQuery.parseJSON(data);
//                    if (data.success)
//                    {
//                        alert("Addresses saved successfully.");
//                        $('.isPickupAdrsCls').val(1);
//                        $('#saveNewAddreses').attr("disabled", "disabled");
//                        $('#newAddreses').attr("disabled", "disabled");
//                        if (data.data.additional_km > 0)
//                        {
//                            updateAfterAddressSaved(data);
//                            var additionalData = $('.clsAdditionalParams').val();
//                            var bkgStatus = $("#Booking_bkg_status").val();
//                            if (additionalData != '' && additionalData != undefined && (bkgStatus == 15 || bkgStatus == 1))
//                            {
//                                var additionalDataObj = JSON.parse(additionalData);
//                                if (additionalDataObj.wallet > 0)
//                                {
//                                    prmObj.applyPromo(5, additionalDataObj.wallet);
//                                }
//                                if (additionalDataObj.code != '')
//                                {
//                                    prmObj.applyPromo(1, additionalDataObj.code);
//                                }
//                                if (additionalDataObj.coins > 0)
//                                {
//                                    prmObj.applyPromo(3, additionalDataObj.coins);
//                                }
//                            }
//                        }
//                    }
//                }
//
//            });
//        }
//    }

//    function updateAfterAddressSaved(data)
//    {
//        if (data.data.fare.customerPaid == 0)
//        {
//            $('.clsPayChk').checked = true;
//        }
//        if (huiObj == null || huiObj == undefined)
//        {
//            huiObj = new HandleUI();
//        }
//        huiObj.bkgId = '<?= $model->bkg_id ?>';
//        huiObj.updateInvoice(data);
//        $('.extrachargeDiv').removeClass('hide');
//        $('.additionalKmVal').html(data.data.additional_km);
//        $('.extraChargeVal').html(data.data.extra_charge);
//        $('.oldBasefareDiv').removeClass('hide');
//        $('.txtBaseFareOld').html(data.data.oldBaseFare);
//    }

//    function validateRoute()
//    {
//        var reqFields = <?= CJavaScript::encode($requiredFields) ?>;
//        var success = true;
//        $.each(reqFields, function (key, value)
//        {
//            var PAWObject = AWObject.get(value);
//			var PAWVal = PAWObject.model.id;
//            if (PAWObject && !PAWObject.hasData())
//            {
//                success = false;
//                alert("Pickup and Drop locations are mandatory");
//                PAWObject.focus();
//            }
//			else if($('#' + PAWVal).val() == '')
//			{
//				success = false;
//                alert("Please enter proper address");
//                PAWObject.focus();
//			}
//			
//            return success;
//        });
//
//        return success;
//    }
function blockForm(form)
        {
            block_ele = form.closest('form');

            $(block_ele).block({
                message: '<div class="loader"></div>',
                overlayCSS: {
                    backgroundColor: "#FFF",
                    opacity: 0.8,
                    cursor: 'wait'
                },
                css: {
                    border: 0,
                    padding: 0,
                    backgroundColor: 'transparent'
                }
            });
        }
  function unBlockForm()
        {
            $(block_ele).unblock();
        }
        
        	function validateAddressDayrental(widgetId, fieldId) {
          
		var bkgTypeArry = [9, 10, 11];
		var bkgType = <?php echo $model->bkg_booking_type; ?>;
		if (bkgTypeArry.includes(bkgType))
		{
           
			address = $('.' + fieldId).text();
			addressObj = $('#' + fieldId).val();
			brtRouteArray = fieldId.split("_");
          //  alert(brtRouteArray[1]);
//			$('.BookingRoute_' + brtRouteArray[1] + '_to_place').text(address);
//			$('#BookingRoute_' + brtRouteArray[1] + '_to_place').val(addressObj);
//			$('.BookingRoute_' + brtRouteArray[1] + '_to_place').unbind("click");
		
    $('.BookingRoute_0_to_place').text(address);
			$('#BookingRoute_0_to_place').val(addressObj);
			$('.BookingRoute_0_to_place').unbind("click");
    }
	}
</script>
