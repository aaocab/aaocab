<div class="content-boxed-widget p0 accordion-path">
	<div class="accordion accordion-style-0">
		<div class="accordion-border">
			<a href="javascript:void(0)" class="font18" data-accordion="accordion-11"><span class="uppercase">Add ons</span></a>
			<div class="accordion-text">
			<div class="text-right mt0"><a href="javascript:applyAddon(0);" o style="height: 25px; line-height: 20px; font-size: 14px; padding-right: 10px; color: #0d4da7;">Clear selection</a></div>
				<div class="content-padding p0">
                                    
						<?php 
							  foreach ($applicableAddons as $key => $value){
							  $jData = json_encode($routeRatesArr[$value['id']]->attributes);
						?>
								<div onclick="applyAddon('<?php echo $value['id'];?>')" class="mr10 addonswidget font-12 addOnDiv bottom-10 addOnClass<?php echo $value['id'];?> <?php if($model->bkgInvoice->bkg_addon_ids == $value['id']){?> active <?php }?>">
									<input type="hidden" id="jradata<?php echo $value['id'];?>" name="jradata<?php echo $value['id'];?>" value="<?php echo urlencode($jData); ?>">
									<div data-toggle="buttons" class="btn-widget-2 addon_<?php echo $value['id'];?>">
                                                                            <label class="btn-widget-addon line-height16 addOnLabel<?php echo $value['id'];?>" style="display: flex;">
										 <span class="adnlbltxt<?php echo $value['id'];?>"><?php echo $value['label'];?></span> <span class="font-22 float-right mt0" style="display: flex;">₹<b><?php echo $value['addOnCharge'];?></b></span>
										</label>
									</div>
								</div>
							
						<?php  }?>
				</div>   
			</div>
		</div>
	</div>
</div>

<script>
	 
	var booknow = new BookNow();
	var bkgId = '<?= $model->bkg_id ?>';
	function applyAddon(addOnId){
		var prmCode = huiObj.additionalParams.code;
		var gozocoins = huiObj.additionalParams.coins;
        var wallet = huiObj.additionalParams.wallet;
		var event = (addOnId == 0)?'8':'7';
		
        var content = {
						"bookingId": bkgId,
						"promo": {"code":prmCode},
						"gozoCoins": gozocoins,
						"wallet": wallet,
						"eventType": event};
		$.ajax({
			type: "POST",
			url: "<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/applyaddon')) ?>",
			data: {'addonId': addOnId, 'bkgId': bkgId,'content': content,'YII_CSRF_TOKEN': $('input[name="YII_CSRF_TOKEN"]').val()},
			success: function (data)
			{	
				data = JSON.parse(data);
				if (data.success)
                {
                    huiObj.bkgId = data.data.bookingId;
                    prmObj = new Promotion(huiObj);
                    prmObj.processData(data);
					$(".addOnClass"+addOnId).addClass('active');
					$(".addonswidget").not(".addOnClass"+addOnId).removeClass("active");
					$(".txtAddonLabel").html(data.data.addonLabel);
					addOnApplyStatus = (addOnId !=0)?booknow.showSuccessMsg("Addon applied."):booknow.showSuccessMsg("Addon removed.");
                } else
                {
                    huiObj.setErrors(data);
                }
			},
			error: function (error)
			{
				console.log(error);
			}
		});

	}	
	

</script>
