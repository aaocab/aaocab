
<style>
    body{ background: #fff;}
</style>

<? if ($success == 1) { ?>
    <div class="mt30 col-12 col-lg-6 offset-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="col-12 p10" style="border-left: 5px solid #0766BB;background: #fcf8e3">
                    <span style="color: #00a388;font-weight: bold">You have been successfully un-subscribed from email communications.</span> 
                    <br>If you did this in error, you may re-subscribe by clicking the button below.
                </div>
                <div class="col-12 text-center p10">
                    <?php
                    $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
                        'id' => 'resubscribe-form', 'enableClientValidation' => FALSE,
                        'clientOptions' => array(
                            'validateOnSubmit' => true,
                            'errorCssClass' => 'has-error'
                        ),
                        'enableAjaxValidation' => false,
                        'errorMessageCssClass' => 'help-block',
                        'htmlOptions' => array(
                            'class' => 'form-horizontal', 'enctype' => 'multipart/form-data'
                        ),
                    ));
                    /* @var $form TbActiveForm */
                    ?>
                    <input type="hidden" name="unsub_email" value="<?= $model->usb_email ?>">
                    <button type="submit" class="btn btn-success pull-right" name="resub_btn">Re-subscribe</button>
                    <?php $this->endWidget(); ?>
                </div>
            </div>     
        </div>
    </div>
<? } else if ($success == 2) { ?>
    <div class="col-12 col-lg-6 offset-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="col-12 p10" style="border-left: 5px solid #0766BB;background: #fcf8e3">
                    <span style="color: #00a388;font-weight: bold">You have been successfully re-subscribed to our email communications.</span> 
                    <br>Thanks.
                </div>
            </div>     
        </div>
    </div>
<? } else { ?>
    <?php
		$form = $this->beginWidget('CActiveForm', array(
		'id'					 => 'unsubscribe-form', 'enableClientValidation' => true,
		'clientOptions'			 => array(
			'validateOnSubmit'	 => true,
			'errorCssClass'		 => 'has-error',
			'afterValidate'		 => ''
		),
		'errorMessageCssClass'	 => 'help-block',
		'htmlOptions'			 => array(
			'class'		 => '', 'enctype'	 => 'multipart/form-data'
		),
	));
	?>
    <div class="col-12 col-lg-6 offset-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="col-12 p10" style="border-left: 5px solid #0766BB;background: #fcf8e3">
                    We're sorry to see you go, hopefully we will see you again one day.
                    Please enter your email address and reason in order to unsubscribe.
                </div>
				<?php if($errors[user_id][0] != ''){ ?>
					<div class="has-error text-danger"><?php echo $errors[user_id][0]; ?></div>
				<?php } ?>
                <div class="col-12 p10">
                    <label>Email*</label>
                    <?= $form->emailField($model, 'usb_email', ['class'=> "form-control", 'value' => $email, 'readonly' => true, 'widgetOptions' => ['htmlOptions' => [ 'placeholder' => 'Enter Email', ]]]); ?>
                </div>
                <div class="col-12 p10">
                    <label>Reason</label>
                    <?= $form->textArea($model, 'usb_reason', ['class'=> "form-control", 'label' => '', 'widgetOptions' => ['htmlOptions' => ['placeholder' => 'Enter Reason', 'class' => '']]]); ?>
                </div>
                <div class="col-12 p10">
                    <label>Choose Category</label>
                </div>
                <div class="col-12 p10">
						<div class="form-group checkbox mr-2">
							<?php echo $form->checkbox($model, 'usb_cat_promotional', ['label' => "", 'groupOptions' => ["class" => "checkbox-input"]]);
							?>
							<label for="Unsubscribe_usb_cat_promotional">Promotional</label>
						</div>
						<div class="form-group checkbox mr-2">
							<?php echo $form->checkbox($model, 'usb_cat_booking', ['label' => "", 'groupOptions' => ["class" => "checkbox-input"]]);
							?>
							<label for="Unsubscribe_usb_cat_booking">Booking</label>
						</div>
						<div class="form-group checkbox mr-2">
							<?php echo $form->checkbox($model, 'usb_cat_transactional', ['label' => "", 'groupOptions' => ["class" => "checkbox-input"]]);
							?>
							<label for="Unsubscribe_usb_cat_transactional">Transactional</label>
						</div>
						<div class="form-group checkbox mr-2">
							<?php echo $form->checkbox($model, 'usb_cat_driverupdate', ['label' => "", 'groupOptions' => ["class" => "checkbox-input"]]);
							?>
							<label for="Unsubscribe_usb_cat_driverupdate">Driver updates</label>
						</div>
						<div class="form-group checkbox mr-2">
							<?php echo $form->checkbox($model, 'usb_cat_ratings', ['label' => "", 'groupOptions' => ["class" => "checkbox-input"]]);
							?>
							<label for="Unsubscribe_usb_cat_ratings">Rating and reviews</label>
						</div>
						<div class="form-group checkbox mr-2">
							<?php echo $form->checkbox($model, 'usb_cat_accountinfo', ['label' => "", 'groupOptions' => ["class" => "checkbox-input"]]);
							?>
							<label for="Unsubscribe_usb_cat_accountinfo">Account updates and info</label>
						</div>
                </div>
                <div class="col-12 p10">
                    <button type="submit" class="btn btn-danger pull-right" name="unsub_btn">Un-subscribe</button>
                </div>
            </div>
        </div>
    </div>
    <?php
    $this->endWidget();
}
?>
