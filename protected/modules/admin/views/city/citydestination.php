<?php
$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true, 'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id', 'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true, 'addPrecedence'		 => false,];
?>
<div class="panel">
    <div class="panel-heading"></div>
    <div class="panel-body">
		<?php
		$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'city-manage-form', 'enableClientValidation' => TRUE,
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
				'class' => 'form-horizontal'
			),
		));
		/* @var $form TbActiveForm */
		?>
        <div class="col-xs-3  pull-left">
			<?php
			/* $fromCityArr = Cities::model()->getCityArrDistinct();
			  $this->widget('booster.widgets.TbSelect2', array(
			  'model' => $model,
			  'attribute' => 'cln_city_id',
			  'val' => $model->cln_city_id,
			  'data' => $fromCityArr,
			  //'asDropDownList' => FALSE,
			  //'options' => array('data' => new CJavaScriptExpression($datacity), 'allowClear' => true,),
			  'htmlOptions' => array('style' => 'width:100%', 'multiple' => false,
			  'placeholder' => 'Select City')
			  )); */
			?>

			<?php
			$cityarr			 = Cities::model()->getCityArrDistinct();
			$this->widget('ext.yii-selectize.YiiSelectize', array(
				'model'				 => $model,
				'attribute'			 => 'cln_city_id1',
				'useWithBootstrap'	 => true,
				'placeholder'		 => 'Select City',
				'fullWidth'			 => false,
				'htmlOptions'		 => array('width'	 => '100%',
					'id'	 => 'CityLinks_cln_city_id1'
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
        <div class="col-xs-3 pull-left">
            <label></label>
			<?php echo CHtml::submitButton('Search', array('class' => 'btn btn-info')); ?>
        </div>
        <div class="col-xs-3  pull-right">
            <button type="button" class="btn btn-primary" onclick="adddestination()">Add New</button>
        </div>
		<?php $this->endWidget(); ?>
    </div>
</div>

<div class="panel">
    <div class="panel-heading"></div>
    <div class="panel-body">
		<?
		if ($dataProvider != '')
		{
			$this->widget('booster.widgets.TbGridView', array(
				'responsiveTable'	 => true,
				'dataProvider'		 => $dataProvider,
				'template'			 => "<div class='panel-heading'><div class='row m0'>
                                        <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                </div></div>
                                <div class='panel-body'>{items}</div>
                                <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
				'itemsCssClass'		 => 'table table-striped table-bordered mb0',
				'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
				//     'ajaxType' => 'POST',
				'columns'			 => array(
					array('name'				 => 'cln_city_id', 'value'				 => '$data->clnCities->cty_name',
						'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'City Name'),
					array('name'				 => 'cln_title', 'value'				 => '$data[cln_title]',
						'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Title'),
					array('name'				 => 'cln_category', 'value'				 => 'CityLinks::model()->getCategories($data[cln_category])',
						'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Category'),
					array('name'	 => 'cln_url', 'type'	 => 'raw', 'value'	 => function($data) {

							return CHtml::link($data['cln_url'], $data['cln_url'], ['target' => '_BLANK']);
						},
						'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'URL'),
					array('name'				 => 'cln_datetime', 'value'				 => 'date("d/m/Y H:i:s",strtotime($data[cln_datetime]))',
						'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Date'),
					array('name'				 => 'cln_user_ip', 'value'				 => '$data[cln_user_ip]',
						'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'IP Address'),
					array('name'				 => 'cln_user_id', 'value'				 => '$data->clnUser->usr_name',
						'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'User'),
			)));
		}
		?>
    </div>
</div>

<script>
    function adddestination() {
        var href2 = '<?= Yii::app()->createUrl("admin/city/adddestination"); ?>';
        $.ajax({
            "url": href2,
            "type": "GET",
            "dataType": "html",
            "success": function (data) {
                var box = bootbox.dialog({
                    message: data,
                    title: 'Add Destination',
                    size: 'large',
                    onEscape: function () {
                        // user pressed escape
                    },
                });
            }
        });
    }

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

    $('#CityLinks_cln_city_id1').change(function () {
        $('#CityLinks_cln_city_id').val($('#CityLinks_cln_city_id1').val()).change();
    });
</script>

