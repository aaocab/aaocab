<style>
    .form-group {
        margin-bottom: 7px;
        margin-left: 0 !important;
        margin-right: 0 !important;

    }

    .a-pointer{
        cursor: pointer;
    }

</style>
<div class="row register_path">
    <div class="col-xs-12 col-sm-8 col-md-5 float-none marginauto book-panel2 mt30">
        <div class="row">
            <div class="panel panel-primary mt20 signin-box" id="loginDiv" style="border: none;">
                <div class="panel-body" >

					<?php
					$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
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
					/* @var $form TbActiveForm */
					?>

                    <div class="col-xs-12 float-none marginauto mb20 mt30">
                        <div class="row" style="text-align: center;">
                            <div class="col-xs-12 col-md-10 col-md-offset-1 fbook-btn mb20">
                                <a class="btn btn-lg btn-social btn-facebook pl15 pr15" target="_blank" href="<?= Yii::app()->createUrl('users/oauth', array('provider' => 'Facebook')); ?>"><i class="fa fa-facebook pr5" style="font-size: 22px;"></i> Connect with Facebook</a>
                            </div>
                            <div class="col-xs-12 col-md-10 col-md-offset-1 google-btn">
                                <a class="btn btn-lg btn-social btn-googleplus pl15 pr15" target="_blank"  href="<?= Yii::app()->createUrl('users/oauth', array('provider' => 'Google')); ?>"><img src="../images/google_icon.png" alt="aaocab"> Connect with Google</a>
                            </div>
                            <a class="btn btn-lg btn-social btn-linkedin pl15 pr15 hide" target="_blank" href="<?= Yii::app()->createUrl('users/oauth', array('provider' => 'LinkedIn')); ?>"><i class="fa fa-linkedin"></i></a>

                        </div>
                    </div>
                    <div class="col-xs-12 m0 mt20 mb20 h4 style_or text-center"><span class="style_or3">OR</span></div>
                    <div class="row1 pb20">
						<?php echo CHtml::errorSummary($model); ?>
                        <div style='color:#B80606;' class='pl10 pr10'>
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
								echo "<span style='color: #009900;'>Logged out successfully</span>";
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
                        <div class="col-xs-12 col-sm-10 col-md-9 float-none marginauto">
                            <p class="text-success pl5 pr5 text-center"><?= $msg ?></p>

                            <div class="form-group">                                    <label for="">Email</label>
								<?= $form->emailFieldGroup($emailModel, 'eml_email_address', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['required' => TRUE, 'placeholder' => "Email"]))) ?>
                            </div>
                            <div class="form-group">
                                <label>Password</label>
								<?= $form->passwordFieldGroup($model, 'usr_password', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['required' => TRUE, 'placeholder' => "Password"]))) ?>
                            </div>
                            <div class="text-center pb10 mb10">
                                <input class="btn comm2-btn col-xs-12 pt10 pb10 text-uppercase"  type="submit" name="signin" value="Log In"/>
                            </div>
                        </div>
                    </div>
					<?php $this->endWidget(); ?>

                    <div class="col-xs-12 text-center mt10">
                        <a class="a-pointer" onclick="bKloginHandler()">Forgot my password?</a>
                    </div>
                    <div class="col-xs-12 mt10 mb30">
                        <div class="signin-text text-center"><span>Don't have an account? <?= CHtml::link("Sign up", Yii::app()->createUrl('signup'), ['style' => "", 'class' => ""]); ?></span></div>
                    </div>

                </div>
            </div>
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
    <div class="col-xs-12 col-sm-4 col-md-4 hide">
        <div class="register-add">
            <img src="images/add5.jpg" alt="India">
        </div>
    </div>
</div>


<script>
//    var isLoading = true;
//        $(document).ready(function () {
//
//            $(document).mouseover(function () {             $(".userPopup").fadeOut();
//        });
//
//        $("#forgotMyself").bind('click', forgotMyselfHandler);
//        $("#bKlogin").bind('click', forgotMyselfHandler);
//    });
//
//    function validateCheckHandler() {         if ($("#formId").validation({errorClass: 'validationErr'})) {
//            return true;
//        } else {
//            return false;
//        }
//    }
//
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
