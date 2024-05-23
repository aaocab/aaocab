<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/js/jquery.mask.min.js');
$allCountryMobileCodes = Countries::model()->counrtyMobileCode();
?>
<div class="row">
    <div class="col-lg-7 col-md-8 col-sm-11" style="float: none; margin: auto">
        <div style="text-align:center;" class="col-xs-12">
            <h3>We're excited to have you in our family</h3>
            <?
            if ($status == 'error') {
                echo "<span style='color:#ff0000;'>Password and Confirmation password should be same.</span>";
            } elseif ($status == 'succ') {
                echo "<span style='color:green;'>Activation link sent to your email id.</span>";
            } elseif ($status == "emlext") {
                echo "<span style='color:#ff0000;'>This email address is already registered. Please try again using a new email address.</span>";
            } elseif ($status == "invalidPromo") {
                echo "<span style='color:#ff0000;'>Invalid promo code.</span>";
            } else {
                //do nothing
            }
            ?>
        </div>
        <div class="row">
            <div class="upsignwidt">
                <div class="col-sm-7">
                    <?
                    $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
                        'id' => 'user-register-form', 'enableClientValidation' => true,
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
                        'htmlOptions' => array(
                            'class' => 'form-horizontal',
                        ),
                    ));
                    /* @var $form TbActiveForm */
                    ?>
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <?php echo CHtml::errorSummary($model); ?>
                            <?= $form->textFieldGroup($model, 'username', array('label' => '')) ?>
                            <?= $form->textFieldGroup($model, 'email', array('label' => '')) ?>

                            <?= $form->passwordFieldGroup($model, 'new_password', array('label' => '')) ?>
                            <?= $form->passwordFieldGroup($model, 'repeat_password', array('label' => '')) ?>
                            <?= $form->textFieldGroup($model, 'refcode', array('label' => '')) ?>
                            <?= $form->textFieldGroup($model, 'promo_code', array('label' => '')) ?>
                           
                            <?php echo $form->error($model, 'promo_code'); ?>

                            <?= $form->checkboxGroup($model, 'tnc', array('label' => 'I confirm that I am at least 18 years old. I have read, understand and agree to be bound by Impind\'s
									<a class="fmodal" href="' . Yii::app()->createUrl('index/tns') . '">Terms of Service</a> &amp;
									<a  class="fmodal" href="' . Yii::app()->createUrl('index/privacy') . '">Privacy Policy</a> agreements.</span>')) ?>

                            <div class="panel-footer" style="">
                                <?php echo CHtml::submitButton('Sign Up', array('class' => 'btn btn-primary pull-right')); ?>
                                <?
                                if (!$isProSignUp) {
                                    ?>  <a id="sbtn" href="<?= Yii::app()->createUrl('users/signin'); ?>" class="btn btn-default" >Already Registered? Sign In</a><? } ?>
                            </div>
                        </div>
                    </div><?php $this->endWidget(); ?></div>

                <div class="col-sm-5">
                    <h4 class="pull-left mr20" style="margin-top: 120px">OR</h4>
                    <div class="btn-group-vertical mb10 mt50">
                        <input type="text" id="seturl" style="display: none">
                        <a class="btn btn-lg btn-label btn-social btn-facebook"  href="<?= Yii::app()->createUrl('users/oauth', array('provider' => 'Facebook')); ?>">
                            <i class="fa fa-facebook"></i> Facebook
                        </a><br>
                        <a class="btn btn-lg btn-label btn-social btn-googleplus" href="<?= Yii::app()->createUrl('users/oauth', array('provider' => 'Google')); ?>">
                            <i class="fa fa-google-plus"></i> Google+
                        </a><br>
                        <a class="btn btn-lg btn-label btn-social btn-linkedin"  href="<?= Yii::app()->createUrl('users/oauth', array('provider' => 'LinkedIn')); ?>">
                            <i class="fa fa-linkedin"></i> LinkedIn
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo CHtml::endForm(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        var availableTags = [];
        var front_end_height = $(window).height();
        var footer_height = $(".footer").height();
        var header_height = $(".header").height();
        $(".searchpanel").css({
            "min-height": ((front_end_height - footer_height) - header_height - 11) + "px"
        });
        $(".popover-bottom").popover({
            placement: 'bottom'
        });


        $(function () {
            $("#mySelectedLoc").autocomplete({
                source: availableTags
            });
        });

        $("#myRating").mouseover(
                function (e) {
                    e.stopPropagation();
                    $(".myrates").fadeIn();
                }
        );

        $(".myrates").mouseover(
                function (e) {
                    e.stopPropagation();
                    $(".myrates").show();
                }
        );

        $(document).mouseover(function () {
            $(".userPopup").fadeOut();
            $(".myrates").fadeOut();
        });
        $("#profileDown").mouseover(function (e) {
            e.stopPropagation();
            $(".userPopup").fadeIn();
        });
        $(".userPopup").mouseover(function (e) {
            e.stopPropagation();
            $(".userPopup").show();
        });

        $("#Users_promo_code").blur(function (e) 
        {

            if ($("#Users_promo_code").val() != '') {
                var promocode = $("#Users_promo_code").val();
                $.ajax({
                    url: '<?= Yii::app()->createUrl('users/checkpromo'); ?>',
                    type: 'POST',
                    data: 'promocode=' + promocode,
                    "success": function (response) {
                        if (!response)
                        {
                          alert('Promo code entered is invalid');
                            $("#Users_promo_code").val('');

                        }
                    }
                });
            }
        });
    });


    function validateCheckHandlerss() {
        if ($("#formId").validation({errorClass: 'validationErr'})) {

            if ($('#Users_email').val() != "")
            {
                var pattern = /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/
                var retVal = pattern.test($('#Users_email').val());
                if (retVal == false)
                {
                    $('#errId').html("The email address you have entered is invalid.");
                    return false;
                }
            }
            if ($("#Users_password").val() != $("#cpassword").val())
            {
                $('#errId').html("The passwords you have entered don't match. Please enter them again.");
                $("#Users_password").val("");
                $("#cpassword").val("");
                return false;
            }
            if ($('#chkacc')[0].checked != true)
            {
                alert("Please indicate that you accept Impind\'s \"Terms of Service\" & \"Privacy Policy\" agreements.");
                return false;
            }

            return true;

        }
        else
        {
            return false;
        }
    }

    $('#facebook').click(function () {
        var valurl = $(this).attr('id');
        $('#seturl').val(valurl);
    });
    $('#googleplus').click(function () {
        var valurl = $(this).attr('id');
        $('#seturl').val(valurl);
    });
    $('#linkedin').click(function () {
        var valurl = $(this).attr('id');
        $('#seturl').val(valurl);
    });
</script>
<?
$hlogo = Yii::app()->baseUrl . '/images/logo.png';
$headerlogo = '<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
class="sr-only"> Close</span></button>
<h2 class="modal-title" id="myModalLabel">
<img src="' . $hlogo . '" alt="logo" width="80" style="margin:-5px 0;padding:0"></h2>';
?>
<div class="modal fade " id="termsservice" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog " style="width: 800px;">
        <div class="modal-content">
            <div class="modal-header">
                <?= $headerlogo ?>
            </div>
            <div class="modal-body aboutText">
                <div style="padding-top: 10px; text-align: center; background-color: #ffffff; height: 200px">
                    <h2>Get In Touch</h2>
                    <p>For any questions, please call us at <br> <b>+1-240-244-6746</b><br> or email us at<br> <b><a
                                href="mailto:hello@impind.com" style="text-decoration: none; color: #41B4BD">hello@impind.com</a></b>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade " id="aggeeterms" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog " style="width: 300px;top: 200px">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom: 0">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group " >
                    <input type="checkbox" id="chkacc1">
                    <span style="font-size: 15px">&nbsp;&nbsp;I confirm that I am at least 18 years old. I have read, understand and agree to be bound by Impind's
                        <a style="cursor: pointer;" class="fmodal" href="<?= Yii::app()->createUrl('index/tns') ?>">Terms of Service</a> &
                        <a style="cursor: pointer;" class="fmodal" href="<?= Yii::app()->createUrl('index/privacy') ?>">Privacy Policy</a> agreements.</span>
                </div>
                <div class="btn btn-sm btn-info pull-right"  id="agreebtn">Agree</div>
                <div class="btn btn-sm btn-default " data-dismiss="modal">Close</div>

            </div>
        </div>
    </div>
</div>
<script>
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