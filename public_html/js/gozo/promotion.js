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

    getParams() {
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

        //console.log(this.data);

        $.ajax({
            "type": "POST",
            "url": $baseUrl + "/booking/applypromo",
            "data": this.getParams(),
            "dataType": "json",
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
//        if ((event == '1' || event == '3') && this.isEmpty(value) == false)
//        {
//            $(".showPromoCoinError").text("Please enter promo or gozocoins");
//            $(".showPromoCoinError").show();
//            return false;
//        }
        this.applyParams(event, value);
        prmObj.process(this);
    }

    processData(data)
    {
        if (data.success)
        {
            console.log(data);
            prmObj.eventType = data.data.eventType;
            switch (data.data.eventType)
            {
                case '1':
                    huiObj.promoAppllied(data);
                    prmObj.code = huiObj.additionalParams.code;
                    if(prmObj.isEmpty(prmObj.code) == false)
                    {
                         $(".showPromoCoinError").text("Please enter promo code");
                         $(".showPromoCoinError").show();
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
                         $(".showPromoCoinError").text("Please enter gozocoins");
                         $(".showPromoCoinError").show();
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
