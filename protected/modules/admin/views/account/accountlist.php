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


                    </div>

                </div>

                <div class="projects">

					<?php
					if (!empty($dataProvider))
					{
						$this->widget('booster.widgets.TbGridView', array(
							'id'				 => 'transaction-grid',
							'responsiveTable'	 => true,
							'ajaxUrl'			 => CHtml::normalizeUrl(Yii::app()->createUrl('admin/account/accountlist', $dataProvider->getPagination()->params)),
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
															
								array('name'	 => 'bcb_id', 'filter' => false, 'value'	 => function($data) {
										echo $data['bcb_id'];
									}, 'sortable'		 => true, 'htmlOptions'	 => array(), 'header'		 => 'TRIP ID'),
											array('name'	 => 'bkg_id', 'filter' => false, 'value'	 => function($data) {
										echo $data['bkg_id'];
									}, 'sortable'		 => true, 'htmlOptions'	 => array(), 'header'		 => 'BOOKING ID'),	
								array('name'	 => 'fromLedgerName', 'filter' => false, 'value'	 => function($data) {
										echo $data['fromLedgerName'];
									}
									, 'sortable'	 => true, 'htmlOptions'	 => array(), 'header'	 => 'from LedgerName'),
											
								array('name'	 => 'fromamount', 'filter' => false, 'value'	 => function($data) {
										echo $data['fromamount'];
									}
									, 'sortable'	 => true, 'htmlOptions'	 => array(), 'header'	 => 'From Amount'),
											
								array('name'	 => 'toLedgerName', 'filter' => false, 'value'	 => function($data) {
										echo $data['toLedgerName'].'<br>';
										echo $data['refName'];
									}
									, 'sortable'	 => true, 'htmlOptions'	 => array(), 'header'	 => 'To LedgerName'),
											
								array('name'	 => 'act_remarks', 'filter' => false, 'value'	 => function($data) {
										echo $data['act_remarks'];
									}
									, 'sortable'	 => true, 'htmlOptions'	 => array(), 'header'	 => 'Remarks'),
											
								array('name'	 => 'toamount', 'filter' => false, 'value'	 => function($data) {
										echo $data['toamount'];
									}
									, 'sortable'	 => true, 'htmlOptions'	 => array(), 'header'	 => 'To Amount'),				
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