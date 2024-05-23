

<style>
    .dlgComments .dijitDialogPaneContent{
        overflow: auto;
    }
</style>
<div class="panel-advancedoptions">
    <div class="row">
        <div class="col-md-12">            
            <div class="panel">
                <div class="panel-body panel-no-padding p0 pt10">
                    <div class="panel-scroll1">


<div class="col-lg-12 col-md-12 col-sm-12 col-sm-12 pt10" style="float: none; margin: auto">
		<div class="col-xs-12  ">
			<div class="panel ">
				<div class="panel panel-heading mb0">Dailer Information</div>

					<?php
					$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
						'id'					 => 'admins-register-form', 'enableClientValidation' => true,
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
					?>
				<?= $form->hiddenField($model, 'adm_id', ['value' => $model->adm_id]) ?>
				<div class="panel-body pl50">

					<?php echo $form->errorSummary($model); ?>
					<div class="row">
						<div class="col-xs-12 col-sm-6 col-md-6 ">
							
							<?= $form->textFieldGroup($model, 'adm_dailer_username', array('label' => 'User ID')) ?>
						</div>
						
					</div>

                 <div class="row">
					
						<div class="col-xs-12 col-sm-6 col-md-6 ">
							
						<?= $form->passwordFieldGroup($model, 'adm_dailer_password', array('label' => 'Password')) ?>
						</div>
					</div>
<div class="row">
					<div class="text-left   pr20">
					<input class="btn btn-primary btn-lg pl50 pr50"  type="submit"  value="Submit" />
				</div>
</div>

				</div>
				

				<?php $this->endWidget(); ?>

			</div>
		</div>
	</div>
 </div>
                </div>
            </div>
        </div>
    </div>
</div>