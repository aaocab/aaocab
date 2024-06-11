<?php
$this->beginContent('//layouts/head1');
if ($this->layout == 'column1')
{
	$style = "background-color: inherit";
}
$fixedTop	 = ($this->fixedTop) ? "navbar-fixed-top" : "";
$bgBanner	 = ($this->fixedTop) ? "bg-banner" : "";

$address	 = Config::getGozoAddress(Config::Corporate_address, true)
?>


<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="http://www.googletagmanager.com/ns.html?id=GTM-T73295"
                      height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <div class="fixed-menu hidden-xs">
        <a href="http://www.facebook.com/gozocabs" target="_blank" class="social-1 wow fadeInUp animated" style="visibility: visible; animation-delay: 0.6s; animation-name: fadeInUp;" data-wow-delay="0.5s" title="Facebook"><i class="fa fa-facebook" data-toggle="tooltip" data-placement="left" title="Tooltip on left"></i></a>
        <a href="https://twitter.com/gozocabs" target="_blank" class="social-2 wow bounceIn animated" style="visibility: visible; animation-delay: 0.6s; animation-name: fadeInUp;" data-wow-delay="0.7s" title="Twitter"><i class="fa fa-twitter"></i></a>
        <a href="https://plus.google.com/b/113163564383201478409/+Gozocabs" target="_blank" class="social-3 wow bounceIn animated" style="visibility: visible; animation-delay: 0.6s; animation-name: fadeInUp;" data-wow-delay="0.9s" title="Google+"><i class="fa fa-google-plus"></i></a>
    </div>
    <div class="container smain-bg">
        <header class="header">
            <div class="container1">
                <div class="row">
                    <div class="col-xs-12 col-sm-4 pt10">
						<figure><a class="" href="/">
								<img src="/images/logo2_new.png?v1.1" alt="Gozocabs:India's leader in inter-city taxi | Great service. Price guarantee. Awesome reviews."></a>
						</figure>
					</div>
                    <div class="col-xs-12 col-sm-8" style="padding-top: 1px">
                        <div class="row">
                            <div class="col-xs-12 col-sm-9 semail pt10">
                                <div class="row">
                                    <div class="col-xs-3 pr0"><div class="phone-panel"><i class="fa fa-phone"></i></div></div>
                                    <div class="col-xs-9">
                                        <figure><b>24/7 Support number</b><br><a href="tel:+919051877000" style="text-decoration: none;"><img src="/images/india-flag.png" alt="India"> <span class="mr15">(+91) 90518-77-000 (24x7)</span></a><a href="tel:+16507414696" style="text-decoration: none"><img src="/images/worl-icon.png" alt="International"> (+1) 650-741-GOZO</a></figure>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-3 login-panel">
                                <ul>
                                    <li class="dropdown" id="navbar_sign">
										<?php
										$time		 = Filter::getExecutionTime();

										$GLOBALS['time96']	 = $time;
										?>
										<?php
										$this->renderPartial("/users/navbarsign");
										?>

                                    </li>                                    
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row stop-menu">
                <nav class="navbar">
                    <div class="container float-none marginauto pl0">
                        <!-- Brand and toggle get grouped for better mobile display -->
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle collapsed pull-right" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                        </div>
                        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                            <ul class="nav navbar-nav">
                                <li><a href="/whygozo">Why GozoCabs</a></li>
                                <li><a href="/faq">FAQ</a></li>
                                <li><a href="/vendor/join">Attach Your Taxi</a></li>
                                <li><a href="/blog">Our Blog</a></li>

                            </ul></div>
                        <!-- /.navbar-collapse -->
                    </div><!-- /.container-fluid -->
                </nav>
            </div>

        </header>
		<?php
		$time				 = Filter::getExecutionTime();

		$GLOBALS['time97']	 = $time;
		?>
		<?= $content ?>
		<?php
		$time				 = Filter::getExecutionTime();

		$GLOBALS['time98']	 = $time;
		?>
        <div class="container">
            <div class="row mt40 mb40 hidden-xs">
                <div class="col-xs-12 col-sm-4 text-center wow fadeInUp animated" style="visibility: visible; animation-delay: 0.4s; animation-name: fadeInUp;" data-wow-delay="0.4s">
                    <div class="advance-panel"><figure><img src="/images/img1.png?v=1.1" alt="One Way Drop"></figure></div>
                    <h3>One-way travel</h3>
                    Why pay for round-trip when all you want is a drop at your destination
                </div>
				<? /* /?>
				  <div class="col-xs-12 col-sm-3  text-center wow fadeInUp animated hide" style="visibility: visible; animation-delay: 0.4s; animation-name: fadeInUp;" data-wow-delay="0.6s">
				  <div class="advance-panel"><img src="/images/img2.png?v=1" alt="One Way Drop"></div>
				  <h4 class="orange-color text-uppercase">ZERO Advance</h4>
				  No advance payment required. Pay now or Pay later. Book in advance for the lowest prices
				  </div>
				  <?/ */ ?>
                <div class="col-xs-12 col-sm-4  text-center wow fadeInUp animated" style="visibility: visible; animation-delay: 0.4s; animation-name: fadeInUp;" data-wow-delay="0.6s">
                    <div class="advance-panel"><figure><img src="/images/img3.png?v=1.1" alt="Price Transparency"></figure></div>
                    <h3>Price Transparency</h3>
                    We make all charges clear to you upfront. No extra charges or hidden fees
                </div>
                <div class="col-xs-12 col-sm-4  text-center wow fadeInUp animated" style="visibility: visible; animation-delay: 0.4s; animation-name: fadeInUp;" data-wow-delay="0.8s">
                    <div class="advance-panel"><figure><img src="/images/img4.png?v=1.1" alt="Gozocabs, Customer Support"></figure></div>
                    <h3>24x7</h3>
                    Book yourself for the best rates. We're here to help 24x7
                </div>
            </div>
        </div>
        <div class="row application-panel">
            <div class="application-bg">
                <div class="container marginauto">
                    <h2 class="download-headding">Gozocabs – Inter-City Taxi App</h2>
                    <div class="row application-box">
                        <div class="col-xs-12 col-sm-6">
                            <div class="row">
                                <div class="col-sm-4">
                                    Installs
                                    <h3 class="m0">10,000 - 50,000</h3>
                                </div>
                                <div class="col-sm-3">
                                    Current Version
                                    <h3 class="m0">1.19.70414</h3>
                                </div>
                                <div class="col-sm-5">
                                    Requires Android
                                    <h3 class="m0">4.1 and up</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 text-right">
                            <b class="m0">Book with Gozo cabs mobile app<a href="https://play.google.com/store/apps/details?id=com.gozocabs.client" target="_blank"><img src="/images/GooglePlay.png?v1.1" alt="Gozocabs APP"></a></b>
                        </div>

                    </div>

                </div>
            </div>
            <div class="row hidden-xs routes-panel">
                <div class="container">
                    <div class="col-xs-12">
                        <div class="row border-bottom2 pb20">
                            <div class="col-xs-12 col-sm-5 text-center wow fadeInLeft animated" style="visibility: visible; animation-delay: 0.4s; animation-name: fadeInLeft;" data-wow-delay="0.4s">
                                <h2 class="m0">INDIA</h2>
                                <h2 class="mt0"><a href="tel:+919051877000" style="text-decoration: none; color: #636363">(+91) 90518-77-000</a></h2>
                            </div>
                            <div class="col-xs-12 col-sm-2 text-center">
                                <div class="call-panel"><span style="visibility: visible; animation-delay: 0.4s; animation-name: fadeInUp;" data-wow-delay="0.4s">
                                        <img src="/images/24x7.png" alt="" >
                                    </span></div>
                            </div>
                            <div class="col-xs-12 col-sm-5 text-center wow fadeInRight animated">
                                <h2 class="m0 text-uppercase" style="visibility: visible; animation-delay: 0.6s; animation-name: fadeInRight;" data-wow-delay="0.8s">International</h2>
                                <h2 class="mt0"><a href="tel:+16507414696" style="text-decoration: none; color: #636363">(+1) 650-741-GOZO</a></h2>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 text-center routes-link">
                                <h3 class="text-center">Routes</h3>
                                <ul class="list-inline list-unstyled">
									<?php
									$rutList			 = Yii::app()->cache->get("popularRoute_" . $GLOBALS["rutName"]);
									if ($rutList === false)
									{
										$rutList = Route::model()->popularRoute($GLOBALS["rutName"]);
										Yii::app()->cache->set("popularRoute_" . $GLOBALS["rutName"], $rutList, 604800);
									}
									//$rutList = Route::model()->popularRoute($GLOBALS["rutName"]);
									if (count($rutList) > 0)
									{
										foreach ($rutList as $rut)
										{
											?>
											<li class="p0 pt5">
												<a href="/book-taxi/<?php echo $rut['rutname']; ?>" style="font-weight:600; color: #333"><? echo $rut['from_city']; ?> to <? echo $rut['to_city']; ?></a>
											</li>
											<?
										}
									}
									?>
                                </ul>
                            </div>
                            <div class="col-xs-12 text-center routes-link">
                                <h3 class="text-center mt0">Cities</h3>
                                <ul class="list-inline list-unstyled">
									<?php
									$ctyList = Cities::model()->popularCities();
									if (count($ctyList) > 0)
									{
										foreach ($ctyList as $cty)
										{
											?>
											<li class="p0 pt5">
												<a href="/car-rental/<?php echo strtolower($cty['cty_alias_path']); ?>" style="font-weight:600; color: #333"><? echo $cty['cty_name']; ?></a>
											</li>
											<?
										}
									}
									?>    
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row hidden-xs routes-panel">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12">
                            <h1 class="text-center">Official Partner</h1>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt30 mb30 hidden-xs">
                <div class="col-xs-12 col-sm-6 text-center wow fadeInLeftBig animated" style="visibility: visible; animation-delay: 0.4s; animation-name: fadeInLeftBig;" data-wow-delay="0.4s">
                    <div class="footer_img"><figure><img src="/images/app_design.png?v1.1" alt="Gozocabs App"></figure></div>
                    <b class="m0 mt10">Book with Gozo cabs mobile app</b>
                    <div class="mt10"><figure><a href="https://play.google.com/store/apps/details?id=com.gozocabs.client" target="_blank"><img src="/images/GooglePlay.png?v1.1" alt="Gozocabs APP" style="height: 70px"></a></figure></div>
                </div>
                <div class="col-xs-12 col-sm-6 wow fadeInRightBig animated" style="visibility: visible; animation-delay: 0.4s; animation-name: fadeInRightBig;" data-wow-delay="0.4s">
                    <div class="call-panel2"><i class="fa fa-map-marker fa-5x"></i></div>
                    <address>
                        <h3 class="text-uppercase">
                            Address:</h3>
                        <b><?= $address ?></b>
                    </address>
                    <h4 class="mb0">Phone No.:</h4>
                    <p><b>(+91) 90518-77-000 (24x7)<br>
                            (+1) 650-741-GOZO (24x7)</b></p>
                    <h4>Email:</h4>
                    <figure><img src="/images/email_img.png?v1.1" alt="Email">
                        <a href="mailto:info@aaocab.com" style="text-decoration: none">info@aaocab.com</a>
                    </figure>
                </div>
            </div>
            <footer class="footer">
                <nav class="nav">
                    <div class="row">
                        <div class="col-xs-12 footer-bg">
                            <a href="/ask-us-to-be-official-partner">Ask Us To Be Official Partner</a>|<a href="/business-travel">Business Travel</a>|<a href="/for-startups">For Startups</a>|<a href="/your-travel-desk">Your Travel Desk</a>|<a href="/join-our-agent-network">Join Our Agent Network</a>|<a href="/brand-partner">Brand Partner</a>|<a href="/price-guarantee">Price Guarantee</a><br>
                            <a href="/">Home</a>|<a href="/blog">Blog</a>|<a href="/aboutus">About Us</a>|<a href="/faq">FAQS</a>|<a href="/contactus">Contact Us</a>|<a href="/careers">Careers</a>|<a href="/terms">Terms and Conditions</a>|<a href="/disclaimer">Disclaimer</a>|<a href="/privacy">Privacy Policy</a>|<a href="/sitemap">Sitemap</a>|<a href="/one-way-cab">One Way Cabs</a><br>
                            © <?= date("Y") ?> Gozo Technologies Pvt. Ltd. All Rights Reserved.
                        </div>
                    </div>
                </nav>
            </footer>
        </div>
        <div id="androidModal" class="modal modal-transparent fade" data-backdrop="static" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content" style="box-shadow: none; border: 0; background: transparent">
                    <div class="modal-body p0 text-center">
                        <div style="text-align: right">
                            <a href="#" class="btn btn-primary mb5" data-dismiss="modal"><i class="fa fa-close mr5"></i>close</a>
                        </div>
                        <div>
                            <figure>
                                <a href="https://play.google.com/store/apps/details?id=com.gozocabs.client" target="_blank" rel="nofollow">
                                    <img src="/images/android_app.jpg?v1.2" alt="Gozocabs App" style="max-width: 95%"/>
                                </a>
                            </figure>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--Start of Tawk.to Script-->
        <script type="text/javascript">
            callbackLogin = '';
            formFill = '';
//            var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
//            (function () {
//                var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
//                s1.async = true;
//                s1.src = 'https://embed.tawk.to/5747d08cd5acf00878ac8808/default';
//                s1.charset = 'UTF-8';
//                s1.setAttribute('crossorigin', '*');
//                s0.parentNode.insertBefore(s1, s0);
//            })();
//            Tawk_API.onLoad = function () {
//                var piwikId = Piwik.getAsyncTracker().getVisitorId();
//                Tawk_API.setAttributes({
//                    'PiwikId': piwikId
//                }, function (error) {
//                });
//            };

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
<?php
$detect		 = Yii::app()->mobileDetect;
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

                    try {
                        var fn = callbackLogin + '(' + data1.userdata + ')';
                        eval(fn);
                    } catch (e) {
                        alert(e);
                    }
                }
                $('#navbar_sign').html(data1.rNav);
                $('#userdiv').hide();
                //fillUserform(data1);
                //            if (typeof hideLoginDiv == 'function') {
                //                hideDiv();
                //            }
            };

            function fillUserform(data) {

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
</body>
<?php
$time				 = Filter::getExecutionTime();
$GLOBALS['time99']	 = $time;

$this->endContent();
?>