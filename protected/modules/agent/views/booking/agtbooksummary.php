<style type="text/css">
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; 
        margin: 0; 
    }
    input[type="number"] {
        -moz-appearance: textfield;
    }

    .pac-item >.pac-icon-marker{
        display: none !important;
    }
    .pac-item-query{
        padding-left: 3px;
    }  
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
    .checkbox-inline {
        padding-top: 0 !important; 
    }
</style>
<?
//$model= BookingTemp::model()->findByPk($model->bkg_id);
?>
<div class="panel">            
    <div class="panel-body">   
        <div class="col-xs-12 col-lg-8 col-lg-offset-2">
            <div class="row">
                <div class="col-xs-12">
                    <h3 class="mb10 font-24 weight600">Booking Summary:</h3>
                    <div class="row">
                        <div class="col-xs-12 col-sm-6">
                            Primary Passenger Name: <b><?= trim($model->bkg_user_name) . ' ' . trim($model->bkg_user_lname) ?></b>
                        </div>
                        <div class="col-xs-12 col-sm-6 text-right">
                            Vehicle category: <b><?= SvcClassVhcCat::model()->getVctSvcList('string', 0, 0,$model->bkg_vehicle_type_id) ?></b>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 trip_plan">
                    <h3 class="mb10 font-18 weight600">Your Trip Plan</h3>
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
                    $tolltax_flag = $invModel->bkg_is_toll_tax_included;
                    $statetax_flag = $invModel->bkg_is_state_tax_included;
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
                                    <span class="col-xs-6 col-sm-8">Estimated distance of the trip:</span>
                                    <span class="col-xs-6 col-sm-4 blue-color text-right">
                                        <?= $model->bkg_trip_distance ?> Km</span>
                            </div>
                            <div class="row mb10">
                                    <span class="col-xs-6 col-sm-8">Total days for the trip: </span>
                                    <span class="col-xs-6 col-sm-4 blue-color text-right"><?= $totDays ?> days</span>
                            </div>
                            <div class="row mb10">
                                    <span class="col-xs-6 col-sm-8">Base Fare: <?= $taxStr ?></span>
                                    <span class="col-xs-6 col-sm-4 blue-color text-right">&#x20b9;<?= $invModel->bkg_base_amount ?></span>
                                      <?php //echo $invModel->bkg_base_amount ."--".$invModel->bkg_toll_tax ."--". $invModel->bkg_state_tax?>
                            </div>

                            <div class="row mb10 discounttd <?= ($model->bkg_discount_amount > 0) ? '' : 'hide' ?>">
                                    <span class="col-xs-6 col-sm-8">Discount Amount: </span>
                                    <span class="col-xs-6 col-sm-4 blue-color text-right">&#x20b9;<span class="discountAmount"><?= $invModel->bkg_discount_amount ?></span></span>
                            </div>
                            <div class="row mb10 <?= ($invModel->bkg_driver_allowance_amount > 0) ? '' : 'hide' ?>">
                                    <span class="col-xs-6 col-sm-8">Driver Allowance: </span>
                                    <span class="col-xs-6 col-sm-4 blue-color text-right">&#x20b9;<?= $invModel->bkg_driver_allowance_amount ?></span>
                            </div>
                            <?php if ($invModel->bkg_toll_tax > 0) { ?>
                                <div class="row mb10">
                                        <span class="col-xs-6 col-sm-8">Toll Tax: </span>
                                        <span class="col-xs-6 col-sm-4 blue-color text-right">&#x20b9;<?= $invModel->bkg_toll_tax ?></span>
                                </div>
                            <?php }
                            if ($invModel->bkg_airport_entry_fee > 0) { ?>
								<div class="row mb10">
										<span class="col-xs-6 col-sm-8">Airport Entry Fee: </span>
										<span class="col-xs-6 col-sm-4 blue-color text-right">&#x20b9;<?php echo $invModel->bkg_airport_entry_fee ?></span>
                                </div>
							<?php } 
                            if ($invModel->bkg_state_tax > 0) { ?>
                                <div class="row mb10">
                                        <span class="col-xs-6 col-sm-8">Other Tax: <br/><i style="font-size:0.8em">(Including State Tax / Green Tax etc)</i> </span>
                                        <span class="col-xs-6 col-sm-4 blue-color text-right">&#x20b9;<?= $invModel->bkg_state_tax ?></span>
                                </div>
                            <?php }
                            $staxrate = $invModel->getServiceTaxRate();
                            $taxLabel = ($staxrate == 5) ? 'GST' : 'Service Tax ';
                            if($model->bkg_cgst > 0){ ?>
                            <div class="row mb10">
                                    <span class="col-xs-6 col-sm-8">CGST (@<?= Yii::app()->params['cgst'] ?>%):</span>
                                    <span class="col-xs-6 col-sm-4 blue-color text-right">&#x20b9;<?= ((Yii::app()->params['cgst']/$staxrate) * $invModel->bkg_service_tax); ?></span>
                            </div>
                            <? } ?>
                            <? if($model->bkg_sgst > 0){ ?>
                            <div class="row mb10">
                                    <span class="col-xs-6 col-sm-8">SGST (@<?= Yii::app()->params['sgst'] ?>%):</span>
                                    <span class="col-xs-6 col-sm-4 blue-color text-right">&#x20b9;<?= ((Yii::app()->params['sgst']/$staxrate) * $invModel->bkg_service_tax); ?></span>
                            </div>
                            <? } ?>
                            <? if($model->bkg_igst > 0){ ?>
                            <div class="row mb10">
                                    <span class="col-xs-6 col-sm-8">IGST (@<?= Yii::app()->params['igst'] ?>%):</span>
                                    <span class="col-xs-6 col-sm-4 blue-color text-right">&#x20b9;<?= ((Yii::app()->params['igst']/$staxrate) * $invModel->bkg_service_tax); ?></span>
                            </div>
                            <? } ?>
                            <? if($staxrate != 5){ ?>
                            <div class="row mb10">
                                    <span class="col-xs-6 col-sm-8"><?=$taxLabel?>: </span>
                                    <span class="col-xs-6 col-sm-4 blue-color text-right">&#x20b9;<?= $invModel->bkg_service_tax ?></span>
                            </div>
                            <? } ?>
                            <div class="row mb10">
                                    <span class="col-xs-6 col-sm-8">Estimated Trip cost: </span>
                                    <span class="col-xs-6 col-sm-4 blue-color text-right font-16"><b>&#x20b9;<span id="bkgamtdetails111"><?= $invModel->bkg_total_amount ?></span></b></span>
                        <?php //echo $invModel->bkg_base_amount ."--".$invModel->bkg_toll_tax ."--". $invModel->bkg_state_tax?>
                            </div>
                            <? if ($invModel->bkg_agent_markup > 0) { ?>
                                <div class="row mb10">
                                        <span class="col-xs-6 col-sm-8">Agent Commision:  </span>
                                        <span class="col-xs-6 col-sm-4 blue-color text-right">&#x20b9;<?= $invModel->bkg_agent_markup ?></span>
                                </div>  

                                <div class="row mb10 hide">
                                        <span class="col-xs-6 col-sm-8">Gross Base Fare:  </span>
                                        <span class="col-xs-6 col-sm-4 blue-color text-right">&#x20b9;<?= $invModel->bkg_base_amount ?></span>
                                </div>
                            <? } ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class='col-xs-12 hidden-lg hidden-md hidden-sm recalculateSummary <?= $hide ?> '>

                    <div class="row">
                        <div class="col-xs-8 bg bg-success p10">
                            Base Fare 
                        </div>
                        <div class="col-xs-1 bg bg-success p10">
                            &nbsp;
                        </div>
                        <div class="col-xs-3 bg bg-success p10">
                            <span><i class="fa fa-inr"></i> <span id="baseamt"><?= $invModel->bkg_base_amount + $invModel->bkg_toll_tax + $invModel->bkg_state_tax ?></span></span>
                           <?php //echo $invModel->bkg_base_amount ."--".$invModel->bkg_toll_tax ."--". $invModel->bkg_state_tax?>
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
                            <div class="col-xs-8 bg bg-info p10">
                                Driver Allowance
                            </div>
                            <div class="col-xs-1 bg bg-info p10">
                                <b>+</b>
                            </div>
                            <div class="col-xs-3 bg bg-info p10">
                                <span><i class="fa fa-inr"></i><span id="discount"><?= $invModel->bkg_driver_allowance_amount ?></span></span>
                            </div>
                        </div>
                    <? } ?>
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
            <div class='col-sm-12 hidden-xs mt10 recalculateSummary <?= $hide ?>'>
                <div class="row mt10">

                    <div class="book-font">
                        <table class="col-sm-12 no-border table-responsive" style="table-layout: fixed">
                            <tr>
                                <td class="bg bg-success p10 text-center col-sm-2">
                                    Base Fare <br/>
                                    <span><i class="fa fa-inr"></i> 
                                        <span id="baseamt"><?= $invModel->bkg_base_amount + $invModel->bkg_toll_tax + $invModel->bkg_state_tax ?>
                                       <?php //echo $invModel->bkg_base_amount ."--".$invModel->bkg_toll_tax ."--". $invModel->bkg_state_tax?>
                                        
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
                                    <td class="bg bg-info p10 text-center col-sm-2">
                                        Driver Allowance<br/>
                                        <span><i class="fa fa-inr"></i>
                                            <span id="discount"><?= $invModel->bkg_driver_allowance_amount ?></span>
                                        </span>
                                    </td>
                                <?php  }
                                 if ($invModel->bkg_airport_entry_fee > 0) { ?>
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
            </div>

        </div>





        <div class="col-xs-12 col-lg-8 col-lg-offset-2">
            <div class="row mt15">
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
                    'htmlOptions' => array(
                        'onsubmit' => "return false;", /* Disable normal form submit */
                        'class' => 'form-horizontal',
                    ),
                ));
                /* @var $form TbActiveForm */
                ?>
                <?//= $form->errorSummary($model); ?>
                <?//= CHtml::errorSummary($model); ?>
                <input type="hidden" id="step5" name="step" value="agtsummary">
                <?= $form->hiddenField($model, 'bkg_id', ['id' => 'bkg_id5']); ?>
                <?= $form->hiddenField($model, 'hash', ['id' => 'hash5', 'value' => Yii::app()->shortHash->hash($model->bkg_id)]); ?>
                <?= $form->hiddenField($model, 'preData', []); ?>
                <?= $form->hiddenField($model, 'agentNotifyData', []); ?>
                <?=$form->hiddenField($model,'bookingRoutes');?>
				<?=$form->hiddenField($model,'bkg_route_data');?>
                <div class="col-xs-12 pl50">
                    <label class="checkbox">
                        <?= $form->checkboxGroup($model, 'bkg_tnc', ['label' => 'I agree to the Gozo <a href="javascript:void(0);" onclick="opentns()" >terms and conditions</a>']) ?>
                    </label>
                    <div id="error_div1" style="display: none" class="alert alert-block alert-danger"></div>
                </div>
                <div class="col-xs-12">
                    <button id="connfirmbookbtn" style="height: 50px; "  type="submit" class="btn bg-primary btn-lg  pl30 pr30 text-uppercase white-color" >Confirm Booking</button>
                </div>		

                <?php $this->endWidget(); ?>
            </div>



        </div>
    </div> 
</div>

</div>

<script type="text/javascript">
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

    $("#confirmbook").on('submit', function (event) {
        if ($('#<?= CHtml::activeId($model, "bkg_tnc") ?>').is(':checked'))
        {
            $('#error_div1').hide();
            $('#error_div1').html('');
            processBooking();
        } else
        {
            $('#error_div1').show();
            $('#error_div1').html('Please check Terms and Conditions before proceed.');
            event.preventDefault();
        }

    });

    function processBooking() {
        debugger;
        $.ajax({
            "type": "POST",
            "dataType": "json",
            "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('agent/booking/quotetobook')) ?>",
            "data": $("#confirmbook").serialize(),
            "beforeSend": function () {
               // ajaxindicatorstart("");
            },
            "complete": function () {
              //  ajaxindicatorstop();
            },
            "success": function (data2) {
                if (data2.success) {
                     debugger;
                    location.href = data2.url;
                } else {
                     debugger;
                    var errors = data2.errors;
                    var txt = "<h5>Please fix the following input errors:</h5><ul style='list-style:none'>";
                    $.each(errors, function (key, value) {
                        txt += "<li>" + value + "</li>";
                    });
                    txt += "</li>";
                    $("#error_div1").show();
                    $("#error_div1").html(txt);

                }
            }, "error": function (error) {
                 alert(JSON.stringify(error));
            }

        });
        //  }
    }

</script>
