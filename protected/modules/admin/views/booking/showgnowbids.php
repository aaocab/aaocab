<style>
	.bidBox{
		cursor: pointer;
	}
	.checkbox-round {
		width: 1.3em;
		height: 1.3em;
		background-color: white;
		border-radius: 50%;
		vertical-align: middle;
		border: 1px solid #ddd;
		appearance: none;
		-webkit-appearance: none;
		outline: none;
		cursor: pointer;
	}

	.checkbox-round:checked {
		background-color: gray;
	}
</style>

<div class="col-12 text-center">
	<p class="h4 weight600 ">Trip   ID: <?php echo $model->bkg_bcb_id; ?></p>
	<p class="h4">Booking ID: <?php echo $model->bkg_booking_id; ?></p>
	<p><b><?php echo $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label . '(' . $model->bkgSvcClassVhcCat->scc_ServiceClass->scc_label . $vhcModel . ' )'; ?></b> to travel <b><?php echo $model->bkg_trip_distance; ?> km</b><br>
		from <b><?php echo $model->bkg_pickup_address; ?></b> 
		<br>to <b><?php echo $model->bkg_drop_address; ?></b> 
		<br>on <b><?php echo date('F j, Y', strtotime($model->bkg_pickup_date)); ?></b> at <b><?php echo date('h:i A', strtotime($model->bkg_pickup_date)); ?></b></p>
</div>
<div class='col-12 text-center'><p class='h4 weight600 '>

	</p></div>
<?php
if ($model->bkg_status != 2)
{
	echo "<div class='col-12 text-center panel panel-body'>
	<p class='h4 weight600 '>
	Booking is not in new status
	</p></div>";
}
?>
<div class="col-12 text-right">
	<a class="btn btn-primary" href="<?php echo Yii::app()->createUrl('admin/booking/gnowNotificationList', ['id' => $model->bkg_id]); ?>">
		Notification logs</a></div>

<input type="hidden" id="selectedBid" value="">  
<input type="hidden" id="selectedBooking" value="<?php echo $model->bkg_id; ?>"/>
<div class="hide" id="bdids"></div> 
<div class="hide" id="bdcnts"></div> 
<div class="hide" id="bdshowing"></div> 
<?php
$hash = Yii::app()->shortHash->hash($model->bkg_id);
?>


<div class="row justify-center" id="list1"></div>    
<div class="row " id="btnprcd" style="display: none">
	<div class="col-xl-12 text-center mb-4">
		<button type="button" class="btn btn-primary pl-5 pr-5 text-uppercase btnnxt" id="acceptBidBtn">Proceed</button>
	</div>
</div>


<script>
	$(document).ready(function ()
	{
<?php
if ($model->bkg_status == 2)
{
	?>
			checkLog1();
			setTimer();
<?php } ?>
	});

	function setTimer() {
		$bidStartTimer = setInterval(function () {
			checkLog();
		}, 10000);
	}
	function checkLog()
	{
		$href = "<?php echo Yii::app()->createUrl('admin/booking/getGNowbidData') ?>";
		var bkg_id = '<?php echo $model->bkg_id ?>';
		var bdids = $("#bdids").text();

		jQuery.ajax({
			global: false,
			type: 'GET',
			dataType: 'json',
			url: $href,
			data: {"booking_id": bkg_id, "bdids": bdids},
			success: function (data1)
			{
				dataLoad(data1);
			}
		});
	}
	function checkLog1()
	{
		$href = "<?php echo Yii::app()->createUrl('admin/booking/getGNowbidData') ?>";
		var bkg_id = '<?php echo $model->bkg_id ?>';
		var bdids = $("#bdids").text();

		jQuery.ajax({
			global: false,
			type: 'GET',
			dataType: 'json',
			url: $href,
			data: {"booking_id": bkg_id, "bdids": bdids},
			success: function (data1)
			{
				if (data1.type == 'html') {
					for (dval of data1.bidIds)
					{
						$("#list1").append(data1.dataHtml[dval]);
					}
					$("#bdids").text(data1.bidIds);
				}
				if (data1.type == 'url') {
					window.location.href = data1.url;
				}
				if (data1.cnt > 0) {
					$("#bdcnts").text(data1.cnt);
					$(".bidCount").show();
					$("#btnprcd").show();
				}
				$(".count1").text(data1.cnt);
			}
		});
	}
	function dataLoad(data1) {
		if (data1.type == 'html') {
			if ($("#bdcnts").text() != data1.cnt || ($("#list1").text() == '' && data1.cnt > 0)) {
				for (dval of data1.bidIds)
				{
					$("#list1").append(data1.dataHtml[dval]);
				}
				$("#bdids").text(data1.bidIds);
			}
		}
		if (data1.type == 'url') {
			window.location.href = data1.url;
		}
		if (data1.cnt > 0) {

			$("#bdcnts").text(data1.cnt);
			$(".bidCount").show();
			$("#btnprcd").show();
		}
		$(".count1").text(data1.cnt);
	}
	$("#acceptBidBtn").click(function () {
		let bidId = $("#selectedBid").val();
		let bkgId = $("#selectedBooking").val();
		if (bidId == 0) {
			alert("select a bid first.");
		} else {
			acceptBid(bidId, bkgId);
		}
	});

	function acceptBid(bidId, bkgId) {
		$href = "<?php echo Yii::app()->createUrl('admin/booking/processGNowbidaccept') ?>";
		jQuery.ajax({
			global: false,
			type: 'GET',
			dataType: 'json',
			url: $href,
			data: {"bidId": bidId, "bookingId": bkgId},
			success: function (data1)
			{
				if (data1.success) {
					window.location.href = data1.url;
				} else {
					alert(data1.message);
				}
			}
		});
	}

	$(document).on('change', '.chkbox', function () {
		let curr = $(this);
		let data1 = curr.val();
		$("#selectedBid").val(data1);
		$('.chkbox').prop('checked', false);
		curr.prop('checked', true);
	});


	function processClick(bidid, vndid) {
		$("#selectedBid").val(bidid);
		$('.chkbox').prop('checked', false);
		$('#checkbox_' + vndid + '_' + bidid).prop('checked', true);
	}
</script>



