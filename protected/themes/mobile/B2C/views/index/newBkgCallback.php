<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/fontawesome-web/css/all.min.css?v0.6');
?>
  
<?
	$form = $this->beginWidget('CActiveForm', array(
		'id'  => 'nwbkgCallback-form', 'enableClientValidation' => TRUE,
		'clientOptions' => array(
			'validateOnSubmit' => true,
			'errorCssClass' => 'has-error'
		),
		// Please note: When you enable ajax validation, make sure the corresponding
		// controller action is handling ajax validation correctly.
		// See class documentation of CActiveForm for details on this,
		// you need to use the performAjaxValidation()-method described there.
		'enableAjaxValidation' => false,
		'errorMessageCssClass' => 'help-block',
		'htmlOptions' => array(
			'class' => 'form-horizontal',
		),
	));
	echo $form->hiddenField($model, 'fwp_ref_type');
	?>
	

	<div class="content-boxed-widget">
		<div class="content p0 bottom-0">
			<div class="one-half">
				<div class="input-simple-1 has-icon input-green bottom-20"><span class="gray-color">First Name</span>					
					<br/><?php echo $umodel->usr_name ?>

				</div>
			</div>
			<div class="one-half last-column">
				<div class="input-simple-1 has-icon input-green bottom-20"><span class="gray-color">Last Name</span>
					<br/><?php echo $umodel->usr_lname ?>
				</div>
			</div>
			<div class="clear"></div>
		</div>
	</div>

	<div class="content-boxed-widget">
	    <div class="input-simple-1 has-icon input-green bottom-20">
			<label class="col-sm-4 control-label gray-color">Email</label><i class="fa fa-envelope"></i>
			<?php echo $umodel->usr_email ?>
		</div>
		<div class="input-simple-1 has-icon input-green bottom-20">
			<label class="col-sm-4 control-label gray-color">Reason</label>
			<?php echo FollowUps::getReasonList($refType) ?>
		</div>
		<div class="content p0 bottom-0">
            <?php if (in_array($refType, [2, 4])){?>
					<div class="input-simple-1 has-icon input-green bottom-20" for="FollowUps_fwp_ref_id"><em class="gray-color">Booking Id<span class="<?php echo ($refType==2)?'':'hide' ?>" style="color: red;font-size: 15px;">*</span>:</em>
						<?php echo $form->textField($model, 'fwp_ref_id', ['placeholder' => "Starting with OW/RT/MW"]) ?> 
						<span class="col-sm-4 pr0" id="bkgValTxt" style="display: none"></span>
					</div>
			<?php } ?>


			<div class="input-simple-1 has-icon input-green bottom-20">
				<label class="col-sm-4 control-label gray-color">Phone</label>
				<?php echo $primaryPhone ?>
			</div>

            <div class="input-simple-1 has-icon input-green bottom-20">					 
				<span class="pl0" id="contactNo" style="display: none">
					<?php echo $form->textField($model, 'fwp_contact_phone_no', ['placeholder' => 'Add new number']) ?>
                    <span class="col-sm-4 pr0" id="phnValText" style="display: none"></span>
				</span>
				<div class="pt10">
					<button class="uppercase btn-orange shadow-medium" type ="button" id="addphoneBtn">Add new phone</button>
				</div>
			</div>

            <div class="input-simple-1 has-icon input-green bottom-20">
				<label>Notes for customer agent <span style="color: red;font-size: 15px;">*</span>:</label>
			</div>
			
            <div class="input-simple-1 textarea has-icon  bottom-20">
			    <?= $form->textField($model, 'fwp_desc',['class' => "form-control textarea-simple-2",'placeholder' => "Enter Description"]) ?>
			</div>

			<div class="content p0 bottom-0 mb20 text-center">
		    <div class="Submit-button"> 
					<button type="button" class="uppercase btn-orange shadow-medium" name="downloadBtn" id="registerCMB">CALL ME BACK</button>
				</div> 
			</div>
		</div>
		<div class="clear"></div>
	</div>
   <?php $this->endWidget(); ?>
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
		if ($("#FollowUps_fwp_contact_phone_no").val() == '') {
		    obj1.focus();
			return false;
		}
		
		if ($("#FollowUps_fwp_desc").val() == '') {
			var obj2 = document.getElementById("FollowUps_fwp_desc");
			obj2.focus();
			return false;
		}
		
		$href = "<?= Yii::app()->createUrl('index/storeCallBackData?ismobile=1') ?>";
		jQuery.ajax({type: 'POST',
			url: $href,
			data: $('#nwbkgCallback-form').serialize(),
			"dataType": "html",
			success: function (data)
			{
				$('#callmebackbody').html(data);
				$('#menu-callmeback').data('height','250');
				$('#menu-callmeback h1').remove();
				$('a[data-menu="menu-callmeback"]').click();
            }
		});
	}
	
	function  validatePhoneno() {
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
		//alert('refid'+refid);alert('reftype'+reftype);
		$href = "<?= Yii::app()->createUrl('index/validateBookingidForCMB') ?>";
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
