<style>
    .search-form ul{
        list-style: none ;
        margin-bottom: 20px;
        vertical-align: bottom
    }
    .search-form ul li{
        padding: 0;
    }
    .panel-body {
        border: 1px #eeeeee solid;
        padding: 15px!important;
    }
    .panel-heading {
        padding: 10px 15px!important;
    }
    .pagination {
        margin: 0;
    }
    .table{
        margin-bottom: 5px;
    }
</style>
<?
$paymentType	 = PaymentType::model()->getList();
$var			 = [1 => 'Booking', 2 => 'Vendor'];
$status			 = ['0' => 'Initiated', '1' => 'Success', '2' => 'Failure'];
$ptpJson		 = VehicleTypes::model()->getJSON($paymentType);
$statusJson		 = VehicleTypes::model()->getJSON($status);
$modeJson		 = VehicleTypes::model()->getJSON(PaymentGateway::model()->getModeList());
$for			 = VehicleTypes::model()->getJSON($var);
//json_encode($paymentType);
?>

<div id="content" class="  " style="width: 100%!important">
    <div class="row ">
        <div id="userView">
            <div class="col-xs-12">
                <div class="projects">
                    <div class="panel panel-default">

						<!--///////////////-->
                        <div class="panel-body">
							<?php
							$form			 = $this->beginWidget('booster.widgets.TbActiveForm', array(
								'id'					 => 'transaction-form', 'enableClientValidation' => true,
								'clientOptions'			 => array(
									'validateOnSubmit'	 => true,
									'errorCssClass'		 => 'has-error'
								),
								'enableAjaxValidation'	 => false,
								'errorMessageCssClass'	 => 'help-block',
								'htmlOptions'			 => array(
									'class' => '',
								),
							));
							/* @var $form TbActiveForm */
							?>

                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
								<?= $form->datePickerGroup($model, 'trans_date1', array('label' => 'From Date', 'widgetOptions' => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'From Date', 'value' => $qry['trans_date1'])), 'prepend' => '<i class="fa fa-calendar"></i>')); ?>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
								<?= $form->datePickerGroup($model, 'trans_date2', array('label' => 'To Date', 'widgetOptions' => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'To Date', 'value' => $qry['trans_date2'])), 'prepend' => '<i class="fa fa-calendar"></i>')); ?>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3"> 
								<?= $form->textFieldGroup($model, 'trans_code', array('widgetOptions' => ['htmlOptions' => ['value' => $qry['trans_code']]])) ?>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3"> 
								<?= $form->textFieldGroup($model, 'trans_booking', array('label' => 'Booking ID', 'widgetOptions' => ['htmlOptions' => ['value' => $qry['trans_booking'], 'placeholder' => 'Booking ID']])) ?>
                            </div>


                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="Trnsactions_trans_ptp_id">Payment Type</label>
									<?php
									?>
									<?php
									$paymenttypearr	 = AccountLedger::getPaymentLedgers(true, true, false, true);
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $model,
										'attribute'		 => 'apg_ledger_id',
										'val'			 => $model->apg_ledger_id,
										'asDropDownList' => FALSE,
										'options'		 => array('data' => new CJavaScriptExpression($paymenttypearr), 'allowClear' => true),
										'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Transaction Type', 'id' => 'ledger_id')
									));
									?>
									<?= $form->error($model, 'apg_ledger_id'); ?>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="Trnsactions_trans_stat">Status</label>
									<?php
									if (count($model->trans_stat) < 0)
									{
										$model->trans_stat = [1];
									}
									$this->widget('booster.widgets.TbSelect2', array(
										'attribute'		 => 'trans_stat',
										'model'			 => $model,
										'data'			 => $status,
										'value'			 => explode(',', $model->trans_stat),
										'htmlOptions'	 => array(
											'multiple'		 => 'multiple',
											'placeholder'	 => 'Service Tier',
											'width'			 => '100%',
											'style'			 => 'width:100%',
										),
									));
									?>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="Trnsactions_trans_mode">Mode</label>
									<?php
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $model,
										'attribute'		 => 'apg_mode',
										'val'			 => $model->apg_mode,
										'asDropDownList' => FALSE,
										'options'		 => array('data' => new CJavaScriptExpression($modeJson), 'allowClear' => true),
										'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Mode')
									));
									?>
                                </div>
                            </div>

							<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="">Transaction For</label>
									<?php
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $model,
										'attribute'		 => 'tranasctionFor',
										'val'			 => $model->tranasctionFor,
										'asDropDownList' => FALSE,
										'options'		 => array('data' => new CJavaScriptExpression($for), 'allowClear' => true),
										'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Mode')
									));
									?>
                                </div>
                            </div>


                            <div class="col-xs-6 col-sm-2 col-md-2 mt20">   
								<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary full-width')); ?>

                            </div>
							<?php $this->endWidget(); ?>
							
							<div class="p20 text-right" >

								<?= CHtml::beginForm(Yii::app()->createUrl('admin/transaction/payList'), "post", ['style' => "margin-bottom: 10px; margin-top: 10px; margin-left: 20px;"]); ?>

								<div class="row">
											<input type="hidden" id="export1" name="export1" value="true"/>
											<input type="hidden" id="export_trans_date1" name="export_trans_date1" value="<?= $model->trans_date1 ?>">
											<input type="hidden" id="export_trans_date2" name="export_trans_date2" value="<?= $model->trans_date2 ?>">


											<input type="hidden" id="export_trans_code" name="export_trans_code" value="<?= $model->trans_code ?>">
											<input type="hidden" id="export_trans_booking" name="export_trans_booking" value="<?= $model->trans_booking ?>">
											<input type="hidden" id="export_apg_ledger_id" name="export_apg_ledger_id" value="<?= $model->apg_ledger_id ?>">
											<input type="hidden" id="export_trans_stat" name="export_trans_stat" value="<?= implode(',', $model->trans_stat); ?>">
											<input type="hidden" id="export_apg_mode" name="export_apg_mode" value="<?= $model->apg_mode ?>">
											<input type="hidden" id="export_tranasctionFor" name="export_tranasctionFor" value="<?= $model->tranasctionFor ?>">
											<button class="btn btn-default" type="submit" style="width: 185px;">Export Below Table</button>
								</div>
							</div>

							<?= CHtml::endForm() ?>
                        </div>



                    </div>


                </div>
                <div class="col-xs-12 text-center">




					<?
					if ($isPaymentSuccess == 2)
					{
						echo "<span class='text-danger' style='font-size:22px'><b>Refund failed</b></span>";
					}
					else if ($isPaymentSuccess == 1)
					{
						echo "<span class='text-success'  style='font-size:22px'><b>Refund success</b></span>";
					}
					?></div>
                <div class="projects">



					<?php
					if (!empty($dataProvider))
					{
						$this->widget('booster.widgets.TbGridView', array(
							'id'				 => 'transaction-grid',
							'responsiveTable'	 => true,
							'ajaxUrl'			 => CHtml::normalizeUrl(Yii::app()->createUrl('admin/transaction/list', $dataProvider->getPagination()->params)),
							//'filter' => $model,
							'dataProvider'		 => $dataProvider,
							'template'			 => "<div class='panel-heading'><div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
							'itemsCssClass'		 => 'table table-striped table-bordered mb0',
							'htmlOptions'		 => array('class' => 'panel panel-primary'),
							'columns'			 => array(
								array('name'	 => 'bkg_booking_id', 'filter' => false,
									'value'	 => function ($data) {

										if ($data['apg_acc_trans_type'] == '1')
										{
											echo CHtml::link($data['bkg_booking_id'], Yii::app()->createUrl("/admpnl/booking/view", ["id" => $data['bkg_id']]), ["onClick" => "return viewBooking(this)"]);
										}
										else
										{
											//echo $data['vnd_name'];
											echo CHtml::link($data['vnd_name'], Yii::app()->createUrl("admin/vendor/view", ["id" => $data['vnd_id']]), ["class" => "", "onclick" => "", 'target' => '_blank']);
										}
									}
									, 'type'				 => 'raw', 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Booking ID/Vendor'),
								array('name'	 => 'trans_ptp_text', 'filter' => false, 'value'	 => function ($data) {
										echo $data['trans_ptp_text'];
									}
									, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Payment Type'),
								array('name'	 => 'apg_mode', 'filter' => false, 'value'	 => function ($data) {
										echo $data['apg_mode'];
									}
									, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Mode'),
								array('name'	 => 'trans_code', 'filter' => false,
									'value'	 => function ($data) {
										if ($data['trans_code'] != '')
										{
											echo $data['trans_code'];
											if ($data['apg_amount'] < 0)
											{

												echo "<br> Ref : " . $data['refundOrderCode'];
											}
										}
										else
										{
											echo "NA";
										}
									}
									, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => $model->getAttributeLabel('trans_code')),
								array('name'	 => 'apg_txn_id', 'filter' => false, 'value'	 => function ($data) {
										echo $data['apg_txn_id'];
									}, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => "Payment trans id"),
								array('name'	 => 'trans_status', 'filter' => false, 'value'	 => function ($data) {
										if ($data['trans_status'] == '3')
										{
											echo "Pending";
										}
										if ($data['trans_status'] == '2')
										{
											echo "Failure";
										}
										if ($data['trans_status'] == '1')
										{
											echo "Success";
										}
										if ($data['trans_status'] == '0')
										{
											echo "Initiated";
										}
									}, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => $model->getAttributeLabel('trans_status')),
								array('name'	 => 'trans_response_message',
									'value'	 => '$data->trans_response_message',
									'value'	 => function ($data) {
										if ($data['apg_ledger_id'] == 1 || $data['apg_ledger_id'] == 33)
										{
											$message = json_decode($data['trans_response_details'], true);
											echo $message['DESCRIPTION'];
										}
										else
										{
											echo $data['trans_response_message'];
										}
									},
									'filter'			 => false, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => $model->getAttributeLabel('trans_response_message')),
								array('name'	 => 'apg_amount', 'filter' => false, 'value'	 => function ($data) {
										$val	 = $data['apg_amount'];
										$amount	 = ($val == ceil($val)) ? round($val) : $val;
										echo $amount;
									}, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => $model->getAttributeLabel('trans_amount')),
								array('name'				 => 'trans_start_datetime', 'filter'			 => false, 'value'				 =>
									'date("d/m/Y H:i:s",strtotime($data[trans_start_datetime]))'
									, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Start Date/Time'),
								array('name'	 => 'trans_complete_datetime', 'filter' => false, 'value'	 => function ($data) {
										if ($data['trans_complete_datetime'] != NULL)
										{
											echo date("d/m/Y H:i:s", strtotime($data['trans_complete_datetime']));
										}
									}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Complete Date/Time'),
								array('name'			 => 'apg_response_details', 'filter'		 => false, 'htmlOptions'	 => array('style' => 'white-space:nowrap'), 'value'			 => function ($data) {
										if ($data['apg_response_details'] != NULL)
										{
											$responseDetails = json_decode($data['apg_response_details'], true);
											echo 'Payment Id: ' . $responseDetails['razorpay_payment_id'] . '<br/>Order Id: ' . $responseDetails['razorpay_order_id'] . '<br/>Signature: ' . $responseDetails['razorpay_signature'];
										}
									}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-6'), 'header'			 => 'Response Details')
						)));
					}
					?>

                </div>
            </div>
        </div>
    </div>
</div>
<?php
$version = Yii::app()->params['customJsVersion'];
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/custom.js?v=' . $version, CClientScript::POS_HEAD);
?>
<script type="text/javascript">
    function addTransaction11() {
        $href = "<?= Yii::app()->createUrl('admin/transaction/create') ?>";
        jQuery.ajax({type: 'GET',
            url: $href,
            success: function (data)
            {
                tranbox = bootbox.dialog({
                    message: data,
                    title: 'Add Transaction',
                    onEscape: function () {

                    }
                });
                tranbox.on('hidden.bs.modal', function (e) {
                    $('body').addClass('modal-open');
                });
            }
        });
    }

    function addTransaction() {


        $href = "<?= Yii::app()->createUrl('admin/transaction/create') ?>";
        jQuery.ajax({type: 'GET',
            url: $href,
            success: function (data)
            {
                tranbox = bootbox.dialog({
                    message: data,
                    title: 'Add Transaction',
                    onEscape: function () {

                    }
                });
                tranbox.on('hidden.bs.modal', function (e) {
                    $('body').addClass('modal-open');
                });
            }
        });
    }
</script>