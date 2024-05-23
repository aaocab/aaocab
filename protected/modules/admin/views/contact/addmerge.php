<style>
    .select2-container-multi .select2-choices {
        min-height: 50px;
    }
    .new-booking-list .form-horizontal .form-group{ margin-left: 0; margin-right: 0;}
	.modal {
		overflow-y:auto;
	}

</style>
<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/js/jquery.mask.min.js');
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');
$stateList			 = array("" => "Select state") + CHtml::listData(States::model()->findAll('stt_active = :act AND stt_country_id = :con order by stt_name', array(':act' => '1', ':con' => '99')), 'stt_id', 'stt_name');
$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
?>
<div class="row">
	<div class="col-lg-8 col-md-6 col-sm-8 pb10 new-booking-list" <?php if (count($modelMerge) > 0)
{ ?> style="float: left; padding-left:200px;" <?php }
else
{ ?> style="float: none; margin: auto"<?php } ?>>
        <div class="row">
			<?php
			$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
				'id'					 => 'add_contact_form',
				'enableClientValidation' => true,
				'clientOptions'			 => array(
					'validateOnSubmit'	 => true,
					'errorCssClass'		 => 'has-error'
				),
				'enableAjaxValidation'	 => false,
				'errorMessageCssClass'	 => 'help-block',
				'htmlOptions'			 => array(
					'class'		 => 'form-horizontal',
					'enctype'	 => 'multipart/form-data',
				),
			));
			?>			
			<div class="panel panel-default panel-border">
				<div class="panel-body">
					<h3 class="pb10 mt0">Personal Information</h3>
					<div id="msg">
						<?= $form->hiddenField($model, 'new_ctt_id', array()); ?>		
						<?php
						if ($returns->hasErrors())
						{
							?>
							<div class="alert alert-block alert-danger">
								<p>Please fix the following input errors:</p>
								<ul>										
									<li>
										<?php
										if (count($returns->getError('ctt_id')))
										{
											echo "<li>" . $returns->getError('ctt_id')[0] . "</li>";
										}
										if (count($returns->getError('contactEmails')))
										{
											$json = json_decode($returns->getError('contactEmails')[0], true);
											echo "<li>" . $json['ContactEmail_eml_email_address'][0] . "</li>";
										}
										if (count($returns->getError('contactPhones')))
										{
											$json = json_decode($returns->getError('contactPhones')[0], true);
											echo "<li>" . $json['ContactPhone_phn_phone_no'][0] . "</li>";
										}
										if (count($returns->getError('ctt_city')))
										{
											echo "<li>" . $returns->getError('ctt_city')[0] . "</li>";
										}
										?>
									</li>
								</ul>
							</div>
<?php } ?>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-6 col-md-6">
							<label>User Type</label>
<?php echo $form->hiddenField($model, 'userInput'); ?>
<?php echo $form->hiddenField($model, 'accountInput'); ?>
<?= $form->radioButtonListGroup($model, 'ctt_user_type', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Individual', 2 => 'Business')), 'inline' => true)) ?>
						</div>
					</div>			
					<div class="row" id="individual" <?php echo $model->ctt_user_type == 2 ? 'style="display:none"' : "" ?>>
						<div class="col-xs-12 col-sm-6 col-md-6">
							<?= $form->textFieldGroup($model, 'ctt_first_name', array()) ?>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6">
<?= $form->textFieldGroup($model, 'ctt_last_name', array()) ?>
						</div>
					</div>
					<div class="row" <?php echo $model->ctt_user_type == "1" ? 'style="display:none"' : '' ?> id="business_type">
						<div class="col-xs-12 col-sm-6 col-md-6">
							<label>Business Type</label>
							<?php
							$businessTypesArr = Contact::model()->getJSON([1 => 'Sole Propitership', 2 => 'Partner', 3 => 'Private Limited', 4 => 'Limited']);
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'ctt_business_type',
								'val'			 => $model->ctt_business_type,
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($businessTypesArr)),
								'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Business Type')
							));
							?>
							<b><?= $modelMerger->ctt_business_type ?></b>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6">
<?= $form->textFieldGroup($model, 'ctt_business_name', array()) ?>
							<b><?= $modelMerger->ctt_business_name ?></b>
						</div>
					</div>
					<div class="row ">
						<div class="col-xs-12 col-sm-6 col-md-6">
							<?php
							$style		 = "display: none";
							$label		 = "Email*";
							$id			 = "ContactEmail_eml_email_address";
							$ceModels	 = $model->contactEmails;
							if ($ceModels == [])
							{
								$ceModels[] = new ContactEmail();
							}
							for ($i = 0; $i < count($ceModels); $i++)
							{
								if ($i > 0)
								{
									$id		 = "ContactEmail_eml_email_address" . ($i - 1);
									$style	 = "";
									$label	 = "Secondary Email*";
								}
								if ($i > 0)
								{
									?>	
									<div class="clsRouteEmail"> 
									<?php } ?>
									<?php
									echo $form->textFieldGroup($ceModels[$i], '[]eml_email_address', array('label' => $label, 'widgetOptions' => array('htmlOptions' => array('value' => $uvrid != NULL ? $model->email_address : $ceModels[$i]->eml_email_address, 'placeholder' => "Email", 'id' => $id, "readonly" => true, 'onblur' => "checkEmail($i)"))));
									if ($i > 0)
									{
										?>	</div>  <?php
								}
							}
							?>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6">	
							<?php
							$style		 = "display: none";
							$label		 = "Phone*";
							$id			 = "ContactPhone_phn_phone_no";
							$cpModels	 = $model->contactPhones;
							if ($cpModels == [])
							{
								$cpModels[] = new ContactPhone();
							}
							for ($i = 0; $i < count($cpModels); $i++)
							{
								if ($i > 0)
								{
									$id		 = "ContactPhone_phn_phone_no" . ($i - 1);
									$style	 = "";
									$label	 = "Secondary Phone*";
								}
								if ($i > 0)
								{
									?>	
									<div class="clsRoutePhone">  
									<?php } ?>
									<?php
									echo $form->textFieldGroup($cpModels[$i], '[]phn_phone_no', array('label' => $label, 'widgetOptions' => array('htmlOptions' => array('value' => $uvrid != NULL ? $model->phone_no : $cpModels[$i]->phn_phone_no, 'placeholder' => "Phone", 'id' => $id, "readonly" => true, 'onblur' => "checkPhone($i)"))));
									if ($i > 0)
									{
										?>	</div>  <?php
								}
							}
							?>	
						</div>
					</div>
					<div class="row">
						<div class="col-xs-6">
							<div class="row">
								<div class="col-xs-12 col-md-12 ">
									<? //= $form->fileFieldGroup($model, 'ctt_profile_path', array('label' => 'Profile Image', 'widgetOptions' => array())) ?>
									<?= $form->fileFieldGroup($model, 'ctt_profile_path', array('label' => 'Profile Image', 'widgetOptions' => array())); ?>
<?php if ($model->ctt_profile_path != '')
{ ?>
										<div class="row ">
											<div class="col-xs-12 mb15">
												<?php if (substr_count($model->ctt_profile_path, "attachments") > 0)
												{ ?>
													<a href="<?= $model->ctt_profile_path ?>" target="_blank"><?= basename($model->ctt_profile_path); ?></a>
												<?php }
												else
												{
													?>
													<a href="<?= AttachmentProcessing::ImagePath($model->ctt_profile_path) ?>" target="_blank"><?= basename($model->ctt_profile_path); ?></a>
	<?php } ?>
											</div>
										</div>
<?php } ?>
								</div>
							</div>
						</div>
						<div class="col-xs-6">
							<div class="row">
								<div class="col-xs-12 col-md-12 ">
<?= $form->textAreaGroup($model, 'ctt_address', array()) ?>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-6 col-md-6">
							<label class="control-label">State</label>
							<?php
							$dataState	 = VehicleTypes::model()->getJSON($stateList);
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'ctt_state',
								'val'			 => $model->ctt_state,
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($dataState)),
								'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select State')
							));
							?>
						</div>
						<div class="col-xs-12 c0l-sm-8 col-lg-6 mt5">
							<div class="form-group cityinput">
								<label>City</label>
								<?php
								$this->widget('ext.yii-selectize.YiiSelectize', array(
									'model'				 => $model,
									'attribute'			 => 'ctt_city',
									'useWithBootstrap'	 => true,
									"placeholder"		 => "Select Source City",
									'fullWidth'			 => false,
									'htmlOptions'		 => array('width'	 => '100%',
										'id'	 => 'Contact_ctt_city'
									),
									'defaultOptions'	 => $selectizeOptions + array(
								'onInitialize'	 => "js:function(){
                                            populateSource(this, '{$model->ctt_city}');
                                                }",
								'load'			 => "js:function(query, callback){
                                            loadSource(query, callback);
                                            }",
								'render'		 => "js:{
                                            option: function(item, escape){
                                            return '<div><span class=\"\"><i class=\"fa fa-map-marker mr5\"></i>' + escape(item.text) +'</span></div>';
                                            },
                                            option_create: function(data, escape){
                                            return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
                                            }
                                            }",
									),
								));
								?>
							</div>
						</div>
					</div>							
				</div>
			</div>
			<div class="panel panel-default panel-border">
				<div class="panel-body">
					<h3 class="pb10 mt0">Document Info</h3>
					<div class="row">
						<div class="col-xs-12 col-sm-6 ">
							<?= $form->textFieldGroup($model, 'ctt_voter_no', array()) ?>
							<?= $form->hiddenField($model, 'ctt_voter_doc_id', array()); ?>
						</div>
						<div class="col-xs-12 col-sm-6 ">
<?= $form->textFieldGroup($model, 'ctt_aadhaar_no', array()) ?>
							<?= $form->hiddenField($model, 'ctt_aadhar_doc_id', array()); ?>
						</div>
					</div> 
					<div class="row">
						<div class="col-xs-12 col-sm-6 ">
							<?= $form->textFieldGroup($model, 'ctt_pan_no', array()) ?>
							<?= $form->hiddenField($model, 'ctt_pan_doc_id', array()); ?>
						</div>
						<div class="col-xs-12 col-sm-6 ">
<?= $form->textFieldGroup($model, 'ctt_license_no', array()) ?>
<?= $form->hiddenField($model, 'ctt_license_doc_id', array()); ?>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-6">

							<div class="form-group">
								<?=
								$form->datePickerGroup($model, 'locale_license_issue_date', array(
									'label'			 => 'License Issue date',
									'widgetOptions'	 => array(
										'options'		 => array('autoclose'	 => true,
											'endDate'	 => '+0d',
											'format'	 => 'dd/mm/yyyy'),
										'htmlOptions'	 => array('readonly' => true)), 'groupOptions'	 => ['class' => 'm0'], 'prepend'		 => '<i class="fa fa-calendar"></i>'));
								?>                            
							</div> 
						</div>
						<div class="col-xs-12 col-sm-6 ">

							<div class="form-group">
								<?=
								$form->datePickerGroup($model, 'locale_license_exp_date', array(
									'label'			 => 'License Expiry date',
									'widgetOptions'	 => array(
										'options'		 => array('autoclose'	 => true,
											'startDate'	 => $model->locale_license_issue_date == "" ? date("d/m/Y") : $model->locale_license_issue_date, 'format'	 => 'dd/mm/yyyy'), 'htmlOptions'	 => array('readonly' => true
										)), 'groupOptions'	 => ['class' => 'm0'], 'prepend'		 => '<i class="fa fa-calendar"></i>'));
								?>                            
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-6 ">
							<label class="control-label">License Issuing Authority</label>
							<?php
							$dataState	 = VehicleTypes::model()->getJSON($stateList);
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'ctt_dl_issue_authority',
								'val'			 => $model->ctt_dl_issue_authority,
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($dataState)),
								'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select License Issuing Authority')
							));
							?>
						</div>
					</div>
				</div>
			</div>
			<div class="panel panel-default panel-border">
				<div class="panel-body">
					<h3 class="pb10 mt0">Bank Details</h3>
					<div class="row">
						<div class="col-xs-12 col-sm-6 ">
<?= $form->textFieldGroup($model, 'ctt_bank_name', array()) ?>
						</div>
						<div class="col-xs-12 col-sm-6 ">
							<?= $form->textFieldGroup($model, 'ctt_bank_account_no', array()) ?>
						</div>
					</div> 
					<div class="row">
						<div class="col-xs-12 col-sm-6 ">
<?= $form->textFieldGroup($model, 'ctt_bank_branch', array()) ?>
						</div>
						<div class="col-xs-12 col-sm-6 ">
<?= $form->textFieldGroup($model, 'ctt_bank_ifsc', array()) ?>
						</div>
					</div> 
					<div class="row">
						<div class="col-xs-12 col-sm-6 ">
							<label>Account Type</label>
							<?= $form->radioButtonListGroup($model, 'ctt_account_type', array('label' => '', 'widgetOptions' => array('data' => array(0 => 'Savings', 1 => 'Current')), 'inline' => true)) ?>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-6 ">
							<?= $form->textFieldGroup($model, 'ctt_beneficiary_name', array()) ?>
						</div>
						<div class="col-xs-12 col-sm-6">
							<label>Beneficiary Id</label>
<?= $form->textFieldGroup($model, 'ctt_beneficiary_id', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => 'Beneficiary Id'])))
?>
						</div>
					</div> 
				</div>
			</div>
			<div class="" style="text-align: center">
			<?php
			echo CHtml::Button("Submit", array('class' => 'btn btn-primary'));
			?>
			</div>
<?php $this->endWidget(); ?>
        </div>
	</div>
				<?php if (count($modelMerge) > 0)
				{ ?>
		<div class="col-lg-4 col-md-4 col-sm-4 " style="float: right;  padding-left:50px;  padding-right:50px;">
			<div class="row"><h4>List Contact to be merged : </h4>
				<ul style="padding-left:10px">
							<?php for ($i = 0; $i < count($modelMerge); $i++)
							{ ?>

						<div class="col-xs-12 panel panel-default panel-border contact<?php echo $i + 1; ?>" style="color: #666">
							<li><?= ($i + 1) ?><b>. Contact Assigned </b></li>
							<li><b>Contact Id</b>: <?= $modelMerge[$i]['ctt_id'] ?> &nbsp;&nbsp; <a href="<?= Yii::app()->createUrl("admin/contact/view", array('ctt_id' => $modelMerge[$i]['ctt_id'], 'type' => 'view')) ?>" target="_blank">View Contact</a></li>
							<li><?php
						if ($modelMerge[$i]['contactperson'] != NULL)
						{
							echo $form->checkbox($model, 'contactperson', ['label' => "", 'groupOptions' => ["class" => "checkbox-inline"]]);
						}
								?><b>Name</b> :<?= $modelMerge[$i]['contactperson'] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </li>

							<li><?php
								if ($modelMerge[$i]['phn_phone_no'] != NULL)
								{
									echo $form->checkbox($model, 'phn_phone_no', ['label' => "", 'groupOptions' => ["class" => "checkbox-inline"]]);
								}
								?><b>Phone</b> : <?= $modelMerge[$i]['phn_phone_no'] ?> <span id = "docapprove" class="label label-success">Primary</span></li>
							<li><?php
								if ($modelMerge[$i]['eml_email_address'] != NULL)
								{
									echo $form->checkbox($model, 'eml_email_address', ['label' => "", 'groupOptions' => ["class" => "checkbox-inline"]]);
								}
								?><b>Email</b> : <?= $modelMerge[$i]['eml_email_address'] ?> <span id = "docapprove" class="label label-success">Primary</span></li>
							<li><?php
								if ($modelMerge[$i]['ctt_address'] != NULL)
								{
									echo $form->checkbox($model, 'check_ctt_address', ['label' => "", 'groupOptions' => ["class" => "checkbox-inline"]]);
								}
								?><b>Address</b> : <?= $modelMerge[$i]['ctt_address'] ?></li>
							<li><?php
								if ($modelMerge[$i]['ctt_city'] != NULL)
								{
									echo $form->checkbox($model, 'check_ctt_city', ['label' => "", 'groupOptions' => ["class" => "checkbox-inline"]]);
								}
								?><b>City</b> : <?php $cityDetails = Cities::model()->findByPk($modelMerge[$i]['ctt_city']);
						echo $cityDetails->cty_name; ?></li>
							<li><?php
								if ($modelMerge[$i]['ctt_state'] != NULL)
								{
									echo $form->checkbox($model, 'check_ctt_state', ['label' => "", 'groupOptions' => ["class" => "checkbox-inline"]]);
								}
								?><b>State</b> : <?php $stateDetails = States::model()->findByPk($modelMerge[$i]['ctt_state']);
						echo $stateDetails->stt_name; ?></li>
							<li><b>Status</b> : <span id="pan" class="label <?php echo $modelMerge[$i]['ctt_is_verified'] == 0 ? "label-danger" : "label-success" ?>"><?= trim($model->isVerified[$modelMerge[$i]['ctt_is_verified']]); ?></span></li>
							<li><?php
								if ($modelMerge[$i]['ctt_voter_doc_id'] != NULL && $modelMerge[$i]['ctt_voter_doc_id'] != '0' && $modelMerge[$i]['ctt_voter_no'] != '')
								{
									echo $form->checkbox($model, 'check_ctt_voter_no', ['label' => "", 'groupOptions' => ["class" => "checkbox-inline"]]);
								}
								?><b>Voter No</b> : <?= $modelMerge[$i]['ctt_voter_no'] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if ($modelMerge[$i]['doc_status2'] == 0)
								{ ?>	<span id = "docnotapprove" class="label label-info"> Not Approved</span><?php }
					elseif ($modelMerge[$i]['doc_status2'] == '1')
					{ ?>	<span id = "docapprove" class="label label-success"> Approved</span><?php }
					elseif ($modelMerge[$i]['doc_status2'] == 2)
					{ ?>	<span class="label label-danger"> Rejected</span>	<?php } ?></li>

							<li><?php
								if ($modelMerge[$i]['ctt_aadhar_doc_id'] != NULL && $modelMerge[$i]['ctt_aadhar_doc_id'] != '0' && $modelMerge[$i]['ctt_aadhaar_no'] != '')
								{
									echo $form->checkbox($model, 'check_ctt_aadhaar_no', ['label' => "", 'groupOptions' => ["class" => "checkbox-inline"]]);
								}
								?><b>Aadhaar No.</b> : <?= $modelMerge[$i]['ctt_aadhaar_no'] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if ($modelMerge[$i]['doc_status3'] == 0)
								{ ?>	<span id = "docnotapprove" class="label label-info"> Not Approved</span><?php }
					elseif ($modelMerge[$i]['doc_status3'] == '1')
					{ ?>	<span id = "docapprove" class="label label-success"> Approved</span><?php }
					elseif ($modelMerge[$i]['doc_status3'] == 2)
					{ ?>	<span class="label label-danger"> Rejected</span>	<?php } ?></li>
							<li><?php
								if ($modelMerge[$i]['ctt_pan_doc_id'] != NULL && $modelMerge[$i]['ctt_pan_doc_id'] != '0' && $modelMerge[$i]['ctt_pan_no'] != '')
								{
									echo $form->checkbox($model, 'check_ctt_pan_no', ['label' => "", 'groupOptions' => ["class" => "checkbox-inline"]]);
								}
								?><b>Pan No</b> : <?= $modelMerge[$i]['ctt_pan_no'] ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if ($modelMerge[$i]['doc_status4'] == 0)
						{ ?>	<span id = "docnotapprove" class="label label-info"> Not Approved</span><?php }
						elseif ($modelMerge[$i]['doc_status4'] == '1')
						{ ?>	<span id = "docapprove" class="label label-success"> Approved</span><?php }
					elseif ($modelMerge[$i]['doc_status4'] == 2)
					{ ?>	<span class="label label-danger"> Rejected</span>	<?php } ?></li>
							<li><?php
						if ($modelMerge[$i]['ctt_license_doc_id'] != NULL && $modelMerge[$i]['ctt_license_doc_id'] != '0' && $modelMerge[$i]['ctt_license_no'] != '')
						{
							echo $form->checkbox($model, 'check_ctt_license_no', ['label' => "", 'groupOptions' => ["class" => "checkbox-inline"]]);
						}
								?><b>License No.</b> : <?= $modelMerge[$i]['ctt_license_no'] ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php if ($modelMerge[$i]['doc_status5'] == 0)
						{ ?>	<span id = "docnotapprove" class="label label-info"> Not Approved</span><?php }
					elseif ($modelMerge[$i]['doc_status5'] == '1')
					{ ?>	<span id = "docapprove" class="label label-success"> Approved</span><?php }
					elseif ($modelMerge[$i]['doc_status5'] == 2)
					{ ?>	<span class="label label-danger"> Rejected</span>	<?php } ?></li>

							<li><b>License Issuing Authority</b> :<?php $stateDetails = States::model()->findByPk($modelMerge[$i]['ctt_dl_issue_authority']);
						echo $stateDetails->stt_name; ?></li>
							<li><?php
								if ($modelMerge[$i]['ctt_bank_name'] != NULL)
								{
									echo $form->checkbox($model, 'check_ctt_bank_name', ['label' => "", 'groupOptions' => ["class" => "checkbox-inline"]]);
								}
								?><b>Bank Name</b> :<?= $modelMerge[$i]['ctt_bank_name'] ?><span  class="label label-info"><?= $model->userType[$modelMerge[$i]['ctt_bank_name']] ?></span></li>
							<li><?php
						if ($modelMerge[$i]['ctt_bank_account_no'] != NULL)
						{
							echo $form->checkbox($model, 'check_ctt_bank_account_no', ['label' => "", 'groupOptions' => ["class" => "checkbox-inline"]]);
						}
						?><b>Account No.</b> :<?= $modelMerge[$i]['ctt_bank_account_no'] ?></li>
							<li><?php
						if ($modelMerge[$i]['ctt_bank_branch'] != NULL)
						{
							echo $form->checkbox($model, 'check_ctt_bank_branch', ['label' => "", 'groupOptions' => ["class" => "checkbox-inline"]]);
						}
						?><b>Bank Branch</b> :<?= $modelMerge[$i]['ctt_bank_branch'] ?></li>
							<li><?php
						if ($modelMerge[$i]['ctt_bank_ifsc'] != NULL)
						{
							echo $form->checkbox($model, 'check_ctt_bank_ifsc', ['label' => "", 'groupOptions' => ["class" => "checkbox-inline"]]);
						}
						?><b>IFSC Code</b> :<?= $modelMerge[$i]['ctt_bank_ifsc'] ?></li>

							<li><?php
						if ($modelMerge[$i]['ctt_beneficiary_name'] != NULL)
						{
							echo $form->checkbox($model, 'check_ctt_beneficiary_name', ['label' => "", 'groupOptions' => ["class" => "checkbox-inline"]]);
						}
						?><b>Account Owner Name</b> :<?= $modelMerge[$i]['ctt_beneficiary_name'] ?></li>
							<li><?php
						if ($modelMerge[$i]['ctt_beneficiary_id'] != NULL)
						{
							echo $form->checkbox($model, 'check_ctt_beneficiary_id', ['label' => "", 'groupOptions' => ["class" => "checkbox-inline"]]);
						}
						?><b>Beneficiary Id</b> :<?= $modelMerge[$i]['ctt_beneficiary_id'] ?></li>
		<!--									<button class="btn btn-primary btn-sm" style="text-align: center" onclick='copyContact(<?= $i ?>)' >Copy Contact</button>-->
							<li>
								<button class="btn" style="text-align: center" onclick='copyContactChecked(<?= $i ?>)' >Copy Checked</button>
							</li>
							<br>
						</div>
	<?php } ?>
				</ul>
			</div>
	    </div>
<?php } ?>
</div>
<?php echo CHtml::endForm(); ?>
<script type="text/javascript">
    $(document).ready(function () {
        $("#errorctyname").text('');
        $("#errorstate").text('');
        $("#Contact_ctt_user_type_0").click(function () {
            $("#individual").show();
            $("#business").hide();
            $("#business_type").hide();
            $("#Contact_userInput").val(1);
        });
        $("#Contact_ctt_user_type_1").click(function () {
            $("#individual").hide();
            $("#business").show();
            $("#business_type").show();
            $("#Contact_userInput").val(2);
        });
        $("#Contact_ctt_account_type_0").click(function () {
            $("#Contact_accountInput").val(0);
        });
        $("#Contact_ctt_user_type_1").click(function () {
            $("#Contact_accountInput").val(1);
        });

        $('input[type="button"]').click(function () {
            var radioValue = $("input[name='Contact[ctt_user_type]']:checked").val();
            var name = "";
            if (radioValue == 1) {
                name = $("#Contact_ctt_first_name").val() + " " + $("#Contact_ctt_last_name").val();
            } else {
                name = $("#Contact_ctt_business_name").val();
            }
            bootbox.confirm({message: "Do you want to merge  contact with " + name + " ?",
                buttons: {
                    confirm: {
                        label: 'Yes',
                        className: 'btn-success'
                    },
                    cancel: {
                        label: 'No',
                        className: 'btn-danger'
                    }
                },
                callback: function (result) {
                    if (result) {
                        $('#add_contact_form').submit();
                        return false;
                    }
                }
            });
        });
    });
    function populateSource(obj, cityId) {
        obj.load(function (callback)
        {
            var obj = this;
            if ($sourceList == null)
            {
                xhr = $.ajax({
                    url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery', ['apshow' => 1])) ?>',
                    dataType: 'json',
                    data: {
                        // city: cityId
                    },
                    //  async: false,
                    success: function (results)
                    {
                        $sourceList = results;
                        obj.enable();
                        callback($sourceList);
                        obj.setValue(cityId);
                    },
                    error: function ()
                    {
                        callback();
                    }
                });
            } else
            {
                obj.enable();
                callback($sourceList);
                obj.setValue(cityId);
            }
        });
    }
    function loadSource(query, callback) {
        //	if (!query.length) return callback();
        $.ajax({
            url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery')) ?>?apshow=1&q=' + encodeURIComponent(query),
            type: 'GET',
            dataType: 'json',
            global: false,
            error: function ()
            {
                callback();
            },
            success: function (res)
            {
                callback(res);
            }
        });
    }
    function copyContact(index) {
        var pausecontent = <?php echo json_encode($modelMerge); ?>;
        if (pausecontent[index].ctt_account_type == 1) {
            $("#uniform-Contact_ctt_account_type_0 span").removeClass("checked");
            $("#uniform-Contact_ctt_account_type_1 span").addClass("checked");
        } else {
            $("#uniform-Contact_ctt_account_type_0 span").addClass("checked");
            $("#uniform-Contact_ctt_account_type_1 span").removeClass("checked");
        }
        if (pausecontent[index].ctt_user_type == 2) {
            $("#individual").hide();
            $("#business").show();
            $("#business_type").show();
            $("#Contact_ctt_business_name").val(pausecontent[index].ctt_business_name !== null ? pausecontent[index].ctt_business_name : "");
            $('#Contact_ctt_business_type').val(null).trigger('change');
            $('#Contact_ctt_business_type').val(pausecontent[index].ctt_business_type !== null ? pausecontent[index].ctt_business_type : "");
            $('#Contact_ctt_business_type').trigger('change');
            $("#Contact_ctt_first_name").val("");
            $("#Contact_ctt_last_name").val("");
            $("#uniform-Contact_ctt_user_type_0 span").removeClass("checked");
            $("#uniform-Contact_ctt_user_type_1 span").addClass("checked");
        } else {
            $("#individual").show();
            $("#business").hide();
            $("#business_type").hide();
            $("#Contact_ctt_business_name").val("");
            $('#Contact_ctt_business_type').val(null).trigger('change');
            $("#Contact_ctt_first_name").val(pausecontent[index].ctt_first_name !== null ? pausecontent[index].ctt_first_name : "");
            $("#Contact_ctt_last_name").val(pausecontent[index].ctt_last_name !== null ? pausecontent[index].ctt_last_name : "");
            $("#uniform-Contact_ctt_user_type_1 span").removeClass("checked");
            $("#uniform-Contact_ctt_user_type_0 span").addClass("checked");
        }
        $("#Contact_userInput").val(pausecontent[index].ctt_user_type);
        $("#Contact_accountInput").val(pausecontent[index].ctt_account_type);
        $("#Contact_ctt_address").val(pausecontent[index].ctt_address !== null ? pausecontent[index].ctt_address : "");
        $("#Contact_ctt_voter_no").val(pausecontent[index].ctt_voter_no !== null ? pausecontent[index].ctt_voter_no : "");
        $("#Contact_ctt_aadhaar_no").val(pausecontent[index].ctt_aadhaar_no !== null ? pausecontent[index].ctt_aadhaar_no : "");
        $("#Contact_ctt_pan_no").val(pausecontent[index].ctt_pan_no !== null ? pausecontent[index].ctt_pan_no : "");
        $("#Contact_ctt_license_no").val(pausecontent[index].ctt_license_no !== null ? pausecontent[index].ctt_license_no : "");
        $("#Contact_ctt_bank_name").val(pausecontent[index].ctt_bank_name !== null ? pausecontent[index].ctt_bank_name : "");
        $("#Contact_ctt_bank_account_no").val(pausecontent[index].ctt_bank_account_no !== null ? pausecontent[index].ctt_bank_account_no : "");
        $("#Contact_ctt_bank_branch").val(pausecontent[index].ctt_bank_branch !== null ? pausecontent[index].ctt_bank_branch : "");
        $("#Contact_ctt_bank_ifsc").val(pausecontent[index].ctt_bank_ifsc !== null ? pausecontent[index].ctt_bank_ifsc : "");
        $("#Contact_ctt_beneficiary_name").val(pausecontent[index].ctt_beneficiary_name !== null ? pausecontent[index].ctt_beneficiary_name : "");
        $("#Contact_ctt_beneficiary_id").val(pausecontent[index].ctt_beneficiary_id !== null ? pausecontent[index].ctt_beneficiary_id : "");
        $('#Contact_ctt_state').val(null).trigger('change');
        $('#Contact_ctt_state').val(pausecontent[index].ctt_state !== null ? pausecontent[index].ctt_state : ""); // Select the option with a value of '1'
        $('#Contact_ctt_state').trigger('change');
        $('#Contact_ctt_dl_issue_authority').val(null).trigger('change');
        $('#Contact_ctt_dl_issue_authority').val(pausecontent[index].ctt_dl_issue_authority !== null ? pausecontent[index].ctt_dl_issue_authority : ""); // Select the option with a value of '1'
        $('#Contact_ctt_dl_issue_authority').trigger('change');
        var $select = $('select').selectize();
        var selectize = $select[0].selectize;
        selectize.setValue([pausecontent[index].ctt_city]);
        $("#Contact_locale_license_issue_date").val(pausecontent[index].locale_license_issue_date !== null ? moment(pausecontent[index].locale_license_issue_date, 'YYYY-MM-DD', true).format('DD/MM/YYYY') : "");
        $("#Contact_locale_license_exp_date").val(pausecontent[index].locale_license_exp_date !== null ? moment(pausecontent[index].locale_license_exp_date, 'YYYY-MM-DD', true).format('DD/MM/YYYY') : "");
    }

    function copyContactChecked(index) {
        var pausecontent = <?php echo json_encode($modelMerge); ?>;
        var pauseoldcontent = <?php echo json_encode($model); ?>;
		var count = 0;
        
		$('.contact1').find('input[type=checkbox]').each(function () {
        if (this.checked) {
            count++;
        }
		});
        if (count > 0)
        {
            if (pausecontent[index].ctt_user_type == 2) {
                $("#individual").hide();
                $("#business").show();
                $("#business_type").show();
                $("#Contact_ctt_business_name").val(pausecontent[index].ctt_business_name !== null ? pausecontent[index].ctt_business_name : "");
                $('#Contact_ctt_business_type').val(null).trigger('change');
                $('#Contact_ctt_business_type').val(pausecontent[index].ctt_business_type !== null ? pausecontent[index].ctt_business_type : "");
                $('#Contact_ctt_business_type').trigger('change');
                $("#Contact_ctt_first_name").val("");
                $("#Contact_ctt_last_name").val("");
                $("#uniform-Contact_ctt_user_type_0 span").removeClass("checked");
                $("#uniform-Contact_ctt_user_type_1 span").addClass("checked");
            } else {
                $("#individual").show();
                $("#business").hide();
                $("#business_type").hide();
                $("#Contact_ctt_business_name").val("");
                $('#Contact_ctt_business_type').val(null).trigger('change');

                $("#uniform-Contact_ctt_user_type_1 span").removeClass("checked");
                $("#uniform-Contact_ctt_user_type_0 span").addClass("checked");
            }
            if ($(".contact" + (index + 1) + " #uniform-Contact_contactperson").find('.checked').length)
            {
                $("#Contact_ctt_first_name").val(pausecontent[index].ctt_first_name !== null ? pausecontent[index].ctt_first_name : "");
                $("#Contact_ctt_last_name").val(pausecontent[index].ctt_last_name !== null ? pausecontent[index].ctt_last_name : "");
            }

            if ($(".contact" + (index + 1) + " #uniform-Contact_phn_phone_no").find('.checked').length)
            {
                $("#ContactPhone_phn_phone_no").val(pausecontent[index].phn_phone_no !== null ? pausecontent[index].phn_phone_no : "");
            }

            if ($(".contact" + (index + 1) + " #uniform-Contact_eml_email_address").find('.checked').length)
            {
                $("#ContactEmail_eml_email_address").val(pausecontent[index].eml_email_address !== null ? pausecontent[index].eml_email_address : "");
            }

            if ($(".contact" + (index + 1) + " #uniform-Contact_check_ctt_address").find('.checked').length)
            {
                $("#Contact_ctt_address").val(pausecontent[index].ctt_address !== null ? pausecontent[index].ctt_address : "");
            }

            if ($(".contact" + (index + 1) + " #uniform-Contact_check_ctt_state").find('.checked').length)
            {
                $('#Contact_ctt_state').val(null).trigger('change');
                $('#Contact_ctt_state').val(pausecontent[index].ctt_state !== null ? pausecontent[index].ctt_state : ""); // Select the option with a value of '1'
                $('#Contact_ctt_state').trigger('change');
            }

            if ($(".contact" + (index + 1) + " #uniform-Contact_check_ctt_city").find('.checked').length)
            {
                var $select = $('select').selectize();
                var selectize = $select[0].selectize;
                selectize.setValue([pausecontent[index].ctt_city]);
            }

            if ($(".contact" + (index + 1) + " #uniform-Contact_check_ctt_voter_no").find('.checked').length)
            {
                $("#Contact_ctt_voter_no").val(pausecontent[index].ctt_voter_no !== null ? pausecontent[index].ctt_voter_no : "");
                $("#Contact_ctt_voter_doc_id").val(pausecontent[index].ctt_voter_doc_id !== null ? pausecontent[index].ctt_voter_doc_id : "");
            }

            if ($(".contact" + (index + 1) + " #uniform-Contact_check_ctt_aadhaar_no").find('.checked').length)
            {
                $("#Contact_ctt_aadhaar_no").val(pausecontent[index].ctt_aadhaar_no !== null ? pausecontent[index].ctt_aadhaar_no : "");
                $("#Contact_ctt_aadhar_doc_id").val(pausecontent[index].ctt_aadhar_doc_id !== null ? pausecontent[index].ctt_aadhar_doc_id : "");
            }

            if ($(".contact" + (index + 1) + " #uniform-Contact_check_ctt_pan_no").find('.checked').length)
            {
                $("#Contact_ctt_pan_no").val(pausecontent[index].ctt_pan_no !== null ? pausecontent[index].ctt_pan_no : "");
                $("#Contact_ctt_pan_doc_id").val(pausecontent[index].ctt_pan_doc_id !== null ? pausecontent[index].ctt_pan_doc_id : "");
            }

            if ($(".contact" + (index + 1) + " #uniform-Contact_check_ctt_license_no").find('.checked').length)
            {
                $("#Contact_ctt_license_no").val(pausecontent[index].ctt_license_no !== null ? pausecontent[index].ctt_license_no : "");
                $("#Contact_ctt_license_doc_id").val(pausecontent[index].ctt_license_doc_id !== null ? pausecontent[index].ctt_license_doc_id : "");
            }

            if ($(".contact" + (index + 1) + " #uniform-Contact_check_ctt_bank_name").find('.checked').length)
            {
                $("#Contact_ctt_bank_name").val(pausecontent[index].ctt_bank_name !== null ? pausecontent[index].ctt_bank_name : "");
            }

            if ($(".contact" + (index + 1) + " #uniform-Contact_check_ctt_bank_account_no").find('.checked').length)
            {
                $("#Contact_ctt_bank_account_no").val(pausecontent[index].ctt_bank_account_no !== null ? pausecontent[index].ctt_bank_account_no : "");
            }

            if ($(".contact" + (index + 1) + " #uniform-Contact_check_ctt_bank_branch").find('.checked').length)
            {
                $("#Contact_ctt_bank_branch").val(pausecontent[index].ctt_bank_branch !== null ? pausecontent[index].ctt_bank_branch : "");
            }

            if ($(".contact" + (index + 1) + " #uniform-Contact_check_ctt_bank_ifsc").find('.checked').length)
            {
                $("#Contact_ctt_bank_ifsc").val(pausecontent[index].ctt_bank_ifsc !== null ? pausecontent[index].ctt_bank_ifsc : "");
            }

            if ($(".contact" + (index + 1) + " #uniform-Contact_check_ctt_beneficiary_name").find('.checked').length)
            {
                $("#Contact_ctt_beneficiary_name").val(pausecontent[index].ctt_beneficiary_name !== null ? pausecontent[index].ctt_beneficiary_name : "");
            }

            if ($(".contact" + (index + 1) + " #uniform-Contact_check_ctt_beneficiary_id").find('.checked').length)
            {
                $("#Contact_ctt_beneficiary_id").val(pausecontent[index].ctt_beneficiary_id !== null ? pausecontent[index].ctt_beneficiary_id : "");
            }

            if ($(".contact" + (index + 1) + " #uniform-Contact_ctt_id").find('.checked').length)
            {
                $("#Contact_new_ctt_id").val(pausecontent[index].ctt_id !== null ? pausecontent[index].ctt_id : "");
                $("#Contact_ctt_first_name").val(pausecontent[index].ctt_first_name !== null ? pausecontent[index].ctt_first_name : "");
                $("#Contact_ctt_last_name").val(pausecontent[index].ctt_last_name !== null ? pausecontent[index].ctt_last_name : "");
                $("#ContactPhone_phn_phone_no").val(pausecontent[index].phn_phone_no !== null ? pausecontent[index].phn_phone_no : "");
                $("#ContactEmail_eml_email_address").val(pausecontent[index].eml_email_address !== null ? pausecontent[index].eml_email_address : "");
                $("#Contact_userInput").val(pausecontent[index].ctt_user_type);
                $("#Contact_accountInput").val(pausecontent[index].ctt_account_type);
                $("#Contact_ctt_address").val(pausecontent[index].ctt_address !== null ? pausecontent[index].ctt_address : "");
                $("#Contact_ctt_voter_no").val(pausecontent[index].ctt_voter_no !== null ? pausecontent[index].ctt_voter_no : "");
                $("#Contact_ctt_aadhaar_no").val(pausecontent[index].ctt_aadhaar_no !== null ? pausecontent[index].ctt_aadhaar_no : "");
                $("#Contact_ctt_pan_no").val(pausecontent[index].ctt_pan_no !== null ? pausecontent[index].ctt_pan_no : "");
                $("#Contact_ctt_license_no").val(pausecontent[index].ctt_license_no !== null ? pausecontent[index].ctt_license_no : "");
                $("#Contact_ctt_bank_name").val(pausecontent[index].ctt_bank_name !== null ? pausecontent[index].ctt_bank_name : "");
                $("#Contact_ctt_bank_account_no").val(pausecontent[index].ctt_bank_account_no !== null ? pausecontent[index].ctt_bank_account_no : "");
                $("#Contact_ctt_bank_branch").val(pausecontent[index].ctt_bank_branch !== null ? pausecontent[index].ctt_bank_branch : "");
                $("#Contact_ctt_bank_ifsc").val(pausecontent[index].ctt_bank_ifsc !== null ? pausecontent[index].ctt_bank_ifsc : "");
                $("#Contact_ctt_beneficiary_name").val(pausecontent[index].ctt_beneficiary_name !== null ? pausecontent[index].ctt_beneficiary_name : "");
                $("#Contact_ctt_beneficiary_id").val(pausecontent[index].ctt_beneficiary_id !== null ? pausecontent[index].ctt_beneficiary_id : "");
                $('#Contact_ctt_state').val(null).trigger('change');
                $('#Contact_ctt_state').val(pausecontent[index].ctt_state !== null ? pausecontent[index].ctt_state : ""); // Select the option with a value of '1'
                $('#Contact_ctt_state').trigger('change');
                $('#Contact_ctt_dl_issue_authority').val(null).trigger('change');
                $('#Contact_ctt_dl_issue_authority').val(pausecontent[index].ctt_dl_issue_authority !== null ? pausecontent[index].ctt_dl_issue_authority : ""); // Select the option with a value of '1'
                $('#Contact_ctt_dl_issue_authority').trigger('change');
                var $select = $('select').selectize();
                var selectize = $select[0].selectize;
                selectize.setValue([pausecontent[index].ctt_city]);
                $("#Contact_locale_license_issue_date").val(pausecontent[index].locale_license_issue_date !== null ? moment(pausecontent[index].locale_license_issue_date, 'YYYY-MM-DD', true).format('DD/MM/YYYY') : "");
                $("#Contact_locale_license_exp_date").val(pausecontent[index].locale_license_exp_date !== null ? moment(pausecontent[index].locale_license_exp_date, 'YYYY-MM-DD', true).format('DD/MM/YYYY') : "");
            }
        } 
		else
        {
			alert('Please Check Atleast One Checkbox');
        }
    }
</script>
