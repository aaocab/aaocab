<?
if ($model->isNewRecord)
{
	$title	 = "Add";
	//CONFIRM
	$js		 = "
	if($.isFunction(window.refreshSkill))
	{
		window.refreshSkill();
	}
	else
	{
		window.location.reload();
	}
    ";
}
//UPDATE
else
{
	$title	 = "Edit";
	$js		 = "	if($.isFunction(window.refreshSkill))
	{
		window.refreshSkill();
	}
	else
	{
		alert('updated');
	}
		";
}

if (!Yii::app()->request->isAjaxRequest)
{
	$panelCss	 = "col-sm-9 col-md-7 col-lg-6 ";
	$panelClass	 = " panel-grape";
}
else
{
	$panelHeading = "display: none";
}
?>
<div class="row">
    <div class="col-xs-12 <?= $panelCss ?>center-block" style="float: none;">
		<?php
		$form = $this->beginWidget('CActiveForm', array(
			'id'					 => 'skills-form-form', 'enableClientValidation' => true,
			'clientOptions'			 => array(
				'validateOnSubmit'	 => true,
				'errorCssClass'		 => 'has-error',
				'afterValidate'		 => 'js:function(form,data,hasError){
                        if(!hasError){
                                $.ajax({
                                        "type":"POST",
					"dataType":"json",
                                        "url":"' . CHtml::normalizeUrl(Yii::app()->request->url) . '",
                                        "data":form.serialize(),
                                        "success":function(data1){
											if($.isEmptyObject(data1)){
												' . $js . '
											}
											else{
												  settings=form.data(\'settings\');
												$.each (settings.attributes, function (i) {
												  $.fn.yiiactiveform.updateInput (settings.attributes[i], data1, form);
												});
												$.fn.yiiactiveform.updateSummary(form, data1);
											}},
                                        });
                                }
                        }'
			),
			'errorMessageCssClass'	 => 'help-block',
			'htmlOptions'			 => array(
				'class' => 'form-horizontal',
			),
		));
		echo $form->errorSummary($model, null, null, array('class' => 'has-error-summary'));
		?>
        <div class="panel<?= $panelClass ?>">
            <div class="panel-heading" style="<?= $panelHeading ?>"><?= $title ?> Skill</div>
            <div class="panel-body">


                <div class="form-group">
					<?php echo $form->textField($model, 'vnd_name', array('class' => 'form-control', 'placeholder' => $model->getAttributeLabel('vnd_name'))); ?>
					<?php echo $form->error($model, 'vnd_name'); ?>

                </div>
                <div class="form-group">
					<?php echo $form->textArea($model, 'vnd_phone', array('class' => 'form-control', 'placeholder' => $model->getAttributeLabel('vnd_phone'))); ?>
					<?php echo $form->error($model, 'vnd_phone'); ?>
                </div>
                <div class="form-group">
					<?php echo $form->textField($model, 'vnd_email', array('class' => 'form-control', 'placeholder' => $model->getAttributeLabel('vnd_email'))); ?>
					<?php echo $form->error($model, 'vnd_email'); ?>

                </div>
                <div class="form-group">
					<?php echo $form->textField($model, 'vnd_address', array('class' => 'form-control', 'placeholder' => $model->getAttributeLabel('vnd_address'))); ?>
					<?php echo $form->error($model, 'vnd_address'); ?>

                </div>




                <div class="col-xs-3 center-block" style="float: none;">
                    <button class="btn btn-primary btn-label" type="submit" name="btnSumbit"><i class="fa fa-save"></i>Submit</button>
                </div>
            </div><!-- form --></div>				<?php $this->endWidget(); ?></div></div>