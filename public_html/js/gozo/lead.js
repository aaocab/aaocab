/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var City = function () {
var model = {};
this.getCityList = function () {
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
                    $(document).trigger("getCityList", [data]);
                }
            },
            "error": function (error) {
                alert(error);
            }
        });
    };
    
    this.getRouteListbyCities = function () {
        var model = this.model;
        alert(model);
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
            "error": function (error) {
                alert(error);
            }
        });
    };
}
       