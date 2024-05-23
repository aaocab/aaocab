<style>
    .dlgComments .dijitDialogPaneContent{
        overflow: auto;
    }
</style>
<?
$statusList = Booking::model()->getActiveBookingStatus();
$status = Booking::model()->getBookingStatus();
$reconfirmStatus = Booking::model()->getReconfirmStatus();
$cnt = count($model);
?>
<div class="panel panel-default">
    <div class="panel-body">
        <div class="row mb20">
            <div class="col-xs-12 bordered pt10">
                <div class="row">
                    <div class="col-xs-12 text-center  mb5">
                        <label for="type" class="control-label"><span style="font-weight: normal; font-size: 15px;">Trip Id:</span> </label>
                        <b><?= $model[0]['bkg_bcb_id']; ?></b>
                    </div>
                    <div class="col-xs-12 text-center  mb5">
                        <label for="type" class="control-label"><span style="font-weight: normal; font-size: 15px;">Trip Vendor Amount:</span> </label>
                        <b><?= $model[0]['bcb_vendor_amount'] | 0; ?></b>
                    </div>
                    <?php if ($model[0]['vnd_name']) { ?>
                        <div class="col-xs-12 text-center  mb5">
                            <label for="type" class="control-label"><span style="font-weight: normal; font-size: 15px;">Vendor Name:</span> </label>
                            <b><?= $model[0]['vnd_name']; ?></b>
                        </div>
                    <?php } ?>
                    <?php if ($model[0]['drv_name']) { ?>
                        <div class="col-xs-12 text-center  mb5">
                            <label for="type" class="control-label"><span style="font-weight: normal; font-size: 15px;">Driver Name:</span> </label>
                            <b><?= $model[0]['drv_name']; ?></b>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="row">
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
                'action' => Yii::app()->createUrl('rcsr/booking/updatevendoramount'),
                'htmlOptions' => array(
                    'class' => 'form-horizontal',
                ),
            ));
            /* @var $form TbActiveForm */
            ?>
            <?= $form->hiddenField($cabmodel, 'bcb_id') ?>
            <?php
            if (($model[0]['bkg_status']) == 6 && ($model[0]['bkg_status'] == 7)) {
                $saveVendorAccount = "Update Vendor Account";
            } else {
                $saveVendorAccount = "Save";
            }
            if($model[0]['bkg_status'] == 6 || $model[0]['bkg_status'] == 7){
            ?>
              <?}else{?>
                <div class="col-xs-4 col-xs-offset-3"> 
                    <?= $form->textFieldGroup($cabmodel, 'bcb_vendor_amount', ['label' => 'Set Vendor Amount']) ?>
                </div>
                <div class="col-xs-2 text-left pt20 mt5">
                    <?php echo CHtml::submitButton($isNew, array('class' => 'btn btn-primary', 'value' => $saveVendorAccount)); ?>
                </div>
              <?}?>
            <?php $this->endWidget(); ?>
        </div>
        <div class="row mb20">
            <div class="col-xs-12  ">
                <? foreach ($model as $key => $val) { ?>
                    <div class="row mb20">
                        <div class="col-xs-12 bordered pb0">
                            <div class="row">
                                <div class="col-xs-12 text-center    pt10 heading_box">
                                    <div class="control-label">Booking Id: 
                                        <b><?= $val['bkg_booking_id']; ?></b>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12  new-booking-list">
                                    <div class="row   pt0  ">
                                        <div class="col-xs-12 heading_box">Booking Information</div>
                                        <div class="col-xs-12 main-tab1-minheight">
                                            <div class="row new-tab-border-b">
                                                <div class="col-xs-12 col-sm-6 new-tab-border-r">
                                                    <div class="row new-tab1">
                                                        <div class="col-xs-5"><b>Booking Type:</b></div>
                                                        <div class="col-xs-7"><?= Booking::model()->getBookingType($val['bkg_booking_type']); ?></div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-6">
                                                    <div class="row new-tab1">
                                                        <div class="col-xs-5"><b>Booking Status:</b></div>
                                                        <div class="col-xs-7"><?= $status[$val['bkg_status']] ?> <?php
                                                            if ($val['bkg_status'] != '9' || $val['bkg_status'] != '8') {
                                                                echo '(' . $reconfirmStatus[$val['bkg_reconfirm_flag']] . ')';
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row new-tab-border-b">
                                                <div class="col-xs-12 col-sm-6 new-tab-border-r">
                                                    <div class="row new-tab1">
                                                        <div class="col-xs-5"><b>Route:</b></div>
                                                        <div class="col-xs-7"><?= $val['frm_city_name'] . ' to ' . $val['to_city_name']; ?></div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-6 new-tab-border-r">
                                                    <div class="row new-tab1">
                                                        <div class="col-xs-5"><b>Vendor Amount:</b></div>
                                                        <div class="col-xs-7"><?= $val['bkg_vendor_amount']; ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row new-tab-border-b">
                                                <div class="col-xs-12 col-sm-6 new-tab-border-r">
                                                    <div class="row new-tab1">
                                                        <div class="col-xs-5"><b>Trip Distance:</b></div>
                                                        <div class="col-xs-7"><?= ($val['bkg_trip_distance'] != '') ? $val['bkg_trip_distance'] . " Km" : "&nbsp;" ?></div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-6">
                                                    <div class="row new-tab1">
                                                        <div class="col-xs-5"><b>Trip Duration:</b></div>
                                                        <div class="col-xs-7"><?= Filter::getDurationbyMinute($val['bkg_trip_duration']); ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row new-tab-border-b">
                                                <div class="col-xs-12 col-sm-6 new-tab-border-r">
                                                    <div class="row new-tab1">
                                                        <div class="col-xs-5"><b>Pickup Date:</b></div>
                                                        <div class="col-xs-7"><?= date('d/m/Y', strtotime($val['bkg_pickup_date'])); ?></div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-6">
                                                    <div class="row new-tab1">
                                                        <div class="col-xs-5"><b>Pickup Time:</b></div>
                                                        <div class="col-xs-7"><?= date('h:i A', strtotime($val['bkg_pickup_date'])); ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <? if ($model->bkg_return_date != '' && $model->bkg_booking_type == '2') { ?>
                                                <div class="row new-tab-border-b">
                                                    <div class="col-xs-12 col-sm-6 new-tab-border-r">
                                                        <div class="row new-tab1">
                                                            <div class="col-xs-5"><b>Return Date:</b></div>
                                                            <div class="col-xs-7"><?= date('d/m/Y', strtotime($val['bkg_return_date'])); ?></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-6">
                                                        <div class="row new-tab1">
                                                            <div class="col-xs-5"><b>Return Time:</b></div>
                                                            <div class="col-xs-7"><?= date('h:i A', strtotime($val['bkg_return_date'])); ?></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <? } ?>
                                            <div class="row new-tab-border-b">
                                                <div class="col-xs-12 col-sm-6 new-tab-border-r">
                                                    <div class="row new-tab1">
                                                        <div class="col-xs-5"><b>Info source:</b></div>
                                                        <div class="col-xs-7"><?= ( $val['bkg_info_source'] != '') ? $val['bkg_info_source'] : "&nbsp;" ?></div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-6 new-tab-border-r">
                                                    <div class="row new-tab1">
                                                        <div class="col-xs-5"><b>Vendor Name:</b></div>
                                                        <div class="col-xs-7"><?= $val['vnd_name'] ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <? if (($val['bkg_status'] == 8 || $val['bkg_status'] == 9) && $val['bkg_cancel_delete_reason'] != '') { ?>
                                                    <?
                                                    $reason = '';
                                                    if ($val['bkg_status'] == 8) {
                                                        $reason = 'Delete';
                                                    }
                                                    if ($val['bkg_status'] == 9) {
                                                        $reason = 'Cancel';
                                                    }
                                                    ?>
                                                    <div class="col-xs-12 col-sm-6">
                                                        <div class="row new-tab1">
                                                            <div class="col-xs-5"><b><?= $reason ?> Reason:</b></div>
                                                            <div class="col-xs-7"><?= $val['bkg_cancel_delete_reason'] ?></div>
                                                        </div>
                                                    </div>
                                                <? } ?>
                                            </div>
                                            <? if ($val['drv_name'] != '') { ?>
                                                <div class="row new-tab-border-b">
                                                    <div class="col-xs-12 col-sm-6 new-tab-border-r">
                                                        <div class="row new-tab1">
                                                            <div class="col-xs-5"><b>Driver Name:</b></div>
                                                            <div class="col-xs-7"><?= $val['drv_name'] ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <? } ?>
                                            <div class="row">
                                                <div class="col-xs-12 p0">
                                                    <div class="hostory_leftdeep mt0">
                                                        <div class="col-xs-12 col-sm-6 col-md-4">
                                                            <div class="row p5">
                                                                <div class="col-xs-6 col-sm-12"><b>Pickup Location</b></div>
                                                                <div class="col-xs-6 col-sm-12"><?= $val['bkg_pickup_address']; ?></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-12 col-sm-6 col-md-4">
                                                            <div class="row p5">
                                                                <div class="col-xs-6 col-sm-12"><b>Dropoff Location</b></div>
                                                                <div class="col-xs-6 col-sm-12"><?= $val['bkg_drop_address']; ?></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <? } ?>
            </div>
        </div>
    </div>
</div>

