<style>
    #botmanWidgetRoot > div{
        min-width: 0 !important;
		width: auto !important;
		overflow: visible !important;
		bottom: 35px !important;
    }
    .mobile-closed-message-avatar{
		right: 0!important;
		box-shadow:0 0 0 0!important;
	}
    .mobile-closed-message-avatar img{
		width: 80%!important;
	}
</style>
<?php
$botConfig = Config::get("bot.website");
if ((int) $botConfig["show"] > 0)
{
	Yii::app()->clientScript->registerScriptFile("https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/js/widget.js", CClientScript::POS_END, ["async" => true]);
	?>
	<script>
		var botcokie = 0;
		$(document).ready(function()
		{
			var isBknw = window.location.href.indexOf("bknw");
			if (isBknw < 0)
			{
	<?php
	if (!isset($_COOKIE['gzbot']) || empty($_COOKIE['gzbot']))
	{
		?>
					setTimeout(function()
					{
						botmanChatWidget.open();
						botcokie = botcokie + 1;
						return false;
					}, 45000);
	<?php } ?>
			}
		});
		var botmanWidget =
				{
					frameEndpoint: '/bot/chat.html',
					introMessage: "Hi! I'm Sonia, I can give you price quotes, book at trip or answer questions you have. <br> Tell me how can I help you?",
					chatServer: '/bot/Bot',
					title: 'Gozo Cabs',
					mainColor: '#1c4fa2',
					aboutText: '',
					desktopHeight: '400px',
					mobileHeight: "100%",
					bubbleBackground: '',
					autoComplete: 'off',
					headerTextColor: '#fff',
					bubbleAvatarUrl: '/images/botpng.gif',
					
				};
	</script>

	<style>
		input.secure {
			text-security: disc;
			-webkit-text-security: disc;
		}
		/*			.desktop-closed-message-avatar
					{
						height: 120px !important;
						width: 100px !important;
					}*/
	</style>
	<?php
}

$version = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/v3/login.js?v=' . $version, CClientScript::POS_HEAD);
$imgVer	 = Yii::app()->params['imageVersion'];
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/maskFilter.js');
if (Yii::app()->request->url == '/')
{
	?>


	<?php
}

$isContactVnd	 = 0;
$userId			 = UserInfo::getUserId();
if ($userId > 0)
{
	$umodel			 = Users::model()->findByPk($userId);
	$contactId		 = $umodel->usr_contact_id;
	$entityType		 = UserInfo::TYPE_VENDOR;
	$vnd			 = ContactProfile::getEntityById($contactId, $entityType);
	$isContactVnd	 = $vnd['id'];
}
?>

<?= $this->renderPartial('/index/cookies_policy', []); ?>
<?= $this->renderPartial('/index/footer_routes', []); ?>

<div class="d-none d-sm-block" style="position: fixed; right: 25px; bottom: 30px; z-index: 9999;"><span class="ml20"><a href="https://wa.me/918017279124?text=Hello,%20I%20need%20to%20book%20a%20cab" class="contact-bg-1 order-4 inline-block" target=" _blank"><img data-src="/images/whatsapp.svg" class="lozad" width="60" height="60" alt="img" aria-label="Whatsapp"></a></span></div>
<div class="footer-menu d-lg-none">
    <ul class="d-flex">
<!--        <li class="flex-fill"><a href="#"><i class="bx bx-chat font-24"></i><br>Chat</a></li>-->
        <li class="flex-fill"><a href="/faq" target=" _blank"><img src="/images/img_trans.gif" alt="img" width="1" height="1" class="faq-1"><br>FAQ's</a></li>
        <li class="flex-fill"><a href="/vendor/join" target=" _blank"><img src="/images/img_trans.gif" alt="img" width="1" height="1" class="attach-2"><br>Attach Your Taxi</a></li>
        <li class="flex-fill"><a href="/agent/join" target=" _blank"><img src="/images/img_trans.gif" alt="img" width="1" height="1" class="agent-1"><br>Become an agent</a></li>
    </ul>
</div>
<footer class="footer-main">
	<div class="row m0">
		<!--		<div class="col-12">
					<div class="container">
						<div class="row pt40 pb40">
							<div class="col-3">
								<div class="font-16 mb10 text-uppercase merriw"><b>Address</b></div>
								<span>Unit-302, Bestech Chambers, B Block, Sushant Lok, Phase 1, Gurgaon, Haryana 122001</span>
								<br><a href="https://www.tripadvisor.in/Attraction_Review-g304551-d9976364-Reviews-Gozo_Cabs-New_Delhi_National_Capital_Territory_of_Delhi.html"><img class="lozad" data-src="/images/trpadvisor.png"></a>
							</div>
							<div class="col-4">
								<div class="font-16 mb10 text-uppercase"><i class="bx bx-phone-call mr5"></i><b><span class="merriw">Request a call</span></b></div>
														<div class="row">
															<div class="col-5"><span>New Booking:</span></div>
															<div class="col-7 mb10"><a  type="button"  class="btn btn-outline-primary p5 font-12 color-black" style="color:#5a5a5a;" onclick="reqCMB(1)"> Request a call back</a></div>
															
															<div class="col-5"><span>Existing Booking:</span></div>
															<div class="col-7 mb10"><a  type="button"  class="btn btn-outline-primary p5 font-12 color-black" style="color:#5a5a5a;" onclick="reqCMB(2)"> Request a call back</a></div>
		<?php
		if ($isContactVnd === 0 || $isContactVnd > 0)
		{
			?>
																		<div class="col-5"><span>Vendor Helpline:</span></div>
																		<div class="col-7 mb10"><a  type="button"  class="btn btn-outline-primary p5 font-12 color-black" style="color:#5a5a5a;" onclick="reqCMB(4)"> Request a call back</a></div>
			<?php
		}if ($isContactVnd === 0 || $isContactVnd == null)
		{
			?>
																		<div class="col-5"><span>Attach Your Taxi:</span></div> 
																		<div class="col-7 mb10"><a  type="button"  class="btn btn-outline-primary p5 font-12 color-black" style="color:#5a5a5a;" onclick="reqCMB(3)"> Request a call back</a></div>
		<?php } ?>
														</div>
														
								
								<p class="yello-color mt20 mb0 font-16"><i class="bx bx-envelope"></i> <span class="merriw"><b>Email:</b></span></p>
								<b>info@gozocabs.com</b>
							</div>
							<div class="col-2 social-2">
								<div class="font-16 mb10 text-uppercase merriw"><b>Keep in touch</b></div>
								<a href="https://www.facebook.com/gozocabs" target="_blank"><i class="bx bxl-facebook"></i></a><a href="https://twitter.com/gozocabs" target="_blank"><i class="bx bxl-twitter"></i></a><a href="https://www.instagram.com/gozocabs/" target="_blank"><i class="bx bxl-instagram"></i></a>
							</div>
							<div class="col-3">
								<div class="font-16 mb10 text-uppercase merriw"><b>Official Travel Partner</b></div>
								<a href="/e/kumbh" style="text-decoration: none;font-size: 1.2em"><img src="/images/partners-logo3.png?v=1.1" alt="UP Gov. + UP Tourism" title="UP Gov. + UP Tourism"></a>
								<a href="/e/kumbh" style="text-decoration: none;font-size: 1.2em"><img src="/images/kumbh-logo.png?v=1.1" alt="Sula Fest" title="Sula Fest"></a>
								<a href="/e/sulafest" style="text-decoration: none;font-size: 1.2em"><img src="/images/partners-logo5.png?v=1.1" alt="Sula Fest" title="Sula Fest"></a>
							</div>
						</div>
					</div>
				</div>-->
		<div class="col-12 bg-gray2">
			<div class="container">
				<div class="row pt20 pb20">
					<div class="col-12 text-link-black text-center font-12 ">
						<a href="/ask-us-to-be-official-partner">Ask us to be official partner</a><span>&bullet;</span><a href="/business-travel">Business travel</a><span>&bullet;</span><a href="/for-startups">For startups</a><span>&bullet;</span><a href="/your-travel-desk">Your travel desk</a><span>&bullet;</span><a href="/join-our-agent-network">Join our agent network</a><span>&bullet;</span><a href="/brand-partner">Brand Partners</a><span>&bullet;</span><a href="/price-guarantee">Price guarantee</a><span>&bullet;</span><a href="/terms/doubleback">Double back</a><br>
						<a href="/">Home</a><span class="font-18">&bullet;</span><a href="/blog">Blog</a><span>&bullet;</span><a href="/aboutus">About us</a><span>&bullet;</span><a href="/faq">Faq's</a><span>&bullet;</span><a href="/contactus">Contact us</a><span>&bullet;</span><a href="/careers">Careers</a><span>&bullet;</span><a href="/terms">Terms and conditions</a><span>&bullet;</span><a href="/disclaimer">Disclaimer</a><span>&bullet;</span><a href="/privacy">Privacy policy</a><span>&bullet;</span><a href="/sitemap">Sitemap</a><span>&bullet;</span><a href="/one-way-cab">One way cabs</a><span>&bullet;</span><a href="/packages">Packages</a><span>&bullet;</span><a href="/book-cab/daily-rental">Day-rental</a><br><a href="/book-cab/airport-pickup">Airport-transfers</a><span>&bullet;</span><a href="/whygozo" >Why GozoCabs</a><span>&bullet;</span><a href="/newsroom">News room</a><br>
						<p class="mt-1">© <?= date("Y") ?> Gozo Technologies Pvt. Ltd. All Rights Reserved.</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</footer>
	<?php
	$display = 'block';
	if (isset($_COOKIE['gzcookie']))
	{
		$display = 'none';
	}
	?>
<div class="container-fluid">
	<div id="cookieSet" class="row cookies_panel" style="display:<?= $display ?>">
		<div class="col-12">
			GozoCabs.com uses cookies to store information on your computer that is essential to making the site work and to customizing the user experience. By using the site, you consent to the placement of these cookies. Read our <a href="" data-toggle="modal" id="triggercp" data-target="#cookiepolicy">cookie policy</a> to learn more and how to withdraw your consent.
			<form action="" method="post">
				<button type="button" class="btn btn-primary btn-sm proceed-new-btn pt5 pb5" onclick="gdprCom()">&#10004; Continue</button>
			</form>	
		</div>
		<div class="col-12 col-sm-2 text-right">

		</div>
	</div>
</div>

<div class="modal full-screen fade modalView" id="bkCommonModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header" style="display: inline-block;">
				<h5 class="modal-title" id="bkCommonModelHeader"></h5><p class="mb0" id="bkScqReason"></p>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position: absolute; top: 1.5%; right: 4%;">
					<img src="/images/img_trans.gif" alt="img" width="1" height="1" class="x-1">
				</button>
			</div>
			<div class="modal-body" id="bkCommonModelBody">
				<p class="mb-0">
					...
				</p>
			</div>
			<!--                                                <div class="modal-footer">
																<button type="button" class="btn btn-light-secondary" data-dismiss="modal">
																	<i class="bx bx-x d-block d-sm-none"></i>
																	<span class="d-none d-sm-block">Close</span>
																</button>
															</div>-->
		</div>


	</div>
</div>	
<?php
$version	 = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/userLogin.js?v=' . $version, CClientScript::POS_HEAD);
?>
<script>
	$jsUserLogin = null;
	$(document).ready(function()
	{
		$jsUserLogin = new userLogin();
	});


	if ('OTPCredential' in window)
	{
		window.addEventListener('DOMContentLoaded', e => {
			const input = document.querySelector('input[autocomplete="one-time-code"]');
			if (!input)
				return;
			const ac = new AbortController();
			const form = input.closest('form');
			if (form)
			{
				form.addEventListener('submit', e => {
					ac.abort();
				});
			}

			navigator.credentials.get({
				otp: {transport: ['sms']},
				signal: ac.signal
			}).then(otp => {
				$jsUserLogin.putOtp(otp.code);
				$('.otpNum').keyup();
				$jsUserLogin.validateForm();
			}).catch(err => {
				console.log(err);
			});
		});
	}
</script>

<script type="text/javascript">
	callbackLogin = '';
	formFill = '';
	//  var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
	//  (function () {
	//      var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
	//      s1.async = true;
	//      s1.src = 'https://embed.tawk.to/5747d08cd5acf00878ac8808/default';
	//      s1.charset = 'UTF-8';
	//      s1.setAttribute('crossorigin', '*');
	//      s0.parentNode.insertBefore(s1, s0);
	//  })();
	//  Tawk_API.onLoad = function () {
	//      var piwikId = Piwik.getAsyncTracker().getVisitorId();
	//      Tawk_API.setAttributes({
	//          'PiwikId': piwikId
	//      }, function (error) {
	//      });
	//  };

	function viewList(obj)
	{
		var href2 = $(obj).attr("href");
		$.ajax({
			"url": href2,
			"type": "GET",
			"dataType": "html",
			"success": function(data)
			{
				var box = bootbox.dialog({
					message: data,
					title: 'Booking Details',
					size: 'large',
					onEscape: function()
					{
						// user pressed escape
					},
				});
			}
		});
		return false;
	}
</script>
<!--End of Tawk.to Script-->
<!-- Start of StatCounter Code for Default Guide -->
<script type="text/javascript">
	function gdprCom()
	{
		var cookieName = 'gzcookie';
		var cookieValue = 1;
		var days = 180;
		var d = new Date();
		d.setTime(d.getTime() + (days * 24 * 60 * 60 * 1000));
		var expires = "expires=" + d.toUTCString();
		document.cookie = cookieName + "=" + cookieValue + ";" + expires + ";path=/";
		$('#cookieSet').hide();
	}

	$("#triggercp").click(function()
	{
		$('#cookiepolicy').removeClass('fade');
		$('#cookiepolicy').css('display', 'block');
		$('#cookiepolicy').modal('show');
	});
<?php
$detect		 = Yii::app()->mobileDetect;
// call methods
$isMobile	 = $detect->isMobile() && $detect->is("AndroidOS");
if ($isMobile)
{
	?>
		$(document).ready(function()
		{

			$valid = $.cookie('androidModal1');
			if ($valid == undefined || !$valid)
			{
				$('#androidModal').modal('show');
				$.cookie('androidModal1', true, {expires: 1});
			}
		});
	<?php
}
?>

	function signinPartial(callbackFunction)
	{

		var href2 = "<?php echo Yii::app()->createUrl('users/partialsignin', ['callback' => ""]) ?>" + callbackFunction;
		$.ajax({
			"url": href2,
			"data": {"desktheme": 1},
			"type": "GET",
			"dataType": "html",
			"success": function(data)
			{
				$('.modal').modal('hide');
				$('#bkCommonModel').removeClass('fade');
				$('#bkCommonModel').css("display", "block");
				$('#bkCommonModelBody').html(data);
				$('#bkCommonModel').modal('show');
			}
		});
		return false;
	}

	$('.helpline').click(function()
	{
		openHelpline();
	});

	function openHelpline()
	{
		var href2 = "<?= Yii::app()->createUrl('scq/helpline') ?>";
		$.ajax({
			"url": href2,
			"data": {"desktheme": 1},
			"type": "GET",
			"dataType": "html",
			"success": function(data)
			{
				$('.modal').modal('hide');
				$('#helpLineModal').removeClass('fade');
				$('#helpLineModal').css('display', 'block');
				$('#helpLineModelContent').html(data);
				$('#helpLineModal').modal('show');
			}
		});
		return false;
	}

	$(document).ready(function()
	{
		var obj2 = new MaskFilter();
		obj2.getnameFilter();
		setTimeout(function(){
			if(document.getElementById('botmanWidgetRoot') == null)
			{
				return;
			}
			document.getElementById('botmanWidgetRoot').childNodes[0].className = 'botclassnewchild';
			$('.botclassnewchild').click(function()
			{
				if (botcokie == 1)
				{
					var cookieName = 'gzbot';
					var cookieValue = 1;
					var date = new Date();
					date.setTime(date.getTime() + (24 * 60 * 60 * 1000));
					var expires = "; expires=" + date.toUTCString();
					document.cookie = cookieName + "=" + cookieValue + expires + "; path=/";
				}
			});
		}, 5000);
	});

	$('.close').click(function()
	{
		$('.modalView').hide();
	});

</script>
