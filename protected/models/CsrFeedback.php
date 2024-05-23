<?php

/**
 * This is the model class for table "csr_feedback".
 *
 * The followings are the available columns in table 'csr_feedback':
 * @property integer $crf_id
 * @property integer $crf_admin_id
 * @property integer $crf_bkg_id
 * @property integer $crf_bkg_status
 * @property integer $crf_customer_to_driver_rating
 * @property integer $crf_driver_to_cust_rating
 * @property integer $crf_cust_to_car_rating
 * @property integer $crf_csr_to_customer_rating
 * @property integer $crf_csr_to_driver_rating
 * @property string $crf_create_at
 * @property string $crf_updated_at
 * @property integer $crf_active
 */
class CsrFeedback extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'csr_feedback';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('crf_admin_id, crf_bkg_id, crf_bkg_status, crf_create_at, crf_updated_at', 'required'),
			array('crf_id, crf_admin_id, crf_bkg_id, crf_bkg_status, crf_customer_to_driver_rating, crf_driver_to_cust_rating, crf_cust_to_car_rating, crf_csr_to_customer_rating, crf_csr_to_driver_rating, crf_active', 'numerical', 'integerOnly' => true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('crf_id, crf_admin_id, crf_bkg_id, crf_bkg_status, crf_customer_to_driver_rating, crf_driver_to_cust_rating, crf_cust_to_car_rating, crf_csr_to_customer_rating, crf_csr_to_driver_rating, crf_create_at, crf_updated_at, crf_active,crf_cab_id,crf_driver_id', 'safe', 'on' => 'search'),
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
			'crf_id'						 => 'Crf',
			'crf_admin_id'					 => 'Admins Id',
			'crf_bkg_id'					 => 'Booking Id',
			'crf_bkg_status'				 => 'Booking status at the time of creations',
			'crf_customer_to_driver_rating'	 => '1=>Very upset, 2=>Upset, 3=>Ok, 4=>Happy,5=>Very Happy',
			'crf_driver_to_cust_rating'		 => '1=>Very upset, 2=>Upset, 3=>Ok, 4=>Happy,5=>Very Happy',
			'crf_cust_to_car_rating'		 => '1=>Very upset, 2=>Upset, 3=>Ok, 4=>Happy,5=>Very Happy',
			'crf_csr_to_customer_rating'	 => '1=>Very upset, 2=>Upset, 3=>Ok, 4=>Happy,5=>Very Happy',
			'crf_csr_to_driver_rating'		 => '1=>Very upset, 2=>Upset, 3=>Ok, 4=>Happy,5=>Very Happy',
			'crf_create_at'					 => 'create date time',
			'crf_updated_at'				 => 'update date time',
			'crf_active'					 => '0 => inactive, 1 => active',
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

		$criteria->compare('crf_id', $this->crf_id);
		$criteria->compare('crf_admin_id', $this->crf_admin_id);
		$criteria->compare('crf_bkg_id', $this->crf_bkg_id);
		$criteria->compare('crf_bkg_status', $this->crf_bkg_status);
		$criteria->compare('crf_customer_to_driver_rating', $this->crf_customer_to_driver_rating);
		$criteria->compare('crf_driver_to_cust_rating', $this->crf_driver_to_cust_rating);
		$criteria->compare('crf_cust_to_car_rating', $this->crf_cust_to_car_rating);
		$criteria->compare('crf_csr_to_customer_rating', $this->crf_csr_to_customer_rating);
		$criteria->compare('crf_csr_to_driver_rating', $this->crf_csr_to_driver_rating);
		$criteria->compare('crf_create_at', $this->crf_create_at, true);
		$criteria->compare('crf_updated_at', $this->crf_updated_at, true);
		$criteria->compare('crf_active', $this->crf_active);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CsrFeedback the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public static function getCsrFeedbackRating($bookingId)
	{
		$sql = "SELECT 
			   crf_bkg_id,
			   SUM(crf_customer_to_driver_rating) AS customer_to_driver_rating,
			   SUM(crf_driver_to_cust_rating) AS driver_to_cust_rating,
			   SUM(crf_csr_to_customer_rating) AS csr_to_customer_rating,
			   SUM(crf_cust_to_car_rating) AS cust_to_car_rating,
			   SUM(crf_csr_to_driver_rating) AS csr_to_driver_rating
			   FROM 
			   (
					SELECT
					crf_bkg_id,
					CEIL(SUM(crf_customer_to_driver_rating)/COUNT(crf_customer_to_driver_rating)) AS crf_customer_to_driver_rating,
					0 AS crf_driver_to_cust_rating,
					0 AS crf_csr_to_customer_rating,
					0 AS crf_cust_to_car_rating,
					0 AS crf_csr_to_driver_rating
					FROM csr_feedback
					WHERE 1
					AND crf_bkg_id=:bookingId
					AND crf_active= 1
					AND crf_customer_to_driver_rating IS NOT NULL                            

					UNION  

					SELECT 
					crf_bkg_id,
					0 AS crf_customer_to_driver_rating,
					CEIL(SUM(crf_driver_to_cust_rating)/COUNT(crf_driver_to_cust_rating)) AS crf_driver_to_cust_rating,
					0 AS crf_csr_to_customer_rating,
					0 AS crf_cust_to_car_rating,
					0 AS crf_csr_to_driver_rating
					FROM csr_feedback
					WHERE 1
					AND crf_bkg_id=:bookingId
					AND crf_active= 1
					AND crf_driver_to_cust_rating IS NOT NULL

					UNION                  

					SELECT
					crf_bkg_id,
					0 AS crf_customer_to_driver_rating,
					0 AS crf_driver_to_cust_rating,
					CEIL(SUM(crf_csr_to_customer_rating)/COUNT(crf_csr_to_customer_rating)) AS crf_csr_to_customer_rating,
					0 AS crf_cust_to_car_rating,
					0 AS crf_csr_to_driver_rating
					FROM csr_feedback
					WHERE 1
					AND crf_bkg_id=:bookingId
					AND crf_active= 1
					AND crf_csr_to_customer_rating IS NOT NULL

					UNION 

					SELECT 
					crf_bkg_id,
					0 AS crf_customer_to_driver_rating,
					0 AS crf_driver_to_cust_rating,
					0 AS crf_csr_to_customer_rating,
					CEIL(SUM(crf_cust_to_car_rating)/COUNT(crf_cust_to_car_rating)) AS crf_cust_to_car_rating,
					0 AS crf_csr_to_driver_rating
					FROM csr_feedback
					WHERE 1
					AND crf_bkg_id=:bookingId
					AND crf_active= 1
					AND crf_cust_to_car_rating IS NOT NULL

					UNION 

					SELECT
					crf_bkg_id,
					0 AS crf_customer_to_driver_rating,
					0 AS crf_driver_to_cust_rating,
					0 AS crf_csr_to_customer_rating,
					0  AS crf_cust_to_car_rating,
					CEIL(SUM(crf_csr_to_driver_rating)/COUNT(crf_csr_to_driver_rating)) AS crf_csr_to_driver_rating
					FROM csr_feedback
					WHERE 1
					AND crf_bkg_id=:bookingId
					AND crf_active= 1
					AND crf_csr_to_driver_rating IS NOT NULL
				) TEMP  WHERE 1 AND TEMP.crf_bkg_id IS NOT NULL  GROUP BY  crf_bkg_id;";
		return DBUtil::queryRow($sql, DBUtil::SDB(), ['bookingId' => $bookingId]);
	}

	public static function getFeedbackRating($type = 0)
	{
		$arrType = [
			0	 => "Don't know",
			1	 => 'Very Upset',
			2	 => 'Upset',
			3	 => 'Ok',
			4	 => 'Happy',
			5	 => 'Very Happy'
		];
		if ($type != null)
		{
			$type = $type >= 5 ? 5 : $type;
			return $arrType[$type];
		}
		else
		{
			return "Don't know";
		}
	}

	public static function getColorCodeForRating($type = 0)
	{
		$arrType = [
			0	 => "",
			1	 => 'style="background: #ff0000; color: #fff;"',
			2	 => 'style="color: #ff0000;"',
			3	 => 'style="color: #1077d5;"',
			4	 => 'style="background: #e2efda; color: #70ad47;"',
			5	 => 'style="background: #70ad47; color: #fff;"'
		];
		if ($type != null)
		{
			$type = $type >= 5 ? 5 : $type;
			return $arrType[$type];
		}
		else
		{
			return "";
		}
	}



}
