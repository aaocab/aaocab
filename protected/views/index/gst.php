<?php
    $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
        'id' => 'gstForm', 'enableClientValidation' => true,
        'clientOptions' => array(
        'validateOnSubmit' => true,
        'errorCssClass' => 'has-error',
        ),
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // See class documentation of CActiveForm for details on this,
        // you need to use the performAjaxValidation()-method described there.
        'enableAjaxValidation' => false,
        'errorMessageCssClass' => 'help-block',
        'htmlOptions' => array(
            'class' => 'form-horizontal'
        ),
    ));
    /* @var $form TbActiveForm */
?>
<div class="panel panel-default">
    <div class="panel-body pt0 new-booking-list">
        <div class="row register_path">
            <div>
                <div class="col-xs-12">
                    <h1 class="mb10 pb5 border-bottom weight400 text-uppercase">GST Information Form</h1>
                    <h4 style="color: #de6a1e;"><span id="gstOuterDivText"><?= $msg ?></span></h4>
                </div>
            </div>  
            <div>
                <div class="col-xs-12 col-sm-9">
                    <div>
                        <div class="row">
                            <div class="col-xs-12 col-md-8 col-lg-6 ml30">
                                <label for="name"><b>Name</b></label>
                                <?= CHtml::textField("name", '', ['id' => 'name', 'placeholder' => "Name", 'class' => "form-control", 'required' => 'required', 'value' => '']) ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-md-8 col-lg-6 ml30">
                                <label for="address"><b>Address</b></label>
                                <?= CHtml::textArea("address", '', ['id' => 'address', 'placeholder' => "Address", 'class' => "form-control", 'required' => 'required', 'value' => '']) ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-md-8 col-lg-6 ml30">
                                <label for="state"><b>State</b></label>
                                <?= CHtml::textField("state", '', ['id' => 'state', 'placeholder' => "State", 'class' => "form-control", 'required' => 'required', 'value' => '']) ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-md-8 col-lg-6 ml30">
                                <label for="gstNumber"><b>GST Number/ Provisional Id</b></label>
                                <?= CHtml::textField("gstNumber", '', ['id' => 'gstNumber', 'placeholder' => "GST Number/ Provisional Id", 'class' => "form-control", 'required' => 'required', 'value' => '']) ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-md-8 col-lg-6 ml30">
                                <label for="sDesc"><b>Service Description</b></label>
                                <?= CHtml::textField("sDesc", '', ['id' => 'sDesc', 'placeholder' => "Service Description", 'class' => "form-control", 'required' => 'required', 'value' => '']) ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-md-8 col-lg-6 ml30">
                                <label for="sAccount"><b>Service Accounting Code</b></label>
                                <?= CHtml::textField("sAccount", '', ['id' => 'sAccount', 'placeholder' => "Service Accounting Code", 'class' => "form-control", 'required' => 'required', 'value' => '']) ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-md-8 col-lg-6 ml30">
                                <label for="pan"><b>PAN</b></label>
                                <?= CHtml::textField("pan", '', ['id' => 'pan', 'placeholder' => "PAN", 'class' => "form-control", 'required' => 'required', 'value' => '']) ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-md-8 col-lg-6 ml30">
                                <label for="email"><b>Email ID</b></label>
                                <?= CHtml::textField("email", '', ['id' => 'email', 'placeholder' => "Email ID", 'class' => "form-control", 'required' => 'required', 'value' => '']) ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-md-8 col-lg-6 ml30">
                                <label for="arn"><b>Application Reference Number (ARN)</b></label>
                                <?= CHtml::textField("arn", '', ['id' => 'arn', 'placeholder' => "Application Reference Number (ARN)", 'class' => "form-control", 'required' => 'required', 'value' => '']) ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6 m10">
                            <div class="Submit-button" style="text-align: center">
                                <?= CHtml::submitButton('Submit', ['style' => 'font-size:1.3em', 'class' => "btn btn-primary"]) ?>
                            </div>
                        </div>
                    </div>
                    <?php $this->endWidget(); ?>
                </div>
            </div> 
        </div>
    </div>
</div>