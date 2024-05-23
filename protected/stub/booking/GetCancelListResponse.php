<?php

namespace Stub\booking;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class GetCancelListResponse 
{

    /** @var \Stub\common\CancellationList[] $CancellationList */
    public $cancellationList;
    public function setData($model)
    {
        foreach ($model as $model)
        {
            $cancelReason             = new \Stub\common\CancellationList();
            $cancelReason->setData($model);
            $this->cancellationList[] = $cancelReason;
        }
    }

}
