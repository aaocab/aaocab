<style>
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; 
        margin: 0; 
    }
    input[type="number"] {
        -moz-appearance: textfield;
    }
    .summary-div{
	padding: 5px !important;
    }
    .pay_panel{ background: #deebfe;}
    .pay_panel .nav li a{ 
        display: block; border: #fff 1px solid; margin-right: 0; color: #000; text-transform: uppercase; font-size: 16px;
        background: #ededed;
        background: -moz-linear-gradient(top,  #ededed 0%, #ffffff 100%);
        background: -webkit-linear-gradient(top,  #ededed 0%,#ffffff 100%);
        background: linear-gradient(to bottom,  #ededed 0%,#ffffff 100%);
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ededed', endColorstr='#ffffff',GradientType=0 );}

    .pay_panel .nav li a:hover{ background: #fd9e5d!important;}
    .pay_panel .nav a.active{ background: #ff6700!important; color: #fff;}
    .tr {
  display: flex;
}
.th, .td {
  border-top: 1px solid #ccc;
  border-right: 1px solid #ccc;
  padding: 4px 8px;
  flex: 1;
  font-size:14px;
  overflow: auto;
  word-wrap: break-word;
}
.bigCol
{
  max-width:62%;
}
.smallCol
{
  max-width:18%;		
}
.th {
  font-weight: bold;
}
.th[role="rowheader"] {
  background-color: #fff;
}
.th[role="columnheader"] {
  background-color: #fff;
}

</style>
<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/js/jquery.mask.min.js');
/* @var $bModel Booking */
$ccMonth = ['' => 'MM'];
$ccYear = ['' => 'YYYY'];
for ($cm = 1; $cm <= 12; $cm++) {
    $j = str_pad($cm, 2, '0', STR_PAD_LEFT);
    $ccMonth[$j] = $j;
}
$scy = date('Y');
$ecy = date('Y') + 30;
for ($cy = $scy; $cy <= $ecy; $cy++) {
    $ccYear[$cy] = $cy;
}
?>
<div class="container">
    <div class="row">
      <div class="row blue2 white-color" style="margin: 10px 0px 10px 0px!important">
	    <div class="col-xs-12 summary-div">
		<h4>Trip ID: <?= $cabModel->bcb_id ?>
<span style="float: right;">Amount To Collect: <i class="fa fa-inr"></i> <?= round($model->bkgInvoice->bkg_due_amount) ?></span>
</h4>
				
	    </div>
	</div>
        <?php
        foreach ($bModels as $bModel) {
            $fcity = Cities::getName($bModel->bkg_from_city_id);
            $tcity = Cities::getName($bModel->bkg_to_city_id);
            //$fcity		 = ($bModel->bkg_pickup_pincode != '') ? $fcity1 . '-' . $bModel->bkg_pickup_pincode : $fcity1;
            //$tcity		 = ($bModel->bkg_drop_pincode != '') ? $tcity1 . '-' . $bModel->bkg_drop_pincode : $tcity1;
            $routeCityList = $bModel->getTripCitiesListbyId();
            $ct = implode(' &#10147; ', $routeCityList);
            foreach ($bModel->bookingRoutes as $key => $bookingRoute) {
                $pickupCity[] = $bookingRoute->brt_from_city_id;
                $dropCity[] = $bookingRoute->brt_to_city_id;
                $pickup_date[] = $bookingRoute->brt_pickup_datetime;
                $temp_last_date = strtotime($bookingRoute->brt_pickup_datetime) + $bookingRoute->brt_trip_duration;
                $drop_date_time = date('Y-m-d H:i:s', $temp_last_date);
            }
            $pickup_date_time = $pickup_date[0];
            $locationArr = array_unique(array_merge($pickupCity, $dropCity));
            $dateArr = array($pickup_date_time, $drop_date_time);
            #print_r($dateArr);exit;
            #print_r($locationArr);exit;
            $note = DestinationNote::model()->showBookingNotes($locationArr, $dateArr, $showNoteTo = 2);
            $showCustPhone = (BookingTrackLog::model()->getdetailByEvent($bModel->bkg_id, 201))?true:false;
			?>
            <div class="col-12 text-center mt0 mb20">
                <span style="color:red"><b> * Driver App must be used. <a href="#section2">See Trip Rules</a></b></span>
				<?php
                if ($bModel->bkg_agent_id > 0) {
                    $agtArr = Agents::model()->getDetailsbyId($bModel->bkg_agent_id);
                    if (count($agtArr) > 0 && $agtArr['agt_type'] == 1) {
                        echo "(CORPORATE)";
                    } else {
                        echo "(AGENT)";
                    }
                }
                ?>
            </div>


            <div class="col-12">
                <div class="bg-white-box">
                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-12 font-18 mb10"><b>CUSTOMER DETAILS:</b></div>
                                <?php
                                if ($bModel->bkg_status > 2) {
                                    if ($bModel->bkgTrack->btk_cust_details_viewed == 0) {
                                        ?>

                                      <div id="viewCustDetails" class="col-12 col-sm-7"><a href="" class="btn btn-primary btn-sm mb5 mr5 color-white" onclick="viewCustomerContact(<?= $bModel->bkg_id ?>)" title="View Customer Details" style="">Click here to view Customer Details</a>
                                        </div>

                                        <?php
                                    } else {
                                        ?>
                                        <div class="col-12" id="customerDetails">
                                            <div class="row mb10">

                                                <div class="col-12 col-md-5 col-lg-4 pl5"><b>Full Name:</b></div>
                                                <div class="col-12 col-md-7 col-lg-8 pl5"><?= $bModel->bkgUserInfo->getUsername() ?></div>

                                            </div>
											<div class="row mb10">

                                                <div class="col-12 col-md-5 col-lg-4 pl5"><b>Email:</b></div>
                                                <div class="col-12 col-md-7 col-lg-8 pl5"><?= $bModel->bkgUserInfo->bkg_user_email; ?></div>

                                            </div>
                                            <?php
											if($showCustPhone)
											{
                                            if ($bModel->bkgUserInfo->bkg_contact_no != '') {
                                                ?>
                                                <div class="row">
                                                    <div class="col-12 col-md-5 col-lg-4 pl5"><b>Primary Phone:</b></div>
                                                    <div class="col-12 col-md-7 col-lg-8 pl5">+<?= $bModel->bkgUserInfo->bkg_country_code ?><?= $bModel->bkgUserInfo->bkg_contact_no ?></div>
                                                </div>
                                                <?php
                                            }
                                            if ($bModel->bkgUserInfo->bkg_alt_contact_no != '') {
                                                ?>
                                                <div class="row">
                                                    <div class="col-12 summary-div" style="font-size:12px;">
                                                        <div class="col-12 col-sm-5 pl5"><b>Alternate Phone:</b></div>
                                                        <div class="col-12 col-sm-7 pl5">+<?= $bModel->bkgUserInfo->bkg_alt_country_code ?><?= $bModel->bkgUserInfo->bkg_alt_contact_no ?></div>
                                                    </div>
                                                </div>
                                                <?php
												}
											}
                                            ?>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>
                            </div>	
                        </div>
                    </div>

                </div>
            </div>   

            <?php
            if (!empty($note)) {
                ?>

                <div class="col-12 mt30">
                    <div class="font-18 color-white bg-blue3 text-center text-uppercase p10"><b>Special instructions & advisories that may affect your planned travel</b></div>
                    <table class="table table-bordered">
                        <tr>
                            <th scope="col" width="15%">Place</th>
                            <th scope="col">Note</th>
                            <th scope="col" width="18%">Valid From</th>
                            <th scope="col" width="18%">Valid To</th>
                        </tr>

                        <tbody>
                            <?php
                                for ($i = 0; $i < count($note); $i++) {
                            ?> 
                            <tr>
                                    <th scope="row">
                                        <?php if ($note[$i]['dnt_area_type'] == 1) { ?>
                                                    <?= ($note[$i]['dnt_zone_name']) ?>
                                        <?php }?>
                                        <?php if ($note[$i]['dnt_area_type'] == 3) { ?>
                                            <?= ($note[$i]['cty_name']) ?>
                                        <?php } else if ($note[$i]['dnt_area_type'] == 2) { ?>
                                            <?= ($note[$i]['dnt_state_name']) ?>
                                        <?php } else if ($note[$i]['dnt_area_type'] == 0) { ?>
                                            <?= "Applicable to all" ?>
                                        <?php } else if ($note[$i]['dnt_area_type'] == 4) { ?>
                                            <?= Promos::$region[$note[$i]["dnt_area_id"]] ?>
                                            <?php
                                        }
                                        ?>
                                    </th>
                                    <td><?= ($note[$i]['dnt_note']) ?></td>
                                    <td><?= (DateTimeFormat::DateTimeToLocale($note[$i]['dnt_valid_from'])) ?></td>
                                    <td><?= (DateTimeFormat::DateTimeToLocale($note[$i]['dnt_valid_to'])) ?></td>
                            </tr>
                             <?php
                                }
                             ?>
                        </tbody>
                    </table>
                </div>

                <?php
            }
            ?>
            <div class="col-12 mt20">
                <div class="bg-white-box">
                    <?php
                    $sccVndDesc = $bModel->bkgSvcClassVhcCat->scc_ServiceClass->scc_vnd_desc;
                    $arrServiceDesc = json_decode($sccVndDesc);
                    $serviceVndDesc = '';
                    $serviceVndDesc .= '<ol>';
                    foreach ($arrServiceDesc as $key => $value) {
                        $serviceVndDesc .= '<li>' . $value . '</li>';
                    }
                    $serviceVndDesc .= '</ol>';
                    ?>
					<div class="col-12 font-18 mb10 pl0"><b>RENTAL DETAILS:</b></div>
                        <div class="row summary-div">
                            <div class="col-12 col-sm-5"><b>Car Package For:</b></div>

                            <div class="col-12 col-sm-7">
                               <?php echo $ct ?>
                            </div>

                        </div>

                    <div class="row summary-div">
                        <div class="col-12 col-sm-5"><b>Cab type & Class:</b></div>
                        <div class="col-12 col-sm-7"><?= $bModel->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label . '('. $bModel->bkgSvcClassVhcCat->scc_ServiceClass->scc_label. ') - '.$bModel->bkgVehicleType->vht_make.' '.$bModel->bkgVehicleType->vht_model?>
						<?php 
							$sccDesc = $bModel->bkgSvcClassVhcCat->scc_ServiceClass->scc_desc;
							$arrServiceDesc = json_decode($sccDesc);
							$serviceDesc = '';	
							foreach ($arrServiceDesc as $key => $value)
							{
								if($key != 0)
								{
									$serviceDesc .= ', '; 
								}
								$serviceDesc .= $value;
							}
							?>
							<?= $serviceDesc ?>
						</div>
						</div>

                   <div class="row summary-div">
                        <div class="col-12 col-sm-5"><b>Pickup Address:</b></div>
                        <div class="col-12 col-sm-7"><a href="https://maps.google.com/?q=<?php echo $bModel->bookingRoutes[0]->brt_from_latitude . "," . $bModel->bookingRoutes[0]->brt_from_longitude; ?>" target="_blank"><?= $bModel->bkg_pickup_address; ?></a>
						</div>
                    </div>

                    <div class="row summary-div">
                        <div class="col-12 col-sm-5"><b>Final Drop Address:</b></div>
                        <div class="col-12 col-sm-7">
						<?php $btrCount =count($bModel->bookingRoutes)-1;?>
					<a href="https://maps.google.com/?q=<?php echo $bModel->bookingRoutes[$btrCount]->brt_to_latitude . "," . $bModel->bookingRoutes[0]->brt_to_longitude; ?>" target="_blank"><?= $bModel->bkg_drop_address; ?></a>
						</div>
                    </div>

                    <div class="row summary-div">
                        <div class="col-12 col-sm-5"><b>Journey Type:</b></div>
                        <div class="col-12 col-sm-7"><?= $bModel->getBookingType($bModel->bkg_booking_type); ?></div>
                    </div>

                    <div class="row summary-div">
                        <div class="col-12 col-sm-5"><b>Pickup  Date Time:</b></div>
                        <div class="col-12 col-sm-7"><?= date('jS M Y (D) h:i A', strtotime($bModel->bkg_pickup_date)); ?></div>
                    </div>

                    <div class="row summary-div">
                        <div class="col-12 col-sm-5"><b>Kms Included:</b></div>
                        <div class="col-12 col-sm-7"><?= $bModel->bkg_trip_distance; ?> km</div>
                    </div>

                    <div class="row summary-div">
                        <div class="col-12 col-sm-5"><b>Charges beyond <?= $bModel->bkg_trip_distance ?> Km:</b></div>
                        <div class="col-12 col-sm-7"><?= $bModel->bkgInvoice->bkg_rate_per_km_extra; ?> / km</div>
                    </div>
					
					<?php
				      if ($bModel->bkgInvoice->bkg_night_pickup_included == 1 && $bModel->bkg_booking_type == 1)
						{
						$isAllowencePickupText = "Night pickup allowance included (pickup time is between 10pm and 6am)."	;
						}
						if($bModel->bkgInvoice->bkg_night_drop_included == 1 && $bModel->bkg_booking_type == 1)
						{
							if($isAllowencePickupText!="")
							{
								$br = "<br />";
								
							}else{
								$br = "";
							}
						$isAllowenceDropOffText = "Night dropoff allowance included (drop off time is between 10am and 6am)."	;	
						}
				
				?>
				<div class="row summary-div">
                        <div class="col-12 col-sm-5"><b>Included with quotation:</b></div>
                        <div class="col-12 col-sm-7">
						<?= $bModel->bkg_trip_distance; ?> kms for the exact itinerary listed below. NO route deviations allowed unless listed in itinerary <?php
						if ($bModel->bkgInvoice->bkg_is_toll_tax_included == 1 && $bModel->bkgInvoice->bkg_is_state_tax_included == 1)
						{
							echo '; Toll & state taxes';
						}

//						if($model->bkg_booking_type == 2)
//						{
//							echo "<br />"."Drivers(Day)allowance is included";
//						}
					
						if($bModel->bkg_booking_type == 1)
						{
						echo "<br />".$isAllowencePickupText.$br.$isAllowenceDropOffText;
						}
						?>
						<?php
						if( $prr_day_driver_allowance >0 &&( $bModel->bkg_booking_type == 2 || $bModel->bkg_booking_type == 3))
						{
						echo "<br />"."Drivers daytime allowance of Rs. ".$prr_day_driver_allowance." per day is included in quotation";
						}
						?>
						</div>
                    </div>

					<div class="row summary-div">
                        <div class="col-12 col-sm-5"><b>Additional charges paid:</b></div>
                        <div class="col-12 col-sm-7">
						<?php
						$splRequest = 0;
						if ($model->bkgUserInfo->bkg_country_code != '91' && $model->bkgPref->bkg_send_sms == 1)
						{
							echo "International SMS fee (Rs. 99/-)";
							if ($model->bkgAddInfo->bkg_spl_req_carrier != 0 || $model->bkgAddInfo->bkg_spl_req_lunch_break_time != 0)
							{
								echo ';<br>';
							}
							$splRequest = 1;
						}
						if ($model->bkgAddInfo->bkg_spl_req_lunch_break_time != 0)
						{
							echo $model->bkgAddInfo->bkg_spl_req_lunch_break_time . ' minutes break during journey (Rs. ' . $model->bkgAddInfo->bkg_spl_req_lunch_break_time * 5 . '/-)';
							if ($model->bkgAddInfo->bkg_spl_req_carrier != 0)
							{
								echo ';<br>';
							}
							$splRequest = 1;
						}
						if ($model->bkgAddInfo->bkg_spl_req_carrier != 0)
						{
							echo 'Carrier requested in car (Rs. 150/-)';
							$splRequest = 1;
						}
						if ($splRequest == 0)
						{
							echo 'No special requests received';
						}
						?>
						</div>
                    </div>

				<div class="row summary-div">
                        <div class="col-12 col-sm-5"><b>Customer to pay separately:</b></div>
                        <div class="col-12 col-sm-7">
						<ul>
						<li>Any and all airport entry or parking charges.</li>
						<li>Any parking charges.</li>
			<?php
			if ($bModel->bkgInvoice->bkg_is_toll_tax_included == 0 && $bModel->bkgInvoice->bkg_is_state_tax_included == 0)
			{
			    echo '<li>Toll & state taxes.</li>';
			}
			?>
										
				<?php
			$night_driver_allowance_txt = ($prr_Night_driver_allowance > 0) ? "of Rs. " . $prr_Night_driver_allowance : '';
			if ($bModel->bkgInvoice->bkg_night_drop_included == 0 && $bModel->bkgInvoice->bkg_night_pickup_included == 1)
			{
				if ($bModel->bkg_booking_type == 1)
				{
					echo "<li>" . " Night drop allowance " . $night_driver_allowance_txt . " to be paid if drop off happens between (10pm and 6am).</li>";
				}
				else
				{
					echo "<li>" . "Night drop allowance " . $night_driver_allowance_txt . " to be paid to driver for each night when driving between the hours of 10pm and 6am.</li> ";
				}
			}?> 
						
				<?php 
			if ($bModel->bkgInvoice->bkg_night_pickup_included == 0 && $bModel->bkgInvoice->bkg_night_drop_included==1)
			{
				if ($bModel->bkg_booking_type == 1)
				{
					echo "<li>" . " Night pickup allowance " . $night_driver_allowance_txt . " to be paid if drop off happens between (10pm and 6am).</li>";
				}
				else
				{
					echo "<li>" . "Night pickup allowance " . $night_driver_allowance_txt . " to be paid to driver for each night when driving between the hours of 10pm and 6am.</li>";
				}
			}
			if ($bModel->bkgInvoice->bkg_night_pickup_included == 0 && $bModel->bkgInvoice->bkg_night_drop_included == 0)
			{
				if ($bModel->bkg_booking_type == 1)
				{
					echo "<li>" . " Night driving allowance " . $night_driver_allowance_txt . " to be paid if pickup or drop off happens between (10pm and 6am).</li>";
				}
				else
				{
					echo "<li>" . "Night driving allowance " . $night_driver_allowance_txt . " to be paid to driver for each night when driving between the hours of 10pm and 6am.</li>";
				}
			}
			if ($bModel->bkgInvoice->bkg_night_pickup_included == 1 && $bModel->bkgInvoice->bkg_night_drop_included == 1)
			{
				echo" ";
			}
				?>
               </ul>
						</div>
                    </div>
                    <?php
                    if ($bModel->bkgInvoice->bkg_corporate_credit > 0 && $bModel->bkgInvoice->bkg_corporate_remunerator == 2) {
                        
                    } else {
                        $tags = [];
                        if ($bModel->bkgTrail->bkg_tags != '') {
                            $tags = explode(',', $bModel->bkgTrail->bkg_tags);
                        }
                        ?>
<!--
                        <div class="row summary-div">
                            <div class="col-12 col-sm-5 font-18"><b>Amount To Collect:</b></div>
                            <div class="col-12 col-sm-7 font-18">&#x20B9;<b><?//= round($bModel->bkgInvoice->bkg_due_amount) ?></b></div>
                        </div>-->

                        <?php
                    }
                    ?>
					 <div class="row summary-div">
                        <div class="col-12 col-sm-5"><b>Number of Passengers:</b></div>
                        <div class="col-12 col-sm-7"><?= ($bModel->bkgAddInfo->bkg_no_person == '') ? '0' : $bModel->bkgAddInfo->bkg_no_person; ?></div>
                    </div>

                    <div class="row summary-div">
                        <div class="col-12 col-sm-5"><b>Number of large suitcases:</b></div>
                        <div class="col-12 col-sm-7"><?= $bModel->bkgAddInfo->bkg_num_large_bag; ?></div>
                    </div>

                    <div class="row summary-div">
                        <div class="col-12 col-sm-5"><b>Number of small bags:</b></div>
                        <div class="col-12 col-sm-7"><?= $bModel->bkgAddInfo->bkg_num_small_bag; ?></div>
                    </div>
                </div>
<br>
				<div class="bg-white-box">
						<div class="col-12 font-18 mb10 pl0"><b>FARE DETAILS:</b></div>

				<!--<div class="row summary-div">
				<div class="col-12 col-sm-5"><b>Total cost:</b></div>
				<div class="col-12 col-sm-7">
				   Rs.<strong><em><? #= number_format($bModel->bkgInvoice->bkg_total_amount) ?></em></strong>
				</div>
				</div>-->
			<?php
				if ($bModel->bkgInvoice->bkg_convenience_charge > 0)
				{
					?>
				<!--<div class="row summary-div">
				<div class="col-12 col-sm-5"><b>Applicable  Collect on Delivery(COD) fee<br />
							</strong>(to be waived if advance payment is received  48hours before start of trip)</b></div>
				<div class="col-12 col-sm-7">
				   Rs.<strong><em><? #= number_format($bModel->bkgInvoice->bkg_total_amount - $bModel->bkgInvoice->bkg_total_amount) ?></em></strong>
				</div>
				</div>
				<div class="row summary-div">
				<div class="col-12 col-sm-5"><b>Total cost: </b>(if advance payment  not received)</div>
				<div class="col-12 col-sm-7">
				   Rs.<strong><em><? #= number_format($bModel->bkgInvoice->bkg_total_amount); ?></em></strong>
				</div>
				</div>-->
				<?php
				}
				$advance	 = ($bModel->bkgInvoice->bkg_advance_amount > 0) ? $bModel->bkgInvoice->bkg_advance_amount : 0;
				if ($advance > 0)
				{
					?>
				<!--<div class="row summary-div">
				<div class="col-12 col-sm-5"><b>Advance payment received:</b></div>
				<div class="col-12 col-sm-7">
				   Rs.<strong><em><? #= number_format($advance) ?></em></strong>
				</div>
				</div>-->
				<?php
				}
				?>  
				<div class="row summary-div">
				<div class="col-12 col-sm-5"><b>Amount To Collect:</b></div>
				<div class="col-12 col-sm-7">
				   Rs.<strong><em><?= round($bModel->bkgInvoice->bkg_due_amount) ?></em></strong>
				</div>
				</div>

                </div>
                <div class="row">
                    <div class="col-12 table-responsive">
                       <div class="col-12 font-18 mb10 pl0"><b>BOOKING SUMMARY</b></div>
                        <table   align="center" class="table table-striped table-bordered">
                            <tr class="">
                                <td><b>From</b></td>
                                <td><b>To</b></td>
                                <td class="text-center"><b>Departure Date</b></td>
                                <td class="text-center"><b>Time</b></td>
                                <td class="text-center "><b>Route Distance</b></td>
                                <td class="text-center"><b>Duration</b></td>
                            </tr>
                            <?php
                            $last = 0;
                            $tdays = '';
                            foreach ($bModel->bookingRoutes as $k => $brt) {
                                ?>
                                <tr>
                                    <td><?= $brt->brtFromCity->cty_name ?><br><?= $brt->brt_from_location ?></td>
                                    <td><?= $brt->brtToCity->cty_name ?><br><?= $brt->brt_to_location ?></td>
                                    <td class="text-center"><?= DateTimeFormat::DateTimeToDatePicker($brt->brt_pickup_datetime); ?></td>
                                    <td class="text-center"><?= DateTimeFormat::DateTimeToTimePicker($brt->brt_pickup_datetime); ?></td>
                                    <td class="text-center"><?= $brt->brt_trip_distance ?> Km</td>
                                    <td class="text-center"><?= round($brt->brt_trip_duration / 60) . ' hours'; ?></td>
                                </tr>
                            <?php } ?>

                        </table>
                    </div>
                </div>
            </div>
        <?php } ?>



        <div class="row text-justify">
				
            <div class="col-12">
                
	<div class="col-12 list-type-3 font-12 mt20" id="section2">
		<h3>IMPORTANT POINTS: </h3>
		<ol type="1">
			<li><b>Must use Driver app throughout the journey.</b></li>
			  <ol type="i">
					<li>
						Start, Stop and resume trip with the driver app. 
					</li>
					<li>
						You may not close the driver app during the trip. 
					</li>
				</ol>
 <?php if (in_array($bModel->bkg_booking_type, [2,3,9,10,11])) { ?>
			<li>Customer has paid for the kms included in this trip. Customer is authorized to use the vehicle for sightseeing in the neighboring areas/cities of all destination(s) in the itinerary.</li>
 <?php }else{?>
<li>All additional pickups and drops are chargeable seperately. Sight-seeing is not included on one-way or airport transfer bookings.</li>
 <?php }?>
<li>Driver must arrive at pickup location at least 15min before pickup time.</li>
			<ol type="i">
					<li>
						Trip will attract late arrival penalty if Driver arrives at location after pickup time.
					</li>
				</ol>
			<li>Customer may request driver for ID verification. If ID not matching or not provided then cutsomer can cancel the booking for zero cancellation charges. Penalties will apply for non matching driver or non matching car.</li>
		 <?php if (in_array($bModel->bkg_status, [5,6, 7])) { ?>
		<li>Ensure that Drivers License, Car RC  and Insurance are on file with Gozo. Ensure only a commercial permit vehicle is assigned.</li>
		 <?php } else{ ?>
		<li style="color:red">aaocab REQUIRES YOU TO ASSIGN DRIVER & CAR 12 hours before pickup. Ensure that Drivers License, Car RC and Insurance are on file with Gozo. Ensure only a commercial permit vehicle is assigned.</li>
		 <?php } ?>
		<li>Driver must not entertain a change of address greater than 5km from actual destination by customer unless "change of address" is sent by system.</li>
		</ol>

		<h3>ADDITIONAL INFORMATION: </h3>

		<div class="col-12 col-sm-6 pl10"><?= $bModel->getFullInstructions(); ?></div>
	</div>
			</div>
        </div>

					
    </div>
</div>
</div>
<script>
    function viewCustomerContact(booking_id)
    {
        $href = "<?= Yii::app()->createUrl('booking/viewCustomerDetails') ?>";
        jQuery.ajax({type: 'GET',
            url: $href,
            data: {"booking_id": booking_id, "type": 2},
            success: function (data)
            {
                var obj = $.parseJSON(data);
                if (obj.success == true)
                {
                    $("#customerDetails").show();
                    $("#viewCustDetails").hide();
                    window.location.reload();
                }
            }
        });
    }

</script>
