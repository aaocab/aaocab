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
	$outputJs		 = Yii::app()->request->isAjaxRequest;
	$record			 = Vendors::model()->getDrillDownInfo($vndid);
	$vndmodel		 = Vendors::model()->findByPk($vndid);
	$data			 = Vendors::model()->getViewDetailbyId($vndid);
	$vendorAmount	 = AccountTransDetails::model()->calAmountByVendorId($vndid, '', $ven_to_date);

	$date1			 = date('Y-m-d', strtotime("-10 days"));
	$date2			 = date('Y-m-d');
	$vendorModels	 = AccountTransDetails::vendorTransactionList($vndid, $date1, $date2, '1', '', null, 'data');
	$vendorModels->setSort(['params' => array_filter($_GET + $_POST)]);
	$vendorModels->setPagination(['params' => array_filter($_GET + $_POST)]);

//	var_dump($data);
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
										<td><b>Vendor</b></td>
										<td><?php echo $record['vnd_name'] ?></td>
									</tr>
									<?php
									$vndUserType		 = ($record['ctt_user_type'] == '1') ? "Owner" : "Company";
									$vndOwner			 = ($record['ctt_user_type'] == '1') ? $record['ctt_first_name'] . ' ' . $record['ctt_last_name'] : $record['ctt_business_name'];
									?>
									<tr>
										<td><b><?php echo $vndUserType ?></b></td>
										<td><?php echo ($vndOwner == '') ? 'Not Available' : $vndOwner ?></td>
									</tr>

									<tr>
										<td><b><?php echo $vndUserType ?> phone no.</b></td>
										<td><?php echo $record['phn_phone_no'] ?></td>
									</tr>
									<tr>
										<td><b>Preferred method of contact</b></td>
										<td>Phone</td>
									</tr>                        


								</table>
								<h4 class="mb5">Permanent notes</h4>
								<p><?php echo $record['vnp_notes'] ?></p>
							</div>
							<div class="col-xs-12 col-sm-7 table-responsive">
								<table class="table table-striped table-bordered">
									<?php $overall_rating		 = ($record['vrs_vnd_overall_rating'] == '') ? 'Not Available' : $record['vrs_vnd_overall_rating'] ?>
									<?php $overall_star_rating = ($record['vrs_vnd_overall_rating'] == '') ? 0 : $record['vrs_vnd_overall_rating'] ?>
									<tr>
										<td><b>Current rating</b></td>
										<td><span class="stars"><?php echo $overall_star_rating ?></span><?php echo $overall_rating ?></td>
									</tr>
									<tr>
										<td><b>Rating trend</b></td>
										<td>
											<div class="col-xs-4 pl0"><span class="stars"><?php echo ($record['vnd_last_three_month_rating'] == '') ? $overall_star_rating : $record['vnd_last_three_month_rating'] ?></span><?php echo ($record['vnd_last_three_month_rating'] == '') ? $overall_rating : $record['vnd_last_three_month_rating'] ?><br>(3 m)</div>
											<div class="col-xs-4"><span class="stars"><?php echo ($record['vnd_last_six_month_rating'] == '') ? $overall_star_rating : $record['vnd_last_six_month_rating'] ?></span><?php echo ($record['vnd_last_six_month_rating'] == '') ? $overall_rating : $record['vnd_last_six_month_rating'] ?><br>(6 m)</div>
											<div class="col-xs-4"><span class="stars"><?php echo ($record['vnd_last_twelve_month_rating'] == '') ? $overall_star_rating : $record['vnd_last_twelve_month_rating'] ?></span><?php echo ($record['vnd_last_twelve_month_rating'] == '') ? $overall_rating : $record['vnd_last_twelve_month_rating'] ?><br>(12 m)</div>
										</td>
									</tr>
									<?php
									if (1 === 2)
									{
										?>
										<?php $zones	 = str_replace(",", ", ", $record['vnd_zones']); ?>
										<?php $zones	 = str_replace("Z-", "", $zones); ?>
										<tr >
											<td><b>Zones operating in</b></td>
											<td><?php echo ($zones == '') ? 'Not Available' : $zones ?></td>
										</tr>
									<?php } ?>
									<tr>
										<td><b>Home City</b></td>
										<td><?php echo ($record['vnd_home_city'] == '') ? 'Not Available' : $record['vnd_home_city'] ?></td>
									</tr>
									<tr>
										<td><b># of Trips</b></td>
										<td>
											<div class="col-sm-2 pl0"><?php echo $record['vnd_last_ten_day_trips'] ?><br>(Last 10 d)</div>
											<div class="col-sm-2"><?php echo $record['vnd_last_one_month_trips'] ?><br>(1 m)</div>
											<div class="col-sm-2"><?php echo $record['vnd_last_three_month_trips'] ?><br>(3 m)</div>
											<div class="col-sm-2"><?php echo $record['vnd_last_six_month_trips'] ?><br>(6 m)</div>
											<div class="col-sm-2"><?php echo $record['vnd_last_twelve_month_trips'] ?><br>(12 m)</div>
											<div class="col-sm-2"><?php echo ($record['vrs_total_trips'] == '') ? 0 : $record['vrs_total_trips'] ?><br>(lifetime)</div>
										</td>
									</tr>
								</table>
							</div> 
							<div class="col-sm-3 table-responsive">
								<table class="table table-striped table-bordered">
									<tr>
										<td><b>Accounts Balance</b></td>
										<td><i class="fa fa-inr"></i><?php
											if ($vendorAmount['vendor_amount'] < 0)
											{
												echo trim(-1 * $vendorAmount['vendor_amount']);
											}
											else
											{
												echo '0';
											}
											?></td>
									</tr>
									<tr>
										<td><b>Withdrawable Balance</b></td>
										<td><i class="fa fa-inr"></i>
											<?php
											echo $vendorAmount['withdrawable_balance'];
											?></td>
									</tr>
									<tr>
										<td><b>Accounts Receivable</b></td>
										<td><i class="fa fa-inr"></i><?php
											if ($vendorAmount['vendor_amount'] > 0)
											{
												echo trim($vendorAmount['vendor_amount']);
											}
											else
											{
												echo '0';
											}
											?></td>
									</tr>
									<tr>
										<td><b>Security Deposit</b></td>
										<td><i class="fa fa-inr"></i>
											<?php
											echo ($vendorAmount['vnd_security_amount'] > 0) ? round($vendorAmount['vnd_security_amount']) : 0;
											?>
										</td>
									</tr>
								</table>
							</div>
							<div class="col-xs-6" >
								<a class="" onclick="closeDocumentApprovalCall()">	
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

								<?php
								$params1 = array_filter($_GET + $_POST);
								/* @var $model BookingTemp */
								/* @var $dataProvider CActiveDataProvider */
								$bid	 = 1;
								$params	 = array_filter($params1);

								foreach ($tabFilterVal as $bid => $tabs)
								{
									if (in_array($bid, [3, 5, 6]))
									{
										$tabUrl = "data-url=\"" . Yii::app()->createUrl('admin/booking/list', array('tab' => $bid, 'vndid' => $vndid)) . '"';
										?>
										<li class='<?php echo "tabactive" . $bid ?>' id="<?php echo "tablist_" . $bid; ?>">
											<a data-toggle="tabajax" id="tid_<?php echo $bid ?>" <?php echo $tabUrl ?> class="bg-white" href="#sec<?php echo $bid ?>">
												<?php echo $tabs['label'] ?> <span class="font-bold" style="font-size: 1.2em">(<?php echo $tabs['count'] ?>)

											</a>
										</li><?php
									}
									else
									{
										?>
										<li class='<?php echo ${"tabactive" . $bid} ?>' id="<?php echo "tablist_" . $bid ?>"><a data-toggle="tab" id="tid_<?php echo $bid ?>" class="bg-white" href="#sec<?php echo $bid ?>"><?php echo $tabs['label'] ?> <span class="font-bold" style="font-size: 1.2em">(<?php echo $tabs['count'] ?>)</span></a></li>
										<?php
									}
								}
								?>
								<li class="tabactive4" id="tablist_4"><a data-toggle="tab" id="tid_4" class="bg-white" href="#sec4">Accounts </a></li>
								<li class="tabactive10" id="tablist_20">
									<?php $tabUrl	 = "data-url=\"" . Yii::app()->createUrl('admin/vehicle/availabilitylist', array('vndid' => $vndid, 'source' => 'mycall')) . '"';
									?>
									<a data-toggle="tabajax" id="tid_20" <?php echo $tabUrl ?> class="bg-white" href="#sec20"> Cab Availability List</a>
								</li>
								<li class="tabactive10 " id="tablist_10">
									<?php $tabUrl	 = "data-url=\"" . Yii::app()->createUrl('admin/scq/list', array('refId' => $model['scq_related_bkg_id'], 'fwpId' => $model['scq_id'], 'isMycall' => 1)) . '"';
									?>
									<a data-toggle="tabajax" id="tid_10" <?php echo $tabUrl ?> class="bg-white" href="#sec10"> FollowUp	</a>
								</li>

							</ul>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="tab-content p0">
						<?php
						foreach ($dataProviders as $bid => $dataProvider)
						{
							?><div id="<?php echo 'sec' . $bid ?>" tabid="<?php echo $bid ?>" class="tab-pane <?php echo ${'tabactive' . $bid} ?>">
							<?php
							switch ($bid)
							{
								case 1:
									$this->renderPartial('mycallVndDrv', ['dataProvider' => $dataProvider, 'drvmodel' => $drvmodel]);
									break;
								case 2:
									$this->renderPartial('mycallVndVhc', ['dataProvider' => $dataProvider, 'vhcmmodel' => $vhcmmodel]);
									break;
							}
							?></div>

							<?php
						}
						?>
						<div id="<?php echo 'sec0' ?>" tabid="0" class="tab-pane active">			 

							<?php
							$vndId			 = $vndmodel->vnd_id;
							$contactID		 = ContactProfile::getByEntityId($vndId, $entityType		 = UserInfo::TYPE_VENDOR);
							$data			 = Vendors::model()->getViewDetailbyId($vndId);
							$vndTripDetails	 = Vendors::getBookingHistoryById($vndId);
							$cabData		 = VendorVehicle::getVehicleListByVndId($vndId);
							$driverData		 = VendorDriver::getDriverListbyVendorid($vndId);
							$docByContactId	 = $contactID != null || $contactID != "" ? Document::model()->getAllDocsbyContact($contactID, 'vendor') : array();
							$vndStats		 = VendorStats::model()->getbyVendorId($vndId);
							$showlog		 = VendorsLog::model()->getByVendorId($vndId);
							$bidLog			 = BookingVendorRequest::model()->getbidbyVnd($vndId);
							$penalty		 = AccountTransDetails::model()->getbyVendorId($vndId);
							$cbrDetails		 = ServiceCallQueue::model()->getCBRDetailbyId($vndId, "Vendor");
							$notificationLog = NotificationLog::model()->getbyVendorId($vndId);
							echo $this->renderPartial('../vendor/view', array(
								'model'				 => $vndmodel,
								'mycall'			 => 1,
								'data'				 => $data,
								'cabData'			 => $cabData,
								'driverData'		 => $driverData,
								'vndTripDetails'	 => $vndTripDetails,
								'docpath'			 => $docByContactId,
								'vndStats'			 => $vndStats,
								'showlog'			 => $showlog,
								'bidLog'			 => $bidLog,
								'penalty'			 => $penalty,
								'cbrdetails'		 => $cbrDetails,
								'notificationLog'	 => $notificationLog
									), false, false);
							?>
						</div>
						<div id="<?php echo 'sec4' ?>" tabid="4" class="tab-pane">	
							<div class="row">
								<?php
								$dateFromDate	 = DateTimeFormat::DateToLocale($date1);

								$openingBalance = AccountTransDetails::getOpeningBalance($vndid, $date1);
								if ($openingBalance != 0)
								{
									$date = date_create($dateFromDate);
									?>
									<h2 align="center" class = "mb5">Opening Balance at <?php echo date_format($date, "dS M ,Y") ?>	( Rs: <?php echo $openingBalance ?>)</h2>
								<?php } ?>
							</div>
							<?php
							$this->renderPartial('mycallVndAcct', ['vendorModels' => $vendorModels, 'isAjax' => $outputJs], false);
							?>
						</div>
						<?php
						$paneTab = [3, 5, 6, 10, 20];
						foreach ($paneTab as $val)
						{
							?>
							<div id="<?php echo 'sec' . $val ?>" tabid="<?php echo $val ?>" class='<?php echo 'tab-pane tabactive' . $val ?>'>
							</div>
							<?php
						}
						?>
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
    function closeDocumentApprovalCall()
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
                    $href = '/aaohome/lead/closeDocumentApprovalCall';
                    jQuery.ajax({type: 'GET', url: $href, dataType: 'json',
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
            $('#vendorDetails').show();
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
            $('#vendorDetails').show();
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
        // ajax load from data-url
        $(href).load(url, function (result)
        {
            pane.tab('show');
            addTabCache($(this).attr('tabid'));
        });
    });
    function autoAllocateLead(admId, type)
    {
        $href = '/aaohome/admin/autoAllocateLead';
        jQuery.ajax({type: 'GET', url: $href, dataType: 'json', data: {'adm_id': admId, 'type': type},

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