<section>
	<div>
        <div class="row row inner-top-mune m0 mb20">
            <div class="col-xs-12 h4 m0 text-uppercase">Order Details&nbsp;&nbsp; ( Order Number : <?=$model->vor_number;?> )</div>
        </div>
		<div class="row">
			<?php
			foreach ($model->voucherOrderDetails as $orderDetails)
			{
				?>
				<div class="col-xs-12 col-sm-12 coupon_box">
					<div class="panel panel-default">
						<div class="p20 panel-body">
							<div class="col-sm-4"><b><?php echo $orderDetails->vodVch->vch_title; ?></b> - <?php echo $orderDetails->vodVch->vch_desc;?></div>
							<div class="col-sm-4"><?php echo $orderDetails->vod_vch_qty; ?></div>
							<div class="col-sm-4"><i class='fa fa-inr'></i><?php echo $orderDetails->vod_vch_price; ?></div>
						</div>

					</div>
				</div>
			<?php }
			?>
			<div class="col-xs-12 col-sm-12">
				<div class="p20 panel-body col-sm-12">
					<div class="col-sm-8">&nbsp;</div>
					<div class="col-sm-2"><i class='fa fa-inr'></i><?= $model->vor_total_price; ?></div>
					<div class="col-sm-2">&nbsp;</div>
				</div>
			</div>
        </div>
		
		<div class="row">
            <div class="row row inner-top-mune m0 mb20">
                <div class="col-xs-12 h4 m0 text-uppercase">Billing Information</div>
            </div>
			<?php					
			if (!$isMobile)
			{
				/* @var $model VoucherOrder */
				$this->renderPartial("summaryBilling", ["model" => $model, 'isredirct' => $isredirct], false);
			}
			?>
        </div>
		
</section>