<style type="text/css">
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; 
        margin: 0; 
    }
    input[type="number"] {
        -moz-appearance: textfield;
    }
	.checkbox-inline{ padding-left: 0; vertical-align: middle;}

</style>
<?
/* @var $model Booking */
$fcity					 = Cities::getName($model->bkg_from_city_id);
$tcity					 = Cities::getName($model->bkg_to_city_id);
$infosource				 = BookingAddInfo::model()->getInfosource('user');
$routeCityList			 = $model->getTripCitiesListbyId();
$ct						 = implode(' &#10147 ', $routeCityList);
$action					 = Yii::app()->request->getParam('action');
$hash					 = Yii::app()->request->getParam('hash');
$otherExist				 = ($model->bkgAddInfo->bkg_spl_req_other != '') ? 'block' : 'none';
$model->bkg_chk_others	 = ($model->bkgAddInfo->bkg_spl_req_other != '') ? 1 : 0;
$response				 = Contact::referenceUserData($model->bkgUserInfo->bui_id, 3);
if ($response->getStatus())
{
$contactNo	 = $response->getData()->phone['number'];
$countryCode = $response->getData()->phone['ext'];
$firstName	 = $response->getData()->email['firstName'];
$lastName	 = $response->getData()->email['lastName'];
$email		 = $response->getData()->email['email'];
}
//echo $abc = ($showAdditional) ? 'True' : 'False';
?>

<div class="container">
	<div class="col-xs-12 col-md-8">
		<?
		if ($payment)
		{
		if ($succ == 'success')
		{
		?> 
		<div role="alert" class="alert alert-success"> 
			<strong>Transaction was successful. Thank you for your order. Your Transaction Id : <?= $transid ?></strong>
		</div>
		<?
		}
		elseif ($succ == 'fail')
		{
		?>
		<div role="alert" class="alert alert-danger"> 
			<strong>Oh snap!</strong> Something went wrong. Transaction was not successful. 
		</div>
		<?
		}
		else
		{
		?>
		<div role="alert" class="alert alert-block"> 
			<strong>Oh snap!</strong> Something went wrong. Please wait and refresh after sometime. 
		</div>
		<?
		}
		}
		else
		{
		if ($model->bkgInvoice->bkg_advance_amount > 0)
		{
		echo '<h3 class="mb10 font-24 weight600">Booking Confirmed</h3>';
		}
		else
		{
		echo '<h3 class="mb10 font-24 weight600">Booking Created</h3>';
		}
		?>
		<?php
		}
		?>
		<?
		if ($model->bkg_status == 2 && $showAdditional && $succ == 'success')
		{
		?>
		<p class="weight400"> Booking Successful! You will receive the cab details 3 hours before your scheduled pickup time.
		</p>
		<? } ?>
		<?
		if ($model->bkg_status == 1 && $model->bkgInvoice->bkg_corporate_remunerator != 2 && $isApproved != 1)
		{
		?>
		<span class="text-danger">Gozo has sent an OTP to the customer by SMS and email.</span><br>
		<span class="text-danger">Customer must confirm the booking by entering the OTP at the link provided to them.</span>
		<? } ?>
		<p></p>
		<p></p>
	<!--        <p class="text-left"> 
			Please review the details of your booking request.
		</p>-->
	</div>

	<div class="col-xs-12 col-md-8">
		<div class="panel panel-default">
			<div class="panel-body">
				<div class="row">
					<div class="col-xs-12 h4">

						<?= $ct ?> (<?= ucwords($model->getBookingType($model->bkg_booking_type, 'Trip')) ?>)


					</div>
					<div class="col-xs-12">
						<div class="row">
							<div class="col-sm-4 pull-left h5">
								Booking ID : <?= $model->bkg_booking_id ?>
							</div>
							<div class="col-sm-8 text-right h5">
								Status : <b><?= Booking::model()->getActiveBookingStatus($model->bkg_status) ?></b>
							</div>
						</div> 
					</div>
					<div class="col-xs-12 mt20">
						<div class="row mb10">
							<div class="col-xs-12 col-sm-3"><b>Full Name:</b></div>
							<div class="col-xs-12 col-sm-9"><?= $firstName . ' ' . $lastName ?></div>
						</div>
					</div>
					<div class="col-xs-12">
						<div class="row mb10">
							<div class="col-xs-12 col-sm-3"><b>Cab:</b></div>
							<div class="col-xs-12 col-sm-9"><?= $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label . ' ' . $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_desc ?></div>
						</div>
					</div>
					<div class="col-xs-12">
						<div class="row mb10">
							<div class="col-xs-12 col-sm-3"><b>Date:</b></div>
							<div class="col-xs-12 col-sm-9"><?= date('jS M Y (l)', strtotime($model->bkg_pickup_date)) ?></div>
						</div>
					</div>
					<div class="col-xs-12">
						<div class="row mb10">
							<div class="col-xs-12 col-sm-3"><b>Time:</b></div>
							<div class="col-xs-12 col-sm-9"><?= date('h:i A', strtotime($model->bkg_pickup_date)) ?></div>
						</div>
					</div>
					<div class="col-xs-12">
						<div class="row mb10">
							<div class="col-xs-12 col-sm-3"><b>Pickup Point:</b></div>
							<div class="col-xs-12 col-sm-9"><?= $model->bkg_pickup_address . ', ' . $fcity ?></div>
						</div>
					</div>
					<div class="col-xs-12">
						<div class="row mb10">
							<div class="col-xs-12 col-sm-3"><b>Drop Area:</b></div>
							<div class="col-xs-12 col-sm-9"><?= $model->bkg_drop_address . ', ' . $tcity ?></div>
						</div>
					</div>
					<?
					if ($contactNo != '')
					{
					?>
					<div class="col-xs-12">
						<div class="row mb10">
							<div class="col-xs-12 col-sm-3"><b>Primary Phone:</b></div>
							<div class="col-xs-12 col-sm-9">+<?= $countryCode ?><?= $contactNo ?></div>
						</div>
					</div>
					<? } ?>
					<?
					if ($model->bkgUserInfo->bkg_alt_contact_no != '')
					{
					?>
					<div class="col-xs-12">
						<div class="row mb10">
							<div class="col-xs-12 col-sm-3"><b>Alternate Phone:</b></div>
							<div class="col-xs-12 col-sm-9">+<?= $model->bkgUserInfo->bkg_alt_country_code ?><?= $model->bkgUserInfo->bkg_alt_contact_no ?></div>
						</div>
					</div>
					<?
					} if ($email != '')
					{
					?>

					<div class="col-xs-12">
						<div class="row mb10">
							<div class="col-xs-12 col-sm-3"><b>Email:</b></div>
							<div class="col-xs-12 col-sm-9"><?= $email ?></div>
						</div>
					</div>
					<? } ?>
					<div class="col-xs-12">
						<div class="row mb10">
							<div class="col-xs-12 col-sm-3"><b>Base Fare:</b></div>
							<div class="col-xs-12 col-sm-9">&#x20b9;<?= $model->bkgInvoice->bkg_base_amount; ?></div>
						</div>
					</div>
					<?
					if ($model->bkgInvoice->bkg_discount_amount > 0)
					{
					?>
					<div class="col-xs-12">
						<div class="row mb10">
							<div class="col-xs-12 col-sm-3"><b>Discount:</b></div>
							<div class="col-xs-12 col-sm-9">&#x20b9;<?= $model->bkgInvoice->bkg_discount_amount; ?></div>
						</div>
					</div>
					<?
					}

					if ($model->bkgInvoice->bkg_is_airport_fee_included > 0)
					{
					?>
					<div class="col-xs-12">
						<div class="row mb10">
							<div class="col-xs-12 col-sm-3"><b>Airport Entry Fee:</b></div>
							<div class="col-xs-12 col-sm-9">&#x20b9;<?= $model->bkgInvoice->bkg_airport_entry_fee; ?></div>
						</div>
					</div>
					<?
					}
					if ($model->bkgInvoice->bkg_additional_charge > 0 || $model->bkgInvoice->bkg_driver_allowance_amount > 0)
					{
					?>
					<div class="col-xs-12">
						<div class="row mb10">
							<div class="col-xs-12 col-sm-3"><b>Additional Charge:</b></div>
							<div class="col-xs-12 col-sm-9">&#x20b9;<?= $model->bkgInvoice->bkg_additional_charge + $model->bkgInvoice->bkg_driver_allowance_amount; ?></div>
						</div>
					</div>
					<? } ?>
					<?
					if ($model->bkgInvoice->bkg_convenience_charge > 0)
					{
					?>
					<div class="col-xs-12">
						<div class="row mb10">
							<div class="col-xs-12 col-sm-3"><b>Collect on delivery (COD) fee:</b></div>
							<div class="col-xs-12 col-sm-9">&#x20b9;<?= $model->bkgInvoice->bkg_convenience_charge ?></h4></div>
						</div>
					</div>
					<? } ?>
					<?
					$staxrate	 = $model->bkgInvoice->getServiceTaxRate();
					$taxLabel	 = ($staxrate == 5) ? 'GST' : 'Service Tax ';
					?>
					<?
					if ($model->bkgInvoice->bkg_sgst > 0)
					{
					?>
					<div class="col-xs-12">
						<div class="row mb10">
							<div class="col-xs-12 col-sm-3"><b>SGST (@<?= Yii::app()->params['sgst'] ?>%):</b></div>
							<div class="col-xs-12 col-sm-9">&#x20b9;<?= ((Yii::app()->params['sgst'] / $staxrate) * $model->bkgInvoice->bkg_service_tax) | 0; ?></div>
						</div>
					</div>
					<? } ?>
					<?
					if ($model->bkgInvoice->bkg_cgst > 0)
					{
					?>
					<div class="col-xs-12">
						<div class="row mb10">
							<div class="col-xs-12 col-sm-3"><b>CGST (@<?= Yii::app()->params['cgst'] ?>%):</b></div>
							<div class="col-xs-12 col-sm-9">&#x20b9;<?= ((Yii::app()->params['cgst'] / $staxrate) * $model->bkgInvoice->bkg_service_tax) | 0; ?></div>
						</div>
					</div>
					<? } ?>
					<?
					if ($model->bkgInvoice->bkg_igst > 0)
					{
					?>
					<div class="col-xs-12">
						<div class="row mb10">
							<div class="col-xs-12 col-sm-3"><b>IGST (@<?= Yii::app()->params['igst'] ?>%):</b></div>
							<div class="col-xs-12 col-sm-9">&#x20b9;<?= ((Yii::app()->params['igst'] / $staxrate) * $model->bkgInvoice->bkg_service_tax) | 0; ?></div>
						</div>
					</div>
					<? } ?>
					<?
					if ($staxrate != 5)
					{
					?>
					<div class="col-xs-12">
						<div class="row mb10">
							<div class="col-xs-12 col-sm-3"><b><?= $taxLabel ?>:</b></div>
							<div class="col-xs-12 col-sm-9">&#x20b9;<?= $model->bkgInvoice->bkg_service_tax; ?></div>
						</div>
					</div>
					<?php } if ($model->bkgInvoice->bkg_state_tax > 0) { ?>
                    <div class="col-xs-12">
                                <div class="row mb10">
                                        <span class="col-xs-6 col-sm-3">Other Tax: <br/><i style="font-size:0.8em">(Including State Tax / Green Tax etc)</i> </span>
                                        <span class="col-xs-6 col-sm-9">&#x20b9;<?= $model->bkgInvoice->bkg_state_tax ?></span>
                                </div>
                    </div>
                            <?php }
                    ?>
					<div class="col-xs-12">
						<div class="row mb10">
							<div class="col-xs-12 col-sm-3"><b>Total Fare:</b></div>
							<div class="col-xs-12 col-sm-9"><h4 class="m0">&#x20b9;<b><?= $model->bkgInvoice->bkg_total_amount; ?></b></h4></div>
						</div>
					</div>
					<?
					if ($model->bkgInvoice->bkg_corporate_credit > 0)
					{
					?>
					<div class="col-xs-12">
						<div class="row mb10">
							<div class="col-xs-12 col-sm-3"><b>Corporate Credits used:</b></div>
							<div class="col-xs-12 col-sm-9"><h4 class="m0">&#x20b9;<?= $model->bkgInvoice->bkg_corporate_credit ?></h4></div>
						</div>
					</div>
					<? } ?>
					<?
					if ($model->bkgInvoice->bkg_advance_amount > 0 || $model->bkgInvoice->bkg_credits_used > 0)
					{
					?>

					<?
					if ($model->bkgInvoice->bkg_credits_used > 0)
					{
					?>
					<div class="col-xs-12">
						<div class="row mb10">
							<div class="col-xs-12 col-sm-3"><b>Credits used:</b></div>
							<div class="col-xs-12 col-sm-9"><h4 class="m0">&#x20b9;<?= $model->bkgInvoice->bkg_credits_used ?></h4></div>
						</div>
					</div>
					<? } ?>



					<?
					}

					if ($model->bkgInvoice->bkg_due_amount >= 0)
					{
					?>
					<div class="col-xs-12 blue2 white-color">
						<div class="row">
							<div class="col-xs-12 col-sm-3"><b>Amount Due:</b></div>
							<div class="col-xs-12 col-sm-9">
								<h4 class="m0">
									&#x20b9;<b><?= round($model->bkgInvoice->bkg_due_amount); ?></b>
								</h4>
							</div>
						</div>
					</div>
					<?
					}
					?>
				</div>
				<div class="col-xs-12 mt10 text-center">
					<div class="col-xs-12">
						<div class="col-xs-12 col-sm-12"><b>Thank you for choosing aaocab!</b></div>
					</div>
				</div>
			</div>
		</div>
		<div class="thumbnail p10 border-radius">
			<div class="caption pl0 pr0">
				<ul class="pl20">
					<li>Any changes to itinerary needs to be documented into the booking. Addition of pickup/drop points or way points or sightseeing may cause change in fare.</li>
					<li>15% cancellation fees for cancellation < 24hours</li>
					<li>For additional luggage, request a cab with carrier (availability not guaranteed )</li>
					<li>All parking charges are to be borne by the customer</li>
					<li>Toll, state taxes extra unless explicitly stated as included on itinerary.</li>
				</ul>
			</div>
		</div>
	</div>
	<?
	if ($model->bkg_status == 2 && $model->bookingRoutes[0]->brt_from_pincode == '' && $showAdditional)
	{
	?>
	<div class="col-xs-12 col-md-4">
		<div class="panel panel-default">
			<div class="panel-body">
<div class="row">
				<div class="col-xs-12 mb20">  
					<b>Provide additional information</b>
				</div>
				<?php
				$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
					'id'					 => 'booking-form121', 'enableClientValidation' => true,
					'clientOptions'			 => array(
						'validateOnSubmit'	 => true,
						'errorCssClass'		 => 'has-error',
						'afterValidate'		 => 'js:function(form,data,hasError){
						   if(!hasError){
                    $.ajax({
                        "type":"POST",
                        "dataType":"json",
                        "url":"' . CHtml::normalizeUrl(Yii::app()->request->url) . '",
							"data":form.serialize(),
							"success":function(data1){							
								if(data1.success){
								location.href="' . CHtml::normalizeUrl(Yii::app()->createUrl('agent/booking/summary')) . '?id="+data1.id+"&hash="+data1.hash;
								}
								else{								
								settings=form.data(\'settings\');
								data2 = data1.error;
								$.each (settings.attributes, function (i) {
								$.fn.yiiactiveform.updateInput (settings.attributes[i], data2, form);
								});
								$.fn.yiiactiveform.updateSummary(form, data2);
								}                         
							},
                            });
                        }
                    }'
					),
					'errorMessageCssClass'	 => 'help-block',
					'htmlOptions'			 => array(
						'class'		 => '', 'enctype'	 => 'multipart/form-data'
					),
				));
				/* @var $form TbActiveForm */
				?>
				<?= $form->hiddenField($model, 'bkg_id', ['id' => 'bkg_id4']); ?>
				<?=
				$form->hiddenField($model, 'hash', ['id' => 'hash4', 'class' => 'clsHash', 'value' => $hash]);
				?>
				<div id="error_div" style="display: none" class="alert alert-block alert-danger"></div>
				<div class="col-xs-12 special_request">
					<h3 class="mb10 mt0 font-18 weight600">Additional Details</h3>
					<?= $form->errorSummary($model); ?>
					<?= CHtml::errorSummary($model); ?>
					<div class="row">
						<div class="form-group">
							<label for="inputEmail" class="control-label col-xs-12 col-sm-5">Customer Type</label>
							<div class="col-xs-12">
								<?=
								$form->radioButtonListGroup($model->bkgAddInfo, 'bkg_user_trip_type', array(
									'label'			 => '', 'widgetOptions'	 => array(
										'data' => Booking::model()->userTripList
									), 'inline'		 => true,));
								?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="form-group">
							<label for="inputEmail" class="control-label col-xs-12">Send booking confirmations by</label>
							<div class="col-xs-12">
								<label class="checkbox-inline pt0 pl20 pr30">
									<?= $form->checkboxGroup($model->bkgPref, 'bkg_send_email', ['label' => 'Email']) ?>
								</label>
								<label class="checkbox-inline pt0 ">
									<?= $form->checkboxGroup($model->bkgPref, 'bkg_send_sms', ['label' => 'Phone']) ?>
								</label>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="form-group">
							<label for="inputEmail" class="control-label col-xs-5">Number of Passengers</label>
							<div class="col-xs-7">
								<?= $form->numberFieldGroup($model->bkgAddInfo, 'bkg_no_person', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Number of Passengers", 'min' => 1, 'max' => $bdata['vht_capacity']]), 'groupOptions' => [])) ?>                      
							</div>
						</div>
					</div>
					<div class="row">
						<div class="form-group">
							<label for="inputEmail" class="control-label col-xs-5"><?= $model->getAttributeLabel('bkg_num_large_bag') ?></label>
							<div class="col-xs-7">
								<?= $form->numberFieldGroup($model->bkgAddInfo, 'bkg_num_large_bag', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Number of large suitcases", 'min' => 0, 'max' => $bdata['vht_big_bag_capacity']]), 'groupOptions' => [])) ?>                      
							</div>
						</div>
					</div>
					<div class="row">
						<div class="form-group">
							<label for="inputEmail" class="control-label col-xs-5"><?= $model->getAttributeLabel('bkg_num_small_bag') ?></label>
							<div class="col-xs-7">
								<?= $form->numberFieldGroup($model->bkgAddInfo, 'bkg_num_small_bag', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Number of small bags", 'min' => 0, 'max' => $bdata['vht_bag_capacity']]), 'groupOptions' => [])) ?>                      
							</div>	
						</div>
					</div>
					<div class="row">
						<div class="form-group">
							<label for="inputEmail" class="control-label col-xs-5">How did you hear about Gozo cabs? </label>
							<div class="col-xs-7">
								<?php
								$datainfo	 = VehicleTypes::model()->getJSON($infosource);
								$this->widget('booster.widgets.TbSelect2', array(
									'model'			 => $model->bkgAddInfo,
									'attribute'		 => 'bkg_info_source',
									'val'			 => "'" . $model->bkgAddInfo->bkg_info_source . "'",
									'asDropDownList' => FALSE,
									'options'		 => array('data' => new CJavaScriptExpression($datainfo)),
									'htmlOptions'	 => array('style' => 'width:100%;margin-bottom:10px', 'placeholder' => 'Select Infosource ')
								));
								?>
							</div>
						</div>
					</div>
					<? $sourceDescShow	 = ($model->bkgAddInfo->bkg_info_source == 'Friend' || $model->bkgAddInfo->bkg_info_source == 'Other Media') ? '' : 'hide'; ?>
					<div class="row">
						<div class="form-group <?= $sourceDescShow ?> " id="source_desc_show">
							<label for="inputEmail" class="control-label col-xs-5">&nbsp;</label>
							<div class="col-xs-7">
								<?= $form->textFieldGroup($model->bkgAddInfo, 'bkg_info_source_desc', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => ""]),)) ?>                      
							</div>
						</div>
					</div>
					<h3 class="font-18 weight600">&nbsp;<br>Journey Details: </h3>
					<?
					$j				 = 0;
					$cntRt			 = sizeof($model->bookingRoutes);
					foreach ($model->bookingRoutes as $key => $brtRoute)
					{
					if ($j == 0)
					{
					?>       
					<div class="row">
						<div class = "form-group mb15">
							<label for="pickup_address" class="control-label col-xs-12 col-sm-5 pt10">Pickup Pincode for <?= $brtRoute->brtFromCity->cty_name ?></label>
							<div class="col-xs-12 col-sm-7">
								<?= $form->numberFieldGroup($brtRoute, "[$brtRoute->brt_id]brt_from_pincode", array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Pincode (Optional)"]), 'groupOptions' => ['class' => ''])) ?>
							</div>
						</div>
					</div>
					<?
					}
					$j++;
					$opt = (($key + 1) == $cntRt) ? 'Required' : 'Optional';
					?>
					<div class = "row">
						<div class = "form-group mb15">
							<label for="pickup_address" class="control-label col-xs-12 col-sm-5 pt10">Drop Pincode for <?= $brtRoute->brtToCity->cty_name ?></label>
							<div class="col-xs-12 col-sm-7">
								<?= $form->numberFieldGroup($brtRoute, "[$brtRoute->brt_id]brt_to_pincode", array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Pincode (Optional)"]))) ?>
							</div>
						</div>
					</div>
					<?
					}
					?>
				</div>

				<div class="col-xs-12 special_request">
					<h3 class="mb0 font-18 weight600">&nbsp;Special Requests</h3>
					<div class="col-xs-12 ml10">
						<?= $form->checkboxGroup($model->bkgAddInfo, 'bkg_spl_req_senior_citizen_trvl', []) ?>
						<?= $form->checkboxGroup($model->bkgAddInfo, 'bkg_spl_req_kids_trvl', []) ?>
						<?= $form->checkboxGroup($model->bkgAddInfo, 'bkg_spl_req_woman_trvl', []) ?>
						<?
						$scvVctId = SvcClassVhcCat::model()->getCatIdBySvcid($model->bkg_vehicle_type_id);
						if ($scvVctId != VehicleCategory::SEDAN_ECONOMIC)
						{
						echo $form->checkboxGroup($model->bkgAddInfo, 'bkg_spl_req_carrier', []);
						}
						?>
						<?= $form->checkboxGroup($model->bkgAddInfo, 'bkg_spl_req_driver_hindi_speaking', []) ?>
						<?= $form->checkboxGroup($model->bkgAddInfo, 'bkg_spl_req_driver_english_speaking', []) ?>
						<?= $form->checkboxGroup($model, 'bkg_chk_others', ['label' => 'Others']) ?>
						<div id="othreq" style="display: <?= $otherExist ?>">
							<?= $form->textAreaGroup($model->bkgAddInfo, 'bkg_spl_req_other', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Other Requests"]))) ?>  
						</div>
					</div> 
					<div class="col-xs-12">
						<?= $form->errorSummary($model) ?>
					</div>
				</div>

				<div class="col-xs-12">
					<div class="text-center mt10">
						<?= CHtml::submitButton('Submit', array('class' => 'btn btn-primary btn-lg pl40 pr40')); ?>
					</div>
				</div>
</div>
				<?php $this->endWidget(); ?>
			</div>
		</div>
	</div>
	<?php }
	?>

	<?
	if (APPLICATION_ENV == 'production')
	{
	?>
	<!-- Google Code for Confirm Lead Conversion Page -->
	<script type="text/javascript">
		/* <![CDATA[ */
		var google_conversion_id = 937550432;
		var google_conversion_language = "en";
		var google_conversion_format = "3";
		var google_conversion_color = "ffffff";
		var google_conversion_label = "J39FCNvt1WYQ4MSHvwM";
		var google_remarketing_only = false;
		/* ]]> */
	</script>
	<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
	</script>
	<noscript>
	<div style="display:inline;">
		<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/937550432/?label=J39FCNvt1WYQ4MSHvwM&amp;guid=ON&amp;script=0"/>
	</div>
	</noscript>
	<?
	}
	?>
	<script type="text/javascript">
		$(document).ready(function () {
        $("#BookingAddInfo_bkg_info_source").change(function () {
		var infosource = $("#BookingAddInfo_bkg_info_source").val();
		extraAdditionalInfo(infosource);
        });
		});
		function extraAdditionalInfo(infosource)
		{
        $("#source_desc_show").addClass('hide');
        if (infosource == 'Friend') {
		$("#source_desc_show").removeClass('hide');
		$("#agent_show").addClass('hide');
		$("#BookingAddInfo_bkg_info_source_desc").attr('placeholder', "Friend's email please");
        } else if (infosource == 'Other') {
		$("#source_desc_show").removeClass('hide');
		$("#agent_show").addClass('hide');
		$("#BookingAddInfo_bkg_info_source_desc").attr('placeholder', "");
        }
		}
		$('#<?= CHtml::activeId($model, "bkg_chk_others") ?>').change(function () {
        if ($('#<?= CHtml::activeId($model, "bkg_chk_others") ?>').is(':checked'))
        {
		$("#othreq").show();
        } else {
		$("#othreq").hide();
        }
		});
		$('form').on('focus', 'input[type=number]', function (e) {
        $(this).on('mousewheel.disableScroll', function (e) {
		e.preventDefault()
        })
        $(this).on("keydown", function (event) {
		if (event.keyCode === 38 || event.keyCode === 40) {
		event.preventDefault();
		}
        });
		});
		$('form').on('blur', 'input[type=number]', function (e) {
        $(this).off('mousewheel.disableScroll');
        $(this).off('keydown');
		});
	</script>
</div>