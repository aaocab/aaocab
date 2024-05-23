<style>
	.form-control.input-horizontal    {
        display: inline !important;
		width: auto !important; 
    }

</style>

<div class=" container ">
	<div class="panel panel-primary">
		<div class="panel-body">
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
			<div class="p20 pl30  border">

				<div class="row mt10">
					<div class="col-sm-12">
						<div class="row">
							<label class="col-sm-4">First Name: </label>
							<span  class="form-control input-horizontal bg bg-gray col-sm-6"><?php echo $umodel->usr_name ?></span>
						</div>
					</div>
				</div>
				<div class="row mt10">
					<div class="col-sm-12">
						<div class="row">
							<label class="col-sm-4">Last Name: </label>
							<span  class="form-control input-horizontal bg bg-gray col-sm-6"><?php echo $umodel->usr_lname ?></span>
						</div>
					</div>

				</div>
				<div class="row mt10">
					<div class="col-sm-12">
						<div class="row">
							<label class="col-sm-4">Email: </label>
							<span  class="form-control input-horizontal bg bg-gray col-sm-6"><?php echo $umodel->usr_email ?></span>
						</div>
					</div>

				</div>
				<div class="row mt10">
					<div class="col-sm-12">
						<div class="row">
							<label class="col-sm-4">Reason: </label>
							<span  class="form-control input-horizontal bg bg-gray col-sm-6"><?php echo ServiceCallQueue::getReasonList($refType) ?></span>	
						</div>
					</div>

				</div>
				<?php
				if (in_array($refType, [2, 4]))
				{
					?>
					<div class="row mt10">
						<div class="col-sm-12 ">
							<div class="row">
								<label class="col-sm-4" for="ServiceCallQueue_scq_related_bkg_id">Booking Id <?php ?><span class="<?php echo ($refType == 2) ? '' : 'hide' ?>" style="color: red;font-size: 15px;">*</span>: </label>
								<?php echo $form->textField($model, 'scq_related_bkg_id', ['class' => 'form-control input-horizontal col-sm-6', 'placeholder' => "Starting with OW/RT/MW"]) ?> 
								<span class="col-sm-12 pr0 " id="bkgValTxt" style="display: none"></span>
							</div>
						</div>
					</div>
				<?php } ?>
				<div class="row mt10">
					<div class="col-sm-12">
						<div class="row">
							<label for="Users_usr_mobile" class="col-sm-4">Phone: </label>
							<span  class="form-control input-horizontal bg bg-gray  col-sm-6"><?php echo $primaryPhone ?></span>
						</div>
					</div>
				</div>
				<div class="row mt10">
					<div class="col-sm-12">
						<div class="row">
							<div class="col-sm-4   "> </div>						 
							<span class="col-sm-4 pl0" id="contactNo"style="display: none">
								<?php echo $form->textField($model, 'scq_to_be_followed_up_with_value', ['class' => 'form-control', 'placeholder' => 'Add new number']) ?>
								<span class="col-sm-4 pr0" id="phnValText" style="display: none"></span>
							</span>
							<div class="col-sm-6  ">
								<button class="btn btn-success" type ="button" id="addphoneBtn">Add new phone</button>
							</div>
						</div>
						<div class="row mt10">
							<div class="col-xs-12">
								<label>Notes for customer agent <span style="color: red;font-size: 15px;">*</span>:</label>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-12">
								<?php echo $form->textArea($model, 'scq_creation_comments', ['class' => "form-control", 'placeholder' => "Enter Description", "cols" => "50", "rows" => "3"]) ?>
							</div>
						</div>

						<div class="row mt10">
							<div class="col-xs-12  text-center">  
								<button type="button" class="btn btn-success" name="downloadBtn" id="registerCMB">CALL ME BACK</button>
							</div> 
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
    $('#addphoneBtn').click(function ()
    {
        $('#contactNo').show();
    })
    $('#registerCMB').click(function ()
    {
        validateBookingid();
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
        $href = "<?php echo Yii::app()->createUrl('scq/storeCallBackData') ?>";
        jQuery.ajax({type: 'POST',
            url: $href,
            data: $('#nwbkgCallback-form').serialize(),
            "dataType": "html",
            success: function (data)
            {
                $('#helpLineModal').modal('hide');
                $('#callmeback').removeClass('fade');
                $('#callmeback').css("display", "block");
                $('#callmebackBody').html(data);
                $('#callmeback').modal('show');

            }
        });
    }
    function validatePhoneno() {

        var phone = $('#ServiceCallQueue_scq_to_be_followed_up_with_value').val();
        $href = "<?php echo Yii::app()->createUrl('lookup/validatePhone') ?>";
        jQuery.ajax({type: 'GET',
            "url": $href,
            data: {'phone': phone},
            "dataType": "json",
            success: function (data1)
            {

                $('#phnValText').css('color', 'green');
                $('#phnValText').html('<span style="color: red;font-size: 15px;">*</span>Invalid phone number');
                $('#phnValText').show();
                if (!data1.success)
                {
                    $('#phnValText').css('color', 'red');
                    alert("Invalid Phone number");
                    return false;
                }
                registerCMB();
            }
        });

    }
    function validateBookingid() {

        var refid = $('#ServiceCallQueue_scq_related_bkg_id').val();
        var reftype = $('#ServiceCallQueue_scq_follow_up_queue_type').val();
        $href = "<?php echo Yii::app()->createUrl('lookup/validateBooking') ?>";
        jQuery.ajax({type: 'GET',
            "url": $href,
            data: {'refid': refid, 'reftype': reftype},
            "dataType": "json",
            success: function (data1)
            {
                $('#bkgValTxt').css('color', 'green');
                $('#bkgValTxt').html('<i class="fa fa-check"></i>');
                $('#bkgValTxt').show();
                if (!data1.success)
                {
                    data1.flag == 1 ? $('#bkgValTxt').html('<span style="color: red;font-size: 15px;">*</span> Booking id required') : $('#bkgValTxt').html('<i class="fa fa-times"></i> Wrong booking id');
                    $('#bkgValTxt').css('color', 'red');
                    return false;
                }
                validatePhoneno();
            }
        });

    }
</script>
