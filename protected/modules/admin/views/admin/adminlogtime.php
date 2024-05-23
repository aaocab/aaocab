<div id="content" class=" mt20">
    <div class="row mb50">
        <div id="userView1">
            <div class=" col-xs-12 ">
                    <div class="panel panel-default">
                        <div class="panel-body" >
							<?php
							if (!empty($dataProvider))
							{
								$params									 = array_filter($_REQUEST);
								$dataProvider->getPagination()->params	 = $params;
								$dataProvider->getSort()->params		 = $params;
								$this->widget('booster.widgets.TbGridView', array(
									'id'				 => 'admin-grid',
									'responsiveTable'	 => true,
									'dataProvider'		 => $dataProvider,
									'template'			 => "<div class='panel-heading'><div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
									'itemsCssClass'		 => 'table table-striped table-bordered mb0',
									'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
									'columns'			 => array
										(
										array('name' => 'ado_time', 'value' => 'date("d/M/Y h:i A", strtotime($data["ado_time"]))', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Time'),
										array('name'	 => 'ado_status', 'value'	 => function($data) {
												if ($data['ado_status'] == 1)
												{
													echo "Logged In";
												}
												else
												{
													echo "Logged Out";
												}
											}, 'sortable'								 => true, 'headerHtmlOptions'						 => array(), 'header'								 => 'Status'),
								)));
							}
							?>
                        </div>
                    </div>
               
            </div>
        </div>
    </div>
</div>


