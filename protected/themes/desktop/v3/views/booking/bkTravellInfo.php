<style>
	.in-style{
		padding-left: 81px!important;
	}
</style>
<?php
if ($model instanceof Booking)
{
	$user		 = $model->bkgUserInfo->bkg_user_id;
	$phone_User	 = $model->bkgUserInfo->bkg_country_code . $model->bkgUserInfo->bkg_contact_no;
}
else
if ($model instanceof BookingTemp)
{
	$user				 = $model->bkg_user_id;
	$phone_BookingTemp	 = $model->bkg_country_code . $model->bkg_contact_no;
}
if ($user == 0)
{
	$user = UserInfo::getUserId();
}


if ($user != '')
{
	$contactModel	 = Contact::model()->getByUserId($user);
	$contactId		 = $contactModel->ctt_id;

	$primaryPhone = ContactPhone::getContactNumber($contactId);
	if ($primaryPhone == "")
	{

		$primaryPhone = ($phone_User == "") ? ($phone_BookingTemp) : ($phone_User);
	}
	$primaryPhone	 = "+" . $primaryPhone;
	$emailId		 = ContactEmail::getContactEmailById($contactId);
	$isValid		 = Filter::validatePhoneNumber($primaryPhone);
	$firstName		 = $contactModel->ctt_first_name;
	$lastName		 = $contactModel->ctt_last_name;
	if ($isValid)
	{
		Filter::parsePhoneNumber($primaryPhone, $code, $number);
	}

	$userFirstName = "bkg_user_name";
	if($model instanceof Booking)
	{
		$model = $model->bkgUserInfo;
		$userFirstName = "bkg_user_fname";
		//goto skiptemp;
	}

	if ($model->bkg_traveller_type == 2)
	{
		goto skipReplace;
	}
	
	if($model instanceof BookingUser)
	{
		$model->bkg_user_fname = $firstName;
	}
	else
	{
		$model->bkg_user_name	 = $firstName;
	}
	
	$model->bkg_user_lname	 = $lastName;
	$model->bkg_contact_no	 = $number;
	$model->bkg_country_code = $code;
	$email					 = $model->bkg_user_email	 = $emailId;
	$phone					 = $number;

	skipReplace:
	if (!Yii::app()->user->isGuest)
	{
		if ($firstName == '' && Yii::app()->user->loadUser()->usr_name != '')
		{
			$firstName = Yii::app()->user->loadUser()->usr_name;
		}
		if ($lastName == '' && Yii::app()->user->loadUser()->usr_lname != '')
		{
			$lastName = Yii::app()->user->loadUser()->usr_lname;
		}
	}
}

/** @var CActiveForm $form */
$form = $this->beginWidget('CActiveForm', array(
	'id'					 => 'cabtravellinfo',
	'enableClientValidation' => false,
	'clientOptions'			 => [
		'validateOnSubmit'	 => false,
		'errorCssClass'		 => 'has-error',
	],
	'enableAjaxValidation'	 => false,
	'errorMessageCssClass'	 => 'help-block',
	'action'				 => Yii::app()->createUrl('booking/travellerInfo'),
	'htmlOptions'			 => array(
		"onsubmit"		 => "return checkTravellerInfo();",
		'class'			 => 'form-horizontal',
		'autocomplete'	 => 'off',
	),
		));
?>

<input type="hidden" id="hash" name="hash"  value="<?php echo $hash ?>">
<input type="hidden" id="isbkpn" name="isbkpn" value="<?php echo $isbkpn; ?>">
<input type="hidden" id="bkg_id" name="bkg_id" value="<?php echo $bkgId; ?>">
<div class="row justify-center">
	<div class="col-12 <?= $gnowPickupHide ?> radio-style7">
		<div class="form-check-inline radio radio-glow">
			<?php
			echo $form->radioButton($model, "bkg_traveller_type", ["value" => "1", "checked" => ($model->bkg_traveller_type == 1), "id" => "travelling_0", "class" => "clsTravelType mr5"]);
			?><label class="mb-0 form-check-label" for="travelling_0">I am travelling</label>
		</div>
		<div class="form-check-inline radio radio-glow">
			<?php
			echo $form->radioButton($model, "bkg_traveller_type", ["value" => "2", "checked" => ($model->bkg_traveller_type == 2), "id" => "travelling_1", "class" => "clsTravelType mr5"]);
			?>			<label class="mb-0 form-check-label" for="travelling_1">Someone else is travelling</label>
		</div>
	</div>
	<div class="col-12 mt-1">
		<div class="row">
			<div class="col-12">
				<div class=" bg-white-box">
					<div class="row <?= $gnowPickupHide ?>">
						<div class="col-12 ">
							<p class="weight600 mb10">Please give us the traveller's name</p><br />

						</div>
						<div class="col-12 col-xl-6">
							<p class="mb5"><small class="form-text">First name</small></p>
							<fieldset class="form-group position-relative">
							
								<?= $form->textField($model, $userFirstName, ['placeholder' => "Enter first name", 'class' => 'form-control m0 firstname clsInput', 'id' => 'iconLeft', 'required' => true]) ?>
							</fieldset>
						</div>
						<div class="col-12 col-xl-6 mb-1">

							<p class="mb5"><small class="form-text">Last name</small></p>
							<fieldset class="form-group position-relative">
								<?= $form->textField($model, 'bkg_user_lname', ['placeholder' => "Enter last name", 'class' => 'form-control m0 lastname clsInput', 'id' => 'iconLeft', 'required' => true]) ?>
							</fieldset>
						</div>
						<div class="col-12 col-xl-6 mb-1">

							<p class="mb5"><small class="form-text">Phone number</small></p>
							<fieldset class="form-group position-relative">
								<?php
								if ($phone == '' && $code == '')
								{
									$css = "";
								}
								else
								{
									$css = "clsInput";
								}
								$this->widget('ext.intlphoneinput.IntlPhoneInput', array(
									'model'					 => $model,
									'attribute'				 => 'fullContactNumber',
									'codeAttribute'			 => 'bkg_country_code',
									'numberAttribute'		 => 'bkg_contact_no',
									'options'				 => array(// optional
										'separateDialCode'	 => true,
										'autoHideDialCode'	 => true,
										'initialCountry'	 => 'in'
									),
									'htmlOptions'			 => ['class' => 'form-control phoneno in-style ' . $css . '', 'style' => 'padding-left:81px!important;', 'id' => 'fullContactNumber1', 'required' => true],
									'localisedCountryNames'	 => false,
								));
								?> 
							</fieldset>
						</div>
						<div class="col-12 col-xl-6 mb-1">

							<p class="mb5"><small class="form-text">Email address</small></p>
							<fieldset class="form-group position-relative">
<?= $form->emailField($model, 'bkg_user_email', ['placeholder' => "Email Address", 'class' => 'form-control m0 emailaddress clsInput']) ?> 
							</fieldset>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-12">
		<div class="row m0 justify-center">
			<div class="col-xl-12 text-center mb-1">
				<input type="hidden" name="step" value="<?= $pageid ?>">
				<input type="hidden" name="rdata" value="<?= $this->pageRequest->getEncrptedData() ?>">
				<button type="submit" class="btn btn-primary pl-5 pr-5">Save</button>
			</div>
		</div>
	</div>
</div>


<?php $this->endWidget(); ?>

<script type="text/javascript">
	function resetTravellerForm()
	{
		var phoneno = '<?= $number; ?>';
		var val = $(".clsTravelType:checked").val();
		let readOnly = (val === "1") ? true : false;
		if (phoneno === '')
		{
			$("#fullContactNumber1").val(' ');
		}
		$(".clsInput").attr('readonly', readOnly);

		$(".clsInput").each(function()
		{
			if ($(this).val().trim() === "")
			{
				$(this).attr('readonly', false);
			}
		});
	}

	$(document).ready(function()
	{

		var secondaryTravellerInfo = $("#secondaryTravellerInfo").val();

		var travellerType = '<?= $model->bkg_traveller_type ?>';

		if (travellerType == 2)
		{
			$("#travelling_1").prop("checked", true);
		}
		else
		{
			$("#travelling_0").prop("checked", true);

		}
		resetTravellerForm();
	});

	$('.clsTravelType').change(function()
	{	
		resetTravellerForm();

		let val = $(".clsTravelType:checked").val();
		if (val === "1")
		{
			var fname = '<?= $firstName ?>';
			var lname = '<?= $lastName ?>';
			var phoneno = '<?= $number; ?>';
			var code = '<?= $code; ?>';
			var emailaddress = '<?= $emailId; ?>';
			$(".firstname").val(fname);
			$(".lastname").val(lname);
			if (phoneno)
			{
				$("#fullContactNumber1").val('+' + code + phoneno);
				$('input[name="BookingTemp[fullContactNumber]"]').val('+' + code + phoneno);
			}
			$(".emailaddress").val(emailaddress);

		}
		if (val === "2")
		{
			var fname = '<?= $model->$userFirstName ?>';
			var lname = '<?= $model->bkg_user_lname ?>';
			var phoneno = '<?= $model->bkg_contact_no ?>';
			var emailaddress = '<?= $model->bkg_user_email ?>';

			$('.firstname').val((fname != "") ? fname : '');
			$('.lastname').val((lname != "") ? lname : '');

			$("#fullContactNumber1").val((phoneno != "") ? phoneno : '');
			$('.emailaddress').val((emailaddress != "") ? emailaddress : '');

		}
	});

	function checkTravellerInfo()
	{	
//alert($("#fullContactNumber1").val());
//var phone = $("#cabtravellinfo INPUT[name=BookingTemp[bkg_contact_no]]").val();
//alert(phone);
//if(phone ==='')
//{
//    alert("Phone cannot be blank");
//    return false;
//}
		var isbkpn = '<?= $isbkpn ?>';
		var form = $("form#cabtravellinfo");
		//   alert(form.serialize());
		$.ajax({
			"type": "POST",
			"dataType": "json",
			"url": "<?= CHtml::normalizeUrl($this->getURL('booking/travellerInfo')) ?>",
			"data": form.serialize(),
			"beforeSend": function()
			{
				blockForm(form);
			},
			"complete": function()
			{
				unBlockForm(form);
			},
			"success": function(data)
			{
				
				if (data.success)
				{
					$("INPUT[name=rdata]").val(data.rdata);
					$('.clsusername').html(data.username);
					$('#bkCommonModel').removeClass('fade');
					toastr['success']("Saved Successfully", {
						closeButton: true,
						tapToDismiss: false,
						timeout: 500000
					});
					$('.modal').modal('hide');
					$('#bkCommonModel').removeClass('fade');
					if(isbkpn == 1)
					{
						$('.trvlrname').text(data.username);
						$('.trvlremail').text(data.email);
						$('.trvlrphone').text(data.phoneno);
					}
				}
				else
				{
					var errors = data.errors;
					messages = errors;
					displayError(form, messages);
				}
			},
			"error": function(xhr, ajaxOptions, thrownError)
			{	

				let message = xhr.status.toString() + ": " + thrownError.toString();
				toastr['success'](message, {
					closeButton: true,
					tapToDismiss: false,
					timeout: 500000
				});
				
				Console.log(message);
			}
		});
		return false;

	}

</script>