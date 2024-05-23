
<div class="row">
    <div class="col-md-12 col-sm-10 col-xs-12">
        <div class="panel panel-white">
			<?php
			$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
				'id'					 => 'followuplog', 'enableClientValidation' => true,
				'clientOptions'			 => array(
					'validateOnSubmit'	 => true,
					'errorCssClass'		 => 'has-error',
					'afterValidate'		 => 'js:function(form,data,hasError){
                                            if(!hasError){

                                                $.ajax({
                                                "type":"POST",
                                                "url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('admin/followUp/add', ['Id' => $_REQUEST['Id']])) . '",
                                                "data":form.serialize(),
                                                        "success":function(data1){
var dt = JSON.parse(data1);

                                                                if(dt.success1==1)
                                                                {

                                                             location.reload(true);
                                                                }
                                                        },
                                                });
                                            }
                                        }'
				),
				// Please note: When you enable ajax validation, make sure the corresponding
				// controller action is handling ajax validation correctly.
				// See class documentation of CActiveForm for details on this,
				// you need to use the performAjaxValidation()-method described there.
				'enableAjaxValidation'	 => false,
				'errorMessageCssClass'	 => 'help-block',
				'htmlOptions'			 => array(
					'class' => '',
				),
			));
			/* @var $form TbActiveForm */
			?>
			<?php echo
			$form->errorSummary($model);
		 
		 
			?>
            <div class="panel-body">
                <div class="row mt10" >
                    <div class="col-xs-6 col-md-4 col-sm-4 form-group text-center">
						<?=
						$form->textAreaGroup($model, 'fpl_remarks', array('label'			 => '',
							'htmlOptions'	 => array('placeholder' => 'Remarks'),
							'widgetOptions'	 => ['htmlOptions' => ['placeholder' => 'Remarks']]))
						?>
                    </div>
                    <div class="col-xs-6 col-sm-3 col-md-5 form-group text-center">
						<?php
						//$eventArr_Re	 = FollowupLog::getEventList();
						//unset($eventArr_Re[5]);
						$eventList	 = FollowupType::getJSON(FollowupLog::getEventList_v1(), 1);
						//$eventList_Re	 = FollowupType::getJSON($eventArr_Re, 1);
						$this->widget('booster.widgets.TbSelect2', array(
							'model'			 => $model,
							'attribute'		 => 'fpl_event_id',
							'val'			 => $model->fpl_event_id,
							'asDropDownList' => FALSE,
							'options'		 => array('data' => new CJavaScriptExpression($eventList), 'allowClear' => true),
							'htmlOptions'	 => array('class' => '', 'style' => 'width: 100%', 'placeholder' => 'Select Events')
						));
						?>
                    </div>

                    <div class="col-xs-6 col-sm-2 col-md-3 form-group">
                        <button class="btn btn-info full-width" type="submit"  name="Submit">Submit</button>
                    </div>
                </div>
            </div>
			<?php $this->endWidget(); ?>
        </div>
    </div>
</div>
