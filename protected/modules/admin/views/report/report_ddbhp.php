<style type="text/css">
    .cityinput > .selectize-control>.selectize-input{
        width:100% !important;
    }
</style>
<?php
$activeDisplay	 = ($model->activeCount != '' && $model->activeCount != NULL) ? "block" : "none";
$profitDisplay	 = ($model->profitCount != '' && $model->profitCount != NULL) ? "block" : "none";
$lossDisplay	 = ($model->lossCount != '' && $model->lossCount != NULL) ? "block" : "none";
$marginDisplay	 = ($model->netMarginCount != '' && $model->netMarginCount != NULL) ? "block" : "none";
$markupDisplay	 = ($model->markupCount != '' && $model->markupCount != NULL) ? "block" : "none";
?>
<div class="row">
	<div class="panel" >
		<div class="panel-body">
			<?php
			$form			 = $this->beginWidget('booster.widgets.TbActiveForm', array(
				'id'					 => 'ddbhp-form', 'enableClientValidation' => true,
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
							$daterang		 = "Select Date Range";
							$from_date		 = ($model->from_date == '') ? '' : $model->from_date;
							$to_date		 = ($model->to_date == '') ? '' : $model->to_date;
							if ($from_date != '' && to_date != '')
							{
								$daterang = date('F d, Y', strtotime($from_date)) . " - " . date('F d, Y', strtotime($to_date));
							}
							?>
							<div id="bookingDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
								<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
								<span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
							</div>
							<?= $form->hiddenField($model, 'from_date'); ?>
							<?= $form->hiddenField($model, 'to_date'); ?>
						</div>
						<div class="col-xs-12 col-sm-3"> 
							<label class="control-label" style="margin-left:5px;">Area Type</label>
							<?php
							$areaArr			 = DynamicDemandSupplySurge::model()->getAreaType();
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'areaType',
								'data'			 => $areaArr,
								'options'		 => array('allowClear' => true),
								'htmlOptions'	 => array(
									'class'			 => 'p0',
									'style'			 => 'width:100%;margin-left:5px;',
									'multiple'		 => false,
									'placeholder'	 => 'Select Vehicle Category'
								)
							));
							?>
						</div>
						<div class="col-xs-12 col-sm-2">
							<label class="control-label">From Zone</label>
							<?php
							$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
								'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
								'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
								'openOnFocus'		 => true, 'preload'			 => false,
								'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
								'addPrecedence'		 => false,];
							$this->widget('ext.yii-selectize.YiiSelectize', array(
								'model'				 => $model,
								'attribute'			 => 'fromZone',
								'useWithBootstrap'	 => true,
								"placeholder"		 => "Select Zone",
								'fullWidth'			 => false,
								'options'			 => array('allowClear' => true),
								'htmlOptions'		 => array('width' => '100%'),
								'defaultOptions'	 => $selectizeOptions + array(
							'onInitialize'	 => "js:function(){
															populateZone(this, '{$model->fromZone}');
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
						<div class="col-xs-12 col-sm-2">
							<label class="control-label">To Zone</label>
							<?php
							$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
								'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
								'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
								'openOnFocus'		 => true, 'preload'			 => false,
								'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
								'addPrecedence'		 => false,];
							$this->widget('ext.yii-selectize.YiiSelectize', array(
								'model'				 => $model,
								'attribute'			 => 'toZone',
								'useWithBootstrap'	 => true,
								"placeholder"		 => "Select Zone",
								'fullWidth'			 => false,
								'options'			 => array('allowClear' => true),
								'htmlOptions'		 => array('width' => '100%'),
								'defaultOptions'	 => $selectizeOptions + array(
							'onInitialize'	 => "js:function(){
													populateZone(this, '{$model->toZone}');
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
						<div class="col-xs-12 col-sm-2"> 
							<label class="control-label" style="margin-left:5px;">Booking Type</label>
							<?php
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'bkgTypes',
								'data'			 => Booking::model()->bkgtype,
								'htmlOptions'	 => array('class'			 => 'p0', 'style'			 => 'width:100%;margin-left:5px;', 'multiple'		 => 'multiple',
									'placeholder'	 => 'Booking Type')
							));
							?>

						</div>
					</div>
					<div class="row mt10">
						<div class="col-xs-12 col-sm-2"> 
							<label class="control-label" style="margin-left:5px;">Vehicle Category</label>
							<?php
							$categoryList		 = VehicleCategory::getCat();
							$categoryListArr	 = array_intersect_key($categoryList, array_flip(['1', '2', '3']));
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'vehicleCategory',
								'data'			 => $categoryListArr,
								'htmlOptions'	 => array('class'			 => 'p0',
									'style'			 => 'width:100%;margin-left:5px;',
									'multiple'		 => 'multiple',
									'placeholder'	 => 'Select Vehicle Category'
								)
							));
							?>
						</div>
						<div class="col-xs-12 col-sm-1">
							<label class="control-label">Active Count</label>
							<?php
							$filters			 = [
								1	 => 'Greater than',
								2	 => 'Less than',
							];
							$dataPay			 = Filter::getJSON($filters);
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'activeCountDrop',
								'val'			 => $model->activeCountDrop,
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($dataPay), 'allowClear' => true),
								'htmlOptions'	 => array('class' => 'p0', 'style' => 'width:100%', 'placeholder' => 'Select active count')
							));
							?>	
						</div>
						<div class="col-xs-12 col-sm-1" id="activeCountText" style="display: <?= $activeDisplay ?>;">
							<?= $form->numberFieldGroup($model, 'activeCount', array('label' => 'Count', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Count', 'class' => 'form-control', 'title' => '')))) ?>
						</div>
						<div class="col-xs-12 col-sm-1">
							<label class="control-label">Profit Count</label>
							<?php
							$filters			 = [
								1	 => 'Greater than',
								2	 => 'Less than',
							];
							$dataPay			 = Filter::getJSON($filters);
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'profitCountDrop',
								'val'			 => $model->profitCountDrop,
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($dataPay), 'allowClear' => true),
								'htmlOptions'	 => array('class' => 'p0', 'style' => 'width:100%', 'placeholder' => 'Select profit count')
							));
							?>	
						</div>
						<div class="col-xs-12 col-sm-1" id="profitCountText" style="display: <?= $profitDisplay ?>;">
							<?= $form->numberFieldGroup($model, 'profitCount', array('label' => 'Count', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Count', 'class' => 'form-control', 'title' => '')))) ?>
						</div>
						<div class="col-xs-12 col-sm-1">
							<label class="control-label">Loss Count</label>
							<?php
							$filters			 = [
								1	 => 'Greater than',
								2	 => 'Less than',
							];
							$dataPay			 = Filter::getJSON($filters);
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'lossCountDrop',
								'val'			 => $model->lossCountDrop,
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($dataPay), 'allowClear' => true),
								'htmlOptions'	 => array('class' => 'p0', 'style' => 'width:100%', 'placeholder' => 'Select profit count')
							));
							?>	
						</div>
						<div class="col-xs-12 col-sm-1" id="losstCountText" style="display: <?= $lossDisplay ?>;">
							<?= $form->numberFieldGroup($model, 'lossCount', array('label' => 'Count', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Count', 'class' => 'form-control', 'title' => '')))) ?>
						</div>
						<div class="col-xs-12 col-sm-1">
							<label class="control-label">Net Margin</label>
							<?php
							$filters			 = [
								1	 => 'Greater than',
								2	 => 'Less than',
							];
							$dataPay			 = Filter::getJSON($filters);
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'netMarginDrop',
								'val'			 => $model->netMarginDrop,
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($dataPay), 'allowClear' => true),
								'htmlOptions'	 => array('class' => 'p0', 'style' => 'width:100%', 'placeholder' => 'Select net margin')
							));
							?>	
						</div>
						<div class="col-xs-12 col-sm-1" id="netMarginCountText" style="display: <?= $marginDisplay ?>;">
							<?= $form->numberFieldGroup($model, 'netMarginCount', array('label' => 'Count', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Count', 'class' => 'form-control', 'title' => '')))) ?>
						</div>
						<div class="col-xs-12 col-sm-1">
							<label class="control-label">Markup</label>
							<?php
							$filters			 = [
								1	 => 'Greater than',
								2	 => 'Less than',
							];
							$dataPay			 = Filter::getJSON($filters);
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'markupDrop',
								'val'			 => $model->markupDrop,
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($dataPay), 'allowClear' => true),
								'htmlOptions'	 => array('class' => 'p0', 'style' => 'width:100%', 'placeholder' => 'Select net margin')
							));
							?>	
						</div>
						<div class="col-xs-12 col-sm-1" id="markupCountText" style="display: <?= $markupDisplay ?>;">
							<?= $form->numberFieldGroup($model, 'markupCount', array('label' => 'Count', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Count', 'class' => 'form-control', 'title' => '')))) ?>
						</div>
						<div class="col-xs-12 col-sm-2 mt20"> 
							<?php echo $form->checkboxListGroup($model, 'dds_apply_markup', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Is Markup Apply '), 'htmlOptions' => []))) ?>
						</div>
						<div class="col-xs-12 col-sm-2 mt20">
							<button class="btn btn-primary full-width submitButton" type="submit"  name="accountingFlag">Search</button>
						</div>
					</div>
				</div>
			</div>
			<?php $this->endWidget(); ?>

			<div class="col-xs-12">
				<?php
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
							array('name'			 => 'dds_key_desc', 'htmlOptions'	 => array('style' => 'word-wrap: break-word;', 'class' => ''), 'value'			 => function ($data) {
									echo $data['dds_key_desc'] . "<br>" . $data['dds_key'];
								}, 'header' => 'Description'),
							array('name' => 'dds_pickup_date', 'value' => $data['dds_pickup_date'], 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Pickup Date'),
							array('name'	 => 'dds_area_type', 'value'	 => function ($data) {
									echo DynamicDemandSupplySurge::model()->getAreaType($data['dds_area_type']);
								}, 'headerHtmlOptions'	 => array(), 'header'			 => 'Area Type'),
							array('name' => 'fromZone', 'value' => $data['fromZone'], 'header' => 'From Zone'),
							array('name' => 'toZone', 'value' => $data['toZone'], 'header' => 'To Zone'),
							array('name'	 => 'dds_trip_type', 'value'	 => function ($data) {
									if ($data['dds_trip_type'] > 0)
									{
										echo Booking::model()->getBookingType($data['dds_trip_type']);
									}
									else
									{
										echo "-";
									}
								}, 'headerHtmlOptions'	 => array('class' => 'text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Trip Type'),
							array('name'	 => 'dds_vhc_cat', 'value'	 => function ($data) use ($arrVhcCat) {
									if ($data['dds_vhc_cat'] > 0)
									{
										echo $arrVhcCat[$data['dds_vhc_cat']];
									}
									else
									{
										echo "-";
									}
								}, 'headerHtmlOptions'	 => array('class' => 'text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Vehicle Category'),
							array('name' => 'dds_bkg_active_count', 'value' => $data['dds_bkg_active_count'], 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Active Count'),
							array('name' => 'dds_bkg_gozo_cancelled_count', 'value' => $data['dds_bkg_gozo_cancelled_count'], 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Cancelled Count'),
							array('name' => 'dds_bkg_manual_count', 'value' => $data['dds_bkg_manual_count'], 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Manual Count'),
							array('name' => 'dds_bkg_critical_count', 'value' => $data['dds_bkg_critical_count'], 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Critical Count'),
							array('name' => 'dds_bkg_profit_count', 'value' => $data['dds_bkg_profit_count'], 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Profit Count'),
							array('name' => 'dds_bkg_loss_count', 'value' => $data['dds_bkg_loss_count'], 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Loss Count'),
							array('name' => 'dds_net_margin', 'value' => $data['dds_net_margin'], 'headerHtmlOptions' => array('class' => 'text-right'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Net Margin'),
							array('name' => 'dds_markup', 'value' => $data['dds_markup'], 'headerHtmlOptions' => array('class' => 'text-right'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Markup'),
							array(
								'header'			 => 'Action',
								'class'				 => 'CButtonColumn',
								'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
								'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
								'template'			 => '{markupOff}{markupOn}',
								'buttons'			 => array(
									'markupOff'		 => array(
//								'click'		 => 'function(){
//												var con = confirm("Are you sure you want to Markup Off?");
//												return con;
//												}',
										'url'		 => 'Yii::app()->createUrl("aaohome/report/changeMarkup", array("dds_key" =>$data[dds_key]))',
										'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\active.png',
										'visible'	 => '($data[dds_apply_markup] == 1)',
										'label'		 => '<i class="fa fa-toggle-on"></i>',
										'options'	 => array('data-toggle' => 'ajaxModal', 'id' => 'markupOff', 'style' => '', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs p0', 'title' => 'Markup Off')
									),
									'markupOn'		 => array(
//								'click'		 => 'function(){
//												var con = confirm("Are you sure you want to Markup On?");
//												return con;
//												}',
										'url'		 => 'Yii::app()->createUrl("aaohome/report/changeMarkup", array("dds_key" =>$data[dds_key]))',
										'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\inactive.png',
										'visible'	 => '($data[dds_apply_markup] == 0 || $data[dds_apply_markup] == NULL)',
										'label'		 => '<i class="fa fa-toggle-on"></i>',
										'options'	 => array('data-toggle' => 'ajaxModal', 'id' => 'markupOn', 'style' => '', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs p0', 'title' => 'Markup On')
									),
									'htmlOptions'	 => array('class' => 'center'),
								))
					)));
				}
				?>
			</div>


		</div>
	</div>
</div>
<script>
	$(document).on('click', '.submitButton', function () {
		var activeDrop = $("#DynamicDemandSupplySurge_activeCountDrop").val();
		var activeVal = $("#DynamicDemandSupplySurge_activeCount").val();
		var profitDrop = $("#DynamicDemandSupplySurge_profitCountDrop").val();
		var profitVal = $("#DynamicDemandSupplySurge_profitCount").val();
		var lossDrop = $("#DynamicDemandSupplySurge_lossCountDrop").val();
		var lossVal = $("#DynamicDemandSupplySurge_lossCount").val();
		var marginDrop = $("#DynamicDemandSupplySurge_netMarginDrop").val();
		var marginVal = $("#DynamicDemandSupplySurge_netMarginCount").val();
		var markupDrop = $("#DynamicDemandSupplySurge_markupDrop").val();
		var markupVal = $("#DynamicDemandSupplySurge_markupCount").val();
		if (activeDrop > 0) {
			if (activeVal == '') {
				bootbox.alert("Please enter active count");
				return false;
			}
		}
		if (profitDrop > 0) {
			if (profitVal == '') {
				bootbox.alert("Please enter profit count");
				return false;
			}
		}
		if (lossDrop > 0) {
			if (lossVal == '') {
				bootbox.alert("Please enter loss count");
				return false;
			}
		}
		if (marginDrop > 0) {
			if (marginVal == '') {
				bootbox.alert("Please enter margin count");
				return false;
			}
		}
		if (markupDrop > 0) {
			if (markupVal == '') {
				bootbox.alert("Please enter markup count");
				return false;
			}
		}
		return true;

	});
	var start = '<?= date('d/m/Y'); ?>';
	var end = '<?= date('d/m/Y', strtotime('+2 day')); ?>';
	$('#bookingDate').daterangepicker(
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
					'Next 7 Days': [moment(), moment().add(6, 'days')],
					'Next 15 Days': [moment(), moment().add(15, 'days')],
					'This Month': [moment().startOf('month'), moment().endOf('month')],
					'Next Month': [moment().add(1, 'month').startOf('month'), moment().add(1, 'month').endOf('month')],
				}
			}, function (start1, end1) {
		$('#DynamicDemandSupplySurge_from_date').val(start1.format('YYYY-MM-DD'));
		$('#DynamicDemandSupplySurge_to_date').val(end1.format('YYYY-MM-DD'));
		$('#bookingDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
	});
	$('#DynamicDemandSupplySurge_activeCountDrop,#DynamicDemandSupplySurge_profitCountDrop,#DynamicDemandSupplySurge_lossCountDrop,#DynamicDemandSupplySurge_netMarginDrop,#DynamicDemandSupplySurge_markupDrop').change(function () {
		var activeCount = $('#DynamicDemandSupplySurge_activeCountDrop').val();
		var profitCount = $('#DynamicDemandSupplySurge_profitCountDrop').val();
		var lossCount = $('#DynamicDemandSupplySurge_lossCountDrop').val();
		var marginCount = $('#DynamicDemandSupplySurge_netMarginDrop').val();
		var markupCount = $('#DynamicDemandSupplySurge_markupDrop').val();
		if (activeCount > 0) {
			$("#activeCountText").show("slow");
		} else {
			$("#activeCountText").hide("slow");
			$('#DynamicDemandSupplySurge_activeCount').val('');
		}
		if (profitCount > 0) {
			$("#profitCountText").show("slow");
		} else {
			$("#profitCountText").hide("slow");
			$('#DynamicDemandSupplySurge_profitCount').val('');
		}
		if (lossCount > 0) {
			$("#losstCountText").show("slow");
		} else {
			$("#losstCountText").hide("slow");
			$('#DynamicDemandSupplySurge_lossCount').val('');
		}
		if (marginCount > 0) {
			$("#netMarginCountText").show("slow");
		} else {
			$("#netMarginCountText").hide("slow");
			$('#DynamicDemandSupplySurge_netMarginCount').val('');
		}
		if (markupCount > 0) {
			$("#markupCountText").show("slow");
		} else {
			$("#markupCountText").hide("slow");
			$('#DynamicDemandSupplySurge_markupCount').val('');
		}
	});

</script>