<?php
//Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/lookup/cities?v' . Cities::model()->getLastModified());
$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true, 'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id', 'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true, 'addPrecedence'		 => false,];
?>
<div class="panel">
    <div class="panel-heading"></div>
    <div class="panel-body">
		<?php
		$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'city-adddestination-form', 'enableClientValidation' => TRUE,
			'clientOptions'			 => array(
				'validateOnSubmit'	 => true,
				'errorCssClass'		 => 'has-error',
				'afterValidate'		 => 'js:function(form,data,hasError){
                    if(!hasError){
                 
                        $.ajax({
                            "type":"POST",
                            "dataType":"json",
                            "url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('admin/city/adddestination')) . '",
                            "data":form.serialize(),
                            "success":function(data2){   
                                if(data2.success){
                                    alert("Your Destination has been added successfully.");
                                    bootbox.hideAll();
                                }else{
                                    alert("Please add at least one Destination.");
                                }
                            },
                            error: function (xhr, ajaxOptions, thrownError) 
                            {
                                alert(xhr.status);
                                alert(thrownError);
                            }
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
				'class' => 'form-horizontal'
			),
		));
		/* @var $form TbActiveForm */
		?> 

        <div class="col-xs-4">
            <label>
                Select City
            </label>

			<?php
			$cityarr			 = Cities::model()->getCityArrDistinct();
			$this->widget('ext.yii-selectize.YiiSelectize', array(
				'model'				 => $model,
				'attribute'			 => 'cln_city_id2',
				'useWithBootstrap'	 => true,
				'placeholder'		 => 'Select City',
				'fullWidth'			 => false,
				'htmlOptions'		 => array('width'	 => '100%',
					'id'	 => 'CityLinks_cln_city_id2'
				),
				'defaultOptions'	 => $selectizeOptions + array(
			'onInitialize'	 => "js:function(){
                populateSource(this, '{$cityarr}');
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
			echo $form->hiddenField($model, 'cln_city_id');
			?>
            <span class="has-error"><? echo $form->error($model, 'cln_city_id'); ?></span> 
        </div>

        <div class="col-xs-4"><?= $form->textFieldGroup($model, 'cln_title', array('label' => "Destination Title", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Destination Title')))) ?></div>
        <div class="col-xs-4"><?= $form->textFieldGroup($model, 'cln_url', array('label' => "Destination URL", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Destination URL')))) ?></div>
        <div class="col-xs-12">
			<?php echo CHtml::submitButton('Save', array('class' => 'btn btn-primary')); ?>
        </div>
		<?php $this->endWidget(); ?>
    </div>
</div>

<script>
    $sourceList = null;
    function populateSource(obj, cityId) {
        obj.load(function (callback) {
            var obj = this;
            if ($sourceList == null) {
                xhr = $.ajax({
                    url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery', ['apshow' => 1])) ?>',
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
        //if (!query.length) return callback();
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

    $('#CityLinks_cln_city_id2').change(function () {
        $('#CityLinks_cln_city_id').val($('#CityLinks_cln_city_id2').val()).change();
    });
</script>
