<?php

/**
 * This is the model class for table "booking_invoice_process".
 *
 * The followings are the available columns in table 'booking_invoice_process':
 * @property integer $bip_id
 * @property integer $bip_bir_id
 * @property string $bip_bkg_id
 * @property integer $bip_status
 */
class BookingInvoiceProcess extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'booking_invoice_process';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('bip_bir_id, bip_bkg_id, bip_status', 'required'),
			// array('bip_bir_id', 'bip_status', 'numerical', 'integerOnly' => true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('bip_id, bip_bir_id, bip_bkg_id, bip_status', 'safe', 'on' => 'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'bip_id'	 => 'Bip',
			'bip_bir_id' => 'Bip Bir',
			'bip_bkg_id' => 'Bip Bkg',
			'bip_status' => 'Bip Status',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria = new CDbCriteria;

		$criteria->compare('bip_id', $this->bip_id);
		$criteria->compare('bip_bir_id', $this->bip_bir_id);
		$criteria->compare('bip_bkg_id', $this->bip_bkg_id);
		$criteria->compare('bip_status', $this->bip_status);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BookingInvoiceProcess the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public static function CreateProcess($modelBkgInvReq)
	{
		$transaction = DBUtil::beginTransaction();
		try
		{
			$processBooking = BookingInvoiceProcess::getTotalRequestForProcess($modelBkgInvReq);
			foreach ($processBooking as $bkgId)
			{
				$processModel				 = new BookingInvoiceProcess();
				$processModel->bip_bir_id	 = $modelBkgInvReq->bir_id;
				$processModel->bip_bkg_id	 = $bkgId['bkg_id'];
				$processModel->bip_status	 = 2;
				$processModel->validate();
				if (!$processModel->save())
				{
					throw new Exception(json_encode($processModel->getErrors()), ReturnSet::ERROR_VALIDATION);
				}
			}
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $e)
		{
			DBUtil::rollbackTransaction($transaction);
			throw new Exception($e);
		}
		return true;
	}

	public static function getTotalRequestForProcess($modelBkgInvReq)
	{
		$agentid	 = $modelBkgInvReq->bir_agent_id;
		$frmdate	 = $modelBkgInvReq->bir_bkg_pickup_date_from;
		$todate		 = $modelBkgInvReq->bir_bkg_pickup_date_to;
		$requestCond = $modelBkgInvReq->bir_request_cond;

		$arrReqCond = [];
		if (trim($requestCond) != '' && $requestCond != null)
		{
			$arrReqCond = json_decode($requestCond, true);
		}
		if (count($arrReqCond) > 0 && isset($arrReqCond['bkgTypes']))
		{
			$cond = " AND bkg_booking_type IN ({$arrReqCond['bkgTypes']}) ";
		}

		$dateRange = " AND bkg_pickup_date BETWEEN '$frmdate' AND '$todate' ";

		if ($modelBkgInvReq->bir_request_type == 1)
		{
			$status = ' AND bkg_status IN(6,7,9) ';
		}
		if ($modelBkgInvReq->bir_request_type == 2)
		{
			$status = ' AND bkg_status IN(2,3,5,6,7,9) ';
		}
		if ($modelBkgInvReq->bir_request_type == 3)
		{
			$status = ' AND bkg_status IN(2,3,5) ';
		}

		$sql = "SELECT bkg_id 
				FROM booking 
				WHERE bkg_agent_id = '$agentid' AND bkg_active = 1 {$status} {$dateRange} {$cond} ";
		return DBUtil::queryAll($sql, DBUtil::SDB());
	}

	public function getTripSheetList($reqid)
	{
		$sql = "SELECT 
		    bip.bip_id AS bipId,
		    bip.bip_bkg_id AS bkgId,
		    bkg.bkg_pickup_date,
		    bkg.bkg_create_date,
		    bkg.bkg_booking_id,
		    bip.bip_bir_id AS requestId,
		    CONCAT(fromCity.cty_name, ' - ', toCity.cty_name)
		  AS cities,
	       fromCity.cty_name
		  AS fromCity,
	       toCity.cty_name
		  AS toCity,
		  booking_user.bkg_user_fname,
		  booking_user.bkg_user_lname,
		  bkg.bkg_create_date,
	       bkg.bkg_pickup_date,
	       booking_invoice.bkg_base_amount,
	       bkg.bkg_trip_distance,
       booking_invoice.bkg_extra_toll_tax,
       booking_invoice.bkg_toll_tax,
       booking_invoice.bkg_extra_state_tax,
       booking_invoice.bkg_extra_km,
       booking_invoice.bkg_extra_km_charge,
       booking_invoice.bkg_extra_pickup_charge,
       booking_invoice.bkg_extra_drop_charge,
       booking_invoice.bkg_total_amount,
       booking_invoice.bkg_discount_amount,
       booking_invoice.bkg_parking_charge,
       booking_invoice.bkg_trip_waiting_charge,
       booking_invoice.bkg_rate_per_km,
       booking_invoice.bkg_rate_per_km_extra,
       booking_invoice.bkg_state_tax,
       booking_invoice.bkg_extra_state_tax,
       booking_invoice.bkg_service_tax bkg_service_tax,
	   booking_invoice.bkg_advance_amount,
       booking_invoice.bkg_refund_amount,
       drv.drv_name,
       vhc.vhc_number,
       bkg.bkg_status,
       btk.bkg_start_odometer,
       btk.bkg_end_odometer,
       btk.bkg_trip_start_time,
       btk.bkg_trip_end_time,
	   bcb.bcb_vendor_collected,
	   booking_invoice.bkg_partner_commission,
	   if(
          agents.agt_type = 2 AND bkg.bkg_status IN (5, 6, 7),
            booking_invoice.bkg_advance_amount
          - booking_invoice.bkg_refund_amount
          - booking_invoice.bkg_partner_commission,
          0)
          payableAmountNormal,
       if(
          agents.agt_type = 2 AND bkg.bkg_status = 9,
          round(
             if(
                agents.agt_commission_value = 1,
                (  (  booking_invoice.bkg_advance_amount
                    - booking_invoice.bkg_refund_amount)
                 * (100 - agents.agt_commission)
                 / (100 + booking_invoice.bkg_service_tax_rate)),
                0)),
          0)
          payableAmountCancelled
      
		FROM
		    booking_invoice_process AS bip
		INNER JOIN booking_invoice_request AS bir
		ON
		    bir.bir_id = bip.bip_bir_id AND bip.bip_bir_id = $reqid
		INNER JOIN booking bkg ON bkg.bkg_id = bip.bip_bkg_id
        INNER JOIN agents ON agents.agt_id = bkg.bkg_agent_id
        INNER JOIN cities fromCity ON bkg.bkg_from_city_id=fromCity.cty_id
		INNER  JOIN cities toCity ON bkg.bkg_to_city_id=toCity.cty_id
        INNER JOIN booking_user ON booking_user.bui_bkg_id=bkg.bkg_id
        INNER JOIN booking_invoice ON booking_invoice.biv_bkg_id=bkg.bkg_id AND bkg.bkg_active=1 AND bkg.bkg_status IN (2,3,5,6,7,9)
	INNER  JOIN booking_cab bcb ON bcb.bcb_id=bkg.bkg_bcb_id AND bcb.bcb_active=1
	INNER JOIN booking_track btk ON btk.btk_bkg_id = bkg.bkg_id
    LEFT JOIN `drivers` drv ON drv.drv_id=bcb.bcb_driver_id AND drv.drv_active>0
    LEFT JOIN `vehicles` vhc ON vhc.vhc_id=bcb.bcb_cab_id
	WHERE
		    bip.bip_status = 2 AND bir.bir_request_status = 0
		    ";
		return DBUtil::queryAll($sql);
	}

	public static function getDutySlipBybkgId($bkgid)
	{
		if ($bkgid == null || $bkgid == "")
		{
			throw new Exception("Required data missing", ReturnSet::ERROR_INVALID_DATA);
		}
		$params	 = array("bkgid" => $bkgid);
		$sql	 = "Select * from booking_pay_docs WHERE bpay_bkg_id =:bkgid AND bpay_status = 1";
		return DBUtil::queryAll($sql, DBUtil::SDB(), $params);
	}

	public static function createCsv($reqid)
	{
		$filename	 = "tripSheet_" . $reqid . ".csv";
		$rows		 = BookingInvoiceProcess::getTripSheetList($reqid);
		$filePath	 = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'uploads/sheet';
		if (!is_dir($filePath))
		{
			mkdir($filePath);
		}
		$backup_file = $filePath . DIRECTORY_SEPARATOR . $filename;
		$file		 = fopen($backup_file, 'w');
		fputcsv($file, [
			'Booking ID',
			'Traveller Name',
			'Cab No',
			'Driver name',
			'From City',
			'To City',
			'Pickup Date/Time',
			'Booking status',
			'Booking Date/Time',
			'Base Fare',
			'Included kms',
			'Start odometer',
			'End odometer',
			'Extra kms',
			'Rate per extra km',
			'Extra km Amount',
			'Start time',
			'End time',
			'Total kms run',
			'Toll charges (inc/exc)',
			'State Tax (inc/exc)',
			'Parking charges',
			'Waiting charge',
			'Billing sub-total (base + all charges)',
			'GST charges  (5% of subtotal)',
			'Total  (Sub-total + GST)',
			'Partner Commission',
			'Cash Collected',
			'Partner Payable',
			'Attachment link'
		]);
		foreach ($rows as $row)
		{
			$bkgstatus	 = Booking::model()->getActiveBookingStatus($row['bkg_status']);
			$paydocs	 = BookingInvoiceProcess::getDutySlipBybkgId($row['bkgId']);
			$arrdoc		 = [];
			foreach ($paydocs as $dutySlip)
			{
				$imgUrl		 = Yii::app()->params['fullBaseURL'] . $dutySlip['bpay_image'];
				$arrdoc []	 = $imgUrl;
			}
			$doclink							 = implode(',', $arrdoc);
			$rowArray							 = array();
			$rowArray['bkg_booking_id']			 = $row['bkg_booking_id'];
			$rowArray['traveller_name']			 = $row['bkg_user_fname'] . ' ' . $row['bkg_user_lname'];
			$rowArray['vhc_number']				 = $row['vhc_number'];
			$rowArray['drv_name']				 = $row['drv_name'];
			$rowArray['fromCity']				 = $row['fromCity'];
			$rowArray['toCity']					 = $row['toCity'];
			$rowArray['bkg_pickup_date']		 = $row['bkg_pickup_date'];
			$rowArray['bkg_status']				 = $bkgstatus;
			$rowArray['bkg_create_date']		 = $row['bkg_create_date'];
			$rowArray['bkg_base_amount']		 = $row['bkg_base_amount'];
			$rowArray['bkg_trip_distance']		 = $row['bkg_trip_distance'];
			$rowArray['bkg_start_odometer']		 = $row['bkg_start_odometer'];
			$rowArray['bkg_end_odometer']		 = $row['bkg_end_odometer'];
			$rowArray['bkg_extra_km']			 = $row['bkg_extra_km'];
			$rowArray['bkg_rate_per_km']		 = $row['bkg_rate_per_km'];
			$rowArray['bkg_extra_km_charge']	 = $row['bkg_extra_km_charge'];
			$rowArray['bkg_trip_start_time']	 = $row['bkg_trip_start_time'];
			$rowArray['bkg_end_start_time']		 = $row['bkg_trip_start_time'];
			$rowArray['bkg_total_km']			 = $row['bkg_trip_distance'] + $row['bkg_extra_km'];
			$rowArray['bkg_toll_tax']			 = $row['bkg_toll_tax'];
			$rowArray['bkg_state_tax']			 = $row['bkg_state_tax'];
			$rowArray['bkg_parking_charge']		 = $row['bkg_parking_charge'];
			$rowArray['bkg_trip_waiting_charge'] = $row['bkg_trip_waiting_charge'];
			$rowArray['bkg_subtotal_amount']	 = $row['bkg_base_amount'] + $row['bkg_toll_tax'] + $row['bkg_state_tax'];
			$rowArray['bkg_service_tax']		 = $row['bkg_service_tax'];
			$rowArray['bkg_total_amount']		 = $row['bkg_total_amount'];
			$rowArray['bkg_partner_commission']	 = $row['bkg_partner_commission'];
			$rowArray['cashcollected']			 = $row['bkg_total_amount'] - ($row['bkg_advance_amount'] + $row['bkg_refund_amount']);
			$rowArray['payable']				 = ($row['bkg_status'] == 9) ? $row['payableAmountCancelled'] : $row['payableAmountNormal'];
			$rowArray['attachment_link']		 = $doclink;
			$row1								 = array_values($rowArray);
			fputcsv($file, $row1);
			//fwrite($file, $row1);
		}
		fclose($file);

		//$link					 = Yii::app()->createAbsoluteUrl('booking/downloadDocs', ['birId' => $reqid, 'filename' => $filename]);
		$link								 = Yii::app()->params['fullBaseURL'] . '/booking/downloadDocs?birId=' . $reqid . '&filename=' . $filename;
		$requestModel						 = BookingInvoiceRequest::model()->findByPk($reqid);
		$requestModel->bir_request_status	 = 1;
		$requestModel->bir_download_link	 = $filename;
		if ($requestModel->save())
		{
			if ($requestModel['bir_request_user_email'] != '' && $link != '')
			{
				$agtmodel		 = Agents::model()->findByPk($requestModel->bir_agent_id);
				$emailwrapper	 = new emailWrapper();
				$emailwrapper->sendDownloadInvoiceLink($requestModel['bir_request_user_email'], $link, $requestModel['bir_request_user_id'], $requestModel['bir_id'], $agtmodel['agt_company'], $requestModel['bir_bkg_pickup_date_from'], $requestModel['bir_bkg_pickup_date_to'], 2);
			}
		}
	}

}
