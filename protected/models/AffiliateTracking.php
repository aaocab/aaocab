<?php

/**
 * This is the model class for table "affiliate_tracking".
 *
 * The followings are the available columns in table 'affiliate_tracking':
 * @property integer $aft_id
 * @property integer $aft_ref_type
 * @property integer $aft_aff_id
 * @property integer $aft_bkg_id
 * @property integer $aft_count
 * @property string $aft_url
 * @property string $aft_created_at
 */
class AffiliateTracking extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'affiliate_tracking';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('aft_url, aft_ref_type, aft_aff_id', 'required'),
			array('aft_ref_type, aft_aff_id, aft_bkg_id, aft_count', 'numerical', 'integerOnly' => true),
			array('aft_url', 'length', 'max' => 500),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('aft_id, aft_ref_type, aft_aff_id, aft_bkg_id, aft_count, aft_url, aft_created_at', 'safe', 'on' => 'search'),
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
			'aft_id'		 => 'Aft',
			'aft_ref_type'	 => 'Aft Ref Type',
			'aft_aff_id'	 => 'Aft Aff',
			'aft_bkg_id'	 => 'Aft Bkg',
			'aft_url'		 => 'Aft Url',
			'aft_created_at' => 'Aft Created At',
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

		$criteria->compare('aft_id', $this->aft_id);
		$criteria->compare('aft_ref_type', $this->aft_ref_type);
		$criteria->compare('aft_aff_id', $this->aft_aff_id);
		$criteria->compare('aft_bkg_id', $this->aft_bkg_id);
		$criteria->compare('aft_url', $this->aft_url, true);
		$criteria->compare('aft_created_at', $this->aft_created_at, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AffiliateTracking the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function add($data)
	{
		$result = $this->find($data['aft_bkg_id'], $data['aft_aff_id'], $data['aft_ref_type']);
		if (!$result)
		{
			$model				 = new AffiliateTracking();
			$model->attributes	 = $data;
			$success			 = $model->save();
		}
		else
		{
			$model				 = AffiliateTracking::model()->findByPk($result['aft_id']);
			$model->aft_count	 = $model->aft_count + 1;
			$success			 = $model->save();
		}
		return $success;
	}

	public function find($bkg_id, $aff_id, $ref_type)
	{
		$sql	 = "select aft_id from affiliate_tracking where aft_ref_type = $ref_type and aft_aff_id = $aff_id and aft_bkg_id = '$bkg_id' and aft_bkg_id IS NOT NULL";
		$result	 = DBUtil::queryRow($sql);
		return $result;
	}

}
