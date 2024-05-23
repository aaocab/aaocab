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
	switch ((int) $model["scq_follow_up_queue_type"])
	{
		case 42:
		case 43:
		case 44:
		case 45:
		case 34:
		case 16:
		case 17:
		case 20:
		case 21:
		case 1:
			switch ((int) $model["scq_ref_type"])
			{
				case 1:
					$result		 = $resultLD;
					$companyName = $model['scq_agent_id'] > 0 ? "(" . Agents::model()->findByPk($model['scq_agent_id'])->agt_company . ")" : "";
					$type		 = "Lead  Booking $companyName";
					$typeId		 = 1;
					break;
				case 2:
					$result		 = $resultQT;
					$companyName = $model['scq_agent_id'] > 0 ? "(" . Agents::model()->findByPk($model['scq_agent_id'])->agt_company . ")" : "";
					$type		 = (in_array($result['bkg_status'], [1, 15]) ) ? "Quoted Booking$companyName" : "Existing Booking";
					$typeId		 = 2;
					break;
				default :
					$calType	 = ServiceCallQueue::getReasonList($model["scq_follow_up_queue_type"]);
					$type		 = "Follow Up - $calType (CALL)";
					$typeId		 = 1;
					break;
			}
			break;

		case 53:
			$result				 = $resultQT;
			$type				 = "VIP/VVIP Booking";
			$typeId				 = 2;
			break;
		case 2:
			$result				 = $resultQT;
			$type				 = (in_array($result['bkg_status'], [1, 15]) ) ? "Quoted Booking" : "Existing Booking";
			$typeId				 = 2;
			break;
		case 3:
			$calType			 = ServiceCallQueue::getReasonList($model["scq_follow_up_queue_type"]);
			$showFollowUpOnly	 = false;
			if ($assignModel['scq_to_be_followed_up_with_type'] == 2)
			{
				$showFollowUpOnly = true;
			}
			$type	 = "Follow Up - $calType (CALL)";
			$typeId	 = 1;

			if ($assignModel['scq_follow_up_queue_type'] == 2)
			{
				$typeId = 2;
			}
			break;
		case 7:
			$result	 = $resultQT;
			$type	 = (in_array($result['bkg_status'], [1, 15]) ) ? "Quoted Booking" : "Existing Booking";
			$typeId	 = 2;
			break;
	}
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

								<p class="mb5"><b>Currently Viewing Assigned Quote/Lead ID:</b> <?php echo $result['bkg_id'] ?></p>
								<p class="mb5"><b>Other Assigned Quoted IDs:</b>
									<?php
									if (empty($assignedQTLeads))
									{
										echo '<b>No Quoted booking has been assigned</b>';
									}
									else
									{
										?>
										<select id="relquote" name="" style="width:30%;height:30px" onchange="detailsbyQuoteid(this.value,<?php echo $typeId ?>)">
											<?php
											foreach ($assignedQTLeads as $leadids)
											{
												?>
												<option value="<?php echo $leadids['bkg_id'] ?>" <?php if ($leadids['bkg_id'] == $result['bkg_id']) echo ' selected="selected"'; ?>><?php echo $leadids['bkg_booking_id'] ?></option>
											<?php } ?>
										</select>
										<?php
									}
									?>
								</p>
								<p class="mb5"><b>Other Assigned Lead IDs:</b>
									<?php
									if (empty($assignedLDLeads))
									{
										echo '<b>No lead booking has been assigned</b>';
									}
									else
									{
										?>
										<select id="" name="" style="width:30%;height:30px" onchange="detailsbyleadid(this.value,<?php echo $typeId ?>)">
											<?php
											foreach ($assignedLDLeads as $leadids)
											{
												?>
												<option value="<?php echo $leadids['bkg_id'] ?>" <?php if ($leadids['bkg_id'] == $result['bkg_id']) echo ' selected="selected"'; ?>><?php echo $leadids['bkg_booking_id'] ?></option>
											<?php } ?>
										</select>
										<?php
									}
									?>
								</p>
							</div>

							<input type="hidden" name="ldType1" id="ldType1" value="<?php
							echo $result["bkg_user_id"];
							echo $csr;
							?>">
							<input type="hidden" name="ldType" id="ldType" value="<?php echo $leadType ?>">
							<input type="hidden" name="ldBkgId" id="ldBkgId" value="<?php echo $result['bkg_id'] ?>">

							<div class="row" style="float:right; margin-right: 280px; margin-top: 20px;">
								<div class="col-xs-12">

									<?php
									if ($leadType == 1)
									{
										?>	
										<div class="col-xs-6" >
											<a class="addfollowup">	
												<span class="btn btn-info btn-sm mb5 mr5" >Add Remarks</span>
											</a>
										</div>
										<div class="col-xs-6" >
											<a class="" onclick="closeCall('<?php echo $result["bkg_user_id"]; ?>', '<?php echo $csr; ?>', '<?php echo $result['bkg_id']; ?>')">	
												<span class="btn btn-info btn-sm mb5 mr5" >Close Call</span>
											</a>
										</div>		

										<?php
									}
									if ($leadType == 2 || $leadType == 3)
									{
										if ($leadType == 2)
										{
											?>
											<div class="col-xs-6" >
												<a>	
													<span class="btn btn-info btn-sm mb5 mr5" onclick="callQuoteRemarks(<?php echo $result['bkg_id'] ?>)">Add Remarks</span>
												</a>
											</div><?php }
										?>
										<div class="col-xs-6" >
											<a class="" onclick="closeCall('<?php echo $result["bkg_user_id"]; ?>', '<?php echo $csr; ?>', '<?php echo $result['bkg_id']; ?>')">	
												<span class="btn btn-info btn-sm mb5 mr5" >Close Call</span>
											</a>
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
		</div>
		<?php
		if ($leadType != 3)
		{
			?>
			<div class="row">
				<div class="col-xs-12">
					<ul class="nav nav-tabs" style="text-align: left;">
						<li class="active"><a href="#first" data-toggle="tab" id="first_tab" onclick="mycalltab(1)">Details</a></li>
						<li><a href="#first" data-toggle="tab" id="first_tab" onclick="mycalltab(2)">Remarks</a></li>
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

									case 30:
										$params['tab']		 = 1;
										$params['userid']	 = $result["bkg_user_id"] > 0 ? $result["bkg_user_id"] : "0";
										$params['mycall']	 = 1;
										$tabUrl				 = "data-url=\"" . Yii::app()->createUrl('admin/lead/report', $params) . '"';
										break;

									case 50:
										$tabUrl = "data-url=\"" . Yii::app()->createUrl('admin/booking/showlog', array('booking_id' => $result['bkg_id'], 'hash' => $result["bkg_user_id"])) . '"';
										break;

									case 60:
										$tabUrl	 = "data-url=\"" . Yii::app()->createUrl('admin/lead/showlog', array('booking_id' => $result['bkg_id'], 'hash' => $leadType)) . '"';
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
											if (in_array($bid, [10, 20, 30, 40, 70, 80]))
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

				<div class="row">
					<?php
					$csrid	 = UserInfo::getUserId();
					$teamId	 = Teams::getMultipleTeamid($csrid);
					foreach ($teamId as $teamId)
					{

						$teamArrList = array("3", "4", "41", "27", "28", "29", "30", "36");
						if (in_array($teamId['tea_id'], $teamArrList))
						{
							?>
							<div class="col-xs-3">
								<a href="<?= Yii::app()->createUrl('admin/lead/allocatedLeadByTeam', array('team' => $teamId['tea_id'])); ?>" class="btn btn-primary mt5"  style="width: 185px;"><?php echo $teamId['tea_name']; ?></a>
							</div>
							<?php
						}
					}
					?>
					<div class="col-xs-3">
						<a href="<?= Yii::app()->createUrl('admin/lead/allocatedLeadByTeam', array('team' => 1)); ?>" class="btn btn-primary mt5"  style="width: 185px;">Retail Sales</a>

					</div>
					<div class="col-xs-3">

						<a href="<?= Yii::app()->createUrl('admin/lead/allocatedLeadByTeam', array('team' => 51)); ?>" class="btn btn-primary mt5"  style="width: 185px;">Vendor Due Amount</a>
					</div>
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


        function detailsbyQuoteid(bkg_id, typeid)
        {
            var bkg_id = bkg_id;
            var typeid = typeid;
            $href = '/admpnl/booking/CurrentlyAssignedDetails';
            if (typeid == 2)
            {
                $href = '/admpnl/booking/CurrentlyAssignedDetails';
            }
            $('#selectedQuote').text(bkg_id);
            jQuery.ajax({type: 'GET', url: $href, dataType: 'html', data: {"bkg_id": bkg_id},
                success: function (data)
                {
                    $("#current_1").html(data);
                    $("#sec80").html(data);

                }});
        }
        function detailsbyleadid(bkg_id, typeid)
        {
            var bkg_id = bkg_id;
            var typeid = typeid;
            $href = '/admpnl/lead/CurrentlyAssignedDetails';
            if (typeid == 2)
            {
                $href = '/admpnl/lead/CurrentlyAssignedDetails';
            }

            jQuery.ajax({type: 'GET', url: $href, dataType: 'html', data: {"bkg_id": bkg_id},
                success: function (data)
                {
                    $("#current_1").html(data);
                    $("#sec80").html(data);

                }});
        }

        function closeCall(userid, csrid, bkgid)
        {
            var userid = userid;
            var csrid = csrid;
            var bkgid = bkgid;
            var answer = window.confirm("Are you sure want to close call?")
            if (answer)
            {
                if (csrid != '')
                {
                    $href = '/admpnl/lead/CloseCall';
                    jQuery.ajax({type: 'GET', url: $href, dataType: 'json', data: {"userid": userid, "csrid": csrid, "bkgid": bkgid},
                        success: function (data)
                        {
                            if (data.success == false)
                            {
                                alert("You have a pending quote/lead. Please close all pending quote and pending lead.");
                            } else
                            {
                                alert("Successfully call closed.");
                                window.location.reload();//location.reload(true);
                            }

                        }});
                }

            }
        }

        var bkgid = '<?php echo $result['bkg_id'] ?>';
        $('.addfollowup').click(function ()
        {
            var booking_id = bkgid;

            if (booking_id == "" || booking_id == null)
            {
                return false;
            }

            $href = '/admpnl/lead/addfollowup';
            jQuery.ajax({type: 'GET', url: $href, data: {"bkg_id": booking_id},
                success: function (data)
                {
                    box = bootbox.dialog({
                        message: data,
                        // title: 'Add Contact',
                        size: 'large',
                        onEscape: function ()
                        {
                            $('.bootbox.modal').modal('hide');
                        },
                    });
                }});
        });

        $('.leadfollow').click(function ()
        {
            var booking_id = bkgid;
            if (booking_id == "" || booking_id == null)
            {
                return false;
            }

            $href = '/admpnl/lead/leadfollow';
            jQuery.ajax({type: 'GET', url: $href, data: {"bkg_id": booking_id},
                success: function (data)
                {
                    box = bootbox.dialog({
                        message: data,
                        //title: 'Add Contact',
                        size: 'large',
                        onEscape: function ()
                        {
                            $('.bootbox.modal').modal('hide');
                        },
                    });
                }});
        });



        let lgID = 0;
        function delOneminlog()
        {
            $("#isAddRemark").val("1");
            $href = "<?php echo Yii::app()->createUrl('admin/booking/deloneminutelog') ?>";
            var booking_id = bkgid;
            if (booking_id == "" || booking_id == null)
            {
                return false;
            }
            jQuery.ajax({type: 'GET',
                dataType: 'json',
                url: $href,
                data: {"booking_id": booking_id, "logID": lgID},
                success: function (data)
                {
                    //  alert(data);
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

        var start = '<?= date('d/m/Y', strtotime('-1 month')); ?>';
        var end = '<?= date('d/m/Y'); ?>';
        $('#bkgCreateDate').daterangepicker({
            locale: {
                format: 'DD/MM/YYYY',
                cancelLabel: 'Clear'
            },
            "showDropdowns": true,
            "alwaysShowCalendars": true,
            startDate: start,
            endDate: end,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            }
        }, function (start1, end1) {
            $('#Booking_bkg_create_date1').val(start1.format('YYYY-MM-DD'));
            $('#Booking_bkg_create_date2').val(end1.format('YYYY-MM-DD'));
            $('#bkgCreateDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
        });
        $('#bkgCreateDate').on('cancel.daterangepicker', function (ev, picker) {
            $('#bkgCreateDate span').html('Select Booking Date Range');
            $('#Booking_bkg_create_date1').val('');
            $('#Booking_bkg_create_date2').val('');
        });
        $('#bkgPickupDate').daterangepicker({
            locale: {
                format: 'DD/MM/YYYY',
                cancelLabel: 'Clear'
            },
            "showDropdowns": true,
            "alwaysShowCalendars": true,
            startDate: start,
            endDate: end,
            ranges: {
                'Today': [moment(), moment()],
                'Tomorrow': [moment().add(1, 'days'), moment().add(1, 'days')],
                'Next 7 Days': [moment(), moment().add(6, 'days')],
                'Next 15 Days': [moment(), moment().add(15, 'days')],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Next Month': [moment().add(1, 'month').startOf('month'), moment().add(1, 'month').endOf('month')],
            }
        }, function (start1, end1) {
            $('#Booking_bkg_pickup_date1').val(start1.format('YYYY-MM-DD'));
            $('#Booking_bkg_pickup_date2').val(end1.format('YYYY-MM-DD'));
            $('#bkgPickupDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
        });
        $('#bkgPickupDate').on('cancel.daterangepicker', function (ev, picker) {
            $('#bkgPickupDate span').html('Select Pickup Date Range');
            $('#Booking_bkg_pickup_date1').val('');
            $('#Booking_bkg_pickup_date2').val('');
        });
        var checkCounter = 0;
        var checked = [];
        function setMarkComplete()
        {
            checked = [];
            $('#bookingTab5 input[name="booking_id5[]"]').each(function (i)
            {
                if (this.checked)
                {
                    checked.push(this.value);
                }
            });

            if (checked.length == 0) {
                bootbox.alert("Please select a booking for mark complete.");
                return false;
            }
            if (checked.length > 0) {
                var j = 0;
                var checked1 = [];
                while (j < 10 && checkCounter < checked.length) {
                    checked1.push(checked[checkCounter]);
                    j++;
                    checkCounter++;
                }
                markCompleteAjax(checked1);
            }
        }
        function markCompleteAjax(checkedIds)
        {
            ajaxindicatorstart("Processing " + checkCounter.toString() + " of " + checked.length.toString() + "");
            var href = '<?= Yii::app()->createUrl("admin/booking/setcompletebooking"); ?>';
            $.ajax({
                'type': 'GET',
                'url': href,
                'dataType': 'json',
                global: false,
                data: {"bkIds": checkedIds.toString()},
                success: function (data) {
                    if (data.success) {
                        if (checkCounter >= checked.length)
                        {
                            ajaxindicatorstop();
                            checkCounter = 0;
                            updateGrid(5);
                            removeTabCache(6);
                        } else
                        {
                            setMarkComplete();
                        }
                    } else {
                        ajaxindicatorstop();
                        checkCounter = 0;
                        alert("Sorry error occured");
                    }
                },
                error: function (xhr, status, error) {
                    ajaxindicatorstop();
                    checkCounter = 0;
                    alert(xhr.error);
                }
            });

        }

        function autoAssignment(zoneId)
        {
            $href = "<?= Yii::app()->createUrl('admin/booking/autoAssignment') ?>";
            jQuery.ajax({type: 'GET',
                url: $href,
                'data': {'zoneId': zoneId},
                'dataType': 'json',
                success: function (data)
                {
                    if (data.success)
                    {
                        bootbox.alert(data.BookingName + " is assigned for your vendor assignment."),
                                location.href = data.url;
                    }
                }
            });
        }

        function autoAssign(obj)
        {
            $href = $(obj).attr("href");
            $.ajax({
                url: $href,
                type: 'GET',
                "dataType": "json",
                success: function (result) {
                    window.location = result.url;
                },
                error: function (xhr, status, error) {
                    alert('Sorry error occured');
                }
            });
            return false;
        }

        function populateSource(obj, cityId)
        {

            obj.load(function (callback)
            {
                var obj = this;
                if ($sourceList == null)
                {
                    xhr = $.ajax({
                        url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery', ['apshow' => 1, 'city' => ''])) ?>' + cityId,
                        dataType: 'json',
                        success: function (results)
                        {
                            $sourceList = results;
                            obj.enable();
                            callback($sourceList);
                            obj.setValue(cityId);
                        },
                        error: function ()
                        {
                            callback();
                        }
                    });
                } else
                {
                    obj.enable();
                    callback($sourceList);
                    obj.setValue(cityId);
                }
            });
        }

        function loadSource(query, callback)
        {
            //	if (!query.length) return callback();
            $.ajax({
                url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery')) ?>?apshow=1&q=' + encodeURIComponent(query),
                type: 'GET',
                dataType: 'json',
                global: false,
                error: function ()
                {
                    callback();
                },
                success: function (res)
                {
                    callback(res);
                }
            });
        }
	</script>

	<script type="text/javascript">
        var csrBox;
        $(document).ready(function ()
        {


            //--- changed 1311 --///
            var start = '<?= date('d/m/Y'); ?>';
            //var startval = '<? ($model->bkg_create_date1 == '') ? '' : DateTimeFormat::DateToDatePicker($model->bkg_create_date1) ?>';
            var end = '<?= date('d/m/Y'); ?>';
            //var endval = '<? ($model->bkg_create_date2 == '') ? '' : DateTimeFormat::DateToDatePicker($model->bkg_create_date2) ?>';

            //$('#BookingTemp_bkg_create_date1').val(startval);
            //$('#BookingTemp_bkg_create_date2').val(endval);

            $('#bkgCreateDate').daterangepicker(
                    {
                        locale: {
                            format: 'DD/MM/YYYY',
                            cancelLabel: 'Clear'
                        },
                        "showDropdowns": true,
                        "alwaysShowCalendars": true,
                        startDate: start,
                        endDate: end,
                        ranges: {
                            'Today': [moment(), moment()],
                            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                            'This Month': [moment().startOf('month'), moment().endOf('month')],
                            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                        }
                    }, function (start1, end1) {
                $('#BookingTemp_bkg_create_date1').val(start1.format('DD/MM/YYYY'));
                $('#BookingTemp_bkg_create_date2').val(end1.format('DD/MM/YYYY'));
                $('#bkgCreateDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
            });
            $('#bkgCreateDate').on('cancel.daterangepicker', function (ev, picker) {
                $('#bkgCreateDate span').html('Select Create Date Range');
                $('#BookingTemp_bkg_create_date1').val('');
                $('#BookingTemp_bkg_create_date2').val('');
            });
            $('#bkgPickupDate').daterangepicker(
                    {
                        locale: {
                            format: 'DD/MM/YYYY',
                            cancelLabel: 'Clear'
                        },
                        "showDropdowns": true,
                        "alwaysShowCalendars": true,
                        startDate: start,
                        endDate: end,
                        ranges: {
                            'Today': [moment(), moment()],
                            'Tomorrow': [moment().add(1, 'days'), moment().add(1, 'days')],
                            'Next 7 Days': [moment(), moment().add(6, 'days')],
                            'Next 15 Days': [moment(), moment().add(15, 'days')],
                            'All upcoming': [moment(), moment().add(11, 'month')],
                            'This Month': [moment().startOf('month'), moment().endOf('month')],
                            'Next Month': [moment().add(1, 'month').startOf('month'), moment().add(1, 'month').endOf('month')],
                        }
                    }, function (start1, end1) {
                $('#BookingTemp_bkg_pickup_date1').val(start1.format('DD/MM/YYYY'));
                $('#BookingTemp_bkg_pickup_date2').val(end1.format('DD/MM/YYYY'));
                $('#bkgPickupDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
            });
            $('#bkgPickupDate').on('cancel.daterangepicker', function (ev, picker) {
                $('#bkgPickupDate span').html('Select Pickup Date Range');
                $('#BookingTemp_bkg_pickup_date1').val('');
                $('#BookingTemp_bkg_pickup_date2').val('');
            });

        });

        function updateGrid(id)
        {
            if ($.fn.yiiGridView != undefined && $.fn.yiiGridView.settings['leadGrid' + id] != undefined) {
                $url = $('#leadGrid' + id).yiiGridView('getUrl');
                $('#sec' + id).load($url);
                //          addTabCache(id);
            }
        }

        function assignCSR(obj, tab = 1)
        {
            $href = $(obj).attr('href');
            jQuery.ajax(
                    {
                        type: 'GET',
                        "dataType": "json",
                        url: $href,
                        success: function (data1)
                        {
                            csrBox.remove();
                            updateGrid(tab);
                        }
                    });
            return false;
        }

        function addCsr(obj)
        {
            try
            {
                $href = $(obj).attr("href");
                jQuery.ajax({type: "GET", "dataType": "html", url: $href, success: function (data)
                    {
                        csrBox = bootbox.dialog({
                            message: data,
                            title: "Assign CSR",
                            className: "bootbox-lg",
                        });
                    }});
            } catch (e)
            {
                alert(e);
            }
            return false;
        }

        function changeLock(obj, type, tab)
        {
            var con = confirm("Do you want to " + type + " this lead?");
            if (con)
            {
                $href = $(obj).attr('href');
                $.ajax(
                        {
                            url: $href,
                            success: function (result)
                            {
                                if (result != null && result != "")
                                {
                                    if (result.trim() == "true") {
                                        updateGrid(tab);
                                    } else {
                                        alert('Sorry error occured');
                                    }
                                }
                            },
                            error: function (xhr, status, error)
                            {
                                alert('Sorry error occured');
                            }
                        });
            }
            return false;
        }


        function follow(obj)
        {
            try
            {
                $href = $(obj).attr("href");
                jQuery.ajax({type: "GET", "dataType": "html", url: $href, success: function (data)
                    {
                        csrBox = bootbox.dialog({
                            message: data,
                            title: "Lead follow up",
                            size: 'large',
                        });
                    }});
            } catch (e)
            {
                alert(e);
            }
            return false;
        }
        function followUp(obj)
        {
            try
            {
                $href = $(obj).attr("href");
                jQuery.ajax({type: "GET", "dataType": "html", url: $href, success: function (data)
                    {
                        bootbox.dialog({
                            message: data,
                            size: 'large',
                            title: "Add follow up",
                        });
                    }});
            } catch (e)
            {
                alert(e);
            }
            return false;
        }

        function showLog(obj)
        {
            try
            {
                $href = $(obj).attr("href");
                jQuery.ajax({type: "GET", "dataType": "html", url: $href, success: function (data)
                    {
                        bootbox.dialog({
                            message: data,
                            title: "Show Log",
                            className: "bootbox-lg",
                            callback: function () {
                            },
                        });
                    }});
            } catch (e)
            {
                alert(e);
            }
            return false;
        }

        function autoAssign(obj)
        {
            $href = $(obj).attr("href");
            $.ajax({
                url: $href,
                type: 'GET',
                "dataType": "json",
                success: function (result) {
                    window.location = result.url;
                },
                error: function (xhr, status, error) {
                    alert('Sorry error occured');
                }
            });
            return false;
        }

        function markRead(obj)
        {
            var con = confirm("Are you sure you want to mark this read?");
            if (con) {
                $href = $(obj).attr("href");
                $.ajax({
                    url: $href,
                    success: function (result) {
                        if (result != null && result != "")
                        {
                            if (result.trim() == "true") {
                                updateGrid('.$status.');
                            } else {
                                alert('Sorry error occured');
                            }
                        }
                    },
                    error: function (xhr, status, error) {
                        alert('Sorry error occured');
                    }
                });
            }
            return false;
        }

        function markInvalid(obj)
        {
            try
            {
                $href = $(obj).attr("href");
                jQuery.ajax({type: "GET", "dataType": "html", url: $href, success: function (data)
                    {
                        bootbox.dialog({
                            message: data,
                            title: "Mark Invalid",
                            callback: function () {
                            },
                        });
                    }});
            } catch (e)
            {
                alert(e);
            }
            return false;
        }

        function showRelated(obj)
        {
            try
            {
                $href = $(obj).attr("href");
                jQuery.ajax({type: "GET", "dataType": "html", url: $href, success: function (data)
                    {
                        bootbox.dialog({
                            message: data,
                            title: "Related Leads",
                            size: 'large',
                            callback: function () {
                            },
                        });
                    }});
            } catch (e)
            {
                alert(e);
            }
            return false;
        }

        $sourceList = null;
        function populateSource(obj, cityId)
        {

            obj.load(function (callback) {
                var obj = this;
                if ($sourceList == null) {
                    xhr = $.ajax({
                        url: '<?php echo CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery', ['apshow' => 1, 'city' => ''])) ?>' + cityId,
                        dataType: 'json',
                        data: {
                            // city: cityId
                        },
                        //  async: false,
                        success: function (results) {
                            $sourceList = results;
                            obj.enable();
                            callback($sourceList);
                            obj.setValue(cityId);
                        },
                        error: function () {
                            callback();
                        }
                    });
                } else {
                    obj.enable();
                    callback($sourceList);
                    obj.setValue(cityId);
                }
            });
        }
        function loadSource(query, callback) {
            //	if (!query.length) return callback();
            $.ajax({
                url: '<?php echo CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery')) ?>?apshow=1&q=' + encodeURIComponent(query),
                type: 'GET',
                dataType: 'json',
                global: false,
                error: function () {
                    callback();
                },
                success: function (res) {
                    callback(res);
                }
            });
        }
        function callQuoteRemarks(qtid) {
            var bkgid = $('#relquote').val();
            if (bkgid == undefined)
            {

                bkgid = qtid;
            }
            adminAction(21, bkgid, '', 1);
        }

        function autoAllocateLead(admId, type)
        {
            $href = '/admpnl/admin/autoAllocateLead';
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