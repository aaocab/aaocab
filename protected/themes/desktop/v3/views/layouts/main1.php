<?php
$this->beginContent('//layouts/head');
?>

<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="http://www.googletagmanager.com/ns.html?id=GTM-T73295"
                      height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->


	<nav class="header-navbar navbar-expand-lg navbar navbar-with-menu navbar-static-top navbar-brand-center border-b-gray mb-1">
        <div class="navbar-header d-xl-block d-none">

        </div>
		<div class="navbar-wrapper" style="margin-left: 0;">
            <div class="navbar-container content">
                <div class="navbar-collapse" id="navbar-mobile">


                    <div class="inline-block">
						<ul class="nav navbar-nav">
							<li class="nav-item mobile-menu mr-auto d-md-none">
								<a class="nav-link nav-menu-main backButton hide"><img src="/images/bx-chevron-left.svg" alt="img" width="36" height="36"></a>
							</li>

							<li class="nav-item">
								<?php
								if (!Yii::app()->user->isGuest)
								{
									$coinhtml	 = "";
									$uname		 = Yii::app()->user->loadUser()->usr_name;
									$coin		 = UserCredits::model()->getUserCoin(Yii::app()->user->getId());
									if ($coin > 0)
									{
										$coinhtml = '  <img src="/images/img-2022/gozo_coin.svg?v=0.2" alt="Gozo Coin" width="14"> ' . $coin;
									}
									?>
	<!--								<a href="#" onclick="$('.aside').asidebar('open')" class="hidden-sm hidden-xs"><i class="bx bx-menu font-24 color-black p10"></i></a>-->
									<div class="aside">
										<div class="aside-header">
											<span class="close" data-dismiss="aside" aria-hidden="true">×</span>
										</div>
										<div class="aside-contents">
											<ul>

												<li><a href="/users/view"><img src="/images/bx-user.svg" alt="img" width="14" height="14" class="mr10"> <?= ucfirst(Yii::app()->user->loadUser()->usr_name) . '  ' . $coinhtml ?></a></li>
												<li><a href="/users/creditlist"><img src="/images/bxl-creative-commons.svg" alt="img" width="14" height="14" class="mr10"> Accounts details</a></li>
												<li><a href="/booking/list"><img src="/images/bx-spreadsheet.svg" alt="img" width="14" height="14" class="mr10"> My bookings</a></li>
												<li><a href="/users/refer"><img src="/images/bx-user-plus.svg" alt="img" width="14" height="14" class="mr10"> Refer friends</a></li>

	<!--					<li><a href="/blog"><i class="bx bxl-blogger font-14"></i> Gozo blog</a></li>-->

	<!--					<li class="mt10"><a href="/agent/join"><i class="bx bx-user-circle mr10"></i> Become an agent</a></li>
	<li><a href="/vendor/join"><i class="bx bx-car mr10"></i> Attach Your Taxi</a></li>-->


												<li><a href="/users/changePassword"><img src="/images/bx-lock-alt.svg" alt="img" width="14" height="14" class="mr10"> Change password</a></li>

	<!--<li><a href="#"><i class="bx bx-phone mr10"></i> Contact</a></li>-->



	<!--						<li><a href="/"><i class="bx bx-check mr10"></i> New Booking</a></li>-->



						<li><a href="<?= Yii::app()->createUrl('users/logoutv3') ?>"><img src="/images/bx-log-out-circle.png" alt="img" width="14" height="14" class="mr10"> Logout</a></li>

											</ul>
										</div>
									</div>
									<?php
								}
								?>
								<div class="aside-backdrop"></div>
								<a class="navbar-brand" href="/">
									<img src="/images/gozo-white.webp?v=0.2" alt="Gozocabs:India's leader in inter-city taxi | Great service. Price guarantee. Awesome reviews." title="Gozocabs:India's leader in inter-city taxi | Great service. Price guarantee. Awesome reviews." aria-label="Gozocabs:India's leader in inter-city taxi | Great service. Price guarantee. Awesome reviews." width="100" height="34">
								</a>
							</li>
						</ul>
					</div>

<!--					<span class="nav navbar-nav float-right d-flex align-items-center ml-auto widget-menu mr10 icon-top"><a type="button" href="javascript:void(0)" class="" onClick="return reqCMB(1)"><img src="/images/img-2022/call-back.png" alt="" width="28"></a></span>-->
					<span class="userNavBar ml-auto">
						<?php
						$this->renderPartial("/users/navbarsign");
						?>
					</span>
                </div>
            </div>
        </div>
    </nav>
	<?php
	$time = Filter::getExecutionTime();

	$GLOBALS['time97']	 = $time;
	?>

	<?= $content ?>

	<?php
	$time				 = Filter::getExecutionTime();

	$GLOBALS['time98'] = $time;
	?>
	<? //= $this->renderPartial("/index/footer"); ?>

    <script>
		function w3_open()
		{
			document.getElementById("mySidebar").style.display = "block";
		}

		function w3_close()
		{
			document.getElementById("mySidebar").style.display = "none";
		}
    </script>

	<div class="modal fade modalView" id="bkCommonModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content">
				<div class="modal-header p15">
					<h5 class="modal-title" id="bkCommonModelHeader"></h5>
					<button type="button" class="close mt30 n" data-dismiss="modal" aria-label="Close">
						<img src="/images/img_trans.gif" alt="img" width="1" height="1" class="x-1">
					</button>
				</div>
				<div class="modal-body" id="bkCommonModelBody">
					<p class="mb-0">
						...
					</p>
				</div>
				<!--                                                <div class="modal-footer">
																	<button type="button" class="btn btn-light-secondary" data-dismiss="modal">
																		<i class="bx bx-x d-block d-sm-none"></i>
																		<span class="d-none d-sm-block">Close</span>
																	</button>
																</div>-->
			</div>
		</div>
	</div>

	<!-- The Modal -->
	<div class="modal full-screen" id="myAddressModal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header" style="display: inline-block; padding: 5px 10px 0; border-bottom: 0px">
					<button type="button" class="close float-right" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body p15">
				</div>
                <div class="modal-body1 p15">

				</div>
			</div>
		</div>
	</div>

</body>

<?php $this->endContent(); ?>

<script type="text/javascript">
	var home = null;
	$(document).ready(function()
	{
		home = new Home();
	});
</script>