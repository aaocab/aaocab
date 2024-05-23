<?php
$api = Config::getGoogleApiKey('browserapikey');
Yii::app()->clientScript->registerScriptFile("//maps.googleapis.com/maps/api/js?key={$api}&libraries=places&");
$version = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/js/gozo/hyperLocation.js?v=$version");
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/jquery.disableAutoFill.min.js');
?>
<?php
$bookingType	 = Booking::model()->getBookingType($model->bkg_booking_type);
$addressLabel	 = ($model->bkg_booking_type == 4) ? 'Location' : 'Address';
$brtRoutes		 = $model->bookingRoutes;

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


//echo '<pre>';print_r($model->bkgUserInfo);exit();



$i				 = 0;
$requiredFields	 = [];

$form = $this->beginWidget('CActiveForm', array(
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
<div class="container mb-2">
	<div class="row">
		<div class="col-12 text-center">
			<p class=""><img src="/images/img-2022/location.svg" width="70" alt=""></p>
		</div>
		<div class="col-12 col-lg-8 offset-lg-2 mt-1">
			<div class="row">
				<div class="col-12 mb20">
					<div class=" bg-white-box">
						<div class="row">
							<div class="col-12"><?php
foreach ($brtRoutes as $brtRoute)
{
	$ctr = ($brtRoute->brt_id > 0) ? $brtRoute->brt_id : $i;
	if ($i == 0)
	{
		$requiredFields[]	 = CHtml::activeId($brtRoute, "[" . ($ctr) . "]from_place");
		?>

										<div class="row">
											<div class="col-12">
												<p class="weight600 mb10">Please give us the traveller name</p>
											</div>
											<div class="col-12 col-xl-6">
												<p class="mb5"><small class="form-text">First Name*</small></p>
												<fieldset class="form-group position-relative">
		<?= $form->textField($model->bkgUserInfo, 'bkg_user_fname', ['placeholder' => "Enter first name", 'class' => 'form-control m0', 'id' => 'bkuserfname', 'required' => true]) ?>
												</fieldset>
											</div>
											<div class="col-12 col-xl-6 mb-1">

												<p class="mb5"><small class="form-text">Last Name*</small></p>
												<fieldset class="form-group position-relative">
		<?= $form->textField($model->bkgUserInfo, 'bkg_user_lname', ['placeholder' => "Enter last name", 'class' => 'form-control m0', 'id' => 'bkuserlname', 'required' => true]) ?>

												</fieldset>
											</div>

											<div class="col-12 col-xl-6">
												<p class="weight600 mb10" for="iconLeft">We need your pickup address* <? //= $brtRoute->brtFromCity->cty_display_name   ?></p>
												<fieldset class="form-group position-relative mb0">
		<?php
		$requiredFields[]	 = CHtml::activeId($brtRoute, "[$ctr]from_place");
		$this->widget('application.widgets.PlaceAddress',
				['model' => $brtRoute, 'attribute' => "[$ctr]from_place", 'city' => $brtRoute->brt_from_city_id, "user" => $user]);
		?>
													<div class="form-control-position">
													</div>
												</fieldset>

											</div>

										</div>


		<?php
	}
	?>

									<?php
									$i++;
								}
								?>
								<input type="hidden" value="<? echo (($model->bkgFromCity->cty_garage_address == $model->bkg_pickup_address || $model->bkg_pickup_address == '' || $model->bkgToCity->cty_garage_address == $model->bkg_drop_address || $model->bkg_drop_address == '' ) && $model->bkg_booking_type != 4) ? '0' : '1' ?>" class="isPickupAdrsCls" name="isPickupAdrsCls">
							</div>
							</fieldset>
						</div>

					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-xl-12 text-center mt-5 mb-4">
					<button type="button" class="btn btn-primary pl-5 pr-5 text-uppercase btnsave">Next</button>
				</div>
			</div>
		</div>
<?php $this->endWidget(); ?>

		<script type="text/javascript">

			$(".btnsave").click(function()
			{
				if ($.trim($("#bkuserfname").val()) == "")
				{
					alert("First Name is mandatory");
					return false;
				}
				if ($.trim($("#bkuserlname").val()) == "")
				{
					alert("Last Name is mandatory");
					return false;
				}
				saveAddressesByRoutes();
			});
			function saveAddressesByRoutes()
			{
				var success = validateRoute();
				//var success = validateAddresses();
				if (success)
				{
					$.ajax({
						"type": "POST",
						"url": '<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/saveGnowPickAddress')) ?>',
						"data": $('#customerinfo').serialize(),
						"dataType": "html",
						"success": function(data2)
						{
							data1 = JSON.parse(data2);
							if (data1.success)
							{
								//location = "/booking/pay";
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
					var PAWVal = PAWObject.model.id;
					if (PAWObject && !PAWObject.hasData())
					{
						success = false;
						alert("Pickup location is mandatory");
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





