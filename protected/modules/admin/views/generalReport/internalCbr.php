<style>
	.table-flex { display: flex; flex-direction: column; }
	.tr-flex { display: flex; }
	.th-flex, .td-flex{ flex-basis: 35%; }
	.thead-flex, .tbody-flex { overflow-y: scroll; }
	.tbody-flex { max-height: 250px; }
</style>
<?php
$customerDisplay	 = ($model->requestedBy > 0 && $model->custId != '') ? "" : "none";
$vendorDisplay		 = ($model->requestedBy > 0 && $model->vendId != '') ? "block" : "none";
$driverDisplay		 = ($model->requestedBy > 0 && $model->drvId != '') ? "block" : "none";
$adminDisplay		 = ($model->requestedBy > 0 && $model->adminId != '') ? "block" : "none";
$agentDisplay		 = ($model->requestedBy > 0 && $model->agntId != '') ? "block" : "none";
$teamDisplay		 = ($model->scq_to_be_followed_up_by_type > 0 && $model->scq_to_be_followed_up_by_id != '') ? "block" : "none";
$gozenDisplay		 = ($model->scq_to_be_followed_up_by_type > 0 && $model->isGozen != '') ? "block" : "none";
$dropdownDisplay	 = ($model->requestedBy > 0) ? "block" : "none";
?>
<?php
$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
?>
<?php
$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
	'id'					 => 'urgentPickUp-report', 'enableClientValidation' => true,
	'clientOptions'			 => array(
		'validateOnSubmit'	 => true,
		'errorCssClass'		 => 'has-error'
	),
	'enableAjaxValidation'	 => false,
	'errorMessageCssClass'	 => 'help-block',
	'htmlOptions'			 => array(
		'class' => '',
	),
		));
/* @var $form TbActiveForm */
?>
<!--=========================================================================-->
<div class="row">
	<div class="col-xs-12  pb10">
		<a href="/admpnl/generalReport/scqreport" target="_blank"> Click To View  SCQ Report</a>
		<br>
		<a target="_blank" href="/admpnl/scq/teamStaticalData/">Click To View Team Report</a>
	</div>
</div>
<div class="row"> 
	<div class="col-xs-12 col-sm-4 col-md-4">
		<?= $form->textFieldGroup($model, 'search', array('label' => 'Search By (Booking Id, Followup Id,Followup Comment)', 'htmlOptions' => array('placeholder' => 'Search By (Booking Id, Followup Id,Followup Comment)'))) ?>
	</div>
	<div class="col-xs-12 col-sm-2 col-md-2">
		<div class="form-group">
			<label class="control-label">Created By</label>
			<?php
			$filters			 = [
				1	 => 'Consumer',
				2	 => 'Vendor',
				3	 => 'Driver',
				4	 => 'Admin',
				5	 => 'Agent',
			];
			$dataPay			 = Filter::getJSON($filters);
			$this->widget('booster.widgets.TbSelect2', array(
				'model'			 => $model,
				'attribute'		 => 'requestedBy',
				'val'			 => $model->requestedBy,
				'asDropDownList' => FALSE,
				'options'		 => array('data' => new CJavaScriptExpression($dataPay), 'allowClear' => true),
				'htmlOptions'	 => array('class' => 'p0', 'style' => 'width:100%', 'placeholder' => 'Select Requested', 'id' => 'requestedBy')
			));
			?>	
		</div> 
	</div>
	<div class="col-xs-12 col-sm-2 col-md-2" id="requestedDrop" style="display: <?= $dropdownDisplay ?>;">
		<div id="followCustomer"  style="display: <?= $customerDisplay ?>">
			<label class="control-label">Search Customers</label>
			<?php
			$this->widget('ext.yii-selectize.YiiSelectize', array(
				'model'				 => $model,
				'attribute'			 => 'custId',
				'useWithBootstrap'	 => true,
				"placeholder"		 => "Select Customers",
				'fullWidth'			 => false,
				'htmlOptions'		 => array('width'	 => '100%',
					'id'	 => 'ServiceCallQueue_custId'
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

		<div id="followVendor"  style="display: <?= $vendorDisplay ?>;">
			<label class="control-label">Search Vendors</label>
			<?php
			$this->widget('ext.yii-selectize.YiiSelectize', array(
				'model'				 => $model,
				'attribute'			 => 'vendId',
				'useWithBootstrap'	 => true,
				"placeholder"		 => "Select Vendors",
				'fullWidth'			 => false,
				'htmlOptions'		 => array('width'	 => '100%',
					'id'	 => 'ServiceCallQueue_vendId'
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

		<div id="followDriver" style="display:<?= $driverDisplay ?>;">
			<label class="control-label">Search Drivers</label>
			<?php
			$this->widget('ext.yii-selectize.YiiSelectize', array(
				'model'				 => $model,
				'attribute'			 => 'drvId',
				'useWithBootstrap'	 => true,
				"placeholder"		 => "Select Drivers",
				'fullWidth'			 => false,
				'htmlOptions'		 => array('width'	 => '100%',
					'id'	 => 'ServiceCallQueue_drvId'
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

		<div id="followAgents"  style="display: <?= $agentDisplay ?>;">
			<label class="control-label">Search Agents</label>
			<?php
			$this->widget('ext.yii-selectize.YiiSelectize', array(
				'model'				 => $model,
				'attribute'			 => 'agntId',
				'useWithBootstrap'	 => true,
				"placeholder"		 => "Select Agents",
				'fullWidth'			 => false,
				'htmlOptions'		 => array('width'	 => '100%',
					'id'	 => 'ServiceCallQueue_agntId'
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
			<label class="control-label">Search Admins</label>
			<?php
			$this->widget('ext.yii-selectize.YiiSelectize', array(
				'model'				 => $model,
				'attribute'			 => 'adminId',
				'useWithBootstrap'	 => true,
				"placeholder"		 => "Select Admins",
				'fullWidth'			 => false,
				'htmlOptions'		 => array('width'	 => '100%',
					'id'	 => 'ServiceCallQueue_adminId'
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
			<label class="control-label">Assigned By</label>
			<?php
			$filters			 = [
				1	 => 'By a Gozo Team',
				2	 => 'By a Gozen',
			];
			$dataPay			 = Filter::getJSON($filters);
			$this->widget('booster.widgets.TbSelect2', array(
				'model'			 => $model,
				'attribute'		 => 'scq_to_be_followed_up_by_type',
				'val'			 => $model->scq_to_be_followed_up_by_type,
				'asDropDownList' => FALSE,
				'options'		 => array('data' => new CJavaScriptExpression($dataPay), 'allowClear' => true),
				'htmlOptions'	 => array('class' => 'p0', 'style' => 'width:100%', 'placeholder' => 'Select Disposed By')
			));
			?>	
		</div> 
	</div>
	<div class="col-xs-12 col-sm-2 col-md-2">
		<div id="followteam"  style="display: <?= $teamDisplay ?>">
			<label class="control-label">Search Teams</label>
			<?php
			$this->widget('ext.yii-selectize.YiiSelectize', array(
				'model'				 => $model,
				'attribute'			 => 'scq_to_be_followed_up_by_id',
				'useWithBootstrap'	 => true,
				"placeholder"		 => "Select Team(s)",
				'fullWidth'			 => false,
				'htmlOptions'		 => array('width'	 => '100%',
					'id'	 => 'ServiceCallQueue_scq_to_be_followed_up_by_id'
				),
				'defaultOptions'	 => $selectizeOptions + array(
			'onInitialize'	 => "js:function(){
                                            populateTeams(this, '{$model->scq_to_be_followed_up_by_id}');
                                                }",
			'load'			 => "js:function(query, callback){
                                            loadTeams(query, callback);
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
		<div id="followgozn"  style="display: <?= $gozenDisplay ?>">
			<label class="control-label">Search Gozens</label>
			<?php
			$this->widget('ext.yii-selectize.YiiSelectize', array(
				'model'				 => $model,
				'attribute'			 => 'isGozen',
				'useWithBootstrap'	 => true,
				"placeholder"		 => "Select Gozens",
				'fullWidth'			 => false,
				'htmlOptions'		 => array('width'	 => '100%',
					'id'	 => 'ServiceCallQueue_isGozen'
				),
				'defaultOptions'	 => $selectizeOptions + array(
			'onInitialize'	 => "js:function(){
                                            populateGozen(this, '{$model->isGozen}');
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
</div>





<div class="row">
	<div class="col-xs-12 col-sm-4 col-md-4 text-left mt20 pl20" >
		<label class="checkbox-inline pt0 pl0" for="openfollowup">Show only Open follow ups
			<input class="form-control" id="openfollowup" type="radio" value="1"  onclick="changeStatus()" name="ServiceCallQueue[isFollowUpOpen]" <?php
			if ($model->isFollowUpOpen == 1)
			{
				echo 'checked="checked"';
			}
			?> >
		</label>

		<div class="row">
			<div class="col-xs-12 col-sm-2 col-md-2 text-left mt20 pl20 isDue24 "  ><b>Show</b>
			</div>
			<div class="col-xs-12 col-sm-5 col-md-5 text-left mt20 pl20 isDue24" >
				<label class="checkbox-inline pt0 pl0" for="allOpen">All Open 

					<input id="allOpen" type="radio" value="0" name="ServiceCallQueue[isDue24]" <?php
					if ($model->isDue24 == 0)
					{
						echo 'checked="checked"';
					}
					?>>

				</label>
			</div>
			<div class="col-xs-12 col-sm-5 col-md-5 text-left mt20 isDue24 " >
				<label class="checkbox-inline pt0 pl0" for="next24hour">Next 24 Hours
					<input type="radio" id="next24hour" value="1" name="ServiceCallQueue[isDue24]" <?php
					if ($model->isDue24 == 1)
					{
						echo 'checked="checked"';
					}
					?>>

				</label></div>

		</div>

	</div>
	<div class="col-xs-12 col-sm-4 col-md-4 text-left mt20 pl00" >
		<label for="closefollwoup">Show only Closed follow ups
			<input class="form-control" id="closefollwoup" type="radio" value="0" onclick="changeStatus()"   name="ServiceCallQueue[isFollowUpOpen]" <?php
			if ($model->isFollowUpOpen == 0)
			{
				echo 'checked="checked"';
			}
			?> ></label></div>


	<div class="col-xs-12 col-sm-3 col-md-3 text-center mt10 p5" >
		<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary full-width submitService')); ?>
	</div>

</div>
<!--=========================================================================-->
<?php $this->endWidget(); ?>
<?php
if (!empty($dataProvider))
{

	$params									 = array_filter($_REQUEST);
	$dataProvider->getPagination()->pageSize = 200;
	$dataProvider->getPagination()->params	 = $params;
	$dataProvider->getSort()->params		 = $params;
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
		'columns'			 =>
		array
			(
			array('name'	 => 'scq_id', 'value'	 => function($data) {
					if ($data['scq_prev_or_originating_followup'] != 0)
					{
						echo CHtml::link($data['scq_id'], Yii::app()->createUrl("admin/scq/view", ["id" => $data['scq_id']]), ['target' => '_blank']) . "</br>    followed by " . CHtml::link($data['scq_prev_or_originating_followup'], Yii::app()->createUrl("admin/scq/view", ["id" => $data['scq_prev_or_originating_followup']]), ['target' => '_blank']);
					}
					else
					{
						echo CHtml::link($data['scq_id'], Yii::app()->createUrl("admin/scq/view", ["id" => $data['scq_id']]), ['target' => '_blank']);
					}
				}, 'sortable'								 => true,
				'headerHtmlOptions'						 => array('class' => 'col-xs-1'), 'header'								 => 'ID'),
			array('name'	 => 'Follow up created by person', 'value'	 => function($data) {
					$detail = Admins::model()->getProfileData($data['scq_created_by_uid']);
					echo $detail[0]['adm_fname'] . ' ' . $detail[0]['adm_lname'];
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Created by'),
			array('name'	 => 'Followup instructions', 'value'	 => function ($data) {
					echo $data['scq_creation_comments'];
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Instructions'),
			array('name'	 => 'callerType', 'value'	 => function($data) {
					if ($data['scq_to_be_followed_up_with_entity_type'] == 5)
					{
						echo CHtml::link($data['callerType'], Yii::app()->createUrl("admin/agent/view", ["agent" => $data['scq_to_be_followed_up_with_entity_id']]), ["onclick" => "", 'target' => '_blank']);
					}
					else if ($data['scq_to_be_followed_up_with_entity_type'] == 3)
					{
						echo CHtml::link($data['callerType'], Yii::app()->createUrl("admin/driver/view", ["id" => $data['scq_to_be_followed_up_with_entity_id']]), ["onclick" => "", 'target' => '_blank']);
					}
					else if ($data['scq_to_be_followed_up_with_entity_type'] == 2)
					{
						echo CHtml::link($data['callerType'], Yii::app()->createUrl("admin/vendor/view", ["id" => $data['scq_to_be_followed_up_with_entity_id']]), ["onclick" => "", 'target' => '_blank']);
					}
					else
					{
						echo $data['callerType'];
					}
					$cttlink = CHtml::link($data['contactName'], Yii::app()->createUrl("admin/contact/view", ["ctt_id" => $data['cttId']]), ["onclick" => "", 'target' => '_blank']);
					echo "<br>(" . $cttlink . ")";
				}, 'header' => 'FollowUp With'),
			array('name'	 => 'Follow up By Team', 'value'	 => function($data) {
					$teamarr = Teams::getList();
					echo $data['scq_to_be_followed_up_by_type'] == 1 ? $teamarr[$data['scq_to_be_followed_up_by_id']] : "";
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Follow up By Team'),
			array('name'	 => 'Follow up CSR Name', 'value'	 => function($data) {

					echo $admin_name = $data['gozen'] != null ? $data['gozen'] : "";
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Follow up CSR Name'),
			array('name'	 => 'Remarks ', 'value'	 => function($data) {
					echo $data['scq_disposition_comments'];
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Remarks'),
			array('name'	 => 'scq_create_date', 'value'	 => function($data) {
					echo date('Y-m-d H:i:s', strtotime($data['scq_create_date']));
				}, 'sortable'			 => true,
				'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Created on'),
			array('name'	 => 'scq_follow_up_date_time', 'value'	 => function ($data) {
					echo date('Y-m-d H:i:s', strtotime($data['scq_follow_up_date_time']));
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Due date'),
			array('name'	 => 'scq_related_bkg_id', 'value'	 => function($data) {
					if ($data['scq_related_bkg_id'] != null)
					{
						echo CHtml::link($data['scq_related_bkg_id'], Yii::app()->createUrl("admin/booking/view", ["id" => $data['scq_related_bkg_id']]), ["class" => "viewBooking", "onclick" => "", 'target' => '_blank']);
					}
				}, 'sortable'			 => true,
				'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Related Booking'),
			array('name'	 => 'scq_id', 'value'	 => function($data) {
					if ($data['scq_assigned_uid'] == null)
					{
						$selectedComplete	 = "";
						$disable			 = "";
						$statusVal			 = $data['scq_status'];
						if ($statusVal == 2)
						{
							$selectedComplete	 = "selected";
							$disable			 = "disabled";
						}
						$val = $data['scq_id'];
						echo '<select class="form-control" ' . $disable . ' name="event_id_' . $val . '" id="event_id_' . $val . '" onchange="actionFollow(' . $val . ');"><option value="">Select</option><option  value="2" ' . $selectedComplete . '>FollowUp Completed</option><option value="1">FollowUp Reschedule</option></select>';
					}
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Actions'),
		)
	));
}
?>



<script type="text/javascript">

	$(document).on('click', '.submitService', function () {


        var followupPerson = $("#requestedBy").val();
        var castId = $("#ServiceCallQueue_custId").val();
        var venId = $("#ServiceCallQueue_vendId").val();
        var drvId = $("#ServiceCallQueue_drvId").val();
        var admId = $("#ServiceCallQueue_adminId").val();
        var agentId = $("#ServiceCallQueue_agntId").val();
		var followupWith = $("#ServiceCallQueue_scq_to_be_followed_up_by_type").val();
		var teamId = $("#ServiceCallQueue_scq_to_be_followed_up_by_id").val();
		var isGozenId = $("#ServiceCallQueue_isGozen").val();
		
		
        if (followupPerson == 1) {
            if (castId == '') {
                bootbox.alert("Please select Customer name from customer dropdown");
                return false;
            }
        }
		if (followupPerson == 2) {
            if (venId == '') {
                bootbox.alert("Please select Vendor name from Vendor dropdown");
                return false;
            }
        }
		if (followupPerson == 3) {
            if (drvId == '') {
                bootbox.alert("Please select Driver name from Driver dropdown");
                return false;
            }
        }
		if (followupPerson == 4) {
            if (admId == '') {
                bootbox.alert("Please select Admin name from Admin dropdown");
                return false;
            }
        }
		if (followupPerson == 5) {
            if (agentId == '') {
                bootbox.alert("Please select Agent name from Agent dropdown");
                return false;
            }
        }
		if (followupWith == 1) {
            if (teamId == '') {
                bootbox.alert("Please select Team name from team dropdown");
                return false;
            }
        }
		if (followupWith == 2) {
            if (isGozenId == '') {
                bootbox.alert("Please select Gozen name from gozen dropdown");
                return false;
            }
        }
    });

    var isFollowUp = ($("input[name='ServiceCallQueue[isFollowUpOpen]']:checked").val());
    if (isFollowUp == 1)
    {
        $("input[name='ServiceCallQueue[isDue24]']").attr("disabled", false);
        $(".isDue24").show("slow");
    } else
    {
        $("input[name='ServiceCallQueue[isDue24]']").attr("disabled", true);
        $(".isDue24").hide("slow");
    }
    function actionFollow(followId)
    {
        var eventId = $("#event_id_" + followId).val();
        var isReSchedule = (eventId == 1) ? 1 : 0;
        if (eventId == 1)
        {
            bootbox.confirm("Are you sure you want to reschedule this followup to a later time?", function (result) {
                if (result)
                {
                    actionReschedule(followId);
                } else
                {
                    $("#event_id_" + followId).prop('selectedIndex', 0);
                    return;
                }
            });

        } else {
            bootbox.prompt({
                title: "Remarks",
                locale: 'custom',
                inputType: 'textarea',
                placeholder: 'Summarize how you disposed/solved the call in 1-2 sentences. Do not just say Done or solved',
                callback: function (result) {
                    if (result)
                    {
                        $href = "<?= Yii::app()->createUrl('admin/scq/registerlog') ?>";
                        jQuery.ajax({type: 'GET',
                            url: $href,
                            data: {"refId": followId,
                                "remarks": result,
                                "eventId": $("#event_id_" + followId).val(),
                                "flag": isReSchedule, },
                            success: function (data)
                            {
                                var obj = $.parseJSON(data);
                                if (obj.result == 1)
                                {
                                    location.reload();
                                }
                            }
                        });

                    } else {
                        $("#event_id_" + followId).prop('selectedIndex', 0);
                    }



                }
            });
        }


    }
    function actionReschedule(followId)
    {
        var isReschedule = 1;
        $href = "<?= Yii::app()->createUrl('admin/scq/CtrScq') ?>";
        // $href = "<?= Yii::app()->createUrl('admin/scq/reschedule') ?>";
        jQuery.ajax({type: 'GET',
            url: $href,
            data: {"scqId": followId, "isReschedule": isReschedule},
            success: function (data)
            {
                schedulebox = bootbox.dialog({
                    size: "large",
                    message: data,
                    title: 'Reschedule',
                    onEscape: function () {
                    }
                });

            }
        });
    }

    function changeStatus()
    {
        var isFollowUp = ($("input[name='ServiceCallQueue[isFollowUpOpen]']:checked").val());
        if (isFollowUp == 1)
        {
            $("input[name='ServiceCallQueue[isDue24]']").attr("disabled", false);
            $(".isDue24").show("slow");
        } else
        {
            $("input[name='ServiceCallQueue[isDue24]']").attr("disabled", true);
            $(".isDue24").hide("slow");
        }

    }
	
	$vendor = null;
    $gozenList = null;
    $vndList = null;
    $drvList = null;
    $adminList = null;
    $custList = null;
    $gozenList = null;
    $teamList = null;
    $followUp = new FollowUp();
    $('#requestedBy').change(function () {
        var person = $('#requestedBy').val();
        if (person > 0) {
            $("#requestedDrop").show("slow");
        }

        if (person == 1)
        {
            $("#followVendor").hide("slow");
            $("#followDriver").hide("slow");
            $("#followAgents").hide("slow");
            $("#followCustomer").show("slow");
            $("#followAdm").hide("slow");
            $("#ServiceCallQueue_vendId").val('').trigger('change');
            $("#ServiceCallQueue_drvId").val('').trigger('change');
            $("#ServiceCallQueue_adminId").val('').trigger('change');
            $("#ServiceCallQueue_agntId").val('').trigger('change');
        }
        if (person == 2)
        {
            $("#followVendor").show("slow");
            $("#followDriver").hide("slow");
            $("#followAgents").hide("slow");
            $("#followCustomer").hide("slow");
            $("#followAdm").hide("slow");
            $("#ServiceCallQueue_custId").val('').trigger('change');
            $("#ServiceCallQueue_drvId").val('').trigger('change');
            $("#ServiceCallQueue_adminId").val('').trigger('change');
            $("#ServiceCallQueue_agntId").val('').trigger('change');
        }
        if (person == 3)
        {
            $("#followVendor").hide("slow");
            $("#followDriver").show("slow");
            $("#followAgents").hide("slow");
            $("#followCustomer").hide("slow");
            $("#followAdm").hide("slow");
            $("#ServiceCallQueue_custId").val('').trigger('change');
            $("#ServiceCallQueue_vendId").val('').trigger('change');
            $("#ServiceCallQueue_adminId").val('').trigger('change');
            $("#ServiceCallQueue_agntId").val('').trigger('change');
        }
        if (person == 4)
        {
            $("#followVendor").hide("slow");
            $("#followDriver").hide("slow");
            $("#followAgents").hide("slow");
            $("#followCustomer").hide("slow");
            $("#followAdm").show("slow");
            $("#ServiceCallQueue_custId").val('').trigger('change');
            $("#ServiceCallQueue_vendId").val('').trigger('change');
            $("#ServiceCallQueue_drvId").val('').trigger('change');
            $("#ServiceCallQueue_agntId").val('').trigger('change');
        }
        if (person == 5)
        {
            $("#followVendor").hide("slow");
            $("#followDriver").hide("slow");
            $("#followAgents").show("slow");
            $("#followCustomer").hide("slow");
            $("#followAdm").hide("slow");
            $("#ServiceCallQueue_custId").val('').trigger('change');
            $("#ServiceCallQueue_vendId").val('').trigger('change');
            $("#ServiceCallQueue_drvId").val('').trigger('change');
            $("#ServiceCallQueue_adminId").val('').trigger('change');
        }
    });

    $('#ServiceCallQueue_scq_to_be_followed_up_by_type').change(function () {
        var disposed = $('#ServiceCallQueue_scq_to_be_followed_up_by_type').val();
        if (disposed == 1)
        {
            $("#followteam").show("slow");
            $("#followgozn").hide("slow");
            $("#ServiceCallQueue_isGozen").val('').trigger('change');
        }
        if (disposed == 2)
        {
            $("#followteam").hide("slow");
            $("#followgozn").show("slow");
            $("#ServiceCallQueue_scq_to_be_followed_up_by_id").val('').trigger('change');
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
    function populateGozen(obj, gozen)
    {
        $followUp.populateGozen(obj, gozen);
    }
    function loadGozen(query, callback)
    {
        $followUp.loadGozen(query, callback);
    }
	function populateTeams(obj, teamId)
    {
        $followUp.populateTeams(obj, teamId);
    }
    function loadTeams(query, callback)
    {
        $followUp.loadTeams(query, callback);
    }
	
</script>
