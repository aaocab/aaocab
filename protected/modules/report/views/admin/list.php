<?php
$pageno		 = filter_input(INPUT_GET, 'page');
?>
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
		?>
		<div class="row">

			<div class="col-xs-12 col-sm-4 col-md-4 form-group ">
				<?php
				$daterang	 = "Select Create  Date Range";
				$createdate1 = ($model->create_date1 == '') ? '' : $model->create_date1;
				$createdate2 = ($model->create_date2 == '') ? '' : $model->create_date2;
				if ($createdate1 != '' && $createdate2 != '')
				{
					$daterang = date('F d, Y', strtotime($createdate1)) . " - " . date('F d, Y', strtotime($createdate2));
				}
				?>
				<label  class="control-label">Create Date</label>
				<div id="bkgCreateDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
					<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
					<span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
				</div>
				<?php
				echo $form->hiddenField($model, 'create_date1');
				echo $form->hiddenField($model, 'create_date2');
				?>
			</div>

			<div class="col-xs-12 col-sm-3 col-md-2 pt15">
				<?php echo $form->checkboxGroup($model, 'rpe_isFile_created', ['label' => 'Show Only File Created', 'widgetOptions' => ['htmlOptions' => ['value' => 2]]]);
				?>
			</div>

		</div>

		<div class="row">
			<div class="col-xs-6 text-center">
				<div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-2 col-md-2 text-center mt20" style="padding: 4px;">
					<button class="btn btn-primary full-width" type="submit">Search</button>
				</div>
			</div>
		</div>
		<?php $this->endWidget(); ?>

    </div>


	<div class="col-xs-12">
		<?php
		if (!empty($dataProvider))
		{
			$dataProvider->getPagination()->params	 = array_filter($_GET + $_POST);
			$dataProvider->getSort()->params		 = array_filter($_GET + $_POST);

			$this->widget('booster.widgets.TbGridView', array(
				'responsiveTable'	 => true,
				'dataProvider'		 => $dataProvider,
				'id'				 => 'ConfigListGrid',
				'template'			 => "<div class='panel-heading'><div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
				'itemsCssClass'		 => 'table table-striped table-bordered mb0',
				'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
				'columns'			 => array(
					array('name' => 'rpe_file_name', 'value' => '$data[rpe_file_name]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'File Name'),
					array('name'	 => 'rpe_create_date',
						'value'	 => function ($data) {
							if ($data['rpe_create_date'] != '')
							{
								echo DateTimeFormat::DateTimeToLocale($data['rpe_create_date']);
							}
						}, 'sortable'			 => true, 'filter'			 => false, 'headerHtmlOptions'	 => array(), 'header'			 => 'Create Date'),
					array('name'	 => 'rpe_expiry_time',
						'value'	 => function ($data) {
							if ($data['rpe_expiry_time'] != '')
							{
								echo DateTimeFormat::DateTimeToLocale($data['rpe_expiry_time']);
							}
						}, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Expiry Date'),
					array('name'	 => 'rpe_isFile_created',
						'value'	 => function ($data) {
							if ($data['rpe_isFile_created'] == 1)
							{
								echo "File Not Created";
							}
							else
							{
								echo "File Created";
							}
						}, 'sortable'			 => false, 'filter'			 => false, 'headerHtmlOptions'	 => array(), 'header'			 => 'Is File Created'),
					array('name'	 => 'rpe_status',
						'value'	 => function ($data) {
							if ($data['rpe_status'] == 1)
							{
								echo "Active";
							}
							else
							{
								echo "Inactive";
							}
						},
						'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Status'),
					array(
						'header'			 => 'Action',
						'class'				 => 'CButtonColumn',
						'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
						'template'			 => '{download}',
						'buttons'			 => array(
							'download' => array(
								'url'		 => '$data["rpe_download_link"]', 'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\download.png',
								'visible'	 => '($data[rpe_isFile_created]==2)', 'label'		 => '<i class="fa fa-toggle-off"></i>', 'options'	 => array('data-toggle'	 => 'ajaxModal',
									'id'			 => 'rtgInactive',
									'style'			 => '',
									'rel'			 => 'popover',
									'data-placement' => 'left',
									'class'			 => 'btn btn-xs rtg_inactive p0',
									'title'			 => 'Download'),
							),
						))
			)));
		}
		?>


    </div>
</div>

<script>
    function ConfigListGrid()
    {
        $('#ConfigListGrid').yiiGridView('update');
    }

    $(document).ready(function () {
        var start = '<?= date('d/m/Y', strtotime('-1 month')); ?>';
        var end = '<?= date('d/m/Y'); ?>';

        $('#bkgCreateDate').daterangepicker(
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
            $('#ReportExport_create_date1').val(start1.format('YYYY-MM-DD'));
            $('#ReportExport_create_date2').val(end1.format('YYYY-MM-DD'));
            $('#bkgCreateDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
        });
        $('#bkgCreateDate').on('cancel.daterangepicker', function (ev, picker) {
            $('#bkgCreateDate span').html('Select Transaction Date Range');
            $('#ReportExport_create_date1').val('');
            $('#ReportExport_create_date2').val('');
        });

    });

</script>