
<style>
    .has-error .form-control {
        border-color: #e73d4a!important;
    }
</style>
<!-- END LOGO -->
<!-- BEGIN LOGIN -->
<div class="content">
    <!-- BEGIN LOGIN FORM -->
    <? if ($verifyEmail != 1) { ?>
        <?= CHtml::beginForm('', "post", ['id' => "formId", 'accept-charset' => "utf-8", 'class' => 'login-form']); ?>
        <div class="form-title">
            <span class="form-title"><?= $message ?></span>
        </div>

        <? if ($_REQUEST['emailverified'] == 1) { ?>
            <div class="alert alert-success">
                <button class="close" data-close="alert"></button>
                <span>You have successfully verified your email address.</span>
            </div>
        <? } ?>

        <div class="form-title">
            <span class="form-title">Welcome to Gozo Cabs.</span>
            <span class="form-subtitle"></span>
        </div>
        <div class="alert alert-danger display-hide">
            <button class="close" data-close="alert"></button>
            <span> Enter your username and password. </span>
        </div>
     <? if ($status == 'errortype') { ?>
       <div class="alert alert-danger">
            <button class="close" data-close="alert"></button>
            <span> Please select login type </span>
        </div>
    <? } ?>
        <? if ($status == 'error') { ?>
            <div class="alert alert-danger">
                <button class="close" data-close="alert"></button>
                <span> invalid username/password. </span>
            </div>
        <? } ?>
     <? if ($status == 'erroruser') { ?>
            <div class="alert alert-danger">
                <button class="close" data-close="alert"></button>
                <span> Agent is not registered. </span>
            </div>
        <? } ?>
        <div class="form-group form-subtitle">
            <span class="pull-left pb10"><input type="radio" name="agt_type" value="0">Login As Agent</span>  <span class="pull-right pb10"><input type="radio" name="agt_type" value="1">Login As Corporate</span>
        </div>
        <div class="form-group">
            <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
            <label class="control-label visible-ie8 visible-ie9">Username</label>
            <input class="form-control form-control-solid placeholder-no-fix" type="text" autocomplete="off" placeholder="Username" name="txtUsername" id="txtUsername" required="true"/> </div>
        <div class="form-group">
            <label class="control-label visible-ie8 visible-ie9">Password</label>
            <input class="form-control form-control-solid placeholder-no-fix" type="password" autocomplete="off" placeholder="Password" name="txtPassword" id="txtPassword" required="true"/> </div>
        <div class="form-actions">
            <button type="submit" class="btn red btn-block uppercase">Login</button>
        </div>
        <div class="form-actions">
            <div class="pull-right forget-password-block">
                <a href="javascript:;" id="forget-password" class="forget-password">Forgot Password?</a>
            </div>
        </div>
        <?= CHtml::endForm() ?>

        <!-- END LOGIN FORM -->
        <!-- BEGIN FORGOT PASSWORD FORM -->
        <form class="forget-form" action="<?php echo Yii::app()->createUrl('agent/index/forgotpassword', []); ?>" method="post">
            <input type="hidden" value="" id="csrf1" name="YII_CSRF_TOKEN" >
            <div class="form-title">
                <span class="form-title">Forget Password ?</span>
                <span class="form-subtitle" id="forgotemailerr" style="display: none"></span>
            </div>
            <div class="form-group">
                <input class="form-control email placeholder-no-fix" type="text" autocomplete="off" placeholder="Email" name="forgotemail" id="forgotemail" /> </div>
            <div class="form-actions">
                <button type="button" id="back-btn" class="btn btn-default">Back</button>
                <button type="submit" class="btn btn-primary uppercase pull-right" onclick="return validateForgotEmail();">Submit</button>
            </div>
        </form>
    <? } else { ?>
        <div class="col-xs-12 text-center mb5">
            <div class="alert alert-success">
                Verification code has been sent to registered email address.
            </div>     
        </div>
        <input type="hidden" name="emailtoverify" id="emailtoverify" value="<?= $emailtoverify ?>">
        <div class="col-xs-12 text-center mb5">
            <div class="form-group">
                <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Enter verification code" name="emailvercode" id="emailvercode" /> </div>
        </div>
        <div class="col-xs-12 text-center">
            <button class="btn btn-danger" onclick="verifyEmail();">Submit</button>
        </div> 
    <? } ?>
    <!-- END FORGOT PASSWORD FORM -->
</div>
<div class="copyright hide"> 2017 © Gozo Technologies Private Limited.</div>
<!-- END LOGIN -->
<!--[if lt IE 9]>
<script src="/assets/mtnc/global/plugins/respond.min.js"></script>
<script src="/assets/mtnc/global/plugins/excanvas.min.js"></script> 
<script src="/assets/mtnc/global/plugins/ie8.fix.min.js"></script> 
<![endif]-->
<!-- BEGIN CORE PLUGINS -->
<script>
    $('#csrf1').val($('input[name="YII_CSRF_TOKEN"]').val());

    function validateForgotEmail() {
        $('#forgotemailerr').text('');
        if ($('#forgotemail').val() != "") {
            validateEmail();

        } else {
            $('#forgotemailerr').show();
            $('#forgotemailerr').text('Enter your e - mail to reset it.');
            return false;
        }


    }
    function validateEmail() {

        var email = $('#forgotemail').val();
        href = '<?= Yii::app()->createUrl('users/validateemail') ?>';
        jQuery.ajax({type: 'GET', data: {"email": email}, url: href, dataType: "json", async: false,
            success: function (data) {
                $('#forgotemailerr').text('');
                if (data.success) {
                    $('#forgotemailerr').hide();
                    return true;
                } else {
                    $('#forgotemailerr').show();
                    $('#forgotemailerr').text('Email is not registered.');
                    return false;
                }
            }
        });
    }

    function verifyEmail() {
        var code = $('#emailvercode').val();
        var email = $('#emailtoverify').val();
        href = '<?= Yii::app()->createUrl('users/verifyemail') ?>';
        jQuery.ajax({type: 'GET', data: {"code": code, "email": email}, url: href, dataType: "json",
            success: function (data) {

                if (data.success) {
                    location.href = '<?= Yii::app()->createUrl('agent/index/index', ['emailverified' => 1]) ?>';
                } else {
                    alert("Invalid Verification Code");
                }

            }
        });
    }

</script>
