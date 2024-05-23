<?php

/**
 * This is the model class for table "dialers".
 *
 * The followings are the available columns in table 'dialers':
 *
 */
class Dialers extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'dialers';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
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

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return QuotationDetails the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function dialerCheck($arr = [], $testMode = false)
	{
		$callerNumberRaw	 = $arr['callerNumber'];
		Filter::parsePhoneNumber($callerNumberRaw, $code, $callerNumber);
		$searchOption	 = '';
		if ($arr['callerType'] == 1)
		{
			$searchOption .= ""
					. " AND ((bkg_contact_no = '$callerNumber' "
					. "OR bkg_contact_no = '0$callerNumber' OR concat('0', bkg_contact_no) = '$callerNumber' "
					. "OR bkg_contact_no = '91$callerNumber' OR concat('91', bkg_contact_no) = '$callerNumber' "
					. "OR bkg_contact_no = concat(bkg_country_code,'$callerNumber') OR concat(bkg_country_code, bkg_contact_no) = '$callerNumber' "
					. ")"
					. " OR (bkg_alt_contact_no = '$callerNumber' "
					. "OR bkg_alt_contact_no = '0$callerNumber' OR concat('0', bkg_alt_contact_no) = '$callerNumber' "
					. "OR bkg_alt_contact_no = '91$callerNumber' OR concat('91', bkg_alt_contact_no) = '$callerNumber')"
					. "OR bkg_alt_contact_no = concat(bkg_alt_country_code,'$callerNumber') OR concat(bkg_alt_country_code, bkg_contact_no) = '$callerNumber' "
					. ")";
		}
		else if ($arr['callerType'] == 2)
		{
			$searchOption .= " AND length(bcb_driver_phone)>=10 AND (bcb_driver_phone = '$callerNumber'
		      OR  SUBSTRING(bcb_driver_phone,-10) = SUBSTRING('$callerNumber',-10) )      ";
		}
		else
		{
			return false;
		}
		if ($arr['tripID'] > 0)
		{
			$searchOption .= " AND bcb_id = " . $arr['tripID'];
		}
		if ($arr['bkgID'] > 0)
		{
			$searchOption = " AND bkg_id = " . $arr['bkgID'];
		}
		if ($arr['bookingID'] != '')
		{
			$bookID			 = $arr['bookingID'];
			$bkgidSize		 = (strlen($bookID) < 6) ? 6 : strlen($bookID);
			//$searchOption .= " AND bkg_booking_id = '" . $arr['bookingID'] . "'";
			$searchOption	 = " AND (SUBSTRING(bkg_booking_id,-$bkgidSize) = '" . $bookID . "' OR SUBSTRING(bkg_agent_ref_code,-$bkgidSize) = '" . $bookID . "')";
		}


		$sql = "
	    SELECT  bcb.bcb_id , concat(bkg_country_code,bkg_contact_no) fullContactNo, SUBSTRING(bcb_driver_phone,-10) bcb_driver_phone,
	    bkg.bkg_id,  bkg.bkg_status, bkg_booking_id,
	    (bkg_pickup_date - INTERVAL 6 HOUR) dur6hour,
		
	    bkg.bkg_pickup_date pickupDate,
	    (bkg.bkg_pickup_date + INTERVAL IFNULL(bkg.bkg_trip_duration, 0) MINUTE) tripEndTime,
	    IF(NOW() BETWEEN (bkg_pickup_date - INTERVAL 6 HOUR) AND (bkg.bkg_pickup_date + INTERVAL IFNULL(bkg.bkg_trip_duration, 0) MINUTE),1,0) durationActive,
	    IF(NOW()  > (bkg.bkg_pickup_date + INTERVAL IFNULL(bkg.bkg_trip_duration, 0) MINUTE),1,0) afterActive,
	    bkg.bkg_trip_duration
	    FROM booking_cab bcb
	    JOIN booking bkg ON bkg.bkg_bcb_id = bcb.bcb_id
	    JOIN booking_user  ON bui_bkg_id = bkg_id
	    WHERE 1 $searchOption
	    AND bcb.bcb_active = 1
	    AND bkg.bkg_status IN (2,3,5) AND LEAST(DATE_SUB(bkg_pickup_date, INTERVAL 6 HOUR), SubWorkingMinutes(60, bkg_pickup_date))<NOW()
	    GROUP BY bcb.bcb_id
		";
		if (!$testMode)
		{
			$sql .= "HAVING (NOW() < tripEndTime) ";
		}
		$sql .= " ORDER BY bkg_pickup_date DESC";

		$dataSet = DBUtil::queryAll($sql, DBUtil::SDB());
		return $dataSet;
	}

}
