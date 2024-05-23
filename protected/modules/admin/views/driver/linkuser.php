<?php

//	echo print_r($agentModel, true);
//	exit;
 
?>
<div class="panel">
    <div class="panel panel-body">
        <div class="col-xs-6"><b>Driver Code :</b> <?= $agentModel->drv_code;?></div>
        <div class="col-xs-6"><b>Name : </b><?= $agentModel->drv_name; ?></div>
    </div>
</div>

<div class="panel">
    <div class="panel panel-heading"><div class="col-sm-12">Linked Users <button class="btn btn-success pull-right" type="button" name="linkuser" onclick="linkUser(<?= $agentModel->drv_id ?>);"><i class="fa fa-link"></i> Link user</button></div></div>
    <div class="panel panel-body">
		<div class="col-xs-12"> 
			<?
			if (!empty($dataProvider))
			{
				$this->widget('booster.widgets.TbGridView', array
				(
					'responsiveTable'	 => true,
					'dataProvider'		 => $dataProvider,
					'id'				 => 'userlinkedlist',
					'template'			 => "<div class='panel-heading'><div class='row m0'><div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>
                 <div class='panel-body'>{items}</div><div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
					'itemsCssClass'		 => 'table table-striped table-bordered mb0',
					'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
					'columns'			 => array(
						array('name' => 'usr_name', 'type' => 'raw', 'value' => '$data["name"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Name'),
						array('name'	 => 'usr_mobile',
							'value'	 => function ($data) {
								if ($data["phone"] != '')
								{
									echo '+' . $data["code"] . $data["phone"];
								}
							},
							'sortable'			 => false, 'headerHtmlOptions'	 => array(), 'header'			 => 'Phone'),
						array('name' => 'usr_email', 'value' => '$data["email"]', 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Email'),
						array('name' => 'usr_city', 'value' => '$data["usr_city"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'City'),
						array('name'	 => 'usr_created_at',
							'value'	 => function ($data) {
								echo DateTimeFormat::DateTimeToLocale($data["usr_created_at"]);
							},
							'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Signup Date'),
						array(
							'header'			 => 'Action',
							'class'				 => 'CButtonColumn',
							'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
							'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
							'template'			 => '{unlink}',
							'buttons'			 => array(
								'unlink'		 => array(
									'click'		 => 'function(){
                                                            var con = confirm("Are you sure you want to unlink this user to this partner?");
                                                            if(con){
                                                                    $href = $(this).attr(\'href\');
                                                                    $.ajax({
                                                                        url: $href,
                                                                        dataType: "json",
                                                                        success: function(result){
                                                                                if(result.success){
                                                                                 alert(result.msg);
                                                                                        $(\'#userlinkedlist\').yiiGridView(\'update\');
                                                                                }else{
                                                                                       if(result.msg!=""){
                                                                                                alert(result.msg);
                                                                                       }else{
                                                                                        alert(\'Sorry error occured\');
                                                                                        }
                                                                                }

                                                                        },
                                                                        error: function(xhr, status, error){
                                                                                alert(\'Sorry error occured\');
                                                                        }
                                                                    });
                                                            }
                                                            return false;
                                                        }',
									'url'		 => 'Yii::app()->createUrl("admin/agent/linkuser", array(\'user_id\' => $data[\'user_id\'],\'agt_id\'=>' . $agentModel->drv_id . '))',
									'label'		 => '<i class="fa fa-unlink"></i>',
									'options'	 => array('data-toggle' => 'ajaxModal', 'style' => 'padding: 4px ;margin-left: 4px', 'class' => 'btn btn-xs text-danger unlinkuser', 'title' => 'Unlink User'),
								),
								'htmlOptions'	 => array('class' => 'center'),
							))
				)));
			}
			?>
		</div>
    </div>
</div>

<script type="text/javascript">
    function linkUser(drvId) 
	{
        $.ajax({
            "url": '<?= Yii::app()->createUrl("admpnl/driver/link", array("drvId" => "")) ?>' + drvId,
            "type": "GET",
            "dataType": "html",
            "success": function (data) {
                var box = bootbox.dialog({
                    message: data,
                    title: 'Link User',
                    size: 'large',
                    onEscape: function () {
                        // user pressed escape
                    },
                });
                return false;
            }
        });
        return false;
    }
</script>




