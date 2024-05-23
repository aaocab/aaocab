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
					<label class="control-label">Received Date</label>
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

			<div class="col-xs-12 col-sm-2"> 
				<?= $form->textFieldGroup($model, 'phoneno', array('widgetOptions' => ['htmlOptions' => ['label' => 'Phone Number', 'placeholder' => 'Phone Number']])) ?>
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
//							if ($GLOBALS['checkContactAccess'])
//							{
//								$showNumber = ($data['whl_phone_number']);
//							}
//							else
//							{
//								$showNumber = Filter::maskPhoneNumber($data['whl_phone_number']);
//							}
							$showNumber = ($data['whl_phone_number']);
							echo $showNumber;
						}, 'sortable'								 => true,
						'headerHtmlOptions'						 => array('class' => ''),
						'header'								 => 'Phone No'),
					array('name'	 => 'whl_created_by_name', 'value'	 => function ($data) {
							return $data['whl_created_by_name'];
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-2'),
						'header'			 => 'Name'),
						
					array('name'	 => 'whl_message', 'value'	 => function ($data) {
							return $data['whl_message'];
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => ''),
						'header'			 => 'Message'),
					
					array('name'	 => 'whl_created_date', 'value'	 => function ($data) {
							if (!empty($data['whl_created_date']))
							{
								echo DateTimeFormat::DateTimeToLocale($data['whl_created_date']);
							}
						}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => ''),
						'header'			 => 'Received On'),
				
					array(
						'header'			 => 'Action',
						'class'				 => 'CButtonColumn',
						'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
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
								'url'		 => 'Yii::app()->createUrl("report/notification/ShowWhatsappMsg", array("whlId" => $data["whl_id"]))',
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
        var start = '<?= date('d/m/Y', strtotime('0 Day')); ?>';
        var end = '<?= date('d/m/Y'); ?>';

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
        
    });

</script>