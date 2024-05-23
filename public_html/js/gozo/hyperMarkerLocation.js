/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


var HyperMarkerLocation = function (attr)
{
	var model = {};
	model.ctLat = 0.0;
	model.ctLon = 0.0;
	model.ctELat = 0.0;
	model.ctELng = 0.0;
	model.ctWLat = 0.0;
	model.ctWLng = 0.0;
        this.apiKey  = attr;
        this.extDistSet = 0.0;
        this.extDistCheck = 0.0;
	this.autocomplete;
        this.marker = false;
        this.map;
        this.acInputs;
        this.centerCoordinates;
        this.infowindowContent;
        this.input;
        this.infowindow;
        this.card;
        this.myLatlng;
        this.geocoder;
        var csrfToken= '';
        this.lastLat;
        this.lastLong;
        this.lastAddress;
        this.icon = "https://maps.gstatic.com/mapfiles/place_api/icons/geocode-71.png";
        this.geocoder;
        this.isMobile = function () {
		return (this.Android() || this.BlackBerry() || this.iOS() || this.Opera() || this.Windows());
	};
	this.Android = function () {
		return navigator.userAgent.match(/Android/i);
	};
	this.BlackBerry = function () {
		return navigator.userAgent.match(/BlackBerry/i);
	};
	this.iOS = function () {
		return navigator.userAgent.match(/iPhone|iPad|iPod/i);
	};
	this.Opera = function () {
		return navigator.userAgent.match(/Opera Mini/i);
	};
	this.Windows = function () {
		return navigator.userAgent.match(/IEMobile/i);
	};
        
        this.showErrorMsg = function (content, title = false, bellmsg = false)
	{
		document.getElementById("notify_error").click();
		$("#noti_error_content").html(content);
		if (title) {
			$("#noti_error_title").html(title);
		}
		if (bellmsg) {
			$("#noti_error_bell_msg").html(bellmsg);
		}
		setTimeout(function () {
			$('#noti_error_bell_msg').click();
		}, 5000);
	};

	this.initializepl = function ()
	{
                var self = this;
		var model = this.model;
                this.card = document.getElementById('pac-card');
                this.input = document.getElementById('pac-input');
                this.infowindowContent = document.getElementById('infowindow-content');
		this.acInputs = document.getElementsByClassName(model.hyperLocationClass);
		var len = this.acInputs.length;
		var j = 0;
                this.geocoder = new google.maps.Geocoder();
		var eastboundLat = 0.0;
		var eastboundLon = 0.0;
		var westboundLat = 0.0;
		var westboundLon = 0.0;
                var latlongdiff = 0.00;
		var pluslatlongdiff = latlongdiff;
		var minuslatlongdiff = 0.0 - latlongdiff;
		var boundObj = null;
                
                this.extDistSet =  model.airport == 1 ? 0.70 : 0.05;
                this.extDistCheck =  model.airport == 1 ? 9.0 : 0.05;
              
                 /*Marker Initialize */
                this.centerCoordinates = new google.maps.LatLng(model.ctyLat, model.ctyLon);
                var mapOptions = {
                    zoom: 18,
                    center: this.centerCoordinates,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                };
                this.map = new google.maps.Map(document.getElementById('map'),mapOptions);

                this.marker = new google.maps.Marker({
                    map: this.map,
                    position: this.centerCoordinates,
                    draggable: true 
                });

                //this.markerLocation(model.ctyLat, model.ctyLon);
                this.geocoder.geocode({'latLng': this.centerCoordinates }, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[0]) {
                            var isAirport = 0;
                            if(results[0].types.indexOf("airport") > -1)
                            {
                                isAirport = 1;
                            }
                            self.infowindowContent.children['place-icon'].src = "https://maps.gstatic.com/mapfiles/place_api/icons/geocode-71.png";
                            self.infowindowContent.children['place-name'].textContent = results[0].formatted_address;
                            self.infowindow.open(self.map, self.marker);
                            document.getElementById('pac-input').value = results[0].formatted_address;
                            document.getElementById('plc-add').value = results[0].formatted_address;
                            document.getElementById('plc-lat').value = self.marker.getPosition().lat();
                            document.getElementById('plc-long').value = self.marker.getPosition().lng();
                            document.getElementById('plc-plcid').value = results[0].place_id;
                            document.getElementById('plc-types').value = JSON.stringify(results[0].types);
                            document.getElementById('plc-isAirport').value = isAirport;
                            self.lastLat = self.marker.getPosition().lat();
                            self.lastLong = self.marker.getPosition().lng();
                            self.lastAddress = results[0].formatted_address;
                        }
                    }
                });
                
                this.loadMarker();
                 /* Auto complete Initialize */
                $(this.acInputs[j].id).attr("autocomplete", "disabled");
                
                if(model.bound != '' && model.bound != null && model.bound != undefined)
                {
                    boundObj = model.bound;
                }
                if (boundObj != null)
                {
                        if (boundObj.northeast.lat > 0)
                        {
                                latLngType = 1;
                        } else
                        {
                                latLngType = 2;
                        }
                } else
                {
                        latLngType = 0;
                }
                if (latLngType == 1)
                {
                        westboundLat = boundObj.northeast.lat - minuslatlongdiff;
                        westboundLon = boundObj.northeast.lng - minuslatlongdiff;
                        eastboundLat = boundObj.southwest.lat - pluslatlongdiff;
                        eastboundLon = boundObj.southwest.lng - pluslatlongdiff;
                } else if (latLngType == 2)
                {
                        ctLat = model.ctyLat;
                        ctLon = model.ctyLon;

                        eastboundLat = ctLat - this.extDistSet;
                        eastboundLon = ctLon - this.extDistSet;
                        westboundLat = ctLat - 0.0 + this.extDistSet;//parseFloat
                        westboundLon = ctLon - 0.0 + this.extDistSet;

                        boundObj.northeast.lat = westboundLat;
                        boundObj.northeast.lng = westboundLon;
                        boundObj.southwest.lat = eastboundLat;
                        boundObj.southwest.lng = eastboundLon;

                        model.bound = boundObj;
                } else
                {
                        ctLat = model.ctyLat;
                        ctLon = model.ctyLon;

                        eastboundLat = ctLat - this.extDistSet;
                        eastboundLon = ctLon - this.extDistSet;
                        westboundLat = ctLat - 0.0 + this.extDistSet;//parseFloat
                        westboundLon = ctLon - 0.0 + this.extDistSet;
                }
                var defaultBounds = new google.maps.LatLngBounds(
                                new google.maps.LatLng(eastboundLat, eastboundLon),
                                new google.maps.LatLng(westboundLat, westboundLon));
                var options = {
                        types: [],
                        bounds: defaultBounds,
                        strictBounds: 1,
                        componentRestrictions: {country: 'IN'}
                };

                if (model.isCtyAirport != 1 || model.isCtyPoi != 1)
                {
                        this.autocomplete = new google.maps.places.Autocomplete(this.acInputs[j], options);
                        $("." + model.hyperLocationClass).attr("autocomplete", "disabled");
                        this.autocomplete.inputId = this.acInputs[j].id;
                        this.infowindow = new google.maps.InfoWindow();
                        this.infowindow.setContent(this.infowindowContent);
                        this.loadAddress(this.acInputs[j].id, this.autocomplete);
                }
	};
        
        /* Changes on 13/01/2020 By Chiranjit Hazra*/     
	this.loadAddress = function (placeBoxId, autocomplete)
	{
                var self = this;
		var model = this.model;
		var boundObj;
                var msg = '';
		autocomplete = autocomplete == undefined ? this.autocomplete : autocomplete;
		google.maps.event.addListener(autocomplete, 'place_changed', function ()
		{
                    var isAirport = 0;
                    var error = 0;
                    var place = autocomplete.getPlace();
                    var placeTypes = place.types;
                    if(model.airport == 1)
                    {
                        var notSpecificArea = ["locality","administrative_area_level_1","administrative_area_level_2","administrative_area_level_3","country"];
                        var stopExecution = 0;
                        if(place.types != undefined)
                        {
                            for (var j = 0; j < place.types.length ; j++)
                            {
                                if (place.types[j] == "airport") {
                                  isAirport = 1;
                                }
                                if(notSpecificArea.includes(place.types[j]))
                                {
                                    stopExecution = 1;
                                    break;
                                }
                            }
                        }
                        if(stopExecution == 1)
                        {
                            msg = "Please select specific address or area within a city.";
                            error +=1;
                           boundObj = null;
                        }
                        else
                        {
                            bound = '{"northeast":{"lat":' + model.ctyLat + ',"lng":' + model.ctyLon + '},"southwest":{"lat":' + model.ctyLat + ',"lng":' + model.ctyLon + '}}';
                            boundObj = JSON.parse(bound);
                        }
                    }
                    else
                    {
                        boundObj = model.bound;
                    }
                    if(boundObj) 
                    {
//			if (((boundObj.northeast.lat - 0.0 + self.extDistCheck) >= place.geometry.location.lat() && place.geometry.location.lat() >= (boundObj.southwest.lat - self.extDistCheck)) &&
//					((boundObj.northeast.lng - 0.0 + self.extDistCheck) >= place.geometry.location.lng() && place.geometry.location.lng() >= (boundObj.southwest.lng - self.extDistCheck)))
//			{
                            self.map.fitBounds(place.geometry.viewport);
                            if(self.marker == false)
                            {
                                self.marker = new google.maps.Marker({
                                  map: self.map,
                                  position: place.geometry.location,
                                  draggable: true 
                              });
                            }
                            self.marker.setPosition(place.geometry.location);
                            self.marker.setVisible(true);

                            self.infowindowContent.children['place-icon'].src = place.icon;
                            self.infowindowContent.children['place-name'].textContent = place.formatted_address;
                            self.infowindow.open(self.map, self.marker);
                            document.getElementById('plc-add').value = place.formatted_address;
                            document.getElementById('plc-lat').value = place.geometry.location.lat();
                            document.getElementById('plc-long').value = place.geometry.location.lng();
                            document.getElementById('plc-plcid').value =place.place_id;
                            document.getElementById('plc-types').value = JSON.stringify(place.types);
                            document.getElementById('plc-isAirport').value =isAirport;
                            self.lastLat = place.geometry.location.lat();
                            self.lastLong = place.geometry.location.lng();
                            self.lastAddress = place.formatted_address;

//			} else if(error == 0)
//                        {
//                            msg = "The location is far off from the city. Please enter correct location address.";
//                            error +=1;
//                        }
                    }
                    
                    if(error > 0)
                    {
                        if(self.isMobile())
                        {
                            self.showErrorMsg(msg);
                        }
                        else
                        {
                             alert(msg);
                        }
                        self.setPrevLocation();
                    }
		});
	};
        
	this.findAddress = function (id)
	{
            var placeBoxId = {};
            placeBoxId.inputId = id;
            this.autocomplete = placeBoxId;
            this.loadAddress(id);
	};
        
        this.loadMarker = function ()
        {
            var self = this;
            google.maps.event.addListener(self.marker, 'dragend', function(event){
                //self.markerLocation();
                self.geocoder.geocode({'latLng': self.marker.getPosition()}, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[0]) {
                            var isAirport = 0;
                            if(results[0].types.indexOf("airport") > -1)
                            {
                                isAirport = 1;
                            }
                            self.infowindowContent.children['place-icon'].src = "https://maps.gstatic.com/mapfiles/place_api/icons/geocode-71.png";
                            self.infowindowContent.children['place-name'].textContent = results[0].formatted_address;
                            self.infowindow.open(self.map, self.marker);
                            document.getElementById('pac-input').value = results[0].formatted_address;
                            document.getElementById('plc-add').value = results[0].formatted_address;
                            document.getElementById('plc-lat').value = self.marker.getPosition().lat();
                            document.getElementById('plc-long').value = self.marker.getPosition().lng();
                            document.getElementById('plc-plcid').value = results[0].place_id;
                            document.getElementById('plc-types').value = JSON.stringify(results[0].types);
                            document.getElementById('plc-isAirport').value = isAirport;
                            self.lastLat = self.marker.getPosition().lat();
                            self.lastLong = self.marker.getPosition().lng();
                            self.lastAddress = results[0].formatted_address;
                        }
                    }
                });
            });
        };
        
        /* Changes on 13/01/2020 By Chiranjit Hazra*/
        
        this.markerLocation = function (lat, long)
        {
            if((lat == '' || lat == undefined) && (long == '' || long == undefined))
            {
                var currentLocation = this.marker.getPosition();
                this.myLatlng  = currentLocation.lat() + ',' + currentLocation.lng();
            }
            else
            {
                this.myLatlng  = lat + ',' + long;
            }
            var msg = '';
            var self = this;
            var xhttp = new XMLHttpRequest();
            var boundObj;
            var isAirport = 0;
            var model = this.model;
            var url = "https://maps.googleapis.com/maps/api/geocode/json?latlng="+ self.myLatlng +"&key="+ self.apiKey +"&sensor=false";
            xhttp.onreadystatechange = function() {
              if (this.readyState == 4 && this.status == 200) {
                var result = JSON.parse(this.responseText);
                if(model.bound != '' && model.bound != null && model.bound != undefined)
                {
                    boundObj = model.bound;
                }
                else
                {
                    bound = '{"northeast":{"lat":' + model.ctyLat + ',"lng":' + model.ctyLon + '},"southwest":{"lat":' + model.ctyLat + ',"lng":' + model.ctyLon + '}}';
                    boundObj = JSON.parse(bound);
                }
                if(boundObj) 
                {
//                    if (((boundObj.northeast.lat - 0.0 + self.extDistCheck) >= result.results[0].geometry.location.lat && result.results[0].geometry.location.lat >= (boundObj.southwest.lat - self.extDistCheck)) &&
//                                    ((boundObj.northeast.lng - 0.0 + self.extDistCheck) >= result.results[0].geometry.location.lng && result.results[0].geometry.location.lng >= (boundObj.southwest.lng - self.extDistCheck)))
//                    {
                        self.infowindowContent.children['place-icon'].src = "https://maps.gstatic.com/mapfiles/place_api/icons/geocode-71.png";
                        self.infowindowContent.children['place-name'].textContent = result.results[0].formatted_address;
                        self.infowindow.open(self.map, self.marker);
                        document.getElementById('pac-input').value = result.results[0].formatted_address;
                        document.getElementById('plc-add').value = result.results[0].formatted_address;
                        document.getElementById('plc-lat').value = result.results[0].geometry.location.lat;
                        document.getElementById('plc-long').value = result.results[0].geometry.location.lng;
                        document.getElementById('plc-plcid').value = result.results[0].place_id;
                        document.getElementById('plc-types').value = JSON.stringify(result.results[0].types);
                        document.getElementById('plc-isAirport').value =isAirport;
                        self.lastLat = result.results[0].geometry.location.lat;
                        self.lastLong = result.results[0].geometry.location.lng;
                        self.lastAddress = result.results[0].formatted_address;
//                    } 
//                    else
//                    {
//                        msg = "The location is far off from the city. Please enter correct location address.";
//                        if(self.isMobile())
//                        {
//                            self.showErrorMsg(msg);
//                        }
//                        else
//                        {
//                             alert(msg);
//                        }
//                        self.setPrevLocation();
//                        self.loadMarker();
//                    }
                }
              }
            };
            xhttp.open("GET", url , true);
            xhttp.send();
        };
        
        this.setPrevLocation = function ()
        {
            var self = this;
            self.marker.setVisible(false);
            self.marker = new google.maps.Marker({
                map: self.map,
                position: {"lat":self.lastLat, "lng":self.lastLong},
                draggable: true 
            });
            self.marker.setPosition({"lat":self.lastLat, "lng":self.lastLong});
            self.marker.setVisible(true);

            self.infowindowContent.children['place-icon'].src = self.icon;
            self.infowindowContent.children['place-name'].textContent = self.lastAddress;
            self.infowindow.open(self.map, self.marker);
        };
        
}