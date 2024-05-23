<?php
$stateList			 = array("" => "Select state") + CHtml::listData(States::model()->findAll('stt_active = :act AND stt_country_id = :con order by stt_name', array(':act' => '1', ':con' => '99')), 'stt_id', 'stt_name');
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

	<?php
	$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
		'id'					 => 'price-surge-form-form',
		'clientOptions'			 => array(
			'validateOnSubmit'	 => true,
			'errorCssClass'		 => 'has-error',
			'afterValidate'		 => 'js:function(form,data,hasError){
                if(!hasError){
                 
                    $.ajax({
                    "type":"POST",
                    "dataType":"json",                  
                    "url":"' . CHtml::normalizeUrl(Yii::app()->request->url) . '",
                    "data":form.serialize(),
                    "beforeSend": function () {
                        ajaxindicatorstart("");
                    },
                    "complete": function () {  
                        ajaxindicatorstop();
                    },
                    "success":function(data1){
                        if(data1.success){
			    alert(data1.message);
                        } else{
                            var errors = data1.errors;
                            settings=form.data(\'settings\');
                             $.each (settings.attributes, function (i) {
                                $.fn.yiiactiveform.updateInput (settings.attributes[i], errors, form);
                              });
                              $.fn.yiiactiveform.updateSummary(form, errors);
                            } 
                        },
                     error: function(xhr, status, error){
                         }
                    });

                    }
                }'
		),
		'enableAjaxValidation'	 => false,
	));
	?>

	<?php echo $form->errorSummary($model); ?>
	<?php if (Yii::app()->user->hasFlash('success')): ?>
		<div class="col-xs-12 text-success text-center">
			<?php echo Yii::app()->user->getFlash('success'); ?>
		</div>
	<?php endif; ?> 
	<?
	if (Yii::app()->user->hasFlash('error'))
	{
		?>
	    <div class="col-xs-12 alert-error text-center" style="color: #ff0000">
			<?php echo Yii::app()->user->getFlash('error'); ?>
	    </div>
	<? } ?> 
    <div class="col-xs-6 col-sm-4 col-md-6 col-lg-4" style="min-width: 315px">
		<?
		$daterang	 = "Select Price Surge Date Range";
		$createdate1 = ($model->prc_from_date == '') ? '' : $model->prc_from_date;
		$createdate2 = ($model->prc_to_date == '') ? '' : $model->prc_to_date;

		if ($createdate1 != '' && $createdate2 != '')
		{
			$daterang = date('F d, Y', strtotime($createdate1)) . " - " . date('F d, Y', strtotime($createdate2));
		}
		?>
        <label  class="control-label">Price Surge Date Range</label>
        <div id="bkgSurgeDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
            <span><?= $daterang ?></span> <b class="caret"></b>
        </div>
		<?
		echo $form->hiddenField($model, 'prc_from_date');
		echo $form->hiddenField($model, 'prc_to_date');
		?>
    </div>

    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
		<?php echo $form->dropDownListGroup($model, 'prc_value_type', ['label' => 'Value Type', 'widgetOptions' => ['data' => [1 => 'Amount', 2 => 'Percentage']]]); ?>
		<?php echo $form->error($model, 'prc_value_type'); ?>
    </div>

    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
		<?php echo $form->numberFieldGroup($model, 'prc_value'); ?>
    </div>

    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
        <div class="form-group cityinput">
            <label>Source City</label>
			<?php
//        $this->widget('booster.widgets.TbSelect2', array(
//            'model' => $model,
//            'attribute' => 'prc_source_city',
//            'val' => $model->prc_source_city,
//            'asDropDownList' => FALSE,
//            // 'data' => $cityList2,
//            'options' => array('data' => new CJavaScriptExpression('$cityList')),
//            'htmlOptions' => array('style' => 'width:100%', 'placeholder' => 'Select Source City')
//        ));

			$this->widget('ext.yii-selectize.YiiSelectize', array(
				'model'				 => $model,
				'attribute'			 => 'prc_source_city',
				'useWithBootstrap'	 => true,
				"placeholder"		 => "Select Source City",
				'fullWidth'			 => false,
				'options'			 => array('allowClear' => true),
				'htmlOptions'		 => array('width'	 => '100%',
					'id'	 => 'from_city_id1'
				),
				'defaultOptions'	 => $selectizeOptions + array(
			'onInitialize'	 => "js:function(){
                                  populateSource(this, '{$model->prc_source_city}');
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
                        }", 'allowClear'	 => true
				),
			));
			?>
			<?php echo $form->error($model, 'prc_source_city'); ?>
        </div>
    </div> 

    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
        <div class="form-group cityinput">
            <label>Destination City</label>
			<?php
			$this->widget('ext.yii-selectize.YiiSelectize', array(
				'model'				 => $model,
				'attribute'			 => 'prc_destination_city',
				'useWithBootstrap'	 => true,
				"placeholder"		 => "Select Destination City",
				'fullWidth'			 => false,
				'htmlOptions'		 => array('width'	 => '100%',
					'id'	 => 'to_city_id1'
				),
				'defaultOptions'	 => $selectizeOptions + array(
			'onInitialize'	 => "js:function(){
                                  populateSource(this, '{$model->prc_destination_city}');
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
			<?php echo $form->error($model, 'prc_destination_city'); ?>
        </div>
    </div>

    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 ">
        <div class="form-group cityinput">
			<?php // echo $form->textFieldGroup($model,'prc_source_zone');    ?>
            <label>Source Zone</label>
			<?php
			$loc			 = Zones::model()->getZoneList();
			$SubgroupArray	 = CHtml::listData(Zones::model()->getZoneList(), 'zon_id', function ($loc) {
						return $loc->zon_name;
					});
			$this->widget('booster.widgets.TbSelect2', array(
				'name'			 => 'prc_source_zone',
				'model'			 => $model,
				'data'			 => $SubgroupArray,
				'value'			 => $model->prc_source_zone,
				'options'		 => array('allowClear' => true),
				'htmlOptions'	 => array(
					'placeholder'	 => 'Source Zone',
					'width'			 => '100%',
					'style'			 => 'width:100%',
				),
			));
			?>
			<?php echo $form->error($model, 'prc_source_zone'); ?>
        </div></div>

    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
        <div class="form-group cityinput">
			<?php // echo $form->textFieldGroup($model,'prc_destination_zone');      ?>
            <label>Destination Zone</label>
			<?php
			$this->widget('booster.widgets.TbSelect2', array(
				'name'			 => 'prc_destination_zone',
				'model'			 => $model,
				'data'			 => $SubgroupArray,
				'value'			 => $model->prc_destination_zone,
				'options'		 => array('allowClear' => true),
				'htmlOptions'	 => array(
					'placeholder'	 => 'Destination Zone',
					'width'			 => '100%',
					'style'			 => 'width:100%',
				),
			));
			?>
			<?php echo $form->error($model, 'prc_destination_zone'); ?>
        </div> </div>


    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
        <div class="form-group cityinput">
            <label>Source State</label>
			<?php
			$dataState	 = VehicleTypes::model()->getJSON($stateList);
			$this->widget('booster.widgets.TbSelect2', array(
				'model'			 => $model,
				'attribute'		 => 'prc_source_state',
				'val'			 => $model->prc_source_state,
				'asDropDownList' => FALSE,
				'options'		 => array('data' => new CJavaScriptExpression($dataState), 'allowClear' => true),
				'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select State')
			));
			?> 
			<?php echo $form->error($model, 'prc_source_state'); ?>
        </div></div>

    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 ">
        <div class="form-group cityinput">
            <label>Destination State</label>
			<?php
			$this->widget('booster.widgets.TbSelect2', array(
				'model'			 => $model,
				'attribute'		 => 'prc_destination_state',
				'val'			 => $model->prc_destination_state,
				'asDropDownList' => FALSE,
				'options'		 => array('data' => new CJavaScriptExpression($dataState), 'allowClear' => true),
				'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select State')
			));
			?> 
			<?php echo $form->error($model, 'prc_destination_state'); ?>
        </div></div>

    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 ">
        <div class="form-group  ">
            <label>Select Region </label>
			<?php
			$regionList	 = VehicleTypes::model()->getJSON(Vendors::model()->getRegionList());
			$this->widget('booster.widgets.TbSelect2', array(
				'model'			 => $model,
				'attribute'		 => 'prc_region',
				'val'			 => $model->prc_region,
				//'asDropDownList' => FALSE,
				'data'			 => Vendors::model()->getRegionList(),
				'options'		 => array('allowClear' => true),
				'htmlOptions'	 => array('style' => 'width: 100%', 'placeholder' => 'Select Region')
			));
			?>
			<?php echo $form->error($model, 'prc_region'); ?>
        </div></div>


    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
        <div class="form-group  ">
			<?php // echo $form->drop($model,'prc_vehicle_type');       ?>
            <label>Car Type</label>
			<?php
			$cartype	 = SvcClassVhcCat::getVctSvcList();
			$this->widget('booster.widgets.TbSelect2', array(
				'model'			 => $model,
				'attribute'		 => 'prc_vehicle_type',
				'val'			 => $model->prc_vehicle_type,
				'data'			 => $cartype,
				'options'		 => array('allowClear' => true),
				'htmlOptions'	 => array('style' => 'width:100%', 'width' => '100%', 'placeholder' => 'Select Car Type')
			));
			?>
			<?php echo $form->error($model, 'prc_vehicle_type'); ?>
        </div></div>

    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4  ">
        <div class="form-group cityinput">
			<?php // echo $form->drop($model,'prc_vehicle_type');       ?>
            <label>Trip Type</label>
			<?php
			$tripType	 = Booking::model()->getBookingType();
			$this->widget('booster.widgets.TbSelect2', array(
				'model'			 => $model,
				'attribute'		 => 'prc_trip_type',
				'val'			 => $model->prc_trip_type,
				'data'			 => $tripType,
				'options'		 => array('allowClear' => true),
				'htmlOptions'	 => array('style' => 'width:100%', 'width' => '100%', 'placeholder' => 'Select Trip Type')
			));
			?>
			<?php echo $form->error($model, 'prc_trip_type'); ?>
        </div>
    </div>

    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 mt10">
		<?php echo $form->textAreaGroup($model, 'prc_desc'); ?>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mt10">
		<?= $form->numberFieldGroup($model, 'prc_priority_score', array('widgetOptions' => array('htmlOptions' => ['min' => 0, 'max' => 999]), 'groupOptions' => ['class' => 'm0'])) ?>                      
    </div>
    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mt10">
        <div class="form-group cityinput">
			<?php // echo $form->drop($model,'prc_vehicle_type');       ?>
            <label>Availability Type</label>
			<?php
			$avlType	 = [1 => 'Available', 0 => 'Not Available'];
			$this->widget('booster.widgets.TbSelect2', array(
				'model'			 => $model,
				'attribute'		 => 'prc_is_available',
				'val'			 => $model->prc_is_available,
				'data'			 => $avlType,
				'htmlOptions'	 => array('style' => 'width:100%', 'width' => '100%', 'placeholder' => 'Availability Type')
			));
			?>
			<?php echo $form->error($model, 'prc_is_available'); ?>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mt10">
		<?= $form->checkboxGroup($model, 'prc_override_ds', array()) ?>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mt10">
		<?= $form->checkboxGroup($model, 'prc_override_dz', array()) ?>
    </div>
	<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mt10">
		<?= $form->checkboxGroup($model, 'prc_override_de', array()) ?>
    </div>
	<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mt10">
		<?= $form->checkboxGroup($model, 'prc_override_ddv2', array()) ?>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mt10">
		<?= $form->checkboxGroup($model, 'prc_sold_out', array()) ?>
    </div>


    <div class="col-xs-12 text-center">
		<?php echo CHtml::submitButton('Save', ['class' => 'btn btn-info btn-lg']); ?>
    </div>

	<?php $this->endWidget(); ?>

</div><!-- form -->
<script type="text/javascript">
    var start = '<?= date('d/m/Y', strtotime('-1 month')); ?>';
    var end = '<?= date('d/m/Y'); ?>';

    $('#bkgSurgeDate').daterangepicker(
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
//                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
//                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
//                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
//                    'This Month': [moment().startOf('month'), moment().endOf('month')],
//                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],

                    'Tomorrow': [moment().add(1, 'days'), moment().add(1, 'days')],
                    'Next 7 Days': [moment(), moment().add(6, 'days')],
                    'Next 30 Days': [moment(), moment().add(29, 'days')],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Next Month': [moment().add(1, 'month').startOf('month'), moment().add(1, 'month').endOf('month')],

                }
            }, function (start1, end1) {
        $('#PriceSurge_prc_from_date').val(start1.format('YYYY-MM-DD'));
        $('#PriceSurge_prc_to_date').val(end1.format('YYYY-MM-DD'));
        $('#bkgSurgeDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
    });
    $('#bkgSurgeDate').on('cancel.daterangepicker', function (ev, picker) {
        $('#bkgSurgeDate span').html('Select Price Surge Date Range');
        $('#PriceSurge_prc_from_date').val('');
        $('#PriceSurge_prc_to_date').val('');
    });
    $sourceList = null;


    function populateSource(obj, cityId) {

        obj.load(function (callback) {
            var obj = this;
            if ($sourceList == null) {
                xhr = $.ajax({
                    url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery', ['apshow' => 1, 'city' => ''])) ?>' + cityId,
                    dataType: 'json',
                    data: {
                        // city: cityId
                    },
                    //  async: false,
                    success: function (results) {
                        $sourceList = results;
                        obj.enable();
                        callback($sourceList);
                        obj.setValue(cityId);
                    },
                    error: function () {
                        callback();
                    }
                });
            } else {
                obj.enable();
                callback($sourceList);
                obj.setValue(cityId);
            }
        });
    }
    function loadSource(query, callback) {
        //	if (!query.length) return callback();
        $.ajax({
            url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery')) ?>?apshow=1&q=' + encodeURIComponent(query),
            type: 'GET',
            dataType: 'json',
            global: false,
            error: function () {
                callback();
            },
            success: function (res) {
                callback(res);
            }
        });
    }
</script>