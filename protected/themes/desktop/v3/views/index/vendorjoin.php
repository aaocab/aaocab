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
<div class="row bg-white m0">
	<div class="col-12 col-lg-8 offset-lg-2">
			<div class="row">
				<div class="col-12 mt10"><img src="/images/banner-join.jpg?v=0.2" alt="" title="" class="img-fluid"></div>
				<div class="col-12 mt30">
					<p class="font-30 merriw">Attach your taxi for local rides</p>
				</div>
				<div class="col-12">
					<div class="row">
						<div class="col-12">
							<div id="VendorOuterDiv" class="col-12">
								<p class="merriw font-20"><span id="VendorOuterDivText"></span></p>
							</div>  
							<div id="VendorInnerDiv">
								<p class="merriw font-18"><b>START YOUR APPLICATION</b> <span class="float-right"><img src="/images/resume.svg" alt="" width="40"></span></p>
								<?php
								$form = $this->beginWidget('CActiveForm', array(
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
											$("#VendorOuterDivText").html("<b>Your application is almost approved.</b><br/> <br/> <a href=\"https://play.google.com/store/apps/details?id=com.aaocab.vendor&hl=en_IN\" target=\"_blank\">Download Gozo partner app – from google play store</a> <br/><br/> Watch this video to sign your vendor agreement and upload your papers  <br/> <br/> <a href=\"https://youtu.be/AfbwgIJN0H0\" target=\"_blank\"> https://youtu.be/AfbwgIJN0H0 <br/></a><br/> You will start receiving business within 48hours of uploading all your papers"); 
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
								/* @var $form CActiveForm */
								?>
								<input type="hidden" value="<?=$platform;?>" id="platform" name="platform" />
								<input type="hidden" value="0" id="socialuserid" name="socialuserid" />
								<input type="hidden" value="0" id="contactId" name="contactId" />
								<input type="hidden" value="0" id="isDriver" name="isDriver" />
								<input type="hidden" id="dataMapping" name="dataToMap"/>
								<input type="hidden" value="<?= $telegramId; ?>" name="telegramId" id="telegramId"/>
								<!-- //Start - Application Form Part 1 -->
								<div id="venjoin1">
									<div class="row">
										<div class="col-12 col-lg-6">
											<label for="name" class="merriw">First name <span class="font-11 text-muted">(as shown on your Driver's License)</span></label>
											<div class="form-group">
												<?= $form->textField($model, 'first_name', array('placeholder' => 'Enter First Name', 'class' => "form-control")) ?>
												<?php echo $form->error($model, 'first_name', ['class' => 'help-block color-red error']); ?>
											</div>
										</div>
									</div>

									<div class="row">
										<div class="col-12 col-lg-6">
											<label for="name" class="merriw">Last name <span class="font-11 text-muted">(as shown on your Driver's License)</span></label>
											<div class="form-group">
												<?= $form->textField($model, 'last_name', array('placeholder' => 'Enter Last Name', 'class' => "form-control")) ?>
												<?php echo $form->error($model, 'last_name', ['class' => 'help-block error']); ?>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-12 col-lg-6">
											<label for="phone" class="merriw">Phone numbers</label>
											<div class="row">
												<div class="col-12 pr0 form-group">
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
														'htmlOptions'			 => ['class' => 'form-control full-width', 'id' => 'fullContactNumber1', 'maxlength' => '10', 'onkeypress' => "return isNumber(event)", 'value' => ''],
														'localisedCountryNames'	 => false, // other public properties
													));
													?> 
												</div>
											</div>
										</div>
										<div class="col-12 col-lg-6">
											<label for="email" class="merriw">Email address</label>
											<div class="form-group">
												<?= $form->textField($modelContEmail, 'eml_email_address', array('placeholder' => 'Enter Email Id', 'class' => "form-control", "required" => true)) ?>
												<?php echo $form->error($modelContEmail, 'eml_email_address', ['class' => 'help-block error']); ?>
											</div>
											<span id="errId" style="color: #F25656"></span>
										</div>
									</div>

									<div class="row">
										<div class="col-12 col-lg-6">
											<label for="city" class="merriw">What city do you do most business in</label>
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
													return '<div><span class=\"\"><img src=\"/images/bxs-map.svg\" alt=\"img\" width=\"20\" height=\"20\">' + escape(item.text) +'</span></div>';
													},
													option_create: function(data, escape){
													return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
													}
												}",
												),
											));
											?>
										</div>
										<div class="col-12 col-lg-6">
											<label for="name" class="merriw">Driver license number</label>
											<div class="form-group">
												<?= $form->textField($model, 'vnd_driver_license', array('placeholder' => 'Driver license number', 'class' => "form-control")) ?>
												<?php echo $form->error($model, 'vnd_driver_license', ['class' => 'help-block error']); ?>
											</div>
											<span id="errId" style="color: #F25656"></span>
										</div>
									</div>

									<div class="row">
										<div class="col-12 top-buffer mb-3">
											<div class="Submit-button" style="text-align: left" id="vendorSubmitDiv">
												<button type="button" class = "btn btn-sm btn-primary text-uppercase hvr-push" onclick = "vencarinfo()" >Finish</button>
											</div>
										</div>
									</div> 
								</div>

								<div id="loading"></div>
								<?php $this->endWidget(); ?>
							</div>
						</div>
								<div class="col-12">
									<div class="card p20">
										<div class="row">
											<div class="col-12 col-lg-9">
												<h2 class="font-30 merriw border-b-gray pb10 mb10">Attach your car into <br>the Gozo Vendor networks</h2>
										<p> If you own or operate a inter-city taxi, <br>then you should join with Gozo.</p>
										<h2 class="font-20 merriw color-blue"><b>Benefits for Gozo vendor partners</b></h2>
										<ul class="pl0 ul-style-c">
											<li><img src="/images/bx-right-arrow-circle.svg" alt="img" width="18" height="18" class="mr10"> Gozo focuses on getting customer demand</li>
											<li><img src="/images/bx-right-arrow-circle.svg" alt="img" width="18" height="18" class="mr10"> You simply provide top quality service </li>
											<li><img src="/images/bx-right-arrow-circle.svg" alt="img" width="18" height="18" class="mr10"> Stay busy in all seasons. Good service = More business</li>
											<li><img src="/images/bx-right-arrow-circle.svg" alt="img" width="18" height="18" class="mr10"> Get great reviews from customers</li>
											<li><img src="/images/bx-right-arrow-circle.svg" alt="img" width="18" height="18" class="mr10"> Gozo sends you payments on-time</li>
											<li><img src="/images/bx-right-arrow-circle.svg" alt="img" width="18" height="18" class="mr10"> Use your Gozo partner and Gozo driver mobile app to keep in continous touch with Gozo</li>
										</ul>
</div>
											<div class="col-12 col-lg-3 d-none d-lg-block" style="margin-top: -31px;"><img src="/images/join_3.png" alt="" width="180"></div>
											<div class="col-12 col-lg-3 font-30 merriw color-blue d-lg-none">
												Inviting DCO's and<br>
												Taxi Operators All Over India
											</div>
</div>
										
									</div>
								</div>
						<div class="col-12">
							<div class="card bg-gray">
								<div class="row m0">
									<div class="col-12 col-lg-4 text-center"><img src="/images/img-2.png" alt="" class="img-fluid ml15 mt15" ></div>
									<div class="col-12 col-lg-8">
										<p class="mt-3 mb10 font-30 merriw">Any questions? <br>Contact our Vendor Relations team</p>
										<p class="font-16"><img src="/images/bx-envelope.svg" alt="img" width="18" height="18" class="mr10"> <a href="mailto:vendors@aaocab.in" class="color-blue">vendors@aaocab.in</a></p>
										<p><img src="/images/bx-paper-plane.svg" alt="img" width="18" height="18" class="mr10"><a href="https://t.me/aaocab" class="color-blue"> Join the aaocab channel on telegram</a></p>
									</div>
								</div>
							</div>
						</div>
						<div class="col-12 mt-3 mb-3">
							<h4 class="card-title merriw text-center">Click the link below for YouTube Videos (Hindi Version)</h4>
							<div class="row">
								<div class="col-12 col-xl-3">
									<div class="row">
										<div class="col-12"><iframe width="100%" height="100" src="http://www.youtube.com/embed/3T12L7XWnyo" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>
										<div class="col-12 text-center mb5"><a href="http://www.youtube.com/watch?v=3T12L7XWnyo&&list=PLtO3n8NwlGMQsJ_KDX9hyHOZqqlX0uo-5&&index=5" target="_blank" class="color-black">Attach your cab & upload your documents</a></div>
									</div>
								</div>
								<div class="col-12 col-xl-3">
									<div class="row">
										<div class="col-12"><iframe width="100%" height="100" src="http://www.youtube.com/embed/AfbwgIJN0H0" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>
										<div class="col-12 text-center mb5"><a href="http://www.youtube.com/watch?v=AfbwgIJN0H0&&list=PLtO3n8NwlGMQsJ_KDX9hyHOZqqlX0uo-5&&index=11" target="_blank" class="color-black">Vendor Registration and documents Upload</a></div>
									</div>
								</div>
								<div class="col-12 col-xl-3">
									<div class="row">
										<div class="col-12"><iframe width="100%" height="100" src="http://www.youtube.com/embed/4630FwpTMsE" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>
										<div class="col-12 text-center mb5"><a href="http://www.youtube.com/watch?v=4630FwpTMsE&&list=PLtO3n8NwlGMQsJ_KDX9hyHOZqqlX0uo-5&&index=33" target="_blank" class="color-black">How to Add CAB, Upload DOCs, Sign the LOU</a></div>
									</div>
								</div>
								<div class="col-12 col-xl-3">
									<div class="row">
										<div class="col-12"><iframe width="100%" height="100" src="http://www.youtube.com/embed/etKRxPYYjLw" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>
										<div class="col-12 text-center mb5"><a href="http://www.youtube.com/watch?v=etKRxPYYjLw&&list=PLtO3n8NwlGMQsJ_KDX9hyHOZqqlX0uo-5&&index=35" target="_blank" class="color-black">Partner App - Full Vendor App Functionality</a></div>
									</div>
								</div>
<div class="col-12 mt10 text-right pr0"><a href="/partners-testimonials" class="btn btn-primary btn-sm mr-1 mb-1">Click here for more video</a></div>
							</div>
						</div>
							
						<div class="col-12 relative-text mb-3">
							<div class="join-b-first"><img src="/images/banner-join2.jpg" alt="" class="img-fluid"></div>
							<div class="join-b-second merriw color-white">
								Travel Agents,<br>
Hotel owners, Shopkeepers... <br>
Join our travel partner<br>
network<br>
<a href="/agent/join" class="btn btn-sm btn-primary text-uppercase hvr-push mt10">Join Here</a>
</div>
						</div>
<!--							<div class="col-12 mt10">
								<div class="card">
									<div class="card-body">
										<p class="font-20 merriw">Watch, what our Partners are saying about aaocab...</p>

										<div class="row">
											<div class="col-12 col-lg-2">
												<div class="card">
													<div class="card-body p0">
														<div class="row">
															<div class="col-12 text-center mb5"><b>Vipul Agarwal</b></div>
															<div class="col-12"><iframe width="100%" height="100" src="http://www.youtube.com/embed/eACThcHWMtw" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>
														</div>
													</div>
												</div>
											</div>
											<div class="col-12 col-lg-2">
												<div class="card">
													<div class="card-body p0">
														<div class="row">
															<div class="col-12 text-center mb5"><b>Raigonda Pujari</b></div>
															<div class="col-12"><iframe width="100%" height="100" src="http://www.youtube.com/embed/AKOjm5K9MaM" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>
														</div>
													</div>
												</div>
											</div>
											<div class="col-12 col-lg-2">
												<div class="card">
													<div class="card-body p0">
														<div class="row">
															<div class="col-12 text-center mb5"><b>Sameer Sheikh</b></div>
															<div class="col-12"><iframe width="100%" height="100" src="http://www.youtube.com/embed/IQQQCOhw4lE" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>
														</div>
													</div>
												</div>
											</div>
											<div class="col-12 col-lg-2">
												<div class="card">
													<div class="card-body p0">
														<div class="row">
															<div class="col-12 text-center mb5"><b>Mr. Krishna</b></div>
															<div class="col-12"><iframe width="100%" height="100" src="http://www.youtube.com/embed/3JdKhRQA_Q4" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>
														</div>
													</div>
												</div>
											</div>
											<div class="col-12 col-lg-2">
												<div class="card">
													<div class="card-body p0">
														<div class="row">
															<div class="col-12 text-center mb5"><b>Sunil Kumar</b></div>
															<div class="col-12"><iframe width="100%" height="100" src="http://www.youtube.com/embed/Jw2Q-dgDapI" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>
														</div>
													</div>
												</div>
											</div>
											<div class="col-12 col-lg-2">
												<div class="card">
													<div class="card-body p0">
														<div class="row">
															<div class="col-12 text-center mb5"><b>Mr. Kartik</b></div>
															<div class="col-12"><iframe width="100%" height="100" src="http://www.youtube.com/embed/HlEziADtJHY" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>-->
							
						</div>
					</div>
				</div>
			</div>
	</div>

<!--modal for win a day end-->
<div id="socialBoxDetails" class="modal fade bd-example-modal-lg" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header pb5 pt5">
			    <h4 class="modal-title" id="socialBoxModalLabel">Social Login</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
            <div class="modal-body mb10 user-review pt0 blue-color" id="socialBoxDetailsBody">

            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var countSubmit = 0;
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
		let emailCheck = $('#ContactEmail_eml_email_address').attr('required', 'required');
        let city = $("#Vendors_vnd_city").val();
		let license = $("#Vendors_vnd_driver_license").val();
		
        if (fName === "" || fName === null)
        {
            alert("Please select your first name");
            return false;
        } else if (lName === "" || lName === null)
        {
            alert("Please select your last name");
            return false;
        }
		else if (number === "" || number === null)
        {
            alert("Phone Number should not be blank");
            return false;
        }
		else if ((email === "" || email === null))
        {
            alert("Email Address should not be blank");
            return false;
        } 
		else if (!isValidEmail(email))
		{
			alert('Invalid Email Address');
			return false;
		} else if (city === "" || city === null)
        {
            alert("Please select your home city");
            return false;
        } else if (license === "" || license === null)
		{
			alert("Please select your driving license");
			return false;
		}
		else
        {
            loadSocialLogin();
        }
    }

	function isValidEmail(val) {
		if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(val)) {
			return true;
		}
		return false;
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
            //console.log(1);
            let fName = $("#Vendors_first_name").val();
            let lName = $("#Vendors_last_name").val();
            let phoneNumber = $("#ContactPhone_phn_phone_no").val();
            let emailId = $("#ContactEmail_eml_email_address").val();
            let city = $("#Vendors_vnd_city").val();

		    let temp = {};
            temp.driverLicenseNo = $('#<?= CHtml::activeId($model, "vnd_driver_license") ?>').val().trim();
           

            temp.socialLoginUserId = $("#socialuserid").val();
            temp.contactId = $("#contactId").val();
            temp.isDriver = $("#isDriver").val();
            temp.fName = fName.trim();
            temp.lName = lName.trim();
            temp.countryCode = $('#countryCode').val();
            temp.phoneNumber = phoneNumber.trim();
            temp.emailId = emailId.trim();
            temp.cityId = city;
            temp.telegramId = $('#telegramId').val();
			temp.platform = $('#platform').val();
           
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
                                //window.location.reload();
								setTimeout(window.location = "/vendor/success", 30000);
                            } else
                            {
                                alert(response.errors);
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
						<div class='col-12 google-btn mt20' style='' align='center'>\
							<a href='#' style='width:100%;text-align:center;margin: auto;'>\n\
								<span class='btn btn-outline-primary mr-1 mb-1' style='color:#FFFFFF;' onclick='socailSigin(\"google\");'>\n\
									<img src=\"/images/google_icon.png\"> Login with Google\n\
								</span>\n\
							</a>\n\
						</div>\
					</div>\n\
				</div>\n\
				<div class='row'>\
					<div class='col-12 col-md-12 fbook-btn mt20'>\
						<p id='divShowAlert'></p>\
					</div>\
				</div>\n\
			</div>\n\
		</div>";

         $('#socialBoxDetails').removeClass('fade');
         $('#socialBoxDetails').css('display', 'block');
         $('#socialBoxDetailsBody').html(socialLogin);
         $('#socialBoxDetails').modal('show');
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
											box.modal('hide');
											box.css('display', 'none');
											$('.modal-backdrop').remove();
											$("body").removeClass("modal-open");
                                        }
                                    }).removeClass('fade').css('display', 'block');
                        }
                    }
                });

        return false;
    }

    function signinWithFB()
    {
        var href = '<?= Yii::app()->createUrl('users/oauth', array('provider' => 'Facebook', 'isFlexxi' => true)); ?>';
        var fbWindow = window.open(href, 'aaocab', 'left=20,top=20,width=500,height=500,toolbar=1,resizable=0');
    }

    function signinWithGoogle()
    {
        var href = '<?= Yii::app()->createUrl('users/oauth', array('provider' => 'Google', 'isFlexxi' => true)); ?>';
        var googleWindow = window.open(href, 'aaocab', 'left=20,top=20,width=500,height=500,toolbar=1,resizable=0');

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
                                        $("#VendorOuterDiv").hide();
                                        $("#VendorOuterDivText").html('');
										$('#socialBoxDetails').css('display', 'none');
										createVendor();
								    }
                                }
                            } else
                            {
                                $('#socialuserid').val(userId);
                                $("#VendorOuterDiv").hide();
                                $("#VendorOuterDivText").html('');
								$('#socialBoxDetails').css('display', 'none');
								createVendor();
                            }
                        } else
                        {
                            $("#divShowAlert").html("Your social account already linked as a vendor. Please download the vendor app to continue. For any assistance, please contact our help desk");
						}
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
	
	function isNumber(evt)
	{
		evt = (evt) ? evt : window.event;
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		if (charCode > 31 && (charCode < 48 || charCode > 57))
		{
			var message = "<div class='errorSummary'>Please enter only Numbers.</div>";
			toastr['error'](message, 'Failed to process!', {
				closeButton: true,
				tapToDismiss: false,
				timeout: 500000
			});
			return false;
		}
		return true;
	}

</script>
