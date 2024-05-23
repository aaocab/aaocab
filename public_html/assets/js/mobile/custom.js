//$(window).on('load',function(){
////	setTimeout(function(){$("#preloader").addClass('hide-preloader');},50);// will fade out the white DIV that covers the website.
//});

$(document).ready(function()
{
	'use strict';

	function init_template()
	{
		var header_lines = $('.header-line-2, .header-line-1');
		var header = $('.header');
		var header_tabs = $('.header-tabs');
		var header_search = $('.header-search');
		var menu_box = $('.menu-box');
		var menu_hider = $('#menu-hider');
		var page_transitions = $('#page-transitions');
		var page_content = $('.page-content');
		var movable_items = $('.header, .page-content');

		//Activating Menus After Page Load
		setTimeout(function()
		{
			menu_box.css({"display": "block"});
		}, 0);

		//Activating Menu Functions on Data Click
		$('a[data-menu]').on('click', function()
		{
			menu_box.removeClass('menu-box-active');
			var menuID = $('#' + $(this).data('menu'));
			var menuSelect = menuID.data('selected');
			var menuTitle = menuID.data('title');
			var menuSubTitle = menuID.data('subtitle');
			var menuLoad = menuID.data('load');
			var menuHeight = menuID.data('height');
			var menuWidth = menuID.data('width');
			menuID.addClass('menu-box-active');
			menu_hider.addClass('menu-hider-active');

			if (menuID.data('height'))
			{
				menuID.css({'height': menuHeight})
				menuID.css({'top': 'auto'})
			}

			if (menuSubTitle === '')
			{
				menuID.find('.menu-title h1').css({'margin-bottom': '10px'});
				menuID.find('.menu-title h1').css({'margin-top': '20px'});
				menuID.find('.menu-title .menu-hide').css({'margin-top': '-2px'});
			}

			if (menuID.hasClass('menu-modal'))
			{
				menuID.css({
					'height': menuHeight,
					'width': menuWidth,
					'margin-top': (menuHeight / 2) * (-1),
					'margin-left': (menuWidth / 2) * (-1)
				});
			}

			if (menuID.hasClass('menu-sidebar-left-push'))
			{
				movable_items.addClass('move-contents-left');
			}
			if (menuID.hasClass('menu-sidebar-right-push'))
			{
				movable_items.addClass('move-contents-right');
			}
			if (menuID.hasClass('menu-sidebar-left-parallax'))
			{
				movable_items.addClass('parallax-contents-left');
			}
			if (menuID.hasClass('menu-sidebar-right-parallax'))
			{
				movable_items.addClass('parallax-contents-right');
			}

			if (menuID.data('load'))
			{
				menuID.find('#' + menuSelect).addClass('menu-active');
				menuID.find('.menu-title h1').html(menuTitle);
				menuID.find('.menu-title span').html(menuSubTitle);
			}
			return false;
		});


//        menu_modal.each(function(){
//            var modalHeight = menu_modal.data('height');
//            var modalWidth = menu_modal.data('width');
//            menu_modal.css({
//                'height':modalHeight,
//                'width':modalWidth,
//                'margin-top':(modalHeight/2)*(-1),
//                'margin-left':(modalWidth/2)*(-1)
//            });
//        });

		//Hiding the menu on click.
		$('#menu-hider, .close-menu, .menu-hide').on('click', function()
		{

			menu_box.removeClass('menu-box-active');
			menu_hider.removeClass('menu-hider-active');
			movable_items.removeClass('move-contents-left move-contents-right parallax-contents-left parallax-contents-right');
			header_lines.removeClass('move-contents-left move-contents-right');
			page_content.removeClass('move-contents-left move-contents-right');
			$('.search-header a').removeClass('search-close-active');
			$('#search-page').removeClass('move-search-list-up');
			return false;
		});




		//Header Pretitle
		if ($('.header-pretitle').length)
		{
			$('.header-title').css({'margin-top': '8px'});
		}


		//Back To Top & Back Button
		$('.back-button').on('click', function()
		{
			page_transitions.addClass('back-button-clicked');
			page_transitions.removeClass('back-button-not-clicked');
			window.history.go(-1);
			return false;
		});
		$('.back-to-top-badge, .back-to-top').on("click", function(e)
		{
			e.preventDefault();
			$('html, body').animate({
				scrollTop: 0
			}, universalTransitionTime);
			return false;
		});


		//Accordion
		var accordion_trigger = $('a[data-accordion]');
		var accordion_content = $('.accordion-content');
		$(document).on("click", "a[data-accordion]", function()
		{
			var accordion_number = $(this).data('accordion');
			$('.accordion-content').hide();
			accordion_content.slideUp(200);
			var already = 0;
			if ($(this).find('i:last-child').hasClass("rotate-180"))
			{
				$(this).find('i:last-child').removeClass('rotate-180');
				$('#' + accordion_number).slideUp(200);
				already = 1;
			}
			if (already == 0)
			{
				$('.accordion i').removeClass('rotate-180');
				if ($('#' + accordion_number).is(":visible"))
				{
					$('#' + accordion_number).slideUp(200);
					$(this).find('i:last-child').removeClass('rotate-180');
				}
				else
				{
					$('#' + accordion_number).slideDown(200);
					$(this).find('i:last-child').addClass('rotate-180');
				}
			}
		});

		//Preload Image
		$(function()
		{
			$(".preload-image").lazyload();
		});

		//Show Back To Home When Scrolling
		$(window).on('scroll', function()
		{
			var total_scroll_height = document.body.scrollHeight;
			var inside_header = ($(this).scrollTop() <= 100);
			var passed_header = ($(this).scrollTop() >= 0); //250
			var passed_header2 = ($(this).scrollTop() >= 150); //250
			var footer_reached = ($(this).scrollTop() >= (total_scroll_height - ($(window).height() + 300)));
			var window_top = $(window).scrollTop();
			var content_height = $('.page-content').height();
			var window_height = $(window).height();
			var totalScroll = (window_top / (content_height - window_height)) * 100;

			if (inside_header === true)
			{
				$('.back-to-top-badge').removeClass('back-to-top-badge-visible');
				$('.scroll-ad').removeClass('scroll-ad-visible');
				if (header.hasClass('header-scroll-effect'))
				{
					header.removeClass('header-effect');
					header_tabs.removeClass('header-tabs-effect');
					header_search.removeClass('header-search-effect');
				}
			}
			else if (passed_header === true)
			{
				$('.back-to-top-badge').addClass('back-to-top-badge-visible');
				$('.scroll-ad').addClass('scroll-ad-visible');
				if (header.hasClass('header-scroll-effect'))
				{
					header.addClass('header-effect');
					header_tabs.addClass('header-tabs-effect');
					header_search.addClass('header-search-effect');
				}
			}
			if (footer_reached == true)
			{
				$('.back-to-top-badge').removeClass('back-to-top-badge-visible');
			}

			$(".reading-line").css("width", totalScroll + "%");
		});


		var formSubmitted = "false";

		//Universal Transition Timing
		var universalTransitionTime = 300;
		setTimeout(function()
		{
			$('.header, .header-line-1, .header-line-2, .header-tabs, .header-search, .footer, .menu-box, #menu-hider, .menu-hider-active, .page-content').css({
				WebkitTransition: 'all ' + universalTransitionTime + 'ms ease',
				MozTransition: 'all ' + universalTransitionTime + 'ms ease',
				MsTransition: 'all ' + universalTransitionTime + 'ms ease',
				OTransition: 'all ' + universalTransitionTime + 'ms ease',
				transition: 'all ' + universalTransitionTime + 'ms ease'
			});
		}, 0);

		$('head').append('<meta name="apple-mobile-web-app-capable" content="yes">');
		var isMobile = {
			Android: function()
			{
				return navigator.userAgent.match(/Android/i);
			},
			iOS: function()
			{
				return navigator.userAgent.match(/iPhone|iPad|iPod/i);
			},
			Windows: function()
			{
				return navigator.userAgent.match(/IEMobile/i);
			},
			any: function()
			{
				return (isMobile.Android() || isMobile.iOS() || isMobile.Windows());
			}
		};
		if (!isMobile.any())
		{
			$('body').addClass('is-not-ios');
		}
		if (isMobile.Android())
		{
			$('body').addClass('is-not-ios');
			$('head').append('<meta name="theme-color" content="#000000"> />');
		}
		if (isMobile.iOS())
		{
			$('body').addClass('is-ios');
		}

		if (!$('#footer-menu').length)
		{
			$('.footer').addClass('footer-no-padding');
		}

		//Device Has Notch? 
		var deviceHasNotch = "false";
		var deviceNotchSize = "44" //44 pixel is the default notch size


		//Create Cookies
		function createCookie(e, t, n)
		{
			if (n)
			{
				var o = new Date;
				o.setTime(o.getTime() + 48 * n * 60 * 60 * 1e3);
				var r = "; expires=" + o.toGMTString();
			}
			else
			
				var r = "";
			document.cookie = e + "=" + t + r + "; path=/";
		}

		function readCookie(e)
		{
			for (var t = e + "=", n = document.cookie.split(";"), o = 0; o < n.length; o++)
			{
				for (var r = n[o];
						" " == r.charAt(0); )
				
					r = r.substring(1, r.length);
				if (0 == r.indexOf(t))
				
					return r.substring(t.length, r.length)
			}
			return null
		}

		//Cookie Policy
		function eraseCookie(e)
		{
			createCookie(e, "", -1);
		}
		

		//Snackbars
		var snackbar_trigger = $('a[data-deploy-snack]');
		snackbar_trigger.on("click", function()
		{
			var snack_number = $(this).data('deploy-snack');
			$('#' + snack_number).addClass('active-snack');
			setTimeout(function()
			{
				$('#' + snack_number).removeClass('active-snack');
			}, 5000);
		});
		$('.snackbar a').on('click', function()
		{
			$(this).parent().removeClass('active-snack');
		});
		

		//Tabs
		$('.active-tab').slideDown(0);
		$('a[data-tab]').on("click", function()
		{
			var tab_number = $(this).data('tab');
			$(this).parent().find('[data-tab]').removeClass('active-tab-button');
			$(this).parent().parent().find('.tab-titles a').removeClass('active-tab-button');
			$(this).addClass('active-tab-button');
			$(this).parent().parent().find('.tab-item').slideUp(200);
			$('#' + tab_number).slideDown(200);
		});
		$('a[data-tab-pill]').on("click", function()
		{
			var tab_number = $(this).data('tab-pill');
			if (tab_number == 'tab-pill-3a')
			{
				$(this).addClass('active-tab-pill-button active');
			}
			$('.sub-tab').removeClass('active-tab-pill-button active');
			$('[data-sub-tab="' + tab_number + '"]').addClass('active-tab-pill-button active');
			//$('.mainTab').removeClass('active');
			$('.mainTab').removeClass('active');
			$(tab_number).addClass('active');
			//var tab_bg = $(this).parent().parent().find('.active-tab-pill-button').data('active-tab-pill-background');
			$(this).parent().find('[data-tab-pill]').removeClass('active-tab-pill-button active');
			$(this).parent().parent().find('.tab-titles a').removeClass(' active-tab-pill-button active');
			$(this).addClass('active-tab-pill-button active');
			$(this).parent().parent().find('.tab-item').slideUp(200);
			$('#' + tab_number).slideDown(200);
		});

		//Header Tabs
		if ($('.header-tabs').length)
		{
			var header_tabs = $('.header-tabs');
			var header_tabs_number = $('.header-tabs').data('total-tabs');
			var header_tabs_color = $('.header-tabs').data('tabs-color');
			var header_tabs_width = $('.header-tabs').width();
			var header_tabs_active = $('.header-tabs a.active-tab').data('header-tab');
			var header_tab_content = $('.header-tab-content');

			header_tab_content.hide();
			$('#' + header_tabs_active).show();
			$('.header-tabs .active-tab').addClass(header_tabs_color);
			$('.header-tabs a').css({"width": (header_tabs_width / header_tabs_number - 1)})

			$('a[data-header-tab]').on('click', function()
			{
				var header_tab_id = $('#' + $(this).data('header-tab'));
				header_tabs.find('a').removeClass(header_tabs_color);
				$(this).addClass(header_tabs_color)
				header_tab_content.hide();
				header_tab_id.show();
			})
		}

		//Progress Bar
		var progress_bar = $('.progress-bar');
		var progress_bar_wrapper = $('.progress-bar-wrapper');

		if (progress_bar.length > 0)
		{
			progress_bar_wrapper.each(function()
			{
				var progress_height = $(this).data('progress-height');
				var progress_border = $(this).data('progress-border');
				var progress_round = $(this).attr('data-progress-round');
				var progress_color = $(this).data('progress-bar-color');
				var progress_bg = $(this).data('progress-bar-background');
				var progress_complete = $(this).data('progress-complete');
				var progress_text_visible = $(this).attr('data-progress-text-visible');
				var progress_text_color = $(this).attr('data-progress-text-color');
				var progress_text_size = $(this).attr('data-progress-text-size');
				var progress_text_position = $(this).attr('data-progress-text-position');
				var progress_text_before = $(this).attr('data-progress-text-before');
				var progress_text_after = $(this).attr('data-progress-text-after');
				if (progress_round === 'true')
				{
					$(this).find('.progress-bar').css({
						'border-radius': progress_height
					})
					$(this).css({
						'border-radius': progress_height
					})
				}
				if (progress_text_visible === 'true')
				{
					$(this).append('<em>' + progress_text_before + progress_complete + '%' + progress_text_after + '</em>')
					$(this).find('em').css({
						"color": progress_text_color,
						"text-align": progress_text_position,
						"font-size": progress_text_size + 'px',
						"height": progress_height + 'px',
						"line-height": progress_height + progress_border + 'px'
					});
				}
				$(this).css({
					"height": progress_height + progress_border,
					"background-color": progress_bg,
				})
				$(this).find('.progress-bar').css({
					"width": progress_complete + '%',
					"height": progress_height - progress_border,
					"background-color": progress_color,
					"border-left-color": progress_bg,
					"border-right-color": progress_bg,
					"border-left-width": progress_border,
					"border-right-width": progress_border,
					"margin-top": progress_border,
				})
			});
		}

		//Countdown
		function countdown(dateEnd)
		{
			var timer, years, days, hours, minutes, seconds;
			dateEnd = new Date(dateEnd);
			dateEnd = dateEnd.getTime();
			if (isNaN(dateEnd))
			{
				return;
			}
			timer = setInterval(calculate, 1);

			function calculate()
			{
				var dateStart = new Date();
				var dateStart = new Date(dateStart.getUTCFullYear(), dateStart.getUTCMonth(), dateStart.getUTCDate(), dateStart.getUTCHours(), dateStart.getUTCMinutes(), dateStart.getUTCSeconds());
				var timeRemaining = parseInt((dateEnd - dateStart.getTime()) / 1000)
				if (timeRemaining >= 0)
				{
					years = parseInt(timeRemaining / 31536000);
					timeRemaining = (timeRemaining % 31536000);
					days = parseInt(timeRemaining / 86400);
					timeRemaining = (timeRemaining % 86400);
					hours = parseInt(timeRemaining / 3600);
					timeRemaining = (timeRemaining % 3600);
					minutes = parseInt(timeRemaining / 60);
					timeRemaining = (timeRemaining % 60);
					seconds = parseInt(timeRemaining);
					if ($('.countdown').length > 0)
					{
						$(".countdown #years")[0].innerHTML = parseInt(years, 10);
						$(".countdown #days")[0].innerHTML = parseInt(days, 10);
						$(".countdown #hours")[0].innerHTML = ("0" + hours).slice(-2);
						$(".countdown #minutes")[0].innerHTML = ("0" + minutes).slice(-2);
						$(".countdown #seconds")[0].innerHTML = ("0" + seconds).slice(-2);
					}
				}
				else
				{
					return;
				}
			}

			function display(days, hours, minutes, seconds)
			{}
		}

		$('[data-toggle-box]').on('click', function()
		{
			var toggle_box = $(this).data('toggle-box');
			if ($('#' + toggle_box).is(":visible"))
			{
				$('#' + toggle_box).slideUp(250);
			}
			else
			{
				$("[id^='box']").slideUp(250);
				$('#' + toggle_box).slideDown(250);
			}
		});
	}
	setTimeout(init_template, 0);
	//  $('body').append('<div class="page-change-preloader preloader-light hide-change-preloader"><div id="preload-spinner" class="spinner-red"></div></div>');
});

