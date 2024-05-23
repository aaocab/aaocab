
<style type="text/css">
.table {
  border-left: 1px solid #ccc;
  border-bottom: 1px solid #ccc;
}

.tr {
  display: flex;
}

.th, .td {
  border-top: 1px solid #ccc;
  border-right: 1px solid #ccc;
  padding: 4px 8px;
  flex: 1;
  overflow-wrap: break-word;
  word-wrap: break-word;
}
.bigCol
{
  max-width:65%;
}
.smallCol
{
  max-width:15%;		
}
.th {
  font-weight: bold;
}
.th[role="rowheader"] {
  background-color: #fff;
}
.th[role="columnheader"] {
  background-color: lightgrey;
}
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
	.autoMarkerLoc{
		font-size: 30px;
		color:red;
		cursor: pointer;
	}
</style>
<?
//Yii::app()->clientScript->registerScriptFile('https://maps.googleapis.com/maps/api/js?key=AIzaSyDhybncyDc1ddM2qzn453XqYW8ZQDm7RW8&libraries=places&', CClientScript::POS_HEAD);
//Yii::app()->clientScript->registerScriptFile('https://maps.googleapis.com/maps/api/js?key=AIzaSyDhybncyDc1ddM2qzn453XqYW8ZQDm7RW8&libraries=places&', CClientScript::POS_HEAD);




/* @var $model BookingTemp */
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');
$ccode					 = Countries::model()->getCodeList();
$additionalAddressInfo	 = "Building No./ Society Name";
$addressLabel			 = ($model->bkg_booking_type == 4) ? 'Location' : 'Address';
$infosource				 = BookingAddInfo::model()->getInfosource('user');
//$ulmodel				 = new Users('login');
//$urmodel				 = new Users('insert');
$locFrom				 = [];
$locTo					 = [];
$hyperLocationClass		 = 'txtHyperLocation';
$autocompleteFrom		 = $autocompleteTo			 = $hyperLocationClass;
$locReadonly			 = ['readonly' => 'readonly'];
$locMarkerTo = $locMarkerFrom    = '';
$scvVctId = SvcClassVhcCat::model()->getCatIdBySvcid($model->bkg_vehicle_type_id);
if ($model->bkgFromCity->cty_is_poi == 1)
{
	$locFrom			 = $locReadonly;
	$autocompleteFrom	 = '';
	$locMarkerFrom       = "hide";
}
if ($model->bkgToCity->cty_is_poi == 1)
{
	$locTo			 = $locReadonly;
	$autocompleteTo	 = '';
	$locMarkerTo     = "hide";
}
$userdiv = 'block';
if (!Yii::app()->user->isGuest)
{
	$user					 = Yii::app()->user->loadUser();
	if ($user->usr_contact_id)
	{
		$contactModel			 = Contact::model()->findByPk($user->usr_contact_id);
		$emailModel				 = ContactEmail::model()->findByConId($user->usr_contact_id);
		$phoneModel				 = ContactPhone::model()->findByConId($user->usr_contact_id);
	$model->bkg_user_id		 = Yii::app()->user->getId();
		$model->bkg_user_name	 = $contactModel->ctt_first_name;
		$model->bkg_user_lname	 = $contactModel->ctt_last_name;
	if ($model->bkg_cav_id > 0)
	{
			$model->bkg_user_email	 = $emailModel[0]->eml_email_address;
			$model->bkg_contact_no	 = $phoneModel[0]->phn_phone_no;
	}
	}
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
//	$ulmodel->usr_email			 = $model->bkg_user_email;
//	$urmodel->usr_email			 = $model->bkg_user_email;
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
	 <?php
			if(!empty($note))
			{
			?>
	<div class="col-xs-12 col-sm-12">
	<div class="panel panel-default main_time border-blueline">            
	<div class="panel-body padding_zero pb0"> 
	 
	    
		 <div class="heading-part ml15 n mb10"><i class="fa fa-info-circle ml15"></i> Special instructions & advisories that may affect your planned travel</div><br>
			 <div aria-describedby="caption" class="table" role="grid">
				 <div class="tr" role="row">
					 <div class="th smallCol" role="columnheader">
						 Place
					 </div>
					 <div class="th bigCol" role="columnheader">
						 Note
					 </div>
					 <div class="th smallCol" role="columnheader">
						 Valid From
					 </div>
					 <div class="th smallCol" role="columnheader">
						 Valid To
					 </div>
				 </div>
				 <?php
				 for ($i = 0; $i < count($note); $i++)
				 {
					 ?>  
					 <div class="tr" role="row">
						 <div class="th smallCol" role="rowheader">
                                                                        <?php if ($note[$i]['dnt_area_type'] == 1) { ?>
                                                                            <?= ($note[$i]['dnt_zone_name']) ?>
                                                                        <?php }?>
									<?php if ($note[$i]['dnt_area_type'] == 3)
									{ ?>
							  <?= ($note[$i]['cty_name']) ?>
									<?php }
									else if ($note[$i]['dnt_area_type'] == 2)
									{ ?>
							  <?= ($note[$i]['dnt_state_name']) ?>
									<?php }
									else if ($note[$i]['dnt_area_type'] == 0)
									{ ?>
							 <?="Applicable to all"?>
									<?php }
									else if ($note[$i]['dnt_area_type'] == 4)
									{ ?>
										   <?= Promos::$region[$note[$i]["dnt_area_id"]]?>
							<?php
							 }
							?>
							
						 </div>
						 <div class="td bigCol" role="gridcell">
							 <?= ($note[$i]['dnt_note']) ?>
						 </div>
						 <div class="td smallCol" role="gridcell">
							 <?= (DateTimeFormat::DateTimeToLocale($note[$i]['dnt_valid_from'])) ?>
						 </div>
						 <div class="td smallCol" role="gridcell">
							 <?= (DateTimeFormat::DateTimeToLocale($note[$i]['dnt_valid_to'])) ?>
						 </div>
					 </div>
				 <?php
				 }
				 ?>
			 </div>
	 </div>
	</div>
	</div>
	<?php
	  }
	?>
	<?php $this->renderPartial('bkLoginInfo', ['bkgModel' => $model]); ?>

	<?php
// $model=  Booking::model()->findByPk(25157);
//   $cabRate = Rate::model()->getCabDetailsbyCities($model->bkg_from_city_id, $model->bkg_to_city_id);
	$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
		'id'					 => 'customerinfo',
		'enableClientValidation' => true,
		'clientOptions'			 => array(
			'validateOnSubmit'	 => true,
			'errorCssClass'		 => 'has-error'
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
			<div class="panel-body padding_zero pb0">   
				<div class="row">
					<div class="col-xs-12">
						<? //= $form->errorSummary($model);   ?>
						<? //= CHtml::errorSummary($model); ?>
					</div>
				</div>

				<input type="hidden" id="step4" name="step" value="4">
				<?= $form->hiddenField($model, 'bkg_user_id'); ?> 
				<?= $form->hiddenField($model, 'bkg_id', ['id' => 'bkg_id4', 'class' => 'clsBkgID']); ?>
				<?= $form->hiddenField($model, 'hash', ['id' => 'hash4', 'class' => 'clsHash']); ?> 

				<?
				if ($model->bkg_booking_type == 7)
				{
					echo $form->hiddenField($model, 'bkg_shuttle_id');
				}
				if ($model->bkg_cav_id > 0)
				{
					echo $form->hiddenField($model, 'bkg_cav_id');
					echo $form->hiddenField($model, 'cavhash',['value'=> Yii::app()->shortHash->hash($model->bkg_cav_id)]);
				}
				?>
				
				<div class="col-xs-12 col-sm-6 pr40">
					<div class="heading-part ml15 n mb10"><i class="fa fa-info-circle"></i> Traveller's Information</div><br>
					<div id="error_div_info" style="display: none;white-space: pre-wrap" class="alert alert-block alert-danger"></div>
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
						<div class="col-xs-12 pl0">
							<?php
							$loggedinemail	 = "";
							$loggedinphone	 = "";
							if (!Yii::app()->user->isGuest)
							{
								$user = Yii::app()->user->loadUser();
								if ($user->usr_contact_id)
								{
									$contactModel	 = Contact::model()->findByPk($user->usr_contact_id);
									$emailModel		 = ContactEmail::model()->findByConId($user->usr_contact_id);
									$phoneModel		 = ContactPhone::model()->findByConId($user->usr_contact_id);
//									$loggedinemail	 = Yii::app()->user->loadUser()->usr_email;
//									$loggedinphone	 = Yii::app()->user->loadUser()->usr_mobile;
									$loggedinemail	 = $emailModel[0]->eml_email_address;
									$loggedinphone	 = $phoneModel[0]->phn_phone_no;
							}
							}

							$this->widget('ext.intlphoneinput.IntlPhoneInput', array(
								'model'					 => $model,
								'attribute'				 => 'bkg_contact_no',
								'codeAttribute'			 => 'bkg_country_code',
								'numberAttribute'		 => 'bkg_contact_no',
								'options'				 => array(// optional
									'separateDialCode'	 => true,
									'autoHideDialCode'	 => true,
									'initialCountry'	 => 'in'
								),
								'htmlOptions'			 => ['class' => 'form-control', 'id' => 'fullContactNumber1'],
								'localisedCountryNames'	 => false, // other public properties
							));
							?> 
							<?php echo $form->error($model, 'bkg_country_code'); ?>
							<?php echo $form->error($model, 'bkg_contact_no1'); ?>
						</div>
					</div>
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
				<div class="col-xs-12 col-sm-6 journey-p" id="addressAtPage4">
<!--					<div class="heading-part ml15 n mb10 p0"><i class="fa fa-map-signs"></i> Journey Details:</div>-->
					<?
					if ($model->bkg_booking_type == 7)
					{
						$model->bookingRoutes	 = $model->getRoutes();
						$brtRoute				 = $model->bookingRoutes[0];
						?>
						<div class ="row mt10">
							<div class="col-xs-12  ">
								<label for="pickup_address" class="control-label text-left">Pickup <?= $addressLabel ?> for <?= $brtRoute->brtFromCity->cty_name ?> *:</label>
							</div>
							<div class="col-xs-12 pb0">
								<div class="form-control" style="min-height: 60px;height: auto;background-color:#eaeaea"><?= $model->bkg_pickup_address ?></div>
							</div>                    

						</div>

						<div class="row pt20">

							<div class="col-xs-12 ">

								<label for="pickup_address" class="control-label text-left">Drop <?= $addressLabel ?> for <?= $brtRoute->brtToCity->cty_name ?> <?= $optReq ?>:</label>
							</div>
							<div class="col-xs-12 mb15 n pb0">
								<div class="form-control" style="min-height: 60px;height: auto;background-color:#eaeaea"><?= $model->bkg_drop_address ?></div>
							</div>

						</div>
						<?
						echo $form->hiddenField($brtRoute, "[0]brt_from_latitude", ['class' => 'locLat_0']);
						echo $form->hiddenField($brtRoute, "[0]brt_from_longitude", ['class' => 'locLon_0']);
						echo $form->hiddenField($brtRoute, "[0]brt_from_place_id", ['class' => 'locPlaceid_0']);
						echo $form->hiddenField($brtRoute, "[0]brt_from_formatted_address", ['class' => 'locFAdd_0']);

						echo $form->hiddenField($brtRoute, "[0]brt_to_latitude", ['class' => "locLat_1"]);
						echo $form->hiddenField($brtRoute, "[0]brt_to_longitude", ['class' => "locLon_1"]);
						echo $form->hiddenField($brtRoute, "[0]brt_to_place_id", ['class' => "locPlaceid_1"]);
						echo $form->hiddenField($brtRoute, "[0]brt_to_formatted_address", ['class' => "locFAdd_1"]);
					}
					else
					{
						$j = 0;
						if (!$model->bkg_cav_id)
						{
							$model->bookingRoutes = $model->getRoutes();
						}
						$cntRt = sizeof($model->bookingRoutes);
						foreach ($model->bookingRoutes as $key => $brtRoute)
						{
							$brtRoute->brt_from_location;
							$brtRoute->brtFromCity->cty_name;
							if ($j > 0)
							{
								goto skipPickupAddress;
							}
							$ctyLat[$key]		 = $brtRoute->brtFromCity->cty_lat;
							$ctyLon[$key]		 = $brtRoute->brtFromCity->cty_long;
							$bound[$key]		 = $brtRoute->brtFromCity->cty_bounds;
							$isCtyAirport[$key]	 = $brtRoute->brt_from_city_is_airport;
							$isCtyPoi[$key]		 = $brtRoute->brt_from_city_is_poi;
							
							if($brtRoute->brtFromCity->cty_is_airport != 1 && $brtRoute->brtFromCity->cty_is_poi != 1)
							{
								$brtRoute->brt_from_location = "";
							}

							$locFrom = [];
							if ($brtRoute->brtFromCity->cty_is_airport == 1 || $brtRoute->brtFromCity->cty_is_poi == 1)
							{
								$brtRoute->brt_from_latitude = $brtRoute->brtFromCity->cty_lat;
								$brtRoute->brt_from_longitude = $brtRoute->brtFromCity->cty_long;
								$brtRoute->brt_from_place_id  = $brtRoute->brtFromCity->cty_place_id;
								$brtRoute->brt_from_formatted_address  = $brtRoute->brtFromCity->cty_garage_address;
								$locFrom = $locReadonly;
								$locMarkerFrom      = 'hide';
							}
							?>       

<!--							<div class ="row mt10">
								<div class="col-xs-12 pl0 compact">
									<label for="pickup_address" class="control-label text-left">Pickup <?//= $addressLabel ?> for <?//= $brtRoute->brtFromCity->cty_name ?> *:</label>
									<input type="hidden" id="ctyRad0" class="hide" value="<?//= $brtRoute->brtFromCity->cty_radius ?>">
									<?
//									if (( $scvVctId != VehicleCategory::SHARED_SEDAN_ECONOMIC && $brtRoute->brtFromCity->cty_is_airport != 1 && $brtRoute->brtFromCity->cty_is_poi != 1))
//									{
//										$chkLabelPickup = 'I will provide later, Assume city center now.';
//										echo $form->checkboxGroup($model, 'pickup_later_chk', ['label' => $chkLabelPickup, 'groupOptions' => ['style' => 'margin-bottom:5px;', "class" => "checkbox-inline", "data-key" => $key]]);
//									}
//									else if ($model->bkg_flexxi_type == 2)
//									{
//										$model->flexxi_fs_chk	 = 1;
//										$chkLabelPickup			 = 'I agree that I may not be picked up at this address and I may need to join the other riders at the common pickup point.';
//										echo $form->checkboxGroup($model, 'flexxi_fs_chk', ['label' => $chkLabelPickup, 'groupOptions' => ['style' => 'margin-bottom:5px;', "class" => "checkbox-inline"]]);
//									}
//
									echo $form->hiddenField($brtRoute, "[0]brt_from_latitude", ['class' => 'locLat_0']);
									echo $form->hiddenField($brtRoute, "[0]brt_from_longitude", ['class' => 'locLon_0']);
									echo $form->hiddenField($brtRoute, "[0]brt_from_place_id", ['class' => 'locPlaceid_0']);
									echo $form->hiddenField($brtRoute, "[0]brt_from_formatted_address", ['class' => 'locFAdd_0']);
									echo $form->hiddenField($brtRoute, "[0]brt_from_location_cpy", ['class' => 'cpy_loc_0']);
//									?>
								</div>
								<div class="col-xs-12 mb15 n pb0">
									<div class="row">
										<div class="col-xs-10">
									<?
//									$required	 = false;
//									$readOnly	 = '';
//									if ($brtRoute->brtFromCity->cty_is_airport == 1 || $brtRoute->brtFromCity->cty_is_poi == 1)
//									{
//										$readOnly = 'readonly';
//									}
//									echo $form->textAreaGroup($brtRoute, "[$key]brt_from_location", array('label' => '', 'widgetOptions' => array('htmlOptions' => ['id' => "locFont_$key", 'class' => "form-control $autocompleteFrom brt_location_$key", 'readonly' => 'readonly', 'required' => $required, "autocomplete" => "section-new", 'placeholder' => "Pickup Address","onblur"=>"hyperModel.clearAddress(this)"] + $locFrom)));
//									$form->textFieldGroup($model, 'bkg_pickup_address');
									?>
								</div>                    
										<div class="col-xs-2"><span class="autoMarkerLoc <?//= $locMarkerFrom ?>" data-lockey="<?= $key ?>" data-toggle="tooltip" title="Select source location on map"><img src="/images/locator_icon4.png" alt="Precise location" width="30" height="30"></span></div>
									</div>
								</div>                    

							</div>-->
							<?
							skipPickupAddress:

							$key1				 = $key + 1;
							$ctyLat[$key1]		 = $brtRoute->brtToCity->cty_lat;
							$ctyLon[$key1]		 = $brtRoute->brtToCity->cty_long;
							$bound[$key1]		 = $brtRoute->brtToCity->cty_bounds;
							$isCtyAirport[$key1] = $brtRoute->brt_to_city_is_airport;
							$isCtyPoi[$key1]	 = $brtRoute->brt_to_city_is_poi;
							
							if($brtRoute->brt_to_place_id == "" && $brtRoute->brtToCity->cty_is_airport != 1 && $brtRoute->brtToCity->cty_is_poi != 1)
							{
								$brtRoute->brt_to_location = "";
							}
							$opt	 = ($key1 == $cntRt) ? '(Required)' : '';
							$optReq	 = ($key1 == $cntRt) ? ' *' : '';
							if(in_array($model->bkg_booking_type,[9,10,11]))
                            {
								$opt = '(Optional)';
							}
							$locTo = [];
							if ($brtRoute->brtToCity->cty_is_airport == 1 || $brtRoute->brtToCity->cty_is_poi == 1)
							{
								$brtRoute->brt_to_latitude = $brtRoute->brtToCity->cty_lat;
								$brtRoute->brt_to_longitude = $brtRoute->brtToCity->cty_long;
								$brtRoute->brt_to_place_id  = $brtRoute->brtToCity->cty_place_id;
								$brtRoute->brt_to_formatted_address  = $brtRoute->brtToCity->cty_garage_address;
								$locTo = $locReadonly;
								$locMarkerTo      = 'hide';
							}
							?>

<!--							<div class="row mt10">
								<div class="col-xs-12">
									<div class="row">
										<div class="col-xs-12 pl0">
											<label for="pickup_address" class="control-label text-left">Drop <?= $addressLabel ?> for <?= $brtRoute->brtToCity->cty_name ?> <?= $optReq ?>:</label>
											<?
//											if ($key1 == $cntRt && $scvVctId != VehicleCategory::SHARED_SEDAN_ECONOMIC && $brtRoute->brtToCity->cty_is_airport != 1 && $brtRoute->brtToCity->cty_is_poi != 1)
//											{
//												echo $form->checkboxGroup($model, 'drop_later_chk', ['label' => 'I will provide later, Assume city center now.', 'groupOptions' => ["style" => "margin-bottom: 5px;", "class" => "checkbox-inline", "data-key" => $key1]]);
//											}
//											else if ($key1 < $cntRt)
//											{
//												echo "<div class='checkbox-inline form-group' style='margin-bottom: 5px;'><div class='checkbox'><label><input type='checkbox' id='skipAdd" . $key1 . "' name='skipAdd' class='checkbox-inline' data-key='" . $key1 . "'> I will provide later, Assume city center now. </label></div></div>";
//											}
											?>
											<input type="hidden" id="ctyRad<?= $key1 ?>"  value="<?= $brtRoute->brtToCity->cty_radius ?>">
											<?= $form->hiddenField($brtRoute, "[$key]brt_to_latitude", ['class' => "locLat_$key1"]); ?>
											<?= $form->hiddenField($brtRoute, "[$key]brt_to_longitude", ['class' => "locLon_$key1"]); ?>
											<?= $form->hiddenField($brtRoute, "[$key]brt_to_place_id", ['class' => "locPlaceid_$key1"]); ?>
											<?= $form->hiddenField($brtRoute, "[$key]brt_to_formatted_address", ['class' => "locFAdd_$key1"]); ?>
											<?= $form->hiddenField($brtRoute, "[$key]brt_to_location_cpy", ['class' => "cpy_loc_$key1"]); ?>

										</div>
										<div class="col-xs-12">
											<div class="row">
												<div class="col-xs-10">
											<?php
//											$placeHolder = "Drop Address ";
//											$required	 = false;
//													if ($model->bkg_booking_type == 1 && $scvVctId == VehicleCategory::SHARED_SEDAN_ECONOMIC)
//											{
//												$required = true;
//											}
//											echo $form->textAreaGroup($brtRoute, "[$key]brt_to_location", array('label' => '', 'widgetOptions' => array("groupOptions" => ["style" => "margin-bottom:0"], 'htmlOptions' => ['id' => "locFont_$key1", 'class' => "form-control $autocompleteTo brt_location_$key1", "autocomplete" => "new-password",  'readonly' => 'readonly', 'required' => $required, 'placeholder' => $placeHolder,"onblur"=>"hyperModel.clearAddress(this)"] + $locTo)));
//											echo "<span class='hide' style='color:#a94442' id='skipAddErr" . $key1 . "'>Please select any location</span>";
//											if ($key1 == $cntRt)
//											{
//												$form->textFieldGroup($model, 'bkg_drop_address');
//												CHtml::error($model, 'bkg_drop_address');
//											}
											?>
										</div>
												<div class="col-xs-2"><span class="autoMarkerLoc <?= $locMarkerTo ?>" data-lockey="<?= $key1?>" data-toggle="tooltip" title="Select destination location on map"><img src="/images/locator_icon4.png" alt="Precise location" width="30" height="30"></span></div>
											</div>
										</div>

									</div>
								</div>
							</div>-->
							<?
							$j++;
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

					<? } ?>
				</div>
				<div class="col-sm-12">
					<div class="row">
						<div class="col-sm-6 pl0 mt20">
							<div class="mt0">
								<? //= CHtml::submitButton('NEXT', array('class' => 'btn next3-btn pl40 pr40', 'id' => 'nxtBtnAddDtls'));         ?>
								<?php echo CHtml::button('NEXT', array('class' => 'btn next3-btn pl40 pr40 pt15 pb15', 'id' => 'nxtBtnAddDtls')); ?>
							</div>
						</div>
					</div> 
				</div>
			</div>

		</div>
	</div>
	<?php
	$dboApplicable = Filter::dboApplicable($model);
	if ($dboApplicable)
	{
		if (!Yii::app()->user->isGuest)
		{
			?>
			<div class="col-sm-4 text-center">
				<img src="/images/doubleback_fares2.jpg" alt="" width="350" class="img-responsive">
			</div>
			<?php
		}
	}
	?>

	<?php $this->endWidget(); ?>


</div>

<?php
#$model->bkg_booking_type	 = 1; // For Testing purpose 
#$model->bkg_transfer_type	 = 0; // For Testing purpose 
?>

<script type="text/javascript">
	var bookNow = new BookNow();
	$("#l3").find("span").html(' By <?= $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label ?>');
	$("#info_car_type").html('<?= $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label ?>');

	if (parseInt('<?= $model->bkg_booking_type ?>') == 7) {
		$('#bdate').html('<?= date('\O\N jS M Y \<\b\r/>\A\T h:i A', strtotime($model->bkg_pickup_date)) ?>');
	}
	if (parseInt('<?= $model->bkg_booking_type ?>') == 1 || parseInt('<?= $model->bkg_booking_type ?>') == 3 || parseInt('<?= $model->bkg_booking_type ?>') == 2 || parseInt('<?= $model->bkg_booking_type ?>') == 5) {
		$('#btype').html(bookNow.arrTripTypes['<?= $model->bkg_booking_type ?>']);
	}
	var hyperModel = new HyperLocation();
	var model = {};
	var data = {};
	data.infoSource = "<?= CHtml::activeId($model, "bkg_info_source") ?>";
	data.bookingType = parseInt(<?= $model->bkg_booking_type ?>);
	data.carType = "<?= VehicleTypes::model()->getCarByCarType($model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_id); ?>";
	data.chkOthers = "<?= CHtml::activeId($model, "bkg_chk_others") ?>";
	data.sendSms = "<?= CHtml::activeId($model, "bkg_send_sms") ?>";
	data.sendEmail = "<?= CHtml::activeId($model, "bkg_send_email") ?>";
	data.contactNo = "<?= CHtml::activeId($model, "bkg_contact_no") ?>";
	data.infoSourceDesc = "<?= CHtml::activeId($model, "bkg_info_source_desc") ?>";
	data.flightChk = "<?= CHtml::activeId($model, "bkg_flight_chk") ?>";
	data.key = "<?= $key; ?>";
	data.fromCity = "<?= $brtRoute->brtFromCity->cty_id ?>",
			data.queryStr = "<?= $brtRoute->brtFromCity->cty_name ?>";
	data.vehicleType = "<?= $model->bkg_vehicle_type_id ?>";
	data.pickupLaterChk = "<?= CHtml::activeId($model, "pickup_later_chk") ?>";
	data.dropLaterChk = "<?= CHtml::activeId($model, "drop_later_chk") ?>";
	data.countryCode = "<?= CHtml::activeId($model, "bkg_country_code") ?>";
	data.userEmail = "<?= CHtml::activeId($model, "bkg_user_email") ?>";
	data.hyperlocationClass = '<?= $hyperLocationClass ?>';
	bookNow.data = data;
	$(document).ready(function ()
	{
		var cntRt = "<?= sizeof($model->bookingRoutes)?>";
		 $('[data-toggle="tooltip"]').tooltip();
		setHyperLocationData();
		bookNow.bkInfoReady();
		for (i = 0; i < cntRt; i++) {
		    $( "#skipAdd" + i ).prop( "checked", true );
		    $("#skipAdd" + i).attr('disabled', 'disabled');
		}
		$( "#BookingTemp_pickup_later_chk" ).prop( "checked", true );
		$("#BookingTemp_pickup_later_chk").attr('disabled', 'disabled');
		$( "#BookingTemp_drop_later_chk" ).prop( "checked", true );
		$("#BookingTemp_drop_later_chk").attr('disabled','disabled');
	});
	bookNow.bkInfoNext();

	function setHyperLocationData()
	{
		model.booking_type = '<?= $model->bkg_booking_type ?>';
		model.transfer_type = '<?= $model->bkg_transfer_type ?>';
		model.ctyLat = <?= json_encode($ctyLat) ?>;
		model.ctyLon = <?= json_encode($ctyLon) ?>;
		model.bound = <?= json_encode($bound) ?>;
		model.isCtyAirport = <?= json_encode($isCtyAirport) ?>;
		model.isCtyPoi = <?= json_encode($isCtyPoi) ?>;
		model.hyperLocationClass = '<?= $hyperLocationClass ?>';
		hyperModel.model = model;
		//hyperModel.initializepl();
	}

	$('.autoMarkerLoc').click(function(event){
		var locKey = $(event.currentTarget).data('lockey');
		var ctyLat = <?= json_encode($ctyLat) ?>;
		var ctyLon = <?= json_encode($ctyLon) ?>;
		var bound  = <?= json_encode($bound) ?>;
		var isAirport = <?= json_encode($isCtyAirport) ?>;
		var isCtyPoi  = <?= json_encode($isCtyPoi) ?>;
		if($('.locLat_' + locKey).val() != '' && $('.locLon_' + locKey).val() != '')
		{
			ctyLat[locKey] = $('.locLat_' + locKey).val();
			ctyLon[locKey] = $('.locLon_' + locKey).val();
		}
		
		if(locKey == 0){
			var title  = 'Enter approximate source location and then move pin to exact location';
			var locSearch = 'source';
		}
		if(locKey > 0){
			var title  = 'Enter approximate destination location and then move pin to exact location';
			var locSearch = 'destination';
		}
		
		$.ajax({
			"type":"POST",
			"url": '<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/autoMarkerAddress')) ?>',
			"data": {"ctyLat":ctyLat[locKey],"ctyLon":ctyLon[locKey],"bound":bound[locKey],"isCtyAirport":isAirport[locKey],"isCtyPoi":isCtyPoi[locKey],"locKey":locKey,"location":locSearch,"airport":0,"YII_CSRF_TOKEN":$("input[name='YII_CSRF_TOKEN']").val()},
			"dataType": "HTML",
			"success":function(data1)
			{
				var box = bootbox.dialog({
					message: data1,
					title: title,
					size: 'medium',
					onEscape: function ()
					{
						// user pressed escape
					},
				});
			}

		});
	});

	$('.txtHyperLocation').change(function () {
<?php if ($model->bkg_booking_type == 4)
{ ?>
			hyperModel.findAddressAirport(this.id);
<?php }
else
{ ?>
			hyperModel.findAddress(this.id);
		<?php } ?>
	});
	$('#BookingTemp_pickup_later_chk,#BookingTemp_drop_later_chk').change(function (event) {
		var key = $(event.currentTarget).parent().parent().parent().data('key');
		if ($(event.currentTarget).prop("checked") == true) {
			$(".brt_location_" + key).attr('readonly', true);
			$(".brt_location_" + key).addClass("input-disabled");
			$(".brt_location_" + key).val("");
			$('.locLat_' + key).val('');
			$('.locLon_' + key).val('');
			$('.locPlaceid_' + key).val('');
			$('.locFAdd_' + key).val('');
		} 
//		else {
//			$(".brt_location_" + key).attr('readonly', false);
//			$(".brt_location_" + key).removeClass("input-disabled");
//		}
	});
	
	$('input[name="skipAdd"]').click(function (event) {
		var key = $(event.currentTarget).data('key');
		if ($(event.currentTarget).is(':checked') == true)
		{
			$('.brt_location_' + key).attr('disabled', true);
			$('.brt_location_' + key).val('');
			$('.locLat_' + key).val('');
			$('.locLon_' + key).val('');
			$('.locPlaceid_' + key).val('');
			$('.locFAdd_' + key).val('');
			$('.brt_location_' + key).css('border', '1px solid #ccc');
			$('.skipAddErr' + key).addClass('hide');
		} else
		{
			$('.brt_location_' + key).attr('disabled', false);
		}
	});
</script>
