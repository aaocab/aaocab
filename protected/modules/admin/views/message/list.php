<div class="row">

    <div class="col-xs-12">
		<?php
		$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'sms-form', 'enableClientValidation' => true,
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
            <div class="col-xs-6 col-md-3"> 
				<?= $form->textFieldGroup($model, 'number', array('widgetOptions' => ['htmlOptions' => []])) ?>
            </div><div class="col-xs-6 col-md-3"> 
				<?= $form->textFieldGroup($model, 'message', array('widgetOptions' => ['htmlOptions' => []])) ?>
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
			<div class="col-xs-6 col-md-3"> 
				<?= $form->textFieldGroup($model, 'booking_id', array('widgetOptions' => ['htmlOptions' => []])) ?>
            </div>
            <div class="col-xs-12 text-center mb20">
                <button class="btn btn-primary" type="submit" style="width: 185px;"  name="bookingSearch">Search</button>
            </div>
        </div>


		<?php $this->endWidget(); ?>
    </div>

	<div class="col-xs-12">
		<?php
		if (!empty($dataProvider))
		{
			$GLOBALS['checkContactAccess'] = Yii::app()->user->checkAccess("bookingContactAccess");

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
				//    'ajaxType' => 'POST',
				'columns'			 => array
					(
					array('name'	 => 'number', 'value'	 => function ($data) {
							if ($GLOBALS['checkContactAccess'] == true)
							{
								$showNumber = ($data['number']);
							}
							else
							{
								$showNumber = Filter::maskPhoneNumber($data['number']);
							}
							echo $showNumber;
						}
						, 'sortable'			 => true
						, 'headerHtmlOptions'	 => array()
						, 'header'			 => 'Number'),
					array('name'	 => 'message',
						'value'	 => function ($data) {//'$data[message]',
//						 
							if ($data['otpIndex'] > 0 && !$GLOBALS['checkContactAccess'])
							{
								if ($data['otpIndex'] <= 20)
								{
									echo preg_replace("/[0-9]{4}/", "****", $data['message']);
								}
								else
								{
									echo substr($data['message'], 0, $data['otpIndex']) . preg_replace("/[0-9]{6}/", "******", substr($data['message'], $data['otpIndex']));
								}
							}
							else
							{
								echo $data['message'];
							}
						}
						,
						'headerHtmlOptions'	 => array(), 'header'			 => 'Message'),
					array('name' => 'booking_id', 'value' => '$data[booking_id]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Booking ID'),
					array('name'	 => 'recipient',
						'value'	 => function ($data) {
							if ($data['recipient'] != '')
							{
								echo SmsLog::model()->getRecipient($data['recipient']);
							}
						},
						'sortable'			 => false, 'headerHtmlOptions'	 => array(), 'header'			 => 'Recipient'),
					array('name'	 => 'delivery_response',
						'value'	 => function ($data) {
							if ($data['delivery_response'] != '')
							{
								return wordwrap($data['delivery_response'], 20, "<br />\n");
							}
						},
						'sortable'			 => false, 'headerHtmlOptions'	 => array(), 'header'			 => 'Response'),
					array('name'	 => 'date_sent',
						'value'	 => function ($data) {

							return DateTimeFormat::DateTimeToLocale($data['date_sent']);
						},
						'sortable'			 => true, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Date Sent'),
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
			$('#SmsLog_sendDate1').val(start1.format('YYYY-MM-DD'));
			$('#SmsLog_sendDate2').val(end1.format('YYYY-MM-DD'));
			$('#sendDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
		});
		$('#sendDate').on('cancel.daterangepicker', function (ev, picker) {
			$('#sendDate span').html('Select Send Date Range');
			$('#SmsLog_sendDate1').val('');
			$('#SmsLog_sendDate2').val('');
		});
	});







</script>