<div class="row">
	<div class="col-xs-12">
		<?php
		if (!empty($dataProvider))
		{
			$this->widget('booster.widgets.TbGridView', array(
				'responsiveTable'	 => true,
				'dataProvider'		 => $dataProvider,
				'template'			 => "<div class='panel-heading'><div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
				'itemsCssClass'		 => 'table table-striped table-bordered mb0',
				'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
				//    'ajaxType' => 'POST',
				'columns'			 => array
					(
					array('name'	 => 'agt_name', 'value'	 => function ($data) {
							if ($data['agt_name'] == '')
							{
								echo 'B2C';
							}
							else
							{
								echo $data['agt_name'];
							}
						}
						, 'sortable'			 => true
						, 'headerHtmlOptions'	 => array()
						, 'header'			 => 'Name'),
					array('name' => 'scc_label', 'value' => '$data[scc_label]', 'headerHtmlOptions' => array(), 'header' => 'Service Class'),
					array('name' => 'cnp_label', 'value' => '$data[cnp_label]', 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Cancel Rule'),
					array('name' => 'cnp_desc', 'value' => '$data[cnp_desc]', 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Cancel Details'),
					array('name' => 'prc_zone_category', 'value' => '$data[prc_zone_category]', 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Category'),
			)));
		}
		?>

    </div>
</div>