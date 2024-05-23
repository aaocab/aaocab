<?php
$agentId = \Yii::app()->request->cookies['gozo_agent_id']->value;
if (Yii::app()->user->isGuest)
{
	$model = new Users('login');
		?>
		<ul class="nav navbar-nav float-right d-flex align-items-center ml-auto widget-menu">
			<li class="dropdown nav-search nav-item">
				<?php 
					if($this->pageRequest->booking->agentId != Config::get('Mobisign.partner.id') && (($this->pageRequest->booking->agentId || $agentId) != Config::get('Kayak.partner.id')))
					{
				?>
				<a class="nav-link nav-link-search p10 requestcall" href="javascript:void(0);" data-toggle="dropdown" data-target=".dropdown-menu-left">
					<div class="user-nav d-lg-flex mr-2 hidden-xs hidden-sm"><button type="button" class="btn btn-primary hvr-push"><img src="/images/bxs-phone.png" alt="img" width="16" height="16"> Request a call</button></div>
					<img src="/images/bx-phone-call.svg" alt="img" width="22" height="22" class="d-block d-sm-none color-black">
				</a>
					<?php } ?>
				<div class="dropdown-menu dropdown-menu-right">
					<a data-toggle="ajaxModal" id="newbook" rel="popover" data-placement="left" class="dropdown-item" title="New Booking" onClick="return reqCMB(1)" href="<?= Yii::app()->createUrl("scq/newBookingCallBack", array("reftype" => 1)) ?>"><img src="/images/bx-car.png" alt="img" width="14" height="14" class="mr10">New Booking</a>
					<a data-toggle="ajaxModal" id="exisbook" rel="popover" data-placement="left" class="dropdown-item" title="New Booking" onClick="return reqCMB(2)" href="<?= Yii::app()->createUrl("scq/existingBookingCallBack", array("reftype" => 2)) ?>"><img src="/images/bx-envelope.svg" alt="img" width="14" height="14" class="mr10">Existing Booking</a>
					<div class="divider m-0">
						<div class="divider-text">Call us at</div>
					</div>
					<a id="phonecall" rel="popover" data-placement="left" class="dropdown-item" href="tel:+919051877000"><img src="/images/bxs-phone2.svg" alt="img" width="16" height="16" class="mr10">+91 90518-77000</a>
				</div>
			</li>
			<?php
			if (!Yii::app()->user->isGuest)
			{
				$uname = Yii::app()->user->loadUser()->usr_name;
				?>
				<li>
					<a href="#" onclick="$('.aside').asidebar('open')" class="pt-1 pr-1 hidden-lg hidden-md" style="color: #475F7B; display: inline;"><i class="bx bx-menu font-24"></i></a>
					<div class="aside">
						<div class="aside-header">
							<span class="close font-30" data-dismiss="aside" aria-hidden="true">&times;</span>
						</div>
						<div class="aside-contents">
							<ul>
								<li><a href="/users/view"><img src="/images/bx-user2.svg" alt="Profile" width="14" height="14" class="mr10"> Profile</a></li>
								<li><a href="/users/creditlist"><img src="/images/bxl-creative-commons.svg" alt="Accounts details" width="14" height="14" class="mr10"> Accounts details</a></li>
								<li><a href="/booking/list"><img src="/images/bx-spreadsheet.png" alt="My bookings" width="14" height="14" class="mr10"> My bookings</a></li>
								<li><a href="/users/refer"><img src="/images/bx-user-plus.png" alt="Refer friends" width="14" height="14" class="mr10"> Refer friends</a></li>
								<li><a href="/users/changePassword"><img src="/images/bx-lock-alt.svg" alt="Change password" width="14" height="14" class="mr10"> Change password</a></li>
								<li><a href="<?= Yii::app()->createUrl('users/logoutv3') ?>"><img src="/images/bx-log-out-circle.png" alt="Logout" width="14" height="14" class="mr10"> Logout</a></li>
							</ul>
						</div>
					</div>
					<div class="aside-backdrop"></div>
				</li>
				<?php
			}
			?>
			<li class="dropdown dropdown-user nav-item pr10">
				<?php 
					if($this->pageRequest->booking->agentId != Config::get('Mobisign.partner.id') && (($this->pageRequest->booking->agentId || $agentId) != Config::get('Kayak.partner.id')))
					{
				?>
				<a href="javascript:void(0)" onclick="$skipLogin = 2; return showLogin();" role="button"  class="color-black requestcall"><img src="/images/bxs-user3.png" alt="img" width="34" height="34" class="bx-border-circle"></a>
				<?php } ?>
				<a href="#" class="sinUpPopUp"  id="sinUpPopUp" class="dropdown-toggle pr0 gradient-green-blue btn-sign mt0" data-toggle="dropdown" role="button"
				   aria-haspopup="true" aria-expanded="false"  class="btn btn-primary mr-1 mb-1 mt-1" style="display:none">Sign up</a>
			</li>

		</ul>
		<?php
}
else
{
	$uname = Yii::app()->user->loadUser()->usr_name;
	$coinhtml	 = "";
	$uname		 = Yii::app()->user->loadUser()->usr_name;
	$coin		 = UserCredits::model()->getUserCoin(Yii::app()->user->getId());
	if ($coin > 0)
	{
		$coinhtml = '&nbsp;'.'  <img src="/images/img-2022/gozo_coin.svg?v=0.2" alt="Gozo Coin" width="14"> '.'&nbsp;'. $coin;
	}

	
	if($this->pageRequest->booking->agentId != Config::get('Mobisign.partner.id') && (($this->pageRequest->booking->agentId || $agentId) != Config::get('Kayak.partner.id')))
	{

			if(Yii::app()->user->getId() > 0)
			 {
            $contactData = ContactProfile::getEntitybyUserId(Yii::app()->user->getId());
            $contactID   = $contactData['cr_contact_id'];
            $data        = Contact::getByPerson($contactID);
            if ($data['contact']['ctt_user_type'] == 1)
            {
                $name = $data['contact']['ctt_first_name'];
            }
            else
            {
                $name = $data['contact']['ctt_business_name'];
            }

            $rowUcm = UserCategoryMaster::getByUserId(Yii::app()->user->getId());
            if ($rowUcm['ucm_id'] != '')
            {
                $catCss = UserCategoryMaster::getColorByid($rowUcm['ucm_id']);
            }
        }
	?>
	<ul class="nav navbar-nav float-right d-flex align-items-center ml-auto widget-menu" >
		<li class="dropdown nav-search nav-item">
			<a class="nav-link nav-link-search p10" href="javascript:void(0);" data-toggle="dropdown" data-target=".dropdown-menu-left">
				<div class="user-nav d-lg-flex hidden-xs hidden-sm"><button type="button" class="btn btn-primary hvr-push"><img src="/images/bxs-phone.png" alt="img" width="16" height="16"> Request a call</button></div>
				<img src="/images/bx-phone-call.svg" alt="img" width="22" height="22" class="d-block d-sm-none color-black">
			</a>
			 <div class="dropdown-menu dropdown-menu-left space-right">
				<a data-toggle="ajaxModal" id="newbook" rel="popover" data-placement="left" class="dropdown-item" title="New Booking" onClick="return reqCMB(1)" href="<?= Yii::app()->createUrl("scq/newBookingCallBack", array("reftype" => 1)) ?>"><img src="/images/bx-car.png" alt="img" width="14" height="14" class="mr10">New Booking</a>
				<a data-toggle="ajaxModal" id="exisbook" rel="popover" data-placement="left" class="dropdown-item" title="New Booking" onClick="return reqCMB(2)" href="<?= Yii::app()->createUrl("scq/existingBookingCallBack", array("reftype" => 2)) ?>"><img src="/images/bx-envelope.svg" alt="img" width="14" height="14" class="mr10">Existing Booking</a>
				<div class="divider m-0">
					<div class="divider-text">Call us at</div>
				</div>
				<a id="phonecall" rel="popover" data-placement="left" class="dropdown-item" href="tel:+919051877000"><img src="/images/bxs-phone2.svg" alt="img" width="16" height="16" class="mr10">+91 90518-77000</a>
			</div>
		</li>

		<li class="dropdown dropdown-user nav-item"><a class="dropdown-toggle nav-link dropdown-user-link p-0 color-blue" href="javascript:void(0);" data-toggle="dropdown"  data-target=".dropdown-menu-right">
				<div class="user-nav d-lg-flex d-none mt-1"><button type="button" class="btn btn-outline-primary mb-1">Hi <?php echo $name;//$uname ;?></button></div>
				<img src="/images/bx-menu.svg" alt="img" width="40" height="40" class="pr-1 d-xl-none d-lg-none d-xxl-block">
			</a>
			<div class="dropdown-menu dropdown-menu-right pb-0" style="z-index: 99999;">
				<?php  if($rowUcm['ucm_id']!=''){ echo '<div class="user-categoty">'."<img src='/images/{$catCss}' alt='' width='25' title='{$rowUcm['ucm_label']}'>".'</div>'; }?>
				<a class="dropdown-item" href="/users/view"><img src="/images/bx-user2.png" alt="Profile" width="16" height="16" class="mr10"> Profile <?= $coinhtml ?></a>
				<a class="dropdown-item" href="/users/refer"><img src="/images/bx-user-plus.png" alt="Refer friends" width="16" height="16" class="mr10"> Refer friends</a>
				<a class="dropdown-item" href="/index/index"><img src="/images/bx-car.png" alt="New booking" width="16" height="16" class="mr10"> New booking</a>
				<a class="dropdown-item" href="/booking/list"><img src="/images/bx-spreadsheet.svg" alt="My bookings" width="16" height="16" class="mr10"> My bookings</a>
				<a class="dropdown-item" href="/users/creditlist"><img src="/images/bxl-creative-commons.png" alt="Accounts details" width="16" height="16" class="mr10"> Payments</a>
				<a class="dropdown-item" href="/place/view"><img src="/images/bx-directions.png" alt="Favourite places" width="16" height="16" class="mr10"> Favourite places</a>
				<a class="dropdown-item" href="/users/changePassword"><img src="/images/bx-lock-alt.png" alt="Change password" width="16" height="16" class="mr10"> Change password</a>
				<a class="dropdown-item" href="<?= Yii::app()->createUrl('users/logoutv3') ?>"><img src="/images/bx-log-out-circle.png" alt="Log out" width="16" height="16" class="mr10"> Log out</a>
			</div>
		</li>

	</ul>
	<?php
		}
	}
	?>
	

