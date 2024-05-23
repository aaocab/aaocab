<style>
	ul{ list-style-type: none; padding: 0;}
	.feature_box{ border: #cccccc 1px solid;}
</style>
<div class="row">
	<div class="col-xs-12">
		<div class='panel' style="box-shadow: 0 0 0 0;"><div class='panel panel-body' style="box-shadow: 0 0 0 0;">
				<div class='row'>
					<?php
					if ($userModel->user_id != '')
					{
						$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
							'id'					 => 'flexxiRupee1Form', 'enableClientValidation' => true,
							'clientOptions'			 => array(
								'validateOnSubmit'	 => true,
								'errorCssClass'		 => 'has-error',
								'afterValidate'		 => 'js:function(form,data,hasError)
								{
									var formData = new FormData(form[0]);
									if(!hasError){
										$.ajax({
										"type":"POST",
										"dataType":"text",
										"url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('index/flexxiQuote', [])) . '",
										"data":formData,
										async: false,
										cache: false,
										contentType: false,
										processData: false,
										"beforeSend": function() 
										{
											ajaxindicatorstart("");
										},
										"complete": function() 
										{                     
											ajaxindicatorstop();
										},
										"success":function(data1)
										{ 
											var data=jQuery.parseJSON(data1);
											if(data.success)
											{
												window.location.href = data.url;
											}
											else
											{
												var data = jQuery.parseJSON(data1);
												var errors = data.errors;
												$("#showErrors").show();
												$("#showErrors").text(errors);
												settings=form.data(\'settings\');
												$.each (settings.attributes, function (i) 
												{
													try
													{
														$.fn.yiiactiveform.updateInput (settings.attributes[i], errors, form);
													}
													catch(e)
													{

													}
												});
												$.fn.yiiactiveform.updateSummary(form, errors);
											}},
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
								'class'		 => '', 'enctype'	 => 'multipart/form-data'
							),
						));
						/* @var $form TbActiveForm */
						?>
						<div role="alert" class="alert alert-danger" id="showErrors" style="display:none;"></div>
						<div class="col-xs-12 col-sm-7 col-md-6">
							<h4 class="mt0"><i class="fa fa-map-signs"></i>TRAVELLER'S INFORMATION</h4>
							<div class="col-xs-12 search-cabs-box mb30">
								<div class="row p10">
									<div class="col-xs-12 col-sm-6 col-md-6">
										<label for="name"><b>First name :</b></label>
										<?= $form->textFieldGroup($userModel, 'usr_name', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter First Name')))) ?>
									</div>
									<div class="col-xs-12 col-sm-6 col-md-6">
										<label for="name"><b>Last name :</b></label>
										<?= $form->textFieldGroup($userModel, 'usr_lname', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Last Name')))) ?>
									</div>
									<div class="col-xs-12">
										<label for="phone"><b>Phone number :</b></label>
										<div class="row">
											<div class="col-xs-3">
												<?= CHtml::textField("countryCode", '91', ['id' => 'countryCode', 'placeholder' => "Country Code", 'class' => "form-control", 'required' => 'required', 'value' => '91', 'readOnly' => true]) ?>
											</div>
											<div class="col-xs-9 pl0">
												<?= $form->textFieldGroup($userModel, 'usr_mobile', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Phone Number', 'required' => 'required' )))) ?>
											</div>
										</div>
									</div>
									<div class="col-xs-12">
										<label for="email"><b>Email address :</b></label>
										<?= $form->textFieldGroup($userModel, 'usr_email', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Email Id', 'required' => 'required')))) ?>
										<span id="errId" style="color: #F25656"></span>
										<input type="hidden" name="flsId" id="flsId" value="<?= $sendParams['flsId']; ?>">
									</div>
									<div class="col-xs-12">
									<?php echo CHtml::submitButton('Continue', array('class' => 'btn btn-primary')); ?>
									</div>
								</div>
							</div>

						</div>	

						<div class="col-xs-12 col-sm-5 col-md-4">
							<h4 class="mt0"><i class="fa fa-map-signs"></i>TRIP SUMMARY</h4>
							<div class="col-xs-12 search-cabs-box mb30">
								<div class="row p10">
									<p><label for="name"><span style="font-weight: normal;">Cab Type:</span> Shared Sedan</label><br>
									<label for="name"><span style="font-weight: normal;">Trip Type :</span> One Way</label><br>
									<?php $pickupDate	 = date("d F-Y", strtotime($sendParams['fls_pickup_date']));
									$pickupTime	 = date("h:i A", strtotime($sendParams['fls_pickup_date']));
									?>		
									<label for="name"><span style="font-weight: normal;">Pickup Date :</span> <?= $pickupDate ?></label><br>

									<label for="name"><span style="font-weight: normal;">Pickup Time :</span> <?= $pickupTime ?></label><br>

									<label for="name"><span style="font-weight: normal;">Pickup Address :</span> <?= $sendParams['fls_pickup_address'] ?></label><br>

									<label for="name"><span style="font-weight: normal;">Drop Address :</span> <?= $sendParams['fls_drop_address'] ?></label><br>

									<label for="name"><span style="font-weight: normal;">Distance :</span>	<?= $sendParams['rut_estm_distance'] ?> Kms.</label></p>

								</div>
							</div>

						</div>	

						<?php $this->endWidget(); ?>	
					<?php 
						}
					?>
				</div>
			</div>
		</div>
	</div>
</div>
