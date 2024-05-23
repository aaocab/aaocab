<style>
    .input-simple-1.textarea textarea{
		line-height: 20px!important;
		width: 100%;
	}
</style>
<?php
$this->layout	 = 'column1';
?>
<?php
$bookingStatus	 = Booking::model()->getUserBookingStatus();
$version		 = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/bookNow.min.js?v=' . $version);
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/fontawesome-web/css/all.min.css?v0.6');
?>

<div class="content-boxed-widget p10 mb10 top-10">
	<div class="content bottom-0 uppercase"><h3 class="mb0">Booking List </h3></div>
</div>
<?php
foreach ($models as $key => $val)
{
	$bookingType = Booking::model()->getBookingType($val['bkg_booking_type']);
	$bkid		 = $val['bkg_id'];

	$uniqueid	 = Booking::model()->generateLinkUniqueid($bkid);
	$bkgPrefmodel	 = BookingPref::model()->getByBooking($bkid);
	$link		 = Yii::app()->createAbsoluteUrl('/' . '/r/' . $uniqueid);
	$isRating	 = Ratings::model()->getRatingbyBookingId($bkid);

	$response = Contact::referenceUserData($val['bui_id'], 3);
	if ($response->getStatus())
	{
		$contactNo	 = $response->getData()->phone['number'];
		$email		 = $response->getData()->email['email'];
	}

	$route	 = BookingRoute::model()->getRouteName($bkid);
	$hash	 = Yii::app()->shortHash->hash($bkid);
	$payurl	 = Yii::app()->createAbsoluteUrl('booking/paynow', ['id' => $bkid, 'hash' => $hash]);

	$cabImg			 = $val['vct_image'];
	$cabType		 = $val['vct_desc'];
	$bookingModel	 = Booking::model()->findbyPk($bkid);
	$cab			 = $bookingModel->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label . '(' . $bookingModel->bkgSvcClassVhcCat->scc_ServiceClass->scc_label . ")";
	if ($val['bkg_bcb_id'] && $val['bkg_status'] > 3 && $val['bkg_status'] <= 7)
	{
		$cabImg	 = $val['vct_image'];
		$cabType = $val['vct_desc'];
	}
	?>
	<div class="content-boxed-widget p0">
		<div class="content text-center box-text-9 bottom-5 p5"> <?= $route ?></div>
		<div class="content p10 bottom-0">
			<div class="ribbon1"><?= $bookingType ?></div>
			<div class="ribbon3 text-right"><span class="gray-color">Booking ID</span><br>
<!--				<a onclick="return viewBooking(this)" href="<?#= Yii::app()->createUrl('booking/view', array('bookingID' => $val['bkg_id'])) ?>" bkgcode="<?= $val['bkg_booking_id'] ?>" data-menu="menu-list-modal3"><span class="bg-pink-dark p5 font-11 bor-radius3"><?= $val['bkg_booking_id'] ?></span></a>-->
				<a href="<?php echo $payurl; ?>" bkgcode="<?= $val['bkg_booking_id'] ?>"><span class="bg-pink-dark p5 font-11 bor-radius3"><?= Filter::formatBookingId($val['bkg_booking_id']) ?></span></a>

			</div>
			<div class="content bottom-10 text-center">
				<img src="<?= '/' . $cabImg ?>" alt="Image Not Found" width="150" class="text-center inline-block"><br>
				<?= strtoupper($cabType); ?>
			</div>

			<div class="content p0 bottom-0">
				<b><?= ucfirst($val['bkg_user_fname']) . ' ' . ucfirst($val['bkg_user_lname']); ?></b>
			</div>
			<div class="content p0 bottom-10">
				<?
				if ($contactNo != '')
				{
				?>

				<span class="gray-color"><i class="fas fa-phone font-10"></i> Mobile:</span><br>
				<?= $contactNo; ?>
				<? } ?>

			</div>
			<div class="content p0 bottom-10">
				<div class="one-half">
					<?
					//					if ($val['bkg_user_email'] != '')
					//					{
					?>
					<span class="gray-color"><i class="fas fa-envelope font-10"></i> Email:</span><br>
					<?= $email; ?>
					<? // } ?>
				</div>
				<div class="text-right">
					<?
					if ($val['bkg_status'] == 6 || $val['bkg_status'] == 7)
					{

					if ($isRating != false)
					{
					?>
					<a class="uppercase btn-green pl5 pr5 mr5" href="<?= Yii::app()->createUrl('rating/showreview', ['bkg_id' => $bkid]) ?>"  onclick="return showreview(this)" bkgId="<?= $bkid ?>" data-menu="menu-list-rating<?= $bkid ?>" title="Reviewed">Show Review</a>
					<?
					}
					else
					{
					?>
					<a href="<?= $link ?>" target="_blank" class="uppercase btn-green pl5 pr5 mr5" id="review" title="Review">Review</a>	
					<?
					}
					}
					?>

				</div>
			</div>

			<div class="content p0 bottom-10">
				<div class="one-half">
					<span class="gray-color"><i class="fas fa-clock font-10"></i> Booked on:</span><br>
					<?= date('d/m/Y', strtotime($val['bkg_create_date'])) . ', ' . date('h:i A', strtotime($val['bkg_create_date'])); ?>
				</div>
				<div class="one-half last-column text-right">
					<span class="gray-color"><i class="fas fa-clock font-10"></i> Pickup Date:</span><br>
					<?= date('d/m/Y', strtotime($val['bkg_pickup_date'])) . ', ' . date('h:i A', strtotime($val['bkg_pickup_date'])); ?>
				</div>
				<div class="clear"></div>
				<?
				if ($val['bkg_booking_type'] == 2 || $val['bkg_booking_type'] == 3)
				{
				?>
				<div class="content p0 bottom-0">
					<span class="gray-color"><i class="fas fa-clock font-10"></i> Return Date:</span><br>
					<?= date('d/m/Y', strtotime($val['bkg_pickup_date'])) . ', ' . date('h:i A', strtotime($val['bkg_pickup_date'])); ?>
				</div>
				<? } ?>
			</div>
			<div class="content p0 bottom-10">
				<?
				if ($val['bcb_driver_id'] != '')
				{
				?>

				<div class="one-half">
					<span class="gray-color">Driver:</span><br>
					<?= ucwords($val['bcb_driver_name']); ?>
				</div>
				<? } ?>
				<?
				if ($val['bcb_cab_id'] != '')
				{
				?>
				<div class="one-half last-column text-right">
					<span class="gray-color">Cab Number:</span><br>
					<?= strtoupper($val['vhc_number']); ?>
				</div>
				<?
				}
				?>
				<div class="clear"></div>
			</div>
			<?
			if ($val['bkg_advance_amount'] > 0)
			{
			?>
			<div class="content p0 bottom-10">
				<div class="one-half">
					<span class="gray-color"><i class="fas fa-rupee-sign font-10"></i> Advance Paid:</span><br>
					<i class="fas fa-rupee-sign font12"></i><?= round($val['bkg_advance_amount']) ?>
				</div>
				<div class="one-half last-column text-right">
					<span class="gray-color"><i class="fas fa-rupee-sign font-10"></i> Due Amount:</span><br>
					<i class="fas fa-rupee-sign font12"></i><?= round($val['bkg_due_amount']) ?>
				</div>
				<div class="clear"></div>
			</div>
			<div class="content p0 bottom-10">
				<?
				if ($val['bkg_cancel_charge'] > 0)
				{
				?> 

				<div class="one-half">
					<span class="gray-color"><i class="fas fa-rupee-sign font-10"></i> Cancellation Charge:</span><br>
					<i class="fas fa-rupee-sign font12"></i><?= round($val['bkg_cancel_charge']) ?>
				</div>
				<? } ?>
				<?
				if ($val['bkg_refund_amount'] > 0)
				{
				?>
				<div class="one-half last-column text-right">
					<span class="gray-color"><i class="fas fa-rupee-sign font-10"></i> Refund Amount:</span><br>
					<i class="fas fa-rupee-sign font12"></i><?= round($val['bkg_refund_amount']) ?>

				</div>
				<div class="clear"></div>
				<? } ?>
			</div>
			<?
			if ($val['bkg_refund_amount'] > 0)
			{
			?>
			<div class="content p0 bottom-10">
				<span class="gray-color">Refund Transaction ID:</span><br>
				<?= PaymentGateway::getTXNIDbyBkgId($val['bkg_id'], 1);
				?>
			</div>
			<? } ?>

			<? } ?>

			<div class="content p0 bottom-0 text-center">
				<?
				$date1	 = date('Y-m-d H:i:s', strtotime($val['bkg_pickup_date'] . '- 120 minute'));
				$date1	 = new DateTime($date1);
				$date2	 = date('Y-m-d H:i:s');
				$date2	 = new DateTime($date2);
				$stop	 = true;
				$isPromo = BookingSub::model()->getApplicable($val['bkg_from_city_id'], $val['bkg_to_city_id'], 3);
				?>
				<?php
				if ($val['bkg_is_gozonow'] == 1 && $val['bkg_pickup_date'] <> Filter::getDBDateTime())
				{

					$bkgId	 = $val['bkg_id'];
					$hash	 = Yii::app()->shortHash->hash($val['bkg_id']);
					$gzurl	 = Yii::app()->createAbsoluteUrl('gznow/' . $bkgId . '/' . $hash);
					?>
					<a href="<?= $gzurl ?>" class="uppercase btn-red pl5 pr5 mr5 text-uppercase" id="gznowtrack" title="Track Booking">Track Booking</a>

					<?php
				}
				?>
				<?
				if ((($val['bkg_status'] == 15 || $val['bkg_status'] == 2 || $val['bkg_status'] == 3 || $val['bkg_status'] == 5) && ($val['bkg_due_amount'] > 0 || $val['bkg_due_amount'] == '')) || (!$isPromo && $val['bkg_status'] == 1 && $date1 > $date2))
				{
				?>

				<a href="<?= $payurl ?>" class="uppercase btn-green pl5 pr5 mr5" id="payment" title="Payment">Make payment</a>

				<? } ?>
				<?
				if ($val['bkg_status'] == 15 || $val['bkg_status'] == 2 || $val['bkg_status'] == 3 || $val['bkg_status'] == 5)
				{
				?>
				<a href="javascript:void(0);" class="uppercase btn-red pl5 pr5 mr5 cancelModal" data-id="<?= $val['bkg_id'] ?>">Cancel Booking</a>
				<a href="javascript:void(0);" data-menu="menu-list-modal<?= $val['bkg_id'] ?>" id="cancel-modal<?= $val['bkg_id'] ?>" class="hide">Cancel Booking</a>
				<? } ?>

				<?
				$date = DBUtil::getCurrentTime();
				if (($val['bkg_status'] == 2 || $val['bkg_status'] == 15) && $val['bkg_pickup_date'] > $date)
				{
				?>
																									<!--						<button type="button" class="uppercase btn-green pl10 pr10 mr5" id="modify" onclick="modify(<?= $bkid ?>)" title="Edit Booking"><i class="fas fa-edit"></i></button>-->
				<a href="javascript:void(0);" class="uppercase btn-green pl10 pr10 mr5" data-menu="menu-edit-modal<?= $val['bkg_id'] ?>" data-id="<?= $val['bkg_id'] ?>"><i class="fas fa-edit"></i></a>
				<? } ?>
				<?php
				$pickupDate	 = date('Y-m-d H:i:s', strtotime($val['bkg_pickup_date']));
				$pickupTime	 = new DateTime($pickupDate);
				if ($val['bkg_status'] == 2 && $pickupTime > $date2 && $bkgPrefmodel['bkg_critical_score'] <= 0.65)
				{
					?>
					<a href="javascript:void(0);" class="uppercase btn-orange pt5 pb5 pl10 pr10 mr5 mt10" data-menu="menu-reshedule-modal<?= $val['bkg_id'] ?>" data-id="<?= $val['bkg_id'] ?>">Reschedule Pickup Time</a>
				<?php } ?>
			</div>
		</div>
		<div class="content box-text-4 p10 bottom-0 line-height14">
			<div class="one-half"><span class="font-11">STATUS:</span><br><b class="<?= ($val['bkg_status'] <= 7) ? 'color-green3-dark' : 'red-color'; ?>"><?= $bookingStatus[$val['bkg_status']]; ?></b></div>
			<div class="one-half last-column text-right"><span class="font-11 uppercase">Total amount:</span><br>
				<span class="font-20"><span class="inr-font">₹</span><b>
						<?
						if ($val['bkg_due_amount'] != '')
						{
						if ($val['bkg_due_amount'] >= 0)
						{
						echo round($val['bkg_due_amount']);
						}
						else
						{
						echo '0';
						}
						}
						else
						{

						echo round($val['bkg_total_amount']);
						}
						?></b>
				</span>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<div id="menu-list-modal3" data-selected="menu-components" data-width="300" data-height="420" class="menu-box menu-modal">
		<div class="menu-title border-none pt0"><a href="#" class="menu-hide mt25 n" style="z-index: 9;"><i class="fa fa-times"></i></a>
		</div>
		<div id="bookDetails"></div>

	</div>
	<div id="menu-list-rating<?= $bkid ?>" data-selected="menu-components" data-width="300" data-height="420" class="menu-box menu-modal">
		<div class="menu-title border-none pt0"><a href="#" class="menu-hide mt25 n" style="z-index: 9;"><i class="fa fa-times"></i></a>
		</div>
		<div id="rating-details-<?= $bkid ?>"></div>
	</div>
	<div id="menu-list-modal<?= $val['bkg_id'] ?>" data-selected="menu-components" data-width="300" data-height="370" class="menu-box menu-modal">
		<div class="menu-title"><a href="#" class="menu-hide mt15 n" id="menubox<?= $id ?>"><i class="fa fa-times"></i></a>
			<h1>Cancel Booking</h1>
		</div>
		<?
		$rDetail		 = CancelReasons::model()->getListbyUserType(1);
		$reasonList		 = ['' => '< Select a reason >'] + $rDetail[0];
		$reasonPHList	 = $rDetail[1];
		$jsReasonPHList	 = json_encode($reasonPHList);
		?>
		<div class="menu-page">
			<div class="p15">
				<?= CHtml::hiddenField("bk_id", $val['bkg_id'], ['id' => "bk_id"]) ?>
				<div class="select-box select-box-1 mt10">

					<em for="delete"><b>Reason for cancellation : </b></em>
					<?= CHtml::dropDownList('bkreason' . $val['bkg_id'], '', $reasonList, ['id' => "bkreason" . $val['bkg_id'], 'class' => "form-control cancelReason", 'required' => true, 'data-id' => $val['bkg_id']]) ?>

					<div class="mt10 input-simple-1 textarea has-icon bottom-10" id="reasontext<?= $val['bkg_id'] ?>" style="display: none; line-height: 20px;">
						<?= CHtml::textArea('bkreasontext', '', ['id' => "bkreasontext" . $val['bkg_id'], 'class' => "content p0 bottom-0", 'placeholder' => 'Description', 'required' => true]) ?>
					</div>
				</div>
				<div class="Submit-button text-center mt20" style="position: absolute; bottom: -180px; left: 10px; right: 10px;">
					<a href="#" id="usercancelmodal<?= $val['bkg_id'] ?>" class="button bg-highlight button-full button-rounded button-sm uppercase ultrabold shadow-small cancelButton" data-id = "<?= $val['bkg_id'] ?>">SUBMIT</a>
				</div>
				<?= CHtml::endForm() ?>
			</div>
		</div>
	</div>
	<div id="menu-edit-modal<?= $val['bkg_id'] ?>" data-selected="menu-components" data-width="300" data-height="425" class="menu-box menu-modal">
		<div class="menu-title"><a href="#" class="menu-hide mt15 n" id="menubox<?= $id ?>"><i class="fa fa-times"></i></a>
			<h1>Edit Booking</h1>
		</div>
		<div class="menu-page">
			<?= CHtml::hiddenField('hash' . $val['bkg_id'], Yii::app()->shortHash->hash($bookingModel->bkg_id)) ?>
			<? $route			 = BookingRoute::model()->getRouteName($val['bkg_id']); ?>
			<div class="content text-center font-18 mb10">
				<?= $route ?>
	<!--				<span class="has-error"><? //echo $form->error($model, 'bkg_from_city_id');              ?></span>-->
			</div>
			<div class="content mb0">
				<div class="one-half">Estimated distance:</div>
				<div class="one-half last-column text-right"><?= $val['bkg_trip_distance'] . " Km"; ?></div>
				<div class="clear"></div>
			</div>
			<div class="content mb0">
				<div class="one-half">Estimated duration:</div>
				<div class="one-half last-column text-right">
					<span id="time"><?
						$hr				 = date('G', mktime(0, $val['bkg_trip_duration'])) . " Hr";
						$min			 = (date('i', mktime(0, $val['bkg_trip_duration'])) != '00') ? ' ' . date('i', mktime(0, $val['bkg_trip_duration'])) . " min" : '';
						echo $hr . $min;
						?></span>
					<? //= $form->hiddenField($bookingModel->bkg_trip_duration, 'bkg_trip_duration', array())  ?>
					<input type="hidden" value="<?= $bookingModel->bkg_trip_duration ?>" id="bkg_trip_duration<?= $val['bkg_id'] ?>" />
				</div>
				<div class="clear"></div>
			</div>
			<div class="content mb10">
				<div class="one-half">Car Model:</div>
				<div class="one-half last-column text-right"><?= $cab ?></div>
				<div class="clear"></div>
			</div>
			<div class="decoration decoration-margins mb10"></div>

			<div class="content" style="display: none;">
				<input type="hidden" value="<?= DateTimeFormat::DateTimeToDatePicker($bookingModel->bkg_pickup_date) ?>" id="bkg_pickup_date_date<?= $val['bkg_id'] ?>">	
				<input type="hidden" value="<?= date('h:i A', strtotime($bookingModel->bkg_pickup_date)) ?>" id="bkg_pickup_date_time<?= $val['bkg_id'] ?>">	
				<div id="errordivpdate" class="ml15 mt10 " style="color:#da4455"></div>
			</div>

			<div class="content input-simple-1 has-icon input-blue bottom-20">
				<em class="control-label">Primary Contact Number </em> 
				<div class="isd-input">
					<?php
					$userId			 = Yii::app()->user->getId();
					$id				 = $val['bkg_id'];
					//$bookingModel->bkgUserInfo->fullContactNumber= $bookingModel->bkgUserInfo->bkg_contact_no;
					$this->widget('ext.intlphoneinput.IntlPhoneInput', array(
						'model'					 => $bookingModel->bkgUserInfo,
						'attribute'				 => 'fullContactNumber',
						'codeAttribute'			 => 'bkg_country_code',
						'numberAttribute'		 => 'bkg_contact_no',
						'options'				 => array(// optional
							'separateDialCode'	 => true,
							'autoHideDialCode'	 => true,
							'initialCountry'	 => 'in'
						),
						'htmlOptions'			 => ['class' => 'form-control', 'id' => 'fullContactNumber' . $id],
						'localisedCountryNames'	 => false, // other public properties
					));
					?>
				</div>
			</div>
			<div class="content input-simple-1 has-icon input-blue bottom-20">
				<em for="delete">Email Address:</em>
				<?= CHtml::textField($bookingModel->bkgUserInfo->bkg_user_email, $email, ['id' => "bkg_user_email" . $val['bkg_id'], 'class' => "", 'placeholder' => 'Email Address']) ?>
			</div>
		</div>
		<div class="content top-20">
			<a href="#" id="usereditmodal<?= $val['bkg_id'] ?>" class="button button-rounded shadow-small bg-highlight button-full uppercase ultrabold editButton" data-id = "<?= $val['bkg_id'] ?>">SUBMIT</a>
		</div>
	</div>
	<div id="menu-reshedule-modal<?= $val['bkg_id'] ?>" data-selected="menu-components" data-width="300" data-height="425" class="menu-box menu-modal">
		<div class="menu-title"><a href="#" class="menu-hide mt15 n" id="menubox<?= $id ?>"><i class="fa fa-times"></i></a>
			<h1>Reschedule Pickup Time</h1>
		</div>
		<div class="menu-page">
			<?= CHtml::hiddenField('hash' . $val['bkg_id'], Yii::app()->shortHash->hash($bookingModel->bkg_id)) ?>
			<input type="hidden" value="<?= $bookingModel->bkg_pickup_date ?>" id="bkg_pickup_date_booking<?= $val['bkg_id'] ?>">
			<div class="content text-center font-18 mb10">
				Booking Id: <?= Filter::formatBookingId($val['bkg_booking_id']); ?>
			</div>
			<div class="content mb20">
				<b>Current Pickup Time:</b><br>
				<?php echo date('d/m/Y', strtotime($val['bkg_pickup_date'])) . ', ' . date('h:i A', strtotime($val['bkg_pickup_date'])); ?>
				<div class="clear"></div>
			</div>
			<div class="content input-simple-1 has-icon input-blue bottom-20">
				<div class="one-half">Post pone by</div> 
				<div class="isd-input one-half last-column text-right">
					<?php
					$timeSchedule	 = Filter::scheduleTimeInterval();
					$scheduleList	 = ['' => 'Select Time'] + $timeSchedule;
					?>
					<?= CHtml::dropDownList('timeSchedule' . $val['bkg_id'], '', $scheduleList, ['id' => "Booking_timeSchedule" . $val['bkg_id'], 'class' => "form-control timeSchedule", 'required' => true, 'data-id' => $val['bkg_id'], 'onChange' => 'changePickupTime()']) ?>
				</div>
				<div class="clear"></div>
			</div>
			<div class="content mb20 hide" id="resBkg<?= $val['bkg_id'] ?>">
				<b>Reschedule Pickup Time:</b><br>
				<span id="rescheduleBkg<?= $val['bkg_id'] ?>"></span>
				<div class="clear"></div>
			</div>
		</div>
		<div class="content top-20">
			<a href="#" class="button button-rounded shadow-small bg-highlight button-full uppercase ultrabold resheduleButton" data-id = "<?= $val['bkg_id'] ?>">SUBMIT</a>
		</div>
	</div>

<?php } ?>
<?
if (empty($models))
{
?>
<div class="content-boxed-widget">
	<div class="list_heading text-center pt20 pb20" style="background: #f77026; color: #fff;">
		<b>Sorry!! No records found</b>
	</div>
</div>

<? } ?>
<div class="pagination mt20 bottom-0">
	<?php
	$this->widget('booster.widgets.TbPager', array('pages' => $usersList->pagination));
	?>
</div>	

<script>
	function changePickupTime() {
		var id = $(event.currentTarget).data('id');
		var pickup = $('#bkg_pickup_date_booking' + id).val();
		var newTime = $('#Booking_timeSchedule' + id).val();
		var pickupDate = new Date(pickup);
		var rescheduleTime = parseInt(newTime);
		var newDateTime = pickupDate.setMinutes(pickupDate.getMinutes() + rescheduleTime);
		var nowDate = new Date(newDateTime);
		var rescheduleDate = nowDate.getDate();
		var rescheduleMonth = nowDate.getMonth() + 1;
		var rescheduleYear = nowDate.getFullYear();
		var rescheduleHour = nowDate.toLocaleString('en-US', {hour: 'numeric', minute: 'numeric', hour12: true});
		var finalRescheduleDateTime = rescheduleDate + "/" + rescheduleMonth + "/" + rescheduleYear + ", " + rescheduleHour;
		if (finalRescheduleDateTime != '') {
			$('#resBkg' + id).show();
			$("#rescheduleBkg" + id).text(finalRescheduleDateTime);
		}
	}
	;
	var canBookCSRFToken = '<?php echo Yii::app()->request->csrfToken; ?>';
	var booknow = new BookNow();
	$(document).ready(function () {
		var front_end_height = parseInt($(window).outerHeight(true));
		var footer_height = parseInt($("#footer").outerHeight(true));
		var header_height = parseInt($("#header").outerHeight(true));
		var ch = (front_end_height - (header_height + footer_height + 23));
		$("#content").attr("style", "height:" + ch + "px;");
	});
	function canBooking(booking_id) {
		$href = "<?= Yii::app()->createUrl('booking/canbooking') ?>";
		var $booking_id = booking_id;
		jQuery.ajax({type: 'GET',
			url: $href,
			data: {"booking_id": $booking_id},
			success: function (data)
			{

				data = strip_html_tags(data);
				booknow.showErrorMsg(data);
			}
		});
	}
	function viewBooking(obj) {
		var href2 = $(obj).attr("href");
		var bcode = $(obj).attr("bkgcode");
		$.ajax({
			"url": href2,
			"type": "GET",
			"dataType": "html",
			"success": function (data) {
				document.getElementById("bookDetails").innerHTML = data;
			}
		});
		return false;
	}
	function ratetheJourney(booking_id) {
		$href = "<?= Yii::app()->createUrl('rating/addreview') ?>";
		var $booking_id = booking_id;
		jQuery.ajax({type: 'GET',
			url: $href,
			data: {"bkg_id": $booking_id},
			success: function (data)
			{
				var box = bootbox.dialog({
					message: data,
					title: 'Review',
					onEscape: function () {
					},
				});
			}
		});
	}
	function showreview(obj) {
		var href = $(obj).attr("href");
		var bkg_id = $(obj).attr("bkgId");
		jQuery.ajax({type: 'GET',
			url: href,
			dataType: 'html',
			success: function (data)
			{
				document.getElementById("rating-details-" + bkg_id).innerHTML = data;
			}
		});
		return false;
	}
	function receipt(booking_id, hsh)
	{
		$href = "<?= Yii::app()->createUrl('booking/receipt') ?>";
		var $booking_id = booking_id;
		window.open($href + "/bkg/" + $booking_id + "/hsh/" + hsh, '_blank');
	}
	function verifyBooking(booking_id, hash) {
		$href = "<?= Yii::app()->createUrl('booking/verifybooking') ?>";
		var $booking_id = booking_id;
		var $hash = hash;
		jQuery.ajax({type: 'GET',
			url: $href,
			data: {"booking_id": $booking_id},
			success: function (data)
			{
				if (data == true) {
					confirmBooking($booking_id, $hash);
				} else {
					booknow.showErrorMsg('Insufficient data. Please contact our customer support.');
				}
			}
		});
	}

	function confirmBooking($booking_id, $hash) {
		var href1 = '<?= Yii::app()->createUrl('booking/confirmmobile') ?>';
		jQuery.ajax({'type': 'GET', 'url': href1,
			'data': {'bid': $booking_id, 'manual': 'manual', 'hsh': $hash},
			success: function (data) {
				box = bootbox.dialog({
					message: data,
					title: '',
					size: 'medium',
					onEscape: function () {
					}
				});
			},
			error: function (xhr, ajaxOptions, thrownError) {

			}
		});
	}
	function strip_html_tags(str)
	{
		if ((str === null) || (str === ''))
			return false;
		else
			str = str.toString();
		return str.replace(/<[^>]*>/g, '');
	}


	var rpList = [];
	rpList = <?= $jsReasonPHList ?>;

	$('.cancelReason').change(function (event) {
		var id = $(event.currentTarget).data('id');
		var reason = $("#bkreason" + id).val();
		if (reason != '') {
			$("#bkreasontext" + id).attr('placeholder', rpList[reason]);
			$("#reasontext" + id).show();
			$("#bkreasontext" + id).attr('required', 'required');
		}
	});


	$('.cancelButton').click(function (event) {

		var href = '<?= Yii::app()->createUrl('booking/canbooking') ?>';
		var bkid = $(event.currentTarget).data('id');
		var bkreason = $('#bkreason' + bkid).val();
		var bkreasontext = $('#bkreasontext' + bkid).val();
		if (bkreason == '')
		{
			booknow.showErrorMsg('Please select cancelation reason');
		} else if (bkreasontext == '')
		{
			booknow.showErrorMsg('Description Can not be blank');
		} else
		{
			$.ajax({
				'type': 'post',
				'url': href,
				'dataType': 'text',
				'data': {'bk_id': bkid, 'bkreason': bkreason, 'bkreasontext': bkreasontext, 'view': 'mobile', 'YII_CSRF_TOKEN': canBookCSRFToken},
				success: function (data)
				{
					if (data == 'success') {
						window.location.reload(true);
					}

				}
			});
		}
	});

	$('.cancelModal').click(function (event) {

		var bkid = $(event.currentTarget).data('id');
		if (checkTripStatus(bkid))
		{
			var href = '<?= Yii::app()->createUrl('booking/canbooking') ?>';
			$.ajax({
				'type': 'get',
				'url': href,
				'dataType': 'text',
				'data': {'booking_id': bkid, 'view': 'mobile', 'YII_CSRF_TOKEN': canBookCSRFToken},
				success: function (data)
				{
					if (data != '') {
						booknow.showErrorMsg(data);
					} else
					{
						$('#cancel-modal' + bkid).click();
					}

				}
			});
		}
	});

	function checkTripStatus(booking_id)
	{
		$href = "<?php echo Yii::app()->createUrl('booking/CheckTripStatus') ?>";
		var $booking_id = booking_id;
		$.ajax({type: 'GET',
			url: $href,
			data: {"booking_id": $booking_id},
			success: function (data)
			{

				var dt = JSON.parse(data);

				var msg = dt.message;
				var retVal = confirm(msg);
				if (retVal) {


					return true;
				}
			}
		});
		return true;
	}

	$('.editButton').click(function (event) {

		var href = '<?= Yii::app()->createUrl('booking/editnew') ?>';
		var bkid = $(event.currentTarget).data('id');
		var usercountrycode = $(".selected-dial-code").html();
		var usercontact = $.trim($('#fullContactNumber' + bkid).val());
		var useremail = $.trim($('#bkg_user_email' + bkid).val());
		var bkg_trip_duration = $('#bkg_trip_duration' + bkid).val();
		var bkg_pickup_date_date = $('#bkg_pickup_date_date' + bkid).val();
		var bkg_pickup_date_time = $('#bkg_pickup_date_time' + bkid).val();
		var hash = $('#hash' + bkid).val();
		var cont = usercontact.replace(/\s/g, '');
		//alert(usercontact.val.length);
		if (usercontact == "" || useremail == "")
		{
			booknow.showErrorMsg('Mobile No Or Email Cannot be blank');
			return false;
		} else if (!isValidEmail(useremail))
		{
			booknow.showErrorMsg('Invalid Email Address');
			return false;
		} else if (cont.length < 10 || cont.length > 12)
		{
			booknow.showErrorMsg('Invalid Mobile No');
			return false;
		} else if (isInteger(usercontact) == false) {
			booknow.showErrorMsg('Invalid Mobile No');
			return false;
		} else {
			$.ajax({
				'type': 'post',
				'url': href,
				'dataType': 'text',
				'data': {'bkg_id': bkid, 'hash': hash, 'BookingUser': {'bkg_country_code': usercountrycode, 'bkg_contact_no': usercontact,
						'bkg_user_email': useremail}, 'Booking': {'bkg_id': bkid, 'bkg_trip_duration': bkg_trip_duration, 'bkg_pickup_date_date': bkg_pickup_date_date, 'bkg_pickup_date_time': bkg_pickup_date_time}, 'view': 'mobile', 'YII_CSRF_TOKEN': canBookCSRFToken},
				success: function (data)
				{
					data1 = JSON.parse(data);
					if (data1.success == true) {
						window.location.reload(true);
					}

				}
			});
		}
	});


	$('.resheduleButton').click(function (event) {
		var bkid = $(event.currentTarget).data('id');
		var newTime = $('#Booking_timeSchedule' + bkid).val();
		if (newTime == "")
		{
			booknow.showErrorMsg('Please select time');
			return false;
		}
		if (confirm("Pickup time can be re-schedule only once. \n Do you want to reschedule pickup time?")) {
			saveReschedulePickupTime(bkid, newTime);
		} else {
			return false;
		}
	});

	function saveReschedulePickupTime(bkid, newTime)
	{
		var href = '<?= Yii::app()->createUrl('booking/savepickuptime') ?>';
		$.ajax({
			'type': 'post',
			'url': href,
			'dataType': 'text',
			'data': {"bkg_id": bkid, "timePrePost": 1, "timeSchedule": newTime, 'YII_CSRF_TOKEN': canBookCSRFToken},
			success: function (data)
			{
				data1 = JSON.parse(data);
				if (data1.success == true) {
					booknow.showSuccessMsg("Pickup time updated successfully.");
					window.location.reload(true);
				} else {
					booknow.showErrorMsg(data1.message);
				}

			}
		});
		return false;
	}

	function isValidEmail(val) {
		if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(val)) {
			return true;
		}
		return false;
	}

	function isInteger(s) {
		var i;
		s = s.toString();
		for (i = 0; i < s.length; i++) {
			var c = s.charAt(i);
			if (isNaN(c)) {
				return false;
			}
		}
		return true;
	}

</script>