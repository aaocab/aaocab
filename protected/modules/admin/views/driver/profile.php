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
$id			 = $model->drv_contact_id;
$drvPhone	 = ContactPhone::model()->getContactPhoneById($id);
$drvEmail	 = ContactEmail::model()->getContactEmailById($id);
$accType	 = $model->accType;
$params['Booking']['bcb_driver_id'] = $model->drv_id;
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
			<div class="row">
				<div class="col-xs-12 text-center"> 

					<a class="btn btn-success btn-sm dropdown-toggle" target="blank" href="/aaohome/contact/view?ctt_id=<?php echo $model->drv_contact_id ?>&viewType=driver" target="_blank">View Contact</a>
					<a class="btn btn-info font11x" href="<?php echo Yii::app()->createUrl("aaohome/driver/showlog", array('drvId' => $model->drv_id)) ?>" target="_blank">Show Log</a>
					<a class="btn btn-warning font11x" href="<?php echo Yii::app()->createUrl("aaohome/driver/history", array('drvId' => $model->drv_id)) ?>" target="_blank">Driving History</a>
					<a class="btn btn-danger font11x" href="<?php echo Yii::app()->createUrl("aaohome/driver/tripDetails", array('drvId' => $model->drv_id)) ?>" target="_blank">Trip Details</a>
					<a class="btn btn-success font11x" onclick="updateDriverDetails();">Manually Update Statistical Data</a>
                    <a class="btn btn-success font11x"  onclick="drvLocation(<?= $model->drv_id ?>)" title="Driver Location" >Driver Location</a>
					<a class="btn btn-primary font11x"  href="<?php echo Yii::app()->createUrl("aaohome/booking/list", $params) ?>" target="_blank">Booking History</a>
					<a class="btn btn-primary font11x"  href="<?php echo Yii::app()->createUrl("aaohome/driver/deviceHistory",  array('drvId' => $model->drv_id)) ?>" target="_blank">View Device History</a>
				</div>
			</div>
            <div style="border-collapse: collapse; border: 1px; " >
                <div class="row bordered mt10" >
                    <div class="col-xs-12 text-center"> 
                        <font style=" font-size: 18px;"><b>Driver Name:</b></font> <?= $model->drv_name; ?><br> 
                        <b>Gozo Driver Record Number:</b> <?= dechex($model->drv_id); ?><br>
                        <b>Approval Status:</b> <?= $data['approve_status']; ?><br>
                        <b>Approved By:</b> <?= $data['approve_by_name']; ?><br>

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


            </div>
        </div>    
    </div>
</div> 
<script  type="text/javascript">

    function updateDriverDetails()
    {

        $href = "<?php echo Yii::app()->createUrl("aaohome/driver/UpdateDetails") ?>";
        $id = <?php echo $model->drv_id; ?>;

        jQuery.ajax({type: 'GET',
            url: $href,
            data: {"drv_id": $id},
            success: function (data)
            {
                data = JSON.parse(data);
                bootbox.alert(data.message);

            }
        });
    }
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
	
function drvLocation(drvId) 
{
    href = "<?= Yii::app()->createUrl('aaohome/booking/getDrvCurrentLocation') ?>";
    jQuery.ajax({type: 'GET',
            url: href,
            "dataType": "json",
            data: {"drvId": drvId},
            success: function (data)
            {
               if (data.success)
               {
                       window.open(data.destUrl, '_blank');
               }
            }
    });
}
</script>
