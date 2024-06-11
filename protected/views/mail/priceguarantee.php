<?php
/* @var $model Booking */
$routeCityList = $model->getTripCitiesListbyId();
$model1 = clone $model;
$model->calculateConvenienceFee(0);
$model->calculateTotal();
$ct = implode(' -> ', $routeCityList);
$splRequest = $model->getSpecialRequests();
$advance = ($model->bkg_advance_amount > 0) ? $model->bkg_advance_amount : 0;
$due = $model1->bkg_due_amount;
$guaranteeLink = "<a href=\"http://www.aaocab.com/price-guarantee\" target=\"_blank\" >here</a>";
$condition1 = "<h3>Terms & Conditions : </h3>
<ul style=\"list-style-type: none; padding-left:0px;\">
    <li type=\"1\">Review the full terms and conditions of Gozo's price guarantee $guaranteeLink.</li>
    <li type=\"1\">We continuously scan the market for better rates. If we find a better rate than what you booked, we will automatically reduce your booking price.</li>
    <li type=\"1\"> If you find a quotation for the same booking at a lower price than Gozo, we will beat that quotation by Rs. 100/-</li>
</ul>";
$condition2 = "<h3>Conditions: </h3>
<ul style=\"list-style-type: none; padding-left:0px;\">
    <li type=\"1\">We will match quotations from any competing company who will provide you an authorized vehicle with tourist permit and proper licenses.</li>
</ul>";
?>
<table align="center" width="100%" bgcolor="#fff">
    <tr>
        <td>
            <table width="722" bgcolor="#eff9f7" align="center" style="border: #d4d4d4 1px solid; font-family: 'Arial'; font-size: 13px; padding: 5px; color: #000;" cellpadding="0" cellspacing="0">
                <tr>
                    <td align="center" valign="top">
                        <table width="98%">
                            <tr><td align="center" width="100%"><img src="http://www.aaocab.com/images/logo2.png" alt="" width="156" height="56"></td></tr>
                            <tr>
                                <td>
                                    <p>Hi <?= $model->getUsername() ?>,</p>
                                    <p style="line-height: 24px;">Your trip to <?=$model->bkgToCity->cty_name;?> is in <?= $arr['day']; ?> days. Your trip is backed by Gozo’s <a href="http://www.aaocab.com/price-guarantee" target="_blank" style="color: #17599f;">Best Price Guarantee.</a> Prices can go up as the travel date gets closer. 
                                </p>
                                </td>
                                </tr>
                                <tr>
                                    <td>
                                        <table width="100%">
                                            <tr>
                                                <td width="49%">
                                                    <div style=" border: #ff6700 2px solid; background: #fff; padding: 8px; display: block;">
                                                        <table width="100%" border="0" cellpadding="7">
                                                            <tr>
                                                                <td colspan="2"><strong>Your trip summary:</strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2"><span style="color: #7a7a7a;">Booking Id:</span> <?= Filter::formatBookingId($model->bkg_booking_id);?></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2"><span style="color: #7a7a7a;">Traveler name:</span> <?=$model->getUsername() ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td width="50%"><span style="color: #7a7a7a;">From:</span> <?=$model->bkgFromCity->cty_name;?></td>
                                                                <td><span style="color: #7a7a7a;">To:</span> <?=$model->bkgToCity->cty_name;?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><span style="color: #7a7a7a;">On:</span> <?= date('jS M Y (D) h:i A', strtotime($model->bkg_pickup_date)); ?></td>
                                                                <td><span style="color: #7a7a7a;">by:</span> <?= '('.$model->bkgSvcClassVhcCat->scc_ServiceClass->scc_label.') '.$model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label . ' ' . $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_desc . ' (' . $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_capacity . ' seater)' ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2"><span style="color: #7a7a7a;">Cost:</span> Rs. <b style="font-size: 18px; color: #dc1d1d;"> <?=number_format($model->bkg_total_amount,2);?></b></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2"><span style="color: #7a7a7a;">Tolls:</span> <?=($model->bkg_is_toll_tax_included == 1) ? "(Included)" : "(Excluded)" ?> </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2"><span style="color: #7a7a7a;">State Tax:</span> <?=($model->bkg_is_state_tax_included == 1) ? "(Included)" : "(Excluded)" ?> </td>
                                                            </tr>    
                                                            <?php
                                                            if ($advance > 0) 
                                                            {?>
                                                            <tr>
                                                                <td colspan="2"><span style="color: #7a7a7a;">Advance paid:</span> Rs. <b style="font-size: 18px; color: #509315;"><?= number_format($advance,2);?></b></td>
                                                            </tr>
                                                            <?php
                                                            }?>
                                                            <tr>
                                                                <td colspan="2"><span style="color: #7a7a7a;">Payment Due:</span> Rs. <b style="font-size: 18px; color: #509315;"><?= number_format($due,2); ?></b></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2"><span style="color: #7a7a7a;">Trip Status:</span>  <?php
                                                                    if ($model->bkg_reconfirm_flag == 0) 
                                                                    {
                                                                        echo '<font style="color:red"><b>RECONFIRM PENDING</b></font>';
                                                                    } 
                                                                    else if ($model->bkg_reconfirm_flag == 1) 
                                                                    {
                                                                        echo '<font style="color:green"><b>RECONFIRMED</b></font>';
                                                                    }
                                                                    ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2"><span style="color: #7a7a7a;">Special  Instructions:</span> <?=($splRequest!='') ? $splRequest : 'none'; ?></td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </td>
                                                <td width="2%"></td>
                                                <td width="49%"  align="center" valign="top">
                                                    <table width="100%" border="0" cellpadding="7">
                                                        <tr>
                                                            <td align="center" valign="top"><img src="http://aaocab.com/images/price-guarantee-img.jpg" alt="Price Guarantee"  /></td>
                                                        </tr>
                                                        <?php
                                                        if ($due > 0) 
                                                        {
                                                        ?>
                                                        <tr>
                                                            <td align="center" valign="middle">
                                                                <div style="background: #ff6600; color: #fff; font-size: 20px; text-transform: uppercase; text-align: center;">
                                                                    <a href="<?= $payurl ?>" target="_blank" style="color: #fff; text-decoration: none; padding: 8px; display: block; font-weight: bold;">Pay Now</a>
                                                                </div>
                                                                <p>To lock your price &amp; get a 5% discount</p>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                        }?>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" align="left"><p>We're looking forward to serving you.</p></td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" align="left">&nbsp;</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr><td>&nbsp;</td></tr>
    <tr>
        <td align="left" style="font-size: 12px; padding-left: 5px;"><?=$condition1;?></td>
    </tr>
</table>

