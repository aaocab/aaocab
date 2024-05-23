<?php

namespace Stub\common;

/**
 * Description of RatingDetails
 *
 * @author Roy
 */
class Rating
{

	public $bookingId, $overall, $review, $recommend, $platform;

	/** @var \Stub\common\RatingDetails $driver */
	public $driver;

	/** @var \Stub\common\RatingDetails $csr */
	public $csr;

	/** @var \Stub\common\RatingDetails $cab */
	public $cab;

	public function getModelData(\Ratings $model = null)
	{
		if ($model == null)
		{
			$model = new \Ratings();
		}
		$model->rtg_booking_id			 = $this->bookingId;
		$model->rtg_customer_overall	 = $this->overall;
		$model->rtg_customer_review		 = $this->review;
		$model->rtg_customer_recommend	 = $this->recommend;
		$model->rtg_platform			 = $this->platform;
		return $model;
	}

	public function setCarModelData(\Ratings $model = null)
	{
		$this->comment	 = $model->rtg_car_cmt;
		$this->rate		 = $model->rtg_customer_car;
	}

	public function setDriverModelData(\Ratings $model = null)
	{
		$this->comment	 = $model->rtg_driver_cmt;
		$this->rate		 = $model->rtg_customer_driver;
	}

	public function setCsrModelData(\Ratings $model = null)
	{
		$this->comment	 = $model->rtg_csr_cmt;
		$this->rate		 = $model->rtg_customer_csr;
	}

}
