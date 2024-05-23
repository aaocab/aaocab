<?php
/** @var CActiveForm $form */
$form = $this->beginWidget('CActiveForm', array(
	'id'					 => 'tripsuggestion',
	'enableClientValidation' => false,
	'clientOptions'			 => [
		'validateOnSubmit'	 => false,
		'errorCssClass'		 => 'has-error',
	],
	'enableAjaxValidation'	 => false,
	'errorMessageCssClass'	 => 'help-block',
	'action'				 => Yii::app()->createUrl('booking/suggestedtripselect'),
	'htmlOptions'			 => array(
		//"onsubmit"		 => "return suggestedtripselect();",
		'class'			 => 'form-horizontal',
		'autocomplete'	 => 'off',
	),
		));


$fcityName	 = Cities::getName($model->bkg_from_city_id);
$tcityName	 = Cities::getName($model->bkg_to_city_id);
$tripCity	 = ($model->bkg_from_city_id == $model->bkg_to_city_id) ? $fcityName : $fcityName . " to " . $tcityName;

if(!empty($cabQuote))
{
?>
<div class="col-12 col-lg-8 offset-lg-2 p0"><h2 class="font-18 weight500"><b>Other available options</b></h2></div>
<?php } ?>
<div class="row justify-center">
	<?php 
		if(!empty($cabQuote))
		{
	?>
	<div class="col-12 text-center mb10">
		If above trip type is not suitable for your needs, here are the additional option for your <?= $tripCity ?> trip
	</div>
	<?php
		}
	foreach ($cabQuote as $tripTypeKey => $value)
	{
		$bookingType = filter::bookingTypes($tripTypeKey, true);
		$tripType	 = explode("(", $bookingType)[0];
		$tripDesc	 = TncPoints::getSuggestTripContentkayak($tripTypeKey);

		foreach ($value->cabRate as $key => $cab)
		{
			$cabRate	 = $cab;
			//print'<pre>';print_r($cab);		
			$vctModel	 = VehicleCategory::model()->findByPk($cabRate->cab->cabCategory->scvVehicleId);

			$objFare = $cabRate->fare;
			if ($cabRate->discountedFare != null)
			{
				$objFare = $cabRate->discountedFare;
			}
			$discountedBaseFare = $objFare->baseFare - $objFare->discount;

			if ($cabRate->cab->cabCategory->scvVehicleServiceClass == 1)
			{
				$colorclass = "bg-orange color-white";
			}
			if ($cabRate->cab->cabCategory->scvVehicleServiceClass == 2)
			{
				$colorclass = "bg-blue color-white";
			}
			if ($cabRate->cab->cabCategory->scvVehicleServiceClass == 4)
			{
				$colorclass = "bg-blue5 color-white";
			}
			if ($cabRate->cab->cabCategory->scvVehicleServiceClass == 6)
			{
				$colorclass = "bg-green2 color-white";
			}
			$defLeadId = $deflid;//($value->defLeadId>0)?$value->defLeadId:0;
			$cardClick	 = 'onclick="suggestedtripselect(' . $tripTypeKey . ','.$defLeadId.');" style="cursor: pointer;"';
			$tripHr = ($tripTypeKey == 10)?'8 hr - ':(($tripTypeKey == 11)?'12 hr - ':''); 
			$staxrate	 = BookingInvoice::getGstTaxRate($pageRequest->booking->agentId, $pageRequest->booking->tripType);
			$catLabel = SvcClassVhcCat::getCatrgoryLabel($cabRate->cab->id);
			?>
			<div class="col-xl-3 col-md-4 col-sm-12 flex2 cb-none ct-1 ct-2" <?= $cardClick ?>>
				<div class="card text-center pt-1">
					<span class="text-center mt-2"> <img src="<?= "/" . $vctModel->vct_image ?>" width="150" class="img-fluid" alt="singleminded"></span>
					<div class="card-header text-center p10 pb0" style="display: inline-block;">
						<p class="text-center heading-line-2 mb0"><?=$catLabel . ' | ' . $tripType ?>  <span class="badge badge-pill <?= $colorclass ?> badge-new"><?= $cabRate->cab->cabCategory->catClass ?></span></p>
					</div>
					<div class="card-body p10 pt0">
						<div class="d-flex">
									<div class="flex-fill">
										<p>
											<span data-toggle="tooltip" data-placement="top" title="<?php echo $vctModel->vct_capacity . " passengers"; ?>"><img src="/images/bxs-group.svg" alt="img" width="14" height="14"><span class="font-16 weight600 pr5"><?= $vctModel->vct_capacity; ?></span></span>
											<span data-toggle="tooltip" data-placement="top" title="<?php echo $cabRate->cab->bagCapacity . " bags"; ?>"><img src="/images/bxs-shopping-bag.svg" alt="img" width="14" height="14"><span class="font-16 weight600 pr5"><?= $cabRate->cab->bagCapacity; ?></span></span>
											<img src="/images/bxs-tachometer.svg" alt="img" width="14" height="14"><span class="font-14 weight600"><?= $tripHr ?><?= $cabRate->distance; ?> km  </span>
										</p>
									</div>

								</div>
								<ul class="widget-todo-list-wrapper pl0 mt10" style="list-style-type: none;">
											<li class="widget-todo-item">
												<div class="widget-todo-title-wrapper d-flex justify-content-between align-items-center mb-50">
													<div class="widget-todo-title-area d-flex align-items-center">

														<span class="widget-todo-title ml-50 color-gray font-12">Base fare</span>
													</div>
													<div class="widget-todo-item-action d-flex align-items-center">
														<span class="font-14"><?php echo Filter::moneyFormatter($objFare->baseFare); ?></span>
													</div>
												</div>
											</li>
											<li class="widget-todo-item <?= ($objFare->driverAllowance > 0) ? '' : 'hide' ?>">
												<div class="widget-todo-title-wrapper d-flex justify-content-between align-items-center mb-50">
													<div class="widget-todo-title-area d-flex align-items-center">

														<span class="widget-todo-title ml-50 color-gray font-12">Driver Allowance</span>
													</div>
													<div class="widget-todo-item-action d-flex align-items-center">
														<span class="font-14"><?php echo Filter::moneyFormatter($objFare->driverAllowance); ?></span>
													</div>
												</div>
											</li>
											<li class="widget-todo-item <?= ($objFare->tollIncluded > 0) ? '' : 'hide' ?>">
												<div class="widget-todo-title-wrapper d-flex justify-content-between align-items-center mb-50">
													<div class="widget-todo-title-area d-flex align-items-center">

														<span class="widget-todo-title ml-50 color-gray font-12">Toll Tax (<?php echo ($objFare->tollIncluded > 0) ? "Included" : "Excluded"; ?>):</span>
													</div>
													<div class="widget-todo-item-action d-flex align-items-center">
														<span class="font-14"><?php echo Filter::moneyFormatter(($objFare->tollTax > 0) ? $objFare->tollTax : "0"); ?></span>
													</div>
												</div>
											</li>
											<li class="widget-todo-item <?= ($objFare->stateTax > 0) ? '' : 'hide' ?>">
												<div class="widget-todo-title-wrapper d-flex justify-content-between align-items-center mb-50">
													<div class="widget-todo-title-area d-flex align-items-center">

														<span class="widget-todo-title ml-50 color-gray font-12">State Tax (<?php echo ($objFare->stateTaxIncluded > 0) ? "Included" : "Excluded"; ?>):</span>
													</div>
													<div class="widget-todo-item-action d-flex align-items-center">
														<span class="font-14"><?php echo Filter::moneyFormatter(($objFare->stateTax > 0) ? $objFare->stateTax : "0"); ?></span>
													</div>
												</div>
											</li>
											<li class="widget-todo-item border-bottom">
												<div class="widget-todo-title-wrapper d-flex justify-content-between align-items-center mb-50">
													<div class="widget-todo-title-area d-flex align-items-center">

														<span class="widget-todo-title ml-50 color-gray font-12">GST (<?php echo $staxrate; ?>)%:</span>
													</div>
													<div class="widget-todo-item-action d-flex align-items-center">
														<span class="font-14"><?php echo Filter::moneyFormatter($objFare->gst); ?></span>
													</div>
												</div>
											</li>
											<li class="widget-todo-item">
												<div class="widget-todo-title-wrapper d-flex justify-content-between align-items-center mb-50">
													<div class="widget-todo-title-area d-flex align-items-center">

														<span class="widget-todo-title ml-50 color-gray font-12">Total</span>
													</div>
													<div class="widget-todo-item-action d-flex align-items-center">
														<span class="font-16 weight600"><?php echo Filter::moneyFormatter($objFare->totalAmount); ?></span>
<!--														<span data-toggle="tooltip" data-html="true" data-placement="top" title='<?= $details ?>'><img src="/images/bx-info-circle.svg" alt="img" width="14" height="14"></span>-->
													</div>
												</div>
											</li>
										</ul>
						

						<?php //$details	 = $this->renderPartial("fareBrkup", ['serviceTier' => $cabRate, 'partnerId' => $model->bkg_agent_id, 'tripType' => $tripTypeKey], true); ?>

						<p class="mb-0">
<!--							<span class="font-13 del-diagonal color-gray <?//= ($cabRate->fare->baseFare == ($objFare->baseFare - $objFare->discount)) ? "hide" : "" ?>"><?php //echo Filter::moneyFormatter($cabRate->fare->baseFare); ?></span>-->
<!--							<span class="font-16 weight600"><?php echo Filter::moneyFormatter($discountedBaseFare); ?></span>-->
<!--							<span data-toggle="tooltip" data-html="true" data-placement="top" title='<?= $details ?>'><img src="/images/bx-info-circle.svg" alt="img" width="14" height="14"></span>-->
						</p>
<!--						<p class="font-11 mb5 text-muted mb0 mr10 mt10">+<?php echo Filter::moneyFormatter($objFare->totalAmount - ($objFare->baseFare - $objFare->discount)) ?> in tolls, state tax, allowances, GST</p>-->
						<p class="font-13 mb5  mb0 mr10 mt10"><?= $tripDesc ?></p>
					</div>
				</div>
			</div>
			<div class="col-xl-3 col-md-4 col-sm-12 flex2 cs-none ct-1 ct-2" <?= $cardClick ?>>
				<div class="card mb-2">
					<div class="radio-style7">
						<div class="radio">
							<label>
								<div class="row m0">
									<div class="col-12 ct-2 p0">
										<p class="font-14 weight600 mb0 mt5"><?= $vctModel->vct_label ?><span class="mb0 text-center cabInfoTooltip"> | <?= $tripType ?></span> <span class="badge badge-pill badge-secondary p5 pl10 pr10" style="position: relative; top: -3px;"><img src="/images/bxs-tachometer2.svg" alt="img" width="12" height="12"> <span class="font-11 weight500"><?= $tripHr ?><?= $cabRate->distance; ?> km  </span></span></p>
									</div>
									<div class="col-6 p0">
												<span class="text-center"> <img src="/images/cabs/car-etios.jpg" width="100" class="img-fluid"></span>
												<div class="mt10 pl20">
													<p class="mb5">
														<span data-toggle="tooltip" data-placement="top" title="<?php echo $vctModel->vct_capacity . " passengers"; ?>"><img src="/images/bxs-group.svg" alt="img" width="18" height="18"><span class="font-14 weight600 pr5 align-middle"><?= $vctModel->vct_capacity ?></span></span>
														<span data-toggle="tooltip" data-placement="top" title="<?php echo $cabRate->cab->bagCapacity . " bags"; ?>"><img src="/images/bxs-shopping-bag.svg" alt="img" width="18" height="18"><span class="font-14 weight600 pr5"><?= $cabRate->cab->bagCapacity ?></span></span>
													</p>
					<!--												<p>Going from one city to another city. Direct transfer from pickup address to your drop-off address. You are not allowed for any unplanned pickups,drops or detours unless mentioned.</p>-->
												</div>
											</div>
									<div class="col-6 p0 text-right" style="margin-top: -30px;">
										<div class="badge badge-pill <?= $colorclass ?> weight500 mb5 pl10 pr10 font-10 mt5"><?= $cabRate->cab->cabCategory->catClass ?></div>
										<ul class="widget-todo-list-wrapper pl0 mt10" style="list-style-type: none;">
									<li class="widget-todo-item">
										<div class="widget-todo-title-wrapper d-flex justify-content-between align-items-center mb-50">
											<div class="widget-todo-title-area d-flex align-items-center">

												<span class="widget-todo-title ml-50 color-gray font-12">Base fare</span>
											</div>
											<div class="widget-todo-item-action d-flex align-items-center">
												<span class="font-14"><?php echo Filter::moneyFormatter($objFare->baseFare); ?></span>
											</div>
										</div>
									</li>
									<li class="widget-todo-item <?= ($objFare->driverAllowance > 0) ? '' : 'hide' ?>">
										<div class="widget-todo-title-wrapper d-flex justify-content-between align-items-center mb-50">
											<div class="widget-todo-title-area d-flex align-items-center">

												<span class="widget-todo-title ml-50 color-gray font-12">Driver Allowance</span>
											</div>
											<div class="widget-todo-item-action d-flex align-items-center">
												<span class="font-14"><?php echo Filter::moneyFormatter($objFare->driverAllowance); ?></span>
											</div>
										</div>
									</li>
									<li class="widget-todo-item <?= ($objFare->tollIncluded > 0) ? '' : 'hide' ?>">
										<div class="widget-todo-title-wrapper d-flex justify-content-between align-items-center mb-50">
											<div class="widget-todo-title-area d-flex align-items-center">

												<span class="widget-todo-title ml-50 color-gray font-12">Toll Tax (<?php echo ($objFare->tollIncluded > 0) ? "Included" : "Excluded"; ?>):</span>
											</div>
											<div class="widget-todo-item-action d-flex align-items-center">
												<span class="font-14"><?php echo Filter::moneyFormatter(($objFare->tollTax > 0) ? $objFare->tollTax : "0"); ?></span>
											</div>
										</div>
									</li>
									<li class="widget-todo-item <?= ($objFare->stateTax > 0) ? '' : 'hide' ?>">
										<div class="widget-todo-title-wrapper d-flex justify-content-between align-items-center mb-50">
											<div class="widget-todo-title-area d-flex align-items-center">

												<span class="widget-todo-title ml-50 color-gray font-12">State Tax (<?php echo ($objFare->stateTaxIncluded > 0) ? "Included" : "Excluded"; ?>):</span>
											</div>
											<div class="widget-todo-item-action d-flex align-items-center">
												<span class="font-14"><?php echo Filter::moneyFormatter(($objFare->stateTax > 0) ? $objFare->stateTax : "0"); ?></span>
											</div>
										</div>
									</li>
									<li class="widget-todo-item border-bottom">
										<div class="widget-todo-title-wrapper d-flex justify-content-between align-items-center mb-50">
											<div class="widget-todo-title-area d-flex align-items-center">

												<span class="widget-todo-title ml-50 color-gray font-12">GST (<?php echo $staxrate; ?>)%:</span>
											</div>
											<div class="widget-todo-item-action d-flex align-items-center">
												<span class="font-14"><?php echo Filter::moneyFormatter($objFare->gst); ?></span>
											</div>
										</div>
									</li>
									<li class="widget-todo-item">
										<div class="widget-todo-title-wrapper d-flex justify-content-between align-items-center mb-50">
											<div class="widget-todo-title-area d-flex align-items-center">

												<span class="widget-todo-title ml-50 color-gray font-12">Total</span>
											</div>
											<div class="widget-todo-item-action d-flex align-items-center">
												<span class="font-16 weight600"><?php echo Filter::moneyFormatter($objFare->totalAmount); ?></span>
<!--												<span data-toggle="tooltip" data-html="true" data-placement="top" title='<?= $details ?>'><img src="/images/bx-info-circle.svg" alt="img" width="14" height="14"></span>-->
											</div>
										</div>
									</li>
								</ul>


									</div>
								<div class="col-12 p0"><p class="font-11 mb5 text-muted text-center"> <?= $tripDesc ?>  </p></div>
								</div>
							</label>
						</div>
					</div>
				</div>
			</div>
			<?php
		}
	}
	?>
</div>
<?php $this->endWidget(); ?>

<script  type="text/javascript">
	$(function ()
	{
		$('[data-toggle="tooltip"]').tooltip();
		
		$('.cabInfoTooltip').click(function (evt)
        {
            evt.stopPropagation();
        });
	});
	
	function suggestedtripselect(triptype,leadid=0)
	{	//debugger;
		$href = "<?= Yii::app()->createUrl('booking/suggestedtripselect') ?>";
		var rdata = $("#travellercontact").closest("form").find("INPUT[name=rdata]").val();
		jQuery.ajax({type: 'POST',
			"dataType": "html",
			url: $href,
			data: {'rdata': rdata,'tripType': triptype, 'YII_CSRF_TOKEN': $('input[name="YII_CSRF_TOKEN"]').val(),'leadid':leadid},
			success: function(data)
			{	
				data = JSON.parse(data);
				if(data.success){
					window.location.href = data.url;
				}
				//$('#tab7').html(data);
			}
		});
	}
</script>

