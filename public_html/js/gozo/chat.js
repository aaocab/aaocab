/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var Chat = function () {

	this.chatTimer = null;
	this.leftPanel = false;

	this.model = {
		"entityId": 0,
		"entityType": 0,
		"ownerId": 0,
		"userId": 0,
		"userType": 0,
		"chtId": 0,
		"chlId": 0,
		"isClicked": 0
	};

	this.resJson = {
		"jsonData": "",
	};

	this.msgTemplate = '<div class="col-xs-12 pl0"><span class="CLS_TEXT_LEFT NEW_CLS" id="CHL_ID"><span class="blue-color">REF_NAME (REF_TYPE @ CREATED)</span>MSG_APPEAR_ADMIN <span id="2_CHL_ID" class="label VND_SEL_CLASS" onclick="$chat.updateToggleButton(CHT_REF_ID, 2, CHL_ID)" style="cursor:pointer"><span>VND_SEL_CROSS</span> Vendor</span> <span id="3_CHL_ID" class="label DRV_SEL_CLASS" onclick="$chat.updateToggleButton(CHT_REF_ID, 3, CHL_ID)" style="cursor:pointer"><span>DRV_SEL_CROSS</span> Driver</span> <span id="1_CHL_ID" class="label USER_SEL_CLASS" onclick="$chat.updateToggleButton(CHT_REF_ID, 1, CHL_ID)" style="cursor:pointer"><span>USER_SEL_CROSS</span> Customer</span><br><hr class="mt5 mb5"><span class="black-color white-space-pre">CHL_MSG</span><br></span></div>';

	this.infoMsgTemplate = '<div class="col-xs-12 pl0 text-center mt10 mb10"><span class="CLS_TEXT_LEFT NEW_CLS" id="CHL_ID"><span class="blue-color">REF_NAME (REF_TYPE @ CREATED)</span> <span class="black-color">CHL_MSG</span></div>';

	this.leftMsgLITemplate = '<li id="left_CHT_REF_ID" onclick="$chat.setMessageBox(CHT_REF_ID, CHT_REF_TYPE, OWNER_ID, USER_ID, USER_TYPE)" style="cursor:pointer" class="CLASS_ATT_REQ">ENTITY_DETAILS</li>';

	this.bookingDetailsTemplate = '<div class="row top-part m0"><div class="col-xs-12 col-sm-4"><span class="chat-lable-color">Name:</span> CUSTOMER_NAME<br><span class="chat-lable-color">Booking Type:</span> BOOKING_TYPE<br><span class="chat-lable-color">Cab Type:</span> CAB_TYPE<br></div><div class="col-xs-12 col-sm-4"><span class="chat-lable-color">Booking Id:</span> BOOKING_ID<br><span class="chat-lable-color">Distance:</span> DISTANCE<br><span class="chat-lable-color">Duration:</span> DURATION<br></div><div class="col-xs-12 col-sm-4"><span class="chat-lable-color">Pickup Date:</span> PICKUP_DATE<br>RETURN_DATE<span class="chat-lable-color">Route:</span> ROUTE<br></div><a href="DETAILS_LINK" target="_blank"><span class="top-icon"><i class="fa fa-info"></i></span></a></div>';


	this.start = function (interval = 10000) {		 
		this.stop();
		this.chatTimer = setInterval(function () {
			$chat.getChatLogs();
		}, interval);
	},
			this.stop = function () {
				if (this.chatTimer !== null)
				{
					clearInterval(this.chatTimer);
				}
			},
			this.setMessageBox = function (entityId, entityType, ownerId, userId, userType) {

				this.model.entityId = entityId;
				this.model.entityType = entityType;
				this.model.ownerId = ownerId;
				this.model.userId = userId;
				this.model.userType = userType;
				this.model.chtId = 0;
				this.model.chlId = 0;
				this.model.isClicked = 1;

				$("#messageChatTable").empty();
				$("#ChatLog_cht_ref_id").val(entityId);
				$("#ChatLog_cht_ref_type").val(entityType);

				this.getChatLogs();

				if (this.leftPanel == true) {
					this.showChatFormOrJoinLinks();
				}
			};

	this.updateToggleButton = function (eId, eType, chlId) {
		var objChat = this;

		$.ajax({
			"type": "GET",
			"dataType": "json",
			"url": $baseUrl + "/admpnl/chat/updateToggle",
			"data": {"entityId": eId, "entityType": eType, "chlId": chlId},
			"global": false,
			success: function (data)
			{
				if (data.success)
				{
					var eleId = 'span#' + eType + '_' + chlId;
					if (eType == 1) {
						$(eleId).toggleClass('label-default label-success');
						$(eleId).hasClass('label-success') ? $(eleId + ' span').html('[x]') : $(eleId + ' span').html('[]');
					} else if (eType == 2) {
						$(eleId).toggleClass('label-default label-info');
						$(eleId).hasClass('label-info') ? $(eleId + ' span').html('[x]') : $(eleId + ' span').html('[]');
					} else if (eType == 3) {
						$(eleId).toggleClass('label-default label-warning');
						$(eleId).hasClass('label-warning') ? $(eleId + ' span').html('[x]') : $(eleId + ' span').html('[]');
					}
				} else {
					alert('Please join/ takeover the chat');
				}
			},
			"error": function (error) {
				console.log(error);
			}
		});
	};

	this.getChatLogs = function () {

		var objChat = this;
		var model = this.model;

		this.stop();
		$.ajax({
			"type": "GET",
			"dataType": "json",
			"url": $baseUrl + "/admpnl/chat/chatlog",
			"data": model,
			"global": false,
			"async": true,
			success: function (data)
			{
				if (data.success)
				{
					objChat.resJson.jsonData = data;

					objChat.model.chtId = data.chatDetails.cht_id;
					objChat.model.ownerId = data.chatDetails.cht_owner_id;
					objChat.model.userId = data.userId;
					objChat.model.userType = data.userType;

					// Mark previous messages as read
					$("span.unread").removeClass("unread");

					// Booking Details
					objChat.topDetails();

					// Messages (Right Section)
					objChat.populateMessagePanel();

					// Chat Form
					objChat.showChatFormOrJoinLinks();

					if (objChat.leftPanel == true) {

						// Bookings/ Vendors etc... (Left Section)
						objChat.populateLeftPanel(data);

						// Highlight Selected Booking Number In Left
						objChat.highlightSelectedMessagesFromLeft();
					}
				} else if (data.jsonLeft && objChat.leftPanel) {

					// Bookings/ Vendors etc... (Left Section)
					objChat.populateLeftPanel(data);
				}
			},
			"error": function (error) {
				console.log(error);
			},
			"complete": function () {
				objChat.start();
			}
		});
	};

	this.getJoinChatLink = function () {
		var model = this.model;

		var entityId = model.entityId;
		var entityType = model.entityType;

		var chatJoinLink = $("#messageJoinChatLink").html();

		chatJoinLink = chatJoinLink.replace('ParamEntityId', entityId);
		chatJoinLink = chatJoinLink.replace('ParamEntityType', entityType);

		return chatJoinLink;
	};

	this.takeChatOwnerShip = function (ownerShipAct) {
		var objChat = this;

		var ownerShipModel = this.model;
		ownerShipModel.ownerShipAct = ownerShipAct;

		this.stop();
		$.ajax({
			"type": "GET",
			"dataType": "json",
			"url": $baseUrl + "/admpnl/chat/takeover",
			"data": ownerShipModel,
			"global": false,
			success: function (data)
			{
				if (data.success)
				{
					objChat.model.ownerId = data.ownerId;

					objChat.getChatLogs();

					if (objChat.leftPanel == true) {
						// Chat Form
						objChat.showChatFormOrJoinLinks();
					}
				}
			},
			"error": function (error) {
				console.log(error);
			},
			"complete": function () {
				objChat.start();
			}
		});
	};

	this.showChatFormOrJoinLinks = function () {

		$("#messageJoinChatBox").hide();
		$("#messageChatBox").hide();
		$("#messageTakeOverChatBox").hide();

		if (this.model.chtId > 0 || this.model.entityId > 0) {
			if (this.model.ownerId == 0 || this.model.ownerId == null) {
				$("#messageJoinChatBox").show();
			} else if (this.model.ownerId == this.model.userId) {
				$("#messageChatBox").show();
			} else if (this.model.ownerId > 0 && this.model.ownerId != this.model.userId) {
				$("#messageTakeOverChatBox").show();
			}
		}
	};

	this.populateLeftPanel = function (jsonData) {
		var objChat = this;

		$("#ulMsgListLeftTable").empty();
	
		var obj2 = JSON.parse(jsonData.jsonLeft);

		if (Object.keys(obj2).length > 0) {
			var display_txt2 = "";

			$.each(obj2, function (idx, obj)
			{
				display_txt2 += objChat.leftPanelData(obj);
			});
			 
			$("#ulMsgListLeftTable").append(display_txt2);
		}
	};

	this.leftPanelData = function (obj) {
		 
		var objModel = this.model;

		var owner_name = $.trim(obj.owner_name);

		var classAttReq = '';
		if ((obj.cht_status == 0 || (obj.cht_status == 1 && obj.cht_unread_count_for_admin > 0)) && (owner_name == '' || (obj.cht_unread_count_for_admin > 0 && obj.lastMsgTimeDiff >= 1))) {
			owner_name = '<b>Waiting for reply !!!</b>';
			classAttReq = 'att_req';
		}

		if ($.trim(owner_name) != '') {
			owner_name += '<br>';
		}

		var entityDetails = obj.bkg_booking_id + " (" + obj.cht_unread_count_for_admin + ") " + "<br>" + owner_name + " (" + obj.created + ")" + "<br>" + obj.entity_name;

		var leftPanelMessageLI = this.leftMsgLITemplate;

		leftPanelMessageLI = leftPanelMessageLI.replace(/CHT_REF_ID/g, obj.cht_ref_id);
		leftPanelMessageLI = leftPanelMessageLI.replace('CHT_REF_TYPE', obj.cht_ref_type);

		leftPanelMessageLI = leftPanelMessageLI.replace('OWNER_ID', obj.cht_owner_id);
		leftPanelMessageLI = leftPanelMessageLI.replace('USER_ID', objModel.userId);
		leftPanelMessageLI = leftPanelMessageLI.replace('USER_TYPE', objModel.userType);

		leftPanelMessageLI = leftPanelMessageLI.replace('CLASS_ATT_REQ', classAttReq);
		leftPanelMessageLI = leftPanelMessageLI.replace('ENTITY_DETAILS', entityDetails);

		return leftPanelMessageLI;
	};


	// Highlight Selected Booking Number In Left
	this.highlightSelectedMessagesFromLeft = function () {
		var entityId = $("#ChatLog_cht_ref_id").val();

		if (entityId > 0) {
			jQuery('#ulMsgListLeftTable>li.active').removeClass('active');
			jQuery('#left_' + entityId).addClass('active');
		}
	};

	// Populate Message Panel
	this.populateMessagePanel = function () { 
		var objModel = this;
		var model = this.model;

		var obj1 = JSON.parse(this.resJson.jsonData.json);

		if (Object.keys(obj1).length > 0) {
			// Append text at the end
			var display_txt = "";

			$.each(obj1, function (idx, obj)
			{
				//console.log('obj == ', obj);
				objModel.model.chlId = obj.chl_id;
				display_txt += objModel.messagePanelData(obj);
			});
			$("#messageChatTable").append(display_txt);

			$('#messageChatTable').scrollTop($('#messageChatTable')[0].scrollHeight);
		}
	};

	this.messagePanelData = function (obj) {

		var newcls = "";
		if (obj.chl_admin_is_read == 0) {
			newcls = "unread";
		}
		var clsTextLeft = 'chat-text-left';
		if (obj.chl_ref_type == 4 && obj.chl_type <= 0) {
			var clsTextLeft = 'chat-text-right';
		} else if (obj.chl_ref_type == 4 && obj.chl_type > 0) {
			var clsTextLeft = 'chat-text-center';
		}

		var messageMarker = this.msgTemplate;
		if (obj.chl_type > 0) {
			var messageMarker = this.infoMsgTemplate;
		}

		messageMarker = messageMarker.replace('CLS_TEXT_LEFT', clsTextLeft);
		messageMarker = messageMarker.replace('NEW_CLS', newcls);
		messageMarker = messageMarker.replace(/CHL_ID/g, obj.chl_id);
		messageMarker = messageMarker.replace('REF_NAME', obj.ref_name);
		messageMarker = messageMarker.replace('REF_TYPE', obj.ref_type);
		//messageMarker = messageMarker.replace('CREATED', obj.created);
		messageMarker = messageMarker.replace('CREATED', obj.chl_created);
		messageMarker = messageMarker.replace(/CHT_REF_ID/g, obj.cht_ref_id);

		messageMarker = messageMarker.replace('MSG_APPEAR_ADMIN', (obj.chl_ref_type != 4 ? '<span class="label label-primary">Admin</span>' : ''));

		messageMarker = messageMarker.replace('VND_SEL_CLASS', (obj.chl_vendor_visible == 1 ? 'label-info' : 'label-default'));
		messageMarker = messageMarker.replace('VND_SEL_CROSS', (obj.chl_vendor_visible == 1 ? '[x]' : '[]'));

		messageMarker = messageMarker.replace('DRV_SEL_CLASS', (obj.chl_driver_visible == 1 ? 'label-warning' : 'label-default'));
		messageMarker = messageMarker.replace('DRV_SEL_CROSS', (obj.chl_driver_visible == 1 ? '[x]' : '[]'));

		messageMarker = messageMarker.replace('USER_SEL_CLASS', (obj.chl_customer_visible == 1 ? 'label-success' : 'label-default'));
		messageMarker = messageMarker.replace('USER_SEL_CROSS', (obj.chl_customer_visible == 1 ? '[x]' : '[]'));

		messageMarker = messageMarker.replace('CHL_MSG', obj.chl_msg);

		return messageMarker;
	};

	this.topDetails = function () {

		$("#topDetails").hide();
		$("#topDetails").empty();

		var topDetailsMarker = '';

		if (this.model.entityId > 0) {
			if (this.model.entityType == 0) {
				topDetailsMarker = this.topBookingDetails();
			}
		}

		if (topDetailsMarker != '') {
			$("#topDetails").append(topDetailsMarker);
			$("#topDetails").show();
		}
	};

	this.topBookingDetails = function () {
		var booking = this.resJson.jsonData.topDetails;

		var bookingMarker = this.bookingDetailsTemplate;
		bookingMarker = bookingMarker.replace('BOOKING_ID', booking.bkg_booking_id);
		bookingMarker = bookingMarker.replace('CUSTOMER_NAME', booking.consumer_name);
		bookingMarker = bookingMarker.replace('BOOKING_TYPE', booking.booking_type);
		bookingMarker = bookingMarker.replace('CAB_TYPE', booking.cab_type);
		bookingMarker = bookingMarker.replace('ROUTE', booking.route_name);
		bookingMarker = bookingMarker.replace('DISTANCE', booking.bkg_trip_distance + ' Kms');
		bookingMarker = bookingMarker.replace('DURATION', booking.bkg_trip_duration);
		bookingMarker = bookingMarker.replace('PICKUP_DATE', booking.pick_date);
		bookingMarker = bookingMarker.replace('DETAILS_LINK', booking.details_link);

		// Return Date
		if (booking.return_date) {
			var returnDate = '<span class="chat-lable-color">Return Date:</span> ' + booking.return_date + '<br>';
			bookingMarker = bookingMarker.replace('RETURN_DATE', returnDate);
		} else {
			bookingMarker = bookingMarker.replace('RETURN_DATE', '');
		}

		return bookingMarker;
	};
};
       