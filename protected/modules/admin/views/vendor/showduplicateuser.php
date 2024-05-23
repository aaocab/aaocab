<style>
    .dlgComments .dijitDialogPaneContent{
        overflow: auto;
    }
</style>
<div class="panel-advancedoptions" >
    <div class="row">
        <div class="col-md-12">            
            <div class="panel" >
                <div class="panel-body ">
                    <div class="">
                        <div style="width: 100%; overflow: auto;  border: 1px #aaa solid;color: #444;">
							<?php
							if (!empty($dataProvider))
							{
								$this->widget('booster.widgets.TbGridView', array(
									'id'				 => 'vendorduplicateuser',
									'responsiveTable'	 => true,
									// 'filter' => FALSE,
									'dataProvider'		 => $dataProvider,
									'template'			 => "<div class='panel-heading'>
                                        <div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div>
                                            <div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                        </div>
                                    </div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'>
                                        <div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 p5'>{summary}</div>
                                            <div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                         </div>
                                     </div>",
									'itemsCssClass'		 => 'table table-striped table-bordered mb0',
									'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
									'columns'			 => array(
										array('name'	 => 'name', 'filter' => FALSE, 'value'	 => function($data) {
												echo $data['vnd_name'];
											}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header' => 'Vendor Name'),
										array('name'	 => 'code', 'filter' => FALSE, 'value'	 => function($data) {
												echo $data['vnd_code'];
											}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header' => 'Vendor Code'),
                                        array(
											'header'			 => 'Action',
											'class'				 => 'CButtonColumn',
											'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
											'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
											'template'			 => '{viewvendor}{viewcontact}{viewuser}',
											'buttons'			 => array(
												'viewvendor'		 => array(
													'url'		 => 'Yii::app()->createUrl("admin/vendor/view", array("id" => $data["vnd_id"]))',
													'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\driver\show_details.png',
													'label'		 => '<i class="fa fa-check"></i>',
													'options'	 => array('target'=>'_blank','style' => '', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'accdetails btn btn-xs p0', 'title' => 'Vendor Details'),
												),
                                                'viewcontact'		 => array(
													'url'		 => 'Yii::app()->createUrl("admin/contact/view", array("ctt_id" => $data["vnd_contact_id"]))',
													'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\show_log.png',
													'label'		 => '<i class="fa fa-check"></i>',
													'options'	 => array('target'=>'_blank','style' => '', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'accdetails btn btn-xs p0', 'title' => 'Contact Details'),
												),
                                                'viewuser'		 => array(
													'url'		 => 'Yii::app()->createUrl("admin/user/list", array("userid" => $data["vnd_user_id"]))',
													'imageUrl'	 => Yii::app()->request->baseUrl . '\images\profile.png',
													'label'		 => '<i class="fa fa-check"></i>',
													'options'	 => array('target'=>'_blank','style' => '', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'accdetails btn btn-xs p0', 'title' => 'User Details'),
												)
                                                
                                                )
                                            )            
								)));
							}
							?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
