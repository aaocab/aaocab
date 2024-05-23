
<style type="text/css">
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; 
        margin: 0; 
    }
    input[type="number"] {
        -moz-appearance: textfield;
    }

    .pac-item >.pac-icon-marker{
        display: none !important;
    }
    .pac-item-query{
        padding-left: 3px;
    }
    .booking-info{
        /*font-variant: all-petite-caps;
        font-size: initial;*/
        color: #d46767;
    }
    .fb-btn{
	background: #3B5998;
	text-transform: uppercase;
	font-size: 14px;
	border: none;
	padding: 7px 8px;
	color: #fff;
	-webkit-border-radius: 2px;
	-moz-border-radius: 2px;
	border-radius: 2px;
	transition: all 0.5s ease-in-out 0s;
    }
    @media (max-width: 767px)
    {
	.modal-dialog{ margin-left: auto; margin-right: auto;}
    }
    .autoMarkerLoc{
	font-size: 30px;
	color:red;
	cursor: pointer;
    }
</style>


<style type="text/css">

    .checkbox-inline {
        padding-top: 0 !important;      
        padding-left: 30px;
        margin-top: -5px !important;      
    }

    .selectize-dropdown-content {
        overflow-y: auto;
        max-height: 200px;
    }

    .selectize-dropdown, .selectize-dropdown.form-control {
        border-radius: 0;
        -webkit-box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
    }

    .selectize-dropdown [data-selectable], .selectize-dropdown .optgroup-header {
        padding: 6px 12px;
        border-bottom: solid 1px #aaa;
    }
    .nav-tabs>li.active ,.nav-tabs>li.active>a, li.active,.nav-tab li:active{
        color: #fff !important;
	/*background: #f13016 !important;*/
	background: #ff4f00 !important;
    }
    .timer-control {
        min-width: 100%;
    }
    .home-search,.home-search1{

    }
    .search-form-panel label{
        margin-bottom: 0;
        font-weight: normal;
    }
    .selectize-dropdown-content{
	padding: 0;
    }

    .cookies_panel{ position: absolute; bottom: 0; z-index: 9999;}
    /*.mob-out-banner img{ width: 100%;}*/
    .mob-app-img a{ width: 47%;}
    .search-pad{ padding-top: 20px!important ;padding-bottom: 18px!important; }
    .search-pad:hover{ padding-top: 20px!important ;padding-bottom: 18px!important;}
    .search-sub-text{ font-size: 0.8em; padding-bottom: 7px!important }

    .logo-section-box{ background: #fff; padding: 15px 10px 8px 10px; display: inline-block; border-radius: 4px; font-size: 12px; font-weight: 500;}
    .logo-fst{ width: 150px; float: left;}
    .logo-fst img{ width: 100%;}
    .logo-sec{ float: left;}
    .stop-menu .navbar-nav li a{ font-size: 14px;}
    .stop-menu .navbar-nav li{ padding: 3px!important;}
    .select-font{ font-weight: 900;}

    .modal-body{ height: 500px;}
    @media (min-width: 991px) and (max-width: 1200px) {
	.logo-section-box{ padding: 8px;}
	.logo-fst{ width: 100px;}
	.logo-fst img{ width: 100px;}
	.logo-sec img{ width: 20%;}
    }
    @media (min-width: 768px) and (max-width: 1024px) {
	.logo-fst{ width: 80px;}
	.logo-fst img{ width: 100%;}
	.logo-sec{ font-size: 8px; width: 130px;}
	.logo-sec img{ width: 30%;}
	.stop-menu .navbar-nav li{ padding: 0!important;}
	.stop-menu .navbar-nav li a{ font-size: 10px; line-height: 18px!important; padding: 2px 5px!important;}

    }
    @media (min-width: 320px) and (max-width: 767px) {
	.logo-section-box{ padding: 8px; background: none;}
	.logo-fst{ width: 100px;}
	.logo-fst img{ width: 100px;}
	.logo-sec{ margin-top: -10px; font-size: 11px;}
	.logo-sec img{ width: 23%;}
    }
    .datepicker {
	padding: 5px
    }
    .btn-rounded.active{
	background-color: #4fb9a7;
	color:#fff;
    }
    .autoMarkerLoc{
	font-size: 30px;
	color:red;
	cursor: pointer;
    }
</style>

<?php 
//$api			 = Yii::app()->params['googleBrowserApiKey'];
$api			 = Config::getGoogleApiKey('browserapikey');
?>
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key=<?= $api ?>&libraries=places&"></script>

<?php
$autoAddressJSVer	 = Yii::app()->params['autoAddressJSVer'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/js/gozo/hyperLocation.js?v=$autoAddressJSVer");
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/jquery.disableAutoFill.min.js');
?>

<?php 
$locFrom		 = [];
$locTo			 = [];
$hyperLocationClass	 = 'txtHyperLocation';
$autocompleteFrom	 = $autocompleteTo		 = $hyperLocationClass;
$locReadonly		 = ['readonly' => 'readonly'];
$locMarkerTo		 = $locMarkerFrom		 = '';
$scvVctId		 = SvcClassVhcCat::model()->getCatIdBySvcid($model->bkg_vehicle_type_id);
if ($model->bkgFromCity->cty_is_poi == 1)
{
    $locFrom		 = $locReadonly;
    $autocompleteFrom	 = '';
    $locMarkerFrom		 = "hide";
}
if ($model->bkgToCity->cty_is_poi == 1)
{
    $locTo		 = $locReadonly;
    $autocompleteTo	 = '';
    $locMarkerTo	 = "hide";
}
?>

<?php
$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
    'id'			 => 'autoAddressForm',
    'enableClientValidation' => true,
    'clientOptions'		 => array(
	'validateOnSubmit'	 => true,
	'errorCssClass'		 => 'has-error'
    ),
    'enableAjaxValidation'	 => false,
    'errorMessageCssClass'	 => 'help-block',
    'htmlOptions'		 => array(
	'class' => 'form-horizontal',
    //	'autocomplete' => 'disabled',
    ),
	));
/* @var $form TbActiveForm */
?>

<div class="col-xs-12 col-sm-12 journey-p">
    <input type="radio" name="payChk" value="0" checked="checked" class="mt5 clsPayChk hide">
    <?= $form->hiddenField($model, "bkg_booking_type"); ?>
    <?= $form->hiddenField($model, "bkg_id"); ?>
    <?= $form->hiddenField($model, "hash", ['value' => Yii::app()->shortHash->hash($model->bkg_id)]); ?>
    <?= $form->hiddenField($model, "bkg_status"); ?>
    <?php 
    if ($model->bkg_booking_type == 7)
    {
	$model->bookingRoutes	 = $model->bookingRoutes;
	$brtRoute		 = $model->bookingRoutes[0];
	$cntRt			 = sizeof($model->bookingRoutes);
	?>
        <div class ="row mt10">
    	<div class="col-xs-12  ">
    	    <label for="pickup_address" class="control-label text-left">Pickup <?= $addressLabel ?> for <?= $brtRoute->brtFromCity->cty_name ?> *:</label>
    	</div>
    	<div class="col-xs-12 pb0">
    	    <div class="form-control" style="min-height: 60px;height: auto;background-color:#eaeaea"><?= $model->bkg_pickup_address ?></div>
    	</div>                    

        </div>

        <div class="row pt20">

    	<div class="col-xs-12 ">

    	    <label for="pickup_address" class="control-label text-left">Drop <?= $addressLabel ?> for <?= $brtRoute->brtToCity->cty_name ?> <?= $optReq ?>:</label>
    	</div>
    	<div class="col-xs-12 mb15 n pb0">
    	    <div class="form-control" style="min-height: 60px;height: auto;background-color:#eaeaea"><?= $model->bkg_drop_address ?></div>
    	</div>

        </div>
	<?php 
	echo $form->hiddenField($brtRoute, "[0]brt_from_latitude", ['class' => 'locLat_0']);
	echo $form->hiddenField($brtRoute, "[0]brt_from_longitude", ['class' => 'locLon_0']);
	echo $form->hiddenField($brtRoute, "[0]brt_from_place_id", ['class' => 'locPlaceid_0']);
	echo $form->hiddenField($brtRoute, "[0]brt_from_formatted_address", ['class' => 'locFAdd_0']);

	echo $form->hiddenField($brtRoute, "[0]brt_to_latitude", ['class' => "locLat_1"]);
	echo $form->hiddenField($brtRoute, "[0]brt_to_longitude", ['class' => "locLon_1"]);
	echo $form->hiddenField($brtRoute, "[0]brt_to_place_id", ['class' => "locPlaceid_1"]);
	echo $form->hiddenField($brtRoute, "[0]brt_to_formatted_address", ['class' => "locFAdd_1"]);
    }
    else
    {
	$j = 0;
	if (!$model->bkg_cav_id)
	{
	    $model->bookingRoutes = $model->bookingRoutes;
	}
	$cntRt = sizeof($model->bookingRoutes);
	foreach ($model->bookingRoutes as $key => $brtRoute)
	{
	    $brtRoute->brt_from_location	 = '';
	    $brtRoute->brt_to_location	 = '';
	    $brtRoute->brtFromCity->cty_name;
	    if ($j > 0)
	    {
		goto skipPickupAddress;
	    }
	    $ctyLat[$key]		 = $brtRoute->brtFromCity->cty_lat;
	    $ctyLon[$key]		 = $brtRoute->brtFromCity->cty_long;
	    $bound[$key]		 = $brtRoute->brtFromCity->cty_bounds;
	    $isCtyAirport[$key]	 = $brtRoute->brtFromCity->cty_is_airport;
	    $isCtyPoi[$key]		 = $brtRoute->brtFromCity->cty_is_poi;

	    if ($brtRoute->brtFromCity->cty_is_airport != 1 && $brtRoute->brtFromCity->cty_is_poi != 1)
	    {
		//$brtRoute->brt_from_location = "";
	    }

	    $locFrom = [];
	    if ($brtRoute->brtFromCity->cty_is_airport == 1 || $brtRoute->brtFromCity->cty_is_poi == 1)
	    {
		$brtRoute->brt_from_latitude		 = $brtRoute->brtFromCity->cty_lat;
		$brtRoute->brt_from_longitude		 = $brtRoute->brtFromCity->cty_long;
		$brtRoute->brt_from_place_id		 = $brtRoute->brtFromCity->cty_place_id;
		$brtRoute->brt_from_formatted_address	 = $brtRoute->brtFromCity->cty_garage_address;
		$locFrom				 = $locReadonly;
		$locMarkerFrom				 = 'hide';
	    }
	    ?>       

	    <div class ="row mt10">
		<div class="col-xs-12 pl0 compact">
		    <label for="pickup_address" class="control-label text-left">Pickup <?= $addressLabel ?> for <?= $brtRoute->brtFromCity->cty_name ?> *:</label>
		    <input type="hidden" id="ctyRad0" class="hide" value="<?= $brtRoute->brtFromCity->cty_radius ?>">
		    <input type="hidden" id="pickupAddressCount" value="<?= $j ?>">
		    <?php 
		    echo $form->hiddenField($brtRoute, "[0]brt_id", ['value' => $brtRoute->brt_id]);
		    echo $form->hiddenField($brtRoute, "[0]brt_from_latitude", ['class' => 'locLat_0']);
		    echo $form->hiddenField($brtRoute, "[0]brt_from_longitude", ['class' => 'locLon_0']);
		    echo $form->hiddenField($brtRoute, "[0]brt_from_place_id", ['class' => 'locPlaceid_0']);
		    echo $form->hiddenField($brtRoute, "[0]brt_from_formatted_address", ['class' => 'locFAdd_0']);
		    echo $form->hiddenField($brtRoute, "[0]brt_from_location_cpy", ['class' => 'cpy_loc_0']);
		    ?>
		</div>
		<div class="col-xs-12 mb15 n pb0">
		    <div class="row">
			<div class="col-xs-10">
			    <?php 
			    $required	 = false;
			    $readOnly	 = '';
			    if ($brtRoute->brtFromCity->cty_is_airport == 1 || $brtRoute->brtFromCity->cty_is_poi == 1)
			    {
				$readOnly = 'readonly';
			    }

			    echo $form->textAreaGroup($brtRoute, "[$key]brt_from_location", array('label' => '', 'widgetOptions' => array('htmlOptions' => ['id' => "locFont_$key", 'class' => "form-control $autocompleteFrom brt_location_$key", 'readonly' => $readOnly, 'required' => $required, "autocomplete" => "section-new", 'placeholder' => "Enter your pickup address (REQUIRED)", "onblur" => "hyperModel.clearAddress(this)"] + $locFrom)));
			    $form->textFieldGroup($model, 'bkg_pickup_address');
			    ?>
			</div>                    
			<div class="col-xs-2"><span class="autoMarkerLoc <?= $locMarkerFrom ?>" data-lockey="<?= $key ?>" data-toggle="tooltip" title="Select source location on map"><img src="/images/locator_icon4.png" alt="Precise location" width="30" height="30"></span></div>
		    </div>
		</div>                    

	    </div>
	    <?php 
	    skipPickupAddress:

	    $key1			 = $key + 1;
	    $ctyLat[$key1]		 = $brtRoute->brtToCity->cty_lat;
	    $ctyLon[$key1]		 = $brtRoute->brtToCity->cty_long;
	    $bound[$key1]		 = $brtRoute->brtToCity->cty_bounds;
	    $isCtyAirport[$key1]	 = $brtRoute->brtToCity->cty_is_airport;
	    $isCtyPoi[$key1]	 = $brtRoute->brtToCity->cty_is_poi;

	    if ($brtRoute->brt_to_place_id == "" && $brtRoute->brtToCity->cty_is_airport != 1 && $brtRoute->brtToCity->cty_is_poi != 1)
	    {
		//$brtRoute->brt_to_location = "";
	    }
	    //$opt	 = ($key1 == $cntRt) ? '(REQUIRED)' : '';
	    $opt	 = 'REQUIRED';
	    $optReq	 = ($key1 == $cntRt) ? ' *' : '';
	    if (in_array($model->bkg_booking_type, [9, 10, 11]))
	    {
		$opt = '(Optional)';
	    }
	    $locTo = [];
	    if ($brtRoute->brtToCity->cty_is_airport == 1 || $brtRoute->brtToCity->cty_is_poi == 1)
	    {
		$brtRoute->brt_to_latitude		 = $brtRoute->brtToCity->cty_lat;
		$brtRoute->brt_to_longitude		 = $brtRoute->brtToCity->cty_long;
		$brtRoute->brt_to_place_id		 = $brtRoute->brtToCity->cty_place_id;
		$brtRoute->brt_to_formatted_address	 = $brtRoute->brtToCity->cty_garage_address;
		$locTo					 = $locReadonly;
		$locMarkerTo				 = 'hide';
	    }
//			if($brtRoute->brt_to_location!=""){
//			        $locTo								 = $locReadonly;   
//			}
	    ?>

	    <div class="row mt10">
		<div class="col-xs-12">
		    <div class="row">
			<div class="col-xs-12 pl0">
			    <label for="pickup_address" class="control-label text-left">Drop <?= $addressLabel ?> for <?= $brtRoute->brtToCity->cty_name ?> <?= $optReq ?>:</label>
			    <input type="hidden" id="ctyRad<?= $key1 ?>"  value="<?= $brtRoute->brtToCity->cty_radius ?>">
			    <?php 
			    if ($j > 0)
			    {
				echo $form->hiddenField($brtRoute, "[$key]brt_id", ['value' => $brtRoute->brt_id]);
			    }
			    ?>
			    <?= $form->hiddenField($brtRoute, "[$key]brt_to_latitude", ['class' => "locLat_$key1"]); ?>
			    <?= $form->hiddenField($brtRoute, "[$key]brt_to_longitude", ['class' => "locLon_$key1"]); ?>
			    <?= $form->hiddenField($brtRoute, "[$key]brt_to_place_id", ['class' => "locPlaceid_$key1"]); ?>
			    <?= $form->hiddenField($brtRoute, "[$key]brt_to_formatted_address", ['class' => "locFAdd_$key1"]); ?>
			    <?= $form->hiddenField($brtRoute, "[$key]brt_to_location_cpy", ['class' => "cpy_loc_$key1"]); ?>

			</div>
			<div class="col-xs-12">
			    <div class="row">
				<div class="col-xs-10">
				    <?php
				    $placeHolder	 = "Enter your drop address ($opt)";
				    $required	 = false;
				    if ($model->bkg_booking_type == 1 && $scvVctId == VehicleCategory::SHARED_SEDAN_ECONOMIC)
				    {
					$required = true;
				    }
				    echo $form->textAreaGroup($brtRoute, "[$key]brt_to_location", array('label' => '', 'widgetOptions' => array("groupOptions" => ["style" => "margin-bottom:0"], 'htmlOptions' => ['id' => "locFont_$key1", 'class' => "form-control $autocompleteTo brt_location_$key1", "autocomplete" => "new-password", 'required' => $required, 'placeholder' => $placeHolder, "onblur" => "hyperModel.clearAddress(this)"] + $locTo)));
				    echo "<span class='hide' style='color:#a94442' id='skipAddErr" . $key1 . "'>Please select any location</span>";
				    if ($key1 == $cntRt)
				    {
					$form->textFieldGroup($model, 'bkg_drop_address');
					CHtml::error($model, 'bkg_drop_address');
				    }
				    ?>
				</div>
				<div class="col-xs-2"><span class="autoMarkerLoc <?= $locMarkerTo ?>" data-lockey="<?= $key1 ?>" data-toggle="tooltip" title="Select destination location on map"><img src="/images/locator_icon4.png" alt="Precise location" width="30" height="30"></span></div>
			    </div>
			</div>

		    </div>
		</div>
	    </div>
	    <?php 
	    $j++;
	}
	?>
        <div class="row">
	    <?php
	    if ($model->bkg_flexxi_type == 2)
	    {
		?>
		<div class="col-xs-12 p0 booking-info">
		    Please provide drop addresses for your trip.
		    You will be provided a common pickup address and pickup time where you will join other riders.
		    <?php  /* /?>at Rs. <?= $model->bkg_rate_per_km_extra ?>/Km</b><?php / */ ?>
		</div>
		<?php 
	    }
	    else
	    {
		?>
		<div class="col-xs-12 p0 booking-info  " id="cityCentreText" style="display: none">
		    The currently quoted amount is quoted from city center to city center. 
		    Exact addresses will help us provide updated more accurate fare quote for your booking. 
		    Distance driven beyond included Kms is billed as applicable. <?php  /* /?>at Rs. <?= $model->bkg_rate_per_km_extra ?>/Km</b><?php / */ ?>
		</div>
	    <?php } ?>
        </div>

    <?php  } ?>
    <button type="button" id="saveAddreses" class="btn btn-effect-ripple btn-success p5 mt10" name="saveAddreses" onclick="saveAddresses();">Save Addresses</button>
</div>
<?php $this->endWidget(); ?>
<script type="text/javascript">
    hyperModel = new HyperLocation();
    countRoutes = '<?= $cntRt ?>';
    model = {};
    $(document).ready(function () {
        setHyperLocationData();
    });

    function setHyperLocationData()
    {
		$('#addressAtPage4').empty();
        model.booking_type = '1';
        model.transfer_type = '1';
        model.ctyLat = <?= json_encode($ctyLat) ?>;
        model.ctyLon = <?= json_encode($ctyLon) ?>;
        model.bound = <?= json_encode($bound) ?>;
        model.isCtyAirport = <?= json_encode($isCtyAirport) ?>;
        model.isCtyPoi = <?= json_encode($isCtyPoi) ?>;
        model.hyperLocationClass = "txtHyperLocation";
        hyperModel.model = model;
         hyperModel.initializepl();
    }


    $('.txtHyperLocation').change(function () {
<?php
if ($model->bkg_booking_type == 4)
{
    ?>
            hyperModel.findAddressAirport(this.id);
    <?php
}
else
{
    ?>
            hyperModel.findAddress(this.id);
<?php } ?>
    });


    $('.autoMarkerLoc').click(function (event) {
        var locKey = $(event.currentTarget).data('lockey');
        var ctyLat = <?= json_encode($ctyLat) ?>;
        var ctyLon = <?= json_encode($ctyLon) ?>;
        var bound = <?= json_encode($bound) ?>;
        var isAirport = <?= json_encode($isCtyAirport) ?>;
        var isCtyPoi = <?= json_encode($isCtyPoi) ?>;
        if ($('.locLat_' + locKey).val() != '' && $('.locLon_' + locKey).val() != '')
        {
            ctyLat[locKey] = $('.locLat_' + locKey).val();
            ctyLon[locKey] = $('.locLon_' + locKey).val();
        }

        if (locKey == 0) {
            var title = 'Enter approximate source location and then move pin to exact location';
            var locSearch = 'source';
        }
        if (locKey > 0) {
            var title = 'Enter approximate destination location and then move pin to exact location';
            var locSearch = 'destination';
        }

        $.ajax({
            "type": "POST",
            "url": '<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/autoMarkerAddress')) ?>',
            "data": {"ctyLat": ctyLat[locKey], "ctyLon": ctyLon[locKey], "bound": bound[locKey], "isCtyAirport": isAirport[locKey], "isCtyPoi": isCtyPoi[locKey], "locKey": locKey, "location": locSearch, "airport": 0, "YII_CSRF_TOKEN": $("input[name='YII_CSRF_TOKEN']").val()},
            "dataType": "HTML",
            "success": function (data1)
            {
                $('#mapModelContent').html(data1);
                $('#mapModal').modal('show');
            }

        });
    });

    function saveAddresses()
    {
        var success = validateAddresses();
        if (success)
        {
            $.ajax({
                "type": "POST",
                "url": '<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/autoaddress')) ?>',
                "data": $('#autoAddressForm').serialize() + '&additionalParams=' + $('.clsAdditionalParams').val(),
                "dataType": "html",
                "success": function (data)
                {
                    data = jQuery.parseJSON(data);
                    if (data.success)
                    {
                        alert("Addresses saved successfully.");
                        $('.isPickupAdrsCls').val(1);
			$('#saveAddreses').attr("disabled","disabled");
                        if (data.data.additional_km > 0) 
			{
                            updateAfterAddressSaved(data);
			    var additionalData = $('.clsAdditionalParams').val();
			    var bkgStatus = $("#Booking_bkg_status").val();
                            if (additionalData != '' && additionalData != undefined && (bkgStatus == 15 || bkgStatus==1)) {
                                var additionalDataObj = JSON.parse(additionalData);
				if (additionalDataObj.wallet > 0) {
                                    prmObj.applyPromo(5, additionalDataObj.wallet);
                                }
                                if (additionalDataObj.code != '') {
                                    prmObj.applyPromo(1, additionalDataObj.code);
                                }
                                if (additionalDataObj.coins > 0) {
                                    prmObj.applyPromo(3, additionalDataObj.coins);
                                }
                               
				
                            }
                        }
                    }
                }

            });
        }
    }

    function updateAfterAddressSaved(data) {

        if (data.data.fare.customerPaid == 0) {
            $('.clsPayChk').checked = true;
        }
        if (huiObj == null || huiObj == undefined) {
            huiObj = new HandleUI();
        }
        huiObj.bkgId = '<?= $model->bkg_id ?>';
        huiObj.updateInvoice(data);
        $('.extrachargeDiv').removeClass('hide');
        $('.additionalKmVal').html(data.data.additional_km);
        $('.extraChargeVal').html(data.data.extra_charge);
        $('.oldBasefareDiv').removeClass('hide');
        $('.txtBaseFareOld').html(data.data.oldBaseFare);
    }

    function validateAddresses() {
        var errorColor = "#a94442";
        var noError = "1px solid #ccc";
        var tripType = $('#Booking_bkg_booking_type').val();
        let i;
        var hasError = 0;
        for (i = 0; i <= countRoutes; i++)
        {
            if ($('.brt_location_' + i).val().trim().length == 0 || $('.locLat_' + i).val() == '' || $('.locLon_' + i).val() == '' || $('.locPlaceid_' + i).val() == '')
            {
                $('.brt_location_' + i).css("border-color", errorColor);
                hasError = 1;
            } else
            {
                $('.brt_location_' + i).css("border", noError);
            }

            if (($('.brt_location_' + i).val().trim().length == 0 || $('.locLat_' + i).val() == '' || $('.locLon_' + i).val() == '' || $('.locPlaceid_' + i).val() == '') && (tripType != 9 && tripType != 10 && tripType != 11))
            {
                $('.brt_location_' + i).css("border-color", errorColor);
                hasError = 1;
            } else
            {
                $('.brt_location_' + i).css("border", noError);
            }
        }

        if (hasError > 0)
        {
            return false;
        }
        return true;
    }
</script>
<input id="map_canvas" type="hidden">

<div class="modal fade" id="mapModal" tabindex="-1" role="dialog" style="z-index:1000000 !important;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="mapModalLabel">Select Precise Location</h4>
            </div>
            <div class="modal-body" id="mapModelContent">

            </div>
        </div>
    </div>
</div>

























































































