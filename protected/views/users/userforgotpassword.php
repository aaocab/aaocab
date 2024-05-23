<div class="row register_path">
    <div class="col-xs-12 col-sm-6 float-none marginauto book-panel2 mt20">
        <div class="panel panel-default" style="" id="fPass">
            <div class="panel-body" >
                <?php
                $form1 = $this->beginWidget('booster.widgets.TbActiveForm', array(
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
                /* @var $form TbActiveForm */
                ?>
                <div class="row">
                    <div class="col-xs-12 col-sm-8 float-none marginauto">
                        <p>Enter your email address to reset your password.</p>
                        <div id="msg"></div>
                        <div class="row pl15 pr15">
                            <div class="col-xs-8">
                                <?= $form1->emailFieldGroup($modelfg, 'user_email', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['required' => TRUE, 'placeholder' => "Email"]))) ?></div>
                            <div class="col-xs-4 pl0"> 
                                <button class="btn btn-info pull-left" onclick="return validateCheckHandler2(event)" type="submit" name="signin">Submit</button> 
                            </div>
                        </div>


                        <a href="/signin" style="float:none;" class="btn btn-info">Return to Sign in</a>
                    </div></div>
                <?php $this->endWidget(); ?>
            </div>


        </div>
    </div>
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