/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var Addon = function ()
{
		var model = {};

		this.applyAddon = function (addOnId, bkgId)
		{
				var event = 7;
				if(addOnId == 0){
						event = 8;
				}
				this.data = {"addonId": addOnId, "bkgId": bkgId, "content": {
								"bookingId": bkgId,
								"promo": {"code": huiObj.additionalParams.code},
								"gozoCoins": huiObj.additionalParams.coins,
								"wallet": huiObj.additionalParams.wallet,
								"eventType": event,
						}, "YII_CSRF_TOKEN": this.getToken()};


				$.ajax({
						"url": $baseUrl + "/booking/applyaddon",
						"type": "POST",
						"dataType": "json",
						"data": this.data,
						"success": function (data)
						{
                                                    if (data.success)
                                                    {
                                                        huiObj.bkgId = data.data.bookingId;
                                                        prmObj = new Promotion(huiObj);
                                                        prmObj.processData(data);
                                                        if(data.data.eventType == 8 )
                                                        {
                                                           $(".spanAddonCreditSucc").removeClass("alert-success"); 
                                                        }
                                                        else
                                                        {
                                                           $(".spanAddonCreditSucc").addClass("alert-success");   
                                                        } 
                                                        $(".spanAddonCreditSucc").removeClass('hide');
                                                        $(".spanAddonCreditSucc").html(data.message);
                                                        $(".txtAddonCharge").html(data.data.addonLabel);
                                                    } else {
                                                        $(".showAddonDescApplied").html(data.errors[0]);
                                                        $(".showAddonDescApplied").addClass('alert alert-danger');
                                                    }
						},
						"error": function (error)
						{
								alert(error);
						}
				});
		};

		this.getToken = function ()
		{
				return $("input[name='YII_CSRF_TOKEN']").val();
		};
}