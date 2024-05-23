<?php
$cs		 = Yii::app()->getClientScript();
$jsVer	 = Yii::app()->params['siteJSVersion'];
$cs->registerScriptFile("/js/gozo/city.js?v=$jsVer");
$cs->registerScriptFile('/js/gozo/geocodeMarker.js?v=' . $jsVer);
$cs->registerScriptFile('/js/gozo/placeAutoComplete.js?v=' . $jsVer);
$cs->registerScriptFile(Yii::app()->baseUrl . "/js/gozo/hyperLocation.js?v=$jsVer");
$cs->registerScriptFile(Yii::app()->baseUrl . '/js/jquery.disableAutoFill.min.js');

$brtRoutes		 = $model->bookingRoutes;
$addressLabel	 = ($model->bkg_booking_type == 4) ? 'Location' : 'Address';

$form		 = $this->beginWidget('CActiveForm', array(
	'id'					 => 'customerinfo',
	'action'				 => '',
	'enableClientValidation' => true,
	'clientOptions'			 => array(
		'validateOnSubmit'	 => true,
		'errorCssClass'		 => 'has-error'
	),
	//'enableAjaxValidation'	 => false,
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
<div class="row">
	<div class="col-12 text-center">
		<p class=""><img src="/images/img-2022/location.svg" width="70" alt=""></p>
	</div>
	<div class="col-12 col-lg-8 offset-lg-2 mt-1">

		<div class=" bg-white-box">


			<?php
			$brtRoute	 = $brtRoutes[0];
			?>
			<div class="row">

				<div class="col-12 col-xl-6 offset-xl-3 add-widget mb-1">
					<p class="weight600 mb5" for="iconLeft">
						Your drop address in <?= $brtRoute->brtToCity->cty_display_name ?></p>
					<?php
					if ($brtRoute->brtToCity->cty_is_airport != 1 && !in_array($model->bkg_booking_type, [4, 12]))
					{
						$this->widget('application.widgets.SelectAddress', array(
							'model'			 => $brtRoute,
							"htmlOptions"	 => ["class" => "border border-light rounded p10 selectAddress item"],
							'attribute'		 => "[{$brtRoute->brt_id}]to_place",
							"city"			 => "{$brtRoute->brt_to_city_id}",
							"modalId"		 => "myAddressModal"
						));
					}
					else
					{
						?>
						<div class="border border-light rounded p10 selectAddress item">
							<?php
							if ($brtRoute->brtToCity->cty_is_airport == 1 || $brtRoute->brtToCity->cty_is_poi == 1)
							{
								echo $brtRoute->brtToCity->cty_full_name;
							}
							else if (in_array($model->bkg_booking_type, [4, 12]))
							{
								echo $brtRoute->brt_to_location;
							}
							?></div>
						<?php
					}
					?>
				</div>
			</div>


		</div>
		<div class="row">
			<div class="col-xl-12 text-center  mb-4">
				<input type="hidden" name="pageID" id="pageID" value="">
				<input type="hidden" name="rdata" value="">
				<button type="submit" class="btn btn-primary pl-5 pr-5 text-uppercase">Next</button>
			</div>
		</div>
	</div></div>
<?php $this->endWidget(); ?>
<script>
	function saveAddressesByRoutes()
	{
		var success = validateRoute();
		var form = $("form#customerinfo");
		if (success)
		{
			$.ajax({
				"type": "POST",
				"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/gnowDropAddress')) ?>",
				"data": form.serialize(),
				"beforeSend": function()
				{
					blockForm(form);
				},
				"complete": function()
				{
					unBlockForm(form);
				},
				"success": function(data2)
				{
					data = JSON.parse(data2);
					if (data.success)
					{
						location = data.url;
					}
					else
					{
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
</script>