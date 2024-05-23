<style type="text/css">
    .form-group {
        margin-bottom: 7px;
        margin-top: 15px;

        margin-left: 0 !important;
        margin-right: 0 !important;
    }

    .form-horizontal .checkbox-inline{
        padding-top: 0;
    }
    #BookingCab_chk_user_msg{
        margin-left: 10px
    }
    .dlgComments .dijitDialogPaneContent{
        overflow: auto;
    }
    .selectize-input {
        min-width: 100px!important;
        width: 100%!important;      
    }
	.customer-panel {
		height: 100%;
		position: relative;

		background: #fff;
		border: #e6e6e6 1px solid!important;
		-webkit-border-radius: 2px;
		-moz-border-radius: 2px;
		border-radius: 2px;
		color: #3b3b3b;
	</style>
	<?php
	Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/js/jquery.mask.min.js');
	?>
	<?php
	if ($remainingSeats > 1)
	{
		echo "<span class='text-danger'>Can not assign cab and driver to this flexxi share booking as there are more than 1 seats empty.</span>";
	}
	else
	{
		?>
		<script type="text/javascript">
			$(document).ready(function () {
				$('.bootbox').removeAttr('tabindex');
			});
		</script>

		<?php
		$modelDoc			 = new Document();
		$modelVehicle		 = new Vehicles();
		$modelVehicleDocs	 = new VehicleDocs();
		$version			 = Yii::app()->params['siteJSVersion'];
		Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/booking.js?v=' . $version);
		Yii::app()->clientScript->registerCssFile(ASSETS_URL . '/plugins/form-select2/select2.css');
		?>
		<div class="panel-advancedoptions" >
			<div class="row">
				<div class="col-xs-12">
					<?php
					$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
						'id'					 => 'vendors-register-form1', 'enableClientValidation' => true,
						'clientOptions'			 => array(
							'validateOnSubmit'	 => true,
							'errorCssClass'		 => 'has-error',
							'afterValidate'		 => 'js:function(form,data,hasError){
                        if(!hasError){
						       
                            if(!getUnapprovedalert()){  
							   return false;
                            }  
							
							licnumber=$("#BookingCab_bcb_drv_lic_number").val();
							licexpdate=$("#BookingCab_bcb_drv_lic_exp_date").val();
							drivercabremark=$("#BookingCab_bkg_driver_cab_message").val();
						   	if(drivercabremark=="")
							{
							    $("#BookingCab_bkg_driver_cab_message_em_").text("Cab/Driver is unapproved. Please specify the reason for assigning it");
								$("#BookingCab_bkg_driver_cab_message_em_").addClass("text-danger");
								$("#markedBadBox").addClass("has-error");
								$("#BookingCab_bkg_driver_cab_message_em_").show();
							}
						    if(drivercabremark!="")
							{
								$("#markedBadBox").removeClass("has-error");
							}
							if(licnumber=="")
							{
								$("#BookingCab_bcb_drv_lic_number_em_").text("Please enter licence number");
								$("#BookingCab_bcb_drv_lic_number_em_").addClass("text-danger");
								$("#licNumberBox").addClass("has-error");
								$("#BookingCab_bcb_drv_lic_number_em_").show();
							}
							if(licnumber!="")
							{
								$("#licNumberBox").removeClass("has-error");
							}
							if(licexpdate=="")
							{
								$("#BookingCab_bcb_drv_lic_exp_date_em_").text("Please provide licence expiry date");
								$("#BookingCab_bcb_drv_lic_exp_date_em_").addClass("text-danger");
								$("#BookingCab_bcb_drv_lic_exp_date_em_").show();
								$("#licNumberExpDateBox").addClass("has-error");
							}
							if(licexpdate!="")
							{
								$("#licNumberExpDateBox").removeClass("has-error");
							}
							if(((licnumber=="" && drvLicNo=="") || (licexpdate=="" && drvLicExpDt=="") ||  (drivercabremark=="" && (vhcAppr!=1 || drvAppr!=1))))
							{
							  return false;
							}
							var form1 = $("#vendors-register-form1");
                            var formData = new FormData(form1[0]); 
                            $.ajax({
                            "type":"POST",
                            "url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('admin/booking/assigncabdriver', ['booking_id' => $bmodel->bkg_id])) . '",
						   data: formData,
						   processData: false,
						   contentType: false,
                           "dataType": "json",
                           "success":function(data1){
                                    if(data1.success)
                                    {                                    
                                        cabAssigned(data1.oldStatus);
                                    }
                                    else
                                    {
                                        var errors = data1.errors;
                                        settings=form.data(\'settings\');
                                        $.each (settings.attributes, function (i) 
                                        {
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
							'class'		 => 'form-horizontal',
							'enctype'	 => 'multipart/form-data',
						),
					));
					/* @var $form TbActiveForm */
					?>
					<div class="panel panel-default mb0">
						<div class="panel-body">
							<div class="row">
								<?php echo CHtml::errorSummary($model); ?>
								<?= $form->hiddenField($model, 'bcb_id') ?>
								<?= $form->hiddenField($model, 'bcb_vendor_id') ?>
								<?= $form->hiddenField($model, 'isVendorDriverFleet') ?>
								<?= $form->hiddenField($model, 'isVendorCabFleet') ?>
								<?= $form->hiddenField($model, 'bcb_driver_phone') ?>
								<div style="display:none" id="isVhcApproved"></div>
									<div class="row flex">
										<div class="col-md-6">
											<div class="panel panel-default panel-border customer-panel">
												<h3 class="pl15">Driver Information:</h3>
												<div class="panel-body pt0 pb0">
													<div class="row">
														<div class="col-sm-10">
															<?php
															$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
																'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
																'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
																'openOnFocus'		 => true, 'preload'			 => false,
																'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
																'addPrecedence'		 => false,];
															$this->widget('ext.yii-selectize.YiiSelectize', array(
																'model'				 => $model,
																'attribute'			 => 'bcb_driver_id',
																'useWithBootstrap'	 => true,
																"placeholder"		 => "Search Driver by name/phone",
																'fullWidth'			 => false,
																'htmlOptions'		 => array('width' => '100%'),
																'defaultOptions'	 => $selectizeOptions + array(
															'onInitialize'	 => "js:function(){
																  populateDriver(this, '{$model->bcb_driver_id}');
											}",
															'load'			 => "js:function(query, callback){
																loadDriver(query, callback);
											}",
															'render'		 => "js:{
																	option: function(item, escape){
																		return '<div><span class=\"\"><i class=\"fa fa-user mr5\"></i>' + escape(item.text) +'</span></div>';
																	},
																	option_create: function(data, escape){
																		return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
																	}
																}",
																),
															));
															?>

															<span id="markedBadDriver">
																<span class="fa-stack" title="Bad Driver">
																	<i class="fa-user-secret fa-stack-1x"></i>
																	<i class="fa fa-ban fa-stack-2x text-danger"></i>
																</span>
															</span>
															<span class="has-error"><?php echo $form->error($model, 'bcb_driver_id'); ?></span>

														</div> 
														<div class="col-sm-2 pl0">
															<button class="btn btn-info" type="button" id="addDriver"  onClick="bootbox.hideAll(); window.open($adminUrl + '/driver/add?vndid=' +<?= $model->bcb_vendor_id; ?>, '_blank');">Add Driver</button>
														</div>

													</div>
													<div align="center" id="driverStatus" class="col-xs-12 mt10 n"></div>

												</div>

												<div class="panel-body pt0">                                       
													<div class="row">

														<div id="markedBadBox" style="display:none;" class="col-sm-6 has-error">
															<?=
															$form->textAreaGroup($model, 'bkg_driver_cab_message', array('label'			 => '', 'widgetOptions'	 => [
																	'htmlOptions' => ['placeholder' => 'Please explain why you want to assign this driver or cab for the booking']]))
															?>
													</div>  
												</div>
												<div class="row">
													<div id="licNumberBox" style="display:none;" class="col-sm-6 has-error">
														<?=
														$form->textFieldGroup($model, 'bcb_drv_lic_number', array('label'			 => '', 'widgetOptions'	 => [
																'htmlOptions' => ['placeholder' => 'Enter driver license number']]))
														?>
													</div>
													<div id="licNumberExpDateBox" style="display:none;" class="col-sm-6 has-error">

														<?=
														$form->datePickerGroup($model, 'bcb_drv_lic_exp_date', array('label'			 => '',
															'widgetOptions'	 => array('options'		 => array('autoclose' => true, 'startDate' => date("d/m/Y"), 'format' => 'dd/mm/yyyy'),
																'htmlOptions'	 => array('readonly' => true, 'placeholder' => 'Enter driver license expiry date', 'value' => '')),
															'prepend'		 => '<i class="fa fa-calendar"></i>'));
														?>
													</div>
												</div>
												<div class="row">
													<div id="licFrontPath"  style="display:none;" class="col-sm-6">
														<?= $form->fileFieldGroup($modelDoc, 'doc_file_front_path', array('label' => 'Licence Front:', 'widgetOptions' => array())) ?> 
														<?= $form->checkboxListGroup($modelDoc, 'doc_temp_approved', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Temporary approved for next 24 hours'), 'htmlOptions' => []), 'inline' => true)) ?>
													</div>

													<div id="licBackPath"  style="display:none;" class="col-sm-6">
														<?= $form->fileFieldGroup($modelDoc, 'doc_file_back_path', array('label' => 'Licence Back:', 'widgetOptions' => array())) ?>
													</div>
												</div>

											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="panel panel-default panel-border customer-panel">
											<h3 class="pl15">Cab Information:</h3>

											<div class="panel-body pt0 pb0">
												<div class="row">
													<div class="col-sm-10">

														<?php
														$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
															'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
															'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
															'openOnFocus'		 => true, 'preload'			 => false,
															'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
															'addPrecedence'		 => false,];
														$this->widget('ext.yii-selectize.YiiSelectize', array(
															'model'				 => $model,
															'attribute'			 => 'bcb_cab_id',
															'useWithBootstrap'	 => true,
															"placeholder"		 => "Select Cabs by number",
															'fullWidth'			 => false,
															'htmlOptions'		 => array('width' => '100%'),
															'defaultOptions'	 => $selectizeOptions + array(
														'onInitialize'	 => "js:function(){
																  populateCabs(this, '{$model->bcb_cab_id}');
											}",
														'load'			 => "js:function(query, callback){
																loadCabs(query, callback);
											}",
														'render'		 => "js:{
																	option: function(item, escape){
																		return '<div><span class=\"\"><i class=\"fa fa-car mr5\"></i>' + escape(item.text) +'</span></div>';
																	},
																	option_create: function(data, escape){
																		return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
																	}
																}",
															),
														));
														?>
														<span id="markedBadCar">
															<span class="fa-stack" title="Bad Car">
																<i class="fa fa-car fa-stack-1x"></i>
																<i class="fa fa-ban fa-stack-2x text-danger"></i>
															</span>
														</span>
														<div class="has-error"><?= $form->error($model, 'bcb_cab_id') ?></div>
														<div class="has-error"><?= $form->error($model, 'bcb_cab_id1') ?></div>
													</div>

													<div class="col-sm-2 pl0">
														<button class="btn btn-info" type="button" id="addVehicle" >Add Cab</button>
	<!--															<button class="btn btn-info" type="button" onClick="bootbox.hideAll();window.open($adminUrl + '/vehicle/create?agtid=' +<?= $model->bcb_vendor_id; ?>+'&vhtid='+<?= $bmodel->bkg_vehicle_type_id; ?>, '_blank');">Add Cab</button>-->
													</div>
												</div>

												<div align="center" id="cabsStatus" class="col-xs-12 mt10 n"></div>

											</div>


											<div class="panel-body pt0">

												<div class="row">
													<div id="InsuranceExpDateBox" style="display:none;"  class="col-sm-6">

														<?=
														$form->datePickerGroup($modelVehicle, 'vhc_insurance_exp_date', array('label'			 => '',
															'widgetOptions'	 => array('options'		 => array('autoclose' => true, 'startDate' => date("d/m/Y"), 'format' => 'dd/mm/yyyy'),
																'htmlOptions'	 => array('readonly' => true, 'placeholder' => 'Enter vehicle insurance expiry date', 'value' => '')),
															'prepend'		 => '<i class="fa fa-calendar"></i>'));
														?>
													</div>

													<div id="RegistrationExpDateBox"  style="display:none;" class="col-sm-6">

														<?=
														$form->datePickerGroup($modelVehicle, 'vhc_reg_exp_date', array('label'			 => '',
															'widgetOptions'	 => array('options'		 => array('autoclose' => true, 'startDate' => date("d/m/Y"), 'format' => 'dd/mm/yyyy'),
																'htmlOptions'	 => array('readonly' => true, 'placeholder' => 'Enter vehicle registration expiry date', 'value' => '')),
															'prepend'		 => '<i class="fa fa-calendar"></i>'));
														?>
													</div>

												</div>
												<div class="row">

													<div id="RegistrationPath" style="display:none;" class="col-sm-6">
														<?= $form->fileFieldGroup($modelVehicleDocs, 'registrationCertificateFile', array('label' => 'Registration Certificate:', 'widgetOptions' => array())) ?>
														<?= $form->checkboxListGroup($modelVehicle, 'vhc_temp_reg_certificate_approved', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Temporary approved for next 24 hours'), 'htmlOptions' => []), 'inline' => true)) ?>

													</div>

													<div id="InsurancePath" style="display:none;"   class="col-sm-6">
														<?= $form->fileFieldGroup($modelVehicleDocs, 'insuranceFile', array('label' => 'Insurance:', 'widgetOptions' => array())) ?>
														<?= $form->checkboxListGroup($modelVehicle, 'vhc_temp_insurance_approved', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Temporary approved for next 24 hours'), 'htmlOptions' => []), 'inline' => true)) ?>
													</div>
												</div>                 
											</div>
										</div>


									</div>
								</div>
								<div class="col-sm-12">
									<?=
									$form->checkboxListGroup($model, 'chk_user_msg', array(
										'widgetOptions'	 => array(
											'data' => array('User', 'Driver', 'Vendor'),
										),
										'inline'		 => true,
											)
									);
									?>
								</div>
								<div id="logOutput"></div>
							</div>

						</div>
						<div class="panel-footer text-center">
							<?php echo CHtml::button('Submit', array('class' => 'btn btn-primary pl30 pr30', 'onClick' => 'checkCabDriverTimeOverlap()')); ?>
						</div>
					</div>
					<?php $this->endWidget(); ?>
				</div>
			</div>
		</div>

		<?php
		$version			 = Yii::app()->params['customJsVersion'];
		Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/custom.js?v=' . $version, CClientScript::POS_HEAD);
		?>

		<script>
			drvLicNo = "";
			drvLicExpDt = "";
			vhcAppr = '0';
			drvAppr = '0';
			$(document).ready(function () {
				$("#markedBadDriver").hide();
				$("#markedBadCar").hide();
				if ($("#BookingCab_bcb_driver_id").val() != '' || $("#BookingCab_bcb_cab_id").val() != '') {
					driverDetails();
					checkCabTimeOverlap();
				}
				$("#BookingCab_chk_user_msg_0").attr('checked', 'checked');
				$("#BookingCab_chk_user_msg_1").attr('checked', 'checked');
			});

			$(document).on("driverCabDetails", function (event, data) {
				$("#BookingCab_bcb_driver_phone").val(data.data.drvContact);
				cabDrvDetails(data);
			});

			$("#BookingCab_bcb_driver_id").change(function () {
				$("#driverStatus").html("");
				$("#BookingCab_bcb_driver_phone").val('');
				driverDetails();
			});

			$("#BookingCab_bcb_cab_id").change(function () {
				$("#cabsStatus").html("");
				cabDetails();
				checkCabTimeOverlap();
			});

			function driverDetails() {
				var booking = new Booking();
				var model = {};
				model.driverId = $("#BookingCab_bcb_driver_id").val();
				model.vehicleId = $("#BookingCab_bcb_cab_id").val();
				model.vendorId = $("#BookingCab_bcb_vendor_id").val();
				booking.model = model;
				booking.driverCabDetails();

			}
			function cabDetails() {
				var booking = new Booking();
				var model = {};
				model.driverId = $("#BookingCab_bcb_driver_id").val();
				model.vehicleId = $("#BookingCab_bcb_cab_id").val();
				model.vendorId = $("#BookingCab_bcb_vendor_id").val();
				booking.model = model;
				booking.driverCabDetails();
			}

			function cabDrvDetails(data)
			{
				var isCabFleet = data.data.isCabFleet;
				var driverId = data.data.driverId;
				var vehicleId = data.data.vehicleId;
				var isCabFreeze = data.data.isCabFreeze;
				var isCabCng = data.data.isCabCng;
				var isDrvFleet = data.data.isDrvFleet;
				var isDrvFreeze = data.data.isDrvFreeze;
				var driverBad = data.data.drvMarkBad;
				var carBad = data.data.carMarkBad;
				var vhcApproved = data.data.isVhcApproved;
				vhcAppr = vhcApproved;
				var drvApproved = data.data.isDrvApproved;
				drvAppr = drvApproved;
				var drvLic = data.data.isDrvlicense;
				var drvLicExpDate = data.data.isDrvLicExpDate;
				drvLicNo = drvLic;
				drvLicExpDt = drvLicExpDate;
				var drvLicId = data.data.isLicenseDocId;
				var isRegistrationCertificate = data.data.isRegistrationCertificate;
				var isInsurance = data.data.isInsurance;
				var vehicleRegistrationCertificateExpDate = data.data.vehicleRegistrationCertificateExpDate;
				var vehicleInsuranceExpDate = data.data.vehicleInsuranceExpDate;
				checkRemarkBox(driverBad, carBad, vhcApproved, drvApproved, data.logOutput);
				if (driverId != "") {
					$("#driverStatus").html("");
					var htmlDriver = isDrvFleet == "1" ? " In Vendor Fleet | " : " Not In Vendor Fleet |  ";
					htmlDriver += drvApproved == "1" ? " Approved " : "Not Approved";
					htmlDriver += isDrvFreeze == "1" ? " | Frozen  " : "";
					$("#driverStatus").html("<i>" + htmlDriver + "</i>");
					$("#BookingCab_isVendorDriverFleet").val(isDrvFleet);
					checkDriverInfoBox(drvApproved, drvLic, drvLicExpDate, drvLicId);
				}
				if (vehicleId != "") {
					$("#cabsStatus").html("");
					var htmlCabs = isCabFleet == "1" ? " In Vendor Fleet | " : " Not In Vendor Fleet | ";
					htmlCabs += vhcApproved == "1" ? " Approved " : " Not Approved ";
					htmlCabs += isCabFreeze == "1" ? " | Frozen  " : "";
					$("#cabsStatus").html("<i>" + htmlCabs + "</i>");
					$("#BookingCab_isVendorCabFleet").val(isCabFleet);
					checkCabInfoBox(isRegistrationCertificate, isInsurance, vehicleRegistrationCertificateExpDate, vehicleInsuranceExpDate);
				}
			}

			function checkCabInfoBox(isRegistrationCertificate, isInsurance, vehicleRegistrationCertificateExpDate, vehicleInsuranceExpDate)
			{
				$('#InsuranceExpDateBox').hide();
				$('#RegistrationExpDateBox').hide();
				$('#RegistrationPath').hide();
				$('#InsurancePath').hide();
				if (isRegistrationCertificate == "0")
				{
					$('#RegistrationPath').show();
					$('#VehicleDocs_registrationCertificateFile').val('');
					$('#VehicleDocs_registrationCertificateFile').removeClass("has-error");
				}
				if (isInsurance == "0")
				{
					$('#InsurancePath').show();
					$('#VehicleDocs_insuranceFile').val('');
					$('#VehicleDocs_insuranceFile').removeClass("has-error");
				}

				if (vehicleInsuranceExpDate == '')
				{
					$('#InsuranceExpDateBox').show();
					$('#Vehicles_vehicleInsuranceExpDate').val('');
					$('#Vehicles_vehicleInsuranceExpDate').removeClass("has-error");
				}
				if (vehicleRegistrationCertificateExpDate == '')
				{
					$('#RegistrationExpDateBox').show();
					$('#Vehicles_vehicleRegistrationCertificateExpDate').val('');
					$('#Vehicles_vehicleRegistrationCertificateExpDate').removeClass("has-error");
				}
			}
			function getUnapprovedalert() {
				vhcid = $("#BookingCab_bcb_cab_id").val();
				driverId = $("#BookingCab_bcb_driver_id").val();
				appres = false;
				if (driverId == "")
				{
					$('#BookingCab_bcb_driver_id_em_').text('Please select a driver.');
					$('#BookingCab_bcb_driver_id_em_').addClass('text-danger');
					$('#BookingCab_bcb_driver_id_em_').show();
					return false;
				} else if (vhcid == "")
				{
					$('#BookingCab_bcb_driver_id_em_').text('');
					$('#BookingCab_bcb_driver_id_em_').removeClass('text-danger');
					$('#BookingCab_bcb_driver_id_em_').hide();
					$('#BookingCab_bcb_cab_id_em_').text('Please select a cab.');
					$('#BookingCab_bcb_cab_id_em_').addClass('text-danger');
					$('#BookingCab_bcb_cab_id_em_').show();
					return false;
				} else
				{
					$('#BookingCab_bcb_driver_id_em_').text('');
					$('#BookingCab_bcb_driver_id_em_').removeClass('text-danger');
					$('#BookingCab_bcb_driver_id_em_').hide();
					$('#BookingCab_bcb_cab_id_em_').text('');
					$('#BookingCab_bcb_cab_id_em_').removeClass('text-danger');
					$('#BookingCab_bcb_cab_id_em_').hide();
					$href = '<?= Yii::app()->createUrl('admin/vehicle/checkapprovedntottrips') ?>';
					jQuery.ajax({type: 'GET', url: $href,
						"dataType": "json",
						async: false,
						data: {"vhcid": vhcid},
						success: function (data) {

							if (data.showMessage) {
								var con = confirm("We need papers for this car. Allowing assignment but papers must be submitted within 24 hours");
								if (con) {
									appres = true;
								} else {
									appres = false;
								}
							} else {
								appres = true;
							}
						}
					});
					return appres;
				}

			}
			function showMessage() {
				bootbox.confirm({
					size: "medium",
					message: "We need papers for this car. Allowing assignment but papers must be submitted within 24 hours",
					buttons: {
						confirm: {
							label: 'OK',
							className: 'btn-success'
						},
						cancel: {
							label: 'CANCEL',
							className: 'btn-danger'
						}
					},
					callback: function (result) {

						return result;

					}
				});
			}
			$('#addVehicle').click(function () {
				agtid = $('#BookingCab_bcb_vendor_id').val();
				vhtid = <?= $bmodel->bkg_vehicle_type_id ?>;
				$href = '<?= Yii::app()->createUrl('admin/vehicle/create') ?>';
				jQuery.ajax({type: 'GET', url: $href, data: {"agtid": agtid, "vhtid": vhtid},
					success: function (data) {
						box = bootbox.dialog({
							message: data,
							title: 'Add Cab',
							onEscape: function () {
								// user pressed escape


							},
						});

						box.on('hidden.bs.modal', function (e) {
							$('body').addClass('modal-open');
						});
					}});
			});
			function checkDriverInfoBox(drvApproved, drvLic, drvLicExpDate, drvLicId = 0) {
				flag = 0;
				$('#licNumberBox').hide();
				$('#licNumberExpDateBox').hide();
				$('#licFrontPath').hide();
				$('#licBackPath').hide();
				$('#BookingCab_bcb_driver_id1_em_').hide();
				if (drvLic == '')
				{
					flag++;
					$('#licNumberBox').show();
					$('#BookingCab_bcb_drv_lic_number').val('');
					$('#BookingCab_bcb_drv_lic_number').removeClass("has-error");
				}
				if (drvLicExpDate == '')
				{
					flag++;
					$('#licNumberExpDateBox').show();
					$('#BookingCab_bcb_drv_lic_exp_date').val('');
					$('#BookingCab_bcb_drv_lic_exp_date').removeClass("has-error");
				}
				if (drvLicId == '' || drvLicId == 0)
				{
					flag++;
					$('#licFrontPath').show();
					$('#BookingCab_doc_file_front_path').removeClass("has-error");
					$('#licBackPath').show();
					$('#BookingCab_doc_file_back_path').removeClass("has-error");
				}
				if (drvApproved != 1 && flag >= 2)
				{
					$('#BookingCab_bcb_driver_id1_em_').text('You are adding a new driver. Enter the correct license number and Driver License expiry date. It is MUST that the vendor will upload all documents within next 24 hours');
					$('#BookingCab_bcb_driver_id1_em_').addClass('text-danger');
					$('#BookingCab_bcb_driver_id1_em_').show();
				} else if (drvApproved != 1)
				{
					$('#BookingCab_bcb_driver_id1_em_').text('Driver is not approved.');
					$('#BookingCab_bcb_driver_id1_em_').addClass('text-danger');
					$('#BookingCab_bcb_driver_id1_em_').show();
			}
			}
			function checkRemarkBox(drvBad, cabBad, vhcApproved, drvApproved, logOutput) {
				$("#markedBadDriver").hide();
				$("#markedBadCar").hide();
				$('#BookingCab_bcb_cab_id1_em_').hide();
				if (vhcApproved != 1 || drvApproved != 1)
				{
					if (vhcApproved != 1)
					{
						$('#BookingCab_bcb_cab_id1_em_').text('You are adding a new car. Enter the correct Car license plate number and require that the vendor provide all documents within next 24 hours');
						$('#BookingCab_bcb_cab_id1_em_').addClass('text-danger');
						$('#BookingCab_bcb_cab_id1_em_').show();
					}
					$("#markedBadBox").show();
				} else if (drvBad > 0 || cabBad > 0)
				{
					$("#markedBadBox").show();
					if (drvBad > 0) {
						$("#markedBadDriver").show();
					} else {
						$("#markedBadDriver").hide();
					}
					if (cabBad > 0) {
						$("#markedBadCar").show();
					} else {
						$("#markedBadCar").hide();
					}
				} else
				{
					$("#markedBadBox").hide();
				}
				if ($.fn.yiiGridView != undefined && $.fn.yiiGridView.settings['bookingmarkedbadGrid'] != undefined)
				{
					$(document).off('click.yiiGridView', $.fn.yiiGridView.settings['bookingmarkedbadGrid'].updateSelector);
				}
				$('#logOutput').html(logOutput);
			}
			function checkCabTimeOverlap() {
				var bcbid = $("#BookingCab_bcb_id").val();
				var cabid = $("#BookingCab_bcb_cab_id1").val();
				if (cabid > 0) {
					$href = '<?= Yii::app()->createUrl('admin/booking/checkcabtimeoverlap') ?>';
					jQuery.ajax({type: 'GET', "dataType": "json", url: $href, data: {"bcbid": bcbid, "cabid": cabid},
						success: function (data1) {
							if (data1.overlapTrips > 0) {
								$pretext = $('#BookingCab_bcb_cab_id1_em_').text();
								$errText = 'This Cab is already assigned to other booking for the trip duration.';
								$textVal = ($pretext != '') ? '<br>' + $errText : $errText;
								$('#BookingCab_bcb_cab_id1_em_').html($textVal);
								$('#BookingCab_bcb_cab_id1_em_').addClass('text-danger');
								$('#BookingCab_bcb_cab_id1_em_').show();
								$("#markedBadBox").show();
							}
						}
					});
				}
			}
			refreshDriver = function () {
				agtid = $('#BookingCab_bcb_vendor_id').val();
				box.modal('hide');
				$href = '<?= Yii::app()->createUrl('admin/driver/json') ?>';
				jQuery.ajax({type: 'GET', "dataType": "json", url: $href, data: {"agtid": agtid},
					success: function (data1) {
						$data = data1;
						$('#<?= CHtml::activeId($model, "bcb_driver_id") ?>').select2({data: $data, multiple: false});
					}
				});
			};
			refreshCab = function () {
				agtid = $('#BookingCab_bcb_vendor_id').val();
				vhtid = <?= $bmodel->bkg_vehicle_type_id ?>;
				box.modal('hide');
				$href = '<?= Yii::app()->createUrl('admin/vehicle/json') ?>';
				jQuery.ajax({type: 'GET', "dataType": "json", url: $href, data: {"agtid": agtid},
					success: function (data1) {
						$data = data1;
						$('#<?= CHtml::activeId($model, "bcb_cab_id") ?>').trigger({type: 'select2:select', params: {data: $data, multiple: false}});

					}
				});
			};
			$sourceList1 = null;
			function populateDriver(obj, drvId) {
				obj.load(function (callback) {
					var obj = this;
					if ($sourceList1 == null) {
						xhr = $.ajax({
							url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/alldriverbyquery', ['onlyActive' => ($model->bcb_driver_id > 0 || $model->bcb_cab_id > 0) ? 4 : 3, 'drv' => ''])) ?>',
							dataType: 'json',
							data: {drv: drvId, vnd: $("#BookingCab_bcb_vendor_id").val()},
							success: function (results) {
								$sourceList1 = results;
								obj.enable();
								callback($sourceList1);
								obj.setValue(drvId);
							},
							error: function () {
								callback();
							}
						});
					} else {
						obj.enable();
						callback($sourceList1);
						obj.setValue(drvId);
					}
				});
			}
			function loadDriver(query, callback) {
				$.ajax({
					url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/alldriverbyquery')) ?>?vnd=' + $("#BookingCab_bcb_vendor_id").val() + '&onlyActive=2&q=' + encodeURIComponent(query),
					type: 'GET',
					dataType: 'json',
					global: false,
					error: function () {
						callback();
					},
					success: function (res) {
						callback(res);
					}
				});
			}
			$sourceList2 = null;
			function populateCabs(obj, cabid) {
				obj.load(function (callback) {
					var obj = this;
					if ($sourceList2 == null) {
						xhr = $.ajax({
							url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcabsbyquery', ['onlyActive' => ($model->bcb_driver_id > 0 || $model->bcb_cab_id > 0) ? 2 : 1])) ?>',
							dataType: 'json',
							data: {cabs: cabid, vnd: $("#BookingCab_bcb_vendor_id").val()},
							success: function (results) {
								console.log(results);
								$sourceList2 = results;
								obj.enable();
								callback($sourceList2);
								obj.setValue(cabid);
							},
							error: function () {
								callback();
							}
						});
					} else {
						obj.enable();
						callback($sourceList2);
						obj.setValue(cabid);
					}
				});
			}
			function loadCabs(query, callback) {
				$.ajax({
					url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcabsbyquery')) ?>?vnd=' + $("#BookingCab_bcb_vendor_id").val() + '&onlyActive=1&q=' + encodeURIComponent(query),
					type: 'GET',
					dataType: 'json',
					global: false,
					error: function () {
						callback();
					},
					success: function (res) {
						callback(res);
					}
				});
			}
			function checkCabDriverTimeOverlap() {
				var bcbid = $("#BookingCab_bcb_id").val();
				var cabid = $("#BookingCab_bcb_cab_id").val();
				var driverid = $("#BookingCab_bcb_driver_id").val();
				if (cabid > 0 || driverid > 0) {
					$href = '<?= Yii::app()->createUrl('admin/booking/checkcabtimeoverlap') ?>';
					jQuery.ajax({type: 'GET', "dataType": "json", url: $href, data: {"bcbid": bcbid, "cabid": cabid, "driverid":driverid},
						success: function (data1) {
							if (data1.overlapTrips > 0 || data1.overlapDriverTrips > 0) {
								if (data1.userType == 4) {
									bootbox.confirm({
										size: "medium",
										message: data1.msg,
										buttons: {
											confirm: {
												label: 'OK',
												className: 'btn-success'
											},
											cancel: {
												label: 'CANCEL',
												className: 'btn-danger'
											}
										},
										callback: function (result) {
											if (result) {
												$('#vendors-register-form1').submit();
											}
										}
									});
								}
							} else {
								$('#vendors-register-form1').submit();
							}
						}
					});
				}
			}
		</script>
	<?php } ?>