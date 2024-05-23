<?php

namespace Stub\consumer;

class ProfileUpdateResponse extends \Stub\common\Consumer
{
	public function setModel(\Users $model)
    {
        $this->setData($model);
    }

}
