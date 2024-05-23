<?php

/**
 * This is the model class for table "preferred_rate".
 *
 * The followings are the available columns in table 'preferred_rate':
 * @property integer $prid
 * @property integer $user_type
 * @property integer $entity_id
 * @property integer $rate_type
 * @property integer $from_id
 * @property integer $to_id
 * @property integer $cab_type
 * @property integer $booking_type
 * @property integer $service_tier
 * @property double $vendor_amount
 * @property string $valid_until
 * @property string $created_date
 */
class PreferredRate extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'preferred_rate';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_type, entity_id, rate_type, from_location_id, to_location_id, cab_type, booking_type, service_tier, vendor_amount, valid_until, created_date', 'required'),
			array('user_type, entity_id, rate_type, from_location_id, to_location_id, cab_type, booking_type, service_tier', 'numerical', 'integerOnly' => true),
			array('vendor_amount', 'numerical'),
				// The following rule is used by search().
				// @todo Please remove those attributes that should not be searched.
				//array('prid, user_type, entity_id, rate_type, from_id, to_id, cab_type, booking_type, service_tier, vendor_amount, valid_until, created_date', 'safe', 'on'=>'search'),
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
			'prid'				 => 'Prid',
			'user_type'			 => 'User Type',
			'entity_id'			 => 'Entity',
			'rate_type'			 => '1=>city_city,2=>zone_zone',
			'from_location_id'	 => 'From',
			'to_location_id'	 => 'To',
			'cab_type'			 => 'Cab Type',
			'booking_type'		 => '1=>oneway,2=>roundtrip',
			'service_tier'		 => 'Service Tier',
			'vendor_amount'		 => 'Vendor Amount',
			'valid_until'		 => 'Valid Until',
			'created_date'		 => 'Created Date',
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

		$criteria->compare('prid', $this->prid);
		$criteria->compare('user_type', $this->user_type);
		$criteria->compare('entity_id', $this->entity_id);
		$criteria->compare('rate_type', $this->rate_type);
		$criteria->compare('from_location_id', $this->from_id);
		$criteria->compare('to_location_id', $this->to_id);
		$criteria->compare('cab_type', $this->cab_type);
		$criteria->compare('booking_type', $this->booking_type);
		$criteria->compare('service_tier', $this->service_tier);
		$criteria->compare('vendor_amount', $this->vendor_amount);
		$criteria->compare('valid_until', $this->valid_until, true);
		$criteria->compare('created_date', $this->created_date, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return preferredRate the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getDetails($vndId, $rateType, $text)
	{

		$searching = "";

		if ($rateType == 1)
		{
			if ($text != "")
			{
				$searching = "HAVING
						(
							c1.cty_name LIKE ('" . $text . "%')
							OR c2.cty_name LIKE ('" . $text . "%')
						)";
			}
			$sql = "SELECT prid,user_type,rate_type,vendor_amount,service_tier,from_location_id,to_location_id,valid_until,cab_type,booking_type,c1.cty_name as from_location,c2.cty_name as to_location,
                    (SELECT CONCAT(vct_label, ' (', scc_label , ')') as label
					FROM svc_class_vhc_cat
					INNER JOIN service_class ON scc_id = scv_scc_id
					INNER JOIN vehicle_category ON vct_id = scv_vct_id
					WHERE scv_active = 1 
						AND scc_active = 1 
					AND vct_active = 1
					AND scv_id =cab_type ) AS vehicle_lavel
					FROM   preferred_rate
                    INNER JOIN cities c1 ON c1.cty_id = from_location_id
                    INNER JOIN cities c2 ON c2.cty_id = to_location_id
                    INNER JOIN vehicle_category ON vehicle_category.vct_id =cab_type
					WHERE rate_type=" . $rateType . "
                    AND entity_id =" . $vndId . " GROUP BY prid " . $searching . "";
		}
		else
		{
			if ($text != "")
			{
				$searching = "HAVING
						(
							z1.zon_name LIKE ('" . $text . "%')
							OR z2.zon_name LIKE ('" . $text . "%')
						)";
			}
			$sql = "SELECT prid,user_type,rate_type,vendor_amount,service_tier,from_location_id,to_location_id,valid_until,cab_type,booking_type,z1.zon_name as from_location,z2.zon_name as to_location,
					(SELECT CONCAT(vct_label, ' (', scc_label , ')') as label
					FROM svc_class_vhc_cat
					INNER JOIN service_class ON scc_id = scv_scc_id
					INNER JOIN vehicle_category ON vct_id = scv_vct_id
					WHERE scv_active = 1 
						AND scc_active = 1 
					AND vct_active = 1
					AND scv_id =cab_type ) AS vehicle_lavel
					FROM   preferred_rate
                    INNER JOIN zones z1 ON z1.zon_id = from_location_id
                    INNER JOIN zones z2 ON z2.zon_id = to_location_id
                    INNER JOIN vehicle_category ON vehicle_category.vct_id =cab_type
					WHERE rate_type=" . $rateType . "
                    AND entity_id =" . $vndId . " GROUP BY prid " . $searching . "";
		}


		$sqlData = DBUtil::queryAll($sql);
		return $sqlData;
	}

}
