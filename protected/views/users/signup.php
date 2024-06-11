<style>
    .form-group {
        margin-bottom: 7px;
        margin-left: 0 !important;
        margin-right: 0 !important;
    }

</style>
<? $imgVer	 = Yii::app()->params['imageVersion']; ?>
<div class="row mt20 flex">
    <div class="col-xs-12 col-sm-7 col-md-7 book-panel2 padding_zero">
        <div class="panel panel-primary">
            <div class="panel-body">
                <div class="h3 mt0 orange-color text-center" style="font-weight: normal">Sign Up to aaocab</div>
                <div class="h5 text-center" style="font-weight: normal">Already on aaocab? <a href="/signin">Log In</a></div>
                <div class="row" style="text-align: center;">
					<div class="col-xs-12 col-md-6 col-md-offset-3 fbook-btn mb20">
						<a class="btn btn-lg btn-social btn-facebook pl15 pr15" target="_blank" href="<?= Yii::app()->createUrl('users/oauth', array('provider' => 'Facebook')); ?>"><i class="fa fa-facebook pr5" style="font-size: 22px;"></i> Connect with Facebook</a>
					</div>
					<div class="col-xs-12 col-md-6 col-md-offset-3 google-btn">
						<a class="btn btn-lg btn-social btn-googleplus pl15 pr15" target="_blank"  href="<?= Yii::app()->createUrl('users/oauth', array('provider' => 'Google')); ?>"><img src="../images/google_icon.png" alt="aaocab"> Connect with Google</a>
					</div>
					<a class="btn btn-lg btn-social btn-linkedin pl15 pr15 hide" target="_blank" href="<?= Yii::app()->createUrl('users/oauth', array('provider' => 'LinkedIn')); ?>"><i class="fa fa-linkedin"></i></a>
					<div class="col-xs-12 m0 mt30 mb20 h4 style_or"><span class="style_or3">OR</span></div>
                </div>
				<?php
				$form	 = $this->beginWidget('booster.widgets.TbActiveForm', array(
					'id'					 => 'signup-form', 'enableClientValidation' => true,
					'clientOptions'			 => array(
						'validateOnSubmit'	 => true,
						'errorCssClass'		 => 'has-error'
					),
					// Please note: When you enable ajax validation, make sure the corresponding
					// controller action is handling ajax validation correctly.
					// See class documentation of CActiveForm for details on this,
					// you need to use the performAjaxValidation()-method described there.
					'enableAjaxValidation'	 => false,
					'errorMessageCssClass'	 => 'help-block',
					'htmlOptions'			 => array(
						'class' => 'form-horizontal',
					),
				));
				/* @var $form TbActiveForm */
				?>

                <div class="row">
                    <div style="text-align:center; padding:0 20px ;">

						<?php
						if ($status == 'errors')
						{
							echo "<span style='color:#ff0000;'>Password didn't match.</span>";
						}
						elseif ($status == 'emlext')
						{
							echo "<span style='color:#B80606;'>This Email addresss is already registered. Please enter a new email address.</span>";
						}
						elseif ($status == 'phnext')
						{
							echo "<span style='color:#B80606;'>This Phone No is already registered. Please enter a new Phone No.</span>";
						}
						elseif ($status == 'error')
						{
							echo "<span style='color:#ff0000;'>Please Try Again.</span>";
						}
						else
						{
							
						}
						?>
                    </div>
					<? //php echo CHtml::errorSummary($model);   ?>
                    <div class="form-group">
                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <label>First Name<span style="color: red;font-size: 15px;">*</span></label>
							<?= $form->textFieldGroup($contactModel, 'ctt_first_name', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Enter your name"]))) ?>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <label>Last Name<span style="color: red;font-size: 15px;">*</span></label>
							<?= $form->textFieldGroup($contactModel, 'ctt_last_name', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Enter your last name"]))) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <label>Email<span style="color: red;font-size: 15px;">*</span></label>
							<?= $form->emailFieldGroup($emailModel, 'eml_email_address', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Email (will be used for login) "]))) ?>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <label>Mobile<span style="color: red;font-size: 15px;">*</span></label>


							<?php
							$this->widget('ext.intlphoneinput.IntlPhoneInput', array(
								'model'					 => $phoneModel,
								'attribute'				 => 'phn_phone_no',
								'codeAttribute'			 => 'phn_phone_country_code',
								'numberAttribute'		 => 'phn_phone_no',
								'options'				 => array(// optional
									'separateDialCode'	 => true,
									'autoHideDialCode'	 => true,
									'initialCountry'	 => 'in'
								),
								'htmlOptions'			 => ['class' => 'form-control', 'id' => 'fullContactNumber1'],
								'localisedCountryNames'	 => false, // other public properties
							));
							?> 

                        </div>
                    </div> 
                    <div class="form-group">
                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <label>Password<span style="color: red;font-size: 15px;">*</span></label>
							<?= $form->passwordFieldGroup($model, 'new_password', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Password"]))) ?>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <label>Repeat Password<span style="color: red;font-size: 15px;">*</span></label>
							<?= $form->passwordFieldGroup($model, 'repeat_password', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Repeat Password"]))) ?>
                        </div>
                    </div> 
                    <div class="form-group">
                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <label>Referral Code</label>
							<?php
							$cookieReferredCode = Yii::app()->request->cookies['gozo_refferal_id']->value;
							if ($model->usr_referred_code != '')
							{
								$model->usr_referred_code = $model->usr_referred_code;
							}
							else if ($cookieReferredCode != '')
							{
								$model->usr_referred_code = $cookieReferredCode;
							}
							?>
							<?= $form->textFieldGroup($model, 'usr_referred_code', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Refferal Code"]))) ?>
                        </div>

                    </div> 

                    <div class="form-group">
                        <div class="form-group text-left pl15">
							<?= CHtml::submitButton("REGISTER", ['class' => "btn comm2-btn pl20 pr20 p10", 'tabindex' => "4"]); ?>
                        </div>
                    </div>
                    <div class="clr"></div>
                </div>
				<?php $this->endWidget(); ?>
            </div>
            <div class="panel-footer mr0 ml0 mb0 text-center hide" >
                <div class="mt10">
                    <a class="forgotpass2" onclick="bKloginHandler()">Forgot my password?</a>
                </div>
                <hr class="m10">
                <div style="text-align: center;">
                    <label class="mr10"><strong>Connect with</strong></label>
                    <a class="btn btn-lg btn-social btn-facebook pl15 pr15" href="<?= Yii::app()->createUrl('users/oauth', array('provider' => 'Facebook')); ?>"><i class="fa fa-facebook"></i></a>
                    <a class="btn btn-lg btn-social btn-googleplus pl15 pr15" href="<?= Yii::app()->createUrl('users/oauth', array('provider' => 'Google')); ?>"><i class="fa fa-google-plus"></i></a>
                    <a class="btn btn-lg btn-social btn-linkedin pl15 pr15" href="<?= Yii::app()->createUrl('users/oauth', array('provider' => 'LinkedIn')); ?>"><i class="fa fa-linkedin"></i></a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-5 col-md-5 padding_zero">
        <div class="register-add">
			<div class="pt30 pb30">
				<div class="mt20 mb50"><img src="../images/logo_outstation.png?v1.1" alt="aaocab"></div>
				<div class="mt50 pt50 mb5"><b>Book with Gozo cabs mobile app</b></div>
				<div class="mb50 pb50">
					<a href="https://play.google.com/store/apps/details?id=com.aaocab.client" target="_blank"><img src="/images/GooglePlay.png?v1.1" alt="aaocab APP"></a> <a href="https://itunes.apple.com/app/id1398759012?mt=8" target="_blank"><img src="/images/app_store.png?v1.2" alt="aaocab APP"></a>
				</div>
				<div class="h1 mt50 orange-color">
					India's Leader<br>
					in Outstation Travel
				</div>
				<div class="h4 mt0">
					<b>For One-Way, Round Trip & Multi-city Trip</b>
				</div>
				<div class="h5 mb20">
					<p>Consistent Quality | Easy Experience</p>
					<p>Great Service | Fair Pricing</p>
				</div>
			</div>
        </div>
    </div>

</div>

<script type="text/javascript">
    $(document).ready(function () {
    });
    function validateCheckHandlerss() {
        if ($("#formId").validation({errorClass: 'validationErr'})) {
            return true;
        } else {
            return false;
        }
    }
</script>
