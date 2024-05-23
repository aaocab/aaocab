<style>
    .ques{
        font-weight: bold;

    }
    .ans{
        padding-bottom: 15px;
    }
    #btnTop {
        display: none; /* Hidden by default */
        position: fixed; /* Fixed/sticky position */
        bottom: 50%; /* Place the button at the bottom of the page */
        right: 30px; /* Place the button 30px from the right */
        z-index: 99; /* Make sure it does not overlap */
        border: none; /* Remove borders */
        outline: none; /* Remove outline */
        background-color: #f14848; /* Set a background color */
        color: white; /* Text color */
        cursor: pointer; /* Add a mouse pointer on hover */
        padding: 4px; /* Some padding */
        border-radius: 100px; /* Rounded corners */
        font-size: 14px; /* Increase font size */
        width: 30px; height: 30px;
    }

    #btnTop:hover {
        background-color: #555; /* Add a dark-grey background on hover */
    }

    #btnBottom {
        display: none; /* Hidden by default */
        position: fixed; /* Fixed/sticky position */
        bottom: 50%; /* Place the button at the bottom of the page */
        right: 64px; /* Place the button 30px from the right */
        z-index: 99; /* Make sure it does not overlap */
        border: none; /* Remove borders */
        outline: none; /* Remove outline */
        background-color: #3ec4b5; /* Set a background color */
        color: white; /* Text color */
        cursor: pointer; /* Add a mouse pointer on hover */
        padding: 4px; /* Some padding */
        border-radius: 100px; /* Rounded corners */
        font-size: 14px; /* Increase font size */
        width: 30px; height: 30px;
    }
    #btnBottom:hover {
        background-color: #555; /* Add a dark-grey background on hover */
    }
    .color-blue{ color: #13a4c9!important;}
</style>

<article>
    <section>
        <div class="right_ul">
            <button onclick="topFunction()" id="btnTop" title="Go to top"><i class="fa fa-arrow-up"></i></button>
            <button onclick="bottomFunction()" id="btnBottom" title="Go to bottom"><i class="fa fa-arrow-down"></i></button>
            <div class="row">
                <div class="col-xs-12">
                    <div class="inner_banner">
                        <figure><img src="/images/doubleback.jpg?v=0.1" alt="price-guarantee"></figure>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="text-center mt30 n"><a class="btn booking-new-btn" href="/" role="button">BOOK NOW</a></div>
                </div>
            </div>
            <ol class="p15">
                <li>This offer is valid for trips booked directly with Gozo Cabs either by booking by phone with us, on <a href="#" class="color-blue">Gozocabs.com</a> or through our mobile app.

                </li>
                <li>The trip must be booked and paid for at least < 72hours in advance of travel date and time and must have at least 15% paid for in advance. The Double back program requires that there be a time difference of 72hours from the time payment is received for the booking and the trip start time.</li>
                <li>DOUBLE BACK is available for trips booked all over India.</li>
                <li>The offer period is valid from May 16, 2019 to December 31, 2019.</li>
                <li>The DOUBLE BACK GUARANTEE will match up to ₹5000 to double the original amount that you paid in advance at the time of booking.</li>
                <li>This offer is NOT applicable on bookings of Gozo SHARE service. Your booking confirmation will clearly state if your booking is covered by the Double back offer.</li>
                <li>Gozo Cabs reserves the right to terminate the offer without giving any notice at its sole discretion.</li>
                <li>Refunds are usually processed within 21 days from the date of request, however, Gozo is not responsible for delays from the payment gateways. Gozo shall issue our guaranteed refund-match amount after due diligence is done by our team to ensure that the T&C of double back offer were met and that the act was free of malice or malicious intent.</li>
                <li>No other compensation shall be applicable alongside this offer.</li>
                <li>The listed fares are subject to change without any prior notification.</li>
                <li>Gozo reserves the right to refuse or deny service to anyone. In such cases, your booking will not be accepted and DOUBLE BACK shall not apply. In all such cases, Gozo may cancel the booking at least 5 days before pickup time or within 24 hours of receiving your booking request whichever is later.</li>
                <li>This program does not apply to bookings that are generated from a third-party marketing affiliate or reseller partner of Gozo cabs. Only applies to bookings directly made on GozoCabs.com website or mobile app or booked with us by directly by phone</li>
                <li>In addition to the above terms, all standard Gozo terms and conditions as listed at www.gozocabs.com/terms shall be applicable.</li>
                <li>For example, if you have paid ₹500 advance and Gozo cancels the trip, you’ll get ₹1000 cash refund. If you have paid ₹3000 in advance and Gozo is unable to arrange a car, you’ll get ₹6000 refund. If you have paid ₹6000 in advance and Gozo is unable to arrange a car, you’ll get ₹11000 refund, as it is capped by our ₹5000 match guarantee.</li>
                <li>Double Back program guarantee is only applicable when Gozo Cabs is unable to allocate a cab for your service and rest of the advance booking & payment terms & conditions are met and not when the ride is canceled by the customer.</li>
                <li>Gozo reserves the right in its sole discretion to modify or discontinue the Double back program or to restrict its availability to any person, at any time, for any or no reason, and without prior notice or liability to you. The terms that are in effect at the time of your booking will determine your eligibility under the Double back program.</li>
                <li>The failure by Gozo to enforce any provision of these Terms & Conditions shall not constitute a waiver of that provision.</li>
                <li>In the scenario where Gozo’s car is a no-show, customer must inform via an email immediately to info@gozocabs.in (will auto-reply with a case #) or via a phone call or web chat and get a case # immediately. Double back offer cannot be honored in the case of car no-show complaints unless a support case # was created and it is agreed between Gozo and customer in writing via an email or other instrument that the vehicle was not available for service.</li>
                <li>Double back program shall not be applicable if the vehicle is allocated but gets delayed due to traffic or other conditions beyond our control. So long as Gozo has allocated the vehicle to serve the ride, any cancellation by the customer because of delay in pick up or break down shall not apply for Double back guarantee.</li>

            </ol>
            <div class="text-center mb20" style="margin-top: 30px;"><a class="btn booking-new-btn" href="/" role="button">BOOK NOW</a></div>
        </div>
    </section>
</article>

<script>
// When the user scrolls down 20px from the top of the document, show the button

    window.onscroll = function () {
        scrollFunction()
    };
    function scrollFunction() {

        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            document.getElementById("btnTop").style.display = "block";
            document.getElementById("btnBottom").style.display = "block";

        } else {
            document.getElementById("btnTop").style.display = "none";
            document.getElementById("btnBottom").style.display = "none";
        }
        if (document.documentElement.scrollTop >= 9345)
        {
            document.getElementById("btnBottom").style.display = "none";
        }
    }
// When the user clicks on the button, scroll to the top of the document
    function topFunction() {
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
    }
    function bottomFunction()
    {
        var percentageToScroll = 82;
        var percentage = percentageToScroll / 100;
        var height = $(document).height() - $(window).height();
        var scrollAmount = height * percentage;
        //alert("aa="+scrollAmount);
        jQuery("html, body").animate({
            scrollTop: scrollAmount
        }, 900);
    }
</script>
