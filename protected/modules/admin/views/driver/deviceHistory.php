<style>
    .dlgComments .dijitDialogPaneContent{
        overflow: auto;
    }
</style>
<?
$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];

?>
<div class="panel-advancedoptions" >
    <div class="row">
        <div class="col-md-12">            
            <div class="panel" >
                <div class="panel-body">
					<?php
					$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
					'id' => 'deviceHistory-form', 'enableClientValidation' => true,
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
							 $apt_last_login1  = ($model->apt_last_login1 == '') ? '' : $model->apt_last_login1;
							 $apt_last_login2 = ($model->apt_last_login2 == '') ? '' : $model->apt_last_login2;
							 if ($apt_last_login1  != '' && $apt_last_login2 != '')
							 {
								$daterang = date('F d, Y', strtotime($apt_last_login1)) . " - " . date('F d, Y', strtotime($apt_last_login2));
							 }
							 ?>
							 <div id="apt_last_login" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
								 <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
								 <span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
							 </div>
							 <?= $form->hiddenField($model, 'apt_last_login1'); ?>
			                <?= $form->hiddenField($model, 'apt_last_login2'); ?>
						</div>
					 </div>
			         <div class="row">
                        <div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-2 col-md-2 text-center mt20 p5">   
			            <?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary full-width')); ?></div>
		             </div>
				
                    <div class="">
                        <div style="width: 100%; overflow: auto;  border: 1px #aaa solid;color: #444;">
							<?php
							if (!empty($dataProvider))
							{
								$this->widget('booster.widgets.TbGridView', array(
									'id'				 => 'driverlog-grid' . $qry['booking_id'],
									'responsiveTable'	 => true,
									// 'filter' => FALSE,
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
										array('name'	 => 'name', 'filter' => FALSE, 'value'	 => function($data) {
												
													$modelv = Drivers::model()->findByPk($data['apt_entity_id']);
													echo ($modelv->drv_name!= '') ? $modelv->drv_name : $modelv->drv_code ;
												
												
											}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Name'),
										array('name'	 => 'type', 'filter' => FALSE, 'value'	 => function($data) {
												echo $data['apt_device'];
											}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Device'),
										array('name'	 => 'dlg_desc', 'filter' => FALSE, 'value'	 => function($data) {
												echo $data['apt_device_uuid'];
											}, 'sortable'			 => false,
											'headerHtmlOptions'	 => array('class' => 'col-xs-4'), 'header'			 => 'Device ID'),
										array('name'	 => 'apt_os_version', 'filter' => FALSE, 'value'	 => function($data) {
												echo $data['apt_apk_version'];
											}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Apk Version'),
										array('name'				 => 'vlg_created', 'filter'			 => FALSE, 'value'				 => 'date("d/M/Y h:i A", strtotime($data[apt_last_login]))',
											'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'),
											'header'			 => 'Last login')
								)));
							}
							?>
                        </div>
                    </div>
					
					<?php $this->endWidget(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var start = '<?= date('d/m/Y', strtotime('-1 month')); ?>';
    var end = '<?= date('d/m/Y'); ?>';
    $('#apt_last_login').daterangepicker(
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
        $('#AppTokens_apt_last_login1').val(start1.format('YYYY-MM-DD'));
        $('#AppTokens_apt_last_login2').val(end1.format('YYYY-MM-DD'));
        $('#apt_last_login span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
    });
    $('#apt_last_login').on('cancel.daterangepicker', function (ev, picker) {
        $('#apt_last_login span').html('Select Pickup Date Range');
        $('#AppTokens_apt_last_login1').val('');
        $('#AppTokens_apt_last_login2').val('');
    });
</script>
