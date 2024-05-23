<?php Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');
?>
<style>
    .isd-input .selectize-input{
        min-width: 70px ;
        border-radius: 0;
    }
    .form-horizontal .checkbox-inline {
        padding-top: 0;
    }
    input[type="radio"]{
        margin-top: 0;
    }
	td, th {
        padding: 10px  !important ; 
    }
	.fb-btn{
		background: #3B5998;
		text-transform: uppercase;
		font-size: 14px;
		border: none;
		padding: 7px 8px;
		color: #fff;
		-webkit-border-radius: 2px;
		-moz-border-radius: 2px;
		border-radius: 2px;
		transition: all 0.5s ease-in-out 0s;
	}
	@media (max-width: 767px)
    {
		.modal-dialog{ margin-left: auto; margin-right: auto;}
	}
</style>
<?php
$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
	'id'					 => 'bookingtime-form',
	'enableClientValidation' => true,
	'clientOptions'			 => array(
		'validateOnSubmit'	 => true,
		'errorCssClass'		 => 'has-error',
		'afterValidate'		 => 'js:function(form,data,hasError){
				if(!hasError){
					$.ajax({
						"type":"POST",
						"dataType":"html",
						"url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('booking/rates')) . '",
						"data":form.serialize(),
                        "beforeSend": function(){
                            ajaxindicatorstart("");
                        },
                        "complete": function(){
                            ajaxindicatorstop();
                        },
						"success":function(data2){
							var data = "";
							var isJSON = false;
							try {
							data = JSON.parse(data2);
							
							isJSON = true;
							} catch (e) {
								
							}
							if(!isJSON){  
							
								' . '$jsBookNow.showTab(\'Quote\');' . '  
								trackPage("' . CHtml::normalizeUrl(Yii::app()->createUrl('booking/rates')) . '");
								}
							else
							{
								var errors = data.errors;
								
								msg =JSON.stringify(errors);
								if(errors)
								{
									
									var x = window.matchMedia("(max-width: 700px)");
									if (x.matches) 
									{
										var result = JSON.parse(msg);
										 for (k in result) {
											bootbox.alert({
												 message: result[k],
												 class: "",
												 callback: function () {
												 }
											})
										 }
									}
								}
								settings=form.data(\'settings\');
								$.each (settings.attributes, function (i) {
									$.fn.yiiactiveform.updateInput (settings.attributes[i], errors, form);
								});
								$.fn.yiiactiveform.updateSummary(form, errors);
								messages = errors;
								content = \'\';
								var summaryAttributes = [];
								for (var i in settings.attributes) {
									if (settings.attributes[i].summary) {
									
										summaryAttributes.push(settings.attributes[i].id);
									}
								}
								
								
								
								$jsBookNow.displayError(form, messages);
							} 
							$jsBookNow.enableTab("Quote");	
							$("#menuQuote").html(data2);
							
						},
						error: function (xhr, ajaxOptions, thrownError) 
						{
							alert(thrownError);
								alert(xhr.status);
								alert(thrownError);
						}
					});
				}
			}'
	),
	'enableAjaxValidation'	 => false,
	'errorMessageCssClass'	 => 'help-block',
	'htmlOptions'			 => array(
		'class'			 => 'form-horizontal',
		'autocomplete'	 => 'off',
	),
		));
?>
<?= $form->hiddenField($model, 'bkg_booking_type'); ?>
<?= $form->hiddenField($model, 'bkg_id', ['id' => 'bkg_id1', 'class' => 'clsBkgID']); ?>
<?= $form->hiddenField($model, 'hash', ['id' => 'hash1', 'class' => 'clsHash']); ?>
<?= $form->hiddenField($model, 'bkg_package_id', ['id' => 'bkg_package_id1']); ?>
<?= $form->hiddenField($model, 'bktyp', ['value' => 0, 'id' => 'bktyp1']); ?>
<?= $form->hiddenField($model, 'stepOver'); ?>
<input type="hidden" id="step1" name="step" value="1">

<?= $form->errorSummary($model); ?>
<!-- LOGIN SECTION -->
<div id="error_div" style="display:none" class="alert alert-block alert-danger"></div>

<?php
if ($model->bkg_booking_type == 5) // Package
{
	$pckageID			 = $model->bkg_package_id;
	$pickupDate			 = $model->bkg_pickup_date_date;
	$pickupTime			 = $model->bkg_pickup_date_time;
	$formatPickUpDt		 = DateTimeFormat::DatePickerToDate($pickupDate);
	$formatPickUpTime	 = date('H:i:s', strtotime($pickupTime));
	$pickupDtTime		 = $formatPickUpDt . ' ' . $formatPickUpTime;
	$packagemodel		 = Package::model()->findByPk($pckageID);
	$routeModel			 = $packagemodel->packageDetails;
	$multijsondata		 = BookingRoute::model()->setTripRouteInfo($routeModel, 5, $pickupDtTime);
	$model->preData		 = $multijsondata;
	$this->renderPartial("bkTypePackage", ['model' => $model, 'form' => $form]);
}
else
{
	?>
	<div class="row">
		<div class="col-xs-12">
			<?php
			$brtFromCityId = 0;
			$brtToCityId   = 0;
			if (trim($model->bkg_route_data) == '')
			{
				$model->bkg_route_data = 0; // For ajax load only
			}
			$brtReturn	 = clone($model);
			$brtRoutes	 = $model->getRoutes();
			if(in_array($model->bkg_booking_type, [4]) && $model->bktyp == 4)
			{
				$brtRoutes = $model->swapRouteForAirportTransfer($brtRoutes);
				$model->bkgAirport = $brtRoutes[0]->brt_from_city_id;
			}
			if ($model->bkg_booking_type == 2)
			{
				$brtRoutes[0]->brt_return_date_date	 = $brtReturn['bookingRoutes'][0]->brt_return_date_date;
				$brtRoutes[0]->brt_return_date_time	 = '10:00PM'; // return time always 10 pm
			}
			
			foreach ($brtRoutes as $key => $brtRoute)
			{
				
				if ($key > 0 && $step == 0)
				{
					continue;
				}
			
				$brtRoute->attributes;
				if($model->bkg_booking_type == 4 || $model->bkg_booking_type == 1)
				{
					$brtFromCityId = $brtRoute->brt_from_city_id;
					$brtToCityId   = $brtRoute->brt_to_city_id;
				}
				$form->error($brtRoute, 'brt_from_city_id');
				$form->error($brtRoute, 'brt_to_city_id');
				$form->error($brtRoute, 'brt_pickup_date_date');
				$form->error($brtRoute, 'brt_pickup_date_time');
				$this->renderPartial('addroute', ['model' => $brtRoute, 'sourceCity' => $oldRoute->brt_to_city_id, 'previousCity' => $oldRoute->brt_from_city_id, 'btype' => $model->bkg_booking_type, 'index' => 0, 'bkgTempModel' => $model,'form' => $form], false, false);
			}
			?>
			<span id='insertBefore'></span> 
		</div>
	</div>
	<?
	if ($model->bkg_booking_type == 3)
	{
		?>
		<div class="row float-right clsMulti" style="white-space: nowrap">
			<div class="col-xs-12">
				<a href="Javascript:void(0)" class="btn btn-primary addmoreField weight400 font-bold" id="fieldAfter" title="Add More" onclick="$jsBookNow.addMoreRoute();">
					<i class="fa fa-plus"></i></a>
				<a href="Javascript:void(0)" class="btn btn-danger" id="fieldBefore" title="Remove" style="display: none"><i class="fa fa-times"></i></a>
			</div>
		</div>
		<?
	}
}
?>

<div class="col-xs-12 text-center mt10 mb10" >
	<?php if($model->bkg_booking_type == 4){ ?>
		<button type="button" class="btn next3-btn pl40 pr40" id="btnAirTransfer">NEXT</button>
	<?php }else if($model->bkg_booking_type == 1){ ?>
		<button type="button" class="btn next3-btn pl40 pr40" id="onewaybtn">NEXT</button>
	<?php }
		else if ($model->bkg_booking_type == 9 || $model->bkg_booking_type == 10 ||$model->bkg_booking_type == 11)
        {
            ?>
            <button type="button" class="btn next3-btn pl40 pr40" id="dayrentalbtn">NEXT</button>
		<?php
		}
		else{ ?>
		<?= CHtml::submitButton('NEXT', array('class' => 'btn next3-btn pl40 pr40 devbtnstep2', 'id' => 'btnSubmit')); ?>
	<?php } ?>
	

</div>
<?php $this->endWidget(); ?>

<script type="text/javascript">
	$jsBookNow = new BookNow();
	var hyperModel = new HyperLocation();
	var model = {};
	model.count = $("INPUT.ctyDrop").length;
	model.fromCityId = "<?= $model->bkg_from_city_id ?>";
	model.toCityId = "<?= $model->bkg_to_city_id ?>";
	//model.transferTypeOne = "<?//= CHtml::activeId($model, "bkg_transfer_type_0") ?>";
	//model.transferTypeTwo = "<?//= CHtml::activeId($model, "bkg_transfer_type_1") ?>";
	model.bookingType = parseInt(<?= $model->bkg_booking_type ?>);
	model.transferType = parseInt(<?= $model->bkg_transfer_type; ?>);
	$jsBookNow.data = model;
	$(document).ready(function ()
	{
		<?php if($model->bkg_booking_type == 4)
		{
			if($model->bkg_transfer_type == 2){
			?>
				$('.autoAirMarkerLoc').attr('title', 'Select From Address on map');
				$('#BookingTemp_bkg_transfer_type_1').change();
			<?php } 
			else {
			?>
				$('.autoAirMarkerLoc').attr('title', 'Select To Address on map');
				$('#BookingTemp_bkg_transfer_type_0').change();
		<?php } } ?>
		<?php if($model->bkg_booking_type == 4 && $model->bktyp == 1 && !UserInfo::isLoggedIn()){?>
			alert("The distance of travel is too small for a one-way trip, we're switching the trip type to Airport transfer (Local rental)");
		<?php }else if($model->bkg_booking_type == 1 && $model->bktyp == 4 && !UserInfo::isLoggedIn()){ ?>
			alert("The distance of travel is too long for a local rental (Airport Transfer), we're switching the trip type to a One-way trip (Outstation rental)");
		<?php } ?>
		$('#<?= CHtml::activeId($brtRoute, "brt_pickup_date_time") ?>').val('<?= date('h:i A', strtotime('+4 hour')) ?>');
		$jsBookNow.bkRouteReady();
		hyperModel.initializeplAirport();
		$('#btnAirTransfer').on('click',function(){
			$('#bktyp1').val(4);
			var prevFromCtyId  = '<?= $brtFromCityId ?>';
			var prevToCtyId    = '<?= $brtToCityId ?>';
			var currFromCtyId  = $('#ctyIdAir0').val();
			var currToCtyId    = $('#ctyIdAir1').val();
			if(prevFromCtyId == 0 || prevFromCtyId != currFromCtyId || prevToCtyId == 0 || prevToCtyId != currToCtyId)
			{
				$.ajax({
					"type":"POST",
					"url": '<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/validateAirport')) ?>',
					"data": $('#bookingtime-form').serialize(),
					"dataType": "json",
					"success":function(data1)
					{
						if(data1.success)
						{
							if(data1.hasOwnProperty("errors"))
							{
								$("#BookingTemp_bkg_booking_type").val(1);
								$("#BookingTemp_stepOver").val(1);
								$('#btype').text('ONE WAY TRIP');
							}
							$('#bookingtime-form').submit();
						}
						else
						{
							var errors = data1.errors;
							var content = "";
							for(var key in errors)
							{
								$.each(errors[key], function (j, message) {
									content = content + message + '\n';
								});
							}
							alert(content); 
						}  
					}

				});
			}
			else
			{
				$('#bookingtime-form').submit();
			}

		});
		
		$('#onewaybtn').click(function(){
			$('#bktyp1').val(1);
			var prevFromCtyId  = '<?= $brtFromCityId ?>';
			var prevToCtyId    = '<?= $brtToCityId ?>';
			var currFromCtyId  = $('SELECT.ctyPickup').val();
			var currToCtyId    = $('SELECT.ctyDrop').val();
			if(prevFromCtyId == 0 || prevFromCtyId != currFromCtyId || prevToCtyId == 0 || prevToCtyId != currToCtyId)
			{
				$.ajax({
					"type":"GET",
					"async":false,
					"url": '<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/validateOneway')) ?>',
					"data": {'fromCityId':$("SELECT.ctyPickup").val(),'toCityId':$("SELECT.ctyDrop").val()},
					"dataType": "json",
					"success":function(data1)
					{
						if(data1.success == true)
						{
							$("#BookingTemp_bkg_booking_type").val(data1.bkType);
							$('#btype').text('AIRPORT TRANSFER');
							$("#BookingTemp_stepOver").val(1);
							$("#bkg_transfer_type1").val(data1.transferType);

							$('#OnelocLat0').val(data1.from.cty_lat);
							$('#OnelocLon0').val(data1.from.cty_long);
							$('#OnelocFAdd0').val(data1.from.cty_garage_address);
							$('#Onelocation0').val(data1.from.cty_garage_address);
							$('#OneisAirport0').val(data1.from.cty_is_airport);

							$('#OnelocLat1').val(data1.to.cty_lat);
							$('#OnelocLon1').val(data1.to.cty_long);
							$('#OnelocFAdd1').val(data1.to.cty_garage_address);
							$('#Onelocation1').val(data1.to.cty_garage_address);
							$('#OneisAirport1').val(data1.to.cty_is_airport);
						}
						else
						{
							$("#BookingTemp_bkg_booking_type").val(1);
							$("#BookingTemp_stepOver").val(0);
							$('#btype').text('ONE WAY TRIP');
							$("#bkg_transfer_type1").val(0);
							$('#OnelocLat0').val('');
							$('#OnelocLon0').val('');
							$('#OnelocFAdd0').val('');
							$('#Onelocation0').val('');
							$('#OneisAirport0').val('');

							$('#OnelocLat1').val('');
							$('#OnelocLon1').val('');
							$('#OnelocFAdd1').val('');
							$('#Onelocation1').val('');
							$('#OneisAirport1').val('');
						}
						$('#bookingtime-form').submit();
					}

				});
			}
			else
			{
			  $('#bookingtime-form').submit();
			}
		});
		
		$('#dayrentalbtn').click(function () {
            $.ajax({
                "type": "GET",
                "async": false,
                "url": '<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/validateDayRental')) ?>',
                "data": {'fromCityId': $("SELECT.ctyPickup").val(), 'bkType': $("#BookingTemp_bkg_booking_type").val()},
                "dataType": "json",
                "success": function (data1)
                {
                    if (data1.success == true)
                    {
                        $("#BookingTemp_bkg_booking_type").val(data1.bkType);
                        $("#BookingRoute_brt_to_city_id").val(data1.brt_to_city_id);
                        $('#topRouteDesc').text('Day Rental');
                          
                        $('#OnelocLat0').val(data1.from.cty_lat);
                        $('#OnelocLon0').val(data1.from.cty_long);
                        $('#OnelocFAdd0').val(data1.from.cty_garage_address);
                        $('#Onelocation0').val(data1.from.cty_garage_address);
                        $('#OneisAirport0').val(data1.from.cty_is_airport);
                        
                    } else
                    {
                        $("#BookingTemp_bkg_booking_type").val(data1.bkType);
                        $('#topRouteDesc').text('Day Rental');

                        $('#OnelocLat0').val('');
                        $('#OnelocLon0').val('');
                        $('#OnelocFAdd0').val('');
                        $('#Onelocation0').val('');
                        $('#OneisAirport0').val('');
                    }
                    $('#bookingtime-form').submit();
                }
            });
        });
		
		$('.autoComLoc').change(function () {
			$('#btnAirTransfer').attr('disabled',true);
			$('#btnAirTransfer').text('Loading...');
			hyperModel.findAddressAirport(this.id);
		});
		
		$('.autoAirMarkerLoc').click(function(event){
			var locKey = $(event.currentTarget).data('lockey');
			var lat = $('#locLat1').val();
			var long = $('#locLon1').val();
			var isAirport = $('#isAirport1').val();
			if ($("#BookingTemp_bkg_transfer_type_1").is(":checked")) {
				var title  = 'Enter approximate source location and then move pin to exact location';
				var locSearch = 'source';
			}
			if ($("#BookingTemp_bkg_transfer_type_0").is(":checked")) {
				var title  = 'Enter approximate destination location and then move pin to exact location';
				var locSearch = 'destination';
			}
			if(lat == '' || long == '')
			{
				lat = $('#locLat0').val();
				long = $('#locLon0').val();

			}
			if(lat == '' || long == '')
			{
				alert("Please select airport first");
			}
			else
			{
				$.ajax({
					"type":"POST",
					"url": '<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/autoMarkerAddress')) ?>',
					"data": {"ctyLat":lat,"ctyLon":long,"bound":'',"isCtyAirport":isAirport,"isCtyPoi":0,"locKey":locKey,'location':locSearch,"airport":1,"YII_CSRF_TOKEN":$("input[name='YII_CSRF_TOKEN']").val()},
					"dataType": "HTML",
					"success":function(data1)
					{
						var box = bootbox.dialog({
							message: data1,
							title: title,
							size: 'small',
							onEscape: function ()
							{
								// user pressed escape
							},
	});	
					}

				});
			}
		});
	});	
	$jsBookNow.bkRouteNext();

	$('.devbtnstep2').click(function (e) {
		$("#BookingTemp_bkg_user_email_em_").html("");
		$("#BookingTemp_bkg_user_email_em_").hide();
		$("#BookingTemp_bkg_contact_no_em_").html("");
		$("#BookingTemp_bkg_contact_no_em_").hide();
		var uemail = $("#BookingTemp_bkg_user_email1").val();
		var uphn = $("#BookingTemp_fullContactNumber").val();
		if ($.trim(uemail) != "")
		{
			if (!$jsBookNow.validateEmail(uemail)) {
				$("html,body").animate({scrollTop: 180}, "slow");
				$("#BookingTemp_bkg_user_email_em_").html("Email is not valid");
				$("#BookingTemp_bkg_user_email_em_").css("color", "#a94442");
				$("#BookingTemp_bkg_user_email_em_").show();
				e.preventDefault();
			}
		}
		if ($.trim(uphn) != "")
		{
			var regex = /^[0-9\s]*$/;
			if (!regex.test($.trim(uphn))) {
				$("html,body").animate({scrollTop: 180}, "slow");
				$("#BookingTemp_bkg_contact_no_em_").html("Contact number not valid.");
				$("#BookingTemp_bkg_contact_no_em_").css("color", "#a94442");
				$("#BookingTemp_bkg_contact_no_em_").show();
				e.preventDefault();
			}
		}
	});
	
	function populateAirportList(obj, cityId)
	{
		obj.load(function (callback)
		{
			var obj = this;
			if ($sourceList == null)
			{
				xhr = $.ajax({
					url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('index/getairportcities')) ?>',
					dataType: 'json',
					data: {
						city: cityId
					},
					//  async: false,
					success: function (results)
					{
						$sourceList = results;
						obj.enable();
						callback($sourceList);
						obj.setValue('<?= $model->bkgAirport ?>');
					},
					error: function ()
					{
						callback();
					}
				});
			} else
			{
				obj.enable();
				callback($sourceList);
				obj.setValue('<?= $model->bkgAirport ?>');
			}
		});
	}
	
	function loadAirportSource(query, callback)
	{
		$.ajax({
			url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('index/getairportcities')) ?>?q=' + encodeURIComponent(query),
			type: 'GET',
			dataType: 'json',
			error: function ()
			{
				callback();
			},
			success: function (res)
			{
				callback(res);
			}
		});
	}
	
	$('input[name="BookingTemp[bkg_transfer_type]"]').change(function(event){
		var radVal = $(event.currentTarget).val();
		var dlabel = (radVal == 2) ?  'From Address' : 'To Address';
		var slabel = (radVal == 1) ?  'From the Airport' : 'To the Airport';
		$('#trslabel').text(slabel);
		$('#trdlabel').text(dlabel);
		$('#trslabel').css('font-weight','bold');
		$('#trdlabel').css('font-weight','bold');
		if(radVal == 2)
		{
			$('.autoAirMarkerLoc').attr('title', 'Select source location on map');
			$('.autoAirMarkerLoc').attr('data-original-title', 'Select source location on map');
		}
		else
		{
			$('.autoAirMarkerLoc').attr('title', 'Select destination location on map');
			$('.autoAirMarkerLoc').attr('data-original-title', 'Select location Address on map');
		}
	});
	
</script>	
