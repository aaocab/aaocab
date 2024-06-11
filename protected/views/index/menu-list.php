<script type="text/javascript" language="javascript">
$(document).ready(function () 
{ 
	if($("#loging_stat").val()==1)
	{
		$("#menu-login").html("<a href='/users/logout'><i class='fas fa-sign-in-alt color-orange-dark'></i><span>Log Out</span></a>");
	}
    
});
</script>

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
                <span>Why aaocab</span>
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
        <li id="menu-login">
            <a href="/signup">
                <i class='fas fa-sign-in-alt color-orange-dark'></i>
                <span id="signId">Sign In</span>
				<em>Log In to aaocab</em>
                <i class="fa fa-angle-right"></i>
            </a>
        </li>
    </ul>
</div>

<script type="text/javascript">
    $('#menu-hider, .close-menu, .menu-hide').on('click',function(){
        $('.menu-box').removeClass('menu-box-active');
        $('#menu-hider').removeClass('menu-hider-active');
        return false;
    });
</script>