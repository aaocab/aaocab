<?
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/js/jquery.mask.min.js');
?>
<div class="container">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <?php
                if (isset($_GET['msg']) && $_GET['msg'] == 1) {
                    ?>
                    <div class="col-xs-12"> <h4 style="color: #de6a1e;">Invitation Sent Successfully.</h4>   </div>
                    <?php
                }
                ?>
                <div id="VendorInnerDiv">
                    <div class="col-xs-12 col-sm-9"> 
                        <h3 class="m0 mb10 pb5 border-bottom weight400 text-uppercase">Operator Invite</h3>
                        <div> <b>Become a part of Gozo Family…</b></div>
                        <div><p> If you drive an inter-city taxi or operate a inter-city taxi service company, then you should join with Gozo.</p> </div>
                        <div><b>To join Gozo fill up this form…</b></div>

                        <?php
                        $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
                            'id' => 'operatorForm', 'enableClientValidation' => true,
                            'clientOptions' => array(
                                'validateOnSubmit' => true,
                                'errorCssClass' => 'has-error',
                            ),
                            // Please note: When you enable ajax validation, make sure the corresponding
                            // controller action is handling ajax validation correctly.
                            // See class documentation of CActiveForm for details on this,
                            // you need to use the performAjaxValidation()-method described there.
                            'enableAjaxValidation' => false,
                            'errorMessageCssClass' => 'help-block',
                            'htmlOptions' => array(
                                'class' => 'form-horizontal'
                            ),
                        ));
                        /* @var $form TbActiveForm */
                        ?>

                        <article>
                            <section>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-10 col-md-8 col-lg-6 ml30">
                                        <div class="">
                                            <label for="operator"><b>Taxi operator company name</b></label>
                                            <?= $form->textFieldGroup($model, 'opt_company_name', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Company Name')))) ?>
                                            <span id="errId" style="color: #F25656"></span>
                                        </div>
                                        <div class="">
                                            <label for="email"><b>Email</b></label>
                                            <?= $form->textFieldGroup($model, 'opt_email', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Email Address')))) ?>
                                            <span id="errId" style="color: #F25656"></span>
                                        </div>
                                        <div class="">
                                            <label class="col-xs-12 m0 p0" for="phone"><b>Phone</b></label>
                                            <div class="col-xs-2 m0 p0"><?= CHtml::textField("countryCode", '91', ['id' => 'countryCode', 'placeholder' => "Country Code", 'class' => "form-control", 'required' => 'required', 'value' => '91', 'readOnly' => true]) ?>
                                            </div>
                                            <div class="col-xs-10 m0 p0">
                                                <?= $form->textFieldGroup($model, 'opt_phone', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Phone Number')))) ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="Submit-button" style="" id="vendorSubmitDiv">
                                    <?= CHtml::submitButton('Submit', ['class' => "btn btn-primary"]) ?>
                                </div>
                                <div id="loading"></div>
                            </section>
                        </article>
                        <?php $this->endWidget(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('#phone').mask('9999999999');

        $('#VendorOuterDiv').hide();

    });

    function opentns() {


        $href = '<?= Yii::app()->createUrl('index/termsvendor') ?>';
        jQuery.ajax({type: 'GET', url: $href,
            success: function (data) {
                box = bootbox.dialog({
                    message: data,
                    title: '',
                    size: 'large',
                    onEscape: function () {
                        box.modal('hide');
                    }
                });
            }
        });
    }

    function validateCheckHandlerss() {
        if ($('#email').val() != "") {
            var pattern = /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/
            var retVal = pattern.test($('#email').val());
            if (retVal == false)
            {
                $('#errId').html("The email address you have entered is invalid.");
                return false;
            } else
            {
                $('#errId').html("");
                return true;
            }
        }
        return true;

    }



</script>
