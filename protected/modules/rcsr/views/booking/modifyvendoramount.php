<? 
$bkgIds = [$model->bkg_id];
$higherVechileType = Booking::model()->getHigherVehicleTypeInBookings($bkgIds);
$quote = Quotation::model()->getVendorAmountListByBookingIds($bkgIds);
?>
<div class="panel panel-default">
    <div class="panel-body">
        <div class="row">
            <div class="col-xs-4 text-right"><label class="control-label">Proposed Vendor Amount : </label></div>
            <? //= $model->bkg_id ?>
            <div class="col-xs-6">  <?= $quote[$higherVechileType] ?></div>
            
        </div>
        <div class="row">
            <div class="col-xs-12">
                <?php
                $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
                    'id' => 'vendor-form', 'enableClientValidation' => TRUE,
                    'clientOptions' => array(
                        'validateOnSubmit' => true,
                        'errorCssClass' => 'has-error'
                    ),
                    // Please note: When you enable ajax validation, make sure the corresponding
                    // controller action is handling ajax validation correctly.
                    // See class documentation of CActiveForm for details on this,
                    // you need to use the performAjaxValidation()-method described there.
                    'enableAjaxValidation' => false,
                    'errorMessageCssClass' => 'help-block',
                    'htmlOptions' => array(
                        'class' => 'form-horizontal',
                    ),
                ));
                /* @var $form TbActiveForm */
                ?>
                <?= $form->hiddenField($cabmodel, 'bcb_id') ?>

                <div class="row">
                    <div class="col-xs-4 text-right"><label class="control-label">Set Vendor Amount: </label></div>
                    <div class="col-xs-6"> <?= $form->textFieldGroup($cabmodel, 'bcb_vendor_amount', ['label' => '']) ?>
                    </div>
                </div>
                <div class="panel-footer" style="text-align: center">
                    <?php echo CHtml::submitButton($isNew, array('class' => 'btn btn-primary')); ?>
                </div>
                <?php $this->endWidget(); ?>
            </div>
        </div>
    </div>
</div>