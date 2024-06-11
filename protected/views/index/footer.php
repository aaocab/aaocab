<?php
$botConfig = Config::get("bot.website");
if ((int) $botConfig["show"] > 0)
{
	?>	
	<script src='https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/js/widget.js'></script>
	<script>

		$(document).ready(function ()
		{
			setTimeout(function ()
			{
				botmanChatWidget.open();
				return false;
			}, 45000);
		});


		var botmanWidget =
				{
					frameEndpoint: '/bot/chat.html',
					introMessage: "Hi! I'm Sonia, I can give you price quotes, book at trip or answer questions you have. <br> Tell me how can I help you?",
					chatServer: '/bot/Bot',
					title: 'GozoCab',
					mainColor: '#ec4e04',
					aboutText: '',
					desktopHeight: '1000',
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
<?php }
?>

<style>
	.cookies_panel{ position:fixed; bottom: 0; background: #054b8a; width: 100%; text-align: left; color: #fff; padding-bottom: 10px; z-index: 99;}
	.cookies_panel a{ color: #fff; text-decoration: underline;}
	.cookies_panel a:hover{ color: #000; text-decoration: underline;}

	.proceed-make-btn{
		/* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#ff6700+0,ff4f00+100 */
		background: #ff6700; /* Old browsers */
		background: -moz-linear-gradient(top,  #ff6700 0%, #ff4f00 100%); /* FF3.6-15 */
		background: -webkit-linear-gradient(top,  #ff6700 0%,#ff4f00 100%); /* Chrome10-25,Safari5.1-6 */
		background: linear-gradient(to bottom,  #ff6700 0%,#ff4f00 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ff6700', endColorstr='#ff4f00',GradientType=0 ); /* IE6-9 */
		text-transform: uppercase; font-size: 14px; font-weight: bold; border: none; padding: 9px 12px; margin-top: 14px;
		-webkit-border-radius: 4px;
		-moz-border-radius: 4px;
		border-radius: 4px;
		transition:all 0.5s ease-in-out 0s;
		word-wrap: break-word;
		color: #fff;
		line-height: 17px;
		margin-bottom: 20px;
		font-size: 13px;
		text-decoration: none;
	}
	.proceed-make-btn:hover{ background: #1a4ea2; color: #fff; text-decoration: none;}
	@media (max-width: 767px) {
		.proceed-make-btn{
			/* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#ff6700+0,ff4f00+100 */
			background: #ff6700; /* Old browsers */
			background: -moz-linear-gradient(top,  #ff6700 0%, #ff4f00 100%); /* FF3.6-15 */
			background: -webkit-linear-gradient(top,  #ff6700 0%,#ff4f00 100%); /* Chrome10-25,Safari5.1-6 */
			background: linear-gradient(to bottom,  #ff6700 0%,#ff4f00 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
			filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ff6700', endColorstr='#ff4f00',GradientType=0 ); /* IE6-9 */
			text-transform: uppercase; font-size: 14px; font-weight: bold; border: none; padding: 9px 12px; margin-top: 14px;
			-webkit-border-radius: 4px;
			-moz-border-radius: 4px;
			border-radius: 4px;
			transition:all 0.5s ease-in-out 0s;
			width: 100%;
			word-wrap: break-word;
			color: #fff;
			line-height: 17px;
			display: block;
			margin-bottom: 20px;
			font-size: 11px;
			text-decoration: none;
		}
		.proceed-make-btn:hover{ background: #1a4ea2; color: #fff; text-decoration: none;}
	}
</style>
<? $imgVer = Yii::app()->params['imageVersion']; ?>
<?php
if (Yii::app()->request->url == '/' || Yii::app()->request->url == '/bknw')
{
	?>
	<div class="container">
		<div class="row">
			<div class="col-xs-12 text-center mt20">
				<a href="<?= Yii::app()->getBaseUrl(true) ?>/cheapest-oneway-rides" class="proceed-make-btn" target="_blank">Make your outstation booking in advance and let us find you the cheapest one-way rides</a>
			</div>
		</div>
	</div>
	<?php
}
?>
<?php
$detect		 = Yii::app()->mobileDetect;
// call methods
$isMobile	 = $detect->isMobile();
if ($isMobile)
	goto skipFooter;
?>
<div class="container">
    <div class="row mt40 mb40 hidden-xs">
		<div class="col-xs-12 col-sm-6 col-md-3 text-center wow fadeInUp animated" style="visibility: visible; animation-delay: 0.4s; animation-name: fadeInUp;" data-wow-delay="0.4s">
			<div class="advance-panel"><figure><img class="lozad" data-src="/images/img1.png?v=<?= $imgVer ?>" alt="One Way Drop"></figure></div>
			<h3>One-way travel</h3>
			Why pay for round-trip when all you want is a drop at your destination
		</div>
		<? /* /?>
		  <div class="col-xs-12 col-sm-3  text-center wow fadeInUp animated hide" style="visibility: visible; animation-delay: 0.4s; animation-name: fadeInUp;" data-wow-delay="0.6s">
		  <div class="advance-panel"><img src="/images/img2.png?v=1" alt="One Way Drop"></div>
		  <h4 class="orange-color text-uppercase">ZERO Advance</h4><b>Book with Gozo cabs mobile app</b>
		  No advance payment required. Pay now or Pay later. Book in advance for the lowest prices
		  </div>
		  <?/ */ ?>
		<div class="col-xs-12 col-sm-6 col-md-3  text-center wow fadeInUp animated" style="visibility: visible; animation-delay: 0.4s; animation-name: fadeInUp;" data-wow-delay="0.6s">
            <div class="advance-panel"><figure><img class="lozad" data-src="/images/img3.png?v=<?= $imgVer ?>" alt="Price Transparency"></figure></div>
			<h3>Price Transparency</h3>
			We make all charges clear to you upfront. No extra charges or hidden fees
		</div>
		<div class="col-xs-12 col-sm-6 col-md-3  text-center wow fadeInUp animated" style="visibility: visible; animation-delay: 0.4s; animation-name: fadeInUp;" data-wow-delay="0.8s">
			<div class="advance-panel"><figure><img class="lozad" data-src="/images/img4.png?v=<?= $imgVer ?>" alt="aaocab, Customer Support"></figure></div>
			<h3>24x7</h3>
			Book yourself for the best rates. We're here to help 24x7
		</div>
		<div class="col-xs-12 col-sm-6 col-md-3  text-center wow fadeInUp animated" style="visibility: visible; animation-delay: 0.4s; animation-name: fadeInUp;" data-wow-delay="0.8s">
			<div class="advance-panel"><figure><img class="lozad" data-src="/images/img5.png?v=<?= $imgVer ?>" alt="aaocab, Customer Support"></figure></div>
			<h3>Zero Cancellation*</h3>
			Enjoy zero cancellation benefit subject to Gozo <a href="http://www.aaocab.com/terms#Cancellation" target="_blank">terms</a>;
		</div>
    </div>
</div>
<div class="row application-panel hidden-xs pl n pr n">
    <div class="application-bg m15 n">
		<div class="container marginauto">
			<div class="row application-box">
				<div class="col-xs-12 mb20">
					<b class="m0 white-color">Book with Gozo cabs mobile app <a href="https://play.google.com/store/apps/details?id=com.aaocab.client" target="_blank"><img class="lozad" data-src="/images/GooglePlay.png?v1.1" alt="aaocab APP"></a>
						<a href="https://itunes.apple.com/app/id1398759012?mt=8" target="_blank"><img class="lozad" data-src="/images/app_store.png?v1.2" alt="aaocab APP"></a>
					</b>
				</div>
			</div>
		</div>
    </div>
    <div class="row hidden-xs routes-panel">
		<div class="container">
			<div class="col-xs-12">
				<div class="row">
					<div class="col-xs-12 text-center routes-link new-list-srtyle">
						<div class="h3 text-center">Routes</div>
						<ul class="list-inline list-unstyled">
							<?php
							Logger::create("Executing Popular Route: " . Filter::getExecutionTime());
							$rutList	 = Yii::app()->cache->get("popularRoute_" . $GLOBALS["rutName"]);
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
										<a href="/book-taxi/<?php echo $rut['rutname']; ?>" style="font-weight:600; color: #333"><? echo $rut['from_city']; ?> to <? echo $rut['to_city']; ?></a>
									</li>
									<?
								}
							}
							Logger::create("Popular Route Rendered: " . Filter::getExecutionTime());
							?>
						</ul>
					</div>
					<div class="col-xs-12 text-center routes-link new-list-srtyle2">
						<div class="h3 text-center">Cities</div>
						<ul class="list-inline list-unstyled flex">
							<?php
							Logger::create("Executing Popular Cities: " . Filter::getExecutionTime());
							$ctyList = Yii::app()->cache->get("popularCities");
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
										<a href="/car-rental/<?php echo strtolower($cty['cty_alias_path']); ?>" style="font-weight:600; color: #333"><? echo $cty['cty_name']; ?></a>
									</li>
									<?
								}
							}
							Logger::create("Popular Cities Rendered: " . Filter::getExecutionTime());
							?>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!--<div class="row hidden-xs routes-panel mb40">
	<div class="container">
		<div class="row">
		<div class="col-xs-12">
			<h1 class="text-center">Official Partner</h1>
		</div>
		<div class="col-xs-12 partners-box">
			<ul>
			<li><img src="images/partners-img1.jpg" alt="" ></li>
			<li><img src="images/partners-img1.jpg" alt="" ></li>
			</ul>
		</div>
		</div>
	</div>
	</div> -->
	<div class="row hidden-xs payment-bg">
		<div class="container">
			<div class="row">		
				<div class="col-xs-12 partners-box">
					<img class="lozad" data-src="/images/payment-option.png?v=0.1" alt="">
				</div>
			</div>
		</div>
	</div>
</div>
<?php
skipFooter:
?>
<footer class="footer">
	<nav class="nav">
		<div class="row footer-bg">
			<div class="container">
				<div class="row mb30 pb30 footer-border hidden-xs">
					<div class="col-xs-12 col-sm-4 text-left">
						<h4 class="yello-color"><i class="fa fa-location-arrow"></i> Address</h4>
						<?= Config::getGozoAddress(Config::Corporate_address, true)?>
						<br><a href="http://www.tripadvisor.in/Attraction_Review-g304551-d9976364-Reviews-Gozo_Cabs-New_Delhi_National_Capital_Territory_of_Delhi.html"><img class="lozad" data-src="/images/trpadvisor.png"></a>

					</div>
					<div class="col-xs-12 col-sm-3 text-left">
						<h4 class="yello-color mb0"><i class="fa fa-phone"></i> Support number (24x7)</h4>
						<br>
						<b>
							New Booking: +91 70444-52999<br>
							Existing Booking: +91 90518-77000<br>
							Vendor Helpline: +91 90511-16230 / 62899-05921<br>
							Attach Your Taxi: +91 96743-11190
						</b>
						<h4 class="yello-color mt20 mb0"><i class="fa fa-envelope-o"></i> Email</h4>
						<b>info@aaocab.com</b>
					</div>
					<div class="col-xs-12 col-sm-2 text-left">
						<h4 class="yello-color">Keep in touch</h4>
						<a href="http://www.facebook.com/aaocab" target="_blank"><i class="fa fa-facebook-f fa-2x mr10"></i></a>
						<a href="https://twitter.com/aaocab" target="_blank"><i class="fa fa-twitter fa-2x mr10"></i></a>
						<!--<a href="https://plus.google.com/+aaocab" target="_blank"><i class="fa fa-google-plus fa-2x mr10"></i></a>-->
						<a href="http://www.instagram.com/aaocab/" target="_blank"><i class="fa fa-instagram fa-2x mr10"></i></a>
					</div>
					<div class="col-xs-12 col-sm-3 text-left">
						<h4 class="yello-color">Official Travel Partner</h4>
						<div class="logo-section-box">
							<a href="/e/kumbh" style="text-decoration: none;font-size: 1.2em"><img class="lozad" data-src="/images/partners-logo3.png?v=1.1" alt="UP Gov. + UP Tourism" title="UP Gov. + UP Tourism"></a><a href="/e/kumbh" style="text-decoration: none;font-size: 1.2em"><img class="lozad" data-src="/images/kumbh-logo.png?v=1.1" alt="Sula Fest" title="Sula Fest"></a><a href="/e/sulafest" style="text-decoration: none;font-size: 1.2em"><img class="lozad" data-src="/images/partners-logo5.png?v=1.1" alt="Sula Fest" title="Sula Fest"></a>
						</div>
					</div>

				</div>
				<div class="row">
					<div class="col-xs-12">
						<a href="/ask-us-to-be-official-partner">Ask Us To Be Official Partner</a>|<a href="/business-travel">Business Travel</a>|<a href="/for-startups">For Startups</a>|<a href="/your-travel-desk">Your Travel Desk</a>|<a href="/join-our-agent-network">Join Our Agent Network</a>|<a href="/brand-partner">Brand Partners</a>|<a href="/price-guarantee">Price Guarantee</a>|<a href="/terms/doubleback">DOUBLE BACK</a><br>
						<a href="/">Home</a>|<a href="/blog">Blog</a>|<a href="/aboutus">About Us</a>|<a href="/faq">FAQS</a>|<a href="/contactus">Contact Us</a>|<a href="/careers">Careers</a>|<a href="/terms">Terms and Conditions</a>|<a href="/disclaimer">Disclaimer</a>|<a href="/privacy">Privacy Policy</a>|<a href="/sitemap">Sitemap</a>|<a href="/one-way-cab">One Way Cabs</a>|<a href="/packages">Packages</a>|<a href="/day-rental">day-rental</a>|<a href="/airport-transfers">airport-transfers</a>|<a href="/shuttle">shuttle</a>|<a href="/whygozo">Why aaocab</a>|<a href="/newsroom">News Room</a><br>
						© <?= date("Y") ?> Gozo Technologies Pvt. Ltd. All Rights Reserved.
					</div>
				</div>
				<p>&nbsp;</p>
			</div>
		</div>
	</nav>
	<?php
	$display = 'block';
	if (isset($_COOKIE['gzcookie']))
	{
		$display = 'none';
	}
	?>
	<div id="cookieSet" class="row cookies_panel" style="display:<?= $display ?>">
		<div class="col-xs-12 pt15">
			aaocab.com uses cookies to store information on your computer that is essential to making the site work and to customizing the user experience. By using the site, you consent to the placement of these cookies. Read our <a href="" data-toggle="modal" data-target="#cookiepolicy">cookie policy</a> to learn more and how to withdraw your consent.
			<form action="" method="post">
				<button type="button" class="btn btn-primary proceed-new-btn pt5 pb5" onclick="gdprCom()">&#10004; Continue</button>
			</form>	
		</div>
		<div class="col-xs-12 col-sm-2 text-right">

		</div>
	</div>

</footer>
<?= $this->renderPartial('/index/cookies_policy', []); ?>


<!-- <div id="androidModal" class="modal modal-transparent fade hide " data-backdrop="static" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content" style="box-shadow: none; border: 0; background: transparent">
			<div class="modal-body p0 text-center">
				<div style="text-align: right">
					<a href="#" class="btn btn-primary mb5" data-dismiss="modal"><i class="fa fa-close mr5"></i>close</a>
				</div>
				<div>
					<figure>
						<a href="https://play.google.com/store/apps/details?id=com.aaocab.client" target="_blank" rel="nofollow">
							<img src="/images/android_app.jpg?v1.2" alt="aaocab App" style="max-width: 95%"/>
						</a>
					</figure>
				</div>
			</div>
		</div>
	</div>
</div> -->

<!--Start of Tawk.to Script-->
<div class="modal fade" id="mapModal" tabindex="-1" role="dialog" style="z-index:1000 !important;">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="mapModalLabel">Select Precise Location</h4>
			</div>
			<div class="modal-body" id="mapModelContent">

			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	callbackLogin = '';
	formFill = '';
	//    var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
	//    (function () {
	//        var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
	//        s1.async = true;
	//        s1.src = 'https://embed.tawk.to/5747d08cd5acf00878ac8808/default';
	//        s1.charset = 'UTF-8';
	//        s1.setAttribute('crossorigin', '*');
	//        s0.parentNode.insertBefore(s1, s0);
	//    })();
	//    Tawk_API.onLoad = function () {
	//        var piwikId = Piwik.getAsyncTracker().getVisitorId();
	//        Tawk_API.setAttributes({
	//            'PiwikId': piwikId
	//        }, function (error) {
	//        });
	//    };

	function viewList(obj) {
		var href2 = $(obj).attr("href");

		$.ajax({
			"url": href2,
			"type": "GET",
			"dataType": "html",
			"success": function (data) {
				var box = bootbox.dialog({
					message: data,
					title: 'Booking Details',
					size: 'large',
					onEscape: function () {
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
<?php
$detect		 = Yii::app()->mobileDetect;
// call methods
$isMobile	 = $detect->isMobile() && $detect->is("AndroidOS");
if ($isMobile)
{
	?>
		$(document).ready(function () {

			$valid = $.cookie('androidModal1');
			if ($valid == undefined || !$valid) {
				$('#androidModal').modal('show');
				$.cookie('androidModal1', true, {expires: 1});
			}
		});
	<?
}
?>
	var refreshNavbar = function (data1)
	{

		if (callbackLogin != '')
		{
			updateLoginClose();
			try {
				var fn = callbackLogin + '(' + data1.userdata + ')';
				eval(fn);
			} catch (e) {
				alert(e);
			}
		}
		$('#navbar_sign').html(data1.rNav);
		$('#userdiv').hide();
		bootbox.hideAll();
		//fillUserform(data1);
		//            if (typeof hideLoginDiv == 'function') {
		//                hideDiv();
		//            }
	}


	function fillUserform(data)
	{
		if ($('#BookingTemp_bkg_user_name').val() == '' && $('#BookingTemp_bkg_user_lname').val() == '')
		{
			$('#BookingTemp_bkg_user_name').val(data.usr_name);
			$('#BookingTemp_bkg_user_lname').val(data.usr_lname);
		}
		if (data.usr_mobile != '') {
			if ($('#BookingTemp_bkg_contact_no').val() == '') {
				$('#BookingTemp_bkg_contact_no').val(data.usr_mobile);
			} else if ($('#BookingTemp_bkg_contact_no').val() != '' && $('#BookingTemp_bkg_contact_no').val() != data.usr_mobile) {
				$('#BookingTemp_bkg_alternate_contact').val(data.usr_mobile);
			}
		}
		if (data.usr_email != '') {
			if ($('#BookingTemp_bkg_user_email1').val() == '') {
				$('#BookingTemp_bkg_user_email1').val(data.usr_email);
			}
			if ($('#BookingTemp_bkg_user_email2').val() == '') {
				$('#BookingTemp_bkg_user_email2').val(data.usr_email);
			}
		}
		fillUserform11(data);
	}


	function fillUserform11(data) {
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
	}

</script>
