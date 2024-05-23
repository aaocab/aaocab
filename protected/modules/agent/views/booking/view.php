<?php
/* @var $model Booking */
//$cityList = CHtml::listData(Cities::model()->findAll(array('order' => 'cty_name', 'condition' => 'cty_active=:act', 'params' => array(':act' => '1'))), 'cty_id', 'cty_name');
$status			 = Booking::model()->getBookingStatus();
//$adminlist = Admins::model()->findNameList();
//$statuslist = Booking::model()->getActiveBookingStatus();
$reconfirmStatus = Booking::model()->getReconfirmStatus();
$cancelDetail	 = CancelReasons::model()->findByPk($model->bkg_cancel_id);
$rutInfo		 = [];
$cntRut			 = count($bookingRouteModel);
$spclInstruction = $model->getFullInstructions();
$vencabdriver	 = $model->getBookingCabModel();
$gozoAmount		 = ($model->bkgInvoice->bkg_gozo_amount != '') ? $model->bkgInvoice->bkg_gozo_amount : $model->bkgInvoice->bkg_total_amount - $model->bkgInvoice->bkg_vendor_amount;
$dueAmount		 = ($model->bkgInvoice->bkg_due_amount != '') ? $model->bkgInvoice->bkg_due_amount : $model->bkgInvoice->bkg_total_amount - $model->bkgInvoice->getTotalPayment();
$grossAmount	 = $model->bkgInvoice->calculateGrossAmount();
$response		 = Contact::referenceUserData($model->bkgUserInfo->bui_id, 3);
if ($response->getStatus())
{
	$contactNo	 = $response->getData()->phone['number'];
	$countryCode = $response->getData()->phone['ext'];
	$firstName	 = $response->getData()->email['firstName'];
	$lastName	 = $response->getData()->email['lastName'];
	$email		 = $response->getData()->email['email'];
}
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
    .control-label{
        font-weight: bold
    }   
    .bordered {
        border: 1px solid #ddd;
        min-height: 45px;
        line-height: 1.2em;
        margin-bottom: 10px;
        margin-left: 0;
        margin-right: 0;
        padding-bottom: 10px;

    }
    .new-tab1{
        margin-top: 5px
    }

</style>
<div class="container-fluid">
<div class="row">
    <div class="col-xs-12">
        <h2 class="text-center font-24 mt0 mb20">Booking Id:<b><?= $model->bkg_booking_id ?></b></h2>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-md-12 col-lg-7 new-booking-list">
		<div class="table-responsive">
			<table class="table table-striped table-bordered" width="100%">
			<tr>
				<td colspan="4" class="text-center"><h2 class="font-14 m0 text-uppercase"><b>Booking Information</b></h2></td>
			</tr>
			<tr>
				<td width="18%"><b>Name</b></td>
				<td width="32%"><div class="hide"><a href="#" onclick="showLinkedUser(<?= $model->bkgUserInfo->bkg_user_id ?>, '<?= Yii::app()->createUrl('admin/user/details') ?>')"><?= $firstName . ' ' . $lastName; ?></a></div><?= $firstName . ' ' . $lastName; ?></td>
				<td width="18%"><b>Email:</b></td>
				<td width="32%"><?= ($email != '') ? $email : " "; ?></td>
			</tr>
			<tr>
				<td><b>Contact:</b></td>
				<td><?= ($contactNo != '') ? '+' . $countryCode . '-' . $contactNo : " "; ?></td>
				<td><b>Alternate Contact:</b></td>
				<td><?= ($model->bkgUserInfo->bkg_alt_contact_no != '') ? '+' . $model->bkgUserInfo->bkg_alt_country_code . '-' . $model->bkgUserInfo->bkg_alt_contact_no : " "; ?></td>
			</tr>
			<tr>
				<td><b>Booking Type:</b></td>
				<td><?= Booking::model()->getBookingType($model->bkg_booking_type); ?></td>
				<td><b>Booking Status:</b></td>
				<td><?= $status[$model->bkg_status] ?> <?php
								if ($model->bkg_status != '9' || $model->bkg_status != '8')
								{
									echo '(' . $reconfirmStatus[$model->bkg_reconfirm_flag] . ')';
								}
								?></td>
			</tr>
			<tr>
				<td><b>Route:</b></td>
				<td><?= $model->bkgFromCity->cty_name . ' to ' . $model->bkgToCity->cty_name; ?></td>
				<td><b>Cab Type:</b></td>
				<td><?=
								$model->bkgSvcClassVhcCat->scc_ServiceClass->scc_label . " (" . $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label . ")";
								?></td>
			</tr>
			<tr>
				<td><b>Trip Distance:</b></td>
				<td><?= ($model->bkg_trip_distance != '') ? $model->bkg_trip_distance . " Km" : "&nbsp;" ?></td>
				<td><b>Trip Duration:</b></td>
				<td><?= Filter::getDurationbyMinute($model->bkg_trip_duration) ?></td>
			</tr>
			<tr>
				<td><b>Pickup Date:</b></td>
				<td><?= date('d/m/Y', strtotime($model->bkg_pickup_date)); ?></td>
				<td><b>Pickup Time:</b></td>
				<td><?= date('h:i A', strtotime($model->bkg_pickup_date)); ?></td>
			</tr>
			<tr>
				<td><b>Create Date:</b></td>
				<td><?= date('d/m/Y', strtotime($model->bkg_create_date)); ?></td>
				<td><b>Create Time:</b></td>
				<td><?= date('h:i A', strtotime($model->bkg_create_date)); ?></td>
			</tr>
			<tr>
				<?
				if ($model->bkg_return_date != '' && $model->bkg_booking_type == '2') {
					?>
					<td><b>Return Date:</b></td>
					<td><?= date('d/m/Y', strtotime($model->bkg_return_date)); ?></td>
					<td><b>Return Time:</b></td>
					<td><?= date('h:i A', strtotime($model->bkg_return_date)); ?></td>
				<? } ?>
			</tr>
			<tr>
				<?
				if ($model->bkg_agent_id > 0) {
					$agtModel = Agents::model()->findByPk($model->bkg_agent_id);
					?>
				<td><b><?= ($model->bkg_agent_id > 0 && $agtModel->agt_type == 1) ? "Corporate" : "Agent" ?> Name:</b></td>
				<td><?php echo ($model->bkgAgent->agt_company); ?></td>
				<? } ?>
				<?
				if ($model->bkgAddInfo->bkg_file_path != '') {
					?>
				<b>File Path:</b>
				<a href="<?= $model->bkgAddInfo->bkg_file_path ?>" target="_blank">File</a>
				<? } ?>
				<?
					if (($model->bkg_status == 8 || $model->bkg_status == 9) && $model->bkg_cancel_delete_reason != '')
					{
						?>
						<?
						$reason = '';
						if ($model->bkg_status == 8)
						{
							$reason = 'Delete';
						}
						if ($model->bkg_status == 9)
						{
							$reason = 'Cancel';
						}
						?>
				<td><b><?= $reason ?> Reason:</b></td>
				<td><?= $model->bkg_cancel_delete_reason . "$cancelDetail->cnr_reason" ?></td>
<? } ?>
			</tr>
		</table>
        </div>
        <?php
        if ($cntRut > 0)
		{
			$diffdays = 0;
			foreach ($bookingRouteModel as $key => $bookingRoute)
			{
				$rutName		 = $bookingRoute->brtFromCity->cty_name . ' to ' . $bookingRoute->brtToCity->cty_name;
				$pickLoc		 = $bookingRoute->brt_from_location;
				$pickDateTime	 = DateTimeFormat::DateTimeToDatePicker($bookingRoute->brt_pickup_datetime) . " " . DateTimeFormat::DateTimeToTimePicker($bookingRoute->brt_pickup_datetime);
				$dist			 = $bookingRoute->brt_trip_distance . 'Km';
				$dura			 = Filter::getDurationbyMinute($bookingRoute->brt_trip_duration);

				if ($key == 0)
				{
					$diffdays = 1;
				}
				else
				{
					$date1		 = new DateTime(date('Y-m-d', strtotime($bookingRouteModel[0]->brt_pickup_datetime)));
					$date2		 = new DateTime(date('Y-m-d', strtotime($bookingRoute->brt_pickup_datetime)));
					$difference	 = $date1->diff($date2);
					$diffdays	 = ($difference->d + 1);
				}

				$last_date	 = date('Y-m-d H:i:s', strtotime($bookingRoute->brt_pickup_datetime . '+ ' . $bookingRoute->brt_trip_duration . ' minute'));
				$rutInfo[]	 = ['rutName'		 => $rutName, 'pickLoc'		 => $pickLoc, 'pickDateTime'	 => $pickDateTime,
					'dist'			 => $dist, 'dura'			 => $dura, 'diffdays'		 => $diffdays, 'last_date'		 => $last_date];
			}
		}
        
        
        ?>
         <div class="row mt20">
            <div class="col-xs-12">
                <div class="table-responsive">
                    <table class="table table-responsive table-bordered compact mb0" style="background: #fff!important;">
                        <thead>
                            <tr class="all_detailss">
                                <td class="col-xs-4 text-center text-uppercase"><b>Route</b></td>

                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="row m0 mb5"><div class="col-xs-12 font11x"><b><?= $rutInfo[0]['rutName'] ?></b> </div></div>
                                    <div class="row m0 mb5"><div class="col-xs-12"><b>Pickup Location:</b> <?= $rutInfo[0]['pickLoc'] ?></div></div>
                                    <div class="row m0 mb5"><div class="col-xs-12"><b>Pickup Time:</b> <?= $rutInfo[0]['pickDateTime'] ?></div></div>
                                    <div class="row m0 mb5"><div class="col-xs-12 col-lg-5"><b>Duration:</b> <nobr><?= $rutInfo[0]['dura'] ?> </nobr></div><div class="col-xs-12 col-lg-4"> <b>Distance:</b> <nobr><?= $rutInfo[0]['dist'] ?></nobr></div><div class="col-xs-12 col-lg-3"> <b>Day:</b> <?= $rutInfo[0]['diffdays'] ?></div></div>
                                </td>

                            </tr>
							<?
							if ($cntRut > 1)
							{
								for ($i = 1; $i < $cntRut; $i++)
								{
									?>
									<tr>
										<td>
											<div class="row m0"><div class="col-xs-12 font11x"><b><?= $rutInfo[$i]['rutName'] ?></b></div></div>
											<div class="row m0"><div class="col-xs-12"><b>Pickup Location:</b> <?= $rutInfo[$i]['pickLoc'] ?></div></div>
											<div class="row m0"><div class="col-xs-12"><b>Pickup Time:</b> <?= $rutInfo[$i]['pickDateTime'] ?></div></div>
											<div class="row m0"><div class="col-xs-5"><b>Duration:</b> <?= $rutInfo[$i]['dura'] ?> </div><div class="col-xs-4"> <b>Distance:</b> <?= $rutInfo[$i]['dist'] ?> </div><div class="col-xs-3"> <b>Day:</b> <?= $rutInfo[$i]['diffdays'] ?></div></div>
										</td>
									</tr>
									<?
								}
							}
							?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-md-12 col-lg-5 new-booking-list">
        
		<?
		
        $staxrate	 = $model->bkgInvoice->getServiceTaxRate();
					$taxLabel	 = ($staxrate == 5) ? 'GST' : 'Service Tax ';
		?>
  
       
         <div class="row">
            <div class="col-xs-12">
                <div class="table-responsive">
                    <table class="table table-responsive table-bordered compact mb0" style="background: #fff!important;">
                        <thead>
                            <tr class="all_detailss">
                                <td class="col-xs-4 text-center text-uppercase"><b>INVOICE</b></td>

                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <?php
                                   // echo "<pre>";
                                   // print_r($model->bkgInvoice);
                                    ?>
                                      <div class="row m0 mb10"><div class="col-xs-6">Rate per kilometer:</div><div class="col-xs-6 text-right">&#x20b9;<?= $model->bkgInvoice->bkg_rate_per_km ?></div></div>
                                     <div class="row m0 mb10"><div class="col-xs-6">Charges (per km) after <?= $model->bkg_trip_distance; ?> km:</div><div class="col-xs-6 text-right">&#x20b9;<?= $model->bkgInvoice->bkg_rate_per_km_extra ?></div></div> 
                                    
                                    
                                    
                                    <div class="row m0 mb10"><div class="col-xs-6">Base Fare:</div><div class="col-xs-6 text-right">&#x20b9;<?= $model->bkgInvoice->bkg_base_amount; ?></div></div>
                                    <?php
                                    if ($model->bkgInvoice->bkg_is_toll_tax_included == 1 && $model->bkgInvoice->bkg_toll_tax > 0)
                                    {
                                        ?>
                                        <div class="row m0 mb10"><div class="col-xs-6">Toll Tax:</div><div class="col-xs-6 text-right">&#x20b9;<?= $model->bkgInvoice->bkg_toll_tax; ?></div></div>
                                        <?
                                    }
                                    if ($staxrate != 5)
                                    {
                                        ?>
                                        <div class="row m0 mb10"><div class="col-xs-6"><?= $taxLabel ?>:</div><div class="col-xs-6 text-right">&#x20b9;<?= $model->bkgInvoice->bkg_service_tax; ?></div></div>
                                    <?php } if ($model->bkgInvoice->bkg_state_tax > 0)
                                    { ?>
                                    <div class="row m0 mb10"><div class="col-xs-6">Other Tax:</div><div class="col-xs-6 text-right">&#x20b9;<?= $model->bkgInvoice->bkg_state_tax ?></div></div>
                                     <?php } ?>
                                    <div class="row m0 mb10"><div class="col-xs-6">IGST:</div><div class="col-xs-6 text-right">&#x20b9;<?= ((Yii::app()->params['igst'] / $staxrate) * $model->bkgInvoice->bkg_service_tax) | 0; ?></div></div>
                                    
                                    <div class="row m0 mb10"><div class="col-xs-6">Due Amount:</div><div class="col-xs-6 text-right">&#x20b9;<?= $model->bkgInvoice->bkg_due_amount ?></div></div>
                               
                                  <div class="row m0 mb10 bg-blue3 pt10 pb10" style="border-radius: 5px;"><div class="col-xs-6"><b>Total Amount:</b></div><div class="col-xs-6 text-right font-16"><b>&#x20b9;<?= $model->bkgInvoice->bkg_total_amount ?></b></div></div>
                                
                             
                                
                                
                                </td>

                            </tr>
							
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row pt20">
            <div class="col-xs-12">
                <div class="table-responsive panel panel-primary compact">
                    <table class="table table-striped table-bordered"  >
                        <thead>
                            <tr>
                                <th>
                                    <span class="text-uppercase">Pickup Location</span>
                                </th>
                                <th>
                                    <span class="text-uppercase">Dropoff Location</span>
                                </th>
                                <th>
                                    <span class="text-uppercase">Additional Information</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
									<?= $model->bkg_pickup_address; ?>
                                </td>
                                <td>
									<?= $model->bkg_drop_address; ?>
                                </td>
                                <td>
									<?= ($spclInstruction != "") ? $spclInstruction : "&nbsp;" ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div> 
            </div>
        </div>
    </div>
</div>
</div>











<script type="text/javascript">
    var acctbox;
    $(document).ready(function () {
        $('#view').show();
        $('#edit').hide();
        var flag = '<?= $model->bkgPref->bkg_account_flag ?>';
        if (flag == '1') {
            $("#clearFlag").show();
            $("#setFlag").hide();
        } else {
            $("#setFlag").show();
            $("#clearFlag").hide();
        }
    });

    function showLinkedUser(user, url) {
        if (user > 0)
        {
            jQuery.ajax({type: 'GET',
                url: url,
                dataType: 'html',
                data: {"user": user},
                success: function (data)
                {
                    showuser = bootbox.dialog({
                        message: data,
                        title: 'User Details',
                        size: 'large',
                        onEscape: function () {
                        }
                    });
                    showuser.on('hidden.bs.modal', function (e) {
                        $('body').addClass('modal-open');
                    });
                    return true;
                },
                error: function (x) {
                    alert(x);
                }
            });
        }
    }
    function viewList(obj) {
        var href2 = $(obj).attr("href");

        $.ajax({
            "url": href2,
            "type": "GET",
            "dataType": "html",
            "success": function (data) {
                var box = bootbox.dialog({
                    message: data,
                    title: 'Booking Details',
                    size: 'large',
                    onEscape: function () {
                        // user pressed escape
                    },
                });
            }
        });
        return false;
    }

    function showContact() {
        bkgId = '<?= $model->bkg_id ?>';
        if (bkgId > 0) {

            $href = "<?= Yii::app()->createUrl('admin/booking/getcontacts') ?>";


            jQuery.ajax({type: 'GET',
                url: $href,
                dataType: 'json',
                data: {"bkgId": bkgId},
                success: function (data) {
                    if (data.success) {
                        $("#userEmail").text(data.email);
                        $("#userPhone").text(data.phone);
                        $("#userAltPhone").text(data.altPhone);
                        $("#showContactDetails").hide();
                    } else {
                        alert("Sorry error occured");
                    }
                },
                error: function (x) {
                    alert(x);
                }
            });
        }
    }
</script>
