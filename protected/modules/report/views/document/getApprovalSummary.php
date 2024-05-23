<div class="row">
    <div class="col-xs-12">
        <div class="panel panel-default"> 
			<div class="panel-body">
                <div class="row"> 
					<div class="col-sm-10 col-xs-12 ">
						<div class="row ">
							<?php
							$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
								'id'					 => 'doc-form', 'enableClientValidation' => true,
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

							<div class="col-xs-12 col-sm-6 col-md-6 col-lg-4 ">
								<?php
								$daterang	 = "Select Date Range";
								$fromDate	 = ($docModel->appDate1 == '') ? '' : $docModel->appDate1;
								$toDate		 = ($docModel->appDate2 == '') ? '' : $docModel->appDate2;
								if ($fromDate != '' && $toDate != '')
								{
									$daterang = date('F d, Y', strtotime($fromDate)) . " - " . date('F d, Y', strtotime($toDate));
								}
								?>
								<label class="control-label">Pickup Date Range</label>
								<div id="docAppDate" class="inputFilter" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
									<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
									<span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
								</div>	
								<?= $form->hiddenField($docModel, 'appDate1', ['class' => 'appDateVal1', 'value' => $docModel->appDate1]); ?>
								<?= $form->hiddenField($docModel, 'appDate2', ['class' => 'appDateVal2', 'value' => $docModel->appDate2]); ?>			
							</div>
							<div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
								<label class="control-label">Select By</label>
								<?php
								$groupList	 = [1 => 'CSR', 2 => 'Date', 3 => 'CSR and Date'];
								$this->widget('booster.widgets.TbSelect2', array(
									'model'			 => $docModel,
									'attribute'		 => 'groupType',
									'val'			 => $docModel->groupType,
									'asDropDownList' => true,
									'data'			 => $groupList,
									'htmlOptions'	 => array('class' => 'inputFilter', 'style' => 'width:100%', 'placeholder' => 'Select group type')
								));
								?>
							</div>

							<div class="col-xs-12 col-sm-3 col-md-3 col-lg-2 mt20 pt5"> 
								<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary active col-xs-12')); ?>
							</div>
							<?php $this->endWidget(); ?>
						</div> 
					</div>
					<div class="col-xs-12 col-sm-2 col-md-2 col-lg-2 pull-right <?php echo ($showExport) ? '' : 'hide'; ?>">
						<div class="row ">
							<?php
							$form1		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
								'id'					 => 'docapp-export', 'enableClientValidation' => true,
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

							<?= $form1->hiddenField($docModel, 'appDate1', ['class' => 'appDateVal1', 'id' => 'bkg_pickup_date1']); ?>
							<?= $form1->hiddenField($docModel, 'appDate2', ['class' => 'appDateVal2', 'id' => 'bkg_pickup_date2']); ?>
							<?= $form1->hiddenField($docModel, 'groupType', ['id' => 'groupType']); ?>

							<input type="hidden" id="export" name="export" value="true"/>
							<div class="col-xs-12 mt20  pt5 "> 
								<?php echo CHtml::submitButton('Export', array('class' => 'btn btn-default active col-xs-12')); ?>
							</div>

							<?php $this->endWidget(); ?>

						</div>
					</div>

				</div>
			</div>

			<?php
			if (!empty($dataProvider))
			{
				$showSigns								 = "<span class='col-md-12 col-lg-12 hidden-xs col-sm-12 text-nowrap'><i class='fa fa-check text-success'></i> | <i class='fa fa-times text-danger'></i></span><span class='col-xs-12 hidden-sm hidden-lg hidden-md'>(approve | reject)</span>";
				$params									 = array_filter($_REQUEST);
				$dataProvider->getPagination()->params	 = $params;
				$dataProvider->getSort()->params		 = $params;
				$this->widget('booster.widgets.TbGridView', array(
					'responsiveTable'	 => true,
					'dataProvider'		 => $dataProvider,
					'template'			 => "<div class='panel-heading'><div class='row'>
                                    <div class='col-xs-12 col-sm-6 pt5'>{summary}</div>
									<div class='col-xs-12 col-sm-6'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'><div class='table-responsive'>{items}</div></div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
					'itemsCssClass'		 => 'table-striped table-bordered dataTable mb0',
					'htmlOptions'		 => array('class' => 'panel panel-primary compact'),
					'columns'			 => array(
						array('name' => 'appdate', 'value' => 'DateTimeFormat::DateToDatePicker($data[appdate])', 'sortable' => true, 'headerHtmlOptions' => array('class' => ''), 'header' => 'Verified on', 'visible' => ($docModel->groupType != 1)),
						array('name' => 'approvedBy', 'value' => '$data[approvedBy]', 'sortable' => true, 'headerHtmlOptions' => array('class' => ''), 'header' => 'Verified by', 'visible' => ($docModel->groupType != 2)),
						array('name' => 'totCount', 'value' => '$data[totApproved]." | ".$data[totRejected]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center col-md-1'), 'htmlOptions' => array('class' => 'text-center'), 'header' => "Total Docs $showSigns"),
						array('name' => 'totVoter', 'value' => '$data[approveVoter]." | ".$data[rejectVoter]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center col-md-1'), 'htmlOptions' => array('class' => 'text-center'), 'header' => "Voter $showSigns"),
						array('name' => 'totPAN', 'value' => '$data[approvePAN]." | ".$data[rejectPAN]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center col-md-1'), 'htmlOptions' => array('class' => 'text-center'), 'header' => "PAN $showSigns"),
						array('name' => 'totAadhar', 'value' => '$data[approveAadhar]." | ".$data[rejectAadhar ]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center col-md-1'), 'htmlOptions' => array('class' => 'text-center'), 'header' => "Aadhar $showSigns"),
						array('name' => 'totLicense', 'value' => '$data[approveLicense]. " | " .$data[rejectLicense]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center col-md-1'), 'htmlOptions' => array('class' => 'text-center'), 'header' => "Driving License $showSigns"),
						array('name' => 'totInsurance', 'value' => '$data[approveInsurance]. " | " .$data[rejectInsurance]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center col-md-1'), 'htmlOptions' => array('class' => 'text-center'), 'header' => "Vehicle Insurance $showSigns"),
						array('name' => 'totRC', 'value' => '$data[approveRC]. " | " .$data[rejectRC]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center col-md-1'), 'htmlOptions' => array('class' => 'text-center'), 'header' => "Vehicle RC $showSigns"),
						array('name' => 'totPUC', 'value' => '$data[approvePUC]. " | " .$data[rejectPUC]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center col-md-1'), 'htmlOptions' => array('class' => 'text-center'), 'header' => "PUC $showSigns"),
						array('name' => 'totPermit', 'value' => '$data[approvePermit]. " | " .$data[rejectPermit]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center col-md-1'), 'htmlOptions' => array('class' => 'text-center'), 'header' => "Permit $showSigns"),
						array('name' => 'totFitness', 'value' => '$data[approveFitness]. " | " .$data[rejectFitness]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center col-md-1'), 'htmlOptions' => array('class' => 'text-center'), 'header' => "Fitness $showSigns"),
				)));
			}
			?> 
		</div> 
	</div>  
</div>  



<script>
	$(document).ready(function ()
	{
		var startDate = '<?php echo ($docModel->appDate1) ? date('d/m/Y', strtotime($docModel->appDate1)) : ''; ?>';
		var endDate = '<?php echo ($docModel->appDate2) ? date('d/m/Y', strtotime($docModel->appDate2)) : ''; ?>';
		var start = (startDate == '') ? '<?= date('d/m/Y', strtotime('-1 week')); ?>' : startDate;
		var end = (endDate == '') ? '<?= date('d/m/Y'); ?>' : endDate;
		$('#docAppDate').daterangepicker(
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
						//'Last 30 Days': [moment().subtract(29, 'days'), moment()],
						//'This Month': [moment().startOf('month'), moment()],
						//'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
					}
				}, function (start1, end1) {
			$('.appDateVal1').val(start1.format('YYYY-MM-DD'));
			$('.appDateVal2').val(end1.format('YYYY-MM-DD'));
			if ((end1 - start1) > 604799999) {
				bootbox.alert('Select date range between 7 days only');
				return false;
			}

			$('#docAppDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));

		});
		$('#docAppDate').on('cancel.daterangepicker', function (ev, picker) {
			$('#docAppDate span').html('Select Date Range');
			$('.appDateVal1').val('');
			$('.appDateVal2').val('');
		});

	});
</script>