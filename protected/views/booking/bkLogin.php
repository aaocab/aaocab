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
<div class="row">
	<?php
	$loginMainDivClass	 = 'col-xs-12 col-sm-9 col-md-7 book-panel pb0';
	$loginSubDivClass	 = 'pb30';

	if (Yii::app()->user->isGuest)
	{
		$loginMainDivClass	 = 'col-xs-12 col-sm-6 col-md-6 marginauto book-panel pb0';
		$loginSubDivClass	 = '';
		$uname				 = '';
		$isLoggedin			 = false;
		?>
		<div class="col-xs-12 col-sm-5 col-md-4 float-left marginauto book-panel pb0" id="hideLogin">
			<div class="panel panel-default border-radius box-shadow1">
				<div class="panel-body p15 pb30">
					<h4 class="text-center mt0">Login below to get personalized offers </h4>
					<div class="col-xs-12 col-md-10 col-md-offset-1 fbook-btn mb10">
						<a class="btn btn-lg btn-social btn-facebook pl15 pr15" onclick="socailSigin('facebook')" ><i class="fa fa-facebook pr5" style="font-size: 22px;"></i> Login with Facebook</a>
					</div>

					<div class="col-xs-12 col-md-10 col-md-offset-1 google-btn">
						<a class="btn btn-lg btn-social btn-googleplus pl15 pr15" onclick="socailSigin('google')" ><img src="../images/google_icon.png" alt="aaocab"> Login with Google</a>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
	else
	{
		$user			 = Yii::app()->user->loadUser();
		$contactModel	 = Contact::model()->findByPk($user->usr_contact_id);
		$uname			 = $contactModel->ctt_first_name?$contactModel->ctt_first_name:'';
		$isLoggedin		 = true;
	}
	?>
	<div class="<?= $loginMainDivClass ?>" id="hideDetails">
		<div class="panel panel-default border-radius box-shadow1 box-style">
			<div class="panel-body p20 <?php echo $loginSubDivClass ?>">
				<span class="m0 h3 "><i class="fa fa-pencil-square-o"></i> 
					Your contact details </span>(these will be used to send you quotes and booking updates)

				<div class="row m0">
					<div class="col-xs-12 col-sm-6 ptl0 pb0 pl0 pr5">
						<label class="control-label">Phone Number (incl. country code)</label>
						<div class="form-group">   

							<?php
							$loggedinemail	 = "";
							$loggedinphone	 = "";
							if (!Yii::app()->user->isGuest)
							{
								$user = Yii::app()->user->loadUser();
								if ($user->usr_contact_id)
								{
									$emailModel		 = ContactEmail::model()->findByConId($user->usr_contact_id);
									$phoneModel		 = ContactPhone::model()->findByConId($user->usr_contact_id);
//									$loggedinemail	 = Yii::app()->user->loadUser()->usr_email;
//									$loggedinphone	 = Yii::app()->user->loadUser()->usr_mobile;
									$loggedinemail	 = $emailModel[0]->eml_email_address;
									$loggedinphone	 = $phoneModel[0]->phn_phone_no;
								}
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
										'separateDialCode'	 => true,
										'autoHideDialCode'	 => true,
										'initialCountry'	 => 'in'
									),
									'htmlOptions'			 => ['class' => 'form-control', 'value' => $loggedinphone, "autocomplete" => "section-new"],
									'localisedCountryNames'	 => false, // other public properties
								));
								?>
								<?= $form->error($model, 'bkg_country_code'); ?>
								<?= $form->error($model, 'bkg_contact_no'); ?>
							</div>
						</div>
					</div>
					<div class="col-xs-12 col-sm-6 pl20 pr20">      
						<?= $form->emailFieldGroup($model, 'bkg_user_email', array('label' => 'Email Address', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Email Address", "autocomplete" => "section-new", 'id' => CHtml::activeId($model, "bkg_user_email1"), 'value' => $loggedinemail]))) ?>                      
					</div>                
				</div>
			</div>

		</div>

	</div>
	<div class="col-md-2"><?php $this->renderPartial("bkBanner", ['model' => $model]); ?></div>
</div>
<?
if ($model->bkg_booking_type == 4)
{
	?>
	<div class="row">
		<div class="col-sm-11 marginauto float-none pl30">
			<?= $form->radioButtonListGroup($model, 'bkg_transfer_type', array('label' => 'Pickup Type', 'widgetOptions' => array('data' => Booking::model()->transferTypes), 'inline' => true)) ?>
		</div>
	</div>
	<?
}
else if ($model->bkg_booking_type == 1)
{
	?>
	<?= $form->hiddenField($model, 'bkg_transfer_type', ['id' => 'bkg_transfer_type1', 'value' => 0]); ?>
<? } ?>
<script>
    $(document).ready(function () {
<?php
if ($model->bkg_booking_type == 4 && $model->bkg_transfer_type == 0)
{
	?>
	        $('#BookingTemp_bkg_transfer_type_0').attr('checked', true);
<?php } ?>
    });
</script>



