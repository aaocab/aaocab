<?php
Yii::app()->clientScript->registerCssFile(ASSETS_URL . '/plugins/form-select2/select2.css');
?>
<style>
    .form-horizontal .checkbox-inline{
        padding-top: 0;
        padding-left: 0!important;
    }  
    .tt-suggestion {
        font-size: 1.2em;
        line-height: 0.7em;
        padding: 0;
        margin:  0;
    }
    .new-booking-list .form-horizontal .form-group{ margin-left: 0; margin-right: 0;}

</style>

<div class="row">
    <div class="col-md-6 col-sm-8 pt20 new-booking-list" style="float: none; margin: auto">
        <div style="text-align:center;" class="col-xs-12">

        </div>
        <div class="row">
            <div class="panel panel-default panel-border">
				<div class="panel-body">
					<?php
					$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
						'id'					 => 'vehicle-type-register-form', 'enableClientValidation' => TRUE,
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
                    <div class="panel panel-default">
                        <div class="panel-body">
							<?php echo CHtml::errorSummary($model); ?>
                            <div class="row">
                                <div class="col-xs-12 col-md-6">
									<?php
										echo $form->typeAheadGroup($model, 'cfg_name', array('widgetOptions'	 => array(
											'options'		 => array(
												'hint'		 => false,
												'highlight'	 => true
											),
											'htmlOptions'	 => array('id' => 'cfg_name', 'required' => true)
										),
										'label'			 => 'Config Name',
											)
									);
									?>
									<?php echo $form->error($model, 'cfg_name'); ?>
                                </div>
                                <div class="col-xs-12 col-md-6">
									<?php
										echo $form->typeAheadGroup($model, 'cfg_value', array('widgetOptions'	 => array(
											'options'		 => array(
												'hint'		 => false,
												'highlight'	 => true
											),
											'htmlOptions'	 => array('id' => 'cfg_value', 'required' => true)
										),
										'label'			 => 'Config Value',
											)
									);
									?>
									<?php echo $form->error($model, 'cfg_value'); ?>
                                </div>
                            </div>
							<div class="row">
									<div class="col-xs-12 "><label>Description</label></div>
												<div class="col-xs-12">  
												    <?php echo $form->textAreaGroup($model, 'cfg_description', array('label' => '')); ?>
												</div>
								</div>
								<div class="row">
									<div class="col-xs-12">
									<?php
										echo $form->typeAheadGroup($model, 'cfg_env', array('widgetOptions'	 => array(
											'options'		 => array(
												'hint'		 => false,
												'highlight'	 => true
											),
											'htmlOptions'	 => array('id' => 'cfg_env')
										),
										'label'			 => 'Environment',
											)
									);
										?>
										<?php echo $form->error($model, 'cfg_env'); ?>
									</div>
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
</div>
<?php echo CHtml::endForm(); ?>