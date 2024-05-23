/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/* global google */

var geocodeMarker = function (placeObj, bounds, container, callback)
{
    if (callback === undefined)
    {
        callback = function (obj)
        {

        };
    }

    this.model = {};
    this.model.placeObj = placeObj;
    this.model.container = container;
    this.model.bounds = bounds;
    this.model.googlePlaceObject = {};
    this.marker = null;
    this.error = "";
    var obj = this;
    this.initMap = function ()
    {
       
        var lat = 28.647883;
        var long = 77.128928;

        if (this.model.placeObj !== null && this.model.placeObj !== "")
        {
            lat = this.model.placeObj.coordinates.latitude;
            long = this.model.placeObj.coordinates.longitude;
        }
        if (lat==0 && long == 0 && this.model.bounds !== undefined && this.model.bounds !== null)
        {
            var center = bounds.getCenter();
            lat = center.lat();
            long = center.lng();
        }

        var myLatLng = new google.maps.LatLng(lat, long);
        var myOptions = {
            zoom: 15,
            center: myLatLng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        if (this.model.bounds !== undefined && this.model.bounds !== null)
        {
            myOptions.restriction = {
                latLngBounds: obj.model.bounds,
                strictBounds: true
            };
        }
        map = new google.maps.Map(obj.model.container, myOptions);
        this.marker = new google.maps.Marker({position: myLatLng, map: map, draggable: true});

        google.maps.event.addListener(this.marker, 'dragend', function ()
        {
            obj.geocodePosition(obj.marker.getPosition());
        });
    };

    this.geocodePosition = function (pos)
    {
        geocoder = new google.maps.Geocoder();
        geocoder.geocode({latLng: pos}, function (results, status)
        {
            if (status === google.maps.GeocoderStatus.OK)
            {
                var placeObj = {};
                this.error = "";
                placeObj.address = results[0].formatted_address;
                placeObj.place_id = results[0].place_id;
                placeObj.coordinates = {};
                placeObj.coordinates.latitude = results[0].geometry.location.lat();
                placeObj.coordinates.longitude = results[0].geometry.location.lng();
                obj.model.placeObj = placeObj;
                obj.model.googlePlaceObject = results[0];
                $(obj.model.container).trigger("positionChange", [obj]);
            }
            else
            {
                this.error = 'Cannot determine address at this location. ' + status;
            }

        }
        );
    };

    this.onPositionChange = function (func)
    {
        $(obj.model.container).on("positionChange", func);
    };
    this.setData = function ()
    {

    };
};