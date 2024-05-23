<?
$selectizeOptions = ['create'		 => false, 'persist'		 => true, 'selectOnTab'		 => true, 'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
    'optgroupValueField'	 => 'id', 'optgroupLabelField'	 => 'text', 'sortField'		 => 'text', 'optgroupField'		 => 'id', 'openOnFocus'		 => true, 'preload'		 => false,
    'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
    'addPrecedence'		 => false,];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/plugins/form-typeahead/typeahead.bundle.min.js');
?>
<div class="row">
    <div class="col-12 mb30">
        <div class="card">
<div class="card-body">
            <?
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


            <?= $form->errorSummary($model, NULL, NULL, ['class' => 'errorSummary alert alert-danger']) ?>
            <div class="row">
                <div class="col-12 col-lg-6">
					<div class="form-group">
					<label class="control-label" for="UserPlaces_name">Name of Place</label>
                    <?= $form->textField($model, 'name',['required' => true,'class'=>'form-control','placeholder'=>'Place Name', 'value' => $model->name]) ?>
					<?php echo $form->error($model, 'name', ['class' => 'help-block danger mt5 error']); ?>
                    </div>
                </div>
                <div class="col-12">
                    <div class="row">
						<div class="col-12"><label class="control-label" for="UserPlaces_address1">Address</label></div>
                        <div class="col-12 col-lg-4">
                           <div class="form-group">
                            
                            <?= $form->textField($model, 'address1', ['required' => true,'class'=>'form-control','placeholder'=>'Address1', 'value' => $model->address1]) ?>
                            <?php echo $form->error($model, 'address1', ['class' => 'help-block danger mt5 error']); ?>
                            </div>
                        </div>
                        <div class="col-12 col-lg-4">
                           <div class="form-group">
                            <?= $form->textField($model, 'address2', ['class'=>'form-control','placeholder'=>'Address2','value' => $model->address2]) ?>
                           </div>
                        </div>
                        <div class="col-12 col-lg-4">
                            <div class="form-group">
                            <?= $form->textField($model, 'address3', ['class'=>'form-control','placeholder'=>'Nearby Landmark','value' => $model->address3]) ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-6"> 
                    <label class="control-label" for="ffcity">City</label>
                <?php
                    $this->widget('ext.yii-selectize.YiiSelectize', array(
							'model'			 => $model,
							'attribute'		 => 'city',
							'useWithBootstrap'	 => true,
							"placeholder"		 => "Enter Your City",
							'fullWidth'		 => false,
							'htmlOptions'		 => array('width' => '50%', ''
							),
							'defaultOptions'	 => $selectizeOptions + array(
							'onInitialize'	 => "js:function(){
											populateSource(this, '{$model->city}');
										}",
							 'load'		 => "js:function(query, callback){
												loadSource(query, callback);
											}",
							'render'	 => "js:{
											option: function(item, escape){
											return '<div><span class=\"\"><i class=\"fa fa-map-marker ml5 mr5\"></i>' + escape(item.text) +'</span></div>';
											},
											option_create: function(data, escape){
											return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
											}
											}",
							),
					));
                ?>            

                </div>              
                <div class="col-12 col-lg-6">
                    <div class="form-group">
                    <label class="control-label" for="UserPlaces_zip">Zip Code</label>
                    <?= $form->textField($model, 'zip',['required' => true,'class'=>'form-control','placeholder'=>'Zip', 'value' => $model->zip]) ?>
                    <?php echo $form->error($model, 'zip', ['class' => 'help-block danger mt5 error']); ?>
                   </div>
                </div>
                <div class="col-12">
                    <input class="btn btn-primary text-uppercase gradient-green-blue border-none mt15"  type="submit" name="sub" value="Submit" />
                </div>
            </div>

            <?php $this->endWidget(); ?>
        </div>
</div>
    </div>
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


