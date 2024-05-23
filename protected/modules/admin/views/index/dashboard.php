
<div id="content">
    <div class="mainBody">
        <div class="icopPart ">
            <div class="row" style="margin: auto">
                <div class="col-xs-12 col-md-10 mt50 text-center col-md-offset-1">
                    <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
                        <div class="thumbnail tile tile-medium tile-orange">
                            <a href="<?php echo Yii::app()->createUrl('admin/user/list') ?>" class="fa-links">
                                <h1>Users</h1>
                                <i class="fa fa-3x fa-user"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
                        <div class="thumbnail tile tile-medium tile-blue">
                            <a href="<?php echo Yii::app()->createUrl('admin/booking/list') ?>" class="fa-links">
                                <h1>Bookings</h1>
                                <i class="fa fa-3x fa-car"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
                        <div class="thumbnail tile tile-medium tile-green">
                            <a href="<?php echo Yii::app()->createUrl('admin/vehicle/list') ?>" class="fa-links">
                                <h1>Vehicles</h1>
                                <i class="fa fa-3x fa-cab"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
                        <div class="thumbnail tile tile-medium tile-wisteria">
                            <a href="<?php echo Yii::app()->createUrl('admin/driver/list') ?>" class="fa-links">
                                <h1>Drivers</h1>
                                <i class="fa fa-3x fa-wheelchair"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
                        <div class="thumbnail tile tile-medium tile-wet-asphalt">
                            <a href="<?php echo Yii::app()->createUrl('admin/route/list') ?>" class="fa-links">
                                <h1>Routes</h1>
                                <i class="fa fa-3x fa-map-marker"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
                        <div class="thumbnail tile tile-medium tile-turquoise">
                            <a href="<?php echo Yii::app()->createUrl('admin/rate/list') ?>" class="fa-links">
                                <h1>Rates</h1>
                                <i class="fa fa-3x fa-inr"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
                        <div class="thumbnail tile tile-medium tile-red">
                            <a href="<?php echo Yii::app()->createUrl('admin/vendor/list') ?>" class="fa-links">
                                <h1>Vendors</h1>
                                <i class="fa fa-3x fa-briefcase"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
                        <div class="thumbnail tile tile-medium tile-sun-flower">
                            <a href="<?php echo Yii::app()->createUrl('admin/message/list') ?>" class="fa-links">
                                <h1>SMS</h1>
                                <i class="fa fa-3x fa-envelope"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
                        <div class="thumbnail tile tile-medium tile-lime">
                            <a href="<?php echo Yii::app()->createUrl('admin/report/booking') ?>" class="fa-links">
                                <h1>Reports</h1>
                                <i class="fa fa-3x fa-pie-chart"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
                        <div class="thumbnail tile tile-medium tile-emerald">
                            <a href="<?php echo Yii::app()->createUrl('admin/promo/list') ?>" class="fa-links">
                                <h1>Promotions</h1>
                                <i class="fa fa-3x fa-diamond"></i>
                            </a>
                        </div>
                    </div>
					<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
                        <div class="thumbnail tile tile-medium tile-blue">
                            <a href="<?php echo Yii::app()->createUrl('admin/vendor/vendoraccounts') ?>" class="fa-links">
                                <h1>Accounts</h1>
                                <i class="fa fa-3x fa-calculator"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
						<div class="thumbnail tile tile-medium tile-green">
                            <a href="<?php echo Yii::app()->createUrl('admin/vehicle/approvelist') ?>" class="fa-links">
                                <h1>Vehicle Approve</h1>
                                <i class="fa fa-3x fa-check-circle"></i>
                            </a>
                        </div>
                    </div>

					<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">

						<div class="thumbnail tile tile-medium tile-wisteria">
                            <a href="<?php echo Yii::app()->createUrl('admin/driver/approvelist') ?>" class="fa-links">
                                <h1>Driver Approve</h1>
                                <i class="fa fa-3x fa-check-circle"></i>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="clr"></div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        var front_end_height = parseInt($(window).outerHeight(true));
        var footer_height = parseInt($("#footer").outerHeight(true));
        var header_height = parseInt($("#header").outerHeight(true));
        var ch = (front_end_height - (header_height + footer_height + 23));
        //console.log("wH: "+front_end_height+" HH : "+header_height+" FH: "+footer_height+"CH :"+ch);
        $("#content").attr("style", "height:" + ch + "px;");
    });
</script>
