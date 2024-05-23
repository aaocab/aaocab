<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Stub\common;

/**
 * Description of RatingDetails
 *
 * @author Roy
 * */
class RatingDetails
{

	public $star;
	public $recommend;
	public $comments;
	public $good_attrs	 = [];
	public $bad_attrs	 = [];

	/**
	 *  @param \Ratings $model
	 * 
	 */
	public function getModelCustomerCar($model = null)
	{
		if ($model == null)
		{
			$model = new \Ratings();
		}
		$model->rtg_car_cmt			 = $this->comments;
		$model->rtg_customer_car	 = (int) $this->star;
		$model->rtg_car_good_attr	 = implode(',', $this->good_attrs);
		$model->rtg_car_bad_attr	 = implode(',', $this->bad_attrs);
	}

	/**
	 * 
	 * @param \Ratings $model
	 */
	public function getModelCustomerDriver(\Ratings $model = null)
	{
		if ($model == null)
		{
			$model = new \Ratings();
		}
		$model->rtg_driver_cmt		 = $this->comments;
		$model->rtg_customer_driver	 = (int) $this->star;
		$model->rtg_driver_good_attr = implode(',', $this->good_attrs);
		$model->rtg_driver_bad_attr	 = implode(',', $this->bad_attrs);
	}

	/**
	 * 
	 * @param \Ratings $model
	 */
	public function getModelCustomerCsr(\Ratings $model = null)
	{
		if ($model == null)
		{
			$model = new \Ratings();
		}
		$model->rtg_csr_cmt			 = $this->comments;
		$model->rtg_customer_csr	 = (int) $this->star;
		$model->rtg_csr_good_attr	 = implode(',', $this->good_attrs);
		$model->rtg_csr_bad_attr	 = implode(',', $this->bad_attrs);
	}

	public function setOverallData(\Ratings $model)
	{
		if ($model == null)
		{
			return false;
		}
		$this->star		 = (int) $model->rtg_customer_overall;
		$this->comments	 = $model->rtg_customer_review;
		unset($this->bad_attrs);
		unset($this->good_attrs);
		return $this;
	}

}
