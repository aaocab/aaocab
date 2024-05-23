<div class="panel-advancedoptions" >
    <div class="row">
        <div class="col-md-12">            
            <div class="panel" >
                <div class="panel-body p0">
                    <div class="">
                        <div style="width: 100%; overflow: auto;  border: 1px #aaa solid;color: #444;">
							<?php
							if (!empty($dataProvider))
							{
								$this->widget('booster.widgets.TbGridView', array(
									'id'				 => 'drvcbr-grid' . $qry['booking_id'],
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
                                            
                                         </div>
                                     </div>",
									'itemsCssClass'		 => 'table table-striped table-bordered mb0',
									'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
									'columns'			 => array(
										array('name'	 => 'id', 'filter' => FALSE, 'value'	 => function($data) {
												echo $data['scq_id'];
											}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'ID'),
										array('name'	 => 'tea_name', 'filter' => FALSE, 'value'	 => function($data) {
												echo $data['tea_name'];
											}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Team'),
										array('name'	 => 'scq_follow_up_date_time', 'filter' => FALSE, 'value'	 => function($data) {
												echo date("d-m-Y H:i:s", strtotime($data['scq_follow_up_date_time']));
											}, 'sortable'			 => false,
											'headerHtmlOptions'	 => array('class' => 'col-xs-4'), 'header'			 => 'Followup At'),
										array('name'	 => 'user_fname', 'filter' => FALSE, 'value'	 => function($data) {
												echo ($data['user_fname'] . ' ' . $data['user_lname']);
											}, 'sortable'			 => false,
											'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Created By'),
										array('name'				 => 'followupWith',
											'filter'			 => FALSE,
											'value'				 => '$data[followupWith]',
											'sortable'			 => false,
											'headerHtmlOptions'	 => array('class' => 'col-xs-2')
											, 'header'			 => 'Followup With'),
										array('name'				 => 'scq_creation_comments',
											'filter'			 => FALSE,
											'value'				 => '$data[scq_creation_comments]',
											'sortable'			 => false,
											'headerHtmlOptions'	 => array('class' => 'col-xs-2')
											, 'header'			 => 'Opening Comment'),
										array('name'				 => 'scq_disposition_comments',
											'filter'			 => FALSE,
											'value'				 => '$data[scq_disposition_comments]',
											'sortable'			 => false,
											'headerHtmlOptions'	 => array('class' => 'col-xs-2')
											, 'header'			 => 'Closing Comment'),
										array('name'				 => 'status',
											'filter'			 => FALSE,
											'value'				 => '$data[status]',
											'sortable'			 => false,
											'headerHtmlOptions'	 => array('class' => 'col-xs-2')
											, 'header'			 => 'Status'),
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