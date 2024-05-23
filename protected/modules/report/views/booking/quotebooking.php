<div class="row">
    <div class="col-xs-12 col-md-11 col-lg-11" style="float: none; margin: auto">
		<?php
		$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
			'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
			'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
			'openOnFocus'		 => true, 'preload'			 => false,
			'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
			'addPrecedence'		 => false,];
		?>
		<?php
		$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'quoteBooking-form', 'enableClientValidation' => TRUE,
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
				'class' => 'form-horizontal',
			),
		));
		/* @var $form TbActiveForm */
		/* @var $form TbActiveForm */
		?>
		<?= CHtml::errorSummary($model); ?> 
        <div class="row">
            <div class="col-xs-12">
                <div class="panel panel-default panel-border">
                    <div class="panel-body">
                        <div class="row mb15">
                            <div class="col-xs-12 col-sm-4">
                                <label>From City *</label>
								<?php
								$this->widget('ext.yii-selectize.YiiSelectize', array(
									'model'				 => $model,
									'attribute'			 => 'bkg_from_city_id',
									'useWithBootstrap'	 => true,
									"placeholder"		 => "Select Source City",
									'fullWidth'			 => false,
									'options'			 => array('allowClear' => true),
									'htmlOptions'		 => array('width'	 => '100%',
										'id'	 => 'Booking_bkg_from_city_id'
									),
									'defaultOptions'	 => $selectizeOptions + array(
								'onInitialize'	 => "js:function(){
                                            populateSource(this, '{$model->bkg_from_city_id}');
                                                }",
								'load'			 => "js:function(query, callback){
                                            loadSource(query, callback);
                                            }",
								'render'		 => "js:{
                                            option: function(item, escape){
                                            return '<div><span class=\"\"><i class=\"fa fa-map-marker mr5\"></i>' + escape(item.text) +'</span></div>';
                                            },
                                            option_create: function(data, escape){
                                            return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
                                            }
                                            }",
									),
								));
								?>
                            </div>
                            <div class="col-xs-12 col-sm-4">
                                <label>To City *</label>
								<?php
								$this->widget('ext.yii-selectize.YiiSelectize', array(
									'model'				 => $model,
									'attribute'			 => 'bkg_to_city_id',
									'useWithBootstrap'	 => true,
									"placeholder"		 => "Select Destination City",
									'fullWidth'			 => false,
									'options'			 => array('allowClear' => true),
									'htmlOptions'		 => array('width'	 => '100%',
										'id'	 => 'Booking_bkg_to_city_id'
									),
									'defaultOptions'	 => $selectizeOptions + array(
								'onInitialize'	 => "js:function(){
                                            populateSource(this, '{$model->bkg_to_city_id}');
                                                }",
								'load'			 => "js:function(query, callback){
                                            loadSource(query, callback);
                                            }",
								'render'		 => "js:{
                                            option: function(item, escape){
                                            return '<div><span class=\"\"><i class=\"fa fa-map-marker mr5\"></i>' + escape(item.text) +'</span></div>';
                                            },
                                            option_create: function(data, escape){
                                            return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
                                            }
                                            }",
									),
								));
								?>	
                            </div>
                            <div class="col-xs-12 col-sm-4">
                                <label>Pickup Date *</label>
								<?php $strpickdate		 = ($model->bkg_pickup_date == '') ? date('Y-m-d H:i:s') : $model->bkg_pickup_date; ?>
								<?=
								$form->datePickerGroup($model, 'bkg_pickup_date_date', array('label'			 => '',
									'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'Pickup Date', 'value' => DateTimeFormat::DateTimeToDatePicker($strpickdate))), 'prepend'		 => '<i class="fa fa-calendar"></i>'));
								?>
                            </div>
                            <div class="col-xs-12 col-sm-4">
                                <label>Pickup Time *</label>
								<?php
								echo $form->timePickerGroup($model, 'bkg_pickup_date_time', array('label'			 => '',
									'widgetOptions'	 => array('id' => CHtml::activeId($model, "bkg_pickup_date_time"), 'options' => array('autoclose' => true), 'htmlOptions' => array('placeholder' => 'Pickup Time', 'value' => date('h:i A', strtotime($strpickdate))))));
								?> 
                            </div>
                            <div class="col-xs-12 col-sm-4">
                                <label>Vehicle Type *</label>
								<?php
								$this->widget('booster.widgets.TbSelect2', array(
									'model'			 => $model,
									'attribute'		 => 'bkg_vehicle_type_id',
									'val'			 => $model->bkg_vehicle_type_id,
									'asDropDownList' => FALSE,
									'options'		 => array(
										'data'		 => new CJavaScriptExpression(SvcClassVhcCat::model()->getJSON(SvcClassVhcCat::model()->getVctSvcList())),
										'allowClear' => true
									),
									'htmlOptions'	 => array('required' => true, 'class' => 'p0', 'style' => 'width: 100%', 'placeholder' => 'Select Vehicle Type')
								));
								?>
                            </div>
                            <div class="col-xs-12 col-sm-4">
                                <label>Booking Type *</label>
								<?php
								$bookingType		 = Booking::model()->getBookingType();
								$dataBookType		 = VehicleTypes::model()->getJSON($bookingType);
								$this->widget('booster.widgets.TbSelect2', array(
									'model'			 => $model,
									'attribute'		 => 'bkg_booking_type',
									'val'			 => $model->bkg_booking_type,
									'asDropDownList' => FALSE,
									'options'		 => array('data' => new CJavaScriptExpression($dataBookType)),
									'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Booking Type')
								));
								?>
                            </div>

                        </div>



                        <!--  -->
                        <div class="row">
                            <div class="col-xs-12 text-center pb10">
                                <input type="submit" value="Submit" name="yt0" class="btn btn-primary pl30 pr30">
                            </div>
                        </div>
                        <div class="row ">          
                            <div class="col-xs-12 col-sm-12" >
								<?php
								if ($model != '')
								{
									?>
									<table class="table">
										<thead>
											<tr>
												<th scope="col">Vehicle Type</th>
												<th scope="col">Trip Distance(KM)</th>
												<th scope="col">Regular Base Amount</th>
												<th scope="col">Rock Base Amount</th>
												<th scope="col">Base Amount</th>
												<th scope="col">Total Amount</th>
												<th scope="col">Vendor  Amount</th>
												<th scope="col">Apply Surge</th>
												<th scope="col">Surge Factor Used</th>  
												<th scope="col">PPE Path</th>  
												<th scope="col">Manual</th>
												<th scope="col">DZPP</th>
												<th scope="col">DDBP</th>
												<th scope="col">DURP</th>
												<th scope="col">DEBP</th>
												<th scope="col">DDBP(V2)</th>
												<th scope="col">DDSBP</th>
												<th scope="col">DTBP</th>
											</tr>
										</thead>
										<tbody>
											<?php
											foreach ($quote as $quotesVal)
											{
												$svcDetails	 = SvcClassVhcCat::getVctSvcList($returnType	 = "object", $svccId		 = 0, $vctId		 = 0, $quotesVal->skuId);
												?>
												<tr>
													<td scope="row"><?php echo $svcDetails->scv_label; ?></td>
													<td><?php echo $quotesVal->routeDistance->tripDistance; ?></td>
													<td><?php echo $quotesVal->routeRates->regularBaseAmount; ?></td>
													<td><?php echo $quotesVal->routeRates->rockBaseAmount; ?></td>
													<td><?php echo $quotesVal->routeRates->baseAmount; ?></td>
													<td><?php echo $quotesVal->routeRates->totalAmount; ?></td>
													<td><?php echo $quotesVal->routeRates->vendorAmount; ?></td>
													<td><?php echo $quotesVal->routeRates->applySurge; ?></td>
													<td><?php echo BookingPriceFactor::surgeFactorList[$quotesVal->routeRates->surgeFactorUsed]; ?></td>
													<td><?php echo (json_decode($quotesVal->routeRates->additional_param)->surgeFactorDescription); ?></td>
													<td>
														RockBase Amount: <?php echo $quotesVal->routeRates->srgManual->rockBaseAmount; ?><br>
														Surge Amount: <?php echo round($quotesVal->routeRates->srgManual->surgeAmount, 2); ?><br>
														Is Applied Amount: <?php echo $quotesVal->routeRates->srgManual->isApplied; ?><br>
														Surge Factor Amount: <?php echo $quotesVal->routeRates->srgManual->factor; ?>
													</td>
													<td>
														RockBase Amount: <?php echo $quotesVal->routeRates->srgDZPP->rockBaseAmount; ?><br>
														Surge Amount: <?php echo round($quotesVal->routeRates->srgDZPP->surgeAmount, 2); ?><br>
														Is Applied Amount: <?php echo $quotesVal->routeRates->srgDZPP->isApplied; ?><br>
														Surge Factor Amount: <?php echo $quotesVal->routeRates->srgDZPP->factor; ?>
													</td>
													<td>
														RockBase Amount: <?php echo $quotesVal->routeRates->srgDDBP->rockBaseAmount; ?><br>
														Surge Amount: <?php echo round($quotesVal->routeRates->srgDDBP->surgeAmount, 2); ?><br>
														Is Applied Amount: <?php echo $quotesVal->routeRates->srgDDBP->isApplied; ?><br>
														Surge Factor Amount: <?php echo $quotesVal->routeRates->srgDDBP->factor; ?>
													</td>
													<td>
														RockBase Amount: <?php echo $quotesVal->routeRates->srgDURP->rockBaseAmount; ?><br>
														Surge Amount: <?php echo round($quotesVal->routeRates->srgDURP->surgeAmount, 2); ?><br>
														Is Applied Amount: <?php echo $quotesVal->routeRates->srgDURP->isApplied; ?><br>
														Surge Factor Amount: <?php echo $quotesVal->routeRates->srgDURP->factor; ?>
													</td>
													<td>
														RockBase Amount: <?php echo $quotesVal->routeRates->srgDEBP->rockBaseAmount; ?><br>
														Surge Amount: <?php echo round($quotesVal->routeRates->srgDEBP->surgeAmount, 2); ?><br>
														Is Applied Amount: <?php echo $quotesVal->routeRates->srgDEBP->isApplied; ?><br>
														Surge Factor Amount: <?php echo $quotesVal->routeRates->srgDEBP->factor; ?>
													</td>
													<td>
														RockBase Amount: <?php echo $quotesVal->routeRates->srgDDBPV2->rockBaseAmount; ?><br>
														Surge Amount: <?php echo round($quotesVal->routeRates->srgDDBPV2->surgeAmount, 2); ?><br>
														Is Applied Amount: <?php echo $quotesVal->routeRates->srgDDBPV2->isApplied; ?><br>
														Surge Factor Amount: <?php echo $quotesVal->routeRates->srgDDBPV2->factor; ?>
													</td>
													<td>
														RockBase Amount: <?php echo $quotesVal->routeRates->srgDDSBP->rockBaseAmount; ?><br>
														Surge Amount: <?php echo round($quotesVal->routeRates->srgDDSBP->surgeAmount, 2); ?><br>
														Is Applied Amount: <?php echo $quotesVal->routeRates->srgDDSBP->isApplied; ?><br>
														Surge Factor Amount: <?php echo $quotesVal->routeRates->srgDDSBP->factor; ?>
													</td>
													<td>
														RockBase Amount: <?php echo $quotesVal->routeRates->srgDTBP->rockBaseAmount; ?><br>
														Surge Amount: <?php echo round($quotesVal->routeRates->srgDTBP->surgeAmount, 2); ?><br>
														Is Applied Amount: <?php echo $quotesVal->routeRates->srgDTBP->isApplied; ?><br>
														Surge Factor Amount: <?php echo $quotesVal->routeRates->srgDTBP->factor; ?>
													</td>
												</tr>
												<?php
											}
											?>
										</tbody>
									</table>
									<?php
								}
								?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<?php $this->endWidget(); ?>
</div>
</div>

<script>
    function populateSource(obj, cityId)
    {

        obj.load(function (callback)
        {
            var obj = this;
            if ($sourceList == null)
            {
                xhr = $.ajax({
                    url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery', ['apshow' => 1, 'city' => ''])) ?>' + cityId,
                    dataType: 'json',
                    success: function (results)
                    {
                        $sourceList = results;
                        obj.enable();
                        callback($sourceList);
                        obj.setValue(cityId);
                    },
                    error: function ()
                    {
                        callback();
                    }
                });
            } else
            {
                obj.enable();
                callback($sourceList);
                obj.setValue(cityId);
            }
        });
    }
    function loadSource(query, callback)
    {
        //	if (!query.length) return callback();
        $.ajax({
            url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery')) ?>?apshow=1&q=' + encodeURIComponent(query),
            type: 'GET',
            dataType: 'json',
            global: false,
            error: function ()
            {
                callback();
            },
            success: function (res)
            {
                callback(res);
            }
        });
    }
</script>