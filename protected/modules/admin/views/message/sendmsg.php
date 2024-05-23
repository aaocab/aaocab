

<?
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/plugins/form-typeahead/typeahead.bundle.min.js');
$jsrefresh	 = "
if($.isFunction(window.redirectList))
{
window.redirectList();
}
else
{
window.location.reload();
}
";
?>

<div class="row mb20">
    <div class="col-lg-4 col-md-6 col-sm-8 col-sm-10 pt10" style="float: none; margin: auto">
        <div class="col-xs-12 text-center">

			<?
			$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
				'id'					 => 'sms-form', 'enableClientValidation' => true,
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
					'class' => 'form-horizontal',
				),
			));
			/* @var $form TbActiveForm */
			?>
            <div class="panel panel-default pb5">
                <div class="panel-body text-left">
                    <div class="col-xs-12 ">
						<?= CHtml::errorSummary($model); ?>
						<?= $form->textFieldGroup($model, 'number', array('widgetOptions' => ['htmlOptions' => []])) ?>
						<?= $form->textAreaGroup($model, 'message', array('widgetOptions' => ['htmlOptions' => []])) ?>
						<?= $form->textFieldGroup($model, 'booking_id', array('widgetOptions' => ['htmlOptions' => []])) ?>
                    </div>

                </div>
                <div class="panel-footer text-center pt5 pb5 border-bottom">
                    <input class="btn btn-primary"  type="submit" name="sub" value="Submit" />
                </div>

				<?php $this->endWidget(); ?>
            </div>
        </div>
    </div>
</div>


