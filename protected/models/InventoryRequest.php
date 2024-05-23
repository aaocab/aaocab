<?php

/**
 * This is the model class for table "inventory_request".
 *
 * The followings are the available columns in table 'inventory_request':
 * @property integer $irq_id
 * @property string $irq_from_zone_id
 * @property string $irq_date_requested
 * @property integer $irq_cab_type_id
 * @property integer $irq_vendor_current_supply
 * @property integer $irq_vendor_required
 * @property integer $irq_request_ctr
 * @property integer $irq_last_initiated_by
 * @property string $irq_last_initiated_at
 * @property integer $irq_first_initiated_by
 * @property string $irq_first_initiated_at
 * @property integer $irq_status
 */
class InventoryRequest extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'inventory_request';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('irq_cab_type_id, irq_vendor_current_supply, irq_vendor_required, irq_request_ctr, irq_last_initiated_by, irq_first_initiated_by, irq_status', 'numerical', 'integerOnly' => true),
			array('irq_from_zone_id', 'length', 'max' => 255),
			array('irq_date_requested, irq_last_initiated_at, irq_first_initiated_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('irq_id,irq_from_zone_id,irq_date_requested, irq_cab_type_id, irq_vendor_current_supply, irq_vendor_required, irq_request_ctr, irq_last_initiated_by, irq_last_initiated_at, irq_first_initiated_by, irq_first_initiated_at, irq_status', 'safe', 'on' => 'search'),
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
			'irq_id'					 => 'Irq',
			'irq_from_zone_id'			 => 'Irq From Zone',
			'irq_date_requested'		 => 'Irq Date Requested',
			'irq_cab_type_id'			 => 'Irq Cab Type',
			'irq_vendor_current_supply'	 => 'Irq Vendor Current Supply',
			'irq_vendor_required'		 => 'Irq Vendor Required',
			'irq_request_ctr'			 => 'Irq Request Ctr',
			'irq_last_initiated_by'		 => 'Irq Last Initiated By',
			'irq_last_initiated_at'		 => 'Irq Last Initiated At',
			'irq_first_initiated_by'	 => 'Irq First Initiated By',
			'irq_first_initiated_at'	 => 'Irq First Initiated At',
			'irq_status'				 => 'Irq Status',
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

		$criteria->compare('irq_id', $this->irq_id);
		$criteria->compare('irq_from_zone_id', $this->irq_from_zone_id, true);
		$criteria->compare('irq_date_requested', $this->irq_date_requested, true);
		$criteria->compare('irq_cab_type_id', $this->irq_cab_type_id);
		$criteria->compare('irq_vendor_current_supply', $this->irq_vendor_current_supply);
		$criteria->compare('irq_vendor_required', $this->irq_vendor_required);
		$criteria->compare('irq_request_ctr', $this->irq_request_ctr);
		$criteria->compare('irq_last_initiated_by', $this->irq_last_initiated_by);
		$criteria->compare('irq_last_initiated_at', $this->irq_last_initiated_at, true);
		$criteria->compare('irq_first_initiated_by', $this->irq_first_initiated_by);
		$criteria->compare('irq_first_initiated_at', $this->irq_first_initiated_at, true);
		$criteria->compare('irq_status', $this->irq_status);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return InventoryRequest the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public static function get_combinations($arrays)
	{
		$result	 = array();
		$arrays	 = array_values($arrays);
		$sizeIn	 = sizeof($arrays);
		$size	 = $sizeIn > 0 ? 1 : 0;
		foreach ($arrays as $array)
			$size	 = $size * sizeof($array);
		for ($i = 0; $i < $size; $i ++)
		{
			$result[$i] = array();
			for ($j = 0; $j < $sizeIn; $j ++)
				array_push($result[$i], current($arrays[$j]));
			for ($j = ($sizeIn - 1); $j >= 0; $j --)
			{
				if (next($arrays[$j]))
					break;
				elseif (isset($arrays[$j]))
					reset($arrays[$j]);
			}
		}
		return $result;
	}

	public function setData($bkgID)
	{
		$bkgModel	 = Booking::model()->findByPk($bkgID);
		$trans		 = DBUtil::beginTransaction();
		try
		{
			$fzones		 = ZoneCities::model()->findZoneByCity($bkgModel->bkg_from_city_id);
			$userInfo	 = UserInfo::getInstance();
			$zoneArr	 = array(explode(',', $fzones));
			$return		 = [];
			foreach ($zoneArr[0] as $value)
			{
				$model = $this->find('irq_from_zone_id=:fzone AND irq_status=1', ['fzone' => $value]);
				if (!$model)
				{
					$model							 = new InventoryRequest();
					$model->irq_first_initiated_by	 = $userInfo->getUserId();
					$model->irq_first_initiated_at	 = new CDbExpression('NOW()');
				}
				$current_supply						 = Vendors::model()->getVndCountByZone($value);
				$plusRequiredAmt					 = 10;
				$model->irq_from_zone_id			 = $value;
				$model->irq_date_requested			 = new CDbExpression('NOW()');
				$model->irq_cab_type_id				 = $bkgModel->bkg_vehicle_type_id;
				$model->irq_vendor_current_supply	 = $current_supply;
				$model->irq_vendor_required			 = $plusRequiredAmt;
				$model->irq_request_ctr				 = $model->irq_request_ctr + 1;
				$model->irq_last_initiated_by		 = $userInfo->getUserId();
				$model->irq_last_initiated_at		 = new CDbExpression('NOW()');
				if ($model->save())
				{
					$return = ['success' => true];
				}
				else
				{
					throw new Exception($model->getErrors());
					$return = ['success' => false, 'error' => $model->getErrors()];
				}
			}

			DBUtil::commitTransaction($trans);
		}
		catch (Exception $e)
		{
			Yii::log($e->getTraceAsString(), CLogger::LEVEL_ERROR);
			DBUtil::rollbackTransaction($trans);
		}
		return $return;
	}

	public function checkInventoryByFromCity($city)
	{
		$sql		 = "SELECT count(zct_zon_id)  FROM `zone_cities` "
				. "INNER JOIN inventory_request ON irq_from_zone_id  =   zct_zon_id "
				. " WHERE zct_cty_id = $city AND irq_status =1 ";
		$recordset	 = Yii::app()->db1->createCommand($sql)->queryScalar();
		return $recordset;
	}

	public function updateInventoryRequest()
	{
		$sql	 = "SELECT * FROM inventory_request WHERE irq_status =1";
		$record	 = DBUtil::queryAll($sql, DBUtil::SDB());
		foreach ($record as $value)
		{
			$zoneId			 = $value['irq_from_zone_id'];
			$oldSupply		 = $value['irq_vendor_current_supply'];
			$requiredSupply	 = $value['irq_vendor_required'];
			$current_supply	 = Vendors::model()->getVndCountByZone($zoneId);
			if ($current_supply >= ($oldSupply + $requiredSupply))
			{
				$updatesql = "UPDATE `inventory_request` SET `irq_status` = '0' WHERE `irq_id` = '" . $value['irq_id'] . "'";
				DBUtil::command($updatesql)->execute();
			}
		}
	}

	public function getZoneCount()
	{
		$sql		 = "SELECT COUNT(DISTINCT irq_from_zone_id) FROM `inventory_request`WHERE irq_status=1";
		$recordset	 = Yii::app()->db1->createCommand($sql)->queryScalar();
		return $recordset;
	}

	public function getZoneListByNMI()
	{
		$sql		 = "SELECT irq_from_zone_id,zon_id,zon_name FROM `inventory_request`
INNER JOIN zones ON irq_from_zone_id=zon_id
WHERE irq_status=1";
		$recordset	 = DBUtil::queryAll($sql, DBUtil::SDB());
		return $recordset;
	}

	public function checkNMIzonebyBkg($bkgID)
	{
		$sql		 = "SELECT * FROM `inventory_request` WHERE irq_from_zone_id IN"
				. "(SELECT zon_id FROM booking bkg INNER JOIN zone_cities fzonecity ON fzonecity.zct_cty_id = bkg.bkg_from_city_id "
				. "INNER JOIN zones fzone ON fzone.zon_id = fzonecity.zct_zon_id "
				. "LEFT JOIN inventory_request ON irq_from_zone_id=zon_id WHERE 1=1 AND bkg_id= $bkgID)AND irq_status=1";
		$zoneArray	 = DBUtil::queryAll($sql, DBUtil::SDB());
		return count($zoneArray);
	}

	public static function getNMIZoneId()
	{
		$sql		 = "SELECT GROUP_CONCAT(DISTINCT irq_from_zone_id) as zone_id FROM `inventory_request` WHERE irq_status=1";
		return DBUtil::queryScalar($sql, DBUtil::SDB());
		
	}

}
