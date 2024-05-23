<?php

namespace Stub\common;

class State
{

	public $id, $code,$name;

	public static function getList($data)
	{
		$arr = [];
		foreach ($data as $row)
		{
			$obj		 = new State();
			$obj->id	 = $row['stt_id'];
			$obj->name	 = $row['stt_name'];
			$arr[]		 = $obj;
		}

		return $arr;
	}
	public function getIdName($stateId)
	{
		$this->code= (int)$stateId;
		$modelCity = \States::model()->findByPk($this->code);
		$this->name				= $modelCity->stt_name;
		return $this;
		
	}
	public function setData($Id='')
	{
	   $this->id = (int)$Id;
	   $this->name = \States::model()->findUniqueZone($this->id); 
	   return $this;
	}

}
