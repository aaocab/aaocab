<?php
$pageno				 = Yii::app()->request->getParam('page');
$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
//$vendorListJson = Vendors::model()->getJSON1();
?>
<div class="row m0">
    <div class="col-xs-12">        
        <div class="panel panel-default">
            <div class="panel-body">
				<?php
				$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
					'id'					 => 'patlist-form', 'enableClientValidation' => true,
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
				// @var $form TbActiveForm 
				?>
				<div class="row">
                    <div class="col-xs-12 col-md-3">
                        <div class="form-group">
                            <label class="control-label">Channel Partner</label>
							<?php
							$dataagents			 = Agents::model()->getAgentsFromBooking();
//							$edataagents		 = json_decode($dataagents);
//							$edataagents[]		 = (object) ['id' => 0, 'text' => 'B2C'];
//							$dataagents			 = json_encode($edataagents);

							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'ltp_partner_id',
								'val'			 => $model->ltp_partner_id,
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($dataagents), 'allowClear' => true),
								'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Partner name')
							));
							?>
                        </div> 
                    </div>
					<div class="col-xs-12 col-md-3">
                        <div class="form-group">
                            <label class="control-label">Select City</label>
							<?php
							$cities = Cities::model()->getRailwayBusList();

							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'ltp_city_id',
								'val'			 => $model->ltp_city_id,
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($cities), 'allowClear' => true),
								'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select city')
							));
							?>
                        </div> 
                    </div>
<!--					<div class="col-xs-12 col-md-3">
						<div class="form-group">
							<label class="control-label">Cab Type</label>
							<?php
//							$returnType		 = "list";
//							$vehicleList	 = SvcClassVhcCat::getVctSvcList($returnType);
//							$this->widget('booster.widgets.TbSelect2', array(
//								'model'			 => $model,
//								'attribute'		 => 'ltp_vehicle_type',
//								'val'			 => $model->ltp_vehicle_type,
//								'data'			 => $vehicleList,
//								'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => 'multiple',
//									'placeholder'	 => 'Select Car Type')
//							));
							?>
						</div>
					</div>-->
					<div class="col-xs-12 col-md-2">
                        <div class="form-group">
                            <label class="control-label">Transfer Type</label>
							<?php
							$transferTypes	 = LocalTransferPackage::model()->transferTypes;

							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'ltp_transfer_type',
								'val'			 => $model->ltp_transfer_type,
								//'asDropDownList' => FALSE,
								'data'			 => $transferTypes, //, 'allowClear' => true),
								'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Transfer Type')
							));
							?>
                        </div> 
                    </div>	
					<div class="col-xs-12 col-sm-1 mt20"> 
						<?php echo $form->checkboxListGroup($model, 'is_b2c', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'B2C '), 'htmlOptions' => []))) ?>
					</div>
				</div>
				<div class="row">
					<?php
					$checkExportAccess = Yii::app()->user->checkAccess("exportRate");
					if ($checkExportAccess)
					{
						?>
						<div class="col-xs-8 col-sm-3"> 
							<button class="btn btn-default" name="exportRate" id="exportRate" type="submit" value="Export" style="width: 185px;">Export Table</button>
						</div>
						<?php }
					?>
					<div class="col-xs-4 col-md-3">   
<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary', 'value' => 'Search')); ?></div>
				</div>

			</div>				
<?php $this->endWidget(); ?>

			<a class="btn btn-primary mb10" href="<?= Yii::app()->createUrl('admin/rate/addlocal') ?>" style="text-decoration: none">Add new</a>
			<?php
			if (!empty($dataProvider))
			{
				$this->widget('booster.widgets.TbGridView', array(
					'responsiveTable'	 => true,
					'dataProvider'		 => $dataProvider,
					'selectableRows'	 => 2,
					'id'				 => 'PATListGrid',
					'template'			 => "<div class='panel-heading'><div class='row m0'>
            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
            </div></div>
            <div class='panel-body'>{items}</div>
            <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
					'itemsCssClass'		 => 'table table-striped table-bordered mb0',
					'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
					//'ajaxType' => 'POST',
					'columns'			 => array(
						array('name'	 => 'partnerName', 'value'	 => function ($data) {
								echo ($data['partnerName'] != '') ? $data['partnerName'] : 'B2C';
							}, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Partner '),
						array('name' => 'localType', 'value' => '$data[localType]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Local Type '),	
						array('name' => 'localName', 'value' => '$data[localName]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Local Transfer '),
						array('name'				 => 'transferType',
							'value'				 => '$data[transferType]',
							'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Transfer type'),
						array('name' => 'ltp_minimum_km', 'value' => '$data[ltp_minimum_km]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Minimum km'),
						array('name' => 'ltp_extra_per_km_rate', 'value' => '$data[ltp_extra_per_km_rate]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Extra rate/km'),
						array('name' => 'vehicleType', 'value' => '$data[vehicleType]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Cab type'),
						array('name' => 'ltp_vendor_amount', 'value' => '$data[ltp_vendor_amount]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Vendor amount'),
						array('name' => 'ltp_total_fare', 'value' => '$data[ltp_total_fare]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Total fare'),
//						array(
//							'header'			 => 'Airport Fee Include/Exclude',
//							'class'				 => 'CButtonColumn',
//							'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
//							'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
//							'template'			 => '{includeairportfee}{excludeairportfee}',
//							'buttons'			 => array(
//								'includeairportfee'	 => array(
//									'click'		 => 'function(e){                                                        
//                                    try
//                                    {
//                                        $href = $(this).attr("href");
//                                        jQuery.ajax({type:"GET",url:$href,success:function(data)
//                                        {
//											window.location.href=$href;
//                                        }}); 
//                                    }
//                                    catch(e)
//                                    { 
//                                        alert(e); 
//                                    }
//                                    return false;
//
//                                }',
//									'url'		 => 'Yii::app()->createUrl("aaohome/rate/includeairportfee", array("patid" => $data[pat_id],"is_airport_fee_included"=>$data[is_airport_fee_included]))',
//									'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\active.png',
//									'visible'	 => '($data[is_airport_fee_included] == 1)',
//									'label'		 => '<i class="fa fa-toggle-on"></i>',
//									'options'	 => array('data-toggle' => 'ajaxModal', 'id' => 'admFreeze', 'style' => '', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs driverFreeze p0', 'title' => 'Include airport fee')
//								),
//								'excludeairportfee'	 => array(
//									'click'		 => 'function(e){                                                        
//                                    try
//                                    {
//                                        $href = $(this).attr("href");
//                                        jQuery.ajax({type:"GET",url:$href,success:function(data)
//                                        {
//										   window.location.href=$href;
//                                        }}); 
//                                    }
//                                    catch(e)
//                                    { 
//                                        alert(e); 
//                                    }
//                                    return false;
//
//                                }',
//									'url'		 => 'Yii::app()->createUrl("aaohome/rate/includeairportfee", array("ltpid" => $data[ltp_id],"is_airport_fee_included"=>$data[is_airport_fee_included]))',
//									'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\inactive.png',
//									'visible'	 => '($data[is_airport_fee_included] == 0)',
//									'label'		 => '<i class="fa fa-toggle-on"></i>',
//									'options'	 => array('data-toggle' => 'ajaxModal', 'id' => 'admFreeze', 'style' => '', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs driverUnfreeze p0', 'title' => 'Exclude airport fee')
//								),
//								'htmlOptions'		 => array('class' => 'center'),
//							)
//						),
						array(
							'header'			 => 'Action',
							'class'				 => 'CButtonColumn',
							'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
							'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
							'template'			 => '{edit}{delete}',
							'buttons'			 => array(
								'edit'			 => array(
									'url'		 => 'Yii::app()->createUrl("admin/rate/addlocal", array("ltpid" => $data[ltp_id]))',
									'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\rate_list\edit_booking.png',
									'label'		 => '<i class="fa fa-edit"></i>',
									'options'	 => array('style' => '', 'class' => 'btn btn-xs ignoreJob p0', 'title' => 'Edit'),
								),
								'delete'		 => array(
									'click'		 => 'function(){
														var con = confirm("Are you sure you want to delete this local rate?");
														return con;
													}',
									'url'		 => 'Yii::app()->createUrl("admin/rate/deleteLocal", array(\'ltpid\' => $data[ltp_id]))',
									'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\driver\customer_cancel.png',
									'label'		 => '<i class="fa fa-remove"></i>',
									'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'class' => 'btn btn-xs conDelete p0', 'title' => 'Delete Local'),
								),
//								'log'			 => array(
//									'click'		 => 'function(){
//                                                    $href = $(this).attr(\'href\');
//                                                    jQuery.ajax({type: \'GET\',
//                                                    url: $href,
//                                                    success: function (data)
//                                                    {
//
//                                                        var box = bootbox.dialog({
//                                                            message: data,
//                                                            title: \'Partner Airport Transfer Log\',
//															size: \'large\',
//                                                            onEscape: function () {
//
//                                                                // user pressed escape
//                                                            }
//                                                        });
//                                                    }
//                                                });
//                                                    return false;
//                                                    }',
//									'url'		 => 'Yii::app()->createUrl("admin/rate/showPatLog", array("ltpid" => $data["ltp_id"]))',
//									'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\rate_list\show_log.png',
//									'label'		 => '<i class="fa fa-list"></i>',
//									'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'class' => 'btn btn-xs conshowlog p0', 'title' => 'Show Log'),
//								),
								'htmlOptions'	 => array('class' => 'center'),
							))
					)
						)
				);
			}
			?>
		</div> 
	</div> 
</div>
