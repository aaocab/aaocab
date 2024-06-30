<style>
    .search-form ul{
        list-style: none ;
        margin-bottom: 20px;
        vertical-align: bottom
    }
    .search-form ul li{
        padding: 0;
    }
    .table{
        margin-bottom: 5px;
    }
    .pagination {
        margin: 0;
    }
.modal{ overflow: auto;}
</style>

<?php
#Yii::app()->session['agt_type']	 = $model->agt_type;
$form							 = $this->beginWidget('booster.widgets.TbActiveForm', array(
	'id'					 => 'driver-register-form', 'enableClientValidation' => FALSE,
	'clientOptions'			 => array(
		'validateOnSubmit'	 => true,
		'errorCssClass'		 => 'has-error'
	),
	'enableAjaxValidation'	 => false,
	'errorMessageCssClass'	 => 'help-block',
	'htmlOptions'			 => array(
		'class'			 => 'form-horizontal', 'enctype'		 => 'multipart/form-data', 'autocomplete'	 => "off",
	),
		));
/* @var $form TbActiveForm */
?>
<div class="row">
    <div class="col-xs-12">
        <div class="col-xs-3 ml15 mr15">
			<label>Channel Partner Types</label>
			<?#= $form->dropDownListGroup($model, 'agt_type', ['label' => '', 'widgetOptions' => ['data' => ['-1' => 'All'] + $model->getAgentType(), 'htmlOptions' => []]]) ?>
			<?php
			$agentTypesArr	 = $model->getAgentType();
			$this->widget('booster.widgets.TbSelect2', array (
				'model'			 => $model,
				'attribute'		 => 'agt_type',
				'val'			 => $model->agt_type,
				'data'			 => $agentTypesArr,
				'htmlOptions'	 => array (
					'style'			 => 'width:100%', 
					'multiple'		 => 'multiple',
					'placeholder'	 => 'Partner Types'
				)
			));
			?>
		</div>
		<div class="col-xs-12 col-sm-2 col-md-2">
			<label class="control-label">Status</label>
			<?php
			$filters						 = [
				1	 => 'Active',
				2	 => 'Inactive',
			];
			$dataPay						 = Filter::getJSON($filters);
			$this->widget('booster.widgets.TbSelect2', array(
				'model'			 => $model,
				'attribute'		 => 'agt_active',
				'val'			 => $model->agt_active,
				'asDropDownList' => FALSE,
				'options'		 => array('data' => new CJavaScriptExpression($dataPay), 'allowClear' => true),
				'htmlOptions'	 => array('class' => 'p0', 'style' => 'width:100%', 'placeholder' => 'Select Status')
			));
			?>
		</div>
		<div class="col-xs-12 col-sm-3 col-md-3">
			<label class="control-label">Created Date</label>
			<?php
			$daterang						 = "Select Created Date Range";
			$createDate1					 = ($model->createDate1 == '') ? '' : $model->createDate1;
			$createDate2					 = ($model->createDate2 == '') ? '' : $model->createDate2;
			if ($createDate1 != '' && $createDate2 != '')
			{
				$daterang = date('F d, Y', strtotime($createDate1)) . " - " . date('F d, Y', strtotime($createDate2));
			}
			?>
			<div id="createdDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
				<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
				<span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
			</div>
			<?= $form->hiddenField($model, 'createDate1'); ?>
			<?= $form->hiddenField($model, 'createDate2'); ?>
		</div>
        <div class="col-xs-1 mt20 p5 ml10"><?= CHtml::submitButton('Submit', array('class' => 'btn btn-primary full-width')); ?></div>
    </div>
</div>
<?php $this->endWidget(); ?>

<div class="row"> 
    <div class=" col-xs-12 ">
        <a class="btn btn-primary mb10" href="<?= Yii::app()->createUrl('admin/agent/form') ?>" style="text-decoration: none; ">Add new</a>
		<?php
		$accmanagername = (Admins::model()->getAdminList());
		//$dd = Admins::model()->getAdminById($data->agtAdmin->adm_fname);

		if (!empty($dataProvider))
		{

			$this->widget('booster.widgets.TbGridView', array(
				'id'				 => 'agent-grid',
				'responsiveTable'	 => true,
				'filter'			 => $model,
				'dataProvider'		 => $dataProvider,
				'template'			 => "<div class='panel-heading'><div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
				'itemsCssClass'		 => 'table table-striped table-bordered mb0',
				'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary'),
				'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
				'columns'			 => array(
//										array('name' => 'agt_fname', 'filter' => CHtml::activeTextField($model, 'agt_fname', array('class' => 'form-control', 'placeholder' => 'Search by ' . $model->getAttributeLabel('agt_fname'))), 'value' => '$data["agt_fname"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => $model->getAttributeLabel('agt_fname')),
//										array('name' => 'agt_lname', 'filter' => CHtml::activeTextField($model, 'agt_lname', array('class' => 'form-control', 'placeholder' => 'Search by ' . $model->getAttributeLabel('agt_lname'))), 'value' => '$data["agt_lname"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => $model->getAttributeLabel('agt_lname')),

					array('name'	 => 'agt_company', 'filter' => CHtml::activeTextField($model, 'agt_company', array('class' => 'form-control', 'placeholder' => 'Search by ' . $model->getAttributeLabel('agt_company'))),
						'value'	 => function ($data) {
							if ($data["agt_type"] == 1)
							{
								$suffix = '<br><span class="label label-warning">' . "Corporate Buyer" . '</span>';
							}
							else if ($data["agt_type"] == 2)
							{
								$suffix = '<br><span class="label label-info">' . "Authorized Reseller" . '</span>';
							}
							else
							{
								$suffix = '<br><span class="label label-primary">' . "Travel Agent" . '</span>';
							}
							$name = $data["agt_fname"] . '' . $data["agt_lname"];
							$orgName =  ($data["agt_company"] == "" ? $name : $data["agt_company"]);
							echo CHtml::link($orgName, Yii::app()->createUrl("admin/agent/view", ["agent" => $data['agt_id']]), ["class" => "", 'target' => '_blank']);
							echo $suffix;
							echo ($data['agt_code'] != '') ? "<br> ( " . $data['agt_code'] . " )" : "";
						}, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => $model->getAttributeLabel('agt_company')),
					array('name'	 => 'agt_owner_name', 'filter' => CHtml::activeTextField($model, 'agt_owner_name', array('class' => 'form-control', 'placeholder' => 'Search by ' . $model->getAttributeLabel('agt_owner_name'))),
						'value'	 => function ($data) {
							echo CHtml::link($data['agt_owner_name'], Yii::app()->createUrl("admin/agent/view", ["agent" => $data['agt_id']]), ["class" => "viewAccount", "onclick" => "return viewDetails(this)"]);
						}, 'sortable'			 => true, 'type'				 => 'raw', 'headerHtmlOptions'	 => array(), 'header'			 => $model->getAttributeLabel('agt_owner_name')),
					//array('name' => 'agt_fname', 'filter' => CHtml::activeTextField($model, 'agt_fname', array('class' => 'form-control', 'placeholder' => 'Search by ' . $model->getAttributeLabel('agt_fname'))), 'value' => '$data["agt_fname"]." ".$data["agt_lname"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Name'), 
					array('name' => 'adm_fname', 'filter' => CHtml::activeTextField($model, 'adm_fname', array('class' => 'form-control', 'placeholder' => 'Search by Account Manager Name')), 'value' => function($data){ echo $data["adm_fname"] ." ".$data["adm_lname"];}, 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Account Manager Name'),
					array('name' => 'agt_phone', 'filter' => CHtml::activeTextField($model, 'agt_phone', array('class' => 'form-control', 'placeholder' => 'Search by ' . $model->getAttributeLabel('agt_phone'))), 'value' => '$data["agt_phone"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => $model->getAttributeLabel('agt_phone')),
					array('name' => 'agt_credit_limit', 'filter' => FALSE, 'value' => '$data["agt_credit_limit"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => $model->getAttributeLabel('agt_credit_limit')),
					array('name' => 'agt_effective_credit_limit', 'filter' => FALSE, 'value' => '$data["agt_effective_credit_limit"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => $model->getAttributeLabel('agt_effective_credit_limit')),
					array('name' => 'agt_email', 'filter' => CHtml::activeTextField($model, 'agt_email', array('class' => 'form-control', 'placeholder' => 'Search by Email ID')), 'value' => '$data["agt_email"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => $model->getAttributeLabel('agt_email')),
					array('name'	 => 'agt_active', 'filter' => CHtml::activeDropDownList($model, 'agt_active', array('1' => 'On', '2' => 'Off'), ['class' => 'form-control']), 'value'	 => function ($data) {
							if ($data['agt_active'] == 1)
							{
								echo "On";
							}
							else
							{
								echo "Off";
							}
						}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => $model->getAttributeLabel('agt_active')),
					array('name'	 => 'agt_approved', 'filter' => CHtml::activeDropDownList($model, 'agt_approved', array('0' => 'Unapproved', '1' => 'Approved', '2' => 'Rejected'), ['class' => 'form-control']), 'value'	 => function ($data) {
							if ($data[agt_approved] == 1)
							{
								echo "Approved";
							}
							else if ($data[agt_approved] == 2)
							{
								echo "Rejected";
							}
							else
							{
								echo "Unapproved";
							}
						}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1', 'title' => "U = Unapproved , A = Approved, R = Rejected"), 'header'			 => $model->getAttributeLabel('status')),
					array('name' => 'agt_commission', 'filter' => FALSE, 'value' => '$data["agt_commission"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'htmlOptions' => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'), 'header' => 'Commission Amount'),
					array('name' => 'agt_create_date', 'filter' => FALSE, 'value' => 'date("d/M/Y h:i A", strtotime($data["agt_create_date"]))', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => $model->getAttributeLabel('agt_create_date')),
					array(
						'header'			 => 'Action',
						'class'				 => 'CButtonColumn',
						'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
						'template'			 => '{showaccount}{edit}{booking_history}<br>{active}{log}{settings}{inactive}{approve}<br>{disapprove}{agttype}{delete}{linkuser}', //hide{credit_history}{changeAgentType}
						'buttons'			 => array(
							'showaccount'		 => array(
								'url'		 => 'Yii::app()->createUrl("admin/agent/ledgerbooking", array("agtId" => $data["agt_id"]))',
								'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\gozocoins.png',
								'label'		 => '<i class="fa fa-check"></i>',
								'options'	 => array('style' => '', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'accdetails btn btn-xs p0', 'title' => 'Agent Account'),
							),
							'edit'				 => array(
								'url'		 => '($data[agt_type]==1) ? Yii::app()->createUrl("admin/agent/corporateform", array("crpId" => $data["agt_id"])) : Yii::app()->createUrl("admin/agent/form", array("agtid" => $data["agt_id"]))',
								'imageUrl'	 => Yii::app()->request->baseUrl . '/images/icon/edit_booking.png',
								'label'		 => '<i class="fa fa-edit"></i>',
								'options'	 => array('style' => '', 'class' => 'btn btn-xs conEdit p0', 'title' => 'Edit Model'),
							),
//                            'credit_history' => array(
//                                'click' => 'function(){
//                                                                var href = $(this).attr(\'href\');
//                                                                $.ajax({
//                                                                       "type": "GET",
//                                                                       "dataType": "html",
//                                                                       "url": href,
//                                                                       "success": function (data)
//                                                                       {
//                                                                           bootbox.dialog({
//                                                                               message: data,
//                                                                               className: "bootbox-xs",
//                                                                               title: "Partner Credit History",
//                                                                               size: "large",
//                                                                               callback: function () {
//
//                                                                               }
//                                                                           });
//                                                                       }
//                                                                   });
//                                                                   return false;
//                                                                 }',
//                                'url' => 'Yii::app()->createUrl("admin/agent/credithistory", array("agent" => $data["agt_id"]))',
//                                'imageUrl' => Yii::app()->request->baseUrl . '/images/icon/agent_list/credit_history.png',
//                                'label' => '<i class="fa fa-file"></i>',
//                                'options' => array('style' => '', 'class' => 'btn btn-xs co p0', 'title' => 'Credit History'),
//                            ),
							'booking_history'	 => array(
								'click'		 => 'function(){
                                                                var href = $(this).attr(\'href\');
                                                                $.ajax({
                                                                       "type": "GET",
                                                                       "dataType": "html",
                                                                       "url": href,
                                                                       "success": function (data)
                                                                       {
                                                                           bootbox.dialog({
                                                                               message: data,
                                                                               className: "bootbox-xs",
                                                                               title: "Partner Booking History",
                                                                               size: "large",
                                                                               callback: function () {

                                                                               }
                                                                           });
                                                                       }
                                                                   });
                                                                   return false;
                                                                 }',
								'url'		 => 'Yii::app()->createUrl("admin/agent/bookinghistory", array("agent" => $data["agt_id"]))',
								'imageUrl'	 => Yii::app()->request->baseUrl . '/images/icon/agent_list/agent_booking_history.png',
								'label'		 => '<i class="fa fa-file"></i>',
								'options'	 => array('style' => '', 'class' => 'btn btn-xs booking p0', 'title' => 'Booking History'),
							),
							'log'				 => array(
								'click'		 => 'function(){
                                                    $href = $(this).attr(\'href\');
                                                    jQuery.ajax({type: \'GET\',
                                                    url: $href,
                                                    success: function (data)
                                                    {

                                                        var box = bootbox.dialog({
                                                            message: data,
                                                            title: \'Agent Log\',
                                                            onEscape: function () {

                                                                // user pressed escape
                                                            }
                                                        });
                                                    }
                                                });
                                                    return false;
                                                    }',
								'url'		 => 'Yii::app()->createUrl("admin/agent/showlog", array("agtid" => $data["agt_id"]))',
								'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\rate_list\show_log.png',
								'label'		 => '<i class="fa fa-list"></i>',
								'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'class' => 'btn btn-xs conshowlog p0', 'title' => 'Show Log'),
							),
							'settings'			 => array(
								'url'		 => 'Yii::app()->createUrl("admin/agent/settings", array("agtid" => $data["agt_id"]))',
								'label'		 => '<i class="fa fa-cog" aria-hidden="true"></i>',
								'options'	 => array('style' => '', 'class' => 'btn btn-xs conEdit p5', 'title' => 'Setting'),
							),
//							'resetpassword'		 => array(
//								'click'		 => 'function(){
//                                                                            var href = $(this).attr(\'href\');
//                                                                            $.ajax({
//                                                                                   "type": "GET",
//                                                                                   "dataType": "html",
//                                                                                   "url": href,
//                                                                                   "success": function (data)
//                                                                                   {
//                                                                                       bootbox.dialog({
//                                                                                           message: data,
//                                                                                           className: "bootbox-xs",
//                                                                                           title: "Reset Password",
//                                                                                           size: "small",
//                                                                                           callback: function () {
//                                                                                              
//                                                                                           }
//                                                                                       });
//                                                                                   }
//                                                                               });
//                                                                               return false;
//                                                                 }',
//								'url'		 => 'Yii::app()->createUrl("admin/agent/changepassword", array("agent" => $data["agt_id"]))',
//								'label'		 => '<i class="fa fa-key" aria-hidden="true"></i>',
//								'options'	 => array('style' => ' ', 'class' => 'btn btn-xs agtResetPass p5', 'title' => 'Reset Password'),
//							),
							'active'			 => array(
								'click'		 => 'function(){
                                                            var con = confirm("Are you sure you want to deactivate this partner?"); 
                                                              if(con){
                                                                    $href = $(this).attr(\'href\');
                                                                    $.ajax({
                                                                        url: $href,
                                                                        dataType: "json",
                                                                        success: function(result){
                                                                                if(result.success){
                                                                                        $(\'#agent-grid\').yiiGridView(\'update\');
                                                                                }else{
                                                                                        alert(\'Sorry error occured\');
                                                                                }

                                                                        },
                                                                        error: function(xhr, status, error){
                                                                                alert(\'Sorry error occured\');
                                                                        }
                                                                    });
                                                                    }
                                                                    return false;
                                                    }',
								'url'		 => 'Yii::app()->createUrl("aaohome/agent/changestatus", array("agt_id" => $data[agt_id],"agt_active"=>1))',
								'imageUrl'	 => Yii::app()->request->baseUrl . '/images/icon/active.png',
								'visible'	 => '$data[agt_active] == 1',
								'label'		 => '<i class="fa fa-toggle-on"></i>',
								'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs conÉnable p0', 'title' => 'Active')
							),
							'inactive'			 => array(
								'click'		 => 'function(){
                                                            var con = confirm("Are you sure you want to activate this partner?"); 
                                                              if(con){
                                                                    $href = $(this).attr(\'href\');
                                                                    $.ajax({
                                                                        url: $href,
                                                                        dataType: "json",
                                                                        success: function(result){
                                                                                if(result.success){
                                                                                        $(\'#agent-grid\').yiiGridView(\'update\');
                                                                                }else{
                                                                                        alert(\'Sorry error occured\');
                                                                                }
                                                                        },
                                                                        error: function(xhr, status, error){
                                                                                alert(\'Sorry error occured\');
                                                                        }
                                                                    });
                                                                    }
                                                                    return false;
                                                    }',
								'url'		 => 'Yii::app()->createUrl("aaohome/agent/changestatus", array("agt_id" => $data[agt_id],"agt_active"=>2))',
								'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\agent_list\inactive.png',
								'visible'	 => '$data[agt_active] == 2',
								'label'		 => '<i class="fa fa-toggle-off"></i>',
								'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs conDelete p0', 'title' => 'Inactive'),
							),
							'approve'			 => array(
								'click'		 => 'function(){
                                                            var con = confirm("Are you sure you want to approve this partner?"); 
                                                              if(con){
                                                                    $href = $(this).attr(\'href\');
                                                                    $.ajax({
                                                                        url: $href,
                                                                        dataType: "html",
                                                                        success: function(result){
                                                                        
                                                                                    bootbox.dialog({
                                                                                         message: result,
                                                                                         className: "bootbox-xs",
                                                                                         title: "Partner Approval",
                                                                                         size: "large",
                                                                                         callback: function () {

                                                                                         }
                                                                                     });

                                                                       
//                                                                                if(result.success){
//                                                                                        $(\'#agent-grid\').yiiGridView(\'update\');
//                                                                                }else{
//                                                                                 errorMsg=(result.msg=="")?"Sorry error occured":result.msg;
//                                                                                   alert(errorMsg);
//                                                                                }

                                                                        },
                                                                        error: function(xhr, status, error){
                                                                                alert(\'Sorry error occured\');
                                                                        }
                                                                    });
                                                                    }
                                                                    return false;
                                                    }',
								'url'		 => 'Yii::app()->createUrl("aaohome/agent/approve", array("agt_id" => $data[agt_id],"agt_approve"=>1))',
								//'imageUrl' => Yii::app()->request->baseUrl . '\images\icon\inactive.png',
								'visible'	 => '($data[agt_approved]==2 || $data[agt_approved]==0)',
								'label'		 => '<i class="fa fa-times"></i>',
								'options'	 => array('data-toggle' => 'ajaxModal', 'style' => 'padding: 4px', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs  conApprove', 'title' => 'Approve')
							),
							'disapprove'		 => array(
								'click'		 => 'function(){
                                                            var con = confirm("Are you sure you want to disapprove this partner?"); 
                                                              if(con){
                                                                    $href = $(this).attr(\'href\');
                                                                    $.ajax({
                                                                        url: $href,
                                                                        dataType: "html",
                                                                        success: function(result){
                                                                                bootbox.dialog({
                                                                                         message: result,
                                                                                         className: "bootbox-xs",
                                                                                         title: "Partner Approval",
                                                                                         size: "large",
                                                                                         callback: function () {

                                                                                         }
                                                                                     });

                                                                        },
                                                                        error: function(xhr, status, error){
                                                                                alert(\'Sorry error occured\');
                                                                        }
                                                                    });
                                                                    }
                                                                    return false;
                                                    }',
								'url'		 => 'Yii::app()->createUrl("aaohome/agent/approve", array("agt_id" => $data[agt_id],"agt_approve"=>2))',
								'visible'	 => '($data[agt_approved]==1)',
								'label'		 => '<i class="fa fa-check"></i>',
								'options'	 => array('data-toggle' => 'ajaxModal', 'style' => 'padding: 4px', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs conDisapprove p5', 'title' => 'Disapprove')
							),
							'agttype'			 => array(
								'click'		 => 'function(){
                                                                   var con = confirm("Are you sure you want to change type of this agent?"); 
                                                                     if(con){
                                                                           $href = $(this).attr(\'href\');
                                                                           $.ajax({
                                                                               url: $href,
                                                                               dataType: "html",
                                                                               success: function(data){
                                                                                        var box = bootbox.dialog({
                                                                                                       message: data,
                                                                                                       title: "Change Partner Type",
                                                                                                       size: "large",
                                                                                                       onEscape: function () {
                                                                                                           // user pressed escape
                                                                                                       },
                                                                                                   });     
                                                                               },
                                                                               error: function(xhr, status, error){
                                                                                       alert(\'Sorry error occured\');
                                                                               }
                                                                           });
                                                                           }
                                                                           return false;
                                                           }',
								'url'		 => 'Yii::app()->createUrl("aaohome/agent/changetype", array("agt_id" => $data[agt_id]))',
								'label'		 => '<i class="fa fa-flag" ></i>',
								'visible'	 => '($data[agt_type]!=1)',
								'options'	 => array('data-toggle' => 'ajaxModal', 'style' => 'padding: 4px ;margin-left: 4px', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs changeAgentType', 'title' => 'Change Agent Type')
							),
							'delete'			 => array(
								'click'		 => 'function(){
                                                            var con = confirm("Are you sure you want to delete this partner?"); 
                                                              if(con){
                                                                    $href = $(this).attr(\'href\');
                                                                    $.ajax({
                                                                        url: $href,
                                                                        dataType: "json",
                                                                        success: function(result){
                                                                                if(result.success){
                                                                                        alert(result.message);
                                                                                        location.reload();
                                                                                }else{
                                                                                        alert(result.message);
                                                                                }

                                                                        },
                                                                        error: function(xhr, status, error){
                                                                                alert(\'Sorry error occured\');
                                                                        }
                                                                    });
                                                                    }
                                                                    return false;
                                                    }',
								'url'		 => 'Yii::app()->createUrl("aaohome/agent/delete11", array("agt_id" => $data[agt_id],"agt_type"=>$data[agt_type]))',
								'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\delete_booking.png',
								'label'		 => '<i class="fa fa-toggle-on"></i>',
								'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs agentDelete p0', 'title' => 'Delete')
							),
							'linkuser'			 => array(
								'click'		 => 'function(){
                                                                    $href = $(this).attr(\'href\');
                                                                    $.ajax({
                                                                        url: $href,
                                                                        dataType: "html",
                                                                        success: function(data){
                                                                               var linkuserbootbox1 = bootbox.dialog({ 
                                                                                   message: data,  
                                                                                   title:"Link User",
                                                                                   size: "large",
                                                                                   callback: function(){   }
                                                                               });
                                                                                linkuserbootbox1.on("hidden.bs.modal", function () { $(this).data("bs.modal", null); });
                                                                        },
                                                                        error: function(xhr, status, error){
                                                                                alert(\'Sorry error occured\');
                                                                        }
                                                                    });
                                                            
                                                                    return false;
                                                    }',
								'url'		 => 'Yii::app()->createUrl("aaohome/agent/linkuser", array("agt_id" => $data[agt_id]))',
								'label'		 => '<i class="fa fa-users"></i>',
								'options'	 => array('data-toggle' => 'ajaxModal', 'style' => 'padding: 4px ;margin-left: 4px', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs linkUser', 'title' => 'Link User')
							),
							'changeAgentType'		 => array(
														'click'		 => 'function(e){                                                        
                                                        try
                                                            {
                                                            $href = $(this).attr("href");
                                                            jQuery.ajax({type:"GET",url:$href,success:function(data)
                                                            {
                                                                bootbox.dialog({ 
                                                                message: data, 
                                                                className:"bootbox-sm",
                                                                title:"Change Agent Type",
                                                                success: function(result){
                                                                if(result.success){
                                                                
                                                                    }else{
                                                                        alert(\'Sorry error occured\');
                                                                    }
                                                                },
                                                                error: function(xhr, status, error){
                                                                    alert(\'Sorry error occured\');
                                                                }
                                                            });
                                                            }}); 
                                                            }
                                                            catch(e)
                                                            { alert(e); }
                                                        return false;
                                                            
                                                    }',
														'url'		 => 'Yii::app()->createUrl("aaohome/agent/changeAgentType", array("agt_id" => $data[agt_id]))',
														'imageUrl'	 => Yii::app()->request->baseUrl . '\images\assign_vendor.png',
														//'visible'	 => '($data[vnd_active] == 1 && Yii::app()->user->checkAccess("vendorList"))',
														'label'		 => '<i class="fa fa-toggle-on"></i>',
														'options'	 => array('data-toggle' => 'ajaxModal', 'id' => 'remark', 'style' => '', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs addremark p0', 'title' => 'Change Agent Type')
													),
							'htmlOptions'		 => array('class' => 'center'),
						))
			)));
		}
		?>
    </div>
</div>
<script type="text/javascript">
	$(document).ready(function () {
		var start = '<?= date('d/m/Y', strtotime('-1 month')); ?>';
		var end = '<?= date('d/m/Y'); ?>';
		$('#createdDate').daterangepicker(
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
			$('#Agents_createDate1').val(start1.format('YYYY-MM-DD'));
			$('#Agents_createDate2').val(end1.format('YYYY-MM-DD'));
			$('#createdDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
		});
		$('#createdDate').on('cancel.daterangepicker', function (ev, picker) {
			$('#createdDate span').html('Select Allocated Date Range');
			$('#Agents_createDate1').val('');
			$('#Agents_createDate2').val('');
		});

	});
	function viewDetails(obj) {
		var href2 = $(obj).attr("href");

		$.ajax({
			"url": href2,
			"type": "GET",
			"dataType": "html",
			"success": function (data) {

				var box = bootbox.dialog({
					message: data,
					title: 'Partner Details',
					size: 'large',
					onEscape: function () {
						// user pressed escape
					},
				});
			}
		});
		return false;
	}
</script>