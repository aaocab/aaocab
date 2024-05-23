<?
$version = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/bookNow.js?v=' . $version);
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/fontawesome-web/css/all.min.css?v0.6');
?>

<div class="content-boxed-widget page-login page-login-full widget-style-1 widget-content-bg">
<!--	<div class="tab-titles">

			<a href="#" class="uppercase bold active-tab-button">Log In</a>
		</div>-->
<h3 class="top-10 text-style-2 text-left bottom-0">
    Welcome,<br>
    Ready for a journey?
</h3>
<p class="color-gray bottom-0">Enter your registered email ID</p>
		<div>
				<?php
				$form	 = $this->beginWidget('CActiveForm', array(
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
						'class'	 => 'form-horizontal', 'method' => 'post',
					),
				));
				?>



				<?php echo CHtml::errorSummary($model); ?>
				<div style='color:#B80606;' class='pl10 pr10 text-center'>
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
					if ($status == 'signupsuccess')
					{
						$msg = "You have successfully registered with us. Please login";
					}
					?>
				</div>
				<p class="pl5 pr5 text-center mb10"><span style='color: #009900;'><?= $msg ?></span></p>

                                <div class="page-login-field top-10">
                                    <?= $form->emailField($emailModel, 'eml_email_address', array('label' => '', 'id' => 'email_1', 'class'=>'pl15', 'placeholder' => "Email", 'widgetOptions' => array('htmlOptions' => ['required' => TRUE, 'placeholder' => "Email"]))) ?>
                                </div>

                                <div class="page-login-field bottom-10 top-10">
                                    <?= $form->passwordField($model, 'usr_password', array('label' => '', 'id' => 'pass_1', 'class'=>'pl15', 'placeholder' => "Password", 'widgetOptions' => array('htmlOptions' => ['required' => TRUE, 'placeholder' => "Password"]))) ?>
                                </div>

                                <div class="page-login-links bottom-10">
                                    <a class="create float-left mt0" href="<?php echo Yii::app()->createUrl('/forgotpassword'); ?>"><i class="fa fa-eye"></i>Forgot Password</a>
                                    <div class="clear"></div>
                                </div>
                                <input class="btn-submit"  type="submit" name="signin" value="Login"/><img src="/images/right.svg" width="45" alt="" class="pull-right">
				<?php $this->endWidget(); ?>

				<div class="decoration decoration-margins ml0 mr0 mt30"><span class="or_style">Or sign in with</span></div>


                                <div class="line-height20 text-center mt20">
                                    <div class="mt20 ">
                                        <a href="<?= Yii::app()->createUrl('users/oauth', array('provider' => 'Google')); ?>" ><img src="/images/btn_google_signin_light_normal_web.png?v=0.1" alt="Login with Google" class="inline-block"></a>
                                    </div>
                                   <!-- <div class="one-half last-column text-left">
                                        <a href="<?= Yii::app()->createUrl('users/oauth', array('provider' => 'Facebook')); ?>" class="btn-social-1"><img src="/images/facebook.svg" width="50" alt=""></a>
                                    </div>-->
                                    <div class="clear"></div>
                                    <p class="mt20 color-gray">Dont have an  account yet? <a href="<?php echo Yii::app()->createUrl('/signup'); ?>" class="default-link"><b>Sign Up</b></a></p>
                                </div>
            </div>
</div>
<script>
    $('#sign-upp').click(function () {
        alert("fsdf");
        window.location = "<?= Yii::app()->getBaseUrl(true) ?>/signup";
        return false;
    });
</script>
<script type="text/javascript">
    $jsBookNow = new BookNow();
    $(document).ready(function () {
    });
    function validateCheckHandlerss() {
        if ($("#formId").validation({errorClass: 'validationErr'})) {
            return true;
        } else {
            return false;
        }
    }


    $("#ulogin-form").submit(function (event) {



        var is_error = 0;
        var msg = "";
        var uemail = $("#email_1").val();
        if ($.trim(uemail) == "")
        {
            msg += "Email cannot be blank<br/>";
            is_error++;
        } else if (!$jsBookNow.validateEmail(uemail))
        {
            msg += "Email is not valid<br/>";
            is_error++;
        }

        if ($.trim($("#pass_1").val()) == "")
        {
            msg += "Password cannot be blank<br/>";
            is_error++;
        }

        if (is_error > 0) {
            $jsBookNow.showErrorMsg(msg);
            event.preventDefault();
        }
    });
    $("#signup-form").submit(function (event) {
        var is_error = 0;
        var msg = "";
        var uemail = $("#email_2").val();
        var usercontact = $.trim($('#fullContactNumber').val());
        var cont = usercontact.replace(/\s/g, '');

        if ($.trim($("#Contact_ctt_first_name").val()) == "")
        {
            msg += "First name cannot be blank<br/>";
            is_error++;
        }
        if ($.trim($("#Contact_ctt_last_name").val()) == "")
        {
            msg += "Last name cannot be blank<br/>";
            is_error++;
        }

        if ($.trim(uemail) == "")
        {
            msg += "Email cannot be blank<br/>";
            is_error++;
        } else if (!$jsBookNow.validateEmail(uemail))
        {
            msg += "Email is not valid<br/>";
            is_error++;
        }

        if (usercontact == "")
        {
            msg += 'Mobile no cannot be blank<br/>';
            is_error++;
        } else if (cont.length < 10 || cont.length > 12)
        {
            msg += 'Invalid mobile no<br/>';
            is_error++;
        } else if (isInteger(usercontact) == false) {
            msg += 'Invalid mobile no<br/>';
            is_error++;
        }

        if ($.trim($("#Users_new_password").val()) == "")
        {
            msg += "Password cannot be blank<br/>";
            is_error++;
        }
        if ($.trim($("#Users_repeat_password").val()) != $.trim($("#Users_new_password").val()))
        {
            msg += "Repeat Password does not match<br/>";
            is_error++;
        }



        if (is_error > 0) {
            $jsBookNow.showErrorMsg(msg);
            event.preventDefault();
        }
    });
    function isInteger(s) {
        var i;
        s = s.toString();
        for (i = 0; i < s.length; i++) {
            var c = s.charAt(i);
            if (isNaN(c)) {
                return false;
            }
        }
        return true;
    }
</script>

