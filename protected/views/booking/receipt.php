<?php
if ($invoiceList) {
    if (date($invoiceList[0]['bkg_pickup_date']) < date('Y-m-d H:i:s') && in_array($invoiceList[0]['bkg_status'], [5, 6, 7])) {
        $cartype = VehicleTypes::model()->getCarType();
        $type = $invoiceList['type'];
        $filter = new Filter();
        $rupees = 'Rupees' . ucwords($filter->convertNumberToWord($invoiceList[0]['bkg_total_amount'])) . 'only.';
        $duerupees = 'Rupees' . ucwords($filter->convertNumberToWord($invoiceList[0]['bkg_due_amount'])) . 'only.';

        $route = BookingRoute::model()->getRouteName($invoiceList[0]['bkg_id']);
        if (!$isPDF) {
            ?>
            <title><?= Filter::formatBookingId($invoiceList[0]['bkg_booking_id']); ?>-<?= $invoiceList[0]['bkg_user_fname'] . ' ' . $invoiceList[0]['bkg_user_lname']; ?>-<?= date('Ymd', strtotime($invoiceList[0]['bkg_pickup_date'])); ?></title>
            <?
        }
        ?>
        <link rel="stylesheet" href="/css/font-awesome/css/font-awesome.css">
        <link rel="stylesheet" href="/css/site.min.css">
        <link rel="stylesheet" type="text/css" href="/css/component.css"/>
        <link href="/css/hover.css" rel="stylesheet" media="all">
        <link rel="stylesheet" href="/css/site.css?v=<?= Yii::app()->params['sitecssVersion']; ?>">
        <style>
            .table-responsive td{ font-size: 12px!important; padding: 5px;}
            .table-responsive .table td{ font-size: 12px!important; padding: 8px 10px;}
            .invoice_box{ border: 1px solid #e4e4e4;}
            .invoice_white { background-color: #fff; }
        </style>
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    &nbsp;
                </div>
            </div>
        </div>
        <div class="container invoice_white">
            <div class="row">
                <div class="col-xs-12 pt10 pb20 invoice_box">
                    <div class="row">
                        <table style="width:100%;">
                            <tr>
                                <td class="mt20" style="width: 33%">
                                    <div id="printDiv">
                                        <?php
                                        if (!$isPDF) {
                                            ?>
                                            <button class="btn btn-default" onclick="printFunction()"><i class="fa fa-print"></i> Print</button>
                                        <?php } ?>
                                    </div>
                                </td>
                                <td class="text-uppercase mt20 text-center"  style="width: 34%">
                                    <span style="font-size: 20px;">I N V O I C E</span>
                                </td>
                                <td class="text-right" style="width: 33%">
                                    <div>
                                        <figure><img src="http://www.aaocab.com/images/logo2.png" alt="aaocab:India's leader in inter-city taxi | Great service. Price guarantee. Awesome reviews."></figure>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <hr class="mt5">
                    <table style="width:100%"><tr>
                            <td>
                                <b>Traveler Information:</b><br/>
                                <b>Name: </b><?= ucfirst($invoiceList[0]['bkg_user_fname']) . ' ' . ucfirst($invoiceList[0]['bkg_user_lname']); ?>,</b>
                                <? if ($invoiceList[0]['bkg_contact_no'] != '') { ?>
                                    <br/>
                                    <b>Phone: </b><?= '+' . $invoiceList[0]['bkg_alt_country_code'] . $invoiceList[0]['bkg_contact_no']; ?>
                                <? } ?>
                                <? if ($invoiceList[0]['bkg_user_email'] != '') { ?>
                                    <br/>
                                    <b>Email: </b><?= $invoiceList[0]['bkg_user_email']; ?>
                                <? } ?>

                            </td>
                            <td class="text-right" style="vertical-align: top">
                                <b>Invoice generated on: </b> <?= date('M d, Y'); ?>
                            </td>
                        </tr>
                    </table>
                    <br>

                    <?php
                    if ($invoiceList[0]['bkg_agent_id'] > 0) {
                        //print_r($invoiceList); exit;
                        ?>
                        <table style="width:100%">
                            <tr><td>&nbsp;</td></tr>
                            <tr>
                                <td>
                                    <b>Billing Information:</b> <? //= $invoiceList['bkg_agent_id']; ?><br/>
                                    <b>Company Name: </b><?= ucfirst($invoiceList[0]['agt_cmpny']); ?> ( <?= ucfirst($invoiceList[0]['agt_fn']) . ' ' . ucfirst($invoiceList[0]['agt_ln']); ?> )<br/>
                                    <b>Billing Address: </b><?= $invoiceList[0]['agt_address']; ?><br/>
                                    <!-- <b>Booking requested by and his Contact details: </b><?//= '+' . $invoiceList['agt_ph_pref'] . $invoiceList['agt_contct']; ?><br/>
                                    <b>Email: </b><?//= $invoiceList['agt_email']; ?><br/> -->
                                    <b>GSTIN: </b><?= $invoiceList[0]['agt_gstin']; ?><br/>
                                    <b>Your Reference ID: </b><?= $invoiceList[0]['bkg_agent_ref_code']; ?>
                                </td>
                            </tr>
                        </table>
                    <?php } ?>

                    <div class="row mt20">
                        <div class="col-xs-12 table-responsive">
                            <table class="table table-bordered mb0">
                                <tr>
                                    <td colspan="2"><b>Journey Details</b></td>
                                </tr>
                                <tr>
                                    <td>Route</td>
                                    <td class="text-left"><?= $route; ?></td>
                                </tr>
                                <tr>
                                    <td>Order Number</td>
                                    <td class="text-left"><?= Filter::formatBookingId($invoiceList[0]['bkg_booking_id']); ?></td>
                                </tr>
                                <?php
                                if ($invoiceList[0]['bkg_booking_type'] == 2) {
                                    ?>
                                    <tr>
                                        <td>Date and Time of Onward Trip</td>
                                        <td class="text-left"><?= date('jS M Y (D) g:i a', strtotime($invoiceList[0]['bkg_pickup_date'])); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Date and Time of Return Trip</td>
                                        <td class="text-left"><?= date('jS M Y (D) g:i a', strtotime($invoiceList[0]['bkg_return_date'])); ?></td>
                                    </tr>
                                    <?php
                                } else {
                                    ?>
                                    <tr>
                                        <td>Date and Time of Journey</td>
                                        <td class="text-left"><?= date('jS M Y (D) g:i a', strtotime($invoiceList[0]['bkg_pickup_date'])); ?></td>
                                    </tr>
                                <?php } ?>
                                <tr>
                                    <td>Type of Car</td>
                                    <td class="text-left"><?= $cartype[$type] . ' (' . $invoiceList[0]['make'] . '-' . $invoiceList[0]['model'] . ')'; ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mt20">
                        <div class="col-xs-6 table-responsive">
                            <table class="table table-bordered mb10">
                                <tr>
                                    <td class="text-right"><b>Item</b></td>
                                    <td width="30%" class="text-right"><b>Amount (Rs.)</b></td>
                                </tr>
                                <tr>
                                    <td class="text-right">Base Fare</td>
                                    <?
                                    $baseAmount = $invoiceList[0]['bkg_base_amount'];
                                    $sTax = $invoiceList[0]['bkg_service_tax'];
                                    $sTax2 = round(($sTax * 0.2) / 6);
                                    $sTax1 = $sTax - (2 * $sTax2);
                                    ?>
                                    <td class="text-right"><b><i class="fa fa-inr"></i> <?= number_format(round($baseAmount)) ?></b></td>
                                </tr>

                                <tr>
                                    <td class="text-right">Driver Allowance</td> 
                                    <td class="text-right"><i class="fa fa-inr"></i> <?= number_format($invoiceList[0]['bkg_driver_allowance_amount']) ?></td>
                                </tr>

								<?php if ($invoiceList[0]['bkg_addon_charges'] > 0) { ?>
								<tr>
                                    <td class="text-right">Addon Charge</td> 
                                    <td class="text-right"><i class="fa fa-inr"></i> <?= number_format($invoiceList[0]['bkg_addon_charges']) ?></td>
                                </tr>
								<?php } ?>

                                <?php
                                if ($invoiceList[0]['bkg_toll_tax'] > 0) {
                                    ?>
                                    <tr>
                                        <td class="text-right">Toll Tax</td>
                                        <td class="text-right"><i class="fa fa-inr"></i> <?= number_format($invoiceList[0]['bkg_toll_tax']) ?></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                                <?php
                                if ($invoiceList[0]['bkg_state_tax'] > 0) {
                                    ?>
                                    <tr>
                                        <td class="text-right">State Tax</td>
                                        <td class="text-right"><i class="fa fa-inr"></i> <?= number_format($invoiceList[0]['bkg_state_tax']) ?></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                                <tr>
                                    <td class="text-right">Convenience Charge</td> 
                                    <td class="text-right"><i class="fa fa-inr"></i> <?= number_format($invoiceList[0]['bkg_convenience_charge']) ?></td>
                                </tr>

                                <?php
                                if ($invoiceList[0]['bkg_extra_km'] > 0) {
                                    ?>
                                    <tr>
                                        <td class="text-right">Extra kms driven</td> 
                                        <td class="text-right"> <?= number_format($invoiceList[0]['bkg_extra_km']) ?> kms</td>
                                    </tr>
                                <?php } ?>
                                <?php
                                if ($invoiceList[0]['bkg_extra_km'] > 0) {
                                    ?>
                                    <tr>
                                        <td class="text-right">Additional charges (for extra kms driven) @ Rate Rs. <?= $invoiceList[0]['bkg_rate_per_km_extra'] ?></td>
                                        <td class="text-right"><i class="fa fa-inr"></i> <?= number_format($invoiceList[0]['bkg_extra_km_charge']) ?></td>
                                    </tr>
                                <?php } ?>
                                <?php
                                if ($invoiceList[0]['bkg_parking_charge'] > 0) {
                                    ?>
                                    <tr>
                                        <td class="text-right">Other charges (Parking)</td> 
                                        <td class="text-right"><i class="fa fa-inr"></i> <?= number_format($invoiceList[0]['bkg_parking_charge']) ?></td>
                                    </tr>
                                <?php } ?>
                                <?php
                                if ($invoiceList[0]['bkg_extra_toll_tax'] > 0) {
                                    ?>
                                    <tr>
                                        <td class="text-right">Other charges (Toll)</td> 
                                        <td class="text-right"><i class="fa fa-inr"></i> <?= number_format($invoiceList[0]['bkg_extra_toll_tax']) ?></td>
                                    </tr>
                                <?php } ?>
                                <?php
                                if ($invoiceList[0]['bkg_extra_state_tax'] > 0) {
                                    ?>
                                    <tr>
                                        <td class="text-right">Other charges (State tax)</td> 
                                        <td class="text-right"><i class="fa fa-inr"></i> <?= number_format($invoiceList[0]['bkg_extra_state_tax']) ?></td>
                                    </tr>
                                <?php } ?>

                                <? if (($invoiceList[0]['bkg_service_tax_rate'] == 5) || (date('Y-m-d', strtotime($invoiceList[0]['bkg_create_date'])) >= date('Y-m-d', strtotime('2017-07-01')))) {
                                    ?>
                                    <? if ($invoiceList[0]['bkg_sgst'] > 0) { ?>
                                        <tr>
                                            <td class="text-right">SGST @ <?= Yii::app()->params['sgst']; ?>%</td>
                                            <td class="text-right"><i class="fa fa-inr"></i> <?= ((Yii::app()->params['sgst'] / $invoiceList[0]['bkg_service_tax_rate']) * $sTax) ?></td>
                                        </tr>
                                    <? } ?>
                                    <? if ($invoiceList[0]['bkg_cgst'] > 0) { ?>
                                        <tr>
                                            <td class="text-right">CGST @ <?= Yii::app()->params['cgst']; ?>%</td>
                                            <td class="text-right"><i class="fa fa-inr"></i> <?= ((Yii::app()->params['cgst'] / $invoiceList[0]['bkg_service_tax_rate']) * $sTax) ?></td>
                                        </tr>
                                    <? } ?>
                                    <? if ($invoiceList[0]['bkg_igst'] > 0) { ?>
                                        <tr>
                                            <td class="text-right">IGST @ <?= Yii::app()->params['igst']; ?>%</td>
                                            <td class="text-right"><i class="fa fa-inr"></i> <?= ((Yii::app()->params['igst'] / $invoiceList[0]['bkg_service_tax_rate']) * $sTax) ?></td>
                                        </tr>
                                    <? } ?>
                                <? } else {
                                    ?>
                                    <tr>
                                        <td class="text-right">GST @ 5.6%</td>
                                        <td class="text-right"><i class="fa fa-inr"></i> <?= number_format($sTax1) ?></td>
                                    </tr>
                                    <tr>
                                        <td class="text-right">Swachh Bharat Cess @ 0.2%</td>
                                        <td class="text-right"><i class="fa fa-inr"></i> <?= number_format($sTax2) ?></td>
                                    </tr>
                                    <tr>
                                        <td class="text-right">Krishi Kalyan Cess @ 0.2%</td>
                                        <td class="text-right"><i class="fa fa-inr"></i> <?= number_format($sTax2) ?></td>
                                    </tr>
                                <? } ?>
                                <?php if ($invoiceList[0]['bkg_additional_charge'] > 0) { ?>
                                    <tr>
                                        <td class="text-right">Additional Charge @ <?= ucwords($invoiceList[0]['bkg_additional_charge_remark']) ?></td>
                                        <td class="text-right"><i class="fa fa-inr"></i> <?= number_format($invoiceList[0]['bkg_additional_charge']) ?></td>
                                    </tr>
                                <?php } ?>
                                <?php if ($invoiceList[0]['bkg_discount_amount'] > 0) { ?>
                                    <tr>
                                        <td class="text-right">Discount</td>
                                        <td class="text-right"><i class="fa fa-inr"></i> <?= number_format($invoiceList[0]['bkg_discount_amount']) ?></td>
                                    </tr>
                                <?php } ?>
                                <tr>
                                    <td class="text-right"><b>Total Amount (in figures)</b></td>
                                    <td class="text-right"><b><i class="fa fa-inr"></i> <?= number_format($invoiceList[0]['bkg_total_amount']) ?></b></td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-right"><b>Total Amount (in words): <?= $rupees ?></b></td>
                                </tr>
                            </table>
                        </div>
                    </div> 

                    <div class="row mt20">   
                        <div class="col-xs-6 table-responsive">
                            <table class="table table-bordered mb10">
                                <tr>
                                    <td class="text-right"><b>Item</b></td>
                                    <td class="text-right"><b>Amount (Rs.)</b></td>
                                </tr>

                                <?php if ($invoiceList[0]['bkg_agent_id'] > 0) { 
                                    $totPartnerCredit = ($totPartnerCredit=='')?0:$totPartnerCredit;
                                    ?>
                                    <tr>
                                        <td class="text-right">Corporate credits given (ie. Corporate coins used)</td> 
                                        <td class="text-right"><i class="fa fa-inr"></i> <?= number_format($totPartnerCredit) ?></td>
                                    </tr>
                                <?php } ?>
                                <tr>
                                    <td class="text-right">Advance received on the booking (Actual Payment received)</td> 
                                    <td class="text-right"><i class="fa fa-inr"></i>  <?= number_format($totAdvance - $totPartnerCredit - $invoiceList[0]['bkg_refund_amount']) ?></td>
                                </tr>
                                <? if ($invoiceList[0]['bkg_credits_used'] > 0) { ?>
                                    <tr>
                                        <td class="text-right">Gozo coins used</td> 
                                        <td class="text-right"><i class="fa fa-inr"></i> <?= number_format($invoiceList[0]['bkg_credits_used']) ?></td>
                                    </tr>
                                <? } ?>
                                <tr>
                                    <td class="text-right">Cash collected by driver</td> 
                                    <td class="text-right">
                                        <i class="fa fa-inr"></i> 
                                        <?php if ($invoiceList[0]['bkg_vendor_collected'] > 0) { ?>
                                            <?= number_format($invoiceList[0]['bkg_vendor_collected']) ?>
                                            <?php
                                        } else {
                                            echo '0';
                                        }
                                        ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-right"><b>Total Amount Due (in figures)</b></td>
                                    <td class="text-right"><b> 
                                            <i class="fa fa-inr"></i>
                                            <?php if ($invoiceList[0]['bkg_due_amount'] > 0) { ?>
                                                <?= number_format($invoiceList[0]['bkg_due_amount']) ?>
                                                <?php
                                            } else {
                                                echo '0';
                                            }
                                            ?>
                                        </b></td>
                                </tr>
                                <!-- <tr>
                                    <td colspan="2" class="text-right"><b>Total Amount Due (in words): 
                                <?php /* if ($invoiceList['bkg_due_amount'] > 0) { ?>
                                  <?= $duerupees ?>
                                  <?php } else { echo 'Zero Only'; } */ ?>
                                    </b></td>
                                </tr> -->
                            </table>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-xs-12"  style="font-size: 11px;">
                            <p>We declare that this Invoice shows the actual value of all transactions and that all particulars are true and correct. 
                                This bill is issued by the cab driver and not by aaocab. aaocab acts only as an intermediary for arranging the cab services. 
                                GST is collected and remitted by Gozo Technologies Pvt. Ltd. [GSTIN: <?= Filter::getGstin($invoiceList[0]['bkg_pickup_date']);?>]; in the capacity of Aggregator as per the Finance Budget, 2015 read with GST Notification No. 5/2015
                                In case of any queries/complaints, write to us on info@aaocab.com <br>
                                This is an electronically generated invoice and does not require signature. All applicable terms and conditions are available at http://www.aaocab.com/terms</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <hr>
                            <p style="font-size: 11px; text-align: center; margin-bottom: 2px;"><b>Corporate Office:</b> <?= $address ?>.</p>
                            <p style="font-size: 11px; text-align: center;">Email: info@aaocab.com &nbsp;|&nbsp; Phone: (+91) 90518-77-000 (24x7), International (+1) 650-741-GOZO (24x7) &nbsp;|&nbsp; www.aaocab.com </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <? } else {
        ?> 
        Your invoice will be generated after your trip is successfully completed with us. You can also view or download by signing in your user account.
        <?
    }
} else {
    ?> 
    The link is no longer active.<br>The invoice may be generated only after your trip is successfully completed with us. You can also view or download by signing in your user account.<br>For any help please contact our customer care.
<? } ?>

<script>
    function printFunction() {
        $('#printDiv').hide();
        window.print();
    }
</script>
