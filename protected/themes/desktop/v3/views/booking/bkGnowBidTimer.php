<style>
	.bidBox{
		cursor: pointer;
	}
</style> 
<div class="container mb-2 sections" id="timershow"></div>	 
<input type="hidden" id="selectedBid" value="">
<input type="hidden" id="timer1date" >
<input type="hidden" id="activeTimer">
<input type="hidden" id="selectedBooking" value="<?php echo $model->bkg_id; ?>"/>
<div class="hide" id="bdids"></div> 
<div class="hide" id="bdcnts"></div> 
<div class="hide" id="bdshowing"></div>  
<div class="hide" id="bdremove"></div>  
<?php
$hash = Yii::app()->shortHash->hash($model->bkg_id);
?>

<div class="container mb-2 sections" id="section2"   >
	<?= $this->renderPartial('gnowTimerStep2', ['model' => $model, 'timerShow' => true, 'step1DiffSecs' => $step1DiffSecs], false, true); ?>
</div>
<div class="container mb-2 sections" id="section3"  style="display: none">
	<?= $this->renderPartial('gnowTimerStep3', ['model' => $model], false, true); ?>
</div>
<div class="container mb-2 dataDiv" >
	<div class="row justify-center" id="list1"></div>    

	<div class="row btnDiv">
		<div class="col-xl-12 text-center mb-4">
			<button type="button" class="btn btn-warning pl-5 pr-5   btnnxt" id="cancelBtn"  style="display: none">Stop finding a car</button>
		</div>
	</div>
</div>

<script>
	$(document).ready(function ()
	{
		checkLog('0');
		sendVendorNotify();
	});
	function setTimer() {
		$bidStartTimer = setInterval(function () {
			checkLog('1');
		}, 5000);
	}
	function checkLog(checkval)
	{
		$href = "<?php echo Yii::app()->createUrl('booking/getGNowReqData') ?>";
		var bkg_id = '<?php echo $model->bkg_id ?>';
		var hash = '<?php echo $hash ?>';
		var bdids = $("#bdids").text();

		jQuery.ajax({
			global: false,
			type: 'GET',
			dataType: 'json',
			url: $href,
			data: {"booking_id": bkg_id, "hash": hash, "bdids": bdids},
			success: function (data1)
			{
				dataLoad(data1, checkval);
			},
			"error": function (xhr) {
				if (typeof $bidStartTimer !== 'undefined') {
					clearInterval($bidStartTimer);
				}
			}
		});
	}

	function dataLoad1(data1, checkval) {
		if (data1.type == 'html' && data1.timerStat.stepValidation == '1_1_2') {
			if (checkval == '0' || $("#bdcnts").text() != data1.cnt || ($("#list1").text() == '' && data1.cnt > 0)) {
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
			if (data1.timerStat.stepValidation == '1_1_2') {
				$('#acceptBidBtn1').show();
			}
			$('#quoteFound').show();
			$('#quoteDue').hide();
		}
		if (data1.timerStat.stepValidation == '0_0_1') {
			$('#timerresetbtn').hide();
			$('#noCabFound').hide();
			$(".sections").hide();
			$("#section3").show();
			if (typeof $bidStartTimer !== 'undefined') {
				clearInterval($bidStartTimer);
			}
			if (typeof $bidTimer1 !== 'undefined') {
				clearInterval($bidTimer1);
			}
			if (typeof $bidTimer2 !== 'undefined') {
				clearInterval($bidTimer2);
			}
			$('#errorText').text(data1.timerStat.message);
		}
		$(".count1").text(data1.cnt);
		if (data1.timerStat.stepValidation == '0_0_0') {
			$(".sections").hide();
			$("#section3").show();
			if (typeof $bidStartTimer !== 'undefined') {
				clearInterval($bidStartTimer);
			}
			if (typeof $bidTimer1 !== 'undefined') {
				clearInterval($bidTimer1);
			}
			if (typeof $bidTimer2 !== 'undefined') {
				clearInterval($bidTimer2);
			}
		} else
		{
			if ($('#activeTimer').val() != data1.timerStat.stepValidation) {
				$('#timershow').html(data1.timerStat.dataHtml);
				if (data1.timerStat.timerRunning == 'timer1') {
					if (typeof $bidTimer2 !== 'undefined') {
						clearInterval($bidTimer2);
					}
					startTimer1(data1.timerStat.step1DiffSecs);
					if (data1.timerStat.stepValidation == '1_2_1') {
						$('#minMoreText').text('Giving it 3 more minutes');
					}
					if (data1.timerStat.stepValidation == '1_3_1') {
						$(".sections").hide();
						$("#timershow").show();
						$('#minMoreText').text('Giving it 5 more minutes');
					}
				}
				if (data1.timerStat.timerRunning == 'timer2') {
					if (data1.timerStat.stepValidation == '1_3_1') {
						$(".sections").hide();
						$("#timershow").show();
						$('#minMoreText').text('Giving it 5 more minutes');
					}
					if (typeof $bidTimer2 !== 'undefined') {
						clearInterval($bidTimer2);
					}
					startTimer2(data1.timerStat.step1DiffSecs);
				}
				$('#activeTimer').val(data1.timerStat.stepValidation);
			}
		}
	}

	function dataLoad(data1, checkval) {
		if (checkval == '0' && data1.timerStat.step1DiffSecs > 0) {
			startTimer1(data1.timerStat.step1DiffSecs);
		}
		if (checkval == '0' && data1.timerStat.step1DiffSecs <= 0) {

//			alert(data1.timerStat.step1DiffSecs);
//			$('#smallSpinner').show();
//			$('#quoteFound').remove();
//			startTimer1(300);
		}
		if (data1.type == 'url') {
			window.location.href = data1.url;
		}
		if (checkval == '1' && data1.timerStat.vndNotified !== 'undefined' && data1.timerStat.vndNotified == 0) {
			$('#drvTxt').hide();
			$('.noVnd').show();
		}
		if (data1.timerStat.stepValidation == '0_0_1') {
			$('#timerresetbtn').hide();
			$('#noCabFound').hide();
			$(".sections").hide();
			$("#list1").text('');
			$(".btnDiv").hide();
			$("#section3").show();
			if (typeof $bidStartTimer !== 'undefined') {
				clearInterval($bidStartTimer);
			}
			if (typeof $bidTimer1 !== 'undefined') {
				clearInterval($bidTimer1);
			}
			if (typeof $bidTimer2 !== 'undefined') {
				clearInterval($bidTimer2);
			}
			$('#errorText').text(data1.timerStat.message);
			$("#acceptBidBtn1").hide();
		} else {
			$('#cancelBtn').show();
//			$('#timeRemaining').text(data1.timerStat.step1DiffSecs + " minute(s)");
			if (data1.type == 'html') {
				if ($("#bdremove").text() == '') {
//					if (data1.removeIds.length > 0) {
					$("#bdremove").text(data1.removeIds);
					if ($("#bdremove").text() != '') {
						for (drval of data1.removeIds)
						{
							$("#" + drval).remove();
							let bdcnt = $("#bdcnts").text() - 1;
							$(".count1").text(bdcnt);
							$("#bdcnts").text(bdcnt);
						}
					}
					$("#bdremove").text('');
				}
				if (checkval == '0' || $("#bdcnts").text() != data1.cnt || ($("#list1").text() == '' && data1.cnt > 0) || $("#bdids").text() == '') {
					for (dval of data1.bidIds)
					{
						$("#list1").append(data1.dataHtml[dval]);
					}
					$("#bdids").text(data1.bidIds);
				}
				if ($(".bidCards").length != data1.cnt) {
					$("#list1").text('');
					$("#bdids").text('');
					$("#bdcnts").text('');
					$(".count1").text(data1.cnt);
				}
			}

			$('.bidCards').map(function () {
				console.log(this.id);
			});

			if (data1.type == 'url') {
				window.location.href = data1.url;
			}
			if (data1.cnt > 0) {
				$("#bdcnts").text(data1.cnt);
				$(".bidCount").show();
				$('#acceptBidBtn1').show();
				$('#quoteFound').show();
				$('#quoteDue').hide();
				$('#drvTxt').hide();
				$('.noVnd').hide();
				for (dval1 of data1.bidIds)
				{

					var bvrId = dval1.split("_");
					//var fiveMinutes = 60 * 5;
					var fiveMinutes = $('#bidtimeleft_' + bvrId[1]).val();
					display = $('#times_' + bvrId[1]);
					if ($('#isTimerStarted_' + bvrId[1]).val() == 1)
					{
						continue;
					}
					startTimer(fiveMinutes, display);
					$('#isTimerStarted_' + bvrId[1]).val(1);
				}
			} else {

				$("#list1").text('');
				$("#bdids").text('');
			}

			if (data1.timerStat.stepValidation == '0_0_0') {
				$(".sections").hide();
				$("#section3").show();
				if (typeof $bidStartTimer !== 'undefined') {
					clearInterval($bidStartTimer);
				}
				if (typeof $bidTimer1 !== 'undefined') {
					clearInterval($bidTimer1);
				}
				if (typeof $bidTimer2 !== 'undefined') {
					clearInterval($bidTimer2);
				}
			} else {
				$('#cancelBtn').show();
				$('#cancelBtn').removeAttr("disabled");
			}
			$(".count1").text(data1.cnt);
			if (checkval == '0') {
				setTimer();
			}
			if (checkval == '1' && data1.timerStat.durationRemaining > 0 && data1.timerStat.step1DiffSecs <= 0) {
				if (typeof $bidTimer1 !== 'undefined') {
					clearInterval($bidTimer1);
				}
				startTimer1(data1.timerStat.step1DiffSecs);
			}
		}
	}
	$("#acceptBidBtn1").click(function () {
		let bidId = $("#selectedBid").val();
		let bkgId = $("#selectedBooking").val();
		if (bidId == 0) {
			var message = "<div class='errorSummary'><ul><li>Select an offer first.</li></ul></div>";
			toastr['error'](message, 'Failed to process!', {
				closeButton: true,
				tapToDismiss: false,
				timeout: 500000
			});
		} else {
			acceptBid(bidId, bkgId);
		}
	});

	$(document).on('click', '.btnAccpt', function () {
		let curr = $(this);
		let bidId = curr.val();
		let bkgId = $("#selectedBooking").val();
		$("#selectedBid").val(bidId);
		if (bidId == 0) {
			var message = "<div class='errorSummary'><ul><li>Select an offer first.</li></ul></div>";
			toastr['error'](message, 'Failed to process!', {
				closeButton: true,
				tapToDismiss: false,
				timeout: 500000
			});
		} else {
			acceptBid(bidId, bkgId);
		}
	});

	function acceptBid(bidId, bkgId) { 
//		alert(bidId);
		clearInterval($bidStartTimer);
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
					//window.location.href = data1.url;
					paymentreview(hashVal);
					return;
				} else {
					setTimer();
				}
			}
		});
	}
	$(document).on('click', '.btnDeny', function () {
		let curr = $(this);
		let bidId = curr.val();
		let bkgId = $("#selectedBooking").val();

		if (bidId == 0) {
			var message = "<div class='errorSummary'><ul><li>Select an offer first.</li></ul></div>";
			toastr['error'](message, 'Failed to process!', {
				closeButton: true,
				tapToDismiss: false,
				timeout: 500000
			});
		} else {
			denyBid(bidId, bkgId);
		}
	});
	function denyBid(bidId, bkgId) {

		$href = "<?php echo Yii::app()->createUrl('booking/processGNowOfferDeny') ?>";
		var hashVal = '<?php echo $hash ?>';
		msg = 'The offer will be removed from the list.';
		var retVal = confirm(msg);
		if (retVal) {
			jQuery.ajax({
				global: false,
				type: 'GET',
				dataType: 'json',
				url: $href,
				data: {"bidId": bidId, "bookingId": bkgId, "hash": hashVal},
				success: function (data1)
				{
					if (data1.success) {
						var message = "<div class='errorSummary'><ul><li>The offer is rejected</li></ul></div>";
						toastr['success'](message, 'Done', {
							closeButton: true,
							tapToDismiss: false,
							timeout: 500000
						});

						let bdcnt = $("#bdcnts").text() - 1;
						$(".count1").text(bdcnt);
						$("#bdcnts").text(bdcnt);
						$("#" + bidId).remove();

						let bdidstr = $("#bdids").text();
						bdids = bdidstr.split(',')
						var index = bdids.indexOf(bidId);
//		var index1 = $.inArray(bidId,bdids);
						if (index >= 0) {
							bdids.splice(index, 1);
						}
						$("#bdids").text(bdids);
					} else {

					}
				}
			});
		}
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

//	$("#acceptBidBtn").click(function () {
//		 
//		let bidId = $("#selectedBid").val();
//		let bkgId = $("#selectedBooking").val();
//		if (bidId == 0) {
//			alert("select a bid first.");
//		} else {
//			acceptBid(bidId, bkgId);
//		}
//	});
	function showTimer() {

		$href = "<?php echo Yii::app()->createUrl('booking/showtimer') ?>";
		var bkgId = '<?php echo $model->bkg_id ?>';
		var hashVal = '<?php echo $hash ?>';

		jQuery.ajax({
			global: false,
			type: 'GET',
			dataType: 'json',
			url: $href,
			data: {"bookingId": bkgId, "hash": hashVal},
			success: function (data1)
			{
//				alert(data1.timerRunning);
				$('#timershow').html(data1.dataHtml);
				if (data1.timerRunning == 'timer1') {
					startTimer1(data1.step1DiffSecs);
					if (data1.stepValidation == '1_2_1') {
						$('#2minText').text('2 more minutes');
					}
				}
				if (data1.timerRunning == 'timer2') {
					startTimer2(data1.step1DiffSecs);
				}
				$('#activeTimer').val(data1.stepValidation);
			}
		});
	}

	function resetBidTimer() {
		$href = "<?php echo Yii::app()->createUrl('booking/resettimer') ?>";
		var bkgId = '<?php echo $model->bkg_id ?>';
		var hashVal = '<?php echo $hash ?>';
		jQuery.ajax({
			global: false,
			type: 'GET',
			dataType: 'json',
			url: $href,
			data: {"bookingId": bkgId, "hash": hashVal, "bdids": 0},
			success: function (data1)
			{
//				dataLoad(data1);
				if (data1.success) {
					$('#activeTimer').val('');
					checkLog(1);
					setTimer();
				} else {
					$('#errorText').text(data1.message);
					$('#timerresetbtn').hide();
				}
			}
		});
	}


	$("#cancelBtn").click(function () {
		$href = "<?php echo Yii::app()->createUrl('booking/cancelgnow') ?>";
		var bkgId = '<?php echo $model->bkg_id ?>';
		var hashVal = '<?php echo $hash ?>';
		msg = 'Your request will be cancelled.';
		var retVal = confirm(msg);
		if (retVal) {
			$("#cancelBtn").hide();
			$("#cancelBtn").prop('disabled', true);
			jQuery.ajax({
				global: false,
				type: 'GET',
				dataType: 'json',
				url: $href,
				data: {"bookingId": bkgId, "hash": hashVal, "tripId": '<?php echo $model->bkg_bcb_id ?>'},
				success: function (data1)
				{
					if (data1.success) {
						$('#errorText').text(data1.message);
						$('#timerresetbtn').hide();
					}
				}
			});
		}
	});


	function sendVendorNotify()
	{
		var bkg_id = '<?php echo $model->bkg_id ?>';
		var hash = '<?php echo $hash ?>';
		$.ajax({
			"type": "GET",
			"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/notifyGnowVendor')) ?>",
			"async": true,
			data: {"id": bkg_id, "hash": hash},
			"success": function (data2)
			{
				console.log(data2);
			},
			"error": function (xhr) {
				if (xhr.status == "524")
				{
					setTimeOut(sendVendorNotify(), 5000);
				}
			}
		});
	}
</script>
<script>
	function startTimer(duration, display) {
		var timer = duration, minutes, seconds;
		setInterval(function () {
			minutes = parseInt(timer / 60, 10)
			seconds = parseInt(timer % 60, 10);

			minutes = minutes < 10 ? "0" + minutes : minutes;
			seconds = seconds < 10 ? "0" + seconds : seconds;

			display.text(minutes + ":" + seconds);

			if (--timer < 0) {
				display.text("");
			}
		}, 1000);
	}
	
	
	function paymentreview(hash)
	{	
		var href2 = "<?php echo Yii::app()->createUrl('booking/paymentreview') ?>";
		$.ajax({
			"url": href2,
			data: {'hash': hash, 'YII_CSRF_TOKEN': '<?= Yii::app()->request->csrfToken ?>'},
			"type": "POST",
			"dataType": "html",
			"success": function(data)
			{	
				trackPage("<?= CHtml::normalizeUrl($this->getURL('booking/paymentreview')) ?>");
				$('#myAddressModal .modal-body1').html(data);
				$('#myAddressModal .modal-body1').show();
				$('#myAddressModal .modal-body').hide();
				$('#myAddressModal').removeClass('full-screen');
				$('#myAddressModal').addClass('bootbox');
				$('#myAddressModal').addClass('fade');
				$('#myAddressModal').addClass('show');
				$('.modal-backdrop').last().css("display", "block");
				$('#myAddressModal').modal().show();
				$('.clsAdditionalParams').val('{"code":"","coins":0,"wallet":0}');
			},
			"error": function(xhr, ajaxOptions, thrownError)
			{
				alert(xhr.status);
				alert(thrownError);
			}
		});
	}
</script>