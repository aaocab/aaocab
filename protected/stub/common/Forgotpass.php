<?php

namespace Stub\common;

class Forgotpass
{

	/** @var \Stub\common\Person $profile */
	public $profile;

	public function getModel($model = null)
	{
		if ($model == null)
		{
			/* @var $model Users */
			$model = new \Users();
		}
		$model->usr_email = $this->profile->email;

		return $model;
	}

}
