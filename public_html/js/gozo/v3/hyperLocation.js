/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


var HyperLocation = function ()
{

    var model = {};
    model.ctLat = 0.0;
    model.ctLon = 0.0;
    model.ctELat = 0.0;
    model.ctELng = 0.0;
    model.ctWLat = 0.0;
    model.ctWLng = 0.0;
    var autocomplete;
    var csrfToken = '';
    this.isMobile = function ()
    {
        return (this.Android() || this.BlackBerry() || this.iOS() || this.Opera() || this.Windows());
    };
    this.Android = function ()
    {
        return navigator.userAgent.match(/Android/i);
    };
    this.BlackBerry = function ()
    {
        return navigator.userAgent.match(/BlackBerry/i);
    };
    this.iOS = function ()
    {
        return navigator.userAgent.match(/iPhone|iPad|iPod/i);
    };
    this.Opera = function ()
    {
        return navigator.userAgent.match(/Opera Mini/i);
    };
    this.Windows = function ()
    {
        return navigator.userAgent.match(/IEMobile/i);
    };

    this.showErrorMsg = function (content, title = false, bellmsg = false)
    {
        document.getElementById("notify_error").click();
        $("#noti_error_content").html(content);
        if (title)
        {
            $("#noti_error_title").html(title);
        }
        if (bellmsg)
        {
            $("#noti_error_bell_msg").html(bellmsg);
        }
        setTimeout(function ()
        {
            $('#noti_error_bell_msg').click();
        }, 5000);
    };

    this.initializepl = function ()
    {
        var model = this.model;
        var acInputs = document.getElementsByClassName(model.hyperLocationClass);
        var len = acInputs.length;
        var i = 0;
        var j = 0;

        var eastboundLat = 0.0;
        var eastboundLon = 0.0;
        var westboundLat = 0.0;
        var westboundLon = 0.0;
        var latlongdiff = 0.00;
        var pluslatlongdiff = latlongdiff;
        var minuslatlongdiff = 0.0 - latlongdiff;
        var boundObj;
        for (i; i < len; i++)
        {
            j = i;

            $(acInputs[j].id).attr("autocomplete", "disabled");

            boundObj = JSON.parse(model.bound[i]);
            if (boundObj != null)
            {
                if (boundObj.northeast.lat > 0)
                {
                    latLngType = 1;
                }
                else
                {
                    latLngType = 2;
                }
            }
            else
            {
                latLngType = 0;
            }
            if (latLngType == 1)
            {
                westboundLat = boundObj.northeast.lat - minuslatlongdiff;
                westboundLon = boundObj.northeast.lng - minuslatlongdiff;
                eastboundLat = boundObj.southwest.lat - pluslatlongdiff;
                eastboundLon = boundObj.southwest.lng - pluslatlongdiff;
            }
            else if (latLngType == 2)
            {
                ctLat = model.ctyLat[i];
                ctLon = model.ctyLon[i];

                eastboundLat = ctLat - 0.05;
                eastboundLon = ctLon - 0.05;
                westboundLat = ctLat - 0.0 + 0.05;//parseFloat
                westboundLon = ctLon - 0.0 + 0.05;

                boundObj.northeast.lat = westboundLat;
                boundObj.northeast.lng = westboundLon;
                boundObj.southwest.lat = eastboundLat;
                boundObj.southwest.lng = eastboundLon;

                model.bound[i] = JSON.stringify(boundObj);
            }
            else
            {
                ctLat = model.ctyLat[i];
                ctLon = model.ctyLon[i];

                eastboundLat = ctLat - 0.05;
                eastboundLon = ctLon - 0.05;
                westboundLat = ctLat - 0.0 + 0.05;//parseFloat
                westboundLon = ctLon - 0.0 + 0.05;
            }
            var defaultBounds = new google.maps.LatLngBounds(
                    new google.maps.LatLng(eastboundLat, eastboundLon),
                    new google.maps.LatLng(westboundLat, westboundLon));
            var options = {
                types: [],
                bounds: defaultBounds,
                fields: ['address_components', 'geometry', 'place_id', 'formatted_address'],
                strictBounds: 1,
                componentRestrictions: {country: 'IN'}
            };

            if (model.isCtyAirport[i] != 1 || model.isCtyPoi[i] != 1)
            {
                this.autocomplete = new google.maps.places.Autocomplete(acInputs[j], options);
                $("." + model.hyperLocationClass).attr("autocomplete", "disabled");
                this.autocomplete.inputId = acInputs[j].id;
                this.loadAddress(acInputs[j].id, this.autocomplete);
            }
        }
    };
    this.initializeplAirport = function ()
    {
        var acInputs = document.getElementsByClassName('autoComLoc');
        var len = acInputs.length;
        var i = 0;
        for (i; i < len; i++)
        {
            var options = {
                types: [],
                strictBounds: false,
                fields: ['address_components', 'geometry', 'place_id', 'formatted_address'],
                componentRestrictions: {country: 'IN'}
            };
            this.autocomplete = new google.maps.places.Autocomplete(acInputs[i], options);
            $(".autoComLoc").attr("autocomplete", "disabled");
            this.autocomplete.inputId = acInputs[i].id;
            this.loadAddressAirport(acInputs[i].id, this.autocomplete);
        }
        if ($('#locLat0').val() > 0 && $('#locLon0').val() > 0)
        {
            this.initializeplAirportForBound($('#locLat0').val(), $('#locLon0').val());
        }
    };

    this.initializeplCities = function ()
    {
        var acInputs = document.getElementsByClassName('autoComCities');
        var len = acInputs.length;
        var i = 0;
        for (i; i < len; i++)
        {
            var options = {
                types: [],
                strictBounds: false,
                fields: ['address_components', 'geometry', 'place_id', 'formatted_address'],
                componentRestrictions: {country: 'IN'}
            };
            this.autocomplete = new google.maps.places.Autocomplete(acInputs[i], options);
            $(".autoComCities").attr("autocomplete", "disabled");
            this.autocomplete.inputId = acInputs[i].id;
            this.loadAddressCities(acInputs[i].id, this.autocomplete);
        }

    };

    this.loadAddressCities = function (placeBoxId, autocomplete)
    {
        var self = this;
        var city, dist, state, statecode;
        autocomplete = autocomplete == undefined ? this.autocomplete : autocomplete;
        google.maps.event.clearInstanceListeners(autocomplete);
        google.maps.event.addListener(autocomplete, 'place_changed', function ()
        {
            var isAirport = 0;
            var isPoi = 0;
            var SpecificArea = ["locality", "airport"];
            var stopExecution = 0;
            var msg = '';
            var place = autocomplete.getPlace();
            placeTypes = place.types;

            var latitude = place.geometry.location.lat();
            var longitude = place.geometry.location.lng();
            var city = place.address_components[0].long_name;
            var cityAlias = place.address_components[0].short_name;
            if (place.types.indexOf("airport") > -1)
            {
                city = place.name;
                cityAlias = "";
            }

            var placeId = place.place_id;
            for (var i = 0; i < place.address_components.length; i++)
            {
                for (var b = 0; b < place.address_components[i].types.length; b++)
                {
                    if (place.address_components[i].types[b] == "administrative_area_level_2")
                    {
                        dist = place.address_components[i].long_name;
                    }
                    if (place.address_components[i].types[b] == "administrative_area_level_1")
                    {
                        state = place.address_components[i].long_name;
                        statecode = place.address_components[i].short_name;
                    }
                }
            }

            if (placeTypes != undefined)
            {
                for (var j = 0; j < place.types.length; j++)
                {
                    if (place.types[j] == "airport")
                    {
                        isAirport = 1;
                    }
                    if (place.types[j] == "point_of_interest")
                    {
                        isPoi = 1;
                    }
                    if (SpecificArea.includes(place.types[j]))
                    {
                        stopExecution = 1;
                        break;
                    }
                }
            }

            if (stopExecution == 0)
            {
                msg = "Please select specific city.";
                alert(msg);
                $("#Cities_cty_garage_address").val('');
                return false;
            }

            var isCity = self.checkDuplicateCity(city, state, statecode, latitude, longitude, placeId, place.types);
            if (!isCity)
            {
                msg = "City Already Exist.";
                alert(msg);
                return false;
            }


            $('#Cities_cty_name').val(city);
            var isEqual = city.localeCompare(cityAlias);
            if (isEqual != 0)
            {
                $('#Cities_cty_alias_name').val(cityAlias);
            }
            $('#Cities_cty_county').val(dist);
            $('#Cities_cty_state_name').val(state);
            $('#Cities_cty_lat').val(latitude);
            $('#Cities_cty_long').val(longitude);
            if (isAirport == 1)
            {
                $('input[name="Cities[cty_is_airport]"]').click();
            }
            if (isPoi == 1)
            {
                $('input[name="Cities[cty_is_poi]"]').click();
            }
        });
    };

    this.checkDuplicateCity = function (city, state, statecode, latitude, longitude, placeid, types)
    {
        var result = false;
        $.ajax({
            type: "GET",
            dataType: "json",
            url: $baseUrl + "/aaohome/city/checkDuplicateCity",
            async: false,
            data: {'city': city, 'state': state, 'statecode': statecode, 'cLat': latitude, 'cLong': longitude, 'placeId': placeid, 'types': types},
            success: function (data)
            {
                if (data.success)
                {
                    result = true;
                }
                return result;
            },
            error: function (error)
            {
                console.log(error);
            }
        });
        return result;
    };

    this.initializeplAirportForBound = function (lat, long)
    {
        var acInputs = document.getElementsByClassName('autoComLoc');
        var ctLat, ctLon, eastboundLat, eastboundLon, westboundLat, westboundLon;
        ctLat = lat;
        ctLon = long;

        eastboundLat = ctLat - 0.5;
        eastboundLon = ctLon - 0.5;
        westboundLat = ctLat - 0.0 + 0.5;//parseFloat
        westboundLon = ctLon - 0.0 + 0.5;

        var defaultBounds = new google.maps.LatLngBounds(
                new google.maps.LatLng(eastboundLat, eastboundLon),
                new google.maps.LatLng(westboundLat, westboundLon));
        var options = {
            types: [],
            fields: ['address_components', 'geometry', 'place_id', 'formatted_address'],
            bounds: defaultBounds,
            strictBounds: 1,
            componentRestrictions: {country: 'IN'}
        };
        this.autocomplete = new google.maps.places.Autocomplete(acInputs[0], options);
        $(".autoComLoc").attr("autocomplete", "disabled");
        this.autocomplete.inputId = acInputs[0].id;
        this.loadAddressAirport(acInputs[0].id, this.autocomplete);
    };

    this.getAirportBoundsOptions = function (lat, long)
    {
        var ctLat, ctLon, eastboundLat, eastboundLon, westboundLat, westboundLon;
        ctLat = lat;
        ctLon = long;

        eastboundLat = ctLat - 0.5;
        eastboundLon = ctLon - 0.5;
        westboundLat = ctLat - 0.0 + 0.5;//parseFloat
        westboundLon = ctLon - 0.0 + 0.5;

        var defaultBounds = new google.maps.LatLngBounds(
                new google.maps.LatLng(eastboundLat, eastboundLon),
                new google.maps.LatLng(westboundLat, westboundLon));
        var options = {
            types: [],
            fields: ['address_components', 'geometry', 'place_id', 'formatted_address'],
            bounds: defaultBounds,
            strictBounds: 1,
            componentRestrictions: {country: 'IN'}
        };
        return options;
    };

    /* Changes on 13/01/2020 By Chiranjit Hazra*/

    this.loadAddress = function (placeBoxId, autocomplete)
    {
        var self = this;
        var model = this.model;
        var boundObj;
        autocomplete = autocomplete == undefined ? this.autocomplete : autocomplete;
        google.maps.event.clearInstanceListeners(autocomplete);
        google.maps.event.addListener(autocomplete, 'place_changed', function ()
        {
            var notSpecificArea = ["locality", "administrative_area_level_1", "administrative_area_level_2", "administrative_area_level_3", "country"];
            var stopExecution = 0;
            var msg = "";
            var place = autocomplete.getPlace();
           // placeTypes = place.types;

            var latitude = place.geometry.location.lat();
            var longitude = place.geometry.location.lng();

            strArr = placeBoxId.split('_');
            suffixNum = strArr[1];

            boundObj = JSON.parse(model.bound[suffixNum]);

//                    if(boundObj) {
//			if (((boundObj.northeast.lat - 0.0 + 0.05) >= latitude && latitude >= (boundObj.southwest.lat - 0.05)) &&
//					((boundObj.northeast.lng - 0.0 + 0.05) >= longitude && longitude >= (boundObj.southwest.lng - 0.05)))
//			{
//
//			} else
//                        {
//                            msg = "The location is far off from the city. Please enter correct location address.";
//                            if(self.isMobile())
//                            {
//                                self.showErrorMsg(msg);
//                            }
//                            else
//                            {
//                                 alert(msg);
//                            }
//                            $('.brt_location_' + suffixNum).val('');
//                            $('.locLat_' + suffixNum).val('').change();
//                            $('.locLon_' + suffixNum).val('').change();
//                            return false;
//                        }
//                    }
			if(place.address_components[0]!=undefined)
            {
                    placeTypes = place.address_components[0].types;
            }
            if (placeTypes != undefined)
            {
                if ($('#' + placeBoxId).val().length < 25 || (placeTypes.indexOf("sublocality") == -1 && placeTypes.indexOf("political") > -1))
                {
                    place = null;
                    $('.brt_location_' + suffixNum).val('');
                    msg = "Enter proper address rather than city or region name.";
                    if (self.isMobile())
                    {
                        self.showErrorMsg(msg);
                    }
                    else
                    {
                        alert(msg);
                    }
                    $('.locLat_' + suffixNum).val('').change();
                    $('.locLon_' + suffixNum).val('').change();
                    return false;
                }
            }

            if (latitude > 0 && longitude > 0)
            {
                $('.locLat_' + suffixNum).val(latitude).change();
                $('.locLon_' + suffixNum).val(longitude).change();
                $('.locFAdd_' + suffixNum).val(place.formatted_address).change();
                $('.locPlaceid_' + suffixNum).val(place.place_id).change();
                $('.brt_location_' + suffixNum).val($('#' + placeBoxId).val()).change();
                $('.cpy_loc_' + suffixNum).val($('#' + placeBoxId).val());
            }
        });
    };

    /* Changes on 13/01/2020 By Chiranjit Hazra*/

    this.loadAddressAirport = function (placeBoxId, autocomplete)
    {
        var self = this;
        var model = this.model;
        var boundObj;
        var bound;
        var city, state;
        autocomplete = autocomplete == undefined ? this.autocomplete : autocomplete;
        google.maps.event.clearInstanceListeners(autocomplete);
        google.maps.event.addListener(autocomplete, 'place_changed', function ()
        {
            var isAirport = 0;
            var notSpecificArea = ["locality", "administrative_area_level_1", "administrative_area_level_2", "administrative_area_level_3", "country"];
            var stopExecution = 0;
            var msg = '';
            var place = autocomplete.getPlace();
            placeTypes = place.types;

            var latitude = place.geometry.location.lat();
            var longitude = place.geometry.location.lng();

            for (var i = 0; i < place.address_components.length; i++)
            {
                for (var b = 0; b < place.address_components[i].types.length; b++)
                {
                    if (place.address_components[i].types[b] == "locality")
                    {
                        city = place.address_components[i].long_name;
                    }
                    if (place.address_components[i].types[b] == "administrative_area_level_1")
                    {
                        state = place.address_components[i].long_name;
                    }
                }
            }

            textName1 = 'locLon';
            textBoxName = 'brt_location';
            preNumLen = textBoxName.length;

            plNumLen = placeBoxId.length;

            suffixNum = 1;
            if (placeTypes != undefined)
            {
                for (var j = 0; j < place.types.length; j++)
                {
                    if (place.types[j] == "airport")
                    {
                        isAirport = 1;
                    }
                    if (notSpecificArea.includes(place.types[j]))
                    {
                        stopExecution = 1;
                        break;
                    }
                }
            }

            if (stopExecution == 1)
            {
                msg = "Please select specific address or area within a city.";
                if (self.isMobile())
                {
                    self.showErrorMsg(msg);
                }
                else
                {
                    alert(msg);
                }
                $("#brt_location" + suffixNum).val('');
                $('#btnTransfer').attr('disabled', false);
                $('#btnTransfer').text('Proceed');
                $('#btnAirTransfer').attr('disabled', false);
                $('#btnAirTransfer').text('NEXT');
                return false;
            }

            if (suffixNum == 0)
            {
                self.initializeplAirportForBound(latitude, longitude);
            }
//                    else if(suffixNum == 1)
//                    {
//                        bound = '{"northeast":{"lat":' + $('#locLat0').val() + ',"lng":' + $('#locLon0').val() + '},"southwest":{"lat":' + $('#locLat0').val() + ',"lng":' + $('#locLon0').val() + '}}';
//                        
//                        boundObj = JSON.parse(bound);
//                        if(boundObj) 
//                        {
//                            if (((boundObj.northeast.lat - 0.0 + 9.0) >= latitude && latitude >= (boundObj.southwest.lat - 9.0)) &&
//                                            ((boundObj.northeast.lng - 0.0 + 9.0) >= longitude && longitude >= (boundObj.southwest.lng - 9.0)))
//                            {
//
//                            } else
//                            {
//                                msg = "The location is far off from the city. Please enter correct location address.";
//                                if(self.isMobile())
//                                {
//                                    self.showErrorMsg(msg);
//                                }
//                                else
//                                {
//                                     alert(msg);
//                                }
//                                $("#brt_location" + suffixNum).val('');
//                                $('#btnTransfer').attr('disabled',false);
//				$('#btnTransfer').text('Proceed');
//                                $('#btnAirTransfer').attr('disabled',false);
//				$('#btnAirTransfer').text('NEXT');
//                                return false;
//                            }
//                        }
//                    }
            self.getCityReturn(suffixNum, '', isAirport, place);

        });
    };

    this.findAddress = function (id)
    {
        var placeBoxId = {};
        placeBoxId.inputId = id;
        this.autocomplete = placeBoxId;
        this.loadAddress(id);
    };

    this.findAddressAirport = function (id)
    {
        var placeBoxId = {};
        placeBoxId.inputId = id;
        this.autocomplete = placeBoxId;
        this.loadAddressAirport(id);
    };

    this.swap = function ()
    {
        var locName, locId, locLat, locLong, locPlaceId, locFAdd, isAirport;
        locId = $('#ctyIdAir0').val();
        locLat = $('#locLat0').val();
        locLong = $('#locLon0').val();
        locPlaceId = $('#locPlaceid0').val();
        locFAdd = $('#locFAdd0').val();
        locName = $('#brt_location0').val();
        isAirport = $('#isAirport0').val();
        /*swap*/
        $('#ctyIdAir0').val($('#ctyIdAir1').val());
        $('#locLat0').val($('#locLat1').val());
        $('#locLon0').val($('#locLon1').val());
        $('#locPlaceid0').val($('#locPlaceid1').val());
        $('#locFAdd0').val($('#locFAdd1').val());
        $('#brt_location0').val($('#brt_location1').val());
        $('#isAirport0').val($('#isAirport1').val());

        $('#ctyIdAir1').val(locId);
        $('#locLat1').val(locLat);
        $('#locLon1').val(locLong);
        $('#locPlaceid1').val(locPlaceId);
        $('#locFAdd1').val(locFAdd);
        $('#brt_location1').val(locName);
        $('#isAirport1').val(isAirport);
    };

    this.changeTrDestination = function (value, prevId)
    {
        var self = this;
        var suffixNum = 0;
        if ((prevId == '' || prevId == null || prevId == undefined) || (prevId != value))
        {
            $('#ctyIdAir1').val('');
            $('#locLat1').val('');
            $('#locLon1').val('');
            $('#locPlaceid1').val('');
            $('#locFAdd1').val('');
            $('#brt_location1').val('');
            $('#isAirport1').val(0);
        }
        self.getCityReturn(suffixNum, value);
    };

    this.getCityReturn = function (suffixNum, cityid, isAirport, place)
    {
        var self = this;
        if (cityid > 0 || place != undefined)
        {
            $.ajax({
                type: "GET",
                dataType: "json",
                url: $baseUrl + "/city/cityId",
                data: {'cLat': place == undefined ? '' : place.geometry.location.lat(), 'cLong': place == undefined ? '' : place.geometry.location.lng(), 'placeId': place == undefined ? '' : place.place_id, 'formattedAddress': place == undefined ? '' : place.formatted_address, 'types': place == undefined ? '' : place.types, 'isAirport': isAirport, 'ctyId': cityid},
                success: function (data1)
                {
                    data = data1;
                    if (data.ctyId > 0)
                    {
                        $('#ctyIdAir' + suffixNum).val(data.ctyId).change();
                        if (suffixNum == 0)
                        {
                            $('#Booking_bkg_from_city_id').val(data.ctyId).change();
                        }
                        else
                        {
                            $('#Booking_bkg_to_city_id').val(data.ctyId).change();
                        }
                        if (data.ctyLat > 0 && data.ctyLong > 0)
                        {
                            $('#locLat' + suffixNum).val(data.ctyLat).change();
                            $('#locLon' + suffixNum).val(data.ctyLong).change();
                            $('#locFAdd' + suffixNum).val(place == undefined ? data.grageAdd : place.formatted_address).change();
                            if (data.grageAdd != '')
                            {
                                $('#brt_location' + suffixNum).val(data.grageAdd).change();
                            }
                            $('#locPlaceid' + suffixNum).val(place == undefined ? '' : place.place_id).change();
                            $('#isAirport' + suffixNum).val(data.isAirport);
                            if (cityid > 0)
                            {
                                if (!self.isMobile())
                                {
                                    self.initializeplAirportForBound(data.ctyLat, data.ctyLong);
                                }
                            }
                            $('.cpy_loc_' + suffixNum).val($('#brt_location' + suffixNum).val());
                        }
                    }
                    else
                    {
                        $("#brt_location" + suffixNum).val('');
                        msg = "Please select correct location.";
                        if (self.isMobile())
                        {
                            self.showErrorMsg(msg);
                        }
                        else
                        {
                            alert(msg);
                        }
                    }
                    $('#btnTransfer').attr('disabled', false);
                    $('#btnTransfer').text('Proceed');
                    $('#btnAirTransfer').attr('disabled', false);
                    $('#btnAirTransfer').text('NEXT');
                },
                error: function (error)
                {
                    console.log(error);
                }
            });
        }
    };

    this.placeArr = [];
    this.clearAddress = function (obj, type)
    {
        var placeBoxId = $(obj).attr('id');
        if (type == "airport")
        {
            var key = 1;
        }
        else
        {
            var strArr = placeBoxId.split('_');
            var key = strArr[1];
        }

        if (this.placeArr.length == 0)
        {
            this.placeArr.push(key);
        }
        else if (!this.placeArr.includes(key))
        {
            this.placeArr.push(key);
        }
        else
        {
            var str = $('#' + placeBoxId).val();
            var str1 = $('.cpy_loc_' + key).val();
            var result = str1.localeCompare(str);
            if (result == 1 || result == -1)
            {
                $('#' + placeBoxId).val($('.cpy_loc_' + key).val()).change();
                $('#btnTransfer').attr('disabled', false);
                $('#btnTransfer').text('Proceed');
                $('#btnAirTransfer').attr('disabled', false);
                $('#btnAirTransfer').text('NEXT');
            }
        }
        return;
    };
}