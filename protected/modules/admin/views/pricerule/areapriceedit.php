<style type="text/css">
    .select2-container-multi .select2-choices {
        min-height: 50px;
    }
    .new-booking-list .form-horizontal .form-group{ margin-left: 0; margin-right: 0;}
    .hide {
        display:none;
    }
    .textAlign{
        text-align: center !important; 
        vertical-align: middle !important; 
    }
    .form-group {
        margin-bottom: 0px;
    }
</style>
<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/city.js?v=' . $version);
if ($error != '')
{
	?>  
	<div class="col-xs-12 text-danger text-center"><?= $error ?></div> 
	<?php
}
else
{
	$carType	 = SvcClassVhcCat::model()->getVctSvcList();
	$areatype	 = AreaPriceRule::model()->areatype;
	$area		 = 0;
	$tripType	 = Booking::model()->getBookingType();
	?>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 pb10 new-booking-list" style="float: none; margin:0 auto;">
			<div class="row">
				<div class="upsignwidt">
					<div class="col-xs-12 col-sm-12 col-md-12">

						<?php
						$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
							'id'					 => 'editpricerule-manage-form', 'enableClientValidation' => TRUE,
							'clientOptions'			 => array(
								'validateOnSubmit'	 => true,
								'errorCssClass'		 => 'has-error'
							),
							// Please note: When you enable ajax validation, make sure the corresponding
							// controller action is handling ajax validation correctly.
							// See class documentation of CActiveForm for details on this,
							// you need to use the performAjaxValidation()-method described there.
							'enableAjaxValidation'	 => false,
							'errorMessageCssClass'	 => 'help-block',
							'htmlOptions'			 => array(
								'class' => 'form-horizontal'
							),
						));
						/* @var $form TbActiveForm */
						?>

						<div class="panel panel-default">
							<div class="panel-body">

								<div class="form-group">
									<div class="row" style="width: 1250px;overflow:hidden; margin:0 auto;"> 

										<!--Table-->
										<table class="table table-hover table-fixed table-responsive" style="table-layout:fixed;border-bottom: 1px solid #eee;">

											<tr>
												<td  class="bg-primary"></td>
												<?php
												foreach ($models as $key => $model)
												{
													?>

													<th  class="bg-primary text-center"><?= Filter::bookingTypes($key, true) ?></th>

												<?php } ?>
											</tr>

											<tr>
												<td class="bg-primary textAlign"><b>Calculation Type <span style="color:red;font-size:15pt">*</span></b></td>
												<?php
												foreach ($models as $key => $model)
												{
													$pref = $model->prr_id;
													if ($model->isNewRecord)
													{
														$pref = $key;
													}
													?>											
													<td  class="<?= $type ?>">

														<?php
//														$calcType	 = $model->calculation_type;
//														$dataJson	 = VehicleTypes::model()->getJSON($calcType);
//														$this->widget('booster.widgets.TbSelect2', array(
//															'model'			 => $model,
//															'attribute'		 => 'prr_calculation_type',
//															'val'			 => $model->prr_calculation_type,
//															'asDropDownList' => FALSE,
//															'options'		 => array('data' => new CJavaScriptExpression($dataJson)),
//															'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Calculation Type', 'id' => 'PriceRule_prr_calculation_type' .$pref)
//														));
														echo $form->dropDownListGroup($model, 'prr_calculation_type', array('label' => '', 'widgetOptions' => array('data' => $model->calculation_type, 'htmlOptions' => array('id' => 'PriceRule_prr_calculation_type' . $pref))));
														?>
													</td>
												<?php } ?>
											</tr>

											<tr>
												<td class="bg-primary textAlign"><b>Rate per km <span style="color:red;font-size: 15pt">*</span></b></td>
												<?php
												foreach ($models as $key => $model)
												{
													$type = ($model->isNewRecord)? 'bg-info': '' ;
													$pref = $model->prr_id;
													if ($model->isNewRecord)
													{
														$pref = $key;
													}
													?>
													<td class="<?= $type ?>"><?=
														$form->textFieldGroup($model, 'prr_rate_per_km', array('label'			 => '',
															'widgetOptions'	 => array('htmlOptions' => array('min' => 0, 'max' => 50, 'id' => 'PriceRule_prr_rate_per_km' . $pref, 'placeholder' => 'Rates (per km)'))));
														?>

																<?= $form->hiddenField($model, 'prr_cab_type'); ?>
																<?= $form->hiddenField($model, 'area_id'); ?>
																<?= $form->hiddenField($model, 'areaType'); ?>
																<?= $form->hiddenField($model, 'areaId'); ?>
																<?= $form->hiddenField($model, 'isType'); ?>
																<?php //$form->textField($model, 'prr_id');?>
<input type="hidden" id="PriceRule_prr_id<?=$key?>"name="PriceRule_prr_id<?=$key?>" value="<?=$model->prr_id?>">
													</td>

												<?php } ?>
											</tr>

											<tr>
												<td class="bg-primary textAlign"><b>Rates(per minute)</b></td>
												<?php
												foreach ($models as $key => $model)
												{
													$type = ($model->isNewRecord)? 'bg-info': '';
													$pref = $model->prr_id;
													if ($model->isNewRecord)
													{
														$pref = $key;
													}
													?>
													<td  class="<?= $type ?>"><?=
														$form->textFieldGroup($model, 'prr_rate_per_minute', array('label'			 => '',
															'widgetOptions'	 => array('htmlOptions' => array('min' => 0, 'max' => 50, 'id' => 'PriceRule_prr_rate_per_minute' . $pref, 'placeholder' => 'Rates (per minute)'))));
														?> </td>

												<?php } ?>
											</tr>

											<tr>
												<td class="bg-primary textAlign"><b>Rates(per km extra)<span style="color:red;font-size: 15pt">*</span></b></td>
												<?php
												foreach ($models as $key => $model)
												{
													$type = ($model->isNewRecord)? 'bg-info': '' ;
													$pref = $model->prr_id;
													if ($model->isNewRecord)
													{
														$pref = $key;
													}
													?>

													<td  class="<?= $type ?>">
														<?=
														$form->textFieldGroup($model, 'prr_rate_per_km_extra', array('label'			 => '',
															'widgetOptions'	 => array('htmlOptions' => array('min' => 0, 'max' => 50, 'id' => 'PriceRule_prr_rate_per_km_extra' . $pref, 'placeholder' => 'Rates (per minute)'))));
														?> 
													</td>

												<?php } ?>
											</tr>

											<tr>
												<td class="bg-primary textAlign"><b>Rates(per minute extra)</b></td>
												<?php
												foreach ($models as $key => $model)
												{
													$type = ($model->isNewRecord)? 'bg-info': '' ;
													$pref = $model->prr_id;
													if ($model->isNewRecord)
													{
														$pref = $key;
													}
													?>

													<td  class="<?= $type ?>">
														<?=
														$form->numberFieldGroup($model, 'prr_rate_per_minute_extra', array('label'			 => '',
															'widgetOptions'	 => array('htmlOptions' => array('min' => 0, 'max' => 50, 'id' => 'PriceRule_prr_rate_per_minute_extra' . $pref, 'placeholder' => 'Rates (per minute extra)'))));
														?>
													</td>

												<?php } ?>
											</tr>


											<tr>
												<td class="bg-primary textAlign"><b>Minimum Kilometer<span style="color:red;font-size: 15pt">*</span></b></td>
												<?php
												foreach ($models as $key => $model)
												{
													$type = ($model->isNewRecord)? 'bg-info': '' ;
													$pref = $model->prr_id;
													if ($model->isNewRecord)
													{
														$pref = $key;
													}
													?>

													<td  class="<?= $type ?>">
														<?=
														$form->numberFieldGroup($model, 'prr_min_km', array('label'			 => '',
															'widgetOptions'	 => array('htmlOptions' => array('min' => 0, 'max' => 50, 'id' => 'PriceRule_prr_min_km' . $pref, 'placeholder' => 'Minimum Kilometer'))));
														?>
													</td>

												<?php } ?>
											</tr>

											<tr>
												<td class="bg-primary textAlign"><b>Minimum Duration</b></td>
												<?php
												foreach ($models as $key => $model)
												{
													$type = ($model->isNewRecord)? 'bg-info': '' ;
													$pref = $model->prr_id;
													if ($model->isNewRecord)
													{
														$pref = $key;
													}
													?>

													<td  class="<?= $type ?>">
														<?=
														$form->numberFieldGroup($model, 'prr_min_duration', array('label'			 => '',
															'widgetOptions'	 => array('htmlOptions' => array('min' => 0, 'max' => 50, 'id' => 'PriceRule_prr_min_duration' . $pref, 'placeholder' => 'Minimum Duration'))));
														?>
													</td>

												<?php } ?>
											</tr>

											<tr>
												<td class="bg-primary textAlign"><b>Minimum Base Amount</b></td>
												<?php
												foreach ($models as $key => $model)
												{
													$type = ($model->isNewRecord)? 'bg-info': '' ;
													$pref = $model->prr_id;
													if ($model->isNewRecord)
													{
														$pref = $key;
													}
													?>	
													<td  class="<?= $type ?>"> 
														<?=
														$form->numberFieldGroup($model, 'prr_min_base_amount', array('label'			 => '',
															'widgetOptions'	 => array('htmlOptions' => array('min' => 0, 'max' => 5000, 'id' => 'PriceRule_prr_min_base_amount' . $pref, 'placeholder' => 'Minimum Base Amount'))));
														?>
													</td>
												<?php } ?>
											</tr>
											<tr>
												<td class="bg-primary textAlign"><b>Minimum Kilometer Per Day</b></td>
												<?php
												foreach ($models as $key => $model)
												{
													$type = ($model->isNewRecord)? 'bg-info': '' ;
													$pref = $model->prr_id;
													if ($model->isNewRecord)
													{
														$pref = $key;
													}
													?>	

													<td  class="<?= $type ?>">
														<?=
														$form->numberFieldGroup($model, 'prr_min_km_day', array('label'			 => '',
															'widgetOptions'	 => array('htmlOptions' => array('min' => 0, 'max' => 50, 'id' => 'PriceRule_prr_min_km_day' . $pref, 'placeholder' => 'Minimum Kilometer Per Day'))));
														?>
													</td>
												<?php } ?>
											</tr>

											<tr>
												<td class="bg-primary textAlign"><b>Maximum Kilometer Per Day</b></td>
												<?php
												foreach ($models as $key => $model)
												{
													$type = ($model->isNewRecord)? 'bg-info': '' ;
													$pref = $model->prr_id;
													if ($model->isNewRecord)
													{
														$pref = $key;
													}
													?>												
													<td  class="<?= $type ?>">
														<?=
														$form->numberFieldGroup($model, 'prr_max_km_day', array('label'			 => '',
															'widgetOptions'	 => array('htmlOptions' => array('min' => 0, 'max' => 500, 'id' => 'PriceRule_prr_max_km_day' . $pref, 'placeholder' => 'Maximum Kilometer Per Day'))));
														?>
													</td>
												<?php } ?>
											</tr>

											<tr>
												<td class="bg-primary textAlign"><b>Day Driver Allowance</td>
												<?php
												foreach ($models as $key => $model)
												{
													$type = ($model->isNewRecord)? 'bg-info': '' ;
													$pref = $model->prr_id;
													if ($model->isNewRecord)
													{
														$pref = $key;
													}
													?>		
													<td  class="<?= $type ?>">
														<?=
														$form->numberFieldGroup($model, 'prr_day_driver_allowance', array('label'			 => '',
															'widgetOptions'	 => array('htmlOptions' => array('min' => 0, 'max' => 50, 'id' => 'PriceRule_prr_day_driver_allowance' . $pref, 'placeholder' => 'Day Driver Allowance'))));
														?>
													</td>
												<?php } ?>
											</tr>
											<tr>
												<td class="bg-primary textAlign"><b>Night Driver Allowance</b></td>
												<?php
												foreach ($models as $key => $model)
												{
													$type = ($model->isNewRecord)? 'bg-info': '' ;
													$pref = $model->prr_id;
													if ($model->isNewRecord)
													{
														$pref = $key;
													}
													?>
													<td  class="<?= $type ?>">
														<?=
														$form->numberFieldGroup($model, 'prr_night_driver_allowance', array('label'			 => '',
															'widgetOptions'	 => array('htmlOptions' => array('min' => 0, 'max' => 50, 'id' => 'PriceRule_prr_night_driver_allowance' . $pref, 'placeholder' => 'Night Driver Allowance'))));
														?>
													</td>
												<?php } ?>
											</tr>

											<tr>
												<td class="bg-primary textAlign"><b>Driver Allowance Kilometer Limit</b></td>
												<?php
												foreach ($models as $key => $model)
												{
													$type = ($model->isNewRecord)? 'bg-info': '' ;
													$pref = $model->prr_id;
													if ($model->isNewRecord)
													{
														$pref = $key;
													}
													?>											
													<td  class="<?= $type ?>">
														<?=
														$form->numberFieldGroup($model, 'prr_driver_allowance_km_limit', array('label'			 => '',
															'widgetOptions'	 => array('htmlOptions' => array('min' => 0, 'max' => 50, 'id' => 'PriceRule_prr_driver_allowance_km_limit' . $pref, 'placeholder' => 'Driver Allowance Kilometer Limit'))));
														?>
													</td>
												<?php } ?>
											</tr>
											<tr>
												<td class="bg-primary textAlign"><b>Minimum Pick Up Duration</b></td>
												<?php
												foreach ($models as $key => $model)
												{
													$type = ($model->isNewRecord)? 'bg-info': '' ;
													$pref = $model->prr_id;
													if ($model->isNewRecord)
													{
														$pref = $key;
													}
													?>			

													<td class="<?= $type ?>">
														<?=
														$form->numberFieldGroup($model, 'prr_min_pickup_duration', array('label'			 => '',
															'widgetOptions'	 => array('htmlOptions' => array('min' => 0, 'max' => 50, 'id' => 'PriceRule_prr_min_pickup_duration' . $pref, 'placeholder' => 'Minimum Pick Up Duration'))));
														?>
													</td>
												<?php } ?>
											</tr>


											<tr align="center">
												<td class="bg-primary textAlign"><b>Select Night Start Time<span style="color:red;font-size: 15pt">*</span></b></td>
												<?php
												foreach ($models as $key => $model)
												{
													$type = ($model->isNewRecord)? 'bg-info': '' ;
													$pref = $model->prr_id;
													if ($model->prr_night_start_time != '')
													{
														$ptimeEnd = $model->prr_night_start_time;
													}
													else
													{
														$ptimeEnd = date('h:i A', strtotime('6am'));
													}

													if ($model->isNewRecord)
													{
														$pref = $key;
													}
													?>			
													<td class="<?= $type ?>">
														<?=
														$form->timePickerGroup($model, 'prr_night_start_time', array('label'			 => '',
															'widgetOptions'	 => array('options'		 => array('defaultTime'	 => true,
																	'autoclose'		 => true),
																'htmlOptions'	 => array('required'		 => true, 'placeholder'	 => 'Night Start Time',
																	'value'			 => date('h:i A', strtotime($ptimeEnd)),
																	'id'			 => 'PriceRule_prr_night_start_time' . $pref,
																	'class'			 => 'form-control pr0 border-radius text text-info ')),
															'groupOptions'	 => ['class' => 'm0'],
														));
														?> 
													</td>
												<?php } ?>
											</tr>

											<tr>
												<td class="bg-primary textAlign"><b>Select Night End Time <span style="color:red;font-size: 15pt">*</span></b></td>
												<?php
												foreach ($models as $key => $model)
												{
													$type = ($model->isNewRecord)? 'bg-info': '' ;
													$pref = $model->prr_id;
													if ($model->prr_night_end_time != '')
													{
														$ptimeEnd = $model->prr_night_end_time;
													}
													else
													{
														$ptimeEnd = date('h:i A', strtotime('6am'));
													}

													if ($model->isNewRecord)
													{
														$pref = $key;
													}
													?>			
													<td class="<?= $type ?>">
														<?=
														$form->timePickerGroup($model, 'prr_night_end_time', array('label'			 => '',
															'widgetOptions'	 => array('options'		 => array('defaultTime'	 => true,
																	'autoclose'		 => true),
																'htmlOptions'	 => array('required'		 => true, 'placeholder'	 => 'Night End Time',
																	'value'			 => date('h:i A', strtotime($ptimeEnd)),
																	'id'			 => 'PriceRule_prr_night_end_time' . $pref,
																	'class'			 => 'form-control pr0 border-radius text text-info')),
															'groupOptions'	 => ['class' => 'm0'],
														));
														?> 
													</td>
												<?php } ?>
											</tr>


											<tr>
												<td class="bg-primary textAlign"><b>Action</b></td>
												<?php
												foreach ($models as $key => $model)
												{
													$pref = 0;
													if ($model->isNewRecord)
													{
														$pref = $key;
													}
													$isExistingPriceRule = 'notExist';
													if ($model->prr_id != '')
													{
														$isExistingPriceRule = PriceRule::model()->checkExistingPriceRule($model->prr_id, $model->aprId);
														if ($isExistingPriceRule)
														{
															$isExistingPriceRule = 'exist';
														}
														else
														{
															$isExistingPriceRule = 'notExist';
														}
													}
													?>	
													<td>
														<div class="col-xs-12 text-center">
															<div class="col-xs-12 text-center">
																<input type="button" value="<?php echo ($model->isNewRecord || $model->isType) ? 'Add' : 'Update'; ?>" name="addUpdate<?=$pref?>" id="priceSave" class="btn <?php echo ($model->isNewRecord) ? 'btn-success' : 'btn-warning'; ?> btn-small" onClick="savePriceData(<?= ($model->prr_id > 0) ? $model->prr_id : 0; ?>, <?= $pref ?>, '<?= $isExistingPriceRule ?>')"> 
															</div>

															<div class="col-xs-12 text-center">
																<?php
																if (!$model->isNewRecord)
																{
																	?>
																	<input type="button" data-toggle="ajaxModal" value="View" name="yt0" id="priceView" class="btn btn-info btn-small" onClick="priceDataPopUp(<?= $model->prr_id; ?>)">
																<?php } ?>	
															</div>
															<div class="col-xs-12"><?php
																if ($isExistingPriceRule == 'exist')
																{
																	echo "*This rule is exists in other area or cab.";
																}
																?></div>

														</div>
													</td>
												<?php } ?>
											</tr>
<tr><td align="center" colspan="8">
<input type="button" value="Add / Update All" name="yt0" id="" class="btn btn-warning btn-small" onclick="updateAllPriceData()"></td></tr>
										</table>
									</div>
								

	

</div>
							</div>
						</div>
						<?php $this->endWidget(); ?>

					</div>
				</div>
			</div> 
		</div>
	</div>
	<script type="text/javascript">
		var city = new City();
		$('#editpricerule-manage-form').submit(function (event) {
			var cabType = $('#PriceRule_prr_cab_type').val();
			if (cabType == '')
			{
				$("#PriceRule_prr_cab_type_em_").text("Car type cannot be blank");
				$("#PriceRule_prr_cab_type_em_").css({"color": "#a94442", "display": "block"});
				$("#errMsg").css({"color": "#f25656"});
				return false;
			} else {
				$("#PriceRule_prr_cab_type_em_").css({"display": "none"});
			}
			return true;
		});

		function savePriceData(id, tripType, existingPriceRule)
		{
			var prrId = id;

			if (existingPriceRule == "exist" && id > 0)
			{
				if (!confirm('This rule is used in other area or cab. Are you sure to update this rule?')) {
					bootbox.hideAll();
					return false;
				}
			}
			if (id == 0) {
				id = tripType;
			}
			$.ajax({
				"type": "GET",
				"async": false,
				"url": '<?= Yii::app()->createUrl('admin/pricerule/edit') ?>',
				"data": {'ratePerKm': $('#PriceRule_prr_rate_per_km' + id).val(),
					'ratePerMintute': $('#PriceRule_prr_rate_per_minute' + id).val(), 'ratePerKmExtra': $('#PriceRule_prr_rate_per_km_extra' + id).val(),
					'ratePerMinExtra': $('#PriceRule_prr_rate_per_minute_extra' + id).val(), 'minKilometer': $('#PriceRule_prr_min_km' + id).val(),
					'minDuration': $('#PriceRule_prr_min_duration' + id).val(), 'minBaseAmount': $('#PriceRule_prr_min_base_amount' + id).val(),
					'minKmDay': $('#PriceRule_prr_min_km_day' + id).val(), 'maxKmDay': $('#PriceRule_prr_max_km_day' + id).val(),
					'dayDriverAllowance': $('#PriceRule_prr_day_driver_allowance' + id).val(), 'nightDriverAllowance': $('#PriceRule_prr_night_driver_allowance' + id).val(),
					'nightDriverAllowanceKmLimit': $('#PriceRule_prr_driver_allowance_km_limit' + id).val(), 'minPickDuration': $('#PriceRule_prr_min_pickup_duration' + id).val(),
					'nightStartTime': $('#PriceRule_prr_night_start_time' + id).val(), 'nightEndTime': $('#PriceRule_prr_night_end_time' + id).val(),
					'calculationType': $('#PriceRule_prr_calculation_type' + id).val(), 'prr_id': prrId, 'tripType': tripType, 'cabType': $('#PriceRule_prr_cab_type').val(),
					'areaId': $('#PriceRule_area_id').val(), 'areaType': $('#PriceRule_areaType').val(), 'apr_area_id': $('#PriceRule_areaId').val(), 'isType': $('#PriceRule_isType').val(),
					'priceSave': "btn"},
				"dataType": "json",
				"success": function (data1)
				{
					if (data1.success == true)
					{
						alert("Price rule added/updated successfully.");
						piceEdit();
						
					} else
					{
						alert(data1.message);
					}
				}
			});
			return false;
		}

		function priceDataPopUp(id)
		{
			$('.bootbox').modal('hide');
			$("#resultLoading").hide();
			$.ajax({
				"type": "GET",
				"async": false,
				"url": '<?= Yii::app()->createUrl('admin/pricerule/view') ?>',
				"data": {'prrid': id, 'priceView': "btn"},
				"dataType": "html",
				"success": function (data)
				{
					var box = bootbox.dialog({
						message: data,
						size: 'large',
						title: 'Price Rule Details',
						onEscape: function () {
							if ($('body').hasClass("modal-open")) {
								box.on('hidden.bs.modal', function (e) {
									$('body').addClass('modal-open');
								});
							}
						}
					});
					return false;
				}
			});
			return false;
		}
		function updateAllPriceData()
		{
			var id;
			var arr =[1,2,3,4,9,10,16,11];
			$.each(arr, function (index, value) {
			var prr_id = $('#PriceRule_prr_id' + value).val();
	
			if(prr_id == '')
				{
				id = value;
				}else{
					id = prr_id;
				}
		 $.ajax({
				"type": "GET",
				"async": false,
				"url": '<?= Yii::app()->createUrl('admin/pricerule/edit') ?>',
				"data": {'ratePerKm': $('#PriceRule_prr_rate_per_km' + id).val(),
					'ratePerMintute': $('#PriceRule_prr_rate_per_minute' + id).val(), 'ratePerKmExtra': $('#PriceRule_prr_rate_per_km_extra' + id).val(),
					'ratePerMinExtra': $('#PriceRule_prr_rate_per_minute_extra' + id).val(), 'minKilometer': $('#PriceRule_prr_min_km' + id).val(),
					'minDuration': $('#PriceRule_prr_min_duration' + id).val(), 'minBaseAmount': $('#PriceRule_prr_min_base_amount' + id).val(),
					'minKmDay': $('#PriceRule_prr_min_km_day' + id).val(), 'maxKmDay': $('#PriceRule_prr_max_km_day' + id).val(),
					'dayDriverAllowance': $('#PriceRule_prr_day_driver_allowance' + id).val(), 'nightDriverAllowance': $('#PriceRule_prr_night_driver_allowance' + id).val(),
					'nightDriverAllowanceKmLimit': $('#PriceRule_prr_driver_allowance_km_limit' + id).val(), 'minPickDuration': $('#PriceRule_prr_min_pickup_duration' + id).val(),
					'nightStartTime': $('#PriceRule_prr_night_start_time' + id).val(), 'nightEndTime': $('#PriceRule_prr_night_end_time' + id).val(),
					'calculationType': $('#PriceRule_prr_calculation_type' + id).val(), 'prr_id': prr_id, 'tripType': value, 'cabType': $('#PriceRule_prr_cab_type').val(),
					'areaId': $('#PriceRule_area_id').val(), 'areaType': $('#PriceRule_areaType').val(), 'apr_area_id': $('#PriceRule_areaId').val(), 'isType': $('#PriceRule_isType').val(),
					'priceSave': "btn"},
				"dataType": "json",
				"success": function (data1)
				{
					if (data1.success == true)
					{
						alert("Price rule added/updated successfully.");
						//updateAllPriceData();
						return false;
					} else
					{
						alert(data1.message);
					}
				}
			});
        });
		piceEdit();
		}

	</script>
<?php }
?>
