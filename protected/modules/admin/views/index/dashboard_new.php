<?php
$styleManualAssignment = '';
if ($manualAssignment['manualCnt'] > 0 && $manualAssignment['manualCnt'] <= 25)
{
    $styleManualAssignment = 'background-color: orange';
}
else if ($manualAssignment['manualCnt'] > 25)
{
    $styleManualAssignment = 'background-color: red';
}


$styleCriticalAssignment = '';
if ($criticalAssignment['criticalCnt'] > 0)
{
    $styleCriticalAssignment = 'background-color: red';
}

$styleDelegated = '';
if ($delegatedManager['cnt'] > 0 && $delegatedManager['cnt'] <= 25)
{
    $styleDelegated = 'background-color: blue';
}
else if ($delegatedManager['cnt'] > 25)
{
    $styleDelegated = 'background-color: red';
}

$styleReconfirmPending = '';
if ($reconfirmPending['cnt'] > 0 && $reconfirmPending['cnt'] <= 10)
{
    $styleReconfirmPending = 'background-color: orange';
}
else if ($reconfirmPending['cnt'] > 10)
{
    $styleReconfirmPending = 'background-color: red';
}

$styleNonProfitable = '';
if ($nonProfitable['cnt'] > 0 && $nonProfitable['cnt'] <= 25)
{
    $styleNonProfitable = 'background-color: orange';
}
else if ($nonProfitable['cnt'] > 25)
{
    $styleNonProfitable = 'background-color: red';
}

$styleAccountsAttention = '';
if ($accountsAttention['cnt'] > 0 && $accountsAttention['cnt'] <= 25)
{
    $styleAccountsAttention = 'background-color: orange';
}
else if ($accountsAttention['cnt'] > 25)
{
    $styleAccountsAttention = 'background-color: red';
}

$styleSmartMatching = '';
if ($countMatchList > 10 && $countMatchList <= 20)
{
    $styleSmartMatching = 'background-color: orange';
}
else if ($countMatchList > 20)
{
    $styleSmartMatching = 'background-color: red';
}
?>
<div id="content">
    <div class="mainBody">

        <div class="icopPart ">
            <div class="row" style="margin: auto">
                <div class="col-xs-12 col-sm-4 col-md-3">
                    <div class="panel panel-default"></div>
                </div>
                <div class="col-xs-12">   
                    <div id="bookingSummary">
                        <div class="row" style="display: flex; flex-wrap: wrap; ">
                            <div class="col-lg-2  col-md-4 col-sm-6" style="display: flex;">
                                <div class="panel info-box panel-white" style="background-color: black;width: 100%;">
                                    <div>

                                        <a href="<?php echo Yii::app()->createAbsoluteUrl('admin/generalReport/serviceRequests') ?>" target="_blank">
                                            <div class="panel-body">
                                                <div class="info-box-stats">                                        
                                                    <p class="counter" style="color: #FFF;"><?php echo $countAllInternalCBR; ?></p> 
                                                    <span class = "info-box-title" style="color:#FFF;">Service Requests</span>
                                                </div>
                                            </div>
                                        </a>	
                                    </div>

                                </div>
                            </div>
								<div class="col-lg-2  col-md-4 col-sm-6" style="display: flex;">
                                <div class="panel info-box panel-white" style="background-color: orange; width: 100%;">
                                    <a href="<?= Yii::app()->createAbsoluteUrl('admin/booking/list', ['source' => 253]) ?>" target="_blank">
                                        <div class="panel-body"><div class="info-box-stats">                                        
                                                <p class="counter" style="color: #FFF;"><?= $vipCustomerCount; ?></p> <span class = "info-box-title" style="color: #FFF;">Bookings with VIP/VVIP tagged customers</span>
                                            </div></div>
                                    </a></div>
                            </div>  
                            <div class="col-lg-2  col-md-4 col-sm-6" style="display: flex;">
                                <div class="panel info-box panel-white" style="background-color: red; width: 100%;">
                                    <div>

                                        <a href="<?php echo Yii::app()->createAbsoluteUrl('admin/generalReport/latepickup', ['type' => 12]) ?>" target="_blank">
                                            <div class="panel-body">
                                                <div class="info-box-stats">                                        
                                                    <p class="counter" style="color: #FFF;"><?php echo $urgentAPPickupCount; ?></p> 
                                                    <span class = "info-box-title" style="color:#FFF;">Late AP</span>
                                                </div>
                                            </div>
                                        </a>	
                                    </div>

                                </div>
                            </div>

                            <div class="col-lg-2  col-md-4 col-sm-6" style="display: flex;">
                                <div class="panel info-box panel-white" style="background-color: orange; width: 100%;">
                                    <div>

                                        <a href="<?php echo Yii::app()->createAbsoluteUrl('admin/generalReport/latepickup') ?>" target="_blank">
                                            <div class="panel-body">
                                                <div class="info-box-stats">                                        
                                                    <p class="counter" style="color: #FFF;"><?php echo $urgentPickupCount; ?></p> 
                                                    <span class = "info-box-title" style="color:#FFF;">Late Pickup</span>
                                                </div>
                                            </div>
                                        </a>	
                                    </div>

                                </div>
                            </div>

                            <div class="col-lg-2  col-md-4 col-sm-6" style="display: flex;">
                                <div class="panel info-box panel-white" style="width: 100%;<?php
                                if ($escalations['activeEscalationCnt'] > 0)
                                {
                                    echo 'background-color: orange;';
                                }
                                ?>">
                                    <div>
                                        <a href="<?= Yii::app()->createAbsoluteUrl('admin/generalReport/escalation') ?>" target="_blank">
                                            <div class="panel-body">
                                                <div class="info-box-stats">                                        
                                                    <p class="counter" style="<?php
                                                    if ($escalations['activeEscalationCnt'] > 0)
                                                    {
                                                        echo 'color: #FFF;';
                                                    }
                                                    ?>"><?= $escalations['activeEscalationCnt']; ?></p> 
                                                    <span class = "info-box-title" style="<?php
                                                    if ($escalations['activeEscalationCnt'] > 0)
                                                    {
                                                        echo 'color: #FFF;';
                                                    }
                                                    ?>">Active escalations</span>
                                                </div>
                                            </div>
                                        </a>	
                                    </div>
                                    <div style="float:right; <?php echo ($escalations['activeEscalationCnt'] > 0) ? 'color: #FFF;' : 'color: #000;'; ?> font-size: 13px; margin-right: 5px;">
                                        RED: <?= $escalations['activeEscalationRed']; ?>&nbsp;ORANGE: <?= $escalations['activeEscalationOrange']; ?>&nbsp;<br>YELLOW: <?= $escalations['activeEscalationYellow']; ?>&nbsp;BLUE: <?= $escalations['activeEscalationBlue']; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-2  col-md-4 col-sm-6" style="display: flex;" >
                                <div class="panel info-box panel-white" style="width: 100%;<?= $styleManualAssignment; ?>">
                                    <div>
                                        <a href="<?= Yii::app()->createAbsoluteUrl('admin/booking/list', ['source' => 225]) ?>" target="_blank"> 
                                            <div class="panel-body">
                                                <div class="info-box-stats">
                                                    <p class="counter" style="<?php
                                                    if ($manualAssignment['manualCnt'] > 0)
                                                    {
                                                        echo 'color: #FFF;';
                                                    }
                                                    ?>"><?= $manualAssignment['manualCnt']; ?></p>
                                                    <span class="info-box-title" style="<?php
                                                    if ($manualAssignment['manualCnt'] > 0)
                                                    {
                                                        echo 'color: #FFF;';
                                                    }
                                                    ?>">Bookings in manual assignment</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
<!--                                    <div style="float:right; <?php echo ($manualAssignment['manualCnt'] > 0) ? 'color: #FFF;' : 'color: #000;'; ?> font-size: 13px; margin-right: 5px;">
                                        B2C : <?= $manualAssignment['manualB2C']; ?>&nbsp;MMT : <?= $manualAssignment['manualMMT']; ?>&nbsp;B2BO : <?= $manualAssignment['manualB2B']; ?>
                                    </div>-->
                                </div>
                            </div>
                            <div class="col-lg-2  col-md-4 col-sm-6" style="display: flex;">
                                <div class="panel info-box panel-white" style="width: 100%;<?= $styleCriticalAssignment; ?>">
                                    <div>
                                        <a href="<?= Yii::app()->createAbsoluteUrl('admin/booking/list', ['source' => 226]) ?>" target="_blank"> 
                                            <div class="panel-body">
                                                <div class="info-box-stats">
                                                    <p class="counter" style="<?php
                                                    if ($criticalAssignment['criticalCnt'] > 0)
                                                    {
                                                        echo 'color: #FFF;';
                                                    }
                                                    ?>"><?= $criticalAssignment['criticalCnt']; ?></p>
                                                    <span class="info-box-title" style="<?php
                                                    if ($criticalAssignment['criticalCnt'] > 0)
                                                    {
                                                        echo 'color: #FFF;';
                                                    }
                                                    ?>">Bookings in critical assignment</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
<!--                                    <div style="float:right; <?php echo ($criticalAssignment['criticalCnt'] > 0) ? 'color: #FFF;' : 'color: #000;'; ?> font-size: 13px; margin-right: 5px;">
                                        B2C : <?= $criticalAssignment['criticalB2C']; ?>&nbsp;MMT : <?= $criticalAssignment['criticalMMT']; ?>&nbsp;B2BO : <?= $criticalAssignment['criticalB2B']; ?>
                                    </div> -->
                                </div>
                            </div> 

<!--                            <div class="col-lg-2  col-md-4 col-sm-6" style="display: flex;">
                                <div class="panel info-box panel-white" style="width: 100%;<?php
                                if ($countAllActiveCBR >= 0 && $countAllActiveCBR <= 50)
                                {
                                    echo 'background-color: orange;';
                                }
                                else
                                {
                                    echo 'background-color: red;';
                                }
                                ?>">
                                    <div>
                                        <a href="<?= Yii::app()->createAbsoluteUrl('admin/generalReport/scqreport') ?>" target="_blank">
                                            <div class="panel-body">
                                                <div class="info-box-stats">                                        
                                                    <p class="counter" style="<?php
                                                    if ($countAllActiveCBR > 0)
                                                    {
                                                        echo 'color: #FFF;';
                                                    }
                                                    ?>"><?= $countAllActiveCBR; ?></p> 
                                                    <span class = "info-box-title" style="<?php
                                                    if ($countAllActiveCBR > 0)
                                                    {
                                                        echo 'color: #FFF;';
                                                    }
                                                    ?>">Active CBR</span>
                                                </div>
                                            </div>
                                        </a>

                                        <?php
                                        if ($getcurrentlyServingCBR)
                                        {
                                            ?>
                                            <div style="float:right; <?php echo ($getcurrentlyServingCBR) ? 'color: #FFF;' : 'color: #000;'; ?>">
                                                Currently serving: <?= date("d/M/Y h:i a", strtotime($getcurrentlyServingCBR)); ?>
                                            </div>
                                        <?php } ?>

                                    </div>
                                </div>
                            </div>-->

                            <div class="col-lg-2  col-md-4 col-sm-6" style="display: flex;">
                                <div class="panel info-box panel-white" style="width: 100%;<?php
                                if ($countSosAlert > 0)
                                {
                                    echo 'background-color: red;';
                                }
                                ?>">
                                    <div>
                                        <a href="<?= Yii::app()->createAbsoluteUrl('admin/booking/list', ['source' => 227]) ?>" target="_blank"> 
                                            <div class="panel-body">
                                                <div class="info-box-stats">
                                                    <p class="counter" style="<?php
                                                    if ($countSosAlert > 0)
                                                    {
                                                        echo 'color: #FFF;';
                                                    }
                                                    ?>"><?= $countSosAlert; ?></p>
                                                    <span class="info-box-title" style="<?php
                                                    if ($countSosAlert > 0)
                                                    {
                                                        echo 'color: #FFF;';
                                                    }
                                                    ?>">SOS Alert</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div>&nbsp;</div>
                                </div> 
                            </div> 

<!--                            <div class="col-lg-2  col-md-4 col-sm-6" style="display: flex;">
                                <div class="panel info-box panel-white" style="width: 100%;<?php
                                if ($inventoryShortage > 0)
                                {
                                    echo 'background-color: red;';
                                }
                                ?>">
                                    <div>
                                        <a href="<?= Yii::app()->createAbsoluteUrl('admin/generalReport/zeroInventory') ?>" target="_blank"> 
                                            <div class="panel-body">
                                                <div class="info-box-stats">
                                                    <p class="counter" style="<?php
                                                    if ($inventoryShortage > 0)
                                                    {
                                                        echo 'color: #FFF;';
                                                    }
                                                    ?>"><?= $inventoryShortage; ?></p>
                                                    <span class="info-box-title" style="<?php
                                                    if ($inventoryShortage > 0)
                                                    {
                                                        echo 'color: #FFF;';
                                                    }
                                                    ?>">Zero Inventory Zones</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div>&nbsp;</div>
                                </div> 
                            </div> -->

<!--                            <div class="col-lg-2  col-md-4 col-sm-6" style="display: flex;">
                                <div class="panel info-box panel-white" style="width: 100%;<?php
                                if ($countVendorBlockPayment > 0)
                                {
                                    echo 'background-color: orange;';
                                }
                                ?>">
                                    <div>
                                        <a href="<?= Yii::app()->createAbsoluteUrl('admin/vendor/list', ['source' => 232]) ?>" target="_blank"> 
                                            <div class="panel-body">
                                                <div class="info-box-stats">
                                                    <p class="counter" style="<?php
                                                    if ($countVendorBlockPayment > 0)
                                                    {
                                                        echo 'color: #FFF;';
                                                    }
                                                    ?>"><?= $countVendorBlockPayment; ?></p>
                                                    <span class="info-box-title" style="<?php
                                                    if ($countVendorBlockPayment > 0)
                                                    {
                                                        echo 'color: #FFF;';
                                                    }
                                                    ?>">Payment Locked Vendors</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div>&nbsp;</div>
                                </div> 

                            </div> -->

                            <div class="col-lg-2  col-md-4 col-sm-6" style="display: flex;">
                                <div class="panel info-box panel-white" style="width: 100%;<?php
                                if ($autoCancelOn['cnt'] > 0)
                                {
                                    echo 'background-color: orange;';
                                }
                                ?>">
                                    <div>
                                        <a href="<?= Yii::app()->createAbsoluteUrl('admin/booking/list', ['source' => 233]) ?>" target="_blank"> 
                                            <div class="panel-body">
                                                <div class="info-box-stats">
                                                    <p class="counter" style="<?php
                                                    if ($autoCancelOn['cnt'] > 0)
                                                    {
                                                        echo 'color: #FFF;';
                                                    }
                                                    ?>"><?= $autoCancelOn['cnt']; ?></p>
                                                    <span class="info-box-title" style="<?php
                                                    if ($autoCancelOn['cnt'] > 0)
                                                    {
                                                        echo 'color: #FFF;';
                                                    }
                                                    ?>">Insufficient credit (B2B) bookings (auto-cancel ON)</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
<!--                                    <div style="float:right; <?php echo ($autoCancelOn['cnt'] > 0) ? 'color: #FFF;' : 'color: #000;'; ?> font-size: 13px; margin-right: 5px;">
                                        GOIBIBO : <?= $autoCancelOn['countIBIBO']; ?>&nbsp;MMT : <?= $autoCancelOn['countMMT']; ?>&nbsp;B2BO : <?= $autoCancelOn['countB2B']; ?>
                                    </div> -->
                                </div>
                            </div> 

                            <div class="col-lg-2  col-md-4 col-sm-6" style="display: flex;">
                                <div class="panel info-box panel-white" style="width: 100%;<?php
                                if ($getCountAutoCancelBooking > 0)
                                {
                                    echo 'background-color: red;';
                                }
                                ?>">
                                    <div>
                                        <a href="<?= Yii::app()->createAbsoluteUrl('admin/booking/list', ['source' => 252]) ?>" target="_blank"> 
                                            <div class="panel-body"><div class="info-box-stats">
                                                    <p class="counter" style="<?php
                                                    if ($getCountAutoCancelBooking > 0)
                                                    {
                                                        echo 'color: #FFF;';
                                                    }
                                                    ?>"><?= $getCountAutoCancelBooking; ?></p>
                                                    <span class="info-box-title" style="<?php
                                                    if ($getCountAutoCancelBooking > 0)
                                                    {
                                                        echo 'color: #FFF;';
                                                    }
                                                    ?>">Bookings marked for Auto-cancel</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2  col-md-4 col-sm-6" style="display: flex;">
                                <div class="panel info-box panel-white" style="width: 100%;<?php
                                if ($riskBooking['cnt'] > 0)
                                {
                                    echo 'background-color: red;';
                                }
                                ?>">
                                    <div>
                                        <a href="<?= Yii::app()->createAbsoluteUrl('admin/booking/list', ['source' => 228, 'tab' => 2]) ?>" target="_blank"> 
                                            <div class="panel-body"><div class="info-box-stats">
                                                    <p class="counter" style="<?php
                                                    if ($riskBooking['cnt'] > 0)
                                                    {
                                                        echo 'color: #FFF;';
                                                    }
                                                    ?>"><?= $riskBooking['cnt']; ?></p>
                                                    <span class="info-box-title" style="<?php
                                                    if ($riskBooking['cnt'] > 0)
                                                    {
                                                        echo 'color: #FFF;';
                                                    }
                                                    ?>">At Risk Bookings</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
<!--                                    <div style="float:right; <?php echo ($riskBooking['cnt'] > 0) ? 'color: #FFF;' : 'color: #000;'; ?> font-size: 13px; margin-right: 5px;">
                                        B2C : <?= $riskBooking['countB2C']; ?>&nbsp;MMT : <?= $riskBooking['countMMT']; ?>&nbsp;B2BO : <?= $riskBooking['countB2B']; ?>
                                    </div> -->
                                </div>
                            </div> 


                            <div class="col-lg-2  col-md-4 col-sm-6" style="display: flex;">
                                <div class="panel info-box panel-white" style="width: 100%;<?= $styleDelegated; ?>">
                                    <div>
                                        <a href="<?= Yii::app()->createAbsoluteUrl('admin/booking/list', ['source' => 224]) ?>" target="_blank">
                                            <div class="panel-body"><div class="info-box-stats">
                                                    <p class="counter" style="<?php
                                                    if ($delegatedManager['cnt'] > 0)
                                                    {
                                                        echo 'color: #FFF;';
                                                    }
                                                    ?>"><?= $delegatedManager['cnt']; ?></p> 
                                                    <span class = "info-box-title" style="<?php
                                                    if ($delegatedManager['cnt'] > 0)
                                                    {
                                                        echo 'color: #FFF;';
                                                    }
                                                    ?>">Delegated to Operation manager</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
<!--                                    <div style="float:right; <?php echo ($delegatedManager['cnt'] > 0) ? 'color: #FFF;' : 'color: #000;'; ?> font-size: 13px; margin-right: 5px;">
                                        B2C : <?= $delegatedManager['countB2C']; ?>&nbsp;MMT : <?= $delegatedManager['countMMT']; ?>&nbsp;B2BO : <?= $delegatedManager['countB2B']; ?>
                                    </div>-->
                                </div>
                            </div>
                            <div class="col-lg-2  col-md-4 col-sm-6" style="display: flex;">
                                <div class="panel info-box panel-white" style="width: 100%;<?php
                                if ($getOfflineDriverCount > 0)
                                {
                                    echo "background-color: red";
                                }
                                ?>">
                                    <div>
                                        <a href="<?= Yii::app()->createAbsoluteUrl('admin/booking/list', ['source' => 250]) ?>" target="_blank">
                                            <div class="panel-body"><div class="info-box-stats">
                                                    <p class="counter" <?php
                                                    if ($getOfflineDriverCount > 0)
                                                    {
                                                        echo 'style="color: #FFF;"';
                                                    }
                                                    ?>    ><?= $getOfflineDriverCount; ?></p> 
                                                    <span class = "info-box-title" <?php
                                                    if ($getOfflineDriverCount > 0)
                                                    {
                                                        echo 'style="color: #FFF;"';
                                                    }
                                                    ?>  >Drivers Not logged in prior to pickup</span>

                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                </div>
                            </div>
                            <div class="col-lg-2  col-md-4 col-sm-6" style="display: flex;">
                                <div class="panel info-box panel-white" style="width: 100%;<?php
                                if ($getCountDriverNotLeftforPickup > 0)
                                {
                                    echo "background-color: red";
                                }
                                ?>">
                                    <div>
                                        <a href="<?= Yii::app()->createAbsoluteUrl('admin/booking/list', ['source' => 251]) ?>" target="_blank">
                                            <div class="panel-body"><div class="info-box-stats">
                                                    <p class="counter"  <?php
                                                    if ($getCountDriverNotLeftforPickup > 0)
                                                    {
                                                        echo 'style="color: #FFF;"';
                                                    }
                                                    ?>   ><?= $getCountDriverNotLeftforPickup; ?></p> 
                                                    <span class = "info-box-title" <?php
                                                    if ($getCountDriverNotLeftforPickup > 0)
                                                    {
                                                        echo 'style="color: #FFF;"';
                                                    }
                                                    ?> >Drivers Not left for pickup </span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                </div>
                            </div>



                            <div class="col-lg-2  col-md-4 col-sm-6" style="display: flex;">
                                <div class="panel info-box panel-white" style="width: 100%;<?php
                                if ($completionOverdue['CNT'] > 0)
                                {
                                    echo "background-color: red";
                                }
                                ?>">
                                    <div>
                                        <a href="<?= Yii::app()->createAbsoluteUrl('admin/booking/list', ['source' => 230]) ?>" target="_blank"> 
                                            <div class="panel-body">
                                                <div class="info-box-stats">
                                                    <p class="counter" <?php
                                                    if ($completionOverdue['CNT'] > 0)
                                                    {
                                                        echo 'style="color: #FFF;"';
                                                    }
                                                    ?>><?= $completionOverdue['CNT']; ?></p>
                                                    <span class="info-box-title" <?php
                                                    if ($completionOverdue['CNT'] > 0)
                                                    {
                                                        echo 'style="color: #FFF;"';
                                                    }
                                                    ?>>Completion overdue</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
<!--                                    <div style="float:right; <?php echo ($completionOverdue['CNT'] > 0) ? 'color: #FFF;' : 'color: #000;'; ?> font-size: 12px; margin-right: 5px;">
                                        B2C : <?= $completionOverdue['countB2C']; ?>&nbsp;MMT : <?= $completionOverdue['countMMT']; ?>&nbsp;B2BO : <?= $completionOverdue['countB2B']; ?>
                                    </div>-->
                                </div>
                            </div>





                            <div class="col-lg-2  col-md-4 col-sm-6" style="display: flex;">
                                <div class="panel info-box panel-white" style="width: 100%;<?php
                                if ($uncommonRoutes['cnt'] > 0)
                                {
                                    echo 'background-color: red;';
                                }
                                ?>">

                                    <div>
                                        <a href="<?= Yii::app()->createAbsoluteUrl('admin/booking/list', ['source' => 234]) ?>" target="_blank">
                                            <div class="panel-body"><div class="info-box-stats">                                        
                                                    <p class="counter" style="<?php
                                                    if ($uncommonRoutes['cnt'] > 0)
                                                    {
                                                        echo 'color: #FFF;';
                                                    }
                                                    ?>"><?= $uncommonRoutes['cnt']; ?></p> <span class = "info-box-title" style="<?php
                                                       if ($uncommonRoutes['cnt'] > 0)
                                                       {
                                                           echo 'color: #FFF;';
                                                       }
                                                       ?>" >Uncommon route bookings </span>
                                                </div></div>
                                        </a>
<!--                                        <div style="float:right; <?php echo ($uncommonRoutes['cnt'] > 0) ? 'color: #FFF;' : 'color: #000;'; ?> font-size: 13px; margin-right: 5px;">
                                            B2C : <?= $uncommonRoutes['countB2C']; ?>&nbsp;MMT : <?= $uncommonRoutes['countMMT']; ?>&nbsp;B2BO : <?= $uncommonRoutes['countB2B']; ?>
                                        </div>-->
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2  col-md-4 col-sm-6" style="display: flex;">
                                <div class="panel info-box panel-white" style="width: 100%;<?= $styleAccountsAttention; ?>">
                                    <div>
                                        <a href="<?= Yii::app()->createAbsoluteUrl('admin/booking/list', ['source' => 205]) ?>" target="_blank">
                                            <div class="panel-body">
                                                <div class="info-box-stats">                                        
                                                    <p class="counter" style="<?php
                                                    if ($accountsAttention['cnt'] > 0)
                                                    {
                                                        echo 'color: #FFF;';
                                                    }
                                                    ?>"><?= $accountsAttention['cnt']; ?></p>
                                                    <span class = "info-box-title" style="<?php
                                                    if ($accountsAttention['cnt'] > 0)
                                                    {
                                                        echo 'color: #FFF;';
                                                    }
                                                    ?>">Bookings need Accounts attention</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
<!--                                    <div style="float:right; <?php echo ($accountsAttention['cnt'] > 0) ? 'color: #FFF;' : 'color: #000;'; ?> font-size: 13px; margin-right: 5px;">
                                        B2C : <?= $accountsAttention['countB2C']; ?>&nbsp;MMT : <?= $accountsAttention['countMMT']; ?>&nbsp;B2BO : <?= $accountsAttention['countB2B']; ?>
                                    </div>-->
                                </div>
                            </div>
                            <div class="col-lg-2  col-md-4 col-sm-6" style="display: flex;">
                                <div class="panel info-box panel-white" style="width: 100%;">
                                    <a href="<?= Yii::app()->createAbsoluteUrl('admin/booking/list', ['source' => 231]) ?>" target="_blank">
                                        <div class="panel-body"><div class="info-box-stats">                                        
                                                <p class="counter"><?= $countPendingRefundApprovals['cnt']; ?></p> <span class = "info-box-title">Refund Approvals Pending </span>
                                            </div></div>
                                    </a></div>
                            </div>

                            <div class="col-lg-2  col-md-4 col-sm-6" style="display: flex;">
                                <div class="panel info-box panel-white" style="width: 100%;<?= $styleReconfirmPending; ?>">
                                    <div>
                                        <a href="<?= Yii::app()->createAbsoluteUrl('admin/booking/list', ['source' => 219]) ?>" target="_blank">
                                            <div class="panel-body"><div class="info-box-stats">
                                                    <p class="counter" style="<?php
                                                    if ($reconfirmPending['cnt'] > 0)
                                                    {
                                                        echo 'color: #FFF;';
                                                    }
                                                    ?>"><?= $reconfirmPending['cnt']; ?></p>
                                                    <span class = "info-box-title" style="<?php
                                                    if ($reconfirmPending['cnt'] > 0)
                                                    {
                                                        echo 'color: #FFF;';
                                                    }
                                                    ?>">Bookings have "Reconfirm Pending" in next 36hours</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>	
                            </div>	
<!--                            <div class="col-lg-2  col-md-4 col-sm-6" style="display: flex;">
                                <div class="panel info-box panel-white" style="width: 100%;<?= $styleNonProfitable; ?>">
                                    <div>
                                        <a href="<?= Yii::app()->createAbsoluteUrl('admin/booking/list', ['source' => 220]) ?>" target="_blank">
                                            <div class="panel-body"><div class="info-box-stats">
                                                    <p class="counter" style="<?php
                                                    if ($nonProfitable['cnt'] > 0)
                                                    {
                                                        echo 'color: #FFF;';
                                                    }
                                                    ?>"><?= $nonProfitable['cnt']; ?></p>
                                                    <span class = "info-box-title" style="<?php
                                                    if ($nonProfitable['cnt'] > 0)
                                                    {
                                                        echo 'color: #FFF;';
                                                    }
                                                    ?>">Not profitable bookings in system</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div style="float:right; <?php echo ($nonProfitable['cnt'] > 0) ? 'color: #FFF;' : 'color: #000;'; ?> font-size: 13px; margin-right: 5px;">
                                        B2C : <?= $nonProfitable['countB2C']; ?>&nbsp;MMT : <?= $nonProfitable['countMMT']; ?>&nbsp;B2BO : <?= $nonProfitable['countB2B']; ?>
                                    </div>
                                </div>
                            </div>-->

<!--                            <div class="col-lg-2  col-md-4 col-sm-6" style="display: flex;">
                                <div class="panel info-box panel-white" style="width: 100%;<?= $styleSmartMatching; ?>">
                                    <div>
                                        <a href="<?= Yii::app()->createAbsoluteUrl('admin/booking/smartMatchList') ?>" target="_blank">
                                            <div class="panel-body">
                                                <div class="info-box-stats">                                        
                                                    <p class="counter" style="<?php
                                                    if ($countMatchList > 0)
                                                    {
                                                        echo 'color: #FFF;';
                                                    }
                                                    ?>"><?= $countMatchList; ?></p>
                                                    <span class = "info-box-title" style="<?php
                                                    if ($countMatchList > 0)
                                                    {
                                                        echo 'color: #FFF;';
                                                    }
                                                    ?>">Bookings need smart matching attention</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>-->

                            <div class="col-lg-2  col-md-4 col-sm-6" style="display: flex;">
                                <div class="panel info-box panel-white" style="width: 100%; <?php
                                if ($pickupOverdue['CNT'] > 0)
                                {
                                    echo "background-color: red";
                                }
                                ?>">
                                    <div>
                                        <a href="<?= Yii::app()->createAbsoluteUrl('admin/booking/list', ['source' => 229]) ?>" target="_blank"> 
                                            <div class="panel-body">
                                                <div class="info-box-stats">
                                                    <p class="counter" <?php
                                                    if ($pickupOverdue['CNT'] > 0)
                                                    {
                                                        echo 'style="color: #FFF;"';
                                                    }
                                                    ?> ><?= $pickupOverdue['CNT']; ?></p>
                                                    <span class="info-box-title" <?php
                                                    if ($pickupOverdue['CNT'] > 0)
                                                    {
                                                        echo 'style="color: #FFF;"';
                                                    }
                                                    ?> >Pickup Overdue</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
<!--                                    <div style="float:right; <?php echo ($pickupOverdue['CNT'] > 0) ? 'color: #FFF;' : 'color: #000;'; ?> font-size: 12px; margin-right: 5px;">
                                        B2C : <?= $pickupOverdue['countB2C']; ?>&nbsp;MMT : <?= $pickupOverdue['countMMT']; ?>&nbsp;B2BO : <?= $pickupOverdue['countB2B']; ?>
                                    </div>-->
                                </div>
                            </div>






                            <div class="col-lg-2  col-md-4 col-sm-6" style="display: flex;">
                                <div class="panel info-box panel-white" style="width: 100%;">
                                    <a href="<?= Yii::app()->createAbsoluteUrl('admin/generalReport/inventoryShortage') ?>" target="_blank">
                                        <div class="panel-body"><div class="info-box-stats">                                        
                                                <p class="counter"><?= $nmiAppliedZone; ?></p> <span class = "info-box-title">NMI applied zone</span>
                                            </div></div>
                                    </a></div>
                            </div>


                            <div class="col-lg-2  col-md-4 col-sm-6" style="display: flex;">
                                <div class="panel info-box panel-white" style="width: 100%;">
                                    <a href="<?= Yii::app()->createAbsoluteUrl('admin/booking/list', ['source' => 201]) ?>" target="_blank"> 
                                        <div class="panel-body"><div class="info-box-stats">
                                                <p class="counter"><?= $countMissingDrivers; ?></p>
                                                <span class="info-box-title">Bookings with missing drivers (36 Hours)</span>
                                            </div></div>
                                    </a>
                                </div>
                            </div>                        
                            <div class="col-lg-2  col-md-4 col-sm-6" style="display: flex;">
                                <div class="panel info-box panel-white" style="width: 100%;">
                                    <a href="<?= Yii::app()->createAbsoluteUrl('admin/booking/list', ['source' => 202]) ?>" target="_blank">
                                        <div class="panel-body">
                                            <div class="info-box-stats">                                        
                                                <p class="counter"><?= $countUnassignedVendors ?></p>
                                                <span class="info-box-title">Bookings still unassigned (next 48 Hours)</span>
                                            </div>
                                        </div>
                                    </a>
                                </div> 
                            </div> 


<!--                            <div class="col-lg-2  col-md-4 col-sm-6" style="display: flex;">
                                <div class = "panel info-box panel-white" style="width: 100%;">
                                    <a href = "<?= Yii::app()->createAbsoluteUrl('admin/booking/list', ['source' => 203]) ?>" target="_blank">
                                        <div class="panel-body"><div class = "info-box-stats"> 
                                                <p class = "counter"><?= $countTripVendors ?></p> 
                                                <span class = "info-box-title">Low rating vendor for upcoming trip</span>
                                            </div></div>
                                    </a>
                                </div></div>                        -->
<!--                            <div class="col-lg-2  col-md-4 col-sm-6" style="display: flex;">
                                <div class = "panel info-box panel-white" style="width: 100%;">
                                    <a href = "<?= Yii::app()->createAbsoluteUrl('admin/booking/list', ['source' => 204]) ?>" target = "_blank">
                                        <div class = "panel-body"><div class = "info-box-stats"> 
                                                <p class = "counter"><?= $countTripDrivers ?></p> 
                                                <span class = "info-box-title">Low rating driver for upcoming trip</span>
                                            </div></div>
                                    </a>
                                </div></div>-->

                            <div class="col-lg-2  col-md-4 col-sm-6" style="display: flex;">
                                <div class="panel info-box panel-white" style="width: 100%;">
                                    <a href="<?= Yii::app()->createAbsoluteUrl('admin/booking/list', ['source' => 206]) ?>" target="_blank">
                                        <div class="panel-body"><div class="info-box-stats">                                        
                                                <p class="counter"><?= $countUnverifiedLeeds; ?></p> <span class = "info-box-title">Unverified bookings</span>
                                            </div></div>
                                    </a></div>
                            </div>

                            <div class="col-lg-2  col-md-4 col-sm-6" style="display: flex;">
                                <div class="panel info-box panel-white" style="width: 100%;">
                                    <a href="<?= Yii::app()->createAbsoluteUrl('admin/booking/list', ['source' => 208]) ?>" target="_blank">
                                        <div class="panel-body"><div class="info-box-stats">                                        
                                                <p class="counter"><?= $countMissingDriversDoc; ?></p> <span class = "info-box-title">Drivers missing docs</span>
                                            </div></div>
                                    </a></div>
                            </div>                        

                            <div class="col-lg-2  col-md-4 col-sm-6" style="display: flex;">
                                <div class="panel info-box panel-white" style="width: 100%;">
                                    <a href="<?= Yii::app()->createAbsoluteUrl('admin/vendor/list', ['source' => 210]) ?>" target="_blank">
                                        <div class="panel-body"><div class="info-box-stats">                                        
                                                <p class="counter"><?= $countVendorDocMissing; ?></p> <span class = "info-box-title">Vendors with doc missing (in system)</span>
                                            </div></div>
                                    </a></div>
                            </div>                        
                            <div class="col-lg-2  col-md-4 col-sm-6" style="display: flex;">
                                <div class="panel info-box panel-white" style="width: 100%;">
                                    <a href="<?= Yii::app()->createAbsoluteUrl('admin/vendor/list', ['source' => 211]) ?>" target="_blank">
                                        <div class="panel-body"><div class="info-box-stats">                                        
                                                <p class="counter"><?= $countVendorBankMissing; ?></p> <span class = "info-box-title">Vendors with bank details and/or PAN missing (in system)</span>
                                            </div></div>
                                    </a></div>
                            </div>                        
<!--                            <div class="col-lg-2  col-md-4 col-sm-6" style="display: flex;">
                                <div class="panel info-box panel-white" style="width: 100%;">
                                    <a href="<?= Yii::app()->createAbsoluteUrl('admin/booking/list', ['source' => 215]) ?>" target="_blank">
                                        <div class="panel-body"><div class="info-box-stats">                                        
                                                <p class="counter"><?= $countUndocumentNonCommercial; ?></p> <span class = "info-box-title">Undocumented cars in next 48 hours (not commercial)</span>
                                            </div></div>
                                    </a></div>
                            </div>                        -->
<!--                            <div class="col-lg-2  col-md-4 col-sm-6" style="display: flex;">
                                <div class="panel info-box panel-white" style="width: 100%;">
                                    <a href="<?= Yii::app()->createAbsoluteUrl('admin/booking/list', ['source' => 216]) ?>" target="_blank">
                                        <div class="panel-body"><div class="info-box-stats">                                       
                                                <p class="counter"><?= $countUndocumentCommercial; ?></p> <span class = "info-box-title">Undocumented cars in next 48 hours (commercial verified, but not approved)</span>
                                            </div></div>
                                    </a></div>
                            </div>                        -->
                            <div class="col-lg-2  col-md-4 col-sm-6" style="display: flex;">
                                <div class="panel info-box panel-white" style="width: 100%;">
                                    <a href="<?= Yii::app()->createAbsoluteUrl('admin/booking/list', ['source' => 218]) ?>" target="_blank">
                                        <div class="panel-body"><div class="info-box-stats">                                        
                                                <p class="counter"><?= $countVendorFloating24hrs; ?></p> <span class = "info-box-title">Bookings not picked up by any vendor despite floating for 24hours</span>
                                            </div></div>
                                    </a></div>
                            </div>                        

<!--                            <div class="col-lg-2  col-md-4 col-sm-6" style="display: flex;">
                                <div class="panel info-box panel-white" style="width: 100%;">
                                    <a href="<?= Yii::app()->createAbsoluteUrl('admin/booking/list', ['source' => 217]) ?>" target="_blank">
                                        <div class="panel-body"><div class="info-box-stats">                                        
                                                <p class="counter"><?= $countVendorUnassigned5days; ?></p> <span class = "info-box-title">Bookings created > 2 days ago and not assigned still</span>
                                            </div></div>
                                    </a></div>
                            </div>-->
<!--                            <div class="col-lg-2  col-md-4 col-sm-6" style="display: flex;">
                                <div class="panel info-box panel-white" style="width: 100%;">
                                    <a href="<?= Yii::app()->createAbsoluteUrl('/admpnl/vehicle/carverifydoclist?ctype=boost-verify') ?>" target="_blank">
                                        <div class="panel-body"><div class="info-box-stats">                                        
                                                <p class="counter"><?= $getBoostVerifyPendingCabs; ?></p> <span class = "info-box-title">Boost verify pending approval cabs</span>
                                            </div></div>
                                    </a></div>
                            </div>-->
							
						 
                        </div>
                    </div>

                </div>
            </div>    
            <div class="col-xs-12 col-sm-4 col-md-3">
                <div class="panel panel-default">
                    <div><input type="checkbox" id="dto_auto_check" <?= ($_COOKIE['dto_username'] != "") ? "checked" : "" ?> name="dto_auto_check"/>Auto-Refresh every 
                        <select id="dto_mins" name="dto_mins" onchange="changeTimeout()">
                            <option value="5">5</option>
                            <option value="15">15</option>
                            <option value="30">30</option>
                            <option value="45">45</option>
                        </select> Minutes</div> 
                </div>
                <a href="/admpnl/cache/clear?key=dashboard" target="_blank">Clear Dashboard Cache</a>
            </div> 



        </div>
    </div>
    <div class="clr"></div>
</div>
</div>
<script>
    var dtoMinsVal = 5;
    var timeout;
    $(document).ready(function ()
    {
        $("#dto_mins").val($.cookie('dto_username'));
        dtoMinsVal = $("#dto_mins").val();
        $("#dto_auto_check").change(function ()
        {
            if ($("#dto_auto_check").is(':checked'))
            {
                changeTimeout();
                refreshInterval();
            } else
            {
                $.removeCookie('dto_username');
                clearTimeout(timeout);
            }
        });

        if ($("#dto_auto_check").is(':checked'))
        {
            refreshInterval();
        }
    });

    function getRefreshInterval() {
        dtoMinsVal = $.cookie('dto_username');
        if (dtoMinsVal == undefined)
        {
            dtoMinsVal == 5;
        }
        return dtoMinsVal;
    }

    function changeTimeout() {
        var dtoMinsVal = $("#dto_mins").val();
        if (dtoMinsVal == undefined || dtoMinsVal == null)
        {
            dtoMinsVal = 5;
        }
        $.cookie('dto_username', dtoMinsVal);
        $("#dto_auto_check").prop('checked', true);
    }

    function dashRefresh()
    {
        $.ajax({
            type: "GET",
            url: '/admpnl/index/dashboard',
            dataType: "html",
            success: function (response) {
                var container = document.createElement('div');
                container.innerHTML = response;
                $("#bookingSummary").html($(container).find("#bookingSummary").html());
            },
            failure: function (response) {
                alert(response.d);
            }
        });

        if ($("#dto_auto_check").is(':checked'))
        {
            refreshInterval();
        } else
        {
            clearTimeout(timeout);
        }
    }

    function refreshInterval() {
        if (timeout != undefined && timeout != null)
        {
            clearTimeout(timeout);
        }
        dtoMinsVal = getRefreshInterval();
        timeout = setTimeout(function () {
            dashRefresh();
        }, dtoMinsVal * 60 * 1000);

    }

</script>