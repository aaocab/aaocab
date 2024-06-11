
<style type="text/css">
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; 
        margin: 0; 
    }
    input[type="number"] {
        -moz-appearance: textfield;
    }

    .pac-item >.pac-icon-marker{
        display: none !important;
    }
    .pac-item-query{
        padding-left: 3px;
    }
    .booking-info{
        /*font-variant: all-petite-caps;
        font-size: initial;*/
        color: #d46767;
    }
	.fb-btn{
		background: #3B5998;
		text-transform: uppercase;
		font-size: 14px;
		border: none;
		padding: 7px 8px;
		color: #fff;
		-webkit-border-radius: 2px;
		-moz-border-radius: 2px;
		border-radius: 2px;
		transition: all 0.5s ease-in-out 0s;
	}
	@media (max-width: 767px)
    {
		.modal-dialog{ margin-left: auto; margin-right: auto;}
	}
</style>
<?
//Yii::app()->clientScript->registerScriptFile('https://maps.googleapis.com/maps/api/js?key=AIzaSyDhybncyDc1ddM2qzn453XqYW8ZQDm7RW8&libraries=places&', CClientScript::POS_HEAD);

Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/js/jquery.mask1.min.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/jquery.disableAutoFill.min.js');

/* @var $model BookingTemp */
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');
$ccode					 = Countries::model()->getCodeList();
$additionalAddressInfo	 = "Building No./ Society Name";
$addressLabel			 = ($model->bkg_booking_type == 4) ? 'Location' : 'Address';
$infosource				 = BookingAddInfo::model()->getInfosource('user');
$ulmodel				 = new Users('login');
//$urmodel				 = new Users('insert');
$emailModel				 = new ContactEmail();
$locFrom				 = [];
$locTo					 = [];
$autocompleteFrom		 = 'txtpl';
$autocompleteTo			 = 'txtpl';
$locReadonly			 = ['readonly' => 'readonly'];
$scvVctId				 = SvcClassVhcCat::model()->getCatIdBySvcid($model->bkg_vehicle_type_id);
if ($model->bkg_transfer_type == 1 || $model->bkgFromCity->cty_is_poi == 1)
{
	$locFrom			 = $locReadonly;
	$autocompleteFrom	 = '';
}
if ($model->bkg_transfer_type == 2 || $model->bkgToCity->cty_is_poi == 1)
{
	$locTo			 = $locReadonly;
	$autocompleteTo	 = '';
}
$userdiv = 'block';
if (!Yii::app()->user->isGuest)
{
	$user	 = Yii::app()->user->loadUser();
	$userdiv = 'none';
	if ($model->bkg_booking_type == 1 && $scvVctId == VehicleCategory::SHARED_SEDAN_ECONOMIC)
	{
		if (Users::model()->getFbLogin($model->bkg_user_id, $model->bkg_user_email, $model->bkg_contact_no))
		{
			$userdiv = 'none';
		}
		else
		{
			$userdiv = 'block';
		}
	}
}
else
{
	if (Users::model()->getFbLogin($model->bkg_user_id, $model->bkg_user_email, $model->bkg_contact_no))
	{
		$userdiv = 'none';
	}
	else
	{
		$userdiv = 'block';
	}
	if ($model->bkg_user_id != '')
	{
		$email = ContactEmail::model()->getEmailByUserId($model->bkg_user_id);
	}
	else
	{
		$email = $model->bkg_user_email;
	}
	$emailModel->eml_email_address = $email;
//	$ulmodel->usr_email			 = $email;
//	$urmodel->usr_email			 = $email;
//	$urmodel->usr_mobile		 = $model->bkg_contact_no;
//	$urmodel->usr_country_code	 = $model->bkg_country_code;
//	$urmodel->usr_name			 = $model->bkg_user_name;
//	$urmodel->usr_lname			 = $model->bkg_user_lname;
}
/*
  @var $model Booking
 *  */
?>
<div class="row">

    <div id="userdiv" style="display :<?= $userdiv ?>" >
        <div class="col-xs-12 col-sm-11 col-md-4 pb0 mb20">
            <div class="main_time border-greenline book-panel">
                <div class="p10">
                    <div class="row">
                        <div class="col-xs-12 guest-login-panel">
							<?
							if ($scvVctId != VehicleCategory::SHARED_SEDAN_ECONOMIC)
							{
								?>
								<div class="heading-part mb10">Log In <i class="fa fa-sign-in"></i></div>
								<?
							}
							else
							{
								?>
								<h4 class="text-uppercase mt0 mb10 heading-part" style="text-align: center;">To create a flexxi share booking<i class="fa fa-sign-in" style="margin-left:5px;"></i></h4>
							<? } ?>
							<?
							if ($model->bkg_booking_type == 1 && $scvVctId == VehicleCategory::SHARED_SEDAN_ECONOMIC)
							{
								$isFlexxi = true;
							}
							else
							{
								$isFlexxi = false;
							}
							$this->renderPartial('partialsignin', ['model' => $ulmodel, 'emailModel' => $emailModel, 'isFlexxi' => $isFlexxi], false, true);
							?>
                        </div>
						<?
						if ($scvVctId != VehicleCategory::SHARED_SEDAN_ECONOMIC)
						{
							?>
							<div class="col-xs-12">
								<div class="row guest-panel">
									<div class="col-xs-12 text-center">
										<a class="btn fb-btn text-uppercase" style="width:75%;" onclick="signinWithFB()" role="button"><b><i class="fa fa-facebook" style="    margin-right: 8px;"></i>   Login with Facebook <i class="fa fa-caret-right"></i></b></a>
									</div>
								</div>
							</div>
						<? } ?>

                    </div>
                </div>
            </div>

        </div>
    </div>

	<?php
// $model=  Booking::model()->findByPk(25157);
//   $cabRate = Rate::model()->getCabDetailsbyCities($model->bkg_from_city_id, $model->bkg_to_city_id);
	$form	 = $this->beginWidget('booster.widgets.TbActiveForm', array(
		'id'					 => 'customerinfo',
		'enableClientValidation' => true,
		'clientOptions'			 => array(
			'validateOnSubmit'	 => true,
			'errorCssClass'		 => 'has-error',
			'afterValidate'		 => 'js:function(form,data,hasError){
				if(!hasError){
					$.ajax({
						"type":"POST",
						"dataType":"html",
						"url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('booking/additionaldetail')) . '",
						"data":form.serialize(),
                                                "beforeSend": function(){
                                                   ajaxindicatorstart("");
                                                },
                                                "complete": function(){
                                                    ajaxindicatorstop();                          
                                                },
						"success":function(data2){
							var data = "";
							var isJSON = false;
							try {
								data = JSON.parse(data2);
								isJSON = true;             
							} catch (e) {
							}
							if(!isJSON){                                                     
								 openTab(data2,5);
                                                                 trackPage(\'' . CHtml::normalizeUrl(Yii::app()->createUrl('booking/conview')) . '\');
                                                                 disableTab(5);                                     
							}
							else
							{
				
                                                             if(data.success){
                                                                 trackPage(\'' . CHtml::normalizeUrl(Yii::app()->createUrl('booking/conview')) . '\');
								 disableTab(5);  
                                                                 verifyContactInfo(data.bkg_id,data.hash);
                                                             }else{
															 			 if(data.isNotFb){
											                                   	alert("To create a booking, you need to login via Facebook");  
																				$(document).scrollTop(0);
										                                 }
																		 else if(data.isNotLogin){
																				alert("To create a booking, you need to login");  
																				$(document).scrollTop(0);
																		 }
																		var errors = data.errors;
																		msg =JSON.stringify(errors);
																	if(errors)
																	{
																	    var x = window.matchMedia("(max-width: 700px)");
																		if (x.matches) 
																		{
																			var result = JSON.parse(msg);
																			for (k in result) {
																			 bootbox.alert({

																					 message: result[k],
																					 class: "",
																					 callback: function () 
																					 {
																					 }
																				 })
																				 return false;
																			 }
																		}
																	}
																		settings=form.data(\'settings\');
																		$.each (settings.attributes, function (i) {
																				try{
																						$.fn.yiiactiveform.updateInput (settings.attributes[i], errors, form);
																				}catch(e)
																				{
																				}
                                                                    });
                                                                    $.fn.yiiactiveform.updateSummary(form, errors);
                                                                }
							}             
						},
						error: function (xhr, ajaxOptions, thrownError) 
						{
								alert(xhr.status);
								alert(thrownError);
						}
					});
				}
			}'
		),
		'enableAjaxValidation'	 => false,
		'errorMessageCssClass'	 => 'help-block',
		'htmlOptions'			 => array(
			'class' => 'form-horizontal',
		//	'autocomplete' => 'disabled',
		),
	));
	/* @var $form TbActiveForm */
	?>
    <div class="col-xs-12 col-sm-11 col-md-8">
		<div class="panel panel-default main_time border-blueline">            
			<div class="panel-body padding_zero">   
				<div class="row">
					<div class="col-xs-12">
						<?= $form->errorSummary($model); ?>
						<?= CHtml::errorSummary($model); ?>
					</div>
				</div>
				<input autocomplete="off" name="hidden" type="text" style="display:none;">
				<input type="hidden" id="step4" name="step" value="4">
				<?= $form->hiddenField($model, 'bkg_user_id'); ?> 
				<?= $form->hiddenField($model, 'bkg_id', ['id' => 'bkg_id4']); ?>
				<?= $form->hiddenField($model, 'hash', ['id' => 'hash4', 'class' => 'clsHash']); ?>
				<?
//            $predata = $model->preData;
//            $dataa = CJSON::decode($predata);
//            var_dump($dataa);
				?>
				<div class="col-xs-12 col-sm-6 pr40">
					<div class="heading-part ml15 n mb10"><i class="fa fa-info-circle"></i> Traveller's Information</div>
					<div id="error_div" style="display: none" class="alert alert-block alert-danger"></div>
					<div class="row mt10">
						<div class="col-xs-12 pl0">
							<label for="inputEmail" class="control-label">Primary Passenger Name *:</label>
						</div>
						<div class="col-xs-12">
							<div class="row">
								<div class="col-xs-6 col-sm-6 pr20">
									<?= $form->textFieldGroup($model, 'bkg_user_name', array('label' => '', 'placeholder' => "First Name", 'class' => 'form-control')) ?>
								</div>
								<div class="col-xs-6 col-sm-6 pl20">
									<?= $form->textFieldGroup($model, 'bkg_user_lname', array('label' => '', 'placeholder' => "Last Name", 'class' => 'form-control')) ?>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 pl0">
							<label for="inputEmail" class="control-label">Primary Email address *:</label>
						</div>
						<div class="col-xs-12">
							<?= $form->emailFieldGroup($model, 'bkg_user_email', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Email Address", 'id' => CHtml::activeId($model, "bkg_user_email2")]), 'groupOptions' => ['class' => ''])) ?>                      
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 pl0">
							<label for="inputEmail" class="control-label">Primary Contact Number *:</label>
						</div>
						<div class="col-xs-12">
							<div class="row">   
								<div class="col-xs-5 isd-input">
									<?php
									echo $form->dropDownListGroup($model, 'bkg_country_code', array('label' => '', 'class' => 'form-control', 'widgetOptions' => array('data' => $ccode)))
									?>
								</div>
								<div class="col-xs-7 pr0">
									<?= $form->textField($model, 'bkg_contact_no', array('placeholder' => "Primary Mobile Number", 'class' => 'form-control')) ?>
									<?php echo $form->error($model, 'bkg_country_code'); ?>
									<?php echo $form->error($model, 'bkg_contact_no'); ?>
								</div>
							</div>
						</div>
					</div>
					<?php /*                        <div class="row">
					  <div class="col-xs-12 col-sm-5 pl0">
					  <label for="inputEmail" class="control-label">Alternate Contact Number :</label>
					  </div>
					  <div class="col-xs-12 col-sm-7">
					  <div class="row">
					  <div class="col-xs-5 isd-input">
					  <?php
					  echo $form->dropDownListGroup($model, 'bkg_alt_country_code', array('label' => '', 'class' => 'form-control', 'widgetOptions' => array('data' => $ccode)))
					  ?>
					  </div>
					  <div class="col-xs-7 pr0">
					  <?= $form->textField($model, 'bkg_alternate_contact', array('placeholder' => "Alternate Mobile Number", 'class' => 'form-control')) ?>
					  <?php echo $form->error($model, 'bkg_alt_country_code'); ?>
					  <?php echo $form->error($model, 'bkg_alternate_contact'); ?>
					  </div>
					  </div>
					  </div>
					  </div>
					 */ ?>
					<div class="row">
						<div class="col-xs-12 pl0">
							<label for="inputEmail" class="control-label">Send me booking confirmations by :</label>
						</div>
						<div class="col-xs-12">
							<div class="row">   
								<div class="col-xs-5 isd-input">
									<label class="checkbox-inline pt0 pr30">
										<?= $form->checkboxGroup($model, 'bkg_send_email', ['label' => 'Email']) ?>
									</label>
								</div>
								<div class="col-xs-7 pr0">
									<label class="checkbox-inline pt0 ">
										<?= $form->checkboxGroup($model, 'bkg_send_sms', ['label' => 'Phone']) ?>
									</label>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 journey-p">

					<?php /*
					  <div class="row">
					  <div class="col-xs-5 col-sm-5 pl0">

					  <label class="control-label pl0 ml0" for="BookingTemp_bkg_flight_no" id="flightlabeldivairport"  style="display: none">Enter Flight Number</label><br>

					  <label  class="control-label" id="picklabeloth">Airport Pickup?</label>
					  <label class="checkbox-inline pr30" style="padding-top: 11px;" id="chkAirport">
					  <?= $form->checkboxGroup($model, 'bkg_flight_chk', ['label' => '']) ?>
					  </label>
					  </div>
					  <div class="col-xs-7 col-sm-3 ">

					  <div id="othreq" style="display: none">
					  <div class="form-group" >
					  <label class="control-label pl0 ml0" for="BookingTemp_bkg_flight_no" id="flightlabeldivoth">Enter Flight Number <br></label>
					  <?= $form->textFieldGroup($model, 'bkg_flight_no', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Flight Number"]), 'groupOptions' => ['class' => 'm0'])) ?>
					  </div>
					  </div>
					  </div></div>
					 * 
					 */ ?>
					<div class="row">
						<div class="col-xs-12 col-md-8">
							<div class="heading-part ml15 n mb10 p0"><i class="fa fa-map-signs"></i> Journey Details:</div>
						</div> </div>
					<?
					$j		 = 0;
					$cntRt	 = sizeof($model->bookingRoutes);
					foreach ($model->bookingRoutes as $key => $brtRoute)
					{
						$fbounds	 = $brtRoute->brtFromCity->cty_bounds;
						$fboundArr	 = CJSON::decode($fbounds);
						$tbounds	 = $brtRoute->brtToCity->cty_bounds;
						$tboundArr	 = CJSON::decode($tbounds);
//                    $FLocLat = ($brtRoute->brt_from_latitude != '') ? $brtRoute->brt_from_latitude : $brtRoute->brtFromCity->cty_lat;
//                    $FLocLon = ($brtRoute->brt_from_longitude != '') ? $brtRoute->brt_from_longitude : $brtRoute->brtFromCity->cty_long;
//                    $TLocLat = ($brtRoute->brt_to_latitude != '') ? $brtRoute->brt_to_latitude : $brtRoute->brtToCity->cty_lat;
//                    $TLocLon = ($brtRoute->brt_to_longitude != '') ? $brtRoute->brt_to_longitude : $brtRoute->brtToCity->cty_long;


						if ($j == 0)
						{
							$locFrom = [];
							if ($brtRoute->brtFromCity->cty_is_airport == 1 || $brtRoute->brtFromCity->cty_is_poi == 1)
							{
								$locFrom = $locReadonly;
							}
							?>       

							<div class ="row mt10">
								<div class="col-xs-12">
									<div class = "row ">
										<div class="col-xs-12 ml20 mb10 n pt10">


										</div>
										<div class="col-xs-12 pl0 compact">
											<label for="pickup_address" class="control-label text-left">Pickup <?= $addressLabel ?> for <?= $brtRoute->brtFromCity->cty_name ?> *:</label>
											<input type="hidden" id="ctyLat0" class="" value="<?= $brtRoute->brtFromCity->cty_lat ?>">
											<input type="hidden" id="ctyLon0" class="" value="<?= $brtRoute->brtFromCity->cty_long ?> ">
											<input type="hidden" id="ctyELat0" class="" value="<?= round($fboundArr['northeast']['lat'], 6) ?>">
											<input type="hidden" id="ctyWLat0" class="" value="<?= round($fboundArr['southwest']['lat'], 6) ?>">
											<input type="hidden" id="ctyELng0" class="" value="<?= round($fboundArr['northeast']['lng'], 6) ?>">
											<input type="hidden" id="ctyWLng0" class="" value="<?= round($fboundArr['southwest']['lng'], 6) ?>">
											<input type="hidden" id="ctyRad0" class="hide" value="<?= $brtRoute->brtFromCity->cty_radius ?>">
											<? //= $form->numberFieldGroup($brtRoute, "[0]brt_from_pincode", array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Pincode (Optional)"]), 'groupOptions' => ['class' => 'm0'])) 
											?>
											<?
											if (( $scvVctId != VehicleCategory::SHARED_SEDAN_ECONOMIC && $brtRoute->brtFromCity->cty_is_airport != 1 && $brtRoute->brtFromCity->cty_is_poi != 1))
											{
												$chkLabelPickup = 'I will provide later, Assume city center now.';
												?>
												<?= $form->checkboxGroup($model, 'pickup_later_chk', ['label' => $chkLabelPickup, 'groupOptions' => ['style' => 'margin-bottom:5px;', "class" => "checkbox-inline"]]) ?>
												<?
											}
											else if ($model->bkg_flexxi_type == 2)
											{
												$model->flexxi_fs_chk = 1;

												$chkLabelPickup = 'I agree that I may not be picked up at this address and I may need to join the other riders at the common pickup point.';
												?>
												<?= $form->checkboxGroup($model, 'flexxi_fs_chk', ['label' => $chkLabelPickup, 'groupOptions' => ['style' => 'margin-bottom:5px;', "class" => "checkbox-inline"]]) ?>
												<?
											}
											?>
											<?= $form->hiddenField($brtRoute, "[0]brt_from_latitude", ['id' => 'locLat0']); ?>
											<?= $form->hiddenField($brtRoute, "[0]brt_from_longitude", ['id' => 'locLon0']); ?>
											<?= $form->hiddenField($brtRoute, "[0]brt_from_place_id", ['id' => 'locPlaceid0']); ?>
											<?= $form->hiddenField($brtRoute, "[0]brt_from_formatted_address", ['id' => 'locFAdd0']); ?>
											<?= $form->hiddenField($brtRoute, "[0]brt_from_city_is_airport", ['id' => 'city_is_airport0']); ?>
											<?= $form->hiddenField($brtRoute, "[0]brt_from_city_is_poi", ['id' => 'city_is_poi0']); ?>

											<? // }    ?>
										</div>
										<div class="col-xs-12 mb15 n pb0">
											<?
											$required	 = false;
											$readOnly	 = '';
//											if ($model->bkg_flexxi_type == 1)
//											{
//												$required = true;
//											}
//											else if (($model->bkg_flexxi_type == 2) || $brtRoute->brtFromCity->cty_is_airport == 1)
											if ($brtRoute->brtFromCity->cty_is_airport == 1 || $brtRoute->brtFromCity->cty_is_poi == 1)
											{
												$readOnly = 'readonly';
											}
											?>
											<?= $form->textAreaGroup($brtRoute, "[0]brt_from_location", array('label' => '', 'widgetOptions' => array('htmlOptions' => ['id' => "brt_location$key", 'class' => "form-control $autocompleteFrom", 'readonly' => $readOnly, 'required' => $required, "autocomplete" => "section-new", 'placeholder' => "Pickup Address  (Required)"] + $locFrom))) ?>
											<?php
											//echo
											$form->textFieldGroup($model, 'bkg_pickup_address');

											//	echo $form->error($model, 'bkg_pickup_address');
											?>
										</div>                    
									</div>
									<?php /*
									  if ($model->bkg_transfer_type != '2')
									  {
									  ?>

									  <div class = "row ">
									  <div class="col-xs-12 col-sm-5 pl0 ">
									  <label for="buildinInfo" class="control-label text-left"><?= $additionalAddressInfo ?>:</label>

									  </div>
									  <div class="col-xs-12 col-sm-4 mb0 pb0">
									  <?if($model->bkg_booking_type==6 && $model->bkg_flexxi_type==1){?>
									  <?= $form->textFieldGroup($brtRoute, "[0]brt_additional_from_address", array('label' => '', 'widgetOptions' => array('htmlOptions' => ['id' => "brt_additional$key", "autocomplete"=>"disabled", 'class' => "form-control",'required'=>"required", 'placeholder' => $additionalAddressInfo]))) ?>
									  <?}else if($model->bkg_booking_type==6 && $model->bkg_flexxi_type==2) {?>
									  <?= $form->textFieldGroup($brtRoute, "[0]brt_additional_from_address", array('label' => '', 'widgetOptions' => array('htmlOptions' => ['id' => "brt_additional$key", "autocomplete"=>"disabled", 'class' => "form-control",'readonly'=>"readonly", 'placeholder' => $additionalAddressInfo]))) ?>
									  <?}else if($model->bkg_booking_type==6 && $model->bkg_flexxi_type==2) {?>
									  <?= $form->textFieldGroup($brtRoute, "[0]brt_additional_from_address", array('label' => '', 'widgetOptions' => array('htmlOptions' => ['id' => "brt_additional$key", 'class' => "form-control",'readonly'=>"readonly", 'placeholder' => $additionalAddressInfo]))) ?>
									  <?} else {?>
									  <?= $form->textFieldGroup($brtRoute, "[0]brt_additional_from_address", array('label' => '', 'widgetOptions' => array('htmlOptions' => ['id' => "brt_additional$key", "autocomplete"=>"disabled", 'class' => "form-control", 'placeholder' => $additionalAddressInfo]))) ?>
									  <?}?>

									  </div>
									  </div>
									  <? } */ ?>
								</div>
							</div>
							<?
						}
						$key1	 = $key + 1;
						$j++;
						$opt	 = (($key + 1) == $cntRt) ? 'Required' : 'Optional';
						$optReq	 = (($key + 1) == $cntRt) ? ' *' : '';

						$locTo = [];
						if ($brtRoute->brtToCity->cty_is_airport == 1 || $brtRoute->brtToCity->cty_is_poi == 1)
						{
							$locTo = $locReadonly;
						}
						?>

						<div class="row mt10">
							<div class="col-xs-12">
								<div class="row">
									<div class="col-xs-12 ml20 mb15 n">
										<? //= $form->numberFieldGroup($brtRoute, "[$key]brt_to_pincode", array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Pincode (Optional)"]), 'groupOptions' => ['class' => 'm0']))         ?>

									</div>
									<div class="col-xs-12 pl0">
										<label for="pickup_address" class="control-label text-left">Drop <?= $addressLabel ?> for <?= $brtRoute->brtToCity->cty_name ?> <?= $optReq ?>:</label>
										<?
										if ($key1 == $cntRt && $scvVctId != VehicleCategory::SHARED_SEDAN_ECONOMIC && $brtRoute->brtToCity->cty_is_airport != 1 && $brtRoute->brtToCity->cty_is_poi != 1)
										{
											?>

											<?= $form->checkboxGroup($model, 'drop_later_chk', ['label' => 'I will provide later, Assume city center now.', 'groupOptions' => ["style" => "margin-bottom: 5px;", "class" => "checkbox-inline"]]) ?>

										<? } ?>
										<input type="hidden" id="ctyLat<?= $key + 1 ?>"  value="<?= $brtRoute->brtToCity->cty_lat ?>">
										<input type="hidden" id="ctyLon<?= $key + 1 ?>"  value="<?= $brtRoute->brtToCity->cty_long ?> ">
										<input type="hidden" id="ctyELat<?= $key + 1 ?>" value="<?= round($tboundArr['northeast']['lat'], 6) ?>">
										<input type="hidden" id="ctyWLat<?= $key + 1 ?>"  value="<?= round($tboundArr['southwest']['lat'], 6) ?>">
										<input type="hidden" id="ctyELng<?= $key + 1 ?>" value="<?= round($tboundArr['northeast']['lng'], 6) ?>">
										<input type="hidden" id="ctyWLng<?= $key + 1 ?>" value="<?= round($tboundArr['southwest']['lng'], 6) ?>">
										<input type="hidden" id="ctyRad<?= $key + 1 ?>"  value="<?= $brtRoute->brtToCity->cty_radius ?>">

										<? // if ($model->bkg_booking_type == '4') {  ?>
										<?= $form->hiddenField($brtRoute, "[$key1]brt_to_latitude", ['id' => "locLat$key1"]); ?>
										<?= $form->hiddenField($brtRoute, "[$key1]brt_to_longitude", ['id' => "locLon$key1"]); ?>
										<?= $form->hiddenField($brtRoute, "[$key1]brt_to_place_id", ['id' => "locPlaceid$key1"]); ?>
										<?= $form->hiddenField($brtRoute, "[$key1]brt_to_formatted_address", ['id' => "locFAdd$key1"]); ?>
										<?= $form->hiddenField($brtRoute, "[$key1]brt_to_city_is_airport", ['id' => "city_is_airport$key1"]); ?>
										<?= $form->hiddenField($brtRoute, "[$key1]brt_to_city_is_poi", ['id' => "city_is_poi$key1"]); ?>

										<? // }      ?>
									</div>

									<div class="col-xs-12">
										<?php
										$placeHolder = "Drop Address  ($opt)";
										$required	 = false;
										if ($model->bkg_booking_type == 1 && $scvVctId == VehicleCategory::SHARED_SEDAN_ECONOMIC)
										{
											$required = true;
										}
										?>
										<?= $form->textAreaGroup($brtRoute, "[$key1]brt_to_location", array('label' => '', 'widgetOptions' => array("groupOptions" => ["style" => "margin-bottom:0"], 'htmlOptions' => ['id' => "brt_location$key1", 'class' => "form-control $autocompleteTo", "autocomplete" => "new-password", 'required' => $required, 'placeholder' => $placeHolder] + $locTo))) ?>
										<?php
										if ((($key + 1) == $cntRt))
										{
											$form->textFieldGroup($model, 'bkg_drop_address');
											CHtml::error($model, 'bkg_drop_address');
//								echo $form->error($model, 'bkg_drop_address'); 
										}
										?>
									</div>

								</div>
								<? /*
								  if ($model->bkg_transfer_type != '2')
								  {
								  ?>
								  <div class="row">
								  <div class="col-xs-12 col-sm-5 pl0">
								  <label for="buildinInfo" class="control-label text-left"><?= $additionalAddressInfo ?>:</label>
								  </div>
								  <div class="col-xs-12 col-sm-4">
								  <?= $form->textFieldGroup($brtRoute, "[$key1]brt_additional_to_address", array('label' => '', 'widgetOptions' => array('htmlOptions' => ['id' => "brt_additional$key1", 'class' => "form-control", 'placeholder' => $additionalAddressInfo]))) ?>
								  </div>
								  </div>
								  <? } */ ?> 
							</div>
						</div>
						<?
					}
					?>
					<div class="row">
						<?php
						if ($model->bkg_flexxi_type == 2)
						{
							?>
							<div class="col-xs-12 p0 booking-info">
								Please provide drop addresses for your trip.
								You will be provided a common pickup address and pickup time where you will join other riders.
								<? /* /?>at Rs. <?= $model->bkg_rate_per_km_extra ?>/Km</b><?/ */ ?>
							</div>
							<?
						}
						else
						{
							?>
							<div class="col-xs-12 p0 booking-info  " id="cityCentreText" style="display: none">
								The currently quoted amount is quoted from city center to city center. 
								Exact addresses will help us provide updated more accurate fare quote for your booking. 
								Distance driven beyond included Kms is billed as applicable. <? /* /?>at Rs. <?= $model->bkg_rate_per_km_extra ?>/Km</b><?/ */ ?>
							</div>
						<?php } ?>
					</div>
				</div>

				<div class="col-xs-12" style="padding:20px;">
					<div class="text-center mt0">
						<?= CHtml::submitButton('NEXT', array('class' => 'btn next3-btn pl40 pr40', 'id' => 'nxtBtnAddDtls')); ?>
					</div>
				</div>
			</div> 
		</div>
	</div>
	<?php $this->endWidget(); ?>


</div>



<script type="text/javascript">
    booking_type = '<?= $model->bkg_booking_type ?>';
    transfer_type = '<?= $model->bkg_transfer_type ?>';
    $(document).ready(function ()
    {
        $(".txtpl").attr("autocomplete", "new-password");
        $('.bootbox').removeAttr('tabindex');
        disableTab(4);
        callbackLogin = 'fillUserform';
        setTimeout(function ()
        {
            $(".txtpl").attr("autocomplete", "disabled");

        }, 500);

        $('#<?= CHtml::activeId($model, "bkg_info_source") ?>').change(function ()
        {
            var infosource = $('#<?= CHtml::activeId($model, "bkg_info_source") ?>').val();
            extraAdditionalInfo(infosource);
        });
        if ('<?= $model->bkg_booking_type ?>' == 4)
        {
            $('#chkAirport').hide();
            $('#picklabeloth').hide();
            $('#flightlabeldivoth').hide();
            $('#othreq').show();
            $('#flightlabeldivairport').show();
        }

        if ($('#BookingTemp_bkg_country_code').val() != '91' && <?= $model->bkg_booking_type ?> == 1)
        {
            $('#<?= CHtml::activeId($model, "bkg_send_sms") ?>').prop("checked", false);
        }

        initializepl(booking_type, transfer_type);
    });


    $('.nav-tabs a[href="#menu3"] span').html('BY <?= SvcClassVhcCat::model()->getVctSvcList('string', '', $model->bkgSvcClassVhcCat->scv_vct_id) ?>');

    $('#<?= CHtml::activeId($model, "bkg_chk_others") ?>').change(function ()
    {
        if ($('#<?= CHtml::activeId($model, "bkg_chk_others") ?>').is(':checked'))
        {
            $("#othreq").show();
        } else
        {
            $("#othreq").hide();
        }
    });
    $('#<?= CHtml::activeId($model, "bkg_send_email") ?>').change(function ()
    {
        if ($('#<?= CHtml::activeId($model, "bkg_send_email") ?>').is(':checked') && $('#<?= CHtml::activeId($model, "bkg_user_email") ?>').val() == '')
        {

            var txt = "<h5>Please fix the following input errors:</h5><ul style='list-style:none'>";

            txt += "<li>Please provide email address.</li>";

            txt += "</ul>";
            $("#error_div").show();
            $("#error_div").html(txt);
        }

    });
    $('#<?= CHtml::activeId($model, "bkg_send_sms") ?>').change(function ()
    {
        if ($('#<?= CHtml::activeId($model, "bkg_send_sms") ?>').is(':checked') && $('#<?= CHtml::activeId($model, "bkg_contact_no") ?>').val() == '')
        {

            var txt = "<h5>Please fix the following input errors:</h5><ul style='list-style:none'>";
            txt += "<li>Please provide contact number.</li>";
            txt += "</ul>";
            $("#error_div").show();
            $("#error_div").html(txt);
        }
    });


    function extraAdditionalInfo(infosource)
    {
        $("#source_desc_show").addClass('hide');
        if (infosource == 'Friend')
        {
            $("#source_desc_show").removeClass('hide');
            $("#agent_show").addClass('hide');
            $('#<?= CHtml::activeId($model, "bkg_info_source_desc") ?>').attr('placeholder', "Friend's email please");
        } else if (infosource == 'Other')
        {
            $("#source_desc_show").removeClass('hide');
            $("#agent_show").addClass('hide');
            $('#<?= CHtml::activeId($model, "bkg_info_source_desc") ?>').attr('placeholder', "");
        }

    }


    function validateBothCheck()
    {
        if (!$('#<?= CHtml::activeId($model, "bkg_send_sms") ?>').is(':checked') && !$('#<?= CHtml::activeId($model, "bkg_send_email") ?>').is(':checked'))
        {

            var txt = "<h5>Please fix the following input errors:</h5><ul style='list-style:none'>";
            txt += "<li>Please check one of the communication media to send notifications.</li>";
            txt += "</ul>";
            $("#error_div").show();
            $("#error_div").html(txt);
        }

    }

    function saveBooking()
    {
        $('#customerinfo').submit();
        $.ajax({
            "type": "POST",
            "dataType": "json",
            "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/additionaldetail')) ?>",
            "data": $("#customerinfo").serialize(),
            "success": function (data2)
            {
                if (data2.success)
                {
                    trackPage('<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/finalbook')) ?>');
                    $("#final").show();
                    $("#error_div").hide();
                    openTab(data2.res, data2.type, 5);
                    refreshLasttab(data2.data);
                    // alert(data2);
                } else
                {
                    var errors = data2.errors;

                    var txt = "<h5>Please fix the following input errors:</h5><ul style='list-style:none'>";
                    $.each(errors, function (key, value)
                    {
                        txt += "<li>" + value + "</li>";
                    });
                    txt += "</ul>";
                    $("#error_div").show();
                    $("#error_div").html(txt);
                }
            }
        });
    }
    $('form').on('focus', 'input[type=number]', function (e)
    {
        $(this).on('mousewheel.disableScroll', function (e)
        {
            e.preventDefault()
        })
        $(this).on("keydown", function (event)
        {
            if (event.keyCode === 38 || event.keyCode === 40)
            {
                event.preventDefault();
            }
        });
    });
    $('form').on('blur', 'input[type=number]', function (e)
    {
        $(this).off('mousewheel.disableScroll');
        $(this).off('keydown');
    });

    function callSigninbox()
    {
        var uemail = $('#uemail').text();
        $href = '<?= Yii::app()->createUrl('users/partialsignin', ['callback' => 'refreshUserdata']) ?>';
        jQuery.ajax({type: 'GET', url: $href, "data": {"uemail": uemail},
            success: function (data)
            {
                signinbox = bootbox.dialog({
                    message: data,
                    title: 'Login',
                    onEscape: function ()
                    {
                        signinbox.modal('hide');
                    }
                });
            }
        });
    }

    var refreshUserdata = function ()
    {

        refreshNavbar();

        $('#<?= CHtml::activeId($model, "bkg_user_id") ?>').val($userid);
        if ($userid > 0)
        {
            $('#userdiv').hide();
            $('#welcomediv').show();

            fillUserdata();
            // signinbox.modal('hide');
            //  bootbox.hideAll();
            //signupbox.modal('hide');
        } else
        {
            $('#userdiv').show();
            $('#welcomediv').hide();

        }
    };

    function callSignupbox()
    {
        var uemail = $('#uemail').text();
        var ucode = $('#ucode').text();
        var ucontact = $('#ucontact').text();
        $href = '<?= Yii::app()->createUrl('users/partialsignup', ['callback' => 'refreshNavbar(data1)']) ?>';
        jQuery.ajax({type: 'GET', url: $href, "data": {"uemail": uemail, "ucode": ucode, "ucontact": ucontact},
            success: function (data)
            {
                signupbox = bootbox.dialog({
                    message: data,
                    title: 'Register',
                    onEscape: function ()
                    {
                        signupbox.modal('hide');
                    }
                });

            }
        });
    }

    $('#<?= CHtml::activeId($model, "bkg_flight_chk") ?>').change(function ()
    {
        if ($('#<?= CHtml::activeId($model, "bkg_flight_chk") ?>').is(':checked'))
        {
            $("#othreq").show();
<?
if ($model->bkg_booking_type == 1)
{
	?>
	            findPickupAirport();
<? } ?>
        } else
        {
            $("#othreq").hide();
<?
if ($model->bkg_booking_type == 1)
{
	?>
	            $('#brt_location<?= $key; ?>').val('').change();
<? } ?>
        }
    });

    function findPickupAirport()
    {
        var href1 = '<?= Yii::app()->createUrl('booking/pickupcityairport') ?>';
        jQuery.ajax({'type': 'GET', 'url': href1,
            'data': {'fromCity': '<?= $brtRoute->brtFromCity->cty_id ?>', 'maxDistance': 500, 'forAirport': false, 'queryStr': '<?= $brtRoute->brtFromCity->cty_name ?>', 'limit': 'LIMIT 0, 1'},
            success: function (data)
            {
                var airportArr = data.split(',');
                $('#brt_location<?= $key; ?>').val(airportArr[0]).change();
            }
        });
    }


    $('#<?= CHtml::activeId($model, "bkg_flight_no") ?>').mask('XXXX-XXXXXX', {
        translation: {
            'Z': {
                pattern: /[0-9]/, optional: true
            },
            'X': {
                pattern: /[0-9A-Za-z]/, optional: true
            },
        },
        placeholder: "__ __ __ ____",
        clearIfNotMatch: true
    }
    );


    function verifyContactInfo(bid, hsh)
    {

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

<?
if ($scvVctId != VehicleCategory::SHARED_SEDAN_ECONOMIC)
{
	?>

	    $('#nxtBtnAddDtls').click(function ()
	    {

	        if ($('#<?= CHtml::activeId($model, "pickup_later_chk") ?>').is(':checked') == false)
	        {
	            if ($('#brt_location0').val().trim().length == 0)
	            {
	                $('#brt_location0').css("border-color", "#a94442");
	            } else
	            {
	                $('#brt_location0').css("border", "1px solid #ccc");
	            }
	        } else
	        {
	            $('#brt_location0').css("border", "1px solid #ccc");
	        }
	        if ($('#<?= CHtml::activeId($model, "drop_later_chk") ?>').is(':checked') == false)
	        {
	            if ($('#brt_location<?= $key + 1 ?>').val().trim().length == 0)
	            {
	                $('#brt_location<?= $key + 1 ?>').css("border-color", "#a94442");
	            } else
	            {
	                $('#brt_location<?= $key + 1 ?>').css("border", "1px solid #ccc");
	            }
	        } else
	        {
	            $('#brt_location<?= $key + 1 ?>').css("border", "1px solid #ccc");
	        }
	    });

	    checkActivated = 0;
	    $('#<?= CHtml::activeId($model, "pickup_later_chk") ?>').click(function ()
	    {

	        if ($('#<?= CHtml::activeId($model, "pickup_later_chk") ?>').is(':checked'))
	        {
	            $('#brt_location0').attr('readonly', true);
	            $('#brt_location0').val('');
	            checkActivated++;

	        } else
	        {
	            $('#brt_location0').attr('readonly', false);
	            checkActivated--;
	        }
	        showCityCenterPara();
	    });
	    $('#<?= CHtml::activeId($model, "drop_later_chk") ?>').click(function ()
	    {

	        if ($('#<?= CHtml::activeId($model, "drop_later_chk") ?>').is(':checked'))
	        {
	            $('#brt_location<?= $key + 1 ?>').attr('readonly', true);
	            $('#brt_location<?= $key + 1 ?>').val('');
	            checkActivated++;
	        } else
	        {
	            $('#brt_location<?= $key + 1 ?>').attr('readonly', false);
	            checkActivated--;
	        }
	        showCityCenterPara();
	    });
	    function showCityCenterPara() {
	        if (checkActivated > 0) {


	            $('#cityCentreText').show();
	        } else {
	            $('#cityCentreText').hide();

	        }

	    }


<? } ?>

    $('#<?= CHtml::activeId($model, "bkg_country_code") ?>').change(function ()
    {
        if ($('#<?= CHtml::activeId($model, "bkg_country_code") ?>').val() != '91' && <?= $model->bkg_booking_type ?> == 1)
        {
            $('#<?= CHtml::activeId($model, "bkg_send_sms") ?>').prop("checked", false);
        } else
        {
            $('#<?= CHtml::activeId($model, "bkg_send_sms") ?>').prop("checked", true);
        }
    });

<?
if ($scvVctId != VehicleCategory::SHARED_SEDAN_ECONOMIC)
{
	?>
	    function signinWithFB()
	    {
	        var href = '<?= Yii::app()->createUrl('users/oauth', array('provider' => 'Facebook', 'isFlexxi' => true)); ?>';
	        var fbWindow = window.open(href, 'aaocab', 'left=20,top=20,width=500,height=500,toolbar=1,resizable=0');
	    }
<? } ?>

<?
if ($model->bkg_flexxi_type == 2)
{
	?>
	    $('#<?= CHtml::activeId($model, "pickup_later_chk") ?>').click(function ()
	    {
	        if ($('#<?= CHtml::activeId($model, "pickup_later_chk") ?>').is(':checked'))
	        {
	            $('#nxtBtnAddDtls').css('pointer-events', 'auto');
	        } else
	        {
	            $('#nxtBtnAddDtls').css('pointer-events', 'none');
	        }
	    });
<? } ?>
</script>
