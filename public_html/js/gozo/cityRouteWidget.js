/* 
 * CityRouteWidget
 */
var maxElementCnt = 0;
var citySelectizeObjects = [];
var timeSelectizeObjects = [];
var selectedRoutes = [];
var CityRouteWidget = function () {

	this.timeArr = null;
	this.widgetContainerClass = '.tripDataset';
	this.noOfRows = 3;
	this.fireEvent = true;
	this.routes = [];
	this.selectedCityId = '';

	this.fieldSetTemplate = '<div class="row" id="rowSetParent###ELENO###"><div class="rowSet" id="rowSet###ELENO###"> <div class="col-md-3 cityDiv mt5"><select name="fromcity[]" class="fromcity" id="fromcity###ELENO###" data-eleno="###ELENO###" style="width: 100%;"></select></div><div class="col-md-2 dateDiv mt5"><input type="text" name="fromdate[]" id="fromdate###ELENO###" data-eleno="###ELENO###" value="" class="fromdate form-control border-radius form-control ct-form-control" placeholder="Date" autocomplete="off"/></div><div class="col-md-2 timeDiv mt5"><select name="fromtime[]" id="fromtime###ELENO###" data-eleno="###ELENO###" class="fromtime" style="width: 100%;"></select></div><div class="col-md-1 removeDiv mt5"><input type="button" name="remove[]" id="remove###ELENO###" data-eleno="###ELENO###" value="Remove" onclick="$CRWidget.deleteRow(this)" /><input type="hidden" name="estduration[]" id="estduration###ELENO###" data-eleno="###ELENO###" class="estduration" value="0"/><input type="hidden" name="estdistance[]" id="estdistance###ELENO###" data-eleno="###ELENO###" class="estdistance" value="0"/><input type="hidden" name="estmintime[]" id="estmintime###ELENO###" data-eleno="###ELENO###" class="estmintime" value=""/></div><div class="col-md-4 estArrivalTimeDiv" id="estArrivalTime###ELENO###">-</div></div><div class="col-md-12 text-danger msgDiv mb5" id="msg###ELENO###"></div></div>';

	this.datePickerOptions = {
		'autoclose': true,
		'startDate': new Date(),
		'format': 'dd/mm/yyyy',
		'language': 'en',
		'defaultDate': new Date(),
	};

	// Init
	this.init = function () {
            this.noOfRows = $('#Booking_bkg_booking_type').val() == 8 ? 2 : 3;
            if (selectedRoutes.length > 0) {
                    this.rePopulateRoutes();

            } else if (this.noOfRows > 0) {
                    for (var i = 0; i < this.noOfRows; i++) {
                            this.addRow();
                    }
            }
	};
        
        
	// Add New Row
	this.addNewRow = function () {
		err = 0;
		$(this.widgetContainerClass + ' SELECT[name="fromcity[]"]').each(function () {
			if (($.trim($(this).val()) == '') || ($.trim($(this).val()) <= 0) || ($.trim($(this).val()) == undefined)) {
				alert('Please select city');
				err = 1;
				return false;
			}
		});

		if (err == 0) {
			this.addRow();
		}
	};

	// Add Row
	this.addRow = function () {

		// MaxElementCnt
		maxElementCnt++;

		// FieldSet
		var fieldSetTemplate = this.fieldSetTemplate.replace(/###ELENO###/g, maxElementCnt);
		$(this.widgetContainerClass).append(fieldSetTemplate);

		// City
		var objFromCityElement = $($(this.widgetContainerClass + ' SELECT#fromcity' + maxElementCnt));
		this.initCitySelectize(objFromCityElement);

		// DatePicker
		objDatePicker = $($(this.widgetContainerClass + ' INPUT#fromdate' + maxElementCnt));
		this.initDatePicker(objDatePicker);

		// TimePicker
		objTimePicker = $($(this.widgetContainerClass + ' SELECT#fromtime' + maxElementCnt));
		this.initTimePicker(objTimePicker);

		this.dropCityRow();
	};

	// Delete Row
	this.deleteRow = function (obj) {
		obj = $(obj);

		var cntRow = $(this.widgetContainerClass + ' SELECT[name="fromcity[]"]').length;

		if (cntRow > 3) {
			var currEleNo = this.getAttributeDataEleNo(obj, false);
			var objNextCityEle = this.getElementObject(obj, 'SELECT[name="fromcity[]"]', false, 1);
			var nextCityEleNo = this.getAttributeDataEleNo(objNextCityEle, false);

			var objNextCitySelectize = citySelectizeObjects[nextCityEleNo];

			//$(this.widgetContainerClass + ' #rowSet' + currEleNo).remove();
			$(this.widgetContainerClass + ' #rowSetParent' + currEleNo).remove();

			if (objNextCitySelectize && objNextCitySelectize != undefined) {
				objNextCitySelectize.trigger("change", objNextCitySelectize.getValue());
			} else {
				this.synchronizeDateTime(this, false);
			}
		}
	};

	// Drop City Row
	this.dropCityRow = function () {

		$(this.widgetContainerClass + ' INPUT[name="fromdate[]"]').removeClass('hide');
		$(this.widgetContainerClass + ' INPUT[name="fromdate[]"]').last().addClass('hide');

		$(this.widgetContainerClass + ' div.timeDiv div.fromtime').removeClass('hide');
		$(this.widgetContainerClass + ' div.timeDiv div.fromtime').last().addClass('hide');

		$(this.widgetContainerClass + ' INPUT[name="remove[]"]').removeClass('hide');
		$(this.widgetContainerClass + ' INPUT[name="remove[]"]').last().addClass('hide');
	};


	// City Selectize
	this.initCitySelectize = function (objElement) {

		var objCity = new City();
		var objCityRouteWidget = this;

		objElement.selectize({
			'create': false,
			'persist': true,
			'selectOnTab': true,
			'createOnBlur': true,
			'dropdownParent': 'body',
			'optgroupValueField': 'id',
			'optgroupLabelField': 'text',
			'optgroupField': 'id',
			'openOnFocus': true,
			'preload': false,
			'labelField': 'text',
			'valueField': 'id',
			'searchField': 'text',
			'closeAfterSelect': true,
			'addPrecedence': false,
			'onInitialize': function () {

				citySelectizeObjects[maxElementCnt] = this;

//				objCity.populateSourceForSelectize(this, objCityRouteWidget.selectedCityId);
				objCity.populateAllCityForSelectize(this, objCityRouteWidget.selectedCityId);

			}, 'load': function (query, callback) {

//				objCity.loadSourceForSelectize(query, callback);
				objCity.loadAllCityForSelectize(query, callback);

			}, 'onChange': function (value) {
				if (!objCityRouteWidget.fireEvent) {
					return;
				}

				// Prev
				var prevCityEleVal = $.trim(objCityRouteWidget.getElementValue(this, 'SELECT[name="fromcity[]"]', true, -1));
				var prevDateEleVal = $.trim(objCityRouteWidget.getElementValue(this, 'INPUT[name="fromdate[]"]', true, -1));
				var prevTimeEleVal = $.trim(objCityRouteWidget.getElementValue(this, 'SELECT[name="fromtime[]"]', true, -1));

				// Curr
				var currDateEle = objCityRouteWidget.getElementObject(this, 'INPUT[name="fromdate[]"]', true, 0);
				var currEstDurationEle = objCityRouteWidget.getElementObject(this, 'INPUT[name="estduration[]"]', true, 0);
				var currEstDistanceEle = objCityRouteWidget.getElementObject(this, 'INPUT[name="estdistance[]"]', true, 0);
				var currMinTimeEle = objCityRouteWidget.getElementObject(this, 'INPUT[name="estmintime[]"]', true, 0);

				var currDateEleNo = objCityRouteWidget.getAttributeDataEleNo(currDateEle, false);
				var objCurrTimeSelectize = timeSelectizeObjects[currDateEleNo];

				// Next
				var nextCityEle = objCityRouteWidget.getElementObject(this, 'SELECT[name="fromcity[]"]', true, 1);

				if (nextCityEle)
				{
					var nextCityEleNo = objCityRouteWidget.getAttributeDataEleNo(nextCityEle, false);

					// Selectize Object
					var objNextCitySelectize = citySelectizeObjects[nextCityEleNo];

					// Destination/ To City
					objCity.changeDestinationForSelectize(value, objNextCitySelectize);
				}

				// Calculate duration between cities
				if (parseInt(prevCityEleVal) > 0)
				{
					objCity.getRouteDetailsBetweenCity(prevCityEleVal, value, function (data)
					{
						var routeDetails = data;
						var duration = (parseInt(routeDetails.duration) > 0 ? parseInt(routeDetails.duration) : 0);
						var distance = (parseInt(routeDetails.distance) > 0 ? parseInt(routeDetails.distance) : 0);

						if (duration > 0) {
							var totalDuration = duration;
							if ((duration % 30) > 0) {
								totalDuration = (duration + (30 - (duration % 30)));
							}

							// Set Estimated Duration
							$(currEstDurationEle).val(duration);

							// Set Estimated Distance
							$(currEstDistanceEle).val(distance);

							if (prevDateEleVal != '' && prevTimeEleVal != '') {
                                                            
								var objMoment = moment(prevDateEleVal + ' ' + prevTimeEleVal, 'DD/MM/YYYY hh:mmA');
								var prevDateTime = objMoment._d;
                                                                objMoment.add(totalDuration, 'minutes');
                                                                if(Date.parse(objMoment._d) > Date.parse(prevDateTime))
                                                                {
                                                                    // Set Date
                                                                    $(currDateEle).val(objMoment.format('DD/MM/YYYY'));

                                                                    // Set Time
                                                                    objCurrTimeSelectize.setValue(objMoment.format('hh:mmA'));
                                                                }
                                                                
                                                                // Set Min Time
                                                                $(currMinTimeEle).val(objMoment.format('DD/MM/YYYY hh:mmA'));
								
                                                                // Show Estimated Arrival Time
								$(objCityRouteWidget.widgetContainerClass + ' #estArrivalTime' + currDateEleNo).html(objMoment.format('DD/MM/YYYY hh:mm A'));
                                                                 
								// Populate Route Array
								objCityRouteWidget.populateRouteArray();
							}
						}
					});
				}
			}, 'render': {
				option: function (item, escape) {
					return '<div><span class=""><i class="fa fa-map-marker mr5"></i>' + escape(item.text) + '</span></div>';
				},
				option_create: function (data, escape) {
					return '<div>' + '<span class="">' + escape(data.text) + '</span></div>';
				}
			}
		});
	};

	// DatePicker
	this.initDatePicker = function (objElement) {

		var objCityRouteWidget = this;

		objElement.datepicker(this.datePickerOptions).on("changeDate", function () {

			objCityRouteWidget.synchronizeDateTime(this, false);

			//objCityRouteWidget.populateRouteArray();
		});
	};

	// TimePicker
	this.initTimePicker = function (objElement) {

		var objCityRouteWidget = this;

		var timeArrLen = Object.keys(timeArr).length;

		if (timeArrLen > 0) {
			for (var key in timeArr) {
				objElement.append($('<option />', {value: key, text: timeArr[key]}));
			}
		}

		objElement.selectize({
			'create': false,
			'persist': true,
			'selectOnTab': true,
			'createOnBlur': true,
			'dropdownParent': 'body',
			'optgroupValueField': 'id',
			'optgroupLabelField': 'text',
			'optgroupField': 'id',
			'openOnFocus': true,
			'preload': false,
			'labelField': 'text',
			'valueField': 'id',
			'searchField': 'text',
			'closeAfterSelect': true,
			'addPrecedence': false,
			'onInitialize': function () {

				timeSelectizeObjects[maxElementCnt] = this;
			},
			'onChange': function (value) {

				objCityRouteWidget.synchronizeDateTime(this, true);

				//objCityRouteWidget.populateRouteArray();
			}
		});
	};

	// Synchronize Date Time
	this.synchronizeDateTime = function (obj, selectize)
	{
		var currDateVal = this.getElementValue(obj, 'INPUT[name="fromdate[]"]', selectize, 0);
		var currTimeVal = this.getElementValue(obj, 'SELECT[name="fromtime[]"]', selectize, 0);

		var nextDateEle = this.getElementObject(obj, 'INPUT[name="fromdate[]"]', selectize, 1);
		var nextTimeEle = this.getElementObject(obj, 'SELECT[name="fromtime[]"]', selectize, 1);
		var nextEstDurationEle = this.getElementObject(obj, 'INPUT[name="estduration[]"]', selectize, 1);
		var nextDataEleNo = this.getAttributeDataEleNo(nextDateEle, false);
		var nextEstDurationVal = this.getElementValue(obj, 'INPUT[name="estduration[]"]', selectize, 1);

		var estDurationVal = (parseInt(nextEstDurationVal) > 0 ? parseInt(nextEstDurationVal) : 0);

		if (estDurationVal != undefined && estDurationVal >= 0 && nextDataEleNo > 0)
		{
			var totalDuration = estDurationVal;
			if ((estDurationVal % 30) > 0) {
				totalDuration = (estDurationVal + (30 - (estDurationVal % 30)));
			}

			var objNextTimeSelectize = timeSelectizeObjects[nextDataEleNo];

			var objMoment = moment(currDateVal + ' ' + currTimeVal, 'DD/MM/YYYY hh:mmA');
			objMoment.add(totalDuration, 'minutes');


			// Set Next Date
			$(nextDateEle).datepicker('update', new Date(objMoment.format('YYYY'), (objMoment.format('MM') - 1), objMoment.format('DD')));

			// Set Next Time
			objNextTimeSelectize.setValue(objMoment.format('hh:mmA'));

			// Minimum Estimated Time
			$(this.widgetContainerClass + ' #estmintime' + nextDataEleNo).val(objMoment.format('DD/MM/YYYY hh:mmA'));
			$(this.widgetContainerClass + ' #estArrivalTime' + nextDataEleNo).html(objMoment.format('DD/MM/YYYY hh:mm A'));

			//$(nextDateEle).trigger( "changeDate" );
		}

		this.populateRouteArray();
	};

	this.populateRouteArray = function () {

		var arrRoutes = [];
		var objCityRouteWidget = this;

		var dateEleArray = $(this.widgetContainerClass + ' INPUT[name="fromdate[]"]');
		var timeEleArray = $(this.widgetContainerClass + ' SELECT[name="fromtime[]"]');
		var durationEleArray = $(this.widgetContainerClass + ' INPUT[name="estduration[]"]');
		var distanceEleArray = $(this.widgetContainerClass + ' INPUT[name="estdistance[]"]');
		var minTimeEleArray = $(this.widgetContainerClass + ' INPUT[name="estmintime[]"]');

		$(this.widgetContainerClass + ' SELECT[name="fromcity[]"]').each(function (eleIndex) {

			var nextEleIndex = (eleIndex + 1);

			if (durationEleArray.hasOwnProperty(nextEleIndex)) {

				var fromCityId = $(this).val();
				var fromCityName = $(this).text();
				var pickupDateVal = $(dateEleArray[eleIndex]).val();
				var pickupTimeVal = $(timeEleArray[eleIndex]).val();

				var nextCityEle = objCityRouteWidget.getElementObject(this, 'SELECT[name="fromcity[]"]', false, 1);
				var toCityId = $(nextCityEle).val();
				var toCityName = $(nextCityEle).text();
				var estDurationVal = $(durationEleArray[nextEleIndex]).val();
				var estDistanceVal = $(distanceEleArray[nextEleIndex]).val();

				if (toCityId > 0) {
					var routeItem = {};

					routeItem.f_city_id = fromCityId;
					routeItem.f_city_name = fromCityName;
					routeItem.t_city_id = toCityId;
					routeItem.t_city_name = toCityName;
					routeItem.pickup_date = pickupDateVal;
					routeItem.pickup_time = pickupTimeVal;
					routeItem.estm_duration = estDurationVal;
					routeItem.estm_distance = estDistanceVal;

					arrRoutes.push(routeItem);
				}
			}
		});

		this.routes = arrRoutes;
	};


	this.validateRoutes = function () {
		var objCityRouteWidget = this;
		var routes = this.routes;
		var finalRoutes = [];
		var errFlg = 0;
		var bookingType = $('#Booking_bkg_booking_type').val();
		var routeLen = routes.length;

		if (routeLen <= 1 && bookingType != 8) {
                    alert('For Round Trip / Multi City, you must have pickups from atleast two or more cities.');
		} else if (bookingType == 2 && routes[0].f_city_id != routes[(routeLen - 1)].t_city_id) {
                    alert('For round trip starting and ending city must be same.');
                }else if(routeLen == 0 && bookingType == 8){
                    alert('For Custom Trip you must have pickups atleast two or more cities');
		} else {

			$.ajax({
				"type": "POST",
				"dataType": "json",
				"url": $baseUrl + "/lookup/validateroutes",
				"data": {"multicitydata": routes, "booking_type": bookingType},
				success: function (result)
				{
					result.forEach(function (data, indexKey) {

						if (data.validate_success)
						{	 
							finalRoutes.push({
								"pickup_city": data.pickup_cty,
								"drop_city": data.drop_cty,
								"pickup_city_name": data.pickup_city_name,
								"drop_city_name": data.drop_city_name,
								"pickup_date": data.pickup_date,
								"pickup_time": data.pickup_time,
								"date": data.date,
								"duration": data.duration,
								"estimated_date": data.estimated_date_next,
								"distance": data.distance,
								"return_date": "",
								"return_time": "",
								"day": data.day,
								"totday": data.totday,
								"pickup_cty_lat": data.pickup_cty_lat,
								"pickup_cty_long": data.pickup_cty_long,
								"drop_cty_lat": data.drop_cty_lat,
								"drop_cty_long": data.drop_cty_long,
								"pickup_cty_bounds": data.pickup_cty_bounds,
								"drop_cty_bounds": data.drop_cty_bounds,
								"pickup_cty_radius": data.pickup_cty_radius,
								"drop_cty_radius": data.drop_cty_radius,
								"pickup_cty_is_airport": data.pickup_cty_is_airport,
								"drop_cty_is_airport": data.drop_cty_is_airport,
								"pickup_cty_is_poi": data.pickup_cty_is_poi,
								"drop_cty_is_poi": data.drop_cty_is_poi,
								"pickup_cty_loc": data.pickup_cty_loc,
								"drop_cty_loc": data.drop_cty_loc,
							
							});

							$('#return_date').val(data.next_pickup_date);
							$('#return_time').val(data.next_pickup_time);
						}
						else
						{
							errFlg = 1;

							var estMinTimeElementArray = $(objCityRouteWidget.widgetContainerClass + ' INPUT[name="estmintime[]"]');
							var estMinTime = $(estMinTimeElementArray[indexKey]).val();
							var estMinTimeEleNo = $(estMinTimeElementArray[indexKey]).attr('data-eleno');
							$(objCityRouteWidget.widgetContainerClass + ' #msg' + estMinTimeEleNo).html('Pickup date time must be greater than estimated arrival time in that city. You are arriving at ' + estMinTime);
						}
					});

					if (errFlg == 0) {

						if (bookingType == 2) {
							if ($('#return_date').val() == '' && $('#return_time').val() == '') {
								alert('Return date time is mandatory');
								return;
							} else {
								var d1 = getDateobj($('#return_date').val(), $('#return_time').val());
								var d2 = getDateobj(finalRoutes[(finalRoutes.length - 1)].pickup_date, finalRoutes[(finalRoutes.length - 1)].pickup_time);
								if (d1 < d2) {
									alert("return date time can not be less than pickup time");
									return;
								} else {
									//finalRoutes[(finalRoutes.length - 1)].return_date = $('#return_date').val();
									//finalRoutes[(finalRoutes.length - 1)].return_time = $('#return_time').val();
								}
							}
						}

						finalRoutes[(finalRoutes.length - 1)].return_date = $('#return_date').val();
						finalRoutes[(finalRoutes.length - 1)].return_time = $('#return_time').val();

						selectedRoutes = finalRoutes;
						$('#multicitysubmit').val(JSON.stringify(finalRoutes));

						multicitybootbox.hide();
						multicitybootbox.remove();
						$('body').removeClass('modal-open');
						$('.modal-backdrop').remove();

						var jsonstring = JSON.stringify(finalRoutes);
						updateMulticity(jsonstring, (finalRoutes.length - 1));
					}
				},
				"error": function (error)
				{
					console.log(error);
				}
			});
		}
	};


	this.rePopulateRoutes = function () {
		var selRouteLen = selectedRoutes.length;
		if (maxElementCnt > 0 && selRouteLen > 0) {
			maxElementCnt = 0;
		}

		this.fireEvent = false;

		for (var x = 0; x <= selRouteLen; x++) {

			var rowSetId = (parseInt(x) + 1);

			if (x == 0) { // first

				var routeData = selectedRoutes[x];

				this.selectedCityId = routeData.pickup_city;

				this.addRow();

				var objNextCitySelectize = citySelectizeObjects[rowSetId];
				objNextCitySelectize.setValue(routeData.pickup_city);

				// Date
				var objMoment = moment(routeData.pickup_date + ' ' + routeData.pickup_time, 'DD/MM/YYYY hh:mm A');
				$(this.widgetContainerClass + ' #fromdate' + rowSetId).val(objMoment.format('DD/MM/YYYY'));

				// Time
				var objCurrTimeSelectize = timeSelectizeObjects[rowSetId];
				objCurrTimeSelectize.setValue(objMoment.format('hh:mmA'));

				// Set Estimated Duration
				$(this.widgetContainerClass + ' #estduration' + rowSetId).val(0);

				// Set Estimated Distance
				$(this.widgetContainerClass + ' #estdistance' + rowSetId).val(0);

				// Minimum Estimated Time
				$(this.widgetContainerClass + ' #estmintime' + rowSetId).val('');
				$(this.widgetContainerClass + ' #estArrivalTime' + rowSetId).html('');

			} else if (x == selRouteLen) { // last

				var routeData = selectedRoutes[(x - 1)];

				this.selectedCityId = routeData.drop_city;

				this.addRow();

				var objNextCitySelectize = citySelectizeObjects[rowSetId];
				objNextCitySelectize.setValue(routeData.drop_city);

				// Date
				var objMoment = moment(routeData.return_date + ' ' + routeData.return_time, 'DD/MM/YYYY hh:mm A');
				$(this.widgetContainerClass + ' #fromdate' + rowSetId).val(objMoment.format('DD/MM/YYYY'));

				// Set Estimated Duration
				$(this.widgetContainerClass + ' #estduration' + rowSetId).val(routeData.duration);

				// Set Estimated Distance
				$(this.widgetContainerClass + ' #estdistance' + rowSetId).val(routeData.distance);

				// Minimum Estimated Time
				var totalDuration = routeData.duration;
				if ((routeData.duration % 30) > 0) {
					totalDuration = (parseInt(routeData.duration) + (30 - (parseInt(routeData.duration) % 30)));
				}

				var objMinEstTimeMoment = moment(routeData.pickup_date + ' ' + routeData.pickup_time, 'DD/MM/YYYY hh:mm A');
				objMinEstTimeMoment.add(totalDuration, 'minutes');
				$(this.widgetContainerClass + ' #estmintime' + rowSetId).val(objMinEstTimeMoment.format('DD/MM/YYYY hh:mmA'));
				$(this.widgetContainerClass + ' #estArrivalTime' + rowSetId).html(objMinEstTimeMoment.format('DD/MM/YYYY hh:mm A'));

				// Time
				var objCurrTimeSelectize = timeSelectizeObjects[rowSetId];
				objCurrTimeSelectize.setValue(objMinEstTimeMoment.format('hh:mmA'));

			} else { // others

				var routeData = selectedRoutes[x];
				var prevRouteData = selectedRoutes[(x - 1)];

				this.selectedCityId = routeData.pickup_city;

				this.addRow();

				var objNextCitySelectize = citySelectizeObjects[rowSetId];
				objNextCitySelectize.setValue(routeData.pickup_city);

				// Date
				var objMoment = moment(routeData.pickup_date + ' ' + routeData.pickup_time, 'DD/MM/YYYY hh:mm A');
				$(this.widgetContainerClass + ' #fromdate' + rowSetId).val(objMoment.format('DD/MM/YYYY'));

				// Time
				var objCurrTimeSelectize = timeSelectizeObjects[rowSetId];
				objCurrTimeSelectize.setValue(objMoment.format('hh:mmA'));

				// Set Estimated Duration
				$(this.widgetContainerClass + ' #estduration' + rowSetId).val(prevRouteData.duration);

				// Set Estimated Distance
				$(this.widgetContainerClass + ' #estdistance' + rowSetId).val(prevRouteData.distance);

				// Minimum Estimated Time
				var objMinEstTimeMoment = moment(routeData.date, 'YYYY-MM-DD HH:mm:ss');
				$(this.widgetContainerClass + ' #estmintime' + rowSetId).val(objMinEstTimeMoment.format('DD/MM/YYYY hh:mmA'));
				$(this.widgetContainerClass + ' #estArrivalTime' + rowSetId).html(objMinEstTimeMoment.format('DD/MM/YYYY hh:mm A'));
			}
		}

		// Populate Route Array
		this.populateRouteArray();

		this.fireEvent = true;
	};


	// Getting Element Index
	this.getElementIndex = function (obj, elementName, selectize)
	{
		var index = false;
		var elementArray = $(this.widgetContainerClass + ' ' + elementName);

		var eleNo = this.getAttributeDataEleNo(obj, selectize);

		var elementLen = elementArray.length;

		for (var i = 0; i < elementLen; i++)
		{
			if ($(elementArray[i]).attr('data-eleno') == eleNo)
			{
				index = i;
				break;
			}
		}
		return index;
	}

	// Getting Element Object
	this.getElementObject = function (obj, elementName, selectize, position)
	{
		var elementArray = $(this.widgetContainerClass + ' ' + elementName);

		var index = this.getElementIndex(obj, elementName, selectize);

		if (index !== false && (index + parseInt(position)) >= 0)
		{
			return elementArray[(index + parseInt(position))];
		}

		return false;
	};

	// Getting Element Value
	this.getElementValue = function (obj, elementName, selectize, position) {

		var eleObject = this.getElementObject(obj, elementName, selectize, position);

		if (eleObject) {
			return $(eleObject).val();
		}
		return false;
	};


	// Getting Element No
	this.getAttributeDataEleNo = function (obj, selectize = false) {

		var eleNo = '';
		if (obj && selectize) {
			eleNo = $($(obj)[0].$input["0"]).attr('data-eleno');
		} else {
			eleNo = $(obj).attr('data-eleno');
		}

		if (eleNo > 0) {
			return eleNo;
		} else {
			return false;
	}
	};

	this.init();
};