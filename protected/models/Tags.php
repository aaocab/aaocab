<?php

use components\Event\Events;
use components\Event\EventSchedule;
use components\Event\EventReceiver;

/**
 * This is the model class for table "tags".
 *
 * The followings are the available columns in table 'tags':
 * @property integer $tag_id
 * @property string $tag_name
 * @property string $tag_desc
 * @property integer $tag_booking
 * @property integer $tag_user
 * @property integer $tag_vendor
 * @property integer $tag_partner
 * @property integer $tag_driver
 * @property integer $tag_cab
 * @property integer $tag_active
 * @property string $tag_color
 */
class Tags extends CActiveRecord
{

	const TYPE_USER	 = 1;
	const TYPE_BOOKING = 2;
	const TYPE_VENDOR	 = 3;
	const TYPE_PARTNER = 4;
	const TYPE_DRIVER	 = 5;
	const TYPE_CAB	 = 6;

	public $types = [1 => 'User', 2 => 'Booking', 3 => 'Vendor', 4 => 'Partner', 5 => 'Driver', 6 => 'Cab'];
	public $tagName, $tagDesc;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tags';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tag_name', 'required'),
			array('tag_name', 'unique'),
			array('tag_booking, tag_user, tag_vendor, tag_partner, tag_driver, tag_cab, tag_active', 'numerical', 'integerOnly' => true),
			array('tag_name', 'length', 'max' => 100),
			array('tag_desc', 'length', 'max' => 500),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('tag_id, tag_name, tag_desc, tag_booking, tag_user, tag_vendor, tag_partner, tag_driver, tag_cab, tag_active', 'safe', 'on' => 'search'),
			array('tag_id, tag_name, tag_desc, tag_booking, tag_user, tag_vendor, tag_partner, tag_driver, tag_cab, tag_active, tagName, tag_color', 'safe'),
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
			'tag_id'		 => 'Id',
			'tag_name'		 => 'Tag',
			'tag_desc'		 => 'Desc',
			'tag_booking'	 => 'Use with Booking',
			'tag_user'		 => 'Use with User',
			'tag_vendor'	 => 'Use with Vendor',
			'tag_partner'	 => 'Use with Partner',
			'tag_driver'	 => 'Use with Driver',
			'tag_cab'		 => 'Use with Cab',
			'tag_active'	 => 'Active',
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

		$criteria->compare('tag_id', $this->tag_id);
		$criteria->compare('tag_name', $this->tag_name, true);
		$criteria->compare('tag_desc', $this->tag_desc, true);
		$criteria->compare('tag_booking', $this->tag_booking);
		$criteria->compare('tag_user', $this->tag_user);
		$criteria->compare('tag_vendor', $this->tag_vendor);
		$criteria->compare('tag_partner', $this->tag_partner);
		$criteria->compare('tag_driver', $this->tag_driver);
		$criteria->compare('tag_cab', $this->tag_cab);
		$criteria->compare('tag_active', $this->tag_active);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Tags the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function checkExistingTag()
	{
		
	}

	public static function getFieldByType($type)
	{
		$tagfieId = false;
		switch ($type)
		{
			case self::TYPE_BOOKING:
				$tagfieId	 = "tag_booking";
				break;
			case self::TYPE_USER:
				$tagfieId	 = "tag_user";
				break;
			case self::TYPE_VENDOR:
				$tagfieId	 = "tag_vendor";
				break;
			case self::TYPE_DRIVER:
				$tagfieId	 = "tag_driver";
				break;
			case self::TYPE_PARTNER:
				$tagfieId	 = "tag_partner";
				break;
			case self::TYPE_CAB:
				$tagfieId	 = "tag_cab";
				break;
			default:
				break;
		}
		return $tagfieId;
	}

	public static function getListByType($type)
	{
		$tagfieId = self::getFieldByType($type);
		if (!$tagfieId)
		{
			return false;
		}
		$sql	 = "SELECT tag_id, tag_name FROM tags  
				 	WHERE $tagfieId = 1 AND tag_active = 1";
		$tags	 = DBUtil::query($sql, DBUtil::SDB());
		$list	 = [];
		foreach ($tags as $tag)
		{
			$list[$tag['tag_id']] = $tag['tag_name'];
		}
		return $list;
	}

	public static function getListByids($ids)
	{
		$tagList = trim($ids, ',');
		$sql	 = "SELECT tag_id, tag_name,tag_desc,tag_color FROM tags  
				 	WHERE tag_id IN ($tagList) AND tag_active = 1";
		$tags	 = DBUtil::query($sql, DBUtil::SDB());

		return $tags;
	}

	public static function getByType($type)
	{
		$tagfieId = self::getFieldByType($type);
		if (!$tagfieId)
		{
			return $tagfieId;
		}
		$sql	 = "SELECT GROUP_CONCAT(tag_id) as ids FROM tags WHERE $tagfieId = 1 AND tag_active = 1";
		$tags	 = DBUtil::queryScalar($sql, DBUtil::SDB());
		return $tags;
	}

	public function fetchList($qry = [])
	{
		$where	 = '';
		$params	 = [];
		if ($qry['tagName'] != '')
		{
			$tagname = strtoupper($qry['tagName']);
			$where	 .= " AND tag_name LIKE '%{$tagname}%'";
		}
		$sql			 = "SELECT * FROM `tags`
                    WHERE tag_active = 1 $where";
		$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB(), $params);
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count, 'params'		 => $params,
			'sort'			 => ['attributes'	 => ['tag_name'],
				'defaultOrder'	 => 'tag_name ASC'],
			'pagination'	 => ['pageSize' => 50],
		]);
		return $dataprovider;
	}

	public static function getVIPCount()
	{
		$fromDate	 = date('Y-m-d', strtotime('-1 month'));
		$todate		 = date('Y-m-d', strtotime('+11 month'));
		$sql		 = "select count(1) FROM booking bkg INNER JOIN booking_invoice ON biv_bkg_id = bkg.bkg_id INNER JOIN booking_trail bkgTrail ON bkgTrail.btr_bkg_id = biv_bkg_id WHERE 1
				AND (bkg_pickup_date BETWEEN '{$fromDate} 00:00:00' AND '{$todate} 23:59:59' ) AND bkg_status IN (2,3,5)  
 			    AND bkgTrail.bkg_tags IS NOT NULL AND CONCAT(',', bkg_tags , ',') REGEXP ',(2|3),'";
		$count		 = DBUtil::queryScalar($sql);
		return $count;
	}

	public static function isVIPBooking($bkgId)
	{
		$sql	 = "select bkg_id FROM booking bkg INNER JOIN booking_trail bkgTrail ON bkgTrail.btr_bkg_id = bkg.bkg_id WHERE 1
 			    AND bkgTrail.bkg_tags IS NOT NULL AND CONCAT(',', bkg_tags , ',') REGEXP ',(2),'  AND bkg.bkg_id = {$bkgId}";
		$data	 = DBUtil::queryScalar($sql);
		if ($data > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public static function isVVIPBooking($bkgId)
	{
		$sql	 = "select bkg_id FROM booking bkg INNER JOIN booking_trail bkgTrail ON bkgTrail.btr_bkg_id = bkg.bkg_id WHERE 1
 			    AND bkgTrail.bkg_tags IS NOT NULL AND CONCAT(',', bkg_tags , ',') REGEXP ',(3),'  AND bkg.bkg_id = {$bkgId}";
		$data	 = DBUtil::queryScalar($sql);
		if ($data > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public static function notifyVIPTaggedBooking($bkgId, $notifyWhatsapp = false, $generateCBR = false, $label = '')
	{
		$model		 = Booking::model()->findByPk($bkgId);
		$bkgTrail	 = BookingTrail::model()->getbyBkgId($bkgId);

		if ($notifyWhatsapp)
		{
			$strPhones	 = Config::get('booking.tag.vip.csr.whatsapp.admin.ids');
			$arrAdminIds = json_decode($strPhones, true);
			if ($model->bkg_status == 15)
			{
				$arrAdminIds = $arrAdminIds[15];
			}
			if ($model->bkg_status == 2)
			{
				$arrAdminIds = $arrAdminIds[2];
			}
			foreach ($arrAdminIds as $adminId)
			{
				$phone = Admins::model()->findByPk($adminId)->adm_phone;

				if ($bkgTrail->bkg_tags != '' && $phone != '')
				{
					$strTag		 = '';
					$vipTagsArr	 = Tags::getListByids($bkgTrail->bkg_tags);
					foreach ($vipTagsArr as $key => $val)
					{
						if ($key > 0)
						{
							$strTag .= "/";
						}
						$strTag .= $val['tag_name'];
					}
					self::notifyViaWhatsapp($bkgId, $strTag, $phone, $adminId);
				}
				else if ($label != '' && $phone != '')
				{
					self::notifyViaWhatsapp($bkgId, $label, $phone, $adminId);
				}
			}
		}
		if ($generateCBR)
		{
			if ($bkgTrail->bkg_tags != '')
			{
				$strTag		 = '';
				$vipTagsArr	 = Tags::getListByids($bkgTrail->bkg_tags);
				foreach ($vipTagsArr as $key => $val)
				{
					if ($key > 0)
					{
						$strTag .= "/";
					}
					$strTag .= $val['tag_name'];
				}
				ServiceCallQueue::notifyTaggedBooking($bkgId, $strTag);
			}
			else if ($label != '')
			{
				ServiceCallQueue::notifyTaggedBooking($bkgId, $label);
			}
		}
	}

	public static function notifyVVIPTaggedBooking($bkgId, $notifyWhatsapp = false, $generateCBR = false, $label = '')
	{
		$model		 = Booking::model()->findByPk($bkgId);
		$bkgTrail	 = BookingTrail::model()->getbyBkgId($bkgId);

		if ($notifyWhatsapp)
		{
			$strPhones	 = Config::get('booking.tag.vip.csr.whatsapp.admin.ids');
			$arrAdminIds = json_decode($strPhones, true);
			if ($model->bkg_status == 15)
			{
				$arrAdminIds = $arrAdminIds[15];
			}
			if ($model->bkg_status == 2 && $model->bkg_reconfirm_flag == 1)
			{
				$arrAdminIds = $arrAdminIds[2];
			}
			foreach ($arrAdminIds as $adminId)
			{
				$phone = Admins::model()->findByPk($adminId)->adm_phone;

				if ($bkgTrail->bkg_tags != '' && $phone != '')
				{
					$strTag		 = '';
					$vipTagsArr	 = Tags::getListByids($bkgTrail->bkg_tags);
					foreach ($vipTagsArr as $key => $val)
					{
						if ($key > 0)
						{
							$strTag .= "/";
						}
						$strTag .= $val['tag_name'];
					}
					self::notifyViaWhatsapp($bkgId, $strTag, $phone, $adminId);
				}
				else if ($label != '' && $phone != '')
				{
					self::notifyViaWhatsapp($bkgId, $label, $phone, $adminId);
				}
			}
		}
		if ($generateCBR)
		{
			if ($bkgTrail->bkg_tags != '')
			{
				$strTag		 = '';
				$vipTagsArr	 = Tags::getListByids($bkgTrail->bkg_tags);
				foreach ($vipTagsArr as $key => $val)
				{
					if ($key > 0)
					{
						$strTag .= "/";
					}
					$strTag .= $val['tag_name'];
				}
				ServiceCallQueue::notifyTaggedBooking($bkgId, $strTag);
			}
			else if ($label != '')
			{
				ServiceCallQueue::notifyTaggedBooking($bkgId, $label);
			}
		}
	}

	/**
	 * This function is used to send notifications  for user  who is applicable for double back offer
	 * @param integer $bkgId
	 * @return None
	 */
	public static function notifyViaWhatsapp($bkgId, $tags, $phoneNumber, $adminId, $isSchedule = 0, $schedulePlatform = null)
	{
		$success = false;
		try
		{
			if ($bkgId > 0)
			{
				$model = Booking::model()->findByPk($bkgId);
			}
			if (!$model)
			{
				goto skipAll;
			}

			if ($phoneNumber == '' || !$phoneNumber)
			{
				goto skipAll;
			}
			Filter::parsePhoneNumber($phoneNumber, $code, $number);
			if (!Filter::processPhoneNumber($number, $code))
			{
				goto skipAll;
			}
			if ($model->bkg_status == 15 && WhatsappLog::checkIfAreadySent(1, $bkgId, 59, $code . $number))
			{
				return true;
			}
			$contentParams		 = array(
				'tags'		 => $tags,
				'bookingId'	 => Filter::formatBookingId($model->bkg_booking_id)
			);
			$receiverParams		 = EventReceiver::setData(UserInfo::TYPE_ADMIN, $adminId, WhatsappLog::REF_TYPE_BOOKING, $bkgId, $model->bkg_booking_id, $code, $number, null, 0, null, null);
			$eventScheduleParams = EventSchedule::setData($bkgId, ScheduleEvent::BOOKING_REF_TYPE, ScheduleEvent::VIP_BOOKING, "Notify for {$tags} Booking", $isSchedule, CJSON::encode(array('bkgId' => $bkgId)), 10, $schedulePlatform);
			MessageEventMaster::processPlatformSequences(29, $contentParams, $receiverParams, $eventScheduleParams);
		}
		catch (Exception $ex)
		{
			ReturnSet::setException($ex);
		}
		skipAll:
		return $success;
	}

	/*	 * *
	 * Update tags in all upcoming and current bookings related to this contact id
	 * $cttId contact id whose bookings to update
	 * $cttTags tags which are in that contact to update in bookings
	 * return type boolean true or false
	 */

	public static function updateBookingTags($cttId, $cttTags)
	{

		try
		{
			$params		 = ['cttId' => $cttId, 'cttTags' => '' . $cttTags . ''];
			$sql		 = "UPDATE booking_trail,booking_user,booking SET bkg_tags = :cttTags WHERE btr_bkg_id = bui_bkg_id AND bkg_id = btr_bkg_id AND bkg_status IN(15,1,2,3,4,5) AND bkg_tags IS NULL  AND bkg_contact_id = :cttId";
			DBUtil::execute($sql, $params);
			$cttTagsArr	 = explode(",", $cttTags);
			foreach ($cttTagsArr as $key => $value)
			{
				$sql = "UPDATE booking_trail,booking_user,booking SET bkg_tags = CONCAT(bkg_tags, ',', {$value}) WHERE btr_bkg_id = bui_bkg_id 
					   AND bkg_id = btr_bkg_id AND bkg_status IN(15,1,2,3,4,5) AND bkg_tags IS NOT NULL AND bkg_contact_id =:cttId 
					   AND CONCAT(',', bkg_tags , ',') NOT REGEXP ',({$value}),'";
				DBUtil::execute($sql, ['cttId' => $cttId]);
			}
		}
		catch (Exception $e)
		{
			return false;
		}
		return true;
	}

}
