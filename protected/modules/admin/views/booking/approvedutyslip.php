<?php
//echo "<pre>";
//print_r($data['toll_DutySlipPath']); 
//echo "</pre>";
?>

<div class="row">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="col-xs-12">
                <div class="row">
                    <div class="col-xs-12 text-center h3 mt0">Documents</div>
                </div>
                <div class="row bordered mt10">

                    <div class="col-xs-12 pt10">
                        <div class="col-xs-12 col-sm-2"><b>State Tax : </b></div>
						<div class="col-xs-12 col-sm-10">
							<?php
							$i = 1;
							if (count($data['state_TaxDutySlipPath']) > 0)
							{
								foreach ($data['state_TaxDutySlipPath'] as $statePath)
								{
									?>
									<div style="background-color: #FFFFFF; width: 120px; height: 100px; float: left;">
								<?php if($statePath['images'] != ''){ ?>
										<a href=<?= $statePath['images'] ?> target="_blank"><img id="frLicApprove"  src="<?= $statePath['images'] ?>" style="width: 100px; height: 50px;"></a>	
								<?php } ?>	
										<?php
										//echo ($statePath['images'] != '') ? '<a href="' . $statePath['images'] . '" target="_blank">Attachment Link' . $i . '</a>' : 'Missing';
										//echo ' | ';
										?>
										<?php
										if ($statePath['bpay_approved'] == 0)
										{
											?>
											<span id="apv_<?= $statePath['id']; ?>" style="">
												<img id="frLicApprove"  src="<?= Yii::app()->request->baseUrl; ?>/images/icon/vendor_joining/approved.png" alt="Approve" title="Approve" onclick="approveVendorDocs('<?= $statePath['id']; ?>', '1')" style="cursor:pointer;">
											</span>
											<span id="apv1_<?= $statePath['id']; ?>" style="display:none">Approved</span>

											<span id="rej_<?= $statePath['id']; ?>" style="">
												<img id="frLicReject" src="<?= Yii::app()->request->baseUrl; ?>/images/icon/cab/customer_cancel.png" alt="Reject" title="Reject" onclick="approveVendorDocs('<?= $statePath['id']; ?>', '2')" style="cursor:pointer;">
											</span>
											<span id="rej1_<?= $statePath['id']; ?>" style="display:none">Rejected</span>
											<?php
										}if ($statePath['bpay_approved'] == 1)
										{
											?>		
											<span id="apv_<?= $statePath['id']; ?>" style="display:none">
												<img id="frLicApprove"  src="<?= Yii::app()->request->baseUrl; ?>/images/icon/vendor_joining/approved.png" alt="Approve" title="Approve" onclick="approveVendorDocs('<?= $statePath['id']; ?>', '1')" style="cursor:pointer;">
											</span>
											<span id="apv1_<?= $statePath['id']; ?>">Approved</span>

											<span id="rej_<?= $statePath['id']; ?>" style="">
												<img id="frLicReject" src="<?= Yii::app()->request->baseUrl; ?>/images/icon/cab/customer_cancel.png" alt="Reject" title="Reject" onclick="approveVendorDocs('<?= $statePath['id']; ?>', '2')" style="cursor:pointer;">
											</span>
											<span id="rej1_<?= $statePath['id']; ?>" style="display:none">Rejected</span>

										<?php } ?>
										<?php
										if ($statePath['bpay_approved'] == 2)
										{
											?>		
											<span id="apv_<?= $statePath['id']; ?>" style="">
												<img id="frLicApprove"  src="<?= Yii::app()->request->baseUrl; ?>/images/icon/vendor_joining/approved.png" alt="Approve" title="Approve" onclick="approveVendorDocs('<?= $statePath['id']; ?>', '1')" style="cursor:pointer;">
											</span>
											<span id="apv1_<?= $statePath['id']; ?>" style="display:none">Approved</span>

											<span id="rej_<?= $statePath['id']; ?>" style="display:none">
												<img id="frLicReject" src="<?= Yii::app()->request->baseUrl; ?>/images/icon/cab/customer_cancel.png" alt="Reject" title="Reject" onclick="approveVendorDocs('<?= $statePath['id']; ?>', '2')" style="cursor:pointer;">
											</span>
											<span id="rej1_<?= $statePath['id']; ?>">Rejected</span>


										<?php } ?>
									</div>	
									<?php
									$i++;
								}
							}
							else
							{
								echo 'Missing';
							}
							?>	
                        </div>

                    </div>
                    <div class="col-xs-12 pt10">
                        <div class="col-xs-12 col-sm-2 "><b>Toll Tax : </b></div>
                        <div class="col-xs-12 col-sm-10">
							<?php
							$i = 1;
							if (count($data['toll_DutySlipPath']) > 0)
							{
								foreach ($data['toll_DutySlipPath'] as $tollPath)
								{
									?>
									<div style="background-color: #FFFFFF; width: 120px; height: 100px; float: left;">
									<?php if($tollPath['images'] != ''){ ?>
										<a href=<?= $tollPath['images'] ?> target="_blank"><img id="frLicApprove"  src="<?= $tollPath['images'] ?>" style="width: 100px; height: 50px;"></a>	
								    <?php } ?>	
										<?php
										//echo ($tollPath['images'] != '') ? '<a href="' . $tollPath['images'] . '" target="_blank">Attachment Link' . $i . '</a>' : 'Missing';
										//echo ' | ';
										?>

										<?php
										if ($tollPath['bpay_approved'] == 0)
										{
											?>
											<span id="apv_<?= $tollPath['id']; ?>" style="">
												<img id="frLicApprove"  src="<?= Yii::app()->request->baseUrl; ?>/images/icon/vendor_joining/approved.png" alt="Approve" title="Approve" onclick="approveVendorDocs('<?= $tollPath['id']; ?>', '1')" style="cursor:pointer;">
											</span>
											<span id="apv1_<?= $tollPath['id']; ?>" style="display:none">Approved</span>
											<span id="rej_<?= $tollPath['id']; ?>" style="">
												<img id="frLicReject" src="<?= Yii::app()->request->baseUrl; ?>/images/icon/cab/customer_cancel.png" alt="Reject" title="Reject" onclick="approveVendorDocs('<?= $tollPath['id']; ?>', '2')" style="cursor:pointer;">
											</span>
											<span id="rej1_<?= $tollPath['id']; ?>" style="display:none">Rejected</span>
											<?php
										}if ($tollPath['bpay_approved'] == 1)
										{
											?>		
											<span id="apv_<?= $tollPath['id']; ?>" style="display:none">
												<img id="frLicApprove"  src="<?= Yii::app()->request->baseUrl; ?>/images/icon/vendor_joining/approved.png" alt="Approve" title="Approve" onclick="approveVendorDocs('<?= $tollPath['id']; ?>', '1')" style="cursor:pointer;">
											</span>
											<span id="apv1_<?= $tollPath['id']; ?>">Approved</span>

											<span id="rej_<?= $tollPath['id']; ?>" style="">
												<img id="frLicReject" src="<?= Yii::app()->request->baseUrl; ?>/images/icon/cab/customer_cancel.png" alt="Reject" title="Reject" onclick="approveVendorDocs('<?= $tollPath['id']; ?>', '2')" style="cursor:pointer;">
											</span>
											<span id="rej1_<?= $tollPath['id']; ?>" style="display:none">Rejected</span>

										<?php } ?>
										<?php
										if ($tollPath['bpay_approved'] == 2)
										{
											?>		
											<span id="apv_<?= $tollPath['id']; ?>" style="">
												<img id="frLicApprove"  src="<?= Yii::app()->request->baseUrl; ?>/images/icon/vendor_joining/approved.png" alt="Approve" title="Approve" onclick="approveVendorDocs('<?= $tollPath['id']; ?>', '1')" style="cursor:pointer;">
											</span>
											<span id="apv1_<?= $tollPath['id']; ?>" style="display:none">Approved</span>

											<span id="rej_<?= $tollPath['id']; ?>" style="display:none">
												<img id="frLicReject" src="<?= Yii::app()->request->baseUrl; ?>/images/icon/cab/customer_cancel.png" alt="Reject" title="Reject" onclick="approveVendorDocs('<?= $tollPath['id']; ?>', '2')" style="cursor:pointer;">
											</span>
											<span id="rej1_<?= $tollPath['id']; ?>">Rejected</span>


										<?php } ?>

									</div>
									<?php
									$i++;
								}
							}
							else
							{
								echo 'Missing';
							}
							?>	

						</div>

					</div>
					<div class="col-xs-12 pt10">
						<div class="col-xs-12 col-sm-2 "><b>Parking Charge : </b></div>
						<div class="col-xs-12 col-sm-10">
							<?php
							$i = 1;
							if (count($data['parking_DutySlipPath']) > 0)
							{
								foreach ($data['parking_DutySlipPath'] as $parkingPath)
								{
									?>
									<div style="background-color: #FFFFFF; width: 115px; height: 100px; float: left;">
									<?php if($parkingPath['images'] != ''){ ?>
										<a href=<?= $parkingPath['images'] ?> target="_blank"><img id="frLicApprove"  src="<?= $parkingPath['images'] ?>" style="width: 100px; height: 50px;"></a>	
								    <?php } ?>		
										
										<?php
										//echo ($parkingPath['images'] != '') ? '<a href="' . $parkingPath['images'] . '" target="_blank">Attachment Link' . $i . '</a>' : 'Missing';
										//echo ' | ';
										?>
										<?php
										if ($parkingPath['bpay_approved'] == 0)
										{
											?>
											<span id="apv_<?= $parkingPath['id']; ?>" style="">
												<img id="frLicApprove"  src="<?= Yii::app()->request->baseUrl; ?>/images/icon/vendor_joining/approved.png" alt="Approve" title="Approve" onclick="approveVendorDocs('<?= $parkingPath['id']; ?>', '1')" style="cursor:pointer;">
											</span>
											<span id="apv1_<?= $parkingPath['id']; ?>" style="display:none">Approved</span>
											<span id="rej_<?= $parkingPath['id']; ?>" style="">
												<img id="frLicReject" src="<?= Yii::app()->request->baseUrl; ?>/images/icon/cab/customer_cancel.png" alt="Reject" title="Reject" onclick="approveVendorDocs('<?= $parkingPath['id']; ?>', '2')" style="cursor:pointer;">
											</span>
											<span id="rej1_<?= $parkingPath['id']; ?>" style="display:none">Rejected</span>
											<?php
										}if ($parkingPath['bpay_approved'] == 1)
										{
											?>		
											<span id="apv_<?= $parkingPath['id']; ?>" style="display:none">
												<img id="frLicApprove"  src="<?= Yii::app()->request->baseUrl; ?>/images/icon/vendor_joining/approved.png" alt="Approve" title="Approve" onclick="approveVendorDocs('<?= $parkingPath['id']; ?>', '1')" style="cursor:pointer;">
											</span>
											<span id="apv1_<?= $parkingPath['id']; ?>">Approved</span>

											<span id="rej_<?= $parkingPath['id']; ?>" style="">
												<img id="frLicReject" src="<?= Yii::app()->request->baseUrl; ?>/images/icon/cab/customer_cancel.png" alt="Reject" title="Reject" onclick="approveVendorDocs('<?= $parkingPath['id']; ?>', '2')" style="cursor:pointer;">
											</span>
											<span id="rej1_<?= $parkingPath['id']; ?>" style="display:none">Rejected</span>

										<?php } ?>
										<?php
										if ($parkingPath['bpay_approved'] == 2)
										{
											?>		
											<span id="apv_<?= $parkingPath['id']; ?>" style="">
												<img id="frLicApprove"  src="<?= Yii::app()->request->baseUrl; ?>/images/icon/vendor_joining/approved.png" alt="Approve" title="Approve" onclick="approveVendorDocs('<?= $parkingPath['id']; ?>', '1')" style="cursor:pointer;">
											</span>
											<span id="apv1_<?= $parkingPath['id']; ?>" style="display:none">Approved</span>

											<span id="rej_<?= $parkingPath['id']; ?>" style="display:none">
												<img id="frLicReject" src="<?= Yii::app()->request->baseUrl; ?>/images/icon/cab/customer_cancel.png" alt="Reject" title="Reject" onclick="approveVendorDocs('<?= $parkingPath['id']; ?>', '2')" style="cursor:pointer;">
											</span>
											<span id="rej1_<?= $parkingPath['id']; ?>">Rejected</span>


										<?php } ?>
									</div>
									<?php
									$i++;
								}
							}
							else
							{
								echo 'Missing';
							}
							?>	

						</div>

					</div>
					<div class="col-xs-12 pt10">
						<div class="col-xs-12 col-sm-2"><b>Others : </b></div>
						<div class="col-xs-12 col-sm-10">
							<?php
							$i = 1;
							if (count($data['others_DutySlipPath']) > 0)
							{
								foreach ($data['others_DutySlipPath'] as $othersPath)
								{
									?>
									<div style="background-color: #FFFFFF; width: 120px; height: 100px; float: left;">
									<?php if($othersPath['images'] != ''){ ?>
										<a href=<?= $othersPath['images'] ?> target="_blank"><img id="frLicApprove"  src="<?= $othersPath['images'] ?>" style="width: 100px; height: 50px;"></a>	
								    <?php } ?>			
										
										<?php
										//echo ($othersPath['images'] != '') ? '<a href="' . $othersPath['images'] . '" target="_blank">Attachment Link' . $i . '</a>' : 'Missing';
										//echo ' | ';
										?>
										<?php
										if ($othersPath['bpay_approved'] == 0)
										{
											?>
											<span id="apv_<?= $othersPath['id']; ?>" style="">
												<img id="frLicApprove"  src="<?= Yii::app()->request->baseUrl; ?>/images/icon/vendor_joining/approved.png" alt="Approve" title="Approve" onclick="approveVendorDocs('<?= $othersPath['id']; ?>', '1')" style="cursor:pointer;">
											</span>
											<span id="apv1_<?= $othersPath['id']; ?>" style="display:none">Approved</span>
											<span id="rej_<?= $othersPath['id']; ?>" style="">
												<img id="frLicReject" src="<?= Yii::app()->request->baseUrl; ?>/images/icon/cab/customer_cancel.png" alt="Reject" title="Reject" onclick="approveVendorDocs('<?= $othersPath['id']; ?>', '2')" style="cursor:pointer;">
											</span>
											<span id="rej1_<?= $othersPath['id']; ?>" style="display:none">Rejected</span>
											<?php
										}if ($othersPath['bpay_approved'] == 1)
										{
											?>		
											<span id="apv_<?= $othersPath['id']; ?>" style="display:none">
												<img id="frLicApprove"  src="<?= Yii::app()->request->baseUrl; ?>/images/icon/vendor_joining/approved.png" alt="Approve" title="Approve" onclick="approveVendorDocs('<?= $othersPath['id']; ?>', '1')" style="cursor:pointer;">
											</span>
											<span id="apv1_<?= $othersPath['id']; ?>">Approved</span>

											<span id="rej_<?= $othersPath['id']; ?>" style="">
												<img id="frLicReject" src="<?= Yii::app()->request->baseUrl; ?>/images/icon/cab/customer_cancel.png" alt="Reject" title="Reject" onclick="approveVendorDocs('<?= $othersPath['id']; ?>', '2')" style="cursor:pointer;">
											</span>
											<span id="rej1_<?= $othersPath['id']; ?>" style="display:none">Rejected</span>

										<?php } ?>
										<?php
										if ($othersPath['bpay_approved'] == 2)
										{
											?>		
											<span id="apv_<?= $othersPath['id']; ?>" style="">
												<img id="frLicApprove"  src="<?= Yii::app()->request->baseUrl; ?>/images/icon/vendor_joining/approved.png" alt="Approve" title="Approve" onclick="approveVendorDocs('<?= $othersPath['id']; ?>', '1')" style="cursor:pointer;">
											</span>
											<span id="apv1_<?= $othersPath['id']; ?>" style="display:none">Approved</span>

											<span id="rej_<?= $othersPath['id']; ?>" style="display:none">
												<img id="frLicReject" src="<?= Yii::app()->request->baseUrl; ?>/images/icon/cab/customer_cancel.png" alt="Reject" title="Reject" onclick="approveVendorDocs('<?= $othersPath['id']; ?>', '2')" style="cursor:pointer;">
											</span>
											<span id="rej1_<?= $othersPath['id']; ?>">Rejected</span>


										<?php } ?>
									</div>
									<?php
									$i++;
								}
							}
							else
							{
								echo 'Missing';
							}
							?>	

						</div>

					</div>

					<div class="col-xs-12 pt10">
						<div class="col-xs-12 col-sm-2"><b>Duty Slip : </b></div>
						<div class="col-xs-12 col-sm-10">
							<?php
							$i = 1;
							if (count($data['duty_DutySlipPath']) > 0)
							{
								foreach ($data['duty_DutySlipPath'] as $dutyPath)
								{
									?>
									<div style="background-color: #FFFFFF; width: 120px; height: 100px; float: left;">
									<?php if($dutyPath['images'] != ''){ ?>
										<a href=<?= $dutyPath['images'] ?> target="_blank"><img id="frLicApprove"  src="<?= $dutyPath['images'] ?>" style="width: 100px; height: 50px;"></a>	
								    <?php } ?>		
										
										<?php
										//echo ($dutyPath['images'] != '') ? '<a href="' . $dutyPath['images'] . '" target="_blank">Attachment Link' . $i . '</a>' : 'Missing';
										//echo ' | ';
										?>
										<?php
										if ($dutyPath['bpay_approved'] == 0)
										{
											?>
											<span id="apv_<?= $dutyPath['id']; ?>" style="">
												<img id="frLicApprove"  src="<?= Yii::app()->request->baseUrl; ?>/images/icon/vendor_joining/approved.png" alt="Approve" title="Approve" onclick="approveVendorDocs('<?= $dutyPath['id']; ?>', '1')" style="cursor:pointer;">
											</span>
											<span id="apv1_<?= $dutyPath['id']; ?>" style="display:none">Approved</span>
											<span id="rej_<?= $dutyPath['id']; ?>" style="">
												<img id="frLicReject" src="<?= Yii::app()->request->baseUrl; ?>/images/icon/cab/customer_cancel.png" alt="Reject" title="Reject" onclick="approveVendorDocs('<?= $dutyPath['id']; ?>', '2')" style="cursor:pointer;">
											</span>
											<span id="rej1_<?= $dutyPath['id']; ?>" style="display:none">Rejected</span>
											<?php
										}if ($dutyPath['bpay_approved'] == 1)
										{
											?>		
											<span id="apv_<?= $dutyPath['id']; ?>" style="display:none">
												<img id="frLicApprove"  src="<?= Yii::app()->request->baseUrl; ?>/images/icon/vendor_joining/approved.png" alt="Approve" title="Approve" onclick="approveVendorDocs('<?= $dutyPath['id']; ?>', '1')" style="cursor:pointer;">
											</span>
											<span id="apv1_<?= $dutyPath['id']; ?>">Approved</span>

											<span id="rej_<?= $dutyPath['id']; ?>" style="">
												<img id="frLicReject" src="<?= Yii::app()->request->baseUrl; ?>/images/icon/cab/customer_cancel.png" alt="Reject" title="Reject" onclick="approveVendorDocs('<?= $dutyPath['id']; ?>', '2')" style="cursor:pointer;">
											</span>
											<span id="rej1_<?= $dutyPath['id']; ?>" style="display:none">Rejected</span>

										<?php } ?>
										<?php
										if ($dutyPath['bpay_approved'] == 2)
										{
											?>		
											<span id="apv_<?= $dutyPath['id']; ?>" style="">
												<img id="frLicApprove"  src="<?= Yii::app()->request->baseUrl; ?>/images/icon/vendor_joining/approved.png" alt="Approve" title="Approve" onclick="approveVendorDocs('<?= $dutyPath['id']; ?>', '1')" style="cursor:pointer;">
											</span>
											<span id="apv1_<?= $dutyPath['id']; ?>" style="display:none">Approved</span>

											<span id="rej_<?= $dutyPath['id']; ?>" style="display:none">
												<img id="frLicReject" src="<?= Yii::app()->request->baseUrl; ?>/images/icon/cab/customer_cancel.png" alt="Reject" title="Reject" onclick="approveVendorDocs('<?= $dutyPath['id']; ?>', '2')" style="cursor:pointer;">
											</span>
											<span id="rej1_<?= $dutyPath['id']; ?>">Rejected</span>


										<?php } ?>
									</div>
									<?php
									$i++;
								}
							}
							else
							{
								echo 'Missing';
							}
							?>	

						</div>
					</div>
				</div>
				<?php if($viewds==''){ ?>
				<div class="row">
					<div class="col-xs-12 text-left">
						<input type="checkbox" name="doc_all" id="doc_all" value="" style="margin:50px 0 0 18px;" onclick="setdutyReceived(<?= $bkgID ?>);"> Received All Documents
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 text-center">
						<button type="button" class="btn btn-success" onclick="approveMarkComplete(<?= $bkgID ?>);" >Mark Complete</button>
					</div>
				</div>
				<?php } ?>

			</div>
		</div>    
	</div>
</div>  


<script>
    function approveVendorDocs(id, status)
    {
        var href = '<?= Yii::app()->createUrl("admin/booking/approveDoc"); ?>';
        $.ajax({
            "url": href,
            "type": "GET",
            "dataType": "html",
            "data": {"bpay_id": id, "bpay_status": status},
            "success": function (data1)
            {
                var yy = jQuery.parseJSON(data1);
                //console.log(yy.approve_status);
                if (yy.approve_status == 1)
                {
                    $('#rej1_' + yy.bpay_id).hide();
                    $('#rej_' + yy.bpay_id).show();
                    $('#apv_' + yy.bpay_id).hide();
                    $('#apv1_' + yy.bpay_id).show();
                }
                if (yy.approve_status == 2)
                {
                    $('#rej1_' + yy.bpay_id).show();
                    $('#rej_' + yy.bpay_id).hide();
                    $('#apv_' + yy.bpay_id).show();
                    $('#apv1_' + yy.bpay_id).hide();
                }

            }
        });
        return false;
    }

    function approveMarkComplete(booking_id)
    {
        var docAll = document.getElementById("doc_all");
        if (docAll.checked)
        {
            $href = $adminUrl + "/booking/completebooking";
            var $booking_id = booking_id;

            jQuery.ajax({type: 'GET',
                url: $href,
                data: {"bkid": $booking_id},
                dataType: "json",
                success: function (data)
                {
                    if (data.success)
                    {
                        updateGrid(5);
                        removeTabCache(6);
						approveBox.modal("hide");
                    }
                }
            });
        } else
        {
            alert("Have You Received All Documents ! if YES click Received All Documents");
        }


    }

    function setdutyReceived(booking_id)
    {
        var docAll = document.getElementById("doc_all");
        if (docAll.checked)
        {
            var dutySlip = 1;
            var href = '<?= Yii::app()->createUrl("admin/booking/setdutyReceived"); ?>';
            var $dutySlipRequired = dutySlip;
            var $booking_id = booking_id;
            //alert($dutySlipRequired);
            //alert($booking_id);
            jQuery.ajax({type: 'GET',
                url: href,
                data: {"bkid": $booking_id, "dutyReq": $dutySlipRequired},
                dataType: "json",
                success: function (data)
                {
                    if (data.success)
                    {
                        //updateGrid(5);
                        //removeTabCache(6);
                    }
                }
            });

        } else
        {
            var dutySlip = 0;
            var href = '<?= Yii::app()->createUrl("admin/booking/setdutyReceived"); ?>';
            var $dutySlipRequired = dutySlip;
            var $booking_id = booking_id;
            //alert($dutySlipRequired);
            //alert($booking_id);
            jQuery.ajax({type: 'GET',
                url: href,
                data: {"bkid": $booking_id, "dutyReq": $dutySlipRequired},
                dataType: "json",
                success: function (data)
                {
                    if (data.success)
                    {
                        //updateGrid(5);
                        //removeTabCache(6);
                    }
                }
            });
        }
        return false;
    }

</script>