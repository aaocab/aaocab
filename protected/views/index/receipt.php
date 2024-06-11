<link rel="stylesheet" href="/css/font-awesome/css/font-awesome.css">
<link rel="stylesheet" href="/css/site.min.css">
<link rel="stylesheet" type="text/css" href="/css/component.css"/>
<link href="/css/hover.css" rel="stylesheet" media="all">
<link rel="stylesheet" href="/css/site.css?v=<?= Yii::app()->params['sitecssVersion']; ?>">
<?php
$cartype = VehicleTypes::model()->getCarType();
$type = $invoiceList['type'];
$filter = new Filter();
$rupees = 'Rupees' . ucwords($filter->convertNumberToWord($invoiceList['bkg_amount'])) . 'only.';
if (!$isPDF) {
    ?>
    <title><?= Filter::formatBookingId($invoiceList['bkg_booking_id']); ?>-<?= $invoiceList['bkg_user_name'] . ' ' . $invoiceList['bkg_user_lname']; ?>-<?= date('Ymd', strtotime($invoiceList['bkg_pickup_date'])); ?></title>
    <?
}
?>
<style>
    .table-responsive td{ font-size: 12px!important; padding: 5px;}
    .table-responsive .table td{ font-size: 12px!important; padding: 8px 10px;}
    .invoice_box{ border: 1px solid #e4e4e4;}
</style>
<div class="container">
    <div class="row">
        <div class="col-xs-12">
            &nbsp;
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-xs-12 pt10 pb20 invoice_box">
            <div class="row">
                <table style="width:100%;"><tr>
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
                            <span style="font-size: 20px;">Trip Bill</span>
                        </td>
                        <td class="text-right" style="width: 33%">
                            <div>
                                <figure><img src="http://www.aaocab.com/images/logo2.png" alt="Gozocabs:India's leader in inter-city taxi | Great service. Price guarantee. Awesome reviews."></figure>
                            </div></td></tr>
                </table>

            </div>
            <hr class="mt5">
            <table style="width:100%"><tr>
                    <td>
                        To,<br/>
                        <b><?= ucfirst($invoiceList['bkg_user_name']) . ' ' . ucfirst($invoiceList['bkg_user_lname']); ?>,</b>
                        <? if ($invoiceList['bkg_contact_no'] != '') { ?>
                            <br/>
                            <b>Customer Phone: </b><?= '+' . $invoiceList['bkg_alt_country_code'] . $invoiceList['bkg_contact_no']; ?>
                        <? } ?>
                        <? if ($invoiceList['bkg_user_email'] != '') { ?>
                            <br/>
                            <b>Customer Email: </b><?= $invoiceList['bkg_user_email']; ?>
                        <? } ?>

                    </td>
                    <td class="text-right" style="vertical-align: top">
                        <b>Bill Date:</b> <?= date('M d, Y'); ?>
                    </td>
                </tr>
            </table>
            <div class="row mt20">
                <div class="col-xs-12 table-responsive">
                    <table class="table table-bordered mb10">
                        <tr>
                            <td><b>Journey Details</b></td>
                            <td width="30%" class="text-right"><b>Amount (Rs.)</b></td>
                        </tr>
                        <tr>
                            <td class="p0">
                                <table class="table table-bordered mb0">
                                    <tr>
                                        <td>Route</td>
                                        <td class="text-right"><?= $invoiceList['tocity'] . ' to ' . $invoiceList['fromcity']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Order Number</td>
                                        <td class="text-right"><?= Filter::formatBookingId($invoiceList['bkg_booking_id']); ?></td>
                                    </tr>
                                    <?php
                                    if ($invoiceList['bkg_booking_type'] == 2) {
                                        ?>
                                        <tr>
                                            <td>Date and Time of Onward Trip</td>
                                            <td class="text-right"><?= date('jS M Y (D) g:i a', strtotime($invoiceList['bkg_pickup_date'])); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Date and Time of Return Trip</td>
                                            <td class="text-right"><?= date('jS M Y (D) g:i a', strtotime($invoiceList['bkg_return_date'])); ?></td>
                                        </tr>
                                        <?php
                                    } else {
                                        ?>
                                        <tr>
                                            <td>Date and Time of Journey</td>
                                            <td class="text-right"><?= date('jS M Y (D) g:i a', strtotime($invoiceList['bkg_pickup_date'])); ?></td>
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        <td>Type of Car</td>
                                        <td class="text-right"><?= $cartype[$type] . ' (' . $invoiceList['make'] . '-' . $invoiceList['model'] . ')'; ?></td>
                                    </tr>
                                </table>
                            </td>

                            <?
                            $baseAmount = $invoiceList->bkg_net_charge;
                            $sTax = $invoiceList->bkg_service_tax;
                            $sTax2 = round(($sTax * 0.2) / 6);
                            $sTax1 = $sTax - (2 * $sTax2);
                            ?>
                            <td class="text-right"><b><i class="fa fa-inr"></i> <?= number_format(round($baseAmount)) ?></b></td>
                        </tr>
                        <? if (($invoiceList['bkg_service_tax_rate'] == 5) || (date('Y-m-d', strtotime($invoiceList['bkg_create_date'])) >= date('Y-m-d', strtotime('2017-07-01')))) {
                            ?>
                            <? if($invoiceList['bkg_sgst'] > 0){ ?>
                            <tr>
                                <td class="text-right">SGST @ <?= Yii::app()->params['sgst']; ?>%</td>
                                <td class="text-right"><i class="fa fa-inr"></i> <?= ((Yii::app()->params['sgst']/$invoiceList['bkg_service_tax_rate']) * $sTax) ?></td>
                            </tr>
                            <? } ?>
                            <? if($invoiceList['bkg_cgst'] > 0){ ?>
                            <tr>
                                <td class="text-right">CGST @ <?= Yii::app()->params['cgst']; ?>%</td>
                                <td class="text-right"><i class="fa fa-inr"></i> <?= ((Yii::app()->params['cgst']/$invoiceList['bkg_service_tax_rate']) * $sTax) ?></td>
                            </tr>
                            <? } ?>
                            <? if($invoiceList['bkg_igst'] > 0){ ?>
                            <tr>
                                <td class="text-right">IGST @ <?= Yii::app()->params['igst']; ?>%</td>
                                <td class="text-right"><i class="fa fa-inr"></i> <?= ((Yii::app()->params['igst']/$invoiceList['bkg_service_tax_rate']) * $sTax) ?></td>
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
                        <?php if ($invoiceList['bkg_additional_charge'] > 0) { ?>
                            <tr>
                                <td class="text-right">Additional Charge @ <?= ucwords($invoiceList['bkg_additional_charge_remark']) ?></td>
                                <td class="text-right"><i class="fa fa-inr"></i> <?= number_format($invoiceList['bkg_additional_charge']) ?></td>
                            </tr>
                        <?php } ?>
                        <?php if ($invoiceList['bkg_discount'] > 0) { ?>
                            <tr>
                                <td class="text-right">Discount</td>
                                <td class="text-right"><i class="fa fa-inr"></i> <?= number_format($invoiceList['bkg_discount']) ?></td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td class="text-right"><b>Total Amount (in figures)</b></td>
                            <td class="text-right"><b><i class="fa fa-inr"></i> <?= number_format($invoiceList['bkg_amount']) ?></b></td>
                        </tr>
                        <?php
                        /*
                          if ($invoiceList['bkg_advance_amount'] > 0) { ?>
                          <tr>
                          <td class="text-right">Advance Paid</td>
                          <td class="text-right"><i class="fa fa-inr"></i> <?= number_format($invoiceList['bkg_advance_amount']) ?></td>
                          </tr>
                          <tr>
                          <td class="text-right"><b>Amount Due</b></td>
                          <td class="text-right"><b><i class="fa fa-inr"></i> <?= number_format($invoiceList['bkg_amount_due']) ?></b></td>
                          </tr>
                          <?php }
                         * 
                         */
                        ?>
                        <tr>
                            <td colspan="2"><b>Total Amount (in words): <?= $rupees ?></b></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12"  style="font-size: 11px;">
                    <p>We declare that this Invoice shows the actual value of all transactions and that all particulars are true and correct.</p>
                    <p>This bill is issued by the cab driver and not by Gozocabs. Gozocabs acts only as an intermediary for arranging the cab services.</p>
                    <p>GST is collected and remitted by Gozo Technologies Pvt. Ltd. [GSTIN: <?= Filter::getGstin($invoiceList['bkg_pickup_date']);?>]; in the capacity of Aggregator as per the Finance Budget, 2015 read with GST Notification No. 5/2015</p>
                    <p>In case of any queries/complaints, write to us on info@aaocab.com</p>
                    <p>This is an electronically generated invoice and does not require signature. All terms and conditions are as given on www.aaocab.com.</p>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <hr>
                    <p style="font-size: 11px; text-align: center; margin-bottom: 2px;"><b>Corporate Office:</b> <?= Config::getGozoAddress(); ?>.</p>
                    <p style="font-size: 11px; text-align: center;">Email: info@aaocab.com &nbsp;|&nbsp; Phone: (+91) 90518-77-000 (24x7), International (+1) 650-741-GOZO (24x7) &nbsp;|&nbsp; www.aaocab.com </p>
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
