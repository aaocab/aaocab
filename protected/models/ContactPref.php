<?php

/**
 * This is the model class for table "contact_pref".
 *
 * The followings are the available columns in table 'contact_pref':
 * @property integer $cpr_id
 * @property integer $cpr_ctt_id
 * @property integer $cpr_category
 */
class ContactPref extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'contact_pref';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cpr_category', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cpr_id, cpr_category', 'safe', 'on'=>'search'),
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
			'cpr_id' => 'Cpr',
			'cpr_category' => 'Cpr Category'
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

		$criteria->compare('cpr_id',$this->cpr_id);
		$criteria->compare('cpr_category',$this->cpr_category);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ContactPref the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function updateCategory($userId = 0, $totZeroCashCancelled=null, $totZeroCashCancelledMonth12=null)
	{
		$userCond = "";
		if ($userId > 0)
		{
			$userCond = " AND user_id = " . $userId;
		}

		$sql = "SELECT ctt_id, IFNULL(urs_total_trips,0) urs_total_trips, IFNULL(urs_trips_12months, 0) urs_trips_12months  
				FROM users 
				INNER JOIN user_stats ON urs_user_id = user_id 
				INNER JOIN contact_profile ON cr_is_consumer = user_id AND cr_status = 1 
				INNER JOIN contact ON ctt_id = cr_contact_id AND ctt_id = ctt_ref_code AND ctt_active = 1 
				WHERE usr_active = 1 {$userCond}";

		$result = DBUtil::queryRow($sql);
		try
		{
			$model = ContactPref::model()->find("cpr_ctt_id=:cId", ['cId' => $result['ctt_id']]);
			if (!$model)
			{
				$model				 = new ContactPref();
				$model->cpr_ctt_id	 = $result['ctt_id'];
			}
			
			$totalTrips = $result['urs_total_trips'];
			$totalTrips12Months = $result['urs_trips_12months'];
			
			$netTotalTrips = ($totalTrips - $totZeroCashCancelled);
			$netTotalTrips12Months = ($totalTrips12Months - $totZeroCashCancelledMonth12);
			
			Logger::writeToConsole("OLD cpr_category: ". $model->cpr_category);
			Logger::writeToConsole($userId . " - " . $model->cpr_ctt_id . " - " .$totalTrips . " - " .$totalTrips12Months . " - " .$totZeroCashCancelled . " - " .$totZeroCashCancelledMonth12 . " - " .$netTotalTrips . " - " .$netTotalTrips12Months);

			$model->cpr_category = 1;
			if ($netTotalTrips >= 30 || $netTotalTrips12Months >= 6)
			{
				$model->cpr_category = 4;
			}
			else if ($netTotalTrips >= 20 || $netTotalTrips12Months >= 4)
			{
				$model->cpr_category = 3;
			}
			else if ($netTotalTrips >= 10 || $netTotalTrips12Months >= 2)
			{
				$model->cpr_category = 2;
			}
			
			Logger::writeToConsole("New cpr_category: ". $model->cpr_category);

			$model->save();
		}
		catch (Exception $ex)
		{
			Logger::exception($ex);
			Logger::writeToConsole($ex->getMessage());
		}
	}

}
