<?php
$customerDisplay	 = ($model->allocatedType > 0 && $model->custId != '') ? "block" : "none";
$vendorDisplay		 = ($model->allocatedType > 0 && $model->vendId != '') ? "block" : "none";
$driverDisplay		 = ($model->allocatedType > 0 && $model->drvId != '') ? "block" : "none";
$adminDisplay		 = ($model->allocatedType > 0 && $model->adminId != '') ? "block" : "none";
$agentDisplay		 = ($model->allocatedType > 0 && $model->agntId != '') ? "block" : "none";
$dropdownDisplay	 = ($model->allocatedType > 0) ? "block" : "none";
?>
<?php
$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
?> 
<div class="row m0">
    <div class="col-xs-12">
        <div class="text-right">
        </div>    
        <div class="panel panel-default">
            <div class="panel-body">

				<div class="row mt10" >
					<?php
					$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
						'id'					 => 'qrSearch', 'enableClientValidation' => true,
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
					<div class="col-xs-12 col-sm-2 col-md-2"> 
						<div class="form-group">
							<label class="control-label">QR Code</label>
							<?php echo $form->textField($model, 'qrCode', array('class' => 'form-control', 'placeholder' => 'QR Code')); ?>
						</div>
					</div>
					<input type="hidden" value="<?php echo $model->alloated; ?>" id="QrCode_alloated" name="QrCode[alloated]" >
					<div class="col-xs-12 col-sm-3 col-md-3">
                        <div class="form-group">
                            <label class="control-label">Allocated On</label>
							<?php
							$daterang			 = "Select Allocated Date Range";
							$allocatedDate1		 = ($model->allocatedDate1 == '') ? '' : $model->allocatedDate1;
							$allocatedDate2		 = ($model->allocatedDate2 == '') ? '' : $model->allocatedDate2;
							if ($allocatedDate1 != '' && $allocatedDate2 != '')
							{
								$daterang = date('F d, Y', strtotime($allocatedDate1)) . " - " . date('F d, Y', strtotime($allocatedDate2));
							}
							?>
                            <div id="allocatedDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                <span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
                            </div>
							<?= $form->hiddenField($model, 'allocatedDate1'); ?>
							<?= $form->hiddenField($model, 'allocatedDate2'); ?>

                        </div>
					</div>
					<div class="col-xs-12 col-sm-3 col-md-3">
                        <div class="form-group">
                            <label class="control-label">Activated On</label>
							<?php
							$daterang		 = "Select Activated Date Range";
							$activatedDate1	 = ($model->activatedDate1 == '') ? '' : $model->activatedDate1;
							$activatedDate2	 = ($model->activatedDate2 == '') ? '' : $model->activatedDate2;
							if ($activatedDate1 != '' && $activatedDate2 != '')
							{
								$daterang = date('F d, Y', strtotime($activatedDate1)) . " - " . date('F d, Y', strtotime($activatedDate2));
							}
							?>
                            <div id="activatedDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                <span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
                            </div>
							<?= $form->hiddenField($model, 'activatedDate1'); ?>
							<?= $form->hiddenField($model, 'activatedDate2'); ?>

                        </div>
					</div>
					<div class="col-xs-12 col-sm-2 col-md-2"> 
						<div class="form-group">
							<label class="control-label">Status</label>
							<?php
							$filters = [
								1	 => 'Pending',
								2	 => 'Allocated',
								3	 => 'Activated',
							];
							$dataPay = Filter::getJSON($filters);
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'qrStatus',
								'val'			 => $model->qrStatus,
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($dataPay), 'allowClear' => true),
								'htmlOptions'	 => array('class' => 'p0', 'style' => 'width:100%', 'placeholder' => 'Select Status')
							));
							?>	
						</div>
					</div>
					<div class="col-xs-12 col-sm-2 col-md-2">
						<div class="form-group">
							<label class="control-label">Activated By</label>
							<?php
							$this->widget('ext.yii-selectize.YiiSelectize', array(
								'model'				 => $model,
								'attribute'			 => 'gozens',
								'useWithBootstrap'	 => true,
								"placeholder"		 => "Select Activated By",
								'fullWidth'			 => false,
								'htmlOptions'		 => array('width'	 => '100%',
									'id'	 => 'QrCode_gozens'
								),
								'defaultOptions'	 => $selectizeOptions + array(
							'onInitialize'	 => "js:function(){
                                            populateGozen(this, '{$model->gozens}');
                                                }",
							'load'			 => "js:function(query, callback){
                                            loadGozen(query, callback);
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
					<div class="col-xs-12 col-sm-2 col-md-2">
						<div class="form-group">
							<label class="control-label">Allocated To</label>
							<?php
							$filters = [
								1	 => 'Consumer',
								2	 => 'Vendor',
								3	 => 'Driver',
								4	 => 'Gozens',
								5	 => 'Agent',
							];
							$dataPay = Filter::getJSON($filters);
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'allocatedType',
								'val'			 => $model->allocatedType,
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($dataPay), 'allowClear' => true),
								'htmlOptions'	 => array('class' => 'p0', 'style' => 'width:100%', 'placeholder' => 'Select Allocated To', 'id' => 'allocatedType')
							));
							?>	
						</div> 
					</div>
					<div class="col-xs-12 col-sm-2 col-md-2" id="requestedDrop" style="display: <?= $dropdownDisplay ?>;">
						<div id="followCust"  style="display: <?= $customerDisplay ?>">
							<label class="control-label">Search Customers</label>
							<?php
							$this->widget('ext.yii-selectize.YiiSelectize', array(
								'model'				 => $model,
								'attribute'			 => 'custId',
								'useWithBootstrap'	 => true,
								"placeholder"		 => "Select Customers",
								'fullWidth'			 => false,
								'htmlOptions'		 => array('width'	 => '100%',
									'id'	 => 'QrCode_custId'
								),
								'defaultOptions'	 => $selectizeOptions + array(
							'onInitialize'	 => "js:function(){
											populateCustomer(this, '{$model->custId}');
                                                }",
							'load'			 => "js:function(query, callback){
											console.log('hii');
                                            loadCustomer(query, callback);
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
						<div id="followVnd"  style="display: <?= $vendorDisplay ?>;">
							<label class="control-label">Search Vendors</label>
							<?php
							$this->widget('ext.yii-selectize.YiiSelectize', array(
								'model'				 => $model,
								'attribute'			 => 'vendId',
								'useWithBootstrap'	 => true,
								"placeholder"		 => "Select Vendors",
								'fullWidth'			 => false,
								'htmlOptions'		 => array('width'	 => '100%',
									'id'	 => 'QrCode_vendId'
								),
								'defaultOptions'	 => $selectizeOptions + array(
							'onInitialize'	 => "js:function(){
                                            populateVnds(this, '{$model->vendId}');
                                                }",
							'load'			 => "js:function(query, callback){
                                            loadVnds(query, callback);
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
						<div id="followDrv" style="display:<?= $driverDisplay ?>;">
							<label class="control-label">Search Drivers</label>
							<?php
							$this->widget('ext.yii-selectize.YiiSelectize', array(
								'model'				 => $model,
								'attribute'			 => 'drvId',
								'useWithBootstrap'	 => true,
								"placeholder"		 => "Select Drivers",
								'fullWidth'			 => false,
								'htmlOptions'		 => array('width'	 => '100%',
									'id'	 => 'QrCode_drvId'
								),
								'defaultOptions'	 => $selectizeOptions + array(
							'onInitialize'	 => "js:function(){
                                            populateDrvs(this, '{$model->drvId}');
                                                }",
							'load'			 => "js:function(query, callback){
                                            loadDrvs(query, callback);
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
						<div id="followAgt"  style="display: <?= $agentDisplay ?>;">
							<label class="control-label">Search Agents</label>
							<?php
							$this->widget('ext.yii-selectize.YiiSelectize', array(
								'model'				 => $model,
								'attribute'			 => 'agntId',
								'useWithBootstrap'	 => true,
								"placeholder"		 => "Select Agents",
								'fullWidth'			 => false,
								'htmlOptions'		 => array('width'	 => '100%',
									'id'	 => 'QrCode_agntId'
								),
								'defaultOptions'	 => $selectizeOptions + array(
							'onInitialize'	 => "js:function(){
                                            populatePartner(this, '{$model->agntId}');
                                                }",
							'load'			 => "js:function(query, callback){
                                            loadPartner(query, callback);
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
						<div id="followAdm"  style="display: <?= $adminDisplay ?>;">
							<label class="control-label">Search Gozens</label>
							<?php
							$this->widget('ext.yii-selectize.YiiSelectize', array(
								'model'				 => $model,
								'attribute'			 => 'adminId',
								'useWithBootstrap'	 => true,
								"placeholder"		 => "Select Gozens",
								'fullWidth'			 => false,
								'htmlOptions'		 => array('width'	 => '100%',
									'id'	 => 'QrCode_adminId'
								),
								'defaultOptions'	 => $selectizeOptions + array(
							'onInitialize'	 => "js:function(){
                                            populateAdmins(this, '{$model->adminId}');
                                                }",
							'load'			 => "js:function(query, callback){
                                            loadAdmins(query, callback);
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
					<div class="col-xs-12 col-sm-2 col-md-2"> 
						<div class="form-group">
							<label class="control-label">Approve Status</label>
							<?php
							$filters = [
								0	 => 'Pending',
								1	 => 'Approved',
								2	 => 'Rejected',
							];
							$dataPay = Filter::getJSON($filters);
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'qrApproveStatus',
								'val'			 => $model->qrApproveStatus,
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($dataPay), 'allowClear' => true),
								'htmlOptions'	 => array('class' => 'p0', 'style' => 'width:100%', 'placeholder' => 'Select Approved Status')
							));
							?>	
						</div>
					</div>
					<div class="col-xs-12 col-sm-2 col-md-2"> 
						<div class="form-group">
							<label class="control-label">Agents</label>
							<?php
							$this->widget('ext.yii-selectize.YiiSelectize', array(
								'model'				 => $model,
								'attribute'			 => 'qrc_agent_id',
								'useWithBootstrap'	 => true,
								"placeholder"		 => "Select Agents",
								'fullWidth'			 => false,
								'htmlOptions'		 => array('width'	 => '100%',
									'id'	 => 'QrCode_qrc_agent_id'
								),
								'defaultOptions'	 => $selectizeOptions + array(
							'onInitialize'	 => "js:function(){
                                            populatePartner(this, '{$model->qrc_agent_id}');
                                                }",
							'load'			 => "js:function(query, callback){
                                            loadPartner(query, callback);
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
					<div class="col-xs-2 col-sm-2">	
						<br>
						<?php echo CHtml::submitButton('Search', array('class' => 'btn btn-primary full-width submitCbr', 'name' => 'btnSearch')); ?>
					</div>
					<?php $this->endWidget(); ?>
					<?php
					echo CHtml::beginForm(Yii::app()->createUrl('admin/qr/QrAllocatedList'), "post", ['style' => "margin-bottom: 10px;margin-top: 10px;"]);
					?>
					<div class="col-xs-12 col-sm-2 col-md-2">   
						<label class="control-label"></label>
						<input type="hidden" id="export1" name="export1" value="true"/>
						<input type="hidden" id="export_qrCode" name="QrCode[export_qrCode]" value="<?= $model->qrCode ?>"/>
						<input type="hidden" id="export_allocatedDate1" name="QrCode[export_allocatedDate1]" value="<?= $model->allocatedDate1 ?>"/>
						<input type="hidden" id="export_allocatedDate2" name="QrCode[export_allocatedDate2]" value="<?= $model->allocatedDate2 ?>"/>
						<input type="hidden" id="export_activatedDate1" name="QrCode[export_activatedDate1]" value="<?= $model->activatedDate1 ?>"/>
						<input type="hidden" id="export_activatedDate2" name="QrCode[export_activatedDate2]" value="<?= $model->activatedDate2 ?>"/>
						<input type="hidden" id="export_qrStatus" name="QrCode[export_qrStatus]" value="<?= $model->qrStatus ?>"/>
						<input type="hidden" id="export_gozens" name="QrCode[export_gozens]" value="<?= $model->gozens ?>"/>
						<input type="hidden" id="export_allocatedType" name="QrCode[export_allocatedType]" value="<?= $model->allocatedType ?>"/>
						<input type="hidden" id="export_alloated" name="QrCode[export_alloated]" value="<?= $model->alloated ?>"/>
						<input type="hidden" id="export_qrApproveStatus" name="QrCode[export_qrApproveStatus]" value="<?= $model->qrApproveStatus ?>"/>
						<input type="hidden" id="export_qrc_agent_id" name="QrCode[export_qrc_agent_id]" value="<?= $model->qrc_agent_id ?>"/>
						<button class="btn btn-default" type="submit" style="width: 185px;">Export Below Table</button>
					</div>
					<?php
					echo CHtml::endForm();
					?>
				</div>

                <div class="row"> 

					<?php
					if (!empty($dataProvider))
					{
						$this->widget('booster.widgets.TbGridView', array(
							'responsiveTable'	 => true,
							'dataProvider'		 => $dataProvider,
							'template'			 => "<div class='panel-heading'><div class='row m0'>
                                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                                    </div></div>
                                                    <div class='panel-body table-responsive'>{items}</div>
                                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
							'itemsCssClass'		 => 'table table-striped table-bordered dataTable mb0',
							'htmlOptions'		 => array('class' => 'panel panel-primary  compact'),
							//    'ajaxType' => 'POST',
							'columns'			 => array(
								array('name'	 => 'qrc_code', 'filter' => FALSE, 'value'	 => function ($data) {
										$code = $data["qrc_code"];
										if ($data['qrc_status'] == 3)
										{
											$location = $data['qrc_location_lat'] . ',' . $data['qrc_location_long'];
											echo "<a href='https://maps.google.com/?q=$location' target='_blank'>$code</a>";
										}
										else
										{
											echo $code;
										}
									}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => ''), 'header'			 => 'QR Code'),
								array('name'	 => 'qrc_allocated_by', 'filter' => FALSE, 'value'	 => function ($data) {
										$adminLists = Admins::getAdminList();
										echo ($data['qrc_allocated_by'] != '') ? $adminLists[$data['qrc_allocated_by']] : '-';
									}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => ''), 'header'			 => 'Allocated By'),
								array('name'	 => 'qrc_ent_id', 'filter' => FALSE, 'value'	 => function ($data) {
										switch ($data['qrc_ent_type'])
										{
											case 1: //consumer
												$customerList	 = Users::getById($data['qrc_ent_id']);
												echo $customerList['ctt_first_name'] . ' ' . $customerList['ctt_last_name'] . " <br />(" . "Consumer" . ")";
												break;
											case 2: //vendor
												$vendorList		 = Vendors::getById($data['qrc_ent_id']);
												echo $vendorList['vnd_name'] . " <br />(" . "Vendor" . ")";
												break;
											case 3: //driver
												$driverList		 = Drivers::getByDriverId($data['qrc_ent_id']);
												echo $driverList['ctt_first_name'] . ' ' . $driverList['ctt_last_name'] . " <br />(" . "Driver" . ")";
												break;
											case 4: //admin
												$adminLists		 = Admins::getAdminList();
												echo $adminLists[$data['qrc_ent_id']] . " <br />(" . "Admin" . ")";
												break;
											case 5: //agent
												$agentList		 = Agents::getById($data['qrc_ent_id']);
												echo $agentList['agt_fname'] . ' ' . $agentList['agt_lname'] . " <br />(" . "Agent" . ")";
												break;
											default:
												break;
										}
									}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => ''), 'header'			 => 'Allocated To'),
								array('name'	 => 'qrc_allocate_date', 'filter' => FALSE, 'value'	 => function ($data) {
										echo ($data['qrc_allocate_date'] != '') ? $data['allocatedDate'] : '-';
									}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => ''), 'header'			 => 'Allocated On'),
//								array('name' => 'qrc_otp', 'value' => '$data["qrc_otp"]', 'headerHtmlOptions' => array('class' => ''), 'header' => 'OTP'),
								array('name'	 => 'qrc_activated_by', 'filter' => FALSE, 'value'	 => function ($data) {
										$adminLists = Admins::getAdminList();
										echo ($data['qrc_activated_by'] != '') ? $adminLists[$data['qrc_activated_by']] : '-';
									}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => ''), 'header'			 => 'Activated By'),
								array('name'	 => 'qrc_activated_date', 'filter' => FALSE, 'value'	 => function ($data) {
										echo ($data['qrc_activated_date'] != '') ? $data['activatedDate'] : '-';
									}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => ''), 'header'			 => 'Activated On'),
									array('name'	 => 'companyName', 'filter' => FALSE, 'value'	 => function ($data) {
										echo ($data['qrc_agent_id'] != '') ? $data['companyName'] : '-';
									}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => ''), 'header'			 => 'Agent Name'),
								array('name'				 => 'qrc_scanned_count', 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => ''), 'header'			 => 'Scanned/ Lead/ Booking', 'value'				 => function ($data) {
										$leadCount		 = $data["bkgTempCnt"];
										$qrId			 = $data["qrc_id"];
										$bookingCount	 = $data["bkgCnt"];
										if ($leadCount > 0 && $bookingCount > 0)
										{
											echo $data["qrc_scanned_count"] . ' / ' . "<a href='leadList?qrId=$qrId' target='_blank'>$leadCount</a>" . ' / ' . "<a href='/admpnl/booking/list?qrId=$qrId' target='_blank'>$bookingCount</a>";
										}
										else if ($leadCount > 0)
										{
											echo $data["qrc_scanned_count"] . ' / ' . "<a href='leadList?qrId=$qrId' target='_blank'>$leadCount</a>" . ' / ' . $bookingCount;
										}
										else if ($bookingCount > 0)
										{
											echo $data["qrc_scanned_count"] . ' / ' . $leadCount . ' / ' . "<a href='/admpnl/booking/list?qrId=$qrId' target='_blank'>$bookingCount</a>";
										}
										else
										{
											echo $data["qrc_scanned_count"] . ' / ' . $leadCount . ' / ' . $bookingCount;
										}
									}),
								array('name'	 => 'qrc_approval_status', 'filter' => FALSE, 'value'	 => function ($data) {
										if ($data['qrc_approval_status'] == '2')
										{
											echo "<strong class='label label-danger'>Rejected</strong>";
										}
										else if ($data['qrc_approval_status'] == '1')
										{
											echo "<strong class='label label-success'>Approved</strong>";
										}
										else
										{
											echo "<strong class='label label-primary'>Pending</strong>";
										}
									}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => ''), 'header'			 => 'Approve Status'),
								array('name'	 => 'qrc_status', 'filter' => FALSE, 'value'	 => function ($data) {
										if ($data['qrc_status'] == '3')
										{
											echo "<strong class='label label-success'>Activated</strong>";
										}
										else if ($data['qrc_status'] == '2')
										{
											echo "<strong class='label label-info'>Allocated</strong>";
										}
										else
										{
											echo "<strong class='label label-warning'>Pending</strong>";
										}
									}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => ''), 'header'			 => 'Status'),
								array(
									'header'			 => 'Action',
									'class'				 => 'CButtonColumn',
									'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
									'headerHtmlOptions'	 => array('class' => 'text-center'),
									'template'			 => '{view}',
									'buttons'			 => array(
										'view'			 => array(
											'click'		 => 'function(e){
														try
															{
																$href = $(this).attr("href");
																jQuery.ajax({type:"GET","dataType":"html",url:$href,success:function(data)
																{
																	bootbox.dialog({ 
																	message: data, 
																	title:"QR Details",
																	size: "large",
																	className:"bootbox-lg",    
																	callback: function(){  alert("fff"); }
																});
																}}); 
																}
																catch(e)
																{ alert(e); }
																return false;
															 }',
											'url'		 => 'Yii::app()->createUrl("admin/qr/view", array("qrId"=>$data[qrc_id]))',
											'imageUrl'	 => Yii::app()->request->baseUrl . '\images\view.gif',
											'label'		 => '<i class="fa fa-file-text-o"></i>',
											'options'	 => array('data-toggle'	 => 'ajaxModal',
												'id'			 => 'example',
												'style'			 => '',
												'rel'			 => 'popover',
												'data-placement' => 'left',
												'class'			 => 'btn btn-xs jobDetail5 p0',
												'title'			 => 'View QR Details'),
										),
										'active'		 => array(
											'visible'	 => '$data["qrc_active"] == 0',
											'click'		 => 'function(){
								  var con = confirm("Are you sure you want to Active this QR Code?");
								  return con;
								  }',
											'url'		 => 'Yii::app()->createUrl("admin/qr/status", array("qrId"=>$data[qrc_id]))',
											'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\active.png',
											'label'		 => '<i class="fa fa-toggle-on"></i>',
											'options'	 => array('style' => '', 'class' => 'btn btn-xs resetMarkBad p0', 'title' => 'Active'),
										),
										'inactive'		 => array(
											'visible'	 => '$data["qrc_active"] == 1',
											'click'		 => 'function(){
												var con = confirm("Are you sure you want to Deactive this QR Code?");
												return con;
												}',
											'url'		 => 'Yii::app()->createUrl("admin/qr/status", array("qrId"=>$data[qrc_id]))',
											'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\inactive.png',
											'label'		 => '<i class="fa fa-toggle-on"></i>',
											'options'	 => array('style' => '', 'class' => 'btn btn-xs driverFreeze p0', 'title' => 'Deactive'),
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
<script type="text/javascript">
	$(document).ready(function () {
		var start = '<?= date('d/m/Y', strtotime('-1 month')); ?>';
		var end = '<?= date('d/m/Y'); ?>';
		$('#allocatedDate').daterangepicker(
				{

					locale: {
						format: 'DD/MM/YYYY',
						cancelLabel: 'Clear'
					},
					"showDropdowns": true,
					"alwaysShowCalendars": true,
					startDate: start,
					endDate: end,
					ranges: {
						'Today': [moment(), moment()],
						'Tomorrow': [moment().add(1, 'days'), moment().add(1, 'days')],
						'Next 7 Days': [moment(), moment().add(6, 'days')],
						'Next 15 Days': [moment(), moment().add(15, 'days')],
						'This Month': [moment().startOf('month'), moment().endOf('month')],
						'Next Month': [moment().add(1, 'month').startOf('month'), moment().add(1, 'month').endOf('month')],
					}
				}, function (start1, end1) {
			$('#QrCode_allocatedDate1').val(start1.format('YYYY-MM-DD'));
			$('#QrCode_allocatedDate2').val(end1.format('YYYY-MM-DD'));
			$('#allocatedDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
		});
		$('#allocatedDate').on('cancel.daterangepicker', function (ev, picker) {
			$('#allocatedDate span').html('Select Allocated Date Range');
			$('#QrCode_allocatedDate1').val('');
			$('#QrCode_allocatedDate2').val('');
		});

		$('#activatedDate').daterangepicker(
				{
					locale: {
						format: 'DD/MM/YYYY',
						cancelLabel: 'Clear'
					},
					"showDropdowns": true,
					"alwaysShowCalendars": true,
					startDate: start,
					endDate: end,
					ranges: {
						'Today': [moment(), moment()],
						'Tomorrow': [moment().add(1, 'days'), moment().add(1, 'days')],
						'Next 7 Days': [moment(), moment().add(6, 'days')],
						'Next 15 Days': [moment(), moment().add(15, 'days')],
						'This Month': [moment().startOf('month'), moment().endOf('month')],
						'Next Month': [moment().add(1, 'month').startOf('month'), moment().add(1, 'month').endOf('month')],
					}
				}, function (start1, end1) {
			$('#QrCode_activatedDate1').val(start1.format('YYYY-MM-DD'));
			$('#QrCode_activatedDate2').val(end1.format('YYYY-MM-DD'));
			$('#activatedDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
		});
		$('#activatedDate').on('cancel.daterangepicker', function (ev, picker) {
			$('#activatedDate span').html('Select Activated Date Range');
			$('#QrCode_activatedDate1').val('');
			$('#QrCode_activatedDate2').val('');
		});
		$(document).on('click', '.submitCbr', function () {
			var followupPerson = $("#allocatedType").val();
			var followupPersonEntity = 0;
			if (followupPerson == 1)
			{
				followupPersonEntity = $("#QrCode_custId").val() != "" ? $("#QrCode_custId").val() : 0;
			} else if (followupPerson == 2)
			{
				followupPersonEntity = $("#QrCode_vendId").val() != "" ? $("#QrCode_vendId").val() : 0;
			} else if (followupPerson == 3)
			{
				followupPersonEntity = $("#QrCode_drvId").val() != "" ? $("#QrCode_drvId").val() : 0;
			} else if (followupPerson == 4)
			{
				followupPersonEntity = $("#QrCode_adminId").val() != "" ? $("#QrCode_adminId").val() : 0;
			} else if (followupPerson == 5)
			{
				followupPersonEntity = $("#QrCode_agntId").val() != "" ? $("#QrCode_agntId").val() : 0;
			}

			if (followupPersonEntity == 0)
			{
				switch (parseInt(followupPerson)) {
					case 1:
						bootbox.alert("Please select Customer name from customer dropdown");
						break;
					case 2:
						bootbox.alert("Please select Vendor name from Vendor dropdown");
						break;
					case 3:
						bootbox.alert("Please select Driver name from Driver dropdown");
						break;
					case 4:
						bootbox.alert("Please select Gozens name from Gozens dropdown");
						break;
					case 5:
						bootbox.alert("Please select Agent name from Agent dropdown");
						break;
					default:
				}
				if (followupPerson > 0)
				{
					return false;
				}
			}
			$("#QrCode_alloated").val(followupPersonEntity);
		});
	});
	$vndList = null;
	$drvList = null;
	$adminList = null;
	$custList = null;
	$followUp = new FollowUp();
	$('#allocatedType').change(function () {
		var person = $('#allocatedType').val();
		if (person > 0) {
			$("#requestedDrop").show("slow");
		}
		if (person == 1)
		{
			$("#followVnd").hide("slow");
			$("#followDrv").hide("slow");
			$("#followAgt").hide("slow");
			$("#followCust").show("slow");
			$("#followAdm").hide("slow");
		}
		if (person == 2)
		{
			$("#followVnd").show("slow");
			$("#followDrv").hide("slow");
			$("#followAgt").hide("slow");
			$("#followCust").hide("slow");
			$("#followAdm").hide("slow");
		}
		if (person == 3)
		{
			$("#followVnd").hide("slow");
			$("#followDrv").show("slow");
			$("#followAgt").hide("slow");
			$("#followCust").hide("slow");
			$("#followAdm").hide("slow");
		}
		if (person == 4)
		{
			$("#followVnd").hide("slow");
			$("#followDrv").hide("slow");
			$("#followAgt").hide("slow");
			$("#followCust").hide("slow");
			$("#followAdm").show("slow");
		}
		if (person == 5)
		{
			$("#followVnd").hide("slow");
			$("#followDrv").hide("slow");
			$("#followAgt").show("slow");
			$("#followCust").hide("slow");
			$("#followAdm").hide("slow");
		}
	});
	function populateVnds(obj, vndId)
	{
		$followUp.populateVnds(obj, vndId);
	}
	function loadVnds(query, callback)
	{
		$followUp.loadVnds(query, callback);
	}
	function populateDrvs(obj, drvId)
	{
		$followUp.populateDrvs(obj, drvId);
	}
	function loadDrvs(query, callback)
	{
		$followUp.loadDrvs(query, callback);
	}
	function populateCustomer(obj, cust)
	{
		$followUp.populateCustomer(obj, cust);
	}
	function loadCustomer(query, callback)
	{
		$followUp.loadCustomer(query, callback);
	}
	function populateAdmins(obj, admId)
	{
		$followUp.populateAdmins(obj, admId);
	}
	function loadAdmins(query, callback)
	{
		$followUp.loadAdmins(query, callback);
	}
	function populateGozen(obj, admId)
	{
		$followUp.populateAdmins(obj, admId);
	}
	function loadGozen(query, callback)
	{
		$followUp.loadAdmins(query, callback);
	}

</script>
