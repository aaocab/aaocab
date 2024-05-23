
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
			<div class="form-group back-btn"><a href="#" class="p10 pl0 color-black"><img src="/images/bx-arrow-back.svg" alt="img" width="14" height="14" class="backToRoot"></a></div>
			<div id="welcomeText" class="mb5" style="display: none"></div>

<div class="form-group">
<!--				<label class="text-bold-500" for="exampleInputPassword1">Password</label>-->
				<?= $form->passwordField($userModel, "new_password", ["class" => "form-control", "required" => true]) ?>
				<?= $form->error($userModel, "new_password", ["class" => "text-danger"]) ?>



	<?= $form->hiddenField($userModel, "username", ["class" => "form-control", "required" => true, "placeholder" => "Enter email address / phone number.","value"=>'']) ?>
			</div>
			<div class="row text-center">
				<div class="col-12 col-md-6 col-lg-6 form-group">
					<button type="submit" class="btn btn-primary glow w-200">Proceed<img src="/images/bx-right-arrow-alt.svg" alt="img" width="14" height="14" id="icon-arrow"></button>
				</div>
				<div class="col-12 col-md-6 col-lg-6 form-group">
					

						<button onclick="return $jsUserLogin.loginOTP();" class="btn btn-primary glow w-200">Login with OTP<img src="/images/bx-right-arrow-alt.svg" alt="img" width="14" height="14" id="icon-arrow"></button>


				</div>
			</div>		
			<div id="forgetPassword" class="col-12 text-right p0" style="display: none"><button onclick="return $jsUserLogin.resetPassword();" class="btn btn-link pr0">Forgot password?</button></div>
			<div class="text-center font-16 weight500 color-green skipDiscountMsg" ><span>login to save upto 20%</span></div>
				<div class="text-center">				
			<!--					<a href="javascript:void(0)" class="btn btn-outline btn-outline-primary" onclick="showPhone();">Continue with phone number</a>-->
			<?php 
				$hideSkipLogin = 0;
				$sessSkipLoginCnt = Yii::app()->session['_gz_skip_login_count'];
				$skipLoginContactLimit = json_decode(Config::get('quote.guest'))->contactLimit;
				if($sessSkipLoginCnt > 0 && $sessSkipLoginCnt > $skipLoginContactLimit)
				{
					$hideSkipLogin = 1;
				}
				if($hideSkipLogin!=1){
			?>
			<span class="float-right mt5 skipLoginBtn hide">
				<button onclick="skipLogin();" class="mt10 btn-default border-0 pl20 pr20"><u>SKIP<img src="/images/bx-chevrons-right.svg" alt="img" width="18" height="18"></u></button>
				<input type="hidden" name="fullContactNumberToskip" value="">
				<input type="hidden" name="ContactEmailToskip" value="">
				</span><?}?>
		</div>						
			
										
			
			<?php $this->endWidget(); ?>
		</div>
