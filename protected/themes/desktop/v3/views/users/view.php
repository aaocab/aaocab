<style>
	body {overflow-y: scroll !important;}
	.close{ position: absolute; right: 20px;}
	.modal-footer{border-top:0px solid #dee2e6;}
	.selectize-input{ width: 100%;	}
</style>
<?php
//    $userId    = Yii::app()->user->getId();
//    $userModel = Users::model()->findByPk($userId);
//    $userpic  = $userModel->usr_profile_pic;
//    echo '<img src="' . $userpic . '" height="50px">';
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/js/jquery.mask.min.js');
$cityList	 = ['' => 'Select City'] + Cities::model()->getAllCityList();
$readOnly	 = ($flag) ? 'false' : 'true';

$readOnly = ($emailModel->eml_email_address == null) ? '' : 'true';

//var_dump($model->attributes);
?>
<?php
$form = $this->beginWidget('CActiveForm', array(
	'id'					 => 'view-form', 'enableClientValidation' => true,
	'clientOptions'			 => array(
		'validateOnSubmit'	 => true,
		'errorCssClass'		 => 'has-error'
	),
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// See class documentation of CActiveForm for details on this,
	// you need to use the performAjaxValidation()-method described there.
	'enableAjaxValidation'	 => false,
	'errorMessageCssClass'	 => 'help-block',
	'htmlOptions'			 => array(
		'class' => 'form-horizontal',
	),
		));
/* @var $form CActiveForm */
?>
<div class="row">
    <div class="col-12">
		<?php echo CHtml::errorSummary($model); ?>
    </div>
    <div class="col-12 text-center">
		<?php if (Yii::app()->user->hasFlash('success')): ?>
			<div class="alert alert-success" style="padding: 10px">
				<?php echo Yii::app()->user->getFlash('success'); ?>
			</div>
		<?php endif; ?>
    </div>
</div>
<div class="row">
<div class="col-12">
<div class="card">
<div class="card-body">
    <div class="row mb20">
<!--        <div class="col-9">-->
            <div class="col-12 radio-style7">
		<div class="form-check-inline radio radio-glow">
			<?php
			echo $form->radioButton($contactModel, "ctt_user_type", ["value" => "1", "checked" => ($contactModel->ctt_user_type == 1), "id" => "userType_0", "class" => "clsUserType mr5"]);
			?><label class="mb-0 form-check-label" for="userType_0">Individual</label>
		</div>
		<div class="form-check-inline radio radio-glow">
			<?php
			echo $form->radioButton($contactModel, "ctt_user_type", ["value" => "2", "checked" => ($contactModel->ctt_user_type == 2), "id" => "userType_1", "class" => "clsUserType mr5"]);
			?>			<label class="mb-0 form-check-label" for="userType_1">Business</label>
		</div>
	</div>

            <?php
//           $accountStatus = array(1 => 'Individual', 2 => 'Business');
//            echo $form->radioButtonList($contactModel, 'ctt_user_type', $accountStatus,
//                array('separator' => ' ',
//                    'labelOptions' => array('style' => 'display:inline'), // add this code
//            ));
            ?>
<!--           </div>-->
    </div>
<div class="row mb30">
    <div class="col-12">
        <div class="bg-white-box">
            <?php 
           
            //if($contactModel->ctt_user_type == 1)
           // {
            ?>
            
            <div class="row Individualblock">
               
                <div class="col-6 form-group">
                    <label class="control-label">Name <span style="color: #F00"> *</span></label>
					<?= $form->textField($contactModel, 'ctt_first_name', array('placeholder' => 'First Name', 'class' => 'form-control border-radius nameFilterMask')) ?>
                </div>

                <div class="col-6 form-group">
                    <label class="control-label" for="Contact_ctt_last_name">Last name <span style="color: #F00"> *</span></label>
					<?= $form->textField($contactModel, 'ctt_last_name', array('placeholder' => 'Last Name', 'class' => 'form-control border-radius nameFilterMask')) ?>
                </div>
            </div>
            <?php// }else{?>
             <div class="row Businessblock">
               
                <div class="col-6 form-group">
                    <label class="control-label"> Business Name <span style="color: #F00"> *</span></label>
					<?= $form->textField($contactModel, 'ctt_business_name', array('placeholder' => 'Name', 'class' => 'form-control border-radius nameFilterMask')) ?>
                </div>

            </div>
            <?php// }?>
        </div>
    </div>
</div>

<div class="row mb30">
    <div class="col-12">
        <div class="bg-white-box">
            <h5 class="m0 weight400 mb10">Contacts details</h5>
            <div class="row">
				<div class="col-xs-12 col-md-7">
					<div class="form-group">
						<div class="row">
							<?php $this->renderPartial('emailwidget', ['contactModel' => $contactModel, 'form' => $form]); ?>
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-md-5">
					<div class="form-group">
						<div class="row">
							<?php $this->renderPartial('phonewidget', ['contactModel' => $contactModel, 'form' => $form]); ?>
						</div>
					</div>
				</div>

				<div class="col-12 col-lg-6">
                    <div class="form-group">
                        <label for="usr_gender" class="control-label">Gender<span style="color: #F00"> *</span></label>
						<?= $form->dropDownList($model, 'usr_gender', array('' => 'Select Gender', '1' => 'Male', '2' => 'Female'), array('class' => 'form-control')) ?>
                    </div>
                </div>
                <div class="col-12 col-lg-6">
                    <div class="form-group">
                        <label for="Contact_ctt_address" class="control-label">Address <span style="color: #F00"> *</span></label>
						<?= $form->textField($contactModel, 'ctt_address', array('class' => 'form-control border-radius', 'placeholder' => 'Address')) ?>
                    </div>
                </div>
				<!--                <div class="col-12 col-lg-4">
									<div class="form-group">
										<label for="usr_address2" class="control-label">Address Line2</label>
				<? //$form->textFieldGroup($contactModel, 'ctt_address', array('label' => '', 'class' => 'form-control border-radius')) ?> </div>
								</div>
								<div class="col-12 col-lg-4">
									<div class="form-group">
										<label for="usr_address3" class="control-label">Nearby Landmark</label>
				<? //= $form->textFieldGroup($contactModel, 'ctt_address', array('label' => '', 'class' => 'form-control border-radius')) ?>  </div>
								</div>-->
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label for="usr_zip" class="control-label">Zip/Pin Code</label>
						<?= $form->textField($model, 'usr_zip', array('class' => 'form-control border-radius', 'placeholder' => 'Zip Code')) ?>   </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label for="usr_country" class="control-label">Country</label>
						<?php
						$criteria		 = new CDbCriteria();
						$criteria->order = 'country_order DESC';
						$countryList	 = CHtml::listData(Countries::model()->findAll($criteria), 'id', 'country_name');
						$this->widget('ext.yii-selectize.YiiSelectize', array(
							'model'				 => $model,
							'attribute'			 => 'usr_country',
							'data'				 => $countryList,
							'useWithBootstrap'	 => true,
							'placeholder'		 => 'Country',
							'htmlOptions'		 => array('onchange' => "changeCountry(this)"),
							'defaultOptions'	 => array(
								'create'			 => false,
								'persist'			 => false,
								'createOnBlur'		 => true,
								'closeAfterSelect'	 => true,
								'addPrecedence'		 => true,
							),
						));
						?>
                        <input type="hidden" id="countryname" name="countryname" value="<?= $model->usr_country; ?>">
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="form-group" id="statetextdiv">
                        <label for="usr_state_text" class="control-label">State</label>
						<?= $form->textField($model, 'usr_state_text', array('placeholder' => 'Type State', 'class' => 'form-control')) ?>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="form-group" id="statediv">
                        <label for="usr_country" class="control-label">State</label>
						<?php
						$this->widget('ext.yii-selectize.YiiSelectize', array(
							'model'				 => $contactModel,
							'attribute'			 => 'ctt_state',
							'useWithBootstrap'	 => true,
							"placeholder"		 => "State",
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
                     url:'" . CHtml::normalizeUrl(Yii::app()->createUrl('users/countrytostate', ['countryid' => $model->usr_country])) . "',
                     dataType:'json',                  
                     success:function(results){
                         obj.enable();
                         callback(results);
                          $('#Contact_ctt_state')[0].selectize.setValue({$contactModel->ctt_state});
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
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label for="ctt_city" class="control-label">City</label>
						<?= $form->textField($contactModel, 'ctt_city', array('value' => $city['cty_name'], 'class' => 'form-control', 'placeholder' => 'City')) ?>
                    </div>
                </div>
				<div class="col-12 col-lg-8">
                    <div class="form-group">
                        <button type="submit" class="btn btn-md btn-primary text-uppercase hvr-push"  name="sub" value="Submit">Save</button>
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
<?php $this->endWidget(); ?>
<div class="modal fade" id="otpVerifiedModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-body" id="otpDetailsModelBody">
				<?php echo $form->hiddenField($contactModel, "verify_otp", array('type' => "hidden", 'class' => "encodedata", 'value' => "")); ?>
				<input type="hidden" name="checkPrimary" id="checkprimary" value="">	
				<span class="errormsg font-12 text-danger"></span>
				<div class="col-12">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -18px;">
						<i class="fas fa-times font-14"></i>
					</button>
					<div class="clsBilling row pt15">
						<h5 class="modal-title font-14 headermsg"></h5>
						<div class="sales-info d-flex align-items-center">
							<div class="col-6 mt20 text-right">
								<div class="form-group">
									<?= $form->numberField($contactModel, 'phn_verify_otp', array('value' => '', 'class' => 'form-control text-center', 'placeholder' => 'Enter OTP')) ?>
								</div>
							</div>
							<div class="col-6 mt20">
								<div class="form-group">
									<button type="submit" class="btn-orange pl30 pr30 ml0 mr0" onclick="validateContact()" name="sub" value="Submit">Verify</button>
								</div>
							</div>
						</div>
						<h6 class="mb-0 text-right"></h6>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        var userType = <?php echo $contactModel->ctt_user_type;?>;
        if(userType  === 1)
        {
            $('.Individualblock').show();
            $('.Businessblock').hide();
        }else{
            $('.Individualblock').hide();
            $('.Businessblock').show();
        }
        
        $('.clsUserType').change(function()
	{	
        	var val = $(".clsUserType:checked").val();
           // alert(val);
            if(val == 2)
            {
                $('.Individualblock').hide();
                $('.Businessblock').show();
              
            }
            else{
                $('.Individualblock').show();
                $('.Businessblock').hide();
            }
    });
        
        
        $state = '<?= $contactModel->ctt_state ?>';
        $country = '<?= $model->usr_country ?>';
        if ($country == 99)
        {
            $('#statetextdiv').hide();
            $('#statediv').show();
        } else {
            $('#statetextdiv').show();
            $('#statediv').hide();
        }

        $("#email-list").dblclick(function (e) {
            e.preventDefault();
        });

       changestate($('#countryname').val());




        $(window).on('beforeunload', function () {
            $(window).scrollTop(0);
        });
        $('#<?= CHtml::activeId($phoneModel, 'phn_phone_no') ?>').mask("9999999999");
        $('#<?= CHtml::activeId($model, 'usr_zip') ?>').mask("999999");
        //        $('#<?= CHtml::activeId($model, 'usr_alternative_phone') ?>').mask("(999) 999-9999");
        //        $("#mobileVerify").bind('click', mobileVerifyHandler);
        //        $("#emvrify").bind('click', emalVrifyHandler);
        //        $("#<?= CHtml::activeId($model, 'email') ?>").bind('change', emailVerifyHandler);



        $("#view-form").submit(function (event) {



        });

        $("#Users_usr_state1").change(function () {

            var stid = $("#Users_usr_state").val();
            var href2 = '<?= Yii::app()->createUrl("users/cityfromstate"); ?>';
            $.ajax({
                "url": href2,
                "type": "GET",
                "dataType": "json",
                "data": {"id": stid},
                "success": function (data1) {

                    $data2 = data1;
                    var placeholder = $('#<?= CHtml::activeId($model, "usr_city") ?>').attr('placeholder');
                    $('#<?= CHtml::activeId($model, "usr_city") ?>').select2({data: $data2, placeholder: placeholder});
                }
            });
        });

        $(document).on('click', '.phonePrimary', function () {
            $('.phonePrimary').prop("disabled", false);
            $(this).prop("disabled", true);
            $('.phone_primary').each(function () {
                $(".phone_primary").val(0);
                $('.phonePrimary').removeClass('btn-success');
                $('.phonePrimary').addClass('btn-primary');
                $('.phonePrimary').children('i').removeClass('fa-check-square-o');
                $('.phonePrimary').children('i').addClass('fa-square-o');
            });
            $(this).closest('tr').children('.phone_primary').val(1);
            $(this).closest('td').children('.phonePrimary').removeClass('btn-primary');
            $(this).closest('td').children('.phonePrimary').addClass('btn-success');
            $(this).closest('td').children('.phonePrimary').children('i').removeClass('fa-square-o');
            $(this).closest('td').children('.phonePrimary').children('i').addClass('fa-check-square-o');

        });
    });
    function changestate(selectizeControl)
    {

//        var href2 = '<?= Yii::app()->createUrl("users/countrytostate"); ?>';
//        $.ajax({
//            "url": href2,
//            "type": "GET",
//            "dataType": "json",
//            "data": {"countryid": selectizeControl},
//            "success": function(data1) {
//
//
//                $data2 = data1;
//                var placeholder = $('#<?= CHtml::activeId($model, "usr_state") ?>').attr('placeholder');
//                $('#<?= CHtml::activeId($model, "usr_state") ?>').select2({data: $data2, placeholder: placeholder});
//            }
//        });
    }

    function changeCountry(obj)
    {
        var selectize = $('#Contact_ctt_state')[0].selectize;
        var country = obj.value;
        if (country != 99)
        {
            $('#statetextdiv').show();
            $('#statediv').hide();
        } else {

            $('#statetextdiv').hide();
            $('#statediv').show();

            var href2 = '<?= Yii::app()->createUrl("users/countrytostate"); ?>';
            $.ajax({
                "url": href2,
                "type": "GET",
                "dataType": "json",
                "data": {"countryid": country},
                "success": function (data1) {
                    selectize.clearOptions();
                    selectize.addOption(data1);
                    selectize.refreshOptions(false);
                    //$('select').selectize(options);
                }
            });
        }
    }

    $('.profilesave').click(function (event) {

        var profcontact = $.trim($('#fullContactNumber3').val());
        var cont = profcontact.replace(/\s/g, '');
        if (profcontact == "")
        {
            alert('Mobile no cannot be blank');
            return false;
        } else if (cont.length < 10 || cont.length > 12 || isInteger(profcontact) == false)
        {
            alert('Invalid mobile no');
            return false;
        } else
        {
            return true;
        }
    });

    function isInteger(s) {
        var i;
        s = s.toString();
        for (i = 0; i < s.length; i++) {
            var c = s.charAt(i);
            if (isNaN(c)) {
                return false;
            }
        }
        return true;
    }

    function addContact(type)
    {	
     
        var cttId = '<?= $contactModel->ctt_id ?>';
        $href = "<?= Yii::app()->createUrl('contact/AddContactDetails') ?>";
        jQuery.ajax({type: 'GET',
            url: $href,
            dataType: 'html',
          //  dataType: 'json',
           // "async": true,
            data: {"cttId": cttId, "type": type},
            success: function (data)
            {
              
               // alert(data);
                acctbox = bootbox.dialog({
                    message: data,
                    onEscape: function ()
                    {	
						$('body').removeClass('modal-open');
						$('.modal-backdrop').remove();
					}
                });
            },
			error: function (xhr, status, error) {
                alert('Sorry error occured');
            }
			
        });
    }

    function verifyContact(value, id, type)
    {
        bootbox.confirm({
            message: "Are you sure want to verify <b>" + value + "</b> ?",
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
                    $.ajax({
                        type: 'POST',
                        url: '<?php echo Yii::app()->createUrl('contact/VerifyData'); ?>',
                        data: {"value": value, "cttid": id, "type": type, 'YII_CSRF_TOKEN': "<?= Yii::app()->request->csrfToken ?>"},
                        dataType: 'json',
                        success: function (data2)
                        {
                            obj = data2.data;
                            if (obj)
                            {
                                modalmsg = "We have sent a one-time password (OTP) to " + value + ". Enter it here to proceed";
                                $('.encodedata').val(obj.encCode);
                                $('.headermsg').html(modalmsg);
                                $('#otpVerifiedModel').removeClass('fade');
                                $('#otpVerifiedModel').css("display", "block");
                                $('#otpVerifiedModel').modal('show');
                            } else
                            {
                                var errors = data2.errors;
                                var txt = "<h5>Please fix the following input errors:</h5><ul style='list-style:none'>";
                                $.each(errors, function (key, value)
                                {
                                    txt += "<li>" + value + "</li>";
                                });
                                txt += "</li>";
                                bootbox.alert(txt);
                            }
                        }
                    });
                }
            }
        });
    }

    function validateContact()
    {
        var otpHash = $('.encodedata').val();
        var cttId = '<?= $contactModel->ctt_id ?>';
        var code = $('#Contact_phn_verify_otp').val();
        var isprimary = $('#checkprimary').val();
        var href2 = '<?= Yii::app()->createUrl("contact/ValidateOtp"); ?>';
        $.ajax({
            "url": href2,
            "type": "GET",
            "async": true,
            "dataType": 'json',
            data: {"cttid": cttId, "otphash": otpHash, "code": code},
            "success": function (response)
            {
                if (response.success)
                {
                    alert(response.message);
                    $('#otpVerifiedModel').modal('hide');
                    if (isprimary == '')
                    {
                        location.reload();
                    } else {
                        contactData = response.data;
                        primaryContact(contactData.value, cttId, contactData.type);
                    }
                }
            },
            "error": function (response)
            {
                $('.errormsg').html("Sorry! OTP doesn't match.");
            }
        });
    }

    function primaryContact(value, id, type)
    {
        bootbox.confirm({
            message: "Are you sure want to set primary this contact?",
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
                    $.ajax({
                        type: 'POST',
                        url: '<?php echo Yii::app()->createUrl('/contact/SetPrimaryVerifiedContact'); ?>',
                        data: {"value": value, "id": id, "type": type, 'YII_CSRF_TOKEN': "<?= Yii::app()->request->csrfToken ?>"},
                        dataType: 'json',
                        success: function (data2)
                        {
                            obj = data2.data;
                            if (obj)
                            {
                                modalmsg = "We have sent a one-time password (OTP) to " + value + ". Enter it here to proceed";
                                $('.headermsg').html(modalmsg);
                                $('.encodedata').val(obj.encCode);
                                $('#checkprimary').val(2);
                                $('#otpVerifiedModel').removeClass('fade');
                                $('#otpVerifiedModel').css("display", "block");
                                $('#otpVerifiedModel').modal('show');
                            }
                            if (data2.success && obj == undefined)
                            {
                                location.reload();
                            } else if (data2.errors)
                            {
                                var errors = data2.errors;
                                var txt = "<h5>Please fix the following input errors:</h5><ul style='list-style:none'>";
                                $.each(errors, function (key, value)
                                {
                                    txt += "<li>" + value + "</li>";
                                });
                                txt += "</li>";
                                bootbox.alert(txt);
                            }
                        }
                    });
                }
            }
        });
    }

    function removeContact(value, id, type)
    {
        bootbox.confirm({
            message: "Are you sure want to remove this contact?",
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
                    $.ajax({
                        type: 'POST',
                        url: '<?php echo Yii::app()->createUrl('/contact/removeContact'); ?>',
                        data: {"value": value, "id": id, "type": type, 'YII_CSRF_TOKEN': "<?= Yii::app()->request->csrfToken ?>"},
                        dataType: 'json',
                        success: function (data2)
                        {
                            if (data2.success)
                            {
                                bootbox.alert(data2.message);
                                location.reload();
                            }
                            if (data2.errors)
                            {
                                var errors = data2.errors;
                                var txt = "<h5>Please fix the following input errors:</h5><ul style='list-style:none'>";
                                $.each(errors, function (key, value)
                                {
                                    txt += "<li>" + value + "</li>";
                                });
                                txt += "</li>";
                                bootbox.alert(txt);
                            }
                        },
                        error: function (response)
                        {
                            $('.errormsg').html("Sorry! OTP doesn't match.");
                        }
                    });
                }
            }
        });
    }
</script>
