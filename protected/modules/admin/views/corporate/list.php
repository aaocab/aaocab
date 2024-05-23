
<div class="panel-advancedoptions" >
    <div class="row">
        <div class="col-md-12 pull-left"> <a href="<?= Yii::app()->createUrl('admin/corporate/add') ?>"><div class="btn btn-info"><i class="fa fa-plus"></i> Add</div></a></div>
        <div class="col-md-12">
            <div class="panel" >
                <div class="panel-body panel-no-padding p0 pt10">
                    <div class="panel-scroll1">
                        <div style="width: 100%; overflow: auto;  border: 1px #aaa solid;color: #444;">
							<?php
							if (!empty($dataProvider))
							{
								$this->widget('booster.widgets.TbGridView', array(
									'id'				 => 'corporate-list',
									'responsiveTable'	 => true,
									'dataProvider'		 => $dataProvider,
									'filter'			 => $model,
									'template'			 => "<div class='panel-heading'><div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
									'itemsCssClass'		 => 'table table-striped table-bordered mb0',
									'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
									'columns'			 => array(
										array('name' => 'crp_code', 'value' => '$data->crp_code', 'sortable' => false, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Corporate Code'),
										array('name' => 'crp_fname', 'filter' => FALSE, 'value' => '$data->crp_fname." ".$data->crp_lname', 'sortable' => false, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Name'),
										array('name' => 'crp_company', 'sortable' => false, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => $model->getAttributeLabel('crp_company')),
										array('name' => 'crp_owner', 'sortable' => false, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => $model->getAttributeLabel('crp_owner')),
										array('name' => 'crp_contact', 'value' => '$data->crp_country_code."".$data->crp_contact', 'sortable' => false, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => $model->getAttributeLabel('crp_contact')),
										array('name' => 'crp_email', 'sortable' => false, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => $model->getAttributeLabel('crp_email')),
										array('name' => 'crp_discount_type', 'filter' => FALSE, 'sortable' => false, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => $model->getAttributeLabel('crp_discount_type')),
										array('name' => 'crp_discount_amount', 'filter' => FALSE, 'sortable' => false, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => $model->getAttributeLabel('crp_discount_amount')),
										array('name' => 'crp_credit_limit', 'filter' => FALSE, 'sortable' => false, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => $model->getAttributeLabel('crp_credit_limit')),
										array('name' => 'crp_agreement', 'value' => '($data->crp_agreement==1)?"Yes":"NO"', 'filter' => FALSE, 'sortable' => false, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => $model->getAttributeLabel('crp_agreement')),
										array('name' => 'crp_created', 'filter' => FALSE, 'sortable' => false, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => $model->getAttributeLabel('crp_created')),
										array(
											'header'			 => 'Action',
											'class'				 => 'CButtonColumn',
											'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
											'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
											'template'			 => '{edit}{users}',
											'buttons'			 => array(
												'edit'			 => array(
													'url'		 => 'Yii::app()->createUrl("admin/corporate/add", array(\'id\' => $data->crp_id))',
													'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\city\edit_booking.png',
													'label'		 => '<i class="fa fa-edit"></i>',
													'options'	 => array('style' => 'margin-right: 4px', 'class' => 'btn btn-xs corporateedit p0', 'title' => 'Edit'),
												),
												'htmlOptions'	 => array('class' => 'center'),
												'users'			 => array(
													'url'		 => 'Yii::app()->createUrl("admin/corporate/linkedusers", array(\'id\' => $data->crp_id))',
													'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\show_log.png',
													'label'		 => '<i class="fa fa-edit"></i>',
													'options'	 => array('style' => '', 'class' => 'btn btn-xs linkedusers p0', 'title' => 'Linked Users', 'target' => '_BLANK'),
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
    </div>
</div>