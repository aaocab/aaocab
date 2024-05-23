/* 
 * BookNow
 */
var BookNow = function ()
{
    this.model = {
        "step": 0,
        "bookingType": 1
    };
    this.data = {};
    this.isMobile = function ()
    {
        return (this.Android() || this.BlackBerry() || this.iOS() || this.Opera() || this.Windows());
    };
    this.Android = function ()
    {
        return navigator.userAgent.match(/Android/i);
    };
    this.BlackBerry = function ()
    {
        return navigator.userAgent.match(/BlackBerry/i);
    };
    this.iOS = function ()
    {
        return navigator.userAgent.match(/iPhone|iPad|iPod/i);
    };
    this.Opera = function ()
    {
        return navigator.userAgent.match(/Opera Mini/i);
    };
    this.Windows = function ()
    {
        return navigator.userAgent.match(/IEMobile/i);
    };
    this.arrSteps = ["TripType", "Route", "Quote","Details", "Info", "Summary"];
    this.arrTripTypes = ["", "One Way Trip", "Round Trip/Multi City", "Round Trip/Multi City", "Airport Transfer", "Package", "Flexxi", "Shuttle Trip", "Custom Trip", "Day Rental", "Day Rental", "Day Rental"];
    this.errorColor = "#a94442";
    this.noError = "1px solid #ccc";

    // Init
    this.init = function ()
    {

    };

    // HideTab
    this.hideTab = function ()
    {
        //$('ul.nav-tabs li.ltab').hide();
        //$('ul.nav-tabs li.ltab').removeClass('active');
    };

    // ShowTab
    this.showTab = function (tabName)
    {
        $('ul.nav-tabs li.ltab a[href="#menu' + tabName + '"]').trigger("click", ['inflow']);
    };

    this.goToPrevTab = function (prevStepId)
    {
        var currStepName = this.arrSteps[parseInt(prevStepId) + 1];
        var prevStepName = this.arrSteps[prevStepId];

        $('#menu' + currStepName).hide();
        $('#menu' + prevStepName).show();
    };

    this.jumpToStep = function (stepId)
    {
        this.arrSteps.forEach(function (stepName, index)
        {
            $('#menu' + stepName).hide();
        });

        var jumpToStepName = this.arrSteps[parseInt(stepId)];
        $('#menu' + jumpToStepName).show();
    };
    
    // ChangeTripType
    this.changeTripType = function (objElement)
    {   
        var objBookNow = this;
        var objForm = $(objElement).closest("form");
        this.model.step = $(objForm).find('input[name="step"]').val();
        this.model.bookingType = $(objElement).val();
        if (this.model.bookingType == 5)
        {
            window.location = '/packages';
        }
        else
        {
            $.ajax({
                type: "POST",
                dataType: "html",
                url: $baseUrl + "/booking/pickup",
                data: $(objForm).serialize(),
                success: function (data1)
                {
                    $("#menuRoute").html("");
                    $("#menuRoute").html(data1);
                    document.getElementById("bookingtime-form").reset();

                    var nextStepId = parseInt(objBookNow.model.step) + 1;
                    var nextStepName = objBookNow.arrSteps[nextStepId];

                    if (!objBookNow.isMobile())
                    {
                        $('.clickable').removeClass('active');
                        $(objElement).parent().addClass('active');
                        $("#l1").find("span").html(objBookNow.arrTripTypes[objBookNow.model.bookingType]);
                    }

                    objBookNow.showTab(nextStepName);
                    objBookNow.enableTab("Route");
                    $(objForm)[0].reset();
                    if ($('#topRouteDesc').length)
                    {
                        $('#topRouteDesc').html(objBookNow.arrTripTypes[objBookNow.model.bookingType]);
                    }
                },
                error: function (error)
                {
                    console.log(error);
                }
            });
        }
    };
    
    // ChangeTripType
    this.changeTripTypeNew = function (objElement, contenttype)
    {   
        var objBookNow = this;
        var objForm = $(objElement).closest("form");
        objElement.contenttype;
        this.model.step = $(objForm).find('input[name="step"]').val();
        this.model.bookingType = $(objElement).val();
        if (this.model.bookingType == 5)
        {
            window.location = '/packages';
        }
        else
        {
            $.ajax({
                type: "POST",
                dataType: "html",
                url: $baseUrl + "/booking/pickupnew",
                data: $(objForm).serialize(),
                success: function (data1)
                {   
                    $("#menuRoute").html("");
                    $("#menuRoute").html(data1);
                    document.getElementById("bookingtime-form").reset();
                    $("#menuTripType").removeClass('active');
                    $(objForm)[0].reset();
                },
                error: function (error)
                {
                    console.log(error);
                }
            });
        }
    };

    // PopulateSource
    this.populateSource = function (obj, cityId, btype = '')
    {
        $loadCityId = cityId;
        $bkType = btype;
        obj.load(function (callback)
        {
            var obj = this;
            if ($sourceList == null && $bkType != 9 && $bkType != 10 && $bkType != 11)
            {
                xhr = $.ajax({
                    url: $baseUrl + "/lookup/citylist1",
                    dataType: 'json',
                    data: {
                        city: cityId
                    },
                    //  async: false,
                    success: function (results)
                    {
                        $sourceList = results;
                        obj.enable();
                        callback($sourceList);
                        obj.setValue($loadCityId);
                    },
                    error: function ()
                    {
                        callback();
                    }
                });
            }
            else if ($bkType == 9 || $bkType == 10 || $bkType == 11)
            {
                xhr = $.ajax({
                    url: $baseUrl + "/lookup/dayrentalcitylist",
                    dataType: 'json',
                    data: {
                        city: cityId
                    },
                    //  async: false,
                    success: function (results)
                    {
                        $sourceList = results;
                        obj.enable();
                        callback($sourceList);
                        obj.setValue($loadCityId);
                    },
                    error: function ()
                    {
                        callback();
                    }
                });
            }
            else
            {
                obj.enable();
                callback($sourceList);
                obj.setValue($loadCityId);
            }
        });
    };


    // LoadSource
    this.loadSource = function (query, callback)
    {
        $.ajax({
            url: $baseUrl + '/lookup/citylist1/?q=' + encodeURIComponent(query),
            type: 'GET',
            dataType: 'json',
            error: function ()
            {
                callback();
            },
            success: function (res)
            {
                callback(res);
            }
        });
    };

    // ChangeDestination
    this.changeDestination = function (value, obj, cityId)
    {
        $loadCityId = cityId;
        if (!value.length)
            return;

        obj.disable();
        obj.clearOptions();
        obj.load(function (callback)
        {
            xhr = $.ajax({
                url: $baseUrl + '/lookup/nearestcitylist/source/' + value,
                dataType: 'json',
                success: function (results)
                {
                    obj.enable();
                    callback(results);
                    obj.setValue($loadCityId);
                },
                error: function ()
                {
                    callback();
                }
            });
        });
    };

    // Add Route
    this.addMoreRoute = function ()
    {
        var objBookNow = this;
        var elems = $("SELECT.ctyDrop");
        var len = elems.length;
        var btype = 3; //Multi City Trip
        count = len;

        var scity = $(elems[len - 1]).val();
        var pscity = $($("SELECT.ctyPickup")[len - 1]).val();
        if (len > 1)
        {
            var pscity = $($("SELECT.ctyDrop")[len - 2]).val();
        }
        var pdate = $($("INPUT.datePickup")[len - 1]).val();
        var ptime = $($("INPUT.timePickup")[len - 1]).val();
        messages = {};
        if (pdate == "")
        {
            messages["BookingRoute_brt_pickup_date_date"] = [];
            messages["BookingRoute_brt_pickup_date_date"].push("Please enter pickup date");
        }
        if (pscity == '')
        {
            messages["BookingRoute_brt_from_city_id"] = [];
            messages["BookingRoute_brt_from_city_id"].push("Please select source city");
        }
        if (scity == '')
        {
            messages["BookingRoute_brt_to_city_id"] = [];
            messages["BookingRoute_brt_to_city_id"].push("Please select your destination");
        }
        if (JSON.stringify(messages) != '{}')
        {
            var result = messages;
            var msg = "";
            if (objBookNow.isMobile() || screen.width < 900)
            {
                for (k in result)
                {
                    msg += result[k] + '<br/>';
                }
                objBookNow.showErrorMsg(msg);
                return false;

            }
            else
            {
                for (k in result)
                {
                    msg += result[k] + '\n';
                }
                alert(msg);
                return false;

            }

        }


        $.ajax({
            "type": "POST",
            "dataType": "html",
            "url": $baseUrl + "/booking/addroute",
            "data": {"scity": scity, "pscity": pscity, "pdate": pdate, "ptime": ptime, "index": count, "btype": btype, "YII_CSRF_TOKEN": bkCSRFToken},
            "async": true,
            "success": function (data1)
            {
                $('#fieldBefore').show();
                $("SELECT.ctyPickup").attr('readonly', true);
                $("SELECT.ctyDrop").attr('readonly', true);
                $("INPUT.datePickup").attr('readonly', true);
                $("INPUT.timePickup").attr('readonly', true);
                if (!objBookNow.isMobile())
                {
                    $("INPUT.datePickup").datepicker("remove");
                }
                $("INPUT.timePickup").next("span").hide();
                $('#insertBefore').before(data1);
                $("SELECT.ctyPickup").attr('readonly', true);

                objBookNow.disableRows();
                objBookNow.changeDestination(scity, $dest_city, null);
            }
        });
    };

    this.addRoute = function (form)
    {
        let objBookNow = this;
        $.ajax({
            "type": "POST",
            "dataType": "html",
            "url": "/booking/Addmoreroute",
            "data": form.serialize(),
            "beforeSend": function ()
            {
                ajaxindicatorstart("");
            },
            "complete": function ()
            {
                ajaxindicatorstop();
            },
            "success": function (data2)
            {   
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
                {    
                    objBookNow.clearError(form);
                    $('#fieldBefore').show();
                    $("SELECT.ctyPickup").attr('readonly', true);
                    $("SELECT.ctyDrop").attr('readonly', true);
                    $("INPUT.datePickup").attr('readonly', true);
                    $("INPUT.timePickup").attr('readonly', true);
                    if (!objBookNow.isMobile())
                    {
                        $("INPUT.datePickup").datepicker("remove");
                    }
                    $("INPUT.timePickup").next("span").hide();
                    $(".estarvtime").removeClass('hide');
                    $(".estarvtime").show();
                    $('#insertBefore').before(data2);
                    $("SELECT.ctyPickup").attr('readonly', true);

                    objBookNow.disableRows();
                    return;
                }

                var errors = data.errors;
                settings = form.data('settings');
                $.each(settings.attributes, function (i)
                {
                    $.fn.yiiactiveform.updateInput(settings.attributes[i], errors, form);
                });
                $.fn.yiiactiveform.updateSummary(form, errors);
                messages = errors;
                content = '';
                var summaryAttributes = [];
                for (var i in settings.attributes)
                {
                    if (settings.attributes[i].summary)
                    {

                        summaryAttributes.push(settings.attributes[i].id);
                    }
                }
                objBookNow.displayError(form, messages);

            },
            error: function (xhr, ajaxOptions, thrownError)
            {
                alert(thrownError);
                alert(xhr.status);
            }
        });
    };
    
    this.addRouteNew = function (form)
    {   //debugger;
        let objBookNow = this;
        let bkgType = form[0][1].value;
        $.ajax({
            "type": "POST",
            "dataType": "html",
            "url": "/booking/addmoreitinerary",
            "data": form.serialize(),
            "beforeSend": function ()
            {
                ajaxindicatorstart("");
            },
            "complete": function ()
            {
                ajaxindicatorstop();
            },
            "success": function (data2)
            {   
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
                {  //  debugger;
                    //objBookNow.clearError(form);
                    $('#fieldBefore').show();
                    $("SELECT.ctyPickup").attr('readonly', true);
                    $("SELECT.ctyDrop").attr('readonly', true);
                    $("INPUT.datePickup").attr('readonly', true);
                    $("INPUT.timePickup").attr('readonly', true);
                    if (!objBookNow.isMobile())
                    {
                        $("INPUT.datePickup").datepicker("remove");
                    }
                    $("INPUT.timePickup").next("span").hide();
                    $(".estarvtime").removeClass('hide');
                    $(".estarvtime").show();
                    $('#insertBefore').before(data2);
                    $("SELECT.ctyPickup").attr('readonly', true);

                    objBookNow.disableRows();
                    if(bkgType == 3)
                    {
                       $.ajax({
                            "type": "POST",
                            "dataType": "html",
                            "url": "/booking/showmoreitinerary",
                            "data": form.serialize(),
                            success: function (data) {
                                $('.insertmore').html(data);
                            }
                        });
                    }
                    return;
                }

                var errors = data.errors;
                settings = form.data('settings');
                $.each(settings.attributes, function (i)
                {
                    $.fn.yiiactiveform.updateInput(settings.attributes[i], errors, form);
                });
                $.fn.yiiactiveform.updateSummary(form, errors);
                messages = errors;
                content = '';
                var summaryAttributes = [];
                for (var i in settings.attributes)
                {
                    if (settings.attributes[i].summary)
                    {

                        summaryAttributes.push(settings.attributes[i].id);
                    }
                }
                objBookNow.displayError(form, messages);

            },
            error: function (xhr, ajaxOptions, thrownError)
            {
                alert(thrownError);
                alert(xhr.status);
            }
        });
    };

    // Disable Rows
    this.disableRows = function ()
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
    this.disableRow = function (i)
    {
        var objBookNow = this;
        $("SELECT.ctyDrop")[i].selectize.lock();
        $($("INPUT.datePickup")[i]).attr('readonly', true);
        $($("INPUT.timePickup")[i]).attr('readonly', true);
        if (!objBookNow.isMobile())
        {
            $($("INPUT.datePickup")[i]).datepicker("remove");
        }
        $($("INPUT.timePickup")[i]).next("span").hide();
    };

    // Remove Route
    this.removeRoute = function ()
    {
        var elems = $("SELECT.ctyDrop");
        var len = elems.length;
        $($(".clsRoute")[len - 1]).remove();
        this.enableRows();
    };

    this.disableTab = function (tab)
    {
        $(".nav-tabs .l" + tab + "").addClass('disabled');
    };

    this.enableTab = function (tab)
    {
        $(".nav-tabs .l" + tab + "").removeClass('disabled');
    };

    this.disableTabs = function (tab)
    {
        var objBookNow = this;
        switch (tab)
        {
            case "Route":
                objBookNow.disableTab("Route");
            case "Quote":
                objBookNow.disableTab("Quote");
            case "Info":
                objBookNow.disableTab("Info");
            case "Summary":
                objBookNow.disableTab("Summary");
            default:
                break;
        }

    };

    this.enableTabs = function (tab)
    {
        var objBookNow = this;
        switch (tab)
        {
            case "Summary":
                objBookNow.enableTab("Info");
            case "Info":
                objBookNow.enableTab("Quote");
            case "Quote":
                objBookNow.enableTab("Route");
            case "Route":
                objBookNow.enableTab("TripType");
            default:
                break;
        }
    };

    this.booknowReady = function ()
    {
        var objBookNow = this;
        if ($("#book_Step").val() == 0)
        {
            objBookNow.disableTab('Route');
            $("#book_Step").val("1");
        }
        objBookNow.disableTab('Quote');
        objBookNow.disableTab('Info');
        objBookNow.disableTab('Summary');
    };

    this.checkTabs = function ()
    {
        $('.utab').bind('click', function (e, arg1)
        {
            if (arg1 === undefined)
            {
                var str1 = $(this).attr("class");
                var str2 = "disabled";
                if (str1.indexOf(str2) != -1)
                {
                    e.preventDefault();
                    return false;
                }
            }
        });
    };

    this.enableRow = function (i)
    {
        $("SELECT.ctyDrop")[i].selectize.unlock();
        $($("INPUT.datePickup")[i]).attr('readonly', false);
        $($("INPUT.timePickup")[i]).attr('readonly', false);
        $($("INPUT.datePickup")[i]).datepicker(
                {'autoclose': true, 'startDate': $($("INPUT.datePickup")[i]).attr("min"), 'format': 'dd/mm/yyyy', 'language': 'en'}
        );
        $($("INPUT.timePickup")[i]).next("span").show();
    };

    this.enableRows = function ()
    {
        var objBookNow = this;
        var elems = $("SELECT.ctyDrop");
        var len = elems.length;
        if (len > 1)
        {
            objBookNow.enableRow(len - 1);
            $("SELECT.ctyPickup")[len - 1].selectize.lock();
        }
        else
        {
            $("SELECT.ctyPickup")[len - 1].selectize.unlock();
            $("SELECT.ctyDrop")[len - 1].selectize.unlock();
            $("INPUT.datePickup").attr('readonly', false);
            $("INPUT.timePickup").attr('readonly', false);
            $("INPUT.timePickup").next("span").show();
            var min = new Date($("INPUT.datePickup").attr('min'));
            $("INPUT.datePickup").datepicker(
                    {'autoclose': true, 'startDate': min, 'format': 'dd/mm/yyyy', 'language': 'en'}
            );
            $('#fieldBefore').hide();
            return false;
        }

    };

    this.bkRouteReady = function ()
    {
        var objBookNow = this;
        trackPage("/booking/pickup");
        $('#fieldBefore').click(function ()
        {
            var elems = $("SELECT.ctyDrop");
            var len = elems.length;
            $($(".clsRoute")[len - 1]).remove();
            objBookNow.enableRows();
        });
    };
    this.populateDatarut = function (url, count)
    {
        $scity = $($("INPUT.ctyPickup")[count - 1]).val();
        $tcity = $($("INPUT.ctyDrop")[count - 1]);
        $tcity.select2('val', '').trigger("change");
        if ($scity !== "")
        {
            $.ajax({
                "type": "GET",
                "dataType": "json",
                "url": url,
                "data": {"source": $scity},
                "async": false,
                "success": function (data1)
                {
                    $data2 = data1;
                    var placeholder = $tcity.attr('placeholder');
                    $tcity.select2({data: $data2, placeholder: placeholder, formatNoMatches: function (term)
                        {
                            return "Can't find the Destination?<br>Let us help you.<br><i>Call now</i> (+91) 90518-77-000";
                        }});
                }
            });
        }
    };

    this.resetTransferSelects = function (count)
    {
        $($("INPUT.ctyPickup")[count - 1]).select2('val', '').trigger("change");
        $($("INPUT.ctyDrop")[count - 1]).select2('val', '').trigger("change");
        $($("INPUT.ctyPickup")[count - 1]).val('').change();
        $($("INPUT.ctyDrop")[count - 1]).val('').change();

        $($("INPUT.ctyPickup")[count - 1]).select2({'data': [], formatNoMatches: function (term)
            {
                return "Please, first choose your destination airport.<br><i>If you need any help, please call now</i> (+91) 90518-77-000";
            }}, null, false);
        $($("INPUT.ctyDrop")[count - 1]).select2({'data': [], formatNoMatches: function (term)
            {
                return "Please, first choose your pickup airport.<br><i>If you need any help, please call now</i> (+91) 90518-77-000";
            }}, null, false);
    };

    this.transferOpt1 = function ()
    {
        var objBookNow = this;
        var data = this.data;
        var count = data.count;
        var pTrcity = $($("INPUT.ctyPickup")[count - 1]).val();
        var dTrcity = $($("INPUT.ctyDrop")[count - 1]).val();
        if (pTrcity > 0)
        {
            setTimeout(function ()
            {
                objBookNow.resetTransferSelects(count);
                objBookNow.populateDataTrP();
                setTimeout(function ()
                {
                    $($("INPUT.ctyPickup")[count - 1]).val(pTrcity).trigger('change');
                    if (dTrcity > 0)
                    {
                        $($("INPUT.ctyDrop")[count - 1]).val(dTrcity).trigger('change');
                    }

                }, 400);

            }, 400);

        }
        else
        {
            setTimeout(function ()
            {
                objBookNow.resetTransferSelects(count);
                objBookNow.populateDataTrP();
                $("#dlabel11").text('Pickup Airport');
            }, 300);
        }
    };

    this.transferOpt2 = function ()
    {
        var objBookNow = this;
        var data = this.data;
        var count = data.count;
        if ($($("INPUT.ctyDrop")[count - 1]).val() != '' || data.toCityId != '')
        {
            //console.log('FF == ', $($("INPUT.ctyDrop")[count - 1]).val());
            var toCity = data.toCityId;
            var fromCity = $($("INPUT.ctyPickup")[count - 1]).val();
            objBookNow.changeLabelText('2');
            setTimeout(function ()
            {
                objBookNow.resetTransferSelects(count);
                objBookNow.populateDataTrD();
                setTimeout(function ()
                {
                    $($("INPUT.ctyDrop")[count - 1]).val(toCity).trigger('change');
                    if (fromCity != '')
                    {
                        $($("INPUT.ctyPickup")[count - 1]).val(fromCity).trigger('change');
                    }
                }, 400);
            }, 400);
        }
        else
        {
            setTimeout(function ()
            {
                objBookNow.resetTransferSelects(count);
                objBookNow.populateDataTrD();
            }, 300);
        }
    };

    this.populateDataTrP = function ()
    {
        var objBookNow = this;
        var data = this.data;
        var count = data.count;
        $scity = $($("INPUT.ctyPickup")[count - 1]);
        $tcity = $($("INPUT.ctyDrop")[count - 1]);

        $.ajax({
            "type": "GET",
            "dataType": "json",
            "url": $baseUrl + "/index/getairportcities",
            "success": function (data1)
            {
                $data2 = data1;
                var placeholder = $scity.attr('placeholder');
                $scity.select2({data: $data2, placeholder: placeholder, formatNoMatches: function (term)
                    {
                        return "Can't find the source?<br>Let us help you.<br><i>Call now</i> (+91) 90518-77-000";
                    }}).on('change', function (e)
                {
                    objBookNow.populateDataTrOthersF();
                });

            }
        });
    };

    this.populateDataTrD = function ()
    {
        var objBookNow = this;
        var data = this.data;
        var count = data.count;

        $scity = $($("INPUT.ctyPickup")[count - 1]);
        $tcity = $($("INPUT.ctyDrop")[count - 1]);

        $.ajax({
            "type": "GET",
            "dataType": "json",
            "url": $baseUrl + "/index/getairportcities",
            "success": function (data1)
            {
                $data2 = data1;
                var placeholder = $tcity.attr('placeholder');
                $tcity.select2({data: $data2,
                    placeholder: placeholder,
                    formatNoMatches: function (term)
                    {
                        return "Can't find the Destination?<br>Let us help you.<br><i>Call now</i> (+91) 90518-77-000";
                    }}).on('change', function (e)
                {
                    objBookNow.populateDataTrOthersT();
                });
            }
        });
    };

    this.populateDataTrOthersF = function ()
    {
        var data = this.data;
        var count = data.count;
        var $scityVal = $($("INPUT.ctyPickup")[count - 1]).val();
        var $fcityVal = $(data.fromCityId);
        var $tcity = $($("INPUT.ctyDrop")[count - 1]);
        if (($scityVal > 0 || $fcityVal > 0) && $('#' + data.transferTypeOne).is(':checked'))
        {
            $scityVal = ($scityVal > 0) ? $scityVal : $fcityVal;
            $.ajax({
                "type": "GET",
                "dataType": "json",
                "url": $baseUrl + "/index/getairportnearest",
                "data": {"source": $scityVal},
                "async": false,
                "success": function (data1)
                {
                    $data2 = data1;
                    var placeholder = $tcity.attr('placeholder');
                    $tcity.select2({data: $data2, placeholder: placeholder, formatNoMatches: function (term)
                        {
                            return "Can't find the Destination?<br>Let us help you.<br><i>Call now</i> (+91) 90518-77-000";
                        }});
                }
            });
        }

    };

    this.populateDataTrOthersT = function ()
    {
        var data = this.data;
        var count = data.count;
        var $scity = $("INPUT.ctyPickup");
        var $dcityVal = $($("INPUT.ctyDrop")[count - 1]).val();
        var $tcityVal = $(data.toCityId);
        if (($tcityVal > 0 || $dcityVal > 0) && $('#' + data.transferTypeTwo).is(':checked'))
        {
            $tcityVal = ($dcityVal > 0) ? $dcityVal : $tcityVal;
            $.ajax({
                "type": "GET",
                "dataType": "json",
                "url": $baseUrl + "/index/getairportnearest",
                "data": {"source": $tcityVal},
                "async": false,
                "success": function (data1)
                {
                    $data2 = data1;
                    var placeholder = $scity.attr('placeholder');
                    $scity.select2({data: $data2, placeholder: placeholder, formatNoMatches: function (term)
                        {
                            return "Can't find the Destination?<br>Let us help you.<br><i>Call now</i> (+91) 90518-77-000";
                        }});
                }
            });
        }
    };

    this.bkRouteNext = function ()
    {
        var objBookNow = this;
        var data = this.data;
        if (data.bookingType == 4)
        {

        }
        else
        {
            $($("INPUT.ctyPickup")[data.count - 1]).change(function ()
            {
                objBookNow.populateDatarut();
            });
        }
    };

    this.bkQuoteReady = function (bkgId, hash)
    {
        $('[data-toggle="popover"]').popover();
        $(".clsBkgID").val(bkgId);
        $(".clsHash").val(hash);
        trackPage("/booking/rates");
    };

    this.validateQuote = function (obj)
    {   
        var objBookNow = this;
        var dataModel = this.data;
        var diff = $('#diff').val();
        var vht = ($(obj).attr('value') != undefined)?$(obj).attr('value'):$(obj).data('value');
        var kmr = $(obj).attr("kmr");
        var booktype = $(obj).attr("booktype");

        var vhtCapacity = $(obj).attr("capacity");
        if (this.isMobile())
        {
            var vht = (obj.value != undefined)?obj.value:$(obj).data('value');
            var kmr = obj.kmr;
            var booktype = obj.booktype;
            var vhtCapacity = obj.capacity;
        }
        if (booktype == 'flexxi' && diff < 8)
        {
            alert('Departure time should be at least 8 hours hence for Flexxi shared booking');
            return false;
        }
        if (vht > 0)
        {
            $('#' + dataModel.extraRate).val(kmr);
            if (booktype == 'flexxi')
            {
                $('#' + dataModel.vehicleTypeId).val(11);

                trackPage(dataModel.flexiUrl);
                boxFlexxi = bootbox.dialog({
                    message: $('#flexxi_rates_' + vht).html() + "<br><div class='panel'><div class='panel panel-body'><div class='col-xs-12'><label align='left'>You will Use :</label><div class='row'><div class='col-xs-12'>\
									<div class='row'>\
									<div class='col-xs-3'><label>No. of seats</label><br><input class='form-control' min=1 id='noofseats' type='number' placeholder='No.of seats' max='" + vhtCapacity + "' required></div>\
									<div class='col-xs-4'><label></label><br><br><b>Allowed only <span id='bagunit'>0</span> Bag Units</b></div>\
									<div class='col-xs-5'>\
									<table>\
									<tr><th style='padding:8px; border: 1px solid black; font-size: 13px'>Type of Bag</th>\
									<th style='padding:8px; border: 1px solid black; font-size: 13px'>Bag Units</th></tr>\
									<tr><td style='padding:8px; border: 1px solid black; font-size: 13px'>1 Backpack</td>\
									<td style='padding:8px; border: 1px solid black; font-size: 13px'>1 Bag Unit</td></tr>\
									<tr><td style='padding:8px; border: 1px solid black; font-size: 13px'>1 Small Bag</td>\
									<td style='padding:8px; border: 1px solid black; font-size: 13px'>2 Bag Units</td></tr>\
									<tr><td style='padding:8px; border: 1px solid black; font-size: 13px'>1 Big Bags</td>\
									<td style='padding:8px; border: 1px solid black; font-size: 13px'>4 Bag Units</td></tr>\
									</table>\
									</div>\
									<div class='col-xs-12 text-center mt20'><button value='" + vht + "'  class='btn btn-info' onclick='flexiShare_promo(this)'>Submit</button></div></div></div></div></div>",
                    title: 'Rate Charts:',
                    size: 'large',
                    onEscape: function ()
                    {
                        boxFlexxi.modal('hide');
                    }
                });

                $("#noofseats").bind("keyup click change", function (e)
                {
                    var seat = $('#noofseats').val();
                    if (seat == 0 || seat == '')
                    {
                        $('#bagunit').text(0);
                    }
                    else if (seat == 1)
                    {
                        $('#bagunit').text(3);
                    }
                    else if (seat == 2)
                    {
                        $('#bagunit').text(5);
                    }
                    else
                    {
                        $('#bagunit').text(6);
                    }
                });
            }
            else
            {
                objBookNow.sendQuoteToInfo(obj);
            }
        }
    };
    
     this.validateQuoteNew = function (obj)
    {   
        var objBookNow = this;
        var dataModel = this.data;
        var diff = $('#diff').val();
        var vht = ($(obj).attr('value') != undefined)?$(obj).attr('value'):$(obj).data('value');
        var kmr = $(obj).attr("kmr");
        var booktype = $(obj).attr("booktype");

        var vhtCapacity = $(obj).attr("capacity");
        if (this.isMobile())
        {
            var vht = (obj.value != undefined)?obj.value:$(obj).data('value');
            var kmr = obj.kmr;
            var booktype = obj.booktype;
            var vhtCapacity = obj.capacity;
        }
        if (booktype == 'flexxi' && diff < 8)
        {
            alert('Departure time should be at least 8 hours hence for Flexxi shared booking');
            return false;
        }
        if (vht > 0)
        {
            $('#' + dataModel.extraRate).val(kmr);
            if (booktype == 'flexxi')
            {
                $('#' + dataModel.vehicleTypeId).val(11);

                trackPage(dataModel.flexiUrl);
                boxFlexxi = bootbox.dialog({
                    message: $('#flexxi_rates_' + vht).html() + "<br><div class='panel'><div class='panel panel-body'><div class='col-xs-12'><label align='left'>You will Use :</label><div class='row'><div class='col-xs-12'>\
									<div class='row'>\
									<div class='col-xs-3'><label>No. of seats</label><br><input class='form-control' min=1 id='noofseats' type='number' placeholder='No.of seats' max='" + vhtCapacity + "' required></div>\
									<div class='col-xs-4'><label></label><br><br><b>Allowed only <span id='bagunit'>0</span> Bag Units</b></div>\
									<div class='col-xs-5'>\
									<table>\
									<tr><th style='padding:8px; border: 1px solid black; font-size: 13px'>Type of Bag</th>\
									<th style='padding:8px; border: 1px solid black; font-size: 13px'>Bag Units</th></tr>\
									<tr><td style='padding:8px; border: 1px solid black; font-size: 13px'>1 Backpack</td>\
									<td style='padding:8px; border: 1px solid black; font-size: 13px'>1 Bag Unit</td></tr>\
									<tr><td style='padding:8px; border: 1px solid black; font-size: 13px'>1 Small Bag</td>\
									<td style='padding:8px; border: 1px solid black; font-size: 13px'>2 Bag Units</td></tr>\
									<tr><td style='padding:8px; border: 1px solid black; font-size: 13px'>1 Big Bags</td>\
									<td style='padding:8px; border: 1px solid black; font-size: 13px'>4 Bag Units</td></tr>\
									</table>\
									</div>\
									<div class='col-xs-12 text-center mt20'><button value='" + vht + "'  class='btn btn-info' onclick='flexiShare_promo(this)'>Submit</button></div></div></div></div></div>",
                    title: 'Rate Charts:',
                    size: 'large',
                    onEscape: function ()
                    {
                        boxFlexxi.modal('hide');
                    }
                });

                $("#noofseats").bind("keyup click change", function (e)
                {
                    var seat = $('#noofseats').val();
                    if (seat == 0 || seat == '')
                    {
                        $('#bagunit').text(0);
                    }
                    else if (seat == 1)
                    {
                        $('#bagunit').text(3);
                    }
                    else if (seat == 2)
                    {
                        $('#bagunit').text(5);
                    }
                    else
                    {
                        $('#bagunit').text(6);
                    }
                });
            }
            else
            {
                objBookNow.sendQuoteToAddress(obj);
            }
        }
    };
    
    this.sendQuoteToAddress = function (obj)
    {   
        var vht = ($(obj).attr('value') != undefined)?$(obj).attr('value'):$(obj).data('value');
        var kms = $(obj).attr('kms');
        var kmr = $(obj).attr('kmr');
        var duration = $(obj).attr('duration');
        if (this.isMobile())
        {
            var vht = (obj.value != undefined)?obj.value:$(obj).data('value');
            var kms = obj.kms;
            var kmr = obj.kmr;
            var duration = obj.duration;
        }
        var objBookNow = this;
        var dataModel = this.data;
        $('#' + dataModel.vehicleTypeId).val(vht);
        $('#' + dataModel.bkgTripDistance).val(kms);
        $('#' + dataModel.bkgTripDuration).val(duration);
        $('#' + dataModel.extraRate).val(kmr);
        var data = $("#cabrate-form1").serialize();
        $.ajax({
            type: 'POST',
            url: $baseUrl + "/booking/address",
            data: data,
            beforeSend: function ()
            {
                ajaxindicatorstart("");
            },
            complete: function ()
            {
                ajaxindicatorstop();

            },
            success: function (data)
            {   
                objBookNow.enableTab("Quote");
                $("#menuAddress").html(data);
                if (objBookNow.isMobile() || screen.width < 900)
                {
                    $("html,body").animate({scrollTop: 0}, "slow");
                    $("#menuDetails").hide();
                }
                else
                {
                    $("#menuDetails").hide();
                    $("#menuAddress").removeClass('tab-pane')
                 }
                trackPage("/booking/address");
            },
            error: function (data)
            {
                alert("Error occured.please try again");
                alert(data);
            },
            dataType: 'html'
        });
    };

    this.sendQuoteToInfo = function (obj)
    {
        
        var vht = ($(obj).attr('value') != undefined)?$(obj).attr('value'):$(obj).data('value');
        var kms = $(obj).attr('kms');
        var kmr = $(obj).attr('kmr');
        var duration = $(obj).attr('duration');
        if (this.isMobile())
        {
            var vht = (obj.value != undefined)?obj.value:$(obj).data('value');
            var kms = obj.kms;
            var kmr = obj.kmr;
            var duration = obj.duration;
        }
        var objBookNow = this;
        var dataModel = this.data;
        $('#' + dataModel.vehicleTypeId).val(vht);
        $('#' + dataModel.bkgTripDistance).val(kms);
        $('#' + dataModel.bkgTripDuration).val(duration);
        $('#' + dataModel.extraRate).val(kmr);
        var data = $("#cabrate-form1").serialize();
        $.ajax({
            type: 'POST',
            url: $baseUrl + "/booking/info",
            data: data,
            beforeSend: function ()
            {
                ajaxindicatorstart("");
            },
            complete: function ()
            {
                ajaxindicatorstop();

            },
            success: function (data)
            {
                //objBookNow.showTab('Quote');
                objBookNow.enableTab("Quote");
                $("#menuInfo").html(data);
                objBookNow.showTab('Info');
                objBookNow.enableTab("Info");
                $('body').removeClass('modal-open');
                if (objBookNow.isMobile() || screen.width < 900)
                {
                    $("html,body").animate({scrollTop: 0}, "slow");
                    //$("#menuQuote").hide();
                    $("#menuDetails").hide();
                }
                else
                {
                    $("#menuQuote").removeClass("active");
                    $("#menuQuote").addClass("fade");
                }
                trackPage("/booking/info");
            },
            error: function (data)
            {
                alert("Error occured.please try again");
                alert(data);
            },
            dataType: 'html'
        });
    };

    this.searchFlexi = function (bkgId)
    {
        var href = $baseUrl + "/booking/Flexisearch?bkg_id=" + bkgId;
        jQuery.ajax({type: 'GET', url: href,
            success: function (data)
            {
                box = bootbox.dialog({
                    message: data,
                    title: '',
                    size: 'large',
                    onEscape: function ()
                    {
                        box.modal('hide');
                    }
                });
            }
        });
    };

    this.bkInfoReady = function ()
    {
        var objBookNow = this;
        var data = this.data;
        callbackLogin = 'fillUserform';
        setTimeout(function ()
        {
            $("." + data.hyperlocationClass).attr("autocomplete", "disabled");
        }, 500);
        $('#' + data.infoSource).change(function ()
        {
            var infosource = $('#' + data.infoSource).val();
            objBookNow.extraAdditionalInfo(infosource);
        });
        if (data.bookingType == 4)
        {
            $('#chkAirport').hide();
            $('#picklabeloth').hide();
            $('#flightlabeldivoth').hide();
            $('#othreq').show();
            $('#flightlabeldivairport').show();
        }
        if ($('#BookingTemp_bkg_country_code').val() != '91' && data.bookingType == 1)
        {
            $('#' + data.sendSms).prop("checked", false);
        }
    };

    this.bkInfoNext = function ()
    {
        var objBookNow = this;
        var dataModel = this.data;
        $('.nav-tabs a[href="#menu3"] span').html('BY' + dataModel.carType);
        objBookNow.checkInfo(dataModel);

        if (dataModel.vehicleType != 11)
        {
            $('#nxtBtnAddDtls').click(function ()
            {
                $("#error_div_info").html("");
                $("#error_div_info").hide();
                $("#BookingTemp_bkg_user_email2").next("div").html("");
                $("#BookingTemp_bkg_user_email2").next("div").hide();
                var email1 = $("#BookingTemp_bkg_user_email2").val();
                var hasError = 0;
                var phoneregex = /^[0-9\s]*$/;
                var contact_no = $.trim($("#fullContactNumber1").val());

                //var keyPickup = $('#' + dataModel.pickupLaterChk).data('key');
                //var keyDrop = $('#' + dataModel.dropLaterChk).data('key');                
                if (objBookNow.isMobile() || screen.width < 900)
                {
                    var keyPickup = $('#' + dataModel.pickupLaterChk).data('key');
                    var keyDrop = $('#' + dataModel.dropLaterChk).data('key');
                }
                else
                {
                    var keyPickup = $('#' + dataModel.pickupLaterChk).parent().parent().parent().data('key');
                    var keyDrop = $('#' + dataModel.dropLaterChk).parent().parent().parent().data('key');
                }

                var hasError1 = objBookNow.validateLocation(dataModel, keyPickup, keyDrop);
                var hasError2 = objBookNow.validateName();
                var hasError3 = objBookNow.validateContact(contact_no, phoneregex);
                var hasError4 = objBookNow.validateContactEmail(email1);
                var key = '';
                var hasError5 = 0;
                $.each($('input[name="skipAdd"]'), function (key, value)
                {
                    if ($(value).is(':checked') == false)
                    {
                        key = $(value).data('key');
                        if ($('.brt_location_' + key).val().trim().length == 0 || $('.locLat_' + key).val() == '' || $('.locLon_' + key).val() == '')
                        {
                            $('.brt_location_' + key).css("border-color", objBookNow.errorColor);
                            $('#skipAddErr' + key).removeClass('hide');
                            hasError5 += 1;
                        }
                    }
                });
                hasError = hasError1 + hasError2 + hasError3 + hasError4 + hasError5;
		var islogin = $('#islogin').val();
                if(islogin == 1){
                        var logginCheck = 0;
                        var promise1 = new Promise(function(resolve, reject){
                                logginCheck = objBookNow.checkIfLoggedIn();
                        });
                        promise1.then();
                }		
                if (hasError >= 1)
                {
                    return false;
                }
				else if (logginCheck == 1)
                {
                    return false;
                }	
                else
                {
                    var data = $("#customerinfo").serialize();
                    $.ajax({
                        type: 'POST',
                        url: $baseUrl + "/booking/summary1",
                        data: data,
                        beforeSend: function ()
                        {
                            ajaxindicatorstart("");
                        },
                        complete: function ()
                        {
                            ajaxindicatorstop();
                        },
                        success: function (data)
                        {
                            objBookNow.successInfo(data);
                        },
                        error: function (data)
                        {
                            alert("Error occured.please try again");
                            alert(data);
                        },
                        dataType: 'html'
                    });
                }
            });
        }

        $('#' + dataModel.countryCode).change(function ()
        {
            if ($('#' + dataModel.countryCode).val() != '91' && dataModel.bookingType == 1)
            {
                $('#' + dataModel.sendSms).prop("checked", false);
            }
            else
            {
                $('#' + dataModel.sendSms).prop("checked", true);
            }
        });
    };

    this.extraAdditionalInfo = function (infosource)
    {
        var objBookNow = this;
        var data = this.data;
        $("#source_desc_show").addClass('hide');
        if (infosource == 'Friend')
        {
            $("#source_desc_show").removeClass('hide');
            $("#agent_show").addClass('hide');
            $('#' + data.infoSourceDesc).attr('placeholder', "Friend's email please");
        }
        else if (infosource == 'Other')
        {
            $("#source_desc_show").removeClass('hide');
            $("#agent_show").addClass('hide');
            $('#' + data.infoSourceDesc).attr('placeholder', "");
        }
    };

    this.findPickupAirport = function ()
    {
        var objBookNow = this;
        var dataModel = this.data;
        var href1 = $baseUrl + "/booking/pickupcityairport";
        jQuery.ajax({'type': 'GET', 'url': href1,
            'data': {'fromCity': dataModel.fromCity, 'maxDistance': 500, 'forAirport': false, 'queryStr': dataModel.queryStr, 'limit': 'LIMIT 0, 1'},
            success: function (data)
            {
                var airportArr = data.split(',');
                $('#' + dataModel.key).val(airportArr[0]).change();
            }
        });
    };

    this.showCityCenterPara = function ()
    {
        if (checkActivated > 0)
        {
            $('#cityCentreText').show();
        }
        else
        {
            $('#cityCentreText').hide();
        }
    };

    this.validateEmail = function (email)
    {
        var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
        return reg.test(email);
    };

    this.clearError = function (form)
    {
        settings = form.data('settings');
        $('#' + settings.summaryID).toggle(false);
    };

    this.displayError = function (form, messages)
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
            $.each(messages[key], function (j, message)
            {
                if ($.type(message) === 'array')
                {
                    $.each(messages[key], function (k, v)
                    {
                        if ($.type(v) == "array")
                        {
                            $.each(v, function (k1, v1)
                            {
                                if ($.type(v1) == "array")
                                {
                                    $.each(v1, function (j, message)
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
                            $.each(v, function (j, message)
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

    this.validateLocation = function (dataModel, keyPickup, keyDrop)
    {
        var objBookNow = this;
        var hasError = 0;
        var tripType = dataModel.bookingType;

        if (keyPickup != undefined)
        {
            if ($('#' + dataModel.pickupLaterChk).is(':checked') == false)
            {
                if ($('.brt_location_' + keyPickup).val().trim().length == 0 || $('.locLat_' + keyPickup).val() == '' || $('.locLon_' + keyPickup).val() == '' || $('.locPlaceid_' + keyPickup).val() == '')
                {
                    $('.brt_location_' + keyPickup).css("border-color", objBookNow.errorColor);
                    hasError = 1;
                }
                else
                {
                    $('.brt_location_' + keyPickup).css("border", objBookNow.noError);
                }
            }
            else
            {
                $('.brt_location_' + keyPickup).css("border", "1px solid #ccc");
            }
        }
        if (keyDrop != undefined)
        {
            if ($('#' + dataModel.dropLaterChk).is(':checked') == false)
            {
                if (($('.brt_location_' + keyDrop).val().trim().length == 0 || $('.locLat_' + keyDrop).val() == '' || $('.locLon_' + keyDrop).val() == '' || $('.locPlaceid_' + keyDrop).val() == '') && (tripType != 9 && tripType != 10 && tripType != 11))
                {
                    $('.brt_location_' + keyDrop).css("border-color", objBookNow.errorColor);
                    hasError = 1;
                }
                else
                {
                    $('.brt_location_' + keyDrop).css("border", objBookNow.noError);
                }
            }
            else
            {
                $('.brt_location_' + keyDrop).css("border", objBookNow.noError);
            }
        }
        return hasError;
    };

    this.validateName = function ()
    {
        var objBookNow = this;
        var hasError = 0;
        if ($.trim($("#BookingTemp_bkg_user_name").val()) == "")
        {
            hasError = 1;
            $("#BookingTemp_bkg_user_name_em_").html("First name cannot be blank.");
            $("#BookingTemp_bkg_user_name_em_").show();
            $("#BookingTemp_bkg_user_name_em_").css("color", objBookNow.errorColor);
        }
        else
        {
            $("#BookingTemp_bkg_user_name_em_").html("");
            $("#BookingTemp_bkg_user_name_em_").hide();
        }
        if ($.trim($("#BookingTemp_bkg_user_lname").val()) == "")
        {
            hasError = 1;
            $("#BookingTemp_bkg_user_lname_em_").html("Last name cannot be blank.");
            $("#BookingTemp_bkg_user_lname_em_").show();
            $("#BookingTemp_bkg_user_lname_em_").css("color", objBookNow.errorColor);
        }
        else
        {
            $("#BookingTemp_bkg_user_lname_em_").html("");
            $("#BookingTemp_bkg_user_lname_em_").hide();
        }
        return hasError;
    };

    this.validateContact = function (contact_no, phoneregex)
    {
        var objBookNow = this;
        var hasError = 0;
        if (contact_no == "")
        {
            hasError = 1;
            $("#BookingTemp_bkg_contact_no1_em_").html("Contact number cannot be blank.");
            $("#BookingTemp_bkg_contact_no1_em_").show();
            $("#BookingTemp_bkg_contact_no1_em_").css("color", objBookNow.errorColor);
        }
        else if (phoneregex.test(contact_no) == false)
        {
            hasError = 1;
            $("#BookingTemp_bkg_contact_no1_em_").html("Contact number must be numeric.");
            $("#BookingTemp_bkg_contact_no1_em_").show();
            $("#BookingTemp_bkg_contact_no1_em_").css("color", objBookNow.errorColor);
        }
        else if (contact_no.length < 8)
        {
            hasError = 1;
            $("#BookingTemp_bkg_contact_no1_em_").html("Contact No is too short (minimum is 8 characters)");
            $("#BookingTemp_bkg_contact_no1_em_").show();
            $("#BookingTemp_bkg_contact_no1_em_").css("color", objBookNow.errorColor);
        }
        else
        {
            $("#BookingTemp_bkg_contact_no1_em_").html("");
            $("#BookingTemp_bkg_contact_no1_em_").hide();
        }
        return hasError;
    };

    this.validateContactEmail = function (email1)
    {
        var hasError = 0;
        var objBookNow = this;
        if ($.trim(email1) == "")
        {
            hasError = 1;
            $("#BookingTemp_bkg_user_email2").next("div").html("Email name cannot be blank.");
            $("#BookingTemp_bkg_user_email2").next("div").show();
            $("#BookingTemp_bkg_user_email2").next("div").css("color", objBookNow.errorColor);
        }
        else if (!objBookNow.validateEmail(email1))
        {
            hasError = 1;
            $("#BookingTemp_bkg_user_email2").next("div").html("Email is not valid.");
            $("#BookingTemp_bkg_user_email2").next("div").show();
            $("#BookingTemp_bkg_user_email2").next("div").css("color", objBookNow.errorColor);
        }
        else
        {
            $("#BookingTemp_bkg_user_email2").next("div").html("");
            $("#BookingTemp_bkg_user_email2").next("div").hide();
        }
        return hasError;
    };

    this.successInfo = function (data)
    {
        var objBookNow = this;
        var isJson = 1;
        var data3 = "";
        try
        {
            data3 = JSON.parse(data);
        }
        catch (e)
        {
            isJson = 0;
        }

        if (isJson)
        {
            //console.log(data3);
            var msg = "";
            if (data3.url)
            {
                window.open('http://' + data3.url, '_parent');
            }
            var errors = data3.message;
            if (errors != undefined || errors != '')
            {
                var error1 = data3.errors;
                $.each(error1, function (key, val)
                {
                    msg += val + "\n";
                });

                if (objBookNow.isMobile() || screen.width < 900)
                {
                    objBookNow.showErrorMsg(msg);
                }
                else
                {
                    $("#error_div_info").text(msg);
                    $("#error_div_info").css("display", "block");
                }
            }
            else
            {
                var error1 = JSON.parse(errors);

                if (error1.bkg_contact_no)
                {
                    $("#BookingTemp_bkg_contact_no1_em_").html(error1.bkg_contact_no);
                    $("#BookingTemp_bkg_contact_no1_em_").show();
                    $("#BookingTemp_bkg_contact_no1_em_").css("color", "#a94442");
                    msg += error1.bkg_contact_no + "\n";
                }
                if (error1.bkg_user_email)
                {
                    $("#BookingTemp_bkg_user_email2").next("div").html(error1.bkg_user_email);
                    $("#BookingTemp_bkg_user_email2").next("div").show();
                    $("#BookingTemp_bkg_user_email2").next("div").css("color", "#a94442");
                    msg += error1.bkg_user_email;
                }
                if (error1.bkg_id)
                {
                    var error2 = JSON.parse(error1.bkg_id);
                    var msg1 = "";
                    if (error2.bkg_contact_no)
                    {
                        msg1 += error2.bkg_contact_no + "\n";
                    }
                    if (error2.bkg_user_email)
                    {
                        msg1 += error2.bkg_user_email + "\n";
                    }
                    if (error2.bkg_shuttle_id)
                    {
                        msg1 += error2.bkg_shuttle_id + "\n";
                    }
                    if (objBookNow.isMobile() || screen.width < 900)
                    {
                        objBookNow.showErrorMsg(msg1);
                    }
                    else
                    {
                        $("#error_div_info").text(msg1);
                        $("#error_div_info").css("display", "block");
                    }
                }
                if (error1.bkg_shuttle_id)
                {
                    msg += error1.bkg_shuttle_id;
                }
            }

            return false;

        }
        else
        {
            $("html,body").animate({scrollTop: 0}, "slow");
            objBookNow.showTab('Summary');
            $("#menuSummary").html(data);
            objBookNow.enableTab("Summary");
            if (objBookNow.isMobile() || screen.width < 900)
            {
                $("#menuInfo").hide();
                $("#menuSummary").show();
            }
            trackPage("/booking/summary1");
        }
    };

    this.checkInfo = function (dataModel)
    {
        var objBookNow = this;
        $('#' + dataModel.chkOthers).change(function ()
        {
            if ($('#' + dataModel.chkOthers).is(':checked'))
            {
                $("#othreq").show();
            }
            else
            {
                $("#othreq").hide();
            }
        });
        $('#' + dataModel.sendEmail).change(function ()
        {
            if ($('#' + dataModel.sendEmail).is(':checked') && $('#' + dataModel.userEmail).val() == '')
            {
                var txt = "<h5>Please fix the following input errors:</h5><ul style='list-style:none'>";
                txt += "<li>Please provide email address.</li>";
                txt += "</ul>";
                $("#error_div_info").show();
                $("#error_div_info").html(txt);
            }
        });
        $('#' + dataModel.sendSms).change(function ()
        {
            if ($('#' + dataModel.sendSms).is(':checked') && $('#' + dataModel.contactNo).val() == '')
            {
                var txt = "<h5>Please fix the following input errors:</h5><ul style='list-style:none'>";
                txt += "<li>Please provide contact number.</li>";
                txt += "</ul>";
                $("#error_div_info").show();
                $("#error_div_info").html(txt);
            }
        });

        $('#' + dataModel.flightChk).change(function ()
        {
            if ($('#' + dataModel.flightChk).is(':checked'))
            {
                $("#othreq").show();
                if (dataModel.bookingType == 1)
                {
                    objBookNow.findPickupAirport();
                }
            }
            else
            {
                $("#othreq").hide();
                if (dataModel.bookingType == 1)
                {
                    $('#brt_from_location' + dataModel.key).val('').change();
                }
            }
        });
    };

    this.showSuccessMsg = function (content, title = false, bellmsg = false)
    {
        document.getElementById("notify_success").click();
        $("#noti_success_content").html(content);
        if (title)
        {
            $("#noti_success_title").html(title);
        }
        if (bellmsg)
        {
            $("#noti_success_bell_msg").html(bellmsg);
        }
        setTimeout(function ()
        {
            $('#noti_success_bell_msg').click();
        }, 3000);
    };

    this.showErrorMsg = function (content, title = false, bellmsg = false)
    {
        document.getElementById("notify_error").click();
        $("#noti_error_content").html(content);
        if (title)
        {
            $("#noti_error_title").html(title);
        }
        if (bellmsg)
        {
            $("#noti_error_bell_msg").html(bellmsg);
        }
        setTimeout(function ()
        {
            $('#noti_error_bell_msg').click();
        }, 5000);
    };
    this.showInfoMsg = function (content, title = false, bellmsg = false)
    {
        document.getElementById("notify_info").click();
        $("#noti_info_content").html(content);
        if (title)
        {
            $("#noti_info_title").html(title);
        }
        if (bellmsg)
        {
            $("#noti_info_bell_msg").html(bellmsg);
        }
        setTimeout(function ()
        {
            $('#noti_error_bell_msg').click();
        }, 6000);
    };

    this.homeReady = function ()
    {
        $('.sub-tab').click(function ()
        {
            var id = $(this).data('sub-tab');
            //var radioId;
            if (id == "tab-pill-3a" || id == "tab-pill-4a" || id == "tab-pill-1a" || id == "tab-pill-5a" || id == "tab-pill-7a" || id == "tab-pill-8a" || id == "tab-pill-10a")
            {
                $(this).parent().parent().css({'display': 'none'});
            }
            $('.sub-tab').removeClass('active-tab-pill-button active');
            $('a[data-sub-tab="' + id + '"]').addClass('active-tab-pill-button active');
            if (id == "tab-pill-10a")
            {
                $('a[data-sub-tab="tab-pill-1a"]').addClass('active-tab-pill-button active');
                $('.btnpersonalcab').removeClass('active-tab-pill-button active');
            }
            $('#' + id).css({'display': 'block'});
        });

        $('.sub-subtab').click(function (event)
        {
            var id = $(event.currentTarget).data('subtab-sub');
            if (id == "tab-pill-5a" || id == "tab-pill-7a")
            {
                $(event.currentTarget).parent().parent().css({'display': 'none'});
            }
            $('.sub-subtab').removeClass('active');
            $('a[data-subtab-sub="' + id + '"]').addClass('active');
            $('#' + id).css({'display': 'block'});
        });
    };

    this.validateTrip = function (form, url)
    {
        var objBookNow = this;
        var success = false;
        $.ajax({
            "type": "POST",
            "async": false,
            "url": url,
            "data": form.serialize(),
            "dataType": "json",
            "success": function (data1)
            {
                if (data1.success)
                {
                    success = true;
                }
                else
                {
                    var errors = data1.errors;
                    var content = "";
                    for (var key in errors)
                    {
                        $.each(errors[key], function (j, message)
                        {
                            content = content + message + "<br/>";
                        });
                    }
                    objBookNow.showErrorMsg(content);
                }
            },
        });
        return success;
    };

    this.togglePackage = function (obj)
    {
        let cabType = obj.attr('data-cab');
        let packageRow = $('.rowPackage' + cabType);
        if (obj.hasClass("pkgShow"))
        {
            if (packageRow.length > 0)
            {
                packageRow.show();
                this.togglePackageButton(cabType, false);
            }
            else
            {
                this.getPackageContent(cabType, this.togglePackageButton);
            }
        }
        else
        {
            packageRow.hide();
            this.togglePackageButton(cabType, true);
        }
    };

    this.togglePackageButton = function (cabType, show = true)
    {
        let showButton = $('.pkgShow[data-cab=' + cabType + ']');
        let hideButton = $('.pkgHide[data-cab=' + cabType + ']');

        if (show)
        {
            showButton.removeClass('hide');
            hideButton.addClass('hide');
        }
        else
        {
            showButton.addClass('hide');
            hideButton.removeClass('hide');
    }
    };

    this.getPackageContent = function (cabType, callback)
    {
        $.ajax({
            type: "POST",
            url: "/booking/packageQuote",
            data: {'bkgid': $bkgId, 'cab': cabType, 'YII_CSRF_TOKEN': $('input[name="YII_CSRF_TOKEN"]').val()},
            success: function (data1)
            {
                let category = $('.pkgShow[data-cab=' + cabType + ']').attr('data-cat');
                $('.rowCategory' + category).after(data1);
                callback(cabType, false);
            },
            error: function (error)
            {
                console.log(error);
            }
        });
    };
	
	
	this.checkIfLoggedIn = function ()
    {
		var objBookNow = this;
		var hasError = 0;				
		$.ajax({
			url: '/users/userdata',
			type: 'GET',
			async:false,
			success: function (data) 
			{  	    
				let pdata = JSON.parse(data);                  
				if(pdata.usr_name === null && pdata.usr_lname === null && !pdata.hasOwnProperty('usr_mobile') && !pdata.hasOwnProperty('usr_email'))
				{
					hasError = 1;
					if (!objBookNow.isMobile())
                    {                    	
						$("#signinpopup").click();
					} else {						
						objBookNow.showErrorMsg("Please loggein to continue.");
						$('html, body').animate({scrollTop:$(document).height()}, 'slow');								
					}				         
				}      
			}			
        });		
		return hasError; 		
	};


};