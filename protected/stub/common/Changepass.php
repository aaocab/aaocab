<?php

namespace Stub\common;

class Changepass
{

	public $newPassword;
	public $oldPassword;
	public $repeatPassword;

	public function getModel($model = null)
	{
		if ($model == null)
		{
			/* @var $model Users */
			$model = new \Users();
		}
		$model->new_password = $this->newPassword;
		$model->old_password = $this->oldPassword;
		return $model;
	}

	public function getData($model = null)
	{
		if ($model == null)
		{
			/* @var $model Users */
			$model = new \Users();
		}
		$model->new_password = $this->newPassword;
		$model->repeat_password = $this->repeatPassword;
		return $model;
	}

	public function getModelData()
	{
		$this->getModel();
		$model->repeat_password = $this->repeatPassword;
		return $model;
	}

}
