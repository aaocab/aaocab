<?php

/**
 * This is the model class for table "unsubscribe".
 *
 * The followings are the available columns in table 'unsubscribe':
 * @property integer $usb_id
 * @property string $usb_email
 * @property string $usb_create_date
 * @property integer $usb_active
 * @property string $usb_reason
 * @property integer $usb_cat_promotional
 * @property integer $usb_cat_booking
 * @property integer $usb_cat_transactional
 * @property integer $usb_cat_driverupdate
 * @property integer $usb_cat_ratings
 * @property integer $usb_cat_accountinfo
 */
class Unsubscribe extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	const CAT_PROMOTIONAL				 = 1;
	const CAT_BOOKING					 = 2;
	const CAT_TRANSACTIONAL			 = 3;
	const CAT_DRIVERUPDATE			 = 4;
	const CAT_RATING					 = 5;
	const CAT_ACCOUNT_UPDATE_AND_INFO	 = 6;

	public function tableName()
	{
		return 'unsubscribe';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
// NOTE: you should only define rules for those attributes that
// will receive user inputs.
		return array(
			array('usb_email', 'required'),
			array('usb_email', 'email', 'message' => 'Please enter valid email address'),
			array('usb_email', 'unique', 'on' => 'insert'),
			array('usb_email', 'length', 'max' => 200),
			// The following rule is used by search().
// @todo Please remove those attributes that should not be searched.
			array('usb_id, usb_email,usb_reason,usb_cat_promotional,usb_cat_accountinfo,usb_cat_transactional,usb_cat_driverupdate,usb_cat_ratings,usb_cat_booking, usb_create_date, usb_active', 'safe'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
// NOTE: you may need to adjust the relation name and the related
// class name for the relations automatically generated below.
		return array();
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'usb_id'			 => 'Usb',
			'usb_email'			 => 'Email',
			'usb_create_date'	 => 'Usb Create Date',
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

		$criteria->compare('usb_id', $this->usb_id);
		$criteria->compare('usb_email', $this->usb_email, true);
		$criteria->compare('usb_create_date', $this->usb_create_date, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Unsubscribe the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getCategoryList($category = '')
	{
		$arrCategories = [1 => 'Promotional', 2 => 'Booking', 3 => 'Transactional', 4 => 'Driver Updates', 5 => 'Rating And Reviews', 6 => 'Account Update And Info'];
		if ($category != '')
		{
			return $arrCategories[$category];
		}
		return $arrCategories;
	}

	public function getAll()
	{
		$sql			 = "SELECT * FROM `unsubscribe` WHERE 1 AND usb_active=1 ORDER BY unsubscribe.usb_create_date DESC";
		$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc")->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => ['usb_email', 'usb_create_date'],
				'defaultOrder'	 => 'usb_create_date DESC'], 'pagination'	 => ['pageSize' => 50],
		]);
		return $dataprovider;
	}

	public function updateActive($usbid)
	{
		$sql = "UPDATE `unsubscribe` SET `usb_active` = 0 WHERE unsubscribe.usb_id = $usbid";
		DBUtil::command($sql)->execute();
	}

	public static function isUnsubscribed($email = '', $categoryType = 0)
	{

		if ($email != '')
		{
			$model = Unsubscribe::model()->find('usb_email=:email AND usb_active=1', ['email' => $email]);
			if ($model != '')
			{
				switch ($categoryType)
				{
					CASE Unsubscribe::CAT_PROMOTIONAL:
						if ($model->usb_cat_promotional == 1)
						{
							return true;
						}
						break;
					CASE Unsubscribe::CAT_BOOKING:
						if ($model->usb_cat_booking == 1)
						{
							return true;
						}
						break;
					CASE Unsubscribe::CAT_TRANSACTIONAL:
						if ($model->usb_cat_transactional == 1)
						{
							return true;
						}
						break;
					CASE Unsubscribe::CAT_DRIVERUPDATE:
						if ($model->usb_cat_driverupdate == 1)
						{
							return true;
						}
						break;
					CASE Unsubscribe::CAT_RATING:
						if ($model->usb_cat_ratings == 1)
						{
							return true;
						}
					CASE Unsubscribe::CAT_ACCOUNT_UPDATE_AND_INFO:
						if ($model->usb_cat_accountinfo == 1)
						{
							return true;
						}
						break;
					default :
						return false;
						break;
				}
			}
		}
		return false;
	}

}
