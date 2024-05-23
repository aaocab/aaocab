<style>
    .panel-body{
        padding-top: 0 ;
        padding-bottom: 0;
    }
    .table>tbody>tr>th
    {
        vertical-align: middle
    }

    .table>tbody>tr>td, .table>tbody>tr>th{
        padding: 7px;
        line-height: 1.5em;
    }
</style>
<div class="row m0">
    <div class="col-xs-12">
        <div class="text-right">
        </div>    
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row text-center h3">
                </div>
                <div class="row"> 
					<?php
					$typelist = array("" => "Select Category", "1" => "User", "2" => "Vendor", "3" => "Meterdown");
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
							//    'ajaxType' => 'POST',
							'columns'			 => array(
								array('name' => 'tnc_text', 'value' => '$data->tnc_text', 'headerHtmlOptions' => array('class' => 'col-xs-8'), 'header' => 'Description'),
								array('name'	 => 'tnc_cat',
									'value'	 => function($data) {
										if ($data->tnc_cat == 1)
										{
											$val = 'user';
										}
										else if ($data->tnc_cat == 2)
										{
											$val = 'vendor';
										}
										else
										{
											$val = 'Meterdown';
										}
										return $val;
									},
									'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Category'),
								array('name' => 'tnc_updated_at', 'value' => 'date("d/m/Y ",strtotime($data->tnc_updated_at))', 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'Valid From'),
								array('name' => 'tnc_version', 'value' => '$data->tnc_version', 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'Version'),
								array(
									'header'			 => 'Action',
									'class'				 => 'CButtonColumn',
									'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center'),
									'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
									'template'			 => '{edit}{delete}',
									'buttons'			 => array(
										'edit'			 => array(
											'url'		 => 'Yii::app()->createUrl("admin/terms/add", array(\'tnc_id\' => $data->tnc_id))',
											'imageUrl'	 => false,
											'label'		 => '<i class="fa fa-edit"></i>',
											'options'	 => array('style' => 'margin-right: 2px', 'class' => 'btn btn-xs btn-info ignoreJob', 'title' => 'Edit'),
										),
										'delete'		 => array(
											'click'		 => 'function(){
                                                        var con = confirm("Are you sure you want to dacativate this consitions?");
                                                        return con;
                                                    }',
											'url'		 => 'Yii::app()->createUrl("admin/terms/delterms", array(\'tnc_id\' => $data->tnc_id))',
											'imageUrl'	 => false,
											'label'		 => '<i class="fa fa-remove"></i>',
											'options'	 => array('data-toggle' => 'ajaxModal', 'style' => 'margin-right: 8px', 'class' => 'btn btn-xs btn-danger condelete', 'title' => 'Delete'),
										),
										'htmlOptions'	 => array('class' => 'center'),
									))
						)));
					}
					?> 
                </div> 
            </div>  

        </div>  
    </div>
</div>