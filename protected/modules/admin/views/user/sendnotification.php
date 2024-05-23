<div class="row">
    <div class="col-xs-12 col-sm-4 col-sm-offset-4 float-none marginauto">
		<?php
		$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'sendNotification',
			'enableClientValidation' => TRUE,
			'clientOptions'			 => array(
				'validateOnSubmit'	 => true,
				'errorCssClass'		 => 'has-error'
			),
			'enableAjaxValidation'	 => false,
			'errorMessageCssClass'	 => 'help-block',
			'htmlOptions'			 => array(
				'class'	 => 'form-horizontal',
				'name'	 => 'sendNotification',
			),
		));
		?>
        <div class="panel panel-default panel-border pt20">
            <div class="panel-body pt0">
                <div class="col-xs-12 mb10 pl0 pr0">
					<?
					$ntfTypes	 = $model->getNtfTypes();
					foreach ($ntfTypes as $key => $val)
					{
						$jsonntfTypes[] = array("id" => $key, "text" => $val);
					}
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $model,
						'attribute'		 => 'ntf_type',
						'val'			 => $model->ntf_type,
						'asDropDownList' => FALSE,
						'options'		 => array('data' => new CJavaScriptExpression(CJSON::encode($jsonntfTypes))),
						'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Notification Type', 'value' => $model->ntf_type)
					));
					?>
                </div>
                <div class="col-xs-12 mb10 pl0 pr0">
					<?
					$ntfMessageTypes = $model->getNtfMessageTypes();
					foreach ($ntfMessageTypes as $key => $val)
					{
						$jsonntfMessageTypes[] = array("id" => $key, "text" => $val);
					}
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $model,
						'attribute'		 => 'ntf_message_type',
						'val'			 => $model->ntf_message_type,
						'asDropDownList' => FALSE,
						'options'		 => array('data' => new CJavaScriptExpression(CJSON::encode($jsonntfMessageTypes))),
						'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Meassage Type', 'value' => $model->ntf_message_type)
					));
					?>
                </div>
                <div class="col-xs-12">
					<?= $form->textFieldGroup($model, 'ntf_title', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter your title here', 'required' => 'required')))); ?>
                </div>
                <div class="col-xs-12">
					<?= $form->textFieldGroup($model, 'ntf_coin_value', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter coin value', 'required' => 'required')))); ?>
                </div>
                <div class="col-xs-12 mb10">
					<?= $form->textAreaGroup($model, 'ntf_message', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter your message here', 'required' => 'required', 'style' => 'height:100px;')))); ?>
                </div>
                <div class="col-xs-12 mb10 text-center">
					<?= CHtml::submitButton('Submit', ['class' => "btn btn-primary pl50 pr50"]) ?>
                </div>
				<?php $this->endWidget(); ?>
            </div>
        </div>
    </div>
</div>
