<?php

/**
 * This is the model class for table "operator_vehicle".
 *
 * The followings are the available columns in table 'operator_vehicle':
 * @property integer $orv_id
 * @property integer $orv_vht_id
 * @property string $orv_operator_id
 * @property string $orv_model
 * @property string $orv_make
 * @property integer $orv_vhc_id
 * @property integer $orv_operator_vehicle_id
 * @property integer $orv_status
 * @property string $orv_create_date
 */
class OperatorVehicle extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'operator_vehicle';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('orv_id, orv_vht_id, orv_operator_id, orv_model, orv_vhc_id, orv_status, orv_create_date, orv_operator_vehicle_id, orv_make', 'safe', 'on'=>'search'),
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
			'orv_id' => 'Orv',
			'orv_vht_id' => 'Orv Vht',
			'orv_operator_id' => 'Orv Operator',
			'orv_model' => 'Orv Model',
			'orv_vhc_id' => 'Orv Vhc',
			'orv_status' => 'Orv Status',
			'orv_create_date' => 'Orv Create Date',
			'orv_operator_vehicle_id' => 'Orv operator Vhc'
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

		$criteria=new CDbCriteria;

		$criteria->compare('orv_id',$this->orv_id);
		$criteria->compare('orv_vht_id',$this->orv_vht_id);
		$criteria->compare('orv_operator_id',$this->orv_operator_id);
		$criteria->compare('orv_model',$this->orv_model,true);
		$criteria->compare('orv_vhc_id',$this->orv_vhc_id);
		$criteria->compare('orv_status',$this->orv_status);
		$criteria->compare('orv_create_date',$this->orv_create_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return OperatorVehicle the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * 
	 * @param type $data
	 * @return type
	 */
	public function checkExisting($data = [])
	{
		$criteria	 = new CDbCriteria;
		$criteria->compare('orv_operator_id', $data['operatorId']);
		$criteria->compare('orv_operator_vehicle_id', $data['operatorVehicleId']);
		$exist		 = $this->find($criteria);

		return $exist;
	}

	/**
	 * 
	 * @param type $data
	 * @return boolean
	 */
	public function checkAndSave($data = [])
	{
		$exist = $this->checkExisting($data);
		if (!$exist)
		{
			$model							 = new OperatorVehicle();
			$model->orv_vht_id				 = $data['vehicleTypeId'];
			$model->orv_operator_id			 = $data['operatorId'];
			$model->orv_model                = $data['vehicleModel'];
			$model->orv_make                 = $data['vehicleMake'];
			$model->orv_vhc_id				 = $data['vehicleId'];
			$model->orv_operator_vehicle_id  = $data['operatorVehicleId'];
			if ($model->save())
			{
				return true;
			}
		}
		else
		{
			$exist->orv_status = 1;
			if ($exist->save())
			{
				return true;
			}
		}
		return false;
	}

	/**
	 * 
	 * @param type $refId
	 * @return array
	 */
	public static function checkOperatorRefId($refId)
	{
		$params	 = ['operatorVehicleId' => $refId];
		$sql = "SELECT COUNT(orv_operator_vehicle_id) vhcCount, orv_vhc_id FROM operator_vehicle WHERE orv_operator_vehicle_id = :operatorVehicleId AND `orv_status` = 1";
		$result = DBUtil::queryRow($sql, DBUtil::SDB(), $params);
		return $result;
	}

	/**
	 * 
	 * @param type $operatorId
	 * @param type $vehicleId
	 * @return type
	 */
	public static function getCabModelName($operatorId, $vehicleId)
	{
		$params	 = ['operatorId' => $operatorId, 'vehicleId' => $vehicleId];
		$sql = "SELECT CONCAT(orv_model, ' ',orv_make) as vhcModel FROM operator_vehicle WHERE orv_vhc_id = :vehicleId AND orv_operator_id = :operatorId AND `orv_status` = 1 ORDER BY orv_id DESC LIMIT 0,1";
		$result = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
		return $result;
	}
}
