
<div id="user-content-linkuser">
	<?php
	$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
		'id'					 => 'agentlinkform',
		'enableClientValidation' => true,
		'clientOptions'			 => array(
			'validateOnSubmit'	 => true,
			'errorCssClass'		 => 'has-error',
			'afterValidate'		 => 'js:function(form,data,hasError){
                                   if(!hasError){
                                                    $.ajax({
                                                    "type":"POST",
                                                    "dataType":"html",
                                                    "url":"' . CHtml::normalizeUrl(Yii::app()->request->url) . '",
                                                    "data":form.serialize(),
                                                    "success":function(data1){
                                                            $("#user-content-linkuser").parent().html(data1);
                                                        },
                                                    });
                                    }
                             }'
		),
		'enableAjaxValidation'	 => false,
		'errorMessageCssClass'	 => 'help-block',
		'htmlOptions'			 => array(
			'class' => '',
		),
	));
	?>
    <div class="panel">
        <div class="panel panel-body">
            <div class="col-xs-6"><b>Partner ID :</b> <?= ($agentModel->agt_type == 1) ? $agentModel->agt_referral_code : $agentModel->agt_agent_id ?>-<?= $agentModel->getAgentType($agentModel->agt_type); ?></div>
            <div class="col-xs-6"><b>Name : </b><?= $agentModel->agt_fname . " " . $agentModel->agt_lname ?></div>
            <div class="col-xs-6"><b>Company : </b><?= $agentModel->agt_company ?></div>
            <div class="col-xs-6"><b>Owner : </b><?= $agentModel->agt_owner_name ?></div>
            <div class="col-xs-12 mt30">
                <input type="hidden" name="agt_id" value="<?= $agentModel->agt_id ?>">
                <div class="col-sm-4"><?= $form->textFieldGroup($model, 'search_name', array('label' => "", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Search by name,email,phone')))) ?> </div>
                <div class="col-sm-2"><button class="btn btn-info" type="submit" name="search">Search</button></div>
                <div class="col-sm-3"><button class="btn btn-warning" type="btton" name="addnewuser" onclick="return adduser(<?= $agentModel->agt_id ?>)">Add new user</button></div>
            </div>
        </div>
    </div>
	<?php $this->endWidget(); ?>
    <div class="panel">
        <div class="panel panel-body">
			<?php if (Yii::app()->user->hasFlash('success'))
			{
				?>
				<div class="col-xs-12 text-success text-center"><?php echo Yii::app()->user->getFlash('success'); ?></div>
				<? } ?>
            <div class="col-xs-12"> <?
				if ($dataProvider != '')
				{
					$this->widget('booster.widgets.TbGridView', array(
						'responsiveTable'	 => true,
						'dataProvider'		 => $dataProvider,
						'id'				 => 'listtolink',
						'ajaxUpdate'		 => true,
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
                                                                    var con = confirm("Are you sure you want to link this user to this partner?");
                                                                    if(con){
                                                                            $href = $(this).attr(\'href\');
                                                                            $.ajax({
                                                                                url: $href,
                                                                                dataType: "json",
                                                                                success: function(result){
                                                                                        if(result.success){
                                                                                                alert("User linked successfully.");
                                                                                                $(\'#listtolink\').yiiGridView(\'update\');
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
										'url'		 => 'Yii::app()->createUrl("admin/agent/link", array(\'user_id\' => $data[\'user_id\'],\'agt_id\'=>' . $agentModel->agt_id . '))',
										'label'		 => '<i class="fa fa-link"></i>',
										'options'	 => array('data-toggle' => 'ajaxModal', 'style' => 'padding: 4px ;margin-left: 4px', 'class' => 'btn btn-xs text-success linkuser111', 'title' => 'Link User'),
									),
									'htmlOptions'	 => array('class' => 'center'),
								))
					)));
				}
				else
				{
					?>
					<div class="col-xs-12 text-center">No records found</div>
				<? }
				?>
            </div>
        </div>
    </div>

</div>
<script type="text/javascript">
    var addnewuserbox;
    function adduser(agent_id) {
        $.ajax({
            "url": '<?= Yii::app()->createUrl("aaohome/agent/addnewuser", array("agt_id" => "")) ?>' + agent_id,
            "type": "GET",
            "dataType": "html",
            "success": function (data) {
                addnewuserbox = bootbox.dialog({
                    message: data,
                    title: 'Add new user',
                    size: 'medium',
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