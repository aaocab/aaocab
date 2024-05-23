    <?
    /** @var contact $model */
	$contactId		 = Yii::app()->shortHash->unhash($conid);	
    switch ($templateStyle)
    {
        case Contact::NEW_CON_TEMPLATE:            
			$contactModel = ContactPhone::model()->findByPhoneAndContact($phone, $contactId);
            if ($contactModel->phn_is_expired == 0)
            {
                ?>

                <div class="row bg-gray pt40 pb40">
                    <div class="col-12 col-lg-6 offset-lg-3 bg-white-box">
                    <div class="text-left mb20 alert alert-block p0">
                        <blockquote class="h5">
                            <?
                            $userName = trim($model->ctt_first_name . ' ' . $model->ctt_last_name);

                            if (empty($userName))
                            {
                                $userName = $model->ctt_business_name;
                            }
                            ?>
                            Dear <strong> <?= '<strong>' . $userName . '</strong>' ?>,</strong> <br> <br>
                            Please Confirm your phone number <? echo '<strong>' . $phone . '</strong>' ?> to activate your account
                        </blockquote>
                    </div>
                    <div class="row">
                        <div class="col-6 text-center mb20">
                            <?= CHtml::numberField('phn_verify_otp', yii::app()->shortHash->unhash($otp), $htmlOptions = array('readonly' => true, 'class' => "form-control text-center")); ?> 
                        </div>
                        <div class="col-6 text-center mb20 col-md-1">
                            <?= CHtml::submitButton('Verify', ['onclick' => 'phonevalidate()', 'class' => 'btn btn-primary']); ?>
                        </div>
                    </div>
                    </div>
                </div>
                <?
            }
            else
            {
                ?>
                <div id="dialog" title="Verification Link">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="h4 text-center pt10">
                                <strong>Your verification Link has been expired </strong>
                            </div>
                        </div>
                    </div>
                </div>
                <?
            }


            break;

        case Contact::NOTIFY_OLD_CON_TEMPLATE:
            /** @var contactTemp $tempModel */
            $expireTime   = $tempModel->tmp_ctt_expiry_time;
            #$currentTime = round(microtime(true) * 1000);
            $currentTime  = time();            
			$contactModel = ContactPhone::model()->findByPhoneAndContact($phone, $contactId);
            if ($currentTime < $expireTime || ($contactModel->phn_is_expired == 0))
            {
                ?>

                <div style="text-align:left; margin:20px 0;">
                    <div class=" text-left mb20 alert alert-block">
                        <blockquote class="h5">
                            Dear <strong> <?= '<strong>' . $tempModel->tmp_ctt_name . '</strong>' ?>,</strong> <br> <br>
                            <div class=" text-left ">Your phone number  <?= '<strong>' . $phone . '</strong>' ?> is being added by  <?= '<strong>' . $vndName . '</strong>' ?>  as a Driver to Gozo Cabs. <br> <br>
                                To allow verify your phone number >> <br> </div>
                        </blockquote>
                    </div>
                    <div class=" text-center mb20">
                        <?= CHtml::numberField('phn_verify_otp', yii::app()->shortHash->unhash($otp), $htmlOptions = array('readonly' => true)); ?> 
                        <?= CHtml::submitButton('Verify', ['onclick' => 'phonevalidate()', 'class' => 'btn btn-primary']); ?>
                    </div>
                </div>
                <?
            }
            else
            {
                ?>
                <div id="dialog" title="Verification Link">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="h4 text-center pt10">
                                <strong>Your verification Link has been expired </strong>
                            </div>
                        </div>
                    </div>
                </div>
                <?
            }

            break;

        case Contact::MODIFY_CON_TEMPLATE:            
			$contactModel = ContactPhone::model()->findByPhoneAndContact($phone, $contactId);
            if ($contactModel->phn_is_expired == 0)
            {
                ?>
                <div style="text-align:left; margin:20px 0;">
                    <div class=" text-left mb20 alert alert-block">
                        <blockquote class="h5 ">
                            <?
                            $userName = trim($model->ctt_first_name . ' ' . $model->ctt_last_name);

                            if (empty($userName))
                            {
                                $userName = $model->ctt_business_name;
                            }
                            ?>
                            Dear <strong> <?= '<strong>' . $userName . '</strong>' ?>,</strong> <br> <br>
                            Please Confirm your phone number <? echo '<strong>' . $phone . '</strong>' ?> to modify your phone number
                        </blockquote>
                    </div>
                    <div class=" text-center mb20">
                        <?= CHtml::numberField('phn_verify_otp', yii::app()->shortHash->unhash($otp), $htmlOptions = array('readonly' => true)); ?> 
                        <?= CHtml::submitButton('Verify', ['onclick' => 'phonevalidate()', 'class' => 'btn btn-primary']); ?>
                    </div>
                </div>
                <?
            }
            else
            {
                ?>
                <div id="dialog" title="Verification Link">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="h4 text-center pt10">
                                <strong>Your verification Link has been expired </strong>
                            </div>
                        </div>
                    </div>
                </div>
                <?
            }
            break;
        default:
            break;
    }
    ?>







<script>
    function phonevalidate()
    {
        //debugger
        var hashId = '<?= $conid ?>';
        var otpHash = '<?= $otp ?>';
        var code = $('#phn_verify_otp').val();
        var cttId = '<?= $model->ctt_id ?>';
        let vndId = '<?= $vndId ?>';
        let modifyPhone = '<?= $phone ?>';
        let expireLink = '1';
        var href2 = '<?= Yii::app()->createUrl("contact/verifyPhone"); ?>';
        $.ajax({
            "url": href2,
            "type": "GET",
            "async": true,
            "dataType": 'json',
            data: {"hash": hashId, "cttid": cttId, "code": code, "otp": otpHash, "vndId": vndId, "modifyPhone": modifyPhone, "expireLink": expireLink},
            "success": function (response)
            {
                if (response.success)
                {
                    alert("Phone number  <?= $phone ?> has been verified.");
                    window.location = "<?= Yii::app()->getBaseUrl(true) ?>";
                } else
                {
                    alert("Invalid OTP !!");
                }
            },
            "error": function (response)
            {
                alert("Invalid Request");
            }
        });
    }
</script>
