
<div class="tab-pane fade active show" id="tabLoginForm" role="tabpanel" aria-labelledby="tabLoginForm-tab">
	<?php
	/** @var CActiveForm $form */
	$form = $this->beginWidget('CActiveForm', array(
		'id'					 => 'password-form',
		'enableClientValidation' => false,
		'clientOptions'			 => array(
			'validateOnSubmit'	 => false,
			'errorCssClass'		 => 'has-error',
		),
		'enableAjaxValidation'	 => false,
		'errorMessageCssClass'	 => 'help-block',
		'htmlOptions'			 => array(
			'class'		 => 'form-horizontal',
			'onsubmit'	 => 'return $jsUserLogin.loginViaPassword(this);'
		),
	));
	echo $form->errorSummary([$userModel], '', '', ["class" => 'alert alert-danger formMessages mb-1']);
	?>
	<div class="form-group mt5 n"><a href="#" class="p10 pl0 color-black"><i class='bx bx-arrow-back backToRoot'></i></a></div>
	<div id="welcomeText" class="mb5" style="display: none"></div>
	<input type="hidden" id="ref" name="ref" value="vendorAttach">
	<div class="form-group">
		<!--				<label class="text-bold-500" for="exampleInputPassword1">Password</label>-->

		<?= $form->passwordField($userModel, "new_password", ["class" => "form-control", "required" => true]) ?>
		<?= $form->error($userModel, "new_password", ["class" => "text-danger"]) ?>

		<?= $form->hiddenField($userModel, "username", ["class" => "form-control", "required" => true, "placeholder" => "Enter email address / phone number.", "value" => '']) ?>
	</div>
	<div class="row text-center">
		<div class="col-12 col-md-6 col-lg-6 form-group">
			<button type="submit" class="btn btn-primary glow w-200">Proceed<i id="icon-arrow" class="bx bx-right-arrow-alt align-middle"></i></button>
		</div>
		<div class="col-12 col-md-6 col-lg-6 form-group">
			<button onclick="return $jsUserLogin.loginOTP();" class="btn btn-primary glow w-200">Login with OTP<i id="icon-arrow" class="bx bx-right-arrow-alt align-middle"></i></button>
		</div>
	</div>
	<?php $this->endWidget(); ?>
</div>
