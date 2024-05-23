<style>
    td.disabled{
        display: table-cell !important;
    }
</style>
<?php
$view_url = Filter::viewPath();
$version = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/bookNow.js?v=' . $version, CClientScript::POS_END);
?>

<?php
$ptime = date('h:i A', strtotime('6am'));
//$model->bkg_pickup_date_time = $ptime;
$timeArr = Filter::getTimeDropArr($ptime);
$ptimePackage = Yii::app()->params['defaultPackagePickupTime'];
$timeArrPackage = Filter::getTimeDropArr($ptimePackage);
$selectizeOptions = ['create' => false, 'persist' => true, 'selectOnTab' => true, 'createOnBlur' => true, 'dropdownParent' => 'body',
    'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField' => 'id', 'openOnFocus' => true, 'preload' => false,
    'labelField' => 'text', 'valueField' => 'id', 'searchField' => 'text', 'closeAfterSelect' => true,
    'addPrecedence' => false,];
$cityRadius = Yii::app()->params['airportCityRadius'];
$emptyTransferDropdown = "Please check your transfer type.<br>Let us help you.<br><i>Call now</i> (+91) 90518-77-000";
//Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');
//Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/mtnc/global/plugins/bootbox/bootbox.min.js');
$brtModel = $model->bookingRoutes[0];
$defaultDate = date('Y-m-d H:i:s', strtotime('+2 days'));
$defaultRDate = date('Y-m-d H:i:s', strtotime('+3 days'));
$minDate = date('Y-m-d H:i:s ', strtotime('+4 hour'));
$pdate = ($brtModel->brt_pickup_date_date == '') ? DateTimeFormat::DateTimeToDatePicker($defaultDate) : $brtModel->brt_pickup_date_date;
$ctr = rand(0, 99) . date('mdhis');
//$api = Yii::app()->params['googleBrowserApiKey'];
$api = Config::getGoogleApiKey('browserapikey'); 

Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/booking.js?v=' . $version);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/js/gozo/hyperLocation.js?v=$version");
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/js/jquery.mask1.min.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/jquery.disableAutoFill.min.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/js/gozo/bookingRoute.js?v=$version");
?>
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key=<?= $api ?>&libraries=places&"></script>
<?php
#$this->renderPartial('head_mobile');
?>
<script>
    var hyperModel = new HyperLocation();
    var bookingModel = new Booking();
    $(document).ready(function () {
        $jsBookNow = new BookNow();
        $jsBookNow.homeReady();
    });

</script>




<div class="clear"></div>
<div class="widget-content-1">
  <div class="text-style-1">
    Pick your cab from our wide range of options
</div>  
    <div class="content-style mb0 mobile-type tab-styles">
    <div class="above-overlay">
        <div class="tab-style tabs pt10 home-search-widget">
            <div class="t-style" data-active-tab-pill-background="bg-green-dark" style="display:flex;">
                <a href="#"data-tab-pill="tab-pill-1a" class="devPrimaryTab3 mainTab active" style="width:26%;">Outstation</a>
                <a href="#"data-tab-pill="tab-pill-7a" class="devPrimaryTab3 mainTab" style="width:37%;">Local</a>
				<a href="#"data-tab-pill="tab-pill-5a" class="devPrimaryTab3 mainTab" style="width:37%;float:right;">Airport</a>
<!--            <a href="#"data-tab-pill="tab-pill-10a" class="devPrimaryTab3 mainTab" style="width:25%;float:right;">Shuttle</a>-->
            </div>
            <div class="tab-pill-content p10">
                <?= $this->renderPartial('bkOneway', array('model' => $model, 'brtModel' => $brtModel, 'timeArr' => $timeArr, 'selectizeOptions' => $selectizeOptions, 'pdate' => $pdate, 'minDate' => $minDate, 'ctr' => $ctr), true, false); ?>
                <?//= $this->renderPartial('bkRoundtrip', array('model' => $model, 'brtModel' => $brtModel, 'minDate' => $minDate, 'selectizeOptions' => $selectizeOptions, 'timeArr' => $timeArr, 'pdate' => $pdate, 'defaultDate' => $defaultRDate, 'ctr' => $ctr), true, false); ?>
                <?= $this->renderPartial('bkMultiway', array('model' => $model, 'brtModel' => $brtModel, 'minDate' => $minDate, 'selectizeOptions' => $selectizeOptions, 'pdate' => $pdate, 'timeArr' => $timeArr, 'ctr' => $ctr), true, false); ?>
                <?= $this->renderPartial('bkPackages', array('model' => $model, 'brtModel' => $brtModel, 'minDate' => $minDate, 'selectizeOptions' => $selectizeOptions, 'pdate' => $pdate, 'timeArr' => $timeArr, 'ctr' => $ctr), true, false); ?>
                <?= $this->renderPartial('bkShuttle', array('model' => $model, 'brtModel' => $brtModel, 'minDate' => $minDate, 'selectizeOptions' => $selectizeOptions, 'pdate' => $pdate, 'timeArr' => $timeArr, 'ctr' => $ctr), true, false); ?>
                <?= $this->renderPartial('bkAirportTransfer', array('model' => $model, 'brtModel' => $brtModel, 'minDate' => $minDate, 'selectizeOptions' => $selectizeOptions, 'pdate' => $pdate, 'timeArr' => $timeArr, 'ctr' => $ctr), true, false); ?>
                <?= $this->renderPartial('bkDayRental', array('model' => $model, 'brtModel' => $brtModel, 'minDate' => $minDate, 'selectizeOptions' => $selectizeOptions, 'pdate' => $pdate, 'timeArr' => $timeArr, 'ctr' => $ctr), true, false); ?>
            </div>
        </div>
    </div>
    </div>
    
    
</div>


<script>
    $(document).ready(function () {

<?php if (strtoupper($tripType) == 'DAY-RENTAL') {
    ?>
            function explode() {
                $('.t-style a[data-tab-pill="tab-pill-5a"]').click();
                $('.t-style a[data-sub-tab="tab-pill-7a"]').click();
                $('.sub-tab').removeClass('active-tab-pill-button active');
                $('#tab-pill-5a').css({'display': 'inline', 'visibility': 'hidden', 'position': 'absolute'});
                $('#tab-pill-7a').css({'display': 'block'});
                $('a[data-sub-tab="' + "tab-pill-7a" + '"]').addClass('active-tab-pill-button active');

                $('.t-style a[data-tab-pill="tab-pill-5a"]').click(function () { // bind click event to link
                    $('#tab-pill-5a').css({'display': 'block', 'visibility': 'visible', 'position': 'initial'});
                })
                $('a[data-sub-tab="tab-pill-5a"]').click(function () { // bind click event to link
                    $('#tab-pill-5a').css({'display': 'block', 'visibility': 'visible', 'position': 'initial'});
                })
            }
            setTimeout(explode, 100);
<?php } ?>

<?php
if (strtoupper($tripType) == 'AIRPORT-TRANSFERS') {
    ?>
            function explode() {
                $('.t-style a[data-tab-pill="tab-pill-5a"]').click();
            }
            setTimeout(explode, 100);
<?php } ?>


    });

</script>
<?php
$script = "$(document).ready(function(){
	$('input[name=YII_CSRF_TOKEN]').val('" . $this->renderDynamicDelay('Filter::getToken') . "');
});";
Yii::app()->clientScript->registerScript('updateYiiCSRF', $script, CClientScript::POS_END);
?>