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
        var allPromoCodes = $(".all_promo_codes").val();
        $(".showPromoDescApplied").removeClass('alert alert-danger');
        $(".promoAppliedDiv,.sel_promo").removeClass('hide');
        $(".sel_promo_app").addClass('hide');
        $(".jkl").css({"backgroundColor": " #fff", "color": "#636363", "border-color": "#ddd"});
        $(".showPromoDescApplied").html("");
        $(".spanPromoCreditSucc").removeClass('hide');
        $(".jkl").addClass('lowerslab').removeClass('upperslab');
        $(".spanPromoCreditSucc").html(data.message);
        $(".showPromoDescApplied").html(data.data.promo.description);
        var vid = data.data.promo.id;
        if (allPromoCodes.indexOf(vid) >= 0)
        {
            $("#" + vid).addClass('hide');
            $("#appl_" + vid).removeClass('hide');
            $("#appl_" + vid).closest('.jkl').css({"backgroundColor": " #5cb85c", "color": "#fff", "border-color": "#4cae4c"});
            setTimeout(function ()
            {
                $("#appl_" + vid).closest('.jkl').removeClass('lowerslab');
                $("#appl_" + vid).closest('.jkl').addClass('upperslab');
            }, 500);
        }
        $(".BookingInvoice_bkg_promo1_code").val("");
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
        $(".showPromoDescApplied").removeClass('alert alert-danger');
        $(".sel_promo").removeClass('hide');
        $(".promoAppliedDiv,.promoRemoveDiv,.sel_promo_app").addClass('hide');
        $(".BookingInvoice_bkg_promo1_code").val("");
        $(".jkl").css({"backgroundColor": " #fff", "color": "#636363", "border-color": "#ddd"});
        $(".spanPromoCreditSucc").addClass('hide');
        $(".jkl").addClass('lowerslab').removeClass('upperslab');
        $(".showPromoDescApplied").html(data.message);
        this.prevParams = JSON.parse($(".clsAdditionalParams").val());
        objHandleUI.additionalParams.coins = this.prevParams.coins;
        objHandleUI.additionalParams.code = "";
        objHandleUI.additionalParams.wallet = this.prevParams.wallet;
        objHandleUI.updateAdditionalParams();
    };

    this.setErrors = function (data)
    {
        $(".showPromoDescApplied").html(data.errors[0]);
        $(".promoAppliedDiv").addClass('hide');
        $(".spanPromoCreditSucc").addClass('hide');
        $(".txtpromo").html("");
        $(".showPromoDescApplied").addClass('alert alert-danger');
    };

    this.creditApplied = function (data)
    {
        var objHandleUI = this;
        if ($("#applyGozocoins").prop("checked")==false)
        { 
         this.checkGozocoins();
        }
        $(".showPromoDescApplied").html(data.message);
        $(".showPromoDescApplied").removeClass('alert alert-danger');
        $(".creditRemove").removeClass('hide');
        this.prevParams = JSON.parse($(".clsAdditionalParams").val());
        objHandleUI.additionalParams.code = this.prevParams.code;
        objHandleUI.additionalParams.coins = data.data.fare.gozoCoins;
        objHandleUI.additionalParams.wallet = this.prevParams.wallet;
        objHandleUI.updateAdditionalParams();
    };
    this.creditRemoved = function (data)
    {
        var objHandleUI = this;
        $(".showPromoDescApplied").html(data.message);
        $(".showPromoDescApplied").removeClass('alert alert-danger');
        $(".creditRemove").addClass('hide');
        this.prevParams = JSON.parse($(".clsAdditionalParams").val());
        objHandleUI.additionalParams.code = this.prevParams.code;
        objHandleUI.additionalParams.coins = 0;
        objHandleUI.additionalParams.wallet = this.prevParams.wallet;
        objHandleUI.updateAdditionalParams();
    };
    this.walletApplied = function (data)
    {
        var objHandleUI = this;
        $(".showPromoDescApplied").html(data.message);
        $(".showPromoDescApplied").removeClass('alert alert-danger');
        $(".tdwallet").removeClass('hide');
        
        this.prevParams = JSON.parse($(".clsAdditionalParams").val());
        objHandleUI.additionalParams.code = this.prevParams.code;
        objHandleUI.additionalParams.coins = this.prevParams.coins;
        objHandleUI.additionalParams.wallet = data.data.wallet;
        objHandleUI.updateAdditionalParams();
        
        $("#remainingWallet").text($("#walletbalance").val()-objHandleUI.additionalParams.wallet);
        
    };
    this.walletRemoved = function (data)
    {
        var objHandleUI = this;
        $(".showPromoDescApplied").html(data.message);
        $(".showPromoDescApplied").removeClass('alert alert-danger');
        $(".tdwallet").addClass('show');
        this.prevParams = JSON.parse($(".clsAdditionalParams").val());
        objHandleUI.additionalParams.code = this.prevParams.code;
        objHandleUI.additionalParams.coins = this.prevParams.coins;
        objHandleUI.additionalParams.wallet = 0;
        objHandleUI.updateAdditionalParams();
        $("#remainingWallet").text($("#walletbalance").val());
        $("#BookingInvoice_bkg_wallet_used").val('');
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
        $(".txtPromoCode").text("");
        if (promo != null)
        {
            $(".txtPromoCode").text(promo.code);
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
        $(".txtGozoAddonCharge").text(fare.addOnCharge);
        if (fare.gozoCoins > 0)
        {
            $(".creditvalamt").val(fare.gozoCoins);
        } 
        if (fare.addOnCharge > 0)
        {
            $(".vwAddonCharge").removeClass("hide");
        }
        
        $(".txtDueAmount").text(fare.dueAmount);
        if (fare.baseFare > 0)
        {
            $(".vwBaseFare").removeClass("hide");
        }
        if (fare.discount > 0)
        {
            $(".vwDiscount").removeClass('hide');
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
        /*if (fare.customerPaid > 0)
         {
         $(".vwAdvancePaid").removeClass('hide');
         }*/
        if (fare.gozoCoins > 0)
        {
            $(".vwGozoCoinsUsed").removeClass('hide');
            $(".vwDueAmount").removeClass('hide');
        }
        wallet = parseInt(wallet);
        if (wallet > 0)
        {
            $(".vwWalletUsed").removeClass('hide');
            $(".walletUsed").text(wallet);
            $("#walletUsedAmt").val(wallet);
            $(".vwDueAmount").removeClass('hide');

        } else {
            $('#walletUsedAmt').val(0);
            $(".walletUsed").text(0);
        }
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

    this.updatePayBox = function (fare)
    {
        let minAmount = fare.minPay;
        let dueAmount = fare.dueAmount;
        let setValue = $(".clsPayChk:checked").val();
        $(".payBoxBtnAmount").text(minAmount);
        $(".payBoxMinAmount").text(minAmount);
        $(".payBoxDueAmount").text(dueAmount);
        $(".payBoxTotalAmount").html("<b>" + dueAmount + "</b>");
        $(".miniPay").val(minAmount);
        $(".maxAmount").val(dueAmount);
        if (setValue == 0)
        {
            $(".clsPartialPayment").val(minAmount);
        } else
        {
            $(".clsPartialPayment").val(dueAmount);
        }
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