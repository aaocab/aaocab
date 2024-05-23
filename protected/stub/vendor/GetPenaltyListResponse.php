<?php

namespace Stub\vendor;

/**
 * Description of Get Penalty List Response
 *
 * @author Maiti
 */
class GetPenaltyListResponse
{

    public $reason;

    /**
     * @param $data 
     */
    public function setData($data)
    {
        $this->reason = $data;
    }

    /**
     * @param dataList 
     */
    public function getList()
    {
        $reasons = \Yii::app()->params['PenaltyReason'];
        foreach ($reasons as $row)
        {
            $obj = new \Stub\vendor\GetPenaltyListResponse();
            $obj->setData($row);
            $this->dataList[] = $obj;
        }
    }

}
