<style>
.menu-list li{ line-height: 40px;}
.menu-list li span{ font-weight: 500;}
</style>
<?php
$isLoggedin = false;
$hide = 'hide';
$unhide = '';
$uname  =  "Guest";
if (!Yii::app()->user->isGuest)
{
	$isLoggedin = true;
	$hide = '';
	$unhide = 'hide';
	$uname		 = Yii::app()->user->loadUser()->usr_name;
}
?>
<div id="sidebar-right-over" data-selected="menu-components" data-subtitle="" class="menu-box menu-sidebar-right-over">
	<div class="menu-title">
<h1 class="loggiUser">Hi,&nbsp;<?=$uname?></h1>
		<a href="#" class="menu-hide pt20"><i><img src="/images/x-circle.svg" alt="arrow" width="32" height="32"></i></a>
	</div>
	<div class="menu-page">
		<ul class="menu-list">
			<li class="<?= $hide ?> menu-login">
				<a href="<?php echo Yii::app()->createUrl('/users/view'); ?>" class="default-link">
<!--				<i class='fas fa-user color-red-dark'></i>-->
					<span class="pl0">My Profile</span>
					<i class="pt10"><img src="/images/bx-arrowright.svg" alt="arrow" class="float-right" width="24" height="24"></i>
				</a>
			</li>
			<li class="<?= $hide ?> menu-login">
				<a href="<?php echo Yii::app()->createUrl('/booking/list'); ?>" class="default-link">
<!--				<i class='fas fa-list-alt color-red-dark'></i>-->
					<span class="pl0">Booking List</span>
					<i class="pt10"><img src="/images/bx-arrowright.svg" alt="arrow" class="float-right" width="24" height="24"></i>
				</a>
			</li> 
			 <!--<li class="<?= $hide ?> menu-login ">
				<a href="<?php echo Yii::app()->createUrl('/voucher/orderhistory'); ?>" class="default-link">
					<i class='fa fa-bars color-yellow-dark'></i>
					<span>My Order History</span>
					<em>My Order History</em>
					<i class="fa fa-angle-right"></i>
				</a>
			</li>-->
           <li class="<?= $hide ?> menu-login">
				<a href="<?php echo Yii::app()->createUrl('/users/creditlist'); ?>" class="default-link">
<!--				<i class='fas fa-history color-yellow-dark'></i>-->
					<span class="pl0">My Wallet</span>
					<i class="pt10"><img src="/images/bx-arrowright.svg" alt="arrow" class="float-right" width="24" height="24"></i>
				</a>
			</li>  
			<li class="<?= $hide ?> menu-login">
				<a href="<?php echo Yii::app()->createUrl('/users/redeemgiftcard'); ?>" class="default-link">
<!--				<i class='fa fa-gift color-yellow-dark'></i>-->
					<span class="pl0">Add Gift Card</span>
					<i class="pt10"><img src="/images/bx-arrowright.svg" alt="arrow" class="float-right" width="24" height="24"></i>
				</a>
			</li>
			<li class="<?= $hide ?> menu-login">
				<a href="<?php echo Yii::app()->createUrl('/users/refer'); ?>">
<!--				<i class='fas fa-users color-brown-light'></i>-->
					<span class="pl0">Refer friends</span>
					<i class="pt10"><img src="/images/bx-arrowright.svg" alt="arrow" class="float-right" width="24" height="24"></i>
				</a>
			</li> 
			<li class="<?= $hide ?> menu-login">
				<a href="<?php echo Yii::app()->createUrl('/place/view'); ?>" class="default-link">
<!--				<i class='fa fa-map-marker-alt color-orange-dark'></i>-->
					<span class="pl0">Favourite Places</span>
					<i class="pt10"><img src="/images/bx-arrowright.svg" alt="arrow" class="float-right" width="24" height="24"></i>
				</a>
			</li> 
			
			<li class="<?= $hide ?> menu-login">
				<a href="#" class='header-title default-link'>
<!--				<i class='fas fa-chalkboard-teacher color-magenta-dark'></i>-->
					<span class="pl0">New Booking</span>
					<i class="pt10"><img src="/images/bx-arrowright.svg" alt="arrow" class="float-right" width="24" height="24"></i>
				</a>
			</li>  
			
			<li class="<?= $hide ?> menu-login">
				<a href="<?php echo Yii::app()->createUrl('/users/Changepassword'); ?>" class="default-link">
<!--				<i class='fas fa-lock-open color-blue-dark'></i>-->
					<span class="pl0">Change password</span>
					<i class="pt10"><img src="/images/bx-arrowright.svg" alt="arrow" class="float-right" width="24" height="24"></i>
				</a>
			</li>
			<li class="<?= $unhide ?> menu-logout">
					<a href="<?php echo Yii::app()->createUrl('/signin'); ?>" class="default-link">
<!--						<i class='fas fa-sign-in-alt color-orange-dark'></i>-->
						<span class="pl0" id="signId">Sign In</span>
						<i class="pt10"><img src="/images/bx-arrowright.svg" alt="arrow" width="24" height="24" class="float-right"></i>
					</a>
			</li>
			<li class="<?= $unhide ?> menu-logout">					
				<a href="javascript:void(0);" class="sinUpModal" data-menu="modal-sign-Up">
<!--				<i class='fas fa-user-plus color-orange-dark'></i>-->
					<span class="pl0">Sign Up</span>
					<i class="pt10"><img src="/images/bx-arrowright.svg" alt="arrow" width="24" height="24" class="float-right"></i>
				</a>

			</li>
             <li>
				<a href="<?php echo Yii::app()->createUrl('/agent/join'); ?>" class="default-link">
<!--					<i class='fa fa-user-tie color-blue2-dark'></i>-->
					<span class="pl0">Become an Agent</span>
					<i class="pt10"><img src="/images/bx-arrowright.svg" alt="arrow" width="24" height="24" class="float-right"></i>
				</a>
			</li>    
			<li>
				<a href="<?php echo Yii::app()->createUrl('/vendor/join'); ?>" class="default-link">
<!--					<i class='fa fa-car-side color-red-dark'></i>-->
					<span class="pl0">Attach Your Taxi</span>
					<i class="pt10"><img src="/images/bx-arrowright.svg" alt="arrow" width="24" height="24" class="float-right"></i>
				</a>
			</li>  
			<li>
                <a href="/aboutus">
<!--					<i class="fas fa-user-alt color-green-dark default-link"></i>-->
					<span class="pl0">About Us</span>
					<i class="pt10"><img src="/images/bx-arrowright.svg" alt="arrow" width="24" height="24" class="float-right"></i>
				</a>
			</li> 
			<li id="menu-index">
				<a href="/contactus">
<!--					<i class="fas fa-map-marker-alt color-green-dark default-link"></i>-->
					<span class="pl0">Contact Us</span>
					<i class="pt10"><img src="/images/bx-arrowright.svg" alt="arrow" width="24" height="24" class="float-right"></i>
				</a>
            </li> 
			 <li id="menu-componentsx">
            <a href="/careers">
<!--                <i class="fas fa-users color-yellow-dark"></i>-->
                <span class="pl0">Careers</span>
                <i class="pt10"><img src="/images/bx-arrowright.svg" alt="arrow" width="24" height="24" class="float-right"></i>
            </a>
        </li>   
        <li id="menu-pages">
            <a href="/terms">
<!--                <i class="fas fa-clipboard-list color-red-dark"></i>-->
                <span class="pl0">Terms and Conditions</span>
                <i class="pt10"><img src="/images/bx-arrowright.svg" alt="arrow" width="24" height="24" class="float-right"></i>
            </a>
        </li>    
        <li id="menu-media">
            <a href="/disclaimer">
<!--                <i class="fas fa-exclamation-triangle color-brown-light"></i>-->
                <span class="pl0">Disclaimer</span>
                <i class="pt10"><img src="/images/bx-arrowright.svg" alt="arrow" width="24" height="24" class="float-right"></i>
            </a>
        </li>      
        <li id="menu-contact">
            <a href="/privacy">
<!--                <i class="fas fa-shield-alt color-blue-dark"></i>-->
                <span class="pl0">Privacy Policy</span>
                <i class="pt10"><img src="/images/bx-arrowright.svg" alt="arrow" width="24" height="24" class="float-right"></i>
            </a>
        </li>
        <li id="menu-contact">
            <a href="#">
<!--                <i class="fas fa-sitemap color-magenta-dark"></i>-->
                <span class="pl0">Sitemap</span>
                <i class="pt10"><img src="/images/bx-arrowright.svg" alt="arrow" width="24" height="24" class="float-right"></i>
            </a>
        </li>
        <li id="menu-contact">
            <a href="/one-way-cab" class="default-link">
<!--                <i class="fas fa-arrow-right color-orange-light "></i>-->
                <span class="pl0">One Way Cabs</span>
                <i class="pt10"><img src="/images/bx-arrowright.svg" alt="arrow" width="24" height="24" class="float-right"></i>
            </a>
        </li>
        <li id="menu-contact">
            <a href="/ask-us-to-be-official-partner">
<!--                <i class="fas fa-question-circle color-pink-dark "></i>-->
                <span class="pl0">Ask Us To Be Official Partner</span>
                <i class="pt10"><img src="/images/bx-arrowright.svg" alt="arrow" width="24" height="24" class="float-right"></i>
            </a>
        </li>
        <li id="menu-contact">
            <a href="/business-travel">
<!--                <i class="fas fa-briefcase color-purple"></i>-->
                <span class="pl0">Business Travel</span>
                <i class="pt10"><img src="/images/bx-arrowright.svg" alt="arrow" width="24" height="24" class="float-right"></i>
            </a>
        </li>
        <li id="menu-contact">
            <a href="/for-startups">
<!--                <i class="fas fa-star color-red-dark"></i>-->
                <span class="pl0">For Startups</span>
                <i class="pt10"><img src="/images/bx-arrowright.svg" alt="arrow" width="24" height="24" class="float-right"></i>
            </a>
        </li>
        <li id="menu-contact">
            <a href="/your-travel-desk">
<!--                <i class="fas fa-chalkboard-teacher color-sms"></i>-->
                <span class="pl0">Your Travel Desk</span>
                <i class="pt10"><img src="/images/bx-arrowright.svg" alt="arrow" width="24" height="24" class="float-right"></i>
            </a>
        </li>
        <li id="menu-contact">
            <a href="/join-our-agent-network">
<!--                <i class="fas fa-network-wired color-google"></i>-->
                <span class="pl0">Join Our Agent Network</span>
                <i class="pt10"><img src="/images/bx-arrowright.svg" alt="arrow" width="24" height="24" class="float-right"></i>
            </a>
        </li>
        <li id="menu-contact">
            <a href="/brand-partner">
<!--                <i class="fas fa-user-tie color-red"></i>-->
                <span class="pl0">Brand Partners</span>
                <i class="pt10"><img src="/images/bx-arrowright.svg" alt="arrow" width="24" height="24" class="float-right"></i>
            </a>
        </li>
        <li id="menu-contact">
            <a href="/terms/doubleback">
<!--                <i class="fas fa-user-tie color-red"></i>-->
                <span class="pl0">Double Back</span>
                <i class="pt10"><img src="/images/bx-arrowright.svg" alt="arrow" width="24" height="24" class="float-right"></i>
            </a>
        </li>
			<li id="menu-contact">
				<a href="/price-guarantee">
<!--					<i class="fas fa-award color-pink-dark"></i>-->
					<span class="pl0">Price Guarantee</span>
					<i class="pt10"><img src="/images/bx-arrowright.svg" alt="arrow" width="24" height="24" class="float-right"></i>
				</a>
			</li>
			
			<li id="menu-contact">
				<a href="/newsroom">
<!--					<i class="fas fa-newspaper color-blue-dark"></i>-->
					<span class="pl0">News Room</span>
					<i class="pt10"><img src="/images/bx-arrowright.svg" alt="arrow" width="24" height="24" class="float-right"></i>
				</a>
			</li>
			<li>
				<a href="<?php echo Yii::app()->createUrl('/whygozo'); ?>">
<!--				<i class='fa fa-car color-green-dark'></i>-->
					<span class="pl0">Why aaocab</span>
					<i class="pt10"><img src="/images/bx-arrowright.svg" alt="arrow" width="24" height="24" class="float-right"></i>
				</a>
			</li>  
			<li>
				<a href="<?php echo Yii::app()->createUrl('/faq'); ?>">
<!--				<i class='fa fa-info-circle color-yellow-dark'></i>-->
					<span class="pl0">FAQ</span>
					<i class="pt10"><img src="/images/bx-arrowright.svg" alt="arrow" width="24" height="24" class="float-right"></i>
				</a>
			</li>  
             
			<li>
				<a href="<?php echo Yii::app()->createUrl('index/testimonial'); ?>" class="default-link">
<!--				<i class='fa fa-comment-dots color-brown-light'></i>-->
					<span class="pl0">Testimonials</span>
					<i class="pt10"><img src="/images/bx-arrowright.svg" alt="arrow" width="24" height="24" class="float-right"></i>
				</a>
			</li>      
			<li>
				<a href="<?php echo Yii::app()->createUrl('/blog'); ?>">
<!--				<i class='fas fa-blog color-blue-dark'></i>-->
					<span class="pl0">Our Blog</span>
					<i class="pt10"><img src="/images/bx-arrowright.svg" alt="arrow" width="24" height="24" class="float-right"></i>
				</a>
			</li>
				<li class="<?= $hide ?> menu-login">
					<a href="<?= Yii::app()->createUrl('users/logout') ?>">
<!--					<i class='fas fa-sign-out-alt color-orange-dark'></i>-->
						<span class="pl0" id="signId">Log Out</span>
						<i class="pt10"><img src="/images/bx-arrowright.svg" alt="arrow" width="24" height="24" class="float-right"></i>
					</a> 
				</li>
				
		</ul>
	</div>
</div>  
<!-- menu end -->

<script type="text/javascript">
    $('#menu-hider, .close-menu, .menu-hide').on('click', function () {
        $('.menu-box').removeClass('menu-box-active');
        $('#menu-hider').removeClass('menu-hider-active');

        return false;
    });
$('.header-title').click(function () {
    window.location = "<?= Yii::app()->getBaseUrl(true) ?>";
	}); 
</script>
