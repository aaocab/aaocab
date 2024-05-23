<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/fontawesome-web/css/all.min.css?v0.6');
?>

<?php
$this->layout = 'column1';
?>
<div class="content-boxed-widget p10 mb10 top-10">
    <div class="content bottom-0 uppercase pl0"><h3 class="mb0">Checkout </h3></div>
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


<?php
if (count($cartData) > 0){
    ?>
    <?php
    foreach ($cartData as $c) {
        ?>
        <div class="content-boxed-widget pl15">
            <div class="content pl0 mb0">
                <div class="checkout-total">
                    <strong class="font-14 bold">Voucher</strong>
                    <span class="font-14"><strong><?php echo $c['code']; ?></strong> - <?= $c['title']; ?></span>
                    <div class="clear"></div>

                    <strong class="font-14 bold">Quantity</strong>
                    <span class="font-14"><?php echo $c['qty'] | 0; ?></span>
                    <div class="clear"></div>

                    <strong class="font-14 bold">Price / Quantity</strong>
                    <span class="font-14"><?php echo round($c['price'] / $c['qty']); ?></span>
                    <div class="clear"></div>

                    <strong class="font-14 bold">Price</strong>
                    <span class="font-14">&#8377; <?php echo $c['price']; ?></span>
                    <div class="clear"></div>
                </div>
            </div>
        </div>

    <?php } ?>					
    <div class="content-boxed-widget pl15">
        <div class="content pl0 mb0">
            <div class="checkout-total">
                <strong class="font-14 bold">Total</strong>
                <span class="font-30 color-green3-dark">&#8377;<b><?php echo $cartBalance; ?></b></span>
		    <div class="clear"></div>					
            </div>
        </div>
    </div>

    <?php
} else {
    ?>

    <div class="content p10 bottom-0"><b>Cart is empty</b></div>

    <?php
}
?>		



		
<div class="accordion accordion-style-1">
	<div class="content-boxed-widget p0 accordion-path">
	<div class="accordion accordion-style-0">
		<div class="accordion-border">
			<a href="javascript:void(0)" class="font18" data-accordion="accordion-999"><span class="uppercase">Billing Information</span><i class="fa fa-plus rotate-180"></i></a>
			<div class="accordion-content" id="accordion-999" style="display: block;">
				<div class="accordion-text"><div class="content p0">
							<?php
            $this->renderPartial("voucherbillingdetails", ["model" => $model, 'isredirct' => $isredirct], false);
            ?>
							
					</div></div>
				</div>
			</div>
		</div>
	</div></div>
<div class="content-boxed-widget p10">       
 </div>         
			
		          <?php
            $this->renderPartial("paymentWidget", ["model" => $model, 'isredirct' => $isredirct], false);
            ?>
    