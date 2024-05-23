<?php
$minPrice = $model->bkgInvoice->calculateMinPayment();
$maxPrice = (round($model->bkgInvoice->bkg_total_amount) - $model->bkgInvoice->getAdvanceReceived());
?>
<div class="container mb-2">
	<div class="row">
		<div class="col-12 text-center">
			<h2 class="gothic weight600">Payment Options</h2>
		</div>
		<div class="col-12 col-lg-6 offset-lg-3 mt-3">
			<div class="row">
				<div class="col-12 widget-liststyle mb-1">
					<div class="radio-style4">
						<div class="float-right">
							<span class="font-24">₹</span><span class="font-24 weight600"><?php echo $minPrice; ?></span>
						</div>
						<div class="radio">
							<input id="test12" value="1" type="radio" name="cabsegmentation" class="bkg_user_trip_type" data-val="<?php echo $minPrice; ?>" checked>
							<label for="test12">Part payment (30% advance)</label>
						</div>
					</div>
				</div>
				<div class="col-12 widget-liststyle">
					<div class="radio-style4">
						<div class="float-right">
							<span class="font-24">₹</span><span class="font-24 weight600"><?php echo $maxPrice; ?></span>
						</div>
						<div class="radio">
							<input id="test13" value="2" type="radio" name="cabsegmentation" class="bkg_user_trip_type" data-val="<?php echo $maxPrice; ?>">
							<label for="test13">Full payment (100% advance)</label>
						</div>
					</div>
				</div>
				<div class="col-12 mt-3">
					<h4 class="text-center weight500">I will pay with</h4>
				</div>
				<div class="col-12 col-xl-10 offset-xl-1">
					<div class="card border shadow-none mb-1 app-file-info">
						<div class="card-body p-1 text-center font-16 make-Payment" style="cursor:pointer">
							Credit/Debit Card | Net Banking | Wallet | UPI
						</div>
					</div>
				</div>
				<div class="col-12 col-xl-10 offset-xl-1">
					<div class="card border shadow-none mb-1 app-file-info">
						<div class="card-body p-1 text-center font-16">
							<img src="/images/paytm.png" alt="" width="120">
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>
<div class="card-body">
	<div class="">
<?php
$this->renderPartial("paywidget1", ["model" => $model,'walletBalance'=> $walletBalance], false);
?>
</div>
</div>
<script>
$(document).on('change','.bkg_user_trip_type',function(){
		let data1 = $(this).data("val");		
		$("#BookingInvoice_partialPayment").val(data1);
	});
$(document).on('click','.make-Payment',function(){		
		$("#payubtn").click();
	});	
</script>