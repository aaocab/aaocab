<?php
if (isset($jsonStructureMarkupData) && trim($jsonStructureMarkupData) != '')
{
	?>
	<script type="application/ld+json">
	<?php echo $jsonStructureMarkupData; ?>
	</script>
<?php } ?>
<?php
if (isset($routeBreadcumbStructureMarkupData) && trim($routeBreadcumbStructureMarkupData) != '')
{
	?>
	<script type="application/ld+json">
	<?php echo $routeBreadcumbStructureMarkupData; ?>
	</script>
<?php } ?>
<?php
if (isset($jsonproviderStructureMarkupData) && trim($jsonproviderStructureMarkupData) != '')
{
	?>
	<script type="application/ld+json">
	<?php echo $jsonproviderStructureMarkupData; ?>
	</script>
<?php } ?>
<?php   
$typeName		 = Booking::model()->getBookingType($model->bkg_booking_type, "Trip");
$version		 = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/bookNow.min.js?v=' . $version);
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
		<div class="tab-content p0">
			<div class="tabTripType tab-pane fade active in" id="menuTripType">
				<?php $this->renderPartial("bkTripType" . $this->layoutSufix, ['step' => $step, 'model' => $model]); ?>
			</div>
			<div class="tabRoute tab-pane fade" id="menuRoute">
				<?php
				if ($step >= 1)
				{
					$this->renderPartial("bkRoute".$this->layoutSufix, ['step' => $step, 'model' => $model]);
				}
				?>
			</div>
			<div class="tabQuote tab-pane fade" id="menuQuote">
				<?php
				if ($step >= 2)
				{
					$this->renderPartial("bkQuoteNew".$this->layoutSufix, ['step' => $step, 'model' => $model, 'quotes' => $quotes , 'stepOver' => 1]);
				}
				?>
			</div>
            <div class="tabDetails tab-pane fade" id="menuDetails"></div>
			<div class="tabInfo tab-pane fade" id="menuInfo"></div>
			<div class="tabAddress tab-pane fade" id="menuAddress"></div>
			<div class="tabSummary tab-pane fade" id="menuSummary"></div>
		</div>
	</div>
</div>
<input type="hidden" id="book_Step12" value="<?= $step ?>"/>
<script>
    var bkCSRFToken = "<?= Yii::app()->request->csrfToken ?>";
    $jsBookNow = new BookNow();
	var urls = { "partialsignin": "<?= Yii::app()->createUrl('users/partialsignin') ?>",
					 "refreshuserdata": "<?= Yii::app()->createUrl('users/refreshuserdata') ?>",
					  "googleurl": "<?= Yii::app()->createUrl('users/oauth', array('provider' => 'Google', 'isFlexxi' => true)); ?>",
					  "fburl": "<?= Yii::app()->createUrl('users/oauth', array('provider' => 'Facebook', 'isFlexxi' => true)); ?>",
				   };
	
    $(document).ready(function ()
    {	
		$jsBookNow.jumpToStep('<?= $step ?>');
    });
    function socailSigin(socailSigin)
    {
		$jsLogin.socialLogin(socailSigin,urls);		   
    }
	function updateLogin()
	{
		 $('.login-box-container').css("display", "none");
		$jsLogin.updateLogin(urls);	
	}
	
	
//	$('.autoComLoc').change(function () {
//		hyperModel.findAddressAirport(this.id);
//	});

</script>
<? //$api = Yii::app()->params['googleBrowserApiKey']; ?>
<? $api = Config::getGoogleApiKey('browserapikey'); ?>
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key=<?= $api ?>&libraries=places&"></script>

