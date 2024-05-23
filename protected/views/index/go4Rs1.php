<style>
	.big-font{ font-size: 76px; line-height: normal;}
	.route_box{
		-webkit-border-top-right-radius: 100px;
		-moz-border-radius-topright: 100px;
		border-top-right-radius: 100px;
		background: #0c5cbf;
		color: #fff;
		padding: 30px 20px 20px 20px;
		margin-bottom: 30px;
	}
	.orange-bg2 {
		background: #f77026 none repeat scroll 0 0;
	}
	.route-part{
		-webkit-border-radius: 30px;
		-moz-border-radius: 30px;
		border-radius: 30px;
		background: #efefef;
		-webkit-box-shadow: 0px 0px 24px 0px rgba(0,0,0,0.38);
		-moz-box-shadow: 0px 0px 24px 0px rgba(0,0,0,0.38);
		box-shadow: 0px 0px 24px 0px rgba(0,0,0,0.38);
	}
	.route-pbtn a{
		-webkit-border-bottom-right-radius: 10px;
		-webkit-border-bottom-left-radius: 10px;
		-moz-border-radius-bottomright: 10px;
		-moz-border-radius-bottomleft: 10px;
		border-bottom-right-radius: 10px;
		border-bottom-left-radius: 10px;
		background: #dfdfdf;
		font-size: 30px; font-weight: 700; color: #636363; padding: 20px 55px; text-transform: uppercase; line-height: normal; text-decoration: none;
		-webkit-box-shadow: 0px 12px 24px -3px rgba(0,0,0,0.26);
-moz-box-shadow: 0px 12px 24px -3px rgba(0,0,0,0.26);
box-shadow: 0px 12px 24px -3px rgba(0,0,0,0.26);
	}
	.route-pbtn a:hover{ background: #0c5cbf; color: #fff;}
	[type="radio"]:checked,
	[type="radio"]:not(:checked) {
		position: absolute;
		left: -9999px;
	}
	[type="radio"]:checked + label,
	[type="radio"]:not(:checked) + label
	{
		position: relative;
		padding-left: 28px;
		cursor: pointer;
		line-height: 20px;
		display: inline-block;
		color: #666;
	}
	[type="radio"]:checked + label:before,
	[type="radio"]:not(:checked) + label:before {
		content: '';
		position: absolute;
		left: 0;
		top: 0;
		width: 30px;
		height: 30px;
		border: 1px solid #ddd;
		border-radius: 100%;
		background: #fff;
	}
	[type="radio"]:checked + label:after,
	[type="radio"]:not(:checked) + label:after {
		content: '';
		width: 24px;
		height: 24px;
		background: #f36d33;
		position: absolute;
		top: 3px;
		left: 3px;
		border-radius: 100%;
		-webkit-transition: all 0.2s ease;
		transition: all 0.2s ease;
	}
	[type="radio"]:not(:checked) + label:after {
		opacity: 0;
		-webkit-transform: scale(0);
		transform: scale(0);
	}
	[type="radio"]:checked + label:after {
		opacity: 1;
		-webkit-transform: scale(1);
		transform: scale(1);
	}
	.banner-ani img{ width: 100%;}
	.share_on{ background: #58a39f; padding: 30px 0; font-size: 60px; font-weight: bold;}
	.share_on a{ font-size: 60px; color: #fff; padding: 0 30px;}
	.share_on a:hover{ color: #ffbd2e;}
</style>
<div class="row">
	<div class="col-xs-12 mt20 mb20 text-center">
		<img src="../images/logo_share.png" alt="Gozo Share" >
	</div>
	<div class="col-xs-12 mt20 mb20 text-center">
		<div class="blue2 white-color big-font pt10 pb10">Go for <i class="fa fa-inr"></i>1/- &nbsp;&nbsp;<i class="fa fa-angle-double-right"></i></div>
	</div>
	<div class="col-xs-12 mt20 mb20 text-center banner-ani">
		<img src="../images/all-frames02.gif" alt="Gozo Share" >
	</div>
	<div class="col-xs-12 mt20 mb20">
		<p class="h3">Early bird gets the ride.</p>
		<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p>
	</div>
	<div class="col-xs-12 mt20 mb20">
		<p><span class="h3">Week 1</span> 4th Nov. 2018 to 10th Nov. 2018</p>
	</div>
	<div class="col-xs-12 mt20 mb20">
		<div class="row">
			<div class="col-xs-6 col-sm-3">
				<div class="route_box">
					<div class="text-right pr20">
						<input type="radio" id="test1" name="radio-group" checked>
						<label for="test1">&nbsp;</label>
					</div>
					<p class="mb40 pt20"><span class="h2">Delhi to Agra</span></p>
					<div class="row">
						<div class="col-xs-6 h3">04 Nov</div>
						<div class="col-xs-6 h3">12:30</div>
					</div>
				</div>
			</div>
			<div class="col-xs-6 col-sm-3">
				<div class="route_box">
					<div class="text-right pr20">
						<input type="radio" id="test2" name="radio-group" checked>
						<label for="test2">&nbsp;</label>
					</div>
					<p class="mb40 pt20"><span class="h2">Delhi to Agra</span></p>
					<div class="row">
						<div class="col-xs-6 h3">04 Nov</div>
						<div class="col-xs-6 h3">12:30</div>
					</div>
				</div>
			</div>
			<div class="col-xs-6 col-sm-3">
				<div class="route_box">
					<div class="text-right pr20">
						<input type="radio" id="test3" name="radio-group" checked>
						<label for="test3">&nbsp;</label>
					</div>
					<p class="mb40 pt20"><span class="h2">Delhi to Agra</span></p>
					<div class="row">
						<div class="col-xs-6 h3">04 Nov</div>
						<div class="col-xs-6 h3">12:30</div>
					</div>
				</div>
			</div>
			<div class="col-xs-6 col-sm-3">
				<div class="route_box">
					<div class="text-right pr20">
						<input type="radio" id="test3" name="radio-group" checked>
						<label for="test3">&nbsp;</label>
					</div>
					<p class="mb40 pt20"><span class="h2">Delhi to Agra</span></p>
					<div class="row">
						<div class="col-xs-6 h3">04 Nov</div>
						<div class="col-xs-6 h3">12:30</div>
					</div>
				</div>
			</div>
			<div class="col-xs-6 col-sm-3">
				<div class="route_box">
					<div class="text-right pr20">
						<input type="radio" id="test5" name="radio-group" checked>
						<label for="test5">&nbsp;</label>
					</div>
					<p class="mb40 pt20"><span class="h2">Delhi to Agra</span></p>
					<div class="row">
						<div class="col-xs-6 h3">04 Nov</div>
						<div class="col-xs-6 h3">12:30</div>
					</div>
				</div>
			</div>
			<div class="col-xs-6 col-sm-3">
				<div class="route_box">
					<div class="text-right pr20">
						<input type="radio" id="test6" name="radio-group" checked>
						<label for="test6">&nbsp;</label>
					</div>
					<p class="mb40 pt20"><span class="h2">Delhi to Agra</span></p>
					<div class="row">
						<div class="col-xs-6 h3">04 Nov</div>
						<div class="col-xs-6 h3">12:30</div>
					</div>
				</div>
			</div>
			<div class="col-xs-6 col-sm-3">
				<div class="route_box">
					<div class="text-right pr20">
						<input type="radio" id="test7" name="radio-group" checked>
						<label for="test7">&nbsp;</label>
					</div>
					<p class="mb40 pt20"><span class="h2">Delhi to Agra</span></p>
					<div class="row">
						<div class="col-xs-6 h3">04 Nov</div>
						<div class="col-xs-6 h3">12:30</div>
					</div>
				</div>
			</div>
			<div class="col-xs-6 col-sm-3">
				<div class="route_box">
					<div class="text-right pr20">
						<input type="radio" id="test8" name="radio-group" checked>
						<label for="test8">&nbsp;</label>
					</div>
					<p class="mb40 pt20"><span class="h2">Delhi to Agra</span></p>
					<div class="row">
						<div class="col-xs-6 h3">04 Nov</div>
						<div class="col-xs-6 h3">12:30</div>
					</div>
				</div>
			</div>
			<div class="col-xs-12">
				<span class="h3">You have selected delhi to agra, 04 nov at 10:30 Am. Click 'Book now to proceed</span> &nbsp; &nbsp; <span class="h3 mt10"><i class="fa fa-arrow-right mr20 ml20"></i></span> <a href="#" class="btn btn-primary proceed-new-btn mt0">Book Now!</a>
			</div>

		</div>
	</div>
	<div class="col-xs-12 mt20 mb20 orange-bg2 pt20 pb20 white-color text-center">
		<p class="h1 mt50 mb20">Don't see the route you want to take here?</p>
		<p class="h4 mb50">Tell us where you want to go. We'll add the most popular routes next week!</p>
	</div>
	<div class="col-xs-12 mt20 mb20">
		<div class="row">
			<div class="col-xs-11 col-sm-8 col-md-6 float-none marginauto route-part p40 text-center">
				<span class="h1">Delhi<i class="fa fa-angle-right mr30 ml30"></i>Jaipur</span>
			</div>
			<div class="col-xs-11 col-sm-5 col-md-3 float-none marginauto route-pbtn p20 text-center">
				<a href="#">Suggest</a>
			</div>
		</div>
	</div>
	<div class="col-xs-12 mt20 mb20">
		<div class="row text-uppercase">
			<div class="col-xs-12 text-center">
				<img src="images/go4_icon1.png" alt="" >
				<p class="mt20 h2">Same gender passengers</p>
			</div>
			<div class="col-xs-12 col-sm-6 p20 text-center">
				<img src="images/go4_icon3.png" alt="" >
				<p class="mt20 h2">Best Service</p>
			</div>
			<div class="col-xs-12 col-sm-6 p20 text-center">
				<img src="images/go4_icon2.png" alt="" >
				<p class="mt20 h2">Nationwide 1000+ Routes</p>
			</div>
			<div class="col-xs-12 col-sm-6 p20 text-center">
				<img src="images/go4_icon4.png" alt="" >
				<p class="mt20 h2">Reduce pollution</p>
			</div>
			<div class="col-xs-12 col-sm-6 p20 text-center">
				<img src="images/go4_icon5.png" alt="" >
				<p class="mt20 h2">24x7 Support</p>
			</div>
			<div class="col-xs-12 text-center">
				<img src="images/go4_icon6.png" alt="" >
				<p class="mt20 h2">Make Frinds</p>
			</div>
		</div>
	</div>
	<div class="col-xs-12 mt20 mb20 white-color text-center">
		<div class="row share_on">
			<div class="col-xs-12">Share On:<a href="#"><i class="fa fa-facebook-f"></i></a><a href="#"><i class="fa fa-instagram"></i></a><a href="#"><i class="fa fa-twitter"></i></a></div>
		</div>
	</div>
</div>