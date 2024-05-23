<?php
$version = Yii::app()->params['siteJSVersion'];

$version		 = Yii::app()->params['siteJSVersion'];
/** @var CClientScript $clientScript */
$clientScript	 = Yii::app()->clientScript;

if ($type == Stub\common\ContactVerification::TYPE_EMAIL)
{
	$emailChecked	 = "checked";
	$clickEvent		 = "Users_search_0";
}
if ($type == Stub\common\ContactVerification::TYPE_PHONE)
{
	$phoneChecked	 = "checked";
	$clickEvent		 = "Users_search_1";
}
?>
<script>
	//$jsBookNow = new BookNow();
</script>
<div class="mt-2 mb-2 signInBox">
	<?php
	$form	 = $this->beginWidget('CActiveForm', array(
		'id'					 => 'login-form',
		'enableClientValidation' => true,
		'clientOptions'			 => array(
			'validateOnSubmit'	 => true,
			'errorCssClass'		 => 'has-error',
		),
		'enableAjaxValidation'	 => false,
		'errorMessageCssClass'	 => 'help-block',
		'htmlOptions'			 => array(
			'class'		 => 'form-horizontal',
			'onsubmit'	 => 'return signin(this);'
		),
	));
	?>

	<div class="menuTripType">
		<input type="hidden" name="rdata" value="<?= $this->pageRequest->getEncrptedData() ?>">
		<div class="row">
			<div class="col-12 text-center">
				<p class="merriw heading-line">
					<?php
					$title	 = ($signup == 2) ? "Great! Now, Let's quickly create a gozo account for you." : "Login with";
					echo $title;
					?>
				</p>
			</div>
			<div class="col-12 text-center">
				<div class="correctotp alert alert-danger mb-1 col-12 <?= (!$hasErrors) ? " hide" : "" ?>" style="max-width: 500px; margin: auto"> <?= $errorMessage; ?></div>
			</div>
			<div class="col-12 mb-1" style="min-width: 300px; max-width: 350px; margin: auto">
				<div class="row mb-1 radio-style4">
					<div class="col-6 pr-0">
						<div class="radio">
							<input id="Users_search_0" value="1" type="radio" name="checkaccount" class="bkg_user_trip_type" <?= $emailChecked ?>>
							<label for="Users_search_0">Email address</label>
						</div>
					</div>
					<div class="col-6 pr-0">
						<div class="radio">
							<input id="Users_search_1" value="2" type="radio" name="checkaccount" class="bkg_user_trip_type" <?= $phoneChecked ?>>
							<label for="Users_search_1">Phone number</label>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-12"><?= $form->emailField($emailModel, 'eml_email_address', ['placeholder' => "Email Address", 'class' => 'form-control m0', 'required' => true]) ?></div>
					<div class="col-12 text-center phn_phone" style="min-width: 300px">
						<?php
						//$bookingModel->bkgUserInfo->fullContactNumber= $bookingModel->bkgUserInfo->bkg_contact_no;
						$this->widget('ext.intlphoneinput.IntlPhoneInput', array(
							'model'					 => $phoneModel,
							'attribute'				 => 'fullContactNumber',
							'codeAttribute'			 => 'phn_phone_country_code',
							'numberAttribute'		 => 'phn_phone_no',
							'options'				 => array(// optional
								'customContainer'	 => 'full-width',
								'separateDialCode'	 => true,
								'autoHideDialCode'	 => true,
								'initialCountry'	 => 'in'
							),
							'htmlOptions'			 => ['class' => 'form-control', 'maxlength' => '10', 'onkeypress' => "return isNumber(event)", 'id' => 'fullContactNumber' . $id],
							'localisedCountryNames'	 => false, // other public properties
						));
						?>
					</div>
					<div class="col-12 text-center mt-3" style="min-width: 300px">
						<input type="hidden" id="step1" name="step" value="2">
						<input type="hidden" id="pstep" name="signup" value="<?= $signup ?>">
						<input type="button" value="Go back" name="yt0" class="btn btn-light" onclick="goBack1();">
						<input type="submit" value="Next" name="yt0" id="cabsegmentation" class="btn btn-primary pl-4 pr-4">
					</div>
				</div>
			</div>


		</div>
	</div>
	<?php $this->endWidget(); ?>
</div>
<div class="mt-2 mb-2 otpBox">

</div>

<script>
	var home = null;
	var rdata = window.sessionStorage.getItem("rdata");
	$(document).ready(function()
	{

		home = new Home();
		step = <?= $this->pageRequest->step ?>;
		tabURL = "<?= $this->getURL("booking/signin") ?>";
		tabHead = "";
		pageTitle = "Gozocabs: " + tabHead;


		$('#<?= $clickEvent ?>').click();
		if (rdata != '' && rdata != null)
		{
			showBack();

			if (typeof toggleStep == 'function')
			{
				toggleStep(step, 1, tabURL, pageTitle, tabHead, false, <?= $this->pageRequest->step ?>, false);
			}
		}
		else
		{
			if (typeof hideBack == 'function')
			{

				hideBack();
			}
		}
	});
	$('#Users_search_0').click(function()
	{
		$('#ContactPhone_phn_phone_no').removeAttr('required');
		$('#fullContactNumber').removeAttr('required');
		$('#ContactEmail_eml_email_address').attr('required', 'required');
		home.selectValueTypePhone();
	});
	$('#Users_search_1').click(function()
	{
		$('#ContactEmail_eml_email_address').removeAttr('required');
		$('#ContactPhone_phn_phone_no').attr('required', 'required');
		$('#fullContactNumber').attr('required', 'required');
		home.selectValueTypePhone();
	});

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

	function goBack1()
	{
		var form = $("form#login-form");
		var rdata = window.sessionStorage.getItem("rdata");
		$("form#login-form INPUT[name=rdata]").val(window.sessionStorage.getItem("rdata"));
		form.prop("action", "<?= CHtml::normalizeUrl(Yii::app()->request->urlReferrer) ?>");
		form.prop("onsubmit", "");
		form.submit();
		return false;
	}

	function signin(obj)
	{
		var form = $(obj);
		$.ajax({
			"type": "POST",
			"dataType": "html",
			"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/signin')) ?>",
			"data": form.serialize(),
			"beforeSend": function()
			{
				blockForm(form);
			},
			"complete": function()
			{
				unBlockForm(form);
			},
			"success": function(data2)
			{
				var data = "";
				var isJSON = false;
				try
				{
					data = JSON.parse(data2);
					isJSON = true;
				}
				catch (e)
				{

				}
				if (!isJSON)
				{
					$(".correctotp").html("");
					$(".correctotp").addClass("hide");
					$(".signInBox").hide();
					$(".otpBox").html(data2);
					$(".otpBox").show();
					trackPage("<?= CHtml::normalizeUrl($this->getURL('booking/signin')) ?>");
				}
				else
				{
					if (data.success)
					{
						$(".correctotp").hide();
						location.href = data.data.url;
						return;
					}
					else
					{
						var msg = "<ul class='m-0'>";
						data.errors.forEach(function(val)
						{
							msg += "<li>" + val + "</li>";
						});
						msg += "</ul>";
						$(".correctotp").html(msg);
						$(".correctotp").removeClass("hide");
					}
				}
			},
			error: function(xhr, ajaxOptions, thrownError)
			{
				var msg = "<ul class='list-style-circle'><li>" + xhr.status + ": " + thrownError + "</li></ul>";
				$(".correctotp").html(msg);
				$(".correctotp").removeClass("hide");
			}
		});
		return false;
	}

	function socailSigin(socailSigin)
	{
		socailTypeLogin = socailSigin;
		var href2 = "<?= Yii::app()->createUrl('users/partialsignin') ?>";
		$.ajax({
			"url": href2,
			"type": "GET",
			"dataType": "html",
			"success": function(data)
			{
				if (data.search("You are already logged in") == -1)
				{
					if (socailSigin == "facebook")
					{
						signinWithFB();
					}
					else
					{
						signinWithGoogle();
					}

				}
				else
				{
					var box = bootbox.dialog({message: data, size: 'large',
						onEscape: function()
						{
							updateLogin();
						}
					});
				}
			}
		});
		return false;
	}

	function signinWithGoogle()
	{
		var href = '<?= Yii::app()->createUrl('users/oauth', array('provider' => 'Google', 'isFlexxi' => true)); ?>';
		var googleWindow = window.open(href, 'Gozocabs', 'left=20,top=20,width=500,height=500,toolbar=1,resizable=0');
	}
</script>
