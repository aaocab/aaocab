<?php
$version = Yii::app()->params['sitecssVersion'];
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . "/res/v2d/css/bootstrap.min.css?v0.1");
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . "/res/v2d/css/style.css?v=" . $version);
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . "/res/v2d/css/newstyle.css?v=3");
Yii::app()->clientScript->registerScriptFile(APP_ASSETS . "/js/jcarousellite.min.js");
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/res/v2d/js/bootstrap.min.js");
//Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/res/v2d/js/bootstrap.bundle.min.js', CClientScript::POS_END);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
		<link rel="manifest" href="/manifest.json">
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="http://www.googletagmanager.com/">
		<link rel="preconnect" href="https://connect.facebook.net/">
		<link rel="preconnect" href="http://www.clarity.ms/">
		<link rel="preconnect" href="https://cdn.onesignal.com/">
		<link rel="preconnect" href="https://browser.sentry-cdn.com/">		
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
			'defaultDescription' => "Gozo cabs is  the best rated AC cab services with driver for all India. Book in advance for cheapest prices. 24 x 7 customer support by web chat or phone. One way cab services. Round trips. Airport transfers. Package tours. Shared Taxis. Intercity Shuttle service. More comfortable & cheaper than going by bus or train. Serving in 3000+ cities. Great Price & Top Quality guaranteed."
		]);
		?>
        <!-- Sets initial viewport load and disables zooming  -->
		<meta name="viewport" content="width=device-width, initial-scale=5, maximum-scale=5" >
		<!--		<link rel="preload" href="https://browser.sentry-cdn.com/7.64.0/bundle.min.js" as="script" />-->
		<!--		<link rel="preload" href="https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/js/widget.js" as="script"/>-->
        <script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async></script>
		<script
			src="https://browser.sentry-cdn.com/7.76.0/bundle.min.js"
			integrity="sha384-E6cl5rBqghgWmQzeZzIeEiCZlZ2jwbXjwezpP0iC13ZLtLuFw6YhyuazvzcASt0t"
			crossorigin="anonymous"
		></script>
		<?php
		if (Yii::app()->controller->id == 'users' && Yii::app()->controller->action->id == 'refer')
		{
			?>

			<script>

				var $baseUrl = "<?= Yii::app()->getBaseUrl(true) ?>";
				//old app id 488018534722292
				window.fbAsyncInit = function()
				{
					FB.init({
						appId: '1760374187589468',
						cookie: true,
						xfbml: true,
						version: 'v2.11'
					});

					FB.AppEvents.logPageView();

				};

				(function(d, s, id)
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
			<script>(function(w, d, s, l, i)
				{
					w[l] = w[l] || [];
					w[l].push({'gtm.start':
								new Date().getTime(), event: 'gtm.js'});
					var f = d.getElementsByTagName(s)[0],
							j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
					j.async = true;
					j.src =
							'http://www.googletagmanager.com/gtm.js?id=' + i + dl;
					f.parentNode.insertBefore(j, f);
				})(window, document, 'script', 'dataLayer', 'GTM-T869HBL');</script>
			<!-- End Google Tag Manager -->
			<script>
				window.dataLayer = window.dataLayer || [];
				function gtag()
				{
					dataLayer.push(arguments);
				}
				gtag('set', 'linker', {'domains': ["www.aaocab.com", "m.aaocab.com", "www-aaocab-com.cdn.ampproject.org"]});
				gtag('js', new Date());
				gtag('config', 'G-4TQDEZYH5H');
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
	<!--			<script>(function (w, d, s, l, i)
				{
					w[l] = w[l] || [];
					w[l].push({'gtm.start': new Date().getTime(), event: 'gtm.js'});
					var f = d.getElementsByTagName(s)[0], j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
					j.async = true;
					j.src = 'http://www.googletagmanager.com/gtm.js?id=' + i + dl;
					f.parentNode.insertBefore(j, f);
				})(window, document, 'script', 'dataLayer', 'GTM-T73295');</script>-->
			<!-- End Google Tag Manager -->
		<?php
		}
		//Yii::app()->clientScript->registerCSSFile("/res/v2d/fontawesome-web/css/all.css?v0.2");
		Yii::app()->clientScript->registerCSSFile("/assets/mtnc/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css");
		Yii::app()->clientScript->registerCSSFile("https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap");
		?>
        <!-- site css -->
<!--		<script src="https://kit.fontawesome.com/0122d5ef5d.js"></script>-->
		<!--        <link rel="stylesheet" href="/res/v4d/css/style.css?v0.2" async/>-->
        <link rel="shortcut icon" href="<?= IMAGE_URL ?>/fav-icon.png">
        <link rel="icon" type="image/x-icon" href="<?= IMAGE_URL ?>/favicon/fav.png?v=0.1"/>
		<script>
			$(document).ready(function()
			{
				Sentry.init({dsn: 'https://981df2c06421445ea83713d1792260fd@sentry1.gozo.cab/4', environment: 'DESKTOP-<?= APPLICATION_ENV ?>'});
			});
		</script>
		<script type="text/javascript">
			$(window).scroll(function()
			{
				if ($(window).scrollTop() >= 92)
				{
					$('header').addClass('fixed-header');
					$('header').addClass('sticky');
					$('header div').addClass('visible-title');
				}
				else
				{
					$('header').removeClass('fixed-header');
					$('header').removeClass('sticky');
					$('header div').removeClass('visible-title');
				}
			});
		</script>


        <script type="text/javascript">
			jQuery(function()
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
			jQuery(function()
			{
				jQuery(".style2c").jCarouselLite({
					btnNext: ".next_button1",
					btnPrev: ".previous_button1",
					visible: 3,
					scroll: 1,
					auto: 1
				});
			});
        </script>
        <script type="text/javascript">
			jQuery(function()
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
			jQuery(function()
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
				(function()
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
			gtag('set', 'page_path', url);
			gtag('event', 'page_view');
			gtag('send', 'pageview', url);
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



