/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$citySourceList = null;
var City = function ()
{
    var model = {};
    var multiple = true;
    this.getCityList = function ()
    {
        var model = this.model;
        $.ajax({
            "type": "GET",
            "dataType": "json",
            async: false,
            "url": $baseUrl + "/admpnl/city/getnames",
            data: model,
            success: function (data)
            {
                if (data.success)
                {
                    model = data.data;
                    $(document).trigger("getCityList", [data]);
                }
            },
            "error": function (error)
            {
                alert(error);
            }
        });
    };

    this.getRouteListbyCities = function ()
    {
        var model = this.model;
        $.ajax({
            "type": "GET",
            "dataType": "json",
            "url": $baseUrl + "/admpnl/city/selectcities",
            data: model,
            success: function (data)
            {
                if (data.success)
                {
                    model = data.data;
                    $(document).trigger("getRouteListbyCities", [data]);
                }
            },
            "error": function (error)
            {
                alert(error);
            }
        });
    };

    this.getLatitudeLongitude = function ()
    {
        var model = this.model;
        $.ajax({
            "type": "GET",
            "dataType": "json",
            "url": $baseUrl + "/admpnl/city/updateLatLongByAddress",
            data: model,
            success: function (data)
            {
                if (data.success)
                {
                    model = data.data;
                    $(document).trigger("getLatitudeLongitude", [data]);
                }
            },
            "error": function (error)
            {
                alert(error);

            }
        });
    };

    this.checkCityName = function ()
    {
        var model = this.model;
        $.ajax({
            "type": "GET",
            "dataType": "json",
            "url": $baseUrl + "/admpnl/city/checkcityname",
            data: model,
            success: function (data)
            {
                if (data.success)
                {
                    model = data.data;
                    $(document).trigger("checkCityName", [data]);
                }
            },
            "error": function (error)
            {
                alert(error);
            }
        });
    };

    this.getRouteDistTime = function ()
    {
        var model = this.model;
        $.ajax({
            "type": "GET",
            "dataType": "json",
            "url": $baseUrl + "/admpnl/city/updateRouteDistTime",
            data: model,
            success: function (data)
            {
                if (data.success)
                {
                    model = data.data;
                    $(document).trigger("getRouteDistTime", [data]);
                }
            },
            "error": function (error)
            {
                alert(error);
            }
        });
    };

    this.getRouteName = function ()
    {
        var model = this.model;
        $.ajax({

            "type": "GET",
            "dataType": "json",
            "url": $baseUrl + "/admpnl/route/routename",
            data: model,
            success: function (data)
            {
                if (data.success)
                {
                    model = data.data;
                    $(document).trigger("getRouteName", [data]);
                }
            },
            "error": function (error)
            {
                alert(error);
            }
        });
    };

    this.getCitiesName = function ()
    {
        var model = this.model;
        $.ajax({

            "type": "GET",
            "dataType": "json",
            "url": $baseUrl + "/admpnl/city/getnames",
            data: model,
            success: function (data)
            {
                if (data.success)
                {
                    model = data.data;
                    $(document).trigger("getCitiesName", [data]);
                }
            },
            "error": function (error)
            {
                alert(error);
            }
        });
    };

    this.showArea = function ()
    {
        var model = this.model;

        if (model.area === '1')
        {
            model.url = $baseUrl + '/admpnl/zone/getZoneList';
        } else if (model.area === '2')
        {
            model.url = $baseUrl + '/admpnl/state/getStateList';
        } else if (model.area === '3')
        {
            model.url = $baseUrl + '/admpnl/city/getCityList';
        } else
        {
            model.url = $baseUrl + '/admpnl/promos/getRegionList';
        }
        $.ajax({

            "type": "GET",
            "dataType": "json",
            "url": model.url,
            success: function (data)
            {
                $('#' + model.id).select2({data: data, multiple: true});
                if (model.multiple == false) {
                    $('#' + model.id).select2({data: data});
                }
            },
            "error": function (error)
            {
                alert(error);
            }
        });

    };

    this.populateSourceForSelectize = function (obj, cityId) {

        obj.load(function (callback) {
            var obj = this;

            if ($citySourceList == null || cityId > 0) {
                xhr = $.ajax({
                    url: $baseUrl + '/lookup/citylist1',
                    dataType: 'json',
                    data: {
                        city: cityId
                    },
                    async: false,
                    success: function (results) {
                        $citySourceList = results;
                        obj.enable();
                        callback($citySourceList);
                        obj.setValue('');
                    },
                    error: function () {
                        callback();
                    }
                });
            } else {
                obj.enable();
                callback($citySourceList);
                obj.setValue('');
            }
        });
    };

    this.loadSourceForSelectize = function (query, callback) {

        $.ajax({
            url: $baseUrl + '/lookup/citylist1?q=' + encodeURIComponent(query),
            type: 'GET',
            dataType: 'json',
            async: false,
            error: function () {
                callback();
            },
            success: function (res) {
                callback(res);
            }
        });
    };

    this.changeDestinationForSelectize = function (value, obj) {

        if (!value.length)
            return;

        if (obj && obj != null && obj != undefined) {
            var existingValue = obj.getValue();
            if (existingValue == '')
            {
                existingValue = '';
            }
            obj.disable();
            obj.clearOptions();
            obj.load(function (callback) {
                //  xhr && xhr.abort();
                xhr = $.ajax({
                    url: $baseUrl + '/lookup/nearestcitylist/source/' + value,
                    dataType: 'json',
                    async: false,
                    success: function (results)
                    {
                        obj.enable();
                        callback(results);
                        obj.setValue(existingValue);
                    },
                    error: function () {
                        callback();
                    }
                });
            });
        }
    };

    this.getRouteDetailsBetweenCity = function (fcity, tcity, callback) {
        $.ajax({
            url: $baseUrl + '/lookup/routedetails',
            dataType: 'json',
            data: {
                fcity: fcity,
                tcity: tcity
            },
            success: function (results)
            {
                //obj.enable();
                callback(results);
                //obj.setValue(existingValue);
            },
            error: function () {
                callback();
            }
        });
    };


    this.populateAllCityForSelectize = function (obj, cityId) {

        obj.load(function (callback) {
            var obj = this;

            if ($citySourceList == null || cityId > 0) {
                xhr = $.ajax({
                    url: $baseUrl + '/lookup/allcitylistbyquery?apshow=1&city=' + encodeURIComponent(cityId),
                    dataType: 'json',
                    data: {
                    },
                    async: false,
                    success: function (results) {
                        $citySourceList = results;
                        obj.enable();
                        callback($citySourceList);
                        obj.setValue('');
                    },
                    error: function () {
                        callback();
                    }
                });
            } else {
                obj.enable();
                callback($citySourceList);
                obj.setValue('');
            }
        });
    };

    this.loadAllCityForSelectize = function (query, callback) {
        $.ajax({
            url: $baseUrl + '/lookup/allcitylistbyquery?apshow=1&q=' + encodeURIComponent(query),
            type: 'GET',
            dataType: 'json',
            async: false,
            error: function () {
                callback();
            },
            success: function (res) {
                callback(res);
            }
        });
    };

    this.getDetails = function (id, callback) {
        $.ajax({
            url: $baseUrl + '/api/city/getdetails?cid=' + encodeURIComponent(id),
            type: 'GET',
            dataType: 'json',
            async: false,
            error: function () {
                callback();
            },
            success: function (res) {
                if (res.success)
                {
                    callback(res.data);
                }
            }
        });
    };
	
	this.showAreaForNotes = function ()
    {
        var model = this.model;
		$('#DestinationNote_dnt_area_id1').val('').trigger('change');
		$("#DestinationNote_dnt_area_id2")[0].selectize.clear();
        if (model.area === '1')
        {
            model.url = $baseUrl + '/admpnl/zone/getZoneList';
        } else if (model.area === '2')
        {
            model.url = $baseUrl + '/admpnl/state/getStateList';
        } else if (model.area === '3')
        {            
			$('#get_area2').show();
			$('#get_area1').hide();
        } else
        {
            model.url = $baseUrl + '/admpnl/promos/getRegionList';
        }
		
		if (model.area !== '3') 
		{
			$('#get_area1').show();
			$('#get_area2').hide();
			$.ajax({
				"type": "GET",
				"dataType": "json",
				"url": model.url,
				success: function (data)
				{                
					$('#DestinationNote_dnt_area_id1').select2({data: data, multiple: true});
					if (model.multiple == false) {
						$('#DestinationNote_dnt_area_id1').select2({data: data});
					}				
				},
				"error": function (error)
				{
					alert(error);
				}
			});
		}
    };
	
}

