/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var Voucher = function ()
{
	this.model = {
        "token": null,
        "item": null,
		"id": null		
    };
	this.data = {};
    this.isMobile = function ()
    {
        return (this.Android() || this.BlackBerry() || this.iOS() || this.Opera() || this.Windows());
    };
    this.Android = function ()
    {
        return navigator.userAgent.match(/Android/i);
    };
    this.BlackBerry = function ()
    {
        return navigator.userAgent.match(/BlackBerry/i);
    };
    this.iOS = function ()
    {
        return navigator.userAgent.match(/iPhone|iPad|iPod/i);
    };
    this.Opera = function ()
    {
        return navigator.userAgent.match(/Opera Mini/i);
    };
    this.Windows = function ()
    {
        return navigator.userAgent.match(/IEMobile/i);
    };
	
	// Init
    this.init = function ()
    {

    };
	
	this.itemDelete = function ()
    {
		let objVoucher = this;
		$.ajax({
			url: '/voucher/del',
			data: {"YII_CSRF_TOKEN": objVoucher.model.token,'voucherId':objVoucher.model.id},
			type: 'POST',
			success: function (data) 
			{
				$(".cover" + objVoucher.model.item).hide();
				var mdata = JSON.parse(data);
				if (mdata.cartBalance > 0)
				{
					$(".totPrice").html(mdata.cartBalance);
				}
				else
				{
					$(".ctn2").show();
					$(".ctn1").hide();
				}
			}
		});
        
    };
	this.checkLoginForCheckout = function (event)
    {		
		let objVoucher = this;
		$.ajax({
			url: '/users/userdata',
			data: {"YII_CSRF_TOKEN": objVoucher.model.token},
			type: 'POST',
			async:false,
			success: function (data) 
			{         
				let pdata = JSON.parse(data);                  
				if(pdata.usr_name === null && pdata.usr_lname === null && !pdata.hasOwnProperty('usr_mobile') && !pdata.hasOwnProperty('usr_email'))
				{
					if (!objVoucher.isMobile())
                    {
						$("#signinpopup").click();
					} else {						
						alert("Please get loggedin.");
						$('html, body').animate({scrollTop:$(document).height()}, 'slow');
						$(".voucherCheckLogin").show();
						
					}
					event.preventDefault();           
				}            
			}
        });
	}; 
	
}
