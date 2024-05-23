<div class="row">
    <div class="col-xs-12 widget-tab-box3">
        <div class="panel panel-default">
            <div class="panel-body p0">
                <div class="row bordered m0 pt15 pb15">
                    <div class="col-xs-12 col-md-4 mb20">
                        <p class="color-gray mb0">License Number:</p>
                        <span class="font-14 line-height20"><b><?= ($model->drvContact->ctt_license_no!='' && $model->drvContact->ctt_license_no!=NULL) ? $model->drvContact->ctt_license_no : '';?></b></span>
                    </div>
                    <div class="col-xs-12 col-md-4 mb20">
                        <p class="color-gray mb0">License Expiry Date:</p>
                        <span class="font-14 line-height20"><?= ($model->drvContact->ctt_license_exp_date!='' && $model->drvContact->ctt_license_exp_date!=NULL) ? date('d/m/Y h:i A', strtotime($model->drvContact->ctt_license_exp_date)) : '';?></span>
                    </div>
                    <div class="col-xs-12 col-md-4 mb20">
                        <p class="color-gray mb0">Date Of Joining:</p>
                        <span class="font-14 line-height20"><?= date('d/m/Y h:i A', strtotime($model->drv_created)); ?></span>
                    </div>
                    <div class="col-xs-12 col-md-4 mb20">
                        <p class="color-gray mb0">#Number Of Trips</p>
                        <span class="font-14 line-height20"><b><?= $model->drv_total_trips; ?></b></span>
                    </div>
                    <div class="col-xs-12 col-md-4 mb20">
                        <p class="color-gray mb0">Current Rating:</p>
                        <span class="font-14 line-height20"><b><?= $model->drv_overall_rating; ?></b></span>
                    </div>
                    <div class="col-xs-12 col-md-4 mb20">
                        <p class="color-gray mb0">Last Trip Date:</p>
                        <span class="font-14 line-height20">
                            <b>
                                <?php
						if ($data['last_pickup_date'] != NULL)
						{
							echo date('d/m/Y h:i A', strtotime($data['last_pickup_date']));
						}
						?> 
                            </b>
                        </span>
                    </div>
                    <div class="col-xs-12 col-md-4 mb20">
                        <p class="color-gray mb0">Last Trip Rating:</p>
                        <span class="font-16 line-height20"><b><?= $data['rtg_customer_driver']; ?></b></span>
                    </div>
                    <div class="col-xs-12 col-md-4 mb20">
                        <p class="color-gray mb0">On-Time:</p>
                        <span class="font-14 line-height20">
                            <b>
                                <?php
						if ($data['rtg_driver_ontime'] <> NULL)
						{
							echo ($data['rtg_driver_ontime'] > 0) ? 'Yes' : 'No';
						}
						?>
                            </b>
                        </span>
                    </div>
                    <div class="col-xs-12 col-md-4 mb20">
                        <p class="color-gray mb0">Soft Spoken:</p>
                        <span class="font-14 line-height20">
                            <b>
                                <?php
						if ($data['rtg_driver_softspokon'] <> NULL)
						{
							echo ($data['rtg_driver_softspokon'] > 0) ? 'Yes' : 'No';
						}
						?>
                            </b>
                        </span>
                    </div>
                    <div class="col-xs-12 col-md-4 mb20">
                        <p class="color-gray mb0">Respectfully Dressed:</p>
                        <span class="font-14 line-height20">
                            <b>
                                <?php
						if ($data['rtg_driver_respectfully'] <> NULL)
						{
							echo ($data['rtg_driver_respectfully'] > 0) ? 'Yes' : 'No';
						}
						?>
                            </b>
                        </span>
                    </div>
                    <div class="col-xs-12 col-md-4 mb20">
                        <p class="color-gray mb0">Safe Driver:</p>
                        <span class="font-14 line-height20">
                            <b>
                                <?php
						if ($data['rtg_driver_safely'] <> NULL)
						{
							echo ($data['rtg_driver_safely'] > 0) ? 'Yes' : 'No';
						}?>
                            </b>
                        </span>
                    </div>
                    <div class="col-xs-12 col-md-4 mb20">
                        <p class="color-gray mb0">Trip Types:</p>
                        <span class="font-14 line-height20">
                            <b>
                                <?php
						if ($data['drv_trip_type'] <> NULL)
						{
							echo ($data['drv_trip_type'] != 0) ? Drivers::getType($data['drv_trip_type']) : '';
						}
						?>
                            </b>
                        </span>
                    </div>
            </div>
        </div>
    </div>
</div>
</div>