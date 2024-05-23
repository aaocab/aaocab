/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var FollowUp = function () {
    this.createFollowUps = function ()
    {
        $.ajax({
            "type": "GET",
            "url": $baseUrl + '/admpnl/scq/ctrScq',
            "data":
                    {"YII_CSRF_TOKEN": $("input[name='YII_CSRF_TOKEN']").val()},
            "dataType": "HTML",
            "success": function (data1)
            {
                schedulebox = bootbox.dialog({
                    message: data1,
                    size: 'large',
                    title: '',

                });
                schedulebox.on('hidden.bs.modal', function (e)
                {
                    $('body').addClass('modal-open');
                });
            }

        });


    };

    this.populateVnds = function (obj, vndId)
    {

        obj.load(function (callback)
        {
            var obj = this;
            if ($vndList == null)
            {
                xhr = $.ajax({
                    "url": $baseUrl + '/admpnl/scq/vnds/apshow/1',
                    dataType: 'json', data: {"vndId": vndId},
                    success: function (results)
                    {
                        $vndList = results;
                        obj.enable();
                        callback($vndList);
                        obj.setValue(vndId);
                    },
                    error: function () {
                        callback();
                    }});
            } else
            {
                obj.enable();
                callback($vndList);
                obj.setValue(vndId);
            }
        });


    };
    this.loadVnds = function (query, callback)
    {
        $.ajax({
            "url": $baseUrl + '/admpnl/scq/vnds',
            type: 'GET',
            "data":
                    {"apshow": 1, "q": encodeURIComponent(query)},
            dataType: 'json',
            global: false,
            error: function () {
                callback();
            },
            success: function (res)
            {
                callback(res);
            }
        });


    };
    this.populateDrvs = function (obj, drvId)
    {

        obj.load(function (callback)
        {
            var obj = this;
            if ($drvList == null)
            {
                xhr = $.ajax({
                    "url": $baseUrl + '/admpnl/scq/drvs/apshow/1',
                    dataType: 'json', data: {"drvId": drvId},
                    success: function (results)
                    {
                        $drvList = results;
                        obj.enable();
                        callback($drvList);
                        obj.setValue(drvId);
                    },
                    error: function () {
                        callback();
                    }});
            } else
            {
                obj.enable();
                callback($drvList);
                obj.setValue(drvId);
            }
        });

    };

    this.loadDrvs = function (query, callback)
    {
        $.ajax({
            "url": $baseUrl + '/admpnl/scq/drvs',
            type: 'GET',
            "data":
                    {"apshow": 1, "q": encodeURIComponent(query)},
            dataType: 'json',
            global: false,
            error: function () {
                callback();
            },
            success: function (res)
            {
                callback(res);
            }
        });
    };
    $custList = null;
    this.populateCustomer = function (obj, cust)
    {

        obj.load(function (callback)
        {
            var obj = this;
            if ($custList == null)
            {
                // url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('admpnl/scq/customer', ['onlyActive' => 1, 'agt' => ''])) ?>' + agtId,
                xhr = $.ajax({
                    "url": $baseUrl + '/admpnl/scq/customer/apshow/1/',
                    dataType: 'json',
                    data: {"cust": cust},
                    success: function (results)
                    {
                        $custList = results;
                        obj.enable();
                        callback($custList);
                        obj.setValue(cust);
                    },
                    error: function () {
                        callback();
                    }});
            } else
            {
                obj.enable();
                callback($custList);
                obj.setValue(cust);
            }
        });

    };


    this.loadCustomer = function (query, callback)
    {
        $.ajax({
            "url": $baseUrl + '/admpnl/scq/customer',
            type: 'GET',
            "data":
                    {"apshow": 1, "q": encodeURIComponent(query)},
            dataType: 'json',
            global: false,
            error: function () {
                callback();
            },
            success: function (res)
            {
                callback(res);
            }
        });
    };


    this.populateGozen = function (obj, gozen)
    {

        obj.load(function (callback)
        {
            var obj = this;
            if ($gozenList == null)
            {
                xhr = $.ajax({
                    "url": $baseUrl + '/admpnl/scq/gozens/apshow/1',
                    //  url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('admpnl/scq/gozens', ['apshow' => 1])) ?>',
                    dataType: 'json',
                    data: {"gozen": gozen},

                    success: function (results)
                    {
                        $gozenList = results;
                        obj.enable();
                        callback($gozenList);
                        obj.setValue(gozen);
                    },
                    error: function ()
                    {
                        callback();
                    }
                });
            } else
            {
                obj.enable();
                callback($gozenList);
                obj.setValue(gozen);
            }
        });

    };


    this.loadGozen = function (query, callback)
    {
        $.ajax({
            "url": $baseUrl + '/admpnl/scq/gozens',
            type: 'GET',
            "data":
                    {"apshow": 1, "q": encodeURIComponent(query)},
            dataType: 'json',
            global: false,
            error: function () {
                callback();
            },
            success: function (res)
            {
                callback(res);
            }
        });


    };



    this.bkgDetails = function (bkgID)
    {

        var html1 = "";

        $.ajax({
            type: "GET",
            dataType: "json",
            "data":
                    {"id": bkgID},
            url: $baseUrl + "/admpnl/booking/display",

            success: function (data1)
            {

                var url = $baseUrl + "/admpnl/booking/view?id=" + data1.NID;
                html1 = "<table><tr><td><a href='" + url + "' target='blank'>" + data1.ID + "</a></td></tr>\n"
                            +"<tr><td>Trip :" + data1.Trip + "</td></tr>\n"
                            +"<tr><td>Created On :" + data1.CRTDT + "</td></tr>\n"
							+"<tr><td>Pickup Date :" + data1.PCTDT + "</td></tr></table>";
                $("#bkgDesc").html(html1);
                $("#scq_related_vnd_id").val(data1.vndID);

                return false;
            },
            error: function (error)
            {
                console.log(error);
            }
        });
    };
    this.bkgdtlscq = function (bkgID)
    {
        alert(bkgID);


        $.ajax({
            type: "GET",
            dataType: "json",
            "data":
                    {"id": bkgID},
            url: $baseUrl + "/admpnl/booking/dtlscq",

            success: function (data1)
            {
                $("scq_related_vnd_id").val(data1.vndID);
                return false;
            },
            error: function (error)
            {
                console.log(error);
            }
        });
    };

    this.populateAdmins = function (obj, admId)
    {

        obj.load(function (callback)
        {
            var obj = this;
            if ($adminList == null)
            {
                xhr = $.ajax({
                    "url": $baseUrl + '/admpnl/scq/gozens/apshow/1',
                    //  url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('admpnl/scq/gozens', ['apshow' => 1])) ?>',
                    dataType: 'json',
                    data: {"gozen": admId},

                    success: function (results)
                    {
                        $adminList = results;
                        obj.enable();
                        callback($adminList);
                        obj.setValue(admId);
                    },
                    error: function ()
                    {
                        callback();
                    }
                });
            } else
            {
                obj.enable();
                callback($adminList);
                obj.setValue(admId);
            }
        });

    };


    this.loadAdmins = function (query, callback)
    {
        $.ajax({
            "url": $baseUrl + '/admpnl/scq/gozens',
            type: 'GET',
            "data":
                    {"apshow": 1, "q": encodeURIComponent(query)},
            dataType: 'json',
            global: false,
            error: function () {
                callback();
            },
            success: function (res)
            {
                callback(res);
            }
        });


    };

    this.populateTeams = function (obj, teamId)
    {
        obj.load(function (callback)
        {
            var obj = this;
            if ($teamList == null)
            {
                xhr = $.ajax({
                    "url": $baseUrl + '/admpnl/scq/teams/apshow/1',
                    //  url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('admpnl/scq/team', ['apshow' => 1])) ?>',
                    dataType: 'json',
                    data: {"teamId": teamId},
                    success: function (results)
                    {
                        $teamList = results;
                        obj.enable();
                        callback($teamList);
                        obj.setValue(teamId);
                    },
                    error: function ()
                    {
                        callback();
                    }
                });
            } else
            {
                obj.enable();
                callback($teamList);
                obj.setValue(teamId);
            }
        });

    };


    this.loadTeams = function (query, callback)
    {
        $.ajax({
            "url": $baseUrl + '/admpnl/scq/teams',
            type: 'GET',
            "data":
                    {"apshow": 1, "q": encodeURIComponent(query)},
            dataType: 'json',
            global: false,
            error: function () {
                callback();
            },
            success: function (res)
            {
                callback(res);
            }
        });


    };
}
       