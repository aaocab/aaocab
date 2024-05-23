/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var Promo = function ()
{
	var model = {};

	this.getPromoCode = function ()
	{
		var model = this.model;
		$.ajax({

			"type": "GET",
			"dataType": "json",
			"url": $baseUrl + "/admpnl/promos/getpromodiscount",
                        "async":false,
			 data: model,
			success: function (data)
			{
				if (data.success)
				{
					$("#BookingInvoice_bkg_discount_amount").val('');
					if (data.data.prmActivate == 1 && data.data.pcnType == 3)
					{
						if (data.data.promoCredits > 0 && data.data.discount > 0)
						{
							$("#BookingInvoice_bkg_discount_amount").val(0);
							$('#promocreditsucc').html('Promo applied successfully. You got discount worth ₹' + data.data.discount + ' and Gozo Coins worth ₹' + data.data.promoCredits + '.* You may redeem these Gozo Coins against your next bookings with us.');
						}
					}
                                        if (data.data.prmActivate == 1 && data.data.pcnType == 1)
					{
						if (data.data.discount > 0)
						{
							$("#BookingInvoice_bkg_discount_amount").val(0);
							$('#promocreditsucc').html('Promo applied successfully. You got discount worth ₹' + data.data.discount + ' after payment.');
						}
					}
                                        if (data.data.prmActivate == 1 && data.data.pcnType == 2)
					{
						if (data.data.promoCredits > 0)
						{
							$("#BookingInvoice_bkg_discount_amount").val(0);
							$('#promocreditsucc').html('Promo applied successfully. You got discount worth ₹' + data.data.promoCredits + '.* You may redeem these Gozo Coins against your next bookings with us.');
						}
					}
					if (data.data.prmActivate != 1 && data.data.pcnType == 3)
					{
						if (data.data.promoCredits > 0 && data.data.discount > 0)
						{
							$("#BookingInvoice_bkg_discount_amount").val(data.data.discount);
							$('#promocreditsucc').html('Promo applied successfully. You got discount worth ₹' + data.data.discount + ' and Gozo Coins worth ₹' + data.data.promoCredits + '.* You may redeem these Gozo Coins against your next bookings with us.');
						}
					}
					if (data.data.pcnType == 2)
					{
						if (data.data.promoCredits > 0 && data.data.discount == 0)
						{
							$("#BookingInvoice_bkg_discount_amount").val(0);
							$('#promocreditsucc').html('Promo applied successfully.<br> User got Gozo Coins worth Rs.' + data.data.promoCredits + '.<br> He/She may redeem these Gozo Coins against his/her next bookings with us.');
						}
					}
					if (data.data.prmActivate != 1 && data.data.pcnType == 1)
					{
						if (data.data.discount > 0)
						{
							$("#BookingInvoice_bkg_discount_amount").val(data.data.discount);
							$('#promocreditsucc').html('');
						}
					}
					if (data.data.prmActivate == null && data.data.pcnType == null && data.data.promoCredits == 0 && data.data.discount == 0)
					{
						$("#BookingInvoice_bkg_discount_amount").val(0);
						$('#promocreditsucc').html('');
					}
					$('#oldPromoCode').val(model.code);
					calculateAmount();
				} else
				{
					$("#BookingInvoice_bkg_discount_amount").val(0);
					$('#promocreditsucc').html('');
					$('#oldPromoCode').val('');
					calculateAmount();
					alert("Invalid promo");
				}
			},
			"error": function (error)
			{
				alert(error);
			}
		});
	};

	this.getPromo = function ()
	{
		var model = this.model;
		// alert(model);
		$.ajax({

			"type": "GET",
			"dataType": "json",
			"url": $baseUrl + "/booking/promoapply",
			data: model,
			success: function (data)
			{
				if (data.success)
				{
					model = data.data;
					$(document).trigger("getPromo", [data]);
				}
			},
			"error": function (error)
			{
				alert(error);
			}
		});
	};

	this.PromoCreditAjax = function ()
	{
		var model = this.model;
		var url = model.url;
		var self = this;
		$.ajax({
			"type": "GET",
			"dataType": "json",
			"url": $baseUrl + url,
			"beforeSend": function ()
			{
				ajaxindicatorstart("");
			},
			"complete": function ()
			{
				ajaxindicatorstop();
			},
			data: model,
			success: function (data)
			{
				///$(document).trigger("PromoCreditAjax",data);
				self.updateData(data, model.bkg_id, model.bkghash);
			},
			"error": function (error)
			{
				alert(error);
			}
		});
	};

	this.updateData1 = function (data, id, hash)
	{

		$('#spanPromoCreditSucc').html('');
		var coinPromoStatus4 = $("#coinPromoStatus1").val();
		$("[name=Booking_promosAutoApply]").removeAttr("checked");
		$('#isAdvDiscount').val(0);
		$('#BookingInvoice_bkg_promo1_code').val('');
		$("#promoApplyDiv").removeClass('hide');
		$("#promoAppliedDiv").addClass('hide');
		$('#autoPromoApplyDiv').removeClass('hide');
		if (data.promo_type > 0) {
			$('.etcAmount').html(data.total_amount);
		}
		//reset val
		$('.tdwallet').hide();
		$('.walletUsed').text('');
		$('.tddue').hide();
		$('.bkgamtdetails111').text('');
		$('#isWalletUsed').val(0);
		$('#walletRemove').addClass('hide');
		$('.walletUsedAmt').html(0);
		$('#walletApplyDiv').removeClass('hide');
		$('#walletUsedAmt').val(0);

		//reset val
		if (data.result)
		{
			$("#errMsgPromo").html("");
			$(".recalculateSummary").removeClass('hide');
			$('.dueAmountWithoutCOD').html(data.due_amount);
			$('.bkgamtdetails111').html(data.due_amount);
			$netAmount = data.due_amount;
			$('.bkgtotamt').html(data.total_amount);
			$('.taxAmount').text(data.service_tax).change();
			$('.dueAmtWithCOD').html(data.amountWithConvFee);
			$('#conFee').html(data.convFee);
			$('.igstval').text(data.service_tax).change();
			$('#minipay').val(data.minPayable).change();
			$('#minpayval').html(data.minPayable).change();

			$('#BookingInvoice_partialPayment').attr('max', data.due_amount);
			$('#BookingInvoice_partialPayment').attr('min', data.minPayable);
			$('#max_amount').val(data.due_amount).change();
                        
                        $('.payBoxMinAmount').text(data.minPayable);
                        $('.payBoxDueAmount').text(data.due_amount);
                        $('.payBoxTotalAmount').text(data.total_amount);
                        $.each($('input[name="payChk"]'),function(key,val){
                            if($(val).is(':checked') == true)
                            {
                                if($(val).attr('id') == 'minPayChk')
                                {
                                     $('.payBoxBtnAmount').text($('.payBoxMinAmount').text());
                                     $('#BookingInvoice_partialPayment').val(data.minPayable);
                                }
                                else
                                {
                                    $('.payBoxBtnAmount').text($('.payBoxDueAmount').text());
                                    $('#BookingInvoice_partialPayment').val(data.due_amount);
                                }
                               
                            }
                        });

			if (data.discount > 0)
			{
				$('.discounttd').removeClass('hide');
				$('.discounttd').css('display', 'block');
				$('.tddue').show();
			} else {
				$('.discounttd').hide();
			}

			if (data.isCredit)
			{
				if (data.coinPromoStatus != 1)
				{
					$('.creditApplyDiv').show();
					$('#creditvalamt').val(data.totCredits);
					$('#creditvalamt').attr('credits', data.totCredits);
					$('.creditval').html(data.totCredits);

				}

			} else {
				$('.creditApplyDiv').hide();
			}

			if (data.isPromo)
			{

				$("#promoApplyDiv").removeClass('hide');
				$('#autoPromoApplyDiv').removeClass('hide');
				$('.tdcredit').show();

			} else {

				if (data.hasOwnProperty('creditStatus') && data.creditStatus == 1)
				{
					$("#promoApplyDiv").removeClass('hide');
					$('#autoPromoApplyDiv').removeClass('hide');
					$('.tdcredit').show();
				} else {

					//$("#promoApplyDiv").addClass('hide');
					//$('#autoPromoApplyDiv').addClass('hide');
				}
			}
			//if promo applied
			if (data.promo)
			{
				// remove coin start
				/*if(coinPromoStatus4 == 1)
				 { */


				data.credit = false;
				$('#creditRemove').addClass('hide');
				$('#iscreditapplied').val(0);
				$('#creditapplied').val(0);
				$('#isPayNowCredits').val(0);
				$(".tdcredit").find("#creditUsed").val(0);
				$('#creditUsed').val(0);
				$('#creditvalamt').val(data.totCredits);
				$('#creditvalamt').attr('credits', data.totCredits);
				$('.creditApplyDiv').show();

				/* }*/



				// remove coin end
				$('.discountAmount').html(data.discount);

				//var coinPromoStatus =1;
				var promoJson = $("#all_promo_codes").val();
				if (promoJson != "")
				{
					var promoJson2 = JSON.parse(promoJson);
				}
				var found = 0;

				if (data.isAdvDiscount)
				{
					$('#isAdvDiscount').val(1);
					if ($('#isAdvDiscount').val() == 1)
					{
						$("#confPayNow").prop('checked', true);
					}
				} else
				{
					$('#isAdvDiscount').val(0);
				}
				//$('.discountAmount').html(data.discount);               
				var actual_amount = "";
				if (data.discount > 0)
				{
					var actual_amount = (data.base_amount - data.discount);
				}
				$('.actualAmount').html(actual_amount);
				if (data.promo_type > 0) {
					$('.disPromoType').html(data.promo_code);
					$('.disPromoShow').html('Promocode Applied: ' + data.promo_code);
				}
				$("#txtpromo").text(data.promo_code);
				$("#txtpromo").parent().parent().show();
				$("#txtgozocoin").parent().parent().hide();
				//$("#promoApplyDiv").addClass('hide');                
				// $('#autoPromoApplyDiv').addClass('hide');

				var msg1 = data.message;
				var ispresent = msg1.indexOf("successful");
				if (ispresent >= 0) {

					for (var key in promoJson2) {
						if (key == data.promo_code && data.promo_type != 2) {
							found = 1;
							break;
						}
					}
					if (found == 0) {
						if (data.promo_type == 2) {
							$('#promo_msgdata').html(data.message);
						} else {
							var nhtml = '<span style="cursor: auto;font-weight: bold;color: #000080;">&nbsp; -Applied</span>';
							$('#promo_msgdata').html(data.promo_desc + nhtml);
						}

					}
				}

				if (data.promo_type == 2 || data.promo_type == 3 || data.promo_type == 1)
				{
					//$('#spanPromoCreditSucc').html(data.message);
					$('#spanPromoCreditSucc').html(data.message);

				} else
				{
					$('.discounttd').show();
				}
				$('#max_amount').val(data.total_amount).change();
				$('.bkgamtdetails111').html(data.total_amount);

			}

			//if credit applied
			if (data.credit)
			{
				$('.tdcredit').show();
				//$(".tdcredit").removeClass('hide');
				$('#creditapplied').val(data.credits_used);
				$('#iscreditapplied').val(1);
				$('.creditUsed').html(data.credits_used);
				$('.creditApplyDiv').hide();

				$("#txtpromo").parent().parent().hide();
				$("#txtgozocoin").parent().parent().show();
				$('.disPromoType').html('Gozocoins');
				$('.disPromoShow').html('Gozocoin Applied:');
				$('.ispromo').hide();

				var prmStatus1 = 1;
				if (data.isPromo) {
					prmStatus1 = 0;
				}
				$('#coinPromoStatus1').val(prmStatus1);
				$('.etcAmount').html(data.newtotal);
				$('#creditRemove').removeClass('hide');
			} else
			{
				$('.creditUsed').html('0');
				if (data.discount == 0)
				{
					// $('.tdcredit').hide();
				}
			}


			//if promo removed
			if (data.promoRemove)
			{
				$('#isAdvDiscount').val(0);
				$('#BookingInvoice_bkg_promo1_code').val('');
				$("#promoApplyDiv").removeClass('hide');
				$("#promoAppliedDiv").addClass('hide');
				$('#autoPromoApplyDiv').removeClass('hide');
				$('.etcAmount').html(data.total_amount);
				if (data.isCreditUsed)
				{
					$('#creditRemove').removeClass('hide');
					$('#creditRemove').find('.creditUsed').text(data.creditused);
					$('.creditUsed').html(data.creditused);
					$('.tdcredit').show();
				} else
				{
					$('#creditRemove').addClass('hide');

				}
				if (!data.isCreditUsed && !data.isPromoUsed)
				{
					$(".recalculateSummary").addClass('hide');
				}
				$("#promo").removeClass('hide');

			}
			//if credit removed
			if (data.creditRemove)
			{
				//console.log("hi"+data.refundCredits);
				$('#creditapplied').val(0);
				$('#iscreditapplied').val(0);
				$('.creditApplyDiv').show();
				$('#creditRemove').addClass('hide');
				$('#creditvalamt').val(data.refundCredits);
				$('#creditvalamt').attr('credits', data.refundCredits);
				$('#isPayNowCredits').val(0);
				if (!data.isCreditUsed && !data.isPromoUsed)
				{
					$(".recalculateSummary").addClass('hide');
				}
			}

			//advDisc
			this.payNowLater(id, hash);
			if (data.due_amount <= 0)
			{
				$('#connfirmbookbtn').show();
				$('#paymentdiv').hide();
				$('.confBtns').hide();
			} else
			{
				$('.confBtns').show();
			}
			//advDisc
			//$( "#menu-hider" ).trigger("click");

			if (data.amtWalletUsed > 0)
			{
				if (data.credits_used > 0)
				{
					$('.creditUsed').html(data.credits_used);
		} else
		{
					if (data.credits_used == 0 || data.credits_used == undefined || data.credits_used == 'undefined') {
						$('.tdcredit').hide();
                        }
                        }
				$('.tdwallet').show();
				$('.walletUsed').text(data.amtWalletUsed);
				$('.tddue').show();
				$('.bkgamtdetails111').text(data.due_amount);
				$('#isWalletUsed').val(1);
				$('#walletRemove').removeClass('hide');
				$('.walletUsedAmt').html(data.amtWalletUsed);
				$('#walletApplyDiv').addClass('hide');
				$('#walletUsedAmt').val(data.amtWalletUsed);

			}

		} else
		{

			$("#errMsgPromo").html(data.message);
			//console.log(data.message);
			if (data.amtWalletUsed > 0)
			{
				if (data.credits_used > 0)
				{
					$('.creditUsed').html(data.credits_used);
				} else
				{
					if (data.credits_used == 0 || data.credits_used == undefined || data.credits_used == 'undefined') {
						$('.tdcredit').hide();
		}
				}
				$('.tdwallet').show();
				$('.walletUsed').text(data.amtWalletUsed);
				$('.tddue').show();
				$('.bkgamtdetails111').text(data.due_amount);
				$('#isWalletUsed').val(1);
				$('#walletRemove').removeClass('hide');
				$('.walletUsedAmt').html(data.amtWalletUsed);
				$('#walletApplyDiv').addClass('hide');
				$('#walletUsedAmt').val(data.amtWalletUsed);

			}
		}

		$("#accordion-1").trigger("click");
	}

	this.updateData = function (data, id, hash)
	{
		if (!data.result)
		{
			$("#errMsgPromo").html(data.message);
			$("#accordion-1").trigger("click");
			return;
		}
		this.setGlobalData(data, id, hash);
		this.setPromoData(data, id, hash);
		this.setGozoCoinsData(data, id, hash);
		this.setWalletData(data, id, hash);
		$("#accordion-1").trigger("click");
	}
	this.setGlobalData = function (data, id, hash)
	{
		//reset other
		$('.payBoxMinAmount').text(data.minPayable);
        $('.payBoxDueAmount').text(data.due_amount);
        $('.payBoxTotalAmount').text(data.total_amount);
        $.each($('input[name="payChk"]'),function(key,val){
        if($(val).is(':checked') == true)
        {
            if($(val).attr('id') == 'minPayChk')
            {
                 $('.payBoxBtnAmount').text(data.minPayable);
                 $('#BookingInvoice_partialPayment').val(data.minPayable);
            }
            else
            {
                $('.payBoxBtnAmount').text(data.due_amount);
                $('#BookingInvoice_partialPayment').val(data.due_amount);
            }

        }
    });
		$(".recalculateSummary").removeClass('hide');
		$('.dueAmountWithoutCOD').html(data.due_amount);
		$('.bkgamtdetails111').html(data.due_amount);
		$netAmount = data.due_amount;
		$('.bkgtotamt').html(data.total_amount);
		$('.taxAmount').text(data.service_tax).change();
		$('.dueAmtWithCOD').html(data.amountWithConvFee);
		$('#conFee').html(data.convFee);
		$('.igstval').text(data.service_tax).change();
		$('#minipay').val(data.minPayable).change();
		$('#minpayval').html(data.minPayable).change();
		$('#BookingInvoice_partialPayment').val(data.minPayable);
		$('#BookingInvoice_partialPayment').attr('max', data.due_amount);
		$('#BookingInvoice_partialPayment').attr('min', data.minPayable);
		$('#max_amount').val(data.due_amount).change();
        $('.etcAmount').html(data.total_amount);
		$('.disPromoShow').html("Apply promo code / gozo coins / wallet");
		//reset wallet
		$('#walletUsedAmt').val(0);
		$('#isWalletUsed').val(0);
		$('.walletUsedAmt').html(0);
		$('.tdwallet').hide();
		$('.walletUsed').text('');
		$('#walletApplyDiv').removeClass('hide');
		$('#walletUsedAmt').val(0);
        $('.walletRemoveDiv').removeClass('hide').addClass('hide');
		//reset promo
		$('#showPromoDescApplied').html('');
		$('#spanPromoCreditSucc').html('');
		$("[name=Booking_promosAutoApply]").removeAttr("checked");
		$('#BookingInvoice_bkg_promo1_code').val('');
		$("#promoApplyDiv").removeClass('hide');
		$("#promoAppliedDiv").addClass('hide');
		$('#autoPromoApplyDiv').removeClass('hide');
		$('#BookingInvoice_bkg_promo1_code').val('');
		$("#promoApplyDiv").removeClass('hide');
		$('#autoPromoApplyDiv').removeClass('hide');
		$("#promo").removeClass('hide');
		$('.discounttd').hide();
        $('.promoRemoveDiv').addClass('hide');

		//credit remove
		$('#creditapplied').val(0);
		$('#iscreditapplied').val(0);
		$('.creditApplyDiv').hide();
		$('#creditRemove').addClass('hide');
		$('#creditvalamt').val(data.totCredits);
		$('#creditvalamt').attr('credits', data.totCredits);
		$('#isPayNowCredits').val(0);
		$(".tdcredit").find("#creditUsed").val(0);
		$('#creditUsed').val(0);
		$('#refundCredits').val(data.refundCredits);
		$('.tddue').hide();
	//	$('.bkgamtdetails111').text('');
		$('.tdcredit').hide();
		$('#creditRemove').addClass('hide');
		$('#isAdvDiscount').val(0);
		$('.creditRemoveDiv').addClass('hide');
		
		$(".recalculateSummary").addClass('hide');

		//advDisc
		this.payNowLater(id, hash);
		if (data.due_amount <= 0)
		{
			$('#connfirmbookbtn').show();
			$('#paymentdiv').hide();
			$('.confBtns').hide();
		} else
		{
			$('.confBtns').show();
		}

		if (data.isCredit)
		{
			if (data.isPromoApplied && !data.isRefundCredits)
			{
				return;
			}
			$('.creditApplyDiv').show();
			$('#creditvalamt').val(data.totCredits);
			$('#creditvalamt').attr('credits', data.totCredits);
			$('.creditval').html(data.totCredits);
		}
	}

	this.setPromoData = function (data, id, hash)
	{
		if (data.isPromoApplied)
		{

			$('#spanPromoCreditSucc').html(data.message);
			$('#showPromoDescApplied').html(data.promo_desc + ' - Applied');
			$('.promoRemoveDiv').removeClass('hide');
			if (data.promo_type == 1 || data.promo_type == 3)
			{
				$('.discountAmount').html(data.discount);
				$('.discounttd').show();
				$('.discounttd').removeClass('hide');

			}
			if (data.isAdvDiscount)
			{
				$("#confPayNow").prop('checked', true);
			}
			//var coinPromoStatus =1;
			var promoJson = $("#all_promo_codes").val();
			if (promoJson != "")
			{
				var promoJson2 = JSON.parse(promoJson);
			}
			var found = 0;


			//$('.discountAmount').html(data.discount);               
			var actual_amount = "";
			if (data.discount > 0)
			{
				var actual_amount = (data.base_amount - data.discount);
			}
			$('.actualAmount').html(actual_amount);
			if (data.promo_type > 0) {
				$('.disPromoType').html(data.promo_code);
				$('.disPromoShow').html('Promocode Applied: ' + data.promo_code);
			}
			$("#txtpromo").text(data.promo_code);
			$("#txtpromo").parent().parent().show();
			$("#txtgozocoin").parent().parent().hide();

			if (data.isCredit)
			{
				$('.creditApplyDiv').show();
				$('#creditvalamt').val(data.totCredits);
				$('#creditvalamt').attr('credits', data.totCredits);
			}
			if (data.promo_id > 0)
			{
				var vid = data.promo_id;
				$('#' + vid).addClass('hide');
				$("#appl_" + vid).removeClass('hide');
				$("#appl_" + vid).closest('.jkl').css({"backgroundColor": " #5cb85c", "color": "#fff", "border-color": "#4cae4c"});
				$("#appl_" + vid).closest('.jkl').removeClass('lowerslab');
				$("#appl_" + vid).closest('.jkl').addClass('upperslab');
				$('#max_amount').val(data.total_amount).change();
				$('.bkgamtdetails111').html(data.due_amount);
				$("#promoAppliedDiv").removeClass('hide');
			}
		}
	}

	this.setGozoCoinsData = function (data, id, hash)
	{
		if (data.isGozoCoinsApplied)
		{
			$('.tdcredit').show();
			$(".tdcredit").removeClass('hide');
			$('#creditapplied').val(data.credits_used);
			$('#iscreditapplied').val(1);
			$('.creditUsed').html(data.credits_used);
			$('.creditApplyDiv').hide();
            $('.creditRemoveDiv').removeClass('hide');

			//$("#txtpromo").parent().parent().hide();
			//$("#txtgozocoin").parent().parent().show();
			$('.disPromoShow').html('Gozocoin Applied:  ₹'+data.credits_used);
           // $('#spanPromoCreditSucc').html('Gozocoins ₹'+data.credits_used+' applied successfully.');
			var prmStatus1 = 1;
			if (data.isPromo) {
				prmStatus1 = 0;
			}
			$('#coinPromoStatus1').val(prmStatus1);
			$('#creditRemove').removeClass('hide');
			$('.bkgamtdetails111').html(data.due_amount);
			if (data.credits_used > 0)
			{
				$('#isPayNowCredits').val(data.credits_used);
		}
	}
	}

	this.setWalletData = function (data, id, hash)
	{
		if (data.isWalletApplied)
		{
			$('.tdwallet').show();
			$('.walletUsed').text(data.amtWalletUsed);
			$('.tddue').show();
			$('.bkgamtdetails111').text(data.due_amount);
			$('#isWalletUsed').val(1);
			$('.walletUsedAmt').html(data.amtWalletUsed);
			$('#walletApplyDiv').addClass('hide');
			$('#walletUsedAmt').val(data.amtWalletUsed);
			$('.walletRemoveDiv').removeClass('hide');
			if (data.discount > 0)
			{
				var actual_amount = (data.base_amount - data.discount);
			}
			$('.actualAmount').html(actual_amount);
			$('#walletUsedAmt').val(data.amtWalletUsed);
			$('.disPromoShow').html('Wallet used ₹'+data.amtWalletUsed);
			//$('#spanPromoCreditSucc').html('Wallet used ₹'+data.amtWalletUsed+" successfully.");
		}
	}

	this.payNowLater = function (id, hash)
	{

		$('#connfirmbookbtn').hide();
		$('#paymentdiv').show();

		var url2 = $baseUrl + "/booking/paynow";

		this.ajaxPayNow(url2, id, hash);

	}

	this.ajaxPayNow = function (url, id, hash)
	{
		if (!$isRunningAjax)
		{

			var creditsused = $('#creditapplied').val();
			$.ajax({
				"type": "GET",
				"url": url,
				"dataType": "html",
				data: {'src': 1, 'id': id, 'hash': hash, 'iscreditapplied': creditsused},
				"beforeSend": function ()
				{
					ajaxindicatorstart("");
					$isRunningAjax = true;
				},
				"complete": function ()
				{
					ajaxindicatorstop();
					$isRunningAjax = false;
				},
				success: function (data)
				{
					$isRunningAjax = false;
					$('#paymentdiv').html(data);
					//   $('#bookingDetPayNow').hide();
					var creditsApplied = $('#creditapplied').val();
					if (creditsApplied > 0)
					{
						$('#isPayNowCredits').val(creditsApplied);
					}
					$("#proceedPayNow").on("click", function (event)
					{

						// if ($('#<?= CHtml::activeId($model->bkgTrail, "bkg_tnc")?>').is(':checked'))
						if ($('#BookingTrail_bkg_tnc').is(':checked'))
						{
							$('#error_div1').hide();
							$('#error_div1').html('');
						} else
						{
							$('#error_div1').show();
							$('#error_div1').html('Please check Terms and Conditions before proceed.');
							event.preventDefault();
						}
					});
				},
				"error": function (error)
				{
					alert(error);
					$isRunningAjax = false;
				}
			});
		}
	}

	this.selectType = function ()
	{
		if ($('#PromoCalculation_pcn_type_0').is(':checked'))
		{
			$('.cash').removeClass('hide');
			$('.coin').addClass('hide');
			$('.fixed').addClass('hide');
			$('.coin').find('input[type="number"]').val(0);
			$('.fixed').find('input[type="number"]').val(0);
			$('#PromoCalculation_pcn_value_type_coins_0,#PromoCalculation_pcn_value_type_coins_1').attr('checked', false);
			$('#PromoCalculation_pcn_value_type_coins_0,#PromoCalculation_pcn_value_type_coins_1').parent().removeClass('checked');
			$('#PromoCalculation_pcn_max_coins').removeAttr('readonly');
			$('#PromoCalculation_pcn_min_coins').removeAttr('readonly');

		} else if ($('#PromoCalculation_pcn_type_1').is(':checked'))
		{
			$('.cash').addClass('hide');
			$('.coin').removeClass('hide');
			$('.fixed').addClass('hide');
			$('.cash').find('input[type="number"]').val(0);
			$('.fixed').find('input[type="number"]').val(0);
			$('#PromoCalculation_pcn_value_type_cash_0,#PromoCalculation_pcn_value_type_cash_1').attr('checked', false);
			$('#PromoCalculation_pcn_value_type_cash_0,#PromoCalculation_pcn_value_type_cash_1').parent().removeClass('checked');
			$('#PromoCalculation_pcn_max_cash').removeAttr('readonly');
			$('#PromoCalculation_pcn_min_cash').removeAttr('readonly');

		} else if ($('#PromoCalculation_pcn_type_3').is(':checked'))
		{
			$('.cash').addClass('hide');
			$('.coin').addClass('hide');
			$('.fixed').removeClass('hide');
			$('.coin').find('input[type="number"]').val(0);
			$('#PromoCalculation_pcn_value_type_coins_0,#PromoCalculation_pcn_value_type_coins_1').attr('checked', false);
			$('#PromoCalculation_pcn_value_type_coins_0,#PromoCalculation_pcn_value_type_coins_1').parent().removeClass('checked');
			$('#PromoCalculation_pcn_max_coins').removeAttr('readonly');
			$('#PromoCalculation_pcn_min_coins').removeAttr('readonly');
			$('.cash').find('input[type="number"]').val(0);
			$('#PromoCalculation_pcn_value_type_cash_0,#PromoCalculation_pcn_value_type_cash_1').attr('checked', false);
			$('#PromoCalculation_pcn_value_type_cash_0,#PromoCalculation_pcn_value_type_cash_1').parent().removeClass('checked');
			$('#PromoCalculation_pcn_max_cash').removeAttr('readonly');
			$('#PromoCalculation_pcn_min_cash').removeAttr('readonly');
		} else
		{
			$('.cash').removeClass('hide');
			$('.coin').removeClass('hide');
			$('.fixed').addClass('hide');
			$('.fixed').find('input[type="number"]').val(0);
		}
	}

	this.selectValueTypeCash = function ()
	{
		if ($('#PromoCalculation_pcn_value_type_cash_1').is(':checked'))
		{
			$('#PromoCalculation_pcn_max_cash').val(0);
			$('#PromoCalculation_pcn_max_cash').attr('readonly', 'readonly');
			$('#PromoCalculation_pcn_min_cash').val(0);
			$('#PromoCalculation_pcn_min_cash').attr('readonly', 'readonly');
		} else
		{
			$('#PromoCalculation_pcn_max_cash').removeAttr('readonly');
			$('#PromoCalculation_pcn_min_cash').removeAttr('readonly');
		}
	}

	this.selectValueTypeCoins = function ()
	{
		if ($('#PromoCalculation_pcn_value_type_coins_1').is(':checked'))
		{
			$('#PromoCalculation_pcn_max_coins').val(0);
			$('#PromoCalculation_pcn_max_coins').attr('readonly', 'readonly');
			$('#PromoCalculation_pcn_min_coins').val(0);
			$('#PromoCalculation_pcn_min_coins').attr('readonly', 'readonly');
		} else
		{
			$('#PromoCalculation_pcn_max_coins').removeAttr('readonly');
			$('#PromoCalculation_pcn_min_coins').removeAttr('readonly');
		}
	};

	this.selectValueTypeGiftCard = function ()
	{
		if ($('#Promos_prm_user_type_1').is(':checked'))
        {
            $('.minbaseamt').addClass('hide');
            $('.mingftamt').removeClass('hide');
            $('.area-filter').addClass('hide');
            $('.bookinginfo').addClass('hide');
            $("#PromoCalculation_pcn_type label:nth-child(2)").addClass('hide');
            $("#PromoCalculation_pcn_type label:nth-child(3)").addClass('hide');
        } 
        else
        {
            $("#PromoCalculation_pcn_type label:nth-child(2)").removeClass('hide');
            $("#PromoCalculation_pcn_type label:nth-child(3)").removeClass('hide');
            $('.minbaseamt').removeClass('hide');
            $('.mingftamt').addClass('hide');
            $('.area-filter').removeClass('hide');
            $('.bookinginfo').removeClass('hide');
        }
	}

}
       