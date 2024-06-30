<style type="text/css">
    .yii-selectize ,.selectize-input  {
        min-width: 100px!important;   
    }
</style>

<?
$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
?>
<div class="panel-advancedoptions" >

    <div class="row">
		<div class="col-xs-12">
			<?php
			$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
				'id'					 => 'pricesurgesearch-form', 'enableClientValidation' => true,
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
					'class' => '',
				),
			));
			/* @var $form TbActiveForm */
			?>
			<div class="row">
				<div class="col-xs-12 col-sm-4  col-lg-2">
					<?=
					$form->datePickerGroup($model, 'prc_from_date', array('label'			 => 'Surge Date',
						'widgetOptions'	 => array('options'		 => array('autoclose'	 => true,
								'startDate'	 => date(),
								'format'	 => 'dd/mm/yyyy'),
							'htmlOptions'	 => array('placeholder' => 'Surge Date')),
						'prepend'		 => '<i class="fa fa-calendar"></i>'));
					?>
				</div>
				<div class="col-xs-6 col-sm-4 col-lg-2">
					<div class="form-group cityinput">
						<label class="control-label">Source City</label>
						<?php
						$this->widget('ext.yii-selectize.YiiSelectize', array(
							'model'				 => $model,
							'attribute'			 => 'prc_source_city',
							'useWithBootstrap'	 => true,
							"placeholder"		 => "Select Source City",
							'fullWidth'			 => false,
							'htmlOptions'		 => array('width' => '100%'
							),
							'defaultOptions'	 => $selectizeOptions + array(
						'onInitialize'	 => "js:function(){
				populateSourceCity(this, '{$model->prc_source_city}');
					}",
						'load'			 => "js:function(query, callback){
				loadSourceCity(query, callback);
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
					</div></div>
				<div class="col-xs-6 col-sm-4 col-lg-2">
					<div class="form-group cityinput">
						<label class="control-label  ">Destination City</label>
						<?php
						$this->widget('ext.yii-selectize.YiiSelectize', array(
							'model'				 => $model,
							'attribute'			 => 'prc_destination_city',
							'useWithBootstrap'	 => true,
							"placeholder"		 => "Select Destination City",
							'fullWidth'			 => false,
							'htmlOptions'		 => array('width' => '100%'),
							'defaultOptions'	 => $selectizeOptions + array(
						'onInitialize'	 => "js:function(){
				populateSourceCity(this, '{$model->prc_destination_city}');
					}",
						'load'			 => "js:function(query, callback){
				loadSourceCity(query, callback);
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
					</div> </div>
					
				<div class="col-xs-12 col-sm-2">
					<div class="form-group  ">
						<label>Source Zone (Except DDBP)</label>
						<?php
						$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
							'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
							'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
							'openOnFocus'		 => true, 'preload'			 => false,
							'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
							'addPrecedence'		 => false,];
						$this->widget('ext.yii-selectize.YiiSelectize', array(
							'model'				 => $model,
							'attribute'			 => 'prc_source_zone',
							'useWithBootstrap'	 => true,
							"placeholder"		 => "Source Zone",
							'fullWidth'			 => false,
							'options'			 => array('allowClear' => true),
							'htmlOptions'		 => array('width' => '100%', 'id' => 'prc_source_zone_id1'),
							'defaultOptions'	 => $selectizeOptions + array(
						'onInitialize'	 => "js:function(){
										populateZone(this, '{$model->prc_source_zone}');
											}",
						'load'			 => "js:function(query, callback){
										loadZone(query, callback);
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
				</div>
				<div class="col-xs-6 col-sm-4 col-lg-2">
					<div class="form-group  ">
						<label class="control-label">Destination Zone (Except DDBP)</label>
						<?php
						$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
							'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
							'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
							'openOnFocus'		 => true, 'preload'			 => false,
							'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
							'addPrecedence'		 => false,];
						$this->widget('ext.yii-selectize.YiiSelectize', array(
							'model'				 => $model,
							'attribute'			 => 'prc_destination_zone',
							'useWithBootstrap'	 => true,
							"placeholder"		 => "Destination Zone",
							'fullWidth'			 => false,
							'options'			 => array('allowClear' => true),
							'htmlOptions'		 => array('width' => '100%', 'id' => 'prc_destination_zone1'),
							'defaultOptions'	 => $selectizeOptions + array(
						'onInitialize'	 => "js:function(){
										populateZone(this, '{$model->prc_destination_zone}');
											}",
						'load'			 => "js:function(query, callback){
										loadZone(query, callback);
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
					</div> </div>
				<div class="col-xs-6 col-sm-4 col-lg-2">
					<div class="form-group  ">
						<label class="control-label">Source State</label>
						<?php
						$loc				 = States::model()->getStateList();
						$SubgroupArray		 = CHtml::listData(States::model()->getStateList(), 'stt_id', function ($loc) {
									return $loc->stt_name;
								});
						$this->widget('booster.widgets.TbSelect2', array(
							'attribute'		 => 'prc_source_state',
							'model'			 => $model,
							'data'			 => $SubgroupArray,
							'value'			 => $model->prc_source_state,
							'options'		 => array('allowClear' => true),
							'htmlOptions'	 => array(
								'id'			 => 'prc_source_state_id1',
								'placeholder'	 => 'Source State',
								'width'			 => '100%',
								'style'			 => 'width:100%;',
							),
						));
						?>
					</div></div>

			</div>

			<div class="row">
				<div class="col-xs-6 col-sm-4 col-lg-2">
					<div class="form-group  ">
						<label class="control-label">Destination State</label>
						<?php
						$this->widget('booster.widgets.TbSelect2', array(
							'attribute'		 => 'prc_destination_state',
							'model'			 => $model,
							'data'			 => $SubgroupArray,
							'value'			 => $model->prc_destination_state,
							'options'		 => array('allowClear' => true),
							'htmlOptions'	 => array(
								'id'			 => 'prc_destination_state1',
								'placeholder'	 => 'Destination State',
								'width'			 => '100%',
								'style'			 => 'width:100%;',
							),
						));
						?>
					</div> </div>
				<div class="col-xs-6 col-sm-4  col-lg-2">
					<div class="form-group cityinput">
						<label class="control-label  ">Availability</label>
						<?php
						$filters	 = [
							0	 => 'No',
							1	 => 'Yes',
						];
						$dataPay	 = Filter::getJSON($filters);
						$this->widget('booster.widgets.TbSelect2', array(
							'model'			 => $model,
							'attribute'		 => 'prc_is_available',
							'val'			 => $model->prc_is_available,
							'asDropDownList' => FALSE,
							'options'		 => array('data' => new CJavaScriptExpression($dataPay), 'allowClear' => true),
							'htmlOptions'	 => array('class' => 'p0', 'style' => 'width:100%', 'placeholder' => 'Select Availability')
						));
						?>
					</div> </div>
				<div class="col-xs-12 col-sm-4 col-lg-2">
					<div class="form-group  ">
						<label>Car Type</label>
						<?php
						$returnType	 = "";
						$vehcleList	 = SvcClassVhcCat::getVctSvcList($returnType);
						$this->widget('booster.widgets.TbSelect2', array(
							'model'			 => $model,
							'attribute'		 => 'prc_vehicle_type',
							'val'			 => $model->prc_vehicle_type,
							'data'			 => $vehcleList,
							'options'		 => array('allowClear' => true),
							'htmlOptions'	 => array('style' => 'width:100%', 'width' => '100%', 'placeholder' => 'Select Car Type')
						));
						?>
					</div></div>
				<div class="col-xs-12 col-sm-4 col-lg-2">
					<div class="form-group  ">
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
					</div></div>
				<div class="col-xs-6 col-sm-4  col-lg-2">
					<div class="form-group cityinput">
						<label class="control-label  ">Override</label>
						<?php
						$filters	 = [
							1	 => 'Override DZ',
							2	 => 'Override DE',
							3	 => 'Override DDv2',
							4	 => 'Override DDv1',
							5	 => 'Override Profitability Surge',
							6	 => 'Override DDSBP',
						];
						$dataPay	 = Filter::getJSON($filters);
						$this->widget('booster.widgets.TbSelect2', array(
							'model'			 => $model,
							'attribute'		 => 'overrideType',
							'val'			 => $model->overrideType,
							'asDropDownList' => FALSE,
							'options'		 => array('data' => new CJavaScriptExpression($dataPay), 'allowClear' => true),
							'htmlOptions'	 => array('class' => 'p0', 'style' => 'width:100%', 'placeholder' => 'Select Override')
						));
						?>
					</div> </div>
				<div class="col-xs-6 col-sm-4  col-lg-2">
					<div class="form-group cityinput">
						<label class="control-label  ">Surge Reason</label>
						<?php
						$surgeReason	 = PriceSurge::getSurgeReason();
						$this->widget('booster.widgets.TbSelect2', array(
							'model'			 => $model,
							'attribute'		 => 'prc_surge_reason',
							'val'			 => $model->prc_surge_reason,
							'data'			 => $surgeReason,
							'options'		 => array('allowClear' => true),
							'htmlOptions'	 => array('style' => 'width:100%', 'width' => '100%', 'placeholder' => 'Select Surge Reason')
						));
						?>
					</div> </div>
				<div class="col-xs-6 col-sm-4 col-lg-2 mt20">
					<?php echo $form->checkboxListGroup($model, 'isGozoNow', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Is Gozonow'), 'htmlOptions' => []))) ?>
				</div>
			</div>
			<div class="">
				<div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-2 col-md-2 text-center mt20">   
					<?php echo CHtml::submitButton('Search', array('class' => 'btn btn-primary full-width')); ?></div>
				<div class="col-xs-6 col-sm-4  col-lg-1 mt20">
					<a href="<?= Yii::app()->createUrl('admin/pricesurge/surgeform') ?>"><div class="btn btn-info"><i class="fa fa-plus"></i> Add</div></a>
				</div>
			</div>
			<?php $this->endWidget(); ?>	
			<div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-2 col-md-1 text-center">   
					<?php
					echo CHtml::beginForm(Yii::app()->createUrl('aaohome/pricesurge/list'), "post", ['style' => "margin-top: 24px;"]);
					?>
					<input type="hidden" id="prc_from_date" name="prc_from_date" value="<?= $model->prc_from_date ?>"/>
					<input type="hidden" id="prc_source_city" name="prc_source_city" value="<?= $model->prc_source_city ?>"/>
					<input type="hidden" id="prc_destination_city" name="prc_destination_city" value="<?= $model->prc_destination_city ?>"/>
					<input type="hidden" id="prc_source_zone" name="prc_source_zone" value="<?= $model->prc_source_zone ?>"/>
					<input type="hidden" id="prc_destination_zone" name="prc_destination_zone" value="<?= $model->prc_destination_zone ?>"/>
					<input type="hidden" id="prc_source_state" name="prc_source_state" value="<?= $model->prc_source_state ?>"/>
					<input type="hidden" id="prc_destination_state" name="prc_destination_state" value="<?= $model->prc_destination_state ?>"/>
					<input type="hidden" id="prc_is_available" name="prc_is_available" value="<?= $model->prc_is_available ?>"/>
					<input type="hidden" id="prc_vehicle_type" name="prc_vehicle_type" value="<?= $model->prc_vehicle_type ?>"/>
					<input type="hidden" id="prc_trip_type" name="prc_trip_type" value="<?= $model->prc_trip_type ?>"/>
					<input type="hidden" id="overrideType" name="overrideType" value="<?= $model->overrideType ?>"/>
					<input type="hidden" id="prc_surge_reason" name="prc_surge_reason" value="<?= $model->prc_surge_reason ?>"/>
					<input type="hidden" id="isGozoNow" name="isGozoNow" value="<?= $model->isGozoNow ?>"/>
					<input type="hidden" id="export" name="export" value="true"/>
					<button class="btn btn-default" type="submit" style="width: 185px;">Export</button>
					<?php echo CHtml::endForm(); ?>	
				</div>
		</div>
	</div>
	
	

    <div class="row">
        <div class="col-md-12">
            <div class="panel" >
                <div class="panel-body panel-no-padding p0 pt10">
                    <div class="panel-scroll1">
                        <div style="width: 100%; overflow: auto;  border: 1px #aaa solid;color: #444;">
							<?php
							if (!empty($dataProvider))
							{
								$arr = [];
								if (is_array($dataProvider->getPagination()->params))
								{
									$arr = $dataProvider->getPagination()->params;
								}
								$params1							 = $arr + array_filter($_GET + $_POST);
								/* @var $dataProvider CActiveDataProvider */
								$dataProvider->pagination->pageSize	 = 50;
								$this->widget('booster.widgets.TbGridView', array(
									'id'				 => 'pricesurgelist',
									'ajaxUrl'			 => CHtml::normalizeUrl(Yii::app()->createUrl('admin/pricesurge/list', $params1)),
									'responsiveTable'	 => true,
									'dataProvider'		 => $dataProvider,
									'filter'			 => $model,
									'template'			 => "<div class='panel-heading'><div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
									'itemsCssClass'		 => 'table table-striped table-bordered mb0',
									'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
									'columns'			 => array(
										array('name' => 'prc_id', 'filter' => false, 'value' => '$data[prc_id]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Price Rule Id'),
										array('name' => 'prc_from_date', 'filter' => false, 'value' => 'date("d/m/Y h:i:s",strtotime($data[prc_from_date]))', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'From Date'),
										array('name' => 'prc_to_date', 'filter' => false, 'value' => 'date("d/m/Y h:i:s",strtotime($data[prc_to_date]))', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'To Date'),
										array('name' => 'prc_value', 'filter' => false, 'value' => '$data[prc_value]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Value'),
										array('name' => 'prc_value_type', 'filter' => false, 'value' => '($data[prc_value_type]==1)?"Amount":"Percentage"', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Value Type'),
										array('name' => 'prc_source_city', 'filter' => false, 'value' => '$data[source_cty_name]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Source City'),
										array('name' => 'prc_destination_city', 'filter' => false, 'value' => '$data[dest_cty_name]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Destination City'),
										array('name'	 => 'prc_source_zone', 'filter' => false, 'value'	 => function ($data) use ($arrZones) {
												return Zones::getNamesByIds($arrZones, $data['prc_source_zone']);
											}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions'		 => array('style' => 'text-align: center;word-break: break-all;'), 'header'			 => 'Source Zone'),
										array('name'	 => 'prc_destination_zone', 'filter' => false, 'value'	 => function ($data) use ($arrZones) {
												return Zones::getNamesByIds($arrZones, $data['prc_destination_zone']);
											}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions'		 => array('style' => 'text-align: center;word-break: break-all;'), 'header'			 => 'Destination Zone'),
										array('name'	 => 'prc_vehicle_type', 'filter' => false, 'value'	 => function ($data) {
												if ($data["prc_vehicle_type"] > 0)
												{
													return SvcClassVhcCat::getVctSvcList("string", 0, 0, $data["prc_vehicle_type"]);
												}
												else
												{
													return "";
												}
											}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions'		 => array('style' => 'text-align: center;'), 'header'			 => 'Vehicle Type'),
										array('name'	 => 'prc_trip_type', 'filter' => false, 'value'	 => function ($data) {
												if ($data["prc_trip_type"] > 0)
												{
													return Booking::model()->getBookingType($data["prc_trip_type"]);
												}
												else
												{
													return "";
												}
											},
											'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions'		 => array('style' => 'text-align: center;'), 'header'			 => 'Trip Type'),
										array('name'	 => 'prc_region', 'filter' => false, 'value'	 => function ($data) {
												if ($data["prc_region"] > 0)
												{
													return States::model()->findRegionName($data["prc_region"]);
												}
												else
												{
													return "";
												}
											},
											'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions'		 => array('style' => 'text-align: center;'), 'header'			 => 'Region'),
										array('name' => 'prc_source_state', 'filter' => false, 'value' => '$data[source_stt_name]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Source State'),
										array('name' => 'prc_destination_state', 'filter' => false, 'value' => '$data[dest_stt_name]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Destination State'),
										array('name' => 'prc_surge_reason', 'filter' => false, 'value' => function ($data) {
												if ($data["prc_surge_reason"] > 0)
												{
													return PriceSurge::getSurgeReason($data["prc_surge_reason"]);
												}
												else
												{
													return "";
												}
											},
                                            'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Surge Reason'),
										array('name' => 'prc_is_available', 'filter' => false, 'value' => '($data[prc_is_available]==0)?"No":"Yes"', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Availabile'),
										array('name' => 'prc_priority_score', 'filter' => false, 'value' => '$data[prc_priority_score]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Priority Score'),
										array('name' => 'prc_desc', 'value' => '$data[prc_desc]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Description'),
										array(
											'header'			 => 'Action',
											'class'				 => 'CButtonColumn',
											'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
											'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
											'template'			 => '{edit}{showLog}{delete}',
											'buttons'			 => array(
												'edit'			 => array(
													'url'		 => 'Yii::app()->createUrl("admin/pricesurge/surgeform", array(\'id\' => $data[prc_id]))',
													'imageUrl'	 => Yii::app()->request->baseUrl . '/images/icon/city/edit_booking.png',
													'options'	 => array('style' => 'margin-right: 4px', 'class' => 'surgeedit p0', 'title' => 'Edit','target' => '_blank'),
												),
												'showLog'		 => array(
													'url'		 => 'Yii::app()->createUrl("admin/pricesurge/showlog",  array(\'id\' => $data[prc_id]))',
													'imageUrl'	 => Yii::app()->request->baseUrl . '/images/icon/show_log.png',
													'label'		 => '<i class="fa fa-check"></i>',
													'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'onclick' => 'return openModal(this,"Show Log")', 'data-placement' => 'left', 'class' => 'showlog p0', 'title' => 'Show Log'),
												),
//                                                'delete' => array(
//                                                    'url' => 'Yii::app()->createUrl("admin/pricesurge/delete1", array(\'id\' => $data->prc_id))',
//                                                    'imageUrl' => Yii::app()->request->baseUrl . '/images/icon/delete_booking.png',
//                                                    'options' => array('style' => 'margin-right: 4px', 'class' => 'btn btn-xs surgedelete p0', 'title' => 'Delete'),
//                                                ),
												'delete'		 => array(
													'click'		 => 'function(){
                                                            $href = $(this).attr(\'href\');
                                                            jQuery.ajax({type: \'GET\',
                                                            url: $href,
                                                            "dataType": "json",
                                                            success: function (data1){
                                                                if (data1.success) {
                                                                    refreshApprovalList();
                                                                    return false;
                                                                } else {
                                                                    alert(data1.errors);
                                                                }
                                                            }
                                                        });
                                                        return false;
                                                        }',
													'url'		 => 'Yii::app()->createUrl("admin/pricesurge/delete1", array(\'id\' => $data[prc_id]))',
													'imageUrl'	 => Yii::app()->request->baseUrl . '/images/icon/delete_booking.png',
													'label'		 => '<i class="fa fa-refresh"></i>',
													'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'class' => 'surgedelete p0', 'title' => 'Delete'),
												),
												'htmlOptions'	 => array('class' => 'center'),
											))
								)));
							}
							?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

		<div class="col-md-12">            

            <div class="panel" >

                <div class="panel-body panel-no-padding p0 pt10">
                    <div class="panel-scroll1">
                        <div style="width: 100%; overflow: auto;  border: 1px #aaa solid; color: #444;">

							<div  style=" font-size: 14px;font-weight: 600;; padding:12px; overflow: auto;  background: #7a6fbe; color:#fff" > DDBP Price Surge List </div>
							<table class="table table-bordered mb0">
								<?php
								if ($goldenMarkup != '')
								{
									?>
									<tr >

										<th>Date</th>
										<th>Route-Route</th>
										<th>Zone-Zone</th>
										<th>Zone-State</th>
										<th>Final-Factor</th>

									</tr>

									<tr>
										<td><?= $pickupdate ?></td>
										<td><?= $dynamicSurge->dprRoutes->factor ?></td> 
										<td><?= $dynamicSurge->dprZoneRoutes->factor ?></td> 
										<td><?= $dynamicSurge->dprZonesStates->factor ?></td> 
										<td><?= $dynamicSurge->dprApplied->factor ?></td> 

									</tr>
									<?php
								}
								else
								{
									?>
									<td> No results found.</td>

								<?php }
								?>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-md-12">            

            <div class="panel" >

                <div class="panel-body panel-no-padding p0 pt10">
                    <div class="panel-scroll1">
                        <div style="width: 100%; overflow: auto;  border: 1px #aaa solid; color: #444;">
							<div  style=" font-size: 14px;font-weight: 600; ; padding:12px;  background: #7a6fbe; color:#fff" >  DTBP Price Surge List </div>
							<table class="table table-bordered mb0">
								<?php
								if ($goldenMarkup != '')
								{
									?>
									<tr >

										<th>Date</th>
										<th>Area from</th>
										<th>Area to</th>
										<th>Markup Value</th>
										<th>Trip Type</th>

									</tr>

									<tr>
										<td><?= $pickupdate ?></td>
										<td><?= $goldenMarkup['fromCity'] ?></td> 
										<td><?= $goldenMarkup['toCity'] ?></td> 
										<td><?= $goldenMarkup['glm_markup_value'] ?></td> 
										<td><?= $goldenMarkup['glm_trip_type'] ?></td> 

									</tr>
									<?php
								}
								else
								{
									?>
									<td> No results found.</td>

								<?php }
								?>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-md-12">            

            <div class="panel" >

                <div class="panel-body panel-no-padding p0 pt10">
                    <div class="panel-scroll1">
                        <div style="width: 100%; overflow: auto;  border: 1px #aaa solid; color: #444;">
							<div  style=" font-size: 14px;font-weight: 600; ; padding:12px;  background: #7a6fbe; color:#fff" >  Profitability Surge List </div>
							<table class="table table-bordered mb0">
								<?php
								if ($profitability != '')
								{
									?>
									<tr >

										<th>Date</th>
										<th>Area from</th>
										<th>Area to</th>
										<th>Markup Value</th>
										<th>Trip Type</th>

									</tr>

									<tr>
										<td><?= $pickupdate ?></td>
										<td><?= $profitability['fromCity'] ?></td> 
										<td><?= $profitability['toCity'] ?></td> 
										<td><?= $profitability['prs_surge'] ?></td> 
										<td><?= $profitability['prs_booking_type'] ?></td> 

									</tr>
									<?php
								}
								else
								{
									?>
									<td> No results found.</td>

								<?php }
								?>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>



    </div>


</div>


<script type="text/javascript">
	function refreshApprovalList() {
		$('#pricesurgelist').yiiGridView('update');
	}
	function openModal(obj, title)
	{
		try
		{
			$href = $(obj).attr("href");
			jQuery.ajax({type: "GET", "dataType": "html", url: $href, success: function (data)
				{
					bootbox.dialog({
						message: data,
						title: title,
						size: 'large',
						callback: function () {
						},
					});
				}});
		} catch (e)
		{
			alert(e);
		}
		return false;
	}

</script>