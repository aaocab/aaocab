<?php
/** @var CActiveForm $form */
$form = $this->beginWidget('CActiveForm', array(
	'id'					 => 'travellercontact',
	'enableClientValidation' => false,
	'clientOptions'			 => [
		'validateOnSubmit'	 => false,
		'errorCssClass'		 => 'has-error',
	],
	'enableAjaxValidation'	 => false,
	'errorMessageCssClass'	 => 'help-block',
	'action'				 => Yii::app()->createUrl('booking/travellercontact'),
	'htmlOptions'			 => array(
		"onsubmit"		 => "return checkTravellerContact();",
		'class'			 => 'form-horizontal',
		'autocomplete'	 => 'off',
	),
		));


$tripType = $pageRequest->booking->tripType;
$tripSelectedDesc = TncPoints::getSelectedTripContentkayak($tripType)
?>
<div class="row">
	<div class="col-12 col-lg-8 offset-lg-2">
<h1 class="font-18 weight500"><b>Your current selection</b></h1>
<div class="row">
<?php
					//foreach ($cabQuote as $tripTypeKey => $value)
					//{
						//$bookingType = filter::bookingTypes($tripTypeKey, true);
						//$tripType	 = explode("(", $bookingType)[0];
						//$tripDesc	 = TncPoints::getSuggestTripContentkayak($tripTypeKey);
						$bookingType = filter::bookingTypes($pageRequest->booking->tripType, true);
						$tripType	 = explode("(", $bookingType)[0];

						foreach ($cabQuote->cabRate as $key => $cab)
						{
							$cabRate	 = $cab;
							//print'<pre>';print_r($cab);
							$vctModel	 = VehicleCategory::model()->findByPk($cabRate->cab->cabCategory->scvVehicleId);
							$catLabel = SvcClassVhcCat::getCatrgoryLabel($cabRate->cab->id);
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
							
							$tripHr = ($pageRequest->booking->tripType == 10)?'8 hr - ':(($pageRequest->booking->tripType == 11)?'12 hr - ':''); 
							$staxrate	 = BookingInvoice::getGstTaxRate($pageRequest->booking->agentId, $pageRequest->booking->tripType);
							?>
							<div class="col-xl-12 flex2 cb-none ct-1 ct-2">
								<div class="card">
									
									<?php //$details	 = $this->renderPartial("fareBrkup", ['serviceTier' => $cabRate, 'partnerId' => $pageRequest->booking->agentId, 'tripType' => $pageRequest->booking->tripType], true); ?>
									<div class="card-body">
										<div class="row">
											<div class="col-md-8">
												<div class="card-header p0" style="display: inline-block;">
													<p class="mb0"><span class="heading-line-2"><?=$catLabel . ' | ' . $tripType ?></span> <span class="badge badge-pill badge-secondary pt5 pb5" style="position: relative; top: -4px;"><img src="/images/bxs-tachometer2.svg" alt="img" width="12" height="12"> <span class="font-12 weight500"><?= $tripHr ?><?= $cabRate->distance; ?> km  </span></span></p>
												</div>
												<p class="mb0"><img src="<?= "/" . $vctModel->vct_image ?>" width="150" class="img-fluid" alt="singleminded"></p>
												<div class="flex-fill pl40 mt10">
												<p>
													<span data-toggle="tooltip" data-placement="top" title="<?php echo $vctModel->vct_capacity . " passengers"; ?>"><img src="/images/bxs-group.svg" alt="img" width="14" height="14"><span class="font-16 weight600 pr5"><?= $vctModel->vct_capacity; ?></span></span>
													<span data-toggle="tooltip" data-placement="top" title="<?php echo $cabRate->cab->bagCapacity . " bags"; ?>"><img src="/images/bxs-shopping-bag.svg" alt="img" width="14" height="14"><span class="font-16 weight600 pr5"><?= $cabRate->cab->bagCapacity; ?></span></span>
													
												</p>
											</div>
											</div>
											<div class="col-md-4">
												<p class="text-right mb10"><span class="badge badge-pill <?= $colorclass ?> badge-new"><?= $cabRate->cab->cabCategory->catClass ?></span></p>
												<div class="row">
													<div class="col-12">
														<ul class="widget-todo-list-wrapper pl0" style="list-style-type: none;">
																<li class="widget-todo-item">
																	<div class="widget-todo-title-wrapper d-flex justify-content-between align-items-center mb-50">
																		<div class="widget-todo-title-area d-flex align-items-center">
																			
																			<span class="widget-todo-title ml-50 color-gray">Base fare</span>
																		</div>
																		<div class="widget-todo-item-action d-flex align-items-center">
																			<span class="font-16"><?php echo Filter::moneyFormatter($objFare->baseFare); ?></span>
																		</div>
																	</div>
																</li>
																<li class="widget-todo-item <?= ($objFare->driverAllowance > 0) ? '' : 'hide' ?>">
																	<div class="widget-todo-title-wrapper d-flex justify-content-between align-items-center mb-50">
																		<div class="widget-todo-title-area d-flex align-items-center">
																			
																			<span class="widget-todo-title ml-50 color-gray">Driver Allowance</span>
																		</div>
																		<div class="widget-todo-item-action d-flex align-items-center">
																			<span class="font-16"><?php echo Filter::moneyFormatter($objFare->driverAllowance); ?></span>
																		</div>
																	</div>
																</li>
																<li class="widget-todo-item <?= ($objFare->tollIncluded > 0) ? '' : 'hide' ?>">
																	<div class="widget-todo-title-wrapper d-flex justify-content-between align-items-center mb-50">
																		<div class="widget-todo-title-area d-flex align-items-center">
																			
																			<span class="widget-todo-title ml-50 color-gray">Toll Tax (<?php echo ($objFare->tollIncluded > 0) ? "Included" : "Excluded"; ?>):</span>
																		</div>
																		<div class="widget-todo-item-action d-flex align-items-center">
																			<span class="font-16"><?php echo Filter::moneyFormatter(($objFare->tollTax > 0) ? $objFare->tollTax : "0"); ?></span>
																		</div>
																	</div>
																</li>
																<li class="widget-todo-item <?= ($objFare->stateTax > 0) ? '' : 'hide' ?>">
																	<div class="widget-todo-title-wrapper d-flex justify-content-between align-items-center mb-50">
																		<div class="widget-todo-title-area d-flex align-items-center">
																			
																			<span class="widget-todo-title ml-50 color-gray">State Tax(<?php echo ($objFare->stateTaxIncluded > 0) ? "Included" : "Excluded"; ?>):</span>
																		</div>
																		<div class="widget-todo-item-action d-flex align-items-center">
																			<span class="font-16"><?php echo Filter::moneyFormatter(($objFare->stateTax > 0) ? $objFare->stateTax : "0"); ?></span>
																		</div>
																	</div>
																</li>
																<li class="widget-todo-item border-bottom">
																	<div class="widget-todo-title-wrapper d-flex justify-content-between align-items-center mb-50">
																		<div class="widget-todo-title-area d-flex align-items-center">
																			
																			<span class="widget-todo-title ml-50 color-gray">GST (<?php echo $staxrate; ?>)%:</span>
																		</div>
																		<div class="widget-todo-item-action d-flex align-items-center">
																			<span class="font-16"><?php echo Filter::moneyFormatter($objFare->gst); ?></span>
																		</div>
																	</div>
																</li>
																<li class="widget-todo-item mt5">
																	<div class="widget-todo-title-wrapper d-flex justify-content-between align-items-center mb-50">
																		<div class="widget-todo-title-area d-flex align-items-center">
																			
																			<span class="widget-todo-title ml-50 color-gray">Total</span>
																		</div>
																		<div class="widget-todo-item-action d-flex align-items-center">
																			<span class="font-20 weight600"><?php echo Filter::moneyFormatter($objFare->totalAmount); ?></span>
<!--																			<span data-toggle="tooltip" data-html="true" data-placement="top" title='<?= $details ?>'><img src="/images/bx-info-circle.svg" alt="img" width="14" height="14"></span>-->
																		</div>
																	</div>
																</li>
															</ul>
													</div>
												</div>
											</div>
											<div class="col-md-12"><?= $tripSelectedDesc ?></div>
										</div>

										

										<p class="mb-0">
											<span class="font-13 del-diagonal color-gray <?= ($cabRate->fare->baseFare == ($objFare->baseFare - $objFare->discount)) ? "hide" : "" ?>"><?php echo Filter::moneyFormatter($cabRate->fare->baseFare); ?></span>
											
										</p>
										<p class="font-13 mb5  mb0 mr10 mt10"><?= $tripDesc ?></p>


									</div>
								</div>
							</div>
							<div class="col-xl-3 col-md-4 col-sm-12 flex2 cs-none ct-1 ct-2">
								<div class="card mb-2">
									<div class="radio-style7">
										<div class="radio">
											<label>
												<div class="row m0">
													<div class="col-12 ct-2 p0">
														<p class="font-16 weight600 mb0 mt5"><?= $vctModel->vct_label ?><span class="mb0 text-center cabInfoTooltip"> | <?= $tripType ?></span> <span class="badge badge-pill badge-secondary p5 pl10 pr10" style="position: relative; top: -3px;"><img src="/images/bxs-tachometer2.svg" alt="img" width="12" height="12"> <span class="font-11 weight500"><?= $tripHr ?><?= $cabRate->distance; ?> km  </span></span></p>
													</div>
													<div class="col-6 p0"><span class="text-center"> <img src="/images/cabs/car-etios.jpg" width="100" class="img-fluid"></span>
													<div class="mt10 pl20">

															<span data-toggle="tooltip" data-placement="top" title="<?php echo $vctModel->vct_capacity . " passengers"; ?>"><img src="/images/bxs-group.svg" alt="img" width="18" height="18"><span class="font-14 weight600 pr5 align-middle"><?= $vctModel->vct_capacity ?></span></span>
															<span data-toggle="tooltip" data-placement="top" title="<?php echo $cabRate->cab->bagCapacity . " bags"; ?>"><img src="/images/bxs-shopping-bag.svg" alt="img" width="18" height="18"><span class="font-14 weight600 pr5"><?= $cabRate->cab->bagCapacity ?></span></span>
															
						<!--												<p>Going from one city to another city. Direct transfer from pickup address to your drop-off address. You are not allowed for any unplanned pickups,drops or detours unless mentioned.</p>-->
														<p class="font-11 mb5 text-muted text-center"> <?= $tripDesc ?>  </p>
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
																			<span class="font-18 weight600"><?php echo Filter::moneyFormatter($objFare->totalAmount); ?></span>
<!--																			<span data-toggle="tooltip" data-html="true" data-placement="top" title='<?= $details ?>'><img src="/images/bx-info-circle.svg" alt="img" width="14" height="14"></span>-->
																		</div>
																	</div>
																</li>
															</ul>
													</div>
													<div class="col-12 font-12 p0 mb5"> <?= $tripSelectedDesc ?></div>
												</div>
											</label>
										</div>
									</div>
								</div>
							</div>
							<?php
						}
					//}
					?>
</div>
		<div class="card mb20">
			<div class="card-body p15">
				<div class="row justify-center">
				
	            </div>
				<div class="row">
					<div class="col-12">
						<p class="font-18 weight500">Traveller Info</p>
					</div>
					<div class="col-12  text-center">
						<div class='errorSummaryContactinfo text-danger'></div>
					</div>
					<div class="col-12 col-lg-6">
						<p class="mb5"><small class="form-text">First name</small></p>
						<fieldset class="form-group position-relative">
					<!--													<input type="text" class="form-control" id="iconLeft" placeholder="Enter first name">-->
							<?= $form->textField($model, 'bkg_user_fname', ['placeholder' => "Enter first name", 'class' => 'form-control m0 firstname', 'id' => 'iconLeft', 'required' => true]) ?>
						</fieldset>
					</div>
					<div class="col-12 col-lg-6">

						<p class="mb5"><small class="form-text">Last name</small></p>
						<fieldset class="form-group position-relative">
							<?= $form->textField($model, 'bkg_user_lname', ['placeholder' => "Enter last name", 'class' => 'form-control m0 lastname', 'id' => 'iconLeft', 'required' => true]) ?>
						</fieldset>
					</div>

					<div class="col-12 col-lg-6">

						<p class="mb5"><small class="form-text">Phone number</small></p>
						<fieldset class="form-group position-relative">
							<?php
							$this->widget('ext.intlphoneinput.IntlPhoneInput', array(
								'model'					 => $model,
								'attribute'				 => 'fullContactNumber',
								'codeAttribute'			 => 'bkg_country_code',
								'numberAttribute'		 => 'bkg_contact_no',
								'options'				 => array(// optional
									'separateDialCode'	 => true,
									'autoHideDialCode'	 => true,
									'initialCountry'	 => 'in'
								),
								'htmlOptions'			 => ['class' => 'form-control phoneno in-style', 'style' => 'padding-left:81px!important;', 'id' => 'fullContactNumber1', 'required' => true],
								'localisedCountryNames'	 => false,
							));
							?> 
						</fieldset>
					</div>
					<div class="col-12 col-lg-6">
						<p class="mb5"><small class="form-text">Email address</small></p>
						<fieldset class="form-group position-relative">
							<?= $form->emailField($model, 'bkg_user_email', ['placeholder' => "Email Address", 'class' => 'form-control m0 emailaddress', 'required' => true]) ?> 
						</fieldset>
					</div>
					<div class="col-12 text-center">
						<input type="hidden" name="step" value="<?= $pageid ?>">
						<input type="hidden" name="rdata" value="<?= $this->pageRequest->getEncrptedData() ?>">
						<button type="submit" class="btn btn-primary pl-5 pr-5">Proceed</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="tripsuggest">

</div>
<?php $this->endWidget(); ?>

<script  type="text/javascript">

	$(document).ready(function ()
	{
		step = <?= $step ?>;
		tabURL = "<?= $this->getURL($this->pageRequest->getTravellerURLKayak()) ?>";
		pageTitle = "";
		tabHead = "<?= $this->pageRequest->getItineraryDesc() ?>";
		toggleStep(step, 7, tabURL, pageTitle, tabHead, true, <?= $this->pageRequest->step ?>);
		//getTraveller(<?php echo $objPage->booking->id; ?>);
		agentId = '<?= $pageRequest->booking->agentId ?>';
		kayakAgentId = '<?= Config::get('Kayak.partner.id') ?>';
		if (agentId == kayakAgentId)
		{
			catName = "<?= $this->pageRequest->getCabServiceCategoryDesc() ?>";
			$('a[href="#tab6"]').text(catName);
			bookingtype = "<?= $this->pageRequest->getBkgTypeDesc() ?>";
			$('a[href="#tab4"]').text(bookingtype);
		}

		bookingId = "<?= $pageRequest->booking->id; ?>";
		//debugger;
		var defLeadId = '<?= $pageRequest->booking->defLeadId ?>';
		getTripSuggestions(bookingId,defLeadId);
	});

	function checkTravellerContact() {
		$('.errorSummaryContactinfo').html('');
		var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
		reg.test($('#BookingUser_bkg_user_email').val())

		var form = $("form#travellercontact");
		$.ajax({
			"type": "POST",
			"dataType": "json",
			"url": "<?= CHtml::normalizeUrl($this->getURL('booking/travellercontact')) ?>",
			"data": form.serialize(),
			"beforeSend": function ()
			{
				blockForm(form);
			},
			"complete": function ()
			{
				unBlockForm(form);
			},
			"success": function (data)
			{

				if (data.success)
				{
					window.location.href = data.url;
				} else
				{
					$('.errorSummaryContactinfo').html(data.errMessage);
				}
			},
			"error": function (xhr, ajaxOptions, thrownError)
			{
				alert(xhr.status);
				alert(thrownError);
			}
		});
		return false;

	}
	
	
	function getTripSuggestions(booking_id,defleadId=0)
	{
		$href = "<?= Yii::app()->createUrl('booking/tripsuggestions') ?>";
		jQuery.ajax({type: 'GET',
			"dataType": "html",
			url: $href,
			data: {"bkg_id": booking_id,"dlid":defleadId},
			"beforeSend": function ()
			{
				blockForm($('#travellercontact'));
			},
			"complete": function ()
			{
				unBlockForm($('#travellercontact'));
			},
			success: function(data)
			{	
				$(".tripsuggest").html(data);
			}
		});
	}
</script>

