<style>
    .full-width {
        width: 100% !important;
    }
</style>
<?
if ($vresult == 'false') {
    $error = 'error';
}
if ($vresult == 'true') {
    
}
$errorshow = ($error == '') ? 'hide' : 'show';
?>
<div class="container">
<div class="row mb10">
    <div class="col-12">
        <p class="weight400 text-center">Please enter the verification code you received on <br>
            <? if ($model->bkgUserInfo->bkg_contact_no != '') { ?><b>Phone: +<?= $model->bkgUserInfo->bkg_country_code ?><?= $model->bkgUserInfo->bkg_contact_no ?></b> 
                OR
            <? } ?>
            <b>Email: <?= ($model->bkgUserInfo->bkg_user_email != "") ? $model->bkgUserInfo->bkg_user_email : "" ?></b>
        </p>   </div>
</div>
<div class="row">
    <div class="col-12">
            
                <?php
                $form = $this->beginWidget('CActiveForm', array(
                    'id' => 'verify-form', 'enableClientValidation' => true,
                    'clientOptions' => array(
                        'validateOnSubmit' => true,
                        'errorCssClass' => 'has-error',
                        'afterValidate' => 'js:function(form,data,hasError){
                                  if(!hasError){
                                                $.ajax({
						"type":"POST",
						"dataType":"json",
						"url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('booking/confirmmobile')) . '",
						"data":form.serialize(),
						"success":function(data2){   
                                                        if(data2.success){
                                                                if(data2.manual=="manual"){
                                                                   location.reload();
                                                                }else{
                                                                  // openFinalBooking(data2.bkg_id,data2.hash);
                                                                  processBooking();
                                                                }
                                                        }else{
                                                                $("#errorshow").show();
                                                                $("#moberrordiv").html("Verification code did not match! Booking cannot be verified");

                                                        }

                                                },
                                                error: function (xhr, ajaxOptions, thrownError) 
                                                {
                                                                alert(xhr.status);
                                                                alert(thrownError);
                                                }
                                          });
                             }
                    }'
                    ),
                    'enableAjaxValidation' => false,
                    'errorMessageCssClass' => 'help-block',
                    'htmlOptions' => array(
                        'class' => 'form-inline',
                    ),
                ));
                /* @var $form CActiveForm */
                ?> 
                <?php echo CHtml::errorSummary($model); ?>
                <?= $form->hiddenField($model->bkgUserInfo, 'bui_bkg_id') ?>
                <?= $form->hiddenField($model->bkgUserInfo, 'hash') ?>
                <input type="hidden" name="manual" value="<?= $manual ?>">
                <input type="hidden" name="ctype" value="<?=$ctype?>">
                <div id="errorshow" class="row " style="display: none">

                    <div class="col-12 header" style="color: #f00000" id="moberrordiv">verification code did not match!</label>
                    </div>
                </div>
                <div class="col-sm-8">
                    <?= $form->textField($model->bkgUserInfo, 'bkg_verification_code1',['required' => TRUE, 'class' => 'form-control full-width border-radius','placeholder' => "Enter Verification Code"]) ?>
					<?php echo $form->error($model->bkgUserInfo, 'bkg_verification_code1', ['class' => 'help-block error']); ?>
                </div>
                <div class="col-sm-4">
                    <button class="btn btn-primary text-uppercase gradient-green-blue font-18 border-none pl30 pr30" id="sbmtbtn" type="submit" value="Verify">Apply</button>
                </div>
                <?php $this->endWidget(); ?>
                <div class="col-4 mt20">
                    <button class="btn btn-warning" id="resendcode" type="button" onclick="resendCode(<?= $model->bkg_id ?>, '<?= $model->hash ?>');">Resend</button>
                </div>
    </div>
    <div class="col-12 text-center mb10">
        <?if($manual=='manual'){?>
          <button class="btn btn-info" id="confirmlater" type="button" style="display: none" onclick="bootbox.hideAll();">I will confirm later</button>
        <?}else{?>
<!--         <button class="btn btn-info" id="confirmlater" type="button" style="display: none" onclick="bootbox.hideAll();">I will confirm later</button>-->
        <?}?>
    </div>
    <div class="col-12 mt30">
        <p class="text-center"><b>Did not receive the verification code?</b> <br>Wait a minute or contact support and we will manually verify the booking for you.</p>
    </div>
</div>
</div>
<script>
    $(document).ready(function () {
<? if ($isAlready2Sms >= 2) { ?>
            $('#resendcode').attr('disabled', 'disabled');
            $('#confirmlater').show();
<? } ?>
        var timeoutHandle = setTimeout(function () {
            $('#confirmlater').show();
        }, 30000);
    });

    function openFinalBooking(bkgId, hash) {

        var href1 = '<?= Yii::app()->createUrl('booking/finalbook') ?>';
        jQuery.ajax({'type': 'POST', 'url': href1, 'dataType': 'html',
            'data': {'bid': bkgId, 'hsh': hash, 'step': 4, "YII_CSRF_TOKEN": $('input[name="YII_CSRF_TOKEN"]').val()},
            success: function (data) {
                bootbox.hideAll();
                openTab(data, 5);
            }
        });
    }

    function resendCode(bkgId, hash) {
        var href1 = '<?= Yii::app()->createUrl('booking/confirmmobile') ?>';
        jQuery.ajax({'type': 'GET', 'url': href1, 'dataType': 'json',
            'data': {'bid': bkgId, 'hsh': hash, 'resend': 'resend'},
            success: function (data) {
                if (!data.success) {
                    $('#resendcode').attr('disabled', 'disabled');
                    $('#confirmlater').show();
                } else {
                    alert("code resent successfully.");
                }
            }
        });
    }
</script>


