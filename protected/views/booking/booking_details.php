<section id="section1">
            <div class="container">
                <div class="col-xs-12 col-sm-8 col-md-6 float-none marginauto table-responsive">
                    <h4 class="text-center orange pt10 pb10 m0">BOOKING DETAILS</h4>
                    <? if($model != null) { ?>
                    <table class="table table-bordered white-bg block-color">
                        <?php if($model['booking_id'] != null){?>
                        <tr>
                            <td width="30%">Booking ID</td>
                            <td><b><?= $model['booking_id']?></b></td>  
                        </tr>
                        <?php } ?>
                        <?php if($model['user_name'] != null){?>
                        <tr>
                            <td width="30%">Customer Name</td>
                            <td><b><?= $model['user_name'] ?></b></td>  
                        </tr>
                        <?php } ?>
                        <?php if($model['user_phone']  != null){?>
                        <tr>
                            <td width="30%">Customer Phone</td>
                            <td><b><?= $model['user_phone'] ?></b></td>  
                        </tr>
                        <?php } ?>
                        <?php if($model['user_email']  != null){?>
                        <tr>
                            <td width="30%">Customer Email</td>
                            <td><b><?= $model['user_email'] ?></b></td>  
                        </tr>
                        <?php } ?>
                        <?php if($model['user_alternate_phone']  != null){?>
                        <tr>
                            <td width="30%">Customer Alt Phone</td>
                            <td><b><?= $model['user_alternate_phone'] ?></b></td>  
                        </tr>
                        <?php } ?>
                        <?php if($model['from_city']  != null){?>
                        <tr>
                            <td width="30%">From City</td>
                            <td><b><?= $model['from_city'] ?></b></td>  
                        </tr>
                        <?php } ?>
                        <?php if($model['to_city']  != null){?>
                        <tr>
                            <td width="30%">To City</td>
                            <td><b><?= $model['to_city'] ?></b></td>  
                        </tr>
                        <?php } ?>
                        <?php if($model['pickup_time']  != null){?>
                        <tr>
                            <td width="30%">Pickup Time</td>
                            <td><b><?= $model['pickup_time'] ?></b></td>  
                        </tr>
                        <?php } ?>
                        <?php if($model['return_time']  != null){?>
                        <tr>
                            <td width="30%">Return Time</td>
                            <td><b><?= $model['return_time'] ?></b></td>  
                        </tr>
                        <?php } ?>
                        <?php if($model['created']  != null){?>
                        <tr>
                            <td width="30%">Booking Time</td>
                            <td><b><?= $model['created'] ?></b></td>  
                        </tr>
                        <?php } ?>
                        <?php if($model['pickup_address']  != null){?>
                        <tr>
                            <td width="30%">Pickup Area</td>
                            <td><b><?= $model['pickup_address'] ?></b></td>  
                        </tr>
                        <?php } ?>
                        <?php if($model['drop_address']  != null){?>
                        <tr>
                            <td width="30%">Drop Area</td>
                            <td><b><?= $model['drop_address'] ?></b></td>  
                        </tr>
                        <?php } ?>
                        <?php if($model['status']  != null){?>
                        <tr>
                            <td width="30%">Booking Status</td>
                            <td><b><?= Booking::model()->getBookingStatus($model['status']) ?></b></td>  
                        </tr>
                        <?php } ?>
                        <?php if($model['amount']  != null){?>
                        <tr>
                            <td width="30%">Amount</td>
                            <td><b><?= $model['amount'] ?></b></td>  
                        </tr>
                        <?php } ?>
                        <?php if($model['cab_type']  != null){?>
                        <tr>
                            <td width="30%">Cab Type</td>
                            <td><b><?= $model['cab_type'] ?></b></td>  
                        </tr>
                        <?php } ?>
                        <?php if($model['cab_number']  != null){?>
                        <tr>
                            <td width="30%">Cab Number</td>
                            <td><b><?= $model['cab_number'] ?></b></td>  
                        </tr>
                        <?php } ?>
                        <?php if($model['driver_name']  != null){?>
                        <tr>
                            <td width="30%">Driver Name</td>
                            <td><b><?= $model['driver_name'] ?></b></td>  
                        </tr>
                        <?php } ?>
                        <?php if($model['driver_phone']  != null){?>
                        <tr>
                            <td width="30%">Driver Phone</td>
                            <td><b><?= $model['driver_phone'] ?></b></td>  
                        </tr>
                        <?php } ?>
                        <?php if($model['driver_alt_phn']  != null){?>
                        <tr>
                            <td width="30%">Driver Alt Phone</td>
                            <td><b><?= $model['driver_alt_phn'] ?></b></td>  
                        </tr>
                        <?php } ?>
                    </table> 
                    <? }else{ ?>
                    <div class="col-xs-12">
                        <h5 style="color: red;">Sorry!! The combination you have entered doesn't match our records.</h5>
                    </div>
                    <? } ?>
                </div>
            </div></section>
