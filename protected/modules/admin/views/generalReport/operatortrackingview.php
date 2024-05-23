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
				'id'					 => 'mmtReports-form', 'enableClientValidation' => true,
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
				<?php
				if (!empty($dataProvider))
				{

					$this->widget('booster.widgets.TbGridView', array(
						'id'				 => 'oat-grid',
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
							array('name'				 => 'id', 'sortable'			 => true, 'headerHtmlOptions'	 => array(),
								'value'				 => function ($data) {
									echo CHtml::link($data['oat_id'], Yii::app()->createUrl("admin/xyz/operatorTrackingDetails", ["oatId" => $data['oat_id'], "bkgId" => $data['oat_booking_id']]), ["class" => "viewBooking", "onclick" => "", 'target' => '_blank']);
								}, 'header' => 'Tracking Id'),
							array('name'				 => 'type', 'sortable'			 => true, 'headerHtmlOptions'	 => array(),
								'value'				 => function ($data) {
									$event = OperatorApiTracking::model()->getEventTypeById($data['oat_type']);
									echo $event;
								}, 'header'			 => 'Type'),
							array('name' => 'createdDate', 'value' => '$data["oat_created_at"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Created Date'),
							array('name' => 'errorType', 'value' => '$data["oat_error_type"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Error Type'),
							array('name' => 'errorMsg', 'value' => '$data["oat_error_msg"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Error Message'),
							array('name' => 'requestTime', 'value' => '$data["oat_request_time"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Request Time'),
							array('name' => 'status', 'value' => '$data["oat_status"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Status'),
					)));
				}
				?>
			</div>

<?php $this->endWidget(); ?>
		</div>
	</div>
</div>
