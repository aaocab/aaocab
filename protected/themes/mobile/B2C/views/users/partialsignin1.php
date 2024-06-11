<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/fontawesome-web/css/all.min.css?v0.6');
?>

<div class="col-xs-12 text-center container-fluid banner hide">
    <?php $this->widget('ext.hoauth.widgets.HOAuth'); ?>
</div>
<?php
$callback = Yii::app()->request->getParam('callback', '');

$js = "window.$callback;";


if (Yii::app()->request->isAjaxRequest) {
    $cls = "";
} else {
    $cls = "col-lg-4 col-md-6 col-sm-8 col-xs-10";
}
?>
<div class="row signin-bootbox">
    <div class="<?= $cls ?>" style="float: none; margin: auto">
        <div>
            <?
            if (Yii::app()->user->isGuest) {
                ?>

                <div class="panel panel-white" id="loginDiv">
                    <div class="panel-body pt0">
                        <div class="row flex m0">
                            <div class="col-sm-6 p0 signin-left">
                                <div class="pt30 pb30">
                                    <div class="mt20 mb30"><img src="<?=ASSETS_URL?>images/logo_outstation.png" alt="aaocab"></div>
                                    <div class="mt50 mb5"><b>Book with Gozo cabs mobile app11</b></div>
                                    <div class="mb50">
                                        <a href="https://play.google.com/store/apps/details?id=com.aaocab.client" target="_blank"><img src="/images/GooglePlay.png?v1.1" alt="aaocab APP"></a> <a href="https://itunes.apple.com/app/id1398759012?mt=8" target="_blank"><img src="/images/app_store.png?v1.2" alt="aaocab APP"></a>
                                    </div>
                                    <div class="h1 mt50 pt40 orange-color">
                                        India's Leader<br>
                                        in Outstation Travel
                                    </div>
                                    <div class="h4 mt0">
                                        <b>For One-Way, Round Trip & Multi-city Trip</b>
                                    </div>
                                    <div class="h5 mb20">
                                        <p>Consistent Quality | Easy Experience</p>
                                        <p>Great Service | Fair Pricing</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 p0">
                                <div class="h3 mt40 orange-color text-center" style="font-weight: normal">Log In to aaocab</div>
                                <div class="h5 text-center" style="font-weight: normal">New to aaocab? <a href="/signup">Sign up</a></div>
                                <?php
                                $form = $this->beginWidget('CActiveForm', array(
                                    'id' => 'plogin-form', 'enableClientValidation' => true,
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
									var userinfo = JSON.parse(data1.userdata);
                                    $userid = data1.id;				   
                                    ' . $js . '									
									$("#BookingTemp_fullContactNumber").val(userinfo.usr_mobile);
									$("#BookingTemp_bkg_user_email1").val(userinfo.usr_email);
                                    }
                                else{
                                    settings=form.data(\'settings\');
                                    var data = data1.data;
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
                                <div class="row" style="text-align: center;">
                                    <div class="col-xs-12 col-md-8 col-md-offset-2 fbook-btn mb20">
                                        <a class="btn btn-lg btn-social btn-facebook pl15 pr15" target="_blank" href="<?= Yii::app()->createUrl('users/oauth', array('provider' => 'Facebook')); ?>"><i class="fa fa-facebook pr5" style="font-size: 22px;"></i> Login with Facebook</a>
                                    </div>
                                    <div class="col-xs-12 col-md-8 col-md-offset-2 google-btn">
                                        <a class="btn btn-lg btn-social btn-googleplus pl15 pr15" target="_blank"  href="<?= Yii::app()->createUrl('users/oauth', array('provider' => 'Google')); ?>"><img src="../images/google_icon.png" alt="aaocab"> Login with Google</a>
                                    </div>
                                    <a class="btn btn-lg btn-social btn-linkedin pl15 pr15 hide" target="_blank" href="<?= Yii::app()->createUrl('users/oauth', array('provider' => 'LinkedIn')); ?>"><i class="fa fa-linkedin"></i></a>
                                    <div class="col-xs-12 mt30 h4 style_or"><span class="style_or2">OR</span></div>
                                </div>
                                <div class="row">
                                    <?php echo CHtml::errorSummary($model); ?>

                                </div>                
                                <div class="row">
                                    <div class="col-xs-12 col-sm-7 float-none marginauto">                        
                                        <?= $form->emailField($model, 'usr_email', array('label' => 'Email', 'widgetOptions' => array('htmlOptions' => ['required' => TRUE, 'class' => 'mb0', 'placeholder' => "Email"]))) ?>
                                    </div>
                                </div> 
                                <div class="row">
                                    <div class="col-xs-12 col-sm-7 float-none marginauto">                        
                                        <?= $form->passwordField($model, 'usr_password', array('label' => 'Password', 'widgetOptions' => array('htmlOptions' => ['required' => TRUE, 'placeholder' => "Password"]))) ?>
                                        <div class="text-right mr15 n mt10 n"><a href="/forgotpassword">Forgot Password?</a></div>
                                    </div>
                                    
                                </div>
                                <div class="row">
                                    <div class="col-xs-10 col-sm-7 float-none marginauto text-center signin-border p0 pb20">                   
                                        <input class="btn login-new-btn col-xs-12" type="submit" name="signin" value="Log In"/>
                                    </div>
                                </div>
                                <?php $this->endWidget(); ?>
                                <div class="h5 text-right mr40 mb20" style="font-weight: normal">New to aaocab? <a href="/signup">Sign up</a></div>

                            </div>
                        </div>
                    </div>
                </div>       
                <?
            } else {
                ?> <div class="panel panel-white" id="loginDiv">
                    <div class="panel-body  pb20"> <div class="col-xs-12"><?
                            echo "You are already logged in.";
                            ?> 
                        </div> 
                    </div>
                </div>   <?
            }
            ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
<?
if (!Yii::app()->user->isGuest) {
    ?>
            refreshUserDataLogin();
    <?
}
?>
    });

    function refreshUserDataLogin() {

        $href = '<?= Yii::app()->createUrl('users/refreshuserdata') ?>';
        jQuery.ajax({type: 'get', url: $href,
            "dataType": "json",
            success: function (data1)
            {				
                $('#navbar_sign').html(data1.rNav);
                $('#userdiv').hide();
                fillUserform2(data1.userData);

                fillUserform13(data1.userData);
            }
        });
    }
    function fillUserform2(data) {

        if ($('#BookingTemp_bkg_user_name').val() == '' && $('#BookingTemp_bkg_user_lname').val() == '')
        {

            $('#BookingTemp_bkg_user_name').val(data.usr_name);
            $('#BookingTemp_bkg_user_lname').val(data.usr_lname);
        }
        if (data['usr_mobile'] != '') {
            if ($('#BookingTemp_bkg_contact_no').val() == '') {
                $('#BookingTemp_bkg_contact_no').val(data.usr_mobile);
            } else if ($('#BookingTemp_bkg_contact_no').val() != '' && $('#BookingTemp_bkg_contact_no').val() != data.usr_mobile) {
                $('#BookingTemp_bkg_alternate_contact').val(data.usr_mobile);
            }
        }
        if (data.usr_email != '') {
            if ($('#BookingTemp_bkg_user_email1').val() == '') {
                $('#BookingTemp_bkg_user_email1').val(data.usr_email);
            }
            if ($('#BookingTemp_bkg_user_email2').val() == '') {
                $('#BookingTemp_bkg_user_email2').val(data.usr_email);
            }
        }

    }
    function fillUserform13(data) {
        if ($('#Booking_bkg_user_name').val() == '' && $('#Booking_bkg_user_lname').val() == '')
        {

            $('#Booking_bkg_user_name').val(data.usr_name);
            $('#Booking_bkg_user_lname').val(data.usr_lname);
        }
        if (data.usr_mobile != '') {
            if ($('#Booking_bkg_contact_no').val() == '') {
                $('#Booking_bkg_contact_no').val(data.usr_mobile);
            } else if ($('#Booking_bkg_contact_no').val() != '' && $('#Booking_bkg_contact_no').val() != data.usr_mobile) {
                $('#Booking_bkg_alternate_contact').val(data.usr_mobile);
            }
        }
        if (data.usr_email != '') {
            if ($('#Booking_bkg_user_email1').val() == '') {
                $('#Booking_bkg_user_email1').val(data.usr_email);
            }
            if ($('#Booking_bkg_user_email2').val() == '') {
                $('#Booking_bkg_user_email2').val(data.usr_email);
            }
        }


    }
</script>
