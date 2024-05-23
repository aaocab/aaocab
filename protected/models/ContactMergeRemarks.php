<?php

/**
 * This is the model class for table "contact_merge_remarks".
 *
 * The followings are the available columns in table 'contact_merge_remarks':
 * @property string $cmr_id
 * @property string $cmr_ctt_id
 * @property string $cmr_ctt_mgr_id
 * @property string $cmr_remarks
 * @property string $cmr_created
 * @property string $cmr_modified
 */
class ContactMergeRemarks extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'contact_merge_remarks';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cmr_ctt_id, cmr_ctt_mgr_id, cmr_remarks', 'required'),
			array('cmr_ctt_id, cmr_ctt_mgr_id', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cmr_id, cmr_ctt_id, cmr_ctt_mgr_id, cmr_remarks, cmr_created, cmr_modified', 'safe', 'on'=>'search'),
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
			'cmr_id' => 'Cmr',
			'cmr_ctt_id' => 'Cmr Ctt',
			'cmr_ctt_mgr_id' => 'Cmr Ctt Mgr',
			'cmr_remarks' => 'Cmr Remarks',
			'cmr_created' => 'Cmr Created',
			'cmr_modified' => 'Cmr Modified',
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

		$criteria->compare('cmr_id',$this->cmr_id,true);
		$criteria->compare('cmr_ctt_id',$this->cmr_ctt_id,true);
		$criteria->compare('cmr_ctt_mgr_id',$this->cmr_ctt_mgr_id,true);
		$criteria->compare('cmr_remarks',$this->cmr_remarks,true);
		$criteria->compare('cmr_created',$this->cmr_created,true);
		$criteria->compare('cmr_modified',$this->cmr_modified,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ContactMergeRemarks the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * 
	 * @param type $primaryContactId
	 * @param type $duplicateContactId
	 * @throws Exception
	 */
	public static function setManualMerge($primaryContactId, $duplicateContactId, $remarks="Document data mismatched")
	{
		if(empty($primaryContactId) || empty($duplicateContactId))
		{
			throw new Exception("Invalid data", ReturnSet::ERROR_INVALID_DATA);
		}

		$remarkModel = new ContactMergeRemarks();

		$remarkModel->cmr_ctt_id = $primaryContactId;
		$remarkModel->cmr_ctt_mgr_id = $duplicateContactId;
		$remarkModel->cmr_remarks = $remarks;

		if(!$remarkModel->save())
		{
			throw new Exception(json_encode($remarkModel->getErrors()), ReturnSet::ERROR_VALIDATION);
		}
	}
}


