<?php
$adminList = Admins::getAdminList();
switch ($qrModel->qrc_ent_type)
{
	case 1: //consumer
		$customerList	 = Users::getById($qrModel->qrc_ent_id);
		$allocatedTo	 = $customerList['ctt_first_name'] . ' ' . $customerList['ctt_last_name'] . " (" . "Consumer" . ")";
		break;
	case 2: //vendor
		$vendorList		 = Vendors::getById($qrModel->qrc_ent_id);
		$allocatedTo	 = $vendorList['vnd_name'] . " (" . "Vendor" . ")";
		break;
	case 3: //driver
		$driverList		 = Drivers::getByDriverId($qrModel->qrc_ent_id);
		$allocatedTo	 = $driverList['ctt_first_name'] . ' ' . $driverList['ctt_last_name'] . " (" . "Driver" . ")";
		break;
	case 4: //admin
		$allocatedTo	 = $adminList[$qrModel->qrc_ent_id] . " (" . "Admin" . ")";
		break;
	case 5: //agent
		$agentList		 = Agents::getById($qrModel->qrc_ent_id);
		$allocatedTo	 = $agentList['agt_fname'] . ' ' . $agentList['agt_lname'] . " (" . "Agent" . ")";
		break;
	default:
		break;
}
if ($qrModel->qrc_status == 3)
{
	$class	 = 'label label-success';
	$status	 = 'Activated';
}
else if ($qrModel->qrc_status == 2)
{
	$class	 = 'label label-primary';
	$status	 = 'Allocated';
}
else
{
	$class	 = 'label label-danger';
	$status	 = 'Pending';
}

$imageUrl		 = "/attachments/QR/";
?>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-body pt0">
			<div class="row">
				<div class="col-xs-12 text-center mt0 mb10 font-24">
					<label for="type" class="control-label font-24"><span>QR CODE:</span> </label>
					<b><?= $qrModel->qrc_code; ?></b>
					<strong class='<?= $class; ?>'><?= $status; ?></strong>
				</div>
				<div class="col-xs-12">
					<div class="panel panel-default panel-border mb0">
						<div class="panel-body">
							<div class="row">
								<div class="col-xs-12 col-lg-7">
									<div class="row">
										<div class="col-xs-12 font-16"><b>Contact Details</b></div>
										<div class="col-xs-12 mb10">
											<?php
											$pathContact	 = QrCode::getDocPathById($qrModel->qrc_id, 2);
											?>
											<a href="<?= $pathContact ?>" target="_blank"><img src="<?= $pathContact ?>" style='height:60px;width:60px'></a>

										</div>
										<div class="col-xs-12 col-lg-6 mb10 font-12"><b>Name:</b> <br><?= $qrModel->qrc_contact_name; ?></div>
										<div class="col-xs-12 col-lg-6 mb10"><b>Number:</b> <br><?= $qrModel->qrc_contact_phone; ?></div>
										<div class="col-xs-12 mb10">
											<div class="row">
												<div class="col-xs-12 col-lg-6"><b>Email:</b> <br><?= $qrModel->qrc_contact_email; ?></div>
												<div class="col-xs-12 col-lg-6"><b>UPI Number:</b> <br><?= $qrModel->qrc_upi_number; ?></div>
											</div>
										</div>
										<div class="col-xs-12 col-lg-6 mb10"><b>Allocated To:</b> <br><?= $allocatedTo; ?></div>
										<div class="col-xs-12 col-lg-6 mb10"><b>Allocated By:</b> <br><?= ($qrModel->qrc_allocated_by != '') ? $adminList[$qrModel->qrc_allocated_by] : '-'; ?></div>
										<div class="col-xs-12 col-lg-6 mb10"><b>Allocated On:</b> <br><?= ($qrModel->qrc_allocate_date != '') ? DateTimeFormat::DateTimeToLocale($qrModel->qrc_allocate_date) : '-'; ?></div>
										<div class="col-xs-12 col-lg-6 mb10"><b>Activated By:</b> <br><?= ($qrModel->qrc_activated_by != '') ? $adminList[$qrModel->qrc_activated_by] : '-'; ?></div>
										<div class="col-xs-12 col-lg-6"><b>Activated On:</b> <br><?= ($qrModel->qrc_activated_date != '') ? DateTimeFormat::DateTimeToLocale($qrModel->qrc_activated_date) : '-'; ?></div>
									</div>
								</div>
								<div class="col-xs-12 col-lg-5">
									<div class="row">
										<div class="col-xs-12 font-16"><b>Location Details</b></div>
										<div class="col-xs-12 mb10">
											<?php
											$pathLocation	 = QrCode::getDocPathById($qrModel->qrc_id, 1);
											?>
											<a href="<?= $pathLocation ?>" target="_blank"><img src="<?= $pathLocation ?>" style='height:60px;width:60px'></a>
										</div>
										<div class="col-xs-12 mb10"><b>Name:</b>&nbsp;<br><?= $qrModel->qrc_location_name ?></div>
										<div class="col-xs-12 col-lg-6 mb10"><b>Latitude:</b> <br><?= $qrModel->qrc_location_lat; ?></div>
										<div class="col-xs-12 col-lg-6 mb10"><b>Longitude:</b> <br><?= $qrModel->qrc_location_long; ?></div>
										<div class="col-xs-12 col-lg-6 mb10"><b>Approve Status:</b> <br>
											<?php
											if ($qrModel->qrc_approval_status == 2)
											{
												echo "<strong class='label label-danger'>Rejected</strong>";
											}
											elseif ($qrModel->qrc_approval_status == 1)
											{
												echo "<strong class='label label-success'>Approved</strong>";
											}
											else
											{
												echo "<strong class='label label-primary'>Pending</strong>";
											}
											?>
										</div>
										<div class="col-xs-12 col-lg-6 mb10"><b>Approve By:</b> <br><?= ($qrModel->qrc_approved_by != '') ? $adminList[$qrModel->qrc_approved_by] : '-'; ?></div>
										<div class="col-xs-12 col-lg-6 mb10"><b>Approve Date:</b> <br><?= ($qrModel->qrc_location_long != '') ? DateTimeFormat::DateTimeToLocale($qrModel->qrc_location_long) : '-'; ?></div>

									</div>
								</div>
							</div>
							<div class="row">
								<div>
									<?php
									$checkaccess = Yii::app()->user->checkAccess('approveQr');
									if ($checkaccess)
									{
										if ($qrModel->qrc_status == 3)
										{
											?>
											<div class="col-xs-12 col-lg-12 mb10 mt20 text-center">
												<input type="hidden" id="qrId" value="<?= $qrModel->qrc_id; ?>">
												<a class="btn btn-primary btn-xs pl5 pr5" id="btnAppr" name="btnAppr">Approve</a>
												<a class="btn btn-danger btn-xs pl5 pr5" id="btnDspr" name="btnDspr">Reject</a>
											</div>
										<?php
										}
									}
									?>

								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>    
</div>
<script type="text/javascript">
	$('#btnAppr').click(function () {
		if (!confirm('Are you sure that you want to approve this QR.')) {
			return false;
		} else {
			var qrId = $('#qrId').val();
			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('admpnl/qr/approveQr')) ?>',
				data: {"btntype": "approve", "id": qrId, 'YII_CSRF_TOKEN': "<?= Yii::app()->request->csrfToken ?>"},
				success: function (data) {
					alert(JSON.stringify(data.message));
					bootbox.hideAll();
				}
			});
		}
	});
	$('#btnDspr').click(function () {
		if (!confirm('Are you sure that you want to reject this QR.')) {
			return false;
		} else {
			var qrId = $('#qrId').val();
			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('admpnl/qr/approveQr')) ?>',
				data: {"btntype": "rejecte", "id": qrId, 'YII_CSRF_TOKEN': "<?= Yii::app()->request->csrfToken ?>"},
				success: function (data) {
					alert(JSON.stringify(data.message));
					bootbox.hideAll();
				}
			});
		}

	});



</script>