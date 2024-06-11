<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/fontawesome-web/css/all.min.css?v0.6');
?>

<?
$version = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/bookNow.js?v=' . $version);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/login.js?v=' . $version);

$callback = Yii::app()->request->getParam('callback', '');
if($callback != '')
{
	$js = "window.{$callback};";
}
?>

<div class="content-boxed-widget page-login page-login-full pl10 pr10">
	<div class="tabs">
		<div class="tab-titles">
			<a href="#" class="uppercase bold active-tab-button" data-tab="tab-7">Log In</a>
			<a href="#" data-tab="tab-8" class="uppercase bold">Create Account</a>
		</div>
		<div class="tab-content">
			<div class="tab-item active-tab" id="tab-7" style="display: block;">
				<?php
                $form = $this->beginWidget('CActiveForm', array(
                    'id' => 'ulogin-form', 'enableClientValidation' => true,
                    'clientOptions' => array(
                        'validateOnSubmit' => true,
                        'errorCssClass' => 'has-error',
                        'afterValidate' => 'js:function(form,data,hasError){
                        if(!hasError){             
                            $.ajax({
                                "type":"POST",
                                "dataType":"json",
                                "url":"' . CHtml::normalizeUrl(Yii::app()->request->url) . '",
                                "data":form.serialize(),
                                "success":function(data1){
								if(!$.isEmptyObject(data1) && data1.success==true){
								    var login = new Login();
									var userinfo = JSON.parse(data1.userdata);
									$(".loggiUser").html("Hi,&nbsp;"+userinfo.usr_name);
									$(".clsUserId").val(data1.user_id);
									login.fillUserform2(userinfo);					
									login.fillUserform13(userinfo);
                                    ' . $js . '									
								}
                                else{
                                    settings=form.data(\'settings\');
                                    var data = data1.data;
                                    if($("#email_1").val()!= "" && $("#pass_1").val() != ""){
									  $jsBookNow = new BookNow();
									  $jsBookNow.showErrorMsg(data.Users_usr_password[0]);
									  alert(data.Users_usr_password[0]);
									}
                                    $.each (settings.attributes, function (i) {
                                      $.fn.yiiactiveform.updateInput (settings.attributes[i], data, form);
                                    });
                                    $.fn.yiiactiveform.updateSummary(form, data1);
                                    }},
                                });
                            }
                        }'
                    ),
                    // Please note: When you enable ajax validation, make sure the corresponding
                    // controller action is handling ajax validation correctly.
                    // See class documentation of CActiveForm for details on this,
                    // you need to use the performAjaxValidation()-method described there.
                    'enableAjaxValidation' => false,
                    'errorMessageCssClass' => 'help-block',
                    //'action' => Yii::app()->createUrl('users/partialsigin'),
                    'htmlOptions' => array(
                        'class' => 'form-horizontal',
                    ),
                ));
                /* @var $form CActiveForm */
                ?>



				<?php echo CHtml::errorSummary($model); ?>
				<div style='color:#B80606;' class='pl10 pr10 text-center'>
					
				</div>
                            


                            <div class="line-height20 text-center uppercase mt10">
                                <a href="<?= Yii::app()->createUrl('users/oauth', array('provider' => 'Facebook')); ?>" class="button-round button-icon shadow-small regularbold bg-facebook button-s mr5"><i class="fab fa-facebook"></i> Facebook</a>

                                <a href="<?= Yii::app()->createUrl('users/oauth', array('provider' => 'Google')); ?>" class="button-round button-icon shadow-small regularbold bg-google button-s pl40 pr30 mr0"><i class="fab fa-google mr5"></i> Google</a>
                            </div>
                            <div class="decoration decoration-margins ml0 mr0 mt20"><span class="or_style">OR</span></div>
                            
				<div class="page-login-field top-0">
                    <i class="fas fa-envelope color-highlight"></i>
					<?= $form->emailField($emailModel, 'eml_email_address',  ['class' => 'mb0 form-control','id' => 'email_1','placeholder' => "Enter email"]) ?>
                    <?php echo $form->error($emailModel, 'eml_email_address', ['class' => 'help-block error']); ?>
					<em>(required)</em>
                </div>

                <div class="page-login-field bottom-10 top-20">
                    <i class="fa fa-lock color-highlight"></i>
                    <?= $form->passwordField($model, 'usr_password', ['class' => 'form-control','id' => 'pass_1', 'placeholder' => "Enter password"]) ?>
                    <?php echo $form->error($model, 'usr_password', ['class' => 'help-block error']); ?>
					<em>(required)</em>
                </div>

                <div class="page-login-links bottom-10">
                    <a class="create float-left" href="forgotpassword"><i class="fa fa-eye"></i>Forgot Password</a>
                    <div class="clear"></div>
                </div>
				<input class="button btn-green-blue button-full button-rounded button-sm uppercase ultrabold shadow-small"  type="submit" name="signin" value="LOGIN"/>
				<?php $this->endWidget(); ?>

				
            </div>

			<div class="tab-item" id="tab-8">
				<? $imgVer	 = Yii::app()->params['imageVersion']; ?>
				<!--page-login header-clear-large page-login-full-->
				<h3 class="ultrabold top-0 bottom-0 text-center">Sign Up to aaocab</h3>
				<p class="text-center mb0">Already on aaocab? <a href="#" class="alreadylogin">Log In</a></p>
				<div class="line-height20 text-center uppercase mt20">
					<a href="<?= Yii::app()->createUrl('users/oauth', array('provider' => 'Facebook')); ?>" class="button-round button-icon shadow-small regularbold bg-facebook button-s mr5"><i class="fab fa-facebook"></i> Facebook</a>

					<a href="<?= Yii::app()->createUrl('users/oauth', array('provider' => 'Google')); ?>" class="button-round button-icon shadow-small regularbold bg-google button-s pl40 pr30 mr0"><i class="fab fa-google"></i> Google</a>
				</div>

				<div class="decoration decoration-margins ml0 mr0 top-20"><span class="or_style">OR</span></div>
                <?php
                $form = $this->beginWidget('CActiveForm', array(
                    'id' => 'signup-form', 'enableClientValidation' => true,
                    'clientOptions' => array(
                        'validateOnSubmit' => true,
                        'errorCssClass' => 'has-error',
                        'afterValidate' => 'js:function(form,data,hasError){
                        if(!hasError){             
                            $.ajax({
                                "type":"POST",
                                "dataType":"json",
                                "url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('users/partialsignup?mobiletheme=1')) . '",
                                "data":form.serialize(),
                                "success":function(data1){
								if(!$.isEmptyObject(data1) && data1.success==true){
								    var login = new Login();
									var userinfo = JSON.parse(data1.userdata);
									$(".loggiUser").html("Hi,&nbsp;"+userinfo.usr_name);
									$(".clsUserId").val(data1.user_id);
									login.fillUserform2(userinfo);					
									login.fillUserform13(userinfo);
                                    ' . $js . '									
								}
                                else{
                                    settings=form.data(\'settings\');
                                    var data = data1.data;
									$jsBookNow = new BookNow();
									$jsBookNow.showErrorMsg(data.Users_usr_email[0]);
                                    $.each (settings.attributes, function (i) {
                                      $.fn.yiiactiveform.updateInput (settings.attributes[i], data, form);
                                    });
                                    $.fn.yiiactiveform.updateSummary(form, data1);
                                    }},
                                });
                            }
                        }'
                    ),
                    // Please note: When you enable ajax validation, make sure the corresponding
                    // controller action is handling ajax validation correctly.
                    // See class documentation of CActiveForm for details on this,
                    // you need to use the performAjaxValidation()-method described there.
                    'enableAjaxValidation' => false,
                    'errorMessageCssClass' => 'help-block',
                    //'action' => Yii::app()->createUrl('users/partialsigin'),
                    'htmlOptions' => array(
                        'class' => 'form-horizontal',
                    ),
                ));
                /* @var $form CActiveForm */
                ?>
				<div style="text-align:center; padding:0 20px ;">

                </div>
				<div class="input-simple-1 has-icon input-green bottom-20"><strong>Required</strong><em class="color-highlight">First name</em><i class="fas fa-user-alt"></i>
					<?= $form->textField($contactModel, 'ctt_first_name', array('label' => '', 'class'=>'nameFilterMask', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Enter your name"]))) ?>

				</div>
				<div class="input-simple-1 has-icon input-green bottom-20"><strong>Required</strong><em class="color-highlight">Last name</em><i class="fas fa-user-alt"></i>
					<?= $form->textField($contactModel, 'ctt_last_name', array('label' => '', 'class'=>'nameFilterMask', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Enter your last name"]))) ?>

				</div>
				<div class="input-simple-1 has-icon input-green bottom-20"><strong>Required</strong><em class="color-highlight">Email</em><i class="fas fa-envelope"></i>
					<?= $form->textField($emailModel, 'eml_email_address', array('label' => '', 'id' => 'email_2', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Email (will be used for login) "]))) ?>

				</div>
				<div class="bottom-0">
					<div class="input-simple-1 has-icon input-blue bottom-30"><strong>Required</strong><em class="color-highlight">Phone Number (incl. country code)</em><i class="fa fa-phone"></i>
						<div class="">

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
								'htmlOptions'			 => ['class' => 'form-control', 'id' => 'fullContactNumber'],
								'localisedCountryNames'	 => false, // other public properties
							));
							?> 
							<?php echo $form->error($phoneModel, 'phn_phone_country_code'); ?>
							<?php echo $form->error($phoneModel, 'phn_phone_no'); ?>
						</div>

						<div class="clear"></div>
					</div>
				</div>
				<div class="input-simple-1 has-icon input-green bottom-20"><strong>Required</strong><em class="color-highlight">Password</em><i class="fas fa-lock"></i>
					<?= $form->passwordField($model, 'new_password', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Password"]))) ?>

				</div>

				<div class="input-simple-1 has-icon input-green bottom-20"><strong>Required</strong><em class="color-highlight">Repeat Password</em><i class="fas fa-lock"></i>
					<?= $form->passwordField($model, 'repeat_password', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Repeat Password"]))) ?>

				</div>

				<div class="input-simple-1 has-icon input-green bottom-20"><em class="color-highlight">Referral Code</em>
					<?
					$cookieReferredCode = Yii::app()->request->cookies['gozo_referred_code']->value;
					if ($model->usr_referred_code != '')
					{
						$model->usr_referred_code = $model->usr_referred_code;
					}
					else if ($cookieReferredCode != '')
					{
						$model->usr_referred_code = $cookieReferredCode;
					}
					?>
					<?= $form->textField($model, 'usr_referred_code', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Refferal Code"]))) ?>

				</div>
				<div class="text-center mb20">

					<?= CHtml::submitButton("REGISTER", ['class' => "uppercase btn-green-blue shadow-medium", 'tabindex' => "4"]); ?>
				</div>
				<?php $this->endWidget(); ?>
			</div>
		</div>
	</div>
</div>
<script>
    $('#sign-upp').click(function () {
        alert("fsdf");
        window.location = "<?= Yii::app()->getBaseUrl(true) ?>/signup";
        return false;
    });
</script>
<script type="text/javascript">
    $jsBookNow = new BookNow();
    $(document).ready(function () {
    });
    function validateCheckHandlerss() {
        if ($("#formId").validation({errorClass: 'validationErr'})) {
            return true;
        } else {
            return false;
        }
    }


    $("#ulogin-form").submit(function (event) {
        var is_error = 0;
        var msg = "";
        var uemail = $("#email_1").val();
        if ($.trim(uemail) == "")
        {
            msg += "Email cannot be blank<br/>";
            is_error++;
        } else if (!$jsBookNow.validateEmail(uemail))
        {
            msg += "Email is not valid<br/>";
            is_error++;
        }

        if ($.trim($("#pass_1").val()) == "")
        {
            msg += "Password cannot be blank<br/>";
            is_error++;
        }

        if (is_error > 0) {
            $jsBookNow.showErrorMsg(msg);
            event.preventDefault();
		}
    });
    $("#signup-form").submit(function (event) {
        var is_error = 0;
        var msg = "";
        var uemail = $("#email_2").val();
        var usercontact = $.trim($('#fullContactNumber').val());
        var cont = usercontact.replace(/\s/g, '');

        if ($.trim($("#Contact_ctt_first_name").val()) == "")
        {
            msg += "First name cannot be blank<br/>";
            is_error++;
        }
        if ($.trim($("#Contact_ctt_last_name").val()) == "")
        {
            msg += "Last name cannot be blank<br/>";
            is_error++;
        }

        if ($.trim(uemail) == "")
        {
            msg += "Email cannot be blank<br/>";
            is_error++;
        } else if (!$jsBookNow.validateEmail(uemail))
        {
            msg += "Email is not valid<br/>";
            is_error++;
        }

        if (usercontact == "")
        {
            msg += 'Mobile no cannot be blank<br/>';
            is_error++;
        } else if (cont.length < 10 || cont.length > 12)
        {
            msg += 'Invalid mobile no<br/>';
            is_error++;
        } else if (isInteger(usercontact) == false) {
            msg += 'Invalid mobile no<br/>';
            is_error++;
        }

        if ($.trim($("#Users_new_password").val()) == "")
        {
            msg += "Password cannot be blank<br/>";
            is_error++;
        }
        if ($.trim($("#Users_repeat_password").val()) != $.trim($("#Users_new_password").val()))
        {
            msg += "Repeat Password does not match<br/>";
            is_error++;
        }



        if (is_error > 0) {
            $jsBookNow.showErrorMsg(msg);
            event.preventDefault();
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
	
	
	$('.alreadylogin').click(function(){
		$('a[data-tab="tab-7"]').addClass('active-tab-button');
		$('a[data-tab="tab-8"]').removeClass('active-tab-button');
		$('#tab-7').show();
		$('#tab-8').hide();
	});
	$('a[data-tab="tab-7"]').click(function(){
		$('a[data-tab="tab-7"]').addClass('active-tab-button');
		$('a[data-tab="tab-8"]').removeClass('active-tab-button');
		$('#tab-7').show();
		$('#tab-8').hide();
	});
	$('a[data-tab="tab-8"]').click(function(){
		$('a[data-tab="tab-8"]').addClass('active-tab-button');
		$('a[data-tab="tab-7"]').removeClass('active-tab-button');
		$('#tab-8').show();
		$('#tab-7').hide();
	});
</script>

