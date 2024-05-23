<?php
namespace Stub\consumer;

class CredithistoryRequest
{
	public $decision;
	
	
	public function getModel($model = null)
	{		
		if ($model == null)
		{			
			$model = new \UserCredits();
		}
		$model->creditStatus	 = $this->decision;		
		return $model;
	}
}