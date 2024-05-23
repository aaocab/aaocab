<?php

namespace Stub\vendor;

/**
 * Description of Info Response
 *
 * @author Maiti
 */
class InfoResponse
{

	public $lastLoginDate;
	public $lastLoginTime;
	public $overDue;
	public $active;
	public $freeze;
	public $reason;
	public $key;
	public $value;
	public $tips;
	public static $activeStatus	 = [0 => 'Deleted', 1 => 'Active', 2 => 'Deactive', 3 => 'Pending approval', 4 => 'Ready for approval'];
	public static $partnerType	 = [0 => 'Silver', 1 => 'Gold'];

	/** @var \Stub\common\Vendor $vendor */
	public $vendor;

	/** @var \Stub\common\Platform $device */
	public $device;

	/**
	 * @param \Vendors $model
	 */
	public function setData($data, $vendorId)
	{
		$this->lastLoginDate = date("Y-m-d", strtotime($data['lastLogin']));
		$this->lastLoginTime = date("H:i:s", strtotime($data['lastLogin']));
		$this->overDue		 = $data['overDue'];
		$this->freeze		 = (int) $data['freeze'];
		$this->active		 = (int) $data['active'];
		$this->rating		 = \VendorStats::fetchRating($vendorId);
		$this->apkVersion	 = $data['apk_version'];
		$this->docStatus	 = new \Stub\common\Document();
		$this->docStatus->setDocumentStatus($data);
		$this->vendor		 = new \Stub\common\Vendor();
		$this->vendor->setModelData($vendorId);
	}

	public function getList($dataArr)
	{

		foreach ($dataArr as $row)
		{
			$obj				 = new \Stub\vendor\InfoResponse();
			$obj->setData($row);
			$this->dataList[]	 = $obj;
		}
	}

	public function setMatrixDetails($data, $row)
	{


		$this->key = \Yii::app()->params['vendorMatrixkeys'][$data];
		
		if ($this->key == 'Partner status')
		{

			$this->value = self::$activeStatus[$row];
			goto tips;
		}
		
		if ($this->key == 'Partner level')
		{

			$this->value = self::$partnerType[$row];
			goto tips;
		}
		if ($this->key == 'Dependability')
		{
			$this->value = $row. '%';
		}
		
		
		tips:
		
		$this->tips	 = \Yii::app()->params['tipsVal'][$this->key];
	}

	public function getMatrix($dataArr)
	{
		$data = 0;
		foreach ($dataArr as $row)
		{
			$data				 = $data + 1;
			$obj				 = new \Stub\vendor\InfoResponse();
			$obj->setMatrixDetails($data, $row);
			$this->dataList[]	 = $obj;
		}
	}

}
