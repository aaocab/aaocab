<style>
    .trip_plan table { 
        width: 100%; 
        border-collapse: collapse; 
    }
    /* Zebra striping */
    .trip_plan tr:nth-of-type(odd) { 
        background: #f1f1f1; 
    }
    .trip_plan th { 
        background: #333; 
        color: white; 
        font-weight: bold; 
    }
    .trip_plan td { 
        padding: 6px; 
        border: 1px solid #ccc; 
        text-align: left; 
    }
    .trip_plan th { 
        padding: 6px; 
        border: 1px solid #ccc; 
        text-align: left; 
    }
    @media (max-width: 767px)
    {

        /* Force table to not be like tables anymore */
        .trip_plan table, thead, tbody, th, td, tr { 
            display: block; 
        }

        /* Hide table headers (but not display: none;, for accessibility) */
        .trip_plan thead tr { 
            position: absolute;
            top: -9999px;
            left: -9999px;
        }

        .trip_plan tr{ border: 1px solid #ccc; }

        .trip_plan td{ 
            /* Behave  like a "row" */
            border: none;
            border-bottom: 1px solid #d5d5d5; 
            position: relative;
            padding-left: 50%; 
        }

        .trip_plan td:before { 
            /* Now like a table header */
            position: absolute;
            /* Top/left values mimic padding */
            top: 6px;
            left: 6px;
            width: 45%; 
            padding-right: 10px; 
            white-space: nowrap;
        }

        /*
        Label the data
        */
        .trip_plan td:nth-of-type(1):before { content: "From"; }
        .trip_plan td:nth-of-type(2):before { content: "To"; }
        .trip_plan td:nth-of-type(3):before { content: "Departure Date"; }
        .trip_plan td:nth-of-type(4):before { content: "Time"; }
        .trip_plan td:nth-of-type(5):before { content: "Distance"; }
        .trip_plan td:nth-of-type(6):before { content: "Duration"; }
        .trip_plan td:nth-of-type(7):before { content: "Days"; }
    }
    .checkbox input[type="checkbox"], .checkbox-inline input[type="checkbox"] {
        margin-left: -20px!important;margin-top:5px!important;
    }
    /* Smartphones (portrait and landscape) ----------- */

</style>

<?
$hide = (($model->bkg_promo_code != '' && $model->bkg_discount_amount > 0) || $model->bkg_credits_used > 0) ? '' : 'hide';
$hidepromo = ($model->bkg_promo_code != '') ? '' : 'hide';
$hide1 = ($model->bkg_promo_code != '') ? 'hide' : '';
$enableCOD = $model->enableCOD();
?>
<div class="panel">            
    <div class="panel-body pt0 pb0 p0">   
        <div class="col-xs-12">
            <div class="row">
                <div class="col-xs-12">
                    <h3 class="mb10 text-uppercase">Booking Summary:</h3>
                    <div class="row">
                        <div class="col-xs-12 col-sm-6">
                            <p>Primary Passenger Name: <b><?= $model->getUsername() ?></b></p>
                        </div>
                        <div class="col-xs-12 col-sm-6">
<!--                            <p>Vehicle category: <b><?//= $model->bkgVehicleType->getCabType(); ?></b></p>-->
							<p>Vehicle category: <b><?= SvcClassVhcCat::model()->getVctSvcList('string', 0, 0,$model->bkg_vehicle_type_id) ?></b></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 trip_plan">
                    <h3 class="mb10 text-uppercase">Your Trip Plan</h3>
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
                    $tolltax_flag = $model1->bkg_is_toll_tax_included;
                    $statetax_flag = $model1->bkg_is_state_tax_included;
                    $tolltax_value = $model1->bkg_toll_tax;
                    $statetax_value = $model1->bkg_state_tax;
                    $taxStr = (($tolltax_flag == 1 && $tolltax_value == 0) && ($statetax_flag == 1 && $statetax_value == 0)) ? '<i style="font-size:0.8em">(Toll Tax and State Tax Included)</i>' : '';
                    ?>
                    <div class="row mt20 book-summary">
                        <div class="col-xs-12 col-sm-8 ">
                            <div class="row mb10">
                                <h4 class="m0 ">
                                    <span class="col-xs-6">Estimated distance of the trip:</span>
                                    <span class="col-xs-6 col-sm-4 blue-color text-right">
                                        <?= $model1->bkg_trip_distance ?> Km</span>
                                </h4>
                            </div>
                            <div class="row mb10">
                                <h4 class="m0 ">
                                    <span class="col-xs-6">Total days for the trip: </span>
                                    <span class="col-xs-6 col-sm-4 blue-color text-right"><?= $totDays ?> days</span></h4>
                            </div>
                            <div class="row mb10">
                                <h4 class="m0">
                                    <span class="col-xs-6">Base Fare  <?= $taxStr ?>: </span>
                                    <span class="col-xs-6 col-sm-4 blue-color text-right"><i class="fa fa-inr"></i><?= $model1->bkg_base_amount ?></span></h4>
                            </div>
                            <div class="row mb10 discounttd <?= ($model1->bkg_discount_amount > 0) ? '' : 'hide' ?>">
                                <h4 class="m0">
                                    <span class="col-xs-6">Discount Amount: </span>
                                    <span class="col-xs-6 col-sm-4 blue-color text-right"><i class="fa fa-inr"></i><span class="discountAmount"><?= $model1->bkg_discount_amount ?></span></span></h4>
                            </div>
                            <div class="row mb10 <?= ($model1->bkg_driver_allowance_amount > 0) ? '' : 'hide' ?>">
                                <h4 class="m0">
                                    <span class="col-xs-6">Driver Allowance: </span>
                                    <span class="col-xs-6 col-sm-4 blue-color text-right"><i class="fa fa-inr"></i><?= $model1->bkg_driver_allowance_amount ?></span></h4>
                            </div>
                            <?php if ($model1->bkg_toll_tax > 0) { ?>
                                <div class="row mb10">
                                    <h4 class="m0">
                                        <span class="col-xs-6">Toll Tax: </span>
                                        <span class="col-xs-6 col-sm-4 blue-color text-right"><i class="fa fa-inr"></i><?= $model1->bkg_toll_tax ?></span></h4>
                                </div>
                            <?php } ?>
                            <?php if ($model1->bkg_state_tax > 0) { ?>
                                <div class="row mb10">
                                    <h4 class="m0">
                                        <span class="col-xs-6">Other Tax: <br/><i style="font-size:0.8em">(Including State Tax / Green Tax etc)</i> </span>
                                        <span class="col-xs-6 col-sm-4 blue-color text-right"><i class="fa fa-inr"></i><?= $model1->bkg_state_tax ?></span></h4>
                                </div>
                            <?php } ?>
							<div class="row mb10">
                                <h4 class="m0">
                                    <span class="col-xs-6">GST: </span>
                                    <span class="col-xs-6 col-sm-4 blue-color text-right"><i class="fa fa-inr"></i><?= $model1->bkg_service_tax ?></span></h4>
                            </div>
                            <div class="row mb10">
                                <h4 class="m0">
                                    <span class="col-xs-6">Estimated Trip cost: </span>
                                    <span class="col-xs-6 col-sm-4 blue-color text-right"><b><i class="fa fa-inr"></i><span id="bkgamtdetails111"><?= $model1->bkg_total_amount ?></span></b></span></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row hide">
                <div class="col-xs-12 col-sm-10  mt30 ">
                    <div class="row">
                        <?
                        $applyCredits = 'none';
                        if ($creditVal > 0 && ($model->bkg_credits_used == '' || $model->bkg_credits_used == 0)) {
                            $applyCredits = 'block';
                        }
                        ?>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 " id="creditApplyDiv" style="display: <?= $applyCredits ?>">			
                            Gozo Credit points
                            <div class="input-group m-t-10">
                                <input type="text" id="creditvalamt" credits="<?= $creditVal ?>" name="creditvalamt" class="form-control" value="<?= $creditVal ?>">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-effect-ripple btn-success" onclick="PromoCreditApplyRemove('credit');">Apply</button>
                                </span>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 <?= $hide1 ?>" id="promoApplyDiv">			
                            Got Promo Code?
                            <div class="input-group m-t-10">
                                <input type="text" id="Booking_bkg_promo_code" name="Booking_bkg_promo_code" class="form-control" placeholder="Enter promo">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-effect-ripple btn-success" onclick="PromoCreditApplyRemove('promo');">Apply</button>
                                </span>
                            </div>
                            <div id="errMsgPromo" style="font-weight: bold;color: #FF0000;"></div>               
                        </div>
                        <div class="col-xs-12 mt10 <?= $hide1 ?>" id="autoPromoApplyDiv">
 
                        </div>
                        <div id="spanPromoCreditSucc" class="col-xs-12 text-center mt5" style="font-weight: bold;color: #FF6700;font-size: 17px"></div>
                        <div class="col-xs-12 m10 hide" id="creditRemove">	
                            <div class="text-center">
                                Applied Gozo Coins : <b><i class="fa fa-inr"></i><span  class="text-uppercase creditUsed"><?= $model->bkg_credits_used ?> </span></b>
                                <button class="btn btn-primary p5 pt0 pb0 mt5" onclick="PromoCreditApplyRemove('creditRemove');">Remove Gozo Coins</button>
                            </div>
                        </div>
                        <div class="col-xs-12 mt10 <?= $hidepromo ?>" id="promoAppliedDiv">	
                            <div class="text-center">
                                Applied discount code : <b><span id="txtpromo" class="text-uppercase"><?= $model->bkg_promo_code ?> </span></b>
                                <button class="btn btn-primary p5 pt0 pb0 mt5" onclick="PromoCreditApplyRemove('promoRemove')">Remove Code</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row hide">
                <div class='col-xs-12 hidden-lg hidden-md hidden-sm recalculateSummary <?= $hide ?> '>
                    <h4 class="ml0">Recalculated Amounts</h4>
                    <div class="row">
                        <div class="col-xs-8 bg bg-success p10">
                            Base Fare 
                        </div>
                        <div class="col-xs-1 bg bg-success p10">
                            &nbsp;
                        </div>
                        <div class="col-xs-3 bg bg-success p10">
                            <span><i class="fa fa-inr"></i> <span id="baseamt"><?= $model->bkg_base_amount + $model->bkg_toll_tax + $model->bkg_state_tax ?></span></span>
                        </div>
                    </div>
                    <div class="row discounttd">
                        <div class="col-xs-8 bg bg-danger p10">
                            Discount Amount
                        </div>
                        <div class="col-xs-1 bg bg-danger p10">
                            <b>-</b>
                        </div>
                        <div class="col-xs-3 bg bg-danger p10">
                            <span><i class="fa fa-inr"></i> <span class="discountAmount"><?= $model->bkg_discount_amount ?></span></span>
                        </div>
                    </div>
                    <? if ($model->bkg_additional_charge > 0) { ?>
                        <div class="row">
                            <div class="col-xs-8 bg bg-warning p10">
                                Additional Amount
                            </div>
                            <div class="col-xs-1 bg bg-warning p10">
                                <b>+</b>
                            </div>
                            <div class="col-xs-3 bg bg-warning p10">
                                <span><i class="fa fa-inr"></i> <span id="discount"><?= $model->bkg_additional_charge ?></span></span>
                            </div>
                        </div>
                    <? } if ($model->bkg_driver_allowance_amount > 0) { ?>
                        <div class="row">
                            <div class="col-xs-8 bg bg-info p10">
                                Driver Allowance
                            </div>
                            <div class="col-xs-1 bg bg-info p10">
                                <b>+</b>
                            </div>
                            <div class="col-xs-3 bg bg-info p10">
                                <span><i class="fa fa-inr"></i><span id="discount"><?= $model->bkg_driver_allowance_amount ?></span></span>
                            </div>
                        </div>
                    <? } ?>
                    <div class="row">
                        <div class="col-xs-8 bg bg-success p10">
                            GST
                        </div>
                        <div class="col-xs-1 bg bg-success p10">
                            <b>+</b>
                        </div>
                        <div class="col-xs-3 bg bg-success p10">
                            <span><i class="fa fa-inr"></i> <span class="taxAmount">
                                    <?= $model1->bkg_service_tax ?></span></span>
                        </div>
                    </div>
                    <?
                    if ($model->bkg_credits_used > 0) {
                        $displaycrd = 'block';
                    } else {
                        $displaycrd = 'none';
                    }
                    ?>
                    <div class="row ">
                        <div class="col-xs-8 bg bg-danger p10 tdcredit" style="display: <?= $displaycrd ?>">
                            Gozo Coins Used 
                        </div>
                        <div class="col-xs-1 bg bg-danger p10 tdcredit" style="display: <?= $displaycrd ?>">
                            <b>-</b>
                        </div>
                        <div class="col-xs-3 bg bg-danger p10 tdcredit" style="display: <?= $displaycrd ?>">
                            <span><i class="fa fa-inr"></i><span  class="creditUsed"><?= $model->bkg_credits_used ?></span></span>
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
                            <span><i class="fa fa-inr"></i><span class="dueAmountWithoutCOD"><?= $model1->bkg_due_amount ?></span></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class='col-sm-12 hidden-xs mt20 recalculateSummary hide <?= $hide ?>'>
                <div class="row mt10">
                    <h4 class="ml0">Recalculated Amounts</h4>
                    <div class="book-font">
                        <table class="col-sm-12 no-border table-responsive" style="table-layout: fixed">
                            <tr>
                                <td class="bg bg-success p10 text-center col-sm-2">
                                    Base Fare<br/>
                                    <span><i class="fa fa-inr"></i> 
                                        <span id="baseamt"><?= $model->bkg_base_amount + $model->bkg_toll_tax + $model->bkg_state_tax ?>
                                        </span>
                                    </span>
                                </td>
                                <td class=" text-center h3 col-sm-1 discounttd">
                                    <b>-</b>
                                </td>
                                <td  class="bg bg-danger p10 text-center col-sm-2 discounttd" >
                                    Discount Amount<br/>
                                    <span><i class="fa fa-inr"></i>
                                        <span class="discountAmount"><?= $model->bkg_discount_amount ?></span>
                                    </span>
                                </td>
                                <? if ($model->bkg_additional_charge > 0) { ?>
                                    <td class=" text-center h3 col-sm-1">
                                        <b>+</b>
                                    </td>
                                    <td class="bg bg-warning p10 text-center col-sm-2">
                                        Additional Amount<br/>
                                        <span><i class="fa fa-inr"></i>
                                            <span id="discount"><?= $model->bkg_additional_charge ?></span>
                                        </span>
                                    </td>
                                <? } if ($model->bkg_driver_allowance_amount > 0) { ?>
                                    <td class=" text-center h3 col-sm-1">
                                        <b>+</b>
                                    </td>
                                    <td class="bg bg-info p10 text-center col-sm-2">
                                        Driver Allowance<br/>
                                        <span><i class="fa fa-inr"></i>
                                            <span id="discount"><?= $model->bkg_driver_allowance_amount ?></span>
                                        </span>
                                    </td>
                                <? } ?>
                                <td class=" text-center h3 col-sm-1">
                                    <b>+</b>
                                </td>
                                <td class="bg bg-success p10 text-center col-sm-2">
                                    GST<br/>
                                    <span><i class="fa fa-inr"></i>
                                        <span class="taxAmount">
                                            <?= $model1->bkg_service_tax ?></span>
                                    </span>
                                </td>
                                <?
                                if ($model->bkg_credits_used > 0) {
                                    $displaycrd = 'block';
                                } else {
                                    $displaycrd = 'none';
                                }
                                ?>
                                <td class=" text-center h3 col-sm-1 tdcredit" style="display: <?= $displaycrd ?>">
                                    <b>-</b>
                                </td>
                                <td class="bg bg-danger p10 text-center col-sm-2 tdcredit" style="display: <?= $displaycrd ?>">
                                    Gozo Coins Used<br/>
                                    <span><i class="fa fa-inr"></i>
                                        <span  class="creditUsed"><?= $model->bkg_credits_used ?></span>
                                    </span>
                                </td>
                                <td class=" text-center h3 col-sm-1">
                                    <b>=</b>
                                </td>
                                <td class="bg bg-primary p10 text-center col-sm-2">
                                    Amount Payable<br/>
                                    <span><i class="fa fa-inr"></i> <span class="dueAmountWithoutCOD"><?= $model1->bkg_due_amount ?></span></span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">

                <div class="col-xs-12 mb10">
                    <?php
// $model=  Booking::model()->findByPk(25157);
//   $cabRate = Rate::model()->getCabDetailsbyCities($model->bkg_from_city_id, $model->bkg_to_city_id);
                    $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
                        'id' => 'confirmbook',
                        'enableClientValidation' => false,
                        'clientOptions' => array(
                            'validateOnSubmit' => true,
                            'errorCssClass' => 'has-error',
                            'afterValidate' => 'js:function(form,data,hasError){
				if(!hasError){
				
                }
            }'
                        ),
                        'enableAjaxValidation' => false,
                        'errorMessageCssClass' => 'help-block',
                        //'action' => Yii::app()->createUrl('booking/cabratedetail'),
                        'htmlOptions' => array(
                            'onsubmit' => "return false;", /* Disable normal form submit */
                            'class' => 'form-horizontal',
                        ),
                    ));
                    /* @var $form TbActiveForm */
                    ?>
                    <?= $form->errorSummary($model); ?>
                    <?= CHtml::errorSummary($model); ?>
                    <input type="hidden" id="step5" name="step" value="5">
                    <?= $form->hiddenField($model, 'bkg_id', ['id' => 'bkg_id5']); ?>
                    <?= $form->hiddenField($model, 'hash', ['id' => 'hash5']); ?>
                    <? /* /?>
                      <div class="hide" style="display: none">
                      <h4>Purpose of my trip is:</h4>
                      <label class="radio-inline">
                      <input type="radio" name="trip_purpose" value="1"> Business
                      </label>
                      <label class="radio-inline">
                      <input type="radio" name="trip_purpose" value="2"> Pleasure
                      </label><br>
                      </div>
                      <!--                    <div id="confirmBtns" class="col-xs-12">
                      <div class="col-xs-12 p0 confBtns">
                      <?
                      //                            if($model1->bkg_discount_amount>0 || $model1->bkg_credits_used>0){
                      //                                $dis=' and get 5% Cashback.';
                      //                            }else{
                      //                              $dis=' and get 5% discount.<a href="#" onclick="showTcGozoCoins1()">*T&Cs</a>.';
                      //                            }
                      ?>

                      <input type="radio" name="confBtns" id="confPayNow" checked="true" value="p1" onclick="payNowLater();">Pay minimum ₹<span id="minipay"><? //= round(0.15 * $model1->bkg_total_amount)          ?></span><span id="discAdvSpan"><? //=$dis         ?></span> (<b>Total Payable: <span style="font-weight: bold" class="dueAmountWithoutCOD" id="dueAmountWithoutCOD">₹<?
                      //                                     if(($model1->bkg_discount_amount==0 || $model1->bkg_discount_amount=='' || $model1->bkg_discount_amount==null) && ($model1->bkg_credits_used==0 || $model1->bkg_credits_used=='' || $model1->bkg_credits_used==null))
                      //                                        {
                      //                                         $mode=clone $model1;
                      //                                         $mode->bkg_discount_amount =round($mode->bkg_base_amount*0.05);
                      //                                         $mode->calculateTotal();
                      //                                         echo $mode->bkg_due_amount;
                      //                                        }else{
                      //                                          echo  $model1->bkg_due_amount;
                      //                                        }
                      ?></span></b>)
                      </div>
                      <div class="col-xs-12 p0 confBtns mt10 mb10">
                      <input type="radio" name="confBtns" id="confPayLater" value="c1" onclick="payNowLater();">
                      Pay later with collect on delivery (COD) fee
                      ₹<span id="conFee"><? //= $model->bkg_due_amount - $model1->bkg_due_amount;           ?>.
                      </span> (<b>Total Payable:  ₹<span style="font-weight: bold" class="dueAmtWithCOD">
                      <? //= round($model->bkg_due_amount);  ?></span></b>)
                      </div>
                      <button  type="submit" class="btn gozo_greenBg btn-lg col-xs-12 col-sm-5 col-lg-4 pl30 pr30 mb10 ml10 text-uppercase white-color"  onclick="confirmBooking('p1')" title="Pay Now">Pay Now <br>
                      <span class="text-capitalize pl30" style="font-size: 14px">Pay minimum ₹<span id="minipay"><?= round(0.15 * $model1->bkg_total_amount) ?></span> and GET 5% CASH BACK*</span><br>
                      <span class="text-capitalize" style="font-size: 14px">total:
                      <span style="font-weight: bold" class="dueAmountWithoutCOD">₹<?= $model1->bkg_due_amount ?></span></span>
                      </button>
                      <button type="submit" class="btn bg-primary btn-lg col-xs-12 col-sm-5 col-lg-4 pl30 pr30 mb10 ml10 text-uppercase white-color"  onclick="confirmBooking('c1')">Pay Later<br>
                      <span class="text-capitalize" style="font-size: 14px">Collect on delivery (COD) fee: ₹<span id="conFee"><?= $model->bkg_due_amount - $model1->bkg_due_amount; ?></span> <br>total:
                      ₹<span style="font-weight: bold" class="dueAmtWithCOD"><?= round($model->bkg_due_amount); ?></span>
                      </span>
                      </button>

                      </div> -->

                      <?
                      $amountWithConvFee = round($model->bkg_due_amount);
                      $isAdvDiscount = 0;
                      if ($model->bkg_promo_code != '') {
                      $promoModel = Promotions::model()->getByCode($model->bkg_promo_code);
                      if ($promoModel->prm_activate_on == 1) {
                      $prmdiscount = Promotions::model()->getDiscount($model->bkg_id, trim($model->bkg_promo_code));
                      if ($promoModel->prm_type == 2) {
                      $prmdiscount = 0;
                      }
                      $remainigDiscount = $model->bkg_discount_amount - $prmdiscount;
                      $discount = ($remainigDiscount > 0) ? $remainigDiscount : 0;
                      $mol = clone $model;
                      $mol->bkg_discount_amount = $discount;
                      $mol->calculateConvenienceFee();
                      $mol->calculateTotal();
                      $amountWithConvFee = round($mol->bkg_due_amount);
                      $isAdvDiscount = 1;
                      }
                      }
                      $hidePayButtons = '';
                      if (!$enableCOD) {
                      $hidePayButtons = 'display: none';
                      }
                      // echo $hidePayButtons;
                      ?>
                     * 


                      <div id="confirmBtns" class="col-xs-12" style="<?= $hidePayButtons ?>">
                      <!--                        Confirm Options-->
                      <div class="col-xs-12 p0 confBtns hide">
                      <label class="radio-inline" style="margin-left: 0px;">
                      <input type="radio" name="confBtns" id="confPayNow"  value="p1" onclick="payNowLater();">
                      Pay Now (Minimum: ₹<span id="minipay"><?= $model1->calculateMinPayment(); ?></span>
                      ,Total Payable: <b>₹<span style="font-weight: bold" class="dueAmountWithoutCOD" id="dueAmountWithoutCOD"><?= $model1->bkg_due_amount; ?></span></b>)
                      </label>
                      </div>
                      <?
                      if (!$enableCOD) {
                      ?>
                      <?/ */ ?>

                    <div class="col-xs-12  pt20">
                        <div class="row">
                            <div class="col-xs-4 col-sm-3">To be paid by 
                            </div>
                            <div class="col-xs-8  col-sm-4">
                                <label class="checkbox-inline ">
                                    <?= $form->radioButtonListGroup($model, 'agentBkgAmountPay', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['onclick' => 'showAgentCreditDiv()'], 'data' => [1 => 'Customer', 2 => 'Agent/Company']), 'inline' => true)) ?>
                                </label>
                            </div>
                        </div> 
                    </div>
                    <div class="col-xs-12 mt20" id="divAgentCredit">
                        <div class="row">
                            <div class="col-xs-12 col-sm-5 col-lg-4">Amount paid by company for the booking </div>
                            <div class="col-xs-5 col-sm-3 col-md-2">
                                <?= $form->numberFieldGroup($model, 'agentCreditAmount', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['class' => 'form-control', 'placeholder' => "Agent Advance Credit", 'min' => 0, 'max' => $model1->bkg_total_amount]))) ?>
                            </div> 

                            <div class="col-xs-5 col-sm-offset-5 col-lg-offset-4  pl0" id="dueAmountDiv"></div>
                        </div>
                    </div>




                    <div class="col-sm-9 mt20">
                        <div class="row">
                            <div class="mb0">
                                <div class="col-xs-12 ">Send a booking copy to</div>
                            </div>
                            <div class="col-xs-12">
                                <div class="col-xs-4 col-sm-3">
                                    <?= $form->textFieldGroup($model, 'bkg_copybooking_name', array('label' => "Name", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Name')))) ?>
                                </div>
                                <div class="col-xs-4 col-sm-3 "> 
                                    <?= $form->textFieldGroup($model, 'bkg_copybooking_email', array('label' => "Email", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Email')))) ?>
                                </div>
                                <div class="col-xs-4 col-sm-3"> 
                                    <?= $form->textFieldGroup($model, 'bkg_copybooking_phone', array('label' => "Phone", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Phone')))) ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12"> 
                                <?
                                $isCopyEmail = false;
                                $isCopySMS = false;
                                if ($model->bkg_copybooking_ismail == 1) {
                                    $isCopyEmail = true;
                                }
                                if ($model->bkg_copybooking_issms == 1) {
                                    $isCopySMS = true;
                                }
                                ?>

                                <div class="col-xs-3 col-sm-2"> 
                                    <?= $form->checkboxListGroup($model, 'bkg_copybooking_ismail', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Email'), 'htmlOptions' => ['checked' => $isCopyEmail]), 'inline' => true)) ?>
                                </div>
                                <div class="col-xs-3 col-sm-2"> 
                                    <?= $form->checkboxListGroup($model, 'bkg_copybooking_issms', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Phone'), 'htmlOptions' => ['checked' => $isCopySMS]), 'inline' => true)) ?>
                                </div>
                            </div>  
                        </div>
                        <div class="row">
                            <div class="col-xs-12"> 
                                <div class="col-xs-3"> 
                                    <?= $form->textFieldGroup($model, 'bkg_trvl_email', array('label' => "Email", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Email (Optional)')))) ?>
                                </div>
                                <div class="col-xs-3 "> 
                                    <?= $form->textFieldGroup($model, 'bkg_trvl_phone', array('label' => "Phone", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Phone (Optional)')))) ?>
                                </div>

                            </div> </div>

                    </div>


<!--                    <div class="col-xs-12  pt20">
                        <div class="row">
                            <div class="col-xs-12">Send booking notifications to Traveller?</div>
                            <div class="col-xs-5">
                                <label class="checkbox-inline ">
                                    <?//= $form->radioButtonListGroup($model, 'bkg_trvl_sendupdate', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['onclick' => 'showNotificationDiv()'], 'data' => [1 => 'Yes', 2 => 'No']), 'inline' => true)) ?>
                                </label>
                            </div>
                        </div>
                    </div>-->



                    <div class="col-xs-12 pt20 " id="divUpd">
                        <div class="row">
                            <div class="col-xs-12">Send booking notifications to Traveller by </div>
                            <div class="col-xs-5 ml20">
                                <label class="checkbox-inline" style='width:70px'>
                                    <?= $form->checkboxGroup($model, 'bkg_send_email', ['label' => 'Email', 'groupOptions' => []]) ?>
                                </label>
                                <label class="checkbox-inline" style='width:70px'>
                                    <?= $form->checkboxGroup($model, 'bkg_send_sms', ['label' => 'Phone']) ?>
                                </label>
                                <label class="checkbox-inline" style='width:70px'>
                                    <?= $form->checkboxGroup($model, 'bkg_send_app', ['label' => 'App']) ?>
                                </label>
                            </div> 
                        </div>


                    </div>



                    <div class="col-xs-12 p0 confBtns mt10 mb10 hide">
                        <label class="radio-inline" style="margin-left: 0px;">
                            <input type="radio" name="confBtns" id="confPayLater" checked="true" value="c1" onclick="payNowLater();"> 
                            Pay Later (collect on delivery (COD) fee: ₹<span id="conFee"><?= ($model->bkg_due_amount - $model1->bkg_due_amount); ?></span>
                            ,Total Payable:  <b>₹<span style="font-weight: bold" class="dueAmtWithCOD"><?= $amountWithConvFee; ?></span></b>)
                        </label>
                    </div>
                    <? /* /?> } ?>
                      </div>     <?/ */ ?> 
                    <div class="col-xs-12 ml30 mt20" >
                        <label class="checkbox">
                            <?= $form->checkboxGroup($model, 'bkg_tnc', ['label' => 'I agree to the Gozo <a href="javascript:void(0);" onclick="opentns()" >terms and conditions</a>']) ?>
                        </label><br>
                        <div id="error_div1" style="display: none" class="alert alert-block alert-danger"></div>
                    </div>
                    <div class="col-xs-12">
                        <input type="hidden" name="iscreditapplied" id="iscreditapplied" value="0">
                        <input type="hidden" name="creditapplied" id="creditapplied" value="0">     
                        <input type="hidden" name="isAdvDiscount" id="isAdvDiscount" value="<?= $isAdvDiscount ?>"> 
                        <button id="connfirmbookbtn" style="height: 50px;"  type="submit" class="btn bg-primary btn-lg  pl30 pr30 text-uppercase white-color"  onclick="confirmBooking();">Confirm Booking</button>
                    </div>		

                    <?php $this->endWidget(); ?>
                </div>
            </div>


            <div class="col-sm-12">
                <div class="row" id="paymentdiv">    
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    $(document).ready(function () {
        bid = '<?= $model->bkg_id ?>';
        hsh = '<?= $model->hash ?>';
        $isRunningAjax = false;
        payNowLater();
      //  showNotificationDiv();
        showAgentCreditDiv();

    });
    $('form').on('focus', 'input[type=number]', function (e) {
        $(this).on('mousewheel.disableScroll', function (e) {
            e.preventDefault()
        })
        $(this).on("keydown", function (event) {
            if (event.keyCode === 38 || event.keyCode === 40) {
                event.preventDefault();
            }
        });
    });
    $('form').on('blur', 'input[type=number]', function (e) {
        $(this).off('mousewheel.disableScroll');
        $(this).off('keydown');
    });

    function opentns()
    {
        $href = '<?= Yii::app()->createUrl('index/tns') ?>';
        jQuery.ajax({type: 'GET', url: $href,
            success: function (data) {
                box = bootbox.dialog({
                    message: data,
                    title: '',
                    size: 'large',
                    onEscape: function () {
                        box.modal('hide');
                    }
                });
            }
        });
    }

//    function showNotificationDiv()
//    {
//        if ($('#Booking_bkg_trvl_sendupdate_0').is(':checked')) {
//            $('#divUpd').show();
//        }
//        if (!$('#Booking_bkg_trvl_sendupdate_0').is(':checked') || $('#Booking_bkg_trvl_sendupdate_1').is(':checked')) {
//            $('#divUpd').hide();
//
//        }
//
//    }
    function showAgentCreditDiv()
    {

        if ($('#Booking_agentBkgAmountPay_0').is(':checked')) {
            $('#divAgentCredit').hide();
        }
        if ($('#Booking_agentBkgAmountPay_1').is(':checked')) {
            $('#divAgentCredit').show();
        }
    }
    $('#Booking_agentCreditAmount').blur(function () {
        validateAgentCreditAmount();
    });

    function validateAgentCreditAmount() {
        $('#dueAmountDiv').text('');
        $('#dueAmountDiv').hide();
      var agtcreditamt= $('#Booking_agentCreditAmount').val();
        if (agtcreditamt < "<?= $model1->bkg_total_amount ?>") {
            var dueamnt =<?= $model1->bkg_total_amount ?> - $('#Booking_agentCreditAmount').val();
            $('#dueAmountDiv').show();
            $('#dueAmountDiv').text('Due amount ₹' + dueamnt + ' will be collected from customer.');
            

        }else if (agtcreditamt > "<?= $model1->bkg_total_amount ?>") {
            $('#dueAmountDiv').show();
            $('#dueAmountDiv').text('Amount exceeding total booking amount');
            return false;
        }
        return true;
    }

    function confirmBooking() {
        //skipPopup();
        var checkedBtns = $("input[name='confBtns']:checked").val();
        validateAgentCreditAmount();
        //  if (checkedBtns === 'c1')
        //  {
        $.ajax({
            "type": "POST",
            "dataType": "json",
            "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('agent/booking/finalbook')) ?>/ctype/" + checkedBtns,
            "data": $("#confirmbook").serialize(),
            "beforeSend": function () {
                ajaxindicatorstart("");
            },
            "complete": function () {
                ajaxindicatorstop();
            },
            "success": function (data2) {
                if (data2.success) {
                    location.href = data2.url;
                } else {
                    var errors = data2.errors;
                    var txt = "<h5>Please fix the following input errors:</h5><ul style='list-style:none'>";
                    $.each(errors, function (key, value) {
                        txt += "<li>" + value + "</li>";
                    });
                    txt += "</li>";
                    $("#error_div1").show();
                    $("#error_div1").html(txt);

                }
            }
        });
        //  }
    }

    function PromoCreditApplyRemove(type) {
        var promo = new Promo();
        var model = {};
        var bkgpcode = $('#Booking_bkg_promo_code').val();
        var creditapplied = $('#creditapplied').val();
        var creditvalamt = parseInt($('#creditvalamt').val());
        var actualcredit = parseInt($('#creditvalamt').attr('credits'));
        var prm_id = 0;
        if (type == "credit" && creditvalamt != '' && actualcredit < creditvalamt) {
            alert('You have only ' + actualcredit + ' credits');
            return;
        }

        if (type == "promo" && (bkgpcode == '' || bkgpcode == null || bkgpcode == undefined)) {
            return;
        }
        if (type == "promoAuto") {
            bkgpcode = $("input[name='Booking_promosAutoApply']:checked").val();
            url = "/booking/promoapply";
            prm_id = bkgpcode;
        }

        if (type == "promo") {
            url = "/booking/promoapply";
        }
        if (type == "credit") {
            url = "/booking/creditapply";
        }
        if (type == "creditRemove") {
            url = "/booking/gozocoinsremove";
        }
        if (type == "promoRemove") {
            url = "/booking/promoremove";
        }

        model.url = url;
        model.bkg_id = bid;
        model.bkghash = hsh;
        model.bkg_pcode = bkgpcode;
        model.prm_id = prm_id;
        model.credit_amount = creditapplied;
        model.amount = creditvalamt;
        promo.model = model;

        $(document).on("PromoCreditAjax", function (event, data) {
            updateData(data);
            // promo.promoupdateData(data);
        });
        promo.PromoCreditAjax();
    }



    function updateData(data)
    {
        $('#spanPromoCreditSucc').html('');
        $("[name=Booking_promosAutoApply]").removeAttr("checked");
        if (data.result)
        {
            $("#errMsgPromo").html("");
            $(".recalculateSummary").removeClass('hide');
            $('.dueAmountWithoutCOD').html(data.due_amount);
            $('#bkgamtdetails111').html(data.due_amount);
            $('.taxAmount').html(data.service_tax);
            $('.dueAmtWithCOD').html(data.amountWithConvFee);
            $('#conFee').html(data.convFee);
            $('#minipay').html(data.minPayable);

            if (data.discount > 0)
            {
                $('.discounttd').show();
            } else
            {
                $('.discounttd').hide();
            }

            if (data.isCredit)
            {
                $('#creditApplyDiv').show();
                $('.tdcredit').hide();
                $('#creditvalamt').val(data.totCredits);
                $('#creditvalamt').attr('credits', data.totCredits);
            } else
            {
                $('#creditApplyDiv').hide();
            }

            if (data.isPromo)
            {
                $("#promoApplyDiv").removeClass('hide');
                $('#autoPromoApplyDiv').removeClass('hide');
            } else
            {
                $("#promoApplyDiv").addClass('hide');
                $('#autoPromoApplyDiv').addClass('hide');
            }
            //if promo applied
            if (data.promo)
            {
                if (data.isAdvDiscount) {
                    $('#isAdvDiscount').val(1);
                    if ($('#isAdvDiscount').val() == 1) {
                        $("#confPayNow").prop('checked', true);
                    }
                } else {
                    $('#isAdvDiscount').val(0);
                }
                $('.discountAmount').html(data.discount);
                $("#txtpromo").text(data.promo_code);
                $("#promoApplyDiv").addClass('hide');
                $("#promoAppliedDiv").removeClass('hide');
                $('#autoPromoApplyDiv').addClass('hide');
                if (data.promo_type == 2)
                {
                    $('#spanPromoCreditSucc').html(data.message);
                } else
                {
                    $('.discounttd').show();
                }
            }

            //if credit applied
            if (data.credit)
            {
                $('#creditapplied').val(data.credits_used);
                $('#iscreditapplied').val(1);
                $('.creditUsed').html(data.credits_used);
                $('#creditApplyDiv').hide();
                $('.tdcredit').show();
                $('#creditRemove').removeClass('hide');
            }

            //if promo removed
            if (data.promoRemove)
            {
                $('#isAdvDiscount').val(0);
                $('#Booking_bkg_promo_code').val('');
                $("#promoApplyDiv").removeClass('hide');
                $("#promoAppliedDiv").addClass('hide');
                $('#autoPromoApplyDiv').removeClass('hide');

                if (data.isCreditUsed)
                {
                    $('#creditRemove').removeClass('hide');
                } else
                {
                    $('#creditRemove').addClass('hide');
                }
                if (!data.isCreditUsed && !data.isPromoUsed)
                {
                    $(".recalculateSummary").addClass('hide');
                }

            }
            //if credit removed
            if (data.creditRemove)
            {
                $('#creditapplied').val(0);
                $('#iscreditapplied').val(0);
                $('#creditApplyDiv').show();
                $('#creditRemove').addClass('hide');
                $('#creditvalamt').val(data.totCredits);
                $('#creditvalamt').attr('credits', data.totCredits);
                if (!data.isCreditUsed && !data.isPromoUsed)
                {
                    $(".recalculateSummary").addClass('hide');
                }
            }

            //advDisc
            payNowLater();
            if (data.due_amount <= 0)
            {
                $('#connfirmbookbtn').show();
                $('#paymentdiv').hide();
                $('.confBtns').hide();
            } else
            {
                $('.confBtns').show();
            }
            //advDisc
        } else
        {
            $("#errMsgPromo").html(data.message);
        }
    }

    function showTcGozoCoins() {
        var href1 = '<?= Yii::app()->createUrl('index/tnsgozocoins') ?>';
        jQuery.ajax({type: 'GET', url: href1,
            success: function (data) {
                box = bootbox.dialog({
                    message: data,
                    title: '',
                    size: 'large',
                    onEscape: function () {
                        box.modal('hide');
                    }
                });
            }
        });
    }

    function showTcGozoCoins2() {
        var href1 = '<?= Yii::app()->createUrl('index/cashbackadv') ?>';
        jQuery.ajax({type: 'GET', url: href1,
            success: function (data) {
                box = bootbox.dialog({
                    message: data,
                    title: '',
                    size: 'large',
                    onEscape: function () {
                        box.modal('hide');
                    }
                });
            }
        });
    }

    function showTcGozoCoins25() {
        var href1 = '<?= Yii::app()->createUrl('index/cashbackadv25') ?>';
        jQuery.ajax({type: 'GET', url: href1,
            success: function (data) {
                box = bootbox.dialog({
                    message: data,
                    title: '',
                    size: 'large',
                    onEscape: function () {
                        box.modal('hide');
                    }
                });
            }
        });
    }


    function showTcGozoCoins2p5() {
        var href1 = '<?= Yii::app()->createUrl('index/discadv2p5') ?>';
        jQuery.ajax({type: 'GET', url: href1,
            success: function (data) {
                box = bootbox.dialog({
                    message: data,
                    title: '',
                    size: 'large',
                    onEscape: function () {
                        box.modal('hide');
                    }
                });
            }
        });
    }
    function payNowLater() {

        var checkedBtns = $("input[name='confBtns']:checked").val();

        //paynow clicked
        if (checkedBtns === 'p1') {
            $('#connfirmbookbtn').hide();
            $('#paymentdiv').show();

            var url2 = '<?= Yii::app()->createUrl('booking/paynow') ?>';

            ajaxPayNow(url2);
        }

        //paylater clicked
        if (checkedBtns === 'c1') {
            if ($('#isAdvDiscount').val() == 1) {
                PromoCreditApplyRemove("promoRemove");
            }
            $('#connfirmbookbtn').show();
            $('#paymentdiv').html('');
            $('#paymentdiv').hide();
        }
    }
    function ajaxPayNow(url) {
        if (!$isRunningAjax)
        {
            var id = '<?= $model->bkg_id ?>';
            var hash = '<?= Yii::app()->shortHash->hash($model->bkg_id) ?>';
            var creditsused = $('#creditapplied').val();
            $.ajax({
                "type": "GET",
                "url": url,
                "dataType": "html",
                data: {'src': 1, 'id': id, 'hash': hash, 'iscreditapplied': creditsused},
                "beforeSend": function () {
                    ajaxindicatorstart("");
                    $isRunningAjax = true;
                },
                "complete": function () {
                    ajaxindicatorstop();
                    $isRunningAjax = false;
                },
                success: function (data) {
                    $isRunningAjax = false;
                    $('#paymentdiv').html(data);
                    //   $('#bookingDetPayNow').hide();
                    var creditsApplied = $('#creditapplied').val();
                    if (creditsApplied > 0) {
                        $('#isPayNowCredits').val(creditsApplied);
                    }

                    $("#proceedPayNow").on("click", function (event) {
                        if ($('#<?= CHtml::activeId($model, "bkg_tnc") ?>').is(':checked'))
                        {
                            $('#error_div1').hide();
                            $('#error_div1').html('');
                        } else
                        {
                            $('#error_div1').show();
                            $('#error_div1').html('Please check Terms and Conditions before proceed.');
                            event.preventDefault();
                        }
                    });
                },
                "error": function (error) {
                    alert(error);
                    $isRunningAjax = false;
                }
            });
        }
    }


</script>