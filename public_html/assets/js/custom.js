function actionChanged(obj) {
	var bkid = obj.id;
	var actvalue = obj.value;
	if (actvalue == '10') {
		alert(obj.id + ':' + obj.value);
	} else {

	}
}
function confirmDelete() {
	if (confirm("Do you really want to delete this driver?")) {
		return true;
	} else {
		return false;
	}
}
function edit(obj)
{
	var $drvid = $(obj).attr('drv_id');
	var href2 = $adminUrl + "/driver/add";
	$.ajax({
		"url": href2,
		"type": "GET",
		"dataType": "json",
		"data": {"drvid": $drvid},
		"success": function (data) {
			var box = bootbox.dialog({
				message: data,
				title: 'Comments',
				onEscape: function () {
					// user pressed escape
				},
			});
		}
	});
}
var tab = "<?= $tab ?>";
jQuery(document).ready(function ($) {
	$('#tabs').tab();
});
function addZeros(n) {
	return (n < 10) ? '0' + n : '' + n;
}
function viewBooking(obj) {
	var href2 = $(obj).attr("href");
	$.ajax({
		"url": href2,
		"type": "GET",
		"dataType": "html",
		"success": function (data) {
			var box = bootbox.dialog({
				message: data,
				title: 'Booking Details',
				size: 'large',
				onEscape: function () {
					//location.href = $adminUrl + "/booking/list";
				},
			});
			if ($('body').hasClass("modal-open"))
			{
				box.on('hidden.bs.modal', function (e) {
					$('body').addClass('modal-open');
				});
			}

		}
	});
	return false;
}




function alertMsgBox(id)
{
	$href = $adminUrl + "/user/ajaxshowchat";
	$cuserid = id;

	jQuery.ajax({type: 'GET',
		url: $href,
		data: {"cuserid": $cuserid},
		success: function (data)
		{
			var box = bootbox.dialog({
				message: data,
				title: 'Comments',
				onEscape: function () {
					// user pressed escape
				},
			});
		}
	});
}
function assignvendor(booking_id, booking2_id) {
	$href = $adminUrl + "/booking/showvendor";
	var $booking_id = booking_id;
	var $booking2_id = booking2_id;
	jQuery.ajax({type: 'GET',
		url: $href,
		data: {"booking_id": $booking_id, "booking2_id": $booking2_id},
		success: function (data)
		{

			box = bootbox.dialog({
				message: data,
				// title: 'Vendors List',
				size: 'large',
				onEscape: function () {

					// user pressed escape
				},
			});
		}
	});
}
function manuallytriggerassignment(booking_id) {
	$href = $adminUrl + "/booking/manuallytriggerassignment";
	var $booking_id = booking_id;
	jQuery.ajax({type: 'GET',
		url: $href,
		dataType: "json",
		data: {"booking_id": $booking_id},
		success: function (data)
		{
			bootbox.alert({
				message: data.message,
				backdrop: true
			});

		}
	});
}
function modifyvendoramount(booking_id) {
	$href = $adminUrl + "/booking/modifyvendoramount";
	var $booking_id = booking_id;
	jQuery.ajax({type: 'GET',
		url: $href,
		data: {"booking_id": $booking_id},
		success: function (data)
		{
			box = bootbox.dialog({
				message: data,
				title: 'Vendors Amount',
				size: 'large',
				onEscape: function () {

					// user pressed escape
				},
			});
		}
	});
}

function matchtrip(booking_id, booking2_id) {
	$href = $adminUrl + "/booking/matchtrip";
	var $booking_id = booking_id;
	var $booking2_id = booking2_id;
	jQuery.ajax({type: 'GET',
		url: $href,
		data: {"booking_id": $booking_id, "booking2_id": $booking2_id},
		success: function (data)
		{
			box = bootbox.dialog({
				message: data,
				title: 'Vendors List',
				size: 'large',
				onEscape: function () {

					// user pressed escape
				},
			});
		}
	});
}

function refreshVendorAssign(data) {
	bootbox.hideAll();
	updateGrid(2);
	removeTabCache(data.newStatus);
}
function vendorAssigned(obj, showForbiddenAlert) {
	if (showForbiddenAlert == 1) {
		var reply = confirm('You are going to assign a forbidden vendor. Do you want to continue at your own risk.')
		if (!reply) {
			return false;
		}
	}
	$href = $(obj).attr('href');

	jQuery.ajax({type: 'GET',
		url: $href,
		dataType: "json",
		success: function (data)
		{
			if (data.success)
			{
				box.remove();
				updateGrid(2);
				removeTabCache(data.newStatus);
			} else
			{
				var errors = data.errors;
				if (errors.code == 1)
				{
					if (data.url != undefined)
					{
						$.ajax({
							"url": data.url,
							"type": "GET",
							"dataType": "html",
							"success": function (data1) {
								var box1 = bootbox.dialog({
									message: data1,
									title: 'Customer Marked as Bad:',
									onEscape: function () {
									},
								});
								if ($('body').hasClass("modal-open"))
								{
									box1.on('hidden.bs.modal', function (e) {
										$('body').addClass('modal-open');
									});
								}
							}
						});
					}
				}
				if (errors.code == 2)
				{
					$(".errorSummary").html(errors.message);
					$(".errorSummary").show();
				}
			}
		}});
	return false;
}

function cabAssigned(status) {
	cabBox.remove();
	updateGrid(status);
	updateGrid(2);
	removeTabCache(5);
}

function remarkSent(status) {
	remarkBox.remove();
	updateGrid(status);
	removeTabCache(5);

}

function sosBoxSent(status) {
	sosBox.remove();
	updateGrid(status);
	removeTabCache(5);
}

function upsellSent(status) {
	upsellBox.remove();
	updateGrid(status);
	removeTabCache(5);

}
function quoteExpired(status) {
	expireQuoteBox.remove();
	updateGrid(status);
	removeTabCache(5);
}
function escalationSent(status) {
	escalationBox.remove();
	updateGrid(status);
	removeTabCache(5);
}

function vendorCancelSent(status) {
	escalationBox.remove();
	updateGrid(status);
	removeTabCache(5);
}
function feedbackSent(status) {
	feedbackBox.remove();
	updateGrid(status);
	removeTabCache(5);
}

function markRemarkBoxSent() {
	markRemarkBox.remove();
	updateGrid(status);
	removeTabCache(status);
}

function addFollowupSent(status) {
	addFollowupBox.remove();
	updateGrid(status);
	removeTabCache(5);
}

function cabdriverInfoSent(status) {
	cabdriverInfoBox.remove();
	updateGrid(status);
	removeTabCache(5);
}

function followupCompleteSent(status) {
	followupBox.remove();
	updateGrid(status);
	removeTabCache(status);
}

function csrAssignSent(status) {
	addCsrBox.remove();
	updateGrid(status);
	removeTabCache(5);

}

function profitabilitySent(status) {
	profitabilityBox.remove();
	updateGrid(status);
	removeTabCache(5);
}

function cabdriverInfoSent() {
	cabdriverInfoBox.remove();
	updateGrid(status);
	removeTabCache(5);
}

function showLog(booking_id, hash) {
	$href = $adminUrl + "/booking/showlog";
	var $booking_id = booking_id;
	var $hash = hash;
//     alert(hash);
//     alert(booking_id);
	jQuery.ajax({type: 'GET',
		url: $href,
		data: {"booking_id": $booking_id, "hash": $hash},
		success: function (data)
		{

			var box = bootbox.dialog({
				message: data,
				title: 'Booking Log',
				onEscape: function () {

					// user pressed escape
				}
			});
		}
	});
}

function showDriverLog(booking_id) {
	$href = $adminUrl + "/booking/ShowDriverLog";
	var $booking_id = booking_id;

	jQuery.ajax({type: 'GET',
		url: $href,
		data: {"booking_id": $booking_id},
		success: function (data)
		{

			var box = bootbox.dialog({
				message: data,
				title: 'Driver Log',
				onEscape: function () {

					// user pressed escape
				}
			});
		}
	});
}
function responseBlastSms(booking_id) {

	$href = $adminUrl + "/booking/requestlist";
	var $booking_id = booking_id;
	jQuery.ajax({type: 'GET',
		url: $href,
		data: {"booking_id": $booking_id},
		success: function (data)
		{

			var box = bootbox.dialog({
				message: data,
				title: 'Availabe 3rd party providers in Source zone',
				size: 'large',
				onEscape: function () {

					// user pressed escape
				}
			});
		}
	});
}


function showRelatedBookings(booking_id) {
	$href = $adminUrl + "/booking/related";
	var $booking_id = booking_id;

	jQuery.ajax({type: 'GET',
		url: $href,
		data: {"booking_id": $booking_id},
		success: function (data)
		{

			var box = bootbox.dialog({
				message: data,
				title: 'Related Bookings',
				onEscape: function () {

					// user pressed escape
				}
			});
		}
	});
}

function receipt(booking_id, hash)
{
	$href = $baseUrl + "/booking/invoice";
	var $booking_id = booking_id;
	var $hash = hash;

//    jQuery.ajax({type: 'GET',
//        url: $href,
//        data: {"booking_id": $booking_id},
//        success: function (data)
//        {
//
//            var box = bootbox.dialog({
//                message: data,
//                title: 'Booking Invoice',
//                onEscape: function () {
//
//                 
//                }
//            });
//        }
//    });

//window.location.href = $href+"?booking_id=" + $booking_id;

	window.open($href + "?bkgId=" + $booking_id + "&hash=" + $hash, '_blank');
}


function verifyBooking(booking_id) {
	$href = $adminUrl + "/booking/verifybooking";
	var $booking_id = booking_id;

	jQuery.ajax({type: 'GET',
		url: $href, dataType: "json",
		data: {"bkid": $booking_id},
		success: function (data)
		{
			if (data.errors != '') {
				alert(data.errors);
				updateGrid(1);
				removeTabCache(2);
			}
			if (data.success == false) {
				editBooking(booking_id, data.errors);
			}
			if (data.success == true) {
//                updateGrid(1);
//                removeTabCache(2);
				verifyContactInfo($booking_id);
			}

		}

	});
}

function verifyContactInfo($booking_id) {

	jQuery.ajax({'type': 'GET', 'url': $adminUrl + "/booking/confirmmobile",
		'data': {'bid': $booking_id},
		success: function (data) {
			box = bootbox.dialog({
				message: data,
				title: '',
				size: 'medium',
				onEscape: function () {
				}
			});
		}
	});
}
var box;
function delBooking(booking_id) {
	$href = $adminUrl + "/booking/delbooking";
	var $booking_id = booking_id;
	jQuery.ajax({type: 'GET',
		url: $href,
		data: {"booking_id": $booking_id},
		success: function (data)
		{
			box = bootbox.dialog({
				message: data,
				title: 'Delete Booking',
				onEscape: function () {
					$(this).remove();
				},
			});
			box.on('hidden.bs.modal', function (e) {
				$(this).data('bs.modal', null);
			});

		}
	});
}


function unvDelBooking(booking_id) {
	$href = $adminUrl + "/booking/unverifieddelbooking";
	var $booking_id = booking_id;
	jQuery.ajax({type: 'GET',
		url: $href,
		data: {"booking_id": $booking_id},
		success: function (data)
		{
			box = bootbox.dialog({
				message: data,
				title: 'Delete Unverified Booking',
				onEscape: function () {
					$(this).remove();
				},
			});
			box.on('hidden.bs.modal', function (e) {
				$(this).data('bs.modal', null);
			});

		}
	});
}
function delSuccess(data) {
	box.remove();
	if (data.success)
	{
		updateGrid(data.oldStatus);
		removeTabCache(data.newStatus);
	}
}

function updateGrid(id) {
	if ($.fn.yiiGridView != undefined && $.fn.yiiGridView.settings['bookingTab' + id] != undefined) {
		$url = $('#bookingTab' + id).yiiGridView('getUrl');
		$('#sec' + id).load($url);
		addTabCache(id);
	}
}

$tabCache = [];
function addTabCache(id) {
	var tab = "sec" + id;
	if ($tabCache.indexOf(tab) == -1)
	{
		$tabCache.push(tab);
	}
}

function removeTabCache(id) {
	var tab = "sec" + id;
	var index = $tabCache.indexOf(tab);
	if (index != -1)
	{
		$tabCache.splice(index, 1);
	}
}

function updateTabLabel(obj) {
	for (var key in obj)
	{
		$('#bkgCount' + key).html(obj[key]);
	}
}
var remarkBox;
function addRemarks(booking_id, hash) {
	$href = $adminUrl + "/booking/addremarks";
	var $booking_id = booking_id;
	var $hash = hash;
	//alert(hash);
	jQuery.ajax({type: 'GET',
		url: $href,
		data: {"booking_id": $booking_id, "hash": $hash},
		success: function (data)
		{
			remarkBox = bootbox.dialog({
				message: data,
				size: 'large',
				title: 'Add Remarks',
				onEscape: function () {
					var isAddRemark = $("#remarkVendorDriverSubmit").val();
					//alert(hash);
					//  alert(isAddRemark);
					if (isAddRemark == 1 && hash != '')
					{
						//location.href = $adminUrl + "/lead/mycall";
					} else
					{
						// location.href = $adminUrl + "/booking/view?id=" + booking_id;
					}
				},
			});
		}
	});
}

var upsellBox;
function addUpsellLink(booking_id) {
	$href = $adminUrl + "/booking/upsellremarks";
	var booking_id = booking_id;
	var upsell = '0';
	jQuery.ajax({type: 'GET',
		url: $href,
		data: {"booking_id": booking_id, "upsell_status": upsell},
		success: function (data)
		{
			upsellBox = bootbox.dialog({
				message: data,
				title: 'Add Upsell',
				onEscape: function () {
					// user pressed escape
				},
			});

		}
	});
}


var sosBox;
function sosTurnOff(booking_id) {
	$href = $adminUrl + "/booking/sosOff";
	var booking_id = booking_id;
	var upsell = '0';
	jQuery.ajax({type: 'GET',
		url: $href,
		data: {"booking_id": booking_id},
		success: function (data)
		{
			sosBox = bootbox.dialog({
				message: data,
				title: 'SOS Turn off',
				onEscape: function () {
					// user pressed escape
				},
			});

		}
	});
}



var carVerifyBox;
function carVerify(booking_id) {
	href = $adminUrl + "/booking/carVerify";
	var booking_id = booking_id;
	bootbox.confirm({
		title: "Car Verify",
		message: "Car Verify Flag on?",
		buttons: {
			confirm: {
				label: 'OK',
				className: 'btn-info'
			},
			cancel: {
				label: 'CANCEL',
				className: 'btn-danger'
			}
		},
		callback: function (result) {
			if (result) {
				jQuery.ajax({'type': 'GET', 'url': href,
					'data': {'booking_id': booking_id},
					success: function (data)
					{
						bootbox.hideAll()
						window.location.reload(true);
					}
				});
			}
		}
	});
}
var box;
function noShow(booking_id) {
	$href = $adminUrl + "/booking/noShowUnset";
	var $booking_id = booking_id;
	jQuery.ajax({type: 'GET',
		url: $href,
		data: {"bkg_id": $booking_id},
		success: function (data)
		{
			box = bootbox.dialog({
				message: data,
				title: 'No Show Unset',
				onEscape: function () {
					// user pressed escape

				},
			});
		}
	});
}

function duplicateBooking(booking_id)
{
	$href = $adminUrl + "/booking/duplicateBooking";
	var $booking_id = booking_id;
	jQuery.ajax({type: 'GET',
		url: $href,
		data: {"bkg_id": $booking_id},
		success: function (data)
		{
			box = bootbox.dialog({
				message: data,
				title: 'Create copies (v2)',
				onEscape: function () {
					alert("Please wait page will reloaded");
					location.reload();
				},
			});
		}
	});
}


function removeUpsellLink(booking_id) {
	$href = $adminUrl + "/booking/upsellremarks";
	var booking_id = booking_id;
	var upsell = '1';
	jQuery.ajax({type: 'GET',
		url: $href,
		data: {"booking_id": booking_id, "upsell_status": upsell},
		success: function (data)
		{
			upsellBox = bootbox.dialog({
				message: data,
				title: 'Remove Upsell',
				onEscape: function () {
					// user pressed escape
				},
			});

		}
	});

}


var escalationBox;
function addEscalationLink(booking_id) {
	$href = $adminUrl + "/booking/escalationremarks";
	var booking_id = booking_id;
	var escalation = '0';
	jQuery.ajax({type: 'GET',
		url: $href,
		data: {"booking_id": booking_id, "escalation_status": escalation},
		success: function (data)
		{
			escalationBox = bootbox.dialog({
				message: data,
				title: 'Add Escalation',
				onEscape: function () {
					// user pressed escape
				},
			});

		}
	});
}


function removeEscalationLink(booking_id) {
	$href = $adminUrl + "/booking/escalationremarks";
	var booking_id = booking_id;
	var escalation = '1';
	jQuery.ajax({type: 'GET',
		url: $href,
		data: {"booking_id": booking_id, "escalation_status": escalation},
		success: function (data)
		{
			escalationBox = bootbox.dialog({
				message: data,
				title: 'Remove Escalation',
				onEscape: function () {
					// user pressed escape
				},
			});

		}
	});
}

var feedbackBox;
function sendFeedback(bookingid) {
	$href = $adminUrl + "/booking/feedbackform";
	var booking_id = bookingid;
	jQuery.ajax({type: 'GET',
		url: $href,
		data: {"bookingID": booking_id},
		success: function (data)
		{
			feedbackBox = bootbox.dialog({
				message: data,
				size: 'large',
				title: 'Send Feedback',
				onEscape: function () {
				},
			});
		}
	});
}

function copyToLead(booking_id) {
	//$href = $adminUrl + "/booking/converttolead";
	$href = $adminUrl + "/lead/leadfollow";
	var $booking_id = booking_id;
	jQuery.ajax({type: 'GET',
		url: $href,
		data: {"booking_id": $booking_id},
		success: function (data)
		{
			var box = bootbox.dialog({
				message: data,
				size: 'large',
				title: 'Booking to Lead',
				onEscape: function () {
					// user pressed escape
				}
			});

		}
	});
}



function canBooking(booking_id) {
	$href = $adminUrl + "/booking/canbooking";
	var $booking_id = booking_id;
	jQuery.ajax({type: 'GET',
		url: $href,
		data: {"booking_id": $booking_id},
		success: function (data)
		{
			var cancelBox = bootbox.dialog({
				message: data,
				title: 'Cancel Booking',
				onEscape: function () {
					// user pressed escape
				},
			});

		}
	});
}


function editBookingInfo(booking_id, errors) {
	$href = $adminUrl + "/booking/editbooking";
	var $booking_id = booking_id;


	//   var error=errors;
	jQuery.ajax({type: 'GET',
		url: $href,
		data: {"bookingID": $booking_id, 'errors': errors},
		success: function (data)
		{
			var box = bootbox.dialog({
				message: data,
				title: 'Edit Booking Info',
				size: 'large',
				onEscape: function () {
					// user pressed escape
				},
			});

		}
	});
}

var cabdriverInfoBox;
function sendCabDriverInfo(booking_id)
{

	$href = $adminUrl + "/booking/sendcabdriverinfo";
	var $booking_id = booking_id;
	jQuery.ajax({type: 'GET',
		url: $href,
		data: {"booking_id": $booking_id},
		success: function (data)
		{
			cabdriverInfoBox = bootbox.dialog({
				message: data,
				title: 'Send Cab/Driver details',
				onEscape: function () {
					// user pressed escape
				},
			});

		}
	});
}

function editBooking(booking_id, errors) {
	$href = $adminUrl + "/booking/edit";
	var $booking_id = booking_id;


	//   var error=errors;
	jQuery.ajax({type: 'GET',
		url: $href,
		data: {"bookingID": $booking_id, 'errors': errors},
		success: function (data)
		{
			var box = bootbox.dialog({
				message: data,
				title: 'Edit Booking',
				size: 'large',
				onEscape: function () {
					// user pressed escape
				},
			});

		}
	});
}
function editUserInfo(booking_id, errors) {
	$href = $adminUrl + "/booking/edituserinfo";
	var $booking_id = booking_id;

	jQuery.ajax({type: 'GET',
		url: $href,
		data: {"bookingID": $booking_id, 'errors': errors},
		success: function (data)
		{
			var box = bootbox.dialog({
				message: data,
				title: 'Edit User Info',
				size: 'large',
				onEscape: function () {
					// user pressed escape
				},
			});

		}
	});
}

function editTravellerInfo(booking_id, errors) {
	$href = $adminUrl + "/booking/editTravellerInfo";
	var $booking_id = booking_id;

	jQuery.ajax({type: 'GET',
		url: $href,
		data: {"bookingID": $booking_id, 'errors': errors},
		success: function (data)
		{
			var box = bootbox.dialog({
				message: data,
				title: 'Edit Traveller Info',
				size: 'large',
				onEscape: function () {
					// user pressed escape
				},
			});

		}
	});
}


var cabBox;
function updateCabDetails(booking_id, tabid) {
	//alert(booking_id)
	// alert(tabid)    
	$href = $adminUrl + "/booking/assigncabdriver";
	// alert($href)
	titlestr = ' Driver Details';
	if (tabid == '5') {
		titlestr = 'Change' + titlestr;
	}
	if (tabid == '3') {
		titlestr = 'Add' + titlestr;
	}
	var $booking_id = booking_id;
	jQuery.ajax({type: 'GET',
		url: $href,
		data: {"booking_id": $booking_id},
		success: function (data)
		{
			cabBox = bootbox.dialog({
				message: data,
				title: titlestr,
				size: 'large',
				onEscape: function () {
					// user pressed escape
				},
			});

		}
	});
}


function canVendor(booking_id) {
	$href = $adminUrl + "/booking/canvendor";
	var $booking_id = booking_id;
	jQuery.ajax({type: 'GET',
		url: $href,
		data: {"booking_id": $booking_id},
		success: function (data)
		{
			var box = bootbox.dialog({
				message: data,
				title: 'Cancel Vendor',
				onEscape: function () {
					// user pressed escape
					// $(this).remove();
				},
			});
		}
	});

}

function completeBooking(booking_id) {
	$href = $adminUrl + "/booking/completebooking";
	var $booking_id = booking_id;

	jQuery.ajax({type: 'GET',
		url: $href,
		data: {"bkid": $booking_id},
		dataType: "json",
		success: function (data)
		{
			if (data.success)
			{
				updateGrid(5);
				removeTabCache(6);
			}
		}
	});
}
function settleBooking(booking_id) {
	$href = $adminUrl + "/booking/settlebooking";
	var $booking_id = booking_id;

	jQuery.ajax({type: 'GET',
		url: $href,
		data: {"bkid": $booking_id},
		success: function (data)
		{
			updateGrid(6);
			removeTabCache(7);
			// window.open($adminUrl + "/booking/list/tab/7", '_parent');
		}
	});
}

function blockMessage(booking_id)
{
	$href = $adminUrl + "/booking/blockMessage";
	jQuery.ajax({type: 'GET',
		url: $href,
		dataType: 'json',
		data: {"bkg_id": booking_id, "bkg_blocked_msg": '0'},
		success: function (data) {
			if (data.success) {
				updateGrid(data.status);
				removeTabCache(data.status);
			} else {
				alert("Sorry error occured");
			}
		},
		error: function (x) {
			alert(x);
		}
	});
}

function unblockMessage(booking_id)
{
	$href = $adminUrl + "/booking/blockMessage";
	jQuery.ajax({type: 'GET',
		url: $href,
		dataType: 'json',
		data: {"bkg_id": booking_id, "bkg_blocked_msg": '1'},
		success: function (data) {
			if (data.success) {
				updateGrid(data.status);
				removeTabCache(data.status);
			} else {
				alert("Sorry error occured");
			}
		},
		error: function (x) {
			alert(x);
		}
	});
}

function updateAmountAndMarkComplete(booking_id) {
	$href = $adminUrl + "/booking/updateamtnmarkcomp";
	var $booking_id = booking_id;
	jQuery.ajax({type: 'GET',
		url: $href,
		data: {"booking_id": $booking_id},
		success: function (data)
		{
			var box = bootbox.dialog({
				message: data,
				title: 'Update amount',
				onEscape: function () {
					// user pressed escape
				},
			});

		}
	});
}

function sendDiscountCode(booking_id) {
	$href = $adminUrl + "/booking/sendpromocode";
	var $booking_id = booking_id;
	jQuery.ajax({type: 'GET',
		url: $href,
		data: {"booking_id": $booking_id},
		success: function (data)
		{
			var box = bootbox.dialog({
				message: data,
				title: 'Send Discount Code',
				onEscape: function () {
					// user pressed escape
				},
			});
		}
	});
}

function remindVendorforUpdate(booking_id) {

	$href = $adminUrl + "/booking/remindvendor";
	var $booking_id = booking_id;

	jQuery.ajax({type: 'GET',
		url: $href,
		data: {"bkid": $booking_id},
		success: function (data)
		{
			window.open($adminUrl + "/booking/list/tab/3", '_parent');
		}
	});
}

function sendReviewLink(booking_id)
{
	$href = $baseUrl + "/booking/sendreviewmail";

	jQuery.ajax({type: 'GET',
		url: $href,
		data: {"bkid": booking_id},
		success: function (data)
		{


		}
	});
}
function sendPaymentLink(booking_id)
{
	$href = $adminUrl + "/booking/sendpaymentlink";
	jQuery.ajax({type: 'GET',
		url: $href,
		data: {"bkid": booking_id},
		success: function (data)
		{
			//consol.log(data);
			if (data != '') {
				var json = JSON.parse(data);
				if (json.success == false)
				{
					alert(json.error);
				}
			}
		}
	});
}
function uploads(booking_id)
{
	$href = $adminUrl + "/booking/uploads";
	jQuery.ajax({type: 'GET',
		url: $href,
		data: {"bkg_id": booking_id},
		success: function (data)
		{
			bootbox.dialog({
				message: data,
				size: 'large',
				title: 'Uploads',
				onEscape: function () {
					// user pressed escape
				},
			});
		}
	});
}
function sendConfirmationDetails(booking_id)
{
	$href = $adminUrl + "/booking/sendconfirmation";

	jQuery.ajax({type: 'GET',
		url: $href,
		data: {"bkid": booking_id},
		success: function (data)
		{
			if (data != '')
			{
				var json = JSON.parse(data);
				if (json.success == false)
				{
					alert(json.error);
				}
			}
		}
	});
}



function sendSMStoDriver(booking_id)
{
	$href = $adminUrl + "/booking/sendsmstodriver";
	jQuery.ajax({type: 'GET',
		url: $href,
		data: {"bkid": booking_id},
		success: function (data)
		{


		}
	});
}
function undoCancelnDelete(booking_id) {

	$href = $adminUrl + "/booking/undocandel";
	var $booking_id = booking_id;
	jQuery.ajax({type: 'GET',
		url: $href, dataType: 'json',
		data: {"booking_id": $booking_id},
		success: function (data)
		{
			updateGrid(data.oldtab);
			removeTabCache(data.newtab);
			// window.open($adminUrl + "/booking/list/tab/" + data, '_parent');
		}
	});
}
function undoAction(booking_id, tabid) {
	$href = $adminUrl + "/booking/undoaction";
	var $booking_id = booking_id;
	jQuery.ajax({type: 'GET',
		url: $href, dataType: 'json',
		data: {"booking_id": $booking_id, 'tabid': tabid},
		success: function (data)
		{
			if (data.hasOwnProperty('success') && data.hasOwnProperty('msg') && !data.success)
			{
				alert(data.msg);
			} else
			{
				updateGrid(data);
				removeTabCache(5);
			}
		}
	});
}
function copyBooking(booking_id) {
	window.open($adminUrl + "/booking/createnew/booking_id/" + booking_id, '_blank');
}

function addCreditsDialog(booking_id) {
	$href = $adminUrl + "/user/addcredits";
	var booking_id = booking_id;
	jQuery.ajax({type: 'GET',
		url: $href,
		data: {"booking_id": booking_id},
		success: function (data)
		{
			bootbox.dialog({
				message: data,
				size: 'large',
				title: 'Add Credits',
				onEscape: function () {
					// user pressed escape
				},
			});

		}
	});
}

var acctbox1;
function setAccountingFlag(booking_id) {
	$href = $adminUrl + "/booking/addaccountingremark";
	jQuery.ajax({type: 'GET',
		url: $href,
		// dataType: 'json',
		data: {"bkg_id": booking_id, "bkg_account_flag": '0'},
		success: function (data) {
			acctbox1 = bootbox.dialog({
				message: data,
				title: 'Add remarks for Accounting Flag',
				size: 'xs',
				onEscape: function () {

				}
			});
			acctbox1.on('hidden.bs.modal', function (e) {
				$('body').addClass('modal-open');
			});

			return true;
//            if (data.success) {
//                updateGrid(data.status);
//                removeTabCache(data.status);
//            } else {
//                alert("Sorry error occured");
//            }
		},
		error: function (x) {
			alert(x);
		}
	});
}

function clearAccountingFlag(booking_id) {
	$href = $adminUrl + "/booking/accountflag";
	jQuery.ajax({type: 'GET',
		url: $href,
		dataType: 'json',
		data: {"bkg_id": booking_id, "bkg_account_flag": '1'},
		success: function (data) {
			if (data.success) {
				updateGrid(data.status);
				removeTabCache(data.status);
			} else {
				alert("Sorry error occured");
			}
		},
		error: function (x) {
			alert(x);
		}
	});
}
function accountFlag(booking_id, flag = '') {
	//booking_id = '<?= $model->bkg_id ?>';
	$href = $adminUrl + "/booking/accountflag";

	jQuery.ajax({type: 'GET',
		url: $href,
		dataType: 'json',
		data: {"bkg_id": booking_id, "bkg_account_flag": flag},
		success: function (data) {
			if (data.success) {
				if (flag == '1') {
					$("#setFlag").show();
					$("#clearFlag").hide();
				} else if (flag == '0') {
					$("#setFlag").hide();
					$("#clearFlag").show();
				}
			} else {
				alert("Sorry error occured");
			}
		},
		error: function (x) {
			alert(x);
		}
	});
}
function reconfirmBooking(booking_id)
{
	$href = $adminUrl + "/booking/reconfirmBooking";
	jQuery.ajax({type: 'GET',
		url: $href,
		dataType: 'json',
		data: {"bkg_id": booking_id},
		success: function (data)
		{
			if (data.success)
			{
				updateGrid(data.status);
				removeTabCache(data.status);
			} 
                        else
			{
				alert("booking no longer available");
			}
		},
		error: function (x) {
			alert(x);
		}
	});

}

function reconfirmBookingSms(booking_id)
{
	$href = $adminUrl + "/booking/reconfirmBookingSms";
	jQuery.ajax({type: 'GET',
		url: $href,
		dataType: 'json',
		data: {"bkg_id": booking_id},
		success: function (data)
		{
			if (data.success)
			{
				updateGrid(data.status);
				removeTabCache(data.status);
			} else
			{
				alert("Sorry error occured");
			}
		},
		error: function (x) {
			alert(x);
		}
	});

}

var markRemarkBox;
function markRemarks(booking_id, remark_type) {
	$href = $adminUrl + "/booking/addmarkremark";
	var $booking_id = booking_id;
	var $remark_type = remark_type;
	var $remarkTitle;
	if ($remark_type == '2') {
		remarkTitle = 'Mark Car Bad';
	} else if ($remark_type == '3') {
		remarkTitle = 'Mark Driver Bad';
	} else if ($remark_type == '4') {
		remarkTitle = 'Mark Vendor Bad';
	}
	jQuery.ajax({type: 'GET',
		url: $href,
		data: {"booking_id": $booking_id, "blg_remark_type": $remark_type},
		success: function (data) {
			markRemarkBox = bootbox.dialog({
				message: data,
				title: remarkTitle,
				onEscape: function () {
					// user pressed escape
				},
			});

		}
	});
}


var followupBox;
function followupComplete(bookingId) {
	$href = $adminUrl + "/booking/completefollowup";
	var $booking_id = bookingId;
	jQuery.ajax({type: 'GET',
		url: $href,
		data: {"booking_id": $booking_id},
		success: function (data)
		{
			followupBox = bootbox.dialog({
				message: data,
				title: 'Followup Complete',
				onEscape: function () {
					// user pressed escape
				},
			});

		}
	});
}

var addFollowupBox;
function addFollowup(bookingId) {
	$href = $adminUrl + "/booking/addfollowup";
	var $booking_id = bookingId;
	jQuery.ajax({type: 'GET',
		url: $href,
		data: {"booking_id": $booking_id},
		success: function (data)
		{
			addFollowupBox = bootbox.dialog({
				message: data,
				title: 'Add Followup',
				onEscape: function () {
					// user pressed escape
				},
			});

		}
	});
}
var addCsrBox;
function addCsr(bookingId) {
	$href = $adminUrl + "/booking/showcsr";
	var $booking_id = bookingId;
	jQuery.ajax({type: 'GET',
		url: $href,
		data: {"booking_id": $booking_id},
		success: function (data)
		{
			addCsrBox = bootbox.dialog({
				message: data,
				title: 'Add CSR',
				onEscape: function () {
					// user pressed escape
				},
			});

		}
	});
}
//var addCsrAllocateBox;
function allocateCsr(bookingId) {
	$href = $adminUrl + "/booking/allocatecsr";
	var $booking_id = bookingId;
	jQuery.ajax({type: 'GET',
		url: $href,
		data: {"booking_id": $booking_id},
		success: function (data)
		{
			addCsrBox = bootbox.dialog({
				message: data,
				title: 'Allocate CSR',
				onEscape: function () {
					// user pressed escape
				},
			});

		}
	});
}
var dispatchCsrBox;
function dispatchCsr(bookingId) {

	$href = $adminUrl + "/booking/checkDispatchCsr";
	var $booking_id = bookingId;

	jQuery.ajax({
		url: $href,
		dataType: "json",
		data: {"bkgId": $booking_id},
		success: function (result)
		{
			if (result.success) {
				if (result.scqId > 0) {
					bootbox.alert(result.msg);
					return false;
				} else {
					$href = $adminUrl + "/booking/dispatchcsr";
					jQuery.ajax({type: 'GET',
						url: $href,
						data: {"booking_id": $booking_id},
						success: function (data)
						{
							dispatchCsrBox = bootbox.dialog({
								message: data,
								title: 'Allocate Dispatch CSR',
								onEscape: function () {
									// user pressed escape
								},
							});

						}
					});
				}
			}

		}
	});


}

function selfassignmentOm(bookingId)
{
	selfAllocatedCBR(bookingId, 1);
}
function selfReassignment(bookingId)
{
	$href = $adminUrl + "/booking/reallocateCsr";
	var $booking_id = bookingId;
	jQuery.ajax({type: 'GET',
		url: $href,
		"dataType": "json",
		data: {"booking_id": $booking_id},
		success: function (data)
		{
			if (data.success)
			{
				location.href = data.url;
			}
		}
	});
}
function autoAssignmentByBid(bookingId) {

	$href = $adminUrl + "/booking/autoAssignmentByBid";
	var $booking_id = bookingId;
	jQuery.ajax({type: 'GET',
		url: $href,
		"dataType": "json",
		data: {"booking_id": $booking_id},
		success: function (data)
		{
			if (data.success)
			{
				updateGrid(2);
				removeTabCache(2);
				//location.href = data.url;
			}
		}
	});
}
function blockUnassign(bookingId)
{

	$href = $adminUrl + "/booking/blockUnassign";
	var $booking_id = bookingId;
	jQuery.ajax({type: 'GET',
		url: $href,
		"dataType": "json",
		data: {"booking_id": $booking_id},
		success: function (data)
		{
			if (data.success)
			{
				updateGrid(2);
				removeTabCache(2);
				//location.href = data.url;
			}
		}
	});
}

function sendDetailToCustomer(bookingId)
{
	$href = $adminUrl + "/booking/sendDetailToCustomer";
	var $booking_id = bookingId;
	jQuery.ajax({type: 'GET',
		url: $href,
		"dataType": "json",
		data: {"booking_id": $booking_id},
		success: function (data)
		{
			if (data.success)
			{
				var box = bootbox.dialog({
					message: data.desc,
					title: 'Details Sent',
					onEscape: function () {
						// user pressed escape
					},
				});
			}
		}
	});
}




function autoCancel(bookingId) {

	$href = $adminUrl + "/booking/autoCancel";
	var $booking_id = bookingId;
	jQuery.ajax({type: 'GET',
		url: $href,
		"dataType": "json",
		data: {"booking_id": $booking_id},
		success: function (data)
		{
			if (data.success)
			{
				updateGrid(data.status);
				removeTabCache(data.status);
				//location.href = data.url;
			}
		}
	});
}
function dutySlipOn(bookingId, dsval)
{

	$href = $adminUrl + "/booking/ChangeDutySlipStatus";
	var booking_id = bookingId;
	jQuery.ajax({type: 'GET',
		url: $href,
		"dataType": "json",
		data: {"booking_id": booking_id, "dsval": dsval},
		success: function (data)
		{

			if (data.success)
			{
				updateGrid(data.status);
				removeTabCache(data.status);

			} else
			{
				alert(data.msg);
			}
		}
	});

}

var remarkCarBox;
function markCarRemarks(booking_id) {
	$href = $adminUrl + "/booking/addmarkremark";
	var $booking_id = booking_id;
	jQuery.ajax({type: 'GET',
		url: $href,
		data: {"booking_id": $booking_id, "blg_remark_type": '2'},
		success: function (data)
		{
			remarkCarBox = bootbox.dialog({
				message: data,
				title: 'Add Mark Car Remark',
				onEscape: function () {
					// user pressed escape
				},
			});

		}
	});
}


var remarkDriverBox;
function markDriverRemarks(booking_id) {
	$href = $adminUrl + "/booking/addmarkremark";
	var $booking_id = booking_id;
	jQuery.ajax({type: 'GET',
		url: $href,
		data: {"booking_id": $booking_id, "blg_remark_type": '3'},
		success: function (data)
		{
			remarkDriverBox = bootbox.dialog({
				message: data,
				title: 'Add Mark Driver Remark',
				onEscape: function () {
					// user pressed escape
				},
			});

		}
	});
}


var remarkVendorBox;
function markVendorRemarks(booking_id) {
	$href = $adminUrl + "/booking/addmarkremark";
	var $booking_id = booking_id;
	jQuery.ajax({type: 'GET',
		url: $href,
		data: {"booking_id": $booking_id, "blg_remark_type": '4'},
		success: function (data)
		{
			remarkVendorBox = bootbox.dialog({
				message: data,
				title: 'Add Mark Vendor Remark',
				onEscape: function () {
					// user pressed escape
				},
			});

		}
	});
}

var profitabilityBox;
function addProfitabilityRemarks(booking_id) {
	$href = $adminUrl + "/booking/profitabilityremarks";
	var booking_id = booking_id;
	jQuery.ajax({type: 'GET',
		url: $href,
		data: {"booking_id": booking_id},
		success: function (data)
		{
			profitabilityBox = bootbox.dialog({
				message: data,
				title: 'Add Profitability Remarks',
				onEscape: function () {
					// user pressed escape
				},
			});

		}
	});
}

function totab(tab) {
	//   window.location.href = '/admin/booking/list/tab/' + tab;
}
function updatePaymentExpiry(booking_id) {
	$href = $adminUrl + "/booking/updatepaymentexpiry";

	jQuery.ajax({type: 'GET',
		url: $href,
		data: {"bkid": booking_id},
		success: function (data)
		{


		}
	});
}


function expirePaymentOption(booking_id) {
	$href = $adminUrl + "/booking/lockpaymentoption";

	jQuery.ajax({type: 'GET',
		url: $href,
		data: {"bkid": booking_id},
		success: function (data)
		{


		}
	});
}

assignCSR = function (obj) {
	//  box.modal('hide');
	$href = $(obj).attr('href');
	jQuery.ajax({
		type: 'GET',
		"dataType": "json",
		url: $href,
		success: function (data1) {
			addCsrBox.hide();
			updateGrid(1);
		}
	});
	return false;
};


function changeFSAddressesAndTime(booking_id) {
	$href = $adminUrl + "/booking/changefsaddresses";
	var $booking_id = booking_id;
	jQuery.ajax({type: 'GET',
		url: $href,
		data: {"booking_id": $booking_id},
		success: function (data)
		{
			var fsAddressBox = bootbox.dialog({
				message: data,
				title: '',
				onEscape: function () {
					// user pressed escape
				},
			});

		}
	});
}

function matchFlexxi(booking_id) {
	$href = $adminUrl + "/booking/flexximatch";
	var $booking_id = booking_id;
	jQuery.ajax({type: 'GET',
		url: $href,
		data: {"booking_id": $booking_id},
		success: function (data)
		{
			var matchflexxi = bootbox.dialog({
				message: data,
				title: '',
				onEscape: function () {
					// user pressed escape
				},
			});

		}
	});
}

// Function for sending SMS to unregistered vendors
function sendSMSToUnregisteredVendors(booking_id) {
	$href = $adminUrl + "/booking/sendSMSToUnregisteredVendors";

	jQuery.ajax({type: 'GET',
		url: $href,
		dataType: 'json',
		data: {"bkg_id": booking_id},
		success: function (data)
		{
			if (data.success)
			{
				if (data.countSms > 0)
				{
					if (data.countSms > 1)
					{
						var msg = data.countSms + ' messages has been sent successfully';
					} else
					{
						var msg = data.countSms + ' message has been sent successfully';
					}
				} else
				{
					var msg = "No vendors found in this zone so message can't be sent."
				}
				alert(msg);
			} else
			{
				if (data.errors != '')
				{
					alert(data.errors);
				} else
				{
					alert("Error in sending SMS");
				}
			}
		},
		error: function (x) {
			alert(x);
		}
	});
}
function assignToOM(booking_id) {
	$href = $adminUrl + "/booking/assignOm";
	var $booking_id = booking_id;
	jQuery.ajax({type: 'GET',
		url: $href,
		'dataType': 'json',
		data: {"booking_id": $booking_id},
		success: function (data)
		{
			//  alert("puja");
			if (data.success) {
				bootbox.alert(data.msg);
				updateGrid(2);
				removeTabCache(2);

			}
		}
	});
}
function startChat(booking_id) {
	//alert(booking_id);
	/*$href = $adminUrl + "/booking/startChat";
	 var $booking_id = booking_id;
	 jQuery.ajax({type: 'GET',
	 url: $href,
	 data: {"entityId": $booking_id},
	 success: function (data)
	 {
	 var startChat = bootbox.dialog({
	 message: data,
	 title: '',
	 onEscape: function () {
	 // user pressed escape
	 $chat.stop();
	 },
	 });
	 }
	 });*/

	$href = $adminUrl + "/booking/startChat";
	var $booking_id = booking_id;
	window.open($href + "?entityId=" + $booking_id, '_blank');
}

var cabBox;
function addPenalty(booking_id) {
	//alert(booking_id)

	$href = $adminUrl + "/booking/addpenalty";
	// alert($href)
	titlestr = 'Add Penalty To Vendor';
	var $booking_id = booking_id;
	jQuery.ajax({type: 'GET',
		url: $href,
		data: {"booking_id": $booking_id},
		success: function (data)
		{
			cabBox = bootbox.dialog({
				message: data,
				title: titlestr,

				onEscape: function () {
					// user pressed escape
					cabBox.hide();
				},
			});
			//alert("pankaj");


		}
	});
}

var approveBox;
function approveDutySlip(booking_id)
{
	$href = $adminUrl + "/booking/approveDutySlip";
	var $booking_id = booking_id;
	jQuery.ajax({type: 'GET',
		url: $href,
		data: {"booking_id": $booking_id},
		success: function (data)
		{
			approveBox = bootbox.dialog({
				message: data,
				size: 'large',
				title: 'Document List',
				onEscape: function () {
					// user pressed escape
				},
			});

		}
	});
}

var dutyBox;
function viewDutySlip(booking_id, viewds)
{
	$href = $adminUrl + "/booking/approveDutySlip";
	var $booking_id = booking_id;
	jQuery.ajax({type: 'GET',
		url: $href,
		data: {"booking_id": $booking_id, "viewds": viewds},
		success: function (data)
		{
			dutyBox = bootbox.dialog({
				message: data,
				size: 'large',
				title: 'Document List',
				onEscape: function () {
					// user pressed escape
				},
			});

		}
	});
}

function skipCsrAllocation(bookingId) {
	$href = $adminUrl + "/booking/skipCsrAllocation";
	var $booking_id = bookingId;
	jQuery.ajax({type: 'GET',
		url: $href,
		data: {"booking_id": $booking_id},
		success: function (data)
		{
			addCsrBox = bootbox.dialog({
				message: data,
				title: 'Allocate CSR',
				onEscape: function () {
					// user pressed escape
					updateGrid(2);
					removeTabCache(data.newStatus);
				},
			});

		}
	});
}

function refundApproval(booking_id, val) {

	$href = $adminUrl + "/booking/changeRefundApprovalStatus";
	var booking_id = booking_id;
	jQuery.ajax({type: 'GET',
		url: $href,
		"dataType": "json",
		data: {"booking_id": booking_id, "dsval": val},
		success: function (data)
		{

			if (data.success)
			{
				updateGrid(data.status);
				removeTabCache(data.status);

			}
		}
	});
}



function adminAction(actionid, booking_id, tabid, hash)
{
	//var action = parseInt($("#bkaction_" + booking_id).val());
	switch (actionid) {
		case 0:
			assignvendor(booking_id, 0);
			break;
		case 1:
			canBooking(booking_id);
			break;
		case 2:
			delBooking(booking_id);
			break;
		case 3:
			editBooking(booking_id, '');
			// window.open($adminUrl + "/booking/edit/bookingID/" + booking_id, '_blank');
			break;
		case 4:
			canVendor(booking_id);
			break;
		case 5:
			updateCabDetails(booking_id, tabid);
			break;
		case 6:
			var r = confirm("Do you really want to remind vendor?");
			if (r == true) {
				remindVendorforUpdate(booking_id);
				alert('A message is sent to the vendor');
			}
			break;
		case 7:
			canVendor(booking_id);
			break;
		case 8:
			completeBooking(booking_id);
			break;
		case 9:
			settleBooking(booking_id);
			break
		case 10:
			verifyBooking(booking_id);
			break;
		case 11:
			sendDiscountCode(booking_id);
			break;
		case 12:
			updateAmountAndMarkComplete(booking_id);
			break;
		case 13:
			sendReviewLink(booking_id);
			break;
		case 14:
			showLog(booking_id);
			break;

		case 15:
			var r = confirm("Do you want to revert the booking to the previous status?");
			if (r == true) {
				undoCancelnDelete(booking_id);
			}
			break;
		case 16:
			var r = confirm("Do you want to revert the booking to the previous status?");
			if (r == true) {
				undoAction(booking_id, tabid);
			}
			break;
		case 17:
			var r = confirm("Do you want to send SMS to Driver?");
			if (r == true) {
				sendSMStoDriver(booking_id);
			}
			break;
		case 18:
			var r = confirm("Do you want to copy the data to a new Booking?");
			if (r == true) {
				copyBooking(booking_id);
			}
			break;
		case 20:
			receipt(booking_id, hash);
			break;
		case 21:
			addRemarks(booking_id, hash);
			break;
		case 22:
			copyToLead(booking_id);
			break;
		case 23:
			showRelatedBookings(booking_id);
			break;
		case 24:
			sendPaymentLink(booking_id);
			break;
		case 25:
			sendConfirmationDetails(booking_id);
			break;
		case 26:
			addUpsellLink(booking_id);
			break;
		case 27:
			addEscalationLink(booking_id);
			break;
		case 28:
			removeUpsellLink(booking_id);
			break;
		case 29:
			removeEscalationLink(booking_id);
			break;
		case 30:
			sendFeedback(booking_id);
			break;
		case 31:
			addCreditsDialog(booking_id);
			break;
		case 32:
			setAccountingFlag(booking_id);
			break;
		case 33:
			clearAccountingFlag(booking_id);
			break;
		case 34:
			markRemarks(booking_id, '2');
			break;
		case 35:
			markRemarks(booking_id, '3');
			break;
		case 36:
			markRemarks(booking_id, '4');
			//markVendorRemarks(booking_id);
			break;
		case 37:
			uploads(booking_id);
			break;
		case 38:
			var r = confirm("Do you want update payment expiry time?");
			if (r == true) {
				updatePaymentExpiry(booking_id);
				alert('Payment expiry time updated');
			}
			break;
		case 39:
			var r = confirm("Do you want to lock user payment option?");
			if (r == true) {
				expirePaymentOption(booking_id);
				alert('Payment option locked');
			}
			break;
		case 40:
			addFollowup(booking_id);
			break;
		case 41:
			followupComplete(booking_id);
			break;
		case 42:
			addCsr(booking_id);
			break;
		case 43:
			blockMessage(booking_id);
			break;
		case 44:
			unblockMessage(booking_id);
			break;
		case 45:
			modifyvendoramount(booking_id);
			break;
		case 46:
			addProfitabilityRemarks(booking_id);
			break;
		case 47:
			reconfirmBooking(booking_id);
			break;
		case 48:
			reconfirmBookingSms(booking_id);
			break;
		case 49:
			editUserInfo(booking_id, '');
			break;
		case 50:
			unvDelBooking(booking_id);
			break;
		case 51:
			editBookingInfo(booking_id, '');
			break;
		case 52:
			sendCabDriverInfo(booking_id);
			break;
		case 53:
			showDriverLog(booking_id);
			break;
		case 54:
			changeFSAddressesAndTime(booking_id);
			break;
		case 55:
			matchFlexxi(booking_id);
			break;
		case 56:
			var unv = confirm("Are you sure to send SMS to unregistered vendors?");
			if (unv == true) {
				sendSMSToUnregisteredVendors(booking_id);
			}
			break;
		case 57:
			responseBlastSms(booking_id);
			break;
		case 58:
			var unv = confirm("Are you want to delegate this booking to Operation Manager?");
			if (unv == true) {
				assignToOM(booking_id);
			}
			break;
		case 59:
			allocateCsr(booking_id);
			break;
		case 60:
			var unv = confirm("Do you want to delegate this booking to yourself for vendor assignment?");
			if (unv == true) {
				selfassignmentOm(booking_id);
			}
			break;
		case 61:
			startChat(booking_id);
			break;
		case 62:
			autoAssignmentByBid(booking_id);
			break;
		case 63:
			autoAssignmentByBid(booking_id);
			break;
		case 64:
			autoCancel(booking_id);
			break;
		case 65:
			autoCancel(booking_id);
			break;
		case 66:
			addPenalty(booking_id);
			break;
		case 67:
			dutySlipOn(booking_id, 0);
			break;
		case 68:
			dutySlipOn(booking_id, 1);
			break;
		case 69:
			approveDutySlip(booking_id);
			break;
		case 70:
			viewDutySlip(booking_id, 1);
			break;
		case 71:
			skipCsrAllocation(booking_id);
			break;
		case 72:
			sosTurnOff(booking_id);
			break;
		case 73:
			var r = confirm("Do you want to approve refund request ?");
			if (r == true) {
				refundApproval(booking_id, 3);
			}
			break;
		case 74:
			var r = confirm("Do you want to disapprove refund request ?");
			if (r == true) {
				refundApproval(booking_id, 2);
			}
			break;
		case 75:
			noShow(booking_id);
			break;
		case 76:
			duplicateBooking(booking_id);
			break;
		case 77:

			bootbox.confirm({
				message: "We will now schedule manual assignment. Please talk to all vendors and ask them to give their best bid now. System will now assign the cab to best match vendor. ",
				buttons: {

					cancel: {
						label: 'CANCEL',
						className: 'btn-danger'
					},
					confirm: {
						label: 'CONTINUE ',
						className: 'btn-success'
					}
				},
				callback: function (result) {
					if (result)
					{
						manuallytriggerassignment(booking_id);
					}
				}
			});
			break;
		case 78:
			adminrefund(booking_id);
			break;
		case 79:
			refundFromWallet(booking_id);
			break;
		case 80:
			carVerify(booking_id);
			break;
		case 81:
			expireQuote(booking_id);
			break;
		case 82:
			viewPenalty(booking_id);
			break;
		case 83:
			viewPartnerApiSync(booking_id);
			break;
		case 85:
			vendorNotAssigned(booking_id);
			break;
		case 86:
			notifyVendor(booking_id);
			break;
		case 87:

			viewArchive(booking_id);
			break;
		case 88:
			activateGozonow(booking_id);
			break;
		case 89:
			showGozoNowBids(booking_id);
			break;
		case 90:
			gnowAdminReNotify(booking_id);
			break;
		case 91:
			askManualAssignment(booking_id);
			break;
		case 92:
			var unv = confirm("Do you want to reallocate this booking to yourself for vendor assignment?");
			if (unv == true) {
				selfReassignment(booking_id);
			}
			break;
		case 94:
			dispatchCsr(booking_id);
			break;
		case 93:
			selfAllocatedCBR(booking_id, 0);
			break;
		case 95:
			editTravellerInfo(booking_id, '');
			break;
		case 96:
			NoShowCBR(booking_id, 2);
			break;
		case 97:
			NoShowCBR(booking_id, 1);
			break;
		case 98:
			viewVendorCompensation(booking_id);
			break;
		case 99:
			viewOperatorApiSync(booking_id);
			break;
		case 100:
			blockUnassign(booking_id);
			break;
		case 101:
			blockUnassign(booking_id);
			break;
		case 102:
			sendDetailToCustomer(booking_id);
			break;
                case 103:
			cngAllowed(booking_id);
			break;  
                case 104:
			pushDriverCustomEvents(booking_id, '');
			break; 

	}
}

function dialLead(ip, user, pass, phone, code)
{
	var url = "http://" + ip + "/agc/api.php?source=test&user=" + user + "&pass=" + pass + "&agent_user=" + user + "&function=external_dial&value=" + phone + "&phone_code=" + code + "&search=YES&preview=NO&focus=YES";
	$.ajax({
		url: url,
		method: 'GET',
		success: function (data) {
			bootbox.alert(data);
		}
	});
}
function adminrefund(booking_id)
{
	$href = $adminUrl + "/booking/adminrefund";
	if (confirm("Do you  want to refund from the booking?")) {

		var $booking_id = booking_id;
		jQuery.ajax({type: 'GET',
			url: $href,
			data: {"bkg_id": $booking_id},
			success: function (data)
			{
				box = bootbox.dialog({
					message: data,
					title: 'Refund in progress',
					onEscape: function () {
						alert("Please wait page will reloaded");
						location.reload();
					},
				});
			}
		});
	} else {
		return false;
	}
}

function refundFromWallet(booking_id)
{
	$href = $adminUrl + "/booking/refundFromWallet";
	if (confirm("Do you  want to refund from the wallet to user?")) {

		var $booking_id = booking_id;
		jQuery.ajax({type: 'GET',
			url: $href,
			data: {"bkg_id": $booking_id},
			success: function (data)
			{
				box = bootbox.dialog({
					message: data,
					title: 'Refund from wallet',
					onEscape: function () {
						alert("Please wait page will reloaded");
						location.reload();
					},
				});
			}
		});
	} else {
		return false;
	}
}
var expireQuoteBox;
function expireQuote(booking_id)
{
	$href = $adminUrl + "/booking/expireQuote";
	var $booking_id = booking_id;
	jQuery.ajax({type: 'GET',
		url: $href,
		data: {"booking_id": $booking_id},
		success: function (data)
		{
			expireQuoteBox = bootbox.dialog({
				message: data,
				title: 'Expire Quotation',
				onEscape: function () {
					// user pressed escape

				},
			});
		}
	});
}
function viewPenalty(booking_id)
{
	$href = $adminUrl + "/transaction/viewPenalty";
	var $booking_id = booking_id;
	jQuery.ajax({type: 'GET',
		url: $href,
		data: {"bkg_id": $booking_id},
		success: function (data)
		{
			box = bootbox.dialog({
				message: data,
				size: 'large',
				title: 'View Penalty',
				onEscape: function () {
					//alert("Please wait page will reloaded");
					//location.reload();
				},
			});
		}
	});

}
function viewPartnerApiSync(booking_id)
{
	var url = $adminUrl + "/generalReport/trackingView?id=" + booking_id + "&eventId=187";
	$.ajax({
		url: url,
		method: 'GET',
		success: function (data) {
			var newWindow = window.open(url, "_blank");
		}
	});
}

function vendorNotAssigned(booking_id)
{
	var url = $adminUrl + "/booking/vendorNotAssigned";
	$.ajax({
		url: url,
		method: 'GET',
		success: function (data) {
			addCsrBox = bootbox.dialog({
				message: data,
				title: '',
				onEscape: function () {
					// user pressed escape
				},
			});
		}
	});
}



function notifyVendor(booking_id)
{
	$href = $adminUrl + "/booking/notifyvendor";
	jQuery.ajax({type: 'GET',
		url: $href,
		data: {"bkid": booking_id},
		success: function (data)
		{
			//consol.log(data);
			if (data != '') {
				var json = JSON.parse(data);
				if (json.success == true)
				{
					alert(json.msg);
				}
			}
		}
	});
}
function viewArchive(booking_id)
{
	var url = $adminUrl + "/email/list?type='archive'&bookingId=" + booking_id;
	window.open(url, "", "fullscreen=yes");
}


function activateGozonow(booking_id)
{
	$href = $adminUrl + "/booking/activateGozonow";
	jQuery.ajax({type: 'GET',
		url: $href,
		dataType: 'json',
		data: {"bkg_id": booking_id},
		success: function (data)
		{
			if (data.success)
			{
				alert(data.message);
				showGozoNowBids(booking_id);
			} else {
				alert(data.message);
			}
		},
		error: function (x) {
			alert(x);
		}
	});

}
function showGozoNowBids(booking_id) {
	window.open($adminUrl + "/booking/showgnowbidlist/id/" + booking_id, '_blank');
}
function gnowAdminReNotify(booking_id) {
	$href = $adminUrl + "/booking/gnowAdminReNotify";
	jQuery.ajax({type: 'GET',
		url: $href,
		dataType: 'json',
		data: {"bkg_id": booking_id},
		success: function (data)
		{
			if (data.success)
			{
				alert(data.message);
				if (data.gozonow === '1') {
					showGozoNowOffers(booking_id);
				}
				if (data.gozonow === '2') {
					showGozoNowBids(booking_id);
				}
			} else {
				alert(data.message);
			}
		},
		error: function (x) {
			alert(x);
		}
	});
}
function showGozoNowOffers(booking_id) {
	window.open($adminUrl + "/booking/gnowNotificationList/id/" + booking_id, '_blank');
}
function askManualAssignment(booking_id) {

	$href = $adminUrl + "/booking/askManualAssignment";
	jQuery.ajax({type: 'GET',
		url: $href,
		data: {"bkg_id": booking_id},
		success: function (data)
		{
			box = bootbox.dialog({
				message: data,
				size: 'medium',
				title: 'Ask for manual assignment',
				onEscape: function () {

				},
			});
		}
	});
}


function selfAllocatedCBR(booking_id, type) {

	$href = $adminUrl + "/booking/selfAllocatCBR";
	jQuery.ajax({type: 'GET',
		url: $href,
		dataType: 'json',
		data: {"bkg_id": booking_id, "type": type},
		success: function (data)
		{
			if (data.success)
			{
				window.location.href = data.url;
			} else
			{
				if (data.msg)
				{
					bootbox.alert(data.msg);
				} else
				{
					bootbox.alert("Some error occured");
				}


			}
		}, error: function (x) {
			bootbox.alert(x);
		}
	});
}


function NoShowCBR(booking_id, type) {

	$href = $adminUrl + "/booking/AddNoShowCBR";
	jQuery.ajax({type: 'GET',
		url: $href,
		dataType: 'json',
		data: {"bkg_id": booking_id, "type": type},
		success: function (data)
		{
			if (data.success)
			{
				bootbox.alert(data.msg);
			} else
			{
				bootbox.alert(data.msg);
			}
		}, error: function (x) {
			bootbox.alert(x);
		}
	});
}

function viewVendorCompensation(booking_id)
{
	$href = $adminUrl + "/booking/viewVendorCompensation";
	var $bookingid = booking_id;
	jQuery.ajax({type: 'GET',
		url: $href,
		data: {"bkgid": $bookingid},
		success: function (data)
		{
			box = bootbox.dialog({
				message: data,
				size: 'medium',
				title: 'View Vendor Compensation',
				onEscape: function () {
					//alert("Please wait page will reloaded");
					//location.reload();
				},
			});
		}
	});
}

function viewOperatorApiSync(booking_id)
{
	var url = $adminUrl + "/generalReport/operatorTrackingView?id=" + booking_id;
	$.ajax({
		url: url,
		method: 'GET',
		success: function (data) {
			var newWindow = window.open(url, "_blank");
		}
	});
}

var cngBox;
function cngAllowed(booking_id) {
	$href = $adminUrl + "/booking/cngAllowed";
	var booking_id = booking_id;
	var cngallowed = '0';
	jQuery.ajax({type: 'GET',
		url: $href,
		data: {"booking_id": booking_id, "cngallowed_status": cngallowed},
		success: function (data)
		{
			cngBox = bootbox.dialog({
				message: data,
				title: 'CNG Allowed',
				onEscape: function () {
					
				},
			});

		}
	});
}

function pushDriverCustomEvents(booking_id, errors) {
	$href = $adminUrl + "/booking/pushDriverCustomEvents";
	var $booking_id = booking_id;

	jQuery.ajax({type: 'GET',
		url: $href,
		data: {"bookingID": $booking_id, 'errors': errors},
		success: function (data)
		{
			var box = bootbox.dialog({
				message: data,
				title: '',
				size: 'large',
				onEscape: function () {
					// user pressed escape
				},
			});

		}
	});
}