<?php
$this->layout = 'column1';
?>
<div class="container content-padding mb5">
    <div class="above-overlay">
        <div class="bottom-0 uppercase color-white"><h3 class="mb0 text-center uppercase">Order Details</h3>    
		</div>
    </div>
    <div class="overlay bg-green opacity-80"></div>
</div>

<div class="row">
    <div class="col-xs-12">
        <?php echo CHtml::errorSummary($model); ?>
    </div>
    <div class="col-xs-12 text-center">
        <?php if (Yii::app()->user->hasFlash('success')): ?>
            <div class="alert alert-success" style="padding: 10px">
                <?php echo Yii::app()->user->getFlash('success'); ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="content-boxed-widget">
    <div class="content p0 pb10 bottom-0">           
            <div class="font-13 color-green3-dark mb15 text-center">( Order Number : <?php echo $model->vor_number; ?> )</div>
            <div class="clear"></div>
            <?php
            foreach ($model->voucherOrderDetails as $orderDetails) {
                ?>
                <div class="checkout-total">
                    <strong class="font-14 regularbold">Voucher</strong>
                    <span class="font-14"><strong><?php echo $orderDetails->vodVch->vch_code; ?></strong> - <?php echo $orderDetails->vodVch->vch_title; ?></span>
                    <div class="clear"></div>

                    <strong class="font-14 regularbold">Quantity</strong>
                    <span class="font-14"><?php echo $orderDetails->vod_vch_qty; ?></span>
                    <div class="clear"></div>

                    <strong class="font-14 regularbold">Price</strong>
                    <span class="font-14">&#8377;<?php echo $orderDetails->vod_vch_price; ?></span>
                    <div class="clear"></div>
                </div>
            <div class="decoration mb20"></div>

            <?php }
            ?>
            <div class="checkout-total">
                <strong class="font-16 half-top">Total</strong>
                <span class="font-22 ultrabold half-top">&#8377;<b><?php echo $model->vor_total_price ?></b></span>
                <div class="clear"></div>
            </div>
    </div>
</div> 
<?php
$this->renderPartial("summaryBilling", ["model" => $model, 'isredirct' => $isredirct], false);
?>