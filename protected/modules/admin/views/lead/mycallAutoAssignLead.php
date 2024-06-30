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
if ($model["scq_id"] != '')
{
	$result	 = $resultQT;
	$type	 = "Auto Lead Followup";
	$typeId	 = 2;
	?>
	<div class="">
		<div class="row">
			<div class="col-xs-12">
				<div class="panel panel-default main-tab1">
					<div class="panel-body panel-border">
						<div class="row">
							<div class="col-xs-12 col-lg-7">
								<p class="mb5"><b>Name:</b>   <?php echo $result['bkg_user_fname'] . ' ' . $result['bkg_user_lname']; ?></p>
								<p class="mb5"><b>Account:</b></p>
								<p class="mb5"><b>Joining Date:</b>   <?php echo $result['usr_created_at'] ?></p>
								<p class="mb5"><b>Total Inquiries:</b> <?php echo $totalEnquiry[0]['cnt']; ?></p>
								<p class="mb5"><b>Trip Completed:</b>
									<?php echo ($totalTripComplete[0]['cnt7day'] > 0) ? $totalTripComplete[0]['cnt7day'] : 0; ?>
									(7D) | <?php echo ($totalTripComplete[0]['cnt30day'] > 0) ? $totalTripComplete[0]['cnt30day'] : 0; ?>
									(1M) | <?php echo ($totalTripComplete[0]['cnt90day'] > 0) ? $totalTripComplete[0]['cnt90day'] : 0; ?>
									(3M) | <?php echo ($totalTripComplete[0]['cnt'] > 0) ? $totalTripComplete[0]['cnt'] : 0; ?>
									(Lifetime)
								</p>

								<p class="mb5"><b>Tiers Used:</b>  
									<?php
									if (count($totalTireUsed) > 0)
									{
										foreach ($totalTireUsed as $val)
										{
											echo $val['scc_label'] . ' ';
											echo '(' . $val['cnt'] . ') | ';
										}
									}
									else
									{
										echo "0";
									}
									?>
								</p>	
								<p class="mb5"><b>Cities traveled from/to:</b>
									<?php
									if (count($cityTraveled) > 0)
									{
										$l = 0;
										foreach ($cityTraveled as $value)
										{
											if ($l == 20)
											{
												echo "...";
												break;
											}
											else
											{
												echo $value['city_name'];
											}
											$l++;
										}
									}
									else
									{
										echo "No Cities Found";
									}
									?>
								</p>
							</div>
							<div class="col-xs-12 col-lg-5">
								<p class="mb5"><b>Call Queue:</b>  <?php echo $type ?></p>
								<p class="mb5"><b>Call request received at:</b>  <?php echo $result['bkg_create_date'] ?></p>

								<p class="mb5"><b>Ratings received:</b> Count (<?php
									if (count($userDriverRating > 0))
									{
										echo $userDriverRating['cntcustomer'];
									}
									else
									{
										echo "0";
									}
									?>) | Avg Rating (<?php
									if (count($userDriverRating > 0))
									{
										echo $userDriverRating['rtg_customer_overall'];
									}
									else
									{
										echo "0";
									}
									?>)</p>
								<p class="mb5"><b>Rating given:</b> Count (<?php
									if (count($userDriverRating > 0))
									{
										echo $userDriverRating['cntdriver'];
									}
									else
									{
										echo "0";
									}
									?>) | Avg Rating (<?php
									if (count($userDriverRating > 0))
									{
										echo $userDriverRating['rtg_customer_driver'];
									}
									else
									{
										echo "0";
									}
									?>)</p>

								<p class="mb5"><b>Currently Viewing Booking ID:</b> <?php echo $result['bkg_id'] ?></p>


							</div>



							<div class="row" style="float:right; margin-right: 280px; margin-top: 20px;">
								<div class="col-xs-12">

									<div class="col-xs-6" >
										<a class="" onclick="CloseAutoAssignLead()">	
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
									case 80:
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
											case 3:
												if ($model['scq_related_bkg_id'] > 0)
												{
													$tabUrl = "data-url=\"" . Yii::app()->createUrl('admin/booking/currentlyAssignedDetails', array('bkg_id' => $model['scq_related_bkg_id'])) . '"';
												}
												else
												{
													if ($model['scq_to_be_followed_up_with_contact'] > 0)
													{
														$tabUrl = "data-url=\"" . Yii::app()->createUrl('admin/contact/view', array('ctt_id' => $result["contact_id"])) . '"';
													}
												}
												break;
										}
										break;
									case 70: //Follow Up
										$fwpId	 = $model['scq_id'];
										$refId	 = 0;
										$tabUrl	 = "data-url=\"" . Yii::app()->createUrl('admin/scq/list', array('refId' => $refId, 'fwpId' => $fwpId, 'isMycall' => 1)) . '"';
										break;
									default:
										$tabUrl	 = "data-url=\"" . Yii::app()->createUrl('admin/booking/list', $params) . '"';
										break;
								}
								?>
								<li class='<?php echo ${"tabactive" . $bid} ?>' id="tablist_<?php echo $i; ?>">
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
								$i++;
							}
							?>

						</ul>
						<div class="tab-content p0" id="details_2">

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
    function CloseAutoAssignLead()
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
                    $href = '/aaohome/lead/CloseAutoAssignLead';
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