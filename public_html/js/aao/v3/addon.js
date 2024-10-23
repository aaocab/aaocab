/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var Addon = function ()
{
		var model = {};

		this.applyAddon = function (addOnId, bkgId, addonType)
		{		//debugger;
				var event = 1;
				if(addOnId == 0){
						event = 8;
				}
				this.data = {"addonId": addOnId, "bkgId": bkgId,"addonType":addonType, "content": {
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
						{	//debugger;
                                                    if (data.success)
                                                    {
                                                        huiObj.bkgId = data.data.bookingId;
														if(data.hasOwnProperty("promo"))
														{
															prmObj = new Promotion(huiObj);
															prmObj.processData(data);
														}
														$(".addoncard-body").removeClass('active');
														$(".appladdonremove").removeClass('hide');
														$(".applydaddonremove").addClass('hide');
														$(".addoncard"+addOnId).addClass('active');
														$(".addons_app"+addOnId).removeClass('hide');
														$(".applremove"+addOnId).addClass('hide');
														let pattern = /-/;
														var addonCharge = (pattern.test(data.data.fare.addOnCharge))? Math.abs(data.data.fare.addOnCharge):data.data.fare.addOnCharge; 
														var minusSymbol = (pattern.test(data.data.fare.addOnCharge))?'(-)':'';
														if(addonCharge != 0)
														{
															$(".vwAddonCharge").removeClass('hide');
														}
														else
														{
															$(".vwAddonCharge").addClass('hide');
														}
														$('.displytxt').html('').next().removeClass('hide');
														$('.txtincludecp' + addOnId).text('Included in price');
														$('.addonsmargincp' + addOnId).addClass('hide');
													  //addonsmargin = $(".addonsmargin"+addOnId).text();
													  //$(".txtAddonLabel").html($('.addonslabel'+addOnId).text() + ': ' +minusSymbol+'&#x20B9;'+ addonsmargin);
														if(parseInt(addonType) == 1)
														{
															addonsmargin = $("#addonmargin"+addOnId).val();
															marginSymbol  = (pattern.test($("#addonsymbol"+addOnId).val()))?'(-)':'';
															$(".txtAddonLabel").removeClass('hide');
															$(".txtAddonLabel").html(data.data.addonLabel+':'+marginSymbol+'&#x20B9;'+ addonsmargin);
															$(".txtAddonCharge").html(minusSymbol+'&#x20B9;'+addonCharge);
															$(".applydaddons").html("Applied " + data.data.addonLabel +': '+marginSymbol+'&#x20B9;'+ addonsmargin);
														}
														if(parseInt(addonType) == 2)
														{
															addoncmmargins = $("#addoncmmargins"+addOnId).val();
															addonLabel = $('.addoncMlabel' + addOnId).text();
															marginSymbol  = (pattern.test($("#addoncmsymbol"+addOnId).val()))?'(-)':'';
															$(".txtCabModel").removeClass('hide');
															$(".txtCabModel").html(addonLabel+':'+marginSymbol+'&#x20B9;'+ addoncmmargins);
															$(".txtAddonCharge").html(minusSymbol+'&#x20B9;'+addonCharge);
															$(".applydcabmodeladdons").html("Applied " + addonLabel +': '+marginSymbol+'&#x20B9;'+ addoncmmargins);
														}
														$(".txtGstAmount").html(huiObj.moneyFormatter(data.data.fare.gst));
														$(".payBoxTotalAmount").html(huiObj.moneyFormatter(data.data.fare.totalAmount));
														$(".txtEstimatedAmount").html(huiObj.moneyFormatter(data.data.fare.totalAmount));
                                                    } else {
                                                      //  $(".showAddonDescApplied").html(data.errors[0]);
                                                       // $(".showAddonDescApplied").addClass('alert alert-danger');
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