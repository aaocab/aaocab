<?
if (!$checkaccess)
{
	echo $comment;
}
else
{
	?>
	<div class="panel-advancedoptions">
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
                            "url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('admin/vehicle/freeze', ['vhc_id' => $model->vhc_id, 'vhc_is_freeze' => $model->vhc_is_freeze])) . '",
                            "data":form.serialize(),
                                "dataType": "json",
                                "success":function(data1)
                                {
                                    if(data1.success)
                                    {
                                        bootbox.hideAll();
                                        refreshVehicleGrid();
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
				<div class="panel" >
					<div class="panel-body panel-body p0">
						<div class="panel-scroll1">
							<div>
								<?= $form->hiddenField($logModel, 'clg_vhc_id', array('value' => $model->vhc_id)) ?>
								<div class="form-group">
									<div class="col-xs-12 mt10" id="reasontext" >
										<?= $form->textAreaGroup($logModel, 'clg_desc', array('label' => '', 'rows' => 10, 'cols' => 50, 'widgetOptions' => array('htmlOptions' => array('placeholder' => $comment)))) ?>
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

<script>
function refreshVehicleGrid() {
		location.reload();
	}
</script>
	<?
}?>