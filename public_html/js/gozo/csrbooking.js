/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


var Csrbooking = function () {
    var model = {};

    this.getQoute = function () {
        var model = this.model;
        $.ajax({
            "type": "POST",
            "dataType": "json",
            "url": $baseUrl + "/rcsr/booking/getamountbyvehicle",
            data: model,
            success: function (data)
            {
                if (data.success)
                {
                    model = data.data;
                    $(document).trigger("getQoute", [data]);
                }
            },
            "error": function (error) {
                alert(error);
            }
        });
    };

    this.driverCabDetails = function () {
        var model = this.model;
        $.ajax({
            "type": "GET",
            "dataType": "json",
            "url": $baseUrl + "/rcsr/booking/getdrivercabdetails",
            data: model,
            success: function (data)
            {
                if (data.success)
                {
                    model = data.data;
                    $(document).trigger("driverCabDetails", [data]);
                }
            },
            "error": function (error) {
                alert(error);
            }
        });
    };

    this.getCar = function () {
        var model = this.model;
        $.ajax({
            "type": "GET",
            "dataType": "json",
            "url": $baseUrl + "/rcsr/booking/getcarmodel",
            data: model,
            success: function (data)
            {
                if (data.success)
                {
                    model = data.data;
                    $(document).trigger("getCar", [data]);
                }
            },
            "error": function (error) {
                alert(error);
            }
        });
    };

    this.applyPromo = function (callback) {
        var model = this.model;
        $.ajax({
            "type": "GET",
            "dataType": "json",
            "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/promoapply')) ?>",
            "data": {"bkgid": model.bid, "bkghash": model.hash, 'bkg_pcode': model.promoCode, "iscreditapplied": model.isCreditApplied, "creditapplied": model.creditApplied},
            success: function (data)
            {
                eval(callback + '(data)');
            }
        });
    };


    this.removePromo = function (callback) {
        var model = this.model;
        $.ajax({
            "type": "GET",
            "dataType": "json",
            "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/promoremove')) ?>",
            "data": {"bkgid": model.bid, "bkghash": model.hash, "iscreditapplied": model.isCreditApplied, "creditapplied": model.creditApplied},
            success: function (data)
            {
                eval(callback + '(data)');
            }
        });
    };


    this.applyCredits = function (callback) {
        var model = this.model;
        $.ajax({
            "type": "GET",
            "dataType": "json",
            "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/creditapply')) ?>",
            "data": {"bkgid": model.bid, "bkghash": model.hash, 'creditvalamt': model.creditValueAmount},
            success: function (data)
            {
                eval(callback + '(data)');
            }
        });
    };

    this.routeNames = function (callback) {
        var model = this.model;
        $.ajax({
            "type": "GET",
            "dataType": "json",
            "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/getroutename')) ?>",
            "data": {"fcity": model.fromCity, 'tcity': model.toCity},
            success: function (data)
            {
                eval(callback + '(data)');
            }
        });
    };

    this.reconfirmationBooking = function () {
        var model = this.model;
        $.ajax({
            "type": "GET",
            "dataType": "json",
            "url": $baseUrl + "/booking/reconfirmsubmit",
            data: {"bkgId": model.bid, "type": model.type},
            success: function (data)
            {
                eval(callback + '(data)');
            }
        });
    };
    this.codeVerification = function () {
        var model = this.model;
        $.ajax({
            "type": "GET",
            "dataType": "json",
            "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/codeverify')) ?>",
            "data": model,
            success: function (data)
            {
                eval(callback + '(data)');
            }
        });
    };



    this.ratePerKm = function () {
        var model = this.model;
        $.ajax({
            "type": "GET",
            "dataType": "json",
            "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('rcsr/vehicle/getvehicletyperate')) ?>",
            "data": {"bkgid": model.bid, "bkghash": model.hash, 'creditvalamt': model.creditValueAmount},
            success: function (data)
            {
                eval(callback + '(data)');
            }
        });
    };

    this.routeSelection = function () {
        var model = this.model;
        $.ajax({
            "type": "GET",
            "dataType": "json",
            "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('rcsr/city/selectcities')) ?>",
            data: {"rt_id": model.routeId},
            success: function (data)
            {
                eval(callback + '(data)');
            }
        });
    };

    this.routeName = function () {
        var model = this.model;
        $.ajax({
            "type": "GET",
            "dataType": "json",
            "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('rcsr/route/getroutename')) ?>",
            "data": {"bkgid": model.bid, "bkghash": model.hash, 'creditvalamt': model.creditValueAmount},
            success: function (data)
            {
                eval(callback + '(data)');
            }
        });
    };

    this.citiesName = function () {
        var model = this.model;
        $.ajax({
            "type": "GET",
            "dataType": "json",
            "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('rcsr/city/getnames')) ?>",
            data: {"fcity": model.fromCity, "tcity": model.toCity},
            success: function (data)
            {
                eval(callback + '(data)');
            }
        });
    };


    this.corporateCode = function () {
        var model = this.model;
        $.ajax({
            "type": "GET",
            "dataType": "json",
            "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('rcsr/corporate/corporateexist')) ?>",
            data: {"crp_code": model.corporateCode},
            success: function (data)
            {
                eval(callback + '(data)');
            }
        });
    };

    this.routeAdd = function () {
        var model = this.model;
        $.ajax({
            "type": "GET",
            "dataType": "json",
            "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('index/getnearest')) ?>",
            "data": {"scity": model.fromCity, "pscity": model.toCity, "pdate": model.pickupDate, "ptime": model.pickupTime},
            success: function (data)
            {
                eval(callback + '(data)');
            }
        });
    };


    this.carModels = function () {
        var model = this.model;
        $.ajax({
            "type": "GET",
            "dataType": "json",
            "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('rcsr/booking/getcarmodel')) ?>",
            data: {"rt_id": model.routeId},
            success: function (data)
            {
                eval(callback + '(data)');
            }
        });
    };

    this.markComplete = function () {
        var model = this.model;
        $.ajax({
            "type": "GET",
            "dataType": "json",
            "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('rcsr/booking/setcompletebooking')) ?>",
            data: {"bkIds": model.bid},
            success: function (data)
            {
                eval(callback + '(data)');
            }
        });
    };

    this.cityDetails = function () {
        var model = this.model;
        $.ajax({
            "type": "GET",
            "dataType": "json",
            "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('rcsr/city/getcitydetails')) ?>",
            "data": {"Id": model.cityId, 'cityBox': model.cityBox},
            success: function (data)
            {
                eval(callback + '(data)');
            }
        });
    };

    this.validatePromoCode = function () {
        var model = this.model;
        $.ajax({
            "type": "GET",
            "dataType": "json",
            "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('rcsr/promo/validatecode')) ?>",
            data: {"email": model.email, 'code': model.code, 'userid': model.userId, 'bkgid': model.bid},
            success: function (data)
            {
                eval(callback + '(data)');
            }
        });
    };

    this.driverDetails = function () {
        var model = this.model;
        $.ajax({
            "type": "GET",
            "dataType": "json",
            "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('rcsr/booking/getdriverdetails')) ?>",
            data: {"drvid": model.driverId},
            success: function (data)
            {
                eval(callback + '(data)');
            }
        });
    };

    this.nearestCity = function () {
        var model = this.model;
        $.ajax({
            "type": "GET",
            "dataType": "json",
            "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('index/getnearest')) ?>",
            "data": {"source": model.fromCity},
            success: function (data)
            {
                eval(callback + '(data)');
            }
        });
    };
    this.validateForm = function (event) {
        var ck_email = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/;
        var primaryPhone = $('#Booking_bkg_contact_no').val();
        var email = $('#Booking_bkg_user_email').val();
        var triptype = $('#Booking_bkg_trip_type').val();
        var ratepkm = $('#Booking_bkg_rate_per_km').val();
        var bkgtype = $('#Booking_bkg_booking_type').val();
        var select = $("#Booking_bkg_country_code").selectize({});
        var selectizeControl = select[0].selectize;
        var country_code = selectizeControl.getItem(selectizeControl.getValue()).text();
        error = 0;
        $("#errordivmob").text('');
        $("#errordivemail").text('');
        $("#errordivrate").text('');
        $("#errordivreturn").text('');

        if (bkgtype == 2 && $('#Booking_bkg_return_date_date').val() == '') {
            error += 1;
            $("#errordivreturn").text('');
            $("#errordivreturn").text('Please enter Return Date and Time');
        }
//            if (triptype == 2 && (ratepkm == null || ratepkm == ''))
//            {
//                error += 1;
//                $("#errordivrate").text('');
//                $("#errordivrate").text('Please enter Rate per Km');
//            }
        if ((primaryPhone == '' || primaryPhone == null) && (email == '' || email == null))
        {
            error += 1;
            $("#errordivmob").text('');
            $("#errordivemail").text('');
            $("#errordivmob").text('Please enter contact number or email address.');
        } else
        {
            if (primaryPhone != '')
            {
                if (country_code == '' || country_code == null)
                {
                    error += 1;
                    $("#errordivmob").text("Please select country code.");
                } else
                {
                    error += 0;
                    $("#errordivmob").text('');
                    $("#errordivemail").text('');

                }
            } else
            {
                if (email != '')
                {
                    if (!ck_email.test(email))
                    {
                        error += 1;
                        $("#errordivmob").text('');
                        $("#errordivemail").text('');
                        $("#errordivemail").text('Invalid email address');
                    }
                }
            }
        }

        if ($('#Booking_bkg_total_amount').val() <= 0 || $('#Booking_bkg_total_amount').val() == '' || $('#Booking_bkg_total_amount').val() == 'undefined')
        {
            error += 1;
            alert("Total chargeable amount is mandatory");
        }

        if ($('#Booking_bkg_vehicle_type_id').val() <= 0 || $('#Booking_bkg_vehicle_type_id').val() == '' || $('#Booking_bkg_vehicle_type_id').val() == 'undefined' || $('#Booking_bkg_vehicle_type_id').val() == null || $('#Booking_bkg_vehicle_type_id').val() == 'null')
        {
            error += 1;
            alert("Please select vehicle type.");
        }


//            var base_amt=($('#Booking_bkg_base_amount').val()!='')?parseInt($('#Booking_bkg_base_amount').val()):0;
//            var add_chrge=($('#Booking_bkg_additional_charge').val()!='')?parseInt($('#Booking_bkg_additional_charge').val()):0;
//            var discount=($('#Booking_bkg_discount_amount').val()!='')?parseInt($('#Booking_bkg_discount_amount').val()):0;
//            var drv_allowance=($('#Booking_bkg_driver_allowance_amount').val()!='')?parseInt($('#Booking_bkg_driver_allowance_amount').val()):0;
//            var tax=($('#Booking_bkg_service_tax').val()!='')?parseInt($('#Booking_bkg_service_tax').val()):0;
//            var chargeable_amt=($('#Booking_bkg_total_amount').val()!='')?parseInt($('#Booking_bkg_total_amount').val()):0;
//
//            
//           var est_base_amount=chargeable_amt-tax-drv_allowance+discount-add_chrge;
//           if(est_base_amount!=base_amt)
//           {
//               alert("recheck amount calculation");
//               error += 1;
//           }
        var href1 = '<?= Yii::app()->createUrl("rcsr/corporate/corporateexist"); ?>';
        var crp_code = $('#Booking_corporate_code').val();
        if (crp_code != '') {
            $.ajax({url: href1,
                dataType: "json",
                async: false,
                data: {"crp_code": crp_code},
                "success": function (data) {
                    if (!data.success) {
                        error += 1;
                        alert('Invalid Corporate Code.');
                    }

                }
            });
        }

        if (error > 0)
        {
            event.preventDefault();
        }
    };

};