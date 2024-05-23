<?php
$typeName		 = Booking::model()->getBookingType($model->bkg_booking_type, "Trip");
$this->layout	 = 'column_booking';
$version		 = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/bookNow.js?v=' . $version);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/booking.js?v=' . $version);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/login.js?v=' . $version);
$autoAddressJSVer = Yii::app()->params['autoAddressJSVer'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/js/gozo/hyperLocation.js?v=$autoAddressJSVer");
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/js/jquery.mask1.min.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/jquery.disableAutoFill.min.js');
?>
<script>
	var bkCSRFToken = "<?=Yii::app()->request->csrfToken?>";
	$jsLogin = new Login();	
</script>
<div class="media-view">
	<div class="booking_panel">
		<ul class="nav nav-tabs arrow_box hidden-xs font-sm not-active" id="myTab99">
			<li class="ltab lTripType active tabcolor_1 utab" id="l1">               
				<a data-toggle="tab" href="#menuTripType"><span id="btype">
				<?php echo (($step == '1' || $step == '2') ? $typeName:'Select Trip'); ?></span>
				</a>
			</li>
			<li class="ltab lRoute tabcolor_2 utab" id="l2">
				<a data-toggle="tab" href="#menuRoute"><span id="bdate">Select Dates of Travel</span></a></li>
			<li class="ltab lQuote tabcolor_3 utab" id="l3">
				<a data-toggle="tab" href="#menuQuote"><span id="bcabs">Select Service Type</span></a></li>
			<li class="ltab lInfo tabcolor_4 utab" id="l4">
				<a data-toggle="tab" href="#menuInfo"><span id="binfo">Booking Details</span></a></li>
			<li class="ltab lSummary tabcolor_5 utab" id="l5">
				<a data-toggle="tab" href="#menuSummary"><span id="bpay">Review &amp; Pay</span></a></li>
		</ul>
		<ul class="nav nav-tabs arrow_box hidden-sm hidden-lg hidden-md ml15 mr15 mt20 n" id="myTab">
			
		</ul>
		<div class="tab-content">
			<div class="tabTripType tab-pane fade active in" id="menuTripType">
				<?php $this->renderPartial("bkTripType", ['model' => $model]); ?>
			</div>
			<div class="tabRoute tab-pane fade" id="menuRoute">
				<?php
				if ($step >= 1)
				{
					$this->renderPartial("bkRoute", ['model' => $model]);
				}
				?>
			</div>
			<div class="tabQuote tab-pane fade" id="menuQuote">
				<?php
				
				if ($step >= 2)
				{
					$this->renderPartial("bkQuoteNew", ['model' => $model, 'quotes' => $quotes, 'stepOver' => 1]);
				}
				?>
			</div>

			<div class="tabInfo tab-pane fade" id="menuInfo"></div>
			<div class="tabInfo tab-pane fade" id="menuSummary"></div>
		</div>
	</div>
</div>
<input type="hidden" id="book_Step" value="<?= $step ?>"/>
<script>
    var bkCSRFToken = "<?= Yii::app()->request->csrfToken ?>";
    $jsBookNow = new BookNow();
	$jsBooking = new Booking();
	var urls;
    $jsBookNow.showTab($jsBookNow.arrSteps[<?= $step ?>]);
    $(document).ready(function ()
    {	//callbackLogin = 'fillUserform';	
        $jsBookNow.booknowReady();
		$jsBookNow.checkTabs();
	    urls = { "partialsignin": "<?= Yii::app()->createUrl('users/partialsignin') ?>",
					 "refreshuserdata": "<?= Yii::app()->createUrl('users/refreshuserdata') ?>",
					 "googleurl": "<?= Yii::app()->createUrl('users/oauth', array('provider' => 'Google', 'isFlexxi' => true)); ?>",
					 "fburl": "<?= Yii::app()->createUrl('users/oauth', array('provider' => 'Facebook', 'isFlexxi' => true)); ?>",
				   };
    });
    function socailSigin(socailSigin)
    {
		$jsLogin.socialLogin(socailSigin,urls);		   
    }
	
	function updateLogin()
	{
		$jsLogin.updateLogin(urls);
	}
	
	$('.autoComLoc').change(function () {
		hyperModel.findAddressAirport(this.id);
	});
</script>
<? $api = Config::getGoogleApiKey('browserapikey'); ?>
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key=<?= $api ?>&libraries=places&"></script>