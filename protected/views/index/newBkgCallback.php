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
				'id'					 => 'nwbkgCallback-form', 'enableClientValidation' => TRUE,
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
//				'action'				 => Yii::app()->createUrl('index/storeCallBackData'),
				'htmlOptions'			 => array(
					'class' => 'form-horizontal',
				),
			));
			/* @var $form TbActiveForm */
			/** @var Users $umodel */
			echo $form->hiddenField($model, 'fwp_ref_type');
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
							<span  class="form-control input-horizontal bg bg-gray col-sm-6"><?php echo FollowUps::getReasonList($refType) ?></span>	
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
								<label class="col-sm-4" for="FollowUps_fwp_ref_id">Booking Id <?php ?><span class="<?php echo ($refType == 2) ? '' : 'hide' ?>" style="color: red;font-size: 15px;">*</span>: </label>
								<?php echo $form->textField($model, 'fwp_ref_id', ['class' => 'form-control input-horizontal col-sm-6', 'placeholder' => "Starting with OW/RT/MW"]) ?> 
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
								<?php echo $form->textField($model, 'fwp_contact_phone_no', ['class' => 'form-control', 'placeholder' => 'Add new number']) ?>
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
								<?php echo $form->textArea($model, 'fwp_desc', ['class' => "form-control", 'placeholder' => "Enter Description", "cols" => "50", "rows" => "3"]) ?>
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
			<?php $this->endWidget(); ?>
		</div>
		<div id="waitTime" class=""><span style="color: red;font-size: 15px;"><sup>*</sup></span>Expected call back time: <?php echo $waitTime ?> minutes</div>
	</div>
</div> 
<script type="text/javascript">

	//	function selContact(obj) {
	//
	//		var x = obj.options[obj.selectedIndex].text;
	//		if (obj.selectedIndex != 0) {
	//			$('#FollowUps_fwp_contact_phone_no').val(x).change();
	//		}
	//	}
	$('#addphoneBtn').click(function () {
		$('#contactNo').show();
	})
	$('#registerCMB').click(function () {

		validateBookingid();
	});
	function registerCMB() {

		var obj1 = document.getElementById("FollowUps_fwp_contact_phone_no");
//				if (obj1.selectedIndex == 0) {
//					alert('Select a phone number to contact');
//					obj1.focus();
//					return false;
//				}
		if ($("#FollowUps_fwp_desc").val() == '') {
			var obj2 = document.getElementById("FollowUps_fwp_desc");
			obj2.focus();
			return false;
		}

		if ($("#FollowUps_fwp_contact_phone_no").val() == '') {

			obj1.focus();
			return false;
		}


		$href = "<?php echo Yii::app()->createUrl('index/storeCallBackData') ?>";
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
	function   validatePhoneno() {

		var phone = $('#FollowUps_fwp_contact_phone_no').val();

		$href = "<?php echo Yii::app()->createUrl('index/validatePhone') ?>";
		jQuery.ajax({type: 'GET',
			"url": $href,
			data: {'phone': phone},
			"dataType": "json",
			success: function (data1)
			{
				$('#phnValText').css('color', 'green');
				$('#phnValText').html(data1.text);
				$('#phnValText').show();
				if (!data1.success) {
					$('#phnValText').css('color', 'red');
					$("#FollowUps_fwp_contact_phone_no").focus();
					return false;
				}
				registerCMB();
			}
		});

	}
	function validateBookingid() {

		var refid = $('#FollowUps_fwp_ref_id').val();
		var reftype = $('#FollowUps_fwp_ref_type').val();
		$href = "<?php echo Yii::app()->createUrl('index/validateBookingidForCMB') ?>";
		jQuery.ajax({type: 'GET',
			"url": $href,
			data: {'refid': refid, 'reftype': reftype},
			"dataType": "json",
			success: function (data1)
			{

				$('#bkgValTxt').css('color', 'green');
				$('#bkgValTxt').html(data1.text);
				$('#bkgValTxt').show();
				if (!data1.success) {
					$('#bkgValTxt').css('color', 'red');
					return false;
				}
				validatePhoneno();
			}
		});

	}
</script>
