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

                            <div class="col-xs-6 col-sm-2 col-md-2 mt20">   
								<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary full-width')); ?>
                            </div>
							<?php $this->endWidget(); ?>
                        </div>

						<!--//////////////-->                 

                    </div>

                </div>

                <div class="projects">

					<?php
					if (!empty($dataProvider))
					{
						$this->widget('booster.widgets.TbGridView', array(
							'id'				 => 'transaction-grid',
							'responsiveTable'	 => true,
							'ajaxUrl'			 => CHtml::normalizeUrl(Yii::app()->createUrl('aaohome/account/accountlist', $dataProvider->getPagination()->params)),
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
								//array('name' => 'bkg_booking_id', 'filter' => false, 'value' => 'CHtml::link($data->bkg_booking_id,Yii::app()->createUrl("/aaohome/booking/view",["id"=>$data->bkg_booking_id]),["onClick"=>"return viewBooking(this)"])', 'type' => 'raw', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' =>'Ledger Name'),

								array('name'	 => 'adt_ledger_id', 'filter' => false, 'value'	 => function($data) {
										return AccountLedger::model()->findByPk($data->adt_ledger_id)->ledgerName;
									}
									, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Ledger ID'),
								array('name'	 => 'adt_ledger_id', 'filter' => false, 'value'	 => function($data) {
										echo $data->adt_ledger_id;
									}
									, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Ledger Name'),
								array('name'	 => 'trans_openingbalance', 'filter' => false,
									'value'	 => function($data) {

										echo $data->openingbalance;
									}
									, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Opening Balance'),
								array('name'	 => 'trans_openingbalance', 'filter' => false,
									'value'	 => function($data) {

										echo $data->debit;
									}
									, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Debit'),
								array('name'	 => 'trans_debit', 'filter' => false, 'value'	 => function($data) {
										echo $data->credit;
									}, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Credit'),
								array('name'	 => 'trans_amount', 'filter' => false, 'value'	 => function($data) {
										$val	 = $data->trans_amount;
										$amount	 = ($val == ceil($val)) ? round($val) : $val;
										echo '<nobr><i class="fa fa-inr"></i>' . $amount . '</nobr>';
									}, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Closing Balance'),
								array('name'	 => 'trans_date', 'filter' => false, 'value'	 => function($data) {
										echo $data->act_date;
									}, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Date')
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