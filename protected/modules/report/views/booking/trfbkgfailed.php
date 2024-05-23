<div class="row m0">
    <div class="col-xs-12">
        <div class="text-right">
        </div>    
        <div class="panel panel-default">
            <div class="panel-body">
				
				<div class="row"> 
					<?php
					$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
						'id'					 => 'drvAppUsage-form', 'enableClientValidation' => true,
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
					<div class="col-xs-12 col-sm-2">
                        <div class="form-group">
							<?= $form->textFieldGroup($model, 'trb_trz_journey_code', array('label' => 'Journey Code', 'htmlOptions' => array('placeholder' => 'Code'))) ?>
                        </div> 
                    </div>

					<div class="col-xs-12 col-sm-2">
                        <div class="form-group">
							<?= $form->textFieldGroup($model, 'trb_trz_journey_id', array('label' => 'Journey Id', 'htmlOptions' => array('placeholder' => 'Journey Id'))) ?>
                        </div> 
                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-3" style="">
                        <div class="form-group">
                            <label class="control-label">Create Date Range</label>
							<?php
							$daterang			 = "Select Date Range";
							$createDate1	 = ($model->createDate1 == '') ? '' : $model->createDate1;
							$createDate2	 = ($model->createDate2 == '') ? '' : $model->createDate2;
							if ($createDate1 != '' && $createDate2 != '')
							{
								$daterang = date('F d, Y', strtotime($createDate1)) . " - " . date('F d, Y', strtotime($createDate2));
							}
							?>
                            <div id="trfPickupDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                <span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
                            </div>
							<?= $form->hiddenField($model, 'createDate1'); ?>
							<?= $form->hiddenField($model, 'createDate2'); ?>
                        </div>
					</div>

					 <div class="col-xs-12 col-sm-4 col-md-3" style="">
						<label class="control-label">Status</label>
						<?php
							$statusJson			 = Filter::getJSON(TransferzOffers::getOfferStatus());
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'trb_status',
								'val'			 => ($model->trb_status != '')? $model->trb_status: '2',
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($statusJson), 'allowClear' => true),
								'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Status')
							));
						?>
					</div>
					
                    <div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-2 col-md-1 text-center mt20 p5">   
						<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary full-width')); ?></div>
					<?php $this->endWidget(); ?>
                </div>


				<?php
				if (!empty($dataProvider))
				{

					$params									 = array_filter($_REQUEST);
					$dataProvider->getPagination()->params	 = $params;
					$dataProvider->getSort()->params		 = $params;
					$this->widget('booster.widgets.TbGridView', array(
						'responsiveTable'	 => true,
						'dataProvider'		 => $dataProvider,
						'template'			 => "<div class='panel-heading'><div class='row m0'>
                                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                                    </div></div>
                                                    <div class='panel-body table-responsive'>{items}</div>
                                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
						'itemsCssClass'		 => 'table table-striped table-bordered dataTable mb0',
						'htmlOptions'		 => array('class' => 'panel panel-primary  compact'),
						//       'ajaxType' => 'POST',
						'columns'			 => array(
							array('name'				 => 'trb_id', 'value'				 => '$data[trb_id]',
								'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => ''),
								'header'			 => 'Id'),
							array('name'				 => 'trb_trz_journey_code', 'value'				 => '$data[trb_trz_journey_code]',
								'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => ''),
								'header'			 => 'Journey Code'),
							array('name'				 => 'trb_trz_journey_id', 'value'				 => '$data[trb_trz_journey_id]',
								'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => ''),
								'header'			 => 'Journey Id'),
							
							array('name'	 => 'vehicletype', 'value'	 => function ($data) {
									echo $data['vehicletype'];
								},
								'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => ''),
								'header'			 => 'Vehicle Type'),
							array('name'				 => 'fromCityName', 'value'				 => '$data[fromCityName]',
								'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => ''),
								'header'			 => 'From City'),
							array('name'				 => 'toCityName', 'value'				 => '$data[toCityName]',
								'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => ''),
								'header'			 => 'To City'),
							array('name' => 'trb_status', 'value' =>  function ($data) {
										$cab = TransferzOffers::getOfferStatus($data['trb_status']);
										return $cab;
							}, 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'Status'),
							array('name'				 => 'trb_pickup_date', 'value'				 => 'date("d/M/Y h:i A", strtotime($data[trb_pickup_date]))',
								'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'text-center'),
								'htmlOptions'		 => array('class' => 'text-center'),
								'header'			 => 'Pickup Date/Time'),
							array('name'				 => 'trb_create_date', 'value'				 => 'date("d/M/Y h:i A",strtotime($data[trb_create_date]))',
								'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'text-center'),
								'htmlOptions'		 => array('class' => 'text-center'),
								'header'			 => 'Create Date/Time'),
					)));
				}
				?> 
			</div>  

		</div>  
	</div>
</div>

<script>
    var start = '<?= date('d/m/Y', strtotime('-3 day')); ?>';
    var end = '<?= date('d/m/Y'); ?>';
    $('#trfPickupDate').daterangepicker(
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
        $('#TransferzOffers_createDate1').val(start1.format('YYYY-MM-DD'));
        $('#TransferzOffers_createDate2').val(end1.format('YYYY-MM-DD'));
        $('#trfPickupDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
    });
    $('#trfPickupDate').on('cancel.daterangepicker', function (ev, picker) {
        $('#trfPickupDate span').html('Select Pickup Date Range');
        $('#TransferzOffers_createDate1').val('');
        $('#TransferzOffers_createDate2').val('');
    });
</script>