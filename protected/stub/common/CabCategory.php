<?php

namespace Stub\common;

class CabCategory
{

	public $id, $type, $catClass, $scvParent, $scvVehicleId, $scvVehicleModel, $scvVehicleServiceClass, $scvmodel, $catRank, $catClassRank;

	public function setData($cabId)
	{
		/* @var $svcModel SvcClassVhcCat */
		$svcModel						 = \SvcClassVhcCat::model()->getVctSvcList('object', 0, 0, $cabId);
		$this->id						 = (int) $svcModel->scv_id;
		$this->type						 = \SvcClassVhcCat::getCatrgoryLabel($svcModel->scv_id);
		$this->catClass					 = $svcModel->scc_label;
		$this->scvParent				 = $svcModel->scv_parent_id;
		$this->scvVehicleId				 = $svcModel->scv_vct_id;
		$this->scvVehicleModel			 = $svcModel->scv_model;
		$this->scvVehicleServiceClass	 = $svcModel->scv_scc_id;
		$this->scvmodel					 = $svcModel->scv_label;
		$this->catRank					 = $svcModel->vct_rank;
		$this->catClassRank				 = $svcModel->scc_rank;
	}

}
