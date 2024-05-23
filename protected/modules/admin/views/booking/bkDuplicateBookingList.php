<style>
	.dup-success{
		text-align: center;
		font-size: 18px;
		color: #73b573;
	}
	.dup-error{
		text-align: center;
		font-size: 18px;
		color: #f14343;
	}
	.dup-text{
		font-size: 18px;
	}
</style>
<div class="panel-advancedoptions" >
    <div class="row">
        <div class="col-xs-12"> 
            <div class="panel" >

                <div class="panel-body panel-body panel-no-padding">
                    <div class="panel-scroll">
                      <?php if(count($bkgIds) > 0){ ?>
						<div class="row" id="dup_booking_list" >
                            <div class="col-xs-12">
								<p class="dup-success">Bookings created successfully</p>
                            </div>
							<div class="col-xs-12">
								<div class="row">
									<?php
									if ($bkgIds)
									{
										foreach ($bkgIds as $key => $bkgId)
										{
											?>
											<div class="col-md-4">
												<a href="<?= $urls[$key] ?>" target="_blank"><?= $bkgId; ?></a><br/>
											</div>
										<?php
										}
									}
									?>
								</div>
							</div>
                        </div>
					   <?php } 
						if(count($errors) > 0){ ?>
						<div class="row" id="dup_booking_list" >
                            <div class="col-xs-12">
								<p class="dup-error">Errors occurs</p>
                            </div>
							<div class="col-xs-12">
								<?= json_encode($errors) ?>
							</div>
                        </div>
					   <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
