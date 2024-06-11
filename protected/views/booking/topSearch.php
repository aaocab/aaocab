<style type="text/css">
    .nav-tabs  a {
        font-size:11.3px !important;       
    }
    .nav-tabs>li>a {
        padding: 10px 8px;
    }
    .checkbox-inline {
        padding-top: 0 !important;      
        padding-left: 30px;
        margin-top: -5px !important;      
    }

    input[type="radio"]{
        margin-top: 0;
    }

</style>
<?
$selectizeOptions		 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true, 'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id', 'openOnFocus'		 => true,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
$cityRadius				 = Yii::app()->params['airportCityRadius'];
$emptyTransferDropdown	 = "Please check your transfer type.<br>Let us help you.<br><i>Call now</i> (+91) 90518-77-000";
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');
?>
<div class="col-xs-12 col-sm-5 col-md-4 search-form-panel p0">
    <ul class="nav nav-tabs">
        <li class="active" id="otrip"><a href="#menu4" data-toggle="tab">One-way</a></li>
        <li class="" id='rtrip'><a href="#menu5" data-toggle="tab">Round Trip</a></li>
        <li class="" id='mtrip'><a href="#menu6" data-toggle="tab">Multi Way</a></li>
        <li class="" id='ttrip'><a href="#menu7" data-toggle="tab">Airport Transfer</a></li>
    </ul>
    <div class="tab-content col-xs-12" style="height: 100%">
        <div class="tab-pane fade active in home-search" id="menu4">
			<?
			/* @var $form TbActiveForm|CWidget */
			$form					 = $this->beginWidget('booster.widgets.TbActiveForm', array(
				'id'					 => 'bookingSform',
				'enableClientValidation' => true,
				'clientOptions'			 => array(
					'validateOnSubmit'	 => true,
					'errorCssClass'		 => 'has-error',
					'afterValidate'		 => 'js:function(form,data,hasError){
                            if(!hasError){
                            var success = false;
							$.ajax({
								"type":"POST",
								"async":false,
								"url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('booking/validateSearch')) . '",
								"data":form.serialize(),
									"dataType": "json",
									"success":function(data1){
										if(data1.success)
										{
											success = true;
										}
										else{
											var errors = data1.errors;
											var content = "";
											for(var key in errors){
												$.each(errors[key], function (j, message) {
													content = content + message + \'\n\';
												});
											}
											alert(content);
										}
									},
								});
                                return success;
                                
                            }
                        }'
				),
				'enableAjaxValidation'	 => false,
				'errorMessageCssClass'	 => 'help-block',
				'action'				 => Yii::app()->createUrl('booking/new'),
				'htmlOptions'			 => array(
					'class'			 => 'form-horizontal',
					'autocomplete'	 => 'off',
				),
			));
			/* @var $form TbActiveForm */

			$brtModel		 = $model->bookingRoutes[0];
			?>
            <div class="col-xs-12 pb20">
				<?= $form->errorSummary($model); ?>
				<?= $form->hiddenField($model, 'bkg_booking_type', ['value' => 1]); ?>
				<?= $form->hiddenField($model, 'bktyp', ['value' => 1]); ?>
                <input type="hidden" id="step1" name="step" value="0">
                <label>Source</label>
				<?php
				$this->widget('ext.yii-selectize.YiiSelectize', array(
					'model'				 => $brtModel,
					'attribute'			 => 'brt_from_city_id',
					'useWithBootstrap'	 => true,
					"placeholder"		 => "Source City",
					'fullWidth'			 => false,
					'htmlOptions'		 => array('width' => '50%', ''
					),
					'defaultOptions'	 => $selectizeOptions + array(
				'onInitialize'	 => "js:function(){
					//		populateSource(this);
                        }",
				'load'			 => "js:function(query, callback){
							loadSource(query, callback);
                        }",
				'onChange'		 => "js:function(value) {
							changeDestination(value, \$dest_city);
						}",
				'render'		 => "js:{
								option: function(item, escape){                      
										return '<div><span class=\"\">' + escape(item.text) +'</span></div>';                          
								},
								option_create: function(data, escape){
									 return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
							   }
							}",
					),
				));
				?>
                <span class="has-error"><? echo $form->error($model, 'bkg_from_city_id'); ?></span>
            </div>
            <div class="col-xs-12 pb20">
                <label>Destination</label>
				<?php
				$this->widget('ext.yii-selectize.YiiSelectize', array(
					'model'				 => $brtModel,
					'attribute'			 => 'brt_to_city_id',
					'useWithBootstrap'	 => true,
					"placeholder"		 => "Destination City",
					'fullWidth'			 => false,
					'htmlOptions'		 => array('width' => '50%'
					),
					'defaultOptions'	 => $selectizeOptions + array(
				'onInitialize'	 => "js:function(){
																\$dest_city=this;
						                                    }",
				'render'		 => "js:{
                                         option: function(item, escape){                      
                                                 return '<div><span class=\"\">' + escape(item.text) +'</span></div>';                          
                                         },
                                         option_create: function(data, escape){
                                              return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
                                        }
                                    }",
					),
				));
				?>
                <span class="has-error"><? echo $form->error($brtModel, 'brt_to_city_id'); ?></span>
                <span class="has-error"><? echo $form->error($brtModel, 'brt_pickup_date_date'); ?></span>
                <span class="has-error"><? echo $form->error($brtModel, 'brt_pickup_date_time'); ?></span>
            </div>
            <div class="col-xs-12 ">
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6 pb20">
                        <label>Journey Date</label>
						<?=
						$form->datePickerGroup($brtModel, 'brt_pickup_date_date', array('label'			 => '',
							'widgetOptions'	 => array('options'		 => array('autoclose'	 => true, 'startDate'	 => $minDate,
									'format'	 => 'dd/mm/yyyy'), 'htmlOptions'	 => array('required'		 => true, 'placeholder'	 => 'Pickup Date',
									'class'			 => 'form-control border-radius')),
							'groupOptions'	 => ['class' => 'm0'],
							'prepend'		 => '<i class="fa fa-calendar"></i>'));
						?>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6 pb20">
                        <label>Journey Time</label>
                        <div class="input-group full-width">
							<?=
							$form->timePickerGroup($brtModel, 'brt_pickup_date_time', array('label'			 => '',
								'widgetOptions'	 => array('options'		 => array('defaultTime'	 => true,
										'autoclose'		 => true),
									'htmlOptions'	 => array('required'		 => true, 'placeholder'	 => 'Pickup Time',
										'class'			 => 'form-control pr0 border-radius text text-info')),
								'groupOptions'	 => ['class' => 'm0'],
							));
							?> 
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-offset-3 col-sm-6 pb20">
                <button type="submit" class="btn btn-primary form-control">Proceed</button>
            </div>
			<?php $this->endWidget(); ?>
        </div>
        <div class="tab-pane fade in  home-search1 pt5 pb5" id="menu5">
            <div id='returnform'>
				<?
				$form1			 = $this->beginWidget('booster.widgets.TbActiveForm', array(
					'id'					 => 'bookingRform',
					'enableClientValidation' => true,
					'clientOptions'			 => array(
						'validateOnSubmit'	 => true,
						'errorCssClass'		 => 'has-error',
						'afterValidate'		 => 'js:function(form,data,hasError){
                            if(!hasError){
                            var success = false;
							$.ajax({
								"type":"POST",
								"async":false,
								"url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('booking/validateSearch')) . '",
								"data":form.serialize(),
									"dataType": "json",
									"success":function(data1){
										if(data1.success)
										{
											success = true;
										}
										else{
											var errors = data1.errors;
											var content = "";
											for(var key in errors){
												$.each(errors[key], function (j, message) {
													content = content + message + \'\n\';
												});
											}
											alert(content);
										}
									},
								});
                                return success;
                            }
                        }'
					),
					'enableAjaxValidation'	 => false,
					'errorMessageCssClass'	 => 'help-block',
					'action'				 => Yii::app()->createUrl('booking/new'),
					'htmlOptions'			 => array(
						'class'			 => 'form-horizontal',
						'autocomplete'	 => 'off',
					),
				));
				/* @var $form1 TbActiveForm */
				?>
                <div class="col-xs-12">
					<?= $form1->errorSummary($model); ?>
                    <div id='bkt'>
						<?= $form1->hiddenField($model, 'bkg_booking_type', ['value' => 2]); ?>
						<?= $form1->hiddenField($model, 'bktyp', ['value' => 2]); ?>
                        <input type="hidden" id="step1" name="step" value="0">
                        <input type="hidden" id="step2" name="step2" value="2">
                    </div>
                    <div class="input-group col-xs-12 mb10">
                        <label>Source</label>
						<?php
						$this->widget('ext.yii-selectize.YiiSelectize', array(
							'model'				 => $model,
							'attribute'			 => 'bkg_from_city_id',
							'useWithBootstrap'	 => true,
							"placeholder"		 => "Source City",
							'fullWidth'			 => false,
							'htmlOptions'		 => array('width'	 => '50%', 'id'	 => 'bkg_from_city_id1',
							),
							'defaultOptions'	 => $selectizeOptions + array(
						'onInitialize'	 => "js:function(){
							populateSource(this);
                        }",
						'onChange'		 => "js:function(value) {
							changeDestination(value, \$dest_city1);
						}",
						'render'		 => "js:{
								option: function(item, escape){                      
										return '<div><span class=\"\">' + escape(item.text) +'</span></div>';                          
								},
								option_create: function(data, escape){
									 return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
							   }
							}",
							),
						));
						?>
                        <span class="has-error"><? echo $form->error($model, 'bkg_from_city_id'); ?></span>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="input-group col-xs-12 mb10">
                        <label>Destination</label>
						<?php
						$this->widget('ext.yii-selectize.YiiSelectize', array(
							'model'				 => $model,
							'attribute'			 => 'bkg_to_city_id',
							'useWithBootstrap'	 => true,
							"placeholder"		 => "Select Destination",
							'fullWidth'			 => false,
							'htmlOptions'		 => array('id'	 => 'bkg_to_city_id1', 'width'	 => '50%'
							),
							'defaultOptions'	 => $selectizeOptions + array(
						'onInitialize'	 => "js:function(){
					
																\$dest_city1=this;
						                                    }",
						'render'		 => "js:{
                                         option: function(item, escape){                      
                                                 return '<div><span class=\"\">' + escape(item.text) +'</span></div>';                          
                                         },
                                         option_create: function(data, escape){
                                              return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
                                        }
                                    }",
							),
						));
						?>
                        <span class="has-error"><? echo $form1->error($model, 'bkg_to_city_id1'); ?></span>
                    </div>
                </div>
                <div class="col-xs-12 pb10">
                    <div class="row">
                        <div class="col-sm-6 col-md-6">
                            <label>Start Date</label>
							<?=
							$form1->datePickerGroup($model, 'bkg_pickup_date_date', array('label'			 => '',
								'widgetOptions'	 => array('options'		 => array('autoclose'	 => true, 'startDate'	 => $minDate,
										'format'	 => 'dd/mm/yyyy'), 'htmlOptions'	 => array('required'		 => true, 'placeholder'	 => 'Pickup Date',
										'value'			 => $pdate, 'id'			 => 'Booking_bkg_pickup_date_date1',
										'class'			 => 'border-radius ')), 'groupOptions'	 => ['class' => 'm0'], 'prepend'		 => '<i class="fa fa-calendar"></i>'));
							?>
                            <span class="has-error"><? echo $form1->error($model, 'bkg_pickup_date_date1'); ?></span>
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <label>Start Time</label>
							<?=
							$form1->timePickerGroup($model, 'bkg_pickup_date_time', array('label'			 => '',
								'widgetOptions'	 => array('options'		 => array('defaultTime' => false, 'autoclose' => true),
									'htmlOptions'	 => array('required'		 => true, 'placeholder'	 => 'Pickup Time',
										'value'			 => $ptime, 'id'			 => 'bkg_pickup_date_time1',
										'class'			 => ' pr0 border-radius text text-info')), 'groupOptions'	 => ['class' => 'm0'],));
							?> 
                        </div>
                        <span class="has-error"><? echo $form1->error($model, 'bkg_pickup_date_date1'); ?></span>
                        <span class="has-error"><? echo $form1->error($model, 'bkg_pickup_date_time1'); ?></span>
                    </div>
                </div>
                <div class="col-xs-12 pb10">
                    <div class="row">
                        <div class="col-sm-6 col-md-6">
                            <label>Return Date</label>
							<?=
							$form1->datePickerGroup($model, 'bkg_return_date_date', array('label'			 => '',
								'widgetOptions'	 => array('options'		 => array('autoclose'	 => true, 'startDate'	 => $minDate,
										'format'	 => 'dd/mm/yyyy'),
									'htmlOptions'	 => array('required'		 => true, 'placeholder'	 => 'Return Date',
										'value'			 => DateTimeFormat::DateTimeToDatePicker($defaultRDate), 'id'			 => 'Booking_bkg_return_date_date',
										'class'			 => 'border-radius ')), 'groupOptions'	 => ['class' => 'm0'], 'prepend'		 => '<i class="fa fa-calendar"></i>'));
							?>
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <label>Return Time</label>
							<?=
							$form1->timePickerGroup($model, 'bkg_return_date_time', array('label'			 => '',
								'widgetOptions'	 => array('options'		 => array('defaultTime' => true, 'autoclose' => true),
									'htmlOptions'	 => array('required'		 => true, 'placeholder'	 => 'Return Time',
										'value'			 => $ptime, 'id'			 => 'Booking_bkg_return_date_time',
										'class'			 => ' pr0 border-radius text text-info')), 'groupOptions'	 => ['class' => 'm0'],));
							?> 
                        </div>
                        <span class="has-error"><? echo $form1->error($model, 'bkg_pickup_date_date1'); ?></span>
                        <span class="has-error"><? echo $form1->error($model, 'bkg_pickup_date_time1'); ?></span>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-offset-3 col-sm-6 pb10">
                    <button type="submit" class="btn btn-primary form-control">Proceed</button>
                </div>
				<?php $this->endWidget(); ?>
            </div>
        </div>
        <div class="tab-pane fade in home-search" id="menu6">
            <div id='multiform'>
				<?
				$form2			 = $this->beginWidget('booster.widgets.TbActiveForm', array(
					'id'					 => 'bookingMform',
					'enableClientValidation' => true,
					'clientOptions'			 => array(
						'validateOnSubmit'	 => true,
						'errorCssClass'		 => 'has-error',
						'afterValidate'		 => 'js:function(form,data,hasError){
                            if(!hasError){
                            var success = false;
							$.ajax({
								"type":"POST",
								"async":false,
								"url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('booking/validateSearch')) . '",
								"data":form.serialize(),
									"dataType": "json",
									"success":function(data1){
										if(data1.success)
										{
											success = true;
										}
										else{
											var errors = data1.errors;
											var content = "";
											for(var key in errors){
												$.each(errors[key], function (j, message) {
													content = content + message + \'\n\';
												});
											}
											alert(content);
										}
									},
								});
                                return success;
                                
                            }
                        }'
					),
					'enableAjaxValidation'	 => false,
					'errorMessageCssClass'	 => 'help-block',
					'action'				 => Yii::app()->createUrl('booking/new'),
					'htmlOptions'			 => array(
						'class'			 => 'form-horizontal',
						'autocomplete'	 => 'off',
					),
				));
				/* @var $form1 TbActiveForm */
				?>
                <div class="col-xs-12">
					<?= $form2->errorSummary($model); ?>
                    <div id='bkt'>
						<?= $form2->hiddenField($model, 'bkg_booking_type', ['value' => 3]); ?>
						<?= $form2->hiddenField($model, 'bktyp', ['value' => 3]); ?>
                        <input type="hidden" id="step2" name="step2" value="2">
                        <input type="hidden" id="step1" name="step" value="0">
                    </div>
                    <div class="input-group col-xs-12 mb10">
                        <label>Going From</label>
						<?php
						$this->widget('ext.yii-selectize.YiiSelectize', array(
							'model'				 => $model,
							'attribute'			 => 'bkg_from_city_id',
							'useWithBootstrap'	 => true,
							"placeholder"		 => "Going From",
							'fullWidth'			 => false,
							'htmlOptions'		 => array('width'	 => '50%', 'id'	 => 'bkg_from_city_id_1'
							),
							'defaultOptions'	 => $selectizeOptions + array(
						'onInitialize'	 => "js:function(){
							populateSource(this);
                        }",
						'onChange'		 => "js:function(value) {
							changeDestination(value, \$dest_city_1);
						}",
						'render'		 => "js:{
								option: function(item, escape){                      
										return '<div><span class=\"\">' + escape(item.text) +'</span></div>';                          
								},
								option_create: function(data, escape){
									 return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
							   }
							}",
							),
						));
						?>
                        <span class="has-error"><? echo $form->error($model, 'bkg_from_city_id'); ?></span>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="input-group col-xs-12 mb10">
                        <label>Going To</label>
						<?
						$this->widget('ext.yii-selectize.YiiSelectize', array(
							'model'				 => $model,
							'attribute'			 => 'bkg_to_city_id',
							'useWithBootstrap'	 => true,
							"placeholder"		 => "Going To",
							'fullWidth'			 => false,
							'htmlOptions'		 => array('id'	 => 'bkg_to_city_id_1', 'width'	 => '50%'
							),
							'defaultOptions'	 => $selectizeOptions + array(
						'onInitialize'	 => "js:function(){
																\$dest_city_1=this;
						                                    }",
						'render'		 => "js:{
                                         option: function(item, escape){                      
                                                 return '<div><span class=\"\">' + escape(item.text) +'</span></div>';                          
                                         },
                                         option_create: function(data, escape){
                                              return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
                                        }
                                    }",
							),
						));
						?>
                        <span class="has-error"><? echo $form1->error($model, 'bkg_to_city_id1'); ?></span>
                    </div>
                </div>
                <div class="col-xs-12 pb20">
                    <div class="row">
                        <div class="col-sm-6 col-md-6">
                            <label>Start Date</label>
							<?=
							$form2->datePickerGroup($model, 'bkg_pickup_date_date', array('label'			 => '',
								'widgetOptions'	 => array('options'		 => array('autoclose'	 => true, 'startDate'	 => $minDate,
										'format'	 => 'dd/mm/yyyy'), 'htmlOptions'	 => array('required'		 => true, 'placeholder'	 => 'Pickup Date',
										'value'			 => $pdate, 'id'			 => 'Booking_bkg_pickup_date_date_1',
										'class'			 => 'border-radius ')), 'groupOptions'	 => ['class' => 'm0'], 'prepend'		 => '<i class="fa fa-calendar"></i>'));
							?>
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <label>Start Time</label>
							<?=
							$form2->timePickerGroup($model, 'bkg_pickup_date_time', array('label'			 => '',
								'widgetOptions'	 => array('options'		 => array('defaultTime' => false, 'autoclose' => true),
									'htmlOptions'	 => array('required'		 => true, 'placeholder'	 => 'Pickup Time',
										'value'			 => $ptime, 'id'			 => 'Booking_bkg_pickup_date_time_1',
										'class'			 => ' pr0 border-radius text text-info')), 'groupOptions'	 => ['class' => 'm0'],));
							?> 
                        </div>
                        <span class="has-error"><? echo $form2->error($model, 'bkg_pickup_date_date_1'); ?></span>
                        <span class="has-error"><? echo $form2->error($model, 'bkg_pickup_date_time_1'); ?></span>
                    </div>
                </div>
                <div class="col-xs-12 mb10 p5 text-center">
                    <div class="input-group col-xs-12 text-center">
                        <button type="submit" class="btn btn-primary proceed-btn">Add more city</button>
                    </div>
                </div>
				<?php $this->endWidget(); ?>
            </div>
        </div>
        <div class="tab-pane fade in home-search" id="menu7">
			<?
			/* @var $form TbActiveForm|CWidget */
			$form3			 = $this->beginWidget('booster.widgets.TbActiveForm', array(
				'id'					 => 'bookingTrform',
				'enableClientValidation' => true,
				'clientOptions'			 => array(
					'validateOnSubmit'	 => true,
					'errorCssClass'		 => 'has-error',
					'afterValidate'		 => 'js:function(form,data,hasError){
                            if(!hasError){
                            return true;
                                
                            }
                        }'
				),
				'enableAjaxValidation'	 => false,
				'errorMessageCssClass'	 => 'help-block',
				'action'				 => Yii::app()->createUrl('booking/new'),
				'htmlOptions'			 => array(
					'class'			 => 'form-horizontal',
					'autocomplete'	 => 'off',
				),
			));
			/* @var $form TbActiveForm */
			?>

			<?= $form3->errorSummary($model); ?>
			<?= $form3->hiddenField($model, 'bkg_booking_type', ['value' => 4]); ?>
			<?= $form3->hiddenField($model, 'bktyp', ['value' => 4]); ?>
            <input type="hidden" id="step1" name="step" value="0">
            <div class="col-xs-12 mt5 n">
                <label>Pickup Type</label>
                <nobr><?= $form3->radioButtonListGroup($model, 'bkg_transfer_type', array('label' => '', 'widgetOptions' => array('data' => Booking::model()->transferTypes), 'inline' => true)) ?></nobr>
            </div>
            <div id="ttype" style="display: none">
                <div class="col-xs-12 pb10" id="s1" style="display: none">
                    <label id="slabel">Source</label>
					<?php
					// $datacity = Cities::model()->getJSONServiceCities();
					//$datacity = Cities::model()->getJSONRateSourceCities();
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $model,
						'attribute'		 => 'bkg_from_city_id',
						'val'			 => $model->bkg_from_city_id,
						'asDropDownList' => FALSE,
						'options'		 => array('data'				 => 'js:function(){return {results: fromCityTr};}',
							'formatNoMatches'	 => "js:function(term){return \"$emptyTransferDropdown\"}"),
						'htmlOptions'	 => array('id' => 'bkg_from_city_id_tr', 'style' => 'width:100%', 'placeholder' => 'Select Source', 'class' => 'ctyDrop1')
					));
					?>
                    <span class="has-error"><? echo $form->error($model, 'bkg_from_city_id'); ?></span>
                </div>
                <div class="col-xs-12 pb10" id="s2" style="display: none">
                    <label id="dlabel">Destination</label>
					<?
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $model,
						'attribute'		 => 'bkg_to_city_id',
						'val'			 => $model->bkg_to_city_id,
						'asDropDownList' => FALSE,
						'options'		 => array('data'				 => 'js:function(){return {results: toCity4};}',
							'formatNoMatches'	 => "js:function(term){return \"$emptyTransferDropdown\"}"
						),
						'htmlOptions'	 => array('id' => 'bkg_to_city_id_tr', 'style' => 'width:100%', 'placeholder' => 'Select Destination', 'class' => 'ctyDrop1')
					));
					?>
                    <span class="has-error"><? echo $form->error($model, 'bkg_to_city_id'); ?></span>
                    <span class="has-error"><? echo $form->error($model, 'bkg_pickup_date_date'); ?></span>
                    <span class="has-error"><? echo $form->error($model, 'bkg_pickup_date_time'); ?></span>
                </div>
                <div class="col-xs-12 ">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-6 pb10">
                            <label>Journey Date</label>
							<?
							$defaultDate	 = date('Y-m-d H:i:s', strtotime('+7 days'));
							$defaultRDate	 = date('Y-m-d H:i:s', strtotime('+8 days'));
							$minDate		 = date('Y-m-d H:i:s', strtotime('+4 hour'));
							$pdate			 = ($model->bkg_pickup_date_date == '') ? DateTimeFormat::DateTimeToDatePicker($defaultDate) : $model->bkg_pickup_date_date;
							?>
							<?=
							$form->datePickerGroup($model, 'bkg_pickup_date_date', array('label'			 => '',
								'widgetOptions'	 => array('options'		 => array('autoclose'	 => true, 'startDate'	 => $minDate,
										'format'	 => 'dd/mm/yyyy'), 'htmlOptions'	 => array('required'		 => true, 'placeholder'	 => 'Pickup Date',
										'value'			 => $pdate, 'id'			 => 'Booking_bkg_pickup_date_date_11',
										'class'			 => 'form-control border-radius')),
								'groupOptions'	 => ['class' => 'm0'],
								'prepend'		 => '<i class="fa fa-calendar"></i>'));
							?>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6 pb10">
                            <label>Journey Time</label>
                            <div class="input-group full-width">
								<? $ptime			 = date('h:i A', strtotime('6am')); ?>
								<?=
								$form->timePickerGroup($model, 'bkg_pickup_date_time', array('label'			 => '',
									'widgetOptions'	 => array('options'		 => array('defaultTime'	 => true,
											'autoclose'		 => true),
										'htmlOptions'	 => array('required'		 => true, 'placeholder'	 => 'Pickup Time',
											'value'			 => $ptime,
											'class'			 => 'form-control pr0 border-radius text text-info')),
									'groupOptions'	 => ['class' => 'm0'],
								));
								?> 
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-offset-3 col-sm-6 pb10">
                    <button type="submit" class="btn btn-primary form-control">Proceed</button>
                </div>
            </div>
			<?php $this->endWidget(); ?>
        </div>
    </div>
</div>
<div class="col-xs-12 col-sm-7 col-md-8 p0 banner_panel hidden-xs">
    <div id="myCarousel" class="carousel slide" data-ride="carousel">
        <!-- Indicators 
				   
        -->
        <!-- Wrapper for slides -->
        <div class="carousel-inner banner-carousel" role="listbox" data-interval="10000" >		
            <div class="item active">                
                <figure><a href="/"><img src="/images/banner15.jpg?v=1.2" alt="Save Upto 50%"></a></figure>               
            </div>
            <div class="item">
                <figure><a href="/"><img src="/images/banne13r1.jpg" alt="Live Life, Live Gozo"></a></figure>              
            </div>
            <div class="item">
                <figure><a href="/"><img src="/images/all_india.jpg?v=1.1" alt="Travel All Over India, aaocab"></a></figure>               
            </div>
            <div class="item">
                <figure><a href="/"><img src="/images/banner1.png?v=1.1" alt="Refer Friend & Earn Money"></a></figure>
                <div class="carousel-caption" style="z-index: 999">
                    <h3 class="text-right"><a href="/">Read more..</a></h3>
                </div>
            </div>
            <div class="item">
                <figure><a href="/"><img data-src="/images/banner18.jpg?v=1.2" alt="Travel Happy Travel Gozo"></a></figure>
                <div class="carousel-caption">
                    <h3 class="text-right"><a href="/">Read more..</a></h3>
                </div>
            </div>
            <div class="item">
                <figure><a href="/changeindia"><img data-src="/images/banner21.jpg?v=1.2" alt="Follow aaocab on Social Network"></a></figure>
                <div class="carousel-caption">
                    <h3 class="text-right"><a href="/">Read more..</a></h3>
                </div>
            </div>
            <div class="item">
                <figure><a href="/pricematch"><img data-src="/images/banner19.jpg?v=1.2" alt="Sweetest Deal 7 Days Ahead, aaocab"></a></figure>
                <div class="carousel-caption">
                    <h3 class="text-right"><a href="/">Read more..</a></h3>
                </div>
            </div>
            <div class="item">
                <figure><a href="/tchallenge"><img data-src="/images/banner17.jpg?v=1.2" alt="Go Gozo, aaocab"></a></figure>
                <div class="carousel-caption">
                    <h3 class="text-right"><a href="/tchallenge">Read more..</a></h3>
                </div>
            </div>
            <div class="item">
                <figure><a href="/travelhappy"><img data-src="/images/banner20.jpg?v=1.2" alt="Best Price Guarantee, aaocab"></a></figure>
                <div class="carousel-caption">
                    <h3 class="text-right"><a href="/">Read more..</a></h3>
                </div>
            </div>
        </div>

        <!-- Left and right controls -->
        <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
            <span class="fa fa-angle-left fa-3x" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
            <span class="fa  fa-angle-right fa-3x" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 hidden-lg hidden-md hidden-sm text-center">
        <p class="m0 mt10"><b>Book with Gozo cabs mobile app</b></p>
        <div class="mt10"><figure><a href="https://play.google.com/store/apps/details?id=com.aaocab.client" target="_blank"><img src="/images/GooglePlay.png" alt="aaocab App - Play Store" style="height: 70px"></a></figure></div>
    </div>
</div>

<script>


	$fromCity = '<?= $datacity ?>';
	var toCity = [];
	var toCity1 = [];
	var toCity2 = [];
	var toCity4 = [];
	var fromCityTr = [];
	$destCity = null;
	$(function ()
	{
		$(window).on("scroll", function ()
		{
			if ($(window).scrollTop() > 50)
			{
				$(".top-menu").addClass("white-header");
			}
			else
			{
				$(".top-menu").removeClass("white-header");
			}
		});
	});
	$(document).ready(function ()
	{
//        populateData();
//        populateDataR();
//        populateDataM();
		if (window.location.hash == '#airport-transfer')
		{
			$('#otrip').removeClass('active');
			$('.home-search').removeClass('active');
			$('.home-search1').removeClass('active');
			$('#ttrip').addClass('active');
			$('#menu7').addClass('active');
		}
		$('#Booking_bkg_pickup_date_time1').timepicker({'defaultTime': true, 'autoclose': true});
		$('#Booking_bkg_pickup_date_time_1').timepicker({'defaultTime': true, 'autoclose': true});
		$('#Booking_bkg_return_date_time').timepicker({'defaultTime': true, 'autoclose': true}
		);
		$('#<?= CHtml::activeId($model, "bkg_transfer_type_0") ?>').attr('checked', 'checked');
		trType0Chkd();
	});

	function loadSource(query, callback)
	{
		if (!query.length)
			return callback();
		$.ajax({
			url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/citylist')) ?>?q=' + encodeURIComponent(query),
			type: 'GET',
			error: function ()
			{
				callback();
			},
			success: function (res)
			{
				callback(res.data);
			}
		});
	}
	function populateSource(obj)
	{
		obj.load(function (callback)
		{
			var obj = this;
			xhr = $.ajax({
				url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/citylist')) ?>',
				dataType: 'json',
				success: function (results)
				{
					obj.enable();
					callback(results.data);
					obj.setValue('<?= $model->bkg_from_city_id ?>');
				},
				error: function ()
				{
					callback();
				}
			});
		});
	}
	function changeDestination(value, obj)
	{
		if (!value.length)
			return;
		var existingValue = obj.getValue();
		if (existingValue == '')
		{
			existingValue = '<?= $model->bkg_to_city_id ?>';
		}
		obj.disable();
		obj.clearOptions();
		obj.load(function (callback)
		{
			//  xhr && xhr.abort();
			xhr = $.ajax({
				url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/nearestcitylist')) ?>/source/' + value,
				dataType: 'json',
				success: function (results)
				{
					obj.enable();
					callback(results.data);
					obj.setValue(existingValue);
				},
				error: function ()
				{
					callback();
				}
			});
		});
	}


	function populateDataM(e)
	{
		$scity = e.added.id;
		if ($scity !== "")
		{
			$.ajax({
				"type": "GET",
				"dataType": "json",
				"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('index/getnearest')) ?>",
				"data": {"source": $scity},
				"async": false,
				"success": function (data1)
				{
					toCity2 = data1;
				}
			});
		}
	}
	function populateDataR(e)
	{
		$scity = e.added.id;
		if ($scity !== "")
		{
			$.ajax({
				"type": "GET",
				"dataType": "json",
				"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('index/getnearest')) ?>",
				"data": {"source": $scity},
				"async": false,
				"success": function (data1)
				{
					toCity1 = data1;
				}
			});
		}
	}

	function populateData(e)
	{
		$scity = e.added.id;
		if ($scity !== "")
		{
			$.ajax({
				"type": "GET",
				"dataType": "json",
				"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('index/getnearest')) ?>",
				"data": {"source": $scity},
				"async": false,
				"success": function (data1)
				{
					toCity = data1;
				}
			});
		}
	}


	$('#bookingtimform1').submit(function (event)
	{

		fcity = $('#Booking_bkg_from_city_id').val();
		tcity = $('#Booking_bkg_to_city_id').val();
		// alert(tcity);
	});

	function populateDataTrP()
	{

		$.ajax({
			"type": "GET",
			"dataType": "json",
			"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('index/getairportcities')) ?>",
			"success": function (data1)
			{
				fromCityTr = data1;
			}
		});
	}

	function populateDataTrD()
	{

		$.ajax({
			"type": "GET",
			"dataType": "json",
			"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('index/getairportcities')) ?>",
			"success": function (data1)
			{
				toCity4 = data1;
			}
		});
	}

	function populateDataTrOthersF($scity)
	{

		if ($scity !== "")
		{
			$('#s2').fadeIn('slow');
			$.ajax({
				"type": "GET",
				"dataType": "json",
				"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('index/getairportnearest')) ?>",
				"data": {"source": $scity},
				"async": false,
				"success": function (data1)
				{
					toCity4 = data1;
				}
			});
		}
	}
	function populateDataTrOthersT($scity)
	{

		if ($scity !== "")
		{
			$('#s1').fadeIn('slow');
			$.ajax({
				"type": "GET",
				"dataType": "json",
				"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('index/getairportnearest')) ?>",
				"data": {"source": $scity},
				"async": false,
				"success": function (data1)
				{
					fromCityTr = data1;
				}
			});
		}
	}



	var placeholder1 = $('#<?= CHtml::activeId($model, "bkg_from_city_id") ?>').attr('placeholder');
	var placeholder2 = $('#<?= CHtml::activeId($model, "bkg_to_city_id") ?>').attr('placeholder');

	function resetTransferSelects()
	{
		$('#bkg_from_city_id_tr').select2('val', '').trigger("change");
		$('#bkg_to_city_id_tr').select2('val', '').trigger("change");
		fromCityTr = [];
		toCity4 = [];
	}



	$('#<?= CHtml::activeId($model, "bkg_transfer_type_0") ?>').click(function ()
	{
		resetTransferSelects();
		$('#s2').hide();
		if ($('#<?= CHtml::activeId($model, "bkg_transfer_type_0") ?>').is(':checked'))
		{
			trType0Chkd();
		}
	});

	$('#<?= CHtml::activeId($model, "bkg_transfer_type_1") ?>').click(function ()
	{
		resetTransferSelects();
		$('#s1').hide();
		if ($('#<?= CHtml::activeId($model, "bkg_transfer_type_1") ?>').is(':checked'))
		{
			trType1Chkd()
		}
	});
	function trType0Chkd()
	{
		$("#slabel").text('Pickup Airport');
		$("#dlabel").text('Destination');
		$('#ttype').fadeIn('slow');
		$('#s1').fadeIn('slow');
		populateDataTrP();

	}
	function trType1Chkd()
	{

		$("#dlabel").text('Destination Airport');
		$("#slabel").text('Source');
		$('#ttype').fadeIn('slow');
		$('#s2').fadeIn('slow');
		populateDataTrD();
	}



	$('#bkg_from_city_id_tr').change(function ()
	{
		if ($('#<?= CHtml::activeId($model, "bkg_transfer_type_0") ?>').is(':checked'))
		{
			$('#bkg_to_city_id_tr').select2('val', '');
			populateDataTrOthersF($('#bkg_from_city_id_tr').select2('val'));
		}
	});
	$('#bkg_to_city_id_tr').change(function ()
	{
		if ($('#<?= CHtml::activeId($model, "bkg_transfer_type_1") ?>').is(':checked'))
		{
			$('#bkg_from_city_id_tr').select2('val', '');
			populateDataTrOthersT($('#bkg_to_city_id_tr').select2('val'));
		}
	});

	$('#rtrip').click(function ()
	{
		$('#bkt #Booking_bkg_booking_type').val(2);
		$('#bkt #Booking_bktyp').val(2);
	});
	$('#mtrip').click(function ()
	{
		$('#bkt #Booking_bkg_booking_type').val(3);
		$('#bkt #Booking_bktyp').val('3');
	});
</script>