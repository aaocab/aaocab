<?php
$tripType		 = trim($params['tripType']);
$distanceData	 = Quotation::model()->calculateDistance($tripType, json_decode($quotationData));
$days			 = Quotation::model()->getTravelDays($distanceData['fromDate'], $distanceData['toDate']);
?>
<div class="row">
    <div class="col-xs-12 book-panel2">
        <div class="container p0 mt20">
            <div class="col-xs-12">
				<?php
				$form			 = $this->beginWidget('booster.widgets.TbActiveForm', array(
					'id'					 => 'quotation2-form',
					'action'				 => '',
					'enableClientValidation' => true,
					'clientOptions'			 => array(
						'validateOnSubmit'	 => true,
						'errorCssClass'		 => 'has-error',
						'afterValidate'		 => 'js:function(form,data,hasError){
                            if(!hasError){
                                $.ajax({
                                "type":"POST",
                                "url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('admin/quotation/step2')) . '",
                                "data":form.serialize(),
                                    "dataType": "json",
                                    "success":function(data1){
                                        alert(data1);
                                        if(data1.success){
                                            
                                         }else{
                                            var errors = data1.errors;
                                            settings=form.data(\'settings\');
                                            $.each (settings.attributes, function (i) {
                                              $.fn.yiiactiveform.updateInput (settings.attributes[i], errors, form);
                                            });
                                            $.fn.yiiactiveform.updateSummary(form, errors);
                                         }
                                    },
                                });
                                }
                            }'
					),
					'enableAjaxValidation'	 => false,
					'errorMessageCssClass'	 => 'help-block',
					'htmlOptions'			 => array(
						'class'		 => 'form-horizontal', 'enctype'	 => 'multipart/form-data',
					),
				));
				?>
                <div class="row">
                    <div class="col-xs-12 col-sm-offset-7 col-sm-3">
                        <div class="form-group">
							<?= $form->textFieldGroup($model, 'qot_email_txt', array('label' => "", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Email')))) ?>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-2">
                        <button type="submit" name="btnSave" id="btnSave" value="email" class="btn btn-primary">Email</button>
                        <input type="submit" name="btnSave" id="btnSave" value="save" class="btn btn-success" ></input>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-4">
                        <div class="row">
                            <div class="col-xs-12 mb20">Customer Name:&nbsp;<b><?= $params['name'] ?></b></div>
                            <div class="col-xs-12 mb20">Total Kms:&nbsp;<b><?= $distanceData['calDistance'] ?></b></div>
                            <div class="col-xs-12 mb20">Start Address:&nbsp;<b><?= $distanceData['pickupPoint']; ?></b></div>
                            <div class="col-xs-12 mb20">End Address:&nbsp;<b><?= $distanceData['dropPoint']; ?></b></div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-4">
                        <div class="row">
                            <div class="col-xs-12 mb20">Email:&nbsp;<b><?= $params['email'] ?></b></div>
                            <div class="col-xs-12 mb20">Total # of days:&nbsp;<b><?= $days; ?></b></div>
                            <div class="col-xs-12 mb20">Pick Date & Time:&nbsp;<b><?= $distanceData['fromDate']; ?></b></div>
                            <div class="col-xs-12 mb20">Drop Date & Time:&nbsp;<b><?= $distanceData['toDate']; ?></b></div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-4">
                        <div class="row">
                            <div class="col-xs-12">Phone:&nbsp;<b><?= $params['phone'] ?></b></div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <h5 class="mb0 mt20">Special needs</h5>
                                <div class="col-xs-12 p0">
                                    <label class="checkbox-inline">
										<?=
										$form->checkboxListGroup($model, 'qot_special_needs', array('label'			 => '',
											'widgetOptions'	 => array('data' => Quotation::model()->specialNeeds)))
										?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="profile-right-panel p20">
                        <div class="row">
							<?php
							if (count($data) > 0)
							{
								$ctr = 1;
								foreach ($data as $cab)
								{
									?>
									<table class="table table-bordered" width="100%" border="0" cellspacing="2" cellpadding="2">
										<tr>
											<td style="text-align: center;">Car Type</td>
											<td style="text-align: center;">Total Trip Amount</td>
											<td style="text-align: center;">Total Kms</td>
											<td style="text-align: center;">EST Toll(included)</td>
											<td style="text-align: center;">EST Taxes(included)</td>
											<td style="text-align: center;">#Pick/Drop(included)</td>
											<td style="text-align: center;">Addl KM Rate</td>
											<td style="text-align: center;">Comments</td>
										</tr>
										<tr>
											<td style="text-align: center;"><?= $cab['cab']; ?></td>
											<td style="text-align: center;"><?= '<i class="fa fa-inr"></i>' . $cab['total_amt']; ?></td>
											<td style="text-align: center;"><?= $cab['total_km']; ?></td>
											<td style="text-align: center;">&nbsp;</td>
											<td style="text-align: center;"><?= '<i class="fa fa-inr"></i>' . $cab['est_tax']; ?></td>
											<td style="text-align: center;">&nbsp;</td>
											<td style="text-align: center;"><?= '<i class="fa fa-inr"></i>' . $cab['km_rate']; ?></td>
											<td></td>
										</tr>
									</table>
									<?php
									$ctr = ($ctr + 1);
								}
							}
							?>
                        </div>
                    </div>
                </div>
				<?php echo CHtml::hiddenField('quotation_data', $quotationData); ?>
				<?php echo CHtml::hiddenField('qot_trip_type', $params['tripType']); ?>
				<?php echo CHtml::hiddenField('qot_car_type', $params['carType']); ?>
				<?php echo CHtml::hiddenField('qot_special_needs', $params['specialNeeds']); ?>
				<?php echo CHtml::hiddenField('qot_name', $params['name']); ?>
				<?php echo CHtml::hiddenField('qot_email', $params['email']); ?>
				<?php echo CHtml::hiddenField('qot_phone', $params['phone']); ?>
				<?php echo CHtml::hiddenField('qot_passenger', $params['passenger']); ?> 
				<?php echo CHtml::hiddenField('qot_luggage', $params['luggage']); ?>                
				<?php echo CHtml::hiddenField('qot_start_date', ''); ?> 
				<?php echo CHtml::hiddenField('qot_end_date', ''); ?> 
				<?php $this->endWidget(); ?>
            </div>
        </div>
    </div>
</div>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDhybncyDc1ddM2qzn453XqYW8ZQDm7RW8&libraries=places"
async defer></script>