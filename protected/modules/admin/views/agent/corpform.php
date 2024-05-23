
<?php
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/jquery.mask.min.js');
Yii::app()->clientScript->registerCssFile(ASSETS_URL . '/plugins/form-select2/select2.css');
?>
<style>
    .table_new table{ width: 99%;}
    .selectize-input {
        min-width: 0px !important;
        width: 30% !important;

    }
    .form-horizontal .form-group{ margin-left: 0; margin-right: 0;}
</style>
<div class="container">
    <div class="row">
		<?php
		$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'driver-register-form', 'enableClientValidation' => FALSE,
			'clientOptions'			 => array(
				'validateOnSubmit'	 => true,
				'errorCssClass'		 => 'has-error'
			),
			'enableAjaxValidation'	 => false,
			'errorMessageCssClass'	 => 'help-block',
			'htmlOptions'			 => array(
				'class'			 => 'form-horizontal', 'enctype'		 => 'multipart/form-data', 'autocomplete'	 => "off",
			),
		));
		/* @var $form TbActiveForm */
		?>
        <input type="hidden" name="crpId" id="crpId" value="<?= $model->agt_id; ?>">
        <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
            <div class="panel panel-white panel-border">
                <div class="panel-heading">                            
                    GENERAL INFORMATION
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <label for="inputEmail3" class="control-label">Business Entity Name</label>
							<?= $form->textFieldGroup($model, 'agt_company', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Business Entity Name')))) ?>  
                        </div>
                        <div class="col-sm-6">
							<?
//                            if ($model->agt_id != '') {
//                                $readOnly = ['readOnly' => 'readOnly'];
//                            } else {
							$readOnly	 = [];
//                            }
							?>
                            <label for="inputEmail3" class="control-label">Corporate Code</label>
							<?= $form->textFieldGroup($model, 'agt_referral_code', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Corporate Code') + $readOnly))) ?>  
                        </div>
                        <div class="col-sm-6">
                            <label for="inputEmail3" class="control-label">Owner First Name</label>
							<?= $form->textFieldGroup($model, 'agt_fname', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter First Name')))) ?>  
                        </div>
                        <div class="col-sm-6">
                            <label for="inputEmail3" class="control-label">Owner Last Name</label>
							<?= $form->textFieldGroup($model, 'agt_lname', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Last Name')))) ?>  
                        </div>
						<?
						if ($model->isNewRecord)
						{
							$model->agt_commission_value = Yii::app()->params['agentDefCommissionValue'];
							$model->agt_commission		 = Yii::app()->params['agentDefCommission'];
						}
						?>
                        <div class="col-xs-12">
                            <div class="col-sm-6">
                                <div class="form-group">
									<?
									if ($model->agt_type == 0 || $model->agt_type == 1)
									{
										?>
										<label class="control-label" for="exampleInputName6">Discount Value Type</label>
										<?
									}
									else
									{
										?>
										<label class="control-label" for="exampleInputName6">Commission Value Type</label>
									<? } ?>
									<?= $form->radioButtonListGroup($model, 'agt_commission_value', array('label' => '', 'widgetOptions' => array('htmlOptions' => [], 'data' => Promos::$valueType), 'inline' => true)) ?>
                                </div>
                            </div>
                            <div class="col-sm-6">
								<?
								if ($model->agt_type == 0 || $model->agt_type == 1)
								{
									?>
									<label class="control-label" for="exampleInputName6">Discount Amount</label>
									<?
								}
								else
								{
									?>
									<label class="control-label" for="exampleInputName6">Commission Amount/Agent Markup</label>
								<? } ?>
								<?= $form->textFieldGroup($model, 'agt_commission', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Commission Amount')))) ?>
                            </div> 
                        </div>
                        <div class="col-xs-12">
                            <div class="col-sm-6">
								<?= $form->numberFieldGroup($model, 'agt_credit_limit', array('label' => "Credit Limit", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter credit limit for agent', 'min' => 0)))) ?>
                            </div>
                        </div> 
                    </div>
                </div>
            </div>

            <div class="panel panel-white panel-border">
                <div class="panel-heading">                            
                    ACCOUNT INFORMATION (For electronic payments Gozo Technologies)
                </div>
                <div class="panel-body pt10">
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="inputEmail3" class="control-label">Beneficiary Name</label>
							<?= $form->textFieldGroup($model, 'agt_bank_owner', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Name')))) ?>  
                        </div>
                        <div class="col-sm-4">
                            <label for="inputPassword3" class="control-label">Beneficiary Country</label>
							<?= $form->textFieldGroup($model, 'agt_bank_owner_country', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Country Name')))) ?>  
                        </div>
                        <div class="col-sm-4">
                            <label for="inputFirstName3" class="control-label">Beneficiary Bank</label>
							<?= $form->textFieldGroup($model, 'agt_bank', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Bank Name')))) ?>  
                        </div>
                        <div class="col-sm-4">
                            <label for="inputLastName3" class="control-label">Beneficiary Account Number</label>
							<?= $form->textFieldGroup($model, 'agt_bank_account', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Account Number')))) ?>  
                        </div>
                        <div class="col-sm-4">
                            <label for="inputLastName3" class="control-label">RTGS Number</label>
							<?= $form->textFieldGroup($model, 'agt_rtgs', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter RTGS Number')))) ?>  
                        </div>
                        <div class="col-sm-4">
                            <label for="inputLastName3" class="control-label">MICR Number</label>
							<?= $form->textFieldGroup($model, 'agt_bank_micr', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter MICR Number')))) ?>  
                        </div>
                        <div class="col-sm-6">
                            <label for="inputLastName3" class="control-label">Beneficiary Bank Branch Address</label>
							<?= $form->textAreaGroup($model, 'agt_bank_branch_addrs', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Branch Address')))) ?>  
                        </div>
                        <div class="col-xs-12">
                            *For any queries or additional information, contact <a href="#">accounts@gozocabs.in</a>
                        </div>

                    </div>
                </div>
            </div>

            <div class="panel panel-white panel-border">
                <div class="panel-heading">DOCUMENTS TO BE UPLOADED</div>
                <div class="panel-body">                                 
                    <div class="row">

                        <div class="col-xs-6">
                            <div class="form-group">
                                <label class="control-label" for="exampleInputName6">Signed copy of corporate account agreement</label>
								<?= $form->fileFieldGroup($model, 'agt_agreement', array('label' => '', 'widgetOptions' => array())); ?>
								<?
								if ($model->agt_agreement != '')
								{
									?>
									<a href="<?= $model->agt_agreement ?>" target="_blank"><?= basename($model->agt_agreement) ?></a>
								<? } ?>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label class="control-label" for="exampleInputName6">Scanned copy of business registration certificate</label>
								<?= $form->fileFieldGroup($model, 'agt_bussiness_registration', array('label' => '', 'widgetOptions' => array())); ?>
								<?
								if ($model->agt_bussiness_registration != '')
								{
									?>
									<a href="<?= $model->agt_bussiness_registration ?>" target="_blank"><?= basename($model->agt_bussiness_registration) ?></a>
								<? } ?>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label class="control-label" for="exampleInputName6">Duly filled corporate account registration form</label>
								<?= $form->fileFieldGroup($model, 'agt_corp_reg_form', array('label' => '', 'widgetOptions' => array())); ?>
								<?
								if ($model->agt_corp_reg_form != '')
								{
									?>
									<a href="<?= $model->agt_corp_reg_form ?>" target="_blank"><?= basename($model->agt_corp_reg_form) ?></a>
								<? } ?>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label class="control-label" for="exampleInputName6">Initial deposit for corporate account opening (Cheque or proof of deposit payment)</label>
								<?= $form->fileFieldGroup($model, 'agt_deposit_proof', array('label' => '', 'widgetOptions' => array())); ?>
								<?
								if ($model->agt_deposit_proof != '')
								{
									?>
									<a href="<?= $model->agt_deposit_proof ?>" target="_blank"><?= basename($model->agt_deposit_proof) ?></a>
								<? } ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <p>Note: Attach the documents (Which are applicable) and any other documents which are necessary.<br>
                                    All the attachments shall be self attested.</p>
                            </div>

                        </div>

                    </div>
                </div>
            </div>

            <div class="panel panel-white panel-border">
                <div class="panel-heading">CERTIFICATION</div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="pb10"><u>Details of relationship with Gozo Technologies Pvt Limited employees, if any</u></div>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <td>Sl No.</td>
                                        <td>Name of Employee</td>
                                        <td>Designation</td>
                                        <td>Nature of Relationship</td>
                                    </tr>
                                    <tr>
                                        <td>1</td>
                                        <td><?= $form->textFieldGroup($AgentRel, 'arl_rel_gozoemp_name1', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter name of employee')))) ?></td>
                                        <td><?= $form->textFieldGroup($AgentRel, 'arl_rel_gozoemp_desig1', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Designation')))) ?></td>
                                        <td><?= $form->textFieldGroup($AgentRel, 'arl_rel_gozoemp_reltype1', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Nature of Relationship')))) ?></td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td><?= $form->textFieldGroup($AgentRel, 'arl_rel_gozoemp_name2', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter name of employee')))) ?></td>
                                        <td><?= $form->textFieldGroup($AgentRel, 'arl_rel_gozoemp_desig2', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Designation')))) ?></td>
                                        <td><?= $form->textFieldGroup($AgentRel, 'arl_rel_gozoemp_reltype2', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Nature of Relationship')))) ?></td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td><?= $form->textFieldGroup($AgentRel, 'arl_rel_gozoemp_name3', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter name of employee')))) ?></td>
                                        <td><?= $form->textFieldGroup($AgentRel, 'arl_rel_gozoemp_desig3', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Designation')))) ?></td>
                                        <td><?= $form->textFieldGroup($AgentRel, 'arl_rel_gozoemp_reltype3', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Nature of Relationship')))) ?></td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td><?= $form->textFieldGroup($AgentRel, 'arl_rel_gozoemp_name4', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter name of employee')))) ?></td>
                                        <td><?= $form->textFieldGroup($AgentRel, 'arl_rel_gozoemp_desig4', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Designation')))) ?></td>
                                        <td><?= $form->textFieldGroup($AgentRel, 'arl_rel_gozoemp_reltype4', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Nature of Relationship')))) ?></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="pb10"><u>Authorized users requested by Corporate account holder</u></div>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <td>Sl No.</td>
                                        <td>Name</td>
                                        <td>Email</td>
                                        <td>Phone</td>
                                    </tr>
									<?
									if (count($AgentUsers1) > 0)
									{
										foreach ($AgentUsers1 as $key => $value)
										{
											?> 
											<tr>
												<td>1</td>
												<td> <?= $form->hiddenField($AgentUsers, 'aru_id[]', array('value' => $value->aru_id)); ?>
													<?= $form->textFieldGroup($AgentUsers, 'aru_name[]', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('value' => $value->aru_name)))); ?></td>
												<td> <?= $form->textFieldGroup($AgentUsers, 'aru_email[]', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('value' => $value->aru_email)))); ?></td>
												<td> <?= $form->textFieldGroup($AgentUsers, 'aru_phone[]', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('value' => $value->aru_phone)))); ?></td>
											</tr> 
											<?
										}
									}
									?>
                                    <tr id="adduserstr">
                                        <td>1</td>
                                        <td> <?= $form->hiddenField($AgentUsers, 'aru_id[]'); ?><?= $form->textFieldGroup($AgentUsers, 'aru_name[]', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Name')))); ?></td>
                                        <td> <?= $form->textFieldGroup($AgentUsers, 'aru_email[]', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Email')))); ?></td>
                                        <td> <?= $form->textFieldGroup($AgentUsers, 'aru_phone[]', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Phone')))); ?></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="row pull-right"><span class="btn btn-primary" onclick="return addUsers();">add more</span></div><br>

                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-default panel-border ">
                <h3 class="pl15">Notification defaults</h3>
                <div class="panel-body p0">
                    <div class="col-xs-12"><label class="control-label" for="exampleInputCompany6"><b>All Bookings Copied to</b></label>
                    </div>
                    <div class="col-sm-4">
						<?= $form->textFieldGroup($model, 'agt_copybooking_name', array('label' => "Name", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Name')))) ?>
                    </div>
                    <div class="col-xs-4"> 
						<?= $form->textFieldGroup($model, 'agt_copybooking_email', array('label' => "Email", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Email')))) ?>
                    </div>
                    <div class="col-xs-4"> 
						<?= $form->textFieldGroup($model, 'agt_copybooking_phone', array('label' => "Phone", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Phone')))) ?>
                    </div>
                    <div class="col-xs-12"><label class="control-label" for="exampleInputCompany6"><b>All bookings copied to gozo account manager</b></label>
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label">Select Account Manager</label>
						<?
						$AccManagerArr1	 = VehicleTypes::model()->getJSON(Admins::model()->findNameList());
						$this->widget('booster.widgets.TbSelect2', array(
							'model'			 => $model,
							'attribute'		 => 'agt_copybooking_admin_id',
							'val'			 => $model->agt_copybooking_admin_id,
							'asDropDownList' => FALSE,
							'options'		 => array('data' => new CJavaScriptExpression($AccManagerArr1)),
							'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Account Manager', 'id' => 'Agents_agt_copybooking_admin_id')
						));
						?>  
                    </div>
                    <div class="col-xs-4"> 
						<?= $form->textFieldGroup($model, 'agt_copybooking_admin_email', array('label' => "Email", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Email')))) ?>
                    </div>
                    <div class="col-xs-4"> 
						<?= $form->textFieldGroup($model, 'agt_copybooking_admin_phone', array('label' => "Phone", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Phone')))) ?>
                    </div>
                    <div class="col-xs-12"><label class="control-label" for="exampleInputCompany6"><b>Send booking updates to traveller</b></label>
                    </div>
                    <div class="col-xs-12"><?= $form->radioButtonListGroup($model, 'agt_trvl_sendupdate', array('label' => '', 'widgetOptions' => array('htmlOptions' => [], 'data' => [1 => 'Yes', 2 => 'No']), 'inline' => true)) ?>
                    </div>
                    <div class="col-xs-6"> 
						<? //= $form->textFieldGroup($model, 'agt_trvl_email', array('label' => "Email", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Email'))))   ?>
                    </div>
                    <div class="col-xs-6"> 
						<? //= $form->textFieldGroup($model, 'agt_trvl_phone', array('label' => "Phone", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Phone'))))   ?>
                    </div>
                    <div class="col-xs-12 h4">Notification Options</div>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th></th>
                                <th colspan="4">Agent</th>
                                <th colspan="4">Traveller</th>    
                                <th colspan="4">Relationship Manager</th>   
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td></td>
                                <td>Email</td><td>SMS</td><td>App</td><td>WhatsApp</td>
                                <td>Email</td><td>SMS</td><td>App </td><td>WhatsApp</td>
                                <td>Email</td><td>SMS</td><td>App </td><td>WhatsApp</td>
                            </tr>
							<?
							$arrEvents		 = AgentMessages::getEvents();
							foreach ($arrEvents as $key => $value)
							{

								$agtMsgModel = AgentMessages::model()->getByEventAndAgent($model->agt_id, $key);
								if ($model->isNewRecord)
								{
									$agtMsgModel = new AgentMessages();

									if ($key == AgentMessages::BOOKING_CONF_WITH_PAYMENTINFO || $key == AgentMessages::PAYMENT_CONFIRM || $key == AgentMessages::PAYMENT_FAILED || $key == AgentMessages::BOOKING_EDIT || $key == AgentMessages::CAB_ASSIGNED || $key == AgentMessages::INVOICE || $key == AgentMessages::RATING_AND_REVIEWS || $key == AgentMessages::RESCHEDULE_REQUEST || $key == AgentMessages::CAB_DRIVER_DETAIL || $key == AgentMessages::CANCEL_TRIP)
									{
										$agtMsgModel->agt_agent_email	 = 1;
										$agtMsgModel->agt_agent_sms		 = 1;
										$agtMsgModel->agt_agent_whatsapp = 1;
									}
									if ($key == AgentMessages::BOOKING_CONF_WITHOUT_PAYMENTINFO || $key == AgentMessages::CAB_ASSIGNED)
									{
										$agtMsgModel->agt_trvl_email	 = 1;
										$agtMsgModel->agt_trvl_sms		 = 1;
										$agtMsgModel->agt_trvl_whatsapp	 = 1;
									}
								}

								if ($agtMsgModel != '')
								{
									$isAgentEmail	 = ($agtMsgModel->agt_agent_email == 1) ? true : false;
									$isAgentSMS		 = ($agtMsgModel->agt_agent_sms == 1) ? true : false;
									$isAgentApp		 = ($agtMsgModel->agt_agent_app == 1) ? true : false;
									$isAgentWhatsApp = ($agtMsgModel->agt_agent_whatsapp == 1) ? true : false;

									$isTrvlEmail	 = ($agtMsgModel->agt_trvl_email == 1) ? true : false;
									$isTrvlSMS		 = ($agtMsgModel->agt_trvl_sms == 1) ? true : false;
									$isTrvlApp		 = ($agtMsgModel->agt_trvl_app == 1) ? true : false;
									$isTrvlWhatsApp	 = ($agtMsgModel->agt_trvl_whatsapp == 1) ? true : false;

									$isRmEmail		 = ($agtMsgModel->agt_rm_email == 1) ? true : false;
									$isRmSMS		 = ($agtMsgModel->agt_rm_sms == 1) ? true : false;
									$isRmApp		 = ($agtMsgModel->agt_rm_app == 1) ? true : false;
									$isRmWhatsApp	 = ($agtMsgModel->agt_rm_whatsapp == 1) ? true : false;
								}
								?>    
								<tr>
									<th><?= $arrEvents[$key] ?></th>
									<td>  <?= $form->checkboxGroup($AgentMessages, 'agt_agent_email[' . $key . ']', ['label' => '', 'widgetOptions' => ['htmlOptions' => ['checked' => $isAgentEmail]], 'inline' => true]); ?></td>
									<td>  <?= $form->checkboxGroup($AgentMessages, 'agt_agent_sms[' . $key . ']', ['label' => '', 'widgetOptions' => ['htmlOptions' => ['checked' => $isAgentSMS]], 'inline' => true]); ?></td>
									<td>  <?= $form->checkboxGroup($AgentMessages, 'agt_agent_app[' . $key . ']', ['label' => '', 'widgetOptions' => ['htmlOptions' => ['checked' => $isAgentApp]], 'inline' => true]); ?></td>
									<td>  <?= $form->checkboxGroup($AgentMessages, 'agt_agent_whatsapp[' . $key . ']', ['label' => '', 'widgetOptions' => ['htmlOptions' => ['checked' => $isAgentWhatsApp]], 'inline' => true]); ?></td>
									<td>  <?= $form->checkboxGroup($AgentMessages, 'agt_trvl_email[' . $key . ']', ['label' => '', 'widgetOptions' => ['htmlOptions' => ['checked' => $isTrvlEmail]], 'inline' => true]); ?></td>
									<td>  <?= $form->checkboxGroup($AgentMessages, 'agt_trvl_sms[' . $key . ']', ['label' => '', 'widgetOptions' => ['htmlOptions' => ['checked' => $isTrvlSMS]], 'inline' => true]); ?></td>
									<td>  <?= $form->checkboxGroup($AgentMessages, 'agt_trvl_app[' . $key . ']', ['label' => '', 'widgetOptions' => ['htmlOptions' => ['checked' => $isTrvlApp]], 'inline' => true]); ?></td>
									<td>  <?= $form->checkboxGroup($AgentMessages, 'agt_trvl_whatsapp[' . $key . ']', ['label' => '', 'widgetOptions' => ['htmlOptions' => ['checked' => $isTrvlWhatsApp]], 'inline' => true]); ?></td>
									<td>  <?= $form->checkboxGroup($AgentMessages, 'agt_rm_email[' . $key . ']', ['label' => '', 'widgetOptions' => ['htmlOptions' => ['checked' => $isRmEmail]], 'inline' => true]); ?></td>
									<td>  <?= $form->checkboxGroup($AgentMessages, 'agt_rm_sms[' . $key . ']', ['label' => '', 'widgetOptions' => ['htmlOptions' => ['checked' => $isRmSMS]], 'inline' => true]); ?></td>
									<td>  <?= $form->checkboxGroup($AgentMessages, 'agt_rm_app[' . $key . ']', ['label' => '', 'widgetOptions' => ['htmlOptions' => ['checked' => $isRmApp]], 'inline' => true]); ?></td>
									<td>  <?= $form->checkboxGroup($AgentMessages, 'agt_rm_whatsapp[' . $key . ']', ['label' => '', 'widgetOptions' => ['htmlOptions' => ['checked' => $isRmWhatsApp]], 'inline' => true]); ?></td>
								</tr>   
								<?
							}
							?>
                        </tbody>
                    </table>
                </div>      
            </div>           
        </div>

        <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
            <div class="panel panel-white panel-border">
                <div class="panel-heading">                            
                    CONTACT DETAILS
                </div>
                <div class="panel-body">
                    <div class="col-xs-12">
                        <u>Address (Office)</u>
                        <div class="row"> 
                            <div class="col-sm-12">
                                <label for="inputEmail3" class="control-label">Address</label>
								<?= $form->textAreaGroup($model, 'agt_address', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Address')))) ?>  
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <label for="inputEmail3" class="control-label">State</label>
								<?= $form->textFieldGroup($model, 'agt_state', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter State')))) ?>  
                            </div>

                            <div class="col-sm-6">
                                <label for="inputEmail3" class="control-label text-right">Pin Code</label>
								<?= $form->textFieldGroup($model, 'agt_zip', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Pin Code')))) ?>  
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-sm-4">
                                <Label>Country Code *</Label>
								<?php
								$this->widget('ext.yii-selectize.YiiSelectize', array(
									'model'				 => $model,
									'attribute'			 => 'agt_phone_country_code',
									'useWithBootstrap'	 => true,
									"placeholder"		 => "Code",
									'fullWidth'			 => false,
									'htmlOptions'		 => array('width' => '50%'
									),
									'defaultOptions'	 => array(
										'create'			 => false,
										'persist'			 => true,
										'selectOnTab'		 => true,
										'createOnBlur'		 => true,
										'dropdownParent'	 => 'body',
										'optgroupValueField' => 'id',
										'optgroupLabelField' => 'pcode',
										'optgroupField'		 => 'pcode',
										'openOnFocus'		 => true,
										'labelField'		 => 'pcode',
										'valueField'		 => 'pcode',
										'searchField'		 => 'name',
										//   'sortField' => 'js:[{field:"order",direction:"asc"}]',
										'closeAfterSelect'	 => true,
										'addPrecedence'		 => false,
										'onInitialize'		 => "js:function(){
                                            this.load(function(callback){
                                            var obj=this;                                
                                             xhr=$.ajax({
                                     url:'" . CHtml::normalizeUrl(Yii::app()->createUrl('index/country')) . "',
                                     dataType:'json',                  
                                     success:function(results){
                                         obj.enable();
                                         callback(results.data);
                                         obj.setValue('{$model->agt_phone_country_code}');
                                     },                    
                                     error:function(){
                                         callback();
                                     }});
                                    });
                                    }",
										'render'			 => "js:{
                                         option: function(item, escape){                      
                                                 return '<div><span class=\"\">' + escape(item.name) +'</span></div>';                          
                                         },
                                         option_create: function(data, escape){
                                              return '<div>' +'<span class=\"\">' + escape(data.pcode) + '</span></div>';
                                        }
                                    }",
									),
								));
								?>
                                <span style="color: #F00"> <?= $form->error($model, 'agt_phone_country_code') ?></span>
                            </div>

                            <div class="col-sm-4">
                                <label for="inputEmail3" class="control-label">Phone No.</label>
								<?= $form->textFieldGroup($model, 'agt_phone', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Phone No.')))) ?>  
                            </div>

                            <div class="col-sm-4">
                                <label for="inputEmail3" class="control-label text-right">Fax No.</label>
								<?= $form->textFieldGroup($model, 'agt_fax', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Fax No.')))) ?>  
                            </div>

                            <div class="col-sm-4">
                                <label for="inputEmail3" class="control-label text-right">Email Id</label>
								<?
								$hideemail = [];
								if (!$model->isNewRecord)
								{
									$hideemail = ['readOnly' => 'readOnly'];
								}
								?>
								<?= $form->emailFieldGroup($model, 'agt_email', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Email') + $hideemail))) ?>  
                            </div>
                        </div>
                        <u>Address (Alternate)</u>
                        <div class="row">
                            <div class="col-sm-12">
                                <label for="inputEmail3" class="control-label">Address</label>
								<?= $form->textAreaGroup($model, 'agt_address_alt', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Address')))) ?>  
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <label for="inputEmail3" class="control-label">State</label>
								<?= $form->textFieldGroup($model, 'agt_alt_state', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter State')))) ?>  
                            </div>
                            <div class="col-sm-6">
                                <label for="inputEmail3" class="control-label text-right">Pin Code</label>
								<?= $form->textFieldGroup($model, 'agt_alt_zip', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter PIN')))) ?>  
                            </div>
                        </div>
                        <u>Official Contact Person (s)</u>
                        (used for all official communication)
                        <div class="row">
                            <div class="col-sm-1">
                                <h3>1.</h3>
                            </div>
                            <div class="col-sm-11">
                                <div class="col-sm-4">
                                    <label for="inputEmail3" class="control-label">Name</label>
									<?= $form->textFieldGroup($model, 'agt_other_con_name_one', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter name')))) ?>  
                                </div>
                                <div class="col-sm-4">
                                    <label for="inputEmail3" class="control-label">Phone No.</label>
									<?= $form->textFieldGroup($model, 'agt_other_con_phone_one', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter phone')))) ?>  
                                </div>
                                <!--                                            <div class="row form-group">
                                                                                <label for="inputEmail3" class="control-label">Mobile No.</label>
                                                                                <div class="col-sm-9">
                                                                                    <input type="email" class="form-control" id="inputEmail3" placeholder="Enter mobile no.">
                                                                                </div>
                                                                            </div>-->
                                <div class="col-sm-4">
                                    <label for="inputEmail3" class="control-label">Email Address</label>
									<?= $form->emailFieldGroup($model, 'agt_other_con_email_one', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Email')))) ?>  
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-1">
                                <h3>2.</h3>
                            </div>
                            <div class="col-sm-11">
                                <div class="col-sm-4">
                                    <label for="inputEmail3" class="control-label">Name</label>
									<?= $form->textFieldGroup($model, 'agt_other_con_name_two', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter name')))) ?>  
                                </div>
                                <div class="col-sm-4">
                                    <label for="inputEmail3" class="control-label">Phone No.</label>
									<?= $form->textFieldGroup($model, 'agt_other_con_phone_two', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter phone')))) ?>  
                                </div>
                                <!--                                            <div class="row form-group">
                                                                                <label for="inputEmail3" class="control-label">Mobile No.</label>
                                                                                <div class="col-sm-9">
                                                                                    <input type="email" class="form-control" id="inputEmail3" placeholder="Enter mobile no.">
                                                                                </div>
                                                                            </div>-->
                                <div class="col-sm-4">
                                    <label for="inputEmail3" class="control-label">Email Address</label>
									<?= $form->emailFieldGroup($model, 'agt_other_con_email_two', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Email')))) ?>  
                                </div>
                            </div>
                        </div>

                        <u>Accounts contact</u>
                        <div class="row">
                            <div class="col-sm-6">
                                <label for="inputEmail3" class="control-label">Name</label>
								<?= $form->textFieldGroup($model, 'agt_acc_contact_name', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Name')))) ?>  
                            </div>

                            <div class="col-sm-6">
                                <label for="inputEmail3" class="control-label">Phone No.</label>
								<?= $form->textFieldGroup($model, 'agt_acc_contact_phone', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Phone')))) ?>  
                            </div>

                            <div class="col-sm-6">
                                <label for="inputEmail3" class="control-label">Mobile No.</label>
								<?= $form->textFieldGroup($model, 'agt_acc_contact_mobile', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Mobile')))) ?>  
                            </div>

                            <div class="col-sm-6">
                                <label for="inputEmail3" class="control-label">Email Address</label>
								<?= $form->emailFieldGroup($model, 'agt_acc_contact_email', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Email')))) ?>  
                            </div>
                        </div>

                        <u>Option for account opening deposit</u>
                        <div class="row">
							<?= $form->textFieldGroup($model, 'agt_opening_deposit', array('label' => "Account opening deposit", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array()))) ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-white panel-border">
                <div class="panel-heading">                            
                    ADDITIONAL DETAILS
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <label for="inputEmail3" class="control-label">Number of Travelling employees</label>
							<?= $form->numberFieldGroup($model, 'agt_tot_trvl_employee', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Number of travelling employees', 'min' => 0)))) ?>
                        </div>
                        <div class="col-sm-6">
                            <label for="inputEmail3" class="control-label text-right">Expected billing/month</label>
							<?= $form->textFieldGroup($model, 'agt_expected_trvl_month', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter expected billing month')))) ?>  
                        </div>
                        <div class="col-sm-6"> 
                            <label for="inputEmail3" class="control-label text-right">Annual Turnover (provide a range)</label>
							<?= $form->numberFieldGroup($model, 'agt_anual_turnover', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter annual turnover', 'min' => 0)))) ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12"><u>Regions where services are expected</u></div>
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="p0">
                                    <div class="col-sm-12 mb10">
                                        <div class="col-sm-4">
                                            <label for="inputEmail3" class="control-label text-right">1</label>
											<?= $form->textFieldGroup($model, 'agt_expected_region1', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter expected region')))) ?>  
                                        </div>
                                        <div class="col-sm-4">
                                            <label for="inputEmail3" class="control-label text-right">2</label>
											<?= $form->textFieldGroup($model, 'agt_expected_region2', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter expected region')))) ?>  
                                        </div>
                                        <div class="col-sm-4">
                                            <label for="inputEmail3" class="control-label text-right">3</label>
											<?= $form->textFieldGroup($model, 'agt_expected_region3', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter expected region')))) ?>  
                                        </div>
                                    </div>
                                    <div class="col-sm-12 mb10">
                                        <div class="col-sm-4">
                                            <label for="inputEmail3" class="control-label text-right">4</label>
											<?= $form->textFieldGroup($model, 'agt_expected_region4', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter expected region')))) ?>  
                                        </div>
                                        <div class="col-sm-4">
                                            <label for="inputEmail3" class="control-label text-right">5</label>
											<?= $form->textFieldGroup($model, 'agt_expected_region5', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter expected region')))) ?>  
                                        </div>
                                        <div class="col-sm-4">
                                            <label for="inputEmail3" class="control-label text-right">6</label>
											<?= $form->textFieldGroup($model, 'agt_expected_region6', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter expected region')))) ?>  
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12"><u>Invoicing details</u></div>
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-3 p0">
									<?
									$isInvByBooking		 = ($model->agt_invoiceopt_booking == 1) ? true : false;
									?>
									<?= $form->checkboxListGroup($model, 'agt_invoiceopt_booking', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Invoice by booking'), 'htmlOptions' => ['checked' => $isInvByBooking]), 'inline' => true)) ?>
                                </div>
                                <div class="col-sm-3 p0">
									<?
									$isInvByMonthly		 = ($model->agt_invoiceopt_monthly == 1) ? true : false;
									?>
									<?= $form->checkboxListGroup($model, 'agt_invoiceopt_monthly', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Invoice monthly'), 'htmlOptions' => ['checked' => $isInvByMonthly]), 'inline' => true)) ?>

                                </div>
                                <div class="col-sm-3 p0">
									<?
									$isInvByPrepaid		 = ($model->agt_invoiceopt_prepaid == 1) ? true : false;
									?>
									<?= $form->checkboxListGroup($model, 'agt_invoiceopt_prepaid', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Pre-paid (Advance payment required)'), 'htmlOptions' => ['checked' => $isInvByPrepaid]), 'inline' => true)) ?>

                                </div>
                                <div class="col-sm-3 p0">
									<?
									$isInvByTraveller	 = ($model->agt_invoiceopt_traveller == 1) ? true : false;
									?>
									<?= $form->checkboxListGroup($model, 'agt_invoiceopt_traveller', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Collect from traveller'), 'htmlOptions' => ['checked' => $isInvByTraveller]), 'inline' => true)) ?>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt20 mb20">
                        <div class="col-xs-6">
                            <label for="inputEmail3" class="control-label">Other</label>
							<?= $form->textFieldGroup($model, 'agt_invoiceopt_other', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter other invoice option')))) ?>  
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-white panel-border">
                <div class="panel-heading">FOR GOZO TECHNOLOGIES - OFFICE USE ONLY</div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="col-xs-3">
								<?
								$isDepositRecieved	 = ($model->agt_is_depo_received == 1) ? true : false;
								?>
								<?= $form->checkboxListGroup($model, 'agt_is_depo_received', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Deposit Received'), 'htmlOptions' => ['checked' => $isDepositRecieved,]), 'inline' => true)) ?>
                            </div>
                            <div class="col-xs-3">
                                Rs. <?= $form->textFieldGroup($model, 'agt_depo_amount', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => 'Enter Amount']))); ?>
                            </div> 
                            <div class="col-xs-3">
                                Confirmed by: <?= $form->textFieldGroup($model, 'agt_depo_confirmed_by', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => 'Enter Name']))); ?>
                            </div>
                            <div class="col-xs-3">
                                Date :  <?= $form->datePickerGroup($model, 'agt_depo_date', array('label' => '', 'widgetOptions' => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'Date')), 'prepend' => '<i class="fa fa-calendar"></i>')); ?>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="col-xs-6">
								<?
								$isCorporateCreated	 = ($model->agt_iscorporate_created == 1) ? true : false;
								?>
								<?= $form->checkboxListGroup($model, 'agt_iscorporate_created', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Corporate account created and authorized user ids emailed to'), 'htmlOptions' => ['checked' => $isCorporateCreated]), 'inline' => true)) ?>
                            </div>
                            <div class="col-xs-6">
								<?= $form->textFieldGroup($model, 'agt_comm_email', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => 'Enter Email']))); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>   
        </div>

        <div class="row">
            <div class="col-xs-12 text-center">
                <button type="submit" class="btn btn-primary pl40 pr40 btn-lg">Register</button>
            </div>
        </div>
		<?php $this->endWidget(); ?>      
    </div>
</div>
<script>
	function addUsers() {
		$('#adduserstr').before('<tr>' + $('#adduserstr').html() + '</tr>');
		return false;
	}

</script>




