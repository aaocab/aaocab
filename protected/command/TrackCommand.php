<?php

class TrackCommand extends BaseCommand
{
	/*	 * This action is used for driver tracking after effects
	 * 
	 */

	public function actionProcessPostSyncDriverAppEvents()
	{
		$check = Filter::checkProcess("track processPostSyncDriverAppEvents");
        if (!$check)
        {
            return;
        }
		
		BookingScheduleEvent::postEvents();
	}

	/** Function is used to cancel booking which has no show */
	public function actionProcessNoShowCancelBooking()
	{
		$check = Filter::checkProcess("track processNoShowCancelBooking");
        if (!$check)
        {
            return;
        }
		
		$data = BookingSub::getNoShowBooking();

		$cancelReason = CancelReasons::getNoShowId();
		foreach ($data as $values)
		{
			$cancellationReason	 = $cancelReason['cnr_reason'];
			$reasonId			 = $cancelReason['cnr_id'];
			if ($values['bkg_status'] != 9)
			{
				Booking::model()->canbooking($values['bkg_id'], $cancellationReason, $reasonId);
			}
		}
	}

	/**
	 * Function for generating booking location GPX file
	 */
	public function actionGenerateBookingGPXFile()
	{
		$check = Filter::checkProcess("track generateBookingGPXFile");
        if (!$check)
        {
            return;
        }
		
		$sql	 = "SELECT bkg_id, bkg_booking_id, bkg_pickup_date, bkg_route_city_names 
			FROM booking 
			INNER JOIN booking_track ON bkg_id = btk_bkg_id 
			WHERE bkg_status IN (6,7) AND btk_last_event > 0 
			AND bkg_pickup_date BETWEEN DATE_SUB(NOW(), INTERVAL 4 DAY) AND DATE_SUB(NOW(), INTERVAL 3 DAY) 
			AND btk_gpx_file IS NULL 
			ORDER BY bkg_id DESC LIMIT 0, 25";
		$results = DBUtil::query($sql, DBUtil::SDB());
		foreach ($results as $row)
		{
			$arrBooking						 = [];
			$arrBooking['bkgId']			 = $row['bkg_id'];
			$arrBooking['bookingId']		 = $row['bkg_booking_id'];
			$arrBooking['pickupDate']		 = $row['bkg_pickup_date'];
			$arrBooking['routeCityNames']	 = $row['bkg_route_city_names'];

			$bkgId = $row['bkg_id'];

			$arrTrackData	 = [];
			$sqlLoc			 = "SELECT loc_time, loc_lat, loc_lng, loc_desc FROM location 
				WHERE loc_status = 1 AND loc_ref_type = 1 AND (loc_event_id IS NULL OR loc_event_id NOT IN (107,108,109,110,503,504)) 
				AND loc_ref_id = {$bkgId} ORDER BY loc_time ASC";
			$resLoc			 = DBUtil::query($sqlLoc, DBUtil::SDB());
			if ($resLoc)
			{
				foreach ($resLoc as $rowloc)
				{
					$track				 = [];
					$track['latitude']	 = $rowloc['loc_lat'];
					$track['longitude']	 = $rowloc['loc_lng'];
					$track['time']		 = $rowloc['loc_time'];
					$track['name']		 = $rowloc['loc_desc'];

					$arrTrackData[] = $track;
				}

				// GPX File
				$filePath = GPX::create($arrTrackData, $arrBooking);

				if ($filePath)
				{
					$params				 = [];
					$params['filePath']	 = $filePath;
					$params['bkgId']	 = $bkgId;

					$sqlUpd = "UPDATE booking_track SET btk_gpx_file=:filePath WHERE btk_bkg_id=:bkgId";
					DBUtil::execute($sqlUpd, $params);
				}
			}
		}
	}

}
