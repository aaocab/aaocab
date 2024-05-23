var Trace = {
	handler : null,
	template : '<div id="wraningOverlayDiv" class="traceClass">\
				<div class="traceClassInn"></div>\
				<div id="simplemodal-container" style="position:absolute;top: 0;left: 0px;">\
					<div>\
						<div>\
							<div class="alert-bg">\
								<a href="javascript:void();" class="warningClose closePop" title="Close" style="top: -17px; right: -19px; position: absolute;"><img src="/images/cancle.png"></a>\
								<h1 id="warningTitle"></h1>\
								<p id="warningMsg"></p>\
								<input type="text" class="signupInputult showMe" style="display:none; margin: 0 0 15px 0;" placeholder="Verification Code"/><p style="color:#ff0000; font-size: 12px; display:none;" class="error">Invalid validation code</p>\
								<div id="showfileaddress" style="display:none;">\
								<select class="signupInputult" id="dropDownFile"><option>Electric Bill</option><option>Phone Bill</option><option>Bank Statement</option><option>Water Bill</option><option>Gas Connection</option></select><br/>\
								<input type="file" id="showfile"/><br/>\
								</div>\
								<div id="showfileFein" style="display:none;">\
								<input type="file" id="showfeinfile"/><br/>\
								</div>\
								<div id="showfileSsn" style="display:none;">\
								<input type="file" id="showSsnfile"/><br/>\
								</div>\
								<div class="clr"></div>\
								<div class="alert-buttons">\
									<a href="javascript:void(0)" class="warningbutton warningagree"></a>\
									<a href="javascript:void(0)" class="warningbutton discard"></a>\
									<div class="clr"></div>\
								</div>\
								<div class="clr"></div>\
							</div>\
						</div>\
					</div>\
				</div>\
			</div>',
	show : function ( title, message, buttons, _handler, textbox ) {
		handler = _handler;
		
		if( $("#wraningOverlayDiv").length > 0 )
			$("#wraningOverlayDiv").remove();
		
		$("body").append( $(this.template) );
		
		$("#wraningOverlayDiv").show();
		$("#warningTitle").html( title );
		$("#warningMsg").append( message );
		
		if(textbox==1){
			$(".showMe").show();
		}
		
		if(textbox==2){
			$("#showfileaddress").show();
			$("#showfile").bind('change',showfileChangeHandler);
		}
		
		if(textbox==3){
			$("#showfileFein").show();
			$("#showfeinfile").bind('change',showfeinfileChangeHandler);
		}
		
		if(textbox==4){
			$("#showfileSsn").show();
			$("#showSsnfile").bind('change',showSsnfileChangeHandler);
		}
		
		// plcing the window to the center of the screen
		var wh = $(window).height();
		var ww = $(window).width();
		
		$modelWin = $(".alert-bg");
		var warningWindowW = $modelWin.outerWidth();
		var warningWindowH = $modelWin.outerHeight();
		var x = (ww-warningWindowW)/2;
		var y = (wh-warningWindowH)/2;

		$modelWin.css( 'left' , x+'px' );
		$modelWin.css( 'top' , y+'px' );
		
		if( buttons ) {
			$("#wraningOverlayDiv").find(".warningagree").show().html( buttons.agree );
			$("#wraningOverlayDiv").find(".discard").show().html( buttons.discard );
		} else {
			$("#wraningOverlayDiv").find(".warningagree").hide();
			$("#wraningOverlayDiv").find(".discard").show().html( 'OK' );
		}
		$("#wraningOverlayDiv").find(".warningClose").bind( "click", this.closeClickHandler );
		if(textbox==1){
			$("#wraningOverlayDiv").find(".warningbutton").bind( "click", this. agreeClickHandlers );
			$("#wraningOverlayDiv").find(".discard").bind( "click", this. closeDivHandler );
		}else if(textbox==2 || textbox==3 || textbox==4){
			$("#wraningOverlayDiv").find(".warningbutton").bind( "click", this. agreeClickHandler );
			if(textbox==2){
				$("#wraningOverlayDiv").find(".discard").bind( "click", this. closeDivAddressHandler );
			}
			if(textbox==3){
				$("#wraningOverlayDiv").find(".discard").bind( "click", this. closeDivFEINHandler );
			}
			if(textbox==4){
				$("#wraningOverlayDiv").find(".discard").bind( "click", this. closeDivSSNHandler );
			}
		}else{
			$("#wraningOverlayDiv").find(".warningbutton").bind( "click", this. agreeClickHandler );
		}
			
	},
	closeClickHandler : function(e) {
		$("#wraningOverlayDiv").find(".warningClose").unbind( "click", this.closeClickHandler );
		$("#wraningOverlayDiv").hide();
	},
	agreeClickHandler : function(e) {
		$("#wraningOverlayDiv").find(".warningbutton").unbind( "click", this. agreeClickHandler );
		$("#wraningOverlayDiv").hide();
		if( handler )
			handler( $(e.target).text() );
	},
	
	agreeClickHandlers : function(e) {
		if( handler )
			handler( $(e.target).text() );
	},
	
	closeDivHandler : function(){
		$("#wraningOverlayDiv").find(".warningbutton").unbind( "click", this. agreeClickHandlers );
		$("#wraningOverlayDiv").hide();
	},
	
	closeDivAddressHandler : function(){
		$("#choosenfile").val('');
		$("#wraningOverlayDiv").hide();
	},
	
	closeDivFEINHandler : function(){
		$("#choosenFEINfile").val('');
		$("#wraningOverlayDiv").hide();
	},
	
	closeDivSSNHandler : function(){
		$("#choosenSSNfile").val('');
		$("#wraningOverlayDiv").hide();
	}
}

var Utils = {
	unique : function (origArr) {  
		var newArr = [],  
			origLen = origArr.length,  
			found,  
			x, y;  
			  
		for ( x = 0; x < origLen; x++ ) {  
			found = undefined;  
			for ( y = 0; y < newArr.length; y++ ) {  
				if ( origArr[x] === newArr[y] ) {   
				  found = true;  
				  break;  
				}  
			}  
			if ( !found) newArr.push( origArr[x] );      
		}  
	   return newArr;  
	}
}