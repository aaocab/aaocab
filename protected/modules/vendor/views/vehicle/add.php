<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/js/jquery.mask.min.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/js/jquery.mask1.min.js');
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');
?>
<style>
    .form-horizontal .checkbox-inline{
        padding-top: 0
    }
</style>
<div class="col-lg-offset-1 col-md-4 col-sm-6 pt20" style="float: none; margin: auto">
    <div class="col-xs-12 mb20" style="color:#008a00;text-align: center">
        <?php echo Yii::app()->user->getFlash('success'); ?>
    </div>
    <div class="col-xs-12 mb20" style="color:#F00;text-align: center">
        <?php echo Yii::app()->user->getFlash('error'); ?>
    </div>
    <div class="row">
        <?php
        $form = $this->beginWidget('booster.widgets.TbActiveForm', array('id' => 'vehicle-form', 'enableClientValidation' => TRUE,
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
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">                  
                    <div class="col-xs-12 col-sm-6" style="margin-right: 10px">
                        <?= $form->hiddenField($model, 'vhc_vehicle_id') ?>
                        <?= $form->hiddenField($model, 'vhc_vendor_id') ?>
                        <?
                                  if($modelVehicle->isNewRecord)
                                  {
                                      $readonly=[];
                                  }
                                  else
                                  {
                                     $readonly=['readOnly'=>'readOnly']; 
                                  }
                        ?>
                        <?= $form->textFieldGroup($model, 'vhc_number', array('label' => '<b>Vehicle Number</b>', 'widgetOptions' => array('htmlOptions' => ['placeholder' => 'Vehicle Number']+$readonly))) ?>
                    </div>   
                    <div class="col-xs-11 col-sm-5">
                        <div class='form-group'>
                            <label><b>Vehicle Model</b></label>
                            <div class="input-group">
                                <?
                                $vtypeList = VehicleTypes::model()->getVehicleTypeList(1);
                                $vTypeData = VehicleTypes::model()->getJSON($vtypeList);
                                $this->widget('booster.widgets.TbSelect2', array(
                                    'model' => $model,
                                    'attribute' => 'vhc_type_id',
                                    'val' => $model->vhc_type_id,
                                    'asDropDownList' => FALSE,
                                    'options' => array('data' => new CJavaScriptExpression($vTypeData)),
                                    'htmlOptions' => array('style' => 'width:100%', 'placeholder' => 'Select a model')
                                ));
                                ?>

                            </div>
                            <span class="has-error"><? echo $form->error($model, 'vhc_type_id'); ?></span>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6" style="margin-right: 10px">
                        <?=
                        $form->numberFieldGroup($model, 'vhc_year', array('label' => '<b>Year</b>',
                            'widgetOptions' => array('htmlOptions' => array('min' => date('Y') - 25, 'max' => date('Y')))));
                        ?> 
                    </div>
                    <div class="col-xs-11 col-sm-5">
                        <?= $form->textFieldGroup($model, 'vhc_color', array('label' => '<b>Color</b>', 'widgetOptions' => array())) ?>
                    </div>


                    <div class="col-xs-11 col-sm-5"  style="margin-right: 10px">
                        <?
                        if ($model->vhc_tax_exp_date) {
                            $model->vhc_tax_exp_date = DateTimeFormat::DateToDatePicker($model->vhc_tax_exp_date);
                        }
                        echo $form->datePickerGroup($model, 'vhc_tax_exp_date', array('label' => '<b>Tax Expiry Date</b>',
                            'widgetOptions' => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'))
                        ));
                        ?>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <?
                        if ($model->vhc_dop) {
                            $model->vhc_dop = DateTimeFormat::DateTimeToDatePicker($model->vhc_dop);
                        }

                        echo $form->datePickerGroup($model, 'vhc_dop', array('label' => '<b>Date of Purchase</b>',
                            'widgetOptions' => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'))
                        ));
                        ?>  
                    </div>
                    <div class="col-xs-12" >
                        <label><b>Vehicle owned by <?= $vendorName ?></b></label>
                        <?= $form->radioButtonListGroup($model, 'vhc_owned_or_rented', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Yes', 2 => 'No')), 'inline' => true)) ?>
                        <?
                        if ($model->vhc_is_attached == 1) {
                            $is_attached = true;
                        } else {
                            $is_attached = false;
                        }
                        ?>
                        <?= $form->checkboxListGroup($model, 'vhc_is_attached', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'is exclusive to Gozo'), 'htmlOptions' => ['checked' => $is_attached]), 'inline' => true)) ?>
                         <?
                        if ($model->vhc_is_commercial == 1) {
                            $is_commercial = true;
                        } else {
                            $is_commercial = false;
                        }
                        ?>  
                        <?= $form->checkboxListGroup($model, 'vhc_is_commercial', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'is commercial'), 'htmlOptions' => ['checked' => $is_commercial]), 'inline' => true)) ?>

                    </div>
                </div>
                <div class="row mt20">
                <div class="col-xs-12">  
                    <label><b>Picture of front license plate</b></label>
                    <div class="form-group">
                        <div class="col-xs-8">
                            <?= $form->fileFieldGroup($model, 'vhc_front_plate', array('label' => '', 'widgetOptions' => array())); ?>
                        </div>
                        <?
                        if ($model->vhc_front_plate != '') {
                            ?>
                            <div class="col-xs-4">
                                <a href="<?= $model->vhc_front_plate ?>" target="_blank"><?= basename($model->vhc_front_plate); ?></a>
                            </div>
                        <? } ?>
                    </div>
                </div>       
                <div class="col-xs-12">  
                    <label><b>Picture of rear license plate</b></label>
                    <div class="form-group">
                        <div class="col-xs-8">
                            <?= $form->fileFieldGroup($model, 'vhc_rear_plate', array('label' => '', 'widgetOptions' => array())); ?>
                        </div>
                        <?
                        if ($model->vhc_rear_plate != '') {
                            ?>
                            <div class="col-xs-4">
                                <a href="<?= $model->vhc_rear_plate ?>" target="_blank"><?= basename($model->vhc_rear_plate); ?></a>
                            </div>
                        <? } ?>
                    </div>
                </div>  

                <div class="col-xs-12">       
                    <div class="form-group">
                        <label> <b>Photo copy of valid insurance for the vehicle with clear insurance end-date information</b></label>
                        <div class="col-xs-8">
                            <?= $form->fileFieldGroup($model, 'vhc_insurance_proof', array('label' => '', 'widgetOptions' => array())); ?>
                        </div>
                        <?
                        if ($model->vhc_insurance_proof != '') {
                            ?>
                            <div class="col-xs-4">
                                <a href="<?= $model->vhc_insurance_proof ?>" target="_blank"><?= basename($model->vhc_insurance_proof); ?></a>
                            </div>
                        <? } ?>
                    </div>
                    <div class="col-xs-12 col-sm-6" style="margin-right: 10px">
                        <?
                        if ($model->vhc_insurance_exp_date) {
                            $model->vhc_insurance_exp_date = DateTimeFormat::DateToDatePicker($model->vhc_insurance_exp_date);
                        }
                        echo $form->datePickerGroup($model, 'vhc_insurance_exp_date', array('label' => '<b>Insurance Expiry Date</b>',
                            'widgetOptions' => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'))
                        ));
                        ?>
                    </div>
                </div>   

                <div class="col-xs-12">   
                    <label><b>Photocopy of Pollution under control certificate with readable end date</b></label>
                    <div class="form-group">
                        <div class="col-xs-8">
                            <?= $form->fileFieldGroup($model, 'vhc_pollution_certificate', array('label' => '', 'widgetOptions' => array())); ?>
                        </div>
                        <?
                        if ($model->vhc_pollution_certificate != '') {
                            ?>
                            <div class="col-xs-4">
                                <a href="<?= $model->vhc_pollution_certificate ?>" target="_blank"><?= basename($model->vhc_pollution_certificate); ?></a>
                            </div>
                        <? } ?>
                        <div class="col-xs-8">
                            <?
                            if ($model->vhc_pollution_exp_date) {
                                $model->vhc_pollution_exp_date = DateTimeFormat::DateToDatePicker($model->vhc_pollution_exp_date);
                            }
                            echo $form->datePickerGroup($model, 'vhc_pollution_exp_date', array('label' => '<b>Pollution under control certificate End Date</b>',
                                'widgetOptions' => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'))
                            ));
                            ?>
                        </div>
                    </div>
                </div>     
                <div class="col-xs-12">   
                    <label><b>Photocopy of Registration certificate for the vehicle with readable end date</b></label>
                    <div class="form-group">
                        <div class="col-xs-8">
                            <?= $form->fileFieldGroup($model, 'vhc_reg_certificate', array('label' => '', 'widgetOptions' => array())); ?>
                        </div>
                        <?
                        if ($model->vhc_reg_certificate != '') {
                            ?>
                            <div class="col-xs-4">
                                <a href="<?= $model->vhc_reg_certificate ?>" target="_blank"><?= basename($model->vhc_reg_certificate); ?></a>
                            </div>
                        <? } ?>

                        <div class="col-xs-8">
                            <?
                            if ($model->vhc_reg_exp_date) {
                                $model->vhc_reg_exp_date = DateTimeFormat::DateToDatePicker($model->vhc_reg_exp_date);
                            }
                            echo $form->datePickerGroup($model, 'vhc_reg_exp_date', array('label' => '<b>Registration End Date</b>',
                                'widgetOptions' => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'))
                            ));
                            ?>
                        </div>
                    </div>
                </div>     
                <div class="col-xs-12">    
                    <label><b>Photocopy of applicable commercial permits for the vehicle with readable end date</b></label>
                    <div class="form-group">
                        <div class="col-xs-8">
                            <?= $form->fileFieldGroup($model, 'vhc_permits_certificate', array('label' => '', 'widgetOptions' => array())); ?>
                        </div>
                        <?
                        if ($model->vhc_permits_certificate != '') {
                            ?>
                            <div class="col-xs-4">
                                <a href="<?= $model->vhc_permits_certificate ?>" target="_blank"><?= basename($model->vhc_permits_certificate); ?></a>
                            </div>
                        <? } ?>
                        <div class="col-xs-8">
                            <?
                            if ($model->vhc_commercial_exp_date) {
                                $model->vhc_commercial_exp_date = DateTimeFormat::DateToDatePicker($model->vhc_commercial_exp_date);
                            }
                            echo $form->datePickerGroup($model, 'vhc_commercial_exp_date', array('label' => '<b>commercial permits end date</b>',
                                'widgetOptions' => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'))
                            ));
                            ?>
                        </div>
                    </div>

                    <div class="col-xs-12">    
                        <label><b>Photocopy of fitness certificate for the vehicle with readable end date</b></label>
                        <div class="form-group">
                            <div class="col-xs-8">
                                <?= $form->fileFieldGroup($model, 'vhc_fitness_certificate', array('label' => '', 'widgetOptions' => array())); ?>
                            </div>
                            <?
                            if ($model->vhc_fitness_certificate != '') {
                                ?>
                                <div class="col-xs-4">
                                    <a href="<?= $model->vhc_fitness_certificate ?>" target="_blank"><?= basename($model->vhc_fitness_certificate); ?></a>
                                </div>
                            <? } ?>
                            <div class="col-xs-8">
                                <?
                                if ($model->vhc_fitness_cert_end_date) {
                                    $model->vhc_fitness_cert_end_date = DateTimeFormat::DateToDatePicker($model->vhc_fitness_cert_end_date);
                                }
                                echo $form->datePickerGroup($model, 'vhc_fitness_cert_end_date', array('label' => '<b>Fitness Certificate Expiry Date</b>',
                                    'widgetOptions' => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'))
                                ));
                                ?>
                            </div>
                        </div>
                    </div>     
                </div>
                </div>
                <div class="panel-footer" style="text-align: center">
                    <?php echo CHtml::submitButton('submit', array('class' => 'btn btn-primary')); ?> 
                </div>
                <?php $this->endWidget(); ?>
            </div>
        </div>
    </div>
</div>
<script  type="text/javascript">

    $('#Vehicles_vhc_insurance_exp_date').datepicker({
        format: 'dd/mm/yyyy'
    });
    $('#Vehicles_vhc_tax_exp_date').datepicker({
        format: 'dd/mm/yyyy'
    });
    $('#Vehicles_vhc_dop').datepicker({
        format: 'dd/mm/yyyy'
    });

    $('#<?= CHtml::activeId($model, 'vhc_number') ?>').mask('AA 0Z YYY 0000', {
        translation: {
            'Z': {
                pattern: /[0-9]/, optional: true
            },
            'Y': {
                pattern: /[A-Za-z]/, optional: true
            },
            'X': {
                pattern: /[0-9A-Za-z]/, optional: true
            },
            'A': {
                pattern: /[A-Za-z]/, optional: false
            },
        },
        placeholder: "__ __ __ ____",
        clearIfNotMatch: true
    });
</script>
