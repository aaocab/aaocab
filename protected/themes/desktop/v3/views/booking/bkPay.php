<div class="container mb-2">
	<div class="row">
		<div class="col-12 text-center">
			<h2 class="merriw weight600">Order Summary </h2>
			<div class="badge badge-pill badge-primary mr-1 mb-1"><?php echo Filter::formatBookingId($model->bkg_booking_id); ?></div>
		</div>
		<div class="col-12 col-lg-10 offset-lg-1">
			<div class="row">
				<?php
				$this->renderPartial("bkTripInfo", ["model" => $model], false);
				?>
			</div>
			<div class="row accordion-widget">
				<div class="col-12 mt-1" id="accordion-icon-wrapper">
                    <div class="accordion collapse-icon accordion-icon-rotate" id="accordionWrapa2" data-toggle-hover="true">
                        <div class="card collapse-header">
                            <div id="heading5" class="card-header collapsed" data-toggle="collapse" data-target="#accordion5" aria-expanded="false" aria-controls="accordion5" role="tablist">
                                <span class="collapse-title">
                                    <span class="align-middle">No special services requested</span><br>
									<p class="font-12 mb0 text-muted"> 
	                                 <span class="sq_src <?php echo ($model->bkgAddInfo->bkg_spl_req_senior_citizen_trvl == 1)?"":"hide" ?>">Senior citizen traveling,</span> 
                                     <span class="sq_kid <?php echo ($model->bkgAddInfo->bkg_spl_req_kids_trvl == 1)?"":"hide" ?>">Kids on board,</span>
                                     <span class="sq_wot <?php echo ($model->bkgAddInfo->bkg_spl_req_woman_trvl == 1)?"":"hide" ?>">Women traveling,</span>
                                     <span class="sq_esd <?php echo ($model->bkgAddInfo->bkg_spl_req_driver_english_speaking == 1)?"":"hide" ?>">English-speaking driver required,</span> 
	                               </p>     
                                </span>
                            </div>
                            <div id="accordion5" role="tabpanel" data-parent="#accordionWrapa2" aria-labelledby="heading5" class="collapse" style="">
                               <?php
								$this->renderPartial("bkSpecialRequest", ["model" => $model], false);
								?> 
                            </div>
                        </div>
                        <div class="card collapse-header">
                            <div id="heading6" class="card-header collapsed" data-toggle="collapse" role="button" data-target="#accordion6" aria-expanded="false" aria-controls="accordion6">
                                <span class="collapse-title">
                                    <span class="align-middle">24 Hour cancellation policy</span>
                                </span>
                            </div>
                            <div id="accordion6" role="tabpanel" data-parent="#accordionWrapa2" aria-labelledby="heading6" class="collapse" aria-expanded="false" style="">
								<?php
								$this->renderPartial("bkCanPolicy", ["model" => $model], false);
								?>
                            </div>
                        </div>
                        <div class="card collapse-header">
                            <div id="heading7" class="card-header collapsed" data-toggle="collapse" role="button" data-target="#accordion7" aria-expanded="false" aria-controls="accordion7">
                                <span class="collapse-title">
                                    <span class="align-middle">Fare inclusions/exclusions</span>
                                </span>
                            </div>
                            <div id="accordion7" role="tabpanel" data-parent="#accordionWrapa2" aria-labelledby="heading7" class="collapse" aria-expanded="false" style="">
								<?php
								$this->renderPartial("bkCanInfo", ["model" => $model], false);
								?>
                            </div>
                        </div>
                        <div class="card collapse-header">
                            <div id="heading8" class="card-header" data-toggle="collapse" role="button" data-target="#accordion8" aria-expanded="false" aria-controls="accordion8">
                                <span class="collapse-title">
                                    <span class="align-middle">Coupons/discounts applied</span>
                                </span>
                            </div>
                            <div id="accordion8" role="tabpanel" data-parent="#accordionWrapa2" aria-labelledby="heading8" class="collapse" aria-expanded="false">
                                <?php
								$this->renderPartial("bkDiscounts", ["model" => $model], false);
								?>
                            </div>
                        </div>
                        <div class="card collapse-header">
                            <div id="heading9" class="card-header" data-toggle="collapse" role="button" data-target="#accordion9" aria-expanded="false" aria-controls="accordion9">
                                <span class="collapse-title">
                                    <span class="align-middle">Boarding checks</span>
                                </span>
                            </div>
                            <div id="accordion9" role="tabpanel" data-parent="#accordionWrapa2" aria-labelledby="heading9" class="collapse" aria-expanded="false">
                               <?php
								$this->renderPartial("bkBoardingCheck", ["model" => $model], false);
								?>
                            </div>
                        </div>
                        <div class="card collapse-header">
                            <div id="heading10" class="card-header" data-toggle="collapse" role="button" data-target="#accordion10" aria-expanded="false" aria-controls="accordion10">
                                <span class="collapse-title">
                                    <span class="align-middle">On trip do's &amp; don'ts</span>
                                </span>
                            </div>
                            <div id="accordion10" role="tabpanel" data-parent="#accordionWrapa2" aria-labelledby="heading10" class="collapse" aria-expanded="false">
                                <?php
								$this->renderPartial("bkDonts", ["model" => $model], false);
								?>
                            </div>
                        </div>
                        <div class="card collapse-header">
                            <div id="heading11" class="card-header" data-toggle="collapse" role="button" data-target="#accordion11" aria-expanded="false" aria-controls="accordion11">
                                <span class="collapse-title">
                                    <span class="align-middle">Travel advisories &amp; restrictions</span>
                                </span>
                            </div>
                            <div id="accordion11" role="tabpanel" data-parent="#accordionWrapa2" aria-labelledby="heading11" class="collapse" aria-expanded="false">
                                <?php
								$this->renderPartial("bkAdvisory", ["model" => $model], false);
								?>
                            </div>
                        </div>
                    </div>
                </div>
			</div>
			<div class="row mt-2 widget-pay">
				<div class="col-5">
					<p class="mb0 text-uppercase lineheight14">Total fare</p>
					<p class="mb0"><span class="font-20">&#x20B9;</span><span class="font-24 weight600 etcAmount txtEstimatedAmount"><?php echo $model->bkgInvoice->bkg_total_amount; ?></span></p>
				</div>
				<div class="col-7 text-right"><a href="javascript:void(0);" class="btn mb-1 btn-primary text-uppercase proceedpay-btn">Proceed to Pay</a></div>
			</div>
			<?php				
				$dataSet = Yii::app()->shortHash->hash($model->bkg_id);
			?>
			<form id="target_pay" action="/booking/pay1" method="post">
	            <input type="hidden" name="dataSet" value="<?php echo $dataSet;?>">
                <input type="hidden" name="YII_CSRF_TOKEN" value= "<?= Yii::app()->request->csrfToken ?>">  
	        </form>
		</div>
	</div>
</div>
<script>
$(document).on('click','.proceedpay-btn',function(){		
	$("#target_pay").submit();
});	
</script>