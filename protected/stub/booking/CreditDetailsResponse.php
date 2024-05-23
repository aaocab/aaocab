<?php

namespace Stub\booking;

class CreditDetailsResponse
{
    /** @var \Stub\common\Balance $balance */
    public $balance;
    public $creditAmount;
    
    public $creditDetails = [];

    public function setData($result = null, $creditAmount)
    {
        foreach ($result as $res)
		{
            $balance               = new \Stub\common\Balance();
            $this->creditDetails[] = $balance->setModelData($res);
     	}
        $this->creditAmount = $creditAmount;
        return $this;
    }

}
