<style>
    .booking-info{
        color: red;
        padding-top: 15px;
        line-height: 1.3;
    }

	.sidebar{
		background: none;
	}
    .sidenav{
		background: #0f264d;
		margin-bottom: 5px;
		border-radius: 5px;
		height: auto;
	}
    .sidebar{
		padding-top: 10px;
		padding-bottom: 10px;
	}
    .dropdown-container{
		background: #f7f7f7;
		border: #e7e7e7 1px solid;
		padding: 15px;
	}
    .sidenav .active{
		background: #0f264d;
	}
    .sidenav a, .spclinsadv{
		padding: 12px 8px 12px 16px;
		font-size: 18px;
		color: #fff;
		font-weight: 500;
		border-bottom: none;
	}
</style>
<?php
//Yii::app()->clientScript->registerScriptFile('https://maps.googleapis.com/maps/api/js?key=AIzaSyDhybncyDc1ddM2qzn453XqYW8ZQDm7RW8&libraries=places&', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/js/jquery.mask1.min.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/jquery.disableAutoFill.min.js');

//Yii::app()->clientScript->registerScriptFile('https://maps.googleapis.com/maps/api/js?key=AIzaSyDhybncyDc1ddM2qzn453XqYW8ZQDm7RW8&libraries=places&', CClientScript::POS_HEAD);




/* @var $model BookingTemp */
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');
$ccode					 = Countries::model()->getCodeList();
$additionalAddressInfo	 = "Building No./ Society Name";
$addressLabel			 = ($model->bkg_booking_type == 4) ? 'Location' : 'Address';
$infosource				 = BookingAddInfo::model()->getInfosource('user');
$ulmodel				 = new Users('login');
$urmodel				 = new Users('insert');
$locFrom				 = [];
$locTo					 = [];
$hyperLocationClass		 = 'txtHyperLocation';
$autocompleteFrom		 = $autocompleteTo			 = $hyperLocationClass;
$locReadonly			 = ['readonly' => 'readonly'];
$locMarkerTo			 = $locMarkerFrom			 = '';
if ($model->bkgFromCity->cty_is_poi == 1)
{
	$locFrom			 = $locReadonly;
	$autocompleteFrom	 = '';
	$locMarkerFrom		 = "hide";
}
if ($model->bkgToCity->cty_is_poi == 1)
{
	$locTo			 = $locReadonly;
	$autocompleteTo	 = '';
	$locMarkerTo	 = "hide";
}

$userdiv = 'block';
$infocol = 'col-md-8';
if (!Yii::app()->user->isGuest)
{
	$user					 = Yii::app()->user->loadUser();
	$model->bkg_user_id		 = Yii::app()->user->getId();
	$model->bkg_user_name	 = $user->usr_name;
	$model->bkg_user_lname	 = $user->usr_lname;
	if ($model->bkg_cav_id > 0)
	{
		$model->bkg_user_email	 = $user->email;
		$model->bkg_contact_no	 = $user->usr_mobile;
	}
	$userdiv = 'none';
	$infocol = 'col-md-12';
	if ($model->bkg_booking_type == 1 && $model->bkg_vehicle_type_id == VehicleCategory::SHARED_SEDAN_ECONOMIC)
	{
		if (Users::model()->getFbLogin($model->bkg_user_id, $model->bkg_user_email, $model->bkg_contact_no))
		{
			$userdiv = 'none';
			$infocol = 'col-md-12';
		}
		else
		{
			$userdiv = 'block';
			$infocol = 'col-md-8';
		}
	}
}
else
{
	if (Users::model()->getFbLogin($model->bkg_user_id, $model->bkg_user_email, $model->bkg_contact_no))
	{
		$userdiv = 'none';
		$infocol = 'col-md-12';
	}
	else
	{
		$userdiv = 'block';
		$infocol = 'col-md-8';
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
 *  */
?>

<div class="container mt30 mb30">

    <div class="row bg-white-box-2 m0 mt5">
		<?php $this->renderPartial('bkLoginInfo', ['ulmodel' => $ulmodel, 'userdiv' => $userdiv, 'model' => $model], false, true); ?> 
        <div class="<?= $infocol ?> pt20 pb20 search-panel-3" id="infodiv">
			<?php
// $model=  Booking::model()->findByPk(25157);
//   $cabRate = Rate::model()->getCabDetailsbyCities($model->bkg_from_city_id, $model->bkg_to_city_id);
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


            <div class="flex">            
                <div class="panel-body padding_zero">   
                    <input autocomplete="off" name="hidden" type="text" style="display:none;">
                    <input type="hidden" id="step4" name="step" value="4">
					<input type="hidden" id="islogin" name="islogin" value="<?php echo $islogin; ?>">
					<?= $form->hiddenField($model, 'bkg_user_id'); ?> 
					<?= $form->hiddenField($model, 'bkg_id', ['id' => 'bkg_id4', 'class' => 'clsBkgID']); ?>
					<?= $form->hiddenField($model, 'hash', ['id' => 'hash4', 'class' => 'clsHash']); ?>
					<?php
					if ($model->bkg_booking_type == 7)
					{
						echo $form->hiddenField($model, 'bkg_shuttle_id');
					}
					if ($model->bkg_cav_id > 0)
					{
						echo $form->hiddenField($model, 'bkg_cav_id');
						echo $form->hiddenField($model, 'cavhash', ['value' => Yii::app()->shortHash->hash($model->bkg_cav_id)]);
					}
					?>

                    <div class="col-12">
                        <div class="row">
                            <div class="col-7">
                                <span class="font-20 text-uppercase"><b>Traveller's Information</b></span><br>
                                <div id="error_div_info" style="display: none;white-space: pre-wrap" class="alert alert-block alert-danger"></div>
                                <div class="row mt10">
                                    <div class="col-12">
                                        <label for="inputEmail" class="control-label">Primary Passenger Name *:</label>
                                    </div>
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-sm-6">
												<?= $form->textField($model, 'bkg_user_name', array('placeholder' => "First Name", 'class' => 'form-control nameFilterMask')) ?>
												<?php echo $form->error($model, 'bkg_user_name', ['class' => 'help-block error']); ?>
                                            </div>
                                            <div class="col-sm-6">
												<?= $form->textField($model, 'bkg_user_lname', array('placeholder' => "Last Name", 'class' => 'form-control nameFilterMask')) ?>
												<?php echo $form->error($model, 'bkg_user_lname', ['class' => 'help-block error']); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <label for="inputEmail" class="control-label">Primary Email address *:</label>
                                    </div>
                                    <div class="col-12">
										<?= $form->emailField($model, 'bkg_user_email', ['placeholder' => "Email Address", 'id' => CHtml::activeId($model, "bkg_user_email2"), 'class' => 'form-control']) ?>     
										<?php echo $form->error($model, 'bkg_user_email', ['class' => 'help-block error']); ?>                      
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <label for="inputEmail" class="control-label">Primary Contact Number *:</label>
                                    </div>
                                    <div class="col-12">
										<?php
										$loggedinemail	 = "";
										$loggedinphone	 = "";
										if (!Yii::app()->user->isGuest)
										{
											$loggedinemail	 = Yii::app()->user->loadUser()->usr_email;
											$loggedinphone	 = Yii::app()->user->loadUser()->usr_mobile;
										}

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
                                <div class="row">
                                    <div class="col-12 mt20">
                                        <label for="inputEmail" class="control-label">Send me booking confirmations by :</label>
                                    </div>
                                    <div class="col-12">
                                        <div class="row">   
                                            <div class="col-5 isd-input">
                                                <label class="checkbox-inline pt0 pr30 check-box">Email
													<?php echo $form->checkBox($model, 'bkg_send_email'); ?>
                                                    <span class="checkmark-box"></span>
                                                </label>

                                            </div>
                                            <div class="col-7 pr0">
                                                <label class="checkbox-inline pt0 check-box">Phone
													<?php echo $form->checkBox($model, 'bkg_send_sms'); ?>
                                                    <span class="checkmark-box"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mt20">
								<?php //= CHtml::submitButton('NEXT', array('class' => 'btn next3-btn pl40 pr40', 'id' => 'nxtBtnAddDtls'));     ?>
								<?php echo CHtml::button('NEXT', array('class' => 'btn-orange pl30 pr30 m0', 'id' => 'nxtBtnAddDtls')); ?>
                            </div>
                        </div>
                    </div> 
                </div>
				<?php
				$dboApplicable = Filter::dboApplicable($model);
				if ($dboApplicable)
				{
					if (!Yii::app()->user->isGuest)
					{
						?>
						<div class="col-sm-4 text-center mt20">
							<a target="_blank" href="<?php echo Yii::app()->createUrl("/terms/doubleback"); ?>"><img src="/images/doubleback_fares2.jpg" alt="" width="350" class="img-responsive"></a>
						</div>
						<?php
					}
				}
				?>
				<?php $this->endWidget(); ?>
            </div>
        </div>
    </div>
	<?php
	if (!empty($note))
	{
		?>
		<div class="sidenav mt20">
			<button class="spclinsadv">SPECIAL INSTRUCTIONS & ADVISORIES THAT MAY AFFECT YOUR PLANNED TRAVEL <i class="fa fa-caret-down"></i></button>
			<div class="dropdown-container row" style="display: none;">
				<div class="compact">
					<div class="text-uppercase font-weight-bold text-white text-center p-2 rounded-top" style="background:#0d47a1">Special instructions & advisories that may affect your planned travel</div>
					<div class="row" style="padding: 0px 15px;">
						<div class="col-sm-2" style="border-right:1px solid #ccc;border-left:1px solid #ccc;border-bottom:1px solid #ccc;">
							<div class="p5" style="font-size: 1.2em"><strong>Place</strong></div></div>
						<div class="col-sm-6" style="border-right:1px solid #ccc;border-bottom:1px solid #ccc">
							<div class="p5" style="font-size: 1.2em"><span class="m5"><strong>Note</strong></span></div></div>
						<div class="col-sm-2" style="border-right:1px solid #ccc;border-bottom:1px solid #ccc">
							<div class="p5" style="font-size: 1.2em"><span class="m5"><strong>Valid From</strong></span></div></div>
						<div class="col-sm-2" style="border-right:1px solid #ccc;border-bottom:1px solid #ccc">
							<div class="p5" style="font-size: 1.2em"><span class="m5"><strong> Valid To</strong></span></div></div>
					</div>
					<?php
					for ($i = 0; $i < count($note); $i++)
					{
						?>   
						<div class="row" style="padding: 0px 15px;">
							<div class="col-sm-2" style="border-right:1px solid #ccc;border-left:1px solid #ccc;border-bottom:1px solid #ccc">
								<div class="p5"> 
									<?php
									if ($note[$i]['dnt_area_type'] == 1)
									{
										?>
										<?= ($note[$i]['dnt_zone_name']) ?>
									<?php } ?>                                       
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
										<?= "Applicable to all" ?>
										<?php
									}
									else if ($note[$i]['dnt_area_type'] == 4)
									{
										?>
										<?= Promos::$region[$note[$i]["dnt_area_id"]] ?>
										<?php
									}
									?>
								</div></div>
							<div class="col-sm-6" style="border-right:1px solid #ccc;border-bottom:1px solid #ccc">
								<div class="p5"><span><?= ($note[$i]['dnt_note']) ?></span></div></div>
							<div class="col-sm-2" style="border-right:1px solid #ccc;border-bottom:1px solid #ccc">
								<div class="p5"><span>  <?= (DateTimeFormat::DateTimeToLocale($note[$i]['dnt_valid_from'])) ?></span></div></div>
							<div class="col-sm-2" style="border-right:1px solid #ccc;border-bottom:1px solid #ccc">
								<div class="p5"><span> <?= (DateTimeFormat::DateTimeToLocale($note[$i]['dnt_valid_to'])) ?></span></div></div>
						</div><?php
					}
					?>


				</div>
			</div>

		</div>
	<?php } ?>
	<?php
#$model->bkg_booking_type	 = 1; // For Testing purpose 
#$model->bkg_transfer_type	 = 0; // For Testing purpose 
	?>

    <script type="text/javascript">
        var bookNow = new BookNow();
        $("#l3").find("span").html(' By <?= $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label ?>');
        $("#info_car_type").html('<?= $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label ?>');

        if (parseInt('<?= $model->bkg_booking_type ?>') == 7)
        {
            $('#bdate').html('<?= date('\O\N jS M Y \ , \A\T h:i A', strtotime($model->bkg_pickup_date)) ?>');
        }
        if (parseInt('<?= $model->bkg_booking_type ?>') == 1 || parseInt('<?= $model->bkg_booking_type ?>') == 3 || parseInt('<?= $model->bkg_booking_type ?>') == 2 || parseInt('<?= $model->bkg_booking_type ?>') == 5)
        {
            $('#btype').html(bookNow.arrTripTypes['<?= $model->bkg_booking_type ?>']);
        }

        var hyperModel = new HyperLocation();
        var model = {};
        var data = {};
        data.infoSource = "<?= CHtml::activeId($model, "bkg_info_source") ?>";
        data.bookingType = parseInt(<?= $model->bkg_booking_type ?>);
        data.carType = "<?= VehicleTypes::model()->getCarByCarType($model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_id); ?>";
        data.chkOthers = "<?= CHtml::activeId($model, "bkg_chk_others") ?>";
        data.sendSms = "<?= CHtml::activeId($model, "bkg_send_sms") ?>";
        data.sendEmail = "<?= CHtml::activeId($model, "bkg_send_email") ?>";
        data.contactNo = "<?= CHtml::activeId($model, "bkg_contact_no") ?>";
        data.infoSourceDesc = "<?= CHtml::activeId($model, "bkg_info_source_desc") ?>";
        data.flightChk = "<?= CHtml::activeId($model, "bkg_flight_chk") ?>";
        data.key = "<?= $key; ?>";
        data.fromCity = "<?= $brtRoute->brtFromCity->cty_id ?>",
                data.queryStr = "<?= $brtRoute->brtFromCity->cty_name ?>";
        data.vehicleType = "<?= $model->bkg_vehicle_type_id ?>";
        data.pickupLaterChk = "<?= CHtml::activeId($model, "pickup_later_chk") ?>";
        data.dropLaterChk = "<?= CHtml::activeId($model, "drop_later_chk") ?>";
        data.countryCode = "<?= CHtml::activeId($model, "bkg_country_code") ?>";
        data.userEmail = "<?= CHtml::activeId($model, "bkg_user_email") ?>";
        data.hyperlocationClass = '<?= $hyperLocationClass ?>';
        bookNow.data = data;
        $(document).ready(function ()
        {
            var cntRt = "<?= sizeof($model->bookingRoutes) ?>";
            $('[data-toggle="tooltip"]').tooltip();
            setHyperLocationData();
            bookNow.bkInfoReady();
            for (i = 0; i < cntRt; i++)
            {
                $("#skipAdd" + i).prop("checked", true);
                $("#skipAdd" + i).attr('disabled', 'disabled');
            }
            $("#BookingTemp_pickup_later_chk").prop("checked", true);
            $("#BookingTemp_pickup_later_chk").attr('disabled', 'disabled');
            $("#BookingTemp_drop_later_chk").prop("checked", true);
            $("#BookingTemp_drop_later_chk").attr('disabled', 'disabled');
<?php
if ($model->bkg_is_gozonow == 1 && $model->bkg_user_name != '' && $model->bkg_user_email != '' )
{
	?>
//	            document.getElementById('nxtBtnAddDtls').style.display = "none";
//	            bookNow.bkInfoNext();
//	            document.getElementById('nxtBtnAddDtls').click();
<? } ?>
        });

<?php if ($islogin == 1)
{ ?>
	        bookNow.checkIfLoggedIn();
<?php } ?>
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

        $('.autoMarkerLoc').click(function (event)
        {
            var locKey = $(event.currentTarget).data('lockey');
            var ctyLat = <?= json_encode($ctyLat) ?>;
            var ctyLon = <?= json_encode($ctyLon) ?>;
            var bound = <?= json_encode($bound) ?>;
            var isAirport = <?= json_encode($isCtyAirport) ?>;
            var isCtyPoi = <?= json_encode($isCtyPoi) ?>;
            if ($('.locLat_' + locKey).val() != '' && $('.locLon_' + locKey).val() != '')
            {
                ctyLat[locKey] = $('.locLat_' + locKey).val();
                ctyLon[locKey] = $('.locLon_' + locKey).val();
            }

            $.ajax({
                "type": "POST",
                "url": '<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/autoMarkerAddress')) ?>',
                "data": {"ctyLat": ctyLat[locKey], "ctyLon": ctyLon[locKey], "bound": bound[locKey], "isCtyAirport": isAirport[locKey], "isCtyPoi": isCtyPoi[locKey], "locKey": locKey, "airport": 0, "YII_CSRF_TOKEN": $("input[name='YII_CSRF_TOKEN']").val()},
                "dataType": "HTML",
                "success": function (data1)
                {
                    $('#mapModelContent').html('');
                    $('#mapModelContent').html(data1);
                    $('#mapModal').modal('show');
                }

            });
        });

        $('.txtHyperLocation').change(function ()
        {
<?php
if ($model->bkg_booking_type == 4)
{
	?>
	            hyperModel.findAddressAirport(this.id);
	<?php
}
else
{
	?>
	            hyperModel.findAddress(this.id);
<?php } ?>
        });

        $('#BookingTemp_pickup_later_chk,#BookingTemp_drop_later_chk').change(function (event)
        {
            var key = $(event.currentTarget).data('key');
            if ($(event.currentTarget).prop("checked") == true)
            {
                $(".brt_location_" + key).attr('readonly', true);
                $(".brt_location_" + key).addClass("input-disabled");
                $(".brt_location_" + key).val("");
                $('.locLat_' + key).val('');
                $('.locLon_' + key).val('');
                $('.locPlaceid_' + key).val('');
                $('.locFAdd_' + key).val('');
            } else
            {
                $(".brt_location_" + key).attr('readonly', false);
                $(".brt_location_" + key).removeClass("input-disabled");
            }
        });

        $('input[name="skipAdd"]').click(function (event)
        {
            var key = $(event.currentTarget).data('key');
            if ($(event.currentTarget).is(':checked') == true)
            {
                $('.brt_location_' + key).attr('disabled', true);
                $('.brt_location_' + key).val('');
                $('.locLat_' + key).val('');
                $('.locLon_' + key).val('');
                $('.locPlaceid_' + key).val('');
                $('.locFAdd_' + key).val('');
                $('.brt_location_' + key).css('border', '1px solid #ccc');
                $('.skipAddErr' + key).addClass('hide');
            } else
            {
                $('.brt_location_' + key).attr('disabled', false);
            }
        });

        var dropdown = document.getElementsByClassName("spclinsadv");
        var i;
        for (i = 0; i < dropdown.length; i++) {
            dropdown[i].addEventListener("click", function () {
                this.classList.toggle("active");
                var dropdownContent = this.nextElementSibling;
                if (dropdownContent.style.display === "block") {
                    dropdownContent.style.display = "none";
                } else {
                    dropdownContent.style.display = "block";
                }
            });
        }
    </script>
