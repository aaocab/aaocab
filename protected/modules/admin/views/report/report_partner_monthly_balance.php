<style>
    .panel-body{
        padding-top: 0 ;
        padding-bottom: 0;
    }
    .table>tbody>tr>th
    {
        vertical-align: middle
    }

    .table>tbody>tr>td, .table>tbody>tr>th{
        padding: 7px;
        line-height: 1.5em;
    }
</style>
<?php
$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/jquery.mask.min.js');
Yii::app()->clientScript->registerCssFile(ASSETS_URL . '/plugins/form-select2/select2.css');
?>
<div class="row m0">
    <div class="col-xs-12">
        <div class="text-right">
        </div>    
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row"> 
					<?php
					$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
						'id'					 => 'getassignments', 'enableClientValidation' => true,
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
                    <div class="col-xs-12">
						<!--						<div class="col-xs-3">
													<?//= $form->datePickerGroup($model, 'from_date', array('label' => 'Form Date', 'widgetOptions' => array('options' => array('autoclose' => true, 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'Created Date', 'value' => ($model->from_date == '') ? DateTimeFormat::DateToDatePicker(date('Y-m-d')) : DateTimeFormat::DateToDatePicker($model->from_date))), 'prepend' => '<i class="fa fa-calendar"></i>'));
													?>
												</div>
												<div class="col-xs-3">
													<?//= $form->datePickerGroup($model, 'to_date', array('label' => 'To Date', 'widgetOptions' => array('options' => array('autoclose' => true, 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'Created Date', 'value' => ($model->to_date == '') ? DateTimeFormat::DateToDatePicker(date('Y-m-d')) : DateTimeFormat::DateToDatePicker($model->to_date))), 'prepend' => '<i class="fa fa-calendar"></i>'));
													?>
												</div>-->
						<div class="col-xs-4">
							<label class="control-label">Pickup Date</label>
							<?php
							$daterang			 = "Select Pickup Date Range";
							$fromDate			 = ($model->from_date == '') ? '' : $model->from_date;
							$toDate				 = ($model->to_date == '') ? '' : $model->to_date;
							if ($fromDate != '' && $toDate != '')
							{
								$daterang = date('F d, Y', strtotime($fromDate)) . " - " . date('F d, Y', strtotime($toDate));
							}
							?>
							<div id="dateRange" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
								<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
								<span style="min-width: 240px"><?php echo $daterang ?></span> <b class="caret"></b>
							</div>
							<?php echo $form->hiddenField($model, 'from_date'); ?>
							<?php echo $form->hiddenField($model, 'to_date'); ?>
							<?php
							if ($error != '')
							{
								echo
								"<span class='text-danger'>" . $error . "</span>";
							}
							?>
						</div>		
						<div class="col-xs-3">
							<label class="control-label">Agent</label>
							<?php
							$this->widget('ext.yii-selectize.YiiSelectize', array(
								'model'				 => $partnerModel,
								'attribute'			 => 'cpm_agent_id',
								'useWithBootstrap'	 => true,
								"placeholder"		 => "Select Channel Partner",
								'fullWidth'			 => false,
								'htmlOptions'		 => array('width' => '100%'),
								'defaultOptions'	 => $selectizeOptions + array(
							'onInitialize'	 => "js:function(){
															  populatePartner(this, '{$partnerModel->cpm_agent_id}');
															}",
							'load'			 => "js:function(query, callback){
															loadPartner(query, callback);
															}",
							'render'		 => "js:{
															option: function(item, escape){
															return '<div><span class=\"\"><i class=\"fa fa-user mr5\"></i>' + escape(item.text) +'</span></div>';
															},
															option_create: function(data, escape){
															return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
															}
															}",
								),
							));
							?>
							<?php
							if ($agtError != '')
							{
								echo
								"<span class='text-danger'>" . $agtError . "</span>";
							}
							?>
						</div>
                    </div>

                    <div class="col-xs-12 col-sm-3  col-md-2 col-lg-1 mt20 pt5">   
						<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary')); ?>
                    </div>
					<?php $this->endWidget(); ?>
                </div>
				<div class="row">
					<div class="col-xs-12">
						<?php
						if (!empty($dataProvider))
						{
							$this->widget('booster.widgets.TbGridView', array(
								'responsiveTable'	 => true,
								'dataProvider'		 => $dataProvider,
								'template'			 => "<div class='panel-heading'>
                                        <div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div>
                                            <div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                        </div>
                                    </div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'>
                                        <div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 p5'>{summary}</div>
                                            <div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                        </div>
                                    </div>",
								'itemsCssClass'		 => 'table table-striped table-bordered mb0',
								'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
								'columns'			 => array(
									array('name' => 'agentName', 'value' => $data['agentName'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Name'),
									array('name' => 'pickupDate', 'value' => $data['pickupDate'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Date'),
									array('name' => 'BookingAmount', 'value' => $data['BookingAmount'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Booking Amount'),
									array('name' => 'ServedAmount', 'value' => $data['ServedAmount'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Service Amount'),
									array('name' => 'AdvanceAmount', 'value' => $data['AdvanceAmount'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Advanced Amount'),
									array('name' => 'CancelCharges', 'value' => $data['CancelCharges'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Cancel Charges'),
									array('name' => 'TotalBalance', 'value' => $data['TotalBalance'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Total Balance'),
									array('name' => 'Wallet', 'value' => $data['Wallet'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Wallet'),
									array('name' => 'Bank', 'value' => $data['Bank'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Bank'),
									array('name' => 'Compensation', 'value' => $data['Compensation'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Compensation'),
									array('name' => 'OtherPayments', 'value' => $data['OtherPayments'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Other Payments'),
									array('name' => 'Commission', 'value' => $data['Commission'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-right '), 'header' => 'Commission'),
							)));
						}
						?>
					</div></div>
            </div>  

        </div>  
    </div>
</div>
<script >
    var start = '<?php echo date('d/m/Y', strtotime('-12 month')); ?>';
    var end = '<?php echo date('d/m/Y'); ?>';
    $('#dateRange').daterangepicker(
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
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 15 Days': [moment().subtract(14, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment()],
                    'Last 12 Month': [moment().subtract(12, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                }
            }, function (start1, end1) {
        $('#AccountTransactions_from_date').val(start1.format('YYYY-MM-DD'));
        $('#AccountTransactions_to_date').val(end1.format('YYYY-MM-DD'));
        $('#dateRange span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
    });
    $('#dateRange').on('cancel.daterangepicker', function (ev, picker) {
        $('#dateRange span').html('Select Pickup Date Range');
        $('#AccountTransactions_from_date').val('');
        $('#AccountTransactions_to_date').val('');
    });


</script>






