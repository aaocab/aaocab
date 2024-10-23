/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/ClientSide/javascript.js to edit this template
 */

var bookform = function(){
	var step = 0;
	var pStep = 0;
	
	this.postForm = function(form, url, callbackFunction){
		if(url == null)
		{
			url = $(form).prop("action");
		}
		
		
		$.ajax({
			"type": "POST",
			"dataType": "html",
			"url": url,
			"data": form.serialize(),
			"success": callbackFunction,
			error: function(xhr, ajaxOptions, thrownError)
			{
				var msg = "<ul class='list-style-circle'><li>" + xhr.status + ": " + thrownError + "</li></ul>";
				$(".errorMessages").html(msg);
				$(".errorMessages").show();
			}
		});
	};

	this.checkAccount = function(){
		
	};
	
	this.signin = function(){
		
	};
	
	this.verifyOTP = function(){
		
	};
	
	this.getTripCategory = function(){
		
	};
	
	this.getBookingType = function(){
		
	};
	
	this.getItinerary = function(){
		
	};
	
	this.goBack = function(currentStep){
		
	};
	
	this.gotoStep = function(step){
		
	};
	
	this.showStep = function(){
		//$()
		
	};
	
	this.hideStep = function(){
		
	};
	
};
