
<?php
if ($flashdata = Yii::app()->user->getFlash('coin'))
{//code for flash a popup for ew
	?>
	<script type="text/javascript">

		$(window).on('load', function ()
		{
			$('#myModal3').modal('show');
		});
	</script>
	<?php
}
?>

<?php
/* @var $this Controller */
$this->newHome	 = true;
Logger::beginProfile("renderPartial::topSearch");
?>
<?php $imgVer			 = Yii::app()->params['imageVersion']; ?>
<div class="row">
	<?= $this->renderPartial('topSearch', array('model' => $model, 'tripType' => $tripType), true, FALSE); ?>
</div>
<?php
Logger::endProfile("renderPartial::topSearch");
?>
<div class="row bg-gray">
    <div class="col-12">
        <div class="container">
            <div class="row">
                <div class="col-sm-6 mt20 mb20 pl0">
                    <div class="bg-white-box">
                        <div class="row">
                            <div class="col-2"><img src="/images/join_1.png" alt=""></div>
                            <div class="col-10 pl0">
                                <div class="pull-right"><a class="btn btn-primary text-uppercase gradient-green-blue font-12 border-none mt15" href="/agent/join" role="button">Create account</a></div>
                                <span class="font-16">Become or travel agent with Gozo.</span><br>
                                <span class="font-18 color-orange"><b>Join Gozo's travel partner family..</b></span><br>
                            </div>
                            <div class="col-10 offset-2 pl0 hide"><img src="/images/24-hours.svg" width="25" alt=""> <b>(+91) 90518 77000 (24x7) </b></div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 mt20 mb20 pr0">
                    <div class="bg-white-box">
                        <div class="row">
                            <div class="col-2"><img src="/images/join_2.png" alt=""></div>
                            <div class="col-10 pl0">
                                <div class="pull-right"><a class="btn btn-primary text-uppercase gradient-green-blue font-12 border-none mt15" href="/vendor/join" role="button">Attach your taxi</a></div>
                                <span class="font-16">DCOs and Taxi Operators,</span><br>
                                <span class="font-18 color-orange"><b>Attach your taxi...</b></span><br>
                            </div>
                            <div class="col-10 offset-2 pl0 hide"><img src="/images/24-hours.svg" width="25" alt=""> <b>03371122005 (24x7 Dedicated Vendor line)</b></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 p0">
                    <div class="bg-white-box carousel_area mb20">
                        <!--<h1 class="text-center mt0">Popular Trips</h1> -->
						<?php
						Logger::beginProfile("Route Data Rendered");

						$routeArrList			 = ['delhi-jaipur',
							'delhi-shimla',
							'delhi-nainital',
							'delhi-agra',
							'chennai-tirupati',
							'jaipur-ajmer'
						];
						$imageList				 = ['delhi-jaipur'		 => 'add1.jpg',
							'delhi-shimla'		 => 'add2.jpg',
							'delhi-nainital'	 => 'add3.jpg',
							'delhi-agra'		 => 'add4.jpg',
							'chennai-tirupati'	 => 'add6.jpg',
							'jaipur-ajmer'		 => 'add8.jpg'
						];
						$routeDataArrList		 = [];
						$routeDataArrListJSON	 = Yii::app()->cache->get("routeDataArrList");
						if ($routeDataArrListJSON == false)
						{
							$routeDataArrList = Route::model()->getRouteDetailsbyNameList($routeArrList);
							Yii::app()->cache->set("routeDataArrList", json_encode($routeDataArrList), 604800);
						}
						else
						{
							$routeDataArrList = json_decode($routeDataArrListJSON, true);
						}
						?>
                        <div id="style2b_wrapper">
                            <div id="style2b" class="style2b" style="overflow:hidden">
                                <div class="previous_button"></div>

                                <ul style="width: 3504px; left: -584px; margin: 0px; padding: 0px; position: relative; list-style-type: none; z-index: 101;">

									<?php
									foreach ($routeDataArrList as $rtName => $rtData)
									{
										?>
										<li style="width: 368px; height: 150px; overflow: visible; float: left;">
											<div class="row slider-img-text">
												<div class="col-5 feature_eventimg box"><figure><img src="<?= "/images/" . $imageList[$rtName] ?>?v=<?= $imgVer ?>" alt="<?= $rtData['fcity_name'] . ' to ' . $rtData['tcity_name'] ?>" class="img-fluid"></figure>
												</div>
												<div class="col-7 feature_eventtext text-left pl0">
													<span class="font-18"><b><?= $rtData['fcity_name'] . ' to ' . $rtData['tcity_name'] ?></b></span><br>
													<span class=""><?= $rtData['rut_estm_distance'] ?> Km</span><br><br>
													<span class="text-uppercase color-gray">starting from</span><br>
													<span class="font-30 text-orange-red">&#x20B9;<b><?= $rtData['baseAmount'] ?></b></span><br>
													<span><a href="<?= "/book-taxi/" . $rtName ?>" class="font-18 text-link-green"><b>Book Now</b></a></span>
												</div>
											</div>
										</li>

										<?php
									}
									Logger::endProfile("Route Data Rendered");
									?>
                                </ul>
                                <div class="next_button"></div>
                                <div class="clear"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 bg-white-box mb20">
                    <span class="font-36 text-blue-green"><b>Tour Packages</b></span><span class="pull-right text-uppercase"><b><a href="/packages" class="text-link-orange">View all Packages</a></b></span>
                    <div class="row mt20 flex wigdet-card">
						<?php
						Logger::beginProfile("Package Data Rendered");

						$qry	 = [];
						$pmodel	 = Package::model()->getListtoShow('', $qry);
						$i		 = 1;
						foreach ($pmodel as $pck)
						{
							if ($i <= 4)
							{
								?>
								<div class="col-sm-3">
									<div class="card">
										<img src="<?= $pck['pci_images'] ?>" class="card-img-top" width="150" height="150" onclick="showDetails(<?= $pck['pck_id'] ?>)">
										<div class="card-body text-center p10">
											<p class="font-16"><b><?php echo $pck['pck_name']; ?></b></p>

											<?php
											$pkid			 = $pck['pck_id'];
											$form			 = $this->beginWidget('CActiveForm', array(
												'id'					 => "index-package-form_$pkid", 'enableClientValidation' => true,
												'clientOptions'			 => array(
												),
												// Please note: When you enable ajax validation, make sure the corresponding
												// controller action is handling ajax validation correctly.
												// See class documentation of CActiveForm for details on this,
												// you need to use the performAjaxValidation()-method described there.
												'enableAjaxValidation'	 => false,
												'errorMessageCssClass'	 => 'help-block',
												'action'				 => '/bknw',
												'htmlOptions'			 => array(
													'class' => 'form-horizontal',
												),
											));
											/* @var $form CActiveForm */
											$ptimePackage	 = Yii::app()->params['defaultPackagePickupTime'];

											$defaultDate = date("Y-m-d $ptimePackage", strtotime('+7 days'));
											$pdate		 = DateTimeFormat::DateTimeToDatePicker($defaultDate);
											$ptime		 = date('h:i A', strtotime($ptimePackage));
											?>
											<input type="hidden" id="step11" name="step" value="1">
											<?= $form->hiddenField($model, 'bkg_booking_type', ['value' => 5, 'id' => 'bkg_booking_type5']); ?>
											<?= $form->hiddenField($model, 'bktyp', ['value' => 5, 'id' => 'bktyp5']); ?>
											<?= $form->hiddenField($model, 'bkg_package_id', ['value' => $pkid]); ?>  
											<?= $form->hiddenField($model, 'bkg_pickup_date_date', ['value' => $pdate]); ?>  
											<?= $form->hiddenField($model, 'bkg_pickup_date_time', ['value' => $ptime]); ?> 

											<?php
											if ($pck['prt_package_rate'] != '')
											{
												echo CHtml::submitButton('Book Package', array('class' => 'btn btn text-uppercase gradient-yellow-orange font-10 border-none'));
											}
											else
											{
												?>
												<div class="btn-footer">
													<div class="row m0">
														<div class="col-8 p0">
															<a href="javascript:void(0)" class="helpline btn btn text-uppercase gradient-green-blue font-10 border-none">Call / Email us to book</a>
														</div>
													<?php } ?>
													<div class="col-4 p0">
														<a href="#" class="btn btn text-uppercase gradient-green-blue font-10 border-none" onclick="showDetails(<?= $pck['pck_id'] ?>)"><b>Details</b></a>
													</div>
												</div>
											</div>
											<?php $this->endWidget(); ?>

										</div>
									</div>
								</div>
								<?php
							} $i++;
						}
						Logger::endProfile("Package Data Rendered");
						?>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<!--modal for win a day start-->
<div id="myModal3" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header p5 border-none">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body text-center mb10 user-review pt0 blue-color" style="font-weight: bold; font-size: 14px;">
                <p><span class="h4"><b>Thank you for visiting.</b></span></p><p> Just for visiting we have given you <span style="font-size:20px;" class="orange-color "><?= $flashdata ?></span> in Gozo coins that you can redeem on your next rental with Gozo.</p><p> We are now entering your name for a chance to win a free 1 day rental. There is a new winner announced every month. If you win we will be contacting you by email.</p>
            </div>
        </div>
    </div>
</div>

<!--modal for win a day end-->
<div id="indexPackageDetails" class="modal fade bd-example-modal-lg" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header pb5 pt5">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
            <div class="modal-body mb10 user-review pt0 blue-color" id="indexPackageDetailsBody">

            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function ()
    {
<?php
if ($isFlexxi)
{
	?>
	        window.close();
	        window.opener.updateLogin();
<?php } ?>
    });
    function showDetails(id)
    {
        //alert(id);
        $href = '<?= Yii::app()->createUrl('booking/showPackage', ['pck_id' => '']) ?>' + id;
        jQuery.ajax({type: 'GET', url: $href,
            success: function (data)
            {
                $('#indexPackageDetails').removeClass('fade');
                $('#indexPackageDetails').css('display', 'block');
                $('#indexPackageDetailsBody').html(data);
                $('#indexPackageDetails').modal('show');
            }
        });
    }
</script> 

