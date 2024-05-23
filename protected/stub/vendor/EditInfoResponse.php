<?php

namespace Stub\vendor;

/**
 * Description of User
 *
 * @author Maiti
 */

class EditInfoResponse 
{
    public $business;
    
    /** @var \Stub\common\Vendor $vendor*/
    public $vendor;

    /**
     * 
     * @param \Users $model
     */
    public function setModel(\Users $model, \Vendors $vendorModel)
    {
      //$this->profile->setData($model);
        
        $this->vendor = new \Stub\common\Vendor();
        $this->vendor->setData($vendorModel);
       
    }

}
