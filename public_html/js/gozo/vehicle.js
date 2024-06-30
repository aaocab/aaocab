/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var Vehicle = function () {
var model = {};

this.getVehicleList = function () {
        var model = this.model;
        $.ajax({
            "type": "GET",
            "dataType": "json",
            "url": $baseUrl + "/aaohome/vehicle/getvehicle",
            data: model,
            success: function (data)
            {
                if (data.success)
                {
                    model = data.data;
                    $(document).trigger("getVehicleList", [data]);
                }
            },
            "error": function (error) {
                alert(error);
            }
        });
    };
    
    this.getDriverList = function () {
        var model = this.model;
        $.ajax({
            "type": "GET",
            "dataType": "json",
            "url": $baseUrl + "/aaohome/vehicle/getdriver",
            data: model,
            success: function (data)
            {
                if (data.success)
                {
                    model = data.data;
                    $(document).trigger("getDriverList", [data]);
                }
            },
            "error": function (error) {
                alert(error);
            }
        });
    };
}
       