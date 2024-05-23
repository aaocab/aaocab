<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Beans\transferz;

class BookingRequest
{

	public $id, $preferred, $meetingPointRequired, $expiry, $status, $replacement;

	/** @var \Beans\transferz\Fleet $fleet */
	//public $fleet;

	/** @var \Beans\transferz\Journey $journey */
	public $journey;

	/** @var \Beans\transferz\MeetingPoints[] $meetingPoints */
	//public $meetingPoints;


	public function getModel($model = null)
	{
		if ($model == null)
		{
			$model = \Booking::getNewInstance();
		}
		$model->bkg_agent_id		 = \Config::get('transferz.partner.id');

		if ($this->journey == null)
		{
			$this->journey = new \Beans\transferz\Journey();
		}
		$model = $this->journey->setData($model);
		return $model;
	}

}
