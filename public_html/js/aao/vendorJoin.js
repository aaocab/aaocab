/* 
 * Vendor Join
 */

var VendorJoin = function(){
    this.model = {};
    this.dataModel = {};
    
    this.vendorCarDetails = function()
    {
        var dataModel = this.dataModel;
        
        if ((dataModel.carCount + 1) >= parseInt(dataModel.totalCount) && (dataModel.driverCount + 1) >= parseInt(dataModel.totalCount)) {
            $('#vendorMultiCarNxtDiv').find('button').text('Finish');
        }
        if (dataModel.vndCarModel == '') {
            dataModel.vndCarModel = $('#carmodel').val();
        } else {
            dataModel.vndCarModel = dataModel.vndCarModel + ',' + $('#carmodel').val();
        }
        if (dataModel.vndCarYear == '') {
            dataModel.vndCarYear = $('#caryear').val();
        } else {
            dataModel.vndCarYear = dataModel.vndCarYear + ',' + $('#caryear').val();
        }
        if (dataModel.vndCarNumber == '') {
            dataModel.vndCarNumber = $('#carnumber').val();
        } else {
            dataModel.vndCarNumber = dataModel.vndCarNumber + ',' + $('#carnumber').val();
        }
        if (dataModel.vndDriverName == '') {
            dataModel.vndDriverName = $('#drivername').val();
        } else {
            dataModel.vndDriverName = dataModel.vndDriverName + ',' + $('#drivername').val();
        }
        if (dataModel.vndDriverLicence == '') {
            dataModel.vndDriverLicence = $('#driverlicense').val();
        } else {
            dataModel.vndDriverLicence = dataModel.vndDriverLicence + ',' + $('#driverlicense').val();
        } 
        this.setVendorCarDetails();
        return dataModel;
    };
    
    this.setVendorCarDetails = function(){
        var model = this.model;
        var dataModel = this.dataModel;
        $('#carmodel').parent().find('.help-block').remove();
        $('#carmodel').parent().find('.select2-container a').css('border-color', '#999');
        $('#caryear,#carnumber,#drivername,#driverlicense').parent().removeClass('has-error');
        $('#caryear,#carnumber,#drivername,#driverlicense').parent().find('.help-block').css('display', 'none');
        $('#caryear,#carnumber,#drivername,#driverlicense').parent().find('.help-block').text('');
        $('#' + model.carModelId1).val(dataModel.vndCarModel);
        $('#' + model.carYearId1).val(dataModel.vndCarYear);
        $('#' + model.carNumberId1).val(dataModel.vndCarNumber);
        $('#' + model.driverName1).val(dataModel.vndDriverName);
        $('#' + model.driverLicence1).val(dataModel.vndDriverLicence);
        if (dataModel.carCount === parseInt(dataModel.totalCount) && dataModel.driverCount === parseInt(dataModel.totalCount)) {
            $('#vendorForm').submit();
        } else {
            dataModel.carCount += 1;
            $('#carmodel').val('');
            $('#carmodel').parent().find('a .select2-chosen').text('Select Cab Type');
            $('#caryear').val('');
            $('#carnumber').val('');
            $('#carcount').text(dataModel.carCount);
            dataModel.driverCount += 1;
            $('#drivername').val('');
            $('#driverlicense').val('');
            $('#driverCount').text(dataModel.carCount);
        }
    };
    
    this.validation = function()
    {
        var model = this.model;
        var self  = this;
        
        if (isNaN($('#' + model.phoneNumberId).val()) == false) 
        {
            jQuery("#Vendors_vnd_city").next().next().hide();
            jQuery('#Vendors_phone_no_em_').hide();
            jQuery.ajax
            ({
                type: 'GET', 
                url: model.vendorJoinValidationUrl,
                data: 
                {
                    'FName': $('#Vendors_first_name').val(), 
                    'LName': $('#Vendors_last_name').val(), 
                    'CompanyName': "", 'Phone': $('#fullContactNumber3').val(), 
                    'Email': $('#ContactEmail_eml_email_address').val(), 
                    'City': $('#Vendors_vnd_city').val()},
                success: function (data) 
                {
                    if (data === '[]') 
                    {						
                        $("#get_msg12").click();
                    } 
                    else 
                    {                        
                        var data1 = JSON.parse(data);
                        self.setErrorDetails(data1);				
                    }
                },
                error: function () 
                {
                    alert('error');
                }
            });
        }
    };
    
    this.setErrorDetails = function(data1){
        if ($('#Vendors_first_name').val() == '')
        {
            $('#Vendors_first_name').parent().addClass('has-error');
            $('#Vendors_first_name').parent().find('.help-block').css('display', 'block');
            $('#Vendors_first_name').parent().find('.help-block').text(data1.Vendors_first_name[0]);
        }
        if ($('#Vendors_last_name').val() == '')
        {
            $('#Vendors_last_name').parent().addClass('has-error');
            $('#Vendors_last_name').parent().find('.help-block').css('display', 'block');
            $('#Vendors_last_name').parent().find('.help-block').text(data1.Vendors_last_name[0]);
        }
        
        if ((data1.hasOwnProperty('ContactEmail_eml_email_address') && data1.ContactEmail_eml_email_address[0] == 1) || (data1.hasOwnProperty('ContactPhone_phn_phone_no') && data1.ContactPhone_phn_phone_no[0] == 1)) {
                $("#VendorOuterDiv").show();
                $("#VendorOuterDivText").html("<b>This Contact Information is already exist.Please check</b>"); 
                return false;
        }else{
                $("#VendorOuterDiv").hide();
                $("#VendorOuterDivText").html('');
	}
        if ($('#fullContactNumber3').val() == '' || data1.hasOwnProperty('ContactPhone_phn_phone_no'))
        {
            $('#Vendors_phone_no_em_').addClass('has-error');
            $('#Vendors_phone_no_em_').css('display', 'block');
            $('#Vendors_phone_no_em_').text(data1.ContactPhone_phn_phone_no[0]);
        }
        if ($('#ContactEmail_eml_email_address').val() == '' || data1.hasOwnProperty('ContactEmail_eml_email_address'))
        {
            $('#ContactEmail_eml_email_address').parent().addClass('has-error');
            $('#ContactEmail_eml_email_address').parent().find('.help-block').css('display', 'block');
            $('#ContactEmail_eml_email_address').parent().find('.help-block').text(data1.ContactEmail_eml_email_address[0]);
        }
        if ($('#Vendors_vnd_city').val() == '')
        {
            if ($('#Vendors_vnd_city').parent().hasClass('has-error') == false)
            {
                $('#Vendors_vnd_city').parent().append('<div class="help-block error"></div>');
            }
            $('#Vendors_vnd_city').parent().addClass('has-error');
            $('#Vendors_vnd_city').parent().find('.help-block').css('display', 'block');
            $('#Vendors_vnd_city').parent().find('.help-block').text(data1.Vendors_vnd_city[0]);
        }
    };
    
    this.validationDetails =  function(){
      
        var model = this.model;
        var dataModel = this.dataModel;
        var self = this;
        
        jQuery.ajax({type: 'GET', url: model.vendorJoinValidationDetailsUrl,
            data: {'carModel': dataModel.carModel, 'carYear': dataModel.carYear, 'carNumber': dataModel.carNumber, 'driverName': dataModel.driverName, 'driverLicence': dataModel.driverLicence, 'is_dco': dataModel.isDco},
            success: function (data) {
                if (data === "null" || data === '') {
                    if ($('#' + model.carOwnId).val() == '1' && $('#' + model.isDcoId).val() == '1') {
                        self.venderinfo();
                    } else {
                        self.vendorCarDetails();
                    }
                } else {
                    var data1 = JSON.parse(data);
                    var vhcModel = data1.vehicle_model;
                    var vhcYear = data1.vehicle_year;
                    if ($('#' + model.carOwnId).val() == '1' && $('#' + model.isDcoId).val() == '1')
                    {
                        self.setCarDetailsErrorsForDCO(data1);
                    } else {
                        self.setCarDetailsErrorsForNonDCO(data1);
                    }
                    return false;
                }
                console.log(data);
            },
            error: function () {
                alert('error');
            }
        });
        return dataModel;
    };
    
    this.setCarDetailsErrorsForDCO =  function(data1){
        var model = this.model;
        var self = this;
        if ($('#' + model.carModelId).val() == '' || data1.hasOwnProperty('Vendors_vnd_car_model'))
        {
            $('#' + model.carModelId).parent().append('<div class="help-block error"></div>');
            $('#' + model.carModelId).parent().addClass('has-error');
            $('#' + model.carModelId).parent().find('.select2-container a').css('border-color', '#a94442');
            $('#' + model.carModelId).parent().find('.help-block').css('display', 'block');
            $('#' + model.carModelId).parent().find('.help-block').text(data1.Vendors_vnd_car_model[0]);
        }
        if ($('#' + model.carYearId).val() == '' || data1.hasOwnProperty('Vendors_vnd_car_year'))
        {
            $('#' + model.carYearId).parent().addClass('has-error');
            $('#' + model.carYearId).parent().find('.help-block').css('display', 'block');
            $('#' + model.carYearId).parent().find('.help-block').text(data1.Vendors_vnd_car_year[0]);
        }
        if ($('#' + model.carNumberId).val() == '' || data1.hasOwnProperty('Vendors_vnd_car_number'))
        {
            self.carNumberValidationMobile(data1);
        }
        if ($('#' + model.driverName).val() == '' || data1.hasOwnProperty('Vendors_vnd_driver_name'))
        {
            $('#' + model.driverName).parent().addClass('has-error');
            $('#' + model.driverName).parent().find('.help-block').css('display', 'block');
            $('#' + model.driverName).parent().find('.help-block').text(data1.Vendors_vnd_driver_name[0]);
        }
        if ($('#' + model.driverLicence).val() == '' || data1.hasOwnProperty('Vendors_vnd_driver_license'))
        {
            $('#' + model.driverLicence).parent().addClass('has-error');
            $('#' + model.driverLicence).parent().find('.help-block').css('display', 'block');
            $('#' + model.driverLicence).parent().find('.help-block').text(data1.Vendors_vnd_driver_license[0]);
        }
    };
    
    this.setCarDetailsErrorsForNonDCO =  function(data1){
        var model = this.model;
        var self = this;
        if ($('#carmodel').val() == '' || data1.hasOwnProperty('Vendors_vnd_car_model'))
        {
            $('#carmodel').parent().append('<div class="help-block error"></div>');
            $('#carmodel').parent().addClass('has-error');
            $('#carmodel').parent().find('.select2-container a').css('border-color', '#a94442');
            $('#carmodel').parent().find('.help-block').css('display', 'block');
            $('#carmodel').parent().find('.help-block').text(data1.Vendors_vnd_car_model[0]);
        }
        if ($('#caryear').val() == '' || data1.hasOwnProperty('Vendors_vnd_car_year'))
        {
            $('#caryear').parent().addClass('has-error');
            $('#caryear').parent().find('.help-block').css('display', 'block');
            $('#caryear').parent().find('.help-block').text(data1.Vendors_vnd_car_year[0]);
        }
        if ($('#carnumber').val() == '' || data1.hasOwnProperty('Vendors_vnd_car_number'))
        {
            self.carNumberValidationMobile(data1);
        }
        if ($('#drivername').val() == '' || data1.hasOwnProperty('Vendors_vnd_driver_name'))
        {
            $('#drivername').parent().addClass('has-error');
            $('#drivername').parent().find('.help-block').css('display', 'block');
            $('#drivername').parent().find('.help-block').text(data1.Vendors_vnd_driver_name[0]);
        }
        if ($('#driverlicense').val() == '' || data1.hasOwnProperty('Vendors_vnd_driver_license'))
        {
            $('#driverlicense').parent().addClass('has-error');
            $('#driverlicense').parent().find('.help-block').css('display', 'block');
            $('#driverlicense').parent().find('.help-block').text(data1.Vendors_vnd_driver_license[0]);
        }
    };
    
    this.carNumberValidationMobile = function(data1){
        
        var model = this.model;
        var self =  this;
        jQuery.ajax({type: 'get', url: model.vendorJoinValidationDetailsUrl, success: function (data)
            {
                var msg2 = "Vehicle Model:" + data1.vehicle_model + ", Vehicle Year:" + data1.vehicle_year + +".\n"+ " This cab already exist in our system. To Continue please confirm..";
                var x = confirm(msg2);
                if (x) {
                        self.venderinfo();
                } else {
                        $('#' + model.carNumberId).parent().addClass('has-error');
                        $('#' + model.carNumberId).parent().find('.help-block').css('display', 'block');
                        $('#' + model.carNumberId).parent().find('.help-block').text(data1.Vendors_vnd_car_number[0]);
                }								
            }
        });
    };
    
    this.venderinfo = function() {
         var model = this.model;
        $('#' + model.carModelId1).val($('#' + model.carModelId).val());
        $('#' + model.carYearId1).val($('#' + model.carYearId).val());
        $('#' + model.carNumberId1).val($('#' + model.carNumberId).val());
        $('#' + model.driverName1).val($('#' + model.driverName).val());
        $('#' + model.driverLicence1).val($('#' + model.driverLicence).val());
        $('#vendorForm').submit();

    };
    
    this.carValidation = function(){
        
        var model = this.model;
        var carnumber = $('#' + model.carNumberId).val();
        var carnumber1 = $('#carnumber').val();
        var result;
        var patt1 = /^[A-Za-z]{2}(?: \s)?(?: \s*)?[0-9]{1,2}(?:\s)?(?:\s*)?(?:[A-Za-z])?(?:[A-Za-z]*)?(?:\s)?(?:\s*)?[0-9]{4}$/;
        if (carnumber != '')
        {
            result = carnumber.match(patt1);
            if (result == null || result == '')
            {
                $('#' + model.carNumberId).parent().addClass('has-error');
                $('#' + model.carNumberId).parent().find('.help-block').css('display', 'block');
                $('#' + model.carNumberId).parent().find('.help-block').text('Please provide valid Car Registration number');
                $('#' + model.carNumberId).val('');

            } else
            {
                var a, b, c, d, e;
                a = carnumber.substring(0, 2);
                b = carnumber.substring((carnumber.length - 4), carnumber.length);
                c = carnumber.substring(2, (carnumber.length - 4));
                c = c.trim();
                if (c.length > 2)
                {
                    d = c.substring(0, 2);
                    e = c.substring(2, c.length);
                    e = e.trim();
                    carnumber = a + " " + d + " " + e + " " + b;
                } else
                {
                    carnumber = a + " " + c + " " + b;
                }
                $('#' + model.carNumberId).val(carnumber);
            }
        } else if (carnumber1 != '')
        {
            result = carnumber1.match(patt1);
            if (result == null || result == '')
            {
                $('#carnumber').parent().addClass('has-error');
                $('#carnumber').parent().find('.help-block').css('display', 'block');
                $('#carnumber').parent().find('.help-block').text('Please provide valid Car Registration number');
                $('#carnumber').val('');
            } else
            {
                var a, b, c, d, e;
                a = carnumber.substring(0, 2);
                b = carnumber.substring((carnumber.length - 4), carnumber.length);
                c = carnumber.substring(2, (carnumber.length - 4));
                c = c.trim();
                if (c.length > 2)
                {
                    d = c.substring(0, 2);
                    e = c.substring(2, c.length);
                    e = e.trim();
                    carnumber = a + " " + d + " " + e + " " + b;
                } else
                {
                    carnumber = a + " " + c + " " + b;
                }
                $('#carnumber').val(carnumber1);
            }
        }
        
    };
};
