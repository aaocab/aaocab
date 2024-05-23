<div class="container-fluid">
<div class="row"> 
	<?php
	if (!empty($followUpData))
	{
		$this->widget('booster.widgets.TbGridView', array(
			'responsiveTable'	 => true,
			'dataProvider'		 => $followUpData,
			'template'			 => "<div class='panel-heading'><div class='row m0'>
                                     <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                     </div></div>
                                     <div class='panel-body table-responsive'>{items}</div>
                                     <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
			'itemsCssClass'		 => 'table table-striped table-bordered dataTable mb0',
			'htmlOptions'		 => array('class' => 'panel panel-primary  compact'),
			'columns'			 => array(
				array('headerHtmlOptions' => array('class' => 'col-xs-2'), 'name'	 => 'Contact Name', 'type'	 => 'raw', 'value'	 => function($data) {
						if ($data['cttId'] != '')
						{
							echo CHtml::link($data['contactName'], Yii::app()->createUrl("admin/contact/form", ["ctt_id" => $data['cttId'], "type" => 3]), ["onclick" => "", 'target' => '_blank']);
						}
					}),
				
				//array('name' => 'tea_id', 'value' => '$data["teamId"]', 'headerHtmlOptions' => array('class' => 'col-xs-5'), 'header' => 'Team Id'),
				array('name' => 'tea_name', 'value' => '$data["teamName"]', 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Team Name'),
				array('name' => 'dataSource', 'value' => '$data["dataSource"]', 'headerHtmlOptions' => array('class' => ''), 'header' => 'Data Source'),
				array('headerHtmlOptions' => array('class' => 'col-xs-3'), 'name'	 => 'Reference Id', 'type'	 => 'raw', 'value'	 => function($data) {
						if ($data['followUpTypeId'] != '' && $data['followUpTypeId'] == 2)
						{
							echo CHtml::link($data['followUpRefId'], Yii::app()->createUrl("admin/booking/view", ["id" => $data['followUpRefId']]), ["class" => "viewBooking", "onclick" => "", 'target' => '_blank']);

							echo "<br>";
						}
						else if ($data['followUpTypeId'] != '' && $data['followUpTypeId'] == 1)
						{
							echo CHtml::link('Click To Book', Yii::app()->createUrl("admin/booking/create"), ["onclick" => "", 'target' => '_blank']);
						}
						else if ($data['followUpTypeId'] != '' && $data['followUpTypeId'] == 3)
						{
							echo CHtml::link('Add Vendor', Yii::app()->createUrl("admin/vendor/add"), ["onclick" => "", 'target' => '_blank']);
						}
					}),
				array('name' => 'fwp_ref_type', 'value' => '$data["followUpType"]', 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Reference Type'),
				array('name' => 'callerType', 'value' => '$data["callerType"]', 'headerHtmlOptions' => array('class' => 'col-xs-5'), 'header' => 'Caller Type'),
				array('name' => 'fwp_desc', 'value' => '$data["callerQuery"]', 'headerHtmlOptions' => array('class' => 'col-xs-5'), 'header' => 'Description'),
				array('name' => 'fwp_assigned_csr', 'value' => '$data["csrId"]', 'headerHtmlOptions' => array('class' => 'col-xs-5'), 'header' => 'Assigned CSR'),
				array('name' => 'csrName', 'value' => '$data["csrName"]', 'headerHtmlOptions' => array('class' => 'col-xs-5'), 'header' => 'CSR Name'),
				array('name' => 'fwp_csr_remarks', 'value' => '$data["flwRemarks"]', 'headerHtmlOptions' => array('class' => 'col-xs-5'), 'header' => 'Csr Remarks'),
				array('name' => 'fwp_prefered_time', 'value' => '$data["flwPreferedTime"]', 'headerHtmlOptions' => array('class' => 'col-xs-5'), 'header' => 'Time'),
				array('name' => 'fwp_follow_up_status', 'value' => '$data["followUpStatus"]', 'headerHtmlOptions' => array('class' => 'col-xs-5'), 'header' => 'Status'),
				array(
					'header'			 => 'Action',
					'class'				 => 'CButtonColumn',
					'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
					'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
					'template'			 => '{log}',
					'buttons'			 => array(
						'log'			 => array(
							'click'		 => 'function(){
                                                    $href = $(this).attr(\'href\');
                                                    jQuery.ajax({type: \'GET\',
                                                    url: $href,
                                                    success: function (data)
                                                    {

                                                        var box = bootbox.dialog({
                                                            message: data,
                                                            title: \'Log\',
                                                            onEscape: function () {

                                                                // user pressed escape
                                                            }
                                                        });
                                                    }
                                                });
                                                    return false;
                                                    }',
							'url'		 => 'Yii::app()->createUrl("admin/followup/log", array("Id" => $data["flwUpId"]))',
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



