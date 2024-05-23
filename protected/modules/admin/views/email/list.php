<div class="row">
    <div class="col-xs-12">
		<?php
		$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'email-form', 'enableClientValidation' => true,
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
        <div class="well pb20">
            <div class="col-xs-12 col-sm-6 col-md-3"> 
				<?= $form->textFieldGroup($model, 'elg_address', array('widgetOptions' => ['htmlOptions' => []])) ?>
            </div><div class="col-xs-12 col-sm-6 col-md-3"> 
				<?= $form->textFieldGroup($model, 'elg_subject', array('widgetOptions' => ['htmlOptions' => []])) ?>
            </div><div class="col-xs-12 col-sm-6 col-md-3"> 
				<?= $form->textFieldGroup($model, 'elg_booking_id', array('widgetOptions' => ['htmlOptions' => []])) ?>
            </div><div class="col-xs-12 col-sm-6 col-md-3"> 
				<?= $form->textFieldGroup($model, 'elg_content', array('widgetOptions' => ['htmlOptions' => []])) ?>
            </div>
			<div class="col-xs-12 col-sm-3 col-md-3">
				<div class="form-group">
					<label class="control-label">Send Date</label>
					<?php
					$daterang	 = "Select Send Date Range";
					$sendDate1	 = ($model->sendDate1 == '') ? '' : $model->sendDate1;
					$sendDate2	 = ($model->sendDate2 == '') ? '' : $model->sendDate2;
					if ($sendDate1 != '' && $sendDate2 != '')
					{
						$daterang = date('F d, Y', strtotime($sendDate1)) . " - " . date('F d, Y', strtotime($sendDate2));
					}
					?>
					<div id="sendDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
						<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
						<span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
					</div>
					<?= $form->hiddenField($model, 'sendDate1'); ?>
					<?= $form->hiddenField($model, 'sendDate2'); ?>

				</div>
			</div>
            <div class="col-xs-12 col-md-2 mt20 mb10 text-center">
                <button class="btn btn-primary" type="submit" style="width: 185px;"  name="bookingSearch">Search</button>
            </div>
        </div>
		<?php $this->endWidget(); ?>
    </div>
    <div class="col-xs-12">
		<?php
		if (!empty($dataProvider))
		{
			$GLOBALS['checkContactAccess']	 = Yii::app()->user->checkAccess("bookingContactAccess");
			$params									 = array_filter($_REQUEST);
			$dataProvider->getPagination()->params	 = $params;
			$dataProvider->getSort()->params		 = $params;
			$this->widget('booster.widgets.TbGridView', array(
				'responsiveTable'	 => true,
				'dataProvider'		 => $dataProvider,
				'template'			 => "<div class='panel-heading'><div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
				'itemsCssClass'		 => 'table table-striped table-bordered mb0',
				'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
				//         'ajaxType' => 'POST',
				'columns'			 => array(
					array('name' => 'address', 'value' => function($data)
					{
						if($GLOBALS['checkContactAccess']==true)
						{
							$showEmail =  ($data['elg_address']);
						}else
						{	
							$showEmail =  Filter::maskEmalAddress($data['elg_address']);
						}
						echo $showEmail;
					}
					, 'sortable' => true
					, 'headerHtmlOptions' => array()
					, 'header' => 'Address'),
					array('name' => 'subject', 'value' => '$data["elg_subject"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Subject'),
					array('name'	 => 'body', 'value'	 => function($data) {
							if ($data['elg_content'] != '')
							{
								echo substr(strip_tags($data['elg_content']), 0, 150) . "...";
							}
						}, 'headerHtmlOptions'	 => array(), 'header'			 => 'Body'),
					array('name' => 'booking_id', 'value' => '$data["elg_booking_id"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Booking ID'),
					array('name'	 => 'recipient',
						'value'	 => function ($data) {
							if ($data['elg_recipient'] != '')
							{
								echo EmailLog::model()->getRecipientName($data['elg_recipient']);
							}
						},
						'sortable'			 => false, 'headerHtmlOptions'	 => array(), 'header'			 => 'Recipient'),
					array('name' => 'delivered', 'value' => '$data["elg_delivered"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Response'),
					array('name'	 => 'created',
						'value'	 => function ($data) {
							return DateTimeFormat::DateTimeToLocale($data['elg_created']);
						},
						'sortable'			 => true, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Date Sent'),
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
                                            title: \' Email Content \',
                                            size: \'large\',
                                            onEscape: function () {

                                                // user pressed escape
                                            }
                                        });
                                    }
                                });
                                    return false;
                                    }',
								'url'		 => 'Yii::app()->createUrl("admin/email/showEmail", array("elgId" => $data["elg_id"]))',
								'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\vendor\show_log.png',
								'label'		 => '<i class="fa fa-list"></i>',
								'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'class' => 'btn btn-xs conshowlog p0', 'title' => 'Show Email'),
							),
							'htmlOptions'	 => array('class' => 'center'),
						))
			)));
		}
		?>
    </div>
</div>
<script>

	$(document).ready(function () {
		var start = '<?= date('d/m/Y', strtotime('-1 Day')); ?>';
		var end = '<?= date('d/m/Y'); ?>';

		$('#sendDate').daterangepicker(
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
			$('#EmailLog_sendDate1').val(start1.format('YYYY-MM-DD'));
			$('#EmailLog_sendDate2').val(end1.format('YYYY-MM-DD'));
			$('#sendDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
		});
		$('#sendDate').on('cancel.daterangepicker', function (ev, picker) {
			$('#sendDate span').html('Select Send Date Range');
			$('#EmailLog_sendDate1').val('');
			$('#EmailLog_sendDate2').val('');
		});
	});
</script>