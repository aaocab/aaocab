<style>
    .checkbox-inline{
        padding-left: 15px !important;
    }
</style>
<?php
$typelist	 = array("" => "Select Category", "1" => "User", "2" => "Vendor", "3" => "Meterdown");
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
$dateto		 = ($model->tnc_updated_at != '') ? $model->tnc_updated_at : 'now';
?>

<div class="row mb20">
    <div class="col-lg-4 col-md-6 col-sm-8 col-sm-10 pt10" style="float: none; margin: auto">
        <div class="col-xs-12 text-center">
			<?
			if ($status != "")
			{
				?>
				<span style="color : green;margin-bottom: 10px;"><?= $status; ?></span>   
			<? } ?>
			<?php
			$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
				'id'					 => 'terms-form', 'enableClientValidation' => true,
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
                    <div class="col-xs-12">
						<?= CHtml::errorSummary($model); ?> 
                        <div class="row">
                            <div class="col-xs-12"><label>Terms</label></div>
                            <div class="col-xs-12" style=" padding-left: 0px;padding-right: 0px;">
								 <?php //echo  $form->textArea($model, 'tnc_text', array('style' => 'margin: 0px; width: 349px; height: 142px;')) ?>
								 <?php
								 echo $form->ckEditorGroup(
										 $model, 'tnc_text', array('label'			 => '', 'widgetOptions'	 => array('editorOptions' => array(
											 'plugins' => 'basicstyles,toolbar,enterkey,entities,floatingspace,wysiwygarea,indentlist,list,dialog,dialogui,button,indent,fakeobjects'
										 )))
								 );
								 ?>


                            </div>
                        </div>
						<?=
						$form->dropDownListGroup($model, 'tnc_cat', array('label'			 => '', 'widgetOptions'	 => array(
								'data'			 => $typelist,
								'htmlOptions'	 => array())));
						?>
						<?= $form->textFieldGroup($model, 'tnc_version', array()) ?>
                        <div class="row">
                            <div class="col-xs-12"><label>Update From</label></div>

                            <div class="col-xs-12">    <?=
								$form->datePickerGroup($model, 'tnc_updated_at', array('label'			 => '',
									'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('required' => true, 'value' => date('d/m/Y', strtotime($dateto)))), 'prepend'		 => '<i class="fa fa-calendar"></i>'));
								?>
                            </div></div>




                    </div>
                </div>
                <div class="panel-footer text-center pt5 pb5 border-bottom">
                    <input class="btn btn-primary" type="submit" name="sub" value="Submit"/>
                </div>
				<?php $this->endWidget(); ?>
            </div>
        </div>
    </div>
</div>
 
