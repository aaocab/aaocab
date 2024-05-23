<div class="row">
    <div class="panel panel-default">
        <div class="panel-body">
<div class="col-xs-12 text-center h2 mt0">
	<label for="type" class="control-label"><span style="font-weight: normal; font-size: 30px;">VOUCHER CODE:</span> </label>
	<?= $voucherModel->vch_code ?>
</div>

<div class="col-xs-11 p40">
	<div class="col-xs-12">
		<div class="col-xs-12">
			<div class="panel panel-default panel-border">
				<div class="panel-body">
					<div class="col-xs-12 mb10"><b>DETAILS</b></div>
					<div class="col-xs-12"><b>Description:</b> <?= $voucherModel->vch_title ?></div>
					<?php if($voucherModel->vch_valid_from) { ?>
					<div class="col-xs-6 mt5"><b>Valid Form:</b> <?= date('d/m/Y h:i A', strtotime($voucherModel->vch_valid_from)); ?></div>
					 <?php } ?>
					 <?php if($voucherModel->vch_valid_to) { ?>
					<div class="col-xs-6 mt5"><b>Valid Upto:</b> <?= date('d/m/Y h:i A', strtotime($voucherModel->vch_valid_to)); ?></div>
					<?php } ?>
					<div class="col-xs-6 mt5"><b>Selling Price:</b> <?=$voucherModel->vch_selling_price?></div>
					<div class="col-xs-6 mt5"><b>Max Allowed Counter:</b>&nbsp;<?=$voucherModel->vch_max_allowed_limit?></div>
					<div class="col-xs-6 mt5"><b>Type:</b> <?=($voucherModel->vch_type == 1)?"Promo":"Wallet"?></div>
					<?php if($voucherModel->vch_type == 1) { ?>
					<div class="col-xs-6 mt5"><b>Promo Name:</b> <?= $promoList[$voucherModel->vch_promo_id]; ?></div>
					<?php } ?>
					<?php if($voucherModel->vch_type == 2) { ?>
					<div class="col-xs-6 mt5"><b>Wallet Amount:</b> <?= $voucherModel->vch_wallet_amt; ?></div>
					<?php } ?>
					<div class="col-xs-6 mt5"><b>Max Allowed Limit:</b> <?= $voucherModel->vch_max_allowed_limit; ?></div>
					<div class="col-xs-6 mt5"><b>Max Redeem Per User:</b> <?= $voucherModel->vch_redeem_user_limit; ?></div>
					<div class="col-xs-6 mt5"><b>User Purchase Limit:</b> <?= $voucherModel->vch_user_purchase_limit; ?></div>
					<div class="col-xs-6 mt5"><b>Partner Purchase Limit:</b> <?= $voucherModel->vch_partner_purchase_limit; ?></div>





					<!--<div class="col-xs-6 mt5"><b>:</b> <?//=$voucherModel->?></div>-->
				</div>
			</div>
		</div>

		<!--<div class="col-xs-3">
			<div class="panel panel-default panel-border">
				<div class="panel-body">
					<label class="mb10"><b></b></label>
				</div>
			</div>
		</div>-->
	</div>

	
</div>


  </div>
        </div>    
    </div>