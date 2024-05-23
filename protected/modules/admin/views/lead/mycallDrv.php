<style>
    .dev-height{
        overflow: auto;
        height: 200px;
        border: #dfdfdf 1px solid;
        padding: 15px;
    }
</style>
<?php
$csr		 = UserInfo::getUserId();
$pmodel		 = AdminProfiles::model()->getByAdminID($csr);
?>
<div style="float:right">
	<input type="checkbox" id="auto_allocated" name="auto_allocated" onclick="autoAllocateLead('<?php echo $pmodel->adp_adm_id; ?>', '<?php echo $pmodel->adp_auto_allocated ?>')" value="<?php echo $pmodel->adp_auto_allocated; ?>" <?php echo $pmodel->adp_auto_allocated == 1 ? 'checked="checked"' : ''; ?>>
	<label for="auto_allocated"> <b>Auto Lead Allocate</b></label><br>
</div>
<?php
//var_dump($vndModel);exit;
if ($model["scq_id"] != '')
{
    $outputJs            = Yii::app()->request->isAjaxRequest;
    $record              = DriverStats::model()->getbyDriverId($drvid);
    $data                = Drivers::getDetailsById($drvid);
    $driverAmount        = AccountTransDetails::model()->calBonusAmountByDriverId($drvid, '', $ven_to_date);
    $date1               = date('Y-m-d', strtotime("-10 days"));
    $date2               = date('Y-m-d');
    $driverModels        = AccountTransDetails::driverTransactionList($drvid, $date1, $date2, '1', '', null, 'data');
    ?>
    <div class="">
        <div class="row">
            <div class="col-xs-12">
                <div class="panel panel-default main-tab1">
                    <div class="panel-body panel-border">
                        <div class="row">
                            <div class="col-xs-12 col-sm-5 table-responsive">
                                <table class="table table-striped table-bordered">
                                    <tr>
                                        <td><b>Drivers</b></td>
                                        <td><?php echo $result['drv_name']; ?></td>
                                    </tr>
                                    <?php
                                    $vndUserType         = ( $result['ctt_user_type'] == '1') ? "Owner" : "Company";
                                    $vndOwner            = $result['ctt_user_type'] == '1' ? $result['ctt_first_name'] . ' ' . $result['ctt_last_name'] : $result['ctt_business_name'];
                                    ?>
                                    <tr>
                                        <td><b><?php echo $vndUserType ?></b></td>
                                        <td><?php echo ($vndOwner == '') ? 'Not Available' : $vndOwner ?></td>
                                    </tr>

                                    <tr>
                                        <td><b><?php echo $vndUserType ?> phone no.</b></td>
                                        <td><?php echo $result['drv_phone']; ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>Preferred method of contact</b></td>
                                        <td>Phone</td>
                                    </tr>                        


                                </table>

                            </div>
                            <div class="col-xs-12 col-sm-7 table-responsive">
                                <table class="table table-striped table-bordered">
                                    <?php $overall_rating      = ($record['drs_drv_overall_rating'] == '') ? 'Not Available' : $record['drs_drv_overall_rating'] ?>
                                    <?php $overall_star_rating = ($record['drs_drv_overall_rating'] == '') ? 0 : $record['drs_drv_overall_rating'] ?>
                                    <tr>
                                        <td><b>Current rating</b></td>
                                        <td><span class="stars"><?php echo $overall_star_rating ?></span></td>
                                    </tr>


                                    <tr>
                                        <td><b>Home City</b></td>
                                        <td><?php echo ($result['ctt_city'] == '') ? 'Not Available' : $result['ctt_city'] ?></td>
                                    </tr>

                                </table>
                            </div> 

                            <div class="col-xs-6" >
                                <a class="" onclick="closeCall()">	
                                    <span class="btn btn-info btn-sm mb5 mr5" >Close Call</span>
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        <div class="tab-content">        
            <div id="first" class="tab-pane1 fade in active">

                <div class="row">
                    <div class="col-xs-12 pt5">
                        <div class="row">
                            <ul class="nav nav-tabs  " id="myTab">
                                <li class='tabactive0' id="tablist_0"><a data-toggle="tab" id="tid_0" class="bg-white" href="#sec0"><?php echo 'Details' ?>  </a></li>
                                <li class="tabactive10 " id="tablist_10">
                                    <?php $tabUrl              = "data-url=\"" . Yii::app()->createUrl('admin/scq/list', array('refId' => $model['scq_related_bkg_id'], 'fwpId' => $model['scq_id'], 'isMycall' => 1)) . '"';
                                    ?>
                                    <a data-toggle="tabajax" id="tid_10" <?php echo $tabUrl ?> class="bg-white" href="#sec10"> FollowUp	</a>
                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="tab-content p0">
                        <div id="<?php echo 'sec0' ?>" tabid="0" class="tab-pane active">			 
                            <?php
                            $contactId           = ContactProfile::getByEntityId($drvid, UserInfo::TYPE_DRIVER);
                            $models              = Drivers::model()->findByPk($drvid);
                            $data                = Drivers::getDetailsById($drvid);
                            $pastData            = Drivers::model()->getPastTripList($drvid);
                            $pastTrip            = new CArrayDataProvider($pastData, array('pagination' => array('pageSize' => 25),));
                            $showLog             = DriversLog::model()->getByDriverId($drvid, $viewType);
                            $docById             = $contactId != null || $contactId != "" ? Document::model()->getAllDocsbyContact($contactId, 'driver') : array();

                            $this->renderPartial('../driver/view', array(
                                'model'    => $models,
                                'data'     => $data,
                                'pastData' => $pastTrip,
                                'showLog'  => $showLog,
                                'docpath'  => $docById,
                                'mycall'   => 1,
                                    ), false, false);
                            ?>
                        </div>
                        <div id="sec10" tabid="10" class='tab-pane tabactive10'></div>

                    </div> 
                </div>  
            </div>
        </div>
    </div>
    <?php
    // }
}
else
{
    ?>
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <p><h2>--> Press "REFRESH" in Ops app and then refresh this page.</h2></p>
            </div>
        </div>
    </div>
    <?php
}
?>


<?php
$version = Yii::app()->params['customJsVersion'];
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/custom.js?v=' . $version, CClientScript::POS_HEAD);

$time = Filter::getExecutionTime();

$GLOBALS['time'][9] = $time;
?>
<script type="text/javascript">
    function closeCall(userid, csrid, bkgid)
    {
        bootbox.confirm({
            title: "Close Call",
            message: "Are you sure want to close this call?",
            buttons: {
                cancel: {
                    label: '<i class="fa fa-times"></i> Cancel'
                },
                confirm: {
                    label: '<i class="fa fa-check"></i> Confirm'
                }
            },
            callback: function (result) {
                if (result)
                {
                    $href = '/admpnl/lead/closeDrvCall';
                    jQuery.ajax({type: 'GET', url: $href, dataType: 'json', data: {},
                        success: function (data)
                        {
                            if (data.success == false)
                            {
                                bootbox.alert("You have a pending call back pending to be followed up. Please close it.");
                            } else
                            {
                                bootbox.alert("Successfully call closed.", function () {
                                    window.location.reload();
                                });

                            }

                        }
                    });
                }

            }
        });
    }
    $('#myTab a[data-toggle="tab"]').click(function (e)
    {
        $tid = $(this).attr('id');
        $idval = $tid.substr(4);
        $('.tab-pane').hide();
        if ($idval == 0)
        {
            $('.tabHide').hide();
            $('#driverDetails').show();
        }
        $('#sec' + $idval).show();
    });
    $('#viewId a[data-toggle="tab"]').click(function (e)
    {
        tid = $(this).attr('href');
        $('.tabHide').hide();
        $(tid).show();

    });
    $('#myTab a[data-toggle="tabajax"]').click(function (e)
    {
        $tid = $(this).attr('id');
        $idval = $tid.substr(4);
        $('.tab-pane').hide();
        if ($idval == 0)
        {
            $('.tabHide').hide();
            $('#driverDetails').show();
        }
        $('#sec' + $idval).show();
        e.preventDefault();
        var url = $(this).attr("data-url");
        var href = this.hash;
        var pane = $(this);
        if ($tabCache.indexOf($(href).attr('id')) > -1)
        {
            pane.tab('show');
            return;
        }
        $(href).load(url, function (result)
        {
            pane.tab('show');
            addTabCache($(this).attr('tabid'));
        });
    });
	 function autoAllocateLead(admId, type)
        {
            $href = '/admpnl/admin/autoAllocateLead';
            jQuery.ajax({type: 'GET', url: $href, dataType: 'json', data: {'adm_id' : admId, 'type': type},

                success: function (data)
                {
                    if (data.success == false)
                    {
                        bootbox.alert("Some error occured.");
                    } else
                    {
                        bootbox.alert("Successfully updated", function () {
                            window.location.reload();
                        });

                    }

                }
            });

        }
</script>