

<?php
Yii::app()->clientScript->registerPackage("jqueryV3");
Yii::app()->clientScript->registerPackage("webV3");
Yii::app()->clientScript->registerPackage("webVendor");
Yii::app()->clientScript->registerPackage("fonts");
$version = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/aao/v3/home.js?v=' . $version, CClientScript::POS_HEAD);
Yii::app()->clientScript->registerPackage("webV3End");
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/aao/rap.js?v=' . $version, CClientScript::POS_HEAD);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
		<link rel="manifest" href="/manifest.json">
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="http://www.googletagmanager.com/">
		<link rel="preconnect" href="https://bat.bing.com/">
		<!--		<link rel="preconnect" href="https://connect.facebook.net/">
				<link rel="preconnect" href="http://www.facebook.com/">-->
		
		<link rel="preconnect" href="https://images.aaocab.com/">
<!--        <meta name="google-site-verification" content="5JEqiMjViFVWGKtv22A7eplvB9LBgQIUVpEZQfHtGFo" />-->
        <meta charset="utf-8">
		<?php
		if ($this->ampPageEnabled == 1)
		{
			$canonicalUrl = 'amp' . Yii::app()->request->url;
			?>
			<link rel="amphtml" href="<?php echo Yii::app()->createAbsoluteUrl($canonicalUrl); ?>" />
			<?php
		}

//		$this->widget("application.widgets.SeoHead", [
//			'defaultKeywords'	 => "car rental, taxi service, cab booking, airport transfer, city tour, long-distance trip, One way cab services, outstation taxi, local taxi, oneway, inter city taxi service, Car Hire, Taxi Service, Cab Service, Car Rental India, Online Cab Booking, Online Taxi Booking, Local Taxi Service, Cheap Car Rental, Car Rentals India, Taxi Booking India, Online Car Rentals, Book A Taxi, Book A Cab, Car Rentals Agency India, Car Rent In India, Corporate Car Rental India, Car Rental Company In India",
//			'defaultDescription' => "aaocab is India's leading online cab booking platform, offering a wide range of taxi services, including hourly car rentals, airport transfers, and one way or round trip taxi for a long road trip. Book your car rental or taxi today and enjoy a hassle-free journey!"
//		]);
		Yii::app()->clientScript->registerCssFile("/res/app-assets/css/style.css?v=" . Yii::app()->params['sitecssVersion']);

//		$code = Yii::app()->request->getParam('sid');
//		if ($code != '')
//		{
			$amount		 = Yii::app()->params['invitedAmount'];
			$userId		 = QrCode::model()->find('qrc_code=:code AND qrc_ent_type=1 AND qrc_active=1 AND qrc_status=3', ['code' => $code])->qrc_ent_id;
			$qrLink		 = Yii::app()->createAbsoluteUrl('rating/downloadQrCode', ['userId' => $userId]);
			$bodyTitle	 = 'Dear Friend, I wanted to introduce you to aaocab.com. I used it recently for my long distance taxi travel. You may find them useful to address your long distance travel needs and quality service.aaocab is India’s leader in long distance taxi travel. Please visit  https://www.aaocab.com/' . $code . ' to register and get a credit of ' . $amount . ' points towards your future travel needs';
			?>
			<meta property="og:url" content="<?php //echo 'https://aaocab.com/' . $code; ?>">
			<meta property="og:type" content="website">
			<meta property="og:title" content="Best rated one way outstation cab service.24x7x365. Pan India : Aao Cabs">
			<meta property="og:description" content="<?php echo $bodyTitle; ?>">
			<meta property="og:image" content="<?php echo $qrLink; ?>">

			<!-- Twitter Meta Tags -->
			<meta name="twitter:card" content="summary_large_image">
			<meta property="twitter:domain" content="http://www.aaocab.com/">
			<meta property="twitter:url" content="<?php //?>">
			<meta name="twitter:title" content="Best rated one way outstation cab service. 24x7x365. Pan India : Aao Cabs">
			<meta name="twitter:description" content="<?php //echo $bodyTitle; ?>">
			<meta name="twitter:image" content="<?php //echo $qrLink; ?>">


		<?php //} ?>
        <!-- Sets initial viewport load and disables zooming  -->
		<meta name="viewport" content="width=device-width, initial-scale=1" >
		<!--		<link as="image" rel="preload" href="/images/gozo_svg_logo.svg" type="image/svg+xml" fetchpriority='high' />-->
		<!--		<link rel="preload" href="/res/app-assets/vendors/css/boxicons/fonts/boxicons.woff2"  as="font" type="font/woff2" crossorigin/>-->
		<script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async></script>

		
		<script>
			let gtagId = 'G-4TQDEZYH5H';
			window.dataLayer = window.dataLayer || [];
			function initGTag(params)
			{
				if (params !== undefined && params !== null)
				{
					gtag('config', gtagId, params);
				}
				else
				{
					gtag('config', gtagId);
				}
			}

			function initUserId(userId)
			{
				let params = {'user_id': userId};
				dataLayer.push(params);
			}

			function gtag()
			{
				dataLayer.push(arguments);
			}

			function trackPage(url)
			{
				gtag('set', 'page_path', url);
				gtag('event', 'page_view');
				gtag('send', 'pageview', url);
			}

			function trackPurchase(data)
			{
				gtag("event", "purchase", data);
			}

			function addToCart(data)
			{
				gtag("event", "add_to_cart", data);
			}

			function beginCheckout(data)
			{
				gtag("event", "begin_checkout", data);
			}

			function trackLogin(method)
			{
				gtag("event", "login", {
					method: method
				});
			}

			function trackSignUp(method)
			{
				gtag("event", "sign_up", {
					method: method
				});
			}

			function trackPaymentInfo(data)
			{
				gtag("event", "add_payment_info", data);
			}

<?= (UserInfo::getGA4UserId() > 0) ? "initUserId('" . UserInfo::getGA4UserId() . "');" : "" ?>

			//	gtag('set', 'linker', {'domains': ["www.aaocab.com", "m.aaocab.com", "www-aaocab-com.cdn.ampproject.org"]});
			//gtag('js', new Date());
			//	initGTag();

		</script>
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
		if (Yii::app()->params['enableTracking'] == true)
		{
			?>
			<link rel="preload" href="http://www.googletagmanager.com/gtm.js?id=GTM-T869HBL" as="script" fetchpriority='low' />
			<link rel="preload" href="http://www.googletagmanager.com/gtag/js?id=G-4TQDEZYH5H&l=dataLayer&cx=c" as="script" fetchpriority='low' />
			<link rel="preload" href="https://bat.bing.com/bat.js" as="script" fetchpriority='low' />
			<!--				
			<link rel="preload" href="https://connect.facebook.net/en_US/fbevents.js" as="script" fetchpriority='low' />
				<link rel="preload" href="https://bat.bing.com/p/action/187021924.js" as="script" fetchpriority='low' />-->

			<!-- Google Tag Manager -->
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
				var OneSignal = window.OneSignal || [];
				OneSignal.push(["init", {
						appId: "6d601857-29c5-484c-ba4e-f7b547f3165f",
						autoRegister: true,
						notifyButton: {
							enable: false  /* Set to false to hide */
						}
					}]);
			</script>
		<?php }
		?>
<!--        <link rel="shortcut icon" href="<?= IMAGE_URL ?>/fav-icon.png"/>
        <link rel="icon" type="image/x-icon" href="<?= IMAGE_URL ?>/favicon/fav.png?v=0.1"/>-->

		<script>
			var VISITOR;

			

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
		?>
		<?//$content; ?>

		<!-- page ----->
	<input type="hidden" inputmode="numeric" autocomplete="one-time-code" pattern="\d{6}" required />
	<?php
	$time = Filter::getExecutionTime();
	echo "<!--" . $time * 1000 . "-->";
	?>
	<script type="text/javascript">
		function hideBack()
		{

			$(".backButton").hide();
			$(".backButton").unbind("click");
		}

		function showBack(step = null)
		{
			$(".backButton").show();
			$(".backButton").unbind("click").on("click", function()
			{
				goBack(step);
			});
		}

		function convertToString(obj)
		{
			let str = obj;
			try
			{
				if (obj instanceof jQuery)
				{
					str = obj.prop("outerHTML");
				}
				else
				{
					str = JSON.stringify(obj);
				}
			}
			catch (e)
			{
				
				str = typeof obj;
				str = str + obj;
			}
			return str;
		}

		function blockForm(elem=null)
		{
			if(elem == null)
			{
				elem = $('body');
			}
			//   debugger;
			let objElem = elem;

			if (!(elem instanceof jQuery))
			{
				objElem = $(elem);
			}
			
			if(elem.length == 0)
			{
				objElem = $('elem');
			}
			

			let elems = ['form', 'div.tab-content', 'div.container', 'body'];

			elems.every(function(val)
			{
				block_ele = objElem.closest(val);
				if (block_ele !== undefined)
				{
					return false;
				}
				return true;
			});

			try
			{
				block_ele.block({
					message: '<div class="loader"></div>',
					overlayCSS: {
						backgroundColor: "#FFF",
						opacity: 0.8,
						cursor: 'wait'
					},
					css: {
						border: 0,
						padding: 0,
						backgroundColor: 'transparent'
					}
				});
			}
			catch (e)
			{

				
			}


		}

		function unBlockForm(elem)
		{
			let objElem = elem;

			if (!(elem instanceof jQuery))
			{
				objElem = $(elem);
			}
			let elems = ['form', 'div.tab-content', 'div.container', 'body'];

			elems.every(function(val)
			{
				block_ele = objElem.closest(val);
				if (block_ele !== undefined)
				{
					return false;
				}
				return true;
			});
			try
			{
				block_ele.unblock();
			}
			catch (e)
			{
				
			}
		}

		function goBack(step = 0)
		{

			history.go(-1);
		}

		var mySwiper = {};
		function startSwipe(element)
		{
			let elementId = element.getAttribute('id');
			mySwiper[elementId] = new Swiper(element, {
				spaceBetween: 1,
				slidesPerView: 4,
				autoplay:
						{
							delay: 1500,
						},
				loop: true,
				navigation: {
					nextEl: ".swiper-button-next",
					prevEl: ".swiper-button-prev",
				},
				breakpoints: {

					1200: {
						slidesPerView: 4,
						spaceBetween: 50
					},
					1024: {
						slidesPerView: 3,
						spaceBetween: 40
					},
					840: {
						slidesPerView: 3,
						spaceBetween: 30
					},
					640: {
						slidesPerView: 2,
						spaceBetween: 20
					},
					300: {
						slidesPerView: 1,
						spaceBetween: 10
					}
				}
			});
			$(element).mouseleave(function()
			{
				let elementId = element.getAttribute('id');
				mySwiper[elementId].autoplay.start();
			});
			$(element).mouseenter(function()
			{
				let elementId = element.getAttribute('id');
				mySwiper[elementId].autoplay.stop();
			});
		}

		var observer = new IntersectionObserver(function(entries)
		{
			entries.forEach(function(element)
			{
				let elementId = element.target.getAttribute('id');
				console.log("validating element: " + elementId);
				if (element.isIntersecting === true && mySwiper[elementId] === undefined)
				{
					console.log("starting swiper: " + elementId);
					startSwipe(element.target);
					observer.unobserve(element.target);
				}
			});
		}, {threshold: [0]});
		const boxElList = document.querySelectorAll(".swiper-container");
		boxElList.forEach((el) => {
			observer.observe(el);
		});
	</script>
	<script>
		$('.collapse').collapse({toggle: false});
		function getCbmUrl(reftype)
		{
			href2 = "/";
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

		function reqCMB(reftype, bkgId = '', msg = '')
		{
			var href2 = getCbmUrl(reftype);
			$.ajax({
				"url": href2,
				data: {'reftype': reftype, 'bkgId': bkgId, 'msg': msg},
				"type": "GET",
				"dataType": "html",
				"success": function(data)
				{
					var scqreason = $($.parseHTML(data)).find(".scqreason").text();
					$('#bkCommonModelHeader').text('Request Call back');
					$('#bkScqReason').text(scqreason);
					$('#bkCommonModelBody').html(data);
					$('#bkCommonModel').modal('show');
				},
				"error": function(xhr, ajaxOptions, thrownError)
				{
					if (xhr.status == "403" || xhr.status == "401")
					{
						//var callback = "reqLogin(data1, " + reftype + ")";
						showLogin(function()
						{
							reqCMB(reftype);
						});
						window.sessionStorage.removeItem("rdata");
						window.sessionStorage.removeItem('returnURL');
						//                      window.sessionStorage.setItem('callback', callback);
//                        var href2 = "<?php //echo Yii::app()->createUrl('users/signin')                       ?>";
//                        $.ajax({
//                            "url": href2,
//                            "data": {"signup": 3},
//                            "type": "GET",
//                            "dataType": "html",
//                            "success": function (data)
//                            {
//                                $('#bkCommonModelBody').html(data);
//                                $('#bkCommonModel').modal('show');
//                            }
//                        });
					}
				}
			});
			return false;
		}

	</script>
	<script>
		var lazyLoadInstance = new LazyLoad({
			elements_selector: ".lozad"
		});
		var introBox = null;
		function showGozonowPromt()
		{
			if (typeof $skipShowGozonowPromt !== 'undefined')
			{
				return;
			}

			var date = new Date();
			var today = (date.getTime());
			var daysDifference = 0;
			// var secondsDifference =0;
			var storage = window.localStorage.getItem("gozonowPromt");
			if (storage)
			{
				var parseStorage = JSON.parse(storage);
				var storeDay = parseStorage.timestamp;
				var difference = today - storeDay;
				daysDifference = Math.floor(difference / 1000 / 60 / 60 / 24);
				//secondsDifference = Math.floor(difference/1000);
			}


			if (daysDifference > 5 || (storage == null && daysDifference < 5))
			{

				introBox = bootbox.dialog({
					title: "Important notice",
					message: "Now book all your last minute travel requirements on the website and get live market rates from our suppliers directly on our platform. We are committed to bring you quality cabs at best possible prices even at the last hour.",
					className: "important-notice",
					onEscape: function()
					{

						var date = new Date();
						var object = {flag: 1, timestamp: date.getTime()};
						localStorage.setItem("gozonowPromt", JSON.stringify(object));
						bootbox.hideAll();
					}
				});
			}
		}


		function getVisitor()
		{
			let vid = '<?= Yii::app()->request->cookies['gvid'] ?>';
			if (!vid)
			{
				var href = "<?php echo Yii::app()->createUrl('users/regVisitor') ?>";
				$.ajax({
					type: 'GET',
					url: href,
					async: true,
					success: function(data1)
					{
						//  $.cookie("gvid", data1);
					}
				});
			}
		}
		var loginBox = null;
		var loginCallback = null;
		function showLogin(callback, showPhone = 0, form=null)
		{
			var param = {};
			if (showPhone != 0)
			{
				param = {phone: showPhone};
			}

			if (callback === undefined || callback == null)
			{
				window.sessionStorage.removeItem("loginCallback");
			}


			trackPage('<?= Yii::app()->createUrl("users/signin"); ?>');
			$.ajax({type: 'GET',
				url: '<?= Yii::app()->createUrl("users/signin"); ?>',
				data: param,
				dataType: "html",
				"beforeSend": function ()
                {
                    blockForm(form);
                },
                "complete": function ()
                {
					unBlockForm(form);
                },
				success: function(data)
				{
					// debugger;
					var data2 = "";
					var isJSON = false;
					try
					{
						data2 = JSON.parse(data);
						isJSON = true;
					}
					catch (e)
					{
					}
					if (callback != undefined && callback != null)
					{
						if (isJSON && data2.success)
						{
							callback();
							return;
						}
						loginCallback = callback;
						window.sessionStorage.setItem("loginCallback", callback.toString());
					}
					isJSON = false;
					var jsonData = null;
					try
					{
						jsonData = JSON.parse(data2);
						isJSON = true;
					}
					catch (e)
					{

					}
					if (!isJSON)
					{
						if (loginBox != null)
						{
							loginBox.modal('hide');
						}

						loginBox = bootbox.dialog({
							message: data,
							onShow: function(e)
							{
								if (window.google !== undefined)
								{
									initGoogleSignin();
								}
							},
							onEscape: function()
							{
								loginCallback = null;
								if (loginBox == null)
								{
									bootbox.hideAll();
								}
								else
								{
									loginBox.modal('hide');
								}
								loginBox = null;
							}
						});
					}
					else if (jsonData != null && jsonData.success == true)
					{
						if (callback != null)
						{
							callback();
						}
					}
				}
			});
		}

		function closeLoginFinal()
		{

			if (loginBox != null)
			{
				loginBox.modal('hide');
			}

		}
		function closeLogin(returnVal = false)
		{
			//debugger;
			if (returnVal != 1)
			{
				if (loginBox != null)
				{
					loginBox.modal('hide');
				}
			}
			processLogin();
			if (loginCallback != null)
			{
				if (returnVal !== undefined && returnVal !== false)
				{
					loginCallback(returnVal);
				}
				else
				{
					loginCallback();
				}
			}
			else
			{
				var callback = window.sessionStorage.getItem("loginCallback");
				if (callback != null)
				{
					callback = eval("(" + callback + ")");
					callback();
				}
		}
		}
		function processLogin()
		{
			if (returnUrl != '' && returnUrl != '/')
			{
				location.href = returnUrl;
			}
			else if (loginBox != null)
			{
				refreshNavBar();
				
			}
			else
			{
				location.href = "/";
			}
		}
		function refreshNavBar()
		{
			try
			{
				if ($(".userNavBar").length > 0)
				{
					$.ajax({type: 'GET',
						url: '<?= Yii::app()->createUrl("users/refreshNav"); ?>',
						dataType: "html",
						success: function(data)
						{
							$(".userNavBar").html(data);
						}});
				}
				if ($(".homeNavBar").length > 0)
				{
					$.ajax({type: 'GET',
						url: '<?= Yii::app()->createUrl("index/refreshHomeNav"); ?>',
						dataType: "html",
						success: function(data)
						{
							$(".homeNavBar").html(data);
						}});
				}

				if ($(".cabtravellerinfo").length > 0)
				{
					$.ajax({type: 'GET',
						url: '<?= Yii::app()->createUrl("booking/refreshTravellerInfo"); ?>',
						dataType: "html",
						success: function(data)
						{
							$(".cabtravellerinfo").html(data);
							var isLoggedIn = '<?= Yii::app()->user->isGuest ?>';
							if (isLoggedIn)
							{
								cabsvcId = $('#cabsvcId').val();
								checkTierQuotes(cabsvcId);
							}

						}});
				}

			}
			catch (e)
			{

			}
		}
		function handleException(xhr, callback = null, form=null)
		{
			if (xhr.status == "403")
			{
				showLogin(function()
				{
					if (callback != null)
					{
						callback();
					}
				}, 0, form);
		}

		}


		function displayError(form, messages)
		{
			let content = "";
			try
			{
				content = buildMessage(messages);
				let settings = form.data('settings');
				$.each(settings.attributes, function(i)
				{
					$.fn.yiiactiveform.updateInput(settings.attributes[i], errors, form);
				});
				$.fn.yiiactiveform.updateSummary(form, errors);
				var summaryAttributes = [];
				for (var i in settings.attributes)
				{
					if (settings.attributes[i].summary)
					{
						summaryAttributes.push(settings.attributes[i].id);
					}
				}

				$('#' + settings.summaryID).toggle(content !== '').find('ul').html(content);
			}
			catch (exception)
			{
				console.log(exception.message);
				var message = "<ul>" + content + "</ul>";
				toastr['error'](message, 'Failed to process!', {
					closeButton: true,
					tapToDismiss: false,
					timeout: 500000
				});
			}
			return (content == "");
		}

		function buildMessage(messages)
		{
			let content = "";
			let msgs = [];
			for (var key in messages)
			{
				if ($.type(messages[key]) === 'string')
				{
					content = content + '<li>' + messages[key] + '</li>';
					continue;
				}
				$.each(messages[key], function(j, message)
				{
					if ($.type(message) === 'array')
					{
						$.each(messages[key], function(k, v)
						{
							if ($.type(v) == "array")
							{
								$.each(v, function(k1, v1)
								{
									if ($.type(v1) == "array")
									{
										$.each(v1, function(j, message)
										{
											if (msgs.indexOf(message) > -1)
											{
												return;
											}
											msgs.push(message);
											content = content + '<li>' + message + '</li>';
										});
									}
									else
									{
										if (msgs.indexOf(v1) > -1)
										{
											return;
										}
										msgs.push(v1);
										content = content + '<li>' + v1 + '</li>';
									}
								});
							}
							else
							{
								$.each(v, function(j, message)
								{
									if (msgs.indexOf(message) > -1)
									{
										return;
									}
									msgs.push(message);
									content = content + '<li>' + message + '</li>';
								});
							}
						});
					}
					else
					{
						if (msgs.indexOf(message) > -1)
						{
							return;
						}
						msgs.push(message);
						content = content + '<li>' + message + '</li>';
					}
				});
			}

			return content;
		}

		let deferredPrompt;
		window.addEventListener('beforeinstallprompt', (e) => {
			console.log("beforeinstallprompt");
			// Prevent Chrome 67 and earlier from automatically showing the prompt
			e.preventDefault();
			console.log("beforeinstallprompt1");
			// Stash the event so it can be triggered later.
			deferredPrompt = e;
		});
	</script>
	<!--End home page slider-->
        
        <?php echo  "<img src='/images/under_maintenance.jpg'/>";?>
</html>



