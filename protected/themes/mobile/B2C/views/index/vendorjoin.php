
<style>
    .new-booking-list .form-horizontal .form-group{ margin-left: 0; margin-right: 0;}
    .top-buffer{padding-top: 10px;}
    .modal-dialog{ width: 95%!important;}
</style>
<?php
if (!Yii::app()->user->isGuest)
{
	$this->redirect('/users/view');
}
$version			 = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/vendorJoin.js?v=' . $version);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/bookNow.js?v=' . $version);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/js/jquery.mask.min.js');
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/fontawesome-web/css/all.min.css?v0.6');
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



<div class="content-boxed-widget">

	<div id="VendorOuterDiv">
		<div class="content p0 bottom-0" id="VendorOuterDivText"></div>
	</div>  
	<div class="content" id="show_notice" style="display:none;">
			<div class="notification-small notification-has-icon notification-green">
				<div class="notification-icon"><i class="fa fa-exclamation notification-icon"></i></div>
				<p id="show_notice_msg"></p>
				<a href="#" class="close-notification"><i class="fa fa-times"></i></a>
			</div>
	</div>

	<div id="VendorInnerDiv" class="content p0 bottom-0">
		<h1 class="font-18">START YOUR APPLICATION</h1>
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
                                                                        $("#VendorOuterDivText").html("<h1 class=\"font-18 color-green3-dark text-center bottom-0\">Your application is almost approved.</h1><p class=\"text-center bottom-10\"><a href=\"https://play.google.com/store/apps/details?id=com.gozocabs.vendor&hl=en_IN\" target=\"_blank\" class=\"color-highlight\">Download Gozo partner app – from google play store</a></p><p class=\"text-center bottom-10\">Watch this video to sign your vendor agreement and upload your papers</p><p class=\"text-center bottom-0\"><a href=\"https://youtu.be/AfbwgIJN0H0\" target=\"_blank\"> https://youtu.be/AfbwgIJN0H0</a></p><p class=\"text-center bottom-10\">You will start receiving business within 48 hours of uploading all your papers</p>"); 
                                                                }
                                                                else
                                                                {		countSubmit--;
                                                                        $("#VendorOuterDiv").show();
                                                                        $("#vendorSubmitDiv").show();
                                                                        if(data1.msg=="signError"){
                                                                           $("#VendorOuterDivText").html("<p class=\"text-center\">Thank you. You have already submitted information to attach your car with us. We have now resent you instructions to take the next step.</p>"); 
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
                                                                           $("#VendorOuterDivText").html("<p class=\"error\">Error occured. Please enter all the mandatory fields.</p>"); 
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
		<input type="hidden" value="0" id="socialuserid" name="socialuserid">
		<input type="hidden" value="0" id="contactId" name="contactId" />
		<input type="hidden" value="0" id="isDriver" name="isDriver" />
		<input type="hidden" id="dataMapping" name="dataToMap"/>
        <input type="hidden" value="<?=$telegramId;?>" name="telegramId" id="telegramId"/>
		<input type="hidden" id="social_Type" value="0"/>
		<input type="hidden" id="m_Count" value="0"/>

		<div id="venjoin1">
			<div class="content p0 bottom-0">
				<div class="input-simple-1 has-icon input-green bottom-30">
					<em for="name">First name (as shown on your Driver's License)</em>
					<?= $form->textField($model, 'first_name', array('placeholder' => 'Enter First Name', 'class' => "form-control")) ?>
					<?php echo $form->error($model, 'first_name', ['class' => 'help-block error']); ?>
				</div>
			</div>

			<div class="content p0 bottom-0">
				<div class="input-simple-1 has-icon input-green bottom-30">
					<em for="name">Last name (as shown on your Driver's License)</em>
					<?= $form->textField($model, 'last_name', array('placeholder' => 'Enter Last Name', 'class' => "form-control")) ?>
					<?php echo $form->error($model, 'last_name', ['class' => 'help-block error']); ?>
				</div>
			</div> 						
			<div class="content p0 bottom-0">
				<div class="input-simple-1 has-icon input-blue bottom-30"><em>Phone Number (incl. country code)</em><i class="fa fa-phone"></i>
					<div class="bottom-30">
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
							'htmlOptions'			 => ['class' => 'form-control', 'id' => 'fullContactNumber3', 'value' => ''],
							'localisedCountryNames'	 => false, // other public properties
						));
						?> 
						<div class="help-block error" id="Vendors_phone_no_em_" style="display: none;"></div>
					</div>

				</div>
			</div>

			<div class="content p0 bottom-0">
				<div class="input-simple-1 has-icon input-green bottom-50 pb10">
					<em for="email">Email address</em>
					<?= $form->textField($modelContEmail, 'eml_email_address', array('placeholder' => 'Enter Email Id', 'class' => "form-control")) ?>
					<?php echo $form->error($modelContEmail, 'eml_email_address', ['class' => 'help-block error']); ?>
					<span id="errId" style="color: #F25656"></span>
				</div>
			</div>

			<div class="content p0 bottom-0">
				<div class="select-box-1 bottom-30">
					<em for="city" class="bottom-0">What city do you do most business in</em>
					<?php
					$this->widget('ext.yii-selectize.YiiSelectize', array(
						'model'				 => $model,
						'attribute'			 => 'vnd_city',
						'useWithBootstrap'	 => false,
						"placeholder"		 => "Select City",
						'fullWidth'			 => true,
						'htmlOptions'		 => array('width'	 => '100%', 'class'	 => 'ctyCheck bottom-0'
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
			<div class="">
					<div class="input-simple-1 has-icon input-green bottom-30">
						<em for="city" class="bottom-0">Driver license number</em>
						<?= $form->hiddenField($model, 'vnd_driver_license1') ?>
						<?= $form->textField($model, 'vnd_driver_license', array('placeholder' => 'Enter Driver license number', 'id' => 'driverlicense', 'class' => "form-control")) ?>
					</div>
				</div>
			<div class="content p0 bottom-0 mb20 text-center">
				<div class="Submit-button" id="vendorSubmitDiv">
					<button type="button" class = "uppercase btn-orange shadow-medium" onclick = "vencarinfo()" >Finish</button>
				</div>
			</div> 
		</div>

		

		<div id="loading"></div>
		<?php $this->endWidget(); ?>
	</div> 
</div>
<div class="content-boxed-widget">
	<div class="content text-center bottom-0">
		<div class="display-ini"><img src="../images/dco_operators.png?v=0.1" alt="Inviting DCO's and Txi Operators all over India" width="140"></div>
	</div>
	<h2 class="font-18"><b>Attach your car into the Gozo Vendor networks</b></h2>
	<p> If you own or operate a inter-city taxi, then you should join with Gozo.</p>
	<b>Benefits for Gozo vendor partners</b>
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
	<h3 class="font-16">Any questions? Contact our Vendor Relations team</h3>
	<p class="bottom-10"><img src="<?= Yii::app()->baseUrl ?>/images/india-flag.png" alt="INDIA" class="display-ini mr10"><a href="tel:03371122005"  style="color:#000">03371122005</a> <span style="font-size: 12px;">(24x7 Dedicated Vendor line)</span></p>
	<p class="bottom-10"><a href="mailto:vendors@gozocabs.in"><i class="fa fa-envelope font-16 color-highlight"></i> vendors@gozocabs.in </a></p>
	<p class="bottom-10"><a href="https://t.me/gozocabs"><i class="fa fa-paper-plane font-16 color-highlight"></i> Join the GozoCabs channel on telegram </a></p>
	<div class="decoration bottom-10"></div>
	<h3 class="font-16">Travel Agents, Hotel owners, Shopkeepers...<a href="<?php echo Yii::app()->createUrl('/agent/join'); ?>" class="color-highlight default-link">Join our travel partner network here</a></h3>
        <h3 class="mb0 font-16">Click the link below for YouTube Videos (Hindi Version)</h3>
        <ul> 
            <li><i class="fab fa-youtube mr5" style="color: #ff0202;"></i><a href="https://www.youtube.com/watch?v=3T12L7XWnyo&amp;&amp;list=PLtO3n8NwlGMQsJ_KDX9hyHOZqqlX0uo-5&amp;&amp;index=5" target="_blank"  style="display: inline-block">Attach your cab &amp; upload your documents</a></li>
            <li><i class="fab fa-youtube mr5" style="color: #ff0202;"></i><a href="https://www.youtube.com/watch?v=AfbwgIJN0H0&amp;&amp;list=PLtO3n8NwlGMQsJ_KDX9hyHOZqqlX0uo-5&amp;&amp;index=11" target="_blank" style="display: inline-block">Vendor Registration and documents Upload</a></li>
            <li><i class="fab fa-youtube mr5" style="color: #ff0202;"></i><a href="https://www.youtube.com/watch?v=4630FwpTMsE&amp;&amp;list=PLtO3n8NwlGMQsJ_KDX9hyHOZqqlX0uo-5&amp;&amp;index=33" target="_blank" style="display: inline-block">How to Add CAB, Upload DOCs, Sign the LOU</a></li>
            <li><i class="fab fa-youtube mr5" style="color: #ff0202;"></i><a href="https://www.youtube.com/watch?v=etKRxPYYjLw&amp;&amp;list=PLtO3n8NwlGMQsJ_KDX9hyHOZqqlX0uo-5&amp;&amp;index=35" target="_blank" style="display: inline-block">Partner App - Full Vendor App Functionality</a></li>
        </ul>
        <h3 class="mb0 font-16">Watch, what our Partners are saying about GozoCabs...</h3>
        <div class="text-center mb5">
            <div class="text-center mb5"><b>Vipul Agarwal</b></div>
            <div><iframe width="100%" height="150" src="https://www.youtube.com/embed/eACThcHWMtw" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen=""></iframe></div>
            <div class="content text-center p10 pb0 mb0">

                <a href="/partners-testimonials" class="uppercase btn-2 mr5 font-14">Click here for more video</a>
            </div>
        </div>
</div>

<!-------------------------modal window--------------------------------->
<a href="" data-menu="show_msg12" style="display:none;"><span id="get_msg12" class=""></span></a>       

<div id="show_msg12" data-selected="menu-components" data-width="320" data-height="200" class="menu-box menu-modal">
	<div class="clear"></div>	
	<div class="menu-title"><a href="#" class="menu-hide mt15 n"><i class="fa fa-times"></i></a>
		<h2 class="font18"><b class="font-14">Login With</b></h2>
	</div>
	<div class="menu-page p10 line-height18">
		<div class="clear"></div>
		<div class="line-f3 line-height20 mb10" id="msg_box1">
			<div class='social-log mt10 mb20 text-center'>
				<div class='line-f3'>
					<div class='line-s3 text-right'><a  href='javascript:void(0);' class='social-log-f' onclick='socailSigin("facebook")'><i class='fab fa-facebook-f'></i></a></div>
					<div class='line-s3 text-left pl5'><a  href='javascript:void(0);' class='social-log-g' onclick='socailSigin("google")'><i class='fab fa-google'></i></a></div>			
				</div>
			</div>
		</div>
	</div>
	<div class="clear"></div>
</div>
<!-------------------------modal window--------------------------------->

<script type="text/javascript">
    var countSubmit = 0;
    var model = {};
    var dataModel = {};
    dataModel.carCount = 1;
    dataModel.driverCount = 1;
    dataModel.totalCount = '';
    dataModel.vndCarModel = '';
    dataModel.vndCarYear = '';
    dataModel.vndCarNumber = '';
    dataModel.vndDriverName = '';
    dataModel.vndDriverLicence = '';
    var socialBox;
    var booknow = new BookNow();
	var googleWindow;

    $(document).ready(function () {
        $('#phone').mask('9999999999');
        $('#VendorOuterDiv').hide();
    });

    function vencarinfo()
    {
        //vendorjoinvalidationajax();
        let fName = $("#Vendors_first_name").val();
        let lName = $("#Vendors_last_name").val();
        let number = $('#ContactPhone_phn_phone_no').val().trim();
        let email = $('#ContactEmail_eml_email_address').val().trim();
        let city = $("#Vendors_vnd_city").val();
		let drvLicense = $("#driverlicense").val();
		let phncode = $("#ContactPhone_phn_phone_country_code").val();
		
        if (fName === "" || fName === null)
        {
            booknow.showErrorMsg("Please select your first name");
            return false;
        } else if (lName === "" || lName === null)
        {
            booknow.showErrorMsg("Please select your last name");
            return false;
        }else if (number === "" || number === null)
        {
            booknow.showErrorMsg('Phone number should not be blank');
			return false;
        }else if (phncode=='91' && (isNaN(number) || number.length != '10'))
        {			
			booknow.showErrorMsg('Invalid Phone number');
			return false;						        
        }else if (email === "" || email === null)
        {
            booknow.showErrorMsg('Email Address should not be blank');
			return false;
        }  else if (city === "" || city === null)
        {
            booknow.showErrorMsg("Please select your home city");
            return false;
        } else if (drvLicense === "" || drvLicense === null)
        {
            booknow.showErrorMsg("Please select your driver license number");
            return false;
        } else
        {
            $("#get_msg12").click();
        }
    }

    function vencardetails()
    {
        var vendorjoin = new VendorJoin();
        model.carModelId1 = '<?= CHtml::activeId($model, "vnd_car_model1") ?>';
        model.carYearId1 = '<?= CHtml::activeId($model, "vnd_car_year1") ?>';
        model.carNumberId1 = '<?= CHtml::activeId($model, "vnd_car_number1") ?>';
        model.driverName1 = '<?= CHtml::activeId($model, "vnd_driver_name1") ?>';
        model.driverLicence1 = '<?= CHtml::activeId($model, "vnd_driver_license1") ?>';
        vendorjoin.dataModel = dataModel;
        vendorjoin.model = model;
        dataModel = vendorjoin.vendorCarDetails();
    }

    function vencarcount() {

        if ($('#<?= CHtml::activeId($modelVndPref, "vnp_cars_own") ?>').val() >= 1 && $('#<?= CHtml::activeId($model, "vnd_cat_type") ?>').val() >= 1) {
            if ($('#<?= CHtml::activeId($modelVndPref, "vnp_cars_own") ?>').val() > 1 && $('#<?= CHtml::activeId($model, "vnd_cat_type") ?>').val() == '1') {
                booknow.showErrorMsg("If you are driver for this car.Please select only one car");
            } else if ($('#<?= CHtml::activeId($modelVndPref, "vnp_cars_own") ?>').val() == '1' && $('#<?= CHtml::activeId($model, "vnd_cat_type") ?>').val() == '1') {

                if ($('#<?= CHtml::activeId($model, "vnd_cat_type") ?>').val() == '1') {
                    $('#<?= CHtml::activeId($model, "vnd_driver_name") ?>').val($('#<?= CHtml::activeId($model, "first_name") ?>').val() + ' ' + $('#<?= CHtml::activeId($model, "last_name") ?>').val())
                }

                $('#venjoin3').css('display', 'block');
                $('#venjoin2').css('display', 'none');

            } else {
                $('#totalcount').text($('#<?= CHtml::activeId($modelVndPref, "vnp_cars_own") ?>').val());
                dataModel.totalCount = $('#<?= CHtml::activeId($modelVndPref, "vnp_cars_own") ?>').val();
                if ($('#<?= CHtml::activeId($model, "vnd_cat_type") ?>').val() == '1') {
                    $('#<?= CHtml::activeId($model, "vnd_driver_name") ?>').val($('#<?= CHtml::activeId($model, "first_name") ?>').val() + ' ' + $('#<?= CHtml::activeId($model, "last_name") ?>').val())
                }
                $('#venjoin4').css('display', 'block');
                $('#driverjoin').css('display', 'none');
                $('#venjoin2').css('display', 'none');
                if ($('#<?= CHtml::activeId($modelVndPref, "vnp_cars_own") ?>').val() == '1') {
                    $('#vendorMultiCarNxtDiv').find('button').text('Finish');
                }
            }
        } else {
            booknow.showErrorMsg("Please select at least one car and driver");
        }
    }

    function vendorjoinvalidationajax() {
        var vendorjoin = new VendorJoin();
        model.phoneNumberId = '<?= CHtml::activeId($modelContPhone, "phn_phone_no") ?>';
        model.vendorJoinValidationUrl = '<?= Yii::app()->createUrl('index/vendorjoinvalidation') ?>';
        vendorjoin.model = model;
        vendorjoin.validation();
    }

    function socailSigin(socailSigin)
    {
        socailTypeLogin = socailSigin;
        var href2 = "<?= Yii::app()->createUrl('users/partialsignin') ?>";
        $.ajax({
            "url": href2,
            "type": "GET",
            "dataType": "html",
            "success": function (data) {
                $(".menu-hide").click();
                $("html,body").animate({scrollTop: 0}, "slow");
                if (data.search("You are already logged in") == -1) {
                    if (socailSigin == "facebook") {
                        signinWithFB();
                    } else {
                        signinWithGoogle();
                    }
                } else {
                    updateLogin();
                }
            }
        });
        return false;
    }

    function signinWithFB() {
		$("#social_Type").val('0');
        var href = '<?= Yii::app()->createUrl('users/oauth', array('provider' => 'Facebook', 'isFlexxi' => true)); ?>';
        var fbWindow = window.open(href, 'Gozocabs', 'left=20,top=20,width=500,height=500,toolbar=1,resizable=0');

    }
    function signinWithGoogle() {
		$("#social_Type").val('2');
        var href = '<?= Yii::app()->createUrl('users/oauth', array('provider' => 'Google', 'isFlexxi' => true)); ?>';
		//googleWindow = window.open(href, 'Gozocabs', 'left=20,top=20,width=500,height=500,toolbar=1,resizable=0');
	   //parent.window.location = href;
		googleWindow = window.open(href, 'Gozocabs', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=200,height=200');
		return false;
		
    }

    function updateLogin() {
        $href = '<?= Yii::app()->createUrl('users/refreshuserdata') ?>';
        jQuery.ajax({type: 'get', url: $href, "dataType": "json", success: function (data1)
            {
                if (data1.userData.usr_email == "") {
                    if (socailTypeLogin == "facebook") {
                        socailTypeLogin = "";
                        signinWithFB();
                    } else {
                        socailTypeLogin = "";
                        signinWithGoogle();
                    }
                } else {
                    validateUser(data1.userData.usr_email)

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
        let conEmail = email + "," + emailData;

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
                                        booknow.showSuccessMsg("You are already linked as a vendor. For any assistance, please contact our help desk to activate your account");
                                        //$("#divShowAlert").html("You are already linked as a vendor. For any assistance, please contact our help desk to activate your account");
										$("#show_notice").show();
										$("#show_notice_msg").html("You are already linked as a vendor.");
                                    } else
                                    {
                                        $("#contactId").val(contactDetail[index].eml_contact_id);
                                        if (typeof contactDetail[index].cr_is_driver !== "undefined")
                                        {
                                            $("#isDriver").val(1);
                                        }

                                        $('#socialuserid').val(userId);                                        
										createVendor();
                                    }
                                }
                            } else
                            {
                                $('#socialuserid').val(userId);                               
								createVendor();
                            }
                        } else
                        {
                            booknow.showSuccessMsg("Your social account already linked as a vendor. Please download the vendor app to continue. For any assistance, please contact our help desk");
                            //$("#divShowAlert").html("Your social account already linked as a vendor. Please download the vendor app to continue. For any assistance, please contact our help desk");
                            //alert("Your social account already linked as a vendor. Please download the vendor app to continue. For any assistance, please contact our help desk");
							$("#show_notice").show();
							$("#show_notice_msg").html("Your social account already linked as a vendor.");
                            return false;
                        }
                    }
                });
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
            booknow.showSuccessMsg("Stored all car details. Click Finish to complete your registration");
            //$('#vendorMultiCarNxtDiv').find('button').text('Finish');
            $('#vendorMultiCarNxtDiv').find('button').prop("onclick", null).off("click");
            $('#vendorMultiCarNxtDiv').find('button').text('Finish');
            $('#vendorMultiCarNxtDiv').find('button').on("click", createVendor);
        } else
        {
            booknow.showSuccessMsg("Stored. Add another car details");
            $("#carcount").text(currentCount + 1);
        }
    }

    /**
     * This function is used for final submission of the vendor registration
     * @returns {undefined}
     */
    function createVendor()
    {   
        let drvLicense = $("#driverlicense").val();
        if (drvLicense)
        {          

            $("#finalsubmit").attr("disabled", true);
            $('#finalsubmit').text('Proccessing request...');
            let fName = $("#Vendors_first_name").val();
            let lName = $("#Vendors_last_name").val();
            let phoneNumber = $("#ContactPhone_phn_phone_no").val();
            let emailId = $("#ContactEmail_eml_email_address").val();
            let city = $("#Vendors_vnd_city").val();
			let temp = {};
            temp.driverLicenseNo = $("#driverlicense").val();
            temp.socialLoginUserId = $("#socialuserid").val();
            temp.contactId = $("#contactId").val();           
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
                            console.log(response);
                            if (response.success)
                            {								
                                booknow.showSuccessMsg(response.message);
                                //window.location.reload();
								setTimeout(window.location = "/vendor/success", 30000);
								
								
                            } else
                            {
                                booknow.showSuccessMsg(response.errors);
                                /*$("#vendorMultiCarNxtDiv").find('button').attr("disabled", false);
                                $('#vendorMultiCarNxtDiv').find('button').text('Finish');

                                $("#finalsubmit").attr("disabled", false);
                                $('#finalsubmit').text('Finish');*/
                            }
                        },
                        "error": function (error)
                        {
                            alert(error);
							console.log(error);
                        }
                    });
        } else
        {
            alert("Please select your driving license field");
            return false;
        }
    }


    function getUserId(email)
    {
        $href = '<?= Yii::app()->createUrl('users/getUserIdAfterSocialLogin') ?>';
        jQuery.ajax({type: 'get', url: $href, "dataType": "json", "data": {"email": email}, success: function (data1)
            {
                if (data1.success == "true")
                {
                    $('#socialuserid').val(data1.userid);
                    $('#venjoin2').css('display', 'block');
                    $('#venjoin1').css('display', 'none');
                    $("#VendorOuterDiv").hide();
                    $("#VendorOuterDivText").html('');
                    $(".menu-hide").click();
                } else
                {
                    booknow.showErrorMsg('You account already linked with another vendor.');
                }
            }
        });
    }

    function vendorjoinvalidationdetailsajax() {
        var vendorjoin = new VendorJoin();
        if ($('#<?= CHtml::activeId($modelVndPref, "vnp_cars_own") ?>').val() == '1' && $('#<?= CHtml::activeId($model, "vnd_cat_type") ?>').val() == '1') {
            dataModel.carModel = $('#<?= CHtml::activeId($model, "vnd_car_model") ?>').val();
            dataModel.carYear = $('#<?= CHtml::activeId($model, "vnd_car_year") ?>').val();
            dataModel.carNumber = $('#<?= CHtml::activeId($model, "vnd_car_number") ?>').val();
            dataModel.driverName = $('#<?= CHtml::activeId($model, "vnd_driver_name") ?>').val();
            dataModel.driverLicence = $('#<?= CHtml::activeId($model, "vnd_driver_license") ?>').val();
            dataModel.isDco = 1;
            if (dataModel.carModel == "" || dataModel.carYear == "" || dataModel.carNumber == "" || dataModel.driverName == "" || dataModel.driverLicence == "") {
                booknow.showErrorMsg("All fields are manadatory");
                return false;
            }
        } else {
            dataModel.carModel = $('#carmodel').val();
            dataModel.carYear = $('#caryear').val();
            dataModel.carNumber = $('#carnumber').val();
            dataModel.driverName = $('#drivername').val();
            dataModel.driverLicence = $('#driverlicense').val();
            dataModel.isDco = 0;
            if (dataModel.carModel == "" || dataModel.carYear == "" || dataModel.carNumber == "") {
                booknow.showErrorMsg("All fields are manadatory");
                return false;
            }
        }
        model.vendorJoinValidationDetailsUrl = '<?= Yii::app()->createUrl('index/vendorjoinvalidationdetails') ?>';
        model.carOwnId = '<?= CHtml::activeId($modelVndPref, "vnp_cars_own") ?>';
        model.isDcoId = '<?= CHtml::activeId($model, "vnd_cat_type") ?>';
        model.carModelId = '<?= CHtml::activeId($model, "vnd_car_model") ?>';
        model.carYearId = '<?= CHtml::activeId($model, "vnd_car_year") ?>';
        model.carNumberId = '<?= CHtml::activeId($model, "vnd_car_number") ?>';
        model.driverName = '<?= CHtml::activeId($model, "vnd_driver_name") ?>';
        model.driverLicence = '<?= CHtml::activeId($model, "vnd_driver_license") ?>';
        model.carModelId1 = '<?= CHtml::activeId($model, "vnd_car_model1") ?>';
        model.carYearId1 = '<?= CHtml::activeId($model, "vnd_car_year1") ?>';
        model.carNumberId1 = '<?= CHtml::activeId($model, "vnd_car_number1") ?>';
        model.driverName1 = '<?= CHtml::activeId($model, "vnd_driver_name1") ?>';
        model.driverLicence1 = '<?= CHtml::activeId($model, "vnd_driver_license1") ?>';
        vendorjoin.model = model;
        vendorjoin.dataModel = dataModel;
        dataModel = vendorjoin.validationDetails();
    }

    function opentns() {
        $href = '<?= Yii::app()->createUrl('index/termsvendor') ?>';
        jQuery.ajax({type: 'GET', url: $href,
            success: function (data) {
                $(".menu-hide").click();
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
        var vendorjoin = new VendorJoin();
        model.carNumberId = '<?= CHtml::activeId($model, 'vnd_car_number') ?>';
        vendorjoin.model = model;
        vendorjoin.carValidation();
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
	
	/*
	 * Only For Sign In With Google From Mobile Browser
	 */
	setInterval(function()
	{ 
		let bkCSRFToken = $('input[name ="YII_CSRF_TOKEN"]').val();
		let s_Type = $("#social_Type").val();
		let cnt	   = $("#m_Count").val();
		var counter  = parseInt(cnt);	
		if(parseInt(s_Type)===2 && counter < 30)	
		{
			$.ajax({
				url: '/users/userdata',
				data: {"YII_CSRF_TOKEN": bkCSRFToken},
				type: 'POST',
				async:false,
				success: function (data) 
				{  	counter++;  
					$("#m_Count").val(counter);
					let pdata = JSON.parse(data);                  
					if(pdata.usr_name === null && pdata.usr_lname === null && !pdata.hasOwnProperty('usr_mobile') && !pdata.hasOwnProperty('usr_email'))
					{					
						//console.log("You are not loggedin.");		         
					} 
					else 
					{						
						$("#social_Type").val('0');
						googleWindow.close();
						validateUser(pdata.usr_email);
					}     
				}			
			});			
		}		
	}, 5000);
</script>
