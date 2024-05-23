<?php

namespace Stub\common;

class DenyReasons 
{

	public $reasonId; //reason id.
	public $reason;
	
	
	

	public function setReasonData($dataArr)
	{
		#print_r($dataArr);
		foreach ($dataArr as $key =>$row)
		{
			$obj				 = new \Stub\common\DenyReasons();
			$obj->fillReasonData($row,$key);
			
			$this->dataList[]	 = $obj;
		}
	}
	public function fillReasonData($row, $key)
	{

		$this->id	 = (int) $key;
		$this->text	 = $row;
	}

}
