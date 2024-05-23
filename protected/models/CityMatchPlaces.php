<?php

/**
 * This is the model class for table "city_match_places".
 *
 * The followings are the available columns in table 'city_match_places':
 * @property integer $cmpl_id
 * @property integer $cmpl_city_id
 * @property string $cmpl_dest_ids

 * @property integer $cmpl_dest_id
 * @property integer $cmpl_active
 * @property string $cmpl_created_at
 *
 * The followings are the available model relations:
 * @property Cities $cmplCity
 * @property Cities $cmplDest
 */
class CityMatchPlaces extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'city_match_places';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cmpl_id, cmpl_city_id, cmpl_dest_ids, cmpl_dest_id, cmpl_active, cmpl_created_at', 'safe', 'on' => 'search'),
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
			'cmplCity'	 => array(self::BELONGS_TO, 'Cities', 'cmpl_city_id'),
			'cmplDest'	 => array(self::BELONGS_TO, 'Cities', 'cmpl_dest_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'cmpl_id'			 => 'Cmpl',
			'cmpl_city_id'		 => 'Cmpl City',
			'cmpl_dest_id'		 => 'Cmpl Dest',
			'cmpl_active'		 => 'Cmpl Active',
			'cmpl_created_at'	 => 'Cmpl Created At',
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

		$criteria->compare('cmpl_id', $this->cmpl_id);
		$criteria->compare('cmpl_city_id', $this->cmpl_city_id);
		$criteria->compare('cmpl_dest_ids', $this->cmpl_dest_ids, true);
		$criteria->compare('cmpl_dest_id', $this->cmpl_dest_id);
		$criteria->compare('cmpl_active', $this->cmpl_active);
		$criteria->compare('cmpl_created_at', $this->cmpl_created_at, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CityMatchPlaces the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function findById($cityId)
	{
		return self::model()->findByAttributes(array('cmpl_city_id' => $cityId));
	}

	public function getListNearestPlaces($city_id)
	{
		$sql = "SELECT rut_from_city_id,GROUP_CONCAT(rut_to_city_id SEPARATOR ',' ) as rut_to_city_ids
                FROM
                (
                    SELECT DISTINCT route.rut_from_city_id, fromcity.cty_name as frmCity, route.rut_to_city_id, tocity.cty_name as toCity , route.rut_estm_distance, IF(bookCnt>0,bookCnt,0) as bookCnt
                    FROM `route`
                    INNER JOIN `cities` as fromcity ON fromcity.cty_id=route.rut_from_city_id AND fromcity.cty_active=1 AND fromcity.cty_is_airport<>1
                    LEFT JOIN `cities` as tocity ON tocity.cty_id=route.rut_to_city_id AND tocity.cty_active=1 AND tocity.cty_is_airport<>1
                    LEFT JOIN
                    (
                        SELECT booking.bkg_from_city_id, COUNT(1) as bookCnt
                        FROM `booking_cab`
                        INNER JOIN `booking` ON booking.bkg_bcb_id=booking_cab.bcb_id AND booking_cab.bcb_active=1 AND booking.bkg_active=1
                        WHERE booking.bkg_status IN (6,7)
                        GROUP BY booking.bkg_from_city_id
                    ) bkg ON bkg.bkg_from_city_id=route.rut_to_city_id
                    WHERE route.rut_estm_distance BETWEEN 20 AND 100
                    AND route.rut_from_city_id<>route.rut_to_city_id
                    AND route.rut_from_city_id IN ($city_id)
                    AND tocity.cty_name IS NOT NULL
                    ORDER BY `route`.`rut_from_city_id` ASC, bookCnt DESC LIMIT 0,10
                ) a";
		return DBUtil::queryRow($sql);
	}

	public function insertUpdateNearestCityPlace($city_id)
	{
		$success	 = false;
		$row		 = $this->getListNearestPlaces($city_id);
		$transaction = Yii::app()->db->beginTransaction();
		try
		{
			if ($row['rut_from_city_id'] > 0 && $row['rut_to_city_ids'] != '')
			{
				$model = CityMatchPlaces::model()->findById($row['rut_from_city_id']);
				if ($model != '')
				{
					$model->cmpl_dest_ids	 = $row['rut_to_city_ids'];
					$transStatus			 = "Update";
				}
				else
				{
					$model					 = new CityMatchPlaces();
					$model->cmpl_city_id	 = $row['rut_from_city_id'];
					$model->cmpl_dest_ids	 = $row['rut_to_city_ids'];
					$model->cmpl_active		 = 1;
					$transStatus			 = "Insert";
				}
				if ($model->save())
				{
					$success = true;
				}
				else
				{
					$errors = "data not yet saved.\n\t\t" . json_encode($model->getErrors());
					throw new Exception($errors);
					Logger::create($errors, CLogger::LEVEL_ERROR);
				}
				if ($success == true)
				{
					$transaction->commit();
					$info = $transStatus . " - " . $row['rut_from_city_id'] . " - " . $row['rut_to_city_ids'];
					Logger::create($info, CLogger::LEVEL_INFO);
				}
			}
			else
			{
				$errors = "data not yet found.\n\t\t" . ($cityRow['rut_from_city_id'] . " - " . $cityRow['rut_to_city_ids']);
				throw new Exception($errors);
				Logger::create($errors, CLogger::LEVEL_ERROR);
			}
		}
		catch (Exception $e)
		{
			$transaction->rollback();
		}
		return $success;
	}

	public function updateNearestPlaces($city_id, $dest_ids)
	{

		$sql = "INSERT INTO `city_match_places`(`cmpl_city_id`, `cmpl_dest_ids`, `cmpl_active`)
                VALUES  ($city_id,'$dest_ids',1)
                ON DUPLICATE KEY UPDATE city_match_places.cmpl_city_id=$city_id";
	}

}
