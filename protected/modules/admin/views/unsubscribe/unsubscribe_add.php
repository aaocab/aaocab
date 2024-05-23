<?php
Yii::app()->clientScript->registerCssFile(ASSETS_URL . '/plugins/form-select2/select2.css');
$cityList = CHtml::listData(Cities::model()->findAll(array('order' => 'cty_name', 'condition' => 'cty_active=:act', 'params' => array(':act' => '1'))), 'cty_id', 'cty_name');
?>
<div class="row">
    <div class="col-lg-offset-1 col-lg-6 col-md-6 col-sm-8 pt20" style="float: none; margin: auto">
        <div style="text-align:center;" class="col-xs-12">
			<?php
			if ($status == "emlext")
			{
				echo "<span style='color:#ff0000;'>This email address is already registered. Please try again using a new email address.</span>";
			}
			elseif ($status == "added")
			{
				echo "<span style='color:#00aa00;'>Driver added successfully.</span>";
			}
			else
			{
				//do nothing
			}
			?>
        </div>
        <div class="row">
            <div class="col-xs-12">
				<?php
				$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
					'id'					 => 'zone-manage-form', 'enableClientValidation' => TRUE,
					'clientOptions'			 => array(
						'validateOnSubmit'	 => true,
						'errorCssClass'		 => 'has-error'
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
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="col-xs-12">
							<?= $form->textFieldGroup($model, 'usb_email') ?>
                        </div>
                    </div>
                    <div class="panel-footer" style="text-align: center">
						<?php echo CHtml::submitButton($isNew, array('class' => 'btn btn-primary')); ?>
                    </div>
                </div>
				<?php $this->endWidget(); ?>
            </div>
        </div>
    </div>
</div>

