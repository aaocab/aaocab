<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/fontawesome-web/css/all.min.css?v0.6');
?>

<?php
$this->layout = 'column1';
?>
<div class="content-boxed-widget p10 mb10 top-10">
    <div class="content bottom-0 uppercase"><h5 class="mb0"><?php echo $this->pageTitle; ?></h5></div>
</div>
<br/>
<div class="content-boxed-widget p0 mt30 n mb10">
    <div class="content bottom-0 uppercase p0 mb30"><img src="/images/gift_voucher.jpg" alt="Buy Gozo Voucher" class="preload-image responsive-image bottom-5"></div>
</div>
 
<?php
$i = 1;
if(count($data) > 0) {
foreach ($data as $d) {
    $hashVoucherId = Yii::app()->shortHash->hash($d['vch_id']);
    ?>
    <div class="container content-padding mb0 p5">
        <div class="above-overlay">
            <h4 class="bold top-10 color-white text-center"><b><?php echo $d['vch_title'] . " ( " .$d['vch_code']. " )"; ?></b></h4>
        </div>
        <div class="overlay bg-blue-dark opacity-90"></div>
    </div>
    <div class="container content-padding p10 pb10">
        <div class="above-overlay">
			<?php
				$desc = (strlen($d['vch_desc'])<50)? $d['vch_desc'] : substr($d['vch_desc'],0,50).'...';				
			?>
            <p><b><?php echo $desc; ?></b><br/>
			<a href="javascript:void(0);" data-menu="modal-voucher-list<?php echo $i; ?>">More Details</a></p>
            <div class="one-half">
                <p class="mb5 font-34 color-green3-dark">&#8377;<b><?php echo $d['vch_selling_price']; ?></b><span class="color-gray-dark font-22">.00</span></p>
                <?php if (!empty($d['vch_valid_to'])) { ?>
                    <p class="font-11 color-gray-dark mb10">
                        <?php echo 'Valid Till  ' . date('jS F, Y', strtotime($d['vch_valid_to'])); ?>
                    </p>
                <?php } ?>
            </div>
            <div class="one-half last-column text-right"><b>
                    <a href="<?= Yii::app()->createUrl('voucher/detail', ['voucherId' => $hashVoucherId]) ?>" class="uppercase btn-orange shadow-medium pl15 pr15 font-16" id="Buy Voucher" title="Buy Voucher">Add to Cart</a>
                </b>
            </div>
            <div class="clear"></div>
        </div>
        <div class="overlay bg-white"></div>

    </div>
	<div id="modal-voucher-list<?php echo $i; ?>" data-selected="menu-components" data-width="300" data-height="325" class="menu-box menu-modal">
   <div class="menu-title"><a href="#" class="menu-hide mt15 n p5" id="menubox<?php echo $id; ?>"><i class="fa fa-times"></i></a>
        <h1>Description</h1>
    </div>
    <div class="menu-page pl15">
    <?php echo $d['vch_desc']; ?><br/>
	<p> Max Purchase Per User :  <?php echo $d['vch_user_purchase_limit']; ?><br/>
	Max Redeem Per User : <?php echo $d['vch_redeem_user_limit']; ?></p>
  </div> </div>
<?php 
	$i++;
} 
} else {?>
 <div class="container content-padding mb0 p5">
	<div class="above-overlay">
		<h4 class="bold top-10 color-black text-center"><b>Currently no vouchers are available. Please keep checking</b></h4>
	</div>
	</div>
<?php  } ?>
</div>