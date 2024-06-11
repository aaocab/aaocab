<? $imgVer	 = Yii::app()->params['imageVersion']; ?>
<div class="row title-widget">
    <div class="col-12">
        <div class="container">
			<?php echo $this->pageTitle; ?>
        </div>
    </div>
</div>
<div class="row bg-gray pt30 pb30">
    <div class="col-12 col-lg-6 offset-lg-3">
        <div class="bg-white-box p0">
            <div class="row m0">
                <div class="col-12 p30 pb0 border-bottom">
                    <h1 class="font-22 mt0 text-center mb0"><b>Sign Up to aaocab</b></h1>
                    <div class="font-14 text-center mb20">Already on aaocab? <a href="/signin" class="color-orange">Log In</a></div>
                    <div class="row text-center">
                        <div class="col-12 col-md-6">
                            <a class="social-btn bg-facebook" target="_blank" href="<?= Yii::app()->createUrl('users/oauth', array('provider' => 'Facebook')); ?>"><i class="fab fa-facebook-f mr10 font-18"></i> Connect with Facebook</a>
                        </div>
                        <div class="col-12 col-md-6">
                            <a class="social-btn bg-google" target="_blank"  href="<?= Yii::app()->createUrl('users/oauth', array('provider' => 'Google')); ?>"><i class="fab fa-google mr10 font-18"></i> Connect with Google</a>
                        </div>
                        <a class="btn btn-lg btn-social btn-linkedin pl15 pr15 hide" target="_blank" href="<?= Yii::app()->createUrl('users/oauth', array('provider' => 'LinkedIn')); ?>"><i class="fa fa-linkedin"></i></a>
                        <div class="col-12 m0 mt30 mb20 h4 style_or color-gray"><span class="style_or3">OR</span></div>
                    </div>
					<?php
						if (!empty($errors))
						{							
						?>
						<div class="alert alert-danger" style="font-size: 15px;" role="alert">
							<ul style="list-style-type:none;">
								<?php foreach($errors as $err){	?>
										<li><?php echo $err; ?></li>
								<?php 	}?>
							</ul>
						</div>
	
	                <?php }
					$form	 = $this->beginWidget('CActiveForm', array(
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
					/* @var $form CActiveForm */
					?>

                    <div class="row">
                        <div class="col-12">
                            <div>

								<?php
//								if ($status == 'errors')
//								{
//									echo "<span style='color:#ff0000;'>Password didn't match.</span>";
//								}
//								elseif ($status == 'emlext')
//								{
//									echo "<span style='color:#B80606;'>This Email addresss is already registered. Please enter a new email address.</span>";
//								}
//								elseif ($status == 'phnext')
//								{
//									echo "<span style='color:#B80606;'>This Phone No is already registered. Please enter a new Phone No.</span>";
//								}
//								elseif ($status == 'error')
//								{
//									echo "<span style='color:#ff0000;'>Please Try Again.</span>";
//								}
//								else
//								{
//									
//								}
								?>
                            </div>
							<? //php echo CHtml::errorSummary($model);    ?>
                            <div class="row form-group">
                                <div class="col-12 col-sm-6 col-md-6">
                                    <label>First Name<span style="color: red;font-size: 15px;">*</span></label>
									<?= $form->textField($contactModel, 'ctt_first_name', ['placeholder' => "Enter your name", 'required' => "required", 'class' => 'form-control nameFilterMask']) ?>
                                </div>
                                <div class="col-12 col-sm-6 col-md-6">
                                    <label>Last Name<span style="color: red;font-size: 15px;">*</span></label>
									<?= $form->textField($contactModel, 'ctt_last_name', ['placeholder' => "Enter your last name", 'required' => "required", 'class' => 'form-control nameFilterMask']) ?>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-12 col-sm-6 col-md-6">
                                    <label>Email<span style="color: red;font-size: 15px;">*</span></label>
									<?= $form->emailField($emailModel, 'eml_email_address', ['placeholder' => "Email (will be used for login)", 'required' => "required", 'class' => 'form-control']) ?>
                                </div>
                                <div class="col-12 col-sm-6 col-md-6">
                                    <label>Mobile (incl. country code)<span style="color: red;font-size: 15px;">*</span></label>

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
										'htmlOptions'			 => ['class' => 'form-control', 'required' => 'required', 'id' => 'fullContactNumber1', 'value' => '', 'maxlength' => '15'],
										'localisedCountryNames'	 => false, // other public properties
									));
									?> 

                                </div>
                            </div> 
                            <div class="row form-group">
                                <div class="col-12 col-sm-6 col-md-6">
                                    <label>Password<span style="color: red;font-size: 15px;">*</span></label>
									<?= $form->passwordField($model, 'new_password', ['placeholder' => "Password", 'required' => "required", 'class' => 'form-control']) ?>
									<?php echo $form->error($model, 'new_password', ['class' => 'help-block error']); ?>
                                </div>
                                <div class="col-12 col-sm-6 col-md-6">
                                    <label>Repeat Password<span style="color: red;font-size: 15px;">*</span></label>
									<?= $form->passwordField($model, 'repeat_password', ['placeholder' => "Repeat Password", 'required' => "required", 'class' => 'form-control']) ?>
									<?php echo $form->error($model, 'repeat_password', ['class' => 'help-block error']); ?>
                                </div>
                            </div> 
                            <div class="row form-group">
                                <div class="col-12 col-sm-6 col-md-6">
                                    <label>Referral Code</label>
									<?
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
									<?= $form->textField($model, 'usr_referred_code', ['placeholder' => "Refferal Code", 'class' => 'form-control']) ?>
                                </div>
                            </div> 
							<?php
							if (CCaptcha::checkRequirements())
							{
								?>  
								<div class="row form-group">
									<div class="col-12 col-sm-6 col-md-12">
										<?php
										echo '<b>ARE YOU HUMAN?</b><br />' . $form->labelEx($model, 'verifyCode');
										?> 
										<div>
											<?php
											//$this->widget('CCaptcha',array(  'captchaAction'=>'site/captcha' ));  
											$this->widget('CCaptcha', array('clickableImage' => true, 'captchaAction' => "site/captcha"));
											echo $form->error($model, 'verifyCode');
											echo '<br />' . $form->textField($model, 'verifyCode');
											?>
											<div class="">Please enter the letters as they are shown in the image above.<br/>
												Letters are not case-sensitive.
											</div>
										</div>
									</div>
								</div>
								<?php
							}
							?>
                            <div class="row form-group">
                                <div class="form-group text-left pl15">
									<?= CHtml::submitButton("REGISTER", ['class' => "btn text-uppercase gradient-green-blue font-14 pt10 pb10 pl30 pr30 border-none", 'tabindex' => "4"]); ?>
                                </div>
                            </div>
                            <div class="clr"></div>
                        </div>
						<?php $this->endWidget(); ?>
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
                <div class="col-12 text-center pt20">
                    <div class="font-20">Book with Gozo cabs mobile app</div>
                    <div class="pb20">
                        <a href="https://play.google.com/store/apps/details?id=com.aaocab.client" target="_blank"><img src="/images/GooglePlay.png?v1.1" alt="aaocab APP"></a> <a href="https://itunes.apple.com/app/id1398759012?mt=8" target="_blank"><img src="/images/app_store.png?v1.2" alt="aaocab APP"></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function validateCheckHandlerss() {
        if ($("#formId").validation({errorClass: 'validationErr'})) {
            return true;
        } else {
            return false;
        }
    }
</script>
