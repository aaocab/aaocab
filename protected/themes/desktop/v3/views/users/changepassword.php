<div class="container">
	<div class="row">
		<div class="col-lg-8 offset-lg-2">
			<div class="card">
				<div class="card-body">
					<?php
					$form = $this->beginWidget('CActiveForm', array(
						'id'					 => 'cpass-form', 'enableClientValidation' => true,
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
					if ($status == 'no')
					{
						$form->addError($model->old_password, 'The token must contain letters or digits.');
					}
					/* @var $form CActiveForm */
					?>

					<div class="row">
						<div class="badge-circle badge-circle-lg badge-circle-light-success mx-auto mt0">
							<img src="/images/bx-lock-alt.svg" alt="img" width="22" height="22">
						</div>
						<div class="col-12 font-18 mb15 text-center"><b>Change password</b></div>
						<div class="col-12">
							<div class="form-group">
								<label for="old_password">Current password</label>
								<?= $form->passwordField($model, 'old_password', ['required' => TRUE, 'placeholder' => "Current password", 'class' => "form-control"]) ?>
<?php echo $form->error($model, 'old_password', ['class' => 'help-block error']); ?>
							</div>
							<div class="form-group">
								<label for="new_password">New password</label>
								<?= $form->passwordField($model, 'new_password', ['required' => TRUE, 'placeholder' => "New password", 'class' => "form-control"]) ?>
<?php echo $form->error($model, 'new_password', ['class' => 'help-block error']); ?>
							</div>
							<div class="form-group">
								<label for="repeat_password">Confirm password</label>
								<?= $form->passwordField($model, 'repeat_password', ['required' => TRUE, 'placeholder' => "Confirm password", 'class' => "form-control"]) ?>
<?php echo $form->error($model, 'repeat_password', ['class' => 'help-block error']); ?>
							</div>
							<div id="err" style="margin-bottom: 10px;color: #B80606" ><?= $message ?></div>
							<div class="text-center">
								<input class="btn btn-primary"  type="submit" name="changepassword" value="Change password"/>
							</div>
						</div>
					</div>
<?php $this->endWidget(); ?>

                </div>
			</div>
		</div>
	</div>
</div>

