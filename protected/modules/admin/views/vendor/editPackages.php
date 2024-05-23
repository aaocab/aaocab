<style type="text/css">
    .btnSubmit{
        width:150px;text-transform: uppercase;padding:10px;margin-top:20px;
    }
    #boost-edit-form .form-group.has-error .form-control {
        width:97%!important;
    }
    .hide{
        display :block;
    }
     .form-horizontal .form-group{ margin-left: 0; margin-right: 0;}
</style>
<?php
if ($error != '') {
    ?>  
    <div class="col-xs-12 text-danger text-center"><?= $error ?></div> 
    <?php
} else {
    ?>
    <div class="row">
        <div class="col-xs-8" style="float: none; margin: auto">
    <?php echo CHtml::errorSummary($model); ?>
    <?php
    $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
        'id' => 'package-edit-form', 'enableClientValidation' => TRUE,
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
    <div class="col-xs-12 col-md-12">
        <div class="panel panel-default panel-border">
            <div class="panel-body">
                <div class="row mb15 mt10">
                    <div class="col-xs-12 col-sm-12">
                        <div class="col-xs-12 col-sm-6">
                            <?php
                            $oldSendDate = $model->vpk_sent_date;
                            $model->packagesSentDate = $oldSendDate;
                            $sendDate = $model->vpk_sent_date != '' ? $model->packagesSentDate : date('Y-m-d H:i:s');
                            ?>
                            <?= $form->datePickerGroup($model, 'packagesSentDate', array('label' => 'Packages Sent Date',
                                'widgetOptions' => array('options' => array('autoclose' => true, 'startDate' => date('d/m/Y'), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('required' => true, 'value' => date('d/m/Y', strtotime($sendDate)))), 'prepend' => '<i class="fa fa-calendar"></i>'));
                            ?>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <?php
                            if ($model->vpk_sent_date != '') {
                                $oldSendDate = $model->vpk_sent_date;
                                $model->packagesSentTime = $oldSendDate;
                                $ptime = date('h:i A', strtotime($model->packagesSentTime));
                            } else {
                                $ptime = date('h:i A', strtotime(now));
                            }
                            #$fromTimeArr = Filter::getTimeDropArr($ptime);
                            ?>
                            <?=
                            $form->timePickerGroup($model, 'packagesSentTime', array('label' => 'Packages Sent Time',
                                'widgetOptions' => array('options' => array('autoclose' => true), 'htmlOptions' => array('required' => true, 'value' => $ptime))));
                            ?>
                        </div>
                    </div>
                </div>

                <div class="row mb15"> 
                    <div class="col-xs-12 col-sm-12">
                        <div class="col-xs-12 col-md-6">
                        <label>Type of Packages</label>
                        <?php
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'vpk_type',
								'val'			 => $model->vpk_type,
								'asDropDownList' => FALSE,
								'options'		 => array(
									'data'		 => new CJavaScriptExpression(VendorPackages::model()->getJSON(VendorPackages::model()->getType())),
									'allowClear' => true
								),
								'htmlOptions'	 => array('class' => 'p0', 'style' => 'width: 100%', 'placeholder' => 'Select Packages Type', 'readonly' =>'readonly')
							));
							?>
                    </div>  
                        <div class="col-xs-12 col-sm-6">
                            <label>No. of Packages Sent</label>
                            <?= $form->numberFieldGroup($model, 'vpk_sent_count', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => 'No. of Packages Sent']))) ?>
                        </div>
                        
                    </div>
                </div>

                <div class="row mb15"> 
                     <div class="col-xs-12 col-md-12">
                            <div class="col-xs-12 col-md-6">
                            <?php
                            if ($model->vpk_received_date != '') {
                                $receiveDate = date('d/m/Y', strtotime($model->vpk_received_date));
                                $model->packagesReceivedDate = $receiveDate;
                            }
                            echo $form->datePickerGroup($model, 'packagesReceivedDate', array('label' => 'Packages Received Date',
                                'widgetOptions' => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => ['placeholder' => 'Packages Received Date']), 'prepend' => '<i class="fa fa-calendar"></i>'
                            ));
                            ?>
                            </div>
                            <div class="col-xs-12 col-md-6">
                                <?php
                                if ($model->vpk_received_date != '') {
                                    $oldReceiveDate = $model->vpk_received_date;
                                    $model->packagesReceivedTime = $oldReceiveDate;
                                    $rtime = date('h:i A', strtotime($model->packagesReceivedTime));
                                } else {
                                    $rtime = '00:00:00';
                                }
                                #$toTimeArr = Filter::getTimeDropArr($ptime);
                                ?>
                                <?=
                                $form->timePickerGroup($model, 'packagesReceivedTime', array('label' => 'Packages Received Time',
                                    'widgetOptions' => array('options' => array('autoclose' => true), 'htmlOptions' => array('required' => true, 'value' => $rtime))));
                                ?>
                            </div>
                        </div>
                     </div>

                <div class="row mb15"> 
                     <div class="col-xs-12 col-md-12">
                    <div class="col-xs-12 col-sm-6">
                                <label>Tracking Number</label>
                                <?= $form->textFieldGroup($model, 'vpk_tracking_number', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Tracking Number')))) ?>
                        </div>
                    <div class="col-xs-12 col-md-6">
                        <label>Delivered by courier</label>
                        <?php
                        $deliveredCourierArr = VendorPackages::$deliveredCourierArr;
                        if ($model->vpk_delivered_by_courier == '' || $model->vpk_delivered_by_courier == NULL)
                        {
                             $model->vpk_delivered_by_courier = 0;
                        }
                        $courierVal = $model->vpk_delivered_by_courier;
                        $this->widget('booster.widgets.TbSelect2', array(
                            'model' => $model,
                            'attribute' => 'vpk_delivered_by_courier',
                            'val'	      => $courierVal,
                            'data' => $deliveredCourierArr,
                            'htmlOptions' => array('style' => 'width:100%', 'multiple' => '','placeholder' => 'Select Delivered by courier')
                        ));
                        ?>
                    </div>  
                     </div>
                </div>  
            </div>
            <div class="col-xs-12 text-center panel-footer">
                <input type="submit" value="Submit" name="yt0" id="packagesSubmit" class="btn btn-primary pl30 pr30 btnSubmit">
            </div>
        </div>
    </div>
    <?php $this->endWidget(); ?>
        </div>
    </div>
    <?php
}?>