<div>
<?php
			/** @var CActiveForm $form */
			$form = $this->beginWidget('CActiveForm', array(
				'id'					 => 'login-form',
				'enableClientValidation' => false,
				'clientOptions'			 => array(
					'validateOnSubmit'	 => false,
					'errorCssClass'		 => 'has-error',
				),
				'enableAjaxValidation'	 => false,
				'errorMessageCssClass'	 => 'help-block',
				'htmlOptions'			 => array(
					'class'		 => 'form-horizontal',
					'onsubmit'	 => 'return $jsUserLogin.signIn(this);'
				),
			));
			//echo $form->errorSummary([$userModel], '', '', ["class" => 'alert alert-danger formMessages mb-1']);
			?>
    
    <p>  <span class="alertForm"></span></p>
    <br/>
    <span class="correctotp"></span>
    <br/>
    <a type="submit" class="btn btn-success glow w-200 position-relative continue ">Continue</a>
    <a type="submit" class="btn btn-danger glow w-200 position-relative notContinue">Cancel</a>
    
    
    <input type="hidden" id="signUpDt" name="signUpDt" value="">
    <input type="hidden" id="otpObj" name="otpObj" value="">
    <input type="hidden" id="existContactType" name="existContactType" value="">
    <input type="hidden" name="rdata" value="<?= $this->pageRequest->getEncrptedData() ?>">
    <input type="hidden" class="verifyData" id="verifyData" name="verifyData" value='<?php echo $verifyData;?>'>
    <input type="hidden" class="notContinueWidExist" id="notContinueWidExist" name="notContinueWidExist" value="">
    <?php $this->endWidget(); ?>
</div>
<script>
$jsUserLogin = new userLogin();
    $(document).ready(function ()
    {
	if($('#ContactPhone_phn_phone_no').val())
          {
	$('#fullContactNumber').attr('readonly', true);
           }

    $('.notContinue').click(function() {
        $(".notContinueWidExist").val(1);
			 createOTPObj();
	       // $("#signInStep2").show();
            $("#alertExist").hide();
            $(".alerterror").text("");
            $(".alerterror").hide();
            
        });
    });
    
    function createOTPObj()
    {
       
       var form = $("form#login-form");
		$.ajax({
			"type": "POST",
			"dataType": "html",
			"url": $baseUrl + '/users/createOTPObj',
			"data": form.serialize(),
			"beforeSend": function ()
			{
				blockForm(form);
			},
			"complete": function ()
			{
				unBlockForm(form);
			},
			"success": function (data1)
			{
               // debugger;
                
                let data = false;
                                var isJSON = false;
                                try
                                {
                                      
                                    data = JSON.parse(data1);
                                    isJSON = true;
                                } catch (e)
                                {
                                    isJSON = false;
                                }
				unBlockForm();
				if (!isJSON)
				{
                   //debugger;
                    
					$("#loginForm").hide();
					$("#googleBlock").hide();
					$("#PasswordForm").hide();
					$(".signInStep2").html(data1);
					$(".signInStep2").show();
                     var notContinueWidExist = $(".notContinueWidExist").val();
                     if(notContinueWidExist!=1)
                     {
                       //  debugger;
                    $('#ContactEmail_eml_email_address').attr('readonly', true);
                    $('#fullContactNumber').attr('readonly', true);
                     }else{
                          $('#ContactEmail_eml_email_address').attr('readonly', false);
                    $('#fullContactNumber').attr('readonly', false);   
                     }
                    $(".alerterror").text("");
                    $(".alerterror").hide();  
                    $("#alertExist").hide();
                    $("#directLoginOTP").val(1);
                    $(".loginByRegister").show();
                    $(".Register").hide();
                                   
				}
                else{
                          var dta =    JSON.parse(data1);
                          if(dta.success == false)
                          {
                             $(".correctotp").html(dta.errors);
                             return false;
                          }
                            var dt =   dta.data;
                            dt.success;
						    var userType = dt.userType;
						$(".alerterror").text("");
                        $(".alerterror").hide();
						$("#SignUpOtpWithPersonalDetails").show();
                        $("#alertExist").hide();
						$("#login-form INPUT[name=rdata]").val(dt.rdata);
                        $("#signup-form INPUT[name=rdata]").val(dt.rdata);
                        $("[name=verifyData]").val(dt.verifyData);
                       
                        $("#newContactComponent").val(dt.newContactComponent);
                            if(dt.newContactComponent)
                                {
                                    $("#resendExistingContactOTP").val(1);
                                }
						$("#otpObject").val(dt.otpObject);
						if (userType === "1")
						{
                            $(".userName").text(dt.userName);
                            $('#ContactEmail_eml_email_address').val(dt.userName);
							$('#ContactEmail_eml_email_address').attr('readonly', true);
						} else {
                            $(".userName").text(dt.userNameCode + dt.userNamePhone);
                            $('#fullContactNumber').val(dt.userNameCode + dt.userNamePhone);
							$('#fullContactNumber').attr('readonly', true);
						}
                                                $("#number1").val('');
                                                $("#number2").val('');
                                                $("#number3").val('');
                                                $("#number4").val(''); 
                                                $("#directLoginOTP").val(1);
                                                $(".loginByRegister").show();
                                                $(".Register").hide();
                                            }
                                           
		},
			error: function (xhr, ajaxOptions, thrownError)
			{
				var msg = "<ul class='list-style-circle'><li>" + xhr.status + ": " + thrownError + "</li></ul>";
				$(".correctotp").html(msg);
				$(".correctotp").removeClass("hide");
			}
		});
		return false;
	}
    $('.continue').click(function() {
        createOTPObj();


    });
    
 
</script>