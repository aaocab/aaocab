<div class="row">
    <div class="col-12 text-center container-fluid banner" style="display: none;">
        <?php $this->widget('ext.hoauth.widgets.HOAuth'); ?>
    </div>
</div>
<?php
$callback = Yii::app()->request->getParam('callback', '');
if($callback != '')
{
	$js = "window.{$callback};";
}


if (Yii::app()->request->isAjaxRequest) {
    $cls = "";
} else {
    $cls = "col-lg-4 col-md-6 col-sm-8";
}
?>
<div class="row">

    <?
    if (Yii::app()->user->isGuest) {
        ?>

        <div class="flex" id="loginDiv">
            <div class="col-sm-6 gradient-green-blue2 text-center color-white">
                <span><img src="/images/gozo_orange-white.svg?v0.1" width="180" alt="aaocab" class="mt40"></span><br><br>
                <div class="font-24 mt50 mb5">Download The App</div>
                <div class="mb50">
                    <a href="https://play.google.com/store/apps/details?id=com.aaocab.client" target="_blank"><img src="/images/GooglePlay.png?v1.1" alt="aaocab APP"></a> <a href="https://itunes.apple.com/app/id1398759012?mt=8" target="_blank"><img src="/images/app_store.png?v1.2" alt="aaocab APP"></a>
                </div>
                <div class="font-30 mt50 pt40 orange-color"><b>Leader in outstation Taxi</b></div>
                <span class="font-18"><b>Available in 3000+ cities across India</b></span>
            </div>
            <div class="col-sm-6">
                <div class="modal-header border-none pb0">
                    <h5 class="modal-title" id="bkCommonModelHeader"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <p class="font-24 mt0 text-center mb0"><b>Log In to aaocab</b></p>
                <p class="font-14 text-center">New to aaocab? <a  href="javascript:void(0);" class="showSinUpPopUp">Sign up</a></p>


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
								var login = new Login();
									var userinfo = JSON.parse(data1.userdata);
                                    $userid = data1.id;				   
                                    ' . $js . '									
									login.fillUserform2(userinfo);
									login.fillUserform13(userinfo);
									$("#bkCommonModel").modal("hide");
									var pagename = location.pathname.substring(1);
									if(pagename == "signin"){
										window.location = "/";
									}
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
                <div class="row">
                    <!--<div class="col-8 offset-2 mb10">
                        <a class="social-btn bg-facebook" target="_blank" href="<?= Yii::app()->createUrl('users/oauth', array('provider' => 'Facebook')); ?>"><i class="fab fa-facebook-square mr10"></i> Login with Facebook</a>
                    </div>-->
                    <div class="col-12 text-center">
                        <a target="_blank"  href="<?= Yii::app()->createUrl('users/oauth', array('provider' => 'Google')); ?>"><img src="/images/btn_google_signin_light_normal_web.png?v=0.1" alt="Login with Google"></a>
                    </div>

                    <div class="col-12 mt20 mb10 font-20 text-center">OR</div>
                </div>



                <div class="row">
                    <?php echo CHtml::errorSummary($model); ?>

                </div>                
                <div class="row">
                    <div class="col-10 offset-1">          
                    <div class="form-group">        
                        <label class="control-label" for="ContactEmail_eml_email_address">Email</label>      
                        <?= $form->emailField($emailModel, 'eml_email_address',  ['required' => TRUE, 'class' => 'mb0 form-control', 'placeholder' => "Enter email"]) ?>
                        <?php echo $form->error($emailModel, 'eml_email_address', ['class' => 'help-block error']); ?>
                    </div>
                    </div>
                    <div class="col-10 offset-1">   
                    <div class="form-group">         
                        <label class="control-label" for="Users_usr_password">Password</label>            
                        <?= $form->passwordField($model, 'usr_password', ['required' => TRUE,'class' => 'form-control', 'placeholder' => "Enter password"]) ?>
                        <?php echo $form->error($model, 'usr_password', ['class' => 'help-block error']); ?>
                    </div>
                    </div>
                    <div class="col-10 offset-1  n text-right"><a href="/forgotpassword">Forgot Password?</a></div>
                    <div class="col-12 text-center">                   
                        <input class="btn text-uppercase gradient-green-blue font-14 pt10 pb10 pl30 pr30 border-none mt15" type="submit" name="signin" value="Log In"/>
                    </div>
                </div>
                <?php $this->endWidget(); ?>
                <p class="font-18 text-center mt20" style="font-weight: normal">New to aaocab? <a  href="javascript:void(0);" class="showSinUpPopUp">Sign up</a></p>

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
<script type="text/javascript">
    var login = new Login();
    $(document).ready(function () {
		$('.showSinUpPopUp').click(function () {		
			$('.sinUpPopUp').click();
		 });
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
                login.fillUserform2(data1.userData);

                login.fillUserform13(data1.userData);
            }
        });
    }

</script>
