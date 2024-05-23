<div class="row m0">
    <div class="col-xs-12">
        <div class="text-right"></div>    
        <div class="panel panel-default">
            <div class="panel-body">
				<div class="row"> 
					<?php
					$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
						'id'					 => 'accountingFlagSetForm', 'enableClientValidation' => true,
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
                    <div class="col-xs-12 col-sm-4 col-md-3" style="">
                        <div class="form-group">
                            <label class="control-label">Booking Create Date</label>
							<?php
							$daterang	 = "Select Date Range";
							$createDate1 = ($model->bkg_create_date1 == '') ? '' : $model->bkg_create_date1;
							$createDate2 = ($model->bkg_create_date2 == '') ? '' : $model->bkg_create_date2;
							if ($createDate1 != '' && $createDate2 != '')
							{
								$daterang = date('F d, Y', strtotime($createDate1)) . " - " . date('F d, Y', strtotime($createDate2));
							}
							?>
                            <div id="bkgCreateDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                <span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
                            </div>
							<?= $form->hiddenField($model, 'bkg_create_date1'); ?>
							<?= $form->hiddenField($model, 'bkg_create_date2'); ?>
                        </div>
					</div>

                    <div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-2 col-md-1 text-center mt20 p5">   
						<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary full-width')); ?>
					</div>
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
						'template'			 => "<div class='panel-heading'><div class='row'>
                                    <div class='col-xs-12 col-sm-6 pt5'>{summary}</div>
									<div class='col-xs-12 col-sm-6'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'><div class='table-responsive'>{items}</div></div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
						'itemsCssClass'		 => 'table-striped table-bordered dataTable mb0',
						'htmlOptions'		 => array('class' => 'panel panel-primary  compact'),
						//       'ajaxType' => 'POST',
						'columns'			 => array(
							array('name'	 => 'bkg_booking_id', 'value' => function ($data) 
								{
									echo CHtml::link($data['bkg_booking_id'], Yii::app()->createUrl("admin/booking/view", ["id" => $data['bkg_id']]), ["class" => "", "target" => "_blank"]);
								},
								'sortable'			 => true, 
								'headerHtmlOptions' => array('class' => ''), 
								'header' => 'Booking Id'),
							array('name'			 => 'bkg_create_date', 'value' => '$data[bkg_create_date]',
								'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => ''),
								'header'			 => 'Create Date'),
							array('name'			 => 'bkg_pickup_date ', 'value' => '$data[bkg_pickup_date]',
								'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => ''),
								'header'			 => 'Pickup Date'),
							array('name'			 => 'status', 'value' => '$data[status]',
								'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => ''),
								'header'			 => 'Status'),
							array('name'			 => 'blg_desc', 'value' => '$data[blg_desc]',
								'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => ''),
								'header'			 => 'Description'),
					)));
				}
				?> 
			</div>  
		</div>  
	</div>
</div>

<script>
	var start = '<?= date('d/m/Y', strtotime('-1 day')); ?>';
	var end = '<?= date('d/m/Y'); ?>';
	$('#bkgCreateDate').daterangepicker(
			{
				locale: {
					format: 'DD/MM/YYYY',
					cancelLabel: 'Clear',
					firstDay: 1
				},
				"showDropdowns": true,
				"alwaysShowCalendars": true,
				startDate: start,
				endDate: end,
				maxDate: end,

				ranges: {
					'Today': [moment(), moment()],
					'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
					'Last 7 Days': [moment().subtract(6, 'days'), moment()],
					'Last 30 Days': [moment().subtract(29, 'days'), moment()],
					'This Month': [moment().startOf('month'), moment().endOf('month')],
					'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
				}
			}, function (start1, end1) {
		$('#Booking_bkg_create_date1').val(start1.format('YYYY-MM-DD'));
		$('#Booking_bkg_create_date2').val(end1.format('YYYY-MM-DD'));
		$('#bkgCreateDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
	});
	$('#bkgCreateDate').on('cancel.daterangepicker', function (ev, picker) {
		$('#bkgCreateDate span').html('Select Create Date Range');
		$('#Booking_bkg_create_date1').val('');
		$('#Booking_bkg_create_date2').val('');
	});
</script>