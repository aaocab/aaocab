<style type="text/css">
    .cityinput > .selectize-control>.selectize-input{
        width:100% !important;
    }
</style>
<div class="row">
	<div class="panel" >
	<div class="panel-body">
		<?php
		$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
		'id' => 'accountMismatchReport-form', 'enableClientValidation' => true,
		'clientOptions' => array(
			'validateOnSubmit' => true,
			'errorCssClass' => 'has-error'
		),
		// Please note: When you enable ajax validation, make sure the corresponding
		// controller action is handling ajax validation correctly.
		// See class documentation of CActiveForm for details on this,
		// you need to use the performAjaxValidation()-method described there.
		'enableAjaxValidation' => false,
		'errorMessageCssClass' => 'help-block',
		'htmlOptions' => array(
			'class' => '',
		),
		));
		/* @var $form TbActiveForm */
		?>
		 <div class="col-xs-12 col-sm-4 col-md-4">
			<div class="form-group">
				<label class="control-label">Date Range</label>
				 <?php
				 $daterang = "Select Date Range";
				 $from_date  = ($model->from_date == '') ? '' : $model->from_date;
				 $to_date = ($model->to_date == '') ? '' : $model->to_date;
				 if ($from_date  != '' && to_date != '')
				 {
					$daterang = date('F d, Y', strtotime($from_date)) . " - " . date('F d, Y', strtotime($to_date));
				 }
				 ?>
				 <div id="bookingDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
					 <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
					 <span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
				 </div>
				 <?= $form->hiddenField($model, 'from_date'); ?>
				<?= $form->hiddenField($model, 'to_date'); ?>
			</div>
		 </div>
		
		 
		<div class="col-xs-12 col-sm-3 mt5"><br>
			<button class="btn btn-primary full-width" type="submit"  name="acoountMismatchSearch">Search</button>
		</div>
    <div class="col-xs-12">
		<?php
		if (!empty($dataProvider))
		{
			$this->widget('booster.widgets.TbGridView', array(
				'id'				 => 'route-grid',
				'responsiveTable'	 => true,
				'dataProvider'		 => $dataProvider,
				'template'			 => "<div class='panel-heading'><div class='row m0'>
                                    <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
				'itemsCssClass'		 => 'table table-striped table-bordered mb0',
				'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
				'columns'			 => array(
				
				array('name' => 'bkg_booking_id', 'value' => function($data) {
                    if ($data['bkg_booking_id'] != '') {
                        echo CHtml::link($data['bkg_booking_id'], Yii::app()->createUrl("admin/booking/view", ["id" => $data['bkg_id']]), ["class" => "viewBooking", "onclick" => "", 'target' => '_blank']);
                    }
                }, 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'Booking Id'),
				array('name' => 'status', 'value' => '$data["bkg_status"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Status'),
				array('name' => 'netBaseAmount', 'value' => '$data["bkg_net_base_amount"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Net Base Amount'),
			    array('name' => 'netAdvancedAmount', 'value' => '$data["bkg_net_advance_amount"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Net Advanced Amount'),
				array('name' => 'partnerCommission', 'value' => '$data["bkg_partner_commission"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Partner Commission'),
				array('name' => 'creditUsed', 'value' => '$data["bkg_credits_used"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Credit Used'),
				array('name' => 'totalTrans', 'value' => '$data["totalTrans"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Total Transaction'),
				array('name' => 'adtCreditUsed', 'value' => '$data["adtCreditUsed"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Ledger Credit Used'),
				array('name' => 'adtAdvance', 'value' => '$data["adtAdvance"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Ledger Amount'),
				array('name' => 'adtCommission', 'value' => '$data["adtCommission"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Ledger Commission'),
				array('name' => 'adtPartnerCredits', 'value' => '$data["adtPartnerCredits"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Ledger Partner Credit'),
				array('name' => 'Booking Souce', 'value' => function($data) {
					if ($data['bkg_agent_id'] != '') 
					{
                        $agentsModel = Agents::model()->findByPk($data['bkg_agent_id']);
						$agt_id = $data['bkg_agent_id'];
						$owner = ($agentsModel->agt_owner_name != '') ? $agentsModel->agt_owner_name : ($agentsModel->agt_fname . " " . $agentsModel->agt_lname);
					    if ($agentsModel->agt_type == 1)
						{
							echo $agentsModel->agt_id;
                            echo "<span class='text-danger'>CORPORATE (<a target='_blank' href='/aaohome/agent/view?agent=$agt_id'>" . ($agentsModel->agt_company . "-" . $owner)  . ")</a><br></span>";
                        } 
						else 
						{
                            echo "PARTNER (<a target='_blank' href='/aaohome/agent/view?agent=$agt_id'>" . ($agentsModel->agt_company . "-" . $owner) . ")</a><br>";
                        }
					}
                }, 'sortable' => false, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Booking Source'),
			)));
		}
		?>
    </div>
	
	<?php $this->endWidget(); ?>
  </div>
	</div>
</div>
<script>
    var start = '<?= date('d/m/Y', strtotime('-1 month')); ?>';
    var end = '<?= date('d/m/Y'); ?>';
    $('#bookingDate').daterangepicker(
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
                    'Last 15 Days': [moment().subtract(15, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                }
            }, function (start1, end1) {
        $('#AccountTransactions_from_date').val(start1.format('YYYY-MM-DD'));
        $('#AccountTransactions_to_date').val(end1.format('YYYY-MM-DD'));
        $('#bookingDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
    });
    $('#bookingDate').on('cancel.daterangepicker', function (ev, picker) {
        $('#bookingDate span').html('Select Date Range');
        $('#AccountTransactions_from_date').val('');
        $('#AccountTransactions_date').val('');
    });
</script>