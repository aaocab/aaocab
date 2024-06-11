<?php
if (isset($organisationSchema) && trim($organisationSchema) != '')
{
	?>
	<script type="application/ld+json">
	<?php
	echo $organisationSchema;
	?>
	</script>
<?php } ?>
<div class="row m0">
    <div class="col-12 bg-black mb30 p0">
        <img src="/images/contact-bg.jpg?v=0.5" alt="" class="img-fluid">
    </div>
</div>


<div class="container">
    <div class="row">
        <div class="col-12 mt10 mb10">
            <div class="bg-white-box">
                <div class="row">
                    <div class="col-md-4"><img src="/images/contact.png" alt=""></div>
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-12">
                                <h1 class="font-24 mt10"><b>Contact Us</b></h1></div>
                            <div class="col-md-6">
                                <h2 class="font-18 mb0"><b>Address</b></h2>
                                <?= Config::getGozoAddress(Config::Corporate_address, true)?>
                                <h2 class="font-18 mb0 mt30"><b>E-mail</b></h2>
                                <a href="mailto:info@aaocab.com" style="text-decoration: none; color: #282828;"><i class="fa fa-envelope"></i> info@aaocab.com</a>

                            </div>
                            <div class="col-md-6">
                                <h2 class="font-18 mb0"><b>Our Phones</b></h2>
                                For corporate enquiries:
                                <b>(+91) 124-670-7941 (24x7)</b><br><br>
                                <b>For Booking:</b><br>
<!--                                <img src="/images/india-flag.png" alt="India"> <span>(+91) 90518-77-000 (24x7)</b></span><br>
                                <img src="/images/worl-icon.png" alt="International"> (+1) 650-741-GOZO-->
                                <a  href="javascript:void(0)" class="helpline text-warning"> Request a call back</a>
                                <h2 class="font-18 mb0 mt30"><b>KEEP IN TOUCH</b></h2>
                                <div class="social-2">
                                    <a href="http://www.facebook.com/aaocab" target="_blank" class="mt5"><i class="fab fa-facebook-f"></i></a><a href="https://twitter.com/aaocab" target="_blank"><i class="fab fa-twitter"></i></a><a href="http://www.instagram.com/aaocab/" target="_blank"><i class="fab fa-instagram"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 mb30 mt30">
            <iframe src="https://maps.google.com/maps?q=%20%23401%2C%20Signet%20Tower%2C%20DN-2%2C%20Salt%20Lake%20Bypass%2C%20DN%20Block%2C%20Sector%20V%2C%20Bidhannagar%2C%20Kolkata%2C%20West%20Bengal%20700091&t=&z=13&ie=UTF8&iwloc=&output=embed" width="100%" height="400" frameborder="0" style="border:0; border-radius: 10px;" allowfullscreen></iframe>
        </div>
    </div>
</div>

