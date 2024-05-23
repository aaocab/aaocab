<?php
Logger::profile("Initiating column_booking");

/** @var BookFormRequest $objPage */
$objPage			 = $this->pageRequest;
/** @var Stub\common\Booking $objBooking */
$objBooking			 = $objPage->booking;
$pageid				 = $objPage->step;
$isRedirectedBooking				 = $objPage->isRedirectedBooking;
/** @var Controller $this */
$version			 = Yii::app()->params['siteJSVersion'];
$this->beginContent('//layouts/main1');
Yii::app()->clientScript->registerPackage("uiControls");
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/js/gozo/v3/bookingRoute.js?v=$version", CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/js/gozo/v3/booking.js?v=$version", CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/js/gozo/v3/hyperLocation.js?v=$version", CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/userLogin.js?v=' . $version, CClientScript::POS_HEAD);
if($objBooking != null){
$model				 = $objBooking->getLeadModel();
}
?>
<script>

	function openNav()
	{
		document.getElementById("mySidenav").style.width = "250px";
	}

	function closeNav()
	{
		document.getElementById("mySidenav").style.width = "0";
	}
</script>
<div id="bkgContainer" class="container mt20 mb30 bkgContainer">

	<div class="bkgContent">
		<div class="breadcrumb-widget">
			<ul class="nav nav-pills" id="bkgForm">
				<li role="nav-item" style="display: none"  class="chkGuestUser<?= UserInfo::isLoggedIn() ? " hide" : "" ?>"><a class="nav-link" data-toggle="pill" href="#tab1">Check Account</a></li>
				<li role="nav-item" style="display: none"  class="chkGuestUser <?= UserInfo::isLoggedIn() ? " hide" : "" ?>"><a class="nav-link" data-toggle="pill" href="#tab2">Sign In</a></li>
<!--				<li role="nav-item" class="<?//= ($pageid < 4) ? "hide" : "" ?>"><a class="nav-link"  data-toggle="pill" title="Trip Type" href="#tab4">Trip Type</a></li>-->
				<?php if($model->bkg_agent_id == Config::get('Kayak.partner.id')){?>
				<li role="nav-item"><a class="nav-link pl10"  title="Home" href="/" style="background: none;"><img src="/images/bx-home-alt-2.svg" alt="" width="16" height="16"></a></li>
				<?php } ?>
				<li role="nav-item" class="<?= ($pageid < 4) ? "hide" : "" ?>"><a class="nav-link" data-toggle="pill" title="Booking Type" href="#tab4">Booking Type</a></li>
				<li role="nav-item" class="<?= ($pageid < 5) ? "hide" : "" ?>"><a class="nav-link text-left" data-toggle="pill" title="Itinerary" href="#tab5">Itinerary</a></li>
				<li role="nav-item" class="<?= ($pageid < 6 || $isRedirectedBooking==1) ? "hide" : "" ?>"><a class="nav-link" data-toggle="pill" title="Cab Type" href="#tab6">Cab Type</a></li>
				<li role="nav-item" style="display: none"><a class="nav-link" data-toggle="pill" title="Gozo Now" href="#tab8">Gozo Now Inventory</a></li>
				<li role="nav-item" class="<?= ($pageid < 9) ? "hide" : "" ?>"><a class="nav-link" data-toggle="pill" title="Service Tier" href="#tab9">Service Tier</a></li>
<!--				<li role="nav-item" class="<?= ($pageid < 10 || !$objPage->isSelectAvailable()) ? "hide" : "" ?>"><a class="nav-link" data-toggle="pill" title="Select Model" href="#tab10">Select Model</a></li>-->
				<li role="nav-item" class=" hide"><a class="nav-link" data-toggle="pill" title="Travel Info" href="#tab11">Travel Info</a></li>
				<?php if($model->bkg_agent_id = Config::get('Kayak.partner.id')){?>
				<li role="nav-item" class="<?= ($pageid < 7) ? "hide" : "" ?>"><a class="nav-link" data-toggle="pill" title="Traveller Info" href="#tab7">Traveller Info</a></li>
				<?php }?>
<!--				<li role="nav-item" class="<?= ($pageid < 12) ? "hide" : "" ?>"><a class="nav-link" data-toggle="pill" title="Addons" href="#tab12">Addons</a></li>
				<li role="nav-item" class="<?= ($pageid < 13) ? "hide" : "" ?>"><a class="nav-link" data-toggle="pill" title="Address" href="#tab13">Address</a></li>-->
			</ul>
		</div>
		<div class="tab-content">
			<div class="tab-pane chkGuestUser <?= ($pageid == 1) ? " active" : "" ?><?= UserInfo::isLoggedIn() ? " hide" : "" ?>" id="tab1"><?= ($pageid == 1) ? $content : ($this->renderPartial("checkgozoaccount", ["step" => 1, "model" => $model])); ?></div>
<?php Logger::profile("checkgozoaccount rendered"); ?>
			<div class="tab-pane chkGuestUser <?= ($pageid == 2) ? " active" : "" ?><?= UserInfo::isLoggedIn() ? " hide" : "" ?>" id="tab2"><?= ($pageid == 2) ? $content : "" ?></div>
<?php Logger::profile("pageid2 rendered"); ?>
<!--			<div class="tab-pane <?//= ($pageid == 4) ? " active" : "" ?>" id="tab4"><?//= ($pageid == 4) ? $content : (($pageid > 4) ? $this->renderFile($this->getViewFile("cabsegmentation"), ["step" => 4, "model" => $model]) : "") ?></div>-->
			<div class="tab-pane <?= ($pageid == 4) ? " active" : "" ?>" id="tab4"><?= ($pageid == 4) ? $content : (($pageid > 4) ? $this->renderFile($this->getViewFile("servicetypes"), ["step" => 4, "model" => $model]) : "") ?></div>
<?php Logger::profile("servicetypes rendered"); ?>
			<div class="tab-pane <?= ($pageid == 5) ? " active" : "" ?>" id="tab5"><?= ($pageid == 5) ? $content : (($pageid > 5) ? $this->renderFile($this->getViewFile("bkItinerary"), ["step" => 5, "model" => $model]) : "") ?></div>
<?php Logger::profile("bkItinerary rendered"); ?>
			<div class="tab-pane <?= ($pageid == 6) ? " active" : "" ?>" id="tab6"><?= ($pageid == 6) ? $content : (($pageid > 6) ? $this->renderFile($this->getViewFile("bkQuoteNew"), ["step" => 6, "model" => $model]) : "") ?></div>
<?php Logger::profile("bkQuoteNew rendered"); ?>
			<div class="tab-pane <?= ($pageid == 8) ? " active" : "" ?>" id="tab8"><?= ($pageid == 8) ? $content : (($pageid > 8 && $model->bkg_is_gozonow > 0) ? $this->renderFile($this->getViewFile("bkGNowInventory"), ["step" => 8, "model" => $model]) : "") ?></div>
<?php Logger::profile("bkGNowInventory rendered"); ?>
			<div class="tab-pane <?= ($pageid == 9) ? " active" : "" ?>" id="tab9"><?= ($pageid == 9) ? $content : (($pageid > 9) ? $this->renderFile($this->getViewFile("bkDetails"), ["step" => 9, "model" => $model]) : "") ?></div>
<?php Logger::profile("bkDetails rendered"); ?>
<!--			<div class="tab-pane <?= ($pageid == 10) ? " active" : "" ?>" id="tab10"><?= ($pageid == 10) ? $content : (($pageid > 10 && $objPage->isSelectAvailable()) ? $this->renderFile($this->getViewFile("showVehicleModel"), ["step" => 10, "model" => $model]) : "") ?></div>-->
<!--			<div class="tab-pane <?= ($pageid == 12) ? " active" : "" ?>" id="tab12"><?= ($pageid == 12) ? $content : (($pageid > 12) ? $this->renderFile($this->getViewFile("bkAddons"), ["step" => 12, "model" => $model]) : "") ?></div>
			<div class="tab-pane <?= ($pageid == 13) ? " active" : "" ?>" id="tab13"><?= ($pageid == 13) ? $content : (($pageid > 13) ? $this->renderFile($this->getViewFile("bkAddress"), ["step" => 13, "model" => $model]) : "") ?></div>-->
		<div class="tab-pane <?= ($pageid == 7 && $model->bkg_agent_id = Config::get('Kayak.partner.id')) ? " active" : "" ?>" id="tab7"><?= ($pageid == 7 && $model->bkg_agent_id = Config::get('Kayak.partner.id')) ? $content : (($pageid > 7) ? "" : "") ?></div>
</div>
	</div>
</div>

<script type="text/javascript">
	var step = "";
	var tabURL = "";
	var pageTitle = "";
	var tabHead = "";


	function AddStep(content)
	{
		var elem = $(".bkgContent > .step" + step);

		if (elem.length === 0)
		{
			elem = $(".bkgContent").add('div').addClass("bkgStep");
		}
		elem.html(content);
	}

	function setData(data)
	{
		var storage = window.sessionStorage;
		storage.setItem("bkgdata", data);
	}

	function getData()
	{
		var storage = window.sessionStorage;
		return storage.getItem("bkgdata");
	}

	function setSessData()
	{
		var elem = $("INPUT:hidden[name=sdata]");
		if (elem.length > 0 && elem.val() == '')
		{
			elem.val(getData());
		}
	}

	function showStep(step, title = '', showTabTitle = true, showTab = true)
	{
		var tab = getTab(step);
		getTab(step).click();
		if (title != '' && showTabTitle)
		{
			getTab(step).parent().prev("a").html(title);
		}
		if (showTab)
		{
			getTab(step).html(getTab(step).prop("title"));
			getTab(step).removeClass("hide");
			getTab(step).parent().removeClass("hide");
		}
		for (i = step + 1; i < 15; i++)
		{
			var tabStep = getTab(i);
			if (tabStep.length > 0)
			{
				tabStep.parent().addClass("hide");
				getTabView(i).html("");
			}
	}
	}

	function getTab(step)
	{
		var tabHead = $("#bkgForm a[href=\"#tab" + step.toString() + "\"]");
		return tabHead;
	}

	function getTabView(step)
	{
		var tabView = $("#bkgForm .tab-pane#tab" + step.toString() + "");
		return tabView;
	}


	function toggleStep(step, prevStep, url = null, title = null, tabTitle = '', showTabTitle = true, currentPage = 0, showTab = true)
	{
		if (url !== null && url !== '')
		{
			history.pushState({"step": step}, title, url);
			if (step == currentPage)
			{
				trackPage(url);
			}
		}
		if (title != null)
		{
			document.title = title;
		}

		getTab(prevStep).html(tabTitle);

		showStep(step, '', false, true);
	}


	function displayFormError(form, messages)
	{
		settings = form.data('settings');
		content = "";
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
		$('#' + settings.summaryID).toggle(content !== '').find('ul').html(content);
		return (content == "");
	}

	window.addEventListener('popstate', function(event)
	{
		var step=4;
		if (event.state!==null && event.state.hasOwnProperty("step"))
		{
			step = event.state.step;
		}
		getTab(step).click();
	}, false);

	function bindFocus()
	{
		$('input[type=text]').on("focus", function()
		{
			scrollTop(this);
		});
	}

	function scrollTop(obj)
	{
		$('html, body').animate({
			scrollTop: $(obj).offset().top - 30 + 'px'
		}, 'fast');
	}

</script>
<?php $this->endContent(); ?>
<script>
	$(document).ready(function(){
		$jsUserLogin = new userLogin();
	});

if ('OTPCredential' in window) {
	window.addEventListener('DOMContentLoaded', e => {
    const input = document.querySelector('input[autocomplete="one-time-code"]');
    if (!input) return;
    const ac = new AbortController();
    const form = input.closest('form');
    if (form) {
      form.addEventListener('submit', e => {
        ac.abort();
      });
    }

    navigator.credentials.get({
      otp: { transport:['sms'] },
      signal: ac.signal
    }).then(otp => {
	  $jsUserLogin.putOtp(otp.code);
	  $('.otpNum').keyup();
	  $jsUserLogin.validateForm();
    }).catch(err => {
      console.log(err);
    });
  });
}
</script>

