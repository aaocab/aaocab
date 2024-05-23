
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
	$form			 = $this->beginWidget('booster.widgets.TbActiveForm', array(
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
        <div class="panel panel-heading">Add transaction</div>
        <div class="panel-body">
            <div id="row">
                <div class="col-sm-8">
					<?= $form->textAreaGroup($model, 'agt_trans_remarks', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'add notes here on payment received, payment sent or any communication with agent (promise of payment, phone call or complaints). A manual entry will not appear on invoice unless an amount is entered in the manual entry.(+=credit to gozo, -=debit to gozo)', 'class' => 'form-control', 'title' => 'add notes here on payment received, payment sent or any communication with agent (promise of payment, phone call or complaints). A manual entry will not appear on invoice unless an amount is entered in the manual entry.(+=credit to gozo, -=debit to gozo)', 'style' => 'min-height:100px')))) ?>
                </div>
                <div class="col-sm-4 pl40">
					<?= $form->textFieldGroup($model, 'agt_trans_amount', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('style' => 'width:150px', 'placeholder' => 'Enter Amount', 'class' => 'form-control')))) ?>
					<?php
					$arrOperatorType = AgentTransactions::model()->getOperatorList();
					$operatorJson	 = VehicleTypes::model()->getJSON($arrOperatorType);
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
						<?=
						$form->datePickerGroup($model, 'agt_trans_date', array('label'			 => '',
							'widgetOptions'	 => array('options'		 => array('autoclose'	 => true, 'startDate'	 => date(),
									'format'	 => 'dd/mm/yyyy'), 'htmlOptions'	 => array('placeholder'	 => 'Transaction Date',
									'class'			 => 'input-group border-gray full-width')),
							'prepend'		 => '<i class="fa fa-calendar"></i>'));
						?>
						<?= $form->error($model, 'agt_trans_date') ?>
                    </div>
                    <div class="col-sm-4">
						<?php
						$paymenttypearr	 = PaymentType::model()->getList(false, true);
						unset($paymenttypearr[PaymentType::TYPE_SETTLE]);
						$ptpJson		 = VehicleTypes::model()->getJSON($paymenttypearr);
						$this->widget('booster.widgets.TbSelect2', array(
							'model'			 => $model,
							'attribute'		 => 'agt_ptp_id',
							'val'			 => $model->agt_ptp_id,
							'asDropDownList' => FALSE,
							'options'		 => array('data' => new CJavaScriptExpression($ptpJson)),
							'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Payment Type', 'id' => 'AgentTransactions_agt_ptp_id')
						));
						?><?= $form->error($model, 'agt_ptp_id'); ?>
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
							$bankTransType	 = VehicleTypes::model()->getJSON(AccountTransDetails::model()->getbankTransTypeList());
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'agt_trans_type',
								'val'			 => $model->agt_trans_type,
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($bankTransType)),
								'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Bank Transaction Type', 'id' => 'AgentTransactions_agt_trans_type')
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
$form			 = $this->beginWidget('booster.widgets.TbActiveForm', array(
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
        <div class="panel panel-heading">Transaction List</div>
        <div class="panel panel-body">
            <div class="col-sm-12">
                <table class="table table-striped table-bordered">
                    <tr class="text-center">
                        <td><b>Accounts Payable</b></td>
                        <td><b>Accounts Receivable</b></td>
                        <td><b>Security Deposit</b></td>
                    </tr>
                    <tr  class="text-center">
                        <td><i class="fa fa-inr"></i><?php
							if ($agentAmount['totAmount'] < 0)
							{
								echo trim(-1 * $agentAmount['totAmount']);
							}
							else
							{
								echo '0';
							}
							?></td>
                        <td><i class="fa fa-inr"></i><?php
							if ($agentAmount['totAmount'] > 0)
							{
								echo trim($agentAmount['totAmount']);
							}
							else
							{
								echo '0';
							}
							?></td>
                        <td><i class="fa fa-inr"></i>
							<?= ($agentAmount['securitydepo'] > 0) ? round($agentAmount['securitydepo']) : 0; ?>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-xs-12 pb20">
                <div class="col-xs-6">
					<?
					$daterang	 = "Select Transaction Date Range";
					$createdate1 = ($model->trans_create_date1 == '') ? '' : $model->trans_create_date1;
					$createdate2 = ($model->trans_create_date2 == '') ? '' : $model->trans_create_date2;
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
					<?
					echo $form->hiddenField($model, 'trans_create_date1');
					echo $form->hiddenField($model, 'trans_create_date2');
					?>
                </div>
                <div class="col-xs-6 mt30">  
                    <label  class="control-label"></label>
                    <button class="btn btn-primary mt5" type="submit" style="width: 185px; padding: 7px 10px;"  name="bookingSearch">Search</button>
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
					<?
					$ctr				 = 0;
					$countTransaction	 = count($agentmodels);
					?>
                    <tr class="bg-gray"><td colspan="9"> <?
							$this->widget('CLinkPager', array('pages' => $agentList->pagination));
							?><span class="pull-right">Showing results <?= $countTransaction ?> out of <?= $totalRecords ?></span></td></tr>
                    <tr class="blue2 white-color">
                        <td align="center"><b>Transaction Date</b></td>
                        <td align="center"><b>Booking ID</b></td>
                        <td align="center"><b>Pickup Date</b></td>
                        <td align="center"><b>Booking Info</b></td>
<!--                        <td align="center"><b>Advanced Collected</b></td>-->
                        <td class="text-center"><b>amount (<i class="fa fa-inr"></i>)</b><br>(+=credit to gozo,<br>-=credit to agent)</td>
                        <td align="center"><b>Notes</b></td>
                        <td align="center"><b>Who</b></td>
                        <td align="center"><b>Running Balance</b></td>
                    </tr>
					<?php
					if (count($agentmodels) > 0)
					{
						foreach ($agentmodels as $agent)
						{
							?>
							<tr>
								<td><?php echo date('d/m/Y', strtotime($agent['transDate'])); ?></td>
								<td><?= $agent['bookingId'] ?></td>
								<td><?= $agent['pickupDate'] ?></td>
								<td><?
									if ($agent['bookingInfo'] == "NA")
									{
										echo $agent["agt_trans_code"] . " (" . PaymentType::model()->getList()[$agent['agt_ptp_id']] . ")";
									}
									else
									{
										echo $agent['bookingInfo'];
									}
									?></td>
		<!--                                <td><? //= number_format($agent['advanceAmount']);  ?></td>-->
								<td class="text-right"><?= number_format(round($agent['transAmount'])); ?></td>
								<td><?= $agent['transRemarks'] ?></td>
								<td><b><?php echo trim($agent['adminName']); ?></b></td>
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
				<?
				$this->widget('CLinkPager', array('pages' => $agentList->pagination));
				?>
            </div>

        </div>
    </div>
</div>
<?php $this->endWidget(); ?>



<script>
    $(document).ready(function () {
        var ptpValue = $("#AgentTransactions_agt_ptp_id").val();
        checkPaymentType(ptpValue);
        var transValue = $("#AgentTransactions_agt_trans_type").val();
        checkTransactionType(transValue);

        var start = '<?= date('d/m/Y', strtotime('-1 month')); ?>';
        var end = '<?= date('d/m/Y'); ?>';


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
            $('#AgentTransactions_trans_create_date1').val(start1.format('YYYY-MM-DD'));
            $('#AgentTransactions_trans_create_date2').val(end1.format('YYYY-MM-DD'));
            $('#bkgCreateDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
        });
        $('#bkgCreateDate').on('cancel.daterangepicker', function (ev, picker) {
            $('#bkgCreateDate span').html('Select Transaction Date Range');
            $('#AgentTransactions_trans_create_date1').val('');
            $('#AgentTransactions_trans_create_date2').val('');
        });

    });
    $("#AgentTransactions_agt_ptp_id").click(function () {
        var ptpValue = $("#AgentTransactions_agt_ptp_id").val();
        checkPaymentType(ptpValue);
    });
    $("#AgentTransactions_agt_trans_type").click(function () {
        var transValue = $("#AgentTransactions_agt_trans_type").val();
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
            $("#AgentTransactions_agt_trans_date").attr("placeholder", "Settlment Date");
        } else
        {
            $("#AgentTransactions_agt_trans_date").attr("placeholder", "Transaction Date");
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

</script>