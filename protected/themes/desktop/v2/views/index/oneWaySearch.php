<?php
$ptime					 = date('h:i A', strtotime('6am'));
//$model->bkg_pickup_date_time = $ptime;
$timeArr				 = Filter::getTimeDropArr($ptime);
$ptimePackage			 = Yii::app()->params['defaultPackagePickupTime'];
$timeArrPackage			 = Filter::getTimeDropArr($ptimePackage);
$selectizeOptions		 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true, 'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id', 'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
$cityRadius				 = Yii::app()->params['airportCityRadius'];
$emptyTransferDropdown	 = "Please check your transfer type.<br>Let us help you.<br><i>Call now</i> (+91) 90518-77-000";
//Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');
?>

<div class="row search-panel-2">
	<div class="col-12">
<div class="tab-content">
	<div class="tab-pane active mt10 mb5" id="menu4">
		<?php
		/* @var $form CActiveForm|CWidget */
		$form					 = $this->beginWidget('CActiveForm', array(
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
			'action'				 => Yii::app()->createUrl('book-cab/one-way'),
			'htmlOptions'			 => array(
				'class' => 'form-horizontal',
			),
		));
		/* @var $form CActiveForm */
		/** @var BookingTemp $model */
		$brtModel				 = $model->bookingRoutes[0];
		?>
		<div class="row" >
			<div class="col-12 col-sm-12 col-lg-6">
				<div class="row">
					<div class="col-12 col-sm-6">

						<?//= $form->errorSummary($brtModel); ?>
						<?= $form->hiddenField($model, 'bkg_booking_type', ['value' => 1, 'id' => 'bkg_booking_type1']); ?>
						<?= $form->hiddenField($model, 'bktyp', ['value' => 1, 'id' => 'bktyp1']); ?>
						<input type="hidden" id="step11" name="step" value="1">
						<label> Default Source</label>
						<?php
						$this->widget('ext.yii-selectize.YiiSelectize', array(
							'model'				 => $brtModel,
							'attribute'			 => 'brt_from_city_id',
							'useWithBootstrap'	 => true,
							"placeholder"		 => "Going From",
							'fullWidth'			 => false,
							'htmlOptions'		 => array('width' => '50%', ''
							),
							'defaultOptions'	 => $selectizeOptions + array(
						'onInitialize'	 => "js:function(){
								populateSource(this, '{$brtModel->brt_from_city_id}');
							}",
						'load'			 => "js:function(query, callback){
								loadSource(query, callback);
							}",
						'onChange'		 => "js:function(value) {
								changeDestination(value, \$dest_city);
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
						<span class="has-error"><? //echo $form->error($brtModel, 'brt_from_city_id'); ?></span>
					</div>
					<div class="col-12 col-sm-6 col-md-6">
						<label>Destination</label>
					<?php
					$this->widget('ext.yii-selectize.YiiSelectize', array(
						'model'				 => $brtModel,
						'attribute'			 => 'brt_to_city_id',
						'useWithBootstrap'	 => true,
						"placeholder"		 => "Going To",
						'fullWidth'			 => false,
						'htmlOptions'		 => array('width' => '50%'
						),
						'defaultOptions'	 => $selectizeOptions + array(
					'onInitialize'	 => "js:function(){ \$dest_city=this;
												}",
					 'render'		 => "js:{
												option: function(item, escape){
												return '<div><span class=\"\"><i class=\"fa fa-map-marker mr5\"></i>' + escape(item.text) +'</span></div>';                          
												},
												option_create: function(data, escape){
												 return '<div>' +'<span class=\"\"><i class=\"fa fa-map-marker mr5\"></i>' + escape(data.text) + '</span></div>';
											   }
											   }",
						),
					));
					?>
						<span class="has-error"><? echo $form->error($brtModel, ' brt_to_city_id'); ?></span>
						<span class="has-error"><? echo $form->error($brtModel, ' brt_pickup_date_date'); ?></span>
						<span class="has-error"><? echo $form->error($brtModel, ' brt_pickup_date_time'); ?></span>
					</div>
				</div>
            </div>
			<div class="col-12 col-sm-12 col-lg-4 search-panel-3">
				<div class="row">
					<div class="col-12 col-sm-6 col-md-6">
						<label>Journey Date</label>
                        <?
						$defaultDate	 = date('Y-m-d H:i:s', strtotime('+2 days'));
						$defaultRDate	 = date('Y-m-d H:i:s', strtotime('+3 days'));
						$minDate		 = date('Y-m-d H:i:s ', strtotime('+4 hour'));
						$pdate			 = ($brtModel->brt_pickup_date_date == '') ? DateTimeFormat::DateTimeToDatePicker($defaultDate) : $brtModel->brt_pickup_date_date;
						?>
    

<div class="input-group"><div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
					<?=
						$form->widget('zii.widgets.jui.CJuiDatePicker',array(
							'model'=>$brtModel,
							'attribute'=>'brt_pickup_date_date',
							'options'=> array('autoclose'=> true, 'startDate'=> $minDate,'format'=> 'dd/mm/yyyy'),
							'htmlOptions'=> array('required' => true, 'placeholder'	=> 'Pickup Date',
											'value'			 => $pdate,
											'class'			 => 'form-control input-style')
							),true);
					?>
</div>
					</div>
					<div class="col-12 col-sm-6 col-md-6">
						<label>Journey Time</label>
						<div class="input-group timer-control">
							<?
							$this->widget('ext.timepicker.TimePicker', array(
							'model'			 => $brtModel,
							'id'			 => 'brt_pickup_date_time_1' . date('mdhis'),
							'attribute'		 => 'brt_pickup_date_time',
							'options'		 => ['widgetOptions' => array('options' => array())],
							'htmlOptions'	 => array('required'		 => true, 'placeholder'	 => 'Pickup Time', 'class' => 'form-control')
							));
							?>
						</div>
					</div>
				</div>
			</div>
			<div class="col-12 col-lg-2  pb20 text-center">
				<button type="submit" class="btn-orange pl20 pr20">proceed</button>
			</div>
		</div>
<?php $this->endWidget(); ?>
	</div>
	<div class="tab-pane mt10 mb5" id="menu5">
		<div id='returnform'>
			<?
			$form1			 = $this->beginWidget('CActiveForm', array(
			'id'					 => 'bookingRform',
			'enableClientValidation' => true,
			'clientOptions'			 => array(
			'validateOnSubmit'	 => true,
			'errorCssClass'		 => 'has-error',
			'afterValidate'		 => 'js:function(form, data, hasError){
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
			alert(content); }  },
			});
			return success;
			}
			}'
			),
			'enableAjaxValidation'	 => false,
			'errorMessageCssClass'	 => 'help-block',
			'action'				 => Yii::app()->createUrl('booking/booknow'),
			'htmlOptions'			 => array(
			'class' => 'form-horizontal',
			),
			));
			/* @var $form1 CActiveForm */
			?>
			<div class="row">
				<div class="col-12 col-md-12 col-lg-5">
					<div class="row">
						<div class="col-12 col-sm-6 col-md-6 col-lg-6">
							<?//= $form1->errorSummary($model); ?>
							<div id='bkt'>
							<?= $form1->hiddenField($model, 'bkg_booking_type', ['value' => 2, 'id' => 'bkg_booking_type2']); ?>
							<?= $form1->hiddenField($model, 'bktyp', ['value' => 2, 'id' => 'bktyp2']); ?>
							<?= $form1->hiddenField($brtModel, 'brt_return_date_time', ['value' => '10:00 PM']); ?>
							<input type="hidden" id="step12" name="step" value="1">
							<input type="hidden" id="step22" name="step2" value="2">
							</div>
							<div class="input-group col-12">
								<label>Source</label>
<?php
							$this->widget('ext.yii-selectize.YiiSelectize', array(
								'model'				 => $brtModel,
								'attribute'			 => 'brt_from_city_id',
								'useWithBootstrap'	 => true,
								"placeholder"		 => "Source City",
								'fullWidth'			 => false,
								'htmlOptions'		 => array('width'	 => '50%', 'id'	 => 'bkg_from_city_id1',
								),
								'defaultOptions'	 => $selectizeOptions + array(
							'onInitialize'	 => "js:function(){
									populateSource(this, '{$brtModel->brt_from_city_id}');
								}",
							 'load'			 => "js:function(query, callback){
									loadSource(query, callback);
								 }",
							 'onChange'		 => "js:function(value) {
									changeDestination(value, \$dest_city1);
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
								<span class="has-error"><? echo $form->error($brtModel, 'brt_from_city_id'); ?></span>
							</div>
						</div>
						<div class="col-12 col-sm-6 col-md-6 col-lg-6">
							<div class="input-group col-12">
							<label>Destination</label>
							<?php
							$this->widget('ext.yii-selectize.YiiSelectize', array(
								'model'				 => $brtModel,
								'attribute'			 => 'brt_to_city_id',
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
														 return '<div><span class=\"\"><i class=\"fa fa-map-marker mr5\"></i>' + escape(item.text) +'</span></div>';                          
												 },
												 option_create: function(data, escape){
													  return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
								}
								}",
								),
							));
							?>
							<span class="has-error"><? echo $form1->error($brtModel, 'brt_to_city_id1'); ?></span>
						</div>
						</div>
					</div>
				</div>
				<div class="col-12 col-md-12 col-lg-7">
					<div class="row">
						<div class="col-12 col-sm-4 col-lg-3">

							<label>Start Date</label>

                    <?=
						$form1->widget('zii.widgets.jui.CJuiDatePicker',array(
							'model'=>$brtModel,
							'attribute'=>'brt_pickup_date_date',
							'options'=> array('autoclose'=> true, 'startDate'=> $minDate,'format'=> 'dd/mm/yyyy'),
							'htmlOptions'=> array('required' => true, 'placeholder'	=> 'Pickup Date',
											'value'			 => $pdate,
											'class'			 => 'form-control input-style')
							),true);
					?>
							<span class="has-error"><? echo $form1->error($model, 'bkg_pickup_date_date1'); ?></span>
						</div>
						<div class="col-12 col-sm-4 col-lg-3">
							<label>Start Time</label>
							<?
							$this->widget('ext.timepicker.TimePicker', array(
							'model'			 => $brtModel,
							'id'			 => 'brt_pickup_date_time_2' . date('mdhis'),
							'attribute'		 => 'brt_pickup_date_time',
							'options'		 => ['widgetOptions' => array('options' => array())],
							'htmlOptions'	 => array('required'		 => true, 'placeholder'	 => 'Pickup Time', 'class' => 'form-control border-radius')
							));
							?>
						</div>
						<span class="has-error"><? echo $form1->error($model, 'brt_pickup_date_date1'); ?></span>
						<span class="has-error"><? echo $form1->error($model, 'brt_pickup_date_time1'); ?></span>

						<div class="col-12 col-sm-4 col-lg-3">

					    <label>Return Date</label>
						<?=
						$form1->widget('zii.widgets.jui.CJuiDatePicker',array(
							'model'=>$brtModel,
							'attribute'=>'brt_return_date_date',
							'options'=> array('autoclose'=> true, 'startDate'=> $minDate,'format'=> 'dd/mm/yyyy'),
							'htmlOptions'=> array('required' => true, 'placeholder'	=> 'Return Date',
											'value'			 => DateTimeFormat::DateTimeToDatePicker($defaultRDate),
											'class'			 => 'form-control input-style')
							),true);
					     ?>
						</div>
						<div class="col-sm-4 col-md-6 hide col-lg-3">
							<label>Return Time</label>

						</div>

						<span class="has-error"><? echo $form1->error($brtModel, 'brt_pickup_date_date1'); ?></span>
						<span class="has-error"><? echo $form1->error($model, 'brt_pickup_date_time1'); ?></span>
						<div class="col-sm-12 col-lg-3 pb20 text-center">
							<button type="submit" class="btn btn-primary proceed-new-btn">proceed</button>
						</div>
					</div>
				</div>
<?php $this->endWidget(); ?>
			</div>
		</div>
	</div>
	<div class="tab-pane home-search mt10 mb5" id="menu6">
		<div id='multiform'>
			<?
			$form2			 = $this->beginWidget('CActiveForm', array(
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
			'action'				 => Yii::app()->createUrl('booking/booknow'),
			'htmlOptions'			 => array(
			'class' => 'form-horizontal',
			),
			));
			/* @var $form1 CActiveForm */
			?>
			<div class="row">
				<div class="col-12 col-sm-6 col-md-3">
<?= $form2->errorSummary($model); ?>
					<div id='bkt'>
<?= $form2->hiddenField($model, 'bkg_booking_type', ['value' => 3, 'id' => 'bkg_booking_type3']); ?>
					<?= $form2->hiddenField($model, 'bktyp', ['value' => 3, 'id' => 'bktyp3']); ?>
						<input type="hidden" id="step23" name="step2" value="2">
						<input type="hidden" id="step13" name="step" value="1">

					</div>
					<div class="input-group col-12">
						<label>Going From</label>
<?php
$this->widget('ext.yii-selectize.YiiSelectize', array(
	'model'				 => $brtModel,
	'attribute'			 => 'brt_from_city_id',
	'useWithBootstrap'	 => true,
	"placeholder"		 => "Going From",
	'fullWidth'			 => false,
	'htmlOptions'		 => array('width'	 => '50%', 'id'	 => 'bkg_from_city_id_1'
	),
	'defaultOptions'	 => $selectizeOptions + array(
'onInitialize'	 => "js:function(){
							populateSource(this, '{$model->bkg_from_city_id}');
						}",
 'load'			 => "js:function(query, callback){
							loadSource(query, callback);
						}",
 'onChange'		 => "js:function(value) {
							changeDestination(value, \$dest_city_1);
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
						<span class="has-error"><? echo $form->error($model, 'bkg_from_city_id'); ?></span>
					</div>
				</div>
				<div class="col-12 col-sm-6 col-md-3">
					<div class="input-group col-12">
						<label>Going To</label>
						<?
						$this->widget('ext.yii-selectize.YiiSelectize', array(
						'model'				 => $brtModel,
						'attribute'			 => 'brt_to_city_id',
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
						return '<div><span class=\"\"><i class=\"fa fa-map-marker mr5\"></i>' + escape(item.text) +'</span></div>';                          
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
				<div class="col-12 col-md-6">
					<div class="row">
						<div class="col-sm-6 col-md-4">
							<label>Start Date</label>
                        <?=
							$form2->widget('zii.widgets.jui.CJuiDatePicker',array(
								'model'=>$brtModel,
								'attribute'=>'brt_pickup_date_date',
								'options'=> array('autoclose'=> true, 'startDate'=> $minDate,'format'=> 'dd/mm/yyyy'),
								'htmlOptions'=> array('required' => true, 'placeholder'	=> 'Pickup Date',
												'value'			 => $pdate,
												'class'			 => 'form-control input-style')
							),true);
					    ?>
						</div>
						<div class="col-sm-6 col-md-4">
							<label>Start Time</label>

							<?
							$this->widget('ext.timepicker.TimePicker', array(
							'model'			 => $brtModel,
							'id'			 => 'brt_pickup_date_time_3' . date('mdhis'),
							'attribute'		 => 'brt_pickup_date_time',
							'options'		 => ['widgetOptions' => array('options' => array())],
							'htmlOptions'	 => array('required'		 => true, 'placeholder'	 => 'Pickup Time', 'class' => 'form-control border-radius')
							));
							?>
						</div>
						<span class="has-error"><? echo $form2->error($model, 'brt_pickup_date_date_1'); ?></span>
						<span class="has-error"><? echo $form2->error($model, 'brt_pickup_date_time_1'); ?></span>
						<div class="col-sm-12 col-md-4">
							<div class="input-group col-12 pb20  text-center">
								<button type="submit" class="btn btn-primary proceed-new-btn">Add more city</button>
							</div>
						</div>
					</div>
				</div>
			</div>
<?php $this->endWidget(); ?>
		</div>
	</div>
	<div class="tab-pane home-search mt10 mb5" id="menu7">
		<?
		/* @var $form CActiveForm|CWidget */
		$form3			 = $this->beginWidget('CActiveForm', array(
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
		'action'				 => Yii::app()->createUrl('booking/booknow'),
		'htmlOptions'			 => array(
		'class' => 'form-horizontal',
		),
		));
		/* @var $form CActiveForm */
		?>

<?= $form3->errorSummary($model); ?>
<?= $form3->hiddenField($model, 'bkg_booking_type', ['value' => 4, 'id' => 'bkg_booking_type4']); ?>
<?= $form3->hiddenField($brtModel, 'brttyp', ['value' => 4, 'id' => 'bktyp4']); ?>
		<?= $form3->hiddenField($brtModel, 'brt_from_city_id', ['id' => 'brt_from_city_id_tr']); ?>
		<?= $form3->hiddenField($brtModel, 'brt_to_city_id', ['id' => 'brt_to_city_id_tr']); ?>
		<input type="hidden" id="step14" name="step" value="1">
		<div class="row">

			<div class="col-12 col-sm-4 col-lg-2">
				Transfer Type<br>

				<div class="btn-group" data-toggle="buttons">
					<label class="btn btn-success btn-rounded active btn-md " id="bkg_transfer_type_0">
						<input placeholder="Transfer Type" id="BookingTemp_bkg_transfer_type_0" value="1" type="radio" name="BookingTemp[bkg_transfer_type]"  checked autocomplete="off"> Pick Up
					</label>

					<label class="btn btn-success btn-rounded btn-md" id="bkg_transfer_type_1">
						<input placeholder="Transfer Type" id="BookingTemp_bkg_transfer_type_1" value="2" type="radio" name="BookingTemp[bkg_transfer_type]" autocomplete="off"> Drop Off
					</label>
				</div>
			</div>
			<div class="col-12 col-sm-8 col-lg-4">
				<div class="row">
					<div class="col-12 col-sm-6 col-md-6">
				    <label>Journey Date</label>
                    
						<?
						$defaultDate	 = date('Y-m-d H:i:s', strtotime('+7 days'));
						$defaultRDate	 = date('Y-m-d H:i:s', strtotime('+8 days'));
						$minDate		 = date('Y-m-d H:i:s', strtotime('+4 hour'));
						$pdate			 = ($brtModel->brt_pickup_date_date == '') ? DateTimeFormat::DateTimeToDatePicker($defaultDate) : $model->bkg_pickup_date_date;
						?>
                   

					<div class="input-group"><div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                    <?=
							$form->widget('zii.widgets.jui.CJuiDatePicker',array(
								'model'=>$brtModel,
								'attribute'=>'brt_pickup_date_date',
								'options'=> array('autoclose'=> true, 'startDate'=> $minDate,'format'=> 'dd/mm/yyyy'),
								'htmlOptions'=> array('required' => true, 'placeholder'	=> 'Pickup Date',
												'value'			 => $pdate,
												'class'			 => 'form-control input-style')
							),true);
					?>
					</div>
					</div>
					<div class="col-12 col-sm-6 col-md-6">
						<label>Journey Time</label>
						<div class="input-group full-width">
							<?
							$this->widget('ext.timepicker.TimePicker', array(
							'model'			 => $brtModel,
							'id'			 => 'brt_pickup_date_time_4' . date('mdhis'),
							'attribute'		 => 'brt_pickup_date_time',
							'options'		 => ['widgetOptions' => array('options' => array())],
							'htmlOptions'	 => array('required'		 => true, 'placeholder'	 => 'Pickup Time', 'class' => 'form-control border-radius')
							));
							?>
						</div>
					</div>
				</div>
			</div>
			<div class="col-12 col-sm-12 col-lg-6" id="ttype" >
				<div class="row">
					<div class="col-12 col-sm-5 col-lg-5 pb10" id="s1">
						<label id="slabel">Airport</label>
<?php
$this->widget('ext.yii-selectize.YiiSelectize', array(
	'model'				 => $model,
	'attribute'			 => 'bkgAirport',
	'useWithBootstrap'	 => true,
	"placeholder"		 => "Select Airport",
	'fullWidth'			 => false,
	'htmlOptions'		 => array('width' => '50%'
	),
	'defaultOptions'	 => $selectizeOptions + array(
'onInitialize'	 => "js:function(){
							populateAirportList(this, '{$model->bkgAirport}');
						}",
 'load'			 => "js:function(query, callback){
							loadAirportSource(query, callback);
						}",
 'onChange'		 => "js:function(value) {
							changeTrDestination(value, \$destLocation);
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
						<span class="has-error"><? echo $form->error($brtModel, 'brt_from_city_id'); ?></span>
					</div>
					<div class="col-12 col-sm-5 col-lg-5 pb10" id="s2">
						<label id="dlabel">Destination</label>
						<?
						$this->widget('ext.yii-selectize.YiiSelectize', array(
						'model'				 => $model,
						'attribute'			 => 'bkgTransferLoc',
						'useWithBootstrap'	 => true,
						"placeholder"		 => "Select Location",
						'fullWidth'			 => false,
						'htmlOptions'		 => array('width' => '50%'
						),
						'defaultOptions'	 => $selectizeOptions + array(
						'onInitialize'	 => "js:function(){
						\$destLocation=this;
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
						<span class="has-error"><? echo $form->error($brtModel, 'brt_to_city_id'); ?></span>
						<span class="has-error"><? echo $form->error($brtModel, 'brt_pickup_date_date'); ?></span>
						<span class="has-error"><? echo $form->error($brtModel, 'brt_pickup_date_time'); ?></span>
					</div>
					<div class="col-12 col-sm-2 col-lg-2 pb20 pl0 text-center">
						<button type="submit" id="btnTransfer" class="btn btn-primary proceed-new-btn">proceed</button>
					</div>
				</div>
			</div>
		</div>
<?php $this->endWidget(); ?>
	</div>

	<div class="tab-pane home-search mt10 mb5 " id="menu8">
		<div class="col-12">
			<!--<a href="/packages" class="btn btn-primary">Go to Packages</a> -->
<?php
$form = $this->beginWidget('CActiveForm', array(
	'id'					 => 'driver-register-form', 'enableClientValidation' => FALSE,
	'action'				 => array('/packages'),
	'clientOptions'			 => array(
		'validateOnSubmit'	 => true,
		'errorCssClass'		 => 'has-error'
	),
	'enableAjaxValidation'	 => false,
	'errorMessageCssClass'	 => 'help-block',
	'htmlOptions'			 => array(
		'class'			 => 'form-horizontal', 'enctype'		 => 'multipart/form-data', 'autocomplete'	 => "off",
	),
		));
/* @var $form CActiveForm */

$selectizeOptions = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
?>

			<div class="col-3 mb20">Select City
			<?php
			$this->widget('ext.yii-selectize.YiiSelectize', array(
				'model'				 => $model,
				'attribute'			 => 'from_city',
				'useWithBootstrap'	 => true,
				"placeholder"		 => "Select City",
				'fullWidth'			 => false,
				'htmlOptions'		 => array('width' => '100%',
				//  'id' => 'from_city_id1'
				),
				'defaultOptions'	 => $selectizeOptions + array(
			'onInitialize'	 => "js:function(){
				  populateSourceCityPackage(this, '{$model->from_city}');
								}",
			'load'			 => "js:function(query, callback){
				loadSourceCityPackage(query, callback);
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
			<div class="col-5">
				<?
				$model->min_nights	 = 0;
				$model->max_nights	 = 10;
				?>
				<div class="col-6 pr0 mr0"><div class="col-12">Min No. of Nights</div><div class="col-6"><? echo $form->numberField($model, 'min_nights',  ['placeholder' => "", 'width' => '10px;', 'min' => 0,'class' => 'form-control m0']); ?></div></div>
				<div class="col-6 pl0 ml0"><div class="col-12">Max No. of Nights</div><div class="col-6"><? echo $form->numberField($model, 'max_nights', ['placeholder' => "", 'width' => '10px;', 'min' => 0,'class' => 'form-control m0']); ?></div></div>
			</div>
			<div class="col-2 pt5"><input type="submit" class="btn btn-primary proceed-new-btn" value="PROCEED"></div>

<?php $this->endWidget(); ?>
		</div>
	</div>
			<?php
			Filter::createLog("Form Render Completed: " . Filter::getExecutionTime());
			?>
</div>
	</div>
</div>
<script>


	$fromCity = '<?= $datacity ?>';
	var toCity = [];
	var toCity1 = [];
	var toCity2 = [];
	var toCity4 = [];
	var airportList = [];
	var trlocList = [];

	$destCity = null;
	
	$(document).ready(function ()
	{

		$("#bkg_pickup_date_time1").selectize();
		$("#bkg_pickup_date_time2").selectize();
		$("#bkg_pickup_date_time3").selectize();
		$("#bkg_pickup_date_time4").selectize();
		$("#bkg_pickup_date_time5").selectize();
		
		$('#<?= CHtml::activeId($model, "bkg_transfer_type_0") ?>').attr('checked', 'checked');
		trType0Chkd();
	});
	$sourceList = null;


	function loadSource(query, callback)
	{

		$.ajax({
			url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/citylist1')) ?>?q=' + encodeURIComponent(query),
			type: 'GET',
			dataType: 'json',
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
	
	function loadTime(query, callback)
	{

		//	if (!query.length) return callback();
		$.ajax({
			url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/timedrop')) ?>?q=' + encodeURIComponent(query),
			type: 'GET',
			dataType: 'json',
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

	function populateSource(obj, cityId)
	{
		obj.load(function (callback)
		{
			var obj = this;
			if ($sourceList == null)
			{
				xhr = $.ajax({
					url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/citylist1')) ?>',
					dataType: 'json',
					data: {
						city: cityId
					},
					//  async: false,
					success: function (results)
					{
						$sourceList = results;
						obj.enable();
						callback($sourceList);
						obj.setValue('<?= $model->bkg_from_city_id ?>');
					},
					error: function ()
					{
						callback();
					}
				});
			}
			else
			{
				obj.enable();
				callback($sourceList);
				obj.setValue('<?= $model->bkg_from_city_id ?>');
			}
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
					callback(results);
					obj.setValue(existingValue);
				},
				error: function ()
				{
					callback();
				}
			});
		});
	}

	function changeTrDestination(value, obj)
	{
		if (!value.length)
			return;
		var existingValue = obj.getValue();
		if (existingValue == '')
		{
			existingValue = '<?= $model->bkgTransferLoc ?>';
		}
		obj.disable();
		obj.clearOptions();
		obj.load(function (callback)
		{
			//  xhr && xhr.abort();
			xhr = $.ajax({
				url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('index/getairportnearest')) ?>/source/' + value,
				dataType: 'json',
				success: function (results)
				{
					obj.enable();
					callback(results);
					obj.setValue(existingValue);
				},
				error: function ()
				{
					callback();
				}
			});
		});
	}


	$('#bookingtimform1').submit(function (event)
	{

		fcity = $('#Booking_bkg_from_city_id').val();
		tcity = $('#Booking_bkg_to_city_id').val();
		// alert(tcity);
	});


	$('#<?= CHtml::activeId($model, "bkg_transfer_type_0") ?>').click(function ()
	{

		if ($('#<?= CHtml::activeId($model, "bkg_transfer_type_0") ?>').is(':checked'))
		{
			trType0Chkd();
		}
	});

	$('#<?= CHtml::activeId($model, "bkg_transfer_type_1") ?>').click(function ()
	{

		if ($('#<?= CHtml::activeId($model, "bkg_transfer_type_1") ?>').is(':checked'))
		{
			trType1Chkd()
		}
	});
	
	function validateTransfer()
	{
		var trType = 0;
		if ($('#<?= CHtml::activeId($model, "bkg_transfer_type_0") ?>').is(':checked'))
		{
			$('#bkg_from_city_id_tr').val($('#<?= CHtml::activeId($model, "brtAirport") ?>').selectize().val()).change();
			$('#bkg_to_city_id_tr').val($('#<?= CHtml::activeId($model, "brtTransferLoc") ?>').selectize().val()).change();
			trType = 1;
		}
		if ($('#<?= CHtml::activeId($model, "bkg_transfer_type_1") ?>').is(':checked'))
		{
			$('#bkg_to_city_id_tr').val($('#<?= CHtml::activeId($model, "brtAirport") ?>').selectize().val()).change();
			$('#bkg_from_city_id_tr').val($('#<?= CHtml::activeId($model, "brtTransferLoc") ?>').selectize().val()).change();
			trType = 2;
		}
		var strCityErr = '';
		if (trType == 0)
		{
			alert('error');
		}

		if (trType == 1)
		{
			if ($('#bkg_from_city_id_tr').val() == '')
			{
				strCityErr += "Pickup Airport must be selected before proceed.";
			}
			if ($('#bkg_to_city_id_tr').val() == '')
			{
				strCityErr += "\nDestination location must be selected before proceed.";
			}

		}
		
	}


	$('#btnTransfer').click(function (event)
	{
		if (!validateTransfer())
		{
			event.preventDefault();
		}
	});

	function populateSourceCityPackage(obj, cityId)
	{
		
		obj.load(function (callback)
		{
			var obj = this;
			if ($sourceList22 == null)
			{
				xhr = $.ajax({
					url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/citylistpackage1', ['apshow' => 1, 'city' => ''])) ?>' + cityId,
					dataType: 'json',
					data: {
						// city: cityId
					},
					//  async: false,
					success: function (results)
					{
						$sourceList22 = results;
						obj.enable();
						callback($sourceList22);
						obj.setValue(cityId);
					},
					error: function ()
					{
						callback();
					}
				});
			}
			else
			{
				obj.enable();
				callback($sourceList22);
				obj.setValue(cityId);
			}
		});
	}
	function loadSourceCityPackage(query, callback)
	{
		$.ajax({
			url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/citylistpackage1')) ?>?apshow=1&q=' + encodeURIComponent(query),
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
</script>