<div id="content">
    <div class="row">
        <div id="userView1">
            <div class=" col-xs-12 pt30">
                <div class="projects ">

                    <a class="btn btn-primary mb10 ml20" href="<?= Yii::app()->createUrl('admin/account/addSheet') ?>" style="text-decoration: none">Add new</a>
                    <div class="panel panel-default">
                        <div class="panel-body">
							<?php
							$arrSheetType = $model->arrSheetType;
							$arrStatus = $model->arrStatus;
							if (!empty($dataProvider))
							{
								$this->widget('booster.widgets.TbGridView', array(
									'id'				 => 'sheet-grid',
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
									'columns'			 => array(
										array('name' => 'prs_title', 'filter' => false, 'value' => '$data["prs_title"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Title'),
										array('name' => 'prs_sheet_type', 'filter' => false, 'value' => function ($data) use ($arrSheetType) {
											echo $arrSheetType[$data["prs_sheet_type"]];
										}, 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Sheet Type'),
										array('name' => 'prs_filename', 'filter' => false, 'value' => '$data["prs_filename"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Filename'),
										array('name' => 'prs_row_count', 'filter' => false, 'value' => '$data["prs_row_count"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Row Count'),
										array('name' => 'prs_status', 'filter' => false, 'value' => function ($data) use ($arrStatus) {
											echo $arrStatus[$data["prs_status"]];
										}, 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Status'),
										array('name' => 'prs_create_date', 'filter' => false, 'value' => '$data["prs_create_date"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Create Date'),
										array(
											'header'			 => 'Action',
											'class'				 => 'CButtonColumn',
											'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
											'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
											'template'			 => '{edit}',
											'buttons'			 => array(
												'edit'			 => array(
													'url'		 => 'Yii::app()->createUrl("admin/account/exportSheet", array(\'prsId\' => $data[prs_id]))',
													'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\download.png',
													'label'		 => '<i class="fa fa-download"></i>',
													'options'	 => array('style' => '', 'class' => 'btn btn-xs p0', 'title' => 'Download'),
												),
												'htmlOptions'	 => array('class' => 'center'),
											)
										)
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