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
$response = Contact::referenceUserData($model->bkgUserInfo->bui_id, 2);
if ($response->getStatus())
{
	$contactNo	 = $response->getData()->phone['number'];
	$countryCode = $response->getData()->phone['ext'];
}
?>

<div class="row mb10">
    <div class="col-xs-12">
        <p class="weight400 text-center">Please enter the verification code you received on <br>
            <? if ($contactNo != '') { ?><b>Phone: +<?= $countryCode ?><?= $contactNo ?></b> 
                <br>OR <br>
            <? } ?>
		<?php $response = Contact::referenceUserData($model->bkgUserInfo->bui_id, 1);
			if ($response->getStatus())
			{
				$email	 = $response->getData()->email['email'];
			}
		?>
            <b>Email: <?= ($email != "") ? $email : "" ?></b>
        </p>   </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 book-panel2 float-none marginauto">
        <div class="panel panel-default">
            <div class="panel-body">
                <?php
                $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
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
                /* @var $form TbActiveForm */
                ?> 
                <?php echo CHtml::errorSummary($model); ?>
                <?= $form->hiddenField($model->bkgUserInfo, 'bui_bkg_id') ?>
                <?= $form->hiddenField($model->bkgUserInfo, 'hash') ?>
                <input type="hidden" name="manual" value="<?= $manual ?>">
                <input type="hidden" name="ctype" value="<?=$ctype?>">
                <div id="errorshow" class="row " style="display: none">

                    <div class="col-xs-12 header" style="color: #f00000" id="moberrordiv">verification code did not match!</label>
                    </div>
                </div>
                <div class="col-sm-5">
                    <?= $form->textFieldGroup($model->bkgUserInfo, 'bkg_verification_code1', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['required' => TRUE, 'class' => 'form-control full-width border-radius']))) ?>
                </div>
                <div class="col-sm-3">
                    <button class="btn btn-success border-radius full-width  border-none" id="sbmtbtn" type="submit" value="Verify">Apply</button>
                </div>
                <?php $this->endWidget(); ?>
                <div class="col-xs-4">
                    <button class="btn btn-warning" id="resendcode" type="button" onclick="resendCode(<?= $model->bkg_id ?>, '<?= $model->hash ?>');">Resend</button>
                </div>
            </div>
        </div> 
    </div>
    <div class="col-xs-12 text-center mb10">
        <?if($manual=='manual'){?>
          <button class="btn btn-info" id="confirmlater" type="button" style="display: none" onclick="bootbox.hideAll();">I will confirm later</button>
        <?}else{?>
<!--         <button class="btn btn-info" id="confirmlater" type="button" style="display: none" onclick="bootbox.hideAll();">I will confirm later</button>-->
        <?}?>
    </div>
    <div class="col-xs-12">
        <p class="text-center"><b>Did not receive the verification code? <br>Wait a minute or you could call us on <nobr>+91 90518-77-000</nobr> and we will manually verify the booking for you.</b></p>
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


