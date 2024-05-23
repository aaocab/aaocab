<?php
$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/jquery.mask.min.js');
Yii::app()->clientScript->registerCssFile(ASSETS_URL . '/plugins/form-select2/select2.css');
/** @var Booking $model */
?>
<div class="row">
    <div class="col-xs-12">
		<?php
		/* @var $model Vendors */
		$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'getassignments', 'enableClientValidation' => true,
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
		<div class="row">
			<div class="col-xs-12 col-sm-2 col-md-2 col-lg-1" >
				<div class="form-group"> 
					<label class="control-label">Group by</label><br>
					<select class="form-control" name="Booking[groupvar]">
						<option value="hour" <?php echo ($orderby == 'hour') ? 'selected' : '' ?>>Hour</option>
						<option value="date" <?php echo ($orderby == 'date') ? 'selected' : '' ?>>Day</option>
						<option value="week" <?php echo ($orderby == 'week') ? 'selected' : '' ?>>Week</option>
						<option value="month" <?php echo ($orderby == 'month') ? 'selected' : '' ?>>Month</option>
					</select>

				</div>
			</div>
			<div class="col-xs-12 col-sm-6  col-md-4 col-lg-3" style="">
				<div class="form-group">
					<label class="control-label">Confirm Date</label>
					<?php
					$daterang			 = "Select Create Date Range";
					$bkg_create_date1	 = ($model->bkg_create_date1 == '') ? '' : $model->bkg_create_date1;
					$bkg_create_date2	 = ($model->bkg_create_date2 == '') ? '' : $model->bkg_create_date2;
					if ($bkg_create_date1 != '' && $bkg_create_date2 != '')
					{
						$daterang = date('F d, Y', strtotime($bkg_create_date1)) . " - " . date('F d, Y', strtotime($bkg_create_date2));
					}
					?>
					<div id="bkgCreateDate" class="col-md-3" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
						<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
						<span style="min-width: 240px"><?php echo $daterang ?></span> <b class="caret"></b>
					</div>
					<?php echo $form->hiddenField($model, 'bkg_create_date1'); ?>
					<?php echo $form->hiddenField($model, 'bkg_create_date2'); ?>

				</div>
				<?php
				if ($error != '')
				{
					echo
					"<span class='text-danger'> $error</span>";
				}
				?>
			</div>
			<div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
				<div class="form-group">
					<label class="control-label">Booking Type</label>

					<?php
					$bookingTypesArr	 = $model->booking_type;
					$bookingTypesArr[2]	 = 'Round Trip';
					$bookingTypesArr[3]	 = 'Multi City';
					$bookingTypesArr[12] = 'Airport Package';
					asort($bookingTypesArr);
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $model,
						'attribute'		 => 'bkgtypes',
						'val'			 => $model->bkgtypes,
						'data'			 => $bookingTypesArr,
						'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => 'multiple',
							'placeholder'	 => 'Booking Type')
					));
					?>
				</div>
			</div>
			<div class="col-xs-12 col-sm-2 col-md-2 col-lg-3">
				<div class="form-group">
					<label class="control-label">Week Days</label><br>

					<?php
					$weekDaysArr		 = array(1 => 'Sun', 2 => 'Mon ', 3 => 'Tue ', 4 => 'Wed', 5 => 'Thu', 6 => 'Fri', 7 => 'Sat');
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $model,
						'attribute'		 => 'weekDays',
						'val'			 => $model->weekDays,
						'data'			 => $weekDaysArr,
						'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => 'multiple',
							'placeholder'	 => 'Week Days')
					));
					?>
				</div>
			</div>
			<div class="col-xs-12 col-sm-2 col-md-2 col-lg-3">
				<div class="form-group">
					<label class="control-label">Region</label><br>

					<?php
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $model,
						'attribute'		 => 'bkg_region',
						'val'			 => $model->bkg_region,
						'data'			 => Vendors::model()->getRegionList(),
						'htmlOptions'	 => array('class'			 => 'p0', 'multiple'		 => 'multiple',
							'style'			 => 'width: 100%', 'placeholder'	 => 'Region')
					));
					?>
				</div>
			</div>
			<div class="col-xs-12 col-sm-4 col-md-3 col-lg-8 mt15">
				<div class="form-group">
					<div style="display: inline-block">
						<?php echo $form->checkboxGroup($model, 'local', array('label' => 'Local')) ?>
					</div>
					<div style="display: inline-block">
						<?php echo $form->checkboxGroup($model, 'outstation', array('label' => 'Outstation')) ?>
					</div>
					<div style="display: inline-block">
						<?php echo $form->checkboxGroup($model, 'restricted', array('label' => 'Restricted Time')) ?>
					</div>
					<div style="display: inline-block">
						<?php echo $form->checkboxGroup($model, 'isGozonow', array('label' => 'Gozo Now')) ?>
					</div>
					<div style="display: inline-block">
						<?php echo $form->checkboxGroup($model, 'isMobile', array('label' => 'Mobile App')) ?>
					</div>
					<div style="display: inline-block">
						<?php echo $form->checkboxGroup($model, 'isAndroid', array('label' => 'Android App')) ?>
					</div>
					<div style="display: inline-block">
						<?php echo $form->checkboxGroup($model, 'isiOS', array('label' => 'iOS App')) ?>
					</div>
					<div class="ml10"  style="display: inline-block">
						<button class="btn btn-primary" type="submit" style="width: 125px;" >Search</button> 
					</div>
				</div>
			</div>
		</div>
		<?php
		$this->endWidget();
		$checkExportAccess	 = false;
		if ($roles['rpt_export_roles'] != null)
		{
			$checkExportAccess = Filter::checkACL($roles['rpt_export_roles']);
		}
		if ($checkExportAccess)
		{
			?>
			<?= CHtml::beginForm(Yii::app()->createUrl('/report/financial/DailyConfirmation'), "post", ['style' => "margin-bottom: 10px;"]); ?>
			<input type="hidden" id="export" name="export" value="true"/>
			<input type="hidden" id="orderby" name="orderby" value="<?php echo $orderby; ?>"/>
			<input type="hidden" id="export_bkg_create_date1" name="bkg_create_date1" value="<?= ($model->bkg_create_date1 == '') ? '' : $model->bkg_create_date1 ?>"/>
			<input type="hidden" id="export_bkg_create_date2" name="bkg_create_date2" value="<?= ($model->bkg_create_date2 == '') ? '' : $model->bkg_create_date2 ?>"/>
			<input type="hidden" id="bkgtypes" name="bkgtypes" value="<?= implode(",", $model->bkgtypes) ?>"/>
			<input type="hidden" id="weekDays" name="weekDays" value="<?= implode(",", $model->weekDays) ?>"/>
			<input type="hidden" id="bkg_region" name="bkg_region" value="<?= implode(",", $model->bkg_region) ?>"/>
			<input type="hidden" id="local" name="local" value="<?= $model->local ?>"/>
			<input type="hidden" id="outstation" name="outstation" value="<?= $model->outstation ?>"/>
			<input type="hidden" id="restricted" name="restricted" value="<?= $model->restricted ?>"/>
			<input type="hidden" id="isGozonow" name="isGozonow" value="<?= $model->isGozonow ?>"/>
			<input type="hidden" id="isMobile" name="isMobile" value="<?= $model->isMobile ?>"/>
			<input type="hidden" id="isAndroid" name="isAndroid" value="<?= $model->isAndroid ?>"/>
			<input type="hidden" id="isiOS" name="isiOS" value="<?= $model->isiOS ?>"/>
			<button class="btn btn-default" type="submit" style="width: 185px;">Export Below Table</button>

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
			Logger::profile("Starting GridView");
			$this->widget('booster.widgets.TbExtendedGridView', array(
				'responsiveTable'	 => true,
				'fixedHeader'		 => true,
				'headerOffset'		 => 110,
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
				'itemsCssClass'		 => 'table items table-striped table-bordered mb0',
				'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
				'columns'			 => array(
					array('name'	 => 'week', 'value'	 =>
						function ($data) {
							switch ($data['groupType'])
							{
								case 'hour':
									echo "<nobr>" . $data['hour'] . "</nobr>";
									break;
								case 'date':
									echo "<nobr>" . $data['date'] . "</nobr>";
									break;
								case 'week':
									echo nl2br($data['week']);
									break;
								case 'month':
									echo "<nobr>" . $data['month'] . "</nobr>";
									break;
								default:
									break;
							}
						},
						'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => ucfirst($orderby)),
					array('name' => 'cnt Self Cancelled', 'value' => $data['cnt Self Cancelled'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Count Self Cancelled'),
					array('name' => 'cnt Self Active', 'value' => $data['cnt Self Active'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Count Self Active'),
					array('name' => 'cnt Self Gozo Coins', 'value' => $data['cnt Self Gozo Coins'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Count Self Gozo Coins'),
					array('name' => 'cnt Self', 'value' => $data['cnt Self'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Count Self'),
					array('name' => 'Count Gozo Coins', 'value' => $data['Count Gozo Coins'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Count Gozo Coins'),
					array('name' => 'Count Admin Cancelled', 'value' => $data['Count Admin Cancelled'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Count Admin Cancelled'),
					array('name' => 'Count Admin Active', 'value' => $data['Count Admin Active'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Count Admin Active'),
					array('name' => 'count Admin', 'value' => $data['count Admin'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Count Admin'),
					array('name' => 'B2C Margin', 'value' => $data['B2C Margin'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'B2C Margin'),
					array('name' => 'B2C Gozo Amount', 'value' => $data['B2C Gozo Amount'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'B2C Gozo Amount'),
					array('name' => 'Count B2C', 'value' => $data['Count B2C'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Count B2C'),
					array('name' => 'Cancelled MMT', 'value' => $data['Cancelled MMT'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Cancelled MMT'),
					array('name' => 'Count MMT', 'value' => $data['Count MMT'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Count MMT'),
					array('name' => 'MMT Margin', 'value' => $data['MMT Margin'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'MMT Margin'),
					array('name' => 'MMT Gozo Amount', 'value' => $data['MMT Gozo Amount'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'MMT Gozo Amount'),
			)));
		}
		Logger::profile("Ending GridView");
		?>
    </div>
</div>
<script >
    var start = '<?php echo date('d/m/Y', strtotime('-15 day')); ?>';
    var end = '<?php echo date('d/m/Y'); ?>';
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
                    'Last 2 Days': [moment().subtract(1, 'days'), moment()],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'Last 10 week': [moment().subtract(10, 'weeks').startOf('isoWeek'), moment()],
                    'This Month': [moment().startOf('month'), moment()],
                    'Last Month To Date': [moment().subtract(1, 'month').startOf('month'), moment()],
                    'Last 5 month': [moment().subtract(5, 'month').startOf('month'), moment()]
                }
            }, function (start1, end1)
    {
        $('#Booking_bkg_create_date1').val(start1.format('YYYY-MM-DD'));
        $('#Booking_bkg_create_date2').val(end1.format('YYYY-MM-DD'));
        $('#bkgCreateDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
    });
    $('#bkgCreateDate').on('cancel.daterangepicker', function (ev, picker)
    {
        $('#bkgCreateDate span').html('Select Create Date Range');
        $('#Booking_bkg_create_date1').val('');
        $('#Booking_bkg_create_date2').val('');
    });

    $('#getassignments').submit(function (event)
    {

        var fromDate = new Date($('#BookingSub_bkg_pickup_date1').val());
        var toDate = new Date($('#BookingSub_bkg_pickup_date2').val());

        var diffTime = Math.abs(fromDate - toDate);
        var diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        if (diffDays > 92)
        {
            alert("Date range should not be greater than 90 days");
            return false;
        }
    });

</script>