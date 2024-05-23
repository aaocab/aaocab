

<?php
$callback	 = Yii::app()->request->getParam('callback', 'loadList');
$title		 = ($model->isNewRecord) ? "Add" : "Edit";
$js			 = "window.$callback;";


if (Yii::app()->request->isAjaxRequest)
{
	$cls = "";
}
else
{
	$cls = "col-lg-4 col-md-6 col-sm-8 col-10";
}
?>
<div class="<?= $cls ?> ml15 mr15 n">
	<div class="card no-b" id="signupDiv">
		<div class="card-header border-none text-center">

			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
			<h5 class="modal-title font-24 mt0 mb0" id="bkCommonModelHeader"><b>Sign Up to Gozocabs</b></h5>
			<p class="mb0">Already on Gozocabs? <a href="javascript:void(0)" class="showSignIn">Sign In</a></p>
		</div>
		<div class="card-body">
			<?php
			$form = $this->beginWidget('CActiveForm', array(
				'id'					 => 'psignup-form', 'enableClientValidation' => true,
				'clientOptions'			 => array(
					'validateOnSubmit'	 => true,
					'errorCssClass'		 => 'has-error',
					'afterValidate'		 => 'js:function(form,data,hasError){
                        if(!hasError){      
                            $.ajax({
                                "type":"POST",
                                "dataType":"json",
                                "url":"' . CHtml::normalizeUrl(Yii::app()->request->url) . '",
                                "data":form.serialize(),
                                "success":function(data1){
                                if(!$.isEmptyObject(data1) && data1.success==true){                                    
									$("#bkCommonModel2").modal("hide");
									$("#bkCommonModel").modal("hide");
									var userinfo = JSON.parse(data1.userdata);
                                    $userid = data1.id;				   
                                    ' . $js . '		
									$("#hideLogin").hide();
									if($("#hideDetails").hasClass("col-xs-12 col-sm-6 col-md-6 marginauto book-panel pb0"))
									{
										$("#hideDetails").removeClass("col-xs-12 col-sm-6 col-md-6 marginauto book-panel pb0");
										$("#hideDetails").addClass("col-xs-12 col-sm-9 col-md-7 book-panel pb0");
									}
									
									$("#userdiv").hide();
									$("#navbar_sign").html(data1.rNav);
									$("#hideLogin").hide();
									var login = new Login();
									var userinfo = JSON.parse(data1.userdata);
									login.fillUserform2(userinfo);
									login.fillUserform13(userinfo);
							    }
                                else{
                                    settings=form.data(\'settings\');
                                    $.fn.yiiactiveform.updateSummary(form, data1);
									$("#demop > li").remove();
									elements = data1.errors;
									elements.forEach(function(item, index) { 
										 newlistitem = document.createElement("li"); 
										 newlistitem.innerHTML = "" + item + "";
										 document.getElementById("demop").appendChild(newlistitem);
									});
									$("#demop").parent("div").css("display","block");
								
                                    }									
									},
                                });
                            }
                        }'
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
			/* @var $form CActiveForm */
			?>
			<div class="col-12">

<!--				<div class="col-12"><div><span style='color:#B80606;' class="showErrorN"></span></div>-->
                    <div class="col-12 alert alert-danger" style="font-size: 15px;display:none;" role="alert">
						<ul style="list-style-type:none;" id="demop">
						</ul>
			        </div>
                    <div class="row">

                        <div class="col-12 col-sm-6">
							<div class="form-group">
								<label class="control-label" for="Contact_ctt_first_name">First Name<span style="color: red;font-size: 15px;">*</span></label>
								<?= $form->textField($contactModel, 'ctt_first_name', ['placeholder' => "Enter your name", 'required' => "required", 'class' => "form-control nameFilterMask"]) ?>
								<?php echo $form->error($contactModel, 'ctt_first_name', ['class' => 'help-block error']); ?>
							</div>
                        </div>
                        <div class="col-12 col-sm-6">  
							<div class="form-group">           
								<label class="control-label" for="Contact_ctt_last_name">Last Name<span style="color: red;font-size: 15px;">*</span></label>
								<?= $form->textField($contactModel, 'ctt_last_name', ['placeholder' => "Enter your last name", 'required' => "required", 'class' => "form-control nameFilterMask"]) ?>
								<?php echo $form->error($contactModel, 'ctt_last_name', ['class' => 'help-block error']); ?>
							</div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-12 col-sm-6 ">         
							<div class="form-group">
								<label class="control-label" for="ContactEmail_eml_email_address">Email<span style="color: red;font-size: 15px;">*</span></label>
								<?= $form->emailField($emailModel, 'eml_email_address', ['placeholder' => "Email (will be used for login)", 'required' => "required", 'class' => "form-control"]) ?>
								<?php echo $form->error($emailModel, 'eml_email_address', ['class' => 'help-block error']); ?>
							</div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-6">
							<div class="form-group">
								<label class="control-label" for="ContactPhone_phn_phone_no">Mobile <span style="color: red;font-size: 15px;">*</span></label>
								<div class="isd-input">		
									<?php
									$this->widget('ext.intlphoneinput.IntlPhoneInput', array(
										'model'					 => $phoneModel,
										'attribute'				 => 'phn_phone_no',
										'codeAttribute'			 => 'phn_phone_country_code',
										'numberAttribute'		 => 'phn_phone_no',
										'options'				 => array(// optional
											'separateDialCode'	 => true,
											'autoHideDialCode'	 => true,
											'initialCountry'	 => 'in'
										),
										'htmlOptions'			 => ['class' => 'form-control', 'required' => 'required', 'id' => 'fullContactNumber2', 'value' => '', 'maxlength' => '15'],
										'localisedCountryNames'	 => false, // other public properties
									));
									?> 
                                </div>
							</div>
                        </div> 
                    </div>

                    <div class="row">
                        <div class="col-12 col-sm-6">   
							<div class="form-group">            
								<label class="control-label" for="Users_new_password">Password<span style="color: red;font-size: 15px;">*</span></label>
								<?= $form->passwordField($model, 'new_password', ['placeholder' => "Password", 'required' => "required", 'class' => "form-control"]) ?>
								<?php echo $form->error($model, 'new_password', ['class' => 'help-block error']); ?>
							</div>
                        </div>
                        <div class="col-12 col-sm-6">        
							<div class="form-group">
								<label class="control-label" for="Users_repeat_password">Repeat Password<span style="color: red;font-size: 15px;">*</span></label>                  
								<?= $form->passwordField($model, 'repeat_password', ['placeholder' => "Repeat Password", 'required' => "required", 'class' => "form-control"]) ?>
								<?php echo $form->error($model, 'repeat_password', ['class' => 'help-block error']); ?>
							</div>
                        </div>
						<div class="col-12 col-sm-6">
							<div class="form-group">
								<label>Referral Code</label>                                    
								<?= $form->textField($model, 'usr_referred_code', ['placeholder' => "Refferal Code", 'class' => 'form-control']) ?>
							</div>
                        </div> 
						<?php
						if (CCaptcha::checkRequirements())
						{
							?>  
							<div class="col-12 col-sm-12">
								<?php
								echo '<b>ARE YOU HUMAN?</b><br />' . $form->labelEx($model, 'verifyCode');
								?> 
								<div>
									<?php
									$this->widget('CCaptcha',array( 'clickableImage' => true,'captchaAction'=>'site/captcha' ));
									echo $form->error($model, 'verifyCode');
									echo '<br />' . $form->textField($model, 'verifyCode');
									?>
									<div>Please enter the letters as they are shown in the image above.<br/>
										Letters are not case-sensitive.
									</div>
								</div>
							</div>
							<?php }
						?> 
                    </div>
                    <div class="row">
                        <div class="col-12 text-center mt20">
                            <input class="btn btn-primary text-uppercase gradient-green-blue border-none" type="submit" value="REGISTER"  tabindex="4"/>
                        </div>
                    </div>
                </div>
				<?php $this->endWidget(); ?>
            </div>

        </div>
    </div> 
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $(".showSignIn").click(function () {
            $('.modal').modal('hide');
            $("#signinpopup").click();
        });
    });

    function validateCheckHandlerss() {
        if ($("#formId").validation({errorClass: 'validationErr'})) {
            return true;
        } else {
            return false;
        }
    }
</script>
