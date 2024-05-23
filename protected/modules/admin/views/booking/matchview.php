<?php
$cityList	 = CHtml::listData(Cities::model()->findAll(array('order' => 'cty_name', 'condition' => 'cty_active=:act', 'params' => array(':act' => '1'))), 'cty_id', 'cty_name');
$status		 = Booking::model()->getBookingStatus();
$adminlist	 = Admins::model()->findNameList();
$statuslist	 = Booking::model()->getActiveBookingStatus();
$bookingType = array(1 => 'One way', 2 => 'Return');
$locked		 = ' <i class="fa fa-lock"></i>';
$css		 = ($isAjax) ? "col-xs-12" : "";
/* @var $model booking */
//echo $model->brt_bkg_id;
//echo $model->bookingRoutes['0']->brt_bkg_id;
//echo $model->bkg_id;
//var_dump($model->attributes);
//
//exit;
$advamt		 = $model->bkgInvoice->bkg_advance_amount;
$amtdue		 = $model->bkgInvoice->bkg_due_amount;
$refund		 = $model->bkgInvoice->bkg_refund_amount;
$vndamt		 = $model->bkgInvoice->bkg_vendor_amount;
$bkgamt		 = $model->bkgInvoice->bkg_total_amount;
$gzamount	 = ($gzamount == '') ? $bkgamt - $vndamt : $gzamount;
//$vndcolamt = $bkgamt + $refund - $advamt - $amtdue;
$vndcolamt	 = $model->bkgInvoice->bkg_vendor_collected;
$response	 = Contact::referenceUserData($model->bkgUserInfo->bui_id, 3);
if ($response->getStatus())
{
	$contactNo	 = $response->getData()->phone['number'];
	$countryCode = $response->getData()->phone['ext'];
	$firstName	 = $response->getData()->email['firstName'];
	$lastName	 = $response->getData()->email['lastName'];
	$email		 = $response->getData()->email['email'];
}
//echo $gzdue = $gzamount - $advamt;
//echo '<br>' . $vndcolamt;
//exit;
?>
<style>
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
    .rating-cancel{
        display: none !important;
        visibility: hidden !important;
    }
    div .comments {
        border-bottom:1px #333 solid;
        padding:3px;
        line-height: 14px;
        font-weight: normal;
    }

    div .comments .comment {
        padding:3px;max-width:100%
    }
    div .comments .footer {
        padding:2px 5px;
        color: #888;
        text-align: right;
        font-style: italic;
        font-size: 0.85em;
        height: auto;
        width: auto;
    }   

    .remarkbox{
        width: 100%; 
        padding: 3px;  
        overflow: auto; 
        line-height: 14px; 
        font: normal arial; 
        border-radius: 5px; 
        -moz-border-radius: 5px; 
        border: 1px #aaa solid;
    }

</style>

<div class="row">
    <div class="col-xs-12 text-center h2 mt0">
        <label for="type" class="control-label"><span style="font-weight: normal; font-size: 30px;">Booking Id:</span> </label>
        <b><?= $model->bkg_booking_id ?></b>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 mb20">
        <div style="text-align: center" class="below-buttons">
			<? $button_type = 'label'; ?>
			<?= $model->getActionButton([], $button_type); ?>
			<? $ratingModel = Ratings::model()->getRatingbyBookingId($model->bkg_id); ?>
			<?
			if ($model->bkg_status > 4 && $ratingModel->rtg_customer_overall == '')
			{
				?>
				<a class="btn btn-info mt5" id="review" onclick="addCustRating(<?= $model->bkg_id ?>)" title="Add Customer Review"><i class="fa fa-star-o"></i> Add Customer Review</a>
			<? } ?>
			<?
			if ($model->bkg_status > 4 && $ratingModel->rtg_csr_customer == '')
			{
				?>
				<a class="btn btn-info mt5 ml5" id="review" onclick="addCSRRating(<?= $model->bkg_id ?>)" title="Add CSR Review"><i class="fa fa-star-o"></i> Add CSR Review</a>
			<? } ?>
        </div>
    </div>
</div>


<div class="row" id="view">

    <div class="col-xs-12">
        <div class="hostory_left">
            <div class="row">
                <div class="col-xs-12 pt10">
                    <div class="col-xs-12 col-sm-6 col-md-3">
                        <div class="row p5">
                            <div class="col-xs-6 col-sm-12"><b>Name: </b></div>
                            <div class="col-xs-6 col-sm-12"><?= $firstName . ' ' . $lastName; ?></div>
                        </div>
                        <div class="row p5">
                            <div class="col-xs-6 col-sm-12"><b>Contact: </b></div>
							<?
							if ($contactNo != '')
							{
								?>
								<div class="col-xs-6 col-sm-12">+<?= $countryCode; ?>-<?= $contactNo; ?></div>
								<?
							}
							else
							{
								?>
								<div class="col-xs-6 col-sm-12">&nbsp;</div>
							<? } ?>
                        </div>
                        <div class="row p5">
                            <div class="col-xs-6 col-sm-12"><b>Route: </b></div>
                            <div class="col-xs-6 col-sm-12"><?= $cityList[$model->bkg_from_city_id] . ' to ' . $cityList[$model->bkg_to_city_id]; ?></div>
                        </div>
                        <div class="row p5">
                            <div class="col-xs-6 col-sm-12"><b>Estimated Distance: </b></div>
                            <div class="col-xs-6 col-sm-12"><?= ($model->bkg_trip_distance != '') ? $model->bkg_trip_distance . " Km" : "&nbsp;" ?></div>
                        </div>
                        <div class="row p5">
                            <div class="col-xs-6 col-sm-12"><b>Cab Type: </b></div>
                            <div class="col-xs-6 col-sm-12"><?= SvcClassVhcCat::model()->getVctSvcList('string', '', $model->bkgSvcClassVhcCat->scv_vct_id); ?></div>
                        </div>
						<?
						if ($model->bkgInvoice->bkg_promo1_code != '')
						{
							?>
							<div class="row p5">
								<div class="col-xs-6 col-sm-12"><b>Promo Code: </b></div>
								<div class="col-xs-6 col-sm-12"><?= strtoupper($model->bkgInvoice->bkg_promo1_code) ?></div>
							</div> 
						<? } ?>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-3">
                        <div class="row p5">
                            <div class="col-xs-6 col-sm-12"><b>Email Id: </b></div>
                            <div class="col-xs-6 col-sm-12"><?= ($email != '') ? $email : "&nbsp;" ?></div>
                        </div>
                        <div class="row p5">
                            <div class="col-xs-6 col-sm-12"><b>Alternate Contact: </b></div>
							<?
							if ($model->bkgUserInfo->bkg_alt_contact_no != '')
							{
								?>
								<div class="col-xs-6 col-sm-12">+<?= $model->bkgUserInfo->bkg_alt_country_code; ?>-<?= $model->bkgUserInfo->bkg_alt_contact_no; ?></div>
								<?
							}
							else
							{
								?>
								<div class="col-xs-6 col-sm-12">&nbsp;</div>
							<? } ?>
                        </div>
                        <div class="row p5">
                            <div class="col-xs-6 col-sm-12"><b>Booking Type: </b></div>
                            <div class="col-xs-6 col-sm-12"><?= $bookingType[$model->bkg_booking_type] ?></div>
                        </div>
                        <div class="row p5">
                            <div class="col-xs-6 col-sm-12"><b>Estimated Duration: </b></div>
							<?
							if ($model->bkg_trip_duration != '')
							{
								?>
								<div class="col-xs-6 col-sm-12"><?
									$hr	 = date('G', mktime(0, $model->bkg_trip_duration)) . " Hr";
									$min = (date('i', mktime(0, $model->bkg_trip_duration)) != '00') ? ' ' . date('i', mktime(0, $model->bkg_trip_duration)) . " min" : '';
									echo $hr . $min;
									?>  </div>
								<?
							}
							else
							{
								?>
								<div class="col-xs-6 col-sm-12">&nbsp;</div>
							<? } ?>
                        </div>
                        <div class="row p5 pb20">
                            <div class="col-xs-6 col-sm-12"><b>Info source: </b></div>
                            <div class="col-xs-6 col-sm-12"><?= ( $model->bkgAddInfo->bkg_info_source != '') ? $model->bkgAddInfo->bkg_info_source : "&nbsp;" ?></div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-3">
                        <div class="row p5">
                            <div class="col-xs-6 col-sm-12"><b>Status: </b></div>
                            <div class="col-xs-6 col-sm-12"><?= $status[$model->bkg_status] ?></div>
                        </div>
                        <div class="row p5">
                            <div class="col-xs-6 col-sm-12"><b>Pickup Date: </b></div>
                            <div class="col-xs-6 col-sm-12"><?= date('d/m/Y', strtotime($model->bkg_pickup_date)); ?></div>
                        </div>
                        <div class="row p5">
                            <div class="col-xs-6 col-sm-12"><b>Pickup Time: </b></div>
                            <div class="col-xs-6 col-sm-12"><?= date('h:i A', strtotime($model->bkg_pickup_date)); ?></div>
                        </div>
                        <div class="row p5">
                            <div class="col-xs-6 col-sm-12"><b>Return Date: </b></div>
                            <div class="col-xs-6 col-sm-12"><?= ($model->bkg_booking_type == '2') ? date('d/m/Y', strtotime($model->bkg_return_date)) : "&nbsp;" ?></div>
                        </div>
                        <div class="row p5">
                            <div class="col-xs-6 col-sm-12"><b>Return Time: </b></div>
                            <div class="col-xs-6 col-sm-12"><?= ($model->bkg_booking_type == '2') ? date('h:i A', strtotime($model->bkg_return_date)) : "&nbsp;" ?></div>
                        </div>
                        <div class="row p5 pb20">
                            <div class="col-xs-6 col-sm-12"><b>Verification Code: </b></div>
                            <div class="col-xs-6 col-sm-12"><?= ($model->bkgUserInfo->bkg_verification_code != '') ? $model->bkgUserInfo->bkg_verification_code : "&nbsp;" ?></div>
                        </div>
                    </div>

                    <div class="hostory_rightdeep col-xs-12 col-sm-6 col-md-3 mt10 n">
                        <div class="row">
                            <div class="col-xs-6 col-md-12 col-lg-6 text-center" id="AccountFlagBlock">
                                <a class="btn btn-primary btn-sm" id="setFlag" style="display: none;" onclick="accountFlag(<?= $model->bkg_id ?>, '0')" title="Set accounting flag" style="">Set accounting flag</a>    
                                <a class="btn btn-success btn-sm" id="clearFlag" style="display: none;" onclick="accountFlag(<?= $model->bkg_id ?>, '1')" title="Clear accounting flag" style="">Clear accounting flag</a>
                            </div>
                            <div class="col-xs-6 col-md-12 col-lg-6 text-center">
                                <a class="btn btn-info btn-sm" id="bkg_acct" onclick="editAccount()" title="Edit Account Details" style="">Edit Account Details</a>
                            </div>
                        </div> 
						<?
						$this->renderPartial('accountsdetail', ['model' => $model]);
						?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
					<?
					if ($model->bkg_cancel_delete_reason != '')
					{
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
						<div class="col-xs-12">
							<div class="row">
								<div class="hostory_leftdeep">
									<div class="col-xs-12">
										<div class="row p5">
											<div class="col-xs-12">
												<b><?= $reason ?> Reason: </b><?= ($model->bkg_cancel_delete_reason != '') ? $model->bkg_cancel_delete_reason : " " ?>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<?
					}
					?>


                    <div class="col-xs-12">
                        <div class="row">
                            <div class="hostory_leftdeep">
                                <div class="col-xs-12 col-sm-6 col-md-4">
                                    <div class="row p5">
                                        <div class="col-xs-6 col-sm-12"><b>Pickup Location: </b></div>
                                        <div class="col-xs-6 col-sm-12"><?= $model->bkg_pickup_address; ?></div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-4">
                                    <div class="row p5">
                                        <div class="col-xs-6 col-sm-12"><b>Dropoff Location: </b></div>
                                        <div class="col-xs-6 col-sm-12"><?= $model->bkg_drop_address; ?></div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-4">
                                    <div class="row p5">
                                        <div class="col-xs-6 col-sm-12"><b>Additional Information: </b></div>
                                        <div class="col-xs-6 col-sm-12"><?= ($model->bkg_instruction_to_driver_vendor != "") ? $model->bkg_instruction_to_driver_vendor : "&nbsp;" ?></div>
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
<div class="row mt20">
    <div class="col-xs-12">
        <div class="table-responsive">
            <table class="table table-bordered">
                <tr class="all_detailss">
                    <td class="col-xs-3 text-center"><b>Route</b></td>
                    <td class="col-xs-3 text-center"><b>Vendor</b></td>
                    <td class="col-xs-3 text-center"><b>Driver</b></td>
                    <td class="col-xs-3 text-center"><b>Cab</b></td>
                </tr>
				<?
//var_dump($model->bookingRoutes);/*
				if ($model->bkgBcb->bcb_vendor_id != '')
				{
					foreach ($model->bookingRoutes as $routes)
					{
						?>
						<tr>
							<td>
								<p>Route: <b><?= $routes->brtFromCity->cty_name . ' - ' . $routes->brtToCity->cty_name; ?></b></br>
									Pickup Location: <?= $routes->brtFromCity->cty_name ?></br>
									Pickup Time: <?= DateTimeFormat::DateTimeToLocale($routes->brt_pickup_datetime); ?>
							</td>
							<?
							$vencabdriver = $routes->brtBcb;
							$vehicleModel = $vencabdriver->bcbCab->vhcType->vht_model;
							if($vencabdriver->bcbCab->vhc_type_id === Config::get('vehicle.genric.model.id'))
							{
								$vehicleModel = OperatorVehicle::getCabModelName($vencabdriver->bcb_vendor_id, $vencabdriver->bcb_cab_id);
							}
							?>
							<td  style="vertical-align: middle;">
								Name: <b>
									<?= $vencabdriver->bcbVendor->vnd_name ?>
								</b></br>
								Phone: <?= $vencabdriver->bcbVendor->vnd_phone ?><br>
								Rating: <?= $vencabdriver->bcb_vendor_rating ?><br>
								Lifetime Trips: <?= $vencabdriver->bcb_vendor_trips ?>
							</td>
							<td   style="vertical-align: middle;">
								Name: <b><?= $vencabdriver->bcb_driver_name ?></b></br>
								Phone: <?= $vencabdriver->bcb_driver_phone ?><br>
								Rating: <?= $vencabdriver->bcb_driver_rating ?><br>
								Lifetime Trips: <?= $vencabdriver->bcb_driver_trips ?>
							</td>
							<td   style="vertical-align: middle;">
								Vehicle name: <b><?= $vehicleModel; ?></b><br>
								Car number: <?= $vencabdriver->bcbCab->vhc_number ?><br>
								Rating: <?= $vencabdriver->bcb_cab_rating ?><br>
								Lifetime Trips: <?= $vencabdriver->bcb_cab_trips ?>
							</td>
							<? ?>
						</tr>
						<?
					}
				}
				?>
            </table>
        </div>
    </div>
</div>





<?
if ($model->bkgAddInfo->bkg_file_path != '')
{
	?>
	<div class="row">
		<div class="col-xs-12">
			<div class="form-group mt20 mb20">
				<div class = "col-xs-12 p0">
					<label class="control-label">File Attached: </label>
					<a href="/<?= $model->bkgAddInfo->bkg_file_path ?>" target="_blank">File</a>
				</div>
			</div>
		</div>
	</div>
<? } ?>
<?
if ($model->bkg_status > 4)
{
	?>
	<div class="row">
		<div class="col-xs-12 mt20">
			<?
			if ($ratingModel->rtg_customer_overall)
			{
				?> 
				<label class="mt10 control-label">Customer Rating</label>
				<div class="col-xs-12 rounded pb10">
					<div class="row">
						<?
						if ($ratingModel->rtg_customer_recommend)
						{
							?> <div class='col-xs-12 mt10'>
								<?= $ratingModel->getAttributeLabel('rtg_customer_recommend') ?><br>
								<?
								$this->widget('CStarRating', array(
									'model'		 => $ratingModel,
									'attribute'	 => 'rtg_customer_recommend',
									'minRating'	 => 1,
									'maxRating'	 => 10,
									'starCount'	 => 10,
									'value'		 => $ratingModel->rtg_customer_recommend,
									'readOnly'	 => true,
								));
								?>
							</div><?
						}
						if ($ratingModel->rtg_customer_overall)
						{
							?> <div class='col-xs-6 mt10'>

								<?= $ratingModel->getAttributeLabel('rtg_customer_overall') ?><br>
								<?
								$this->widget('CStarRating', array(
									'model'		 => $ratingModel,
									'attribute'	 => 'rtg_customer_overall',
									'minRating'	 => 1,
									'maxRating'	 => 5,
									'starCount'	 => 5,
									'value'		 => $ratingModel->rtg_customer_overall,
									'readOnly'	 => true,
								));
								?></div><?
						}
						if ($ratingModel->rtg_customer_driver)
						{
							?> <div class='col-xs-6 mt10'>
								<?= $ratingModel->getAttributeLabel('rtg_customer_driver') ?><br>
								<?
								$this->widget('CStarRating', array(
									'model'		 => $ratingModel,
									'attribute'	 => 'rtg_customer_driver',
									'minRating'	 => 1,
									'maxRating'	 => 5,
									'starCount'	 => 5,
									'value'		 => $ratingModel->rtg_customer_driver,
									'readOnly'	 => true,
								));
								?></div><?
						}
						if ($ratingModel->rtg_customer_csr)
						{
							?> <div class='col-xs-6 mt10'>
								<?= $ratingModel->getAttributeLabel('rtg_customer_csr') ?><br>
								<?
								$this->widget('CStarRating', array(
									'model'		 => $ratingModel,
									'attribute'	 => 'rtg_customer_csr',
									'minRating'	 => 1,
									'maxRating'	 => 5,
									'starCount'	 => 5,
									'value'		 => $ratingModel->rtg_customer_csr,
									'readOnly'	 => true,
								));
								?></div><?
						}
						if ($ratingModel->rtg_customer_car)
						{
							?> <div class='col-xs-6 mt10'>
								<?= $ratingModel->getAttributeLabel('rtg_customer_car') ?><br>
								<?
								$this->widget('CStarRating', array(
									'model'		 => $ratingModel,
									'attribute'	 => 'rtg_customer_car',
									'minRating'	 => 1,
									'maxRating'	 => 5,
									'starCount'	 => 5,
									'value'		 => $ratingModel->rtg_customer_car,
									'readOnly'	 => true,
								));
								?></div><?
						}
						?></div>
						<?
						if ($ratingModel->rtg_customer_review)
						{
							?> <div class='mt20'>
							<?= $ratingModel->getAttributeLabel('rtg_customer_review') ?> </div>
						<div class="col-xs-12 p15 rounded mt5 mb10">
							<?= $ratingModel->rtg_customer_review;
							?>
						</div>
						<?
					}
				}
				?>
			</div>
		</div>
	</div>
	<div class="row">
		<?
		if ($ratingModel->rtg_csr_customer)
		{
			?>


			<div class="col-xs-12">
				<label class="mt10 control-label">CSR Rating</label>
				<div class="col-xs-12 rounded pb10 pt10">
					<div class="row">
						<?
						if ($ratingModel->rtg_csr_customer)
						{
							?> <div class='col-xs-6'>

								<?= $ratingModel->getAttributeLabel('rtg_csr_customer') ?><br>
								<?
								$this->widget('CStarRating', array(
									'model'		 => $ratingModel,
									'attribute'	 => 'rtg_csr_customer',
									'minRating'	 => 1,
									'maxRating'	 => 5,
									'starCount'	 => 5,
									'value'		 => $ratingModel->rtg_csr_customer,
									'readOnly'	 => true,
								));
								?></div><?
						}
						if ($ratingModel->rtg_csr_vendor)
						{
							?> <div class='col-xs-6'>
								<?= $ratingModel->getAttributeLabel('rtg_csr_vendor') ?><br>
								<?
								$this->widget('CStarRating', array(
									'model'		 => $ratingModel,
									'attribute'	 => 'rtg_csr_vendor',
									'minRating'	 => 1,
									'maxRating'	 => 5,
									'starCount'	 => 5,
									'value'		 => $ratingModel->rtg_csr_vendor,
									'readOnly'	 => true,
								));
								?></div><?
						}
						?></div><?
						if ($ratingModel->rtg_csr_review)
						{
							?> <div class='mt20'>
							<?= $ratingModel->getAttributeLabel('rtg_csr_review') ?> </div>
						<div class="col-xs-12 p15 rounded mt10 mb10">
							<?= $ratingModel->rtg_csr_review;
							?>
						</div>
						<?
					}
					?>
				</div>
			</div>
			<?
		}
		?>
	</div>
<? } ?>
<div class="row booking-log">
    <div class="col-xs-12 text-center">
        <label class = "control-label h3">Booking Log</label>
		<?
		Yii::app()->runController('admin/booking/showlog/booking_id/' . $model->bkg_id);
		?>
    </div>
</div>
<script>

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
    function showLog(booking_id) {
        $href = "<?= Yii::app()->createUrl('admin/booking/showlog') ?>";
        var $booking_id = booking_id;
        jQuery.ajax({type: 'GET',
            url: $href,
            data: {"booking_id": $booking_id},
            success: function (data)
            {
                var box = bootbox.dialog({
                    message: data,
                    title: 'Booking Log',
                    onEscape: function () {
                    },
                });
                box.on('hidden.bs.modal', function (e) {
                    $('body').addClass('modal-open');
                });
            }
        });
    }

    function addCSRRating(booking_id) {
        $href = "<?= Yii::app()->createUrl('admin/rating/addcsrreview') ?>";
        var $booking_id = booking_id;
        jQuery.ajax({type: 'GET',
            url: $href,
            data: {"bkg_id": $booking_id},
            success: function (data)
            {
                var box = bootbox.dialog({
                    message: data,
                    title: 'Add CSR Review',
                    onEscape: function () {
                    }
                });
                box.on('hidden.bs.modal', function (e) {
                    $('body').addClass('modal-open');
                });
            }
        });
    }

    function addCustRating(booking_id) {
        $href = "<?= Yii::app()->createUrl('admin/rating/addcustreview') ?>";
        jQuery.ajax({type: 'GET',
            url: $href,
            data: {"bkg_id": booking_id},
            success: function (data)
            {
                var box = bootbox.dialog({
                    message: data,
                    title: 'Add Customer Review',
                    onEscape: function () {
                    }
                });
                box.on('hidden.bs.modal', function (e) {
                    $('body').addClass('modal-open');
                });
            }
        });
    }
    function save() {
        $('#edit').hide();
        $('#view').show();
    }

    //    function refreshAccountDetails() {
    //        jQuery.ajax({type: 'GET', url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('admin/booking/view')) ?>',
    //            success: function (data)
    //            {
    //                $('#acctdata').html(data);
    //            }
    //        });
    //    }

    var refreshAccountDetails = function () {
        jQuery.ajax({type: 'GET', url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('admin/booking/view', ['view' => 'accountsdetail', 'id' => $model->bkg_id])) ?>',
            success: function (data)
            {
                $('#acctdata').html(data);
                $('.bootbox').removeAttr('tabindex');
            }
        });
    };


    function editAccount() {
        //   $('#view').hide();
        //    $('#edit').show();

        booking_id = '<?= $model->bkg_id ?>';
        $href = "<?= Yii::app()->createUrl('admin/booking/editaccounts') ?>";
        jQuery.ajax({type: 'GET',
            url: $href,
            data: {"bkg_id": booking_id},
            success: function (data)
            {
                acctbox = bootbox.dialog({
                    message: data,
                    title: 'Edit Accounts Details',
                    size: 'large',
                    onEscape: function () {

                    }
                });
                acctbox.on('hidden.bs.modal', function (e) {
                    $('body').addClass('modal-open');
                });
            }
        });


    }


    function accountFlag1(flag = '') {
        booking_id = '<?= $model->bkg_id ?>';
        $href = "<?= Yii::app()->createUrl('admin/booking/accountflag') ?>";
        jQuery.ajax({type: 'GET',
            url: $href,
            dataType: 'json',
            data: {"bkg_id": booking_id, "bkg_account_flag": flag},
            success: function (data) {
                if (data.success) {
                    if (flag == '1') {
                        $("#setFlag").show();
                        $("#clearFlag").hide();
                    } else if (flag == '0') {
                        $("#setFlag").hide();
                        $("#clearFlag").show();
                    }
                } else {
                    alert("Sorry error occured");
                }
            },
            error: function (x) {
                alert(x);
            }
        });
    }

</script>
<?php
$version = Yii::app()->params['customJsVersion'];
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/custom.js?v=' . $version, CClientScript::POS_HEAD);
?>