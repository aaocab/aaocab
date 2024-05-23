<?php
$version			 = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/jquery.mask.min.js');
Yii::app()->clientScript->registerCssFile(ASSETS_URL . '/plugins/form-select2/select2.css');
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/jquery.mask1.min.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/booking.js?v=' . $version);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/route.js?v=' . $version);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/city.js?v=' . $version);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/promo.js?v=' . $version);
$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
$cartype			 = VehicleTypes::model()->getParentVehicleTypes(1);
unset($cartype[11]);
// = Agents::model()->getAgentList();
$status				 = Booking::model()->getBookingStatus();

$bookingType = Booking::model()->getBookingType();
unset($bookingType[2]);
unset($bookingType[5]);
unset($bookingType[6]);
unset($bookingType[8]);
unset($bookingType[9]);
unset($bookingType[10]);
unset($bookingType[11]);
//print_r($bookingType);
$infosource	 = BookingAddInfo::model()->getInfosource('admin');
$countrycode = Yii::app()->params['countrycode'];
$ccode		 = (int) str_replace('+', '', $countrycode);
$showadd	 = 0;


$fcity		 = $packagedt[0]["pcd_from_city"];
$count		 = count($packagedt);
$tcitylast	 = $packagedt[$count - 1]["pcd_to_city"];


$additionalAddressInfo	 = "Building No./ Society Name";
$api					 = Yii::app()->params['googleBrowserApiKey'];
$autoAddressJSVer		 = Yii::app()->params['autoAddressJSVer'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/js/gozo/hyperLocation.js?v=$autoAddressJSVer");
$autocompleteFrom		 = 'txtpl';
$autocompleteTo			 = 'txtpl';
$locReadonly			 = ['readonly' => 'readonly'];

$j		 = 0;
$staxrate						 = BookingInvoice::getGstTaxRate($model->bkg_agent_id, $model->bkg_booking_type);
//$now = Filter::getDBDateTime();
//$model
//$expiremint  = $bkgprfmodel['minuteGiven']*0.2;
//$expireDateTime	 = date("Y-m-d H:i:s", strtotime(" +$expiremint minute", strtotime($now)));
?>
<style>
    @media (min-width: 992px){
        .modal-lg {
            width: 95%!important;
        }
    }
    @media (min-width: 768px){
        .modal-lg {
            width: 100%;
        }
    }

    .form-group {
        margin-bottom: 7px;
        margin-left: 0 !important;
        margin-right: 0 !important;
    }
    .bootstrap-timepicker-widget input  {
        border: 1px #555555 solid;color: #555555;
    }
    .navbar-nav > li > a {
        padding: 6px 30px;
    }
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin:0;
    }
    .selectize-input {

        width: 30% !important;
    }
    .checkbox label {
        padding-left: 0px;
    }

    td, th {
        padding: 10px  !important ; 
    }

</style>
<div class="container">
	<?php
	/* @var $form TbActiveForm */
	$form	 = $this->beginWidget('booster.widgets.TbActiveForm', array(
		'id'					 => 'booking-form', 'enableClientValidation' => true,
		'clientOptions'			 => array(
			'validateOnSubmit'	 => true,
			'errorCssClass'		 => 'has-error',
			'afterValidate'		 => 'js:function(form,data,hasError){
                if(!hasError){
				
                     	$("#bkgSubmitDiv").hide();
							if (countSubmit==0){
								countSubmit++;
								
                    $("#btnsbmt").prop( "disabled", true );
                    if(!validateBooking())
					{
						countSubmit--;
						$("#bkgSubmitDiv").show();
                        $("#btnsbmt").prop( "disabled", false );
                        return false;                         
					}
                    $.ajax({
                    "type":"POST",
                    "dataType":"json",
                    async: false,
                    "url":"' . CHtml::normalizeUrl(Yii::app()->request->url) . '",
                    "data":form.serialize(),
                    "beforeSend": function () {
                        ajaxindicatorstart("");
                    },
                    "complete": function () {                     
                        ajaxindicatorstop();
                    },
                    "success":function(data1){
                        if(data1.success){
                        alert(data1.message);
                        location.href=data1.url;
                            return false;
                        } else{
						countSubmit--;
						$("#bkgSubmitDiv").show();
                        $("#btnsbmt").prop( "disabled", false );
                            var errors = data1.errors;
                            settings=form.data(\'settings\');
                             $.each (settings.attributes, function (i) {
                                $.fn.yiiactiveform.updateInput (settings.attributes[i], errors, form);
                              });
                              $.fn.yiiactiveform.updateSummary(form, errors);
                            } 
                        },
                     error: function(xhr, status, error){
					    countSubmit--;
						$("#bkgSubmitDiv").show();
                       var x= confirm("Network Error Occurred. Do you want to retry?");
                       if(x){
                                $("#booking-form").submit();
                            }
                            else{
                            $("#btnsbmt").prop( "disabled", false );
                            }
                         }
                    });

                    }
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
			'class' => '',
		),
	));
	?>
    <div class="row">

		<?= $form->hiddenField($model, 'lead_id', array('readonly' => true)) ?>
		<?= $form->hiddenField($usrModel, 'bkg_user_id'); ?>
		<?= $form->hiddenField($usrModel, 'bkg_contact_id'); ?>
		<?= $form->hiddenField($model, 'bkg_from_city_id'); ?>
		<?= $form->hiddenField($model, 'bkg_to_city_id'); ?>
		<?= $form->hiddenField($model, 'routeProcessed'); ?>
		<?= $form->hiddenField($usrModel, 'bkg_contact_id'); ?>
		<input type="hidden" id="oldPromoCode" name="oldPromoCode" value="">
		<input type="hidden" id="bkg_surge_differentiate_amount" name="bkg_surge_differentiate_amount" value="">
        <input type="hidden" id="bkgPricefactor" name="bkgPricefactor">

        <div class="col-md-7">
			<?=
			$form->errorSummary($model);
			echo CHtml::errorSummary($model);
			$form->errorSummary($addInfoModel);
			echo CHtml::errorSummary($addInfoModel);
			?>
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-default panel-border">
                        <h3 class="pl15 pb10">Booking Information <span class="pull-right mr20">
								<?php
								if (!$package)
								{
									?>
									<button class="btn btn-info" type="button" id="addCity">Add City</button>
									<?
								}
								else
								{
									?>
									<button class="btn btn-info hide" type="button" id="editLocation">Edit Location</button>
								<? } ?>


                            </span>
						</h3>
                        <div class="panel-body pt0">
                            <div class="row">
                                <div class="col-sm-6">

									<?
									if ($package)
									{
										?>
										<div class="form-group">
											<b class="mb10 text-uppercase h4"> <?= $packages['pck_name'] . '<span style="white-space:nowrap" class="h5"> (' . $packages['pck_auto_name'] . ')</span>' ?></b>
											<input type="hidden" id="multicityjsondata" name="multicityjsondata" value='<?= json_encode($model->preData); ?>'>
											<input  name="Booking[bkg_booking_type]" id="Booking_bkg_booking_type" type="hidden"  value="5">  
											<input type="hidden" name="packageJson" id="packageJson" value="">
											<input type="hidden" id="return_date" name="Booking[bkg_return_date]" value="">
											<input type="hidden" id="return_time" name="Booking[bkg_return_time]" value="">
											<input type="hidden" id="drop_date" name="Booking[bkg_drop_date]" value="">
											<input type="hidden" id="drop_time" name="Booking[bkg_drop_time]" value="">
											<input type="hidden" id="first_pickup" name="first_pickup" value="<?= $firstPickup ?>">
											<input type="hidden" id="last_dropoff" name="last_dropoff" value="<?= $lastDrop ?>">
											<input type="hidden" id="pckageID" name="Booking[bkg_package_id]" value="<?= $package ?>">
										</div>
										<?php
									}
									else
									{
										?>
										<div class="form-group">
											<label class="control-label" for="exampleInputName6">Booking Type</label>
											<input type="hidden" id="multicityjsondata" name="multicityjsondata" value='<?= json_encode($model->preData); ?>'>
											<?php
											$dataBookType = VehicleTypes::model()->getJSON($bookingType);
											$this->widget('booster.widgets.TbSelect2', array(
												'model'			 => $model,
												'attribute'		 => 'bkg_booking_type',
												'val'			 => ($model->bkg_booking_type == '') ? 1 : $model->bkg_booking_type,
												'asDropDownList' => FALSE,
												'options'		 => array('data' => new CJavaScriptExpression($dataBookType)),
												'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Booking Type', 'class' => 'input-group')
											));
											?>
											<span class="btn-info p5 mt10 col-xs-3" id="addmulticities" style="display: none">add cities</span>
											<span class="has-error"><? echo $form->error($model, 'bkg_booking_type'); ?></span>
										</div>
									<? } ?>
                                </div>
                            </div>


                            <!--                            multicity-->
                            <div class="row" id='tripTablecreate' style="display: <?= ($model->preData != '') ? 'block' : 'none' ?>">
                                <div class="col-xs-12 table-responsive" >
                                    <div class="float-none marginauto">
										<?php
										if (!$package)
										{
											?>
											<h3 class="mb10 text-uppercase">Trip Info  <button type="button" class="btn btn-info ml15" onclick="editmulticity()"><i class="fa fa-edit"></i></button></h3>
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
															$ctyLat[$key]		 = $brtRoute->pickup_cty_lat;
															$ctyLon[$key]		 = $brtRoute->pickup_cty_long;
															$bound[$key]		 = $brtRoute->pickup_cty_bounds;
															$isCtyAirport[$key]	 = $brtRoute->pickup_cty_is_airport;
															$isCtyPoi[$key]		 = $brtRoute->pickup_cty_is_poi;

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

																		<input type="hidden" name="BookingRoute[0][brt_from_latitude]" class="locLat_0" value="<?= $brtRoute->pickup_loc_lat ?>">
																		<input type="hidden" class="locLon_0" name="BookingRoute[0][brt_from_longitude]" value="<?= $brtRoute->pickup_loc_long ?>">
																		<input type="hidden" id="city_is_airport0" name="BookingRoute[0][brt_from_city_is_airport]" value="<?= $brtRoute->pickup_cty_is_airport ?>">
																		<input type="hidden" id="city_is_poi0" name="BookingRoute[0][brt_from_city_is_poi]" value="<?= $brtRoute->pickup_cty_is_poi ?>">

																	</div>
																	<div class="col-xs-12 col-sm-6 mb0 pb0"><div class="form-group">
																			<textarea id="locOldCreate_0" class="form-control brt_location_0 txtpl form-control" placeholder="Pickup Address  (Required)" name="BookingRoute[0][brt_from_location]" autocomplete="off"><?= $brtRoute->pickup_address ?></textarea>
																			<div class="help-block error" id="BookingRoute_0_brt_from_location_em_" style="display:none"></div>
																		</div></div>
																</div><div class="row ">
																	<div class="col-xs-12 col-sm-6 pl0">
																		<label for="buildinInfo0" class="control-label text-left"><?= $additionalAddressInfo ?>:</label>
																	</div>
																	<div class="col-xs-12 col-sm-6 mb0 pb0"><div class="form-group">
																			<input id="brt_additional0" class="form-control form-control" placeholder="<?= $additionalAddressInfo ?>" name="BookingRoute[0][brt_additional_from_address]" type="text">
																			<div class="help-block error" id="BookingRoute_0_brt_additional_from_address_em_" style="display:none">
																			</div></div></div></div></div>
															<?
														}
														$key1	 = $key + 1;
														$j++;
														$opt	 = (($key + 1) == $cntRt) ? 'Required' : 'Optional';
														$optReq	 = (($key + 1) == $cntRt) ? ' *' : '';

														$ctyLat[$key1]		 = $brtRoute->drop_cty_lat;
														$ctyLon[$key1]		 = $brtRoute->drop_cty_long;
														$bound[$key1]		 = $brtRoute->drop_cty_bounds;
														$isCtyAirport[$key1] = $brtRoute->drop_cty_is_airport;
														$isCtyPoi[$key1]	 = $brtRoute->drop_cty_is_poi;

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
																	<input name="BookingRoute[<?= $key1 ?>][brt_to_latitude]"  class="locLatVal locLat_<?= $key1 ?>"  type="hidden" value="<?= $brtRoute->drop_loc_lat ?>">
																	<input name="BookingRoute[<?= $key1 ?>][brt_to_longitude]"  class="locLonVal locLon_<?= $key1 ?>" type="hidden" value="<?= $brtRoute->drop_loc_long ?>">
																	<input id="city_is_airport<?= $key1 ?>" name="BookingRoute[<?= $key1 ?>][brt_to_city_is_airport]" type="hidden"  value="<?= $brtRoute->drop_cty_is_airport ?>">
																	<input id="city_is_poi<?= $key1 ?>" name="BookingRoute[<?= $key1 ?>][brt_to_city_is_poi]" type="hidden"  value="<?= $brtRoute->drop_cty_is_poi ?>">
																</div>
																<div class="col-xs-12 col-sm-6">
																	<div class="form-group">
																		<textarea id="locOldCreate_<?= $key1 ?>" class="form-control brt_location_<?= $key1 ?> txtpl form-control" placeholder="Drop Address  (Optional)" name="BookingRoute[<?= $key1 ?>][brt_to_location]" autocomplete="off"><?= $brtRoute->drop_address ?></textarea>
																		<div class="help-block error" id="BookingRoute_<?= $key1 ?>_brt_to_location_em_" style="display:none"></div>
																	</div></div>
															</div>
															<div class="row"><div class="col-xs-12 col-sm-6 pl0">
																	<label for="buildinInfo" class="control-label text-left"><?= $additionalAddressInfo ?>:</label>
																</div>
																<div class="col-xs-12 col-sm-6">
																	<div class="form-group"><input id="brt_additional<?= $key1 ?>" class="form-control form-control" placeholder="<?= $additionalAddressInfo ?>" name="BookingRoute[<?= $key1 ?>][brt_additional_to_address]" type="text">
																		<div class="help-block error" id="BookingRoute_<?= $key1 ?>_brt_additional_to_address_em_" style="display:none">
																		</div></div></div></div></div>
														<?
													}


													/* foreach ($arrmulticitydata as $key => $value)
													  {
													  var_dump($arrmulticitydata);

													  ?>

													  <div class="col-sm-12">	1
													  <div class="row">
													  <div class="col-sm-6">
													  <div class="form-group has-success">
													  <label class="control-label" for="Booking_bkg_pickup_address">Pick up Location From <?= $value->pickup_city_name ?></label>
													  <textarea class="form-control" placeholder="Pick up Address" name="Booking[bkg_pickup_address]<?= $key ?>" id="Booking_bkg_pickup_address<?= $key ?>" onChange="updateAdress(<?= $key ?>, 1)"><?= $value->pickup_address ?></textarea>
													  </div>
													  </div>
													  <div class="col-sm-6">
													  <div class="form-group">
													  <label class="control-label" for="Booking_bkg_drop_address">Drop off Location To <?= $value->drop_city_name ?></label>
													  <textarea class="form-control" placeholder="Drop Address" name="Booking[bkg_drop_address]<?= $key ?>" id="Booking_bkg_drop_address<?= $key ?>" onChange="updateAdress(<?= $key ?>, 2)"><?= $value->drop_address ?></textarea>
													  </div>
													  </div>
													  </div>
													  </div>
													  <?php
													 * 

													  } */
												}
												else
												{
													
												}
												?>
											</div>
										</div>
									</div>

								</div>
							</div>
							<?
							$showdiv	 = ($model->bkg_booking_type != '' || $package != '' ) ? $model->bkg_booking_type : 1;
							?>

							<div class="row" id="ctyinfo_bkg_type_1"  style="display: <? echo ($showdiv == 1) ? 'block' : 'none' ?>">
								<div class="col-sm-6 ">
									<div class="form-group cityinput">
										<label class="control-label" for="exampleInputName6">Source City</label>
										<?php
										$this->widget('ext.yii-selectize.YiiSelectize', array(
											'model'				 => $model,
											'attribute'			 => 'bkg_from_city_id1',
											'useWithBootstrap'	 => true,
											"placeholder"		 => "Select Source City",
											'fullWidth'			 => false,
											'htmlOptions'		 => array('width'	 => '100%',
												'id'	 => 'Booking_bkg_from_city_id1'
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
										<span class="has-error"><? echo $form->error($model, 'bkg_from_city_id'); ?></span>
									</div>
								</div>
								<div class="col-sm-6">
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
												'id'	 => 'Booking_bkg_to_city_id1'
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
								</div>  
							</div>
							<?
							$showdiv1	 = ($model->bkg_booking_type != '') ? $model->bkg_booking_type : 1;
							?>
							<div class="row" id="pickup_div" style="display: <? echo ($showdiv1 == 1) ? 'block' : 'none' ?>">
								<div class="col-sm-6">

									<? $strpickdate = ($model->bkg_pickup_date == '') ? date('Y-m-d H:i:s', strtotime('+4 hour')) : $model->bkg_pickup_date; ?>
									<?=
									$form->datePickerGroup($model, 'bkg_pickup_date_date', array('label'			 => 'Pickup Date',
										'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'Pickup Date', 'value' => DateTimeFormat::DateTimeToDatePicker($strpickdate), 'class' => 'input-group border-gray full-width')), 'prepend'		 => '<i class="fa fa-calendar"></i>'));
									?>
								</div>

								<div class="col-sm-6">

									<?
									echo $form->timePickerGroup($model, 'bkg_pickup_date_time', array('label'			 => 'Pickup Time',
										'widgetOptions'	 => array('id' => CHtml::activeId($model, "bkg_pickup_date_time"), 'options' => array('autoclose' => true), 'htmlOptions' => array('placeholder' => 'Pickup Time', 'value' => date('h:i A', strtotime($strpickdate)), 'class' => 'input-group border-gray full-width'))));
									?>
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
										'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'Return Date', 'value' => $model->bkg_return_date)), 'prepend'		 => '<i class="fa fa-calendar"></i>'));
									?>
								</div>
								<div class="col-sm-6">
									<?=
									$form->timePickerGroup($model, 'bkg_return_date_time', array('label'			 => 'Return Time',
										'widgetOptions'	 => array('options' => array('autoclose' => true), 'htmlOptions' => array('placeholder' => 'Return Time', 'id' => 'Booking_bkg_return_date_time', 'value' => date('h:i A', $strrtedate)))));
									?>
								</div>
								<div id="errordivreturn" class="mt5 ml15" style="color:#da4455"></div>
							</div>
							<?
							if ($model->lead_id > 0)
							{
								$showadd = 1;
							}
							?>
							<div class="row" id="address_div1" style="display: <? echo ($showdiv == 1 || $showadd == 1) ? 'block' : 'none' ?>">
								<div class="col-sm-6 hide">
									<?= $form->textAreaGroup($model, 'bkg_pickup_address', array('label' => 'Pick up Location', 'widgetOptions' => array('htmlOptions' => array()))) ?>
								</div>
								<div class="col-sm-6 hide">
									<?= $form->textAreaGroup($model, 'bkg_drop_address', array('label' => 'Drop off Location', 'widgetOptions' => array('htmlOptions' => array()))) ?>
								</div> 
							</div>

							<input type="hidden"   id="preSCity">
							<input type="hidden"   id="preDCity">
							<div class="row" id="address_div"> 
							</div> 
							<div class="row">
								<div class="col-sm-6">
									<?= $form->textFieldGroup($model, 'bkg_trip_distance', array('label' => "Estimated distance", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'In Km', 'readonly' => 'readonly')))) ?>
								</div>
								<div class="col-sm-6">
									<?= $form->textFieldGroup($model, 'bkg_trip_duration', array('label' => "Estimated duration", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'In Min', 'readonly' => 'readonly')))) ?>
								</div>   
							</div>
							<div class="row">
								<div class="col-sm-6">
									<div class="form-group">
										<label class="control-label" for="exampleInputCompany6">Car Model</label>
										<?php
										$returnType	 = "list";
										$vehicleList = SvcClassVhcCat::getVctSvcList($returnType);
										$this->widget('booster.widgets.TbSelect2', array(
											'model'			 => $model,
											'attribute'		 => 'bkg_vehicle_type_id',
											'val'			 => $model->bkg_vehicle_type_id,
											"data"			 => $vehicleList,
											'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Car Type')
										));
										?>
										<span class="has-error"><? echo $form->error($model, 'bkg_vehicle_type_id'); ?></span>
									</div>
								</div>

								<div class="col-sm-6 pt20 mt5" style="display: none" id="itenaryButtonDiv">
									<a class="btn btn-primary" onclick="copyItinerary()"   id="itenaryButton">Copy Itinerary to Clipboard</a>
								</div>
								<div class="hide" id="divQuote"></div>
							</div>

							<div class="row">
								<?
								if ($model->trip_user == '')
								{
									$model->trip_user = 1;
								}
								if ($model->bkg_agent_id > 0)
								{
									$model->trip_user = 2;
								}
								?>
								<?=
								$form->radioButtonListGroup($model, 'trip_user', array(
									'label'			 => '', 'widgetOptions'	 => array(
										'data'			 => Booking::model()->userTripList1,
										'htmlOptions'	 => array('onclick' => 'bookingPreference();'),
									), 'inline'		 => true,)
								);
								?>  
							</div>
							<div class="row <?= ($model->bkg_agent_id > 0) ? "" : "hide" ?>" id="linkagentdiv">
								<div class="col-xs-12">
									<div class="form-group">
										<label class="control-label">Link to Channel Partner</label>
										<?php
										$dataagents = Agents::model()->getAgentsFromBooking(false);
										$this->widget('booster.widgets.TbSelect2', array(
											'model'			 => $model,
											'attribute'		 => 'bkg_agent_id',
											'val'			 => $model->bkg_agent_id,
											'asDropDownList' => FALSE,
											'options'		 => array('data' => new CJavaScriptExpression($dataagents)),
											'htmlOptions'	 => array(
												'id'			 => 'bkg_agent_id',
												'style'			 => 'width:100%',
												'placeholder'	 => 'Partner Name')
										));
										?>
									</div> 
								</div> 
							</div>
							<input type="hidden" value="" id="agt_type" name="agt_type">
							<input type="hidden" value="" id="agt_commission_value" name="agt_commission_value">
							<input type="hidden" value="" id="agt_commission" name="agt_commission">

							<div class="row hide mt10" id="booking_ref_code_div">
								<div class="col-xs-4">Agent Ref ID</div>
								<div class="col-xs-8">
									<?= $form->textFieldGroup($model, 'bkg_agent_ref_code', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Agents reference ID for this booking')))); ?>          
								</div>
							</div>
							<div class="row hide" id="divpaidby">
								<div class="col-xs-4">To be paid by</div>
								<div class="col-xs-8">
									<label class="checkbox-inline ">
										<?
										if ($model->agentBkgAmountPay == '')
										{
											$model->agentBkgAmountPay = 2;
										}
										?>
										<?= $form->radioButtonListGroup($model, 'agentBkgAmountPay', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['onclick' => 'showAgentCreditDiv()'], 'data' => [1 => 'Customer', 2 => 'Agent/Company']), 'inline' => true)) ?>
									</label>
								</div>
							</div> 



							<!--           notification agent options-->
							<div class="row hide" id="agtnotification">
								<div class="col-sm-12 mt20">
									<div class="row" id="" >

										<h3 class="pl15">Partner preferences</h3>
										<div class="col-xs-12 mb20" style="display:none;" id="divpaidby2">
											<? //$model->bkgPref->bkg_trip_otp_required	= 0; ?>
											<? //= $form->radioButtonListGroup($model->bkgPref, 'bkg_trip_otp_required', array('label' => 'Otp is required', 'widgetOptions' => array('data' => [1 => 'Yes', 0 => 'No']), 'inline' => true)) ?>
											<span class="mr15" id="divpref1"></span>
											<span class="mr15" id="divpref2"></span>
											<span class="mr15" id="divpref3"></span>
											<span class="mr15" id="divpref4"></span>
											<span class="mr15" id="divpref5"></span>
											<span class="mr15" id="divpref6"></span>
											<span class="mr15" style="border:0px solid #cccccc; padding: 2px;" id="bkg_pref_req_other1"></span>

										</div>
									</div>	
									<div class="row">
										<div class="mb0">
											<div class="col-xs-12 ">Send a booking copy to</div>
										</div>
										<div class="  pt15">
											<div class="col-xs-6 col-md-3">
												<?= $form->textFieldGroup($model, 'bkg_copybooking_name', array('label' => "Name", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Name')))) ?>
											</div>
											<div class="col-xs-6  col-md-3"> 
												<?= $form->textFieldGroup($model, 'bkg_copybooking_email', array('label' => "Email", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Email')))) ?>
											</div>
											<div class="col-xs-3  col-md-2  pr0">
												<div class="form-group ">
													<label>Country Code</label>
													<?php
													$this->widget('ext.yii-selectize.YiiSelectize', array(
														'model'				 => $model,
														'attribute'			 => 'bkg_copybooking_country',
														'useWithBootstrap'	 => true,
														"placeholder"		 => "Code",
														'fullWidth'			 => false,
														'htmlOptions'		 => array(
														),
														'defaultOptions'	 => array(
															'create'			 => false,
															'persist'			 => true,
															'selectOnTab'		 => true,
															'createOnBlur'		 => true,
															'dropdownParent'	 => 'body',
															'optgroupValueField' => 'id',
															'optgroupLabelField' => 'pcode',
															'optgroupField'		 => 'pcode',
															'openOnFocus'		 => true,
															'labelField'		 => 'pcode',
															'valueField'		 => 'pcode',
															'searchField'		 => 'name',
															//   'sortField' => 'js:[{field:"order",direction:"asc"}]',
															'closeAfterSelect'	 => true,
															'addPrecedence'		 => false,
															'onInitialize'		 => "js:function(){
                                                                this.load(function(callback){
                                                                var obj=this;                                
                                                                 xhr=$.ajax({
                                                         url:'" . CHtml::normalizeUrl(Yii::app()->createUrl('index/country')) . "',
                                                         dataType:'json',                  
                                                         success:function(results){
                                                             obj.enable();
                                                             callback(results.data);
                                                              obj.setValue('{$model->bkg_copybooking_country}');
                                                         },                    
                                                         error:function(){
                                                             callback();
                                                         }});
                                                        });
                                                        }",
															'render'			 => "js:{
                                                            option: function(item, escape){                      
                                                            return '<div><span class=\"\">' + escape(item.name) +'</span></div>';                          
                                                            },
                                                            option_create: function(data, escape){
                                                            return '<div>' +'<span class=\"\">' + escape(data.pcode) + '</span></div>';
                                                                }
                                                            }",
														),
													));
													?>
												</div>  </div>
											<div class="col-xs-9 col-md-4"> 
												<?= $form->textFieldGroup($model, 'bkg_copybooking_phone', array('label' => "Phone", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Phone')))) ?>
												<div id="mobcopybooking" style="color:#da4455"></div>
											</div>
										</div>
									</div>
									<div class="row">

									</div>


								</div>


								<div class="col-xs-12 pt20 " id="divUpd">

								</div>
							</div>
							<!--            notification agent options-->

							<!--        corporate additional details -->

							<div class="row">
								<div class="col-xs-12 mt20 hide" id="corp_addt_details">
									<input type="checkbox" name="corp_addt_details[]" value="1"  checked>Driver & Car details required at least 12 hours before the pickup
									<br><br><input type="checkbox" name="corp_addt_details[]" value="2" checked>Corporate booking – car must be new and clean inside & outside
									<br><br><input type="checkbox" name="corp_addt_details[]" value="3" checked>Corporate company require duty slips for all parking or toll payments.
									<br><br><input type="checkbox" name="corp_addt_details[]" value="4" checked>Do not ask traveller for any cash. Contact Gozo for any issues.
								</div>
								<!--        corporate additional details -->
								<div class="col-xs-12 hide" id="agent_notify_option"><button type="button" class="btn btn-info btn-small" onclick="shownotifyopt()">Notification Options</button></div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row" id="errorShow" style="display: none">
				<div class="col-xs-12">
					<div class="panel panel-default panel-border">

						<div class="panel-body ">
							<div class="row">
								<div class="col-xs-12 p5 alert-danger" id="errorMsg"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12">
					<div class="panel panel-default panel-border">
						<h3 class="pl15">Payment Information</h3>
						<?= $form->hiddenField($invModel, 'bkg_chargeable_distance'); ?>
						<?= $form->hiddenField($trcModel, 'bkg_garage_time'); ?>
						<?= $form->hiddenField($invModel, 'bkg_is_toll_tax_included'); ?>
						<?= $form->hiddenField($invModel, 'bkg_is_state_tax_included'); ?>
						<?= $form->hiddenField($invModel, 'bkg_gozo_base_amount'); ?>

						<? //$form->hiddenField($model, 'bkg_is_day_driver_allowance_amount'); ?>
						<? // $form->hiddenField($model, 'bkg_is_night_driver_allowance_amount'); ?>
						<? //$form->hiddenField($model, 'bkg_is_nightpickupinclude'); ?>
						<? //$form->hiddenField($model, 'bkg_is_nightdropoffinclude'); ?>


<!--						<input type="hidden" name="Booking[bkg_is_day_driver_allowance_amount]" id="Booking_bkg_is_day_driver_allowance_amount" value="">
	<input type="hidden" name="Booking[bkg_is_night_driver_allowance_amount]" id="Booking_bkg_is_night_driver_allowance_amount" value="">
	<input type="hidden" name="Booking[bkg_is_nightpickup_driver_allowance_amount]" id="Booking_bkg_is_nightpickup_driver_allowance_amount" value="">
	<input type="hidden" name="Booking[bkg_is_nightdropoff_driver_allowance_amount]" id="Booking_bkg_is_nightdropoff_driver_allowance_amount" value="">
						-->

						<?
						$toll_checked	 = ($invModel->bkg_is_toll_tax_included == 1) ? 'checked="checked"  disabled="disabled"' : "";
						$state_checked	 = ($invModel->bkg_is_state_tax_included == 1) ? 'checked="checked" disabled="disabled"' : "";
						$parking_checked = ($invModel->bkg_is_parking_included == 1) ? 'checked="checked" disabled="disabled"' : "";
						//	$isDayAllowence = ($model->bkg_is_day_driver_allowance_amount == 1) ? 'checked="checked" disabled="disabled"' : "";
						//	$isNightAllowence = ($model->bkg_is_night_driver_allowance_amount == 1) ? 'checked="checked" disabled="disabled"' : "";
						//$isNightPickupAllowence = ($model->bkg_is_nightpickupinclude == 1) ? 'checked="checked" disabled="disabled"' : "";
						//$isNightDropoffAllowence = ($model->bkg_is_nightdropoffinclude == 1) ? 'checked="checked" disabled="disabled"' : "";
						?>

						<div class="panel-body pt0">

							<div class="row">
								<div class="col-sm-6" >
									<?= $form->textFieldGroup($invModel, 'bkg_rate_per_km_extra', array('widgetOptions' => array())) ?>                                   
								</div>
								<div class="col-sm-6" id="div_rate_per_km"  style="display: none">
									<?= $form->textFieldGroup($invModel, 'bkg_rate_per_km', array('widgetOptions' => array())) ?>
									<div id="errordivrate" class="mt5 " style="color:#da4455"></div>
								</div>
								<div class="col-sm-6">
									<?
									$readonly		 = [];
									if (!Yii::app()->user->checkAccess('accountEdit'))
									{
										$readonly = ['readonly' => 'readonly'];
									}
									?>
									<?= $form->textFieldGroup($invModel, 'bkg_base_amount', array('label' => 'Amount', 'widgetOptions' => array('htmlOptions' => ['placeholder' => 'Net Charge'] + $readonly))) ?>
									<div id="trip_rate"></div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-6">
									<?= $form->textFieldGroup($invModel, 'bkg_additional_charge_remark', array('widgetOptions' => array('htmlOptions' => []))) ?>
								</div>
								<div class="col-sm-6">
									<?= $form->textFieldGroup($invModel, 'bkg_additional_charge', array('widgetOptions' => array('htmlOptions' => []))) ?>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-6">
									<?
									$agentDisable					 = ($model->bkg_agent_id > 0) ? ['readonly' => 'readonly'] : [];
									?>
									<?= $form->textFieldGroup($invModel, 'bkg_promo1_code', array('label' => 'Promo Code', 'widgetOptions' => array('htmlOptions' => ['placeholder' => 'Promo Code'] + $agentDisable))) ?>
									<span class="text-danger" id="promocreditsucc"></span>
								</div>
								<div class="col-sm-6">
									<label class="control-label" for="exampleInputName6">Discount</label>
									<?= $form->textFieldGroup($invModel, 'bkg_discount_amount', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['readonly' => 'readonly', 'placeholder' => 'Discount']))) ?>
								</div>
							</div>
							<div class="row"> 
								<div class="col-sm-6">

								</div>
								<input type="hidden" name="rtevndamt" id="rtevndamt">
								<?= $form->hiddenField($invModel, 'bkg_quoted_vendor_amount'); ?>

								<div class="col-sm-6  ">
									<?= $form->textFieldGroup($invModel, 'bkg_vendor_amount', array('widgetOptions' => array('htmlOptions' => ['readonly' => 'readonly']))) ?>
									<? //$form->textFieldGroup($model, 'bkg_driver_allowance_amount', array('label' => ' Driver Allowance', 'widgetOptions' => array('htmlOptions' => ['placeholder' => 'Day Driver allowance']))) ?>
								</div>
							</div>
							<!--							 <div class="row"> 
															<div class="col-sm-6">
																<span >Driver Allowance</span> <br />
															Day     <span class="checkeDayAllowance  pt20"><input type="checkbox" name="bkg_is_day_driver_allowance_amount1" id="Booking_bkg_is_day_driver_allowance_amount1" <? //$isDayAllowence                 ?>></span>
															Night   <span class="checkeNightAllowance  pt20"><input type="checkbox" name="bkg_is_night_driver_allowance_amount1" id="Booking_bkg_is_night_driver_allowance_amount1" <? // $isNightAllowence                 ?>></span>	
															
															</div>
															<div class="col-sm-6  ">
							<? //$form->numberFieldGroup($model, 'bkg_day_driver_allowance_amount', array('label' => 'Day Driver Allowance', 'widgetOptions' => array('htmlOptions' => ['placeholder' => 'Day Driver allowance'])));  ?>
																
															</div>
														</div>-->
							<div class="row"> 
								<div class="col-sm-6  pt20">
									Night Pickup <span class="checkeNightPickupAllowance"><input type="checkbox" name="bkg_night_pickup_included" id="Booking_bkg_night_pickup_included1" <?= $isNightPickupAllowence ?>></span>
									Night Drop Off <span class="checkeNightDropOffAllowance"><input type="checkbox" name="bkg_night_drop_included" id="Booking_bkg_night_drop_included1" <?= $isNightDropoffAllowenceAllowence ?>></span>	

									<?= $form->hiddenField($invModel, 'bkg_night_pickup_included'); ?>
									<?= $form->hiddenField($invModel, 'bkg_night_drop_included'); ?>



								</div>
								<div class="col-sm-6  ">
									<? // $form->numberFieldGroup($model, 'bkg_night_driver_allowance_amount', array('label' => 'Night Driver Allowance', 'widgetOptions' => array('htmlOptions' => ['placeholder' => 'Night Driver allowance'])));  ?>
									<?= $form->textFieldGroup($invModel, 'bkg_driver_allowance_amount', array('label' => 'Driver Allowance', 'widgetOptions' => array('htmlOptions' => ['placeholder' => 'Driver allowance', 'oldamount' => 0]))); ?>
								</div>
							</div>

							<div class="row">
								<div class="col-xs-6 pt20">Parking Included <span class="checkerparkingtax"><input type="checkbox" name="bkg_is_parking_included" id="Booking_bkg_is_parking_included" <?= $parking_checked ?>></span></div>
								<div class="col-sm-6">
									<?= $form->textFieldGroup($invModel, 'bkg_parking_charge', array('widgetOptions' => array('htmlOptions' => array('readonly' => 'readonly', 'plceholder' => 'Parking')))) ?>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-6 pt20">Toll tax Included <span class="checkertolltax"><input type="checkbox" name="bkg_is_toll_tax_included1" id="Booking_bkg_is_toll_tax_included1" <?= $toll_checked ?>></span></div>
								<div class="col-sm-6">
									<?= $form->textFieldGroup($invModel, 'bkg_toll_tax', array('widgetOptions' => array('htmlOptions' => array('readonly' => 'readonly', 'plceholder' => 'Toll Tax')))) ?>
								</div>
							</div>

							<div class="row">
								<div class="col-sm-6 pt20">
									State tax Included <span class="checkerstatetax"><input type="checkbox" name="bkg_is_state_tax_included1" id="Booking_bkg_is_state_tax_included1" <?= $state_checked ?>></span>

								</div>
								<div class="col-sm-6">
									<?= $form->textFieldGroup($invModel, 'bkg_state_tax', array('widgetOptions' => array('htmlOptions' => array('readonly' => 'readonly', 'plceholder' => 'State Tax')))) ?>
								</div>
							</div>

							<?
//  if ($model->bkg_advance_amount == '' || $model->bkg_advance_amount == 0) {
							?>
							<div class="row">
								<div class="col-sm-6">
								</div>
								<div class="col-sm-6">
									<?= $form->textFieldGroup($invModel, 'bkg_convenience_charge', array('label' => 'Collect on delivery(COD) fee', 'widgetOptions' => array('htmlOptions' => array('readonly' => 'readonly')))) ?>                                 
								</div>
							</div>
							<?
							//$staxrate						 = Filter::getServiceTaxRate();
							$taxLabel						 = ($staxrate == 5) ? 'GST' : 'Service Tax ';
							?>

							<div class="row">
								<div class="col-sm-6">
								</div>
								<div class="col-sm-6 pull-right">
									<? $invModel->bkg_service_tax_rate	 = $staxrate; ?>
									<?= $form->hiddenField($invModel, 'bkg_service_tax_rate'); ?>
									<?= $form->textFieldGroup($invModel, 'bkg_service_tax', array('label' => "$taxLabel    (rate: " . $staxrate . '%)', 'widgetOptions' => array('htmlOptions' => array('readonly' => 'readonly')))) ?>
								</div>
							</div>


							<div class="row">
								<div class="col-sm-6">
									<div class="form-group">
										<label class="control-label" for="amountwithoutcod">Total Amount(Without COD)</label>
										<input readonly="readonly" class="form-control" name="amountwithoutcod" id="amountwithoutcod" type="text" value="0">
									</div> 
								</div>
								<div class="col-sm-6">
									<?= $form->textFieldGroup($invModel, 'bkg_total_amount', array('label' => 'Total Chargeable ' . $invModel->getAttributeLabel('bkg_total_amount'), 'widgetOptions' => array('htmlOptions' => array('readonly' => 'readonly')))) ?>
								</div>
							</div>
							<div class="row  hide" id="divAgentCredit">
								<div class="col-xs-6 pull-right">
									<?
									if ($model->agentCreditAmount == '')
									{
										$model->agentCreditAmount = 0;
									}
									?>
									<?= $form->numberFieldGroup($model, 'agentCreditAmount', array('label' => 'Amount paid by Agent', 'widgetOptions' => array('htmlOptions' => ['class' => 'form-control', 'placeholder' => "Agent Advance Credit", 'min' => 0]))) ?>
								</div> 
							</div>
							<div class="row hide" id="div_due_amount">
								<div class="col-xs-6 pull-right">
									<label class="text-info">Total Due Amount</label> 
									<div class="form-control" id="id_due_amount"><?= $invModel->bkg_due_amount ?></div>
								</div>
							</div>
							<?
							$tripdistance = ($model->bkg_trip_distance != '' && $model->bkg_trip_distance > 0) ? $model->bkg_trip_distance : 0;
							if ($tripdistance > 0)
							{
								if ($invModel->bkg_rate_per_km > 0)
								{
									$tripextrarate = "Note: Ext. Chrg. After " . $tripdistance . " Kms. = " . $invModel->bkg_rate_per_km . "/Km.";
								}
							}
							?>
							<div class="row" id="vehicle_dist_ext"><?= $tripextrarate ?>  </div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-5">
			<div class="row">
				<div class="col-xs-12">
					<div class="panel panel-default panel-border">
						<h3 class="pl15">Personal Information</h3>
						<div class="panel-body pt0">
							<div class="row">
								<div class="col-sm-6">
									<label class="control-label" for="exampleInputName6">Contact Number</label>
									<div class="row">
										<div class="col-sm-4">
											<div class="form-group">
												<?php
												$this->widget('ext.yii-selectize.YiiSelectize', array(
													'model'				 => $usrModel,
													'attribute'			 => 'bkg_country_code',
													'useWithBootstrap'	 => true,
													"placeholder"		 => "Code",
													'fullWidth'			 => false,
													'htmlOptions'		 => array(
													),
													'defaultOptions'	 => array(
														'create'			 => false,
														'persist'			 => true,
														'selectOnTab'		 => true,
														'createOnBlur'		 => true,
														'dropdownParent'	 => 'body',
														'optgroupValueField' => 'id',
														'optgroupLabelField' => 'pcode',
														'optgroupField'		 => 'pcode',
														'openOnFocus'		 => true,
														'labelField'		 => 'pcode',
														'valueField'		 => 'pcode',
														'searchField'		 => 'name',
														//   'sortField' => 'js:[{field:"order",direction:"asc"}]',
														'closeAfterSelect'	 => true,
														'addPrecedence'		 => false,
														'onInitialize'		 => "js:function(){
																this.load(function(callback){
																var obj=this;                                
																 xhr=$.ajax({
														 url:'" . CHtml::normalizeUrl(Yii::app()->createUrl('index/country')) . "',
														 dataType:'json',                  
														 success:function(results){
															 obj.enable();
															 callback(results.data);
															  obj.setValue('{$usrModel->bkg_country_code}');
														 },                    
														 error:function(){
															 callback();
														 }});
														});
														}",
														'render'			 => "js:{
															option: function(item, escape){                      
															return '<div><span class=\"\">' + escape(item.name) +'</span></div>';                          
													   },
														option_create: function(data, escape){
															return '<div>' +'<span class=\"\">' + escape(data.pcode) + '</span></div>';
														   }
														}",
													),
												));
												?>
											</div>
										</div>
										<div class="col-sm-8">
											<div class="form-group">
												<?= $form->textFieldGroup($usrModel, 'bkg_contact_no', array('label' => '', 'widgetOptions' => array('class' => '', 'htmlOptions' => array('onchange' => 'showlinkedUser()')))) ?>
												<div id="errordivmob" style="color:#da4455"></div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-6">
									<label class="control-label" for="exampleInputName6">Alternate Contact Number</label>
									<div class="row">
										<div class="col-sm-4">
											<div class="form-group">
												<?php
												$this->widget('ext.yii-selectize.YiiSelectize', array(
													'model'				 => $usrModel,
													'attribute'			 => 'bkg_alt_country_code',
													'useWithBootstrap'	 => true,
													"placeholder"		 => "Code",
													'fullWidth'			 => false,
													'htmlOptions'		 => array(
													),
													'defaultOptions'	 => array(
														'create'			 => false,
														'persist'			 => true,
														'selectOnTab'		 => true,
														'createOnBlur'		 => true,
														'dropdownParent'	 => 'body',
														'optgroupValueField' => 'id',
														'optgroupLabelField' => 'pcode',
														'optgroupField'		 => 'pcode',
														'openOnFocus'		 => true,
														'labelField'		 => 'pcode',
														'valueField'		 => 'pcode',
														'searchField'		 => 'name',
														'closeAfterSelect'	 => true,
														'addPrecedence'		 => false,
														'onInitialize'		 => "js:function(){
																this.load(function(callback){
																var obj=this;                                
																 xhr=$.ajax({
														 url:'" . CHtml::normalizeUrl(Yii::app()->createUrl('index/country')) . "',
														 dataType:'json',                  
														 success:function(results){
															 obj.enable();
															 callback(results.data);
															 obj.setValue('{$usrModel->bkg_alt_country_code}');

														 },                    
														 error:function(){
															 callback();
														 }});
														 });
														 }",
														'render'			 => "js:{
															option: function(item, escape){
															return '<div><span class=\"\">' + escape(item.name) +'</span></div>';
															},
															option_create: function(data, escape){
															return '<div>' +'<span class=\"\">' + escape(data.pcode) + '</span></div>';
														}}",
													),
												));
												?>
											</div>
										</div>
										<div class="col-sm-8">
											<div class="form-group">
												<?= $form->textFieldGroup($usrModel, 'bkg_alt_contact_no', array('label' => '', 'widgetOptions' => array())) ?>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-6">
									<?= $form->emailFieldGroup($usrModel, 'bkg_user_email', array('label' => 'Email', 'widgetOptions' => array('htmlOptions' => array('class' => '', 'onchange' => 'showlinkedUser()')))); ?>
									<div id="errordivemail" style="color:#da4455"></div>
								</div>
								<div class="col-xs-12" id="linkedusers">

								</div>
							</div>
							<div class="row">
								<div class="col-sm-6">
									<?= $form->textFieldGroup($usrModel, 'bkg_user_fname', array('label' => "First Name", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'First Name','class'=>'nameFilterMask')))) ?>
								</div>
								<div class="col-sm-6">
									<?= $form->textFieldGroup($usrModel, 'bkg_user_lname', array('label' => 'Last Name', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Last Name','class'=>'nameFilterMask')))) ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12">
					<div class="panel panel-default panel-border">
						<h3 class="pl15">ADDITIONAL INFORMATION FOR VENDOR/DRIVER</h3>
						<p></p>
						<div class="panel-body pt0">
							<div class="row">
								<!--								<div class="col-sm-12">
								<? //= $form->textAreaGroup($model, 'bkg_remark', array('label' => 'Enter booking remarks', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'booking remarks are internal to Gozo. These are not shared with Agent, vendor or driver',)))) ?>
																</div>-->
								<div class="col-sm-12">
									<?= $form->textAreaGroup($model, 'bkg_instruction_to_driver_vendor', array('label' => 'Instructions to Vendor/Driver', 'widgetOptions' => array('htmlOptions' => array('style' => 'min-height:90px', 'placeholder' => 'Add customer requirements in customer special requests section. In this box, write instructions that will be sent to vendor and driver ONLY.')))) ?>
								</div>
								<div class="col-sm-12">
									<?= $form->checkboxGroup($prfModel, 'bkg_invoice', array('widgetOptions' => array('htmlOptions' => []))) ?>
									<?= $form->checkboxGroup($prfModel, 'bkg_trip_otp_required', array('widgetOptions' => array('htmlOptions' => []))) ?>
									<?= $form->checkboxGroup($prfModel, 'bkg_driver_app_required', array('widgetOptions' => array('htmlOptions' => ['checked' => 'checked']))) ?>
									<?= $form->checkboxGroup($prfModel, 'bkg_duty_slip_required', array('widgetOptions' => array('htmlOptions' => []))) ?>
									<?= $form->checkboxGroup($prfModel, 'bkg_water_bottles_required', array('widgetOptions' => array('htmlOptions' => []))) ?>
									<?= $form->checkboxGroup($prfModel, 'bkg_is_cash_required', array('widgetOptions' => array('htmlOptions' => []))) ?>
									<?= $form->checkboxGroup($prfModel, 'bkg_pref_other', array('label' => 'Other instructions', 'widgetOptions' => array('htmlOptions' => []))) ?>

<!--	<input name="BookingPref[bkg_driver_app_required]" id="BookingPref_bkg_driver_app_required" value="1" type="checkbox"><span class="mr15">Use of Driver app is required</span>					
	<input name="BookingPref[bkg_duty_slip_required]" id="BookingPref_bkg_duty_slip_required" value="1" type="checkbox"><span class="mr15">All receipts & duty slips required</span>
	<input name="BookingPref[bkg_water_bottles_required]" id="BookingPref_bkg_water_bottles_required" value="1" type="checkbox"><span class="mr15">2x 500ml water bottles required</span>					
	<input name="BookingPref[bkg_is_cash_required]" id="BookingPref_bkg_is_cash_required" value="1" type="checkbox"><span class="mr15">Do not ask customer for cash</span>-->

<!--<input name="BookingPref[bkg_pref_other]" id="BookingPref_bkg_pref_other" value="1" type="checkbox"><span class="mr15">Other instructions</span>-->
									<span id="othprefreq" style="display:none;">
										<?= $form->textAreaGroup($prfModel, 'bkg_pref_req_other', array('label' => 'Other instructions', 'widgetOptions' => array('htmlOptions' => ['placeholder' => '']))) ?>

<!--<textarea class="form-control" name="BookingPref[bkg_pref_req_other]" id="bkg_pref_req_other" value=""></textarea>-->
									</span>
									</br>		
								</div>
							</div>
							<div class="row ">
								<div class="col-xs-12 special_request">
									<h3>SPECIAL REQUESTS BY CUSTOMER</h3>
									<p class="mb0">(NOTE: Enter all customer special requests here. there will be shown to customer and also sent to vendor)</p>
									<div class="col-xs-12 pl0">
										<?= $form->checkboxGroup($addInfoModel, 'bkg_spl_req_senior_citizen_trvl', []) ?>
										<?= $form->checkboxGroup($addInfoModel, 'bkg_spl_req_kids_trvl', []) ?>
										<?= $form->checkboxGroup($addInfoModel, 'bkg_spl_req_woman_trvl', []) ?>
										<?= $form->checkboxGroup($addInfoModel, 'bkg_spl_req_carrier', []) ?>
										<?= $form->checkboxGroup($addInfoModel, 'bkg_spl_req_driver_hindi_speaking', []) ?>
										<?= $form->checkboxGroup($addInfoModel, 'bkg_spl_req_driver_english_speaking', []) ?>
										<?= $form->checkboxGroup($model, 'bkg_chk_others', ['label' => 'Others']) ?>
										<div id="othreq" style="display: none">
											<?= $form->textFieldGroup($addInfoModel, 'bkg_spl_req_other', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Other Requests"]), 'groupOptions' => ['class' => 'm0'])) ?>  
										</div>
										<?= $form->checkboxGroup($model, 'bkg_add_my_trip', ['label' => 'I Will Take Journesy Break', 'widgetOptions' => ['htmlOptions' => ['checked' => "checked"]]]) ?>
										<?= $form->dropDownListGroup($addInfoModel, 'bkg_spl_req_lunch_break_time', ['label' => '', 'widgetOptions' => ['data' => ['0' => '15 minutes (Included Free)', '30' => '30', '60' => '60', '90' => '90', '120' => '120', '150' => '150', '180' => '180'], 'htmlOptions' => []]]) ?>
									</div> 
								</div>
							</div>
							<h3>CUSTOMER PREFERENCES</h3>
							<!--							<div class="row">
															<div class="col-xs-12"><label class="control-label" style="text-align: left;" for="exampleInputName6">Booking Preference</label></div>
														</div>-->

							<div class="row">
								<div class="col-xs-12"> 
									<div class="form-group"> 
										<label >Add Tags</label>
										<?php
										$SubgroupArray2	 = Booking::model()->getTags() + [0 => ''];
										$this->widget('booster.widgets.TbSelect2', array(
											'name'			 => 'bkg_tags',
											'model'			 => $model,
											'data'			 => $SubgroupArray2,
											// 'value' => explode(',', $model->bkg_tags),
											'htmlOptions'	 => array(
												'multiple'		 => 'multiple',
												'placeholder'	 => 'Add keywords that you may use to search for this booking later',
												'width'			 => '100%',
												'style'			 => 'width:100%',
											),
										));
										?>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group">
										<label class="control-label" style="text-align: left;" for="exampleInputName6"><nobr>How did you hear about Gozo cabs?</nobr></label>
										<?php
										$datainfo		 = VehicleTypes::model()->getJSON($infosource);
										$this->widget('booster.widgets.TbSelect2', array(
											'model'			 => $addInfoModel,
											'attribute'		 => 'bkg_info_source',
											'val'			 => "'" . $addInfoModel->bkg_info_source . "'",
											'asDropDownList' => FALSE,
											'options'		 => array('data' => new CJavaScriptExpression($datainfo)),
											'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Infosource')
										));
										?>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group">
										<?= $form->textFieldGroup($addInfoModel, 'bkg_flight_no', array('label' => 'Flight Number', 'widgetOptions' => array('htmlOptions' => array()))) ?>
									</div>
								</div>
								<? $sourceDescShow	 = ($addInfoModel->bkg_info_source == 5 || $addInfoModel->bkg_info_source == 6) ? '' : 'hide'; ?>
								<div class="col-sm-6 <?= $sourceDescShow ?>" id="source_desc_show">
									<div class="form-group">
										<label class="control-label" for="type">&nbsp;</label>
										<?= $form->textFieldGroup($addInfoModel, 'bkg_info_source_desc', array('label' => "", 'widgetOptions' => array('htmlOptions' => array('placeholder' => '')))) ?>										
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-6">
									<label class="control-label" for="exampleInputName6"></label>
									<?= $form->fileFieldGroup($model, 'fileImage', array('label' => '', 'widgetOptions' => array('htmlOptions' => []))) ?>
								</div>
								<div class="col-sm-6">
									<label class="control-label" for="exampleInputName6"></label>
									<?= $form->checkboxGroup($prfModel, 'bkg_tentative_booking', array('widgetOptions' => array('htmlOptions' => []))) ?>
								</div>
							</div>

							<div class="row">
								<label for="inputEmail" class="control-label col-xs-5">Customer Type</label>
								<div class="col-xs-7">
									<?=
									$form->radioButtonListGroup($addInfoModel, 'bkg_user_trip_type', array(
										'label'			 => '', 'widgetOptions'	 => array(
											'data' => Booking::model()->userTripList
										), 'inline'		 => true,)
									);
									?>
								</div>
							</div>
							<div class="row">
								<label for="inputEmail" class="control-label col-xs-5">Send me booking confirmations by</label>
								<div class="col-xs-7">
									<label class="checkbox-inline pt0">
										<?= $form->checkboxGroup($prfModel, 'bkg_send_email', ['label' => 'Email']) ?>
									</label>
									<label class="checkbox-inline pt0">
										<?= $form->checkboxGroup($prfModel, 'bkg_send_sms', ['label' => 'Phone']) ?>
									</label>
								</div>
							</div>

							<div class="row mb5">
								<label for="inputEmail" class="control-label col-xs-5">Number of Passengers</label>
								<div class="col-xs-7">
									<?= $form->numberFieldGroup($addInfoModel, 'bkg_no_person', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Number of Passengers", 'min' => 1, 'max' => 10]), 'groupOptions' => ['class' => 'm0'])) ?>                      
								</div>
							</div>
							<div class="row mb5">
								<label for="inputEmail" class="control-label col-xs-5">Number of large suitcases</label>
								<div class="col-xs-7">
									<?= $form->numberFieldGroup($addInfoModel, 'bkg_num_large_bag', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Number of large suitcases", 'min' => 0, 'max' => 10]), 'groupOptions' => ['class' => 'm0'])) ?>                      
								</div>
							</div>
							<div class="row mb5">
								<label for="inputEmail" class="control-label col-xs-5">Number of small bags</label>
								<div class="col-xs-7">
									<?= $form->numberFieldGroup($addInfoModel, 'bkg_num_small_bag', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Number of small bags", 'min' => 0, 'max' => 10]), 'groupOptions' => ['class' => 'm0'])) ?>                      
								</div>
							</div>

							<div class="row ">
								<!--								<div class="col-xs-6">
								<? //= $form->numberFieldGroup($model, 'bkg_pickup_pincode', array('label' => 'Pickup Address Pin Code', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Pincode", 'min' => 100000, 'max' => 999999]), 'groupOptions' => ['class' => 'm0'])) ?>  
																</div>
																<div class="col-xs-6">
								<? //= $form->numberFieldGroup($model, 'bkg_drop_pincode', array('label' => 'Drop Address Pin Code', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Pincode", 'min' => 100000, 'max' => 999999]), 'groupOptions' => ['class' => 'm0'])) ?>  
																</div>-->
							</div>

							<div class="row" id="<?= TbHtml::activeId($model, "bkgTrail") ?>" style="display: <? echo ($model->bkg_agent_id != '') ? 'none' : 'block' ?>">
								<div class="col-xs-12">Enter here the date and time by which customer will make payment: Remind customer that prices are rising and they need to make payment.</div>
								<div class="col-sm-6">

									<?=
									$form->datePickerGroup($model->bkgTrail, 'locale_followup_date', array('label'			 => 'Followup Date',
										'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'Followup Date')), 'prepend'		 => '<i class="fa fa-calendar"></i>'));
									?>
								</div>
								<div class="col-sm-6">
									<?=
									$form->timePickerGroup($model->bkgTrail, 'locale_followup_time', array('label'			 => 'Followup Time',
										'widgetOptions'	 => array('id' => CHtml::activeId($model->bkgTrail, "locale_followup_time"), 'options' => array('autoclose' => true), 'htmlOptions' => array('placeholder' => 'Reminder Time'))));
									?>
								</div>
								<div class="col-xs-12 has-error">
									<?= $form->error($model, "bkgTrail") ?>
									<?= $form->error($model, "bkgAddInfo") ?>
								</div>
							</div>


						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<input type="hidden" id="agentnotifydata" name="agentnotifydata" value='<?= json_encode($model->agentNotifyData); ?>'>
		<input type="hidden" id="createQuote" name="createQuote" value="0">
		<div class="col-xs-12 pb10" style="text-align:center;" id="bkgSubmitDiv1">
			<?= CHtml::submitButton('Submit', array('style' => 'font-size:1.4em;display:none;', 'class' => 'btn btn-primary btn-lg pl50 pr50', 'id' => 'btnsbmt', 'disabled' => 'disabled')); ?>

			<button type="button" class="btn btn-primary btn-lg pl50 pr50" style="font-size:1.4em;" id="btnQuote">Create Quote</button>
		</div>
	</div>
	<div id="driver1"></div>
	<?php $this->endWidget(); ?>
</div> 
<script type="text/javascript">

    var countSubmit = 0;
    $sourceList = null;
    var hyperModel = new HyperLocation();
    $(document).ready(function ()
    {
//$("#Booking_bkg_trip_distance").val(<? //$packages->                     ?>);

        jQuery('#Booking_bkg_pickup_date_date').datepicker({'autoclose': true, 'startDate': new Date(), 'format': 'dd/mm/yyyy', 'language': 'en'});
        jQuery('#Booking_bkg_pickup_date_time').timepicker({'defaultTime': false, 'autoclose': true});
        jQuery('#Booking_bkg_return_date_time').timepicker({'defaultTime': false, 'autoclose': true});
        $rate = 0;
        $dist = '';
        $time = '';
        $('.glyphicon').addClass('fa').removeClass('glyphicon');
        $('.glyphicon-time').addClass('fa-clock-o').removeClass('glyphicon-time');
        $isLeadLoad =<?= ($model->lead_id != "") ? "true" : "false" ?>;
        if ($isLeadLoad)
        {
            $("#Booking_bkg_booking_type").click();
        }
        $isCopyLoad =<?= ($_REQUEST['booking_id'] > 0) ? "true" : "false" ?>;
        if ($isCopyLoad)
        {
            $("#Booking_bkg_booking_type").change();
        }
        $('#ytBooking_bkg_add_my_trip').parent().parent().css('float', 'left');
        $('#BookingAddInfo_bkg_spl_req_lunch_break_time').parent().css('float', 'right');
        booking_type = $('#Booking_bkg_booking_type').val();
        transfer_type = 0;
//        initializepl(booking_type, transfer_type);
        if ($('#Booking_trip_user_1').is(':checked') == true)
        {
            $('#btnQuote').css('display', 'none');
            $('#btnsbmt').prop('disabled', false);
            $('#btnsbmt').css('display', 'inline-block');
            $('#btnQuote').prop('disabled', true);
        }
<?
if ($model->lead_id > 0 && $model->bkg_vehicle_type_id > 0)
{
	?>

	        updateLead();
	        getAmountbyCitiesnVehicle();

<? } ?>

<?
if ($_REQUEST['booking_id'] > 0 && $model->bkg_vehicle_type_id > 0)
{
	?>
	        getDiscount();
	        calculateAmount();
<? } ?>

        $(document).on('click', '#Booking_bkg_is_state_tax_included1', function ()
        {
            if ($('#Booking_bkg_is_state_tax_included1').is(':checked'))
            {
                $('#BookingInvoice_bkg_is_state_tax_included').val(1);
                $('#BookingInvoice_bkg_state_tax').removeAttr('readOnly');
            } else
            {
                $('#BookingInvoice_bkg_is_state_tax_included').val(0);
                $('#BookingInvoice_bkg_state_tax').attr('readOnly', 'readOnly');
                $('#BookingInvoice_bkg_state_tax').val(0).change();
            }
        });
        $(document).on('click', '#Booking_bkg_is_toll_tax_included1', function ()
        {
            if ($('#Booking_bkg_is_toll_tax_included1').is(':checked'))
            {
                $('#BookingInvoice_bkg_is_toll_tax_included').val(1);
                $('#BookingInvoice_bkg_toll_tax').removeAttr('readOnly');
            } else
            {
                $('#BookingInvoice_bkg_is_toll_tax_included').val(0);
                $('#BookingInvoice_bkg_toll_tax').attr('readOnly', 'readOnly');
                $('#BookingInvoice_bkg_toll_tax').val(0).change();
            }
        });
<?
if ($model->bkg_agent_id > 0)
{
	?>
	        var agent_id = '<?= $model->bkg_agent_id; ?>';
	        onAgentSelected(agent_id);
<? } ?>

	<?php if ($model->preData != '') { ?>
	        setHyperLocationData();
<?php } ?>

    });

    function setHyperLocationData()
    {
        var model = {};
        model.booking_type = '<?= $model->bkg_booking_type ?>';
        model.transfer_type = '0';
        model.ctyLat = <?= json_encode($ctyLat) ?>;
        model.ctyLon = <?= json_encode($ctyLon) ?>;
        model.bound = <?= json_encode($bound) ?>;
        model.isCtyAirport = <?= json_encode($isCtyAirport) ?>;
        model.isCtyPoi = <?= json_encode($isCtyPoi) ?>;
        model.hyperLocationClass = 'txtpl';
        hyperModel.model = model;
        hyperModel.initializepl();
    }

    function validateBooking()
    {
        var ck_email = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/;
        var primaryPhone = $('#BookingUser_bkg_contact_no').val();
        var email = $('#BookingUser_bkg_user_email').val();
        var ratepkm = $('#BookingInvoice_bkg_rate_per_km').val();
        var bkgtype = $('#Booking_bkg_booking_type').val();
        var select = $("#BookingUser_bkg_country_code").selectize({});
        var selectizeControl = select[0].selectize;
        var country_code = selectizeControl.getItem(selectizeControl.getValue()).text();
        error = 0;
        $("#errordivmob").text('');
        $("#errordivemail").text('');
        $("#errordivrate").text('');
        $("#errordivreturn").text('');
        $('#errordivemailcrp').text('');
        if (bkgtype == 2 && $('#Booking_bkg_return_date_date').val() == '')
        {
            error += 1;
            $("#errordivreturn").text('');
            $("#errordivreturn").text('Please enter Return Date and Time');
        }
        if ((primaryPhone == '' || primaryPhone == null) && (email == '' || email == null))
        {
            error += 1;
            $("#errordivmob").text('');
            $("#errordivemail").text('');
            $("#errordivmob").text('Please enter contact number or email address.');
        } else
        {
            if (primaryPhone != '')
            {
                if (country_code == '' || country_code == null)
                {
                    error += 1;
                    $("#errordivmob").text("Please select country code.");
                } else
                {
                    error += 0;
                    $("#errordivmob").text('');
                    $("#errordivemail").text('');
                }
            } else
            {
                if (email != '')
                {
                    if (!ck_email.test(email))
                    {
                        error += 1;
                        $("#errordivmob").text('');
                        $("#errordivemail").text('');
                        $("#errordivemail").text('Invalid email address');
                    }
                }
            }
        }


        if ($('#BookingInvoice_bkg_total_amount').val() <= 0 || $('#BookingInvoice_bkg_total_amount').val() == '' || $('#BookingInvoice_bkg_total_amount').val() == 'undefined')
        {
            error += 1;
            alert("Total chargeable amount is mandatory");
        }

        if ($('#Booking_bkg_vehicle_type_id').val() <= 0 || $('#Booking_bkg_vehicle_type_id').val() == '' || $('#Booking_bkg_vehicle_type_id').val() == 'undefined' || $('#Booking_bkg_vehicle_type_id').val() == null || $('#Booking_bkg_vehicle_type_id').val() == 'null')
        {
            error += 1;
            alert("Please select vehicle type.");
        }
        var trip_user = $("input[name=\'Booking[trip_user]\']:checked").val();
//        if (trip_user == 3 && ($('#corporate_id').val() == '' || $('#corporate_id').val() == null || $('#corporate_id').val() == 'undefined')) {
//            error += 1;
//            alert("Please select corporate.");
//        }

        if ((trip_user == 2) && ($('#bkg_agent_id').val() == '' || $('#bkg_agent_id').val() == null || $('#bkg_agent_id').val() == 'undefined'))
        {
            error += 1;
            alert("Link to Channel Partner.");
        }

        $('#mobcopybooking').html("");
        var val = $('#Booking_bkg_copybooking_phone').val();
        if (val != '' && val != null && val != "" && val != undefined)
        {
            if (/^\d{10}$/.test(val))
            {
                // value is ok, use it
            } else
            {
                error += 1;
                $('#mobcopybooking').html("Number must be of 10 digit.");
                $('#Booking_bkg_copybooking_phone').focus();
            }
        }

        if ($('#uniform-Booking_bkg_copybooking_issms>span').hasClass('checked'))
        {
            $('input[name="Booking[bkg_copybooking_issms]"]').val(1).trigger('change');
        } else
        {
            $('input[name="Booking[bkg_copybooking_issms]"]').val(0).trigger('change');
        }

        if (error > 0)
        {
            return false;
        }

        return true;
    }
    $("#Booking_bkg_pickup_date_date").change(function ()
    {
<?php
if ($package)
{
	?>
	        assignPackageDt();
<? } ?>
        getRoute();
        getDiscount();
        $("#errordivpdate").text('');
    });
    $('#<?= CHtml::activeId($model, "bkg_chk_others") ?>').change(function ()
    {
        if ($('#<?= CHtml::activeId($model, "bkg_chk_others") ?>').is(':checked'))
        {
            $("#othreq").show();
        } else
        {
            $("#othreq").hide();
        }
    });
//    $('#corporate_id').change(function () {
//        getAgentDetails($("#corporate_id").select2("val"));
//        getAgentBaseDiscFare();
//        calculateAmount();
//    });
    $('#bkg_agent_id').change(function ()
    {
        var agtId = $("#bkg_agent_id").select2("val");
        getBookingPreferences(agtId);
        onAgentSelected(agtId);
        getAmountbyCitiesnVehicle();

    });
    function getBookingPreferences(agtId)
    {
        //alert(agtId);
        //if (agtId != '' && agtId != null)
        //{
        jQuery.ajax({type: 'GET',
            url: '<?= Yii::app()->createUrl('admin/agent/bookingpreferences') ?>',
            dataType: 'json',
            data: {"agt_id": agtId},
            async: true,
            success: function (data)
            {
                //alert(data.preferences.agt_otp_required);
                var otpreq = data.preferences.agt_otp_required;
                var appreq = data.preferences.agt_driver_app_required;
                var botreq = data.preferences.agt_water_bottles_required;
                var cashreq = data.preferences.agt_is_cash_required;
                var slipreq = data.preferences.agt_duty_slip_required;
                var otherreq = data.preferences.agt_pref_req_other;
                //alert(otherreq);
                //console.log(x);
                if (otpreq == 1) {
                    $('input:checkbox[name="BookingPref[bkg_trip_otp_required]"]').attr('checked', 'checked');
                    $('div#uniform-BookingPref_bkg_trip_otp_required span').addClass('checked');
                    var pref1 = '<i class="fa fa-lg fa-check-square-o"></i> OTP is required';
                    $('#divpref1').html(pref1);
                }
                if (otpreq == 0) {
                    $('input:checkbox[name="BookingPref[bkg_trip_otp_required]"]').attr('checked', 'false');
                    $('div#uniform-BookingPref_bkg_trip_otp_required span').removeClass('checked');
                    var pref1 = '<i class="fa fa-lg fa-square-o"></i> OTP is required';
                    $('#divpref1').html(pref1);
                }
                if (appreq == 1) {
                    $('input:checkbox[name="BookingPref[bkg_driver_app_required]"]').attr('checked', 'checked');
                    $('div#uniform-BookingPref_bkg_driver_app_required span').addClass('checked');
                    var pref2 = '<i class="fa fa-lg fa-check-square-o"></i> Use of Driver app is required';
                    $('#divpref2').html(pref2);
                }
                if (appreq == 0) {
                    $('input:checkbox[name="BookingPref[bkg_driver_app_required]"]').attr('checked', 'false');
                    $('div#uniform-BookingPref_bkg_driver_app_required span').removeClass('checked');
                    var pref2 = '<i class="fa fa-lg fa-square-o"></i> Use of Driver app is required';
                    $('#divpref2').html(pref2);
                }
                if (botreq == 1) {
                    $('input:checkbox[name="BookingPref[bkg_water_bottles_required]"]').attr('checked', 'checked');
                    $('div#uniform-BookingPref_bkg_water_bottles_required span').addClass('checked');
                    var pref3 = '<i class="fa fa-lg fa-check-square-o"></i> 2x 500ml water bottles required';
                    $('#divpref3').html(pref3);
                }
                if (botreq == 0) {
                    $('input:checkbox[name="BookingPref[bkg_water_bottles_required]"]').attr('checked', 'false');
                    $('div#uniform-BookingPref_bkg_water_bottles_required span').removeClass('checked');
                    var pref3 = '<i class="fa fa-lg fa-square-o"></i> 2x 500ml water bottles required';
                    $('#divpref3').html(pref3);
                }
                if (cashreq == 1) {
                    $('input:checkbox[name="BookingPref[bkg_is_cash_required]"]').attr('checked', 'checked');
                    $('div#uniform-BookingPref_bkg_is_cash_required span').addClass('checked');
                    var pref4 = '<i class="fa fa-lg fa-check-square-o"></i> Do not ask customer for cash';
                    $('#divpref4').html(pref4);
                }
                if (cashreq == 0) {
                    $('input:checkbox[name="BookingPref[bkg_is_cash_required]"]').attr('checked', 'false');
                    $('div#uniform-BookingPref_bkg_is_cash_required span').removeClass('checked');
                    var pref4 = '<i class="fa fa-lg fa-square-o"></i> Do not ask customer for cash';
                    $('#divpref4').html(pref4);
                }
                if (slipreq == 1) {
                    $('input:checkbox[name="BookingPref[bkg_duty_slip_required]"]').attr('checked', 'checked');
                    $('div#uniform-BookingPref_bkg_duty_slip_required span').addClass('checked');
                    var pref5 = '<i class="fa fa-lg fa-check-square-o"></i> All receipts & duty slips required';
                    $('#divpref5').html(pref5);
                }
                if (slipreq == 0) {
                    $('input:checkbox[name="BookingPref[bkg_duty_slip_required]"]').attr('checked', 'false');
                    $('div#uniform-BookingPref_bkg_duty_slip_required span').removeClass('checked');
                    var pref5 = '<i class="fa fa-lg fa-square-o"></i> All receipts & duty slips required';
                    $('#divpref5').html(pref5);
                }
                if (otherreq != null) {
                    $('div#uniform-BookingPref_bkg_pref_other span').addClass('checked');
                    $("#BookingPref_bkg_pref_req_other").text(data.preferences.agt_pref_req_other);
                    $("#bkg_pref_req_other1").text(data.preferences.agt_pref_req_other);
                    var pref6 = '<i class="fa fa-lg fa-check-square-o"></i> Other instructions:';
                    $('#divpref6').html(pref6);
                    $('#othprefreq').show();
						}if(otherreq == null){
                    $('input:checkbox[name="BookingPref[bkg_pref_other]"]').attr('checked', 'false');
                    $('div#uniform-BookingPref_bkg_pref_other span').removeClass('checked');
                    $("#BookingPref_bkg_pref_req_other").text(data.preferences.agt_pref_req_other);
                    $("#bkg_pref_req_other1").text(data.preferences.agt_pref_req_other);
                    var pref6 = '<i class="fa fa-lg fa-square-o"></i> Other instructions:';
                    $('#divpref6').html(pref6);
                    $('#othprefreq').hide();
                }

            },
            error: function (x)
            {
                alert(x);
            }
        });
        //}
    }
    $('input:checkbox[name="BookingPref[bkg_pref_other]"]').change(function ()
    {
        if ($('input:checkbox[name="BookingPref[bkg_pref_other]"]').is(':checked'))
        {
            $("#othprefreq").show();
        } else
        {
            $("#othprefreq").hide();
        }
    });
    function onAgentSelected(agtId)
    {
        getAgentDetails(agtId);
        getAgentBaseDiscFare();
        calculateAmount();
        $('#corp_addt_details').addClass('hide');
        if ($('#agt_type').val() == 1)
        {
            $('#corp_addt_details').removeClass('hide');
        }

        var trip_user = $("input[name=\'Booking[trip_user]\']:checked").val();
        if ((trip_user == 2) && $('#agt_type').val() != 1 && $('#bkg_agent_id').val() != '' && $('#bkg_agent_id').val() != null && $('#bkg_agent_id').val() != undefined && $('#bkg_agent_id').val() != '0' && $('#bkg_agent_id').val() != 0)
        {
            var totalAmount = parseInt(Math.round($('#Booking_bkg_total_amount').val()));
            $('#Booking_agentCreditAmount').val(totalAmount);
            $('#div_due_amount').removeClass('hide');
            $('#id_due_amount').html(0);
        }

    }

    function updateLead()
    {
        $href = "<?= Yii::app()->createUrl('admin/booking/convertdata') ?>";
        jQuery.ajax({type: 'GET', dataType: 'json', url: $href, async: false,
            success: function (data1)
            {
                updateMulticity(1, 1);
            },
            error: function (e)
            {
                //alert(e);
            }
        });
    }
    $("#Booking_bkg_from_city_id").change(function ()
    {
        getRoute();
    });
    $("#Booking_bkg_to_city_id").change(function ()
    {
        getRoute();
    });

    $("#Booking_bkg_booking_type").change(function ()
    {
        getRoute();
    });
    $("#BookingInvoice_bkg_base_amount").change(function ()
    {
        getDiscount();
        calculateAmount();
    });
    $("#BookingInvoice_bkg_additional_charge").change(function ()
    {
        calculateAmount();
    });
    $("#BookingInvoice_bkg_discount_amount").change(function ()
    {
        getDiscount();
        calculateAmount();
    });

    $("#BookingAddInfo_bkg_info_source").change(function ()
    {
        var infosource = $("#BookingAddInfo_bkg_info_source").val();
        extraAdditionalInfo(infosource);
    });
    function extraAdditionalInfo(infosource)
    {
        $("#source_desc_show").addClass('hide');
        if (infosource == 21)
        {
            $("#BookingAddInfo_bkg_info_source_desc").val('');
            $("#source_desc_show").addClass('hide');
        } else
        {
            if (infosource == 5)
            {
                $("#source_desc_show").removeClass('hide');
                $("#BookingAddInfo_bkg_info_source_desc").attr('placeholder', "Friend's email please");
            } else if (infosource == 6)
            {
                $("#source_desc_show").removeClass('hide');
                $("#BookingAddInfo_bkg_info_source_desc").attr('placeholder', "");
            }
        }
    }

    $("#Booking_bkg_vehicle_type_id").change(function ()
    {
        getRoute();
        getDiscount();
    });
    $("#BookingInvoice_bkg_promo1_code").change(function ()
    {
        $("#BookingInvoice_bkg_discount_amount").val('');
        getDiscount();
    });
    $('#BookingInvoice_bkg_driver_allowance_amount').change(function ()
    {
        calculateAmount();
    });
    $('#BookingInvoice_bkg_toll_tax').change(function ()
    {
        calculateAmount();
    });
    $('#BookingInvoice_bkg_state_tax').change(function ()
    {
        calculateAmount();
    });
    $("#Booking_bkg_booking_type").click(function ()
    {

        var $bkgtype = $("#Booking_bkg_booking_type").val();
        $('#addmulticities').hide();
        $('#ctyinfo_bkg_type_1').hide();
        $('#address_div').hide();
        if ($bkgtype == '1' || $bkgtype == '9' || $bkgtype == '10' || $bkgtype == '11')
        {
            $isLeadLoad = false;
            $("#Booking_bkg_return_date_date").val('');
            $("#Booking_bkg_return_date_time").val('');
            $("#Booking_bkg_route").removeAttr('disabled');
            $('#ctyinfo_bkg_type_1').show();
            $('#addmulticities').hide();
            // $('.multicitydetrow').remove();
            $('#tripTablecreate').hide();
            $('#pickup_div').show();
            $('#address_div').show();
            $("#multicityjsondata").val('');
            $('.multicitydetrow').remove();
        }
        if ($bkgtype == '2')
        {
            if ($isLeadLoad)
            {
                $isLeadLoad = false;
                return;
            }
            $('#pickup_div').hide();
            $('#Booking_bkg_route').attr('disabled', 'disabled');
            $href = '<?= Yii::app()->createUrl('admin/booking/multicityform', ['bookingType' => '']) ?>' + $bkgtype;
            jQuery.ajax({type: 'GET', url: $href,
                success: function (data)
                {
                    multicitybootbox = bootbox.dialog({
                        message: data,
                        size: 'large',
                        title: 'Add pickup info',
                        onEscape: function ()
                        {
                            $('#addmulticities').show();
                            multicitybootbox.hide();
                            multicitybootbox.remove();
                        },
                    });
                    multicitybootbox.on('hidden.bs.modal', function (e)
                    {
                        $('body').addClass('modal-open');
                    });
                }});
        }



        if ($bkgtype == '3')
        {
            if ($isLeadLoad)
            {
                $isLeadLoad = false;
                return;
            }
            $('#pickup_div').hide();
            $('#Booking_bkg_route').attr('disabled', 'disabled');
            $href = '<?= Yii::app()->createUrl('admin/booking/multicityform', ['bookingType' => '']) ?>' + $bkgtype;
            jQuery.ajax({type: 'GET', url: $href, success: function (data)
                {

                    $('#multicityjsondata').val('');
                    multicitybootbox = bootbox.dialog({
                        message: data,
                        size: 'large',
                        title: 'Add pickup info',
                        onEscape: function ()
                        {
                            $('#addmulticities').show();
                            multicitybootbox.hide();
                            multicitybootbox.remove();
                        },
                    });
                    multicitybootbox.on('hidden.bs.modal', function (e)
                    {
                        $('body').addClass('modal-open');
                    });
                }
            });
        }
        // getRoute();
    });
    $('#Booking_bkg_route').bind("change", function ()
    {
        selctRoute();
    });
    $(document).on("getRouteListbyCities", function (event, data)
    {
        routeCitiesList(data);
    });
    function selctRoute()
    {
        var city = new City();
        var model = {};
        model.routeId = $("#Booking_bkg_route").val();
        if (model.routeId == "")
        {
            return;
        }
        city.model = model;
        city.getRouteListbyCities();
    }
    $fireChange = true;
    function routeCitiesList(data)
    {

        $fireChange = false;
        $("#Booking_bkg_from_city_id").val(data.data.fcity).change();
        $fireChange = true;
        $("#Booking_bkg_to_city_id").val(data.data.tcity).change();
    }
    $(document).on("getRouteList", function (event, data)
    {
<?php
if (!$package)
{
	?>
	        routeList(data);
<? } ?>
    });
    function getRoute()
    {

        if (!$fireChange)
        {
            return false;
        }

        var route = new Route();
        var model = {};
        model.fromCity = $("#Booking_bkg_from_city_id").val();
        model.toCity = $("#Booking_bkg_to_city_id").val();
        model.bookingType = $("#Booking_bkg_booking_type").val();
        model.pickupAddress = $("#Booking_bkg_pickup_address").val();
        model.dropAddress = $("#Booking_bkg_drop_address").val();
        model.pickupDate = $("#Booking_bkg_pickup_date_date").val();
        model.pickupTime = $("#Booking_bkg_pickup_date_time").val();
        route.model = model;
        if (model.fromCity != '' && model.toCity != '' && model.bookingType != '')
        {
            var preSCity = $("#preSCity").val();
            var preDCity = $("#preDCity").val();
            if (preSCity != model.fromCity && preDCity != model.toCity)
            {
                getAutoAddressBox();
                $("#preSCity").val(model.fromCity);
            }
            getAmountbyCitiesnVehicle();
//            route.getRouteList();
        }

    }

    function routeList(data)
    {
        //alert("puja");
        if (data.rutid > 0)
        {
            $("#Booking_bkg_route").val(data.data.rutid).change();
            $("#Booking_bkg_trip_distance").val(data.distance).change();
            $("#Booking_bkg_trip_duration").val(data.duration).change();
        } else
        {
            $("#Booking_bkg_route").val('').change();
            $("#Booking_bkg_trip_distance").val(data.data.distance).change();
            $("#Booking_bkg_trip_duration").val(data.data.duration).change();
        }
    }

    function getDiscount()
    {
        pdate = $("#Booking_bkg_pickup_date_date").val();
        ptime = $('#<?= CHtml::activeId($model, "bkg_pickup_date_time") ?>').val();
        if (pdate == '' && ptime == '')
        {
            $("#errordivpdate").text('');
            $("#errordivpdate").text('Please enter Pickupdate/Time');
        }
        if (pdate != '' && ($("#BookingInvoice_bkg_promo1_code").val() != '' || $("#BookingInvoice_bkg_discount_amount").val() != '' || $("#oldPromoCode").val() != '') && $("#BookingInvoice_bkg_base_amount").val() != '')
        {
            getDiscountbyCodenAmount($("#BookingInvoice_bkg_promo1_code").val(), $("#BookingInvoice_bkg_base_amount").val());
        }
    }

    function getDiscountbyCodenAmount(code, amount)
    {
        var promo = new Promo();
        var model = {};
        //model.userId = $("#Booking_bkg_user_id").val();

        model.pickupDate = $("#Booking_bkg_pickup_date_date").val();
        model.pickupTime = $('#<?= CHtml::activeId($model, "bkg_pickup_date_time") ?>').val();
        model.code = code;
        model.amount = amount;
        model.fromCityId = $("#Booking_bkg_from_city_id").val();
        model.toCityId = $("#Booking_bkg_to_city_id").val();
        model.email = $("#BookingUser_bkg_user_email").val();
        model.phone = $("#BookingUser_bkg_contact_no").val();
        model.oldCode = $("#oldPromoCode").val();
        model.carType = $('#Booking_bkg_vehicle_type_id').val();
        model.bookingType = $('#Booking_bkg_booking_type').val();

        promo.model = model;
        if ((code != '' || $("#oldPromoCode").val() != '') && amount > 0)
        {
//            $(document).on("getPromoCode", function (event, data) {
//                promoCode(data);
//            });
            promo.getPromoCode();
        } else if ($("#BookingInvoice_bkg_discount_amount").val() != '' && $("#BookingInvoice_bkg_promo1_code").val() != '')
        {
            $("#BookingInvoice_bkg_discount_amount").val('');
            $("#BookingInvoice_bkg_total_amount").val($("#BookingInvoice_bkg_base_amount").val());
        } else if ($("#BookingInvoice_bkg_discount_amount").val() != '' && $("#BookingInvoice_bkg_base_amount").val() != '')
        {
            calculateAmount();
        }
    }

//    function promoCode(data)
//    {
//        if (data.success) {
//            $("#BookingInvoice_bkg_discount_amount").val('');
//            if (data.data.discount > 0) {
//                $("#BookingInvoice_bkg_discount_amount").val(data.data.discount);
//            } else {
//                if (data.data.promoCredits > 0) {
//                    $('#promocreditsucc').html('Promo applied successfully.<br> User got Gozo Coins worth Rs.' + data.data.promoCredits + '.<br> He/She may redeem these Gozo Coins against his/her next bookings with us.');
//                    //     $('#promocreditsucc').delay(15000).fadeOut();
//                }
//                $("#BookingInvoice_bkg_discount_amount").val(0);
//            }
//            calculateAmount();
//        }
//		else
//			$("#BookingInvoice_bkg_discount_amount").val(data.data.discount);
//			calculateAmount();
//			alert("Invalid promo");
//		}
//    }

    function getAmountbyCitiesnVehicle()
    {
        var cntBrt = $(".txtpl").length;
        cntVal = cntBrt - 1;
        var booking = new Booking();
        var model = {};
        $userType = $("input[name='Booking[trip_user]']:checked").val();
        if ($userType == 2)
        {
            model.agentId = $('#bkg_agent_id').val();
        }
        routeData = [];
        if (cntBrt > 0)
        {
            var locLatVal = '';
            var locLonVal = '';
            var brtlocationVal = '';
            var brtAddLocationVal = '';
            for (g = 0; g < cntBrt; g++)
            {
                locLatx = "locLat_" + g;
                locLonx = "locLon_" + g;
                brt_locationx = "brt_location_" + g;
                brt_AddLocationx = "brt_additional" + g;
                locLatVal = $("." + locLatx).val();
                locLonVal = $("." + locLonx).val();
                brtlocationVal = $("." + brt_locationx).val();
                brtAddLocationVal = $("." + brt_AddLocationx).val();
                brtlocationVal = (brtAddLocationVal == undefined || brtAddLocationVal == '') ? brtlocationVal : brtAddLocationVal + ', ' + brtlocationVal;
                routeData[g] = {
                    "locLatVal": locLatVal,
                    "locLonVal": locLonVal,
                    "brtLocationVal": brtlocationVal};
            }
        }
        //alert("es");
        //alert($("#Booking_bkg_vehicle_type_id").val());
        model.fromCity = $("#Booking_bkg_from_city_id").val();
        model.toCity = $("#Booking_bkg_to_city_id").val();
        model.toCity = $("#Booking_bkg_to_city_id").val();
        model.cabType = $("#Booking_bkg_vehicle_type_id").val();
        model.sccClassType = $('input[name=serviceClass]:checked').val();
        model.tripDistance = $('#Booking_bkg_trip_distance').val();
        model.tripDuration = $('#Booking_bkg_trip_duration').val();
        model.multiCityData = $('#multicityjsondata').val();
        var additinalSourceAdd = $("#brt_additional0").val();
        var additinalDestAdd = $('#brt_additional' + cntVal).val();
        var brtSlocationVal = (additinalSourceAdd != '') ? additinalSourceAdd + ', ' : '';
        var brtDlocationVal = (additinalDestAdd != '') ? additinalDestAdd + ', ' : '';
        model.bookingType = $('#Booking_bkg_booking_type').val();
        model.pickupAddress = brtSlocationVal + $('.brt_location_0').val();
        model.dropupAddress = brtDlocationVal + $('.brt_location_' + cntVal).val();
        model.routeDataArr = routeData;
        model.pickupDate = $('#Booking_bkg_pickup_date_date').val();
        model.pickupTime = $('#Booking_bkg_pickup_date_time').val();
        model.YII_CSRF_TOKEN = $('input[name="YII_CSRF_TOKEN"]').val();
        model.pckageID = $("#pckageID").val();
        booking.model = model;
        if (model.fromCity != '' && model.toCity != '' && model.cabType != '')
        {
            $(document).on("getQoute", function (event, data)
            {
                getQoutation(data);
            });
            booking.getQoute();
        }
    }

    function getQoutation(data)
    {
        $("#errorShow").hide();
        $("#errorMsg").html('');
        if (data.data.quoteddata.success != true)
        {
            $("#errorShow").show();

            $("#errorMsg").html('Error : ' + data.data.quoteddata.errorText);
//            alert('Sorry! Your request can not be processed right now!Please try later.' + data.data.quoteddata.errorText);
            //return false;
        }
//var routeSurgeFlag;
        var qRouteRates = data.data.quoteddata.routeRates;
        //console.log(qRouteRates);
        var qRouteDistance = data.data.quoteddata.routeDistance;
        var qRouteDuration = data.data.quoteddata.routeDuration;
        var parking = qRouteRates.parkingAmount;
        var parkingInclude = qRouteRates.isParkingIncluded;
        var surgeFactorUsed = qRouteRates.surgeFactorUsed;
        var ddbpBaseAmount = qRouteRates.srgDDBP.rockBaseAmount;
        var dtbpBaseAmount = qRouteRates.srgDTBP.rockBaseAmount;
        var ddbpFactorType = qRouteRates.srgDDBP.refModel.dprApplied.type;
        var manualSurgeId = qRouteRates.srgManual.refId;
        var ddbpSurgeFactor = qRouteRates.srgDDBP.refModel.dprApplied.factor;
        var manualBaseAmount = qRouteRates.srgManual.rockBaseAmount;
        var regularBaseAmount = qRouteRates.regularBaseAmount;
        var differentiateSurgeAmount = qRouteRates.differentiateSurgeAmount;
        var routeSurgeFlag = qRouteRates.srgDDBP.refModel.routeFlag;
        var ddbpRouteToRouteFactor = qRouteRates.srgDDBP.refModel.dprRoutes.factor;
        var ddbpZoneToZoneFactor = qRouteRates.srgDDBP.refModel.dprZoneRoutes.factor;
        var ddbpZoneToStateFactor = qRouteRates.srgDDBP.refModel.dprZonesStates.factor;
        var ddbpZoneFactor = qRouteRates.srgDDBP.refModel.dprZones.factor;
        if (routeSurgeFlag == true)
        {
            routeSurgeFlag = 1;
        }
        var ddbpMasterFlag = qRouteRates.srgDDBP.refModel.globalFlag;
        var quoteStatement = data.data.quoteStatement;



        var bookingPriceFactor = {surgeFactorUsed, ddbpBaseAmount, dtbpBaseAmount, ddbpFactorType, manualSurgeId, ddbpSurgeFactor, manualBaseAmount, regularBaseAmount, routeSurgeFlag, ddbpRouteToRouteFactor, ddbpZoneToZoneFactor, ddbpZoneToStateFactor, ddbpZoneFactor, ddbpMasterFlag};
        var bookingPriceFactorJSON = JSON.stringify(bookingPriceFactor);
        $('#bkgPricefactor').val(bookingPriceFactorJSON);

        //console.log(bookingPriceFactorJSON);

        $("#divQuote").html(quoteStatement).change();
        $("#itenaryButtonDiv").show();
        $("#itenaryButton").text("Copy Itinerary to Clipboard");
        $("#itenaryButton").removeClass("btn-success");
        $("#itenaryButton").addClass("btn-primary");


        $("#bkg_surge_differentiate_amount").val(differentiateSurgeAmount);


//       alert(qRouteRates.isNightPickupIncluded);
//		 alert(qRouteRates.isNightDropIncluded);
//		 alert(qRouteRates.isDayAllowanceIncluded);
//		 alert(qRouteRates.isNightAllowanceIncluded);



//		$("#Booking_bkg_is_day_driver_allowance_amount").val(qRouteRates.isDayAllowanceIncluded);
//		$("#Booking_bkg_is_night_driver_allowance_amount").val(qRouteRates.isNightAllowanceIncluded);
        $("#BookingInvoice_bkg_night_pickup_included").val(qRouteRates.isNightPickupIncluded);
        $("#BookingInvoice_bkg_night_drop_included").val(qRouteRates.isNightDropIncluded);


        $("#BookingInvoice_bkg_base_amount").val(qRouteRates.baseAmount);
        $("#BookingInvoice_bkg_toll_tax").val(qRouteRates.tollTaxAmount | 0);
        $("#BookingInvoice_bkg_state_tax").val(qRouteRates.stateTax | 0);
        $("#BookingInvoice_bkg_rate_per_km_extra").val(qRouteRates.ratePerKM);
        $("#BookingInvoice_bkg_total_amount").val(qRouteRates.totalAmount);
        $('#BookingInvoice_bkg_gozo_base_amount').val(qRouteRates.baseAmount);
        if (qRouteRates.isParkingIncluded == 1)
        {
            $("#BookingInvoice_bkg_parking_charge").val(qRouteRates.parkingAmount);
            $('.checkerparkingtax span').addClass('checked');
            $('#Booking_bkg_is_parking_included').val(1);
        } else
        {

            $('#Booking_bkg_is_parking_included').val(0);
            $('.checkerparkingtax span').removeClass('checked');
            $("#BookingInvoice_bkg_parking_charge").val(0);
        }


        $("#trip_rate").text('');
//            if (qRouteRates.costPerKM > 0) {
//                //  $("#trip_rate").text('Rate : Rs.' + data.est_booking_info['km_rate'] + ' per km');
//            }

        $('#BookingInvoice_bkg_service_tax').val(qRouteRates.gst);
        $('#BookingInvoice_bkg_driver_allowance_amount').val(qRouteRates.driverAllowance);
        $('#BookingInvoice_bkg_driver_allowance_amount').attr('oldamount', qRouteRates.driverAllowance);

//		$('#Booking_bkg_day_driver_allowance_amount').val(qRouteRates.driverDayAllowance);
//		$('#Booking_bkg_night_driver_allowance_amount').val(qRouteRates.driverNightAllowance);

        var bkgtype = $("#Booking_bkg_booking_type").val();
        if (data.data.distArr != '' && bkgtype != 1 && bkgtype != 9 && bkgtype != 10 && bkgtype != 11)
        {
            var distArrVal = data.data.distArr;
            var multicityjsondata = $.parseJSON($('#multicityjsondata').val());

            $.each(distArrVal, function (k, v)
            {
                $('#fdistcreate' + k).text(v['dist']);
                $('#distancecreate' + (k + 1)).text(v['dist']);
                $('#fduracreate' + k).text(v['dura']);
                $('#durationcreate' + (k + 1)).text(v['dura']);
                multicityjsondata[k]['distance'] = v['dist'] + "";
                multicityjsondata[k]['duration'] = v['dura'] + "";
                multicityjsondata[k]['pickup_city'] = v['fromCity'] + "";
                multicityjsondata[k]['drop_city'] = v['toCity'] + "";

            });
            $('#multicityjsondata').val(JSON.stringify(multicityjsondata)).change();
        } else
        {

            $("#multicityjsondata").val(JSON.stringify(data.data.arrjsondata)).change();
//            alert($("#multicityjsondata").val());
        }
        if (qRouteRates.isTollIncluded == 1)
        {
            $('.checkertolltax span').addClass('checked');
            $('#BookingInvoice_bkg_is_toll_tax_included').val(1);
            $('#Booking_bkg_is_toll_tax_included1').attr('checked', 'true');
            $('#Booking_bkg_is_toll_tax_included1').attr('disabled', 'disabled');
            $('#BookingInvoice_bkg_toll_tax').attr('readOnly', 'readOnly');
        } else
        {
            $('#BookingInvoice_bkg_is_toll_tax_included').val(0);
            $('.checkertolltax span').removeClass('checked');
            $('#Booking_bkg_is_toll_tax_included1').attr('checked', 'false');
            $('#Booking_bkg_is_toll_tax_included1').removeAttr('disabled');
        }
        if (qRouteRates.isStateTaxIncluded == 1)
        {
            $('#BookingInvoice_bkg_is_state_tax_included').val(1);
            $('.checkerstatetax span').addClass('checked');
            $('#Booking_bkg_is_state_tax_included1').attr('checked', 'true');
            $('#Booking_bkg_is_state_tax_included1').attr('disabled', 'disabled');
            $('#BookingInvoice_bkg_state_tax').attr('readOnly', 'readOnly');
        } else
        {
            $('#BookingInvoice_bkg_is_state_tax_included').val(0);
            $('.checkerstatetax span').removeClass('checked');
            $('#Booking_bkg_is_state_tax_included1').attr('checked', 'false');
            $('#Booking_bkg_is_state_tax_included1').removeAttr('disabled');
        }

//		if (qRouteRates.isDayAllowanceIncluded == 1)
//        {
//            $('#Booking_bkg_is_day_driver_allowance_amount').val(1);
//            $('.checkeDayAllowance span').addClass('checked');
//            $('#Booking_bkg_is_day_driver_allowance_amount1').attr('checked', 'true');
//            $('#Booking_bkg_is_day_driver_allowance_amount1').attr('disabled', 'disabled');
//            $('#Booking_bkg_is_day_driver_allowance_amount1').attr('readOnly', 'readOnly');
//        } else
//        {
//            $('#Booking_bkg_is_day_driver_allowance_amount').val(0);
//            $('.checkeDayAllowance span').removeClass('checked');
//            $('#Booking_bkg_is_day_driver_allowance_amount1').attr('checked', 'false');
//            $('#Booking_bkg_is_day_driver_allowance_amount1').attr('disabled', 'disabled');;
//        }

//		if (qRouteRates.isNightAllowanceIncluded == 1)
//        {
//            $('#Booking_bkg_is_night_driver_allowance_amount').val(1);
//            $('.checkeNightAllowance span').addClass('checked');
//            $('#Booking_bkg_is_night_driver_allowance_amount1').attr('checked', 'true');
//            $('#Booking_bkg_is_night_driver_allowance_amount1').attr('disabled', 'disabled');
//            $('#Booking_bkg_is_night_driver_allowance_amount1').attr('readOnly', 'readOnly');
//        } else
//        {
//            $('#Booking_bkg_is_night_driver_allowance_amount').val(0);
//            $('.checkeNightAllowance span').removeClass('checked');
//            $('#Booking_bkg_is_night_driver_allowance_amount1').attr('checked', 'false');
//            $('#Booking_bkg_is_night_driver_allowance_amount1').attr('disabled', 'disabled');;
//        }

        if (qRouteRates.isNightPickupIncluded == 1)
        {
            $('#Booking_bkg_night_pickup_included').val(1);
            $('.checkeNightPickupAllowance span').addClass('checked');
            $('#Booking_bkg_night_pickup_included1').attr('checked', 'true');
            $('#Booking_bkg_night_pickup_included1').attr('disabled', 'disabled');
            $('#Booking_bkg_night_pickup_included1').attr('readOnly', 'readOnly');
        } else
        {
            $('#Booking_bkg_night_pickup_included').val(0);
            $('.checkeNightPickupAllowance span').removeClass('checked');
            $('#Booking_bkg_is_nightpickupinclude1').attr('checked', 'false');
            $('#Booking_bkg_is_nightpickupinclude1').attr('disabled', 'disabled');

        }

        if (qRouteRates.isNightDropIncluded == 1)
        {
            $('#Booking_bkg_is_nightdropoffinclude').val(1);
            $('.checkeNightDropOffAllowance span').addClass('checked');
            $('#Booking_bkg_is_nightdropoffinclude1').attr('checked', 'true');
            $('#Booking_bkg_is_nightdropoffinclude1').attr('disabled', 'disabled');
            $('#Booking_bkg_is_nightdropoffinclude1').attr('readOnly', 'readOnly');
        } else
        {
            $('#Booking_bkg_is_nightdropoffinclude').val(0);
            $('.checkeNightDropOffAllowance span').removeClass('checked');
            $('#Booking_bkg_is_nightdropoffinclude1').attr('checked', 'false');
            $('#Booking_bkg_is_nightdropoffinclude1').attr('disabled', 'disabled');
        }


        $('#BookingInvoice_bkg_rate_per_km').val(qRouteRates.costPerKM);
        $('#BookingInvoice_bkg_chargeable_distance').val(qRouteRates.quotedDistance);
        $('#rtevndamt').val(qRouteRates.vendorAmount);
        $('#BookingTrack_bkg_garage_time').val(qRouteDuration.totalMinutes);
        $('#Booking_bkg_trip_distance').val(qRouteDistance.quotedDistance);
        $('#Booking_bkg_trip_duration').val(qRouteDuration.totalMinutes);
        $('#BookingInvoice_bkg_quoted_vendor_amount').val(Math.round(qRouteRates.vendorAmount));
        if (qRouteRates.costPerKM > 0 && $('#Booking_bkg_trip_distance').val() > 0)
        {
            $('#vehicle_dist_ext').html("Note: Ext. Chrg. After " + $('#Booking_bkg_trip_distance').val() + " Kms. = " + qRouteRates.costPerKM + "/Km.");
        } else
        {
            $('#vehicle_dist_ext').html("");
        }
        getAgentBaseDiscFare();
        calculateAmount();
        var trip_user = $("input[name=\'Booking[trip_user]\']:checked").val();
        if ((trip_user == 2) && $('#bkg_agent_id').val() != '' && $('#bkg_agent_id').val() != null && $('#bkg_agent_id').val() != undefined && $('#bkg_agent_id').val() != '0' && $('#bkg_agent_id').val() != 0)
        {
            var totalAmount = parseInt(Math.round($('#BookingInvoice_bkg_total_amount').val()));
            $('#Booking_agentCreditAmount').val(totalAmount);
            $('#div_due_amount').removeClass('hide');
            $('#id_due_amount').html(0);
        }

        $("#Booking_routeProcessed").val('');
        if (data.data.processedRoute != '')
        {
            $("#Booking_routeProcessed").val(data.data.processedRoute);
        }

    }
    var previousAddToMyTrip = 0;
    var addToMyTripFixedMin = 30;
    var addToMyTripFixedAmount = 150;
    function calculateAmount()
    {
        var gross_amount = Math.round($('#BookingInvoice_bkg_base_amount').val());
        var trip_user = $("input[name=\'Booking[trip_user]\']:checked").val();
        gross_amount = (gross_amount == '') ? 0 : parseInt(gross_amount);
        var additional = Math.round($('#BookingInvoice_bkg_additional_charge').val());
        var additional = (additional == '') ? 0 : parseInt(Math.round(additional - previousAddToMyTrip));
        var addToMyTripInMin = $('#BookingAddInfo_bkg_spl_req_lunch_break_time').val(), addToMyTrip;
        addToMyTrip = addToMyTripFixedAmount * (addToMyTripInMin / addToMyTripFixedMin);
        previousAddToMyTrip = addToMyTrip;
        var addToMyTripForVendor = addToMyTrip != '0' ? (addToMyTrip * 60) / 100 : '0';
        var rateVendorAmount = Math.round($('#rtevndamt').val());
        var vendor_amount = Math.round(rateVendorAmount + (addToMyTripInMin != '0' ? addToMyTripForVendor : 0) + additional);
        additional = Math.round(additional + addToMyTrip);
        var discount_amount = Math.round($('#BookingInvoice_bkg_discount_amount').val());
        var driver_allowance = 0;
        var parking_charge = 0;
        var gozo_base_amount = Math.round($('#BookingInvoice_bkg_gozo_base_amount').val());
        gross_amount = Math.round(gross_amount + additional);
        discount_amount = (discount_amount == '') ? 0 : parseInt(discount_amount);
        gross_amount = gross_amount - discount_amount;
        if ($('#BookingInvoice_bkg_driver_allowance_amount').val() != '' && $('#BookingInvoice_bkg_driver_allowance_amount').val() > 0)
        {
            // gross_amount = gross_amount + parseInt($('#Booking_bkg_driver_allowance_amount').val());
            driver_allowance = parseInt($('#BookingInvoice_bkg_driver_allowance_amount').val());
        }
        if ($('#BookingInvoice_bkg_parking_charge').val() != '' && $('#BookingInvoice_bkg_parking_charge').val() > 0)
        {
            parking_charge = parseInt($('#BookingInvoice_bkg_parking_charge').val());
        }

        var conFee1 = gross_amount * 0.05;
        var conFee2 = 249;
        //  var conFee1 = gross_amount * 0.10;
        //   var conFee2 = 499;
        var conFee = 0;
        if (conFee1 > conFee2)
        {
            conFee = conFee2;
        } else
        {
            conFee = conFee1;
        }

        if ((trip_user == 2) && ($('#bkg_agent_id').val() != '' && $('#bkg_agent_id').val() != null && $('#bkg_agent_id').val() != undefined && $('#bkg_agent_id').val() != '0' && $('#bkg_agent_id').val() != 0))
        {
            if ($('#agt_type').val() == 1)
            {
                conFee = 0;
                $('#agtnotification').removeClass('hide');
            } else
            {
                conFee = 0;
                $('#divpaidby').removeClass('hide');
                $('#agtnotification').removeClass('hide');
                showAgentCreditDiv();
            }
        }

        //    conFee=0 //set Convenience charge zero;
        var convenience_charge = Math.round(conFee);
        var tollTaxVal = ($('#BookingInvoice_bkg_toll_tax').val() == '') ? 0 : parseInt($('#BookingInvoice_bkg_toll_tax').val());
        var stateTaxVal = ($('#BookingInvoice_bkg_state_tax').val() == '') ? 0 : parseInt($('#BookingInvoice_bkg_state_tax').val());
        var service_tax_rate = ($('#BookingInvoice_bkg_service_tax_rate').val() == '') ? 0 : $('#BookingInvoice_bkg_service_tax_rate').val();
        var service_tax_amount = 0;
        if (service_tax_rate != 0)
        {
            service_tax_amount = Math.round(((gross_amount + tollTaxVal + stateTaxVal + driver_allowance + parking_charge) * parseFloat(service_tax_rate) / 100));
        }

        var amountwithoutconvenienc = gross_amount + service_tax_amount + tollTaxVal + stateTaxVal + driver_allowance + parking_charge;
        $('#amountwithoutcod').val(amountwithoutconvenienc);
        gross_amount = gross_amount + convenience_charge;
        $('#BookingInvoice_bkg_convenience_charge').val(convenience_charge);
        service_tax_amount = 0;
        if (service_tax_rate != 0)
        {
            service_tax_amount = Math.round(((gross_amount + tollTaxVal + stateTaxVal + driver_allowance + parking_charge) * parseFloat(service_tax_rate) / 100));
        }
        var net_amount = gross_amount + service_tax_amount;
        var net_amount = net_amount + tollTaxVal + stateTaxVal + driver_allowance + parking_charge;
        $('#BookingInvoice_bkg_additional_charge').val(additional);
        addToMyTripInMin != '0' ? $('#BookingInvoice_bkg_additional_charge_remark').val("Customer will pay " + addToMyTripInMin + ' minutes Journey Break') : $('#BookingInvoice_bkg_additional_charge_remark').val('');
        $('#BookingInvoice_bkg_total_amount').val(net_amount);
        $('#BookingInvoice_bkg_vendor_amount').val(vendor_amount);
        $('#BookingInvoice_bkg_service_tax').val(service_tax_amount);
        if ((trip_user == 2) && $('#agt_type').val() != 1 && $('#bkg_agent_id').val() != '' && $('#bkg_agent_id').val() != null && $('#bkg_agent_id').val() != undefined && $('#bkg_agent_id').val() != '0' && $('#bkg_agent_id').val() != 0)
        {
            $('#Booking_agentCreditAmount').attr('max', net_amount);
            var corpCredit = Math.round($('#Booking_agentCreditAmount').val());
            corpCredit = (corpCredit == '') ? 0 : parseInt(corpCredit);
            var due_amt = parseInt(net_amount) - corpCredit;
            $('#id_due_amount').html(due_amt);
        } else
        {
            $('#Booking_agentCreditAmount').val('');
            $('#div_due_amount').addClass('hide');
            $('#id_due_amount').html(net_amount);
        }

    }

    $('#Booking_agentCreditAmount').change(function ()
    {
        calculateAmount();
    });
    function calculatefare()
    {
        getDiscount();
        calculateAmount();
    }

    function getAmount()
    {
        if ($("#Booking_bkg_route_id").val() != '' && $("#Booking_bkg_vehicle_type_id").val() != '')
        {
            model.routeId = $("#Booking_bkg_route_id").val();
            model.vehicleId = $("#Booking_bkg_vehicle_type_id").val();
            $(document).on("amount", function (event, data)
            {
                getCalculateAmount(data);
            });
            booking.amount();
        }
    }

    function getCalculateAmount(data)
    {
        $("#BookingInvoice_bkg_total_amount").val('0');
        $("#BookingInvoice_bkg_base_amount").val('0');
        if (data.data.routeRate)
        {
            $("#BookingInvoice_bkg_base_amount").val(data.data.routeRate).change();
            getDiscount();
            calculateAmount();
        }
    }

    $('#Booking_bkg_pickup_date_date').datepicker({
        format: 'dd/mm/yyyy'
    });
    $('#Booking_bkg_return_date_date').datepicker({
        format: 'dd/mm/yyyy'
    });
    function getApplicableDistance(dist)
    {
        distkm = parseInt(dist) + 15;
        distkm = (Math.ceil(distkm / 10)) * 10;
        return distkm;
    }

    $('#addCity').unbind("click").bind("click", function ()
    {
        $href = '<?= Yii::app()->createUrl('admin/city/create') ?>';
        jQuery.ajax({type: 'GET', url: $href,
            success: function (data)
            {
                box = bootbox.dialog({
                    message: data,
                    title: 'Add City',
                    onEscape: function ()
                    {
                        box.hide();
                        box.remove();
                    },
                });
            }});
    });
    refreshCity = function ()
    {
        box.hide();
        box.remove();
        $href = "<?= Yii::app()->createUrl('admin/city/json') ?>";
        jQuery.ajax({type: 'GET', dataType: 'json', url: $href, async: false,
            success: function (data1)
            {
                $('#<?= CHtml::activeId($model, "bkg_from_city_id") ?>').select2({data: data1, multiple: false});
                $('#<?= CHtml::activeId($model, "bkg_to_city_id") ?>').select2({data: data1, multiple: false});
            },
            error: function (e)
            {
                //alert(e);
            }
        });
    };
    $('#addmulticities').click(function ()
    {
        var $bkgtype = $("#Booking_bkg_booking_type").val();
        $('#addmulticities').hide();
        $('#ctyinfo_bkg_type_1').hide();
        if ($bkgtype == '2')
        {

            $('#address_div').hide();
            $('#Booking_bkg_route').attr('disabled', 'disabled');
            $href = '<?= Yii::app()->createUrl('admin/booking/multicityform', ['bookingType' => '']) ?>' + $bkgtype;
            jQuery.ajax({type: 'GET', url: $href,
                success: function (data)
                {

                    multicitybootbox = bootbox.dialog({
                        message: data,
                        size: 'large',
                        title: 'Add pickup info',
                        onEscape: function ()
                        {
                            $('#addmulticities').show();
                            multicitybootbox.hide();
                            multicitybootbox.remove();
                        },
                    });
                    multicitybootbox.on('hidden.bs.modal', function (e)
                    {
                        $('body').addClass('modal-open');
                    });
                }
            });
        }

        if ($bkgtype == '3')
        {

            $('#Booking_bkg_route').attr('disabled', 'disabled');
            $('#address_div').hide();
            $href = '<?= Yii::app()->createUrl('admin/booking/multicityform', ['bookingType' => '']) ?>' + $bkgtype;
            jQuery.ajax({type: 'GET', url: $href,
                success: function (data)
                {

                    multicitybootbox = bootbox.dialog({
                        message: data,
                        size: 'large',
                        title: 'Add pickup info',
                        onEscape: function ()
                        {
                            $('#addmulticities').show();
                            multicitybootbox.hide();
                            multicitybootbox.remove();
                        },
                    });
                    multicitybootbox.on('hidden.bs.modal', function (e)
                    {
                        $('body').addClass('modal-open');
                    });
                }
            });
        }
    });

    function loadScript()
    {
        var script = document.createElement('script');
        script.type = 'text/javascript';

//        script.src = 'https:////maps.googleapis.com/maps/api/js?key=<? //= $api            ?>&libraries=places&';

        document.body.appendChild(script);
        booking_type = $('#Booking_bkg_booking_type').val();
    }
    window.onload = loadScript;

    function getCityBounds(cty_bounds, ctLat, ctLon)
    {
        var Bounds = cty_bounds;
        var BoundArr = [];
        if (cty_bounds != null)
        {
            BoundArr['ne_lat'] = Bounds.northeast.lat;
            BoundArr['ne_long'] = Bounds.northeast.lng;
            BoundArr['sw_lat'] = Bounds.southwest.lat;
            BoundArr['sw_long'] = Bounds.southwest.lng;
        } else
        {
            BoundArr['ne_lat'] = ctLat - 0.05;
            BoundArr['ne_long'] = ctLon - 0.05;
            BoundArr['sw_lat'] = ctLat - 0.0 + 0.05;//parseFloat
            BoundArr['sw_long'] = ctLon - 0.0 + 0.05;
        }

        return BoundArr;
    }

    function updateMulticity(data, tot)
    {
        booking_type = $('#Booking_bkg_booking_type').val();
        transfer_type = 0;
        var routetot = (tot);
        var bounds = [];
        var ctyLat = [];
        var ctyLong = [];
        var isCtyAirport = [];
        var isCtyPoi = [];
        var model = {};
        var data = $.parseJSON(data);
        $('#tripTablecreate').show();
        $('#insertTripRowcreate').html('');
        $('.multicitydetrow').remove();
        $('#address_div').hide();
        $('#Booking_bkg_pickup_date_date').val(data[0].pickup_date);
        $('#Booking_bkg_pickup_date_time').val(data[0].pickup_time);
        $("#Booking_bkg_from_city_id").val(data[0].pickup_city);
        $("#Booking_bkg_to_city_id").val(data[tot].drop_city);
        $("#multicityjsondata").val(JSON.stringify(data));
        $("#ctyinfo_bkg_type_1").hide();
        $('#show_return_date_time').html("");
        if ($('#Booking_bkg_booking_type').val() == 2)
        {
            $('#Booking_bkg_return_date_time').val(data[tot].return_time);
            $('#Booking_bkg_return_date_date').val(data[tot].return_date);
        }
        var total_distance = 0;
        var total_duration = 0;
        for (var i = 1; i <= tot + 1; i++)
        {
            $('#insertTripRowcreate').before('<tr class="multicitydetrow">' +
                    '<td id="fcitycreate0"></td>' +
                    '<td id="tcitycreate0"> </td>' +
                    '<td id="fdatecreate0"> </td>' +
                    '<td id="distancecreate0"> </td>' +
                    '<td id="durationcreate0"> </td>' +
                    '<td id="noOfDayscreate0"> </td>' +
                    '</tr>');
            $('#fcitycreate0').attr('id', 'fcitycreate' + i);
            $('#tcitycreate0').attr('id', 'tcitycreate' + i);
            $('#fdatecreate0').attr('id', 'fdatecreate' + i);
            $('#distancecreate0').attr('id', 'distancecreate' + i);
            $('#durationcreate0').attr('id', 'durationcreate' + i);
            $('#noOfDayscreate0').attr('id', 'noOfDayscreate' + i);
            $('#noOfDayscreate' + i).text('1');
            total_distance = (total_distance + parseInt(data[(i - 1)].distance));
            total_duration = (total_duration + parseInt(data[(i - 1)].duration));
            $('#noOfDayscreate' + i).text(data[(i - 1)].day);
            $('#totdayscreate').text(data[(i - 1)].totday);
            $('#fcitycreate' + i).html('<b>' + data[(i - 1)].pickup_city_name + '</b>');
            $('#tcitycreate' + i).html('<b>' + data[(i - 1)].drop_city_name + '</b>');
            $('#fdatecreate' + i).text(data[(i - 1)].pickup_date + " " + data[(i - 1)].pickup_time);
            $('#distancecreate' + i).text(data[(i - 1)].distance);
            $('#durationcreate' + i).text(data[(i - 1)].duration);
        }
        $('#Booking_bkg_trip_distance').val(total_distance);
        $('#Booking_bkg_trip_duration').val(total_duration);
        var addresshtml = "";
        b = 0;
        for (var j = 0; j <= routetot; j++)
        {
            picaddress = '';
            dropaddress = '';
            if (data[j].pickup_cty_is_poi == 1 || data[j].pickup_cty_is_airport == 1) {
                picaddress = data[j].pickup_cty_loc;
            }
            if (data[j].drop_cty_is_poi == 1 || data[j].drop_cty_is_airport == 1) {
                dropaddress = data[j].drop_cty_loc;
            }

            b = j + 1;
            var pickBounds = getCityBounds(data[j].pickup_cty_bounds, data[j].pickup_cty_lat, data[j].pickup_cty_long);
            var pickup_cty_ne_lat = pickBounds.ne_lat;
            var pickup_cty_ne_long = pickBounds.ne_long;
            var pickup_cty_sw_lat = pickBounds.sw_lat;
            var pickup_cty_sw_long = pickBounds.sw_long;
            var dropBounds = getCityBounds(data[j].drop_cty_bounds, data[j].drop_cty_lat, data[j].drop_cty_long);
            var drop_cty_ne_lat = dropBounds.ne_lat;
            var drop_cty_ne_long = dropBounds.ne_long;
            var drop_cty_sw_lat = dropBounds.sw_lat;
            var drop_cty_sw_long = dropBounds.sw_long;
            if (j == 0)
            {
                addresshtml +=
                        '<div class="col-xs-12 pb10"><div class="row ">\n\
						<div class="col-xs-12 col-sm-6 pl0 ">\n\
							<label for="pickup_address' + j + '" class="control-label text-left">Pickup Address for ' + data[j].pickup_city_name + ':</label>\n\
							<input type="hidden" id="ctyLat' + j + '" value="' + data[j].pickup_cty_lat + '">\n\
							<input type="hidden" id="ctyLon' + j + '" value="' + data[j].pickup_cty_long + '">\n\
							<input type="hidden" id="ctyELat' + j + '" value="' + pickup_cty_ne_lat + '">\n\
							<input type="hidden" id="ctyWLat' + j + '" value="' + pickup_cty_sw_lat + '">\n\
							<input type="hidden" id="ctyELng' + j + '" value="' + pickup_cty_ne_long + '">\n\
							<input type="hidden" id="ctyWLng' + j + '" value="' + pickup_cty_sw_long + '">\n\
							<input type="hidden" id="ctyRad' + j + '" value="' + data[j].pickup_cty_radius + '">\n\
							<input name="BookingRoute[' + j + '][brt_from_latitude]" class="locLatVal locLat_' + j + '" type="hidden" value="">\n\
							<input name="BookingRoute[' + j + '][brt_from_longitude]"  class="locLonVal locLon_' + j + '"  type="hidden" value="">\n\
							<input id="city_is_airport' + j + '" name="BookingRoute[' + j + '][brt_from_city_is_airport]" type="hidden"  value="' + data[j].pickup_cty_is_airport + '">\n\
							<input id="city_is_poi' + j + '" name="BookingRoute[' + j + '][brt_from_city_is_poi]" type="hidden"  value="' + data[j].pickup_cty_is_poi + '">\n\
						</div>\n\
						<div class="col-xs-12 col-sm-5 mb0 pb0"><div class="form-group">\n\
							<textarea id="locoldbooking_' + j + '" class="form-control brt_location_' + j + ' txtpl form-control" placeholder="Pickup Address  (Required)" name="BookingRoute[' + j + '][brt_from_location]" autocomplete="off">' + picaddress + '</textarea>\n\
							<div class="help-block error" id="BookingRoute_' + j + '_brt_from_location_em_" style="display:none"></div>\n\
						</div></div>\n\
						</div><div class="row ">\n\
						<div class="col-xs-12 col-sm-6 pl0">\n\
							<label for="buildinInfo' + j + '" class="control-label text-left"><?= $additionalAddressInfo ?>:</label>\n\
						</div>\n\
						<div class="col-xs-12 col-sm-5 mb0 pb0"><div class="form-group">\n\
							<input id="brt_additional' + j + '" class="form-control form-control" placeholder="<?= $additionalAddressInfo ?>" name="BookingRoute[' + j + '][brt_additional_from_address]" type="text">\n\
							<div class="help-block error" id="BookingRoute_' + j + '_brt_additional_from_address_em_" style="display:none">\n\
						</div></div></div></div></div>';

                bounds[j] = JSON.stringify(data[j].pickup_cty_bounds);
                ctyLat[j] = data[j].pickup_cty_lat;
                ctyLong[j] = data[j].pickup_cty_long;
                isCtyAirport[j] = data[j].pickup_cty_is_airport;
                isCtyPoi[j] = data[j].pickup_cty_is_poi;
            }
            addresshtml +=
                    '<div class="col-xs-12 pt10 pb20"><div class="row">\n\
					<div class="col-xs-12 col-sm-6 pl0">\n\
						<label for="pickup_address' + b + '" class="control-label text-left">Drop Address for ' + data[j].drop_city_name + ' :</label>\n\
						<input type="hidden" id="ctyLat' + b + '" value="' + data[j].drop_cty_lat + '">\n\
						<input type="hidden" id="ctyLon' + b + '" value="' + data[j].drop_cty_long + '">\n\
						<input type="hidden" id="ctyELat' + b + '" value="' + drop_cty_ne_lat + '">\n\
						<input type="hidden" id="ctyWLat' + b + '" value="' + drop_cty_sw_lat + '">\n\
						<input type="hidden" id="ctyELng' + b + '" value="' + drop_cty_ne_long + '">\n\
						<input type="hidden" id="ctyWLng' + b + '" value="' + drop_cty_sw_long + '">\n\
						<input type="hidden" id="ctyRad' + b + '" value="' + data[j].drop_cty_radius + '">\n\
						<input name="BookingRoute[' + b + '][brt_to_latitude]"  class="locLatVal locLat_' + b + '" type="hidden" value="">\n\
						<input name="BookingRoute[' + b + '][brt_to_longitude]"  class="locLonVal locLon_' + b + '" type="hidden" value="">\n\
						<input id="city_is_airport' + b + '" name="BookingRoute[' + b + '][brt_to_city_is_airport]" type="hidden"  value="' + data[j].drop_cty_is_airport + '">\n\
						<input id="city_is_poi' + b + '" name="BookingRoute[' + b + '][brt_to_city_is_poi]" type="hidden"  value="' + data[j].drop_cty_is_poi + '">\n\
					</div>\n\
					<div class="col-xs-12 col-sm-5">\n\
						<div class="form-group">\n\
							<textarea id="locoldbooking_' + b + '" class="form-control brt_location_' + b + ' txtpl form-control" placeholder="Drop Address  (Optional)" name="BookingRoute[' + b + '][brt_to_location]" autocomplete="off">' + dropaddress + '</textarea>\n\
							<div class="help-block error" id="BookingRoute_' + b + '_brt_to_location_em_" style="display:none"></div>\n\
						</div></div>\n\
					</div>\n\
					<div class="row"><div class="col-xs-12 col-sm-6 pl0">\n\
						<label for="buildinInfo" class="control-label text-left"><?= $additionalAddressInfo ?>:</label>\n\
					</div>\n\
					<div class="col-xs-12 col-sm-5">\n\
						<div class="form-group"><input id="brt_additional' + b + '" class="form-control form-control" placeholder="<?= $additionalAddressInfo ?>" name="BookingRoute[' + b + '][brt_additional_to_address]" type="text">\n\
							<div class="help-block error" id="BookingRoute_' + b + '_brt_additional_to_address_em_" style="display:none">\n\
					</div></div></div></div></div>';

            bounds[b] = JSON.stringify(data[j].drop_cty_bounds);
            ctyLat[b] = data[j].drop_cty_lat;
            ctyLong[b] = data[j].drop_cty_long;
            isCtyAirport[b] = data[j].drop_cty_is_airport;
            isCtyPoi[b] = data[j].drop_cty_is_poi;
        }

        model.booking_type = booking_type;
        model.transfer_type = '0';
        model.ctyLat = ctyLat;
        model.ctyLon = ctyLong;
        model.bound = bounds;
        model.isCtyAirport = isCtyAirport;
        model.isCtyPoi = isCtyPoi;
        model.hyperLocationClass = 'txtpl';
        $('#address').html(addresshtml);
        // $("#multicityjsondata").val(JSON.stringify(data));
        hyperModel.model = model;
        hyperModel.initializepl();

    }

    function getAutoAddressBox()
    {
        var pickup_city = $('#<?= CHtml::activeId($model, "bkg_from_city_id") ?>').val();
        var drop_city = $('#<?= CHtml::activeId($model, "bkg_to_city_id") ?>').val();
        var href = '<?= Yii::app()->createUrl("admin/booking/onewayautoaddress"); ?>';
        var booking_type = $('#Booking_bkg_booking_type').val();
        if (booking_type == 1 || booking_type == 9 || booking_type == 10 || booking_type == 11)
        {
            $.ajax({
                url: href, dataType: "HTML",
                data: {"pickup_city": pickup_city, "drop_city": drop_city, "booking_type": booking_type, "hyperInitialize": ''},
                "success": function (data)
                {
                    $('#address_div').html(data);
                }

            });
        }


    }

    function editmulticity()
    {
        var $bkgtype = $("#Booking_bkg_booking_type").val();
        $('#ctyinfo_bkg_type_1').hide();
        $href = '<?= Yii::app()->createUrl('admin/booking/multicityform', ['bookingType' => '']) ?>' + $bkgtype;
        jQuery.ajax({type: 'GET', url: $href,
            success: function (data)
            {
                multicitybootbox = bootbox.dialog({
                    message: data,
                    size: 'large',
                    title: 'Add pickup info',
                    onEscape: function ()
                    {
                        multicitybootbox.hide();
                        multicitybootbox.remove();
                    },
                });
                multicitybootbox.on('hidden.bs.modal', function (e)
                {
                    $('body').addClass('modal-open');
                });
            }
        });
    }
    function  getDateobj(pdpdate, ptptime)
    {
        var date = pdpdate;
        var time = ptptime;
        var dateArr = date.split("/");
        var timeArr = time.split(" ");
        var mer = timeArr[1];
        var temp = timeArr[0].split(":");
        var hour = Number(temp[0]);
        var min = Number(temp[1]);
        if (mer == "PM")
        {
            if (hour != 12)
            {
                hour = 12 + hour;
            }
        } else if (hour == 12)
        {
            hour = 0;
        }
        //  var currDateTime = new Date();
        var dateObj = new Date(Number(dateArr[2]), Number(dateArr[1]) - 1, Number(dateArr[0]), hour, min, 0);
        return dateObj;
    }

    $('#<?= CHtml::activeId($addInfoModel, 'bkg_flight_no') ?>').mask('XXXX-XXXXXX', {
        translation: {
            'Z': {
                pattern: /[0-9]/, optional: true
            },
            'X': {
                pattern: /[0-9A-Za-z]/, optional: true
            },
        },
        placeholder: "__ __ __ ____",
        clearIfNotMatch: true
    });
    function showlinkedUser()
    {
        var phone = $('#BookingUser_bkg_contact_no').val();
        var email = $('#BookingUser_bkg_user_email').val();
        var code = $('#BookingUser_bkg_country_code').val();
        if ((phone != '' && phone != null && phone != undefined) || (email != '' && email != null && email != undefined))
        {
            var href1 = '<?= Yii::app()->createUrl("admin/user/linkedusers"); ?>';
            $.ajax({url: href1,
                dataType: "json",
                // async: false,
                data: {"phone": phone, "email": email, "code": code},
                "success": function (data)
                {
                    if (data.success)
                    {
                        var users = data.users;
                        var html = '';
                         $.each(users, function (key, value)
                        {
                            html = html + '\
                                    <div class="p5" style="font-size: 1.1em">\n\
                            <a href="#" class="ml5" onclick="showUserDet(\'' + value["id"] + '\')">' + value['email'] + '</a><span id="spnLinkUser' 
															+ key + '" class="linkuserbtn bg-warning m5" code="' + $value['code'] + '" phone="' + value['phone'] + '" email="' + value['email'] + '<a href="#" class="ml5" onclick="showUserView(\'' + value["id"] + '\')">' + value['fname'] + '</a>' +  '" lname="' + value['lname'] + '" onclick="linkUser(this,\'' + value["id"] + '\')"><i class="fa fa-check"></i></span><br></div>';
                        });
                        
                       
                        $('#linkedusers').html('<div class="panel panel-primary panel-border compact"><div class="panel-heading" style="min-height:0">Existing Users (tick to link): </div><div class="panel-body">' + html + '</div></div>');
                        var userCount = data.userCount;
                        if (userCount > 0)
                        {
                            $("#spnLinkUser0").click();
                        }
                    } else
                    {
                        if (data.error == "[]")
                        {
                            $('#linkedusers').html('');
						}
                        else
                        {
                            var errors = JSON.parse(data.error);
                            $.each(errors, function (k, v) {
                                alert(v);
                            });
                        }
                    }
                }
            });
        } else
        {
            $('#linkedusers').html('');
        }

    }
    function showUserDet(user)
    {
        if (user > 0)
        {
            jQuery.ajax({type: 'GET',
                url: '<?= Yii::app()->createUrl('admin/user/details') ?>',
                dataType: 'html',
                data: {"user": user},
                success: function (data)
                {
                    showuser = bootbox.dialog({
                        message: data,
                        title: 'User Details',
                        size: 'large', onEscape: function ()
                        {
                        }
                    });
                    showuser.on('hidden.bs.modal', function (e)
                    {
                        $('body').addClass('modal-open');
                    });
                    return true;
                },
                error: function (x)
                {
                    alert(x);
                }
            });
        }
    }
    
    function showUserView(user)
    {
         if (user > 0)
        {
            jQuery.ajax({type: 'GET',
                url: '<?= Yii::app()->createUrl('admin/user/view') ?>',
                dataType: 'html',
                data: {"id": user},
                success: function (data)
                {
                    showuser = bootbox.dialog({
                        message: data,
                        title: 'User Details',
                        size: 'large', onEscape: function ()
                        {
                        }
                    });
                    showuser.on('hidden.bs.modal', function (e)
                    {
                        $('body').addClass('modal-open');
                    });
                    return true;
                },
                error: function (x)
                {
                    alert(x);
                }
            });
        }
    }

    function linkUser(obj, userId)
    {
        if ($(obj).hasClass('bg-warning'))
        {
            $('.linkuserbtn').removeClass('bg-success');
            $('.linkuserbtn').addClass('bg-warning');
            $('#BookingUser_bkg_user_id').val(userId);
            $(obj).removeClass('bg-warning');
            $(obj).addClass('bg-success');
            var chngEmail = $(obj).attr('email');
            var chngCode = $(obj).attr('code');
            var chngPhone = $(obj).attr('phone');
            var chngFname = $(obj).attr('fname');
            var chngLname = $(obj).attr('lname');
            var phone = $('#BookingUser_bkg_contact_no').val();
            var code = $('#BookingUser_bkg_country_code2').val();
            var email = $('#BookingUser_bkg_user_email').val();
            if (chngEmail != '' && chngEmail != null && chngEmail != undefined && email == "")
            {
                $('#BookingUser_bkg_user_email').val(chngEmail);
            }
            if (chngFname != '' && chngFname != null && chngFname != undefined)
            {
                $('#BookingUser_bkg_user_fname').val(chngFname);
            }
            if (chngLname != '' && chngLname != null && chngLname != undefined)
            {
                $('#BookingUser_bkg_user_lname').val(chngLname);
            }
            if (chngPhone != '' && chngPhone != null && chngPhone != undefined && chngPhone != "null" && phone == "")
            {
                $('#BookingUser_bkg_country_code2').val(chngCode);
                $('#BookingUser_bkg_contact_no').val(chngPhone);
            }
        } else
        {
            $('#BookingUser_bkg_user_id').val('');
            $(obj).removeClass('bg-success');
            $(obj).addClass('bg-warning');
        }
    }

    function bookingPreference()
    {
        var agtId = '';
        getBookingPreferences(agtId);
        $('#divpaidby2').show();
        $('#linkcorpdiv').addClass('hide');
        $('#linkagentdiv').addClass('hide');
        $('#trip_user').addClass('hide');
        $('#divpaidby').addClass('hide');
        $('#agtnotification').addClass('hide');
        var trip_user = $("input[name=\'Booking[trip_user]\']:checked").val();
        $('#divAgentCredit').addClass('hide');
//        if (trip_user == 3) {
//            $('#linkcorpdiv').removeClass('hide');
//            $("#bkg_agent_id").select2("val", '');
//            $('#divAgentCredit').addClass('hide');
//            $('#Booking_agentCreditAmount').val('');
//        }
        if (trip_user == 2)
        {
            $('#linkagentdiv').removeClass('hide');
            //   $("#corporate_id").select2("val", '');
            $("#bkg_agent_id").select2("val", '');
            $('#corp_addt_details').addClass('hide');
            $('#Booking_agentCreditAmount').val("");
        }
        if (trip_user == 1)
        {
            $('#linkagentdiv').addClass('hide');
            $('#linkcorpdiv').addClass('hide');
            //     $('#corporate_id').val('');
            $('#bkg_agent_id').val('');
            //         $("#corporate_id").select2("val", '');
            $("#bkg_agent_id").select2("val", '');
            getAgentBaseDiscFare();
            getAmountbyCitiesnVehicle();
            calculateAmount();
            $('#corp_addt_details').addClass('hide');
            $('#agent_notify_option').addClass('hide');
            $('#Booking_agentCreditAmount').val("");
            $('#booking_ref_code_div').addClass('hide');

        }


    }


    function showAgentCreditDiv()
    {
        var agentPaymentBy = $("input[name=\'Booking[agentBkgAmountPay]\']:checked").val();
        if (agentPaymentBy == 1)
        {
            $('#divAgentCredit').addClass('hide');
            $('#div_due_amount').addClass('hide');
            $('#Booking_agentCreditAmount').val("");
            $('#partPrefdiv').hide();
            $('#partPrefdiv2').hide();
        }
        if (agentPaymentBy == 2)
        {
            $('#divAgentCredit').removeClass('hide');
            $('#div_due_amount').removeClass('hide');
            $('#partPrefdiv').show();
            $('#partPrefdiv2').show();
        }
    }

    function getAgentBaseDiscFare()
    {
        var base_fare = Math.round($('#BookingInvoice_bkg_gozo_base_amount').val());
        var trip_user = $("input[name=\'Booking[trip_user]\']:checked").val();
        var agt_type = $("#agt_type").val();
        var agt_commisssion_value = $('#agt_commission_value').val();
        var agt_commission = $('#agt_commission').val();
        if (base_fare != '' && base_fare != null && base_fare != undefined && base_fare != 0 && base_fare != '0')
        {
            if (
                    (agt_commisssion_value != '' && agt_commisssion_value != null && agt_commisssion_value != undefined && agt_commisssion_value != "null") &&
                    (agt_commission != '' && agt_commission != null && agt_commission != undefined && agt_commission != "null") &&
                    (trip_user == 2 && agt_type != 2 && agt_type != '' && ($('#bkg_agent_id').val() != '' && $('#bkg_agent_id').val() != null && $('#bkg_agent_id').val() != undefined && $('#bkg_agent_id').val() != '0' && $('#bkg_agent_id').val() != 0))
                    )

            {
                agt_commisssion_value = parseInt(Math.round(agt_commisssion_value));
                var totalAmount = Math.round($('#BookingInvoice_bkg_total_amount').val());
                totalAmount = (totalAmount == '') ? 0 : parseInt(totalAmount);
                var vendorAmount = Math.round($('#BookingInvoice_bkg_vendor_amount').val());
                vendorAmount = (vendorAmount == '') ? 0 : parseInt(vendorAmount);
                var gozo_amount = totalAmount - vendorAmount;
                if (agt_commisssion_value == 1)
                {
                    var agentMarkup = Math.round(base_fare * (agt_commission / 100));
                } else
                {
                    var agentMarkup = agt_commission;
                }
                if (agentMarkup > gozo_amount)
                {
                    base_fare = base_fare - gozo_amount;
                } else
                {
                    base_fare = base_fare - Math.round(agentMarkup);
                }
                $('#BookingInvoice_bkg_base_amount').val(base_fare);
            } else
            {
                $('#BookingInvoice_bkg_base_amount').val(base_fare);
            }
        }
    }
    function getAgentDetails(agtId)
    {

        if (agtId != '' && agtId != null)
        {
            jQuery.ajax({type: 'GET',
                url: '<?= Yii::app()->createUrl('admin/agent/agentsbytype') ?>',
                dataType: 'json',
                data: {"agt_id": agtId},
                async: false,
                success: function (data)
                {
                    if (data.type == 2)
                    {
                        $('#agent_notify_option').removeClass('hide');
                        $('#agt_type').val(data.notifyDetails.agt_type);
                        $('#Booking_bkg_copybooking_name').val(data.notifyDetails.agt_copybooking_name);
                        $('#Booking_bkg_copybooking_email').val(data.notifyDetails.agt_copybooking_email);
                        $('#Booking_bkg_copybooking_phone').val(data.notifyDetails.agt_copybooking_phone);
                        $('#agt_commission_value').val(data.notifyDetails.agt_commission_value);
                        $('#agt_commission').val(data.notifyDetails.agt_commission);
                        var $select = $("#Booking_bkg_copybooking_country").selectize();
                        var selectize = $select[0].selectize;
                        selectize.setValue(data.notifyDetails.agt_phone_country_code);
                    }
                    $('#booking_ref_code_div').removeClass('hide');
                },
                error: function (x)
                {
                    alert(x);
                }
            });
        }
    }

    function shownotifyopt()
    {
        var agent_id = $("#bkg_agent_id").select2("val");
//        if ($('#bkg_agent_id').val() == '' || $('#bkg_agent_id').val() == null || $('#bkg_agent_id').val() == 'undefined') {
//            var agent_id = $("#corporate_id").select2("val");
//        }
        var agentnotifydata = $('#agentnotifydata').val();
        jQuery.ajax({type: 'POST',
            url: '<?= Yii::app()->createUrl('admin/agent/bookingmsgdefaults') ?>',
            dataType: 'html',
            data: {"agent_id": agent_id, "notifydata": agentnotifydata, "YII_CSRF_TOKEN": $('input[name="YII_CSRF_TOKEN"]').val()},
            success: function (data)
            {
                shownotifydiag = bootbox.dialog({
                    message: data,
                    title: '',
                    size: 'large',
                    onEscape: function ()
                    {
                    }
                });
                shownotifydiag.on('hidden.bs.modal', function (e)
                {
                    $('body').addClass('modal-open');
                });
                return true;
            },
            error: function (x)
            {
                alert(x);
            }
        });
    }

    function savenotifyoptions()
    {
        jQuery.ajax({type: 'POST',
            url: '<?= Yii::app()->createUrl('admin/agent/bookingmsgdefaults') ?>',
            dataType: 'json',
            data: $('#agent-notification-form').serialize(),
            success: function (data)
            {
                $('#agentnotifydata').val(JSON.stringify(data.data));
                bootbox.hideAll();
                alert('Notification details saved successfully.');
                return true;
            },
            error: function (x)
            {
                alert(x);
            }
        });
        return false;
    }
    function populateSource(obj, cityId)
    {

        obj.load(function (callback)
        {
            var obj = this;
            if ($sourceList == null)
            {
                xhr = $.ajax({
                    url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery', ['apshow' => 1])) ?>',
                    dataType: 'json',
                    data: {
                        // city: cityId
                    },
                    //  async: false,
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
    $('#Booking_bkg_from_city_id1').change(function ()
    {
        // alert('3');
        $('#Booking_bkg_from_city_id').val($('#Booking_bkg_from_city_id1').val()).change();
    });
    $('#Booking_bkg_to_city_id1').change(function ()
    {
        $('#Booking_bkg_to_city_id').val($('#Booking_bkg_to_city_id1').val()).change();
    });
    $('#Booking_preData').change(function ()
    {
//        alert('predata');
    });
    function savepckdel()
    {

        var fromlocation = $("#first_pickup_popup").val();
        var tolocation = $("#last_dropoff_popup").val();
//        var fromlocation = $("#pickup_address").val();
//        var tolocation = $("#dropoff_address").val();
        var date = $('#Booking_bkg_pickup_date_date').val();
        var time = $('#Booking_bkg_pickup_date_time').val();
        var packageID = $("#packageID").val();
        $href = '<?= Yii::app()->createUrl('admin/package/updatePackage') ?>';
        jQuery.ajax({
            type: 'GET',
            url: $href,
            dataType: 'json',
            data: {"packageID": packageID, "fromlocation": fromlocation, "tolocation": tolocation, "pickupDt": date, "pickupTime": time},
            success: function (data)
            {
                // alert(JSON.stringify(data));

                packagebootbox.hide();
                packagebootbox.remove();
                var packageDel = data.packageModel;
                var count = packageDel.length;
                var lastRow = count - 1;
                var html = "";
                var upTb = '<table class="table-bordered111" border="1" cellpadding="10" width="100%" id="packagetb">\n\
                    <thead><tr><th>1From</th><th>To</th><th>From Location</th><th>To Location</th><th>Date</th><th>Distance</th><th>Duration</th><th>No of s</th><th>No of Nights</th></tr></thead><tbody>';
                var downTb = '</tbody></table>';
                $.each(packageDel, function (key, value)
                {
                    var sl = key + 1;
                    if (key == 0)
                    {
                        var firstFromLocaion = fromlocation;
                    } else
                    {
                        firstFromLocaion = value['pickup_address'];
                    }

                    var pdate = value['pickup_date'] + ' ' + value['pickup_time'];

                    if (key == lastRow)
                    {
                        var lastToLocaion = tolocation;
                    } else
                    {
                        lastToLocaion = value['drop_address'];
                    }
                    html = html + '<tr class="packagerow">\n\
			<td id="fcitycreate' + sl + '"><b>' + value['pickup_city_name'] + '</b></td>\n\
<td id="tcitycreate' + sl + '"><b>' + value['drop_city_name'] + '</b></td>\n\
<td id="fcitylocation' + sl + '">' + firstFromLocaion + '</td>\n\
<td id="tcitylocation' + sl + '">' + lastToLocaion + ' </td>\n\
<td id="fdatecreate' + sl + '">' + pdate + ' </td>\n\
<td id="fdistcreate' + sl + '">' + value['distance'] + '</td>\n\
<td id="fduracreate' + sl + '">' + value['duration'] + '</td>\n\
<td id="noOfDayCount' + sl + '">' + value['pcd_day_serial'] + '</td>\n\
<td id="noOfNightCount' + sl + '">' + value['pcd_night_serial'] + '</td></tr>';
                });
                // $('#return_time').val($jsonArrMulticity[($count - 1)].pickup_time);
                // $('#return_date').val($jsonArrMulticity[($count - 1)].pickup_date);

                var lastNightSerial = packageDel[(count - 1)].pcd_night_serial;
                var retunDate = packageDel[(count - 1)].date;
                getDropTiming(lastNightSerial, retunDate);
                $('#return_date').val(packageDel[(count - 1)].date);
                var str = packageDel[(count - 1)].date;
                var ret = str.split(" ");
                var time = ret[1];
                $('#return_time').val(time);
                $('#packagetb').html(upTb + html + downTb);
                $('#packageJson').val(JSON.stringify(data.packageModel));
                $('#multicityjsondata').val(JSON.stringify(data.packageModel));
                $('#first_pickup').val(fromlocation);
                $('#last_dropoff').val(tolocation);
                return;
            }
        });
    }
    function assignPackageDt()
    {
        var currentDate = new Date();
        // alert (currentDate);
        var date = $('#Booking_bkg_pickup_date_date').val();
        var time = $('#Booking_bkg_pickup_date_time').val();
        var pckageID = $("#pckageID").val();
        var fromlocation = $("#first_pickup").val();
        var tolocation = $("#last_dropoff").val();
        $href = '<?= Yii::app()->createUrl('admin/package/assignPackageDtTime') ?>';
        jQuery.ajax({
            type: 'GET',
            url: $href,
            dataType: 'json',
            data: {"pckageID": pckageID, "pickupDt": date, "pickupTime": time,
                "fromlocation": fromlocation, "tolocation": tolocation},
            success: function (data)
            {
                //var data = $.parseJSON(data);
                //alert(JSON.stringify(data));
                //packagebootbox.hide();
                //packagebootbox.remove();

                var packageDel = data.packageModel;
                var html = "";
                var upTb = '<table class="table-bordered11" border="1" cellpadding="10" width="100%" id="packagetb">\n\
                    <thead><tr><th>From</th><th>To</th>' +
//<th>From Location</th><th>To Location</th>\n\
                        '<th>Date</th><th>Distance</th><th>Duration</th><th>No of days</th><th>No of Nights</th></tr></thead><tbody>';
                var downTb = '</tbody></table>';
                var count = packageDel.length;
                var lastRow = count - 1;
                $.each(packageDel, function (key, value)
                {
                    var sl = key + 1;
                    // var packagedelID = $.trim(value['pcd_pck_id']);
                    if (key == 0)
                    {
                        var firstFromLocaion = fromlocation;
                    } else
                    {
                        firstFromLocaion = value['pickup_address'];
                    }
                    if (key == lastRow)
                    {
                        var lastToLocaion = tolocation;
                    } else
                    {
                        lastToLocaion = value['drop_address'];
                    }
                    var pdate = value['pickup_date'] + ' ' + value['pickup_time'];
                    html = html + '<tr class="packagerow">\n\
<td id="fcitycreate' + sl + '"><b>' + value['pickup_city_name'] + '</b></td>\n\
<td id="tcitycreate' + sl + '"><b>' + value['drop_city_name'] + '</b></td>'
//<td id="fcitylocation' + sl + '">' + firstFromLocaion + '</td>
//<td id="tcitylocation' + sl + '">' + lastToLocaion + ' </td>\n\
                            + '<td id="fdatecreate' + sl + '">' + pdate + ' </td>\n\
<td id="fdistcreate' + sl + '">' + value['distance'] + '</td>\n\
<td id="fduracreate' + sl + '">' + value['duration'] + '</td>\n\
<td id="noOfDayCount' + sl + '">' + value['daycount'] + '</td>\n\
<td id="noOfNightCount' + sl + '">' + value['nightcount'] + '</td>\n\
</tr>';
                });
                var lastNightSerial = packageDel[(count - 1)].pcd_night_serial;
                var retunDate = packageDel[(count - 1)].date;
                getDropTiming(lastNightSerial, retunDate);
                $('#return_date').val(packageDel[(count - 1)].date);
                var str = packageDel[(count - 1)].date;
                var ret = str.split(" ");
                var time = ret[1];
                $('#return_time').val(time);
                $('#packagetb').html(upTb + html + downTb);
                $('#packageJson').val(JSON.stringify(data.packageModel));
                $('#multicityjsondata').val(JSON.stringify(data.multijsondata));
                return;
            }
        });
    }




    function getDropTiming(night, date)
    {
        $href = '<?= Yii::app()->createUrl('admin/package/assignDropoffTime') ?>';
        jQuery.ajax({
            type: 'GET',
            url: $href,
            dataType: 'json',
            data: {"night": night, "date": date},
            success: function (data)
            {
                // alert(data.dropdate);
                var dropdate = data.dropdate;
                var ret = dropdate.split(" ");
                var time = ret[1];
                $('#drop_date').val(dropdate);
                $('#drop_time').val(time);
            }
        });
    }

    $("#Booking_bkg_pickup_date_time").change(function ()
    {
<?php
if ($package)
{
	?>
	        assignPackageDt();
<? } ?>
    });
    $('#editLocation').unbind("click").bind("click", function ()
    {
        var packageID = $("#pckageID").val();
        $href = '<?= Yii::app()->createUrl('admin/booking/editPackage') ?>';
        jQuery.ajax({type: 'GET', url: $href, data: {"packageID": packageID},
            success: function (data)
            {
                packagebootbox = bootbox.dialog({
                    message: data,
                    size: 'medium',
                    title: 'Edit Package Info',
                });
            }
        });
    });
    function editPackageInfo(ID, type)
    {
        var $bkgtype = $("#Booking_bkg_booking_type").val();
        var pcdID = ID;
        $href = '<?= Yii::app()->createUrl('admin/booking/editPackage') ?>';
        jQuery.ajax({type: 'GET', url: $href, data: {"pcdID": pcdID, "type": type},
            success: function (data)
            {
                packagebootbox = bootbox.dialog({
                    message: data,
                    size: 'medium',
                    title: 'Edit Package Info',
//                    onEscape: function () {
//                        packagebootbox.hide();
//                        packagebootbox.remove();
//                    },
                });
//                packagebootbox.on('hidden.bs.modal', function (e) {
//                    $('body').addClass('modal-open');
//                });
            }
        });
    }




    $('#<?= CHtml::activeId($model, "bkg_add_my_trip") ?>').change(function ()
    {
        if ($('#<?= CHtml::activeId($model, "bkg_add_my_trip") ?>').is(':checked'))
        {
            $("#addtomytripreq").show();
        } else
        {
            $("#addtomytripreq").hide();
        }
    });
    $("#BookingAddInfo_bkg_spl_req_lunch_break_time").change(function ()
    {
        var brkType = $('#Booking_bkg_booking_type').val();
        var source = $('#Booking_bkg_from_city_id1').val();
        var destination = $('#Booking_bkg_to_city_id1').val();
        var vehicle = $('#Booking_bkg_vehicle_type_id').val();
        if (brkType == '1' && source != '' && destination != '' && vehicle != '')
        {
            calculateAmount();
        } else
        {
            $("#BookingAddInfo_bkg_spl_req_lunch_break_time").val('0');
        }


    });
    function updateAdress(id, type)
    {
//        var data = $("#multicityjsondata").val();
//        var arrDt = JSON.parse(data);
//        $.each(arrDt, function (key, value) {
//            if (key == id)
//            {
//                if (type == 1)
//                {
//                    var pickupAdd = $("#Booking_bkg_pickup_address" + id).val();
//                    value['pickup_address'] = pickupAdd;
//                } else {
//                    var dropoffAdd = $("#Booking_bkg_drop_address" + id).val();
//                    value['drop_address'] = dropoffAdd;
//                }
//            }
//        });
//        $("#multicityjsondata").val(JSON.stringify(arrDt));
//    }
//	
//	if('<? //=$model->preData                 ?>'!='null' && '<? //=$model->preData                 ?>' != ''  ){
//		   $jsonArrMulticity1.push({
//                                            "pickup_city": fromCity,
//                                            "drop_city": $('#to_city').val(),
//                                            "pickup_city_name": pick_city_name,
//                                            "drop_city_name": $('#to_city').select2('data').text,
//                                            "pickup_date": $('#pickup_date').val(),
//                                            "pickup_time": $('#pickup_time').val(),
//                                            "date": data.date,
//                                            "duration": data.duration,
//                                            "estimated_date": $('#estimated_pickup_date').val(),
//                                            "distance": data.distance,
//                                            "return_date": "",
//                                            "return_time": "",
//                                            "day": data.day,
//                                            "totday": data.totday,
//                                            "pickup_cty_lat": data.pickup_cty_lat,
//                                            "pickup_cty_long": data.pickup_cty_long,
//                                            "drop_cty_lat": data.drop_cty_lat,
//                                            "drop_cty_long": data.drop_cty_long,
//                                            "pickup_cty_bounds": data.pickup_cty_bounds,
//                                            "drop_cty_bounds": data.drop_cty_bounds,
//											"pickup_cty_radius": data.pickup_cty_radius,
//                                            "drop_cty_radius": data.drop_cty_radius,
//											"pickup_cty_is_airport": data.pickup_cty_is_airport,
//                                            "drop_cty_is_airport": data.drop_cty_is_airport,
//                                        });
//		$abs = <? //=$model->preData                 ?>;
//		alert($abs[0].pickup_city_name);
    }
    function copyItinerary()
    {
        var $temp = $("<textarea>");
        var brRegex = /<br\s*[\/]?>/gi;
        $("body").append($temp);
        $temp.val($("#divQuote").html().replace(brRegex, "\r\n")).select();
        document.execCommand("copy");
        $temp.remove();
        $("#itenaryButton").text('Ready to paste');
        $("#itenaryButton").removeClass("btn-primary");
        $("#itenaryButton").addClass("btn-success");
    }

    $('#btnQuote').click(function () {
        //alert("sdfsdfdsfdfs");
        if ($('#bkg_agent_id').val() == "")
        {
            $('#createQuote').val(1);
            $('form').submit();
//			if(countSubmit==0){
//				countSubmit++;
//				 
//			}


        } else
        {
            alert('You can not select agent on create quote');
        }
    });

    $('#Booking_trip_user_0').click(function () {
        if ($('#Booking_trip_user_0').is(':checked') == true)
        {
            $('#btnsbmt').css('display', 'none');
            $('#btnsbmt').prop('disabled', true);
            $('#btnQuote').css('display', 'inline-block');
            $('#btnQuote').prop('disabled', false);
        }
    });
    $('#Booking_trip_user_1').click(function () {
        if ($('#Booking_trip_user_1').is(':checked') == true)
        {
            $('#btnQuote').css('display', 'none');
            $('#btnsbmt').prop('disabled', false);
            $('#btnsbmt').css('display', 'inline-block');
            $('#btnQuote').prop('disabled', true);
        }
    });

    $('.txtpl').change(function () {
        hyperModel.findAddress(this.id);
    });

</script>
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key=<?= $api ?>&libraries=places&"></script>
<input id="map_canvas" type="hidden">

