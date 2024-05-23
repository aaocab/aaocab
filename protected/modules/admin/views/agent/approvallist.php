

<div class="panel panel-default">
    <div class="panel-body">
		<?php
		$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'agent-search-form', 'enableClientValidation' => FALSE,
			'clientOptions'			 => array(
				'validateOnSubmit'	 => true,
				'errorCssClass'		 => 'has-error'
			),
			'enableAjaxValidation'	 => false,
			'errorMessageCssClass'	 => 'help-block',
			'htmlOptions'			 => array(
				'class'			 => 'form-horizontal', 'enctype'		 => 'multipart/form-data', 'autocomplete'	 => "off",
			),
		));
		/* @var $form TbActiveForm */
		?>
        <div class="col-xs-6">
			<?php
			$arrJSON1	 = array();
			$arr1		 = ['1' => 'approved', '2' => 'pending_approval', '3' => 'rejected', '0' => 'registered'];
			foreach ($arr1 as $key => $val)
			{
				$arrJSON1[] = array("id" => $key, "text" => $val);
			}
			$approvedriverlist = CJSON::encode($arrJSON1);

			$this->widget('booster.widgets.TbSelect2', array(
				'model'			 => $model,
				'attribute'		 => 'agt_approved',
				'val'			 => $model->agt_approved,
				'asDropDownList' => FALSE,
				'options'		 => array('data' => new CJavaScriptExpression($approvedriverlist), 'allowClear' => true),
				'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'approved status')
			));
			?>
        </div>
        <div class="col-xs-6 text-center">
            <button class="btn btn-info" type="submit" style="width: 185px;">Search</button>
        </div>
		<?php $this->endWidget(); ?>
    </div>
</div>

<?php
if (!empty($dataProvider))
{
	$this->widget('booster.widgets.TbGridView', array(
		'id'				 => 'agent-grid',
		'responsiveTable'	 => true,
		'filter'			 => $model,
		'dataProvider'		 => $dataProvider,
		'template'			 => "<div class='panel-heading'><div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
		'itemsCssClass'		 => 'table table-striped table-bordered mb0',
		'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
		'columns'			 => array(
//										array('name' => 'agt_fname', 'filter' => CHtml::activeTextField($model, 'agt_fname', array('class' => 'form-control', 'placeholder' => 'Search by ' . $model->getAttributeLabel('agt_fname'))), 'value' => '$data["agt_fname"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => $model->getAttributeLabel('agt_fname')),
//										array('name' => 'agt_lname', 'filter' => CHtml::activeTextField($model, 'agt_lname', array('class' => 'form-control', 'placeholder' => 'Search by ' . $model->getAttributeLabel('agt_lname'))), 'value' => '$data["agt_lname"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => $model->getAttributeLabel('agt_lname')),
			array('name'	 => 'agt_owner_name', 'filter' => CHtml::activeTextField($model, 'agt_owner_name', array('class' => 'form-control', 'placeholder' => 'Search by ' . $model->getAttributeLabel('agt_owner_name'))), 'value'	 => function($data) {
					$suffix = ($data["agt_type"] == 1) ? '<br><span class="label label-success">' . "Corporate" . '</span>' : '<br><span class="label label-info">' . "Agent" . '</span>';
					return $data["agt_owner_name"] . $suffix;
				}, 'sortable'			 => true, 'type'				 => 'raw', 'headerHtmlOptions'	 => array(), 'header'			 => $model->getAttributeLabel('agt_username')),
			array('name' => 'agt_phone', 'filter' => CHtml::activeTextField($model, 'agt_phone', array('class' => 'form-control', 'placeholder' => 'Search by ' . $model->getAttributeLabel('agt_phone'))), 'value' => '$data["agt_phone"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => $model->getAttributeLabel('agt_phone')),
			array('name' => 'agt_email', 'filter' => CHtml::activeTextField($model, 'agt_email', array('class' => 'form-control', 'placeholder' => 'Search by ' . $model->getAttributeLabel('agt_email'))), 'value' => '$data["agt_email"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => $model->getAttributeLabel('agt_email')),
			array('name'	 => 'agt_active', 'filter' => CHtml::activeCheckBoxList($model, 'agt_active', array('1' => 'On', '2' => 'Off')), 'value'	 => function($data) {
					if ($data->agt_active == 1)
					{
						echo "On";
					}
					else
					{
						echo "Off";
					}
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => $model->getAttributeLabel('agt_active')),
			array('name' => 'agt_create_date', 'filter' => FALSE, 'value' => 'date("d/M/Y h:i A", strtotime($data["agt_create_date"]))', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => $model->getAttributeLabel('agt_create_date')),
			array(
				'header'			 => 'Action',
				'class'				 => 'CButtonColumn',
				'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => ''),
				'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
				'template'			 => '{approve}{disapprove}{agttype}',
				'buttons'			 => array(
					'approve'		 => array(
						'click'		 => 'function(){
                                                            var con = confirm("Are you sure you want to approve this agent?"); 
                                                              if(con){
                                                                    $href = $(this).attr(\'href\');
                                                                    $.ajax({
                                                                        url: $href,
                                                                        dataType: "json",
                                                                        success: function(result){
                                                                                if(result.success){
                                                                                        $(\'#agent-grid\').yiiGridView(\'update\');
                                                                                }else{
                                                                                       if(result.msg!=""){
                                                                                       bootbox.alert(result.msg);
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
						'url'		 => 'Yii::app()->createUrl("admpnl/agent/approve", array("agt_id" => $data->agt_id,"agt_approve"=>1))',
						//'imageUrl' => Yii::app()->request->baseUrl . '\images\icon\inactive.png',
						'visible'	 => '($data->agt_approved==0 || $data->agt_approved==2)',
						'label'		 => '<i class="fa fa-check" ></i>',
						'options'	 => array('data-toggle' => 'ajaxModal', 'style' => 'padding: 4px', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-warning conApprove', 'title' => 'Approve')
					),
					'disapprove'	 => array(
						'click'		 => 'function(){
                                                            var con = confirm("Are you sure you want to disapprove this agent?"); 
                                                              if(con){
                                                                    $href = $(this).attr(\'href\');
                                                                    $.ajax({
                                                                        url: $href,
                                                                        dataType: "json",
                                                                        success: function(result){
                                                                                if(result.success){
                                                                                        $(\'#agent-grid\').yiiGridView(\'update\');
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
						'url'		 => 'Yii::app()->createUrl("admpnl/agent/approve", array("agt_id" => $data->agt_id,"agt_approve"=>0))',
						//  'imageUrl' => Yii::app()->request->baseUrl . '\images\icon\active.png',
						'visible'	 => '($data->agt_approved==1)',
						'label'		 => '<i class="fa fa-check" ></i>',
						'options'	 => array('data-toggle' => 'ajaxModal', 'style' => 'padding: 4px', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-success conDisapprove', 'title' => 'Disapprove')
					),
					'agttype'		 => array(
						'click'		 => 'function(){
                                                            var con = confirm("Are you sure you want to change type of this agent?"); 
                                                              if(con){
                                                                    $href = $(this).attr(\'href\');
                                                                    $.ajax({
                                                                        url: $href,
                                                                        dataType: "html",
                                                                        success: function(data){
                                                                               // if(result.success){
                                                                                
                                                                                                         var box = bootbox.dialog({
                                                                                                                        message: data,
                                                                                                                        title: "Change Agent Type",
                                                                                                                        size: "large",
                                                                                                                        onEscape: function () {
                                                                                                                            // user pressed escape
                                                                                                                        },
                                                                                                                    });     
                                                                                        $(\'#agent-grid\').yiiGridView(\'update\');
//                                                                                }else{
//                                                                                       if(result.msg!=""){
//                                                                                       bootbox.alert(result.msg);
//                                                                                       }else{
//                                                                                        alert(\'Sorry error occured\');
//                                                                                        }
//                                                                                }

                                                                        },
                                                                        error: function(xhr, status, error){
                                                                                alert(\'Sorry error occured\');
                                                                        }
                                                                    });
                                                                    }
                                                                    return false;
                                                    }',
						'url'		 => 'Yii::app()->createUrl("admpnl/agent/changetype", array("agt_id" => $data->agt_id))',
						'label'		 => '<i class="fa fa-flag" ></i>',
						'visible'	 => '($data->agt_type!=1)',
						'options'	 => array('data-toggle' => 'ajaxModal', 'style' => 'padding: 4px ;margin-left: 4px', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-primary changeAgentType', 'title' => 'Change Agent Type')
					),
					'htmlOptions'	 => array('class' => 'center'),
				))
	)));
}
