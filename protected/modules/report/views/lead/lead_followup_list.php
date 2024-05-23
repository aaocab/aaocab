<div class='row p15'>
	<?php
	$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
		'id'					 => 'leadFolloupForm',
		'enableClientValidation' => true,
		//		'method'				 => 'post',
		'clientOptions'			 => array(
			'validateOnSubmit'	 => true,
			'errorCssClass'		 => 'has-error'
		),
		'enableAjaxValidation'	 => false,
		'errorMessageCssClass'	 => 'help-block',
		'htmlOptions'			 => array(
			'class' => '',
		),
	));
	/* @var $form TbActiveForm */
	?>
	<div class="col-xs-6 col-sm-4 col-md-4" style="">
		<div class="form-group">
			<label class="control-label">Date Range</label>
			<?php
			$daterang	 = "Select Date Range";
			$from_date	 = ($model->lfu_from_date == '') ? '' : $model->lfu_from_date;
			$to_date	 = ($model->lfu_to_date == '') ? '' : $model->lfu_to_date;
			if ($from_date != '' && $to_date != '')
			{
				$daterang = date('F d, Y', strtotime($from_date)) . " - " . date('F d, Y', strtotime($to_date));
			}
			?>
			<div id="leadFollowupDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
				<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
				<span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
			</div>

		</div>
		<?= $form->hiddenField($model, 'lfu_from_date'); ?>
		<?= $form->hiddenField($model, 'lfu_to_date'); ?>

	</div>

	<div class="col-xs-12 col-sm-2 col-md-2">   
		<label class="control-label"></label>
		<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary full-width submitCbr')); ?>
	</div>
</div>


<?php $this->endWidget(); ?>
<div>
	<div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-2 col-md-2 mt5">
		<?php
		$checkExportAccess = false;
		if ($roles['rpt_export_roles'] != null)
		{
			$checkExportAccess = Filter::checkACL($roles['rpt_export_roles']);
		}
		if ($checkExportAccess)
		{
			echo CHtml::beginForm(Yii::app()->createUrl('report/lead/autoLeadFollowup'), "post", ['style' => "margin-bottom: 10px;margin-top: 10px;"]);
			?>
			<input type="hidden" id="from_date" name="from_date" value="<?= $model->lfu_from_date; ?>"/>
			<input type="hidden" id="to_date" name="to_date" value="<?= $model->lfu_to_date; ?>"/>
			<input type="hidden" id="export" name="export" value="true"/>
			<button class="btn btn-default" type="submit" style="width: 185px;">Export</button>
			<?php
			echo CHtml::endForm();
		}
		?>
	</div>
</div>


<div class="row">
    <div class="col-xs-12">
		<?php
		if (!empty($dataProvider))
		{
			$params									 = array_filter($_REQUEST);
			$dataProvider->getPagination()->params	 = $params;
			$dataProvider->getSort()->params		 = $params;
			$this->widget('booster.widgets.TbGridView', array(
				'responsiveTable'	 => true,
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
					array('name'				 => 'lfu_create_date', 'value'				 => $data['lfu_create_date'], 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Create Date'),
					array('name'				 => 'lfu_followup', 'value'				 => $data['lfu_followup'], 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Followup'),
					array('name'				 => 'lfu_id', 'value'				 => $data['lfu_id'], 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Lfu Id'),
					array('name'				 => 'lfu_ref_type', 'value'				 => $data['lfu_ref_type'], 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Ref Type'),
					array('name'				 => 'lfu_ref_id', 'value'				 => $data['lfu_ref_id'], 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Ref Id'),
					array('name'				 => 'lfu_comment', 'value'				 => $data['lfu_comment'], 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Comment'),
					array('name'				 => 'lfu_tellus', 'value'				 => $data['lfu_tellus'], 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Tellus'),
					array('name'				 => 'lfu_type', 'value'				 => $data['lfu_type'], 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Type'),
					array('name'				 => 'lfu_status', 'value'				 => $data['lfu_status'], 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Status'),
					array('name'	 => 'bkg_contact_no', 'value'	 => function ($data) {

							$data['bkg_country_code'] . " " . $data['bkg_contact_no'];
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Contact Number'),
					array('name'				 => 'bkg_user_email', 'value'				 => $data['bkg_user_email'], 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'User Email'),
					array('name'				 => 'bkg_booking_id', 'value'				 => $data['bkg_booking_id'], 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Booking Id'),
					array('name'				 => 'bkg_pickup_date', 'value'				 => $data['bkg_pickup_date'], 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Pickup Date'),
			)));
		}
		?>
    </div>
</div>


<script type="text/javascript">

    $(document).ready(function () {

		var start = '<?= ($model->lfu_from_date == '') ? date('d/m/Y', strtotime("-1 month", time())) : date('d/m/Y', strtotime($model->lfu_from_date)); ?>';
        var end = '<?= ($model->lfu_to_date == '') ? date('d/m/Y') : date('d/m/Y', strtotime($model->lfu_to_date)); ?>';


        $('#leadFollowupDate').daterangepicker(
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
            $('#LeadFollowup_lfu_from_date').val(start1.format('YYYY-MM-DD'));
            $('#LeadFollowup_lfu_to_date').val(end1.format('YYYY-MM-DD'));
            $('#leadFollowupDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
        });
        $('#leadFollowupDate').on('cancel.daterangepicker', function (ev, picker) {
            $('#leadFollowupDate span').html('Select Transaction Date Range');
            $('#LeadFollowup_lfu_from_date').val('');
            $('#LeadFollowup_lfu_to_date').val('');
        });

    });

</script>