<?php
$agtId				 = $_GET['agtId'];
$walletList			 = Yii::app()->createUrl('admpnl/agent/wallet', array("agtId" => $agtId));
$partnerId			 = 'ID: ' . ($agentModel->agt_type == 1) ? $agentModel->agt_referral_code : $agentModel->agt_agent_id . '-' . $agentModel->getAgentType($agentModel->agt_type);
$this->pageTitle	 = "Gozo Accounts Ledger for " . $agentModel->agt_company . ' ' . $partnerId;
?>
<div class="col-xs-12">
    <div class="panel">
        <div class="panel-body">
            <div class="col-xs-3"><b>Partner ID :</b> <?= ($agentModel->agt_type == 1) ? $agentModel->agt_referral_code : $agentModel->agt_agent_id ?>-<?= $agentModel->getAgentType($agentModel->agt_type); ?></div>
            <div class="col-xs-3"><b>Name : </b><?= $agentModel->agt_fname . " " . $agentModel->agt_lname ?></div>
            <div class="col-xs-3"><b>Company : </b><?= $agentModel->agt_company ?></div>
            <div class="col-xs-3"><b>Owner : </b><?= $agentModel->agt_owner_name ?></div>
        </div>
    </div>
</div>

<div class="col-xs-6">
	<?php
	$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
		'id'					 => 'addamount-form', 'enableClientValidation' => true,
		'clientOptions'			 => array(
			'validateOnSubmit'	 => true,
			'errorCssClass'		 => 'has-error',
		),
		'enableAjaxValidation'	 => false,
		'errorMessageCssClass'	 => 'help-block',
		'htmlOptions'			 => array(
			'class' => 'form-horizontal'
		),
	));
	/* @var $form TbActiveForm */
	?>

    <div class="panel panel-default">
        <div class="panel panel-heading">Add transaction <span style="color: red; font-size: 50; margin-left: 100px"><?php echo $errormsg; ?></span></div>
        <div class="panel-body">
            <div id="row">
                <div class="col-sm-8">
					<?= $form->textAreaGroup($model, 'apg_remarks', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'add notes here on payment received, payment sent or any communication with agent (promise of payment, phone call or complaints). A manual entry will not appear on invoice unless an amount is entered in the manual entry.(+=credit to gozo, -=debit to gozo)', 'class' => 'form-control', 'title' => 'add notes here on payment received, payment sent or any communication with agent (promise of payment, phone call or complaints). A manual entry will not appear on invoice unless an amount is entered in the manual entry.(+=credit to gozo, -=debit to gozo)', 'style' => 'min-height:100px')))) ?>
                </div>
                <div class="col-sm-4 pl40">
					<?= $form->textFieldGroup($model, 'apg_amount', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('style' => 'width:150px', 'placeholder' => 'Enter Amount', 'class' => 'form-control')))) ?>
					<?php
					$arrOperatorType	 = PaymentGateway::model()->getOperatorListPartner();
					$operatorJson		 = VehicleTypes::model()->getJSON($arrOperatorType);
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $model,
						'attribute'		 => 'operator_id',
						'val'			 => $model->operator_id,
						'asDropDownList' => FALSE,
						'options'		 => array('data' => new CJavaScriptExpression($operatorJson)),
						'htmlOptions'	 => array('style' => 'width:110%', 'placeholder' => 'Operator Type')
					));
					?>
					<?= $form->error($model, 'operator_id') ?>
                </div>
            </div>
            <div id="row">
                <div class="row col-sm-12">
                    <div class="col-sm-4">
						<?php
						$restrictionMaxdate	 = Config::get("accounts.restriction.maxdatetime");
						$date				 = date('Y-m-d', strtotime('+1 day', strtotime($restrictionMaxdate)));
						$minDate			 = date("d/m/Y", strtotime($date));
						?>
						<?=
						$form->datePickerGroup($model, 'apg_date', array('label'			 => '',
							'widgetOptions'	 => array('options'		 => array('autoclose'	 => true, 'startDate'	 => $minDate,
									'format'	 => 'dd/mm/yyyy'), 'htmlOptions'	 => array('placeholder'	 => 'Transaction Date',
									'class'			 => 'input-group border-gray full-width')),
							'prepend'		 => '<i class="fa fa-calendar"></i>'));
						?>
						<?= $form->error($model, 'apg_date') ?>
                    </div>
                    <div class="col-sm-4">
						<?php
						$paymenttypearr		 = AccountLedger::getGozoPiadLedgerIds();
						$this->widget('booster.widgets.TbSelect2', array(
							'model'			 => $model,
							'attribute'		 => 'apg_ledger_id',
							'val'			 => $model->apg_ledger_id,
							'asDropDownList' => FALSE,
							'options'		 => array('data' => new CJavaScriptExpression($paymenttypearr)),
							'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Transaction Type', 'id' => 'PaymentGateway_apg_ledger_id')
						));
						?><?= $form->error($model, 'apg_ledger_id'); ?>
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
							$bankTransType		 = VehicleTypes::model()->getJSON(AccountTransDetails::model()->getbankTransTypeList());
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'apg_banktrans_type',
								'val'			 => $model->apg_banktrans_type,
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($bankTransType)),
								'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Bank Transaction Type', 'id' => 'PaymentGateway_apg_banktrans_type')
							));
							?>
							<?= $form->error($model, 'agt_trans_type'); ?>
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
							?>
							<?php echo $form->error($model, 'bank_chq_dated'); ?>
                        </div>
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
						<?= CHtml::submitButton('Save Manual Entry', array('class' => 'btn btn-success mt5', 'name' => 'addnewtransaction')); ?>
                    </div>
                    <div class="col-sm-4">&nbsp;</div>
                </div>
            </div>
        </div>
    </div>
	<?php $this->endWidget(); ?>
</div>
<?php
$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
	'id'					 => 'addamount-form', 'enableClientValidation' => true,
	'clientOptions'			 => array(
		'validateOnSubmit'	 => true,
		'errorCssClass'		 => 'has-error',
	),
	'enableAjaxValidation'	 => false,
	'errorMessageCssClass'	 => 'help-block',
	'htmlOptions'			 => array(
		'class' => 'form-horizontal'
	),
		));
/* @var $form TbActiveForm */
?>
<div class="col-xs-6">
    <div class="panel panel-default">
        <div class="panel panel-heading">Account Summary</div>
        <div class="panel panel-body">
            <div class="col-sm-12">
                <table class="table table-striped table-bordered">
                    <tr class="text-center">
						<td style="font-size: 14px;"><b>Wallet Balance</b><br><span style="font-size: 10px; font-weight: 800">(+=credit to Partner<br>-=debit to Partner)</span></td>
                        <td style="font-size: 14px;"><b>Ledger Balance</b><br><span style="font-size: 10px; font-weight: 800">(+=credit to Gozo<br>-=debit to Gozo)</span></td>
                        <td style="font-size: 14px;"><b>Accounts Balance</b><br><span style="font-size: 10px; font-weight: 800">(+=credit / receivable to Gozo<br>-=debit / payable by Gozo)</span></td>
                        <td style="font-size: 14px;"><b>Security Deposit</b></td>
                    </tr>

                    <tr  class="text-center">
                        <td><i class="fa fa-inr"></i>
							<?php echo $getBalance['pts_wallet_balance']; ?>
						</td>		    
                        <td><i class="fa fa-inr"></i>
							<?php
							echo trim($getBalance['pts_ledger_balance']);
							?>
						</td>
                        <td><i class="fa fa-inr"></i>
							<?php
							echo trim(($getBalance['pts_ledger_balance']) - ($getBalance['pts_wallet_balance']));
							?>
						</td>
                        <td><i class="fa fa-inr"></i>
							<?= ($agentAmount['securitydepo'] > 0) ? round($agentAmount['securitydepo']) : 0; ?>
                        </td>
                    </tr>
					<tr><td colspan="4">Credit Limit :&nbsp; <i class="fa fa-inr"></i><?php echo $agentModel->agt_credit_limit; ?></td></tr>
					<tr><td colspan="4">Effective Credit Limit :&nbsp; <i class="fa fa-inr"></i><?php echo $agentModel->agt_effective_credit_limit; ?></td></tr>
					<tr><td colspan="4">Overdue days :&nbsp; <?php echo $agentModel->agt_overdue_days; ?></td></tr>       
				</table>
            </div>
            <div class="col-xs-12 pb20">
                <div class="col-xs-6">
					<?php
					$daterang			 = "Select Transaction Date Range";
					$createdate1		 = ($model->trans_create_date1 == '') ? '' : $model->trans_create_date1;
					$createdate2		 = ($model->trans_create_date2 == '') ? '' : $model->trans_create_date2;
					if ($createdate1 != '' && $createdate2 != '')
					{
						$daterang = date('F d, Y', strtotime($createdate1)) . " - " . date('F d, Y', strtotime($createdate2));
					}
					?>
                    <label  class="control-label pb10">Transaction Date</label>
                    <div id="bkgCreateDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                        <span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
                    </div>
					<?php
					echo $form->hiddenField($model, 'trans_create_date1');
					echo $form->hiddenField($model, 'trans_create_date2');
					?>
                </div>
				<div class="col-xs-6 mt30">
					<?php
					$paymenttypearr		 = AccountLedger::getAllLedgerIds();
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $model,
						'attribute'		 => 'apg_ledger_type_id',
						'val'			 => $model->apg_ledger_id,
						'asDropDownList' => FALSE,
						'options'		 => array('data' => new CJavaScriptExpression($paymenttypearr), 'multiple' => true),
						'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Transaction Type', 'id' => 'PaymentGateway_apg_ledger_type_id')
					));
					?><?= $form->error($model, 'apg_ledger_id'); ?>
				</div>
                <div class="col-xs-6 mt30">
                    <label  class="control-label"></label>
                    <button class="btn btn-primary mt5" type="submit" style="width: 185px; padding: 7px 10px;"  name="bookingSearch">Search</button>
                </div>
				<div class="row">
					<div class="col-sm-6"><a href="<?= $walletList; ?>" target="_blank">View Wallet</a></div>
				</div>
            </div>
        </div>
    </div>

</div>
<div class="col-xs-12">
    <div class="panel panel-default">
        <div class="panel panel-body">
            <div class="col-sm-12">
                <table class="table table-bordered">
					<?php
					$ctr				 = 0;
					$countTransaction	 = count($agentmodels);
					?>
                    <tr class="bg-gray"><td colspan="10"> <?php
							$this->widget('CLinkPager', array('pages' => $agentList->pagination));
							?><span class="pull-right">Showing results <?= $countTransaction ?> out of <?= $totalRecords ?></span></td></tr>
                    <tr class="blue2 white-color">
                        <td align="center"><b>Transaction Date</b></td>
                        <td align="center"><b>Booking ID</b></td>
                        <td align="center"><b>Pickup Date</b></td>
						<td align="center"><b>Create Date</b></td>
                        <td align="center"><b>Booking Info</b></td>
						<td align="center"><b>Entity Type</b></td>
			<!--                        <td align="center"><b>Advanced Collected</b></td>-->
                        <td class="text-center" style="width: 140px;"><b>amount (<i class="fa fa-inr"></i>)</b><br>(+=credit to gozo,<br>-=credit to agent)</td>
                        <td align="center"><b>Notes</b></td>
                        <td align="center"><b>Who</b></td>
                        <td align="center" style="width: 190px;"><b>Running Balance (<i class="fa fa-inr"></i>)</b><br>(+=credit / receivable to gozo<br>-=debit / payable by gozo)</b></td>
                    </tr>
					<?php
//                    array(13) (
//  [act_date] => (string) 2018-04-04 00:08:33
//  [act_id] => (string) 161320
//  [adt_id] => (string) 322639
//  [adt_ledger_id] => (string) 15
//  [act_remarks] => (string) Partner credits Rs.-3439 reverted on booking cancelled.
//  [agt_company] => (string) MakeMy Trip India Pvt. Ltd.
//  [ledgerIds] => (string) 26
//  [ledgerNames] => (string) Partner Coins: NC270362684284689 (OW180282785)
//  [adt_amount] => (string) -3439.00
//  [bkg_pickup_date] => (string) 2018-04-07 07:00:00
//  [bkg_advance_amount] => (string) 3439.00
//  [openBalance] => (string) 3348459.00
//  [runningBalance] => (string) 3345020
//)
					if (count($agentmodels) > 0)
					{
						foreach ($agentmodels as $agent)
						{
							?>
							<tr>
								<td><?php echo date('d/m/Y', strtotime($agent['act_date'])); ?></td>
								<td><?= $agent['ledgerNames'] ?></td>
								<td><?= $agent['bkg_pickup_date'] ?></td>
								<td><?php echo date('d/m/Y', strtotime($agent['act_created'])); ?></td>
								<td><?php
									if ($agent['bookingInfo'] == "NA")
									{
										echo AccountLedger::model()->findByPk($agent['adt_ledger_id'])->ledgerName;
									}
									else
									{
										$bookingId = ($agent['bookingInfo'] == NULL) ? 'NA' : $agent['bookingInfo'];
										echo trim($bookingId);
									}
									?></td>
								<td><?php
									if ($agent['atd1LedgerId'] == 49)
									{
										echo (round($agent['adt_amount']) > 0) ? "Added" : "Deducted";
										echo ($agent['entityType'] == NULL) ? ' ' : '- ' . $agent['entityType'];
									}
									else
									{
										echo (round($agent['adt_amount']) > 0) ? "Gozo Receiver" : "Gozo Paid";
										echo ($agent['entityType'] == NULL) ? ' ' : '- ' . $agent['entityType'];
									}
									?></td>
					<!--                                <td><?php
//                                    $agent_adv_amt =($agent['bkg_advance_amount']-$agent['bkg_refund_amount']);
//
//                                    echo number_format($agent_adv_amt);
								?></td>-->
								<td class="text-right"><?= number_format(round($agent['adt_amount'])); ?></td>
								<td><?= $agent['agt_trans_remarks'] ?></td>
								<td><b><?php
										$adname = ($agent['adminName'] == NULL) ? 'NA' : $agent['adminName'];
										echo trim($adname);
										?></b></td>
								<td align="right"><?= number_format($agent['runningBalance']); ?></td>
							</tr>
							<?php
						}
					}
					else
					{
						?>
						<tr><td colspan="10">No Records  Found.</td></tr>
					<?php }
					?>
                </table>
				<?php
				$this->widget('CLinkPager', array('pages' => $agentList->pagination));
				?>
            </div>

        </div>
    </div>
</div>
<?php $this->endWidget(); ?>

<?php
$model->ven_from_date	 = '01/06/2016';
$model->ven_to_date		 = date('d/m/Y');
$model->ven_date_type	 = 2;
$checkExportAccess		 = Yii::app()->user->checkAccess("Export");
$form					 = $this->beginWidget('booster.widgets.TbActiveForm', array(
	'id'					 => 'generate-vendor-form', 'enableClientValidation' => true,
	'action'				 => '/admpnl/agent/ledgerpdf',
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
<?= $form->hiddenField($model, "apg_trans_ref_id"); ?>
<?php
$this->endWidget();
?>


<script>
	$(document).ready(function () {
		var ptpValue = $("#PaymentGateway_apg_ledger_id").val();
		checkPaymentType(ptpValue);
		var transValue = $("#PaymentGateway_apg_banktrans_type").val();
		checkTransactionType(transValue);
		var start = '<?= $model->trans_create_date1 != null ? date('d/m/Y', strtotime($model->trans_create_date1)) : date('d/m/Y', strtotime('-1 month')); ?>';
		var end = '<?= $model->trans_create_date2 != null ? date('d/m/Y', strtotime($model->trans_create_date2)) : date('d/m/Y'); ?>';

		$('#bkgCreateDate').daterangepicker(
				{
					locale: {
						format: 'DD/MM/YYYY',
						cancelLabel: 'Clear'
					},
					"showDropdowns": true,
					"alwaysShowCalendars": true,
					startDate: start,
					endDate: end,
					ranges: {
						'Today': [moment(), moment()],
						'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
						'Last 7 Days': [moment().subtract(6, 'days'), moment()],
						'Last 30 Days': [moment().subtract(29, 'days'), moment()],
						'This Month': [moment().startOf('month'), moment().endOf('month')],
						'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
					}
				}, function (start1, end1) {
			$('#PaymentGateway_trans_create_date1').val(start1.format('YYYY-MM-DD'));
			$('#PaymentGateway_trans_create_date2').val(end1.format('YYYY-MM-DD'));
			$('#bkgCreateDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
		});
		$('#bkgCreateDate').on('cancel.daterangepicker', function (ev, picker) {
			$('#bkgCreateDate span').html('Select Transaction Date Range');
			$('#PaymentGateway_trans_create_date1').val('');
			$('#PaymentGateway_trans_create_date2').val('');
		});

	});
	$("#PaymentGateway_apg_ledger_id").click(function () {
		var ptpValue = $("#PaymentGateway_apg_ledger_id").val();
		checkPaymentType(ptpValue);
	});
	$("#PaymentGateway_apg_banktrans_type").click(function () {
		var transValue = $("#PaymentGateway_apg_banktrans_type").val();
		checkTransactionType(transValue);
	});

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
			$("#PaymentGateway_apg_date").attr("placeholder", "Settlment Date");
		} else
		{
			$("#PaymentGateway_apg_date").attr("placeholder", "Transaction Date");
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

	$("#PaymentGateway_ven_date_type_0").click(function () {
		var dateVal = $("#PaymentGateway_ven_date_type_0").val();

<?php
$dateFromDate			 = DateTimeFormat::DateToLocale($dateFromDate);
$dateTodate				 = DateTimeFormat::DateToLocale($dateTodate);
?>
		$("#PaymentGateway_ven_from_date").val('<?= DateTimeFormat::DateToLocale($dateFromDate) ?>');
		$("#PaymentGateway_ven_to_date").val('<?= DateTimeFormat::DateToLocale($dateTodate) ?>');
	});

	$("#PaymentGateway_apg_date_type_1").click(function () {
		var dateVal = $("#PaymentGateway_apg_date_type_1").val();
		$("#PaymentGateway_ven_from_date").val('');
		$("#PaymentGateway_ven_to_date").val('');
	});

</script>