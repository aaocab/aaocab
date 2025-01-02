
<body class="horizontal-layout horizontal-menu navbar-static 1-column   footer-static bg-full-screen-image  blank-page" data-open="hover" data-menu="horizontal-menu" data-col="1-column">
	<?php if (!$pageOtp)
	{ ?>
	    <div class="app-content content"  id="signin_card1">
	        <div class="content-overlay"></div>
	        <div class="content-wrapper">
	            <div class="content-header row">
	            </div>
	            <div class="content-body">
	                <section id="auth-login" class="row flexbox-container">
	                    <div class="col-xl-4 col-lg-4 col-11">
							<p class="text-center"><a href="<?= Yii::app()->createUrl('supplier/user/signin') ?>"><img src="<?= IMAGE_URL . '/supplier/gozo-partner.svg' ?>" alt="" width="110" ></a></p>
	                        <div class="card mb-0">
	                            <div class="row m-0">
	                                <div class="col-12 px-0">
	                                    <div class="card disable-rounded-right mb-0 p-2 h-100 d-flex justify-content-center">
	                                        <div class="card-header pb-1">
	                                            <div class="card-title">
	                                                <h4 class="text-center mb-2">Existing partners</h4>
	                                            </div>
	                                        </div>
	                                        <div class="card-body">
<!--	                                            <div class="d-flex flex-md-row flex-column justify-content-around">
	                                               
	                                                    <img src="<?= IMAGE_URL . '/supplier/google.svg' ?>" alt="" width="245" >

														</div>
														<div class="divider">
															<div class="divider-text text-uppercase text-muted"><small>or</small>
															</div>
														</div>-->
														<div>
															<button type="button" class="btn btn-primary glow w-100 position-relative" onclick="gotoSignin1();"><i class='bx bx-log-in-circle'></i> Sign in using OTP</button>
														</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
	                </section>

	            </div>
	        </div>
	    </div>

	    <div class="app-content content" style="display: none" id="signin_card2">
	        <div class="content-overlay"></div>
	        <div class="content-wrapper">
	            <div class="content-header row">
	            </div>
	            <div class="content-body">
	                <section id="auth-login" class="row flexbox-container">
	                    <div class="col-xl-4 col-lg-4 col-11">
							<p class="text-center"><a href="<?= Yii::app()->createUrl('supplier/user/signin') ?>"><img src="<?= IMAGE_URL . '/supplier/gozo-partner.svg' ?>" alt="" width="110" ></a></p>
							<div class="card mb-0">
								<div class="row m-0">
									<div class="col-12 px-0">

										<div class="card disable-rounded-right mb-0 p-2 h-100 d-flex justify-content-center">
											<div class="card-body">
												<form method="POST" action="<?= Yii::app()->createUrl('supplier/user/signin') ?>">

													<div class="form-group text-center">
														<h6 class="text-danger"><?= $errors ?></h6>
														<label class="d-block">Login with</label>
														<div class="custom-control-inline">
															<div class="radio mr-1">
																<input type="radio" name="bsradio" id="radio1" value="1" checked="true" onclick="changeInputType('number')">
																<label for="radio1">Phone</label>
															</div>
															<div class="radio">
																<input type="radio" name="bsradio" id="radio2" value="2" onclick="changeInputType('email')">
																<label for="radio2">Email</label>
															</div>
														</div>
													</div>
													<div class="form-group mt30">
														<input type="hidden" name="YII_CSRF_TOKEN" value= "<?= Yii::app()->request->csrfToken ?>">  
														<input type="text" class="form-control font-24 text-center" name="username" id ="username" placeholder="Enter phone number" required>
													</div>
													<div class="text-center">
														<button type="submit" class="btn btn-primary btn-lg position-relative">Next</button>
													</div>
												</form>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
	                </section>

	            </div>
	        </div>
	    </div>
<?php }
else
{ ?>
		<div class="app-content content">
			<div class="content-overlay"></div>
			<div class="content-wrapper">
				<div class="content-header row">
				</div>
				<div class="content-body">
					<section id="auth-login" class="row flexbox-container">
						<div class="col-xl-4 col-lg-4 col-11">
							<p class="text-center"><a href="<?= Yii::app()->createUrl('supplier/user/signin') ?>"><img src="<?= IMAGE_URL . '/supplier/gozo-partner.svg' ?>" alt="" width="110"></a></p>
							<div class="card mb-0">
								<div class="row m-0">
									<div class="col-12 px-0">
										<div class="card disable-rounded-right mb-0 p-2 h-100 d-flex justify-content-center">
											<div class="card-body">
												<form method="POST"  id="otpform" onsubmit="return verifyOtpAndLogin();">
													<div class="form-group text-center">
														<h6 class="text-danger" id="errMessageOtpPage"><?= $errors ?></h6>
														<h6 class="text-success" id="succMessageOtpPage"></h6>
														<h6>Provide the OTP sent to <?php echo $name; ?> on <?php echo ($type == 2) ? 'phone' : 'email'; ?> ending in <?php echo $masked_username; ?></h6>
														<div class="row d-flex justify-content-center">
															<div class="col-12 col-xl-10">
																<div class="row"><input type="hidden" name="YII_CSRF_TOKEN" value= "<?= Yii::app()->request->csrfToken ?>">  
																	<input type="hidden" value="<?= $encryptedData ?>" name="verifyNo" id="verifyNo">
																	<div class="col-3 p10"><input type="number" maxlength="1" class="form-control font-24 text-center otpinput" name="otp1" required></div>
																	<div class="col-3 p10"><input type="number" maxlength="1" class="form-control font-24 text-center otpinput"  name="otp2" required></div>
																	<div class="col-3 p10"><input type="number" maxlength="1" class="form-control font-24 text-center otpinput"  name="otp3" required></div>
																	<div class="col-3 p10"><input type="number" maxlength="1" class="form-control font-24 text-center otpinput"  name="otp4" required></div>
																	<div class="col-12 text-center" id="testOtp"><?=$testOtp?></div>
																</div>
															</div>
														</div>
													</div>
													<div class="text-center">
													    <p id="otpTimeLeft"></p>
														<p><a type="button" class="text-primary" onclick="resendOtp();" id="resendOtpBtn" style="display:none">Resend OTP</a></p>
														<button type="submit" class="btn btn-primary btn-lg position-relative">Verify &amp; Login</button>
													</div>
												</form>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</section>

				</div>
			</div>
		</div>
<? } ?>
</body>
<script>
	let otpCntr = 0;
    let gotoSignin1 = () => {
        $('#signin_card1').hide();
        $('#signin_card2').show();
    }
    let changeInputType = (type) => {
        const inputField = document.getElementById('username');
        inputField.type = type;
        inputField.value = ''; // Clear the input field value
        inputField.placeholder = type === 'number' ? 'Enter phone number' : 'Enter email';
    }

    $(document).ready(function () {
		<?php if ($errors != '')
		{ ?>
					$('#signin_card1').hide();
					$('#signin_card2').show();
		<?php } ?>

		timeLeftOtp();
    });

    function verifyOtpAndLogin()
	{	
		debugger;
		$.ajax({
			"type": "POST",
			"url": "<?= Yii::app()->createUrl('supplier/user/verifyotp')?>",
			'dataType': "json",
			"data": $("#otpform").serialize(),
			"success": function (data1) {
				debugger;
				if (data1.success)
				{
					location.href = data1.url;
				}
				else 
				{
					alert(data1.errors);
					return false;
				}
			},
			 "error": function (err)
            {
                alert(err);
            }
		});
		return false;
	}


	const otpInputs = document.querySelectorAll('.otpinput');

	otpInputs.forEach((input, index) => {
	  input.addEventListener('input', () => {
		const nextInput = otpInputs[index + 1];
		if (nextInput && input.value) {
		  nextInput.focus();
		}
	  });
	});

	function resendOtp()
	{
		$('#resendOtpBtn').hide();
		if(otpCntr >= 3)
		{
			$('#errMessageOtpPage').html("Your OTP limit has been exceeded, you can try again after some time.");
			return false;
		}
		debugger;
		$.ajax({
			"type": "POST",
			"url": "<?= Yii::app()->createUrl('supplier/user/resendotp')?>",
			'dataType': "json",
			"data": $("#otpform").serialize(),
			"success": function (data1) {
				debugger;
				if (data1.success)
				{
					$('#verifyNo').val(data1.encryptedData);
					$('#testOtp').html(data1.testOtp);
					$('#succMessageOtpPage').html('OTP is resent successfully');
					otpCntr++;
					timeLeftOtp();
				}
				else 
				{
					alert(data1.errors);
					return false;
				}
			},
			 "error": function (err)
            {
                alert(err);
            }
		});
		return false;
	}
	
	function timeLeftOtp()
	{
			let otpTimeLeft = 30; // 60 seconds
			const otpTimeDisplay = document.getElementById('otpTimeLeft');
			
			const otpTimer = setInterval(() => {
			  if (otpTimeLeft <= 0) {
				clearInterval(otpTimer);
				otpTimeDisplay.textContent = '';
				$('#resendOtpBtn').show();
				$('#succMessageOtpPage').html('');
			  } else {
			  if(otpTimeDisplay!=null){
				otpTimeDisplay.textContent = `Time left: ${otpTimeLeft} seconds`;
				otpTimeLeft--;
			  }
			  }
			}, 500);
	}

</script>

