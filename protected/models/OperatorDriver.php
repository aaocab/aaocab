<?php

/**
 * This is the model class for table "operator_driver".
 *
 * The followings are the available columns in table 'operator_driver':
 * @property integer $ord_id
 * @property integer $ord_drv_id
 * @property string $ord_operator_driver_id
 * @property integer $ord_contact_id
 * @property integer $ord_operator_id
 * @property integer $ord_status
 * @property string $ord_create_date
 */
class OperatorDriver extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'operator_driver';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ord_drv_id, ord_contact_id, ord_operator_id, ord_status', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('ord_id, ord_drv_id, ord_contact_id, ord_operator_id, ord_status, ord_create_date, ord_operator_driver_id', 'safe', 'on'=>'search'),
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
			'ord_id' => 'Ord',
			'ord_drv_id' => 'Ord Drv',
			'ord_contact_id' => 'Ord Contact',
			'ord_operator_id' => 'Ord Operator',
			'ord_status' => 'Ord Status',
			'ord_create_date' => 'Ord Create Date',
            'ord_operator_driver_id' => 'Ord Operator driver',
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

		$criteria->compare('ord_id',$this->ord_id);
		$criteria->compare('ord_drv_id',$this->ord_drv_id);
		$criteria->compare('ord_contact_id',$this->ord_contact_id);
		$criteria->compare('ord_operator_id',$this->ord_operator_id);
		$criteria->compare('ord_status',$this->ord_status);
		$criteria->compare('ord_create_date',$this->ord_create_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return OperatorDriver the static model class
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
		$criteria->compare('ord_operator_id', $data['operatorId']);
		$criteria->compare('ord_operator_driver_id', $data['operatorDriverId']);
		$exist		 = $this->find($criteria);

		return $exist;
	}

	/**
	 * 
	 * @param type $refId
	 * @return array
	 */
	public static function checkOperatorRefId($refId)
	{
		$params	 = ['operatorDriverId' => $refId];
		$sql = "SELECT COUNT(ord_operator_driver_id) drvCount, ord_drv_id FROM operator_driver WHERE ord_operator_driver_id = :operatorDriverId AND ord_status = 1";
		$result = DBUtil::queryRow($sql, DBUtil::SDB(), $params);
		return $result;
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
			$model							 = new OperatorDriver();
			$model->ord_drv_id				 = $data['driverId'];
			$model->ord_operator_id			 = $data['operatorId'];
			$model->ord_contact_id			 = $data['driverContactId'];
			$model->ord_operator_driver_id	 = $data['operatorDriverId'];
			if ($model->save())
			{
				return true;
			}
		}
		else
		{
			$exist->ord_status = 1;
			if ($exist->save())
			{
				return true;
			}
		}
		return false;
	}
}
