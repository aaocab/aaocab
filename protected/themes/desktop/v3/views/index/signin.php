					<ul class="nav nav-tabs d-flex justify-content-between" id="nav-tabs" role="tablist">
						<li class="nav-item">
							<a class="nav-link active text-center" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Login</a>
						</li>
						<li class="nav-item">
							<a class="nav-link text-center" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">New user</a>
						</li>
					</ul>
					<div class="tab-content" id="myTabContent">
						<div class="tab-pane fade active show" id="home" role="tabpanel" aria-labelledby="home-tab">
							<form action="">
								<div class="form-group mb-50">
									<label class="text-bold-500" for="exampleInputEmail1">Email Id / Phone no.</label>
									<input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email Id / phone no."></div>
								<div class="form-group">
									<label class="text-bold-500" for="exampleInputPassword1">Password</label>
									<input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
								</div>
								<div class="form-group d-flex flex-md-row flex-column justify-content-between">
									<div class="text-right"><a href="auth-forgot-password.html" class="card-link text-bold-500">Login with OTP</a></div>
								</div>
								<div class="d-flex justify-content-center">
									<button type="submit" class="btn btn-primary glow w-200">Login<img src="/images/bx-right-arrow-alt.svg" alt="img" width="14" height="14"></button>
								</div>								
								<div class="divider">
									<div class="divider-text text-uppercase text-muted"><small>OR</small>
									</div>
								</div>
								<div class="text-center">
									<a href="#">
										<img src="/images/btn_google_signin_dark_normal_web@2x.png" alt="" width="200"></a>

								</div>
							</form>
						</div>
						<div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
							<form action="">
								<div class="form-group mb-50">
									<label class="text-bold-500" for="exampleInputEmail1">First name</label>
									<input type="text" class="form-control" id="" placeholder="Enter first name">
								</div>
								<div class="form-group mb-50">
									<label class="text-bold-500" for="exampleInputEmail1">Last name</label>
									<input type="text" class="form-control" id="" placeholder="Enter last name">
								</div>
								<div class="form-group mb-50">
									<label class="text-bold-500" for="exampleInputEmail1">Email</label>
									<input type="email" class="form-control" id="" placeholder="Enter email address">
								</div>
								<div class="form-group">
									<label class="text-bold-500" for="exampleInputPassword1">Phone</label>
									<input type="number" class="form-control" id="exampleInputPassword1" placeholder="Enter phone">
								</div>
								<div class="d-flex justify-content-center">
									<button type="submit" class="btn btn-primary glow w-200 position-relative">Signup<img src="/images/bx-right-arrow-alt.svg" alt="img" width="14" height="14"></button>
								</div>
								<div class="divider">
									<div class="divider-text text-uppercase text-muted"><small>OR</small>
									</div>
								</div>
								<div class="text-center">
									<a href="#">
										<img src="/images/google-signup.png" alt="" width="200"></a>

								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>