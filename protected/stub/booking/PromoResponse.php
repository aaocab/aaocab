<?php

namespace Stub\booking;

class PromoResponse
{
    public $bookingId;

    /** @var \Stub\common\PromoDetails $promo */
    public $promo;
    

    /** @var \Stub\common\Fare $fare */
    public $fare;
    
    public $message;

    public function setData(\Booking $model, $promoModel, $message, \BookingInvoice $modelInvoice)
    {
        if ($model == null)
        {
            $model = new \Booking();
        }
        //print_r($result);exit;
        $this->bookingId = (int) $model->bkg_id;
        
        $this->fare = new \Stub\common\Fare();
        //$this->fare->setInvoicePromoData($model->bkgInvoice);
        $this->fare->setInvoicePromoData($modelInvoice);
        
        if($promoModel)
        {
           $this->promo = new \Stub\common\PromoDetails();
           $this->promo->setModelData($promoModel);
        }

        $this->message = $message;
        return $this;
    }

}
