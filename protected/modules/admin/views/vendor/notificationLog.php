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
									'id'				 => 'notification-grid' . $qry['booking_id'],
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
										array('name'	 => 'ntl_title', 'filter' => FALSE, 'value'	 => function($data) {
												echo $data['ntl_title'];
											}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Title'),
										array('name'	 => 'ntl_message', 'filter' => FALSE, 'value'	 => function($data) {
												echo $data['ntl_message'];
											}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-4'), 'header'			 => 'Message'),
										array('name'	 => 'ntl_created_on', 'filter' => FALSE, 'value'	 => function($data) {
												echo date("d/M/Y h:i A", strtotime($data['ntl_created_on']));
											}, 'sortable'			 => false,
											'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Created At'),
										array('name'	 => 'status', 'filter' => FALSE, 'value'	 => function($data) {
												echo $data['status'];
											}, 'sortable'			 => false,
											'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Status'),
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