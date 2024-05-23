/* 
 * Booking
 */
var Booking = function()
{
	this.model = {
		"step": 0,
		"bookingType": 1
	};
	this.data = {};
	this.isMobile = function()
	{
		return (this.Android() || this.BlackBerry() || this.iOS() || this.Opera() || this.Windows());
	};
	this.Android = function()
	{
		return navigator.userAgent.match(/Android/i);
	};
	this.BlackBerry = function()
	{
		return navigator.userAgent.match(/BlackBerry/i);
	};
	this.iOS = function()
	{
		return navigator.userAgent.match(/iPhone|iPad|iPod/i);
	};
	this.Opera = function()
	{
		return navigator.userAgent.match(/Opera Mini/i);
	};
	this.Windows = function()
	{
		return navigator.userAgent.match(/IEMobile/i);
	};

	this.addRouteNew = function(form)
	{   //debugger;
		let currFromCtyId = $('SELECT.ctyPickup').val();
		let currToCtyId = $('SELECT.ctyDrop').val();
		let objBooking = this;
		let bkgType = form[0][1].value;
		$("#error_div").html("");
		$("#error_div").hide();
		if (currFromCtyId == '' || currToCtyId == '')
		{
			$("#error_div").html("Please select Source/destination city");
			$("#error_div").show();
			return false;

		}
		$.ajax({
			"type": "POST",
			"dataType": "html",
			"url": "/booking/addmoreitinerary",
			"data": form.serialize(),
			"beforeSend": function()
			{
				ajaxindicatorstart("");
			},
			"complete": function()
			{
				ajaxindicatorstop();
			},
			"success": function(data2)
			{   //debugger;   
				var data = "";
				var isJSON = false;
				try
				{
					data = JSON.parse(data2);
					isJSON = true;
				}
				catch (e)
				{

				}
				if (!isJSON)
				{   // debugger;
                                  //  alert("ddddddddddddddd");
					$("SELECT.ctyPickup").attr('readonly', true);
					$("SELECT.ctyDrop").attr('readonly', true);
					$("INPUT.datePickup").attr('readonly', true);
					$("INPUT.timePickup").attr('readonly', true);
					$("INPUT.timePickup").next("span").hide();
					$('#bkgItinerary').html(data2);
					$("SELECT.ctyPickup").attr('readonly', true);
					$("#bookingItinerary_es_").css('display','none');
					objBooking.disableRows();
					return;
				}
				if (!data.success && data.errors)
				{
					messages = data.errors;
					objBooking.displayError(form, messages);
				}

			},
			error: function(xhr, ajaxOptions, thrownError)
			{
				alert(thrownError);
				alert(xhr.status);
			}
		});
	};

	// Disable Rows
	this.disableRows = function()
	{
		var elems = $("SELECT.ctyDrop");
		var len = elems.length;
		if (len > 1)
		{
			$("SELECT.ctyPickup")[0].selectize.lock();
			for (var i = 0; i < len - 1; i++)
			{
				this.disableRow(i);
			}
			$('#fieldBefore').show();
		}
	};

	// Disable Row
	this.disableRow = function(i)
	{
		var objBooking = this;
		$("SELECT.ctyDrop")[i].selectize.lock();
		$($("INPUT.datePickup")[i]).attr('readonly', true);
		$($("INPUT.timePickup")[i]).attr('readonly', true);
		if (!objBooking.isMobile())
		{
			$($("INPUT.datePickup")[i]).datepicker("remove");
		}
		$($("INPUT.timePickup")[i]).next("span").hide();
	};

	this.bkRouteReady = function()
	{
		var objBookNow = this;
		trackPage("/booking/pickup");
		$('#fieldBefore').click(function()
		{
			var elems = $("SELECT.ctyDrop");
			var len = elems.length;
			$($(".clsRoute")[len - 1]).remove();
			objBookNow.enableRows();
		});
	};

	this.displayError = function(form, messages)
	{
		settings = form.data('settings');
		content = "";
		let msgs = [];
		for (var key in messages)
		{
			if ($.type(messages[key]) === 'string')
			{
				content = content + '<li>' + messages[key] + '</li>';
				continue;
			}
			$.each(messages[key], function(j, message)
			{
				if ($.type(message) === 'array')
				{
					$.each(messages[key], function(k, v)
					{
						if ($.type(v) == "array")
						{
							$.each(v, function(k1, v1)
							{
								if ($.type(v1) == "array")
								{
									$.each(v1, function(j, message)
									{
										if (msgs.indexOf(message) > -1)
										{
											return;
										}
										msgs.push(message);
										content = content + '<li>' + message + '</li>';
									});
								}
								else
								{
									if (msgs.indexOf(v1) > -1)
									{
										return;
									}
									msgs.push(v1);
									content = content + '<li>' + v1 + '</li>';
								}
							});
						}
						else
						{
							$.each(v, function(j, message)
							{
								if (msgs.indexOf(message) > -1)
								{
									return;
								}
								msgs.push(message);
								content = content + '<li>' + message + '</li>';
							});
						}
					});
				}
				else
				{
					if (msgs.indexOf(message) > -1)
					{
						return;
					}
					msgs.push(message);
					content = content + '<li>' + message + '</li>';
				}
			});
		}
		$('#' + settings.summaryID).toggle(content !== '').find('ul').html(content);
		return (content == "");
	};

};