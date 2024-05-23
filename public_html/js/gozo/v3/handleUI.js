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
        //$(".sel_promo_app").addClass('hide');
        $(".applremove").removeClass('hide');
        $(".applydremove").addClass('hide');
        if (typeof data.data.promo === "undefined") 
        {
            return false;
        }
        var vid = data.data.promo.id;
        $(".appl_" + vid).addClass('hide');
        $(".promo_app"+vid).removeClass('hide');
        $(".promoRemoveDiv").removeClass('hide');
        $(".card-body").removeClass('active');
        $(".promocard"+vid).addClass('active');
       //$("#appl_" + vid).removeClass('hide');
        $("#" + vid).addClass('hide');
        
        
        $(".BookingInvoice_bkg_promo1_code").val(data.data.promo.code);
        $(".txtpromo").text(data.data.promo.code);
        $('.applydiscount').removeClass('hide');
        $(".applydiscount").text("Applied promo code: " + data.data.promo.code);
        $(".coupondiscount").text("Promo/Gozo coins applied");
        this.prevParams = JSON.parse($(".clsAdditionalParams").val());
        objHandleUI.additionalParams.coins = this.prevParams.coins;
        objHandleUI.additionalParams.code = data.data.promo.code;
        objHandleUI.additionalParams.wallet = this.prevParams.wallet;
        objHandleUI.updateAdditionalParams();
    };

    this.promoRemoved = function (data)
    {   
        var objHandleUI = this;
        $(".applremove").removeClass('hide');
        $(".applydremove").addClass('hide');
        $(".promoRemoveDiv,.promoAppliedDiv,.sel_promo_app,.applydiscount").addClass('hide');
        $(".coupondiscount").text("Promo/Gozo coins");
        $(".card-body").removeClass('active');
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
		var message = "<div class='errorSummary'><ul><li>" + data.errors[0] + "</li></ul></div>";
		toastr['error'](message, 'Failed to process!', {
			closeButton: true,
			tapToDismiss: false,
			timeout: 500000
		});
    };

    this.creditApplied = function (data)
    {
        var objHandleUI = this;
        if ($("#applyGozocoins").prop("checked")==false)
        { 
         this.checkGozocoins();
        }
        $(".creditRemoveDiv").removeClass('hide');
        $(".applydiscount").text("Gozocoins ₹" + data.data.fare.gozoCoins + " applied");
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
		$(".walletbox").addClass('hide');
        $(".walletRemoveDiv").removeClass('hide');
		$(".confirmbooking").removeClass('hide');
        $("#remainingWallet").text($("#walletbalance").val()- data.data.wallet);
    };
    
    this.walletRemoved = function (data)
    {	
        var objHandleUI = this;
		$(".walletbox").removeClass('hide');
                $(".walletRemoveDiv").addClass('hide');
		$(".confirmbooking").addClass('hide');
		$('.textproceed').removeClass('hide');
		$('.paymentoption').removeClass('hide');
		$("#remainingWallet").text($("#walletbalance").val());
    };
    
    this.checkGozocoins = function (isGuest=0)
    {
       
        $('.promoApplyDiv').hide();
        $('.autoPromoApplyDiv').hide();
        if(isGuest!=1)
        {//login
	$('.creditApplyDiv').removeClass('hide');
        $('.creditApplyDiv').show();
        $('.creditApplyDiv').css("display", "block");
         $('.loginBox').hide();
         $('.loginBox').css("display", "none");
        }else{
        $('.loginBox').removeClass('hide');//show
         $('.loginBox').show();
        $('.loginBox').css("display", "block");
        }
	$('#applyGozocoins').prop('checked', true);
        $('#applyPromo').prop('checked', false);
    
    };

    this.checkPromo = function ()
    {
        $('.loginBox').hide();
        $('.loginBox').addClass('hide');
        $('.loginBox').css("display", "none");
        
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
        $(".txtBaseFare").text(this.moneyFormatter(fare.baseFare));
        $(".txtPromoCode").html("");
        $(".distxtwallet").removeClass('color-highlight bolder');
        $(".distxtpromo").removeClass('color-highlight bolder');
        $(".distxtgozo").removeClass('color-highlight bolder');
        if (promo != null)
        {
            $(".txtPromoCode").html(promo.code);
            $(".txtPromoId").val(promo.id);
        }
		else
		{
			this.promoRemoved(fare);
		}

        $(".txtDiscountAmount").text(this.moneyFormatter(fare.discount));
        $(".txtDiscountedBaseAmount").text(this.moneyFormatter(fare.netBaseFare));
        $(".txtDriverAllowance").text(this.moneyFormatter(fare.driverAllowance));
        $(".txtTollTax").text(this.moneyFormatter(fare.tollTax));
        $(".txtStateTax").text(this.moneyFormatter(fare.stateTax));
        $(".txtAirportFee").text(this.moneyFormatter(fare.airportFee));
        $(".txtGstAmount").text(this.moneyFormatter(fare.gst));
	$(".txtEstimatedAmount").text(this.moneyFormatter(fare.totalAmount));
        $(".txtAdvancePaid").text(this.moneyFormatter(fare.customerPaid));
        $(".txtGozoCoinsUsed").text(fare.gozoCoins);
        $(".hiddenGozoCoinsUsed").text(fare.gozoCoins);
        $(".txtDueAmount").text(this.moneyFormatter(fare.dueAmount));
		if (fare.addOnCharge != 0)
		{
        $('.txtAddOnCharge').html(fare.addOnCharge);
        $('.vwAddOnCharge').removeClass('hide');
        }
        if (fare.baseFare > 0)
        {
            $(".vwBaseFare").removeClass("hide");
            $(".clsprmapplyaa").addClass("hide");
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
            $(".clsprmapplyaa").removeClass("hide");
        } 
        else
        {
             $(".vwGozoCoinsUsed").addClass('hide');
			 this.creditRemoved(fare);
        }
		if(fare.promoCoins > 0)
		{
			$(".vwPromoCoins").removeClass('hide');
			$(".txtPromoCoins").text(this.moneyFormatter(fare.promoCoins));
		}
		else
		{
			$(".vwPromoCoins").addClass('hide');
		}
        wallet = parseInt(wallet);
        if (wallet > 0)
        {
            $(".vwWalletUsed").removeClass('hide');
            $(".walletUsed").text(wallet);
            $(".vwDueAmount").removeClass('hide');
            $(".distxtwallet").addClass('color-highlight bolder');
        }
		
		if(wallet > 0 && fare.minPay == 0)
		{
			$('.textproceed').addClass('hide');
			$('.paymentoption').addClass('hide');
		}
    };
    
    this.hidePromoCoinsError = function()
    {
        $(".showPromoError").text("");
        $(".showPromoError").hide();
        $(".showCoinsError").text("");
        $(".showCoinsError").hide();
    }

    this.updatePayBox = function (fare)
    {   
        let minAmount = fare.minPay;
        let dueAmount = fare.dueAmount;
        let calAmount = (fare.customerPaid > 0)?dueAmount:fare.totalAmount;
        let setValue = $(".payChk:checked").val();
		$("#minamount").val(minAmount);
		$("#dueamount").val(dueAmount);
		$("#discountamount").val(fare.discount);
        $(".payBoxBtnAmount").text(this.moneyFormatter(minAmount));
        $(".payBoxMinAmount").text(this.moneyFormatter(minAmount));
		$(".payBoxMinAmount").val(minAmount);
        $(".minDueAmount").text(this.moneyFormatter(minAmount));
        $(".payBoxDueAmount").text(this.moneyFormatter(dueAmount));
        $(".payBoxTotalAmount").html("<b>" + this.moneyFormatter(calAmount) + "</b>");
        if (setValue == 0)
        {
            $(".clsMinPay").text(this.moneyFormatter(dueAmount));
		}
		else
		{
            $(".clsMinPay").text(this.moneyFormatter(dueAmount));
        }
       // $(".txtEstimatedAmount").text(this.moneyFormatter(dueAmount));
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
	
    this.moneyFormatter = function(x,y=0)
    {	
        x=x.toString();
        let lastThree = x.substring(x.length-3);
        let otherNumbers = x.substring(0,x.length-3);
        if(otherNumbers != '')
        lastThree = ',' + lastThree;
        let res = otherNumbers.replace(/\B(?=(\d{2})+(?!\d))/g, ",") + lastThree;
        if(y==1)
        {
            return res;
		}
		else
		{
            return '₹'+res;
        }
		
    };
    
    this.removeCommas = function(x)
    {
        x = x.replaceAll(',', '');
	x = parseInt(x);
        return x;
    };
};