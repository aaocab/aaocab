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

        <div class="col-xs-12 col-sm-6   col-md-3">
			<?=
			$form->datePickerGroup($model, 'cpm_from_date', array('label'			 => 'Date',
				'widgetOptions'	 => array('options'		 => array('autoclose'	 => true,
						'startDate'	 => date(),
						'format'	 => 'dd/mm/yyyy'),
					'htmlOptions'	 => array('placeholder' => 'From Date')),
				'prepend'		 => '<i class="fa fa-calendar"></i>'));
			?></div>
        <div class="col-xs-12 col-sm-6 col-md-3">
            <div class="form-group cityinput"> 
				<?php // echo $form->drop($model,'cpm_vehicle_type'); ?>
                <label>Channel Partner</label>
				<?php
//		$agtList	 = Agents::model()->getAgentList();
//		$this->widget('booster.widgets.TbSelect2', array(
//		    'model'		 => $model,
//		    'attribute'	 => 'cpm_agent_id',
//		    'val'		 => $model->cpm_agent_id,
//		    'data'		 => $agtList,
//		    'htmlOptions'	 => array('style'		 => 'width:100%', 'width'		 => '100%',
//			'placeholder'	 => 'Select Channel Partner')
//		));
				$this->widget('ext.yii-selectize.YiiSelectize', array(
					'model'				 => $model,
					'attribute'			 => 'cpm_agent_id',
					'useWithBootstrap'	 => true,
					"placeholder"		 => "Select Channel Partner",
					'fullWidth'			 => false,
					'htmlOptions'		 => array('width' => '100%'),
					'defaultOptions'	 => $selectizeOptions + array(
				'onInitialize'	 => "js:function(){
                                  populatePartner(this, '{$model->cpm_agent_id}');
                                }",
				'load'			 => "js:function(query, callback){
                                loadPartner(query, callback);
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
				<?= $form->error($model, 'cpm_agent_id'); ?>
            </div>
        </div>
        <div class="col-xs-12 col-md-6">
            <div class="row">
                <div class="col-xs-12 col-sm-6 ">
                    <div class="form-group"> 
                        <label class="control-label">Source Zone</label>
						<?php
						$this->widget('ext.yii-selectize.YiiSelectize', array(
							'model'				 => $model,
							'attribute'			 => 'cpm_source_zone',
							'useWithBootstrap'	 => true,
							"placeholder"		 => "Source Zone",
							'fullWidth'			 => false,
							'options'			 => array('allowClear' => true),
							'htmlOptions'		 => array('width' => '100%'),
							'defaultOptions'	 => $selectizeOptions + array(
						'onInitialize'	 => "js:function(){
													populateZone(this, '{$model->cpm_source_zone}');
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
                <div class="col-xs-12 col-sm-6">
                    <div class="form-group"> 
                        <label class="control-label">Destination Zone</label>
						<?php
						$this->widget('ext.yii-selectize.YiiSelectize', array(
							'model'				 => $model,
							'attribute'			 => 'cpm_destination_zone',
							'useWithBootstrap'	 => true,
							"placeholder"		 => "Destination Zone",
							'fullWidth'			 => false,
							'options'			 => array('allowClear' => true),
							'htmlOptions'		 => array('width' => '100%'),
							'defaultOptions'	 => $selectizeOptions + array(
						'onInitialize'	 => "js:function(){
													populateZone(this, '{$model->cpm_destination_zone}');
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
            </div>
        </div>
        <div class="col-xs-offset-3 col-sm-offset-4 col-md-offset-5 col-xs-6 col-sm-4 col-md-2 text-center mt10">   
			<?php echo CHtml::submitButton('Search', array('class' => 'btn btn-primary full-width')); ?>
        </div>
		<?php $this->endWidget(); ?>

    </div>
    <div class="text-left mt30 n" style="height: 0 "> <a href="<?= Yii::app()->createUrl('admin/agent/markupadd') ?>" class="btn btn-info pt5 pb5"><i class="fa fa-plus"></i> Add </a></div>

    <div class="row">

        <div class="col-md-12">
            <div class="panel" >
                <div class="panel-body panel-no-padding p0 pt10">
                    <div class="panel-scroll1">
                        <div style="width: 100%; overflow: auto;  border: 1px #aaa solid;color: #444;">
							<?php
							if (!empty($dataProvider))
							{
								$this->widget('booster.widgets.TbGridView', array(
									'id'				 => 'pricesurge-list',
									'responsiveTable'	 => true,
									'dataProvider'		 => $dataProvider,
									//  'filter' => $model,
									'template'			 => "<div class='panel-heading'><div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
									'itemsCssClass'		 => 'table table-striped table-bordered mb0',
									'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
									'columns'			 => array(
										array('name' => 'cpm_from_date', 'filter' => false, 'value' => 'date("d/m/Y H:i",strtotime($data->cpm_from_date))', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'From Date'),
										array('name' => 'cpm_to_date', 'filter' => false, 'value' => 'date("d/m/Y H:i",strtotime($data->cpm_to_date))', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'To Date'),
										array('name' => 'cpm_agent', 'filter' => false, 'value' => '$data->cpmAgent->agt_company." (". $data->cpmAgent->agt_fname." ".$data->cpmAgent->agt_lname.")"', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Channel Partner'),
										array('name' => 'cpm_value', 'filter' => false, 'value' => '$data->cpm_value', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Value'),
										array('name'	 => 'cpm_value_type', 'filter' => false,
											'value'	 => function ($data) {
												echo $data->getValueType();
											},
											'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions'		 => array('style' => 'text-align: center;'), 'header'			 => 'Value Type'),
										array('name' => 'cpm_apply_surge', 'filter' => false, 'value' => '($data->cpm_apply_surge==1)?"Apply":"Not Apply"', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Surge'),
										array('name' => 'cpm_source_city', 'filter' => false, 'value' => '$data->cpmSourceCity->cty_name', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Source City'),
										array('name' => 'cpm_destination_city', 'filter' => false, 'value' => '$data->cpmDestCity->cty_name', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Destination City'),
										array('name' => 'cpm_source_zone', 'filter' => false, 'value' => '$data->cpmSourceZone->zon_name', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Source Zone'),
										array('name' => 'cpm_destination_zone', 'filter' => false, 'value' => '$data->cpmDestZone->zon_name', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Destination Zone'),
										array('name'	 => 'cpm_vehicle_type', 'filter' => false, 'value'	 => function ($data) {
												echo ($data->cpm_vehicle_type != '') ? SvcClassVhcCat::model()->getVctSvcList('string', '', $data->cpm_vehicle_type) : '-';
											}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions'		 => array('style' => 'text-align: center;'), 'header'			 => 'Vehicle Type'),
										array('name'	 => 'cpm_trip_type', 'filter' => false, 'value'	 => function ($data) {
												echo ($data->cpm_trip_type != '') ? Booking::model()->getBookingType($data->cpm_trip_type) : '-';
											}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions'		 => array('style' => 'text-align: center;'), 'header'			 => 'Trip Type'),
										array('name' => 'cpm_desc', 'value' => '$data->cpm_desc', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Description'),
										array(
											'header'			 => 'Action',
											'class'				 => 'CButtonColumn',
											'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => ''),
											'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
											'template'			 => '{edit}{delete}{log}',
											'buttons'			 => array(
												'edit'			 => array(
													'url'		 => 'Yii::app()->createUrl("admin/agent/markupadd", array(\'id\' => $data->cpm_id))',
													'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\city\edit_booking.png',
													'options'	 => array('style' => 'margin-right: 4px', 'class' => 'btn btn-xs cpmarkup1 p0', 'title' => 'Edit'),
												),
												'delete'		 => array(
													'url'		 => 'Yii::app()->createUrl("admin/agent/markupdelete", array(\'id\' => $data->cpm_id))',
													'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\delete_booking.png',
													'options'	 => array('style' => 'margin-right: 4px', 'class' => 'btn btn-xs cpmarkup2 p0', 'title' => 'Delete'),
												),
												'log'			 => array(
													'click'		 => 'function(){
                                                                    $href = $(this).attr(\'href\');
                                                                    jQuery.ajax({type: \'GET\',
                                                                    url: $href,
                                                                    success: function (data)
                                                                    {

                                                                        var box = bootbox.dialog({
                                                                            message: data,
                                                                            title: \'Channel Partner Markup Log\',
                                                                            onEscape: function () {

                                                                                // user pressed escape
                                                                            }
                                                                        });
                                                                    }
                                                                });
                                                                    return false;
                                                                    }',
													'url'		 => 'Yii::app()->createUrl("admin/agent/markuplog", array(\'id\' => $data[cpm_id]))',
													'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\rate_list\show_log.png',
													'label'		 => '<i class="fa fa-list"></i>',
													'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'class' => 'btn btn-xs conshowlog p0', 'title' => 'Show Log'),
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
    </div>
</div>