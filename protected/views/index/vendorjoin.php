<style>
    .new-booking-list .form-horizontal .form-group{ margin-left: 0; margin-right: 0;}
    .top-buffer{padding-top: 10px;}
    .modal-dialog{ width: 95%!important;}
</style>
<?
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/js/jquery.mask.min.js');
$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
#$cartype  = VehicleTypes::model()->getParentVehicleTypeList(1);
$cartype			 = VehicleTypes::model()->getParentVehicleTypes(2);
$yearRange			 = [];
$yearRange['']		 = 'Select model year';
$dy					 = date('Y');
for ($i = $dy; $i >= $dy - 20; $i--)
{
	$yearRange[$i] = $i;
}
?>
<div class="row pt20">
    <p style="color:green;" id="successAlert"></p>
    <div class="col-xs-12 col-sm-6 join_padding mb20">
		<div class="pt20 new-booking-list main_time border-blueline">
			<div class="row">
				<div id="VendorOuterDiv">
					<div class="col-xs-12">
						<h4 style="color: #000000;"><span id="VendorOuterDivText"></span></h4>
					</div>
				</div>  
				<div id="VendorInnerDiv">
					<div class="col-xs-12 mb10 ml20" style="color:#48b9a7;"><h3>START YOUR APPLICATION</h3></div>
					<?php
					$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
						'id'					 => 'vendorForm', 'enableClientValidation' => true,
						'clientOptions'			 => array(
							'validateOnSubmit'	 => true,
							'errorCssClass'		 => 'has-error',
							'afterValidate'		 => 'js:function(form,data,hasError){
							if(!hasError){
							$("#vendorSubmitDiv").hide();
							if (countSubmit==0){
								countSubmit++;
							$.ajax({
								"type":"POST",
								"url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('index/vendorjoining', [])) . '",
								"data":form.serialize(),
									"dataType": "json",
									"success":function(data1){
									if(data1.success)
										{
											$("#VendorInnerDiv").hide();
											$("#VendorOuterDiv").show();
											$("#VendorOuterDivText").html("<b>Your application is almost approved.</b><br/> <br/> <a href=\"https://play.google.com/store/apps/details?id=com.gozocabs.vendor&hl=en_IN\" target=\"_blank\">Download Gozo partner app – from google play store</a> <br/><br/> Watch this video to sign your vendor agreement and upload your papers  <br/> <br/> <a href=\"https://youtu.be/AfbwgIJN0H0\" target=\"_blank\"> https://youtu.be/AfbwgIJN0H0 <br/></a><br/> You will start receiving business within 48hours of uploading all your papers"); 
										}
										else
										{   
											countSubmit--;
											$("#VendorOuterDiv").show();
											$("#vendorSubmitDiv").show();
											if(data1.msg=="signError"){
											   $("#VendorOuterDivText").html("Thank you. You have already submitted information to attach your car with us. We have now resent you instructions to take the next step."); 
											   $("#Vendors_first_name").val("");
											   $("#Vendors_last_name").val("");
											//   $("#Vendors_vnd_company").val("");
											   $("#ContactPhone_phn_phone_no").val("");
											   $("#ContactEmail_eml_email_address").val("");
											   $("#Vendors_vnd_city").val("");   
											}
											else if(data1.msg=="error")
											{
											   countSubmit--;
											   $("#VendorOuterDivText").html("Error occured. Please enter all the mandatory fields."); 
											}

										}
									},
								});
								}
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
					/* @var $form TbActiveForm */
					?>
					<input type="hidden" value="0" id="socialuserid" name="socialuserid" />
					<input type="hidden" value="0" id="contactId" name="contactId" />
					<input type="hidden" value="0" id="isDriver" name="isDriver" />
					<input type="hidden" id="dataMapping" name="dataToMap"/>

					<!-- //Start - Application Form Part 1 -->
					<div id="venjoin1" class="col-xs-12">
						<div class="row">
							<div class="col-xs-11 col-md-8 ml20">
								<label for="name"><b>First name (as shown on your Driver's License)</b></label>
								<?= $form->textFieldGroup($model, 'first_name', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter First Name')))) ?>
							</div>
						</div>

						<div class="row">
							<div class="col-xs-11 col-md-8 ml20">
								<label for="name"><b>Last name  (as shown on your Driver's License)</b></label>
								<?= $form->textFieldGroup($model, 'last_name', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Last Name')))) ?>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-11 col-md-8 ml20">
								<label for="phone"><b>Phone numbers</b></label>
								<div class="row">
								<div class="col-xs-12">
								<?php
									$this->widget('ext.intlphoneinput.IntlPhoneInput', array(
										'model'					 => $modelContPhone,
										'attribute'				 => 'phn_phone_no',
										'codeAttribute'			 => 'phn_phone_country_code',
										'numberAttribute'		 => 'phn_phone_no',
										'options'				 => array(// optional
											'separateDialCode'	 => true,
											'autoHideDialCode'	 => true,
											'initialCountry'	 => 'in'
										),
										'htmlOptions'			 => ['class' => 'form-control', 'id' => 'fullContactNumber1'],
										'localisedCountryNames'	 => false, // other public properties
									));
								?> 
								</div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-xs-11 col-md-8 ml20">
								<label for="email"><b>Email address</b></label>
								<?= $form->textFieldGroup($modelContEmail, 'eml_email_address', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Email Id')))) ?>
								<span id="errId" style="color: #F25656"></span>
							</div>
						</div>

						<div class="row">
							<div class="col-xs-11 col-md-8 ml20">
								<label for="city"><b>What city do you do most business in</b></label>
								<?php
								$this->widget('ext.yii-selectize.YiiSelectize', array(
									'model'				 => $model,
									'attribute'			 => 'vnd_city',
									'useWithBootstrap'	 => true,
									"placeholder"		 => "Select City",
									'fullWidth'			 => true,
									'htmlOptions'		 => array('width'	 => '100%', 'class'	 => 'ctyCheck'
									),
									'defaultOptions'	 => $selectizeOptions + array(
								'onInitialize'	 => "js:function(){
												  populateSource(this, '{$model->vnd_city}');
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

						<div class="row">
							<div class="col-xs-11 col-md-8 col-lg-4 top-buffer ml20 pt0">
								<div class="Submit-button" style="text-align: left" id="vendorSubmitDiv">
									<button type="button" class = "btn btn-primary btn-lg pl40 pr40 proceed-new-btn" onclick = "vencarinfo()" >Next</button>
								</div>
							</div>
						</div> 
					</div>
					<!-- //End - Application Form Part 1 -->


					<!--------2nd div start: For Cars number and DOC or Not --------->
					<div id="venjoin2" style="display: none;">
						<div class="row">
							<div class="col-xs-11 col-md-8 ml20">
								<label for="name"><b>How many cars do you own</b></label>
								<?=
								$form->dropDownListGroup($modelVndPref, 'vnp_cars_own',
								 [
											'label'			 => '',
											"id"			 => "vndPrefCarOwn",
											'widgetOptions'	 =>
											[
												'data'			 =>
												[
													'0'	 => 'Select Quantity',
													'1'	 => '1', '2'	 => '2', '3'	 => '3', '4'	 => '4', '5'	 => '5',
													'6'	 => '6', '7'	 => '7', '8'	 => '8', '9'	 => '9', '10' => '10', '11' => '11', '12' => '12', '13' => '13', '14' => '14', '15' => '15', '16' => '16', '17' => '17', '18' => '18', '19' => '19', '20' => '20'
												],
												'htmlOptions'	 => []
											]
								])
								?>
							</div>
						</div>

						<div class="row">
							<div class="col-xs-11 col-md-8 ml20">
								<label for="name"><b>Are you driver also for this car?</b></label>
								<?=
								$form->dropDownListGroup($model, 'vnd_cat_type',
								 ['label'			 => '',
											'widgetOptions'	 =>
											['data'			 =>
												['0'	 => 'Select',
													'1'	 => 'Yes',
													'2'	 => 'No'
												],
												'htmlOptions'	 => []
											]
								])
								?>
							</div>
						</div>

						<div class="row">
							<div class="col-xs-11 col-md-8 col-lg-4 ml20 pt0">
								<div class="Submit-button" style="text-align: left" id="vendorSubmitDiv">
									<button type="button" class = "btn btn-primary btn-lg pl40 pr40 proceed-new-btn" onclick="vencarcount()">Next</button>
								</div>
							</div>
						</div>
					</div>    

					<!--------3rd div start--------->
					<div id="venjoin3" style="display: none;">
						<div class="row">
							<div class="col-xs-11 col-md-8 ml20">
								<label for="name"><b>Car model</b></label>
								<?php
								$this->widget('booster.widgets.TbSelect2', array(
									'model'			 => $model,
									'attribute'		 => 'vnd_car_model',
									'val'			 => $model->vnd_car_model,
									'data'			 => $cartype,
									'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Car Type')
								));
								?>
							</div>
						</div>

						<div class="row">
							<div class="col-xs-11 col-md-8 ml20">
								<label for="year"><b>Year of manufacture of car</b></label>
								<? //= $form->textFieldGroup($model, 'vnd_car_year', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Car Year')))) ?>
								<?=
								$form->numberFieldGroup($model, 'vnd_car_year', array('label'			 => '',
									'widgetOptions'	 => array('htmlOptions' => array('min' => date('Y') - 25, 'max' => date('Y')))));
								?>
								<span id="errId" style="color: #F25656"></span>
							</div>
						</div>

						<div class="row">
							<div class="col-xs-11 col-md-8 ml20">
								<label for="name"><b>Car number plate</b></label>
								<?= $form->textFieldGroup($model, 'vnd_car_number', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Car Number')))) ?>
							</div>
						</div>

						<div class="row">
							<div class="col-xs-11 col-md-8 ml20">
								<label for="name"><b>Driver name</b></label>
								<?= $form->textFieldGroup($model, 'vnd_driver_name', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Driver Name')))) ?>
							</div>
						</div>

						<div class="row">
							<div class="col-xs-11 col-md-8 ml20">
								<label for="name"><b>Driver license number</b></label>
								<?= $form->textFieldGroup($model, 'vnd_driver_license', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Driver license number')))) ?>
							</div>
						</div>

						<div class="row" id="divFinishRegistration">
							<div class="col-xs-11 col-md-8 col-lg-4 top-buffer ml20">
								<div class="Submit-button" style="text-align: left" >
									<button type="button" class = "btn btn-primary btn-lg pl40 pr40 proceed-new-btn" id="finalsubmit" onclick="createVendor()">Finish</button>
								</div>
							</div>
						</div> 
					</div>
					<!--------4th div START: FOR NON-DOC --------->
					<div id="venjoin4" style="display: none;"> 
						<div class="panel-body new-booking-list">
							<h4>Add Car #<span id="carcount">1</span></h4>
							<div class="row">
								<?= $form->hiddenField($model, 'vnd_car_model1') ?>
								<div class="col-xs-11 col-md-8 ml20">
									<label for="name"><b>Car model</b></label>
									<?php
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $model,
										'attribute'		 => 'vnd_car_model',
										'val'			 => $model->vnd_car_model,
										'data'			 => $cartype,
										'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Car Type', 'id' => 'carmodel')
									));
									?>
								</div>
							</div>

							<div class="row">
								<div class="col-xs-11 col-md-8 ml20">
									<label for="year"><b>Year of manufacture of car</b></label>
									<?= $form->hiddenField($model, 'vnd_car_year1') ?>
									<? //= $form->textFieldGroup($model, 'vnd_car_year', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Car Year', 'id' => 'caryear')))) ?>
									<?=
									$form->numberFieldGroup($model, 'vnd_car_year', array('label'			 => '',
										'widgetOptions'	 => array('htmlOptions' => array('min' => date('Y') - 25, 'max' => date('Y'), 'id' => 'caryear'))));
									?>
									<span id="errId" style="color: #F25656"></span>
								</div>
							</div>

							<div class="row">
								<div class="col-xs-11 col-md-8 ml20">
									<label for="name"><b>Car number plate</b></label>
									<?= $form->hiddenField($model, 'vnd_car_number1') ?>
									<?= $form->textFieldGroup($model, 'vnd_car_number', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Car Number', 'id' => 'carnumber')))) ?>
								</div>
							</div>
						</div>    
						<div class="panel-body new-booking-list" id="driverjoin">
							<h4>Add Driver #<span id="driverCount">1</span></h4>
							<div class="row">
								<div class="col-xs-11 col-md-8 ml20">
									<label for="name"><b>Driver name</b></label>
									<?= $form->hiddenField($model, 'vnd_driver_name1') ?>
									<?= $form->textFieldGroup($model, 'vnd_driver_name', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Driver Name', 'id' => 'drivername')))) ?>
								</div>
							</div>

							<div class="row">
								<div class="col-xs-11 col-md-8 ml20">
									<label for="name"><b>Driver license number</b></label>
									<?= $form->hiddenField($model, 'vnd_driver_license1') ?>
									<?= $form->textFieldGroup($model, 'vnd_driver_license', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Driver license number', 'id' => 'driverlicense')))) ?>
								</div>
							</div>
						</div>    
						<div class="row" id="vendorMultiCarNxtDiv">
							<div class="col-xs-11 col-md-8 col-lg-4 ml20 pt0">
								<div class="Submit-button" style="text-align: left" >
									<button type="button" class = "btn btn-primary btn-lg pl40 pr40 proceed-new-btn" onclick="addCarDetails()">Next</button>
								</div>
							</div>
						</div>
					</div>
					<!--------4th div END: FOR NON-DOC--------->

					<div id="loading"></div>
					<?php $this->endWidget(); ?>
				</div>
			</div> 
		</div>

    </div>
    <div class="col-xs-12 col-sm-6">
		<div class="pull-right"><img src="../images/dco_operators.png?v=0.1" alt="Inviting DCO's and Txi Operators all over India"></div>
        <div><b>Attach your car into the Gozo Vendor networks</b></div>
        <div><p> If you own or operate a inter-city taxi, then you should join with Gozo.</p></div>
        <div><b>Benefits for Gozo vendor partners</b></div>
        <div>
            <ul class="pl15">
                <li> Gozo focuses on getting customer demand</li>
                <li> You simply provide top quality service </li>
                <li> Stay busy in all seasons. Good service = More business</li>
                <li> Get great reviews from customers</li>
                <li> Gozo sends you payments on-time</li>
                <li> Use your Gozo partner and Gozo driver mobile app to keep in continous touch with Gozo</li>
            </ul>
        </div>
        <h2 class="mt20"><span style="color:#096dc4">Any questions? Contact our Vendor Relations team</span></h2>
		<div class="pl20 pr20 main_time pb0 border-greenline" style="margin: 0%; font-size: 16px; color: #000;">
			<p><figure><img src="<?= Yii::app()->baseUrl ?>/images/india-flag.png" alt="INDIA" class="mr10 mb5"><a href="tel:03366283905"  style="color:#000">03366283905</a> or <a href="tel:03371122005"  style="color:#000">03371122005</a> <span style="font-size: 12px;">(24x7 Dedicated Vendor line)</span></figure></p>
			<p><i class="fa fa-envelope mr10 mb10" style="font-size:18px; color: #fb6523;"></i> <a href="mailto:vendors@gozocabs.in" style="color:#000;">vendors@gozocabs.in</a></p>
			<p><i class="fa fa-paper-plane mr10 mb10" style="font-size:18px; color: #36abe8;"></i> <a href="https://t.me/gozocabs" style="color:#000;"> Join the GozoCabs channel on telegram</a></p>
		</div>
		<br>
		<h2 class="mt0"><span style="color:#096dc4">Travel Agents, Hotel owners, Shopkeepers...<a href="http://www.aaocab.com/agent/join">Join our travel partner network here</a></span></h2>
	</div>
</div>

<script type="text/javascript">
    var countSubmit = 0;
    var carCount = 1, driverCount = 1, totalCount;
    var vndCarModel = vndCarYear = vndCarNumber = vndDriverName = vndDriverLicence = '';
    var socialBox;
    $(document).ready(function () {
        $('#phone').mask('9999999999');
        $('#VendorOuterDiv').hide();

    });

    function vencarinfo()
    {
        let fName = $("#Vendors_first_name").val();
        let lName = $("#Vendors_last_name").val();
        let number = $('#ContactPhone_phn_phone_no').val().trim();
        let email = $('#ContactEmail_eml_email_address').val().trim();
        let city = $("#Vendors_vnd_city").val();
        if (fName === "" || fName === null)
        {
            alert("Please select your first name");
            return false;
        } else if (lName === "" || lName === null)
        {
            alert("Please select your last name");
            return false;
        } else if (email === "" || email === null)
        {
            alert("Email Address should not be blank");
            return false;
        } else if (number === "" || number === null)
        {
            alert("Phone Number should not be blank");
            return false;
        } else if (city === "" || city === null)
        {
            alert("Please select your home city");
            return false;
        } else
        {
            loadSocialLogin();
        }
    }

    function venderinfo() {

        $('#<?= CHtml::activeId($model, "vnd_car_model1") ?>').val($('#<?= CHtml::activeId($model, "vnd_car_model") ?>').val());
        $('#<?= CHtml::activeId($model, "vnd_car_year1") ?>').val($('#<?= CHtml::activeId($model, "vnd_car_year") ?>').val());
        $('#<?= CHtml::activeId($model, "vnd_car_number1") ?>').val($('#<?= CHtml::activeId($model, "vnd_car_number") ?>').val());
        $('#<?= CHtml::activeId($model, "vnd_driver_name1") ?>').val($('#<?= CHtml::activeId($model, "vnd_driver_name") ?>').val());
        $('#<?= CHtml::activeId($model, "vnd_driver_license1") ?>').val($('#<?= CHtml::activeId($model, "vnd_driver_license") ?>').val());
        $('#vendorForm').submit();

    }

    function vencardetails() {
        if ((carCount + 1) >= parseInt(totalCount) && (driverCount + 1) >= parseInt(totalCount)) {
            $('#vendorMultiCarNxtDiv').find('button').text('Finish');
        }

        if (vndCarModel == '') {
            vndCarModel = $('#carmodel').val();
        } else {
            vndCarModel = vndCarModel + ',' + $('#carmodel').val();
            console.log('car model:- ' + vndCarModel);
        }
        if (vndCarYear == '') {
            vndCarYear = $('#caryear').val();
        } else {
            vndCarYear = vndCarYear + ',' + $('#caryear').val();
        }
        if (vndCarNumber == '') {
            vndCarNumber = $('#carnumber').val();
        } else {
            vndCarNumber = vndCarNumber + ',' + $('#carnumber').val();
        }
        if (vndDriverName == '') {
            vndDriverName = $('#drivername').val();
        } else {
            vndDriverName = vndDriverName + ',' + $('#drivername').val();
        }
        if (vndDriverLicence == '') {
            vndDriverLicence = $('#driverlicense').val();
        } else {
            vndDriverLicence = vndDriverLicence + ',' + $('#driverlicense').val();
        }


        $('#carmodel').parent().find('.help-block').remove();
        $('#carmodel').parent().find('.select2-container a').css('border-color', '#999');
        $('#caryear,#carnumber,#drivername,#driverlicense').parent().removeClass('has-error');
        $('#caryear,#carnumber,#drivername,#driverlicense').parent().find('.help-block').css('display', 'none');
        $('#caryear,#carnumber,#drivername,#driverlicense').parent().find('.help-block').text('');

        $('#<?= CHtml::activeId($model, "vnd_car_model1") ?>').val(vndCarModel);
        $('#<?= CHtml::activeId($model, "vnd_car_year1") ?>').val(vndCarYear);
        $('#<?= CHtml::activeId($model, "vnd_car_number1") ?>').val(vndCarNumber);
        $('#<?= CHtml::activeId($model, "vnd_driver_name1") ?>').val(vndDriverName);
        $('#<?= CHtml::activeId($model, "vnd_driver_license1") ?>').val(vndDriverLicence);
        if (carCount === parseInt(totalCount) && driverCount === parseInt(totalCount)) {
            //alert('exit');
            $('#vendorForm').submit();
        } else {
            carCount += 1;
            $('#carmodel').val('');
            $('#carmodel').parent().find('a .select2-chosen').text('Select Cab Type');
            $('#caryear').val('');
            $('#carnumber').val('');
            $('#carcount').text(carCount);
            driverCount += 1;
            $('#drivername').val('');
            $('#driverlicense').val('');
            $('#driverCount').text(carCount);
        }
    }

    function vencarcount()
    {
        if ($('#<?= CHtml::activeId($modelVndPref, "vnp_cars_own") ?>').val() >= 1
                && $('#<?= CHtml::activeId($model, "vnd_cat_type") ?>').val() >= 1)
        {
            if ($('#<?= CHtml::activeId($modelVndPref, "vnp_cars_own") ?>').val() > 1
                    && $('#<?= CHtml::activeId($model, "vnd_cat_type") ?>').val() == '1')
            {
                alert("If you are driver for this car.Please select only one car");
            } else if ($('#<?= CHtml::activeId($modelVndPref, "vnp_cars_own") ?>').val() == '1'
                    && $('#<?= CHtml::activeId($model, "vnd_cat_type") ?>').val() == '1')
            {

                if ($('#<?= CHtml::activeId($model, "vnd_cat_type") ?>').val() == '1')
                {
                    $('#<?= CHtml::activeId($model, "vnd_driver_name") ?>').val($('#<?= CHtml::activeId($model, "first_name") ?>').val() + ' ' + $('#<?= CHtml::activeId($model, "last_name") ?>').val())
                }

                $('#venjoin3').css('display', 'block');
                $('#venjoin2').css('display', 'none');

            } else
            {
                $('#totalcount').text($('#<?= CHtml::activeId($modelVndPref, "vnp_cars_own") ?>').val());
                totalCount = $('#<?= CHtml::activeId($modelVndPref, "vnp_cars_own") ?>').val();
                if ($('#<?= CHtml::activeId($model, "vnd_cat_type") ?>').val() == '1')
                {
                    $('#<?= CHtml::activeId($model, "vnd_driver_name") ?>').val($('#<?= CHtml::activeId($model, "first_name") ?>').val() + ' ' + $('#<?= CHtml::activeId($model, "last_name") ?>').val())
                }

                $('#venjoin4').css('display', 'block');
                $('#driverjoin').css('display', 'none');
                $('#venjoin2').css('display', 'none');

                if ($('#<?= CHtml::activeId($modelVndPref, "vnp_cars_own") ?>').val() == '1')
                {
                    $('#vendorMultiCarNxtDiv').find('button').text('Finish');
                }
            }
        } else
        {
            alert("Please select at least one car and driver");
        }
    }

    /**
     * This function holds the car details
     * @returns {undefined}
     */
    let arrCarDetails = [];
    function addCarDetails()
    {
        let carCount = parseInt($('#<?= CHtml::activeId($modelVndPref, "vnp_cars_own") ?>').val());

        let carModel = $('#carmodel').val();
        let carYear = $('#caryear').val();
        let carNumber = $('#carnumber').val();

        let temp = {};

        temp.carModel = carModel;
        temp.carYear = carYear;
        temp.carNumber = carNumber;

        arrCarDetails.push(temp);

        let currentCount = parseInt($("#carcount").text());
        console.log(arrCarDetails);

        if (carCount === currentCount)
        {
            alert("Stored all car details. Click Finish to complete your registration");
            $('#vendorMultiCarNxtDiv').find('button').prop("onclick", null).off("click");
            $('#vendorMultiCarNxtDiv').find('button').text('Finish');
            $('#vendorMultiCarNxtDiv').find('button').on("click", createVendor);

        } else
        {
            alert("Stored. Add another car details");
            $("#carcount").text(currentCount + 1);
        }
    }

    /**
     * This function is used for final submission of the vendor registration
     * @returns {undefined}
     */
    function createVendor()
    {
        let drvLicense = $("#Vendors_vnd_driver_license").val();
        if (drvLicense)
        {
            $("#vendorMultiCarNxtDiv").find('button').attr("disabled", true);
            $('#vendorMultiCarNxtDiv').find('button').text('Proccessing request...');

            $("#finalsubmit").attr("disabled", true);
            $('#finalsubmit').text('Proccessing request...');
            //debugger
            //console.log(1);
            let fName = $("#Vendors_first_name").val();
            let lName = $("#Vendors_last_name").val();
            let phoneNumber = $("#ContactPhone_phn_phone_no").val();
            let emailId = $("#ContactEmail_eml_email_address").val();
            let city = $("#Vendors_vnd_city").val();

            if (arrCarDetails.length === 0)
            {
                let temp1 = {};

                temp1.carModel = $('#<?= CHtml::activeId($model, "vnd_car_model") ?>').val();
                temp1.carYear = $('#<?= CHtml::activeId($model, "vnd_car_year") ?>').val().trim();
                temp1.carNumber = $('#<?= CHtml::activeId($model, "vnd_car_number") ?>').val().trim();

                arrCarDetails.push(temp1);
            }

            let temp = {};
            if (parseInt($('#<?= CHtml::activeId($model, "vnd_cat_type") ?>').val()) === 2)
            {
                temp.isDco = 0;
            } else
            {
                temp.isDco = 1;
                temp.driverName = $('#<?= CHtml::activeId($model, "vnd_driver_name") ?>').val().trim();
                temp.driverLicenseNo = $('#<?= CHtml::activeId($model, "vnd_driver_license") ?>').val().trim();
            }

            temp.socialLoginUserId = $("#socialuserid").val();
            temp.contactId = $("#contactId").val();
            temp.isDriver = $("#isDriver").val();
            temp.fName = fName.trim();
            temp.lName = lName.trim();
            temp.countryCode = $('#countryCode').val();
            temp.phoneNumber = phoneNumber.trim();
            temp.emailId = emailId.trim();
            temp.cityId = city;
            temp.carOwnCount = $('#<?= CHtml::activeId($modelVndPref, "vnp_cars_own") ?>').val();
            temp.carDetails = arrCarDetails;

            let url = "<?= Yii::app()->createUrl('Vendor/registerVendor') ?>";
            $("#dataMapping").val(JSON.stringify(temp));

            $.ajax
                    ({
                        "type": "POST",
                        "dataType": "json",
                        "url": url,
                        "data": $("#vendorForm").serialize(),
                        "success": function (response)
                        {
                            //console.log(response);
                            if (response.success)
                            {
                                alert(response.message);
                                window.location.reload();
                            } else
                            {
                                alert(response.errors);
                                $("#vendorMultiCarNxtDiv").find('button').attr("disabled", false);
                                $('#vendorMultiCarNxtDiv').find('button').text('Finish');

                                $("#finalsubmit").attr("disabled", false);
                                $('#finalsubmit').text('Finish');
                            }
                        },
                        "error": function (error)
                        {
                            alert(error);
                        }
                    });
        } else
        {
            alert("Please select your driving license field");
            return false;
        }
    }



    /**
     * This function opens social login modal
     * @returns {undefined}
     */
    function loadSocialLogin()
    {
        var socialLogin =
                "<div class='panel'>\n\
			<div class='panel panel-body'>\
				<div class='row'>\
					<div class='col-xs-12 col-md-5 fbook-btn mt20'>\
						<a href='#' style='width:100%;text-align:center;margin: auto;'>\n\
							<span class='btn btn-xs btn-social btn-facebook pl5 pr5' onclick='socailSigin(\"facebook\");'>\n\
								<i class='fa fa-facebook pr5' style='font-size: 22px;'></i> Login with Facebook\n\
							</span>\n\
						</a> \n\
					</div>\
					<div class='col-xs-12 col-md-2 mt20' style='text-align:center;'>--OR--</div>\
						<div class='col-xs-12 col-md-5 google-btn mt20'>\
							<a href='#' style='width:100%;text-align:center;margin: auto;'>\n\
								<span class='btn btn-xs btn-social btn-googleplus pr5' onclick='socailSigin(\"google\");'>\n\
									<img src='/images/google_icon.png'> Login with Google\n\
								</span>\n\
							</a>\n\
						</div>\
					</div>\n\
				</div>\n\
				<div class='row'>\
					<div class='col-xs-12 col-md-5 fbook-btn mt20'>\
						<p id='divShowAlert'></p>\
					</div>\
				</div>\n\
			</div>\n\
		</div>";

        socialBox = bootbox.dialog(
                {
                    message: socialLogin,
                    title: "Social Login",
                    size: 'small',
                    onEscape: function ()
                    {
                        // user pressed escape
                    },
                });
    }

    /**
     * @deprecated 26-11-2019
     * @returns {undefined}
     */
    function vendorjoinvalidationajax()
    {
        if (isNaN($('#<?= CHtml::activeId($modelContPhone, "phn_phone_no") ?>').val()) == false)
        {
            $href = '<?= Yii::app()->createUrl('index/vendorjoinvalidation') ?>';
            jQuery.ajax({type: 'GET', url: $href,
                data: {'FName': $('#Vendors_first_name').val(), 'LName': $('#Vendors_last_name').val(), 'CompanyName': "", 'Phone': $('#ContactPhone_phn_phone_no').val(), 'Email': $('#ContactEmail_eml_email_address').val(), 'City': $('#Vendors_vnd_city').val()},
                success: function (data)
                {
                    if (data === '[]')
                    {
                        loadSocialLogin();
                    } else
                    {
                        console.log(data);
                        var data1 = JSON.parse(data);
                        if ($('#<?= CHtml::activeId($model, "first_name") ?>').val() == '')
                        {
                            $('#<?= CHtml::activeId($model, "first_name") ?>').parent().addClass('has-error');
                            $('#<?= CHtml::activeId($model, "first_name") ?>').parent().find('.help-block').css('display', 'block');
                            $('#<?= CHtml::activeId($model, "first_name") ?>').parent().find('.help-block').text(data1.Vendors_first_name[0]);
                        }
                        if ($('#<?= CHtml::activeId($model, "last_name") ?>').val() == '')
                        {
                            $('#<?= CHtml::activeId($model, "last_name") ?>').parent().addClass('has-error');
                            $('#<?= CHtml::activeId($model, "last_name") ?>').parent().find('.help-block').css('display', 'block');
                            $('#<?= CHtml::activeId($model, "last_name") ?>').parent().find('.help-block').text(data1.Vendors_last_name[0]);
                        }

//						if ((data1.hasOwnProperty('ContactEmail_eml_email_address') && data1.ContactEmail_eml_email_address[0] == 1 && !data1.hasOwnProperty('ContactPhone_phn_phone_no')) || (data1.hasOwnProperty('ContactPhone_phn_phone_no') && data1.ContactPhone_phn_phone_no[0] == 1 && !data1.hasOwnProperty('ContactEmail_eml_email_address'))) {
//							$("#VendorOuterDiv").show();
//							$("#VendorOuterDivText").html("<b>This Contact Information is already exist.Please check</b>"); 
//							return false;
//                        }

                        if ((data1.hasOwnProperty('ContactEmail_eml_email_address') && data1.ContactEmail_eml_email_address[0] == 1) || (data1.hasOwnProperty('ContactPhone_phn_phone_no') && data1.ContactPhone_phn_phone_no[0] == 1)) {
                            $("#VendorOuterDiv").show();
                            $("#VendorOuterDivText").html("<b>This Contact Information is already exist.Please check</b>");
                            return false;
                        } else {
                            $("#VendorOuterDiv").hide();
                            $("#VendorOuterDivText").html('');
                        }

                        if ($('#<?= CHtml::activeId($modelContPhone, "phn_phone_no") ?>').val() == '' || data1.hasOwnProperty('ContactPhone_phn_phone_no')) {
                            $('#<?= CHtml::activeId($modelContPhone, "phn_phone_no") ?>').parent().addClass('has-error');
                            $('#<?= CHtml::activeId($modelContPhone, "phn_phone_no") ?>').parent().find('.help-block').css('display', 'block');
                            $('#<?= CHtml::activeId($modelContPhone, "phn_phone_no") ?>').parent().find('.help-block').text(data1.ContactPhone_phn_phone_no[0]);
                        }

                        if ($('#<?= CHtml::activeId($modelContEmail, "eml_email_address") ?>').val() == '' || data1.hasOwnProperty('ContactEmail_eml_email_address')) {
                            $('#<?= CHtml::activeId($modelContEmail, "eml_email_address") ?>').parent().addClass('has-error');
                            $('#<?= CHtml::activeId($modelContEmail, "eml_email_address") ?>').parent().find('.help-block').css('display', 'block');
                            $('#<?= CHtml::activeId($modelContEmail, "eml_email_address") ?>').parent().find('.help-block').text(data1.ContactEmail_eml_email_address[0]);
                        }
                        if ($('#<?= CHtml::activeId($model, "vnd_city") ?>').val() == '') {
                            if ($('#<?= CHtml::activeId($model, "vnd_city") ?>').parent().hasClass('has-error') == false) {
                                $('#<?= CHtml::activeId($model, "vnd_city") ?>').parent().append('<div class="help-block error"></div>');
                            }
                            $('#<?= CHtml::activeId($model, "vnd_city") ?>').parent().addClass('has-error');
                            $('#<?= CHtml::activeId($model, "vnd_city") ?>').parent().find('.help-block').css('display', 'block');
                            $('#<?= CHtml::activeId($model, "vnd_city") ?>').parent().find('.help-block').text(data1.Vendors_vnd_city[0]);
                        }
                    }
                },
                error: function () {
                    alert('error');
                }
            });
        }
    }

    /**
     * This function is used for verifying the user logged in status
     * @type type
     */
    function socailSigin(socailSigin)
    {
        socailTypeLogin = socailSigin;
        var url = "<?= Yii::app()->createUrl('users/partialsignin') ?>";

        $.ajax(
                {
                    "url": url,
                    "type": "GET",
                    "dataType": "html",
                    "success": function (data)
                    {
                        if (data.search("You are already logged in") == -1)
                        {
                            if (socailSigin == "facebook")
                            {
                                signinWithFB();
                            } else
                            {
                                signinWithGoogle();
                            }
                        } else
                        {
                            var box = bootbox.dialog(
                                    {
                                        message: data,
                                        size: 'large',
                                        onEscape: function ()
                                        {
                                            updateLogin();
                                        }
                                    });
                        }
                    }
                });

        return false;
    }

    function signinWithFB()
    {
        var href = '<?= Yii::app()->createUrl('users/oauth', array('provider' => 'Facebook', 'isFlexxi' => true)); ?>';
        var fbWindow = window.open(href, 'Gozocabs', 'left=20,top=20,width=500,height=500,toolbar=1,resizable=0');
    }

    function signinWithGoogle()
    {
        var href = '<?= Yii::app()->createUrl('users/oauth', array('provider' => 'Google', 'isFlexxi' => true)); ?>';
        var googleWindow = window.open(href, 'Gozocabs', 'left=20,top=20,width=500,height=500,toolbar=1,resizable=0');

    }

    function updateLogin()
    {
        $href = '<?= Yii::app()->createUrl('users/refreshuserdata') ?>';
        jQuery.ajax(
                {
                    type: 'get',
                    url: $href,
                    "dataType": "json",
                    success: function (data1)
                    {
                        if (data1.userData.usr_email == "")
                        {
                            socailTypeLogin = "";
                            if (socailTypeLogin == "facebook")
                            {
                                signinWithFB();
                            } else
                            {
                                signinWithGoogle();
                            }
                        } else
                        {

                            validateUser(data1.userData.usr_email);
                        }

                    }
                });
    }

    /**
     * This function is used for validating the user
     * @param {type} email
     * @returns {undefined}
     */
    function validateUser(email)
    {
       
		var emailData = $("#ContactEmail_eml_email_address").val();
        let conEmail = (email !== 'undefined')?email + "," + emailData:emailData;
        $href = '<?= Yii::app()->createUrl('contact/verifyContactId') ?>';
        jQuery.ajax(
                {
                    type: 'get',
                    url: $href,
                    "dataType": "json",
                    "data":
                            {
                                "email": conEmail
                            },
                    success: function (response)
                    {
                        console.log(response);

                        if (response.success)
                        {
                            let data = response.data;
                            let userId = data.userId;


                            if (data.contactDetail.length > 0)
                            {
                                let contactDetail = data.contactDetail;
                                for (let index = 0; index < contactDetail.length; index++)
                                {
                                    //Check for deactivated account
                                    if (typeof contactDetail[index].cr_is_vendor !== "undefined")
                                    {
                                        $("#divShowAlert").html("You are already linked as a vendor. For any assistance, please contact our help desk to activate your account");
                                    } else
                                    {
                                        $("#contactId").val(contactDetail[index].eml_contact_id);
                                        if (typeof contactDetail[index].cr_is_driver !== "undefined")
                                        {
                                            $("#isDriver").val(1);
                                        }

                                        $('#socialuserid').val(userId);
                                        $('#venjoin2').css('display', 'block');
                                        $('#venjoin1').css('display', 'none');
                                        $("#VendorOuterDiv").hide();
                                        $("#VendorOuterDivText").html('');
                                        socialBox.modal('hide');
                                    }
                                }
                            } else
                            {
                                $('#socialuserid').val(userId);
                                $('#venjoin2').css('display', 'block');
                                $('#venjoin1').css('display', 'none');
                                $("#VendorOuterDiv").hide();
                                $("#VendorOuterDivText").html('');
                                socialBox.modal('hide');
                            }
                        } else
                        {
                            $("#divShowAlert").html("Your social account already linked as a vendor. Please download the vendor app to continue. For any assistance, please contact our help desk");
                            //alert();
                        }
                    }
                });
    }

    function vendorjoinvalidationdetailsajax() {

        var carModel, carYear, carNumber, driverName, driverLicence, isDco;

        if ($('#<?= CHtml::activeId($modelVndPref, "vnp_cars_own") ?>').val() == '1' && $('#<?= CHtml::activeId($model, "vnd_cat_type") ?>').val() == '1') {
            carModel = $('#<?= CHtml::activeId($model, "vnd_car_model") ?>').val();
            carYear = $('#<?= CHtml::activeId($model, "vnd_car_year") ?>').val();
            carNumber = $('#<?= CHtml::activeId($model, "vnd_car_number") ?>').val();
            driverName = $('#<?= CHtml::activeId($model, "vnd_driver_name") ?>').val();
            driverLicence = $('#<?= CHtml::activeId($model, "vnd_driver_license") ?>').val();
            isDco = 1;
            if (carModel == "" || carYear == "" || carNumber == "" || driverName == "" || driverLicence == "") {
                alert("All fields are manadatory");
                return false;
            }
        } else {
            carModel = $('#carmodel').val();
            carYear = $('#caryear').val();
            carNumber = $('#carnumber').val();
            driverName = $('#drivername').val();
            driverLicence = $('#driverlicense').val();
            isDco = 0;
            if (carModel == "" || carYear == "" || carNumber == "") {
                alert("All fields are manadatory");
                return false;
            }
        }
        $href = '<?= Yii::app()->createUrl('index/vendorjoinvalidationdetails') ?>';
        jQuery.ajax({type: 'GET', url: $href,
            data: {'carModel': carModel, 'carYear': carYear, 'carNumber': carNumber, 'driverName': driverName, 'driverLicence': driverLicence, 'is_dco': isDco},
            success: function (data) {
                if (data === "null" || data === '') {
                    if ($('#<?= CHtml::activeId($modelVndPref, "vnp_cars_own") ?>').val() == '1' && $('#<?= CHtml::activeId($model, "vnd_cat_type") ?>').val() == '1') {
                        venderinfo();
                    } else {
                        vencardetails();
                    }
                } else {
                    var data1 = JSON.parse(data);
                    var vhcModel = data1.vehicle_model;
                    var vhcYear = data1.vehicle_year;
                    if ($('#<?= CHtml::activeId($modelVndPref, "vnp_cars_own") ?>').val() == '1' && $('#<?= CHtml::activeId($model, "vnd_cat_type") ?>').val() == '1')
                    {
                        if ($('#<?= CHtml::activeId($model, "vnd_car_model") ?>').val() == '' || data1.hasOwnProperty('Vendors_vnd_car_model')) {
                            $('#<?= CHtml::activeId($model, "vnd_car_model") ?>').parent().append('<div class="help-block error"></div>');
                            $('#<?= CHtml::activeId($model, "vnd_car_model") ?>').parent().addClass('has-error');
                            $('#<?= CHtml::activeId($model, "vnd_car_model") ?>').parent().find('.select2-container a').css('border-color', '#a94442');
                            $('#<?= CHtml::activeId($model, "vnd_car_model") ?>').parent().find('.help-block').css('display', 'block');
                            $('#<?= CHtml::activeId($model, "vnd_car_model") ?>').parent().find('.help-block').text(data1.Vendors_vnd_car_model[0]);
                        }
                        if ($('#<?= CHtml::activeId($model, "vnd_car_year") ?>').val() == '' || data1.hasOwnProperty('Vendors_vnd_car_year')) {
                            $('#<?= CHtml::activeId($model, "vnd_car_year") ?>').parent().addClass('has-error');
                            $('#<?= CHtml::activeId($model, "vnd_car_year") ?>').parent().find('.help-block').css('display', 'block');
                            $('#<?= CHtml::activeId($model, "vnd_car_year") ?>').parent().find('.help-block').text(data1.Vendors_vnd_car_year[0]);
                        }
                        if ($('#<?= CHtml::activeId($model, "vnd_car_number") ?>').val() == '' || data1.hasOwnProperty('Vendors_vnd_car_number')) {
                            $href = '<?= Yii::app()->createUrl('index/vendorjoinvalidationdetails') ?>';
                            jQuery.ajax({type: 'get', url: $href, success: function (data)
                                {
                                    bootbox.confirm({
                                        title: "",
                                        message: "Vehicle Model:" + vhcModel + ", Vehicle Year:" + vhcYear + ".<br> This cab already exist in our system. To Continue please confirm..",

                                        buttons: {
                                            cancel: {
                                                label: '<i class="fa fa-times"></i> Cancel'
                                            },
                                            confirm: {
                                                label: '<i class="fa fa-check"></i> Confirm'
                                            }
                                        },
                                        callback: function (result) {
                                            if (result)
                                            {
                                                venderinfo();
                                            } else {
                                                $('#<?= CHtml::activeId($model, "vnd_car_number") ?>').parent().addClass('has-error');
                                                $('#<?= CHtml::activeId($model, "vnd_car_number") ?>').parent().find('.help-block').css('display', 'block');
                                                $('#<?= CHtml::activeId($model, "vnd_car_number") ?>').parent().find('.help-block').text(data1.Vendors_vnd_car_number[0]);
                                            }
                                        }
                                    });
                                }
                            });

                        }
                        if ($('#<?= CHtml::activeId($model, "vnd_driver_name") ?>').val() == '' || data1.hasOwnProperty('Vendors_vnd_driver_name')) {
                            $('#<?= CHtml::activeId($model, "vnd_driver_name") ?>').parent().addClass('has-error');
                            $('#<?= CHtml::activeId($model, "vnd_driver_name") ?>').parent().find('.help-block').css('display', 'block');
                            $('#<?= CHtml::activeId($model, "vnd_driver_name") ?>').parent().find('.help-block').text(data1.Vendors_vnd_driver_name[0]);
                        }
                        if ($('#<?= CHtml::activeId($model, "vnd_driver_license") ?>').val() == '' || data1.hasOwnProperty('Vendors_vnd_driver_license')) {
                            $('#<?= CHtml::activeId($model, "vnd_driver_license") ?>').parent().addClass('has-error');
                            $('#<?= CHtml::activeId($model, "vnd_driver_license") ?>').parent().find('.help-block').css('display', 'block');
                            $('#<?= CHtml::activeId($model, "vnd_driver_license") ?>').parent().find('.help-block').text(data1.Vendors_vnd_driver_license[0]);
                        }
                    } else {
                        if ($('#carmodel').val() == '' || data1.hasOwnProperty('Vendors_vnd_car_model')) {
                            $('#carmodel').parent().append('<div class="help-block error"></div>');
                            $('#carmodel').parent().addClass('has-error');
                            $('#carmodel').parent().find('.select2-container a').css('border-color', '#a94442');
                            $('#carmodel').parent().find('.help-block').css('display', 'block');
                            $('#carmodel').parent().find('.help-block').text(data1.Vendors_vnd_car_model[0]);
                        }
                        if ($('#caryear').val() == '' || data1.hasOwnProperty('Vendors_vnd_car_year')) {
                            $('#caryear').parent().addClass('has-error');
                            $('#caryear').parent().find('.help-block').css('display', 'block');
                            $('#caryear').parent().find('.help-block').text(data1.Vendors_vnd_car_year[0]);
                        }
                        if ($('#carnumber').val() == '' || data1.hasOwnProperty('Vendors_vnd_car_number')) {
                            $href = '<?= Yii::app()->createUrl('index/vendorjoinvalidationdetails') ?>';
                            jQuery.ajax({type: 'get', url: $href, success: function (data)
                                {
                                    bootbox.confirm({
                                        title: "",
                                        message: "Vehicle Model:" + vhcModel + ", Vehicle Year:" + vhcYear + ".<br> This cab already exist in our system. To Continue please confirm..",

                                        buttons: {
                                            cancel: {
                                                label: '<i class="fa fa-times"></i> Cancel'
                                            },
                                            confirm: {
                                                label: '<i class="fa fa-check"></i> Confirm'
                                            }
                                        },
                                        callback: function (result) {
                                            if (result)
                                            {
                                                vencardetails();
                                            } else {
                                                $('#carnumber').parent().addClass('has-error');
                                                $('#carnumber').parent().find('.help-block').css('display', 'block');
                                                $('#carnumber').parent().find('.help-block').text(data1.Vendors_vnd_car_number[0]);
                                            }
                                        }
                                    });
                                }
                            });
                        }
                        if ($('#drivername').val() == '' || data1.hasOwnProperty('Vendors_vnd_driver_name')) {
                            $('#drivername').parent().addClass('has-error');
                            $('#drivername').parent().find('.help-block').css('display', 'block');
                            $('#drivername').parent().find('.help-block').text(data1.Vendors_vnd_driver_name[0]);
                        }
                        if ($('#driverlicense').val() == '' || data1.hasOwnProperty('Vendors_vnd_driver_license')) {
                            $('#driverlicense').parent().addClass('has-error');
                            $('#driverlicense').parent().find('.help-block').css('display', 'block');
                            $('#driverlicense').parent().find('.help-block').text(data1.Vendors_vnd_driver_license[0]);
                        }
                    }
                    return false;
                }
                console.log(data);
            },
            error: function () {
                alert('error');
            }
        });
    }

    function opentns() {
        $href = '<?= Yii::app()->createUrl('index/termsvendor') ?>';
        jQuery.ajax({type: 'GET', url: $href,
            success: function (data) {
                box = bootbox.dialog({
                    message: data,
                    title: '',
                    size: 'large',
                    onEscape: function () {
                        box.modal('hide');
                    }
                });
            }
        });
    }

    function validateCheckHandlerss() {
        if ($('#email').val() != "") {
            var pattern = /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/
            var retVal = pattern.test($('#email').val());
            if (retVal == false)
            {
                $('#errId').html("The email address you have entered is invalid.");
                return false;
            } else
            {
                $('#errId').html("");
                return true;
            }
        }
        return true;

    }

    $sourceList = null;
    function populateSource(obj, cityId) {

        obj.load(function (callback) {
            var obj = this;
            if ($sourceList == null) {
                xhr = $.ajax({
                    url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery', ['apshow' => 0, 'city' => ''])) ?>' + cityId,
                    dataType: 'json',
                    data: {
                        // city: cityId
                    },
                    //  async: false,
                    success: function (results) {
                        $sourceList = results;
                        obj.enable();
                        callback($sourceList);
                        obj.setValue(cityId);
                    },
                    error: function () {
                        callback();
                    }
                });
            } else {
                obj.enable();
                callback($sourceList);
                obj.setValue(cityId);
            }
        });
    }
    function loadSource(query, callback) {
        //	if (!query.length) return callback();
        $.ajax({
            url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery')) ?>?apshow=0&q=' + encodeURIComponent(query),
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

    $('#<?= CHtml::activeId($model, 'vnd_car_number') ?>,#carnumber').change(function () {
        var carnumber = $('#<?= CHtml::activeId($model, 'vnd_car_number') ?>').val();
        var carnumber1 = $('#carnumber').val();
        var result;
        var patt1 = /^[A-Za-z]{2}(?: \s)?(?: \s*)?[0-9]{1,2}(?:\s)?(?:\s*)?(?:[A-Za-z])?(?:[A-Za-z]*)?(?:\s)?(?:\s*)?[0-9]{4}$/;
        if (carnumber != '')
        {
            result = carnumber.match(patt1);
            if (result == null || result == '')
            {
                $('#<?= CHtml::activeId($model, "vnd_car_number") ?>').parent().addClass('has-error');
                $('#<?= CHtml::activeId($model, "vnd_car_number") ?>').parent().find('.help-block').css('display', 'block');
                $('#<?= CHtml::activeId($model, "vnd_car_number") ?>').parent().find('.help-block').text('Please provide valid Car Registration number');
                $('#<?= CHtml::activeId($model, "vnd_car_number") ?>').val('');

            } else
            {
                var a, b, c, d, e;
                a = carnumber.substring(0, 2);
                b = carnumber.substring((carnumber.length - 4), carnumber.length);
                c = carnumber.substring(2, (carnumber.length - 4));
                c = c.trim();
                if (c.length > 2)
                {
                    d = c.substring(0, 2);
                    e = c.substring(2, c.length);
                    e = e.trim();
                    carnumber = a + " " + d + " " + e + " " + b;
                } else
                {
                    carnumber = a + " " + c + " " + b;
                }
                $('#<?= CHtml::activeId($model, "vnd_car_number") ?>').val(carnumber);
            }
        } else if (carnumber1 != '')
        {
            result = carnumber1.match(patt1);
            if (result == null || result == '')
            {
                $('#carnumber').parent().addClass('has-error');
                $('#carnumber').parent().find('.help-block').css('display', 'block');
                $('#carnumber').parent().find('.help-block').text('Please provide valid Car Registration number');
                $('#carnumber').val('');
            } else
            {
                var a, b, c, d, e;
                a = carnumber.substring(0, 2);
                b = carnumber.substring((carnumber.length - 4), carnumber.length);
                c = carnumber.substring(2, (carnumber.length - 4));
                c = c.trim();
                if (c.length > 2)
                {
                    d = c.substring(0, 2);
                    e = c.substring(2, c.length);
                    e = e.trim();
                    carnumber = a + " " + d + " " + e + " " + b;
                } else
                {
                    carnumber = a + " " + c + " " + b;
                }
                $('#carnumber').val(carnumber1);
            }
        }
    });

    function clearAll() {
        $("input[type='text']").val("");
        $("input[type='hidden'").val("");
        $('#venjoin1').css('display', 'block');
        $('#venjoin2').css('display', 'none');
        $('#venjoin3').css('display', 'none');
        $('#venjoin4').css('display', 'none');
        $('#countryCode').val('91');
    }
</script>
