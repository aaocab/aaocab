<style>
    .table-flex {
        display: flex;
        flex-direction: column;
    }
    .tr-flex {
        display: flex;
    }
    .th-flex, .td-flex{
        flex-basis: 35%;
    }
    .thead-flex, .tbody-flex {
        overflow-y: scroll;
    }
    .tbody-flex {
        max-height: 250px;
    }
</style>
<?php
$customerDisplay	 = ($followUps->requestedBy > 0 && $followUps->custId != '') ? "" : "none";
$vendorDisplay		 = ($followUps->requestedBy > 0 && $followUps->vendId != '') ? "block" : "none";
$driverDisplay		 = ($followUps->requestedBy > 0 && $followUps->drvId != '') ? "block" : "none";
$adminDisplay		 = ($followUps->requestedBy > 0 && $followUps->adminId != '') ? "block" : "none";
$agentDisplay		 = ($followUps->requestedBy > 0 && $followUps->agntId != '') ? "block" : "none";
$gozenDisplay		 = ($followUps->scq_to_be_followed_up_by_type > 0 && $followUps->isGozen != '') ? "block" : "none";
$dropdownDisplay	 = ($followUps->requestedBy > 0) ? "block" : "none";
?>
<div class="row">
    <div class="col-xs-12  pb10">
        <a href="/admpnl/generalReport/scqreport" target="_blank"> Click To View  SCQ Report</a>
        <br>
        <a href="/admpnl/scq/cbrStaticalDetailsData?fromdate=<?php echo $followUps->from_date; ?>&todate=<?php echo $followUps->to_date; ?>" target="_blank" > Click To View CBR Statistical Data Report</a>
    </div>
</div>
<div class="row">
	<?php
	$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
		'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
		'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
		'openOnFocus'		 => true, 'preload'			 => false,
		'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
		'addPrecedence'		 => false,];
	?> 
	<?php
	$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
		'id'					 => 'booking-form',
		'enableClientValidation' => true,
		'method'				 => 'get',
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
    <div class='row p15'>

        <div class='row p15'>
            <!--			
                    <div class="col-xs-12 col-sm-2 col-md-2">
			<?
			//=
			$form->datePickerGroup($followUps, 'date', array('label'			 => 'Filter By Date',
				'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => '01/01/2021', 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'Filter By Date')), 'prepend'		 => '<i class="fa fa-calendar"></i>'));
			?>  
                    </div>
            -->
            <div class ="col-xs-12 col-sm-2 col-md-3">

                <div class="form-group">
                    <label  class="control-label">Filter By Date </label>
					<?php
					$daterang			 = "Select Date Range";
					if (isset($followUps->from_date) && $followUps->from_date != "")
					{
						$createdate1 = date("Y-m-d", strtotime($followUps->from_date));
					}
					else
					{
						$createdate1 = date("Y-m-d");
					}
					if (isset($followUps->to_date) && $followUps->to_date != "")
					{
						$createdate2 = date("Y-m-d", strtotime($followUps->to_date));
					}
					else
					{
						$createdate2 = date("Y-m-d");
					}
					if ($createdate1 != '' && $createdate2 != '')
					{
						$daterang = date('F d, Y', strtotime($createdate1)) . " - " . date('F d, Y', strtotime($createdate2));
					}
					?>
                    <div id="bkgCreateDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                        <span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
                    </div>
                    <input type="hidden" value="<?php echo $followUps->from_date; ?>" id="ServiceCallQueue_fromdate" name="ServiceCallQueue[fromdate]" >
                    <input type="hidden" value="<?php echo $followUps->to_date; ?>" id="ServiceCallQueue_todate" name="ServiceCallQueue[todate]" >									
                </div>

            </div>

            <div class="col-xs-12 col-sm-3 col-md-2"> 
                <div class="form-group">
                    <label class="control-label" style="margin-left:5px;">Search By Queue</label>
					<?php
					$queueTypeJson			 = Filter::getJSON(array("1" => "New Booking", "2" => "Existing Booking", "3" => " New Vendor Attachtment", "4" => "Existing Vendor", "5" => "Advocacy", "6" => "Driver", "7" => "Payment Followup", "8" => "Corporate Sales", "9" => "Service Requests", "10" => "SOS", "11" => "Penalty Dispute", "12" => "UpSell(CNG/Value)", "13" => "Vendor Advocacy", "14" => "Dispatch", "15" => "Vendor Approval", "16" => "New Lead Booking", "17" => "New Quote Booking", "18" => "B2B Post pickup", "19" => "Booking At Risk(Bar)", "20" => "New Lead Booking(International)", "21" => "New Quote Booking(International)", "22" => "FBG", "23" => "General Accounts", '24' => "Upsell(Value+/Select)", '25' => "Booking Complete Review", '26' => "Apps Help & Tech support", '27' => "Gozo Now", '29' => "Auto Lead Followup",'30' => "Document Approval", '31' => "Vendor Approval  Zone Based Inventory", '32' =>  "Critical and stress (risk) assignments(CSA)", '33' => "Airport DailyRental", '34' => "Last Min Booking", '35' => "High Price", '36' => "Driver NoShow", '37' => "Customer NoShow", '38' => "MMT Support", '39' => "Driver Car BreakDown", '40' => "Vendor Assign", '41' => "Cusomer Booking Cancel", '42' => "Spice Lead Booking", '43' => "Spice Quote Booking", '44' => "Spice Lead Booking International", '45' => "Spice Quote Booking International",'51' => "Booking Reschedule"));
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $followUps,
						'attribute'		 => 'queueType',
						'val'			 => $followUps->queueType,
						'asDropDownList' => FALSE,
						'options'		 => array('data' => new CJavaScriptExpression($queueTypeJson), 'allowClear' => true),
						'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Queue')
					));
					?>
                </div>
            </div>
            <div class="col-xs-12 col-sm-2 col-md-2"> 
                <div class="form-group">
                    <label class="control-label" style="margin-left:5px;">Search Event</label>
					<?php
					$eventTypeJson			 = Filter::getJSON(array("1" => "Create Today(Active/InActive)", "2" => "All Active", "3" => "Created Before Today(Active)", "4" => "Created Today(Active)", '5' => 'Assignable Now', '6' => 'Closed Today', '7' => 'Currently Assigned'));
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $followUps,
						'attribute'		 => 'event_id',
						'val'			 => $followUps->event_id,
						'asDropDownList' => FALSE,
						'options'		 => array('data' => new CJavaScriptExpression($eventTypeJson), 'allowClear' => true),
						'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Event')
					));
					?>
                </div>
            </div>
            <div class="col-xs-12 col-sm-2 col-md-2"> 
                <div class="form-group">
                    <label class="control-label" style="margin-left:5px;">Search Event By </label>
					<?php
					$followupPersonTypeJson	 = Filter::getJSON(array("1" => "All", "2" => "Customer", "3" => "Admin", "4" => "IVR"));
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $followUps,
						'attribute'		 => 'event_by',
						'val'			 => $followUps->event_by,
						'asDropDownList' => FALSE,
						'options'		 => array('data' => new CJavaScriptExpression($followupPersonTypeJson), 'allowClear' => true),
						'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Search Event By')
					));
					?>
                </div>
            </div>
            <div class="col-xs-12 col-sm-2 col-md-2">
                <label class="control-label">Filter By Assigned CSR</label>
				<?php
				$csrSearch				 = Admins::model()->employeesList(1);
				$this->widget('booster.widgets.TbSelect2', array(
					'model'			 => $followUps,
					'attribute'		 => 'csrSearch',
					'val'			 => $followUps->csrSearch,
					'data'			 => $csrSearch,
					'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => 'multiple',
						'placeholder'	 => 'Filter By Assigned CSR')
				));
				?> 
            </div>
            <div class="col-xs-12 col-sm-2 col-md-1">
                <div class="form-group"><label class="control-label" for="ServiceCallQueue_isCreated">Created By Me</label>
                    <div class="input-group">
                        <input class="form-control  ct-form-control " type="checkbox" value="<?php echo $followUps->isCreated; ?>" id="ServiceCallQueue_isCreated" name="ServiceCallQueue[isCreated]" <?php
						if ($followUps->isCreated > 0)
						{
							echo 'checked="checked"';
						}
						?> >
                    </div>
                    <div class="help-block error" id="ServiceCallQueue_isCreated_em_" style="display:none"></div>

                </div>  
            </div>
        </div>
		<div class="row p15">
			<div class="col-xs-12 col-sm-2 col-md-2">
                <div class="form-group">
                    <label class="control-label">Teams('Closed By' event only)</label>
					<?php
					$dataTeam			 = Teams::getList();
					$allArr[]			 = "All";
					$dataTeams			 = array_merge($allArr, $dataTeam);
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $followUps,
						'attribute'		 => 'scq_to_be_followed_up_by_id',
						'data'			 => $dataTeams,
						'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Team(s)')
					));
					?>
                </div> 
            </div>
			<div class="col-xs-12 col-sm-2 col-md-2">
				<div class="form-group">
					<label class="control-label">Requested By</label>
					<?php
					$filters			 = [
						0	 => 'Select Requested',
						1	 => 'Consumer',
						2	 => 'Vendor',
						3	 => 'Driver',
						4	 => 'Admin',
						5	 => 'Agent',
					];
					$dataPay			 = Filter::getJSON($filters);
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $followUps,
						'attribute'		 => 'requestedBy',
						'val'			 => $followUps->requestedBy,
						'asDropDownList' => FALSE,
						'options'		 => array('data' => new CJavaScriptExpression($dataPay), 'allowClear' => true),
						'htmlOptions'	 => array('class' => 'p0', 'style' => 'width:100%', 'placeholder' => 'Select Requested', 'id' => 'requestedBy')
					));
					?>	
				</div> 
			</div>
			<div class="col-xs-12 col-sm-2 col-md-2" id="requestedDrop" style="display: <?= $dropdownDisplay ?>;">
				<div id="followCust"  style="display: <?= $customerDisplay ?>">
					<label class="control-label">Search Customers</label>
					<?php
					$this->widget('ext.yii-selectize.YiiSelectize', array(
						'model'				 => $followUps,
						'attribute'			 => 'custId',
						'useWithBootstrap'	 => true,
						"placeholder"		 => "Select Customers",
						'fullWidth'			 => false,
						'htmlOptions'		 => array('width'	 => '100%',
							'id'	 => 'ServiceCallQueue_custId'
						),
						'defaultOptions'	 => $selectizeOptions + array(
					'onInitialize'	 => "js:function(){
											populateCustomer(this, '{$followUps->custId}');
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
						'model'				 => $followUps,
						'attribute'			 => 'vendId',
						'useWithBootstrap'	 => true,
						"placeholder"		 => "Select Vendors",
						'fullWidth'			 => false,
						'htmlOptions'		 => array('width'	 => '100%',
							'id'	 => 'ServiceCallQueue_vendId'
						),
						'defaultOptions'	 => $selectizeOptions + array(
					'onInitialize'	 => "js:function(){
                                            populateVnds(this, '{$followUps->vendId}');
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
						'model'				 => $followUps,
						'attribute'			 => 'drvId',
						'useWithBootstrap'	 => true,
						"placeholder"		 => "Select Drivers",
						'fullWidth'			 => false,
						'htmlOptions'		 => array('width'	 => '100%',
							'id'	 => 'ServiceCallQueue_drvId'
						),
						'defaultOptions'	 => $selectizeOptions + array(
					'onInitialize'	 => "js:function(){
                                            populateDrvs(this, '{$followUps->drvId}');
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
						'model'				 => $followUps,
						'attribute'			 => 'agntId',
						'useWithBootstrap'	 => true,
						"placeholder"		 => "Select Agents",
						'fullWidth'			 => false,
						'htmlOptions'		 => array('width'	 => '100%',
							'id'	 => 'ServiceCallQueue_agntId'
						),
						'defaultOptions'	 => $selectizeOptions + array(
					'onInitialize'	 => "js:function(){
                                            populatePartner(this, '{$followUps->agntId}');
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
						'model'				 => $followUps,
						'attribute'			 => 'adminId',
						'useWithBootstrap'	 => true,
						"placeholder"		 => "Select Admins",
						'fullWidth'			 => false,
						'htmlOptions'		 => array('width'	 => '100%',
							'id'	 => 'ServiceCallQueue_adminId'
						),
						'defaultOptions'	 => $selectizeOptions + array(
					'onInitialize'	 => "js:function(){
                                            populateAdmins(this, '{$followUps->adminId}');
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
					<label class="control-label">Disposed By</label>
					<?php
					$filters			 = [
						0	 => 'Select Disposed By',
						2	 => 'By a Gozen',
					];
					$dataPay			 = Filter::getJSON($filters);
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $followUps,
						'attribute'		 => 'scq_to_be_followed_up_by_type',
						'val'			 => $followUps->scq_to_be_followed_up_by_type,
						'asDropDownList' => FALSE,
						'options'		 => array('data' => new CJavaScriptExpression($dataPay), 'allowClear' => true),
						'htmlOptions'	 => array('class' => 'p0', 'style' => 'width:100%', 'placeholder' => 'Select Disposed By')
					));
					?>	
				</div> 
			</div>
			<div class="col-xs-12 col-sm-2 col-md-2">
				<div id="followgozn"  style="display: <?= $gozenDisplay ?>">
					<label class="control-label">Search Gozens</label>
					<?php
					$this->widget('ext.yii-selectize.YiiSelectize', array(
						'model'				 => $followUps,
						'attribute'			 => 'isGozen',
						'useWithBootstrap'	 => true,
						"placeholder"		 => "Select Gozens",
						'fullWidth'			 => false,
						'htmlOptions'		 => array('width'	 => '100%',
							'id'	 => 'ServiceCallQueue_isGozen'
						),
						'defaultOptions'	 => $selectizeOptions + array(
					'onInitialize'	 => "js:function(){
                                            populateGozen(this, '{$followUps->isGozen}');
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
        <div class='row p15'>
            <div class="col-xs-12 col-sm-2 col-md-2">   
                <label class="control-label"></label>
				<?php echo CHtml::button('Submit', array('class' => 'btn btn-primary full-width submitCbr')); ?>
            </div>


			<?php $this->endWidget(); ?>
			<?php
			$checkExportAccess	 = Yii::app()->user->checkAccess("Export");
			if ($checkExportAccess)
			{
				$persionEntityValue	 = Yii::app()->request->getParam('followupPersonEntity');
				$WithEntityTypeValue = Yii::app()->request->getParam('followupWithEntityType');
				echo CHtml::beginForm(Yii::app()->createUrl('admin/generalReport/cbrdetailsreport'), "post", ['style' => "margin-bottom: 10px;margin-top: 10px;"]);
				?>
				<div class="col-xs-12 col-sm-2 col-md-2">   
					<label class="control-label"></label>
					<input type="hidden" id="export1" name="export1" value="true"/>
					<input type="hidden" id="export_isCreated" name="export_isCreated" value="<?= $followUps->isCreated ?>"/>
					<input type="hidden" id="export_csrId" name="export_csrId" value="<?= implode(',', $followUps->csrSearch) ?>"/>
					<input type="hidden" id="export_date" name="export_date" value="<?= $followUps->date ?>"/>
					<input type="hidden" id="export_fromdate" name="export_fromdate" value="<?= $followUps->from_date ?>"/>
					<input type="hidden" id="export_todate" name="export_todate" value="<?= $followUps->to_date ?>"/>
					<input type="hidden" id="export_event_id" name="export_event_id" value="<?= $followUps->event_id ?>"/>
					<input type="hidden" id="export_event_by" name="export_event_by" value="<?= $followUps->event_by ?>"/>
					<input type="hidden" id="export_queueType" name="export_queueType" value="<?= $followUps->queueType ?>"/>
					<input type="hidden" id="export_teamId" name="export_teamId" value="<?= $followUps->scq_to_be_followed_up_by_id ?>"/>
					<input type="hidden" id="export_requested" name="export_requested" value="<?= $followUps->requestedBy ?>"/>
					<input type="hidden" id="export_disposed" name="export_disposed" value="<?= $followUps->scq_to_be_followed_up_by_type ?>"/>
					<input type="hidden" id="export_followupPersonEntity" name="export_followupPersonEntity" value="<?= $persionEntityValue ?>"/>
					<input type="hidden" id="export_followupWithEntityType" name="export_followupWithEntityType" value="<?= $WithEntityTypeValue ?>"/>
					<button class="btn btn-default" type="submit" style="width: 185px;">Export Below Table</button>
				</div>
				<?php
				echo CHtml::endForm();
			}
			?>
        </div>
    </div>

</div>
<?php
if (!empty($dataProvider))
{

	$params									 = array_filter($_REQUEST);
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
			array('name'	 => 'FollowupId', 'value'	 => function ($data) {
					echo CHtml::link($data["FollowupId"], Yii::app()->createUrl("admin/scq/view", ["id" => $data["FollowupId"]]), ['target' => '_blank']);
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Followup ID'),
			array('name'	 => 'CustomerContactId', 'value'	 => function ($data) {

					if ($data['CustomerContactId'] > 0)
					{
						$contacName				 = Contact::model()->findByPk($data['CustomerContactId'])->ctt_name;
						$contactProfileDetails	 = ContactProfile::getCodeByCttId($data['CustomerContactId']);
						echo CHtml::link($contacName . " ( " . $data['CustomerContactId'] . " )", Yii::app()->createUrl("admin/contact/form", ["ctt_id" => $data['CustomerContactId']]), ["onclick" => "", 'target' => '_blank']);
						if ($contactProfileDetails['cr_is_partner'] != null)
						{
							echo "<br>" . CHtml::link("AGT00" . $contactProfileDetails['cr_is_partner'], Yii::app()->createUrl("admin/agent/form", ["agtid" => $contactProfileDetails['cr_is_partner']]), ["onclick" => "", 'target' => '_blank']);
						}
						if ($contactProfileDetails['drv_code'] != null)
						{
							echo "<br>" . CHtml::link($contactProfileDetails['drv_code'], Yii::app()->createUrl("admin/driver/profile", ["id" => $contactProfileDetails['cr_is_driver']]), ["onclick" => "", 'target' => '_blank']);
						}
						if ($contactProfileDetails['vnd_code'] != null)
						{
							echo "<br>" . CHtml::link($contactProfileDetails['vnd_code'], Yii::app()->createUrl("admin/vendor/profile", ["id" => $contactProfileDetails['cr_is_vendor']]), ["onclick" => "", 'target' => '_blank']);
						}
					}
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Customer Contact Id'),
			array('name'	 => 'ItemID', 'value'	 => function ($data) {
					if ($data['ItemID'] != null)
					{
						echo CHtml::link($data['ItemID'], Yii::app()->createUrl("admpnl/booking/view", ["id" => $data['ItemID']]), ['target' => '_blank']);
					}
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Booking ID'),
			array('name'	 => 'Show Images', 'value'	 => function ($data) {
					$docImages			 = CallBackDocuments::model()->findByAttributes(['cbd_scq_id' => $data["FollowupId"], 'cbd_active' => 1]);	
					if ($docImages != NULL)
					{
						echo CHtml::link("Show Images", Yii::app()->createUrl("admpnl/scq/ServiceCallBackDoc", ["id" => $data["FollowupId"]]), ['target' => '_blank']);
					}
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Images Link'),
			array('name'	 => 'QueueType', 'value'	 => function ($data) {
					echo $data['followUpType'];
					$jsonDecode			 = json_decode($data['scq_additional_param']);
					$DriverAppNotUsed	 = $jsonDecode->DriverAppNotUsed;
					if ($DriverAppNotUsed == 1)
					{
						echo "<br>(<b>Driver App Not Used</b>)";
					}
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Queue Type'),
			array('name'	 => 'csrName', 'value'	 => function ($data) {
					if ($data['csrName'] != null)
					{
						$teamDetails = Teams::getDetails($data['empCode']);
						echo $data['csrName'] . " (" . $teamDetails['tea_name'] . "/" . $data['empCode'] . ")";
					}
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Assigned CSR<br>(Team/Employee ID)'),
			array('name'	 => 'createDate', 'value'	 => function ($data) {
					echo $data['createDate'] . "<br>";
					if ($data['scq_created_by_type'] == 1)
					{
						echo $data['usr_name'] . "<br>";
					}
					else if ($data['scq_created_by_type'] == 2)
					{
						echo $data['vnd_name'] . "<br>";
					}
					else if ($data['scq_created_by_type'] == 3)
					{
						echo $data['drv_name'] . "<br>";
					}
					else if ($data['scq_created_by_type'] == 4)
					{
						if ($data['CreatedCsrName'] != null)
						{
							$teamDetails = Teams::getDetails($data['CreatedCsrempCode']);
							echo $data['CreatedCsrName'] . " (" . $teamDetails['tea_name'] . "/" . $data['CreatedCsrempCode'] . ")";
						}
					}
					?>
					<a href="#" data-toggle="tooltip" data-placement="top" title="<?php echo $data['scq_creation_comments'] ?>"><i class="fa fa-info" aria-hidden="true"></i></a>
					<?php
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Created Date (CSR)'),
			array('name'	 => 'followUpdDate', 'value'	 => function ($data) {
					echo $data['followUpdDate'];
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'FollowUp Date'),
			array('name'	 => 'assignedDate', 'value'	 => function ($data) {
					echo $data['assignedDate'];
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Assign Date'),
			array('name'	 => 'time_toassigned', 'value'	 => function ($data) {
					if (($data['followUpdDate'] != null) && ($data['assignedDate'] != null))
					{
						$to_time	 = strtotime($data['assignedDate']);
						$from_time	 = strtotime($data['followUpdDate']);
						$mintue		 = round(abs($to_time - $from_time) / 60, 2);
						echo Filter::getDurationbyMinute($mintue, 1);
					}
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Time to Assign'),
			array('name'	 => 'assignedDate', 'value'	 => function ($data) {
					echo $data['currentAssignmentCount'];
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Ready to assign count'),
			array('name'	 => 'closedDate', 'value'	 => function ($data) {
					if ($data['closedDate'] != null)
					{
						$teamDetails = Teams::getDetails($data['ClosedCsrempCode']);
						echo $data['closedDate'] . "<br>";
						echo $data['ClosedCsrName'] . " (" . $teamDetails['tea_name'] . "/" . $data['ClosedCsrempCode'] . ")";
						?>
						<a href="#" data-toggle="tooltip" data-placement="top" title="<?php echo $data['scq_disposition_comments'] ?>"><i class="fa fa-info" aria-hidden="true"></i></a>
						<?php
					}
					else
					{
						if ($data['scq_disposition_comments'] != null)
						{
							if ($data['scq_created_by_type'] == 1)
							{
								echo $data['usr_name'] . "<br>";
							}
							else if ($data['scq_created_by_type'] == 2)
							{
								echo $data['vnd_name'] . "<br>";
							}
							else if ($data['scq_created_by_type'] == 3)
							{
								echo $data['drv_name'] . "<br>";
							}
							?>
							<a href="#" data-toggle="tooltip" data-placement="top" title="<?php echo $data['scq_disposition_comments'] ?>"><i class="fa fa-info" aria-hidden="true"></i></a>
							<?php
						}
					}
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Closed Date (CSR)'),
			array('name'	 => 'onlineTime', 'value'	 => function ($data) {
					if (($data['closedDate'] != null) && ($data['assignedDate'] != null))
					{
						$to_time	 = strtotime($data['closedDate']);
						$from_time	 = strtotime($data['assignedDate']);
						$mintue		 = round(abs($to_time - $from_time) / 60, 2);
						echo Filter::getDurationbyMinute($mintue, 1);
					}
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Time to Close'),
		)
	));
}
?>

<script type="text/javascript">
    $(document).ready(function () {
        $(document).on('click', '.submitCbr', function () {
            var queueType = $("#ServiceCallQueue_queueType").val();
            var event_id = $("#ServiceCallQueue_event_id").val();
            var event_by = $("#ServiceCallQueue_event_by").val();
            var csrSearch = $("#ServiceCallQueue_csrSearch").val();
            var isCreated = $("#ServiceCallQueue_isCreated").val();
            var fromdate = $("#ServiceCallQueue_fromdate").val();
            var todate = $("#ServiceCallQueue_todate").val();
            var teamId = $("#ServiceCallQueue_scq_to_be_followed_up_by_id").val();
            var followupPerson = $("#requestedBy").val();
            var followupWith = $("#ServiceCallQueue_scq_to_be_followed_up_by_type").val();
            var gozenId = $("#ServiceCallQueue_isGozen").val();

            var followupPersonEntity = 0;
            if (followupPerson == 1)
            {
                followupPersonEntity = $("#ServiceCallQueue_custId").val() != "" ? $("#ServiceCallQueue_custId").val() : 0;
            } else if (followupPerson == 2)
            {
                followupPersonEntity = $("#ServiceCallQueue_vendId").val() != "" ? $("#ServiceCallQueue_vendId").val() : 0;
            } else if (followupPerson == 3)
            {
                followupPersonEntity = $("#ServiceCallQueue_drvId").val() != "" ? $("#ServiceCallQueue_drvId").val() : 0;
            } else if (followupPerson == 4)
            {
                followupPersonEntity = $("#ServiceCallQueue_adminId").val() != "" ? $("#ServiceCallQueue_adminId").val() : 0;
            } else if (followupPerson == 5)
            {
                followupPersonEntity = $("#ServiceCallQueue_agntId").val() != "" ? $("#ServiceCallQueue_agntId").val() : 0;
            }
            var followupWithEntityType = 0;
            if (followupWith == 1)
            {
                followupWithEntityType = $("#ServiceCallQueue_scq_to_be_followed_up_by_id").val() != "" ? $("#ServiceCallQueue_scq_to_be_followed_up_by_id").val() : 0;
            } else if (followupWith == 2)
            {
                followupWithEntityType = $("#ServiceCallQueue_isGozen").val() != "" ? $("#ServiceCallQueue_isGozen").val() : 0;
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
                        bootbox.alert("Please select Admin name from Admin dropdown");
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
            if (followupWithEntityType == 0)
            {
                switch (parseInt(followupWith)) {
                    case 1:
                        bootbox.alert("Please select Team name from team dropdown");
                        break;
                    case 2:
                        bootbox.alert("Please select Gozen name from gozen dropdown");
                        break;
                    default:
                }
                if (followupWith > 0)
                {
                    return false;
                }
            }
            window.location.href = '/admpnl/generalReport/cbrdetailsreport/?queueType=' + queueType + '&event_id=' + event_id + '&event_by=' + event_by + "&csrId=" + csrSearch + "&teamId=" + teamId + "&isCreated=" + isCreated + "&fromdate=" + fromdate + "&todate=" + todate + "&followupPerson=" + followupPerson + "&followupPersonEntity=" + followupPersonEntity + "&followupWith=" + followupWith + "&followupWithEntityType=" + followupWithEntityType;
        });

        $(document).on('click', '#ServiceCallQueue_isCreated', function () {
            var queueType = $("#ServiceCallQueue_isCreated").val() == 1 ? 0 : 1;
            $("#ServiceCallQueue_isCreated").val(queueType);

        });

    });
    var start = '<?= date("d/m/Y", strtotime($createdate1)); ?>';
    var end = '<?= date("d/m/Y", strtotime($createdate2)); ?>';

    $('#bkgCreateDate').daterangepicker(
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
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                }
            }, function (start1, end1) {
        $('#ServiceCallQueue_fromdate').val(start1.format('YYYY-MM-DD'));
        $('#ServiceCallQueue_todate').val(end1.format('YYYY-MM-DD'));
        $('#bkgCreateDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
    });
    $('#bkgCreateDate').on('cancel.daterangepicker', function (ev, picker) {
        $('#bkgCreateDate span').html('Select Booking Date Range');
        $('#ServiceCallQueue_fromdate').val('');
        $('#ServiceCallQueue_todate').val('');
    });

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

    $('#ServiceCallQueue_scq_to_be_followed_up_by_type').change(function () {
        var disposed = $('#ServiceCallQueue_scq_to_be_followed_up_by_type').val();
        if (disposed == 1)
        {
            $("#followteam").show("slow");
            $("#followgozn").hide("slow");
        }
        if (disposed == 2)
        {
            $("#followteam").hide("slow");
            $("#followgozn").show("slow");
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