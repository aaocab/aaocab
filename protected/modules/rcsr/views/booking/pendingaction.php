<?
$higherVechileType = Booking::model()->getHigherVehicleTypeInBookings($bkgids);
$quote = Quotation::model()->getVendorAmountListByBookingIds($bkgids);
$cnt = count($models);
$totBkgAmt = 0;
$totVendorAmt = 0;
?>
<div class="panel panel-default">
    <div class="panel-body">
        <div class="row">
            <div class="col-xs-12">
                <table class="table table-bordered table-responsive">

                    <tr><th>Booking Id</th><th>Booking Amount</th><th>Vendor Amount</th> <th>Proposed Vendor Amount</th>               
                    </tr>
                    <?
                    $i = 0;
                    foreach ($models as $model) {
                        $totBkgAmt+= $model->bkg_total_amount;
                        $totVendorAmt+= $model->bkg_vendor_amount;
                        ?>
                        <tr>  
                            <td><?= $model->bkg_booking_id ?></td>
                            <td><?= $model->bkg_total_amount ?></td>
                              <td><?= $model->bkg_vendor_amount ?></td>
                            <? if ($i == 0) { ?>
                            <td class='text-center' style="vertical-align: middle" rowspan="<?= $cnt ?>"><?= $quote[$higherVechileType] ?></td> 
                            <? } ?>
                          
                        </tr><?
                            $i++;
                        }
                        ?>
                        <tr>  
                            <td><b>Total</b></td>
                            <td><b><?= $totBkgAmt ?></b></td>
                            <td><b><?= $totVendorAmt ?></b></td>
                           
                          
                        </tr>
                </table>
            </div>
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
                    <div class="col-xs-4 text-right"><label class="control-label">Vendor Amount : </label></div>
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