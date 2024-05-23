<?php

namespace Stub\vendor;

class StatusDetailsResponse
{

    public $isAgreement;
    public $isDocument;
    public $isCarAvailable;
    public $isDriver;
    public $isBussiness;
    public $isServiceType;
    public $isMemoLicence;
    public $isMessage;

    public $entityId;
    public $entityType;

    /** @var \Stub\common\Driver $driver */
    public $driver;

    /** @var \Stub\common\Vendor $vendor */
    public $vendor;

    /** @var \Stub\common\Cab $cab */
    public $cab;

    public $document;

	/**
     * @param \Vendors $model
     * @param \$result
     */
    public function setData($model, $result, $isMessage)
    {
        $this->isAgreement    = $model['is_agmt'];
        $this->isDocument     = $model['is_doc'];
        $this->isCarAvailable = $model['is_car'];
        $this->isDriver       = $model['is_driver'];
        $this->isBussiness    = $model['is_bussiness'];
        $this->isMemoLicence  = $model['is_memo_licence'];
        $this->isServiceType  = $model['isServiceType'];
        $this->isSocialLink   = $model['vnd_social_link'];
        $this->isMessage      = $isMessage;

        foreach($result as $row)
        {
            $obj              = new \Stub\vendor\StatusDetailsResponse();
            $obj->fillModelData($row);
            $this->dataList[] = $obj;
        }
    }

    public function fillModelData($row)
    {
        $this->entityId        = $row['entity_id'];
        $this->entityType      = $row['entity_type'];

        $driver          = new \Stub\common\Driver();
        $driver->fillData($row);
        $this->driver = $driver;

        $cab             = new \Stub\common\Cab();
        $cab->fillData($row);
        $this->cab = $cab; 

        $this->document = $row['docs'];
    }

}
