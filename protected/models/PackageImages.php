<?php

/**
 * This is the model class for table "package_images".
 *
 * The followings are the available columns in table 'package_images':
 * @property integer $pci_id
 * @property integer $pci_pck_id
 * @property string $pci_images
 * @property string $pci_tourist_attractions
 * @property integer $pci_status
 * @property integer $pci_image_type
 * @property integer $pci_pcd_id
 * @property string $pci_create_date
 * @property string $pci_modified_date
 *
 * The followings are the available model relations:
 * @property Package $pciPck
 */
class PackageImages extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'package_images';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('pci_image_type,pci_images', 'required'),
			array('pci_pck_id, pci_status, pci_image_type, pci_pcd_id', 'numerical', 'integerOnly'=>true),
			array('pci_images, pci_tourist_attractions', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('pci_id, pci_pck_id, pci_images, pci_tourist_attractions, pci_status, pci_image_type, pci_pcd_id, pci_create_date, pci_modified_date', 'safe', 'on'=>'search'),
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
			'pciPck' => array(self::BELONGS_TO, 'Package', 'pci_pck_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'pci_id' => 'Pci',
			'pci_pck_id' => 'Pci Pck',
			'pci_images' => 'Images',
			'pci_tourist_attractions' => 'Pci Tourist Attractions',
			'pci_status' => 'Pci Status',
			'pci_image_type' => 'Image Type',
			'pci_pcd_id' => 'Pci Pcd',
			'pci_create_date' => 'Pci Create Date',
			'pci_modified_date' => 'Pci Modified Date',
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

		$criteria->compare('pci_id',$this->pci_id);
		$criteria->compare('pci_pck_id',$this->pci_pck_id);
		$criteria->compare('pci_images',$this->pci_images,true);
		$criteria->compare('pci_tourist_attractions',$this->pci_tourist_attractions,true);
		$criteria->compare('pci_status',$this->pci_status);
		$criteria->compare('pci_image_type',$this->pci_image_type);
		$criteria->compare('pci_pcd_id',$this->pci_pcd_id);
		$criteria->compare('pci_create_date',$this->pci_create_date,true);
		$criteria->compare('pci_modified_date',$this->pci_modified_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PackageImages the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
