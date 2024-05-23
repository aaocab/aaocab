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
<div class='row'>
	<?php
	$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
		'id'					 => 'booking-form',
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
	<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
		<div class="form-group">
			<label class="control-label">Date Range</label>
			<?php
			$daterang	 = "Select Date Range";
			$from_date	 = ($model->bkg_pickup_date1 == '') ? '' : $model->bkg_pickup_date1;
			$to_date	 = ($model->bkg_pickup_date2 == '') ? '' : $model->bkg_pickup_date2;
			if ($from_date != '' && $to_date != '')
			{
				$daterang = date('F d, Y', strtotime($from_date)) . " - " . date('F d, Y', strtotime($to_date));
			}
			?>
			<div id="bkgPickupDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
				<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
				<span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
			</div>
			<?= $form->hiddenField($model, 'bkg_pickup_date1'); ?>
			<?= $form->hiddenField($model, 'bkg_pickup_date2'); ?>

		</div>
	</div>
	<div class="col-xs-12 col-sm-6 col-md-4 col-lg-2">
		<label class="control-label">Region:</label>
		<?php
		//	$regionarr			 = Promos::model()->getRegion();
		$regionarr			 = States::model()->findRegionName();
		$this->widget('booster.widgets.TbSelect2', array(
			'model'			 => $model,
			'attribute'		 => 'bkg_region',
			'val'			 => explode(',', $model->bkg_region),
			'data'			 => $regionarr,
			'htmlOptions'	 => array('style'			 => 'width:100%',
				'placeholder'	 => 'Select Region', 'style'			 => 'width: 100%')
		));
		?>
	</div>
	<div class="col-xs-12 col-sm-6 col-md-4 col-lg-2">
		<div class="form-group">
			<label class="control-label">Booking Type</label>
			<?php
			$bookingTypesArr	 = $model->booking_type;
			unset($bookingTypesArr[2]);
			$this->widget('booster.widgets.TbSelect2', array(
				'model'			 => $model,
				'attribute'		 => 'bkgtypes',
				'val'			 => $model->bkgtypes,
				'data'			 => $bookingTypesArr,
				//'asDropDownList' => FALSE,
				//'options' => array('data' => new CJavaScriptExpression($datacity), 'allowClear' => true,),
				'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => 'multiple',
					'placeholder'	 => 'Booking Type')
			));
			?>
		</div>
	</div>
	<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
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
				'htmlOptions'		 => array('width' => '100%'),
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
	<div class="col-xs-12 col-sm-6 col-md-4 col-lg-2 mt20">
		<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary full-width submitCbr')); ?>
	</div>
	<?php $this->endWidget(); ?>
</div>
<div>
	<?php
	$checkExportAccess	 = false;
	if ($roles['rpt_export_roles'] != null)
	{
		$checkExportAccess = Filter::checkACL($roles['rpt_export_roles']);
	}
	if ($checkExportAccess)
	{
		echo CHtml::beginForm(Yii::app()->createUrl('report/booking/ZoneZoneBookingCount'), "post", ['style' => "margin-bottom: 10px;margin-top: 10px;"]);
		?>
		<input type="hidden" id="export_fromdate" name="export_fromdate" value="<?= date("Y-m-d", strtotime($model->bkg_pickup_date1)); ?>"/>
		<input type="hidden" id="export_todate" name="export_todate" value="<?= date("Y-m-d", strtotime($model->bkg_pickup_date2)); ?>"/>
		<input type="hidden" id="export_bkgregion" name="export_bkgregion" value="<?= $model->bkg_region; ?>"/>
		<input type="hidden" id="export_bkgtype" name="export_bkgtype" value="<?= implode(",", $model->bkgtypes); ?>"/>
		<input type="hidden" id="export_agentid" name="export_agentid" value="<?= $model->bkg_agent_id; ?>"/>
		<input type="hidden" id="export" name="export" value="true"/>
		<button class="btn btn-default btn-5x pr30 pl30 mt10" type="submit" style="width: 185px;">Export</button>
		<?php
		echo CHtml::endForm();
	}
	?>
</div>
<?php
if (!empty($dataProvider))
{

	$params									 = array_filter($_REQUEST);
	$dataProvider->getPagination()->params	 = $params;
	$dataProvider->getSort()->params		 = $params;
	$this->widget('booster.widgets.TbGridView', array(
		'responsiveTable'	 => true,
		'dataProvider'		 => $dataProvider,
		'template'			 => "<div class='panel-heading'><div class='row m0'>
								<div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
								</div></div>
								<div class='panel-body table-responsive'>{items}</div>
								<div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
		'itemsCssClass'		 => 'table table-striped table-bordered dataTable mb0',
		'htmlOptions'		 => array('class' => 'panel panel-primary  compact'),
		'columns'			 =>
		array
			(
			array('name' => 'cnt', 'value' => $data['cnt'], 'sortable' => true, 'headerHtmlOptions' => array('class' => ''), 'header' => 'Count'),
			array('name' => 'PickupDate', 'value' => $data['PickupDate'], 'sortable' => true, 'headerHtmlOptions' => array('class' => ''), 'header' => 'Pickup Date'),
			array('name' => 'zon_name', 'value' => $data['zon_name'], 'sortable' => true, 'headerHtmlOptions' => array('class' => ''), 'header' => 'Zon Name'),
			array('name' => 'trip_type', 'value' => $data['trip_type'], 'sortable' => true, 'headerHtmlOptions' => array('class' => ''), 'header' => 'Trip Type'),
		)
	));
}
?>

<script>
    var start = '<?= date('d/m/Y', strtotime('-15 days')); ?>';
    var end = '<?= date('d/m/Y'); ?>';

    $('#bkgPickupDate').daterangepicker(
            {
                locale: {
                    format: 'DD/MM/YYYY',
                    cancelLabel: 'Clear'
                },
                dateLimit: {
                    'months': 1,
                    'days': -1
                },
                "showDropdowns": true,
                "alwaysShowCalendars": true,
                startDate: start,
                endDate: end,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 31 Days': [moment().subtract(31, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, function (start1, end1)
    {
        $('#bkg_pickup_date1, #Booking_bkg_pickup_date1').val(start1.format('YYYY-MM-DD'));
        $('#bkg_pickup_date2, #Booking_bkg_pickup_date2').val(end1.format('YYYY-MM-DD'));
        $('#bkgPickupDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
    });
    $('#bkgPickupDate').on('cancel.daterangepicker', function (ev, picker)
    {
        $('#bkgPickupDate span').html('Select Pickup Date Range');
        $('#bkg_pickup_date1, #Booking_bkg_pickup_date1').val('');
        $('#bkg_pickup_date2, #Booking_bkg_pickup_date2').val('');
    });
</script>

