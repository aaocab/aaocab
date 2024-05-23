<?php

/**
 * This is the model class for table "cab_stats".
 *
 * The followings are the available columns in table 'cab_stats':
 * @property integer $cbs_id
 * @property integer $cbs_vhc_id
 * @property string $cbs_vhc_overall_rating
 * @property integer $cbs_vhc_total_trip
 * @property string $cbs_trust_score
 * @property integer $cbs_no_of_star
 * @property string $cbs_modified_date
 * @property integer $cbs_active
 */
class CabStats extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'cab_stats';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cbs_vhc_id, cbs_vhc_overall_rating, cbs_vhc_total_trip, cbs_trust_score, cbs_no_of_star', 'required'),
			array('cbs_vhc_id, cbs_vhc_total_trip, cbs_no_of_star, cbs_active', 'numerical', 'integerOnly' => true),
			array('cbs_vhc_overall_rating, cbs_trust_score', 'length', 'max' => 10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cbs_id, cbs_vhc_id, cbs_vhc_overall_rating, cbs_vhc_total_trip, cbs_trust_score, cbs_no_of_star, cbs_modified_date, cbs_active', 'safe', 'on' => 'search'),
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
			'cbs_id'				 => 'Id',
			'cbs_vhc_id'			 => 'Vhc',
			'cbs_vhc_overall_rating' => 'Vhc Overall Rating',
			'cbs_vhc_total_trip'	 => 'Vhc Total Trip',
			'cbs_trust_score'		 => 'Trust Score',
			'cbs_no_of_star'		 => 'No Of Star',
			'cbs_modified_date'		 => 'Modified Date',
			'cbs_active'			 => 'Active',
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

		$criteria->compare('cbs_id', $this->cbs_id);
		$criteria->compare('cbs_vhc_id', $this->cbs_vhc_id);
		$criteria->compare('cbs_vhc_overall_rating', $this->cbs_vhc_overall_rating, true);
		$criteria->compare('cbs_vhc_total_trip', $this->cbs_vhc_total_trip);
		$criteria->compare('cbs_trust_score', $this->cbs_trust_score, true);
		$criteria->compare('cbs_no_of_star', $this->cbs_no_of_star);
		$criteria->compare('cbs_modified_date', $this->cbs_modified_date, true);
		$criteria->compare('cbs_active', $this->cbs_active);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CabStats the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getByCabId($cabID)
	{
		$criteria	 = new CDbCriteria;
		$criteria->compare('cbs_vhc_id', $cabID);
		$model		 = $this->find($criteria);
		if ($model)
		{
			return $model;
		}
		else
		{
			return false;
		}
	}

	public function saveScore($val)
	{
		$cabID			 = $val['bcb_cab_id'];
		$numberOfTrip	 = $val['cnt'];
		$results		 = Ratings::CalculateRating($val);
		$star			 = $results["star"];
		$rating			 = $results["rating"];  // this is trust score value  
		$model			 = CabStats::model()->getByCabId($cabID);
		if ($model == null)
		{
			$model				 = new CabStats();
			$model->cbs_vhc_id	 = $cabID;
		}
		$model->cbs_active				 = 1;
		$model->cbs_vhc_overall_rating	 = round($rating / 2, 1);   // rating = trust score value /2
		$model->cbs_vhc_total_trip		 = $numberOfTrip;
		$model->cbs_trust_score			 = round($rating, 2);
		$model->cbs_no_of_star			 = $star;
		$model->cbs_modified_date		 = new CDbExpression('NOW()');
		if ($model->save())
		{
			return $model;
		}
		else
		{
			return false;
		}
	}

}
