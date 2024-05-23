<?php

/**
 * This is the model class for table "bidSense".
 *
 * The followings are the available columns in table 'bidSense':
 * @property string $id
 * @property string $date
 * @property double $bidCount
 * @property double $vendorMaxAmount
 * @property double $vendorMinAmount
 * @property double $vendorAvgAmount
 * @property double $vendorMedianAmount
 * @property string $vendorPickupDate
 * @property string $rowIdentifier
 * @property double $vendorBidBins
 * @property double $active
 * @property string $created_at
 * @property string $modified_at
 */
class BidSense extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'bidSense';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('created_at, modified_at', 'required'),
			array('bidCount, vendorMaxAmount, vendorMinAmount, vendorAvgAmount, vendorMedianAmount, vendorBidBins, active', 'numerical'),
			array('rowIdentifier', 'length', 'max' => 20),
			array('date, vendorPickupDate', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, date, bidCount, vendorMaxAmount, vendorMinAmount, vendorAvgAmount, vendorMedianAmount, vendorPickupDate, rowIdentifier, vendorBidBins, active, created_at, modified_at', 'safe', 'on' => 'search'),
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
			'id'				 => 'ID',
			'date'				 => 'Date',
			'bidCount'			 => 'Bid Count',
			'vendorMaxAmount'	 => 'Vendor Max Amount',
			'vendorMinAmount'	 => 'Vendor Min Amount',
			'vendorAvgAmount'	 => 'Vendor Avg Amount',
			'vendorMedianAmount' => 'Vendor Median Amount',
			'vendorPickupDate'	 => 'Vendor Pickup Date',
			'rowIdentifier'		 => 'Row Identifier',
			'vendorBidBins'		 => 'Vendor Bid Bins',
			'active'			 => 'Active',
			'created_at'		 => 'tells you when it was created',
			'modified_at'		 => 'tells you when it was modified',
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

		$criteria->compare('id', $this->id, true);
		$criteria->compare('date', $this->date, true);
		$criteria->compare('bidCount', $this->bidCount);
		$criteria->compare('vendorMaxAmount', $this->vendorMaxAmount);
		$criteria->compare('vendorMinAmount', $this->vendorMinAmount);
		$criteria->compare('vendorAvgAmount', $this->vendorAvgAmount);
		$criteria->compare('vendorMedianAmount', $this->vendorMedianAmount);
		$criteria->compare('vendorPickupDate', $this->vendorPickupDate, true);
		$criteria->compare('rowIdentifier', $this->rowIdentifier, true);
		$criteria->compare('vendorBidBins', $this->vendorBidBins);
		$criteria->compare('active', $this->active);
		$criteria->compare('created_at', $this->created_at, true);
		$criteria->compare('modified_at', $this->modified_at, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BidSense the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public static function getAvgVendorAskingRate($rowIdentifier)
	{
		$sql= 'SELECT temp.avgAskingRate,temp.Type FROM 
			(
				(
					SELECT 
					1 AS "Type",
					ROUND((((SUM(IFNULL(bidSense.vendorMedianAmount,0))/COUNT(1)))),2) AS avgAskingRate
					FROM bidSense
					WHERE 1
					AND DATE(vendorPickupDate)=DATE(CURDATE())
					AND `rowIdentifier`=:rowIdentifier 
				)
			  UNION 
			   (
				SELECT 
				2 AS "Type",
				ROUND((((SUM(IFNULL(bidSense.vendorMedianAmount,0))/COUNT(1)))),2) AS avgAskingRate
				FROM bidSense
				WHERE 1
				AND (vendorPickupDate BETWEEN DATE(DATE_SUB(CURDATE(),INTERVAL 3 DAY)) AND DATE(DATE_ADD(CURDATE(),INTERVAL 3 DAY)))
				AND DATE(vendorPickupDate)>=DATE(CURDATE())
				AND `rowIdentifier`=:rowIdentifier
			   )
			  UNION
			  (
				SELECT 
				3 AS "Type",
				ROUND((((SUM(IFNULL(bidSense.vendorMedianAmount,0))/COUNT(1)))),2) AS avgAskingRate
				FROM bidSense 
				WHERE 1
				AND (vendorPickupDate BETWEEN DATE(DATE_SUB(CURDATE(),INTERVAL 7 DAY)) AND DATE(DATE_ADD(CURDATE(),INTERVAL 7 DAY)))
				AND DATE(vendorPickupDate)>=DATE(CURDATE())
				AND `rowIdentifier`=:rowIdentifier
			  )
			  UNION
			  (
				SELECT 
				4 AS "Type",
				ROUND((((SUM(IFNULL(bidSense.vendorMedianAmount,0))/COUNT(1)))),2) AS avgAskingRate
				FROM bidSense 
				WHERE 1
				AND (vendorPickupDate BETWEEN DATE(DATE_SUB(CURDATE(),INTERVAL 15 DAY)) AND DATE(DATE_ADD(CURDATE(),INTERVAL 15 DAY)))
				AND DATE(vendorPickupDate)>=DATE(CURDATE())
				AND `rowIdentifier`=:rowIdentifier
			  )
	    ) temp WHERE 1 AND temp.avgAskingRate IS NOT NULL ORDER  BY temp.Type DESC';
		$result						 = DBUtil::queryAll($sql, DBUtil::SDB(), ['rowIdentifier' => $rowIdentifier]);
		$i							 = 0;
		$askingRate					 = 0;
		$sumWeight					 = 0;
		$weigthPercentage			 = array("0" => 0.1, "1" => 0.2, "2" => 0.3, "3" => 0.4);
		$BidSenseWeigthPercentage	 = Config::get('BidSenseWeigthPercentage');
		if (!empty($BidSenseWeigthPercentage))
		{
			$results			 = CJSON::decode($BidSenseWeigthPercentage);
			$weigthPercentage	 = array("0" => $results['step_size_15day'], "1" => $results['step_size_7day'], "2" => $results['step_size_3day'], "3" => $results['step_size_1day']);
		}
		foreach ($result as $row)
		{
			if ($i == 0 && ( count($result) - 1) == 0)
			{
				$askingRate	 = $row['avgAskingRate'];
				$sumWeight	 = $weigthPercentage[$i];
			}
			else if ($i == 0)
			{
				$askingRate	 = $row['avgAskingRate'] * $weigthPercentage[$i];
				$sumWeight	 = $weigthPercentage[$i];
			}
			else if ((count($result) - 1) == $i)
			{
				$askingRate += $row['avgAskingRate'] * (1 - $sumWeight);
			}
			else
			{
				$askingRate	 += $row['avgAskingRate'] * $weigthPercentage[$i];
				$sumWeight	 += $weigthPercentage[$i];
			}
			$i++;
		}
		return $askingRate;
	}

}
