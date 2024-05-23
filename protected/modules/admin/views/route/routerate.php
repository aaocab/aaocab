<style>
    .dlgComments .dijitDialogPaneContent{
        overflow: auto;
    }
</style>

<div class="panel-advancedoptions" >
    <div class="row">
        <div class="col-md-12">  
			<?php
			$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
				'id'					 => 'route_rate_form', 'enableClientValidation' => true,
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
					'class'		 => 'form-horizontal', 'enctype'	 => 'multipart/form-data'
				),
			));
			/* @var $form TbActiveForm */
			?>
            <div class="panel" >
                <div class="panel-body panel-no-padding p0 pt10">
                    <div class="panel-scroll1">
                        <div style="width: 100%; overflow: auto;  border: 1px #aaa solid;color: #444;">
                            <div class="row">

                            </div>
                            <div class="col-xs-12">  <div class="panel-footer" style="text-align: center">
									<?php echo CHtml::submitButton($isNew, array('class' => 'btn  btn-primary')); ?>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
			<?php $this->endWidget(); ?>
        </div>
    </div>
</div>