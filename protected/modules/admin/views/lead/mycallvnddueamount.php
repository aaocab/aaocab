<style>
    .dev-height{
        overflow: auto;
        height: 200px;
        border: #dfdfdf 1px solid;
        padding: 15px;
    }
</style>
<?php
$csr	 = UserInfo::getUserId();
$pmodel	 = AdminProfiles::model()->getByAdminID($csr);
?>
<div style="float:right">
	<input type="checkbox" id="auto_allocated" name="auto_allocated" onclick="autoAllocateLead('<?php echo $pmodel->adp_adm_id; ?>', '<?php echo $pmodel->adp_auto_allocated ?>')" value="<?php echo $pmodel->adp_auto_allocated; ?>" <?php echo $pmodel->adp_auto_allocated == 1 ? 'checked="checked"' : ''; ?>>
	<label for="auto_allocated"> <b>Auto Lead Allocate</b></label><br>
</div>
<?php
if ($model["scq_id"] != '')
{
	$result	 = $resultQT;
	$type	 = "Vendor Due Amount";
	$typeId	 = 2;
	?>
	<div class="">
		<div class="row">
			<div class="col-xs-12">
				<div class="panel panel-default main-tab1">
					<div class="panel-body panel-border">
						<div class="row">
							<div class="col-xs-12 col-lg-7">
								<p class="mb5"><b>Name:</b>   <?php echo $vndData['vnd_name']; ?></p>
								<p class="mb5"><b>Code:</b> <?php echo $vndData['vnd_code']; ?></p>
								<p class="mb5"><b>Home Zone:</b>   <?php echo $vndData['vnd_home_zone']; ?></p>
								<p class="mb5"><b>Total Completed Trips:</b> <?php echo $vndData['vnd_total_trips']; ?></p>
								<div class="col-xs-6" ><br/>
									<a target="_blank" class="" href="/aaohome/vendor/vendoraccount?vnd_id=<?php echo $vndData['vnd_id']; ?>">	
										<span class="btn btn-info btn-sm mb5 mr5" >Show Account Details</span>
									</a>
								</div>	

							</div>
							<div class="col-xs-12 col-lg-5">
								<p class="mb5"><b>Credit Limit:</b>  <?php echo '&#x20B9;' . $vndData['vnd_credit_limit']; ?></p>
								<p class="mb5"><b>Withdrawable Balance:</b>  <?php echo '&#x20B9;' . $vndData['withdrawable_balance']; ?></p>
								<p class="mb5"><b>Running Balance:</b>  <?php echo '&#x20B9;' . $calAmount['vendor_amount']; ?></p>
							</div>
							<div class="row" style="float:right; margin-right: 280px; margin-top: 20px;">
								<div class="col-xs-12">

									<div class="col-xs-6" >
										<a class="" onclick="CloseVndPayCall()">	
											<span class="btn btn-info btn-sm mb5 mr5" >Close Call</span>
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
		if ($leadType != 3)
		{
			?>
			<div class="row">
				<div class="col-xs-12">
					<ul class="nav nav-tabs" style="text-align: left;">
						<li class="active"><a href="#first" data-toggle="tab" id="first_tab" onclick="mycalltab(1)">Details</a></li>

					</ul>
				</div>
			</div>
		<?php } ?>
		<div class="tab-content">        
			<div id="first" class="tab-pane fade in active">

				<div class="row">
					<div class="col-xs-12">
						<ul class="nav nav-tabs" id="myTab">							
							<?php
							$i = 1;
							foreach ($leadStatus as $bid => $bval)
							{
								if ($bid != 70 && $showFollowUpOnly)
								{
									continue;
								}
								$label = '';
								unset($params['Booking_page']);
								if (!empty($dataProvider[0]['data']))
								{
									$params = $dataProvider[0]['data']->getPagination()->params;
								}

								$params['tab']		 = $bid;
								$params['userid']	 = $result["bkg_user_id"] | $resultLD["bkg_user_id"];
								$params['userid']	 = $params['userid'] > 0 ? $params['userid'] : "";
								$params['source']	 = 'mycall';
								switch ((int) $bid)
								{

									/* case 80:
									  $params['tab']		 = 1;
									  $params['userid']	 = $result["bkg_user_id"];
									  switch ($leadType)
									  {
									  case 1://LD
									  $tabUrl	 = "data-url=\"" . Yii::app()->createUrl('admin/lead/currentlyAssignedDetails', array('bkg_id' => $result['bkg_id'])) . '"';
									  break;
									  case 2://QT
									  $tabUrl	 = "data-url=\"" . Yii::app()->createUrl('admin/booking/currentlyAssignedDetails', array('bkg_id' => $result['bkg_id'])) . '"';
									  break;
									  }
									  break; */
									case 70: //Follow Up
										$fwpId								 = $model['scq_id'];
										$refId								 = 0;
										$tabUrl								 = "data-url=\"" . Yii::app()->createUrl('admin/scq/list', array('refId' => $refId, 'fwpId' => $fwpId, 'isMycall' => 1)) . '"';
										break;
									default:
										$params['Booking']['bcb_vendor_id']	 = 63850;
										$tabUrl								 = "data-url=\"" . Yii::app()->createUrl('admin/booking/list', $params) . '"';
										break;
								}
								if ($i > 1)
								{
									?>
									<li class='' id="tablist_<?php echo $i; ?>">
										<a data-toggle="tabajax" id="tid_<?php echo $bid ?>" <?php echo $tabUrl ?> class="bg-white" href="#sec<?php echo $bid ?>"><?php echo $bval ?> 
											<span id="bkgCount<?php echo $bid ?>" class="font-bold" style="font-size: 1.2em">
												<?php
												if (in_array($bid, [70, 80]))
												{
													echo $statusCount[$bid];
												}
												?>
											</span>
										</a>
									</li>
									<?php
								}
								$i++;
							}
							?>
							<li  class='active' id="tablist_3"><a data-toggle="tab" id="tid_3" class="bg-white" href="#sec3" aria-expanded="true"><?php echo 'Vendor Details' ?>  </a></li>
							<!--<li  class='' id="tablist_4"><a data-toggle="tab" id="tid_4" class="bg-white" href="#sec4"><?php echo 'Test123' ?>  </a></li>-->
						</ul>
						<div class="tab-content p0" id="details_2">
							<div id="<?php echo 'sec3' ?>" tabid="3" class="tab-pane active">	 
								<?php
								$vndId			 = $model['scq_to_be_followed_up_with_entity_id'];
								$vndmodel		 = Vendors::model()->findByPk($vndId);
								$contactId		 = ContactProfile::getByEntityId($vndId, UserInfo::TYPE_VENDOR);
								$data			 = Vendors::model()->getViewDetailbyId($vndId);
								$vndTripDetails	 = Vendors::getBookingHistoryById($vndId);
								$cabData		 = VendorVehicle::getVehicleListByVndId($vndId);
								$driverData		 = VendorDriver::getDriverListbyVendorid($vndId);
								$docByContactId	 = $contactId != null || $contactId != "" ? Document::model()->getAllDocsbyContact($contactId, 'vendor') : array();
								$vndStats		 = VendorStats::model()->getbyVendorId($vndId);
								$showlog		 = VendorsLog::model()->getByVendorId($vndId);
								$bidLog			 = BookingVendorRequest::model()->getbidbyVnd($vndId);
								$penalty		 = AccountTransDetails::model()->getbyVendorId($vndId);
								$cbrDetails		 = ServiceCallQueue::model()->getCBRDetailbyId($vndId, "Vendor");
								echo $this->renderPartial('../vendor/view', array(
									'model'			 => $vndmodel,
									'mycall'		 => 1,
									'data'			 => $data,
									'cabData'		 => $cabData,
									'driverData'	 => $driverData,
									'vndTripDetails' => $vndTripDetails,
									'docpath'		 => $docByContactId,
									'vndStats'		 => $vndStats,
									'showlog'		 => $showlog,
									'bidLog'		 => $bidLog,
									'penalty'		 => $penalty,
									'cbrdetails'	 => $cbrDetails,
										), true, false);
								?>
							</div>
							<?php
							foreach ($leadStatus as $bid => $bval)
							{
								$tabUrl = "";
								?>
								<div id="<?php echo 'sec' . $bid ?>" tabid="<?php echo $bid ?>" class="tab-pane <?php echo ${'tabactive' . $bid} ?>">

								</div>
								<?php
							}
							?>
							<div id="<?php echo 'sec4' ?>" tabid="4" class="tab-pane">
								<div class="row widget-tab-content mb30">
									<div class="col-xs-12">
										<div class="container">
											Testing123
										</div></div></div>
							</div>
						</div>						
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
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
<div id="selectedQuote"></div>
<script>

    function mycalltab(tabval)
    {
        var tabval = tabval;
        if (tabval == 1)
        {
            $("#current_1").show();
            $("#tablist_6").hide();
            $("#tablist_7").hide();
            $("#tablist_1").show();
            $("#tablist_2").show();
            $("#tablist_3").show();
            $("#tablist_4").show();
            $("#tablist_5").show();
            $("#tablist_8").show();
            $("#tablist_9").hide();

        }

        if (tabval == 2)
        {
            $("#tablist_7").addClass("active");
            $('#tablist_7').trigger("click");
            $("#current_1").hide();
            $("#tablist_1").hide();
            $("#tablist_2").hide();
            $("#tablist_3").hide();
            $("#tablist_4").hide();
            $("#tablist_5").hide();
            $("#tablist_6").show();
            $("#tablist_7").show();
            $("#tablist_8").hide();
            $("#tablist_9").show();

        }
    }
    function CloseVndPayCall()
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
                    $href = '/aaohome/lead/CloseVendorDuePaymentCall';
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








    $(document).ready(function ()
    {
        $('#tabactive_1').addClass('active');
        $("#tablist_6").hide();
        $("#tablist_7").hide();
        $("#tablist_9").hide();

        $('[data-toggle="tabajax"]:first').click();

    });
    $('#myTab a[data-toggle="tabajax"]').click(function (e)
    {

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
<?php
$version			 = Yii::app()->params['customJsVersion'];
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/custom.js?v=' . $version, CClientScript::POS_HEAD);
$time				 = Filter::getExecutionTime();
$GLOBALS['time'][9]	 = $time;
?>