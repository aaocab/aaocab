 <style>
    .panel-body{
        padding-top: 0 ;
        padding-bottom: 0;
    }

	#pageloader
	{
		background: rgba( 255, 255, 255, 0.8 );
		display: none;
		height: 100%;
		position: fixed;
		width: 100%;
		z-index: 9999;
	}

	#pageloader img
	{
		left: 50%;
		margin-left: -32px;
		margin-top: -32px;
		position: absolute;
		top: 50%;
	}
</style>

<div class="row m0">
    <div class="col-xs-12">
        <div class="text-right">
        </div>    
        <div class="panel panel-default">
            <div class="panel-body">
				<?php
				$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
					'id'					 => 'vendor_balance', 'enableClientValidation' => true,
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


                <div id="row" class="row">

					<div class="col-xs-12 col-sm-3 "><?
						$daterang	 = date('F d, Y') . " - " . date('F d, Y');

						if(!$fromDate && !$toDate)
						{
							$fromDate	 = date('Y-m-d', strtotime('-6 day'));
							$toDate		 = date('Y-m-d');
						}
						$daterang = date('F d, Y', strtotime($fromDate)) . " - " . date('F d, Y', strtotime($toDate));
						?>
						<label  class="control-label">Transaction Date Range</label>
						<div id="vendorBalanceDateRange" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
							<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
							<span><?= $daterang ?></span> <b class="caret"></b>
						</div>
						<input type="hidden" id="fromDate" name="fromDate" value="<?= $fromDate ?>"/>
						<input type="hidden" id="toDate" name="toDate" value="<?= $toDate ?>"/>
						<input type="hidden" id="export1" name="export1" value="true"/>

					</div>

					<div class="col-xs-6 col-sm-4 col-md-2 text-center mt20 pt5  ">   
						<?php echo CHtml::submitButton('Export', array('id'=>'btnVndBalanceSubmit', 'class' => 'btn btn-primary full-width')); ?>
					</div>
				</div>
				<?php $this->endWidget(); ?>

            </div>  

        </div>  
    </div>
</div>
<script>
	$(document).ready(function () {


		var start = '<?= date('d/m/Y', strtotime('-6 day')); ?>';
		var end = '<?= date('d/m/Y'); ?>';

		$('#vendorBalanceDateRange').daterangepicker(
				{
					locale: {
						format: 'DD/MM/YYYY',
						cancelLabel: 'Clear'
					},
					"showDropdowns": true,
					"alwaysShowCalendars": true,
					"startDate": start,
					"endDate": end,
					"maxDate": end,
					ranges: {
						'Today': [moment(), moment()],
						'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
						'Last 7 Days': [moment().subtract(6, 'days'), moment()],
						'Last 30 Days': [moment().subtract(29, 'days'), moment()],
						'This Month': [moment().startOf('month'), moment().endOf('month')],
						'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
					}
				},
				function (start1, end1) {
					$('#fromDate').val(start1.format('YYYY-MM-DD'));
					$('#toDate').val(end1.format('YYYY-MM-DD'));
					$('#vendorBalanceDateRange span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
					 $('#btnVndBalanceSubmit').removeAttr('disabled');
				});

		$('#vendorBalanceDateRange').on('cancel.daterangepicker', function (ev, picker) {
			$('#vendorBalanceDateRange span').html('Select Date Range');
			$('#fromDate').val('');
			$('#toDate').val('');
		});
		$("#vendor_balance").on("submit", function () {
			 $('#btnVndBalanceSubmit').attr('disabled','disabled');
		});
		 
	});
</script>

