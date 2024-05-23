<?php
$isLoggedin = false;
if (!Yii::app()->user->isGuest)
{
	$isLoggedin = true;
}
?>
<div id="sidebar-right-over" data-selected="menu-components" data-subtitle="" data-load="menu-list" class="menu-box menu-load menu-sidebar-right-over">
	<div class="menu-title">
		<span class="color-highlight">Check out our pages</span>
		<h1>Navigation</h1>
		<a href="#" class="menu-hide"><i class="fa fa-times"></i></a>
	</div>
	<div class="menu-page">
		<ul class="menu-list">
			<li id="menu-index">
				<a href="/whygozo">
					<i class='fa fa-car color-green-dark'></i>
					<span>Why GozoCabs</span>
					<em>Why should you ride with Gozo?</em>
					<i class="fa fa-angle-right"></i>
				</a>
			</li>  
			<li id="menu-components">
				<a href="/faq">
					<i class='fa fa-info-circle color-yellow-dark'></i>
					<span>FAQ</span>
					<em>Frequently asked questions (FAQ)</em>
					<i class="fa fa-angle-right"></i>
				</a>
			</li>     
			<li id="menu-pages">
				<a href="/vendor/join">
					<i class='fa fa-car-side color-red-dark'></i>
					<span>Attach Your Taxi</span>
					<em>DCOs and Taxi Operators, Attach your taxi...</em>
					<i class="fa fa-angle-right"></i>
				</a>
			</li>    
			<li id="menu-media">
				<a href="/testimonial">
					<i class='fa fa-comment-dots color-brown-light'></i>
					<span>Testimonials</span>
					<em>What People are Saying</em>
					<i class="fa fa-angle-right"></i>
				</a>
			</li>      
			<li id="menu-contact">
				<a href="/blog">
					<i class='fas fa-blog color-blue-dark'></i>
					<span>Our Blog</span>
					<em>Gozo Cabs' insight on India travel</em>
					<i class="fa fa-angle-right"></i>
				</a>
			</li>
			<?
			if ($isLoggedin)
			{
				?>
				<li id="menu-pages">
					<a href="/users/view">
						<i class='fa fa-car-side color-red-dark'></i>
						<span>My Profile</span>
						<em>My Profile</em>
						<i class="fa fa-angle-right"></i>
					</a>
				</li>    
				<li id="menu-media">
					<a href="/booking/list">
						<i class='fa fa-comment-dots color-brown-light'></i>
						<span>Booking List</span>
						<em>Booking List</em>
						<i class="fa fa-angle-right"></i>
					</a>
				</li>      
				<li id="menu-contact">
					<a href="/users/Changepassword">
						<i class='fas fa-blog color-blue-dark'></i>
						<span>Change password</span>
						<em>Change password</em>
						<i class="fa fa-angle-right"></i>
					</a>
				</li>
				<li id="menu-contact">
					<a href="<?= Yii::app()->createUrl('users/logout') ?>">
						<i class='fas fa-sign-in-alt color-orange-dark'></i>
						<span id="signId">Log Out</span>
						<em>Log Out from Gozocabs</em>
						<i class="fa fa-angle-right"></i>
					</a> 
				</li>
			<? }
			else
			{
				?>
				<li id="menu-contact">
					<a href="/signin">
						<i class='fas fa-sign-in-alt color-orange-dark'></i>
						<span id="signId">Sign In</span>
						<em>Log In to Gozocabs</em>
						<i class="fa fa-angle-right"></i>
					</a>
				</li>
	<?php }
?>
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
</script>
