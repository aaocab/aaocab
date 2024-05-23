<?
/* @var $model Booking */
$gozoAmount = ($model->bkg_gozo_amount != '') ? $model->bkg_gozo_amount : $model->bkg_total_amount - $model->bkg_vendor_amount;
$dueAmount = ($model->bkg_due_amount != '') ? $model->bkg_due_amount : $model->bkg_total_amount - $model->getTotalPayment();
$grossAmount = $model->calculateGrossAmount();
$tabclass = ($minheight) ? "main-tab2-$minheight" : 'main-tab2';
$bcRow = BookingCab::model()->getTripGozoAmountByBkgID($model->bkg_id);
$tripGozoAmount = $bcRow['gozoAmount'];
?>

<div class="<?= $tabclass ?>">
    <div class="col-xs-12 col-sm-6 p0">
        <div class="<?= $tabclass ?>">
            <div class="row p5 new-tab2">
                <div class="col-xs-6"><b>Base Fare:</b></div>
                <div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= $model->bkg_base_amount ?></div>
            </div>
            <?
            if ($model->bkg_additional_charge > 0)
            {
                ?>
                <div class="row p5 new-tab2">
                    <div class="col-xs-6"><b>Additional Charge:</b></div>
                    <div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= $model->bkg_additional_charge ?></div>
                </div>
                <?
            } if ($model->bkg_driver_allowance_amount > 0)
            {
                ?>
                <div class="row p5 new-tab2">
                    <div class="col-xs-6"><b>Driver Allowance:</b></div>
                    <div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= ($model->bkg_driver_allowance_amount != '') ? $model->bkg_driver_allowance_amount : '0'; ?></div>
                </div>
            <?
            } if ($model->bkg_convenience_charge != 0)
            {
                ?>
                <div class="row p5 new-tab2">
                    <div class="col-xs-6"><b>COD Charge: </b></div>
                    <div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= $model->bkg_convenience_charge ?></div>
                </div>
            <?php
            } 
            if ($model->bkg_extra_km_charge > 0)
            {
                ?>
                <div class="row p5 new-tab2">
                    <div class="col-xs-6"><b>Extra charges ( for extra <?=$model->bkg_extra_km;?> kms driven ) : </b></div>
                    <div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= $model->bkg_extra_km_charge ?></div>
                </div>
            <?php
            }if ($model->bkg_extra_state_tax > 0)
            {
                ?>
                <div class="row p5 new-tab2">
                    <div class="col-xs-6"><b>Other charges (State) :</b></div>
                    <div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= round($model->bkg_extra_state_tax) ?></div>
                </div>
            <?php
            }
            if ($model->bkg_extra_toll_tax > 0)
            {
                ?>
                <div class="row p5 new-tab2">
                    <div class="col-xs-6"><b>Other charges (Toll) :</b></div>
                    <div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= round($model->bkg_extra_toll_tax) ?></div>
                </div>
            <?php
            }
            if ($model->bkg_parking_charge > 0)
            {
                ?>
                <div class="row p5 new-tab2">
                    <div class="col-xs-6"><b>Other charges (Parking) :</b></div>
                    <div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= round($model->bkg_parking_charge) ?></div>
                </div>
            <?php
            }
            if ($model->bkg_discount_amount != 0)
            {
                ?>
                <div class="row p5 new-tab2">
                    <div class="col-xs-6"><b>Discount:</b></div>
                    <div class="col-xs-6 text-right">(-)<i class="fa fa-inr"></i><?= $model->bkg_discount_amount ?> </div>
                </div>
            <?php } ?>
            <div class="row p5 new-tab4">
                <div class="col-xs-6"><b>Amount (Excl Tax):</b></div>
                <div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= $grossAmount ?></div>
            </div>
            <?
            //$staxrate = $model->getServiceTaxRate();
			$serviceTaxRate				 = BookingInvoice::getGstTaxRate($model->bkg_agent_id, $model->bkg_booking_type);
			$staxrate    = ($serviceTaxRate == 0)? 1 : $serviceTaxRate;
            $taxLabel = ($serviceTaxRate == 5) ? 'GST' : 'Service Tax ';
            ?>
            <?
            if ($model->bkg_cgst > 0)
            {
                ?>
                <div class="row p5 new-tab2">
                    <div class="col-xs-6"><b>CGST (@<?= Yii::app()->params['cgst'] ?>%):</b></div>
                    <div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= ((Yii::app()->params['cgst'] / $staxrate) * $model->bkg_service_tax)|0; ?></div>
                </div>
            <? } ?>
            <?
            if ($model->bkg_sgst > 0)
            {
                ?>
                <div class="row p5 new-tab2">
                    <div class="col-xs-6"><b>SGST (@<?= Yii::app()->params['sgst'] ?>%):</b></div>
                    <div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= ((Yii::app()->params['sgst'] / $staxrate) * $model->bkg_service_tax)|0; ?></div>
                </div>
            <? } ?>
            <?
            if ($model->bkg_igst > 0)
            {
                ?>
                <div class="row p5 new-tab2">
                    <div class="col-xs-6"><b>IGST (@<?= Yii::app()->params['igst'] ?>%):</b></div>
                    <div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= ((Yii::app()->params['igst'] / $staxrate) * $model->bkg_service_tax)|0; ?></div>
                </div>
            <? } ?>
            <?
            if ($serviceTaxRate != 5)
            {
                ?>
                <div class="row p5 new-tab2">
                    <div class="col-xs-6"><b><?= $taxLabel ?>:</b></div>
                    <div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= $model->bkg_service_tax; ?></div>
                </div>
            <? } ?>
            <div class="row p5 new-tab2">
                <div class="col-xs-6"><b>Toll Tax <?= ($model->bkg_is_toll_tax_included == 1) ? "(Included)" : "(Excluded)" ?>:</b></div>
                <div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= ($model->bkg_toll_tax != '') ? $model->bkg_toll_tax : 0; ?></div>
            </div>
            <div class="row p5 new-tab2">
                <div class="col-xs-6"><b>State Tax <?= ($model->bkg_is_state_tax_included == 1) ? "(Included)" : "(Excluded)" ?>: </b></div>
                <div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= ($model->bkg_state_tax != '') ? $model->bkg_state_tax : 0; ?></div>
            </div>

            <div class="row p5 new-tab3">
                <div class="col-xs-6"><b>TOTAL AMOUNT</b></div>
                <div class="col-xs-6  text-right amount_size"><span><b><i class="fa fa-inr"></i><?= $model->bkg_total_amount ?></b></span></div>
            </div>
            <?php
           
                if ($tripGozoAmount<0)
                {
                    ?>
                    <div class="row p5 new-tab3">
                        <div class="col-xs-12 text-right"><b><font style="color:red">Trip Not Profitable</font></b></div>
                    </div>
                    <?php
                }
           
            ?>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6 p0">
        <div class="<?= $tabclass ?>">
            <div class="row p5 new-tab2 hidden-xs">
                <div class="col-xs-6"><b>Total Amount:</b></div>
                <div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= $model->bkg_total_amount ?></div>
            </div>
            <div class="row p5 new-tab2">
                <div class="col-sm-8 col-xs-6"><b>Charges (per km) after <?= $model->bkg_trip_distance ?> km:</b></div>
                <div class="col-xs-6 col-sm-4 text-right"><i class="fa fa-inr"></i><?= $model->bkg_rate_per_km_extra ?></div>
            </div>
            <?
            if ($model->bkg_advance_amount > 0)
            {
                ?>
                <div class="row p5 new-tab2">
                    <div class="col-xs-6"><b>Customer Advance:</b></div>
                    <div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= ($model->bkg_advance_amount != '') ? round($model->bkg_advance_amount) : 0 ?></div>
                </div>
                <?
            } if ($model->bkg_refund_amount > 0)
            {
                ?>
                <div class="row p5 new-tab2">
                    <div class="col-xs-6"><b>Customer Refund:</b></div>
                    <div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= ($model->bkg_refund_amount != '') ? round($model->bkg_refund_amount) : 0; ?></div>
                </div>
            <? } ?>
            <div class="row p5 new-tab2">
                <div class="col-xs-6"><b>Customer Due:</b></div>
                <div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= round($dueAmount) ?></div>
            </div>
            <?
            if ($model->bkg_credits_used != '' && $model->bkg_credits_used > 0)
            {
                ?>
                <div class="row p5 new-tab2">
                    <div class="col-xs-6"><b>Gozo Coins Used: </b></div>
                    <div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= $model->bkg_credits_used ?></div>
                </div>
                <?
            }
            if ($model->bkg_corporate_credit != '' && $model->bkg_corporate_credit > 0)
            {
                ?>
                <div class="row p5 new-tab2">
                    <div class="col-xs-6"><b>Corporate Credits:</b></div>
                    <div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= ($model->bkg_corporate_credit != '') ? $model->bkg_corporate_credit : 0 ?></div>
                </div>
            <? } ?>

<!--            <div class="row p5 new-tab2">
                <div class="col-xs-6"><b>Vendor Amount:</b></div>
                <div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= $model->bkg_vendor_amount ?></div>
            </div>-->
            <?php
            if (($model->bkg_quoted_vendor_amount != $model->bkg_vendor_amount) && $model->bkg_quoted_vendor_amount > 0)
            {
                ?>
                <div class="row p5 new-tab2">
                    <div class="col-xs-8"><b>Quoted Vendor Amount:</b></div>
                    <div class="col-xs-4 text-right"><i class="fa fa-inr"></i><?= $model->bkg_quoted_vendor_amount ?></div>
                </div>
                <?
            }
            ?>
<!--            <div class="row p5 new-tab2">
                <div class="col-xs-6"><b>Trip Vendor Amount:</b></div>
                <div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= $cabmodel->bcb_vendor_amount ?></div>
            </div>-->
            <?
            if ($model->bkg_vendor_collected > 0)
            {
                ?>
                <div class="row p5 new-tab2">
                    <div class="col-xs-6"><b>Vendor Collected:</b></div>
                    <div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= ($model->bkg_vendor_collected != '') ? $model->bkg_vendor_collected : 0; ?></div>
                </div>
            <? } ?>
<!--            <div class="row p5 new-tab2">
                <div class="col-xs-6"><b>Vendor Due: </b></div>
                <div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= ($model->bkg_vendor_amount - $model->bkg_vendor_collected) ?></div>
            </div>
            <div class="row p5 new-tab2">
                <div class="col-xs-6"><b>Gozo Amount:</b></div>
                <div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= $gozoAmount ?></div>
            </div>

            <div class="row p5 new-tab2">
                <div class="col-xs-6"><b>Gozo Due:</b></div>
                <div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= ($gozoAmount - $model->getAdvanceReceived()) ?></div>
            </div>-->
            <?
            if ($model->bkg_agent_markup > 0)
            {
                ?>
<!--                <div class="row p5 new-tab2">
                    <div class="col-xs-6"><b>Partner Commission:</b></div>
                    <div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= $model->bkg_agent_markup; ?></div>
                </div>-->
            <? } ?>
            <?php
            if ($tripGozoAmount < 0)
            {
                ?>
<!--                <div class="row p5 new-tab2">
                    <div class="col-xs-8"><b><font style="color:red">NOT PROFITABLE!! Loss = </font></b></div>
                    <div class="col-xs-4 text-right"><b><font style="color:red"><i class="fa fa-inr"></i><?= ($tripGozoAmount) * -1 ?></font></b></div>
                </div>-->
                <?php
            }
            else
            {
                ?>
<!--                <div class="row p5 new-tab2">
                    <div class="col-xs-8"><b><font style="color:green">Profit(Trip) Amount = </font></b></div>
                    <div class="col-xs-4 text-right"><b><font style="color:green"><i class="fa fa-inr"></i><?= ($tripGozoAmount) ?></font></b></div>
                </div>-->
                <?php
            }
            ?>
        </div>
    </div>

</div>