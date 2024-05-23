<?php

/**
 * This is the model class for table "rating_attributes".
 *
 * The followings are the available columns in table 'rating_attributes':
 * @property integer $ratt_id
 * @property integer $ratt_applicable_to
 * @property integer $ratt_type
 * @property string $ratt_name
 * @property string $ratt_name_bad
 * @property integer $ratt_active
 * @property double $ratt_penalty_amount
 * @property double $ratt_bonus_amount
 */
class RatingAttributes extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'rating_attributes';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ratt_applicable_to, ratt_type', 'required'),
			array('ratt_applicable_to, ratt_type, ratt_active', 'numerical', 'integerOnly' => true),
			array('ratt_name, ratt_name_bad', 'length', 'max' => 100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('ratt_id, ratt_applicable_to, ratt_type, ratt_name, ratt_name_bad, ratt_active, ratt_penalty_amount, ratt_bonus_amount', 'safe', 'on' => 'search'),
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
			'ratt_id'				 => 'Ratt',
			'ratt_applicable_to'	 => 'Ratt Applicable To',
			'ratt_type'				 => 'Ratt Type',
			'ratt_name'				 => 'Ratt Name',
			'ratt_name_bad'			 => 'Ratt Name Bad',
			'ratt_active'			 => 'Ratt Active',
			'ratt_penalty_amount'	 => 'Ratt Penalty Amount',
			'ratt_bonus_amount'		 => 'Ratt Bonus Amount'
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

		$criteria->compare('ratt_id', $this->ratt_id);
		$criteria->compare('ratt_applicable_to', $this->ratt_applicable_to);
		$criteria->compare('ratt_type', $this->ratt_type);
		$criteria->compare('ratt_name', $this->ratt_name, true);
		$criteria->compare('ratt_name_bad', $this->ratt_name_bad, true);
		$criteria->compare('ratt_active', $this->ratt_active);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return RatingAttributes the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function ratingAttributes()
	{
		$sql			 = "SELECT
					ratt_id,
					ratt_applicable_to,
					ratt_name,
					ratt_name_bad
					FROM
						rating_attributes
					WHERE
						ratt_active = 1 
					ORDER BY rating_attributes.ratt_name  DESC";
		$allAttributes	 = DBUtil::queryAll($sql);
		$dataArray		 = [];
		if (count($allAttributes) > 0)
		{
			foreach ($allAttributes as $val)
			{
				if ($val['ratt_applicable_to'] == '1')
				{
					$dataArray['driver'][] = array("id" => (int) $val['ratt_id'], "positive" => $val['ratt_name'], "negative" => $val['ratt_name_bad']);
				}
				if ($val['ratt_applicable_to'] == '2')
				{
					$dataArray['csr'][] = array("id" => (int) $val['ratt_id'], "positive" => $val['ratt_name'], "negative" => $val['ratt_name_bad']);
				}
				if ($val['ratt_applicable_to'] == '3')
				{
					$dataArray['car'][] = array("id" => (int) $val['ratt_id'], "positive" => $val['ratt_name'], "negative" => $val['ratt_name_bad']);
				}
			}
		}
		return $dataArray;
	}

	/**
	 * 
	 * @param integer $type [ 1 => 'Front End', 2 => 'Admin End' ]
	 * @return array
	 */
	public function getRatingAttributes($type)
	{
		$dataArray = array();
		if ($type == '1')
		{
			$sql			 = " SELECT * FROM rating_attributes where ratt_active=1 ORDER BY rating_attributes.ratt_name  DESC";
			$allAttributes	 = DBUtil::queryAll($sql);
			foreach ($allAttributes as $val)
			{
				if ($val['ratt_applicable_to'] == '1')
				{
					$dataArray['driver']['good'][]	 = array("ratt_id" => (int) $val['ratt_id'], "ratt_name" => $val['ratt_name'], "ratt_name_bad" => $val['ratt_name_bad']);
					$dataArray['driver']['bad'][]	 = array("ratt_id" => (int) $val['ratt_id'], "ratt_name" => $val['ratt_name'], "ratt_name_bad" => $val['ratt_name_bad']);
				}
				if ($val['ratt_applicable_to'] == '2')
				{
					$dataArray['csr']['good'][]	 = array("ratt_id" => (int) $val['ratt_id'], "ratt_name" => $val['ratt_name'], "ratt_name_bad" => $val['ratt_name_bad']);
					$dataArray['csr']['bad'][]	 = array("ratt_id" => (int) $val['ratt_id'], "ratt_name" => $val['ratt_name'], "ratt_name_bad" => $val['ratt_name_bad']);
				}
				if ($val['ratt_applicable_to'] == '3')
				{
					$dataArray['car']['good'][]	 = array("ratt_id" => (int) $val['ratt_id'], "ratt_name" => $val['ratt_name'], "ratt_name_bad" => $val['ratt_name_bad']);
					$dataArray['car']['bad'][]	 = array("ratt_id" => (int) $val['ratt_id'], "ratt_name" => $val['ratt_name'], "ratt_name_bad" => $val['ratt_name_bad']);
				}
			}
		}
		if ($type == '2')
		{
			$sql			 = " SELECT * FROM rating_attributes WHERE ratt_active=1";
			$all_attributes	 = DBUtil::queryAll($sql);
			foreach ($all_attributes as $val)
			{
				$dataArray[$val['ratt_id']] = array("ratt_id" => (int) $val['ratt_id'], 'ratt_name' => $val['ratt_name'], 'ratt_name_bad' => $val['ratt_name_bad']);
			}
		}
		return $dataArray;
	}

	/**
	 * 
	 * @param integer $applicableTo [ 1=>Driver, 2=> CSR, 3=> Car ]	
	 * @param string $ids
	 * @param integer $type [ 1=>Good, 2=>Bad ]	
	 * @return string
	 */
	public static function getAttrByIds($applicableTo, $ids, $type = 1)
	{
		$attr = [];
		if ($ids != '' && $applicableTo > 0)
		{
			$sql	 = "SELECT * FROM `rating_attributes` WHERE rating_attributes.ratt_id IN ($ids) AND rating_attributes.ratt_applicable_to='$applicableTo'";
			$rows	 = DBUtil::queryAll($sql);
			$ctr	 = 0;
			foreach ($rows as $row)
			{
				if ($type == 1)
				{
					$attr[] = $row['ratt_name'];
				}
				else if ($type == 2)
				{
					$attr[] = $row['ratt_name_bad'];
				}
				$ctr++;
			}
		}
		return $attr;
	}

	public static function getIds($data)
	{//1:good;0:bad;
		$sql	 = "SELECT ratt_id,IF(`ratt_name` = '" . $data . "',1,0)as remarkTagGood,IF(`ratt_name_bad` = '" . $data . "',1,0)as remarkTagBad FROM `rating_attributes` WHERE 1 AND (`ratt_name` = '" . $data . "' || `ratt_name_bad` = '" . $data . "' )ORDER BY `ratt_id` DESC LIMIT 1";
		$result	 = DBUtil::queryRow($sql);
		return $result;
	}

	public function getDriverRatingTags($drvId)
	{
		$param	 = ['drv_id' => $drvId];
		$sql	 = "SELECT DISTINCT(r.rtg_customer_driver),
							CASE WHEN ra.ratt_type='OK' OR ra.ratt_type=1 THEN ratt_name
								 WHEN ra.ratt_type='OK2' OR ra.ratt_type=2 THEN ratt_name_bad
							END AS rating_tag,ra.ratt_type,
							CASE WHEN ra.ratt_type='OK' OR ra.ratt_type=1 THEN 'GOOD'
								 WHEN ra.ratt_type='OK2' OR ra.ratt_type=2 THEN 'BAD'
							END AS rating_type
							
							FROM drivers AS d 
							JOIN booking_cab AS bcb ON bcb.bcb_driver_id = d.drv_id 
							JOIN ratings AS r ON bcb.bcb_bkg_id1 = r.rtg_booking_id
							JOIN rating_attributes AS ra ON r.rtg_customer_driver=ra.ratt_id 
							WHERE d.drv_id = :drv_id AND r.rtg_customer_driver IS NOT NULL AND ra.ratt_applicable_to = 1";

		$data = DBUtil::queryAll($sql, DBUtil::SDB(), $param);
		return $data;
	}

}
