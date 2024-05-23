<?php
$this->layout = 'column1';
?>
<div class="container content-padding mb5">
    <div class="above-overlay">
        <div class="bottom-0 uppercase color-white"><h3 class="mb0 text-center uppercase"><?php echo $this->pageTitle; ?></h3>    
		</div>
    </div>
    <div class="overlay bg-green opacity-80"></div>
</div>
<style>
.active {opacity: 0.5;}
</style>
<?php
if ($models > 0) {
    foreach ($models as $order) {
        ?>
        <div class="content-boxed-widget">
            <div class="content p0 pb10 bottom-0">
                <h4 class="text-center text-uppercase color-green3-dark"><?php echo $order['vch_title'] . " - " . $order['vch_code']; ?></h4>
                <div class="decoration mb20"></div>
                <div class="checkout-total">					
                    <span class="font-14"><?php echo $order['vch_desc']; ?></span>
                    <div class="clear"></div>

                    <strong class="font-14 regularbold">Order Number</strong>
                    <span class="font-14"><?php echo $order['vor_number']; ?></span>
                    <div class="clear"></div>

                    <!--<strong class="font-14 regularbold">Voucher Type</strong>
                    <span class="font-14"><?php //echo Vouchers::getType($order['vch_type']); ?></span>
                    <div class="clear"></div>-->

                    <strong class="font-14 regularbold">Quantity</strong>
                    <span class="font-14"><?php echo $order['vod_vch_qty']; ?></span>
                    <div class="clear"></div>

                    <strong class="font-14 regularbold">Price</strong>
                    <span class="font-14">&#8377;<?php echo $order['vch_selling_price']; ?></span>
                    <div class="clear"></div>
					<?php if(!empty($order['vor_date'])) { ?>
					 <strong class="font-14 regularbold">Purchase Date:</strong>
                    <span class="font-14"><?php echo date('d/m/Y', strtotime($order['vor_date'])) . ', ' . date('h:i A', strtotime($order['vor_date'])); ?></span>
                    <div class="clear"></div>
					<?php }  ?>
					<strong class="font-14 regularbold">Status</strong>
                    <span class="font-14 <?php echo ($order['vor_active'] == 1) ? 'color-green3-dark' : 'red-text-color'; ?>"><?php echo VoucherOrder::getStatus($order['vor_active']); ?></span>
                    <div class="clear"></div>
                </div>

                <div class="checkout-total">
                    <div class="decoration mb20"></div>
                    <strong class="font-16 half-top">Total Amount</strong>
                    <span class="font-16 ultrabold half-top">&#8377;<?php echo $order['vod_vch_price'] ?></span>
                    <div class="clear"></div>
                </div>

            </div>
        </div>
        <?php
    }
}
if (count($models) == 0) {
    ?>
    <div class="content-boxed-widget">
        <div class="content p10 bottom-0">
            <div class="text-center">
                <div class="list_heading text-center pt20 pb20" style="background: #f77026; color: #fff;">
                    <b>Sorry!! No records found</b>
                </div> 
            </div>
        </div>
    </div>
<?php } ?>
<div class="text-right pagination">
    <?php
    $this->widget('booster.widgets.TbPager', array('pages' => $usersList->pagination));
    ?>
</div>