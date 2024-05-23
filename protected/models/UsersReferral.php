<?php

/**
 * This is the model class for table "users_referral".
 *
 * The followings are the available columns in table 'users_referral':
 * @property string $usf_id
 * @property integer $usf_beneficiary_id
 * @property integer $usf_benefactor_id
 * @property integer $usf_referral_count
 * @property string $usf_freferral_date
 * @property string $usf_lreferral_date
 * @property string $usf_created
 * @property string $usf_modified
 * @property integer $usf_status
 */
class UsersReferral extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'users_referral';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('usf_beneficiary_id, usf_benefactor_id, usf_freferral_date', 'required'),
			array('usf_beneficiary_id, usf_benefactor_id, usf_referral_count, usf_status', 'numerical', 'integerOnly' => true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('usf_id, usf_beneficiary_id, usf_benefactor_id, usf_referral_count, usf_freferral_date, usf_lreferral_date, usf_created, usf_modified, usf_status', 'safe', 'on' => 'search'),
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
			'usf_id'			 => 'Usf',
			'usf_beneficiary_id' => 'User Referral Id',
			'usf_benefactor_id'	 => 'User Reffered Id',
			'usf_referral_count' => 'User Referral-User Reffered Combination count',
			'usf_freferral_date' => 'System Created date',
			'usf_lreferral_date' => 'System Created date',
			'usf_created'		 => 'System Created date',
			'usf_modified'		 => 'System Modified date',
			'usf_status'		 => '1=>active, 0=>Inactive',
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

		$criteria->compare('usf_id', $this->usf_id, true);
		$criteria->compare('usf_beneficiary_id', $this->usf_beneficiary_id);
		$criteria->compare('usf_benefactor_id', $this->usf_benefactor_id);
		$criteria->compare('usf_referral_count', $this->usf_referral_count);
		$criteria->compare('usf_freferral_date', $this->usf_freferral_date, true);
		$criteria->compare('usf_lreferral_date', $this->usf_lreferral_date, true);
		$criteria->compare('usf_created', $this->usf_created, true);
		$criteria->compare('usf_modified', $this->usf_modified, true);
		$criteria->compare('usf_status', $this->usf_status);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UsersReferral the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * This function is used to check if beneficiaryId and benefactorId combinations exist or not
	 * @param type $beneficiaryId
	 * @param type $benefactorId
	 * @return lastId 
	 */
	public static function isExist($beneficiaryId, $benefactorId)
	{
		$sql = "SELECT usf_id  FROM users_referral WHERE 1 AND usf_beneficiary_id=:beneficiaryId AND usf_benefactor_id=:benefactorId AND usf_status=1 ORDER BY usf_id DESC";
		return DBUtil::queryScalar($sql, DBUtil::SDB(), ['beneficiaryId' => $beneficiaryId, 'benefactorId' => $benefactorId]);
	}

	/**
	 * This function is used insert/update beneficiaryId and benefactorId combinations 
	 * @param type $beneficiaryId
	 * @param type $benefactorId
	 * @param type $id last id for  beneficiaryId and benefactorId combinations 
	 * @return return int 
	 * @throws Exception
	 */
	public static function updateStatus($beneficiaryId, $benefactorId)
	{
		$model = UsersReferral::model()->find('usf_beneficiary_id=:beneficiaryId AND usf_benefactor_id=:benefactorId AND usf_status=1 ', ['beneficiaryId' => $beneficiaryId, 'benefactorId' => $benefactorId]);
		if ($model == null)
		{
			$model						 = new UsersReferral();
			$model->usf_beneficiary_id	 = $beneficiaryId;
			$model->usf_benefactor_id	 = $benefactorId;
			$model->usf_freferral_date	 = DBUtil::getCurrentTime();
			$model->usf_created			 = DBUtil::getCurrentTime();
		}
		$model->usf_referral_count	 = $model->usf_referral_count + 1;
		$model->usf_lreferral_date	 = DBUtil::getCurrentTime();
		$model->usf_modified		 = DBUtil::getCurrentTime();
		if (!$model->save())
		{
			throw new Exception(CJSON::encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
		}
		return $model->usf_id;
	}

}
