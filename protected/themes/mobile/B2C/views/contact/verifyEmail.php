<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/fontawesome-web/css/all.min.css?v0.6');
?>
<div class="container">
    <?
    /** @var contact $model */
    switch ($templateStyle)
    {
        case Contact::NEW_CON_TEMPLATE:
            $modifyModel = ContactEmail::model()->findEmailIdByEmail($contactEmail);
            if ($modifyModel->eml_is_expired == 0)
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
                            Please Confirm your email address <? echo '<strong>' . $contactEmail . '</strong>' ?> to activate your account
                        </blockquote>
                    </div>

                    <div style="text-align:left; margin-left:50px;">
                        <button type="button" class="btn btn-success  btn-md"  onclick="verify(1)" ><i class="fa fa-check-square-o"> </i> OK, Add me </button>
                        <button type="button" class="btn btn-warning  btn-md"  onclick="verify(0)"><i class="fa fa fa-times" style="color:red"></i> No, Do NOT add me</button>
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
            $expireTime  = $tempModel->tmp_ctt_expiry_time;
            #$currentTime = round(microtime(true) * 1000);
            $currentTime = time();
            if ($currentTime < $expireTime || ($model->contactEmails[0]->eml_is_expired == 0))
            {
                ?>
                <div style="text-align:left; margin:10px 0;">
                    <div class=" text-left mb20 alert alert-block">
                        <blockquote class="h5">
                            Dear <strong> <?= '<strong>' . $tempModel->tmp_ctt_name . '</strong>' ?>,</strong> <br> <br>
                            <div class=" text-left ">Your email address  <?= '<strong>' . $contactEmail . '</strong>' ?>  is being added by  <?= '<strong>' . $vndName . '</strong>' ?> as a Driver to Gozo Cabs. <br> <br>

                                To allow click here >> <br>
                            </div>
                        </blockquote>
                    </div>
                    <div style="text-align:left; margin-left:50px;">
                        <?
                        if ($tempModel->tmp_ctt_request_by != $vndId)
                        {
                            ?>
                            <button type="button" class="btn btn-success  btn-md"  onclick="verify(1)" ><i class="fa fa-check-square-o"></i> OK, Add me </button>
                            <?
                        }
                        else
                        {
                            ?>
                            <button type="button" class="btn btn-success  btn-md"  onclick="verify(4)" ><i class="fa fa-check-square-o"></i> OK, Add me </button>
                            <?
                        }
                        ?>
                        <button type="button" class="btn btn-warning  btn-md"  onclick="verify(2)"><i class="fa fa fa-times" style="color:red"></i> No, Do NOT add me </button>
                        <button type="button" class="btn btn-warning  btn-md"  onclick="verify(3)"><i class="fa fa fa-times" style="color:darkred"></i> I do not recognize this message </button>
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
            $modifyModel = ContactEmail::model()->findEmailIdByEmail($contactEmail);
            if ($modifyModel->eml_is_expired == 0)
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
                            Please Confirm your email address <? echo '<strong>' . $contactEmail . '</strong>' ?> to modify your email address
                        </blockquote>
                    </div>
                    <div style="text-align:left; margin-left:50px;">
                        <button type="button" class="btn btn-success  btn-md"  onclick="verify(1)" ><i class="fa fa-check-square-o"></i> OK, Add me </button>
                        <button type="button" class="btn btn-warning  btn-md"  onclick="verify(0)"><i class="fa fa fa-times" style="color:red"></i> No, Do NOT add me  </button>
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




</div>



</div>

<script>


    function verify(isVerify)
    {

        let hasContactId   = '<?= $contactId ?>';
        let cttId          = '<?= $model->ctt_id ?>';
        let tempPkId       = '<?= $tempPkId ?>';
        let templateStyle  = '<?= $templateStyle ?>';
        let vendorName     = '<?= $vndName ?>';
        let vndId          = '<?= $vndId ?>';
        let modifyEmail    = '<?= $contactEmail ?>';
        let expireLink     = '1';

        var href2 = '<?= Yii::app()->createUrl("contact/VerifyEmail"); ?>';
        $.ajax({
            "url": href2,
            "type": "GET",
            async: true,
            //dataType: "text",
            data: {"hasContactId": hasContactId, "cttId": cttId, "isVerify": isVerify, "tempPkId": tempPkId, "templateStyle": templateStyle, "vendorName": vendorName, "vndId": vndId, "modifyEmail": modifyEmail, "expireLink": expireLink},
            "success": function (response)
            {
                switch (isVerify)
                {
                    case 1:
                        alert(" <? echo $contactEmail ?> has been verified. Thank You!");
                        window.location = "<?= Yii::app()->getBaseUrl(true) ?>";
                        break;

                    case 0:
                        alert("you have not verify your email address");
                        window.location = "<?= Yii::app()->getBaseUrl(true) ?>";
                        break;

                    case 2:
                        alert("you have not verify your email address");
                        window.location = "<?= Yii::app()->getBaseUrl(true) ?>";
                        break;

                    case 3:
                        alert("you have not recognized this email address");
                        window.location = "<?= Yii::app()->getBaseUrl(true) ?>";
                        break;
                    case 4:
                        alert("You are now added as driver to  this vendor account");
                        window.location = "<?= Yii::app()->getBaseUrl(true) ?>";


                    default:
                        break;
                }
            }
        });
    }

</script>



