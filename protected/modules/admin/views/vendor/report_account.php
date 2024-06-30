<style>
    span.stars, span.stars span {
        display: block;
        background: url(http://www.aaocab.com/images/stars.png) 0 -16px repeat-x;
        width: 80px;
        height: 16px;
    }

    span.stars span {
        background-position: 0 0;
    }
</style>

<?php
if ($errors)
{
	?>

	<!--    <div class="container">-->
	<div class="row p20 alert alert-danger h4 text-center">

		<div class="col-xs-12">
			<p class="dup-error">Errors occured</p>
		</div>
		<div class="col-xs-12 ">
			<?= $errors ?>

		</div>
	</div>

	<?php
	goto skipAll;
}
$ptpJson		 = VehicleTypes::model()->getJSON(PaymentType::model()->getList(false, false));
$modeJson		 = VehicleTypes::model()->getJSON(PaymentGateway::model()->getModeList());
$bankTransType	 = VehicleTypes::model()->getJSON(PaymentGateway::model()->getbankTransTypeList());
$operatorJson	 = VehicleTypes::model()->getJSON(AccountTransDetails::model()->getOperatorList());
$gozoPaid		 = AccountLedger::getGozoPiadLedgerIds();
$gozoReceiver	 = AccountLedger::getGozoReceiverLedgerIds();

//$vndid				 = $_GET['vnd_id'];
$vndid = $agtId;

$viewLockedAmount	 = Yii::app()->createUrl('aaohome/vendor/Getlockamount', array("vnd_id" => $vndid));
$viewMetrics		 = Yii::app()->createUrl('aaohome/vendor/ViewMetrics', array("vnd_id" => $vndid));
?>

<section id="section7">
	<!--    <div class="container">-->
	<div class="profile-right-panel p20">
		<div class="row">
			<div class="col-xs-12 col-sm-5 table-responsive">
				<table class="table table-striped table-bordered">
					<tr>
						<td><b>Vendor</b></td>
						<td><?= $record['vnd_name'] ?></td>
					</tr>
					<?php
					$vndUserType		 = ($record['ctt_user_type'] == '1') ? "Owner" : "Company";
					$vndOwner			 = ($record['ctt_user_type'] == '1') ? $record['ctt_first_name'] . ' ' . $record['ctt_last_name'] : $record['ctt_business_name'];
					?>
					<tr>
						<td><b><?= $vndUserType ?></b></td>
						<td><?= ($vndOwner == '') ? 'Not Available' : $vndOwner ?></td>
					</tr>

					<tr>
						<td><b><?= $vndUserType ?> phone no.</b></td>
						<td><?= $record['phn_phone_no'] ?></td>
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
						<td><?= $record['ctt_beneficiary_id'] ?></td>
					</tr>
				</table>
				<h4 class="mb5">Permanent notes</h4>
				<p><?= $record['vnp_notes'] ?></p>
			</div>
			<div class="col-xs-12 col-sm-7 table-responsive">
				<table class="table table-striped table-bordered">
					<? $overall_rating		 = ($record['vrs_vnd_overall_rating'] == '') ? 'Not Available' : $record['vrs_vnd_overall_rating'] ?>
					<? $overall_star_rating = ($record['vrs_vnd_overall_rating'] == '') ? 0 : $record['vrs_vnd_overall_rating'] ?>
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

					<?php $zones				 = str_replace(",", ", ", $record['vnd_zones']); ?>
					<?php $zones				 = str_replace("Z-", "", $zones); ?>
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
							<div class="col-sm-2"><?= ($record['vrs_total_trips'] == '') ? 0 : $record['vrs_total_trips'] ?><br>(lifetime)</div>
						</td>
					</tr>
				</table>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-5">Vendor Credit limit: <input type="text" style="width: 25%;" value="<?= $record['vrs_credit_limit'] ?>" readonly="readonly"> (recommended amount:2222)</div>
			<div class="col-sm-3">Credit throttle level: <b>75%</b></div>
			<div class="col-sm-2"><a href="<?= $viewLockedAmount; ?>" target="_blank">View Locked Amount</a></div>
			<div class="col-sm-2"><a href="<?= $viewMetrics; ?>" target="_blank">View Vendor Metrics</a></div>
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
							<td><b>Accounts Balance</b></td>
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
							<td><b>Withdrawable Balance</b></td>
							<td><i class="fa fa-inr"></i>
								<?php
								echo $vendorAmount['withdrawable_balance'];
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
					<a href="<?php echo Yii::app()->createUrl('aaohome/vendor/refreshVendorAccount', array("vnd_id" => $vndid)); ?>" class = "btn btn-info btn-xm text-center mr50" style="margin-left: 55px">Refresh</a>
				</div>
				<div class="col-sm-9">
					<div class="panel panel-default">
						<div class="panel-body">

							<?= $form->radioButtonListGroup($model, 'apg_type', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Gozo Paid', 2 => 'Gozo receiver')), 'inline' => true)) ?>

							<?php
							if ($model->apg_type == 1)
							{
								$gozoPaidTextStyle = 'display:block;';
							}
							else if ($model->apg_type == 2)
							{
								$gozoReceiverTextStyle = 'display:block;';
							}
							else
							{
								$gozoPaidTextStyle		 = 'display:none;';
								$gozoReceiverTextStyle	 = 'display:none;';
							}
							?>
							<div class="row" >
								<div class="col-sm-4  " id="gozoPaid" style="<?= $gozoPaidTextStyle; ?>;">
									<?php
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $model,
										'attribute'		 => 'apg_ledger_id_1',
										'val'			 => $model->apg_ledger_id_1,
										'asDropDownList' => FALSE,
										'options'		 => array('data' => new CJavaScriptExpression($gozoPaid)),
										'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Operator Type')
									));
									?>
									</br></br>
								</div>

								<div class="col-sm-4 " id="gozoReceiver" style="<?= $gozoReceiverTextStyle; ?>;">
									<?php
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $model,
										'attribute'		 => 'apg_ledger_id_2',
										'val'			 => $model->apg_ledger_id_2,
										'asDropDownList' => FALSE,
										'options'		 => array('data' => new CJavaScriptExpression($gozoReceiver)),
										'htmlOptions'	 => array('onchange' => "showgozotrip(this)", 'style' => 'width:100%', 'placeholder' => 'Operator Type')
									));
									?>
									<?php
									$gozoTripTextStyle	 = 'display:none;';
									?>     
									</br></br>

									<div id="row" >
										<div class="col-sm-12">
											<div id="gozoTripid" style="display:none;">
												<?= $form->textFieldGroup($model, 'trip_id', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Trip ID / (Booking ID When Removed OTP Penalty)', 'class' => 'form-control', 'title' => '', 'style' => 'min-height:23px', 'style' => 'min-width:385px')))) ?>
											</div>
										</div>


									</div> 
								</div>
								<div class="col-sm-4">&nbsp;</div>
							</div>


							<div id="row">
								<div class="col-sm-8">
									<?= $form->textAreaGroup($model, 'apg_remarks', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'add notes here on payment received, payment sent or any communication with vendor (promise of payment, phone call or complaints). A manual entry will not appear on invoice unless an amount is entered in the manual entry.(+=credit to gozo, -=debit to gozo)', 'class' => 'form-control', 'title' => 'add notes here on payment received, payment sent or any communication with vendor (promise of payment, phone call or complaints). A manual entry will not appear on invoice unless an amount is entered in the manual entry.(+=credit to gozo, -=debit to gozo)', 'style' => 'min-height:83px')))) ?>
								</div>
								<div class="col-sm-4 pl50">
									<?= $form->textFieldGroup($model, 'apg_amount', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('style' => 'width:220px', 'placeholder' => 'Enter Amount', 'class' => 'form-control')))) ?>

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
										<?= CHtml::submitButton('Save Manual Entry', array('class' => 'btn btn-success mt5', 'id' => 'subBtn1')); ?>
									</div>
									<div class="col-sm-4">&nbsp;</div>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>
			<?php $this->endWidget(); ?>

			<?php
			$checkExportAccess	 = Yii::app()->user->checkAccess("Export");
			$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
				'id'					 => 'generate-vendor-form', 'enableClientValidation' => true,
				'action'				 => '/aaohome/vendor/vendoraccount?vnd_id=' . $vndid,
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
            <div class="row">
                <div class="col-xs-12 col-sm-4 col-md-3 text-left mt10">

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
				<div class="col-xs-12 col-sm-3 col-md-2 p5">
					<?php
					$paymenttypearr		 = AccountLedger::getLedgerIds();
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
                <div class="col-xs-12 col-sm-3 col-md-2 mb10 p5">
                    <div class="input-group col-xs-12">
						<?php echo CHtml::submitButton('View Accounts', array('class' => 'btn btn-success', 'placeholder' => 'View Accounts')); ?>

					</div>
                </div>
            </div>
			<?= $form->hiddenField($model, "apg_trans_ref_id"); ?>
			<?php
			$this->endWidget();
			?>

			<div class="row">
				<?php
				$vndIds = Vendors::getRelatedIds($agtId);
				$openingBalance		 = AccountTransDetails::getOpeningBalance($vndIds, $dateFromDate);
				if ($openingBalance != 0)
				{
					$date = date_create($dateFromDate);
					?>
					<h2 align="center" class = "mb5">Opening Balance at <?php echo date_format($date, "dS M ,Y") ?>	( Rs: <?php echo $openingBalance ?>)</h2>
				<?php } ?>

				<div class="panel panel-default">
					<div class="panel-body">
						<div class="col-xs-12 table-responsive">
							<?php
							if (!empty($vendorModels))
							{
								$params									 = array_filter($_REQUEST);
								$vendorModels->getPagination()->params	 = $params;
								$vendorModels->getSort()->params		 = $params;
								$this->widget('booster.widgets.TbGridView', array(
									'responsiveTable'	 => true,
									'id'				 => 'reportlist',
									'dataProvider'		 => $vendorModels,
									'template'			 => "<div class='panel-heading'><div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
									'itemsCssClass'		 => 'table table-striped table-bordered mb0',
									'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
									'columns'			 => array(
										array('name'	 => 'bkg_pickup_date', 'value'	 => function ($data) {
												echo ($data['bkg_pickup_date'] == NULL) ? 'NA' : date('d/m/Y', strtotime($data['bkg_pickup_date']));
											}, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Pickup Date'),
										array('name'	 => 'ledgerNames', 'value'	 => function ($data) {
												echo ($data['ledgerNames'] == NULL) ? 'NA' : $data['ledgerNames'];
											}, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Ledger Name'),
										array('name'	 => 'bkg_advance_amount', 'value'	 => function ($data) {
												echo ($data['bkg_advance_amount'] == NULL ) ? 'NA' : trim($data['bkg_advance_amount']);
											}, 'sortable'								 => true, 'headerHtmlOptions'						 => array(), 'header'								 => 'Advanced Collected'),
										array('name'	 => 'act_date', 'value'	 => function ($data) {
												echo date('d/m/Y', strtotime($data['act_date']));
											}, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Transaction Date'),
										array('name'	 => 'act_created', 'value'	 => function ($data) {
												echo date('d/m/Y', strtotime($data['act_created']));
											}, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Created Date'),
										array('name'	 => 'booking_info', 'value'	 => function ($data) {
												$fromCity = ($data['from_city'] == NULL) ? 'NA' : trim($data['from_city']);
												echo ($data['ledgerNames'] == NULL) ? 'NA' : $fromCity;
											}, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Booking Info'),
										array('name'	 => 'entityType', 'value'	 => function ($data) {
												echo (round($data['ven_trans_amount']) > 0) ? "Gozo Receiver" : "Gozo Paid";
												echo ($data['entityType'] == NULL) ? ' ' : '- ' . $data['entityType'];
											}, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Entity Type'),
										array('name'	 => 'ven_trans_amount', 'value'	 => function ($data) {
												echo round($data['ven_trans_amount']);
											}, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Amount (<i class="fa fa-inr"></i>)</b><br>(+=credit to gozo,<br>-=credit to vendor)'),
										array('name'	 => 'ven_trans_remarks', 'value'	 => function ($data) {
												echo trim($data['ven_trans_remarks']);
											}, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Notes'),
										array('name'	 => 'adm_name', 'value'	 => function ($data) {
												echo "<b>" . trim($data['adm_name']) . "</b>";
											}, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Who'),
										array('name'	 => 'runningBalance', 'value'	 => function ($data) {
												echo number_format((float) $data['runningBalance'], 2, '.', '');
											}, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => '<b>Running Balance</b>'),
								)));
							}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
		$checkExportAccess	 = Yii::app()->user->checkAccess("Export");
		$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'generate-vendor-form', 'enableClientValidation' => true,
			'action'				 => '/aaohome/vendor/ledgerpdf',
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
		<?= $form->hiddenField($model, "ven_to_date"); ?>
		<?= $form->hiddenField($model, "ven_from_date"); ?>
		<?php
		$this->endWidget();
		?>
		<input type="hidden" id="vnd_123" value="0">
	</div>
    <!--</div>-->
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

	$("#PaymentGateway_apg_ptp_id").click(function () {
		var ptpValue = $("#PaymentGateway_apg_ptp_id").val();
		checkPaymentType(ptpValue);
	});

	$("#PaymentGateway_ven_date_type_0").click(function () {
		var dateVal = $("#PaymentGateway_ven_date_type_0").val();
<?php
$dateFromDate		 = DateTimeFormat::DateToLocale($dateFromDate);
$dateTodate			 = DateTimeFormat::DateToLocale($dateTodate);
?>
		$("#PaymentGateway_ven_from_date").val('<?= DateTimeFormat::DateToLocale($dateFromDate) ?>');
		$("#PaymentGateway_ven_to_date").val('<?= DateTimeFormat::DateToLocale($dateTodate) ?>');
	});

	$("#PaymentGateway_apg_date_type_1").click(function () {
		var dateVal = $("#PaymentGateway_apg_date_type_1").val();
		$("#PaymentGateway_ven_from_date").val('');
		$("#PaymentGateway_ven_to_date").val('');
	});

	$("#PaymentGateway_apg_type_0").click(function () {
		var accVal = $("#PaymentGateway_apg_type_0").val();
		checkGroupType(1);
	});

	$("#PaymentGateway_apg_type_1").click(function () {
		var accVal = $("#PaymentGateway_apg_type_1").val();
		checkGroupType(2);
	});

	$("#PaymentGateway_apg_trans_type").click(function () {
		var transValue = $("#PaymentGateway_apg_trans_type").val();
		checkTransactionType(transValue);
	});

	function checkGroupType(type) {

		var tt = $("#vnd_123").val();
		if (tt == type)
		{
			return false;
		} else {
			$("#vnd_123").val(type);
		}

		if (type == 1) {
			$("#gozoPaid").show();
			$("#gozoReceiver").hide();
			$("#s2id_PaymentGateway_apg_ledger_id_1").select2("val", "");
			$("#s2id_PaymentGateway_apg_ledger_id_2").select2("val", "");
			$("#PaymentGateway_apg_remarks").val("");
			$("#PaymentGateway_apg_amount").val("");
			$("#PaymentGateway_apg_date").val("");

		} else if (type == 2) {
			$("#gozoReceiver").show();
			$("#gozoPaid").hide();
			$("#s2id_PaymentGateway_apg_ledger_id_1").select2("val", "");
			$("#s2id_PaymentGateway_apg_ledger_id_2").select2("val", "");
			$("#PaymentGateway_apg_remarks").val("");
			$("#PaymentGateway_apg_amount").val("");
			$("#PaymentGateway_apg_date").val("");
		}

	}

	function showgozotrip(obj) {
		var ledger = obj.value;
		if (ledger == 28)
		{
			$("#gozoTripid").show();

		} else {
			$("#gozoTripid").hide();
		}
	}

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

	$(document).ready(function () {
		$("#subBtn1").click(function () {
			$(this).css({"opacity": "0.3", "pointer-events": "none"});
			return true;
		});
	});


</script>
<?
skipAll:?>