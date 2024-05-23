<div class="row">
</div>
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
				'columns'			 => array(
					array('name' => 'usb_email', 'value' => $data['usb_email'], 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Unsubscribe Email'),
					array('name'	 => 'usb_create_date', 'value'	 => function($data) {
							echo $data['usb_create_date'];
						}, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Unsubscribe Date'),
					array(
						'header'			 => 'Action',
						'class'				 => 'CButtonColumn',
						'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
						'template'			 => '{edit}{delete}',
						'buttons'			 => array(
							'edit'			 => array(
								'url'		 => 'Yii::app()->createUrl("admin/unsubscribe/add", array("usb_id" => $data["usb_id"]))',
								'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\zone\edit_booking.png',
								'label'		 => '<i class="fa fa-edit"></i>',
								'options'	 => array('style' => '', 'class' => 'btn btn-xs', 'title' => 'Edit'),
							),
							'delete'		 => array(
								'url'		 => 'Yii::app()->createUrl("admin/unsubscribe/delete", array("usb_id" => $data["usb_id"]))',
								'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\customer_cancel.png',
								'label'		 => '<i class="fa fa-delete"></i>',
								'options'	 => array('style' => '', 'class' => 'btn btn-xs ignoreJob p0', 'title' => 'Delete'),
							),
							'htmlOptions'	 => array('class' => 'center'),
						))
			)));
		}
		?>
    </div>
</div>