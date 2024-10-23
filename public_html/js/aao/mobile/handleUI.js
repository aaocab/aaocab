/* 
 * HandleUI
 */
var HandleUI = function ()
{
    this.additionalParams = {};
    this.additionalParams.code = "";
    this.additionalParams.coins = 0;
    this.bkgId = 0;
    this.additionalParams.wallet = 0;

    this.promoAppllied = function (data)
    {
        var objHandleUI = this;
        if ($("#applyPromo").prop("checked")==false)
        { 
         this.checkPromo();
        }
        $(".sel_promo_app").addClass('hide');
        $(".promoRemoveDiv").removeClass('hide');
        if (typeof data.data.promo == "undefined") 
        {
            return false;
        }
        var vid = data.data.promo.id;
        $("#appl_" + vid).removeClass('hide');
        $("#" + vid).addClass('hide');
        $(".BookingInvoice_bkg_promo1_code").val(data.data.promo.code);
        $(".txtpromo").text(data.data.promo.code);
        this.prevParams = JSON.parse($(".clsAdditionalParams").val());
        objHandleUI.additionalParams.coins = this.prevParams.coins;
        objHandleUI.additionalParams.code = data.data.promo.code;
        objHandleUI.additionalParams.wallet = this.prevParams.wallet;
        objHandleUI.updateAdditionalParams();
    };

    this.promoRemoved = function (data)
    {
        var objHandleUI = this;
        $(".promoRemoveDiv,.promoAppliedDiv,.sel_promo_app").addClass('hide');
        $(".sel_promo").removeClass('hide');
        $(".jkl").css({"backgroundColor": " #fff", "color": "#636363", "border-color": "#ddd"});
        $(".spanPromoCreditSucc").html("");
        $(".jkl").addClass('lowerslab').removeClass('upperslab');
        $(".showPromoDescApplied").html(data.message);
        $(".txtPromoCode").text("");
        this.prevParams = JSON.parse($(".clsAdditionalParams").val());
        objHandleUI.additionalParams.coins = this.prevParams.coins;
        objHandleUI.additionalParams.code = "";
        objHandleUI.additionalParams.wallet = this.prevParams.wallet;
        objHandleUI.updateAdditionalParams();
    };

    this.setErrors = function (data)
    {
        $(".txtErrorMsg").fadeIn();
        $(".txtErrorMsg").text(data.errors[0]);
        $(".txtErrorMsg").fadeOut(4000);
    };

    this.creditApplied = function (data)
    {
        var objHandleUI = this;
        if ($("#applyGozocoins").prop("checked")==false)
        { 
         this.checkGozocoins();
        }
        $(".creditRemoveDiv").removeClass('hide');
        this.prevParams = JSON.parse($(".clsAdditionalParams").val());
        objHandleUI.additionalParams.code = this.prevParams.code;
        objHandleUI.additionalParams.coins = data.data.fare.gozoCoins;
        objHandleUI.additionalParams.wallet = this.prevParams.wallet;
        objHandleUI.updateAdditionalParams();
    };
    
    this.creditRemoved = function (data)
    {
        var objHandleUI = this;
        $(".creditRemoveDiv").addClass('hide');
        this.prevParams = JSON.parse($(".clsAdditionalParams").val());
        objHandleUI.additionalParams.code = this.prevParams.code;
        objHandleUI.additionalParams.coins = 0;
        objHandleUI.additionalParams.wallet = this.prevParams.wallet;
        objHandleUI.updateAdditionalParams();
    };

    this.walletApplied = function (data)
    {
        var objHandleUI = this;
        $(".walletRemoveDiv").removeClass('hide');
        this.prevParams = JSON.parse($(".clsAdditionalParams").val());
        objHandleUI.additionalParams.code = this.prevParams.code;
        objHandleUI.additionalParams.coins = this.prevParams.coins;
        objHandleUI.additionalParams.wallet = data.data.wallet;
        objHandleUI.updateAdditionalParams();
    };
    
    this.walletRemoved = function (data)
    {
        var objHandleUI = this;
        $(".walletRemoveDiv").addClass('hide');
        this.prevParams = JSON.parse($(".clsAdditionalParams").val());
        objHandleUI.additionalParams.code = this.prevParams.code;
        objHandleUI.additionalParams.coins = this.prevParams.coins;
        objHandleUI.additionalParams.wallet = 0;
        objHandleUI.updateAdditionalParams();

    };
    
    this.checkGozocoins = function ()
    {
        $('.promoApplyDiv').hide();
        $('.autoPromoApplyDiv').hide();
        $('.creditApplyDiv').show();
        $('.creditApplyDiv').css("display", "block");

        $('#applyGozocoins').prop('checked', true);
        $('#applyPromo').prop('checked', false);
    };

    this.checkPromo = function ()
    {
        $('.promoApplyDiv').show();
        $('.autoPromoApplyDiv').show();
        $('.creditApplyDiv').hide();
        $('.creditApplyDiv').css("display", "none");

        $('#applyGozocoins').prop('checked', false);
        $('#applyPromo').prop('checked', true);
    };

    this.updateInvoice = function (data)
    {
        var objHandleUI = this;
        objHandleUI.updateBillingInvoice(data.data.fare, data.data.promo, data.data.wallet);
        objHandleUI.updatePayBox(data.data.fare);
    };

    this.updateBillingInvoice = function (fare, promo, wallet)
    {   
        $(".clsBilling").addClass("hide");
        $(".txtBaseFare").text(fare.baseFare);
        $(".txtPromoCode").html("");
        $(".distxtwallet").removeClass('color-highlight bolder');
        $(".distxtpromo").removeClass('color-highlight bolder');
        $(".distxtgozo").removeClass('color-highlight bolder');
        if (promo != null)
        {
            $(".txtPromoCode").html(promo.code);
        }
        $(".txtDiscountAmount").text(fare.discount);
        $(".txtDiscountedBaseAmount").text(fare.netBaseFare);
        $(".txtDriverAllowance").text(fare.driverAllowance);
        $(".txtTollTax").text(fare.tollTax);
        $(".txtStateTax").text(fare.stateTax);
        $(".txtAirportFee").text(fare.airportFee);
        $(".txtGstAmount").text(fare.gst);
        $(".txtEstimatedAmount").text(fare.totalAmount);
        $(".txtAdvancePaid").text(fare.customerPaid);
        $(".txtGozoCoinsUsed").text(fare.gozoCoins);
        $(".txtDueAmount").text(fare.dueAmount);
        if(fare.addOnCharge != 0){
        $('.txtAddOnCharge').html(fare.addOnCharge);
        $('.vwAddOnCharge').removeClass('hide');
        }
        if (fare.baseFare > 0)
        {
            $(".vwBaseFare").removeClass("hide");
        }
        if (fare.discount > 0)
        {
            $(".vwDiscount").removeClass('hide');
            $(".distxtpromo").addClass('color-highlight bolder');
        }
        if (fare.driverAllowance > 0)
        {
            $(".vwDriverAllowance").removeClass('hide');
        }
        if (fare.tollIncluded == 1 && fare.tollTax > 0)
        {
            $(".vwTollTax").removeClass('hide');
        }
        if (fare.stateTaxIncluded == 1 && fare.stateTax > 0)
        {
            $(".vwStateTax").removeClass('hide');
        }
        if (fare.airportChargeIncluded == 1 && fare.airportFee > 0)
        {
            $(".vwAirportCharge").removeClass('hide');
        }
        if (fare.customerPaid > 0)
         {
         $(".vwAdvancePaid").removeClass('hide');
         }
        if (fare.gozoCoins > 0)
        {
            $(".vwGozoCoinsUsed").removeClass('hide');
            $(".vwDueAmount").removeClass('hide');
            $(".distxtgozo").addClass('color-highlight bolder');
        } 
        wallet = parseInt(wallet);
        if (wallet > 0)
        {
            $(".vwWalletUsed").removeClass('hide');
            $(".walletUsed").text(wallet);
            $(".vwDueAmount").removeClass('hide');
            $(".distxtwallet").addClass('color-highlight bolder');
        }
    };
    
    
    this.hidePromoCoinsError = function()
    {
        $(".showPromoCoinError").text("");
        $(".showPromoCoinError").hide();
    }

    this.updatePayBox = function (fare)
    {
        let minAmount = fare.minPay;
        let dueAmount = fare.dueAmount;
        let calAmount = (fare.customerPaid > 0)?fare.totalAmount:dueAmount;
        let setValue = $(".payChk:checked").val();
        $(".payBoxBtnAmount").text(minAmount);
        $(".payBoxMinAmount").text(minAmount);
        $(".minDueAmount").text(minAmount);
        $(".payBoxDueAmount").text(dueAmount);
        $(".payBoxTotalAmount").html("<b>" + calAmount + "</b>");
        if (setValue == 0)
        {
            $(".clsMinPay").text(dueAmount);
        } else {
            $(".clsMinPay").text(dueAmount);
        }
        $(".maxAmount").val(dueAmount);
        $(".minipay").val(minAmount);
        $(".clsPartialPayment").attr("min", minAmount);
        $(".clsPartialPayment").attr("max", dueAmount);
    };
    
    this.updateAdditionalParams = function ()
    {
        $(".clsAdditionalParams").val(JSON.stringify(this.additionalParams));
    };

    this.getToken = function ()
    {
        return $("input[name='YII_CSRF_TOKEN']").val();
    };
};