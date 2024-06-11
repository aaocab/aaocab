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
	.social-2 a{
		padding-top: 8px;
	}
</style>
<?php
$botConfig = Config::get("bot.website");
/*if ((int) $botConfig["show"] > 0)
{
	?>		<script src='https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/js/widget.js'></script>
	<script>
		var botcokie = 0;
		window.onload = function()
		{
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
		};
		$(document).ready(function()
		{
			var isBknw = window.location.href.indexOf("bknw");
			if (isBknw < 0)
			{-->
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
					mainColor: '#ec4e04',
					aboutText: '',
					desktopHeight: '1000',
					bubbleBackground: '',
					autoComplete: 'off',
					headerTextColor: '#fff',
					bubbleAvatarUrl: '/images/botpng.gif',
				};
	</script>
	<?php
}*/

$version = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/login.js?v=' . $version);
$imgVer	 = Yii::app()->params['imageVersion'];
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/maskFilter.js?v=' . $version);
$address = Config::getGozoAddress();
if (Yii::app()->request->url == '/')
{
	?>
	<div class="row bg-gray">
		<div class="col-12 text-center mb20">
			<b>MAKE YOUR OUTSTATION BOOKING IN ADVANCE AND LET US FIND YOU THE CHEAPEST ONE-WAY RIDES, <a href="<?= Yii::app()->getBaseUrl(true) ?>/cheapest-oneway-rides" target="_blank">Click here</a></b>
		</div>
		<div class="col-6 gradient-green-blue">
			<div class="row">
				<div class="col-sm-12 col-md-10 offset-md-2">
					<div class="app-img pt20 text-right"><img src="/images/app-img.png" alt=""></div>
					<div class="app-icon">
						<span class="font-18"><b>Download The App</b></span><br>
						<a href="https://play.google.com/store/apps/details?id=com.gozocabs.client" target="_blank"><img src="/images/GooglePlay.png?v1.1" alt="Gozocabs APP"></a>
						<a href="https://itunes.apple.com/app/id1398759012?mt=8" target="_blank"><img src="/images/app_store.png?v1.2" alt="Gozocabs APP"></a>
					</div>
				</div>
			</div>
		</div>
		<div class="col-6 bg-orange color-white pt50">
			<div class="row text-center">
				<div class="col-md-6 col-lg-4">
					<img src="/images/img10.png" alt=""><br>
					<span class="font-18"><b>One-way travel</b></span>
					<p>Why pay for round-trip when all you want is a drop at your destination</p>
				</div>
				<div class="col-md-6 col-lg-4">
					<img src="/images/img11.png" alt=""><br>
					<span class="font-18"><b>Price Transparency</b></span>
					<p>We make all charges clear to you upfront. No extra charges or hidden fees</p>
				</div>
			</div>
			<div class="row text-center">
				<div class="col-md-6 col-lg-4">
					<img src="/images/img12.png" alt=""><br>
					<span class="font-18"><b>24x7</b></span>
					<p>Book yourself for the best rates. We're here to help 24x7</p>
				</div>
				<div class="col-md-6 col-lg-4">
					<img src="/images/img13.png" alt=""><br>
					<span class="font-18"><b>Zero Cancellation*</b></span>
					<p>Enjoy zero cancellation benefit subject to Gozo <a href="/terms#Cancellation">terms;</a></p>
				</div>
			</div>
		</div>
		<div class="container ul-style-a">
			<div class="row">
				<div class="col-12 bg-white-box mt30">
					<span class="font-36 text-blue-green"><b>Routes</b></span>
					<ul>
						<?php
						Logger::create("Executing Popular Route: " . Filter::getExecutionTime());
						$rutList = Yii::app()->cache->get("popularRoute_" . $GLOBALS["rutName"]); //$rutList = false;
						if ($rutList === false)
						{
							$rutList = Route::model()->popularRoute($GLOBALS["rutName"]);
							Yii::app()->cache->set("popularRoute_" . $GLOBALS["rutName"], $rutList, 604800);
						}
						if (count($rutList) > 0)
						{
							foreach ($rutList as $rut)
							{
								?>
								<li class="">
									<a href="/book-taxi/<?php echo $rut['rutname']; ?>"><?php echo $rut['from_city']; ?> to <?php echo $rut['to_city']; ?></a>
								</li> |
								<?php
							}
						}
						Logger::create("Popular Route Rendered: " . Filter::getExecutionTime());
						?>
					</ul>
				</div>
				<div class="col-12 bg-white-box mt30 mb30">
					<span class="font-36 text-blue-green"><b>Cities</b></span>
					<ul class="list-inline list-unstyled flex">
						<?php
						Logger::create("Executing Popular Cities: " . Filter::getExecutionTime());
						$ctyList = Yii::app()->cache->get("popularCities"); //$ctyList = false;
						if ($ctyList === false)
						{
							$ctyList = Cities::model()->popularCities();
							Yii::app()->cache->set("popularCities", $ctyList, 60 * 60 * 24 * 2);
						}
						if (count($ctyList) > 0)
						{
							foreach ($ctyList as $cty)
							{
								?>
								<li class="">
									<a href="/car-rental/<?php echo strtolower($cty['cty_alias_path']); ?>"><?php echo $cty['cty_name']; ?></a>
								</li> |
								<?php
							}
						}
						Logger::create("Popular Cities Rendered: " . Filter::getExecutionTime());
						?>
					</ul>
				</div>
			</div>
		</div>
	</div>

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
<?= $this->renderPartial('/index/footer_routes', []); ?>
	<style>
		input.secure {
			text-security: disc;
			-webkit-text-security: disc;
		}

	</style>
<footer>
	<div class="row">
		<div class="col-12 bg-blue4 color-white">
			<div class="container">
				<div class="row pt40 pb40">
					<div class="col-3">
						<div class="font-18 mb10 text-uppercase"><b>Address</b></div>
						<span><?= $address ?></span>
						<br><a href="http://www.tripadvisor.in/Attraction_Review-g304551-d9976364-Reviews-Gozo_Cabs-New_Delhi_National_Capital_Territory_of_Delhi.html"><img src="/images/img_trans.gif" alt="Trpadvisor" title="Trpadvisor" width="1" height="1" class="sprit-2"></a>
						<img src="/images/img_trans.gif" alt="Google Review" title="Google Review" width="1" height="1" class="lozad sprit-3">
					</div>
					<div class="col-4">
						<div class="font-18 mb10 text-uppercase"><b>Request a call</b></div>
						<div class="row">
							<div class="col-5"><span>New Booking:</span></div>
							<div class="col-7 mb10"><a  type="button"  class="btn btn-light p5 font-12 color-black" style="color:#5a5a5a;" onclick="reqCMB(1)"> Request a call back</a></div>

							<div class="col-5"><span>Existing Booking:</span></div>
							<div class="col-7 mb10"><a  type="button"  class="btn btn-light p5 font-12 color-black" style="color:#5a5a5a;" onclick="reqCMB(2)"> Request a call back</a></div>
							<?php
							if ($isContactVnd === 0 || $isContactVnd > 0)
							{
								?>
								<div class="col-5"><span>Vendor Helpline:</span></div>
								<div class="col-7 mb10"><a  type="button"  class="btn btn-light p5 font-12 color-black" style="color:#5a5a5a;" onclick="reqCMB(4)"> Request a call back</a></div>
								<?php
							}if ($isContactVnd === 0 || $isContactVnd == null)
							{
								?>
								<div class="col-5"><span>Attach Your Taxi:</span></div>
								<div class="col-7 mb10"><a  type="button"  class="btn btn-light p5 font-12 color-black" style="color:#5a5a5a;" onclick="reqCMB(3)"> Request a call back</a></div>
							<?php } ?>
							<div class="col-5"><span>Call us at:</span></div>
							<div class="col-7 mb10"><a href="tel:+919051877000"class="btn btn-light p5 font-12 color-black" style="color:#5a5a5a;"> +91 90518-77000</a></div>
						</div>


						<p class="yello-color mt20 mb0 font-20"><i class="fa fa-envelope-o"></i> <b>Email:</b></p>
						<b>info@aaocab.com</b>
					</div>
					<div class="col-2 social-2">
						<div class="font-18 mb10 text-uppercase"><b>Keep in touch</b></div>
						<a href="http://www.facebook.com/gozocabs" target="_blank" class="mt5"><img src="/images/img_trans.gif" alt="Facebook" title="Facebook" width="1" height="1" class="sprit-6"></a><a href="https://twitter.com/gozocabs" target="_blank"><img src="/images/img_trans.gif" alt="Twitter" title="Twitter" width="1" height="1" class="sprit-7"></a><a href="http://www.instagram.com/gozocabs/" target="_blank"><img src="/images/img_trans.gif" alt="instagram" title="instagram" width="1" height="1" class="sprit-8"></a>
					</div>
					<div class="col-3">
						<div class="font-18 mb10 text-uppercase"><b>Official Travel Partner</b></div>
						<a href="/e/kumbh" style="text-decoration: none;font-size: 1.2em"><img src="/images/partners-logo3.png?v=1.1" alt="UP Gov. + UP Tourism" title="UP Gov. + UP Tourism" width="64" height="45"></a>
						<a href="/e/kumbh" style="text-decoration: none;font-size: 1.2em"><img src="/images/img_trans.gif" alt="kumbh Mela" title="kumbh Mela" width="1" height="1" class="sprit-4"></a>
						<a href="/e/sulafest" style="text-decoration: none;font-size: 1.2em"><img src="/images/img_trans.gif" alt="Sula Fest" title="Sula Fest" width="1" height="1" class="sprit-5"></a>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 bg-blue5 color-white">
			<div class="container">
				<div class="row pt20 pb20">
					<div class="col-12 text-link-black text-center font-12">
						<a href="/ask-us-to-be-official-partner">Ask Us To Be Official Partner</a> :: <a href="/business-travel">Business Travel</a> :: <a href="/for-startups">For Startups</a> :: <a href="/your-travel-desk">Your Travel Desk</a> :: <a href="/join-our-agent-network">Join Our Agent Network</a> :: <a href="/brand-partner">Brand Partners</a> :: <a href="/price-guarantee">Price Guarantee</a> :: <a href="/terms/doubleback">DOUBLE BACK</a><br>
						<a href="/">Home</a> :: <a href="/blog">Blog</a> :: <a href="/aboutus">About Us</a> :: <a href="/faq">FAQS</a> :: <a href="/contactus">Contact Us</a> :: <a href="/careers">Careers</a> :: <a href="/terms">Terms and Conditions</a> :: <a href="/disclaimer">Disclaimer</a> :: <a href="/privacy">Privacy Policy</a> :: <a href="/sitemap">Sitemap</a> :: <a href="/one-way-cab">One Way Cabs</a> :: <a href="/packages">Packages</a> :: <a href="/day-rental">day-rental</a><br> <a href="/airport-transfers">airport-transfers</a> :: <a href="/whygozo">Why GozoCabs</a> :: <a href="/newsroom">News Room</a><br>
						© <?= date("Y") ?> Gozo Technologies Pvt. Ltd. All Rights Reserved.
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
	$display = 'block';
	if (isset($_COOKIE['gzcookie']))
	{
		$display = 'none';
	}
	?>
	<div id="cookieSet" class="row cookies_panel" style="display:<?= $display ?>">
		<div class="col-xs-12 pt15">
			aaocab.com uses cookies to store information on your computer that is essential to making the site work and to customizing the user experience. By using the site, you consent to the placement of these cookies. Read our <a href="" data-toggle="modal" id="triggercp" data-target="#cookiepolicy">cookie policy</a> to learn more and how to withdraw your consent.
			<form action="" method="post">
				<button type="button" class="btn btn-primary proceed-new-btn pt5 pb5" onclick="gdprCom()">&#10004; Continue</button>
			</form>
		</div>
		<div class="col-xs-12 col-sm-2 text-right">

		</div>
	</div>
</footer>
<?= $this->renderPartial('/index/cookies_policy', []); ?>


<div class="modal fade bd-example-modal-lg" id="bkCommonModel" tabindex="-1" role="dialog" aria-labelledby="bkCommonModelLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
		<div class="modal-content">
			<!--            <div class="modal-header">
							<h5 class="modal-title" id="bkCommonModelHeader"></h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>-->
			<div class="modal-body pt0 pb0" id="bkCommonModelBody">
				...
			</div>
		</div>
	</div>
</div>
<div class="modal fade bd-example-modal-lg" id="bkCommonModel2" tabindex="-1" role="dialog" aria-labelledby="bkCommonModelLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-body pt0 pb0" id="bkCommonModelBody2"></div>
		</div>
	</div>
</div>
<!--Start of Tawk.to Script-->
<div class="modal fade bd-example-modal-lg" id="mapModal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-dialog-centered modal-lg" role="document" style="max-width: 80% !important;">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="mapModalLabel">Select Precise Location</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="mapModelContent" style="height: 500px;">

			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="helpLineModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg" role="document" style="max-width: 43% !important;">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Contact Us</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="helpLineModelContent">
				<div class="row"></div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade " id="callmeback" tabindex="-1" role="dialog"   aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg" role="document" style="max-width: 43% !important;">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="callModalLabel">Request Call back</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body  " id="callmebackBody">
				...
			</div>
		</div>
	</div>
</div>
<div style="position: fixed; right: 25px; bottom: 30px; z-index: 9999;"><span class="ml20"><a href="https://wa.me/918017279124?text=Hello,%20I%20need%20to%20book%20a%20cab" class="contact-bg-1 order-4" target=" _blank"><img src="/images/whatsapp.svg" width="60" height="60" alt="Gozocabs"></a></span></div>

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
	var refreshNavbar = function(data1)
	{

		if (callbackLogin != '')
		{
			updateLoginClose();
		}
		$('#navbar_sign').html(data1.rNav);
		$('#userdiv').hide();
		$("#infodiv").removeClass("col-md-8");
		$("#infodiv").addClass("col-md-12");
	};
	$('.collapse').collapse(
			{toggle: false});
</script>

<script>
	function reqLogin(data1, refType)
	{
		refreshNavbar(data1);
		reqCMB(refType);
	}
	function getCbmUrl(reftype)
	{
		switch (reftype)
		{
			case 1:
				var href2 = "<?php echo Yii::app()->createUrl('scq/newBookingCallBack') ?>";
				break;
			case 2:
				var href2 = "<?php echo Yii::app()->createUrl('scq/existingBookingCallBack') ?>";
				break;
			case 3:
				var href2 = "<?php echo Yii::app()->createUrl('scq/vendorAttachmentCallBack') ?>";
				break;
			case 4:
				var href2 = "<?php echo Yii::app()->createUrl('scq/existingVendorCallBack') ?>";
				break;
			default:
				var href2 = "<?php echo Yii::app()->createUrl('scq/newBookingCallBack') ?>";
				break;
		}

		return  href2;
	}

	function reqCMB(reftype)
	{	
		var href2 = getCbmUrl(reftype);
		$.ajax({
			"url": href2,
			data: {'reftype': reftype,"ismobile": true},
			"type": "GET",
			"dataType": "html",
			"success": function(data)
			{	
				$('.modal').modal('hide');
				$('#callmeback').removeClass('fade');
				$('#callmeback').css("display", "block");
				$('#callmebackBody').html(data);
				$('#callmeback').modal('show');
			},
			"error": function(xhr, ajaxOptions, thrownError)
			{
				if (xhr.status == "403")
				{
					var callback = "reqLogin(data1, " + reftype + ")";
					var href2 = "<?php echo Yii::app()->createUrl('users/partialsignin') ?>";
					$.ajax({
						"url": href2,
						"data": {"desktheme": 1, 'callback': callback},
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
				}
			}
		});
		return false;
	}

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

	var obj2 = new MaskFilter();
	obj2.getnameFilter();




</script>