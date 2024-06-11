<?php
$this->beginContent('//layouts/head1');
if ($this->layout == 'column1') {
	$style = "background-color: inherit";
} 
$fixedTop = ($this->fixedTop) ? "navbar-fixed-top" : "";
$bgBanner = ($this->fixedTop) ? "bg-banner" : "";
$address	 = Config::getGozoAddress(Config::Corporate_address, true);
?>
<body>
    <div class="fixed-menu">
        <a href="http://www.facebook.com/gozocabs" target="_blank" class="social-1 hvr-push" rel="nofollow"><i class="fa fa-facebook"></i></a>
        <a href="https://twitter.com/gozocabs" target="_blank" class="social-2 hvr-push" rel="nofollow"><i class="fa fa-twitter"></i></a>
        <a href="https://plus.google.com/b/113163564383201478409/+Gozocabs" target="_blank" class="social-3 hvr-push" rel="nofollow"><i class="fa fa-google-plus"></i></a>
    </div>
    <nav class="navbar navbar-default <?= $fixedTop ?> top-menu">
        <div class="container p0">

            <div class="navbar-header logo ml15 n">
                <div class="row m0"><div class="col-xs-12 text-center">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="/"><img src="/images/logo2.png?v1.1" alt="Gozocabs"></a>
                    </div></div></div>
            <div class="text-right phone-line">
                <div class="nav-phone mb10"><a href="tel:+919051877000" style="text-decoration: none; color: #000"> <img src="/images/india-flag.png" alt="INDIA"> (+91) 90518-77-000<span class="nav-phone-24x7">  (24x7)</span></a></div>
                <div class="nav-phone"><a href="tel:+16507414696" style="text-decoration: none; color: #000"><img src="/images/worl-icon.png" class="mr5" alt="International"> (+1) 650-741-GOZO   (24x7)</a></div>
            </div>
            <div class="clearfix">
                <div class="row m0 text-center">
                    <div class="col-xs-12 col-sm-4 col-lg-5" style="text-shadow: 1px 1px #fff;">
                        <!--                       <a href="/careers"><h2>WE ARE HIRING</h2>
                                                   <h3 class="mt0">APPLY NOW</h3></a>-->
                    </div>
                </div>
            </div>
            <div class=" new-navbar pr10">
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav navbar-right mr0">
                        <!--                            <li><a href="#" class="hvr-overline-from-center">our specials</a></li>-->
                        <li><a href="/vendor/join" class="hvr-overline-from-center">Attach Taxi</a></li>
                        <li><a href="/whygozo" class="hvr-overline-from-center">Why Gozo Cabs</a></li>
                        <li><a href="/blog" class="hvr-overline-from-center">Our Blog</a></li>
                        <li><a href="/faq" class="hvr-overline-from-center">FAQ'S</a></li>
                        <li class="dropdown" id="navbar_sign">
							<?php
							/** @var CController $this */
							$this->renderDynamic('renderPartial',"/users/navbarsign");
							?>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </nav>

    <div class="<?= $bgBanner ?> body-content container-fluid">
		<?= $content ?>

    </div>
    <div class="container">
        <div class="row mt10 mb20 ml0 mr0">
            <div class="col-xs-12 col-sm-3 col-md-3 text-center">
                <img src="/images/img1.png?v1.1" alt="One Way Drop">
                <h4 class="block-color">One Way Drop</h4>
                Why pay for round trip when all you want is a drop at your destination.
            </div>
            <div class="col-xs-12 col-sm-3 col-md-3 text-center">
                <img src="/images/img2.png?v1.1" alt="One Way Drop">
                <h4 class="block-color">Zero Advance</h4>
                No advance payments required. Pay the full amount, directly to the drivers.
            </div>
            <div class="col-xs-12 col-sm-3 col-md-3 text-center">
                <img src="/images/img3.png?v1.1" alt="One Way Drop">
                <h4 class="block-color">Fixed Charges</h4>
                No extra toll, night charges. You pay what is agreed for.
            </div>
            <div class="col-xs-12 col-sm-3 col-md-3 text-center">
                <img src="/images/img4.png?v1.1" alt="One Way Drop">
                <h4 class="block-color">Reliable</h4>
                Fully confirmed bookings. Well behaved and informed drivers.
            </div>
        </div>

    </div>
    <section id="section3" class="">
        <div class="container">
            <div class="call-panel"><i class="fa fa-phone fa-4x"></i></div>
            <div class="col-xs-12 col-sm-6 col-md-6">
                <h4 class="m0 text-center">INDIA</h4>
                <h1 class="mt0 text-center">(+91) 90518-77-000</h1>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6">
                <h4 class="m0 text-center">International</h4>
                <h1 class="mt0 text-center">(+1) 650-741-GOZO</h1>
            </div>

            <div class="col-xs-12 text-center routes-link">
                <h3 class="text-center">Routes</h3>
                <a href="/delhi-chandigarh">Delhi to Chandigarh</a>
                <a href="/delhi-jaipur">Delhi to Jaipur</a>
                <a href="/delhi-shimla">Delhi to Shimla</a>
                <a href="/delhi-manali">Delhi to Manali</a>
                <a href="/delhi-ludhiana">Delhi to Ludhiana</a>
                <a href="/delhi-dehradun">Delhi to Dehradun</a>
                <a href="/delhi-haridwar">Delhi to Haridwar</a>
                <a href="/delhi-mahakumbh">Delhi to Mahakumbh</a>
                <a href="/delhi-nainital">Delhi to Nainital</a>
                <a href="/bangalore-tirupati">Bangalore to Tirupati</a>
                <a href="/chennai-tirupati">Chennai to Tirupati</a>
                <a href="/vijayawada-tirupati">Vijayawada to Tirupati</a>
                <a href="/delhi-mussoorie">Delhi to Mussoorie</a>
                <a href="/delhi-rishikesh">Delhi to Rishikesh</a>
                <a href="/chandigarh-shimla">Chandigarh to Shimla</a>
                <a href="/chandigarh-manali">Chandigarh to Manali</a>
                <a href="/delhi-agra">Delhi to Agra</a>
                <a href="/delhi-ajmer">Delhi to Ajmer</a>
                <a href="/jaipur-ajmer">Jaipur to Ajmer</a>
                <a href="/delhi-jodhpur">Delhi to Jodhpur</a>
            </div>

        </div>
    </section>
    <footer id="footer">
        <div class="container">
            <div class="row m0">    
                <div class="col-xs-12 col-sm-6 col-md-3 column">          
                    <h4 class="orange pt5 pb5 weight400">Gozo Cabs</h4>
                    <ul class="nav">
                        <li><a href="/">Home</a></li>
                        <li><a href="/blog">Blog</a></li>
                        <li><a href="/aboutus">About Us</a></li>
                        <li><a href="/faq">FAQS</a></li>
                        <li><a href="/contactus">Contact Us</a></li>
                        <li><a href="/careers">Careers</a></li>
                        <li><a href="/terms">Terms and Conditions</a></li>
                        <li><a href="/disclaimer">Disclaimer</a></li>
                        <li><a href="/privacy">Privacy Policy</a></li>
                        <li><a href="/sitemap">Sitemap</a></li>
                        <li><a href="/one-way-cabs">One Way Cabs</a></li>
                        <li><a href="/ask-us-to-be-official-partner">Ask Us To Be Official Partner</a></li>
                        <li><a href="/business-travel">Business Travel</a></li>
                        <li><a href="/for-startups">For Startups</a></li>
                        <li><a href="/your-travel-desk">Your Travel Desk</a></li>
                        <li><a href="/join-our-agent-network">Join Our Agent Network</a></li>
                        <li><a href="/brand-partner">Brand Partner</a></li>
                        <li><a href="/price-guarantee">Price Guarantee</a></li>
                    </ul> 
                </div>
                <div class="col-xs-12 col-sm-6 col-md-5 column">   
                    <a href="https://play.google.com/store/apps/details?id=com.gozocabs.client" target="_blank" rel="nofollow">
                        <img src="/images/GooglePlay.png?v1.1" style="max-width: 200px" /></a>
                    <h4 class="orange pt5 pb5 weight400">Social Media</h4>
                    <div class="social-panel2 p0">
                        <a href="http://www.facebook.com/gozocabs" target="_blank" class="social-1 hvr-push" rel="nofollow"><i class="fa fa-facebook"></i></a><a href="https://twitter.com/gozocabs" target="_blank" class="social-2 hvr-push" rel="nofollow"><i class="fa fa-twitter"></i></a><a href="https://plus.google.com/b/113163564383201478409/+Gozocabs" target="_blank" class="social-3 hvr-push" rel="nofollow"><i class="fa fa-google-plus"></i></a>                    </div>

                </div>
                <div class="col-xs-12 col-sm-6 col-md-4 column">          
                    <h4 class="orange pt5 pb5 weight400">Address</h4>
					<?= $address ?>
                    <div class="address-panel p0 ">
                        <h4 class="m0 mb10">Call</h4>
                        <p><a href="tel:+919051877000" style="text-decoration: none"><img src="/images/india-flag.png" alt="India"> (+91) 90518-77-000 (24x7)</a></p>
                        <p><a href="tel:+16507414696" style="text-decoration: none"><img src="/images/worl-icon.png" alt="World"> (+1) 650-741-GOZO (24x7)</a></p>
                    </div>
                    <div class="address-panel p0 ">
                        <h4 class="m0 mb10">E-mail</h4>
                        <a href="mailto:info@aaocab.com" style="text-decoration: none">info@aaocab.com.</a>
                    </div>
                </div>

            </div><!--/row-->
        </div>
        <div class="blue2 mt20 text-center white-color pt15 pb15">© 2016 www.aaocab.com. All rights reserved.</div>
    </footer>
    <!-- Modal -->
    <div id="androidModal" class="modal modal-transparent fade" data-backdrop="static" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content" style="box-shadow: none; border: 0">
                <div class="modal-body p5 text-center">
                    <a href="#" class="btn btn-primary mb30 pull-right" data-dismiss="modal"><i class="fa fa-close mr5"></i>close</a>
                    <div>
                        <a href="https://play.google.com/store/apps/details?id=com.gozocabs.client" target="_blank" rel="nofollow">
                            <img src="/images/mobile_app.jpg?v1.1"  width="80%"/>
                        </a></div>
                </div>
            </div>

        </div>
    </div>
    <!--Start of Tawk.to Script-->
	<script type="text/javascript">
//        var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
//        (function () {
//            var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
//            s1.async = true;
//            s1.src = 'https://embed.tawk.to/5747d08cd5acf00878ac8808/default';
//            s1.charset = 'UTF-8';
//            s1.setAttribute('crossorigin', '*');
//            s0.parentNode.insertBefore(s1, s0);
//        })();
//        Tawk_API.onLoad = function () {
//            var piwikId = Piwik.getAsyncTracker().getVisitorId();
//            Tawk_API.setAttributes({
//                'PiwikId': piwikId
//            }, function (error) {});
//        };
	</script>
	<!--End of Tawk.to Script-->
    <!-- Start of StatCounter Code for Default Guide -->
    <script type="text/javascript">
<?php
$detect = Yii::app()->mobileDetect;
// call methods
$isMobile = $detect->isMobile() && $detect->is("AndroidOS");
if ($isMobile) {
	?>
	        $(document).ready(function () {
	            $valid = $.cookie('androidModal');
	            if ($valid == undefined || !$valid) {
	                $('#androidModal').modal('show');
	                $.cookie('androidModal', true, {expires: 1});
	            }
				alert("<?=$detect->is("AndroidOS")?>");
	        });
	<?
}
?>
    </script>
</body>
<?php $this->endContent(); ?>