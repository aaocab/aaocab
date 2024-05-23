<style type="text/css">
    .cityinput > .selectize-control>.selectize-input{
        width:100% !important;
    }
</style>
<?php
$datazone		 = Zones::model()->getZoneArrByFromBooking();
$assignDisplay	 = ($model->assignCount != '' && $model->assignCount != NULL) ? "block" : "none";
$lossDisplay	 = ($model->lossCount != '' && $model->lossCount != NULL) ? "block" : "none";
$marginDisplay	 = ($model->netMargin != '' && $model->netMargin != NULL) ? "block" : "none";
?>
<div class="row">
	<div class="panel" >
		<div class="panel-body">
			<?php
			$form			 = $this->beginWidget('booster.widgets.TbActiveForm', array(
				'id'					 => 'zoneProfitability-form', 'enableClientValidation' => true,
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

			<div class="col-xs-12">
				<div class="form-group">
					<div class="row">
						<div class="col-xs-12 col-sm-3">
							<label class="control-label">Pickup Date</label>
							<?php
							$daterang		 = "Select Pickup Date Range";
							$from_date		 = ($model->from_date == '') ? '' : $model->from_date;
							$to_date		 = ($model->to_date == '') ? '' : $model->to_date;
							if ($from_date != '' && $to_date != '')
							{
								$daterang = date('F d, Y', strtotime($from_date)) . " - " . date('F d, Y', strtotime($to_date));
							}
							?>
							<div id="pickupDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
								<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
								<span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
							</div>
						</div>

						<?= $form->hiddenField($model, 'from_date'); ?>
						<?= $form->hiddenField($model, 'to_date'); ?>

						<div class="col-xs-12 col-sm-3">
							<label class="control-label">Create Date</label>
							<?php
							$daterang			 = "Select Create Date Range";
							$create_from_date	 = ($model->create_from_date == '') ? '' : $model->create_from_date;
							$create_to_date		 = ($model->create_to_date == '') ? '' : $model->create_to_date;
							if ($create_from_date != '' && $create_to_date != '')
							{
								$daterang = date('F d, Y', strtotime($create_from_date)) . " - " . date('F d, Y', strtotime($create_to_date));
							}
							?>
							<div id="createDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
								<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
								<span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
							</div>
						</div>

						<?= $form->hiddenField($model, 'create_from_date'); ?>
						<?= $form->hiddenField($model, 'create_to_date'); ?>

						<div class="col-xs-12 col-sm-2">
							<label class="control-label">Type</label>
							<?php
							$filters		 = [
								1	 => 'Zone',
								2	 => 'Zone to zone',
								3	 => 'Zone to state',
								4	 => 'Zone to cab type',
								5	 => 'Zone to zone cab type',
							];
							$dataPay		 = Filter::getJSON($filters);
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'bkgZoneType',
								'val'			 => $model->bkgZoneType,
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($dataPay), 'allowClear' => true),
								'htmlOptions'	 => array('class' => 'p0', 'style' => 'width:100%', 'placeholder' => 'Select Type')
							));
							?>	
						</div>
						<div class="col-xs-12 col-sm-2">
							<div class="form-group">
								<label class="control-label">Booking Type</label>

								<?php
								$bookingTypesArr = Booking::model()->booking_type;
								unset($bookingTypesArr[2]);
								$this->widget('booster.widgets.TbSelect2', array(
									'model'			 => $model,
									'attribute'		 => 'bkgTypes',
									'val'			 => $model->bkgTypes,
									'data'			 => $bookingTypesArr,
									'htmlOptions'	 => array(
										'style'			 => 'width:100%',
										'multiple'		 => 'multiple',
										'placeholder'	 => 'Booking Type'
									)
								));
								?>
							</div>
						</div>
						<div class="col-xs-12 col-sm-2">
							<div class="form-group">
								<label>From Zone</label>
								<?php
									$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
									'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
									'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
									'openOnFocus'		 => true, 'preload'			 => false,
									'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
									'addPrecedence'		 => false,];
									$this->widget('ext.yii-selectize.YiiSelectize', array(
										'model'				 => $model,
										'attribute'			 => 'sourcezone',
										'useWithBootstrap'	 => true,
										"placeholder"		 => "Select Zone",
										'fullWidth'			 => false,
										'options'			 => array('allowClear' => true),
										'htmlOptions'		 => array('width'	 => '100%'),
										'defaultOptions'	 => $selectizeOptions + array(
									'onInitialize'	 => "js:function(){
													populateZone(this, '{$model->sourcezone}');
														}",
									'load'			 => "js:function(query, callback){
													loadZone(query, callback);
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
						<div class="col-xs-12 col-sm-2">
							<div class="form-group">
								<label class="control-label">Region </label>
								<?php
								#$regionList			 = VehicleTypes::model()->getJSON(Vendors::model()->getRegionList());
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
						<div class="col-xs-12 col-sm-2">
							<div class="form-group">
								<label class="control-label">State </label>
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
						<div class="col-xs-12 col-sm-1">
							<label class="control-label">Assign Count</label>
							<?php
							$filters		 = [
								1	 => 'Greater than',
								2	 => 'Less than',
							];
							$dataPay		 = Filter::getJSON($filters);
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'assignCountDrop',
								'val'			 => $model->assignCountDrop,
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($dataPay), 'allowClear' => true),
								'htmlOptions'	 => array('class' => 'p0', 'style' => 'width:100%', 'placeholder' => 'Select assign count')
							));
							?>	
						</div>
						<div class="col-xs-12 col-sm-1" id="assignCountText" style="display: <?= $assignDisplay ?>;">
							<?= $form->textFieldGroup($model, 'assignCount', array('label' => 'Count', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Count', 'class' => 'form-control', 'title' => '')))) ?>
						</div>
						<div class="col-xs-12 col-sm-1">
							<label class="control-label">Loss Count</label>
							<?php
							$filters		 = [
								1	 => 'Greater than',
								2	 => 'Less than',
							];
							$dataPay		 = Filter::getJSON($filters);
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'lossCountDrop',
								'val'			 => $model->lossCountDrop,
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($dataPay), 'allowClear' => true),
								'htmlOptions'	 => array('class' => 'p0', 'style' => 'width:100%', 'placeholder' => 'Select loss count')
							));
							?>	
						</div>
						<div class="col-xs-12 col-sm-1" id="lossCountText" style="display:<?= $lossDisplay ?>;">
							<?= $form->textFieldGroup($model, 'lossCount', array('label' => 'Count', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Count', 'class' => 'form-control', 'title' => '')))) ?>
						</div>
						<div class="col-xs-12 col-sm-1">
							<label class="control-label">Margin</label>
							<?php
							$filters		 = [
								1	 => 'Greater than',
								2	 => 'Less than',
							];
							$dataPay		 = Filter::getJSON($filters);
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'netMarginDrop',
								'val'			 => $model->netMarginDrop,
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($dataPay), 'allowClear' => true),
								'htmlOptions'	 => array('class' => 'p0', 'style' => 'width:100%', 'placeholder' => 'Select margin')
							));
							?>	
						</div>
						<div class="col-xs-12 col-sm-5" style="margin-top: 15px;">
							<div class="row">
								<div class="col-xs-12 mt12"> 
									<div style="display: inline-block">
										<?php echo $form->checkboxListGroup($model, 'b2cbookings', array('label' => '', 'inline' => true, 'widgetOptions' => array('data' => array(1 => 'B2C Only ')), 'groupOptions' => ['htmlOptions' => ["style" => "display: inline-block;"]])) ?>
									</div>
									<div style="display: inline-block">
										<?php echo $form->checkboxGroup($model, 'mmtbookings', array('label' => 'MMT')) ?>
									</div>
									<div style="display: inline-block">
										<?php echo $form->checkboxGroup($model, 'nonAPIPartner', array('label' => 'Non API Partner')) ?>
									</div>
									<div style="display: inline-block">
										<?php echo $form->checkboxListGroup($model, 'excludeAT', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Exclude Airport Transfer '), 'htmlOptions' => []))) ?>
									</div>
								</div>
							</div>	
						</div>
						<div class="col-xs-12 col-sm-1" id="netMarginText" style="display:<?= $marginDisplay ?>;">
							<?= $form->textFieldGroup($model, 'netMargin', array('label' => 'Net margin', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Net margin', 'class' => 'form-control', 'title' => '')))) ?>
						</div>
					</div>

					<div class="row">
<div class="col-xs-6 col-sm-2">
							<div style="display: inline-block;">
							<label class="control-label">Cab Type</label><br>
							<?php
							$returnType			 = "listCategory";
							$vehicleList		 = SvcClassVhcCat::getVctSvcList($returnType);
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'bkg_vehicle_type_id',
								'val'			 => $model->bivBkg->bkg_vehicle_type_id,
								'data'			 => $vehicleList,
								'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => 'multiple',
									'placeholder'	 => 'Select Car Type')
							));
							?>
						</div>
						</div>
						<div class="col-xs-6 col-sm-1 mt20">
							<button class="btn btn-primary full-width submitButton" type="submit">Search</button>
						</div>
					</div>
				</div>
			</div>


			<?php $this->endWidget(); ?>

			<div class="col-xs-12">
				<?php
				if ($error != '')
				{
					echo
					"<span class='text-danger'> $error</span>";
				}
				?>
				<?php
				$visible	 = false;
				$cabVisible	 = false;
				if (in_array($model->bkgZoneType, array(2, 3, 5)))
				{
					$visible = true;
				}
				if (in_array($model->bkgZoneType, array(4, 5)))
				{
					$cabVisible = true;
				}

				if (!empty($dataProvider))
				{
					$this->widget('booster.widgets.TbGridView', array(
						'id'				 => 'trip-grid',
						'responsiveTable'	 => true,
						'dataProvider'		 => $dataProvider,
						'template'			 => "<div class='panel-heading'><div class='row m0'>
                                    <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
						'itemsCssClass'		 => 'table table-striped table-bordered mb0',
						'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
						'columns'			 => array(
							array('name' => 'fromZone', 'value' => $data['fromZone'], 'headerHtmlOptions' => array(), 'header' => 'From Zone'),
							array('name' => 'toZone', 'value' => $data['toZone'], 'headerHtmlOptions' => array(), 'header' => 'To Zone/ State', 'visible' => $visible),
							array('name' => 'cabType', 'value' => $data['cabType'], 'headerHtmlOptions' => array(), 'header' => 'Cab Type', 'visible' => $cabVisible),
							array('name' => 'cnt', 'value' => $data["cnt"], 'htmlOptions' => array('class' => 'text-center'), 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'header' => 'Count'),
							array('name'	 => 'newCnt', 'value'	 => function ($data) {
									echo $data['newCnt'] . ($data['newManualCnt'] > 0 || $data['newCriticalCnt'] > 0 ? ' [ M: ' . $data['newManualCnt'] . ', C: ' . $data['newCriticalCnt'] . ' ]' : '');
								}, 'htmlOptions'		 => array('class' => 'text-center'), 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'New Count'),
							array('name' => 'assignedCnt', 'value' => $data["assignedCnt"], 'htmlOptions' => array('class' => 'text-center'), 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'header' => 'Assigned Count'),
							array('name' => 'lossCount', 'value' => $data["lossCount"], 'htmlOptions' => array('class' => 'text-center'), 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'header' => 'Loss Count'),
							array('name' => 'newLossCount', 'value' => $data["newLossCount"], 'htmlOptions' => array('class' => 'text-center'), 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'header' => 'New Loss Count'),
							array('name' => 'assignLossCount', 'value' => $data["assignLossCount"], 'htmlOptions' => array('class' => 'text-center'), 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'header' => 'Assigned Loss Count'),
							array('name' => 'netMargin', 'value' => $data["netMargin"], 'htmlOptions' => array('class' => 'text-right'), 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-right'), 'header' => 'Net Margin'),
							array('name' => 'netLossMargin', 'value' => $data["netLossMargin"], 'htmlOptions' => array('class' => 'text-right'), 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-right'), 'header' => 'Net Loss Margin'),
							array('name' => 'netProfitMargin', 'value' => $data["netProfitMargin"], 'htmlOptions' => array('class' => 'text-right'), 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-right'), 'header' => 'Net Profit Margin'),
							array('name' => 'lossBkgIds', 'value' => $data["lossBkgIds"], 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Loss Bookings'),
							array('name' => 'highMarginBkgIds', 'value' => $data["highMarginBkgIds"], 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'High Margin Bookings'),
					)));
				}
				?>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).on('click', '.submitButton', function () {
		var assignDrop = $("#BookingInvoice_assignCountDrop").val();
		var assignVal = $("#BookingInvoice_assignCount").val();
		var lossDrop = $("#BookingInvoice_lossCountDrop").val();
		var lossVal = $("#BookingInvoice_lossCount").val();
		var netMarginDrop = $("#BookingInvoice_netMarginDrop").val();
		var netMargin = $("#BookingInvoice_netMargin").val();
		if (assignDrop > 0) {
			if (assignVal == '') {
				bootbox.alert("Please enter assign count");
				return false;
			}
		}
		if (lossDrop > 0) {
			if (lossVal == '') {
				bootbox.alert("Please enter loss count");
				return false;
			}
		}
		if (netMarginDrop > 0) {
			if (netMargin == '') {
				bootbox.alert("Please enter net margin");
				return false;
			}
		}
		return true;

	});
	var start = '<?= date('d/m/Y', strtotime($model->from_date)); ?>';
	var end = '<?= date('d/m/Y', strtotime($model->to_date)); ?>';
	$('#pickupDate').daterangepicker(
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
					'Tomorrow': [moment().add(1, 'days'), moment().add(1, 'days')],
					'Last 7 Days': [moment().subtract(6, 'days'), moment()],
					'Next 7 Days': [moment(), moment().add(6, 'days')],
					'Next 15 Days': [moment(), moment().add(15, 'days')],
					'This Month': [moment().startOf('month'), moment().endOf('month')],
					'Next Month': [moment().add(1, 'month').startOf('month'), moment().add(1, 'month').endOf('month')],
				}
			}, function (start1, end1) {
		$('#BookingInvoice_from_date').val(start1.format('YYYY-MM-DD'));
		$('#BookingInvoice_to_date').val(end1.format('YYYY-MM-DD'));
		$('#pickupDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
	});
	$('#pickupDate').on('cancel.daterangepicker', function (ev, picker) {
		$('#pickupDate span').html('Select Pickup Date Range');
		$('#BookingInvoice_from_date').val('');
		$('#BookingInvoice_to_date').val('');
	});

	var createStart = '<?= date('d/m/Y', strtotime($model->create_from_date)); ?>';
	var createEnd = '<?= date('d/m/Y', strtotime($model->create_to_date)); ?>';
	$('#createDate').daterangepicker(
			{
				locale: {
					format: 'DD/MM/YYYY',
					cancelLabel: 'Clear'
				},
				"showDropdowns": true,
				"alwaysShowCalendars": true,
				//startDate: createStart,
				//endDate: createEnd,
				ranges: {
					'Today': [moment(), moment()],
					'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
					'Last 7 Days': [moment().subtract(6, 'days'), moment()],
					'Last 15 Days': [moment().subtract(15, 'days'), moment()],
					'This Month': [moment().startOf('month'), moment().endOf('month')],
					'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
				}
			}, function (start1, end1) {
		$('#BookingInvoice_create_from_date').val(start1.format('YYYY-MM-DD'));
		$('#BookingInvoice_create_to_date').val(end1.format('YYYY-MM-DD'));
		$('#createDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
	});
	$('#createDate').on('cancel.daterangepicker', function (ev, picker) {
		$('#createDate span').html('Select Create Date Range');
		$('#BookingInvoice_create_from_date').val('');
		$('#BookingInvoice_create_to_date').val('');
	});

	$('#BookingInvoice_assignCountDrop').change(function () {
		var assignCount = $('#BookingInvoice_assignCountDrop').val();
		if (assignCount > 0) {
			$("#assignCountText").show("slow");
		} else {
			$("#assignCountText").hide("slow");
			$('#BookingInvoice_assignCount').val('');
		}
	});
	$('#BookingInvoice_lossCountDrop').change(function () {
		var lossCount = $('#BookingInvoice_lossCountDrop').val();
		if (lossCount > 0) {
			$("#lossCountText").show("slow");
		} else {
			$("#lossCountText").hide("slow");
			$('#BookingInvoice_lossCount').val('');
		}
	});
	$('#BookingInvoice_netMarginDrop').change(function () {
		var netMargin = $('#BookingInvoice_netMarginDrop').val();
		if (netMargin > 0) {
			$("#netMarginText").show("slow");
		} else {
			$("#netMarginText").hide("slow");
			$('#BookingInvoice_netMargin').val('');
		}
	});
	
	
</script>