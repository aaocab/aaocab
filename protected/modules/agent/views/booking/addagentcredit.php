

<style type="text/css">
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; 
        margin: 0; 
    }
    input[type="number"] {
        -moz-appearance: textfield;
    }
    .input-group-addon>i {
        color: #666666;
    }
</style>


<div class="row">
    <div class="col-xs-12">
        <div class="h3 text-center mt0">
            <?= $bkgCode ?>
        </div>
        <?
        $dueagentCreditAmount = $model->bkgInvoice->bkg_total_amount - $model->bkgInvoice->getAdvanceReceived();
        ?>
        <?php
        $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
            'id' => 'agent-credit',
            'enableClientValidation' => true,
            //'action' => Yii::app()->createUrl('agent/booking/addagentcredit', []),
            'clientOptions' => array(
                'validateOnSubmit' => true,
                'errorCssClass' => 'has-error',
                'afterValidate' => 'js:function(form,data,hasError){
                   
                    if(!hasError){
                    $.ajax({
                    "type":"POST",
                    "url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('agent/booking/addagentcredit', [])) . '",
                    "data":form.serialize(),
                            "dataType": "json",
                            "success":function(data1){
                           
                                    if(data1.success)
                                    {
                                       if(data1.url != "")
                                            {
                                            location.href=data1.url;
                                                return false;
                                            }
                                    }
                                    else{
                                   
                                    var errors = data1.errors;    
                                    if(errors!=""){
                                    $("#creditError").text(errors).change();
                                    }
                                
                                  }
                                },
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
            'htmlOptions' => array(
                'class' => 'form-horizontal'
            ),
        ));
        /* @var $form TbActiveForm */
        ?>
        <? //= CHtml::beginForm(Yii::app()->createUrl('agent/booking/addagentcredit'), "post", ['accept-charset' => "UTF-8", 'id' => "deleteForm", 'class' => "form", 'data-replace' => ".error-message p"]); ?>
        <input type="hidden" id="bk_id" name="bk_id" value="<?= $bkid ?>">
        <div class="modal-body">
            <div class="row mt10">
                <div class="col-xs-6">Total Booking Amount</div>
                <div class="col-xs-6"><i class="fa fa-inr"></i> <?= $model->bkgInvoice->bkg_total_amount ?></div>
            </div>
            <div class="row  mt10 ">
                <div class="col-xs-6">Total Agent Credit Used</div>
                <div class="col-xs-6"><i class="fa fa-inr"></i> <?= $model->bkgInvoice->bkg_corporate_credit ?></div>
            </div>
            <div class="row  mt10 ">
                <div class="col-xs-6">Due Amount</div>
                <div class="col-xs-6"><i class="fa fa-inr"></i> <?= $dueagentCreditAmount ?></div>
            </div>
            <div class="row mt10">
                <div class="col-xs-6 m5 ml0">Enter Credit amount to be added</div>
                <div class="col-xs-3">
                    <?= $form->numberFieldGroup($model, 'agentCreditAmount', array('label' => '', 'prepend' => '<i class="fa fa-inr"></i>', 'widgetOptions' => array('htmlOptions' => ['class' => 'form-control', 'placeholder' => "Agent Advance Credit", 'min' => 0, 'max' => $dueagentCreditAmount, 'value' => $dueagentCreditAmount]))) ?>
                    <div id="creditError" class=" help-block error text-danger font-bold"></div>
                </div> 
                <div id="creditError" class=" help-block error text-danger font-bold"></div>
            </div> 
        </div>
        <div class="modal-footer">
            <?= CHtml::submitButton("SUBMIT", ['class' => "btn green"]); ?>
            <button type="button" data-dismiss="modal" class="btn dark btn-outline">Close</button>
        </div>
        <?php $this->endWidget(); ?>

    </div>
</div>

<script type="text/javascript">

    $('form').on('focus', 'input[type=number]', function (e) {
        $(this).on('mousewheel.disableScroll', function (e) {
            e.preventDefault()
        })
        $(this).on("keydown", function (event) {
            if (event.keyCode === 38 || event.keyCode === 40) {
                event.preventDefault();
            }
        });
    });
    $('form').on('blur', 'input[type=number]', function (e) {
        $(this).off('mousewheel.disableScroll');
        $(this).off('keydown');
    });
    
     $('#agent-credit').one('submit', function () {
        $(this).find('input[type="submit"]').attr('disabled', 'disabled');
    });
</script>