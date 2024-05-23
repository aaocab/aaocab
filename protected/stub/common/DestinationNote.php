<?php

namespace Stub\common;

class DestinationNote 
{

	public $noteId; //destination note id.
	public $note;
	public $noteAreaType;
	public $areaId;
    public $areaName;
	public $validFromDate;
	public $validFromTime;
	public $validToDate;
	public $validToTime;
    public $status;
	
	
	public function getData($dataArr)
	{
		$arr = [];
		foreach ($dataArr as $row)
		{
			$obj						 = new $this;
			$obj				 = new \Stub\common\DestinationNote();
			$obj->fillData($row);
			$arr[]						 = $obj;
		}
		return $arr;
		
	}
	public function fillData($row)
	{
		$this->noteId		 = (int) $row['dnt_id'];
		$this->noteAreaType	 =  \DestinationNote::model()->areatype[$row['dnt_area_type']];
		$this->note			 = $row['dnt_note'];
		$this->areaId		 = (int) $row['dnt_area_id'];
		$this->areaName		 = ($row['dnt_area_type'] == 0 ) ? "Global" : (($row['dnt_area_type'] == 2) ? $row['dnt_state_name'] : (($row['dnt_area_type'] == 3) ? $row['cty_name'] : \Promos::$region[$row['dnt_area_id']]));
		$this->validFromDate = $row['dnt_valid_from_date'];
		$this->validFromTime = $row['dnt_valid_from_time'];
		$this->validToDate	 = $row['dnt_valid_to_date'];
		$this->validToTime	 = $row['dnt_valid_to_time'];
		$this->status		 = ($row['dnt_active'] == 1) ? "Active" : "Inactive";
	}
	public function getModel($model= NULL)
	{
		
		if (!$model)
		{
			$model = new \DestinationNote();
		}
		
		$model->dnt_note			 = $this->note;
		$model->dnt_area_type		         =(strtolower($this->noteAreaType) == "global" ) ? 0 : ((strtolower($this->noteAreaType) == "state") ? 2 : ((strtolower($this->noteAreaType) == "city") ? 3 : ((strtolower($this->noteAreaType) == "region") ? 4 :'')));
		$model->dnt_area_id			 = (int)($this->areaId==""?0:$this->areaId);
		$model->dnt_valid_from_date		 = $this->validFromDate;
		$model->dnt_valid_from_time		 = $this->validFromTime;
		$model->dnt_valid_to_date	     = $this->validToDate;
		$model->dnt_valid_to_time	     = $this->validToTime;

		return $model;
	}
	public function getAreaData($dataList)
	{
	    $areas		 = [];
	    $cnt		 = 0;
	    foreach ($dataList as $da)
	    {
		    $areas[$cnt]['areaID']		 = (int) $da->id;
		    $areas[$cnt]['areaName']	 = $da->text;
		    $cnt++;
	    }
	    return $areas;
	}

	public function setAreaData($dataArr)
	{
		foreach ($dataArr as $row)
		{
			$obj				 = new \Stub\common\DestinationNote();
			$obj->fillAreaData($row);
			$this->dataList[]	 = $obj;
		}
	}
	public function fillAreaData($row)
	{
		$this->areaID				 = (int) $row['areaID'];
		$this->areaName				= $row['areaName'];
		
	}

}
