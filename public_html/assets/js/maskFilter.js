class MaskFilter 
{  
	getnameFilter() 
	{
		/***--- Allows only alphanumeric & whitespace input ---***/		
		$(document).on("keypress",".nameFilterMask",function(e) {	
			let inputValue = e.charCode;			
			if(!(inputValue >= 48 && inputValue <= 57) && !(inputValue >= 65 && inputValue <= 90) && !(inputValue >= 97 && inputValue <= 122) && (inputValue != 32 && inputValue != 0))
			{
				e.preventDefault();
			}
		});
	}
  
}