<style>
	.form-horizontal .form-group{ margin-left: 0; margin-right: 0;}
	input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; 
        margin: 0; 
    }
    input[type="number"] {
        -moz-appearance: textfield;
    }
	#messageBox{display:none; color: red; font: 18px;}
	.error {
		color: red;
		margin-left: 5px;
	}
</style> 
<div class="container mt30">

    <div class="  spot-panel">

		<?
		$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'create-trip', 'enableClientValidation' => FALSE,
			'clientOptions'			 => array(
				'validateOnSubmit'	 => true,
				'errorCssClass'		 => 'has-error'
			),
			'enableAjaxValidation'	 => false,
			'errorMessageCssClass'	 => 'help-block',
			'action'				 => Yii::app()->createUrl('agent/booking/spot'),
			'htmlOptions'			 => array(
				'class'		 => 'form-horizontal', 'enctype'	 => 'multipart/form-data'
			),
		));
		/* @var $form TbActiveForm */
		$ccode		 = Countries::model()->getCodeList();
		echo $form->hiddenField($model, 'bkg_booking_type');
		$no_of_seat	 = $model->preData['preBookData']['bkg_no_of_seats'];

		echo $form->hiddenField($model, 'bkg_shuttle_id');
		echo $form->hiddenField($model, 'step', ['value' => '17']);

		// echo $form->errorSummary($model);
		echo $form->hiddenField($model, 'preData', ['value' => json_encode($model->preData)]);
		?>   
		<input type = "hidden" name="Booking[bkg_no_of_seats]"    id="Booking_bkg_no_of_seats" value="<?= $model->preData['preBookData']['bkg_no_of_seats'] ?>">
		<?= $form->errorSummary($model); ?>

		<?
		if (count($errorsArr) > 0)
		{
			?>
			<div class="alert alert-block alert-danger"><p>Please fix the following input errors:</p>
				<ul>
					<?
					foreach ($errorsArr as $userNo => $err)
					{
						?>
						<li><ul style="padding-left: 0; list-style-type: none;">
								<?
								echo "<b>Traveller #" . ($userNo + 1) . "</b>: <br>";
								foreach ($err as $k => $errval)
								{
									?>
									<li style="padding-left: 5px;">
										<?= $errval[0]; ?>
									</li>
									<?
								}
								?>
							</ul></li>
					<? } ?>
				</ul></div>
		<? }
		?>

		<input type="hidden" name="step" value="17">
		<div class="row ">
			<div class="col-xs-12 col-sm-4 h3 ">
				Traveller Info 
			</div>
		</div>
		<div class="row ">
			<div class="col-xs-12 col-sm-10 col-sm-offset-2">
				<div class="row mb10">
					<label class="col-xs-12 col-sm-2 mt5 control-label">Traveller #1 Name</label>
					<div class="col-xs-12 col-sm-4 mr5">
						<div class="form-group">
							<input class="form-control " placeholder="Enter First Name"  name="BookingUser[bkg_user_fname][]" id="BookingUser_bkg_user_fname_1" type="text" value="<?= $model->preData['userInfo']['bkg_user_fname'][0] ?>"  >
							<span id="messageBox"></span>
						</div>			
					</div>
					<div class="col-xs-12 col-sm-4">
						<div class="form-group">
							<input class="form-control form-control" placeholder="Enter Last Name" 
								   name="BookingUser[bkg_user_lname][]" id="BookingUser_bkg_user_lname_1" type="text" value="<?= $model->preData['userInfo']['bkg_user_lname'][0] ?>">
							<span id="messageBox"></span>
						</div>		
					</div>
				</div>
				<div class="row mb10">
					<label class="col-xs-12 col-sm-2 mt5 control-label">Mobile Phone</label>
					<div class="col-sm-2 col-xs-4 isd-input ">
						<div class="form-group">
							<select class="form-control" placeholder="Country Code" name="BookingUser[bkg_country_code][]" id="BookingUser_bkg_country_code_1">
								<?
								foreach ($ccode as $k => $v)
								{
									$selected = '';
									if ($k == '91')
									{
										$selected = 'selected="selected"';
									}
									echo '<option value="' . $k . '" ' . $selected . '>' . $v . '</option>';
								}
								?>
							</select>
							<?php
							//echo $form->dropDownListGroup($bookingUser, 'bkg_country_code', array('label' => '', 'widgetOptions' => array('data' => $ccode)))
							?>
						</div>		
					</div>

					<div class="col-xs-8 col-sm-3">
						<div class="form-group">
							<input class="form-control" placeholder="Enter Mobile Number" 
								   name="BookingUser[bkg_contact_no][]" id="BookingUser_bkg_contact_no_1" type="number" value="<?= $model->preData['userInfo']['bkg_contact_no'][0] ?>">
						</div>	
					</div>
					<div class="col-xs-8 col-sm-3 col-xs-offset-4 col-sm-offset-0 " style="font-size: 12px; ">Gozo will send verification link to customer phone</div>
				</div>
				<div class="row">
					<label class="col-xs-12 col-sm-2 mt5 control-label" >Email</label>
					<div class="col-sm-4">
						<div class="form-group">
							<input class="form-control" placeholder="Enter Email Address" 
								   name="BookingUser[bkg_user_email][]" id="BookingUser_bkg_user_email_1" type="email" value="<?= $model->preData['userInfo']['bkg_user_email'][0] ?>">
						</div>	

					</div>
					<div class="col-xs-8 col-sm-3 " style="font-size: 12px; ">Gozo will send verification link to customer email</div>
				</div>
			</div>
		</div>
		<?
//echo $no_of_seat;
		$j = 1;
		for ($i = 2; $i <= $no_of_seat; $i++)
		{
			?>
			<div class="row mt20  ">
				<div class="col-xs-12 col-sm-9 col-sm-offset-2 ">	
					<hr class="mt0"  style="height: 1px; background-color: #ccc;border: none;"> 
				</div>
				<div class="col-xs-12 col-sm-10 col-sm-offset-2 ">	

					<div class="row">
						<label class="col-xs-12 col-sm-2 mt5 control-label">Traveller #<?= $i ?> Name</label>
						<div class="col-xs-12 col-sm-4 mr5">
							<div class="form-group">
								<input class="form-control" placeholder="Enter First Name" 
									   name="BookingUser[bkg_user_fname][]" 
									   id="BookingUser_bkg_user_fname_<?= $i ?>" type="text" value="<?= $model->preData['userInfo']['bkg_user_fname'][$j] ?>">
							</div>			
						</div>
						<div class="col-xs-12 col-sm-4">
							<div class="form-group">
								<input class="form-control" placeholder="Enter Last Name" 
									   name="BookingUser[bkg_user_lname][]" id="BookingUser_bkg_user_lname_<?= $i ?>" type="text" value="<?= $model->preData['userInfo']['bkg_user_lname'][$j] ?>" >
							</div>		
						</div>

					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-8 col-sm-offset-2 pb10 ">
							<span id="contact_rad_primary_<?= $i ?>">
								<label class="checkbox-inline pt0 ml0">
									<input id="contact_hide_<?= $i ?>" value="1" type="radio" class="contact_hide" name="contact_rad[<?= $i ?>]" onclick="hideCont(this,<?= $i ?>)">Send all details to Traveller #1</label>
								<label class="checkbox-inline pt0 ml0">
									<input id="contact_show_<?= $i ?>" value="2" type="radio" class="contact_show" name="contact_rad[<?= $i ?>]" onclick="showCont(this,<?= $i ?>)">Provide separate email & phone</label>
							</span>
						</div>
					</div>

					<div id="contact_<?= $i ?>" class="trvl_contact" style="display: none">
						<div class="row">
							<label class="col-xs-12 col-sm-2 mt5 control-label">Mobile Phone</label>
							<div class="col-sm-2 col-xs-4 isd-input ">
								<div class="form-group">
									<select class="form-control" placeholder="Country Code" name="BookingUser[bkg_country_code][]" id="BookingUser_bkg_country_code_<?= $i ?>">
										<?
										foreach ($ccode as $k => $v)
										{
											$selected = '';
											if ($k == '91')
											{
												$selected = 'selected="selected"';
											}
											echo '<option value="' . $k . '" ' . $selected . '>' . $v . '</option>';
										}
										?>
									</select>
									<?php ?>
								</div>		
							</div>
							<div class="col-xs-8 col-sm-3">
								<div class="form-group">
									<input class="form-control" placeholder="Enter Mobile Number" 
										   name="BookingUser[bkg_contact_no][]" id="BookingUser_bkg_contact_no_<?= $i ?>" type="number" value="<?= $model->preData['userInfo']['bkg_contact_no'][$j] ?>">
								</div>	

							</div></div><div class="row" >
							<label class="col-xs-12 col-sm-2 mt5 control-label" >Email</label>
							<div class="col-sm-4">
								<div class="form-group">
									<input class="form-control" placeholder="Enter Email Address" 
										   name="BookingUser[bkg_user_email][]" id="BookingUser_bkg_user_email_<?= $i ?>" type="email" value="<?= $model->preData['userInfo']['bkg_user_email'][$j] ?>">
								</div>	

							</div>
						</div>	
					</div>

				</div>
			</div>

			<?
			$j++;
		}
		?>
		<div class="row">
			<div class="col-xs-12 text-right mt30">
				<button type="submit" class="pull-left  btn btn-danger btn-lg pl25 pr25 pt30 pb30" name="step17ToStep16"><b> <i class="fa fa-arrow-left"></i> Previous</b></button> <button type="submit" class="btn btn-primary btn-lg pl50 pr50 pt30  pb30" id="step17submit"  name="step17submit"><b>Next <i class="fa fa-arrow-right"></i></b></button>
			</div>
		</div> 
		<?php $this->endWidget(); ?>
	</div>
</div>

<script>

	history.pushState(null, null, location.href);
	window.onpopstate = function () {
		history.go(1);
	};
	$(document).ready(function ()
	{
		var countContactRadio = '<?= count($contactRadio) ?>';
		var countContactArr = <?= json_encode($contactRadio) ?>;
		if (countContactRadio > 0) {
			for (i = 1; i <= countContactRadio; i++) {
				var j = i + 1;
				if (countContactArr[j] == '2') {
					$('#contact_show_' + j).attr("checked", true);
					$('#contact_' + j).show();
				} else {
					$('#contact_hide_' + j).attr("checked", true);
					$('#contact_' + j).hide();
				}
			}
		} else {
			$('.contact_hide').attr("checked", true);
		}
	});
	$('form').on('focus', 'input[type=number]', function (e) {
		$(this).on('mousewheel.disableScroll', function (e) {
			e.preventDefault()
		});
		$(this).on("keydown", function (event) {
			if (event.keyCode === 38 || event.keyCode === 40) {
				event.preventDefault();
			}
		});
	});
	$('form').on('blur', 'input[type=number]', function (e) {
		$(this).off('mousewheel.disableScroll');
		$(this).off('keydown');
	});
	function hideCont($obj, $j) {
		$('#contact_' + $j).hide();
		$('#BookingUser_bkg_contact_no_' + $j).val('');
		$('#BookingUser_bkg_user_email_' + $j).val('');
	}
	function showCont($obj, $j) {
		$('#contact_' + $j).show();
	}
</script>