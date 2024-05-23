<?php

namespace Stub\consumer;

class Session extends \Stub\common\Consumer
{

	public $authId;
	/**
	 * 
	 * @param type $authId
	 * @param \Users $model
	 * @return boolean|$this
	 */
	public function setModelData($authId, \Users $model = null)
	{
		if ($model == NULL)
		{
			$model = new \Users();
		}
		$this->authId = $authId;
		if ($model->user_id > 0)
		{
			$this->profile->id				 = (int) $model->user_id;
			$this->profile->approveStatus	 = (int) $model->usr_active;
			$this->setDataSet($model);
		}
		return $this;
	}

	/**
	 * 
	 * @param string $authId
	 * @param integer $userId
	 * @deprecated
	 */
	public function setCustomerData($authId, $model)
	{
		$this->authId = $authId;
		if ($userId > 0)
		{
			$model			 = new \Stub\common\Consumer();
			$this->consumer	 = $model->setConsumerData($model);
		}
	}

}
