<?php
/** @var BookFormRequest $objPage */
$objPage	 = $this->pageRequest;
/** @var Stub\common\Booking $objBooking */
$objBooking	 = $objPage->booking;
$version = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/js/gozo/hyperLocation.js?v=$version");
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/jquery.disableAutoFill.min.js');

$tncType	 = TncPoints::getTncIdsByStep(12);
$tncArr		 = TncPoints::getTypeContent($tncType);
?>
<?php
$brtRoutes	 = $model->bookingRoutes;

$addressLabel	 = ($model->bkg_booking_type == 4) ? 'Location' : 'Address';
$isGozoNow		 = 0;
if ($model instanceof Booking)
{
	$user		 = $model->bkgUserInfo->bkg_user_id;
	$isGozoNow	 = $model->bkgPref->bkg_is_gozonow;
}
else if ($model instanceof BookingTemp)
{
	$user		 = $model->bkg_user_id;
	$isGozoNow	 = $model->bkg_is_gozonow;
}
if ($user == "")
{
	$user = UserInfo::getUserId();
}


if ($user != '')
{
	//	$usrModel					 = Users::model()->findByPk($user);
	$contactId		 = ContactProfile::getByEntityId($user);
	$contactModel	 = Contact::model()->findByPk($contactId);
	$primaryPhone	 = "+" . ContactPhone::getContactNumber($contactId);
	$emailId		 = ContactEmail::getContactEmailById($contactId);
	$isValid		 = Filter::validatePhoneNumber($primaryPhone);
	if ($isValid)
	{
		Filter::parsePhoneNumber($primaryPhone, $code, $number);
	}
	$usermodel->bkg_user_fname	 = $contactModel->ctt_first_name;
	$usermodel->bkg_user_lname	 = $contactModel->ctt_last_name;
	$usermodel->bkg_contact_no	 = $number;
	$usermodel->bkg_country_code = $code;
	$usermodel->bkg_user_email	 = $emailId;
}

$diff = floor((strtotime($model->bkg_pickup_date) - time()) / 3600);

$form	 = $this->beginWidget('CActiveForm', array(
	'id'					 => 'customerinfo',
	'action'				 => 'booking/address',
	'enableClientValidation' => true,
	'clientOptions'			 => array(
		'validateOnSubmit'	 => true,
		'errorCssClass'		 => 'has-error'
	),
	'enableAjaxValidation'	 => false,
	'errorMessageCssClass'	 => 'help-block',
	'htmlOptions'			 => array(
		'class'		 => 'form-horizontal',
		'onsubmit'	 => 'return saveAddressesByRoutes(this);'
	),
		));
/* @var $form CActiveForm */
?>
<?= $form->hiddenField($model, "bkg_id"); ?>
<input type="hidden" id="hash" name="hash"  value="<?php echo $hash ?>">
<input type="hidden" id="isAirport" name="isAirport" value="<?php echo $brtRoutes[0]->brtFromCity->cty_is_airport; ?>">
<div class="row">
	<div class="col-12 text-center">
		<p class="mb0"><!--<img src="/images/img-2022/location.svg" width="70" alt="">--><img src="/images/bxs-map-pin.svg" alt="img" width="48" height="48"></p>
	</div>

	<div class="col-12 mt-1">
		<div class="row">
			<div class="col-12">
				<div class=" bg-white-box">

					<?php
					$i		 = 0;
					foreach ($brtRoutes as $key => $brtRoute)
					{
						?>
						<div class="row">
							<?php
							if ($i == 0)
							{

								if ($model->bkg_booking_type != 14)
								{
									?>
									<div class="col-12 col-xl-12 add-widget mb-1">
										<p class="weight600 mb5" for="iconLeft">
											Your pickup address in <?= $brtRoute->brtFromCity->cty_display_name ?>
											<input type="hidden" class='addressline' id="jsonAddr_<?= $brtRoute->brtFromCity->cty_id ?>" value="">
										</p><?php
										if ($brtRoute->brtFromCity->cty_is_airport != 1 && !in_array($model->bkg_booking_type, [4, 12, 14, 15]))
										{
											$laterChkBox = true;
											if ($brtRoute->brtFromCity->cty_is_poi == 1 || $diff < 24)
											{
												$laterChkBox = false;
											}
											$this->widget('application.widgets.SelectAddress', array(
												'model'			 => $brtRoute,
												"htmlOptions"	 => ["class" => "  border border-light rounded p10 selectAddress item"] + ['platform' => 1] + ['pickLater' => "pickup_later_chk_{$key}"],
												'attribute'		 => "[{$key}]from_place",
												"city"			 => "{$brtRoute->brt_from_city_id}",
												"modalId"		 => "myAddressModal"
											));
											if ($laterChkBox)
											{
												echo $form->checkBox($model, "pickup_later_chk", ['class' => 'mt10', 'id' => "pickup_later_chk_{$key}", 'onclick' => "removeAdrsPickup('{$key}',true)"]);
												?>

												<label for="Booking_pickup_later_chk" style="cursor: pointer" class="ml5" onclick="removeAdrsPickup(<?= $key ?>, false)">I will provide later</label>
											<?php
											}
										}
										else
										{
											?>
											<div class="border border-light rounded p10 selectAddress item">
												<?php
												if ($brtRoute->brtFromCity->cty_is_airport == 1 || $brtRoute->brtFromCity->cty_is_poi == 1 || in_array($brtRoute->brtFromCity->cty_poi_type, [1, 2]))
												{
													echo $brtRoute->brtFromCity->cty_full_name;
												}
												else if (in_array($model->bkg_booking_type, [4, 12, 14, 15]))
												{
													echo $brtRoute->brt_from_location;
												}
												?></div>
											<?php
										}
										?>

									</div>
								<?php }
							} ?>
							<?php
									// if ($model->bkg_is_gozonow == 0)
							//{
							?>
							<div class="col-12 col-xl-12 add-widget">
								<p class="weight600 mb5" for="iconLeft">
									Your drop address in <?= $brtRoute->brtToCity->cty_display_name ?></p>
								<input type="hidden" class='addressline' id="jsonAddr_<?= $brtRoute->brtToCity->cty_id ?>" value="">
								<?php
								if ($brtRoute->brtToCity->cty_is_airport != 1 && !in_array($model->bkg_booking_type, [4, 12, 14, 15]))
								{
									$laterChkBox = true;
									if ($brtRoute->brtToCity->cty_is_poi == 1 || $diff < 24)
									{
										$laterChkBox = false;
									}
									$this->widget('application.widgets.SelectAddress', array(
										'model'			 => $brtRoute,
										"htmlOptions"	 => ["class" => "  border border-light rounded p10 selectAddress item"] + ['platform' => 1] + ['pickLater' => "drop_later_chk_{$key}"],
										'attribute'		 => "[{$key}]to_place",
										"city"			 => "{$brtRoute->brt_to_city_id}",
										"modalId"		 => "myAddressModal"
									));
									if ($laterChkBox)
									{
										echo $form->checkBox($model, "drop_later_chk", ['class' => 'mt10', 'id' => "drop_later_chk_{$key}", 'onclick' => "removeAdrsDrop('{$key}',true)"]);
										?>

										<label for="Booking_drop_later_chk" style="cursor: pointer" class="ml5" onclick="removeAdrsDrop(<?= $key ?>, false)">I will provide later</label>
									<?php
									}
								}
								else
								{
									?>
									<div class="border border-light rounded p10 selectAddress item">
										<?php
										if ($brtRoute->brtToCity->cty_is_airport == 1 || $brtRoute->brtToCity->cty_is_poi == 1 || in_array($brtRoute->brtToCity->cty_poi_type, [1, 2]))
										{
											echo $brtRoute->brtToCity->cty_full_name;
										}
										else if (in_array($model->bkg_booking_type, [4, 12, 14, 15]))
										{
											echo $brtRoute->brt_to_location;
										}
										?></div>
								<?php
							}
							?>

							</div>
						<?php // } ?>
						</div>
	<?php
	$i++;
}
?>
				</div>
			</div>
		</div>
	</div>
	<div class="col-12 mt-2">
		<div class="row m0 justify-center">
			<div class="col-xl-12 text-center mb-1">
				<input type="hidden" name="step" value="<?= $pageid ?>">
				<input type="hidden" name="rdata" value="<?= $this->pageRequest->getEncrptedData() ?>">
<!--				<input type="button" value="Go back" step="<?= $pageid ?>" name="yt0" class="btn btn-light backButton">-->
				<button type="submit" class="btn btn-primary pl-5 pr-5">Proceed</button>
			</div>

			<div class="col-12 mb5">
				<div class="row mb-1">
					<div class="col-2 col-lg-2 mt-2 roundimageairport p0 hide"><div class="round-2"><img src="/images/img-2022/sonia.jpg" alt="Sonia" title="Sonia"></div></div>
					<div class="col-10 col-lg-10 cabcontentairport mt-1"></div>
				</div>
			</div>
		</div>
	</div>
</div>

</div>
<?php $this->endWidget(); ?>
<script type="text/javascript">
	$(document).ready(function()
	{	//debugger;
		step = <?= $step ?>;
		prevstep = <?= $prevstep ?>;
		tabURL = "<?= $this->getURL($this->pageRequest->getInfoURL()) ?>";
		pageTitle = "";

		if (parseInt(prevstep) == 6)
		{
			tabURL = "<?= $this->getURL($objPage->getQuoteURL()) ?>";
			prevstep = 5;
		}
		if (parseInt(prevstep) == 12)
		{
			tabHead = "<?= $this->pageRequest->getCabServiceAddonsDesc() ?>";
		}

		toggleStep(step, prevstep, tabURL, pageTitle, tabHead, true, <?= $this->pageRequest->step ?>);

		var isCityAirport = '<?= $brtRoute->brtToCity->cty_is_airport ?>';
		if (parseInt(isCityAirport) == 1)
		{
			var tncval = JSON.parse('<?= $tncArr ?>');
			$('.cabcontentairport').html(tncval[86]);
			$('.roundimageairport').removeClass('hide');
		}

		var bkgType = '<?= $model->bkg_booking_type ?>';
		if (bkgType == 9 || bkgType == 10 || bkgType == 11)
		{
			$(".BookingRoute_0_to_place").unbind("click");
			$('#drop_later_chk_0').css('pointer-events', 'none');
			$("label[for='Booking_drop_later_chk']").css('pointer-events', 'none');
		}

	});


	function removeAdrsPickup(key, isChkbox = true)
	{
		var adrsElement = 'BookingRoute_' + key + '_from_place';
		let laterchkbox = $('#pickup_later_chk_' + key);
		var bkgType = '<?= $model->bkg_booking_type ?>';
		if (isChkbox)
		{
			if (laterchkbox.is(':checked'))
			{
				$('#' + adrsElement).val('');
				$('.' + adrsElement).html('&nbsp;');
				if (bkgType == 9 || bkgType == 10 || bkgType == 11)
				{
					$('#drop_later_chk_' + key).prop('checked', true);
					$('#drop_later_chk_' + key).css('pointer-events', 'none');
					$("label[for='Booking_drop_later_chk']").css('pointer-events', 'none');
					removeAdrsDrop(key, true);
				}
			}
		}
		else
		{
			laterchkbox.click();
		}

		fromplace = $('#BookingRoute_' + key + '_from_place').val();
		if ((!laterchkbox.is(':checked') || fromplace != '') && (bkgType == 9 || bkgType == 10 || bkgType == 11))
		{
			$('#drop_later_chk_' + key).prop('checked', false);
			$('#drop_later_chk_' + key).css('pointer-events', 'none');
			$("label[for='Booking_drop_later_chk']").css('pointer-events', 'none');
	}
	}
	function removeAdrsDrop(key, isChkbox = true)
	{
		var adrsElement = 'BookingRoute_' + key + '_to_place';
		let laterchkbox = $('#drop_later_chk_' + key);
		if (isChkbox)
		{
			if (laterchkbox.is(':checked'))
			{
				$('#' + adrsElement).val('');
				$('.' + adrsElement).html('&nbsp;');
			}
		}
		else
		{
			laterchkbox.click();
	}

	}

	function saveAddressesByRoutes()
	{
		var success = validateRoute();
		var form = $("form#customerinfo");
		if (success)
		{
			//debugger;
			//alert($('#customerinfo').serialize());
			$.ajax({
				"type": "POST",
				"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/address')) ?>",
				"data": form.serialize(),
				"beforeSend": function()
				{
					blockForm(form);
				},
				"complete": function()
				{
				},
				"success": function(data2)
				{
					data = JSON.parse(data2);
					console.log('data2 ', data2);
					console.log('data ', data);
					if (data.success)
					{
						if(data.hasOwnProperty("ga4data"))
						{
							addToCart(data.ga4data);
						}
						location = data.url;
					}
					else
					{
						unBlockForm(form);
						var errors = "Please contact customer support";
						if (data.hasOwnProperty("errors"))
						{
							errors = data.errors.join("</li><li>");
						}

						var message = "<div class='errorSummary'><ul><li>" + errors + "</li></ul></div>";
						toastr['error'](message, 'Failed to process!', {
							closeButton: true,
							tapToDismiss: false,
							timeout: 500000
						});
					}
				},
				error: function (jqXHR, textStatus, errorThrown) {
					unBlockForm(form);
					toastr['error'](message, 'Failed to process!', {
							closeButton: true,
							tapToDismiss: false,
							timeout: 500000
						});
                 }
			});
		}
		return false;
	}

	function validateRoute()
	{

		var reqFields = <?= CJavaScript::encode($requiredFields) ?>;
		var success = true;
		$.each(reqFields, function(key, value)
		{
			var PAWObject = AWObject.get(value);
			//	alert(JSON.parse(PAWObject));
			var PAWVal = PAWObject.model.id;
			if (PAWObject && !PAWObject.hasData())
			{
				success = false;
				alert("Pickup and Drop locations are mandatory");
				PAWObject.focus();
			}
			else if ($('#' + PAWVal).val() == '')
			{
				success = false;
				alert("Please enter proper address");
				PAWObject.focus();
			}

			return success;
		});
		return success;
	}

	function selectAddress(index, city)
	{
		var href2 = "<?php echo Yii::app()->createUrl('booking/existAddress') ?>";
		$.ajax({
			"url": href2,
			"data": {city: city, field: index},
			"type": "GET",
			"dataType": "html",
			"success": function(data)
			{
				$('#bkCommonModelBody').html(data);
				$('#bkCommonModel').modal('show');
			}
		});
	}

	$('.dropAddress').click(function()
	{

		var href2 = "<?php echo Yii::app()->createUrl('booking/existAddress') ?>";
		$.ajax({
			"url": href2,
			"data": {type: 'drop', lead: '<?= $bookingTemp->bkg_id ?>'},
			"type": "GET",
			"dataType": "html",
			"success": function(data)
			{
				$('.modal').modal('hide');
				$('#bkCommonModel').removeClass('fade');
				$('#bkCommonModel').css("display", "block");
				$('#bkCommonModelBody').html(data);
				$('#bkCommonModel').modal('show');
			}
		});
		return false;
	});

	function validateAddressDayrental(widgetId, fieldId)
	{
        
       
        
		//alert(widgetId +" SSSTEST "+fieldId);
		var bkgTypeArry = [9, 10, 11];
		var bkgType = <?php echo $model->bkg_booking_type; ?>;
		if (bkgTypeArry.includes(bkgType))
		{
            debugger;
			address = $('.' + fieldId).text();
			addressObj = $('#' + fieldId).val();

			$('.BookingRoute_0_to_place').text(address);
			$('#BookingRoute_0_to_place').val(addressObj);
			$(".BookingRoute_0_to_place").unbind("click");
		}
	}

</script>





