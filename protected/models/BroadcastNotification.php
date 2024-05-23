<?php

/**
 * This is the model class for table "broadcast_notification".
 *
 * The followings are the available columns in table 'broadcast_notification':
 * @property integer $bcn_id
 * @property string $bcn_form_input
 * @property string $bcn_query
 * @property string $bcn_user_id
 * @property string $bcn_schedule_for
 * @property string $bcn_started_at
 * @property string $bcn_start_count
 * @property string $bcn_completed_at
 * @property integer $bcn_status
 * @property integer $bcn_active
 * @property string $bcn_created_at
 */
class BroadcastNotification extends CActiveRecord
{

	public $bcn_user_type, $bcn_region, $bcn_zon_name, $bcn_loggedIn_option, $bcn_last_loggedIn, $bcn_rating_option, $bcn_current_rating, $bcn_link, $bcn_title, $bcn_message, $bcn_chk_app, $bcn_date, $bcn_time, $bcn_customer_loggedIn_option, $bcn_customer_last_loggedIn, $bcn_user_id, $bcn_vendor;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'broadcast_notification';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('bcn_form_input, bcn_schedule_for, bcn_started_at,bcn_start_count, bcn_completed_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('bcn_id, bcn_form_input, bcn_query, bcn_schedule_for, bcn_started_at, bcn_start_count,  bcn_completed_at, bcn_status, bcn_active, bcn_created_at', 'safe', 'on' => 'search'),
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
			'bcn_id'			 => 'Bcn',
			'bcn_form_input'	 => 'Bcn Form Input',
			'bcn_query'			 => 'Bcn Query',
			'bcn_schedule_for'	 => 'Bcn Schedule For',
			'bcn_started_at'	 => 'Bcn Started At',
			'bcn_completed_at'	 => 'Bcn Completed At',
			'bcn_status'		 => '1=>\'pending\', 2=>\'in progress\', 3=> \'completed\'',
			'bcn_active'		 => '1=>\'active\', 2=>\'deactive\'',
			'bcn_created_at'	 => 'Bcn Created At',
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

		$criteria->compare('bcn_id', $this->bcn_id);
		$criteria->compare('bcn_form_input', $this->bcn_form_input, true);
		$criteria->compare('bcn_query', $this->bcn_query, true);
		$criteria->compare('bcn_schedule_for', $this->bcn_schedule_for, true);
		$criteria->compare('bcn_started_at', $this->bcn_started_at, true);
		$criteria->compare('bcn_completed_at', $this->bcn_completed_at, true);
		$criteria->compare('bcn_status', $this->bcn_status);
		$criteria->compare('bcn_active', $this->bcn_active);
		$criteria->compare('bcn_created_at', $this->bcn_created_at, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BroadcastNotification the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getRating()
	{
		return array(1	 => 'rating 1.0',
			2	 => 'rating 2.0',
			3	 => 'rating 3.0',
			4	 => 'rating 4.0',
			5	 => 'rating 5.0');
	}

	public function getLoginOptions()
	{
		return array(1	 => 'Have',
			2	 => 'Have not');
	}

	public function getLoggedInData()
	{
		return array(1	 => 'Today',
			7	 => 'Greater Than Last 7 days',
			30	 => 'Greater Than Last 30 days',
			60	 => 'Greater Than Last 60 days',
			90	 => 'Greater Than Last 90 days',
			180	 => 'Greater Than Last 180 days');
	}

	public function getCustomerLoggedInData()
	{
		return array(10	 => 'Greater Than Last 10 days',
			30	 => 'Greater Than Last 30 days',
			40	 => 'Greater Than Last 40 days');
	}

	public function getRatingOption()
	{
		return array(1	 => 'More than',
			2	 => 'Less than');
	}

	public function getList($type = '')
	{
		$pageSize		 = 50;
		$sql			 = "SELECT bcn_id,json_value(bcn_form_input,'$.bcn_title') AS bcn_title,bcn_user_type,bcn_schedule_for,bcn_status,bcn_active,bcn_created_at,adm.adm_user FROM  broadcast_notification LEFT JOIN admins adm ON adm.adm_id = broadcast_notification.bcn_user_id";
		$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc")->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => ['bcn_user_type', 'bcn_schedule_for', 'bcn_status', 'bcn_active', 'bcn_created_at'],
				'defaultOrder'	 => 'bcn_schedule_for  DESC'], 'pagination'	 => ['pageSize' => $pageSize],
		]);
		return $dataprovider;
	}

	public static function buildNotificationQuery($formData)
	{
		if ($formData['bcn_user_type'] == 1)
		{
			$queryData = self::buildVendorNotificationQuery($formData);
		}
		elseif ($formData['bcn_user_type'] == 2)
		{
			$queryData = self::buildDriverNotificationQuery($formData);
		}
		elseif ($formData['bcn_user_type'] == 3)
		{
			$queryData = self::buildCustomerNotificationQuery($formData);
		}
		return $queryData;
	}

	public static function buildCustomerNotificationQuery($formData)
	{
		$condition = "";

		if ($formData['bcn_customer_loggedIn_option'] != '' && $formData['bcn_customer_last_loggedIn'] != '')
		{
			if ($formData['bcn_customer_loggedIn_option'] == 1)
			{
				if ($formData['bcn_customer_last_loggedIn'] != 4)
				{
					$condition .= " AND apt.apt_last_login BETWEEN DATE_SUB(NOW(), INTERVAL " . $formData['bcn_customer_last_loggedIn'] . " DAY) AND NOW()";
				}
				else
				{
					$condition .= " AND apt.apt_last_login < NOW() ";
				}
			}
			else
			{
				$condition .= " AND apt.apt_last_login IS NULL";
			}
		}
		$sql = "SELECT usr.user_id, apt.apt_last_login FROM users usr
					INNER JOIN app_tokens apt ON apt.apt_user_id= usr.user_id
					WHERE usr.usr_active =1 AND apt.apt_user_type = 1 $condition";
		return $sql;
	}

	public static function buildDriverNotificationQuery($formData)
	{
		$condition = "";

		if ($formData['bcn_loggedIn_option'] != '' && $formData['bcn_last_loggedIn'] != '')
		{
			if ($formData['bcn_loggedIn_option'] == 1)
			{
				if ($formData['bcn_last_loggedIn'] != 4)
				{
					$condition .= " AND drs.drs_last_logged_in BETWEEN DATE_SUB(NOW(), INTERVAL " . $formData['bcn_last_loggedIn'] . " DAY) AND NOW()";
				}
				else
				{
					$condition .= " AND drs.drs_last_logged_in < NOW() ";
				}
			}
			else
			{
				$condition .= " AND drs.drs_last_logged_in IS NULL";
			}
		}
		if ($formData['bcn_rating_option'] != '' && $formData['bcn_current_rating'] != '')
		{
			if ($formData['bcn_rating_option'] == 1)
			{
				$condition .= " AND drs.drs_drv_overall_rating >= '" . $formData['bcn_current_rating'] . "'";
			}
			else
			{
				$condition .= " AND drs.drs_drv_overall_rating < '" . $formData['bcn_current_rating'] . "'";
			}
		}
		$sql = "SELECT drv.drv_id, drs.drs_last_logged_in, drs.drs_drv_overall_rating FROM drivers drv INNER JOIN driver_stats drs ON drs.drs_drv_id = drv.drv_id WHERE drv.drv_active=1 $condition";
		return $sql;
	}

	public static function buildVendorNotificationQuery($formData)
	{
		$condition = "";

		$arrZoneIds = [];
		if ($formData['bcn_region'] != '')
		{
			$regionIds	 = implode(",", $formData['bcn_region']);
			$zoneIds	 = Zones::getIdsByRegionId($regionIds);
			$arrZoneIds	 = explode(',', $zoneIds);
		}
		if ($formData['bcn_zon_name'] != '')
		{
			$arrZoneIds = array_merge($arrZoneIds, $formData['bcn_zon_name']);
		}
		if (count($arrZoneIds) > 0)
		{
			$zoneIds	 = implode(",", $arrZoneIds);
			$condition	 .= " AND vnp.vnp_home_zone IN ($zoneIds)";
		}
		else if ($formData['bcn_region'] != '' || $formData['bcn_zon_name'] != '')
		{
			$condition .= " AND vnp.vnp_home_zone IN (-1)"; // No Records found
		}

		if ($formData['bcn_loggedIn_option'] != '' && $formData['bcn_last_loggedIn'] != '')
		{
			if ($formData['bcn_loggedIn_option'] == 1)
			{
				if ($formData['bcn_last_loggedIn'] != 4)
				{
					$condition .= " AND vrs.vrs_last_logged_in BETWEEN DATE_SUB(NOW(), INTERVAL " . $formData['bcn_last_loggedIn'] . " DAY) AND NOW()";
				}
				else
				{
					$condition .= " AND vrs.vrs_last_logged_in < NOW() ";
				}
			}
			else
			{
				$condition .= " AND vrs.vrs_last_logged_in IS NULL";
			}
		}
		if ($formData['bcn_rating_option'] != '' && $formData['bcn_current_rating'] != '')
		{
			if ($formData['bcn_rating_option'] == 1)
			{
				$condition .= " AND vrs.vrs_vnd_overall_rating >= '" . $formData['bcn_current_rating'] . "'";
			}
			else
			{
				$condition .= " AND vrs.vrs_vnd_overall_rating < '" . $formData['bcn_current_rating'] . "'";
			}
		}
		if ($formData['bcn_vendor'] != '')
		{
			$vendorIds	 = implode(",", $formData['bcn_vendor']);
			$condition	 .= " AND vnd.vnd_id IN ($vendorIds)";
		}
		$sql = "SELECT vnd.vnd_id, vrs.vrs_last_logged_in,vnp.vnp_home_zone, vrs.vrs_vnd_overall_rating FROM vendors vnd
					INNER JOIN vendor_pref vnp ON vnp.vnp_vnd_id = vnd.vnd_id 
					INNER JOIN vendor_stats vrs ON vrs.vrs_vnd_id = vnd.vnd_id
					WHERE vnd.vnd_active= 1 $condition ";
		return $sql;
	}

	public static function updateJobData()
	{
		try
		{
			$sql		 = "SELECT * FROM broadcast_notification bcn 
					WHERE bcn.bcn_active=1 AND bcn.bcn_status =1 
					ORDER BY bcn.bcn_schedule_for ASC LIMIT 1";
			$sqlQuery	 = DBUtil::queryAll($sql);
			foreach ($sqlQuery as $val)
			{
				$model				 = BroadcastNotification::model()->findByPk($val['bcn_id']);
				$model->bcn_status	 = 2;
				if ($model->save())
				{
					$updateDataOnChildTable = BroadcastNotificationDetails::updateData($val);
				}
			}
			echo "Data inserted Successfully";
		}
		catch (Exception $ex)
		{
			$error = $ex->getMessage();
		}
	}

	public static function addCbrBroadcastNotification($vendorIds, $adminId)
	{
		$model					 = new BroadcastNotification();
		$data					 = array(
			"bcn_user_type"					 => "1",
			"bcn_loggedIn_option"			 => "",
			"bcn_last_loggedIn"				 => "180",
			"bcn_rating_option"				 => "",
			"bcn_current_rating"			 => "",
			"bcn_customer_loggedIn_option"	 => "1",
			"bcn_customer_last_loggedIn"	 => "",
			"bcn_link"						 => "",
			"bcn_title"						 => "Gozo has released payment.",
			"bcn_message"					 => "Your payment has been processed .It will be credited into your bank account within 1-2 business day",
			"bcn_chk_app"					 => "1",
			"bcn_date"						 => date('d/m/Y'),
			"bcn_time"						 => date('h:i A')
		);
		$model->bcn_form_input	 = CJSON::encode($data);
		$model->bcn_schedule_for = date('Y-m-d H:i:s');
		$model->bcn_user_type	 = 1;
		$model->bcn_user_id		 = $adminId;
		$model->bcn_query		 = "SELECT vnd.vnd_id
                                    FROM vendors vnd
                                    INNER JOIN vendor_pref vnp ON vnp.vnp_vnd_id = vnd.vnd_id 
                                    INNER JOIN vendor_stats vrs ON vrs.vrs_vnd_id = vnd.vnd_id
                                    WHERE vnd.vnd_active=1  AND vrs.vrs_last_logged_in BETWEEN DATE_SUB(NOW(), INTERVAL 180 DAY) AND NOW() AND vnd_id IN ($vendorIds)";
		if ($model->save())
		{
			$success = true;
			$msg	 = "Data Saved Successfully";
		}
	}

}
