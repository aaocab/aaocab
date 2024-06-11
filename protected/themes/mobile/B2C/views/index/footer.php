<style>
    #botmanWidgetRoot > div{
        min-width: 0 !important;
		width: auto !important;
		overflow: visible !important;
		bottom: 35px !important;
    }
    .mobile-closed-message-avatar{ right: 0!important; box-shadow:0 0 0 0!important;}
    .mobile-closed-message-avatar img{ width: 80%!important;} 
</style>

<div id="modal-sign-Up" data-selected="menu-components" data-width="320" data-height="600" class="menu-box menu-modal">
	<div class="menu-title"><a href="#" class="menu-hide mt15 n" id="menubox"><i class="fa fa-times"></i></a>
        <h1>Sign Up to aaocab</h1>
    </div>
    <div class="menu-page signUpBody"></div>   
</div>
<?php
//	$protocol = ((!emptyempty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";  
//	$CurPageURL = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];  

$botConfig = Config::get("bot.mobilesite");
$urlPath = explode('/', $_SERVER["REQUEST_URI"]);
if($urlPath[1] == 'bkpn'){
$botConfig["show"] = 0;
}
if ((int) $botConfig["show"] > 0)
{
	?>
<!--<script src='https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/js/widget.js'></script>-->
	<script>
	    var botcokie = 0;
	    $(document).ready(function ()
	    {
	        var isBknw = window.location.href.indexOf("bknw");
	        if (isBknw < 0) {
			<?php if (!isset($_COOKIE['gzbot']) || empty($_COOKIE['gzbot']))
			{ ?>
//							setTimeout(function ()
//							{
//								botmanChatWidget.open();
//								botcokie = botcokie + 1;
//								return false;
//							}, 45000);
			<?php } ?>
	        }
	    });

	    var botmanWidget =
	            {
	                frameEndpoint: '/bot/chat.html',
	                introMessage: "Hi! I'm Sonia, I'm online and can help you",
	                chatServer: '/bot/Bot',
	                title: 'GozoCab',
	                mainColor: '#ec4e04',
	                aboutText: '',
	                bubbleBackground: '',
	                headerTextColor: '#fff',
	                bubbleAvatarUrl: '/images/botpng.gif',
	            };
	</script> 
	<?php
}
?>
<?= $this->renderPartial('/index/footer_routes', []); ?>

<!--<div id="footer-menu" class="footer-menu-5-icons" style="margin-top: -60px;position: inherit">
	<a href="/" class="color-green4-dark default-link"><i class="fa fa-home"></i><span>Home</span></a>
	<a href="/aboutus" class="color-green4-dark"><i class="fas fa-user-alt"></i><span>About Us</span></a>
	<a href="/blog" class="color-green4-dark"><i class="fas fa-blog"></i><span>Blog</span></a>
	<a href="/contactus" class="color-green4-dark"><i class="fas fa-map-marker-alt"></i><span>Contact</span></a>
	<a href="#" data-menu="menu-list-bottom3" class="color-green4-dark"><i class="fas fa-bars"></i><span>Menu</span></a>
	<div class="clear"></div>
</div>-->


<!--<div class="footer footer-new pl0 pr0 pt10">
	<div class="footer-link">
		<ul>
			<li><a href="/" class="default-link">Home</a></li>
                        <li><a href="/aboutus">About Us</a></li>
                        <li><a href="/blog">Blog</a></li>
                        <li><a href="#" data-menu="menu-list-bottom3"><i class="fas fa-bars font-16" style="padding-bottom: 2px"></i></a></li>
			
			
		</ul>
			
	</div>
	<div class="clear"></div>
	<div class="footer-copyright mt0">© <?php echo date("Y"); ?> Gozo Technologies Pvt. Ltd. All Rights Reserved.</div>
</div>-->

<div id="menu-list-bottom3" data-selected="menu-components" data-height="440" class="menu-box menu-bottom menu-list-bottom">
    <div class="menu-title">
        <h1 class="mt10">Gozo Cabs</h1>
        <a href="#" class="menu-hide"><i class="fa fa-times"></i></a>
    </div>
	<div class="menu-page">
		<ul class="menu-list">
			<li id="menu-index">
				<a href="/contactus">
					<i class="fas fa-map-marker-alt color-green-dark default-link"></i>
					<span>Contact Us</span>
					<i class="fa fa-angle-right"></i>
				</a>
			</li>  
			<li id="menu-componentsx">
				<a href="/careers">
					<i class="fas fa-users color-yellow-dark"></i>
					<span>Careers</span>
					<i class="fa fa-angle-right"></i>
				</a>
			</li>   
			<li id="menu-pages">
				<a href="/terms">
					<i class="fas fa-clipboard-list color-red-dark"></i>
					<span>Terms and Conditions</span>
					<i class="fa fa-angle-right"></i>
				</a>
			</li>    
			<li id="menu-media">
				<a href="/disclaimer">
					<i class="fas fa-exclamation-triangle color-brown-light"></i>
					<span>Disclaimer</span>
					<i class="fa fa-angle-right"></i>
				</a>
			</li>      
			<li id="menu-contact">
				<a href="/privacy">
					<i class="fas fa-shield-alt color-blue-dark"></i>
					<span>Privacy Policy</span>
					<i class="fa fa-angle-right"></i>
				</a>
			</li>
			<li id="menu-contact">
				<a href="#">
					<i class="fas fa-sitemap color-magenta-dark"></i>
					<span>Sitemap</span>
					<i class="fa fa-angle-right"></i>
				</a>
			</li>
			<li id="menu-contact">
				<a href="/one-way-cab" class="default-link">
					<i class="fas fa-arrow-right color-orange-light "></i>
					<span>One Way Cabs</span>
					<i class="fa fa-angle-right"></i>
				</a>
			</li>
			<li id="menu-contact">
				<a href="/ask-us-to-be-official-partner">
					<i class="fas fa-question-circle color-pink-dark "></i>
					<span>Ask Us To Be Official Partner</span>
					<i class="fa fa-angle-right"></i>
				</a>
			</li>
			<li id="menu-contact">
				<a href="/business-travel">
					<i class="fas fa-briefcase color-purple"></i>
					<span>Business Travel</span>
					<i class="fa fa-angle-right"></i>
				</a>
			</li>
			<li id="menu-contact">
				<a href="/for-startups">
					<i class="fas fa-star color-red-dark"></i>
					<span>For Startups</span>
					<i class="fa fa-angle-right"></i>
				</a>
			</li>
			<li id="menu-contact">
				<a href="/your-travel-desk">
					<i class="fas fa-chalkboard-teacher color-sms"></i>
					<span>Your Travel Desk</span>
					<i class="fa fa-angle-right"></i>
				</a>
			</li>
			<li id="menu-contact">
				<a href="/join-our-agent-network">
					<i class="fas fa-network-wired color-google"></i>
					<span>Join Our Agent Network</span>
					<i class="fa fa-angle-right"></i>
				</a>
			</li>
			<li id="menu-contact">
				<a href="/brand-partner">
					<i class="fas fa-user-tie color-red"></i>
					<span>Brand Partners</span>
					<i class="fa fa-angle-right"></i>
				</a>
			</li>
			<li id="menu-contact">
				<a href="/terms/doubleback">
					<i class="fas fa-user-tie color-red"></i>
					<span>Double Back</span>
					<i class="fa fa-angle-right"></i>
				</a>
			</li>
			<li id="menu-contact">
				<a href="/price-guarantee">
					<i class="fas fa-award color-pink-dark"></i>
					<span>Price Guarantee</span>
					<i class="fa fa-angle-right"></i>
				</a>
			</li>
			<li id="menu-contact">
				<a href="/whygozo">
					<i class="fas fa-comments color-pinterest"></i>
					<span>Why aaocab</span>
					<i class="fa fa-angle-right"></i>
				</a>
			</li>
			<li id="menu-contact">
				<a href="/newsroom">
					<i class="fas fa-newspaper color-blue-dark"></i>
					<span>News Room</span>
					<i class="fa fa-angle-right"></i>
				</a>
			</li>
		</ul>
	</div>
	<?php
	$version = Yii::app()->params['siteJSVersion'];
	Yii::app()->clientScript->registerScriptFile(ASSETS_URL . 'js/maskFilter.js');
	?>
	<script type="text/javascript">
        $('#menu-hider, .close-menu, .menu-hide').on('click', function () {
            $('.menu-box').removeClass('menu-box-active');
            $('#menu-hider').removeClass('menu-hider-active');
            return false;
        });
	</script></div>

<div id="map-marker" data-selected="menu-components" class="menu-box menu-sidebar-right-full map-z grid-panel">
	<div id="map-marker-content"></div>
</div> 

<!--<script type="text/javascript" src="<?= ASSETS_URL ?>js/mobile/jquery.js"></script>-->
<style>
	.grid-panel{transition: all 300ms ease 0s; display: inline-grid!important;}
</style>
<!--<script type="text/javascript" src="<?= ASSETS_URL ?>js/mobile/plugins.js"></script>-->
<?php 
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . 'js/mobile/custom.min.js', CClientScript::POS_HEAD);
?>


<script>
        $fromCity = '<?= $datacity ?>';
        var toCity = [];
        var toCity1 = [];
        var toCity2 = [];
        var toCity4 = [];
        var airportList = [];
        var trlocList = [];
        $destCity = null;

        $(document).ready(function ()
        {
            /*$("#bkg_pickup_date_time1").selectize();
             $("#bkg_pickup_date_time2").selectize();
             $("#bkg_pickup_date_time3").selectize();
             $("#bkg_pickup_date_time4").selectize();
             $("#bkg_pickup_date_time5").selectize();*/

            /*$('.date_PickSet').datepicker({
             changeMonth: true,
             format: 'dd/mm/yyyy',
             minDate: 0
             });*/

            var trasferType = $("input[name='BookingTemp[bkg_transfer_type]']:checked").val();
            if (typeof (trasferType) != "undefined") {
                var dlabel = (trasferType == 2) ? 'From Address' : 'To Address';
                var slabel = (trasferType == 1) ? 'From the Airport' : 'To the Airport';
                $('#slabel').text(slabel);
                $('#dlabel').text(dlabel);
                $('#trslabel').text(slabel);
                $('#trdlabel').text(dlabel);
            }
        });
        $sourceList = null;

        function loadSource(query, callback) {
            $.ajax({
                url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/citylist1')) ?>?q=' + encodeURIComponent(query),
                type: 'GET',
                dataType: 'json',
                error: function () {
                    callback();
                },
                success: function (res) {
                    callback(res);
                }
            });
        }

        function loadTime(query, callback) {
            $.ajax({
                url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/timedrop')) ?>?q=' + encodeURIComponent(query),
                type: 'GET',
                dataType: 'json',
                error: function () {
                    callback();
                },
                success: function (res) {
                    callback(res);
                }
            });
        }



        function populatePackage(obj, pckid) {
            obj.load(function (callback) {
                var obj = this;
                if ($sourceList == null) {
                    xhr = $.ajax({
                        url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/package')) ?>',
                        dataType: 'json',
                        data: {
                            pckid: pckid
                        },
                        //  async: false,
                        success: function (results) {
                            $sourceList = results;
                            obj.enable();
                            callback($sourceList);
                            obj.setValue('<?= $model->bkg_package_id ?>');
                        },
                        error: function () {
                            callback();
                        }
                    });
                } else {
                    obj.enable();
                    callback($sourceList);
                    obj.setValue('<?= $model->bkg_package_id ?>');
                }
            });
        }

        function loadPackage(query, callback) {
            $.ajax({
                url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/package')) ?>?q=' + encodeURIComponent(query),
                type: 'GET',
                dataType: 'json',
                error: function () {
                    callback();
                },
                success: function (res) {
                    callback(res);
                }
            });
        }


        function changeDestination(value, obj, dcity) {
            if (!value.length)
                return;
            var existingValue = obj.getValue();
            if (existingValue == '')
            {
                existingValue = dcity;
            }
            obj.disable();
            obj.clearOptions();
            obj.load(function (callback) {
                //  xhr && xhr.abort();
                xhr = $.ajax({
                    url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/nearestcitylist')) ?>/source/' + value,
                    dataType: 'json',
                    success: function (results)
                    {
                        obj.enable();
                        callback(results);
                        obj.setValue(existingValue);
                    },
                    error: function () {
                        callback();
                    }
                });
            });
        }

        $('input[name="BookingTemp[bkg_transfer_type]"]').change(function (event) {
            var trasferType = $(event.currentTarget).val();
            var dlabel = (trasferType == 2) ? 'From Address' : 'To Address';
            var slabel = (trasferType == 1) ? 'From the Airport' : 'To the Airport';
            $('#slabel').text(slabel);
            $('#dlabel').text(dlabel);
            $('#trslabel').text(slabel);
            $('#trdlabel').text(dlabel);
        });

        $('.btnairporttransfer').click(function (event) {
            var trasferType = $(event.currentTarget).data('key');
            if (trasferType == "1") {
                $('#BookingTemp_bkg_transfer_type').val(1);
                $('#slabel').text('From the Airport');
                $('#dlabel').text('Drop Address');
                $('#trslabel').text('From the Airport');
                $('#trdlabel').text('To Address');
            } else {
                $('#BookingTemp_bkg_transfer_type').val(2);
                $('#slabel').text('To the Airport');
                $('#dlabel').text('Pickup Address');
                $('#trslabel').text('To the Airport');
                $('#trdlabel').text('From Address');
            }
        });

        function reqLogin(data1, refType) {
            reqCMB("1");
        }

        function reqCMB(reftype) {
            var href2 = "<?php echo Yii::app()->createUrl('index/newBkgCallback') ?>";
            $.ajax({
                "url": href2,
                data: {'reftype': reftype},
                "type": "GET",
                "dataType": "html",
                "success": function (data) {
                    $('#callmebackmessagebody').html(data);
                    $('a[data-menu="sidebar-right-overcallback"]').click();
                },
                "error": function (xhr, ajaxOptions, thrownError) {
                    if (xhr.status == "403")
                    {
                        //alert("Jai jagannath");
                        var href2 = "<?php echo Yii::app()->createUrl('users/partialsignin', ['callback' => "reqLogin(data1, '1')"]) ?>";
                        $.ajax({
                            "url": href2,
                            "data": {"mobiletheme": 1},
                            "type": "GET",
                            "dataType": "html",
                            "success": function (data) {
                                $('#callmebackloginbody').html(data);
                                $('a[data-menu="menu-login-modal"]').click();
                            }
                        });
                    }
                }
            });
            return false;
        }

        $('#userloginmodal').click(function () {
            var href = '<?= Yii::app()->createUrl('users/Signin') ?>';
            var username = $('#username').val();
            var pass = $('#password').val();
            jQuery.ajax({'type': 'GET', 'url': href, 'dataType': 'html',
                'data': {usr_email: username, usr_password: pass},
                success: function (data)
                {
                    if (data != '') {
                        $('#menu-login-modal').removeClass('menu-box-active');
                        $('#menu-hider').removeClass('menu-hider-active');
                        var data1 = JSON.parse(data)
                        //$(".bkgclsUserId").val(data1.user_id);
                        $(".loggiUser").html("Hi,&nbsp;" + data1.usr_name);
                        //$jsLogin.fillUserform2(data1);					
                        //$jsLogin.fillUserform13(data1);					
                        $("#errmsg_login").addClass("hide");
                        //$('.login-box-container').css("display", "none");
                        reqCMB(1);
                    } else
                    {
                        $("#errmsg_login").removeClass("hide");
                    }

                }
            });
        });
</script>
<script type="text/javascript">

    $('.sinUpModal').click(function () {
        var href2 = "<?= Yii::app()->createUrl('users/signup?is_partial=1', ['callback' => 'refreshNavbar(data1)']) ?>";
        $.ajax({
            "url": href2,
            "data": {"desktheme": 1},
            "type": "GET",
            "dataType": "html",
            "success": function (data) {
                $('.signUpBody').html(data);
            }
        });
        return false;

    });
    /*  $('#Booking_bkg_return_date_date').datepicker({
     format: 'dd/mm/yyyy'
     });
     
     $('#BookingTemp_bkg_pickup_date_date').datepicker({
     format: 'dd/mm/yyyy'
     });*/

    $('.helpline').click(function () {
        openHelpline();
    });

    function openHelpline() {
        var href2 = "<?= Yii::app()->createUrl('scq/helpline') ?>";
        $.ajax({
            "url": href2,
            "data": {"ismobile": true},
            "type": "GET",
            "dataType": "html",
            "success": function (data) {
                $('#helplinebody').html(data);
                var flwup = $('#flwup').val();
                if (flwup) {
                    $('#phonr-hover1').data('height', '250');
                    $('#phonr-hover1 h2').remove();
                }
                $('a[data-menu="phonr-hover1"]').click();
            }
        });
        return false;
    }

    function reqLogin(data1, refType) {
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
	
	function getCustomerNotes(msgtype, rutname)
	{
		cityarr = rutname.split("-");
		switch(msgtype)
		{
			case 9:
				var message = "I want to book Tempo Traveller 9 seater from "+ cityarr[0] +' to '+ cityarr[1];
				break;
			case 12:
				var message = "I want to book Tempo Traveller 12 seater from "+ cityarr[0] +' to '+ cityarr[1];
				break;
			case 15:
				var message = "I want to book Tempo Traveller 15 seater from "+ cityarr[0] +' to '+ cityarr[1];
				break;
			default:
				var message = '';
				break;
		}
		return message;
	}
    function reqCMB(reftype, msgtype, rutname) { 
        debugger;
		var href2 = getCbmUrl(reftype);
		if(typeof(msgtype) != "undefined" && typeof(rutname) != "undefined")
		{
			var notes = getCustomerNotes(msgtype, rutname);
		}
		$.ajax({
            "url": href2,
            data: {"ismobile": true, 'reftype': reftype},
            "type": "GET",
            "dataType": "html",
            "success": function (data) { 
                $('#callmebackmessagebody').html(data);
				$('.scqnotes').text(notes);
				$('a[data-menu="sidebar-right-overcallback"]').click();
            },
            "error": function (xhr, ajaxOptions, thrownError) { 
                if (xhr.status == "401")
                {
                    var callback = "reqLogin(data1, " + reftype + ")";
                    var href2 = "<?php echo Yii::app()->createUrl('users/partialsignin') ?>";
                    $.ajax({
                        "url": href2,
                        "data": {"mobiletheme": 1, 'callback': callback},
                        "type": "GET",
                        "dataType": "html",
                        "success": function (data) {
                            $('#callmebackloginbody').html(data);
                            $('a[data-menu="menu-login-modal"]').click();
                        }
                    });
                }
            }
        });
        return false;
    }
    var obj2 = new MaskFilter();
    obj2.getnameFilter();

    window.onload = function () {
//        document.getElementById('botmanWidgetRoot').childNodes[0].className = 'botclassnewchild';
//        $('.botclassnewchild').click(function () {
//            if (botcokie == 1) {
//                var cookieName = 'gzbot';
//                var cookieValue = 1;
//                var date = new Date();
//                date.setTime(date.getTime() + (24 * 60 * 60 * 1000));
//                var expires = "; expires=" + date.toUTCString();
//                document.cookie = cookieName + "=" + cookieValue + expires + "; path=/";
//            }
//			$('#botmanWidgetRoot').css('display','none');
//        });
//		$('#botmanWidgetRoot').css('display','none');
    };
	
	function clickToChat(){
		botmanChatWidget.open();
		$('#botmanWidgetRoot').css('display','');
		return false;
	}
</script>
<!--Start of Tawk.to Script-->
<script type="text/javascript">
    /*var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
     (function () {
     var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
     s1.async = true;
     s1.src = 'https://embed.tawk.to/5747d08cd5acf00878ac8808/default';
     s1.charset = 'UTF-8';
     s1.setAttribute('crossorigin', '*');
     s0.parentNode.insertBefore(s1, s0);
     })();
     Tawk_API.onLoad = function () {
     var piwikId = Piwik.getAsyncTracker().getVisitorId();
     Tawk_API.setAttributes({
     'PiwikId': piwikId
     }, function (error) {});
     };*/
</script>
<!--End of Tawk.to Script-->