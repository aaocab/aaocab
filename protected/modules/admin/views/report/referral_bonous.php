<div class="row">
    <div class="col-xs-12">
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
					array('name' => 'Referral Name', 'value' => '$data[referralName]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Referral Name'),
					array('name' => 'Invitee Name', 'value' => '$data[inviteeName]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Invitee Name'),
					array('name'	 => 'Bonus Amount', 'value'	 => function($data) 
						{
							echo '<i class="fa fa-inr"></i>' . $data['act_amount'];
						}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-right'), 'htmlOptions'  => array('class' => 'text-right'), 'header'			 => 'Bonous Amount'),
					array('name'	 => 'Bonous Date', 'value'	 => function($data) 
						{
							echo date('d/m/Y H:i:s', strtotime($data['act_date']));
						}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Bonous Date'),
					array('name' => 'Remarks', 'value' => '$data[act_remarks]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-4'), 'header' => 'Remarks'),
			
				)
			));
		}
		?>
    </div>
</div>