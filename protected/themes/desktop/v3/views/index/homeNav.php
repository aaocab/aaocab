<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/res/app-assets/js/asidebar.jquery.js", CClientScript::POS_HEAD);

$isContactVnd	 = 0;
$userId			 = UserInfo::getUserId();
if ($userId > 0)
{
	$umodel			 = Users::model()->findByPk($userId);
	$contactId		 = $umodel->usr_contact_id;
	$entityType		 = UserInfo::TYPE_VENDOR;
	$vnd			 = ContactProfile::getEntityById($contactId, $entityType);
	$isContactVnd	 = $vnd['id'];
}
?>

<nav class="horizontal-layout header-navbar navbar-expand-lg navbar navbar-with-menu navbar-static-top navbar-brand-center">
	<div class="navbar-wrapper ml-0">
		<div class="navbar-container content pl0">
			<div class="navbar-collapse" id="navbar-mobile">
			<?php  
				$agentId = \Yii::app()->request->cookies['gozo_agent_id']->value; 
				if($agentId != Config::get('Kayak.partner.id'))
				{
			?>
				<ul class="nav navbar-nav float-right d-flex align-items-center ml-auto" style="z-index: 99;">
					<li class="dropdown nav-search nav-item">
						<a class="nav-link nav-link-search p10" href="javascript:void(0);" data-toggle="dropdown">
							<div class="user-nav d-lg-flex hidden-xs hidden-sm"><button type="button" class="btn btn-primary hvr-push"><img id="phone" src="/images/img_trans.gif" alt="img" width="1" height="1"> Request a call</button></div>
								
								<img src="/images/img_trans.gif" alt="img" width="1" height="1" class="user-2 bx-border-circle  bx-sm d-block d-sm-none color-black">
						</a>
						<div class="dropdown-menu dropdown-menu-left pb20">
							<a data-toggle="ajaxModal" id="newbook" rel="popover" data-placement="left" class="dropdown-item" title="New Booking" onClick="return reqCMB(1)" href="<?= Yii::app()->createUrl("scq/newBookingCallBack", array("reftype" => 1)) ?>"><img src="/images/img_trans.gif" alt="New Booking" width="1" height="1" class="newBooking-1 mr10" >New Booking</a>
							<a data-toggle="ajaxModal" id="exisbook" rel="popover" data-placement="left" class="dropdown-item" title="New Booking" onClick="return reqCMB(2)" href="<?= Yii::app()->createUrl("scq/existingBookingCallBack", array("reftype" => 2)) ?>"><img src="/images/img_trans.gif" alt="Existing Booking" width="1" height="1" class="envelope-1 mr10" >Existing Booking</a>
							<?php
							if ($isContactVnd === 0 || $isContactVnd > 0)
							{
								?>
								<a data-toggle="ajaxModal" id="vndhelp" rel="popover" data-placement="left" class="dropdown-item" title="New Booking" onClick="return reqCMB(4)" href="<?= Yii::app()->createUrl("scq/existingVendorCallBack", array("reftype" => 4)) ?>"><img src="/images/img_trans.gif" alt="Vendor Helpline" width="1" height="1" class="vendor-1 mr10">Vendor Helpline</a>
								<?php
							}
							if ($isContactVnd === 0 || $isContactVnd == null)
							{
								?>
								<a data-toggle="ajaxModal" id="attachtaxi" rel="popover" data-placement="left" class="dropdown-item" title="New Booking" onClick="return reqCMB(3)" href="<?= Yii::app()->createUrl("scq/vendorAttachmentCallBack", array("reftype" => 3)) ?>"><img src="/images/img_trans.gif" alt="Attach Your taxi" width="1" height="1" class="attach-1 mr10">Attach Your taxi</a>
							<?php } ?>
							<div class="divider mb10 ml10">
								<div class="divider-text">Call us at</div>
							</div>
							<a id="phonecall" rel="popover" data-placement="left" class="dropdown-item" style="display: inline" href="tel:+919051877000"><img src="/images/img_trans.gif" alt="Attach Your taxi" width="1" height="1" class="callus-1 mr10">+91 90518-77000</a>
							<a href="https://wa.me/918017279124?text=Hello,%20I%20need%20to%20book%20a%20cab" target=" _blank" class="dropdown-item"><img src="/images/img_trans.gif" alt="Attach Your taxi" width="1" height="1" class="whatsapp-1 mr10">Whatsapp</a>
						</div>
					</li>
				</ul>
			<?php	
				}
				if (!Yii::app()->user->isGuest)
				{

					$uname		 = Yii::app()->user->loadUser()->usr_name;
					$coinhtml	 = "";
					$uname		 = Yii::app()->user->loadUser()->usr_name;
					$coin		 = UserCredits::model()->getUserCoin(Yii::app()->user->getId());
					if ($coin > 0)
					{
						$coinhtml = '&nbsp;'.'  <img data-src="/images/img-2022/gozo_coin.svg?v=0.2" alt="Gozo Coin" class="lozad mr5" width="14"> '.'&nbsp;' . $coin;
					}


					if($agentId != Config::get('Kayak.partner.id'))
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
                
                
					$rowUcm =  UserCategoryMaster::getByUserId(Yii::app()->user->getId());
					 if($rowUcm['ucm_id']!='')
					 {
						 $catCss = UserCategoryMaster::getColorByid($rowUcm['ucm_id']);
					 }
			  }
?>

					<ul class="nav navbar-nav float-right align-items-center ml-auto widget-menu" style="display: contents;">
                        <li class="dropdown dropdown-user nav-item"><a class="dropdown-toggle nav-link dropdown-user-link p-0 color-blue" href="javascript:void(0);" data-toggle="dropdown"  data-target=".dropdown-menu-right">
								<div class="user-nav d-lg-flex d-none mt-1"><button type="button" class="btn btn-outline-primary mb-1">Hi <?php echo $name;//$uname; ?></button></div>
								<img src="/images/img_trans.gif" alt="Attach Your taxi" width="1" height="1" class="menubar-1 pr-1 d-xl-none d-lg-none d-xxl-block pl10 pt10">
							</a>
							<div class="dropdown-menu space-left dropdown-menu-right pb-0">
								<?php  if($rowUcm['ucm_id']!=''){ echo '<div class="user-categoty">'."<img src='/images/{$catCss}' alt='' width='25' title='{$rowUcm['ucm_label']}'>".'</div>'; }?>
								<a class="dropdown-item" href="/users/view"><img data-src="/images/bx-user2.png" alt="img" class="lozad mr5" width="16" height="16" class="mr10"> Profile <?= $coinhtml ?></a>
								<a class="dropdown-item" href="/users/refer"><img data-src="/images/bx-user-plus.png" alt="img" class="lozad mr5" width="16" height="16" class="mr10"> Refer friends</a>
								<a class="dropdown-item" href="/index/index"><img data-src="/images/bx-car.png" alt="img" class="lozad mr5" width="16" height="16" class="mr10"> New booking</a>
								<a class="dropdown-item" href="/booking/list"><img data-src="/images/bx-spreadsheet.png" alt="img" class="lozad mr5" width="16" height="16" class="mr10"> My bookings</a>
								<a class="dropdown-item" href="/users/creditlist"><img data-src="/images/bxl-creative-commons.png" class="lozad mr5" alt="img" width="16" height="16" class="mr10"> Payments</a>
								<a class="dropdown-item" href="/place/view"><img data-src="/images/bx-directions.png" alt="img" class="lozad mr5" width="16" height="16" class="mr10"> Favourite places</a>
								<a class="dropdown-item" href="/users/changePassword"><img data-src="/images/bx-lock-alt.png" alt="img" class="lozad mr5" width="16" height="16" class="mr10"> Change password</a>
								<a class="dropdown-item" href="<?= Yii::app()->createUrl('users/logoutv3') ?>"><img data-src="/images/bx-log-out-circle.png" alt="img" class="lozad mr5" width="16" height="16" class="mr10"> Log out</a>
							</div>
						</li>
					</ul>
					<?php
					}
				}
				else
				{
					if($agentId != Config::get('Kayak.partner.id'))
					{
					?>
					<div class="bookmark-wrapper d-flex align-items-center">
						<ul class="nav navbar-nav">
					<li class="dropdown dropdown-user nav-item p10">
						<a href="javascript:void(0)" role="button" onclick="return showLogin();"  class="color-black"><img class="user-1 bx-border-circle" src="/images/img_trans.gif" alt="img" width="1" height="1"></a>
					</li></ul></div>
					<?php
					}
				}
				?>
			</div>
		</div>
	</div>
</nav>
<?php
if(Yii::app()->request->isAjaxRequest)
{
?>
<script type="text/javascript">
$(document).ready(function()
			{

				lozad('.lozad', {
					load: function(el)
					{
						el.src = el.dataset.src;
						el.onload = function()
						{
							el.classList.add('fade-src');
						}
					}
				}).observe();
			});
</script>
	
	<?php	
}