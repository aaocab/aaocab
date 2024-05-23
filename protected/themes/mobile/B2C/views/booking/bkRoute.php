<style>
    .selectize-input{ min-width: 60px;}
    td.disabled{
        display: table-cell !important;
    }
</style>
<?php

$version = Yii::app()->params['siteJSVersion'];
$version = '';

$form = $this->beginWidget('CActiveForm', array(
    'id'                     => 'bookingtime-form',
    'enableClientValidation' => true,
    'clientOptions'          => array(
        'validateOnSubmit' => true,
        'errorCssClass'    => 'has-error',
        'afterValidate'    => 'js:function(form,data,hasError){
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
							$("html,body").animate({ scrollTop: 180 }, "slow");
							var data = "";
							var isJSON = false;
							try {							
							data = JSON.parse(data2);
							var rdata = data.errors[""];
							isJSON = true;
							} catch (e) {								
							}
							if(!isJSON){  
								$jsBookNow.showTab(\'Quote\');  
							}
							else
							{
								//var errors = JSON.parse(data.errors);		
								var errors = data.errors;
								
								msg =JSON.stringify(errors);
								msg = "";
								for (k in errors) {
									if($.type(errors[k])==="string")
									{
										msg += errors[k]+"<br/>"
									}
								}
								$jsBookNow = new BookNow();
								$jsBookNow.showErrorMsg(msg);
								return false;
							} 
						    $("#menuQuote").html(data2);							
							$("#menuQuote").show();
							$("#menuRoute").hide();
							//$("#mob_route_section").hide();
							//$("#bookingtime-form").hide(); 
							
						},
						error: function (xhr, ajaxOptions, thrownError) 
						{
								$jsBookNow.showErrorMsg(xhr.status);
								$jsBookNow.showErrorMsg(thrownError);

						}
					});
				}
			}'
    ),
    'enableAjaxValidation'   => false,
    'errorMessageCssClass'   => 'help-block',
    'htmlOptions'            => array(
        'class'        => 'form-horizontal',
        'autocomplete' => 'off',
    ),
        ));
?>
<?= $form->hiddenField($model, 'bkg_booking_type'); ?>
<?= $form->hiddenField($model, 'bkg_id', ['id' => 'bkg_id1', 'class' => 'clsBkgID']); ?>
<?= $form->hiddenField($model, 'hash', ['id' => 'hash1', 'class' => 'clsHash']); ?>
<?= $form->hiddenField($model, 'bkg_package_id', ['id' => 'bkg_package_id1']); ?>
<?= $form->hiddenField($model, 'bkg_user_id', ['class' => 'clsUserId', 'value' => '']); ?> 
<?= $form->hiddenField($model, 'bktyp', ['value' => 0, 'id' => 'bktyp1']); ?>
<?= $form->hiddenField($model, 'stepOver'); ?>
<input type="hidden" id="step1" name="step" value="1">
<div id="mob_route_section">
    <?php $this->renderPartial("bkRouteHeader" . $this->layoutSufix, ['prevStep' => 0, 'model' => $model]); ?>

    <?php $this->renderPartial("bkLogin" . $this->layoutSufix, ['model' => $model, 'form' => $form]); ?>            

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

        <?php
		$arrivalTime = $model->bookingRoutes[0]->arrival_time;
		$brtFromCityId = 0;
		$brtToCityId   = 0;
        if (trim($model->bkg_route_data) == '')
        {
            $model->bkg_route_data = 0; // For ajax load only
        }

        $brtReturn = clone($model);
        $brtRoutes = $model->bookingRoutes;

		if(in_array($model->bkg_booking_type, [4]) && $model->bktyp == 4)
		{
			$brtRoutes = $model->swapRouteForAirportTransfer($brtRoutes);
			$brtRoutes[0]->airport = $brtRoutes[0]->brt_from_city_id;
		}
        if ($model->bkg_booking_type == 2)
        {
            $brtRoutes[0]->brt_return_date_date = $brtReturn['bookingRoutes'][0]->brt_return_date_date;
            $brtRoutes[0]->brt_return_date_time = '10:00PM'; // return time always 10 pm
        }

        if ($oldRoute == null)
        {
            $oldRoute = BookingRoute::model();
        }
		
		foreach ($brtRoutes as $key => $brtRoute)
		{ 
		if ($key > 0 && $step == 0)
		{
			continue;
		}
		if ($model->bkg_booking_type == 4 || $model->bkg_booking_type == 1)
		{
			$brtFromCityId	 = $brtRoute->brt_from_city_id;
			$brtToCityId	 = $brtRoute->brt_to_city_id;
		}
		$form->error($brtRoute, 'brt_from_city_id');
		$form->error($brtRoute, 'brt_to_city_id');
		$form->error($brtRoute, 'brt_pickup_date_date');
		$form->error($brtRoute, 'brt_pickup_date_time');

        $this->renderPartial('addroute' . $this->layoutSufix, ['model' => $brtRoute, 'form' => $form, 'sourceCity' => $oldRoute->brt_to_city_id, 'previousCity' => $oldRoute->brt_from_city_id, 'btype' => $model->bkg_booking_type, 'index' => 0,'estArrTime' => $arrivalTime ,'bkgTempModel' => $model], false, false);
		}
        $oldRoute->brt_to_city_id   = $brtRoutes[0]->brt_to_city_id;
        $oldRoute->brt_from_city_id = $brtRoutes[0]->brt_from_city_id;
        ?>  

        <span id='insertBefore'></span>
        <?
        if ($model->bkg_booking_type == 3)
        {
            ?>
            <div class="text-center clsMulti mt20 mr0" style="white-space: nowrap">

                <a href="Javascript:void(0)" class="btn-submit-orange inline-block pr10 pl10" id="fieldAfter" title="Add More" onclick="$jsBookNow.addRoute($('#bookingtime-form'));">
                    Add city</a>
                <a href="Javascript:void(0)" class="color-gray font-16 bg-none bolder inline-block pr10 pl10" id="fieldBefore" title="Remove" style="display: none">Remove city</a>

            </div>
            <?
        }
    }
    ?><div class="text-center clsMulti top-20">
    <?php if ($model->bkg_booking_type == 4)
    {
        ?>
            <button type="button" class="uppercase btn-orange shadow-medium" id="btnAirTransfer">Next</button>
        <?php
        }
        else if ($model->bkg_booking_type == 1)
        {
            ?>
            <button type="button" class="btn-2" id="onewaybtn">Next</button>

        <?php
        }
        else if ($model->bkg_booking_type == 9 || $model->bkg_booking_type == 10 ||$model->bkg_booking_type == 11)
        {
            ?>
            <button type="button" class="uppercase btn-orange shadow-medium" id="dayrentalbtn">Next</button>
<?php
}
else
{
    ?>
            <button type="submit" class="btn-2 font-16" id="nxtbk">Proceed</button>
<?php } ?>
    </div>
    <div class="clear"></div>
</div>
<?php $this->endWidget(); ?>
<?php
if (Yii::app()->request->url == '/' || Yii::app()->request->url == '/bknw')
{
    ?>
    <div class="content-boxed-widget top-30">
        <a href="<?= Yii::app()->getBaseUrl(true) ?>/cheapest-oneway-rides" target="_blank" class="color-black text-center font-14 wrapword line-height16">Make your outstation booking in advance and let us find you the cheapest one-way rides</a>
    </div>
<?php } 
Yii::app()->clientScript->registerCssFile(ASSETS_URL . 'css/mobile/style.css?' . $cssversion);
?>

<script type="text/javascript">
    $jsBookNow = new BookNow();
	var bookingModel = new Booking();
    var hyperModel = new HyperLocation();
    var model = {};
    model.count = $("INPUT.ctyDrop").length;
    model.fromCityId = "<?= $model->bkg_from_city_id ?>";
    model.toCityId = "<?= $model->bkg_to_city_id ?>";
    model.bookingType = parseInt(<?= $model->bkg_booking_type ?>);
    model.transferType = parseInt(<?= $model->bkg_transfer_type; ?>);
    $jsBookNow.data = model;
    $(document).ready(function ()
    {
		<?php if($model->bkg_booking_type == 4 && $model->bktyp == 1 && !UserInfo::isLoggedIn()){?>
			
			setTimeout(function(){ $jsBookNow.showInfoMsg("The distance of travel is too small for a one-way trip, we're switching the trip type to Airport transfer (Local rental)");}, 1000);
		<?php }else if($model->bkg_booking_type == 1 && $model->bktyp == 4 && !UserInfo::isLoggedIn()){ ?>
			setTimeout(function(){ $jsBookNow.showInfoMsg("The distance of travel is too long for a local rental (Airport Transfer), we're switching the trip type to a One-way trip (Outstation rental)");}, 1000);
		<?php } ?>
        $('#<?= CHtml::activeId($model, "brt_pickup_date_time") ?>').val('<?= date('h:i A', strtotime('+4 hour')) ?>');
        //hyperModel.initializeplAirport();
        $jsBookNow.bkRouteReady();
        $("#menuTripType").hide();
        $('#btnAirTransfer').click(function () {
			$('#bktyp1').val(4);
			var prevFromCtyId  = '<?= $brtFromCityId ?>';
			var prevToCtyId    = '<?= $brtToCityId ?>';
			var currFromCtyId  = $('#ctyIdAir0').val();
			var currToCtyId    = $('#ctyIdAir1').val();
			if(prevFromCtyId == 0 || prevFromCtyId != currFromCtyId || prevToCtyId == 0 || prevToCtyId != currToCtyId)
			{
				$.ajax({
					"type": "POST",
					"async": false,
					"url": '<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/validateAirport')) ?>',
					"data": $('#bookingtime-form').serialize(),
					"dataType": "json",
					"success": function (data1)
					{
						if (data1.success)
						{
							if (data1.hasOwnProperty("errors"))
							{
								$("#BookingTemp_bkg_booking_type").val(1);
								$("#BookingTemp_stepOver").val(1);
								$('#topRouteDesc').text('One Way Trip');
							}
							$('#bookingtime-form').submit();
						} else
						{
							var errors = data1.errors;
							var content = "";
							for (var key in errors)
							{
								$.each(errors[key], function (j, message) {
									content = content + message + '<br>';
								});
							}
							$jsBookNow.showErrorMsg(content);
						}
					}

				});
			}
			else
			{
				$('#bookingtime-form').submit();
			}

        });

       $('.autoMarkerLoc').click(function () {
//		    $('#btnAirTransfer').attr('disabled',true);
//			$('#btnAirTransfer').text('Loading...');
//			hyperModel.findAddressAirport(this.id);
			var locKey = $(event.currentTarget).data('lockey');
			var lat = $('#locLat1').val();
			var long = $('#locLon1').val();
			if(lat == '' || long == '')
			{
				lat = $('#locLat0').val();
				long = $('#locLon0').val();

			}
			if(lat == '' || long == '')
			{
				$jsBookNow.showErrorMsg("Please select airport first");
			}
			else
			{
				$.ajax({
					"type":"POST",
					"url": '<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/autoMarkerAddress')) ?>',
					"data": {"ctyLat":lat,"ctyLon":long,"bound":'',"isCtyAirport":0,"isCtyPoi":0,"locKey":locKey,"airport":1,"YII_CSRF_TOKEN":$("input[name='YII_CSRF_TOKEN']").val()},
					"dataType": "HTML",
					"success":function(data1)
					{
						$('#map-marker-content').html(data1);
						$('#booknow-map-marker').click();
					}

		});
			}
		});


        $('#onewaybtn').click(function () {
			$('#bktyp1').val(1);
			var prevFromCtyId  = '<?= $brtFromCityId ?>';
			var prevToCtyId    = '<?= $brtToCityId ?>';
			var currFromCtyId  = $('SELECT.ctyPickup').val();
			var currToCtyId    = $('SELECT.ctyDrop').val();
			if(prevFromCtyId == 0 || prevFromCtyId != currFromCtyId || prevToCtyId == 0 || prevToCtyId != currToCtyId)
			{
				$.ajax({
					"type": "GET",
					"async": false,
					"url": '<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/validateOneway')) ?>',
					"data": {'fromCityId': $("SELECT.ctyPickup").val(), 'toCityId': $("SELECT.ctyDrop").val()},
					"dataType": "json",
					"success": function (data1)
					{
						if (data1.success == true)
						{
							$("#BookingTemp_bkg_booking_type").val(data1.bkType);
							$("#bkg_transfer_type1").val(data1.transferType);
							$('#topRouteDesc').text('Airport Transfer');
							$("#BookingTemp_stepOver").val(1);
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
						} else
						{
							$("#BookingTemp_bkg_booking_type").val(1);
							$("#bkg_transfer_type1").val(0);
							$('#topRouteDesc').text('One Way Trip');
							$("#BookingTemp_stepOver").val(0);
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
    });
    
    $jsBookNow.bkRouteNext();


    $('#nxtbk').click(function (e) {

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
                        obj.setValue('<?= $brtRoute->airport ?>');
                        var pac = PACObject.getObject('<?= CHtml::activeId($brtRoute, 'place') ?>');
                        pac.setValue('<?= $brtRoute->place ?>', true);
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
                obj.setValue('<?= $brtRoute->airport ?>');
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

</script>