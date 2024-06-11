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

		$sql = "SELECT bkg_id, bkg_booking_id, bkg_pickup_date, bkg_route_city_names, bcb_driver_id, 
				btl.btl_sync_time left_time, btl.btl_coordinates left_coordinates, 
				btl2.btl_sync_time arrived_time, btl2.btl_coordinates arrived_coordinates, 
				bkg_trip_start_time, bkg_trip_start_coordinates, bkg_trip_end_time, bkg_trip_end_coordinates, 
				LEAST(bkg_pickup_date, IFNULL(btl.btl_sync_time, bkg_pickup_date), IFNULL(btl2.btl_sync_time, bkg_pickup_date), IFNULL(bkg_trip_start_time, bkg_pickup_date)) stTime, 
				GREATEST(IFNULL(bkg_trip_end_time, bkg_pickup_date), IFNULL(bkg_return_date, bkg_pickup_date), IF(bkg_booking_type NOT IN (2,3), DATE_ADD(bkg_pickup_date, INTERVAL (bkg_trip_duration + 60) MINUTE), DATE_ADD(bkg_pickup_date, INTERVAL (bkg_trip_duration + 240) MINUTE))) edTime 
				FROM booking 
				INNER JOIN booking_cab ON bcb_id = bkg_bcb_id 
				INNER JOIN booking_track ON bkg_id = btk_bkg_id 
				LEFT JOIN booking_track_log btl ON bkg_id = btl.btl_bkg_id AND btl.btl_event_type_id = 201 
				LEFT JOIN booking_track_log btl2 ON bkg_id = btl2.btl_bkg_id AND btl2.btl_event_type_id = 203 
				WHERE bkg_status IN (6,7) AND btk_gpx_file IS NULL 
				AND (
					(bkg_booking_type NOT IN (2,3) AND bkg_pickup_date >= DATE_SUB(NOW(), INTERVAL 4 DAY)) 
					OR (bkg_booking_type IN (2,3) AND bkg_pickup_date >= DATE_SUB(NOW(), INTERVAL 15 DAY)) 
				)
				ORDER BY bkg_pickup_date ASC 
				LIMIT 0, 1000";

		$results = DBUtil::query($sql, DBUtil::SDB());
		foreach ($results as $row)
		{
			$arrTrackData = [];

			$bkgId		 = $row['bkg_id'];
			$driverId	 = $row['bcb_driver_id'];
			$stTime		 = $row['stTime'];
			$edTime		 = $row['edTime'];

			if ($stTime == '' || $stTime == null || $edTime == '' || $edTime == null)
			{
				continue;
			}

			$arrBooking						 = [];
			$arrBooking['bkgId']			 = $row['bkg_id'];
			$arrBooking['bookingId']		 = $row['bkg_booking_id'];
			$arrBooking['pickupDate']		 = $row['bkg_pickup_date'];
			$arrBooking['routeCityNames']	 = $row['bkg_route_city_names'];

			if ($row['left_coordinates'] != '' && $row['left_coordinates'] != null)
			{
				$leftCoordinates = explode(',', trim($row['left_coordinates']));
				if (trim($leftCoordinates[0]) != '' && trim($leftCoordinates[1]) != '')
				{
					$track				 = [];
					$track['latitude']	 = trim($leftCoordinates[0]);
					$track['longitude']	 = trim($leftCoordinates[1]);
					$track['time']		 = $row['left_time'];
					$track['name']		 = 'Left For Pickup';

					$arrTrackData[] = $track;
				}
			}

			if ($row['arrived_coordinates'] != '' && $row['arrived_coordinates'] != null)
			{
				$arrivedCoordinates = explode(',', trim($row['arrived_coordinates']));
				if (trim($arrivedCoordinates[0]) != '' && trim($arrivedCoordinates[1]) != '')
				{
					$track				 = [];
					$track['latitude']	 = trim($arrivedCoordinates[0]);
					$track['longitude']	 = trim($arrivedCoordinates[1]);
					$track['time']		 = $row['arrived_time'];
					$track['name']		 = 'Arrived For Pickup';

					$arrTrackData[] = $track;
				}
			}

			if ($row['bkg_trip_start_coordinates'] != '' && $row['bkg_trip_start_coordinates'] != null)
			{
				$startCoordinates = explode(',', trim($row['bkg_trip_start_coordinates']));
				if (trim($startCoordinates[0]) != '' && trim($startCoordinates[1]) != '')
				{
					$track				 = [];
					$track['latitude']	 = trim($startCoordinates[0]);
					$track['longitude']	 = trim($startCoordinates[1]);
					$track['time']		 = $row['bkg_trip_start_time'];
					$track['name']		 = 'Trip Started';

					$arrTrackData[] = $track;
				}
			}

			if ($row['bkg_trip_end_coordinates'] != '' && $row['bkg_trip_end_coordinates'] != null)
			{
				$endCoordinates = explode(',', trim($row['bkg_trip_end_coordinates']));
				if (trim($endCoordinates[0]) != '' && trim($endCoordinates[1]) != '')
				{
					$track				 = [];
					$track['latitude']	 = trim($endCoordinates[0]);
					$track['longitude']	 = trim($endCoordinates[1]);
					$track['time']		 = $row['bkg_trip_end_time'];
					$track['name']		 = 'Trip Ended';

					$arrTrackData[] = $track;
				}
			}

			$sqlLoc = "SELECT DISTINCT loc_time, loc_lat, loc_lng, loc_desc 
						FROM location 
						WHERE loc_status = 1 
						AND (loc_event_id IS NULL OR loc_event_id NOT IN (107,108,109,110,503,504)) AND loc_desc IS NULL 
						AND (
							(loc_ref_type = 1 AND loc_ref_id = {$bkgId}) 
							OR (loc_entity_type = 3 AND loc_entity_id = {$driverId} AND loc_time BETWEEN '{$stTime}' AND '{$edTime}') 
						) 
						ORDER BY loc_time ASC";

			$resLoc = DBUtil::query($sqlLoc, DBUtil::SDB());
			if ($resLoc)
			{
				foreach ($resLoc as $rowloc)
				{
					$track				 = [];
					$track['latitude']	 = $rowloc['loc_lat'];
					$track['longitude']	 = $rowloc['loc_lng'];
					$track['time']		 = $rowloc['loc_time'];
					$track['name']		 = ($rowloc['loc_desc'] == null ? '' : $rowloc['loc_desc']);

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
