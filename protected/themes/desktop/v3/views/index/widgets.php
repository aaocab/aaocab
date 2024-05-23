<script>
	function startTimer(duration, display) {
		var timer = duration, minutes, seconds;
		setInterval(function () {
			minutes = parseInt(timer / 60, 10)
			seconds = parseInt(timer % 60, 10);

			minutes = minutes < 10 ? "0" + minutes : minutes;
			seconds = seconds < 10 ? "0" + seconds : seconds;

			display.text(minutes + ":" + seconds);

			if (--timer < 0) {
				timer = duration;
			}
		}, 1000);
	}

	jQuery(function ($) {
		var fiveMinutes = 60 * 5,
				display = $('#time');
		startTimer(fiveMinutes, display);
	});
</script>
<style>
.button.play {
  box-sizing: border-box;
  width: 74px;
  height: 74px;
  border-width: 37px 0px 37px 74px;
  border-color: transparent transparent transparent #202020;
  background: #fff;
}
</style>

<div class="container">
	<div class="row">
		<div class="col-12 mb20">
			<div class="list-group" style="border-radius: 0;">
				<button type="button" class="list-group-item list-group-item-action active">Cras justo odio <i class='bx bx-chevron-right float-right'></i></button>
				<button type="button" class="list-group-item list-group-item-action">Dapibus ac facilisis in <i class='bx bx-chevron-right float-right'></i></button>
				<button type="button" class="list-group-item list-group-item-action">Morbi leo risus <i class='bx bx-chevron-right float-right'></i></button>
				<button type="button" class="list-group-item list-group-item-action">Porta ac consectetur ac <i class='bx bx-chevron-right float-right'></i></button>
				<button type="button" class="list-group-item list-group-item-action">Vestibulum at eros <i class='bx bx-chevron-right float-right'></i></button>
			</div>
		</div>
	</div>
</div>

<div class="container">
	<div class="row">
		<div class="col-sm-12">
			<label for="iconLeft" class="color-gray2">Name</label>
			<fieldset class="form-group position-relative has-icon-left">
				<input type="text" class="form-control" id="iconLeft" placeholder="Enter your name">
				<div class="form-control-position">
					<i class='bx bx-user-circle'></i>
				</div>
			</fieldset>
		</div>
		<div class="col-sm-12">
			<label for="iconLeft" class="color-gray2">Email</label>
			<fieldset class="form-group position-relative has-icon-left">
				<input type="text" class="form-control" id="iconLeft" placeholder="Enter your email">
				<div class="form-control-position">
					<i class='bx bx-envelope'></i>
				</div>
			</fieldset>
		</div>
		<div class="col-sm-12">
			<label for="iconLeft" class="color-gray2">Mobile</label>
			<fieldset class="form-group position-relative has-icon-left">
				<input type="text" class="form-control" id="iconLeft" placeholder="Enter your mobile">
				<div class="form-control-position">
					<i class='bx bx-phone-call'></i>
				</div>
			</fieldset>
		</div>
		<div class="col-sm-12">
			<label for="iconLeft" class="color-gray2">Address</label>
			<fieldset class="form-group position-relative has-icon-left">
				<input type="text" class="form-control" id="iconLeft" placeholder="Enter your address">
				<div class="form-control-position">
					<i class='bx bx-map'></i>
				</div>
			</fieldset>
		</div>
		<div class="col-sm-12">
			<label for="iconLeft" class="color-gray2">State</label>
			<fieldset class="form-group position-relative has-icon-left">
				<input type="text" class="form-control" id="iconLeft" placeholder="">
				<div class="form-control-position">
					<i class='bx bx-buildings'></i>
				</div>
			</fieldset>
		</div>
		<div class="col-sm-12">
			<label for="iconLeft" class="color-gray2">City</label>
			<fieldset class="form-group position-relative has-icon-left">
				<input type="text" class="form-control" id="iconLeft" placeholder="">
				<div class="form-control-position">
					<i class='bx bx-home-alt'></i>
				</div>
			</fieldset>
		</div>
		<div class="col-sm-12">
			<label for="iconLeft" class="color-gray2">Date of birth</label>
			<fieldset class="form-group position-relative has-icon-left">
				<input type="text" class="form-control" id="iconLeft" placeholder="">
				<div class="form-control-position">
					<i class='bx bx-calendar'></i>
				</div>
			</fieldset>
		</div>
		<div class="col-sm-12">
			<label for="iconLeft" class="color-gray2">Gender</label>
			<fieldset class="form-group position-relative has-icon-left">
				<input type="text" class="form-control" id="iconLeft" placeholder="">
				<div class="form-control-position">
					<i class='bx bx-group'></i>
				</div>
			</fieldset>
		</div>
	</div>
</div>

<div class="container">
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
				<div class="card-body">
					<label for="iconLeft" class="color-gray2">Driver license number</label>
					<input class="form-control form-control-lg" id="sizeLarge" type="text" placeholder="Enter your driver license number">
				</div>
			</div>
		</div>
		<div class="col-sm-12">
			<div class="card">
				<div class="card-body text-center">
					<img src="/images/img-2022/driver_license.png" width="100" alt=""><br>
					<a href="#" class="btn btn-lg btn-primary mb-1 mt-3">Add photo</a>
				</div>
			</div>
		</div>
		<div class="col-sm-12">
			<div class="card">
				<div class="card-body text-center">
					<img src="/images/img-2022/driver_license.png" width="100" alt=""><br>
					<a href="#" class="btn btn-lg btn-primary mb-1 mt-3">Add photo</a>
				</div>
			</div>
		</div>
		<div class="col-sm-12">
			<div class="card">
				<div class="card-body">
					<label for="iconLeft" class="color-gray2">Date of expiration</label>
					<input class="form-control form-control-lg" id="sizeLarge" type="text" placeholder=""><br>
					Please enter the expiration date of your document
				</div>
			</div>
		</div>
	</div>
</div>

<div class="container">
	<div class="row">
		<div class="col-12 mt20 mb20">
					<div class="star-widget">
						<input type="radio" name="star" id="star1"><label for="star1" class="bx bxs-star"></label>
						<input type="radio" name="star" id="star2"><label for="star2" class="bx bxs-star"></label>
						<input type="radio" name="star" id="star3"><label for="star3" class="bx bxs-star"></label>
						<input type="radio" name="star" id="star4"><label for="star4" class="bx bxs-star"></label>
						<input type="radio" name="star" id="star5"><label for="star5" class="bx bxs-star"></label>
					</div>
		</div>
	</div>
</div>
<div class="container">
	<div class="row">
		<div class="col-12">
			<div class="card">
					<div class="rating-widget">
						<input type="radio" name="rating" id="rate10"><label for="rate10">10</label>
						<input type="radio" name="rating" id="rate9"><label for="rate9">9</label>
						<input type="radio" name="rating" id="rate8"><label for="rate8">8</label>
						<input type="radio" name="rating" id="rate7"><label for="rate7">7</label>
						<input type="radio" name="rating" id="rate6"><label for="rate6">6</label>
						<input type="radio" name="rating" id="rate5"><label for="rate5">5</label>
						<input type="radio" name="rating" id="rate4"><label for="rate4">4</label>
						<input type="radio" name="rating" id="rate3"><label for="rate3">3</label>
						<input type="radio" name="rating" id="rate2"><label for="rate2">2</label>
						<input type="radio" name="rating" id="rate1"><label for="rate1">1</label>
					</div>
			</div>
		</div>
	</div>
</div>
<div class="container">
	<div class="row">
		<div class="col-12"><p class="font-18"><b>How did we do on AT202654926</b></p></div>
		<div class="col-6 col-xl-6">
			<p class="mb5">Picked up on: <span class="weight500">09/05/2022 12:30 PM</span></p>
			<p class="mb5">Route: <span class="weight500">Chhatrapati Shivaji International Airport (bom), Mumbai-Mumbai</span></p>
			<p class="mb5">Cab: <span class="weight500">Ertiga MH 06 BP 0155</span></p>
		</div>
		<div class="col-6 col-xl-6 text-right">
			<p class="mb5">Booking Time: <span class="weight500">07/05/2022 07:41 PM</span></p>
			<p class="mb5">Driver: <span class="weight500">Jayesh Ashok Jain</span></p>
			<p class="mb5">Trip Type: <span class="weight500">Airport Transfer</span></p>
		</div>
		<div class="col-12 mt-1">
			<p class="font-18"><b>How likely are you to recommend Gozo to your friends and family?</b></p>
			<div class="rating-list">
				<div class="d-flex justify-content-between">
					<span><b>Never</b></span><span class="right-m"><b>Absolutely</b></span>
				</div>
				<ul>
					<li><a href="#">1</a></li>
					<li><a href="#">2</a></li>
					<li><a href="#">3</a></li>
					<li><a href="#">4</a></li>
					<li><a href="#">5</a></li>
					<li><a href="#">6</a></li>
					<li><a href="#">7</a></li>
					<li><a href="#">8</a></li>
					<li><a href="#">9</a></li>
					<li><a href="#">10</a></li>
				</ul>
			</div>
		</div>
		<div class="col-12 mt-1">
			<p class="font-18"><b>Your comments about the overall trip</b></p>
			<div class="rating-list-2">
				<div class="d-flex justify-content-between">
					<span><b>Horrible</b></span><span class="right-m"><b>Loved It!</b></span>
				</div>
				<ul>
					<li><a href="#"><i class="bx bxs-star"></i></a></li>
					<li><a href="#"><i class="bx bxs-star"></i></a></li>
					<li><a href="#"><i class="bx bxs-star"></i></a></li>
					<li><a href="#"><i class="bx bxs-star"></i></a></li>
					<li><a href="#"><i class="bx bx-star"></i></a></li>
				</ul>
			</div>
		</div>
		<div class="col-12 mt-1">
			<div class="card mb10">
				<div class="card-body">
					<div class="checkbox">
						<input type="checkbox" class="checkbox-input" id="checkbox1" checked="">
						<label for="checkbox1"><b>I want Gozo team to contact me</b></label>
					</div>
					<div class="radio-style4 mt-1">
						<p class="mb5">Did you find website & app easy</p>

						<div class="radio inline-block mr-1">
							<input id="Users_search_0" value="1" type="radio" name="checkaccount" class="bkg_user_trip_type" checked="checked">	
							<label for="Users_search_0">Yes</label>
						</div>
						<div class="radio inline-block mr-1">
							<input id="Users_search_1" value="1" type="radio" name="checkaccount" class="bkg_user_trip_type" checked="checked">	
							<label for="Users_search_1">No</label>
						</div>
						<div class="radio inline-block">
							<input id="Users_search_2" value="1" type="radio" name="checkaccount" class="bkg_user_trip_type" checked="checked">	
							<label for="Users_search_2">Didn't use</label>
						</div>
						<textarea class="form-control mt-1" id="basicTextarea" rows="3" placeholder="Comments about our website & app"></textarea>
						<span class="text-muted font-12">You have 1000 characters left. (Maximum characters: 1000)</span>
					</div>

				</div>
			</div>
		</div>
		<div class="col-12">
			<textarea class="form-control mt-1" id="basicTextarea" rows="3" placeholder="Overall trip comments"></textarea>
			<span class="text-muted font-12">You have 1000 characters left. (Maximum characters: 1000)</span>
		</div>
	</div>
</div>



<div class="container">
<div class="row">
<div class="col-12">
<button class='button play'></button>
</div>
</div>
</div>

<div class="card-body">
                                    <ul class="timeline ps ps--active-y" style="height: 415px;">
                                        <li class="timeline-item timeline-arrow active pb5">
											<h6 class="timeline-title weight500 font-16">Going For Pickup</h6>
											<div class="ml20 mt5" style="word-wrap: break-word; word-break: break-all;">
												<i class="bx bxs-calendar"></i> 2022-05-02 18:50:16 <a href="#" target="_blank"><i class="bx bxs-map ml-1"></i>22.5752316,88.4336981</a>
												<i class="bx bxs-user ml-1"></i> Driver (KARTICK)
											</div>
										</li>
                                        <li class="timeline-item timeline-icon-primary active">
                                            <h6 class="timeline-title weight500 font-16">Arrived</h6>
											<div class="ml20 mt5" style="word-wrap: break-word; word-break: break-all;">
												<i class="bx bxs-calendar"></i> 2022-05-02 18:50:16 <a href="#" target="_blank"><i class="bx bxs-map ml-1"></i>22.5752316,88.4336981</a>
												<i class="bx bxs-user ml-1"></i> Driver (KARTICK)
											</div>
                                        </li>
                                        <li class="timeline-item timeline-icon-success active">
                                            <h6 class="timeline-title weight500 font-16">Start</h6>
											<div class="ml20 mt5" style="word-wrap: break-word; word-break: break-all;">
												<i class="bx bxs-calendar"></i> 2022-05-02 18:50:16 <a href="#" target="_blank"><i class="bx bxs-map ml-1"></i>22.5752316,88.4336981</a>
												<i class="bx bxs-user ml-1"></i> Driver (KARTICK)
											</div>
                                        </li>
                                        <li class="timeline-item timeline-icon-light active">
                                            <h6 class="timeline-title weight500 font-16">Going For Pickup</h6>
											<div class="ml20 mt5" style="word-wrap: break-word; word-break: break-all;">
												<i class="bx bxs-calendar"></i> 2022-05-02 18:50:16 <a href="#" target="_blank"><i class="bx bxs-map ml-1"></i>22.5752316,88.4336981</a>
												<i class="bx bxs-user ml-1"></i> Driver (KARTICK)
											</div>
                                        </li>
                                        <li class="timeline-item timeline-icon-danger">
                                            <h6 class="timeline-title weight500 font-16">Trip end</h6>
											<div class="ml20 mt5" style="word-wrap: break-word; word-break: break-all;">
												<i class="bx bxs-calendar"></i> 2022-05-02 18:50:16 <a href="#" target="_blank"><i class="bx bxs-map ml-1"></i>22.5752316,88.4336981</a>
												<i class="bx bxs-user ml-1"></i> Driver (KARTICK)
											</div>
                                        </li>
                                    <div class="ps__rail-x" style="left: 0px; bottom: 0px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 0px; height: 415px; right: 0px;"><div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 239px;"></div></div></ul>
                                    <button class="btn btn-block btn-primary">View All Notifications</button>
                                </div>

<div class="row">
                        <div class="col-12 breadcrumb-widget">
                            <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item"><a href="javascript:void(0);">One Way Trip</a></li>
                                            <li class="breadcrumb-item"><a href="javascript:void(0);">ON 27th Mar 2022  , AT 09:00 AM</a></li>
                                            <li class="breadcrumb-item"><a href="javascript:void(0);">Sedan</a></li>
                                            <li class="breadcrumb-item active" aria-current="page">Value+</li>
                                            <li class="breadcrumb-item active" aria-current="page">Address</li>
                                            <li class="breadcrumb-item active" aria-current="page">Review & Pay</li>
                                        </ol>
                                    </nav>
                        </div>
                    </div>







<a href="#" onclick="$('.aside').asidebar('open')">Open aside</a>
<div class="aside">
	<div class="aside-header">
        jQuery asidebar
        <span class="close" data-dismiss="aside" aria-hidden="true">&times;</span>
	</div>
	<div class="aside-contents">
        <h4>This is an off-canvas menu</h4>
        <p>Aside contents</p>
	</div>
</div>
<div class="aside-backdrop"></div>



<nav class="header-navbar navbar-expand-lg navbar navbar-with-menu navbar-static-top bg-white navbar-brand-center border-b-none">
	<div class="navbar-header d-xl-block d-none">

	</div>
	<div class="navbar-wrapper">
		<div class="navbar-container content">
			<div class="navbar-collapse" id="navbar-mobile">
				<ul class="nav navbar-nav float-right d-flex align-items-center ml-auto">
					<li class="dropdown dropdown-user nav-item"><a class="dropdown-toggle nav-link dropdown-user-link p-0" href="javascript:void(0);" data-toggle="dropdown">
							<div class="user-nav d-lg-flex d-none"><button type="button" class="btn btn-primary"><i class="fas fa-phone-alt" style="color:#fff;"></i> Request a call</button></div>
							<span class="fonticon-container color-black d-xl-none d-lg-none d-xxl-block">
								<div class="fonticon-wrap mb-0">
									<i class="bx bx-menu color-black"></i>
								</div>
							</span>
						</a>
						<div class="dropdown-menu dropdown-menu-left pb-0"><a class="dropdown-item" href="page-user-profile.html"><i class="bx bx-user mr-50"></i> New Booking</a><a class="dropdown-item" href="app-email.html"><i class="bx bx-envelope mr-50"></i> Existing Booking</a><a class="dropdown-item" href="app-todo.html"><i class="bx bx-check-square mr-50"></i> Vendor Helpline</a><a class="dropdown-item" href="app-chat.html"><i class="bx bx-message mr-50"></i> Attach Your Taxi</a>
						</div>
					</li>
				</ul>
			</div>
		</div>
	</div>
</nav>

<div class="container-fluid bg-outline pb-5">
	<div class="row">
		<div class="col-12 text-center pt-5 style-widget-1">
			<img src="images/gozo-white.svg" alt="Gozocabs:India's leader in inter-city taxi | Great service. Price guarantee. Awesome reviews." title="Gozocabs:India's leader in inter-city taxi | Great service. Price guarantee. Awesome reviews." width="150">
			<h2 class="gothic weight600 mt-2">We are India's leader for<br>
				inter-city taxi travel</h2>
			<? //= $this->renderPartial('topSearch', array('model' => $model, 'tripType' => $tripType), true, FALSE); ?>
			<a href="/bookNow" class="btn btn-lg btn-primary mb-1 mt-3"><span>Book a Ride </span></a>
		</div>
	</div>
</div> 

<div class="container-fluid pt-4 pb-4 bg-gray">
	<div class="row">
		<div class="col-12 text-center mb-2">
			<h2 class="text-bold-600 gothic">Top Review</h2>
			<i class="bx bxs-star font-48 color-blue"></i> <i class="bx bxs-star font-48 color-blue"></i> <i class="bx bxs-star font-48 color-blue"></i> <i class="bx bxs-star font-48 color-blue"></i> <i class="bx bxs-star font-48 color-blue"></i>
		</div>
		<div class="col-12 col-lg-3">
			<div class="card kb-hover-1">
				<div class="card-body">
					<p class=" text-muted font-13">Udayji was excellent driver. He was on time, well mannered and followed all Covid protocols. The car was as good as new and completely sanitized and very clean. I would love to travel again with him some day.</p>
					<h6>Aniket Ghanwatkar <span class="font-11 color-gray">3rd Jan 2022</span></h6>
					<div class="badge-circle badge-circle-lg badge-circle-light-primary float-right"><b>GH</b></div>
					<p class="font-11">Chhatrapati Shivaji International Airport (bom) - Mumbai (Mumbai Airport) - Maharashtra - Kopargaon - Ahmednagar - Maharashtra, One Way</p>
				</div>
			</div>
		</div>
		<div class="col-12 col-lg-3">
			<div class="card kb-hover-1">
				<div class="card-body">
					<p class=" text-muted font-13">Udayji was excellent driver. He was on time, well mannered and followed all Covid protocols. The car was as good as new and completely sanitized and very clean. I would love to travel again with him some day.</p>
					<h6>Aniket Ghanwatkar <span class="font-11 color-gray">3rd Jan 2022</span></h6>
					<div class="badge-circle badge-circle-lg badge-circle-light-primary float-right"><b>GH</b></div>
					<p class="font-11">Chhatrapati Shivaji International Airport (bom) - Mumbai (Mumbai Airport) - Maharashtra - Kopargaon - Ahmednagar - Maharashtra, One Way</p>
				</div>
			</div>
		</div>
		<div class="col-12 col-lg-3">
			<div class="card kb-hover-1">
				<div class="card-body">
					<p class=" text-muted font-13">Udayji was excellent driver. He was on time, well mannered and followed all Covid protocols. The car was as good as new and completely sanitized and very clean. I would love to travel again with him some day.</p>
					<h6>Aniket Ghanwatkar <span class="font-11 color-gray">3rd Jan 2022</span></h6>
					<div class="badge-circle badge-circle-lg badge-circle-light-primary float-right"><b>GH</b></div>
					<p class="font-11">Chhatrapati Shivaji International Airport (bom) - Mumbai (Mumbai Airport) - Maharashtra - Kopargaon - Ahmednagar - Maharashtra, One Way</p>
				</div>
			</div>
		</div>
		<div class="col-12 col-lg-3">
			<div class="card kb-hover-1">
				<div class="card-body">
					<p class=" text-muted font-13">Udayji was excellent driver. He was on time, well mannered and followed all Covid protocols. The car was as good as new and completely sanitized and very clean. I would love to travel again with him some day.</p>
					<h6>Aniket Ghanwatkar <span class="font-11 color-gray">3rd Jan 2022</span></h6>
					<div class="badge-circle badge-circle-lg badge-circle-light-primary float-right"><b>GH</b></div>
					<p class="font-11">Chhatrapati Shivaji International Airport (bom) - Mumbai (Mumbai Airport) - Maharashtra - Kopargaon - Ahmednagar - Maharashtra, One Way</p>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="container-fluid pb-4 pt-3">
	<div class="container">
		<div class="row card-shadow-no">
			<div class="col-xl-3 col-md-6 col-12">
				<div class="card text-center">
					<div class="card-body">
						<img src="/images/img-2022/icon10.svg" width="170" alt="One-way travel">
						<h5 class="mb-0 mt-1 weight600 gothic">One-way travel</h5>
					</div>
				</div>
			</div>
			<div class="col-xl-3 col-md-6 col-12">
				<div class="card text-center">
					<div class="card-body">
						<img src="/images/img-2022/inr3.svg" width="100" alt="Price Transparency">
						<h5 class="mb-0 mt-1 weight600 gothic">Price Transparency</h5>
					</div>
				</div>
			</div>
			<div class="col-xl-3 col-md-6 col-12">
				<div class="card text-center">
					<div class="card-body">
						<img src="/images/img-2022/icon12.svg" width="120" alt="24x7">
						<h5 class="mb-0 mt-1 weight600 gothic">24x7</h5>
					</div>
				</div>
			</div>
			<div class="col-xl-3 col-md-6 col-12">
				<div class="card text-center">
					<div class="card-body">
						<img src="/images/img-2022/icon13.svg" width="110" alt="Zero Cancellation*">
						<h5 class="mb-0 mt-1 weight600 gothic">Zero Cancellation*</h5>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>



<div class="container-fluid pb-4 pt-3 bg-gray">
	<div class="container">
		<div class="row card-shadow-no">
			<div class="col-12 col-xl-8 offset-xl-2 text-center">
				<p class="font-20 weight500 color-gray mb-0">Become or travel agent with Gozo.</p>
				<p class="font-30 weight600 mb-4 gothic">Join Gozo's travel partner family..</p>
				<p class="font-20 mb-0 weight600 gothic">Gozo Travel partner program</p>
				<p>Travel Agents, Hotels travel desks, Shopkeepers... Offer convenience to your customers and make money. Its simple! Join now and instantly start creating bookings for your customers</p>
			</div>
			<div class="col-12 mt-2 list-3styled">

				<p class="font-20 weight600 gothic">Benefits of joining Gozo's Travel networkâ€¦</p>
			</div>
		</div>
		<div class="row" style="display: flex; flex-wrap: wrap;">
			<div class="col-12 col-xl-9">
				<div class="row">
					<div class="col-12 col-sm-12 col-md-6 col-xl-4 flex2">
                        <div class="card text-center">
                            <div class="card-body p-1">
                                <div class="badge-circle badge-circle-lg badge-circle-light-primary mx-auto mb-50">
                                    <i class="fas fa-car-alt font-medium-5"></i>
                                </div>
                                <div class="text-muted">Get direct access to India's largest network of intercity AC Taxi</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6 col-xl-4 flex2">
                        <div class="card text-center">
                            <div class="card-body p-1">
                                <div class="badge-circle badge-circle-lg badge-circle-light-primary mx-auto mb-50">
                                    <i class="far fa-user font-medium-5"></i>
                                </div>
                                <div class="text-muted">Offer convenience of outstation taxi bookings to your customers</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6 col-xl-4 flex2">
                        <div class="card text-center">
                            <div class="card-body p-1">
                                <div class="badge-circle badge-circle-lg badge-circle-light-primary mx-auto mb-50">
                                    <span class="font-medium-5">&#x20B9;</span>
                                </div>
                                <div class="text-muted">Buy bookings at very low pricing - and sell them to create profits</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6 col-xl-4 flex2">
                        <div class="card text-center">
                            <div class="card-body p-1">
                                <div class="badge-circle badge-circle-lg badge-circle-light-primary mx-auto mb-50">
                                    <i class="fas fa-money-check font-medium-5"></i>
                                </div>
                                <div class="text-muted">Easily create pre-paid or post-paid bookings using our kiosk</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6 col-xl-4 flex2">
                        <div class="card text-center">
                            <div class="card-body p-1">
                                <div class="badge-circle badge-circle-lg badge-circle-light-primary mx-auto mb-50">
                                    <i class="far fa-clock font-medium-5"></i>
                                </div>
                                <div class="text-muted">Get 24x7 support from our travel desk & service center</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6 col-xl-4 flex2">
                        <div class="card text-center">
                            <div class="card-body p-1">
                                <div class="badge-circle badge-circle-lg badge-circle-light-primary mx-auto mb-50">
                                    <i class="fas fa-user-tie font-medium-5"></i>
                                </div>
                                <div class="text-muted">Just like our other partners you can generate business of Rs. 50,000 to Rs. 1Lac every month</div>
                            </div>
                        </div>
                    </div>
				</div>
			</div>
			<div class="col-12 col-xl-3 text-center d-none d-sm-block"><img src="/images/img-2022/icon9.svg" width="150" class="mt-3" alt=""></div>
			<div class="col-12"><a href="#" class="btn btn-lg btn-primary mb-1 mt-1">Create account</a></div>
		</div>
	</div>
</div>

<div class="container-fluid pb-4 pt-3">
	<div class="container">
		<div class="row card-shadow-no">
			<div class="col-12 col-xl-8 offset-xl-2 text-center">
				<p class="font-20 weight500 color-gray mb-0">DCOs and Cab Operators</p>
				<p class="font-30 weight600 mb-4 gothic">Attach your cab...</p>
				<p class="font-20 mb-0 weight600 gothic">Attach your car into the Gozo Vendor networks</p>
				<p>If you own or operate a inter-city taxi, then you should join with Gozo.</p>
			</div>
			<div class="col-12 mt-2">
				<p class="font-20 weight600 gothic">Benefits for Gozo vendor partners</p>
			</div>
		</div>
		<div class="row">
			<div class="col-12 col-xl-9">
				<div class="row" style="display: flex; flex-wrap: wrap;">
					<div class="col-12 col-sm-12 col-md-6 col-xl-4 flex2">
						<div class="card text-center">
							<div class="card-body p-1">
								<div class="badge-circle badge-circle-lg badge-circle-light-primary mx-auto mb-50">
									<i class="fas fa-chart-line font-medium-5"></i>
								</div>
								<div class="text-muted">Gozo focuses on getting customer demand</div>
							</div>
						</div>
					</div>
					<div class="col-12 col-sm-12 col-md-6 col-xl-4 flex2">
						<div class="card text-center">
							<div class="card-body p-1">
								<div class="badge-circle badge-circle-lg badge-circle-light-primary mx-auto mb-50">
									<i class="fas fa-award font-medium-5"></i>
								</div>
								<div class="text-muted">You simply provide top quality service</div>
							</div>
						</div>
					</div>
					<div class="col-12 col-sm-12 col-md-6 col-xl-4 flex2">
						<div class="card text-center">
							<div class="card-body p-1">
								<div class="badge-circle badge-circle-lg badge-circle-light-primary mx-auto mb-50">
									<i class="bx bxs-briefcase font-medium-5"></i>
								</div>
								<div class="text-muted">Stay busy in all seasons. Good service = More business</div>
							</div>
						</div>
					</div>
					<div class="col-12 col-sm-12 col-md-6 col-xl-4 flex2">
						<div class="card text-center">
							<div class="card-body p-1">
								<div class="badge-circle badge-circle-lg badge-circle-light-primary mx-auto mb-50">
									<i class="fas fa-star font-medium-5"></i>
								</div>
								<div class="text-muted">Get great reviews from customers</div>
							</div>
						</div>
					</div>
					<div class="col-12 col-sm-12 col-md-6 col-xl-4 flex2">
						<div class="card text-center">
							<div class="card-body p-1">
								<div class="badge-circle badge-circle-lg badge-circle-light-primary mx-auto mb-50">
									<i class="bx bx-money font-medium-5"></i>
								</div>
								<div class="text-muted">Gozo sends you payments on-time</div>
							</div>
						</div>
					</div>
					<div class="col-12 col-sm-12 col-md-6 col-xl-4 flex2">
						<div class="card text-center">
							<div class="card-body p-1">
								<div class="badge-circle badge-circle-lg badge-circle-light-primary mx-auto mb-50">
									<i class="fas fa-hands-helping font-medium-5"></i>
								</div>
								<div class="text-muted">Use your Gozo partner and Gozo driver mobile app to keep in continous touch with Gozo</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-12 col-xl-3 text-center d-none d-sm-block"><img src="/images/img-2022/icon7.svg" width="230" class="mt-3" alt=""></div>
			<div class="col-12"><a href="#" class="btn btn-lg btn-primary mb-1 mt-1">Attach your taxi</a></div>
		</div>
	</div>
</div>



<div class="container-fluid pb-4 pt-3 bg-gray3">
	<div class="container">
		<div class="row">
			<div class="col-12 col-xl-8 offset-xl-2 text-center">
				<p class="font-30 weight600 mb-4 gothic">Tour Packages</p>
			</div>
			<div class="col-12 card-deck">
				<div class="row" style="display: flex; flex-wrap: wrap;">
					<?php
					$qry	 = [];
					$pmodel	 = Package::model()->getListtoShow('', $qry);
					$i		 = 1;
					foreach ($pmodel as $pck)
					{
						if ($i <= 4)
						{
							?>
							<div class="col-12 col-md-6 col-lg-3 col-xl-3 flex mb-1">
								<div class="card m0">
									<img src="<?= $pck['pci_images'] ?>" class="card-img-top img-fluid" alt="singleminded">
									<div class="card-header">
										<h4 class="card-title"><?php echo $pck['pck_name']; ?></h4>
									</div>
									<div class="card-body">
										<a href="#" class="btn mr-1 mb-1 btn-primary btn-sm">Call / Email us to book</a><br>
										<a href="#" class="btn mr-1 mb-1 btn-secondary btn-sm">Details</a>
									</div>
								</div>
							</div>
							<?php
						} $i++;
					}
					?>



					<div class="col-12 mt-2 text-right"><a href="#" class="btn btn-icon btn-outline-primary mr-1 mb-1"><i class="bx bx-dots-horizontal-rounded"></i></a></div>
				</div>                        
			</div>
		</div>
	</div>
</div>




<div class="container pt-5">
	<div class="row mt-3">
		<div class="col-12 col-xl-6 offset-xl-2 text-center widget-panel">
			<p class="font-30 weight600 gothic">Download App Now!</p>
			<p><a href="#"><img src="/images/img-2022/google_play_store_logo.png" width="200" alt="" class="mb-1"></a> <a href="#"><img src="/images/img-2022/app-store-logo.png" width="200" alt="" class="mb-1"></a></p>
		</div>
		<div class="col-12 col-xl-4 text-center d-none d-sm-block"><img src="/images/img-2022/mobile.svg" width="400" alt="" class="img-fluid"></div>
	</div>
</div>
<div class="container">
	<div class="row mb-2 radio-style3">
		<div class="col-12 col-lg-3 offset-lg-3 text-center">
			<img src="/images/img-2022/icon1.svg" width="150" alt="">
			<div class="radio mt-1">
				<p class="text-center mb-0">IN-THE-CITY</p>
				<input id="cabsegmentation_0" value="1" type="radio" name="cabsegmentation" class="bkg_user_trip_type">
				<label for="cabsegmentation_0"></label>
			</div>
		</div>
		<div class="col-12 col-lg-3 radio text-center">
			<img src="/images/img-2022/icon2.svg" width="150" alt="">
			<div class="radio mt-1 ">
				<p class="text-center mb-0">OUTSTATION (INTER-CITY)</p>
				<input id="cabsegmentation_1" value="2" type="radio" name="cabsegmentation" class="bkg_user_trip_type">
				<label for="cabsegmentation_1"></label>
			</div>
		</div>
	</div>
	<div class="row mb-2 radio-style3">
		<div class="col-12 col-lg-3 offset-lg-3 text-center">
			<img src="/images/img-2022/icon3.svg" width="150" alt="">
			<div class="radio mt-1">
				<p class="text-center mb-0">IN-THE-CITY</p>
				<input id="cabsegmentation_0" value="1" type="radio" name="cabsegmentation" class="bkg_user_trip_type">
				<label for="cabsegmentation_0"></label>
			</div>
		</div>
		<div class="col-12 col-lg-3 radio text-center">
			<img src="/images/img-2022/icon4.svg" width="150" alt="">
			<div class="radio mt-1 ">
				<p class="text-center mb-0">OUTSTATION (INTER-CITY)</p>
				<input id="cabsegmentation_1" value="2" type="radio" name="cabsegmentation" class="bkg_user_trip_type">
				<label for="cabsegmentation_1"></label>
			</div>
		</div>
	</div>
</div>

<div class="container mb-2">
	<div class="row">
		<div class="col-12 text-center">
			<h2 class="gothic weight600">Want a specific model of cab?</h2>
		</div>
		<div class="col-12 col-lg-4 offset-lg-4 mt-3">
			<div class="row">
				<div class="col-12 widget-liststyle mb-1">
					<div class="radio-style4">
						<div class="radio">
							<input id="test12" value="1" type="radio" name="cabsegmentation" class="bkg_user_trip_type">
							<label for="test12">Upgrade to guaranteed D'zire for add <span class="font-16 ml5">&#x20B9;</span><span class="font-16 weight600">2779</span></label>
						</div>
					</div>
				</div>
				<div class="col-12 widget-liststyle mb-1">
					<div class="radio-style4">
						<div class="radio">
							<input id="test13" value="1" type="radio" name="cabsegmentation" class="bkg_user_trip_type">
							<label for="test13">Upgrade to guaranteed Innova for add <span class="font-16 ml5">&#x20B9;</span><span class="font-16 weight600">2779</span></label>
						</div>
					</div>
				</div>
				<div class="col-12 widget-liststyle mb-1">
					<div class="radio-style4">
						<div class="radio">
							<input id="test14" value="1" type="radio" name="cabsegmentation" class="bkg_user_trip_type">
							<label for="test14">Decline upgrade. Allocate whatever is available.</label>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>



<div class="container mb-2">
	<div class="row" style="display: flex; flex-wrap: wrap;">
		<div class="col-xl-3 col-md-6 col-sm-12 flex">
			<div class="card text-center pt-1" style="width: 100%;">
				<span class="text-center"><img src="/images/car-etios.jpg" width="150" class="img-fluid" alt="singleminded"></span>
				<div class="card-header text-center pt10 pb5" style="display: inline-block;">
					<h3 class="text-center font-22 weight600 text-uppercase">Compact</h3>
				</div>
				<div class="card-body">
					<p class="weight400 mb0">More comfort</p>
					<p class="weight400 mb5">Reasonably priced</p>
					<p class="weight400 color-blue">
						<i class="bx bxs-star"></i> <i class="bx bxs-star"></i> <i class="bx bxs-star"></i> <i class="bx bxs-star"></i>
					</p>
					<p class="mb0"><span class="font-20">&#x20B9;</span><span class="font-24 weight600">2450</span></p>
					<p class="mb0">onwards</p>
					<div class="radio-style3">
                        <div class="radio">
                            <input id="test1" value="1" type="radio" name="cabsegmentation" class="bkg_user_trip_type">
                            <label for="test1"></label>
                        </div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-3 col-md-6 col-sm-12 flex">
			<div class="card text-center pt-1" style="width: 100%;">
				<span class="text-center"><img src="/images/car-etios.jpg" width="150" class="img-fluid" alt="singleminded"></span>
				<div class="card-header text-center pt10 pb5" style="display: inline-block;">
					<h3 class="text-center font-22 weight600 text-uppercase">Sedan</h3>
				</div>
				<div class="card-body">
					<p class="weight400 mb0">More comfort</p>
					<p class="weight400 mb5">Reasonably priced</p>
					<p class="weight400 color-blue">
						<i class="bx bxs-star"></i> <i class="bx bxs-star"></i>
					</p>
					<p class="mb0"><span class="font-20">&#x20B9;</span><span class="font-24 weight600">2450</span></p>
					<p class="mb0">onwards</p>
					<div class="radio-style3">
                        <div class="radio">
                            <input id="test2" value="1" type="radio" name="cabsegmentation" class="bkg_user_trip_type">
                            <label for="test2"></label>
                        </div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-3 col-md-6 col-sm-12 flex">
			<div class="card text-center pt-1" style="width: 100%;">
				<span class="text-center"><img src="/images/car-etios.jpg" width="150" class="img-fluid" alt="singleminded"></span>
				<div class="card-header text-center pt10 pb5" style="display: inline-block;">
					<h3 class="text-center font-22 weight600 text-uppercase">suv</h3>
				</div>
				<div class="card-body">
					<p class="weight400 mb0">More comfort</p>
					<p class="weight400 mb5">Reasonably priced</p>
					<p class="weight400 color-blue">
						<i class="bx bxs-star"></i> <i class="bx bxs-star"></i> <i class="bx bxs-star"></i> <i class="bx bxs-star"></i>
					</p>
					<p class="mb0"><span class="font-20">&#x20B9;</span><span class="font-24 weight600">2450</span></p>
					<p class="mb0">onwards</p>
					<div class="radio-style3">
                        <div class="radio">
                            <input id="test3" value="1" type="radio" name="cabsegmentation" class="bkg_user_trip_type">
                            <label for="test3"></label>
                        </div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-3 col-md-6 col-sm-12 flex">
			<div class="card text-center pt-1" style="width: 100%;">
				<span class="text-center"><img src="/images/car-etios.jpg" width="150" class="img-fluid" alt="singleminded"></span>
				<div class="card-header text-center pt10 pb5" style="display: inline-block;">
					<h3 class="text-center font-22 weight600 text-uppercase">Tempo Traveller</h3>
				</div>
				<div class="card-body">
					<p class="weight400 mb0">More comfort</p>
					<p class="weight400 mb5">Reasonably priced</p>
					<p class="weight400 color-blue">
						<i class="bx bxs-star"></i> <i class="bx bxs-star"></i> <i class="bx bxs-star"></i>
					</p>
					<p class="mb0"><span class="font-20">&#x20B9;</span><span class="font-24 weight600">2450</span></p>
					<p class="mb0">onwards</p>
					<div class="radio-style3">
                        <div class="radio">
                            <input id="test4" value="1" type="radio" name="cabsegmentation" class="bkg_user_trip_type">
                            <label for="test4"></label>
                        </div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<hr>

<div class="container mb-2">
	<div class="row" style="display: flex; flex-wrap: wrap;">
		<div class="col-xl-3 col-md-6 col-sm-12 flex">
			<div class="card text-center pt-1" style="width: 100%;">
				<div class="card-header text-center pt10 pb5" style="display: inline-block;">
					<h3 class="text-center font-24 weight600 text-uppercase">CNG</h3>
				</div>
				<div class="card-body">
					<p class="weight400 mb0">More comfort</p>
					<p class="weight400 mb5">Reasonably priced</p>
					<p class="weight400 color-blue">
						<i class="bx bxs-star"></i> <i class="bx bxs-star"></i> <i class="bx bxs-star"></i> <i class="bx bxs-star"></i>
					</p>
					<p class="mb0"><span class="font-20">&#x20B9;</span><span class="font-24 weight600">2450</span></p>
					<p class="mb0">onwards</p>
					<div class="radio-style3">
                        <div class="radio">
                            <input id="test5" value="1" type="radio" name="cabsegmentation" class="bkg_user_trip_type">
                            <label for="test5"></label>
                        </div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-3 col-md-6 col-sm-12 flex">
			<div class="card text-center pt-1" style="width: 100%;">
				<div class="card-header text-center pt10 pb5" style="display: inline-block;">
					<h3 class="text-center font-24 weight600 text-uppercase">Value</h3>
				</div>
				<div class="card-body">
					<p class="weight400 mb0">More comfort</p>
					<p class="weight400 mb5">Reasonably priced</p>
					<p class="weight400 color-blue">
						<i class="bx bxs-star"></i> <i class="bx bxs-star"></i>
					</p>
					<p class="mb0"><span class="font-20">&#x20B9;</span><span class="font-24 weight600">2450</span></p>
					<p class="mb0">onwards</p>
					<div class="radio-style3">
                        <div class="radio">
                            <input id="test6" value="1" type="radio" name="cabsegmentation" class="bkg_user_trip_type">
                            <label for="test6"></label>
                        </div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-3 col-md-6 col-sm-12 flex">
			<div class="card text-center pt-1" style="width: 100%;">
				<div class="card-header text-center pt10 pb5" style="display: inline-block;">
					<h3 class="text-center font-24 weight600 text-uppercase">Value+</h3>
				</div>
				<div class="card-body">
					<p class="weight400 mb0">More comfort</p>
					<p class="weight400 mb5">Reasonably priced</p>
					<p class="weight400 color-blue">
						<i class="bx bxs-star"></i> <i class="bx bxs-star"></i> <i class="bx bxs-star"></i> <i class="bx bxs-star"></i>
					</p>
					<p class="mb0"><span class="font-20">&#x20B9;</span><span class="font-24 weight600">2450</span></p>
					<p class="mb0">onwards</p>
					<div class="radio-style3">
                        <div class="radio">
                            <input id="test7" value="1" type="radio" name="cabsegmentation" class="bkg_user_trip_type">
                            <label for="test7"></label>
                        </div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-3 col-md-6 col-sm-12 flex">
			<div class="card text-center pt-1" style="width: 100%;">
				<div class="card-header text-center pt10 pb5" style="display: inline-block;">
					<h3 class="text-center font-24 weight600 text-uppercase">Select</h3>
				</div>
				<div class="card-body">
					<p class="weight400 mb0">More comfort</p>
					<p class="weight400 mb5">Reasonably priced</p>
					<p class="weight400 color-blue">
						<i class="bx bxs-star"></i> <i class="bx bxs-star"></i> <i class="bx bxs-star"></i>
					</p>
					<p class="mb0"><span class="font-20">&#x20B9;</span><span class="font-24 weight600">2450</span></p>
					<p class="mb0">onwards</p>
					<div class="radio-style3">
                        <div class="radio">
                            <input id="test8" value="1" type="radio" name="cabsegmentation" class="bkg_user_trip_type">
                            <label for="test8"></label>
                        </div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-12 text-center">
			<button type="button" class="btn btn-primary mr-1 mb-1 text-uppercase">book now</button> <button type="button" class="btn btn-secondary mr-1 mb-1 text-uppercase">Save this Quote</button>
		</div>
	</div>
</div>

<hr>
<div class="container mb-2">
	<div class="row">
		<div class="col-12 text-center">
			<h6 class="gothic">
				Looking for available cabs for<br>
				Agra to Delhi at february 2, 2022 at 9:15 PM</h6>
		</div>
		<div class="col-12 text-center weight600 animated-chart">
			<!--                    <div class="animation-chart ct-golden-section"></div>-->
			<div class="svg-item">
				<svg width="100%" height="100%" viewBox="0 0 40 40" class="donut">
				<circle class="donut-hole" cx="20" cy="20" r="15.91549430918954" fill="#fff"></circle>
				<circle class="donut-ring" cx="20" cy="20" r="15.91549430918954" fill="transparent" stroke-width="3.5"></circle>
    <circle class="donut-segment donut-segment-2" cx="20" cy="20" r="15.91549430918954" fill="transparent" stroke-width="3.5" stroke-dasharray="100 00" stroke-dashoffset="25"></circle>
				<g class="donut-text donut-text-1">

				<text y="50%" transform="translate(0, 2)">
				<tspan x="50%" text-anchor="middle" class="donut-percent">15</tspan>   
				</text>
				<text y="60%" transform="translate(0, 2)">
        <tspan x="50%" text-anchor="middle" class="donut-data">minute</tspan>   
				</text>
				</g>
				</svg>
			</div>
			<p>Just 2 minutes</p>
			<p>Your booking request ID is<br>
				OW2022587668</p>
			<p class="mt-5 color-green">
				x cabs found
			</p>
		</div>
		<div class="col-xl-12 text-center">
			<button type="button" class="btn btn-primary pl-5 pr-5 text-uppercase">next</button>
		</div>
	</div>
</div>

<hr>

<div class="container mb-2">
	<div class="row">
		<div class="col-12 text-center">
			<h6 class="gothic">Request ID: OW202254879</h6>
			<p class="color-gray font-12 lineheight14">Sedan (Economy tier) to travel 215 km<br>
				from Agra to Delhi on feb 2, 2022 at 9:15 pm</p>
		</div>
		<div class="col-12 text-center">
                    <p class="font-22 weight600 mb-0">X cabs found...</p>
                    <p class="mb-0 font-18">The offered quotes expire in</p>
                    <div class="mb-3"><span id="time" class="time-widget-1">06:00</span></div>
		</div>
	</div>
    <div class="row">
        <div class="col-12 col-xl-4">
            <div class="card widget-user-details">
                <div class="card-header">
                    <div class="card-title-details d-flex">
						<div class="checkbox lineheight14">
							<input type="checkbox" class="checkbox-input" id="checkbox2">
							<label for="checkbox2"><span class="color-gray font-14 weight400">cab arrives in</span><br><span class="font-18">5 min</span></label>
						</div>
                        <div>
                        </div>
                    </div>
                    <div class="heading-elements">
                        <span class="font-20">&#x20B9;</span><span class="font-24 weight600">2450</span>
                    </div>
                </div>
                <div class="card-body d-flex">
                    <div class="d-inline-flex mr-xl-2" style="position: relative;">
                        <div class="profit-content ml-50 mt-50">
                            <small class="text-muted font-14">Operator</small>
                            <h5 class="mb-0 weight500">V-XXXXXX</h5>
                            <p>173 trip on this route</p>
                        </div>
                        <div class="resize-triggers"><div class="expand-trigger"><div style="width: 91px; height: 65px;"></div></div><div class="contract-trigger"></div>
                        </div>
                    </div>
                    <div class="d-inline-flex" style="position: relative;">
                        <div class="profit-content mt-4">
                            <p>Etios - 2016 Model </p>
                        </div>
                        <div class="resize-triggers"><div class="expand-trigger"><div style="width: 95px; height: 65px;"></div></div><div class="contract-trigger"></div></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-4">
            <div class="card widget-user-details">
                <div class="card-header">
                    <div class="card-title-details d-flex">
						<div class="checkbox lineheight14">
							<input type="checkbox" class="checkbox-input" id="checkbox2">
							<label for="checkbox2"><span class="color-gray font-14 weight400">cab arrives in</span><br><span class="font-18">5 min</span></label>
						</div>
                        <div>
                        </div>
                    </div>
                    <div class="heading-elements">
                        <span class="font-20">&#x20B9;</span><span class="font-24 weight600">2450</span>
                    </div>
                </div>
                <div class="card-body d-flex">
                    <div class="d-inline-flex mr-xl-2" style="position: relative;">
                        <div class="profit-content ml-50 mt-50">
                            <small class="text-muted font-14">Operator</small>
                            <h5 class="mb-0 weight500">V-XXXXXX</h5>
                            <p>173 trip on this route</p>
                        </div>
                        <div class="resize-triggers"><div class="expand-trigger"><div style="width: 91px; height: 65px;"></div></div><div class="contract-trigger"></div>
                        </div>
                    </div>
                    <div class="d-inline-flex" style="position: relative;">
                        <div class="profit-content mt-4">
                            <p>Etios - 2016 Model </p>
                        </div>
                        <div class="resize-triggers"><div class="expand-trigger"><div style="width: 95px; height: 65px;"></div></div><div class="contract-trigger"></div></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-4">
            <div class="card widget-user-details">
                <div class="card-header">
                    <div class="card-title-details d-flex">
						<div class="checkbox lineheight14">
							<input type="checkbox" class="checkbox-input" id="checkbox2">
							<label for="checkbox2"><span class="color-gray font-14 weight400">cab arrives in</span><br><span class="font-18">5 min</span></label>
						</div>
                        <div>
                        </div>
                    </div>
                    <div class="heading-elements">
                        <span class="font-20">&#x20B9;</span><span class="font-24 weight600">2450</span>
                    </div>
                </div>
                <div class="card-body d-flex">
                    <div class="d-inline-flex mr-xl-2" style="position: relative;">
                        <div class="profit-content ml-50 mt-50">
                            <small class="text-muted font-14">Operator</small>
                            <h5 class="mb-0 weight500">V-XXXXXX</h5>
                            <p>173 trip on this route</p>
                        </div>
                        <div class="resize-triggers"><div class="expand-trigger"><div style="width: 91px; height: 65px;"></div></div><div class="contract-trigger"></div>
                        </div>
                    </div>
                    <div class="d-inline-flex" style="position: relative;">
                        <div class="profit-content mt-4">
                            <p>Etios - 2016 Model </p>
                        </div>
                        <div class="resize-triggers"><div class="expand-trigger"><div style="width: 95px; height: 65px;"></div></div><div class="contract-trigger"></div></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-4">
            <div class="card widget-user-details">
                <div class="card-header">
                    <div class="card-title-details d-flex">
						<div class="checkbox lineheight14">
							<input type="checkbox" class="checkbox-input" id="checkbox2">
							<label for="checkbox2"><span class="color-gray font-14 weight400">cab arrives in</span><br><span class="font-18">5 min</span></label>
						</div>
                        <div>
                        </div>
                    </div>
                    <div class="heading-elements">
                        <span class="font-20">&#x20B9;</span><span class="font-24 weight600">2450</span>
                    </div>
                </div>
                <div class="card-body d-flex">
                    <div class="d-inline-flex mr-xl-2" style="position: relative;">
                        <div class="profit-content ml-50 mt-50">
                            <small class="text-muted font-14">Operator</small>
                            <h5 class="mb-0 weight500">V-XXXXXX</h5>
                            <p>173 trip on this route</p>
                        </div>
                        <div class="resize-triggers"><div class="expand-trigger"><div style="width: 91px; height: 65px;"></div></div><div class="contract-trigger"></div>
                        </div>
                    </div>
                    <div class="d-inline-flex" style="position: relative;">
                        <div class="profit-content mt-4">
                            <p>Etios - 2016 Model </p>
                        </div>
                        <div class="resize-triggers"><div class="expand-trigger"><div style="width: 95px; height: 65px;"></div></div><div class="contract-trigger"></div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12 text-center mb-4">
            <button type="button" class="btn btn-primary pl-5 pr-5 text-uppercase">next</button>
        </div>
    </div>
</div>


<hr>

<div class="container mb-2">
	<div class="row">
		<div class="col-12 text-center">
			<p class="mb-4"><img src="/images/img-2022/location.svg" width="70" alt=""></p>
		</div>
		<div class="col-12 col-lg-8 offset-lg-2 mt-3">
			<div class="row">
                        <div class="col-12 col-xl-6 mb-2">
                            <p class="mb5"><small class="form-text">Traveller Name</small></p>
                            <fieldset class="form-group position-relative has-icon-left">
                                <input type="text" class="form-control" id="iconLeft" placeholder="Enter traveller name">
                                <div class="form-control-position">
                                    <i class="bx bx-user"></i>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                    <div class="row">
                        
				<div class="col-12 col-xl-6">
					<label for="iconLeft">We need your pickup address</label>
					<p class="mb5"><small class="form-text">Location</small></p>
					<fieldset class="form-group position-relative has-icon-left">
						<input type="text" class="form-control" id="iconLeft" placeholder="Use my current location">
						<div class="form-control-position">
							<i class="fas fa-map-marker-alt"></i>
						</div>
					</fieldset>
				</div>
				<div class="col-12 col-xl-6">
					<label for="iconLeft">Your drop address</label>
					<p class="mb5"><small class="form-text">Location</small></p>
					<fieldset class="form-group position-relative has-icon-left">
						<input type="text" class="form-control" id="iconLeft" placeholder="Use my current location">
						<div class="form-control-position">
							<i class="fas fa-map-marker-alt"></i>
						</div>
					</fieldset>
				</div>
			</div>
		</div>
	</div>
    <div class="row">
        <div class="col-xl-12 text-center mt-5 mb-4">
            <button type="button" class="btn btn-primary pl-5 pr-5 text-uppercase">next</button>
        </div>
    </div>
</div>


<hr>


<div class="container mb-2">
	<div class="row">
		<div class="col-12 text-center">
			<h2 class="gothic weight600">Order Summary</h2>
			<div class="badge badge-pill badge-primary mr-1 mb-1">QT202516660</div>
		</div>
		<div class="col-12 col-lg-10 offset-lg-1 mt-3">
			<div class="row">
				<div class="col-12 widget-liststyle">
					<div class="widget-img"><img src="images/cabs/car-ertiga.png" width="220" alt=""></div>
					<ul class="list-unstyled">
						<li>Traveller Name: <span class="text-bold-500">Sudipta Mitra</span></li>
						<li>Email: <span class="text-bold-500">sudiptaa008@gmail.com</span></li>
						<li>Phone: <span class="text-bold-500">+91 8017879076</span></li>
					</ul>
					<h6 class="weight500 mt-2">Itinerary summary</h6>
					<ul class="list-unstyled">
						<li class="pb-25">Delhi <i class="fas fa-long-arrow-alt-right"></i> Agra by Value Sedan</li>
						<li class="pb-25">Oneway journey (212km Est.1 hrs 30 min +/-30min)</li>
						<li class="pb-25">Trip starting on 13th Feb 2022, Sunday, 12:16 PM</li>
						<li class="pb-25">Trip expected to complete on 13th Feb 2022, Sunday</li>
					</ul>
					<ul>
						<li></li>
						<li></li>
						<li></li>
						<li></li>
					</ul>
				</div>
			</div>
			<div class="row card-style accordion-widget">
				<div class="col-12 mt-2" id="accordion-icon-wrapper">
                    <div class="accordion collapse-icon accordion-icon-rotate" id="accordionWrapa2" data-toggle-hover="true">
                        <div class="card collapse-header">
                            <div id="heading5" class="card-header collapsed" data-toggle="collapse" data-target="#accordion5" aria-expanded="false" aria-controls="accordion5" role="tablist">
                                <span class="collapse-title">
                                    <span class="align-middle">No special services requested</span>
                                    <p class="font-12 mb0 text-muted">Senior citizen traveling, Kids on board</p>
                                </span>
                                
                            </div>
                            <div id="accordion5" role="tabpanel" data-parent="#accordionWrapa2" aria-labelledby="heading5" class="collapse" style="">
                                <div class="card-body">

                                    <p>Please provide additional information to help us to serve you better.</p>
                                    <ul class="list-unstyled mb-0">
                                        <li class="d-inline-block mr-2 mb-1">
                                            <fieldset>
                                                <div class="checkbox">
                                                    <input type="checkbox" class="checkbox-input" id="checkbox3">
                                                    <label for="checkbox3">Senior citizen traveling</label>
                                                </div>
                                            </fieldset>
                                        </li>
                                        <li class="d-inline-block mr-2 mb-1">
                                            <fieldset>
                                                <div class="checkbox">
                                                    <input type="checkbox" class="checkbox-input" id="checkbox4">
                                                    <label for="checkbox4">Kids on board</label>
                                                </div>
                                            </fieldset>
                                        </li>
                                        <li class="d-inline-block mr-2 mb-1">
                                            <fieldset>
                                                <div class="checkbox">
                                                    <input type="checkbox" class="checkbox-input" id="checkbox5">
                                                    <label for="checkbox5">Women traveling</label>
                                                </div>
                                            </fieldset>
                                        </li>
                                        <li class="d-inline-block mr-2 mb-1">
                                            <fieldset>
                                                <div class="checkbox">
                                                    <input type="checkbox" class="checkbox-input" id="checkbox6">
                                                    <label for="checkbox6">English-speaking driver required</label>
                                                </div>
                                            </fieldset>
                                        </li>
                                        <li class="d-inline-block mr-2 mb-1">
                                            <fieldset>
                                                <div class="checkbox">
                                                    <input type="checkbox" class="checkbox-input" id="checkbox7">
                                                    <label for="checkbox7">Other</label>
                                                </div>
                                            </fieldset>
                                        </li>
                                        <li class="d-inline-block mr-2 mb-1">
                                            <fieldset>
                                                <div class="checkbox">
                                                    <input type="checkbox" class="checkbox-input" id="checkbox8">
                                                    <label for="checkbox8">Add a journey break (â‚¹150/30mins)</label>
                                                </div>
                                            </fieldset>
                                        </li>
                                        <li class="d-inline-block mr-2 mb-1">
                                            <fieldset>
                                                <select class="form-control" id="basicSelect">
                                                    <option>IT</option>
                                                    <option>Blade Runner</option>
                                                    <option>Thor Ragnarok</option>
                                                </select>
                                            </fieldset>
                                            <span class="font-11">First 15min free. Unplanned journey breaks are not allowed for one-way trips</span>
                                        </li>

                                    </ul>
                                    <h5 class="mt-3">Additional Details</h5>
                                    <ul class="list-unstyled mb-0">
                                        <li class="d-inline-block mr-2 mb-1 radio-style4">
                                            <p class="mb5">Personal Or Business Trip?</p>

											<div class="radio inline-block mr-1">
												<input id="Users_search_0" value="1" type="radio" name="checkaccount" class="bkg_user_trip_type" checked="checked">	
												<label for="Users_search_0">Personal</label>
											</div>
											<div class="radio inline-block">
												<input id="Users_search_1" value="1" type="radio" name="checkaccount" class="bkg_user_trip_type" checked="checked">	
												<label for="Users_search_1">Business</label>
											</div>

                                        </li>
                                        <li class="d-inline-block mr-2 mb-1">
											<div class="form-group">
                                                <label for="helpInputTop">Number of Passengers *</label>
                                                <input type="text" class="form-control" id="helpInputTop">
                                            </div>
                                        </li>
                                        <li class="d-inline-block mr-2 mb-1">
											<div class="form-group">
												<label>Number of Large Bags</label>
                                                <select class="form-control" id="basicSelect">
                                                    <option>IT</option>
                                                    <option>Blade Runner</option>
                                                    <option>Thor Ragnarok</option>
                                                </select>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="card collapse-header">
                            <div id="heading6" class="card-header" data-toggle="collapse" role="button" data-target="#accordion6" aria-expanded="false" aria-controls="accordion6">
                                <span class="collapse-title">
                                    <span class="align-middle">24 Hour cancellation policy</span>
                                </span>
                            </div>
                            <div id="accordion6" role="tabpanel" data-parent="#accordionWrapa2" aria-labelledby="heading6" class="collapse" aria-expanded="false">
                                <div class="card-body">
                                    <p>Cancellation information</p>
                                    <div class="row">
                                        <div class="col-12 col-xl-4">
											<div class="card border shadow-none mb-1 app-file-info">
												<div class="card-header gradient-1 p5"></div>
												<div class="card-body p-50">
													<div class="app-file-recent-details mt-1">
														<div class="app-file-name font-weight-bold text-center mb-1">Free cancellation period</div>
														<div class="d-inline-block font-11">01 Jan 2022 05:30 am</div>
														<div class="d-inline-block font-11"><i class="fas fa-arrow-right"></i></div>
														<div class="d-inline-block font-11">13 Feb 2022 03:00 am</div>
													</div>
												</div>
											</div>
										</div>
										<div class="col-12 col-xl-4">
											<div class="card border shadow-none mb-1 app-file-info">
												<div class="card-header gradient-2 p5"></div>
												<div class="card-body p-50">
													<div class="app-file-recent-details mt-1">
														<div class="app-file-name font-weight-bold text-center mb-1">Cancellation Charge: â‚¹682</div>
														<div class="d-inline-block font-11">01 Jan 2022 05:30 am</div>
														<div class="d-inline-block font-11"><i class="fas fa-arrow-right"></i></div>
														<div class="d-inline-block font-11">13 Feb 2022 03:00 am</div>
													</div>
												</div>
											</div>
										</div>
										<div class="col-12 col-xl-4">
											<div class="card border shadow-none mb-1 app-file-info">
												<div class="card-header gradient-3 p5"></div>
												<div class="card-body p-50">
													<div class="app-file-recent-details mt-1">
														<div class="app-file-name font-weight-bold text-center mb-1">No Refund</div>
														<div class="d-inline-block font-11">01 Jan 2022 05:30 am</div>
														<div class="d-inline-block font-11"><i class="fas fa-arrow-right"></i></div>
														<div class="d-inline-block font-11">After this</div>
													</div>
												</div>
											</div>
										</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card collapse-header">
                            <div id="heading7" class="card-header" data-toggle="collapse" role="button" data-target="#accordion7" aria-expanded="false" aria-controls="accordion7">
                                <span class="collapse-title">
                                    <span class="align-middle">Fare inclusions/exclusions</span>
                                </span>
                            </div>
                            <div id="accordion7" role="tabpanel" data-parent="#accordionWrapa2" aria-labelledby="heading7" class="collapse" aria-expanded="false">
                                <div class="card-body" style="display: flex; flex-wrap: wrap;">

                                    <div class="row d-flex justify-content-start mb-3">
                                        <div class="col-md-4 col-sm-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <h5 class="font-16 weight500"><img src="/images/img-2022/check2.svg" width="15" alt="" class="mr5"> TOLL TAXES</h5>
                                                        <p>Our estimate of toll charges for travel on this route are &#x20B9;415. Toll taxes (even if amount is different) is already included in the trip cost</p>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4 col-sm-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <h5 class="font-16 weight500"><img src="/images/img-2022/check2.svg" width="15" alt="" class="mr5"> STATE TAXES</h5>
                                                        <p>Our estimate of State Tax for travel on this route are &#x20B9;100. State Taxes (even if amount is different) is already included in the trip cost</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4 col-sm-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <h5 class="font-16 weight500"><img src="/images/img-2022/close.svg" width="15" alt="" class="mr5"> AIRPORT ENTRY CHARGES</h5>
                                                        <p>Our estimate of airport entry charges on this route are &#x20B9;0 . Any charges incurred is payable by customer.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <p><img src="/images/img-2022/close.svg" width="15" alt="" class="mr5"> NIGHT PICKUP CHARGES (10 PM - 6 AM) - Rs.250</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <p><img src="/images/img-2022/close.svg" width="15" alt="" class="mr5"> NIGHT DROP CHARGES (10 PM - 6 AM) - Rs.250</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <p><img src="/images/img-2022/close.svg" width="15" alt="" class="mr5"> WAITING CHARGES</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <p><img src="/images/img-2022/close.svg" width="15" alt="" class="mr5"> EXTRA CHARGES ( &#x20B9;11 / KM beyond 215 KMS).</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <p><img src="/images/img-2022/close.svg" width="15" alt="" class="mr5"> GREEN TAX</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <p><img src="/images/img-2022/close.svg" width="15" alt="" class="mr5"> MCD</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <p><img src="/images/img-2022/close.svg" width="15" alt="" class="mr5"> ENTRY TAXES / CHARGES</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <h5 class="font-16 weight500"><img src="/images/img-2022/close.svg" width="15" alt="" class="mr5"> PARKING CHARGES</h5>
                                                        <p>Customer will directly pay for parking charges after the total parking cost for the trip exceeds â‚¹0. Driver must upload all parking receipts for payments made by drive.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <p>FINAL OUTSTANDING SHALL BE COMPUTED AFTER TRIP COMPLETION. ADDITIONAL AMOUNT, IF ANY, MAY BE PAID IN CASH TO THE DRIVER DIRECTLY.</p>
                                </div>
                            </div>
                        </div>
                        <div class="card collapse-header">
                            <div id="heading8" class="card-header" data-toggle="collapse" role="button" data-target="#accordion8" aria-expanded="false" aria-controls="accordion8">
                                <span class="collapse-title">
                                    <span class="align-middle">Coupons/discounts applied</span>
                                </span>
                            </div>
                            <div id="accordion8" role="tabpanel" data-parent="#accordionWrapa2" aria-labelledby="heading8" class="collapse" aria-expanded="false">
                                <div class="card-body">
                                    <div class="d-inline-block mr-2 mb-1 radio-style4">
                                        <div class="radio">
                                            <input id="test9" value="1" type="radio" name="cabsegmentation" class="bkg_user_trip_type">
                                            <label for="test9">Apply Promo</label>
                                        </div>
                                    </div>
                                    <div class="d-inline-block mr-2 mb-1 radio-style4">
                                        <div class="radio">
                                            <input id="test10" value="1" type="radio" name="cabsegmentation" class="bkg_user_trip_type">
                                            <label for="test10">Enter Gozo Coins</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-9 col-md-4 col-sm-12 pr-0">
                                            <div class="form-group">
                                                <input type="text" class="form-control copy-to-clipboard" id="copy-to-clipboard-input" value="Enter promo">
                                            </div>
                                        </div>
                                        <div class="col-3 col-md-2 col-sm-12">
                                            <button class="btn btn-primary copy-btn">Apply</button>
                                        </div>
                                    </div>
                                    <h5 class="mt-3"><b>OFFERS</b></h5>
                                    <div class="row">
                                        <div class="col-12 col-xl-4 d-inline-block ">
                                            <div class="mb-1">
												<div class="card border shadow-none mb-1 app-file-info">
													<div class="card-body p-1">
														<div class="app-file-recent-details">
															<div class="app-file-name font-weight-bold mb5">TRYGZ</div>
															<div class="d-inline-block">Book yourself. Save money on your first 5 
																bookings. 7% Cash | 14% Coins | T&C Apply</div>
															<div class="text-right"><a href="#" class="btn btn-secondary round mr-1 mt5">Apply</a></div>
														</div>
													</div>
												</div>
											</div>
                                        </div>
                                        <div class="col-12 col-xl-4 d-inline-block">
                                            <div class=" mb-1">
												<div class="card border shadow-none mb-1 app-file-info">
													<div class="card-body p-1">
														<div class="app-file-recent-details">
															<div class="app-file-name font-weight-bold mb5">TRYGZ</div>
															<div class="d-inline-block">Book yourself. Save money on your first 5 
																bookings. 7% Cash | 14% Coins | T&C Apply</div>
															<div class="text-right"><a href="#" class="btn btn-secondary round mr-1 mt5">Apply</a></div>
														</div>
													</div>
												</div>
											</div>
                                        </div>
                                        <div class="col-12 col-xl-4 d-inline-block">
                                            <div class=" mb-1">
												<div class="card border shadow-none mb-1 app-file-info">
													<div class="card-body p-1">
														<div class="app-file-recent-details">
															<div class="app-file-name font-weight-bold mb5">TRYGZ</div>
															<div class="d-inline-block">Book yourself. Save money on your first 5 
																bookings. 7% Cash | 14% Coins | T&C Apply</div>
															<div class="text-right"><a href="#" class="btn btn-secondary round mr-1 mt5">Apply</a></div>
														</div>
													</div>
												</div>
											</div>
                                        </div>
                                        <div class="col-12">
											<div class="alert alert-success mb-2" role="alert">
												Promo TRYGZ applied successfully. You will get discount worth &#x20B9;148 and Gozo Coins worth &#x20B9;295.* You may redeem these Gozo Coins against your future bookings with us.*T&C Apply
											</div>

                                        </div>
                                        <div class="col-12">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h4 class="font-16">Applied discount code : <b>TRYGZ</b></h4>
                                                <a href="#" class="btn btn-danger mb-1 btn-sm">Remove code</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card collapse-header">
                            <div id="heading9" class="card-header" data-toggle="collapse" role="button" data-target="#accordion9" aria-expanded="false" aria-controls="accordion9">
                                <span class="collapse-title">
                                    <span class="align-middle">Boarding checks</span>
                                </span>
                            </div>
                            <div id="accordion9" role="tabpanel" data-parent="#accordionWrapa2" aria-labelledby="heading9" class="collapse" aria-expanded="false">
                                <div class="card-body list-3styled">
                                    <ul>
                                        <li>CHECK IDENTIFICATION OF THE DRIVER</li>
                                        <li>CONFIRM THE LICENSE PLATE OF YOUR CAR.</li>
                                        <li>DO NOT RIDE IF THE VEHICLE IS NOT COMMERCIALLY LICENSED. (YELLOW LICENSE PLATE)</li>
                                        <li>DO NOT RIDE IF THE VEHICLE AND/OR DRIVER INFORMATION DO NOT MATCH THE INFORMATION PROVIDED BY GOZO.</li>
                                        <li>GOZO SHALL NOT BE RESPONSIBLE OR LIABLE IN ANY MANNER IF YOU CHOOSE TO RIDE IN A VEHICLE THAT IS NOT COMMERCIALLY LICENSED OR RIDE 
											WITH A DRIVER OR VEHICLE OTHER THAN THE ONE THAT WE HAVE ASSIGNED TO YOU.</li>
                                        <li>YOU MAY ASK THE DRIVER FOR IDENTIFICATION TO ENSURE THAT YOU ARE RIDING WITH THE CORRECT DRIVER. DRIVERS MAY ASK FOR YOUR 
											IDENTIFICATION TOO. FAILURE TO PROVIDE IDENTIFICATION WILL MAKE YOUR BOOKING SUBJECT TO CANCELLATION AT YOUR COST.</li>
                                        <li>PROVIDE THE ONE-TIME PASSWORD (OTP) TO THE DRIVER TO START THE TRIP.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="card collapse-header">
                            <div id="heading10" class="card-header" data-toggle="collapse" role="button" data-target="#accordion10" aria-expanded="false" aria-controls="accordion10">
                                <span class="collapse-title">
                                    <span class="align-middle">On trip do's & don'ts</span>
                                </span>
                            </div>
                            <div id="accordion10" role="tabpanel" data-parent="#accordionWrapa2" aria-labelledby="heading10" class="collapse" aria-expanded="false">
                                <div class="card-body list-3styled">
                                    <ul>
                                        <li>YOU HAVE BOOKED A PRICE AND TIME-OPTIMIZED POINT-TO-POINT JOURNEY SPECIFIED BY THE PICKUP ADDRESS, DROP ADDRESS AND ROUTING INSTRUCTIONS (USE OF SPECIFIED ROUTES). This is a cost-optimized transport trip, no sightseeing or unplanned stops are included.</li>
                                        <li>YOU HAVE BOOKED OUR ECO-FRIENDLY / CNG SERVICE TIER. YOUR TRIP WILL BE SERVED WITH A *VEHICLE FUELED BY CNG*</li>
                                        <li>DRIVER WILL WAIT FOR MAX 15MIN AT PICKUP TIME (BEFORE TRIP START) AFTER WHICH TRIP MAY BE CANCELED OR RESCHEDULED AT YOUR COST.</li>
                                        <li>WHEN YOU BOOK OUR VALUE OR CNG SERVICE TIERS, WE ONLY PROMISE TO PROVIDE A VEHICLE OF A CERTAIN CATEGORY (COMPACT, SEDAN, SUV etc). WE DO NOT GUARANTEE A SPECIFIC MODEL OF CAR. TO CHOOSE A SPECIFIC VEHICLE MODEL YOU MUST BOOK IN PREMIUM SERVICE TIER.</li>
                                        <li>ON-JOURNEY STOPS OR WAYPOINTS ARE NOT ALLOWED UNLESS PART OF ITINERARY AND WRITTEN IN THIS CONFIRMATION EMAIL.</li>
                                        <li>A SINGLE 15-MINUTE COMPLIMENTARY ON JOURNEY BREAK IS INCLUDED FOR POINT-TO-POINT TRIPS LONGER THAN 4 HOURS.</li>
                                        <li>30 MIN REST FOR DRIVER AND/OR CHANGE OF VEHICLE FOR MORE THAN 4 HOURS CONTINUOUS DRIVE IS CUSTOMARY. THIS IS TO ENSURE SAFE DRIVING.</li>
                                        <li>DRIVABLE KMS IN YOUR QUOTE ARE ESTIMATES. YOU WILL BE BILLED FOR THE ACTUAL DISTANCE TRAVELLED. IF YOU HAVE NOT PROVIDED THE EXACT ADDRESS BEFORE YOUR PICKUP THE BOOKING AMOUNT IS ESTIMATE FOR TRAVEL FROM CITY CENTER TO CITY CENTER. ACTUAL DISTANCE TRAVELLED MAY DIFFER BASED ON EXACT ADDRESSES AND/OR ITINERARY.</li>
                                        <li>DRIVER MAY NOT AGREE TO ANY CHANGES TO ROUTE OR ITINERARY. ANY CHANGES, ADDITIONS OF WAYPOINTS, PICK-UP POINTS, DROP POINTS, HALTS, DESTINATION CITIES OR SIGHTSEEING SPOTS ARE ABSOLUTELY NOT AUTHORIZED UNLESS THEY ARE ADDED TO YOUR ITINERARY AND CONFIRMED IN WRITING THROUGH A BOOKING CONFIRMATION EMAIL. CHANGES TO ITINERARY MAY LEAD TO PRICE CHANGES.</li>
                                        <li>AC MAY BE SWITCHED OFF ONLY IN HILLY REGIONS (HIGH ALTITUDE) TO PREVENT ENGINE OVERLOAD.</li>
                                        <li>"YOU AGREE TO MAKE PAYMENTS FOR YOUR TRIP AS PER THE FOLLOWING PAYMENT SCHEDULE. (1) ADVANCE PAYMENT TO GOZO AT TIME OF BOOKING. (2) 50% OF REMAINING PAYABLE AMOUNT TO DRIVER UPON BOARDING (3) DAILY PART PAYMENTS OF REMAINING AMOUNT TO DRIVER IN EQUAL PARTS "</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="card collapse-header">
                            <div id="heading11" class="card-header" data-toggle="collapse" role="button" data-target="#accordion11" aria-expanded="false" aria-controls="accordion11">
                                <span class="collapse-title">
                                    <span class="align-middle">Travel advisories & restrictions</span>
                                </span>
                            </div>
                            <div id="accordion11" role="tabpanel" data-parent="#accordionWrapa2" aria-labelledby="heading11" class="collapse" aria-expanded="false">
                                <div class="card-body">
                                    Coming Soon!
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
			</div>
			<div class="row mt-2">
				<div class="col-5">
					<p class="mb0 text-uppercase weight500 lineheight14">Total fare</p>
					<p class="mb0"><span class="font-20">&#x20B9;</span><span class="font-24 weight600">2779</span></p>
				</div>
				<div class="col-7 text-right"><a href="#" class="btn mt5 mb-1 btn-primary text-uppercase">Proceed to Pay</a></div>
			</div>
		</div>
	</div>

</div>


<hr>


<div class="container mb-2">
	<div class="row">
		<div class="col-12 text-center">
			<h2 class="gothic weight600">Payment Options</h2>
		</div>
		<div class="col-12 col-lg-6 offset-lg-3 mt-3">
			<div class="row">
				<div class="col-12 widget-liststyle mb-1">
					<div class="radio-style4">
						<div class="float-right">
							<span class="font-24">&#x20B9;</span><span class="font-24 weight600">700</span>
						</div>
						<div class="radio">
                                    <input id="test13d" value="1" type="radio" name="cabsegmentation" class="bkg_user_trip_type">
                                    <label for="test13d">Part payment (30% advance)</label>
						</div>
					</div>
				</div>
				<div class="col-12 widget-liststyle">
					<div class="radio-style4">
						<div class="float-right">
							<span class="font-24">&#x20B9;</span><span class="font-24 weight600">2779</span>
						</div>
						<div class="radio">
                                    <input id="test13v" value="1" type="radio" name="cabsegmentation" class="bkg_user_trip_type">
                                    <label for="test13v">Full payment (100% advance)</label>
						</div>
					</div>
				</div>
				<div class="col-12 mt-3">
					<h4 class="text-center weight500">I will pay with</h4>
				</div>
				<div class="col-12 col-xl-10 offset-xl-1">
					<div class="card border shadow-none mb-1 app-file-info">
						<div class="card-body p-1 text-center font-16">
							Credit/Debit Card | Net Banking | Wallet | UPI
						</div>
					</div>
				</div>
				<div class="col-12 col-xl-10 offset-xl-1">
					<div class="card border shadow-none mb-1 app-file-info">
						<div class="card-body p-1 text-center font-16">
							<img src="images/paytm.png" alt="" width="120">
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>

<hr>


<div class="container mb-2">
	<div class="row">
		<div class="col-12 text-center">
			<h2 class="gothic weight600">Order Summary</h2>
			<div class="badge badge-pill badge-primary mr-1 mb-1">QT202516660</div>
		</div>
		<div class="col-12 col-lg-10 offset-lg-1 mt-3">
			<div class="row">
				<div class="col-12 widget-liststyle">
					<ul class="list-unstyled">
						<li>Traveller Name: <span class="text-bold-500">Sudipta Mitra</span></li>
						<li>Email: <span class="text-bold-500">sudiptaa008@gmail.com</span></li>
						<li>Phone: <span class="text-bold-500">+91 8017879076</span></li>
					</ul>
				</div>
			</div>
			<div class="row card-style accordion-widget">
				<div class="col-12 mt-2" id="accordion-icon-wrapper1">
                    <div class="accordion collapse-icon accordion-icon-rotate" id="accordionWrapa21" data-toggle-hover="true">
                        <div class="card collapse-header">
                            <div id="heading121" class="card-header collapsed" data-toggle="collapse" data-target="#accordion121" aria-expanded="false" aria-controls="accordion121" role="tablist">
                                <span class="collapse-title">
                                    <span class="align-middle">Billing Details</span>
                                </span>
                            </div>
                            <div id="accordion121" role="tabpanel" data-parent="#accordionWrapa21" aria-labelledby="heading121" class="collapse" style="">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="text-right edit-icons mr-1">
                                            <a href="#" class="float-right">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                        </div>
                                        <div class="col-12 col-xl-8">
                                            <div class="row">
                                                <div class="col-4">
                                                    <p class="mb0"><span class="font-22">&#x20B9;</span><span class="font-24 weight600">2779</span></p>
                                                    <p class="mb0 weight400 lineheight14">Total fare</p>
                                                </div>
                                                <div class="col-4 text-center">
                                                    <p class="mb0"><span class="font-22">&#x20B9;</span><span class="font-24 weight600">700</span></p>
                                                    <p class="mb0 weight400 lineheight14">Paid</p>
                                                </div>
                                                <div class="col-4 text-right">
                                                    <p class="mb0"><span class="font-22">&#x20B9;</span><span class="font-24 weight600">2000</span></p>
                                                    <p class="mb0 weight400 lineheight14">Pay to driver</p>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="d-flex justify-content-between my-1">
                                                        <div class="sales-info d-flex align-items-center">
                                                            <div class="sales-info-content">
                                                                <h6 class="mb-0">Distance quoted of the trip:</h6>
                                                                <small class="text-muted">(based on pickup and drop addresses provided)</small>
                                                            </div>
                                                        </div>
                                                        <h6 class="mb-0 text-right">215 Km<br><small class="text-muted">(Charges after 215 Km @  ï…–11/km)</small></h6>
                                                    </div>
                                                    <div class="d-flex justify-content-between my-1">
                                                        <div class="sales-info d-flex align-items-center">
                                                            <div class="sales-info-content">
                                                                <h6 class="mb-0">Total days for the trip:</h6>
                                                            </div>
                                                        </div>
                                                        <h6 class="mb-0 text-right">1 days</h6>
                                                    </div>
                                                    <div class="d-flex justify-content-between my-1">
                                                        <div class="sales-info d-flex align-items-center">
                                                            <div class="sales-info-content">
                                                                <h6 class="mb-0">Base Fare:</h6>
                                                            </div>
                                                        </div>
                                                        <h6 class="mb-0 text-right">&#x20B9;2046</h6>
                                                    </div>
                                                    <div class="d-flex justify-content-between my-1">
                                                        <div class="sales-info d-flex align-items-center">
                                                            <div class="sales-info-content">
                                                                <h6 class="mb-0">Toll Tax:</h6>
                                                            </div>
                                                        </div>
                                                        <h6 class="mb-0 text-right">&#x20B9;415</h6>
                                                    </div>
                                                    <div class="d-flex justify-content-between my-1">
                                                        <div class="sales-info d-flex align-items-center">
                                                            <div class="sales-info-content">
                                                                <h6 class="mb-0">Other Taxes:</h6>
                                                                <small class="text-muted">(Including State Tax / Green Tax etc)</small>
                                                            </div>
                                                        </div>
                                                        <h6 class="mb-0 text-right">&#x20B9;100</h6>
                                                    </div>
                                                    <div class="d-flex justify-content-between my-1">
                                                        <div class="sales-info d-flex align-items-center">
                                                            <div class="sales-info-content">
                                                                <h6 class="mb-0">GST (@5%):</h6>
                                                            </div>
                                                        </div>
                                                        <h6 class="mb-0 text-right">&#x20B9;102</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
			</div>
			<div class="row mt-2">
				<div class="col-5">
					<p class="mb0 text-uppercase weight500 lineheight14">Total fare</p>
					<p class="mb0"><span class="font-20">&#x20B9;</span><span class="font-24 weight600">2779</span></p>
				</div>
				<div class="col-7 text-right"><a href="#" class="btn mt5 mb-1 btn-primary text-uppercase">Proceed to Pay</a></div>
			</div>
		</div>
	</div>

</div>



<hr>




<div class="container mb-2">
	<div class="row">
		<div class="col-12 text-center mt-3">
			<h2 class="gothic weight600">Gozo Now</h2>
		</div>
		<div class="col-12 col-lg-6 offset-lg-3 mt-1 text-center font-16">
			<p>Inventory is limited & prices are changing too fast for your date & time of travel</p>
			<p class="weight300">On the next screen, we will show you price ranges for cars. As always, we will provide you a final price before you book.</p>
		</div>
		<div class="col-xl-12 text-center mt-5 mb-4">
            <button type="button" class="btn btn-primary pl-5 pr-5 text-uppercase">next</button>
        </div>
	</div>
</div>



<hr>


<div class="container mb-2">
	<div class="row">
		<div class="col-12 text-center">
			<p class="mb-4"><img src="/images/img-2022/location.svg" width="70" alt=""></p>
			<h2 class="gothic weight600">We'll need your pickup address before<br>
				we can find you a cab</h2>
		</div>
		<div class="col-12 col-lg-4 offset-lg-4 mt-3">
			<div class="row">
				<div class="col-12">
					<label class="color-gray">Location</label>
					<fieldset class="form-group">
						<select class="form-control" id="basicSelect">
							<option>Use my current location</option>
							<option>Blade Runner</option>
							<option>Thor Ragnarok</option>
						</select>
					</fieldset>
				</div>
			</div>
		</div>
	</div>
    <div class="row">
        <div class="col-xl-12 text-center mt-5 mb-4">
            <button type="button" class="btn btn-primary pl-5 pr-5 text-uppercase">next</button>
        </div>
    </div>
</div>

<!--<div class="container">
        <div class="row">
            <div class="col-12">
                <label id="minutes" class="time-widget-1">00</label>
                <label id="colon" class="ml-1 mr-1">:</label>
                <label id="seconds" class="time-widget-1">00</label>
                <script type="text/javascript">
                    var minutesLabel = document.getElementById("minutes");
                    var secondsLabel = document.getElementById("seconds");
                    var totalSeconds = 0;
                    setInterval(setTime, 1000);

                    function setTime()
                    {
                        ++totalSeconds;
                        secondsLabel.innerHTML = pad(totalSeconds%60);
                        minutesLabel.innerHTML = pad(parseInt(totalSeconds/60));
                    }

                    function pad(val)
                    {
                        var valString = val + "";
                        if (valString.length < 2)
                        {
                            return "0" + valString;
                        } else
                        {
                            return valString;
                        }
                    }
                </script>
            </div>
        </div>
    </div>-->

<!--<div class="container">
    <div class="row">
        <div class="col-12">
            <p id="demo"></p>
            <script>
// Set the date we're counting down to
var countDownDate = new Date("March 5, 2022 15:37:25").getTime();

// Update the count down every 1 second
var x = setInterval(function() {

  // Get today's date and time
  var now = new Date().getTime();
	
  // Find the distance between now and the count down date
  var distance = countDownDate - now;
	
  // Time calculations for days, hours, minutes and seconds
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);
	
  // Output the result in an element with id="demo"
  document.getElementById("demo").innerHTML = days + "d " + hours + "h "
  + minutes + "m " + seconds + "s ";
	
  // If the count down is over, write some text 
  if (distance < 0) {
    clearInterval(x);
    document.getElementById("demo").innerHTML = "EXPIRED";
  }
}, 1000);
</script>
        </div>
    </div>
</div>-->

<div class="container">
    <div class="row">
        <div class="col-12 m20">
            <div>Registration closes in <br><span id="time" class="time-widget-1">05:00</span></div>
        </div>
    </div>
</div>

<hr>
<div class="container mb-2">
            <div class="row">
                <div class="col-12 text-center">
                    <p><img src="/images/img-2022/location.svg" width="70" alt=""></p>
                </div>
                <div class="col-12 col-lg-8 offset-lg-2 mt-2">
                    <div class="row">
                        <div class="col-12 weight600">Please give us the traveller name</div>
                        <div class="col-12 col-xl-6 mb-2">
                            <p class="mb5"><small class="form-text">First name</small></p>
                            <fieldset class="form-group position-relative has-icon-left">
                                <input type="text" class="form-control" id="iconLeft" placeholder="Enter traveller name">
                                <div class="form-control-position">
                                    <i class="bx bx-user"></i>
                                </div>
                            </fieldset>
                        </div>
                        <div class="col-12 col-xl-6 mb-2">
                            <p class="mb5"><small class="form-text">Last name</small></p>
                            <fieldset class="form-group position-relative has-icon-left">
                                <input type="text" class="form-control" id="iconLeft" placeholder="Enter traveller name">
                                <div class="form-control-position">
                                    <i class="bx bx-user"></i>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                    <div class="row widget-address">
                        
                        <div class="col-12 col-xl-6">
                            <p class="weight600 mb0">We need your pickup address </p>
                            <p class="mt0 font-12 mb0">Existing address</p>
                        </div>
                        <div class="col-12 p0">
                        <div class="swiper-centered-slides swiper-container p-1 radio-style4">
                                        <div class="swiper-wrapper">
                                            <div class="swiper-slide rounded swiper-shadow" id="getting-text">
                                                <div class="row">
                                                    <div class="col-3 pr0">
                                                        <div class="radio">
                                                            <input id="test13x" value="1" type="radio" name="cabsegmentation" class="bkg_user_trip_type">
                                                            <label for="test13x"></label>
                                                        </div>
                                                    </div>
                                                    <div class="col-9 pl0 font-12">
                                                        41, Pubpara, Udaypur Belgharia, Kolkata 700056, West Bengal
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="swiper-slide rounded swiper-shadow" id="pricing-text"> 
                                                <div class="row">
                                                    <div class="col-3 pr0">
                                                        <div class="radio">
                                                            <input id="test14x" value="1" type="radio" name="cabsegmentation" class="bkg_user_trip_type">
                                                            <label for="test14x"></label>
                                                        </div>
                                                    </div>
                                                    <div class="col-9 pl0 font-12">
                                                        5/D, Pubpara, Udaypur Belgharia, Kolkata 700056, West Bengal
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="swiper-slide rounded swiper-shadow" id="sales-text"> 
                                                <div class="row">
                                                    <div class="col-3 pr0">
                                                        <div class="radio">
                                                            <input id="test15x" value="1" type="radio" name="cabsegmentation" class="bkg_user_trip_type">
                                                            <label for="test15x"></label>
                                                        </div>
                                                    </div>
                                                    <div class="col-9 pl0 font-12">
                                                        18/C, Pubpara, Udaypur Belgharia, Kolkata 700056, West Bengal
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="swiper-slide rounded swiper-shadow" id="usage-text"> 
                                                <div class="row">
                                                    <div class="col-3 pr0">
                                                        <div class="radio">
                                                            <input id="test16x" value="1" type="radio" name="cabsegmentation" class="bkg_user_trip_type">
                                                            <label for="test16x"></label>
                                                        </div>
                                                    </div>
                                                    <div class="col-9 pl0 font-12">
                                                        41, Pubpara, Belgharia, Kolkata 700056, West Bengal, Kolkata 700056, West Bengal, India
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="swiper-slide rounded swiper-shadow" id="general-text"> 
                                                <div class="row">
                                                    <div class="col-3 pr0">
                                                        <div class="radio">
                                                            <input id="test17x" value="1" type="radio" name="cabsegmentation" class="bkg_user_trip_type">
                                                            <label for="test17x"></label>
                                                        </div>
                                                    </div>
                                                    <div class="col-9 pl0 font-12">
                                                        41, Dumdum Park, Pubpara, Belgharia, Kolkata 700056, West Bengal, Kolkata 700056, West Bengal, India
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Add Arrows -->
                                        <div class="swiper-button-next"></div>
                                        <div class="swiper-button-prev"></div>
                                    </div>
                        </div>
                        <div class="col-12 p0 mt-1">
                            <a href="#" class="btn btn-outline-light text-uppercase font-10">Add new address</a> <a href="#" class="btn btn-outline-light text-uppercase font-10">Use my current location</a>
                        </div>
                        <div class="col-12 mt-3">
                            <p class="weight600 mb0">Your drop address </p>
                            <p class="mt0 font-12 mb0">Existing address</p>
                        </div>
                        <div class="col-12">
                        <div class="swiper-centered-slides swiper-container p-1 radio-style4">
                                        <div class="swiper-wrapper">
                                            <div class="swiper-slide rounded swiper-shadow" id="getting-text">
                                                <div class="row">
                                                    <div class="col-3 pr0">
                                                        <div class="radio">
                                                            <input id="test13x" value="1" type="radio" name="cabsegmentation" class="bkg_user_trip_type">
                                                            <label for="test13x"></label>
                                                        </div>
                                                    </div>
                                                    <div class="col-9 pl0 font-12">
                                                        41, Pubpara, Udaypur Belgharia, Kolkata 700056, West Bengal
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="swiper-slide rounded swiper-shadow" id="pricing-text"> 
                                                <div class="row">
                                                    <div class="col-3 pr0">
                                                        <div class="radio">
                                                            <input id="test14x" value="1" type="radio" name="cabsegmentation" class="bkg_user_trip_type">
                                                            <label for="test14x"></label>
                                                        </div>
                                                    </div>
                                                    <div class="col-9 pl0 font-12">
                                                        5/D, Pubpara, Udaypur Belgharia, Kolkata 700056, West Bengal
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="swiper-slide rounded swiper-shadow" id="sales-text"> 
                                                <div class="row">
                                                    <div class="col-3 pr0">
                                                        <div class="radio">
                                                            <input id="test15x" value="1" type="radio" name="cabsegmentation" class="bkg_user_trip_type">
                                                            <label for="test15x"></label>
                                                        </div>
                                                    </div>
                                                    <div class="col-9 pl0 font-12">
                                                        18/C, Pubpara, Udaypur Belgharia, Kolkata 700056, West Bengal
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="swiper-slide rounded swiper-shadow" id="usage-text"> 
                                                <div class="row">
                                                    <div class="col-3 pr0">
                                                        <div class="radio">
                                                            <input id="test16x" value="1" type="radio" name="cabsegmentation" class="bkg_user_trip_type">
                                                            <label for="test16x"></label>
                                                        </div>
                                                    </div>
                                                    <div class="col-9 pl0 font-12">
                                                        41, Pubpara, Belgharia, Kolkata 700056, West Bengal, Kolkata 700056, West Bengal, India
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="swiper-slide rounded swiper-shadow" id="general-text"> 
                                                <div class="row">
                                                    <div class="col-3 pr0">
                                                        <div class="radio">
                                                            <input id="test17x" value="1" type="radio" name="cabsegmentation" class="bkg_user_trip_type">
                                                            <label for="test17x"></label>
                                                        </div>
                                                    </div>
                                                    <div class="col-9 pl0 font-12">
                                                        41, Dumdum Park, Pubpara, Belgharia, Kolkata 700056, West Bengal, Kolkata 700056, West Bengal, India
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Add Arrows -->
                                        <div class="swiper-button-next"></div>
                                        <div class="swiper-button-prev"></div>
                                    </div>
                        </div>
                        <div class="col-12 mt-1">
                            <a href="#" class="btn btn-outline-light text-uppercase font-10">Add new address</a> <a href="#" class="btn btn-outline-light text-uppercase font-10">Use my current location</a>
                        </div>
                    </div>
                </div>
            </div>
    <div class="row">
        <div class="col-xl-12 text-center mt-5 mb-4">
            <button type="button" class="btn btn-primary pl-5 pr-5 text-uppercase">next</button>
        </div>
    </div>
        </div>    
<section id="tooltip-positions">
                    <div class="row ">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Tooltip Positions</h4>
                                </div>
                                <div class="card-body">
                                    <p>Four options are available: top, right, bottom, and left aligned.</p>
                                    <div class="row">
                                        <div class="col-xl-3 col-md-6 mb-1">
                                            <h6>Basic Top Tooltip</h6>
                                            <p class="my-1">Add <code>data-placement="top"</code> to add top tooltip.</p>
                                            <button type="button" class="btn btn-outline-primary" data-toggle="tooltip" data-placement="top" title="Tooltip on top">
                                                Tooltip on top
                                            </button>
                                        </div>
                                        <div class="col-xl-3 col-md-6 mb-1">
                                            <h6>Basic Right Tooltip</h6>
                                            <p class="my-1">Add <code>data-placement="right"</code> to add right tooltip.</p>
                                            <button type="button" class="btn btn-outline-primary" data-toggle="tooltip" data-placement="right" title="Tooltip on right">
                                                Tooltip on right
                                            </button>
                                        </div>
                                        <div class="col-xl-3 col-md-6 mb-1">
                                            <h6>Basic Bottom Tooltip</h6>
                                            <p class="my-1">Add <code>data-placement="bottom"</code> to add bottom tooltip.</p>
                                            <button type="button" class="btn btn-outline-primary" data-toggle="tooltip" data-placement="bottom" title="Tooltip on bottom">
                                                Tooltip on bottom
                                            </button>
                                        </div>
                                        <div class="col-xl-3 col-md-6 mb-1">
                                            <h6>Basic Left Tooltip</h6>
                                            <p class="my-1">Add <code>data-placement="left"</code> to add left tooltip.</p>
                                            <button type="button" class="btn btn-outline-primary" data-toggle="tooltip" data-placement="left" title="Tooltip on left">
                                                Tooltip on left
                                            </button>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>