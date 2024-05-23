<style>
    .panel-body{
        padding-top: 0 ;
        padding-bottom: 0;
    }
    .table>tbody>tr>th
    {
        vertical-align: middle
    }

    .table>tbody>tr>td, .table>tbody>tr>th{
        padding: 7px;
        line-height: 1.5em;
    }
</style>

<div class="row m0">
    <div class="col-xs-12">
        <div class="text-right">
        </div>    
        <div class="panel panel-default">
            <div class="panel-body">

                <div class="row"> 
					<?php
					$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
						'id'					 => 'finalcialReportForm',
						'enableClientValidation' => true,
						'clientOptions'			 => array(
							'validateOnSubmit'	 => true,
							'errorCssClass'		 => 'has-error'
						),
						// Please note: When you enable ajax validation, make sure the corresponding
						// controller action is handling ajax validation correctly.
						// See class documentation of CActiveForm for details on this,
						// you need to use the performAjaxValidation()-method described there.
						'enableAjaxValidation'	 => false,
						'errorMessageCssClass'	 => 'help-block',
						'htmlOptions'			 => array(
							'class' => '',
						),
					));
					/* @var $form TbActiveForm */
					?>
					<div class="col-xs-6 col-sm-4 col-lg-3">
						<?php
						//$daterang = date('F d, Y') . " - " . date('F d, Y');
						$daterang	 = "Select Date Range";
						$createdate1 = ($model->bkg_create_date1 == '') ? '' : $model->bkg_create_date1;
						$createdate2 = ($model->bkg_create_date2 == '') ? '' : $model->bkg_create_date2;
						if ($createdate1 != '' && $createdate2 != '')
						{
							$daterang = date('F d, Y', strtotime($createdate1)) . " - " . date('F d, Y', strtotime($createdate2));
						}
						?>
						<label  class="control-label">From & To Date Selection</label>
						<div id="bkgCreateDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
							<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
							<span><?= $daterang ?></span> <b class="caret"></b>
						</div>
						<?php
						echo $form->hiddenField($model, 'bkg_create_date1');
						echo $form->hiddenField($model, 'bkg_create_date2');
						?>
					</div>
                    <div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-2 col-md-2 text-center mt20 p5">   
						<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary full-width')); ?>
					</div>
					<?php $this->endWidget(); ?>
                </div>

				<?php
				$bookingMMTArr = BookingSub::getCompletedBookingCountByMMT($model->bkg_create_date1, $model->bkg_create_date2);
				if (count($data2) > 0)
				{
					$bookingLogArr	 = BookingLog::model()->getGozoCancelCounReport($model->bkg_create_date1, $model->bkg_create_date2);
					$bookingUserArr	 = BookingUser::model()->getRepeatCustomerBookingsCounReport($model->bkg_create_date1, $model->bkg_create_date2, 1);
					?>  
					<div>
						<div class="panel panel-primary  compact" id="yw0">
							<div class="panel-heading">
								<div class="row m0">
									<div class="col-xs-5 col-sm-2 pt5">By pickup date</div>
									<div class="col-xs-4 col-sm-5 pt5">
										<div class="summary">Total <?php echo count($data2) ?> results.</div></div>
									<div class="col-xs-3 col-sm-5 pr0"></div>
								</div></div>
							<div class="panel-body table-responsive" style="overflow: auto"><table class="table table-striped table-bordered dataTable mb0 table">
									<thead>
										<tr>
											<th id="yw0_c0">Date</th>
											<th id="yw0_c1">Count Created</th>
											<th id="yw0_c2">Created B2C</th>
											<th id="yw0_c3">Created B2B</th>
											<th id="yw0_c4">Cancelled</th>
											<th id="yw0_c5">Cancelled B2C</th>
											<th id="yw0_c6">Cancelled B2B</th>
											<th id="yw0_c7">Completed</th>
											<th id="yw0_c8">Completed B2C</th>
											<th id="yw0_c9">Completed B2B</th>
											<th id="yw0_c9">Completed B2B API</th>
											<th id="yw0_c10">Created Amount</th>
											<th id="yw0_c11">Created Amount B2C</th>
											<th id="yw0_c12">Created Amount B2B</th>
											<th id="yw0_c13">Completed Amount</th>
											<th id="yw0_c14">Completed Amount B2C</th>
											<th id="yw0_c15">Completed Amount B2B</th>
											<th id="yw0_c16">Gozo Amount</th>
											<th id="yw0_c17">Gozo Amount B2C</th>
											<th id="yw0_c18">Gozo Amount B2B</th>
	                                        <th>Total Base Fare Amount</th>
											<th>Total Toll, State, Parking & Airport Fee Amount</th>
											<th>Total Driver Allowance Amount</th>
											<th>Total GST Amount</th>
											<th id="yw0_c19">Partner Commission</th>
											<th id="yw0_c20">Max Ticket Size</th>
											<th id="yw0_c21">Max Ticket Size B2C</th>
											<th id="yw0_c22">Max Ticket Size B2B</th>
											<th id="yw0_c23">Min Ticket Size</th>
											<th id="yw0_c24">MIn Ticket Size B2C</th>
											<th id="yw0_c25">Min Ticket Size B2B</th>
											<th id="yw0_c26">Matched Trips</th>
											<th id="yw0_c27">Single Trips</th>
											<th id="yw0_c28">Matched Trips Gozo Amount</th>
											<th id="yw0_c29">Single Trips Gozo Amount</th>
											<th id="yw0_c30">Vendor Amount Completed</th>
											<th id="yw0_c31">Vendor Amount B2B</th>
											<th id="yw0_c32">Vendor Amount B2C</th>
											<th id="yw0_c33">Vendor Amount Matched</th>
											<th id="yw0_c34">Vendor Amount Unmatched</th>
											<th id="yw0_c35">Cancelled GozoAmount</th>
											<th id="yw0_c36">Cancelled B2C Gozo Amount</th>
											<th id="yw0_c37">Cancelled B2B Gozo Amount</th>
											<th id="yw0_c38">Cancel Charge</th>
											<th id="yw0_c39">Cancel Charge B2C</th>
											<th id="yw0_c40">Cancel Charge B2B</th>

											<th id="yw0_c41">Active Drivers</th>
											<th id="yw0_c42">Active Users</th>
											<th id="yw0_c43">Active Cars</th>
											<th id="yw0_c44"># Gozo Cancellation</th>
											<th id="yw0_c46"># <3Star Bookings </th>
											<th id="yw0_c46"># Booking With Rating</th>
											<th id="yw0_c47"># Loss Bookings</th>
											<th id="yw0_c47"># Total Loss Amount</th>
											<th id="yw0_c48"># OnTime Pickup</th>
											<th id="yw0_c49"># Bookings Repeat Customer</th>
											<th id="yw0_c49">Pickup MMT</th>
											<th id="yw0_c50">Completed MMT</th>
											<th id="yw0_c51">Commission MMT</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$i				 = 0;
										foreach ($data2 as $data)
										{
											$indexArrivedOnTime			 = array_search($data['date'], array_column($bookingLogArr, 'date'));
											$indexCountRepeatCustomer	 = array_search($data['date'], array_column($bookingUserArr, 'date'));
											$indexMMTCompletedCount		 = array_search($data['date'], array_column($bookingMMTArr, 'date'));
											?>
											<tr class="<?php echo $i == 0 ? 'even' : 'odd' ?>">
												<td><?php echo $data['date']; ?></td>
												<td class="text-right"><?php echo $data['cntCreated']; ?></td>
												<td class="text-right"><?php echo $data['createdB2C']; ?></td>
												<td class="text-right"><?php echo $data['createdB2B']; ?></td>
												<td class="text-right"><?php echo $data['cancelled']; ?></td>
												<td class="text-right"><?php echo $data['cancelledB2C']; ?></td>
												<td class="text-right"><?php echo $data['cancelledB2B']; ?></td>
												<td class="text-right"><?php echo $data['completed']; ?></td>
												<td class="text-right"><?php echo $data['completedB2C']; ?></td>
												<td class="text-right"><?php echo $data['completedB2B']; ?></td>
												<td class="text-right"><?php echo $data['completedB2BAPI']; ?></td>
												<td class="text-right"><?php echo $data['createdAmount']; ?></td>
												<td class="text-right"><?php echo $data['createdAmountB2C']; ?></td>
												<td class="text-right"><?php echo $data['createdAmountB2B']; ?></td>
												<td class="text-right"><?php echo $data['completedAmount']; ?></td>
												<td class="text-right"><?php echo $data['completedAmountB2C']; ?></td>
												<td class="text-right"><?php echo $data['completedAmountB2B']; ?></td>
												<td class="text-right"><?php echo $data['gozoAmount']; ?></td>
												<td class="text-right"><?php echo $data['gozoAmountB2C']; ?></td>
												<td class="text-right"><?php echo $data['gozoAmountB2B']; ?></td>
												<td class="text-right"><?php echo $data['totalBaseFare']; ?></td>
												<td class="text-right"><?php echo $data['totalTollAndStateTax']; ?></td>
												<td class="text-right"><?php echo $data['totalDriverAllowance']; ?></td>
												<td class="text-right"><?php echo $data['totalGst']; ?></td>	
												<td class="text-right"><?php echo $data['partnerCommission']; ?></td>
												<td class="text-right"><?php echo $data['maxTicketSize']; ?></td>
												<td class="text-right"><?php echo $data['maxTicketSizeB2C']; ?></td>
												<td class="text-right"><?php echo $data['maxTicketSizeB2B']; ?></td>
												<td class="text-right"><?php echo $data['minTicketSize']; ?></td>
												<td class="text-right"><?php echo $data['minTicketSizeB2C']; ?></td>
												<td class="text-right"><?php echo $data['minTicketSizeB2B']; ?></td>
												<td><?php echo $data['matchedTrips']; ?></td>
												<td><?php echo $data['singleTrips']; ?></td>
												<td><?php echo $data['matchedTripsGozoAmount']; ?></td>											
												<td class="text-right"><?php echo $data['singleTripsGozoAmount']; ?></td>
												<td class="text-right"><?php echo $data['vendorAmountCompleted']; ?></td>
												<td class="text-right"><?php echo $data['vendorAmountB2B']; ?></td>
												<td class="text-right"><?php echo $data['vendorAmountB2C']; ?></td>
												<td class="text-right"><?php echo $data['vendorAmountMatched']; ?></td>
												<td class="text-right"><?php echo $data['vendorAmountUnmatched']; ?></td>
												<td class="text-right"><?php echo $data['cancelledGozoAmount']; ?></td>
												<td class="text-right"><?php echo $data['cancelledB2CGozoAmount']; ?></td>
												<td class="text-right"><?php echo $data['cancelledB2BGozoAmount']; ?></td>
												<td class="text-right"><?php echo $data['cancelCharge']; ?></td>
												<td class="text-right"><?php echo $data['cancelChargeB2C']; ?></td>
												<td class="text-right"><?php echo $data['cancelChargeB2B']; ?></td>


												<td class="text-right"><?php echo $data['driverCount']; ?></td>
												<td class="text-right"><?php echo $data['UserCount']; ?></td>
												<td class="text-right"><?php echo $data['VehicleCount']; ?></td>
												<td class="text-right"><?php
													if (false !== $indexArrivedOnTime)
													{
														echo $bookingLogArr[$indexArrivedOnTime]['GozoCancelCount'];
													}
													else
													{
														echo "0";
													}
													?></td>
												<td class="text-right"><?php echo $data['Count3starbookings']; ?></td>
												<td class="text-right"><?php echo $data['bookingsRatingsReceived']; ?></td>	

												<td class="text-right"><?php echo $data['CountLossbookings']; ?></td>
												<td class="text-right"><?php echo $data['totalLossAmount']; ?></td>	

												<td class="text-right"><?php echo $data['CountArrivedOnTime']; ?></td>
												<td class="text-right">
													<?php
													if (false !== $indexCountRepeatCustomer)
													{
														echo $bookingUserArr[$indexCountRepeatCustomer]['CountRepeatCustomer'];
													}
													else
													{
														echo "0";
													}
													?>
												</td>
												<td class="text-right"><?php echo $data['createdMMT']; ?></td>
												<td class="text-right">
													<?php
													if (false !== $indexMMTCompletedCount)
													{
														echo $bookingMMTArr[$indexMMTCompletedCount]['completedMMT'];
													}
													else
													{
														echo "0";
													}
													?>
												</td>
												<td class="text-right"><?php echo $data['partnerCommissionMMT']; ?></td>
											</tr>
											<?php
											$i++;
										}
										?>
									</tbody>
								</table></div>
							<div class="panel-footer"><div class="row m0"><div class="col-xs-12 col-sm-6 p5"><div class="summary">Total <?php echo count($data2) ?> results.</div></div><div class="col-xs-12 col-sm-6 pr0"></div></div></div><div class="keys" style="display:none" title="/admpnl/report/Financial"><span></span><span></span><span></span><span></span><span></span><span></span><span></span></div>
							
							<div>
								#Created Amount: Booking Total Amount (Including Cancelled Bookings)<br>
								#Completed Amount: Booking Total Amount (Completed & Settled Bookings)<br>
								#Total Base Fare Amount: Base Amount + Additional Charge + Extra Charge + Extra KM Charge + AddOn Charges + Convenience Charge - (Discount Amount + Extra Discount Amount) (Excluding Cancelled Bookings)<br>
								#Total Toll, State, Parking & Airport Fee Amount: Toll Tax + Extra Toll Tax + State Tax + Extra State Tax + Parking Charge + Airport Entry Fee (Excluding Cancelled Bookings)<br>
								#Total GST Amount: Total GST (Excluding Cancelled Bookings)<br>
								#Cancel Charge: Advance Amount - Refund Amount (Cancelled Bookings)<br>
							</div>
						</div>
					</div>
				
				<?php } ?>
				<?php
				if (count($data1) > 0)
				{
					$bookingArr				 = BookingSub::model()->getBookingQuotationCounReport($model->bkg_create_date1, $model->bkg_create_date2);
					$bookingUserArrCreate	 = BookingUser::model()->getRepeatCustomerBookingsCounReport($model->bkg_create_date1, $model->bkg_create_date2, 0);
					?>        
					<div>
						<div class="panel panel-primary  compact" id="yw0">
							<div class="panel-heading"><div class="row m0">
									<div class="col-xs-5 col-sm-2 pt5">By create date</div>
									<div class="col-xs-4 col-sm-5 pt5"><div class="summary">Total <?php echo count($data1); ?> results.</div></div>
									<div class="col-xs-3 col-sm-5 pr0"></div>
								</div></div>
							<div class="panel-body table-responsive" style="overflow: auto"><table class="table table-striped table-bordered dataTable mb0 table">
									<thead>
										<tr>
											<th id="yw0_c0">Date</th>
											<th id="yw0_c1">Count Created</th>
											<th id="yw0_c2">Created B2C</th>
											<th id="yw0_c3">Created B2B</th>
											<th id="yw0_c4">Cancelled</th>
											<th id="yw0_c5">cancelled B2C</th>
											<th id="yw0_c6">cancelled B2B</th>
											<th id="yw0_c7">Completed</th>
											<th id="yw0_c8">Completed B2C</th>
											<th id="yw0_c9">Completed B2B</th>
											<th id="yw0_c10">Created Amount</th>
											<th id="yw0_c11">Created Amount B2C</th>
											<th id="yw0_c12">Created Amount B2B</th>
											<th id="yw0_c13">Completed Amount</th>
											<th id="yw0_c14">Completed Amount B2C</th>
											<th id="yw0_c15">Completed Amount B2B</th>
											<th id="yw0_c16">Gozo Amount</th>
											<th id="yw0_c17">Gozo Amount B2C</th>
											<th id="yw0_c18">Gozo Amount B2B</th>
											<th>Total Base Fare Amount</th>
											<th>Total Toll Tax, State Tax & Parking Amount</th>
											<th>Total Driver Allowance Amount</th>
											<th>Total GST Amount</th>
											<th id="yw0_c19">Partner Commission</th
											><th id="yw0_c20">Max Ticket Size</th>
											<th id="yw0_c21">Max Ticket Size B2C</th>
											<th id="yw0_c22">Max Ticket Size B2B</th>
											<th id="yw0_c23">Min Ticket Size</th>
											<th id="yw0_c24">Min Ticket Size B2C</th>
											<th id="yw0_c25">Min Ticket Size B2B</th>
											<th id="yw0_c26">Matched Trips</th>
											<th id="yw0_c27">Single Trips</th>
											<th id="yw0_c28">Matched TripsGozoAmount</th>
											<th id="yw0_c29">Single Trips Gozo Amount</th>
											<th id="yw0_c30">Vendor Amount Completed</th>
											<th id="yw0_c31">Vendor Amount B2B</th>
											<th id="yw0_c32">Vendor Amount B2C</th>
											<th id="yw0_c33">Vendor Amount Matched</th>
											<th id="yw0_c34">Vendor Amount Unmatched</th>
											<th id="yw0_c35">Cancelled Gozo Amount</th>
											<th id="yw0_c36">Cancelled B2C Gozo Amount</th>
											<th id="yw0_c37">Cancelled B2B Gozo Amount</th>
											<th id="yw0_c38">Cancel Charge</th>
											<th id="yw0_c39">Cancel Charge B2C</th>
											<th id="yw0_c40">Cancel Charge B2B</th>

											<th id="yw0_c40">Enquiries</th>
											<th id="yw0_c50"># Bookings In North Region</th>
											<th id="yw0_c51"># Bookings In West Region </th>
											<th id="yw0_c52"># Bookings In Central Region</th>
											<th id="yw0_c53"># Bookings In South Region</th>
											<th id="yw0_c54"># Bookings In East Region</th>
											<th id="yw0_c55"># Bookings In NorthEast Region</th>
											<th id="yw0_c56"># Bookings Repeat Customer</th>
											<th id="yw0_c57">Created MMT</th>
											<th id="yw0_c58">Completed MMT</th>
											<th id="yw0_c59">Commission MMT</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$i						 = 0;
										foreach ($data1 as $data)
										{
											$indexEnquiriesCount			 = array_search($data['date'], array_column($bookingArr, 'date'));
											$indexCountRepeatCustomerCreate	 = array_search($data['date'], array_column($bookingUserArrCreate, 'date'));
											$indexMMTCompletedCount			 = array_search($data['date'], array_column($bookingMMTArr, 'date'));
											?>
											<tr class="<?php echo $i == 0 ? 'even' : 'odd' ?>">
												<td><?php echo $data['date']; ?></td>
												<td class="text-right"><?php echo $data['cntCreated']; ?></td>
												<td class="text-right"><?php echo $data['createdB2C']; ?></td>
												<td class="text-right"><?php echo $data['createdB2B']; ?></td>
												<td class="text-right"><?php echo $data['cancelled']; ?></td>
												<td class="text-right"><?php echo $data['cancelledB2C']; ?></td>
												<td class="text-right"><?php echo $data['cancelledB2B']; ?></td>
												<td class="text-right"><?php echo $data['completed']; ?></td>
												<td class="text-right"><?php echo $data['completedB2C']; ?></td>
												<td class="text-right"><?php echo $data['completedB2B']; ?></td>
												<td class="text-right"><?php echo $data['createdAmount']; ?></td>
												<td class="text-right"><?php echo $data['createdAmountB2C']; ?></td>
												<td class="text-right"><?php echo $data['createdAmountB2B']; ?></td>
												<td class="text-right"><?php echo $data['completedAmount']; ?></td>
												<td class="text-right"><?php echo $data['completedAmountB2C']; ?></td>
												<td class="text-right"><?php echo $data['completedAmountB2B']; ?></td>
												<td class="text-right"><?php echo $data['gozoAmount']; ?></td>
												<td class="text-right"><?php echo $data['gozoAmountB2C']; ?></td>
												<td class="text-right"><?php echo $data['gozoAmountB2B']; ?></td>												
												<td class="text-right"><?php echo $data['totalBaseFare']; ?></td>
												<td class="text-right"><?php echo $data['totalTollAndStateTax']; ?></td>
												<td class="text-right"><?php echo $data['totalDriverAllowance']; ?></td>
												<td class="text-right"><?php echo $data['totalGst']; ?></td>
												<td class="text-right"><?php echo $data['partnerCommission']; ?></td>
												<td class="text-right"><?php echo $data['maxTicketSize']; ?></td>
												<td class="text-right"><?php echo $data['maxTicketSizeB2C']; ?></td>
												<td class="text-right"><?php echo $data['maxTicketSizeB2B']; ?></td>
												<td class="text-right"><?php echo $data['minTicketSize']; ?></td>
												<td class="text-right"><?php echo $data['minTicketSizeB2C']; ?></td>
												<td class="text-right"><?php echo $data['minTicketSizeB2B']; ?></td>
												<td><?php echo $data['matchedTrips']; ?></td>
												<td><?php echo $data['singleTrips']; ?></td>
												<td><?php echo $data['matchedTripsGozoAmount']; ?></td>												
												<td class="text-right"><?php echo $data['singleTripsGozoAmount']; ?></td>
												<td class="text-right"><?php echo $data['vendorAmountCompleted']; ?></td>
												<td class="text-right"><?php echo $data['vendorAmountB2B']; ?></td>
												<td class="text-right"><?php echo $data['vendorAmountB2C']; ?></td>
												<td class="text-right"><?php echo $data['vendorAmountMatched']; ?></td>
												<td class="text-right"><?php echo $data['vendorAmountUnmatched']; ?></td>
												<td class="text-right"><?php echo $data['cancelledGozoAmount']; ?></td>
												<td class="text-right"><?php echo $data['cancelledB2CGozoAmount']; ?></td>
												<td class="text-right"><?php echo $data['cancelledB2BGozoAmount']; ?></td>
												<td class="text-right"><?php echo $data['cancelCharge']; ?></td>
												<td class="text-right"><?php echo $data['cancelChargeB2C']; ?></td>
												<td class="text-right"><?php echo $data['cancelChargeB2B']; ?></td>
												<td class="text-right">
													<?php
													if (false !== $indexEnquiriesCount)
													{
														echo $bookingArr[$indexEnquiriesCount]['enquiriesCount'];
													}
													else
													{
														echo "0";
													}
													?>
												</td>
												<td class="text-right"><?php echo $data['North']; ?></td>
												<td class="text-right"><?php echo $data['West']; ?></td>
												<td class="text-right"><?php echo $data['Central']; ?></td>
												<td class="text-right"><?php echo $data['South']; ?></td>
												<td class="text-right"><?php echo $data['East']; ?></td>
												<td class="text-right"><?php echo $data['NorthEast']; ?></td>
												<td class="text-right">
													<?php
													if (false !== $indexCountRepeatCustomerCreate)
													{
														echo $bookingUserArrCreate[$indexCountRepeatCustomerCreate]['CountRepeatCustomer'];
													}
													else
													{
														echo "0";
													}
													?>
												</td>
												<td class="text-right"><?php echo $data['createdMMT']; ?></td>
												<td class="text-right">
													<?php
													if (false !== $indexMMTCompletedCount)
													{
														echo $bookingMMTArr[$indexMMTCompletedCount]['completedMMT'];
													}
													else
													{
														echo "0";
													}
													?>
												</td>
												<td class="text-right"><?php echo $data['partnerCommissionMMT']; ?></td>
											</tr>
											<?php
											$i++;
										}
										?>


									</tbody>
								</table></div>
							<div class="panel-footer"><div class="row m0"><div class="col-xs-12 col-sm-6 p5"><div class="summary">Total <?php echo count($data1); ?> results.</div></div><div class="col-xs-12 col-sm-6 pr0"></div></div></div><div class="keys" style="display:none" title="/admpnl/report/Financial"><span></span><span></span><span></span><span></span><span></span><span></span><span></span></div>
						</div>				
					</div>
				<?php } ?>

				

				<?php
				if (count($data3) > 0)
				{
					?>  
					<div>
						<div class="panel panel-primary  compact" id="yw1">
							<div class="panel-heading"><div class="row m0">
									<div class="col-xs-12 col-sm-4 pt5">Compensation / Penalty</div>
									<div class="col-xs-12 col-sm-4 pt5">
										<div class="summary">Total  <?php echo count($data3); ?> results.</div></div>
									<div class="col-xs-12 col-sm-4 pr0"></div>
								</div></div>
							<div class="panel-body table-responsive"><table class="table table-striped table-bordered dataTable mb0 table">
									<thead>
										<tr>
											<th id="yw1_c0">Date</th>
											<th id="yw1_c1">Partner Compensation</th>
											<th id="yw1_c2">Operator Compensation</th>
											<th id="yw1_c3">Operator Penalty</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$i = 0;
										foreach ($data3 as $data)
										{
											?>
											<tr class="<?php echo $i == 0 ? 'even' : 'odd' ?>">
												<td><?php echo $data['date']; ?></td>
												<td class="text-right"><?php echo $data['partnerCompensation']; ?></td>
												<td class="text-right"><?php echo $data['operatorCompensation']; ?></td>
												<td class="text-right"><?php echo $data['operatorPenalty']; ?></td>
											</tr>
											<?php
											$i++;
										}
										?>
									</tbody>
								</table></div>
							<div class="panel-footer"><div class="row m0"><div class="col-xs-12 col-sm-6 p5"><div class="summary">Total <?php echo count($data3) ?> results.</div></div><div class="col-xs-12 col-sm-6 pr0"></div></div></div><div class="keys" style="display:none" title="/admpnl/report/Financial"><span></span><span></span><span></span><span></span><span></span><span></span><span></span></div>
						</div>				</div>
				<?php } ?>



				<?php
				if (count($data4) > 0)
				{
					?>  
					<div>
						<div class="panel panel-primary  compact" id="yw1">
							<div class="panel-heading"><div class="row m0">
									<div class="col-xs-12 col-sm-4 pt5">Sticky Score Cars</div>
									<div class="col-xs-12 col-sm-4 pt5">
										<div class="summary">Total  <?php echo count($data4); ?> results.</div></div>
									<div class="col-xs-12 col-sm-4 pr0"></div>
								</div></div>
							<div class="panel-body table-responsive"><table class="table table-striped table-bordered dataTable mb0 table">
									<thead>
										<tr>
											<th id="yw1_c0">Date</th>
											<th id="yw1_c1">#  Non-Sticky Cars</th>
											<th id="yw1_c2">#  Super-Sticky Cars </th>
											<th id="yw1_c3"># Sticky Cars </th>
											<th id="yw1_c4">%  Booking Served By Non-Sticky Cars</th>
											<th id="yw1_c5">%  Booking Served By Super-Sticky Cars </th>
											<th id="yw1_c6">%  Booking Served By Sticky Cars </th>
										</tr>
									</thead>
									<tbody>
										<?php
										$i = 0;
										foreach ($data4 as $data)
										{
											$indexBooking = array_search($data['date'], array_column($data5, 'date'));
											?>
											<tr class="<?php echo $i == 0 ? 'even' : 'odd' ?>">
												<td><?php echo $data['date']; ?></td>
												<td class="text-right"><?php echo $data['Countnonstickycars']; ?></td>
												<td class="text-right"><?php echo $data['Countsuperstickycars']; ?></td>
												<td class="text-right"><?php echo $data['Countstickycars']; ?></td>												
												<td class="text-right">
													<?php
													if (false !== $indexBooking)
													{
														echo $data5[$indexBooking]['CountnonStickyBookingcars'];
													}
													else
													{
														echo "0";
													}
													?>
												</td>
												<td class="text-right">
													<?php
													if (false !== $indexBooking)
													{
														echo $data5[$indexBooking]['CountSuperstickyBookingcars'];
													}
													else
													{
														echo "0";
													}
													?>
												</td>
												<td class="text-right">
													<?php
													if (false !== $indexBooking)
													{
														echo $data5[$indexBooking]['CountStickyBookingcars'];
													}
													else
													{
														echo "0";
													}
													?>
												</td>
											</tr>
											<?php
											$i++;
										}
										?>
									</tbody>
								</table></div>
							<div class="panel-footer"><div class="row m0"><div class="col-xs-12 col-sm-6 p5"><div class="summary">Total <?php echo count($data4) ?> results.</div></div><div class="col-xs-12 col-sm-6 pr0"></div></div></div><div class="keys" style="display:none" title="/admpnl/report/Financial"><span></span><span></span><span></span><span></span><span></span><span></span><span></span></div>
						</div>				</div>
				<?php } ?>
            </div>  

        </div>  
    </div>
</div>
<script>
    $(document).ready(function () {


        var start = '<?= date('d/m/Y', strtotime('-1 month')); ?>';
        var end = '<?= date('d/m/Y'); ?>';

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
                        'Last 6 Month': [moment().subtract(6, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                    }
                }, function (start1, end1) {
            $('#Booking_bkg_create_date1').val(start1.format('YYYY-MM-DD'));
            $('#Booking_bkg_create_date2').val(end1.format('YYYY-MM-DD'));

            $('#bkgCreateDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
        });
        $('#bkgCreateDate').on('cancel.daterangepicker', function (ev, picker) {
            $('#bkgCreateDate span').html('Select Date Range');
            $('#Booking_bkg_create_date1').val('');
            $('#Booking_bkg_create_date2').val('');

        });


    });
    function viewDetail(obj) {
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