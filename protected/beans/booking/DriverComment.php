<?php

/*
 * Description of DriverComment
 *
 * @author Deepak
 *  
 * @property string $bkgId
 * @property string $remarks
 * @property \Beans\common\Coordinates $coordinates
 */

namespace Beans\booking;

class DriverComment
{

	public $remarks, $createDate;

	/** @var \Beans\Booking $booking */
	public $booking;

	/** @var \Beans\common\Coordinates $coordinates */
	public $coordinates;

	public static function getData($row)
	{
		$obj				 = new DriverComment();
		$obj->booking		 = new \Beans\Booking();
		$obj->booking->id	 = (int) $row->bkgId;
		$obj->remarks		 = $row->remarks;
		$obj->coordinates	 = \Beans\common\Coordinates::setLatLng($row);
		return $obj;
	}

	public static function setData($row)
	{
		$obj				 = new DriverComment();
		$obj->booking		 = new \Beans\Booking();
		$obj->booking->id	 = (int) $row['bkg_id'];
		$obj->remarks		 = $row['blg_desc'];
		$obj->createDate	 = $row['blg_created'];
		$obj->userType		 = $row['user_type'];
		return $obj;
	}

	public function setDesc()
	{
		$desc = $this->remarks . ' (' . $this->coordinates->getCoordinateString() . ')';
		return $desc;
	}

	public function setList($data)
	{
		$dataList = [];
		foreach ($data as $row)
		{
			$dataList[] = DriverComment::setData($row);
		}
		return $dataList;
	}

	public function setTrackLogModel($userInfo, $platform = null, \BookingTrackLog $btlModel = null)
	{
		if ($btlModel == null)
		{
			$btlModel = new \BookingTrackLog();
		}
		$btlModel->btl_user_id		 = $userInfo::getUserId();
		$btlModel->btl_user_type_id	 = \UserInfo::TYPE_DRIVER;
		$btlModel->btl_bkg_id		 = $this->booking->id;
		$btlModel->btl_event_type_id = \BookingTrack::REMARKS_ADDED;
		$btlModel->btl_coordinates	 = $this->coordinates->getCoordinateString();
		$btlModel->btl_remarks		 = $this->remarks; 
		if ($platform)
		{
			$btlModel->btl_event_platform = $platform;
		}
		return $btlModel;
	}

}
