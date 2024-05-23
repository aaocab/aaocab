<?php

/**
 * This is the model class for table "broadcast_notification_details".
 *
 * The followings are the available columns in table 'broadcast_notification_details':
 * @property integer $bnd_id
 * @property integer $bnd_bnc_id
 * @property integer $bnd_user_type
 * @property integer $bnd_user_id
 * @property integer $bnd_status
 * @property string $bnd_create_date
 */
class BroadcastNotificationDetails extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'broadcast_notification_details';
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
			array('bnd_id, bnd_bnc_id, bnd_user_type, bnd_user_id, bnd_status, bnd_create_date', 'safe', 'on' => 'search'),
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
			'bnd_id'			 => 'Bnd',
			'bnd_bnc_id'		 => 'Bnd Bnc',
			'bnd_user_type'		 => '1=>\'Vendor\',2=>\'driver\', 3=>\'consumer\', 4=>\'admin ops\'',
			'bnd_user_id'		 => 'Bnd User',
			'bnd_status'		 => '1=>\'pending\', 2=> \'inprogress\',3=>\'completed\'',
			'bnd_create_date'	 => 'Bnd Create Date',
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

		$criteria->compare('bnd_id', $this->bnd_id);
		$criteria->compare('bnd_bnc_id', $this->bnd_bnc_id);
		$criteria->compare('bnd_user_type', $this->bnd_user_type);
		$criteria->compare('bnd_user_id', $this->bnd_user_id);
		$criteria->compare('bnd_status', $this->bnd_status);
		$criteria->compare('bnd_create_date', $this->bnd_create_date, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BroadcastNotificationDetails the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public static function updateData($data)
	{
		try
		{
			$bcnId		 = $data['bcn_id'];
			$userType	 = $data['bcn_user_type'];
			$queryData	 = $data['bcn_query'];

			$sql	 = "$queryData";
			$rows	 = DBUtil::query($sql);
			foreach ($rows as $row)
			{
				if ($userType == 1)
				{
					$userId = $row['vnd_id'];
				}
				elseif ($userType == 2)
				{
					$userId = $row['drv_id'];
				}
				else
				{
					$userId = $row['user_id'];
				}
				$insertSql	 = "INSERT INTO `broadcast_notification_details` (`bnd_bnc_id`, `bnd_user_type`, `bnd_user_id`, `bnd_status`) 
								VALUES ('$bcnId', '$userType' , '$userId', '1')";
				$result		 = DBUtil::command($insertSql)->execute();
			}

			return $result;
		}
		catch (Exception $ex)
		{
			$error = $ex->getMessage();
		}
	}

	public static function runBroadcastNotification()
	{
		for($x = 0; $x <= 10; $x++)
		{
			$batchSize		 = 100;
			$arrSuccessIds	 = array();
			$arrFailedIds	 = array();

			$sqlBcn	 = "SELECT bcn.bcn_id,bcn.bcn_user_type,bcn.bcn_schedule_for, bcn.bcn_form_input, bcn.bcn_started_at FROM broadcast_notification bcn WHERE bcn.bcn_status = 2 AND bcn.bcn_active=1 AND bcn.bcn_schedule_for <= NOW() ORDER BY bcn.bcn_schedule_for ASC LIMIT 1";
			$result	 = DBUtil::queryRow($sqlBcn);
			if ($result)
			{
				$bcnId				 = $result['bcn_id'];
				$bcnData			 = CJSON::decode($result['bcn_form_input']);
				$message			 = $bcnData['bcn_message'];
				$title				 = $bcnData['bcn_title'];
				$notificationTitle	 = ($bcnData['bcn_title'] != '') ? $bcnData['bcn_title'] : 'Important Notification';

				$sqlBnd			 = "SELECT bnd.bnd_id,bnd.bnd_bnc_id,bnd.bnd_user_id FROM broadcast_notification_details bnd WHERE bnd.bnd_status = 1 AND bnd.bnd_bnc_id = $bcnId LIMIT $batchSize";
				$sqlQuery		 = DBUtil::query($sqlBnd);
				$currentDateTime = date('Y-m-d H:i:s');
				if (count($sqlQuery) <= 0)
				{
					$model					 = BroadcastNotification::model()->findByPk($result['bcn_id']);
					$model->bcn_completed_at = $currentDateTime;
					$model->bcn_status		 = 3;
					$model->save();
					echo "Master table completed status update";
				}
				else
				{
					$model = BroadcastNotification::model()->findByPk($result['bcn_id']);
					if ($model->bcn_start_count == 0)
					{
						$model->bcn_start_count	 = 1;
						$model->bcn_started_at	 = $currentDateTime;
					}
					$model->save();
					foreach ($sqlQuery as $val)
					{
						Logger::writeToConsole('bnd_id == ' . $val['bnd_id']);
	/*					echo "\r\nbcn_user_type == " . $bcnData['bcn_user_type'];
						echo "\r\nbnd_user_id == " . $val['bnd_user_id'];
						echo "\r\nbcn_message == " . $bcnData['bcn_message'];
						echo "\r\nbcn_last_loggedIn == " . $bcnData['bcn_last_loggedIn'];
	*/
						if ($bcnData['bcn_user_type'] == 1)
						{
							$payLoadData = ['EventCode' => Booking::CODE_VENDOR_BROADCAST];
							$success	 = AppTokens::model()->notifyVendor($val['bnd_user_id'], $payLoadData, $bcnData['bcn_message'], $notificationTitle, false, $bcnData['bcn_last_loggedIn']);
						}
						elseif ($bcnData['bcn_user_type'] == 2)
						{
							$payLoadData = ['EventCode' => Booking::CODE_DRIVER_BROADCAST];
							$success	 = AppTokens::model()->notifyDriver($val['bnd_user_id'], $payLoadData, "", $bcnData['bcn_message'], "", $notificationTitle, 0, $bcnData['bcn_last_loggedIn']);
						}
						elseif ($bcnData['bcn_user_type'] == 3)
						{
							$payLoadData = ['EventCode' => Booking::CODE_CONSUMER_BROADCAST];
							$success	 = AppTokens::model()->notifyConsumer($val['bnd_user_id'], $payLoadData, "", $message, $notificationTitle);
						}

						if ($success)
						{
							$arrSuccessIds[] = $val['bnd_id'];
						}
						else
						{
							$arrFailedIds[] = $val['bnd_id'];
						}
					}

					Logger::writeToConsole('arrSuccessIds == ' . count($arrSuccessIds));
					Logger::writeToConsole('arrFailedIds == ' . count($arrFailedIds));

					if (count($arrSuccessIds) > 0 || count($arrFailedIds) > 0)
					{
						Logger::writeToConsole('updateNotificationStatus');
						$statusUpdate = self::updateNotificationStatus($arrSuccessIds, $arrFailedIds);
					}

					Logger::writeToConsole('Run Successfully');
				}
			}
			else
			{
				Logger::writeToConsole('Something went wrong/ no data found');
			}
		}
	}

	public static function updateNotificationStatus($arrSuccessIds, $arrFailedIds)
	{
		if (count($arrSuccessIds) > 0)
		{
			$successIds	 = implode(',', $arrSuccessIds);
			$sql		 = "UPDATE broadcast_notification_details SET bnd_status = 3, bnd_sent_date=NOW() WHERE bnd_status = 1 AND bnd_id IN ($successIds) ";
			$result		 = DBUtil::command($sql)->execute();
		}
		if (count($arrFailedIds) > 0)
		{
			$failedIds	 = implode(',', $arrFailedIds);
			$sql		 = "UPDATE broadcast_notification_details SET bnd_status = 2, bnd_sent_date=NOW() WHERE bnd_status = 1 AND bnd_id IN ($failedIds) ";
			$result		 = DBUtil::command($sql)->execute();
		}
	}

}
