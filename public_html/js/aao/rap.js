/* 
 * AutocompleteAddress
 */
var AutocompleteAddress = function()
{
	var model = {};
	model.url = "";
	model.blankKey = "";
	this.loadMapByPlaceId = function(value, apiKey)
	{
	var placeID = value;
		if (placeID)
		{

			$("#map").show();
			$("#map").html('<iframe width=\"100%\" style=\"border:0\" loading=\"lazy\"  allowfullscreen referrerpolicy=\"no-referrer-when-downgrade\" src="https://www.google.com/maps/embed/v1/place?key=' + apiKey + '&zoom=15&q=place_id:' + placeID + '"></iframe>');
			$("#placeId").val(placeID);



		}
	};

	this.getValue = function(pval, callback, city, sessionId,tvar)
	{
          
		if (pval.length > 1 && ((!pval.startsWith(model.blankKey) && model.blankKey != '') || model.blankKey == ''))
		{
			$.ajax({
				type: "GET",
				url: $baseUrl + '/lookup/getPredictions',
				data: {pval: pval, city: city, sessiontoken: sessionId},
				dataType: "json",
				global: false,
				error: function()
				{
					callback();
				},
				"success": function(res)
				{
					if (res.length < 1)
					{
						model.blankKey = pval;
						$("#map1").hide();
					}
					else
					{
						$("#map1").show();
						callback(res);
					}
				}
			});
		}
	};

	this.pacSubmit = function(sessID, placeID, callback ,rawText)
	{
       //  debugger;
		$.ajax({
			"type": "GET",
			// "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/getLatlngByPlaceId')) ?>",
			"url": $baseUrl + '/lookup/getLatlngByPlaceId',
			"data": {placeID: placeID, sessionId: sessID, rawText: rawText},
			"dataType": "json",
			global: false,
			error: function()
			{
				//callback();
			},
			"success": function(res)
			{
                         
                                let addressLineMaxSize =100;
				let mapAddress = JSON.parse(res.address);
				let address1 = $.trim($("#txtAddress1").val());
				let address2 = $.trim($("#txtAddress2").val());
                                
                                address1 = address1.substr(0,addressLineMaxSize);
                                address2 = address2.substr(0,addressLineMaxSize);

                                
				if (address1 != '')
				{
					mapAddress.address = address1 + ", " + mapAddress.address;
				}
				if (address2 != '')
				{
					//mapAddress.address = address2 + ", " + mapAddress.address;
                                         mapAddress.address =  mapAddress.address +' ('+address2+')';
				}
				callback(mapAddress);

			}
		});
	};
    this.addOption = function (control, val, apiKey, city)
    {
      //  debugger;
        var rap = this;
        if (val !== "")
        {

            var obj = $.parseJSON(val);
            if (obj[0].city == city)
            {
                control.addOption({
                    id: obj[0].placeId,
                    text: obj[0].addressMain
                });
                control.setValue([obj[0].placeId]);
                $("#placeId").val(obj[0].placeId);
                $("#txtAddress1").val(obj[0].address1);
                $("#txtAddress2").val(obj[0].address2);
                rap.loadMapByPlaceId(obj[0].placeId, apiKey);
            }
        }
    };

}
