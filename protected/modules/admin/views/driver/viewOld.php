<?php
/* @var $model Drivers */
if (isset($data['drv_id']) && $data['drv_id'] <> '')
{
	$model				 = Drivers::model()->resetScope()->findByPk($data['drv_id']);
//    $driverDocs = DriverDocs::model()->findAllByDrvId($data['drv_id']);
	$driverDocs			 = Document::model()->getDocsByDrvId($model->drv_id, $model->drv_contact_id);
	$voterDoc			 = $voterBackDoc		 = $panDoc				 = $aadharDoc			 = $driverLicenseDoc	 = $driverLicenseDoc2	 = $pcVerificationDoc	 = '';
	$voterId			 = $driverDocs['drv_voter_id']['drd_id'];
	$voterDoc			 = $driverDocs['drv_voter_id']['drd_file'];
	$voterStatus		 = $driverDocs['drv_voter_id']['drd_status'];
	$voterRemarks		 = $driverDocs['drv_voter_id']['drd_remarks'];

	$voterBackId		 = $driverDocs['drv_voter_back_id']['drd_id'];
	$voterBackDoc		 = $driverDocs['drv_voter_back_id']['drd_file'];
	$voterBackStatus	 = $driverDocs['drv_voter_back_id']['drd_status'];
	$voterBackRemarks	 = $driverDocs['drv_voter_back_id']['drd_remarks'];

	$panId		 = $driverDocs['drv_pan_id']['drd_id'];
	$panDoc		 = $driverDocs['drv_pan_id']['drd_file'];
	$panStatus	 = $driverDocs['drv_pan_id']['drd_status'];
	$panRemarks	 = $driverDocs['drv_pan_id']['drd_remarks'];

	$panBackId		 = $driverDocs['drv_pan_back_id']['drd_id'];
	$panBackDoc		 = $driverDocs['drv_pan_back_id']['drd_file'];
	$panBackStatus	 = $driverDocs['drv_pan_back_id']['drd_status'];
	$panBackRemarks	 = $driverDocs['drv_pan_back_id']['drd_remarks'];

	$aadharId		 = $driverDocs['drv_aadhaar_id']['drd_id'];
	$aadharDoc		 = $driverDocs['drv_aadhaar_id']['drd_file'];
	$aadharStatus	 = $driverDocs['drv_aadhaar_id']['drd_status'];
	$aadharRemarks	 = $driverDocs['drv_aadhaar_id']['drd_remarks'];

	$aadharBackId		 = $driverDocs['drv_aadhaar_back_id']['drd_id'];
	$aadharBackDoc		 = $driverDocs['drv_aadhaar_back_id']['drd_file'];
	$aadharBackStatus	 = $driverDocs['drv_aadhaar_back_id']['drd_status'];
	$aadharBackRemarks	 = $driverDocs['drv_aadhaar_back_id']['drd_remarks'];

	$driverLicenseId		 = $driverDocs['drv_licence_id']['drd_id'];
	$driverLicenseDoc		 = $driverDocs['drv_licence_id']['drd_file'];
	$driverLicenseStatus	 = $driverDocs['drv_licence_id']['drd_status'];
	$driverLicenseRemarks	 = $driverDocs['drv_licence_id']['drd_remarks'];

	$driverLicenseId2		 = $driverDocs['drv_licence_back_id']['drd_id'];
	$driverLicenseDoc2		 = $driverDocs['drv_licence_back_id']['drd_file'];
	$driverLicenseStatus2	 = $driverDocs['drv_licence_back_id']['drd_status'];
	$driverLicenseRemarks2	 = $driverDocs['drv_licence_back_id']['drd_remarks'];

	$pcVerificationId		 = $driverDocs['drv_police_ver']['drd_id'];
	$pcVerificationDoc		 = $driverDocs['drv_police_ver']['drd_file'];
	$pcVerificationStatus	 = $driverDocs['drv_police_ver']['drd_status'];
	$pcVerificationRemarks	 = $driverDocs['drv_police_ver']['drd_remarks'];



	$voterApproveStyle	 = ($voterDoc != '' && $voterStatus == 0) ? "display:block;" : "display:none;";
	$voterRejectStyle	 = ($voterDoc != '' && $voterStatus < 2) ? "display:block;" : "display:none;";
	$voterReloadStyle	 = ($voterDoc != '' && $voterStatus == 2) ? "display:block;" : "display:none;";
	if ($voterDoc != '')
	{
		if ($voterStatus == 0)
		{
			$voter = ['class' => 'label label-info', 'style' => 'display:block;', 'level' => 'Not Approved'];
		}
		else if ($voterStatus == 1)
		{
			$voter = ['class' => 'label label-success', 'style' => 'display:block;', 'level' => 'Approved'];
		}
		else if ($voterStatus == 2)
		{
			$voter = ['class' => 'label label-danger', 'style' => 'display:block;', 'level' => 'Rejected'];
		}
	}
	else
	{
		$voter = '';
	}

	$voterApproveStyle2	 = ($voterBackDoc != '' && $voterBackStatus == 0) ? "display:block;" : "display:none;";
	$voterRejectStyle2	 = ($voterBackDoc != '' && $voterBackStatus < 2) ? "display:block;" : "display:none;";
	$voterReloadStyle2	 = ($voterBackDoc != '' && $voterBackStatus == 2) ? "display:block;" : "display:none;";
	if ($voterBackDoc != '')
	{
		if ($voterBackStatus == 0)
		{
			$voterBack = ['class' => 'label label-info', 'style' => 'display:block;', 'level' => 'Not Approved'];
		}
		else if ($voterBackStatus == 1)
		{
			$voterBack = ['class' => 'label label-success', 'style' => 'display:block;', 'level' => 'Approved'];
		}
		else if ($voterBackStatus == 2)
		{
			$voterBack = ['class' => 'label label-danger', 'style' => 'display:block;', 'level' => 'Rejected'];
		}
	}
	else
	{
		$voterBack = '';
	}


	$aadharApproveStyle	 = ($aadharDoc != '' && $aadharStatus == 0) ? "display:block;" : "display:none;";
	$aadharRejectStyle	 = ($aadharDoc != '' && $aadharStatus < 2) ? "display:block;" : "display:none;";
	$aadharReloadStyle	 = ($aadharDoc != '' && $aadharStatus == 2) ? "display:block;" : "display:none;";
	if ($aadharDoc != '')
	{
		if ($aadharStatus == 0)
		{
			$aadhar = ['class' => 'label label-info', 'style' => 'display:block;', 'level' => 'Not Approved'];
		}
		else if ($aadharStatus == 1)
		{
			$aadhar = ['class' => 'label label-success', 'style' => 'display:block;', 'level' => 'Approved'];
		}
		else if ($aadharStatus == 2)
		{
			$aadhar = ['class' => 'label label-danger', 'style' => 'display:block;', 'level' => 'Rejected'];
		}
	}
	else
	{
		$aadhar = '';
	}

	$aadharApproveStyle2 = ($aadharBackDoc != '' && $aadharBackStatus == 0) ? "display:block;" : "display:none;";
	$aadharRejectStyle2	 = ($aadharBackDoc != '' && $aadharBackStatus < 2) ? "display:block;" : "display:none;";
	$aadharReloadStyle2	 = ($aadharBackDoc != '' && $aadharBackStatus == 2) ? "display:block;" : "display:none;";
	if ($aadharBackDoc != '')
	{
		if ($aadharBackStatus == 0)
		{
			$aadharBack = ['class' => 'label label-info', 'style' => 'display:block;', 'level' => 'Not Approved'];
		}
		else if ($aadharBackStatus == 1)
		{
			$aadharBack = ['class' => 'label label-success', 'style' => 'display:block;', 'level' => 'Approved'];
		}
		else if ($aadharBackStatus == 2)
		{
			$aadharBack = ['class' => 'label label-danger', 'style' => 'display:block;', 'level' => 'Rejected'];
		}
	}
	else
	{
		$aadharBack = '';
	}


	$panApproveStyle = ($panDoc != '' && $panStatus == 0) ? "display:block;" : "display:none;";
	$panRejectStyle	 = ($panDoc != '' && $panStatus < 2) ? "display:block;" : "display:none;";
	$panReloadStyle	 = ($panDoc != '' && $panStatus == 2) ? "display:block;" : "display:none;";
	if ($panDoc != '')
	{
		if ($panStatus == 0)
		{
			$pan = ['class' => 'label label-info', 'style' => 'display:block;', 'level' => 'Not Approved'];
		}
		else if ($panStatus == 1)
		{
			$pan = ['class' => 'label label-success', 'style' => 'display:block;', 'level' => 'Approved'];
		}
		else if ($panStatus == 2)
		{
			$pan = ['class' => 'label label-danger', 'style' => 'display:block;', 'level' => 'Rejected'];
		}
	}
	else
	{
		$pan = '';
	}

	$panApproveStyle2	 = ($panBackDoc != '' && $panBackStatus == 0) ? "display:block;" : "display:none;";
	$panRejectStyle2	 = ($panBackDoc != '' && $panBackStatus < 2) ? "display:block;" : "display:none;";
	$panReloadStyle2	 = ($panBackDoc != '' && $panBackStatus == 2) ? "display:block;" : "display:none;";
	if ($panBackDoc != '')
	{
		if ($panBackStatus == 0)
		{
			$panBack = ['class' => 'label label-info', 'style' => 'display:block;', 'level' => 'Not Approved'];
		}
		else if ($panBackStatus == 1)
		{
			$panBack = ['class' => 'label label-success', 'style' => 'display:block;', 'level' => 'Approved'];
		}
		else if ($panBackStatus == 2)
		{
			$panBack = ['class' => 'label label-danger', 'style' => 'display:block;', 'level' => 'Rejected'];
		}
	}
	else
	{
		$panBack = '';
	}


	$dlApproveStyle	 = ($driverLicenseDoc != '' && $driverLicenseStatus == 0) ? "display:block;" : "display:none;";
	$dlRejectStyle	 = ($driverLicenseDoc != '' && $driverLicenseStatus < 2) ? "display:block;" : "display:none;";
	$dlReloadStyle	 = ($driverLicenseDoc != '' && $driverLicenseStatus == 2) ? "display:block;" : "display:none;";
	if ($driverLicenseDoc != '')
	{
		if ($driverLicenseStatus == 0)
		{
			$dl = ['class' => 'label label-info', 'style' => 'display:block;', 'level' => 'Not Approved'];
		}
		else if ($driverLicenseStatus == 1)
		{
			$dl = ['class' => 'label label-success', 'style' => 'display:block;', 'level' => 'Approved'];
		}
		else if ($driverLicenseStatus == 2)
		{
			$dl = ['class' => 'label label-danger', 'style' => 'display:block;', 'level' => 'Rejected'];
		}
	}
	else
	{
		$dl = '';
	}

	$dlApproveStyle2 = ($driverLicenseDoc2 != '' && $driverLicenseStatus2 == 0) ? "display:block;" : "display:none;";
	$dlRejectStyle2	 = ($driverLicenseDoc2 != '' && $driverLicenseStatus2 < 2) ? "display:block;" : "display:none;";
	$dlReloadStyle2	 = ($driverLicenseDoc2 != '' && $driverLicenseStatus2 == 2) ? "display:block;" : "display:none;";
	if ($driverLicenseDoc2 != '')
	{
		if ($driverLicenseStatus2 == 0)
		{
			$dl2 = ['class' => 'label label-info', 'style' => 'display:block;', 'level' => 'Not Approved'];
		}
		else if ($driverLicenseStatus2 == 1)
		{
			$dl2 = ['class' => 'label label-success', 'style' => 'display:block;', 'level' => 'Approved'];
		}
		else if ($driverLicenseStatus2 == 2)
		{
			$dl2 = ['class' => 'label label-danger', 'style' => 'display:block;', 'level' => 'Rejected'];
		}
	}
	else
	{
		$dl2 = '';
	}

	$pcApproveStyle	 = ($pcVerificationDoc != '' && $pcVerificationStatus == 0) ? "display:block;" : "display:none;";
	$pcRejectStyle	 = ($pcVerificationDoc != '' && $pcVerificationStatus < 2) ? "display:block;" : "display:none;";
	$pcReloadStyle	 = ($pcVerificationDoc != '' && $pcVerificationStatus == 2) ? "display:block;" : "display:none;";
	if ($pcVerificationDoc != '')
	{
		if ($pcVerificationStatus == 0)
		{
			$pc = ['class' => 'label label-info', 'style' => 'display:block;', 'level' => 'Not Approved'];
		}
		else if ($pcVerificationStatus == 1)
		{
			$pc = ['class' => 'label label-success', 'style' => 'display:block;', 'level' => 'Approved'];
		}
		else if ($pcVerificationStatus == 2)
		{
			$pc = ['class' => 'label label-danger', 'style' => 'display:block;', 'level' => 'Rejected'];
		}
	}
	else
	{
		$pc = '';
	}
}
$id = $model->drv_contact_id;
$drvPhone = ContactPhone::model()->getContactPhoneById($id);
$drvEmail = ContactEmail::model()->getContactEmailById($id);
$accType  = $model->accType;
?>
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


</style>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-body">
            <div style="border-collapse: collapse; border: 1px; " >
                <div class="row bordered mt10" >
                    <div class="col-xs-12 text-center"> 
                        <font style=" font-size: 18px;"><b>Driver Name:</b></font> <?= $model->drv_name; ?><br> 
                        <b>Gozo Driver Record Number:</b> <?= dechex($model->drv_id); ?><br>
                        <b>Approval Status:</b> <?= $data['approve_status']; ?><br>
                        <b>Approved By:</b> <?= $data['approve_by_name']; ?><br>
                        <?php echo CHtml::link("Show Contact", Yii::app()->createUrl("admin/contact/view", ["ctt_id" => $model->drv_contact_id,'viewType' =>'driver']), ["class" => "", "onclick" => "return viewContactDriver(this)"]);?>
                    </div>
                </div>
                <div class="row bordered" >
                    <div class="col-xs-6 text-left" style=" padding-left: 50px;"> 
                        <b>Contact Details</b>
                        <br>Phone : <?= $drvPhone; ?>
                        <br>Alternative Phone Number : <?= $data['drv_alt_phone'] ?>
						<br>Email : <?= $drvEmail; ?>
                        <br>Address : <?= $model->drvContact->ctt_address; ?>
                        <br>City : <?= $data['city_name']; ?>
                    </div>
                    <div class="col-xs-6 text-left"> 
                        <b>Driving History</b>
						<br>License Number : <?= ($model->drvContact->ctt_license_no!='' && $model->drvContact->ctt_license_no!=NULL) ? $model->drvContact->ctt_license_no : '';?>
						<br>License Expiry Date : <?= ($model->drvContact->ctt_license_exp_date!='' && $model->drvContact->ctt_license_exp_date!=NULL) ? date('d/m/Y h:i A', strtotime($model->drvContact->ctt_license_exp_date)) : '';?>
                        <br>Date Of Joining : <?= date('d/m/Y h:i A', strtotime($model->drv_created)); ?>
                        <br>#Number Of Trips : <?= $model->drv_total_trips; ?>
                        <br>Current Rating : <?= $model->drv_overall_rating; ?>
                        <br>Last Trip Date : <?php
						if ($data['last_pickup_date'] != NULL)
						{
							echo date('d/m/Y h:i A', strtotime($data['last_pickup_date']));
						}
						?> 
                        <br>Last Trip Rating : <?= $data['rtg_customer_driver']; ?>
                        <br>On-Time : <?php
						if ($data['rtg_driver_ontime'] <> NULL)
						{
							echo ($data['rtg_driver_ontime'] > 0) ? 'Yes' : 'No';
						}
						?>
                        <br>Soft Spoken : <?php
						if ($data['rtg_driver_softspokon'] <> NULL)
						{
							echo ($data['rtg_driver_softspokon'] > 0) ? 'Yes' : 'No';
						}
						?>
                        <br>Respectfully Dressed : <?php
						if ($data['rtg_driver_respectfully'] <> NULL)
						{
							echo ($data['rtg_driver_respectfully'] > 0) ? 'Yes' : 'No';
						}
						?>
                        <br>Safe Driver : <?php
						if ($data['rtg_driver_safely'] <> NULL)
						{
							echo ($data['rtg_driver_safely'] > 0) ? 'Yes' : 'No';
						}?>
						<br>Trip Types : <?php
						if ($data['drv_trip_type'] <> NULL)
						{
							echo ($data['drv_trip_type'] != 0) ? Drivers::getType($data['drv_trip_type']) : '';
						}
						?>
                    </div>
                </div>
                				
				<div class="row bordered text-center">
                    <div class="h5 pl20 ml10 pt5 m0"><b>Bank Details</b></div>
                    <div class="col-xs-12 col-sm-6 col-md-3 pt10">
                        <div class="col-xs-12 "><b>Bank Name: </b> <?= $data['ctt_bank_name']; ?></div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-3 pt10">
                        <div class="col-xs-12"><b>Bank Branch: </b><?= $data['ctt_bank_branch']; ?></div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-3 pt10">
                        <div class="col-xs-12 "><b>Account No: </b> <?= $data['ctt_bank_account_no']; ?></div>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-3 pt10">
                        <div class="col-xs-12"><b>Account Type: </b><?= $accType[$data['ctt_account_type']]; ?></div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-3 pt10">
                        <div class="col-xs-12"><b>IFSC Code: </b><?= $data['ctt_bank_ifsc']; ?></div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-3 pt10">
                        <div class="col-xs-12"><b>Beneficiary Name: </b><?= $data['ctt_beneficiary_name']; ?></div>
                    </div>
                </div>
				
                <div class="row bordered" >
                    <div class="col-xs-12 text-left" style=" padding-left: 50px;"> 
                        <b>App / Login Information</b>
                        <br>Username : <?= $model->drvUser->usr_name; ?>
                        <br>Last Login Date : <?= $model->drv_last_login; ?>
                    </div>
                </div>      
                <div class="row bordered" >
                    <div class="row" style=" padding-left: 20px;"><b>Past Trip Details :</b></div>
                    <div class="row col-xs-12">
                        <div class="col-xs-1 text-left" style=" padding-left: 10px;"><b>Booking ID</b></div>
                        <div class="col-xs-1 text-left" ><b>Booking Type</b></div>
                        <div class="col-xs-2 text-left"><b>From</b></div>
                        <div class="col-xs-2 text-left"><b>To</b></div>
                        <div class="col-xs-2 text-left"><b>Pickup Date</b></div>
                        <div class="col-xs-1 text-left"><b>Driver Rating</b></div>
                        <div class="col-xs-3 text-left"><b>Driver Comments</b></div>
                    </div>
					<?php
					if (count($pastData) > 0)
					{
						foreach ($pastData as $pdata)
						{
							?>
							<div class="row  col-xs-12">
								<div class="col-xs-1 text-left" style="padding-left: 10px;"><?= $pdata['bkg_booking_id']; ?></div>
								<div class="col-xs-1 text-left"><?= $pdata['booking_type']; ?></div>
								<div class="col-xs-2 text-left"><?= $pdata['from_city']; ?></div>
								<div class="col-xs-2 text-left"><?= $pdata['to_city']; ?></div>
								<div class="col-xs-2 text-left"><?= $pdata['bkg_pickup_date']; ?></div>
								<div class="col-xs-1 text-left"><?= $pdata['rtg_customer_driver']; ?></div>
								<div class="col-xs-3 text-left"><?= $pdata['rtg_driver_cmt']; ?></div>
							</div>
							<?php
						}
					}
					else
					{
						?>
						<div class="row col-xs-12 text-center"><b>No Records Yet Found.</b></div>
						<?php
					}
					?>    
                </div>
                <div class="row bordered" >
                    <div class="col-xs-12 ">
                        <div class="col-xs-12 text-center">
                            <label class = "control-label h3 ">Driver Log</label>
                        </div>
						<?php
						Yii::app()->runController('admin/driver/showlog/drvId/' . $model->drv_id . '/view/1');
						?>
                    </div>
                </div>
            </div>
        </div>    
    </div>
</div> 
<script  type="text/javascript">

/*    function rejectDriverDocs(id, status)
    {
        var href = '<?//= Yii::app()->createUrl("admin/driver/rejectDriverDoc"); ?>';
        jQuery.ajax({type: 'GET',
            url: href,
            data: {"drd_id": id, "drd_status": status},
            success: function (data)
            {
                upsellBox = bootbox.dialog({
                    message: data,
                    title: 'Add Remarks for Reject Document',
                    onEscape: function () {
                        // user pressed escape
                    },
                });

            }
        });
    }

    function updateDriverDocs(id, status)
    {
        var href = '<?//= Yii::app()->createUrl("admin/driver/updateDriverDoc"); ?>';
        $.ajax({
            "url": href,
            "type": "GET",
            "dataType": "html",
            "data": {"drd_id": id, "drd_status": status},
            "success": function (data1)
            {
                var dataSet = data1.split("~");
                if (dataSet[1] == 1)
                {
                    var img = dataSet[0] + dataSet[1];
                    $(dataSet[0]).show();
                    $(dataSet[0]).css("display", "block");
                    $(dataSet[0]).removeClass('label-info');
                    $(dataSet[0]).addClass('label label-success');
                    $(dataSet[0]).html("Approved");
                    $(img).hide();
                }
                if (dataSet[1] == 2)
                {
                    $(dataSet[0]).show();
                    $(dataSet[0]).css("display", "block");
                    $(dataSet[0]).removeClass('label-info');
                    $(dataSet[0]).removeClass('label-success');
                    $(dataSet[0]).addClass('label label-danger');
                    $(dataSet[0]).html("Rejected");

                    var img = dataSet[0] + dataSet[1];
                    var imgOn = dataSet[0] + '3';
                    $(dataSet[0]).show();
                    $(img).hide();
                    $(imgOn).show();
                } else if (dataSet[1] == 3)
                {
                    var div = dataSet[0] + 'Div';
                    var img = dataSet[0] + dataSet[1];
                    $(dataSet[0]).hide();
                    $(div).show();
                    $(img).hide();
                }
            }
        });
        return false;
    }
	*/
	 function viewContactDriver(obj) {
        var href2 = $(obj).attr("href");
		$.ajax({
            "url": href2,
            "type": "GET",
            "dataType": "html",
            "success": function (data) {
                var box = bootbox.dialog({
                    message: data,
                    title: 'Driver Contact',
                    size: 'large',
                    onEscape: function () {
                        // user pressed escape
                    },
                });
                if ($('body').hasClass("modal-open"))
                {
                    box.on('hidden.bs.modal', function (e) {
                        $('body').addClass('modal-open');
                    });
                }

            }
        });
        return false;
    }
</script>
