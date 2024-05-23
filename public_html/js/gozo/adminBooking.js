/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var AdminBooking = function ()
{
	this.model = {};
	var $fireChange = true;
	var $addonRouteRates = [];
	var elemNoOfPerson = "";
	var venInstructionArr = [
		{key: "1", label: "Senior Citizen Travelling", selected: true, charges: 0},
		{key: "2", label: "Kids on board", selected: true, charges: 0},
		{key: "3", label: "Women traveling", selected: true, charges: 0},
		{key: "4", label: "Require vehicle with Carrier", selected: true, charges: 150},
		{key: "5", label: "Require hindi speaking driver", selected: true, charges: 0},
		{key: "6", label: "Require english speaking driver", selected: true, charges: 0},
	];
	this.elmUserCountryCodeAdd = '#BookingUser_bkg_country_code2';
	this.elmUserCountryCode = '#BookingUser_bkg_country_code';
	this.elmUserEmail = '#BookingUser_bkg_user_email';
	this.elmUserEmail1 = '#BookingUser_bkg_user_email1';
	this.elmUserFname = '#BookingUser_bkg_user_fname';
	this.elmUserFname1 = '#BookingUser_bkg_user_fname1';
	this.elmUserLname = '#BookingUser_bkg_user_lname';
	this.elmUserLname1 = '#BookingUser_bkg_user_lname1';
	this.elmFullContactNoAdd = '#fullContactNumber2';
	this.elmContactNoAdd = '#BookingUser_bkg_contact_no2';
	this.elmContactNo = '#BookingUser_bkg_contact_no';
	this.elmUserId = '#BookingUser_bkg_user_id';
        this.elmContactId = '#BookingUser_bkg_contact_id';
	this.elmReturnDate = "#Booking_bkg_return_date_date";
	this.elmReturnTime = "#Booking_bkg_return_date_time";
	this.elmRoute = "#Booking_bkg_route";
	this.elmMulticityjsondata = ".box-multicityjson";
	this.elmFromCityId = "#Booking_bkg_from_city_id";
	this.elmToCityId = "#Booking_bkg_to_city_id";
	this.elmPickUpAdd = "#Booking_bkg_pickup_address";
	this.elmDropAdd = "#Booking_bkg_drop_address";
	this.elmPickupDate = "#Booking_bkg_pickup_date_date";
	this.elmPickupTime = "#Booking_bkg_pickup_date_time";
	this.elmVehicleTypeId = "#Booking_bkg_vehicle_type_id";
	this.elmServiceClassId = 'input[name=serviceClass]:checked';
	this.elmTripDistance = '#Booking_bkg_trip_distance,#tot_est_dist';
	this.elmTripDuration = '#Booking_bkg_trip_duration,#tot_est_dur';
	this.elmbaseamount = '#Booking_baseamount';

	this.elmSurgeDiffAmt = "#bkg_surge_differentiate_amount";
	this.elmNightPickupInc = "#BookingInvoice_bkg_night_pickup_included";
	this.elmNightDropInc = "#BookingInvoice_bkg_night_drop_included";
	this.elmBaseAmt = "#BookingInvoice_bkg_base_amount";
	this.elmCancelAddonCharge = "#BookingInvoice_bkg_addon_charges";
	this.elmAgtAddonCharge = "#bkg_addon_charges_standard";
	this.elmTollTax = "#BookingInvoice_bkg_toll_tax";
	this.elmStateTax = "#BookingInvoice_bkg_state_tax";
	this.elmRatePreExtraKm = "#BookingInvoice_bkg_rate_per_km_extra";
	this.elmRatePerExtraMin = "#BookingInvoice_bkg_extra_per_min_charge";
	this.elmTotalAmt = "#BookingInvoice_bkg_total_amount";
	this.elmGozoBaseAmt = '#BookingInvoice_bkg_gozo_base_amount';
	this.elmParkingCharge = "#BookingInvoice_bkg_parking_charge";
	this.elmParkingInc = '#Booking_bkg_is_parking_included';
	this.elmServiceTax = '#BookingInvoice_bkg_service_tax';
	this.elmDriverAllowanceAmt = '#BookingInvoice_bkg_driver_allowance_amount';
	this.elmTollTaxInc = '#BookingInvoice_bkg_is_toll_tax_included';
	this.elmTollTaxAdd = '#Booking_bkg_is_toll_tax_included1';
	this.elmStateTaxInc = '#BookingInvoice_bkg_is_state_tax_included';
	this.elmStateTaxAdd = '#Booking_bkg_is_state_tax_included1';
	this.elmNightPickupAdd = '#Booking_bkg_night_pickup_included1';
	this.elmNightDropAdd = '#Booking_bkg_night_drop_included1';
	this.elmRatePerKm = '#BookingInvoice_bkg_rate_per_km';
	this.elmChargeableDis = '#BookingInvoice_bkg_chargeable_distance';
	this.elmGarageTime = '#BookingTrack_bkg_garage_time';
	this.elmQuotedVenAmt = '#BookingInvoice_bkg_quoted_vendor_amount';
	this.elmAdditionalCharge = '#BookingInvoice_bkg_additional_charge';
	this.elmSplLunchbrk = '#BookingAddInfo_bkg_spl_req_lunch_break_time';
	this.elmSplCarrierReq = '#BookingAddInfo_bkg_spl_req_carrier';
	this.elmDiscountAmt = '#BookingInvoice_bkg_discount_amount';
	this.elmServiceTaxRate = '#BookingInvoice_bkg_service_tax_rate';
	this.elmConvenienceCharge = '#BookingInvoice_bkg_convenience_charge';
	this.elmAddChargeRemark = '#BookingInvoice_bkg_additional_charge_remark';
	this.elmVndAmt = '#BookingInvoice_bkg_vendor_amount';
	this.elmamountwithoutcod = '#amountwithoutcod';
	this.elmAgtCreditAmt = '#Booking_agentCreditAmount';
	this.elmAgtType = "#agt_type";
	this.elmAgtCommVal = '#agt_commission_value';
	this.elmAgtComm = '#agt_commission';
	this.elmPromo1Code = "#BookingInvoice_bkg_promo1_code";
	this.elmOldPromoCode = "#oldPromoCode";
	this.elmAgtId = "#bkg_agent_id";
	this.elmAgtApproved = "#agt_approved_untill_date";
	this.elmAgtManagers = "#arl_operating_managers";
	this.elmBlockAutoAssign = '#BookingPref_bkg_block_autoassignment';
	this.elmAgtPaidCust = '#agt_paid_custom';
	this.elmPayAgtName = '#pay_agt_name';
	this.elmCopyBkgName = '#Booking_bkg_copybooking_name';
	this.elmCopyBkgEmail = '#Booking_bkg_copybooking_email';
	this.elmCopyBkgPh = '#Booking_bkg_copybooking_phone';
	this.elmCopyBkgCountry = "#Booking_bkg_copybooking_country";
	this.elmFromCtyIdAdd = '#Booking_bkg_from_city_id1';
	this.elmToCtyIdAdd = '#Booking_bkg_to_city_id1';
	this.elmLocFollowupDate = '#BookingTrail_locale_followup_date';
	this.elmSplReqOther = '#BookingAddInfo_bkg_spl_req_other';
	this.elmNoPerson = '#BookingAddInfo_bkg_no_person';
	this.elmNoLargebag = '#BookingAddInfo_bkg_num_large_bag';
	this.elmNoSmallBag = '#BookingAddInfo_bkg_num_small_bag';
	this.elmLocFollowupTime = '#BookingTrail_locale_followup_time';
	this.elmModelId = '#Booking_bkg_vht_id';
	this.elmIsFbgType = '#bkg_is_fbg_type';
	this.elmAddonId = '#BookingInvoice_bkg_addon_ids';
	this.elmAirportFee = '#BookingInvoice_bkg_airport_entry_fee';

    this.emailphone = '#emailphone';

	this.showlinkedUser = function()
	{
		
		var model = {};
		var self = this;

        $('#bkErrors').addClass('hide');
      
        var emailphone ='';
        emailphone = $(this.emailphone).val();
        model.emailphone = emailphone;
        
    
        if ((emailphone != '' && emailphone != null && emailphone != undefined))
        {
            $.ajax({
                "type": "GET",
                "dataType": "json",
                "url": $baseUrl + "/admpnl/user/linkedusers",
                "async": false,
                data: model,
                "success": function (data)
                {
                    var emailphoneType = data.typeEmlPh;
                    if (data.success)
                    {
                        var htmlDiv = data.userInfoHtml;
                        $('#linkedusers').html(htmlDiv);
                        var userCount = data.userCount;
                        if (userCount > 0)
                        {
                            $("#spnLinkUser0").click();
                        }
                        $('#custonerInformation').removeClass('hide');
					}
					else
                    {
                       
                        $('#linkedusers').html('');
                        $('#custonerInformation').addClass('hide');
                        $(self.elmContactNo).val('');
						$(self.elmUserEmail).val('');
                        if (emailphoneType === 2)
                        {
                            $(self.elmContactNo).val(emailphone);
                        }
                        if (emailphoneType === 1)
                        {
                            $(self.elmUserEmail).val(emailphone);
                        }
                        $(self.elmUserFname).val('');
                        $(self.elmUserLname).val('');
                      //  $(self.elmUserLname1).val('');
                        $(self.elmUserId).val('');
                        if (data.error == "[]")
                        {
                            $('#customerPhoneDetailsForm').submit();
						}
						else
                        {
                            var errors = JSON.parse(data.error);
                            $.each(errors, function (k, v)
                            {
                                self.showErrors(v, self.elmFullContactNoAdd);
                            });
                        }
                    }
                }
            });
		}
		else
        {
            this.showErrors("Please Provide Customer's Contact Number", this.elmFullContactNoAdd);
            $('#linkedusers').html('');
        }
    };

	this.showUserDet = function(user)
    {
		if (user > 0)
		{
			jQuery.ajax({
				type: 'GET',
				url: $baseUrl + '/admpnl/user/details',
				dataType: 'html',
				data: {"user": user},
				success: function(data)
				{
					showuser = bootbox.dialog({
						message: data,
						title: 'User Details',
						size: 'large', onEscape: function()
						{
						}
					});
					showuser.on('hidden.bs.modal', function(e)
					{
						$('body').addClass('modal-open');
					});
					return true;
				},
				error: function(x)
				{
					alert(x);
				}
			});
		}
	};

	this.showUserView = function(user)
    {
		if (user > 0)
		{
			jQuery.ajax({
				type: 'GET',
				url: $baseUrl + '/admpnl/user/view',
				dataType: 'html',
				data: {"id": user},
				success: function(data)
				{
					showuser = bootbox.dialog({
						message: data,
						title: 'User Details',
						size: 'large', onEscape: function()
						{
						}
					});
					showuser.on('hidden.bs.modal', function(e)
					{
						$('body').addClass('modal-open');
					});
					return true;
				},
				error: function(x)
				{
					alert(x);
				}
			});
		}
	};

    this.linkUser = function (obj, userId)
    {
        if ($(obj).hasClass('bg-warning'))
        {
            $('.linkuserbtn').removeClass('bg-success');
            $('.linkuserbtn').addClass('bg-warning');
            $(this.elmContactId).val(userId);
            $(obj).removeClass('bg-warning');
            $(obj).addClass('bg-success');
            var chngEmail = $(obj).attr('email');
            var chngPhone = $(obj).attr('phone');
            var chngCode = $(obj).attr('code');
            var chngFname = $(obj).attr('fname');
            var chngLname = $(obj).attr('lname');
            var phone = $(this.elmContactNoAdd).val();
            if (chngPhone != '' && chngPhone != null && chngPhone != undefined && chngPhone != "null" && phone != "")
            {
				$(this.elmUserCountryCode).val(chngCode);
                $(this.elmContactNo).val(chngPhone);
                $(this.elmContactNoAdd).val(chngPhone);
                $(this.elmFullContactNoAdd).val(chngPhone);
            }
            if (chngEmail != '' && chngEmail != null && chngEmail != undefined)
            {
                $(this.elmUserEmail).val(chngEmail);
            }
            if (chngFname != '' && chngFname != null && chngFname != undefined)
            {
                $(this.elmUserFname).val(chngFname);
            }
            if (chngLname != '' && chngLname != null && chngLname != undefined)
            {
                $(this.elmUserLname).val(chngLname);
            }
		}
		else
        {
			$(this.elmContactId).val("");
			$(this.elmUserCountryCode).val("");
			$(this.elmContactNo).val("");
            $(this.elmUserId).val('');
            $(obj).removeClass('bg-success');
            $(obj).addClass('bg-warning');
        }
    };

	this.bookingTypeDetails = function($bkgtype, $isLeadLoad)
	{
		var self = this;
		$('#addmulticities').hide();
		$('#ctyinfo_bkg_type_1').hide();
		$("#ctyinfo_bkg_type_2").html('');
		$("#ctyinfo_bkg_type_2").addClass('hide');
		$('#address_div').hide();
		if ($bkgtype == '1' || $bkgtype == '9' || $bkgtype == '10' || $bkgtype == '11')
		{
			$isLeadLoad = false;
			$(this.elmReturnDate).val('');
			$(this.elmReturnTime).val('');
			$(this.elmRoute).removeAttr('disabled');
			$('#ctyinfo_bkg_type_1').show();
			$('#addmulticities').hide();
			// $('.multicitydetrow').remove();
			$('#tripTablecreate').hide();
			$('#pickup_div').show();
			$('#address_div').show();
			$(this.elmMulticityjsondata).val('');
			$('.multicitydetrow').remove();
		}
		else if ($bkgtype == '2' || $bkgtype == '3' || $bkgtype == '8')
		{
			if ($isLeadLoad)
			{
				$isLeadLoad = false;
				return;
			}
			$('#pickup_div').hide();
			$(this.elmRoute).attr('disabled', 'disabled');
			$href = $baseUrl + '/admpnl/booking/multicityform?bookingType=' + $bkgtype;
			jQuery.ajax({type: 'GET', url: $href, async: false,
				success: function(data)
				{
					multicitybootbox = bootbox.dialog({
						message: data,
						size: 'large',
						title: 'Add pickup info',

					});
					multicitybootbox.on('hidden.bs.modal', function(e)
					{
						$('body').addClass('modal-open');
					});
				}});
		}
		else if ($bkgtype == '4')
		{
			$href = $baseUrl + '/admpnl/booking/airportTransfer';
			jQuery.ajax({type: 'GET', dataType: "HTML", url: $href, async: false,
				success: function(data)
				{
					$("#ctyinfo_bkg_type_2").html(data);
					$("#ctyinfo_bkg_type_2").removeClass('hide');
				}});
		}
		else if ($bkgtype == '15')
		{	
			$href = $baseUrl + '/admpnl/booking/railwayBusTransfer';
			jQuery.ajax({type: 'GET', dataType: "HTML", url: $href, async: false,
				success: function(data)
				{
					$("#ctyinfo_bkg_type_2").html(data);
					$("#ctyinfo_bkg_type_2").removeClass('hide');
				}});
		}
		
		// getRoute();
	};

	this.getRoute = function(booking)
	{
		var jsonData = JSON.parse($('#jsonData_routeType').val());
		if (!$fireChange)
		{
			return false;
		}

		var model = {};
		model.fromCity = $(this.elmFromCityId).val();
		model.toCity = $(this.elmToCityId).val();
		model.bookingType = jsonData.bkg_booking_type;
		model.pickupAddress = $(this.elmPickUpAdd).val();
		model.dropAddress = $(this.elmDropAdd).val();
		model.pickupDate = $(this.elmPickupDate).val();
		model.pickupTime = $(this.elmPickupTime).val();
		if (model.bookingType == '9' || model.bookingType == '10' || model.bookingType == '11')
		{
			model.toCity = model.fromCity;
			$(this.elmToCityId).val(model.fromCity);
		}
		if (model.fromCity != '' && model.toCity != '' && model.bookingType != '')
		{
			var preSCity = $("#preSCity").val();
			var preDCity = $("#preDCity").val();
			if ((preSCity != model.fromCity || preDCity != model.toCity) || (model.bookingType == '9' || model.bookingType == '10' || model.bookingType == '11'))
			{
				this.getAutoAddressBox(model.bookingType);
				$("#preSCity").val(model.fromCity);
				$("#preDCity").val(model.toCity);
			}
		}

	};

	this.getAutoAddressBox = function(bkType, block)
	{
		var pickup_city, drop_city, hyperInitialize;
		var isGozonow = 0;
		if (block == 'traveller')
		{
			var jsonData = JSON.parse($('#jsonData_travellerInfo').val());
			isGozonow = jsonData.isGozonow;
			pickup_city = jsonData.bkg_from_city_id;
			drop_city = jsonData.bkg_to_city_id;
		}
		else
		{
			pickup_city = $(this.elmFromCityId).val();
			drop_city = $(this.elmToCityId).val();
			block = 'route';
		}
		var href = $baseUrl + '/admpnl/booking/onewayautoaddress';
		var booking_type = bkType;
		if (booking_type == 1 || booking_type == 9 || booking_type == 10 || booking_type == 11 || booking_type == 8)
		{
			$.ajax({
				url: href, dataType: "HTML",
				data: {"pickup_city": pickup_city, "drop_city": drop_city, "booking_type": booking_type, "isGozonow": isGozonow, "hyperInitialize": block},
				"success": function(data)
				{
					if (block == 'traveller')
					{
						$('#reAddress').html(data);
					}
					else
					{
						$('#address_div').html(data);
					}
				}

			});
		}
	};

	this.quoteJsonData = [];
	this.qouteCallBlock = '';
	this.qouteBkgType = '';
	this.getAmountbyCitiesnVehicle = function(booking, jsonData, block, tripDist = 0)
	{	//debugger;
		var self = this;
		var fromCityId, toCityId, cabType, sccClassType, tripDistance, tripDuration, pickupAdd, dropAdd, pickDate, pickTime, multiCityjson, multiCityArr;
		var cntBrt, cntVal;
		var model = {};
		this.quoteJsonData = jsonData;
		this.qouteCallBlock = block;
		this.qouteBkgType = jsonData.bkg_booking_type;
		$userType = jsonData.trip_user;
		var isGozonow = jsonData.isGozonow | 0;
		var addOnId = jsonData.addonid;
		if ($userType == 2)
		{
			model.agentId = jsonData.bkg_agent_id;
		}
		if (block == "route")
		{
			cntBrt = (jsonData.bkg_booking_type == 4 || jsonData.bkg_booking_type == 15) ? 2 : $(".txtpl" + block).length;
			cntVal = cntBrt - 1;
			fromCityId = $(this.elmFromCityId).val();
			toCityId = $(this.elmToCityId).val();
			cabType = $(this.elmVehicleTypeId).val();
			sccClassType = $(this.elmServiceClassId).val();
			tripDistance = $(this.elmTripDistance).val();
			tripDuration = $(this.elmTripDuration).val();
			pickupAdd = $('.brt_location_0').val();
			dropAdd = $('.brt_location_' + cntVal).val();
			if (jsonData.bkg_booking_type == 4 || jsonData.bkg_booking_type == 15)
			{
				pickupAdd = $('#brt_location0').val();
				dropAdd = $('#brt_location' + cntVal).val();
			}
			pickDate = $(this.elmPickupDate).val();
			pickTime = $(this.elmPickupTime).val();
			multiCityjson = $(this.elmMulticityjsondata).val();
		}
		else
		{
			fromCityId = jsonData.bkg_from_city_id;
			toCityId = jsonData.bkg_to_city_id;
			cabType = jsonData.bkg_vehicle_type_id;
			sccClassType = $(this.elmServiceClassId).val();
			if (tripDist == 1)
			{
				tripDistance = $(this.elmTripDistance).val();
			}
			else
			{
				tripDistance = jsonData.tot_est_dist;
			}
			tripDuration = jsonData.tot_est_dur;
			pickDate = jsonData.bkg_pickup_date_date;
			pickTime = jsonData.bkg_pickup_date_time;
			multiCityjson = JSON.stringify(jsonData.multicityjsondata);
			cntBrt = jsonData.BookingRoute.length;
			cntVal = cntBrt - 1;
			pickupAdd = jsonData.BookingRoute[0].hasOwnProperty('brt_from_location') ? jsonData.BookingRoute[0].brt_from_location : jsonData.multicityjsondata[0].pickup_address;
			dropAdd = jsonData.BookingRoute[cntVal].hasOwnProperty('brt_to_location') ? jsonData.BookingRoute[cntVal].brt_to_location : jsonData.multicityjsondata[0].drop_address;
		}
		routeData = [];
		if (cntBrt > 0)
		{
			var locLatVal = '';
			var locLonVal = '';
			var brtlocationVal = '';
			for (g = 0; g < cntBrt; g++)
			{
				if (block == "route")
				{
					locLatVal = $(".locLat_" + g).val();
					locLonVal = $(".locLon_" + g).val();
					brtlocationVal = $(".brt_location_" + g).val();
					if (jsonData.bkg_booking_type == 4 || jsonData.bkg_booking_type == 15)
					{
						locLatVal = $("#locLat" + g).val();
						locLonVal = $("#locLon" + g).val();
						brtlocationVal = $("#brt_location" + g).val();
					}
				}
				else
				{
					if ((cntBrt - 1) == g)
					{
						locLatVal = jsonData.BookingRoute[g].hasOwnProperty('brt_to_latitude') ? jsonData.BookingRoute[g].brt_to_latitude : "";
						locLonVal = jsonData.BookingRoute[g].hasOwnProperty('brt_to_longitude') ? jsonData.BookingRoute[g].brt_to_longitude : "";
						brtlocationVal = jsonData.BookingRoute[g].hasOwnProperty('brt_to_location') ? jsonData.BookingRoute[g].brt_to_location : "";
					}
					else
					{
						locLatVal = jsonData.BookingRoute[g].hasOwnProperty('brt_from_latitude') ? jsonData.BookingRoute[g].brt_from_latitude : "";
						locLonVal = jsonData.BookingRoute[g].hasOwnProperty('brt_from_longitude') ? jsonData.BookingRoute[g].brt_from_longitude : "";
						brtlocationVal = jsonData.BookingRoute[g].hasOwnProperty('brt_from_location') ? jsonData.BookingRoute[g].brt_from_location : "";
					}
				}
				routeData[g] = {
					"locLatVal": locLatVal,
					"locLonVal": locLonVal,
					"brtLocationVal": brtlocationVal};
			}
		}
			
               var isAirportPickup = 1;
               if($('#BookingTemp_bkg_transfer_type_1').is(":checked"))
               {
                   isAirportPickup = 2;
               }

		if(jsonData.bkg_service_class == 4)
		{
	        	model.modelId = ($(this.elmModelId).val() > 0) ? $(this.elmModelId).val() : jsonData.modelId;
		}
		model.fromCity = fromCityId;
		model.toCity = toCityId;
		model.cabType = cabType;
		model.sccClassType = sccClassType;
		model.tripDistance = tripDistance;
		model.tripDuration = tripDuration;
		model.multiCityData = multiCityjson;
		model.bookingType = jsonData.bkg_booking_type;
		model.pickupAddress = pickupAdd;
		model.dropupAddress = dropAdd;
		model.routeDataArr = routeData;
		model.pickupDate = pickDate;
		model.pickupTime = pickTime;
		model.isGozonow = isGozonow;
		model.minTripDistance = tripDist;
		model.addOnId = addOnId;
		model.sccId = jsonData.bkg_service_class;
		model.tripUser = jsonData.trip_user;
		model.YII_CSRF_TOKEN = $('input[name="YII_CSRF_TOKEN"]').val();
                model.isAirportPickup = isAirportPickup;
		booking.model = model;
		$('.addOnDiv').attr("style", "display:none");
		if (model.fromCity != '' && model.toCity != '' && model.cabType != '')
		{
			$(document).unbind("getQoute").on("getQoute", function(event, data)
			{
				if (self.qouteBkgType != 8)
				{
					self.getQoutation(data, self.quoteJsonData, self.qouteCallBlock);
				}
				else
				{
					self.customQoutation(data);
				}
			});
			booking.getQoute();
	}
	};

	this.customQoutation = function(data)
	{
		var self = this;
		$("#errorShow").hide();
		$("#errorMsg").html('');
		if (data.data.distArr != '')
		{
			var distArrVal = data.data.distArr;
			var multicityjsondata = $.parseJSON($(this.elmMulticityjsondata).val());

			$.each(distArrVal, function(k, v)
			{
				$('#fdistcreate' + k).text(v['dist']);
				$('#distancecreate' + (k + 1)).text(v['dist']);
				$('#fduracreate' + k).text(v['dura']);
				$('#durationcreate' + (k + 1)).text(v['dura']);
				multicityjsondata[k]['distance'] = v['dist'] + "";
				multicityjsondata[k]['duration'] = v['dura'] + "";
				multicityjsondata[k]['pickup_city'] = v['fromCity'] + "";
				multicityjsondata[k]['drop_city'] = v['toCity'] + "";

			});
			$(this.elmMulticityjsondata).val(JSON.stringify(multicityjsondata)).change();
		}

	};

	this.getQoutation = function(data, jsonData, block)
	{	  // debugger;
             if($('#jsonData_payment').length >0){
                let jsonDataTemp = JSON.parse($('#jsonData_payment').val());
                let dataTemp = {"cabNotSupported": 0};
                $.extend(true, jsonDataTemp, dataTemp);
                $('#jsonData_payment').val(JSON.stringify(jsonDataTemp));
                }
		var self = this;
		$('#errorCodeQuote').val(0);
		$("#errorShow").hide();
		$("#errorMsg").html('');
        $(".btn-payment").removeClass("disabled");
		if (data.data.quoteddata.success != true && (data.data.default==false || jsonData.bkg_booking_type==15))
		{
			var elm = '.btn-route';
			if (data.data.quoteddata.errorText.indexOf('Time') > -1)
			{
				this.showErrors(data.data.quoteddata.errorText, elm);
			}
			else
			{
				$("#errorShow").show();
				//$("#errorMsg").html('Error : ' + data.data.quoteddata.errorText + '. (<a href="javascript:void(0)" onclick="admBooking.focusErrorElm(\'' + elm + '\')">Go there</a>)');
				//$(document).scrollTop(0);
                                if(!$(".btn-payment").hasClass("disabled"))
                                {
                                    //  $(".btn-payment").addClass("disabled");
                                }
               		//	return false;                             
                                //toastr.error('Error : ' + data.data.quoteddata.errorText);
                                if (confirm("Cab type not supported, still want to process this booking ?")) 
                                {
                                    if($('#jsonData_payment').length >0)
                                    {

                                        let jsonDataTemp1 = JSON.parse($('#jsonData_payment').val());
                                        let dataTemp1 = {"cabNotSupported": 1};
                                        $.extend(true, jsonDataTemp1, dataTemp1);
                                        $('#jsonData_payment').val(JSON.stringify(jsonDataTemp1));
                                    }
                                    else if(jsonData.bkg_booking_type==15 && (data.data.quoteddata.routeDuration==null || data.data.quoteddata.routeDuration==undefined))
                                    {
                                        
                                        $("#errorMsg").html('Error : Price is not added for this booking, ' + data.data.quoteddata.errorText);
                                         if(!$(".btn-route").hasClass("disabled"))
                                         {
                                             $(".btn-route").addClass("disabled");
                                         }
                                        $(document).scrollTop(0);
                                        return false;
                                    }
                                } 
                                else {
                                    txt = "You pressed Cancel!";
                                }
                               
			}
			if (data.data.quoteddata.errorCode == 107)
			{
				$('#errorCodeQuote').val(107);
				return false;
			}

		}
		$addonRouteRates = data.data.routeRatesArr;
		var addonId = (jsonData.bkg_addon_ids > 0) ? jsonData.bkg_addon_ids : 0;
		var sccId = data.data.sccId;
		if (sccId > 0 && block != "route")
		{
			var jsonData = JSON.parse($('#jsonData_payment').val());
			var sccData = {"bkg_service_class": sccId};
			$.extend(true, jsonData, sccData);
			$('#jsonData_payment').val(JSON.stringify(jsonData));
		}


		this.updatePriceByRouteRates($addonRouteRates[addonId], addonId);
		this.updateOthersByQuoteData(data.data, jsonData);
		if ($('#jsonData_travellerInfo').val() == "" || $('#jsonData_travellerInfo').val() == undefined || $('#jsonData_travellerInfo').val() == null || $('#jsonData_travellerInfo').val() == 'undefined')
		{
			//this.generateTierBoxes(data.data.newQuoteArr, sccId);
			if (data.data.applicableAddons)
			{
				this.generateAddonBoxes(data.data.applicableAddons, addonId);
			}
			if(data.data.cpAddons)
			{
				this.generateCPAddonBoxes(data.data.cpAddons, 0);
			}
			if(data.data.cmAddons)
			{
				this.generateCMAddonBoxes(data.data.cmAddons,0);
			}			
			$('.carModel').addClass('hide');
			if (sccId == 5 || sccId == 4)
			{
				if (jsonData.modelId > 0)
				{
					//do if car model selected	
				}
				else
				{
					this.generateCarModels(data.data);
				}
				$('.carModel').removeClass('hide');
			}
		}
	};

	var addToMyTripFixedMin = 30;
	var addToMyTripFixedAmount = 150;

	this.calculateAmount = function(jsonData)
	{	
		var self = this;
		var addToMyTripForVendor = 0;
		var addToMyTripInMin = 0;
		var addToMyTrip = 0;
		var additionalRemark = "";
		var additional;
		var gross_amount = Math.round($(self.elmBaseAmt).val());
		var trip_user = jsonData.trip_user;
		gross_amount = (gross_amount == '') ? 0 : parseInt(gross_amount);
		if ($(self.elmAdditionalCharge).val() == '0' && $(self.elmAddChargeRemark).val() == '')
		{
			additional = Math.round($(self.elmAdditionalCharge).val() + ($(self.elmSplCarrierReq).is(':checked') == true ? 150 : 0));
			addToMyTripInMin = ($(self.elmSplLunchbrk).val() | 0), addToMyTrip;
			addToMyTrip = addToMyTripFixedAmount * (addToMyTripInMin / addToMyTripFixedMin);
			addToMyTripForVendor = addToMyTrip != '0' ? (addToMyTrip * 60) / 100 : 0;
		}
		else
		{
			additional = Math.round($(self.elmAdditionalCharge).val());
		}

		if(jsonData.hasOwnProperty('bkg_addon_details'))
		{
			addonCPcharge = (typeof(jsonData.bkg_addon_details.type1) != 'undefined')? jsonData.bkg_addon_details.type1.adn_value:0;
			addonCMcharge = (typeof(jsonData.bkg_addon_details.type2) != 'undefined')? jsonData.bkg_addon_details.type2.adn_value:0;
			addonCharge = parseInt(addonCPcharge) + parseInt(addonCMcharge);  
			$(self.elmCancelAddonCharge).val(addonCharge);
		}
	
		var addonCharge = $(self.elmCancelAddonCharge).val() | 0;
		var rateVendorAmount = Math.round($('#rtevndamt').val());
		var vendor_amount = Math.round(rateVendorAmount + (addToMyTripInMin != '0' ? addToMyTripForVendor : 0) + additional);
		additional = Math.round(additional + addToMyTrip);
		var discount_amount = Math.round($(self.elmDiscountAmt).val());
		var driver_allowance = 0;
		var parking_charge = 0;
		var gozo_base_amount = Math.round($(self.elmGozoBaseAmt).val());
		gross_amount = Math.round(gross_amount + additional + addonCharge);
		discount_amount = (discount_amount == '') ? 0 : parseInt(discount_amount);
		gross_amount = gross_amount - discount_amount;
		if ($(self.elmDriverAllowanceAmt).val() != '' && $(self.elmDriverAllowanceAmt).val() > 0)
		{
			driver_allowance = parseInt($(self.elmDriverAllowanceAmt).val());
		}
		if ($(self.elmParkingCharge).val() != '' && $(self.elmParkingCharge).val() > 0)
		{
			parking_charge = parseInt($(self.elmParkingCharge).val());
		}

		var conFee1 = gross_amount * 0.05;
		var conFee2 = 249;
		var conFee = 0;
		if (conFee1 > conFee2)
		{
			conFee = conFee2;
		}
		else
		{
			conFee = conFee1;
		}

		if ((trip_user == 2) && jsonData.bkg_agent_id != '' && jsonData.bkg_agent_id != null && jsonData.bkg_agent_id != undefined && jsonData.bkg_agent_id != '0' && jsonData.bkg_agent_id != 0)
		{
			if (jsonData.agt_type == 1)
			{
				conFee = 0;
				$('#agtnotification').removeClass('hide');
			}
			else
			{
				conFee = 0;
				$('#divpaidby').removeClass('hide');
				$('#agtnotification').removeClass('hide');
				self.showAgentCreditDiv(jsonData.agentBkgAmountPay);
			}
		}

		conFee = 0; // Convenience Charges to be charged at confirm as cash

		var convenience_charge = Math.round(conFee);
		var tollTaxVal = ($(self.elmTollTax).val() == '') ? 0 : parseInt($(self.elmTollTax).val());
		var stateTaxVal = ($(self.elmStateTax).val() == '') ? 0 : parseInt($(self.elmStateTax).val());
		var service_tax_rate = ($(self.elmServiceTaxRate).val() == '') ? 0 : $(self.elmServiceTaxRate).val();
		var airportEntryCharge = ($('#BookingInvoice_bkg_airport_entry_fee').val() == '') ? 0 : parseInt($('#BookingInvoice_bkg_airport_entry_fee').val());
		var service_tax_amount = 0;

		var pickDate1 = $(self.elmPickupDate).val();
		var [day, month, year] = pickDate1.split('/');
		var pickDate_1 = [year, month, day].join('-');
		var pickDate2 = '2022-04-01';
		var diffInDays = moment(pickDate2).diff(moment(pickDate_1), 'days');
		if (service_tax_rate != 0 && diffInDays <= 0)
		{
			service_tax_amount = Math.round(((gross_amount + tollTaxVal + stateTaxVal + driver_allowance + parking_charge + airportEntryCharge) * parseFloat(service_tax_rate) / 100));
		}
		else
		{
			service_tax_amount = Math.round(((gross_amount + driver_allowance) * parseFloat(service_tax_rate) / 100));
		}
//        if (service_tax_rate != 0)
//        {
//            service_tax_amount = Math.round(((gross_amount + driver_allowance) * parseFloat(service_tax_rate) / 100));
//        }
		var amountwithoutconvenienc = gross_amount + service_tax_amount + tollTaxVal + stateTaxVal + driver_allowance + parking_charge + airportEntryCharge;
		$(self.elmamountwithoutcod).val(amountwithoutconvenienc);
		gross_amount = gross_amount + convenience_charge;
		$(self.elmConvenienceCharge).val(convenience_charge);
		service_tax_amount = 0;

		if (service_tax_rate != 0 && diffInDays <= 0)
		{
			service_tax_amount = Math.round(((gross_amount + tollTaxVal + stateTaxVal + driver_allowance + parking_charge + airportEntryCharge) * parseFloat(service_tax_rate) / 100));
		}
		else
		{
			service_tax_amount = Math.round(((gross_amount + driver_allowance) * parseFloat(service_tax_rate) / 100));
		}

//        if (service_tax_rate != 0)
//        {
//            service_tax_amount = Math.round(((gross_amount + driver_allowance) * parseFloat(service_tax_rate) / 100));
//        }
		var net_amount = gross_amount + service_tax_amount;
		var net_amount = net_amount + tollTaxVal + stateTaxVal + driver_allowance + parking_charge + airportEntryCharge;
		$(self.elmAdditionalCharge).val(additional);
		additionalRemark = $(self.elmSplCarrierReq).is(':checked') == true ? "Customer will pay Rs.150/- carrier charges" : '';
		if ($(self.elmSplLunchbrk).val() != '0' && $(self.elmSplLunchbrk).val() != undefined)
		{
			additionalRemark += additionalRemark == '' ? "Customer will pay " + $(self.elmSplLunchbrk).val() + ' minutes Journey Break' : ", Customer will pay " + $(self.elmSplLunchbrk).val() + ' minutes Journey Break';
		}
		$(self.elmAddChargeRemark).val(additionalRemark);
		$(self.elmTotalAmt).val(net_amount);
		$(self.elmVndAmt).val(vendor_amount);
		$(self.elmServiceTax).val(service_tax_amount);
		if ((trip_user == 2) && jsonData.agt_type != 1 && jsonData.bkg_agent_id != '' && jsonData.bkg_agent_id != null && jsonData.bkg_agent_id != undefined && jsonData.bkg_agent_id != '0' && jsonData.bkg_agent_id != 0)
		{
			$(self.elmAgtCreditAmt).attr('max', net_amount);
			var corpCredit = Math.round($(self.elmAgtCreditAmt).val());
			corpCredit = (corpCredit == '') ? 0 : parseInt(corpCredit);
			var due_amt = parseInt(net_amount) - corpCredit;
			$('#id_due_amount').html(due_amt);
		}
		else
		{
			$(self.elmAgtCreditAmt).val('');
			$('#div_due_amount').addClass('hide');
			$('#id_due_amount').html(net_amount);
		}

	};


	this.getAgentBaseDiscFare = function(jsonData)
	{
		var base_fare = Math.round($(this.elmGozoBaseAmt).val());
		var trip_user = jsonData.trip_user;
		var agt_type = jsonData.agt_type;
		var agt_commisssion_value = jsonData.agt_commission_value;
		var agt_commission = jsonData.agt_commission;
		if (base_fare != '' && base_fare != null && base_fare != undefined && base_fare != 0 && base_fare != '0')
		{
			if (
					(agt_commisssion_value != '' && agt_commisssion_value != null && agt_commisssion_value != undefined && agt_commisssion_value != "null") &&
					(agt_commission != '' && agt_commission != null && agt_commission != undefined && agt_commission != "null") &&
					(trip_user == 2 && agt_type != 2 && agt_type != '' && (jsonData.bkg_agent_id != '' && jsonData.bkg_agent_id != null && jsonData.bkg_agent_id != undefined && jsonData.bkg_agent_id != '0' && jsonData.bkg_agent_id != 0))
					)

			{
				agt_commisssion_value = parseInt(Math.round(agt_commisssion_value));
				var totalAmount = Math.round($(this.elmTotalAmt).val());
				totalAmount = (totalAmount == '') ? 0 : parseInt(totalAmount);
				var vendorAmount = Math.round($(this.elmVndAmt).val());
				vendorAmount = (vendorAmount == '') ? 0 : parseInt(vendorAmount);
				var gozo_amount = totalAmount - vendorAmount;
				if (agt_commisssion_value == 1)
				{
					var agentMarkup = Math.round(base_fare * (agt_commission / 100));
				}
				else
				{
					var agentMarkup = agt_commission;
				}
				if (agentMarkup > gozo_amount)
				{
					base_fare = base_fare - gozo_amount;
				}
				else
				{
					base_fare = base_fare - Math.round(agentMarkup);
				}
				$(this.elmBaseAmt).val(base_fare);
			}
			else
			{
				$(this.elmBaseAmt).val(base_fare);
			}
		}
	};

	this.showAgentCreditDiv = function(agentPaymentBy)
	{
		if (agentPaymentBy == 1)
		{
			$('#divAgentCredit').addClass('hide');
			$('#div_due_amount').addClass('hide');
			$(this.elmAgtCreditAmt).val("");
			$('#partPrefdiv').hide();
			$('#partPrefdiv2').hide();
		}
		if (agentPaymentBy == 2)
		{
			$('#divAgentCredit').removeClass('hide');
			$('#div_due_amount').removeClass('hide');
			$('#partPrefdiv').show();
			$('#partPrefdiv2').show();
		}
	};

	this.selctRoute = function(city)
	{
		var model = {};
		model.routeId = $(this.elmRoute).val();
		if (model.routeId == "")
		{
			return;
		}
		city.model = model;
		city.getRouteListbyCities();
	};

	this.routeCitiesList = function(data)
	{

		$fireChange = false;
		$(this.elmFromCityId).val(data.data.fcity).change();
		$fireChange = true;
		$(this.elmToCityId).val(data.data.tcity).change();
	};

	this.getDateobj = function(pdpdate, ptptime)
	{
		var date = pdpdate;
		var time = ptptime;
		var dateObj = "";
		if (time != "")
		{
			var timeArr = time.split(" ");
			var mer = timeArr[1];
			var temp = timeArr[0].split(":");
			var hour = Number(temp[0]);
			var min = Number(temp[1]);
			if (mer == "PM")
			{
				if (hour != 12)
				{
					hour = 12 + hour;
				}
			}
			else if (hour == 12)
			{
				hour = 0;
			}
		}
		//  var currDateTime = new Date();
		if (date != "")
		{
			var dateArr = date.split("/");
			dateObj = new Date(Number(dateArr[2]), Number(dateArr[1]) - 1, Number(dateArr[0]), hour, min, 0);
		}
		return dateObj;
	};

	this.updateMulticity = function(data, tot, jsonData, hyperModel, block)
	{
		var self = this;
		var booking_type = jsonData.bkg_booking_type;
		transfer_type = 0;
		var routetot = (tot);
		var data = $.parseJSON(data);
		if (block == 'route')
		{
			$('#tripTablecreate').show();
			$('#insertTripRowcreate').html('');
			$('.multicitydetrow').remove();
			$('#address_div').hide();
			$(this.elmPickupDate).val(data[0].pickup_date);
			$(this.elmPickupTime).val(data[0].pickup_time);
			$(this.elmFromCityId).val(data[0].pickup_city);
			$(this.elmToCityId).val(data[tot].drop_city);
			$(this.elmMulticityjsondata).val(JSON.stringify(data));
			$("#ctyinfo_bkg_type_1").hide();
			$('#show_return_date_time').html("");
			if (booking_type == 2 || booking_type == 3 || booking_type == 8)
			{
				$(this.elmReturnTime).val(data[tot].return_time);
				$(this.elmReturnDate).val(data[tot].return_date);
			}
			var total_distance = 0;
			var total_duration = 0;
			for (var i = 1; i <= tot + 1; i++)
			{
				$('#insertTripRowcreate').before('<tr class="multicitydetrow">' +
						'<td id="fcitycreate0"></td>' +
						'<td id="tcitycreate0"> </td>' +
						'<td id="fdatecreate0"> </td>' +
						'<td id="distancecreate0"> </td>' +
						'<td id="durationcreate0"> </td>' +
						'<td id="noOfDayscreate0"> </td>' +
						'</tr>');
				$('#fcitycreate0').attr('id', 'fcitycreate' + i);
				$('#tcitycreate0').attr('id', 'tcitycreate' + i);
				$('#fdatecreate0').attr('id', 'fdatecreate' + i);
				$('#distancecreate0').attr('id', 'distancecreate' + i);
				$('#durationcreate0').attr('id', 'durationcreate' + i);
				$('#noOfDayscreate0').attr('id', 'noOfDayscreate' + i);
				$('#noOfDayscreate' + i).text('1');
				total_distance = (total_distance + parseInt(data[(i - 1)].distance));
				total_duration = (total_duration + parseInt(data[(i - 1)].duration));
				$('#noOfDayscreate' + i).text(data[(i - 1)].day);
				$('#totdayscreate').text(data[(i - 1)].totday);
				$('#fcitycreate' + i).html('<b>' + data[(i - 1)].pickup_city_name + '</b>');
				$('#tcitycreate' + i).html('<b>' + data[(i - 1)].drop_city_name + '</b>');
				$('#fdatecreate' + i).text(data[(i - 1)].pickup_date + " " + data[(i - 1)].pickup_time);
				$('#distancecreate' + i).text(data[(i - 1)].distance);
				$('#durationcreate' + i).text(data[(i - 1)].duration);
			}
			$(this.elmTripDistance).val(total_distance);
			$(this.elmTripDuration).val(total_duration);
		}
		var displayRoute = (block == 'route' && booking_type != 8) ? 'none' : '';
		var addresshtml = "";
		b = 0;
		var bounds = [];
		var ctyLat = [];
		var ctyLong = [];
		var isCtyAirport = [];
		var isCtyPoi = [];
		var model = {};
		var mapBound;
		var hyperTxt = 'txtpl' + block
		for (var j = 0; j <= routetot; j++)
		{
			picaddress = '';
			dropaddress = '';
			if (data[j].pickup_cty_is_poi == 1 || data[j].pickup_cty_is_airport == 1)
			{
				picaddress = data[j].pickup_cty_loc;
			}
			if (data[j].drop_cty_is_poi == 1 || data[j].drop_cty_is_airport == 1)
			{
				dropaddress = data[j].drop_cty_loc;
			}

			b = j + 1;
			var pickBounds = self.getCityBounds(data[j].pickup_cty_bounds, data[j].pickup_cty_lat, data[j].pickup_cty_long);
			var pickup_cty_ne_lat = pickBounds.ne_lat;
			var pickup_cty_ne_long = pickBounds.ne_long;
			var pickup_cty_sw_lat = pickBounds.sw_lat;
			var pickup_cty_sw_long = pickBounds.sw_long;
			var dropBounds = self.getCityBounds(data[j].drop_cty_bounds, data[j].drop_cty_lat, data[j].drop_cty_long);
			var drop_cty_ne_lat = dropBounds.ne_lat;
			var drop_cty_ne_long = dropBounds.ne_long;
			var drop_cty_sw_lat = dropBounds.sw_lat;
			var drop_cty_sw_long = dropBounds.sw_long;
			var plcTxt = 'Optional';

			if (j == routetot && booking_type != 8 && block != 'route')
			{
				plcTxt = 'Required';
			}
			var plcTxt1 = (block == 'route') ? 'Optional' : 'Required';
			if (j == 0)
			{
                                var isHideMapAirport = (data[j].pickup_cty_is_airport == 1)?"hide":"";
				var airPickLat = (data[j].pickup_cty_is_airport == 1) ? data[j].pickup_cty_lat : "";
				var airPickLong = (data[j].pickup_cty_is_airport == 1) ? data[j].pickup_cty_long : "";
				var airPickFormAdd = (data[j].pickup_cty_is_airport == 1) ? data[j].pickup_city_name : "";
				bounds[j] = JSON.stringify(data[j].pickup_cty_bounds);
				ctyLat[j] = data[j].pickup_cty_lat;
				ctyLong[j] = data[j].pickup_cty_long;
				isCtyAirport[j] = data[j].pickup_cty_is_airport;
				isCtyPoi[j] = data[j].pickup_cty_is_poi;
				mapBound = JSON.stringify({"ctyLat": ctyLat[j], "ctyLon": ctyLong[j], "bound": bounds[j], "isAirport": isCtyAirport[j], "isCtyPoi": isCtyPoi[j]});
				addresshtml += `<div class="col-xs-12 pb10"><div class="row "><div class="col-xs-12 col-sm-6 pl0 ">
							<label style ="display:${displayRoute}" for="pickup_address${j}" class="control-label text-left">Pickup Address for ${data[j].pickup_city_name}:</label>
							<input type="hidden" id="ctyLat${j}" value="${data[j].pickup_cty_lat}">
							<input type="hidden" id="ctyLon${j}" value="${data[j].pickup_cty_long}">
							<input type="hidden" id="ctyELat${j}" value="${pickup_cty_ne_lat}">
							<input type="hidden" id="ctyWLat${j}" value="${pickup_cty_sw_lat}">
							<input type="hidden" id="ctyELng${j}" value="${pickup_cty_ne_long}">
							<input type="hidden" id="ctyWLng${j}" value="${pickup_cty_sw_long}">
							<input type="hidden" id="ctyRad${j}" value="${data[j].pickup_cty_radius}">
							<input name="BookingRoute[${j}][brt_from_latitude]" class="locLatVal locLat_${j}" type="hidden" value="${airPickLat}">
							<input name="BookingRoute[${j}][brt_from_longitude]"  class="locLonVal locLon_${j}"  type="hidden" value="${airPickLong}">
                                                        <input name="BookingRoute[${j}][brt_from_place_id]"  class="locLonVal locPlaceid_${j}"  type="hidden" value="${data[j].pickup_cty_place_id}">
                                                        <input name="BookingRoute[${j}][brt_from_formatted_address]"  class="locLonVal locFAdd_${j}"  type="hidden" value="${airPickFormAdd}">
							<input id="city_is_airport${j}" name="BookingRoute[${j}][brt_from_city_is_airport]" type="hidden"  value="${data[j].pickup_cty_is_airport}">
							<input id="city_is_poi${j}" name="BookingRoute[${j}][brt_from_city_is_poi]" type="hidden"  value="${data[j].pickup_cty_is_poi}">
                                                        <input class="brt_location_${j} cpy_loc_${j}" type="hidden"  value="">
                                                        <input type="hidden" class="mapBound_${j}" value='${mapBound}'>
						</div>
						<div style ="display:${displayRoute}" class="col-xs-12 col-sm-5 mb0 pb0">
                                                <div class="row">
                                                    <div class="col-xs-10">
                                                    <div class="form-group">`;
				var textareaHtml = `<textarea  id="loc${block}_${j}" class="form-control brt_location_${j} route-focus ${hyperTxt}" placeholder="Pickup Address  (${plcTxt1})" name="BookingRoute[${j}][brt_from_location]" autocomplete="off" onblur="hyperModel.clearAddress(this)">${picaddress}</textarea>`;
				if((data[j].pickup_cty_is_airport == 1)){
                                    textareaHtml = `<input type="hidden"  id="loc${block}_${j}" class="form-control brt_location_${j} route-focus ${hyperTxt}" placeholder="Pickup Address  (${plcTxt1})" name="BookingRoute[${j}][brt_from_location]" autocomplete="off" onblur="hyperModel.clearAddress(this)"><span>${picaddress}</span>`;
                                }
                                addresshtml += textareaHtml + `<div class="help-block error" id="BookingRoute_${j}_brt_from_location_em_" style="display:none"></div>
						</div></div>
                                                <div class="col-xs-2"><span class="autoMarkerLoc ${isHideMapAirport}" data-lockey="${j}" data-toggle="tooltip" title="Select source location on map" onclick="showMap(this,source)"><img src="/images/locator_icon4.png" alt="Precise location" width="30" height="30"></span></div>
                                                </div></div>
						</div></div>`;

			}
                         var isHideMapAirportDrop = (data[j].drop_cty_is_airport == 1)?"hide":"";
			var airDropLat = (data[j].drop_cty_is_airport == 1) ? data[j].drop_cty_lat : "";
			var airDropLong = (data[j].drop_cty_is_airport == 1) ? data[j].drop_cty_long : "";
			var airDropFormAdd = (data[j].drop_cty_is_airport == 1) ? data[j].drop_city_name : "";
			bounds[b] = JSON.stringify(data[j].drop_cty_bounds);
			ctyLat[b] = data[j].drop_cty_lat;
			ctyLong[b] = data[j].drop_cty_long;
			isCtyAirport[b] = data[j].drop_cty_is_airport;
			isCtyPoi[b] = data[j].drop_cty_is_poi;
			mapBound = JSON.stringify({"ctyLat": ctyLat[b], "ctyLon": ctyLong[b], "bound": bounds[b], "isAirport": isCtyAirport[b], "isCtyPoi": isCtyPoi[b]});
			addresshtml +=
					`<div class="col-xs-12 pt10 pb20"><div class="row">
					<div class="col-xs-12 col-sm-6 pl0">
						<label style ="display:${displayRoute}" for="pickup_address${b}" class="control-label text-left">Drop Address for ${data[j].drop_city_name} :</label>
						<input type="hidden" id="ctyLat${b}" value="${data[j].drop_cty_lat}">
						<input type="hidden" id="ctyLon${b}" value="${data[j].drop_cty_long}">
						<input type="hidden" id="ctyELat${b}" value="${drop_cty_ne_lat}">
						<input type="hidden" id="ctyWLat${b}" value="${drop_cty_sw_lat}">
						<input type="hidden" id="ctyELng${b}" value="${drop_cty_ne_long}">
						<input type="hidden" id="ctyWLng${b}" value="${drop_cty_sw_long}">
						<input type="hidden" id="ctyRad${b}" value="${data[j].drop_cty_radius}">
						<input name="BookingRoute[${b}][brt_to_latitude]"  class="locLatVal locLat_${b}" type="hidden" value="${airDropLat}">
						<input name="BookingRoute[${b}][brt_to_longitude]"  class="locLonVal locLon_${b}" type="hidden" value="${airDropLong}">
							<input name="BookingRoute[${b}][brt_from_place_id]"  class="locLonVal locPlaceid_${b}"  type="hidden" value="${data[j].drop_cty_place_id}">
                                                <input name="BookingRoute[${b}][brt_from_formatted_address]"  class="locLonVal locFAdd_${b}"  type="hidden" value="${airDropFormAdd}">
						<input id="city_is_airport${b}" name="BookingRoute[${b}][brt_to_city_is_airport]" type="hidden"  value="${data[j].drop_cty_is_airport}">
						<input id="city_is_poi${b}" name="BookingRoute[${b}][brt_to_city_is_poi]" type="hidden"  value="${data[j].drop_cty_is_poi}">
                                                <input class="brt_location_${b} cpy_loc_${b}" type="hidden"  value="">
                                                <input type="hidden" class="mapBound_${b}" value='${mapBound}'>
					</div>
					<div style ="display:${displayRoute}" class="col-xs-12 col-sm-5">
                                        <div class="row">
                                            <div class="col-xs-10">
						<div class="form-group">`;
					var  textareaHtml1 = `<textarea id="loc${block}_${b}" class="form-control brt_location_${b} route-focus ${hyperTxt}" placeholder="Drop Address  (${plcTxt})" name="BookingRoute[${b}][brt_to_location]" autocomplete="off" onblur="hyperModel.clearAddress(this)">${dropaddress}</textarea>`;
					if(data[j].drop_cty_is_airport == 1){
                                            textareaHtml1 = `<input type="hidden" id="loc${block}_${b}" class="form-control brt_location_${b} route-focus ${hyperTxt}" placeholder="Drop Address  (${plcTxt})" name="BookingRoute[${b}][brt_to_location]" autocomplete="off" onblur="hyperModel.clearAddress(this)"><span>${dropaddress}</span>`;
                                        }
                                        addresshtml += textareaHtml1 +`<div class="help-block error" id="BookingRoute_${b}_brt_to_location_em_" style="display:none"></div>
						</div></div>
                                             <div class="col-xs-2"><span class="autoMarkerLoc  ${isHideMapAirportDrop}" data-lockey="${b}" data-toggle="tooltip" title="Select destination location on map" onclick="showMap(this,'destination')"><img src="/images/locator_icon4.png" alt="Precise location" width="30" height="30"></span></div>
                                             </div></div>
					</div></div>`;
		}
		model.booking_type = booking_type;
		model.transfer_type = '0';
		model.ctyLat = ctyLat;
		model.ctyLon = ctyLong;
		model.bound = bounds;
		model.isCtyAirport = isCtyAirport;
		model.isCtyPoi = isCtyPoi;
		model.hyperLocationClass = hyperTxt;
		if (block == 'traveller')
		{
			$('#reAddress').removeClass('row');
			$('#reAddress').html(addresshtml);
		}
		else
		{
			$('#address').html(addresshtml);
		}

		var isGozonow = jsonData.isGozonow;
		 
		hyperModel.model = model;
		if ($('#bkg_agent_id').val() != '' && $('#bkg_agent_id').val() != undefined)
		{
			if ($('.txtpltraveller').length)
			{
				$('.txtpltraveller').removeAttr('readonly');
			}
			if ($('.txtplroute').length)
			{
				$('.txtplroute').removeAttr('readonly');
			}
			$('.autoMarkerLoc').show();
			$('.custInfo').addClass('hide');
			hyperModel.initializepl();
		}
		else
		{
			if ($('.txtpltraveller').length)
			{
				$('.txtpltraveller').attr('readonly', true);
			}
			if ($('.txtplroute').length)
			{
				$('.txtplroute').attr('readonly', true);
			}
			$('.autoMarkerLoc').addClass('hide');
			$('.custInfo').removeClass('hide');
			if (isGozonow == 1)
			{
				$('.txtpltraveller').attr('readonly', false);
				hyperModel.initializepl();
			}

		}

	};

	this.getCityBounds = function(cty_bounds, ctLat, ctLon)
	{
		var Bounds = cty_bounds;
		var BoundArr = [];
		if (cty_bounds != null)
		{
			BoundArr['ne_lat'] = Bounds.northeast.lat;
			BoundArr['ne_long'] = Bounds.northeast.lng;
			BoundArr['sw_lat'] = Bounds.southwest.lat;
			BoundArr['sw_long'] = Bounds.southwest.lng;
		}
		else
		{
			BoundArr['ne_lat'] = ctLat - 0.05;
			BoundArr['ne_long'] = ctLon - 0.05;
			BoundArr['sw_lat'] = ctLat - 0.0 + 0.05;//parseFloat
			BoundArr['sw_long'] = ctLon - 0.0 + 0.05;
		}

		return BoundArr;
	};

	this.routeList = function(data)
	{
		if (data.rutid > 0)
		{
			$(this.elmRoute).val(data.data.rutid).change();
			$(this.elmTripDistance).val(data.distance).change();
			$(this.elmTripDuration).val(data.duration).change();
		}
		else
		{
			$(this.elmRoute).val('').change();
			$(this.elmTripDistance).val(data.data.distance).change();
			$(this.elmTripDuration).val(data.data.duration).change();
		}
	}

	this.copyItinerary = function()
	{
		var $temp = $("<textarea>");
		var brRegex = /<br\s*[\/]?>/gi;
		$("body").append($temp);
		$temp.val($("#divQuote").html().replace(brRegex, "\r\n")).select();
		document.execCommand("copy");
		$temp.remove();
		$("#itenaryButton").text('Ready to paste');
		$("#itenaryButton").removeClass("btn-primary");
		$("#itenaryButton").addClass("btn-success");
	};

	this.getDiscount = function(promo, jsonData)
	{
		pdate = jsonData.bkg_pickup_date_date;
		ptime = jsonData.bkg_pickup_date_time;
		if (pdate == '' && ptime == '')
		{
			$("#errordivpdate").text('');
			$("#errordivpdate").text('Please enter Pickupdate/Time');
		}
		if (pdate != '' && ($(this.elmPromo1Code).val() != '' || $(this.elmDiscountAmt).val() != '' || $(this.elmOldPromoCode).val() != '') && $(this.elmBaseAmt).val() != '')
		{
			this.getDiscountbyCodenAmount(promo, jsonData);
		}
	};

	this.getDiscountbyCodenAmount = function(promo, jsonData)
	{
		var model = {};
		var code = $(this.elmPromo1Code).val();
		var amount = $(this.elmBaseAmt).val();
		//model.userId = $("#Booking_bkg_user_id").val();

		model.pickupDate = jsonData.bkg_pickup_date_date;
		model.pickupTime = jsonData.bkg_pickup_date_time;
		model.code = code;
		model.amount = amount;
		model.fromCityId = jsonData.bkg_from_city_id;
		model.toCityId = jsonData.bkg_to_city_id;
		model.email = jsonData.bkg_user_email;
		model.phone = jsonData.bkg_contact_no;
		model.oldCode = $(this.elmOldPromoCode).val();
		model.carType = jsonData.bkg_vehicle_type_id;
		model.bookingType = jsonData.bkg_booking_type;
                model.contactId = jsonData.bkg_contact_id;
		promo.model = model;
		if ((code != '' || $(this.elmOldPromoCode).val() != '') && amount > 0)
		{
			promo.getPromoCode();
		}
		else if ($(this.elmDiscountAmt).val() != '' && $(this.elmPromo1Code).val() != '')
		{
			$(this.elmDiscountAmt).val('');
			$(this.elmTotalAmt).val($(this.elmBaseAmt).val());
		}
		else if ($(this.elmDiscountAmt).val() != '' && $(this.elmBaseAmt).val() != '')
		{
			this.calculateAmount(jsonData);
		}
	};

	this.bookingPreference = function(booking)
	{
		var agtId = '';
		this.getBookingPreferences(agtId);
		$('#divpaidby2').show();
		$('#linkcorpdiv').addClass('hide');
		$('#linkagentdiv').addClass('hide');
		$('#trip_user').addClass('hide');
		$('#divpaidby').addClass('hide');
		$('#agtnotification').addClass('hide');
		var trip_user = $("input[name=\'Booking[trip_user]\']").val();
		if (trip_user == 2)
		{
			$('#linkagentdiv').removeClass('hide');
			$(this.elmAgtId).val('');
			$('#corp_addt_details').addClass('hide');
			$(this.elmAgtCreditAmt).val("");
		}
		if (trip_user == 1)
		{
			$('#linkagentdiv').addClass('hide');
			$('#linkcorpdiv').addClass('hide');
			if ($(this.elmAgtId).val() > 0)
			{
				$(this.elmAgtId).val('');
			}
			$('#corp_addt_details').addClass('hide');
			$(this.elmAgtCreditAmt).val("");
			$('#booking_ref_code_div').addClass('hide');

		}
	};

	this.getBookingPreferences = function(agtId)
	{
		$.ajax({
			type: 'GET',
			url: $baseUrl + '/admpnl/agent/bookingpreferences',
			dataType: 'json',
			async: false,
			data: {"agt_id": agtId},
			"success": function(data)
			{
				var otpreq = data.preferences.agt_otp_required;
				var appreq = data.preferences.agt_driver_app_required;
				var botreq = data.preferences.agt_water_bottles_required;
				var cashreq = data.preferences.agt_is_cash_required;
				var slipreq = data.preferences.agt_duty_slip_required;
				var otherreq = data.preferences.agt_pref_req_other;
				var pref;
				pref = '<ul>';
				if (otpreq == 1)
				{
					$('input:checkbox[name="BookingPref[bkg_trip_otp_required]"]').attr('checked', 'checked');
					$('div#uniform-BookingPref_bkg_trip_otp_required span').addClass('checked');
					pref = pref + '<li>OTP is required, </li>';
				}
				if (appreq == 1)
				{
					$('input:checkbox[name="BookingPref[bkg_driver_app_required]"]').attr('checked', 'checked');
					$('div#uniform-BookingPref_bkg_driver_app_required span').addClass('checked');
					pref = pref + '<li>Use of Driver app is required,</li> ';
				}
				if (botreq == 1)
				{
					$('input:checkbox[name="BookingPref[bkg_water_bottles_required]"]').attr('checked', 'checked');
					$('div#uniform-BookingPref_bkg_water_bottles_required span').addClass('checked');
					pref = pref + '<li>2x 500ml water bottles required, </li>';
				}
				if (cashreq == 1)
				{
					$('input:checkbox[name="BookingPref[bkg_is_cash_required]"]').attr('checked', 'checked');
					$('div#uniform-BookingPref_bkg_is_cash_required span').addClass('checked');
					pref = pref + '<li>Do not ask customer for cash</li>';
					//$('#divpref4').html(pref4);
				}
				if (slipreq == 1)
				{
					$('input:checkbox[name="BookingPref[bkg_duty_slip_required]"]').attr('checked', 'checked');
					$('div#uniform-BookingPref_bkg_duty_slip_required span').addClass('checked');
					pref = pref + '<li>All receipts & duty slips required,</li> ';
				}
				if (otherreq != null && otherreq != '')
				{
					$('div#uniform-BookingPref_bkg_pref_other span').addClass('checked');
					$("#BookingPref_bkg_pref_req_other").text(data.preferences.agt_pref_req_other);
					$("#bkg_pref_req_other1").text(data.preferences.agt_pref_req_other);
					pref = pref + '<li>Other instructions:' + otherreq + '</li>';
					$('#othprefreq').show();
				}
				pref = pref + '</ul>';
				$('#divpref').html(pref);
			},
			"error": function(x)
			{
				alert(x);
			}
		});
	};

	this.onAgentSelected = function(agtId)
	{
		this.getAgentDetails(agtId);
		var trip_user = $("input[name=\'Booking[trip_user]\']").val();
		if ((trip_user == 2) && ($(this.elmAgtId).val() != '' && $(this.elmAgtId).val() != null && $(this.elmAgtId).val() != undefined && $(this.elmAgtId).val() != '0' && $(this.elmAgtId).val() != 0))
		{
			if ($(this.elmAgtType).val() == 1)
			{
				conFee = 0;
				$('#agtnotification').removeClass('hide');
			}
			else
			{
				conFee = 0;
				$('#divpaidby').removeClass('hide');
				$('#agtnotification').removeClass('hide');
			}
		}
		$('#corp_addt_details').addClass('hide');
		if ($(this.elmAgtType).val() == 1)
		{
			$('#corp_addt_details').removeClass('hide');
		}
		if ((trip_user == 2) && $(this.elmAgtType).val() != 1 && $(this.elmAgtId).val() != '' && $(this.elmAgtId).val() != null && $(this.elmAgtId).val() != undefined && $(this.elmAgtId).val() != '0' && $(this.elmAgtId).val() != 0)
		{
			var totalAmount = parseInt(Math.round($(this.elmTotalAmt).val()));
			$(this.elmAgtCreditAmt).val(totalAmount);
			$('#div_due_amount').removeClass('hide');
			$('#id_due_amount').html(0);
		}

	};

	this.getAgentDetails = function(agtId, block)
	{
		var self = this;
		if (agtId != '' && agtId != null)
		{
			jQuery.ajax({type: 'GET',
				url: $baseUrl + '/admpnl/agent/agentsbytype',
				dataType: 'json',
				data: {"agt_id": agtId},
				async: false,
				success: function(data)
				{
					if (data.type == 2)
					{
						if (block == "payment")
						{
							if (data.notifyDetails.agt_vendor_autoassign_flag == 1)
							{
								$(self.elmBlockAutoAssign).attr('checked', true);
								$(self.elmBlockAutoAssign).parent().addClass('checked');
							}
							else
							{
								$(self.elmBlockAutoAssign).attr('checked', false);
								$(self.elmBlockAutoAssign).parent().removeClass('checked');
							}
							if (data.notifyDetails.agt_payable_percentage < 100 && data.notifyDetails.agt_payable_percentage >= 0)
							{
								$(self.elmAgtPaidCust).find('input').val(data.notifyDetails.agt_payable_percentage);
							}
							$(self.elmPayAgtName).text(data.notifyDetails.agt_name);
						}
						else
						{
							$(self.elmAgtManagers).val(data.notifyDetails.arl_operating_managers);
							$(self.elmAgtApproved).val(data.notifyDetails.agt_approved_untill_date);
							$(self.elmAgtType).val(data.notifyDetails.agt_type);
							$(self.elmCopyBkgName).val(data.notifyDetails.agt_copybooking_name);
							$(self.elmCopyBkgEmail).val(data.notifyDetails.agt_copybooking_email);
							$(self.elmCopyBkgPh).val(data.notifyDetails.agt_copybooking_phone);
							$(self.elmAgtCommVal).val(data.notifyDetails.agt_commission_value);
							$(self.elmAgtComm).val(data.notifyDetails.agt_commission);
							var $select = $(self.elmCopyBkgCountry).selectize();
							var selectize = $select[0].selectize;
							selectize.setValue(data.notifyDetails.agt_phone_country_code);
						}
					}
					$('#booking_ref_code_div').removeClass('hide');
				},
				error: function(x)
				{
					alert(x);
				}
			});
		}
	};

	this.shownotifyopt = function()
	{
		var agent_id = $(this.elmAgtId).val();
		var agentnotifydata = $('#agentnotifydata').val();
		jQuery.ajax({type: 'POST',
			url: $baseUrl + '/admpnl/agent/bookingmsgdefaults',
			dataType: 'html',
			data: {"agent_id": agent_id, "notifydata": agentnotifydata, "YII_CSRF_TOKEN": $('input[name="YII_CSRF_TOKEN"]').val()},
			success: function(data)
			{
				shownotifydiag = bootbox.dialog({
					message: data,
					title: '',
					size: 'large',
					onEscape: function()
					{
					}
				});
				shownotifydiag.on('hidden.bs.modal', function(e)
				{
					$('body').addClass('modal-open');
				});
				return true;
			},
			error: function(x)
			{
				alert(x);
			}
		});
	};


	this.showCustomerPhoneDetails = function()
	{
		$('#bkErrors').addClass('hide');
		$(this.elmContactNo).val('');
		$('#customerPhoneDetails').removeClass('hide');
		$('#linkedusers').html('');
	};

	this.showB2Btype = function()
	{
		$('#bkErrors').addClass('hide');
		$('#partnerType').removeClass('hide');
		$('#customerPhoneDetails,#bookingType,#bookingRoute,#payment,#travellerInfo,#additionalInfo,#rePayment,#vendorIns').html('');
		$('#customerPhoneDetails,#bookingType,#bookingRoute,#payment,#travellerInfo,#additionalInfo,#rePayment,#vendorIns').addClass('hide');
		$('#b2b').addClass('btn-primary');
		$('#b2c').removeClass('btn-primary');
		$(document).scrollTop($("#partnerType").offset().top);
	};

	this.showB2Ctype = function()
	{
		$('#bkErrors').addClass('hide');
		$('#customerPhoneDetails').removeClass('hide');
		$('#bookingType,#partnerType,#bookingRoute,#payment,#travellerInfo,#additionalInfo,#rePayment,#vendorIns').html('');
		$('#bookingType,#partnerType,#bookingRoute,#payment,#travellerInfo,#additionalInfo,#rePayment,#vendorIns').addClass('hide');
		$('#b2c').addClass('btn-primary');
		$('#b2b').removeClass('btn-primary');
		$(document).scrollTop($("#customerPhoneDetails").offset().top);
	};

	this.validatePartner = function()
	{
		if ($('#Booking_trip_user').val() == 2)
		{
			var phChk = this.validatePhoneNumber($(this.elmCopyBkgPh).val(), this.elmCopyBkgPh);
			if (($(this.elmAgtId).val() == '' || $(this.elmAgtId).val() == null || $(this.elmAgtId).val() == 'undefined'))
			{
				this.showErrors('Please Select an agent', '.btn-partner');
				return false;
			}
			else if (phChk == false)
			{
				$(document).scrollTop(0);
				return false;
			}
		}
		return true;
	};

	this.validatePhoneNumber = function(val, elmph)
	{
		if (val != '' && val != null && val != "" && val != undefined)
		{
			if (/^\d{5,15}$/.test(val))
			{
				return true;
			}
			else
			{
				this.showErrors('Please Enter valid phone number', elmph);
				return false;
			}
		}
	};

	this.validateRoute = function()
	{
		var jsonData = JSON.parse($('#jsonData_routeType').val());
//        if($('#errorMsg').html() != ''){
//            $(document).scrollTop(0);
//            return false;
//        }
        if ((jsonData.bkg_booking_type == 1 || jsonData.bkg_booking_type == 9 || jsonData.bkg_booking_type == 10 || jsonData.bkg_booking_type == 11) && ($(this.elmFromCtyIdAdd).val() == '' || $(this.elmFromCtyIdAdd).val() == 'undefined' || $(this.elmFromCtyIdAdd).val() == null || $(this.elmFromCtyIdAdd).val() == 'null'))
        {
            this.showErrors('Please select Source City', this.elmFromCtyIdAdd);
            return false;
		}
		else if (jsonData.bkg_booking_type == 1 && ($(this.elmToCtyIdAdd).val() == '' || $(this.elmToCtyIdAdd).val() == 'undefined' || $(this.elmToCtyIdAdd).val() == null || $(this.elmToCtyIdAdd).val() == 'null'))
        {
            this.showErrors('Please select Destination City', this.elmToCtyIdAdd);
            return false;
		}
		else if (jsonData.bkg_booking_type == 4 && ($(this.elmFromCityId).val() == '' || $(this.elmFromCityId).val() == 'undefined' || $(this.elmFromCityId).val() == null || $(this.elmFromCityId).val() == 'null'))
        {
            this.showErrors('Please select an Airport', this.elmToCtyIdAdd);
            return false;
		}
		else if (jsonData.bkg_booking_type == 4 && ($(this.elmToCityId).val() == '' || $(this.elmToCityId).val() == 'undefined' || $(this.elmToCityId).val() == null || $(this.elmToCityId).val() == 'null'))
        {
            this.showErrors('Please select Location', this.elmToCtyIdAdd);
            return false;
		}
		else if ($(this.elmPickupDate).val() == '')
        {
            this.showErrors('Please select Pickup Date', this.elmPickupDate);
            return false;
		}
		else if ($(this.elmPickupTime).val() == '')
        {
            this.showErrors('Please select Pickup Time', this.elmPickupTime);
            return false;
		}
		else if (jsonData.bkg_booking_type == 2 && $(this.elmReturnDate).val() == '')
        {
            this.showErrors('Please enter Return Date and Time', this.elmReturnDate);
            return false;
		}
		else if (($('.brt_location_0').val() == '' || $('.locLat_0').val() == '' || $('.locLat_0').val() == '' || $('.locPlaceid_0').val() == '') && jsonData.bkg_booking_type == 8 && jsonData.bkg_agent_id > 0)
        {
            this.showErrors('Please select pickup location', '#locroute_0');
            return false;
		}
		else if ($(this.elmVehicleTypeId).val() <= 0 || $(this.elmVehicleTypeId).val() == '' || $(this.elmVehicleTypeId).val() == 'undefined' || $(this.elmVehicleTypeId).val() == null || $(this.elmVehicleTypeId).val() == 'null')
        {
            this.showErrors('Please select vehicle type', this.elmVehicleTypeId);
            return false;
		}
		else if (jsonData.elmPickupDate != '')
        {
            var pickupDate = $(this.elmPickupDate).val()
            var pickupTime = $(this.elmPickupTime).val()
            var dates1 = pickupDate.split("/");
            var newDate = dates1[2] + "-" + dates1[1] + "-" + dates1[0];

			const date = new Date(newDate + " " + pickupTime);
			var pickupDateTime = date.getTime();

            const currentDate = new Date();
            var curdate = currentDate.getTime();
            if (pickupDateTime < curdate)
            {
                this.showErrors('Pickup time not less than current time', this.elmPickupTime);
                return false;
            }
		}
		else if ($(this.elmServiceClassId).val() <= 0 || $(this.elmServiceClassId).val() == '' || $(this.elmServiceClassId).val() == 'undefined' || $(this.elmServiceClassId).val() == null || $(this.elmServiceClassId).val() == 'null')
        {
            // this.showErrors('Please select Service Class', this.elmVehicleTypeId);
            //  return false;
        }  

		$("#bkErrors").addClass("hide");
		return true;
	};

	this.showRoute = function()
	{
		$('#bkErrors').addClass('hide');
		$('#bookingRoute').removeClass('hide');
	};
	this.validatePayment = function()
	{
		var errors = 0;
		var jsonData = JSON.parse($('#jsonData_payment').val());
		if ($(this.elmBaseAmt).val() <= 0 || $(this.elmBaseAmt).val() == '' || $(this.elmBaseAmt).val() == 'undefined')
		{
			this.showErrors('Please provide base amount', jsonData.trip_user == 1 ? this.elmBaseAmt : "#bkg_base_amount_standard");
			return false;
		}
		else if ($(this.elmTotalAmt).val() <= 0 || $(this.elmTotalAmt).val() == '' || $(this.elmTotalAmt).val() == 'undefined')
		{
			this.showErrors('Total chargeable amount is mandatory', jsonData.trip_user == 1 ? this.elmTotalAmt : "#bkg_total_amount_standard");
			return false;
		}
		if (jsonData.bkg_booking_type == 8)
		{
			if ($(this.elmTripDistance).val() <= 0 || $(this.elmTripDistance).val() == '')
			{
				this.showErrors("Trip Distance can't be 0 or blank", jsonData.trip_user == 1 ? this.elmTripDistance : "#bkg_trip_distance_standard");
				return false;
			}
			else if ($(this.elmTripDuration).val() <= 0 || $(this.elmTripDuration).val() == '')
			{
				this.showErrors("Trip Duration can't be 0 or blank", jsonData.trip_user == 1 ? this.elmTripDuration : "#bkg_trip_duration_standard");
				return false;
			}
			else if ($(this.elmRatePreExtraKm).val() <= 0 || $(this.elmRatePreExtraKm).val() == '')
			{
				this.showErrors("Rate per extra Km. can't be 0 or blank", jsonData.trip_user == 1 ? this.elmRatePreExtraKm : "#bkg_rate_per_km_extra_standard");
				return false;
			}
			else if ($(this.elmRatePerKm).val() <= 0 || $(this.elmRatePerKm).val() == '')
			{
				this.showErrors("Rate per Km. can't be 0 or blank", jsonData.trip_user == 1 ? this.elmRatePerKm : "#bkg_rate_per_km_standard");
				return false;
			}
			else if ($(this.elmVndAmt).val() <= 0 || $(this.elmVndAmt).val() == '')
			{
				this.showErrors("Vendor Amount can't be 0 or blank", jsonData.trip_user == 1 ? this.elmVndAmt : "#bkg_vendor_amount_standard");
				return false;
			}

		}
		$("#bkErrors").addClass("hide");
		return true;
	};

	this.validateTravellerInfo = function(val)
	{
            var blockAddress = 0;
            var jqXHR= $.ajax({
            "type": "POST",
            url: $baseUrl + "/admpnl/booking/getBlokedLocationData",
            "dataType": "json",
            "async": false,
            "data": {'jsonData': jsonData, "YII_CSRF_TOKEN": $('input[name="YII_CSRF_TOKEN"]').val()},
            success: function (data)
            {
                if (data.isBlocked == true)
                {
                    blockAddress = 1;
                    var txt;
                    if (confirm("This route is blocked, still want to process this booking ?")) {
                        txt = "Are you sure want to process this booking";
                        $("#isBlockedLocation").val(blockAddress);
                    } else {
                        txt = "You pressed Cancel!";
                    }

                }

            }
        });
            
        //jqXHR.responseText;
		$('#bkErrors').addClass('hide');
		var locAddLen = jsonData.bkg_booking_type == 4 ? 2 : $('.txtpltraveller').length;
		var ck_email = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/;
                
                var fromLoc = $('.brt_location_0').val();
                var lastLoc = $('.brt_location_' + (locAddLen - 1)).val();
                if($('#city_is_airport0').val() == 1)
                {
                    fromLoc = 'airport';
                    lastLoc = 'airport';
                }
		if ($(this.elmUserEmail1).val() == '' || $(this.elmUserEmail1).val() == 'undefined')
		{
			this.showErrors('Please enter email address', this.elmUserEmail1);
			return false;
		}
		else if (!ck_email.test($(this.elmUserEmail1).val()))
		{
			this.showErrors('Invalid Email address', this.elmUserEmail1);
			return false;
		}
		else if ($(this.elmUserFname1).val() == '' || $(this.elmUserFname1).val() == 'undefined')
		{
			this.showErrors('Please enter first name of the customer', this.elmUserFname1);
			return false;
		}
		else if ($(this.elmUserLname1).val() == '' || $(this.elmUserLname1).val() == 'undefined')
		{
			this.showErrors('Please enter last name of the customer', this.elmUserLname1);
			return false;
		}
		else if (( fromLoc == '' || $('.locLat_0').val() == '' || $('.locLat_0').val() == '') && (jsonData.bkg_booking_type != 8 && jsonData.bkg_booking_type != 4 && jsonData.bkg_agent_id > 0))
		{
			this.showErrors('Please select pickup and drop locations', '#loctraveller_0');
			return false;
		}
		else if ((jsonData.bkg_booking_type != 8 && jsonData.bkg_booking_type != 4 && jsonData.bkg_booking_type != 9 && jsonData.bkg_booking_type != 10 && jsonData.bkg_booking_type != 11 && jsonData.bkg_agent_id > 0) && ( lastLoc == '' || $('.locLat_' + (locAddLen - 1)).val() == '' || $('.locLat_' + (locAddLen - 1)).val() == ''))
		{
			this.showErrors('Please select pickup and drop locations', '#loctraveller_' + (locAddLen - 1));
			return false;
		}
		$("#bkErrors").addClass("hide");
		return true;
	};

	this.validateAdditionalInfo = function()
	{
		$('#bkErrors').addClass('hide');
		var jsonData = JSON.parse($("#jsonData_additionalInfo").val());
		if (this.vehicleInfo(jsonData) == false)
		{
			return false;
			$(document).scrollTop(0);
		}
		else if ($(this.elmLocFollowupDate).val() == '')
		{
			this.showErrors('Followup Date is required', this.elmLocFollowupDate);
			return false;
		}
		else if ($('input[name="Booking[bkg_chk_others]"]').is(':checked'))
		{

			if ($(this.elmSplReqOther).val() == "" || $(this.elmSplReqOther).val() == undefined)
			{
				this.showErrors('Please add your comment', this.elmSplReqOther);
				return false;
			}
		}
		else if (this.checkFollowupTiming(jsonData) == false)
		{
			return false;
		}

		return true;

	};

	this.vendorInstruction = function(jsonData)
	{
		var rtsuccess = true;
		var favorite;
		var breakTime;
		favorite = '<ul>';
		if (jsonData.bkg_spl_req_senior_citizen_trvl == '1')
		{
			var sprequest = '<li>Senior Citizen Travelling</li>';
			favorite = favorite + sprequest;
		}
		if (jsonData.bkg_spl_req_kids_trvl == '1')
		{
			var sprequest = '<li>Kids on board</li>';
			favorite = favorite + sprequest;
		}

		if (jsonData.bkg_spl_req_woman_trvl == '1')
		{
			var sprequest = '<li>Women traveling</li>';
			favorite = favorite + sprequest;
		}
		if (jsonData.bkg_spl_req_carrier == '1')
		{
			var sprequest = '<li>Require vehicle with Carrier (customer already paid Rs.150/-)</li>';
			favorite = favorite + sprequest;
		}

		if (jsonData.bkg_spl_req_driver_hindi_speaking == '1')
		{
			var sprequest = '<li>Require hindi speaking driver</li>';
			favorite = favorite + sprequest;
		}

		if (jsonData.bkg_spl_req_driver_english_speaking == '1')
		{

			var sprequest = '<li>Require english speaking driver</li>';
			favorite = favorite + sprequest;
		}

		if (jsonData.bkg_spl_req_other != "" && typeof (jsonData.bkg_spl_req_other) != 'undefined')
		{

			var sprequest = '<li>' + jsonData.bkg_spl_req_other + '</li>';
			favorite = favorite + sprequest;
		}
		if (jsonData.bkg_spl_req_lunch_break_time == 0 || typeof (jsonData.bkg_spl_req_lunch_break_time) == 'undefined')
		{
			breakTime = 15;
		}
		else
		{
			breakTime = jsonData.bkg_spl_req_lunch_break_time;
		}
		sprequest = "<li>Journey Break (customer already paid " + breakTime + " minutes)</li>";
		favorite = favorite + sprequest;

		if (jsonData.trip_user == 2)
		{
			var partnetPref = $("#divpref").html();
			if (partnetPref != "")
			{
                                partnetPref = partnetPref.replace('<ul>','');
                                partnetPref = partnetPref.replace('</ul>','');
				favorite = favorite + partnetPref;
			}
		}
                favorite = favorite + "</ul>";
		$('#instruction').html(favorite);
		var regex = /(<([^>]+)>)/ig;
		for (var i = 0; i < favorite.length; i++)
		{
			favorite[i] = favorite[i].replace(regex, "");
		}
		//var vendorInstruction = partnetPref.text();
		//$('#Booking_bkg_instruction_to_driver_vendor').val(favorite);

		return rtsuccess;

	};

	this.vehicleInfo = function(jsonData)
	{
		var self = this;
		vehicleId = jsonData.scvId;

		var noPassenger = $(self.elmNoPerson).val();
		var noLargeBag = $(self.elmNoLargebag).val();
		var noSmallBag = $(self.elmNoSmallBag).val();
		var rtsuccess = true;
		$.ajax({
			type: "GET",
			dataType: "html",
			url: $baseUrl + "/admpnl/vehicle/VehicleTypeById",
			async: false,
			data: {"vehicleId": vehicleId},
			success: function(data1)
			{
				obj = jQuery.parseJSON(data1);
				if (noPassenger > 0)
				{

					if ((parseInt(noPassenger) > parseInt(obj.vht_capacity)))
					{
						self.showErrors('Your selected cab can accomodate ' + obj.vht_capacity + ' passengers', self.elmNoPerson);
						rtsuccess = false;
					}
				}
				else
				{
					self.showErrors('Please enter number of passenger', self.elmNoPerson);
					rtsuccess = false;
				}
//				if (noLargeBag > 0)
//				{
//
//					if ((parseInt(noLargeBag) > parseInt(obj.vht_big_bag_capacity)))
//					{
//						self.showErrors('The selected cab can accomodate ' + obj.vht_big_bag_capacity + ' big bag(s)', self.elmNoLargebag);
//						rtsuccess = false;
//					}
//				}
//				if (noSmallBag > 0)
//				{
//
//					if ((parseInt(noSmallBag) > parseInt(obj.vht_bag_capacity)))
//					{
//						self.showErrors('The selected cab can accomodate ' + obj.vht_bag_capacity + 'small bag(s)', self.elmNoSmallBag);
//						rtsuccess = false;
//					}
//				}

			},
			error: function(error)
			{
				console.log(error);
			}
		});
		return rtsuccess;
	};

	this.extraAdditionalInfo = function(infosource)
	{
		$("#source_desc_show").addClass('hide');
		if (infosource == 21)
		{
			$("#BookingAddInfo_bkg_info_source_desc").val('');
			$("#source_desc_show").addClass('hide');
		}
		else
		{
			if (infosource == 5)
			{
				$("#source_desc_show").removeClass('hide');
				$("#BookingAddInfo_bkg_info_source_desc").attr('placeholder', "Friend's email please");
			}
			else if (infosource == 6)
			{
				$("#source_desc_show").removeClass('hide');
				$("#BookingAddInfo_bkg_info_source_desc").attr('placeholder', "");
			}
		}
	};

	this.showErrors = function(txt, elm)
	{
		$('#bkErrors ul').html('<li>' + txt + '. (<a href="javascript:void(0)" onclick="admBooking.focusErrorElm(\'' + elm + '\')">Go there</a>)</li>');
		$('#bkErrors').removeClass('hide');
		$(document).scrollTop(0);
	};

	this.focusErrorElm = function(elm)
	{
		$(elm).focus();
	};



	this.checkFollowupTiming = function(jsonData)
	{
		var followupDate = $(this.elmLocFollowupDate).val();
		var followupTime = $(this.elmLocFollowupTime).val();
		var pickupDate = jsonData.bkg_pickup_date_date;
		var pickupTime = jsonData.bkg_pickup_date_time;
		var self = this;
		var success = true;
		if (followupDate != '')
		{
			$.ajax({
				type: "GET",
				dataType: "json",
				url: $baseUrl + "/admpnl/booking/checkFollowupTiming",
				async: false,
				data: {"pickupDate": pickupDate, 'pickupTime': pickupTime, 'followupDate': followupDate, 'followupTime': followupTime},
				success: function(data1)
				{
					if (data1.success == false)
					{
						self.showErrors(data1.error, self.elmLocFollowupTime);
						success = false;
					}
				},
				error: function(error)
				{
					console.log(error);
				}
			});
		}
		return success;
	};

	this.checkChangedPaymentInfo = function()
	{
		var msg = "";
		if ($('#bkg_is_fbg_type').val() == 1)
		{
			msg += "FBG";
		}
		if ($(this.elmBaseAmt).val() != $('#bkg_base_amount_standard').val())
		{
			if (msg != '')
			{
				msg += ',';
			}
			msg += " Standard Base amount Rs. " + $('#bkg_base_amount_standard').val() + " converted to Rs. " + $(this.elmBaseAmt).val();
		}
		if ($(this.elmAdditionalCharge).val() != $('#bkg_additional_charge_standard').val())
		{
			if (msg != '')
			{
				msg += ',';
			}
			msg += " Standard additional charges Rs. " + $('#bkg_additional_charge_standard').val() + " converted to Rs. " + $(this.elmAdditionalCharge).val();
		}
		if ($(this.elmDiscountAmt).val() != $('#bkg_discount_amount_standard').val())
		{
			if (msg != '')
			{
				msg += ',';
			}
			msg += " Standard discount amount Rs. " + $('#bkg_discount_amount_standard').val() + " converted to Rs. " + $(this.elmDiscountAmt).val();
		}
		if ($(this.elmDriverAllowanceAmt).val() != $('#bkg_driver_allowance_amount_standard').val())
		{
			if (msg != '')
			{
				msg += ',';
			}
			msg += " Standard driver Allowance Rs. " + $('#bkg_driver_allowance_amount_standard').val() + " converted to Rs. " + $(this.elmDriverAllowanceAmt).val();
		}
		if ($(this.elmParkingCharge).val() != $('#bkg_parking_charge_standard').val())
		{
			if (msg != '')
			{
				msg += ',';
			}
			msg += " Standard parking charge Rs. " + $('#bkg_parking_charge_standard').val() + " converted to Rs. " + $(this.elmParkingCharge).val();
		}
		if ($(this.elmTollTax).val() != $('#bkg_toll_tax_standard').val())
		{
			if (msg != '')
			{
				msg += ',';
			}
			msg += " Standard toll tax amount Rs. " + $('#bkg_toll_tax_standard').val() + " converted to Rs. " + $(this.elmTollTax).val();
		}
		if ($(this.elmStateTax).val() != $('#bkg_state_tax_standard').val())
		{
			if (msg != '')
			{
				msg += ',';
			}
			msg += " Standard state tax amount Rs. " + $('#bkg_state_tax_standard').val() + " converted to Rs. " + $(this.elmStateTax).val();
		}
		if ($(this.elmConvenienceCharge).val() != $('#bkg_convenience_charge_standard').val())
		{
			if (msg != '')
			{
				msg += ',';
			}
			msg += " Standard convenience charge Rs. " + $('#bkg_convenience_charge_standard').val() + " converted to Rs. " + $(this.elmConvenienceCharge).val();
		}
		if ($(this.elmServiceTax).val() != $('#bkg_service_tax_standard').val())
		{
			if (msg != '')
			{
				msg += ',';
			}
			msg += " Standard service charge Rs. " + $('#bkg_service_tax_standard').val() + " converted to Rs. " + $(this.elmServiceTax).val();
		}
		if ($(this.elmamountwithoutcod).val() != $('#amountwithoutcodstandard').val())
		{
			if (msg != '')
			{
				msg += ',';
			}
			msg += " Standard amount without COD Rs. " + $('#amountwithoutcodstandard').val() + " converted to Rs. " + $(this.elmamountwithoutcod).val();
		}
		if ($(this.elmTotalAmt).val() != $('#bkg_total_amount_standard').val())
		{
			if (msg != '')
			{
				msg += ',';
			}
			msg += " Standard total amount Rs. " + $('#bkg_total_amount_standard').val() + " converted to Rs. " + $(this.elmTotalAmt).val();
		}
		if ($(this.elmRatePreExtraKm).val() != $('#bkg_rate_per_km_extra_standard').val())
		{
			if (msg != '')
			{
				msg += ',';
			}
			msg += " Standard Rate for extra Km. is " + $('#bkg_rate_per_km_extra_standard').val() + " converted to " + $(this.elmRatePreExtraKm).val();
		}
		if ($(this.elmRatePerKm).val() != $('#bkg_rate_per_km_standard').val())
		{
			if (msg != '')
			{
				msg += ',';
			}
			msg += " Standard Rate per Km. is " + $('#bkg_rate_per_km_standard').val() + " converted to " + $(this.elmRatePerKm).val();
		}
		if ($(this.elmVndAmt).val() != $('#bkg_vendor_amount_standard').val())
		{
			if (msg != '')
			{
				msg += ',';
			}
			msg += " Standard Vendor rate Rs. " + $('#bkg_vendor_amount_standard').val() + " converted to Rs. " + $(this.elmVndAmt).val();
		}
		if ($(this.elmTripDistance).val() != $('#bkg_trip_distance_standard').val())
		{
			if (msg != '')
			{
				msg += ',';
			}
			msg += " Standard Trip Distance " + $('#bkg_trip_distance_standard').val() + " converted to " + $(this.elmTripDistance).val();
		}
		if ($(this.elmTripDuration).val() != $('#bkg_trip_duration_standard').val())
		{
			if (msg != '')
			{
				msg += ',';
			}
			msg += " Standard Trip Duration " + $(this.elmTripDuration).val() + " converted to " + $('#bkg_trip_duration_standard').val();
		}
		$('#paymentChangesData').val(msg);
	};

	this.changeInvoicePaymentData = function()
	{
		//standard
		$(this.elmBaseAmt).val($('#bkg_base_amount_standard').val());
		$(this.elmAdditionalCharge).val($('#bkg_additional_charge_standard').val());
		$(this.elmDiscountAmt).val($('#bkg_discount_amount_standard').val());
		$(this.elmVndAmt).val($('#bkg_vendor_amount_standard').val());
		$(this.elmDriverAllowanceAmt).val($('#bkg_driver_allowance_amount_standard').val());
		$(this.elmParkingCharge).val($('#bkg_parking_charge_standard').val());
		$(this.elmTollTax).val($('#bkg_toll_tax_standard').val());
		$(this.elmStateTax).val($('#bkg_state_tax_standard').val());
		$(this.elmConvenienceCharge).val($('#bkg_convenience_charge_standard').val());
		$(this.elmServiceTax).val($('#bkg_service_tax_standard').val());
		$(this.elmamountwithoutcod).val($('#amountwithoutcodstandard').val());
		$(this.elmTotalAmt).val($('#bkg_total_amount_standard').val());
		$(this.elmRatePreExtraKm).val($('#bkg_rate_per_km_extra_standard').val());
		$(this.elmRatePerExtraMin).val($('#bkg_rate_per_min_extra_standard').val());
		$(this.elmTripDistance).val($('#bkg_trip_distance_standard').val());
		$(this.elmTripDuration).val($('#bkg_trip_duration_standard').val());
		$(this.elmRatePerKm).val($('#bkg_rate_per_km_standard').val());
	};

	this.changeCustomPaymentData = function()
	{
		$('#bkg_base_amount_custom').val($(this.elmBaseAmt).val());
		$('#bkg_additional_charge_custom').val($(this.elmAdditionalCharge).val());
		$('#bkg_discount_amount_custom').val($(this.elmDiscountAmt).val());
		$('#bkg_vendor_amount_custom').val($(this.elmVndAmt).val());
		$('#bkg_driver_allowance_amount_custom').val($(this.elmDriverAllowanceAmt).val());
		$('#bkg_parking_charge_custom').val($(this.elmParkingCharge).val());
		$('#bkg_toll_tax_custom').val($(this.elmTollTax).val());
		$('#bkg_state_tax_custom').val($(this.elmStateTax).val());
		$('#bkg_convenience_charge_custom').val($(this.elmConvenienceCharge).val());
		$('#bkg_airport_fee_custom').val($('#BookingInvoice_bkg_airport_entry_fee').val());
		$('#bkg_service_tax_custom').val($(this.elmServiceTax).val());
		$('#amountwithoutcodcustom').val($(this.elmamountwithoutcod).val());
		$('#bkg_total_amount_custom').val($(this.elmTotalAmt).val());
		$('#bkg_rate_per_km_extra_custom').val($(this.elmRatePreExtraKm).val());
		$('#bkg_rate_per_min_extra_custom').val($(this.elmRatePerExtraMin).val());
		$('#bkg_trip_distance_custom').val($(this.elmTripDistance).val());
		$('#bkg_trip_duration_custom').val($(this.elmTripDuration).val());
		$('#bkg_rate_per_km_custom').val($(this.elmRatePerKm).val());
		$('#bkg_is_fbg_type').val($(this.elmIsFbgType).val());
		$('#bkg_addon_charges_custom').val($(this.elmAgtAddonCharge).val());
	};

	this.changeStandardPaymentData = function()
	{
		$('#bkg_base_amount_standard').val($(this.elmBaseAmt).val());
		$('#bkg_additional_charge_standard').val($(this.elmAdditionalCharge).val());
		$('#bkg_discount_amount_standard').val($(this.elmDiscountAmt).val());
		$('#bkg_vendor_amount_standard').val($(this.elmVndAmt).val());
		$('#bkg_driver_allowance_amount_standard').val($(this.elmDriverAllowanceAmt).val());
		$('#bkg_parking_charge_standard').val($(this.elmParkingCharge).val());
		$('#bkg_toll_tax_standard').val($(this.elmTollTax).val());
		$('#bkg_state_tax_standard').val($(this.elmStateTax).val());
		$('#bkg_airport_fee_standard').val($('#BookingInvoice_bkg_airport_entry_fee').val());
		$('#bkg_convenience_charge_standard').val($(this.elmConvenienceCharge).val());
		$('#bkg_service_tax_standard').val($(this.elmServiceTax).val());
		$('#amountwithoutcodstandard').val($(this.elmamountwithoutcod).val());
		$('#bkg_total_amount_standard').val($(this.elmTotalAmt).val());
		$('#bkg_rate_per_km_extra_standard').val($(this.elmRatePreExtraKm).val());
		$('#bkg_rate_per_min_extra_standard').val($(this.elmRatePerExtraMin).val());
		$('#bkg_trip_distance_standard').val($(this.elmTripDistance).val());
		$('#bkg_trip_duration_standard').val($(this.elmTripDuration).val());
		$('#bkg_rate_per_km_standard').val($(this.elmRatePerKm).val());
		$('#bkg_addon_charges_standard').val($(this.elmAgtAddonCharge).val());
		//$('#bkg_addon_charges_custom').val($(this.elmAgtAddonCharge).val());
	};

	this.calculateCustomAmount = function(promo, jsonData)
	{
		this.getDiscount(promo, jsonData);
		this.calculateAmount(jsonData);
		this.changeCustomPaymentData();
		this.checkChangedPaymentInfo();
	};

	this.calculateAgentPaidAmount = function(paidVal)
	{
		var agtPrevPaid = $(this.elmTotalAmt).val();
		var agtCurrPaid;
		if (paidVal < 100 && paidVal >= 0)
		{
			agtCurrPaid = Math.round((agtPrevPaid * paidVal) / 100);
		}
		else
		{
			agtCurrPaid = Math.round($(this.elmTotalAmt).val());
		}
		$(this.elmAgtCreditAmt).val(agtCurrPaid).change();
	};

	this.editmulticity = function(bkgtype)
	{
		$('#ctyinfo_bkg_type_1').hide();
		$.ajax({
			type: 'GET',
			dataType: "HTML",
			url: $baseUrl + "/admpnl/booking/multicityform?bookingType=" + bkgtype,
			async: false,
			success: function(data)
			{
				multicitybootbox = bootbox.dialog({
					message: data,
					size: 'large',
					title: 'Add pickup info',

				});
				multicitybootbox.on('hidden.bs.modal', function(e)
				{
					$('body').addClass('modal-open');
				});
			}
		});
	};

	this.showMarkerMap = function(mapMarkerBound, locKey, location)
	{

		$.ajax({
			"type": "POST",
			"url": $baseUrl + '/admpnl/booking/autoMarkerAddress',
			"data": {"ctyLat": mapMarkerBound.ctyLat, "ctyLon": mapMarkerBound.ctyLon, "bound": mapMarkerBound.bound, "isCtyAirport": mapMarkerBound.isAirport, "isCtyPoi": mapMarkerBound.isCtyPoi, "locKey": locKey, "loc": location, "airport": mapMarkerBound.airport, "YII_CSRF_TOKEN": $("input[name='YII_CSRF_TOKEN']").val()},
			"dataType": "HTML",
			"success": function(data1)
			{
				mapbootbox = bootbox.dialog({
					message: data1,
					size: 'large',
					title: 'Enter approximate ' + location + ' location and then move pin to exact location',

				});
				mapbootbox.on('hidden.bs.modal', function(e)
				{
					$('body').addClass('modal-open');
				});
			}

		});
	};



	this.showAddOn = function(classId)
	{	
		$('.btn-widget-1').removeClass("btn-success");
		$('.btn-widget-1').removeClass("active");
		$(".btn-widget-2").removeClass("btn-success");
//				$('label.btn-widget').attr("style", "");
//
		$('.serviceClass' + classId).addClass("btn-success");
		//$('.serviceClass' + classId).addClass("btn-widget-1");
		$('label.sccLabel' + classId).attr("style", "color: #fffff");
		$('.serviceClass' + classId).addClass("active");
		$('#Booking_bkg_service_class').val(classId);
		$('#sccId').val(classId);
		if(classId == 4)
		{
			$('#addOnCabDiv').addClass('hide');
		}
		else
		{
			$('#addOnCabDiv').removeClass('hide');	
		}
		var jsonData = JSON.parse($('#jsonData_payment').val());
		var sccData = {"bkg_service_class": classId};
		var addonData = {"bkg_addon_details": ''};
		$.extend(true, jsonData, sccData);
		$.extend(true, jsonData, addonData);
		$('#jsonData_payment').val(JSON.stringify(jsonData));
		this.getAmountbyCitiesnVehicle(booking, jsonData);
	};

	this.showPaymentDetails = function(addOnId, margin = 0,addonType = '')
	{
		var addonsParams = {};
		var jsonData = JSON.parse($('#jsonData_payment').val());
		$("#Booking_addonId").val(addOnId);
		$(".btn-widget-2").removeClass("btn-success");
		$(".addon_" + addOnId).addClass("btn-success");
//				var addoncharge = $(".addon_" + addOnId).attr("addoncharge");
//				$('#BookingInvoice_bkg_addon_charges').val(addoncharge);
		$('label.btn-widget-addon').attr("style", "");
		$('label.addOnLabel' + addOnId).attr("style", "color: #ffffff");
		var classid = $('#Booking_bkg_service_class').val();
		if(addonType == 1)
		{
			addonsParams.type1 = {"adn_type": 1, "adn_id": addOnId, "adn_value": margin};
		}
		if(addonType == 2)
		{
			addonsParams.type2 = {"adn_type": 2, "adn_id": addOnId, "adn_value": margin};
		}
		var addonData = {"bkg_addon_details": addonsParams, "addonid": addOnId};//{"addonid": addOnId,"add"};
		$.extend(true, jsonData, addonData);
                $('#jsonData_payment').val(JSON.stringify(jsonData));
		if(addonType == 1)
		{
		this.updatePriceByRouteRates($addonRouteRates[jsonData.bkg_addon_details.type1.adn_id], jsonData.bkg_addon_details.type1.adn_id);
		}
		this.calculateAmount(jsonData);
	};
	this.generateTierBoxes = function(newQuoteArr, sccId)
	{
		var html = '';
		$.each(newQuoteArr, function(index, value)
		{
			noAddOnHtml = "";
			var quote = encodeURIComponent(JSON.stringify(value.routeRates));
			var noAddOnHtml = '<input type="hidden" class="getquotebycls' + index + '" value="' + quote + '">';
//			
			var selectedClass = (index == sccId) ? "btn-success active" : "";

			html += `<div data-toggle="buttons" class="${selectedClass} btn-widget-1 col-xs-2 p16 mb10 serviceClass${index}"  onclick="admBooking.showAddOn(${index},1)">
				<label class="btn-widget sccLabel${index}">
                        ${value.className}
                        <br>&#x20B9;${value.routeRates.baseAmount}</label>
                        </div>${noAddOnHtml}`;
		});

		if (html != '')
		{
			$('#serviceClassDiv').html(html);
		}

	};
	this.generateAddonBoxes = function(applicableAddons, aadonId)
	{
		var addOnHtml = '';
		$.each(applicableAddons, function(index1, addon)
		{

			var selectedClass = (addon.id == aadonId) ? "btn-success" : "";
			addOnHtml += `
       				<div  onclick="admBooking.showPaymentDetails(${addon.id})" class="col-xs-2 addOnDiv addOnDiv${index1}" style="" addonId="${addon.id}">
       				<div data-toggle="buttons" class="${selectedClass} btn-widget-2 addon_${addon.id}" addoncharge="">
                                <label class="btn-widget-addon addOnLabel${addon.id}">${addon.label}<br>&#x20B9;${addon.addOnCharge}</label>\</div></div>`;

		});
		if (addOnHtml != '')
		{
			$('#addOnDiv').html(addOnHtml);
		}
	};

	this.generateCPAddonBoxes = function(applicableAddons, aadonId)
	{	//debugger;
		$("#Booking_bkg_addon_ids").html('');
		var defDesc = "";
		$.each(applicableAddons, function(index1, addon)
		{
                        var  htmlPlus = "<span>&#8722;</span>";
			if (addon.charge >= 0)
			{
                            htmlPlus = "<span>&#43;</span>";
                        }
                        if(addon.id==0)
                        {
                           defDesc = addon.desc;
                        }
                        $("#Booking_bkg_addon_ids").append($("<option cost="+addon.charge+" details='"+addon.desc+"'></option>").val(addon.id).html(addon.label+" "+htmlPlus+" &#x20B9;"+addon.charge));
		});
                $("#Booking_bkg_addon_ids").select2("val", "0");
                $('#addonDetailsDiv').html("<b>Addon Details: </b><br>"+defDesc);
	};

	this.generateCMAddonBoxes = function(applicableAddons, aadonId)
	{	//debugger;
		$("#Booking_bkg_addon_cab").html('');
		$.each(applicableAddons, function(index1, addon)
		{
					var  htmlPlus = "<span>&#8722;</span>";
			if (addon.charge >= 0)
			{
				htmlPlus = "<span>&#43;</span>";
			}
			$("#Booking_bkg_addon_cab").append($("<option cost=" + addon.charge + "></option>").val(addon.id).html(addon.label + " " + htmlPlus + " &#x20B9;" + addon.charge));
		});
		$("#Booking_bkg_addon_cab").select2("val","0");
	};

	this.generateCarModels = function(newQuoteArr)
	{

		var jsonData = JSON.parse($('#jsonData_payment').val());
		sccId = jsonData.bkg_service_class;
		vctId = jsonData.bkg_vehicle_type_id;
		$.ajax({type: 'GET',
			url: $baseUrl + '/admpnl/vehicle/modellist',
			dataType: 'json',
			data: {'sccId': sccId, 'vctId': vctId},
			success: function(data)
			{
				$("#Booking_bkg_vht_id").val("");
				var data = $.parseJSON(data);
				$('.carModel').removeClass('hide');
				$.each(data, function()
				{
					$("#Booking_bkg_vht_id").append($("<option></option>").val(this['id']).html(this['text']));
				});
			},
		});

	};
	/**
	 * This function is used to fill price related quote data
	 * @param {array} data response coming from server
	 * @param {array} jsonData previous data filled by user
	 * @returns nothing
	 */
	this.updatePriceByRouteRates = function(qRouteRates, addOnId)
	{	//debugger;
		var self = this;
		var parking = qRouteRates.parkingAmount | 0;
        var partner_soldout = qRouteRates.partner_soldout | 0;
		var parkingInclude = qRouteRates.isParkingIncluded | 0;
		var surgeFactorUsed = qRouteRates.surgeFactorUsed | 0;
		var regularBaseAmount = qRouteRates.regularBaseAmount | 0;
		var minVendorAmount = qRouteRates.minVendorAmount | 0;
		var maxVendorAmount = qRouteRates.maxVendorAmount | 0;
		var gnowSuggestedOfferRange;
		if (minVendorAmount > 0 && maxVendorAmount > 0)
		{
			var gnowSuggestedOfferRange = qRouteRates.gnowSuggestedOfferRange;
		}
		 
		var differentiateSurgeAmount = qRouteRates.differentiateSurgeAmount | 0;
		if (qRouteRates.hasOwnProperty('srgDDBP'))
		{
			var ddbpBaseAmount = qRouteRates.srgDDBP.rockBaseAmount | 0;
			var ddbpFactorType = qRouteRates.srgDDBP.refModel.dprApplied.type;
			var ddbpSurgeFactor = qRouteRates.srgDDBP.refModel.dprApplied.factor;
			var routeSurgeFlag = qRouteRates.srgDDBP.refModel.routeFlag;
			var ddbpRouteToRouteFactor = qRouteRates.srgDDBP.refModel.dprRoutes.factor;
			var ddbpZoneToZoneFactor = qRouteRates.srgDDBP.refModel.dprZoneRoutes.factor;
			var ddbpZoneToStateFactor = qRouteRates.srgDDBP.refModel.dprZonesStates.factor;
			var ddbpZoneFactor = qRouteRates.srgDDBP.refModel.dprZones.factor;
			if (routeSurgeFlag == true)
			{
				routeSurgeFlag = 1;
			}
			var ddbpMasterFlag = qRouteRates.srgDDBP.refModel.globalFlag;
		}
		if (qRouteRates.hasOwnProperty('srgDTBP'))
		{
			var dtbpBaseAmount = qRouteRates.srgDTBP.rockBaseAmount | 0;
		}
		if (qRouteRates.hasOwnProperty('srgManual'))
		{
			var manualSurgeId = qRouteRates.srgManual.refId | 0;
			var manualBaseAmount = qRouteRates.srgManual.rockBaseAmount | 0;
		}
		if (qRouteRates.hasOwnProperty('srgDZPP'))
		{
			var dzppSurgeFactor = qRouteRates.srgDZPP.factor;
			var dzppBaseAmount = qRouteRates.srgDZPP.rockBaseAmount | 0;
			var dzppSurgeDesc = qRouteRates.srgDZPP.surgeDesc;
		}
        if (qRouteRates.hasOwnProperty('srgDEBP'))
        {
            var debpSurgeFactor = qRouteRates.srgDEBP.factor;
            var debpBaseAmount = qRouteRates.srgDEBP.rockBaseAmount | 0;
        }
        
        if (qRouteRates.hasOwnProperty('srgDURP'))
        {
            var durpSurgeFactor = qRouteRates.srgDURP.factor;
            var durpBaseAmount = qRouteRates.srgDURP.rockBaseAmount | 0;
        }
        if (qRouteRates.hasOwnProperty('srgDDBPV2'))
        {
            var ddbpv2SurgeFactor = qRouteRates.srgDDBPV2.factor;
            var ddbpv2BaseAmount = qRouteRates.srgDDBPV2.rockBaseAmount | 0;
        }
        if (qRouteRates.hasOwnProperty('srgDDSBP'))
        {
            var ddsbpSurgeFactor = qRouteRates.srgDDSBP.factor;
            var ddsbpBaseAmount = qRouteRates.srgDDSBP.rockBaseAmount | 0;
        }
        if (qRouteRates.hasOwnProperty('additional_param'))
        {
            var additional_param = qRouteRates.additional_param;
        }

        var bookingPriceFactor = {surgeFactorUsed, ddbpBaseAmount, dtbpBaseAmount, ddbpFactorType, manualSurgeId, ddbpSurgeFactor, manualBaseAmount, regularBaseAmount, routeSurgeFlag, ddbpRouteToRouteFactor, ddbpZoneToZoneFactor, ddbpZoneToStateFactor, ddbpZoneFactor, ddbpMasterFlag, dzppSurgeFactor, dzppBaseAmount, dzppSurgeDesc, debpSurgeFactor, debpBaseAmount, durpSurgeFactor, durpBaseAmount, ddbpv2BaseAmount, ddbpv2SurgeFactor, ddsbpBaseAmount, ddsbpSurgeFactor, additional_param,partner_soldout,gnowSuggestedOfferRange};
		var bookingPriceFactorJSON = JSON.stringify(bookingPriceFactor);
		$('#bkgPricefactor').val(bookingPriceFactorJSON);
		$(self.elmSurgeDiffAmt).val(differentiateSurgeAmount);
		$(self.elmNightPickupInc).val(qRouteRates.isNightPickupIncluded);
		$(self.elmNightDropInc).val(qRouteRates.isNightDropIncluded);


		$(self.elmBaseAmt).val(qRouteRates.baseAmount | 0);
		$(self.elmTollTax).val(qRouteRates.tollTaxAmount | 0);
		$(self.elmStateTax).val(qRouteRates.stateTax | 0);
		$(self.elmRatePreExtraKm).val(qRouteRates.ratePerKM);
		$(self.elmRatePerExtraMin).val(qRouteRates.extraPerMinCharge);

		$(self.elmTotalAmt).val(qRouteRates.totalAmount | 0);
		$(self.elmGozoBaseAmt).val(qRouteRates.baseAmount | 0);
		if (qRouteRates.isParkingIncluded == 1)
		{
			$(self.elmParkingCharge).val(qRouteRates.parkingAmount | 0);
			$('.checkerparkingtax span').addClass('checked');
			$(self.elmParkingInc).val(1);
		}
		else
		{

			$(self.elmParkingInc).val(0);
			$('.checkerparkingtax span').removeClass('checked');
			$(self.elmParkingCharge).val(0);
		}

//              var amountwithoutconvenienc = qRouteRates.totalAmount;
//				$(self.elmamountwithoutcod).val(amountwithoutconvenienc);


		$("#trip_rate").text('');
		$(self.elmServiceTax).val(qRouteRates.gst);
		$(self.elmDriverAllowanceAmt).val(qRouteRates.driverAllowance | 0);
		$(self.elmDriverAllowanceAmt).attr('oldamount', qRouteRates.driverAllowance | 0);
		if (qRouteRates.isTollIncluded == 1)
		{
			$('.checkertolltax span').addClass('checked');
			$(self.elmTollTaxInc).val(1);
			$(self.elmTollTaxInc).attr('checked', true);
			$(self.elmTollTaxAdd).attr('checked', true);
			$(self.elmTollTaxAdd).attr('disabled', true);
			$(self.elmTollTax).attr('readonly', true);
		}
		else
		{
			$(self.elmTollTaxInc).val(0);
			$('.checkertolltax span').removeClass('checked');
			$(self.elmTollTaxInc).attr('checked', false);
			$(self.elmTollTaxAdd).attr('checked', false);
			$(self.elmTollTaxAdd).attr('disabled', false);
			$(self.elmTollTax).attr('readonly', false);
		}
		if (qRouteRates.isStateTaxIncluded == 1)
		{
			$(self.elmStateTaxInc).val(1);
			$('.checkerstatetax span').addClass('checked');
			$(self.elmStateTaxInc).attr('checked', true);
			$(self.elmStateTaxAdd).attr('checked', true);
			$(self.elmStateTaxAdd).attr('disabled', true);
			$(self.elmStateTax).attr('readonly', true);
		}
		else
		{
			$(self.elmStateTaxInc).val(0);
			$('.checkerstatetax span').removeClass('checked');
			$(self.elmStateTaxInc).attr('checked', false);
			$(self.elmStateTaxAdd).attr('checked', false);
			$(self.elmStateTaxAdd).attr('disabled', false);
			$(self.elmStateTax).attr('readonly', false);
		}

		if (qRouteRates.isNightPickupIncluded == 1)
		{
			$(self.elmNightPickupInc).val(1);
			$('.checkeNightPickupAllowance span').addClass('checked');
			$(self.elmNightPickupAdd).attr('checked', true);
			$(self.elmNightPickupAdd).attr('disabled', true);
			$(self.elmNightPickupAdd).attr('readonly', true);
		}
		else
		{
			$(self.elmNightPickupInc).val(0);
			$('.checkeNightPickupAllowance span').removeClass('checked');
			$(self.elmNightPickupAdd).attr('checked', false);
			$(self.elmNightPickupAdd).attr('disabled', false);
			$(self.elmNightPickupAdd).attr('readonly', false);
		}

		if (qRouteRates.isNightDropIncluded == 1)
		{
			$(self.elmNightDropInc).val(1);
			$('.checkeNightDropOffAllowance span').addClass('checked');
			$(self.elmNightDropAdd).attr('checked', true);
			$(self.elmNightDropAdd).attr('disabled', true);
			$(self.elmNightDropAdd).attr('readonly', true);
		}
		else
		{
			$(self.elmNightDropInc).val(0);
			$('.checkeNightDropOffAllowance span').removeClass('checked');
			$(self.elmNightDropAdd).attr('checked', false);
			$(self.elmNightDropAdd).attr('disabled', false);
			$(self.elmNightDropAdd).attr('readOnly', false);
		}
		if (qRouteRates.isAirportEntryFeeIncluded == 1)
		{
			$('.checkairportfee span').addClass('checked');
			$('#BookingInvoice_bkg_airport_entry_fee').val(qRouteRates.airportEntryFee | 0);
			$('#BookingInvoice_bkg_is_airport_fee_included').attr('checked', true);
			$('#BookingInvoice_bkg_is_airport_fee_included').val(1);
		}
		else
		{
			$('.checkairportfee span').removeClass('checked');
			$('#BookingInvoice_bkg_is_airport_fee_included').attr('checked', false);
			$('#BookingInvoice_bkg_airport_entry_fee').val(0);
			$('#BookingInvoice_bkg_is_airport_fee_included').val(0);
		}

		$(self.elmCancelAddonCharge).val(qRouteRates.addonCharge | 0);
		$(self.elmAgtAddonCharge).val(qRouteRates.addonCharge | 0);

		var driver_allowance = 0;
		var parking_charge = 0;
		var gozo_base_amount = Math.round($(self.elmGozoBaseAmt).val());
		var gross_amount = Math.round(qRouteRates.baseAmount);
		var discount_amount = 0;//(discount_amount == '') ? 0 : parseInt(discount_amount);
		gross_amount = gross_amount - discount_amount;
		if ($(self.elmDriverAllowanceAmt).val() != '' && $(self.elmDriverAllowanceAmt).val() > 0)
		{
			driver_allowance = parseInt($(self.elmDriverAllowanceAmt).val());
		}

		var tollTaxVal = ($(self.elmTollTax).val() == '') ? 0 : parseInt($(self.elmTollTax).val());
		var stateTaxVal = ($(self.elmStateTax).val() == '') ? 0 : parseInt($(self.elmStateTax).val());
		var service_tax_rate = ($(self.elmServiceTaxRate).val() == '') ? 0 : $(self.elmServiceTaxRate).val();
		var airportEntryCharge = ($('#BookingInvoice_bkg_airport_entry_fee').val() == '') ? 0 : parseInt($('#BookingInvoice_bkg_airport_entry_fee').val());
		var service_tax_amount = 0;
		var addonCharge = Math.round($(self.elmCancelAddonCharge).val());
		if (service_tax_rate != 0)
		{
			service_tax_amount = Math.round(((gross_amount + driver_allowance) * parseFloat(service_tax_rate) / 100));
		}


		var amountwithoutconvenienc = gross_amount + service_tax_amount + tollTaxVal + stateTaxVal + driver_allowance + parking_charge + airportEntryCharge + addonCharge;
		$(self.elmamountwithoutcod).val(amountwithoutconvenienc);

		$(self.elmRatePerKm).val(qRouteRates.costPerKM);
		$(self.elmChargeableDis).val(qRouteRates.quotedDistance | 0);
		$('#rtevndamt').val(qRouteRates.vendorAmount | 0);
		$(self.elmQuotedVenAmt).val(Math.round(qRouteRates.vendorAmount | 0));
		$(self.elmVndAmt).val(Math.round(qRouteRates.vendorAmount | 0));
		$(self.elmbaseamount).val(qRouteRates.baseAmount | 0);

		$(self.elmAddonId).val(addOnId | 0);

		if (!$('input[name="pricerad"]').length)
		{
			this.changeCustomPaymentData();
			this.changeStandardPaymentData();
		}
		else
		{
			if ($('input[name="pricerad"]:checked').val() == 'custom')
			{
				this.changeCustomPaymentData();
			}
			else
			{
				this.changeStandardPaymentData();
			}
		}

	};
	/**
	 * This function is used to fill other quote related data
	 * @param {array} data response coming from server
	 * @param {array} jsonData previous data filled by user
	 * @returns nothing
	 */
	this.updateOthersByQuoteData = function(data, jsonData)
	{	
            //debugger;
		var self = this;
		var qRouteDistance = data.quoteddata.routeDistance;
		var qRouteDuration = data.quoteddata.routeDuration;
		var quoteStatement = data.quoteStatement;
               
                var totalMinutes =  (qRouteDuration!=null) ? (qRouteDuration.totalMinutes) : 0;
                
		$("#divQuote").html(quoteStatement).change();
		$("#itenaryButtonDiv").show();
                if(!$(".is_gozo_now_checkbox").is(":checked"))
                {
                    if (checkGozoNow())
                    {
                            jsonData.isGozonow = 1;
                            $('#Booking_isGozonow').val('1');
                    }
                }

		$("#itenaryButton").text("Copy Itinerary to Clipboard");
		$("#itenaryButton").removeClass("btn-success");
		$("#itenaryButton").addClass("btn-primary");

		var bkgtype = jsonData.bkg_booking_type;

		if (data.distArr != '' && bkgtype != 1 && bkgtype != 9 && bkgtype != 10 && bkgtype != 11 && bkgtype != 4 && bkgtype != 15)
		{
			var distArrVal = data.distArr;
			var multicityjsondata = $.parseJSON($(this.elmMulticityjsondata).val());

			$.each(distArrVal, function(k, v)
			{	
				$('#fdistcreate' + k).text(v['dist']);
				$('#distancecreate' + (k + 1)).text(v['dist']);
				$('#fduracreate' + k).text(v['dura']);
				$('#durationcreate' + (k + 1)).text(v['dura']);
				multicityjsondata[k]['distance'] = v['dist'] + "";
				multicityjsondata[k]['duration'] = v['dura'] + "";
				multicityjsondata[k]['pickup_city'] = v['fromCity'] + "";
				multicityjsondata[k]['drop_city'] = v['toCity'] + "";

			});
			$(self.elmMulticityjsondata).val(JSON.stringify(multicityjsondata)).change();
		}
		else
		{
			$(self.elmMulticityjsondata).val(JSON.stringify(data.arrjsondata)).change();
		}

//debugger;
		$(self.elmGarageTime).val(totalMinutes| 0);
              //  $(self.elmGarageTime).val(qRouteDuration.totalMinutes | 0);
		$(self.elmTripDistance).val(qRouteDistance.quotedDistance | 0);
		$(self.elmTripDuration).val(totalMinutes | 0);
              //  $(self.elmTripDuration).val(qRouteDuration.totalMinutes | 0);


		if ($(self.elmRatePerKm).val() > 0 && qRouteDistance.quotedDistance > 0)
		{
			$('#vehicle_dist_ext').html("Note: Ext. Chrg. After " + $(self.elmTripDistance).val() + " Kms. = " + $(self.elmRatePreExtraKm).val() + "/Km.");
		}
		else
		{
			$('#vehicle_dist_ext').html("");
		}
		var trip_user = jsonData.trip_user;
		if (trip_user == 2 && jsonData.agentBkgAmountPay==2 && jsonData.bkg_agent_id != '' && jsonData.bkg_agent_id != null && jsonData.bkg_agent_id != undefined && jsonData.bkg_agent_id != '0' && jsonData.bkg_agent_id != 0)
		{
			var totalAmount = parseInt(Math.round($(self.elmTotalAmt).val()));
			$(self.elmAgtCreditAmt).val(totalAmount);
			$('#div_due_amount').removeClass('hide');
			$('#id_due_amount').html(0);
		}
                debugger;
		$("#Booking_routeProcessed").val('');
		if (data.processedRoute != '')
		{
			$("#Booking_routeProcessed").val(data.processedRoute);
		}

	};

	this.getCustomServiceClass = function(sccId)
	{
		$('.customserclass').removeClass("btn-success");
		$('.customserclass').removeClass("active");
		$('.serviceClass' + sccId).addClass("btn-success");
		$('.serviceClass' + sccId).addClass("active");
		$('label.sccLabel' + sccId).attr("style", "color: #fffff");
		var jsonData = JSON.parse($('#jsonData_payment').val());
		var scvId = $('.scvId' + sccId).val();
		var data = {"bkg_service_class": sccId, "sccId": sccId, "cartypeid": scvId};

		$.extend(true, jsonData, data);
		$('#jsonData_payment').val(JSON.stringify(jsonData));
		$('.carModel').addClass('hide');
		if (sccId == 4 || sccId == 5)
		{
			admBooking.generateCarModels(jsonData);
		}
	};

}

