<div class="row title-widget">
    <div class="col-12">
        <div class="container">
            <?php echo $this->pageTitle; ?>
        </div>
    </div>
</div>
<div class="row bg-gray pt30 pb30">
    <div class="col-12 col-lg-8 offset-lg-2">
        <div class="bg-white-box p0">
            <div class="row">
                <div class="col-12 col-lg-6 pl30 pr30">
						<?php
						$form = $this->beginWidget('CActiveForm', array(
							'id'					 => 'ulogin-form', 'enableClientValidation' => true,
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
                            <div class="row text-center pt20 pb20">
                               <!-- <div class="col-12 col-md-10 offset-md-1 mb20">
                                    <a class="social-btn bg-facebook" target="_blank" href="<?= Yii::app()->createUrl('users/oauth', array('provider' => 'Facebook')); ?>"><i class="fab fa-facebook-f pr5" style="font-size: 22px;"></i> Connect with Facebook</a>
                                </div>-->
                                <div class="col-12 col-md-10 offset-md-1">
                                    <a target="_blank"  href="<?= Yii::app()->createUrl('users/oauth', array('provider' => 'Google')); ?>"><img src="/images/btn_google_signin_dark_normal_web@2x.png?v=0.1" width="280" alt="Login with Google"></a>
                                </div>
                                <a class="btn btn-lg btn-social btn-linkedin pl15 pr15 hide" target="_blank" href="<?= Yii::app()->createUrl('users/oauth', array('provider' => 'LinkedIn')); ?>"><i class="fa fa-linkedin"></i></a>

                            </div>
                        </div>
                        <div class="col-12 text-center mb20"><b>OR</b></div>
                        <div class="col-12">
								<?php echo CHtml::errorSummary($model); ?>
                            <div style='color:#B80606;' class="text-center font-18">
									<?
									if ($status == 'error')
									{
										echo "<span>You have entered an invalid email address or a password. Please enter correct details.</span>";
									}
									elseif ($status == "emailerror")
									{
										echo "<span>You have not verified your email address yet.</span>" . "<br><span style='color: #000000'>Go to your inbox and click on the activation link in the email we sent you to activate your account.</span>";
										?>
										<br><a href="<?= Yii::app()->createUrl('Users/verification', array('id' => $id)) ?>">click here to send activation link again</a><br><br>
										<?
									}
									elseif ($status == "emailinvalid")
									{
										echo "<span style='color: #000000;'>Invalid user id. Please create an account</span>";
									}
									elseif ($status == "logout")
									{
										echo "<span class='color-green2'>Logged out successfully</span>";
									}
									elseif ($status == "pusucc")
									{
										echo "<span style='color: #009900;'>Your password has been updated successfully. Please Login with your new password.</span>";
									}
									?>
									<?php
									$msg = '';
									if ($status == 'succ')
									{
										$msg = "";
									}
									elseif ($status == 'signupsuccess')
									{
										$msg = "You have successfully registered with us. Please login";
									}
									else
									{
										
									}
									?>
                            </div>
                            <div class="col-12">
                                <p class="text-success pl5 pr5 text-center"><?= $msg ?></p>
                                <div class="form-group">  
								<label for="">Email</label>
                                <?= $form->emailField($emailModel, 'eml_email_address',['required' => TRUE, 'placeholder' => "Email",'class' => 'form-control']) ?>
                                <?php echo $form->error($emailModel, 'eml_email_address',['class' => 'help-block error']);?>
                                </div>
                                <div class="form-group">
                                <label>Password</label>
                                <?= $form->passwordField($model, 'usr_password', ['required' => TRUE, 'placeholder' => "Password", 'class'=>"form-control"]) ?>
                                <?php echo $form->error($model, 'usr_password',['class' => 'help-block error']);?>
                                </div>
                                <div class="text-center pb10 mb10">
                                    <input class="btn text-uppercase gradient-green-blue font-14 pt10 pb10 pl30 pr30 border-none"  type="submit" name="signin" value="Log In"/>
                                </div>
                            </div>
                        </div>
                    </div>
						<?php $this->endWidget(); ?>

                    <div class="col-12 text-center ">
                        <a class="a-pointer" onclick="bKloginHandler()" style="cursor: pointer">Forgot my password?</a>
                    </div>
                    <div class="col-12 mt10 mb30">
                        <div class="signin-text text-center"><span>Don't have an account? <?= CHtml::link("Sign up", Yii::app()->createUrl('signup'), ['style' => "", 'class' => ""]); ?></span></div>
                    </div>


                    <div>
							<?
							$this->actionForgotform();
							?>
                    </div>

                    <div class="panel-footer mr0 ml0 mb0 hide" >


                        <div style="text-align: center;">
                            <label class="mr10"><strong>Connect with</strong></label>
                            <a class="btn btn-lg btn-social btn-facebook pl15 pr15" href="<?= Yii::app()->createUrl('users/oauth', array('provider' => 'Facebook')); ?>"><i class="fa fa-facebook"></i></a>
                            <a class="btn btn-lg btn-social btn-googleplus pl15 pr15" href="<?= Yii::app()->createUrl('users/oauth', array('provider' => 'Google')); ?>"><i class="fa fa-google-plus"></i></a>
                            <a class="btn btn-lg btn-social btn-linkedin pl15 pr15" href="<?= Yii::app()->createUrl('users/oauth', array('provider' => 'LinkedIn')); ?>"><i class="fa fa-linkedin"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 gradient-green-blue2 text-center color-white widget-style2">
                    <span><img src="/images/gozo_orange-white.svg?v0.1" width="180" alt="aaocab" class="mt40"></span><br><br>
                    <div class="font-24 mt50 mb5">Download The App</div>
                    <div class="mb50">
                        <a href="https://play.google.com/store/apps/details?id=com.aaocab.client" target="_blank"><img src="/images/GooglePlay.png?v1.1" alt="aaocab APP"></a> <a href="https://itunes.apple.com/app/id1398759012?mt=8" target="_blank"><img src="/images/app_store.png?v1.2" alt="aaocab APP"></a>
                    </div>
                    <div class="font-30 mt50 pt40 orange-color"><b>Leader in outstation Taxi</b></div>
                    <span class="font-18"><b>Available in 3000+ cities across India</b></span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function bKlogin() {
        $("#msg").html('');

        $('#loginDiv').show(600);//, function () 

        $('#fPass').hide(600);

    }

    function bKloginHandler() {
        $("#msg").html('');

        $('#loginDiv').hide(600);//, function () 

        $('#fPass').show(600);



    }
</script>
