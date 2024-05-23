       
<div class="container mt50">
	<!--    <div class="row">
			<div class="col-xs-12 text-center"><img src="/images/logo2.png" alt="Gozocabs:India's leader in inter-city taxi | Great service. Price guarantee. Awesome reviews."></div>
		</div>-->

    <div class="row spot-panel text-center">
        <div class="col-xs-12 pull-center">
            <h2 style="color: #4bb825;">Yay! The Bookings are CREATED!</h2>
            <h2 style="color: #4bb825;">Booking IDs : <?= implode(', ', $bkgIds)?></h2>
			<?
			if ($model->bkg_status != 2)
			{
				?>
				<h2>The customer will receive a link in email and SMS to reconfirm this booking.</h2>
				<h2>  REMIND the customer to RECONFIRM THE BOOKING within 90 MINUTES.</h2> 
				<h2> If Booking is not reconfirmed within 90mins by the customer, it will get automatically cancelled and the car will not be sent</h2>
			<? } ?>

        </div>
    </div>
</div>
<script>
	history.pushState(null, null, location.href);
	window.onpopstate = function () {
		history.go(1);
	};
</script>