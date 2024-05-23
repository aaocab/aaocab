<?php

namespace Stub\common;

class Teams
{

	// Teams
	public $id, $name;

	/** @var Teams $model */
	public function setData($id)
	{
		$this->id	 = (int) $id;
		$this->name	 = \Teams::model()->getNames($id);

		return $this;
	}

	public function setDataValues($id, $name)
	{
		$this->id	 = (int) $id;
		$this->name	 = $name;
		return $this;
	}

	/* @var $mapModel \CatDepartTeamMap */
	/* @var $admModel \Admins */

	public function setAdminData(\Admins $admModel)
	{
		$data		 = [];
		$mapModel	 = $admModel->admProfiles;
		$cdtData	 = json_decode($mapModel->adp_cdt_id);
		$cdtId		 = "";
		foreach ($cdtData as $cdt)
		{
			$cdtId .= $cdt->cdtId . ",";
		}
		$cdt_id		 = \CatDepartTeamMap::getCatdepatTeamId(rtrim($cdtId, ","));
		$cdtModel	 = \CatDepartTeamMap::model()->findByPk($cdt_id);
		if (empty($cdtModel))
		{
			goto skipall;
		}
		$tId		 = $cdtModel->cdtTea->tea_id;
		$tName		 = $cdtModel->cdtTea->tea_name;
		$deptName	 = $cdtModel->cdtDpt->dpt_name;
		$catName	 = $cdtModel->cdtCat->cat_name;
		$teamName	 = $tName . " (" . $deptName . "/ " . $catName . ")";
		$this->id	 = (int) $tId;
		$this->name	 = $teamName;
		$data[]		 = $this;
		skipall:
		return $data;
	}

}
