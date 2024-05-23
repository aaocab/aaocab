<?php
/* @var $form TbActiveForm */
$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
	'id'					 => 'travellerInfoForm', 'enableClientValidation' => true,
	'clientOptions'			 => array(
		'validateOnSubmit'	 => true,
		'errorCssClass'		 => 'has-error',

		'afterValidate'		 => 'js:function(form,data,hasError){
                if(!hasError){
                  

					if(!admBooking.validateTravellerInfo())
					{
                        return false;                         
					}
              
                    $.ajax({
                    "type":"POST",
                    "dataType":"HTML",
                    async: false,
                    "url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('admin/booking/travellerInfo')) . '",
                    "data":form.serialize(),
                    "beforeSend": function () {
                       
                        ajaxindicatorstart("");

                    },
                    "complete": function () {                     
                        ajaxindicatorstop();
                    },
                    "success":function(data1){
				
						$("#bkErrors").addClass("hide");
						$(".btn-travellerInfo").removeClass("btn-info");
						
						
						
                        $(".btn-travellerInfo").addClass("disabled");
						$("#travellerInfo").find("input,textarea").attr("disabled",true);
						$("#travellerInfo").find(".selectize-control").addClass("disabled");
						if($("#reAddress").html() == "")
						{
							$("#additionalInfo").html(data1);
							$("#additionalInfo").removeClass("hide");
							$(document).scrollTop($("#additionalInfo").offset().top);
						}
						else
						{
							$("#payment").html("");
							$("#payment").addClass("hide");
							$("#rePayment").html(data1);
							$("#rePayment").removeClass("hide");
							$("#addOnDiv").hide();
							$("#addOnCabDiv").hide();
							$(document).scrollTop($("#rePayment").offset().top);
						}
						$(".btn-editTravellerInfo").removeClass("hide");
                        },
                     error: function(xhr, status, error){
                      }
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
		'onkeydown'	 => "return event.key != 'Enter';",
		'class'		 => '',
	),
		));


?>
<?= CHtml::hiddenField("jsonData_travellerInfo", $data, ['id' => 'jsonData_travellerInfo']) ?>
<div class="row">
	<div class="col-xs-12">
		<div class="panel panel-default panel-border">
			<span class="edit-block btn-editTravellerInfo hide"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span>
			<h3 class="pl15">Traveler Information</h3>
			<div class="panel-body pt0">
				<div class="row">
                    <div class="col-sm-8">
						<div class="row mb15">
							<div class="col-sm-4">
								<label class="checkbox-inline pt0 pl0">
									<input type="radio" name="bookingStat" value="own" checked="checked"> I am travelling
								</label>
							</div>
							<div class="col-sm-4">
								<label class="checkbox-inline pt0 pl0">
									<input type="radio" name="bookingStat" value="other"> Somebody else travelling
								</label>
							</div>
						</div>
                    </div>
					<div class="col-sm-6">
						<div class="row">
							<div class="col-sm-4">
								<div class="form-group">
									<label>Country Code</label>
									<?php
									$this->widget('ext.yii-selectize.YiiSelectize', array(
										'model'				 => $usrModel,
										'attribute'			 => 'bkg_country_code1',
										'useWithBootstrap'	 => true,
										"placeholder"		 => "Code",
										'fullWidth'			 => false,
										'htmlOptions'		 => array(
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
												 obj.setValue('{$usrModel->bkg_country_code1}');
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
								</div>  
							</div>
							<div class="col-sm-8"> 
								<?= $form->textFieldGroup($usrModel, 'bkg_contact_no1', array('label' => "Phone", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Phone', 'id' => 'fullContactNumber')))) ?>
							</div>
						</div>
					</div>
					<!--					<div class="col-sm-6">
											<label class="control-label" for="exampleInputName6">Contact Number</label>
											<div class="row">
												<div class="col-xs-12">
					<?php
//								$this->widget('ext.intlphoneinput.IntlPhoneInput', array(
//									'model'					 => $usrModel,
//									'attribute'				 => 'bkg_contact_no1',
//									'codeAttribute'			 => 'bkg_country_code1',
//									'numberAttribute'		 => 'bkg_contact_no1',
//									'options'				 => array(// optional
//										'separateDialCode'	 => true,
//										'autoHideDialCode'	 => true,
//										'initialCountry'	 => 'in'
//									),
//									'htmlOptions'			 => ['class' => 'form-control admin-ph', 'id' => 'fullContactNumber'],
//									'localisedCountryNames'	 => false, // other public properties
//								));
					?> 
					<?php //echo $form->error($usrModel, 'bkg_country_code1'); ?>
					<?php //echo $form->error($usrModel, 'bkg_contact_no1'); ?>
												</div>
											</div>
										</div>-->
					<div class="col-sm-6">
						<?= $form->emailFieldGroup($usrModel, 'bkg_user_email1', array('label' => 'Email', 'widgetOptions' => array('htmlOptions' => array()))); ?>
						<div id="errordivemail" style="color:#da4455"></div>
						<input type="hidden" name="YII_CSRF_TOKEN"  >  
						<input type="hidden" name ="isBlockedLocation" id ="isBlockedLocation">
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6 mt15">
						<?= $form->textFieldGroup($usrModel, 'bkg_user_fname1', array('label' => "First Name", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'First Name', 'class' => 'nameFilterMask')))) ?>
					</div>
					<div class="col-sm-6 mt15">
						<?= $form->textFieldGroup($usrModel, 'bkg_user_lname1', array('label' => 'Last Name', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Last Name', 'class' => 'nameFilterMask')))) ?>
					</div>
				</div><br>
				<h3 class="loc-head hide">What's your pickup and drop location? <span class="loc-head text-danger hide text-sm custInfo" style="font-weight: normal">(Customer will need to give us his pickup and drop address using auto-complete right before he is making payment. )</span></h3>
				<div id="reAddress" class="row">
				</div>
				<div id="gnowAddressErr" class="text-danger"></div>
				<div class="row">
					<div class="col-sm-12 text-center">
						<button type='button' class='btn btn-info btn-travellerInfo pl20 pr20'>Next</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $this->endWidget(); ?>

<script>
	var jsonData = JSON.parse($('#jsonData_travellerInfo').val());
	var isMultiAirport = 0;
	$(document).ready(function ()
	{
		
		
		$('input[name=YII_CSRF_TOKEN]').val("<?php echo $this->renderDynamicDelay('Filter::getToken'); ?>");
	
		
		
		//$('input[name=YII_CSRF_TOKEN]').val('" . $this->renderDynamicDelay('Filter::getToken') . "');
		var cntBrt = jsonData.BookingRoute.length;
		var cntVal = cntBrt - 1;
		$('#reAddress').html("");
		if (((jsonData.BookingRoute[0].brt_from_latitude == '' || jsonData.BookingRoute[0].brt_from_latitude == undefined) && (jsonData.BookingRoute[0].brt_from_longitude == '' || jsonData.BookingRoute[0].brt_from_longitude == undefined)) || ((jsonData.BookingRoute[cntVal].brt_to_latitude == '' || jsonData.BookingRoute[cntVal].brt_to_latitude == undefined) && (jsonData.BookingRoute[cntVal].brt_to_longitude == '' || jsonData.BookingRoute[cntVal].brt_to_longitude == undefined)))
		{
			var bookingLength = jsonData.BookingRoute.length;
			if (jsonData.bkg_booking_type == 1 || jsonData.bkg_booking_type == 9 || jsonData.bkg_booking_type == 10 || jsonData.bkg_booking_type == 11 || (jsonData.bkg_booking_type == 8 && bookingLength == 2))
			{
				$('.loc-head').removeClass('hide');
				admBooking.getAutoAddressBox(jsonData.bkg_booking_type, 'traveller');
			} else if (jsonData.bkg_booking_type != 4)
			{
				isMultiAirport = 1;
				$('.loc-head').removeClass('hide');
				admBooking.updateMulticity(jsonData.multicityAutoComData, jsonData.multicityAutoComTot, jsonData, hyperModel, 'traveller');
			}
		}

		if (isMultiAirport == 0 && jsonData.bkg_booking_type == 3) {
			$('.loc-head').removeClass('hide');
			admBooking.updateMulticity(jsonData.multicityAutoComData, jsonData.multicityAutoComTot, jsonData, hyperModel, 'traveller');
		}

		var $radiobtn = $("input:radio[name=bookingStat]");
		if ($("input:radio[name=bookingStat]:checked").val() == 'own')
		{
			if ($("#fullContactNumber").val() != "")
			{
				$('#fullContactNumber').attr('readonly', true);
				$("#travellerInfo").find(".selectize-control").addClass("disabled");
			}
			if ($("#BookingUser_bkg_user_email1").val() != "")
			{
				$('#BookingUser_bkg_user_email1').attr('readonly', true);
			}
			if ($("#BookingUser_bkg_user_fname1").val() != "")
			{
				$('#BookingUser_bkg_user_fname1').attr('readonly', true);
			}
			if ($("#BookingUser_bkg_user_lname1").val() != "")
			{
				$('#BookingUser_bkg_user_lname1').attr('readonly', true);
			}
		}
		$radiobtn.on("change", function () {

			if ($(this).val() == 'other') {

				$('#fullContactNumber').attr('readonly', false);
				$("#travellerInfo").find(".selectize-control").removeClass("disabled");
				$('#BookingUser_bkg_user_email1').attr('readonly', false);
				$('#BookingUser_bkg_user_fname1').attr('readonly', false);
				$('#BookingUser_bkg_user_lname1').attr('readonly', false);
			} else
			{
				if ($("#fullContactNumber").val() != "")
				{
					$('#fullContactNumber').attr('readonly', true);
					$("#travellerInfo").find(".selectize-control").addClass("disabled");
				}
				if ($("#BookingUser_bkg_user_email1").val() != "")
				{
					$('#BookingUser_bkg_user_email1').attr('readonly', true);
				}
				if ($("#BookingUser_bkg_user_fname1").val() != "")
				{
					$('#BookingUser_bkg_user_fname1').attr('readonly', true);
				}
				if ($("#BookingUser_bkg_user_lname1").val() != "")
				{
					$('#BookingUser_bkg_user_lname1').attr('readonly', true);
				}
			}
		});
	});


	
		
//		 $('#travellerInfoForm').on('beforeSubmit', function (e) {
//
//       checkAddressForBlock();
//        return true;
//
//       
//
//    });
	$(".btn-travellerInfo").click(function () {
	//	 checkAddressForBlock();
		
		$('#gnowAddressErr').text('');
		if (jsonData.isGozonow == '1' && jsonData.bkg_booking_type == 1 && ($('#loctraveller_0').val() == '' || $('#loctraveller_1').val() == '')) {
			$('#gnowAddressErr').text('This booking requires pickup and drop address');
			return false;
		}
		if (jsonData.isGozonow == '1' && jsonData.bkg_booking_type != 1 && ($('#loctraveller_0').val() == '' )) {
			$('#gnowAddressErr').text('Pickup address is required for Gozo-now booking');
			return false;
		}
		$("#travellerInfoForm").submit();
	});

	$(".btn-editTravellerInfo").click(function () {
		$('#rePayment,#additionalInfo,#vendorIns').html('');
		$('#rePayment,#additionalInfo,#vendorIns').addClass('hide');
		$(".btn-travellerInfo").addClass("btn-info");
		$(".btn-travellerInfo").removeClass("disabled");
		$("#travellerInfo").find("input").attr("disabled", false);
		$("#travellerInfo").find(".selectize-control").removeClass("disabled");
		$(".btn-editTravellerInfo").addClass("hide");
	});

	$('.txtpltraveller').change(function () {
		hyperModel.findAddress(this.id);
	});
	
	function showMessage(isBlock) {
		debugger;

		
bootbox.confirm({
			message: "Are you sure want to confirm this booking ?",
			buttons: {
				confirm: {
					label: 'OK',
					className: 'btn-info'
				},
				cancel: {
					label: 'CANCEL',
					className: 'btn-danger'
				}
			},
			callback: function (result) {
				if (result) {
					debugger;
					 $("#isBlockedLocation").val(isBlock);
					 //finalSubmit();
					//$("#travellerInfoForm").submit();
				}
			}
		});
		
		
		
		
	}
	
	function checkAddressForBlock()
	{
		var blockAddress=0;
			$.ajax({
			"type": "POST",
			"url": "<?= Yii::app()->createUrl("admin/booking/getBlokedLocationData") ?>",
			"dataType": "json",
			"async": false,
			"data": {'jsonData': jsonData,"YII_CSRF_TOKEN": $('input[name="YII_CSRF_TOKEN"]').val()},
			success: function(data)
			{
				alert(data.isBlocked);
				if (data.isBlocked)
				{
					blockAddress = 1; 
					bootbox.confirm({
			message: "Are you sure want to confirm this booking ?",
			buttons: {
				confirm: {
					label: 'OK',
					className: 'btn-info'
				},
				cancel: {
					label: 'CANCEL',
					className: 'btn-danger'
				}
			},
			callback: function (result) {
				if (result) {
					debugger;
					 $("#isBlockedLocation").val(blockAddress);
					
				}
			}
		});
				
						}
				
			}
		});
		return true;
	}
	function finalSubmit()
	{
		alert("fghghgh");
		//$("#travellerInfoForm").submit();
		return;
	}
					
</script>