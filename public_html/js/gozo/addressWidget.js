/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

AWObject = {
    object: {},
    init: function (widgetId, cityId, recreate)
    {
        if ((recreate !== undefined && recreate) || this.object[widgetId] == undefined)
        {
            this.object[widgetId] = new addressWidget(widgetId, cityId);
        }
        return this.object[widgetId];
    },
    get: function (id)
    {
        if (this.object[id] != undefined)
        {
            return this.object[id];
        }
        return false;
    }

};


var addressWidget = function (id, cityId)
{
    this.model = {};
    this.model.id = id;
    this.model.input = document.getElementById(id);
    this.model.container = ".PAWidget.PAW" + this.model.id;
    this.model.city = cityId;
    var obj = this;

    this.getContainer = function ()
    {
        return $(this.model.container);
    };

    this.addSelect = function (selectObject)
    {
        this.model.selectField = selectObject;
    };
    this.goSelect = function (cityDesc)
    {
      alert("kjuhhu");
       // alert($.parseJSON(cityDesc));
    
        $('#'+cityDesc.divValue).text(cityDesc.text);
        $('#'+cityDesc.divValue).attr("data-value", cityDesc.id);
        $('.modal').modal('hide');
    };
    this.goNewSelect = function (cityDesc,divValue)
    {
       
        var arr = $.parseJSON(cityDesc);
        $('#'+divValue).text(arr.address);
        $('#'+divValue).attr("data-value", cityDesc);
        $('.modal').modal('hide');
    };
    this.addPAC = function (pacObject)
    {
        this.model.PACObject = pacObject;
        this.getContainer().find(".PAWToggleLink").unbind("click").on("click", function (e)
        {
            obj.toggleControl(this);
        });
    };

    this.model.setCity = function (city)
    {
        this.model.city = city;
        this.model.selectField.load(function (callback)
        {
            obj.loadSelect("", callback);
        });
    };
    
    this.setValue = function(value){
        this.model.input.value=value;
        $('.pickUpAddress').val(value);
    };

    this.getPACObject = function ()
    {
        return this.model.PACObject;
    };

    this.getSelectObject = function ()
    {
        return this.model.selectField;
    };

    this.hasData = function ()
    {
        if (this.getContainer().find(".PAWExisting").hasClass("hide"))
        {
            if (this.getContainer().find(".PAWNew").find("#"+this.model.id).val() == "")
                return false;
        }
        if (this.getContainer().find(".PAWNew").hasClass("hide"))
        {
            if (this.getSelectObject().getValue() == "")
                return false;
        }
        return true;

    };

    this.focus = function ()
    {
        if (this.getContainer().find(".PAWExisting").hasClass("hide"))
        {
            this.getPACObject().model.displayField.focus();
        }
        if (this.getContainer().find(".PAWNew").hasClass("hide"))
        {
            this.getSelectObject().focus();
        }

    };

    this.toggleControl = function (obj)
    {
        var type = $(obj).attr("data-val");
        if (type === "1")
        {
            this.getContainer().find(".PAWExisting").addClass("hide");
            this.getContainer().find(".PAWNew").removeClass("hide");
        }
        else if (type === "2")
        {
            this.getContainer().find(".PAWExisting").removeClass("hide");
            this.getContainer().find(".PAWNew").addClass("hide");
        }
    };

    this.loadSelect = function (query, callback)
    {
        $.ajax({
            url: $baseUrl + '/user/getAddress/city/' + this.model.city + '?q=' + encodeURIComponent(query),
            type: 'GET',
            dataType: 'json',
            error: function ()
            {
                callback();
            },
            success: function (res)
            {
                callback(res);
            }
        });
    };
  

}