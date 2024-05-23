<?php

namespace Stub\common;

/** @property string $value 
 *  @property string $frontPath 
 *  @property string $backPath 
 *  @property string $approvedDateTime 
 *  @property int $aprovedBy 
 *  @property string $expiryDateTime
 *  @property string $issueDateTime
 *  @property int $status 
 *  @property int $type
 *  @property boolean $isRequired
 */
class Documents1
{

	public $type;
	public $frontPath;
	public $backPath;
	public $approvedDateTime;
	public $aprovedBy;
	public $issueDateTime;
	public $status		 = 0;
	public $isRequired	 = false;
	public $checksum;

	/**
	 * @param \VehicleDocs $vhdModel
	 *  */
	public function setVehicleDocs($vhdModel)
	{
		$this->type				 = (int) $vhdModel->vhd_type;
		//$this->frontPath		 = $vhdModel->vhd_file;
		$this->frontPath		 = \VehicleDocs::getDocPathById($vhdModel->vhd_id);
		$this->approvedDateTime	 = $vhdModel->vhd_appoved_at;
		$this->status			 = (int) $vhdModel->vhd_status;
	}

	/**
	 * @param \VehicleDocs $vhdModel
	 *  
	 */
	public function getVhdModel($vhdModel = null)
	{
		if ($vhdModel == null)
		{
			$vhdModel = new \VehicleDocs();
		}
		$vhdModel->vhd_type		 = $this->type;
		$vhdModel->vhd_file		 = $this->frontPath;
		$vhdModel->vhd_checksum	 = $this->checksum;
		$vhdModel->vhd_status	 = 0;

		return $vhdModel;
	}
}
