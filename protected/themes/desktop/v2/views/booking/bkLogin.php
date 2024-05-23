<?php
$this->layout		 = 'column_booking';
$version			 = Yii::app()->params['siteJSVersion'];
/** @var CClientScript $clientScript */
$clientScript		 = Yii::app()->clientScript;
//$clientScript->registerCssFile(Yii::app()->baseUrl . '/js/intl-tel-input/css/intlTelInput.css?v=');
$clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/bookNow.js?v=' . $version);
?>
<script>
    $jsBookNow = new BookNow();
</script>
<div class="container">
	<div class="row mt30">
		<?php
		$loginMainDivClass	 = 'col';
		$loginSubDivClass	 = 'pb30';

		if (Yii::app()->user->isGuest)
		{
			$loginMainDivClass	 = 'col-sm-7';
			$loginSubDivClass	 = '';
			$uname				 = '';
			$isLoggedin			 = false;
			?>
			<div class="col-lg-5 col-12 pb20" id="hideLogin">
				<div class="bg-white-box">
					<span class="pull-left mt30"><img src="/images/man.svg" width="50" alt=""></span>
					<div class="col-10 pull-right">
						<span class="font-16"><b>Login below to get personalized offers </b></span>
						<p></p>
						<!--<a class="social-btn bg-facebook mt10 mb10" onclick="socailSigin('facebook')" ><i class="fab fa-facebook-square mr10"></i> <b>Login with Facebook</b></a>-->
						<a onclick="socailSigin('google')" ><img src="/images/btn_google_signin_light_normal_web.png?v=0.1" alt="Login with Google"></a>
					</div>
					<div class="clear"></div>
				</div>
			</div>
			<?php
		}
		else
		{
			$uname		 = Yii::app()->user->loadUser()->usr_name;
			$isLoggedin	 = true;
		}
		?>
		<div class="col" id="hideDetails">
			<div class="bg-white-box">
				<div class="<?php echo $loginSubDivClass ?>">
					<img src="/images/contact.svg" width="30" alt="">
					<span class="font-18"> <b>Your contact details</b></span> (these will be used to send you quotes and booking updates)
					<div class="row mt10">
						<div class="col-sm-6">
							<label class="control-label">Phone Number (incl. country code)</label>
							<div class="form-group">   

								<?php
								$loggedinemail	 = "";
								$loggedinphone	 = "";
								if (!Yii::app()->user->isGuest)
								{
									$loggedinemail	 = Yii::app()->user->loadUser()->usr_email;
									$loggedinphone	 = Yii::app()->user->loadUser()->usr_mobile;
								}
								?>		
								<div class="col-xs-12">
									<?php
									$this->widget('ext.intlphoneinput.IntlPhoneInput', array(
										'model'					 => $model,
										'attribute'				 => 'fullContactNumber',
										'codeAttribute'			 => 'bkg_country_code',
										'numberAttribute'		 => 'bkg_contact_no',
										'options'				 => array(// optional
											'autoPlaceholder'	 => 'polite',
											'separateDialCode'	 => true,
											'autoHideDialCode'	 => true,
											'initialCountry'	 => 'IN',
											'customPlaceholder'	=> 'js:function(selectedCountryPlaceholder, selectedCountryData) {
														return "Enter your mobile number";
													  }'
										),
										'htmlOptions'			 => ['class' => 'form-control', 'value' => $loggedinphone],
										'localisedCountryNames'	 => false, // other public properties
									));
									?>
<?= $form->error($model, 'bkg_country_code'); ?>
<?= $form->error($model, 'bkg_contact_no'); ?>
								</div>
							</div>
						</div>
<!--						<div class="col-sm-6">      
							<label class="control-label" for="BookingTemp_bkg_user_email">Email Address</label>
<?//= $form->emailField($model, 'bkg_user_email', ['placeholder' => "Email Address", 'id' => CHtml::activeId($model, "bkg_user_email1"), 'class' => 'form-control', 'value' => $loggedinemail]) ?>                      
						</div>                -->
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-2"><?php $this->renderPartial("bkBanner", ['model' => $model]); ?></div>
</div>
<?php if ($model->bkg_booking_type == 1)
{ ?>
	<?= $form->hiddenField($model, 'bkg_transfer_type', ['id' => 'bkg_transfer_type1', 'value' => 0]); ?>
<?php } ?>




