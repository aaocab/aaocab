<?php
$selectizeOptions = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
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
	.btnSubmit{
		width:150px;text-transform: uppercase;padding:10px;margin-top:20px;
	}
	#Note .form-group.has-error .form-control {
		width:97%!important;
	}
	.showState1, .showCity1{
		display :none;
	}
</style>

<div class="row">
    <div class="col-xs-12">
		<?php echo CHtml::errorSummary($noteModel);?>
		
		<?php
					$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
						'id'					 => 'note-add-form', 'enableClientValidation' => TRUE,
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
							'class' => 'form-horizontal',
						),
					));
					/* @var $form TbActiveForm */
					?>

		<div class="col-xs-12">
			<div class="panel panel-default panel-border">
				<div class="panel-body">
					<div class="row mb15">
						<div class="col-xs-12 col-md-12">
							<label> Note Applicable For</label>
							<?= $form->radioButtonListGroup($noteModel, 'dnt_area_type', array('label' => '', 'widgetOptions' => array('htmlOptions' => [], 'data' => DestinationNote::$noteAreaType), 'inline' => true)) ?>
						</div>
						
						<?php 

if($noteModel->dnt_area_type = 1){?>
						<div class="col-xs-12 col-sm-4" >
                          <fieldset class="showCity">
							<label>Route City *</label>

							<?php
							$this->widget('ext.yii-selectize.YiiSelectize', array(
								'model'				 => $noteModel,
								'attribute'			 => 'dnt_area_id',
								'useWithBootstrap'	 => true,
								"placeholder"		 => "From",
								'fullWidth'			 => false,
								'htmlOptions'		 => array('width' => '100%',
								//  'id' => 'from_city_id1'
								),
								'defaultOptions'	 => $selectizeOptions + array(
							'onInitialize'	 => "js:function(){
											  populateSource(this, '{$model->rut_from_city_id}');
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
							</fieldset>
						</div>
						<?php }else{?>
						<div class="col-xs-12 col-sm-4">
							<fieldset class="showState">
							<label>Route State *</label>

							<?php
							$this->widget('ext.yii-selectize.YiiSelectize', array(
								'model'				 => $noteModel,
								'attribute'			 => 'dnt_area_id',
								'useWithBootstrap'	 => true,
								"placeholder"		 => "Route State",
								'fullWidth'			 => false,
								'htmlOptions'		 => array('width' => '100%',
								//  'id' => 'from_city_id1'
								),
								'defaultOptions'	 => $selectizeOptions + array(
							'onInitialize'	 => "js:function(){
											  populateState(this, '{$stateModel->stt_id}');
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
							</fieldset>
						</div>
						<?php }?>	
							
							<div class="col-xs-12 col-sm-11">
								<label>Notes *</label>
								<?= $form->textFieldGroup($noteModel, 'dnt_note', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Note', 'id'=>'Note', 'style' => 'margin-left:18px')))) ?>
							</div>
							<div class="col-xs-12 col-sm-6">
									<div class="col-xs-12 col-sm-7">
                                    <?php $datefrom	 = $noteModel->dnt_valid_from_date != '' ? $noteModel->dnt_valid_from_date : date('Y-m-d H:i:s');?>
									<?= $form->datePickerGroup($noteModel, 'dnt_valid_from_date', array('label' => 'Valid From Date', 
										'widgetOptions' => array('options' => array('autoclose' => true, 'startDate' => date('d/m/Y'), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('required' => true, 'value' => date('d/m/Y', strtotime($datefrom)))), 'prepend' => '<i class="fa fa-calendar"></i>')); ?>
								</div>
								  <div class="col-xs-12 col-sm-1"></div>
								<div class="col-xs-12 col-sm-4">
									<?php
										if ($noteModel->dnt_valid_from_time != '')
										{
											$ptime = date('h:i A', strtotime($noteModel->dnt_valid_from_time));
										}
										else
										{
											$ptime = date('h:i A', strtotime(now));
										}
										$fromTimeArr = Filter::getTimeDropArr($ptime);
										?>
									<?=
									$form->timePickerGroup($noteModel, 'dnt_valid_from_time', array('label'			 => 'Valid From Time',
										'widgetOptions'	 => array('options' => array('autoclose' => true), 'htmlOptions' => array('required' => true, 'value' =>$fromTimeArr))));
									?>

								</div>
							</div>
                           
							<div class="col-xs-12 col-sm-6">
								<div class="col-xs-12 col-sm-7">
									 <?php $dateto	 = $noteModel->dnt_valid_to_date != '' ? $noteModel->dnt_valid_to_date : date('Y-m-d H:i:s');?>
									<?= $form->datePickerGroup($noteModel, 'dnt_valid_to_date', array('label' => 'Valid To Date', 
										'widgetOptions' => array('options' => array('autoclose' => true, 'startDate' => date('d/m/Y'), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('required' => true, 'value' => date('d/m/Y', strtotime($dateto)))), 'prepend' => '<i class="fa fa-calendar"></i>')); ?>
								</div>
                               <div class="col-xs-12 col-sm-1"></div>
								<div class="col-xs-12 col-sm-4">
									<?php
										if ($noteModel->dnt_valid_to_time != '')
										{
											$ptime = date('h:i A', strtotime($noteModel->dnt_valid_to_time));
										}
										else 
										{
											$ptime =date('h:i A', strtotime(now));
										}
										$toTimeArr = Filter::getTimeDropArr($ptime);
										?>
									<?=
									$form->timePickerGroup($noteModel, 'dnt_valid_to_time', array('label'=> 'Valid To Time',
										'widgetOptions'	 => array('options' => array('autoclose' => true), 'htmlOptions' => array('required' => true, 'value' => $toTimeArr))));
									?>
								</div>
                           </div>

						<div class="col-xs-12 col-sm-3"></div>
						<div class="col-xs-12 text-center">
							<input type="submit" value="Submit" name="yt0" id="notesubmit" class="btn btn-primary pl30 pr30 btnSubmit">
						</div>
					</div>
				</div>
			</div>
		</div>

		<?php $this->endWidget(); ?>
    </div>

</div>

<script type="text/javascript">
    $sourceList = null;
    function populateSource(obj, cityId) {

        obj.load(function (callback) {
            var obj = this;
            if ($sourceList == null) {
                xhr = $.ajax({
                    url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery')) ?>',
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
	function populateState(obj, stateId) {
 
        obj.load(function (callback) {
            var obj = this;
            if ($sourceList == null) {
                xhr = $.ajax({
                    url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allStatelist')) ?>',
                    dataType: 'json',
                    data: {
                        // state: stateId
                    },
                    //  async: false,
                    success: function (results) {
                        $sourceList = results;
                        obj.enable();
                        callback($sourceList);
                        obj.setValue(stateId);
                    },
                    error: function () {
                        callback();
                    }
                });
            } else {
                obj.enable();
                callback($sourceList);
                obj.setValue(stateId);
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