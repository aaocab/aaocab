<?php

namespace Stub\consumer;

/**
 * Description of ReviewCustomerRequest
 *
 * @author Maiti
 */
class RatingRequest
{

	public $bookingId, $overall, $review, $recommend, $platform;

	/** @var \Stub\common\RatingDetails $driver */
	public $driver;

	/** @var \Stub\common\RatingDetails $csr */
	public $csr;

	/** @var \Stub\common\RatingDetails $car */
	public $car;

	//put your code here
	public function getModel($model = null)
	{
		/** @var Ratings $model */
		if ($model == null)
		{
			$model = new \Ratings();
		}
		$model->rtg_booking_id			 = (int) $this->bookingId;
		$model->rtg_customer_overall	 = $this->overall;
		$model->rtg_customer_review		 = $this->review;
		$model->rtg_customer_recommend	 = $this->recommend;
		$model->rtg_platform			 = $this->platform;
		$this->car->getModelCustomerCar($model);
		$this->driver->getModelCustomerDriver($model);
		$this->csr->getModelCustomerCsr($model);

		return $model;
	}

}
