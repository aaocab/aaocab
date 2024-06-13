<?php
$cssversion	 = Yii::app()->params['sitecssVersion'];
$jsversion	 = Yii::app()->params['siteJSVersion'];
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="http://www.googletagmanager.com/">
		<link rel="preconnect" href="https://bat.bing.com/">
<!--		<link rel="preconnect" href="https://connect.facebook.net/">
		<link rel="preconnect" href="http://www.facebook.com/">-->
		
		<link rel="preconnect" href="https://images.aaocab.com/">

		<link rel="manifest" href="/manifest.json">
        <meta name="google-site-verification" content="5JEqiMjViFVWGKtv22A7eplvB9LBgQIUVpEZQfHtGFo" />
        <meta charset="utf-8">
		<?php
		$this->widget("application.widgets.SeoHead", [
			'defaultKeywords'	 => "outstation taxi,oneway, outstation-taxi-india, shared outstation, Car Rental, inter city taxi service, Car Hire, Taxi Service, Cab Service, Cab Hire, Taxi Hire ,Cab Rental, Taxi Booking, Rent A Car, Car Rental India, Online Cab Booking, Taxi Cab , Car Rental Service, Online Taxi Booking, Local Taxi Service, Cheap Car Rental , Car Rental, Car Hire Services, Car Rentals India, Taxi Booking India, Cab Booking India Car For Hire, Taxi Services, Online Car Rentals , Book A Taxi , Book A Cab, Car Rentals Agency India, Car Rent In India, India Rental Cars, India Cabs, Rent Car In India, Car Rental India, India Car Rental, Rent A Car India, Car Rental In India, Rent A Car In India, India Car Rental Company, Corporate Car Rental India, Car Rental Company In India",
			'defaultDescription' => "Gozo cabs is the best rated AC cab service for outstation travel in India. Book for cheapest and best service. 24x7 phone & online customer support."
		]);
		?>
		<link as="image" rel="preload" href="/images/gozo_svg_logo.svg" type="image/svg+xml" fetchpriority='high' />
		<link as="image" rel="preload" href="/images/bx-arrowright.svg" type="image/svg+xml" fetchpriority='high'/>
		<link rel="preload" href="http://www.googletagmanager.com/gtm.js?id=GTM-T869HBL" as="script" fetchpriority='low' />
		<link rel="preload" href="http://www.googletagmanager.com/gtag/js?id=G-4TQDEZYH5H&l=dataLayer&cx=c" as="script" fetchpriority='low' />
		<link rel="preload" href="https://bat.bing.com/bat.js" as="script" fetchpriority='low' />
<!--		<link rel="preload" href="https://connect.facebook.net/en_US/fbevents.js" as="script" fetchpriority='low' />-->
		<link rel="preload" href="https://bat.bing.com/p/action/187021924.js" as="script" fetchpriority='low' />
		<link rel="shortcut icon" href="<?= IMAGE_URL ?>/fav-icon.png"/>
        <link rel="icon" type="image/x-icon" href="<?= IMAGE_URL ?>/favicon/fav.png?v=0.1"/>
		<?php
		if ($this->ampPageEnabled == 1)
		{
			$canonicalUrl = 'amp' . Yii::app()->request->url;
			?>
			<link rel="amphtml" href="<?php echo Yii::app()->createAbsoluteUrl($canonicalUrl); ?>" />
			<?php
		}

		Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/css/mobile/framework.min.css?v=6.121');
///		Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/css/mobile/framework-store.min.css');
		Yii::app()->clientScript->registerCssFile(ASSETS_URL . 'css/mobile/style.min.css?' . $cssversion);
		Yii::app()->clientScript->registerScriptFile(ASSETS_URL . 'js/jquery.lazyload.min.js');
		?>
		<?php
		if (APPLICATION_ENV == 'production' && Yii::app()->params['enableTracking'] == true)
		{
			?>
			<script type="text/javascript">
				(function(w, d, s, l, i)
				{
					w[l] = w[l] || [];
					w[l].push({'gtm.start': new Date().getTime(), event: 'gtm.js'});
					var f = d.getElementsByTagName(s)[0], j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
					j.async = true;
					j.src = 'http://www.googletagmanager.com/gtm.js?id=' + i + dl;
					f.parentNode.insertBefore(j, f);
				})(window, document, 'script', 'dataLayer', 'GTM-T869HBL');
				window.dataLayer = window.dataLayer || [];
				function gtag()
				{
					dataLayer.push(arguments);
				}
				gtag('set', 'linker', {"domains": ["www.aaocab.com", "m.aaocab.com", "www-aaocab-com.cdn.ampproject.org"]});
				gtag('js', new Date());
				gtag('config', 'G-4TQDEZYH5H');
				function trackPage(url)
				{
					gtag('set', 'page_path', url);
					gtag('event', 'page_view');
					gtag('send', 'pageview', url);
				}

				var OneSignal = window.OneSignal || [];
				OneSignal.push(["init", {appId: "6d601857-29c5-484c-ba4e-f7b547f3165f", autoRegister: true, notifyButton: {enable: false  /* Set to false to hide */}}]);<!-- Facebook Pixel Code -->
			</script>

					<?php
				}
				else
				{
					?>
					<script type="text/javascript">
							function trackPage(url)
							{
								console.log("trackPage:" + url);						
							}
						</script>
							<?php
						}
						?>

							<script type="text/javascript">
						$(document).ready(function()
						{
							Sentry.init({dsn: 'https://981df2c06421445ea83713d1792260fd@sentry1.gozo.cab/4', environment: 'MOBILE-<?= APPLICATION_ENV ?>'});
						});
							</script>													
								<script type="text/javascript">
									var $baseUrl = "<?= Yii::app()->getBaseUrl(true) ?>";
							$.fn.popover = function(){};
							$.fn.tooltip = function(){};
							
							function ajaxindicatorstart(text)
							{
								if (jQuery('body').find('#resultLoading').attr('id') !== 'resultLoading')
								{
									jQuery('body').append('<div id="resultLoading" style="display:none"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/ajax-loader1.gif"><div>' + text + '</div></div><div class="bg"></div></div>');
								}

								jQuery('#resultLoading').css({
									'width': '100%', 'height': '100%', 'position': 'fixed',
									'z-index': '10000000', 'top': '0', 'left': '0', 'right': '0',
									'bottom': '0', 'margin': 'auto'
								});
								jQuery('#resultLoading .bg').css({
									'background': '#ddd', 'opacity': '0.6', 'width': '100%',
									'height': '100%', 'position': 'absolute', 'top': '0'
								});
								jQuery('#resultLoading>div:first').css({
									'width': '50px', 'height': '75px', 'text-align': 'center', 'position': 'fixed',
									'top': '0', 'left': '0', 'right': '0', 'bottom': '0',
									'margin': 'auto', 'font-size': '16px', 'z-index': '10', 'color': '#111'
								});
								jQuery('#resultLoading .bg').height('100%');
								jQuery('#resultLoading').fadeIn(100);
								jQuery('body').css('cursor', 'wait');
							}

									function ajaxindicatorstop(									)
									{
								jQuery('#resultLoading .bg').height('100%');
								jQuery('#resultLoading').fadeOut(100);
								jQuery('body').css('cursor', 'default																		');
									}
		</script>
	</head>

									<?php
									echo $content;

									$type = Yii::app()->request->getParam('app');
									if ($type == 1)
									{
										?>
										<script type="text/javascript">
											jQuery(".btn-orange").hide();
										</script>
									<?php } ?>

							</html>
