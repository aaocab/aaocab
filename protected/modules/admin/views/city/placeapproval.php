
<div class="panel">
    <div class="panel-heading"></div>
    <div class="panel-body">
		<?
		if ($dataProvider != '')
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
				//     'ajaxType' => 'POST',
				'columns'			 => array(
					array('name'				 => 'cpl_city_id', 'value'				 => '$data->cplCities->cty_name',
						'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'City Name'),
					array('name'				 => 'cpl_category', 'value'				 => 'CityPlaces::model()->getCategories($data[cpl_category])',
						'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Category'),
					array('name'				 => 'cpl_places', 'value'				 => '$data[cpl_places]',
						'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Places'),
					array('name'	 => 'cpl_url', 'type'	 => 'raw', 'value'	 => function($data) {

							return CHtml::link($data['cpl_url'], $data['cpl_url'], ['target' => '_BLANK']);
						},
						'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'URL'),
					array('name'				 => 'cpl_datetime', 'value'				 => 'date("d/m/Y H:i:s",strtotime($data[cpl_datetime]))',
						'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Date'),
					array('name'				 => 'cpl_user_ip', 'value'				 => '$data[cpl_user_ip]',
						'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'IP Address'),
					array('name'				 => 'cpl_user_id', 'value'				 => '$data->cplUser->usr_name',
						'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'User'),
					array(
						'header'			 => 'Action',
						'class'				 => 'CButtonColumn',
						'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => ''),
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
						'template'			 => '{approve}',
						'buttons'			 => array(
							'approve'		 => array(
								'url'		 => 'Yii::app()->createUrl("admin/city/placeapproval", array(\'place_id\' => $data[cpl_id],\'place_approve\' => 1))',
								'imageUrl'	 => false,
								'label'		 => '<i class="fa fa-check"></i>',
								'visible'	 => '($data->cpl_status==0?true:false)',
								'options'	 => array('data-toggle' => 'ajaxModal', 'style' => 'margin-right: 2px', 'class' => 'btn btn-xs btn-warning approve', 'title' => 'Pending approval'),
							),
							'htmlOptions'	 => array('class' => 'center'),
						))
			)));
		}
		?>
    </div>
</div>
