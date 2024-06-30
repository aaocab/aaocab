
<div id="content">
    <div class="mainBody">
        <div class="icopPart ">
            <div class="row" style="margin: auto">
                <div class="col-xs-12 col-sm-4 col-md-3">
                    <div class="panel panel-default"></div>
                </div>
                <div class="col-xs-12">   
<!--                    <div id="bookingSummary">
						<div class="row">
							<div class="col-lg-2  col-md-4 col-sm-6" style="min-height: 172px">
								<div class="panel info-box panel-white">
									<a href="<?= Yii::app()->createAbsoluteUrl('rcsr/booking/list', ['source' => 201]) ?>" target="_blank"> 
										<div class="panel-body"><div class="info-box-stats">
												<p class="counter"><?= $countMissingDrivers; ?></p>
												<span class="info-box-title">Bookings with missing drivers (36 Hours)</span>
											</div></div>
									</a>
								</div>
							</div>                        
							<div class="col-lg-2  col-md-4 col-sm-6" style="min-height: 172px">
								<div class="panel info-box panel-white">
									<a href="<?= Yii::app()->createAbsoluteUrl('rcsr/booking/list', ['source' => 202]) ?>" target="_blank">
										<div class="panel-body"><div class="info-box-stats">                                        
												<p class="counter"><?= $countUnassignedVendors ?>
												</p> <span class = "info-box-title">Bookings still unassigned (next 48 Hours)</span></div>
										</div>
									</a></div>
							</div>                        
							<div class="col-lg-2  col-md-4 col-sm-6" style="min-height: 172px">
								<div class = "panel info-box panel-white">
									<a href = "<?= Yii::app()->createAbsoluteUrl('rcsr/booking/list', ['source' => 203]) ?>" target="_blank">
										<div class="panel-body"><div class = "info-box-stats"> 
												<p class = "counter"><?= $countTripVendors ?></p> 
												<span class = "info-box-title">Low rating vendor for upcoming trip</span>
											</div></div>
									</a>
								</div></div>                        
							<div class="col-lg-2  col-md-4 col-sm-6" style="min-height: 172px">
								<div class = "panel info-box panel-white">
									<a href = "<?= Yii::app()->createAbsoluteUrl('rcsr/booking/list', ['source' => 204]) ?>" target = "_blank">
										<div class = "panel-body"><div class = "info-box-stats"> 
												<p class = "counter"><?= $countTripDrivers ?></p> 
												<span class = "info-box-title">Low rating driver for upcoming trip</span>
											</div></div>
									</a>
								</div></div>
							<div class="col-lg-2  col-md-4 col-sm-6" style="min-height: 172px">
								<div class="panel info-box panel-white">
									<a href="<?= Yii::app()->createAbsoluteUrl('rcsr/booking/list', ['source' => 205]) ?>" target="_blank">
										<div class="panel-body"><div class="info-box-stats">                                        
												<p class="counter"><?= $countAccountsFlag; ?></p> <span class = "info-box-title">Bookings need Accounts attention</span>
											</div></div>
									</a>
								</div>
							</div>
							<div class="col-lg-2  col-md-4 col-sm-6" style="min-height: 172px">
								<div class="panel info-box panel-white">
									<a href="<?= Yii::app()->createAbsoluteUrl('rcsr/booking/list', ['source' => 206]) ?>" target="_blank">
										<div class="panel-body"><div class="info-box-stats">                                        
												<p class="counter"><?= $countUnverifiedLeeds; ?></p> <span class = "info-box-title">Leads need converting</span>
											</div></div>
									</a></div>
							</div>
							<div class="col-lg-2  col-md-4 col-sm-6" style="min-height: 172px">
								<div class="panel info-box panel-white">
									<a href="<?= Yii::app()->createAbsoluteUrl('rcsr/booking/list', ['source' => 207]) ?>" target="_blank">
										<div class="panel-body"><div class="info-box-stats">                                        
												<p class="counter"><?= $countMissingCarsDoc; ?></p> <span class = "info-box-title">Cars missing docs</span>
											</div></div>
									</a></div>
							</div>
							<div class="col-lg-2  col-md-4 col-sm-6" style="min-height: 172px">
								<div class="panel info-box panel-white">
									<a href="<?= Yii::app()->createAbsoluteUrl('rcsr/booking/list', ['source' => 208]) ?>" target="_blank">
										<div class="panel-body"><div class="info-box-stats">                                        
												<p class="counter"><?= $countMissingDriversDoc; ?></p> <span class = "info-box-title">Drivers missing docs</span>
											</div></div>
									</a></div>
							</div>                        
							<div class="col-lg-2  col-md-4 col-sm-6" style="min-height: 172px">
								<div class="panel info-box panel-white">
									<a href="<?= Yii::app()->createAbsoluteUrl('rcsr/booking/list', ['source' => 209]) ?>" target="_blank">
										<div class="panel-body"><div class="info-box-stats">                                        
												<p class="counter"><?= $countActiveEscalations; ?></p> <span class = "info-box-title">Active escalations</span>
											</div></div>
									</a></div>
							</div>                        
							<div class="col-lg-2  col-md-4 col-sm-6" style="min-height: 172px">
								<div class="panel info-box panel-white">
									<a href="<?= Yii::app()->createAbsoluteUrl('rcsr/vendor/list', ['source' => 210]) ?>" target="_blank">
										<div class="panel-body"><div class="info-box-stats">                                        
												<p class="counter"><?= $countVendorDocMissing; ?></p> <span class = "info-box-title">Vendors with doc missing (in system)</span>
											</div></div>
									</a></div>
							</div>                        
							<div class="col-lg-2  col-md-4 col-sm-6" style="min-height: 172px">
								<div class="panel info-box panel-white">
									<a href="<?= Yii::app()->createAbsoluteUrl('rcsr/vendor/list', ['source' => 211]) ?>" target="_blank">
										<div class="panel-body"><div class="info-box-stats">                                        
												<p class="counter"><?= $countVendorBankMissing; ?></p> <span class = "info-box-title">Vendors with bank details and/or PAN missing (in system)</span>
											</div></div>
									</a></div>
							</div>                        
							<div class="col-lg-2  col-md-4 col-sm-6" style="min-height: 172px">
								<div class="panel info-box panel-white">
									<a href="<?= Yii::app()->createAbsoluteUrl('rcsr/booking/list', ['source' => 215]) ?>" target="_blank">
										<div class="panel-body"><div class="info-box-stats">                                        
												<p class="counter"><?= $countUndocumentNonCommercial; ?></p> <span class = "info-box-title">Undocumented cars in next 48 hours (not commercial)</span>
											</div></div>
									</a></div>
							</div>                        
							<div class="col-lg-2  col-md-4 col-sm-6" style="min-height: 172px">
								<div class="panel info-box panel-white">
									<a href="<?= Yii::app()->createAbsoluteUrl('rcsr/booking/list', ['source' => 216]) ?>" target="_blank">
										<div class="panel-body"><div class="info-box-stats">                                       
												<p class="counter"><?= $countUndocumentCommercial; ?></p> <span class = "info-box-title">Undocumented cars in next 48 hours (commercial verified, but not approved)</span>
											</div></div>
									</a></div>
							</div>                        
							<div class="col-lg-2  col-md-4 col-sm-6" style="min-height: 172px">
								<div class="panel info-box panel-white">
									<a href="<?= Yii::app()->createAbsoluteUrl('rcsr/booking/list', ['source' => 218]) ?>" target="_blank">
										<div class="panel-body"><div class="info-box-stats">                                        
												<p class="counter"><?= $countVendorFloating24hrs; ?></p> <span class = "info-box-title">Bookings not picked up by any vendor despite floating for 24hours</span>
											</div></div>
									</a></div>
							</div>                        
							<div class="col-lg-2  col-md-4 col-sm-6" style="min-height: 172px">
								<div class="panel info-box panel-white">
									<a href="<?= Yii::app()->createAbsoluteUrl('rcsr/booking/match') ?>" target="_blank">
										<div class="panel-body"><div class="info-box-stats">                                        
												<p class="counter"><?= $countMatchList; ?></p> <span class = "info-box-title">Bookings need smart matching attention</span>
											</div></div>
									</a></div>
							</div>                        
							<div class="col-lg-2  col-md-4 col-sm-6" style="min-height: 172px">
								<div class="panel info-box panel-white">
									<a href="<?= Yii::app()->createAbsoluteUrl('rcsr/booking/list', ['source' => 217]) ?>" target="_blank">
										<div class="panel-body"><div class="info-box-stats">                                        
												<p class="counter"><?= $countVendorUnassigned5days; ?></p> <span class = "info-box-title">Bookings created > 2 days ago and not assigned still</span>
											</div></div>
									</a></div>
							</div>
                                                        <div class="col-lg-2  col-md-4 col-sm-6" style="min-height: 172px">
                                                            <div class="panel info-box panel-white">
                                                                <a href="<?= Yii::app()->createAbsoluteUrl('rcsr/booking/list', ['source' => 219]) ?>" target="_blank">
                                                                    <div class="panel-body"><div class="info-box-stats">
                                                                            <p class="counter"><?= $countReconfirmPending; ?></p> <span class = "info-box-title">Bookings have  "Reconfirm Pending" in next 36hours</span>
                                                                        </div>
                                                                    </div>
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-2  col-md-4 col-sm-6" style="min-height: 172px">
                                                            <div class="panel info-box panel-white">
                                                                <a href="<?= Yii::app()->createAbsoluteUrl('rcsr/booking/list', ['source' => 220]) ?>" target="_blank">
                                                                    <div class="panel-body"><div class="info-box-stats">
                                                                            <p class="counter"><?= $countNonProfitable; ?></p> <span class = "info-box-title">Not profitable bookings in system</span>
                                                                        </div>
                                                                    </div>
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-2  col-md-4 col-sm-6" style="min-height: 172px">
                                                            <div class="panel info-box panel-white">
                                                                <a href="<?= Yii::app()->createAbsoluteUrl('rcsr/vendor/list', ['source' => 221]) ?>" target="_blank">
                                                                    <div class="panel-body"><div class="info-box-stats">
                                                                            <p class="counter"><?= $countVendorsForApproval; ?></p> <span class = "info-box-title">Vendors ready for approval</span>
                                                                        </div>
                                                                    </div>
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-2  col-md-4 col-sm-6" style="min-height: 172px">
                                                            <div class="panel info-box panel-white">
                                                                <a href="<?= Yii::app()->createAbsoluteUrl('rcsr/driver/list', ['source' => 222]) ?>" target="_blank">
                                                                    <div class="panel-body"><div class="info-box-stats">
                                                                            <p class="counter"><?= $countDriversForApproval; ?></p> <span class = "info-box-title">Drivers ready for approval</span>
                                                                        </div>
                                                                    </div>
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-2  col-md-4 col-sm-6" style="min-height: 172px">
                                                            <div class="panel info-box panel-white">
                                                                <a href="<?= Yii::app()->createAbsoluteUrl('rcsr/vehicle/list', ['source' => 223]) ?>" target="_blank">
                                                                    <div class="panel-body"><div class="info-box-stats">
                                                                            <p class="counter"><?= $countCarsForApproval; ?></p> <span class = "info-box-title">Cars ready for approval</span>
                                                                        </div>
                                                                    </div>
                                                                </a>
                                                            </div>
                                                        </div>
                                                        
                                                    
                                                    </div>
                                                </div>-->

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
            url: '/aaohome/index/dashboardnew',
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