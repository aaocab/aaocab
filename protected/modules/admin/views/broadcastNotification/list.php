<style type="text/css">
    .cityinput > .selectize-control>.selectize-input{
        width:100% !important;
    }
</style>


<div class="row">

    <div class="col-xs-12">
		<?php
		if (!empty($dataProvider))
		{
			$this->widget('booster.widgets.TbGridView', array(
				'id'				 => 'route-grid',
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
					array('name' => 'bcn_user_type', 'value' => function($data) {
						if ($data["bcn_user_type"] == 1){echo "Vendor";}
						elseif ($data["bcn_user_type"] == 2) {echo "Driver";}
						elseif ($data["bcn_user_type"] == 3) {echo "Consumer";};
					}, 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'User Type'),
                                        array('name' => 'adm_user', 'value' => '$data["adm_user"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'User Name'),
					array('name'				 => 'bcn_title', 'sortable'			 => true, 'headerHtmlOptions'	 => array(),
						'value'				 => '$data["bcn_title"]',
						'header' => 'Title'),
                                        array('name'				 => 'bcn_schedule_for', 'sortable'			 => true, 'headerHtmlOptions'	 => array(),
						'value'				 => function($data) {
							echo DateTimeFormat::DateTimeToLocale($data['bcn_schedule_for']);
						},
						'header' => 'Schedule For'),
					array('name'				 => 'bcn_created_at', 'sortable'			 => true, 'headerHtmlOptions'	 => array(),
						'value'				 => function($data) {
							echo DateTimeFormat::DateTimeToLocale($data['bcn_created_at']);
						},
						'header'			 => 'Created at'),
                                        array('name' => 'bcn_status', 'value' => function($data) {
						if ($data["bcn_status"] == 1){echo "Pending";}
						elseif ($data["bcn_status"] == 2) {echo "In progress";}
						elseif ($data["bcn_status"] == 3) {echo "Completed";};
					}, 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Status'),
					array('name' => 'bcn_active', 'value' => '($data["bcn_active"] == 1) ? "Active" : "Inactive"', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Mode'),
					array(
						'header'			 => 'Action',
						'class'				 => 'CButtonColumn',
						'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
						'template'			 => '{disable}{enable}',
						'buttons'			 => array(
							/* overide dynamic routes */
							'disable'	 => array(
								'click'		 => 'function(){
                                  var con1 = confirm("Are you sure you want to disable this notification?"); 
                                  if(con1){
                                    $href = $(this).attr(\'href\');
                                    $.ajax({
                                        url: $href,
                                        success: function(result){
                                            if(result == "true"){
                                                $(\'#route-grid\').yiiGridView(\'update\');
                                            }else{
                                                alert(\'Sorry error occured\');
                                            }

                                        },
                                        error: function(xhr, status, error){
                                            alert(\'Sorry error occured\');
                                        }
                                    });
                                    }
                                    return false;
                                    }',
								'url'		 => 'Yii::app()->createUrl("admin/broadcastNotification/changestatus", array("activateid" => $data["bcn_id"]))',
								'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\route_list\active.png',
								'visible'	 => '$data["bcn_active"]==0 OR $data["bcn_active"]==1?true:false;',
								'label'		 => '<i class="fa fa-toggle-off"></i>',
								'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'class' => 'btn btn-xs dynamicDisabled p0', 'title' => 'Disabled notification'),
							),
							'enable'	 => array(
								'click'		 => 'function(){
                                    var con1 = confirm("Are you sure you want to enable this notification?");
                                    if(con1){
                                    $href = $(this).attr(\'href\');
                                    $.ajax({
                                        url: $href,
                                        success: function(result){
                                            if(result == "true"){
                                                $(\'#route-grid\').yiiGridView(\'update\');
                                            }else{
                                                alert(\'Sorry error occured\');
                                            }

                                        },
                                        error: function(xhr, status, error){
                                            alert(\'Sorry error occured\');
                                        }
                                    });
                                    }
                                    return false;
                                    }',
								'url'		 => 'Yii::app()->createUrl("admin/broadcastNotification/changestatus", array("disableid" => $data["bcn_id"]))',
								'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\route_list\inactive.png',
								'visible'	 => '$data["bcn_active"]==2?true:false;',
								'label'		 => '<i class="fa fa-toggle-on"></i>',
								'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'class' => 'btn btn-xs dynamic�nable p0', 'title' => 'Enable notification'),
							),
							'htmlOptions'		 => array('class' => 'center'),
						))
			)));
		}
		?>
    </div>
</div>

