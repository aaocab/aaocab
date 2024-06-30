/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var Route = function () {
var model = {};
this.getRouteList = function () {
        var model = this.model;
        $.ajax({
            
            "type": "GET",
            "dataType": "json",
            "url": $baseUrl + "/aaohome/route/getroutename",
            data: model,
            success: function (data)
            {
                if (data.success)
                {
                    model = data.data;
                    $(document).trigger("getRouteList", [data]);
                }
            },
            "error": function (error) {
                alert(error);
            }
        });
    };
    
    
    
}
       