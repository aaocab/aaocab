<?php
$version = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/home.js?v=1238');

$version		 = Yii::app()->params['siteJSVersion'];
/** @var CClientScript $clientScript */
$clientScript	 = Yii::app()->clientScript;
//$clientScript->registerCssFile(Yii::app()->baseUrl . '/js/intl-tel-input/css/intlTelInput.css?v=');
$clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/bookNow.js?v=' . $version);
?>
<script>
    $jsBookNow = new BookNow();
</script>
<div class="container mt30 mb30">
	<?php
	$form			 = $this->beginWidget('booster.widgets.TbActiveForm', array(
		'id'					 => 'login-form',
		'enableClientValidation' => true,
		'clientOptions'			 => array(
			'validateOnSubmit'	 => true,
			'errorCssClass'		 => 'has-error',
		),
		'enableAjaxValidation'	 => false,
		'action'				 => Yii::app()->createUrl('users/loginVO'),
		'errorMessageCssClass'	 => 'help-block',
		'htmlOptions'			 => array(
			'class' => 'form-horizontal',
		),
	));
	?>
	<div class="menuTripType">
		<div class="row">
			<div class="col-12 mb-3 text-center">
				<p class="merriw heading-line">Login with</p>
			</div>
			<div class="col-12 col-xl-6 offset-xl-3 mb-2">
				<div class="row">
					<div class="col-12"></div>
					<div class="col-12 mt-3">
						<div class="row mb-1 radio-style4">
							<div class="col-6">
								<div class="radio">
									<input id="Users_search_0" value="1" type="radio" name="checkaccount" class="bkg_user_trip_type" checked="">	
									<label for="Users_search_0">Email address</label>
								</div>
							</div>
							<div class="col-6">
								<div class="radio">
									<input id="Users_search_1" value="2" type="radio" name="checkaccount" class="bkg_user_trip_type" checked="">
									<label for="Users_search_1">Phone number</label>
								</div>
							</div>
						</div>
					</div>
					<div class="col-12"><?= $form->emailField($emailModel, 'eml_email_address', ['placeholder' => "Email Address", 'class' => 'form-control m0']) ?></div>
					<div class="col-12 text-center phn_phone">
						<?php
						//$bookingModel->bkgUserInfo->fullContactNumber= $bookingModel->bkgUserInfo->bkg_contact_no;
						$this->widget('ext.intlphoneinput.IntlPhoneInput', array(
							'model'					 => $phoneModel,
							'attribute'				 => 'fullContactNumber',
							'codeAttribute'			 => 'phn_phone_country_code',
							'numberAttribute'		 => 'phn_phone_no',
							'options'				 => array(// optional
								'separateDialCode'	 => true,
								'autoHideDialCode'	 => true,
								'initialCountry'	 => 'in'
							),
							'htmlOptions'			 => ['class' => 'yii-selectize selectized form-control', 'id' => 'fullContactNumber' . $id],
							'localisedCountryNames'	 => false, // other public properties
						));
						?>
					</div>
					<div class="col-12 text-center mt-3">
						<input type="submit" value="Next" name="yt0" id="cabsegmentation" class="btn btn-primary pl-5 pr-5">
					</div>
					<div class="col-12 text-center mt-2">
						<p class="mb-2">or</p>
						<a onclick="socailSigin('google')" ><img src="/images/btn_google_signin_light_normal_web.png?v=0.1" alt="Login with Google"></a></div>
				</div>
			</div>


		</div>
	</div>
	<?php $this->endWidget(); ?>
</div>


<script>

    var home = new Home();
    $(document).ready(function ()
    {
        $('#Users_search_0').attr('checked', true);
        home.selectValueTypePhone();
    });

    $('#Users_search_0').click(function ()
    {
        home.selectValueTypePhone();
    });
		
	$('#Users_search_1').click(function ()
    {
        home.selectValueTypePhone();
    });
	
	
	
    function socailSigin(socailSigin)
    {
        socailTypeLogin = socailSigin;
        var href2 = "<?= Yii::app()->createUrl('users/partialsignin') ?>";
        $.ajax({
            "url": href2,
            "type": "GET",
            "dataType": "html",
            "success": function (data) {
                if (data.search("You are already logged in") == -1) {
                    if (socailSigin == "facebook") {
                        signinWithFB();
                    } else {
                        signinWithGoogle();
                    }

                } else {
                    var box = bootbox.dialog({message: data, size: 'large',
                        onEscape: function () {
                            updateLogin();
                        }
                    });
                }
            }
        });
        return false;
    }

    function signinWithGoogle() {
        var href = '<?= Yii::app()->createUrl('users/oauth', array('provider' => 'Google', 'isFlexxi' => true)); ?>';
        var googleWindow = window.open(href, 'aaocab', 'left=20,top=20,width=500,height=500,toolbar=1,resizable=0');

    }
</script>
