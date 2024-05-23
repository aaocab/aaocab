
<div class="row">
    <div class="col-xs-12">
        <h3 class="font-24 weight600">Booking Summary:</h3>
        <div class="row">
            <div class="col-xs-12 col-sm-6">
                <p class="m0">Primary Passenger Name: <b><?= $model->getUsername() ?></b></p>
            </div>
            <div class="col-xs-12 col-sm-6">
                <p class="m0">Vehicle category: <b><?= SvcClassVhcCat::model()->getVctSvcList('string', 0, 0,$model->bkg_vehicle_type_id) ?></b></p>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 trip_plan">
        <h3 class="font-18 weight600">Your Trip Plan</h3>
        <div class="trip_plan">
            <table id="summary">
                <thead>
                    <tr>
                        <th>From</th>
                        <th>To</th>
                        <th>Departure Date</th>
                        <th>Time</th>
                        <th>Distance</th>
                        <th>Duration</th> 
                        <th>Days</th>
                    </tr>
                </thead>
                <tbody>
                    <?
                    $last = 0;
                    $tdays = '';
                    foreach ($model->bookingRoutes as $k => $brt) {
                        if ($k == 0) {
                            $datediff1 = 0;
                        } else {
                            $datediff1 = strtotime($model->bookingRoutes[$k]->brt_pickup_datetime) - strtotime($model->bookingRoutes[$k - 1]->brt_pickup_datetime);
                        }
                        $tdays = floor(($datediff1 / 3600) / 24) + 1;
                        $last = $k;
                        ?>
                        <tr>
                            <td><?= $brt->brtFromCity->cty_name ?>
                                <br><?= $brt->brt_from_location ?>
                            </td>
                            <td><?= $brt->brtToCity->cty_name ?>
                                <br><?= $brt->brt_to_location ?>
                            </td>
                            <td><?= DateTimeFormat::DateTimeToDatePicker($brt->brt_pickup_datetime); ?></td>
                            <td><?= DateTimeFormat::DateTimeToTimePicker($brt->brt_pickup_datetime); ?></td>
                            <td><?= $brt->brt_trip_distance ?> Km</td>
                            <td><?= round($brt->brt_trip_duration / 60) . ' hours'; ?></td>
                            <td><?= $tdays ?></td>
                        </tr>
                    <? } ?>
                </tbody>
            </table>
        </div>
        <?php
        $totDays = floor(($model->bkg_trip_duration / 60) / 24) + 1;
        $incArr = [0 => 'Excluded', 1 => 'Included'];
        $tolltax_flag = $model->bkg_is_toll_tax_included;
        $statetax_flag = $model->bkg_is_state_tax_included;
        $tolltax_value = $invModel->bkg_toll_tax;
        $statetax_value = $invModel->bkg_state_tax;
        $taxStr = (($tolltax_flag == 1 && $tolltax_value == 0) && ($statetax_flag == 1 && $statetax_value == 0)) ? '<br><i style="font-size:0.8em">(Toll Tax and State Tax Included)</i>' : '';
        
//echo "<pre>";
//print_r($invModel);
//echo "</pre>";
//echo "========<br>";
//echo "<pre>";
//print_r($model);
//echo "</pre>";

?>
        <div class="row mt20 book-summary">
            <div class="col-xs-12">
                <div class="row mb10">
                    
                        <div class="col-xs-6 col-sm-8">Estimated distance of the trip:</div>
                        <div class="col-xs-6 col-sm-4 blue-color text-right">
                            <?= $model->bkg_trip_distance ?> Km</div>
                    
                </div>
                <div class="row mb10">
                        <div class="col-xs-6 col-sm-8">Total days for the trip: </div>
                        <div class="col-xs-6 col-sm-4 blue-color text-right"><?= $totDays ?> days</div>
                </div>
                <div class="row mb10">
                        <div class="col-xs-6 col-sm-8">Base Fare: <?= $taxStr ?></div>
                        <div class="col-xs-6 col-sm-4 blue-color text-right">&#x20b9;<?= $invModel->bkg_base_amount ?></div>
                </div>


                <div class="row mb10 discounttd <?= ($invModel->bkg_discount_amount > 0) ? '' : 'hide' ?>">
                        <div class="col-xs-6 col-sm-8">Discount Amount: </div>
                        <div class="col-xs-6 col-sm-4 blue-color text-right">&#x20b9;<span class="discountAmount"><?= $invModel->bkg_discount_amount ?></span></div>
                </div>
                <div class="row mb10 <?= ($invModel->bkg_driver_allowance_amount > 0) ? '' : 'hide' ?>">
                        <div class="col-xs-6 col-sm-8">Driver Allowance: </div>
                        <div class="col-xs-6 col-sm-4 blue-color text-right">&#x20b9;<?= $invModel->bkg_driver_allowance_amount ?></div>
                </div>
                
                <?php if ($invModel->bkg_toll_tax > 0) { ?>
                    <div class="row mb10">
                            <div class="col-xs-6 col-sm-8">Toll Tax: </div>
                            <div class="col-xs-6 col-sm-4 blue-color text-right">&#x20b9;<?= $invModel->bkg_toll_tax ?></div>
                    </div>
                <?php } 
                 if ($invModel->bkg_airport_entry_fee > 0) { ?>
                    <div class="row mb10">
                            <div class="col-xs-6 col-sm-8">Airport Entry Fee: </div>
                            <div class="col-xs-6 col-sm-4 blue-color text-right">&#x20b9;<?php echo $invModel->bkg_airport_entry_fee ?></div>
                    </div>
                <?php } 
                 if ($invModel->bkg_state_tax > 0) { ?>
                    <div class="row mb10">
                            <div class="col-xs-6 col-sm-8">Other Tax: <br/><i style="font-size:0.8em">(Including State Tax / Green Tax etc)</i> </div>
                            <div class="col-xs-6 col-sm-4 blue-color text-right">&#x20b9;<?= $invModel->bkg_state_tax ?></div>
                    </div>
                <?php } 
           
                   // $staxrate = BookingInvoice::getGstTaxRate($model->bkg_agent_id, $model->bkg_booking_type);
                   // $taxRate = ($staxrate) * 0.01;

                $staxrate = $invModel->getServiceTaxRate();
                $taxLabel = ($staxrate == 5) ? 'GST' : 'Service Tax ';

               // $staxrate = $invModel->getServiceTaxRate();
               // $taxLabel = ($staxrate == 5) ? 'GST' : 'Service Tax ';
                 if ($model->bkg_cgst > 0) { ?>
                    <div class="row mb10">
                            <div class="col-xs-6 col-sm-8">CGST (@<?= Yii::app()->params['cgst'] ?>%):</div>
                            <div class="col-xs-6 col-sm-4 blue-color text-right">&#x20b9;<?= ((Yii::app()->params['cgst'] / $staxrate) * $invModel->bkg_service_tax)|0; ?></div>
                    </div>
                <? } ?>
                <? if ($model->bkg_sgst > 0) { ?>
                    <div class="row mb10">
                            <div class="col-xs-6 col-sm-8">SGST (@<?= Yii::app()->params['sgst'] ?>%):</div>
                            <div class="col-xs-6 col-sm-4 blue-color text-right">&#x20b9;<?= ((Yii::app()->params['sgst'] / $staxrate) * $invModel->bkg_service_tax)|0; ?></div>
                    </div>
                <? } ?>
                <? if ($model->bkg_igst > 0) { ?>
                    <div class="row mb10">
                            <div class="col-xs-6 col-sm-8">IGST (@<?= Yii::app()->params['igst'] ?>%):</div>
                            <div class="col-xs-6 col-sm-4 blue-color text-right">&#x20b9;<?= ((Yii::app()->params['igst'] / $staxrate) * $invModel->bkg_service_tax)|0; ?></div>
                    </div>
                <? } ?>
                <? if ($staxrate != 5) { ?>
                    <div class="row mb10">
                            <div class="col-xs-6 col-sm-8"><?= $taxLabel ?>: </div>
                            <div class="col-xs-6 col-sm-4 blue-color text-right">&#x20b9;<?= $invModel->bkg_service_tax ?></div>
                    </div>
                <? } ?>
                <div class="row mb10">
                        <div class="col-xs-6 col-sm-8 font-18">Estimated Trip cost: </div>
                        <div class="col-xs-6 col-sm-4 blue-color text-right font-18"><b>&#x20b9;<span id="bkgamtdetails111"><?= $invModel->bkg_total_amount ?></span></b></div>
               <?php //echo $invModel->bkg_base_amount ."--".$invModel->bkg_toll_tax ."--". $invModel->bkg_state_tax?>
                </div>

                <? if ($invModel->bkg_agent_markup > 0) { ?>
                    <div class="row mb10">
                            <div class="col-xs-6 col-sm-8">Agent Commision:  </div>
                            <div class="col-xs-6 col-sm-4 blue-color text-right">&#x20b9;<?= $invModel->bkg_agent_markup ?></div>
                    </div>

                    <div class="row mb10 hide">
                            <div class="col-xs-6 col-sm-8">Gross Base Fare:  </div>
                            <div class="col-xs-6 col-sm-4 blue-color text-right">&#x20b9;<?= $invModel->bkg_base_amount ?></div>
                    </div>
                <? } ?>
<!--                <div class="row mb10">
                    <label class="checkbox-inline pl40 ml30 tolltaxincluded" style="padding-top: 11px;" >
                        <input type="checkbox" name="BookingTemp[bkg_is_toll_tax_included]" id="bkg_is_toll_tax_included" value="1" <? echo ($invModel->bkg_is_toll_tax_included==1)?"checked":""?>>Toll Tax Included
                    </label>
                    <label class="checkbox-inline pl40 ml30 statetaxincluded" style="padding-top: 11px;">
                        <input type="checkbox" name="BookingTemp[bkg_is_state_tax_included]"  id="bkg_is_state_tax_included" value="1" <? echo ($invModel->bkg_is_state_tax_included==1)?"checked":""?>>State Tax Included
                    </label>
                    <label class="checkbox-inline pl40 ml30" style="padding-top: 11px;" >
                        <input type="checkbox" name="BookingInvoice[bkg_is_airport_fee_included]" id="bkg_is_airport_fee_included" value="1" <? echo ($invModel->bkg_is_airport_fee_included ==1)?"checked":""?>>Airport Fee Included
                    </label>
                </div>-->
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class='col-xs-12 hidden-lg hidden-md hidden-sm recalculateSummary <?= $hide ?> '>

        <div class="row">
            <div class="col-xs-8 bg bg-success p10">
                Base Fare <?php echo $invModel->bkg_base_amount ."===". $invModel->bkg_toll_tax ."===". $invModel->bkg_state_tax ?>
            </div>
            <div class="col-xs-1 bg bg-success p10">
                &nbsp;
            </div>
            <div class="col-xs-3 bg bg-success p10">
                <span><i class="fa fa-inr"></i> <span id="baseamt"><?php echo $invModel->bkg_base_amount + $invModel->bkg_toll_tax + $invModel->bkg_state_tax ?></span></span>
            </div>
        </div>
        <? if ($invModel->bkg_discount_amount > 0) { ?>
            <div class="row discounttd">
                <div class="col-xs-8 bg bg-danger p10">
                    Discount Amount
                </div>
                <div class="col-xs-1 bg bg-danger p10">
                    <b>-</b>
                </div>
                <div class="col-xs-3 bg bg-danger p10">
                    <span><i class="fa fa-inr"></i> <span class="discountAmount"><?= $invModel->bkg_discount_amount > 0 ?></span></span>
                </div>
            </div>
        <? } if ($invModel->bkg_additional_charge > 0) { ?>
            <div class="row">
                <div class="col-xs-8 bg bg-warning p10">
                    Additional Amount
                </div>
                <div class="col-xs-1 bg bg-warning p10">
                    <b>+</b>
                </div>
                <div class="col-xs-3 bg bg-warning p10">
                    <span><i class="fa fa-inr"></i> <span id="discount"><?= $invModel->bkg_additional_charge ?></span></span>
                </div>
            </div>
        <? } if ($invModel->bkg_driver_allowance_amount > 0) { ?>
            <div class="row">
                <div class="col-xs-8 bg bg-warning p10">
                    Driver Allowance
                </div>
                <div class="col-xs-1 bg bg-warning p10">
                    <b>+</b>
                </div>
                <div class="col-xs-3 bg bg-warning p10">
                    <span><i class="fa fa-inr"></i><span id="discount"><?= $invModel->bkg_driver_allowance_amount ?></span></span>
                </div>
            </div>
        <?php } ?>
        <div class="row">
            <div class="col-xs-8 bg bg-info p10">
                GST
            </div>
            <div class="col-xs-1 bg bg-info p10">
                <b>+</b>
            </div>
            <div class="col-xs-3 bg bg-info p10">
                <span><i class="fa fa-inr"></i> <span class="taxAmount">
                        <?= $invModel->bkg_service_tax ?></span></span>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-8 bg bg-primary p10">
                Amount Payable
            </div>
            <div class="col-xs-1 bg bg-primary p10">
                <b>=</b>
            </div>
            <div class="col-xs-3 bg bg-primary p10">
                <span><i class="fa fa-inr"></i><span class="dueAmountWithoutCOD"><?= $invModel->bkg_due_amount ?></span></span>
            </div>
        </div>
    </div>
</div>
<!--<div class='col-sm-12 hidden-xs mt20 recalculateSummary <?= $hide ?>'>
    <div class="row mt10">

        <div class="book-font">
            <table class="col-sm-12 no-border table-responsive" style="table-layout: fixed">
                <tr>
                    <td class="bg bg-success p10 text-center col-sm-2">
                        <span><i class="fa fa-inr"></i> 
                            <span id="baseamt"><?= $invModel->bkg_base_amount + $invModel->bkg_toll_tax + $invModel->bkg_state_tax ?>
                            </span>
                        </span>
                    </td>
                    <? if ($invModel->bkg_discount_amount > 0) { ?>
                        <td class=" text-center h3 col-sm-1 discounttd">
                            <b>-</b>
                        </td>
                        <td  class="bg bg-danger p10 text-center col-sm-2 discounttd" >
                            Discount Amount<br/>
                            <span><i class="fa fa-inr"></i>
                                <span class="discountAmount"><?= $invModel->bkg_discount_amount ?></span>
                            </span>
                        </td>
                    <? } if ($invModel->bkg_additional_charge > 0) { ?>
                        <td class=" text-center h3 col-sm-1">
                            <b>+</b>
                        </td>
                        <td class="bg bg-warning p10 text-center col-sm-2">
                            Additional Amount<br/>
                            <span><i class="fa fa-inr"></i>
                                <span id="discount"><?= $invModel->bkg_additional_charge ?></span>
                            </span>
                        </td>
                    <? } if ($invModel->bkg_driver_allowance_amount > 0) { ?>
                        <td class=" text-center h3 col-sm-1">
                            <b>+</b>
                        </td>
                        <td class="bg bg-warning p10 text-center col-sm-2">
                            Driver Allowance<br/>
                            <span><i class="fa fa-inr"></i>
                                <span id="discount"><?= $invModel->bkg_driver_allowance_amount ?></span>
                            </span>
                        </td>
                    <? } if ($invModel->bkg_airport_entry_fee > 0) { ?>
                    <td class=" text-center h3 col-sm-1">
                        <b>+</b>
                    </td>
                    <td class="bg bg-warning p10 text-center col-sm-2">
                            Airport Entry Fee: <br/>
                            <span><i class="fa fa-inr"></i>
                                <span id=""><?php echo $invModel->bkg_airport_entry_fee ?></span>
                            </span>
                    </td>
                    <?php } ?>
                    <td class=" text-center h3 col-sm-1">
                        <b>+</b>
                    </td>
                    <td class="bg bg-info p10 text-center col-sm-2">
                        GST<br/>
                        <span><i class="fa fa-inr"></i>
                            <span class="taxAmount">
                                <?= $invModel->bkg_service_tax ?></span>
                        </span>
                    </td>

                    <td class=" text-center h3 col-sm-1">
                        <b>=</b>
                    </td>
                    <td class="bg bg-primary p10 text-center col-sm-2">
                        Amount Payable<br/>
                        <span><i class="fa fa-inr"></i> <span class="dueAmountWithoutCOD"><?= $invModel->bkg_due_amount ?></span></span>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>-->
<script>
        $('#bkg_is_toll_tax_included').change(function () {
            debugger;
         //   alert("jgjkjjk");
        var toll_included = 0;
        var state_included = 0;
        if ($('#bkg_is_toll_tax_included').is(':checked'))
        {
            toll_included = 1;

        }
        if ($('#bkg_is_state_tax_included').is(':checked'))
        {
            state_included = 1;

        }
        getStateToll(toll_included, state_included);
    });

    $('#bkg_is_state_tax_included').change(function () {
        var toll_included = 0;
        var state_included = 0;
        if ($('#bkg_is_toll_tax_included').is(':checked'))
        {
            toll_included = 1;

        }
        if ($('#bkg_is_state_tax_included').is(':checked'))
        {
            state_included = 1;

        }
        getStateToll(toll_included, state_included);
    });

    function getStateToll(toll, state) {
      //  alert("jjjjjj");
        debugger;
        var preData = $('#<?= CHtml::activeId($model, "preData") ?>').val();
        var bkg_id4 = $('#bkg_id4').val();
        var hash4 = $('#hash4').val();

        jQuery.ajax({type: 'GET',
            url: '<?= Yii::app()->createUrl('agent/booking/booksummaryrefresh') ?>',
            dataType: 'html',
            data: {"toll": toll, "state": state, "preData": preData, "bkg_id": bkg_id4, "hash": hash4},
            success: function (data)
            {
                debugger;
                $('#booksummaryrefresh').html(data);
            },
            error: function (x) {
                //alert(x);
                console.log(x);
            }
        });
    }
</script>