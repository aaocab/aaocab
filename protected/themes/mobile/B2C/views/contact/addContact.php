<?php
$phoneClass = '';
$contactLabel = ($type == 1)?"Email Id" : "Phone No";
		$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
					'id'					 => 'addContact-form',
					'enableClientValidation' => true,
					'clientOptions'			 => array(
						'validateOnSubmit'	 => true,
						'errorCssClass'		 => 'has-error',
						'afterValidate'		 => 'js:function(form,data,hasError){
                    if(!hasError){				
								if(!checkValidation())
								{
								   return false;
								}
								
							}
                    }'
					),
					'enableAjaxValidation' => false,
					'errorMessageCssClass'	 => 'help-block',
					'htmlOptions'			 => array(
						'class' => 'form-horizontal'
					),
				));
		/* @var $form TbActiveForm */
		?>
<div class="panel panel-default">
		<div class="row">
			<div class="col-12">
				<div class="form-group">
				   <label  class="control-label mt15">Enter <?= $contactLabel ?>: </label>
<!--				   <input value="" placeholder="<?//= $contactLabel ?>"   class="form-control <?//= $contactClass ?>">-->
				<?php 
					if($type==1)
					{
				?>
				   <div class="input-simple-2 has-icon input-green bottom-15"><?= $form->emailField($emailModel, 'eml_email_address', ['placeholder' => "Email Address", 'class' => 'bottom-15', 'required' => true]) ?></div>
					<?php }else{?>
				   <div class="input-simple-2 has-icon input-green bottom-15 phn_phone">
						<?php
						//$bookingModel->bkgUserInfo->fullContactNumber= $bookingModel->bkgUserInfo->bkg_contact_no;
						$phoneClass = "saveContact";
						$this->widget('ext.intlphoneinput.IntlPhoneInput', array(
							'model'					 => $phoneModel,
							'attribute'				 => 'fullContactNumber',
							'codeAttribute'			 => 'phn_phone_country_code',
							'numberAttribute'		 => 'phn_phone_no',
							'options'				 => array(// optional
								'separateDialCode'	 => true,
								'autoHideDialCode'	 => true,
								'initialCountry'	 => 'in'
							),
							'htmlOptions'			 => ['class' => 'yii-selectize selectized form-control', 'id' => 'fullContactNumber' . $id],
							'localisedCountryNames'	 => false, // other public properties
						));
						?>
					</div>
					<?php } ?>
				</div>
			</div>            
		</div>
    <div class="">
		<div class="col-xs-12 text-center pb10">
			<input type="submit" value="Submit" name="yt0" id="saveContact" class="button shadow-medium button-blue <?= $phoneClass ?>">
		</div>
    </div>
</div> 
<?php $this->endWidget(); ?>
<script>
$jsBookNow = new BookNow();
$('.saveContact').click(function (event) {
        var profcontact = $.trim($('#fullContactNumber').val());
        var cont = profcontact.replace(/\s/g, '');
        if (profcontact == "")
        {
            $jsBookNow.showErrorMsg('Mobile no cannot be blank');
            return false;
        } else if (cont.length < 10 || cont.length > 12 || isInteger(profcontact) == false)
        {
            $jsBookNow.showErrorMsg('Invalid mobile no!');
            return false;
        } else
        {
            return true;
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