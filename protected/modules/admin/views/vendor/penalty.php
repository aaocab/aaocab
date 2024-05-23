<style>
    .dlgComments .dijitDialogPaneContent{
        overflow: auto;
    }
</style>
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
									'id'				 => 'penalty-grid',
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
										array('name'	 => 'act_date', 'value'	 => function ($data) {
												echo date('d/m/Y', strtotime($data['act_date']));
											}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Transaction Date'),
										array('name'	 => 'Details', 'filter' => FALSE, 'value'	 => function ($data) {
												echo $data['act_remarks'];
											}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-4')),
										array('name'	 => 'Amount', 'filter' => FALSE, 'value'	 => function ($data) {
												echo $data['act_amount'];
											}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-1')),
										array('name'	 => 'Parameter', 'filter' => FALSE, 'value'	 => function ($data) {
												echo $data['adt_addt_params'];
											}, 'sortable'			 => false,
											'headerHtmlOptions'	 => array('class' => 'col-xs-3'), 'header'			 => 'Params')
//										array('name'	 => 'vlg_event_id', 'filter' => FALSE, 'value'	 => function($data) {
//												echo VendorsLog::model()->getEventByEventId($data['vlg_event_id']);
//											}, 'sortable'			 => false,
//											'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Event'),
//										array('name'				 => 'vlg_created',
//											'filter'			 => FALSE,
//											'value'				 => 'date("d/M/Y h:i A", strtotime($data[vlg_created]))',
//											'sortable'			 => false,
//											'headerHtmlOptions'	 => array('class' => 'col-xs-2')
//											, 'header'			 => 'Created')
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
