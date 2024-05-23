/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var Driver = function () {
var model = {};

this.getDriverState = function () {
        var model = this.model;
        $.ajax({
            "type": "GET",
            "dataType": "json",
            "url": $baseUrl + "/admpnl/driver/cityfromstate1",
            data: model,
            success: function (data)
            {
                if (data.success)
                {
                    model = data.data;
                    $(document).trigger("getDriverState", [data]);
                }
            },
            "error": function (error) {
                alert(error);
            }
        });
    };
    
    this.getCityStateList = function () {
        var model = this.model;
        $.ajax({
            "type": "GET",
            "dataType": "json",
            "url": $baseUrl + "/admpnl/driver/cityfromstate1",
            data: model,
            success: function (data)
            {
                if (data.success)
                {
                    model = data.data;
                    $(document).trigger("getCityStateList", [data]);
                }
            },
            "error": function (error) {
                alert(error);
            }
        });
    };
}
       