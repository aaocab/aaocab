<div class="content mb0 p0">
		<div class="panel-body p0">
			<div class="text-danger text-center errSelectModel hide">Please select at least one car model of your choice.</div>
			<div class="checkbox-style-new pb10">
                <label class="text-center"><b>Select model of your choice</b></label>
				<?php
                //$carModelsSelectTier = ServiceClassRule::model()->getCabByClassId($sccId, $baseAmt, $catId);
                $carModelsSelectTier = CJSON::decode($carModelsSelectTier);
				foreach ($carModelsSelectTier as $key => $value){
					$scvData = SvcClassVhcCat::getByVhtAndTier($value['id'],$sccId);
					$scvIdVht = $scvData['scv_id'];
					$scvIdVhtParent = $scvData['scv_parent_id'];
				?>
 <input type="hidden" name="baseAmount" id="baseAmount<?php echo $value['id'];?>" value="<?=$quotes[$scvIdVht]->routeRates->baseAmount?>">
 <input type="hidden" name="discAmount" id="discAmount<?php echo $value['id'];?>" value="<?=$quotes[$scvIdVht]->routeRates->discount?>">
 <input type="radio"  name="service_class_area"  value="<?php echo $value['id'];?>"/> <?php echo $value['text'];?><span class="pull-right">&#x20B9;<?=$quotes[$scvIdVht]->routeRates->baseAmount?></span><br />
                <?php }?>
			</div>
		</div>
</div>
<script>
    $('input:radio[name="service_class_area"]').click(function(){
		//var  srvclassmodel = $('input:radio[name="service_class_area"]:checked').val();
		var srvclassmodel = $(this).val();
		var scvid = <?php echo $scvId;?>;
		$("#<?= CHtml::activeId($model, "bkg_vht_id") ?>").val(srvclassmodel);
		document.querySelector('input[name="qouteRadio"][value="' + scvid + '"]').checked = true;
		var baseFare = $('#baseAmount'+srvclassmodel).val();
		var discount = $('#discAmount'+srvclassmodel).val();
		var discBaseFare = baseFare - discount;
		calculateFareBreakUp(baseFare,discBaseFare, scvid);
		setTimeout(function () {
			bookNow.showSuccessMsg("Model selected.Prices may slightly changed.");
		}, 1000);
	});
	

	function calculateFareBreakUp(baseFare, discountedBaseFare, scvid) { //debugger;
		var toll = ($('.clsTollAmt' + scvid).html() == '' || $('.clsTollAmt' + scvid).html() == undefined) ? 0 : parseInt($('.clsTollAmt' + scvid).html());
		var state = ($('.clsStateAmt' + scvid).html() == '' || $('.clsStateAmt' + scvid).html() == undefined) ? 0 : parseInt($('.clsStateAmt' + scvid).html());
		var airportAmt = ($('.clsAirportAmt' + scvid).html() == '' || $('.clsAirportAmt' + scvid).html() == undefined) ? 0 : parseInt($('.clsAirportAmt' + scvid).html());
		var da = ($('.clsDAAmt' + scvid).html() == '' || $('.clsDAAmt' + scvid).html() == undefined) ? 0 : parseInt($('.clsDAAmt' + scvid).html());
		var gstRate = ($('.clsGstRate' + scvid).html() == '' || $('.clsGstRate' + scvid).html() == undefined) ? 0 : parseInt($('.clsGstRate' + scvid).html());
		var discount = baseFare - discountedBaseFare;
		var grossBaseFare = baseFare - discount + toll + state + da + airportAmt;
		var GST = Math.round((grossBaseFare * parseFloat(gstRate) / 100));
		var totalAmt = grossBaseFare + GST;
		$('.clsBaseAmt' + scvid).html(baseFare);
		$('.clsBaseAmtDisc' + scvid).html(discountedBaseFare);
		$('.clsBaseFare').html('<b>₹'+discountedBaseFare+'</b>');
		$('.clsDiscount'+ scvid).html('<b>₹'+discount+'</b>');
		$('.clsGstAmt' + scvid).html(GST);
		$('.clsTotalAmt' + scvid).html(totalAmt);
	}
</script>




