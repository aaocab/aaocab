<div class="row">
	                 <div class="col-xs-12 col-sm-6 p20 pt0 pb0 table-responsive">
                        <table class="table table-striped table-bordered">
							<tr><td colspan="2"><b>Driver : <?php  echo $driverAmount['drv_name']; ?> (<?php  echo $driverAmount['drv_id']; ?>)</b></td></tr>
                            <tr>
                                <td><b>Accounts Payable</b></td>
                                <td><i class="fa fa-inr"></i><?php
									if ($driverAmount['bonus_amount'] < 0)
									{
										echo trim(-1 * $driverAmount['bonus_amount']);
									}
									else
									{
										echo '0';
									}
									?></td>
                            </tr>
                            <tr>
                                <td><b>Accounts Receivable</b></td>
                                <td><i class="fa fa-inr"></i><?php
									if ($driverAmount['bonus_amount'] > 0)
									{
										echo trim($driverAmount['bonus_amount']);
									}
									else
									{
										echo '0';
									}
									?></td>
                            </tr>
                           
                        </table>
						 
                    </div> <!--	<div> 
		<a href="<?php //echo Yii::app()->createUrl("admin/driver/addtransaction", $_REQUEST + ["id" => $driverAmount["drv_id"]]); ?>">Save Manual Entry</a>
	</div>-->
                    <div class="panel panel-default">
                        <div class="panel-body">
							<a class="btn btn-primary" href="<?php echo Yii::app()->createUrl("admin/driver/addtransaction", $_REQUEST + ["id" => $driverAmount["drv_id"]]); ?>">Add Transaction</a>

                            <div class="col-xs-12 table-responsive p0">
                                <table class="table table-bordered">
                                    <tr class="blue2 white-color">
                                       
                                        <td align="center"><b>Trip ID/<br>Booking ID</b></td>
                                        
                                        <td align="center"><b>Transaction Date</b></td>
					<td align="center"><b>Created Date</b></td>
                                        
                                        <td class="text-center"><b>amount (<i class="fa fa-inr"></i>)</b><br>(+=credit to gozo,<br>-=credit to driver)</td>
                                        <td align="center"><b>Notes</b></td>
                                        <td align="center"><b>Who</b></td>
                                        <td align="center"><b>Running Balance</b></td>
                                    </tr>
									<?php
									$ctr				 = 0;
// print_r($vendorAmount); die;
// $currentBal       = $vendorAmount['vendor_amount'];
									$countTransaction	 = count($driverModels);

									if (count($driverModels) > 0)
									{
										$openBalance	 = $driverModels[0]['openBalance'];
										$runningBalance	 = $driverModels[0]['runningBalance'];
										if ($openBalance != 0)
										{
											?>
											<tr>
												
												<td><?= "NA" ?></td>
												
												<td><?php echo date('d/m/Y', strtotime($driverModels[0]['drv_trans_date'])); ?></td>
												<td><?php echo date('d/m/Y', strtotime($driverModels[0]['drv_createdate'])); ?></td>
											
												<td class="text-right"><?php echo round($openBalance); ?></td>
												<td><?= "Opening  Balance" ?></td>
												<td ><?= "NA" ?></td>
												<td align="right"><?= $openBalance; ?></td>
											</tr>
											<?
										}
										foreach ($driverModels as $driver)
										{
											 $bookingId  = ($driver['booking_id'] == NULL) ? 'NA' : $driver['booking_id'];
											$pickupDate	 = ($driver['bkg_pickup_date'] == NULL) ? 'NA' : date('d/m/Y', strtotime($driver['bkg_pickup_date']));
											$fromCity	 = ($driver['from_city'] == NULL) ? 'NA' : trim($driver['from_city']);
											$advanceAmt		 = ($driver['bkg_advance_amount'] == NULL ) ? 'NA' : trim($driver['bkg_advance_amount']);
											$balance[$ctr]	 = $driver['ven_trans_amount'];
											$index			 = ($countTransaction - $ctr);
											$bookingDetail	 = ($driver['ledgerNames'] == NULL) ? 'NA' : $fromCity;
											?>
											<tr>
												
												<td><?= ($driver['ledgerNames'] == NULL) ? 'NA' : $driver['ledgerNames'] ?></td>
												
												<td><?php echo date('d/m/Y', strtotime($driver['drv_trans_date'])); ?></td>
												<td><?php echo date('d/m/Y', strtotime($driver['drv_createdate'])); ?></td>
												
												<td class="text-right"><?php echo round($driver['drv_bonus_amount']); ?></td>
												<td><?php echo trim($driver['drv_remarks']); ?>
												    <?php 
													if(isset($driver['bank_charge']) && $driver['bank_charge']!='')
													{
													echo ",Bank charge deducted(".$driver['bank_charge'].")";
													}
													?>
												</td>
												<td><b><?php echo trim($driver['adm_name']); ?></b></td>
												<td align="right"><?= $driver['runningBalance'] ?></td>
											</tr>
											<?php
										}
									}
									else
									{
										?>
										<tr><td colspan="10">No Records Yet Found.</td></tr>
									<?php }
									?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>