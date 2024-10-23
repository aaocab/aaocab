/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */




/* global google */

var PACObject = {
    _object: {},
    getObject: function (id)
    {
        return this._object[id];
    },
    setObject: function (label, obj)
    {
        this._object[label] = obj;
    }
};
var txtFields;
var placeAutoComplete = function (txtField, dataField, widgetId)
{      
   txtFields = txtField;
    this.model = {};
    if (widgetId !== undefined && widgetId !== null)
    {
        PACObject.setObject(widgetId, this);
        this.model.controlId = widgetId;
    }
    this.model.displayField = document.getElementById(txtField);
    this.model.valueField = $("#" + dataField + ".hdn" + this.model.controlId)[0];
    this.model.placeObj = '{"coordinates": {}}';
    this.box = null;
    this.geocode = null;
     var defaultOptions = {
        types: [],
        fields: ['place_id'],
        strictBounds: 1,
        componentRestrictions: {country: 'IN'}
    };
//    var defaultOptions = {
//        types: [],
//        fields: ['address_components', 'geometry', 'place_id', 'formatted_address'],
//        strictBounds: 1,
//        componentRestrictions: {country: 'IN'}
//    };
    this.selected = false;
    this.model.defaultOptions = JSON.stringify(defaultOptions);

    var obj = this;
    $(this.model.displayField).on("focus", function ()
    {
        obj.selected = true;
    });
    $(this.model.displayField).on("blur", function ()
    {
        if (!obj.selected && obj.model.displayValue !== obj.model.displayField.value)
        {
            obj.clear();
        }
    });
    $(this.model.displayField).on('keypress', function (e)
    {
        if ((e.keyCode || e.which) == 13)
        {
            return false;
        }
    });
    this.initControl = function (options)
    {
        this.model.options = options;
        this.model.displayValue = this.model.displayField.value;
        this.clear();
        this.enable();
        this.removePAC();

        this.model.autocomplete = new google.maps.places.Autocomplete(this.model.displayField, options);
        var obj = this;
        google.maps.event.addListener(this.model.autocomplete, 'place_changed', function ()
        {
         
            obj.processValue();
        });
        $(this.model.valueField).trigger("load", [this]);
    };

    this.setValue = function (place, silent = false)
    {
        if (place !== undefined && place != '')
        {
            if (place.constructor === "test".constructor)
            {
                try
                {
                    place = JSON.parse(place);
                }
                catch (e)
                {
                    return false;
                }
            }
            this.model.displayField.value = place.address;
            this.model.valueField.value = JSON.stringify(place);
            this.model.displayValue = this.model.displayField.value;
            if (!silent)
            {
                $(this.model.displayField).trigger("placechange", [this]);
            }
    }
    };

    this.getValueObject = function ()
    {
        var place = this.model.valueField.value;
        if (place !== undefined && place !== '')
        {
            if (place.constructor === "test".constructor)
            {
                try
                {
                    place = JSON.parse(place);
                }
                catch (e)
                {
                    return false;
                }
            }
        }
        return place;
    };

    this.processValue = function ()
    {
     
        this.selected = true;
        this.model.displayValue = this.model.displayField.value;
        var placeObj = JSON.parse(this.model.placeObj);
        this.model.googlePlaceObject = this.model.autocomplete.getPlace();
        placeObj.address = this.model.displayValue;
        placeObj.place_id = this.model.googlePlaceObject.place_id;

        var geo = this;
        placeObj = geo.getgeo(placeObj);
        if (!placeObj.coordinates)
        {
            this.model.displayField.value = 'Sorry! Address not found';
            this.model.valueField.value = '';
        }

        this.model.valueField.value = JSON.stringify(placeObj);
        $(this.model.displayField).trigger("placechange", [this]);
    };
    this.getgeo = function (placeObj)
    {
   //  alert(JSON.stringify(placeObj));
		let model = this.model;
        $.ajax({
             async: false,
            type: "POST",
            url: $baseUrl + "/lookup/getPlace",
            dataType: 'json',
            data: {place: placeObj},
            success: function (data)
            {
                placeObj = data.data;
				placeObj.address = model.displayField.value;
            },
            error: function (error)
            {
                console.log(error);
            }
        });
        return placeObj;
    };
    this.focus = function ()
    {
        this.model.displayField.focus();
    };

    this.clear = function ()
    {
        this.model.displayField.value = '';
        this.model.valueField.value = '';
    };

    this.disable = function ()
    {
        this.model.displayField.readOnly = true;
    };

    this.enable = function ()
    {
        this.model.displayField.readOnly = false;
    };

    this.removePAC = function ()
    {
        if (this.model.autocomplete != undefined)
        {
            google.maps.event.clearInstanceListeners(this.model.autocomplete);
        }
    };

    this.getPlaceObject = function ()
    {
        return JSON.parse(this.model.valueField.value);
    };

    this.getGooglePlaceObject = function ()
    {
        return this.model.googlePlaceObject;
    };

    this.onPlaceChange = function (func)
    {
        $(this.model.displayField).on("placechange", func);
    };

    this.onLoad = function (func)
    {
        $(this.model.valueField).on("load", func);
    };

    this.bindOnClick = function ()
    {
        this.model.displayField.readonly = true;
        $(this.model.displayField).unbind("click").on("click", function ()
        {
            obj.openMobileMap();
        });
    };

    this.validateAddress = function (event)
    {
        var check = this.isPreciseLocation();
        if (!check)
        {
            alert("Please enter proper street/building/point address");
            this.clear();
            this.focus();
        }
        return check;
    };

    this.isPreciseLocation = function ()
    {
        var placeTypes;
        var success = false;
        var invalidTypes = ['locality', 'administrative_area_level_3', 'administrative_area_level_2', 'administrative_area_level_1', 'country', 'postal_code'];
		
		let placeObj  = this.getPlaceObject();

		if(placeObj.types !== undefined)
        {
			success = true;
			placeTypes = placeObj.types;
			invalidTypes.every(function (value)
			{
				if (placeTypes.indexOf(value) >= 0)
				{
					success = false;
				}
				return success;
			});
        }
        if(!success)
        {
            var area =  this.calculateArea(placeObj.bounds);
            success  = (area && area<2);
        }

        return success;
    };

    this.openGeocodeMarker = function ()
    {
           
        var placeObj = JSON.parse(this.model.placeObj);
        try
        {
            placeObj = JSON.parse(this.model.valueField.value);
        }
        catch (e)
        {

        }
       // this.showMapBox();
       this.openMobileMap();
        var bounds = null;
        if (this.model.options !== undefined && this.model.options !== null)
        {
            bounds = this.model.options.bounds;
        }
        this.geocode = new geocodeMarker(placeObj, bounds, document.getElementById("map" + obj.model.controlId));
        this.geocode.initMap();
    };

    this.showMapBox = function ()
    {
         
        if (this.box !== null)
        {
            this.box.addClass('show').css('display', 'block');
            return;
        }
 
        this.box = bootbox.dialog({
            title: '',
            message: '<h4>Select Precise Location</h4><div id="map' + obj.model.controlId + '" style="min-height: 500px"></div>',
            size: 'large',
            onEscape: function ()
            {
                obj.box.modal('hide');
                obj.box.css('display', 'none');
                $('.modal-backdrop').remove();
                $("body").removeClass("modal-open");
            },
            buttons: {
                submit: {
                    label: 'Save Location',
                    className: 'btn-success',

                    callback: function ()
                    {
                        obj.model.googlePlaceObject = obj.geocode.model.googlePlaceObject;
                        obj.setValue(obj.geocode.model.placeObj);
                        obj.box.modal('hide');
                        obj.box.css('display', 'none');
                        $('.modal-backdrop').remove();
                        $("body").removeClass("modal-open");
                    }
                }
            }
        }).addClass('show').css('display', 'block');
    };

    this.openMobileMap = function ()
    {
        if (this.model.options == null)
        {
            return;
        }
        $.ajax({
            "type": "GET",
            "url": '/booking/autoMarkerAddress',
            "data": {},
            "dataType": "HTML",
            "success": function (data1)
            {
              // alert(data1);
                $('#map-marker-content').html(data1);
                $(document).ready(function ()
                {
                    initMap(obj);
                });
                $('#booknow-map-marker').click();
            }

        });
    };

    this.getAirportBounds = function (lat, long)
    {
        var ctLat, ctLon, eastboundLat, eastboundLon, westboundLat, westboundLon;
        ctLat = lat;
        ctLon = long;

        eastboundLat = ctLat - 0.25;
        eastboundLon = ctLon - 0.25;
        westboundLat = ctLat - 0.0 + 0.25;//parseFloat
        westboundLon = ctLon - 0.0 + 0.25;

        var defaultBounds = new google.maps.LatLngBounds(
                new google.maps.LatLng(eastboundLat, eastboundLon),
                new google.maps.LatLng(westboundLat, westboundLon));

        return defaultBounds;
    };

    this.initAirportBounds = function (airportId)
    {
        if (airportId === '')
        {
            this.clear();
            this.model.options = null;
            this.disable();
            return;
        }
        var cityObj = new City();
        cityObj.getDetails(airportId, function (data)
        {
            var bounds = obj.getAirportBounds(data.coordinates.latitude, data.coordinates.longitude);
            var options = JSON.parse(obj.model.defaultOptions);
            options.bounds = bounds;
            obj.initControl(options);
        });
    };

    this.calculateArea = function (bounds)
    {
        var ne = new google.maps.LatLng(bounds.northeast.lat, bounds.northeast.lng); // LatLng of the north-east corner
        var sw = new google.maps.LatLng(bounds.southwest.lat, bounds.southwest.lng); // LatLng of the south-west corder
        var nw = new google.maps.LatLng(ne.lat(), sw.lng());
        var se = new google.maps.LatLng(sw.lat(), ne.lng());
        var length = this.calculateDistance(sw, nw);
        var breadth = this.calculateDistance(sw, se);
        var area = length * breadth;
        return area;
    };

    this.calculateDistance = function (location1, location2)
    {
        var lat1 = location1.lat();
        var lon1 = location1.lng();

        var lat2 = location2.lat();
        var lon2 = location2.lng();

        var R = 6371; // Radius of the earth in km
        var dLat = deg2rad(lat2 - lat1);
        var dLon = deg2rad(lon2 - lon1);
        var a =
                Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) *
                Math.sin(dLon / 2) * Math.sin(dLon / 2);
        var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        var d = R * c; // Distance in km
        return (d);

        function deg2rad(deg)
        {
            return deg * (Math.PI / 180);
        }
    };
    
     this.goSelect = function (var1)
    {
           alert(var1);   
       
    };
    
    
    this.getAddressForm = function ()
    {
    
        $('.modal').modal('hide');

        var mapContent = this.model.valueField.value;
        var lat = this.model.googlePlaceObject.geometry.location.lat();
        var long = this.model.googlePlaceObject.geometry.location.lng();
        var obj = JSON.parse(this.model.valueField.value);
        var placeAddress = obj.address;

        $.ajax({
            "type": "GET",
            "url": '/booking/addressForm',
            "data": {mapContent: mapContent, lat: lat, long: long, address: placeAddress},
            "dataType": "HTML",
            "success": function (data1)
            {
                $('#bkCommonModel').removeClass('fade');
                $('#bkCommonModel').css("display", "block");
                $('#bkCommonModelBody').html(data1);
                $('#bkCommonModel').modal('show');

            }

        });
    };
};

