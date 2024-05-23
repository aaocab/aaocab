<?
$version = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/bookNow.js?v=' . $version);
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/fontawesome-web/css/all.min.css?v0.6');
?>
        <div class="content-boxed-widget page-login page-login-full widget-style-2 widget-content-bg" id="fPass">
            <h3 class="top-10 text-style-2 text-left bottom-0">Forgot password?</h3>
            <p class="color-gray bottom-20"> No worries! You will receive a password reset link on registered email address.</p>
			 <div id="forgot">             
                    <?php
                $form1 = $this->beginWidget('CActiveForm', array(
                    'id' => 'uforgotpass-form', 'enableClientValidation' => true,
                    'clientOptions' => array(
                        'validateOnSubmit' => true,
                        'errorCssClass' => 'has-error'
                    ),
                    // Please note: When you enable ajax validation, make sure the corresponding
                    // controller action is handling ajax validation correctly.
                    // See class documentation of CActiveForm for details on this,
                    // you need to use the performAjaxValidation()-method described there.
                    'enableAjaxValidation' => false,
                    'errorMessageCssClass' => 'help-block',
                    //'action' => Yii::app()->createUrl('users/forgot'),
                    'htmlOptions' => array(
                        'class' => 'form-horizontal',
                    ),
                ));
               
                ?>     
                        <div id="msg"></div>
                        <div class="page-login-field input-simple-1 has-icon input-blue bottom-30">
                            <?= $form1->emailField($modelfg, 'user_email', array('label' => '', 'placeholder' => 'Email ID', 'class' => 'pl15', 'widgetOptions' => array('htmlOptions' => ['required' => TRUE, 'placeholder' => "Email"]))) ?>
                        </div>
                        <div class="mb40 mt20"> 
                            <button class="btn-submit" onclick="return validateCheckHandler2(event)" type="submit" name="signin">Submit</button><img src="/images/right.svg" width="45" alt="" class="pull-right">  
                        </div>
                    <?php $this->endWidget(); ?>
			</div>       
        </div>
		<div class="content-boxed-widget text-center" id="emailsucc" style="display:none;">
			<div class="content p0 bottom-0 color-green3-dark font-16">
					Password reset confirmation sent!
			</div>
			<p class="bottom-10">We've sent you an email containing a link that will allow you to reset your password for the next 24 hours.</p>
			<p class="bottom-10">Please check your spam folder if the email doesn't appear within a few minutes.</p>
		</div>
		<div class="content-boxed-widget p20 text-center" style="display:none;">
                    <a class="uppercase btn-green color-white pl15 pr15 default-link" href="<?= Yii::app()->createUrl('users/signin') ?>" style="color: #000000;text-decoration: none">
                        Return to sign in&nbsp;&nbsp;<i class="fa fa-arrow-right"></i>
                    </a>
		</div>



<script>
	$jsBookNow = new BookNow();
    function validateCheckHandler2(e) {
        forgotemail = $("#Users_user_email").val();
		if(forgotemail == ''){
			$jsBookNow.showErrorMsg("Email cannot be blank");
			return false;
		}
		if (!$jsBookNow.validateEmail(forgotemail))
        {
            $jsBookNow.showErrorMsg("Email is not valid");
            return false;
        }
        $.ajax({
            url: '<?= Yii::app()->createUrl('Users/forgotpassword') ?>',
            dataType: "json",
            data: {"forgotemail": forgotemail}, "success": function (data) {
				//console.log(data);
                if (data.status == 'true')
                {
                    $('#emailsucc').show(600);
                    $('#fPass').hide();
                    $("#msg").html('');
                }
                if (data.status == 'false')
                {
                    $("#msg").html('');
					$jsBookNow.showErrorMsg("Email does not exist");
                }
            }
        });
        e.preventDefault();
    }
</script>