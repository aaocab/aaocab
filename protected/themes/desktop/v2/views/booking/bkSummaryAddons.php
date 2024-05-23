<div class="col-12 mb20 mt20">
    <div class="bg-white-box">
		<div id="showAddonDescApplied" class="col-xs-12 text-center mt5   showAddonDescApplied" style="font-size: 12px; font-weight:bold"></div>
        <div class="row">
            <div class="col-12 font-20 mb10 text-uppercase"><b>Add ons</b>
                        <div class="float-right" id="addonAppliedDiv">
                    <button class="btn btn-danger p5 font-12" onclick="applyAddon(0)">Clear Selection</button>
                </div>
            </div>
	<?php 
		foreach ($applicableAddons as $key => $value){
		$jData = json_encode($routeRatesArr[$value['id']]->attributes);
	?>
		<div onclick="applyAddon('<?php echo $value['id'];?>')" class="col-12 btn btn-3 pt5  addOnClass<?php echo $value['id'];?>">
				 <a data-toggle="buttons" class=" p10 text-left addoncls addon_<?php echo $value['id'];?>" addoncharge="" style="width: 100%;">
                            <label style="display: flex;" class="btn-widget-addon mb0 font-13 addOnLabe<?php echo $value['id'];?>">
						<?php echo $value['label'];?>
                                <span class="pull-right mt0 font-22 ml10" style="display: flex;">₹<b><?php echo $value['addOnCharge'];?></b></span>
				  </label>
			</a>
		</div>
		<?php  }?>
		<div id="spanAddonCreditSucc" class="col-10 offset-1 text-center mt5 spanAddonCreditSucc hide alert" style="font-weight: bold;"></div>
        </div>
    </div>
</div>


<script>
	var addons = '<?= $model->bkgInvoice->bkg_addon_ids ?>';
	$(document).ready(function ()
	{
		if(addons != '')
		{
			$('.addon_' + addons).addClass('active');
		}
	});
	
	function applyAddon(addOnId){
		$('.addoncls').removeClass("active");
		$('.addon_' + addOnId).addClass("active");
		var addons = new Addon();
		var bkgId = '<?= $model->bkg_id; ?>';
		addons.applyAddon(addOnId, bkgId);
	};
	
</script>