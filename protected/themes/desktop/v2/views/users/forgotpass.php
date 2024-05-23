<div class="mt20 sudipta" style="display: none" id="fPass">
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
        /* @var $form CActiveForm */
        ?>
        <div class="row">
            <div class="col-12 col-lg-10 offset-lg-1 mb30">
                <h2 class="font-18">Forgot your password?</h2>
                <p>Enter your email address to reset your password.</p>
                <div id="msg"></div>
                <div class="row">
                    <div class="col-8 pr0">
                        <div class="form-group">
                        <?= $form1->emailField($modelfg, 'user_email', ['required' => TRUE, 'placeholder' => "Email",'class'=>'form-control']) ?>
						<?php echo $form1->error($modelfg, 'user_email', ['class' => 'help-block error']); ?>
                        </div>
                    </div>
                    <div class="col-4"> 
                        <button class="btn-orange font-14 m0" onclick="return validateCheckHandler2(event)" type="submit" name="signin">Submit</button> 
                    </div>
                </div>


                <a class="btn btn-primary text-uppercase gradient-green-blue border-none" id="bKlogin" onclick="bKlogin()" style="float:none;">Return to Sign in</a>
            </div></div>
        <?php $this->endWidget(); ?>
    


</div>
<div class="panel panel-default" id="emailsucc" style="display: none">
    <div class="panel-heading">
        Password reset confirmation sent!
    </div>
    <div class="panel-body">
        <p>
            We've sent you an email containing a link that will allow you to reset your password for the next 24 hours.
        </p>
        <p>
            Please check your spam folder if the email doesn't appear within a few minutes.
        </p>
        <div class="btn btn-default">
            <a href="<?= Yii::app()->createUrl('users/signin') ?>" style="color: #000000;text-decoration: none">
                Return to sign in&nbsp;&nbsp;<i class="fa fa-arrow-right"></i>
            </a>
        </div>

    </div>
</div>


<script>

    function validateCheckHandler2(e) {
        forgotemail = $("#Users_user_email").val();


        $.ajax({
            url: '<?= Yii::app()->createUrl('Users/forgotpassword') ?>',
            dataType: "json",
            data: {"forgotemail": forgotemail}, "success": function (data) {
                if (data.status == 'true')
                {                   
                    $('#emailsucc').show(600);
                    $('#fPass').hide(); 
                    $("#msg").html('');
                }
                if (data.status == 'false')
                {
                    $("#msg").html('');
                    $("#msg").html('<p style="color:#ff0000;">Email does not exist</p>');
                    $("#txtfEmail").val('');
                    //isLoading = true;
                }
            }
        });
        e.preventDefault();
    }
</script>

