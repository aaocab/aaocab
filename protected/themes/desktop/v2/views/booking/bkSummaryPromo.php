<?php
$promoRule = Promos::model()->getExpTimeAdvPromo($model->bkg_create_date, $model->bookingRoutes[0]->brt_pickup_datetime);


//$promoArr	 = Promos::allApplicableCodes($model->bkgInvoice->bkg_base_amount, $model->bkg_pickup_date, $model->bkg_pickup_date, $model->bkg_vehicle_type_id, $platform	 = 1, $model->bkg_booking_type, $model->bkg_from_city_id, $model->bkg_to_city_id);
$promoArr = Promos::allApplicableCodes($model);
?>
<div class="col-12" id="promo">
	<?php
	if ($model->bkg_status == 15)
	{
		?>

		<div class="bg-white-box">

			<div class="row m12 ">
				<div class="col-6 radio">
					<label class="radio2-style">
						<input type="radio" name="promo" id="applyPromo" value="0" checked="checked" class="mt5 clsPromo autoApplyPromoRadio"> <span style="font-size: 16px;font-weight: bold;padding: 5px">Apply Promo</span>
						<span class="checkmark-2"></span>
					</label>
				</div>
				<div class="col-6 radio">
					<label class="radio2-style">
						<input type="radio" name="gozocoins" id="applyGozocoins" value="1" class="mt5 autoApplyGozocoinsRadio"> <span style="font-size: 16px;font-weight: bold;padding: 5px">Enter Gozo Coins</span>
						<span class="checkmark-2"></span>
					</label>
				</div>
			</div>

			<div class="row">
				<div class="col-12 col-md-12">
					<div class="row mb20">
						<?php
						$isPromoApplicable = Route::getApplicable($model->bkg_from_city_id, $model->bkg_to_city_id, 1);
						if ($isPromoApplicable)
						{
							?>
							<div class="col-6 col-12 promoApplyDiv" id="promoApplyDiv">

								<div class="input-group m-t-10">
									<input type="text" id="BookingInvoice_bkg_promo1_code" name="BookingInvoice_bkg_promo1_code" class="form-control txtPromoCode" placeholder="Enter promo">
									<span class="input-group-btn">
										<button type="button" class="btn btn-effect-ripple btn-success" onclick="prmObj.applyPromo(1, $('#BookingInvoice_bkg_promo1_code').val());">Apply</button>
									</span>
								</div>
								<div id="errMsgPromo" style="font-weight: bold;color: #FF0000;"></div>               

							</div>
						<? } ?>
						<?
						if ($creditVal > 0 && ($model->bkgInvoice->bkg_credits_used == '' || $model->bkgInvoice->bkg_credits_used == 0) && $model->bkg_flexxi_type != 2 && Yii::app()->user->getId() != '')
						{
							$applyCredits = 'block';
						}
						if ($model->bkgUserInfo->bkg_user_id > 0 && Yii::app()->user->getId() > 0)
						{
							?>

							<div class="col-6 col-12  creditApplyDiv" id="gozoCoinsApply" style="display: none">			

								<div class="input-group m-t-10">
									<input type="number" id="creditvalamt" credits="<?= $creditVal ?>" name="creditvalamt" class="form-control creditvalamt" value="<?= $creditVal ?>" placeholder="Enter gozocoins">
									<span class="input-group-btn">
										<button type="button" class="btn btn-effect-ripple btn-success" onclick="prmObj.applyPromo(3, $('#creditvalamt').val());">Apply</button>
									</span>
								</div>
							</div>
						<?php } ?>
						<div class="col-xs-12 mt10" id="promo_msgdata"></div>

					</div>
					<div id="showPromoDescApplied" class="col-xs-12 text-center mt5   showPromoDescApplied" style="font-size: 12px; font-weight:bold"></div>
					<div class="col-xs-12 mt10 autoPromoApplyDiv" id="autoPromoApplyDiv" data-toggle="buttons">	
						<div class="">
							<?
							if ($promoArr->getRowCount() > 0 && $isPromoApplicable)
							{
								?><div class="heading-part mt10 text-uppercase"><b>Offers</b></div><div class="box_2">
								<?
								$arr_promo = array();

								while ($val = $promoArr->read())
								{
									$arr_promo[] = $val['prm_id'];
									?>
										<div class="col-xs-12 mb10 jkl" style=" border: solid 1px #ddd;border-radius: 4px;padding: 8px;">
											<div class="">
												<div class="">
													<div>													
														<label class="btn btn-xs  btn-primary sel_promo " style="float:right;cursor:pointer;"  
															   autocomplete="off"  name="Booking_promosAutoApply" 
															   activate="<?= $val['prm_activate_on'] ?>" 
															   id="<?= $val['prm_id'] ?>"
															   value="<?= $val['prm_code'] ?>" onclick="prmObj.applyPromo(1, '<?= $val['prm_code'] ?>')">Apply	
														</label>
														<label class="btn btn-xs hide sel_promo_app"  id="appl_<?= $val['prm_id'] ?>" style="float:right;cursor: auto;font-weight: bold;color: #F0FFFF;font-size: 16px;font-style: italic;">Applied</label>
													</div>   
													<p class="heading-part text-body"><strong><?= $val['prm_code'] ?></strong></p>
													<?= $val['prm_desc']; ?>	
													   <!--<input type="hidden" id="disval_<?= $val['prm_id'] ?>" value="<?= $val['prm_code'] ?>">-->
													<input type="hidden" id="disval_<?= $val['prm_id'] ?>" value="<?= $val['prm_code'] ?>">

												</div>
											</div>
										</div>

										<?
									}
								}
								?>
								<input type="hidden" class="all_promo_codes" id="all_promo_codes" value='<?php echo json_encode($arr_promo); ?>' />
								<input type="hidden" id="isPromoApplied1" value="1" />
								<input type="hidden" id="coinPromoStatus1" value="0" />

							</div>
						</div>
					</div>

					<div id="spanPromoCreditSucc" class="col-xs-12 text-center mt5 spanPromoCreditSucc hide alert alert-success" style="font-weight: bold;"></div>

					<div class="col-12 text-center hide promoAppliedDiv" id="promoAppliedDiv">	
						<div class="	">
							Applied discount code : <b><span id="txtpromo" class="text-uppercase txtpromo"><?= $model->bkgInvoice->bkg_promo1_code ?> </span></b>
						</div>
						<button class="btn btn-danger btn-xs" onclick="prmObj.applyPromo(2)">Remove Code</button>
					</div>

					<div class="col-12 text-center mt10 hide creditRemove" id="creditRemove" style="font-weight: bold;">
						<div class="alert alert-success">Gozo coins worth <b>&#x20B9;<span  class="text-uppercase creditUsed txtGozoCoinsUsed"><?= $model->bkgInvoice->bkg_credits_used ?> </span> used successfully.</b></div>						
						<button class="btn btn-danger btn-xs" id="removeGozoCoin" onclick="prmObj.applyPromo(4);">Remove Gozo Coins</button>
					</div>



				</div>
			</div>
		</div>

	<?php }
	?>
</div>
<script>


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


</script>

