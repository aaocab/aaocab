<?php

namespace Stub\booking;

class WalletHistory
{
    /** @var \Stub\common\Balance $balance */
    public $balance;
    public $details = [];

    public function setData($result = null, $walletBalance)
    { 
		foreach ($result as $res)
		{
			$balance		 = new \Stub\common\Balance();
			$this->details[]	 = $balance->setWalletData($res);
		}
		$this->balance = (int) $walletBalance;
		return $this;
    }
}
