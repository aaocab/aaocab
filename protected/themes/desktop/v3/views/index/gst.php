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
<div class="container-fluid bg-gray mt15 n">
<div class="container">
<div class="row justify-center">
                <div class="col-12">
                    <p class="mb20 mt20 text-center heading-inner weight600 merriw">GST Information Form</p>
                    <h4 style="color: #de6a1e;"><span id="gstOuterDivText"><?= $msg ?></span></h4>
                </div>
        </div>
	<div class="row justify-center">
		<div class="col-12 col-xl-10">
			<div class="card">
				<div class="row">
<div class="col-12 col-xl-6">
<div class="card mb0">
<div class="card-body">
					<div class="row">
						<div class="col-12">
							<div class="row">
								<div class="col-12 mb15">
									<label for="name" class="font-13">Name</label>
									<?= CHtml::textField("name", '', ['id' => 'name', 'placeholder' => "Name", 'class' => "form-control", 'required' => 'required', 'value' => '']) ?>
								</div>
							</div>
							<div class="row">
								<div class="col-12 mb15">
									<label for="address" class="font-13">Address</label>
									<?= CHtml::textArea("address", '', ['id' => 'address', 'placeholder' => "Address", 'class' => "form-control", 'required' => 'required', 'value' => '']) ?>
								</div>
							</div>
							<div class="row">
								<div class="col-12 mb15">
									<label for="state" class="font-13">State</label>
									<?= CHtml::textField("state", '', ['id' => 'state', 'placeholder' => "State", 'class' => "form-control", 'required' => 'required', 'value' => '']) ?>
								</div>
							</div>
							<div class="row">
								<div class="col-12 col-xl-6 mb15">
									<label for="gstNumber" class="font-13">GST Number/ Provisional Id</label>
									<?= CHtml::textField("gstNumber", '', ['id' => 'gstNumber', 'placeholder' => "GST Number/ Provisional Id", 'class' => "form-control", 'required' => 'required', 'value' => '']) ?>
								</div>
								<div class="col-12 col-xl-6 mb15">
									<label for="sDesc" class="font-13">Service Description</label>
									<?= CHtml::textField("sDesc", '', ['id' => 'sDesc', 'placeholder' => "Service Description", 'class' => "form-control", 'required' => 'required', 'value' => '']) ?>
								</div>
							</div>
							<div class="row">
								<div class="col-12 col-xl-6 mb15">
									<label for="sAccount" class="font-13">Service Accounting Code</label>
									<?= CHtml::textField("sAccount", '', ['id' => 'sAccount', 'placeholder' => "Service Accounting Code", 'class' => "form-control", 'required' => 'required', 'value' => '']) ?>
								</div>
								<div class="col-12 col-xl-6 mb15">
									<label for="pan" class="font-13">PAN</label>
									<?= CHtml::textField("pan", '', ['id' => 'pan', 'placeholder' => "PAN", 'class' => "form-control", 'required' => 'required', 'value' => '']) ?>
								</div>
							</div>
							<div class="row">
								<div class="col-12 mb15">
									<label for="email" class="font-13">Email ID</label>
									<?= CHtml::textField("email", '', ['id' => 'email', 'placeholder' => "Email ID", 'class' => "form-control", 'required' => 'required', 'value' => '']) ?>
								</div>
							</div>
							<div class="row">
								<div class="col-12 mb15">
									<label for="arn" class="font-13">Application Reference Number (ARN)</label>
									<?= CHtml::textField("arn", '', ['id' => 'arn', 'placeholder' => "Application Reference Number (ARN)", 'class' => "form-control", 'required' => 'required', 'value' => '']) ?>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-12 mb15">
							<div class="Submit-button" style="text-align: center">
								<?= CHtml::submitButton('Submit', ['style' => 'font-size:1.3em', 'class' => "btn btn-primary"]) ?>
							</div>
						</div>
					</div>
					<?php $this->endWidget(); ?>
				</div>
</div>
</div>
<div class="col-12 col-xl-6 d-flex align-items-center"><img src="/images/gst.jpg" alt="" class="img-fluid"></div>
</div>
			</div>
		</div>
	</div>
</div>
</div>