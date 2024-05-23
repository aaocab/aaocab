<?php
$pageno			 = filter_input(INPUT_GET, 'page');
$vendorListJson	 = Vendors::model()->getJSON();
$carType		 = VehicleTypes::model()->getCarType();
$carTypeJson	 = VehicleTypes::model()->getJSON($carType);
?>
<div class="row">
    <div class="panel">
        <div class="panel-heading">Pending Vehicles to approve</div>
        <div class="panel-body">
            <div class="col-xs-12">
				<?php
				$form			 = $this->beginWidget('booster.widgets.TbActiveForm', array(
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
						$this->widget('booster.widgets.TbSelect2', array(
							'model'			 => $model,
							'attribute'		 => 'vhc_vendor_id',
							'val'			 => $model->vhc_vendor_id,
							'asDropDownList' => FALSE,
							'options'		 => array('data' => new CJavaScriptExpression($vendorListJson), 'allowClear' => true),
							'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Vendor')
						));
						?>

                    </div>
                    <div class="col-xs-6 col-sm-3">
						<?= $form->textFieldGroup($model, 'vhc_number', array('label' => '', 'widgetOptions' => array())) ?>
                    </div>

                    <div class="col-xs-6 col-sm-4">

						<? //= $form->radioButtonList($model, 'vhc_approved', array('1' => 'Approved', '2' => 'Pending approval', '0' => 'Not verified', '3' => 'Rejected'), ['class' => 'btn btn-default']); ?>
						<?
						$arrJSON		 = array();
						$arr			 = ['1' => 'Approved', '2' => 'Pending Approval(Verified)', '3' => 'Rejected'];
						foreach ($arr as $key => $val)
						{
							$arrJSON[] = array("id" => $key, "text" => $val);
						}
						$approveStatusList = CJSON::encode($arrJSON);

						$this->widget('booster.widgets.TbSelect2', array(
							'model'			 => $model,
							'attribute'		 => 'vhc_approved',
							'val'			 => $model->vhc_approved,
							'asDropDownList' => FALSE,
							'options'		 => array('data' => new CJavaScriptExpression($approveStatusList), 'allowClear' => true),
							'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'approved status')
						));
						?>

                    </div>

                    <div class="col-xs-12 col-sm-12 text-center">
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
							array('name'	 => 'vhcModel',
								'filter' => false,
								'type'	 => 'raw',
								'value'	 => function ($data) {
									$modelname = $data->vhcType->vht_make . " " . $data->vhcType->vht_model;

									if ($data->vhc_is_edited == 1 && $data->vhc_approved == 2)
									{
										$modelname = $data->vhcType->vht_make . " " . $data->vhcType->vht_model . '  <span class="text-danger" title="new changes to review"><i class="fa fa-exclamation-circle fa-lg" aria-hidden="true"></i></span>';
									}
									return $modelname;
								}
								,
								'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Vehicle Model'),
							array('name' => 'vhc_year', 'value' => '$data->vhc_year', 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Year'),
							array('name' => 'vhc_number', 'filter' => false, 'value' => '$data->vhc_number', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Number'),
							array('name' => 'vendorName', 'filter' => false, 'value' => '$data->vhcVendor->vnd_name', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Vendor'),
							array('name' => 'vhc_color', 'value' => '$data->vhc_color', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Color'),
							array('name' => 'vhcCapacity', 'value' => '$data->vhcType->vht_capacity', 'headerHtmlOptions' => array(), 'header' => 'Capacity'),
							array('name' => 'cartype', 'type' => 'raw', 'value' => '$data->getCarType()', 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Car Type'),
							array('name'	 => 'vhc_dop', 'value'	 => function ($data) {
									if ($data->vhc_dop != '')
									{

										return DateTimeFormat::DateToLocale($data->vhc_dop);
									}
								}, 'sortable'			 => false, 'headerHtmlOptions'	 => array(), 'header'			 => 'Date of Purchase'),
							array(
								'header'			 => 'Action',
								'class'				 => 'CButtonColumn',
								'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center'),
								'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
								'template'			 => '{approve}{approved}{rejected}',
								'buttons'			 => array(
									'approve'		 => array(
										'click'		 => 'function(e){
                                            var con = confirm("Are you sure you want to review this vehicle?"); 
                                                              if(con)
                                                              {
                                            try
                                            {
                                                    $href = $(this).attr("href");
                                                    jQuery.ajax({type:"GET","dataType":"html",url:$href,success:function(data)
                                                    {
                                                        bootbox.dialog({ 
                                                        message: data, 
                                                        className:"bootbox-lg",
                                                        title:"",
                                                        size: "large",
                                                        callback: function(){   }
                                                    });
                                                    }}); 
                                            }
                                            catch(e)
                                            { 
                                            
                                                  alert(e); 
                                            }
                                                  return false;
                                            }
                                            else
                                            {
                                                 return false;
                                            }
                                        }',
										'url'		 => 'Yii::app()->createUrl("admin/vehicle/approve", array(\'vhcid\' => $data->vhc_id,\'approve\'=>1))',
										'imageUrl'	 => false,
										'label'		 => '<i class="fa fa-check"></i>',
										'visible'	 => '($data->vhc_approved==2?true:false)',
										'options'	 => array('data-toggle' => 'ajaxModal', 'style' => 'margin-right: 2px', 'class' => 'btn btn-xs btn-warning approve', 'title' => 'Pending approval'),
									),
									'approved'		 => array(
										'click'		 => 'function(e){
                                            var con = confirm("Are you sure you want to review this vehicle?"); 
                                                              if(con)
                                                              {
                                            try
                                            {
                                                    $href = $(this).attr("href");
                                                    jQuery.ajax({type:"GET","dataType":"html",url:$href,success:function(data)
                                                    {
                                                        bootbox.dialog({ 
                                                        message: data, 
                                                        className:"bootbox-lg",
                                                        title:"",
                                                        size: "large",
                                                        callback: function(){   }
                                                    });
                                                    }}); 
                                            }
                                            catch(e)
                                            { 
                                            
                                                  alert(e); 
                                            }
                                                  return false;
                                            }
                                            else
                                            {
                                                 return false;
                                            }
                                        }',
										'url'		 => 'Yii::app()->createUrl("admin/vehicle/approve", array(\'vhcid\' => $data->vhc_id,\'approve\'=>1))',
										'imageUrl'	 => false,
										'label'		 => '<i class="fa fa-check"></i>',
										'visible'	 => '($data->vhc_approved==1 ?true:false)',
										'options'	 => array('style' => 'margin-right: 2px', 'class' => 'btn btn-xs btn-success approved', 'title' => 'Approved'),
									),
									'rejected'		 => array(
										'click'		 => 'function(e){
                                            var con = confirm("Are you sure you want to review this vehicle?"); 
                                                              if(con)
                                                              {
                                            try
                                            {
                                                    $href = $(this).attr("href");
                                                    jQuery.ajax({type:"GET","dataType":"html",url:$href,success:function(data)
                                                    {
                                                        bootbox.dialog({ 
                                                        message: data, 
                                                        className:"bootbox-lg",
                                                        title:"",
                                                        size: "large",
                                                        callback: function(){   }
                                                    });
                                                    }}); 
                                            }
                                            catch(e)
                                            { 
                                            
                                                  alert(e); 
                                            }
                                                  return false;
                                            }
                                            else
                                            {
                                                 return false;
                                            }
                                        }',
										'url'		 => 'Yii::app()->createUrl("admin/vehicle/approve", array(\'vhcid\' => $data->vhc_id,\'approve\'=>1))',
										'imageUrl'	 => false,
										'label'		 => '<i class="fa fa-check"></i>',
										'visible'	 => '($data->vhc_approved==3 ?true:false)',
										'options'	 => array('style' => 'margin-right: 2px', 'class' => 'btn btn-xs btn-danger rejected', 'title' => 'Rejected'),
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