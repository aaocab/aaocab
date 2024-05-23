<style>
	.spot_link{ 

	}
	.spot_link a{ width: 100%;
	}
	.text_style1{ font-size: 16px; height: 140px; line-height: 16px;}
	.text_style2{ font-size: 12px;}

	@media (min-width: 320px) and (max-width: 767px) {
		.text_style2{ font-size: 9px;}
		.spot_link a{}
		.spot_link .btn:not(.md-skip):not(.bs-select-all):not(.bs-deselect-all).btn-lg{ padding: 10px;}
	}
</style>

<div class="container mt50" id="spotbookingdiv">
	<!--    <div class="row">
			<div class="col-xs-12 text-center"><img src="/images/logo2.png" alt="Gozocabs:India's leader in inter-city taxi | Great service. Price guarantee. Awesome reviews."></div>
		</div>-->
    <div class="row spot-panel">
        <div class="col-xs-12 col-sm-6 text-center mt30 spot_link">
            <a class="btn btn-primary btn-lg pt50 text_style1" href="<?= Yii::app()->createUrl('agent/booking/spot', ['bookingType' => 1, 'step' => 5]) ?>" role="button"><b>ONE-WAY TRIP</b><br><br><p class="text_style2">Customer will go One-way from one city to another.</p></a>
        </div>
        <div class="col-xs-12 col-sm-6 text-center mt30 spot_link">
            <a class="btn btn-primary btn-lg pt50 text_style1" href="<?= Yii::app()->createUrl('agent/booking/spot', ['bookingType' => 2, 'step' => 5]) ?>" role="button"><b>RETURN TRIP</b><br><br><span class="text_style2">Customer will make a round trip journey</span></a>
        </div>
<!--		<div class="col-xs-12 col-sm-6 text-center mt30 spot_link">
            <a class="btn btn-primary btn-lg pt50 text_style1" href="<?//= Yii::app()->createUrl('agent/booking/spot', ['bookingType' => 7, 'step' => 15]) ?>" role="button"><b>SHUTTLE TRIP</b><br><br><span class="text_style2">Sell seats in daily shared shuttles</span></a>
        </div>-->
		<div class="col-xs-12 col-sm-6 text-center mt30 spot_link">
            <a class="btn btn-primary btn-lg pt50 text_style1" href="<?= Yii::app()->createUrl('agent/booking/spot', ['bookingType' => 5, 'step' => 5]) ?>" role="button"><b>DAY RENTAL</b><br><br><span class="text_style2">Customer will book local rental for same city.</span></a>
        </div>

    </div>
</div>


