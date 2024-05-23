<?php
$disabled = "";
if (in_array($model->bkg_status, [1, 9, 10]))
{

	$disabled = "disabled";
}
$addonStub	 = new \Stub\common\Addons();
$arrCPAddons = $addonStub->getList($addons, $defCanRuleId, $model->bkgInvoice->bkg_base_amount, 1, $model->bkg_pickup_date);
$form		 = $this->beginWidget('CActiveForm', array(
	'id'					 => 'addonsreview', 'enableClientValidation' => true,
	'clientOptions'			 => array(
		'validateOnSubmit'	 => true,
		'errorCssClass'		 => 'has-error',
		'afterValidate'		 => ''
	),
	'errorMessageCssClass'	 => 'help-block',
	'htmlOptions'			 => array(
		'class'		 => '', 'enctype'	 => 'multipart/form-data'
	),
		));
/* @var $form CActiveForm */
if ($model->bkgInvoice->bkg_addon_details != '')
{
	$addonsArray		 = json_decode($model->bkgInvoice->bkg_addon_details, true);
	$addonids			 = array_column($arrCPAddons, 'id');
	$addonkey			 = array_search(1, array_column($addonsArray, 'adn_type'));
	$key				 = array_search($addonsArray[$addonkey]['adn_id'], $addonids);
	$selectedAddonLabel	 = $arrCPAddons[$key]->label;
}
?>
<div class="card-body">
	<div>
        <div class="row flex-main">
		<?php
			if (count($arrCPAddons) > 0 && $model->bkgPref->bkg_cancel_rule_id != CancellationPolicyDetails::NON_CANCELLABLE)
			{
				?>

				<?php
				$cpAddonsArray = [];
				foreach ($arrCPAddons as $key => $cPvalue)
				{
					array_push($cpAddonsArray, [$cPvalue->id, $cPvalue->charge]);
					$cpDesc	 = explode(',', $cPvalue->desc);
					$margin	 = (preg_match('/-/', $cPvalue->charge)) ? str_replace('-', '', $cPvalue->charge) : $cPvalue->charge;
					?>

					<div class="col-12 p0">
						<div class="radio-style7">
							<div class="radio">
								<input id="cabaddons<?= $cPvalue->id ?>" value="<?= $cPvalue->id ?>" type="radio" name="cabaddons" class="bkg_user_trip_type" <?php echo ($cPvalue->default == 1) ? " checked " : ""; ?> <?= $disabled ?>>
								<label for="cabaddons<?= $cPvalue->id ?>">
									<b><span class="addonslabel<?= $cPvalue->id ?>"><?php echo $cPvalue->label; ?></span> <span class="txtincludecp<?= $cPvalue->id ?> displytxt color-blue" style="float:right;"><?= ($cPvalue->id == 0) ? 'Included in price' : ''; ?></span><span class="addonsmargincp<?= $cPvalue->id ?> <?= ($cPvalue->id == 0) ? 'hide' : ''; ?>" style="float:right;"><span class="marginsymbol<?= $cPvalue->id ?>"><?php echo (preg_match('/-/', $cPvalue->charge)) ? '-' : '+'; ?></span> &#x20B9;<span class="addonsmargin<?= $cPvalue->id ?>"><?php echo $margin; ?></span></span></b>
									<input type="hidden" name="addonmargin<?= $cPvalue->id ?>" id="addonmargin<?= $cPvalue->id ?>" value="<?php echo $margin ?>">
									<input type="hidden" name="addonsymbol<?= $cPvalue->id ?>" id="addonsymbol<?= $cPvalue->id ?>" value="<?php echo (preg_match('/-/', $cPvalue->charge)) ? '-' : '+'; ?>">
									<ul class="pl30 ml20 mb0 pt5 font-12">
										<?php
										foreach ($cpDesc as $key => $cpVal)
										{
											if ($key == 0)
												continue;
											?>
											<li><?php echo $cpVal; ?></li>
										<?php } ?>
									</ul>
								</label>
							</div>
						</div>
					</div>
					<?php
				}
			}
			else if ($model->bkgPref->bkg_cancel_rule_id == CancellationPolicyDetails::NON_CANCELLABLE)
			{
				//$cnpRuleDetails = CancellationPolicyDetails::model()->findByPk($model->bkgPref->bkg_cancel_rule_id);
				echo "<div class='col-12'>Non-Cancellable policy applied to this booking. This policy is unchangeable.</div>";
			}
			?>

		</div>
    </div>
</div>
<?php $this->endWidget(); ?>
<script>
	$(document).ready(function () {
		var type = '<?php echo $addonsArray[$addonkey]['adn_type']; ?>';
		if (type == 1)
		{
			var addonId = '<?php echo $addonsArray[$addonkey]['adn_id']; ?>';
			var addonLabel = $('.addonslabel' + addonId).text();
			var addOnCharge = '<?php echo $addonsArray[$addonkey]['adn_value']; ?>';
			let pattern = /-/;
			var addonCharge = (pattern.test(addOnCharge)) ? Math.abs(addOnCharge) : addOnCharge;
			var minusSymbol = (pattern.test(addOnCharge)) ? '(-)' : '';
			if (addonId)
			{
				$("#cabaddons" + addonId).prop("checked", true);
				$('.displytxt').html('').next().removeClass('hide');
				$('.txtincludecp' + addonId).text('Included in price');
				$('.addonsmargincp' + addonId).addClass('hide');
				$(".applydaddons").html("Applied " + addonLabel + ': ' + minusSymbol + '&#x20B9;' + addonCharge);
			} else {
				$("#cabaddons0").prop("checked", true)
			}
			$(".addoncard" + addonId).addClass('active');
			$(".applremove" + addonId).addClass('hide');
			$(".addons_app" + addonId).removeClass('hide');
		}
	});

	$('input[type=radio][name=cabaddons]').change(function () {
		var addonId = $('input[name=cabaddons]:checked').val();
		var bkgid = '<?php echo $model->bkg_id; ?>';
		var cpAddonsArray = <?php echo json_encode($cpAddonsArray); ?>;
		var cPmargin = $('#addonmargin' + addonId).val();


		var selectedKey = 0;
		var defaultKey = 0;
		var selectedcost = 0;
		$.each(cpAddonsArray, function (key, val) {
			if (val[0] == addonId) {
				selectedKey = key;
				selectedcost = val[1];
			}
			if (val[0] == 0) {
				defaultKey = key;
			}
		});

		$.each(cpAddonsArray, function (key, val) {

			symbol = "+";
			if (key < selectedKey) {
				symbol = "-";
			}
			let currentcost = Math.abs(val[1]);
			var newcost = parseInt(currentcost) - parseInt(selectedcost);

			if (key < defaultKey) {
				newcost = parseInt(currentcost) + parseInt(selectedcost);
			}
			if (key > defaultKey && selectedcost > 0) {
				newcost = parseInt(currentcost) - parseInt(Math.abs(selectedcost));
			}
			if (key > defaultKey && selectedcost < 0) {
				newcost = parseInt(currentcost) + parseInt(Math.abs(selectedcost));
			}


			$('.addonsmargin' + val[0]).html(Math.abs(newcost));
			$('.marginsymbol' + val[0]).html(symbol);
		});
		var addons = new Addon();
		addons.applyAddon(addonId, bkgid, '1');
	});
</script>
