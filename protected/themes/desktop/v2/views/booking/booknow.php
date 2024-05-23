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
$typeName			 = Booking::model()->getBookingType($model->bkg_booking_type, "Trip");
$this->layout		 = 'column_booking';
$version			 = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/bookNow.js?v=' . $version);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/booking.js?v=' . $version);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/addon.js?v=' . $version);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/login.js?v=' . $version);
$autoAddressJSVer	 = Yii::app()->params['autoAddressJSVer'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/js/gozo/hyperLocation.js?v=$autoAddressJSVer");
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/js/jquery.mask1.min.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/jquery.disableAutoFill.min.js');
?>

<script>
    var bkCSRFToken = "<?= Yii::app()->request->csrfToken ?>";
    $jsLogin = new Login();
    var hyperModel = new HyperLocation();
</script>
<div class="row">
    <div class="col-12 menu-style-1 bg-gray3">
        <div class="container-fluid text-center">
			<?php
			 
				?><ul class="nav nav-tabs not-active" id="myTab99">
					<li class="ltab lTripType tabcolor_1 utab" id="l1">               
						<a data-toggle="tab" href="#menuTripType" class="topTab <?php
						if ($step == '1')
						{
							?>active  <?php } ?>"><span id="btype" class="text-middle">
								<?php echo (($step == '1' || $step == '2') ? $typeName : 'Select Trip'); ?></span>
						</a><i class="fas fa-angle-right"></i>
					</li>
					<li class="ltab lRoute tabcolor_2 utab" id="l2">
						<a data-toggle="tab" href="#menuRoute" class="topTab btn btn-primary disabled"><span id="bdate" class="text-middle">Select Dates of Travel</span></a><i class="fas fa-angle-right"></i></li>
					<li class="ltab lQuote tabcolor_3 utab" id="l3">
						<a data-toggle="tab" href="#menuQuote" class="topTab btn btn-primary disabled <?php
						if ($step == '2')
						{
							?> active  <?php } ?>"><span id="bcabs" class="text-middle">Select Service Type</span></a><i class="fas fa-angle-right"></i></li>
					<li class="ltab lInfo tabcolor_4 utab" id="l4">
						<a data-toggle="tab" href="#menuInfo" class="topTab btn btn-primary disabled"><span id="binfo" class="text-middle">Booking Details</span></a><i class="fas fa-angle-right"></i></li>
					<li class="ltab lSummary tabcolor_5 utab" id="l5">
						<a data-toggle="tab" href="#menuSummary" class="topTab btn btn-primary disabled"><span id="bpay" class="text-middle">Review &amp; Pay</span></a></li>
				</ul><?php
			
			?>
        </div>
    </div>
    <div class="col-12">
        <div class="tab-content">
            <div class="tabTripType tab-pane active in" id="menuTripType">
				<?php $this->renderPartial("bkTripType", ['model' => $model]); ?>
            </div>
            <div class="tabRoute tab-pane" id="menuRoute">
				<?php
				if ($step >= 1)
				{
					$this->renderPartial("bkRoute", ['model' => $model]);
				}
				?>
            </div>
            <div class="tabQuote tab-pane" id="menuQuote">
				<?php
				if ($step >= 2)
				{
					if ($model->bkg_is_gozonow == 1)
					{
						$this->renderPartial("bkQuoteGozoNow", ['model' => $model, 'quotes' => $quotes, 'stepOver' => 1]);
					}
					else
					{
						$this->renderPartial("bkQuoteNew", ['model' => $model, 'quotes' => $quotes, 'stepOver' => 1]);
					}
				}
				?>
            </div>

            <div class="tabInfo tab-pane" id="menuInfo"></div>
            <div class="tabInfo tab-pane" id="menuSummary"></div>
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
        urls = {"partialsignin": "<?= Yii::app()->createUrl('users/partialsignin') ?>",
            "refreshuserdata": "<?= Yii::app()->createUrl('users/refreshuserdata') ?>",
            "googleurl": "<?= Yii::app()->createUrl('users/oauth', array('provider' => 'Google', 'isFlexxi' => true)); ?>",
            "fburl": "<?= Yii::app()->createUrl('users/oauth', array('provider' => 'Facebook', 'isFlexxi' => true)); ?>",
        };
        var isLogin = <?php echo $step; ?>;
        if (isLogin == 2)
        {
            $('a[href="#menuRoute"]').removeClass('btn btn-primary disabled');
            $('a[href="#menuQuote"]').removeClass('btn btn-primary disabled');
        }
    });
    function socailSigin(socailSigin)
    {
        $jsLogin.socialLogin(socailSigin, urls);
    }

    function updateLogin()
    {
        $jsLogin.updateLogin(urls);
    }

    var tabMenu = 0;
    $('.topTab').click(function (event)
    {
        $('.topTab').removeClass('active');
        $(event.currentTarget).addClass('active');
        $(event.currentTarget).removeClass('btn btn-primary disabled');
        var str = event.currentTarget;
        var newstr = str.toString();
        var menu = newstr.substring(newstr.indexOf('#') + 1);
        if (menu == 'menuQuote')
        {
            $('a[href="#menuRoute"]').removeClass('btn btn-primary disabled');
        }
        if (tabMenu != 0)
        {
            $('.tab-pane').removeClass('active').removeClass('fade');
            $('#' + menu).addClass('active');
        }
        tabMenu = tabMenu + 1;
    });


    $('.autoComLoc').change(function ()
    {
        hyperModel.findAddressAirport(this.id, bkCSRFToken);
    });
</script>
<?php $api = Config::getGoogleApiKey('browserapikey'); ?>
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key=<?= $api ?>&libraries=places&"></script>