<?php
if(Yii::app()->request->url=='/' || Yii::app()->request->url=='/bknw')
{
?>
<div class="container">
	<div class="row">
		<div class="col-xs-12 text-center mt20">
			<a href="<?=Yii::app()->getBaseUrl(true)?>/cheapest-oneway-rides" class="proceed-make-btn" target="_blank">Make your outstation booking in advance and let us find you the cheapest one-way rides</a>
		</div>
	</div>
</div>
<?php
}
?>
<footer class="footer">
    <nav class="nav">
		<div class="row footer-bg">
			<div class="container">
				<div class="row">
					<div class="footer-link">
						<a href="/ask-us-to-be-official-partner">Ask Us To Be Official Partner</a>|<a href="/business-travel">Business Travel</a>|<a href="/for-startups">For Startups</a>|<a href="/your-travel-desk">Your Travel Desk</a>|<a href="/join-our-agent-network">Join Our Agent Network</a>|<a href="/brand-partner">Brand Partners</a>|<a href="/price-guarantee">Price Guarantee</a><br>
						<a href="/">Home</a>|<a href="/blog">Blog</a>|<a href="/aboutus">About Us</a>|<a href="/faq">FAQS</a>|<a href="/contactus">Contact Us</a>|<a href="/careers">Careers</a>|<a href="/terms">Terms and Conditions</a>|<a href="/disclaimer">Disclaimer</a>|<a href="/privacy">Privacy Policy</a>|<a href="/sitemap">Sitemap</a>|<a href="/one-way-cab">One Way Cabs</a>|<a href="/whyaaocab">Why aaocab</a><br>
						© <?= date("Y") ?> aaocab Technologies Pvt. Ltd. All Rights Reserved.
					</div>
				</div>
				<p>&nbsp;</p>
			</div>
		</div>
    </nav>
</footer>