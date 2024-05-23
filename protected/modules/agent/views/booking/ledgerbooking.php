
<style>
    .panel-heading{
        padding: 0px 5px 0px 5px !important; 
    }
</style>
<?php
$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
    'id' => 'addamount-form', 'enableClientValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
        'errorCssClass' => 'has-error',
    ),
    'enableAjaxValidation' => false,
    'errorMessageCssClass' => 'help-block',
    'htmlOptions' => array(
        'class' => 'form-horizontal'
    ),
        ));
/* @var $form TbActiveForm */
?>
<div class="col-xs-12">
    <div class="panel panel-default">
        <div class="panel panel-heading" style="padding: 10px !important">Transaction List</div>
        <div class="panel panel-body">
            <div class="col-sm-12">
                <table class="table table-striped table-bordered">
                    <tr  class="text-center">                     
                        <td><b>Accounts Balance</b><br><span style="font-size: 10px; font-weight: 800">(+=credit / receivable to Gozo<br>-=debit / payable by Gozo)</span></td>
                        <td><b>Security Deposit</b></td>

                    </tr>
                    <tr  class="text-center">
                        <td><i class="fa fa-inr"></i>
	                      <?php echo trim(($getBalance['pts_ledger_balance'])-($getBalance['pts_wallet_balance']));?>
		                </td>


                        <td><i class="fa fa-inr"></i>
                            <?= ($agentAmount['securitydepo'] > 0) ? round($agentAmount['securitydepo']) : 0; ?>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-xs-12 pb20">
				<div class="row">
                <div class="col-xs-12 col-sm-6">
                    <?
                    $daterang = "Select Transaction Date Range";
                    $createdate1 = ($model->trans_create_date1 == '') ? '' : $model->trans_create_date1;
                    $createdate2 = ($model->trans_create_date2 == '') ? '' : $model->trans_create_date2;
                    if ($createdate1 != '' && $createdate2 != '') {
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
                <div class="col-xs-12 col-sm-3 col-md-2  mt15 pt20">
                    <label  class="control-label"></label>
                    <button class="btn btn-primary col-xs-12" type="submit" name="bookingSearch">Search</button>
                </div>
                <?php $this->endWidget(); ?>
                <div class="col-xs-12 col-sm-3 col-md-2 mt15">
                    <label  class="control-label"></label>
                    <?= CHtml::beginForm(Yii::app()->createUrl('agent/booking/ledgerbooking'), "post", ['style' => ""]); ?>
                    <input type="hidden" id="export" name="export" value="true"/>
                    <input type="hidden" id="export_from" name="export_from" value="<?= $model->trans_create_date1 ?>"/>
                    <input type="hidden" id="export_to" name="export_to" value="<?= $model->trans_create_date2 ?>"/>
                    <button class="btn btn-default col-xs-12" type="submit">Export Table</button>
                    <?= CHtml::endForm() ?>
                </div>
				</div>
            </div>
        </div>
    </div>

</div>
<div class="col-xs-12">
    <div class="panel panel-default">
        <div class="panel panel-body">
            <div class="col-sm-12">
                <?
                if ($agentList != '') {
                    $this->widget('booster.widgets.TbGridView', array(
                        'id' => 'booking-list',
                        'responsiveTable' => true,
                        'dataProvider' => $agentList,
                        'template' => "<div class='panel-heading dataprovider'><div class='row m0'>
                        <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                        </div></div>
                        <div class='panel-body'>{items}</div>
                        <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
                        'itemsCssClass' => 'table table-striped table-bordered mb0',
                        'htmlOptions' => array('class' => 'table-responsive panel panel-primary'),
                        'columns' => array(
                            ['name' => 'transDate', 'value' => 'date("d/m/Y", strtotime($data[act_date]))', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Transaction Date'],
                            ['name' => 'bookingId', 'value' => '$data[bookingId]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Booking ID'],
                            ['name' => 'bkg_agent_ref_code', 'value' => '$data[bkg_agent_ref_code]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Partner Reference ID'],
                            ['name' => 'pickupDate', 'value' => '$data[bkg_pickup_date]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Pickup Date'],
                            ['name' => 'bookingInfo', 'value' => function($data) {
                                    if ($data['bookingInfo'] == "NA") {
                                        echo $data["agt_trans_code"] . " (" . AccountLedger::model()->findByPk($data['adt_ledger_id'])->ledgerName . ")";
                                    } else {
                                        echo $data['bookingInfo'];
                                    }
                                }, 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Booking Info'],
                            ['name' => 'transAmount', 'value' => 'number_format(round($data[adt_amount]))', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'amount (<i class="fa fa-inr"></i>)</b><br>(+=credit to gozo,<br>-=credit to agent)'],
                            ['name' => 'transRemarks', 'value' => '$data[act_remarks]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Notes'],
                            ['name' => 'adminName', 'value' => '$data[adminName]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Who'],
                            ['name' => 'runningBalance', 'value' => 'number_format($data[runningBalance])', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Running Balance'],
                    )));
                }
                ?>                    
            </div>

        </div>
    </div>
</div>
<script>
    $(document).ready(function () {

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
            $('#AccountTransDetails_trans_create_date1').val(start1.format('YYYY-MM-DD'));
            $('#AccountTransDetails_trans_create_date2').val(end1.format('YYYY-MM-DD'));
            $('#bkgCreateDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
        });
        $('#bkgCreateDate').on('cancel.daterangepicker', function (ev, picker) {
            $('#bkgCreateDate span').html('Select Transaction Date Range');
            $('#AccountTransDetails_trans_create_date1').val('');
            $('#AccountTransDetails_trans_create_date2').val('');
        });

    });
</script>