<?php
$version	 = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/vehicle.js?v=' . $version);
?>
<div class="row">
    <div class="col-xs-12 col-sm-8 col-md-6">
        <div class="<?= $panelCss ?>" style="float: none; margin: auto">  
			<?php
			$datacity	 = Cities::model()->getJSON();
			$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
				'id'					 => 'driver-register-form', 'enableClientValidation' => true,
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
                                "success":function(data1){
                                    if($.isEmptyObject(data1)){
                                        ' . $js . '
                                    }
                                    else{
                                          settings=form.data(\'settings\');
                                        $.each (settings.attributes, function (i) {
                                          $.fn.yiiactiveform.updateInput (settings.attributes[i], data1, form);
                                        });
                                        $.fn.yiiactiveform.updateSummary(form, data1);
                                    }},
                                });
                                }
                        }'
				),
				// Please note: When you enable ajax validation, make sure the corresponding
				// controller action is handling ajax validation correctly.
				// See class documentation of CActiveForm for details on this,
				// you need to use the performAjaxValidation()-method described there.
				'enableAjaxValidation'	 => false,
				'errorMessageCssClass'	 => 'help-block',
				'htmlOptions'			 => array(
					'class'		 => 'form-horizontal', 'enctype'	 => 'multipart/form-data'
				),
			));
			/* @var $form TbActiveForm */
			?>
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">           

						<?php echo CHtml::errorSummary($model); ?>
                        <div class="col-xs-6">
                            <div class="form-group">
								<?php
								$this->widget('booster.widgets.TbSelect2', array(
									'model'			 => $model,
									'attribute'		 => 'cav_from_city',
									'val'			 => $model->cav_from_city,
									'asDropDownList' => FALSE,
									// 'data' => $cityList2,
									'options'		 => array('data' => new CJavaScriptExpression($datacity)),
									'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Source City')
								));
								?>
                            </div>
                        </div>     
                        <div class="col-xs-6">
							<?php
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'cav_to_cities',
								'val'			 => $model->cav_to_cities,
								'asDropDownList' => FALSE,
								//'data' => $cityList2,
								'options'		 => array('data' => new CJavaScriptExpression($datacity)),
								'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Destination City')
							));
							?>
                        </div>     
                    </div>
                    <div class="row">       

                        <div class="col-xs-6">

							<?=
							$form->datePickerGroup($model, 'cav_date', array('label'			 => 'Pickup Date',
								'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'Pickup Date', 'value' => DateTimeFormat::DateTimeToDatePicker(date('Y-m-d H:i:s')))), 'prepend'		 => '<i class="fa fa-calendar"></i>'));
							?>
                        </div>
                        <div class="col-xs-6 pl30 pr30">
							<?
							echo $form->timePickerGroup($model, 'cav_time', array('label'			 => 'Pickup Time',
								'widgetOptions'	 => array('id' => CHtml::activeId($model, "bkg_pickup_date_time"), 'options' => array('autoclose' => true), 'htmlOptions' => array('placeholder' => 'Pickup Time', 'value' => date('h:i A', strtotime($strpickdate))))));
							?>
                        </div>     
                    </div>
                    <div class="row ">       
                        <div class="col-xs-6 ">
							<?php
//							$data		 = Vendors::model()->getJSON();
//							$this->widget('booster.widgets.TbSelect2', array(
//								'model'			 => $model,
//								'attribute'		 => 'vnd_id',
//								'val'			 => $model->vnd_id,
//								'asDropDownList' => FALSE,
//								'options'		 => array('data' => new CJavaScriptExpression($data)),
//								'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Vendor')
//							));
							$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
						$this->widget('ext.yii-selectize.YiiSelectize', array(
					'model'				 => $model,
					'attribute'			 => 'vnd_id',
					'useWithBootstrap'	 => true,
					"placeholder"		 => "Select Vendor",
					'fullWidth'			 => false,
					'htmlOptions'		 => array('width' => '100%'),
					'defaultOptions'	 => $selectizeOptions + array(
				'onInitialize'	 => "js:function(){
                                              populateVendor(this, '{$model->vnd_id}');
                        }",
				'load'			 => "js:function(query, callback){
                                            loadVendor(query, callback);
                        }",
				'render'		 => "js:{
                                                option: function(item, escape){
                                                    return '<div><span class=\"\"><i class=\"fa fa-user mr5\"></i>' + escape(item.text) +'</span></div>';
                                                },
                                                option_create: function(data, escape){
                                                    return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
                                                }
                                            }",
					),
				));
							?>
                        </div>      
                        <div class="col-xs-6  pl30 pr30">

							<?php
							if ($_REQUEST['vhtid'] != '')
							{
								$dt = Drivers::model()->getJSONDrivers($model->vnd_id);
							}
							else
							{
								$dt = "[]";
							}

							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'cav_driver_id',
								'val'			 => $model->cav_driver_id,
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($dt),
								),
								'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Drivers')
							));
							?>

                        </div></div>
                    <div class="row pt30 ">       
                        <div class="col-xs-6">
							<?php
							if ($_REQUEST['vhtid'] != '')
							{
								$dt = Vehicles::model()->getJSONcab($model->vnd_id);
							}
							else
							{
								$dt = "[]";
							}

							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'cav_cab_id',
								'val'			 => $model->cav_cab_id,
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($dt),
								),
								'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Cabs')
							));
							?>

                        </div>
                        <div class="col-xs-6  pl30 pr30"></div>
                    </div>
                </div>
                <div class="panel-footer" style="text-align: center">
					<?php echo CHtml::submitButton('Add', array('class' => 'btn btn-primary')); ?>
                </div>
            </div>
			<?php $this->endWidget(); ?>
        </div>
    </div>
</div>
<script>
    $(document).ready(function ()
    {
        populateData();
        populateDatacab();
    });

    $('#<?= CHtml::activeId($model, "vnd_id") ?>').change(function () {

        populateData();
        populateDatacab();
    });
    function populateData()
    {
        var vehicle = new Vehicle();
        var model = {};
        model.vendorId = $('#<?= CHtml::activeId($model, "vnd_id") ?>').val();
        vehicle.model = model;
        if (model.vendorId !== "")
        {
            $(document).on("getDriverList", function (event, data) {
                driverDetails(data);
            });
            vehicle.getDriverList();
        }
    }

    function driverDetails(data)
    {
        $dataagt = data.data;
        var placeholder = $('#<?= CHtml::activeId($model, "cav_driver_id") ?>').attr('placeholder');
        $('#<?= CHtml::activeId($model, "cav_driver_id") ?>').select2
                ({data: $dataagt,
                    placeholder: placeholder,
                    formatNoMatches: function (term) {
                        return "Can't find the Destination?<br>Let us help you.<br><i>Call now</i> (+91) 90518-77-000";
                    }});
    }

    function populateDatacab()
    {
        var vehicle = new Vehicle();
        var model = {};
        model.vendorId = $('#<?= CHtml::activeId($model, "vnd_id") ?>').val();
        vehicle.model = model;
        if (model.vendorId !== "")
        {
            $(document).on("getVehicleList", function (event, data) {
                vehicleList(data);
            });
            vehicle.getVehicleList();
        }
    }

    function vehicleList(data)
    {
        $data2 = data.data;
        var placeholder = $('#<?= CHtml::activeId($model, "cav_cab_id") ?>').attr('placeholder');
        $('#<?= CHtml::activeId($model, "cav_cab_id") ?>').select2
                ({data: $data2,
                    placeholder: placeholder,
                    formatNoMatches: function (term) {
                        return "Can't find the Destination?<br>Let us help you.<br><i>Call now</i> (+91) 90518-77-000";
                    }});
    }
</script>