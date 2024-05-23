<?php
$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
					'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
					'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
					'openOnFocus'		 => true, 'preload'			 => false,
					'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
					'addPrecedence'		 => false,];
?>

<style type="text/css">
    .cityinput > .selectize-control>.selectize-input{
        width:100% !important;
    }
</style>
<div class="row">
	<div class="panel" >
	<div class="panel-body">
		<?php
		$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
		'id' => 'penaltyTypeReport-form', 'enableClientValidation' => true,
		'clientOptions' => array(
			'validateOnSubmit' => true,
			'errorCssClass' => 'has-error'
		),
		// Please note: When you enable ajax validation, make sure the corresponding
		// controller action is handling ajax validation correctly.
		// See class documentation of CActiveForm for details on this,
		// you need to use the performAjaxValidation()-method described there.
		'enableAjaxValidation' => false,
		'errorMessageCssClass' => 'help-block',
		'htmlOptions' => array(
			'class' => '',
		),
		));
		/* @var $form TbActiveForm */
		?>

		<div class="col-xs-12 col-sm-2 col-md-2 col-lg-2" >
			<div class="form-group"> 
				<label class="control-label">Group By </label>
				<?php
				$typeArr = [1 => 'Create Date', 2 => 'Pickup Date'];
				$type	 = Filter::getJSON($typeArr);
				$this->widget('booster.widgets.TbSelect2', array(
				'model'			 => $model,
				'attribute'		 => 'dateType',
				'val'			 => $dataType,
				'asDropDownList' => FALSE,
				'options'		 => array('data' => new CJavaScriptExpression($type), 'allowClear' => false),
				'htmlOptions'	 => array('class' => 'p0', 'style' => 'width:100%', 'placeholder' => 'Select Date type')
			));
				?>

			</div>
		</div>

		<div class="col-xs-12 col-sm-4 col-md-4 col-lg-3 mdcdate">
			<div class="form-group">
				<label class="control-label">Date Range</label>
				 <?php
				 $daterang = "Select Date Range";
				 $from_date  = ($model->mdcDate1 == '') ? '' : $model->mdcDate1;
				 $to_date = ($model->mdcDate2 == '') ? '' : $model->mdcDate2;
				 if ($from_date  != '' && $to_date != '')
				 {
					$daterang = date('F d, Y', strtotime($from_date)) . " - " . date('F d, Y', strtotime($to_date));
				 }
				 ?>
				 <div id="createDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
					 <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
					 <span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
				 </div>
				 <?= $form->hiddenField($model, 'mdcDate1'); ?>
				<?= $form->hiddenField($model, 'mdcDate2'); ?>
               
			</div>
		 </div>	
		 
		 <div class="col-xs-12 col-sm-2 col-md-2">
			<div class="form-group">
				<label class="control-label">Booking Type</label>

				<?php
				$bookingTypesArr	 = Booking::model()->booking_type;
				unset($bookingTypesArr[2]);
				$this->widget('booster.widgets.TbSelect2', array(
					'model'			 => $model,
					'attribute'		 => 'bookingType',
					'val'			 => $model->bookingType,
					'data'			 => $bookingTypesArr,
					//'asDropDownList' => FALSE,
					//'options' => array('data' => new CJavaScriptExpression($datacity), 'allowClear' => true,),
					'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => 'multiple',
						'placeholder'	 => 'Booking Type')
				));
				?>
			</div>
		</div>

		<div class="col-xs-12 col-sm-2">
			<div class="form-group">
				<label>From Zone</label>
				<?php
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
		
		<div class="col-xs-12 col-sm-2">
			<div class="form-group">
				<label>To Zone</label>
				<?php
					$this->widget('ext.yii-selectize.YiiSelectize', array(
						'model'				 => $model,
						'attribute'			 => 'destinaitonzone',
						'useWithBootstrap'	 => true,
						"placeholder"		 => "Select Zone",
						'fullWidth'			 => false,
						'options'			 => array('allowClear' => true),
						'htmlOptions'		 => array('width'	 => '100%'),
						'defaultOptions'	 => $selectizeOptions + array(
					'onInitialize'	 => "js:function(){
									populateZone(this, '{$model->destinaitonzone}');
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

		<div class="col-xs-12 col-sm-2 col-md-2">
				<div class="form-group">
					<label class="control-label">From City</label>
					<?php
					$this->widget('ext.yii-selectize.YiiSelectize', array(
						'model'				 => $model,
						'attribute'			 => 'fromcity',
						'useWithBootstrap'	 => true,
						"placeholder"		 => "Select Source City",
						'fullWidth'			 => false,
						'options'			 => array('allowClear' => true),
						'htmlOptions'		 => array('width'	 => '100%',
							'id'	 => 'fromcity1'
						),
						'defaultOptions'	 => $selectizeOptions + array(
					'onInitialize'	 => "js:function(){
									populateSource(this, '{$model->fromcity}');
										}",
					'load'			 => "js:function(query, callback){
									loadSource(query, callback);
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
					<span class="has-error"><? echo $form->error($model, 'fromcity'); ?></span>

				</div>
		</div>
		<div class="col-xs-12 col-sm-2 col-md-2">
			<div class="form-group">
				<label class="control-label">To City</label>
				<?php
				$this->widget('ext.yii-selectize.YiiSelectize', array(
					'model'				 => $model,
					'attribute'			 => 'tocity',
					'useWithBootstrap'	 => true,
					"placeholder"		 => "Select Destination City",
					'fullWidth'			 => false,
					'options'			 => array('allowClear' => true),
					'htmlOptions'		 => array('width'	 => '100%',
						'id'	 => 'tocity1'
					),
					'defaultOptions'	 => $selectizeOptions + array(
				'onInitialize'	 => "js:function(){
								populateSource(this, '{$model->tocity}');
									}",
				'load'			 => "js:function(query, callback){
								loadSource(query, callback);
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
				<span class="has-error"><? echo $form->error($model, 'tocity'); ?></span>
			</div> 
		</div>
		<div class="col-xs-12 col-sm-2 col-md-2 pt20 "> 
			<input type="checkbox" name="routes" id="routes" >Show Routes
		</div>

		<div class="col-xs-12 col-sm-3 mt5"><br>
			<button class="btn btn-primary full-width" type="submit"  name="penaltyTypeReports">Search</button>
		</div>
    <div class="col-xs-12">
		<?php
		if (!empty($dataProvider))
		{
			$this->widget('booster.widgets.TbGridView', array(
				'id'				 => 'route-grid',
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
				array('name' => 'fzonename', 'value'	 => '$data["fzonename"]', 'sortable' => true, 'headerHtmlOptions'	 => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-left'), 'header' => 'From Zone Name'),
				array('name' => 'tzonename', 'value' => '$data["tzonename"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-left'),'header' => 'To Zone Name'),
				array('name' => 'routes', 'value' => '$data["routes"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center routes hide'),'htmlOptions' => array('class' => 'text-left routes hide'), 'header' => 'Routes'),
				array('name' => 'mdc_booking_type', 'value' => 
							function ($data) {
								$bookingType = $data['bookingType'];
								$bkgType	 = Booking::model()->getBookingType($bookingType);
								echo $bkgType;
						}, 
					'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'),'htmlOptions' => array('class' => 'text-center'), 'header' => 'Booking Type'),
				array('name' => 'date', 'value' => function ($data) {
									return DateTimeFormat::DateToLocale($data["date"]);
								},
					 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'),'htmlOptions' => array('class' => 'text-center'), 'header' => 'Date'),
				array('name' => 'searchCnt', 'value' => '$data["searchCnt"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'),'htmlOptions' => array('class' => 'text-center'), 'header' => 'Search Count'),
				array('name' => 'holdCnt', 'value' => '$data["holdCnt"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'),'htmlOptions' => array('class' => 'text-center'), 'header' => 'Hold Count'),
				array('name' => 'confirmCnt', 'value' => '$data["confirmCnt"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'),'htmlOptions' => array('class' => 'text-center'), 'header' => 'Confirm Count'),
				array('name' => 'blockedCnt', 'value' => '$data["blockedCnt"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'),'htmlOptions' => array('class' => 'text-center'), 'header' => 'Blocked Count'),
				array('name' => 'errorCnt', 'value' => '$data["errorCnt"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'),'htmlOptions' => array('class' => 'text-center'), 'header' => 'Error Count')
			)));
		}
		?>
    </div>
	
	<?php $this->endWidget(); ?>
  </div>
	</div>
</div>
<script>
    var start = '<?= date('d/m/Y', strtotime($model->mdcDate1)); ?>';
    var end = '<?= date('d/m/Y', strtotime($model->mdcDate2)); ?>';
    $('#createDate').daterangepicker(
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
                    'Last 15 Days': [moment().subtract(15, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                }
            }, function (start1, end1) {
        $('#Zones_mdcDate1').val(start1.format('YYYY-MM-DD'));
        $('#Zones_mdcDate2').val(end1.format('YYYY-MM-DD'));
        $('#createDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
    });
	$('#createDate').on('cancel.daterangepicker', function (ev, picker) {
		$('#createDate span').html('Select Create Date Range');
		$('#Zones_mdcDate1').val('');
		$('#Zones_mdcDate2').val('');
	});
	
	function populateSource(obj, cityId)
    {

        obj.load(function (callback)
        {
            var obj = this;
            if ($sourceList == null)
            {
                xhr = $.ajax({
                    url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery', ['apshow' => 1, 'city' => ''])) ?>' + cityId,
                    dataType: 'json',
                    success: function (results)
                    {
                        $sourceList = results;
                        obj.enable();
                        callback($sourceList);
                        obj.setValue(cityId);
                    },
                    error: function ()
                    {
                        callback();
                    }
                });
            } else
            {
                obj.enable();
                callback($sourceList);
                obj.setValue(cityId);
            }
        });
    }
    function loadSource(query, callback)
    {
        //	if (!query.length) return callback();
        $.ajax({
            url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery')) ?>?apshow=1&q=' + encodeURIComponent(query),
            type: 'GET',
            dataType: 'json',
            global: false,
            error: function ()
            {
                callback();
            },
            success: function (res)
            {
                callback(res);
            }
        });
    }
	
	$('#routes').click(function() {
		if ($('#routes').is(":checked"))
		{
		  $('.routes').removeClass('hide');	
		}
		else{
			$('.routes').addClass('hide');	
		}
	});
</script>