<?php

namespace Stub\vendor;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class CabAvailabilities
{

	public $id, $startDate, $startTime, $duration, $isOneWay, $isShared, $isLocalTrip, $amount, $vendor,
			$source, $destination,
			$dataList, $cab, $driver;
	public
			$totalCount, $pageSize, $currentPage;

	public function fillData($row)
	{
		$this->id			 = (int) $row['cav_id'];
		$this->startDate	 = date('Y-m-d', strtotime($row['cav_date_time']));
		$this->startTime	 = date('H:i:s', strtotime($row['cav_date_time']));
		$this->duration		 = (int) $row['cav_duration'];
		$this->isOneWay		 = (int) $row['cav_is_oneway']|0;
		$this->isShared		 = (int) $row['cav_is_shared']|0;
		$this->isLocalTrip	 = (int) $row['cav_is_local_trip']|0;
		$this->amount		 = (int) $row['cav_amount'];
		$this->cab			 = new \Stub\common\Vehicle();
		$this->cab->fillData($row);
		$this->source		 = new \Stub\common\Location();
		$this->source->setData($row['cav_from_city'], $row['cav_from_city_name']);
		$this->destination	 = new \Stub\common\Location();
		$this->destination->setData($row['cav_to_cities'], $row['cav_to_city_name']);
	}

	public function getList($dataArr, $totalCount, $pageSize, $pageCount)
	{
		$this->totalCount	 = (int) $totalCount;
		$this->pageSize		 = (int) $pageSize;
		$this->currentPage	 = (int) $pageCount;

		foreach ($dataArr as $row)
		{
			$obj				 = new \Stub\vendor\CabAvailabilities();
			$obj->fillData($row);
			$this->dataList[]	 = $obj;
		}
	}

	public function getModel($model = null, $UserInfo)
	{
		if ($model == null)
		{
			$model = new \CabAvailabilities('insert');
		}
		$startDate					 = $this->startDate;
		$startTime					 = $this->startTime;
		$model->cav_date_time		 = $startDate . ' ' . $startTime;
		$model->cav_duration		 = $this->duration;
		$model->cav_from_city		 = $this->source->code;
		$model->cav_to_cities		 = $this->destination->code;
		$model->cav_amount			 = $this->amount;
		$model->cav_vendor_id		 = ($this->vendor->id == null || $this->vendor->id == "") ? $UserInfo->getEntityId() : $this->vendor->id;
		$model->cav_cab_id			 = $this->cab->id;
		$model->cav_driver_id		 = $this->driver->id;
		$model->cav_is_oneway		 = $this->isOneWay|0;
		$model->cav_is_shared		 = $this->isShared|0;
		$model->cav_is_local_trip	 = $this->isLocalTrip|0;
		$model->cav_status			 = 1;

		$model->attributes;
		return $model;
	}

}
