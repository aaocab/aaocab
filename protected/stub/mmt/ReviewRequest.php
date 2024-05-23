<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Stub\mmt;

/**
 * Description of ReviewRequest
 *
 * @author Ankesh
 * @property Tags $tag
 */
class ReviewRequest
{

    public $vendor_id;
    public $partner_name;
    public $order_reference_number;
    public $fleet_owner_id;
    public $chauffeur;
    public $vehicle;
    public $mismatch_reported, $rating, $review, $crowd_sourcing_data;

    /** @var \Stub\mmt\Tags[] $tag */
    public $tags, $tags1;

    public function getModel()
    {
        $booking  = \Booking::findByOrderNo($this->order_reference_number);
		$bkgId    = $booking->bkg_id;
        $rtgModel = \Ratings::findByBkgId($bkgId);

        if (!$rtgModel)
        {
            $model = \Ratings::getNewInstance();
        }
        else
        {
            $model = \Ratings::model()->findByPk($rtgModel->rtg_id);
        }
        $model->rtg_booking_id             = $bkgId;
        $model->rtg_driver_vendor_mismatch = $this->chauffeur->mismatch_reported;
        $model->rtg_customer_driver        = $this->chauffeur->rating;
		if ($rtgModel->rtg_customer_date == null)
		{
			$model->rtg_customer_date = \DBUtil::getCurrentTime();
		}
        $good = "";
        $bad  = "";
        $additionalChauffeurDetails = [];
        foreach ($this->chauffeur->tags as $data)
        {
            $dataSet       = $data->name . ' ' . $data->sentiment;
            $getAttributes = \RatingAttributes::getIds($dataSet);
            if ($getAttributes)
            {
                $tags = new \Stub\mmt\Tags();
                $tags->setRatings($data);
                if ($tags->sentimentRemarkGood == 1)
                {
                    $good .= "," . $tags->sentimentId;
                }
                if ($tags->sentimentRemarkBad == 1)
                {
                    $bad .= "," . $tags->sentimentId;
                }
            }
            else
            {
                
                array_push($additionalChauffeurDetails, 'Title : '. $data->name . ',   Value : ' . $data->sentiment);
            }
        }
        $model->rtg_driver_bad_attr  = trim($bad, ",");
        $model->rtg_driver_good_attr = trim($good, ",");

        $model->rtg_car_vendor_mismatch = $this->vehicle->mismatch_reported;
        $model->rtg_customer_car        = $this->vehicle->rating;
        $positive                       = "";
        $negative                       = "";
        $additionalVehicleDetails = [];
        foreach ($this->vehicle->tags as $data)
        {
            $dataSet = $data->name . ' ' . $data->sentiment;
            $getAttributes      = \RatingAttributes::getIds($dataSet);
            if ($getAttributes)
            {
                $tags = new \Stub\mmt\Tags();
                $tags->setRatings($data);
                if ($tags->sentimentRemarkGood == 1)
                {
                    $positive .= "," . $tags->sentimentId;
                }if ($tags->sentimentRemarkBad == 1)
                {
                    $negative .= "," . $tags1->sentimentId;
                }
            }
            else{
                array_push($additionalVehicleDetails, 'Title : '. $data->name . ',   Value : ' . $data->sentiment);
            }
            
        }
        $model->rtg_car_good_attr = trim($positive, ",");
        $model->rtg_car_bad_attr  = trim($negative, ",");

        $model->rtg_customer_review = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $this->review);
        if($this->review == null && $additionalChauffeurDetails || $additionalVehicleDetails)
        {
            $addtionalDetails = array_merge($additionalChauffeurDetails, $additionalVehicleDetails);
        }
        else
        {
            $addtionalDetails = array_merge($this->crowd_sourcing_data, $additionalChauffeurDetails, $additionalVehicleDetails);
        }
        $model->rtg_review_desc     = json_encode($addtionalDetails);
        
        if(($this->chauffeur->rating == null || $this->chauffeur->rating == '') && ($this->vehicle->rating != null || $this->vehicle->rating !=''))
        {
            $model->rtg_customer_overall = $this->vehicle->rating;
        }
        elseif(($this->chauffeur->rating != null || $this->chauffeur->rating != '') && ($this->vehicle->rating == null || $this->vehicle->rating ==''))
        {
            $model->rtg_customer_overall = $this->chauffeur->rating;
        }
        elseif(($this->chauffeur->rating != null || $this->chauffeur->rating != '') && ($this->vehicle->rating != null || $this->vehicle->rating !=''))
        {
            $model->rtg_customer_overall = round(($this->chauffeur->rating + $this->vehicle->rating)/2);
        }
        
        return $model;
        
    }

}
