<script>
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
</script>
<div class="" >
	<?php
	$serviceTaxRate			= BookingInvoice::getGstTaxRate($model->bkg_agent_id, $model->bkg_booking_type);
	$staxrate    = ($serviceTaxRate == 0)? 1 : $serviceTaxRate;
	$isPromo = BookingSub::model()->getApplicable($model->bkg_from_city_id, $model->bkg_to_city_id, 1);
	//$cabData = VehicleTypes::model()->getMasterCarDetails();
	$cabData = SvcClassVhcCat::model()->getVctSvcList('allDetail');
	$arr1	 = array_values($cabratedata1)[0];
	/* @var $model Booking */
	if (count($cabratedata) >0)
	{


		// $arrr = CJSON::decode($model->preData);
		$cityArr	 = $citiesInRoutes;
		$cityNameArr = $arrr['cityNameArr'];
		$incArr		 = [0 => 'Excluded', 1 => 'Included'];

		// $model=  Booking::model()->findByPk(25157);
		//   $cabRate = Rate::model()->getCabDetailsbyCities($model->bkg_from_city_id, $model->bkg_to_city_id);


		$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'cabrate-form1',
			'enableClientValidation' => true,
			'action'				 => Yii::app()->createUrl('agent/booking/createquote', []),
			'clientOptions'			 => array(
				'validateOnSubmit'	 => true,
				'errorCssClass'		 => 'has-error',
				'afterValidate'		 => 'js:function(form,data,hasError){
				if(!hasError){
					$.ajax({
						"type":"POST",

						"dataType":"html",
						"url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('agent/booking/cabagentratedetail')) . '",
						"data":form.serialize(),
                        "beforeSend": function(){
                            ajaxindicatorstart("");
                        },
                        "complete": function(){
                            ajaxindicatorstop();
                        },
						"success":function(data2){
                       
							var data = "";
							var isJSON = false;
							try {
								data = JSON.parse(data2);
								isJSON = true;
							} catch (e) {

							}
							if(!isJSON){
								 
							}
							else
							{
								var errors = data2.errors;
								settings=form.data(\'settings\');
								$.each (settings.attributes, function (i) {
									$.fn.yiiactiveform.updateInput (settings.attributes[i], errors, form);
								});
								$.fn.yiiactiveform.updateSummary(form, errors);
							}             
						},
						error: function (xhr, ajaxOptions, thrownError) 
						{
                        alert("dgdfg");
								alert(xhr.status);
								alert(thrownError);
						}
					});
				}
			}'
			),
			'enableAjaxValidation'	 => false,
			'errorMessageCssClass'	 => 'help-block',
			'htmlOptions'			 => array(
				//			'onsubmit' => "return false;", /* Disable normal form submit */
				//			'onkeypress' => "validateForm1();",
				'class' => 'form-horizontal',
			),
		));
		/* @var $form TbActiveForm */
		$form->attributes	 = $model->attributes;
		?>
		<?= $form->hiddenField($model, 'preData'); ?>
		<?= $form->hiddenField($model, 'preRutData'); ?>

		<?= $form->errorSummary($model); ?>
		<?= CHtml::errorSummary($model); ?>

		<div class="panel" style="box-shadow: 0 0 0 0;">            
			<div class="panel-body p0 border-none box-shadow-0">   

				<input type="hidden" id="step" name="step" value="agtrtv">
				<input type="hidden" id="ckm_rate" name="ckm_rate" >


				<?= $form->hiddenField($model, 'bkg_booking_type'); ?>           
				<?= $form->hiddenField($model, "bkg_vehicle_type_id"); ?>
				<?= $form->hiddenField($model, "bkg_rate_per_km_extra"); ?>
				<?= $form->hiddenField($model, "bkg_rate_per_km"); ?>
				<?
//            $predata = $model->preData;
//            $dataa = CJSON::decode($predata);
				?>
				<div id="error-border" style="<?= (CHtml::errorSummary($model) != '') ? "border:2px solid #a94442" : "" ?>" class="">
					<div class="row">
						<div class="col-xs-12 text-center mb20">
							<span class="mb0 font-22"><?
								//$ct = $model->getTripCitiesListbyId();
								$ct					 = implode(' &rarr; ', $quotData->routeDistance->routeDesc);
								echo $ct;
								//  echo $ct;
								?> </span>
							<div class="row mb5"><div class="col-xs-6 text-right est-1">Estimated Distance: <b> <?= $quotData->routeDistance->tripDistance . " Km" ?></b>,</div>
								<div class="col-xs-6 text-left est-1">Estimated Time: <b><?= $quotData->routeDuration->durationInWords ?></b></div>
							<? /*  Estimated Time: <b><?= $model->bkg_trip_duration_day ?></b></p> */ ?>
						</div> 
					</div>
					<div class="row hide">
						<div class="col-xs-12 summary-div border-none">
							<div class="checkbox ml20">      
								<? //= $form->checkboxGroup($model, 'bkg_tnc', ['label' => 'I Agree to the <a href="#" onclick="opentns()" >Terms and Conditions</a>'])            ?>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<?
					foreach ($cabratedata as $key => $quoteRate)
					{
						/* @var $routeRates routeRates */
						/* @var $routeDistance routeDistance */
						/* @var $routeDuration routeDuration */
						$routeRates		 = $quoteRate->routeRates;
						$routeDistance	 = $quoteRate->routeDistance;
						$routeDuration	 = $quoteRate->routeDuration;
						$tolltax_value	 = $routeRates->tollTaxAmount;
						$cab			 = $cabData[$key];
						$tolltax_flag	 = $routeRates->isTollIncluded; // $val['tolltax'];
						$statetax_value	 = $routeRates->stateTax; // $val['state_tax'];
						$statetax_flag	 = $routeRates->isStateTaxIncluded; //$val['statetax'];
						if (($tolltax_flag == 1 && $tolltax_value == 0) && ($statetax_flag == 1 && $statetax_value == 0))
						{
							$taxStr = '<i style="font-size:0.8em">(Toll Tax and State Tax included)</i>';
						}
						else if ($tolltax_flag == 0 && $statetax_flag == 0)
						{
							$taxStr = '<i style="font-size:0.8em">(Toll Tax and State Tax excluded may be apply later)</i>';
						}
						if ($cab['scc_id'] != '4')
						{
							$serviceTypeDesc	 = Config::get('booking.service.type.description');
							$objServiceTypeDesc	 = json_decode($serviceTypeDesc);
							$cabCategory = $cab['scv_vct_id'];
							?>
							<div class="col-xs-12 col-sm-6 col-md-4">
								<div class="card" style="min-height: 550px">
									<div class="card-body">
									<h5 style="" class="text-center bold font-18 mt0"><?= $cab['label'] ?> <span class="" data-toggle="tooltip" data-placement="top" title="<?php echo $objServiceTypeDesc->$cabCategory; ?>"><i class="fa fa-info-circle" aria-hidden="true"></i></span>
</h5>
<!--									<div class="text-center"><span><?php echo $objServiceTypeDesc->$cabCategory; ?></span></div>-->
									<div class="car_box text-center"><img src="<?= Yii::app()->baseUrl . '/' . $cab['vct_image'] ?>" alt="" width="120"></div>
									<div class="row pt10 car_bottom">
										<div class="col-xs-7">
											<h3 class="m0 text-uppercase font-16 weight600">Estimated Fare</h3><?= $taxStr ?>
										</div>
										<div class="col-xs-5 text-right">
											<h3 class="m0 text-uppercase font-16 weight600">
												&#x20b9;<?= round($routeRates->totalAmount); ?>
											</h3>
											<span class="small_text hide">(Approx.)</span>
										</div>
									</div>



									<?
									if ($routeRates->discFare != '')
									{
										?>
										<div class="row pt5">
											<h5 class="col-xs-8 text-uppercase text-danger" style="font-size: 18px">Discounted  Fare</h5>
											<div class="col-xs-4 text-right text-danger" style="font-size: 18px"><?= $routeRates->discFare; ?></div>
										</div>
									<? } ?>
									<div class="row pt5">
										<div class="col-xs-4">Model type </div>
										<div class="col-xs-8 text-right" style="white-space: nowrap;overflow: hidden;text-overflow: ellipsis;display: inline-block;max-width: 100%;"><span title="<?= $cab['vct_desc'] ?>"><?= $cab['vct_desc'] ?></span></div>
									</div>
									<div class="row pt5">
										<div class="col-xs-5">Capacity</div>
										<div class="col-xs-7 text-right"><?= $cab['vct_capacity'] ?> Passengers + Driver</div>
									</div>
									<?php
										$luggageCapacity = Stub\common\LuggageCapacity::init($cab['vct_id'], $cab['scc_id']);
									?>
									<div class="row pt5">
										<div class="col-xs-5">Luggage Capacity</div>
										<div class="col-xs-7 text-right">
												<?=(($luggageCapacity->largeBag !=0)?$luggageCapacity->largeBag. ' big bags /':'') ?>
												<?=(($luggageCapacity->smallBag !=0)?$luggageCapacity->smallBag. ' small bag ':'') ?>
											<!--	<?//= $luggageCapacity->largeBag ?> big bags / <?//= $luggageCapacity->smallBag ?> small bag -->
										</div>
									</div>
									<div class="row pt5">
										<div class="col-xs-6">Toll-Tax</div>
										<div class="col-xs-6 text-right"><?= $incArr[$routeRates->isTollIncluded] ?></div>
									</div>
									<div class="row pt5">
										<div class="col-xs-6">State-Tax:</div>
										<div class="col-xs-6 text-right"><?= $incArr[$routeRates->isStateTaxIncluded] ?></div>
									</div>
									<div class="row pt5">
										<div class="col-xs-4">Base Fare </div>
										<div class="col-xs-8 text-right">&#x20b9;<?= $routeRates->baseAmount; ?></div>
									</div>
                                    <div class="row pt5">
										<div class="col-xs-4">Airport Entry Fee </div>
										<div class="col-xs-8 text-right">&#x20b9;<?= $routeRates->airportEntryFee; ?></div>
									</div>
									<div class="row pt5">
										<div class="col-xs-6">Driver Allowance:</div>
										<div class="col-xs-6 text-right">&#x20b9;<?= $routeRates->driverAllowance ?></div>
									</div>
									<?
									//$staxrate	 = Filter::getServiceTaxRate();
									$taxLabel	 = ($serviceTaxRate == 5) ? 'GST' : 'Service Tax ';
									?>
									<?
									if ($cgst > 0)
									{
										?>
										<div class="row pt5">
											<div class="col-xs-6">CGST (@<?= Yii::app()->params['cgst'] ?>%):</div>
											<div class="col-xs-6 text-right">&#x20b9;<?= ((Yii::app()->params['cgst'] / $staxrate) * $routeRates->gst)|0; ?></div>
										</div>
									<? } ?>
									<?
									if ($sgst > 0)
									{
										?>
										<div class="row pt5">
											<div class="col-xs-6">SGST (@<?= Yii::app()->params['sgst'] ?>%):</div>
											<div class="col-xs-6 text-right">&#x20b9;<?= ((Yii::app()->params['sgst'] / $staxrate) * $routeRates->gst)|0; ?></div>
										</div>
									<? } ?>
									<?
									if ($igst > 0)
									{
										?>
										<div class="row pt5">
											<div class="col-xs-6">IGST (@<?= Yii::app()->params['igst'] ?>%):</div>
											<div class="col-xs-6 text-right">&#x20b9;<?= ((Yii::app()->params['igst'] / $staxrate) * $routeRates->gst)|0; ?></div>
										</div>
									<? } ?>
									<?
									if ($serviceTaxRate != 5)
									{
										?>
										<div class="row pt5">
											<div class="col-xs-6"><?= $taxLabel ?> (<?= $serviceTaxRate; ?>%):</div>
											<div class="col-xs-6 text-right">&#x20b9;<?= $routeRates->gst ?></div>
										</div>
									<? } ?>


									<div class="row pt5">
										<div class="col-xs-6">KM in Quote</div>
										<div class="col-xs-6 text-right"><?= $routeDistance->quotedDistance ?> Km</div>
									</div>
									<div class="row pt5">
										<div class="col-xs-6">Ext. Charge After <?= $routeDistance->quotedDistance ?> Kms.</div>
										<div class="col-xs-6 text-right"><?= $routeRates->ratePerKM ?> Km</div>
									</div>
									<div class="row pt5">
										<div class="col-xs-6">Ext. per min charges.</div>
										<div class="col-xs-6 text-right"><?= $routeRates->extraPerMinCharge ?> Min</div>
									</div>

									<div class="row mt10">
										<div class="col-xs-6 col-xs-offset-3">
											<button type="button" value="<?= $cab['scv_id'] ?>" kmr="<?= $routeRates->ratePerKM ?>" cmr="<?= $routeRates->costPerKM ?>" name="bookButton" class="btn btn-success  border-none  col-xs-12" onclick="validateForm1(this);"><b>Book Now</b></button>
										</div>
									</div>
</div>
								</div>
							</div>
						<? }
					}
					?>
				</div>

			</div> 
		</div>
		<?php $this->endWidget(); ?>
<? } 
else
	{
		?>
		<div class="panel">            
			<div class="panel-body pt0 pb0">   
				<h3>We are not offering services in this city for the given category</h3>
			</div>
		</div>
		<?
	} ?>
</div>


<div class="hide">
<? //php print_r($GLOBALS['API']);                   ?>
</div>
<script type="text/javascript">

	function validateForm1(obj) {
		ajaxindicatorstart("Please wait... ");
		var vht = $(obj).attr("value");
		var kmr = $(obj).attr("kmr");
		var cmr = $(obj).attr("cmr");
		//  alert(vht);
		// alert(kmr);
		if (vht > 0) {

			$('#<?= CHtml::activeId($model, "bkg_vehicle_type_id") ?>').val(vht);
			$('#<?= CHtml::activeId($model, "bkg_rate_per_km_extra") ?>').val(kmr);
			$('#<?= CHtml::activeId($model, "bkg_rate_per_km") ?>').val(cmr);
			$('#cabrate-form1').submit();
		}
	}

</script>