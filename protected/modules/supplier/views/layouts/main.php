<?php
Yii::app()->clientScript->registerPackage("webSupplier");
//Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/enquire.js', CClientScript::POS_HEAD);
//Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/jquery.cookie.js', CClientScript::POS_HEAD);
//Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/jquery.nicescroll.min.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/moment.min.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/daterangepicker.js', CClientScript::POS_HEAD);

$vndModel = Vendors::model()->findByPk(Yii::app()->user->getEntityID());
$pName = $vndModel->vnd_name;
if(Yii::app()->user->id > 0)
{
	$refContactData = ContactProfile::getEntitybyUserId(Yii::app()->user->id);
	$contactData	 = ContactProfile::getPrimaryEntitiesByContact($refContactData['ctt_id']);
	$userProfile = new \Beans\common\UserSession();
	$userProfile->setProfileV1($contactData);
	$pName = $userProfile->contact->firstName." ".$userProfile->contact->lastName." (".$userProfile->vendor->code."/".$userProfile->driver->code.")";
}
$cntBookings = BookingSub::getCountBookingsByVendor(Yii::app()->user->getEntityID());
$filterModel = new stdClass();
$filterModel->pickupDateRange->fromDate	 = date('Y-m-d');
$filterModel->pickupDateRange->toDate	 = date('Y-m-d', strtotime('+1 month'));
$totNewBookings = BookingVendorRequest::getPendingRequestV2(Yii::app()->user->getEntityID(),-1,$filterModel,null,true,true);

$filterModel->agent_id = AgentVendorRelation::getByVnd(Yii::app()->user->getEntityID());
$totNewPartnerBookings = BookingVendorRequest::getPendingRequestV2(Yii::app()->user->getEntityID(),-1,$filterModel,null,true,true);

$countCabs	 = Vehicles::getCabListByVendor(Yii::app()->user->getEntityID(), $searchTxt, true, true);
$countDrivers = Drivers::getAllLstByVendor(Yii::app()->user->getEntityID(), '', true, true);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <link href="<?= ASSETS_URL ?>/css/daterangepicker.css" rel="stylesheet" type="text/css"/>
</head>
	<script>
        function ajaxindicatorstart(text)
        {
            if (jQuery('body').find('#resultLoading').attr('id') != 'resultLoading')
            {
                jQuery('body').append('<div id="resultLoading" style="display:none"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/ajax-loader1.gif"><div>' + text + '</div></div><div class="bg"></div></div>');
            }

            jQuery('#resultLoading').css({
                'width': '100%',
                'height': '100%',
                'position': 'fixed',
                'z-index': '10000000',
                'top': '0',
                'left': '0',
                'right': '0',
                'bottom': '0',
                'margin': 'auto'
            });

            jQuery('#resultLoading .bg').css({
                'background': '#ddd',
                'opacity': '0.6',
                'width': '100%',
                'height': '100%',
                'position': 'absolute',
                'top': '0'
            });

            jQuery('#resultLoading>div:first').css({
                'width': '250px',
                'height': '75px',
                'text-align': 'center',
                'position': 'fixed',
                'top': '0',
                'left': '0',
                'right': '0',
                'bottom': '0',
                'margin': 'auto',
                'font-size': '16px',
                'z-index': '10',
                'color': '#111'

            });

            jQuery('#resultLoading .bg').height('100%');
            jQuery('#resultLoading').fadeIn(100);
            jQuery('body').css('cursor', 'wait');
        }

        function ajaxindicatorstop()
        {
            jQuery('#resultLoading .bg').height('100%');
            jQuery('#resultLoading').fadeOut(100);
            jQuery('body').css('cursor', 'default');
        }
	</script>
	<body class="horizontal-layout horizontal-menu navbar-static 2-columns   footer-static  " data-open="hover" data-menu="horizontal-menu" data-col="2-columns">


		<!-- BEGIN: Header-->
		<nav class="header-navbar navbar-expand-lg navbar navbar-with-menu navbar-static-top bg-white navbar-brand-center">
			<div class="navbar-wrapper">
				<div class="navbar-container content">
					<div class="navbar-collapse" id="navbar-mobile">
						<div class="mr-auto float-left bookmark-wrapper d-flex align-items-center">
							<img src="<?= IMAGE_URL . '/supplier/gozo-partner.svg' ?>" alt="img" width="90" >

						</div>
						<ul class="nav navbar-nav float-right d-flex align-items-center">
							<li class="dropdown dropdown-user nav-item"><a class="dropdown-toggle nav-link dropdown-user-link" href="javascript:void(0);" data-toggle="dropdown">
									<div class="user-nav d-lg-flex d-none"><span class="user-name"><?=$pName?></span><span class="user-status color-green">Partner</span></div><span><img class="round" src="<?= IMAGE_URL . '/supplier/no-image.png' ?>" alt="avatar" height="40" width="40"></span>
								</a>
								<div class="dropdown-menu dropdown-menu-right pb-0">
<!--									<a class="dropdown-item" href="page-user-profile.html"><i class="bx bx-user mr-50"></i> My Profile</a>-->
<!--									<a class="dropdown-item" href="app-email.html"><i class='bx bx-rupee mr-50'></i> Account</a>
									<a class="dropdown-item" href="app-todo.html"><i class="bx bx-check-square mr-50"></i> My Social Account</a>
									<a class="dropdown-item" href="app-chat.html"><i class='bx bx-scan mr-50'></i> Permissions Scan</a>
									<a class="dropdown-item" href="app-chat.html"><i class='bx bx-note mr-50'></i> Destination Notes</a>
									<div class="dropdown-divider mb-0"></div>-->
									<a class="dropdown-item" href="<?= Yii::app()->createUrl('supplier/user/signout') ?>"><i class="bx bx-power-off mr-50"></i> Logout</a>
								</div>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</nav>


<div class="container-fluid">
			<div class="row">
				<div class="col-12 side-panel-bar p0 ">
					<nav class="navbar-sticky">
						<input type="checkbox" id="check">
						<label for="check" class="checkbtn">
							<i class='bx bx-menu font-24'></i>
						</label>
						<ul>
							<li><a class="<?php echo ($_SERVER['REQUEST_URI'] == '/supplier/user/dashboard') ? 'active' : ''; ?>" href="<?= Yii::app()->createUrl('supplier/user/dashboard') ?>" ><span >Dashboard</span></a>
							</li>
							<li><a class="<?php echo ($_SERVER['REQUEST_URI'] == '/supplier/booking/list') ? 'active' : ''; ?>" href="<?= Yii::app()->createUrl('supplier/booking/list') ?>" ><span >All Allocated Booking (<span id="cntAllBooking"><?=$cntBookings['tot']?></span>)</span></a>
							</li>
							<li><a class="<?php echo ($_REQUEST['status'] == 'new') ? 'active' : ''; ?>" href="<?= Yii::app()->createUrl('supplier/booking/list?status=new') ?>" ><span >New Request (<span id="cntNewBooking"><?=$totNewBookings?></span>)</span></a>
							</li>
							<li><a class="<?php echo ($_REQUEST['status'] == 'partnernew') ? 'active' : ''; ?>" href="<?= Yii::app()->createUrl('supplier/booking/list?status=partnernew') ?>" ><span >My Partner Request (<span id="cntNewPartnerBooking"><?=$totNewPartnerBookings?></span>)</span></a>
							</li>
							<li><a class="<?php echo ($_REQUEST['status'] == 'assigned') ? 'active' : ''; ?>" href="<?= Yii::app()->createUrl('supplier/booking/list?status=assigned') ?>" ><span >Pending Assignment (<span id="cntAssignedBooking"><?=$cntBookings['assigned']?></span>)</span></a>
							</li>
							<li><a class="<?php echo ($_REQUEST['status'] == 'allocated') ? 'active' : ''; ?>" href="<?= Yii::app()->createUrl('supplier/booking/list?status=allocated') ?>" ><span >Upcoming/On Going Trips (<span id="cntAllocatedBooking"><?=$cntBookings['allocated']?></span>)</span></a>
							</li>
							<li><a class="<?php echo ($_REQUEST['status'] == 'completed') ? 'active' : ''; ?>" href="<?= Yii::app()->createUrl('supplier/booking/list?status=completed') ?>" ><span>Completed (<span id="cntCompletedBooking"><?=$cntBookings['completed']?></span>)</span></a>
							</li>
							<li><a class="<?php echo ($_REQUEST['status'] == 'cancelled') ? 'active' : ''; ?>" href="<?= Yii::app()->createUrl('supplier/booking/list?status=cancelled') ?>" ><span>Cancelled (<span id="cntCancelledBooking"><?=$cntBookings['cancelled']?></span>)</span></a>
							</li>
							<li><a class="<?php echo ($_SERVER['REQUEST_URI'] == '/supplier/cab/list') ? 'active' : ''; ?>" href="<?= Yii::app()->createUrl('supplier/cab/list') ?>" ><span>My Cabs (<?=$countCabs?>)</span></a>
							</li>
							<li><a class="<?php echo ($_SERVER['REQUEST_URI'] == '/supplier/driver/list') ? 'active' : ''; ?>" href="<?= Yii::app()->createUrl('supplier/driver/list') ?>" ><span>My Drivers (<?=$countDrivers?>)</span></a></li>
		<!--					<li><a class="nav-link <?php echo ($_REQUEST['status'] == 70) ? 'active' : ''; ?>" href="<?= Yii::app()->createUrl('supplier/booking/managetrips') ?>" ><span data-i18n="Others">Manage Trips</span></a></li>-->
						</ul>
					</nav>
				</div>
			</div>
		</div>

		<?= $content; ?>

		<div class="sidenav-overlay"></div>
		<div class="drag-target"></div>

	</body>
    <footer class="footer footer-static footer-light text-center">
        <p class="clearfix mb-0">Copyright © 2024 by Gozocabs. All Rights Reserved.</p>
    </footer>
</html>
