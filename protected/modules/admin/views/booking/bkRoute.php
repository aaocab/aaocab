<?php
$selectizeOptions = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
//	$cartype			 = VehicleTypes::model()->getParentVehicleTypes(1);
//	unset($cartype[11]);

$additionalAddressInfo	 = "Building No./ Society Name";
$autocompleteFrom		 = 'txtpl';
$autocompleteTo			 = 'txtpl';
$locReadonly			 = ['readonly' => 'readonly'];
$j						 = 0;
$prfModel                = new BookingPref();
?>
<?php
/* @var $form TbActiveForm */
$form					 = $this->beginWidget('booster.widgets.TbActiveForm', array(
	'id'					 => 'bookingRouteForm', 'enableClientValidation' => true,
	'clientOptions'			 => array(
		'validateOnSubmit'	 => true,
		'errorCssClass'		 => 'has-error',
		'afterValidate'		 => 'js:function(form,data,hasError){
                if(!hasError){
                    if(!admBooking.validateRoute())
					{
                        return false;                         
					}
                    
					 agentId = $("#bkg_agent_id option:selected").val();
                    $.ajax({
                    "type":"POST",
                    "dataType":"HTML",
                    async: false,
                    "url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('admin/booking/route')) . '",
                    "data":form.serialize()+"&agentId="+agentId,
                    "beforeSend": function () {
                        ajaxindicatorstart("");
                    },
                    "complete": function () {                     
                        ajaxindicatorstop();
                    },
                    "success":function(data1){
						$("#bkErrors").addClass("hide");
						if(data1.indexOf("errors") != -1)
						{
							$("#bkErrors").removeClass("hide");
							data1 = JSON.parse(data1);
							var errors = data1.errors;
							$.each(errors, function(k,v){
								$("#bkErrors ul").append("<li>" + v + ". (<a href=\'javascript:void(0)\' onclick=\'admBooking.focusErrorElm(\"#brt_location1\")\'>Go there</a>)</li>");
								$(document).scrollTop(0);
							});
						}
						else
						{
							$(".btn-route").removeClass("btn-info");
							$(".btn-route").addClass("disabled");
							$("#bookingRoute").find("input,textarea").attr("disabled",true);
							$("#bookingRoute").find(".route-focus").addClass("disabled");
							$("#payment").html(data1);
							$("#payment").removeClass("hide");
							$(".btn-editMulticity").addClass("hide");
							$(".btn-editRoute").removeClass("hide");
							$(document).scrollTop($("#payment").offset().top);
						}
                        },
                     error: function(xhr, status, error){
                      
                         }
                    });

                    }
                }'
	),
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// See class documentation of CActiveForm for details on this,
	// you need to use the performAjaxValidation()-method described there.
	'enableAjaxValidation'	 => false,
	'errorMessageCssClass'	 => 'help-block',
	'htmlOptions'			 => array(
		'onkeydown'	 => "return event.key != 'Enter';",
		'class'		 => '',
	),
		));
?>
<?= $form->hiddenField($model, 'bkg_from_city_id'); ?>
<?= $form->hiddenField($model, 'bkg_to_city_id'); ?>
<?= $form->hiddenField($model, 'routeProcessed'); ?>
<?= CHtml::hiddenField("jsonData_routeType", $data, ['id' => 'jsonData_routeType']) ?>
<?= $form->hiddenField($model, 'baseamount'); ?>
<?= $form->hiddenField($model, 'isGozonow'); ?>
<input type="hidden" name="multicityjsondata" class='box-multicityjson' value='<?= json_encode($model->preData); ?>'>
<input type="hidden" id="multicityAutoComData" name="multicityAutoComData">
<input type="hidden" id="multicityAutoComTot" name="multicityAutoComTot">
<div class="row">
	<div class="col-xs-12">
		<div class="panel panel-default panel-border">
			<span class="edit-block btn-editRoute hide"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span>
			<h3 class="pl15">Create Itinerary</h3>
			<div class="panel-body pt0">
                <div class="row" id='tripTablecreate' style="display: <?= ($model->preData != '') ? 'block' : 'none' ?>">
					<div class="col-xs-12 table-responsive" >
						<div class="float-none marginauto">
							<?php
							if (!$package)
							{
								?>
								<h3 class="mb10 text-uppercase">Trip Info  <button type="button" class="btn btn-info btn-editMulticity ml15"><i class="fa fa-edit"></i></button></h3>
							<? } ?>
							<table class="table-bordered113" border="1" CELLPADDING="10" width="100%" id="packagetb">

								<?php
								if ($package)
								{
									?>
									<thead>
									<th>From1</th>
									<th>To</th>
									<th>From Location</th>
									<th>To Location</th>
									<th>Pickup Date&Time</th>
									<th>Distance</th>
									<th>Duration</th>
									<th>No of Days</th>
									<th>No of Nights</th>
									</thead>
									<?
								}
								else
								{
									?>
									<thead>
									<th>From</th>
									<th>To</th>
									<th>Date</th>
									<th>Distance</th>
									<th>Duration</th>
									<th>Day</th>
									</thead>
								<? } ?>
								<?
								$diffdays	 = 0;
								$nightCount	 = 0;
								if ($model->preData != '')
								{
									$arrmulticitydata = $model->preData;
									foreach ($arrmulticitydata as $key => $value)
									{
										$nightCount = $nightCount + $value->nightcount;

										if ($key == 0)
										{
											$diffdays = 1;
										}
										else
										{
											$date1		 = new DateTime(date('Y-m-d', strtotime($arrmulticitydata[0]->date)));
											$date2		 = new DateTime(date('Y-m-d', strtotime($value->date)));
											$difference	 = $date1->diff($date2);
											$diffdays	 = ($difference->d + 1);
										}
										?>

										<?
										if ($package)
										{
											?>
											<tr class="packagerow" >
												<td id="fcitycreate<?= $key ?>"><b>11<?= $value->pickup_city_name ?></b></td>
												<td id="tcitycreate<?= $key ?>"><b><?= $value->drop_city_name ?> </b></td>
												<td id="fcitylocation<?= $key ?>"><?= $value->pickup_address; ?> </td>
												<td id="tcitylocation<?= $key ?>"><?= $value->drop_address; ?></td>
												<td id="fdatecreate<?= $key ?>"><?= DateTimeFormat::DateTimeToLocale($value->date) ?></td>
												<td id="fdistcreate<?= $key ?>"><?= $value->distance; ?> </td>
												<td id="fduracreate<?= $key ?>"><?= $value->duration; ?> </td>
												<td id="noOfDayCount<?= $key ?>"><? echo $value->daycount; ?> </td>
												<td id="noOfNightCount<?= $key ?>"><? echo $value->nightcount; ?> </td>
											</tr>
											<?
										}
										else
										{
											?>
											<tr class="multicitydetrow">
												<td id="fcitycreate<?= $key ?>"><b><?= $value->pickup_city_name ?></b></td>
												<td id="tcitycreate<?= $key ?>"><b><?= $value->drop_city_name ?> </b></td>
												<td id="fdatecreate<?= $key ?>"><?= $value->pickup_date . "<br>" . $value->pickup_time ?></td>
												<td id="fdistcreate<?= $key ?>"><?= $value->distance; ?> </td>
												<td id="fduracreate<?= $key ?>"><?= $value->duration; ?> </td>
												<td id="noOfDayscreate<?= $key ?>"><? echo $diffdays; ?> </td>
											</tr>

										<? } ?>
										<?
										$last_date = date('Y-m-d H:i:s', strtotime($value->date . '+ ' . $value->duration . ' minute'));
									}
								}
								?>

								<tr id='insertTripRowcreate'></tr>
							</table>
							<div class="mt10" id='show_return_date_time'></div>

							<?
							if ($model->preData != '')
							{
								if ($date1 != '')
								{
									$totdiff = $date1->diff(new DateTime(date('Y-m-d', strtotime($last_date))))->d + 1;
								}
								else
								{
									$totdiff = $diffdays;
								}
							}
							?>

							<?
							if (!$package)
							{
								?>
								<h4>Total days for the trip: <span class="blue-color"><span id="totdayscreate"><?= $totdiff ?></span> days</span></h4>
								<?
							}
							else
							{
								?>
								<h4>Total day night for the trip: <span class="blue-color"><span id="totdayscreate"><? echo $value->daycount; ?> Days and <? echo $nightCount; ?> Nights  </span> days</span></h4>
							<? } ?>
						</div>

						<div class="float-none marginauto" >
							<div class="col-xs-12 ">
								<div class="row "  id="address">
									<?php
									if ($model->preData != '')
									{
										$arrmulticitydata = $model->preData;

//													var_dump($arrmulticitydata);

										$j		 = 0;
										$cntRt	 = sizeof($arrmulticitydata);
										foreach ($arrmulticitydata as $key => $brtRoute)
										{

											if ($key == 0)
											{
												$locFrom = [];
												if ($brtRoute->pickup_cty_is_airport == 1 || $brtRoute->pickup_cty_is_poi == 1)
												{
													$locFrom = $locReadonly;
												}
												?>        
												<div class="col-xs-12 pb10"><div class="row "><div class="col-xs-12 col-sm-6 pl0 ">
															<label for="pickup_address" class="control-label text-left">Pickup location for <?= $brtRoute->pickup_city_name ?> :</label>
															<input type="hidden" id="ctyLat0" class="" value="<?= $brtRoute->pickup_cty_lat ?>">
															<input type="hidden" id="ctyLon0" class="" value="<?= $brtRoute->pickup_cty_long ?> ">
															<input type="hidden" id="ctyELat0" class="" value="<?= round($brtRoute->pickup_cty_ne_lat, 6) ?>">
															<input type="hidden" id="ctyWLat0" class="" value="<?= round($brtRoute->pickup_cty_sw_lat, 6) ?>">
															<input type="hidden" id="ctyELng0" class="" value="<?= round($brtRoute->pickup_cty_ne_long, 6) ?>">
															<input type="hidden" id="ctyWLng0" class="" value="<?= round($brtRoute->pickup_cty_sw_long, 6) ?>">
															<input type="hidden" id="ctyRad0" class="" value="<?= $brtRoute->pickup_cty_radius ?>">

															<input type="hidden" id="locLat0" name="BookingRoute[0][brt_from_latitude]" class="" value="<?= $brtRoute->pickup_loc_lat ?>">
															<input type="hidden" id="locLon0" name="BookingRoute[0][brt_from_longitude]" value="<?= $brtRoute->pickup_loc_long ?>">
															<input type="hidden" id="city_is_airport0" name="BookingRoute[0][brt_from_city_is_airport]" value="<?= $brtRoute->pickup_cty_is_airport ?>">
															<input type="hidden" id="city_is_poi0" name="BookingRoute[0][brt_from_city_is_poi]" value="<?= $brtRoute->pickup_cty_is_poi ?>">

														</div>
														<div class="col-xs-12 col-sm-6 mb0 pb0"><div class="form-group">
																<textarea id="brt_location0" class="form-control txtpl form-control route-focus" placeholder="Pickup Address  (Required)" name="BookingRoute[0][brt_from_location]" autocomplete="off"><?= $brtRoute->pickup_address ?></textarea>
																<div class="help-block error" id="BookingRoute_0_brt_from_location_em_" style="display:none"></div>
															</div></div>
													</div>
													<!--<div class="row ">
														<div class="col-xs-12 col-sm-6 pl0">
															<label for="buildinInfo0" class="control-label text-left"><?= $additionalAddressInfo ?>:</label>
														</div>
														<div class="col-xs-12 col-sm-6 mb0 pb0"><div class="form-group">
																<input id="brt_additional0" class="form-control form-control" placeholder="<?= $additionalAddressInfo ?>" name="BookingRoute[0][brt_additional_from_address]" type="text">
																<div class="help-block error" id="BookingRoute_0_brt_additional_from_address_em_" style="display:none">
																</div></div></div>

														</div>-->
												</div>
												<?
											}
											$key1	 = $key + 1;
											$j++;
											$opt	 = (($key + 1) == $cntRt) ? 'Required' : 'Optional';
											$optReq	 = (($key + 1) == $cntRt) ? ' *' : '';

											$locTo = [];
											if ($brtRoute->drop_cty_is_airport == 1 || $brtRoute->drop_cty_is_poi == 1)
											{
												$locTo = $locReadonly;
											}
											?>

											<div class="col-xs-12 pt10 pb20"><div class="row">
													<div class="col-xs-12 col-sm-6 pl0">
														<label for="pickup_address<?= $key1 ?>" class="control-label text-left">Drop Address for <?= $brtRoute->drop_city_name ?> :</label>
														<input type="hidden" id="ctyLat<?= $key1 ?>" value="<?= $brtRoute->drop_cty_lat ?>">
														<input type="hidden" id="ctyLon<?= $key1 ?>" value="<?= $brtRoute->drop_cty_long ?>">
														<input type="hidden" id="ctyELat<?= $key1 ?>" value="<?= $brtRoute->drop_cty_ne_lat ?>">
														<input type="hidden" id="ctyWLat<?= $key1 ?>" value="<?= $brtRoute->drop_cty_sw_lat ?>">
														<input type="hidden" id="ctyELng<?= $key1 ?>" value="<?= $brtRoute->drop_cty_ne_long ?>">
														<input type="hidden" id="ctyWLng<?= $key1 ?>" value="<?= $brtRoute->drop_cty_sw_long ?>">
														<input type="hidden" id="ctyRad<?= $key1 ?>" value="<?= $brtRoute->drop_cty_radius ?>">
														<input id="locLat<?= $key1 ?>" name="BookingRoute[<?= $key1 ?>][brt_to_latitude]"  class="locLatVal"  type="hidden" value="<?= $brtRoute->drop_loc_lat ?>">
														<input id="locLon<?= $key1 ?>" name="BookingRoute[<?= $key1 ?>][brt_to_longitude]"  class="locLonVal" type="hidden" value="<?= $brtRoute->drop_loc_long ?>">
														<input id="city_is_airport<?= $key1 ?>" name="BookingRoute[<?= $key1 ?>][brt_to_city_is_airport]" type="hidden"  value="<?= $brtRoute->drop_cty_is_airport ?>">
														<input id="city_is_poi<?= $key1 ?>" name="BookingRoute[<?= $key1 ?>][brt_to_city_is_poi]" type="hidden"  value="<?= $brtRoute->drop_cty_is_poi ?>">
													</div>
													<div class="col-xs-12 col-sm-6">
														<div class="form-group">
															<textarea id="brt_location<?= $key1 ?>" class="form-control txtpl form-control route-focus" placeholder="Drop Address  (Optional)" name="BookingRoute[<?= $key1 ?>][brt_to_location]" autocomplete="off"><?= $brtRoute->drop_address ?></textarea>
															<div class="help-block error" id="BookingRoute_<?= $key1 ?>_brt_to_location_em_" style="display:none"></div>
														</div></div>
												</div>
												<!--<div class="row"><div class="col-xs-12 col-sm-6 pl0">
													<label for="buildinInfo" class="control-label text-left"><?= $additionalAddressInfo ?>:</label>
												</div>
												<div class="col-xs-12 col-sm-6">
													<div class="form-group"><input id="brt_additional<?= $key1 ?>" class="form-control form-control" placeholder="<?= $additionalAddressInfo ?>" name="BookingRoute[<?= $key1 ?>][brt_additional_to_address]" type="text">
														<div class="help-block error" id="BookingRoute_<?= $key1 ?>_brt_additional_to_address_em_" style="display:none">
														</div></div></div></div>-->
											</div>
											<?
										}
									}
									?>
								</div>
							</div>
						</div>

					</div>
				</div>
				<?
				$showdiv = (in_array($model->bkg_booking_type, [1, 4, 9, 10, 11, 15])) ? "block" : "none";
				?>

				<div class="row" id="ctyinfo_bkg_type_1"  style="display: <?php echo $showdiv; ?>">
					<div class="col-sm-6 ">
						<div class="form-group cityinput">
							<label class="control-label" for="exampleInputName6"><?= (in_array($model->bkg_booking_type, [9, 10, 11])) ? "Select City" : "Source City" ?></label>
							<?php
							$this->widget('ext.yii-selectize.YiiSelectize', array(
								'model'				 => $model,
								'attribute'			 => 'bkg_from_city_id1',
								'useWithBootstrap'	 => true,
								"placeholder"		 => "Select Source City",
								'fullWidth'			 => false,
								'htmlOptions'		 => array('width'	 => '100%',
									'id'	 => 'Booking_bkg_from_city_id1',
									'class'	 => 'route-focus'
								),
								'defaultOptions'	 => $selectizeOptions + array(
							'onInitialize'	 => "js:function(){
										populateSource(this, '{$model->bkg_from_city_id}');
											}",
							'load'			 => "js:function(query, callback){
										loadSource(query, callback, '{$model->bkg_booking_type}');
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
							<span class="has-error"><? echo $form->error($model, 'bkg_from_city_id'); ?></span>
						</div>			
					</div>


					<div class="col-sm-6">
						<?php
						if (!in_array($model->bkg_booking_type, [9, 10, 11]))
						{
							?>
							<div class="form-group cityinput">
								<label class="control-label" for="exampleInputCompany6">Destination City</label>
								<?php
								$this->widget('ext.yii-selectize.YiiSelectize', array(
									'model'				 => $model,
									'attribute'			 => 'bkg_to_city_id1',
									'useWithBootstrap'	 => true,
									"placeholder"		 => "Select Destination City",
									'fullWidth'			 => false,
									'htmlOptions'		 => array('width'	 => '100%',
										'id'	 => 'Booking_bkg_to_city_id1',
										'class'	 => 'route-focus'
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
								<span class="has-error"><? echo $form->error($model, 'bkg_to_city_id'); ?></span>
							</div>
						<?php } ?>
					</div>  
				</div>
				<div class="row" id="ctyinfo_bkg_type_2">

				</div>

				<div class="row" id="pickup_div" style="display: <?= $showdiv ?>">
					<div class="col-sm-6">

						<? $strpickdate = ($model->bkg_pickup_date == '') ? date('Y-m-d H:i:s', strtotime('+4 hour')) : $model->bkg_pickup_date; ?>
						<?=
						$form->datePickerGroup($model, 'bkg_pickup_date_date', array('label'			 => 'Pickup Date',
							'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date('d/m/Y'), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'Pickup Date', 'value' => DateTimeFormat::DateTimeToDatePicker($strpickdate), 'class' => 'input-group border-gray full-width route-focus')), 'prepend'		 => '<i class="fa fa-calendar"></i>'));
						?>
						
					</div>

					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label" for="Booking_bkg_pickup_date_time">Pickup Time</label>
							<div class="bootstrap-timepicker input-group">
								<?php
								$time		 = strtotime(date('H:i', strtotime($strpickdate)));
								$round		 = 30 * 60;
								$rounded	 = round($time / $round) * $round;
								?>
								<?
								$this->widget('ext.timepicker.TimePicker', array(
									'model'			 => $model,
									'id'			 => CHtml::activeId($model, "bkg_pickup_date_time"),
									'attribute'		 => 'bkg_pickup_date_time',
									'options'		 => ['widgetOptions' => array('options' => array())],
									'htmlOptions'	 => array('required' => true, "value" => date('h:i A', $rounded), 'placeholder' => 'Pickup Time', 'class' => 'no-user-select input-group border-gray full-width route-focus form-control ct-form-control')
									
										));
								?> 
								<span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
							</div>
							<div class="help-block error" id="Booking_bkg_pickup_date_time_em_" style="display:none"></div>
						</div>
						<input type="hidden" id="pckageID" name="pckageID" value="<?= $package ?>">
					</div>
					<div id="errordivpdate" class="ml15 mt10" style="color:#da4455"></div>
				</div>
				<div class="row" style="display: none">
					<div class="col-sm-6">
						<?
						$strrtedate	 = ($model->bkg_return_date == '') ? '' : strtotime($model->bkg_return_date);
						if ($model->bkg_return_date != '')
						{
							$model->bkg_return_date = DateTimeFormat::DateTimeToDatePicker($model->bkg_return_date);
						}
						?>
						<?=
						$form->datePickerGroup($model, 'bkg_return_date_date', array('label'			 => 'Return Date',
							'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'Return Date', 'value' => $model->bkg_return_date)), 'prepend'		 => '<i class="fa fa-calendar"></i>', 'class'			 => 'route-focus'));
						?>
					</div>
					<div class="col-sm-6">
						<?=
						$form->timePickerGroup($model, 'bkg_return_date_time', array('label'			 => 'Return Time',
							'widgetOptions'	 => array('options' => array('autoclose' => true), 'htmlOptions' => array('placeholder' => 'Return Time', 'id' => 'Booking_bkg_return_date_time', 'value' => date('h:i A', $strrtedate), 'class' => 'route-focus'))));
						?>
					</div>
					<div id="errordivreturn" class="mt5 ml15" style="color:#da4455"></div>
				</div>
				<?
				if ($model->lead_id > 0)
				{
					$showdiv = "block";
				}
				?>
				<div class="row" id="address_div1" style="display: <?= $showdiv; ?>">
					<div class="col-sm-6 hide">
						<?= $form->textAreaGroup($model, 'bkg_pickup_address', array('label' => 'Pick up Location', 'widgetOptions' => array('htmlOptions' => array('class' => 'route-focus')))) ?>
					</div>
					<div class="col-sm-6 hide">
						<?= $form->textAreaGroup($model, 'bkg_drop_address', array('label' => 'Drop off Location', 'widgetOptions' => array('htmlOptions' => array('class' => 'route-focus')))) ?>
					</div> 
				</div>

				<input type="hidden"   id="preSCity">
				<input type="hidden"   id="preDCity">
				<input type="hidden"   id="errorCodeQuote" value="0">
				<div class="row" id="address_div">

				</div> 
				<div class="row">
					<div class="col-sm-6">
						<label class="control-label" for="tot_est_dist">Estimated distance</label>
						<input type="text" id="tot_est_dist" name="Booking[tot_est_dist]" value="" class="form-control" readonly="readonly">

					</div>
					<div class="col-sm-6">
						<label class="control-label" for="tot_est_dur">Estimated duration</label>
						<input type="text" id="tot_est_dur" name="Booking[tot_est_dur]" value="" class="form-control" readonly="readonly">
					</div>   
				</div>

				<!--									<div class="row">
														<div class="col-sm-6 pt10">
															<div class="form-group">
																<label class="control-label" for="exampleInputCompany6">Service Class</label>
				
				<?php
//					$serviceClass	 = CHtml::listData(ServiceClass::model()->findAll(), 'scc_id', 'scc_label');
//                    
//					foreach ($serviceClass as $key => $svc)
//					{if($key != 3){
				?>
																	<input type="radio" name="serviceClass" id="srv_<? //= $key                         ?>" value="<? //= $key                         ?>" class="checkbox-inline">
				<?php
//													echo $svc;
//                    }}
//												echo $form->hiddenField($model, 'bkg_service_class');
				?>	
															</div>
														</div>
													</div>-->

				<div class="row">
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label" for="exampleInputCompany6">Car Type </label>
							<?php
							$returnType	 = "category";
							$vehicleList = SvcClassVhcCat::getVctSvcList($returnType);
							unset($vehicleList[11]);
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'bkg_vehicle_type_id',
								'val'			 => $model->bkg_vehicle_type_id,
								'data'			 => $vehicleList,
								'htmlOptions'	 => array('style' => 'width:100%', 'id' => 'Booking_bkg_vehicle_type_id', 'placeholder' => 'Select Car Type', 'class' => 'route-focus dboOffer')
							));
							?>
							<span class="has-error"><? echo $form->error($model, 'bkg_vehicle_type_id'); ?></span>
                           <span id ="dbomsg" style="color: red;margin-top: 15px;"></span>
						</div>
					</div>
					<div class="col-sm-6  pt20 mt5" id="is_gozo_now_checkbox_div">
						<?= $form->checkboxGroup($prfModel, 'bkg_is_gozonow', ['label'=>'Convert To Gozo Now Booking', 'widgetOptions' => array('htmlOptions' => array('class'=>'is_gozo_now_checkbox'))]) ?>
					</div>
					<div class="col-sm-6 pt20 mt5" style="display: none" id="itenaryButtonDiv">
						<a class="btn btn-primary" onclick="admBooking.copyItinerary()"   id="itenaryButton">Copy Itinerary to Clipboard</a>
					</div>
					<div class="col-sm-6   mt5 text-primary" style="display: none" id="gozoNowAlert"></div>
					<div class="hide" id="divQuote"></div>
				</div>
				<br>
				<div class="row">
					<div class="col-sm-12 text-center">
						<button type='button' class='btn btn-info btn-route pl20 pr20'>Next</button>
					</div>
				</div>
			</div>
		</div>
	</div> 
</div>
<?php $this->endWidget(); ?>

<script>
	$sourceList = null;
	var jsonData = JSON.parse($('#jsonData_routeType').val());
	$(document).ready(function () {
		$('#address_div').html('');
	});
	$("#Booking_bkg_from_city_id,#Booking_bkg_to_city_id,#Booking_bkg_vehicle_type_id,#Booking_bkg_pickup_date_date").change(function ()
	{
		if ((($('#Booking_bkg_from_city_id').val() != $("#preSCity").val()) || ($('#Booking_bkg_to_city_id').val() != $("#preDCity").val())) && jsonData.bkg_booking_type != 4)
		{
			admBooking.getRoute(booking);
		}
		if ($('#Booking_bkg_pickup_date_time').val() != '')
		{

			jsonData.bkg_service_class = 0;
			admBooking.getAmountbyCitiesnVehicle(booking, jsonData, 'route');
			$("#errordivpdate").text('');
		}
	});

	$('#Booking_bkg_pickup_date_time,#Booking_bkg_pickup_date_date').blur(function () {
		$(".btn-route").removeClass("disabled");
		$('#Booking_bkg_vehicle_type_id').val('').change();
		$('#dbomsg').hide();
	});

	function checkGozoNow() {
//		return false;//disabling auto gozoNow intiate from admin.
		 
		var fcity = $('#Booking_bkg_from_city_id').val();
		var tcity = $('#Booking_bkg_to_city_id').val();
		var pickupDate = $('#Booking_bkg_pickup_date_date').val();
		var pickupTime = $('#Booking_bkg_pickup_date_time').val();
		var bkgType = $('#Booking_bkg_booking_type').val();
		var bkgVehicleType = $('#Booking_bkg_vehicle_type_id').val(); 
		var agtId = jsonData.bkg_agent_id; 
//		var agtId = $('#Booking_bkg_agent_id').val();
		var url = '<?= CHtml::normalizeUrl(Yii::app()->createUrl('admin/booking/checkAdminGozonow')) ?>';
		$.ajax({
			url: url,
			type: 'GET',
			dataType: 'json',
			data: {"fcity": fcity, "tcity": tcity, "pickupDate": pickupDate, "pickupTime": pickupTime, "bkgType": bkgType,
				"bkgVehicleType": bkgVehicleType, "agtId": agtId},
			success: function (data)
			{
				if (data.success)
				{
					if(!$('#is_gozo_now_checkbox_div').hasClass('hide'))
					{
				    	$('#is_gozo_now_checkbox_div').addClass('hide');
					}
					$('#Booking_isGozonow').val('1');
					jsonData.isGozonow = 1;
					$('#gozoNowAlert').show();
					$('#gozoNowAlert').text('Gozo NOW is enabled for this booking. All prices shown here by Gozo sales team are only representative. The actual price of the booking may be much higher or lower as it will be based on the inventory situation & real-time offers chosen by the customer on Gozo NOW screen.');
				} else {
					$('#Booking_isGozonow').val('0');
					jsonData.isGozonow = 0;
					$('#gozoNowAlert').hide();
					$('#gozoNowAlert').text('');
					$('#is_gozo_now_checkbox_div').removeClass('hide');
				}
				return(data.success); 				
			}
		});
	}
	
	$(".dboOffer").click(function(){
		checkDBO();
	});
	
    function checkDBO() 
	{
		var pickupDate = $('#Booking_bkg_pickup_date_date').val();
		var pickupTime = $('#Booking_bkg_pickup_date_time').val();
		var bkgVehicleType = $('#Booking_bkg_vehicle_type_id').val(); 
		var agtId      = jsonData.bkg_agent_id; 

		var url = '<?= CHtml::normalizeUrl(Yii::app()->createUrl('admin/booking/checkDboApplicable')) ?>';
		$.ajax({
			url: url,
			type: 'GET',
			dataType: 'json',
			data: {"pickupDate": pickupDate, "pickupTime": pickupTime, "bkgVehicleType": bkgVehicleType, "agtId": agtId},
			success: function (data)
			{
				if (data.success)
				{
					$('#dbomsg').show();
					$('#dbomsg').html(data.msg);				
				}
				else
				{
					$('#dbomsg').html('');
				}	
			}
		});	
	}
	$('#Booking_bkg_route').bind("change", function ()
	{
		var city = new City();
		admBooking.selctRoute(city);
	});

	$(document).on("getRouteListbyCities", function (event, data)
	{
		admBooking.routeCitiesList(data);
	});

	function  getDateobj(pdpdate, ptptime)
	{
		admBooking.getDateobj(pdpdate, ptptime);
	}

	function updateMulticity(data, tot)
	{
		$('#multicityAutoComTot').val(tot);
		$('#multicityAutoComData').val(data);
		admBooking.updateMulticity(data, tot, jsonData, hyperModel, 'route');
	}

	function populateSource(obj, cityId)
	{
		obj.load(function (callback)
		{
			var obj = this;
			if ($sourceList == null)
			{
				var urlCity = '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery', ['apshow' => 1])) ?>';
				if (jsonData.bkg_booking_type == '9' || jsonData.bkg_booking_type == '10' || jsonData.bkg_booking_type == '11') {
					var urlCity = '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/dayrentalcitylist')) ?>';
				}
				xhr = $.ajax({
					url: urlCity,
					dataType: 'json',
					data: {
					},
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

	function loadSource(query, callback, btype)
	{
		$bkType = btype;
		if ($bkType != 9 && $bkType != 10 && $bkType != 11)
		{
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
	}

	$('#Booking_bkg_from_city_id1').change(function ()
	{
		$('#Booking_bkg_from_city_id').val($('#Booking_bkg_from_city_id1').val()).change();
		if (jsonData.bkg_booking_type == 9 || jsonData.bkg_booking_type == 10 || jsonData.bkg_booking_type == 11) {
			$('#Booking_bkg_to_city_id').val($('#Booking_bkg_from_city_id1').val()).change();
		}
	});

	$('#Booking_bkg_to_city_id1').change(function ()
	{
		$('#Booking_bkg_to_city_id').val($('#Booking_bkg_to_city_id1').val()).change();
	});

	$(".btn-route").click(function () {
		var errorCodeQuote = $('#errorCodeQuote').val();
		if (errorCodeQuote != 107)
		{
			$("#bookingRouteForm").submit();
		}
	});

	$(".btn-editRoute").click(function () {
		$('#payment,#travellerInfo,#additionalInfo,#rePayment,#vendorIns').html('');
		$('#payment,#travellerInfo,#additionalInfo,#rePayment,#vendorIns').addClass('hide');
		$(".btn-route").addClass("btn-info");
		$(".btn-route").removeClass("disabled");
		$("#bookingRoute").find("input,textarea").attr("disabled", false);
		$("#bookingRoute").find(".route-focus").removeClass("disabled");
		$(".btn-editMulticity").removeClass("hide");
		$(".btn-editRoute").addClass("hide");
	});

	$(".btn-editMulticity").click(function () {
		admBooking.editmulticity(jsonData.bkg_booking_type);
		$('#Booking_bkg_vehicle_type_id').val('').change();
	});

	$('.txtplroute').change(function () {
		hyperModel.findAddress(this.id);
	});

	$('.autoComLoc').change(function () {
		hyperModel.findAddressAirport(this.id);
	});

	$("input[name=serviceClass]:radio").click(function () {
		$('#Booking_bkg_vehicle_type_id').val('').change();
		var checkServiceClass = $('input[name=serviceClass]:checked').val();
		$('#Booking_bkg_service_class').val(checkServiceClass);
		$('#Booking_bkg_vehicle_type_id').empty();
		$('#Booking_bkg_vehicle_type_id').append($('<option>').text("--Select Car Type --").attr('value', ""));
		$.ajax({
			url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/getallcarmodelbyclass')) ?>?val=' + checkServiceClass,
			type: 'GET',
			dataType: 'json',
			data: {"scv_id": checkServiceClass},
			success: function (data)
			{
				$.each(data, function (key, value) {
					$('#Booking_bkg_vehicle_type_id').append($('<option>').text(value).attr('value', key));
				});
			}
		});
	});

	$('.is_gozo_now_checkbox').click(function () 
	{
		if($(".is_gozo_now_checkbox").is(":checked"))
		{ 
			    let jsonDataTemp = JSON.parse($('#jsonData_routeType').val());
                let dataTemp = {"isGozonow": 1};
                $.extend(true, jsonDataTemp, dataTemp);
				$('#jsonData_routeType').val(JSON.stringify(jsonDataTemp));
				$('#Booking_isGozonow').val('1');
				jsonData.isGozonow = 1;
				$('#gozoNowAlert').show();
				$('#gozoNowAlert').text('Gozo NOW is enabled for this booking. All prices shown here by Gozo sales team are only representative. The actual price of the booking may be much higher or lower as it will be based on the inventory situation & real-time offers chosen by the customer on Gozo NOW screen.');
		}
		else
		{
				let jsonDataTemp = JSON.parse($('#jsonData_routeType').val());
                let dataTemp = {"isGozonow": 0};
                $.extend(true, jsonDataTemp, dataTemp);
				$('#jsonData_routeType').val(JSON.stringify(jsonDataTemp));
				$('#Booking_isGozonow').val('0');
				jsonData.isGozonow = 0;
				$('#gozoNowAlert').hide();
				$('#gozoNowAlert').text('');
		}
	});
</script>
