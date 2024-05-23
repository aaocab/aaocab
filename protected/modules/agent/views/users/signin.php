
<style>
    .has-error .form-control {
        border-color: #e73d4a!important;
    }
</style>
<!-- END LOGO -->
<!-- BEGIN LOGIN -->
<div class="content">
    <!-- BEGIN LOGIN FORM -->
    <div class="form-group form-subtitle">
        <?
        if ($status == 'errorAgentOrCorp') {
            echo "Login For<br><br>";

            foreach ($arrAgentTypes as $value) {
                $arrAgentType = Agents::model()->getAgentType($value['type']);
                if($value['type']==1){
                    $corpCode = $value['corpcode']."-".$arrAgentType;
                }else{
                   $corpCode = $value['agentid']."-".$arrAgentType;
                }
                $strCompany =($value['company']!='')?$value['company']." (".$corpCode.")":$value['name']." (".$corpCode.")";
                
                ?>
                <span class="pb10"><input type="radio" name="agt_type" value="<?= $value['id'] ?>"><?=$strCompany?></span><br><br>
            <? } ?>
            <div class="form-group form-subtitle"> <button type="button" class="btn btn-xs red uppercase" onclick="loginAgent();">Proceed</button></div>
        <? } ?>
    </div>


    <?
    $hideLogin = ($status == 'errorAgentOrCorp') ? ['class' => 'login-form hide'] : ['class' => 'login-form'];
    ?>
    <?= CHtml::beginForm('', "post", ['id' => "formId", 'accept-charset' => "utf-8"] + $hideLogin); ?>
    <div class="form-title">
        <span class="form-title"><?= $message ?></span>
    </div>

    <div class="row">
        <p class="form-title text-center">Welcome to Gozo Cabs.</p>
        <p class="form-subtitle" style="color: #f3cd36"><?
            if ($_REQUEST['agtjoin'] == 1) {
                echo 'We have sent you an email with temporary credentials. You may please login and complete your registration.';
            }
            if ($_REQUEST['agtjoin'] == 2) {
                echo 'Congratulations! You are successfully registered as our corporate. You can login with your existing user credentials.';
            }
            if ($_REQUEST['agtjoin'] == 3) {
                echo 'Congratulations! You are successfully registered as our agent. You can login with your existing user credentials.';
            }
            ?>
            </p>
    </div>
    <div class="alert alert-danger display-hide">
        <button class="close" data-close="alert"></button>
        <span> Enter your username and password. </span>
    </div>

    <? if ($status == 'error') { ?>
        <div class="alert alert-danger">
            <button class="close" data-close="alert"></button>
            <span> invalid username/password. </span>
        </div>
    <? } ?>
    <? if ($status == 'resetpass') { ?>
        <div class="alert alert-success">
            <button class="close" data-close="alert"></button>
            <span > <strong>You have updated password successfully please login with your new password .</strong> </span>
        </div>
    <? } ?>
    <? if ($status == 'erroruser') { ?>
        <div class="alert alert-danger">
            <button class="close" data-close="alert"></button>
            <span> Agent/Corporate is not registered. </span>
        </div>
    <? } ?>
    <?
    $usr = ($status != '' && $status == 'errorAgentOrCorp') ? $usr : '';
    $psw = ($status != '' && $status == 'errorAgentOrCorp') ? $psw : '';
    ?>
    <div class="form-group">
        <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
        <label class="control-label visible-ie8 visible-ie9">Username</label>
        <input class="form-control form-control-solid placeholder-no-fix" type="text" autocomplete="off" placeholder="Username" name="txtUsername" id="txtUsername" required="true" value="<?= $usr ?>"/> </div>
    <div class="form-group">
        <label class="control-label visible-ie8 visible-ie9">Password</label>
        <input class="form-control form-control-solid placeholder-no-fix" type="password" autocomplete="off" placeholder="Password" name="txtPassword" id="txtPassword" required="true" value="<?= $psw ?>"/> </div>
    <div class="form-actions">
        <button type="submit" class="btn red btn-block uppercase">Login</button>
    </div>
    <div class="form-actions">
		<div class="pull-left forget-password-block">
            <a href="/agent/join" class="forget-password">Become a Gozo Agent</a>
        </div>
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
    function loginAgent() {

        if (!$("input[name='agt_type']").is(':checked')) {
            alert('Please select agent type');
        }
        else {
            // var agtType = $("input[name='agt_type']:checked").val();
            var agtType = $("input[type='radio'][name='agt_type']:checked").val();
            var YII_CSRF_TOKEN = $('input[name="YII_CSRF_TOKEN"]').val();
            href = '<?= Yii::app()->createAbsoluteUrl('agent/users/signin') ?>';
            jQuery.ajax({type: 'POST', data: {"agtType": agtType, "txtUsername": $('#txtUsername').val(), "txtPassword": $('#txtPassword').val(), 'YII_CSRF_TOKEN': YII_CSRF_TOKEN}, url: href, dataType: "json", async: false,
                success: function (data) {
                    if (data.success) {
                        location.href = data.url;
                    }
                    if (!data.success) {
                        alert(data.message);
                    }
                }
            });
        }

    }


</script>
