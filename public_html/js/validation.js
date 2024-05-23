 (function( $ ) {
	var formElementsSelectors = ['input','password','select', 'textarea','checkbox'];
	var config ;
	var isFormValid;
	var isValidate = false;
	
	$.fn.validation = function( data ) {

		config = data;
		isFormValid = true;
		
		// Validate user
		if( data && data.validate != undefined )
			isValidate = data.validate;
			
		for( var i=0; i<formElementsSelectors.length; i++ ) {
			$(this).find(formElementsSelectors[i]+"[validation]").bind( "change", onChangeHandler );
			$.each( $(this).find(formElementsSelectors[i]+"[validation]"), function( i, obj ) {
				if( doValidation( obj ) == false ) {
					isFormValid = false;
				}
			});
		}
        isValidate = true;
		// Return to indicate form is valid
		return isFormValid;
	}
	
	$.fn.validateone = function( e ) {
		return doValidation( e );
	}
	
	function onChangeHandler( e ) {
		return doValidation( $(e.target) );
	}
	
	function doValidation( formElement ) {
		
		//console.log( ' :::: ' + isValidate );
        if( isValidate == false )
            return;
		
		$element = $(formElement);
		var validations = $element.attr('validation');
		validations = validations.split('*');
		//$("#error"+$element.attr('id')).empty();
		$element.css('border','');
        $e_rel = $('div[e_rel]').filter('[e_rel="'+$element.attr('id')+'"]').empty();

		var isValid;
		var isElementValid = true;
		for( var i=0; i<validations.length; i++ ) {
			
			var validationProp = validations[i].split('|');
			isValid = true;
			
			switch( validationProp[0] ) {
				
				case "blank":
					if( $element.val() == "" ) {
						isValid = false;
					}
					break;
				
				case "number":
					if( isInteger( $element.val() ) == false ) {
						isValid = false;
					}
					break;
				
				case "length":
					if( $element.val().length < validationProp[2] ) {
						isValid = false;
					}
					break;
				
				case "email":
					if( isValidEmail( $element.val() ) == false  ){
						isValid = false;
					}
					break;
				
				case "match":
					if( $element.val() != $("#"+validationProp[2] ).val() ) {
						isValid = false;
					}					
					break;
				
				case "unmatch":
					if( $element.val() == $("#"+validationProp[2] ).val() ) {
						isValid = false;
					}
					break;

                case "checked":
                    if( $element.is(":checked") == false ) {
                        isValid = false;
                    }
                    break;
					
				case "blankcheck":
                    if(  $element.val() == "" && $("#"+validationProp[2] ).is(":checked") ) {
                        isValid = false;
                    }
                    break;
					
				case "scroll":
					if( $element.val() == "" ) {
						$("html, body").animate({ scrollTop: 320 }, "slow");
						isValid = false;
					}
					break;
					
					
			}
			
			// if not valid break the loop
			if( isValid == false ) {
                $errorMsg = $('<p style="display:none; padding:0 0 0 5px; color:#ff0000;" class="'+config.errorClass+'">'+validationProp[1]+'</p>');
				$element.css('border','1px solid red');
                $errorMsg.appendTo( $e_rel );
                $errorMsg.show();
				$e_rel.show();
				isElementValid = false;
			}
			
		}
		return isElementValid;
		
	}
	
	
	function isInteger(s){
		var i;
		s = s.toString();
		for (i = 0; i < s.length; i++){
			 var c = s.charAt(i);
			 if (isNaN(c)) {
				return false;
			}
		}
		return true;
	}
	
	function isValidEmail( val ) {
		if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(val)) {
			return true;
		}
		return false;
	}
	
 })( jQuery );