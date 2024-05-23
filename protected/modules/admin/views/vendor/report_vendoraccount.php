<style>
    span.stars, span.stars span {
        display: block;
        background: url(http://localhost:92/images/stars.png) 0 -16px repeat-x;
        width: 80px;
        height: 16px;
    }

    span.stars span {
        background-position: 0 0;
    }
</style>
<?php
$ptpJson			 = VehicleTypes::model()->getJSON(PaymentType::model()->getList(false, false));
$modeJson			 = VehicleTypes::model()->getJSON(AccountTransactions::model()->getModeList());
$bankTransType		 = VehicleTypes::model()->getJSON(AccountTransDetails::model()->getbankTransTypeList());
$operatorJson		 = VehicleTypes::model()->getJSON(AccountTransactions::model()->getOperatorList());
?>

<section id="section7">
    <div class="container">
        <div class="profile-right-panel p20">
            <div class="row">
                <div class="col-xs-12 col-sm-5 table-responsive">
                    <table class="table table-striped table-bordered">
                        <tr>
                            <td><b>Vendor</b></td>
                            <td><?= $record['vnd_name'] ?></td>
                        </tr>
                        <tr>
                            <td><b>Owner</b></td>
                            <td><?= ($record['vnd_owner'] == '') ? 'Not Available' : $record['vnd_owner'] ?></td>
                        </tr>
                        <tr>
                            <td><b>Owner phone no.</b></td>
                            <td><?= $record['vnd_phone'] ?></td>
                        </tr>
                        <tr>
                            <td><b>Preferred method of contact</b></td>
                            <td>Phone</td>
                        </tr>                        
                        <tr>
                            <td><b>Contract copy</b></td>
                            <td><a href="#" class="btn btn-info" id="review" onclick="" title="File" target="_blank" style="padding: 0px 6px;">File</a></td>
                        </tr>
						<tr>
                            <td><b>Beneficiary Id</b></td>
                            <td><?= $record['vnd_beneficiary_id'] ?></td>
                        </tr>
                    </table>
                    <h4 class="mb5">Permanent notes</h4>
                    <p><?= $record['vnd_notes'] ?></p>
                </div>
                <div class="col-xs-12 col-sm-7 table-responsive">
                    <table class="table table-striped table-bordered">
						<? $overall_rating		 = ($record['vnd_overall_rating'] == '') ? 'Not Available' : $record['vnd_overall_rating'] ?>
						<? $overall_star_rating = ($record['vnd_overall_rating'] == '') ? 0 : $record['vnd_overall_rating'] ?>
                        <tr>
                            <td><b>Current rating</b></td>
                            <td><span class="stars"><?= $overall_star_rating ?></span><?= $overall_rating ?></td>
                        </tr>
                        <tr>
                            <td><b>Rating trend</b></td>
                            <td>
                                <div class="col-xs-4 pl0"><span class="stars"><?= ($record['vnd_last_three_month_rating'] == '') ? $overall_star_rating : $record['vnd_last_three_month_rating'] ?></span><?= ($record['vnd_last_three_month_rating'] == '') ? $overall_rating : $record['vnd_last_three_month_rating'] ?><br>(3 m)</div>
                                <div class="col-xs-4"><span class="stars"><?= ($record['vnd_last_six_month_rating'] == '') ? $overall_star_rating : $record['vnd_last_six_month_rating'] ?></span><?= ($record['vnd_last_six_month_rating'] == '') ? $overall_rating : $record['vnd_last_six_month_rating'] ?><br>(6 m)</div>
                                <div class="col-xs-4"><span class="stars"><?= ($record['vnd_last_twelve_month_rating'] == '') ? $overall_star_rating : $record['vnd_last_twelve_month_rating'] ?></span><?= ($record['vnd_last_twelve_month_rating'] == '') ? $overall_rating : $record['vnd_last_twelve_month_rating'] ?><br>(12 m)</div>
                            </td>
                        </tr>
                        <tr>
                            <td><b># of active drivers</b></td>
                            <td><?= ($record['vnd_total_drivers'] == '') ? 0 : $record['vnd_total_drivers'] ?></td>
                        </tr>
                        <tr>
                            <td><b># of active vehicles</b></td>
                            <td><?= ($record['vnd_total_vehicles'] == '') ? 0 : $record['vnd_total_vehicles'] ?></td>
                        </tr>
						<? $zones				 = str_replace(",", ", ", $record['vnd_zones']); ?>
						<? $zones				 = str_replace("Z-", "", $zones); ?>
                        <tr>
                            <td><b>Zones operating in</b></td>
                            <td><?= ($zones == '') ? 'Not Available' : $zones ?></td>
                        </tr>
                        <tr>
                            <td><b>Home City</b></td>
                            <td><?= ($record['vnd_home_city'] == '') ? 'Not Available' : $record['vnd_home_city'] ?></td>
                        </tr>
                        <tr>
                            <td><b># of Trips</b></td>
                            <td>
                                <div class="col-sm-2 pl0"><?= $record['vnd_last_ten_day_trips'] ?><br>(Last 10 d)</div>
                                <div class="col-sm-2"><?= $record['vnd_last_one_month_trips'] ?><br>(1 m)</div>
                                <div class="col-sm-2"><?= $record['vnd_last_three_month_trips'] ?><br>(3 m)</div>
                                <div class="col-sm-2"><?= $record['vnd_last_six_month_trips'] ?><br>(6 m)</div>
                                <div class="col-sm-2"><?= $record['vnd_last_twelve_month_trips'] ?><br>(12 m)</div>
                                <div class="col-sm-2"><?= ($record['vnd_total_trips'] == '') ? 0 : $record['vnd_total_trips'] ?><br>(lifetime)</div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-5">Vendor Credit limit: <input type="text" style="width: 25%;" value="<?= $record['vnd_credit_limit'] ?>" readonly="readonly"> (recommended amount:2222)</div>
                <div class="col-sm-7">Credit throttle level: <b>75%</b></div>
            </div>
            <div id="vendorContent">
				<?php
				$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
					'id'					 => 'addamount-form', 'enableClientValidation' => true,
					'clientOptions'			 => array(
						'validateOnSubmit'	 => true,
						'errorCssClass'		 => 'has-error',
					),
					// Please note: When you enable ajax validation, make sure the corresponding
					// controller action is handling ajax validation correctly.
					// See class documentation of CActiveForm for details on this,
					// you need to use the performAjaxValidation()-method described there.
					'enableAjaxValidation'	 => false,
					'errorMessageCssClass'	 => 'help-block',
					'htmlOptions'			 => array(
						'class' => 'form-horizontal'
					),
				));
				/* @var $form TbActiveForm */
				?>


                <div class="row mt30">
                    <div class="col-sm-3 table-responsive">
                        <table class="table table-striped table-bordered">
                            <tr>
                                <td><b>Accounts Payable</b></td>
                                <td><i class="fa fa-inr"></i><?php
									if ($vendorAmount['vendor_amount'] < 0)
									{
										echo trim(-1 * $vendorAmount['vendor_amount']);
									}
									else
									{
										echo '0';
									}
									?></td>
                            </tr>
                            <tr>
                                <td><b>Accounts Receivable</b></td>
                                <td><i class="fa fa-inr"></i><?php
									if ($vendorAmount['vendor_amount'] > 0)
									{
										echo trim($vendorAmount['vendor_amount']);
									}
									else
									{
										echo '0';
									}
									?></td>
                            </tr>
							<tr>
                                <td><b>Security Deposit</b></td>
                                <td><i class="fa fa-inr"></i>
									<?= ($vendorAmount['vnd_security_amount'] > 0) ? round($vendorAmount['vnd_security_amount']) : 0; ?>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="col-sm-9">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div id="row">
                                    <div class="col-sm-8">
										<?= $form->textAreaGroup($model, 'ven_trans_remarks', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'add notes here on payment received, payment sent or any communication with vendor (promise of payment, phone call or complaints). A manual entry will not appear on invoice unless an amount is entered in the manual entry.(+=credit to gozo, -=debit to gozo)', 'class' => 'form-control', 'title' => 'add notes here on payment received, payment sent or any communication with vendor (promise of payment, phone call or complaints). A manual entry will not appear on invoice unless an amount is entered in the manual entry.(+=credit to gozo, -=debit to gozo)', 'style' => 'min-height:83px')))) ?>
                                    </div>
                                    <div class="col-sm-4 pl50">
										<?= $form->textFieldGroup($model, 'ven_trans_amount', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('style' => 'width:220px', 'placeholder' => 'Enter Amount', 'class' => 'form-control')))) ?>
										<?php
										$this->widget('booster.widgets.TbSelect2', array(
											'model'			 => $model,
											'attribute'		 => 'ven_operator_id',
											'val'			 => $model->ven_operator_id,
											'asDropDownList' => FALSE,
											'options'		 => array('data' => new CJavaScriptExpression($operatorJson)),
											'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Operator Type')
										));
										?>
                                    </div>
                                </div>
                                <div id="row">
                                    <div class="row col-sm-12"> 
                                        <div class="col-sm-4">
											<?=
											$form->datePickerGroup($model, 'ven_trans_date', array('label'			 => '',
												'widgetOptions'	 => array('options'		 => array('autoclose'	 => true, 'startDate'	 => date(),
														'format'	 => 'dd/mm/yyyy'), 'htmlOptions'	 => array('placeholder'	 => 'Transaction Date',
														'class'			 => 'input-group border-gray full-width')),
												'prepend'		 => '<i class="fa fa-calendar"></i>'));
											?>
                                        </div>
                                        <div class="col-sm-4">
											<?php
											$this->widget('booster.widgets.TbSelect2', array(
												'model'			 => $model,
												'attribute'		 => 'ven_ptp_id',
												'val'			 => $model->ven_ptp_id,
												'asDropDownList' => FALSE,
												'options'		 => array('data' => new CJavaScriptExpression($ptpJson)),
												'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Payment Type')
											));
											?><?php echo $form->error($model, 'ven_ptp_id'); ?>
                                        </div>

                                    </div>
                                </div>
                                <div id="row">
                                    <div class="row col-sm-12"> 
                                        <div class="col-sm-4">
                                            <div id="bankNameBlock" style="display:none;">
												<?= $form->textFieldGroup($model, 'bank_name', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Bank Name', 'class' => 'form-control')))) ?>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div id="transTypeBlock" style="display:none;">
												<?php
												$this->widget('booster.widgets.TbSelect2', array(
													'model'			 => $model,
													'attribute'		 => 'ven_trans_type',
													'val'			 => $model->ven_trans_type,
													'asDropDownList' => FALSE,
													'options'		 => array('data' => new CJavaScriptExpression($bankTransType)),
													'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Bank Transaction Type')
												));
												?>
                                            </div>

                                        </div>
                                        <div class="col-sm-4">
                                            <div id="bankBranchBlock" style="display:none;">
												<?= $form->textFieldGroup($model, 'bank_branch', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Bank Branch', 'class' => 'form-control')))) ?>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div id="row">
                                    <div class="row col-sm-12"> 
                                        <div class="col-sm-4">
                                            <div id="bankChquenoBlock"  style="display:none;">
												<?= $form->textFieldGroup($model, 'bank_chq_no', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Bank Cheque No.', 'class' => 'form-control')))) ?>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 ml15">
                                            <div id="bankChqueDateBlock"  style="display:none;">
												<?=
												$form->datePickerGroup($model, 'bank_chq_dated', array('label'			 => '',
													'widgetOptions'	 => array('options'		 => array('autoclose'	 => true, 'startDate'	 => date(),
															'format'	 => 'dd/mm/yyyy'), 'htmlOptions'	 => array('placeholder'	 => 'Cheque Dated',
															'class'			 => 'input-group border-gray full-width')),
													'prepend'		 => '<i class="fa fa-calendar"></i>'));
												?>                                                    </div>
                                        </div>
                                        <div class="col-sm-4">&nbsp;</div>
                                    </div>
                                </div>


                                <div id="row">
                                    <div class="row col-sm-12"> 
                                        <div class="col-sm-4">
                                            <div id="bankIfscBlock" style="display:none;">
												<?= $form->textFieldGroup($model, 'bank_ifsc', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Bank IFSC Code', 'class' => 'form-control')))) ?>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
											<?= CHtml::submitButton('Save Manual Entry', array('class' => 'btn btn-success mt5')); ?>
                                        </div>
                                        <div class="col-sm-4">&nbsp;</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
				<?php $this->endWidget(); ?>
                <div class="row">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="col-xs-12 table-responsive">
                                <table class="table table-bordered">
                                    <tr class="blue2 white-color">
                                        <td align="center"><b>Date</b></td>
                                        <td align="center"><b>Trip ID</b></td>

                                        <td align="center"><b>Advanced Collected</b></td>
                                        <td align="center"><b>Pickup Date</b></td>
                                        <td align="center"><b>Booking Info</b></td>

                                        <td class="text-center"><b>amount (<i class="fa fa-inr"></i>)</b><br>(+=credit to gozo,<br>-=credit to vendor)</td>
                                        <td align="center"><b>Notes</b></td>
                                        <td align="center"><b>Who</b></td>
                                        <td align="center"><b>Running Balance</b></td>
                                    </tr>
									<?php
									$ctr				 = 0;
									$currentBal			 = $vendorAmount['vendor_amount'];
									$countTransaction	 = count($vendorModels);
									if (count($vendorModels) > 0)
									{
										foreach ($vendorModels as $vendor)
										{
											$bookingId	 = ($vendor['bkg_booking_id'] == NULL) ? 'NA' : $vendor['bkg_booking_id'];
											$pickupDate	 = ($vendor['bkg_booking_id'] == NULL) ? 'NA' : date('d/m/Y', strtotime($vendor['bkg_pickup_date']));
											$fromCity	 = ($vendor['from_city'] == NULL) ? 'NA' : trim($vendor['from_city']);

											$advanceAmt		 = ($vendor['advance_amount'] == NULL || $vendor['advance_amount'] == '0') ? 'NA' : trim($vendor['advance_amount']);
											$balance[$ctr]	 = $vendor['ven_trans_amount'];
											$index			 = ($countTransaction - $ctr);
											$bookingDetail	 = ($vendor['bkg_booking_id'] == NULL) ? 'NA' : $bookingId . "<BR>" . $fromCity;
											?>
											<tr>
												<td><?php echo date('d/m/Y', strtotime($vendor['act_date'])); ?></td>
												<td><?= ($vendor['ven_trip_id'] == NULL) ? 'NA' : $vendor['ven_trip_id'] ?></td>

												<td align="right"><?= $advanceAmt; ?></td>
												<td><?= $pickupDate ?></td>
												<td><?= $bookingDetail ?></td>

												<td class="text-right"><?php echo round(trim($vendor['ven_trans_amount'])); ?></td>
												<td><?php echo trim($vendor['ven_trans_remarks']); ?></td>
												<td><b><?php echo trim($vendor['adm_name']); ?></b></td>
												<td align="right"><?= $currentBal; ?></td>
											</tr>
											<?php
											$currentBal		 = ($currentBal - $vendor['ven_trans_amount']);
											$ctr			 = ($ctr + 1);
										}
									}
									else
									{
										?>
										<tr><td colspan="10">No Records Yet Found.</td></tr>
									<?php }
									?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
			<?php
			$checkExportAccess	 = Yii::app()->user->checkAccess("Export");
			$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
				'id'					 => 'generate-vendor-form', 'enableClientValidation' => true,
				'action'				 => '/admpnl/vendor/ledgerpdf',
				'clientOptions'			 => array(
					'validateOnSubmit'	 => true,
					'errorCssClass'		 => 'has-error'
				),
				// Please note: When you enable ajax validation, make sure the corresponding
				// controller action is handling ajax validation correctly.
				// See class documentation of CActiveForm for details on this,
				// you need to use the performAjaxValidation()-method described there.
				'enableAjaxValidation'	 => false,
				'errorMessageCssClass'	 => 'help-block',
				'htmlOptions'			 => array(
					'class'	 => '',
					'target' => '_blank',
				),
			));
			/* @var $form TbActiveForm */
			?>
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-5 text-right mt10">
                    Generate Invoices
                    <label class="radio-inline">
						<?=
						$form->radioButtonListGroup($model, 'ven_date_type', array(
							'label'			 => '', 'widgetOptions'	 => array(
								'data' => array('1' => 'This Week', '2' => 'Date Range'),
							), 'inline'		 => true,)
						);
						?>
                    </label>
                </div>
                <div class="col-xs-12 col-sm-9 col-md-5 mb10">
                    <div class="row">
                        <div class="col-sm-6 col-md-6 mb10 p5">
                            <div class="input-group full-width">
								<?= $form->datePickerGroup($model, 'ven_from_date', array('label' => '', 'widgetOptions' => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'From Date')), 'prepend' => '<i class="fa fa-calendar"></i>')); ?>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6 p5">
                            <div class="input-group full-width">
								<?=
								$form->datePickerGroup($model, 'ven_to_date', array('label'			 => '',
									'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'To Date')), 'prepend'		 => '<i class="fa fa-calendar"></i>'));
								?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-3 col-md-2 mb10 p5">
                    <div class="input-group col-xs-12">
						<?php echo CHtml::submitButton('Generate PDF', array('class' => 'btn btn-success', 'placeholder' => 'Generate PDF')); ?>
                    </div>
                </div>
            </div>
			<?= $form->hiddenField($model, "trans_vendor_id"); ?>
			<?php
			$this->endWidget();
			?>
        </div>
    </div>
</section>
<script>
    $(function () {
        $('span.stars').stars();
    });
    $.fn.stars = function () {
        return $(this).each(function () {
            // Get the value
            var val = parseFloat($(this).html());
            // Make sure that the value is in 0 - 5 range, multiply to get width
            var size = Math.max(0, (Math.min(5, val))) * 16;
            // Create stars holder
            var $span = $('<span />').width(size);
            // Replace the numerical value with stars
            $(this).html($span);
        });
    }
    $("#VendorTransactions_ven_date_type_0").click(function () {
        var dateVal = $("#VendorTransactions_ven_date_type_0").val();
<?php
$dateFromDate		 = DateTimeFormat::DateToLocale($dateFromDate);
$dateTodate			 = DateTimeFormat::DateToLocale($dateTodate);
?>
        $("#VendorTransactions_ven_from_date").val('<?= DateTimeFormat::DateToLocale($dateFromDate) ?>');
        $("#VendorTransactions_ven_to_date").val('<?= DateTimeFormat::DateToLocale($dateTodate) ?>');
    });

    $("#VendorTransactions_ven_date_type_1").click(function () {
        var dateVal = $("#VendorTransactions_ven_date_type_1").val();
        $("#VendorTransactions_ven_from_date").val('');
        $("#VendorTransactions_ven_to_date").val('');
    });

    $("#VendorTransactions_ven_ptp_id").click(function () {
        var ptpValue = $("#VendorTransactions_ven_ptp_id").val();
        checkPaymentType(ptpValue);
    });

    $("#VendorTransactions_ven_trans_type").click(function () {
        var transValue = $("#VendorTransactions_ven_trans_type").val();
        checkTransactionType(transValue);
    });

    /*
     $("#VendorTransactions_ven_date_type_0").click(function () {
     
     if ($('#VendorTransactions_ven_date_type_0').attr('checked', 'checked')) {
     var dateValue = $("#VendorTransactions_ven_date_type_0").val();
     var currentDate = new Date();
     $("#VendorTransactions_ven_from_date").val();
     $("#VendorTransactions_ven_to_date").val();
     } else {
     $("#VendorTransactions_ven_from_date").val('');
     $("#VendorTransactions_ven_to_date").val('');
     }
     });
     
     $("#VendorTransactions_ven_date_type_1").click(function () {
     var dateValue = $("#VendorTransactions_ven_date_type_1").val();
     });
     */
    function checkPaymentType(ptpVal) {
        if (ptpVal == 2)
        {
            $("#transTypeBlock").show();
            $("#bankNameBlock").show();
            $("#bankIfscBlock").hide();
        } else {
            $("#transTypeBlock").hide();
            $("#bankNameBlock").hide();
            $("#bankIfscBlock").hide();
            $("#bankBranchBlock").hide();
            $("#bankChquenoBlock").hide();
            $("#bankChqueDateBlock").hide();
        }
        if (ptpVal == 8)
        {
            $("#VendorTransactions_ven_trans_date").attr("placeholder", "Settlment Date");
        } else
        {
            $("#VendorTransactions_ven_trans_date").attr("placeholder", "Transaction Date");
        }
    }

    function checkTransactionType(transType) {
        if (transType == 1) {
            $("#bankBranchBlock").show();
            $("#bankIfscBlock").hide();
            $("#bankChquenoBlock").hide();
            $("#bankChqueDateBlock").hide();
        } else if (transType == 2) {
            $("#bankBranchBlock").show();
            $("#bankIfscBlock").hide();
            $("#bankChquenoBlock").show();
            $("#bankChqueDateBlock").show();
        } else if (transType == 3) {
            $("#bankBranchBlock").show();
            $("#bankIfscBlock").show();
            $("#bankChquenoBlock").hide();
            $("#bankChqueDateBlock").hide();
        }
    }

    $(document).ready(function () {
    });

</script>