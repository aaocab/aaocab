<?php
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/jquery.mask.min.js');
Yii::app()->clientScript->registerCssFile(ASSETS_URL . '/plugins/form-select2/select2.css');
?>
<style>
    @media (min-width: 992px){
        .modal-lg {
            width: 95%!important;
        }
    }
    @media (min-width: 768px){
        .modal-lg {
            width: 100%;
        }
    }
    .selectize-input {
        min-width: 0px !important;
        width: 30% !important;
    }
</style>
<div class="row">
    <div class="col-lg-10 col-md-12 col-sm-12" style="float: none; margin: auto">
        <div style="text-align:center;" class="col-xs-12">
        </div>
        <div class="row">
            <div class="upsignwidt11">
                <div class="col-xs-12">
					<?php
					$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
						'id'					 => 'feedback-form',
						'enableClientValidation' => true,
						'clientOptions'			 => array(
							'validateOnSubmit'	 => true,
							'errorCssClass'		 => 'has-error',
							'afterValidate'		 => 'js:function(form,data,hasError){
                        if(!hasError){
                            $.ajax({
                            "type":"POST",
                            "url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('admin/booking/feedbackform', ['bookingID' => $model->bkg_id])) . '",
                            "data":form.serialize(),
							"dataType": "json",
                                                        "success":function(data1){
									if(data1.success)
									{
										feedbackSent(data1.oldStatus);
									}
									else{
									var errors = data1.errors;
									settings=form.data(\'settings\');
									$.each (settings.attributes, function (i) {
									$.fn.yiiactiveform.updateInput (settings.attributes[i], errors, form);
									});
									$.fn.yiiactiveform.updateSummary(form, errors);
								}
								},
                            });
                            }
                        }'
						),
						// Please note: When you enable ajax validation, make sure the corresponding
						// controller action is handling ajax validation correctly.
						// See class documentation of CActiveForm for details on this,
						// you need to use the performAjaxValidation()-method described there.
						'enableAjaxValidation'	 => false,
						'errorMessageCssClass'	 => 'help-block',
						'htmlOptions'			 => array(
							'class' => 'form-horizontal'
						),
					));
					$response	 = Contact::referenceUserData($model->bkgUserInfo->bui_id, 3);
					if ($response->getStatus())
					{
						$contactNo	 = $response->getData()->phone['number'];
						$countryCode = $response->getData()->phone['ext'];
						$firstName	 = $response->getData()->email['firstName'];
						$lastName	 = $response->getData()->email['lastName'];
						$email		 = $response->getData()->email['email'];
					}
					?>
                    <div class="row">
                        <div class="col-xs-12 col-sm-6">
							<?= $form->hiddenField($model, 'bkg_id') ?>
                            <div class="col-sm-6">
                                <div class="form-group"><label class="control-label" for="phone"><b>Booking Id</b></label> : <?= $model->bkg_booking_id ?></div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group"><label class="control-label" for="name"><b>Customer Name</b></label> : <?= $firstName; ?>&nbsp;<?= $lastName; ?></div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group"><label class="control-label" for="email"><b>Customer Email</b></label> : <?= $email; ?></div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group"><label class="control-label" for="phone"><b>Customer Phone</b></label> : +<?= $countryCode ?><?= $contactNo; ?></div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <div class="col-sm-6">
                                <div class="form-group"><label class="control-label" for="drivername"><b>Driver Name</b></label> : <?= $cabmodel->bcb_driver_name ?></div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group"><label class="control-label" for="driverphone"><b>Driver Phone</b></label> : <?= $cabmodel->bcb_driver_phone ?></div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group"><label class="control-label" for="vendorname"><b>Vendor Name</b></label> : <?= $cabmodel->bcbVendor->vnd_name ?></div>
                            </div>
                            <div class="col-sm-6">
								<?php
								$cttId		 = $cabmodel->bcbVendor;
								$phone		 = ContactPhone::model()->getContactPhoneById($cttId->vnd_contact_id);
								?>
                                <div class="form-group"><label class="control-label" for="vendorphone"><b>Vendor Phone</b></label> : +<?= $cttId->vndContact->contactPhones->phn_phone_country_code; ?><?= $phone; ?></div>
                            </div>
                        </div>
					</div>
					<div class="row">
						<div class="col-xs-12">
							<div class="col-md-3">
								<div class="form-group"><label><b>Send message to: </b></label></div>
							</div>
							<div class="col-md-3">
								<div class="form-group"><label><input type="checkbox" name="chk_customer"/> <b>Customer</b></label></div>
							</div>
							<div class="col-md-3">
								<div class="form-group"><label><input type="checkbox" name="chk_vendor"/> <b>Vendor</b></label></div>
							</div>
							<div class="col-md-3">
								<div class="form-group"><label><input type="checkbox" name="chk_driver"/> <b>Driver</b></label></div>
							</div>
						</div>
					</div>                      
					<div class="row">
						<div class="col-xs-12">
							<div class="col-md-3">
								<div class="form-group"><label><b>Send message via: </b></label></div>
							</div>
							<div class="col-md-3">
								<div class="form-group"><label><input type="checkbox" name="chk_sms" checked/> <b>SMS</b></label></div>
							</div>
							<div class="col-md-3">
								<div class="form-group"><label><input type="checkbox" name="chk_email" checked/> <b>E-Mail</b></label></div>
							</div>
							<div class="col-md-3">
								<div class="form-group"><label><input type="checkbox" name="chk_app" checked/> <b>Push Notification on App</b></label></div>
							</div>
						</div>
					</div>                      
                    <div class="row">
                        <div class="col-xs-12 col-sm-8" style="margin: auto; float: none;">
                            <div class="form-group">
								<?= $form->textAreaGroup($model, 'bkg_message', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('style' => 'height : 100px;', 'placeholder' => 'Your message.')))) ?>
                            </div>
                        </div>
                    </div>
                    <div class="" style="text-align: center"> <?php echo CHtml::submitButton('Send Message', array('class' => 'btn btn-primary')); ?> </div>
                </div>
            </div>
        </div>
    </div>
</div>
<? $ratingModel = Ratings::model()->getRatingbyBookingId($model->bkg_id); ?>
<?
if ($model->bkg_status > 4)
{
	?>
	<div class="row">
		<div class="col-xs-12">
			<?
			if ($ratingModel->rtg_customer_overall)
			{
				?> 
				<label class="mt10 control-label">Customer Rating</label>
				<div class="col-xs-12 rounded pb10">
					<div class="row">
						<?
						if ($ratingModel->rtg_customer_recommend)
						{
							?> <div class='col-xs-12 mt10'>
								<?= $ratingModel->getAttributeLabel('rtg_customer_recommend') ?><br>
								<?
								$this->widget('CStarRating', array(
									'model'		 => $ratingModel,
									'attribute'	 => 'rtg_customer_recommend',
									'minRating'	 => 1,
									'maxRating'	 => 10,
									'starCount'	 => 10,
									'value'		 => $ratingModel->rtg_customer_recommend,
									'readOnly'	 => true,
								));
								?>
							</div><?
						}
						if ($ratingModel->rtg_customer_overall)
						{
							?> <div class='col-xs-6 mt10'>

								<?= $ratingModel->getAttributeLabel('rtg_customer_overall') ?><br>
								<?
								$this->widget('CStarRating', array(
									'model'		 => $ratingModel,
									'attribute'	 => 'rtg_customer_overall',
									'minRating'	 => 1,
									'maxRating'	 => 5,
									'starCount'	 => 5,
									'value'		 => $ratingModel->rtg_customer_overall,
									'readOnly'	 => true,
								));
								?></div><?
						}
						if ($ratingModel->rtg_customer_driver)
						{
							?> <div class='col-xs-6 mt10'>
								<?= $ratingModel->getAttributeLabel('rtg_customer_driver') ?><br>
								<?
								$this->widget('CStarRating', array(
									'model'		 => $ratingModel,
									'attribute'	 => 'rtg_customer_driver',
									'minRating'	 => 1,
									'maxRating'	 => 5,
									'starCount'	 => 5,
									'value'		 => $ratingModel->rtg_customer_driver,
									'readOnly'	 => true,
								));
								?></div><?
						}
						if ($ratingModel->rtg_customer_csr)
						{
							?> <div class='col-xs-6 mt10'>
								<?= $ratingModel->getAttributeLabel('rtg_customer_csr') ?><br>
								<?
								$this->widget('CStarRating', array(
									'model'		 => $ratingModel,
									'attribute'	 => 'rtg_customer_csr',
									'minRating'	 => 1,
									'maxRating'	 => 5,
									'starCount'	 => 5,
									'value'		 => $ratingModel->rtg_customer_csr,
									'readOnly'	 => true,
								));
								?></div><?
						}
						if ($ratingModel->rtg_customer_car)
						{
							?> <div class='col-xs-6 mt10'>
								<?= $ratingModel->getAttributeLabel('rtg_customer_car') ?><br>
								<?
								$this->widget('CStarRating', array(
									'model'		 => $ratingModel,
									'attribute'	 => 'rtg_customer_car',
									'minRating'	 => 1,
									'maxRating'	 => 5,
									'starCount'	 => 5,
									'value'		 => $ratingModel->rtg_customer_car,
									'readOnly'	 => true,
								));
								?></div><?
						}
						?></div>
						<?
						if ($ratingModel->rtg_customer_review)
						{
							?> <div class='mt20'>
							<?= $ratingModel->getAttributeLabel('rtg_customer_review') ?> </div>
						<div class="col-xs-12 p15 rounded mt5 mb10">
							<?= $ratingModel->rtg_customer_review;
							?>
						</div>
						<?
					}
					?>

					<?
				}
				?>
			</div></div>
	</div>
	<div class="row">
		<?
		if ($ratingModel->rtg_csr_customer)
		{
			?>
			<div class="col-xs-12">
				<label class="mt10 control-label">CSR Rating</label>
				<div class="col-xs-12 rounded pb10 pt10">
					<div class="row">
						<?
						if ($ratingModel->rtg_csr_customer)
						{
							?> <div class='col-xs-6'>

								<?= $ratingModel->getAttributeLabel('rtg_csr_customer') ?><br>
								<?
								$this->widget('CStarRating', array(
									'model'		 => $ratingModel,
									'attribute'	 => 'rtg_csr_customer',
									'minRating'	 => 1,
									'maxRating'	 => 5,
									'starCount'	 => 5,
									'value'		 => $ratingModel->rtg_csr_customer,
									'readOnly'	 => true,
								));
								?></div><?
						}
						if ($ratingModel->rtg_csr_vendor)
						{
							?> <div class='col-xs-6'>
								<?= $ratingModel->getAttributeLabel('rtg_csr_vendor') ?><br>
								<?
								$this->widget('CStarRating', array(
									'model'		 => $ratingModel,
									'attribute'	 => 'rtg_csr_vendor',
									'minRating'	 => 1,
									'maxRating'	 => 5,
									'starCount'	 => 5,
									'value'		 => $ratingModel->rtg_csr_vendor,
									'readOnly'	 => true,
								));
								?></div><?
						}
						?></div><?
						if ($ratingModel->rtg_csr_review)
						{
							?> <div class='mt20'>
							<?= $ratingModel->getAttributeLabel('rtg_csr_review') ?> </div>
						<div class="col-xs-12 p15 rounded mt10 mb10">
							<?= $ratingModel->rtg_csr_review;
							?>
						</div>
						<?
					}
					?>
				</div>
			</div>
			<?
		}
		?>
	</div>
<? } ?>
<div class="row booking-log">
    <div class="col-xs-12 text-center">
        <label class = "control-label h3">Booking Log</label>
		<?
		Yii::app()->runController('admin/booking/showlog/booking_id/' . $model->bkg_id);
		?>
    </div>
</div>
<?php $this->endWidget(); ?>
<?php echo CHtml::endForm(); ?>
<?php
$version = Yii::app()->params['customJsVersion'];
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/custom.js?v=' . $version, CClientScript::POS_HEAD);
?>
