<div class="row">
    <div class="col-xs-12">        
		<?php
		$form			 = $this->beginWidget('booster.widgets.TbActiveForm', array(
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
		<div class="row"> 
			<div class="col-xs-12 col-sm-3">
				<div class="form-group">
					<label class="control-label">Create Date</label>
					<?php
					$daterang		 = "Select Date Range";
					$whl_created_on1 = ($model->whl_created_on1 == '') ? '' : $model->whl_created_on1;
					$whl_created_on2 = ($model->whl_created_on2 == '') ? '' : $model->whl_created_on2;
					if ($whl_created_on1 != '' && $whl_created_on2 != '')
					{
						$daterang = date('F d, Y', strtotime($whl_created_on1)) . " - " . date('F d, Y', strtotime($whl_created_on2));
					}
					?>
					<div id="whlCreatedOn" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
						<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
						<span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
					</div>
					<?= $form->hiddenField($model, 'whl_created_on1'); ?>
					<?= $form->hiddenField($model, 'whl_created_on2'); ?>

				</div>
			</div>

			<div class="col-xs-12 col-sm-3" style="">
				<div class="form-group">
					<label class="control-label">Send Date</label>
					<?php
					$sendDateRange	 = "Select Date Range";
					$sendDate1		 = ($model->sendDate1 == '') ? '' : $model->sendDate1;
					$sendDate2		 = ($model->sendDate2 == '') ? '' : $model->sendDate2;
					if ($sendDate1 != '' && $sendDate2 != '')
					{
						$sendDateRange = date('F d, Y', strtotime($sendDate1)) . " - " . date('F d, Y', strtotime($sendDate2));
					}
					?>
					<div id="whlSendOn" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
						<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
						<span style="min-width: 240px"><?= $sendDateRange ?></span> <b class="caret"></b>
					</div>
					<?= $form->hiddenField($model, 'sendDate1'); ?>
					<?= $form->hiddenField($model, 'sendDate2'); ?>

				</div>
			</div>

			<div class="col-xs-12 col-sm-3" style="">
				<div class="form-group">
					<label class="control-label">Delivery Date</label>
					<?php
					$devDateRang	 = "Select Date Range";
					$deliveryDate1	 = ($model->deliveryDate1 == '') ? '' : $model->deliveryDate1;
					$deliveryDate2	 = ($model->deliveryDate2 == '') ? '' : $model->deliveryDate2;
					if ($deliveryDate1 != '' && $deliveryDate2 != '')
					{
						$devDateRang = date('F d, Y', strtotime($deliveryDate1)) . " - " . date('F d, Y', strtotime($deliveryDate2));
					}
					?>
					<div id="whlDeliveryOn" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
						<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
						<span style="min-width: 240px"><?= $devDateRang ?></span> <b class="caret"></b>
					</div>
					<?= $form->hiddenField($model, 'deliveryDate1'); ?>
					<?= $form->hiddenField($model, 'deliveryDate2'); ?>

				</div>
			</div>

			<div class="col-xs-12 col-sm-3" style="">
				<div class="form-group">
					<label class="control-label">Read Date</label>
					<?php
					$readDateRang	 = "Select Date Range";
					$readDate1		 = ($model->readDate1 == '') ? '' : $model->readDate1;
					$readDate2		 = ($model->readDate2 == '') ? '' : $model->readDate2;
					if ($readDate1 != '' && $readDate2 != '')
					{
						$readDateRang = date('F d, Y', strtotime($readDate1)) . " - " . date('F d, Y', strtotime($readDate2));
					}
					?>
					<div id="whlReadOn" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
						<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
						<span style="min-width: 240px"><?= $readDateRang ?></span> <b class="caret"></b>
					</div>
					<?= $form->hiddenField($model, 'readDate1'); ?>
					<?= $form->hiddenField($model, 'readDate2'); ?>

				</div>
			</div>



		</div>
		<div class="row">

			<div class="col-xs-12 col-sm-2"> 
				<?= $form->textFieldGroup($model, 'phoneno', array('widgetOptions' => ['htmlOptions' => ['label' => 'Phone Number', 'placeholder' => 'Phone Number']])) ?>
			</div>

			<div class="col-xs-12 col-sm-2" style="">
				<div class="form-group">
					<label class="control-label">Sent By/ Received From</label>
					<?php
					$statusJson	 = Filter::getJSON($model->createdByType);
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $model,
						'attribute'		 => 'whl_created_by_type',
						'val'			 => explode(",", $model->whl_created_by_type),
						'asDropDownList' => FALSE,
						'options'		 => array('data' => new CJavaScriptExpression($statusJson), 'allowClear' => true, 'multiple' => true),
						'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Status', 'multiple' => 'multiple',)
					));
					?>
				</div>
			</div>

			<div class="col-xs-12 col-sm-3" style="">
				<div class="form-group">
					<label class="control-label">Template Name</label>
					<?php
					$statusJson	 = WhatsappLog::getJsonTemplateName();
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $model,
						'attribute'		 => 'templatename',
						'val'			 => $model->templatename,
						'asDropDownList' => FALSE,
						'options'		 => array('data' => new CJavaScriptExpression($statusJson), 'allowClear' => true),
						'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Status', 'multiple' => 'multiple')
					));
					?>
				</div>
			</div>

			<div class="col-xs-12 col-sm-2" style="">
				<div class="form-group">
					<label class="control-label">Ref Type</label>
					<?php
					$data		 = WhatsappLog::model()->getJSONAllRefType();

					$this->widget('booster.widgets.TbSelect2', array(
						'attribute'		 => 'whl_ref_type',
						'model'			 => $model,
						'value'			 => $model->whl_ref_type,
						'asDropDownList' => FALSE,
						'options'		 => array('data' => new CJavaScriptExpression($data), 'allowClear' => true),
						'htmlOptions'	 => array(
							'style'			 => 'width:100%', 'placeholder'	 => 'Select Ref Type')
					));
					?>
				</div>
			</div> 
			<div class="col-xs-12 col-sm-2"> 
				<?= $form->textFieldGroup($model, 'whl_ref_id', array('widgetOptions' => ['htmlOptions' => ['placeholder' => 'Id']])) ?>
			</div>

			<div class="col-xs-12 col-sm-2" style="">
				<div class="form-group">
					<label class="control-label">Status</label>
					<?php
					$statusJson = Filter::getJSON($model->status);
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $model,
						'attribute'		 => 'whl_status',
						'val'			 => explode(",", $model->whl_status),
						'asDropDownList' => FALSE,
						'options'		 => array('data' => new CJavaScriptExpression($statusJson), 'allowClear' => true, 'multiple' => true),
						'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Status', 'multiple' => 'multiple',)
					));
					?>
				</div>
			</div>

			<div class="col-xs-12 col-sm-3 mt20">   
				<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary')); ?>
			</div>
		</div>

		<?php $this->endWidget(); ?>
		<BR>
		<?php
		if (!empty($dataProvider))
		{
			$GLOBALS['checkContactAccess']			 = Yii::app()->user->checkAccess("bookingContactAccess");
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
				'columns'			 => array(
					array('name'	 => 'whl_phone_number', 'value'	 => function ($data) {
							if ($GLOBALS['checkContactAccess'])
							{
								$showNumber = ($data['whl_phone_number']);
							}
							else
							{
								$showNumber = Filter::maskPhoneNumber($data['whl_phone_number']);
							}
							echo $showNumber;
						}, 'sortable'								 => true,
						'headerHtmlOptions'						 => array('class' => ''),
						'header'								 => 'Phone No'),
					array('name'	 => 'whl_entity_type_name', 'value'	 => function ($data) {
							echo $data['whl_entity_type_name'];
						}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => ''), 'header'			 => 'Entity Type'),
					array('name'	 => 'whl_entity_id', 'value'	 => function ($data) {
							switch ($data['whl_entity_type'])
							{
								case UserInfo::TYPE_CONSUMER:
									echo CHtml::link($data['whl_entity_id'], Yii::app()->createUrl("admin/user/view", ["id" => $data['whl_entity_id']]), ["class" => "", "onclick" => "", 'target' => '_blank']);
									break;
								case UserInfo::TYPE_VENDOR:
									echo CHtml::link($data['whl_entity_id'], Yii::app()->createUrl("admin/vendor/view", ["id" => $data['whl_entity_id']]), ["class" => "", "onclick" => "", 'target' => '_blank']);
									break;
								case UserInfo::TYPE_DRIVER:
									echo CHtml::link($data['whl_entity_id'], Yii::app()->createUrl("admin/driver/view", ["id" => $data['whl_entity_id']]), ["class" => "", "onclick" => "", 'target' => '_blank']);
									break;

								default:
									echo $data['whl_entity_id'];
									break;
							}
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => ''),
						'header'			 => 'Entity id'),
					array('name'	 => 'whl_ref_type_name', 'value'	 => function ($data) {
							echo $data['whl_ref_type_name'];
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => ''),
						'header'			 => 'Ref Type'),
					array('name'	 => 'whl_ref_id', 'value'	 => function ($data) {
							switch ($data['whl_ref_type'])
							{
								case 1;
									echo CHtml::link($data['whl_ref_id'], Yii::app()->createUrl("admin/booking/view", ["id" => $data['bkg_id']]), ["class" => "viewBooking", "onclick" => "", 'target' => '_blank']);
									break;

								case 2;
									echo CHtml::link($data['whl_ref_id'], Yii::app()->createUrl("admin/booking/view", ["id" => $data['bkg_id']]), ["class" => "viewBooking", "onclick" => "", 'target' => '_blank']);
									break;
								default:
									echo $data['whl_ref_id'];
									break;
							}
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => ''),
						'header'			 => 'Ref Id'),
					array('name'	 => 'wht_template_name', 'value'	 => function ($data) {
							echo ucfirst(str_replace('_', ' ', $data['wht_template_name']));
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => ''),
						'header'			 => 'Template Name'),
					array('name'	 => 'whl_created_by_type', 'value'	 => function ($data) {
							echo $data['whl_created_by_type'];
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => ''),
						'header'			 => 'Sent By/ Received From'),
					array('name'	 => 'whl_status', 'value'	 => function ($data) {
							echo $data['whl_status'];
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => ''),
						'header'			 => 'Status'),
					array('name'	 => 'whl_created_date', 'value'	 => function ($data) {
							if (!empty($data['whl_created_date']))
							{
								echo DateTimeFormat::DateTimeToLocale($data['whl_created_date']);
							}
						}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => ''),
						'header'			 => 'Created On'),
					array('name'	 => 'whl_sent_date', 'value'	 => function ($data) {
							if (!empty($data['whl_sent_date']))
							{
								echo DateTimeFormat::DateTimeToLocale($data['whl_sent_date']);
							}
						}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => ''),
						'header'			 => 'Send Date'),
					array('name'	 => 'whl_delivered_date', 'value'	 => function ($data) {
							if (!empty($data['whl_delivered_date']))
							{
								echo DateTimeFormat::DateTimeToLocale($data['whl_delivered_date']);
							}
						}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => ''),
						'header'			 => 'Delivery Date'),
					array('name'	 => 'whl_read_date', 'value'	 => function ($data) {
							if (!empty($data['whl_read_date']))
							{
								echo DateTimeFormat::DateTimeToLocale($data['whl_read_date']);
							}
						}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => ''),
						'header'			 => 'Read Date'),
					array(
						'header'			 => 'Action',
						'class'				 => 'CButtonColumn',
						'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
						'template'			 => '{view}',
						'buttons'			 => array(
							'view'			 => array(
								'click'		 => 'function(){
                                    $href = $(this).attr(\'href\');
                                    jQuery.ajax({type: \'GET\',
                                    url: $href,
                                    success: function (data)
                                    {
                                        var box = bootbox.dialog(
										{
                                            message: data,
                                            title: \' Whatsapp Content \',
                                            size: \'large\',
                                            onEscape: function () {

                                                // user pressed escape
                                            }
                                        });
                                    }
                                });
                                    return false;
                                    }',
								'url'		 => 'Yii::app()->createUrl("report/notification/ShowMsg", array("whlId" => $data["whl_id"]))',
								'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\vendor\show_log.png',
								'label'		 => '<i class="fa fa-list"></i>',
								'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'class' => 'btn btn-xs conshowlog p0', 'title' => 'Show Message'),
							),
							'htmlOptions'	 => array('class' => 'center'),
						))
			)));
		}
		?> 

	</div>  
</div>
<script type="text/javascript">
	$(document).ready(function () {
		var start = '<?= date('d/m/Y', strtotime('-10 Day')); ?>';
		var end = '<?= date('d/m/Y'); ?>';

		var sendstart = '<?= date('d/m/Y', strtotime('-10 Day')); ?>';
		var sendend = '<?= date('d/m/Y'); ?>';

		var deliverystart = '<?= date('d/m/Y', strtotime('-10 Day')); ?>';
		var deliveryend = '<?= date('d/m/Y'); ?>';

		var readDatestart = '<?= date('d/m/Y', strtotime('-10 Day')); ?>';
		var readDateend = '<?= date('d/m/Y'); ?>';

		$('#whlCreatedOn').daterangepicker(
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
						'Last 30 Days': [moment().subtract(29, 'days'), moment()],
						'This Month': [moment().startOf('month'), moment().endOf('month')],
						'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
					}
				}, function (start1, end1) {
			$('#WhatsappLog_whl_created_on1').val(start1.format('YYYY-MM-DD'));
			$('#WhatsappLog_whl_created_on2').val(end1.format('YYYY-MM-DD'));
			$('#whlCreatedOn span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
		});
		$('#whlCreatedOn').on('cancel.daterangepicker', function (ev, picker) {
			$('#whlCreatedOn span').html('Select Send Date Range');
			$('#WhatsappLog_whl_created_on1').val('');
			$('#WhatsappLog_whl_created_on2').val('');
		});

		$('#whlSendOn').daterangepicker(
				{
					locale: {
						format: 'DD/MM/YYYY',
						cancelLabel: 'Clear'
					},
					"showDropdowns": true,
					"alwaysShowCalendars": true,
					startDate: sendstart,
					endDate: sendend,
					ranges: {
						'Today': [moment(), moment()],
						'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
						'Last 7 Days': [moment().subtract(6, 'days'), moment()],
						'Last 30 Days': [moment().subtract(29, 'days'), moment()],
						'This Month': [moment().startOf('month'), moment().endOf('month')],
						'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
					}
				}, function (sendstart1, sendend1) {
			$('#WhatsappLog_sendDate1').val(sendstart1.format('YYYY-MM-DD'));
			$('#WhatsappLog_sendDate2').val(sendend1.format('YYYY-MM-DD'));
			$('#whlSendOn span').html(sendstart1.format('MMMM D, YYYY') + ' - ' + sendend1.format('MMMM D, YYYY'));
		});

		$('#whlSendOn').on('cancel.daterangepicker', function (ev, picker) {
			$('#whlSendOn span').html('Select Send Date Range');
			$('#WhatsappLog_sendDate1').val('');
			$('#WhatsappLog_sendDate2').val('');
		});

		$('#whlDeliveryOn').daterangepicker(
				{
					locale: {
						format: 'DD/MM/YYYY',
						cancelLabel: 'Clear'
					},
					"showDropdowns": true,
					"alwaysShowCalendars": true,
					startDate: deliverystart,
					endDate: deliveryend,
					ranges: {
						'Today': [moment(), moment()],
						'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
						'Last 7 Days': [moment().subtract(6, 'days'), moment()],
						'Last 30 Days': [moment().subtract(29, 'days'), moment()],
						'This Month': [moment().startOf('month'), moment().endOf('month')],
						'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
					}
				}, function (deliverystart1, deliveryend1) {
			$('#WhatsappLog_deliveryDate1').val(deliverystart1.format('YYYY-MM-DD'));
			$('#WhatsappLog_deliveryDate2').val(deliveryend1.format('YYYY-MM-DD'));
			$('#whlDeliveryOn span').html(deliverystart1.format('MMMM D, YYYY') + ' - ' + deliveryend1.format('MMMM D, YYYY'));
		});

		$('#whlDeliveryOn').on('cancel.daterangepicker', function (ev, picker) {
			$('#whlDeliveryOn span').html('Select Send Date Range');
			$('#WhatsappLog_deliveryDate1').val('');
			$('#WhatsappLog_deliveryDate2').val('');
		});

		$('#whlReadOn').daterangepicker(
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
						'Last 30 Days': [moment().subtract(29, 'days'), moment()],
						'This Month': [moment().startOf('month'), moment().endOf('month')],
						'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
					}
				}, function (start1, end1) {
			$('#WhatsappLog_readDate1').val(start1.format('YYYY-MM-DD'));
			$('#WhatsappLog_readDate2').val(end1.format('YYYY-MM-DD'));
			$('#whlReadOn span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
		});

		$('#whlReadOn').on('cancel.daterangepicker', function (ev, picker) {
			$('#whlReadOn span').html('Select Send Date Range');
			$('#WhatsappLog_readDate1').val('');
			$('#WhatsappLog_readDate2').val('');
		});
	});

</script>