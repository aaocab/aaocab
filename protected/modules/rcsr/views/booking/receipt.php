<link rel="stylesheet" href="/css/font-awesome/css/font-awesome.css">
<link rel="stylesheet" href="/css/site.min.css">
<link rel="stylesheet" type="text/css" href="/css/component.css"/>
<link href="/css/hover.css" rel="stylesheet" media="all">
<link rel="stylesheet" href="/css/site.css?v=<?= Yii::app()->params['sitecssVersion']; ?>">
<?php
$cartype   = VehicleTypes::model()->getCarType();
$type      = $invoiceList['type'];
$filter    = new Filter();
$route     = BookingRoute::model()->getRouteName($invoiceList['bkg_id']);
$rupees    = 'Rupees' . ucwords($filter->convertNumberToWord($invoiceList['bkg_total_amount'])) . 'only.';
$duerupees = 'Rupees' . ucwords($filter->convertNumberToWord($invoiceList['bkg_due_amount'])) . 'only.';

if (!$isPDF) {
    ?>
    <title><?= $invoiceList['bkg_booking_id']; ?>-<?= date('Ymd', strtotime($invoiceList['bkg_pickup_date'])); ?></title>
    <?
}
?>
<style type="text/css">
    .table-responsive td{ font-size: 11px!important; padding: 5px;}
    .table-responsive .table td{ font-size: 11px!important; padding: 8px 10px;}
    .invoice_box{font-size: 13px!important;  border: 1px solid #e4e4e4;}
    .invoice_white { background-color: #fff; }


</style>
<?
$isCorporate = false;
$topMargin   = 0;
if ($invoiceList['bkg_agent_id'] > 0) {
    $isCorporate      = true;
    //$totPartnerCredit = AccountTransDetails::model()->getTotalPartnerCredit($invoiceList['bkg_id']);
    // $topMargin   = 100;
}
?>
<div class="container">
    <div class="row mt20">
        <div class="col-xs-12   invoice_box" style="padding-top: <?= $topMargin ?>px">
            <div class="row table-responsive">

                <table style="width:100%; border-bottom:1px solid #e4e4e4;" class="p10 table-responsive ">
                    <tr style=" background-color: #fff;">
                        <td class="mt20" style="width: 33%;text-align: center;border: 0">
                            <div>
                                <img src="https://www.gozocabs.com/images/logo2.png" alt="">
                            </div>
                        </td>
                        <td class="text-uppercase mt20 text-center"  style="width: 34%;border: 0">
                            <span style="font-size: 20px;">I N V O I C E</span>
                        </td>
                        <td class="text-left p10" style="width: 33%;padding-left: 3px;border: 0">
                            <strong>Mailing Address:</strong><br>
                            H-215, Block H, Upper Ground Floor,<br>
                            Sushant Shopping Arcade,<br>
                            Sushant Lok phase -1,<br>
                            Gurugram , Haryana Pin - 122001
                        </td>
                    </tr>
                </table>
            </div>


            <table style="width:100%" class="table-responsive">
                <tr style=" background-color: #fff;">
                    <td style="border: 0">
                        <b>Traveler Information:</b><br/>
                        <b>Name: </b><b><?= ucfirst($invoiceList['bkg_user_name']) . ' ' . ucfirst($invoiceList['bkg_user_lname']); ?></b>
                        <?php
                        if ($invoiceList['bkg_contact_no'] != '') {
                            ?>
                            <br/>
                            <b>Phone: </b><?= '+' . $invoiceList['bkg_alt_country_code'] . $invoiceList['bkg_contact_no']; ?>
                            <?
                        }
                        if ($invoiceList['bkg_user_email'] != '') {
                            ?>
                            <br/>
                            <b>Email: </b><?= $invoiceList['bkg_user_email']; ?>
                        <? } ?>
                    </td>
                    <td class="text-right" style="vertical-align: top;border: 0">
                        <b>Invoice generated on: </b> <?= date('M d, Y'); ?>
                    </td>
                </tr>
            </table>

 <?if ( $isCorporate) {?>

            <table style="width:100% " class="table-responsive">
                <tr style=" background-color: #fff;"> 
                    <td style="border: 0">
                       
                        <b>Billing Information:</b>  <br/>
                      <?/*/?>
  <?
                        if (!$isCorporate) {
                            $agentId                    = Yii::app()->params['gozoChannelPartnerId'];
                            $model                      = Agents::model()->findByPk($agentId);
                            $invoiceList['agt_cmpny']   = $model->agt_company;
                            $invoiceList['agt_address'] = $model->agt_address;
                            $invoiceList['agt_gstin']   = $model->agt_gstin;
                            ?>
                            <b>Company Name: </b><?= ucfirst($invoiceList['agt_cmpny']); ?><br/>
                            <?
                            if ($invoiceList['agt_gstin'] != "") {

                                echo "<b>GSTIN: </b> " . $invoiceList['agt_gstin'];
                            }
                        }
                        else <?/*/  
                            ?>

                            <b>Company : </b><?= ucfirst($invoiceList['agt_cmpny']); ?>
<!--                            ( <?//= ucfirst($invoiceList['agt_fn']) . ' ' . ucfirst($invoiceList['agt_ln']); ?> )-->
                            <br/>
<!--                            <b>Billing Address: </b><?//= $invoiceList['agt_address']; ?><br/>-->
                            <!-- <b>Booking requested by and his Contact details: </b><? //= '+' . $invoiceList['agt_ph_pref'] . $invoiceList['agt_contct'];                                           ?><br/>
                            <b>Email: </b><? //= $invoiceList['agt_email'];                                          ?><br/> -->
<!--                            <b>GSTIN: </b>
                            <?//php echo $invoiceList['agt_gstin']; ?><br/>-->
                            <b>Your booking reference: </b>
                            <?
                            if ($invoiceList['bkg_agent_ref_code'] != '') {
                                ?>
                                <?= $invoiceList['bkg_agent_ref_code']; ?>
                                <?php
                            }
                            else {
                                echo "< BLANK >";
                            }
                       
                        ?>
                    </td>
                </tr>
            </table>
 <? }?>

            <div class="row  mt10">
                <div class="col-xs-12 table-responsive">
                    <table class="table table-bordered  " >
                        <tr>
                            <td colspan="2"><b>Journey Details</b></td>
                        </tr>
                        <tr>
                            <td style="border-width: 0">Route</td>
                            <td style="border-width: 0" class="text-left"><?= $route; ?></td>
                        </tr>
                        <tr>
                            <td style="border-width: 0">Order Number</td>
                            <td style="border-width: 0"><?= $invoiceList['bkg_booking_id']; ?></td>
                        </tr>
                        <?php
                        if ($invoiceList['bkg_booking_type'] == 2) {
                            ?>
                            <tr>
                                <td style="border-width: 0">Date and Time of Onward Trip</td>
                                <td style="border-width: 0"><?= date('jS M Y (D) g:i a', strtotime($invoiceList['bkg_pickup_date'])); ?></td>
                            </tr>
                            <tr>
                                <td style="border-width: 0">Date and Time of Return Trip</td>
                                <td style="border-width: 0"><?= date('jS M Y (D) g:i a', strtotime($invoiceList['bkg_return_date'])); ?></td>
                            </tr>
                            <?php
                        }
                        else {
                            ?>
                            <tr>
                                <td style="border-width: 0">Date and Time of Journey</td>
                                <td style="border-width: 0"><?= date('jS M Y (D) g:i a', strtotime($invoiceList['bkg_pickup_date'])); ?></td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td style="border-width: 0">Type of Car</td>
                            <td style="border-width: 0"><?= $cartype[$type] //. ' (' . $invoiceList['make'] . '-' . $invoiceList['model'] . ')';                ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="row  ">
                <div class="col-xs-12 table-responsive">
                    <table class="table table-bordered " width="100%">
                        <tr>
                            <td class="text-right"><b>Item</b></td>
                            <td class="text-right"><b>Amount (Rs.)</b></td>
                        </tr>
                        <tr>
                            <td class="text-right" style="border-width: 0">Base Fare</td>
                            <?php
                            $baseAmount = $invoiceList->bkg_base_amount;
                            $sTax       = $invoiceList->bkg_service_tax;
                            $sTax2      = round(($sTax * 0.2) / 6);
                            $sTax1      = $sTax - (2 * $sTax2);
                            ?>
                            <td class="text-right" style="border-width: 0"><i class="fa fa-inr"></i> <?= number_format(round($baseAmount)) ?></td>
                        </tr>
                        <tr>
                            <td class="text-right" style="border-width: 0">Driver Allowance</td> 
                            <td class="text-right" style="border-width: 0"><i class="fa fa-inr"></i> <?= number_format($invoiceList->bkg_driver_allowance_amount) ?></td>
                        </tr>

                        <?php
                        if ($invoiceList->bkg_toll_tax > 0) {
                            ?>
                            <tr>
                                <td class="text-right" style="border-width: 0">Toll Tax</td>
                                <td class="text-right" style="border-width: 0"><i class="fa fa-inr"></i> <?= number_format($invoiceList->bkg_toll_tax) ?></td>
                            </tr>
                            <?php
                        }
                        ?>
                        <?php
                        if ($invoiceList->bkg_state_tax > 0) {
                            ?>
                            <tr>
                                <td class="text-right" style="border-width: 0">State Tax</td>
                                <td class="text-right" style="border-width: 0"><i class="fa fa-inr"></i> <?= number_format($invoiceList->bkg_state_tax) ?></td>
                            </tr>
                            <?php
                        }
                        ?>

                        <tr>
                            <td class="text-right" style="border-width: 0">Convenience Charge</td> 
                            <td class="text-right" style="border-width: 0"><i class="fa fa-inr"></i> <?= number_format($invoiceList->bkg_convenience_charge) ?></td>
                        </tr>
                        <?php
                        if ($invoiceList->bkg_extra_km > 0) {
                            ?>
                            <tr>
                                <td class="text-right" style="border-width: 0">Extra kms driven</td> 
                                <td class="text-right" style="border-width: 0"> <?= number_format($invoiceList->bkg_extra_km) ?> kms</td>
                            </tr>
                        <?php } ?>
                        <?php
                        if ($invoiceList->bkg_extra_km > 0) {
                            ?>
                            <tr>
                                <td class="text-right" style="border-width: 0">Additional charges (for extra kms driven) @ Rate Rs. <?= $invoiceList->bkg_rate_per_km_extra ?></td>
                                <td class="text-right" style="border-width: 0"><i class="fa fa-inr"></i> <?= number_format($invoiceList->bkg_extra_km_charge) ?></td>
                            </tr>
                        <?php } ?>
                        <?php
                        if ($invoiceList->bkg_parking_charge > 0) {
                            ?>
                            <tr>
                                <td class="text-right" style="border-width: 0">Other charges (Parking)</td> 
                                <td class="text-right" style="border-width: 0"><i class="fa fa-inr"></i> <?= number_format($invoiceList->bkg_parking_charge) ?></td>
                            </tr>
                        <?php } ?>
                        <?php
                        if ($invoiceList->bkg_extra_toll_tax > 0) {
                            ?>
                            <tr>
                                <td class="text-right" style="border-width: 0">Other charges (Toll)</td> 
                                <td class="text-right"><i class="fa fa-inr"></i> <?= number_format($invoiceList->bkg_extra_toll_tax) ?></td>
                            </tr>
                        <?php } ?>
                        <?php
                        if ($invoiceList->bkg_extra_state_tax > 0) {
                            ?>
                            <tr>
                                <td class="text-right" style="border-width: 0">Other charges (State tax)</td> 
                                <td class="text-right" style="border-width: 0"><i class="fa fa-inr"></i> <?= number_format($invoiceList->bkg_extra_state_tax) ?></td>
                            </tr>
                        <?php } ?>
<!-- <tr>
<td class="text-right">Other charges (IGST)</td> 
<td class="text-right"><i class="fa fa-inr"></i> <? //= number_format($invoiceList->bkg_convenience_charge)                                          ?></td>
</tr> -->

                        <?php
                        if ($invoiceList['bkg_additional_charge'] > 0) {
                            ?>
                            <tr>
                                <td class="text-right" style="border-width: 0">Additional Charge @ <?= ucwords($invoiceList['bkg_additional_charge_remark']) ?></td>
                                <td class="text-right" style="border-width: 0"><i class="fa fa-inr"></i> <?= number_format($invoiceList['bkg_additional_charge']) ?></td>
                            </tr>
                        <?php } ?>
                        <?php
                        if ($invoiceList['bkg_discount_amount'] > 0) {
                            ?>
                            <tr>
                                <td class="text-right" style="border-width: 0">Discount</td>
                                <td class="text-right" style="border-width: 0"><i class="fa fa-inr"></i> <?= number_format($invoiceList['bkg_discount_amount']) ?></td>
                            </tr>
                        <?php } ?>
                        <? if (($invoiceList['bkg_service_tax_rate'] == 5) || (date('Y-m-d', strtotime($invoiceList['bkg_create_date'])) >= date('Y-m-d', strtotime('2017-07-01')))) { ?>
                            <? if ($invoiceList['bkg_sgst'] > 0) { ?>
                                <tr>
                                    <td class="text-right" style="border-width: 0">SGST @ <?= Yii::app()->params['sgst']; ?>%</td>
                                    <td class="text-right" style="border-width: 0"><i class="fa fa-inr"></i> <?= ((Yii::app()->params['sgst'] / $invoiceList['bkg_service_tax_rate']) * $sTax) ?></td>
                                </tr>
                            <? } ?>
                            <? if ($invoiceList['bkg_cgst'] > 0) { ?>
                                <tr>
                                    <td class="text-right" style="border-width: 0">CGST @ <?= Yii::app()->params['cgst']; ?>%</td>
                                    <td class="text-right" style="border-width: 0"><i class="fa fa-inr"></i> <?= ((Yii::app()->params['cgst'] / $invoiceList['bkg_service_tax_rate']) * $sTax) ?></td>
                                </tr>
                            <? } ?>
                            <? if ($invoiceList['bkg_igst'] > 0) { ?>
                                <tr>
                                    <td class="text-right" style="border-width: 0">IGST @ <?= Yii::app()->params['igst']; ?>%</td>
                                    <td class="text-right" style="border-width: 0"><i class="fa fa-inr"></i> <?= ((Yii::app()->params['igst'] / $invoiceList['bkg_service_tax_rate']) * $sTax) ?></td>
                                </tr>
                            <? } ?>
                            <?
                        }
                        else {
                            ?>
                            <tr>
                                <td class="text-right" style="border-width: 0">GST @ 5.6%</td>
                                <td class="text-right" style="border-width: 0"><i class="fa fa-inr"></i> <?= number_format($sTax1) ?></td>
                            </tr>
                            <tr>
                                <td class="text-right" style="border-width: 0">Swachh Bharat Cess @ 0.2%</td>
                                <td class="text-right" style="border-width: 0"><i class="fa fa-inr"></i> <?= number_format($sTax2) ?></td>
                            </tr>
                            <tr>
                                <td class="text-right" style="border-width: 0">Krishi Kalyan Cess @ 0.2%</td>
                                <td class="text-right" style="border-width: 0"><i class="fa fa-inr"></i> <?= number_format($sTax2) ?></td>
                            </tr>
                        <? } ?> 
                        <tr>
                            <td class="text-right" style="border-width: 0"><b>Total Amount (in figures)</b></td>
                            <td class="text-right" style="border-width: 0"><b><i class="fa fa-inr"></i> 
                                    <?= number_format($invoiceList['bkg_total_amount']) ?>
                                    <? //= number_format($newtotamount)     ?>
                                </b></td>
                        </tr>




                        <tr>
                            <td colspan="2" class="text-right"><b>Total Amount (in words): <?= $rupees ?></b></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="row  ">    
                <div class="col-xs-12 table-responsive">
                    <table class="table table-bordered " width="100%">
                        <tr>
                            <td class="text-right"><b>Item</b></td>
                            <td class="text-right"><b>Amount (Rs.)</b></td>
                        </tr>

                        <?php
                       /* if ($isCorporate) {
                            $totPartnerCredit = ($totPartnerCredit == '') ? 0 : $totPartnerCredit;
                            ?>
                            <tr>
                                <td class="text-right" style="border-width: 0">Partner credit</td> 
                                <td class="text-right" style="border-width: 0"><i class="fa fa-inr"></i> <?= number_format($totPartnerCredit) ?></td>
                            </tr>
                        <?php } */?>
                        <tr>
                            <td class="text-right" style="border-width: 0">Advance payment received </td> 
                            <td class="text-right" style="border-width: 0"><i class="fa fa-inr"></i> <?= number_format($invoiceList['bkg_advance_amount'] - $totPartnerCredit - $invoiceList['bkg_refund_amount']) ?></td>
                        </tr>
                        <? if ($invoiceList->bkg_credits_used > 0) { ?>
                            <tr>
                                <td class="text-right" style="border-width: 0">Gozo coins used</td> 
                                <td class="text-right" style="border-width: 0"><i class="fa fa-inr"></i> <?= number_format($invoiceList->bkg_credits_used) ?></td>
                            </tr>
                        <? } ?>
                        <tr>
                            <td class="text-right" style="border-width: 0">Payment collected by driver</td> 
                            <td class="text-right" style="border-width: 0">
                                <i class="fa fa-inr"></i> 
                                <?php if ($invoiceList['bkg_vendor_collected'] > 0) { ?>
                                    <?= number_format($invoiceList['bkg_vendor_collected']) ?>
                                    <?php
                                }
                                else {
                                    echo '0';
                                }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right" style="border-width: 0"><b>Total Amount Due (in figures)</b></td>
                            <td class="text-right" style="border-width: 0"><b> 
                                    <i class="fa fa-inr"></i>
                                    <?php if ($invoiceList['bkg_due_amount'] > 0) { ?>
                                        <?= number_format($invoiceList['bkg_due_amount']) ?>
                                        <?php
                                    }
                                    else {
                                        echo '0';
                                    }
                                    ?>
                                </b>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="text-right"><b>Total Amount Due (in words): 
                                    <?php if ($invoiceList['bkg_due_amount'] > 0) { ?>
                                        <?= $duerupees ?>
                                        <?php
                                    }
                                    else {
                                        echo 'Zero Only';
                                    }
                                    ?>
                                </b>
                            </td>
                        </tr> 
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12"  style="font-size: 9px;line-height: 1.8em">
                    We declare that this Invoice shows the actual value of all transactions and that all particulars are true and correct. 
                    This bill is issued by the cab driver and not by Gozocabs. Gozocabs acts only as an intermediary for arranging the cab services. 
                    GST is collected and remitted by Gozo Technologies Pvt. Ltd. [GSTIN: <?= Filter::getGstin($invoiceList['bkg_pickup_date']);?>]; in the capacity of Aggregator as per the Finance Budget, 2015 read with GST Notification No. 5/2015
                    In case of any queries/complaints, write to us on info@gozocabs.com <br>
                    This is an electronically generated invoice and does not require signature. All applicable terms and conditions are available at https://www.gozocabs.com/terms 
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <hr>
                    <p style="font-size: 11px; text-align: center; margin-bottom: 2px;line-height: 1.8em"><b>Corporate Office:</b> H-215, Block H, Upper Ground Floor, Sushant Shopping Arcade, Sushant Lok Phase -1, Gurgaon , Haryana, PIN - 122001.</p>
                    <p style="font-size: 11px; text-align: center;">Email: info@gozocabs.com &nbsp;|&nbsp; Phone: (+91) 90518-77-000 (24x7), International (+1) 650-741-GOZO (24x7) &nbsp;|&nbsp; www.gozocabs.com </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function printFunction() {
        $('#printDiv').hide();
        window.print();
    }
</script>
