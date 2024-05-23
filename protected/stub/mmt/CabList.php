<?php

namespace Stub\mmt;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class CabList
{

    public $type;
    public $sku_id;
    public $subcategory;
    public $combustion_type;
    public $model;
    public $carrier;
    public $make_year_type;
    public $make_year;
    public $flags           = [];
    #public $vehicle_model;
    #public $vehicle_image;
    public $availability;
    #public $seat_capacity;
    #public $luggage_allowance;
    #public $duration;
    public $trip_tags       = [];
    public $aminities;
    public $icon;
    public $text;
    public $free_cancellation_window;
    public $min_payment_percentage;
    public $cancellation_rule;
    public static $cabTypes = [1 => 'hatchback', 2 => 'suv', 3 => 'sedan', 5=> 'sedan', 6=> 'suv'];
	public static $cancellationTypes = ['4' => 'FLEXI', '5' => 'SUPER_FLEXI', '9' => 'NON_REFUNDABLE'];
    public static $cabModel = [61, 63, 64, 66];
    
    /** @var \Stub\mmt\Fare $fare */
    public $fare;

    /** @var \Stub\mmt\Amenities $amenities */
    public $amenities;

    public function setQuote(\Quote $quote, $showModel = false, $objData)
    {
        $svcModel    = \SvcClassVhcCat::model()->findByPk($quote->skuId);
        $vhcCategory = $svcModel->scv_vct_id;
        if (array_key_exists($vhcCategory, self::$cabTypes))
        {
            $svcModelCat = \SvcClassVhcCat::model()->getVctSvcList('object', 0, 0, $quote->skuId);

            $this->sku_id          = $svcModel->scv_code;
            $this->type            = self::$cabTypes[$vhcCategory];
            $this->subcategory     = 'basic';

			$isMMTNewCancellationEnable = \Config::get('isMMT.newCabcellationPolicy.enable');

			if($isMMTNewCancellationEnable == 0)
            {
				$cityCategory = \CitiesStats::getCategory($quote->routes[0]->brt_from_city_id);
				$cancellationRule = \CancellationPolicy::getPolicy($cityCategory, $svcModelCat->scc_id);
				$this->cancellation_rule = $cancellationRule['rule'];
			}
			else
			{
				$svcModel                           = \SvcClassVhcCat::model()->getVctSvcList('object', 0, 0, $svcModelCat->scv_id);
				$cancelRuleId						 = \CancellationPolicy::getCancelRuleId($quote->partnerId, $svcModel->scv_id, $quote->routes[0]->brt_from_city_id, $quote->routes[0]->brt_to_city_id, $quote->tripType, $isGozonow = 0, $fromTopZoneCat = true);
				$this->cancellation_rule	 = self::$cancellationTypes[$cancelRuleId];
			}
            
            if ($svcModelCat->scc_id == 6)
            {
                    $this->combustion_type = 'Unknown';
            }
            else
            {
					$this->combustion_type = 'Diesel';
					if (in_array('CO', $objData->search_tags))
					{
						$this->combustion_type = 'Unknown';
					} 
            }
            if ($svcModelCat->scc_id == 1 || $svcModelCat->scc_id == 6)
            {
                    $this->min_payment_percentage = 20;
            }
            else
            {
                    $this->min_payment_percentage = 50;
            }
            if ($svcModel->scv_model > 0)
            {
                $cabModel    = \VehicleTypes::model()->findByPk($svcModel->scv_model);
                $this->model =  $cabModel->vht_model;
            }
            else
            {
                $this->model                    = 'Unknown';
            }

            $this->carrier = ($quote->skuId == 1 || $quote->skuId == 3) ? $this->carrier = false : $this->carrier = true;
            
            if (in_array($quote->skuId, [1, 2, 3, 5, 6, 72, 73, 74, 75]))
            {
                $this->make_year_type = "Unknown";
            }
            else
            {
                $this->make_year_type = "Newer";
                $this->make_year      = date("Y") - ($svcModelCat->scc_model_year);
            }
            if (in_array($quote->tripType, [4, 12]))
            {
                $this->trip_tags[] = "AT";
            }

            $this->flags[] = "CS";

            $this->amenities = new Amenities();
            $this->amenities->setCabType($quote->skuId);

            $this->fare_details = new Fare();
            $this->fare_details->setQuoteData($quote);
        }
    }

    /**
     * This function is used for setting the cab type and fare container
     * 
     * @param type $cabId = scvId
     * @param type $bkgInvoice = bkg Invoice
     */
    public function getAminities($quote)
    {
        #print_r($quote);
        $this->icon = "icon";
        $this->text = "AC";
    }

}
