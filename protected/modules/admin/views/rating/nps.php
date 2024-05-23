<style>
    .edit-button{
        display: none;
    }
    .booking-log{
        display: none;
    }
    .below-buttons{
        display: none;
    }
</style>
<div class="row">
    <div class="col-xs-12">
		<?php
		$groupvar = $model->groupvar;
		
		/* @var $model Vendors */
		$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
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
		<div class="row ml10">
			<div class="col-xs-12 col-sm-2 col-md-2 col-lg-1" >
				<div class="form-group"> 
					<label class="control-label">Group by</label><br>
					<select class="form-control" name="Ratings[groupvar]">
						<option value="date" <?php echo ($model->groupvar == 'date') ? 'selected' : '' ?>>Day</option>
						<option value="week" <?php echo ($model->groupvar == 'week') ? 'selected' : '' ?>>Week</option>
						<option value="month" <?php echo ($model->groupvar == 'month') ? 'selected' : '' ?>>Month</option>
					</select>

				</div>
			</div>
			<div class="col-xs-12 col-sm-2 col-md-3">
				<div class="form-group">
					<label class="control-label">Created Date</label>
					<?php
					$daterang	 = "Select Date Range";
					$from_date	 = ($model->rtg_date1 == '') ? '' : $model->rtg_date1;
					$to_date	 = ($model->rtg_date2 == '') ? '' : $model->rtg_date2;
					if ($from_date != '' && $to_date != '')
					{
						$daterang = date('F d, Y', strtotime($from_date)) . " - " . date('F d, Y', strtotime($to_date));
					}
					?>
					<div id="createdDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
						<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
						<span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
					</div>

					<?= $form->hiddenField($model, 'rtg_date1'); ?>
					<?= $form->hiddenField($model, 'rtg_date2'); ?>
				</div>
			</div>
			<div class="col-xs-12 col-sm-2 col-md-3">
				<div class="form-group">
					<label class="control-label">Booking Type</label>

					<?php
					$bookingTypesArr	 = BookingSub::model()->booking_type;
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
			<div class=" col-xs-12 col-sm-12 col-md-2 text-center mt20">
				<button class="btn btn-primary" type="submit" style="width: 125px;" >Search</button> 
			</div>




		</div>
		
		<?php $this->endWidget(); ?>
	</div>
</div>
<div class="col-xs-12">
	<?php
	if (!empty($dataProvider))
	{
		$GLOBALS['dataNpsByRegionLastYear']		 = $dataNpsByRegionLastYear;
		$params									 = array_filter($_REQUEST);
		$dataProvider->getPagination()->params	 = $params;
		$dataProvider->getSort()->params		 = $params;
		$this->widget('booster.widgets.TbGridView', array(
			'id'				 => 'npsGrid',
			'responsiveTable'	 => true,
			'dataProvider'		 => $dataProvider,
			'template'			 => "<div class='panel-heading'>
                                            <div class='row m0'>
                                                <div class='col-xs-12 col-sm-6 pt5'>{summary}</div>
                                                <div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                            </div>
                                        </div>
                                        <div class='panel-body'>{items}</div>
                                        <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
			'itemsCssClass'		 => 'table table-striped table-bordered mb0',
			'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
			'columns'			 => array(
				array('name'	 => 'date', 'value'	 => function ($data) use ($model) {
						if ($model->groupvar == "month")
						{
							echo $data['month'];
						}
						elseif ($model->groupvar == "week")
						{
							echo nl2br($data['weekLabel']);
						}
						else
						{
							echo $data['date'];
						}
					}, 'htmlOptions'		 => array('class' => 'text-center'), 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'header'			 => 'Date'),
//				array('name' => 'yearName', 'type' => 'raw', 'value' => '$data[yearName]', 'sortable' => true, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'Year'),
//				array('name' => 'monthName', 'value' => '$data[monthName]', 'sortable' => true, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'Month'),
				array('name' => 'responded', 'value' => '$data[responded]', 'sortable' => true, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'Responded'),
				array('name' => 'detractors', 'value' => '$data[detractors]', 'sortable' => true, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'Detractors'),
				array('name' => 'passives', 'value' => '$data[passives]', 'sortable' => true, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'Passives'),
				array('name' => 'nps', 'value' => '$data[nps]', 'sortable' => true, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'Nps'),
				array('name'	 => 'south', 'value'	 => function ($data) use($groupvar) {
						#$search_items	 = array('year' => $data['yearName'], 'month' => $data['monthId'], 'stt_zone' => 4);
						$search_items = array("{$groupvar}" => $data[$groupvar], 'stt_zone' => 4);
						$south1			 = RatingController::GetNpsByRegion($GLOBALS['dataNpsByRegionLastYear'], $search_items);
						#$search_items	 = array('year' => $data['yearName'], 'month' => $data['monthId'], 'stt_zone' => 7);
						$search_items = array("{$groupvar}" => $data[$groupvar], 'stt_zone' => 7);
						$south2			 = RatingController::GetNpsByRegion($GLOBALS['dataNpsByRegionLastYear'], $search_items);
						$count			 = (($south1 > 0) ? 1 : 0) + (($south2 > 0) ? 1 : 0);
						if($count > 0)
						{
							echo number_format((($south1 + $south2) / $count), 2);
						}
						else
						{
							echo "0.00";
						}
					}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'South'),
				array('name'	 => 'north', 'value'	 => function ($data) use($groupvar) {
						#$search_items = array('year' => $data['yearName'], 'month' => $data['monthId'], 'stt_zone' => 1);
						$search_items = array("{$groupvar}" => $data[$groupvar], 'stt_zone' => 1);
						echo number_format(RatingController::GetNpsByRegion($GLOBALS['dataNpsByRegionLastYear'], $search_items), 2);
					}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'North'),
				array('name'	 => 'west', 'value'	 => function ($data) use($groupvar) {
						#$search_items = array('year' => $data['yearName'], 'month' => $data['monthId'], 'stt_zone' => 2);
						$search_items = array("{$groupvar}" => $data[$groupvar], 'stt_zone' => 2);
						echo number_format(RatingController::GetNpsByRegion($GLOBALS['dataNpsByRegionLastYear'], $search_items), 2);
					}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'West'),
				array('name'	 => 'central', 'value'	 => function ($data) use($groupvar) {
						#$search_items = array('year' => $data['yearName'], 'month' => $data['monthId'], 'stt_zone' => 3);
						$search_items = array("{$groupvar}" => $data[$groupvar], 'stt_zone' => 3);
						echo number_format(RatingController::GetNpsByRegion($GLOBALS['dataNpsByRegionLastYear'], $search_items), 2);
					}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'Central'),
				array('name'	 => 'north-east', 'value'	 => function ($data) use($groupvar) {
						#$search_items = array('year' => $data['yearName'], 'month' => $data['monthId'], 'stt_zone' => 6);
						$search_items = array("{$groupvar}" => $data[$groupvar], 'stt_zone' => 6);
						echo number_format(RatingController::GetNpsByRegion($GLOBALS['dataNpsByRegionLastYear'], $search_items), 2);
					}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'North-East'),
				array('name'	 => 'east', 'value'	 => function ($data) use($groupvar) {
						#$search_items = array('year' => $data['yearName'], 'month' => $data['monthId'], 'stt_zone' => 5);
						$search_items = array("{$groupvar}" => $data[$groupvar], 'stt_zone' => 5);
						echo number_format(RatingController::GetNpsByRegion($GLOBALS['dataNpsByRegionLastYear'], $search_items), 2);
					}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'East'),
		)));
	}
	?>
</div>
<?php
$version = Yii::app()->params['customJsVersion'];
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/custom.js?v=' . $version, CClientScript::POS_HEAD);
?>

<script>
	var start = '<?= date('d/m/Y', strtotime($model->rtg_date1)); ?>';
	var end = '<?= date('d/m/Y', strtotime($model->rtg_date2)); ?>';
	$('#createdDate').daterangepicker({
		locale: {
			format: 'DD/MM/YYYY',
			cancelLabel: 'Clear'
		},
		"showDropdowns": true,
		"alwaysShowCalendars": true,
		"startDate": start,
		"endDate": end,
		ranges: {
			'Today': [moment(), moment()],
			'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
			'Last 7 Days': [moment().subtract(6, 'days'), moment()],
			'Last 15 Days': [moment().subtract(15, 'days'), moment()],
			'This Month': [moment().startOf('month'), moment().endOf('month')],
			'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
		}
	}, function (start1, end1) {
		$('#Ratings_rtg_date1').val(start1.format('YYYY-MM-DD'));
		$('#Ratings_rtg_date2').val(end1.format('YYYY-MM-DD'));
		$('#createdDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
	});
</script>