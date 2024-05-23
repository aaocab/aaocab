/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
BRObject = {
	object: {},
	init: function(widgetId, recreate)
	{
		if ((recreate !== undefined && recreate) || this.object[widgetId] == undefined)
		{
			this.object[widgetId] = new bookingRoute(widgetId);
		}
		return this.object[widgetId];
	},
	get: function(id)
	{
		if (this.object[id] != undefined)
		{
			return this.object[id];
		}
		return false;
	}

};
var bookingRoute = function(widgetId)
{
	this.model = {};
	this.model.id = widgetId;
	this.model.source = {};
	this.model.source.fetchUrl = "/lookup/citylist1";
	this.model.source.enable = true;
	this.model.destination = {};
	this.model.destination.fetchUrl = "/lookup/nearestcitylist";
	this.airportSource = false;
	this.airportDestination = false;
	this.airportURL = "/index/getairportcities";
	$('.selectize-control INPUT').attr('autocomplete', 'new-password');

	this.initSource = function(obj, value, enable = true)
	{
		this.model.source.selectObj = obj;
		if (this.airportSource)
		{
			this.model.source.fetchUrl = this.airportURL;
		}
		this.model.source.value = value;
		this.model.source.enable = enable;
		obj.on("loaded", function(selObj)
		{
			if (!enable)
			{
				selObj[0].lock();
			}
			else
			{
				selObj[0].unlock();
			}
		});
		this.populateSource(value);

	};

	this.disable = function(obj)
	{
		if (obj == null)
		{
			return;
		}
		obj.lock();
	};

	this.disableSource = function()
	{
		this.disable(this.model.source.selectObj);
	};

	this.disableDestination = function()
	{
		this.disable(this.model.destination.selectObj);
	};

	this.initDestination = function(obj, value, enable = true)
	{
		this.model.destination.selectObj = obj;
		if (this.airportDestination)
		{
			this.model.destination.fetchUrl = this.airportURL;
		}

		this.model.destination.value = value;
		this.model.destination.enable = enable;
	};
	this.populateSource = function(selectedValue)
	{
		this.model.source.value = selectedValue;
		this.populate(this.model.source);
	};

	this.changeSource = function(value)
	{
		this.model.source.value = value;
		if (value == "")
		{
			// this.model.destination.selectObj.clear();
			return;
		}
		this.populateDestination(this.model.destination.value);
	};
	this.changeDestination = function(value)
	{
		this.model.destination.value = value;
	};
	this.populateDestination = function(selectedValue)
	{
		if (this.model.destination.selectObj == null)
		{
			return;
		}
		this.model.destination.value = selectedValue;
		this.model.destination.source = this.model.source.selectObj.getValue();
		if (this.model.destination.source === "")
		{
			this.model.destination.selectObj.clear();
			this.model.destination.selectObj.disable();
			return;
		}
		this.populate(this.model.destination);
	};

	this.onLoad = function(data, typeObject)
	{
		if (data.length > 0)
		{
			typeObject.selectObj.enable();
			if(typeObject.value != '')
			{
				typeObject.selectObj.setValue(typeObject.value);
			}
		}
		typeObject.selectObj.trigger("loaded", [typeObject.selectObj]);
	};


	this.populate = function(obj)
	{
		var brObj = this;
		obj.selectObj.load(function(callback)
		{
			//this.clearOptions();
			brObj.load(callback, null, obj);

		});
	};

	this.load = function(callback, query, obj)
	{
		var data = {};
		if (obj.hasOwnProperty("value") && obj.value !== null)
		{
			data.city = obj.value;
		}
		if (obj.hasOwnProperty("source") && obj.source !== null)
		{
			data.source = obj.source;
		}

		if (query != null)
		{
			data.q = query;
		}

		xhr = $.ajax({
			url: obj.fetchUrl,
			dataType: 'json',
			data: data,
			success: function(results)
			{
				obj.data = results;
				callback(obj.data);
			},
			error: function()
			{
				callback(1);
			}
		});
	};
};
