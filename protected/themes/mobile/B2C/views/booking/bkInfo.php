<?php
if (!$model->bkg_cav_id)
{
	$model->getRoutes();
	$quotes		 = $model->getQuote();
	/* @var $model BookingTemp */
	$quoteModel	 = $model->quotes;
}
$bookingType = Booking::model()->getBookingType($model->bkg_booking_type);

/* @var $model BookingTemp */
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');
$ccode					 = Countries::model()->getCodeList();
$additionalAddressInfo	 = "Building No./ Society Name";
$addressLabel			 = ($model->bkg_booking_type == 4) ? 'Location' : 'Address';
$infosource				 = BookingAddInfo::model()->getInfosource('user');
//$ulmodel				 = new Users('login');
//$urmodel				 = new Users('insert');
$locFrom				 = [];
$locTo					 = [];
$hyperLocationClass		 = 'txtHyperLocation';
$autocompleteFrom		 = $autocompleteTo			 = $hyperLocationClass;
$locReadonly			 = ['readonly' => 'readonly'];
$scvVctId				 = SvcClassVhcCat::model()->getCatIdBySvcid($model->bkg_vehicle_type_id);

if ($model->bkgFromCity->cty_is_poi == 1)
{
	$locFrom			 = $locReadonly;
	$autocompleteFrom	 = '';
}
if ($model->bkgToCity->cty_is_poi == 1)
{
	$locTo			 = $locReadonly;
	$autocompleteTo	 = '';
}
$userdiv = 'block';
if (!Yii::app()->user->isGuest)
{
	$user					 = Yii::app()->user->loadUser();
	$model->bkg_user_id		 = Yii::app()->user->getId();
	if ($user->usr_contact_id)
	{
		$contactModel			 = Contact::model()->findByPk($user->usr_contact_id);
		$model->bkg_user_name	 = $contactModel->ctt_first_name;
		$model->bkg_user_lname	 = $contactModel->ctt_last_name;
	}
	if ($model->bkg_cav_id > 0)
	{
		$model->bkg_user_email	 = $user->email;
		$model->bkg_contact_no	 = $user->usr_mobile;
	}
	$userdiv = 'none';
	if ($model->bkg_booking_type == 1 && $scvVctId == VehicleCategory::SHARED_SEDAN_ECONOMIC)
	{
		if (Users::model()->getFbLogin($model->bkg_user_id, $model->bkg_user_email, $model->bkg_contact_no))
		{
			$userdiv = 'none';
		}
		else
		{
			$userdiv = 'block';
		}
	}
}
else
{
	if (Users::model()->getFbLogin($model->bkg_user_id, $model->bkg_user_email, $model->bkg_contact_no))
	{
		$userdiv = 'none';
	}
	else
	{
		$userdiv = 'block';
	}
	$ulmodel->usr_email			 = $model->bkg_user_email;
	$urmodel->usr_email			 = $model->bkg_user_email;
	$urmodel->usr_mobile		 = $model->bkg_contact_no;
	$urmodel->usr_country_code	 = $model->bkg_country_code;
	$urmodel->usr_name			 = $model->bkg_user_name;
	$urmodel->usr_lname			 = $model->bkg_user_lname;
}
/*
  @var $model Booking
 * */
?>
<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/fontawesome-web/css/all.min.css?v0.6');
?>
<?php
if (!$model->bkg_cav_id)
{
	?>
	<?php
	$this->renderPartial("bkRouteHeader" . $this->layoutSufix, ['prevStep' => 3, 'model' => $model, 'quoteModel' => $quoteModel]);
}
?>
<!--<div class="content-boxed-widget gradient-green-blue mb10 p5 text-center">
	<div class="">
		<div class="one-half text-left p5">
			Cab Type:<br>
			<?php
			$cabModelDetails = "";
            $cabDetails = $model->bkgSvcClassVhcCat;
			if($model->bkgSvcClassVhcCat->scv_model>0 && ($model->bkgSvcClassVhcCat->scv_scc_id == 4 || $model->bkgSvcClassVhcCat->scv_scc_id == 5))
			{
				$cabModelDetails = $model->bkgSvcClassVhcCat->scv_label."-".$cabDetails->scc_VehicleCategory->vct_label;
			}
			else
			{
				$cabModelDetails = $model->bkgSvcClassVhcCat->scv_label;
			}
			if ($model->bkg_vht_id > 0 && !$cabModelDetails)
			{
				$cabModel		 = VehicleTypes::getModelDetailsbyId($model->bkg_vht_id);
				$cabModelDetails = $cabModel['vht_make'] . " " . $cabModel['vht_model'];
			}
			
			?>
			<span class="font-16 mt5 uppercase" id="info_car_type"><?php// echo $cabDetails->scc_VehicleCategory->vct_label . ' (' . $cabDetails->scc_ServiceClass->scc_label . ')'.$cabModelDetails; ?></span>
		</div>
		<div class="one-half last-column text-right p5">
			Trip Type<br>
			<span class="font-18 uppercase"><?php// echo $bookingType; ?></span>
		</div>
		<div class="clear"></div>
	</div>
</div>-->
<?php
if (Yii::app()->user->isGuest)
{
	$this->renderPartial("bkInfoLogin" . $this->layoutSufix, ['model' => $model, 'id' => '1']);
}
?>
<?php
$form = $this->beginWidget('CActiveForm', array(
	'id'					 => 'customerinfo',
	'enableClientValidation' => true,
	'clientOptions'			 => array(
		'validateOnSubmit'	 => true,
		'errorCssClass'		 => 'has-error'
	),
	'enableAjaxValidation'	 => false,
	'errorMessageCssClass'	 => 'help-block',
	'htmlOptions'			 => array(
		'class' => 'form-horizontal',
	//	'autocomplete' => 'disabled',
	),
		));
/* @var $form CActiveForm */
?>
 <?php
	if(!empty($note))
	{
?>
	<div class="content p0 accordion-path bottom-0 specialinsadv">
		<div class="accordion accordion-style-0 content-boxed-widget p0">
			<div class="accordion-border">
				<a href="javascript:void(0)" class="font18 uppercase" data-accordion="accordion-9">Special instructions & advisories<i class="fa fa-plus"></i></a>
				<div class="accordion-content" id="accordion-9" style="display: none;">
		<div class="accordion-text mt15">
		<span class="bottom-10 font-16"><b>Special instructions & advisories that may affect your planned travel</b></span>
		<div aria-describedby="caption" class="table" role="grid">
		<?php
		for ($i = 0; $i < count($note); $i++)
		{
			?>  
		   <div class="tr" role="row">
			 <div class="th smallCol" role="columnheader">
				Place
			 </div>
			 <div class="td bigCol" role="gridcell">
				 <strong>
							<?php
							if ($note[$i]['dnt_area_type'] == 3)
							{
								?>
							  <?= ($note[$i]['cty_name']) ?>
								<?php
							}
							else if ($note[$i]['dnt_area_type'] == 2)
							{
								?>
							  <?= ($note[$i]['dnt_state_name']) ?>
								<?php
							}
							else if ($note[$i]['dnt_area_type'] == 0)
							{
								?>
							 <?="Applicable to all"?>
								<?php
							}
							else if ($note[$i]['dnt_area_type'] == 4)
							{
								?>
										   <?= Promos::$region[$note[$i]["dnt_area_id"]]?>
							<?php
							 }
							?>
				 </strong>
			 </div>
			</div>
			<div class="tr" role="row">
              <div class="th smallCol" role="columnheader">
				Note
			  </div>
			  <div class="td bigCol" role="gridcell">
					<?= ($note[$i]['dnt_note']) ?>
			  </div>
				
			</div>
			<div class="tr" role="row">
				<div class="th smallCol" role="columnheader">
					Valid From
				</div>	
				<div class="td bigCol" role="gridcell">
					 <?= (DateTimeFormat::DateTimeToLocale($note[$i]['dnt_valid_from'])) ?>
				</div>
			</div>
			<div class="tr" role="row">
					<div class="th smallCol" role="columnheader">
						Valid To
					</div>
				<div class="td bigCol" role="gridcell">
					 <?= (DateTimeFormat::DateTimeToLocale($note[$i]['dnt_valid_to'])) ?>
				</div>
			</div>
		<?php
		}
		?>
	</div>
		</div>			
		</div>
			</div>
		</div>
	</div>
<?php }?>

<div class="page-content">
<div class="widget-content-1">
	<p class="color-black bolder font-18 bottom-5">Traveller's info</p>
<!--<p class="bolder mb0">Who is travelling?</p>
	<div class="fac fac-radio-round fac-green"><span></span>
		<input id="box3-fac-radio-full" type="radio" name="travel_status" value="1" checked>
		<label for="box3-fac-radio-full">Myself</label>
	</div>
	<div class="fac fac-radio-round fac-green"><span></span>
		<input id="box3-fac-radio-fullss" type="radio" name="travel_status" value="0">
		<label for="box3-fac-radio-fullss">Others</label>
	</div>-->
    <div class="content-boxed-widget2 p15 m0 mt10">
	<input autocomplete="off" name="hidden" type="text" style="display:none;">
	<input type="hidden" id="step4" name="step" value="4">
	<input type="hidden" id="islogin" name="islogin" value="<?php echo $islogin;?>">
	<?= $form->hiddenField($model, 'bkg_user_id', ['class' => 'clsUserId', 'id' => 'bkInfoUserId']); ?> 
	<?= $form->hiddenField($model, 'bkg_id', ['id' => 'bkg_id4', 'class' => 'clsBkgID']); ?>
	<?= $form->hiddenField($model, 'hash', ['id' => 'hash4', 'class' => 'clsHash']); ?>
	<?php
	if ($model->bkg_cav_id > 0)
	{
		echo $form->hiddenField($model, 'bkg_cav_id');
		echo $form->hiddenField($model, 'cavhash', ['value' => Yii::app()->shortHash->hash($model->bkg_cav_id)]);
	}
	?>
	<?php
	if ($model->bkg_booking_type == 7)
	{
		echo $form->hiddenField($model, 'bkg_shuttle_id');
	}
	?>
	<label class="color-gray">Passenger Name*:</label>
	<div class="one-half p0 pt5">
		<div class="input-simple-1 has-icon input-green bottom-15">
<?= $form->textField($model, 'bkg_user_name', array('label' => '', 'placeholder' => "First Name", 'class' => 'form-control nameFilterMask')) ?>
<?php echo $form->error($model, 'bkg_user_name', ['class' => 'help-block error']); ?>
		</div>
	</div>
	<div class="one-half last-column p0 pt5">
		<div class="input-simple-1 has-icon input-green bottom-15">
<?= $form->textField($model, 'bkg_user_lname', array('label' => '', 'placeholder' => "Last Name", 'class' => 'form-control nameFilterMask')) ?>
<?php echo $form->error($model, 'bkg_user_lname', ['class' => 'help-block error']); ?>
		</div>
	</div>
	<div class="clear"></div>


	<div class="input-simple-1 has-icon input-green bottom-15"><em class="color-gray">Email*:</em>
<?= $form->emailField($model, 'bkg_user_email', ['placeholder' => "Email Address", 'id' => CHtml::activeId($model, "bkg_user_email2")]) ?> 
<?php echo $form->error($model, 'bkg_user_email', ['class' => 'help-block error']); ?>
	</div>

	<div class="input-simple-1 has-icon input-blue bottom-15"><em class="color-gray">Phone Number (incl. country code)</em>
		<div class="input-simple-1 has-icon input-blue bottom-15 mt10">
			<?php
			$this->widget('ext.intlphoneinput.IntlPhoneInput', array(
				'model'					 => $model,
				'attribute'				 => 'bkg_contact_no',
				'codeAttribute'			 => 'bkg_country_code',
				'numberAttribute'		 => 'bkg_contact_no',
				'options'				 => array(// optional
					'separateDialCode'	 => true,
					'autoHideDialCode'	 => true,
					'initialCountry'	 => 'in'
				),
				'htmlOptions'			 => ['class' => 'form-control', 'id' => 'fullContactNumber1'],
				'localisedCountryNames'	 => false, // other public properties
			));
			?> 
<?php echo $form->error($model, 'bkg_country_code'); ?>
<?php echo $form->error($model, 'bkg_contact_no1'); ?>
		</div>
	</div>

	<div class="checkboxes-demo">
		<label class="bottom-5">Send me booking confirmations by:</label>
		<div class="mt5">               
                    <label class="checkbox-inline pt0 pr30 display-inline"><?= $form->checkbox($model, 'bkg_send_email') ?> Email</label><label class="checkbox-inline pt0 display-inline"><?= $form->checkBox($model, 'bkg_send_sms') ?> Phone</label>              
		</div>
		<div class="clear"></div>
	</div>

	<div class="content mt20 text-center">

	</div>
</div>
</div>
</div>
<div class="content-padding p5 pt10 pb10 fixed-widget-content">
    <div class="one-half mr10 pl15 pt10"><span class="font-16 color-gray"><?php echo $cabDetails->scc_VehicleCategory->vct_label; ?></span> <b><span class="font-16">(<?php echo  $cabDetails->scc_ServiceClass->scc_label;?>)</span></b><br><span class="font-18"><?php echo $cabModelDetails;?></span></div>
    <div class="one-half last-column text-right"><?php echo CHtml::button('Save & proceed', array('class' => 'btn-2 mr5 font-14', 'id' => 'nxtBtnAddDtls')); ?></div>
            </div>
<a href="#" data-menu="map-marker" class="hide" id="info-map-marker"></a>
<?php $this->endWidget(); ?>
<script type="text/javascript">
	var bookNow = new BookNow();
	var hyperModel = new HyperLocation();
	var model = {};
	var data = {};
	data.infoSource = "<?= CHtml::activeId($model, "bkg_info_source") ?>";
	data.bookingType = parseInt(<?= $model->bkg_booking_type ?>);
	//data.carType = "<? //= VehicleTypes::model()->getCarByCarType($model->bkgVehicleType->vht_car_type);    ?>";
	data.carType = "<?= SvcClassVhcCat::model()->getVctSvcList('string', '', $model->bkgSvcClassVhcCat->scv_vct_id); ?>";
	data.chkOthers = "<?= CHtml::activeId($model, "bkg_chk_others") ?>";
	data.sendSms = "<?= CHtml::activeId($model, "bkg_send_sms") ?>";
	data.sendEmail = "<?= CHtml::activeId($model, "bkg_send_email") ?>";
	data.contactNo = "<?= CHtml::activeId($model, "bkg_contact_no") ?>";
	data.infoSourceDesc = "<?= CHtml::activeId($model, "bkg_info_source_desc") ?>";
	data.flightChk = "<?= CHtml::activeId($model, "bkg_flight_chk") ?>";
	data.key = "<?= $key; ?>";
	data.fromCity = "<?= $brtRoute->brtFromCity->cty_id ?>",
			data.queryStr = "<?= $brtRoute->brtFromCity->cty_name ?>";
	data.vehicleTypeId = "<?= $model->bkg_vehicle_type_id ?>";
	data.pickupLaterChk = "<?= CHtml::activeId($model, "pickup_later_chk") ?>";
	data.dropLaterChk = "<?= CHtml::activeId($model, "drop_later_chk") ?>";
	data.countryCode = "<?= CHtml::activeId($model, "bkg_country_code") ?>";
	data.userEmail = "<?= CHtml::activeId($model, "bkg_user_email") ?>";
	data.hyperlocationClass = '<?= $hyperLocationClass ?>';
	bookNow.data = data;
	$(document).ready(function ()
	{
		//setHyperLocationData();
		bookNow.bkInfoReady();
		$("#menuInfo").show();
		$( "#BookingTemp_pickup_later_chk" ).prop( "checked", true );
		$("#BookingTemp_pickup_later_chk").attr('disabled', 'disabled');
		$( "#BookingTemp_drop_later_chk" ).prop( "checked", true );
		$("#BookingTemp_drop_later_chk").attr('disabled','disabled');
		$('input[name ="skipAdd"]').prop( "checked", true );
		$('input[name ="skipAdd"]').attr('disabled','disabled');
		$('.txtHyperLocation').attr('readOnly','true');


<?php
if ($model->bkg_is_gozonow == 1 && $model->bkg_user_name!='' && $model->bkg_user_email != '')
{
	?>
//	        document.getElementById('nxtBtnAddDtls').style.display = "none";
//	        bookNow.bkInfoNext();
//	        document.getElementById('nxtBtnAddDtls').click();
<? } ?>
	});


<?php
if ($islogin == 1)
{
	?>
	bookNow.checkIfLoggedIn();
	<?php }?>
	bookNow.bkInfoNext();

	function setHyperLocationData()
	{
		model.booking_type = '<?= $model->bkg_booking_type ?>';
		model.transfer_type = '<?= $model->bkg_transfer_type ?>';
		model.ctyLat = <?= json_encode($ctyLat) ?>;
		model.ctyLon = <?= json_encode($ctyLon) ?>;
		model.bound = <?= json_encode($bound) ?>;
		model.isCtyAirport = <?= json_encode($isCtyAirport) ?>;
		model.isCtyPoi = <?= json_encode($isCtyPoi) ?>;
		model.hyperLocationClass = '<?= $hyperLocationClass ?>';
		hyperModel.model = model;
		hyperModel.initializepl();
	}

	$('.txtHyperLocation').click(function (event)
	{

//		var locKey = $(event.currentTarget).data('lockey');
//		var ctyLat = <?= json_encode($ctyLat) ?>;
//		var ctyLon = <?= json_encode($ctyLon) ?>;
//		var bound = <?= json_encode($bound) ?>;
//		var isAirport = <?= json_encode($isCtyAirport) ?>;
//		var isCtyPoi = <?= json_encode($isCtyPoi) ?>;
//		if ($('.locLat_' + locKey).val() != '' && $('.locLon_' + locKey).val() != '')
//		{
//			ctyLat[locKey] = $('.locLat_' + locKey).val();
//			ctyLon[locKey] = $('.locLon_' + locKey).val();
//		}
//
//		$.ajax({
//			"type": "POST",
//			"url": '<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/autoMarkerAddress')) ?>',
//			"data": {"ctyLat": ctyLat[locKey], "ctyLon": ctyLon[locKey], "bound": bound[locKey], "isCtyAirport": isAirport[locKey], "isCtyPoi": isCtyPoi[locKey], "locKey": locKey, "airport": 0, "YII_CSRF_TOKEN": $("input[name='YII_CSRF_TOKEN']").val()},
//			"dataType": "HTML",
//			"success": function (data1)
//			{
//				$('#map-marker-content').html(data1);
//				$('#info-map-marker').click();
//			}
//
//		});
	});

	//For readonly textboxes
	$("#BookingTemp_pickup_later_chk,#BookingTemp_drop_later_chk,input[name='skipAdd']").change(function () {
		var key = $(this).data('key');
		if ($(this).prop("checked") == true) {
			$(".brt_location_" + key).attr('readonly', true);
			$(".brt_location_" + key).addClass("input-disabled");
			$(".brt_location_" + key).val("");
			$('.locLat_' + key).val('');
			$('.locLon_' + key).val('');
			$('.locPlaceid_' + key).val('');
			$('.locFAdd_' + key).val('');
		} else {
			$(".brt_location_" + key).attr('readonly', false);
			$(".brt_location_" + key).removeClass("input-disabled");
		}
	});

//		var travelstatus = $("input:radio[name=travel_status]");
//		travelstatus.on( "change", function() {
//            if($(this).val()== 0){
//
//				 $('#fullContactNumber1').attr('readonly', false);
//				 //$("#travellerInfo").find(".selectize-control").removeClass("disabled");
//				 $('#BookingTemp_bkg_user_email2').attr('readonly', false);
//				 $('#BookingTemp_bkg_user_name').attr('readonly', false);
//				 $('#BookingTemp_bkg_user_lname').attr('readonly', false);
//			}
//			else
//			{
//			   if($("#fullContactNumber1").val()!="")
//			   {
//				   $('#fullContactNumber1').attr('readonly', true);
//			   }
//			   if($("#BookingTemp_bkg_user_email2").val()!="")
//			   {
//				   $('#BookingTemp_bkg_user_email2').attr('readonly', true);
//			   }
//				if($("#BookingTemp_bkg_user_name").val()!="")
//			   {
//				   $('#BookingTemp_bkg_user_name').attr('readonly', true);
//			   }
//			   if($("#BookingTemp_bkg_user_lname").val()!="")
//			   {
//				   $('#BookingTemp_bkg_user_lname').attr('readonly', true);
//			   }
//			}
//		});
</script>
<style>
	.input-disabled {
		background-color: #eee !important;
		border: 1px solid rgb(204, 204, 204)!important;
	}
	.table {
  border-left: 1px solid #ccc;
  border-bottom: 1px solid #ccc;
}

.tr {
  display: flex;
}
.th, .td {
  border-top: 1px solid #ccc;
  border-right: 1px solid #ccc;
  padding: 4px 8px;
  flex: 1;
  font-size:14px;
  overflow-wrap: anywhere !important;
  word-wrap: break-word;
  max-width:300px;
  overflow: auto;
}
.bigCol
{
  max-width:70%;
}
.smallCol
{
  max-width:30%;		
}
.th {
  font-weight: bold;
}
.th[role="rowheader"] {
  background-color: #fff;
}
.th[role="columnheader"] {
  background-color: #fff;
}
</style>