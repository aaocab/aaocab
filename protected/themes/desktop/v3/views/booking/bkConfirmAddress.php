<?php
$cs					 = Yii::app()->getClientScript();
$jsVer				 = Yii::app()->params['siteJSVersion'];
$cs->registerScriptFile("/js/gozo/city.js?v=$jsVer");
$cs->registerScriptFile('/js/gozo/geocodeMarker.js?v=' . $jsVer);
$cs->registerScriptFile('/js/gozo/placeAutoComplete.js?v=' . $jsVer);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/js/gozo/hyperLocation.js?v=$jsVer");
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/jquery.disableAutoFill.min.js');

$brtRoutes	 = $model->bookingRoutes;
$diff		 = floor((strtotime($model->bkg_pickup_date) - time()) / 3600);
?>
<div class="card-body editAddress" style="display: <?= $existdisplay; ?>" >


	<?php
	/* this part is for edit address */
	$form		 = $this->beginWidget('CActiveForm', array(
		'id'					 => 'bookingsavedaddress',
		'action'				 => 'booking/address',
		'enableClientValidation' => true,
		'clientOptions'			 => array(
			'validateOnSubmit'	 => true,
			'errorCssClass'		 => 'has-error'
		),
		//'enableAjaxValidation'	 => false,
		'errorMessageCssClass'	 => 'help-block',
		'htmlOptions'			 => array(
			'class'		 => 'form-horizontal',
			'enctype'	 => 'multipart/form-data'
		//'onsubmit'	 => 'return saveAddressesByRoutes(this);'
		),
	));
	/* @var $form CActiveForm */
	?>
	<?= $form->hiddenField($model, "bkg_id"); ?>


	<ul class="timeline mb-0" >
		<?php
		$cnt = count($model->bookingRoutes) - 1;

		$routeDetails = "";
		foreach ($model->bookingRoutes as $key => $v)
		{
			if ($model->bkg_agent_id == Config::get('Kayak.partner.id') && in_array($model->bkg_booking_type, [2, 3]) && ($v->brtFromCity->cty_garage_address == $v->brt_from_location || $v->brt_from_location == '' || $v->brtFromCity->cty_display_name == $v->brt_from_location || $v->brtFromCity->cty_name == $v->brt_from_location))
			{
				continue;
			}
			$routeDetails .= "<li class='timeline-item active pb5' >" . Cities::model()->getName($v->brt_from_city_id) . "--" . $v->brt_from_location . '</li> ';
		}
		$routeDetails .= "<li class='timeline-item active pb5' >" . Cities::model()->getName($model->bookingRoutes[$cnt]->brt_to_city_id) . "--" . $v->brt_to_location . '</li> ';

		echo $routeDetails;
		?>

	</ul>

<?php if (!in_array($model->bkg_status, [1, 9, 10, 6, 7]) && $model->bkg_booking_type != 15)
{ ?>
		<div class="col-12 text-right">
			<a href="#" class="color-black p5 editAddress"><img src="/images/bx-edit-alt.svg" alt="img" width="20" height="20" onclick="editAddress();" class="mr-1"></a>
		</div>
	<?php } ?>
	<!-- comment -->
	<?php $this->endWidget(); ?>
</div>
<?php
$form = $this->beginWidget('CActiveForm', array(
	'id'					 => 'frmBkgAddress',
	'action'				 => 'booking/address',
	'enableClientValidation' => true,
	'clientOptions'			 => array(
		'validateOnSubmit'	 => true,
		'errorCssClass'		 => 'has-error'
	),
	//'enableAjaxValidation'	 => false,
	'errorMessageCssClass'	 => 'help-block',
	'htmlOptions'			 => array(
		'class'		 => 'form-horizontal',
		'enctype'	 => 'multipart/form-data',
	//'onsubmit'	 => 'return checkAddress();'
	),
		));
/* @var $form CActiveForm */
?>
<?= $form->hiddenField($model, "bkg_id"); ?>
<input type="hidden" id="hash" name="hash"  value="<?php echo $hash ?>">
<div class="row putAddress"  style="display: <?= $adddisplay ?>" >


	<div class="col-12 mt-1">
		<div class="row">

			<div class="col-12 mb20">
				<div class="pl15 pr15">

					<?php
//echo "<pre>";
//print_r($brtRoutes);



					$i = 0;
					foreach ($brtRoutes as $key => $brtRoute)
					{


						$fplace = new \Stub\common\Location();
						$fplace->setData(null, null, $brtRoute->brt_from_location, $brtRoute->brt_from_latitude, $brtRoute->brt_from_longitude, null);

						$tplace	 = new \Stub\common\Location();
						$tplace->setData(null, null, $brtRoute->brt_to_location, $brtRoute->brt_to_latitude, $brtRoute->brt_to_longitude, null);
						$keyNew	 = $brtRoute->brt_id;
						?>
						<div class="row">
							<?php
							if ($i == 0)
							{
								?>
								<div class="col-12 col-xl-6 mb-1">
									<p class="weight500 mb5" for="iconLeft">
										Your pickup address in <?= $brtRoute->brtFromCity->cty_display_name ?>
									</p>
									<div class="pick mb10"><input type="hidden" value="<?= $fplace->address ?>" name="<?= $brtRoute->brt_id . '_from_place' ?>" id="<?= $brtRoute->brt_id . '_from_place_old' ?>">

										<?php
										if ($model->bkg_agent_id == Config::get('Mobisign.partner.id'))
										{
											goto skippickaddress;
										}
										if (!in_array($model->bkg_status, [1, 9, 10]))
										{
											if ($brtRoute->brtFromCity->cty_is_airport != 1 && !in_array($model->bkg_booking_type, [12, 15]))
											{
												$addressVal			 = CJSON::encode($fplace);
												$isLaterChkBxChceked = false;
												$fadrs				 = $fplace->address;
												if ($fplace->address != '')
												{
													$arrAdrs = explode(",", $fplace->address);
													$fadrs	 = $arrAdrs[0];
												}
												if (($brtRoute->brtFromCity->cty_name == $fplace->address || $brtRoute->brtFromCity->cty_name == $fadrs) && $fplace->address != '')
												{
													$addressVal = '';
												}
												if ($addressVal == '')
												{
													$isLaterChkBxChceked = true;
												}
												$laterChkBox = true;
												if ($brtRoute->brtFromCity->cty_is_poi == 1 || $diff < 24)
												{
													$laterChkBox = false;
												}
												$this->widget('application.widgets.SelectAddress', array(
													'model'			 => $brtRoute,
													"htmlOptions"	 => ["class" => "border border-light rounded p10 selectAddress  item"] + ['pickLater' => "pickup_later_chk_{$keyNew}"],
													'attribute'		 => "[{$brtRoute->brt_id}]from_place",
													"city"			 => "{$brtRoute->brt_from_city_id}",
													// "isAirport"=> ($brtRoute->brtFromCity->cty_is_airport == 1) ? true : false,
													"value"			 => $addressVal, //'{"coordinates":{"latitude":27.1334356,"longitude":78.0515108},"address":"Ekta Police Chauki, Shamsabad Road, Chamroli Mod, Janpad, Vishwakarmapuram Colony, Agra, Uttar Pradesh, India","place_id":"ChIJaRQX-2JxdDkRKvm6ulDC6H0"}',
													"modalId"		 => "myAddressModal",
													"brtId"			 => "$brtRoute->brt_id"
												));
												if ($laterChkBox)
												{
													echo $form->checkBox($model, "pickup_later_chk", ['class' => 'mt10', 'id' => "pickup_later_chk_{$keyNew}", 'onclick' => "removeAdrsPickup('{$keyNew}',true)", "checked" => $isLaterChkBxChceked]);
													?>

													<label for="Booking_pickup_later_chk" style="cursor: pointer" class="ml5" onclick="removeAdrsPickup(<?= $keyNew ?>, false)">I will provide later</label>
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
													else if (in_array($model->bkg_booking_type, [4, 12, 15]))
													{
														echo $brtRoute->brt_from_location;
													}
													?></div>
											<?php
											}
										}
										skippickaddress:
										if ($model->bkg_agent_id == Config::get('Mobisign.partner.id'))
										{
											?>
											<div class="border border-light rounded p10 selectAddress item">
									<?php echo $brtRoute->brt_from_location; ?>
											</div>
								<?php } ?>
									</div>

								</div>
							<?php } ?>
							<?php
							$adrsInputBox = '';
							if (in_array($model->bkg_booking_type, [2, 3]) && $model->bkg_agent_id == Config::get('Kayak.partner.id'))
							{
								if (($brtRoute->brt_from_city_id == $brtRoute->brt_to_city_id && $brtRoutes[$key - 1] == null) || ($brtRoute->brt_to_city_id == $brtRoutes[$key + 1]->brt_to_city_id && $brtRoutes[$key + 1]->brt_to_city_id != null))
								{
									$adrsInputBox = 'hide';
								}
							}
							?>
							<div class="col-12 col-xl-6 mb-1 <?= $adrsInputBox ?>">
								<p class="weight500 mb5" for="iconLeft">
									Your drop address in <?= $brtRoute->brtToCity->cty_display_name ?></p>
								<div class="drop mb10"  >
									<input type="hidden" value="<?= $tplace->address ?>" name="<?= $brtRoute->brt_id . '_to_place' ?>" id="<?= $brtRoute->brt_id . '_to_place_old' ?>">
									<?php
									if ($model->bkg_agent_id == Config::get('Mobisign.partner.id'))
									{
										goto skipdropaddress;
									}
									if (!in_array($model->bkg_status, [1, 9, 10]))
									{
										if ($brtRoute->brtToCity->cty_is_airport != 1 && !in_array($model->bkg_booking_type, [12, 15]))
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
											$isLaterChkBxChceked1 = false;
											if ($toAddressVal == '')
											{
												$isLaterChkBxChceked1 = true;
											}
											$laterChkBox = true;
											if ($brtRoute->brtToCity->cty_is_poi == 1 || $diff < 24)
											{
												$laterChkBox = false;
											}
											$this->widget('application.widgets.SelectAddress', array(
												'model'			 => $brtRoute,
												"htmlOptions"	 => ["class" => "border border-light rounded p10 selectAddress item"] + ['pickLater' => "drop_later_chk_{$keyNew}"],
												'attribute'		 => "[{$brtRoute->brt_id}]to_place",
												"city"			 => "{$brtRoute->brt_to_city_id}",
												"value"			 => $toAddressVal,
												"modalId"		 => "myAddressModal",
												//"isAirport"		 => ($brtRoute->brtToCity->cty_is_airport == 1) ? true : false,
												"brtId"			 => "$brtRoute->brt_id"
											));
											if ($laterChkBox)
											{
												echo $form->checkBox($model, "drop_later_chk", ['class' => 'mt10', 'id' => "drop_later_chk_{$keyNew}", 'onclick' => "removeAdrsDrop('{$keyNew}',true)", "checked" => $isLaterChkBxChceked1]);
												?>

												<label for="Booking_drop_later_chk" style="cursor: pointer" class="ml5" onclick="removeAdrsDrop(<?= $keyNew ?>, false)">I will provide later</label>
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
											else if (in_array($model->bkg_booking_type, [4, 12, 15]))
											{
												echo $brtRoute->brt_to_location;
											}
											?></div>
		<?php
		}
	}
	skipdropaddress:
	if ($model->bkg_agent_id == Config::get('Mobisign.partner.id'))
	{
		?>
										<div class="border border-light rounded p10 selectAddress item">
		<?php echo $brtRoute->brt_to_location; ?>
										</div>
	<?php } ?>
								</div>


							</div>
						</div>
	<?php
	$i++;
}
?>

				</div>
			</div>



		</div>
		<!--style="display: none"-->
		<div class="row m0 text-right">
			<div class="col-12">

				<!--<a href="#" class="btn btn-primary pl-5 pr-5 mb10 saveAddress" >Save</a>-->
				<input type="button"value="Save" onclick="checkAddress();" style="display: none"class="btn btn-primary pl-5 pr-5 mb10 saveAddress">


			</div></div>


		<!--<div class="col-12 text-right">
									<a href="#" class="color-black p5 editAddress"><img src="/images/bx-edit-alt2.svg" alt="img" width="22" height="22" class="mr-1"></a>
								</div>-->
		<input type="hidden" name="pageID" id="pageID" value="<?= $pageID ?>">
		<input type="hidden" name="rdata" value="<? //= $this->pageRequest->getEncrptedData()   ?>">
	</div></div>

<?php $this->endWidget(); ?>
<script>

	$(document).ready(function ()
	{
		brtRouteId = '<?= $brtRoutes[0]->brt_id ?>';
		var bkgType = '<?= $model->bkg_booking_type ?>';
		if (bkgType == 9 || bkgType == 10 || bkgType == 11)
		{
			$('.BookingRoute_' + brtRouteId + '_to_place').css('pointer-events', 'none');
			$('#drop_later_chk_' + brtRouteId).css('pointer-events', 'none');
			$("label[for='Booking_drop_later_chk']").css('pointer-events', 'none');
		}
	});

	function editAddress()
	{
		var booking_id = '<?= $model->bkg_id ?>';
		param = {bkgid: booking_id};
		$(".putAddress").show();
		$(".editAddress").hide();
	}

	function getAddressData() {
		return $('#frmBkgAddress').serializeArray();
	}


	function checkAddress()
	{

		var href = '<?php echo $this->getURL(['booking/checkAddress', "id" => $model->bkg_id]) ?>';
		var csrf = $('#bookingsplrequest').find("INPUT[name=YII_CSRF_TOKEN]").val();

		var form = $(".cntPayment");
		jQuery.ajax({type: 'POST',
			url: href,
			data: {addData: getAddressData(), YII_CSRF_TOKEN: csrf},
			dataType: "json",
			"beforeSend": function ()
			{
				blockForm(form);
			},
			"complete": function ()
			{
				unBlockForm(form);
			},
			success: function (data)
			{   // debugger;
				// && data.data.extraCharge>0 && data.data.extrakm>0
				$('.saveAddress').hide();
				if (data.data.isBlocked == 'blocked')
				{
					toastr['error']('No cabs available from this location', {
						closeButton: true,
						tapToDismiss: false,
						timeout: 500000
					});
				} else
				{
					if (data.success && data.data.extraCharge > 0 && data.data.extrakm > 0)
					{
						$("#ExtraKm").show();
						$("#ExtraCharges").show();


						bootbox.confirm({
							title: "Fare changed. Do you want to continue?",
							message: data.data.fare,
							className: "important-notice",
							callback: function (result) {
								if (result) {
									saveRequest(1);
								} else {
									$('.saveAddress').show();
								}
							}
						});
					}

					if (data.success && data.data.extraCharge == 0)
					{

						saveRequest(1);
					}
				}
			},
			error: function (xhr)
			{
				unBlockForm(form);
			}
		});
	}

	function removeAdrsPickup(key, isChkbox = true)
	{
		$('input[name="Booking[pickup_later_chk]"]').val(0).change();
		let laterchkbox = $('#pickup_later_chk_' + key);
		var bkgType = '<?= $model->bkg_booking_type ?>';

		if (isChkbox)
		{
			if (laterchkbox.is(':checked'))
			{
				let adrsElement = 'BookingRoute_' + key + '_from_place';
				$('input[name="Booking[pickup_later_chk]"]').val(1).change();
				$('#' + adrsElement).val('');
				$('.' + adrsElement).html('&nbsp;');
				$('#' + key + '_from_place_old').val('');
				if (bkgType == 9 || bkgType == 10 || bkgType == 11)
				{
					$('#drop_later_chk_' + key).prop('checked', true);
					$('#drop_later_chk_' + key).css('pointer-events', 'none');
					$("label[for='Booking_drop_later_chk']").css('pointer-events', 'none');
					removeAdrsDrop(key, true);
				}
			}
		} else
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
		$('input[name="Booking[drop_later_chk]"]').val(0).change();
		let laterchkbox = $('#drop_later_chk_' + key);
		if (isChkbox)
		{
			if (laterchkbox.is(':checked'))
			{
				var adrsElement = 'BookingRoute_' + key + '_to_place';
				$('input[name="Booking[drop_later_chk]"]').val(1).change();
				$('#' + adrsElement).val('');
				$('.' + adrsElement).html('&nbsp;');
				$('#' + key + '_to_place_old').val('');
			}
		} else
		{
			laterchkbox.click();
	}
	}

	function validateAddressDayrental(widgetId, fieldId) {
		//alert(widgetId +" SSSTEST "+fieldId);
		var bkgTypeArry = [9, 10, 11];
		var bkgType = <?php echo $model->bkg_booking_type; ?>;
		if (bkgTypeArry.includes(bkgType))
		{
			address = $('.' + fieldId).text();
			addressObj = $('#' + fieldId).val();
			brtRouteArray = fieldId.split("_");
			$('.BookingRoute_' + brtRouteArray[1] + '_to_place').text(address);
			$('#BookingRoute_' + brtRouteArray[1] + '_to_place').val(addressObj);
			$('.BookingRoute_' + brtRouteArray[1] + '_to_place').unbind("click");
		}
	}

</script>

