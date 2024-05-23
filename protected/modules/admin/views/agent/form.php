<?php
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/jquery.mask.min.js');
Yii::app()->clientScript->registerCssFile(ASSETS_URL . '/plugins/form-select2/select2.css');
?>
<style type="text/css">
    .table_new table{ width: 99%;}
    .selectize-input {
        min-width: 0px !important;
        width: 30% !important;
        /*  background: #de6a1e !important;
         color: #ffffff !important; */
    }
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; 
        margin: 0; 
    }
    input[type="number"] {
        -moz-appearance: textfield;
    }
    .form-horizontal .form-group{ margin-left: 0; margin-right: 0;margin-bottom: 5px;}
</style>
<div class="container">
	<?php
	$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
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
    <div class="row">
		<?php // echo CHtml::errorSummary($model);  ?>

        <div class="col-md-7">
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-default panel-border">
                        <h3 class="pl15 pb10">Personal Information</h3>
                        <div class="panel-body pt0">
							<?
							if ($model->agt_id > 0)
							{
								?>
								<div class="row bg-light p5"> <div class=" pull-left"><span class="h5">   PARTNER ID :  <?= $model->agt_agent_id; ?></span> (<?= $model->getAgentType($model->agt_type); ?>)</div></div>
							<? } ?>
                            <div class="row">

								<?
								if (!$model->isNewRecord)
								{
									echo $form->hiddenField($model, 'agt_id', []);
								}
								?>
                                <div class="col-sm-6">
									<?= $form->textFieldGroup($model, 'agt_company', array('label' => "Company name *", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Company Name')))) ?>  
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label" for="exampleInputName6">Select Company type</label>
										<?php
										$compTypesArr	 = VehicleTypes::model()->getJSON([1 => 'Sole Proprietorship', 2 => 'Partnership', 3 => 'Private Limited', 4 => 'Public Limited']);
										$this->widget('booster.widgets.TbSelect2', array(
											'model'			 => $model,
											'attribute'		 => 'agt_company_type',
											'val'			 => $model->agt_company_type,
											'asDropDownList' => FALSE,
											'options'		 => array('data' => new CJavaScriptExpression($compTypesArr)),
											'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Company Type', 'id' => 'Agents_agt_company_type')
										));
										?>
                                    </div>
                                </div>
                            </div>
                            <div class="row" >
                                <div class="col-sm-6" id="div_owner_name">
									<?= $form->textFieldGroup($model, 'agt_owner_name', array('label' => "Proprietor/Director name *", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Owner Name')))) ?> 
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label" for="exampleInputName6">Gozo Account Manager</label>
										<?php
										$AccManagerArr	 = VehicleTypes::model()->getJSON(Admins::model()->findNameList());
										$this->widget('booster.widgets.TbSelect2', array(
											'model'			 => $model,
											'attribute'		 => 'agt_admin_id',
											'val'			 => $model->agt_admin_id,
											'asDropDownList' => FALSE,
											'options'		 => array('data' => new CJavaScriptExpression($AccManagerArr)),
											'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Account Manager', 'id' => 'Agents_agt_admin_id')
										));
										?>
                                    </div>
                                </div>
                            </div>  
                            <div class="row">
                                <div class="col-sm-6">
									<?= $form->textFieldGroup($model, 'agt_fname', array('label' => "First name *", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter First Name')))) ?> 
                                </div>
                                <div class="col-sm-6">
									<?= $form->textFieldGroup($model, 'agt_lname', array('label' => "Last name *", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Last Name')))) ?> 
                                </div>
                            </div>    

							<?
							if ($model->isNewRecord)
							{
								$model->agt_commission_value = Yii::app()->params['agentDefCommissionValue'];
								$model->agt_commission		 = Yii::app()->params['agentDefCommission'];
							}
							else
							{
								$readOnlyComm = ['readOnly' => 'readOnly'];
							}
							?>
                            <div class="row">
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
										<label class="control-label" for="exampleInputName6">Commission Amount</label>
									<? } ?>
									<?= $form->textFieldGroup($model, 'agt_commission', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Commission Amount')))) ?>
                                </div> 
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
									<?= $form->textFieldGroup($model, 'agt_opening_deposit', array('label' => "Account opening deposit", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('readOnly' => 'readOnly')))) ?>
                                </div>
                                <div class="col-sm-6">
									<?= $form->numberFieldGroup($model, 'agt_credit_limit', array('label' => "Credit Limit", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter credit limit for agent', 'min' => 0, 'readOnly' => 'readOnly')))) ?>
                                </div> 
                            </div>

                            <div class="row">
                                <div class="col-sm-12"><u>Invoicing details</u></div>
                                <div class="col-sm-12">
                                    <div class="row">
                                        <div class="col-sm-3 p0">
											<?
											$isInvByBooking						 = ($model->agt_invoiceopt_booking == 1) ? true : false;
											?>
											<?= $form->checkboxListGroup($model, 'agt_invoiceopt_booking', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Invoice by booking'), 'htmlOptions' => ['checked' => $isInvByBooking]), 'inline' => true)) ?>
                                        </div>
                                        <div class="col-sm-3 p0">
											<?
											$isInvByMonthly						 = ($model->agt_invoiceopt_monthly == 1) ? true : false;
											?>
											<?= $form->checkboxListGroup($model, 'agt_invoiceopt_monthly', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Invoice monthly'), 'htmlOptions' => ['checked' => $isInvByMonthly]), 'inline' => true)) ?>

                                        </div>
                                        <div class="col-sm-3 p0">
											<?
											$isInvByPrepaid						 = ($model->agt_invoiceopt_prepaid == 1) ? true : false;
											?>
											<?= $form->checkboxListGroup($model, 'agt_invoiceopt_prepaid', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Pre-paid (Advance payment required)'), 'htmlOptions' => ['checked' => $isInvByPrepaid]), 'inline' => true)) ?>

                                        </div>
                                        <div class="col-sm-3 p0">
											<?
											$isInvByTraveller					 = ($model->agt_invoiceopt_traveller == 1) ? true : false;
											?>
											<?= $form->checkboxListGroup($model, 'agt_invoiceopt_traveller', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Collect from traveller'), 'htmlOptions' => ['checked' => $isInvByTraveller]), 'inline' => true)) ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
								<div class="col-xs-6">
									<?= $form->textFieldGroup($model, 'agt_gstin', array('label' => "GST Identification Number", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter GSTIN Number')))) ?>
								</div>
								<div class="col-sm-6">
									<?= $form->textFieldGroup($model, 'agt_pan_number', array('label' => "PAN Number", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter PAN number')))) ?>
                                </div>
							</div>
                            <div class="row">
                                <div class="col-sm-6">
									<?= $form->textFieldGroup($model, 'agt_driver_license', array('label' => "Driver license", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter driver license number')))) ?>
                                </div> 
                                <div class="col-sm-6">
									<?= $form->textFieldGroup($model, 'agt_trade_license', array('label' => "Trade license # (if any)", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter trade license number')))) ?>
                                </div> 
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
									<?= $form->datePickerGroup($model, 'agt_license_expiry_date', array('label' => 'Driver license Expiry Date ', 'widgetOptions' => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'Expiry date', 'value' => DateTimeFormat::DateTimeToDatePicker($model->agt_license_expiry_date))), 'prepend' => '<i class="fa fa-calendar"></i>'));
									?>
                                </div>  
                                <div class="col-sm-6">
									<? //= $form->textFieldGroup($model, 'agt_license_issued_state', array('label' => "Driver license issued by state", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter state name')))) ?>
									<label for="Agents_agt_license_issued_state">Driver license issued by state</label>
									<?
									$this->widget('ext.yii-selectize.YiiSelectize', array(
										'model'				 => $model,
										'attribute'			 => 'agt_license_issued_state',
										'useWithBootstrap'	 => true,
										"placeholder"		 => "State",
										'fullWidth'			 => true,
										'htmlOptions'		 => array(
										),
										'defaultOptions'	 => array(
											'create'			 => false,
											'persist'			 => false,
											'selectOnTab'		 => true,
											'createOnBlur'		 => true,
											'dropdownParent'	 => 'body',
											'optgroupValueField' => 'id',
											'optgroupLabelField' => 'id',
											'optgroupField'		 => 'id',
											'openOnFocus'		 => true,
											'labelField'		 => 'text',
											'valueField'		 => 'id',
											'searchField'		 => 'text',
											'closeAfterSelect'	 => true,
											'addPrecedence'		 => false,
											'onInitialize'		 => "js:function(){
                                                                                            this.load(function(callback){
                                                                                            var obj=this;    
                                                                                            xhr=$.ajax({
                                                                                                url:'" . CHtml::normalizeUrl(Yii::app()->createUrl('users/countrytostate', ['countryid' => 99])) . "',
                                                                                                dataType:'json',                  
                                                                                                success:function(results){
                                                                                                    obj.enable();
                                                                                                    callback(results);
                                                                                                     $('#Agents_agt_license_issued_state')[0].selectize.setValue({$model->agt_license_issued_state});
                                                                                                },                    
                                                                                                error:function(){
                                                                                                    callback();
                                                                                                    }});
                                                                                                });
                                                                                            }",
											'render'			 => "js:{
                                                                                   option: function(item, escape){
                                                                                                return '<div><span class=\"\">' + escape(item.text) +'</span></div>';
                                                                                    },
				                                                   option_create: function(data, escape){
                                                                                                 $('#countryname').val(escape(data.id));
                                                                                                 return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
                                                                                   }
                                                                                }",
										),
									));
									?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
									<?= $form->textFieldGroup($model, 'agt_aadhar_id', array('label' => "Aadhar ID", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter aadhar ID')))) ?>
                                </div>  
                                <div class="col-sm-6">
									<?= $form->textFieldGroup($model, 'agt_voter_id', array('label' => "Voter ID", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter voter ID')))) ?>
                                </div>
                            </div>
							<div class="row">
                                <div class="col-sm-6">
									<?= $form->textFieldGroup($model, 'agt_payable_percentage', array('label' => "Percentage of Amount collect from agent", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Percentage of Amount collect from agent')))) ?>
                                </div>  

                            </div>
							<div class="row">
                                <div class="col-sm-12">Smartmatch</div>
                                <div class="col-sm-12">
                                    <div class="row">
                                        <div class="col-sm-3 p0">
											<?php
											$isSmartmatch						 = ($model->agt_allow_smartmatch == 1) ? true : false;
											?>
											<?= $form->checkboxListGroup($model, 'agt_allow_smartmatch', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Allow Smartmatch'), 'htmlOptions' => ['checked' => $isSmartmatch]), 'inline' => true)) ?>
                                        </div>

                                        <div class="col-sm-6 p0">
											<?php
											$model->agt_block_autoassign_flag	 = ($model->agt_vendor_autoassign_flag == 1) ? 0 : 1;
											?>
											<?= $form->checkboxListGroup($model, 'agt_block_autoassign_flag', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Block autoassignment'), 'htmlOptions' => []), 'inline' => true)) ?>
                                        </div>

										<div class="col-sm-3 p0">
											<?php
											$model->agt_payment_lock			 = ($PartnerSettingModel->pts_is_payment_lock == 0) ? 0 : 1;
											?>
											<?= $form->checkboxListGroup($model, 'agt_payment_lock', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Payment Lock'), 'htmlOptions' => []), 'inline' => true)) ?>
                                        </div>

										<div class="col-sm-6 p0">
											<?php
											$model->agt_extra_comm_display		 = ($PartnerSettingModel->pts_extra_comm_display == 0) ? 0 : 1;
											?>
											<?= $form->checkboxListGroup($model, 'agt_extra_comm_display', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Extra Commission display'), 'htmlOptions' => []), 'inline' => true)) ?>
                                        </div>

                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="panel panel-default panel-border">
                    <h3 class="pl15">Notification defaults</h3>
                    <div class="panel-body pt0">
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
							$AccManagerArr1						 = VehicleTypes::model()->getJSON(Admins::model()->findNameList());
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
						<?= $form->radioButtonListGroup($model, 'agt_trvl_sendupdate', array('label' => '', 'widgetOptions' => array('htmlOptions' => [], 'data' => [1 => 'Yes', 2 => 'No']), 'inline' => true)) ?>

						<? $model->agt_chk_others				 = ($model->agt_pref_req_other != '') ? 1 : 0; ?>
						<? $checkedslip						 = ($model->agt_duty_slip_required == 1) ? "'checked'=>'checked'" : ''; ?>
						<? $checkedapp							 = ($model->agt_driver_app_required == 1) ? "'checked'=>'checked'" : ''; ?>
						<? //$checkedotp			 = ($model->agt_otp_required == 1) ? "'checked'=>'checked'" : ''; ?>
						<? $checkedwater						 = ($model->agt_water_bottles_required == 1) ? "'checked'=>'checked'" : ''; ?>
						<? $checkedcash						 = ($model->agt_is_cash_required == 1) ? "'checked'=>'checked'" : ''; ?>

						<div class="row">
							<h3 class="pl15">Partner Preferences</h3>
							<div class="col-sm-6 p0">
								<?= $form->checkboxGroup($model, 'agt_duty_slip_required', ['label' => 'All receipts & duty slips required', 'widgetOptions' => array('htmlOptions' => [$checkedslip])]) ?>
							</div>
							<div class="col-sm-6 p0">
								<?= $form->checkboxGroup($model, 'agt_driver_app_required', ['label' => 'Use of Driver app is required', 'widgetOptions' => array('htmlOptions' => [$checkedapp])]) ?>
							</div>
							<div class="col-sm-6 p0">
								<? //= $form->checkboxGroup($model, 'agt_otp_required', ['label' => 'OTP is required', 'widgetOptions' => array('htmlOptions' => [$checkedotp])]) ?>

								<?php
								$model->agt_otp_not_required		 = ($model->agt_otp_required == 1) ? 0 : 1;
								?>
								<?= $form->checkboxListGroup($model, 'agt_otp_not_required', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'OTP not required from customer. Use Driver app to start, stop trip'), 'htmlOptions' => []), 'inline' => true)) ?>

							</div>
							<div class="col-sm-6 p0">
								<?= $form->checkboxGroup($model, 'agt_water_bottles_required', ['label' => '2x 500ml water bottles required', 'widgetOptions' => array('htmlOptions' => [$checkedwater])]) ?>
							</div>
							<div class="col-sm-6 p0">

								<?= $form->checkboxGroup($model, 'agt_is_cash_required', ['label' => 'Do not ask customer for cash', 'widgetOptions' => array('htmlOptions' => [$checkedcash])]) ?>

							</div>
							<div class="col-sm-6 p0">

								<?= $form->checkboxGroup($model, 'agt_chk_others', ['label' => 'Other', 'widgetOptions' => array('htmlOptions' => [])]) ?>
								<div id="othreq" style="display: block; margin-left: 20px;">
									<?= $form->textAreaGroup($model, 'agt_pref_req_other', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Other Requests"]), 'groupOptions' => ['class' => 'm0'])) ?>  
								</div>
							</div>
						</div>   


					</div>
                </div>
            </div>



        </div>
        <div class="col-md-5">
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-default panel-border">
                        <h3 class="pl15">Bank Details</h3>
                        <div class="panel-body pt0">
                            <div class="row">
                                <div class="col-sm-12">
									<?= $form->textFieldGroup($model, 'agt_bank', array('label' => "Bank name", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter bank name')))) ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
									<?= $form->textFieldGroup($model, 'agt_bank_account', array('label' => "Bank account", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter bank account uumber')))) ?>
                                </div>  
                                <div class="col-sm-6">
									<?= $form->textFieldGroup($model, 'agt_branch_name', array('label' => "Branch name", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter branch name')))) ?>
                                </div>
                            </div>
                            <div class="row">
								<!--                                <div class="col-sm-6">-->
								<? //= $form->textFieldGroup($model, 'agt_swift_code', array('label' => "Swift code", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter swift code'))))  ?>
								<!--                                </div>  -->
                                <div class="col-sm-6">
									<?= $form->textFieldGroup($model, 'agt_ifsc_code', array('label' => "IFSC code", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter IFSC code')))) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-white panel-border">
                        <div class="panel-heading">
                            <span class="pull-left">Upload Documents</span>                  
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-12">
									<?= $form->fileFieldGroup($model, 'agt_owner_photo', array('label' => 'Owner photo', 'widgetOptions' => array())); ?>
									<?
									if ($model->agt_owner_photo != '')
									{
										?>
										<a href="<?= $model->agt_owner_photo ?>" target="_blank"><?= basename($model->agt_owner_photo) ?></a>
									<? } ?>
                                </div>
                                <div class="col-sm-12">
									<?= $form->fileFieldGroup($model, 'agt_pan_card', array('label' => 'Scanned copy of PAN card', 'widgetOptions' => array())); ?>
									<?
									if ($model->agt_pan_card != '')
									{
										?>
										<a href="<?= $model->agt_pan_card ?>" target="_blank"><?= basename($model->agt_pan_card) ?></a>
									<? } ?>
                                </div>
                                <div class="col-sm-12">
									<?= $form->fileFieldGroup($model, 'agt_aadhar', array('label' => 'Scanned copy of aadhar card', 'widgetOptions' => array())); ?>
									<?
									if ($model->agt_aadhar != '')
									{
										?>
										<a href="<?= $model->agt_aadhar ?>" target="_blank"><?= basename($model->agt_aadhar) ?></a>
									<? } ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
									<?= $form->fileFieldGroup($AgentRel, 'arl_voter_id_path', array('label' => 'Scanned copy of voter card', 'widgetOptions' => array())); ?>
									<?
									if ($AgentRel->arl_voter_id_path != '')
									{
										?>
										<a href="<?= $AgentRel->arl_voter_id_path ?>" target="_blank"><?= basename($AgentRel->arl_voter_id_path) ?></a>
									<? } ?>
                                </div>
                                <div class="col-sm-12">
									<?= $form->fileFieldGroup($model, 'agt_company_add_proof', array('label' => 'Scanned copy of company address proof', 'widgetOptions' => array())); ?>
									<?
									if ($model->agt_company_add_proof != '')
									{
										?>
										<a href="<?= $model->agt_company_add_proof ?>" target="_blank"><?= basename($model->agt_company_add_proof) ?></a>
									<? } ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
									<?= $form->fileFieldGroup($AgentRel, 'arl_driver_license_path', array('label' => 'Scanned copy of driver license', 'widgetOptions' => array())); ?>
									<?
									if ($AgentRel->arl_driver_license_path != '')
									{
										?>
										<a href="<?= $AgentRel->arl_driver_license_path ?>" target="_blank"><?= basename($AgentRel->arl_driver_license_path) ?></a>
									<? } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-default panel-border">
                        <h3 class="pl15">Office use only</h3>
                        <div class="panel-body pt0">

                            <!--                                <div class="col-sm-6">
                                                                <div class="form-group">
                                                                    <label class="control-label" for="exampleInputName6">Agent ID</label>
							<? //= $form->textFieldGroup($model, 'agt_id', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Agent ID', 'readOnly'=>'readOnly'))))       ?>
                                                                </div>
                                                            </div>-->
							<?php
							if ($model->agt_referral_code != '')
							{
								?>
								<div class="row">
									<div class="col-sm-12">
										<?= $form->textFieldGroup($model, 'agt_referral_code', array('label' => "Partner Referral Code", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Referral Code ', 'readOnly' => 'readOnly')))) ?>
									</div>
								</div>
							<?php } ?>
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <label class="control-label" for="exampleInputCompany6"><b>Partner  papers received</b> </label>
                                    </div>
									<?
									if ($model->agt_is_owner_photo == 1)
									{
										$is_attached = true;
									}
									else
									{
										$is_attached = false;
									}
									?>
                                    <div class="col-sm-6">
										<?= $form->checkboxListGroup($model, 'agt_is_owner_photo', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Owner photo'), 'htmlOptions' => ['checked' => $is_attached]))) ?>
                                    </div>
									<?
									if ($model->agt_is_license_pic == 1)
									{
										$is_license = true;
									}
									else
									{
										$is_license = false;
									}
									?> 
                                    <div class="col-sm-6">
										<?= $form->checkboxListGroup($model, 'agt_is_license_pic', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Owner license picture'), 'htmlOptions' => ['checked' => $is_license]))) ?>
                                    </div>
									<?
									if ($model->agt_is_owner_aadharcard == 1)
									{
										$is_aadhar = true;
									}
									else
									{
										$is_aadhar = false;
									}
									?>  
                                    <div class="col-sm-6">
										<?= $form->checkboxListGroup($model, 'agt_is_owner_aadharcard', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Owner Aadhar card'), 'htmlOptions' => ['checked' => $is_aadhar]))) ?>
                                    </div>
									<?
									if ($model->agt_is_voter_id == 1)
									{
										$is_voter = true;
									}
									else
									{
										$is_voter = false;
									}
									?>  
                                    <div class="col-sm-6">
										<?= $form->checkboxListGroup($model, 'agt_is_voter_id', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Owner Voter card'), 'htmlOptions' => ['checked' => $is_voter]))) ?>
                                    </div>
									<?
									if ($model->agt_is_owner_pancard == 1)
									{
										$is_pan = true;
									}
									else
									{
										$is_pan = false;
									}
									?>  
                                    <div class="col-sm-6">
										<?= $form->checkboxListGroup($model, 'agt_is_owner_pancard', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Owner PAN card'), 'htmlOptions' => ['checked' => $is_pan]))) ?>
                                    </div>
									<?
									if ($model->agt_is_bussiness_registration == 1)
									{
										$is_registration = true;
									}
									else
									{
										$is_registration = false;
									}
									?>  
                                    <div class="col-sm-6">
										<?= $form->checkboxListGroup($model, 'agt_is_bussiness_registration', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Business reg. certificate'), 'htmlOptions' => ['checked' => $is_registration]))) ?>
                                    </div>
									<?
									if ($model->agt_is_ccin == 1)
									{
										$is_ccin = true;
									}
									else
									{
										$is_ccin = false;
									}
									?>  
                                    <div class="col-sm-6">
										<?= $form->checkboxListGroup($model, 'agt_is_ccin', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Owner CCIN'), 'htmlOptions' => ['checked' => $is_ccin]))) ?>
                                    </div>
									<?
									if ($model->agt_is_agreement == 1)
									{
										$is_agreement = true;
									}
									else
									{
										$is_agreement = false;
									}
									?>  
                                    <div class="col-sm-6">
										<?= $form->checkboxListGroup($model, 'agt_is_agreement', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Agreement'), 'htmlOptions' => ['checked' => $is_agreement]))) ?>
                                    </div>

									<?
									if ($model->agt_is_memorandum == 1)
									{
										$is_memorandum = true;
									}
									else
									{
										$is_memorandum = false;
									}
									?>  
                                    <div class="col-sm-6">
										<?= $form->checkboxListGroup($model, 'agt_is_memorandum', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Memorandum'), 'htmlOptions' => ['checked' => $is_memorandum]))) ?>
                                    </div>
                                </div>
                            </div>  
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-default panel-border">
                        <h3 class="pl15">Contact Information</h3>
                        <div class="panel-body pt0">
                            <div class="row">
                                <div class="col-sm-6">
									<?
									$hideAgent = [];
									if (!$model->isNewRecord)
									{
										$hideAgent = []; // ['readOnly' => 'readOnly'];
									}
									?>
									<?= $form->textFieldGroup($model, 'agt_email', array('label' => "Email 1 *", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter primary email') + $hideAgent))) ?>
                                </div>
                                <div class="col-sm-6">
									<?= $form->textFieldGroup($model, 'agt_email_two', array('label' => "Email 2 ", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter alternative email')))) ?>
                                </div>  
                            </div>
                            <div class="row">
                                <div class="col-sm-6 p0 m0">
                                    <div class="col-xs-5">
                                        <label class="control-label">Country Code*</label>
										<?php
										$model->agt_phone_country_code = ($model->agt_phone_country_code == '') ? '91' : $model->agt_phone_country_code;

										$this->widget('ext.yii-selectize.YiiSelectize', array(
											'model'				 => $model,
											'attribute'			 => 'agt_phone_country_code',
											'useWithBootstrap'	 => true,
											"placeholder"		 => "Code",
											'fullWidth'			 => false,
											'htmlOptions'		 => array(
											),
											'defaultOptions'	 => array(
												'create'			 => false,
												'persist'			 => false,
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
                                        <span class="has-error"><? echo $form->error($model, 'agt_phone_country_code'); ?></span>
                                    </div>  
                                    <div class="col-xs-7 m0">
										<?= $form->textFieldGroup($model, 'agt_phone', array('label' => "Phone1(mobile) *", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter primary phone number')))) ?>
                                    </div>
                                </div>
                                <div class="col-sm-6">
									<?= $form->textFieldGroup($model, 'agt_phone_two', array('label' => "Phone 2", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter alternative phone number')))) ?>
                                </div>  
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
									<?= $form->textFieldGroup($model, 'agt_phone_three', array('label' => "Phone 3", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter second alternative phone number')))) ?>
                                </div> 
                                <div class="col-sm-6">
									<?= $form->textFieldGroup($model, 'agt_fax', array('label' => "Fax Number", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Fax Number')))) ?>
                                </div> 
                                <div class="col-sm-6">
									<?php $cityModel	 = Cities::getName($model->agt_city); ?>
									<?= $form->textFieldGroup($model, 'agt_city', array('label' => "Partner city *", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Agent City', 'value' => $cityModel)))) ?>
                                </div>
                                <div class="col-sm-6">
									<?= $form->textFieldGroup($model, 'agt_location', array('label' => "Partner location (landmark etc)", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter your location')))) ?>
                                </div>   
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
									<?= $form->textAreaGroup($model, 'agt_address', array('label' => "Address ", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter address')))) ?>
                                </div>  
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="control-label" for="exampleInputName6">Other contact</label>
                                        <div class="col-xs-12 pl0 pr0 mb20 table-responsive table_new">
                                            <table class="table table-bordered" width="100%">
                                                <tr>
                                                    <td><b>Sl</b></td>
                                                    <td><b>Name</b></td>
                                                    <td><b>Phone</b></td>
                                                    <td><b>Email</b></td>
                                                </tr>
                                                <tr>
                                                    <td>1</td>
                                                    <td><?= $form->textFieldGroup($model, 'agt_other_con_name_one', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter name', 'style' => "width:80%; margin-left:15px;",)))) ?></td>
                                                    <td><?= $form->textFieldGroup($model, 'agt_other_con_phone_one', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter phone', 'style' => "width:80%; margin-left:15px;",)))) ?></td>
                                                    <td><?= $form->textFieldGroup($model, 'agt_other_con_email_one', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter email', 'style' => "width:80%; margin-left:15px;",)))) ?></td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td><?= $form->textFieldGroup($model, 'agt_other_con_name_two', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter name', 'style' => "width:80%; margin-left:15px;",)))) ?></td>
                                                    <td><?= $form->textFieldGroup($model, 'agt_other_con_phone_two', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter phone', 'style' => "width:80%; margin-left:15px;",)))) ?></td>
                                                    <td><?= $form->textFieldGroup($model, 'agt_other_con_email_two', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter email', 'style' => "width:80%; margin-left:15px;",)))) ?></td>
                                                </tr>
                                                <tr>
                                                    <td>3</td>
                                                    <td><?= $form->textFieldGroup($model, 'agt_other_con_name_three', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter name', 'style' => "width:80%; margin-left:15px;",)))) ?></td>
                                                    <td><?= $form->textFieldGroup($model, 'agt_other_con_phone_three', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter phone', 'style' => "width:80%; margin-left:15px;",)))) ?></td>
                                                    <td><?= $form->textFieldGroup($model, 'agt_other_con_email_three', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter email', 'style' => "width:80%; margin-left:15px;",)))) ?></td>
                                                </tr>
                                            </table>
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
    <div class="row">
		<div class="panel panel-default panel-border">
			<h3 class="pl15">Notification Options</h3>
			<div class="panel-body pt0">







				<table class="table table-bordered ">
					<tr>
						<th></th>
						<th colspan="4" style="text-align: center">Partner</th>
						<th colspan="4" style="text-align: center">Traveller</th>    
						<th colspan="4" style="text-align: center">Relationship Manager</th>   
					</tr>
					<tr>
						<td></td>
						<td>Email</td><td>SMS</td><td>App</td><td>WhatsApp</td>
						<td>Email</td><td>SMS</td><td>App</td><td>WhatsApp</td>
						<td>Email</td><td>SMS</td><td>App</td><td>WhatsApp</td>
					</tr>
					<?
					$arrEvents	 = AgentMessages::getEvents();
					foreach ($arrEvents as $key => $value)
					{
						$isAgentEmail	 = false;
						$isAgentSMS		 = false;
						$isAgentApp		 = false;
						$isAgentWhatsApp = false;

						$isTrvlEmail	 = false;
						$isTrvlSMS		 = false;
						$isTrvlApp		 = false;
						$isTrvlWhatsApp	 = false;

						$isRmEmail		 = false;
						$isRmSMS		 = false;
						$isRmApp		 = false;
						$isRmWhatsApp	 = false;

						$agtMsgModel = AgentMessages::model()->getByEventAndAgent($model->agt_id, $key);
						if ($model->isNewRecord)
						{
							$agtMsgModel = new AgentMessages();

							if ($key == AgentMessages::BOOKING_CONF_WITH_PAYMENTINFO || $key == AgentMessages::PAYMENT_CONFIRM || $key == AgentMessages::PAYMENT_FAILED || $key == AgentMessages::BOOKING_EDIT || $key == AgentMessages::CAB_ASSIGNED || $key == AgentMessages::INVOICE || $key == AgentMessages::RATING_AND_REVIEWS || $key == AgentMessages::RESCHEDULE_REQUEST || $key == AgentMessages::CAB_DRIVER_DETAIL || $key == AgentMessages::CANCEL_TRIP)
							{
								$agtMsgModel->agt_agent_email	 = 1;
								$agtMsgModel->agt_agent_sms		 = 1;
								$agtMsgModel->agt_agent_whatsapp		 = 1;
							}
							if ($key == AgentMessages::BOOKING_CONF_WITHOUT_PAYMENTINFO || $key == AgentMessages::CAB_ASSIGNED)
							{
								$agtMsgModel->agt_trvl_email = 1;
								$agtMsgModel->agt_trvl_sms	 = 1;
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
				</table>
			</div>      
		</div>           
	</div>
    <div class="row">
        <div class="col-xs-12 text-center pb10">
			<?= CHtml::button('Submit', array('class' => 'btn btn-primary pl30 pr30', 'onclick' => 'validateDocNumber()')); ?>
        </div>
    </div>
    <div id="driver1"></div>
	<?php $this->endWidget(); ?>
</div>
<script type="text/javascript">

	$(document).ready(function () {
		$("#Agents_agt_email").blur(function () {
			var agtEmail = $("#Agents_agt_email").val();
			if (agtEmail != '' && agtEmail != null && agtEmail != undefined) {
				$('#Agents_agt_copybooking_email').val(agtEmail);
			}
		});
		$("#Agents_agt_phone").blur(function () {
			var agtPhone = $("#Agents_agt_phone").val();
			if (agtPhone != '' && agtPhone != null && agtPhone != undefined) {
				$('#Agents_agt_copybooking_phone').val(agtPhone);
			}
		});
		$("#Agents_agt_owner_name").blur(function () {
			var agtName = $("#Agents_agt_owner_name").val();
			if (agtName != '' && agtName != null && agtName != undefined) {
				$('#Agents_agt_copybooking_name').val(agtName);
			}
		});
	});
	$("#Agents_agt_company_type").change(function () {
		//        if ($("#Agents_agt_company_type").val() == 3 || $("#Agents_agt_company_type").val() == 4)
		//        {
		//            $('#div_owner_name').removeClass('hide');
		//        }
		//        else
		//        {
		//            $('#div_owner_name').addClass('hide');
		//        }
	});
	$('form').on('focus', 'input[type=number]', function (e) {
		$(this).on('mousewheel.disableScroll', function (e) {
			e.preventDefault()
		})
		$(this).on("keydown", function (event) {
			if (event.keyCode === 38 || event.keyCode === 40) {
				event.preventDefault();
			}
		});
	});
	$('form').on('blur', 'input[type=number]', function (e) {
		$(this).off('mousewheel.disableScroll');
		$(this).off('keydown');
	});

	$('#<?= CHtml::activeId($model, "agt_chk_others") ?>').change(function ()
	{
		if ($('#<?= CHtml::activeId($model, "agt_chk_others") ?>').is(':checked'))
		{
			$("#othreq").show();
		} else
		{
			$("#othreq").hide();
		}
	});

	function validateDocNumber()
	{
		var pan = $("#Agents_agt_pan_number").val();
		var aadhar = $("#Agents_agt_aadhar_id").val();
		var license = $("#Agents_agt_driver_license").val();
		if (pan == '' && aadhar == '' && license == '') {
			bootbox.alert('Please enter Pan/Aadhar/License number');
			return false;
		} else {
			$("#driver-register-form").submit();
		}
	}

</script>