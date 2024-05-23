<?php

/**
 * This is the model class for table "vendor_images".
 *
 * The followings are the available columns in table 'vendor_images':
 * @property integer $vni_id
 * @property integer $vni_vendor_id
 * @property integer $vni_category
 * @property string $vni_path
 * @property string $vni_created
 * @property integer $vni_active
 */
class VendorImages extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'vendor_images';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('vni_vendor_id', 'required'),
			array('vni_vendor_id, vni_category, vni_active', 'numerical', 'integerOnly' => true),
			array('vni_path', 'length', 'max' => 500),
			array('vni_created', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('vni_id, vni_vendor_id, vni_category, vni_path, vni_created, vni_active', 'safe', 'on' => 'search'),
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
			'agiVendor' => array(self::BELONGS_TO, 'Vendors', 'vni_vendor_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'vni_id'		 => 'Agi',
			'vni_vendor_id'	 => 'Agi Vendor',
			'vni_category'	 => '1 => photo, 2 => id_proof, 3 => license',
			'vni_path'		 => 'Agi Path',
			'vni_created'	 => 'Agi Created',
			'vni_active'	 => 'Agi Active',
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

		$criteria->compare('vni_id', $this->vni_id);
		$criteria->compare('vni_vendor_id', $this->vni_vendor_id);
		$criteria->compare('vni_category', $this->vni_category);
		$criteria->compare('vni_path', $this->vni_path, true);
		$criteria->compare('vni_created', $this->vni_created, true);
		$criteria->compare('vni_active', $this->vni_active);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return VendorImages the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function add($vendorId, $category, $path)
	{
		$model					 = new VendorImages();
		$model->vni_vendor_id	 = $vendorId;
		$model->vni_category	 = $category;
		$model->vni_path		 = $path;
		$success				 = false;
		if ($model->validate())
		{
			$success = $model->save();
		}
		return $success;
	}

}
