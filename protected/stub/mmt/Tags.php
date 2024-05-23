<?php

namespace Stub\mmt;

class Tags
{

	public $sentimentRemarkGood ,$sentimentRemarkBad,$sentimentId;

	public function setRatings($data)
	{
        $dataSet                   = $data->name . ' ' . $data->sentiment;
        $dt                        = \RatingAttributes::getIds($dataSet);
        $this->sentimentRemarkGood = $dt['remarkTagGood'];
        $this->sentimentRemarkBad  = $dt['remarkTagBad'];
        $this->sentimentId         = $dt['ratt_id'];
    }
    

}
