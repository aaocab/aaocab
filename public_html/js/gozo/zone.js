/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var Zone = function () {
var model = {};

this.getCityZoneList = function () {
        var model = this.model;
        $.ajax({
            "type": "GET",
            "dataType": "json",
            "url": $baseUrl + "/admpnl/zone/getcityzone",
            data: model,
            success: function (data)
            {
                if (data.success)
                {
                    model = data.data;
                    $(document).trigger("getCityZoneList", [data]);
                }
            },
            "error": function (error) {
                alert(error);
            }
        });
    };
    
    this.getVendorCity= function () {
        var model = this.model;
        $.ajax({
            "type": "GET",
            "dataType": "json",
            "url": $baseUrl + "/admpnl/zone/getvendorcityzone",
            data: model,
            success: function (data)
            {
                if (data.success)
                {
                    model = data.data;
                    $(document).trigger("getVendorCity", [data]);
                }
            },
            "error": function (error) {
                alert(error);
            }
        });
    };
}
       