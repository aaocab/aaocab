<style>
    .panel-body {
        padding-top: 0;
        padding-bottom: 0;
    }
    .table>tbody>tr>th {
        vertical-align: middle
    }
    .table>tbody>tr>td, .table>tbody>tr>th {
        padding: 7px;
        line-height: 1.5em;
    }
</style>
<div class="row m0">
    <div class="col-xs-12 " style="float:none; margin: auto; margin-left: 25px;">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
					<?php
					$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
						'id'					 => 'cancellation-form', 'enableClientValidation' => true,
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

                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-xs-12 col-lg-6">
                                <div class="row">
                                    <div class="col-xs-12 pl5 pr5"><label class="full-width pt5 pb5 text-center bg-primary">Booking Date</label></div>
                                    <div class="col-xs-12 col-sm-6">
										<?= $form->datePickerGroup($model, 'bkg_create_date1', array('label' => '', 'widgetOptions' => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'Booking From Date')), 'prepend' => '<i class="fa fa-calendar"></i>')); ?>
                                    </div>
                                    <div class="col-xs-12 col-sm-6">
										<?=
										$form->datePickerGroup($model, 'bkg_create_date2', array('label'			 => '',
											'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'Booking To Date')), 'prepend'		 => '<i class="fa fa-calendar"></i>'));
										?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-lg-6">
                                <div class="row">
                                    <div class="col-xs-12 pl5 pr5"><label class="full-width pt5 pb5 text-center bg-primary">Pickup Date</label></div>
                                    <div class="col-xs-12 col-sm-6">
										<?=
										$form->datePickerGroup($model, 'bkg_pickup_date_date', array('label'			 => '',
											'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'Pickup From Date')), 'prepend'		 => '<i class="fa fa-calendar"></i>'));
										?>    
                                    </div>
                                    <div class="col-xs-12 col-sm-6">
										<?=
										$form->datePickerGroup($model, 'bkg_return_date_date', array('label'			 => '',
											'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'Pickup To Date')), 'prepend'		 => '<i class="fa fa-calendar"></i>'));
										?>  
                                    </div>                                    
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-3 pt5">
								<?php
//								$vendorListJson	 = Vendors::model()->getJSON();
//								$this->widget('booster.widgets.TbSelect2', array(
//									'model'			 => $model,
//									'attribute'		 => 'bkg_vendor_id',
//									'val'			 => $model->bkg_vendor_id,
//									'asDropDownList' => FALSE,
//									'options'		 => array('data' => new CJavaScriptExpression($vendorListJson), 'allowClear' => true),
//									'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Vendor')
//								));
								$selectizeOptions = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
									'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
									'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
									'openOnFocus'		 => true, 'preload'			 => false,
									'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
									'addPrecedence'		 => false,];
								$this->widget('ext.yii-selectize.YiiSelectize', array(
									'model'				 => $model,
									'attribute'			 => 'bkg_vendor_id',
									'useWithBootstrap'	 => true,
									"placeholder"		 => "Select Vendor",
									'fullWidth'			 => false,
									'htmlOptions'		 => array('width' => '100%'),
									'defaultOptions'	 => $selectizeOptions + array(
								'onInitialize'	 => "js:function(){
                                              populateVendor(this, '{$model->bkg_vendor_id}');
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
                            <div class="col-xs-12 col-sm-6 col-md-3 pt5">
								<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary full-width')); ?>
                            </div>
                            <div class="col-xs-12 col-sm-6  col-md-3 pt5">
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-3 pt5">
                            </div>
                        </div>
                    </div>
					<?php $this->endWidget(); ?>
                </div>


                <div class="row" style="margin-top: 10px">
                    <div class="col-xs-12 col-sm-7 col-md-5">       
                        <table class="table table-bordered" style="">
                            <thead>
                                <tr style="color: black;background: whitesmoke">
                                    <th><u>Status</u></th>
									<th><u>Count</u></th>
								</tr>
                            </thead>
                            <tbody id="count_booking_row">                         
                                <tr>
                                    <td style="border-top : 1px solid grey;font-style: italic;">Total Cancelled</td>
                                    <td style="border-top : 1px solid grey;">
										<?php
										if ($countReport[0]['bkg_status'] != '')
										{
											echo $countReport[0]['count'];
										}
										else
										{
											echo '0';
										}
										?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>


                <div>&nbsp;</div>
				<?php
				if (!empty($dataProvider))
				{
					/* @var $dataProvider TbGridView */
					$checkContactAccess = Yii::app()->user->checkAccess("bookingContactAccess");
					$params = array_filter($_REQUEST);
					$dataProvider->getPagination()->params = $params;
					$dataProvider->getSort()->params = $params;
					$this->widget('booster.widgets.TbGridView', array(
						'responsiveTable'	 => true,
						'dataProvider'		 => $dataProvider,
						'pager'				 => ['maxButtonCount' => 5, 'class' => 'booster.widgets.TbPager'],
						'id'				 => 'reportCancellationGrid',
						'template'			 => "<div class='panel-heading'><div class='row m0'>
                                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                                    </div></div>
                                                    <div class='panel-body table-responsive'>{items}</div>
                                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
						'itemsCssClass'		 => 'table table-striped table-bordered dataTable mb0',
						'htmlOptions'		 => array('class' => 'panel panel-primary  compact'),
						//    'ajaxType' => 'POST',
						'columns'			 => array(
							array('name' => 'bkg_cancel_delete_reason', 'value' => '$data[bkg_cancel_delete_reason]', 'sortable' => true, 'htmlOptions' => array('class' => 'text-left'), 'headerHtmlOptions' => array('class' => 'col-xs-2,text-center'), 'header' => 'Delete Reason'),
							array('name' => 'booking_count', 'value' => '$data[booking_count]', 'sortable' => true, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'col-xs-2 text-center'), 'header' => 'Cancel Count'),
					)));
				}
				?>
            </div>
        </div>
    </div>
</div>
<?php
$version = Yii::app()->params['customJsVersion'];
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/custom.js?v=' . $version, CClientScript::POS_HEAD);
?>
<script>
    function refreshAccGrid() {
        $('#reportAccountGrid').yiiGridView('update');
    }
</script>