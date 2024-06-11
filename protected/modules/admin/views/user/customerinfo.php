<div class="row">
	<?php
	$categoryId	 = ContactPref::model()->find('cpr_ctt_id=:id', ['id' => $model['ctt_id']])->cpr_category;
	$catCss		 = "";
	if ($categoryId > 0)
	{
		$category = UserCategoryMaster::model()->findByPk($categoryId)->ucm_label;
		if ($category != '')
		{
			$catCss = UserCategoryMaster::getColorByid($categoryId);
		}
	}
	if ($model['cr_is_vendor'] != null || $model['cr_is_driver'] != null)
	{
		?>
		<div class="col-xs-12 mb30">
			<?php
			if ($model['cr_is_vendor'] != null)
			{
				echo CHtml::link('<span class="btn-7 mr15">Also a Vendor</span>', Yii::app()->createUrl("admin/vendor/view", ["id" => $model['cr_is_vendor']]), ["class" => "", "onclick" => "", 'target' => '_blank']);
			}
			if ($model['cr_is_driver'] != null)
			{
				echo CHtml::link('<span class="btn-7 mr15">Also a Driver</span>', Yii::app()->createUrl("admin/driver/view", ["id" => $model['cr_is_driver']]), ["class" => "", "onclick" => "", 'target' => '_blank']);
			}
			?>
		</div>
	<?php } ?>
	<div class="col-xs-12">
		<div class="col-xs-12 text-right font-15">
			<span class="verifymsg" style="color: #009900;"></span>
		</div>
		<div class="row">
			<div class="col-xs-12 col-md-8 mb20">
				<div class="widget-tab-box2">
					<div class="row mb20">
						<?php
						$date1	 = new DateTime($model['last_login']);
						$date2	 = new DateTime();
						//echo $date1->format('d/m/Y')."===".$date2->format('d/m/Y');

						$diff = $date2->diff($date1);

						$hours	 = $diff->h;
						$hours	 = $hours + ($diff->days * 24);
						if ($hours >= 0 && $hours <= 24)
						{
							$duration = $hours . " Hours";
						}
						else
						{
							$duration = $diff->days . " Days";
						}
						?>
						<div class ="row">
							<div class="col-xs-7">	<div class="col-xs-12 pr5">
									<h1 class="mb0"><span class="user-imgs"><img src=<?php echo ($userModel->usr_profile_pic_path != '' ? $userModel->usr_profile_pic_path : "/images/user_img.jpg"); ?> alt=""></span>
										<?= $model['ctt_first_name'] . ' ' . $model['ctt_last_name'] ?><span class="ml5" style="position: absolute; top: 2px; right: 55px;"><?= !empty($category) ? "<img src='/images/{$catCss}' alt='' width='22'  title='{$category}'>" : ""; ?></span>									
									</h1>
									<p class="color-gray">Signed up with <b><?= $model['eml_email_address'] ?></b> </p>
								</div> 																
							</div>
							<div class="col-xs-5 text-right" ><div class="col-xs-12 text-right color-gray mt10 n pb10">Last Logged in: <?= $duration ?> ago</div>
								<!--<div class="col-xs-12 text-right">
									<p class="mb0">

										<i class="fas fa-star color-<?= (round($totalbookingdetail['overall_rating']) >= 1) ? 'yellow' : 'gray'; ?>"></i>
										<i class="fas fa-star color-<?= (round($totalbookingdetail['overall_rating']) >= 2) ? 'yellow' : 'gray'; ?>"></i>
										<i class="fas fa-star color-<?= (round($totalbookingdetail['overall_rating']) >= 3) ? 'yellow' : 'gray'; ?>"></i>
										<i class="fas fa-star color-<?= (round($totalbookingdetail['overall_rating']) >= 4) ? 'yellow' : 'gray'; ?>"></i>
										<i class="fas fa-star color-<?= (round($totalbookingdetail['overall_rating']) >= 5) ? 'yellow' : 'gray'; ?>"></i>
									</p>

								<?php
								/* if ($totalbookingdetail['overall_rating'])
								  {
								  $custOverallRating = round($totalbookingdetail['overall_rating']);
								  }
								  else
								  {
								  $custOverallRating = 0;
								  } */
								?>                                                                  
									<p class="color-gray"><?= $custOverallRating; ?>/5 Rating <?php // if ($data['countRating'] != ''){ echo '(' . $data['countRating'] . ' people)'; } else { echo ''; }                                                            ?></p>
								</div> -->
							</div>
						</div>
					</div> 
					<div class="row mb10">
						<div class="col-xs-6">
							<p class="mb0 color-gray">Contact Number</p>
							<p class="font-14"><b><?= $model['phn_phone_country_code'] . '-' . $model['phn_phone_no'] ?></b> 
								<?php
								if (!empty($model['phn_is_verified']) && $model['phn_is_verified'] == "1")
								{
									echo ' <span class="color-green"><i class="fas fa-check-circle"></i> Verified</span>';
								}
								?>									
							</p>

						</div>
						<div class="col-xs-6">
							<p class="mb0 color-gray">Email ID</p>
							<p class="font-14"><b><?= !empty($model['eml_email_address']) ? $model['eml_email_address'] : "-"; ?></b></p>
						</div>
						<div class="col-xs-6">
							<p class="mb0 color-gray">Residing City | State</p>
							<p class="font-14"><b><?= !empty($model['cty_name']) ? $model['cty_name'] : "-"; ?></b></p>
						</div>
						<div class="col-xs-6">
							<p class="mb0 color-gray">Pincode</p>
							<p class="font-14"><b><?= !empty($model['usr_zip']) ? $model['usr_zip'] : "-"; ?></b></p>
						</div>
						<div class="col-xs-6">
							<p class="mb0 color-gray">Address</p>
							<p class="font-14"><b><?= !empty($model['ctt_address']) ? $model['ctt_address'] : "-"; ?></b></p>
						</div>
						<div class="col-xs-6">
							<p class="mb0 color-gray">Gender</p>
							<p class="font-14"><b><?= ($model['usr_gender'] == 1 ? "Male" : "Female"); ?></b></p>
						</div>

						<div class="col-xs-6">	
							<p class="mb0 color-gray">Tags</p>
							<?php
							if ($model['ctt_tags'] != '')
							{
								$tagList = Tags::getListByids($model['ctt_tags']);
								foreach ($tagList as $tag)
								{
									if ($tag['tag_color'] != '')
									{
										$tagBtnList .= " <span title='" . $tag['tag_desc'] . "' class='badge badge-pill badge-primary m5 mr0 p5 pb10 pl10 pr10' style='background:" . $tag['tag_color'] . "'>" . $tag['tag_name'] . "</span>";
									}
									else
									{
										$tagBtnList .= " <span title='" . $tag['tag_desc'] . "' class='badge badge-pill badge-primary m5 mr0 p5 pb10 pl10 pr10' >" . $tag['tag_name'] . "</span>";
									}
								}
								echo $tagBtnList;
							}
							else
							{
								echo '-';
							}
							?>
						</div>
						<!--<div class="col-xs-6">
							<p class="mb0 color-gray">IP Address</p>
							<p class="font-14"><b><? //= !empty($model['usr_ip']) ? $model['usr_ip'] : "-";      ?></b></p>
						</div>-->
					</div>
				</div>
				<!-- showhide social box div started -->
				<div id="socialboxDiv" style="display:none;">
					<?php
					if (count($UserIdArr) > 0)
					{
						?><BR>
						<div class="widget-tab-box2">
							<div class="row">
								<div class="col-xs-12 col-sm-12">
									<p class="mb0 color-gray">Social Information(Linked) </p>
									<p class="font-14">
										<?php
										$socialemail = "";
										$fblinkdata	 = '';
										$glinkdata	 = '';
										$adminId	 = UserInfo::getUserId();
										$auth		 = Yii::app()->authManager;
										$roles		 = $auth->getRoles($adminId);
										$arr		 = array_keys($roles);
										$isAdmin	 = false;
										if (in_array("6 - Developer", $arr) || in_array("SuperAdmin", $arr))
										{
											$isAdmin = true;
										}

										for ($i = 0; $i < count($UserIdArr); $i++)
										{
											$dataprofiledata = explode('"email";', $UserIdArr[$i]['profile_cache']);
											$dataprofiledata = explode(';', $dataprofiledata[1]);
											$dataprofiledata = explode(':"', $dataprofiledata[0]);
											//$socialIcons	 = $UserIdArr[$i]['provider'] == "Google" ? '<i class="fa fa-google" aria-hidden="true"></i>' : '<i  style="color:blue" class="fa fa-facebook-square"></i>';

											if ($UserIdArr[$i]['provider'] == "Facebook" && trim($dataprofiledata[1], '"') != '')
											{
												$fblinkdata .= "<tr><td>" . trim($dataprofiledata[1], '"') . ($isAdmin == true && trim($dataprofiledata[1], '"') != '' ? ' - <a  href="#" id="unlink" onclick="unlinkSocial(\'Facebook\')">Unlink</a>' : '') . "</td></tr>";
											}
											if ($UserIdArr[$i]['provider'] == "Google" && trim($dataprofiledata[1], '"') != '')
											{
												$glinkdata .= "<tr><td>" . trim($dataprofiledata[1], '"') . ($isAdmin == true && trim($dataprofiledata[1], '"') != '' ? ' - <a  href="#" id="unlink" onclick="unlinkSocial(\'Google\')">Unlink</a>' : '') . "</tr></td>";
											}
											$socialemail .= "<b>" . $UserIdArr[$i]['provider'] . " - " . trim($dataprofiledata[1], '"') . "</b><br>";
										}
										//echo $socialemail;
										?>
									</p>
									<div class="col-xs-12 table-style-panel">
										<div class="table-responsive">
											<table class="table table-bordered">
												<tr class="bg-purple color-white">
													<td><b>Google</b></td>
													<td><b>Facebook</b></td>
												</tr>									
												<tr>
													<td><?= ($glinkdata != '' ? "<table  class='table table-bordered'>" . $glinkdata . "</table>" : ' - ') ?> </td>
													<td><?= ($fblinkdata != '' ? "<table   class='table table-bordered'>" . $fblinkdata . "</table>" : ' - ') ?></td>
												</tr>
											</table>
										</div>
									</div>
								</div>
							</div></div>
					<?php } ?>
				</div>
				<br>
				<div class="widget-tab-box2">
					<div class="row mb10">
						<div class="col-xs-12 col-md-9 pr5">
							<h2>Booking Details</h2>
						</div>
					</div>
					<div class="row mb10">
						<div class="col-xs-12 col-md-12 pr5">
							<div class="col-xs-12 col-lg-12">
								<div class="table-responsive">
									<table class="table table-bordered">
										<tbody>
											<tr>
												<td>Last inquiry date:<br><b><?= $totalBookings['lastInquiryDate']; ?></b></td>
												<td>Last traveled date:<br><b><?= $totalBookings['lastTravelledDate']; ?></b></td>
												<td>Last paid booking date:<br><b><?= $totalBookings['lastPaidBookingCreateDate']; ?></b></td>


											</tr>




									</table>
									<!--<p class="mb0 color-gray">Cancelled Quoted: <span class='font-14 color-black'><? //= $totalBookings['totCancelledQt']  ?></span></p>-->
								</div>
							</div>
						</div>
					</div>
					<div class="row mb10">
						<div class="col-xs-12 col-lg-6">
							<div class="table-responsive">
								<table class="table table-bordered">
									<tbody>

										<tr>
											<td>Inquiry</td>
											<td class="text-right font-14 color-black"><b><?= $totalBookings['totInquiry'] ?></b></td>
										</tr>

										<tr>
											<td>Quoted</td>
											<td class="text-right font-14 color-black"><b><?= $totalBookings['totQuote'] ?></b></td>
										</tr>
										<tr>
											<td>Price Expired:</td>
											<td class="text-right font-14 color-black"><b><?= $totalBookings['totUnverified'] ?></b></td>
										</tr>
										<tr>
											<td>New:</td>
											<td class="text-right font-14 color-black"><b><?= $totalBookings['totNew'] ?></b></td>
										</tr>
										<tr>
											<td>Assigned:</td>
											<td class="text-right font-14 color-black"><b><?= $totalBookings['totAssinged'] ?></b></td>
										</tr>
										<tr>
											<td>OntheWay</td>
											<td class="text-right font-14 color-black"><b><?= $totalBookings['totOntheWay'] ?></b></td>
										</tr>
										<tr>
											<td>Completed:</td>
											<td class="text-right font-14 color-black"><b><?= $totalBookings['totCompleted'] ?></b></td>
										</tr>
										<tr>
											<td>Cancelled:</td>
											<td class="text-right font-14 color-black"><b><?= $totalBookings['totCancelled'] ?></b></td>
										</tr>
										<tr>
											<td>Others:</td>
											<td class="text-right font-14 color-black"><b><?= $totalBookings['totOthers'] ?></b></td>
										</tr>
										<tr>
											<td class="success"><b>Total Traveled Trips:</b></td>
											<td class="success text-right font-18 color-black"><b><?= $totalBookings['total'] ?></b></td>
										</tr>
								</table>
								<!--<p class="mb0 color-gray">Cancelled Quoted: <span class='font-14 color-black'><? //= $totalBookings['totCancelledQt']  ?></span></p>-->
							</div>
						</div>
						<div class="col-xs-12 col-lg-6">
							<div class="table-responsive">
								<table class="table table-bordered">
									<tbody>
										<tr>
											<td>Gross margin (Gozo amount from travelled trips):</td>
											<td class="text-right font-14 color-black">&#x20B9;<b><?php echo $totalBookings['totGozoAmount'] ?: 0; ?></b></td>
										</tr>
										<tr>
											<td>Total amount:</td>
											<td class="text-right font-14 color-black">&#x20B9;<b><?php echo $totalBookings['totAmount'] ?: 0; ?></b></td>
										</tr>


										<tr>
											<td>Gross margin(%):</td>
											<td class="text-right font-14 color-black"><b>
													<?php
													if ($totalBookings['totAmount'] > 0)
													{
														echo round(($totalBookings['totGozoAmount'] / $totalBookings['totAmount']) * 100, 2);
													}
													else
													{
														echo 0;
													}
													?>

												</b></td>
										</tr><!--comment-->
										<!--<tr>
										<td>Overall rating:</td>
										<td class = "text-right font-14 color-black"><b><? //= $totalbookingdetail['overall_rating']  
										?> Star</b></td>
							</tr>-->
										<tr>
											<td>Marked bad count:</td>
											<td class="text-right font-14 color-black"><b><?php echo $userModel['usr_mark_customer_count'] ?></b></td>
										</tr>
										<tr>
											<td>Wallet balance:</td>
											<td class="text-right font-14 color-black">&#x20B9;<b><?= $walletBalance ?></b></td>
										</tr>
										<tr>
											<td>Gozo coins:</td>
											<td class="text-right font-14 color-black">&#x20B9;<b><?= $totalGozoCoins ?></b></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>


				<?php
				if ($totalUserCitiesLifetime->count() > 0 || $totalUserAirportCitiesLifetime->count() > 0)
				{
					?>
					<br>
					<div class="widget-tab-box2">
						<div class="row mb10">
							<div class="col-xs-12 col-lg-6">
								<div class="table-responsive">
									<?php
									if ($totalUserCitiesLifetime->count() > 0)
									{
										?>
										<h2>Top 10 City: </h2>
										<table class="table table-bordered">
											<tbody>
												<?php
												foreach ($totalUserCitiesLifetime as $value)
												{
													?>
													<tr>
														<td><?php echo $value['cityName']; ?>:</td>
														<td class="text-right font-14 color-black"><b><?php echo $value['cnt']; ?></b></td>
													</tr>
												<?php }
												?>
											</tbody>
										</table>
									<?php }
									?>
								</div>
							</div>
							<div class="col-xs-12 col-lg-6">
								<div class="table-responsive">
									<?php
									if ($totalUserAirportCitiesLifetime->count() > 0)
									{
										?>
										<h2>Top 10 Airport City: </h2>
										<table class="table table-bordered">
											<tbody>
												<?php
												foreach ($totalUserAirportCitiesLifetime as $value)
												{
													?>
													<tr>
														<td><?php echo $value['cityName']; ?>:</td>
														<td class="text-right font-14 color-black"><b><?php echo $value['cnt']; ?></b></td>
													</tr>
												<?php }
												?>
											</tbody>
										</table>
									<?php }
									?>
								</div>
							</div>
						</div>
					</div>

				<?php } ?>




				<?php
				if ($totalUserMonthLifetime->count() > 0 || $totalUserWeekLifetime->count() > 0)
				{
					?>

					<br>
					<div class="widget-tab-box2">
						<div class="row mb10">
							<div class="col-xs-12 col-lg-6">
								<div class="table-responsive">
									<?php
									if ($totalUserMonthLifetime->count() > 0)
									{
										?>
										<h2>User Monthly Traveled : </h2>
										<table class="table table-bordered">
											<tbody>
												<?php
												foreach ($totalUserMonthLifetime as $value)
												{
													?>
													<tr>
														<td><?php echo $value['monthName']; ?>:</td>
														<td class="text-right font-14 color-black"><b><?php echo $value['cnt']; ?></b></td>
													</tr>
												<?php }
												?>
											</tbody>
										</table>
									<?php }
									?>
								</div>
							</div>



								<div class="col-xs-12 col-lg-6">
								<div class="table-responsive">
									<?php
									if ($totalUserWeekLifetime->count() > 0)
									{
										?>
										<h2>User Week Traveled: </h2>
										<table class="table table-bordered">
											<tbody>
												<?php
												foreach ($totalUserWeekLifetime as $value)
												{
													?>
													<tr>
														<td><?php echo $value['weekId']; ?>:</td>
														<td class="text-right font-14 color-black"><b><?php echo $value['cnt']; ?></b></td>
													</tr>
												<?php }
												?>
											</tbody>
										</table>
									<?php }
									?>
								</div>
							</div>




						</div>
					</div>

				<?php } ?>

				<?php
				if ($totalUserServiceClassLifetime->count() > 0 || $totalUserVehicleClassLifetime->count() > 0)
				{
					?>
					<br>
					<div class="widget-tab-box2">
						<div class="row mb10">
							<div class="col-xs-12 col-lg-6">
								<div class="table-responsive">

									<?php
									if ($totalUserServiceClassLifetime->count() > 0)
									{
										?>

										<h2>Top Service class: </h2>
										<table class="table table-bordered">
											<tbody>
												<?php
												foreach ($totalUserServiceClassLifetime as $value)
												{
													?>
													<tr>
														<td><?php echo $value['tierName']; ?>:</td>
														<td class="text-right font-14 color-black"><b><?php echo $value['cnt']; ?></b></td>
													</tr>
												<?php }
												?>
											</tbody>
										</table>

									<?php }
									?>
								</div>
							</div>
							<div class="col-xs-12 col-lg-6">
								<div class="table-responsive">

									<?php
									if ($totalUserVehicleClassLifetime->count() > 0)
									{
										?>
										<h2>Top Vehicle class: </h2>
										<table class="table table-bordered">
											<tbody>
												<?php
												foreach ($totalUserVehicleClassLifetime as $value)
												{
													?>
													<tr>
														<td><?php echo $value['vehicleName']; ?>:</td>
														<td class="text-right font-14 color-black"><b><?php echo $value['cnt']; ?></b></td>
													</tr>
												<?php }
												?>
											</tbody>
										</table>
									<?php }
									?>
								</div>
							</div>
						</div>
					</div>

				<?php } ?>

			</div>
			<div class="col-xs-12 col-md-4">
				<div class="widget-tab-box2 link-infos">
					<h1 class="font-16">Actions</h1>

					<ul class="pl0">
						<li class="mb5"><a  href="/admpnl/contact/view?ctt_id=<?= $model['ctt_id'] ?>" target="_blank"><i class="fas fa-plus mr5 font-11"></i>View Contact</a></li>
					</ul>  
					<ul class="pl0">
						<li class="mb5"><a  href="/admpnl/user/deviceHistory?userId=<?= $model['user_id'] ?>" target="_blank"><i class="fas fa-plus mr5 font-11"></i>Device History</a></li>
					</ul>
					<?php
					if (count($UserIdArr) > 0)
					{
						?>
						<ul class="pl0">
							<li class="mb5"><a  href="#" id="showsociallink"><i class="fas fa-plus mr5 font-11"></i>Show/Hide Social Information</a></li>
						</ul>
						<?php
						/* $adminId = UserInfo::getUserId();
						  $auth	 = Yii::app()->authManager;
						  $roles	 = $auth->getRoles($adminId);
						  $arr	 = array_keys($roles);
						  if (in_array("6 - Developer", $arr) || in_array("SuperAdmin", $arr))
						  {

						  ?>
						  <ul class="pl0">
						  <li class="mb5"><a  href="#" id="unlink" onclick="unlinkSocial()"><i class="fas fa-plus mr5 font-11"></i>Unlink Social Accounts</a></li>
						  </ul>
						  <?php } */
					}
					?>
					<?php
					if ($model['eml_email_address'] != '')
					{
						?>
						<ul class="pl0">
							<li class="mb5"><a href="#" onclick="sendVerifyLinkByEmail()"><i class="fas fa-plus mr5 font-11"></i>Send Email For Account Recovery</a></li>
						</ul>
						<?php
					}
					if ($model['phn_phone_no'] != '')
					{
						?>
						<ul class="pl0">
							<li class="mb5"><a href="#" onclick="sendOtpByPhone()"><i class="fas fa-plus mr5 font-11"></i>Send SMS For Account Recovery</a></li>
						</ul>
					<?php } ?>

				</div><BR>
				<!-- ongoing booking start -->
				<?php
				if (!empty($ongoingbooking))
				{
					$bmodel = $ongoingbooking;
					?>

					<div class="row">
						<div class="col-xs-12 mb20">
							<div class="widget-tab-box2 link-infos">
								<div class="row mb10">
									<div class="col-xs-12 col-md-9 pr5">
										<h2>On-going Booking</h2>
									</div>
								</div>
								<h1 class="font-14">Booking ID <?php echo CHtml::link($bmodel['bkg_booking_id'], Yii::app()->createUrl("admin/booking/view", ["id" => $bmodel['bkg_id']]), ["class" => "", "onclick" => "", 'target' => '_blank']); ?></h1>
								<div class="row mb10">
									<div class="col-xs-12">
										<p class="mb0 color-gray">Trip date: <?= date("d M Y", strtotime($bmodel['bkg_pickup_date'])); ?> | <?= Booking::model()->getBookingStatus($bmodel['bkg_status']); ?> </p>
									</div>
									<div class="col-xs-12">
										<p class="font-14"><B><?php
												if ($bmodel['from_city'] != "")
												{
													echo $bmodel['from_city'];
												}
												?> - <?php
												if ($bmodel['to_city'] != "")
												{
													echo $bmodel['to_city'];
												}
												?> </B> 
											<span class="color-blue font-12 pl10"><?php
												if ($bmodel['bkg_booking_type'] != "")
												{
													echo Booking::model()->getBookingType($bmodel['bkg_booking_type']);
												}
												else
												{
													echo "";
												}
												?></span></p>
										<p class="color-grey font-12"> <?= $bmodel['trip_duration_days'] ?> Day | <?= $bmodel['bkg_trip_distance'] ?> Kms</p>
									</div>
									<div class="col-xs-12">
										<p class="mb0 color-gray">Car Type</p>
										<p class="font-12"><b><?= SvcClassVhcCat::model()->getVctSvcList("string", 0, 0, $bmodel['bkg_vehicle_type_id']) ?></b></p>
									</div>
									<div class="col-xs-12">
										<p class="mb0 color-gray">Contact number</p>
										<p class="font-12"><b><?= !empty($bmodel['contact_no']) ? $bmodel['contact_no'] : "-"; ?></b></p>
									</div>
									<div class="col-xs-12 text-center mt20">
										<h1 class="font-14"><?php echo CHtml::link('View Booking History', Yii::app()->createUrl("admin/booking/view", ["id" => $bmodel['bkg_id']]), ["class" => "", "onclick" => "", 'target' => '_blank']); ?></h1>
									</div>
								</div>
							</div>
						</div>
					</div>

				<?php } ?>
				<!-- ongoing booking end -->
				<!-- upcoming booking start -->
				<?php
				if (!empty($upcomingbooking))
				{
					$bmodel = $upcomingbooking;
					?>

					<div class="row">
						<div class="col-xs-12 mb20">
							<div class="widget-tab-box2 link-infos">
								<div class="row mb10">
									<div class="col-xs-12 col-md-9 pr5">
										<h2>Upcoming Booking</h2>
									</div>
								</div>
								<h1 class="font-14">Booking ID <?php echo CHtml::link($bmodel['bkg_booking_id'], Yii::app()->createUrl("admin/booking/view", ["id" => $bmodel['bkg_id']]), ["class" => "", "onclick" => "", 'target' => '_blank']); ?></h1>
								<div class="row mb10">
									<div class="col-xs-12">
										<p class="mb0 color-gray">Trip date: <?= date("d M Y", strtotime($bmodel['bkg_pickup_date'])); ?> | <?= Booking::model()->getBookingStatus($bmodel['bkg_status']); ?> </p>
									</div>
									<div class="col-xs-12">
										<p class="font-14"><B><?php
												if ($bmodel['from_city'] != "")
												{
													echo $bmodel['from_city'];
												}
												?> - <?php
												if ($bmodel['to_city'] != "")
												{
													echo $bmodel['to_city'];
												}
												?> </B> 
											<span class="color-blue font-12 pl10"><?php
												if ($bmodel['bkg_booking_type'] != "")
												{
													echo Booking::model()->getBookingType($bmodel['bkg_booking_type']);
												}
												else
												{
													echo "";
												}
												?></span></p>
										<p class="color-grey font-12"> <?= $bmodel['trip_duration_days'] ?> Day | <?= $bmodel['bkg_trip_distance'] ?> Kms</p>
									</div>
									<div class="col-xs-12">
										<p class="mb0 color-gray">Car Type</p>
										<p class="font-12"><b><?= SvcClassVhcCat::model()->getVctSvcList("string", 0, 0, $bmodel['bkg_vehicle_type_id']) ?></b></p>
									</div>
									<div class="col-xs-12">
										<p class="mb0 color-gray">Contact number</p>
										<p class="font-12"><b><?= !empty($bmodel['contact_no']) ? $bmodel['contact_no'] : "-"; ?></b></p>
									</div>
									<div class="col-xs-12 text-center mt20">
										<h1 class="font-14"><?php echo CHtml::link('View Booking History', Yii::app()->createUrl("admin/booking/view", ["id" => $bmodel['bkg_id']]), ["class" => "", "onclick" => "", 'target' => '_blank']); ?></h1>
									</div>
								</div>
							</div>
						</div>
					</div>

					<?php
				}
				else
				{
					?>

					<div class="row">
						<div class="col-xs-12 mb20">
							<div class="widget-tab-box2 link-infos">
								<div class="row mb10">
									<div class="col-xs-12 col-md-9 pr5">
										<h2>Upcoming Booking</h2>
									</div>
								</div>
								<p>No Booking Found</p>
							</div>
						</div>
					</div>

				<?php }
				?>
				<!-- upcoming booking end -->
			</div>
		</div>
	</div>
</div>
<script>
    function unlinkSocial(provider)
    {
        var userid = '<?= $userModel->user_id ?>';
        $href = $adminUrl + "/user/unlinkSocialAcc";
        jQuery.ajax({type: 'GET',
            url: $href,
            data: {"id": userid, "provider": provider},
            dataType: "html",
            success: function (data)
            {
                //consol.log(data);
                if (data !== '') {
                    var json = JSON.parse(data);
                    if (json.success === false)
                    {
                        alert(json.error);
                    } else {
                        $('#unlink').text("Unlink");
                    }
                }
            }
        });
    }
</script>