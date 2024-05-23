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
$paymentType = PaymentType::model()->getList();

$status		 = ['0' => 'Open', '1' => 'Success', '2' => 'Failure'];
$ptpJson	 = VehicleTypes::model()->getJSON($paymentType);
$statusJson	 = VehicleTypes::model()->getJSON($status);
$modeJson	 = VehicleTypes::model()->getJSON(PaymentGateway::model()->getModeList());
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
							$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
								'id'					 => 'transaction-form', 'enableClientValidation' => true,
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
								<?= $form->textFieldGroup($model, 'trans_booking', array('widgetOptions' => ['htmlOptions' => ['value' => $qry['trans_booking']]])) ?>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3"> 
								<?= $form->textFieldGroup($model, 'trans_user', array('widgetOptions' => ['htmlOptions' => ['value' => $qry['trans_user']]])) ?>
                            </div>
							  <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3"> 
								<?= $form->textFieldGroup($model, 'apg_txn_id', array('label'=>'Transaction ID','widgetOptions' => ['htmlOptions' => [ 'placeholder'=>'Transaction ID' , 'value' => $qry['apg_txn_id']]])) ?>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="Trnsactions_trans_ptp_id">Payment Type</label>
									<?php
									?>
									<?php
									$paymenttypearr = AccountLedger::getPaymentLedgers(true,true,false,true);
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
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $model,
										'attribute'		 => 'trans_stat',
										'val'			 => $model->trans_stat,
										'asDropDownList' => FALSE,
										'options'		 => array('data' => new CJavaScriptExpression($statusJson), 'allowClear' => true),
										'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Status')
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
                            <div class="col-xs-6 col-sm-2 col-md-2 mt20">   
								<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary full-width')); ?>
                            </div>
							<?php $this->endWidget(); ?>
                        </div>

           

                    </div>


                </div>

                <div class="p20 text-right" >

					<!--   <a class="btn btn-info btn-sm" id="bkg_acct" onclick="addTransaction()" title="Create Transaction" style="">Create Transaction</a> -->


                </div>
                <div class="col-xs-12 text-center"><?
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
								array('name' => 'bkg_booking_id', 'filter' => false, 
									'value' => function($data) {
										if ($data['apg_acc_trans_type']== '1')
										{
								echo CHtml::link($data['bkg_booking_id'],Yii::app()->createUrl("/admpnl/booking/view",["id"=>$data['bkg_id']]),["onClick"=>"return viewBooking(this)"]);
										}
										else
										{
											echo $data['vnd_name'];
										}
									}
									, 'type' => 'raw', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Booking ID'),
								array('name'				 => 'apg_ledger_id', 'filter'			 => false, 'value'				 => '$data[ledgerName]'
									, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Payment Type'),
								array('name'	 => 'trans_code', 'filter' => false,
									'value'	 => function($data)
									{
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
								array('name'	 => 'apg_mode', 'filter' => false, 'value'	 => function($data)
									{
										echo $data['trans_mode'];
									}, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => $model->getAttributeLabel('trans_mode')),
								array('name'	 => 'trans_status', 'filter' => false, 'value'	 => function($data)
									{
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
											echo "Open";
										}
									}, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => $model->getAttributeLabel('trans_status')),
								array('name'	 => 'trans_response_message',
									'value'	 => '$data->trans_response_message',
									'value'	 => function($data)
									{
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
								array('name'	 => 'apg_amount', 'filter' => false, 'value'	 => function($data)
									{
										$val	 = $data['apg_amount'];
										$amount	 = ($val == ceil($val)) ? round($val) : $val;
										echo '<nobr><i class="fa fa-inr"></i>' . $amount . '</nobr>';
									}, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => $model->getAttributeLabel('trans_amount')),
								array('name'				 => 'trans_start_datetime', 'filter'			 => false, 'value'				 =>
									'date("d/m/Y H:i:s",strtotime($data[trans_start_datetime]))'
									, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Start Date/Time'),
								array('name'	 => 'trans_complete_datetime', 'filter' => false, 'value'	 => function($data)
									{
										if ($data['trans_complete_datetime'] != NULL)
										{
											echo date("d/m/Y H:i:s", strtotime($data['trans_complete_datetime']));
										}
									}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Complete Date/Time'),
								array(
									'header'			 => 'Action',
									'class'				 => 'CButtonColumn',
									'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: left'),
									'headerHtmlOptions'	 => array('class' => 'text-center', 'style' => 'min-width: 100px;'),
									'template'			 => '',//  '{refund}',
									'buttons'			 => array(
										'refund'		 => array(
											'click'		 => 'function(e){
                                                        try
                                                        {
                                                            $href = $(this).attr("href");
                                                            jQuery.ajax({type:"GET","dataType":"html",url:$href,success:function(data)
                                                            {
                                                                bootbox.dialog({ 
                                                                message: data, 
                                                                title: "Refund Amount",
                                                                className:"bootbox-sm",    
                                                                callback: function(){  },

                                                            });
                                                            }}); 
                                                            }
                                                            catch(e)
                                                            { alert(e); }
                                                            return false;
                                                         }',
											'visible'	 => '($data[apg_amount]>0 && $data[trans_status]==1)?true:false',
											'url'		 => 'Yii::app()->createUrl("admin/transaction/refund", array("apgid"=>$data[apg_id],"iswallet"=>$data[is_wallet]))',
											'label'		 => '<i class="fa fa-undo"></i>',
											'options'	 => array('data-toggle' => 'ajaxModal', 'id' => 'example', 'style' => 'margin: 4px', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs btn-danger refund', 'title' => 'Refund'),
										),
										'htmlOptions'	 => array('class' => 'center'),
									))
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