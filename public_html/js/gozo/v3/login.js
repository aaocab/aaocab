/* 
 * Login
 */

var Login = function () {
	this.model = {
		"step": 0,
		"bookingType": 1
	};
	this.isMobile = function () {
		return (this.Android() || this.BlackBerry() || this.iOS() || this.Opera() || this.Windows());
	};
	this.Android = function () {
		return navigator.userAgent.match(/Android/i);
	};
	this.BlackBerry = function () {
		return navigator.userAgent.match(/BlackBerry/i);
	};
	this.iOS = function () {
		return navigator.userAgent.match(/iPhone|iPad|iPod/i);
	};
	this.Opera = function () {
		return navigator.userAgent.match(/Opera Mini/i);
	};
	this.Windows = function () {
		return navigator.userAgent.match(/IEMobile/i);
	};

	// Init
	this.init = function () {

	};
	this.openFbDialog = function (url)
	{
		var href = url;

		window.open(href, 'aaocab', 'left=20,top=20,width=500,height=500,toolbar=1,resizable=0');
	};

	// HideTab    

	this.signinWithFB = function (href) {
		var fbWindow = window.open(href, 'aaocab', 'left=20,top=20,width=500,height=500,toolbar=1,resizable=0');
	};
	this.signinWithGoogle = function (href) {
		var googleWindow = window.open(href, 'aaocab', 'left=20,top=20,width=500,height=500,toolbar=1,resizable=0');
	};
	this.fillUserform2 = function (data) {
		if ($('#BookingTemp_bkg_user_name').val() == '' && $('#BookingTemp_bkg_user_lname').val() == '')
		{
			$('#BookingTemp_bkg_user_name').val(data.usr_name);
			$('#BookingTemp_bkg_user_lname').val(data.usr_lname);
		}
		if (data.usr_mobile != '') {
			if ($('input[name="BookingTemp[fullContactNumber]"]').val() == '')
			{
				$('input[name="BookingTemp[fullContactNumber]"]').val(data.usr_mobile);

			}
			if ($('input[name="BookingTemp[bkg_contact_no]"]').val() == '') {
				$('input[name="BookingTemp[bkg_contact_no]').val(data.usr_mobile);
			} else if ($('input[name="BookingTemp[bkg_contact_no]').val() != '' && $('input[name="BookingTemp[bkg_contact_no]').val() != data.usr_mobile) {
				$('#BookingTemp_bkg_alternate_contact').val(data.usr_mobile);
			}
		}
		if (data.usr_email != '') {
			if ($('input[name="BookingTemp[bkg_user_email]"]').val() == '') {
				$('input[name="BookingTemp[bkg_user_email]"]').val(data.usr_email);
			}
		}
	};

	this.fillUserform13 = function (data) {

		if ($('#Booking_bkg_user_name').val() == '' && $('#Booking_bkg_user_lname').val() == '')
		{
			$('#Booking_bkg_user_name').val(data.usr_name);
			$('#Booking_bkg_user_lname').val(data.usr_lname);
		}

		if (data.usr_mobile != '') {
			if ($('#Booking_bkg_contact_no').val() == '') {
				$('#Booking_bkg_contact_no').val(data.usr_mobile);
			} else if ($('#Booking_bkg_contact_no').val() != '' && $('#Booking_bkg_contact_no').val() != data.usr_mobile) {
				$('#Booking_bkg_alternate_contact').val(data.usr_mobile);
			} else if ($('#BookingTemp_bkg_contact_no').val() != '' && $('#BookingTemp_bkg_contact_no').val() != data.usr_mobile) {
				$('#BookingTemp_bkg_contact_no').val(data.usr_mobile);
			}
		}
		if (data.usr_email != '') {
			if ($('#Booking_bkg_user_email1').val() == '') {
				$('#Booking_bkg_user_email1').val(data.usr_email);
			}
			if ($('#Booking_bkg_user_email2').val() == '') {
				$('#Booking_bkg_user_email2').val(data.usr_email);
			}
		}
		if (this.isMobile())
		{
			$('#sidebar-right-over .menu-login').removeClass('hide');
			$('#sidebar-right-over .menu-logout').addClass('hide');
		}
	};

	this.updateLogin = function (urls) {
		var objBookNow = this;
		jQuery.ajax({type: 'get', url: urls.refreshuserdata, "dataType": "json", success: function (data1)
			{
				if (data1.usr_mobile == "") {
					if (socailTypeLogin == "facebook") {
						socailTypeLogin = "";
						objBookNow.signinWithFB(urls.fburl);
					} else {
						socailTypeLogin = "";
						objBookNow.signinWithGoogle(urls.googleurl);
					}
				} else {
					$('#userdiv').hide();
					$('#navbar_sign').html(data1.rNav);
					$('#hideLogin').hide();
					if($("#hideDetails").hasClass("col-xs-12 col-sm-6 col-md-6 marginauto book-panel pb0"))
                                        {
                                                $("#hideDetails").removeClass("col-xs-12 col-sm-6 col-md-6 marginauto book-panel pb0");
                                                $("#hideDetails").addClass("col-xs-12 col-sm-9 col-md-7 book-panel pb0");
                                        }
					objBookNow.fillUserform2(data1.userData);
					objBookNow.fillUserform13(data1.userData);
				}

			}
		});

	};

	this.socialLogin = function (socailSigin, urls) {

		var objBookNow = this;
		socailTypeLogin = socailSigin;
		var href2 = urls.partialsignin;
		$.ajax({
			"url": href2,
			"type": "GET",
			"dataType": "html",
			"success": function (data) {
				if (data.search("You are already logged in") == -1) {
					if (socailSigin == "facebook") {
						objBookNow.signinWithFB(urls.fburl);
					} else {
						objBookNow.signinWithGoogle(urls.googleurl);
					}

				} else {
					var box = bootbox.dialog({message: data, size: 'large',
						onEscape: function () {
							objBookNow.updateLogin(urls);
						}
					});
				}
			}
		});
		return false;
	};

	
	this.callSignupbox = function (url) {
		$href = url;
		jQuery.ajax({type: 'GET', url: $href, "data": {},
			success: function (data)
			{
				$('#bkSignupModel').removeClass('fade');
				$('#bkSignupModel').css("display" , "block");
				$('#bkSignupModelBody').html(data);
				$('#bkSignupModel').modal('show');
			}
		});
	};
	
	
	this.fillUserFormMobile = function (data) {			
        if ($('input[name="BookingTemp[bkg_user_name]"]').val() == '' && $('input[name="BookingTemp[bkg_user_lname]"]').val() == '')
        {
            $('input[name="BookingTemp[bkg_user_name]"]').val(data.usr_name);
            $('input[name="BookingTemp[bkg_user_lname]"]').val(data.usr_lname);
        }
        if (data['usr_mobile'] != '') {
            if ($('input[name="BookingTemp[bkg_contact_no]"]').val() == '') {
                $('input[name="BookingTemp[bkg_contact_no]"]').val(data.usr_mobile);
				$('input[name="BookingTemp[fullContactNumber]"]').val(data.usr_mobile);
            } 			
        }
        if (data.usr_email != '') {
            if ($('input[name="BookingTemp[bkg_user_email]"]').val() == '') {
                $('input[name="BookingTemp[bkg_user_email]').val(data.usr_email);
            }            
        }
		$(".loggiUser").html("Hi,&nbsp;" + data.usr_name);		
		$("#userdiv").hide();									
		$("#hideLogin").hide();	
		if($("#hideDetails").hasClass("col-xs-12 col-sm-6 col-md-6 marginauto book-panel pb0")) 
		{								
			$("#hideDetails").removeClass("col-xs-12 col-sm-6 col-md-6 marginauto book-panel pb0");
			$("#hideDetails").addClass("col-xs-12 col-sm-9 col-md-7 book-panel pb0");
		}
		$('#sidebar-right-over .menu-login').removeClass('hide');
		$('#sidebar-right-over .menu-logout').addClass('hide');
		$(".menu-hide").click();
    };

};
