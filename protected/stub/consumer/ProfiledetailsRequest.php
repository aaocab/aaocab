<?php
namespace Stub\consumer;

class ProfiledetailsRequest
{
	
	public $userId;
	
	public function getModel($model = null)
	{		
		if ($model == null)
		{
			/* @var $model Users */		
			$model = new \Users();
		}
		$model->user_id	 = $this->userId;		
		return $model;
	}
}