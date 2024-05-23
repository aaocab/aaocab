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
<div class="row m0">
    <div class="col-12">
        <div class="container mt30 mb50">
            <div class="row">
                <div class="col-12"><h1 class="font-22 mb20"><b>DCOs and Cab Operators, Attach your cab...</b></h1></div>
                <div class="col-12 col-sm-5">
                    <div class="bg-white-box p20">
                        <div id="VendorOuterDiv" class="col-12">
                            <h4 style="color: #000000;"><span id="VendorOuterDivText"></span></h4>
                        </div>  
                        <div id="VendorInnerDiv">
                            <p class="font-20"><b>START YOUR APPLICATION</b> <span class="float-right"><img src="/images/resume.svg" alt="" width="40"></span></p>
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
					/* @var $form CActiveForm */
					?>
					<input type="hidden" value="0" id="socialuserid" name="socialuserid" />
					<input type="hidden" value="0" id="contactId" name="contactId" />
					<input type="hidden" value="0" id="isDriver" name="isDriver" />
					<input type="hidden" id="dataMapping" name="dataToMap"/>
                    <input type="hidden" value="<?=$telegramId;?>" name="telegramId" id="telegramId"/>
					<!-- //Start - Application Form Part 1 -->
                            <div id="venjoin1">
                                <div class="row">
                                    <div class="col-md-10">
                                        <label for="name"><b>First name (as shown on your Driver's License)</b></label>
                                        <div class="form-group">
                                        <?= $form->textField($model, 'first_name',  array('placeholder' => 'Enter First Name', 'class' => "form-control")) ?>
                                        <?php echo $form->error($model, 'first_name', ['class' => 'help-block error']); ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-10">
                                        <label for="name"><b>Last name  (as shown on your Driver's License)</b></label>
                                        <div class="form-group">
                                        <?= $form->textField($model, 'last_name', array('placeholder' => 'Enter Last Name', 'class' => "form-control")) ?>
                                        <?php echo $form->error($model, 'last_name', ['class' => 'help-block error']); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-10">
                                        <label for="phone"><b>Phone numbers</b></label>
                                        <div class="row">
                                            <div class="col-12 pr0">
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
													'htmlOptions'			 => ['class' => 'form-control', 'id' => 'fullContactNumber1','value' => ''],
													'localisedCountryNames'	 => false, // other public properties
												));
											?> 
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row pt10">
                                    <div class="col-md-10">
                                        <label for="email"><b>Email address</b></label>
                                        <div class="form-group">
                                        <?= $form->textField($modelContEmail, 'eml_email_address',  array('placeholder' => 'Enter Email Id','class' => "form-control")) ?>
                                        <?php echo $form->error($modelContEmail, 'eml_email_address', ['class' => 'help-block error']); ?>
                                        </div>
                                        <span id="errId" style="color: #F25656"></span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-10">
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
                                    <div class="col-md-10">
                                        <label for="name"><b>Driver license number</b></label>
                                        <div class="form-group">
                                        <?= $form->textField($model, 'vnd_driver_license', array('placeholder' => 'Driver license number','class' => "form-control")) ?>
                                        <?php echo $form->error($model, 'vnd_driver_license', ['class' => 'help-block error']); ?>
                                        </div>
                                        <span id="errId" style="color: #F25656"></span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-8 col-lg-4 top-buffer pt20 mb30">
                                        <div class="Submit-button" style="text-align: left" id="vendorSubmitDiv">
                                            <button type="button" class = "btn text-uppercase gradient-green-blue font-20 border-none pl30 pr30" onclick = "vencarinfo()" >Finish</button>
                                        </div>
                                    </div>
                                </div> 
                            </div>

							<div id="loading"></div>
                            <?php $this->endWidget(); ?>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-7">
                    <div class="bg-white-box p20">
                    <div class="pull-right"><img src="../images/dco_operators.png?v=0.1" alt="Inviting DCO's and Txi Operators all over India"></div>
                    <h2 class="font-16"><b>Attach your car into the Gozo Vendor networks</b></h2>
                    <p> If you own or operate a inter-city taxi, then you should join with Gozo.</p>
                    <h2 class="font-16"><b>Benefits for Gozo vendor partners</b></h2>
                    <ul class="pl15 ul-style-c">
                        <li><i class="fas fa-check-circle mr5"></i> Gozo focuses on getting customer demand</li>
                        <li><i class="fas fa-check-circle mr5"></i> You simply provide top quality service </li>
                        <li><i class="fas fa-check-circle mr5"></i> Stay busy in all seasons. Good service = More business</li>
                        <li><i class="fas fa-check-circle mr5"></i> Get great reviews from customers</li>
                        <li><i class="fas fa-check-circle mr5"></i> Gozo sends you payments on-time</li>
                        <li><i class="fas fa-check-circle mr5"></i> Use your Gozo partner and Gozo driver mobile app to keep in continous touch with Gozo</li>
                    </ul>
                    <h2 class="mt20 mb10 font-24"><b>Any questions? Contact our Vendor Relations team</b></h2>
                    <p class="mb5 font-18"><img src="<?= Yii::app()->baseUrl ?>/images/india-flag.png" alt="INDIA" class="mr10 mb5"><a href="tel:03371122005">03371122005</a> <span class="font-14">(24x7 Dedicated Vendor line)</span></p>
                    <p class="font-18 mb0"><i class="fa fa-envelope mr10 mb10 color-green"></i> <a href="mailto:vendors@gozocabs.in">vendors@gozocabs.in</a> <i class="fa fa-paper-plane ml15 mb10" style="font-size:18px; color: #36abe8;"></i> <a href="https://t.me/gozocabs"> Join the GozoCabs channel on telegram</a></p>
                    <p></p>
                    <div class="row">
                        <div class="col-12 ul-style-c">
                            <p class="mb5 font-16"><b>Click the link below for YouTube Videos (Hindi Version)</b></p>
                            <div class="video-panel">
                            <ul>
                                <li><i class="fab fa-youtube mr5" style="color: #ff0202;"></i><a href="https://www.youtube.com/watch?v=3T12L7XWnyo&&list=PLtO3n8NwlGMQsJ_KDX9hyHOZqqlX0uo-5&&index=5" target="_blank">Attach your cab & upload your documents</a></li>
                                <li><i class="fab fa-youtube mr5" style="color: #ff0202;"></i><a href="https://www.youtube.com/watch?v=AfbwgIJN0H0&&list=PLtO3n8NwlGMQsJ_KDX9hyHOZqqlX0uo-5&&index=11" target="_blank">Vendor Registration and documents Upload</a></li>
                                <li><i class="fab fa-youtube mr5" style="color: #ff0202;"></i><a href="https://www.youtube.com/watch?v=4630FwpTMsE&&list=PLtO3n8NwlGMQsJ_KDX9hyHOZqqlX0uo-5&&index=33" target="_blank">How to Add CAB, Upload DOCs, Sign the LOU</a></li>
                                <li><i class="fab fa-youtube mr5" style="color: #ff0202;"></i><a href="https://www.youtube.com/watch?v=etKRxPYYjLw&&list=PLtO3n8NwlGMQsJ_KDX9hyHOZqqlX0uo-5&&index=35" target="_blank">Partner App - Full Vendor App Functionality</a></li>
                            </ul>
                            </div>
                        </div>
                    </div>
                    <h2 class="font-24"><b>Travel Agents, Hotel owners, Shopkeepers... <a href="http://www.gozocabs.com/agent/join">Join our travel partner network here</a></b></h2>
                </div>
            </div>
                
            </div>
            <div class="row">
                <div class="col-12 mt50">
                    <h3>Watch, what our Partners are saying about GozoCabs...</h3>
                    <div class="flex-widget-1">
                        
                        <div class="flex-widget-2">
                            <div class="row">
                                <div class="col-12 text-center mb5"><b>Vipul Agarwal</b></div>
                                <div class="col-12"><iframe width="150" height="100" src="https://www.youtube.com/embed/eACThcHWMtw" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>
                            </div>
                        </div>
                        <div class="flex-widget-2">
                            <div class="row">
                                <div class="col-12 text-center mb5"><b>Raigonda Pujari</b></div>
                                <div class="col-12"><iframe width="150" height="100" src="https://www.youtube.com/embed/AKOjm5K9MaM" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>
                            </div>
                        </div>
                        <div class="flex-widget-2">
                            <div class="row">
                                <div class="col-12 text-center mb5"><b>Sameer Sheikh</b></div>
                                <div class="col-12"><iframe width="150" height="100" src="https://www.youtube.com/embed/IQQQCOhw4lE" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>
                            </div>
                        </div>
                        <div class="flex-widget-2">
                            <div class="row">
                                <div class="col-12 text-center mb5"><b>Mr. Krishna</b></div>
                                <div class="col-12"><iframe width="150" height="100" src="https://www.youtube.com/embed/3JdKhRQA_Q4" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>
                            </div>
                        </div>
                        <div class="flex-widget-2">
                            <div class="row">
                                <div class="col-12 text-center mb5"><b>Sunil Kumar</b></div>
                                <div class="col-12"><iframe width="150" height="100" src="https://www.youtube.com/embed/Jw2Q-dgDapI" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>
                            </div>
                        </div>
                        <div class="flex-widget-2">
                            <div class="row">
                                <div class="col-12 text-center mb5"><b>Mr. Kartik</b></div>
                                <div class="col-12"><iframe width="150" height="100" src="https://www.youtube.com/embed/HlEziADtJHY" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>
                            </div>
                        </div>
                        
                    </div>
                </div>
                <div class="col-12 mt10 text-right pr0"><a href="/partners-testimonials">Click here for more video</a></div>
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
		else if (email === "" || email === null)
        {
            alert("Email Address should not be blank");
            return false;
        }  else if (city === "" || city === null)
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
						<div class='col-8 col-md-10 google-btn mt20' style='background: #3264a1;' align='center'>\
							<a href='#' style='width:100%;text-align:center;margin: auto;'>\n\
								<span class='btn btn-xs btn-social btn-googleplus pr5' style='color:#FFFFFF;' onclick='socailSigin(\"google\");'>\n\
									<img src='/images/google_icon.png'> Login with Google\n\
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

</script>
