<?php

/**
 * This is the model class for table "unverified_followup".
 *
 * The followings are the available columns in table 'unverified_followup':
 * @property integer $unv_id
 * @property integer $unv_group
 * @property integer $unv_bkg_id
 * @property integer $unv_bkg_tentative_booking
 * @property string $unv_date
 * @property float $unv_amount
 * @property string $unv_comment
 * @property string $unv_cmt
 * @property string $unv_tellus
 * @property integer $unv_type
 * @property integer $unv_status
 * @property string $unv_create_date
 *
 * The followings are the available model relations:
 * @property Booking $unvBkg
 */
class UnverifiedFollowup extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'unverified_followup';
	}

	public $bkg_tentative_booking, $unv_followup;

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('unv_type', 'validateInfo', "on" => "insertUmv"),
			array('unv_bkg_id, unv_bkg_tentative_booking, unv_status', 'numerical', 'integerOnly' => true),
			array('unv_comment, unv_cmt', 'length', 'max' => 4000),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('unv_id, unv_group, unv_bkg_id, unv_bkg_tentative_booking, unv_comment, unv_followup, unv_cmt, unv_date, unv_amount, unv_tellus , unv_type , unv_status, unv_create_date, bkg_tentative_booking', 'safe', 'on' => 'search'),
		);
	}

	public function validateInfo($attribute, $params)
	{
		$success = true;
		if (isset($this->unv_type) && $this->unv_type > 0)
		{
			switch ($this->unv_type)
			{
				case 1:
					if ($this->unv_amount == '' && $this->unv_cmt == '')
					{
						$this->addError('unv_cmt', 'Please enter your input in the space given.');
						$success = false;
					}
					break;
				case 2:
					if ($this->unv_date == '' || $this->unv_date == '1970-01-01' || $this->unv_date == NULL)
					{
						$this->addError('unv_date', 'Please enter your input in the space given.');
						$success = false;
					}
					break;
				case 3:
					if ($this->unv_tellus == '')
					{
						$this->addError('unv_tellus', 'Please enter your input in the space given.');
						$success = false;
					}
					break;
				case 4:
					if ($this->unv_followup == '' || $this->unv_followup == NULL)
					{
						$this->addError('unv_followup', 'Please enter your input in the space given.');
						$success = false;
					}
					break;
			}
		}
		return $success;
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'unvBkg' => array(self::BELONGS_TO, 'Booking', 'unv_bkg_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'unv_id'					 => 'Unv',
			'unv_bkg_id'				 => 'Unv Bkg',
			'unv_bkg_tentative_booking'	 => 'Unv Bkg Tentative Booking',
			'unv_comment'				 => 'Unv Comment',
			'unv_cmt'					 => 'Unv Comment2',
			'unv_status'				 => 'Unv Status',
			'unv_create_date'			 => 'Unv Create Date',
			'unv_amount'				 => 'Amount',
			'unv_comment2'				 => 'Reason',
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

		$criteria->compare('unv_id', $this->unv_id);
		$criteria->compare('unv_bkg_id', $this->unv_bkg_id);
		$criteria->compare('unv_bkg_tentative_booking', $this->unv_bkg_tentative_booking);
		$criteria->compare('unv_comment', $this->unv_comment, true);
		$criteria->compare('unv_cmt', $this->unv_cmt, true);
		$criteria->compare('unv_status', $this->unv_status);
		$criteria->compare('unv_create_date', $this->unv_create_date, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	public function getLeadURL($bkgId, $type = 'e')
	{

		$model	 = BookingTemp::model()->findByPk($bkgId);
		$hash	 = Yii::app()->shortHash->hash($model->bkg_id);
		if ($type == 'e')
		{
			$url = Yii::app()->params['fullBaseURL'] . '/u/' . $bkgId . '/' . $hash;
		}
		else if ($type == 'p')
		{
			$url = Yii::app()->params['fullBaseURL'] . '/u/' . $bkgId . '/' . $hash;
		}
		return $url;
	}

	public static function getURL($bkgId, $type = 'e')
	{
		$model	 = Booking::model()->findByPk($bkgId);
		$email	 = $model->bkgUserInfo->bkg_user_email;
		$hash	 = Yii::app()->shortHash->hash($model->bkg_id);
		if ($type == 'e')
		{
			$eHashCode	 = Yii::app()->shortHash->hash($model->bkgUserInfo->bkg_verifycode_email);
			$url		 = Yii::app()->params['fullBaseURL'] . '/u/' . $bkgId . '/' . $hash . '/e/' . $eHashCode;
		}
		else if ($type == 'p')
		{
			$phone	 = Yii::app()->shortHash->hash($model->bkgUserInfo->bkg_verification_code);
			$url	 = Yii::app()->params['fullBaseURL'] . '/u/' . $bkgId . '/' . $hash . '/p/' . $phone;
		}
		return $url;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UnverifiedFollowup the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getByBkgId($bkgId)
	{
		$criteria = new CDbCriteria;
		$criteria->addCondition("`unv_bkg_id` = '" . $bkgId . "'");
		return $this->find($criteria);
	}

	/**
	 * 
	 * @param integer $bkgId
	 * @param integer $bkgGroup
	 * @param string $desc
	 * @return boolean
	 */
	public function updateForPriceWasHigh($bkgId, $bkgGroup = 1, $desc = '')
	{
		$success			 = false;
		$userInfo			 = UserInfo::model();
		$userInfo->userType	 = UserInfo::TYPE_CONSUMER;
		$eventId = BookingLog::UNVERIFIED_FOLLOWUP_PRICE_HIGH;
		if ($bkgGroup == 1)
		{
			$userInfo->userId	 = $model->bkgUserInfo->bkg_user_id;
			$model				 = Booking::model()->findByPk($bkgId);
			$model->bkg_status	 = 9;
			if ($model->save())
			{
				$oldModel						 = clone $model;
				$params['blg_booking_status']	 = $model->bkg_status;
				BookingLog::model()->createLog($model->bkg_id, $desc, $userInfo, $eventId, $oldModel, $params);
			}
			$success = true;
		}
		else if ($bkgGroup == 2)
		{
			$userInfo->userId	 = $leadModel->bkg_user_id;
			$leadModel			 = BookingTemp::model()->findByPk($bkgId);
			$followStatus		 = $leadModel->bkg_follow_up_status;
			LeadLog::model()->createLog($leadModel->bkg_id, $desc, $userInfo, '', $followStatus, $eventId);
			$success			 = true;
		}
		return $success;
	}

	/**
	 * 
	 * @param integer $bkgId
	 * @param integer $bkgGroup
	 * @param string $dateRequested
	 * @param intgeger $isTentative
	 */
	public function updateForJustLooking($bkgId, $bkgGroup, $dateRequested, $isTentative)
	{
		$success			 = false;
		$userInfo			 = UserInfo::model();
		$userInfo->userType	 = UserInfo::TYPE_CONSUMER;
		if ($bkgGroup == 1)
		{
			$userInfo->userId	 = $model->bkgUserInfo->bkg_user_id;
			$model				 = Booking::model()->findByPk($bkgId);
			if ($isTentative == 1)
			{
				$desc	 = "Customer says was looking. Change pickup date to " . $dateRequested . ".";
				$eventId = BookingLog::UNVERIFIED_FOLLOWUP_LOOKING_TENTATIVE;
			}
			else
			{
				$model->bkg_status	 = 9;
				$model->save();
				$desc				 = "Customer says was looking. Will travel on " . $dateRequested . " ";
				$eventId			 = BookingLog::UNVERIFIED_FOLLOWUP_LOOKING;
			}
			$oldModel						 = clone $model;
			$params['blg_booking_status']	 = $model->bkg_status;
			BookingLog::model()->createLog($model->bkg_id, $desc, $userInfo, $eventId, $oldModel, $params);
			$success						 = true;
		}
		else
		{
			$userInfo->userId = $leadModel->bkg_user_id;
			if ($isTentative == 1)
			{
				$desc = "Customer says was looking. Change pickup date to " . $dateRequested . ".";
				$eventId = BookingLog::UNVERIFIED_FOLLOWUP_LOOKING_TENTATIVE;
			}
			else
			{
				$desc = "Customer says was looking. Will travel on " . $dateRequested . " ";
				$eventId			 = BookingLog::UNVERIFIED_FOLLOWUP_LOOKING;
			}
			$leadModel		 = BookingTemp::model()->findByPk($bkgId);
			$followStatus	 = $leadModel->bkg_follow_up_status;
			LeadLog::model()->createLog($leadModel->bkg_id, $desc, $userInfo, '', $followStatus, $eventId);
			$success		 = true;
		}

		return $success;
	}

	/**
	 * 
	 * @param integer $bkgId
	 * @param integer $bkgGroup
	 * @param string $desc
	 */
	public function updateForOther($bkgId, $bkgGroup, $desc)
	{
		$success			 = false;
		$userInfo			 = UserInfo::model();
		$userInfo->userType	 = UserInfo::TYPE_CONSUMER;
		$eventId			 = BookingLog::UNVERIFIED_FOLLOWUP_OTHER;
		if ($bkgGroup == 1)
		{
			$userInfo->userId				 = $model->bkgUserInfo->bkg_user_id;
			$model							 = Booking::model()->findByPk($bkgId);
			$oldModel						 = clone $model;
			$params['blg_booking_status']	 = $model->bkg_status;
			BookingLog::model()->createLog($model->bkg_id, $desc, $userInfo, $eventId, $oldModel, $params);
			$success						 = true;
		}
		else if ($bkgGroup == 2)
		{
			$leadModel			 = BookingTemp::model()->findByPk($bkgId);
			$userInfo->userId	 = $leadModel->bkg_user_id;
			$followStatus		 = $leadModel->bkg_follow_up_status;
			LeadLog::model()->createLog($leadModel->bkg_id, $desc, $userInfo, '', $followStatus, $eventId);
			//$eventid			 = BookingLog::BOOKING_CREATED;
			//LeadLog::model()->createLog($bkgid, $desc, $userInfo, '', '', $eventid);
			$success			 = true;
		}
		return $success;
	}

	/**
	 * 
	 * @param integer $bkgId
	 * @param integer $bkgGroup
	 * @param string $desc
	 * @return boolean
	 */
	public function updateForCallMe($bkgId, $bkgGroup, $desc)
	{
		$success			 = false;
		$userInfo			 = UserInfo::model();
		$userInfo->userType	 = UserInfo::TYPE_CONSUMER;
		if ($bkgGroup == 1)
		{
			$model			 = Booking::model()->findByPk($bkgId);
			$oldTrailModel	 = clone $model->bkgTrail;
			if (isset($followup_desc) && $followup_desc != '')
			{
				$is_followupFlag					 = 1;
				$model->bkgTrail->bkg_followup_date	 = Filter::getDBDateTime();
				$userInfo							 = UserInfo::getInstance();
				$model->bkgTrail->setFollowup($followup_desc, $oldTrailModel, $userInfo);
			}
			$success = true;
		}
		else
		{
			$eventId = BookingLog::FOLLOWUP_ASSIGN;
			$model							 = BookingTemp::model()->findbyPk($bkgId);
			$userInfo->userId				 = $model->bkg_user_id;
			$model->bkg_follow_up_status	 = LeadLog::CALL_ME_PLEASE;
			$model->bkg_follow_up_comment	 = $desc;
			$model->bkg_follow_up_by		 = $userInfo->userId;
			$model->bkg_follow_up_on		 = new CDbExpression('NOW()');
			$model->scenario				 = 'update_followup';
			if ($model->validate() && $model->save())
			{
				LeadLog::model()->createLog($model->bkg_id, $desc, $userInfo, "", $model->bkg_follow_up_status, $eventId);
				$success = true;
			}
		}
		return $success;
	}

}
