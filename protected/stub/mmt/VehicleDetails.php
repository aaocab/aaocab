<?php

namespace Stub\mmt;

class VehicleDetails
{
    
    const COMBUSTIONTYPEUNKNOWN   = 'Unknown';
    const COMBUSTIONTYPEPETROL    = 'Petrol';
    const COMBUSTIONTYPECNG       = 'CNG';
    const COMBUSTIONTYPEDIESEL    = 'Diesel';
    const COMBUSTIONTYPEEELECTRIC = 'Electric';

    public $type;
    public $subcategory;
    public $combustion_type;
    public $model;
    public $make_year_type;
    public $make_year;
    public static $cabTypes  = ['hatchback' => '1', 'suv' => '2', 'sedan' => '3'];
    
    public $vehicle_number;
    public $vehicle_name;
    public $vehicle_color;






    public function getData($model)
	{
        $modelTypeId = '';
        $cabType = self::$cabTypes[$this->type];
		if($this->make_year == '')
		{
			$this->make_year = date("Y",strtotime("-11 year"));
		}
        $modelYear       = date("Y") - ($this->make_year);
		if(self::COMBUSTIONTYPEUNKNOWN == $this->combustion_type || self::COMBUSTIONTYPECNG == $this->combustion_type)
		{
			$combustionTypeIsCNG          = 1;
            $combustionTypeIsPetrolDiesel = 1;
			if($this->model != 'Unknown'){
				$combustionTypeIsCNG          = 0;
				$combustionTypeIsPetrolDiesel = 1;
			}
		}
		else{
			$combustionTypeIsCNG          = 0;
            $combustionTypeIsPetrolDiesel = 1;
		}
//        if (self::COMBUSTIONTYPEUNKNOWN == $this->combustion_type || self::COMBUSTIONTYPECNG == $this->combustion_type)
//        {
//            $combustionTypeIsCNG          = 1;
//            $combustionTypeIsPetrolDiesel = 1;
//            if ($modelYear > 0 && $modelYear <= 5)
//            {
//                $combustionTypeIsCNG          = 0;
//                $combustionTypeIsPetrolDiesel = 1;
//            }
//			else
//			{
//				$combustionTypeIsCNG          = 0;
//			}
//			
//        }
//        else
//        {
//            $combustionTypeIsCNG          = 0;
//            $combustionTypeIsPetrolDiesel = 1;
//        }
        if($this->model != 'Unknown')
        {
            $modelType = \VehicleTypes::getModelTypeId($this->model);
            $modelTypeId = $modelType['vht_id'];
            $model->bkg_vht_id = $modelTypeId;
			if($this->combustion_type == 'Unknown')
			{
				$modelTypeId = 0;
			}
        }
        else
        {
            $modelTypeId = 0;
        }
        
        $cabModel = \SvcClassVhcCat::getData($cabType,$modelTypeId,$modelYear, $combustionTypeIsCNG, $combustionTypeIsPetrolDiesel);
    
		$model->bkg_vehicle_type_id = $cabModel['scv_id'];
		if($model->bkg_vehicle_type_id == 72 || $model->bkg_vehicle_type_id == 73 || $model->bkg_vehicle_type_id == 74)
		{
			$model->bkgPref->bkg_cng_allowed = 1;
		}
		return $model;        
	}
    
    /**
	 * This function is used to get vehicle details
	 * @param Booking $model
     * @return [object]
	 */
    public function setData($model)
    {
        $vencabdriver         = $model->getBookingCabModel();
		$vehicleModel = $vencabdriver->bcbCab->vhcType->vht_model;
		if($vencabdriver->bcbCab->vhc_type_id === \Config::get('vehicle.genric.model.id'))
		{
			$vehicleModel = \OperatorVehicle::getCabModelName($vencabdriver->bcb_vendor_id, $vencabdriver->bcb_cab_id);
		}

        $this->vehicle_number = $vencabdriver->bcbCab->vhc_number;
        $this->vehicle_name   = $vehicleModel;
        $this->vehicle_color  = $vencabdriver->bcbCab->vhc_color;
    }

}
