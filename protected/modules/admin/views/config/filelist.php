
<div class="row">
	<div class="col-xs-12">
		<?php
		if (!empty($list))
		{
			$params = array_filter($_REQUEST);

			$this->widget('booster.widgets.TbGridView', array(
				'responsiveTable'	 => true,
				'id'				 => 'filelist',
				'dataProvider'		 => $list,
				'template'			 => "<div class='panel-heading'><div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
				'itemsCssClass'		 => 'table table-striped table-bordered mb0',
				'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
				'columns'			 => array(
					array('name' => 'filename', 'value' => '$data[filename]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'File Name'),
					array('name' => 'type', 'value' => '$data[type]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'File Type'),
					array('name' => 'size', 'value' => '$data[size]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Size(KB)'),
					array('name' => 'modified_date', 'value' => '$data[modified_date]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Modified Date'),
					array(
						'header'			 => 'Action',
						'class'				 => 'CButtonColumn',
						'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
						'template'			 => '{download}',
						'buttons'			 => array(
							'download' => array(
								'url'		 => 'Yii::app()->createUrl("aaohome/config/download", array("filename" => $data[download_url]))',
								'label'		 => '<i class="fa fa-download"></i>',
								'options'	 => array('style' => '', 'class' => 'btn btn-xs ignoreJobEdit p0', 'title' => 'Download'),
							),
						))
			)));
		}
		?>
    </div>
</div>



