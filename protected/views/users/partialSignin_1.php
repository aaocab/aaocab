<?php
if (isset($_GET['ref']) && $_GET['ref'] != '')
{
	$refurl = urldecode(Yii::app()->request->getParam('ref'));
}
else
{
	//$refurl = $_SERVER['HTTP_REFERER'];
	$refurl = "";
}
$ref = isset($_REQUEST['ref']) ? Yii::app()->request->getParam('ref') : '';
?>
<!-- email varification confirm message section -->
<?php
if ($status == "valsucc")
{
	$emailvarifymsg = "Your IMPIND account is now active. Please login below. Complete your profile to make the most of IMPIND";
}
if ($status == "notvalidated")
{
	$emailvarifymsg = "Invalid verification link.";
}
if ($status == "succ")
{
	$emailvarifymsg = "We've resent you an email containing a link that will allow you to verify your email address and activate your account.";
}
if ($status == "alreadyact")
{
	$emailvarifymsg = "Your account is already activated.";
}
if ($status == "deactivated")
{
	$emailvarifymsg = "Your account is inactive.";
}
if ($status == 'warnlogin')
{
	$emailvarifymsg = "<b>Please login to request an estimate</b>";
}
?>

<div class="row mt50">
    <div class="col-md-12">
		<?php
		if ($emailvarifymsg != '')
		{
			?>
			<div style="text-align: center; padding: 50px 0 0 0;"><?= $emailvarifymsg ?></div>

			<?
		}
		?>
		<?php
		if ($signoutsucc == "msgsignout")
		{
			?>
			<div style="text-align: center; padding: 50px 0 0 0;">Your account has been signed out successfully.</div>

		<? } ?>
		<?php
		if ($signoutsucc == "succ")
		{
			?>
			<div style="text-align: center; padding: 50px 0 0 0;">Your password has been changed successfully.</div>

		<? } ?>
		<?php
		if ($_REQUEST['searchStatus'] == 1)
		{
			?>
			<div style="text-align: center; padding: 50px 0 0 0;">Please log in to request a job</div>

		<? } ?>
        <div class="row">
            <div class="col-xs-12">
				<?php
				$msg = '';
				if ($status == 'succ')
				{
					$msg = "";
				}
				elseif ($status == 'pusucc')
				{
					$msg = "Your password has been updated successfully. Please Login with your new password.";
				}
				else
				{

				}
				?>
                <p class="logtxtP"><?= $msg ?></p>

                <div class="panel panel-default" id="loginDiv" style="max-width: 350px">
                    <div class="panel-heading"><h2>Login</h2></div>
                    <div class="panel-body">
                        <form method="post" id="formId" action="<?= Yii::app()->createUrl('users/signin'); ?>">
                            <input type="hidden" name="id" value="<?= $id ?>"/>
                            <input type="hidden" name="category" value="<?= $category ?>"/>
                            <input type="hidden" name="b_id" value="<?= $b_id ?>"/>
                            <input type="hidden" name="type" value="<?= $type ?>"/>
                            <input type="hidden" name="refurl" value="<?= $ref ?>"/>
                            <input type="hidden" name="business_id" value="<? echo $bid = (isset($_REQUEST['business_id'])) ? Yii::app()->request->getParam('business_id') : ''; ?>"/>

							<?
							if ($status == 'error')
							{
								echo "<span style='color:#B80606;'>You have entered an invalid email address or a password. Please enter correct details.</span>";
							}
							elseif ($status == "emailerror")
							{
								echo "<span style='color: #000000;'>You have not verified your email address yet.</span>" . "<br><span style='color: #000000'>Go to your inbox and click on the activation link in the email we sent you to activate your account.</span>";
								?>
								<br><a href="<?= Yii::app()->createUrl('Users/verification', array('id' => $id)) ?>">click here to send activation link again</a><br><br>
								<?
							}
							?>

                            <div class="form-group">
                                <input type="text" name="txtEmail" id="txtEmail" class="form-control" placeholder="Email" validation="blank|Please enter email"/>
                                <div e_rel="txtEmail" class="warnText"></div>
                            </div>
                            <div class="form-group">
                                <input type="password" name="txtPass" id="txtPass" class="form-control" placeholder="Password" validation="blank|Please enter password"/>
                                <div e_rel="txtPass" class="warnText"></div>
                            </div>
                            <div class="form-group">
                                <a class="forgotpass2" onclick="bKloginHandler()">Forgot my password?</a>
                            </div>
                            <div class="panel-footer p10" style="text-align: center">
                                <input class="btn btn-info" onclick="return validateCheckHandler()" type="submit" name="signin" value="Sign In"/>

                                <hr class="m10">
                                <div style="text-align: center">
                                    <label class="mr10"><strong>Connect with</strong></label><a class="btn btn-lg btn-social btn-facebook pl15 pr15" href="<?= Yii::app()->createUrl('users/oauth', array('provider' => 'Facebook')); ?>"><i class="fa fa-facebook"></i></a>
                                    <a class="btn btn-lg btn-social btn-googleplus pl15 pr15" href="<?= Yii::app()->createUrl('users/oauth', array('provider' => 'Google')); ?>"><i class="fa fa-google-plus"></i></a>
                                    <a class="btn btn-lg btn-social btn-linkedin pl15 pr15" href="<?= Yii::app()->createUrl('users/oauth', array('provider' => 'LinkedIn')); ?>"><i class="fa fa-linkedin"></i></a>
                                </div>


                            </div>
                        </form>
                    </div>


                </div>
                <div style="max-width: 350px;text-align: center">

                    <a href="<?= Yii::app()->createUrl('users/create'); ?>" style="text-decoration: underline">Create an account</a>

                </div>
                <div class="row"  style="max-width: 350px">
                    <div style="text-align: center">


                    </div>
                </div>
                <div class="panel panel-default mt30" id="fPass" style="display:none; max-width: 400px">
                    <div class="panel-body">
                        <form method="post" id="formId2" action="">
                            <h1 style="font-size:24px;">Forgot your password?</h1>
                            <p>Enter your email address to reset your password.</p>
                            <div id="msg"></div>
                            <div class="form-group">
                                <input type="text" name="txtfEmail" id="txtfEmail" class="form-control" placeholder="Email" validation="blank|Please enter email"/>
                                <div e_rel="txtfEmail" class="warnText"></div>
                            </div>

                            <div class="panel panel-footer p10">
                                <input class="btn btn-info pull-right" onclick="return validateCheckHandler2(event)" type="submit" name="signin" value="Submit"/>
                                <a class="btn btn-default" id="bKlogin" style="float:none;">Return to Sign in</a>
                            </div>
                        </form></div>
                </div>
                <div class="panel panel-default" id="successforgot" style="display: none">
                    <div class="panel-heading">
                        Password reset confirmation sent!
                    </div>
                    <div class="panel-body">
                        <p>
                            We've sent you an email containing a link that will allow you to reset your password for the next 24 hours.
                        </p>
                        <p>
                            Please check your spam folder if the email doesn’t appear within a few minutes.
                        </p>
                        <div class="btn btn-default">
                            <a href="<?= Yii::app()->createUrl('users/signin') ?>" style="color: #000000;text-decoration: none">
                                Return to sign in&nbsp;&nbsp;<i class="fa fa-arrow-right"></i>
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    var isLoading = true;
    $(document).ready(function () {

        $(document).mouseover(function () {
            $(".userPopup").fadeOut();
        });

        $("#forgotMyself").bind('click', forgotMyselfHandler);
        $("#bKlogin").bind('click', forgotMyselfHandler);
    });

    function validateCheckHandler() {
        if ($("#formId").validation({errorClass: 'validationErr'})) {
            return true;
        } else {
            return false;
        }
    }

    function bKloginHandler() {
        $("#msg").html('');
        $("#txtfEmail").val('');
        $('#loginDiv').slideDown(600, function () {
            $(this).hide();
        });
        $('#fPass').slideUp(600, function () {
            $(this).show();
        });
//         $("#loginDiv").slideDown();
//         $("#fPass").slideUp('slow');
    }

    function validateCheckHandler2(e) {
        e.preventDefault();
        var forgotemail = $("#txtfEmail").val();
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if (!regex.test(forgotemail)) {
            $("#msg").html('<p style="color:#ff0000;">Invalid Email</p>');
            return false;
        }
        $.ajax({
            url: '<?= Yii::app()->createUrl('users/forgotpass') ?>',
            dataType: "json",
            data: 'forgotemail=' + forgotemail,
            "success": function (data) {
                if (data.status == 1) {
                    $("#msg").html('');
                    $('#successforgot').show();
                    $('#fPass').hide();
                } else {
                    $("#msg").html('');
                    $("#msg").html('<p style="color:#ff0000;">Email does not exist</p>');
                    $("#txtfEmail").val('');
                    isLoading = true;

                }
            }
        });
    }

    function forgotMyselfHandler() {
        $('#fPass').slideDown(600, function () {
            $(this).hide();
        });
        $('#loginDiv').slideUp(600, function () {
            $(this).show();
        });
    }
    $(function () {
        $('.hoauthWidget a').click(function () {
            var signinWin;
            var screenX = window.screenX !== undefined ? window.screenX : window.screenLeft,
                    screenY = window.screenY !== undefined ? window.screenY : window.screenTop,
                    outerWidth = window.outerWidth !== undefined ? window.outerWidth : document.body.clientWidth,
                    outerHeight = window.outerHeight !== undefined ? window.outerHeight : (document.body.clientHeight - 22),
                    width = 480,
                    height = 400,
                    left = parseInt(screenX + ((outerWidth - width) / 2), 10),
                    top = parseInt(screenY + ((outerHeight - height) / 2.5), 10),
                    options = (
                            'width=' + width +
                            ',height=' + height +
                            ',left=' + left +
                            ',top=' + top
                            );

            signinWin = window.open(this.href, 'Login', options);

            if (window.focus) {
                signinWin.focus()
            }

            return false;
        });
    });

</script>
