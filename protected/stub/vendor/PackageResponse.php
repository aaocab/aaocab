<?php

namespace Stub\vendor;

class PackageResponse
{

	public $id;
	public $trackingNumber;
	public $packageCount;
	public $type;
	public $description;
	public $dataList;
	public static $packages = ['1' => 'Stricker', '2' => 'Bag'];

	public function getSendList($result)
	{


		foreach ($result as $row)
		{

			$obj = new \Stub\vendor\PackageResponse();
			$this->dataList[] = $obj->fillModelData($row);
			
		}

		return $this;
	}

	public function fillModelData($row)
	{


		$this->id				 = $row['vpk_id'];
		$this->type				 = self::$packages[$row['vpk_type']];
		$this->trackingNumber	 = $row['vpk_tracking_number'];
		$this->packageCount		 = $row['vpk_sent_count'];

		return $this;
	}

}
