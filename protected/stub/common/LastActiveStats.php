<?php

namespace Stub\common;

class LastActiveStats
{

    public $entityId, $entityType, $time, $latitude, $longtitude, $contactId, $active, $updateDate, $createDate;

    /** @var LastActiveStats */
    public function setData($latitude, $longtitude, $entityId, $entityType, $contactId)
    {
        $userInfo         = \UserInfo::getInstance();
        $this->entityId   = $entityId > 0 ? $entityId : 0;
        $this->entityType = $entityType > 0 ? $entityType : 0;
        $this->time       = \DBUtil::getCurrentTime();
        $this->latitude   = $latitude != "" || $latitude != NULL ? $latitude : 0;
        $this->longtitude = $longtitude != "" || $longtitude != NULL ? $longtitude : 0;
        $this->contactId  = $contactId > 0 ? $contactId : 0;
        $this->active     = 1;
        $this->updateDate = \DBUtil::getCurrentTime();
        $this->createDate = \DBUtil::getCurrentTime();
        return $this;
    }

}
