<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/fontawesome-web/css/all.min.css?v0.6');
?>

<?php
//print_r($model);
$promoRule				 = Promos::model()->getExpTimeAdvPromo($model->bkg_create_date, $model->bookingRoutes[0]->brt_pickup_datetime);

//$promoArr=Promos::allApplicableCodes($model->bkgInvoice->bkg_base_amount, $model->bkg_pickup_date, $model->bkg_pickup_date, $model->bkg_vehicle_type_id, $platform = 1, $model->bkg_booking_type, $model->bkg_from_city_id, $model->bkg_to_city_id);
$promoArr   = Promos::allApplicableCodes($model); 
$hash = Yii::app()->shortHash->hash($model->bkg_id);
$creditVal = (is_array($creditVal)) ? $creditVal['credits']: $creditVal;

?>

<div id="sidebar-right-over2" data-selected="menu-components" class="menu-box menu-bottom sidebar-widget-style" style="transition: all 300ms ease 0s;">
	<div class="menu-title">
		<h1 class="font-24">Apply Promo</h1>
		<a href="javascript:void(0);" class="menu-hide pt0"><i class="fa fa-times"></i></a>
	</div>
	
	<div class="menu-page p15 pt0">
		<div id="spanPromoCreditSucc" class="mb10 color-green3-dark"></div>
		<div id="errMsgPromo" class="color-red-dark"></div>
        <div class="content p0 mb10" id="walletApplyDiv">
			<div class="input-simple-1 has-icon input-blue"><em>Apply Wallet Balance(₹<?=$walletBalance ?>)</em>
				<div class="from-right"><input type="number" id="BookingInvoice_bkg_wallet_used" name="BookingInvoice_bkg_wallet_used" class="form-control" placeholder="Enter Amount" value="<?=$walletBalance?>"></div>
				<div class="from-left mr0 ml0"><button type="submit" class="btn-submit-orange pull-right pt10 pb10 pl10" onclick="prmObj.applyPromo(5, $('#BookingInvoice_bkg_wallet_used').val());">Apply</button></div>
				<div class="clear"></div>
			</div>
		</div>
		<div class="line-height16 radio bottom-10">
                    <div class="one-half mr5">
                        <label class="radio-style">
                            <input type="radio" name="promo" id="applyPromo" value="0" checked="checked" class="mt5  ">
                            <span class="font-14 p5">Apply Promo</span>
                        </label>
                    </div>
                    <div class="one-half last-column">
                        <label class="radio-style">
                            <input type="radio" name="gozocoins" id="applyGozocoins" value="1"   class="mt5  "> 
                            <span class="font-14 p5">Apply Gozocoins</span>
                        </label>
                    </div>
                    <div class="clear"></div>
                    </div>
		
	</div>
    <?php
		$isPromoApplicable	 = BookingSub::model()->getApplicable($model->bkg_from_city_id, $model->bkg_to_city_id, 1);
		if ($isPromoApplicable)
		{			
	?>
		<div class="content promoApplyDiv mb10" id="promoDiv">
			<div class="input-simple-1 has-icon input-blue"><em>Apply Promo Code</em>
				<div class="from-right"><input type="text" class="BookingInvoice_bkg_promo1_code txtPromoCode" id="BookingInvoice_bkg_promo1_code" name="BookingInvoice_bkg_promo1_code" placeholder="Enter promo code"></div>
				<div class="from-left mr0 ml0"><button type="submit" class="btn-submit-orange pull-right pt10 pb10 pl10" onclick="prmObj.applyPromo(1, $('#BookingInvoice_bkg_promo1_code').val())">Apply</button></div>
				<div class="clear"></div>
			</div>
		</div>
    <?php
		}    
		if ($creditVal > 0 && ($model->bkgInvoice->bkg_credits_used == '' || $model->bkgInvoice->bkg_credits_used == 0) && $model->bkg_flexxi_type != 2 && Yii::app()->user->getId() != '')
		{
			$applyCredits = 'block';
		}
	?>
		<div class="content creditApplyDiv" id="gozoCoinsDiv" style="display: none">
			<div class="input-simple-1 has-icon input-blue bottom-10"><em>Apply Gozo Coins</em>
				<div class="from-right"><input type="number" id="creditvalamt" credits="<?= $creditVal ?>" name="creditvalamt" class="form-control creditvalamt" value="<?= $creditVal ?>"></div>
				<div class="from-left mr0 ml0"><button type="submit" class="btn-submit-orange pt5 pb5 pl10 pull-right" onclick="prmObj.applyPromo(3, $('#creditvalamt').val());">Apply</button></div>
				<div class="clear"></div>
			</div>
		</div>
		
		<div class="content mt20 light-bg walletRemoveDiv tdwallet hide">
			Wallet balance of ₹<span class="walletUsed"></span> to be applied <button class="" onclick="prmObj.applyPromo(6);"><i class="fas fa-times-circle"></i></button>
		</div>
		<div class="content m20 light-bg r-5 promoRemoveDiv <?=($model->bkgInvoice->bkg_promo1_code!='')?'':'hide';?>">
			Applied promo code: <b><span id="txtpromo" class="text-uppercase txtpromo"> <?= $model->bkgInvoice->bkg_promo1_code ?> Applied</span></b>
            <button class="" onclick="prmObj.applyPromo(2)"><i class="fas fa-times-circle"></i></button>
		</div>

        <div class="content m20 light-bg creditRemoveDiv r-5 hide">
			Gozocoins ₹<span class="creditUsed txtGozoCoinsUsed"></span> applied<button class="" onclick="prmObj.applyPromo(4);"><i class="fas fa-times-circle"></i></button>
		</div>
		<div class="content mt20 light-bg txtErrorMsg hide"></div>
        
		<div class="content mb20 autoPromoApplyDiv" id="promoList">
		<?php
		if ($promoArr->getRowCount() > 0 )
		{
			?><b class="uppercase color-green3-dark">Offers</b>
			<?php
			$arr_promo = array();

			while ($val = $promoArr->read())
			{
			?>
                    <div class="mb10"><b><?=$val['prm_code']?></b><span class="pull-right mb0 hide sel_promo_app color-gray-dark" id="appl_<?= $val['prm_id'] ?>"><b><i>Applied</i></b></span>
						<label class="btn-apply sel_promo " style="float:right;cursor: pointer;"  
									   autocomplete="off"  name="Booking_promosAutoApply" 
									   activate="<?= $val['prm_activate_on'] ?>" 
									   id="<?= $val['prm_id'] ?>"
									   value="<?= $val['prm_id'] ?>" onclick="prmObj.applyPromo(1, '<?= $val['prm_code'] ?>')">Apply	
								</label>
					</br>
					</div>   
					<?= $val['prm_desc'] ?>
					  <input type="hidden" id="disval_<?= $val['prm_id'] ?>" value="<?= $val['prm_code'] ?>">
		
		<?php
			}  
		}   
	?>
	</div>
    <input type="hidden" id="all_promo_codes" value='<?php echo json_encode($arr_promo); ?>' />
	<input type="hidden" id="isPromoApplied1" value="1" />
	<input type="hidden" id="coinPromoStatus1" value="0" />
	</div>
</div>
<script>
	var promo = new Promo();
	var model = {};
	
	function useWallet(flagUseWallet)
	{
		var walletAmount = $('#BookingInvoice_bkg_wallet_used').val();
		$('#inlineCheckbox1').prop('checked', false);
		var totWalletAmt = '<?= $walletBalance | 0 ?>';
		if (walletAmount > 0)
		{
			if (parseInt(walletAmount) <= parseInt(totWalletAmt))
			{
				var creditapplied = $('#creditapplied').val();
				$('#errMsgWallet').html("");
				var model = {};
				var promo = new Promo();
				model.url = "/users/usewallet";
				model.bkg_id = bid;
				model.bkghash = '<?= $hash ?>';
				promo.model = model;
				model.amount = walletAmount;
				model.flagUseWallet = flagUseWallet;
				model.credit_amount = creditapplied;
				promo.PromoCreditAjax();
			} else
			{
				$('#errMsgWallet').html("Amount cann't be greater than wallet balance.");
			}
		} else
		{
			$('#errMsgWallet').html("Amount must greater than 0.");
		}
	};
	
	$('#applyPromo').click(function ()
	{
		huiObj.checkPromo();
		prmObj.applyPromo(4);
	});
	
	$('#applyGozocoins').click(function ()
	{
		huiObj.checkGozocoins();
		prmObj.applyPromo(2);
	});
	
//	
//	$('#applyPromo').click(function ()
//	{
//		$('#gozoCoinsDiv').hide();
//		$('#promoDiv').show();
//		$('#promoList').show();
//		prmObj.applyPromo(4);
//		$('#applyGozocoins').prop('checked', false);
//		$('#applyPromo').prop('checked', true);
//	});
//	$('#applyGozocoins').click(function ()
//	{
//		$('#promoDiv').hide();
//		$('#promoList').hide();
//		$('#gozoCoinsDiv').css("display", "block");
//		prmObj.applyPromo(2);
//		$('#applyGozocoins').prop('checked', true);
//		$('#applyPromo').prop('checked', false);
//	});
</script> 