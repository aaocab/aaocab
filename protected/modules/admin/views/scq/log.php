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
			'columns'			 => array(
				array('name'	 => 'Event Name', 'type'	 => 'raw', 'value'	 => function($data) {
						if ($data['fpl_event_id'] != '')
						{
							switch ($data['fpl_event_id'])
							{
								case '1':
									echo 'AUTO_ASSIGNED';
									break;
								case '2':
									echo 'MANUALLY_ASSIGNED';
									break;
								case '3':
									echo 'FOLLOWUP_TRANSFER';
									break;
								case '4':
									echo 'FOLLOWUP_COMPLETE';
									break;
								default:
									echo 'Unknown Event';
									break;
							}
						}
					}),
				array('name'	 => 'User Name', 'type'	 => 'raw', 'value'	 => function($data) {
						if ($data['fpl_user_id'] != '')
						{
							echo $data['adm_fname'] . " " . $data['adm_lname'];
						}
					}),
				array('name' => 'remarks', 'value' => '$data["fpl_remarks"]', 'headerHtmlOptions' => array('class' => 'col-xs-5'), 'header' => 'Reamrks'),
				array('name' => 'created Date', 'value' => '$data["fpl_create_date"]', 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Created Date'),
		)));
	}
	?> 
</div> 





