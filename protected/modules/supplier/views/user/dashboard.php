
<?php 
$filterModel = new stdClass();
$filterModel->pickupDateRange->fromDate	 = date('Y-m-d');
$filterModel->pickupDateRange->toDate	 = date('Y-m-d', strtotime('+1 month'));
$totNewBookings = BookingVendorRequest::getPendingRequestV2(Yii::app()->user->getEntityID(),-1,$filterModel,null,true,true);

?>
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <section id="dashboard-analytics">
                    <div class="row d-flex justify-content-center">
                        <div class="col-xl-2 col-md-6 col-12 profit-report-card">
                            <div class="row">
                                <div class="col-12">
                                    <a href="<?= Yii::app()->createUrl('supplier/booking/list') ?>"><div class="card" style="min-height: 160px;">
                                        <div class="card-body d-flex justify-content-around mt20 mb20">
                                            <div class="d-inline-flex mr-xl-2" style="position: relative;">
                                                <div class="profit-content ml-50 mt-50">
                                                    <h2 class="mb-0 color-green"><?=$cntBookings['tot']?></h2>
                                                    <span class="color-black">All Allocated Booking</span>
                                                </div>
                                            <div class="resize-triggers"><div class="expand-trigger"><div style="width: 91px; height: 65px;"></div></div><div class="contract-trigger"></div></div></div>
                                            <div class="d-inline-flex" style="position: relative;">
                                                <div class="profit-content ml-50 mt-50">
                                                    <img src="images/icon-1.svg" alt="" width="60" >
                                                </div>
                                            <div class="resize-triggers"><div class="expand-trigger"><div style="width: 95px; height: 65px;"></div></div><div class="contract-trigger"></div></div></div>
                                        </div>
                                    </div>
									</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-md-6 col-12 profit-report-card">
                            <div class="row">
                                <div class="col-12">
                                    <a href="<?= Yii::app()->createUrl('supplier/booking/list?status=new') ?>"><div class="card" style="min-height: 160px;">
                                        <div class="card-body d-flex justify-content-around mt20 mb20">
                                            <div class="d-inline-flex mr-xl-2" style="position: relative;">
                                                <div class="profit-content ml-50 mt-50">
                                                    <h2 class="mb-0 color-green"><?=$totNewBookings?></h2>
                                                    <span class="color-black">New Request</span>
                                                </div>
                                            <div class="resize-triggers"><div class="expand-trigger"><div style="width: 91px; height: 65px;"></div></div><div class="contract-trigger"></div></div></div>
                                            <div class="d-inline-flex" style="position: relative;">
                                                <div class="profit-content ml-50 mt-50">
                                                    <img src="images/icon-1.svg" alt="" width="60" >
                                                </div>
                                            <div class="resize-triggers"><div class="expand-trigger"><div style="width: 95px; height: 65px;"></div></div><div class="contract-trigger"></div></div></div>
                                        </div>
                                    </div>
									</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-md-6 col-12 profit-report-card">
                            <div class="row">
                                <div class="col-12">
                                    <a href="<?= Yii::app()->createUrl('supplier/booking/list?status=assigned') ?>"><div class="card" style="min-height: 160px;">
                                        <div class="card-body d-flex justify-content-around mt20 mb20">
                                            <div class="d-inline-flex mr-xl-2" style="position: relative;">
                                                <div class="profit-content ml-50 mt-50">
                                                    <h2 class="mb-0 color-green"><?=$cntBookings['assigned']?></h2>
                                                    <span class="color-black">Pending Assignment</span>
                                                </div>
                                            <div class="resize-triggers"><div class="expand-trigger"><div style="width: 91px; height: 65px;"></div></div><div class="contract-trigger"></div></div></div>
                                            <div class="d-inline-flex" style="position: relative;">
                                                <div class="profit-content ml-50 mt-50">
                                                    <img src="images/icon-2.svg" alt="" width="60" >
                                                </div>
                                            <div class="resize-triggers"><div class="expand-trigger"><div style="width: 95px; height: 65px;"></div></div><div class="contract-trigger"></div></div></div>
                                        </div>
                                    </div></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-md-6 col-12 profit-report-card">
                            <div class="row">
                                <div class="col-12">
                                    <a href="<?= Yii::app()->createUrl('supplier/booking/list?status=allocated') ?>"><div class="card" style="min-height: 160px;">
                                        <div class="card-body d-flex justify-content-around mt20 mb20">
                                            <div class="d-inline-flex mr-xl-2" style="position: relative;">
                                                <div class="profit-content ml-50 mt-50">
                                                    <h2 class="mb-0 color-green"><?=$cntBookings['allocated']?></h2>
                                                    <span class="color-black">Upcoming/On Going Trips</span>
                                                </div>
                                            <div class="resize-triggers"><div class="expand-trigger"><div style="width: 91px; height: 65px;"></div></div><div class="contract-trigger"></div></div></div>
                                            <div class="d-inline-flex" style="position: relative;">
                                                <div class="profit-content ml-50 mt-50">
                                                    <img src="images/icon-2.svg" alt="" width="60" >
                                                </div>
                                            <div class="resize-triggers"><div class="expand-trigger"><div style="width: 95px; height: 65px;"></div></div><div class="contract-trigger"></div></div></div>
                                        </div>
                                    </div></a>
                                </div>
                            </div>
                        </div>
						 <div class="col-xl-2 col-md-6 col-12 profit-report-card">
                            <div class="row">
                                <div class="col-12">
                                    <a href="<?= Yii::app()->createUrl('supplier/booking/list?status=completed') ?>"><div class="card" style="min-height: 160px;">
                                        <div class="card-body d-flex justify-content-around mt20 mb20">
                                            <div class="d-inline-flex mr-xl-2" style="position: relative;">
                                                <div class="profit-content ml-50 mt-50">
                                                    <h2 class="mb-0 color-green"><?=$cntBookings['completed']?></h2>
                                                    <span class="color-black">Completed</span>
                                                </div>
                                            <div class="resize-triggers"><div class="expand-trigger"><div style="width: 91px; height: 65px;"></div></div><div class="contract-trigger"></div></div></div>
                                            <div class="d-inline-flex" style="position: relative;">
                                                <div class="profit-content ml-50 mt-50">
                                                    <img src="images/icon-2.svg" alt="" width="60" >
                                                </div>
                                            <div class="resize-triggers"><div class="expand-trigger"><div style="width: 95px; height: 65px;"></div></div><div class="contract-trigger"></div></div></div>
                                        </div>
                                    </div></a>
                                </div>
                            </div>
                        </div>
						<div class="col-xl-2 col-md-6 col-12 profit-report-card">
                            <div class="row">
                                <div class="col-12">
                                    <a href="<?= Yii::app()->createUrl('supplier/booking/list?status=cancelled') ?>"><div class="card" style="min-height: 160px;">
                                        <div class="card-body d-flex justify-content-around mt20 mb20">
                                            <div class="d-inline-flex mr-xl-2" style="position: relative;">
                                                <div class="profit-content ml-50 mt-50">
                                                    <h2 class="mb-0 color-green"><?=$cntBookings['cancelled']?></h2>
                                                    <span class="color-black">Cancelled</span>
                                                </div>
                                            <div class="resize-triggers"><div class="expand-trigger"><div style="width: 91px; height: 65px;"></div></div><div class="contract-trigger"></div></div></div>
                                            <div class="d-inline-flex" style="position: relative;">
                                                <div class="profit-content ml-50 mt-50">
                                                    <img src="images/icon-2.svg" alt="" width="60" >
                                                </div>
                                            <div class="resize-triggers"><div class="expand-trigger"><div style="width: 95px; height: 65px;"></div></div><div class="contract-trigger"></div></div></div>
                                        </div>
                                    </div></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    


