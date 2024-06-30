<style type="text/css">
    @media (min-width: 992px){
        .modal-lg {
            width: 95%!important;
        }
    }
    @media (min-width: 768px){
        .modal-lg {
            width: 100%;
        }
    }
    .bordered {
        border: 1px solid #ddd;
        min-height: 45px;
        line-height: 1.2em;
        margin-bottom: 10px;
        margin-left: 10px;
        margin-right: 10px;
        padding-bottom: 10px;
    }       
    .scroll {             
        height: 250px;
        overflow-x: hidden;
        overflow-y: auto;
        text-align:justify;
    }
</style>
<?php
if ($agtData != null)
{
$name = $agtData["agt_fname"] . '' . $agtData["agt_lname"];
$orgName =  ($agtData["agt_company"] == "" ? $name : $agtData["agt_company"]);
	?>
	<div class="row widget-tab-content mb30">
		<div class="col-xs-12">
			<div class="container">
				<div class="row">
					<div class="col-xs-12 col-sm-4 col-lg-3">
						<!-- Nav tabs -->
						<div class="widget-tab-box mb30">
							<ul class="nav nav-tabs" role="tablist" id="viewId">
								<li role="presentation" class="p15 pl20 ml5"><b>Driver's Information</b></li>
								<li role="presentation" class="active"><a href="#agentDetails" aria-controls="agentDetails" role="tab" data-toggle="tab">Dashboard</a></li>
								<li role="presentation" id="notificationsli"><a href="#notifications" aria-controls="notifications" role="tab" data-toggle="tab">Notification Settings</a></li>
								<li role="presentation" id="logsli"><a href="#logs" aria-controls="logs" role="tab" data-toggle="tab">Show Log</a></li>
								<li role="presentation" id="documentsli"><a href="#documents" aria-controls="documents" role="tab" data-toggle="tab">Documents</a></li>
								<li role="presentation"><a href="<?php echo Yii::app()->createUrl("/aaohome/agent/form", array('agtid' => $agentId)); ?>" aria-controls="editAgent" target="_blank">Edit Agent</a></li>
							</ul>
						</div>
					</div>
					<div class="col-xs-12 col-sm-8 col-lg-9">
						<!-- Tab panes -->
						<div class="widget-tab-box">
							<div class="tab-content">
								<div role="tabpanel" class="tab-pane tabHide active" id="agentDetails">
									<div class="panel">
										<div class="panel-heading p0 pt5">
											<?= $orgName; ?> (<?= Agents::model()->getAgentType($agtData['agt_type']); ?>)
										</div>
										<div class="panel-body p0 pt20">
											<div class="row">
												<div class="col-xs-12 mb30">
													<?php
													if ($agtData['agt_active'] == 1)
													{
														echo ' <span class="btn-5 mr15">Active</span>';
													}
													else
													{
														echo ' <span class="btn-4 mr15">Inactive</span>';
													}
													$isApprove = 'btn-4 mr15';
													if ($agtData['agt_approved'] == 1)
													{
														$isApprove = 'btn-5 mr15';
													}
													?>
													<span class="<?= $isApprove ?>"><?= $agtData['approve_status']; ?></span>	

												</div>
												<div class="col-xs-12">
													<div class="row">
														<div class="col-xs-12 col-md-8 mb20">
															<div class="widget-tab-box2">
																<div class="row mb20">
																	<div class="col-xs-12 col-md-9 pr5">
																		<h1 class="mb5"><i class="fas fa-user"></i> Agent Details</h1>
																		<?php
																		$date2			 = date_create(date("Y-M-d"));
																		$date1			 = date_create(date("Y-M-d", strtotime($agtData['agt_create_date'])));
																		$diff2			 = date_diff($date1, $date2);
																		$datediff		 = $diff2->format("%R%a days");
																		$years			 = round($datediff / 365);
																		$companyTypeVal	 = Agents::model()->getCompanyType($agtData['agt_company_type']);
																		?>
																		<p class="color-gray">DOJ: <b><?= date('d M Y', strtotime($agtData['agt_create_date'])); ?></b> | 
																			<b><?= $years ?>+</b> year old</p>
																	</div>  
																</div> 
																<div class="row mb10">
																	<div class="col-xs-6">
																		<p class="mb0 color-gray">Agent ID:</p>
																		<p class="font-14"><b><?= $agtData['agt_agent_id']; ?></b></p>
																	</div>
																	<div class="col-xs-6">
																		<p class="mb0 color-gray">Company Name:</p>
																		<p class="font-14"><b><?= $agtData['agt_company']; ?><br></b><?= ($agtData['agt_company_type'] > 0) ? ' (' . $companyTypeVal . ')' : ''; ?></p>
																	</div>
																	<div class="col-xs-6" style="word-wrap: break-word;">
																		<p class="mb0 color-gray">Address:</p>
																		<p class="font-14"><b><?= $agtData['agt_address'] ?></b></p>
																	</div>
																	<div class="col-xs-6">
																		<p class="mb0 color-gray">City:</p>
																		<p class="font-14"><b>
																				<?= $agtData['agt_city_name']; ?>
																			</b></p>
																	</div>
																	<div class="col-xs-6">
																		<p class="mb0 color-gray">Email Id:</p>
																		<p class="font-14"><b><?= $cttEmail ?></b></p>
																	</div>
																	<?
																	if ($agtData['agt_email_two'] != '')
																	{
																		?>
																		<div class="col-xs-6">
																			<p class="mb0 color-gray">Alt Email Id:</p>
																			<p class="font-14"><b><?= $agtData['agt_email_two'] ?></b></p>
																		</div>
																	<? } ?>

																</div>

																<div class="row mb10">
																	<div class="col-xs-6">
																		<p class="mb0 color-gray">Contact</p>
																		<p class="font-14">
																			<?php
																				Filter::parsePhoneNumber($cttPhone, $code, $number);
																			?>
																			<B><?= ($cttPhone == '') ? '' : '+' . $code.$number ?></B>
																		</p>
																	</div>
																	<?
																	if ($agtData['agt_phone_two'] != '' && $agtData['agt_phone_three'] != '')
																	{
																		?>
																		<div class="col-xs-6">
																			<p class="mb0 color-gray">Alt Contact:</p>
																			<p class="font-14">
																				<B><?= $agtData['agt_phone_two'] . '/' . $agtData['agt_phone_three']; ?></B>
																			</p>
																		</div>
																		<?
																	}
																	else if ($agtData['agt_phone_two'] != '' || $agtData['agt_phone_three'] != '')
																	{
																		?>
																		<div class="col-xs-6">
																			<p class="mb0 color-gray">Alt Contact</p>
																			<p class="font-14">
																				<B><?= $agtData['agt_phone_two'] . $agtData['agt_phone_three'] ?></B>
																			</p>
																		</div>
																	<? } ?>
																	<div class="col-xs-6">
																		<p class="mb0 color-gray">License Expiry Date</p>
																		<p class="font-14">
																			<B><?= ($agtData['agt_license_expiry_date'] != '' && $agtData['agt_license_expiry_date'] != NULL) ? date('d/m/Y h:i A', strtotime($agtData['agt_license_expiry_date'])) : '-'; ?> </b>
																		</p>
																	</div>
																	<div class="col-xs-6">
																		<p class="mb0 color-gray">Approved By</p>
																		<p class="font-14">
																			<B><?= $agtData['approve_by_name']; ?></B>
																		</p>
																	</div>

																</div>


																<div class="row mb10">
																	<div class="col-xs-12 mb30"> 
																		<h2>Bank Details</h2>
																		<div class="col-xs-6">
																			<div class="row">
																				<div class="col-xs-12 pl0">
																					<p class="mb0 color-gray lineheight20">Bank name : <b><?= $agtData['agt_bank']; ?></b></p>
																				</div>
																			</div>
																		</div>
																		<div class="col-xs-6">
																			<div class="row">
																				<div class="col-xs-12 pl0">
																					<p class="mb0 color-gray lineheight20">IFSC Code : <b><?= $agtData['agt_ifsc_code']; ?></b></p>
																				</div>
																			</div>
																		</div>
																		<div class="col-xs-6">
																			<div class="row">
																				<div class="col-xs-12 pl0">
																					<p class="mb0 color-gray lineheight20">Bank Branch : <b><?= $agtData['agt_branch_name']; ?></b></p>
																				</div>
																			</div>
																		</div>
																		<div class="col-xs-6">
																			<div class="row">
																				<div class="col-xs-12 pl0">
																					<p class="mb0 color-gray lineheight20">Swift code : <b><?= $agtData['agt_swift_code']; ?></b></p>
																				</div>
																			</div>
																		</div>
																		<div class="col-xs-6">
																			<div class="row">
																				<div class="col-xs-12 pl0">
																					<p class="mb0 color-gray lineheight20">Account no. : <b><?= $agtData['agt_bank_account']; ?></b></p>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>

																<div class="row mb10">
																	<div class="col-xs-12 mb30"> 
																		<h2>Booking Details</h2>
																		<div class="col-xs-6">
																			<div class="row">
																				<div class="col-xs-12 pl0">
																					<p class="mb0 color-gray lineheight20">Total Booking Credit Applied: : <b><i class="fa fa-inr"></i><?= $agtData['totCredit'] | 0; ?></b></p>
																				</div>
																			</div>
																		</div>
																		<div class="col-xs-6">
																			<div class="row">
																				<div class="col-xs-12 pl0">
																					<p class="mb0 color-gray lineheight20">Total Transaction amount : <b><i class="fa fa-inr"></i><?= $agtData['transaction_amount'] | 0; ?></b></p>
																				</div>
																			</div>
																		</div>
																		<?
																		$payto = '';
																		if ($agtData['agtPayable'] == 0)
																		{
																			$payto = '';
																		}
																		else if ($agtData['agtPayable'] < 0)
																		{
																			$payto = ' to Partner';
																		}
																		else
																		{
																			$payto = ' from Partner';
																		}
																		?>
																		<div class="col-xs-6">
																			<div class="row">
																				<div class="col-xs-12 pl0">
																					<p class="mb0 color-gray lineheight20">Payable <?= $payto ?> : <b><i class="fa fa-inr"></i><?= $agtData['agtPayable'] | 0; ?></b></p>
																				</div>
																			</div>
																		</div>
																		<div class="col-xs-6">
																			<div class="row">
																				<div class="col-xs-12 pl0">
																					<p class="mb0 color-gray lineheight20">Total Booking taken: <b><?= $agtData['totBookings'] | 0; ?></b></p>
																				</div>
																			</div>
																		</div>
																		<div class="col-xs-6">
																			<div class="row">
																				<div class="col-xs-12 pl0">
																					<p class="mb0 color-gray lineheight20">Active Bookings: : <b><?= $agtData['totActiveBookings'] | 0; ?></b></p>
																				</div>
																			</div>
																		</div>
																	</div>


																</div>
															</div>
														</div>
														<div class="col-xs-12 col-md-4">
															<div class="row">
																<div class="col-xs-12 mb20">
																	<div class="widget-tab-box2 link-infos">
																		<h1 class="font-16">Actions</h1>
																		<ul class="pl0">
																			<li class="mb5"><a href="<?php echo Yii::app()->createUrl("/aaohome/agent/bookinghistory", ['agent' => $agentId]) ?>" target="_blank"><i class="fas fa-plus mr5 font-11"></i>Booking History</a></li>
																		</ul>
																		<ul class="pl0">
																			<li class="mb5"><a href="<?php echo Yii::app()->createUrl("/aaohome/agent/ledgerbooking", ['agtId' => $agentId]) ?>" target="_blank"><i class="fas fa-plus mr5 font-11"></i>Agent Account</a></li>
																		</ul>
																		<ul class="pl0">
																		<li class="mb5"><a  href="/aaohome/contact/form?ctt_id=<?= $agtModel->agt_contact_id ?>&type=3" target="_blank"><i class="fas fa-plus mr5 font-11"></i>Modify Contact Details</a></li>
																		</ul>
																		<ul class="pl0">
																			<?php
																			$callurl = Yii::app()->createUrl("aaohome/agent/changestatus", array("agt_id" => $agentId, "agt_active" => $agtModel->agt_active));
																			if ($agtModel->agt_active == 1)
																			{
																				$objtitle = 'Block';
																			}
																			else
																			{
																				$objtitle = "Unblock";
																			}
																			?>
																			<li class="mb5"><a onclick="blockAgent(this);return false;" data-title="<?= $objtitle ?>" href ="<?php echo $callurl; ?>"><i class="fas fa-plus mr5 font-11"></i><?= $objtitle ?> Agent</a></li>
																		</ul>  
																		<ul class="pl0">
																			<?php
																			$callurls	 = Yii::app()->createUrl("aaohome/agent/approve", array("agt_id" => $agentId, "agt_approve" => $agtModel->agt_approved));
																			?>
																			<li class="mb5"><a onclick="approveAgent(this);return false;" data-title="approve" href="<?php echo $callurls; ?>"><i class="fas fa-plus mr5 font-11"></i>Approved/Rejected</a></li>
																		</ul>
																		<ul class="pl0">
																			<?php
																			$calurls	 = Yii::app()->createUrl("aaohome/agent/changetype", array("agt_id" => $agentId));
																			?>
																			<li class="mb5"><a onclick="changType(this);return false;" data-title="changetype" href="<?php echo $calurls; ?>"><i class="fas fa-plus mr5 font-11"></i>Change Type</a></li>
																		</ul>
																		<ul class="pl0">
																			<li class="mb5"><a  href="/aaohome/agent/linkuser?agt_id=<?= $agentId ?>" target="_blank"><i class="fas fa-plus mr5 font-11"></i>Link User</a></li>
																		</ul>
																		<ul class="pl0">
																			<li class="mb5"><a  href="/aaohome/agent/settings?agtid=<?= $agentId ?>" target="_blank"><i class="fas fa-plus mr5 font-11"></i>Setting</a></li>
																		</ul>

																	</div>
																</div>



															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div role="tabpanel" class="tab-pane tabHide " id="notifications"> 
									<div class="panel">
										<div class="panel-heading p0 pt5">
											<?= $orgName; ?> (<?= Agents::model()->getAgentType($agtData['agt_type']); ?>)
										</div>
										<div class="panel-body p0 pt20 agtNotifications">                                           
										</div>
									</div>
								</div>
								<div role="tabpanel" class="tab-pane tabHide " id="logs"> 
									<div class="panel">
										<div class="panel-heading p0 pt5">
											<?= $orgName; ?> (<?= Agents::model()->getAgentType($agtData['agt_type']); ?>)
										</div>
										<div class="panel-body p0 pt20 agtLogs">                                           
										</div>
									</div>
								</div>
								<div role="tabpanel" class="tab-pane tabHide " id="documents"> 
									<div class="panel">
										<div class="panel-heading p0 pt5">
											<?= $orgName; ?> (<?= Agents::model()->getAgentType($agtData['agt_type']); ?>)
										</div>
										<div class="panel-body p0 pt20 agtDocuments">                                           
										</div>
									</div>
								</div>


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
	<div class="row">
		<div class="col-xs-12 text-center h3 mt0">        
			<h2 style='color:#ff0000;'>Agent not found.</h2>
		</div>
	</div>
	<?php
}
?>


<script  type="text/javascript">
	$("#documentsli").on("click", function () {
		getDocumentsDetails();
	});
	function getDocumentsDetails()
	{
		var agentId = '<?= $agentId; ?>';
		var href = '<?= Yii::app()->createUrl("aaohome/agent/documentDetails"); ?>';
		$.ajax
				({
					"url": href,
					"data": {"agtId": agentId},
					"type": 'get',
					"dataType": "html",
					success: function (data)
					{
						$('.agtDocuments').html(data);
					}
				});
	}

	$("#notificationsli").on("click", function () {
		getNotificationsDetails();
	});
	function getNotificationsDetails()
	{
		var agentId = '<?= $agentId; ?>';
		var href = '<?= Yii::app()->createUrl("aaohome/agent/notificationsDetails"); ?>';
		$.ajax
				({
					"url": href,
					"data": {"agtId": agentId},
					"type": 'get',
					"dataType": "html",
					success: function (data)
					{
						$('.agtNotifications').html(data);
					}
				});
	}

	$("#logsli").on("click", function () {
		getLogsDetails();
	});
	function getLogsDetails()
	{
		var agentId = '<?= $agentId; ?>';
		var href = '<?= Yii::app()->createUrl("aaohome/agent/showlog"); ?>';
		$.ajax
				({
					"url": href,
					"data": {"agtid": agentId},
					"type": 'get',
					"dataType": "html",
					success: function (data)
					{
						$('.agtLogs').html(data);
					}
				});
	}

	function blockAgent(obj)
	{
		var objtitle = $(obj).data('title');
		var con = confirm("Are you sure you want to " + objtitle + " this agent?");
		if (con) {
			try
			{
				$href = $(obj).attr("href");
				$.ajax({
					url: $href,
					dataType: "json",
					success: function (result) {
						if (result.success) {
							refreshAgentView();
						} else {
							alert('Sorry error occured');
						}
					},
					error: function (xhr, status, error) {
						alert('Sorry error occured');
					}
				});
			} catch (e)
			{
				alert(e);
			}
		}
		return false;
	}
	function approveAgent(obj)
	{
		var con = confirm("Are you sure you want to approve/rejected this agent?");
		if (con) {
			try
			{
				$href = $(obj).attr("href");
				jQuery.ajax({type: "GET", url: $href, success: function (data)
					{
						bootbox.dialog({
							message: data,

							className: "bootbox-sm",
							title: "Partner Approval",
							success: function (result) {
								if (result.success) {
									alert('Done Successfully');

								} else {
									alert('Sorry error occured');
								}
							},
							error: function (xhr, status, error) {
								alert('Sorry error occured');
							}
						});
					}});
			} catch (e)
			{
				alert(e);
			}
		}
		return false;
	}

	function changType(obj)
	{
		var con = confirm("Are you sure you want to change type of this agent?");
		if (con) {
			try
			{
				$href = $(obj).attr("href");
				jQuery.ajax({type: "GET", url: $href, success: function (data)
					{
						bootbox.dialog({
							message: data,

							className: "bootbox-sm",
							title: "Change Partner Type",
							success: function (result) {
								if (result.success) {
									alert('Done Successfully');

								} else {
									alert('Sorry error occured');
								}
							},
							error: function (xhr, status, error) {
								alert('Sorry error occured');
							}
						});
					}});
			} catch (e)
			{
				alert(e);
			}
		}
		return false;
	}
	function refreshAgentView() {
		location.reload();
	}
</script>