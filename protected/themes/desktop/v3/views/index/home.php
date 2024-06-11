<style>
	.swiper-slide {
		text-align: center;
		font-size: 18px;
		background: #fff;
		/* Center slide text vertically */
		display: -webkit-box;
		display: -ms-flexbox;
		display: -webkit-flex;
		display: flex;
		-webkit-box-pack: center;
		-ms-flex-pack: center;
		-webkit-justify-content: center;
		justify-content: center;
		-webkit-box-align: center;
		-ms-flex-align: center;
		-webkit-align-items: center;
		align-items: center;
	}

	.bg-outline {
		background: url(/images/img-2022/banner2.webp?v=0.8) bottom center no-repeat;
		background-size: cover;
		position: relative;
		border-bottom: #D0D0D0 1px solid
	}
.card-header{ margin-bottom: 10px!important;}
</style>
<?php
Yii::app()->clientScript->registerLinkTag("preload", "image/webp", "/images/img-2022/banner2.webp?v=0.8", null, ["as" => "image", "fetchpriority" => "auto"]);

$ptime					 = date('h:i A', strtotime('6am'));
//$model->bkg_pickup_date_time = $ptime;
$timeArr				 = Filter::getTimeDropArr($ptime);
$ptimePackage			 = Yii::app()->params['defaultPackagePickupTime'];
$timeArrPackage			 = Filter::getTimeDropArr($ptimePackage);
$selectizeOptions		 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true, 'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id', 'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
$cityRadius				 = Yii::app()->params['airportCityRadius'];
$emptyTransferDropdown	 = "Please check your transfer type.<br>Let us help you.<br><i>Call now</i> (+91) 90518-77-000";
$discovid				 = (isset($_COOKIE['gzcovid'])) ? 'none' : 'block';
?>


<?php
if ($flashdata				 = Yii::app()->user->getFlash('coin'))
{//code for flash a popup for ew
	?>
	<script type="text/javascript">

		$(window).on('load', function()
		{
			$('#myModal3').modal('show');
		});
	</script>
	<?php
}
?>
<script>
	$(window).scroll(function()
	{
		if ($(this).scrollTop() > 0)
		{
			$('.fonticon-wrap').fadeOut();
		}
		else
		{
			$('.fonticon-wrap').fadeIn();
		}
	});
</script>
<?php
/* @var $this Controller */
$this->newHome	 = true;
$imgVer			 = Yii::app()->params['imageVersion'];
Yii::app()->clientScript->registerLinkTag("preload", "image/webp", "/images/gozo-white.webp?v=0.2", null, ["as" => "image", "fetchpriority" => "auto"]);
Yii::app()->clientScript->registerLinkTag("preload", "image/gif", "/images/img_trans.gif", null, ["as" => "image", "fetchpriority" => "auto"]);
?>
<div class="container-fluid bg-outline">
	<div class="row">
		<div class="homeNavBar col-12 p0">
			<?php $this->renderPartial("homeNav", [], false, false) ?>
		</div>
		<div class="col-12 text-center pt-1 style-widget-1 style-top">
			<img src="/images/gozo-white.webp?v=0.2" alt="aaocab:India's leader in inter-city taxi | Great service. Price guarantee. Awesome reviews." title="aaocab:India's leader in inter-city taxi | Great service. Price guarantee. Awesome reviews." aria-label="aaocab:India's leader in inter-city taxi | Great service. Price guarantee. Awesome reviews." width="150" height="49">
			<h2 class="merriw mt5 mb5 banner-heading">Quality cabs<br>
				at amazing prices</h2>

		</div>

		<!--		<div class="col-12 p0"><div class="car"></div></div>-->
<!--		<div class="col-12 text-center mb10 d-lg-none">
			<a href="https://play.google.com/store/apps/details?id=com.aaocab.client" target="_blank" class="mr5"><img src="/images/app-google.png" alt="" width="80" height="26" class=""></a><a href="https://itunes.apple.com/app/id1398759012?mt=8" target="_blank" class="ml5"><img data-src="/images/app-store.png" alt="" width="80" height="26" class="lozad"></a>
		</div>
		<div class="col-12 text-center mb10 d-none d-lg-block">
			<a href="https://play.google.com/store/apps/details?id=com.aaocab.client" target="_blank" class="mr5"><img src="/images/app-google.png" alt="" width="140" height="46" class=""></a><a href="https://itunes.apple.com/app/id1398759012?mt=8" target="_blank" class="ml5"><img data-src="/images/app-store.png" alt="" width="140" height="46" class="lozad"></a>
		</div>-->
	</div>
</div>

<?php
$cabtype		 = 2;
$tncType		 = TncPoints::getTncIdsByStep(4);
$tncArr			 = TncPoints::getTypeContent($tncType);
$tncArr1		 = json_decode($tncArr, true);
$form			 = $this->beginWidget('CActiveForm', array(
	'id'					 => 'bookingTrip1',
	'enableClientValidation' => true,
	'stateful'				 => true,
	'clientOptions'			 => [
		'validateOnSubmit'	 => false,
		'errorCssClass'		 => 'has-error',
	],
	'enableAjaxValidation'	 => false,
	'errorMessageCssClass'	 => 'help-block',
	'action'				 => $this->getURL(['booking/bkgType', "cabsegmentation" => $cabtype]),
	'htmlOptions'			 => array(
		'class'			 => 'form-horizontal',
		'autocomplete'	 => 'off',
	),
		));
/* @var $form CActiveForm */

$form->error($model, "bkg_booking_type");
?>
<?= $form->hiddenField($model, 'bkg_id', ['id' => 'bkg_id11', 'class' => 'clsBkgID']); ?>
<?= $form->hiddenField($model, 'hash', ['id' => 'hash11', 'class' => 'clsHash']); ?>
<?= $form->hiddenField($model, 'bkg_transfer_type'); ?>
<?= $form->hiddenField($model, 'bkg_booking_type'); ?>
<input type="hidden" name="rdata" value="">
<input type="hidden" name="step" value="4">
<input type="hidden" name="cabType" value="<?= $cabType ?>">
<input type="hidden" name="cabsegmentation" id="cabsegmentation" value="<?= $cabtype ?>">
<div class="container-fluid bg-gray pt-1">
	<div class="row">
		<div class="col-12 col-xl-10 offset-xl-1 tab-view">
			<ul class="nav nav-tabs justify-content-center pl10 text-center d-flex" role="presentation">
				<li class="nav-item mr0 flex-fill">
					<a class="cabsegmentation nav-link text-center  <?php echo ($cabtype == 1) ? 'active' : '' ?>" id="local-tab-center" data-value="1" data-toggle="tab" href="#local-center" aria-controls="local-center" role="tab" aria-selected="true">Local</a>
				</li>
				<li class="nav-item mr0 flex-fill">
					<a class="cabsegmentation nav-link text-center <?php echo ($cabtype == 2) ? 'active' : '' ?>" id="outstation-tab-center" data-value="2" data-toggle="tab" href="#outstation-center" aria-controls="outstation-center" role="tab" aria-selected="false">Outstation</a>
				</li>
				<li class="nav-item mr0 flex-fill">
					<a class="cabsegmentation nav-link text-center" id="airport-tab-center" data-value="2" data-toggle="tab" href="#airport-center" aria-controls="airport-center" role="tab" aria-selected="false">Airport</a>
				</li>
			</ul>
			<div class="tab-content pl0">
				<div class="tab-pane <?php echo ($cabtype == 1) ? 'active' : '' ?>" id="local-center" aria-labelledby="local-tab-center" role="tabpanel">
					<div class="row radio-style6 justify-center">
<!--						<div class="col-12 col-md-4 col-lg-3 ui-facetune">
							<div class="ui-box d-flex">
								<div class="ui-inner-facetune flex-grow-1" onclick="submitServiceType('14_1')">
									<a href="javascript:void(0);">
										<img data-src="/images/img-2022/point-to-point2.png" alt="" class="lozad img-fluid img-no">
										<div class="ui-text-facetune">
											<div class="mb-0 font-18">Point to point (within-the-city)</div>
										</div>
									</a>
									<p class="mt10 d-none d-lg-block"><a href="javascript:void(0);" class="btn btn-primary font-12 mt5 pl10 pr10 color-white hvr-push btn-ride">Book your ride</a></p>
								</div>
								<div class="face-info"><a href="javascript:void(0);" data-toggle="tooltip" data-html="true" data-placement="top" title="" data-original-title="<?= $tncArr1[92] ?>"><img src="/images/img_trans.gif" alt="Info" width="1" height="1" class="info-1"></a></div>
							</div>
						</div>-->
						<div class="col-12 col-lg-3 col-md-3 ui-facetune">
							<div class="ui-box d-flex">
								<div class="ui-inner-facetune flex-grow-1">
									<?php
										$url = $this->getURL(['booking/itinerary', "bkgType" => 10]);
									?>
									<a href="<?=$url?>">
										<img data-src="/images/img-2022/g-icon-5.png" alt="" class="lozad img-fluid img-no">
										<div class="ui-text-facetune">
											<div class="mb-0 font-16">Daily Rental on hourly basis</div>
										</div>
									</a>
									<p class="mt10 d-none d-lg-block"><a href="<?= $url ?>" class="btn btn-primary font-12 mt5 pl10 pr10 color-white hvr-push btn-ride">Book your ride</a></p>
								</div>
								<div class="face-info"><a href="javascript:void(0);" data-toggle="tooltip" data-html="true" data-placement="top" title="" data-original-title="<?= $tncArr1[66] ?>"><img src="/images/img_trans.gif" alt="Info" width="1" height="1" class="info-2"></a></div>
							</div>
						</div>
						<div class="col-12 col-md-4 col-lg-3 ui-facetune">
							<div class="ui-box d-flex">
								<div class="ui-inner-facetune flex-grow-1">
									<?php
										$url = $this->getURL(['booking/itinerary', "bkgType" => 4, "type"=>1]);
									?>
									<a href="<?=$url?>">
										<img data-src="/images/img-2022/g-icon-3.png" alt="" class="lozad img-fluid img-no">
										<div class="ui-text-facetune">
											<div class="mb-0 font-16">Pick-up from airport</div>
										</div>
									</a>
									<p class="mt10 d-none d-lg-block"><a href="<?= $url ?>" class="btn btn-primary font-12 mt5 pl10 pr10 color-white hvr-push btn-ride">Book your ride</a></p>
								</div>
								<div class="face-info"><a href="javascript:void(0);" data-toggle="tooltip" data-html="true" data-placement="top" title="" data-original-title="<?= $tncArr1[64] ?>"><img src="/images/img_trans.gif" alt="Info" width="1" height="1" class="info-2"></a></div>
							</div>
						</div>


						<div class="col-12 col-lg-3 col-md-3 ui-facetune">
							<div class="ui-box d-flex">
								<div class="ui-inner-facetune flex-grow-1">
									<?php
										$url = $this->getURL(['booking/itinerary', "bkgType" => 4, "type"=>2]);
									?>
									<a href="<?=$url?>">
										<img data-src="/images/img-2022/g-icon-4.png" alt="" class="lozad img-fluid img-no">
										<div class="ui-text-facetune">
											<div class="mb-0 font-16">Drop-off to airport</div>
										</div>
									</a>
									<p class="mt10 d-none d-lg-block"><a href="<?= $url ?>" class="btn btn-primary font-12 mt5 pl10 pr10 color-white hvr-push btn-ride">Book your ride</a></p>
								</div>
								<div class="face-info"><a href="javascript:void(0);" data-toggle="tooltip" data-html="true" data-placement="top" title="" data-original-title="<?= $tncArr1[65] ?>"><img src="/images/img_trans.gif" alt="Info" width="1" height="1" class="info-2"></a></div>
							</div>
						</div>

					</div>
				</div>
				<div class="tab-pane <?php echo ($cabtype == 2) ? 'active' : '' ?>" id="outstation-center" aria-labelledby="outstation-tab-center" role="tabpanel">
					<div class="row radio-style6 justify-center">
						<div class="col-12 col-md-4 col-lg-3">
							<div class="ui-box d-flex">
								<div class="ui-inner-facetune flex-grow-1">
									<?php
										$url = $this->getURL(['booking/itinerary', "bkgType" => 1]);
									?>
									<a href="<?= $url ?>">
										<img data-src="/images/img-2022/g-icon-7.png" alt="" width="150" height="150" class="img-fluid img-no lozad">
										<div class="ui-text-facetune">
											<div class="mb-0 font-16">One-way trip</div>
										</div>
									</a>
									<p class="mt10 d-none d-md-block"><a href="<?= $url ?>" class="btn btn-primary font-12 mt5 pl10 pr10 color-white hvr-push btn-ride">Book your ride</a></p>
								</div>
								<div class="face-info"><a href="javascript:void(0);" data-toggle="tooltip" data-html="true" data-placement="top" title="" data-original-title="<?= $tncArr1[61] ?>"><img src="/images/img_trans.gif" alt="Info" width="1" height="1" class="info-2"></a></div>
							</div>
						</div>
						<div class="col-12 col-md-4 col-lg-3">
							<div class="ui-box d-flex">
								<div class="ui-inner-facetune flex-grow-1">
								<?php
										$url = $this->getURL(['booking/itinerary', "bkgType" => 2]);
								?>
									<a href="<?= $url ?>">
										<img data-src="/images/img-2022/g-icon-8.png" alt="" width="150" height="150" class="img-fluid img-no lozad">
										<div class="ui-text-facetune">
											<div class="mb-0 font-16">Round trip</div>
										</div>
									</a>
									<p class="mt10 d-none d-md-block"><a href="<?= $url ?>" class="btn btn-primary font-12 mt5 pl10 pr10 color-white hvr-push btn-ride">Book your ride</a></p>
								</div>
								<div class="face-info"><a href="javascript:void(0);" data-toggle="tooltip" data-html="true" data-placement="top" title="" data-original-title="<?= $tncArr1[63] ?>"><img src="/images/img_trans.gif" alt="Info" width="1" height="1" class="info-2"></a></div>
							</div>
						</div>
						<div class="col-12 col-md-4 col-lg-3">
							<div class="ui-box d-flex">
								<div class="ui-inner-facetune flex-grow-1">
								<?php
										$url = $this->getURL(['booking/itinerary', "bkgType" => 3]);
								?>
									<a href="<?= $url ?>">
										<img data-src="/images/img-2022/g-icon-6.png" alt="" width="150" height="150" class="img-fluid img-no lozad">
										<div class="ui-text-facetune">
											<div class="mb-0 font-16">Multi-city multi-day trip</div>
										</div>
									</a>
									<p class="mt10 d-none d-md-block"><a href="<?= $url ?>" class="btn btn-primary font-12 mt5 pl10 pr10 color-white hvr-push btn-ride">Book your ride</a></p>
								</div>
								<div class="face-info"><a href="javascript:void(0);" data-toggle="tooltip" data-html="true" data-placement="top" title="" data-original-title="<?= $tncArr1[62] ?>"><img src="/images/img_trans.gif" alt="Info" width="1" height="1" class="info-2"></a></div>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane <?php echo ($cabtype == 3) ? 'active' : '' ?>" id="airport-center" aria-labelledby="airport-tab-center" role="tabpanel">
					<div class="row mb-2 radio-style6 justify-center">
						<div class="col-12 col-md-4 col-lg-3">
							<div class="ui-box d-flex">
								<div class="ui-inner-facetune flex-grow-1">
									<?php
										$url = $this->getURL(['booking/itinerary', "bkgType" => 4, "type"=>1]);
									?>
									<a href="<?= $url ?>">
										<img data-src="/images/img-2022/g-icon-3.png" alt="" class="img-fluid img-no lozad">
										<div class="ui-text-facetune">
											<div class="mb-0 font-16">Pick-up from airport (Local)</div>
										</div>
									</a>
									<p class="mt10 d-none d-md-block"><a href="<?= $url ?>" class="btn btn-primary font-12 mt5 pl10 pr10 color-white hvr-push btn-ride">Book your ride</a></p>
								</div>
								<div class="face-info"><a href="javascript:void(0);" data-toggle="tooltip" data-html="true" data-placement="top" title="" data-original-title="<?= $tncArr1[64] ?>"><img src="/images/img_trans.gif" alt="Info" width="1" height="1" class="info-2"></a></div>
							</div>
						</div>
						<div class="col-12 col-md-4 col-lg-3">
							<div class="ui-box d-flex">
								<div class="ui-inner-facetune flex-grow-1">
									<?php
										$url = $this->getURL(['booking/itinerary', "bkgType" => 4, "type"=>2]);
									?>
									<a href="<?= $url ?>">
										<img data-src="/images/img-2022/g-icon-4.png" alt="" class="img-fluid img-no lozad">
										<div class="ui-text-facetune">
											<div class="mb-0 font-16">Drop-off to airport (Local)</div>
										</div>
									</a>
									<p class="mt10 d-none d-md-block"><a href="<?= $url ?>" class="btn btn-primary font-12 mt5 pl10 pr10 color-white hvr-push btn-ride">Book your ride</a></p>
								</div>
								<div class="face-info"><a href="javascript:void(0);" data-toggle="tooltip" data-html="true" data-placement="top" title="" data-original-title="<?= $tncArr1[65] ?>"><img src="/images/img_trans.gif" alt="Info" width="1" height="1" class="info-2"></a></div>
							</div>
						</div>
						<div class="col-12 col-md-4 col-lg-3">
							<div class="ui-box d-flex">
								<div class="ui-inner-facetune flex-grow-1">
									<?php
										$url = $this->getURL(['booking/itinerary', "bkgType" => 1, "type"=>1]);
									?>
									<a href="<?= $url ?>">
										<img data-src="/images/img-2022/g-icon-3.png" alt="" class="img-fluid img-no lozad">
										<div class="ui-text-facetune">
											<div class="mb-0 font-16">Pick-up from airport (Outstation)</div>
										</div>
									</a>
									<p class="mt10 d-none d-md-block"><a href="<?= $url ?>" class="btn btn-primary font-12 mt5 pl10 pr10 color-white hvr-push btn-ride">Book your ride</a></p>
								</div>
								<div class="face-info"><a href="javascript:void(0);" data-toggle="tooltip" data-html="true" data-placement="top" title="" data-original-title="<?= $tncArr1[82] ?>"><img src="/images/img_trans.gif" alt="Info" width="1" height="1" class="info-2"></a></div>
							</div>
						</div>
						<div class="col-12 col-md-4 col-lg-3">
							<div class="ui-box d-flex">
								<div class="ui-inner-facetune flex-grow-1">
									<?php
										$url = $this->getURL(['booking/itinerary', "bkgType" => 1, "type"=>2]);
									?>
									<a href="<?= $url ?>">
										<img data-src="/images/img-2022/g-icon-4.png" alt="" class="img-fluid img-no lozad">
										<div class="ui-text-facetune">
											<div class="mb-0 font-16">Drop-off to airport (Outstation)</div>
										</div>
									</a>
									<p class="mt10 d-none d-md-block"><a href="<?= $url ?>" class="btn btn-primary font-12 mt5 pl10 pr10 color-white hvr-push btn-ride">Book your ride</a></p>
								</div>
								<div class="face-info"><a href="javascript:void(0);" data-toggle="tooltip" data-html="true" data-placement="top" title="" data-original-title="<?= $tncArr1[83] ?>"><img src="/images/img_trans.gif" alt="Info" width="1" height="1" class="info-2"></a></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php $this->endWidget(); ?>
<div class="container-fluid bg-gray pt-2">
<div class="container">
	<div class="row">
		<div class="col-12 col-lg-4 text-center d-none d-lg-block">
			<p class="merriw font-24">Download app here</p>
			<a href="https://play.google.com/store/apps/details?id=com.aaocab.client" target="_blank" class="inline-block"><img src="/images/app-google.png" alt="Download android app" title="Download android app" aria-label="Download android app" width="140" height="46" class="mb10"></a><br><a href="https://itunes.apple.com/app/id1398759012?mt=8" target="_blank" class="inline-block"><img data-src="/images/app-store.png" alt="" aria-label="Download app" width="140" height="46" class="lozad"></a>
		</div>
		<div class="col-12 text-center mb20 d-lg-none">
			<p class="merriw font-24">Download app here</p>
			<a href="https://play.google.com/store/apps/details?id=com.aaocab.client" target="_blank" class="mr5 inline-block"><img src="/images/app-google.png" alt="Download app" title="Download app" aria-label="Download app" width="130" height="42" class=""></a><a href="https://itunes.apple.com/app/id1398759012?mt=8" target="_blank" class="ml5 inline-block"><img data-src="/images/app-store.png" alt="" aria-label="Download app" width="130" height="42" class="lozad"></a>
		</div>
		<div class="col-12 col-lg-4 mb20 text-center"><a href="/refer-friend" class="inline-block"><img src="/images/refer-a-friend2.webp?v=0.2" alt="Refer a friend" width="330" height="167" class="img-fluid"></a></div>
		<div class="col-12 col-lg-4 justify-center text-center">
			<p class="merriw font-24">Certified Excellence</p>
			<div class="row justify-center">
				<div class="col-12"><img data-src="/images/img-2022/trpadvisor2.png?v=0.2" width="100" height="101" alt="Trpadvisor" aria-label="Trpadvisor" class="lozad mr10"> <img data-src="/images/img-2022/google_review.png?v=0.2" width="121" height="101" alt="Google Review" aria-label="Google Review" class="lozad ml10"></div>

			</div>
			<p class="font-16 mt-1">Our excellent reviews speak for themselves </p>
		</div>
		
	</div>
</div>
</div>
<div class="container-fluid bg-gray pt-2">
	<div class="row">
		<div class="col-12 col-xl-8 offset-xl-2 col-md-8 offset-md-2 text-center">
			
		</div>
	</div>
	<div class="row review-widget">

		<div class="col-12 p0">
			<!-- swiper start -->
			<div class="card bg-transparent shadow-none mb5 border-none">
				<div class="card-body">
					<div class="swiper-container swiper-container21 p-1" id="carousel-slider">
						<div class="swiper-wrapper">
							<?php
							Logger::create("Executing Testimonial: " . Filter::getExecutionTime());
							$rows = Yii::app()->cache->get("getTopRatings");
							if ($rows === false)
							{
								/* @var $modelTestimonial Ratings */
								$rows = Ratings::model()->getTopRatings1(9, 2);
								Yii::app()->cache->set("getTopRatings", $rows, 7200);
							}

							$i = 1;
							foreach ($rows as $row)
							{
//								if ($i % 2 == 0)
//								{
								?>
								<div class="swiper-slide rounded swiper-shadow">
									<div class="cent-text1">
										<div class="d-inline-flex mb-1">
											<div class="avatar mr-50"><?= $row['initial'] ?></div>
											<div class="d-flex align-items-center">
												<h3 class="mb-0 font-13"> <?= $row['user_name'] ?> <br><span class="font-11"><?= date('jS M Y', strtotime($row['rtg_customer_date'])) ?></span></h3>
											</div>
										</div>
										<p class="font-13 lineheight20 weight400 review-text"><?= $row['rtg_customer_review'] ?></p>
									</div>
								</div>
								<?php
//							 } 
								$i++;
							}
							?>
                        </div>
						<!-- If we need navigation buttons -->
						<div class="swiper-button-prev"></div>
						<div class="swiper-button-next"></div>
                    </div>
					<div class="row m0 mt10">
						<div class="col-9 col-lg-8 pr0">More reviews on <a href="/index/testimonial" class="color-black" target="_blank">aaocab.com</a>, <a href="https://bit.ly/ReviewGozoOnGoogle" class="color-black" target="_blank">Google</a>, <a href="https://bit.ly/ReviewGozoOnTripAdvisor" class="color-black" target="_blank">TripAdvisor</a> | <a href="/faq#faq53" class="color-red mr10" target="_blank">Do not trust MouthShut.com</a></div>
						<div class="col-3 col-lg-4 text-right pl0 mt5"><a href="/index/testimonial" class="btn btn-primary font-11 pl10 pr10 hvr-push">All reviews</a></div>
					</div>
				</div>
            </div>
            <!-- swiper ends -->
        </div>
		<div class="col-12 text-right mb-1">
			<!--<a href="#" class="btn btn-sm btn-primary mb-1 font-12">More reviews</a>-->
		</div>
    </div>

</div>
<div class="container pt-3 pb-1">
    <div class="row" style="display: flex; flex-wrap: wrap;">
        <div class="col-12 col-md-6 col-xl-6" style="display: flex; flex-direction:row-reverse;">
            <div class="card">
                <div class="card-body p-2">
                    <img data-src="/images/img-2022/car2.png" alt="img" width="492" height="129" class="card-img-top img-fluid lozad">
                    <p class="font-30 mt-1 merriw">Attach your taxi</p>
                    <ul class="pl15">
                        <li>Get more bookings</li>
                        <li>Get rewards from Gozo Cabs</li>
                        <li>Access to cheaper insurance, spare parts, garage
							services and many more benefits!</li>
                    </ul>
                    <p class="text-center mb0"><a href="/vendor/join" class="btn btn-md btn-primary mb-1 mt-2 text-uppercase hvr-push">Know More</a></p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-6" style="display: flex; flex-direction:row-reverse;">
            <div class="card">
                <div class="card-body p-2">
                    <img data-src="/images/img-2022/icon24.png" width="101" height="113" alt="img" class="img-fluid lozad">
                    <p class="font-30 mt-1 merriw">Travel agents and resellers</p>
                    <ul class="pl15">
                        <li>Earn a referral commission</li>
                        <li>Complete transparency</li>
                        <li>Your customers will get the same price as on our website</li>
                    </ul>
                    <p class="text-center mb0"><a href="/agent/join" class="btn btn-md btn-primary mb-1 mt-2 text-uppercase hvr-push">Know More</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
//<div class="row m0 pb-2 pt-1 justify-center flex-main">
//	<div class="col-12 text-center pt-3 pb-2"><p class="merriw heading-text">What are you looking for?</p></div>
//	<div class="col-xl-2 col-md-4 col-6 flex3">
//		<div class="card text-center">
//			<div class="card-body p-1 pb-5">
//				<a href="/book-cab/one-way" class="hvr-float-shadow"><img data-src="/images/img-2022/way-1.png" alt="One way" width="165" height="104" class="card-img-top img-fluid lozad"></a>
//				<div class="merriw pt-1 color-blue font-18">One way</div>
//				<a href="/book-cab/one-way" class="btn btn-primary btn-float font-12 mt5 pl10 pr10 hvr-push">Book your ride</a>
//			</div>
//		</div>
//	</div>
//	<div class="col-xl-2 col-md-4 col-6 flex3">
//		<div class="card text-center">
//			<div class="card-body p-1 pb-5">
//				<a href="/book-cab/round-trip" class="hvr-float-shadow"><img data-src="/images/img-2022/way-2.png" alt="Round trip" width="165" height="104" class="card-img-top img-fluid lozad"></a>
//				<div class="merriw pt-1 color-blue font-18">Round trip</div>
//				<a href="/book-cab/round-trip" class="btn btn-primary btn-float font-12 mt5 pl10 pr10 hvr-push">Book your ride</a>
//			</div>
//		</div>
//	</div>
//	<div class="col-xl-2 col-md-4 col-6 flex3">
//		<div class="card text-center">
//			<div class="card-body p-1 pb-5">
//				<a href="/book-cab/airport-pickup" class="hvr-float-shadow"><img data-src="/images/img-2022/way-10.png" alt="Pickup from airport" width="165" height="104" class="card-img-top img-fluid lozad"></a>
//				<div class="merriw pt-1 color-blue font-18">Pickup from airport</div>
//				<a href="/book-cab/airport-pickup" class="btn btn-primary btn-float font-12 mt5 pl10 pr10 hvr-push">Book your ride</a>
//			</div>
//		</div>
//	</div>
//	<div class="col-xl-2 col-md-4 col-6 flex3">
//		<div class="card text-center">
//			<div class="card-body p-1 pb-5">
//				<a href="/book-cab/airport-drop" class="hvr-float-shadow"><img data-src="/images/img-2022/way-3.png" alt="Book your ride" width="165" height="104" class="card-img-top img-fluid lozad"></a>
//				<div class="merriw pt-1 color-blue font-18">Drop to airport</div>
//				<a href="/book-cab/airport-drop" class="btn btn-primary btn-float font-12 mt5 pl10 pr10 hvr-push">Book your ride</a>
//			</div>
//		</div>
//	</div>
//	<div class="col-xl-2 col-md-4 col-6 flex3">
//		<div class="card text-center">
//			<div class="card-body p-1 pb-5">
//				<a href="/book-cab/daily-rental" class="hvr-float-shadow"><img data-src="/images/img-2022/way-4.png" alt="Daily Rental" width="165" height="104" class="card-img-top img-fluid lozad"></a>
//				<div class="merriw pt-1 color-blue font-18">Daily Rental</div>
//				<a href="/book-cab/daily-rental" class="btn btn-primary btn-float font-12 mt5 pl10 pr10 hvr-push">Book your ride</a>
//			</div>
//		</div>
//	</div>
//	<div class="col-xl-2 col-md-4 col-6 flex3">
//		<div class="card text-center">
//			<div class="card-body p-1 pb-5">
//				<a href="/book-cab/multi-city" class="hvr-float-shadow"><img data-src="/images/img-2022/way-9.png" alt="Multi-city multi-day trip" width="165" height="104" class="card-img-top img-fluid lozad"></a>
//				<div class="merriw pt-1 color-blue font-18">Multi-city multi-day trip</div>
//				<a href="/book-cab/multi-city" class="btn btn-primary btn-float font-12 mt5 pl10 pr10 hvr-push">Book your ride</a>
//			</div>
//		</div>
//	</div>
//			<div class="col-xl-2 col-md-6 col-6">
//				<div class="card text-center">
//					<div class="card-body p-1">
//						<a href="#" class="hvr-float-shadow"><img src="/images/img-2022/way-5.png" alt="" width="" class="card-img-top img-fluid"></a>
//						<div class="merriw pt-1 color-blue font-18">Shuttle</div>
//						<a href="#" class="btn btn-primary mb-1 font-12 mt5 pl10 pr10">Book your ride</a>
//					</div>
//				</div>
//			</div>
//			<div class="col-xl-2 col-md-6 col-6">
//				<div class="card text-center">
//					<div class="card-body p-1">
//						<a href="#" class="hvr-float-shadow"><img src="/images/img-2022/way-6.png" alt="" width="" class="card-img-top img-fluid"></a>
//						<div class="merriw pt-1 color-blue font-18">Tour</div>
//						<a href="#" class="btn btn-primary mb-1 font-12 mt5 pl10 pr10">Book your ride</a>
//					</div>
//				</div>
//			</div>
//</div>
?>


<div class="container">
	<article class="font-15">
		<div class="row">
			<div class="col-12">
				<p>Welcome to aaocab, the best choice for intercity cab services in India. Whether you need a taxi for airport transfer, local sightseeing, or outstation travel, we have the perfect cab for you. Book online or call us and enjoy our reliable, safe, and affordable cabs.</p>
				<p>aaocab is the ultimate solution for taxi service near you. We cover over 2000 destinations in India and offer a wide range of vehicles to suit your needs. You can choose from hatchbacks, sedans, SUVs, luxury sedans, Innova, tempo travellers, minibus etc. Our experienced drivers are well-trained, courteous, and professional. They will ensure that you reach your destination on time and with comfort.</p>
				<p>aaocab is also the fastest way to get a taxi near you. We offer a wide range of chauffeur driven taxi services, including hourly car rentals, airport transfers, and one-way or round-trip taxi for a long distance outstation trip. You can book a cab in minutes using our website or mobile app. You can also call our customer care number and get instant confirmation. We offer transparent pricing, flexible payment options, and hassle-free cancellation policy. You can also track your cab in real-time and get regular updates on your trip. We offer affordable rates, excellent customer service, and a wide range of services to meet your needs. We are committed to providing our customers with the best possible travel experience. aaocab is more than just a taxi service. We are your travel partner who cares about your satisfaction and convenience. </p>
				<p>So what are you waiting for? Book your GozoCab today and explore India with comfort and safety. Whether you need a local taxi, an outstation cab, or a car rental, we have it all. aaocab is the best way to travel by road in India.</p>
			</div>
		</div>
	</article>
	
	<div class="row why-choose d-lg-none">
		<div class="col-12 text-center pt-3 pb-2"><p class="merriw heading-text">Why choose us?</p></div>
		<div class="col-12">
			<!-- swiper start -->
			<div class="card bg-transparent shadow-none border-n">
				<div class="card-body p0">
					<div  id="carousel-slider2" class="swiper-container swiper-container22 p-1">
						<div class="swiper-wrapper">
							<div class="swiper-slide rounded" id="choose-1">
								<div class="text-center">
									<img data-src="/images/img-2022/w-1.png" class="lozad" width="147" height="147" alt="Pan India Coverage">
									<h4 class="mb-0 mt-1 weight600 merriw color-blue font-20">Pan India Coverage</h4>
									<p class="color-black">We are everywhere</p>
								</div>
							</div>
							<div class="swiper-slide rounded" id="choose-2">
								<div class="text-center">
									<img data-src="/images/img-2022/w-2.png" class="lozad" width="147" height="147" alt="100% Transparency">
									<h4 class="mb-0 mt-1 weight600 merriw color-blue font-20">100% Transparency</h4>
									<p class="color-black">Full clarity on what’s included and<br>
										what’s not. No hidden tolls or extra charges.</p>
								</div>
							</div>
							<div class="swiper-slide rounded" id="choose-3">
								<div class="text-center">
									<img data-src="/images/img-2022/w-3.png" class="lozad" width="147" height="147" alt="Amazing prices">
									<h4 class="mb-0 mt-1 weight600 merriw color-blue font-20">Amazing prices</h4>
									<p class="color-black">You won’t find better or fairer pricing.
										That’s a guarantee. </p>
								</div>
							</div>
							<div class="swiper-slide rounded" id="choose-4">
								<div class="text-center">
									<img data-src="/images/img-2022/w-4.png" class="lozad" width="147" height="147" alt="24/7 Support">
									<h4 class="mb-0 mt-1 weight600 merriw color-blue font-20">24/7 Support</h4>
									<p class="color-black">No hold time. Request a
										call and we’ll call you.</p>
								</div>
							</div>
							<div class="swiper-slide rounded" id="choose-5">
								<div class="text-center">
									<img data-src="/images/img-2022/w-5.png" width="147" height="147" class="lozad" alt="Safety first">
									<h4 class="mb-0 mt-1 weight600 merriw color-blue font-20">Safety first</h4>
									<p class="color-black">Double vaccinated drivers. Live updates.</p>
								</div>
							</div>
							<div class="swiper-slide rounded" id="choose-6">
								<div class="text-center">
									<img data-src="/images/img-2022/w-6.png" class="lozad" width="147" height="147" alt="Personalised journeys">
									<h4 class="mb-0 mt-1 weight600 merriw color-blue font-20">Personalised journeys</h4>
									<p class="color-black">Choose your cab. Choose your breaks.</p>
								</div>
							</div>

						</div>
						<!-- Add Arrows -->
						<div class="swiper-button-next"></div>
						<div class="swiper-button-prev"></div>
					</div>

				</div>
			</div>
			<!-- swiper ends -->
		</div>
	</div>    
</div>

<div class="container-fluid bg-gray">
<div class="container">
	<div class="row">
		<div class="col-12 text-center pt-3 pb-2"><p class="merriw heading-text">Why do customers prefer us<br> over our competitors?</p></div>
		<div class="col-12 col-xl-10 offset-xl-1">
			<div class="hover-widget">
				<div class="hover-widget-1"><a href="#" class="hvr-grow"><img data-src="/images/img-2022/hover-3.png?v=0.2" alt="img" aria-label="Senior citizen friendly" width="369" height="178" class="img-fluid lozad"></a></div>
				<div class="hover-widget-2"><a href="#" class="hvr-grow"><img data-src="/images/img-2022/hover-4.png" alt="img" aria-label="Drivers are professional" width="300" height="183" class="img-fluid lozad"></a></div>
				<div class="hover-widget-3"><a href="#" class="hvr-grow"><img data-src="/images/img-2022/hover-2.png" alt="img" aria-label="Safe for women" width="250" height="162" class="img-fluid lozad"></a></div>
				<div class="hover-widget-4"><a href="#" class="hvr-grow"><img data-src="/images/img-2022/hover-6.png" alt="img" aria-label="Always on time" width="400" height="232" class="img-fluid lozad"></a></div>
				<div class="hover-widget-5"><a href="#" class="hvr-grow"><img data-src="/images/img-2022/hover-5.png" alt="img" aria-label="Drivers don’t cancel rides" width="250" height="159" class="img-fluid lozad"></a></div>
				<div class="hover-widget-6"><a href="#" class="hvr-grow"><img data-src="/images/img-2022/hover-1.png?v=0.2" alt="img" aria-label="No hidden charges" width="250" height="138" class="img-fluid lozad"></a></div>
			</div>
		</div>
	</div>

</div>
</div>
<div class="container-fluid pb-4 pt-1 hidden-xs hidden-sm">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center pt-3 pb-2"><p class="merriw heading-text">Why choose us?</p></div>
            <div class="col-12 col-xl-10 offset-xl-1">
                <div class="row">
                    <div class="col-xl-4 col-md-6 col-12 text-center mb-3">
                        <a href="#" class="hvr-float-shadow">
							<img data-src="/images/img-2022/w-1.png" width="147" height="147" alt="Pan India Coverage" class="lozad">
							<h4 class="mb-0 mt-1 weight600 merriw color-blue font-20">Pan India coverage</h4>
							<p class="color-black">We are everywhere</p>
                        </a>
                    </div>

                    <div class="col-xl-4 col-md-6 col-12 text-center mb-3">
                        <a href="#" class="hvr-float-shadow">
							<img data-src="/images/img-2022/w-2.png" width="147" height="147" alt="100% Transparency" class="lozad">
							<h4 class="mb-0 mt-1 weight600 merriw color-blue font-20">100% Transparency</h4>
							<p class="color-black">Full clarity on what’s included and
								what’s not. No hidden tolls or extra charges.</p>
                        </a>
                    </div>
                    <div class="col-xl-4 col-md-6 col-12 text-center mb-3">
                        <a href="#" class="hvr-float-shadow">
							<img data-src="/images/img-2022/w-3.png" width="147" height="147" alt="Amazing prices" class="lozad">
							<h4 class="mb-0 mt-1 weight600 merriw color-blue font-20">Amazing prices</h4>
							<p class="color-black">You won’t find better or fairer pricing.
								That’s a guarantee. </p>
                        </a>
                    </div>
                    <div class="col-xl-4 col-md-6 col-12 text-center mb-3">
                        <a href="#" class="hvr-float-shadow">
							<img data-src="/images/img-2022/w-4.png" width="147" height="147" alt="24/7 Support" class="lozad">
							<h4 class="mb-0 mt-1 weight600 merriw color-blue font-20">24/7 Support</h4>
							<p class="color-black">No hold time. Request a
								call and we’ll call you.</p>
                        </a>
                    </div>
                    <div class="col-xl-4 col-md-6 col-12 text-center mb-3">
                        <a href="#" class="hvr-float-shadow">
							<img data-src="/images/img-2022/w-5.png" width="147" height="147" alt="Safety first" class="lozad">
							<h4 class="mb-0 mt-1 weight600 merriw color-blue font-20">Safety first</h4>
							<p class="color-black">Double vaccinated drivers. Live updates.</p>
                        </a>
                    </div>
                    <div class="col-xl-4 col-md-6 col-12 text-center mb-3">
                        <a href="#" class="hvr-float-shadow">
							<img data-src="/images/img-2022/w-6.png" width="147" height="147" alt="Personalised journeys" class="lozad">
							<h4 class="mb-0 mt-1 weight600 merriw color-blue font-20">Personalised journeys</h4>
							<p class="color-black">Choose your cab. Choose your breaks.</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
//<div class="container-fluid pb-4 pt-3 bg-gray hidden-sm hidden-xs">
	//<div class="row">
		//<div class="col-12 text-center pt-3 pb-2"><p class="merriw heading-text">Become a member to save more!</p></div>
		//<div class="col-12 col-lg-12 col-xl-12">
			//<div class="row justify-center">
				//<div class="col-12 col-md-4 col-lg-3 col-xl-2 text-center mb-3">
					//<a href="#" class="hvr-float-shadow rounded-1">
					//	<img data-src="/images/img-2022/icon19.png" width="96" height="96" alt="Pan India Coverage" class="lozad">
				//	</a>
			//		<p class="color-blue font-18 mt-1 merriw">Better rates</p>
		//		</div>
		//		<div class="col-12 col-md-4 col-lg-3 col-xl-2 text-center mb-3">
			//		<a href="#" class="hvr-float-shadow rounded-1">
			//			<img data-src="/images/img-2022/icon20.png" width="96" height="96" alt="Pan India Coverage" class="lozad">
				//	</a>
			//		<p class="color-blue font-18 mt-1 merriw">Complimentary<br> upgrades</p>
			//	</div>
			//	<div class="col-12 col-md-4 col-lg-3 col-xl-2 text-center mb-3">
			//		<a href="#" class="hvr-float-shadow rounded-1">
			//			<img data-src="/images/img-2022/icon21.png" width="96" height="96" alt="Pan India Coverage" class="lozad">
			//		</a>
			//		<p class="color-blue font-18 mt-1 merriw">More rewards</p>
			//	</div>
			//	<div class="col-12 col-md-4 col-lg-3 col-xl-2 text-center mb-3">
			//		<a href="#" class="hvr-float-shadow rounded-1">
			//			<img data-src="/images/img-2022/icon22.png" width="96" height="96" alt="Pan India Coverage" class="lozad">
			//		</a>
			//		<p class="color-blue font-18 mt-1 merriw">Automatic discounts</p>
			//	</div>
			//	<div class="col-12 col-md-4 col-lg-3 col-xl-2 text-center mb-3">
			//		<a href="#" class="hvr-float-shadow rounded-1">
			//			<img data-src="/images/img-2022/icon23.png" width="96" height="96" alt="Pan India Coverage" class="lozad">
			//		</a>
			//		<p class="color-blue font-18 mt-1 merriw">Discounted add-ons</p>
			//	</div>
			//	<div class="col-12 text-center mb-1">
			//		<a href="/book-cab/airport-pickup" class="btn btn-md btn-primary text-uppercase hvr-push">Coming soon!</a>
		//		</div>
		//	</div>
	//	</div>
//	</div>
//</div>
?>
<?php
//<div class="container-fluid hidden-md hidden-lg hidden-xl bg-gray">
//	<div class="row why-choose">
//		<div class="col-12 text-center pt-3 pb-2"><p class="merriw heading-text">Become a member to save more!</p></div>
//		<div class="col-12">
//			<!-- swiper start -->
//			<div class="card bg-transparent shadow-none border-n">
//				<div class="card-body p0">
//					<div class="swiper-container swiper-container23 p-1"  id="carousel-slider3">
//						<div class="swiper-wrapper">
//							<div class="swiper-slide rounded" id="member-1">
//								<div class="text-center">
//									<a href="#" class="hvr-float-shadow rounded-1">
//										<img data-src="/images/img-2022/icon19.png" class="lozad" width="96" height="96" alt="Better rates">
//									</a>
//									<p class="color-blue font-18 mt-1 merriw">Better rates</p>
//								</div>
//							</div>
//							<div class="swiper-slide rounded" id="member-2">
//								<div class="text-center">
//									<a href="#" class="hvr-float-shadow rounded-1">
//										<img data-src="/images/img-2022/icon20.png" class="lozad" width="96" height="96" alt="Complimentary upgrades">
//									</a>
//									<p class="color-blue font-18 mt-1 merriw">Complimentary<br> upgrades</p>
//								</div>
//							</div>
//							<div class="swiper-slide rounded" id="member-3">
//								<div class="text-center">
//									<a href="#" class="hvr-float-shadow rounded-1">
//										<img data-src="/images/img-2022/icon21.png" class="lozad" width="96" height="96" alt="Rewards">
//									</a>
//									<p class="color-blue font-18 mt-1 merriw">More rewards</p>
//								</div>
//							</div>
//							<div class="swiper-slide rounded" id="member-4">
//								<div class="text-center">
//									<img data-src="/images/img-2022/w-4.png" class="lozad" width="96" height="96" alt="24/7 Support">
//									<h5 class="mb-0 mt-1 weight600 merriw color-blue">24/7 Support</h5>
//									<p class="text-muted">No hold time. Request a
//										call and we’ll call you.</p>
//								</div>
//							</div>
//							<div class="swiper-slide rounded" id="member-5">
//								<div class="text-center">
//									<a href="#" class="hvr-float-shadow rounded-1">
//										<img data-src="/images/img-2022/icon22.png" class="lozad" width="96" height="96" alt="Automatic discounts">
//									</a>
//									<p class="color-blue font-18 mt-1 merriw">Automatic discounts</p>
//								</div>
//							</div>
//							<div class="swiper-slide rounded" id="member-6">
//								<div class="text-center">
//									<a href="#" class="hvr-float-shadow rounded-1">
//										<img data-src="/images/img-2022/icon23.png" class="lozad" width="96" height="96" alt="Discounted add-ons">
//									</a>
//									<p class="color-blue font-18 mt-1 merriw">Discounted add-ons</p>
//								</div>
//							</div>
//
//						</div>
//						<!-- Add Arrows -->
//						<div class="swiper-button-next"></div>
//						<div class="swiper-button-prev"></div>
//					</div>
//					<div class="col-12 text-center mb-1">
//						<a href="/book-cab/airport-pickup" class="btn btn-lg btn-primary text-uppercase hvr-push">Coming soon!</a>
//					</div>
//				</div>
//			</div>
//			<!-- swiper ends -->
//		</div>
//
//	</div>    
//</div>
?>



<div class="container-fluid pt-3 bg-gray">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="row">
					<div class="col-xl-4 offset-xl-1 col-md-6 col-12 text-center mb-3">
						<img data-src="/images/img-2022/bag.png" width="180" height="163" alt="Pan India Coverage" class="lozad">
					</div>
					<div class="col-xl-7 col-md-6 col-12 mb-3">
						<p class="merriw heading-text">If you’re a frequent business<br>
							traveller or looking for a<br>
							corporate plan.</p>
						<p class="b-1"><a href="/business-travel" class="btn btn-md btn-primary mb-1 mt-2 mb-2 text-uppercase hvr-push">Click here!</a></p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php 
//	$topRoutes = Route::getTopRoutesByRegion(20);
//	$topCities			 = Cities::getTopCityByRegion(20);
//	$topAirportTransfer	 = Cities::getTopCityByRegion(20, 1);

?>
<!--<div class="container-fluid pt-3">
	<div class="container p0 list-view-content mb-5">
		<div class="row mb-1">
			<div class="col-12"><p class="font-a mt-1 merriw mb5"><b>Outstation popular cab routes</b></p></div>
			
				<div class="col-12">
					<ul>
							<?php
//								foreach ($topRoutes as $route)
//								{
							?>
							<li>
								<a href="<?//= Yii::app()->createAbsoluteUrl("/book-taxi/" .$route['fromCityName'] . '-'.$route['toCityName']); ?>" target="_blank" ><?//= $route['fromCityName']; ?> to <?//= $route['toCityName']; ?></a>
							</li>
							
							<?php // } ?>
					</ul>
				</div>

		</div>
		<div class="row mb-1">
			<div class="col-12"><p class="font-a mt-1 merriw mb5"><b>Top cities</b> <span class="font-12">(Hourly Rentals, Airport Transfers, Outstation)</span></p></div>
			
			<div class="col-12">
					<ul>
						<?php
//							foreach ($topCities as $topcity)
//							{
						?>
						<li><a href="/outstation-cabs/<?php //echo strtolower(str_replace(' ', '-', $topcity['cty_alias_path'])); ?>"><?//= $topcity['city'] ?></a></li>
						<?php // } ?>
					</ul>
			</div>
			
		</div>
		<div class="row mb-1">
			<div class="col-12"><p class="font-a mt-1 merriw mb5"><b>Airport Transfer</b> <span class="font-12">(Pickup & drop)</span></p></div>
			
			<div class="col-12">
					<ul>	
					<?php
//						foreach ($topAirportTransfer as $topairport)
//						{
					?>
						<li><a href="/airport-transfer/<?//= strtolower($topairport['cty_alias_path']) ?>"><?//= $topairport['city']; ?></a></li>
					<?php // } ?>	
					</ul>
			</div>
			
		</div>
	</div>
</div>-->
<?php  //$getFaqCategory	 = BotFaq::getCategory(); ?>
<?//= $this->renderPartial('/index/homefaq', ['getFaqCategory' => $getFaqCategory]); ?>
<!--<div class="container-fluid pt-3 bg-gray faq-collapse pb-4">
	<div class="container p0 pb-2">
		<div class="row">
			<div class="col-12"><p class="font-20 mt-1 merriw mb5"><b>Frequently asked questions (FAQ)</b></p></div>
			<div class="col-12">
				<section id="collapsible">
                    <div class="collapsible">
                        <div class="card collapse-header">
                            <div id="headingCollapse1" class="card-header" data-toggle="collapse" role="button" data-target="#collapse1" aria-expanded="false" aria-controls="collapse1">
                                <span class="collapse-title">
                                    Collapse Item 1
                                </span>
                            </div>
                            <div id="collapse1" role="tabpanel" aria-labelledby="headingCollapse1" class="collapse">
                                <div class="card-body">
                                    Pie dragée muffin. Donut cake liquorice marzipan carrot cake topping powder candy. Sugar plum
                                    brownie brownie cotton candy.
                                    Tootsie roll cotton candy pudding bonbon chocolate cake lemon drops candy. Jelly marshmallow
                                    chocolate cake carrot cake bear claw ice cream chocolate. Fruitcake apple pie pudding jelly beans
                                    pie candy canes candy canes jelly-o. Tiramisu brownie gummi bears soufflé dessert cake.
                                </div>
                            </div>
                        </div>
                        <div class="card collapse-header">
                            <div id="headingCollapse4" class="card-header" data-toggle="collapse" role="button" data-target="#collapse4" aria-expanded="false" aria-controls="collapse4">
                                <span class="collapse-title">
                                    Collapse Item 4
                                </span>
                            </div>
                            <div id="collapse4" role="tabpanel" aria-labelledby="headingCollapse4" class="collapse">
                                <div class="card-body">
                                    Pie dragée muffin. Donut cake liquorice marzipan carrot cake topping powder candy. Sugar plum
                                    brownie brownie cotton candy.
                                    Tootsie roll cotton candy pudding bonbon chocolate cake lemon drops candy. Jelly marshmallow
                                    chocolate cake carrot cake bear claw ice cream chocolate. Fruitcake apple pie pudding jelly beans
                                    pie candy canes candy canes jelly-o. Tiramisu brownie gummi bears soufflé dessert cake.
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
			</div>
		</div>
	</div>
</div>-->

<?php
//<div class="container mb-3">
 //   <div class="row">
  //      <div class="col-12 col-xl-10 offset-xl-1 text-center pt-3 pb-2">
 //           <div class="promote text-left d-lg-none pb-2 mb-2"><img data-src="/images/img-2022/promote.png" width="200" height="192" alt="img" class="lozad"></div>
 //           <p class="merriw heading-text">Promote your business <br>to our customers free of cost!</p>
 //           <p class="font-18">Our mission is to deliver quality services to our customers at great prices.
//				If you are a business anywhere in India that shares this mission,
//				we will promote your brand to our customers free of cost!</p>
//            <a href="https://connectchief.com/about/gozo-76#jobs" target="_blank" class="btn btn-sm btn-primary mb-1 mt-2 pl-1 pr-1 text-uppercase hvr-push">Let’s reach customers together</a>
//            <div class="promote text-left d-none d-sm-block"><img data-src="/images/img-2022/promote.png" alt="img" width="200" height="192" class="lozad"></div>
//       </div>
//
//    </div>
//</div>
?>
<?php
//<div class="container-fluid pt-2 bg-gray pb-2">
//	<div class="container">
//		<div class="row justify-center">
//			<div class="col-12 col-xl-4 mb-1"><img data-src="/images/img-2022/app-img-a.png" alt="Gozo App" width="350" height="375" class="img-fluid lozad"></div>
//			<div class="col-12 col-xl-4 d-flex align-items-center justify-center">
//				<p class="merriw heading-text text-center">Download the app<br><a href="https://play.google.com/store/apps/details?id=com.aaocab.client" target="_blank"><img data-src="/images/img-2022/Google_Play-Logo.svg" width="200" height="63" alt="" class="img-fluid lozad"></a></p>
//			</div>
//		</div>
//	</div>
//</div>

//<div class="container-fluid pt-2 pb-2">
	//<div class="row">
		//<div class="col-12 text-center pt-3 pb-2"><p class="merriw heading-text">Where does your money go?</p></div>
		//<div class="col-12 text-center mb-2"><img data-src="/images/img-2022/way-8.png" alt="Where does your money go?" width="600" height="389" class="img-fluid lozad"></div>
		//<div class="col-12 text-center pt-3 pb-2">
			//<p class="merriw color-blue font-24">Operating costs include</p>
			//<p class="mb0"><span class="pl-1 pr-1 font-18">&bullet;</span>Cab maintenance<span class="pl-1 pr-1 font-18">&bullet;</span>Fuel<span class="pl-1 pr-1 font-18">&bullet;</span>Driver fees<span class="pl-1 pr-1 font-18">&bullet;</span>Taxes<span class="pl-1 pr-1 font-18">&bullet;</span>Driver incentives<span class="pl-1 pr-1 font-18">&bullet;</span>Team costs<span class="pl-1 pr-1 font-18">&bullet;</span></p>
			//<p>We keep our costs low and pass on the savings to our members - as an end of the year bonus!</p>

		//</div>
	//</div>
//</div>
?>
<script type="text/javascript">
	function signIn()
	{
		var href2 = "<?php echo Yii::app()->createUrl('users/signin') ?>";
		$.ajax({
			"url": href2,
			"data": {"desktheme": 1, },
			"type": "GET",
			"dataType": "html",
			"success": function(data)
			{
				$('.modal-dialog').modal('hide');
				$('#bkCommonModel').removeClass('fade');
				$('#bkCommonModel').css("display", "block");
				$('#bkCommonModelBody').html(data);
				$('.modal-dialog-centered').modal('show');
			}
		});
		return false;
	}
	function submitServiceType(transfertype)
	{
		var ttype = 0;
		if (transfertype.indexOf('_') > -1)
		{
			let arrytrnsfrtype = transfertype.split('_');
			$('#BookingTemp_bkg_transfer_type').val(arrytrnsfrtype[1]);
			ttype = arrytrnsfrtype[1];
			transfertype = arrytrnsfrtype[0];
		}
		$('#BookingTemp_bkg_booking_type').val(transfertype);
		$(".alertsericetype").html('');
		$(".alertsericetype").hide();

		if (ttype > 0)
		{
			window.location.href = "<?= Yii::app()->createUrl('booking/itinerary') ?>" + '/bkgType/' + transfertype + '/type/' + ttype;
		}
		else
		{
			window.location.href = "<?= Yii::app()->createUrl('booking/itinerary') ?>" + '/bkgType/' + transfertype;
		}
	}


</script>


