
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
					array('name'				 => 'cln_city_id', 'value'				 => '$data->clnCities->cty_name',
						'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'City Name'),
					array('name'				 => 'cln_title', 'value'				 => '$data[cln_title]',
						'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Title'),
					array('name'				 => 'cln_category', 'value'				 => 'CityLinks::model()->getCategories($data[cln_category])',
						'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Category'),
					array('name'	 => 'cln_url', 'type'	 => 'raw', 'value'	 => function($data) {

							return CHtml::link($data['cln_url'], $data['cln_url'], ['target' => '_BLANK']);
						},
						'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'URL'),
					array('name'				 => 'cln_datetime', 'value'				 => 'date("d/m/Y H:i:s",strtotime($data[cln_datetime]))',
						'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Date'),
					array('name'				 => 'cln_user_ip', 'value'				 => '$data[cln_user_ip]',
						'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'IP Address'),
					array('name'				 => 'cln_user_id', 'value'				 => '$data->clnUser->usr_name',
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
								'url'		 => 'Yii::app()->createUrl("admin/city/linkapproval", array(\'link_id\' => $data[cln_id],\'link_approve\' => 1))',
								'imageUrl'	 => false,
								'label'		 => '<i class="fa fa-check"></i>',
								'visible'	 => '($data->cln_status==0?true:false)',
								'options'	 => array('data-toggle' => 'ajaxModal', 'style' => 'margin-right: 2px', 'class' => 'btn btn-xs btn-warning approve', 'title' => 'Pending approval'),
							),
							'htmlOptions'	 => array('class' => 'center'),
						))
			)));
		}
		?>
    </div>
</div>
