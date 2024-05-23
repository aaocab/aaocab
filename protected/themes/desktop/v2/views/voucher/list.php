
<div class="row title-widget">
	<div class="col-12">
		<div class="container">
			<?php echo $this->pageTitle; ?>
		</div>
	</div>
</div>
<div class="row mb30">
	<div class="col-12">
		<img src="/images/banner-voucher.jpg" alt="" class="img-fluid">
	</div>
</div>

<div class="row">
	<div class="col-12">
		<div class="container">
			<div class="row">
				<?php
				$i = 1;
				
				foreach ($data as $d)
				{
					$hashVoucherId = Yii::app()->shortHash->hash($d['vch_id']);
					?>
					<div class="col-12 col-lg-4 mb30">
						<div class="voucher-panel">
							<div class="row">
								<div class="col-12">
									<div class="voucher-header"><?php echo $d['vch_title']; ?></div>
								</div>
								<div class="col-12">
									<div class="voucher-body">
										<?php
											$desc = (strlen($d['vch_desc'])<45)? $d['vch_desc'] : substr($d['vch_desc'],0,45).'...';				
										?>
										<?php echo  $desc;?><br> 
										<a href="javascript:void(0);" class="showDescrip" data-target="#voucherDescriptionModel<?php echo $i;?>">more details</a>
										<div class="row mt10">
											<div class="col-lg-7">
                                                                                            <span class="font-30 color-green2">&#x20B9;</span><span class="font-40 color-green2"><b><?php echo $d['vch_selling_price'];?></b></span><span class="font-30 color-gray"><sup>00</sup></span><br>
												<?php if (!empty($d['vch_valid_to'])) { ?>												
											   <span class="font-12 color-gray"> <?php echo 'Valid Till  ' . date('jS F, Y', strtotime($d['vch_valid_to'])); ?></span>
												<?php } ?>
											</div>
											<div class="col-lg-5 text-right btn-buy pl0">
												<a href="<?= Yii::app()->createUrl('voucher/detail', ['voucherId' => $hashVoucherId]) ?>" id="Buy Voucher" title="Buy Voucher">Add to cart</a>
											</div>
										</div>
									</div>
								</div>

							</div>
						</div>
					</div>
					<div class="modal bd-example-modal-lg show" id="voucherDescriptionModel<?php echo $i;?>" tabindex="-1" role="dialog" aria-labelledby="bkSignupModelLabel" style="display: none; padding-right: 10px;">
						<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="signupModalLabel"><?php echo $d['vch_title']; ?></h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">×</span>
									</button>
								</div>
								<div class="modal-body pt30 pb30" id="bkSignupModelBody">
								<?php echo $d['vch_desc']; ?>
								</div>
							</div>
						</div>
					</div>
				<?php
				$i++; 
				}
				if(count($data)==0) 
				{
					echo '<h3>Sorry, no voucher are available.</h3>';
                }
				?>
			</div>
		</div>
	</div>
</div>
<script>
$('.showDescrip').click(function () {
	let target = $(this).data('target');
	$(target).modal('show');	
});
</script>