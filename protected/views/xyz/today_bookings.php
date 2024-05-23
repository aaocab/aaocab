            <div class="container-fluid p0"><div class="panel panel-white"><div class="panel-body">
    
            <title>Today's Bookings</title>
<? if ($error == 1) { ?>
    <div class="row m0 mt20" id="passwordDiv">
        <form name="tbkg" method="POST" action="<?= Yii::app()->request->url ?>">
            <div class="col-xs-offset-4 col-xs-4">   
                <div class="form-group row text-center">
                    <input class="form-control" type="password" id="psw" name="psw" value="" placeholder="Password" required/>
                </div>
                <div class="Submit-button row text-center">
                    <button type="submit" class="btn btn-primary">SUBMIT</button>
                </div>
            </div>
        </form>
    </div>
<? } ?>
<? if ($error == 2) { ?>
    <div class="row m0 mt20" id="wrongPassword" style="">
        <div class="col-xs-offset-4 col-xs-4">
            <h3>Wrong Password</h3>
            <img src="http://static.commentcamarche.net/es.ccm.net/pictures/Ud6krzOUaQiVrbx4IWkuzUrMD8vWr4qbG1wMtmWKQ94r7Doi6fybXXnACJoLFtKR-lol.png">
        </div>
    </div>
<? } ?>
<? if ($error == 0) { ?>
    <div class="row" id="routewiseDiv" style="margin-top: 10px;">  
        <div class="col-xs-12 col-sm-5">       
            <table class="table table-bordered">
                <thead>
                    <tr style="color: black;background: whitesmoke">
                        <th class="text-center"><u>Total Booking</u></th>
                        <th class="text-center"><u>B2B</u></th>
                        <th class="text-center"><u>B2C</u></th>						 
                        <th class="text-center"><u>B2C Unverified</u></th>
						<th class="text-center"><u>B2C Quoted</u></th>
                    </tr>
                </thead>
                <tbody id="count_booking_row">                         
                        <tr>
                            <td class="text-center"><?= $bookings['total_book'] ?></td>
                            <td class="text-center"><?= $bookings['total_b2b'] ?></td>
                            <td class="text-center"><?= $bookings['total_b2c'] ?></td>							
                            <td class="text-center"><?= $bookings['total_b2c_unv'] ?></td>
							<td class="text-center"><?= $bookings['total_b2c_quot'] ?></td>
                        </tr>
                </tbody>
            </table>
            
                <table class="table table-bordered mt10">
                <thead>
                    <tr style="color: blue;background: whitesmoke">
                        <th colspan="4" class="text-center"><u>Today's Region-wise Count</u></th>
                    </tr>
                    <tr style="color: black;background: whitesmoke">
                        <th class="text-center"><u>Region</u></th>
                        <th class="text-center"><u>Count</u></th>
                    </tr>
                </thead>
                <tbody id="count_booking_row">                         
                    <?
                    $cnt = 0;
                    foreach ($regionWiseData as $data) {
                        $cnt += $data['countBook'];
                        ?>
                        <tr>
                            <td class="text-center"><?= $data['region'] ?></td>
                            <td class="text-center"><?= $data['countBook'] ?></td>
                        </tr>

                        <?
                    }
                    ?>
                    <tr><td colspan="1" class="text-center" style="border-top : 1px solid grey;font-style: italic;">Total Bookings Count</td><td colspan="1" style="border-top : 1px solid grey;"  class="text-center"><?= $cnt ?></td></tr>
                </tbody>
            </table>
            
            <table class="table table-bordered mt10">
                <thead>
                    <tr style="color: blue;background: whitesmoke">
                        <th colspan="4" class="text-center"><u>Today's Route-wise Count</u></th>
                    </tr>
                    <tr style="color: black;background: whitesmoke">
                        <th class="text-center"><u>From</u></th>
                        <th class="text-center"><u>To</u></th>
                        <th class="text-center"><u>Count</u></th>
                        <th class="text-center"><u>Amount</u></th>
                    </tr>
                </thead>
                <tbody id="count_booking_row">                         
                    <?
                    $cnt = 0;
                    $amount = 0;
                    foreach ($model1 as $data) {
                        $cnt += $data['count'];
                        $amount += $data['amount'];
                        ?>
                        <tr>
                            <td class="text-center"><?= $data['fromc'] ?></td>
                            <td class="text-center"><?= $data['toc'] ?></td>
                            <td class="text-center"><?= $data['count'] ?></td>
                            <td class="text-center"><?= $data['amount'] ?></td>
                        </tr>

                        <?
                    }
                    ?>
                    <tr><td colspan="2" class="text-center" style="border-top : 1px solid grey;font-style: italic;">Total Bookings Count and Amount</td><td colspan="1" style="border-top : 1px solid grey;"  class="text-center"><?= $cnt ?></td><td colspan="1" style="border-top : 1px solid grey;"  class="text-center"><?= $amount ?></td></tr>
                </tbody>
            </table>
        </div>
        <div class="col-xs-12 col-sm-7">       
            <table class="table table-bordered">
                <thead>
                    <tr style="color: blue;background: whitesmoke">
                        <th colspan="8" class="text-center"><u>Today's Bookings</u></th>
                    </tr>
                    <tr style="color: black;background: whitesmoke">
                        <th class="text-center"><u>Booking ID</u></th>
                        <th class="text-center"><u>Booking Date/Time</u></th>
                        <th class="text-center" style="min-width: 150px"><u>Routes</u></th>
                        <th class="text-center"><u>Pickup Date/Time</u></th>
                        <th class="text-center"><u>Amount</u></th>
                        <th class="text-center"><u>Cab Type</u></th>
                    </tr>
                </thead>
                <tbody id="booking_row">                         
                    <?
                    foreach ($model as $data) {
                        ?>
                        <tr>
                            <td class="text-center"><?= $data['bkg_booking_id'] ?></td>
                            <td class="text-center"><?= date("d/m/Y H:i:s", strtotime($data['bkg_create_date'])) ?></td>
                            <td class="text-center"><?= $data['routes'] ?></td>
                            <td class="text-center"><?= date("d/m/Y H:i:s", strtotime($data['bkg_pickup_date'])) ?></td>
                            <td class="text-center"><?= $data['bkg_total_amount'] ?></td>
                            <td class="text-center"><?= $data['cab_type'] ?></td>
                        </tr>

                        <?
                    }
                    ?>
                </tbody>
            </table>
            <div class="col-xs-12 well text-right">
                <?php
                $this->widget('CLinkPager', array('pages' => $usersList->pagination));
                ?>
            </div>
        </div></div>
<? } ?>
</div></div></div>