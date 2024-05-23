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
					<select class="form-control" name="BookingSub[groupvar]">
						<option value="date" <?php echo ($orderby == 'date') ? 'selected' : '' ?>>Day</option>
						<option value="week" <?php echo ($orderby == 'week') ? 'selected' : '' ?>>Week</option>
						<option value="month" <?php echo ($orderby == 'month') ? 'selected' : '' ?>>Month</option>
					</select>

				</div>
			</div>
			<div class="col-xs-12 col-sm-6  col-md-4 col-lg-3" style="">
				<div class="form-group">
					<label class="control-label">Pickup Date</label>
					<?php
					$daterang			 = "Select Pickup Date Range";
					$bkg_pickup_date1	 = ($model->bkg_pickup_date1 == '') ? '' : $model->bkg_pickup_date1;
					$bkg_pickup_date2	 = ($model->bkg_pickup_date2 == '') ? '' : $model->bkg_pickup_date2;
					if ($bkg_pickup_date1 != '' && $bkg_pickup_date2 != '')
					{
						$daterang = date('F d, Y', strtotime($bkg_pickup_date1)) . " - " . date('F d, Y', strtotime($bkg_pickup_date2));
					}
					?>
					<div id="bkgPickupDate" class="col-md-3" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
						<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
						<span style="min-width: 240px"><?php echo $daterang ?></span> <b class="caret"></b>
					</div>
					<?php echo $form->hiddenField($model, 'bkg_pickup_date1'); ?>
					<?php echo $form->hiddenField($model, 'bkg_pickup_date2'); ?>

				</div>
				<?php
				if ($error != '')
				{
					echo
					"<span class='text-danger'> $error</span>";
				}
				?>
			</div>
			<div class="col-xs-12 col-sm-6  col-md-4 col-lg-3" style="">
				<div class="form-group">
					<label class="control-label">Create Date</label>
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
			<div class="col-xs-12 col-sm-6  col-md-4 col-lg-3" style="">
				<div class="form-group">
					<label class="control-label">Assigned Date</label>
					<?php
					$daterang	 = "Select Assigned Date Range";
					$fromDate	 = ($model->from_date == '') ? '' : $model->from_date;
					$toDate		 = ($model->to_date == '') ? '' : $model->to_date;
					if ($fromDate != '' && $toDate != '')
					{
						$daterang = date('F d, Y', strtotime($fromDate)) . " - " . date('F d, Y', strtotime($toDate));
					}
					?>
					<div id="assignedDate" class="col-md-3" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
						<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
						<span style="min-width: 240px"><?php echo $daterang ?></span> <b class="caret"></b>
					</div>
					<?php echo $form->hiddenField($model, 'from_date'); ?>
					<?php echo $form->hiddenField($model, 'to_date'); ?>

				</div>
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
			<div class="col-xs-12 col-sm-5 col-md-4 col-lg-3" >
				<div class="form-group">
					<label class="control-label">Partner</label>
					<?php
					$this->widget('ext.yii-selectize.YiiSelectize', array(
						'model'				 => $model,
						'attribute'			 => 'bkg_agent_id',
						'useWithBootstrap'	 => true,
						"placeholder"		 => "Select Partner",
						'fullWidth'			 => false,
						'options'			 => array('allowClear' => true),
						'htmlOptions'		 => array('width' => '100%',
						),
						'defaultOptions'	 => $selectizeOptions + array(
					'onInitialize'	 => "js:function(){
                                  populatePartner(this, '{$model->bkg_agent_id}');
                                                }",
					'load'			 => "js:function(query, callback){
                        loadPartner(query, callback);
                        }",
					'render'		 => "js:{
                            option: function(item, escape){
                            return '<div><span class=\"\"><i class=\"fa fa-user mr5\"></i>' + escape(item.text) +'</span></div>';
                            },
                            option_create: function(data, escape){
                            return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
                            }
                        }", 'allowClear'	 => true
						),
					));
					?>
				</div>
			</div>
			<div class="col-xs-12 col-lg-8">
				<div class="row m0 mb15">
					<div class="col-xs-12 col-lg-3">
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
					<div class="col-xs-12 col-lg-3">
						<div style="display: inline-block;">
							<label class="control-label">From Zone</label>

							<?php
							$datazone			 = Zones::model()->getZoneArrByFromBooking();
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'sourcezone',
								'val'			 => $model->sourcezone,
								'data'			 => $datazone,
								'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => 'multiple',
									'placeholder'	 => 'Select Zone')
							));
							?>
						</div>
					</div>
					<div class="col-xs-12 col-lg-3">
						<div style="display: inline-block;">
							<label class="control-label">Region</label><br>

							<?php
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'region',
								'val'			 => $model->region,
								'data'			 => Vendors::model()->getRegionList(),
								'htmlOptions'	 => array('class'			 => 'p0', 'multiple'		 => 'multiple',
									'style'			 => 'width: 100%', 'placeholder'	 => 'Select Region')
							));
							?>
						</div>
					</div>
					<div class="col-xs-12 col-lg-3">
						<div style="display: inline-block;">
							<label class="control-label">State</label><br>
							<?php
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'state',
								'val'			 => $model->state,
								'data'			 => States::model()->getStateList1(),
								'htmlOptions'	 => array('class'			 => 'p0', 'multiple'		 => 'multiple',
									'style'			 => 'width: 100%', 'placeholder'	 => 'Select State')
							));
							?>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xs-12 pl0">
				<div class="row mb15">
					<div class="col-xs-12 col-lg-3"> 
						<div style="display: inline-block">
							<?php echo $form->checkboxListGroup($model, 'b2cbookings', array('label' => '', 'inline' => true, 'widgetOptions' => array('data' => array(1 => 'B2C Only ')), 'groupOptions' => ['htmlOptions' => ["style" => "display: inline-block;"]])) ?>
						</div>
						<div style="display: inline-block">
							<?php echo $form->checkboxGroup($model, 'mmtbookings', array('label' => 'MMT')) ?>
						</div>
						<div style="display: inline-block">
							<?php echo $form->checkboxGroup($model, 'nonAPIPartner', array('label' => 'Non API Partner')) ?>
						</div>
					</div>
					<div class="col-xs-12 col-lg-7">
						<div style="display: inline-block">
							<?php echo $form->checkboxListGroup($model, 'excludeAT', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Exclude Airport Transfer '), 'htmlOptions' => []))) ?>
						</div><div style="display: inline-block">
							<?= $form->checkboxListGroup($model, 'gnowType', array('label' => '', 'inline' => true, 'widgetOptions' => array('data' => array(1 => 'GN Initiated', 2 => 'GN Converted')), 'groupOptions' => ['htmlOptions' => ["style" => "display: inline-block"]])) ?>
						</div><div style="display: inline-block; margin-left: 30px;">
							<?php echo $form->radioButtonListGroup($model, 'nonProfitable', array('label' => '', 'inline' => true, 'widgetOptions' => array('data' => array(1 => 'Non Profitable', 2 => 'Profitable', 0 => 'All')), 'groupOptions' => ['htmlOptions' => ["style" => "display: inline-block"]])) ?>
						</div>
					</div>
					<div class="col-xs-12 col-lg-2">
						<div style="display: inline-block">
							<?php echo $form->checkboxGroup($model, 'local', array('label' => 'Local')) ?>
						</div>
						<div style="display: inline-block">
							<?php echo $form->checkboxGroup($model, 'outstation', array('label' => 'Outstation')) ?>
						</div>
					</div>
				</div>

			</div>
		</div>
		<div class="row">
			<div class="  col-xs-12 col-sm-12 col-md-2 text-center mb20"  >
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
					array('name'	 => 'date', 'value'	 =>
						function ($data) {
							switch ($data['groupType'])
							{
								case 'date':
									echo "<nobr>" . $data['date'] . "</nobr>";
									break;
								case 'week':
									echo nl2br($data['weekLabel']);
									break;
								case 'month':
									echo "<nobr>" . $data['monthname'] . "</nobr>";
									break;
								default:
									break;
							}
						},
						'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => ucfirst($orderby)),
					array('name'	 => 'totalGozoCancelled', 'value'	 =>
						function ($data) {
							echo $data['totalGozoCancelled'];
							if ($data['MMTGozoCancelled'] > 0)
							{
								$others = $data['totalGozoCancelled'] - $data['MMTGozoCancelled'];
								echo " <div class='mt5 text-center'><span class='text-nowrap'>MMT: {$data['MMTGozoCancelled']}</span>, <span class='text-nowrap'>Others: {$others}</span></div>";
							}
						},
						'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Gozo Cancelled'),
					array('name'	 => 'totalBooking', 'value'	 =>
						function ($data) {
							echo $data['totalBooking'];
							$cntManual = '';
							if ($data['cntManual'] > 0 || $data['cntCritical'] > 0)
							{
								$cntManual = "<br><span class='text-center'>M: {$data['cntManual']}, C: {$data['cntCritical']}</span>";
							}
							if ($data['totalUnassigned'] > 0)
							{
								echo " <div class='mt5 text-center'><span class='text-nowrap'>New: {$data['totalUnassigned']}</span>$cntManual<br><span class='text-nowrap'>Assigned: {$data['totalAssigned']}</span></div>";
							}
							if ($data['totalLossBooking'] > 0)
							{
								echo " <div class='mt5 text-center'><span class='text-nowrap'>Loss: {$data['totalLossBooking']}</span></div>";
							}
						},
						'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Total Active'),
					array('name'	 => 'gozoAmount', 'value'	 =>
						function ($data) {
							echo Filter::moneyFormatter($data['gozoAmount']);
							if ($data['totalUnassigned'] > 0)
							{
								$newGozoAmount = Filter::moneyFormatter($data['gozoAmount'] - $data['AssignedGozoAmount']);
								echo " <div class='mt5 text-center'><span class='nowrap'>New: {$newGozoAmount}</span><br><span class='text-nowrap'>Assigned: " . Filter::moneyFormatter($data['AssignedGozoAmount']) . "</span></div>";
							}
							if ($data['gozoLossAmount'] != 0)
							{
								echo " <div class='mt5 text-center'><span class='text-nowrap'>Loss: " . Filter::moneyFormatter($data['gozoLossAmount']) . "</span></div>";
							}
						},
						'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Gozo Amount'),
					array('name'	 => 'TotalMargin', 'value'	 =>
						function ($data) {
							echo $data['TotalMargin'];
							if ($data['totalUnassigned'] > 0)
							{
								echo " <div class='mt5 text-center'><span class='text-nowrap'>New: {$data['UnassignedMargin']}</span><br><span class='text-nowrap'>Assigned: {$data['AssignedMargin']}</span></div>";
							}
						}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Total Margin'),
					array('name'	 => 'ManualGozoAmount', 'value'	 => function ($data) {
							echo Filter::moneyFormatter($data['ManualGozoAmount']);
							if ($data['ManualLossGozoAmount'] != 0)
							{
								echo '<br/>L: ' . Filter::moneyFormatter($data['ManualLossGozoAmount']);
							}
						}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Manual Gozo Amount'),
					array('name'	 => 'AutoGozoAmount', 'value'	 => function ($data) {
							echo Filter::moneyFormatter($data['AutoGozoAmount']);
							if ($data['AutoLossGozoAmount'] != 0)
							{
								echo '<br/>L:' . Filter::moneyFormatter($data['AutoLossGozoAmount']);
							}
						}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Auto Gozo Amount'),
					array('name'	 => 'ManualMargin', 'value'	 => function ($data) {
							echo $data['ManualMargin'];
							if ($data['ManualLossMargin'] != '' || $data['ManualLossMargin'] != NULL)
							{
								echo '<br/>L: ' . $data['ManualLossMargin'];
							}
						}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Manual Margin'),
					array('name'	 => 'AutoMargin', 'value'	 => function ($data) {
							echo $data['AutoMargin'];
							if ($data['AutoLossMargin'] != '' || $data['AutoLossMargin'] != NULL)
							{
								echo '<br/>L: ' . $data['AutoLossMargin'];
							}
						}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Auto Margin'),
					array('name'	 => 'ManualAssignPercent', 'value'	 => function ($data) {
							echo $data['ManualAssignPercent'] . " (" . $data['countManualMargin'] . ")";
							if ($data['ManualLossBookingCount'] != 0)
							{
								echo '<br/>Loss: ' . $data['ManualLossBookingCount'];
							}
						}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Manual Assign Percent'),
					array('name'	 => 'AutoAssignPercent', 'value'	 => function ($data) {
							echo $data['AutoAssignPercent'] . " (" . $data['countAutoMargin'] . ")";
						}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Auto Assign Percent'),
					array('name'	 => 'netBaseAmount', 'value'	 =>
						function ($data) {
							echo Filter::moneyFormatter($data['netBaseAmount']);
							if ($data['totalUnassigned'] > 0)
							{
								$assignedBaseAmount = Filter::moneyFormatter($data['netBaseAmount'] - $data['UnassignedNetBaseAmount']);
								echo " <div class='mt5 text-center'><span class='text-nowrap'>New: " . Filter::moneyFormatter($data['UnassignedNetBaseAmount']) . "</span><br><span class='text-nowrap'>Assigned: {$assignedBaseAmount}</span></div>";
							}
						}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Net BaseAmount'),
					array('name'	 => 'BidAssignPercent', 'value'	 => function ($data) {
							echo $data['BidAssignPercent'] . " (" . $data['countBidMargin'] . ")";
						}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Bid Assign Percent'),
					array('name'	 => 'BidAssignMargin', 'value'	 => function ($data) {
							echo $data['BidAssignMargin'];
						}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Bid Assign Margin'),
					array('name'	 => 'BidGozoAmount', 'value'	 => function ($data) {
							echo Filter::moneyFormatter($data['BidGozoAmount']);
						}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Bid Gozo Amount'),
					array('name' => 'DirectAssignPercent', 'value' => $data['DirectAssignPercent'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Direct Assign Percent'),
					array('name'	 => 'DirectAssignPercent', 'value'	 => function ($data) {
							echo $data['DirectAssignPercent'] . " (" . $data['countDirectMargin'] . ")";
						}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Direct Assign Percent'),
					array('name'	 => 'DirectAssignMargin', 'value'	 => function ($data) {
							echo $data['DirectAssignMargin'];
							if ($data['DirectAssignLossMargin'] != '' || $data['DirectAssignLossMargin'] != NULL)
							{
								echo '<br/>L: ' . $data['DirectAssignLossMargin'];
							}
						}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Direct Assign Margin'),
					array('name'	 => 'DirectGozoAmount', 'value'	 => function ($data) {
							echo Filter::moneyFormatter($data['DirectGozoAmount']);
							if ($data['DirectLossGozoAmount'] != 0)
							{
								echo '<br/>L:' . Filter::moneyFormatter($data['DirectLossGozoAmount']);
							}
						}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Direct Gozo Amount'),
			)));
		}
		?>
    </div>
</div>
<script >
	var start = '<?php echo date('d/m/Y', strtotime('-1 month')); ?>';
	var end = '<?php echo date('d/m/Y'); ?>';
	$('#bkgPickupDate').daterangepicker(
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
					'Next 7 Days': [moment(), moment().add(6, 'days')],
					'This Week': [moment().startOf('week').add(1, 'days'), moment().endOf('week').add(1, 'days')],
					'Last 7 Days': [moment().subtract(6, 'days'), moment()],
					'Last 30 Days': [moment().subtract(29, 'days'), moment()],
					'Last 90 Days': [moment().subtract(89, 'days'), moment()],
					'This Month': [moment().startOf('month'), moment()],
					'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
				}
			}, function(start1, end1)
	{
		$('#BookingSub_bkg_pickup_date1').val(start1.format('YYYY-MM-DD'));
		$('#BookingSub_bkg_pickup_date2').val(end1.format('YYYY-MM-DD'));
		$('#bkgPickupDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
	});
	$('#bkgPickupDate').on('cancel.daterangepicker', function(ev, picker)
	{
		$('#bkgPickupDate span').html('Select Pickup Date Range');
		$('#BookingSub_bkg_pickup_date1').val('');
		$('#BookingSub_bkg_pickup_date2').val('');
	});

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
					'This Month': [moment().startOf('month'), moment()],
					'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
				}
			}, function(start1, end1)
	{
		$('#BookingSub_bkg_create_date1').val(start1.format('YYYY-MM-DD'));
		$('#BookingSub_bkg_create_date2').val(end1.format('YYYY-MM-DD'));
		$('#bkgCreateDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
	});
	$('#bkgCreateDate').on('cancel.daterangepicker', function(ev, picker)
	{
		$('#bkgCreateDate span').html('Select Create Date Range');
		$('#BookingSub_bkg_create_date1').val('');
		$('#BookingSub_bkg_create_date2').val('');
	});

	$('#assignedDate').daterangepicker(
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
					'This Month': [moment().startOf('month'), moment()],
					'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
				}
			}, function(start1, end1)
	{
		$('#BookingSub_from_date').val(start1.format('YYYY-MM-DD'));
		$('#BookingSub_to_date').val(end1.format('YYYY-MM-DD'));
		$('#assignedDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
	});
	$('#assignedDate').on('cancel.daterangepicker', function(ev, picker)
	{
		$('#assignedDate span').html('Select Create Date Range');
		$('#BookingSub_from_date').val('');
		$('#BookingSub_to_date').val('');
	});

	$('#getassignments').submit(function(event)
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