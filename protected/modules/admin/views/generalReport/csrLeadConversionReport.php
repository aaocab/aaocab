<?php
$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/jquery.mask.min.js');
Yii::app()->clientScript->registerCssFile(ASSETS_URL . '/plugins/form-select2/select2.css');
?>
<div class="row">
    <div class="col-xs-12">
		<?php
		/* @var $model Vendors */
		$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'getleadconversion', 'enableClientValidation' => true,
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
			<div class="col-xs-12 col-sm-2 col-md-2 pr0" >
				<div class="form-group"> 
					<label class="control-label">Group by</label><br>
					<select class="form-control" name="ServiceCallQueue[groupvar]" id="callqueuegroupvar">
						<option value="hour" <?php echo ($groupBy == 'hour') ? 'selected' : '' ?>>Hour</option>
						<option value="date" <?php echo ($groupBy == 'date') ? 'selected' : '' ?>>Day</option>
						<option value="week" <?php echo ($groupBy == 'week') ? 'selected' : '' ?>>Week</option>
						<option value="month" <?php echo ($groupBy == 'month') ? 'selected' : '' ?>>Month</option>
					</select>

				</div>
			</div>

			<div class="col-xs-12 col-sm-6 col-md-3" style="">
				<div class="form-group">
					<label class="control-label">Date Range</label>
					<?php
					$daterang			 = "Select Pickup Date Range";
					$scq_create_date1	 = ($model->fromDate == '') ? '' : $model->fromDate;
					$scq_create_date2	 = ($model->toDate == '') ? '' : $model->toDate;
					if ($scq_create_date1 != '' && $scq_create_date2 != '')
					{
						$daterang = date('F d, Y', strtotime($scq_create_date1)) . " - " . date('F d, Y', strtotime($scq_create_date2));
					}
					?>
					<div id="scqCreateDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
						<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
						<span style="min-width: 240px"><?php echo $daterang ?></span> <b class="caret"></b>
					</div>
					<?php echo $form->hiddenField($model, 'fromDate'); ?>
					<?php echo $form->hiddenField($model, 'toDate'); ?>
				</div>
				<?php
				if ($error != '')
				{
					echo
					"<span class='text-danger'> $error</span>";
				}
				?>
			</div>
			<div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
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
			<div class="col-xs-12 col-sm-2 col-md-2">
				<label class="control-label">Filter By Assigned CSR</label>
				<?php
				$adminListJson		 = Admins::model()->getJSON();
				$this->widget('booster.widgets.TbSelect2', array(
					'model'			 => $model,
					'attribute'		 => 'csrSearch',
					'val'			 => $model->csrSearch,
					'asDropDownList' => FALSE,
					'options'		 => array('data' => new CJavaScriptExpression($adminListJson), 'allowClear' => true),
					'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Admins')
				));
				?>
			</div>
			<div class="col-xs-12 col-sm-2 col-md-2">
				<div class="form-group">
					<label class="control-label">Team/Team Leader</label>
					<?php
					$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
						'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
						'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
						'openOnFocus'		 => true, 'preload'			 => false,
						'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
						'addPrecedence'		 => false,];
					?> 
					<?php
					$this->widget('ext.yii-selectize.YiiSelectize', array(
						'model'				 => $model,
						'attribute'			 => 'adminId',
						'useWithBootstrap'	 => true,
						"placeholder"		 => "Select Team Leader",
						'fullWidth'			 => false,
						'htmlOptions'		 => array('width'	 => '100%',
							'id'	 => 'ServiceCallQueue_adminId'
						),
						'defaultOptions'	 => $selectizeOptions + array(
					'onInitialize'	 => "js:function(){
                                            populateGozen(this, '{$model->adminId}');
                                                }",
					'load'			 => "js:function(query, callback){
                                            loadGozen(query, callback);
                                            }",
					'render'		 => "js:{
                                            option: function(item, escape){
                                            return '<div><span class=\"\"><i class=\"fa fa-map-marker mr5\"></i>' + escape(item.text) +'</span></div>';
                                            },
                                            option_create: function(data, escape){
                                            return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
                                            }
                                            }",
						),
					));
					?>
				</div>
			</div>
		</div>
		<div class="row">
            <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
				<div class="form-group">
					<label class="control-label">Booking Type</label>

					<?php
					$bookingTypesArr	 = Booking::model()->booking_type;
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
			<div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
				<div class="form-group">
					<label class="control-label">Region</label><br>

					<?php
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $model,
						'attribute'		 => 'regions',
						'val'			 => $model->regions,
						'data'			 => Vendors::model()->getRegionList(),
						'htmlOptions'	 => array('class'			 => 'p0', 'multiple'		 => 'multiple',
							'style'			 => 'width: 100%', 'placeholder'	 => 'Region')
					));
					?>
				</div>
			</div>
			<div class="col-xs-3 col-sm-2 mt20">
				<?php echo $form->checkboxGroup($model, 'restrictCurrentTime', array('label' => 'Restrict Current Time')) ?>	
			</div>
			<!--			<div class="col-xs-2 col-sm-2 mt20">
			<?php //echo $form->checkboxGroup($model, 'selfCreated', array('label' => 'Include Self Created')) ?>	
						</div>-->
			<div class="col-xs-2 col-sm-2 mt20">
				<?php echo $form->checkboxGroup($model, 'isMobile', array('label' => 'Mobile App')) ?>
			</div>

			<div class="col-xs-2 col-sm-2 mt20">
				<?php echo $form->checkboxGroup($model, 'isAndroid', array('label' => 'Android App')) ?>
			</div>
			<div class="col-xs-2 col-sm-2 mt20">
				<?php echo $form->checkboxGroup($model, 'isIOS', array('label' => 'IOS App')) ?>
			</div>

			<div class="col-xs-2 col-sm-2 mt20">
				<?php echo $form->checkboxGroup($model, 'isGozonow', array('label' => 'Gozo Now')) ?>
			</div>
   
			<div class="col-xs-4 col-sm-2 col-md-2 p15">
				<button class="btn btn-primary" type="submit" style="width: 125px;" >Search</button> 
			</div>
		</div>
		<?php $this->endWidget(); ?>
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
			$this->widget('booster.widgets.TbExtendedGridView', array(
				'id'				 => 'csrLeadGrid',
				'responsiveTable'	 => true,
				'dataProvider'		 => $dataProvider,
				'template'			 => "<div class='panel-heading'><div class='row m0'>
							<div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
							</div></div>
							<div class='panel-body table-responsive'>{items}</div>
							<div class='panel-footer'>
							<div class='row'><div class='col-xs-12 col-sm-6 p5'>{summary}</div>
							<div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
				'itemsCssClass'		 => 'table  table-bordered dataTable mb0',
				'htmlOptions'		 => array('class' => 'panel panel-primary compact'),
				'columns'			 => array(
					array('name'	 => 'date',
						'value'	 => function ($data) {
							echo $data["date"];
						}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => ucfirst($groupBy)),
					//array('name' => 'TeamLeader', 'value' => $data['TeamLeader'], 'headerHtmlOptions' => array(), 'sortable' => true, 'header' => 'Team Leader'),
					array('name'				 => 'totalCSR',
						'value'				 => $data['totalCSR'],
						'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Total CSR'),
					array('name'				 => 'cnt',
						'value'				 => $data['cnt'], 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Total Followup'),
					array('name'				 => 'FollowupsPoints',
						'value'				 => $data['FollowupsPoints'], 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Followups Points'),
					array('name'				 => 'LeadPoints',
						'value'				 => $data['LeadPoints'], 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Lead Points'),
					array('name'				 => 'cntLeadsCreated',
						'value'				 => $data['cntLeadsCreated'], 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Lead Created'),
					array('name'				 => 'quoteCreated',
						'value'				 => $data['quoteCreated']
						, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Quote Created (Admin)'),
					array('name'				 => 'bookingCreated',
						'value'				 => $data['bookingCreated']
						, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Booking Created'),
					array('name'				 => 'bookingConfirmed',
						'value'				 => $data['bookingConfirmed']
						, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Booking Confirmed'),
					array('name'				 => 'bookingCreatedSelf',
						'value'				 => $data['bookingCreatedSelf']
						, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Booking Created Self'),
					array('name'				 => 'bookingConfirmedSelf',
						'value'				 => $data['bookingConfirmedSelf']
						, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Booking Confirmed Self'),
					array('name'				 => 'bookingCreatedAdmin',
						'value'				 => $data['bookingCreatedAdmin']
						, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Booking Created Admin'),
					array('name'				 => 'bookingConfirmedAdmin',
						'value'				 => $data['bookingConfirmedAdmin']
						, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Booking Confirmed Admin'),
					array('name'				 => 'quoteCount',
						'value'				 => function($data){
							return $data["quoteCount"] . '/' . $data["cntSelfUniqueQuoteCreated"];
						}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Quote Count (Assigned/Created)'),
					array('name'				 => 'quoteMedian',
						'value'				 => $data['quoteMedian']
						, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Quote Median'),
					array('name'	 => 'leadCount',
						'value'	 => function ($data) {
							$total = $data["leadCount1"] + $data["leadCount2"] + $data["leadCount3"];
							echo "<div>$total</div>";
							echo "<span style='white-space:nowrap'>({$data["leadCount1"]}/{$data["leadCount2"]}/{$data["leadCount3"]})</span>";
						}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Lead Count'),
					array('name'				 => 'leadMedian',
						'value'				 => $data['leadMedian']
						, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Lead Median'),
					array('name'				 => 'callbackCount',
						'value'				 => $data['callbackCount']
						, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Callback Count'),
					array('name'				 => 'callbackMedian',
						'value'				 => $data['callbackMedian']
						, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Callback Median'),
			)));
		}
		?>
    </div>
</div>
<script >
	var start = '<?php echo date('d/m/Y', strtotime('-15 day')); ?>';
	var end = '<?php echo date('d/m/Y'); ?>';

	$('#scqCreateDate').daterangepicker(
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
					'Last 15 Days': [moment().subtract(14, 'days'), moment()],
					'Last 6 week': [moment().subtract(6, 'weeks').startOf('isoWeek'), moment()],
					'This Month': [moment().startOf('month'), moment()],
					'Last 2 Month': [moment().subtract(2, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
				}
			}, function (start1, end1)
	{
		$('#ServiceCallQueue_fromDate').val(start1.format('YYYY-MM-DD'));
		$('#ServiceCallQueue_toDate').val(end1.format('YYYY-MM-DD'));
		$('#scqCreateDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
	});
	$('#scqCreateDate').on('cancel.daterangepicker', function (ev, picker)
	{
		$('#scqCreateDate span').html('Select Pickup Date Range');
		$('#ServiceCallQueue_scq_create_date1').val('');
		$('#ServiceCallQueue_scq_create_date2').val('');
	});
	$('#getassignments').submit(function (event)
	{

		var fromDate = new Date($('#ServiceCallQueue_scq_create_date1').val());
		var toDate = new Date($('#ServiceCallQueue_scq_create_date2').val());

		var diffTime = Math.abs(fromDate - toDate);
		var diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
		if (diffDays > 90)
		{
			alert("Date range should not be greater than 90 days");
			return false;
		}
	});
	$gozenList = null;
	function populateGozen(obj, gozen)
	{
		$followUp.populateGozen(obj, gozen);
	}
	function loadGozen(query, callback)
	{
		$followUp.loadGozen(query, callback);
	}
</script>