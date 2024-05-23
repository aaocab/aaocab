<?php

/**
 * This is the model class for table "dco_interested_tracking".
 *
 * The followings are the available columns in table 'dco_interested_tracking':
 * @property integer $dit_id
 * @property integer $dit_vnd_id
 * @property string $dit_created
 * @property string $dit_download_attempted_on
 * @property integer $dit_active
 */
class DcoInterestedTracking extends CActiveRecord
{

	public $create_date1;
	public $create_date2;


	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'dco_interested_tracking';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('dit_vnd_id', 'required'),
			array('dit_vnd_id, dit_active', 'numerical', 'integerOnly' => true),
			array('dit_download_attempted_on', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('dit_id, dit_vnd_id, dit_created, dit_download_attempted_on, dit_active', 'safe', 'on' => 'search'),
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
			'dit_id'					 => 'Dit',
			'dit_vnd_id'				 => 'Vnd',
			'dit_created'				 => 'Created',
			'dit_download_attempted_on'	 => 'Download Attempted On',
			'dit_active'				 => 'Active',
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

		$criteria->compare('dit_id', $this->dit_id);
		$criteria->compare('dit_vnd_id', $this->dit_vnd_id);
		$criteria->compare('dit_created', $this->dit_created, true);
		$criteria->compare('dit_download_attempted_on', $this->dit_download_attempted_on, true);
		$criteria->compare('dit_active', $this->dit_active);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DcoInterestedTracking the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * 
	 * @param type $vndId
	 * @return \DcoInterestedTracking
	 */
	public static function checkExisting($vndId)
	{

		$criteria = new CDbCriteria();
		$criteria->compare('dit_vnd_id', $vndId);

		$model = DcoInterestedTracking::model()->find($criteria);
		return $model;
	}

	/**
	 * 
	 * @param int $vndId
	 * @return \DcoInterestedTracking|boolean
	 */
	public static function add($vndId)
	{
		$model = DcoInterestedTracking::checkExisting($vndId);
		if ($model)
		{
			return $model;
		}

		$ditModel				 = new DcoInterestedTracking();
		$ditModel->dit_vnd_id	 = $vndId;
		if ($ditModel->save())
		{
			return $ditModel;
		}
		return false;
	}

	/**
	 * 
	 * @param int $vndId
	 * @return \DcoInterestedTracking|boolean
	 */
	public static function updateDownload($vndId)
	{
		$model = DcoInterestedTracking::checkExisting($vndId);
		if (!$model)
		{
			return false;
		}
		$model->dit_download_attempted_on = new CDbExpression('NOW()');
		return $model->save();
	}

	public static function getCountByDate($fromDate, $toDate, $type = DBUtil::ReturnType_Provider)
	{
		$whereSql		 = $whereApps		 = $where			 = $whereDownload	 = "";
		$params			 = array();
		if (!empty($fromDate) && !empty($toDate))
		{
			$params['fromDate']	 = $fromDate;
			$params['toDate']	 = $toDate;
			$where				 = " AND cle_dt BETWEEN :fromDate AND :toDate ";
			$whereApps			 = " AND (apt.apt_date BETWEEN :fromDate AND :toDate ) ";
			$whereSql			 = " AND (whl_sent_date BETWEEN :fromDate AND :toDate AND whl_wht_id  IN (2,3,4,5,6,7) ) ";
			$whereDownload		 = " AND (dit_created BETWEEN :fromDate AND :toDate ) ";
		}
		$sql = "SELECT cle_dt date, sentCount, deliveredCount, readCount, clickedCount, downloadCount, loginCount 
			FROM calendar_event 
			LEFT JOIN( 
				SELECT sentDate, SUM(sentCount) sentCount, 
					SUM(deliveredCount) deliveredCount,
					SUM(readCount) readCount 
					FROM ( 
					SELECT DATE_FORMAT(whl_sent_date, '%Y-%m-%d') sentDate, 
						(IF(whl_sent_date IS NOT NULL AND whl_status IN (1,3,2), 1, NULL)) sentCount, 
						(IF(whl_delivered_date IS NOT NULL AND whl_status IN (2), 1, NULL)) deliveredCount,  
						(IF(whl_read_date IS NOT NULL AND whl_status IN (2), 1, NULL)) readCount 
						FROM `whatsapp_log` 
							WHERE whl_sent_date IS NOT NULL 
							$whereSql
							AND whl_status IN (1,2,3)
							GROUP BY whl_phone_number ) a 
						GROUP BY sentDate ) a 
					ON cle_dt = a.sentDate 
			LEFT JOIN( 
				SELECT DATE_FORMAT(dit_created, '%Y-%m-%d') clickedDate, 
					COUNT(dit_id) clickedCount, 
					SUM(IF(dit_download_attempted_on IS NOT NULL, 1, NULL)) downloadCount 
					FROM dco_interested_tracking 
						WHERE dit_active = 1 
						$whereDownload
						GROUP BY clickedDate ) b 
					ON cle_dt = b.clickedDate 
			LEFT JOIN( 
				SELECT DATE_FORMAT(apt.apt_date, '%Y-%m-%d') loginDate, 
					COUNT(DISTINCT apt.apt_entity_id) loginCount 
					FROM dco_interested_tracking 
					INNER JOIN app_tokens apt ON dit_vnd_id = apt.apt_entity_id 
						AND DATE(dit_download_attempted_on) = DATE(apt.apt_date) 
					WHERE apt.apt_platform =7 
						AND apt.apt_user_type=2 
						$whereApps 
					GROUP BY loginDate ) c 
				ON cle_dt = c.loginDate 
				WHERE 1 AND cle_active = 1 
					$where
					AND 
					(
						sentCount IS NOT NULL 
						OR deliveredCount IS NOT NULL 
						OR clickedCount IS NOT NULL 
						OR downloadCount IS NOT NULL 
						OR loginCount IS NOT NULL
					) ";
		if ($type == DBUtil::ReturnType_Provider)
		{
			$countSql		 = "SELECT  COUNT(*) FROM ($sql) abc";
			$count			 = DBUtil::queryScalar($countSql, DBUtil::SDB(), $params);
			$dataprovider	 = new CSqlDataProvider($sql, [
				"totalItemCount" => $count,
				'db'			 => DBUtil::SDB(),
				'params'		 => $params,
				'sort'			 => ['attributes' => ['date', 'sentCount', 'deliveredCount', 'readCount', 'clickedCount', 'downloadCount', 'loginCount'], 'defaultOrder' => 'date DESC'],
				"pagination"	 =>
				[
					"pageSize" => 50
				],
			]);
			return $dataprovider;
		}
		else
		{
			return DBUtil::query($sql, DBUtil::SDB(), $params);
		}
	}

}
