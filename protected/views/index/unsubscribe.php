
<style>
    body{ background: #fff;}
</style>
<div class="col-xs-12 bg-warning p30">

</div>
<? if ($success == 1) { ?>
    <div class="col-xs-12">
        <div class="panel">
            <div class="panel-body">
                <div class="col-xs-4 col-xs-offset-4 p10" style="border-left: 5px solid #0766BB;background: #fcf8e3">
                    <span style="color: #00a388;font-weight: bold">You have been successfully un-subscribed from email communications.</span> 
                    <br>If you did this in error, you may re-subscribe by clicking the button below.
                </div>
                <div class="col-xs-4 col-xs-offset-4 p10">
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
    <div class="col-xs-12">
        <div class="panel">
            <div class="panel-body">
                <div class="col-xs-4 col-xs-offset-4 p10" style="border-left: 5px solid #0766BB;background: #fcf8e3">
                    <span style="color: #00a388;font-weight: bold">You have been successfully re-subscribed to our email communications.</span> 
                    <br>Thanks.
                </div>
            </div>     
        </div>
    </div>
<? } else { ?>
    <?php
    $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
        'id' => 'unsubscribe-form', 'enableClientValidation' => FALSE,
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
    ?>
    <div class="col-xs-12">
        <div class="panel">
            <div class="panel-body">
                <div class="col-xs-4 col-xs-offset-4 p10" style="border-left: 5px solid #0766BB;background: #fcf8e3">
                    We're sorry to see you go, hopefully we will see you again one day.
                    Please enter your email address and reason in order to unsubscribe.
                </div>
                <div class="col-xs-4 col-xs-offset-4 p10">
                    <label>Email*</label>
                    <?= $form->textFieldGroup($model, 'usb_email', ['label' => '', 'widgetOptions' => ['htmlOptions' => ['placeholder' => 'Enter Email', 'class' => 'ml15']]]); ?>
                </div>
                <div class="col-xs-4 col-xs-offset-4 p10">
                    <label>Reason</label>
                    <?= $form->textAreaGroup($model, 'usb_reason', ['label' => '', 'widgetOptions' => ['htmlOptions' => ['placeholder' => 'Enter Reason', 'class' => 'ml15']]]); ?>
                </div>
                <div class="col-xs-4 col-xs-offset-4 p10">
                    <label>Choose Category</label>
                </div>
                <div class="col-xs-3 col-xs-offset-4 p10">
                    <?
                    if ($model->usb_cat_promotional == 1) {
                        $cat_promotional = true;
                    } else {
                        $cat_promotional = false;
                    }
                    ?>  
                    <?= $form->checkboxListGroup($model, 'usb_cat_promotional', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Promotional'), 'htmlOptions' => ['checked' => $cat_promotional]), 'inline' => true)) ?>
                    <?
                    if ($model->usb_cat_booking == 1) {
                        $cat_booking = true;
                    } else {
                        $cat_booking = false;
                    }
                    ?>  
                    <?= $form->checkboxListGroup($model, 'usb_cat_booking', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Booking'), 'htmlOptions' => ['checked' => $cat_booking]), 'inline' => true)) ?>
                    <?
                    if ($model->usb_cat_transactional == 1) {
                        $cat_transactional = true;
                    } else {
                        $cat_transactional = false;
                    }
                    ?>  
                    <?= $form->checkboxListGroup($model, 'usb_cat_transactional', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Transactional'), 'htmlOptions' => ['checked' => $cat_transactional]), 'inline' => true)) ?>
                    <?
                    if ($model->usb_cat_driverupdate == 1) {
                        $cat_driverupdate = true;
                    } else {
                        $cat_driverupdate = false;
                    }
                    ?>  
                    <?= $form->checkboxListGroup($model, 'usb_cat_driverupdate', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Driver updates'), 'htmlOptions' => ['checked' => $cat_driverupdate]), 'inline' => true)) ?>
                    <?
                    if ($model->usb_cat_ratings == 1) {
                        $cat_rating = true;
                    } else {
                        $cat_rating = false;
                    }
                    ?>  
                    <?= $form->checkboxListGroup($model, 'usb_cat_ratings', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Rating and reviews'), 'htmlOptions' => ['checked' => $cat_rating]), 'inline' => true)) ?>
                    <?
                    if ($model->usb_cat_accountinfo == 1) {
                        $cat_account_info_and_update = true;
                    } else {
                        $cat_account_info_and_update = false;
                    }
                    ?>  
                    <?= $form->checkboxListGroup($model, 'usb_cat_accountinfo', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Account updates and info'), 'htmlOptions' => ['checked' => $cat_account_info_and_update]), 'inline' => true)) ?>

                </div>
                <div class="col-xs-4 col-xs-offset-4 p10">
                    <button type="submit" class="btn btn-danger pull-right" name="unsub_btn">Un-subscribe</button>
                </div>
            </div>
        </div>
    </div>
    <?php
    $this->endWidget();
}
?>
