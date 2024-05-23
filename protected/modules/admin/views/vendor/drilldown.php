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
$ptpJson			 = VehicleTypes::model()->getJSON(PaymentType::model()->getList(false));
$modeJson			 = VehicleTypes::model()->getJSON(AccountTransDetails::model()->getModeList());
$bankTransType		 = VehicleTypes::model()->getJSON(AccountTransDetails::model()->getbankTransTypeList());
?>
<section id="section7">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 book-panel2">
                <div class="container p0 mt20">
                    <div class="col-xs-12">
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
                                    </table>
                                    <h4 class="mb5">Permanent notes</h4>
                                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p>
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
                                                <div class="col-xs-4 pl0"><span class="stars"><?= ($record['vnd_last_three_month_rating'] == '') ? $overall_star_rating : $record['vnd_last_three_month_rating'] ?></span><?= ($record['vnd_last_three_month_rating'] == '') ? $overall_rating : $record['vnd_last_three_month_rating'] ?><br>(3months)</div>
                                                <div class="col-xs-4"><span class="stars"><?= ($record['vnd_last_six_month_rating'] == '') ? $overall_star_rating : $record['vnd_last_six_month_rating'] ?></span><?= ($record['vnd_last_six_month_rating'] == '') ? $overall_rating : $record['vnd_last_six_month_rating'] ?><br>(6months)</div>
                                                <div class="col-xs-4"><span class="stars"><?= ($record['vnd_last_twelve_month_rating'] == '') ? $overall_star_rating : $record['vnd_last_twelve_month_rating'] ?></span><?= ($record['vnd_last_twelve_month_rating'] == '') ? $overall_rating : $record['vnd_last_twelve_month_rating'] ?><br>(12months)</div>
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
                                                <div class="col-sm-2 pl0"><?= $record['vnd_last_ten_day_trips'] ?><br>(Last 10 days)</div>
                                                <div class="col-sm-2"><?= $record['vnd_last_one_month_trips'] ?><br>(1month)</div>
                                                <div class="col-sm-2"><?= $record['vnd_last_three_month_trips'] ?><br>(3months)</div>
                                                <div class="col-sm-2"><?= $record['vnd_last_six_month_trips'] ?><br>(6months)</div>
                                                <div class="col-sm-2"><?= $record['vnd_last_twelve_month_trips'] ?><br>(12months)</div>
                                                <div class="col-sm-2"><?= ($record['vnd_total_trips'] == '') ? 0 : $record['vnd_total_trips'] ?><br>(lifetime)</div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-5">Vendor Credit limit: <input type="text" style="width: 25%;"> (recommended amount:2222)</div>
                                <div class="col-sm-7">Credit throttle level: <b>75%</b></div>
                            </div>
							<?php
							$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
								'id'					 => 'vendor-transaction-form',
								'enableClientValidation' => true,
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
									'class' => '',
								),
							));
							/* @var $form TbActiveForm */
							?>
                            <div class="row mt30">
                                <div class="col-sm-3 table-responsive">
                                    <table class="table table-striped table-bordered">
                                        <tr>
                                            <td><b>Account Payble</b></td>
                                            <td><i class="fa fa-inr"></i>4444</td>
                                        </tr>
                                        <tr>
                                            <td><b>Account receivable</b></td>
                                            <td><i class="fa fa-inr"></i>5555</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-sm-6">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
												<?= $form->textAreaGroup($model, 'ven_trans_remarks', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Description', 'class' => 'form-control')))) ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
												<?php
												$this->widget('booster.widgets.TbSelect2', array(
													'model'			 => $model,
													'attribute'		 => 'ven_ptp_id',
													'val'			 => $model->ven_ptp_id,
													'asDropDownList' => FALSE,
													'options'		 => array('data' => new CJavaScriptExpression($ptpJson)),
													'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Payment Type')
												));
												?>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
												<?php
												$this->widget('booster.widgets.TbSelect2', array(
													'model'			 => $model,
													'attribute'		 => 'ven_trans_mode',
													'val'			 => $model->ven_trans_mode,
													'asDropDownList' => FALSE,
													'options'		 => array('data' => new CJavaScriptExpression($modeJson)),
													'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Mode')
												));
												?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div id="bankNameBlock" class="form-group" style="display:none;">
												<?= $form->textFieldGroup($model, 'bank_name', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Bank Name', 'class' => 'form-control')))) ?>
                                            </div>
                                        </div>
                                        <div id="bankIfscBlock" class="col-sm-4"  style="display:none;">
                                            <div class="form-group">
												<?= $form->textFieldGroup($model, 'bank_ifsc', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Bank IFSC Code', 'class' => 'form-control')))) ?>
                                            </div>
                                        </div>
                                        <div id="bankBranchBlock" class="col-sm-4"  style="display:none;">
                                            <div class="form-group">
												<?= $form->textFieldGroup($model, 'bank_branch', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Bank Branch', 'class' => 'form-control')))) ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
										<?= $form->textFieldGroup($model, 'ven_trans_amount', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Payment Amount', 'class' => 'form-control')))) ?>
                                    </div>
                                    <div id="transTypeBlock" class="form-group" style="display:none;">
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
                                    <div id="bankChquenoBlock"  class="form-group"  style="display:none;">
										<?= $form->textFieldGroup($model, 'bank_chq_no', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Bank Cheque No.', 'class' => 'form-control')))) ?>
                                    </div>
                                    <div class="form-group">
										<?= CHtml::submitButton('Save Manual Entry', array('class' => 'btn btn-success mt5')); ?>
                                    </div>
                                </div>
                            </div>
							<?php $this->endWidget(); ?>
                            <div class="row">
                                <div class="col-xs-12 table-responsive">
                                    <table class="table table-bordered">
                                        <tr class="blue2 white-color">
                                            <td><b>Date</b></td>
                                            <td><b>Booking ID</b></td>
                                            <td><b>From city</b></td>
                                            <td><b>To city</b></td>
                                            <td><b>Amount</b> (+=credit to gozo -=credit to vendor)</td>
                                            <td><b>Notes</b></td>
                                            <td><b>Who</b></td>
                                        </tr>
                                        <tr>
                                            <td>22/05/2016</td>
                                            <td>WOXXXXX</td>
                                            <td>a</td>
                                            <td>b</td>
                                            <td>2000</td>
                                            <td>payment received vide check 5649 in SBI XXXX</td>
                                            <td><b>Gourav</b></td>
                                        </tr>
                                        <tr>
                                            <td>22/05/2016</td>
                                            <td>WOXXXXX</td>
                                            <td>a</td>
                                            <td>b</td>
                                            <td>-500</td>
                                            <td>payment received vide check 5649 in SBI XXXX</td>
                                            <td><b>Gourav</b></td>
                                        </tr>
                                        <tr>
                                            <td>22/05/2016</td>
                                            <td>WOXXXXX</td>
                                            <td>a</td>
                                            <td>b</td>
                                            <td>2000</td>
                                            <td>payment received vide check 5649 in SBI XXXX</td>
                                            <td><b>Gourav</b></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <form class="form-inline">
                                    <div class="col-xs-12 col-sm-6 col-md-5 text-right mt10">
                                        Generate Invoices <label class="radio-inline">
                                            <input type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1">this week
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1">&nbsp;
                                        </label>
                                    </div>
                                    <div class="col-xs-12 col-sm-9 col-md-5 mb10">
                                        <div class="row">
                                            <div class="col-sm-6 col-md-6 mb10 p5">
                                                <div class="input-group full-width">
                                                    <input type="text" placeholder="From Date" class="form-control border-none border-radius m0">
                                                    <span class="input-group-addon border-radius"><i class="fa  fa-calendar"></i></span>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-6 col-md-6 p5">
                                                <div class="input-group full-width">
                                                    <input type="text" placeholder="To Date" class="form-control border-none border-radius m0">
                                                    <span class="input-group-addon border-radius"><i class="fa  fa-calendar"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-3 col-md-2 mb10 p5">
                                        <div class="input-group col-xs-12">
                                            <button class="btn btn-success" type="submit">Generate PDF</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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

    $("#VendorTransactions_ven_ptp_id").click(function () {
        var ptpValue = $("#VendorTransactions_ven_ptp_id").val();
        checkPaymentType(ptpValue);
    });

    $("#VendorTransactions_ven_trans_type").click(function () {
        alert("SUDIPTA ROY");
    });

    function checkPaymentType(ptpVal) {
        if (ptpVal == 2)
        {

        } else
        {

        }
    }

    function checkTransactionType() {


    }

</script>