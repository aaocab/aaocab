<?php
$version = Yii::app()->params['siteJSVersion'];

Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/jquery.mask.min.js');
Yii::app()->clientScript->registerCssFile(ASSETS_URL . '/plugins/form-select2/select2.css');
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/jquery.mask1.min.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/booking.js?v=' . $version);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/adminBooking.js?v=' . $version);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/route.js?v=' . $version);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/city.js?v=' . $version);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/promo.js?v=' . $version);
//Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/lookup/cities?v' . Cities::model()->getLastModified());
//Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/lookup/routes?v' . Route::model()->getLastModified());
//$api					 = Yii::app()->params['googleBrowserApiKey'];
$api				     = Config::getGoogleApiKey('browserapikey');
$autoAddressJSVer		 = Yii::app()->params['autoAddressJSVer'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/js/gozo/hyperLocation.js?v=$autoAddressJSVer");
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/js/gozo/hyperMarkerLocation.js?v=$autoAddressJSVer");

?>
<script>
	var admBooking	= new AdminBooking();
	var promo		= new Promo();
	var booking		= new Booking();
	var hyperModel	= new HyperLocation();
</script>
<style>
    @media (min-width: 992px){
        .modal-lg {
            width: 95%!important;
        }
    }
    @media (min-width: 768px){
        .modal-lg {
            width: 100%;
        }
    }

    .form-group {
        margin-bottom: 7px;
        margin-left: 0 !important;
        margin-right: 0 !important;
    }
    .bootstrap-timepicker-widget input  {
        border: 1px #555555 solid;color: #555555;
    }
    .navbar-nav > li > a {
        padding: 6px 30px;
    }
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin:0;
    }
    .selectize-input {
		min-width: auto !important;
        width: 100% !important;
    }
	.selectize-control {
        min-width: auto !important;
    }
    td, th {
        padding: 10px  !important ; 
    }
	.admin-ph{
		padding-left: 80px !important;
	}
	.disabled {
		pointer-events: none;
		cursor: default;
	}
	.edit-block{
		float: right;
		padding: 0px 5px;
		font-size: 18px;
		cursor: pointer;
		color: #947418;
	}
	#instruction ul li,#divpref ul li{
		list-style-type: circle;
	}
	.autoMarkerLoc{
		cursor: pointer;
	}
</style>
<div class="container">
	<div id="bkErrors" class="alert alert-block alert-danger hide">
		<p>Please fix the following input errors:</p>
		<ul>
			
		</ul>
	</div>
	<div class="row" id="errorShow" style="display: none">
		<div class="col-xs-12">
			<div class="panel panel-default panel-border">

				<div class="panel-body ">
					<div class="row">
						<div class="col-xs-12 p5 alert-danger" id="errorMsg"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div id="customerType">
		<?= $this->renderPartial("bkCustomerType", ["model" => $model,'custType' => $custType], false, false); ?>
	</div>
	<div id="partnerType" class="hide"></div>
	<div id="customerPhoneDetails" class="hide"></div>
    <div id="bookingType" class="hide"></div>
    <div id="bookingRoute" class="hide"></div>
    <div id="payment" class="hide"></div>
    <div id="travellerInfo" class="hide"></div>
	<div id="rePayment" class="hide"></div>
	<div id="additionalInfo" class="hide"></div>
    <div id="vendorIns" class="hide"></div>
</div>
<script>
	$(document).ready(function(){
		setTimeout(function(){  
			<?php if($custType == 2){ ?>
				$('#b2b').click();
			<?php } else { ?>
				$('#b2c').click();
			<?php } ?>
		},100);
		$('[data-toggle="tooltip"]').tooltip();
	});
	function margeRequiredEncryptedData(ownDetails,ownTotalDetails,overAllTotalDetails)
    {
		
		var ownDataUnEnc = window.atob($(ownDetails).val());
        var ownData = JSON.parse(ownDataUnEnc);
		var dataStr;
        if($(ownTotalDetails).val() == '')
        {
			dataStr = JSON.stringify(ownData);
            $(ownTotalDetails).val(window.btoa(dataStr));
            $(overAllTotalDetails).val(window.btoa(dataStr));
        }
        else
        {
			var totalDataUnEnc = window.atob($(ownTotalDetails).val());
            var totalData = JSON.parse(totalDataUnEnc);
            var fullData  = $.extend(totalData, ownData);
			dataStr = JSON.stringify(fullData);
            $(ownTotalDetails).val(window.btoa(dataStr));
            $(overAllTotalDetails).val(window.btoa(dataStr));
            
        }
    }
	
	function showMap(obj,loc){
		var locKey = $(obj).data('lockey');
		var mapMarkerBound = JSON.parse($('.mapBound_' + locKey).val());
		if($('.locLat_' + locKey).val() != '' && $('.locLon_' + locKey).val() != '')
		{
			mapMarkerBound.ctyLat = $('.locLat_' + locKey).val();
			mapMarkerBound.ctyLon = $('.locLon_' + locKey).val();
		}
		mapMarkerBound.airport = 0;
		admBooking.showMarkerMap(mapMarkerBound, locKey,loc);
	}
	
	function showAirportMap(obj){
		var locKey = $(obj).data('lockey');
		if ($("#BookingTemp_bkg_transfer_type_1").is(":checked")) {
			var location = 'source';
		}
		if ($("#BookingTemp_bkg_transfer_type_0").is(":checked")) {
			var location = 'destination';
		}
		var mapBound = {};
		mapBound.ctyLat = $('#locLat1').val();
		mapBound.ctyLon = $('#locLon1').val();
		mapBound.bound = '';
		mapBound.isAirport = $('#isAirport1').val();
		mapBound.isCtyPoi = 0;
		mapBound.airport = 1;
		if(mapBound.ctyLat == '' || mapBound.ctyLon == '')
		{
			mapBound.ctyLat = $('#locLat0').val();
			mapBound.ctyLon = $('#locLon0').val();
		}
		if(mapBound.ctyLat == '' || mapBound.ctyLon == '')
		{
			alert("Please select airport first");
		}
		else
		{
			admBooking.showMarkerMap(mapBound, locKey,location);
		}
	}
	
</script>
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key=<?= $api ?>&libraries=places&"></script>
<input id="map_canvas" type="hidden">