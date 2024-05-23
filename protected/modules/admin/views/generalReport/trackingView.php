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
		'id' => 'mmtReports-form', 'enableClientValidation' => true,
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
		
    <div class="col-xs-12">
		<?php
		if (!empty($dataProvider))
		{
			if ($bModel->bkg_agent_id == 18190)
			{
				$this->widget('booster.widgets.TbGridView', array(
					'id'				 => 'agt-grid',
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
						array('name' => 'id', 'sortable' => true, 'headerHtmlOptions'	 => array(),
						'value'	 => function($data) {
							echo CHtml::link($data['aat_id'], Yii::app()->createUrl("admin/generalReport/agentTrackingDetails", ["aatId" => $data['aat_id'], "bkgId" => $data['aat_booking_id']]), ["class" => "viewBooking", "onclick" => "", 'target' => '_blank']);
                        },'header' => 'Tracking Id'),
					//array('name' => 'id', 'value' => '$data["aat_id"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Tracking Id'),
					array('name' => 'type', 'sortable' => true, 'headerHtmlOptions'	 => array(),
						'value'	 => function($data) {
							$event = AgentApiTracking::model()->getEventTypeById($data['aat_type']);
							echo $event;
						},'header' => 'Type'),
					array('name' => 'createdDate', 'value' => '$data["aat_created_at"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Created Date'),
					array('name' => 'errorType', 'value' => '$data["aat_error_type"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Error Type'),
					array('name' => 'errorMsg', 'value' => '$data["aat_error_msg"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Error Message'),
					array('name' => 'requestTime', 'value' => '$data["aat_request_time"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Request Time'),
					array('name' => 'status', 'value' => '$data["aat_status"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Status'),
					)));
			}
			else
			{
				$this->widget('booster.widgets.TbGridView', array(
					'id'				 => 'pat-grid',
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
					array('name' => 'id', 'sortable' => true, 'headerHtmlOptions'	 => array(),
						'value'	 => function($data) {
							echo CHtml::link($data['pat_id'], Yii::app()->createUrl("admin/generalReport/patTrackingDetails", ["patId" => $data['pat_id'], "bkgId" => $data['pat_booking_id']]), ["class" => "viewBooking", "onclick" => "", 'target' => '_blank']);

						},'header' => 'Tracking Id'),
					array('name' => 'type', 'sortable' => true, 'headerHtmlOptions'	 => array(),
						'value'	 => function($data) {
							$event = PartnerApiTracking::model()->getEventTypeById($data['pat_type']);
							echo $event;
						},'header' => 'Type'),
					array('name' => 'createdDate', 'value' => '$data["pat_created_at"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Created Date'),
					array('name' => 'errorType', 'value' => '$data["pat_error_type"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Error Type'),
					array('name' => 'errorMsg', 'value' => '$data["pat_error_msg"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Error Message'),
					array('name' => 'requestTime', 'value' => '$data["pat_request_time"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Request Time'),
					array('name' => 'status', 'value' => '$data["pat_status"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Status'),
					
						)));
			}
		}
		?>
    </div>
	
	<?php $this->endWidget(); ?>
  </div>
	</div>
</div>
