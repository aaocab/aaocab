<?php
/** @var BookFormRequest $objPage */
$objPage	 = $this->pageRequest;
/** @var Stub\common\Booking $objBooking */
$objBooking	 = $objPage->booking;

/** @var CActiveForm $form */
$form = $this->beginWidget('CActiveForm', array(
	'id'					 => 'cabAddonsForm',
	'enableClientValidation' => false,
	'clientOptions'			 => [
		'validateOnSubmit'	 => false,
		'errorCssClass'		 => 'has-error',
	],
	'enableAjaxValidation'	 => false,
	'errorMessageCssClass'	 => 'help-block',
	'action'				 => Yii::app()->createUrl('booking/addons'),
	'htmlOptions'			 => array(
		"onsubmit"		 => "return checkAddons();",
		'class'			 => 'form-horizontal',
		'autocomplete'	 => 'off',
	),
		));

$cabArray		 = array_column(array_column($objPage->quote->cabRate, 'cab'), 'id');
$key			 = array_search($objBooking->cab->cabCategory->id, $cabArray);
$objFare		 = $objPage->quote->cabRate[$key]->fare;


$fcity = $objBooking->routes[0]->source->code;
$cntRoutes = count($objBooking->routes);
$tcity = ($cntRoutes > 1)?$objBooking->routes[$cntRoutes-1]->destination->code:$objBooking->routes[0]->destination->code;
$baseAmount = $objPage->quote->cabRate[$key]->fare->baseFare;

$defCanRuleId = CancellationPolicy::getCancelRuleId(null, $objBooking->cabType, $fcity,$tcity,$objBooking->tripType);
$addonCPdata = AddonCancellationPolicy::getByCtyVehicleType($fcity, $tcity, $objBooking->cabType, $objBooking->tripType, $defCanRuleId);
$objBooking->addons= new \Stub\common\Addons();
$addonsObj = $objBooking->addons->getList($addonCPdata, $defCanRuleId, $baseAmount,1,$objBooking->getPickupDate());

$addonCMdata = AddonCabModels::getByCtyVehicleType($fcity, $tcity, $objBooking->cabType, $objBooking->tripType);
$addonsCmObj = $objBooking->addons->getList($addonCMdata, $defCanRuleId, $baseAmount, 2);
?>

<div class="row mt-3">
	<div class="col-12 col-lg-8 col-xl-8">
		<div class="row">
		    <div class="col-12">
			<?php 
				if(count($addonsObj) > 1)
				{
					$cPtext = "Cancellation policy upgrade charges are convenient way to increase flexibility, these charges are non-refundable. Any advance paid in excess of cancellation or no-show charges due is always refunded.";
			?>
		        <div class="row">
					<div class="col-12"><p class="font-18 weight500 mb5">Choose a cancellation policy <span data-toggle="tooltip" data-placement="top" title="<?= $cPtext ?>"><img src="/images/bx-info-circle.svg" alt="img" width="16" height="16"></span></p>
						<p>We understand that plans change sometimes! That's why we want you to have the flexibility you need for cancelling your booking.</p>
					    <p><img src="/images/heartsv3.png" width="18"> We have selected a default cancellation policy for you. You may choose the policy that works best for you below.</p>
						<p>Book worry-free. You can cancel any booking for free within the first 30 minutes of payment</p>
					</div>
                </div>
				<div class="card mb-2">
					<div class="card-body mb20 p10">
						<div class="row addons m0">
							<?php
							$cpAddonsArray	 = [];
							foreach ($addonsObj as $key => $cPvalue)
							{	
								array_push($cpAddonsArray, [$cPvalue->id,$cPvalue->charge]);
								$cpDesc	 = explode(',', $cPvalue->desc);
								$margin	 = (preg_match('/-/', $cPvalue->charge)) ? str_replace('-', '', $cPvalue->charge) : $cPvalue->charge;
								?>
								<div class="col-12 p0">
									<div class="radio-style7">
										<div class="radio">
											<input id="cabaddons<?= $cPvalue->id ?>" value="<?= $cPvalue->id ?>" type="radio" name="cabaddons" class="bkg_user_trip_type applyaddon" <?php echo ($cPvalue->default == 1) ? " checked " : ""; ?>>
											<label for="cabaddons<?= $cPvalue->id ?>">
												<b><span class="addonslabel<?= $cPvalue->id ?>"><?php echo $cPvalue->label; ?></span> <span class="txtincludecp<?= $cPvalue->id ?> displytxt color-blue" style="float:right;"><?= ($cPvalue->id == 0) ? 'Included in price' : ''; ?></span> <span class="addonsmargincp<?= $cPvalue->id ?> <?= ($cPvalue->id == 0) ? 'hide' : ''; ?>" style="float:right;"><span class="marginsymbol<?= $cPvalue->id ?>"><?php echo (preg_match('/-/', $cPvalue->charge)) ? '-' : '+'; ?></span> &#x20B9;<span class="addonsmargin<?= $cPvalue->id ?>"><?php echo $margin; ?></span></span></b>
                                                <input type="hidden" name="addonlebl<?= $cPvalue->id ?>" id="addonlebl<?= $cPvalue->id ?>" value="<?= $cPvalue->label ?>">								
                                                <input type="hidden" name="addonmargin<?= $cPvalue->id ?>" id="addonmargin<?= $cPvalue->id ?>" value="<?php echo $margin ?>">
												<input type="hidden" name="addonsymbol<?= $cPvalue->id ?>" id="addonsymbol<?= $cPvalue->id ?>" value="<?php echo (preg_match('/-/', $cPvalue->charge)) ? '-' : '+'; ?>">
												<ul class="pl15 mb0 pt5 font-12">
													<?php
													foreach ($cpDesc as $key => $cpVal)
													{

														?>
														<li><?php echo $cpVal; ?></li>
													<?php } ?>
												</ul>
											</label>
										</div>
									</div>
								</div>
							<?php } ?>

						</div>
					</div>

				</div>
				<?php
				}
				if (count($addonsCmObj) > 1)
				{
					?>
					<div class="row">
						<div class="col-12 mt-2"><p class="font-18 weight500 mb5">Choose your preferred cab model</p>
							<p>We understand some of our clients prefer to travel in a specific model of cab. For an additional fee, you may pick a model of your choice.</p>
							<p><img src="/images/heartsv3.png" width="18"> <b>If for any reason, we are unable to provide a cab of your choice, we will credit you back the amount you paid for your preferred model of cab.</b></p>
						</div>
					</div>
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
												<input id="cabmodeladdon<?= $cMvalue->id ?>" value="<?= $cMvalue->id ?>" type="radio" name="cabmodeladdon" class="bkg_user_trip_type applyaddon" <?php echo ($cMvalue->default == 1) ? " checked " : ""; ?>>
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
				<?php } ?>
			</div>
		</div>
	</div>
	<div class="col-12 col-lg-4 col-xl-4 d-none d-lg-block mt-2">
		<div class="card">
			<div class="card-header weight600 font-18">Fare Summary <img src="/images/bx-notepad.svg" alt="img" width="20" height="20" class="mr10"></div>
			<div class="addoncardmodal">
				<div class="card-body pb0">
					<div class="d-flex justify-content-between mb-1 lineheight14">
						<span>Distance quoted of the trip:<br><i class="font-10">(based on pickup and drop addresses provided)</i></span>
						<span class="text-right"><b><?php echo $objPage->quote->quotedDistance; ?></b> km<br><i class="font-10">(Charges after <?php echo $objPage->quote->quotedDistance; ?> Km @ ₹<?php echo round($objFare->extraPerKmRate, 2); ?>/km)</i></span>
					</div>
					<div class="d-flex justify-content-between mb-1">
						<span>Total days for the trip:</span>
						<span></span>
					</div>
					<div class="d-flex justify-content-between mb-1">
						<span>Base fare:</span>
						<span><b class="txtBaseFare"><?php echo Filter::moneyFormatter($objFare->baseFare); ?></b></span>
					</div>
					<div class="d-flex justify-content-between mb-1">
						<span>Discount Applied:</span>
						<span><b class="txtBaseFare"><?php echo Filter::moneyFormatter($objFare->discount); ?></b></span>
					</div>
					<div class="d-flex justify-content-between mb-1 aplyaddons hide">
						<span class="aplyaddonslabel"></span>
						<span><b class="aplyaddonsmargin"></b></span>
					</div>
					<div class="d-flex justify-content-between mb-1">
						<span>State tax:</span>
						<span><b class="txtStateTax"><?php echo Filter::moneyFormatter($objFare->stateTax); ?></b></span>
					</div>
					<div class="d-flex justify-content-between mb-1">
						<span>Toll tax:</span>
						<span><b class="txtTollTax"><?php echo Filter::moneyFormatter($objFare->tollTax); ?></b></span>
					</div>
					<div class="d-flex justify-content-between mb-1">
						<span>IGST (@5%):</span>
						<span><b class="txtGst"><?php echo Filter::moneyFormatter($objFare->gst); ?></b></span>
					</div>
					<div class="d-flex justify-content-between mb-1 <?= ($objFare->driverAllowance > 0) ? '' : 'hide'; ?>">
						<span>Driver allowance:</span>
						<span><b><?php echo Filter::moneyFormatter($objFare->driverAllowance); ?></b></span>
					</div>
				</div>
				<div class="card-footer p0">
					<div class="d-flex justify-content-between mb-1 font-20 p10 pl20 pr20 bg-blue4">
						<span><b>Total fare</b></span>
						<span><b class="txtTotalAmount"><?php echo Filter::moneyFormatter($objFare->totalAmount); ?></b></span>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-12 cc-1">
		<div class="row m0 cc-2 pb20">
			<div class="col-4 d-lg-none">
				<p class="mb0 text-uppercase lineheight14">Total Fare</p>
				<p class="mb0"><a href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="" class="addon-fair-breakup-modal link-section" data-original-title=""><span class="font-24 weight600 etcAmount txtEstimatedAmount"><?php echo Filter::moneyFormatter($objFare->totalAmount); ?></span><img src="/images/bx-info-circle.svg" alt="img" width="18" height="18"></a></p>
			</div>
			<div class="col-8 col-lg-12 col-xl-12 text-center">
				<input type="hidden" name="addoncharge" id="addoncharge" value="">
				<input type="hidden" name="rdata" value="<?= $this->pageRequest->getEncrptedData() ?>">
				<input type="hidden" name="pageID" id="pageID" value="12">
				<input type="button" value="Go back" rid="<?= $rid ?>"  step="<?= $pageid ?>" name="yt0" class="btn btn-light backButton">
				<input type="submit" value="Next" name="yt0" id="addonbtn" class="btn btn-primary pl30 pr30 addonclassbtn">
				<input type="hidden" name="step" value="<?= $step ?>">
				<input type="hidden" name="addonidcabmodel" id="addonidcabmodel" value="">
				<input type="hidden" class="clsaddonparams" name="addonparams" value=>
			</div>
		</div>
	</div>
</div>
<?php
//$taxrate = Filter::getServiceTaxRate();
$taxrate				 = BookingInvoice::getGstTaxRate($objBooking->agentId, $objBooking->tripType);
$this->endWidget();
?>
<div class="modal fade" id="bkAddonFareDetailsModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header p10 pb5 pl20">
				<h5 class="modal-title">Fare Summary</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<img src="/images/bx-x.svg" alt="img" width="18" height="18">
				</button>
			</div>
			<div class="modal-body p5" id="bkAddonFareDetailsModelBody">
				<div class="col-12">
					<div class="row"></div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function ()
	{
		step = <?= $step ?>;
		tabURL = "<?= $this->getURL($objPage->getAddonsURL()) ?>";
		pageTitle = "";
		tabHead = "<?= $this->pageRequest->getCabServiceClassDesc() ?>";
		toggleStep(step, 9, tabURL, pageTitle, tabHead, true, <?= $this->pageRequest->step ?>);
		showBack();
		
		$('[data-toggle="tooltip"]').tooltip();
	});

	function checkAddons()
	{
		//debugger;	
		var form = $("form#cabAddonsForm");
		$.ajax({
			"type": "POST",
			"dataType": "html",
			"url": "<?= CHtml::normalizeUrl($this->getURL('booking/addons')) ?>",
			"data": form.serialize(),
			"beforeSend": function ()
			{
				blockForm(form);
			},
			"complete": function ()
			{
				unBlockForm(form);
			},
			"success": function (data2)
			{	//debugger;
				var data = "";
				var isJSON = false;
				try
				{
					data = JSON.parse(data2);
					isJSON = true;
				} catch (e)
				{

				}
				if (!isJSON)
				{
					$("#tab13").html(data2);
				} else
				{
					if (data.success)
					{
						window.sessionStorage.setItem('rdata', data.data.rdata);
						location.href = data.data.url;
						return;
					}

					var errors = data.errors;
					msg = JSON.stringify(errors);
					settings = form.data('settings');
					$.each(settings.attributes, function (i)
					{
						$.fn.yiiactiveform.updateInput(settings.attributes[i], errors, form);
					});
					$.fn.yiiactiveform.updateSummary(form, errors);
					messages = errors;
					content = '';
					var summaryAttributes = [];
					for (var i in settings.attributes)
					{
						if (settings.attributes[i].summary)
						{
							summaryAttributes.push(settings.attributes[i].id);
						}
					}
					displayFormError(form, messages);
				}
			},
			error: function (xhr, ajaxOptions, thrownError)
			{
				if (xhr.status == "403")
				{
					handleException(xhr, function () {
						checkAddons();
					});
				}

			}
		});
		return false;
	}

	$('.addon-fair-breakup-modal').click(function () {
		var data = $('.addoncardmodal').html();
		$('#bkAddonFareDetailsModel').removeClass('fade');
		$('#bkAddonFareDetailsModel').css('display', 'block');
		$('#bkAddonFareDetailsModelBody').html(data);
		$('#bkAddonFareDetailsModel').modal('show');
	});

	var addonsParams = {};
	$(".applyaddon").click(function () {//debugger;
		let label = '';
		var cPval = $('input[name=cabaddons]:checked').val();
		var cMval = $('input[name=cabmodeladdon]:checked').val();
		cPval = (typeof (cPval) != 'undefined') ? cPval : 0;
		cMval = (typeof (cMval) != 'undefined') ? cMval : 0;
		//var cPmargin = $('.addonsmargin' + cPval).text();
		var cPmargin = $('#addonmargin' + cPval).val();
		var cPlabel = $('#addonlebl' + cPval).val();
		if(typeof (cPmargin) != 'undefined')
		{
			cPmargin = (cPmargin != '') ? cPmargin : 0;
		}
		else
		{
			cPmargin = 0;
		}
		//var cMmargin = $('.addoncMmargin' + cMval).text();
		var cMmargin = $('#addoncmmargins' + cMval).val();
		if(typeof (cMmargin) != 'undefined')
		{
			cMmargin = (cMmargin != '') ? cMmargin : 0;
		}
		else
		{
			cMmargin = 0;
		}
		var cMlabel = $('.addoncMlabel' + cMval).text();
		var baseFare = '<?= $objFare->baseFare ?>';
		var discount = '<?= $objFare->discount ?>';
		var stateTax = '<?= $objFare->stateTax ?>';
		var tollTax = '<?= $objFare->tollTax ?>';
		var driverAllowance = '<?= $objFare->driverAllowance ?>';
		var parkingCharge = '<?= $objFare->parkingCharge ?>';
		parkingCharge = (parkingCharge != '') ? parkingCharge : 0;
		var airportEntryCharge = '<?= $objFare->airportEntryFee ?>';
		var sevicetaxRate = '<?= $taxrate ?>';
		//var cMmarginsymbol = $('.cMmarginymbol' + cMval).text();
		var cMmarginsymbol = $('#addoncmsymbol' + cMval).val();
		cMmarginsymbol = (typeof(cMmarginsymbol) != 'undefined')?cMmarginsymbol.replace(/\+/g, ""):"";
		//var marginsymbol = $('.marginsymbol' + cPval).text();
		var marginsymbol = $('#addonsymbol' + cPval).val();
		marginsymbol = (typeof(marginsymbol) != 'undefined')?marginsymbol.replace(/\+/g, ""):"";
		//$('#addoncharge').val(parseInt(cMmarginsymbol + cMmargin));
		$('#addonidcabmodel').val(cMval);
		if (cPval > 0)
		{
			addonsParams.type1 = '{"id":"' + cPval + '","charge":' + parseInt(marginsymbol + cPmargin) + ',"type":1}';
			label += '<br><i class="font-12">' + cPlabel + ': ' + marginsymbol + ' &#x20B9;' + cPmargin + '</i>';
		}
		if (cMval > 0)
		{
			addonsParams.type2 = '{"id":"' + cMval + '","charge":' + parseInt(cMmarginsymbol + cMmargin) + ',"type":2}';
			label += '<br><i class="font-12">' + cMlabel + ': ' + cMmarginsymbol + ' &#x20B9;' + cMmargin + '</i>';
		}
		$(".clsaddonparams").val(JSON.stringify(addonsParams));
		$('.aplyaddons').removeClass('hide');
		margin = parseInt(marginsymbol + cPmargin) + parseInt(cMmarginsymbol + cMmargin);
		var grossAmount = parseInt(baseFare) + parseInt(stateTax) + parseInt(tollTax) + parseInt(driverAllowance) + parseInt(parkingCharge) + parseInt(airportEntryCharge) + parseInt(margin) - parseInt(discount);
		var serviceTax = Math.round(grossAmount * parseInt(sevicetaxRate) * 0.01);
		var totalAmnt = parseInt(grossAmount) + parseInt(serviceTax);
		$('.aplyaddonslabel').html('<b>Addon charge</b>' + label);
		$('.aplyaddonsmargin').html(marginsymbol + cMmarginsymbol + ' &#x20B9;' + Math.abs(margin));
		$('.txtGst').html('&#x20B9;' + serviceTax);
		$('.txtTotalAmount').html('&#x20B9;' + totalAmnt);
		$('.txtEstimatedAmount').html('&#x20B9;' + totalAmnt);
		$('.displytxt').html('').next().removeClass('hide');
		$('.txtincludecp' + cPval).text('Included in price');
		$('.addonsmargincp' + cPval).addClass('hide');
		$('.displytxtcm').html('').next().removeClass('hide');
		$('.txtincludecm' + cMval).text('Included in price');
		$('.addonsmargincm' + cMval).addClass('hide');

		var cpAddonsArray = <?php echo json_encode($cpAddonsArray); ?>; 
		//[[10,-439],[11,-367],[12,-228],[0,0]]; superflexi
		// [[0,0],[1,67],[2,207],[3,427]]; non-refundable
		//[[4,-65],[0,0],[5,135],[6,348]]; standard
	   var selectedKey  = 0;
	   var defaultKey  = 0;
	   var selectedcost = 0;
		$.each(cpAddonsArray, function (key, val) {
			if(val[0] == cPval){
				selectedKey = key;
				selectedcost = val[1];
			}
			if(val[0] == 0){
				defaultKey = key;
			}
		});
		$.each(cpAddonsArray, function (key, val) {
			symbol = "+";
			if(key < selectedKey){
				symbol = "-";
			}
			let currentcost = Math.abs(val[1]);
		//	let selectedcost = cpAddonsArray[selectedKey];
			var newcost = parseInt(currentcost) - parseInt(selectedcost);
			
			if(key < defaultKey){
				newcost = parseInt(currentcost) + parseInt(selectedcost);
			}
			if(key > defaultKey && selectedcost > 0){
				newcost = parseInt(currentcost) - parseInt(Math.abs(selectedcost));
			}
			if(key > defaultKey && selectedcost < 0){
				newcost = parseInt(currentcost) + parseInt(Math.abs(selectedcost));
			}
			

			$('.addonsmargin' + val[0]).html(Math.abs(newcost));
			$('.marginsymbol' + val[0]).html(symbol);
		});

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
	});



</script>