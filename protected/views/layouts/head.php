<?php
Yii::app()->clientScript->registerPackage("web");
Yii::app()->clientScript->registerPackage("webEnd");
//Yii::app()->clientScript->registerCSSFile(ASSETS_URL . '/css/site.css?v1');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
		<link rel="manifest" href="/manifest.json">
		<link rel="preconnect" href="https://embed.tawk.to">
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://onesignal.com">
		<link rel="preconnect" href="https://maps.gstatic.com">
		<link rel="preconnect" href="http://www.googletagmanager.com">
		<link rel="preconnect" href="https://connect.facebook.net">
		<link rel="preconnect" href="https://stats.g.doubleclick.net">
		<link rel="preconnect" href="https://web.facebook.com">
		<link rel="preconnect" href="http://www.facebook.com">

        <script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async></script>
        <meta name="google-site-verification" content="5JEqiMjViFVWGKtv22A7eplvB9LBgQIUVpEZQfHtGFo" />
        <meta charset="utf-8">
		
		<?php
		if ($this->ampPageEnabled == 1)
		{
			$canonicalUrl = 'amp' . Yii::app()->request->url;
			?>
			<link rel="amphtml" href="<?php echo Yii::app()->createAbsoluteUrl($canonicalUrl); ?>" />
		<?php }
		?>
		<?php
		$this->widget("application.widgets.SeoHead", [
			'defaultKeywords'	 => "outstation taxi,oneway, outstation-taxi-india, shared outstation, Car Rental, inter city taxi service, Car Hire, Taxi Service, Cab Service, Cab Hire, Taxi Hire ,Cab Rental, Taxi Booking, Rent A Car, Car Rental India, Online Cab Booking, Taxi Cab , Car Rental Service, Online Taxi Booking, Local Taxi Service, Cheap Car Rental , Car Rental, Car Hire Services, Car Rentals India, Taxi Booking India, Cab Booking India Car For Hire, Taxi Services, Online Car Rentals , Book A Taxi , Book A Cab, Car Rentals Agency India, Car Rent In India, India Rental Cars, India Cabs, Rent Car In India, Car Rental India, India Car Rental, Rent A Car India, Car Rental In India, Rent A Car In India, India Car Rental Company, Corporate Car Rental India, Car Rental Company In India",
			'defaultDescription' => "Gozo cabs is the best rated AC cab service for outstation travel in India. Book for cheapest and best service. 24x7 phone and online customer support."
		]);
		?>
        <!-- Sets initial viewport load and disables zooming  -->
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" >
		<?php
		$controller	 = Yii::app()->getController()->getAction()->controller->id;
		$view		 = "/" . Yii::app()->getController()->getAction()->controller->action->id;
		$url		 = $controller . $view;
		if ($url == 'index/index')
		{
			#if(Yii::app()->getController()->getAction()->controller->action->id=='index'){
			?>
			<!-- og tag start here-->
			<meta property="og:title" content="Gozo Cabs - Best one-way, outstation cabs in India">
			<meta property="og:site_name" content="aaocab">
			<meta property="og:url" content="http://www.aaocab.com/">
			<meta property="og:description" content="Best and Cheapest one-way cabs, outstation cabs and many more taxies and cabs booking services online in India. ">
			<meta property="og:type" content="business.business">
			<meta property="og:image" content="http://www.aaocab.com/images/logo2_outstation.png?v1.2">

			<!-- og tag end here-->
			<?php
		}
		if (Yii::app()->controller->id == 'users' && Yii::app()->controller->action->id == 'refer')
		{
			?>

			<script>

	            var $baseUrl = "<?= Yii::app()->getBaseUrl(true) ?>";
	            //old app id 488018534722292
	            window.fbAsyncInit = function ()
	            {
	                FB.init({
	                    appId: '1760374187589468',
	                    cookie: true,
	                    xfbml: true,
	                    version: 'v2.11'
	                });

	                FB.AppEvents.logPageView();

	            };

	            (function (d, s, id)
	            {
	                var js, fjs = d.getElementsByTagName(s)[0];
	                if (d.getElementById(id))
	                {
	                    return;
	                }
	                js = d.createElement(s);
	                js.id = id;
	                js.src = "https://connect.facebook.net/en_US/sdk.js";
	                fjs.parentNode.insertBefore(js, fjs);
	            }(document, 'script', 'facebook-jssdk'));
			</script>
			<?php
		}
		if (APPLICATION_ENV == 'production' && Yii::app()->params['enableTracking'] == true)
		{
			?>
			<!-- Global site tag (gtag.js) - Google Analytics -->
			<script async src="http://www.googletagmanager.com/gtag/js?id=UA-34493806-1"></script>
			<script>
	            window.dataLayer = window.dataLayer || [];
	            function gtag()
	            {
	                dataLayer.push(arguments);
	            }
	            gtag('set', 'linker', {"domains": ["www.aaocab.com", "m.aaocab.com", "www-aaocab-com.cdn.ampproject.org"]});
	            gtag('js', new Date());
	            gtag('config', 'UA-34493806-1');
			</script>

			<script>
	            var OneSignal = window.OneSignal || [];
	            OneSignal.push(["init", {
	                    appId: "6d601857-29c5-484c-ba4e-f7b547f3165f",
	                    autoRegister: true,
	                    notifyButton: {
	                        enable: false  /* Set to false to hide */
	                    }
	                }]);
			</script>
			<!-- Google Tag Manager -->
			<script>(function (w, d, s, l, i)
	            {
	                w[l] = w[l] || [];
	                w[l].push({'gtm.start': new Date().getTime(), event: 'gtm.js'});
	                var f = d.getElementsByTagName(s)[0], j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
	                j.async = true;
	                j.src = 'http://www.googletagmanager.com/gtm.js?id=' + i + dl;
	                f.parentNode.insertBefore(j, f);
	            })(window, document, 'script', 'dataLayer', 'GTM-T73295');</script>
			<!-- End Google Tag Manager -->
		<?php }
		?>
        <link rel="shortcut icon" href="<?= IMAGE_URL ?>/fav-icon.png">
        <!-- site css -->
        <link rel="stylesheet" href="/css/font-awesome/css/font-awesome.css" async/>
        <link rel="stylesheet" href="<?= ASSETS_URL . '/css/site.css?v1.07' ?>" async/>
        <link rel="icon" type="image/x-icon" href="<?= IMAGE_URL ?>/favicon/favicon.ico"/>
        <script type="text/javascript">
            jQuery(function ()
            {
                jQuery(".style2b").jCarouselLite({
                    btnNext: ".next_button",
                    btnPrev: ".previous_button",
                    visible: 3,
                    scroll: 1,
                    auto: 0
                });
            });

        </script>
        <script type="text/javascript">
            jQuery(function ()
            {
                jQuery(".style2c").jCarouselLite({
                    btnNext: ".next_button1",
                    btnPrev: ".previous_button1",
                    visible: 3,
                    scroll: 1,
                    auto: 0
                });
            });
        </script>
        <script type="text/javascript">
            jQuery(function ()
            {
                jQuery(".style2d").jCarouselLite({
                    btnNext: ".next_button2",
                    btnPrev: ".previous_button2",
                    visible: 3,
                    scroll: 1,
                    auto: 0
                });
            });
        </script>
        <script type="text/javascript">
            jQuery(function ()
            {
                jQuery(".style2e").jCarouselLite({
                    btnNext: ".next_button3",
                    btnPrev: ".previous_button3",
                    visible: 3,
                    scroll: 1,
                    auto: 0
                });
            });
        </script>
        <script>
            var $baseUrl = "<?= Yii::app()->getBaseUrl(true) ?>";
            function ajaxindicatorstart(text)
            {
                if (jQuery('body').find('#resultLoading').attr('id') != 'resultLoading')
                {
                    jQuery('body').append('<div id="resultLoading" style="display:none"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/ajax-loader1.gif"><div>' + text + '</div></div><div class="bg"></div></div>');
                }

                jQuery('#resultLoading').css({
                    'width': '100%',
                    'height': '100%',
                    'position': 'fixed',
                    'z-index': '10000000',
                    'top': '0',
                    'left': '0',
                    'right': '0',
                    'bottom': '0',
                    'margin': 'auto'
                });

                jQuery('#resultLoading .bg').css({
                    'background': '#ddd',
                    'opacity': '0.6',
                    'width': '100%',
                    'height': '100%',
                    'position': 'absolute',
                    'top': '0'
                });

                jQuery('#resultLoading>div:first').css({
                    'width': '250px',
                    'height': '75px',
                    'text-align': 'center',
                    'position': 'fixed',
                    'top': '0',
                    'left': '0',
                    'right': '0',
                    'bottom': '0',
                    'margin': 'auto',
                    'font-size': '16px',
                    'z-index': '10',
                    'color': '#111'

                });

                jQuery('#resultLoading .bg').height('100%');
                jQuery('#resultLoading').fadeIn(100);
                jQuery('body').css('cursor', 'wait');
            }

            function ajaxindicatorstop()
            {
                jQuery('#resultLoading .bg').height('100%');
                jQuery('#resultLoading').fadeOut(100);
                jQuery('body').css('cursor', 'default');
            }
        </script>
		<?php
		if (Yii::app()->params['enableTracking'] == true)
		{
			?>
			<!-- Piwik -->
			<script type="text/javascript">
	            var _paq = _paq || [];
	<?php
	$userId = Yii::app()->user->id;
// If used is logged-in then call 'setUserId' 
// $userId variable must be set by the server when the user has successfully authenticated to your app.
	if (isset($userId))
	{
		echo sprintf("_paq.push(['setUserId', '%s']);", $userId);
	}
	?>
	            _paq.push(['trackPageView']);
	            _paq.push(['enableLinkTracking']);
	            (function ()
	            {
	                var u = "//piwik.aaocab.com/";
	                _paq.push(['setTrackerUrl', u + 'piwik.php']);
	                _paq.push(['setSiteId', 1]);

	                var d = document, g = d.createElement('script'), s = d.getElementsByTagName('script')[0];
	                g.type = 'text/javascript';
	                g.async = true;
	                g.defer = true;
	                g.src = u + 'piwik.js';
	                //s.parentNode.insertBefore(g, s);
	            })();

	            function getVisitorId()
	            {
	                var userId = '<?= Yii::app()->user->id ?>';
	                return userId;

	            }
			</script>
		<noscript><p><figure><img src="//piwik.aaocab.com/piwik.php?idsite=1" style="border:0;" alt="" /></figure></p></noscript>
	<!-- End Piwik Code -->
	<?php /* 	<script type="text/javascript" src="<?= (Yii::app()->request->getIsSecureConnection() ? "https" : "http") ?>://piwik.aaocab.com/plugins/ClickHeat/libs/js/clickheat.js"></script>
	  <noscript><p><a href="http://www.dugwood.com/clickheat/index.html">ClickHeat</a></p></noscript>
	  <script type="text/javascript">
	  <!--
	  clickHeatSite = 1;
	  clickHeatGroup = encodeURIComponent(window.location.pathname + window.location.search);
	  clickHeatServer = "<?= (Yii::app()->request->getIsSecureConnection() ? "https" : "http") ?>://piwik.aaocab.com/plugins/ClickHeat/libs/click.php";
	  initClickHeat();
	  //-->
	  </script>
	 * 
	 */ ?>
<?php } ?>     
</head>


<?= $content; ?>

<!-- page -->
<?php
$time = Filter::getExecutionTime();
echo "<!--" . $time * 1000 . "-->";
if (Yii::app()->params['enableTracking'] == true)
{
	?>

	<noscript><div class="statcounter"><a title="web analytics"
										  href="http://statcounter.com/" target="_blank" rel="nofollow"><img
				class="statcounter"
				src="http://c.statcounter.com/10628859/0/c65a4c4b/1/"
				alt="web analytics"></a></div></noscript>
	<!-- End of StatCounter Code for Default Guide -->
	<!--	<script type="text/javascript">
		var _gaq = _gaq || [];
		_gaq.push(['_setAccount', 'UA-34493806-1']);
		_gaq.push(['_trackPageview']);
		(function ()
		{
			var ga = document.createElement('script');
			ga.type = 'text/javascript';
			ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0];
			s.parentNode.insertBefore(ga, s);
		})();
	</script>-->
	<script>
	    function trackPage(url)
	    {
	        gtag('config', 'UA-34493806-1', {'page_path': url});
	        //_gaq.push(['_trackPageview', url]);
	        _paq.push(['setCustomUrl', url]);
	        _paq.push(['trackPageView']);
	    }
	    /*	    var sc_project = 10628859;
	     var sc_invisible = 1;
	     var sc_security = "c65a4c4b";
	     var scJsHost = (("https:" == document.location.protocol) ? "https://secure." : "http://www.");
	     document.write("<sc" + "ript type='text/javascript' src='" + scJsHost + "statcounter.com/counter/counter.js'></" + "script>");*/

	</script>
	<!-- Facebook Pixel Code -->
	<script>
	    //	const el = document.querySelector('img');


	    !function (f, b, e, v, n, t, s)
	    {
	        if (f.fbq)
	            return;
	        n = f.fbq = function ()
	        {
	            n.callMethod ?
	                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
	        };
	        if (!f._fbq)
	            f._fbq = n;
	        n.push = n;
	        n.loaded = !0;
	        n.version = '2.0';
	        n.queue = [];
	        t = b.createElement(e);
	        t.async = !0;
	        t.src = v;
	        s = b.getElementsByTagName(e)[0];
	        s.parentNode.insertBefore(t, s)
	    }(window, document, 'script',
	            'https://connect.facebook.net/en_US/fbevents.js');
	    fbq('init', '1760374187589468');
	    fbq('track', 'PageView');
	</script>
	<noscript>
	<img height="1" width="1" src="http://www.facebook.com/tr?id=1760374187589468&ev=PageView&noscript=1"/>
	</noscript>
	<!-- End Facebook Pixel Code -->
	<?php
}
else
{
	?><script>
	        function trackPage(url)
	        {
	        }
	</script>
	<?php
}
?>
<script>
    const observer = lozad(); // passing a `NodeList` (e.g. `document.querySelectorAll()`) is also valid
    observer.observe();
</script>
</html>



