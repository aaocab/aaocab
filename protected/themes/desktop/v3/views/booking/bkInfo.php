<style>
    .booking-info{
        color: red;
        padding-top: 15px;
        line-height: 1.3;
    }

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
    .sidenav a, .spclinsadv{
		padding: 12px 8px 12px 16px;
		font-size: 18px;
		color: #fff;
		font-weight: 500;
		border-bottom: none;
	}
</style>
<?php
//Yii::app()->clientScript->registerScriptFile('https://maps.googleapis.com/maps/api/js?key=AIzaSyDhybncyDc1ddM2qzn453XqYW8ZQDm7RW8&libraries=places&', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/js/jquery.mask1.min.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/jquery.disableAutoFill.min.js');

//Yii::app()->clientScript->registerScriptFile('https://maps.googleapis.com/maps/api/js?key=AIzaSyDhybncyDc1ddM2qzn453XqYW8ZQDm7RW8&libraries=places&', CClientScript::POS_HEAD);




/* @var $model BookingTemp */
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');
$ccode					 = Countries::model()->getCodeList();
$additionalAddressInfo	 = "Building No./ Society Name";
$addressLabel			 = ($model->bkg_booking_type == 4) ? 'Location' : 'Address';
$infosource				 = BookingAddInfo::model()->getInfosource('user');
$ulmodel				 = new Users('login');
$urmodel				 = new Users('insert');
$locFrom				 = [];
$locTo					 = [];
$hyperLocationClass		 = 'txtHyperLocation';
$autocompleteFrom		 = $autocompleteTo			 = $hyperLocationClass;
$locReadonly			 = ['readonly' => 'readonly'];
$locMarkerTo			 = $locMarkerFrom			 = '';
if ($model->bkgFromCity->cty_is_poi == 1)
{
	$locFrom			 = $locReadonly;
	$autocompleteFrom	 = '';
	$locMarkerFrom		 = "hide";
}
if ($model->bkgToCity->cty_is_poi == 1)
{
	$locTo			 = $locReadonly;
	$autocompleteTo	 = '';
	$locMarkerTo	 = "hide";
}

$userdiv = 'block';
$infocol = 'col-md-8';
if (!Yii::app()->user->isGuest)
{
	$user					 = Yii::app()->user->loadUser();
	$model->bkg_user_id		 = Yii::app()->user->getId();
	$model->bkg_user_name	 = $user->usr_name;
	$model->bkg_user_lname	 = $user->usr_lname;
	if ($model->bkg_cav_id > 0)
	{
		$model->bkg_user_email	 = $user->email;
		$model->bkg_contact_no	 = $user->usr_mobile;
	}
	$userdiv = 'none';
	$infocol = 'col-md-12';
	if ($model->bkg_booking_type == 1 && $model->bkg_vehicle_type_id == VehicleCategory::SHARED_SEDAN_ECONOMIC)
	{
		if (Users::model()->getFbLogin($model->bkg_user_id, $model->bkg_user_email, $model->bkg_contact_no))
		{
			$userdiv = 'none';
			$infocol = 'col-md-12';
		}
		else
		{
			$userdiv = 'block';
			$infocol = 'col-md-8';
		}
	}
}
else
{
	if (Users::model()->getFbLogin($model->bkg_user_id, $model->bkg_user_email, $model->bkg_contact_no))
	{
		$userdiv = 'none';
		$infocol = 'col-md-12';
	}
	else
	{
		$userdiv = 'block';
		$infocol = 'col-md-8';
	}
	$ulmodel->usr_email			 = $model->bkg_user_email;
	$urmodel->usr_email			 = $model->bkg_user_email;
	$urmodel->usr_mobile		 = $model->bkg_contact_no;
	$urmodel->usr_country_code	 = $model->bkg_country_code;
	$urmodel->usr_name			 = $model->bkg_user_name;
	$urmodel->usr_lname			 = $model->bkg_user_lname;
}
/*
  @var $model Booking
 *  */
?>

<div class="container mt30 mb30">

    <div class="row bg-white-box-2 m0 mt5">
		<?php //$this->renderPartial('bkLoginInfo', ['ulmodel' => $ulmodel, 'userdiv' => $userdiv, 'model' => $model], false, true); ?> 
        <div class="<?= $infocol ?> pt20 pb20 search-panel-3" id="infodiv">
			<?php
// $model=  Booking::model()->findByPk(25157);
//   $cabRate = Rate::model()->getCabDetailsbyCities($model->bkg_from_city_id, $model->bkg_to_city_id);
			$form = $this->beginWidget('CActiveForm', array(
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
			/* @var $form CActiveForm */
			?>


            <div class="flex">            
                <div class="panel-body padding_zero">   
                    <input autocomplete="off" name="hidden" type="text" style="display:none;">
                    <input type="hidden" id="step4" name="step" value="4">
					<input type="hidden" id="islogin" name="islogin" value="<?php echo $islogin; ?>">
					<?= $form->hiddenField($model, 'bkg_user_id'); ?> 
					<?= $form->hiddenField($model, 'bkg_id', ['id' => 'bkg_id4', 'class' => 'clsBkgID']); ?>
					<?= $form->hiddenField($model, 'hash', ['id' => 'hash4', 'class' => 'clsHash']); ?>
					<?php
					if ($model->bkg_booking_type == 7)
					{
						echo $form->hiddenField($model, 'bkg_shuttle_id');
					}
					if ($model->bkg_cav_id > 0)
					{
						echo $form->hiddenField($model, 'bkg_cav_id');
						echo $form->hiddenField($model, 'cavhash', ['value' => Yii::app()->shortHash->hash($model->bkg_cav_id)]);
					}
					?>

                    <div class="col-12">
                        <div class="row">
                            <div class="col-7">
                                <span class="font-20 text-uppercase"><b>Traveller's Information</b></span><br>
                                <div id="error_div_info" style="display: none;white-space: pre-wrap" class="alert alert-block alert-danger"></div>
                                <div class="row mt10">
                                    <div class="col-12">
                                        <label for="inputEmail" class="control-label">Primary Passenger Name *:</label>
                                    </div>
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-sm-6">
												<?= $form->textField($model, 'bkg_user_name', array('placeholder' => "First Name", 'class' => 'form-control nameFilterMask')) ?>
												<?php echo $form->error($model, 'bkg_user_name', ['class' => 'help-block error']); ?>
                                            </div>
                                            <div class="col-sm-6">
												<?= $form->textField($model, 'bkg_user_lname', array('placeholder' => "Last Name", 'class' => 'form-control nameFilterMask')) ?>
												<?php echo $form->error($model, 'bkg_user_lname', ['class' => 'help-block error']); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <label for="inputEmail" class="control-label">Primary Email address *:</label>
                                    </div>
                                    <div class="col-12">
										<?= $form->emailField($model, 'bkg_user_email', ['placeholder' => "Email Address", 'id' => CHtml::activeId($model, "bkg_user_email2"), 'class' => 'form-control']) ?>     
										<?php echo $form->error($model, 'bkg_user_email', ['class' => 'help-block error']); ?>                      
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <label for="inputEmail" class="control-label">Primary Contact Number *:</label>
                                    </div>
                                    <div class="col-12">
										<?php
										$loggedinemail	 = "";
										$loggedinphone	 = "";
										if (!Yii::app()->user->isGuest)
										{
											$loggedinemail	 = Yii::app()->user->loadUser()->usr_email;
											$loggedinphone	 = Yii::app()->user->loadUser()->usr_mobile;
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
                                    <div class="col-12 mt20">
                                        <label for="inputEmail" class="control-label">Send me booking confirmations by :</label>
                                    </div>
                                    <div class="col-12">
                                        <div class="row">   
                                            <div class="col-5 isd-input">
                                                <label class="checkbox-inline pt0 pr30 check-box">Email
													<?php echo $form->checkBox($model, 'bkg_send_email'); ?>
                                                    <span class="checkmark-box"></span>
                                                </label>

                                            </div>
                                            <div class="col-7 pr0">
                                                <label class="checkbox-inline pt0 check-box">Phone
													<?php echo $form->checkBox($model, 'bkg_send_sms'); ?>
                                                    <span class="checkmark-box"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mt20">
								<?php //= CHtml::submitButton('NEXT', array('class' => 'btn next3-btn pl40 pr40', 'id' => 'nxtBtnAddDtls'));     ?>
								<?php echo CHtml::button('NEXT', array('class' => 'btn-orange pl30 pr30 m0', 'id' => 'nxtBtnAddDtls')); ?>
                            </div>
                        </div>
                    </div> 
                </div>
				<?php $this->endWidget(); ?>
            </div>
        </div>
    </div>
</div>
	

    