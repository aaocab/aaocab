       
<div class="container mt50">
<!--    <div class="row">
        <div class="col-xs-12 text-center"><img src="/images/logo2.png" alt="aaocab:India's leader in inter-city taxi | Great service. Price guarantee. Awesome reviews."></div>
    </div>-->

    <div class="row spot-panel">
        <div class="col-xs-12 float-none marginauto">
            <?
            if (!$isBookingConfirmed)
            {
                if ($isRechargeSuccess)
                {
                    ?>
                    <p>Transaction successful.</p>
                    <p>Amount: <?= abs($agentTransModel->agt_trans_amount) ?></p>
                    <p>Transaction ID: <?= $agentTransModel->agt_trans_code ?></p>
                    <?
                }
                else
                {
                    ?>
                    <div class="col-xs-12">
                        <p class="text-danger text-center"><span style="font-size: 20px">Transaction Failed.</span>( Transaction ID: <?= $agentTransModel->agt_trans_code ?>. Amount: <i class="fa fa-inr"></i><?= abs($agentTransModel->agt_trans_amount) ?>)</p>
                        <p class="pull-left"></p>
                        <p class="pull-right"></p>
                    </div>
                    <?
                }
            }
            ?>
            <br>
            <div class="col-xs-12">
                <h4>Booking Details</h4>
                <div class="col-xs-6">Trip Type: <?= Booking::model()->booking_type[$model->bkg_booking_type] ?></div>
                <div class="col-xs-6">Source City: <?= $model->bkgFromCity->cty_name ?></div>
                <div class="col-xs-6">Destination City: <?= $model->bkgToCity->cty_name ?></div>
                <div class="col-xs-6">Pickup Date: <?= date('d/m/Y H:i:s', strtotime($model->bkg_pickup_date)) ?></div>
            </div>
            <?
            $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
                'id'                     => 'agent-booking-spot10', 'enableClientValidation' => FALSE,
                'clientOptions'          => array('validateOnSubmit' => true, 'errorCssClass' => 'has-error'),
                'enableAjaxValidation'   => false,
                'errorMessageCssClass'   => 'help-block',
                'action'                 => Yii::app()->createUrl('agent/booking/spot'),
                'htmlOptions'            => array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data'),
            ));
            ?>
            <? if (!$isBookingConfirmed && $isRechargeSuccess)
            { ?>  
                <button class="btn btn-primary" type="submit" name="btnconfirmbooking">Confirm Booking</button>
            <? } ?>
<?php $this->endWidget(); ?>
        </div>
    </div>
</div>
<script>
history.pushState(null, null, location.href);
    window.onpopstate = function () {
        history.go(1);
    };
</script>