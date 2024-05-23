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

<?
$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
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
						'id'					 => 'otpreport-form', 'enableClientValidation' => true,
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
					// @var $form TbActiveForm 
					?>
					<div class="col-xs-12 col-sm-6 col-md-3">
						<div class="form-group cityinput"> 
							<?php // echo $form->drop($model,'cpm_vehicle_type');  ?>
							<label>Channel Partner</label>
							<?php
							$this->widget('ext.yii-selectize.YiiSelectize', array(
								'model'				 => $model,
								'attribute'			 => 'partner',
								'useWithBootstrap'	 => true,
								"placeholder"		 => "Select Channel Partner",
								'fullWidth'			 => false,
								'htmlOptions'		 => array('width' => '100%'),
								'defaultOptions'	 => $selectizeOptions + array(
							'onInitialize'	 => "js:function(){
                                  populatePartner(this, '{$model->partner}');
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
							<?= $form->error($model, 'cpm_agent_id'); ?>
						</div>
					</div>
                    <div class="col-xs-12 col-sm-4 col-md-3" style="">
                        <div class="form-group">
							<?
							$daterang	 = "Select Pickup Date Range";
							$createdate1 = ($model->fromDate == '') ? '' : $model->fromDate;
							$createdate2 = ($model->toDate == '') ? '' : $model->toDate;
							if ($createdate1 != '' && $createdate2 != '')
							{
								$daterang = date('F d, Y', strtotime($createdate1)) . " - " . date('F d, Y', strtotime($createdate2));
							}
							?>
                            <label  class="control-label">Pickup Date</label>
                            <div id="OtpSearchDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                <span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
                            </div>
							<?
							echo $form->hiddenField($model, 'fromDate');
							echo $form->hiddenField($model, 'toDate');
							?>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-3  col-md-1   mt20 pt5">   
						<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary')); ?></div>
						<?php $this->endWidget(); ?>

					<?php
					$checkExportAccess = false;
					if ($roles['rpt_export_roles'] != null)
					{
						$checkExportAccess = Filter::checkACL($roles['rpt_export_roles']);
					}
					if ($checkExportAccess)
					{
						echo CHtml::beginForm(Yii::app()->createUrl('report/booking/Otpserved'), "post", ['style' => "margin-top: 24px;"]);
						?>
						<input type="hidden" id="fromDate" name="fromDate" value="<?= $model->fromDate ?>"/>
						<input type="hidden" id="toDate" name="toDate" value="<?= $model->toDate ?>"/>
						<input type="hidden" id="partner" name="partner" value="<?= $model->partner ?>"/>
						<input type="hidden" id="export" name="export" value="true"/>
						<button class="btn btn-default" type="submit" style="width: 150px;">Export</button>
						<?php echo CHtml::endForm(); ?>	
					<?php } ?>	
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
                                                    <div class='panel-body table-responsive table-bordered'>{items}</div>
                                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
						'itemsCssClass'		 => 'table table-striped table-bordered dataTable mb0',
						'htmlOptions'		 => array('class' => 'panel panel-primary  compact'),
						//    'ajaxType' => 'POST',
						'columns'			 => array(
							array('name' => 'reportWeek', 'value' => '$data[reportWeek]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Pickup Week/Year'),
							array('name' => 'otp_required', 'value' => '$data[otp_required]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'OTP Required'),
							array('name' => 'otp_sent', 'value' => '$data[otp_sent]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'OTP Sent'),
							array('name' => 'otp_verified', 'value' => '$data[otp_verified]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'OTP Verified'),
							array('name' => 'total_served', 'value' => '$data[total_served]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Total Booking Served'),
					)));
				}
				?> 
            </div>  

        </div>  
    </div>
</div>


<script type="text/javascript">

    var start = '<?= date('d/m/Y', strtotime('-1 month')); ?>';
    var end = '<?= date('d/m/Y'); ?>';


    $('#OtpSearchDate').daterangepicker(
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
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                    'Last 2 Month': [moment().subtract(2, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                    'Last 6 Month': [moment().subtract(6, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                    'Last 12 Month': [moment().subtract(12, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                }
            }, function (start1, end1) {
        $('#BookingTrack_fromDate').val(start1.format('YYYY-MM-DD'));
        $('#BookingTrack_toDate').val(end1.format('YYYY-MM-DD'));
        $('#OtpSearchDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
    });
    $('#OtpSearchDate').on('cancel.daterangepicker', function (ev, picker) {
        $('#OtpSearchDate span').html('Select Booking Date Range');
        $('#BookingTrack_fromDate').val('');
        $('#BookingTrack_toDate').val('');
    });

</script>


