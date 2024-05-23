/* 
 * userLogin
 */
var userLogin = function()
{
	var method = "";
	var model = {};
	model.url = "";


	this.register = function()
	{
		var objuserLogin = this;
		// debugger;
		var directLoginOTP = $("#directLoginOTP").val();
		if (directLoginOTP == 1)
		{
			//this.validateForm();
		}
		var form = $("#signup-form");
              //  alert(form.serialize());
		$.ajax({
			"type": "POST",
			"dataType": "html",
			"url": $baseUrl + '/users/signupOTPNew',
			"data": form.serialize(),
			"beforeSend": function()
			{
				blockForm(form);
			},
			"complete": function()
			{
				unBlockForm(form);
			},
			"success": function(data2)
			{//debugger;
				method = "GOZO_OTP";

				var data = "";
				var isJSON = false;
				try
				{
					data = JSON.parse(data2);
					isJSON = true;
				}
				catch (e)
				{

				}
				if (!isJSON)
				{
					objuserLogin.trackGASignUp(method);
					objuserLogin.trackGALogin(method);
					$(".signInBox").hide();
					$(".otpBox").html(data2);
					$(".otpBox").show();
					trackPage("users/loginOTP");
				}
				else
				{
					if (data.success)
					{
						objuserLogin.trackGASignUp(method);
						objuserLogin.trackGALogin(method);
						$(".correctotp").hide();
						location.href = data.data.url;
						return;
					}
					else
					{
						var errors = data.errors;
						var errorCode = data.errorCode;
						var info = data.data;

						if (errorCode === 1001 || errorCode === 1002)
						{
							 // debugger;
							$(".signInStep2").hide();
							$(".alertExist").show();
							$(".alertForm").html(errors);
							$("#signUpDt").val(JSON.stringify(info.signUpdata));
							$("#otpObj").val(JSON.stringify(info.otpObj));
							$("#existContactType").val(JSON.stringify(info.existContactType));
							$("#rdata").val(JSON.stringify(info.rdata));
                                                        //=============pujapuja=======//
							 $(".verifyData").val(JSON.stringify(info.verifyData));
                                                          //====================//
							$(".correctotp").hide();


						}
						$(".alerterror").html(errors);
						$(".alerterror").removeClass('hide');
						$(".alerterror").show();
					}
				}
			},
			error: function(xhr, ajaxOptions, thrownError)
			{
				var msg = "<ul class='list-style-circle'><li>" + xhr.status + ": " + thrownError + "</li></ul>";
				$(".correctotp").html(msg);
				$(".correctotp").removeClass("hide");
			}
		});
		return false;
	};

	this.signIn = function()
	{
		 // debugger;
		var objuserLogin = this;
		var form = $("form#login-form");
		$.ajax({
			"type": "POST",
			"dataType": "html",
			"url": $baseUrl + '/users/verifyUserName',
			"data": form.serialize(),
			"beforeSend": function()
			{
				//blockForm(form);
			},
			"complete": function()
			{
			},
			"success": function(data1)
			{
                               // debugger;
                                let data = false;
                                var isJSON = false;
                                try
                                {
                                            // JSON.stringify();
                                    data = JSON.parse(data1);
                                    isJSON = true;
                                } catch (e)
                                {
                                    isJSON = false;
                                }
				unBlockForm();
				if (!isJSON)
				{
                                 // debugger;
					$("#loginForm").hide();
					$("#googleBlock").hide();
					$("#PasswordForm").hide();
					$(".signInStep2").html(data1);
					$(".signInStep2").show();
                                        objuserLogin.countdownSU(30);
                                        //$("#SignUpOtpWithPersonalDetails").show();
                                       //  $("#SignUpOtpWithPersonalDetails").html(data);
                                       // $(".signInStep2").html(data1);
				}
				else if (data.success)
				{
					$("[name=usernameToSkip]").val(data.data.userName);
					if (data.data.rdata !== undefined)
					{
						//   $("[name=rdata]").val(data1.data.rdata);
					}

					$("[name=fullContactNumberToskip").val(data.data.userNameCode + data.data.userNamePhone);
					if (data.data.isCaptchaVerify == 1)
					{
                                        //  debugger;
						$("#verifyData").val(data.data.verifyData);
						$("#captchaVerifyForm").show();
						$("#captchaBlock").show();
						$("#verifyURL").val(data.data.verifyURL);
						$("#verifyData").val(data.data.verifyData);
						if (data.data.rdata !== undefined)
						{
							$("[name=rdata]").val(data.data.rdata);
						}
						$("#loginForm").hide();
						$("#googleBlock").hide();
						$("#PasswordForm").hide();
						return;

					}

					var userVal = data.data;
					var newUser = data.data.isNewUser;
					var isEmailOrPhone = data.data.type;
					$("#loginForm").hide();
					$("#googleBlock").hide();
					$("#PasswordForm").show();
					if (newUser != 1)
					{
						//  $("#forgetPassword").hide();  
						$("#welcomeText").show();
						$("#welcomeText").html('Welcome ' + userVal.consumerName + '! please provide password to proceed');
						// if(isEmailOrPhone != 2)
						// {
						$("#forgetPassword").show();
						// }
					}

					$('#password-form').find('input[name="Users[username]"]').val(userVal.userName);
					$("[name=fullContactNumber").val(data.data.userNameCode + data.data.userNamePhone);
					$("[name=ContactEmail").val(data.data.userName);
					if (data.data.userNamePhone > 0)
					{

						$(".divider_skipPasswordForm").show();
						$("#skipPasswordForm").show();
						$(".skipDiscountMsg").show();
					}
					if (newUser === 1)
					{

						var userName = data.data.userName;
						var userType = data.data.userType;

						$("#tabLoginForm").hide();
						$("#PasswordForm").hide();
						$("#SignUpOtpWithPersonalDetails").show();
						trackPage("/signup");
						$("[name=verifyData]").val(data.data.verifyData);
						objuserLogin.countdownSU(30);
						//$("#verifyData").val(data1.data.verifyData);

						$("#otpObject").val(data.data.otpObject);
						$(".userName").text(userName);
						if (userType === 1)
						{
							$("#ContactEmail_eml_email_address").val(userName);
							$('#ContactEmail_eml_email_address').attr('readonly', true);

						}
						else
						{
							$("#ContactPhone_phn_phone_no").val(data.data.userNamePhone);
							$("#ContactPhone_phn_phone_country_code").val(data.data.userNameCode);
							$("#fullContactNumber").val(data.data.userName);
							$('#fullContactNumber').attr('readonly', true);

						}

					}
					return;
				}
				else
				{
					var formatValidationError = data.data.errors;
					$("#newUser").text(formatValidationError);
				}
			},
			error: function(xhr, ajaxOptions, thrownError)
			{

				unBlockForm(form);
				var msg = "<ul class='list-style-circle'><li>" + xhr.status + ": " + thrownError + "</li></ul>";
				$("#signInerror").html(msg);
			}
		});
		return false;
	};
	this.loginViaPassword = function()
	{
		var objuserLogin = this;
		method = "GOZO_PASSWORD";
		var form = $("form#password-form");
		$.ajax({
			"type": "POST",
			"dataType": "html",
			"url": $baseUrl + '/users/verifyPassword',
			"data": form.serialize(),
			"beforeSend": function()
			{
				//blockForm(form);
			},
			"complete": function()
			{
				//unBlockForm(form);
			},
			"success": function(data2)
			{	//debugger;
				var data = "";
				var isJSON = false;
				try
				{
					data = JSON.parse(data2);
					isJSON = true;
				}
				catch (e)
				{
				}
				if (!isJSON)
				{
					$(".signInBox").hide();
					$(".otpBox").html(data2);
					$(".otpBox").show();
					trackPage("/users/loginOTP");
				}
				else
				{
					if (data.success)
					{
						objuserLogin.trackGALogin(method);
						closeLogin();
						$('.cancelbooking').html('<a class="dropdown-item" href="javascript:void(0);" onclick="checkTripStatus()"><img src="/images/bx-x-circle.svg" alt="" width="16" height="16" class="mr10"> Cancel booking</a>');
					}
					else
					{
						var errors = data.errors;
						msg = JSON.stringify(errors);
						messages = errors;

						displayError(form, messages);
					}
				}
			},
			error: function(xhr, ajaxOptions, thrownError)
			{
				var msg = "<ul class='list-style-circle'><li>" + xhr.status + ": " + thrownError + "</li></ul>";
				$(".correctotp").html(msg);
				$(".correctotp").removeClass("hide");
			}
		});
		return false;
	};
	this.loginOTP = function()
	{
		var objuserLogin = this;
		method = "GOZO_OTP";
		var form = $("form#login-form");
		$.ajax({
			"type": "POST",
			"dataType": "html",
			"async": true,
			"url": $baseUrl + '/users/sendOTP',
			"data": form.serialize(),
			"beforeSend": function()
			{
				blockForm(form);
			},
			"complete": function()
			{
				unBlockForm(form);
			},
			"success": function(data2)
			{	//debugger;
				var data = "";
				var isJSON = false;
				try
				{
					data = JSON.parse(data2);
					isJSON = true;
				}
				catch (e)
				{

				}
				if (!isJSON)
				{
					$(".signInBox").hide();
					$(".otpBox").html(data2);
					$(".otpBox").show();
					trackPage("/users/loginOTP");
				}
				else
				{
					if (data.success)
					{
						objuserLogin.trackGALogin(method);
						closeLogin();
					}
					else
					{
						var errors = data.errors;
						msg = JSON.stringify(errors);
						messages = errors;

						displayError(form, messages);
					}
				}
			},
			error: function(xhr, ajaxOptions, thrownError)
			{
				var msg = "<ul class='list-style-circle'><li>" + xhr.status + ": " + thrownError + "</li></ul>";
				$(".correctotp").html(msg);
				$(".correctotp").removeClass("hide");
			}
		});
		return false;
	};
	this.resendOtp = function(verifyData)
	{
		var objuserLogin = this;
		$.ajax({
			"url": $baseUrl + '/users/resendOtp',
			"type": "GET",
			"dataType": "json",
			"data": {'verifyData': verifyData},
			"beforeSend": function()
			{
				blockForm($('.resendotp'));
			},
			"complete": function()
			{
				unBlockForm($('.resendotp'));
			},
			"success": function(data)
			{
				let errorMessage = '';
				if (data.errors !== undefined && data.errors !== null && Array.isArray(data.errors))
				{
					errorMessage = data.errors.join("<br>");
				}

				if (errorMessage !== '')
				{
					$('.correctotp').text(errorMessage);
				}
				if (data.success)
				{

					$('.correctotp').text('OTP sent successfully');
					$('.correctotp').removeClass('danger');
					$('.correctotp').addClass('success');
					$("INPUT[name=rdata]").val(data.data.rdata);
					//  var validTill = <?= $verificationObj->otpValidTill ?>;
					//  var lastSend = <?= $verificationObj->otpLastSent ?>;
					//  var duration = validTill - lastSend;
					$('#counter').show();
					objuserLogin.countdown(30);
					$('#resendotp').hide();
				}
			}
		});
	};

	this.countdown = function(duration)
	{
		//debugger;
		var display2 = $('#counter');
		var timer2 = duration, minutes, seconds;
		$countDown = setInterval(function()
		{
			minutes = parseInt(timer2 / 60, 10);
			seconds = parseInt(timer2 % 60, 10);
			minutes = minutes < 10 ? "0" + minutes : minutes;
			seconds = seconds < 10 ? "0" + seconds : seconds;
			display2.text(minutes + ":" + seconds);
			if (--timer2 < 0)
			{
				clearInterval($countDown);
				$('#resendotp').show();
				$('#counter').hide();
			}
		}, 1000);
	};

	this.countdownSU = function(duration)
	{
		var display2 = $('#counterSU');
		var timer2 = duration, minutes, seconds;
		$countDownSU = setInterval(function()
		{
			minutes = parseInt(timer2 / 60, 10);
			seconds = parseInt(timer2 % 60, 10);
			minutes = minutes < 10 ? "0" + minutes : minutes;
			seconds = seconds < 10 ? "0" + seconds : seconds;
			display2.text(minutes + ":" + seconds);
			if (--timer2 < 0)
			{
				clearInterval($countDownSU);
				$('#resendotpSU').show();
				$('#counterSU').hide();
			}
		}, 1000);
	};

	this.resendOtpSU = function()
	{
		//debugger;
		var objuserLogin = this;
		var verifydata = $(".verifyData").val();
               // alert(verifydata);
		var existingContactOTP = $("#resendExistingContactOTP").val();

		$.ajax({

			"url": $baseUrl + '/booking/resendOtp',
			"type": "GET",
			"dataType": "json",
			"data": {'verifyData': verifydata, 'signUP': 1, 'existingContactOTP': existingContactOTP},
			"beforeSend": function()
			{
				blockForm($('.resendotp'));
			},
			"complete": function()
			{
				unBlockForm($('.resendotp'));
			},
			"success": function(data)
			{
                            //debugger;
				let errorMessage = '';
				if (data.errors !== undefined && data.errors !== null && Array.isArray(data.errors))
				{
					errorMessage = data.errors.join("<br>");
				}

				if (errorMessage !== '')
				{
					$('.correctotp').text(errorMessage);
				}
				if (data.success)
				{
					$('.correctotp').text('OTP sent successfully');
					$('.correctotp').removeClass('danger');
					$('.correctotp').addClass('success');
					$("INPUT[name=rdata]").val(data.data.rdata);
					$("#verifyData").val(data.data.verifyData);
					$("#otpObject").val(data.data.otpObject);
					$('#counterverifyData').show();
					$('#resendotpSU').hide();
					$('#counterSU').show();
					objuserLogin.countdownSU(30);

				}
			}
		});
	};

	this.validateForm = function(callback)
	{
		method = "GOZO_OTP";
		var objuserLogin = this;
		var form = $("form#otpverify-form");
		var url = objuserLogin.initializeUrl();
		objuserLogin.getOTP();
		$.ajax({
			"type": "POST",
			"dataType": "html",
			"url": $baseUrl + url,
			"async": true,
			"data": $(form).serialize(),
			"beforeSend": function()
			{
				blockForm(form);
			},
			"complete": function()
			{
				unBlockForm(form);
			},
			"success": function(data2)
			{
				$("html,body").animate({scrollTop: 180}, "slow");
				var data = "";
				var isJSON = false;
				try
				{
					data = JSON.parse(data2);
					isJSON = true;
				}
				catch (e)
				{

				}
				if (!isJSON)
				{
					objuserLogin.trackGALogin(method);
					$("form#otpverify-form").parent().html(data2);
				}
				else
				{
					objuserLogin.verifyData(data);
				}

				$('.cancelbooking').html('<a class="dropdown-item" href="javascript:void(0);" onclick="checkTripStatus()"><img src="/images/bx-x-circle.svg" alt="" width="16" height="16" class="mr10"> Cancel booking</a>');
			},
			error: function(xhr, ajaxOptions, thrownError)
			{
				BookNow.showErrorMsg(xhr.status);
				BookNow.showErrorMsg(thrownError);

			}
		});

		return false;
	};

	this.verifyData = function(data)
	{
		if (data.success)
		{
			$('.otpverified').removeClass('hide');
			$('.verifyotp').addClass('hide');
			this.trackGALogin(method);
			closeLogin(1);
			return;
		}
		let errorMessage = '';
		if (data.errors !== undefined && data.errors !== null && Array.isArray(data.errors))
		{
			errorMessage = data.errors.join("<br>");
		}

		if (errorMessage !== '')
		{
			$('.correctotp').text(errorMessage);
			$('.correctotp').removeClass('success');
			$('.correctotp').addClass('danger');
		}
	};
	this.getOTP = function()
	{
		var num1 = $('.ootpNumber1').val();
		var num2 = $('.ootpNumber2').val();
		var num3 = $('.ootpNumber3').val();
		var num4 = $('.ootpNumber4').val();
		var cusotp = num1 + num2 + num3 + num4;
		$("#oneTimePassword").val(cusotp);
		return cusotp;
	};

	this.initializeUrl = function()
	{
		var model = this.model;
		return model.url;
	};

	this.putOtp = function(otp)
	{
		var objuserLogin = this;
		var arr = otp.split("");
		$('.ootpNumber1').val(arr[0]);
		$('.ootpNumber2').val(arr[1]);
		$('.ootpNumber3').val(arr[2]);
		$('.ootpNumber4').val(arr[3]);
		objuserLogin.getOTP();
	};

	this.validateForm2 = function()
	{
            
		var objuserLogin = this;
		var form = $("#signup-form");
            // alert($('#test').serialize());
		method = "GOZO_OTP";
		$.ajax({
			"type": "POST",
			"dataType": "html",
			"url": $baseUrl + '/users/verifyOTP',
			"data": $(form).serialize(),
			"beforeSend": function()
			{
				blockForm(form);
			},
			"complete": function()
			{
				unBlockForm(form);
			},
			"success": function(data2)
			{
				// debugger;
				$("html,body").animate({scrollTop: 180}, "slow");
				var data = "";
				var isJSON = false;
				try
				{
					data = JSON.parse(data2);
					isJSON = true;
				}
				catch (e)
				{

				}
				if (!isJSON)
				{
                                  //  debugger;
					//$("#SignUpOtpWithPersonalDetails").html("");
					//$("#SignUpOtpWithPersonalDetails").hide();
                                         $(".signInStep2").addClass('hide');
                                        $(".signInStep2").html("");
					$(".signInStep2").hide();
					$("#otpverified").html(data2);
					$('.otpverified').removeClass('hide');
					$('.verifyotp').addClass('hide');
					closeLogin(1);
				}
				else
				{
					objuserLogin.trackGALogin(method);
					objuserLogin.verifyData(data);
				}
			},
			error: function(xhr, ajaxOptions, thrownError)
			{
				//	BookNow.showErrorMsg(xhr.status);
				//	BookNow.showErrorMsg(thrownError);

			}
		});

		return false;
	};
	this.resetPassword = function()
	{
		// debugger;
		var form = $("form#login-form");
		var email = $("#Users_username").val();
		$.ajax({
			"type": "POST",
			"dataType": "html",
			"url": $baseUrl + '/users/forgotpassword',
			"data": $(form).serialize(),
			"beforeSend": function()
			{
				blockForm(form);
			},
			"complete": function()
			{
				unBlockForm(form);
			},
			"success": function(data2)
			{

				var dataArr = JSON.parse(data2);
				var dt = dataArr.data;
				if (dataArr.success)
				{

					var validityTime = dt.verifyValidity;
					$("#otpValidTill").val(validityTime.otpValidTill);
					$("#otpLastSent").val(validityTime.otpLastSent);

					//  objuserLogin.

					//  var objuserLogin = this;
					var duration = validityTime.otpValidTill - validityTime.otpLastSent;
					countdownFP(30);
					$('#counterFP').show();

					$("#loginForm").hide();
					$("#googleBlock").hide();
					$("#PasswordForm").hide();
					$("#ResetPasswordOTPForm").show();
					if (dt.typeUsr == 1)
					{
						$("#resetPasswordText").html('Reset password link with OTP has been sent to your email.');
						$("#resetPasswordTextOR").show();
					}
					$(".typeID").text(dt.typeID);

					$("#verifyData").val(dt.verifyData);
					$("INPUT[name=rdata]").val(dt.rdata);



				}
			},
			error: function(xhr, ajaxOptions, thrownError)
			{
				var msg = "<ul class='list-style-circle'><li>" + xhr.status + ": " + thrownError + "</li></ul>";
				$(".correctotp").html(msg);
				$(".correctotp").removeClass("hide");
			}
		});
		return false;
	};
	this.getResetOTP = function()
	{
		var num1 = $('.ootpNumberReset1').val();
		var num2 = $('.ootpNumberReset2').val();
		var num3 = $('.ootpNumberReset3').val();
		var num4 = $('.ootpNumberReset4').val();
		var cusotp = num1 + num2 + num3 + num4;
		$("#oneTimePasswordReset").val(cusotp);
		return cusotp;
	};
	this.validateResetPasswordOTP = function()
	{
//debugger;
		var objuserLogin = this;
		var form = $("form#resetOTP-form");
		objuserLogin.getResetOTP();
		$.ajax({
			"type": "POST",
			"dataType": "html",
			"async": false,
			"url": $baseUrl + '/users/verifyOTP',
			"data": $(form).serialize(),
			"beforeSend": function()
			{
				blockForm(form);
			},
			"complete": function()
			{

				unBlockForm(form);
			},
			"success": function(data2)
			{ 
                           

				var dt = JSON.parse(data2);
                               //  alert(dt.success);
				// dt.data.username;
                                if(dt.success)
                                {
				$("#ResetPasswordForm").show();
				$("#ResetPasswordOTPForm").hide();
				$(".username").text(dt.data.username);
				$("#uid").val(dt.data.uid);
				$("#key").val(dt.data.key);
                                }else{
                                    
                                    $(".otpError").text(dt.errors);
                                }
			},
			error: function(xhr, ajaxOptions, thrownError)
			{
                           
				//BookNow.showErrorMsg(xhr.status);
				//BookNow.showErrorMsg(thrownError);

			}
		});

		return false;
	};
	this.setPassword_OLD_ONPROCESS = function()
	{
		var form = $("form#formId");
		$.ajax({
			"type": "POST",
			"dataType": "html",
			"url": $baseUrl + '/users/Resetpassword',
			"data": $(form).serialize(),
			"beforeSend": function()
			{
				blockForm(form);
			},
			"complete": function()
			{
				unBlockForm(form);
			},
			"success": function(data2)
			{
//                               var  dt = JSON.parse(data2);
//				if (dt.status)
//				{
//                                    
//					$("#loginForm").hide();
//					$("#googleBlock").hide();
//                                        $("#PasswordForm").hide();
//					$("#ResetPasswordOTPForm").show();
//                                        $("#resetPasswordText").html('Reset password link with OTP has been sent to your email.');
//                                         dt.verifyValidity;
//                                        $("#verifyData").val(dt.verifyData);
//                                        $("INPUT[name=rdata]").val(dt.rdata);
//                                        
//				}
			},
			error: function(xhr, ajaxOptions, thrownError)
			{
				var msg = "<ul class='list-style-circle'><li>" + xhr.status + ": " + thrownError + "</li></ul>";
				$(".correctotp").html(msg);
				$(".correctotp").removeClass("hide");
			}
		});
		return false;
	};
	this.setPassword = function()
	{
		//   debugger;
		var form = $("form#formId");
		$.ajax({
			"type": "POST",
			"dataType": "json",
			"async": true,
			"url": $baseUrl + '/users/Resetpassword',
			"data": $(form).serialize(),
			"beforeSend": function()
			{
				blockForm(form);
			},
			"complete": function()
			{
				unBlockForm(form);
			},
			"success": function(data2)
			{


				if (data2.success)
				{
					$(".accepted").removeClass("hide");
					$(".changePassword").hide();
					clearInterval(counter);
					setTimeout(closeBox, 5000);
					initialMillis = Date.now();
					counter = setInterval(timer, 1000);
				}
			},
			error: function(xhr, ajaxOptions, thrownError)
			{
				var msg = "<ul class='list-style-circle'><li>" + xhr.status + ": " + thrownError + "</li></ul>";
				$(".correctotp").html(msg);
				$(".correctotp").removeClass("hide");
			}
		});
		return false;
	};



	this.resendOtpForForgotPassword = function()
	{
            
		//  debugger;
		//var form = $("form#resetOTP-form");
                var verifyData = $("#verifyData").val();
		$.ajax({
			"url": $baseUrl + '/users/resendOtpForForgotPassword',
			"type": "GET",
			"dataType": "json",
			//"data": form.serialize(),
                        "data": {'verifyData': verifyData},
			"beforeSend": function()
			{
				blockForm($('.resendotp'));
			},
			"complete": function()
			{
				unBlockForm($('.resendotp'));
			},
			"success": function(data)
			{
				//debugger;

				if (data.success)
				{
					$("INPUT[name=rdata]").val(data.data.rdata);
					if (data.data.status == 1)
					{
                                           // debugger;
						$('.correctotp').text('OTP sent successfully');
						$('.correctotp').removeClass('danger');
						$('.correctotp').addClass('success');
						//$('#counter').show();
						//objuserLogin.countdown(30);
						$('#resendotpFP').hide();
                                                $('#counterFP').show();
                                                countdownFP(30);
					        
                                                
					}
					else
					{
						$('.correctotp').text("Please try again");
					}

				}
			}
		});
	};

	this.trackGALogin = function(method)
	{
		if (typeof trackLogin === "function")
		{
			trackLogin(method);
		}
	};

	this.trackGASignUp = function(method)
	{
		if (typeof trackSignUp === "function")
		{
			trackSignUp(method);
		}
	};

};
