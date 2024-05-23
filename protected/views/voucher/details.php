<style>
    .form-horizontal .form-group{ margin-left: 0; margin-right: 0;}
    .next-btn-back { background: #cdcdcd; text-transform: uppercase; font-size: 18px; font-weight: bold; border: none; padding: 7px 30px; color: #fff; -webkit-border-radius: 2px; -moz-border-radius: 2px; border-radius: 2px; transition: all 0.5s ease-in-out 0s;}
    .v-bg{ background: #f7f7f7;}
</style>
    <div class="row m0 mb20 flex">
        <div class="col-xs-12 h3 m0 text-uppercase text-center">Buy Voucher</div>
    </div>
    <div class="row">
        <?
        $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
        'id' => 'buyForm', 'enableClientValidation' => true,
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
            <div class="col-xs-12 col-sm-9 col-lg-6 col-lg-offset-3">
                <div class="panel panel-default v-bg">
                    <div class="panel-body">
                        <div class="row mb15">
                            <label for="usr_address1" class="col-sm-4 control-label">Voucher</label>
                            <div class="col-sm-8 pt5 font16">
                                <b><?php echo $voucherModel->vch_code; ?></b> (<?= $voucherModel->vch_title; ?>)
                            </div>
                        </div>

                        <div class="row mb15">
                            <label for="usr_address1" class="col-sm-4 control-label">Selling Price</label>
                            <div class="col-sm-8 font24 color-green">
                                &#x20B9;<b><?php echo $voucherModel->vch_selling_price; ?></b>
                            </div>
                        </div>

                        <div class="row mb15">
                            <label for="usr_address2" class="col-sm-4 control-label">Number</label>
                            <div class="col-sm-8">
                                <?php
                                $qtyData = [];
                                for ($i = 1; $i <= 50; $i++) {
                                    $qtyData[$i] = ($i > 0) ? $i : "Select Quantity";
                                }
                                ?>
                                <?= $form->dropDownListGroup($model, 'vsb_qty', array('label' => '', 'widgetOptions' => array('data' => $qtyData))) ?>
                            </div>
                        </div>

                        <div class="row mb15">
                            <label for="usr_address1" class="col-sm-4 control-label">Name</label>
                            <div class="col-sm-8">
<?= $form->textFieldGroup($model, 'vsb_name', array('label' => '', 'class' => 'form-control border-radius')) ?>
                            </div>
                        </div>
                        <div class="row mb15">
                            <label for="usr_address1" class="col-sm-4 control-label">Email</label>
                            <div class="col-sm-8">
<?= $form->textFieldGroup($model, 'vsb_email', array('label' => '', 'class' => 'form-control border-radius')) ?>
                            </div>
                        </div>
                       


                        <div class="row mb15">
                            <div class="col-xs-12 text-right">
                                <button type="submit" class="btn next-btn border-none"  name="sub" value="Submit">Buy</button>
                                <a href="<?= Yii::app()->createUrl('voucher') ?>" class="btn next-btn-back border-none" >Back</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
<?= $form->hiddenField($model, "vsb_vch_id ", ['value' => 1]); ?>
    <?php $this->endWidget(); ?>

