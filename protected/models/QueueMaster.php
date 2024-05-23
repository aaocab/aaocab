<?php

/**
 * This is the model class for table "queue_master".
 *
 * The followings are the available columns in table 'queue_master':
 * @property integer $qum_id
 * @property string $qum_label
 * @property integer $qum_is_customer
 * @property string $qum_customer_label
 * @property integer $qum_is_vendor
 * @property string $qum_vendor_label
 * @property integer $qum_is_driver
 * @property string $qum_driver_label
 * @property integer $qum_is_partner
 * @property string $qum_partner_label
 * @property integer $qum_active
 * @property string $qum_created_on
 */
class QueueMaster extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'queue_master';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('qum_label, qum_created_on', 'required'),
			array('qum_is_customer, qum_is_vendor, qum_is_driver, qum_is_partner, qum_active', 'numerical', 'integerOnly' => true),
			array('qum_label, qum_customer_label, qum_vendor_label, qum_driver_label, qum_partner_label', 'length', 'max' => 500),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('qum_id, qum_label, qum_is_customer, qum_customer_label, qum_is_vendor, qum_vendor_label, qum_is_driver, qum_driver_label, qum_is_partner, qum_partner_label, qum_active, qum_created_on', 'safe', 'on' => 'search'),
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
			'qum_id'			 => 'Qum',
			'qum_label'			 => 'Label',
			'qum_is_customer'	 => 'Is Customer',
			'qum_customer_label' => 'Customer Label',
			'qum_is_vendor'		 => 'Is Vendor',
			'qum_vendor_label'	 => 'Vendor Label',
			'qum_is_driver'		 => 'Is Driver',
			'qum_driver_label'	 => 'Driver Label',
			'qum_is_partner'	 => 'Is Partner',
			'qum_partner_label'	 => 'Partner Label',
			'qum_active'		 => 'Active',
			'qum_created_on'	 => 'Created On',
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

		$criteria->compare('qum_id', $this->qum_id);
		$criteria->compare('qum_label', $this->qum_label, true);
		$criteria->compare('qum_is_customer', $this->qum_is_customer);
		$criteria->compare('qum_customer_label', $this->qum_customer_label, true);
		$criteria->compare('qum_is_vendor', $this->qum_is_vendor);
		$criteria->compare('qum_vendor_label', $this->qum_vendor_label, true);
		$criteria->compare('qum_is_driver', $this->qum_is_driver);
		$criteria->compare('qum_driver_label', $this->qum_driver_label, true);
		$criteria->compare('qum_is_partner', $this->qum_is_partner);
		$criteria->compare('qum_partner_label', $this->qum_partner_label, true);
		$criteria->compare('qum_active', $this->qum_active);
		$criteria->compare('qum_created_on', $this->qum_created_on, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return QueueMaster the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * 
	 * @param integer $entType
	 * @return CDbDataReader
	 */
	public static function getListByEntityType($entType = 0)
	{

		$entLabel	 = self::getLabelFieldByEntityType($entType);
		$entShow	 = self::getShowFieldByEntityType($entType);
		$select		 = ",IFNULL($entLabel,qum_label)  label";
		$where		 = ($entType > 0 && $entShow != '') ? " AND $entShow = 1" : "";
		$sql		 = "SELECT qum_id id $select FROM queue_master WHERE qum_active=1 $where";
		$dataReader	 = DBUtil::query($sql, DBUtil::SDB());
		return $dataReader;
	}

	/**
	 * 
	 * @param integer $entType
	 * @return string
	 */
	public static function getLabelFieldByEntityType($entType = 0)
	{
		$label = 'qum_label';
		switch ($entType)
		{
			case UserInfo::TYPE_VENDOR:
				$label	 = 'qum_vendor_label';
				break;
			case UserInfo::TYPE_DRIVER:
				$label	 = 'qum_driver_label';
				break;
			case UserInfo::TYPE_CONSUMER:
				$label	 = 'qum_customer_label';
				break;
			case UserInfo::TYPE_AGENT:
				$label	 = 'qum_partner_label';
				break;
			default:
				break;
		}
		return $label;
	}

	/**
	 * 
	 * @param integer $entType
	 * @return string
	 */
	public static function getShowFieldByEntityType($entType = 0)
	{
		$label = 'NA';
		switch ($entType)
		{
			case UserInfo::TYPE_VENDOR:
				$label	 = 'qum_is_vendor';
				break;
			case UserInfo::TYPE_DRIVER:
				$label	 = 'qum_is_driver';
				break;
			case UserInfo::TYPE_CONSUMER:
				$label	 = 'qum_is_customer';
				break;
			case UserInfo::TYPE_AGENT:
				$label	 = 'qum_is_partner';
				break;
			default:
				break;
		}
		return $label;
	}

	/**
	 * This is for getting queue list.
	 * @return type
	 */
	public function getList()
	{
		$sql			 = "SELECT qum_id,qum_label FROM queue_master WHERE qum_active = 1 ORDER BY qum_id ASC";
		$QueueModels	 = DBUtil::command($sql)->queryAll($sql);
		$arrList		 = [];
		foreach ($QueueModels as $QueueModel)
		{
			$arrList[$QueueModel['qum_id']] = $QueueModel['qum_label'];
		}
		return $arrList;
	}

}
