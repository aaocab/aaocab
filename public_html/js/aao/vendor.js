/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var Vendor = function () {
var model = {};

    this.getZoneCities= function () {
        var model = this.model;
        $.ajax({
            "type": "GET",
            "dataType": "json",
            "url": $baseUrl + "/aaohome/vendor/zonecity",
            data: model,
            success: function (data)
            {
                if (data.success)
                {
                    model = data.data;
                    $(document).trigger("getZoneCities", [data]);
                }
            },
            "error": function (error) {
                alert(error);
            }
        });
    };
}
       