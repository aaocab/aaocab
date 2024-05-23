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
				<div class="form-group valueBox">
				   <label  class="col-10 offset-1 col-lg-8 offset-lg-2 control-label ">Enter <?= $contactLabel ?>: </label>
<!--				   <input value="" placeholder="<?//= $contactLabel ?>"   class="form-control <?//= $contactClass ?>">-->
				<?php 
					if($type==1)
					{
                        $class = "saveEmailContact";
				?>
				   <div class="col-10 offset-1 col-lg-8 offset-lg-2"><?= $form->emailField($emailModel, 'eml_email_address', ['placeholder' => "Email Address", 'class' => 'form-control m0 email-boxs', 'required' => true]) ?></div>
					<div class="emailerror text-center text-danger"></div>
                       <?php }else{?>
				   <div class="col-10 offset-1 col-lg-8 offset-lg-2 text-center phn_phone">
						<?php
						//$bookingModel->bkgUserInfo->fullContactNumber= $bookingModel->bkgUserInfo->bkg_contact_no;
						$class = "saveContact";
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
            <input type="hidden" id="cttId" name="cttId" value="<?=$model->ctt_id?>">
             <input type="hidden" id="type" name="type" value="<?=$type?>">
             <div class="error text-center text-danger"></div>
              <div class="success text-center text-success"></div>
			<input  value="Submit" name="yt0" id="saveContact" class="btn btn-primary pl-5 pr-5 <?= $class ?>">
		</div>
    </div>
</div> 
<div class="panel panel-default correctotp"></div>
<?php $this->endWidget(); ?>
<script>
$('.saveContact').click(function (event) {
     
        var profcontact = $.trim($('#fullContactNumber').val());
        var cont = profcontact.replace(/\s/g, '');
        if (profcontact == "")
        {
            $('.phnerror').text('Mobile no cannot be blank');
           $('.error').text('');
            return false;
        } else if (cont.length < 7 || cont.length > 12 || isInteger(profcontact) == false)
        {
           // debugger;
            $('.phnerror').text('Invalid mobile no!');
             $('.error').text('');
            return false;
        } 
        else
        {
            saveContact(2);
        }
    });
    $('.saveEmailContact').click(function (event) {
        var ck_email = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/;
        if ($("#ContactEmail_eml_email_address").val() == '' || $("#ContactEmail_eml_email_address").val() == 'undefined')
		{
			 $('.emailerror').text('Email address cannot be blank.');
              $('.error').text('');
			return false;
		}
		else if (!ck_email.test($("#ContactEmail_eml_email_address").val()))
		{
			 $('.emailerror').text('Invalid Email address!');
              $('.error').text('');
			return false;
		}
        else{
        
            saveContact(1);
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
    
     function saveContact(type)
    {
       
     
        var baseUrl = "<?= Yii::app()->getBaseUrl(true) ?>";
        var form = $("form#addContact-form");
        $.ajax({
            "type": "POST",
            "dataType": "json",
            "url": baseUrl + '/contact/addContactDetails',
            data: form.serialize() + "&YII_CSRF_TOKEN=<?= Yii::app()->request->csrfToken ?>",
            "data": $(form).serialize(),
            "beforeSend": function ()
            {
                //blockForm(form);
            },
            "complete": function ()
            {
                //unBlockForm(form);
            },
            "success": function (data2)
            {
             //  debugger;
                if (data2.success)
                {
                    if(data2.data && type === 1)
                    {
                         $('.error').text("");
                        $('.valueBox').hide();
                        $('.saveEmailContact').hide();
                        $('.success').text("Successfully added in your account");
                        setTimeout(closeBox, 3000);
                    }
                    if (data2.data.isNew && type === 2) {
                         $('.error').text("");
                        $('.valueBox').hide();
                        $('.saveContact').hide();
                        $('.success').text("Successfully added in your account");
                        setTimeout(closeBox, 3000);
                    }
                } else
                {
                   //debugger;
                    var errors = data2.errors;
                    var errorCode = data2.errorCode;
                    var info = data2.errors;
                    if (errorCode === 1001 || errorCode === 1002 || errorCode === 105)
                    {
                        $('.error').text(info);
                        $('.emailerror').text('');
                        $('.phnerror').text('');
                    }
                }

            },
            error: function (xhr, ajaxOptions, thrownError)
            {
               // debugger;
                var msg = "<ul class='list-style-circle'><li>" + xhr.status + ": " + thrownError + "</li></ul>";
                $(".error").html(msg);
               
            }
        });
    }
     function closeBox()
        {
                var baseUrl = "<?= Yii::app()->getBaseUrl(true) ?>";
                acctbox.modal('hide');
                location.href = baseUrl + '/users/view';
           
        }
</script>