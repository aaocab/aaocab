<?php

/**
 * This is the model class for table "vcs_cat_svc_type".
 *
 * The followings are the available columns in table 'vcs_cat_svc_type':
 * @property integer $vcs_id
 * @property integer $vcs_vct_id
 * @property integer $vcs_sct_id
 * @property integer $vcs_active
 */
class VcsCatSvcType extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'vcs_cat_svc_type';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('vcs_vct_id, vcs_sct_id', 'required'),
			array('vcs_vct_id, vcs_sct_id, vcs_active', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('vcs_id, vcs_vct_id, vcs_sct_id, vcs_active', 'safe', 'on'=>'search'),
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
			'vcs_id' => 'Vcs',
			'vcs_vct_id' => 'Vcs Vct',
			'vcs_sct_id' => 'Vcs Sct',
			'vcs_active' => 'Vcs Active',
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

		$criteria->compare('vcs_id',$this->vcs_id);
		$criteria->compare('vcs_vct_id',$this->vcs_vct_id);
		$criteria->compare('vcs_sct_id',$this->vcs_sct_id);
		$criteria->compare('vcs_active',$this->vcs_active);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return VcsCatSvcType the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
