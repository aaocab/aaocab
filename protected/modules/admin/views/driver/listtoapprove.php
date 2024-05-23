<div class="row">
    <div class="panel">
        <div class="panel-heading">Pending Drivers to approve</div>
        <div class="panel-body">
            <div class="col-xs-12">
				<?php
				$form1			 = $this->beginWidget('booster.widgets.TbActiveForm', array(
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
						<?php
//						$agentListJson	 = Vendors::model()->getJSON();
//						$this->widget('booster.widgets.TbSelect2', array(
//							'model'			 => $driverModel,
//							'attribute'		 => 'drv_vendor_id',
//							'val'			 => $driverModel->drv_vendor_id,
//							'asDropDownList' => FALSE,
//							'options'		 => array('data' => new CJavaScriptExpression($agentListJson), 'allowClear' => true),
//							'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Vendor')
//						));
						$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
						$this->widget('ext.yii-selectize.YiiSelectize', array(
					'model'				 => $driverModel,
					'attribute'			 => 'drv_vendor_id',
					'useWithBootstrap'	 => true,
					"placeholder"		 => "Select Vendor",
					'fullWidth'			 => false,
					'htmlOptions'		 => array('width' => '100%'),
					'defaultOptions'	 => $selectizeOptions + array(
				'onInitialize'	 => "js:function(){
                                              populateVendor(this, '{$driverModel->drv_vendor_id}');
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
						<?= $form1->textFieldGroup($driverModel, 'drv_name', array('label' => '', 'widgetOptions' => array())) ?>
                    </div>

                    <div class="col-xs-6 col-sm-4">

						<? //= $form->radioButtonList($model, 'vhc_approved', array('1' => 'Approved', '2' => 'Pending approval', '0' => 'Not verified', '3' => 'Rejected'), ['class' => 'btn btn-default']); ?>
						<?php
						$arrJSON1		 = array();
						$arr1			 = ['1' => 'approved', '2' => 'pending_approval(verified)', '3' => 'rejected'];
						foreach ($arr1 as $key => $val)
						{
							$arrJSON1[] = array("id" => $key, "text" => $val);
						}
						$approvedriverlist = CJSON::encode($arrJSON1);

						$this->widget('booster.widgets.TbSelect2', array(
							'model'			 => $driverModel,
							'attribute'		 => 'drv_approved',
							'val'			 => $driverModel->drv_approved,
							'asDropDownList' => FALSE,
							'options'		 => array('data' => new CJavaScriptExpression($approvedriverlist), 'allowClear' => true),
							'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'approved status')
						));
						?>

                    </div>

                    <div class="col-xs-12 col-sm-12 text-center">
                        <button class="btn btn-primary" type="submit" style="width: 185px;">Search</button>
                    </div>
                </div>
				<?php $this->endWidget(); ?>
            </div>

            <div class="col-xs-12">
				<?php
				if (!empty($driverDataProvider))
				{
					$this->widget('booster.widgets.TbGridView', array(
						'responsiveTable'	 => true,
						'filter'			 => $driverModel,
						'dataProvider'		 => $driverDataProvider,
						'id'				 => 'driverListGrid',
						'template'			 => "<div class='panel-heading'><div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
						'itemsCssClass'		 => 'table table-striped table-bordered mb0',
						'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
						//    'ajaxType' => 'POST',
						'columns'			 => array(
							array('name'	 => 'drv_photo_path', 'value'	 => function($data) {
									echo ($data->drv_photo_path != '') ? '<img src="' . $data->drv_photo_path . '" width="50">' : '';
								},
								'sortable'			 => false, 'filter'			 => FALSE,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
								'htmlOptions'		 => array('class' => 'text-center'),
								'header'			 => 'Photo'),
							array('name'	 => 'drv_name',
								'type'	 => 'raw',
								'value'	 => function ($data) {
									$modelname = $data->drv_name;
									if ($data->drv_is_edited == 1 && $data->drv_approved == 2)
									{
										$modelname = $data->drv_name . '  <span class="text-danger" title="new changes to review"><i class="fa fa-exclamation-circle fa-lg" aria-hidden="true"></i></span>';
									}
									return $modelname;
								}
								, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Name'),
							array('name'	 => 'drv_phone', 'value'	 => function($data) {
									if ($data->drv_phone != '')
									{
										echo $data->drv_phone;
										echo "<br>";
									}
									echo $data->drv_email;
								}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'header'			 => 'Phone & Email'),
							array('name'				 => 'drvVendor.vnd_name', 'value'				 => '$data->drvVendor->vnd_name',
								'sortable'			 => false,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'),
								'htmlOptions'		 => array('class' => 'text-left'),
								'header'			 => 'Vendor'),
							array('name'	 => 'drv_aadhaar_img_path', 'value'	 => function($data) {
									echo ($data->drv_aadhaar_img_path != '') ? CHtml::link('Aadhaar Link', Yii::app()->createUrl($data->drv_aadhaar_img_path), array('target' => '_blank')) : 'No';
								},
								'sortable'			 => false, 'filter'			 => FALSE,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
								'htmlOptions'		 => array('class' => 'text-center'),
								'header'			 => 'Aadhar Card'),
							array('name'	 => 'drv_voter_id_img_path', 'value'	 => function($data) {
									echo ($data->drv_voter_id_img_path != '') ? CHtml::link('Voter Link', Yii::app()->createUrl($data->drv_voter_id_img_path), array('target' => '_blank')) : 'No';
								},
								'sortable'			 => false, 'filter'			 => FALSE,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
								'htmlOptions'		 => array('class' => 'text-center'),
								'header'			 => 'Voter Card'),
							array('name'	 => 'drv_pan_img_path', 'value'	 => function($data) {
									echo ($data->drv_pan_img_path != '') ? CHtml::link('Pan Link', Yii::app()->createUrl($data->drv_pan_img_path), array('target' => '_blank')) : 'No';
								},
								'sortable'			 => false, 'filter'			 => FALSE,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
								'htmlOptions'		 => array('class' => 'text-center'),
								'header'			 => 'Pan Card'),
							array('name'	 => 'drv_licence_path', 'value'	 => function($data) {
									echo ($data->drv_licence_path != '') ? CHtml::link('DL Link', Yii::app()->createUrl($data->drv_licence_path), array('target' => '_blank')) : 'No';
								},
								'sortable'			 => false, 'filter'			 => FALSE,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
								'htmlOptions'		 => array('class' => 'text-center'),
								'header'			 => 'DL'),
							array('name'	 => 'drv_police_certificate', 'value'	 => function($data) {
									echo ($data->drv_police_certificate != '') ? CHtml::link('License Link', Yii::app()->createUrl($data->drv_police_certificate), array('target' => '_blank')) : 'No';
								},
								'sortable'			 => false, 'filter'			 => FALSE,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
								'htmlOptions'		 => array('class' => 'text-center'),
								'header'			 => 'Police Verification'),
							array('name'	 => 'drv_approved', 'value'	 => function($data) {
									echo ($data->drv_approved == 1) ? 'Yes' : 'No';
								},
								'sortable'			 => false, 'filter'			 => FALSE,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
								'htmlOptions'		 => array('class' => 'text-center'),
								'header'			 => 'Approved'),
							array('name'	 => 'drv_total_trips', 'value'	 => function($data) {
									echo ($data->drv_total_trips > 0) ? $data->drv_total_trips : 0;
								},
								'sortable'			 => false, 'filter'			 => FALSE,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
								'htmlOptions'		 => array('class' => 'text-center'),
								'header'			 => '# Trips'),
							array(
								'header'			 => 'Action',
								'class'				 => 'CButtonColumn',
								'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center'),
								'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
								'template'			 => '{approve}{approved}{rejected}',
								'buttons'			 => array(
									'approve'		 => array(
										'click'		 => 'function(e){
                                            var con = confirm("Are you sure you want to review this driver?"); 
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
										'url'		 => 'Yii::app()->createUrl("admin/driver/approve", array(\'drvid\' => $data->drv_id,\'approve\'=>1))',
										'imageUrl'	 => false,
										'label'		 => '<i class="fa fa-check"></i>',
										'visible'	 => '($data->drv_approved==2?true:false)',
										'options'	 => array('data-toggle' => 'ajaxModal', 'style' => 'margin-right: 2px', 'class' => 'btn btn-xs btn-warning approve', 'title' => 'Approve'),
									),
									'approved'		 => array(
										'click'		 => 'function(e){
                                            var con = confirm("Are you sure you want to review this driver?"); 
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
										'url'		 => 'Yii::app()->createUrl("admin/driver/approve", array(\'drvid\' => $data->drv_id,\'approve\'=>0))',
										'imageUrl'	 => false,
										'label'		 => '<i class="fa fa-check"></i>',
										'visible'	 => '($data->drv_approved==1?true:false)',
										'options'	 => array('style' => 'margin-right: 2px', 'class' => 'btn btn-xs btn-success approved', 'title' => 'Approved'),
									),
									'rejected'		 => array(
										'click'		 => 'function(e){
                                            var con = confirm("Are you sure you want to review this driver?"); 
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
										'url'		 => 'Yii::app()->createUrl("admin/driver/approve", array(\'drvid\' => $data->drv_id,\'approve\'=>1))',
										'imageUrl'	 => false,
										'label'		 => '<i class="fa fa-check"></i>',
										'visible'	 => '($data->drv_approved==3?true:false)',
										'options'	 => array('data-toggle' => 'ajaxModal', 'style' => 'margin-right: 2px', 'class' => 'btn btn-xs btn-danger approve', 'title' => 'Approve'),
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
    $(document).ready(function () {
        var front_end_height = parseInt($(window).outerHeight(true));
        var footer_height = parseInt($("#footer").outerHeight(true));
        var header_height = parseInt($("#header").outerHeight(true));
        var ch = (front_end_height - (header_height + footer_height + 23));
        //console.log("wH: "+front_end_height+" HH : "+header_height+" FH: "+footer_height+"CH :"+ch);
        $("#content").attr("style", "height:" + ch + "px;");
    });

</script>