<?php

namespace Beans\vendor;

/**
 * Description of BidRequest
 *
 * @author Dev
 * @property string $ids
 * @property string $codes
 * @property string $pickupDate
 * @property string $createDate
 * @property string $tripType
 * @property string $quotedDistance
 * @property string $preferences
 * @property string \Beans\common\Cab $cabType
 * @property string \Beans\booking\Fare $fare
 * @property string \Beans\booking\Route $routes
 * 
 */
class PendingBookingResponse extends \Beans\Booking
{

	public $ids;
	public $codes;
	public $pickupDate;
	public $createDate;
	public $tripType;
	public $quotedDistance;
	public $preferences;

	/** @var \Beans\common\Cab $cabType */
	public $cabType;

	/** @var \Beans\booking\Fare $fare */
	public $fare;

	/** @var \Beans\booking\Route $routes */
	public $routes;

	public function setTripData($tripData)
	{

		$objVal = new \Beans\common\ValueObject();

		$this->ids			 = $tripData->bkgIds;
		$this->status		 = (int) $tripData->bkg_status;
		$this->codes		 = $tripData->bkgBookingIds;
		$this->pickupDate	 = $tripData->bkg_pickup_date;
		$this->createDate	 = $tripData->bkg_create_date;

//		$type = \Filter::convertToObject(['id' => $tripData->bkg_booking_type, 'label' => $tripData->booking_type]);
//		$objVal->fillData($type);
		$tripObj = \Beans\common\ValueObject::setBookingType($tripData->bkg_booking_type, $tripData->bcb_trip_type);

		$this->tripType = $tripObj;

		$this->quotedDistance	 = $tripData->bkg_trip_distance;
		$this->preferences		 = \Beans\booking\Preferences::setData($tripData);

		$this->cabType = \Beans\common\Cab::setData($tripData);

		$this->fare = \Beans\booking\Fare::setData($tripData);

		$this->routes = \Beans\booking\Route::setDataByBkgIds($tripData->bkgIds);
	}

}
