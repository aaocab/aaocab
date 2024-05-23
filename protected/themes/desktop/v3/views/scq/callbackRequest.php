<style>
	.pl80{
		padding-left: 80px!important;
	}
</style>
<div class="container p0">
<div class="row justify-center">
		<?php
		if ($bkgstatus != 9)
		{
			?><div class="col-lg-12 mb5 text-center merriw font-18 hide"><?php echo ServiceCallQueue::getReasonList($refType) ?></div><?php } ?>
<div class="col-12">
			<?php
			$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
				'id'					 => 'nwbkgCallback-form',
				'enableClientValidation' => TRUE,
				'clientOptions'			 => array(
					'validateOnSubmit'	 => true,
					'errorCssClass'		 => 'has-error'
				),
				'enableAjaxValidation'	 => false,
				'errorMessageCssClass'	 => 'help-block',
				'htmlOptions'			 => array(
					'class' => 'form-horizontal',
				),
			));
			/* @var $form TbActiveForm */
			/** @var Users $umodel */
			echo $form->hiddenField($model, 'scq_follow_up_queue_type');
			?>

				<div class="row">
					
					<div class="col-sm-12 mb5">
						<div class="row">
							<label class="col-sm-4">First Name: </label>
							<span  class="form-control input-horizontal bg bg-gray col-sm-7 ml15 mr15"><?php echo $umodel->usr_name ?></span>
						</div>
					</div>
				</div>
				<div class="row mt10">
					<div class="col-sm-12 mb5">
						<div class="row">
							<label class="col-sm-4">Last Name: </label>
							<span  class="form-control input-horizontal bg bg-gray col-sm-7 ml15 mr15"><?php echo $umodel->usr_lname ?></span>
						</div>
					</div>

				</div>
				<div class="row mt10">
					<div class="col-sm-12 mb5">
						<div class="row">
							<label class="col-sm-4">Email: </label>
							<span  class="form-control input-horizontal bg bg-gray col-sm-7 ml15 mr15"><?php echo $umodel->usr_email ?></span>
						</div>
					</div>

				</div>
			<?php
			if ($bkgstatus != 9)
			{
				?>
				<div class="row mt10">
					<div class="col-sm-12 mb5">
						<div class="row">
							<label class="col-sm-4">Reason: </label>
							<span  class="form-control input-horizontal bg bg-gray col-sm-7 ml15 mr15 scqreason"><?php echo ServiceCallQueue::getReasonList($refType) ?></span>	
						</div>
					</div>

				</div>
				<?php
			}
			else
			{
				?>
				<div class="row mt10">
					<div class="col-sm-12 mb5">
						<div class="row">
							<label class="col-sm-4">Follow up type: </label>
							<?php
							$followupTypesArr	 = Filter::getJSON([1 => 'I want to re-book a cab', 2 => 'I have an issue with this booking']);
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'scq_follow_up_queue_type',
								'val'			 => $model->scq_follow_up_queue_type,
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($followupTypesArr)),
								'htmlOptions'	 => array('style' => 'margin-left:15px; width:58%;', 'placeholder' => 'Select Followup type', 'id' => 'scq_follow_up_queue_type')
							));
							?>
						</div>
					</div>

				</div>
				<?php
				}
				if (in_array($refType, [1]))
				{
					echo $form->hiddenField($model, 'scq_related_bkg_id');
				}
				if (in_array($refType, [2, 4]))
				{
					?>
					<div class="row mt10">
						<div class="col-sm-12 mb5">
							<div class="row">
								<label class="col-sm-4" for="ServiceCallQueue_scq_related_bkg_id">Booking Id <span class="<?php echo ($refType == 2) ? '' : 'hide' ?>" style="color: red;font-size: 15px;">*</span>: </label>
								<?php echo $form->textField($model, 'scq_related_bkg_id', ['class' => 'form-control ml15 input-horizontal col-sm-7', 'placeholder' => "Starting with OW/RT/MW"]) ?> 
								<span class="col-sm-12 pr0 ml15 mr15" id="bkgValTxt" style="display: none"></span>
							</div>
						</div>
					</div>
				<?php
			}
				
				$isPhoneExist = ($primaryPhone != '' && Filter::validatePhoneNumber($primaryPhone));
				?>
				<div class="row mt10">
<!--					<div class="col-12 col-xl-12 mb5 <?//($isPhoneExist) ? "":"hide"?>">
						<div class="row">
							<label class="col-12 col-sm-4">Phone: </label>
							<div class="col-12 col-sm-8">
								<div class="input-group">
									<span  class="form-control input-horizontal bg bg-gray"><?php //echo "+".$primaryPhone ?></span>
									<div class="input-group-append" id="button-addon2">
										<button class="btn btn-primary" type ="button" id="addphoneBtn"><strong>+</strong></button>
									</div>
								</div>
							</div>
						</div>
					</div>-->
					<div class="col-12 mt5 <? //(!$isPhoneExist) ? "":"hide"?>" id="contactNo">
						<div class="row ">
							<label class="col-12 col-sm-4">Phone: </label>						 
							<div class="col-12 col-sm-8 ">
								<?php 
                                        $this->widget('ext.intlphoneinput.IntlPhoneInput', array(
											'model'					 => $model,
                                             'attribute'				 => 'fullContactNumber',
											//'attribute'				 => 'scq_to_be_followed_up_with_value',
											'codeAttribute'			 => 'countrycode',
											'numberAttribute'		 => 'scq_to_be_followed_up_with_value',
											'options'				 => array(// optional
												'separateDialCode'	 => true,
												'autoHideDialCode'	 => true,
												'initialCountry'	 => 'in'
											),
											'htmlOptions'			 => ['class' => 'form-control phoneno pl80 form-control input-horizontal bg bg-gray', 'value' => "+".$primaryPhone,'id' => 'fullContactNumber', 'style' => 'width:290px;', 'required' => true],
											'localisedCountryNames'	 => false,
										));
								?>
								<span class="col-sm-4 pr0" id="phnValText" style="display: none"></span>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12 mb5">
						
						<div class="row mt10">
							<div class="col-12 mb5">
								<label>Notes for customer agent <span style="color: red;font-size: 15px;">*</span>:</label>
							</div>
						</div>
						<div class="row">
							<div class="col-12 mb5">
								<?php echo $form->textArea($model, 'scq_creation_comments', ['class' => "form-control", 'placeholder' => "Enter Description", "cols" => "50", "rows" => "3"]) ?>
							</div>
						</div>

						<div class="row mt10">
							<div class="col-12 mb5 text-center">  
								<button type="button" class="btn btn-lg btn-primary btn-sm" name="downloadBtn" id="registerCMB">CALL ME BACK</button>
							</div> 
						</div>	 
					</div>

				</div>
<!--			<div id="waitTime" class=""><span style="color: red;font-size: 15px;"><sup>*</sup></span>Expected call back time: 60 minutes</div>-->
			<?php $this->endWidget(); ?>
</div>
</div>
</div> 
<script type="text/javascript">

    $('#registerCMB').click(function ()
    {
       // validateBookingid();
       registerCMB();
    });
    function registerCMB() {
        var obj1 = document.getElementById("ServiceCallQueue_scq_to_be_followed_up_with_value");
        if ($("#ServiceCallQueue_scq_creation_comments").val() == '')
        {
            var obj2 = document.getElementById("ServiceCallQueue_scq_creation_comments");
            obj2.focus();
            return false;
        }
        if ($("#ServiceCallQueue_scq_to_be_followed_up_with_value").val() == '')
        {
            obj1.focus();
            return false;
        }
//        var refid = $('#ServiceCallQueue_scq_related_bkg_id').val();
//        var reftype = $('#ServiceCallQueue_scq_follow_up_queue_type').val();
        var form = $('#nwbkgCallback-form');
        
//        alert(refid);
//        alert(reftype);
//        alert(form.serialize());
        
        $href = "<?php echo Yii::app()->createUrl('scq/storeCallBackData') ?>";
        jQuery.ajax({type: 'POST',
            url: $href,
            data: form.serialize(),
            "dataType": "html",
			"beforeSend": function ()
			{
				blockForm(form);
			},
			"complete": function ()
			{
				unBlockForm(form);
			},
            success: function (data1)
            {
             //   debugger;
              var data2 = "";
               
               var isJSON = false;
               try
				{
					data2 = JSON.parse(data1);
					isJSON = true;
				} catch (e)
				{

				}
                 if (!isJSON)
				{
                $('#helpLineModal').modal('hide');
                $('#bkCommonModel').removeClass('fade');
                $('#bkCommonModel').css("display", "block");
                $('#bkCommonModelBody').html(data1);
                $('#bkCommonModel').modal('show');  
                }else{
                    $("#nwbkgCallback-form").show();
                }
                if(data2.type == 'booking'&& data2.success == false)
                {
                        $('#bkgValTxt').css('color', 'green');
                        $('#bkgValTxt').html('<i class="fa fa-check"></i>');
                        $('#bkgValTxt').show();
                        if (!data2.success)
                        {
                            data2.flag == 1 ? $('#bkgValTxt').html('<span style="color: red;font-size: 15px;">*</span> Booking id required') : $('#bkgValTxt').html('<i class="fa fa-times"></i> Wrong booking id');
                            $('#bkgValTxt').css('color', 'red');
                            return false;
                        }
                }
                if(data2.type == 'phone' && data2.success == false)
                {
             
                    $('#phnValText').css('color', 'red');
					$('#phnValText').html('<span style="color: red;font-size: 15px;">*</span>Invalid phone number');
					$('#phnValText').show();
                    return false;
                }
            }
        });
    }
//    function validatePhoneno() {
//
//        var phone = $('#ServiceCallQueue_scq_to_be_followed_up_with_value').val();
//        $href = "<?php //echo Yii::app()->createUrl('lookup/validatePhone') ?>";
//        jQuery.ajax({type: 'GET',
//            "url": $href,
//            data: {'phone': phone},
//            "dataType": "json",
//            success: function (data1)
//            {
//                if (!data1.success)
//                {
//                    $('#phnValText').css('color', 'red');
//					$('#phnValText').html('<span style="color: red;font-size: 15px;">*</span>Invalid phone number');
//					$('#phnValText').show();
//                    return false;
//                }
//                registerCMB();
//            }
//        });
//
//    }
//    function validateBookingid() {
//        var refid = $('#ServiceCallQueue_scq_related_bkg_id').val();
//        var reftype = $('#ServiceCallQueue_scq_follow_up_queue_type').val();
//        $href = "<?php //echo Yii::app()->createUrl('lookup/validateBooking') ?>";
//        jQuery.ajax({type: 'GET',
//            "url": $href,
//            data: {'refid': refid, 'reftype': reftype},
//            "dataType": "json",
//            success: function (data1)
//            {
//                $('#bkgValTxt').css('color', 'green');
//                $('#bkgValTxt').html('<i class="fa fa-check"></i>');
//                $('#bkgValTxt').show();
//                if (!data1.success)
//                {
//                    data1.flag == 1 ? $('#bkgValTxt').html('<span style="color: red;font-size: 15px;">*</span> Booking id required') : $('#bkgValTxt').html('<i class="fa fa-times"></i> Wrong booking id');
//                    $('#bkgValTxt').css('color', 'red');
//                    return false;
//                }
//                validatePhoneno();
//            }
//        });
//
//    }
</script>
