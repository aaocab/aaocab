<?php
$this->layout	 = 'column1';
?>
<div>
	<?
	$version		 = Yii::app()->params['siteJSVersion'];
	Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/fontawesome-web/css/all.min.css?v0.6');
	Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/bookNow.js?v=' . $version);
	Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');
	Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/js/jquery.mask.min.js');
	$cityList		 = ['' => 'Select City'] + Cities::model()->getAllCityList();
	?>
	<?
	$form			 = $this->beginWidget('CActiveForm', array(
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
	?>
	<?php if (CHtml::errorSummary($model)): ?>
		<div class="notification-small notification-red">				
			<div><?php echo CHtml::errorSummary($model); ?></div>				
		</div>				
	<?php endif; ?>

	<?php //echo CHtml::errorSummary($model);  ?>
	<?php if (Yii::app()->user->hasFlash('success')): ?>
		<div class="content-boxed-widget text-center color-green3-dark font-16"><?php echo Yii::app()->user->getFlash('success'); ?> </div>
	<?php endif; ?>

	<div class="content-boxed-widget">
		<h3 class="mb10">My Profile</h3>
		<div class="content p0 bottom-0">
			<div class="one-half">
				<div class="input-simple-1 has-icon input-green bottom-20"><em>First Name</em>					
					<?= $form->textField($contactModel, 'ctt_first_name', array('label' => '', 'class' => 'form-control border-radius nameFilterMask', 'required' => '')) ?>

				</div>
			</div>
			<div class="one-half last-column">
				<div class="input-simple-1 has-icon input-green bottom-20"><em>Last Name</em>
					<?= $form->textField($contactModel, 'ctt_last_name', array('label' => '', 'class' => 'form-control border-radius nameFilterMask')) ?>
				</div>
			</div>
			<div class="clear"></div>
		</div>
	</div>

	<div class="content-boxed-widget">
		<h3 class="mb10">Contacts Details</h3>
		<div class="content p0 bottom-0">
			<div class="input-simple-1 has-icon input-green bottom-10">
				<? //= $form->textField($emailModel, 'eml_email_address', array('class' => 'form-control border-radius', 'label' => '', 'widgetOptions' => array('htmlOptions' => array('readOnly' => true)))) ?>
				<?php $this->renderPartial('emailwidget', ['contactModel' => $contactModel, 'form' => $form]); ?>
			</div>
		</div>
		<div class="content p0 bottom-0">
			<div class="input-simple-1 has-icon input-blue bottom-30">
				<div class="bottom-30">
					<?php
					$this->renderPartial('phonewidget', ['contactModel' => $contactModel, 'form' => $form]);
					?> 
				</div>

			</div>
		</div>
		<div class="content p0 bottom-0">
			<div class="select-box select-box-1 mt30 mb20">					
				<em>Gender</em>
				<?= $form->dropDownList($model, 'usr_gender', array('' => 'Select Gender', '1' => 'Male', '2' => 'Female')) ?>
			</div>
		</div>
		<div class="content p0 bottom-0">
			<div class="input-simple-1 has-icon input-green bottom-20"><em>Address</em><i class="fas fa-map-marker-alt"></i>
				<?= $form->textField($contactModel, 'ctt_address', array('label' => '', 'class' => 'form-control border-radius')) ?>
			</div>
<!--					<div class="input-simple-1 has-icon input-green bottom-20"><em>Address Line2</em><i class="fas fa-map-marker-alt"></i>
			<?= $form->textField($model, 'usr_address2', array('label' => '', 'class' => 'form-control border-radius')) ?>
				</div>
				<div class="input-simple-1 has-icon input-green bottom-20"><em>Nearby Landmark</em><i class="fas fa-map-marker-alt"></i>
			<?= $form->textField($model, 'usr_address3', array('label' => '', 'class' => 'form-control border-radius')) ?>  
				</div>-->
			<div class="input-simple-1 has-icon input-green bottom-20"><em>Zip Code</em>
				<?= $form->textField($model, 'usr_zip', array('label' => '', 'class' => 'border-radius')) ?>
			</div>
			<div class="select-box-5 select-box-1 mt30 mb20">
				<em>Country</em>
				<?php
				$criteria		 = new CDbCriteria();
				$criteria->order = 'country_order DESC';
				$countryList	 = CHtml::listData(Countries::model()->findAll($criteria), 'id', 'country_name');
				$this->widget('ext.yii-selectize.YiiSelectize', array(
					'model'				 => $model,
					'attribute'			 => 'usr_country',
					'data'				 => $countryList,
					'useWithBootstrap'	 => false,
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
			</div>


			<div class="input-simple-1 has-icon input-green bottom-20" id="statetextdiv">
				<label for="usr_state_text" class="col-sm-4 control-label">State</label>
				<?= $form->textField($model, 'usr_state_text', array('label' => '')) ?>
			</div>
			<div id="statediv">
				<div class="input-simple-1 has-icon input-green bottom-20"><em>State</em>
					<?php
					$this->widget('ext.yii-selectize.YiiSelectize', array(
						'model'				 => $contactModel,
						'attribute'			 => 'ctt_state',
						'useWithBootstrap'	 => false,
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
			<div class="input-simple-1 has-icon input-green bottom-20"><em>City</em>
				<?= $form->textField($contactModel, 'ctt_city', array('value' => $city['cty_name'])) ?>
			</div>
			<div class="content text-center mb20 p30">

				<button type="button" class="uppercase btn-orange shadow-medium" id="profsubmit" name="sub" value="Submit">Save</button>
			</div>				
		</div>
		<div class="clear"></div>
	</div>
	<?php $this->endWidget(); ?>

	<div id="menu-verify-modal" data-selected="menu-components" data-width="340" data-height="240" class="menu-box menu-modal">
		<div class="menu-title border-none">
			<a href="#" class="menu-hide pt0 line-height42"><i class="fa fa-times"></i></a>
			<div class="errormsg font-12 text-danger"></div>
			<input type="hidden" name="checkPrimary" id="checkprimary" value="">	
			<?php echo $form->hiddenField($contactModel, "verify_otp", array('type' => "hidden", 'class' => "encodedata", 'value' => "")); ?>
			<h5 class="modal-title font-14 headermsg mt15"></h5>
<div class="input-simple-2 has-icon input-green bottom-15">
			<?= $form->textField($contactModel, 'phn_verify_otp', array('value' => '', 'class' => 'form-control text-center phn_verify_otp', 'placeholder' => 'Enter OTP')) ?>
			</div>
<div class="col-6 mt20">
				<div class="form-group text-center">
					<button type="submit" class="button btn-sm shadow-medium button-blue" onclick="validateContact()" name="sub" value="Submit">Verify</button>
				</div>
			</div>
		</div>         
		<div id="verifybody" class="menu-list content">
		</div>    
	</div>

	<script type="text/javascript">
        $jsBookNow = new BookNow();
        $(document).ready(function ()
        {
            $state = '<?= $contactModel->ctt_state ?>';
            $country = '<?= $model->usr_country ?>';
            if ($country == 99)
            {
                $('#statetextdiv').hide();
                $('#statediv').show();
            } else
            {
                $('#statetextdiv').show();
                $('#statediv').hide();
            }
            //changestate($('#countryname').val());
            $(window).on('beforeunload', function ()
            {
                $(window).scrollTop(0);
            });
            $('#<?= CHtml::activeId($phoneModel, 'phn_phone_no') ?>').mask("9999999999");
            $('#<?= CHtml::activeId($model, 'usr_zip') ?>').mask("999999");
            $("#Users_usr_state1").change(function ()
            {
                var stid = $("#Users_usr_state").val();
                var href2 = '<?= Yii::app()->createUrl("users/cityfromstate"); ?>';
                $.ajax({
                    "url": href2,
                    "type": "GET",
                    "dataType": "json",
                    "data": {"id": stid},
                    "success": function (data1)
                    {

                        $data2 = data1;
                        var placeholder = $('#<?= CHtml::activeId($model, "usr_city") ?>').attr('placeholder');
                        $('#<?= CHtml::activeId($model, "usr_city") ?>').select2({data: $data2, placeholder: placeholder});
                    }
                });
            });


        });

        function changeCountry(obj)
        {
            var selectize = $('#Contact_ctt_state')[0].selectize;
            var country = obj.value;
            if (country != 99)
            {
                $('#statetextdiv').show();
                $('#statediv').hide();
            } else
            {
                $('#statetextdiv').hide();
                $('#statediv').show();

                var href2 = '<?= Yii::app()->createUrl("users/countrytostate"); ?>';
                $.ajax({
                    "url": href2,
                    "type": "GET",
                    "dataType": "json",
                    "data": {"countryid": country},
                    "success": function (data1)
                    {
                        selectize.clearOptions();
                        selectize.addOption(data1);
                        selectize.refreshOptions(false);
                    }
                });
            }
        }

        $('#profsubmit').click(function () {
            var usercontact = $.trim($('.fullContactNumber0').text());
            var cont = usercontact.replace(/\s/g, '');
            var msg = "";
            var is_error = 0;
            var uemail = $(".eml_email_address0").text();
            if ($.trim($("#Contact_ctt_first_name").val()) == "")
            {
                msg += "First name cannot be blank<br/>";
                is_error++;
            }
            if ($.trim($("#Contact_ctt_last_name").val()) == "")
            {
                msg += "Last name cannot be blank<br/>";
                is_error++;
            }
            if ($.trim(uemail) == "")
            {
                msg += "Email cannot be blank<br/>";
                is_error++;
            } else if (!$jsBookNow.validateEmail(uemail))
            {
                msg += "Email is not valid<br/>";
                is_error++;
            }

            if (usercontact == "")
            {
                msg += 'Mobile no cannot be blank<br/>';
                is_error++;
            } else if (cont.length < 10 || cont.length > 12)
            {
                msg += 'Invalid mobile no<br/>';
                is_error++;
            } else if (isInteger(usercontact) == false) {
                msg += 'Invalid mobile no<br/>';
                is_error++;
            }

            if (is_error > 0) {

                $jsBookNow.showErrorMsg(msg);
                return false;
            } else {
                $('#view-form').submit();
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
            $href = "<?= Yii::app()->createUrl('contact/addContactDetails') ?>";
            jQuery.ajax({type: 'GET',
                url: $href,
                dataType: 'html',
                data: {"cttId": cttId, "type": type},
                success: function (data)
                {
						$('#addContactbody').html(data);
						var flwup = $('#flwup').val();
						if (flwup) {
							$('#menu-addcontact-modal').data('height', '250');
							$('#menu-addcontact-modal h2').remove();
						}
						$('a[data-menu="menu-addcontact-modal"]').click();
					}
            });
        }

        function verifyContact(value, id, type)
        {
            var confirmverify = confirm("Are you sure want to verify " + value + " ?");
            if (confirmverify) {
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

                            $('#verifybody').html(data2);
							$('.headermsg').html(modalmsg);
							$('.encodedata').val(obj.encCode);
                            var verifybody = $('#verifybody').val();
                            if (verifybody) {
                                $('#menu-verify-modal').data('height', '250');
                                $('#menu-verify-modal h2').remove();
                            }

                            $('a[data-menu="menu-verify-modal"]').click();
                        } else
                        {
                            var errors = data2.errors;
                           // var txt = "<ul style='list-style:none'>";
                            $.each(errors, function (key, value)
                            {
                                txt =  value;
                            });
                            
                            $jsBookNow.showErrorMsg(txt);
                        }
                    }
                });
            }
            {
                return false;
            }
        }

        function validateContact()
        {
            var otpHash = $('.encodedata').val();
            var cttId = '<?= $contactModel->ctt_id ?>';
            var code = $('.phn_verify_otp').val();
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
			var confirmprimary = confirm("Are you sure want to set primary this contact?");
            if (confirmprimary) {
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
							
                            $('#verifybody').html(data2);
							$('.headermsg').html(modalmsg);
							$('.encodedata').val(obj.encCode);
							$('#checkprimary').val(2);
                            var verifybody = $('#verifybody').val();
                            if (verifybody) {
                                $('#menu-verify-modal').data('height', '250');
                                $('#menu-verify-modal h2').remove();
                            }

                            $('a[data-menu="menu-verify-modal"]').click();
						}
						if (data2.success && obj == undefined)
						{
							location.reload();
						}
					}
				});
			}
			{
                return false;
            }
        }
		
	function removeContact(value, id, type)
    {
		var isremove = confirm("Are you sure want to remove this contact?");
        
		if (isremove) {
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
						var txt = "";
						$.each(errors, function (key, value)
						{
							txt = value;
						});
						
						$jsBookNow.showErrorMsg(txt);
					}
				},
				error: function (response)
				{
					$('.errormsg').html("Sorry! OTP doesn't match.");
				}
			});
		}
    }
	</script>
