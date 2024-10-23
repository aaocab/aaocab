/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Promotion
{
    constructor(huiObj)
    {
        this.coins = 0;
        this.code = "";
        this.eventType = 0;
        this.wallet = 0;

        this.bkgId = huiObj.bkgId;
        this.token = huiObj.getToken();
        this.huiObj = huiObj;
    }

    getParams()
    {
        this.data = {"content": {
                "bookingId": this.bkgId,
                "promo": {"code": this.code},
                "gozoCoins": this.coins,
                "wallet": this.wallet,
                "eventType": this.eventType,
            }, "YII_CSRF_TOKEN": this.token};
        return this.data;
    }

    process()
    {
        $.ajax({
            "type": "POST",
            "url": $baseUrl + "/booking/applypromo",
            "data": this.getParams(),
            "dataType": "json",
            "beforeSend": function ()
            {
                blockForm($('#bookingdiscount'));
            },
            "complete": function ()
            {
                unBlockForm($('#bookingdiscount'));
            },
            "success": function (data)
            {
                if (data.success)
                {
                    huiObj.bkgId = data.data.bookingId;
                    prmObj = new Promotion(huiObj);
                    prmObj.processData(data);
                } else
                {
                    huiObj.setErrors(data);
                }
            },
            "error": function (error)
            {
                alert(error);
            }
        });
    }

    applyParams(event, value)
    {
        prmObj = this;
        prmObj.eventType = event;
        prmObj.code = huiObj.additionalParams.code;
        prmObj.coins = huiObj.additionalParams.coins;
        prmObj.wallet = huiObj.additionalParams.wallet;
        switch (event)
        {
            case 1:
                prmObj.code = value;
                break;
            case 2:
                prmObj.code = "";
                break;
            case 3:
                prmObj.coins = value;
                break;
            case 4:
                prmObj.coins = 0;
                break;
            case 5:
                prmObj.wallet = value;
                break;
            case 6:
                prmObj.wallet = 0;
                break;

        }
        return this.getParams();
    }

    applyPromo(event, value)
    {
        huiObj.hidePromoCoinsError();
        this.applyParams(event, value);
        prmObj.process(this);
    }

    processData(data)
    {   
        if (data.success)
        {
            prmObj.eventType = data.data.eventType;
            switch (data.data.eventType)
            {
                case '1':
                    huiObj.promoAppllied(data);
                    prmObj.code = huiObj.additionalParams.code;
                    if(prmObj.isEmpty(prmObj.code) == false)
                    {
                         $(".showPromoError").text("Please enter promo code");
                         $(".showPromoError").show();
                    }
                    break;
                case '2':
                    huiObj.promoRemoved(data);
                    prmObj.code = "";
                    break;
                case '3':
                    huiObj.creditApplied(data);
                    prmObj.coins = huiObj.additionalParams.coins;
                    if(prmObj.isEmpty(prmObj.coins) == false)
                    {
                         $(".showCoinsError").text("Please enter Gozo coins");
                         $(".showCoinsError").show();
                    }
                    break;
                case '4':
                    huiObj.creditRemoved(data);
                    prmObj.coins = 0;
                    break;
                case '5':
                    huiObj.walletApplied(data);
                    prmObj.wallet = huiObj.additionalParams.wallet;
                    break;
                case '6':
                    huiObj.walletRemoved(data);
                    prmObj.wallet = 0;
                    break;
            }
            huiObj.updateInvoice(data);
        } else
        {
            huiObj.setErrors(data);
        }
    }

    processWallet()
    {        
        this.data = {"content": {
                "bookingId": this.bkgId,
                "wallet": this.wallet,
                "eventType": this.eventType,
            }, "YII_CSRF_TOKEN": this.token};

        $.ajax({
            "type": "POST",
            "url": $baseUrl + "/booking/applyWallet",
            "data": this.data,
            "dataType": "json",
            "beforeSend": function () {
                ajaxindicatorstart("");
            },
            "complete": function () {
                ajaxindicatorstop();
            },
            "success": function (data)
            {
               
                if (data.success)
                {
                    if(data.walletUsed > 0)
                    {
                        let dueAmt= 0;
                        let payCheck = $("input[name='payChk']:checked").val();
                       
                        let price0 = $(".spanPrice0").text().substring(1);
                        let price1 = $(".spanPrice1").text().substring(1);
                        let price2 = $(".spanPrice2").text().substring(1);
                        price0 = price0.replace(/,/g, '');
                        price1 = price1.replace(/,/g, '');
                        price2 = price2.replace(/,/g, '');
                     
                        if (payCheck === 0)
                        {
                            dueAmt = price0 - data.walletUsed;
                        } else if (payCheck === 1)
                        {
                            dueAmt = price1 - data.walletUsed;
                        } else
                        {
                            dueAmt = price2 - data.walletUsed;
                        }
                     
                        $(".spanPrice0").text('₹'+(price0 - data.walletUsed).toLocaleString('en-US'));
                        $(".spanPrice1").text('₹'+(price1 - data.walletUsed).toLocaleString('en-US'));
                        $(".spanPrice2").text('₹'+(price2 - data.walletUsed).toLocaleString('en-US'));
                     
                     
                        if ((price0 - data.walletUsed) < 0)
                        {
                            $(".spanPrice0").text('₹0');
                            $('input[id=minPayChk]').attr("disabled",true);
                        }
                        if ((price1 - data.walletUsed) < 0)
                        {
                            $(".spanPrice1").text('₹0');
                            $('input[id=partPayChk]').attr("disabled",true);
                        }
                        if ((price2 - data.walletUsed) < 0)
                        {
                            $(".spanPrice2").text('₹0');
                            $('input[id=fullPayChk]').attr("disabled",true);
                        }
                       $("#BookingInvoice_partialPayment").val(dueAmt);
		       $("#BookingInvoice_partialPayment").attr('max', dueAmt);
                       $("#BookingInvoice_partialPayment").attr('min', dueAmt);
                       
                        $(".walletRemoveDiv").show();
                        $(".walletDiv").hide();
                     
                 
                    $(".walletUsed").text(data.walletUsed);
                    $(".remainingWallet").text(data.remainingWallet);
                     
                      return;
                    }
                    
                    if (data.data.hasOwnProperty("url"))
                    {
                        location.href = data.data.url;
                        return;
                    }

                    huiObj.bkgId = data.data.bookingId;
                    prmObj = new Promotion(huiObj);
                    prmObj.processData(data);
                } else
                {
                    huiObj.setErrors(data);
                }
            },
            "error": function (error)
            {
                alert(error);
            }
        });
    }

    applyWallet(event, value)
    { 
        prmObj = this;
        prmObj.eventType = event;
        prmObj.wallet = huiObj.additionalParams.wallet;
        if(event == 6)
        {
            $("#isWalletSelected").val(0);
        }
        switch (event)
        {
            case 5:
                prmObj.wallet = value;
                break;
            case 6:
                prmObj.wallet = 0;
                break;
            case 7:
                prmObj.wallet = value;
                break;

        }
        prmObj.processWallet(this);
    }

    blockForm(form)
    {
        block_ele = form.closest('form');

        $(block_ele).block({
            message: '<div class="loader"></div>',
            overlayCSS: {
                backgroundColor: "#FFF",
                opacity: 0.8,
                cursor: 'wait'
            },
            css: {
                border: 0,
                padding: 0,
                backgroundColor: 'transparent'
            }
        });
    }

    unBlockForm()
    {
        $(block_ele).unblock();
    }

    isEmpty(value)
    {
        var success = true;
        if (value.length <= 0)
        {
            success = false;
        } else if (value == null)
        {
            success = false;
        } else if (value == "")
        {
            success = false;
        }
        return success;

    }
}
