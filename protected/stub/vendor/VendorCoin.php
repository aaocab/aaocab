<?php

namespace Stub\vendor;

class VendorCoin
{

	public $id, $entityId, $entityType, $type, $desc, $value, $dateTime, $dataList;

	public function getData($vendorCoinModel = '',$totalCoin)
	{
		$data->totalCoin = (int) $totalCoin;
		foreach ($vendorCoinModel as $models)
		{
			$obj				 = new \Stub\vendor\VendorCoin();
			$obj->fillCat($models);
			$data->dataList[]	 = $obj;
		}

		return $data;
	}

	
	public function getTotalCoin($totalCoin)
	{
		$data->totalCoin = (int) $totalCoin;
		return $data;
	}
	
	public function fillCat($model)
	{
		if ($model->vnc_type == 1)
		{
			$type = 'rating';
		}
		elseif ($model->vnc_type == 2)
		{
			$type = 'penalty';
		}
		elseif ($model->vnc_type == 3)
		{
			$type = 'gozonow';
		}
		$this->id		 = (int) $model->vnc_vnd_id;
		$this->entityId	 = (int) $model->vnc_ref_id;
		$this->entityType = (int) $model->vnc_ref_type;
		$this->type		 = ucfirst($type);
		$this->desc		 = ucfirst($model->vnc_desc);
		$this->value	 = (int) $model->vnc_value;
		$this->dateTime	 = date("Y-m-d H:i:s", strtotime($model->vnc_created_at));
	}
	
	

}
