
<?php
$disabled ="";
if (in_array($model->bkg_status, [1,9,10]))
{
	
	$disabled	 = "disabled";
}

$addonsStub= new \Stub\common\Addons();
$addonsCmObj = $addonsStub->getList($addons, 0, $model->bkgInvoice->bkg_base_amount, 2);

if ($model->bkgInvoice->bkg_addon_details != '')
{
	$addonsArray		 = json_decode($model->bkgInvoice->bkg_addon_details, true);
	$addonids			 = array_column($addonsCmObj, 'id');
	$addonkey			 = array_search(2, array_column($addonsArray, 'adn_type'));
}
?>
<div class="card mb-2">
	<div class="card-body p10 font-16 mb20">
		<div class="row addons m0">
			<?php
			$cmAddonsArray = [];
			foreach ($addonsCmObj as $key => $cMvalue)
			{
				array_push($cmAddonsArray, $cMvalue->id);
				$cMmargin = (preg_match('/-/', $cMvalue->charge)) ? str_replace('-', '', $cMvalue->charge) : $cMvalue->charge;
				?>
				<div class="col-12 p0">
					<div class="radio-style7">
						<div class="radio">
							<input id="cabmodeladdon<?= $cMvalue->id ?>" value="<?= $cMvalue->id ?>" type="radio" name="cabmodeladdon" class="bkg_user_trip_type applyaddon" <?php echo ($cMvalue->default == 1) ? " checked " : ""; ?> <?=$disabled?>>
							<label for="cabmodeladdon<?= $cMvalue->id ?>">
								<b><span class="addoncMlabel<?= $cMvalue->id ?>"><?php echo str_replace('_', ' ', $cMvalue->label); ?></span> <span class="txtincludecm<?= $cMvalue->id ?> displytxtcm color-blue" style="float:right;"><?= ($cMvalue->id == 0) ? 'Included in price' : ''; ?></span><span class="addonsmargincm<?= $cMvalue->id ?> <?= ($cMvalue->id == 0) ? 'hide' : ''; ?>" style="float:right;"><span class="cMmarginymbol<?= $cMvalue->id ?>"><?php echo (preg_match('/-/', $cMvalue->charge)) ? '-' : '+'; ?></span> &#x20B9;<span class="addoncMmargin<?= $cMvalue->id ?>"><?php echo $cMmargin; ?></span></span></b>
							</label>
							<input type="hidden" name="addoncmmargins<?= $cMvalue->id ?>" id="addoncmmargins<?= $cMvalue->id ?>" value="<?php echo $cMmargin ?>">
							<input type="hidden" name="addoncmsymbol<?= $cMvalue->id ?>" id="addoncmsymbol<?= $cMvalue->id ?>" value="<?php echo (preg_match('/-/', $cMvalue->charge)) ? '-' : '+'; ?>">
						</div>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>

</div>
<script>
	$(document).ready(function () {
		var type = '<?php echo $addonsArray[$addonkey]['adn_type']; ?>';
		if (type == 2)
		{	//debugger;
			var addonId = '<?php echo $addonsArray[$addonkey]['adn_id']; ?>';
			var addonLabel = $('.addoncMlabel' + addonId).text();
			var addOnCharge = '<?php echo $addonsArray[$addonkey]['adn_value']; ?>';
			let pattern = /-/;
			var addonCharge = (pattern.test(addOnCharge)) ? Math.abs(addOnCharge) : addOnCharge;
			var minusSymbol = (pattern.test(addOnCharge)) ? '(-)' : '';
			if (addonId)
			{	
				//$("#cabmodeladdon" + addonId).click();
				$("#cabmodeladdon" + addonId).prop("checked", true);
				$('.displytxtcm').html('').next().removeClass('hide');
				$('.txtincludecm' + addonId).text('Included in price');
				$('.addonsmargincm' + addonId).addClass('hide');
				$(".applydcabmodeladdons").html("Applied " + addonLabel + ': ' + minusSymbol + '&#x20B9;' + addonCharge);
			} else {
				$("#cabmodeladdon0").prop("checked", true)
			}
			$(".addoncard" + addonId).addClass('active');
			$(".applremove" + addonId).addClass('hide');
			$(".addons_app" + addonId).removeClass('hide');
		}
	});
	

	$('input[type=radio][name=cabmodeladdon]').change(function () {
		//debugger;
		var bkgId = '<?php echo $model->bkg_id; ?>';
		var cMAddonId = $('input[name=cabmodeladdon]:checked').val();
		cMAddonId = (typeof (cMAddonId) != 'undefined') ? cMAddonId : 0;
		var cMmargin = $('#addoncmmargins' + cMAddonId).val();
		if(typeof (cMmargin) != 'undefined')
		{
			cMmargin = (cMmargin != '') ? cMmargin : 0;
		}
		else
		{
			cMmargin = 0;
		}
		$('.displytxtcm').html('').next().removeClass('hide');
		$('.txtincludecm' + cMAddonId).text('Included in price');
		$('.addonsmargincm' + cMAddonId).addClass('hide');
		var cmAddonsArray = <?php echo json_encode($cmAddonsArray); ?>;
			$.each(cmAddonsArray, function (key, val) { //debugger;
				cmmarginsymbl = $('#addoncmsymbol' + val).val();
				cmmargins = $('#addoncmmargins' + val).val();
				let pattern = /-/;
				cmmargins = (cmmarginsymbl == '-') ? '-' + cmmargins : cmmargins;
				calcmmargin =  parseInt(cmmargins) - parseInt(cMmargin);
				var addonCharge = (pattern.test(calcmmargin)) ? Math.abs(calcmmargin) : calcmmargin;
				var symbol = (pattern.test(calcmmargin)) ? '-' : '+';
				$('.addoncMmargin'+val).html(addonCharge);
				$('.cMmarginymbol'+val).html(symbol);
			});
		var addons = new Addon();
		addons.applyAddon(cMAddonId, bkgId, '2');
	});
</script>