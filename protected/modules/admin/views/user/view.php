<div class="row widget-tab-content mb30">
	<div class="col-xs-12">
		<div class="container">
			<div class="row">
				<div class="col-xs-12 col-sm-4 col-lg-3">
					<!-- Nav tabs -->
					<div class="widget-tab-box mb30">
						<ul class="nav nav-tabs" role="tablist" id="viewId">
							<li role="presentation" class="p15 pl20 ml5"><b>Users Details</b></li>
							<li role="presentation" class="active"><a href="#customerInfo" aria-controls="customerInfo" role="tab" data-toggle="tab">Dashboard</a></li>
							<li role="presentation"><a href="#bookinghistory" aria-controls="bookinghistory" role="tab" data-toggle="tab" id="tabbooking">Bookings History</a></li>
							<li role="presentation" id='gozoCoinTransactionli'><a href="#gozoCoinTransaction" aria-controls="gozoCoinTransaction" role="tab" data-toggle="tab">Gozo Coin Transaction</a></li>
							<li role="presentation" id='walletTransactionli'><a href="#walletTransaction" aria-controls="walletTransaction" role="tab" data-toggle="tab">Wallet Transaction</a></li>
							<li role="presentation" id='paymentTransactionli'><a href="#paymentTransaction" aria-controls="paymentTransaction" role="tab" data-toggle="tab">Payment Transaction</a></li>
							<!--<li role="presentation"><a href="#reviews" aria-controls="reviews" role="tab" data-toggle="tab">Reviews</a></li>-->
							<li role="presentation" id="appuse"><a href="#appusage" aria-controls="appusage" role="tab" data-toggle="tab">App Usage</a></li>
							<li role="presentation" id='cbrdetailsli'><a href="#cbrdetails" aria-controls="cbrdetails" role="tab" data-toggle="tab">SCQ Details</a></li>
						</ul>
					</div>
				</div>
				<div class="col-xs-12 col-sm-8 col-lg-9 widget-tab-box5">
					<!-- Tab panes -->
					<div class="widget-tab-box">
						<div class="panel-heading px-5 pt15">User ID <?= $model['user_id'] ?> </div>
						<div class="tab-content">
							<div role="tabpanel" class="tab-pane tabHide active" id="customerInfo">
								<div class="panel">										
									<div class="panel-body p0 pt20">									
										<?php
										$this->renderpartial("customerinfo", [
											'contact'			 => $contact,
											'model'				 => $model,
											'bookingmodel'		 => $bookingmodel,
											'userModel'			 => $userModel,
											'walletBalance'		 => $walletBalance,
											"totalGozoCoins"	 => $totalGozoCoins,
											'totalBookings'		 => $totalBookings,
											"ongoingbooking"	 => $ongoingbooking,
											'upcomingbooking'	 => $upcomingbooking,
											'UserIdArr'			 => $UserIdArr,
												], false, false);
										?>
									</div>
								</div>
							</div>
							<div role="tabpanel" class="tab-pane tabHide " id="bookinghistory">
								<div class="row">
									<div class="col-xs-12 col-lg-6">
										<input type="radio" name="search" value="1" class="active_status" id="searchId" onchange="getuserTripDetails()" checked style="cursor: pointer;" /> <label for="searchId" class="font-18" style="cursor: pointer;">Show Completed & Cancelled Only</label>
									</div>
									<div class="col-xs-12 col-lg-6">
										<input type="radio" name="search" value="2" class="active_status" id="searchId2" onchange="getuserTripDetails()"  style="cursor: pointer;"/><label for="searchId2" class="font-18"  style="cursor: pointer;">Show All</label>
									</div>
								</div>
								<div class="panel">									
									<div class="panel-body p0 pt20" id="showbookinghistory">										
									</div>
								</div>
							</div>
							<div role="tabpanel" class="tab-pane tabHide " id="gozoCoinTransaction">								
							</div>
							<div role="tabpanel" class="tab-pane tabHide " id="walletTransaction">								
							</div>
							<div role="tabpanel" class="tab-pane tabHide " id="paymentTransaction">								
							</div>
							<div role="tabpanel" class="tab-pane tabHide " id="appusage">
								<div class="panel">									
									<div class="panel-body p0 pt20 useapp">
									</div>
								</div>
							</div>
							<div role="tabpanel" class="tab-pane tabHide" id="cbrDetails">								
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	function sendVerifyLinkByEmail()
	{
		var userid = '<?= $userModel->user_id ?>';
		$href = $adminUrl + "/user/SendResetPasswordLink";
		jQuery.ajax({type: 'GET',
			url: $href,
			data: {"id": userid},
			dataType: "html",
			success: function (data)
			{
				//consol.log(data);
				if (data != '') {
					var json = JSON.parse(data);
					if (json.success == false)
					{
						alert(json.error);
					} else {
						$('.verifymsg').html(json);
					}
				}
			}
		});
	}

	function sendOtpByPhone()
	{
		var userid = '<?= $userModel->user_id ?>';
		$href = $adminUrl + "/user/ResetPasswordByPhone";
		jQuery.ajax({type: 'GET',
			url: $href,
			data: {"id": userid},
			dataType: "html",
			success: function (data)
			{
				//consol.log(data);
				if (data != '') {
					var json = JSON.parse(data);
					if (json.success == false)
					{
						alert(json.error);
					} else {
						$('.verifymsg').html(json);
					}
				}
			}
		});
	}
	$("#showsociallink").on("click", function () {
		$('#socialboxDiv').toggle();
		return false;
	});
	$("#tabbooking").on("click", function () {
		getuserTripDetails();
	});
	function getuserTripDetails()
	{
		var userid = '<?= $userModel->user_id ?>';
		var flag = $('input[name=search]:checked').val();
		$href = $adminUrl + "/user/userTripDetails";
		jQuery.ajax({type: 'GET',
			url: $href,
			data: {"userId": userid, "searchBy": flag},
			dataType: "html",
			success: function (data)
			{
				$("#showbookinghistory").html(data);

			}
		});
	}
	$('#appuse').click(function () {
		userAppusage();
	});

	function userAppusage() {
		var vendorId = '<?= $userModel->user_id ?>';
		var type = '1';
		var href = '<?= Yii::app()->createUrl("admpnl/user/appUsage"); ?>';
		$.ajax
				({
					url: href,
					data: {"userId": vendorId, "userType": type},
					type: 'get',
					"dataType": "html",
					success: function (data)
					{
						$('.useapp').html(data);
					}
				});

	}
	$('#gozoCoinTransactionli').click(function () {
		getGozoCoinDetails();
	});

	function getGozoCoinDetails() {
		var userId = '<?= $userModel->user_id ?>';		
		var href = '<?= Yii::app()->createUrl("admpnl/user/getGozoCoinDetails"); ?>';
		$.ajax
				({
					"url": href,
					"data": {"userId": userId},
					"type": 'get',
					"dataType": "html",
					success: function (data)
					{
						$('#gozoCoinTransaction').html(data);
					}
				});

	}
	$('#walletTransactionli').click(function () {
		getWalletDetails();
	});

	function getWalletDetails() {
		var userId = '<?= $userModel->user_id ?>';		
		var href = '<?= Yii::app()->createUrl("admpnl/user/userWalletDetails"); ?>';
		$.ajax
				({
					"url": href,
					"data": {"userId": userId},
					"type": 'get',
					"dataType": "html",
					success: function (data)
					{
						$('#walletTransaction').html(data);
					}
				});

	}
	$('#paymentTransactionli').click(function () {
		getPaymentTransaction();
	});

	function getPaymentTransaction() {
		var userId = '<?= $userModel->user_id ?>';		
		var href = '<?= Yii::app()->createUrl("admpnl/user/paymentTransactionDetails"); ?>';
		$.ajax
				({
					"url": href,
					"data": {"userId": userId},
					"type": 'get',
					"dataType": "html",
					success: function (data)
					{
						$('#paymentTransaction').html(data);
					}
				});

	}
	$('#cbrdetailsli').click(function () {
		getCbrDetails();
	});

	function getCbrDetails() {
		$(".tab-pane").removeClass("active");
		$("#cbrDetails").addClass("active");
		var userId = '<?= $userModel->user_id ?>';		
		var href = '<?= Yii::app()->createUrl("admpnl/user/getCbrDetailsDetails"); ?>';
		$.ajax
				({
					"url": href,
					"data": {"userId": userId},
					"type": 'get',
					"dataType": "html",
					success: function (data)
					{						
						$('#cbrDetails').html(data);
					}
				});

	}
</script>