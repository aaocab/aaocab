<?
if (!$checkaccess)
{
	echo $comment;
}
else
{
	?>
	<div class=" ">
		<?php
		$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'addFreezeForm', 'enableClientValidation' => true,
			'clientOptions'			 => array(
				'validateOnSubmit'	 => true,
				'errorCssClass'		 => 'has-error',
				'afterValidate'		 => 'js:function(form,data,hasError){
                    if(!hasError)
                    {
                        $.ajax({
                            "type":"POST",
                            "url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('admin/driver/freeze', ['drv_id' => $model->drv_id, 'drv_is_freeze' => $model->drv_is_freeze])) . '",
                            "data":form.serialize(),
                                "dataType": "json",
                                "success":function(data1)
                                {
                                    if(data1.success)
                                    {
                                        bootbox.hideAll();
                                        refreshDriverGrid();
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
				'class' => 'form-horizontal'
			),
		));
		/* @var $form TbActiveForm */
		?>
		<div class="row" >
			<div class="col-xs-12">
				<div class="panel mb0" >
					<div class="panel-body pt0 pb0">
						<div class="panel-scroll1">
							<div>
								<?= $form->hiddenField($logModel, 'dlg_drv_id', array('value' => $model->drv_id)) ?>
								<div class="form-group">
									<div class="col-xs-12 mt10" id="reasontext" >
										<?= $form->textAreaGroup($logModel, 'dlg_desc', array('label' => '', 'rows' => 10, 'cols' => 50, 'widgetOptions' => array('htmlOptions' => array('placeholder' => $comment)))) ?>
									</div>
								</div>
								<div class="Submit-button text-center" >
									<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary')); ?>
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>
		<?php $this->endWidget(); ?>
	</div>
	<?
}?>