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
    <div class="col-xs-12 text-center h4  mt0">   
    </div>
</div>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-body p0">
            <div class="col-xs-12">
                <div class="row bordered mt10 pt10">
                    <div class="col-xs-12 col-sm-6 col-md-4 mb10">
                        <span class="color-gray">Sticky Score:</span> <br><span class="font-18 line-height20"><b><?= $model->vrs_sticky_score; ?></b></span>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4 mb10">
                        <span class="color-gray">Security Amount: </span>
						<br><span class="font-18 line-height20"><b><?= $vendorAccount['vnd_security_amount']; ?></b></span>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4 mb10">
                        <span class="color-gray">Overall Rating: </span><br><span class="font-18 line-height20"><b><?= $model->vrs_vnd_overall_rating; ?></b></span>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4 mb10">
                        <span class="color-gray">Total Trip :</span><br><span class="font-18 line-height20"><b><?= $model->vrs_vnd_total_trip; ?></b></span>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-4 mb10">
                        <span class="color-gray">Total Bid: </span><br><span class="font-18 line-height20"><b><?= $model->vrs_tot_bid; ?></b></span>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4 mb10">
                        <span class="color-gray">Count Driver: </span><br><span class="font-18 line-height20"><b><?= $model->vrs_count_driver; ?></b></span>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4 mb10">
                        <span class="color-gray">Count Car: </span><br><span class="font-18 line-height20"><b><?= $model->vrs_count_car ?></b></span>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-4 mb10">
                        <span class="color-gray">Approve Driver Count: </span><br><span class="font-18 line-height20"><b><?= $model->vrs_approve_driver_count; ?></b></span>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4 mb10">
                        <span class="color-gray">Approve Car Count: </span><br><span class="font-18 line-height20"><b><?= $model->vrs_approve_car_count; ?></b></span>
                    </div>
                </div>
                <div class="row bordered mt10">
                    <div class="col-xs-12 col-sm-6 col-md-4 pt10 mb10">
                        <span class="color-gray">Trust Score: </span><br><span class="font-18 line-height20"><b><?= $model->vrs_trust_score; ?></b></span>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4 pt10 mb10">
                        <span class="color-gray">Docs Score: </span><br><span class="font-18 line-height20"><b><?= $model->vrs_docs_score; ?></b></span>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4 pt10 mb10">
                        <span class="color-gray">No Of Star </span><br><span class="font-18 line-height20"><b><?= $model->vrs_no_of_star; ?></b></span>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4 pt10 mb10">
                        <span class="color-gray">Denied Duty Count</span><br><span class="font-18 line-height20"><b><?= $model->vrs_denied_duty_cnt; ?></b></span>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-4 pt10 mb10">
                        <span class="color-gray">Total Trips: </span><br><span class="font-18 line-height20"><b><?= $model->vrs_total_trips; ?></b></span>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4 pt10 mb10">
                        <span class="color-gray">Locked Amount </span>
						<br><span class="font-18 line-height20"><b><?= $vendorAccount['locked_amount']; ?></b></span>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4 pt10 mb10">
                        <span class="color-gray">Withdrawable Balance: </span><br>
						<span class="font-18 line-height20"><b><?= $vendorAccount['withdrawable_balance'] ?></b></span>
                    </div>


                </div>
                <div class="row bordered mt10">
                    <div class="col-xs-12 col-sm-6 col-md-4 pt10 mb10">
                        <span class="color-gray">Last Booking Completed: </span><br><span class="font-18 line-height20"><b><?= !empty($model->vrs_last_bkg_cmpleted) ? date("d-m-Y H:i:s", strtotime($model->vrs_last_bkg_cmpleted)) : "-"; ?></b></span>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4 pt10 mb10">
                        <span class="color-gray">Total Completed 30days: </span><br><span class="font-18 line-height20"><b><?= $model->vrs_total_completed_days_30; ?></b></span>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4 pt10 mb10">
                        <span class="color-gray">Total vehicle 30days </span><br><span class="font-18 line-height20"><b><?= $model->vrs_total_vehicle_30; ?></b></span>
                    </div>


                </div>
                <div class="row bordered mt10">
                    <div class="col-xs-12 col-sm-6 col-md-4 pt10 mb10">
                        <span class="color-gray">Driver App Used: </span><br><span class="font-18 line-height20"><b><?= $model->vrs_driver_app_used; ?></b></span>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4 pt10 mb10">
                        <span class="color-gray">Penalty Count: </span><br><span class="font-18 line-height20"><b><?= $model->vrs_penalty_count; ?></b></span>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4 pt10 mb10">
                        <span class="color-gray">Total booking </span><br><span class="font-18 line-height20"><b><?= $model->vrs_total_booking; ?></b></span>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-4 pt10 mb10">
                        <span class="color-gray">Vendor Margin: </span><br><span class="font-18 line-height20"><b><?= $model->vrs_margin; ?></b></span>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4 pt10 mb10">
                        <span class="color-gray">Bid Win Percentage: </span><br><span class="font-18 line-height20"><b><?= $model->vrs_bid_win_percentage; ?></b></span>
                    </div>
				</div>
				<?php
				if ($dependency['result']['bookingAssigned'] > 0)
				{
					?>
					<div class="row bordered mt10">
						<div class="col-xs-12 col-sm-6 col-md-4 pt10 mb10">
							<span class="color-gray">Dependency within <?= $dependency['day'] ?> days: </span><br><span class="font-18 line-height20"><b><?= $model->vrs_dependency; ?></b></span>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-4 pt10 mb10">
							<span class="color-gray">Boosted dependency Score: </span><br><span class="font-18 line-height20"><b><?= $model->vrs_boost_dependency; ?></b></span>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-4 pt10 mb10">
							<span class="color-gray">Total Booking Assigned within <?= $dependency['day'] ?> days: </span><br><span class="font-18 line-height20"><b><?= $dependency['result']['bookingAssigned']; ?></b></span>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-4 pt10 mb10">
							<span class="color-gray">Total booking direct accept within <?= $dependency['day'] ?> days </span><br><span class="font-18 line-height20"><b><?= $dependency['result']['bookingDirectAccept']; ?></b></span>
						</div>

						<div class="col-xs-12 col-sm-6 col-md-4 pt10 mb10">
							<span class="color-gray">Total booking bid accept within <?= $dependency['day'] ?> days: </span><br><span class="font-18 line-height20"><b><?= $dependency['result']['bookingBidAccept']; ?></b></span>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-4 pt10 mb10">
							<span class="color-gray">Total booking manual accept within <?= $dependency['day'] ?> days: </span><br><span class="font-18 line-height20"><b><?= $dependency['result']['bookingManualAccept']; ?></b></span>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-4 pt10 mb10">
							<span class="color-gray">Total booking Gozo Now Accept within <?= $dependency['day'] ?> days: </span><br><span class="font-18 line-height20"><b><?= $dependency['result']['bookingGNowAccept']; ?></b></span>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-4 pt10 mb10">
							<span class="color-gray">Total direct accept booking cancel within <?= $dependency['day'] ?> days: </span><br><span class="font-18 line-height20"><b><?= $dependency['result']['bookingDirectCancelled']; ?></b></span>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-4 pt10 mb10">
							<span class="color-gray">Total bid accept booking cancel within <?= $dependency['day'] ?> days: </span><br><span class="font-18 line-height20"><b><?= $dependency['result']['bookingBidCancelled']; ?></b></span>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-4 pt10 mb10">
							<span class="color-gray">Total manual accept booking cancel within <?= $dependency['day'] ?> days: </span><br><span class="font-18 line-height20"><b><?= $dependency['result']['bookingManualCancelled']; ?></b></span>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-4 pt10 mb10">
							<span class="color-gray">Total GNOW booking cancel within <?= $dependency['day'] ?> days: </span><br><span class="font-18 line-height20"><b><?= $dependency['result']['bookingGNowCancelled']; ?></b></span>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-4 pt10 mb10">
							<span class="color-gray">Total booking cancel within <?= $dependency['day'] ?> days: </span><br><span class="font-18 line-height20"><b><?= $dependency['result']['bookingCancelled']; ?></b></span>
						</div>

					</div>

					<?php
				}
				else
				{
					?>
					<div class="row bordered mt10">
						<div class="col-xs-12 col-sm-6 col-md-4 pt10 mb10">
							<span class="color-gray">Dependency within <?= $dependency['day'] ?> days: </span><br><span class="font-18 line-height20"><b><?= $model->vrs_dependency; ?></b></span>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-4 pt10 mb10">
							<span class="color-gray">Boosted dependency Score: </span><br><span class="font-18 line-height20"><b><?= $model->vrs_boost_dependency; ?></b></span>
						</div>
					</div>
					<?php
				}
				$total_unassigns_stage1_count	 = !empty($model->vrs_step1_unassign_count) ? intval($model->vrs_step1_unassign_count) : 0;
				$total_unassigns_stage2_count	 = !empty($model->vrs_step2_unassign_count) ? intval($model->vrs_step2_unassign_count) : 0;
				$total_unassigns_system_count	 = !empty($model->vrs_system_unassign_count) ? intval($model->vrs_system_unassign_count) : 0;
				$total_unassigns_count			 = $total_unassigns_stage1_count + $total_unassigns_stage2_count + $total_unassigns_system_count;
				?>
				<div class="row bordered mt10">
					<div class="col-xs-12 col-sm-6 col-md-4 pt10 mb10">
                        <span class="color-gray">Count Total Self Accepts (3 Months): </span><br><span class="font-18 line-height20"><b><?= !empty($model->vrs_self_accept_90_days) ? $model->vrs_self_accept_90_days : 0; ?></b></span>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4 pt10 mb10">
                        <span class="color-gray">Count Total Bid Accepts (3 Months): </span><br><span class="font-18 line-height20"><b><?= !empty($model->vrs_bid_accept_90_days) ? $model->vrs_bid_accept_90_days : 0; ?></b></span>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4 pt10 mb10">
                        <span class="color-gray">Count Total Manual Accepts(3 Months) : </span><br><span class="font-18 line-height20"><b><?= !empty($model->vrs_manual_accept_90_days) ? $model->vrs_manual_accept_90_days : 0; ?></b></span>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4 pt10 mb10">
                        <span class="color-gray">Count Accepts for each Vendor: </span><br><span class="font-18 line-height20"><b><?= !empty($model->vrs_total_accept_90_days) ? $model->vrs_total_accept_90_days : 0; ?></b></span>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4 pt10 mb10">
                        <span class="color-gray">Count Total unassign: </span><br><span class="font-18 line-height20"><b><?= $total_unassigns_count ?></b></span>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4 pt10 mb10">
                        <span class="color-gray">Count Self unassign Stage1 : </span><br><span class="font-18 line-height20"><b><?= $total_unassigns_stage1_count ?></b></span>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4 pt10 mb10">
                        <span class="color-gray">Count Self unassign Stage2: </span><br><span class="font-18 line-height20"><b><?= $total_unassigns_stage2_count; ?></b></span>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4 pt10 mb10">
                        <span class="color-gray">Count System unassign: </span><br><span class="font-18 line-height20"><b><?= $total_unassigns_system_count ?></b></span>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4 pt10 mb10">
                        <span class="color-gray">Last Accept Date: </span><br><span class="font-18 line-height20"><b><?= (!empty($model->vrs_last_direct_assign_date) ? date("d-m-Y H:i:s", strtotime($model->vrs_last_direct_assign_date)) : '-') ?></b></span>
                    </div>
					<div class="col-xs-12 col-sm-6 col-md-4 pt10 mb10">
                        <span class="color-gray">Last Manual Assign Date: </span><br><span class="font-18 line-height20"><b><?= (!empty($model->vrs_last_manual_assign_date) ? date("d-m-Y H:i:s", strtotime($model->vrs_last_manual_assign_date)) : '-') ?></b></span>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4 pt10 mb10">
                        <span class="color-gray">Last Auto Assign Date: </span><br><span class="font-18 line-height20"><b><?= (!empty($model->vrs_last_bid_assign_date) ? date("d-m-Y H:i:s", strtotime($model->vrs_last_bid_assign_date)) : '-'); ?></b></span>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4 pt10 mb10">
                        <span class="color-gray">Last System unassign Date: </span><br><span class="font-18 line-height20"><b><?= (!empty($model->vrs_system_unassign_date) ? date("d-m-Y H:i:s", strtotime($model->vrs_system_unassign_date)) : '-'); ?></b></span>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4 pt10 mb10">
                        <span class="color-gray">Last Self unassign Stage1 Date: </span><br><span class="font-18 line-height20"><b><?= (!empty($model->vrs_last_self_unassign_stage1_date) ? date("d-m-Y H:i:s", strtotime($model->vrs_last_self_unassign_stage1_date)) : '-'); ?></b></span>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4 pt10 mb10">
                        <span class="color-gray">Last Self unassign Stage2 Date: </span><br><span class="font-18 line-height20"><b><?= (!empty($model->vrs_last_self_unassign_stage2_date) ? date("d-m-Y H:i:s", strtotime($model->vrs_last_self_unassign_stage2_date)) : '-'); ?></b></span>
                    </div>
					<div class="col-xs-12 col-sm-6 col-md-4 pt10 mb10">
                        <span class="color-gray">First approval Date: </span><br><span class="font-18 line-height20"><b><?= (!empty($model->vrs_first_approve_date) ? date("d-m-Y H:i:s", strtotime($model->vrs_first_approve_date)) : '-'); ?></b></span>
                    </div>
					<div class="col-xs-12 col-sm-6 col-md-4 pt10 mb10">
                        <span class="color-gray">Last approval Date: </span><br><span class="font-18 line-height20"><b><?= (!empty($model->vrs_last_approve_date) ? date("d-m-Y H:i:s", strtotime($model->vrs_last_approve_date)) : '-'); ?></b></span>
                    </div>
                </div> 

            </div>    
        </div>
    </div>    
</div>
