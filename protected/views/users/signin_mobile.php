<?php
 $this->renderPartial('../index/head_mobile');
?>
<div class="page-login header-clear-large page-login-full">
	<h3 class="ultrabold top-30 bottom-0">Login</h3>
	<?php
		$form	 = $this->beginWidget('booster.widgets.TbActiveForm', array(
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
	<div class="line-f3 line-height20 text-center uppercase mt20">
		<a  class="button-round button-icon shadow-small regularbold bg-facebook button-s"  href="<?= Yii::app()->createUrl('users/oauth', array('provider' => 'Facebook')); ?>"><i class="fab fa-facebook"></i> Connect with Facebook</a>
	</div>
	<div class="line-f3 line-height20 text-center uppercase mb30">
		<a href="<?= Yii::app()->createUrl('users/oauth', array('provider' => 'Google')); ?>" class="button-round button-icon shadow-small regularbold bg-google button-s pl40 pr40"><i class="fab fa-google"></i> Connect with Google</a>
	</div>

	<div class="decoration decoration-margins ml0 mr0"><span class="or_style">OR</span></div>
	<?php echo CHtml::errorSummary($model); ?>
	<div style='color:#B80606;' class='pl10 pr10'>
	 <?
		if ($status == 'error') {
		echo "<span>You have entered an invalid email address or a password. Please enter correct details.</span>";
		} elseif ($status == "emailerror") {
		echo "<span>You have not verified your email address yet.</span>" . "<br><span style='color: #000000'>Go to your inbox and click on the activation link in the email we sent you to activate your account.</span>";
		?>
		<br><a href="<?= Yii::app()->createUrl('Users/verification', array('id' => $id)) ?>">click here to send activation link again</a><br><br>
		<?
		} elseif ($status == "emailinvalid") {
		echo "<span style='color: #000000;'>Invalid user id. Please create an account</span>";
		} elseif ($status == "logout") {
		echo "<span style='color: #009900;'>Logged out successfully</span>";
		} elseif ($status == "pusucc") {
		echo "<span style='color: #009900;'>Your password has been updated successfully. Please Login with your new password.</span>";
		}
		if ($status == 'signupsuccess')
		{
			$msg = "You have successfully registered with us. Please login";
		}
		?>
	</div>
	<p class="pl5 pr5 text-center"><span style='color: #009900;'><?= $msg ?></span></p>
	<div class="page-login-field top-30 form-group">
		<i class="fas fa-envelope color-highlight"></i>
		<?= $form->emailFieldGroup($model, 'usr_email', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['required' => TRUE, 'placeholder' => "Email"]))) ?>
		<em>(required)</em>
	</div>
	<div class="page-login-field bottom-30 form-group">
		<i class="fa fa-lock color-highlight"></i>
		<?= $form->passwordFieldGroup($model, 'usr_password', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['required' => TRUE, 'placeholder' => "Password"]))) ?>
		<em>(required)</em>
	</div>
	<div class="page-login-links bottom-10">
		<a class="forgot float-right" href="signup"><i class="fa fa-user float-right"></i>Create Account</a>
		<a class="create float-left" href="forgotpassword"><i class="fa fa-eye"></i>Forgot Password</a>
		<div class="clear"></div>
	</div>
	<!--<a href="#" class="button bg-highlight button-full button-rounded button-sm uppercase ultrabold shadow-small">LOGIN</a>-->
	 <input class="button bg-highlight button-full button-rounded button-sm uppercase ultrabold shadow-small"  type="submit" name="signin" value="Log In"/>
	<?php $this->endWidget(); ?>
</div>
<div class="clear"></div>
<a href="#" class="back-to-top-badge back-to-top-small bg-highlight"><i class="fa fa-angle-up"></i>Back to Top</a>
	<div id="menu-share" data-height="420" class="menu-box menu-bottom">
		<div class="menu-title">
			<span class="color-highlight">Just tap to share</span>
			<h1>Sharing is Caring</h1>
			<a href="#" class="menu-hide"><i class="fa fa-times"></i></a>
		</div>
		<div class="sheet-share-list">
			<a href="#" class="shareToFacebook"><i class="fab fa-facebook-f bg-facebook"></i><span>Facebook</span><i class="fa fa-angle-right"></i></a>
			<a href="#" class="shareToTwitter"><i class="fab fa-twitter bg-twitter"></i><span>Twitter</span><i class="fa fa-angle-right"></i></a>
			<a href="#" class="shareToLinkedIn"><i class="fab fa-linkedin-in bg-linkedin"></i><span>LinkedIn</span><i class="fa fa-angle-right"></i></a>
			<a href="#" class="shareToGooglePlus"><i class="fab fa-google-plus-g bg-google"></i><span>Google +</span><i class="fa fa-angle-right"></i></a>
			<a href="#" class="shareToPinterest"><i class="fab fa-pinterest-p bg-pinterest"></i><span>Pinterest</span><i class="fa fa-angle-right"></i></a>
			<a href="#" class="shareToWhatsApp"><i class="fab fa-whatsapp bg-whatsapp"></i><span>WhatsApp</span><i class="fa fa-angle-right"></i></a>
			<a href="#" class="shareToMail no-border bottom-5"><i class="fas fa-envelope bg-mail"></i><span>Email</span><i class="fa fa-angle-right"></i></a>
		</div>
	</div>
 