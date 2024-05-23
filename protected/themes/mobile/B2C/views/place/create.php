<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/fontawesome-web/css/all.min.css?v0.6');
?>

<?php
$this->layout	 = 'column1';
?>

<?
$selectizeOptions = [
'create'  => false, 
'persist'			 => true, 
'selectOnTab'		 => true, 
'createOnBlur'		 => true, 
'dropdownParent'	 => 'body',
'optgroupValueField' => 'id', 
'optgroupLabelField' => 'text', 
'sortField'			 => 'text', 
'optgroupField'		 => 'id', 
'openOnFocus'		 => true, 
'preload'			 => false,
'labelField'		 => 'text', 
'valueField'		 => 'id', 
'searchField'		 => 'text', 
'closeAfterSelect'	 => true,
'addPrecedence'		 => false,
					];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/plugins/form-typeahead/typeahead.bundle.min.js');
?>
<div class="content-boxed-widget p0">
                    <?
                    if ($model['user_place_id'] != '') {
                        ?>
                       <div class="content pt15 bottom-20">
						<h3>Edit Place<a class="uppercase btn-orange shadow-medium pl10 pr10 pt5 pb5 pull-right" id="addPlace" href="<?= Yii::app()->createUrl('place/view'); ?>"  name="sub" >View Place</a></h3>
                        
                    </div>  
                    <? } else{?> 
                    <div class="content pt15 bottom-20">
						<h3>Add Places<a class="uppercase btn-orange shadow-medium pl10 pr10 pt5 pb5 pull-right" id="addPlace" href="<?= Yii::app()->createUrl('place/view'); ?>"  name="sub" >View Place</a></h3>
                        
                    </div>
					<? }?>
<div class="content-boxed-widget">

        <?
//print_r($model);
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'user-place-form', 'enableClientValidation' => true,
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
                'class' => 'form-horizontal',
            ),
        ));
        /* @var $form CActiveForm */
        ?>
        
            
                
                <?php echo CHtml::errorSummary($model); ?>
                    
                <div class="input-simple-1 has-icon input-green bottom-30">
					<?php echo $form->labelEx($model,'name',['label' => 'Name of Place']); ?>
                    <?= $form->textField($model, 'name', ['required' => true, 'class' => '', 'value' => $model->name]) ?>
                </div>

                <div class="input-simple-1 has-icon input-green bottom-30">
                    <?php echo $form->labelEx($model,'address1',['label' => 'Address']); ?>
                    <?= $form->textField($model, 'address1',['required' => true, 'class' => 'bottom-20', 'value' => $model->address1]) ?>
                    <?= $form->textField($model, 'address2',[ 'class' => 'bottom-20', 'value' => $model->address2]) ?>   
                    <?= $form->textField($model, 'address3',[ 'class' => 'bottom-20', 'value' => $model->address3]) ?>
                </div>


				<div class="bottom-30"> 
					<label class="control-label" for="ffcity">City</label>
				<?php
				$this->widget('ext.yii-selectize.YiiSelectize', array(
							'model'				 => $model,
							'attribute'			 => 'city',
							'useWithBootstrap'	 => true,
							"placeholder"		 => "Enter Your City",
							'fullWidth'			 => true,
							'htmlOptions'		 => array('width' => '100%', ''
							),
							'defaultOptions'	 => $selectizeOptions + array(
						'onInitialize'	 => "js:function(){
                                        populateSource(this, '{$model->city}');
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
				</div>

                <div class="input-simple-1 has-icon input-green bottom-30">
				  <?php echo $form->labelEx($model,'zip',['label' => 'Zip Code']); ?>
                  <?= $form->textField($model, 'zip',['required' => true, 'class' => '', 'value' => $model->zip]) ?>

                </div>
            <div class="content text-center">
                <input class="uppercase btn-orange shadow-medium"  type="submit" name="sub" value="Submit" />
            </div>
        <?php $this->endWidget(); ?>
</div>
<script type="text/javascript">
    function loadSource(query, callback) {
        //	if (!query.length) return callback();
        $.ajax({
            url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery')) ?>?q=' + encodeURIComponent(query),
            type: 'GET',
            dataType: 'json',

            error: function () {
                callback();
            },
            success: function (res) {
                callback(res);
            }
        });
    }
    $sourceList = null;
    function populateSource(obj, cityId) {
        obj.load(function (callback) {
            var obj = this;
            if ($sourceList == null) {
                xhr = $.ajax({
                    url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery')) ?>',
                    dataType: 'json',
                    data: {
                        city: cityId
                    },
                    //  async: false,
                    success: function (results) {
                        $sourceList = results;
                        obj.enable();
                        callback($sourceList);
                        obj.setValue('<?= $model->city ?>');
                    },
                    error: function () {
                        callback();
                    }
                });
            } else {
                obj.enable();
                callback($sourceList);
                obj.setValue('<?= $model->city ?>');
            }
        });
    }
</script>

