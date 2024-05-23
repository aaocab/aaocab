<style>
.phone-boxs{ padding-left: 82px!important;}
</style>
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
				   <label  class="col-10 offset-1 col-lg-8 offset-lg-2 control-label ">Enter <?= $contactLabel ?>: </label>
<!--				   <input value="" placeholder="<?//= $contactLabel ?>"   class="form-control <?//= $contactClass ?>">-->
				<?php 
					if($type==1)
					{
				?>
				   <div class="col-10 offset-1 col-lg-8 offset-lg-2"><?= $form->emailField($emailModel, 'eml_email_address', ['placeholder' => "Email Address", 'class' => 'form-control m0', 'required' => true]) ?></div>
					<?php }else{?>
				   <div class="col-10 offset-1 col-lg-8 offset-lg-2 text-center phn_phone">
						<?php
						//$bookingModel->bkgUserInfo->fullContactNumber= $bookingModel->bkgUserInfo->bkg_contact_no;
						$phoneClass = "saveContact";
						$this->widget('ext.intlphoneinput.IntlPhoneInput', array(
							'model'					 => $phoneModel,
							'attribute'				 => 'fullContactNumber',
							'codeAttribute'			 => 'phn_phone_country_code',
							'numberAttribute'		 => 'phn_phone_no',
							'options'				 => array(
								'separateDialCode'	 => true,
								'autoHideDialCode'	 => true,
								'initialCountry'	 => 'in'
							),
							'htmlOptions'			 => ['class' => 'yii-selectize selectized form-control phone-boxs', 'maxlength' => '10', 'id' => 'fullContactNumber' . $id],
							'localisedCountryNames'	 => false, // other public properties
						));
						?>
					</div>
					<div class="phnerror text-center text-danger"></div>
					<?php } ?>
				   <div class="text-danger messageContact" style="display:none"></div>
				</div>
			</div>            
		</div>
    <div class="">
		<div class="col-xs-12 text-center pb10">
			<input type="submit" value="Submit" name="yt0" id="saveContact" class="btn btn-primary pl-5 pr-5 <?= $phoneClass ?>">
		</div>
    </div>
</div> 
<?php $this->endWidget(); ?>
<script>
$('.saveContact').click(function (event) {
        var profcontact = $.trim($('#fullContactNumber').val());
        var cont = profcontact.replace(/\s/g, '');
        if (profcontact == "")
        {
            $('.phnerror').text('Mobile no cannot be blank');
            return false;
        } else if (cont.length < 10 || cont.length > 12 || isInteger(profcontact) == false)
        {
            $('.phnerror').text('Invalid mobile no!');
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