<?php
//$this->layout	 = 'column_booking';
$version		 = Yii::app()->params['siteJSVersion'];
$version		 = '';
?>
<script>
$jsBookNow = new BookNow();
</script>
<?php
	$loginMainDivClass = 'content-boxed-widget';
	$loginSubDivClass = 'pb30';

	if (Yii::app()->user->isGuest)
	{
		$loginMainDivClass = 'content-boxed-widget';
		$loginSubDivClass = '';
		$uname		 = '';
		$isLoggedin	 = false;
		$this->renderPartial("bkInfoLogin" . $this->layoutSufix, ['model' => $model, 'id' => '0']);
	} 
	else 
	{		
		$uname		 = Yii::app()->user->loadUser()->usr_name;
		$isLoggedin	 = true;
	}	
?>
<div class="<?php echo $loginMainDivClass ?>" id="hideDetails">
	<h4 class="text-center mb0">Your contact details</h4>
	<p class="text-center sub-heading1">(these will be used to send you quotes and booking updates)</p>
	
	<div class="bottom-0">
			<?php
			$loggedinemail	 = "";
			$loggedinphone	 = "";
			if (!Yii::app()->user->isGuest)
			{
				$loggedinemail	 = Yii::app()->user->loadUser()->usr_email;
				$loggedinphone	 = Yii::app()->user->loadUser()->usr_mobile;
			}
			?>	
			<div class="input-simple-1 has-icon input-blue bottom-20 mt10">
				<em class="color-gray">Phone Number (incl. country code)</em>
			<?php $this->widget('ext.intlphoneinput.IntlPhoneInput', array(
				'model'=>$model,
				'attribute'=>'fullContactNumber',
				'codeAttribute'=>'bkg_country_code',
				'numberAttribute'=>'bkg_contact_no',
				'options'=>array( // optional
				   'separateDialCode'=>true,
				   'autoHideDialCode'=>true,
				   'initialCountry'=>'in'
				),
				'htmlOptions'=>['class'=>'form-control','value' => $loggedinphone],
				'localisedCountryNames'=>false, // other public properties
			)); ?>
				<?//= $form->textField($model, 'bkg_contact_no', array('placeholder' => "Primary Mobile Number", 'class' => 'form-control', 'value' => $loggedinphone)) ?>
				<?= $form->error($model, 'bkg_country_code'); ?>
				<?= $form->error($model, 'bkg_contact_no',['class' => 'help-block error']); ?>
			</div>
		</div>
		<?php if($model->bkg_booking_type == 1){ ?>
			<?= $form->hiddenField($model, 'bkg_transfer_type', ['id' => 'bkg_transfer_type1', 'value' => 0]); ?>
		<?php } ?>

<!--			<div class="input-simple-1 has-icon input-blue bottom-10"><em class="color-gray">Email</em>
				<?//= $form->emailField($model, 'bkg_user_email',['placeholder' => "Email Address", 'id' => CHtml::activeId($model, "bkg_user_email1"), 'value' => $loggedinemail]) ?>                     
				<?//= $form->error($model, 'bkg_user_email',['class' => 'help-block error']); ?>
		</div>-->
	
</div>
<script>
$(document).ready(function(){
	<?php if($model->bkg_booking_type == 4 && ($model->bkg_transfer_type == 0 || $model->bkg_transfer_type == 1)){?>
		$('#BookingTemp_bkg_transfer_type_0').attr('checked',true);
		$('#BookingTemp_bkg_transfer_type_0').click();
	<?php 	}else{ ?>
		$('#BookingTemp_bkg_transfer_type_1').attr('checked',true);
		$('#BookingTemp_bkg_transfer_type_1').click();
	<?php } ?>
});
$('input[name="BookingTemp[bkg_transfer_type]"]').change(function(event){ 
	    var trasferType = $(event.currentTarget).val();
		var dlabel = (trasferType == 2)?'Pickup Address':'Drop Address';
		var slabel = (trasferType == 1) ?  'Pickup Address' : 'Drop Address';
		if(trasferType == 1){
			$('#BookingRoute_airport-selectized').attr("placeholder", "Select Airport");
		}
		if(trasferType == 2){
			$('#BookingRoute_airport-selectized').attr("placeholder", "Select Airport");
		}
		$('#slabel').text(slabel);
		$('#dlabel').text(dlabel);
		$('#trslabel').text(slabel);
		$('#trdlabel').text(dlabel);
	});
</script>
