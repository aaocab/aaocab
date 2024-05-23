<style>
    .panel-body .btn:not(.btn-block){
        margin-bottom:  0
    }
</style>

<?php
$pageno				 = filter_input(INPUT_GET, 'page');
?>
<div class="row">
    <div class="panel">
        <div class="panel-heading">Pending Vehicles to approve</div>
        <div class="panel-body">
            <div class="col-xs-12">
				<?php
				$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
					'id'					 => 'vehicletype-form', 'enableClientValidation' => true,
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
                <div class="form-group row">
                    <div class="col-xs-6 col-sm-3">
						<?
						$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
							'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
							'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
							'openOnFocus'		 => true, 'preload'			 => false,
							'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
							'addPrecedence'		 => false,];
						$this->widget('ext.yii-selectize.YiiSelectize', array(
							'model'				 => $model,
							'attribute'			 => 'vhc_vendor_id',
							'useWithBootstrap'	 => true,
							"placeholder"		 => "Select Vendor",
							'fullWidth'			 => false,
							'htmlOptions'		 => array('width' => '100%'),
							'defaultOptions'	 => $selectizeOptions + array(
						'onInitialize'	 => "js:function(){
                                              populateVendor(this, '{$model->vhc_vendor_id}');
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

                    </div>
                    <div class="col-xs-6 col-sm-3">
						<?= $form->textFieldGroup($model, 'vhcnumber', array('label' => '', 'widgetOptions' => array())) ?>
                    </div>

                    <div class="col-xs-6 col-sm-4 hide">
                    </div>

                    <div class="col-xs-6 col-sm-4">
                        <button class="btn btn-primary" type="submit" style="width: 185px;"  name="bookingSearch">Search</button>
                    </div>
                </div>
				<?php $this->endWidget(); ?>
            </div>

            <div class="col-xs-12">
				<?php
				if (!empty($dataProvider))
				{
					$this->widget('booster.widgets.TbGridView', array(
						'responsiveTable'	 => true,
						'filter'			 => $model,
						'dataProvider'		 => $dataProvider,
						'id'				 => 'vehicleListGrid',
						'template'			 => "<div class='panel-heading'><div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
						'itemsCssClass'		 => 'table table-striped table-bordered mb0',
						'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
						//    'ajaxType' => 'POST',
						'columns'			 => array(
							array('name'				 => 'vhcModel',
								'filter'			 => false, 'value'				 => '$data[vht_model]',
								'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-sm-2'), 'header'			 => 'Vehicle Model'),
							array('name'				 => 'vhc_year', 'value'				 => '$data[vhc_year]', 'sortable'			 => false,
								'headerHtmlOptions'	 => array('class' => 'col-sm-1'), 'header'			 => 'Year'),
							array('name'				 => 'vhc_number', 'filter'			 => false, 'value'				 => '$data[vhc_number]',
								'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-sm-1'), 'header'			 => 'Number'),
							array('name'				 => 'vendorName', 'filter'			 => false, 'value'				 => '$data[vnd_name]',
								'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-sm-3'), 'header'			 => 'Vendor'),
							array('name'				 => 'vhc_color', 'value'				 => '$data[vhc_color]', 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-sm-1'), 'header'			 => 'Color'),
							array('name'				 => 'vht_capacity', 'value'				 => '$data[vht_capacity]',
								'headerHtmlOptions'	 => array('class' => 'col-sm-1'), 'header'			 => 'Capacity'),
							array('name'				 => 'cartype', 'type'				 => 'raw', 'filter'			 => false, 'value'				 => '$data[vct_label]',
								'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-sm-1'), 'header'			 => 'Car Type'),
							array('name'	 => 'vhc_dop', 'filter' => false, 'value'	 => function ($data) {
									if ($data['vhc_dop'] != '')
									{
										echo DateTimeFormat::DateTimeToDatePicker($data['vhc_dop']);
									}
								}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-sm-1'), 'header'			 => 'Date of Purchase'),
							array(
								'header'			 => 'Action',
								'class'				 => 'CButtonColumn',
								'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center'),
								'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
								'template'			 => '{approve}',
								'buttons'			 => array(
									'approve'		 => array(
										'url'		 => 'Yii::app()->createUrl("admin/vehicle/docapprovallist", array("cabid"=>$data[vhc_id]))',
										'visible'	 => '($data[vhc_approved]!=1)',
										'imageUrl'	 => Yii::app()->request->baseUrl . '\images\approve.png',
										'label'		 => '<i class="fa fa-check"></i>',
										'options'	 => array('class'	 => 'btn ignoreJob1 p0', 'target' => '_blank', 'style'	 => 'margin-right: 2px',
											'title'	 => 'Show and approve doc'),
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
<script>
    function refreshVehicleGrid() {
        $('#vehicleListGrid').yiiGridView('update');
    }
</script>
<script>
    $(document).ready(function () {
        var front_end_height = parseInt($(window).outerHeight(true));
        var footer_height = parseInt($("#footer").outerHeight(true));
        var header_height = parseInt($("#header").outerHeight(true));
        var ch = (front_end_height - (header_height + footer_height + 23));
        //console.log("wH: "+front_end_height+" HH : "+header_height+" FH: "+footer_height+"CH :"+ch);
        $("#content").attr("style", "height:" + ch + "px;");
    });

</script>