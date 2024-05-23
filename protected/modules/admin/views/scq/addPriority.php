
<div class="row">
    <div class="col-md-12 col-sm-10 col-xs-12">
        <div class="panel panel-white">
			<?php
			$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
				'id'					 => 'teamqueuemap', 'enableClientValidation' => true,
				'clientOptions'			 => array(
					'validateOnSubmit'	 => true,
					'errorCssClass'		 => 'has-error',
					'afterValidate'		 => 'js:function(form,data,hasError){
                                            if(!hasError){

                                                $.ajax({
                                                "type":"POST",
                                                "url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('admin/Scq/modifyPriority', ['Id' => $_REQUEST['Id']])) . '",
                                                "data":form.serialize(),
                                                        "success":function(data1){
                                                                var dt = JSON.parse(data1);

                                                                if(dt.success==1)
                                                                {
                                                                  location.reload(true);
                                                                }
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
			/* @var $form TbActiveForm */
			?>
			<?php
			echo
			$form->errorSummary($model);
			?>
            <div class="panel-body">
                <div class="row mt10" >
					<div  class="row">
						<div class="col-xs-6 col-md-4 col-sm-4 form-group text-center">
							<?php
							echo
							$form->textFieldGroup($model, 'tqm_priority', array('label'			 => '',
								'htmlOptions'	 => array('placeholder' => 'Priority'),
								'widgetOptions'	 => ['htmlOptions' => ['placeholder' => 'Set Priority', 'required' => true]]))
							?>
						</div>
						<div class="col-xs-6 col-sm-4 col-md-4 form-group text-center">
							<?php
							echo
							$form->textFieldGroup($model, 'tqm_queue_weight', array('label'			 => '',
								'htmlOptions'	 => array('placeholder' => 'Priority'),
								'widgetOptions'	 => ['htmlOptions' => ['placeholder' => 'Set Weightage', 'required' => true]]))
							?>
						</div>
                    </div>
                    <div class="row">
						<div class="col-xs-6 col-md-4 col-sm-4 form-group">
							<?php
							$teamList	 = Teams::getList();
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'tqm_tea_id',
								'val'			 => $model->tqm_tea_id,
								'data'			 => $teamList,
								'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Teams', 'required' => true)
							));
							?>
						</div>
                        <div class="col-xs-6 col-sm-4 col-md-4 form-group">
							<?php
							if (!$flag)
							{
								$queueList = TeamQueueMapping::getList();
								$this->widget('booster.widgets.TbSelect2', array(
									'model'			 => $model,
									'attribute'		 => 'tqm_queue_id',
									'val'			 => $model->tqm_queue_id,
									'data'			 => $queueList,
									'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Queue', 'required' => true)
								));
							}
							else
							{
							$queueList = TeamQueueMapping::getList();
								$this->widget('booster.widgets.TbSelect2', array(
									'model'			 => $model,
									'attribute'		 => 'tqm_queue_id',
									'val'			 => $model->tqm_queue_id,
									'data'			 => [-1 => 'Other then Below List'] + $queueList,
									'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Queue', 'required' => true)
								));
                            echo $form->textFieldGroup($model, 'queueName', array('label' => '',
								'htmlOptions' => array('placeholder' => 'Select Queue'),
								'widgetOptions' => ['htmlOptions' => ['placeholder' => 'Select Queue', 'class' => '']]));
							}
							?>
                        </div>  
                    </div>
                    <div class="row">
						<div class="col-xs-6 col-sm-4 col-md-5 form-group text-center">
							<?php
							if (!$flag)
							{
								echo $form->radioButtonListGroup($model, 'tqm_active', array(
									'label'			 => '', 'widgetOptions'	 => array(
										'data' => [1 => 'Activate', 0 => 'Deactivate'],
									), 'groupOptions'	 => ['class' => 'pl20'], 'inline'		 => true
										),
								);
							}
							?>
                        </div>
                    </div>
                </div>
                <div class="row mt10 text-center" >
                    <div class="col-xs-6 col-sm-2 col-md-4 form-group">
                        <button class="btn btn-info full-width" type="submit"  name="Submit">Submit</button>
                    </div>
                </div>
            </div>
			<?php $this->endWidget(); ?>
        </div>
    </div>
</div>
<script>
$("#TeamQueueMapping_queueName").hide();
$('#TeamQueueMapping_tqm_queue_id').change(function () 
	{
    if ((this.value) == -1) {
        $("#TeamQueueMapping_queueName").show();
		$("#s2id_TeamQueueMapping_tqm_queue_id").hide();
    }
    else{
        $("#TeamQueueMapping_queueName").hide();
    }
});
</script>
