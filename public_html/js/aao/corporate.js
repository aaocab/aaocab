/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var Corporate = function () {
    var model = {};
    
    this.getCorporateCode = function () {
        var model = this.model;
        $.ajax({
            "type": "GET",
            "dataType": "json",
            "url": $baseUrl + "/aaohome/corporate/corporateexist",
            data: model,
            success: function (data)
            {
                    model = data.data;
                    $(document).trigger("getCorporateCode", [data]);
                
            }
        });
    };
    }
       