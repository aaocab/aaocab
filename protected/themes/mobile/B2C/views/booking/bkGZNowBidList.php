<?
/*
 *  @var BookingCab $cabModel 
 *  @var Booking $model 
 */
$cabModel	 = $model->bkgBcb
?>
<?
//$api				 = Yii::app()->params['googleBrowserApiKey'];
$api		 = Config::getGoogleApiKey('browserapikey');
?>
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key=<?= $api ?>&libraries=places&"></script>
<div class="container mb0">
    <div class="row">
        <div class="col-12 col-lg-10 offset-lg-1 mt30">
            <div class="bg-white-box">
                <div class="row">
                    <div class="col-12 text-center">
                        <span class="font-18"><b>Booking ID:<?= Filter::formatBookingId($model->bkg_booking_id); ?></b></span><br>
                        <span class="bg-green3 radius-10 pl10 pr10 color-white">Trip ID: <b><?= $cabModel->bcb_id ?></b></span><br>
                        <span class="color-gray2"><?= date('jS M Y (D) h:i A', strtotime($model->bkg_pickup_date)); ?></span><br>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<div class="content-boxed-widget">
	<div class="content p0 bottom-0">
		<div class="one-half">
			<div class="input-simple-1 has-icon input-green  "><em><b>Included kms:</b></em>					
				<?= $model->bkg_trip_distance; ?>
			</div>
		</div>
		<div class="one-half last-column">
			<div class="input-simple-1 has-icon input-green  "><em><b>Cab type:</b></em>
				<?= $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label . '(' . $model->bkgSvcClassVhcCat->scc_ServiceClass->scc_label . $vhcModel . ' )'; ?>
			</div>
		</div>
		<div class="clear"></div>
	</div>
</div>
<div class="addressWidget">
	<?
	$this->renderPartial('addressWidgetShow', ['model' => $model]);
	?>
</div>

<div class="tab-content p0 content-boxed-widget">


	<!--			|       | Operator			 | Rating    | Punctuality   | # trips <br>completed     | Can pickup at     | Amount<br>(all inclusive)     |
				|-----  |-------------		 |--------   |-------------  |-----------------------    |---------------    |---------------------------    |
				| [ ]   | Operator 0000001   | ****      | 99% ontime    | 459                       | 11:00PM           | 1900                          |
				| [ ]   | Operator 0000002   | *****     | 99% ontime    | 3700                      | 11:30PM           | 1750                          |
				| [ ]   | Operator 0000003   | ***       | 94% ontime    | 678                       | 11:00PM           | 1850                          |
				| [ ]   | Operator 0000004   | ****      | 97% ontime    | 945                       | 11:15PM           | 1925                          |
				| [ ]   | Operator 0000005   | ****      | 98% ontime    | 873                       | 11:30PM           | 1875                          |
	
				PAYMENT DUE ₹ XXXX                    [ BOOK CAR ]  (COUNTDOWN TIMER TO DECIDE)
	-->


	<div id="dataTable" class="bg-white-box">
		<?
		$this->renderPartial('bkGZNowBidListTemplate', ['data' => $data]);
		?>
	</div>



</div> 
<script>
    $(document).ready(function ()
    {
        setTimer();
    });


    function setTimer() {
        setTimeout(function () {
//            checkLog();
        }, 15000);
    }
    function checkLog()
    {

        $href = "<?php echo Yii::app()->createUrl('booking/getGNowReqData') ?>";
        var bkg_id = '<?php echo $model->bkg_id ?>';
        var hash = '<?php echo $hash ?>';
        jQuery.ajax({
            global: false,
            type: 'GET',
            dataType: 'json',
            url: $href,
            data: {"booking_id": bkg_id, "hash": hash},
            success: function (data1)
            {
                if (data1.type == 'html') {
                    $("#dataTable").html(data1.dataHtml);
                    setTimer();

                }
                if (data1.type == 'url') {
                    window.location.href = data1.url;
                }
            }
        });
    }

    function acceptBid(bidId, bkgId) {
        $href = "<?php echo Yii::app()->createUrl('booking/processGNowbidaccept') ?>";
        var hashVal = '<?php echo $hash ?>';
        jQuery.ajax({
            global: false,
            type: 'GET',
            dataType: 'json',
            url: $href,
            data: {"bidId": bidId, "bookingId": bkgId, "hash": hashVal},
            success: function (data1)
            {
                if (data1.success) {
                    window.location.href = data1.url;
                }
            }

        });
    }


</script>