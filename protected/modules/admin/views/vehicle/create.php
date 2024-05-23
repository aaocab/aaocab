<?php
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/jquery.mask1.min.js');
Yii::app()->clientScript->registerCssFile(ASSETS_URL . '/plugins/form-select2/select2.css');
$vtypeList	 = VehicleTypes::model()->getParentVehicleTypes(2);
$vTypeData	 = VehicleTypes::model()->getJSON($vtypeList);
//$vtypeList1 = array();
//foreach ($vtypeList as $key => $value) {
//    $vtypeList1[] == array("id" => $key, "text" => $val);
//}
//$vtypeList2 = CJSON::encode($vtypeList1);


$color		 = array('Red' => 'Red', 'Grey' => 'Grey', 'White' => 'White');
$vendorList	 = array("" => "Select Vendor") + CHtml::listData(Vendors::model()->getAll(array('order' => 'vnd_name')), 'vnd_id', 'vnd_name');
if ($model->isNewRecord)
{
	$title	 = "Add";
	//CONFIRM
	$js		 = "if($.isFunction(window.refreshCab))
			{
				window.refreshCab();
			}
			else
			{
				window.location.reload();
			}
            ";
}
//UPDATE
else
{
	$title	 = "Edit";
	$js		 = "	if($.isFunction(window.refreshCab))
	{
		window.refreshCab();
	}
	else
	{
		alert('updated');
	}
		";
}

//Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/jquery.mask.min.js');
//Yii::app()->clientScript->registerCssFile(ASSETS_URL . '/plugins/form-select2/select2.css');
if (!Yii::app()->request->isAjaxRequest)
{
	$panelCss	 = "col-sm-9 col-md-7 col-lg-6 ";
	$panelClass	 = " panel-grape";
}
else
{

	$panelHeading = "display: none";
}
?>
<div class="row">
    <div class="col-xs-12">
        <div class="<?= $panelCss ?>" style="float: none; margin: auto">  
			<?php
			/* @var $form TbActiveForm */
			$form	 = $this->beginWidget('booster.widgets.TbActiveForm', array(
				'id'					 => 'vehicle-register-form',
				'enableClientValidation' => true,
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
            <div class="panel panel-default mb0">
                <div class="panel-body">
                    <div class="row">           

						<?php echo CHtml::errorSummary($model); ?>
                        <div class="col-xs-6">
                            <div class="form-group">
								<?
								$this->widget('booster.widgets.TbSelect2', array(
									'model'			 => $model,
									'attribute'		 => 'vhc_type_id',
									'val'			 => $model->vhc_type_id,
									'asDropDownList' => FALSE,
									'options'		 => array('data' => new CJavaScriptExpression($vTypeData)),
									'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select a model')
								));
								?> 
                            </div>
                        </div>     
                        <div class="col-xs-6">
							<?=
							$form->numberFieldGroup($model, 'vhc_year', array('label'			 => '',
								'widgetOptions'	 => array('htmlOptions' => array('min' => date('Y') - 25, 'max' => date('Y')))));
							?>
                        </div>     
                    </div>
                    <div class="row">       

                        <div class="col-xs-6">
							<? //= $form->maskedTextFieldGroup($model, 'vhc_number', ['label' => '', 'widgetOptions' => ['mask' => 'aa-9[9]-a[a]-99999']]); ?>
							<?= $form->textFieldGroup($model, 'vhc_number', array('label' => '', 'widgetOptions' => array('data' => array('' => 'Select a model') + $vtypeList))) ?>
                        </div>
                        <div class="col-xs-6">
                            <div class='form-group'>
								<?php
//								$data	 = Vendors::model()->getJSON();
//								$this->widget('booster.widgets.TbSelect2', array(
//									'model'			 => $model,
//									'attribute'		 => 'vhc_vendor_id1',
//									'val'			 => $model->vhc_vendor_id1,
//									'asDropDownList' => FALSE,
//									'options'		 => array('data' => new CJavaScriptExpression($data)),
//									'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Vendor')
//								));
								
								$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
						$this->widget('ext.yii-selectize.YiiSelectize', array(
					'model'				 => $model,
					'attribute'			 => 'vhc_vendor_id1',
					'useWithBootstrap'	 => true,
					"placeholder"		 => "Select Vendor",
					'fullWidth'			 => false,
					'htmlOptions'		 => array('width' => '100%'),
					'defaultOptions'	 => $selectizeOptions + array(
				'onInitialize'	 => "js:function(){
                                              populateVendor(this, '{$model->vhc_vendor_id1}');
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
                                <span class="has-error"><? echo $form->error($model, 'vhc_vendor_id1'); ?></span>
                            </div>  
                        </div>     
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
    $('#<?= CHtml::activeId($model, 'vhc_number') ?>').mask('AA 0Z AYY 0000', {
        translation: {
            'Z': {
                pattern: /[0-9]/, optional: true
            },
            'Y': {
                pattern: /[A-Za-z]/, optional: true
            },
            'A': {
                pattern: /[A-Za-z]/, optional: false
            },
        },
        placeholder: "__ __ __ ____",
        clearIfNotMatch: true
    });
	
	$sourceList4 = null;
            function populateVendor(obj, vndId) {
                obj.load(function (callback) {
                    var obj = this;
                    if ($sourceList4 == null) {
                        xhr = $.ajax({
                            url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allvendorbyquery', ['onlyActive' => 0, 'vnd' => ''])) ?>' + vndId,
                            dataType: 'json',
                            data: {},
                            //  async: false,
                            success: function (results) {
                                $sourceList4 = results;
                                obj.enable();
                                callback($sourceList4);
                                obj.setValue(vndId);
                            },
                            error: function () {
                                callback();
                            }
                        });
                    } else {
                        obj.enable();
                        callback($sourceList4);
                        obj.setValue(vndId);
                    }
                });
            }
            function loadVendor(query, callback) {

                //	if (!query.length) return callback();
                $.ajax({
                    url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allvendorbyquery')) ?>?onlyActive=0&q=' + encodeURIComponent(query),
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