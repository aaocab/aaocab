 
<div class="container mt50">
<!--    <div class="row">
        <div class="col-xs-12 text-center"><img src="/images/logo2.png" alt="aaocab:India's leader in inter-city taxi | Great service. Price guarantee. Awesome reviews."></div>
    </div>-->
    <div class="spot-panel2">

        <?
        $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
            'id'                     => 'agent-booking-spot10', 'enableClientValidation' => FALSE,
            'clientOptions'          => array('validateOnSubmit' => true, 'errorCssClass' => 'has-error'),
            'enableAjaxValidation'   => false,
            'errorMessageCssClass'   => 'help-block',
            'htmlOptions'            => array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data'),
        ));
        echo $form->hiddenField($model, 'bkg_booking_type');
        ?>

        <input type="hidden" name="step" value="10">
        <input type="hidden" name="rechargeAmount" value="">
        <input type="hidden" name="rechargeMethod"  value="">
        <input type="hidden" name="payBy"  value="">
        <input type="hidden" name="isRechargeAccount" value="<?= $isRechargeAccount ?>">
        <?= $form->hiddenField($model, 'preData', ['value' => json_encode($model->preData)]); ?> 
        <div class="col-xs-12">
		<?php 
		       if($isRechargeAccount == 1)
				{
					?>
                    <p class="text-danger text-center"><span style="font-size: 20px">Credit limit exceed. Please recharge your account</span><br></p>
                    <?	
				} ?>
		</div>	

		<?
        if ($agentTransModel != '')
        {
            ?>
            <div class="col-xs-12">
                <?
                if ($agentTransModel->apg_status == 2)
                {
                    ?>
                    <p class="text-danger text-center"><span style="font-size: 20px">Transaction Failed</span><br>( Transaction ID: <?= $agentTransModel->apg_code ?>. Amount: <i class="fa fa-inr"></i><?= abs($agentTransModel->apg_amount) ?>)</p>
                    <?
                }
                else if ($agentTransModel->apg_status == 1)
                {
                    ?>
                    <p class=" text-center"><span style="font-size: 20px;color: #47bb74">Transaction Successful</span><br>( Transaction ID: <?= $agentTransModel->apg_code ?>. Amount: <i class="fa fa-inr"></i><?= abs($agentTransModel->apg_amount) ?>)</p>
                <? } ?>
				
            </div>
        <? } ?>
        <div class="col-xs-12">
            <div class="col-xs-12">
                <h3 class="mb30">Payment</h3>
            </div>
            <button class="col-xs-12 col-md-5 btn btn-primary pull-left" style="height: 140px; line-height: 16px;" type="submit" onclick="return onPaymentOption(1);" name="payByAgent">    
                <div class="col-xs-12 col-md-6">
                    <div class="text-left">
                        <h3 class="mt0 mb5">I WILL PAY GOZO</h3>Please do not collect any payment from the customer<br>we will charge your account.
                    </div>
                    <h5 class="mt20  text-left">Gozo will charge you <i class="fa fa-inr"></i><?= $bkgInvoice->bkg_total_amount; ?> for the booking</h5>
                </div>
            </button>

            <button class="col-xs-12 col-md-5 btn btn-primary  ml5 pull-right" style="height: 140px; line-height: 16px;" type="submit" onclick="return onPaymentOption(2);" name="payByCustomer">
                <div class="col-xs-12 col-md-6">
                    <div class="text-left">
                        <h3 class="mt0 mb5">CUSTOMER WILL PAY GOZO</h3>
                        Please collect full payment from the customer<br>
                        Gozo will charge the customer directly.
                    </div>
                    <div class=" text-left">
                        <h5 class="mt20 text-left">Gozo will charge customer <i class="fa fa-inr"></i><?= $bkgInvoice->bkg_total_amount; ?> for the booking</h5>
                    </div>
                </div>
            </button>
        </div>
        <div class="col-xs-12 mt30">
            <button type="submit" class="pull-left btn btn-danger btn-lg pl25 pr25 pt30 pb30" name="step10ToStep9"><b> <i class="fa fa-arrow-left"></i> Previous</b></button>
        </div>
        <?php $this->endWidget(); ?>
    </div>
</div>

<script>
history.pushState(null, null, location.href);
    window.onpopstate = function () {
        history.go(1);
    };
    function onPaymentOption(opt) {
        $("[name='paymentOpt']").val(0);
        if (opt == 1) {
            $("[name='payBy']").val(1);

            if ($("[name='isRechargeAccount']").val() == 1) {
                rechargedialog = bootbox.dialog({
                    message: "<div class='row'><div class='col-xs-12'><input type='text' required='true' name='recharge_amount' class='form-control' value='<?= $bkgInvoice->bkg_total_amount; ?>' min='<?= $bkgInvoice->bkg_total_amount; ?>'>" +
                            "<h4 class='mt20'>Charge me <i class='fa fa-inr'></i><?= $bkgInvoice->bkg_total_amount; ?> on (credit limit exceeded)</h4>" +
                            "<select class='form-control' name='paymentOpt'>" +
                            "<option value='1'>on my PayTM account</option>" +
                            "<option value='2'>my credit card</option>" +
                            "</select><div class='col-xs-12 mt10 mb5' style='font-size: 12px'>Note: <span class='text-danger'>2% processing fee will be charged</span></div><div class='col-xs-12 text-center'><button type='button' class='btn btn-primary mt10' name='rechargesubmit' onClick='rechargeAccount()'>RECHARGE</button></div></div></div>",
                    title: 'RECHARGE ACCOUNT',
                    className: "bootbox-sm",
                    onEscape: function ()
                    {
                        rechargedialog.modal('hide');
                    }
                });
                return false;
            }

        }
        if (opt == 2) {
            $("[name='rechargeAmount']").val("");
            $("[name='rechargeMethod']").val("");
            $("[name='payBy']").val(2);
        }
        return true;
    }

    function rechargeAccount() {
        $("[name='rechargeAmount']").val($("[name='recharge_amount']").val());
        $("[name='rechargeMethod']").val($("[name='paymentOpt']").val());

        if ($("[name='rechargeAmount']").val() >=<?= $bkgInvoice->bkg_total_amount; ?>) {
            rechargedialog.modal('hide');
            $('#agent-booking-spot10').submit();
            return true;
        } else {
            alert("Recharge amount must be greater than equal to total amount.");
            return false;
        }

    }
</script>
