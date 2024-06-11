<?php

/**
 * This is the model class for table "booking_vendor_request".
 *
 * The followings are the available columns in table 'booking_vendor_request':
 * @property integer $bvr_id
 * @property integer $bvr_bcb_id
 * @property integer $bvr_booking_id
 * @property integer $bvr_vendor_id
 * @property integer $bvr_vendor_rating
 * @property integer $bvr_vendor_score
 * @property integer $bvr_bid_amount
 * @property string $bvr_created_at
 * @property integer $bvr_accepted
 * @property string $bvr_accepted_at
 * @property integer $bvr_assigned
 * @property string $bvr_assigned_at
 * @property string $bvr_last_reminded_at
 * @property integer $bvr_app_notification
 * @property integer $bvr_sms_notification
 * @property float    $bvr_smt_score
 * @property integer $bvr_is_preferred_vendor
 * @property string $bvr_special_remarks
 * @property integer $bvr_is_gozonow
 * @property integer $bvr_active
 * @property integer $bvr_deny_reason_id
 */
class BookingVendorRequest extends CActiveRecord
{

	public $bvr_top_vendor_score, $bvr_vendor_ids;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'booking_vendor_request';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('bvr_booking_id, bvr_vendor_id', 'required'),
			array('bvr_booking_id, bvr_vendor_id, bvr_vendor_score, bvr_accepted, bvr_assigned, bvr_active', 'numerical', 'integerOnly' => true),
			array('bvr_accepted_at, bvr_assigned_at, bvr_last_reminded_at,bvr_is_preferred_vendor,bvr_special_remarks,bvr_is_gozonow,bvr_bcb_id', 'safe'),
			['bvr_bid_amount', 'required', 'on' => 'setbid'],
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('bvr_id, bvr_booking_id, bvr_vendor_id, bvr_vendor_rating, bvr_vendor_score, bvr_created_at, bvr_accepted, bvr_accepted_at, bvr_assigned, bvr_assigned_at, bvr_last_reminded_at,bvr_app_notification, bvr_sms_notification, bvr_active, bvr_bid_amount', 'safe', 'on' => 'search'),
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
			'bvrBooking' => array(self::BELONGS_TO, 'Booking', 'bvr_booking_id'),
			'bvrVendor'	 => array(self::BELONGS_TO, 'Vendors', 'bvr_vendor_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'bvr_id'				 => 'Bvr',
			'bvr_booking_id'		 => 'Bvr Booking',
			'bvr_vendor_id'			 => 'Bvr Vendor',
			'bvr_vendor_rating'		 => 'Bvr Vendor Rating',
			'bvr_vendor_score'		 => 'Bvr Vendor Score',
			'bvr_created_at'		 => 'Bvr Created At',
			'bvr_accepted'			 => 'Bvr Accepted',
			'bvr_accepted_at'		 => 'Bvr Accepted At',
			'bvr_assigned'			 => 'Bvr Assigned',
			'bvr_assigned_at'		 => 'Bvr Assigned At',
			'bvr_last_reminded_at'	 => 'Bvr Last Reminded At',
			'bvr_active'			 => 'Bvr Active',
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

		$criteria->compare('bvr_id', $this->bvr_id);
		$criteria->compare('bvr_booking_id', $this->bvr_booking_id);
		$criteria->compare('bvr_vendor_id', $this->bvr_vendor_id);
		$criteria->compare('bvr_vendor_rating', $this->bvr_vendor_rating);
		$criteria->compare('bvr_vendor_score', $this->bvr_vendor_score);
		$criteria->compare('bvr_created_at', $this->bvr_created_at, true);
		$criteria->compare('bvr_accepted', $this->bvr_accepted);
		$criteria->compare('bvr_accepted_at', $this->bvr_accepted_at, true);
		$criteria->compare('bvr_assigned', $this->bvr_assigned);
		$criteria->compare('bvr_assigned_at', $this->bvr_assigned_at, true);
		$criteria->compare('bvr_last_reminded_at', $this->bvr_last_reminded_at, true);
		$criteria->compare('bvr_active', $this->bvr_active);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BookingVendorRequest the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function isRequestSent($bkgid)
	{
		$criteria = new CDbCriteria;
		$criteria->compare('bvr_booking_id', $bkgid);
		return $this->find($criteria);
	}

	public function SendRequest($bkgid)
	{
		$this->stepRequest($bkgid, '5'); //1st Call

		$this->stepRequest($bkgid, '5,10'); //2nd Call

		$this->stepRequest($bkgid, '15,4294967295'); //3rd Call
	}

	public function getBiddingPriorities($param)
	{
		$sql = "";
	}

	public function stepRequest($bkgid, $limit = '', $onlyAttached = false, $onlyFreeze = false)
	{
		$agtIds		 = $this->getNotifiedVendorIdsbyBookingId($bkgid);
		$bkgModel	 = Booking::model()->findByPk($bkgid);
		$tripId		 = $bkgModel->bkg_bcb_id;
		$where		 = '';
		if ($agtIds != '')
		{
			$where .= ' agt.vnd_id NOT IN (' . $agtIds . ') AND ';
		}
		$where					 .= ' agt.vnd_id IN (SELECT apt_entity_id FROM `app_tokens` WHERE apt_user_type = 2 AND apt_user_id IS NOT NULL AND apt_entity_id IS NOT NULL GROUP by apt_entity_id)';
		$order					 = " totalScore DESC";
		$vndModel				 = Vendors::model();
		$vndModel->vndIsFreezed	 = 1;
		$commandArr				 = $vndModel->fetchRatingQuery($bkgid, '', $where, $limit, $order, $onlyAttached, $onlyFreeze);
		$command				 = $commandArr['sqlCommand'];
		$agtList				 = $command->queryAll();

		if (count($agtList) == 0 && ($onlyFreeze || $onlyAttached))
		{
			BookingTrail::model()->updateVendorRequestCounter($bkgid);
			BookingCab::model()->updateVendorRequestCounter($tripId);
			return;
		}
		$reqArr = [];
		foreach ($agtList as $k => $agt)
		{
			$reqArr[] = [
				'bvr_bcb_id'		 => $tripId,
				'bvr_booking_id'	 => $bkgid,
				'bvr_vendor_id'		 => $agt['vnd_id'],
				'bvr_vendor_rating'	 => $agt['vrs_vnd_overall_rating'],
				'bvr_vendor_score'	 => $agt['totalScore']
			];
		}


		if (sizeof($reqArr) > 0)
		{
			Logger::create("Total Operator: " . sizeof($reqArr), CLogger::LEVEL_TRACE);
			$builder = Yii::app()->db->schema->commandBuilder;
			$command = $builder->createMultipleInsertCommand('booking_vendor_request', $reqArr);
			if ($command->execute() > 0)
			{
				BookingTrail::model()->updateVendorRequestCounter($bkgid);
				BookingCab::model()->updateVendorRequestCounter($tripId);
			}
//			$agtArr	 = [];
//			$agtIds1 = $this->getVendorIdsbyBookingIdNotAccepted($bkgid);
//			if ($agtIds1 != '')
//			{
//				$agtArr = explode(',', $agtIds1);
//				foreach ($agtArr as $vendorid)
//				{
//					$success = AppTokens::model()->notifyVendorBookingRequest($vendorid, $tripId);
//					if ($success)
//					{
//						$this->updateLastReminder($bkgid, $vendorid, 1, 0);
//					}
//				}
//			}
		}
	}

	public function isAssigned($bkgid)
	{
		$criteria = new CDbCriteria();
		$criteria->compare('bvr_booking_id', $bkgid);
		$criteria->compare('bvr_assigned', 1);
		$criteria->compare('bvr_active', 0);
		return $this->findAll($criteria);
	}

	public function isVendorAssigned($bkgid, $vndid)
	{
		$criteria = new CDbCriteria();
		$criteria->compare('bvr_booking_id', $bkgid);
		$criteria->compare('bvr_vendor_id', $vndid);
		$criteria->compare('bvr_assigned', 1);
		return $this->find($criteria);
	}

	public function isVendorAccepted($bkgid, $vndid)
	{
		$criteria = new CDbCriteria();
		$criteria->compare('bvr_booking_id', $bkgid);
		$criteria->compare('bvr_vendor_id', $vndid);
		$criteria->compare('bvr_accepted', 1);
		return $this->find($criteria);
	}

	/** @return array BookingVendorRequest rows */
	public static function getActiveListByTripId($tripId)
	{
		$sql	 = "SELECT * FROM booking_vendor_request WHERE bvr_active=1 AND bvr_accepted=1 AND bvr_bcb_id=:tripId";
		$params	 = ["tripId" => $tripId];
		$rows	 = DBUtil::query($sql, DBUtil::MDB(), $params);
		return $rows;
	}

	public function getVendorIdsbyBookingId($bkgid)
	{
		$sql = "SELECT GROUP_CONCAT(bvr_vendor_id) as bvr_vendor_ids FROM
				`booking_vendor_request`
				WHERE `bvr_booking_id` =$bkgid";

		$cdb	 = DBUtil::command($sql);
		$model	 = $cdb->queryRow();
		return $model['bvr_vendor_ids'];
	}

	public static function getVendorIdsbyBcbId($bcbId)
	{
		$sql		 = "SELECT GROUP_CONCAT(bvr_vendor_id) as bvr_vendor_ids FROM `booking_vendor_request` WHERE `bvr_bcb_id`=$bcbId";
		$vendorIds	 = DBUtil::queryScalar($sql);

		return $vendorIds;
	}

	public function getRespondedVendorIdsbyBookingId($bkgid)
	{
		$sql = "SELECT GROUP_CONCAT(bvr_vendor_id) as bvr_vendor_ids FROM
				`booking_vendor_request`
				WHERE `bvr_booking_id` = $bkgid
				AND (`bvr_accepted` <> 0 OR `bvr_assigned` <> 0)";

		$cdb	 = DBUtil::command($sql);
		$model	 = $cdb->queryRow();
		return $model['bvr_vendor_ids'];
	}

	public function getVendorIdsbyBookingIdNotAccepted($bkgid)
	{
		$sql = "SELECT GROUP_CONCAT(bvr_vendor_id) as bvr_vendor_ids
			FROM `booking_vendor_request`
			WHERE `bvr_booking_id` =$bkgid  AND `bvr_accepted` = 0 AND `bvr_active` = 1";

		$cdb	 = DBUtil::command($sql);
		$model	 = $cdb->queryRow();
		return $model['bvr_vendor_ids'];
	}

	public function getPendingAppNotification()
	{
		$sql = "SELECT  bvr_vendor_id, COUNT(DISTINCT bvr_bcb_id) as cnt
			FROM `booking_vendor_request`
			INNER JOIN booking ON bvr_booking_id=bkg_id AND bkg_status=2
			INNER JOIN app_tokens ON bvr_vendor_id=app_tokens.apt_entity_id AND apt_user_type=2 AND apt_last_login>=DATE_SUB(NOW(), INTERVAL 3 DAY) AND apt_device_token IS NOT NULL AND apt_status=1
			WHERE `bvr_accepted`=0 AND bvr_app_notification=0 AND `bvr_active` = 1  
			GROUP BY bvr_vendor_id";

		$cdb		 = DBUtil::command($sql);
		$vendorList	 = $cdb->queryAll();
		return $vendorList;
	}

	public function sendVendorNotification($booking_id, $vendor_id, $fromCity, $toCity, $pickupdate)
	{
		$payLoadData = ['bookingId' => $booking_id, 'EventCode' => Booking::CODE_VENDOR_BOOKING_REQUEST];
		$success	 = AppTokens::model()->notifyVendor($vendor_id, $payLoadData, "(" . $booking_id . ") " . $fromCity . " to " . $toCity . " on " . $pickupdate, "A new booking has been requested");
		return $success;
	}

	public function updateLastReminder($bkg_id, $vendor_id, $app_val = 0, $sms_val = 0)
	{
		$criteria					 = new CDbCriteria();
		$criteria->compare('bvr_booking_id', $bkg_id);
		$criteria->compare('bvr_vendor_id', $vendor_id);
		$model						 = $this->find($criteria);
		$model->bvr_last_reminded_at = new CDbExpression('NOW()');
		if ($app_val					 = 1)
		{
			$model->bvr_app_notification = 1;
		}
		else if ($sms_val = 1)
		{
			$model->bvr_sms_notification = 1;
		}
		return $model->save();
	}

	public function updateVendorLastReminder($vendor_id, $app_val = 0, $sms_val = 0)
	{
		if ($vendor_id > 0 && ($app_val == 1 || $sms_val == 1))
		{
			$paramSet	 = '';
			$paramWhere	 = '';
			if ($app_val == 1)
			{
				$paramSet	 .= "bvr_app_notification=$app_val";
				$paramWhere	 .= "bvr_app_notification=0";
			}
			elseif ($sms_val == 1)
			{
				$paramSet	 .= "bvr_sms_notification=$sms_val";
				$paramWhere	 .= "bvr_sms_notification = 0";
			}
			$sql = "UPDATE booking_vendor_request
				SET $paramSet ,bvr_last_reminded_at = NOW() 
				WHERE bvr_vendor_id=$vendor_id AND $paramWhere AND bvr_active = 1";
			$row = DBUtil::command($sql)->execute();
		}
		return true;
	}

	public function getAcceptedRequests()
	{

		$criteria			 = new CDbCriteria();
		$criteria->select	 = ['bvr_booking_id', 'GROUP_CONCAT(`t`.bvr_vendor_id) bvr_vendor_ids '];
		$criteria->join		 = "JOIN  booking  bkg ON `t`.bvr_booking_id = bkg.bkg_id ";
		$criteria->addCondition('bkg_create_date > DATE_SUB(NOW(), INTERVAL 1180 MINUTE)');
		$criteria->addCondition("bkg_status = 2 ");
		$criteria->addCondition("bvr_accepted = 1 ");
		$criteria->group	 = 'bvr_booking_id';
		$criteria->order	 = 'bkg_create_date DESC';
		$models				 = $this->findAll($criteria);

		return $models;
	}

	public function getTopRatedAcceptedVendor()
	{
		$criteria			 = new CDbCriteria();
		$criteria->select	 = ['bvr_booking_id', ' MAX(bvr_vendor_score) bvr_top_vendor_score', 'bvr_vendor_id'];
		$criteria->join		 = "JOIN  booking  bkg ON `t`.bvr_booking_id = bkg.bkg_id ";
		$criteria->addCondition('bkg_create_date > DATE_SUB(NOW(), INTERVAL 11160 MINUTE)');
		$criteria->addCondition("bkg_status = 2 ");
		$criteria->addCondition("bvr_accepted = 1 ");
		$criteria->addCondition("bvr_assigned = 0 ");
		$criteria->group	 = 'bvr_booking_id';
		$criteria->order	 = 'bkg_create_date DESC';
		$models				 = $this->findAll($criteria);

		return $models;
	}

	public function getRequestedListNew($vendorId, $sort = '', $page_no = 0, $total_count = 0, $search_txt = '')
	{
		Logger::setModelCategory(__CLASS__, __FUNCTION__);

		$row = AccountTransDetails::getTotTransByVndId($vendorId);

		$totTrans		 = ($row['totTrans'] > 0) ? $row['totTrans'] : 0;
		$vndIsFreeze	 = ($row['vnd_is_freeze'] > 0) ? $row['vnd_is_freeze'] : 0;
		$vndCodFreeze	 = ($row['vnd_cod_freeze'] > 0) ? $row['vnd_cod_freeze'] : 0;
		$offset			 = $page_no * 100;
		if ($sort == 'pk')
		{
			$s1 = "pickupDate ASC";
		}
		else if ($sort == 'nk')
		{
			$s1 = "bvr_created_at DESC, bcb_id DESC";
		}
		else
		{
			$s1 = "pickupDate ASC";
		}

		$search_qry = '';
		if ($search_txt != '')
		{
			$search_qry = " HAVING
                            (
                                cab_model LIKE '%$search_txt%'
                                OR bkg_bcb_id LIKE '%$search_txt%'
                                OR bkg_id LIKE '%$search_txt%'
                                OR bkg_booking_id LIKE '%$search_txt%'
                                OR routes LIKE '%$search_txt%'

                            )";
		}
		$qry = "SET STATEMENT max_statement_time=10 FOR SELECT *,
                  (
                    CASE payment_due WHEN '1' THEN CONCAT(
                        'Your amount due is ',
                        ABS(totTrans),
                        '. Please send payment immediately'
                    ) WHEN '2' THEN 'Your Gozo Account is temporarily frozen. Please contact your Account Manager or Gozo Team to have it resolved.' WHEN '0' THEN ''
                    END
                    ) AS payment_msg,
					IF(bkg_flexxi_type IN(1,2),true,false) isFlexxi
                    FROM
                    (
                        SELECT DISTINCT
                        booking.bkg_id,
						count(*) tot,
                        booking.bkg_booking_id,
                        booking_cab.bcb_id,
                        booking.bkg_pickup_date,
                        $totTrans as totTrans,
                        IF(
                        booking_invoice.bkg_advance_amount >=(booking_invoice.bkg_total_amount * 0.3),
                            1,
                            0
                        ) AS is_discount,
                        booking_invoice.bkg_advance_amount,
                        (booking_invoice.bkg_total_amount * 0.3) AS bkg_min_advance_amount,
                        booking_vendor_request.bvr_id,
                        booking_vendor_request.bvr_bid_amount,
                        booking.bkg_drop_address,
                        booking.bkg_pickup_address,
                        ROUND(
                            booking_cab.bcb_vendor_amount * 0.98
                        ) AS recommended_vendor_amount,
                        booking_cab.bcb_vendor_amount AS max_bid_amount,
                        (
                            booking_cab.bcb_vendor_amount * 0.7
                        ) AS min_bid_amount,
                        booking_invoice.bkg_total_amount,
                        booking.bkg_instruction_to_driver_vendor,
                        booking_vendor_request.bvr_accepted,
                        IF(booking_cab.bcb_trip_type=1,11,booking.bkg_booking_type) as bkg_booking_type,
                        booking.bkg_trip_distance,
                        booking.bkg_return_date,
                        date_format(booking.bkg_return_date,'%T') as bkg_return_time,
                        booking_invoice.bkg_is_toll_tax_included,
                        booking_invoice.bkg_is_state_tax_included,
                        booking.bkg_reconfirm_flag as bkg_reconfirm_id,
                        booking_invoice.bkg_driver_allowance_amount,
                        booking_invoice.bkg_parking_charge,
						booking_invoice.bkg_night_pickup_included,
						booking_invoice.bkg_night_drop_included,
						booking.bkg_flexxi_type,
                        IF(booking.bkg_agent_id > 0, 1, 0) AS is_agent,
                        (
                            CASE WHEN(
                                ($vndIsFreeze = 1) AND ($totTrans > 0) AND booking_invoice.bkg_advance_amount <=(booking_invoice.bkg_total_amount * 0.3)
                            ) THEN '1' WHEN($vndIsFreeze = 2) THEN '2' ELSE '0'
                        END
                        ) AS payment_due,
                        IF(
                            DATE_ADD(NOW(), INTERVAL 13 HOUR) >= booking.bkg_pickup_date,
                            0,
                            1) AS is_biddable,
                            (
                                vehicle_category.vct_label
                            END
                        ) AS cab_model,
                        IF(
                            vehicle_category.vct_id IN(5, 6),
                            1,
                            0
                        ) AS is_assured,
                        MAX(
                            booking.bkg_pickup_date
                        ) AS pickupDate,
                        trip_completion_time, routes
                        FROM  `booking_vendor_request`
                        INNER JOIN `booking_cab` ON booking_vendor_request.bvr_bcb_id = booking_cab.bcb_id AND booking_cab.bcb_active = 1
                        INNER JOIN `booking` ON booking.bkg_bcb_id = booking_cab.bcb_id AND booking.bkg_active = 1 AND booking.bkg_status = 2
                        INNER JOIN `booking_invoice` ON booking.bkg_id = booking_invoice.biv_bkg_id 
                        INNER JOIN
                        (
                            SELECT booking_route.brt_bkg_id, booking_route.brt_bcb_id,
                            MAX(DATE_ADD(booking_route.brt_pickup_datetime, INTERVAL booking_route.brt_trip_duration MINUTE)) as trip_completion_time,
                            GROUP_CONCAT(CONCAT(c1.cty_name,' - ',c2.cty_name) SEPARATOR ' ,') as routes
                            FROM `booking_route`
                            INNER JOIN `booking` ON booking.bkg_id=booking_route.brt_bkg_id AND booking.bkg_status IN (2)
                            INNER JOIN booking_vendor_request ON bvr_bcb_id=bkg_bcb_id AND bvr_vendor_id = $vendorId
                            INNER JOIN `cities` as c1 ON c1.cty_id = booking_route.brt_from_city_id
                            INNER JOIN `cities` as c2 ON c2.cty_id = booking_route.brt_to_city_id
                            WHERE 1 AND booking_route.brt_active=1
                            GROUP BY booking_route.brt_bcb_id
                        ) broute ON broute.brt_bcb_id=booking.bkg_bcb_id
						INNER JOIN svc_class_vhc_cat scv ON scv.scv_id = booking.bkg_vehicle_type_id
						INNER JOIN vehicle_category ON vehicle_category.vct_id = scv.scv_vct_id
                        WHERE booking_vendor_request.bvr_vendor_id = $vendorId
                        AND booking_vendor_request.bvr_assigned = 0
                        AND booking_vendor_request.bvr_active = 1
                        GROUP BY
                            booking.bkg_bcb_id $search_qry
                        ORDER BY " . $s1 . "
                    ) a";
		$qry .= ($total_count == 0) ? " LIMIT 100 OFFSET $offset" : " ";

		$recordset = DBUtil::queryAll($qry, DBUtil::SDB());

		foreach ($recordset as $key => $val)
		{
			if ($val['tot'] > 1)
			{
				$recordset[$key]['bkg_route_name'] = BookingRoute::model()->getRouteNameByBcb($val['bcb_id']);
			}
			else
			{
				$recordset[$key]['bkg_route_name'] = BookingRoute::model()->getRouteName($val['bkg_id']);
			}
			if ($recordset[$key]['bkg_route_name'] == '')
			{
				$recordset[$key]['bkg_route_name'] = BookingRoute::model()->getRouteName($val['bkg_id']);
			}

			$recordset[$key]['bkg_driver_allowance_amount']	 = (int) $val['bkg_driver_allowance_amount'];
			$recordset[$key]['bkg_parking_charge']			 = (int) $val['bkg_parking_charge'];
		}
		Logger::unsetModelCategory(__CLASS__, __FUNCTION__);
		return $recordset;
	}

	/**
	 * @deprecated since version 10-10-2019
	 * @author ramala
	 */
	public function getRequestedList1($vendorId, $sort = '')
	{
		if ($sort == 'pk')
		{
			$s1 = "bcb.bcb_id, bkg.bkg_pickup_date";
		}
		else if ($sort == 'nk')
		{
			$s1 = "bvr_created_at DESC, bcb.bcb_id DESC";
		}
		else
		{
			$s1 = "bcb.bcb_id, bkg.bkg_pickup_date";
		}
		$qry		 = "SELECT DISTINCT(bkg.bkg_id), bcb.bcb_id, vht_model as cab_type, bkg_drop_address, bkg_pickup_address, bkg_pickup_date, bcb_vendor_amount, bkg_total_amount, bkg_instruction_to_driver_vendor, bvr_accepted, bkg_booking_type, bkg_trip_distance, bkg_return_date, bkg_return_time, bkg_is_toll_tax_included, bkg_is_state_tax_included, bkg_reconfirm_flag, concat(CASE vht_car_type WHEN 1 then 'COMPACT' WHEN 2 THEN 'SUV' When 3 then 'SEDAN' When 4 then 'Tempo Traveller' ELSE '' END) cab_model FROM booking_vendor_request
            LEFT JOIN booking_cab bcb on bcb_id = bvr_bcb_id
            LEFT JOIN booking bkg ON bcb.bcb_id=bkg.bkg_bcb_id
            LEFT JOIN vehicle_types on vht_id = bkg_vehicle_type_id
            WHERE bvr_vendor_id = $vendorId AND bkg_status = 2 AND bkg_active = 1 AND bvr_accepted = 0 AND bvr_active = 1
            GROUP BY bkg.bkg_id ORDER BY " . $s1;
		$recordset	 = DBUtil::queryAll($qry);
		foreach ($recordset as $key => $val)
		{
			foreach ($val as $k => $v)
			{
				if ($k == 'bkg_id')
				{
					$recordset[$key]['bkg_route_name'] = BookingRoute::model()->getRouteName($v);
				}
			}
		}
		return $recordset;
	}

	public function findByBookingIdAndVendorId($bkgid, $vendorid)
	{

		$criteria = new CDbCriteria;
		$criteria->compare('bvr_booking_id', $bkgid);
		$criteria->compare('bvr_vendor_id', $vendorid);
		return $this->find($criteria);
	}

	public function findByBcbIdAndVendorId($bcbid, $vendorid)
	{
		$criteria = new CDbCriteria;
		$criteria->compare('bvr_bcb_id', $bcbid);
		$criteria->compare('bvr_vendor_id', $vendorid);
		return $this->findAll($criteria);
	}

	public function assignVendor($bcb_id, $vendor_id)
	{
		$criteria				 = new CDbCriteria();
		$criteria->compare('bvr_bcb_id', $bcb_id);
		$criteria->compare('bvr_vendor_id', $vendor_id);
		$model					 = $this->find($criteria);
		$model->bvr_assigned	 = 1;
		$model->bvr_assigned_at	 = new CDbExpression('NOW()');
		$model->save();
		return TRUE;
	}

	public function getVendorListByBooking($bkg_id)
	{
		$criteria				 = new CDbCriteria();
		$criteria->compare('bvr_booking_id', $bkg_id);
		$criteria->compare('bvr_vendor_id', $vendor_id);
		$model					 = $this->find($criteria);
		$model->bvr_assigned	 = 1;
		$model->bvr_assigned_at	 = new CDbExpression('NOW()');
		return $model->save();
	}

	public function updateListByBooking($bkg_id)
	{

		$qry = "UPDATE booking_vendor_request SET bvr_active = 0 WHERE bvr_booking_id = $bkg_id";
		$row = DBUtil::command($qry)->execute();
		return $row;
	}

	public function updateListByBcb($bcb_id)
	{

		$qry	 = "UPDATE booking_vendor_request SET bvr_active = 0 WHERE bvr_bcb_id = $bcb_id";
		$numRows = DBUtil::command($qry)->execute();
		return $numRows;
	}

	public function getNotifiedVendorIdsbyBookingId($bkgid)
	{
		$bkgModel	 = Booking::model()->findByPk($bkgid);
		$tripId		 = $bkgModel->bkg_bcb_id;
		$sql		 = "SELECT GROUP_CONCAT(bvr_vendor_id) as bvr_vendor_ids FROM
				`booking_vendor_request`
				WHERE `bvr_bcb_id` = '$tripId'
				AND ((`bvr_accepted` >= 0 AND bvr_active = 1) OR (`bvr_assigned` = 1 AND bvr_active = 0))";
		$cdb		 = DBUtil::command($sql);
		$model		 = $cdb->queryRow();
		return $model['bvr_vendor_ids'];
	}

	public function setBidAmount($bvr_id, $bid_amount)
	{
		$model					 = BookingVendorRequest::model()->findByPk($bvr_id);
		$model->bvr_bid_amount	 = $bid_amount;
		$model->bvr_accepted	 = 1;
		$model->bvr_accepted_at	 = new CDbExpression('NOW()');
		$model->scenario		 = 'setbid';
		$success				 = $model->save();
		return $success;
	}

	public function autoAssignVendor()
	{
		$result		 = '';
		$recordsets	 = BookingVendorRequest::model()->getAutoAssignData();
		if (count($recordsets) >= 1)
		{
			$bcb_id = 0;
			foreach ($recordsets as $recordset)
			{
				if ($bcb_id != $recordset['bkg_bcb_id'])
				{
					$cabModel	 = BookingCab::model()->findByPk($recordset['bkg_bcb_id']);
					$userInfo	 = UserInfo::model();
					$remark		 = 'Auto Assigned';
					$assignMode	 = 0;
					$return		 = $cabModel->assignVendor($recordset['bkg_bcb_id'], $recordset['bvr_vendor_id'], $recordset['bvr_bid_amount'], $remark, $userInfo, $assignMode);
					if ($return->isSuccess())
					{
						$bvrModels = BookingVendorRequest::model()->findByBcbIdAndVendorId($recordset['bkg_bcb_id'], $recordset['bvr_vendor_id']);
						if (count($bvrModels) >= 1)
						{
							foreach ($bvrModels as $bvrModel)
							{
								$bvrModel->bvr_assigned		 = 1;
								$bvrModel->bvr_assigned_at	 = new CDbExpression('NOW()');
								$bvrModel->bvr_active		 = 0;
								$success					 = $bvrModel->save();
							}
							if ($success)
							{
								$row = BookingVendorRequest::model()->updateListByBcb($recordset['bkg_bcb_id']);
							}
						}
						$result .= Vendors::model()->getVendorById($recordset['bvr_vendor_id']) . " assigned to Trip ID " . $recordset['bkg_bcb_id'] . ", ";
					}
				}
				$bcb_id = $recordset['bkg_bcb_id'];
			}
		}
		return $result;
	}

	/**
	 * Function for Archive Booking Vendor Request Data
	 * @param $archiveDB
	 */
	public function archiveData($archiveDB)
	{
		$transaction = null;
		try
		{
			$i			 = 0;
			$chk		 = true;
			$totRecords	 = 1500000;
			$limit		 = 1000;

			while ($chk)
			{
				// BVR move all more than 6 months
				// BVR Active=0, Assigned=0, Accepted=0
				$transaction = Yii::app()->db->beginTransaction();

				$sql	 = "SELECT GROUP_CONCAT(bvr_id) as bvr_id FROM (
							SELECT bvr_id FROM `booking_vendor_request` 
							WHERE bvr_active = 0 AND bvr_accepted = 0 AND bvr_assigned = 0 AND (bvr_created_at < DATE(DATE_SUB(NOW(), INTERVAL 15 DAY))) 
							ORDER BY bvr_id LIMIT 0, $limit 
							) as tmp";
				$resQ	 = DBUtil::command($sql)->queryScalar();
				if (!is_null($resQ) && $resQ != '')
				{
					$sql	 = "INSERT INTO " . $archiveDB . ".`booking_vendor_request` (SELECT * FROM `booking_vendor_request` WHERE bvr_id IN ($resQ))";
					$rows	 = DBUtil::command($sql)->execute();

					if ($rows > 0)
					{
						$sql	 = "DELETE FROM `booking_vendor_request` WHERE bvr_id IN ($resQ)";
						$rowsDel = DBUtil::command($sql)->execute();
					}
				}
				$transaction->commit();

				// Bid Amount=0, Assigned=0
				$transaction = Yii::app()->db->beginTransaction();

				$sql	 = "SELECT GROUP_CONCAT(bvr_id) as bvr_id FROM (
							SELECT bvr_id FROM `booking_vendor_request` 
							WHERE bvr_bid_amount = 0 AND bvr_assigned = 0 AND (bvr_created_at < DATE(DATE_SUB(NOW(), INTERVAL 3 MONTH))) 
							ORDER BY bvr_id LIMIT 0, $limit 
							) as tmp";
				$resB	 = DBUtil::command($sql)->queryScalar();
				if (!is_null($resB) && $resB != '')
				{
					$sql	 = "INSERT INTO " . $archiveDB . ".`booking_vendor_request` (SELECT * FROM `booking_vendor_request` WHERE bvr_id IN ($resB))";
					$rows	 = DBUtil::command($sql)->execute();

					if ($rows > 0)
					{
						$sql	 = "DELETE FROM `booking_vendor_request` WHERE bvr_id IN ($resB)";
						$rowsDel = DBUtil::command($sql)->execute();
					}
				}
				$transaction->commit();

				// More than 365 day
				$transaction = Yii::app()->db->beginTransaction();

				$sql	 = "SELECT GROUP_CONCAT(bvr_id) as bvr_id FROM (
								SELECT bvr_id FROM `booking_vendor_request` 
								WHERE 1 AND bvr_created_at < '2022-04-01 00:00:00' 
								ORDER BY bvr_id LIMIT 0, $limit 
							) as tmp";
				$resA	 = DBUtil::command($sql)->queryScalar();
				if (!is_null($resA) && $resA != '')
				{
					$sql	 = "INSERT IGNORE INTO " . $archiveDB . ".`booking_vendor_request` (SELECT * FROM `booking_vendor_request` WHERE bvr_id IN ($resA))";
					$rows	 = DBUtil::command($sql)->execute();

					if ($rows > 0)
					{
						$sql	 = "DELETE FROM `booking_vendor_request` WHERE bvr_id IN ($resA)";
						$rowsDel = DBUtil::command($sql)->execute();
					}
				}
				$transaction->commit();

				$i += $limit;
				if (($resQ <= 0 && $resB <= 0 && $resA <= 0) || $totRecords <= $i)
				{
					break;
				}
			}
		}
		catch (Exception $e)
		{
			echo $e->getMessage();
			echo "\r\n";
			$transaction->rollback();
		}
	}

	public function getVendorIdAutoAssigned($vendor_amount, $bcb_id, $maxVendorAmount, $maxLossVendorAmount, $customerDue)
	{
		$result = $this->getTopVendor($vendor_amount, $bcb_id, $vendor_amount, $customerDue);
		if ($result)
		{
			goto result;
		}

		$result = $this->getTopVendor($vendor_amount, $bcb_id, $maxVendorAmount, $customerDue);
		if ($result)
		{
			goto result;
		}


		$result = $this->getTopVendor($vendor_amount, $bcb_id, $maxVendorAmount, $customerDue, '3.5');
		if ($result)
		{
			goto result;
		}

		$result = $this->getTopVendor($vendor_amount, $bcb_id, $maxLossVendorAmount, $customerDue);
		if ($result)
		{
			goto result;
		}

		$result = $this->getTopVendor($vendor_amount, $bcb_id, $maxLossVendorAmount, $customerDue, '3.5');
		if ($result)
		{
			goto result;
		}

		result:
		return $result;
	}

	public function getTopVendors($maxVendorAmount, $bcb_id, $allowedVendorAmount, $customerDue, $rating = '4', $totalAdvance = null)
	{
		if ($totalAdvance !== null)
		{
			$cond = " AND (vnp_cod_freeze=0 OR $totalAdvance>0)";
		}

		$sql4 = "SELECT bvr_id,bvr_vendor_id, bvr_bid_amount, bvr_bcb_id, bvr_booking_id,
                    CalculateSMT((bcb_vendor_amount + SUM(bkg_gozo_amount - LEAST(IFNULL(bkg_credits_used,0), ROUND(0.15 * bkg_net_base_amount)))), $maxVendorAmount, bvr_bid_amount, vrs_vnd_overall_rating, vrs_sticky_score,vrs_penalty_count,vrs_driver_app_used,vrs_dependency,vrs_boost_percentage) as bestBidRank,
                    (bcb_vendor_amount + SUM(bkg_gozo_amount - LEAST(IFNULL(bkg_credits_used,0), ROUND(0.15 * bkg_net_base_amount)))) as ZeroProfitVA,
                    MAX((bkg_manual_assignment+bkg_critical_assignment)) as criticalityFlag
                FROM booking_vendor_request
                INNER JOIN vendors ON vnd_id=bvr_vendor_id AND vnd_active=1
                INNER JOIN vendor_stats ON vnd_id=vrs_vnd_id
                INNER JOIN booking_cab ON bcb_id=bvr_bcb_id
                INNER JOIN booking ON bcb_id=bkg_bcb_id
                INNER JOIN booking_pref ON bpr_bkg_id=bkg_id
                INNER JOIN booking_invoice ON biv_bkg_id=bkg_id
                INNER JOIN vendor_pref ON vnp_vnd_id=vnd_id 
                                AND (
                                    (vendor_pref.vnp_low_rating_freeze =0 AND vendor_pref.vnp_doc_pending_freeze=0 AND vendor_pref.vnp_manual_freeze=0)
                                    AND (bvr_bid_amount>IF(vnp_credit_limit_freeze=1 OR vnp_cod_freeze=1, $customerDue,0) $cond)
                            )
                WHERE bvr_bcb_id=$bcb_id AND bvr_accepted=1 AND bvr_bid_amount>0 AND bvr_bid_amount<=$maxVendorAmount AND bvr_active =1
                    AND (vrs_dependency>0  
                         OR (bkg_critical_score>0.74 AND vrs_dependency>-100) 
                         OR (bkg_critical_score>0.88 AND vrs_dependency>-300) 
                         OR (bkg_manual_assignment=1 AND vrs_dependency>-150) 
                         OR bkg_critical_assignment=1)
                GROUP BY bvr_vendor_id
                HAVING (bestBidRank>=0 OR criticalityFlag>0)
                ORDER BY bestBidRank DESC, bvr_bid_amount ASC, bvr_vendor_rating DESC";

//      Logger::writeToConsole($sql4);

		$result = DBUtil::query($sql4);

		return $result;
	}

	public function getTopVendor($maxVendorAmount, $bcb_id, $allowedVendorAmount, $customerDue, $rating = '4', $totalAdvance = null)
	{
		$data	 = false;
		$result	 = $this->getTopVendors($maxVendorAmount, $bcb_id, $allowedVendorAmount, $customerDue, $rating, $totalAdvance);
		foreach ($result as $row)
		{
			$data = $row;
			break;
		}
		return $data;
	}

	public function getTopBidVendor($maxVendorAmount, $bcb_id, $allowedVendorAmount, $customerDue, $rating = '4')
	{
		$sql4 = "SELECT bvr_id,bvr_vendor_id, bvr_bid_amount, bvr_bcb_id, bvr_booking_id,
				    CalculateSMT((bcb_vendor_amount + SUM(bkg_gozo_amount - LEAST(IFNULL(bkg_credits_used,0), ROUND(0.15 * bkg_net_base_amount)))), $maxVendorAmount, bvr_bid_amount, vrs_vnd_overall_rating, vrs_sticky_score,vrs_penalty_count,vrs_driver_app_used,vrs_dependency,vrs_boost_percentage) as bestBidRank,
					(bcb_vendor_amount + SUM(bkg_gozo_amount - LEAST(IFNULL(bkg_credits_used,0), ROUND(0.15 * bkg_net_base_amount)))) as ZeroProfitVA
				FROM booking_vendor_request
				INNER JOIN vendors ON vnd_id=bvr_vendor_id AND vnd_active=1
				INNER JOIN vendor_stats ON vnd_id=vrs_vnd_id
				INNER JOIN booking_cab ON bcb_id=bvr_bcb_id
				INNER JOIN booking ON bcb_id=bkg_bcb_id
				INNER JOIN booking_invoice ON biv_bkg_id=bkg_id
				INNER JOIN vendor_pref ON vnp_vnd_id=vnd_id AND ((vendor_pref.vnp_low_rating_freeze =0 AND vendor_pref.vnp_doc_pending_freeze=0 AND vendor_pref.vnp_manual_freeze=0) OR (bvr_bid_amount>$customerDue AND vnp_credit_limit_freeze=1) OR (vnp_cod_freeze=1 AND $customerDue=0) OR vnp_is_freeze=0)
				WHERE bvr_bcb_id=$bcb_id AND bvr_accepted=1 AND bvr_bid_amount>0 AND bvr_bid_amount<$maxVendorAmount GROUP BY bvr_vendor_id HAVING bestBidRank>=0
				ORDER BY bvr_bid_amount ASC, bvr_vendor_rating DESC";

		$result = DBUtil::queryRow($sql4);

		return $result;
	}

	public function getAutoAssignData()
	{
		/* DSA: now subtracting the credits used here. For auto-assignment we should take bkg_credits_used out of gozoamount  */
		$sql = "SELECT  bkg_total_amount,bkg_service_tax,bkg_base_amount,agt_type,agt_commission,bvr.bvr_booking_id as bkgID,bcb_id, COUNT(DISTINCT bvr.bvr_vendor_id) as cntAvailable, SUM(IF(bvr.bvr_bid_amount>0, 1, 0)) as cntBid,
						SUM(IF((bcb_vendor_amount+gozoAmount)>bvr_bid_amount AND bvr.bvr_bid_amount>0,1,0)) as profitableBid, 
						MIN(IF(bvr.bvr_bid_amount>0,bvr_bid_amount, null)) as MinBid, customerDue,
						MAX(bvr.bvr_bid_amount) as MaxBid, a.bcb_vendor_amount, a.gozoAmount,createDiff,pickupDiff, HOUR(NOW()) as currentHour
						FROM booking_vendor_request bvr
						INNER JOIN vendors ON bvr.bvr_vendor_id = vendors.vnd_id AND  vnd_active = 1 
                        INNER JOIN vendor_pref ON vendors.vnd_id = vendor_pref.vnp_id AND vendor_pref.vnp_low_rating_freeze =0 AND vendor_pref.vnp_doc_pending_freeze=0 AND vendor_pref.vnp_manual_freeze=0
						INNER JOIN 
						(
								 SELECT bkg_total_amount,bkg_service_tax,bkg_base_amount,agt_type,agt_commission,bcb_id, count(*) as cnt, 
								 SUM(bkg_total_amount - bkg_service_tax) as GMV, SUM(bkg_total_amount - bkg_advance_amount + bkg_refund_amount - bkg_credits_used) as customerDue,
								 (SUM(bkg_total_amount - bkg_service_tax - IF(agents.agt_type=2,ROUND(bkg_base_amount*agents.agt_commission*0.01),0)) - booking_cab.bcb_vendor_amount - bkg_credits_used) as gozoAmount,  
								 MIN(booking.bkg_pickup_date) as pickupDate, MAX(bkg_create_date) as createDate,  SUM(bkg_total_amount) as totalAmount, bcb_vendor_amount,
								 TIMESTAMPDIFF(HOUR, MAX(bkg_create_date), MIN(booking.bkg_pickup_date)) as createDiff,
								 TIMESTAMPDIFF(HOUR,NOW(),MIN(booking.bkg_pickup_date)) as pickupDiff
								 FROM booking_cab 
								 INNER JOIN booking ON booking_cab.bcb_id=booking.bkg_bcb_id AND bkg_status IN (2) AND bkg_reconfirm_flag = 1 
								 AND booking.bkg_pickup_date<DATE_ADD(NOW(), INTERVAL 7 DAY)
								 INNER JOIN booking_invoice ON booking.bkg_id=booking_invoice.biv_bkg_id
								 INNER JOIN booking_pref ON bpr_bkg_id=bkg_id AND bkg_block_autoassignment=0
								 LEFT JOIN booking_smartmatch ON (bsm_upbooking_id = bkg_id OR bsm_downbooking_id = bkg_id)
								 LEFT JOIN agents ON bkg_agent_id = agents.agt_id AND agents.agt_vendor_autoassign_flag = 1
                                 WHERE bsm_id IS NULL AND (agents.agt_id IS NOT NULL OR booking.bkg_agent_id IS NULL)
								 GROUP BY bcb_id 
								 HAVING 
								  ((createDiff>120 AND pickupDiff<72) OR
								  (createDiff > 96 AND pickupDiff<64) OR
								  (createDiff > 72 AND pickupDiff<48) OR
								  (createDiff > 48 AND pickupDiff<30) OR
								  (createDiff > 36 AND pickupDiff<24) OR
								  (createDiff > 20 AND pickupDiff<16) OR
								  (createDiff > 16 AND pickupDiff<14) OR
								  (createDiff > 10 AND pickupDiff<9))
					  ) a 
					  ON bvr.bvr_bcb_id=a.bcb_id AND bvr.bvr_active=1 AND bvr.bvr_bid_amount>0 AND bvr_accepted=1 AND bvr_assigned =0
					  GROUP BY bcb_id 
					  HAVING 
					     ((createDiff>120 AND pickupDiff<72 AND (profitableBid>=1 or cntBid>5)) OR
						  (createDiff>120 AND pickupDiff<60) OR
						  (createDiff > 96 AND pickupDiff<64  AND (profitableBid>=1 or cntBid>5)) OR
						  (createDiff > 96 AND pickupDiff<48) OR
						  (createDiff > 72 AND pickupDiff<48  AND (profitableBid>=1 or cntBid>5)) OR
						  (createDiff > 72 AND pickupDiff<36) OR
						  (createDiff > 48 AND pickupDiff<30  AND (profitableBid>=1 or cntBid>5)) OR
						  (createDiff > 48 AND pickupDiff<24) OR
						  (createDiff > 36 AND pickupDiff<26  AND (profitableBid>=1 or cntBid>5)) OR
						  (createDiff > 36 AND pickupDiff<20) OR
						  (createDiff > 20 AND pickupDiff<22  AND (profitableBid>=1 or cntBid>5)) OR
						  (createDiff > 20 AND pickupDiff<14) OR
						  (createDiff >= 2 AND pickupDiff<12 AND (currentHour>22 OR currentHour<3)  AND (profitableBid>=1)) OR
						  (createDiff >= 2 AND pickupDiff<8  AND (profitableBid>=1)) OR
						  (createDiff >= 1 AND pickupDiff<12 AND (currentHour>22)  AND (profitableBid>=1 or cntBid>5)) OR
						  (createDiff >= 1 AND pickupDiff<10 AND (currentHour<3)  AND (profitableBid>=1 or cntBid>5)) OR
						  (createDiff >= 1 AND pickupDiff<8  AND (profitableBid>=1 or cntBid>5)) OR
						  (createDiff >= 0 AND pickupDiff<6  AND (profitableBid>=1 or cntBid>5)) OR
						  (createDiff >= 0 AND pickupDiff<3  AND (profitableBid>=1 or cntBid>3)) OR
						  (createDiff > 12 AND pickupDiff<12) OR
						  (createDiff > 8 AND pickupDiff<8) OR
						  (createDiff > 2 AND pickupDiff<6) OR
						  (createDiff >= 1 AND pickupDiff<3))";

		$recordsets = DBUtil::queryAll($sql);
		return $recordsets;
	}

	public function getAutoAssignMatchData()
	{
		$sql		 = "SELECT
				bvr_id,
				bsm_id,
				bcb_id,
				bvr_vendor_id,
				SUM(IF(bvr_bid_amount > 0, 1, 0)) AS cntBid,
				up.bkg_id upbkg,
				down.bkg_id downbkg,
				HOUR(NOW()) AS currentHour,
				((bivUp.bkg_total_amount + bivDown.bkg_total_amount) - (bivUp.bkg_service_tax + bivDown.bkg_service_tax)
				 - (IF(upAgt.agt_type = 2, ROUND(bivUp.bkg_base_amount * upAgt.agt_commission * 0.01), 0) 
				 + IF(downAgt.agt_type = 2, ROUND(bivDown.bkg_base_amount * downAgt.agt_commission * 0.01), 0))) total,
				 bvr_bid_amount,
				IF((bcb_vendor_amount + ((bivUp.bkg_total_amount + bivDown.bkg_total_amount - bivUp.bkg_service_tax - bivDown.bkg_service_tax - IF(upAgt.agt_type = 2, ROUND(bivUp.bkg_base_amount * upAgt.agt_commission * 0.01), 0)- IF(downAgt.agt_type = 2, ROUND(bivDown.bkg_base_amount * downAgt.agt_commission * 0.01), 0)) - bcb_vendor_amount)) > bvr_bid_amount AND bvr_bid_amount > 0, 1, 0) AS profitableBid,
				TIMESTAMPDIFF(HOUR, IF(TIMESTAMPDIFF(SECOND, up.bkg_create_date, down.bkg_create_date) > 0, down.bkg_create_date, up.bkg_create_date), IF(TIMESTAMPDIFF(MINUTE, up.bkg_pickup_date, down.bkg_pickup_date) > 0, up.bkg_pickup_date, down.bkg_pickup_date)) AS createDiff,
				TIMESTAMPDIFF(HOUR, NOW(), IF(TIMESTAMPDIFF(MINUTE, up.bkg_pickup_date, down.bkg_pickup_date) > 0, up.bkg_pickup_date, down.bkg_pickup_date)) AS pickupDiff
				 FROM   booking_cab  
						INNER JOIN booking_smartmatch ON bsm_bcb_id = bcb_id AND bcb_active = 1 AND bcb_trip_type = 1 AND bsm_active = 1 AND bsm_ismatched = 0
						INNER JOIN booking up ON up.bkg_id = bsm_upbooking_id AND up.bkg_status = 2 AND up.bkg_reconfirm_flag = 1
						INNER JOIN booking_pref upPref ON upPref.bpr_bkg_id = up.bkg_id AND upPref.bkg_block_autoassignment = 0
						INNER JOIN booking_invoice bivUp ON bivUp.biv_bkg_id = up.bkg_id
						INNER JOIN booking down ON down.bkg_id = bsm_downbooking_id AND down.bkg_status = 2 AND down.bkg_reconfirm_flag = 1
						INNER JOIN booking_pref downPref ON downPref.bpr_bkg_id = down.bkg_id AND downPref.bkg_block_autoassignment = 0
						INNER JOIN booking_invoice bivDown ON bivDown.biv_bkg_id = down.bkg_id
						INNER JOIN booking_vendor_request ON bvr_bcb_id = bcb_id AND bvr_active = 1 AND bvr_bid_amount > 0 AND bvr_accepted = 1 AND bvr_assigned = 0
						LEFT JOIN agents upAgt ON up.bkg_agent_id = upAgt.agt_id AND upAgt.agt_vendor_autoassign_flag = 1
  				        LEFT JOIN agents downAgt ON down.bkg_agent_id = downAgt.agt_id AND downAgt.agt_vendor_autoassign_flag = 1
                        WHERE (upAgt.agt_id IS NOT NULL OR up.bkg_agent_id IS NULL) AND (downAgt.agt_id IS NOT NULL OR down.bkg_agent_id IS NULL)
						GROUP BY up.bkg_id
				  HAVING   ((createDiff > 120 AND pickupDiff < 72 AND (profitableBid >= 1 OR cntBid > 5)) OR (createDiff > 120 AND pickupDiff < 60)
												   OR (createDiff > 96 AND pickupDiff < 64 AND (profitableBid >= 1 OR cntBid > 5)) OR (createDiff > 96 AND pickupDiff < 48)
												   OR (createDiff > 72 AND pickupDiff < 48 AND (profitableBid >= 1 OR cntBid > 5)) OR (createDiff > 72 AND pickupDiff < 36)
												   OR (createDiff > 48 AND pickupDiff < 30 AND (profitableBid >= 1 OR cntBid > 5)) OR (createDiff > 48 AND pickupDiff < 24)
												   OR (createDiff > 36 AND pickupDiff < 26 AND (profitableBid >= 1 OR cntBid > 5)) OR (createDiff > 36 AND pickupDiff < 20)
												   OR (createDiff > 20 AND pickupDiff < 22 AND (profitableBid >= 1 OR cntBid > 5)) OR (createDiff > 20 AND pickupDiff < 14)
												   OR (createDiff >= 2 AND pickupDiff < 12 AND (currentHour > 22 OR currentHour < 3) AND (profitableBid >= 1)) OR (
												   createDiff >= 2 AND pickupDiff < 8 AND (profitableBid >= 1)) OR (createDiff >= 1 AND pickupDiff < 12 AND (currentHour >
												   22) AND (profitableBid >= 1 OR cntBid > 5)) OR (createDiff >= 1 AND pickupDiff < 10 AND (currentHour < 3) AND (
												   profitableBid >= 1 OR cntBid > 5)) OR (createDiff >= 1 AND pickupDiff < 8 AND (profitableBid >= 1 OR cntBid > 5)) OR (
												   createDiff >= 0 AND pickupDiff < 6 AND (profitableBid >= 1 OR cntBid > 5)) OR (createDiff >= 0 AND pickupDiff < 3 AND (
												   profitableBid >= 1 OR cntBid > 3)) OR (createDiff > 12 AND pickupDiff < 12) OR (createDiff > 8 AND pickupDiff < 8) OR (
												   createDiff > 2 AND pickupDiff < 6) OR (createDiff >= 1 AND pickupDiff < 3)) 
          ";
		$recordsets	 = DBUtil::queryAll($sql);
		return $recordsets;
	}

	public function getTopMatch($upBkgId, $downBkgId)
	{
		$normalBestBidRecord = $this->getNormalBookingTopVendor($upBkgId, $downBkgId);
		$sql				 = "SELECT
						topData.*,
						maxLossVendorAmount
						FROM 
						(SELECT 
						bvr_id,
						bsm_id,
						bvr_bcb_id,
						vnp_is_freeze,
						bsm_upbooking_id,
						bsm_downbooking_id,
						bvr_vendor_id,
						bvr_vendor_score,
						bvr_vendor_rating,
						bsm_margin_matched,
						bsm_margin_original,
						ROUND((bsm_vendor_amt_matched + ((bivUp.bkg_total_amount + bivDown.bkg_total_amount - bivUp.bkg_service_tax - bivDown.bkg_service_tax - IF(upAgt.agt_type = 2, ROUND(bivUp.bkg_base_amount * upAgt.agt_commission * 0.01), 0)- IF(downAgt.agt_type = 2, ROUND(bivDown.bkg_base_amount * downAgt.agt_commission * 0.01), 0)) - bsm_vendor_amt_matched)) * 0.98) maxLossVendorAmount,
						((bivUp.bkg_total_amount + bivDown.bkg_total_amount) - (bivUp.bkg_advance_amount + bivDown.bkg_advance_amount) + (bivUp.bkg_refund_amount + bivDown.bkg_refund_amount) - (bivUp.bkg_credits_used+bivDown.bkg_credits_used) ) customerDue,
						ROUND(bsm_vendor_amt_matched * bvr_vendor_score/bvr_bid_amount,1) as bestBidRank,
						bvr_bid_amount,
						bsm_vendor_amt_matched vendor_amount,
						(bsm_vendor_amt_matched + ((bivUp.bkg_total_amount + bivDown.bkg_total_amount - bivUp.bkg_service_tax - bivDown.bkg_service_tax - IF(upAgt.agt_type = 2, ROUND(bivUp.bkg_base_amount * upAgt.agt_commission * 0.01), 0)- IF(downAgt.agt_type = 2, ROUND(bivDown.bkg_base_amount * downAgt.agt_commission * 0.01), 0)) - bsm_vendor_amt_matched)) maxVendorAmount
						FROM booking_vendor_request
						INNER JOIN booking_smartmatch 
						ON bsm_bcb_id = bvr_bcb_id AND bvr_active = 1 AND bvr_bid_amount > 0 AND bvr_accepted = 1 AND bvr_assigned = 0 AND bsm_active = 1 AND bsm_ismatched = 0
						INNER JOIN booking up ON up.bkg_id = bsm_upbooking_id  AND up.bkg_status = 2
						INNER JOIN booking_invoice bivUp ON up.bkg_id = bivUp.biv_bkg_id
						INNER JOIN booking down ON down.bkg_id = bsm_downbooking_id  AND down.bkg_status = 2
						INNER JOIN booking_invoice bivDown ON down.bkg_id = bivDown.biv_bkg_id
						INNER JOIN vendors ON vnd_id = bvr_vendor_id AND vnd_active = 1
						INNER JOIN vendor_pref ON vnp_vnd_id = vnd_id 
						LEFT JOIN agents upAgt ON up.bkg_agent_id = upAgt.agt_id
						LEFT JOIN agents downAgt ON down.bkg_agent_id = downAgt.agt_id
						WHERE bsm_upbooking_id = $upBkgId) topData
						WHERE vnp_is_freeze = 0 OR (topData.customerDue < topData.bvr_bid_amount AND topData.vnp_is_freeze = 1) 
						AND bvr_bid_amount < ##ALLOWEDVENDORAMT AND (bvr_vendor_rating IS NULL OR bvr_vendor_rating >= ##RATINGVENDOR )
						ORDER BY topData.bestBidRank DESC, topData.bvr_vendor_rating DESC, topData.bvr_bid_amount ASC";
		$sql1				 = str_replace('##ALLOWEDVENDORAMT', 'vendor_amount', $sql);
		$sql1				 = str_replace('##RATINGVENDOR', '4', $sql1);
		$recordsets			 = DBUtil::queryRow($sql1);
		if ($recordsets != false)
		{

			if ($normalBestBidRecord['bestBidRank'] > $recordsets['bestBidRank'])
			{
				return $normalBestBidRecord;
			}
			return $recordsets;
		}
		$sql2		 = str_replace('##ALLOWEDVENDORAMT', 'maxVendorAmount', $sql);
		$sql2		 = str_replace('##RATINGVENDOR', '4', $sql2);
		$recordsets	 = DBUtil::queryRow($sql2);
		if ($recordsets != false)
		{
			if ($normalBestBidRecord['bestBidRank'] > $recordsets['bestBidRank'])
			{
				return $normalBestBidRecord;
			}
			return $recordsets;
		}
		$sql3		 = str_replace('##ALLOWEDVENDORAMT', 'maxVendorAmount', $sql);
		$sql3		 = str_replace('##RATINGVENDOR', '3.5', $sql3);
		$recordsets	 = DBUtil::queryRow($sql3);
		if ($recordsets != false)
		{
			if ($normalBestBidRecord['bestBidRank'] > $recordsets['bestBidRank'])
			{
				return $normalBestBidRecord;
			}
			return $recordsets;
		}

		$sql4		 = str_replace('##ALLOWEDVENDORAMT', 'maxLossVendorAmount', $sql);
		$sql4		 = str_replace('##RATINGVENDOR', '4', $sql4);
		$recordsets	 = DBUtil::queryRow($sql4);
		if ($recordsets != false)
		{
			if ($normalBestBidRecord['bestBidRank'] > $recordsets['bestBidRank'])
			{
				return $normalBestBidRecord;
			}
			return $recordsets;
		}

		$sql5		 = str_replace('##ALLOWEDVENDORAMT', 'maxLossVendorAmount', $sql);
		$sql5		 = str_replace('##RATINGVENDOR', '3.5', $sql5);
		$recordsets	 = DBUtil::command($sql5)->queryRow();
		if ($recordsets != false)
		{
			if ($normalBestBidRecord['bestBidRank'] > $recordsets['bestBidRank'])
			{
				return $normalBestBidRecord;
			}
			return $recordsets;
		}
	}

	public function getMedianBiddingAmount($bcb_id)
	{
		$sql			 = "SELECT ROUND(MEDIAN(`bvr_bid_amount`) OVER (PARTITION BY bvr_bcb_id)) as bidamount FROM `booking_vendor_request` WHERE `bvr_bcb_id`=$bcb_id AND bvr_bid_amount>0";
		$medianAmount	 = DBUtil::command($sql)->queryScalar();
		return $medianAmount;
	}

	public function getBidCountByBkgID($bcbID)
	{
		$sql		 = "SELECT count(DISTINCT bvr_vendor_id) as floated, bvr_bcb_id, SUM(IF(bvr_accepted=1,1,0)) as acceptCnt  FROM `booking_vendor_request` WHERE bvr_bcb_id = $bcbID ";
		$bidCountArr = DBUtil::queryRow($sql);
		return $bidCountArr;
	}

	public function createRequest($bidAmount, $bcbId, $vendorId, $status = 'request')
	{
		$success	 = true;
		$transaction = DBUtil::beginTransaction();
		try
		{

			$result = BookingVendorRequest::model()->checkAlreadyAssignOrNot($vendorId, $bcbId);
			if ($result > 0)
			{
				$model = BookingVendorRequest::model()->findByPk($result);
			}
			else
			{
				$model = new BookingVendorRequest();
			}
			if ($bcbId != '')
			{
				$bookingCabModel = BookingCab::model()->findByPk($bcbId);
			}
			$vendor_amount	 = $bookingCabModel['bcb_vendor_amount'];
			$modelvendor	 = Vendors::model()->findByPk($vendorId);
			$bkgId			 = explode(',', $bookingCabModel->bcb_bkg_id1);
			$initialRating	 = 4.6;
			$initialScore	 = 8;

			$model->bvr_booking_id	 = (int) $bkgId[0];
			$model->bvr_bcb_id		 = $bookingCabModel->bcb_id;
			$model->bvr_vendor_id	 = $modelvendor->vnd_id;
			$rating					 = $modelvendor->vendorStats->vrs_vnd_overall_rating;

			$model->bvr_vendor_rating	 = ($rating == null || $rating == 0) ? $initialRating : $rating;
			$score						 = $modelvendor->vendorStats->vrs_overall_score;
			$model->bvr_vendor_score	 = ($score == null || $score == 0) ? $initialScore : $score;
			$model->bvr_bid_amount		 = $bidAmount;
			$model->bvr_created_at		 = new CDbExpression('NOW()');
			$model->bvr_accepted_at		 = new CDbExpression('NOW()');

			if (Config::get('hornok.operator.id') != $vendorId)
			{
				$model->bvr_active = 1;
			}
			if ($status == 'deny')
			{
				$model->bvr_accepted	 = 2;
				$model->bvr_assigned	 = 2;
				$model->bvr_assigned_at	 = new CDbExpression('NOW()');
			}
			else if ($status == 'newAssign')
			{
				$model->bvr_accepted	 = 1;
				$model->bvr_assigned	 = 1;
				$model->bvr_assigned_at	 = new CDbExpression('NOW()');
			}
			else if ($status == 'manualAssign')
			{
				$model->bvr_accepted	 = 0;
				$model->bvr_assigned	 = 1;
				$model->bvr_assigned_at	 = new CDbExpression('NOW()');
				$model->bvr_accepted_at	 = null;
			}
			else
			{
				$model->bvr_accepted = 1;
				$model->bvr_assigned = 0;
			}


			if (!$model->save())
			{
				throw new Exception('Bid request not set');
			}
			DBUtil::commitTransaction($transaction);
			// add smt

			$res = BookingVendorRequest::scheduleSMTScore($bcbId, $vendorId, $bidAmount);
		}
		catch (Exception $e)
		{
			DBUtil::rollbackTransaction($transaction);
			$success = false;
		}
		return $success;
	}

	/**
	 * @deprecated  use BookingVendorRequest::updateSMTScores
	 * @return boolean 
	 */
	public static function updateSMTScore1($tripId, $vendorId, $score = null, $tripVendorAmount = null)
	{
		if ($score !== null)
		{
			goto updateScore;
		}
		$success = false;
		$result	 = self::getBidScoresByVendor($vendorId, $tripId, $tripVendorAmount);
		$score	 = $result['bestBidRank'];
		$bvrId	 = $result['bvr_id'];

		updateScore:
		if ($score != "")
		{
			$bvrModel				 = BookingVendorRequest::model()->findByPk($bvrId);
			$bvrModel->bvr_smt_score = $score;
			$success				 = $bvrModel->save();
		}

		return $success;
	}

	public function checkAlreadyAssignOrNot($vendorId, $bcbId)
	{
		$sql	 = "SELECT bvr_id FROM booking_vendor_request WHERE bvr_bcb_id=$bcbId AND bvr_vendor_id=$vendorId AND bvr_active=1";
		$result	 = DBUtil::command($sql)->queryScalar();
		return $result;
	}

	public function checkActiveDuplicate($vendorId, $bcbId)
	{
		$sql	 = "SELECT bvr_id FROM booking_vendor_request WHERE bvr_bcb_id=$bcbId AND bvr_vendor_id=$vendorId AND bvr_active=1";
		$result	 = DBUtil::query($sql);
		return $result;
	}

	public static function getPendingRequest($vendorId, $page_no = 0, $filterModel, $offSetCount, $total_count = 0)
	{
		$vndInfo = DBUtil::queryRow("SELECT vnd_cat_type,vnp_is_freeze,vnp_cod_freeze,vnp_accepted_zone,vnp_home_zone,vnp_excluded_cities, vnp_oneway, vnp_round_trip, vnp_multi_trip, vnp_airport, vnp_package, vnp_flexxi,
                            vnp_daily_rental,IF(vnp_is_allowed_tier LIKE '%1%',1,0) value,IF(vnp_is_allowed_tier LIKE '%2%',2,0) valuePlus,
                            IF(vnp_is_allowed_tier LIKE '%3%',3,0) plus,IF(vnp_is_allowed_tier LIKE '%4%',4,0) selectTier
							FROM vendors INNER JOIN vendor_pref ON vnp_vnd_id = vnd_id WHERE vnd_id = $vendorId", DBUtil::SDB());

		$row			 = AccountTransDetails::getTotTransByVndId($vendorId);
		$totTrans		 = ($row['totTrans'] > 0) ? $row['totTrans'] : 0;
		$vndIsFreeze	 = ($row['vnd_is_freeze'] > 0) ? $row['vnd_is_freeze'] : 0;
		$vndCodFreeze	 = ($row['vnd_cod_freeze'] > 0) ? $row['vnd_cod_freeze'] : 0;

		$vndInfo['vnp_accepted_zone']	 = ($vndInfo['vnp_accepted_zone'] == '') ? -1 : trim($vndInfo['vnp_accepted_zone'], ',');
		$vndInfo['vnp_excluded_cities']	 = ($vndInfo['vnp_excluded_cities'] == '') ? -1 : trim($vndInfo['vnp_excluded_cities'], ',');
		$vndInfo['vnp_home_zone']		 = ($vndInfo['vnp_home_zone'] == '') ? -1 : trim($vndInfo['vnp_home_zone'], ',');
		if ($vndIsFreeze > 0 && $row['vnp_low_rating_freeze'] <= 0 && $row['vnp_doc_pending_freeze'] <= 0 && $row['vnp_manual_freeze'] <= 0)
		{
			$vndIsFreeze = 0;
		}
		if ($vndInfo['vnd_cat_type'] == 1)
		{
			$sql = "SELECT GROUP_CONCAT(bkgnew.bkg_id)
					FROM   booking_cab bcb
					INNER JOIN booking ON  bcb.bcb_id = booking.bkg_bcb_id AND bkg_status IN (3, 5, 6, 7) AND bcb_vendor_id = $vendorId AND (bcb_start_time > NOW() OR bcb_end_time > NOW())
					INNER JOIN booking_cab bcbnew ON (bcbnew.bcb_start_time BETWEEN bcb.bcb_start_time AND bcb.bcb_end_time OR bcbnew.bcb_end_time BETWEEN bcb.bcb_start_time AND bcb.bcb_end_time)
					INNER JOIN booking bkgnew ON bkgnew.bkg_bcb_id = bcbnew.bcb_id AND bkgnew.bkg_status = 2 AND bkgnew.bkg_from_city_id 
					INNER JOIN zone_cities zct ON zct.zct_cty_id = bkgnew.bkg_from_city_id
					WHERE  bcb.bcb_active = 1 AND bcbnew.bcb_active = 1
					AND ((zct.zct_zon_id IN (" . $vndInfo['vnp_accepted_zone'] . ")	AND zct.zct_cty_id NOT IN (" . $vndInfo['vnp_excluded_cities'] . ")) OR zct.zct_zon_id IN (" . $vndInfo['vnp_home_zone'] . "))";

			$excludeBookings = DBUtil::querySCalar($sql, DBUtil::SDB());
		}
		$excludeBookings = ($excludeBookings == null || $excludeBookings == '') ? -1 : $excludeBookings;
		if ($vndInfo['vnp_oneway'] != 1)
		{
			$condSelectProfile .= " AND booking.bkg_booking_type NOT IN(1)";
		}
		if ($vndInfo['vnp_round_trip'] != 1 && $vndInfo['vnp_round_trip'] != -1)
		{
			$condSelectProfile .= " AND booking.bkg_booking_type NOT IN(2)";
		}
		if ($vndInfo['vnp_multi_trip'] != 1 && $vndInfo['vnp_multi_trip'] != -1)
		{
			$condSelectProfile .= " AND booking.bkg_booking_type NOT IN(3)";
		}
		if ($vndInfo['vnp_airport'] != 1 && $vndInfo['vnp_airport'] != -1)
		{
			$condSelectProfile .= " AND booking.bkg_booking_type NOT IN(4,12)";
		}
		if ($vndInfo['vnp_package'] != 1 && $vndInfo['vnp_package'] != -1)
		{
			$condSelectProfile .= " AND booking.bkg_booking_type NOT IN(5)";
		}
		if ($vndInfo['vnp_flexxi'] != 1 && $vndInfo['vnp_flexxi'] != -1)
		{
			$condSelectProfile .= " AND booking.bkg_booking_type NOT IN(6)";
		}
		if ($vndInfo['vnp_daily_rental'] != 1 && $vndInfo['vnp_daily_rental'] != -1)
		{
			$condSelectProfile .= " AND booking.bkg_booking_type NOT IN(9,10,11)";
		}
		$serviceTier = $filterModel->tierList;
		foreach ($serviceTier as $service)
		{
			$tiers[] = $service->id;
		}
		$tier_string = implode(",", $tiers);
		if ($tier_string != "")
		{
			$condSelectTierCheck		 = " AND service_class.scc_id IN ($tier_string) ";
			$condSelectTierCheckMatched	 = " AND scc.scc_id IN ($tier_string) ";
		}
		else
		{
			$condSelectTierCheck		 = " AND service_class.scc_id IN ({$vndInfo['value']},{$vndInfo['valuePlus']},{$vndInfo['plus']},{$vndInfo['selectTier']}) ";
			$condSelectTierCheckMatched	 = " AND scc.scc_id IN ({$vndInfo['value']},{$vndInfo['valuePlus']},{$vndInfo['plus']},{$vndInfo['selectTier']}) ";
		}
		$sqlIncludedBookings = " SELECT GROUP_CONCAT(temp.bkg_id) as bkg_id from  ((SELECT bkg_id FROM booking bkg
								INNER JOIN booking_trail ON btr_bkg_id = bkg_id AND btr_is_bid_started = 1
								INNER JOIN zone_cities zct ON zct.zct_cty_id = bkg.bkg_from_city_id 
								WHERE bkg.bkg_pickup_date > NOW() AND bkg.bkg_status = 2 AND bkg.bkg_active = 1 AND bkg_id NOT IN($excludeBookings)
								AND ((zct.zct_zon_id IN (" . $vndInfo['vnp_accepted_zone'] . ")
								AND zct.zct_cty_id NOT IN (" . $vndInfo['vnp_excluded_cities'] . ")) OR zct.zct_zon_id IN (" . $vndInfo['vnp_home_zone'] . ")))
								UNION 
								(SELECT bkg_id FROM booking bkg
								INNER JOIN booking_trail ON btr_bkg_id = bkg_id AND btr_is_bid_started = 1
								INNER JOIN zone_cities zct ON zct.zct_cty_id = bkg.bkg_to_city_id 
								WHERE bkg.bkg_pickup_date > NOW() AND bkg.bkg_status = 2 AND bkg.bkg_active = 1 AND bkg_id NOT IN($excludeBookings)
								AND ((zct.zct_zon_id IN (" . $vndInfo['vnp_accepted_zone'] . ")
								AND zct.zct_cty_id NOT IN (" . $vndInfo['vnp_excluded_cities'] . ")) OR zct.zct_zon_id IN (" . $vndInfo['vnp_home_zone'] . "))))  as temp ";
		$includeBookings	 = DBUtil::queryScalar($sqlIncludedBookings, DBUtil::SDB());
		$includeBookings	 = ($includeBookings == null || $includeBookings == '') ? -1 : $includeBookings;
		$search_qry			 = '';
		$search_txt			 = $filterModel->search_txt;
		if ($search_txt != '')
		{
			$search_qry = " HAVING
				(
					cab_model LIKE '%$search_txt%'
					OR bcb_id LIKE '%$search_txt%'
					OR bkgIds LIKE '%$search_txt%'
					OR bkgBookingIds LIKE '%$search_txt%'
					OR bkg_route_name LIKE '%$search_txt%'
				)";
		}

		if ($filterModel->sort == 'newestBooking')
		{
			$sortCond = "ORDER BY bkgIds DESC";
		}
		if ($filterModel->sort == 'earliestBooking')
		{
			$sortCond = "ORDER BY bkg_pickup_date ASC";
		}

		if ($page_no >= 1)
		{
			$offset		 = ($page_no - 1) * $offSetCount;
			$limitCond	 = ($total_count == 0) ? " LIMIT $offSetCount OFFSET $offset" : " ";
		}
		else
		{
			$limitCond = " ";
		}

		$status	 = $filterModel->bid_status;
		$date	 = $filterModel->date;

		if (!empty($filterModel))
		{
			$bidStatus	 = $filterModel->bidStatus;
			$filter_qry	 = "";
			if ($bidStatus == 1)
			{
				$isBid		 = true;
				$filter_qry	 .= " AND (bvr_id IS NOT NULL AND bvr_accepted = 1 AND bvr_vendor_id = $vendorId)";
			}
			$serviceType = $filterModel->serviceType;
			if ($serviceType == 'local')
			{
				$filter_qry .= " AND booking.bkg_booking_type IN(4,9,10,11,12,15)";
			}
			if ($serviceType == 'outstation')
			{
				$filter_qry .= " AND booking.bkg_booking_type IN(1,2,3,5,6,7,8)";
			}
			if ($serviceType == 'all')
			{
				$filter_qry .= " AND booking.bkg_booking_type IN(1,2,3,5,6,7,8,4,9,10,11,12,15)";
			}

			if ($date != "")
			{
				$filter_qry .= " AND booking.bkg_pickup_date LIKE '" . $date . "%'";
			}
		}

		$val	 = '"';
		$sqlMain = "SET STATEMENT max_statement_time=10 FOR
			        SELECT   
						0 AS matchType,
						IF(booking_pref.bkg_cng_allowed =1 AND (bkgaddinfo.bkg_num_large_bag < 2  OR bkgaddinfo.bkg_num_large_bag >1 ) ,1,0 ) AS is_cng_allowed,
						IF(booking.bkg_booking_type IN (4,9,10,11,12,15), 'local', 'outstation') AS businesstype,					
						IF(booking.bkg_flexxi_type IN (1,2),true,false) isFlexxi,
						bcb_id,booking.bkg_create_date,booking.bkg_trip_distance,booking.bkg_trip_duration,booking.bkg_booking_type,booking.bkg_status,booking.bkg_route_city_names as bkg_route_name,
						GROUP_CONCAT(DISTINCT bkg_id) bkgIds,
						GROUP_CONCAT(DISTINCT bkg_booking_id) bkgBookingIds,
						trim(replace(replace(replace(replace(`bkg_route_city_names`,'[',''),']',''),'$val',''),',','-')) AS bkg_route_name,  
						bcb_bid_start_time, btr.btr_manual_assign_date, btr.btr_critical_assign_date,
						GREATEST(COALESCE(bcb_bid_start_time,0), COALESCE(btr_manual_assign_date,0), COALESCE(btr_critical_assign_date,0)) as booking_priority_date,
						CASE bkg_booking_type WHEN 1 THEN IF(bcb_trip_type=1,'MATCHED','ONE WAY') WHEN 2 THEN 'ROUND TRIP' WHEN 3 THEN 'MULTI WAY' WHEN 4 THEN 'ONE WAY' WHEN 5 THEN 'PACKAGE' WHEN 8 THEN 'PACKAGE' WHEN 9 THEN 'DAY RENTAL 4hr-40km' WHEN 10 THEN 'DAY RENTAL 8hr-80km' WHEN 11 THEN 'DAY RENTAL 12hr-120km' WHEN 12 THEN 'Airport Packages' WHEN 15 THEN 'Local Transfer' ELSE 'SHARED' END AS booking_type,
						 IF(bkg_critical_assignment=1 OR bkg_manual_assignment=1 OR booking.bkg_booking_type = 12,0,IF((DATE_ADD(NOW(), INTERVAL 13 HOUR) >= booking.bkg_pickup_date AND booking.bkg_reconfirm_flag=1 AND bkg_block_autoassignment=0),0,1)) AS is_biddable,
						IF(booking.bkg_agent_id > 0, 1, 0) AS is_agent,
						vehicle_category.vct_label AS cab_model,
						ROUND(booking_cab.bcb_vendor_amount * 0.98) AS recommended_vendor_amount,
						service_class.scc_label AS cab_lavel,
						booking_cab.bcb_vendor_amount AS max_bid_amount,
						(booking_cab.bcb_vendor_amount * 0.7) AS min_bid_amount,
						IF(vehicle_category.vct_id IN(5, 6),1,0) AS is_assured,
						MIN(booking.bkg_pickup_date) bkg_pickup_date,
						bkg_return_date,
						bcb_end_time trip_completion_time,
						biv.bkg_total_amount,
						IFNULL(bvr_bid_amount,0) AS bvr_bid_amount,
						(CASE WHEN (($vndIsFreeze = 1) AND ($totTrans > 0) AND biv.bkg_advance_amount <=(biv.bkg_total_amount * 0.3)) THEN '1' WHEN($vndIsFreeze = 1) THEN '2' ELSE '0' END) AS payment_due,
						(
						CASE (CASE WHEN (($vndIsFreeze = 1) AND ($totTrans > 0) AND biv.bkg_advance_amount <=(biv.bkg_total_amount * 0.3)) THEN '1' WHEN($vndIsFreeze = 1) THEN '2' ELSE '0' END) WHEN '1' THEN CONCAT(
							'Your amount due is ',
							ABS($totTrans),
							'. Please send payment immediately'
						) WHEN '2' THEN 'Your Gozo Account is temporarily frozen. Please contact your Account Manager or Gozo Team to have it resolved.' WHEN '0' THEN ''
						END
						) AS payment_msg,bkg_night_pickup_included,bkg_night_drop_included FROM booking
								  INNER JOIN booking_invoice biv ON biv.biv_bkg_id = booking.bkg_id
								  INNER JOIN booking_add_info bkgaddinfo ON  booking.bkg_id = bkgaddinfo.bad_bkg_id
								  INNER JOIN booking_pref ON bpr_bkg_id = booking.bkg_id
								  INNER JOIN booking_trail btr ON btr_bkg_id = booking.bkg_id
								  INNER JOIN svc_class_vhc_cat scv ON scv.scv_id = booking.bkg_vehicle_type_id
								  INNER JOIN vehicle_category ON vehicle_category.vct_id = scv.scv_vct_id
								  INNER JOIN service_class ON service_class.scc_id = scv.scv_scc_id
								  INNER JOIN booking_route ON brt_bkg_id = booking.bkg_id
								  INNER JOIN cities ct1 ON ct1.cty_id = booking_route.brt_from_city_id
								  INNER JOIN cities ct2 ON ct2.cty_id = booking_route.brt_to_city_id
								  INNER JOIN booking_cab ON bcb_id = bkg_bcb_id AND bcb_active = 1 AND bkg_id IN ($includeBookings)
								  LEFT JOIN booking_vendor_request ON bvr_bcb_id = bcb_id AND bvr_vendor_id = $vendorId  AND bvr_active = 1 
						WHERE  bcb_active = 1 $filter_qry $condSelectProfile $condSelectTierCheck
						GROUP BY bcb_id 
						$search_qry 

						UNION

						SELECT 
						1 AS matchType,
						IF(bpr1.bkg_cng_allowed =1 AND (bkgaddinfo.bkg_num_large_bag < 2  OR bkgaddinfo.bkg_num_large_bag >1 ) ,1,0 ) AS is_cng_allowed,
						IF(booking.bkg_booking_type IN(4,9,10,11,12,14), 'local', 'outstation')AS businesstype,
						IF(booking.bkg_flexxi_type IN(1,2),true,false) isFlexxi,
						bcb_id,booking.bkg_create_date,booking.bkg_trip_distance,booking.bkg_trip_duration,booking.bkg_booking_type,booking.bkg_status,booking.bkg_route_city_names as bkg_route_name ,
						CONCAT(bsm.bsm_upbooking_id,', ',bsm.bsm_downbooking_id) bkgIds,
						CONCAT(booking.bkg_booking_id,', ',bkg.bkg_booking_id) bkgBookingIds,
						CONCAT(fc.cty_name,'-',tc.cty_name) bkg_route_name,
						bcb_bid_start_time, btr.btr_manual_assign_date, btr.btr_critical_assign_date, 
						GREATEST(COALESCE(bcb_bid_start_time,0), COALESCE(btr_manual_assign_date,0), COALESCE(btr_critical_assign_date,0)) as booking_priority_date,
						'MATCHED' AS booking_type, 
						IF((bpr1.bkg_critical_assignment=1 OR bpr1.bkg_manual_assignment=1 OR booking.bkg_booking_type = 12),0,IF((DATE_ADD(NOW(), INTERVAL 13 HOUR) >= booking.bkg_pickup_date AND booking.bkg_reconfirm_flag=1  AND bpr1.bkg_block_autoassignment=0),0,1)) AS is_biddable,
						IF(booking.bkg_agent_id > 0 OR bkg.bkg_agent_id>0, 1, 0) AS is_agent,
						vehicle_category.vct_label AS cab_model,
						ROUND(bsm_vendor_amt_matched * 0.98) AS recommended_vendor_amount,
						scc.scc_label AS cab_lavel,
						bsm_vendor_amt_matched AS max_bid_amount,
						(bsm_vendor_amt_matched * 0.7) AS min_bid_amount,
						IF(vehicle_category.vct_id IN(5, 6),1,0) AS is_assured,
						MIN(booking.bkg_pickup_date) bkg_pickup_date,
						NULL AS bkg_return_date,
						bcb_end_time trip_completion_time,
						biv1.bkg_total_amount,
						IFNULL(bvr_bid_amount,0) AS bvr_bid_amount,
						(CASE WHEN (($vndIsFreeze = 1) AND ($totTrans > 0) AND biv1.bkg_advance_amount <=(biv1.bkg_total_amount * 0.3)) THEN '1' WHEN($vndIsFreeze = 1) THEN '2' ELSE '0' END) AS payment_due,
						(
						CASE (CASE WHEN (($vndIsFreeze = 1) AND ($totTrans > 0) AND biv1.bkg_advance_amount <=(biv1.bkg_total_amount * 0.3)) THEN '1' WHEN($vndIsFreeze = 1) THEN '2' ELSE '0' END) WHEN '1' THEN CONCAT(
							'Your amount due is ',
							ABS($totTrans),
							'. Please send payment immediately'
						) WHEN '2' THEN 'Your Gozo Account is temporarily frozen. Please contact your Account Manager or Gozo Team to have it resolved.' WHEN '0' THEN ''
						END
						) AS payment_msg,
						bkg_night_pickup_included,bkg_night_drop_included
					FROM
							   booking_smartmatch bsm 	
									INNER JOIN booking ON bkg_id = bsm.bsm_upbooking_id AND bsm.bsm_upbooking_id IN ($includeBookings)
									INNER JOIN booking_add_info bkgaddinfo ON  booking.bkg_id = bkgaddinfo.bad_bkg_id
									INNER JOIN booking_invoice biv1 ON biv1.biv_bkg_id = booking.bkg_id
									INNER JOIN booking_pref bpr1 ON bpr1.bpr_bkg_id = booking.bkg_id 
									INNER JOIN booking_trail btr ON btr_bkg_id = booking.bkg_id 
									INNER JOIN svc_class_vhc_cat ON svc_class_vhc_cat.scv_id = booking.bkg_vehicle_type_id
									INNER JOIN vehicle_category ON vehicle_category.vct_id = svc_class_vhc_cat.scv_vct_id
									INNER JOIN service_class scc ON scc.scc_id = svc_class_vhc_cat.scv_scc_id
									INNER JOIN cities fc ON fc.cty_id = booking.bkg_from_city_id AND fc.cty_id = booking.bkg_to_city_id 
									INNER JOIN booking bkg ON bkg.bkg_id = bsm.bsm_downbooking_id	AND bsm.bsm_downbooking_id IN ($includeBookings)
									INNER JOIN cities tc ON tc.cty_id = bkg.bkg_from_city_id AND tc.cty_id = booking.bkg_to_city_id
									INNER JOIN booking_cab ON bcb_id = bsm.bsm_bcb_id AND bcb_active = 1
									LEFT JOIN booking_vendor_request ON bvr_bcb_id = bcb_id AND bvr_vendor_id = $vendorId AND bvr_active = 1 
									WHERE bcb_active = 1 AND bsm.bsm_ismatched = 0 $filter_qry $condSelectProfile $condSelectTierCheckMatched GROUP BY bcb_id  $search_qry $sortCond  $limitCond";

		return DBUtil::queryAll($sqlMain, DBUtil::SDB());
	}

	public static function getPendingRequestV2($vendorId, $pageCount = 0, $filterModel, $offSetCount = 20)
	{

//		$vndInfoSql = "SELECT vnd_cat_type,vnd_active,vnp_is_freeze,vnp_cod_freeze,vnp_accepted_zone,vnp_home_zone,vnp_excluded_cities, vnp_oneway, vnp_round_trip, vnp_multi_trip, vnp_airport, vnp_package, vnp_flexxi,
//                            vnp_daily_rental,vnp_boost_enabled,IF(vnp_is_allowed_tier LIKE '%1%',1,0) value,IF(vnp_is_allowed_tier LIKE '%2%',2,0) valuePlus,
//                            IF(vnp_is_allowed_tier LIKE '%3%',3,0) plus,IF(vnp_is_allowed_tier LIKE '%4%',4,0) selectTier,IF(vnp_is_allowed_tier LIKE '%5%',5,0) selectPlus,IF(vnp_is_allowed_tier LIKE '6%',6,0) cng
//							FROM vendors INNER JOIN vendor_pref ON vnp_vnd_id = vnd_id WHERE vnd_id = $vendorId";
//
//		$vndInfo = DBUtil::queryRow($vndInfoSql, DBUtil::SDB());


		$vndInfo = VendorPref::getInfoById($vendorId);

		$row		 = AccountTransDetails::getTotTransByVndId($vendorId);
		$carTypeRes	 = VehicleTypes::vendorCabType($vendorId);
		$carType	 = $carTypeRes['vhcLabelId'];
		$car_arr	 = explode(",", $carType);

		$carValArr	 = BookingVendorRequest::carAccess($car_arr);
		$carVal		 = implode(",", $carValArr);
		#print_r($carVal);exit;

		$totTrans						 = ($row['totTrans'] > 0) ? $row['totTrans'] : 0;
		$vndIsGozonowEnabled			 = ($row['vnp_gozonow_enabled'] < 2) ? 1 : 0;
		$vndIsFreeze					 = ($row['vnd_is_freeze'] > 0) ? $row['vnd_is_freeze'] : 0;
		$vndCodFreeze					 = ($row['vnd_cod_freeze'] > 0) ? $row['vnd_cod_freeze'] : 0;
		$vndInfo['vnp_accepted_zone']	 = ($vndInfo['vnp_accepted_zone'] == '') ? -1 : trim($vndInfo['vnp_accepted_zone'], ',');
		$vndInfo['vnp_excluded_cities']	 = ($vndInfo['vnp_excluded_cities'] == '') ? -1 : trim($vndInfo['vnp_excluded_cities'], ',');
		$vndInfo['vnp_home_zone']		 = ($vndInfo['vnp_home_zone'] == '') ? -1 : trim($vndInfo['vnp_home_zone'], ',');
		$vndBoostEnable					 = ($vndInfo['vnp_boost_enabled'] > 0) ? $vndInfo['vnp_boost_enabled'] : 0;
		$vendorStatus					 = $vndInfo['vnd_active'];

		$vndStatInfo = VendorStats::model()->fetchMetric($vendorId);

		$vndRating		 = ($vndStatInfo['vrs_vnd_overall_rating'] == null) ? 0 : $vndStatInfo['vrs_vnd_overall_rating'];
		$vndStickyScr	 = ($vndStatInfo['vrs_sticky_score'] == null) ? 4 : $vndStatInfo['vrs_sticky_score'];
		$vndPenaltyCount = $vndStatInfo['vrs_penalty_count'];
		$vndDriverApp	 = $vndStatInfo['vrs_driver_app_used'];
		$vndDependency	 = ($vndStatInfo['vrs_dependency'] == null || $vndStatInfo['vrs_dependency'] == '') ? 0 : $vndStatInfo['vrs_dependency'];
		$vndBoostPercent = ($vndStatInfo['vrs_boost_percentage'] == null) ? 0 : $vndStatInfo['vrs_boost_percentage'];

		$acptBidPercent	 = ($vndBoostEnable > 0) ? (5 - $vndBoostPercent * 0.01 * 2) : 5;
		$zones			 = implode(",", $filterModel->zones);
		$query			 = "";
		if ($zones != "")
		{
			$sqlServiceZone	 = $zones;
			$query			 = "AND (zct.zct_zon_id IN ($sqlServiceZone) AND zct.zct_cty_id NOT IN (" . $vndInfo['vnp_excluded_cities'] . "))";
		}
		else
		{
			$sqlServiceZone	 = "SELECT hsz_service_id FROM home_service_zones WHERE hsz_home_id IN ({$vndInfo['vnp_home_zone']})";
			$query			 = "AND ((zct.zct_zon_id IN ($sqlServiceZone) AND zct.zct_cty_id NOT IN (" . $vndInfo['vnp_excluded_cities'] . ")) OR zct.zct_zon_id IN (" . $vndInfo['vnp_home_zone'] . "))";
		}
		if ($vndIsFreeze > 0 && $row['vnp_low_rating_freeze'] <= 0 && $row['vnp_doc_pending_freeze'] <= 0 && $row['vnp_manual_freeze'] <= 0)
		{
			$vndIsFreeze = 0;
		}

		if ($vndInfo['vnd_is_dco'] == 1)
		{
			$res = Vendors::checkDriverCountForDCO($vendorId);

			$isSelfDriver		 = $res['isSelfDriver']; ##1
			$drvCount			 = $res['drvCount']; ##4
			$isSelfInDriverList	 = $res['isSelfInDriverList']; #1
			if ($isSelfDriver <> 1 || ( $drvCount - $isSelfInDriverList > 0 ))
			{
				goto skipDCOCheck;
			}

			// Check DCO bookings to be excluded if he is already assigned with another trip at the same time
			$sql			 = "SELECT GROUP_CONCAT(DISTINCT bkgnew.bkg_id)
					FROM   booking_cab bcb
					INNER JOIN booking ON  bcb.bcb_id = booking.bkg_bcb_id AND bkg_status IN (3, 5, 6, 7) AND bcb_vendor_id = $vendorId AND (bcb_start_time > NOW() OR bcb_end_time > NOW())
					INNER JOIN booking_cab bcbnew ON (bcbnew.bcb_start_time BETWEEN bcb.bcb_start_time AND bcb.bcb_end_time OR bcbnew.bcb_end_time BETWEEN bcb.bcb_start_time AND bcb.bcb_end_time)
					INNER JOIN booking bkgnew ON bkgnew.bkg_bcb_id = bcbnew.bcb_id AND bkgnew.bkg_status = 2 AND bkgnew.bkg_from_city_id 
					INNER JOIN zone_cities zct ON zct.zct_cty_id = bkgnew.bkg_from_city_id
					WHERE  bcb.bcb_active = 1 AND bcbnew.bcb_active = 1 $query";
			$excludeBookings = DBUtil::querySCalar($sql, DBUtil::SDB());
			skipDCOCheck:
		}
		$excludeBookings = ($excludeBookings == null || $excludeBookings == '') ? -1 : $excludeBookings;

		/** According to discussion on 24/08/22 with AK & KG in pending list all types of booking will not shown except service added with vendor* */
		/** According to AK for in case of airport booking if vendor dependency score>60 then able accept booking if  airport service not added to that vendor * */
		if ($vndInfo['vnp_oneway'] != 1)
		{
			$condSelectProfile .= " AND booking.bkg_booking_type NOT IN(1)";
		}
		if ($vndInfo['vnp_round_trip'] != 1)
		{
			$condSelectProfile .= " AND booking.bkg_booking_type NOT IN(2)";
		}
		if ($vndInfo['vnp_multi_trip'] != 1)
		{
			$condSelectProfile .= " AND booking.bkg_booking_type NOT IN(3)";
		}
		/* if ($vndInfo['vnp_airport'] != 1 &&  $vndDependency < 60)
		  {
		  $condSelectProfile .= " AND booking.bkg_booking_type NOT IN(4,12)";
		  } */
		if ($vndInfo['vnp_package'] != 1)
		{
			$condSelectProfile .= " AND booking.bkg_booking_type NOT IN(5)";
		}
		if ($vndInfo['vnp_flexxi'] != 1)
		{
			$condSelectProfile .= " AND booking.bkg_booking_type NOT IN(6)";
		}
		if ($vndInfo['vnp_daily_rental'] != 1)
		{
			$condSelectProfile .= " AND booking.bkg_booking_type NOT IN(9,10,11)";
		}


		$tiers		 = [];
		$serviceTier = $filterModel->tierList;
		foreach ($serviceTier as $service)
		{
			if ($service->name == 'select')
			{
				$tiers[] = 4;
				$tiers[] = 5;
			}
			else if ($service->name == 'value')
			{
				$tiers[] = 1;
				$tiers[] = 6;
			}
			else
			{
				$tiers[] = $service->id;
			}
		}
		$serviceTierList = $filterModel->tiers;
		foreach ($serviceTierList as $service)
		{
			if ($service == 3)
			{
				$tiers[] = 4;
				$tiers[] = 5;
			}
			else if ($service == 1)
			{
				$tiers[] = 1;
				$tiers[] = 6;
			}
			else
			{
				$tiers[] = $service;
			}
		}
		$tier_string = implode(",", array_unique($tiers));

		if ($tier_string != "")
		{
			$condSelectTierCheck		 = " AND service_class.scc_id IN ($tier_string) ";
			$condSelectTierCheckMatched	 = " AND scc.scc_id IN ($tier_string) ";
		}
		else
		{
//			$condSelectTierCheck		 = " AND service_class.scc_id IN ({$vndInfo['vnp_is_allowed_tier']}) ";
//			$condSelectTierCheckMatched	 = " AND scc.scc_id IN ({$vndInfo['vnp_is_allowed_tier']}) ";
		}


		$search_qry		 = '';
		$search_txt		 = $filterModel->search_txt;
		$allowedZones	 = implode(',', array_filter(explode(',', $vndInfo['vnp_accepted_zone'] . ',' . $vndInfo['vnp_home_zone'])));
		if ($search_txt != '')
		{
			$search_txt	 = addslashes($search_txt);
			$search_qry	 = " AND
				(
					vehicle_category.vct_label LIKE '%$search_txt%'
					OR bcb_id LIKE '%$search_txt%'
					OR booking.bkg_id LIKE '%$search_txt%'
					OR booking.bkg_booking_id LIKE '%$search_txt%'
					OR  booking.bkg_route_city_names LIKE '%$search_txt%'
				)";
		}

		$search_qry .= "  AND ((fzc.zct_zon_id IN ({$sqlServiceZone}) OR tzc.zct_zon_id IN ({$sqlServiceZone}))
							AND (fzc.zct_cty_id NOT IN ({$vndInfo['vnp_excluded_cities']}) OR tzc.zct_cty_id NOT IN ({$vndInfo['vnp_excluded_cities']}))
							AND  booking.bkg_id NOT IN($excludeBookings)	
						)";

		if ($filterModel->sort == 'newestBooking')
		{
			//$sortCond = "ORDER BY bkgIds DESC,isGozoNow DESC";
			$sortCond = "ORDER BY bcb_bid_start_time DESC";
		}
		if ($filterModel->sort == 'earliestBooking' || $sortCond == "")
		{
			$sortCond = "ORDER BY bkg_pickup_date ASC";
		}

		$limitCond = "LIMIT $pageCount, $offSetCount";

		$status		 = $filterModel->bidStatus;
		$date		 = $filterModel->date;
		$isGozoNow	 = $filterModel->isGozoNow;

		if (!empty($filterModel))
		{
			$bidStatus	 = $filterModel->bidStatus;
			$filter_qry	 = "";
			if ($bidStatus == 1)
			{
				$isBid		 = true;
				$filter_qry	 .= " AND (bvr_id IS NOT NULL AND bvr_accepted = 1 AND bvr_vendor_id = $vendorId)";
			}

			$offerTypeList = $filterModel->offerTypes;
//			$offerTypeList	 = array_diff($offerTypeList, [1]);
			if (sizeof($offerTypeList) > 0)
			{
				$offerQryrRaw = [];
				foreach ($offerTypeList as $offerType)
				{//1=>new, 2 =>denied,3=>offered
					if ($offerType == 1)// =>new
					{
						$offerQryrRaw[] = "(bvr_id IS NULL  OR (bvr_id IS NOT NULL AND bvr_accepted <> 2 AND bvr_vendor_id = $vendorId)) ";
					}
					if ($offerType == 2)// =>denied
					{
						$offerQryrRaw[] = "(bvr_id IS NOT NULL AND bvr_accepted = 2 AND bvr_bid_amount = 0) AND bvr_vendor_id = $vendorId";
					}
					if ($offerType == 3)//offered
					{
						$offerQryrRaw[] = "(bvr_id IS NOT NULL AND bvr_accepted = 1 AND bvr_bid_amount > 0) AND bvr_vendor_id = $vendorId";
					}
				}
				$filter_qry .= ' AND (' . implode(' OR ', $offerQryrRaw) . ") ";
			}


			$serviceType = $filterModel->serviceType;
			$bkgTypes	 = '';
			if ($serviceType == 'local')
			{
				$bkgTypes = '4,9,10,11,12,15';
			}
			if ($serviceType == 'outstation')
			{
				$bkgTypes = '1,2,3,5,6,7,8';
			}
			if ($serviceType == 'all' || (empty($filterModel->serviceType) && empty($filterModel->serviceTypes)) || !$filterModel->serviceTypes[0])
			{
				$bkgTypes = '1,2,3,5,6,7,8,4,9,10,11,12,15';
			}

			if (sizeof($filterModel->serviceTypes) > 0)
			{
				$bkgTypes = ltrim($bkgTypes . ',' . implode(',', $filterModel->serviceTypes), ',');
			}
			$filter_qry .= " AND booking.bkg_booking_type IN($bkgTypes)";

			if ($date != "")
			{
				$fromDate	 = $date . " 00:00:00";
				$toDate		 = $date . " 23:59:59";
				$filter_qry	 .= " AND booking.bkg_pickup_date BETWEEN '$fromDate' AND '$toDate' ";
			}
			elseif ($filterModel->pickupDateRange->fromDate || $filterModel->pickupDateRange->toDate)
			{

				if ($filterModel->pickupDateRange->fromDate)
				{
					$fromDate	 = $filterModel->pickupDateRange->fromDate . " 00:00:00";
					$filter_qry	 .= " AND booking.bkg_pickup_date >= '$fromDate' ";
				}
				if ($filterModel->pickupDateRange->toDate)
				{
					$toDate		 = $filterModel->pickupDateRange->toDate . " 23:59:59";
					$filter_qry	 .= " AND booking.bkg_pickup_date <= '$toDate' ";
				}
			}
			else
			{
				$filter_qry .= " AND booking.bkg_pickup_date > DATE_SUB(NOW(), INTERVAL 2 HOUR) ";
			}
			if ($isGozoNow == 1)
			{
				$filter_qry .= "AND booking_pref.bkg_is_gozonow=1";
			}
		}

		$val = '"';

		$acceptBidPercent	 = "GetVendorAcceptMargin2('{$vndInfo['vnp_home_zone']}', '{$vndInfo['vnp_accepted_zone']}', GROUP_CONCAT(DISTINCT fzc.zct_zon_id), GROUP_CONCAT(DISTINCT tzc.zct_zon_id), $vndBoostEnable, $vndBoostPercent, IFNULL(bkg_critical_score,0.65),IFNULL(btr_is_dem_sup_misfire,0))";
		//$acceptBidPercent	 = "GetVendorAcceptMargin1('{$vndInfo['vnp_home_zone']}', '{$vndInfo['vnp_accepted_zone']}', GROUP_CONCAT(DISTINCT fzc.zct_zon_id), GROUP_CONCAT(DISTINCT tzc.zct_zon_id), $vndBoostEnable, $vndBoostPercent, IFNULL(bkg_critical_score,0.65))";
		$acceptableAmount	 = "ROUND(booking_cab.bcb_vendor_amount * 0.01 * $acceptBidPercent)";
		/* $lowSMTAmount is used when smt score is less than 0 */
		$lowSMTAmount		 = "ROUND(booking_cab.bcb_vendor_amount * 0.01 * ($acceptBidPercent - 5))";

		$calculateSMTSql = "CalculateSMT(bcb_vendor_amount + SUM(bkg_gozo_amount - IFNULL(bkg_credits_used,0)),booking_cab.bcb_vendor_amount,
					    $acceptableAmount, $vndRating, $vndStickyScr, $vndPenaltyCount, $vndDriverApp, $vndDependency, $vndBoostPercent)";

		$isAcceptAllowed			 = "IsDirectAcceptAllowed('{$vndInfo['vnp_home_zone']}', GROUP_CONCAT(DISTINCT fzc.zct_zon_id), GROUP_CONCAT(DISTINCT tzc.zct_zon_id), bkg_manual_assignment, $calculateSMTSql, bkg_critical_score, MIN(booking.bkg_pickup_date), GREATEST(IFNULL(bcb_bid_start_time, MAX(bkg_confirm_datetime)), MAX(bkg_confirm_datetime)))";
		$validateAcceptableAmountSQL = "IF(bkg_critical_assignment=1 OR bkg_manual_assignment=1 OR booking.bkg_booking_type = 12, ROUND(booking_cab.bcb_vendor_amount * 0.98), IF($calculateSMTSql>=0, $acceptableAmount, $lowSMTAmount))";

		$calRecomendedAmount = "IF(bkg_critical_assignment=1 OR bkg_manual_assignment=1 , ROUND(booking_cab.bcb_vendor_amount * 0.98), ROUND(booking_cab.bcb_vendor_amount * 0.98))";

		//$showBookingCnd = "AND (booking.bkg_status=2 OR (booking.bkg_status IN (3,5) AND booking_cab.bcb_vendor_id <> $vendorId AND booking.bkg_pickup_date > DATE_ADD(NOW(),INTERVAL 6 hour) ))";
		$showBookingCnd	 = " AND booking.bkg_status=2 AND (booking.bkg_reconfirm_flag=1 OR booking_pref.bkg_is_gozonow=1 ) ";
		$showBookingCnd	 .= " AND (booking_pref.bkg_is_fbg_type=0 OR (booking_pref.bkg_is_fbg_type=1 AND bkg_manual_assignment=0 AND bkg_critical_assignment=0)) ";
		$createTempTable = " (
						SELECT bvr_id, bvr_accepted, bvr_vendor_id, bvr_bcb_id, bvr_bid_amount FROM booking 
						INNER JOIN booking_vendor_request ON bvr_bcb_id = bkg_bcb_id 
						AND bvr_active = 1 AND bkg_status = 2 
						AND bvr_vendor_id = $vendorId 
					) ";
		#DBUtil::createTempTable($createTempTable, $sqlTemp);
		$bidQuery		 = "LEFT JOIN booking_vendor_request bvr ON bvr.bvr_bcb_id = bkg_bcb_id 
 			AND bvr.bvr_vendor_id = $vendorId AND bvr_active = 1";
		if ($filterModel->denyStatus == 1)
		{

//				$bidQuery = "LEFT JOIN booking_vendor_request bvr ON bvr.bvr_bcb_id = bkg_bcb_id  AND bvr.bvr_vendor_id = $vendorId AND bvr.bvr_accepted =2";
			$filter_qry .= " AND (bvr.bvr_accepted = 2 OR bvr.bvr_bcb_id IS NULL )";
		}
		else
		{
			//$bidQuery = "LEFT JOIN $createTempTable bvr ON bvr.bvr_bcb_id = bkg_bcb_id AND bvr_accepted <>2 AND   bvr_bcb_id <> 3988974";

			$filter_qry .= " AND (bvr.bvr_accepted <>2 OR bvr.bvr_bcb_id IS NULL)";
		}
		$sqlMain = "SET STATEMENT max_statement_time=10 FOR
			SELECT   
			    0 AS matchType,
			    IF(booking_pref.bkg_cng_allowed =1 AND (bkgaddinfo.bkg_num_large_bag < 2  OR bkgaddinfo.bkg_num_large_bag >1 ) ,1,0 ) AS is_cng_allowed,
			    IF(booking.bkg_booking_type IN(4,9,10,11,12,15), 'local', 'outstation')AS businesstype,					
			    IF(booking.bkg_flexxi_type IN(1,2),true,false) isFlexxi,
			    bcb_id,booking.bkg_create_date,booking.bkg_trip_distance,booking.bkg_trip_duration,booking.bkg_booking_type,booking.bkg_status,booking.bkg_route_city_names as bkg_route_name,
			    GROUP_CONCAT(DISTINCT bkg_id) bkgIds,
			    GROUP_CONCAT(DISTINCT bkg_booking_id) bkgBookingIds,
			    trim(replace(replace(replace(replace(`bkg_route_city_names`,'[',''),']',''),'$val,',' -'),'$val','')) AS bkg_route_name,
			    bcb_bid_start_time, btr.btr_manual_assign_date, btr.btr_critical_assign_date,
			    GREATEST(COALESCE(bcb_bid_start_time,0), COALESCE(btr_manual_assign_date,0), COALESCE(btr_critical_assign_date,0)) as booking_priority_date,
			    CASE bkg_booking_type WHEN 1 THEN IF(bcb_trip_type=1,'MATCHED','ONE WAY') WHEN 2 THEN 'ROUND TRIP' WHEN 3 THEN 'MULTI WAY' WHEN 4 THEN 'Airport Transfer' WHEN 5 THEN 'PACKAGE' WHEN 8 THEN 'PACKAGE' WHEN 9 THEN 'DAY RENTAL 4hr-40km' WHEN 10 THEN 'DAY RENTAL 8hr-80km' WHEN 11 THEN 'DAY RENTAL 12hr-120km' WHEN 12 THEN 'Airport Packages'  WHEN 15 THEN 'Local Transfer' ELSE 'SHARED' END AS booking_type,
			    IF((booking.bkg_booking_type IN (4,12)) OR ($isAcceptAllowed AND booking.bkg_reconfirm_flag=1 AND bkg_block_autoassignment=0), IF(bkg_status IN (3,5), 1,IF($vendorStatus=2,1,0)),1) AS is_biddable,
			    booking.bkg_vehicle_type_id,
			    IF(booking.bkg_agent_id > 0, 1, 0) AS is_agent,scv.scv_label,
			    TRIM(SUBSTRING_INDEX(scv.scv_label, '(', 1))  AS cab_model,
				 bcb_trip_type,
			    $calRecomendedAmount AS recommended_vendor_amount,
				service_class.scc_id as cab_lavel_id,
			    service_class.scc_label AS cab_lavel,
				 biv.bkg_promo1_id,biv.bkg_promo1_code,biv.bkg_promo2_id,biv.bkg_promo2_code,biv.bkg_discount_amount,biv.bkg_discount_amount,
			    booking_cab.bcb_vendor_amount AS max_bid_amount,
				(booking_cab.bcb_vendor_amount * 0.9) AS minAllowableVendorAmount,
				IF(bcb_max_allowable_vendor_amount>0,bcb_max_allowable_vendor_amount,(booking_cab.bcb_vendor_amount+(bkg_gozo_amount-bkg_credits_used))) AS maxAllowableVendorAmount,
			    (booking_cab.bcb_vendor_amount * 0.7) AS min_bid_amount,
			    IF(booking_cab.bcb_matched_type > 0, 1, 0) AS is_matched,
			    IF(vehicle_category.vct_id IN(5, 6),1,0) AS is_assured,
				IF(bkgaddinfo.bkg_no_person > 0,bkgaddinfo.bkg_no_person,vehicle_category.vct_capacity) AS seatingCapacity,
                IF(bkgaddinfo.bkg_num_large_bag > 0,bkgaddinfo.bkg_num_large_bag,vehicle_category.vct_big_bag_capacity) AS bigBagCapacity,
                IF(bkgaddinfo.bkg_num_small_bag > 0,bkgaddinfo.bkg_num_small_bag,vehicle_category.vct_small_bag_capacity) AS bagCapacity,
			    MIN(booking.bkg_pickup_date) bkg_pickup_date,
			    bkg_return_date,
				IF(bkg_is_gozonow = 1, 1, 0) AS isGozoNow,
				bkg_is_gozonow, 
			    bcb_end_time trip_completion_time,
			    biv.bkg_total_amount,
				biv.bkg_quoted_vendor_amount as quoteVendorAmt,
			    $calculateSMTSql AS smtScore,
			    $validateAcceptableAmountSQL AS acptAmount,
			    IFNULL(bvr_bid_amount,0) AS bvr_bid_amount,bvr_accepted,
			    (CASE WHEN (($vndIsFreeze = 1) AND ($totTrans > 0) AND biv.bkg_advance_amount <=(biv.bkg_total_amount * 0.3)) THEN '1' WHEN($vndIsFreeze = 1) THEN '2' ELSE '0' END) AS payment_due,
					(
                    CASE (CASE WHEN (($vndIsFreeze = 1) AND ($totTrans > 0) AND biv.bkg_advance_amount <=(biv.bkg_total_amount * 0.3)) THEN '1' WHEN($vndIsFreeze = 1) THEN '2' ELSE '0' END) WHEN '1' THEN CONCAT(
                        'Your amount due is ',
                        ABS($totTrans),
                        '. Please send payment immediately'
                    ) WHEN '2' THEN 'Your Gozo Account is temporarily frozen. Please contact your Account Manager or Gozo Team to have it resolved.' WHEN '0' THEN ''
                    END
					) AS payment_msg,bkg_night_pickup_included,bkg_night_drop_included 
			FROM booking
			INNER JOIN booking_cab ON bcb_id = bkg_bcb_id AND bcb_active = 1
			INNER JOIN booking_invoice biv ON biv.biv_bkg_id = booking.bkg_id
			 
			INNER JOIN booking_add_info bkgaddinfo ON  booking.bkg_id = bkgaddinfo.bad_bkg_id
			INNER JOIN booking_pref ON bpr_bkg_id = booking.bkg_id
			INNER JOIN booking_trail btr ON btr_bkg_id = booking.bkg_id
			INNER JOIN svc_class_vhc_cat scv ON scv.scv_id = booking.bkg_vehicle_type_id
			
			INNER JOIN vehicle_category ON vehicle_category.vct_id = scv.scv_vct_id
			INNER JOIN service_class ON service_class.scc_id = scv.scv_scc_id
			INNER JOIN booking_route ON brt_bkg_id = booking.bkg_id
			INNER JOIN cities ct1 ON ct1.cty_id = booking_route.brt_from_city_id
			INNER JOIN cities ct2 ON ct2.cty_id = booking_route.brt_to_city_id
			INNER JOIN zone_cities fzc ON fzc.zct_cty_id=ct1.cty_id AND fzc.zct_active=1
			INNER JOIN zone_cities tzc ON tzc.zct_cty_id=ct2.cty_id AND tzc.zct_active=1
			$bidQuery
			WHERE  bcb_active = 1  $condSelectTierCheck
			 $filter_qry  $condSelectProfile $showBookingCnd $search_qry 
			GROUP BY bcb_id $sortCond  $limitCond";

		//According to Abhishek sir ($condSelectTierCheck) tier checking removed on 24-03-2023.
		//Logger::trace("BookingCab::model()->getRevenueBreakup($tripId) :: " . json_encode($revenueDetails));
		Logger::trace('SQL MAIN ===>' . $sqlMain);
		$data = DBUtil::query($sqlMain, DBUtil::SDB());

//		DBUtil::dropTempTable($createTempTable);

		return $data;
	}

	public function carAccess($car_arr)
	{
		$carVal = array();

		if (in_array(1, $car_arr))
		{
			array_push($carVal, 1);
		}
		if (in_array(2, $car_arr))
		{
			array_push($carVal, 1, 2, 3);
		}
		if (in_array(3, $car_arr))
		{
			array_push($carVal, 1, 3);
		}
		if (in_array(4, $car_arr))
		{
			array_push($carVal, 4);
		}
		if (in_array(5, $car_arr))
		{
			array_push($carVal, 5);
		}
		if (in_array(6, $car_arr))
		{
			array_push($carVal, 6);
		}
		if (in_array(7, $car_arr))
		{
			array_push($carVal, 7);
		}
		if (in_array(8, $car_arr))
		{
			array_push($carVal, 8);
		}
		if (in_array(9, $car_arr))
		{
			array_push($carVal, 9);
		}
		if (in_array(10, $car_arr))
		{
			array_push($carVal, 10);
		}
		if (in_array(11, $car_arr))
		{
			array_push($carVal, 11);
		}
		#print_r($carVal);
		return array_unique($carVal);
	}

	public function getRequestedListNew1($vendorId, $sort = '', $page_no = 0, $total_count = 0, $search_txt = '', $filter = null, $limitFlag = null)
	{
		$param	 = ['vendorId' => $vendorId];
		$sql	 = "SELECT vnd_cat_type,vnp_is_freeze,vnp_cod_freeze,vnp_accepted_zone,vnp_home_zone,vnp_excluded_cities, vnp_oneway, vnp_round_trip, vnp_multi_trip, vnp_airport, vnp_package, vnp_flexxi,
                            vnp_daily_rental,vnp_boost_enabled,IF(vnp_is_allowed_tier LIKE '%1%',1,0) value,IF(vnp_is_allowed_tier LIKE '%2%',2,0) valuePlus,
                            IF(vnp_is_allowed_tier LIKE '%3%',3,0) plus,IF(vnp_is_allowed_tier LIKE '%4%',4,0) selectTier
							FROM vendors INNER JOIN vendor_pref ON vnp_vnd_id = vnd_id WHERE vnd_id =:vendorId";
		$vndInfo = DBUtil::queryRow($sql, null, $param);

		$row			 = AccountTransDetails::getTotTransByVndId($vendorId);
		// get type of car for particular vendor
		$totTrans		 = ($row['totTrans'] > 0) ? $row['totTrans'] : 0;
		$vndIsFreeze	 = ($row['vnd_is_freeze'] > 0) ? $row['vnd_is_freeze'] : 0;
		$vndCodFreeze	 = ($row['vnd_cod_freeze'] > 0) ? $row['vnd_cod_freeze'] : 0;

		$vndInfo['vnp_accepted_zone']	 = ($vndInfo['vnp_accepted_zone'] == '') ? -1 : trim($vndInfo['vnp_accepted_zone'], ',');
		$vndInfo['vnp_excluded_cities']	 = ($vndInfo['vnp_excluded_cities'] == '') ? -1 : trim($vndInfo['vnp_excluded_cities'], ',');
		$vndInfo['vnp_home_zone']		 = ($vndInfo['vnp_home_zone'] == '') ? -1 : trim($vndInfo['vnp_home_zone'], ',');
		$vndBoostEnable					 = ($vndInfo['vnp_boost_enabled'] > 0) ? $vndInfo['vnp_boost_enabled'] : 0;
		$acptBidPercent					 = ($vndBoostEnable > 0) ? 3 : 5;

		$vndStatInfo = VendorStats::model()->fetchMetric($vendorId);

		$vndRating		 = ($vndStatInfo['vrs_vnd_overall_rating'] == null) ? 0 : $vndStatInfo['vrs_vnd_overall_rating'];
		$vndStickyScr	 = ($vndStatInfo['vrs_sticky_score'] == null) ? 4 : $vndStatInfo['vrs_sticky_score'];
		$vndPenaltyCount = $vndStatInfo['vrs_penalty_count'];
		$vndDriverApp	 = $vndStatInfo['vrs_driver_app_used'];
		$vndDependency	 = ($vndStatInfo['vrs_dependency'] == null) ? 0 : $vndStatInfo['vrs_dependency'];
		$vndBoostPercent = ($vndStatInfo['vrs_boost_percentage'] == null) ? 0 : $vndStatInfo['vrs_boost_percentage'];

		//$acptBidPercent  = ($vndBoostEnable > 0) ? (5-($vndBoostPercent*2*0.01)) : 5;
#echo $vndRating.'-'.$vndStickyScr.'-'.$vndPenaltyCount.'-'.$vndDriverApp.'-'.$vndDependency.'-'.$vndBoostPercent.'';exit;

		if ($vndIsFreeze > 0 && $row['vnp_low_rating_freeze'] <= 0 && $row['vnp_doc_pending_freeze'] <= 0 && $row['vnp_manual_freeze'] <= 0)
		{
			$vndIsFreeze = 0;
		}

		if ($vndInfo['vnd_cat_type'] == 1)
		{
			$sql = "SELECT GROUP_CONCAT(bkgnew.bkg_id)
					FROM   booking_cab bcb
					INNER JOIN booking ON  bcb.bcb_id = booking.bkg_bcb_id AND bkg_status IN (3, 5, 6, 7) AND bcb_vendor_id = $vendorId AND (bcb_start_time > NOW() OR bcb_end_time > NOW())
					INNER JOIN booking_cab bcbnew ON (bcbnew.bcb_start_time BETWEEN bcb.bcb_start_time AND bcb.bcb_end_time OR bcbnew.bcb_end_time BETWEEN bcb.bcb_start_time AND bcb.bcb_end_time)
					INNER JOIN booking bkgnew ON bkgnew.bkg_bcb_id = bcbnew.bcb_id AND bkgnew.bkg_status = 2 AND bkgnew.bkg_from_city_id 
					INNER JOIN zone_cities zct ON zct.zct_cty_id = bkgnew.bkg_from_city_id
					WHERE  bcb.bcb_active = 1 AND bcbnew.bcb_active = 1
					AND ((zct.zct_zon_id IN (" . $vndInfo['vnp_accepted_zone'] . ")
                          		AND zct.zct_cty_id NOT IN (" . $vndInfo['vnp_excluded_cities'] . ")) OR zct.zct_zon_id IN (" . $vndInfo['vnp_home_zone'] . "))";

			$excludeBookings = Yii::app()->db1->createCommand($sql)->querySCalar();
		}

		$excludeBookings = ($excludeBookings == null || $excludeBookings == '') ? -1 : $excludeBookings;

		if ($vndInfo['vnp_oneway'] != 1 && $vndInfo['vnp_oneway'] != -1)
		{
			$condSelectProfile .= " AND booking.bkg_booking_type NOT IN(1)";
		}
		if ($vndInfo['vnp_round_trip'] != 1 && $vndInfo['vnp_round_trip'] != -1)
		{
			$condSelectProfile .= " AND booking.bkg_booking_type NOT IN(2)";
		}
		if ($vndInfo['vnp_multi_trip'] != 1 && $vndInfo['vnp_multi_trip'] != -1)
		{
			$condSelectProfile .= " AND booking.bkg_booking_type NOT IN(3)";
		}
		if ($vndInfo['vnp_airport'] != 1 && $vndInfo['vnp_airport'] != -1)
		{
			$condSelectProfile .= " AND booking.bkg_booking_type NOT IN(4)";
		}
		if ($vndInfo['vnp_package'] != 1 && $vndInfo['vnp_package'] != -1)
		{
			$condSelectProfile .= " AND booking.bkg_booking_type NOT IN(5)";
		}
		if ($vndInfo['vnp_flexxi'] != 1 && $vndInfo['vnp_flexxi'] != -1)
		{
			$condSelectProfile .= " AND booking.bkg_booking_type NOT IN(6)";
		}
		if ($vndInfo['vnp_daily_rental'] != 1 && $vndInfo['vnp_daily_rental'] != -1)
		{
			$condSelectProfile .= " AND booking.bkg_booking_type NOT IN(9,10,11)";
		}
		$condSelectTierCheck		 = " AND service_class.scc_id IN ({$vndInfo['value']},{$vndInfo['valuePlus']},{$vndInfo['plus']},{$vndInfo['selectTier']}) ";
		$condSelectTierCheckMatched	 = " AND scc.scc_id IN ({$vndInfo['value']},{$vndInfo['valuePlus']},{$vndInfo['plus']},{$vndInfo['selectTier']}) ";

		$sqlIncludedBookings = "SELECT GROUP_CONCAT(bkg_id) FROM booking bkg
			                    INNER JOIN booking_trail ON btr_bkg_id = bkg_id AND btr_is_bid_started = 1
                                INNER JOIN zone_cities zct ON zct.zct_cty_id = bkg.bkg_from_city_id
                                WHERE bkg.bkg_pickup_date > NOW() AND bkg.bkg_status = 2 AND bkg.bkg_active = 1 AND bkg_id NOT IN($excludeBookings)
                                AND ((zct.zct_zon_id IN (" . $vndInfo['vnp_accepted_zone'] . ")
                                AND zct.zct_cty_id NOT IN (" . $vndInfo['vnp_excluded_cities'] . ")) OR zct.zct_zon_id IN (" . $vndInfo['vnp_home_zone'] . "))";

		$includeBookings = DBUtil::queryScalar($sqlIncludedBookings, DBUtil::SDB());
		$includeBookings = ($includeBookings == null || $includeBookings == '') ? -1 : $includeBookings;

		$search_qry = '';
		if ($search_txt != '')
		{
			$search_qry = " HAVING
				(
					cab_model LIKE '%$search_txt%'
					OR bcb_id LIKE '%$search_txt%'
					OR bkgIds LIKE '%$search_txt%'
					OR bkgBookingIds LIKE '%$search_txt%'
					OR bkg_route_name LIKE '%$search_txt%'
				)";
		}

		if ($sort == 'pk')
		{
			$sortCond = "ORDER BY bkg_pickup_date ASC";
		}
		else if ($sort == 'nk')
		{
			$sortCond = "ORDER BY bcb_id DESC";
		}
		else if ($sort == 'NF')
		{
			$sortCond = "ORDER BY booking_priority_date DESC";
		}

		if ($page_no >= 0)
		{
			if ($limitFlag != 2)
			{
				$offset		 = $page_no * 30;
				$limitCond	 = ($total_count == 0) ? " LIMIT 30 OFFSET $offset" : " ";
			}
			else
			{
				$limitCond = " ";
			}
		}

		if ($filter == null)
		{
			$filter_qry = " AND (bvr_id IS NULL OR bvr_accepted = 1)";
		}
		else
		{
			if (isset($filter['bid']) && $filter['bid'] == 1)
			{
				$isBid = true;

				$filter_qry = " AND (bvr_id IS NOT NULL AND bvr_accepted = 1)";
			}
			if (isset($filter['local']) && $filter['local'] == 1)
			{
				$isLocal	 = true;
				$filter_qry	 = " AND booking.bkg_booking_type IN(4,9,10,11,15)";

				if ($isBid && $isLocal)
				{
					$filter_qry = " AND ((bvr_id IS NOT NULL AND bvr_accepted = 1) OR booking.bkg_booking_type IN(4,9,10,11,15))";
				}
			}
			if (isset($filter['outstation']) && $filter['outstation'] == 1)
			{
				$isOutstaion = true;
				$filter_qry	 = " AND booking.bkg_booking_type IN(1,2,3,5,6,7,8)";
				if ($isBid && $isLocal && $isOutstaion)
				{
					$filter_qry = " AND ((bvr_id IS NOT NULL AND bvr_accepted = 1) OR booking.bkg_booking_type IN(4,9,10,11,15) OR booking.bkg_booking_type IN(1,2,3,5,6,7,8))";
				}

				if ($isBid && !$isLocal && $isOutstaion)
				{
					$filter_qry = " AND ((bvr_id IS NOT NULL AND bvr_accepted = 1) OR booking.bkg_booking_type IN(1,2,3,5,6,7,8))";
				}

				if (!$isBid && $isLocal && $isOutstaion)
				{
					$filter_qry = " AND (booking.bkg_booking_type IN(4,9,10,11,15) OR booking.bkg_booking_type IN(1,2,3,5,6,7,8))";
				}
			}
		}
		$val = '"';

		$acceptableAmount	 = "ROUND(booking_cab.bcb_vendor_amount * 0.01 * (100 - $acptBidPercent))";
		$calculateSMTSql	 = "CalculateSMT(bcb_vendor_amount + SUM(bkg_gozo_amount - IFNULL(bkg_credits_used,0)),booking_cab.bcb_vendor_amount,
					    $acceptableAmount, $vndRating, $vndStickyScr, $vndPenaltyCount, $vndDriverApp, $vndDependency, $vndBoostPercent)";

		$validateAcceptableAmountSQL = "IF(bkg_critical_assignment=1 OR bkg_manual_assignment=1, booking_cab.bcb_vendor_amount, IF($calculateSMTSql>0, $acceptableAmount, 0))";
		$sqlMain					 = "SET STATEMENT max_statement_time=10 FOR
			        SELECT   
			        0 AS matchType,
					IF(booking_pref.bkg_cng_allowed =1 AND (bkgaddinfo.bkg_num_large_bag < 2  OR bkgaddinfo.bkg_num_large_bag >1 ) ,1,0 ) AS is_cng_allowed,
					IF(booking.bkg_booking_type IN(4,9,10,11,15), 'local', 'outstation')AS businesstype,
					bcb_id,
					GROUP_CONCAT(DISTINCT bkg_id) bkgIds,
					GROUP_CONCAT(DISTINCT bkg_booking_id) bkgBookingIds,
					trim(replace(replace(replace(replace(`bkg_route_city_names`,'[',''),']',''),'$val',''),',','-')) AS bkg_route_name,  
					bcb_bid_start_time, btr.btr_manual_assign_date, btr.btr_critical_assign_date,
					GREATEST(COALESCE(bcb_bid_start_time,0), COALESCE(btr_manual_assign_date,0), COALESCE(btr_critical_assign_date,0)) as booking_priority_date,
					CASE bkg_booking_type WHEN 1 THEN IF(bcb_trip_type=1,'MATCHED','ONE WAY') WHEN 2 THEN 'ROUND TRIP' WHEN 3 THEN 'MULTI WAY' WHEN 4 THEN 'Airport Transfer' WHEN 5 THEN 'PACKAGE' WHEN 8 THEN 'PACKAGE' WHEN 9 THEN 'DAY RENTAL 4hr-40km' WHEN 10 THEN 'DAY RENTAL 8hr-80km' WHEN 11 THEN 'DAY RENTAL 12hr-120km' WHEN 12 THEN 'Airport Packages'  WHEN 15 THEN 'Local Transfer' ELSE 'SHARED' END AS booking_type,
					 IF($validateAcceptableAmountSQL>0,0,IF((DATE_ADD(NOW(), INTERVAL 13 HOUR) >= booking.bkg_pickup_date AND booking.bkg_reconfirm_flag=1 AND bkg_block_autoassignment=0),0,1)) AS is_biddable,
					IF(booking.bkg_agent_id > 0, 1, 0) AS is_agent,
					vehicle_category.vct_label AS cab_model,
					ROUND(booking_cab.bcb_vendor_amount * 0.98) AS recommended_vendor_amount,
					service_class.scc_label AS cab_lavel,
					booking_cab.bcb_vendor_amount AS max_bid_amount,
					(booking_cab.bcb_vendor_amount * 0.7) AS min_bid_amount,
					IF(vehicle_category.vct_id IN(5, 6),1,0) AS is_assured,
					MIN(booking.bkg_pickup_date) bkg_pickup_date,
					bkg_return_date,
					bcb_end_time trip_completion_time,
					$calculateSMTSql AS smtScore,
					$validateAcceptableAmountSQL AS acptAmount,
					IFNULL(bvr_bid_amount,0) AS bvr_bid_amount,
				    (CASE WHEN (($vndIsFreeze = 1) AND ($totTrans > 0) AND biv.bkg_advance_amount <=(biv.bkg_total_amount * 0.3)) THEN '1' WHEN($vndIsFreeze = 1) THEN '2' ELSE '0' END) AS payment_due,
					(
                    CASE (CASE WHEN (($vndIsFreeze = 1) AND ($totTrans > 0) AND biv.bkg_advance_amount <=(biv.bkg_total_amount * 0.3)) THEN '1' WHEN($vndIsFreeze = 1) THEN '2' ELSE '0' END) WHEN '1' THEN CONCAT(
                        'Your amount due is ',
                        ABS($totTrans),
                        '. Please send payment immediately'
                    ) WHEN '2' THEN 'Your Gozo Account is temporarily frozen. Please contact your Account Manager or Gozo Team to have it resolved.' WHEN '0' THEN ''
                    END
					) AS payment_msg,bkg_night_pickup_included,bkg_night_drop_included FROM booking
							  INNER JOIN booking_invoice biv ON biv.biv_bkg_id = booking.bkg_id
							  INNER JOIN booking_add_info bkgaddinfo ON  booking.bkg_id = bkgaddinfo.bad_bkg_id
							  INNER JOIN booking_pref ON bpr_bkg_id = booking.bkg_id
							  INNER JOIN booking_trail btr ON btr_bkg_id = booking.bkg_id
							  INNER JOIN svc_class_vhc_cat scv ON scv.scv_id = booking.bkg_vehicle_type_id
							  INNER JOIN vehicle_category ON vehicle_category.vct_id = scv.scv_vct_id
							  INNER JOIN service_class ON service_class.scc_id = scv.scv_scc_id
							  INNER JOIN booking_route ON brt_bkg_id = booking.bkg_id
							  INNER JOIN cities ct1 ON ct1.cty_id = booking_route.brt_from_city_id
							  INNER JOIN cities ct2 ON ct2.cty_id = booking_route.brt_to_city_id
							  INNER JOIN booking_cab ON bcb_id = bkg_bcb_id AND bcb_active = 1 AND bkg_id IN ($includeBookings)
							  LEFT JOIN booking_vendor_request ON bvr_bcb_id = bcb_id AND bvr_vendor_id = $vendorId  AND bvr_active = 1 
					WHERE  bcb_active = 1 $filter_qry $condSelectProfile $condSelectTierCheck
					GROUP BY bcb_id 
					$search_qry
          
					UNION

					SELECT 
					1 AS matchType,
					IF(bpr1.bkg_cng_allowed =1 AND (bkgaddinfo.bkg_num_large_bag < 2  OR bkgaddinfo.bkg_num_large_bag >1 ) ,1,0 ) AS is_cng_allowed,
					IF(booking.bkg_booking_type IN(4,9,10,11,15), 'local', 'outstation')AS businesstype,
					bcb_id,
					CONCAT(bsm.bsm_upbooking_id,', ',bsm.bsm_downbooking_id) bkgIds,
					CONCAT(booking.bkg_booking_id,', ',bkg.bkg_booking_id) bkgBookingIds,
					CONCAT(fc.cty_name,'-',tc.cty_name) bkg_route_name,
					bcb_bid_start_time, btr.btr_manual_assign_date, btr.btr_critical_assign_date, 
					GREATEST(COALESCE(bcb_bid_start_time,0), COALESCE(btr_manual_assign_date,0), COALESCE(btr_critical_assign_date,0)) as booking_priority_date,
					'MATCHED' AS booking_type, 
					IF($validateAcceptableAmountSQL>0,0,IF((DATE_ADD(NOW(), INTERVAL 13 HOUR) >= booking.bkg_pickup_date AND booking.bkg_reconfirm_flag=1  AND bpr1.bkg_block_autoassignment=0),0,1)) AS is_biddable,
					IF(booking.bkg_agent_id > 0 OR bkg.bkg_agent_id>0, 1, 0) AS is_agent,
					vehicle_category.vct_label AS cab_model,
					ROUND(bsm_vendor_amt_matched * 0.98) AS recommended_vendor_amount,
					scc.scc_label AS cab_lavel,
					bsm_vendor_amt_matched AS max_bid_amount,
					(bsm_vendor_amt_matched * 0.7) AS min_bid_amount,
					IF(vehicle_category.vct_id IN(5, 6),1,0) AS is_assured,
					MIN(booking.bkg_pickup_date) bkg_pickup_date,
					NULL AS bkg_return_date,
					bcb_end_time trip_completion_time,
					$calculateSMTSql AS smtScore,
					$validateAcceptableAmountSQL AS acptAmount,
					IFNULL(bvr_bid_amount,0) AS bvr_bid_amount,
					(CASE WHEN (($vndIsFreeze = 1) AND ($totTrans > 0) AND biv1.bkg_advance_amount <=(biv1.bkg_total_amount * 0.3)) THEN '1' WHEN($vndIsFreeze = 1) THEN '2' ELSE '0' END) AS payment_due,
					(
                    CASE (CASE WHEN (($vndIsFreeze = 1) AND ($totTrans > 0) AND biv1.bkg_advance_amount <=(biv1.bkg_total_amount * 0.3)) THEN '1' WHEN($vndIsFreeze = 1) THEN '2' ELSE '0' END) WHEN '1' THEN CONCAT(
                        'Your amount due is ',
                        ABS($totTrans),
                        '. Please send payment immediately'
                    ) WHEN '2' THEN 'Your Gozo Account is temporarily frozen. Please contact your Account Manager or Gozo Team to have it resolved.' WHEN '0' THEN ''
                    END
					) AS payment_msg,
				 	bkg_night_pickup_included,bkg_night_drop_included
					FROM
							   booking_smartmatch bsm 	
									INNER JOIN booking ON bkg_id = bsm.bsm_upbooking_id AND bsm.bsm_upbooking_id IN ($includeBookings)
									INNER JOIN booking_add_info bkgaddinfo ON  booking.bkg_id = bkgaddinfo.bad_bkg_id
									INNER JOIN booking_invoice biv1 ON biv1.biv_bkg_id = booking.bkg_id
									INNER JOIN booking_pref bpr1 ON bpr1.bpr_bkg_id = booking.bkg_id 
									INNER JOIN booking_trail btr ON btr_bkg_id = booking.bkg_id 
									INNER JOIN svc_class_vhc_cat ON svc_class_vhc_cat.scv_id = booking.bkg_vehicle_type_id
									INNER JOIN vehicle_category ON vehicle_category.vct_id = svc_class_vhc_cat.scv_vct_id
									INNER JOIN service_class scc ON scc.scc_id = svc_class_vhc_cat.scv_scc_id
									INNER JOIN cities fc ON fc.cty_id = booking.bkg_from_city_id 
									
									INNER JOIN booking bkg ON bkg.bkg_id = bsm.bsm_downbooking_id	AND bsm.bsm_downbooking_id IN ($includeBookings)
									INNER JOIN cities tc ON tc.cty_id = bkg.bkg_from_city_id 
									INNER JOIN booking_cab ON bcb_id = bsm.bsm_bcb_id AND bcb_active = 1
									LEFT JOIN booking_vendor_request ON bvr_bcb_id = bcb_id AND bvr_vendor_id = $vendorId AND bvr_active = 1 
					WHERE bcb_active = 1 AND bsm.bsm_ismatched = 0 $filter_qry $condSelectProfile $condSelectTierCheckMatched GROUP BY bcb_id 
					$search_qry
				    $sortCond 
				    $limitCond ";

		#echo $sqlMain.'###############################################################';exit;
		#Logger::create('SQL MAIN ===>' . $sqlMain, CLogger::LEVEL_TRACE);

		$result = DBUtil::query($sqlMain, DBUtil::SDB());
		return $result;
	}

	public function autoAssignVendorMatched()
	{

		$recordsets = $this->getAutoAssignMatchData();

		foreach ($recordsets as $value)
		{
			$result	 = $this->getTopMatch($value['upbkg'], $value['downbkg']);
			$trans	 = DBUtil::beginTransaction();
			try
			{
				if (!$result)
				{
					BookingPref::model()->setManualAssignMatched($value['upbkg']);
					continue;
				}

				if ($result['bsm_id'] > 0)
				{
					$upBooking				 = Booking::model()->findByPk($result['bsm_upbooking_id']);
					$upBooking->bkg_bcb_id	 = $result['bvr_bcb_id'];
					$upBooking->save();

					$downBooking			 = Booking::model()->findByPk($result['bsm_downbooking_id']);
					$downBooking->bkg_bcb_id = $result['bvr_bcb_id'];
					$downBooking->save();
					$result1				 = BookingCab::model()->assignVendor($result['bvr_bcb_id'], $result['bvr_vendor_id'], $result['bvr_bid_amount'], "Vendor AutoAssigned And Booking Matched (" . $result['bsm_upbooking_id'] . "," . $result['bsm_downbooking_id'] . ")", UserInfo::getInstance(), 0);
					if ($result1->isSuccess())
					{
						$this->assignVendor($result['bvr_bcb_id'], $result['bvr_vendor_id']);
						BookingSmartmatch::model()->deactivateAllPreMatchedBooking($result['bsm_upbooking_id'], $result['bsm_downbooking_id'], $result['bsm_id']);

						$desc					 = "Smart Match (Auto) booking " . $result['bsm_upbooking_id'] . " with " . $result['bsm_downbooking_id'] . " by System";
						$params['blg_ref_id']	 = BookingLog::REF_MATCH_FOUND;
						$userInfo				 = UserInfo::getInstance();
						BookingLog::model()->createLog($result['bsm_upbooking_id'], $desc, $userInfo, BookingLog::SMART_MATCH, false, $params, '', $result['bvr_bcb_id']);

						$desc = "Smart Match (Auto) booking " . $result['bsm_upbooking_id'] . " with " . $result['bsm_downbooking_id'] . " by System";
						BookingLog::model()->createLog($result['bsm_downbooking_id'], $desc, $userInfo, BookingLog::SMART_MATCH, false, $params, '', $result['bvr_bcb_id']);
						BookingTrail::updateProfitFlag($result['bvr_bcb_id']);
					}
				}
				else
				{
					$arrBvr = Explode(',', $result['bvr_id']);
					foreach ($arrBvr as $bvr)
					{
						$bookingVendorRequest	 = BookingVendorRequest::model()->findByPk($bvr);
						$result					 = BookingCab::model()->assignVendor($bookingVendorRequest->bvr_bcb_id, $bookingVendorRequest->bvr_vendor_id, $bookingVendorRequest->bvr_bid_amount, "Vendor AutoAssigned Without Match ", UserInfo::getInstance(), 1);
						if ($result->isSuccess())
						{
							BookingVendorRequest::model()->assignVendor($bookingVendorRequest->bvr_bcb_id, $bookingVendorRequest->bvr_vendor_id);

							BookingTrail::updateProfitFlag($bookingVendorRequest->bvr_bcb_id);
						}
					}
				}

				DBUtil::commitTransaction($trans);
			}
			catch (Exception $e)
			{
				Logger::exception($e);
				DBUtil::rollbackTransaction($trans);
			}
		}
	}

	public function autoAssignVendorNormal()
	{
		$recordsets = $this->getAutoAssignData();

		foreach ($recordsets as $value)
		{
			$transaction = DBUtil::beginTransaction();
			try
			{
				$calculatedTime		 = $value['calculatedTime'];
				$vendor_amount		 = $value['bcb_vendor_amount'];
				$bcb_id				 = $value['bcb_id'];
				$profit_amount		 = $value['gozoAmount'];
				$customerDue		 = $value['customerDue'];
				// $maxVendorAmount	 = $vendor_amount + $profit_amount;
				// $maxLossVendorAmount = ROUND($maxVendorAmount * 0.98);
				$maxVendorAmount	 = $vendor_amount + ($profit_amount * 0.9); // in the worst cast we're retaining 10% of the profit amount in our pocket atleast (10% of target_margin could be as little as 1-2% **worst case** )
				$maxLossVendorAmount = ROUND($maxVendorAmount * 0.98); // goal is to not make a loss in auto-assign at all as we're getting aggressive in doing manual triggers earlier and faster in the assignment lifecycle. Sooner we assign, lower the profits but also lower the need to make a loss
				//$bvrRecordBybcb      = $this->getRecordsBybcb($bcb_id,($maxVendorAmount-($maxVendorAmount*0.05)));
				//$acceptBidAmt = $vendor_amount+($vendor_amount*0.02);
				//if ($calculatedTime > $value['workingHoursPassed'])
				//{
				$result				 = $this->getVendorIdAutoAssigned($vendor_amount, $bcb_id, $maxVendorAmount, $maxLossVendorAmount, $customerDue);

				if (!$result)
				{
					//$vndIdAll= BookingVendorRequest::model()->getNotAutoAssignVendorsByBcb($bcb_id,$acceptBidAmt);
					BookingPref::model()->setManualAssignment($bcb_id);
					DBUtil::commitTransaction($transaction);
					continue;
				}
				//}
				$booking_id	 = $result['bvr_booking_id'];
				$remark		 = 'Vendor Auto Assigned';
				$vndId		 = $result['bvr_vendor_id'];
				$result		 = BookingCab::model()->assignVendor($bcb_id, $vndId, $result['bvr_bid_amount'], $remark, UserInfo::getInstance(), 1);
				if ($result->isSuccess())
				{
					$this->assignVendor($bcb_id, $vndId);
					BookingTrail::updateProfitFlag($bcb_id);
					DBUtil::commitTransaction($transaction);
				}
				else
				{
					throw $result->getException();
				}
			}
			catch (Exception $e)
			{
				Logger::exception($e);
				DBUtil::rollbackTransaction($transaction);
			}
		}
	}

	public function getNormalBookingTopVendor($bkg1, $bkg2)
	{
		$sql	 = "SELECT
				GROUP_CONCAT(bvr_id) bvr_id,
				0 bsm_id,
				GROUP_CONCAT(bkg_id) bkg_id,
				bcb_id,
				bvr_bcb_id,
				vnp_is_freeze,
				bvr_vendor_id,
				bvr_vendor_score,
				ROUND(SUM(bestBidRank)/count(bkg_id),1) bestBidRank,
				ROUND(SUM(bvr_vendor_rating)/count(bkg_id)) bvr_vendor_rating,
				SUM(bvr_bid_amount) bvr_bid_amount,
				maxLossVendorAmount,
				maxVendorAmount,
				vendor_amount
				FROM 
				(
				(SELECT 
				bkg_id,
				bvr_id,
				bcb_id,
				bvr_bcb_id,
				bvr_vendor_id,
				ROUND(bcb_vendor_amount * bvr_vendor_score/bvr_bid_amount,1) as bestBidRank,
				bvr_vendor_rating,
				bvr_vendor_score,
				bvr_bid_amount,
				(bkg_total_amount - bkg_advance_amount + bkg_refund_amount - bkg_credits_used) customerDue,
				vnp_is_freeze,
				bcb_vendor_amount vendor_amount,
				(bkg_total_amount  - bkg_service_tax - IF(agt_type = 2, ROUND(bkg_base_amount * agt_commission * 0.01), 0)) maxVendorAmount,
				ROUND((bkg_total_amount  - bkg_service_tax - IF(agt_type = 2, ROUND(bkg_base_amount * agt_commission * 0.01), 0)) * 0.98) maxLossVendorAmount
				FROM booking_vendor_request
				INNER JOIN booking ON bkg_id = bvr_booking_id AND bkg_status = 2
				INNER JOIN booking_cab ON bcb_id = bvr_bcb_id AND bvr_active = 1 AND bvr_bid_amount > 0 AND bvr_accepted = 1 AND bvr_assigned = 0 AND bcb_active = 1 AND bcb_trip_type = 0
				INNER JOIN vendors ON vnd_id = bvr_vendor_id AND vnd_active = 1
				INNER JOIN vendor_pref ON vnp_vnd_id = vnd_id
				INNER JOIN booking_invoice ON biv_bkg_id = bkg_id
				LEFT JOIN agents  ON bkg_agent_id = agt_id
				LEFT JOIN booking_smartmatch ON bsm_bcb_id = bvr_bcb_id
				WHERE bvr_booking_id IN($bkg1) AND bsm_id IS NULL
				AND vnp_is_freeze = 0 OR ((bkg_total_amount - bkg_advance_amount + bkg_refund_amount - bkg_credits_used) < bvr_bid_amount AND vnp_is_freeze = 1) 
				AND bvr_bid_amount < bcb_vendor_amount AND (bvr_vendor_rating IS NULL OR bvr_vendor_rating>=4)
				ORDER BY bestBidRank DESC, bvr_vendor_rating DESC, bvr_bid_amount ASC  LIMIT 1)

				UNION 

				(SELECT 
				bkg_id,
				bvr_id,
				bcb_id,
				bvr_bcb_id,
				bvr_vendor_id,
				ROUND(bcb_vendor_amount * bvr_vendor_score/bvr_bid_amount,1) as bestBidRank,
				bvr_vendor_rating,
				bvr_vendor_score,
				bvr_bid_amount,
				(bkg_total_amount - bkg_advance_amount + bkg_refund_amount - bkg_credits_used) customerDue,
				vnp_is_freeze,
				bcb_vendor_amount vendor_amount,
				(bkg_total_amount  - bkg_service_tax - IF(agt_type = 2, ROUND(bkg_base_amount * agt_commission * 0.01), 0)) maxVendorAmount,
				ROUND((bkg_total_amount  - bkg_service_tax - IF(agt_type = 2, ROUND(bkg_base_amount * agt_commission * 0.01), 0)) * 0.98) maxLossVendorAmount
				FROM booking_vendor_request
				INNER JOIN booking ON bkg_id = bvr_booking_id AND bkg_status = 2
				INNER JOIN booking_cab ON bcb_id = bvr_bcb_id AND bvr_active = 1 AND bvr_bid_amount > 0 AND bvr_accepted = 1 AND bvr_assigned = 0 AND bcb_active = 1 AND bcb_trip_type = 0
				INNER JOIN vendors ON vnd_id = bvr_vendor_id AND vnd_active = 1
				INNER JOIN vendor_pref ON vnp_vnd_id = vnd_id
				INNER JOIN booking_invoice ON biv_bkg_id = bkg_id
				LEFT JOIN agents  ON bkg_agent_id = agt_id
				LEFT JOIN booking_smartmatch ON bsm_bcb_id = bvr_bcb_id
				WHERE bvr_booking_id IN($bkg2) AND bsm_id IS NULL
				AND vnp_is_freeze = 0 OR ((bkg_total_amount - bkg_advance_amount + bkg_refund_amount - bkg_credits_used) < bvr_bid_amount AND vnp_is_freeze = 1) 
				AND bvr_bid_amount < bcb_vendor_amount AND (bvr_vendor_rating IS NULL OR bvr_vendor_rating>=4)
				ORDER BY bestBidRank DESC, bvr_vendor_rating DESC, bvr_bid_amount ASC  LIMIT 1)
				) b";
		$result	 = DBUtil::queryRow($sql);
		return $result;
	}

	public function getNotAutoAssignVendorsByBcb($bcb_id, $acceptBidAmt)
	{


		$sql	 = "SELECT bvr_vendor_id FROM `booking_vendor_request`"
				. "INNER JOIN booking_cab ON bcb_id=bvr_bcb_id"
				. "INNER JOIN booking ON bkg_bcb_id=bcb_id "
				. " WHERE `bvr_bcb_id` = $bcb_id AND bvr_active=1 AND bvr_bid_amount>0"
				. "  AND bvr_bid_amount >= $acceptBidAmt AND bvr_accepted=1 AND bvr_assigned =0";
		$result	 = DBUtil::queryAll($sql);
	}

	public function getRecordsBybcb($bcbID, $amount)
	{
		$sql	 = "SELECT *  FROM `booking_vendor_request` WHERE
`bvr_bcb_id` = $bcbID AND  bvr_bid_amount > 0 AND bvr_bid_amount= $amount AND bvr_vendor_rating >= 4  
ORDER BY `booking_vendor_request`.`bvr_vendor_rating` ASC";
		$result	 = DBUtil::queryRow($sql);
	}

	public static function autoVendorAssignments($tripid = '')
	{
		$returnSet	 = new ReturnSet();
		$recordsets	 = BookingVendorRequest::model()->getVendorAutoAssignments($tripid);

		Logger::info("autoVendorAssignments: Total records to be processed - " . $recordsets->getRowCount());
		$i = 0;

		foreach ($recordsets as $value)
		{
			$transaction = null;
			try
			{
				$bcbIsMaxOut	 = $value['bcb_is_max_out'];
				$vendor_amount	 = $value['bcb_vendor_amount'];
				$bcb_id			 = $value['bcb_id'];

				$revenueDetails	 = BookingCab::model()->getRevenueBreakup($bcb_id);
				$customerDue	 = ($revenueDetails['customerDue'] == '') ? 0 : $revenueDetails['customerDue'];
				$totalAdvance	 = $revenueDetails['totalAdvance'];

				if ($value['bkg_is_fbg_type'] == 1)
				{
					$maxVendorAmount = $vendor_amount;
				}
				else
				{
					$maxVendorAmount = ($revenueDetails['bcb_max_allowable_vendor_amount'] == 0) ? $vendor_amount : $revenueDetails['bcb_max_allowable_vendor_amount'];
				}

				Logger::info("Revenue: " . json_encode($revenueDetails) . "\n");

				$pickupTime	 = strtotime($revenueDetails["pickupDate"]);
				$allowedVA	 = $vendor_amount;

				$result = BookingVendorRequest::model()->getTopVendors($maxVendorAmount, $bcb_id, $allowedVA, $customerDue, '3.5', $totalAdvance);

				foreach ($result as $rowTopVendor)
				{
					Logger::info("getTopVendor({$maxVendorAmount}, {$bcb_id}, {$allowedVA}, {$customerDue})  - " . json_encode($rowTopVendor) . "\n");
					$remark		 = 'Vendor Auto Assigned';
					$smtScore	 = $rowTopVendor['bestBidRank'];
					$vndId		 = $rowTopVendor['bvr_vendor_id'];
					$bidID		 = $rowTopVendor['bvr_id'];

					$bcbModel = BookingCab::model()->findByPk($bcb_id);
					if (empty($bcbModel->bookings))
					{
						goto skipAssignment;
					}
					$bookingModel	 = $bcbModel->bookings[0];
					$bookingType	 = $bookingModel->bkg_booking_type;

					//check vendor service available or not
					if ($bookingType == 4 || $bookingType == 12)
					{
						$vendorService = VendorPref::checkApprovedService($vndId, $bookingType);
						if ($vendorService < 1)
						{
							goto skipAssignment;
						}
					}
					// validation for auto assigment
					$validateBidding = BookingVendorRequest::assignmentValidation($bcb_id, $rowTopVendor['bvr_vendor_id']);
					if ($validateBidding > 0)
					{
						goto skipAssignment;
					}
					$transaction	 = DBUtil::beginTransaction();
					$returnStatus	 = BookingCab::model()->assignVendor($bcb_id, $vndId, $rowTopVendor['bvr_bid_amount'], $remark, UserInfo::getInstance(), 0, $smtScore);

					//$result->notifyAssignVendor();
					if (!$returnStatus->isSuccess())
					{
						throw $returnStatus->getException();
					}
					$i++;
					Logger::info("BookingCab::model()->assignVendor: " . json_encode($returnStatus));

					self::updateAssignStatus($bidID);
					BookingTrail::updateProfitFlag($bcb_id);

					DBUtil::commitTransaction($transaction);
					if ($tripid != null && $returnStatus->isSuccess())
					{
						$returnSet->setStatus(true);
						$returnSet->setMessage("Vendor assign successsfully");
					}
					break;
					skipAssignment:
					DBUtil::rollbackTransaction($transaction);
					continue;
				}
			}
			catch (Exception $e)
			{
				DBUtil::rollbackTransaction($transaction);
				ReturnSet::setException($e);
				if ($tripid != null)
				{
					$returnSet->setStatus(false);
					$returnSet->setMessage("Failed to assign vendor");
				}
			}
		}
		Logger::info("autoVendorAssignments - Total booking auto assigned: $i");
		## Assign to EverestFleet
		BookingCab::assignToEverestFleet();

		if ($tripid != null)
		{
			return $returnSet;
		}
	}

	/** @return CDbDataReader */
	public function getVendorAutoAssignments($tripid = '')
	{
		$cond = '';
		if ($tripid > 0)
		{
			$cond = " AND bcb_id = {$tripid}";
		}
		$having = "HAVING ((workingHoursGiven/3 < workingHoursPassed AND  minBid<=bcb_vendor_amount) OR minBid<=bcb_vendor_amount * 0.95
						OR (workingHoursGiven/4 < workingHoursPassed AND (positiveBidCount/bidCount)<0.25 AND bidCount>=10)
						OR (workingHoursGiven/3 < workingHoursPassed AND (positiveBidCount/bidCount)>0.50 AND bidCount>=5)
						OR (GREATEST(ManualAssignment,CriticalAssignment)>0 AND minBid<bcb_max_allowable_vendor_amount)
						OR (bcb_is_max_out=1 and minBid<=bcb_max_allowable_vendor_amount)
						OR (criticalScore>=0.74)
						) 
					AND (pickupDate<=DATE_ADD(NOW(), INTERVAL 7 DAY) OR  (pickupDate>DATE_ADD(NOW(), INTERVAL 7 DAY) AND criticalScore>=0.6))";

		$sql = "SELECT bcb_is_max_out,bcb_id,MIN(booking.bkg_pickup_date) as pickupDate, MAX(bkg_create_date) as createDate,
					SUM(bkg_total_amount) as totalAmount, bcb_vendor_amount, bkg_is_fbg_type,
					CalcWorkingHour(MAX(bkg_create_date), MIN(booking.bkg_pickup_date)) as workingHoursGiven,
					TIMESTAMPDIFF(HOUR, MAX(bkg_create_date), NOW()) as hoursPassed,
					CalcWorkingHour(MAX(bkg_create_date), NOW()) as workingHoursPassed,
					CalcWorkingHour(NOW(), MIN(booking.bkg_pickup_date)) as workingHoursLeft,
					MIN(bvr_bid_amount) as minBid, MAX(bkg_manual_assignment) as ManualAssignment,
					COUNT(DISTINCT bvr_vendor_id) as bidCount,
					COUNT(DISTINCT IF(bvr_bid_amount<=bcb_vendor_amount, bvr_vendor_id, null)) as positiveBidCount,
					MAX(bkg_critical_assignment) as CriticalAssignment, MAX(bkg_critical_score) as criticalScore,
					booking_cab.bcb_max_allowable_vendor_amount      
				FROM booking_cab 
				INNER JOIN booking ON booking_cab.bcb_id=booking.bkg_bcb_id AND bkg_status IN (2) AND bkg_reconfirm_flag = 1 
						AND booking.bkg_pickup_date<DATE_ADD(NOW(), INTERVAL 30 DAY)
				INNER JOIN booking_invoice ON booking.bkg_id=booking_invoice.biv_bkg_id
				INNER JOIN booking_pref ON bpr_bkg_id=bkg_id AND bkg_block_autoassignment=0
				INNER JOIN booking_vendor_request ON bcb_id=bvr_bcb_id AND bvr_active=1 AND booking_vendor_request.bvr_assigned=0 AND bvr_accepted=1
				LEFT JOIN agents ON bkg_agent_id = agents.agt_id AND agents.agt_vendor_autoassign_flag = 1
				WHERE (agents.agt_id IS NOT NULL OR booking.bkg_agent_id IS NULL)
					$cond
				GROUP BY bcb_id 
				$having
		";

		$recordsets = DBUtil::query($sql, DBUtil::SDB());
		return $recordsets;
	}

	public function notifyRejectedVendor($bcbID = 0, $directAcpt = null)
	{
		$model		 = BookingCab::model()->findByPk($bcbID);
		$winVendor	 = $model->bcb_vendor_id;

		$getWinQuality	 = $this->getQualityScaleVendor($bcbID, $winVendor);
		$getIDs			 = self::getLostBidVendorList($winVendor, $bcbID);
		$bkgModel		 = $model->bookings;
		$scvId			 = $bkgModel[0]->bkgSvcClassVhcCat->scv_scc_id;
		$vhcModel		 = ($scvId == 4 || $scvId == 5) ? $bkgModel[0]->bkgSvcClassVhcCat->scv_label : $bkgModel[0]->bkgSvcClassVhcCat->scc_ServiceClass->scc_label;
		$cabModel		 = $bkgModel[0]->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label . '(' . $vhcModel . ')';
		$route			 = $bkgModel[0]->bkgFromCity->cty_full_name . ' to ' . $bkgModel[0]->bkgToCity->cty_full_name;
		if (count($getIDs) > 0)
		{
			foreach ($getIDs as $value)
			{

				$lostVendor		 = $value['bvr_vendor_id'];
				$getLossQuality	 = $this->getQualityScaleVendor($bcbID, $lostVendor);
				$getMsg			 = $this->buildMsg($getLossQuality, $getWinQuality, $bcbID);
				if ($getMsg)
				{
					//$msg = $getMsg;
				}
				else
				{
					$msg = "Your bid for TRIP ID: $bcbID did not win. The winner won with ₹$model->bcb_vendor_amount and got preference because"
							. " he has higher rating"
							. " and higher driver app usage";
				}
				$msg = BookingVendorRequest::showBidRankForLooser($bcbID, $value['bvr_vendor_id'], $winVendor, $model->bcb_vendor_amount);
				if ($directAcpt == 1)
				{
					$msg = "You lost the bid with Trip ID : $bcbID | $cabModel | $route. Booking was directly accepted by another operator";
				}
				$payLoadData = ['tripId' => $bcbID, 'EventCode' => Booking::CODE_VENDOR_BROADCAST];
				$success	 = AppTokens::model()->notifyVendor($value['bvr_vendor_id'], $payLoadData, $msg, "Booking not assigned");
				if ($success)
				{
					$this->updateNotificationFlag($value['bvr_vendor_id'], $bcbID);
				}
			}
		}
		else
		{
			$success = false;
		}

		return $success;
	}

	public function updateNotificationFlag($vndId, $bcbId)
	{
		$sql	 = "UPDATE booking_vendor_request SET bvr_app_notification = 1 WHERE bvr_bcb_id = '$bcbId' AND bvr_vendor_id = '$vndId'";
		$success = DBUtil::command($sql)->execute();
	}

	public static function getLostBidVendorList($winVendor, $bcbID)
	{
		$sql = "SELECT bvr_vendor_id FROM `booking_vendor_request`
		WHERE `bvr_vendor_id` <> $winVendor AND `bvr_bcb_id`=$bcbID AND `bvr_accepted`=1 AND `bvr_bid_amount`>0";
		$row = DBUtil::queryAll($sql);
		return $row;
	}

	public function getQualityScaleVendor($bcbID, $vndID)
	{

		$sql = "SELECT bvr_id,bvr_bid_amount,vrs_vnd_overall_rating,vrs_use_drv_app,vrs_drv_app_last10_trps
			FROM booking_vendor_request  
INNER JOIN vendor_stats ON vendor_stats.vrs_vnd_id = booking_vendor_request.bvr_vendor_id 
AND vendor_stats.vrs_vnd_id = $vndID
WHERE bvr_bcb_id= $bcbID AND bvr_vendor_id = $vndID";
		$row = DBUtil::queryRow($sql);
		return $row;
	}

	public function buildMsg($lossArr, $winArr, $bcbID)
	{
		$winBidAmt	 = $winArr['bvr_bid_amount'];
		$lostBidAmt	 = $lossArr['bvr_bid_amount'];

		$winRating	 = $winArr['vrs_vnd_overall_rating'];
		$lostRating	 = $lossArr['vrs_vnd_overall_rating'];

		$winDriverAppUsage	 = $winArr['vrs_use_drv_app'];
		$lostDriverAppUsage	 = $lossArr['vrs_use_drv_app'];

		$msg	 = "Your bid for TRIP: $bcbID did not win because winner ";
		$msgExt	 = "";
		if ($lostBidAmt > $winBidAmt)
		{
			$msgExt .= " has lower bid and";
		} if ($winRating > $lostRating)
		{
			$msgExt .= " has better rating and";
		}
		//if ($winDriverAppUsage > $lostDriverAppUsage)
		if (1 == 1)
		{
			$msgExt .= " uses driver app more regularly and";
		}
		$var = $msg . $msgExt;

		$msgText = substr($var, 0, -3);

		return $msgText . ".";
	}

	public static function getDemSupMisfireList()
	{

		$sql		 = "
			SELECT   bkg_id, bkg_bcb_id,  bvr.bvr_id,bkg.bkg_pickup_date,  btr.btr_bid_start_time,bcb.bcb_bid_start_time, now() curtimeval ,
			bpr.bkg_manual_assignment,
			GREATEST(DATE_ADD(btr.btr_bid_start_time, INTERVAL 2 HOUR), DATE_ADD(btr.btr_bid_start_time, INTERVAL (0.40 * (TIMESTAMPDIFF(HOUR, btr.btr_bid_start_time, bkg_pickup_date))) HOUR)) demsFireBtr,
			GREATEST(DATE_ADD(bcb.bcb_bid_start_time, INTERVAL 2 HOUR), DATE_ADD(bcb.bcb_bid_start_time, INTERVAL (0.40 * (TIMESTAMPDIFF(HOUR, bcb.bcb_bid_start_time, bkg_pickup_date))) HOUR)) demsFire,
				SUM(IF(bvr.bvr_id>0, 1, 0)) countBvr ,
				SUM(IF(bvr.bvr_bid_amount <= GREATEST(bcb.bcb_max_allowable_vendor_amount, bcb.bcb_vendor_amount)  AND bvr.bvr_bid_amount > 0,1,0)) countAcceptableBid,
				SUM(IF(bvr.bvr_bid_amount > GREATEST(bcb.bcb_max_allowable_vendor_amount, bcb.bcb_vendor_amount)  AND bvr.bvr_bid_amount > 0,1,0)) countOverRatedBid
		FROM     booking bkg
		 JOIN booking_cab bcb ON bcb.bcb_id = bkg.bkg_bcb_id AND bcb.bcb_active = 1
		 JOIN booking_pref bpr ON bpr.bpr_bkg_id = bkg.bkg_id  
		 JOIN booking_trail btr ON btr.btr_bkg_id = bkg.bkg_id AND btr.btr_is_bid_started = 1
			LEFT JOIN booking_vendor_request bvr ON  bvr.bvr_bcb_id = bcb_id  AND bvr.bvr_accepted=1
			WHERE    bkg.bkg_pickup_date> NOW() AND btr_is_dem_sup_misfire = 0 AND  bkg_status = 2 AND 
					GREATEST(DATE_ADD(bcb.bcb_bid_start_time, INTERVAL 2 HOUR), 
						DATE_ADD(bcb.bcb_bid_start_time, INTERVAL (0.40 * (TIMESTAMPDIFF(HOUR, bcb.bcb_bid_start_time, bkg_pickup_date))) HOUR),
						DATE_SUB(bkg_pickup_date, INTERVAL 72 HOUR)
					) < NOW()
 		GROUP BY bkg_id 
		HAVING countAcceptableBid = 0 ";
		$recordsets	 = DBUtil::queryAll($sql, DBUtil::SDB());
		return $recordsets;
	}

	public static function updateAssignStatus($id)
	{
		$bvrModel					 = BookingVendorRequest::model()->findByPk($id);
		$bvrModel->bvr_assigned		 = 1;
		$bvrModel->bvr_assigned_at	 = new CDbExpression('NOW()');
		$bvrModel->bvr_active		 = 1;
		$success					 = false;
		if ($bvrModel->save())
		{

			$success = true;
		}
		else
		{
			$error = $bvrModel->getErrors();
			throw new Exception(json_encode($error), 1);
		}
		return $success;
	}

	public static function getMaxBid($bkgId, $vendorid)
	{
		$sql = "
			SELECT max(bvr.bvr_bid_amount) max_bid
			FROM   booking_vendor_request bvr
			WHERE  bvr.bvr_booking_id = {$bkgId} 
				AND bvr.bvr_vendor_id = {$vendorid} 
				AND bvr.bvr_accepted = 1 ";
		return DBUtil::command($sql)->queryScalar();
	}

	public function getBidCountByBcb($bcbId)
	{
		$sql		 = "SELECT SUM(IF(bvr_accepted = 1,1,0)) totBidReceived,SUM(IF(bvr_accepted = 2,1,0)) totBidDenied FROM booking_vendor_request WHERE bvr_bcb_id = {$bcbId}";
		$bidCountArr = DBUtil::queryRow($sql);

		$bidFloated								 = DBUtil::command("SELECT btr_bid_floated_logged_id,btr_bid_floated FROM booking INNER JOIN booking_trail ON btr_bkg_id = bkg_id WHERE bkg_bcb_id = {$bcbId}")->queryRow();
		$bidCountArr['bidCountFloated']			 = $bidFloated['btr_bid_floated'];
		$bidCountArr['bidCountFloatedLoggedIn']	 = $bidFloated['btr_bid_floated_logged_id'];
		return $bidCountArr;
	}

	public function getVendorSMTScore($maxVendorAmount, $bcb_id, $allowedVendorAmount, $customerDue, $rating = '4')
	{
		#echo $maxVendorAmount."-".$bcb_id."-".$allowedVendorAmount."-".$customerDue."-".$rating = '4';

		$sql = "SELECT bvr_id,bvr_vendor_id, bvr_bid_amount, bvr_bcb_id, bvr_booking_id,
				    CalculateSMT((bcb_vendor_amount + SUM(bkg_gozo_amount - IFNULL(bkg_credits_used,0))), $allowedVendorAmount, bvr_bid_amount, vrs_vnd_overall_rating, vrs_sticky_score,vrs_penalty_count,vrs_driver_app_used,vrs_dependency,vrs_boost_percentage) as bestBidRank,
					(bcb_vendor_amount + SUM(bkg_gozo_amount - IFNULL(bkg_credits_used,0))) as ZeroProfitVA
				FROM booking_vendor_request
				INNER JOIN vendors ON vnd_id=bvr_vendor_id AND vnd_active=1
				INNER JOIN vendor_stats ON vnd_id=vrs_vnd_id
				INNER JOIN booking_cab ON bcb_id=bvr_bcb_id
				INNER JOIN booking ON bcb_id=bkg_bcb_id 
				INNER JOIN booking_invoice ON biv_bkg_id=bkg_id
				INNER JOIN vendor_pref ON vnp_vnd_id=vnd_id AND ((vendor_pref.vnp_low_rating_freeze =0 AND vendor_pref.vnp_doc_pending_freeze=0 AND vendor_pref.vnp_manual_freeze=0) 
				AND (bvr_bid_amount>IF(vnp_credit_limit_freeze=1,$customerDue,0) AND IF(vnp_cod_freeze=1,$customerDue,0)=0 AND vnp_is_freeze=0))
				WHERE bvr_bcb_id=$bcb_id AND bvr_accepted=1 AND bvr_bid_amount>0 AND bvr_bid_amount<$maxVendorAmount GROUP BY bvr_vendor_id 
				ORDER BY bestBidRank DESC, bvr_bid_amount ASC, bvr_vendor_rating DESC";

		$result = DBUtil::queryRow($sql);
		return $result;
	}

	public function getbidbyVnd($vndId)
	{

		$pageSize = 25;
		if ($viewType == 1)
		{
			$pageSize = 20;
		}
		$sql			 = "SELECT bkg.bkg_id, bkg.bkg_booking_id,bkg.bkg_bcb_id as trip_id,ctt.ctt_first_name,ctt.ctt_last_name, vnd.vnd_id,vnd.vnd_code,bvr.bvr_created_at, bvr.bvr_accepted ,
							if(bvr.bvr_bid_amount=0,'NA',bvr.bvr_bid_amount) as bvr_bid_amount,bvr.bvr_accepted_at,bvr.bvr_assigned
							FROM booking_vendor_request bvr
							INNER JOIN vendors vnd ON vnd.vnd_id = bvr.bvr_vendor_id AND vnd.vnd_active=1 and vnd.vnd_id = vnd.vnd_ref_code
							INNER JOIN contact_profile cp on cp.cr_is_vendor = vnd.vnd_id and cp.cr_status =1
							INNER JOIN contact ctt on ctt.ctt_id = cp.cr_contact_id and ctt.ctt_active =1 and ctt.ctt_id = ctt.ctt_ref_code
							INNER JOIN booking bkg ON bkg.bkg_id=bvr.bvr_booking_id AND bkg.bkg_active=1 AND bvr.bvr_vendor_id =$vndId ";
		$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc")->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => ['bvr_id'],
				'defaultOrder'	 => 'bvr_created_at  DESC'], 'pagination'	 => ['pageSize' => $pageSize],
		]);
		return $dataprovider;
	}

	public static function getBidStats($bcbId)
	{
		$sql	 = "SELECT max(bvr_bid_amount) AS maxBid,min(bvr_bid_amount) AS minBid,count(DISTINCT bvr_vendor_id) bidCount FROM `booking_vendor_request`	WHERE `bvr_bcb_id` = {$bcbId} AND bvr_accepted = 1	GROUP BY bvr_bcb_id";
		$bidArr	 = DBUtil::queryRow($sql, DBUtil::SDB());

		$bigAvgsql	 = "Select  AVG(bvr_bid_amount) from  booking_vendor_request where bvr_id IN (SELECT  (MAX(bvr_id)) as maxid	FROM `booking_vendor_request`	WHERE `bvr_bcb_id` = {$bcbId} AND bvr_accepted = 1 GROUP BY bvr_vendor_id)";
		$bidAvg		 = DBUtil::command($bigAvgsql, DBUtil::SDB())->queryScalar();

		$bidStatArr['avgBid']	 = $bidAvg;
		$bidStatArr['maxBid']	 = $bidArr['maxBid'];
		$bidStatArr['minBid']	 = $bidArr['minBid'];
		$bidStatArr['bidCount']	 = $bidArr['bidCount'];
		return $bidStatArr;
	}

	/**
	 * function showBidRank to show bid rank
	 * @param type $bookingId
	 * @param type $vendorId
	 * @return string
	 */
	public static function showBidRank($bookingId, $vendorId)
	{

		//boost enable

		$boostStatus	 = BookingVendorRequest::model()->checkBoostStatus($vendorId);
		$winnerStatus	 = BookingVendorRequest::model()->checkHighestStatus($bookingId);
		$winning_amount	 = $winnerStatus['winning_amount'];
		$winner_score	 = $winnerStatus['bvr_vendor_score'];
		if ($winning_amount != "")
		{
			$reqArr = BookingVendorRequest::vendorOwnRank($bookingId, $vendorId);

			if (!empty($reqArr))
			{

				$bidDiffernece	 = $reqArr['bvr_bid_amount'] - $winning_amount;
				$status			 = ($bidDiffernece < 0 ? 'higher' : 'lower');
				if ($bidDiffernece > 0)
				{
					$winnerMsg = ", Winning bid is ₹" . $bidDiffernece . ' ' . $status;
				}
				if ($winner_score != null || $winner_score != "")
				{
					$winner_score = " and winning partner performance score is " . $winner_score;
				}


				if ($boostStatus == 1)
				{
					$bidRank = ($reqArr['bid_rank'] < 4 ? "is in top 3" : $reqArr['bid_rank']);
					if ($reqArr['bid_rank'] == 1)
					{
						$msg = "Your bid rank " . $bidRank . " Bid rank will keep changing as other bids come in";
					}
					else
					{
						$msg = "Your bid rank " . $bidRank . "" . $winnerMsg . "" . $winner_score;
					}
				}
				else
				{
					$msg = "Only Partners who have Gozo boost can see bid rank. Get Gozo Boost now!";
				}
			}
			else
			{
				$msg = "You have no active bid";
			}
		}
		#echo $msg;
		return $msg;
	}

	public static function showBidRankMessage($bookingId, $vendorId)
	{

		//boost enable
		$getBkgId		 = BookingCab::getBkgIdByTripId($tripId);
		$bookingIds		 = explode(",", $getBkgId[bkg_ids]);
		$bookingId		 = $bookingIds[0];
		$boostStatus	 = BookingVendorRequest::model()->checkBoostStatus($vendorId);
		$winnerStatus	 = BookingVendorRequest::model()->checkHighestStatus($bookingId);

		$winning_amount	 = $winnerStatus['winning_amount'];
		$winner_score	 = $winnerStatus['bvr_vendor_score'];
		if ($winning_amount != "")
		{
			$reqArr = BookingVendorRequest::vendorOwnRank($bookingId, $vendorId);

			if (!empty($reqArr))
			{

				$bidDiffernece	 = $reqArr['bvr_bid_amount'] - $winning_amount;
				$status			 = ($bidDiffernece < 0 ? 'higher' : 'lower');
				if ($bidDiffernece > 0)
				{
					$winnerMsg = ", Winners bid is ₹ " . $bidDiffernece . ' ' . $status;
				}
				if ($winner_score != null || $winner_score != "")
				{
					$winner_score = ", Winner performance score is " . $winner_score;
				}


				if ($boostStatus == 1)
				{

					$msg = "Your bid rank is " . $reqArr['bvr_rank'] . "" . $winnerMsg . "" . $winner_score;
				}
				else
				{
					$msg = "Get Gozo boost to see your bid rank";
				}
			}
			else
			{
				$msg = "You have no active bid";
			}
		}
		//echo $msg;
		return $msg;
	}

	public static function showBidRankAfterBidalocation($tripId, $vendorId)
	{
		$getBkgId			 = BookingCab::getBkgIdByTripId($tripId);
		$bookingIds			 = explode(",", $getBkgId[bkg_ids]);
		$bookingId			 = $bookingIds[0];
		$bookingRouteName	 = BookingRoute::model()->getRouteNameByBookingId($bookingId);
		$boostStatus		 = BookingVendorRequest::model()->checkBoostStatus($vendorId);
		$winnerStatus		 = BookingVendorRequest::model()->checkWinnerStatus($bookingId);
		$winnerBoostStatus	 = BookingVendorRequest::model()->checkBoostStatus($winnerStatus['bvr_vendor_id']);
		$winning_amount		 = $winnerStatus['winning_amount'];
		$winner_score		 = $winnerStatus['bvr_vendor_score'];

		$reqArr = BookingVendorRequest::vendorOwnRank($bookingId, $vendorId);
		if (!empty($reqArr))
		{
			$bidDiffernece = $reqArr['bvr_bid_amount'] - $winning_amount;

			if ($winner_score >= 1)
			{
				$wining_msg = "and his performance score is $winner_score";
			}
			$status = ($bidDiffernece < 0 ? 'higher' : 'lower');
			if ($boostStatus == 0)
			{ //non boosted vendor
				if ($reqArr['bvr_assigned'] != 1)
				{
					switch ($winnerBoostStatus)
					{
						case 0:
							//$msg = "you lost TripId " . $tripId . " from " . $bookingRouteName . ", Winners bid is " . $status . " $wining_msg"." Get Gozo boost to increase your chance to win bids";
							$msg = "You lost Trip Id " . $tripId . " from " . $bookingRouteName . ", get Gozo boost to increase your chance to win bids";
							break;

						case 1:

							$msg = "You lost TripId " . $tripId . " from " . $bookingRouteName . ", winners has Gozo Boost, get Gozo boost to increase your chance to win bids";
							//$msg = "You lost Trip Id " . $trip Id . " from " . $bookingRouteName . ", winners has Gozo Boost, Get Gozo boost to increase your chance to win bids";
							break;
					}
				}
				else
				{
					$msg = "You won TRIPID " . $tripId . " from " . $bookingRouteName . " because your bid is lower.";
				}
			}
			else
			{   // boosted vendor
				if ($reqArr['bvr_assigned'] != 1)
				{
					switch ($winnerBoostStatus)
					{
						case 0:

							//$msg = "you lost TripId " . $reqArr['tripId'] . " from " . $bookingRouteName . ", Your bid rank was " . $reqArr['bvr_rank'] . ", Winners bid was  " . $winning_amount . ' ' . $status . "  $wining_msg";
							$msg = "You lost Trip Id " . $reqArr['tripId'] . " from " . $bookingRouteName . ", your bid rank was " . $reqArr['bvr_rank'] . " ";
							break;

						case 1:

							//$msg = "you lost TripId " . $reqArr['tripId'] . " from " . $bookingRouteName . ", Your bid rank was " . $reqArr['bvr_rank'] . ", Winners bid was  " . $winning_amount . ' ' . $status . " $wining_msg Winner also has Gozo Boost";
							$msg = "You lost Trip Id " . $reqArr['tripId'] . " from " . $bookingRouteName . ", your bid rank was " . $reqArr['bvr_rank'] . ", winner also has Gozo Boost";
							break;
					}
				}
				else
				{

					$msg = "You won TRIPID " . $reqArr['tripId'] . " from " . $bookingRouteName . " because you have Gozo Boost (car sticker).";
				}
			}
		}
		else
		{
			$msg = "You have no active bid";
		}

		echo $msg;
	}

	public function showBidRankForWinner($tripId, $vendorId)
	{
		$getBkgId			 = BookingCab::getBkgIdByTripId($tripId);
		$bookingIds			 = explode(",", $getBkgId[bkg_ids]);
		$bookingId			 = $bookingIds[0];
		$bookingRouteName	 = BookingRoute::model()->getRouteNameByBookingId($bookingId);
		$boostStatus		 = BookingVendorRequest::model()->checkBoostStatus($vendorId);
		switch ($boostStatus)
		{
			case 0:
				$msg = "You won TRIPID " . $tripId . " from " . $bookingRouteName . " because your bid rank is best.";
				break;

			case 1:

				$msg = "You won TRIPID " . $tripId . " from " . $bookingRouteName . " because you have Gozo Boost (car sticker).";
				break;
		}
		return $msg;
	}

	public function showBidRankForLooser($tripId, $vendorId, $winnerId, $winning_amount = null)
	{
		$getBkgId	 = BookingCab::getBkgIdByTripId($tripId);
		$bookingIds	 = explode(",", $getBkgId[bkg_ids]);
		$bookingId	 = $bookingIds[0];
		if ($bookingId != "")
		{
			$bookingRouteName	 = BookingRoute::model()->getRouteNameByBookingId($bookingId);
			$boostStatus		 = BookingVendorRequest::model()->checkBoostStatus($vendorId);
			$winnerStatus		 = BookingVendorRequest::model()->winnerDetails($bookingId, $winnerId);
			$winnerBoostStatus	 = BookingVendorRequest::model()->checkBoostStatus($vendorId);
			$winner_score		 = $winnerStatus['bvr_vendor_score'];

			$reqArr = BookingVendorRequest::vendorOwnRank($bookingId, $vendorId);
			if (!empty($reqArr))
			{
				$bidDiffernece = $reqArr['bvr_bid_amount'] - $winning_amount;

				if ($winner_score >= 1)
				{
					$wining_msg = "and his performance score is $winner_score.";
				}
				$status = ($bidDiffernece < 0 ? 'higher' : 'lower');
			}

			if ($boostStatus == 0)
			{ //non boosted vendor
				switch ($winnerBoostStatus)
				{
					case 0:
						$msg = "You lost Trip Id " . $reqArr['tripId'] . " from " . $bookingRouteName . ", your bid rank was " . $reqArr['bvr_rank'] . "";
						break;

					case 1:

						//$msg = "You lost Trip Id " . $tripId . " from " . $bookingRouteName . ", winner has Gozo Boost";
						$msg = "You lost Trip Id " . $reqArr['tripId'] . " from " . $bookingRouteName . ", your bid rank was " . $reqArr['bvr_rank'] . ", winner has Gozo Boost";
						break;
				}
			}
			else
			{ //boosted vendor
				switch ($winnerBoostStatus)
				{
					case 0:

						//$msg = "You lost TripID " . $reqArr['tripId'] . " from " . $bookingRouteName . ", Your bid rank was " . $reqArr['bvr_rank'] . ", Winners bid was  ₹" . $bidDiffernece . ' ' . $status . "  $wining_msg";
						$msg = "You lost Trip Id " . $reqArr['tripId'] . " from " . $bookingRouteName . ", your bid rank was " . $reqArr['bvr_rank'] . "";
						break;

					case 1:

						//$msg = "You lost TripID " . $reqArr['tripId'] . " from " . $bookingRouteName . ", Your bid rank was " . $reqArr['bvr_rank'] . ", Winners bid was  ₹" . $bidDiffernece . ' ' . $status . " $wining_msg .Winner also has Gozo Boost.";
						$msg = "You lost Trip Id " . $reqArr['tripId'] . " from " . $bookingRouteName . ", your bid rank was " . $reqArr['bvr_rank'] . " . Winner also has Gozo Boost. ";
						break;
				}
			}
		}
		return $msg;
	}

	public function checkBoostStatus($vendorId)
	{
		$param1 = ['vendorId' => $vendorId];

		$sql1			 = "SELECT vnp_boost_enabled FROM `vendor_pref` WHERE `vnp_vnd_id` = :vendorId ";
		$status			 = DBUtil::queryRow($sql1, null, $param1);
		$boostEnabled	 = $status['vnp_boost_enabled'];
		return $boostEnabled;
	}

	public static function checkHighestStatus($bookingId)
	{
		$params	 = ['bkgid' => $bookingId];
		$sql	 = "SELECT min(bvr_bid_amount) as winning_amount,bvr_vendor_score FROM `booking_vendor_request` WHERE `bvr_booking_id` = :bkgid AND bvr_accepted =1";
		$bidStat = DBUtil::queryRow($sql, null, $params);
		return $bidStat;
	}

	public static function checkWinnerStatus($bookingId)
	{
		$params	 = ['bkgid' => $bookingId];
		$sql	 = "SELECT bvr_vendor_id,bvr_bid_amount as winning_amount,bvr_vendor_score FROM `booking_vendor_request` WHERE `bvr_booking_id` = :bkgid AND bvr_accepted =1 AND bvr_assigned=1";
		$bidStat = DBUtil::queryRow($sql, null, $params);
		return $bidStat;
	}

	public static function winnerDetails($bookingId, $vendorId)
	{
		$params	 = ['bkgid' => $bookingId, 'vendorId' => $vendorId];
		$sql	 = "SELECT bvr_vendor_id,bvr_bid_amount as winning_amount,bvr_vendor_score FROM `booking_vendor_request` WHERE `bvr_booking_id` = :bkgid AND bvr_vendor_id = :vendorId ";
		$bidStat = DBUtil::queryRow($sql, null, $params);
		return $bidStat;
	}

	/**
	 * function used to show vendor rank
	 * @param type $bookingId
	 * @param type $vendorId
	 * @return type queryRow
	 */
	public static function vendorOwnRank($bookingId, $vendorId)
	{
		$sql = "SELECT * FROM (
			select bvr_bid_amount,bvr_bcb_id,bvr_vendor_id,bvr_booking_id,bvr_vendor_rating,bvr_vendor_score,bvr_assigned,
                                RANK() over (
                                PARTITION BY bvr_booking_id
                                ORDER BY bvr_bid_amount ASC
                                ) bid_rank
                                from booking_vendor_request
                                WHERE 
                                bvr_booking_id =$bookingId AND bvr_accepted =1) AS tmpRank WHERE bvr_vendor_id = $vendorId";

		$arr = DBUtil::queryRow($sql, DBUtil::SDB());
		return $arr;
	}

	public function allVendorRank($bookingId)
	{
		$unasignedVendor = BookingLog::UnassignedVendors($bookingId);

		if ($unasignedVendor <> '')
		{
			$uassignVendor = "AND bvr_vendor_id not in($unasignedVendor)"; //remove manually unassign vendor
		}


		$sql = "select bvr_bid_amount,bvr_bcb_id,bvr_vendor_id,bvr_booking_id,bvr_vendor_rating,bvr_vendor_score,bvr_assigned,
                                RANK() over (
                                PARTITION BY bvr_booking_id
                                ORDER BY bvr_bid_amount ASC
                                )bid_rank
                                from booking_vendor_request
                                WHERE 
                                bvr_booking_id =$bookingId AND bvr_accepted =1 $uassignVendor GROUP BY bvr_vendor_id ORDER BY bid_rank ";

		$bidArr = DBUtil::queryAll($sql, DBUtil::SDB());

		foreach ($bidArr as $k => $arr)
		{

			//vendorname
			//$model = Booking::model()->findByPk($id);
			$vendorModel = Vendors::model()->findByPk($arr['bvr_vendor_id']);
			#print_r($vendorModel['vnd_id']);

			$reqArr[] = [
				'bvr_vendor_rating'	 => $arr['bvr_vendor_rating'],
				'bvr_bid_amount'	 => $arr['bvr_bid_amount'],
				'bvr_rank'			 => $arr['bid_rank'],
				'vendor_id'			 => $vendorModel['vnd_id'],
				'bvr_assigned'		 => $arr['bvr_assigned'],
				'vendor_name'		 => $vendorModel['vnd_name'],
			];
		}


		return $reqArr;
	}

	// smt score update
	public static function updateSMTByTripId($bcbID, $tripVendorAmount = null)
	{
		$rows = self::getActiveListByTripId($bcbID);
		foreach ($rows as $value)
		{
			$vendorId	 = $value['bvr_vendor_id'];
			$success	 = BookingVendorRequest::updateSMTScore1($bcbID, $vendorId, $tripVendorAmount);
		}
	}

	/**
	 * Get Vendor Bid statistics data for particular trip
	 * (Max Vendor Amount and other parameters will be extracted from trip id)
	 * @param int $vendorId
	 * @param int $tripId
	 * @return array bvr_id, bvr_vendor_id, bvr_bid_amount, bvr_bcb_id, bvr_booking_id, bestBidRank, ZeroProfitVA
	 */
	public static function getBidScoresByVendor($vendorId, $tripId, $tripVendorAmount = null)
	{
		$revenueDetails	 = BookingCab::model()->getRevenueBreakup($tripId);
		Logger::trace("BookingCab::model()->getRevenueBreakup($tripId) :: " . json_encode($revenueDetails));
		$vendorAmount	 = ($tripVendorAmount == null) ? $revenueDetails["bcb_vendor_amount"] : $tripVendorAmount;
		$customerDue	 = ($revenueDetails['customerDue'] == '') ? 0 : $revenueDetails['customerDue'];
		//$maxVendorAmount = ($revenueDetails['bcb_max_allowable_vendor_amount'] == 0) ? $vendorAmount : $revenueDetails['bcb_max_allowable_vendor_amount'];

		$maxVendorAmount = $revenueDetails['bcb_max_allowable_vendor_amount'];
		if ($maxVendorAmount == 0 || $maxVendorAmount == null)
		{
			$maxVendorAmount = $vendorAmount;
		}
		$result = BookingVendorRequest::fetchBidScoresByVendor($vendorId, $tripId, $vendorAmount);
		return $result;
	}

	/**
	 * Get Vendor Bid statistics data for particular trip 
	 * @param int $vendorId 
	 * @param int $tripId
	 * @return array bvr_id, bvr_vendor_id, bvr_bid_amount, bvr_bcb_id, bvr_booking_id, bestBidRank, ZeroProfitVA
	 */
	public static function fetchBidScoresByVendor($vendorId, $tripId, $allowedVendorAmount)
	{
		$sql = "SELECT bvr_id,bvr_vendor_id, bvr_bid_)),amount, bvr_bcb_id, bvr_booking_id,
		    CalculateSMT((bcb_vendor_amount + SUM(bkg_gozo_amount - IFNULL(bkg_credits_used,0))), $allowedVendorAmount, bvr_bid_amount, 
				    vrs_vnd_overall_rating, vrs_sticky_score, vrs_penalty_count,
				    vrs_driver_app_used,vrs_dependency,vrs_boost_percentage) as bestBidRank,
		    (bcb_vendor_amount + SUM(bkg_gozo_amount - IFNULL(bkg_credits_used,0))) as ZeroProfitVA
		FROM booking_vendor_request
		INNER JOIN vendors ON vnd_id=bvr_vendor_id AND vnd_active=1
		INNER JOIN vendor_stats ON vnd_id=vrs_vnd_id
		INNER JOIN booking_cab ON bcb_id=bvr_bcb_id
		INNER JOIN booking ON bcb_id=bkg_bcb_id 
		INNER JOIN booking_invoice ON biv_bkg_id=bkg_id
		INNER JOIN vendor_pref ON vnp_vnd_id=vnd_id AND ((vendor_pref.vnp_low_rating_freeze =0 AND vendor_pref.vnp_doc_pending_freeze=0 AND vendor_pref.vnp_manual_freeze=0))
		WHERE bvr_bcb_id=$tripId AND bvr_accepted=1 AND bvr_bid_amount>0 AND bvr_vendor_id = $vendorId";

		$result = DBUtil::queryRow($sql);
		return $result;
	}

	/**
	 * this function is used for add data in scheduler
	 * @param type $tripId
	 * @param type $vendorId
	 * @param type $tripVendorAmount
	 * @return type
	 */
	public static function scheduleSMTScore($tripId, $vendorId = null, $tripAmount = null)
	{
		if ($tripAmount == "")
		{
			return;
		}
		$bookingCabModel		 = BookingCab::model()->findByPk($tripId);
		$bkgIds					 = explode(',', $bookingCabModel->bcb_bkg_id1);
		$bookingId				 = $bkgIds[0];
		$biddata				 = BookingCab::fetchVendorRelatedAmount($tripId);
		$biddata['vendorId']	 = $vendorId;
		$biddata['bidAmount']	 = ($tripAmount == null ? 0 : $tripAmount);
		$jsonData				 = json_encode($biddata);
		$bseRemarks				 = "SMT related data";
		BookingScheduleEvent::add($bookingId, BookingScheduleEvent::SMT_SCORE_PROCESS, $bseRemarks, $jsonData);
	}

	/**
	 * This function use for update SMT from scheduler
	 * @param type $tripId
	 * @param type $vendorId
	 * @param type $tripVendorAmount
	 * @param type $maxAllowedVendorAmount
	 * @return boolean
	 */
	public static function updateSMTScore($tripId, $vendorId, $bidAmount, $tripAmount, $maxAllowedAmount)
	{
		$result = false;

		$vendorData			 = VendorStats:: fetchMetric($vendorId);
		#$bidamt			 = BookingVendorRequest::calculateBidAmount($vendorId, $tripId);
		$modelVendPref		 = VendorPref::model()->find('vnp_vnd_id=:id', ['id' => $vendorId]);
		$rating				 = ($vendorData['vrs_vnd_overall_rating'] == null ? 0 : $vendorData['vrs_vnd_overall_rating']);
		$score				 = ($vendorData['vrs_sticky_score'] == null ? 0 : $vendorData['vrs_sticky_score']);
		$dependency			 = ($vendorData['vrs_dependency'] == null ? 0 : $vendorData['vrs_dependency']);
		$bcbVendorAmount	 = $tripAmount;
		$acceptableAmount	 = $bidAmount;

		//$calculateSMTSql = "SELECT CalculateSMT(:maxAllowedAmount, :allowedVendorAmount,:acceptableAmount,:vrsRating, :vrsStickyScr, :vrsPenaltyCount,:vrsDriverApp,:vrsDependency,:vrsBoostPercent) as smt";

		$params = ['maxAllowedAmount'	 => $maxAllowedAmount, 'bcbVendorAmount'	 => $bcbVendorAmount, 'acceptableAmount'	 => $acceptableAmount,
			'vrsRating'			 => $rating, 'vrsStickyScr'		 => $score, 'vrsPenaltyCount'	 => $vendorData['vrs_penalty_count'],
			'vrsDriverApp'		 => $vendorData['vrs_driver_app_used'], 'vrsDependency'		 => $dependency, 'vrsBoostPercent'	 => $vendorData['vrs_boost_percentage']];

		$calculateSMTSql = "SELECT CalculateSMT(:maxAllowedAmount, :bcbVendorAmount, :acceptableAmount,:vrsRating, :vrsStickyScr, :vrsPenaltyCount,:vrsDriverApp,:vrsDependency,:vrsBoostPercent) AS smt";

		$result		 = DBUtil::queryRow($calculateSMTSql, null, $params);
		$smtScore	 = $result['smt'];

		$param	 = ["vendorId" => $vendorId, "tripId" => $tripId, 'smtScore' => $smtScore];
		$sql	 = "UPDATE booking_vendor_request SET bvr_smt_score=:smtScore WHERE bvr_bcb_id=:tripId AND bvr_accepted=1 AND bvr_bid_amount>0 AND bvr_vendor_id=:vendorId";

		$result = DBUtil::execute($sql, $param);
		return true;
	}

	/**
	 * function copyPrvBid
	 * @param type $oldBcbId
	 * @param type $newBcbId
	 */
	public static function copyPrvBid($oldBcbId, $newBcbId, $vendorId)
	{
		$rows = self::getOldBids($oldBcbId, $vendorId);
		foreach ($rows as $result)
		{
			try
			{


				/* $sql = "INSERT INTO booking_vendor_request(bvr_booking_id, bvr_bcb_id, bvr_vendor_id, bvr_vendor_rating, bvr_vendor_score, bvr_bid_amount, bvr_created_at, bvr_accepted, bvr_accepted_at, bvr_smt_score, bvr_active,bvr_special_remarks,bvr_is_gozonow,bvr_)
				  VALUES ('" . $result['bvr_booking_id'] . "','" . $newBcbId . "'," . $result['bvr_vendor_id'] . "," . $result['bvr_vendor_rating'] . "," . $result['bvr_vendor_score'] . "," . $result['bvr_bid_amount'] . ", '" . $result['bvr_created_at'] . "', 1, '" . $result['bvr_accepted_at'] . "' , " . $result['bvr_smt_score'] . ",1,'" . $result['bvr_special_remarks'] . "' ," . $result['bvr_is_gozonow'] . ")";

				  DBUtil::execute($sql); */
				$model = new BookingVendorRequest();

				$model->bvr_booking_id		 = (int) $result['bvr_booking_id'];
				$model->bvr_bcb_id			 = $newBcbId;
				$model->bvr_vendor_id		 = $result['bvr_vendor_id'];
				$model->bvr_vendor_rating	 = $result['bvr_vendor_rating'];
				$model->bvr_vendor_score	 = $result['bvr_vendor_score'];
				$model->bvr_bid_amount		 = $result['bvr_bid_amount'];
				$model->bvr_created_at		 = $result['bvr_created_at'];
				$model->bvr_accepted		 = 1;
				$model->bvr_accepted_at		 = $result['bvr_accepted_at'];
				$model->bvr_smt_score		 = ($result['bvr_smt_score'] == NULL ? 0 : $result['bvr_smt_score'] );
				$model->bvr_special_remarks	 = $result['bvr_special_remarks'];
				$model->bvr_is_gozonow		 = $result['bvr_is_gozonow'];
				$model->bvr_snooze_time		 = $result['bvr_snooze_time'];
				if (!$model->save())
				{
					throw new Exception('copy bid request not set');
				}

				$eventId = BookingLog::BID_SET;
				$desc	 = "Bid re-activated ₹" . $result['bvr_bid_amount'];

				$userInfo			 = UserInfo::getInstance();
				$userInfo->userType	 = UserInfo::TYPE_VENDOR;

				$params['blg_vendor_id'] = $result['bvr_vendor_id'];
				$res					 = BookingLog::model()->createLog($result['bvr_booking_id'], $desc, $userInfo, $eventId, '', $params);
			}
			catch (Exception $exc)
			{
				Logger::error($exc);
			}
		}
	}

	/**
	 * function getOldBids (show all active unassigned bid of previous bcb_id)
	 * @param type $tripId
	 * @return all previous data
	 */
	public static function getOldBids($tripId, $vendorId)
	{
		$sql	 = "SELECT * FROM booking_vendor_request WHERE  bvr_accepted=1 AND bvr_assigned=0 AND bvr_bcb_id=:tripId AND bvr_vendor_id <>:vendorId AND  bvr_created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)";
		$params	 = ["tripId" => $tripId, "vendorId" => $vendorId];
		$rows	 = DBUtil::query($sql, DBUtil::MDB(), $params);
		return $rows;
	}

	/**
	 * function modifyAssignStatus (modify unassign vendor status if vendor bid already there)
	 * @param type $tripId
	 * @param type $vendorId
	 * @return boolean
	 */
	public static function modifyAssignStatus($tripId, $vendorId)
	{

		$sql	 = "UPDATE booking_vendor_request SET bvr_assigned =2 WHERE bvr_bcb_id=:tripId AND bvr_vendor_id =:vendorId AND bvr_accepted=1";
		$params	 = ["tripId" => $tripId, "vendorId" => $vendorId];
		$result	 = DBUtil::execute($sql, $params);
		return $result;
	}

	/*
	 * @deprecated 
	 * new function bookingCab countVndDirectAcpt
	 */

	public static function getDirectAcceptCount($vendorId = '', $days = 90)
	{
		$params	 = ['days' => $days];
		$where	 = '';
		if ($vendorId > 0)
		{
			$params ['vndId']	 = $vendorId;
			$where				 = ' AND bvr.bvr_vendor_id=:vndId';
		}
		$sql	 = "SELECT bvr.bvr_vendor_id vndId,count(bvr_bcb_id) count(last accept date committed)
					FROM booking_vendor_request bvr
					WHERE bvr.bvr_assigned = 1 
						AND bvr.bvr_accepted = 1
						AND bvr.bvr_assigned_at IS NOT NULL
						AND bvr.bvr_assigned_at = bvr.bvr_accepted_at
						AND bvr.bvr_assigned_at >= DATE_SUB(curdate(), INTERVAL :days DAY)
						$where
					GROUP BY bvr.bvr_vendor_id ";
		$rows	 = DBUtil::query($sql, DBUtil::MDB(), $params);
		return $rows;
	}

	/**
	 * 
	 * @param int $bcbId
	 * @return Array
	 */
	public static function getactionedTakenVendor($bcbId)
	{

		$appliedVendors		 = self::getAppliedVendors($bcbId);
		$notifiedVendorList	 = [];
		$readVendorsList	 = [];
		if ($appliedVendors)
		{
			$notifiedVendorList = explode(',', $appliedVendors); //Notified vendor id array
		}
		$readVendors = NotificationLog::getReadVendor($bcbId); // readed vendor
		if ($readVendors)
		{
			$readVendorsList = explode(',', $readVendors); //Notified vendor id array
		}
		$vendorsToExclude	 = array_unique(array_merge($notifiedVendorList, $readVendorsList));
		$excludedVendor		 = implode(',', $vendorsToExclude);
		return $excludedVendor;
	}

	public static function getAppliedVendors($bcbId)
	{
		$params	 = ['bcbId' => $bcbId];
		$sql	 = "SELECT  GROUP_CONCAT(DISTINCT bvr.bvr_vendor_id) vndIds
					FROM booking_vendor_request bvr 
					WHERE bvr.bvr_bcb_id=:bcbId AND bvr_accepted <>0
					GROUP BY bvr.bvr_bcb_id ";
		$rows	 = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
		return $rows;
	}

	/**
	 * 
	 * @param int $bcbId
	 * @return Array
	 */
	public static function getGnowAppliedVendors($bcbId)
	{
		$params	 = ['bcbId' => $bcbId];
		$sql	 = "SELECT  GROUP_CONCAT(DISTINCT bvr.bvr_vendor_id) vndIds
					FROM booking_vendor_request bvr 
					WHERE bvr.bvr_bcb_id=:bcbId AND bvr.bvr_is_gozonow=1 
					GROUP BY bvr.bvr_bcb_id ";
		$rows	 = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
		return $rows;
	}

	/**
	 * 
	 * @param int $bcbId
	 * @return Array
	 */
	public static function getBiddedVendors($bcbId)
	{
		$params	 = ['bcbId' => $bcbId];
		$sql	 = "SELECT  GROUP_CONCAT(DISTINCT bvr.bvr_vendor_id) vndIds
					FROM booking_vendor_request bvr 
					WHERE bvr.bvr_bcb_id=:bcbId AND bvr.bvr_bid_amount > 0 AND bvr_active = 1
					GROUP BY bvr.bvr_bcb_id ";
		$rows	 = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
		return $rows;
	}

	/**
	 * Direct Accept Amount
	 * @param type $vendorId
	 * @param type $tripId
	 * @return type
	 */
	public static function getDirectAcceptAmount($vendorId, $tripId)
	{
		$vndInfoSql = "SELECT vnd_cat_type,vnd_active,vnp_is_freeze,vnp_cod_freeze,vnp_accepted_zone,vnp_home_zone,vnp_excluded_cities, vnp_oneway, vnp_round_trip, vnp_multi_trip, vnp_airport, vnp_package, vnp_flexxi,
                            vnp_daily_rental,vnp_boost_enabled,IF(vnp_is_allowed_tier LIKE '%1%',1,0) value,IF(vnp_is_allowed_tier LIKE '%2%',2,0) valuePlus,
                            IF(vnp_is_allowed_tier LIKE '%3%',3,0) plus,IF(vnp_is_allowed_tier LIKE '%4%',4,0) selectTier,IF(vnp_is_allowed_tier LIKE '%5%',5,0) selectPlus,IF(vnp_is_allowed_tier LIKE '6%',6,0) cng
							FROM vendors INNER JOIN vendor_pref ON vnp_vnd_id = vnd_id WHERE vnd_id = $vendorId";

		$vndInfo = DBUtil::queryRow($vndInfoSql, DBUtil::SDB());

		$vndInfo['vnp_accepted_zone']	 = ($vndInfo['vnp_accepted_zone'] == '') ? -1 : trim($vndInfo['vnp_accepted_zone'], ',');
		$vndInfo['vnp_excluded_cities']	 = ($vndInfo['vnp_excluded_cities'] == '') ? -1 : trim($vndInfo['vnp_excluded_cities'], ',');
		$vndInfo['vnp_home_zone']		 = ($vndInfo['vnp_home_zone'] == '') ? -1 : trim($vndInfo['vnp_home_zone'], ',');
		$vndBoostEnable					 = ($vndInfo['vnp_boost_enabled'] > 0) ? $vndInfo['vnp_boost_enabled'] : 0;
		$vendorStatus					 = $vndInfo['vnd_active'];

		$vndStatInfo = VendorStats::model()->fetchMetric($vendorId);

		$vndRating		 = ($vndStatInfo['vrs_vnd_overall_rating'] == null) ? 0 : $vndStatInfo['vrs_vnd_overall_rating'];
		$vndStickyScr	 = ($vndStatInfo['vrs_sticky_score'] == null) ? 4 : $vndStatInfo['vrs_sticky_score'];
		$vndPenaltyCount = $vndStatInfo['vrs_penalty_count'];
		$vndDriverApp	 = $vndStatInfo['vrs_driver_app_used'];
		$vndDependency	 = ($vndStatInfo['vrs_dependency'] == null) ? 0 : $vndStatInfo['vrs_dependency'];
		$vndBoostPercent = ($vndStatInfo['vrs_boost_percentage'] == null) ? 0 : $vndStatInfo['vrs_boost_percentage'];

		$acptBidPercent	 = ($vndBoostEnable > 0) ? (5 - $vndBoostPercent * 0.01 * 2) : 5;
		$allowedZones	 = implode(',', array_filter(explode(',', $vndInfo['vnp_accepted_zone'] . ',' . $vndInfo['vnp_home_zone'])));

		$val = '"';

		$acceptBidPercent	 = "GetVendorAcceptMargin2('{$vndInfo['vnp_home_zone']}', '{$vndInfo['vnp_accepted_zone']}', GROUP_CONCAT(DISTINCT fzc.zct_zon_id), GROUP_CONCAT(DISTINCT tzc.zct_zon_id), $vndBoostEnable, $vndBoostPercent, IFNULL(bkg_critical_score,0.65),IFNULL(btr_is_dem_sup_misfire,0))";
		//$acceptBidPercent	 = "GetVendorAcceptMargin1('{$vndInfo['vnp_home_zone']}', '{$vndInfo['vnp_accepted_zone']}', GROUP_CONCAT(DISTINCT fzc.zct_zon_id), GROUP_CONCAT(DISTINCT tzc.zct_zon_id), $vndBoostEnable, $vndBoostPercent, IFNULL(bkg_critical_score,0.65))";
		$acceptableAmount	 = "ROUND(booking_cab.bcb_vendor_amount * 0.01 * $acceptBidPercent)";
		/* $lowSMTAmount is used when smt score is less than 0 */
		$lowSMTAmount		 = "ROUND(booking_cab.bcb_vendor_amount * 0.01 * ($acceptBidPercent - 5))";

		$calculateSMTSql = "CalculateSMT(bcb_vendor_amount + SUM(bkg_gozo_amount - IFNULL(bkg_credits_used,0)),booking_cab.bcb_vendor_amount,
					    $acceptableAmount, $vndRating, $vndStickyScr, $vndPenaltyCount, $vndDriverApp, $vndDependency, $vndBoostPercent)";

		$isAcceptAllowed			 = "IsDirectAcceptAllowed('{$vndInfo['vnp_home_zone']}', GROUP_CONCAT(DISTINCT fzc.zct_zon_id), GROUP_CONCAT(DISTINCT tzc.zct_zon_id), bkg_manual_assignment, $calculateSMTSql, bkg_critical_score, MIN(booking.bkg_pickup_date), GREATEST(IFNULL(bcb_bid_start_time, MAX(bkg_confirm_datetime)), MAX(bkg_confirm_datetime)))";
		$validateAcceptableAmountSQL = "IF(bkg_critical_assignment=1 OR bkg_manual_assignment=1 OR booking.bkg_booking_type = 12, ROUND(booking_cab.bcb_vendor_amount * 0.98), IF($calculateSMTSql>=0, $acceptableAmount, $lowSMTAmount))";

		$calRecomendedAmount = "IF(bkg_critical_assignment=1 OR bkg_manual_assignment=1 , ROUND(booking_cab.bcb_vendor_amount * 0.98), ROUND(booking_cab.bcb_vendor_amount * 0.98))";

		//$showBookingCnd = "AND (booking.bkg_status=2 OR (booking.bkg_status IN (3,5) AND booking_cab.bcb_vendor_id <> $vendorId AND booking.bkg_pickup_date > DATE_ADD(NOW(),INTERVAL 6 hour) ))";


		$sqlMain = "SELECT  $validateAcceptableAmountSQL AS acptAmount
			FROM booking
			INNER JOIN booking_invoice biv ON biv.biv_bkg_id = booking.bkg_id
			INNER JOIN booking_pref ON bpr_bkg_id = booking.bkg_id
			INNER JOIN booking_trail btr ON btr_bkg_id = booking.bkg_id
			INNER JOIN booking_route ON brt_bkg_id = booking.bkg_id
			INNER JOIN cities ct1 ON ct1.cty_id = booking_route.brt_from_city_id
			INNER JOIN cities ct2 ON ct2.cty_id = booking_route.brt_to_city_id
			INNER JOIN zone_cities fzc ON fzc.zct_cty_id=ct1.cty_id AND fzc.zct_active=1
			INNER JOIN zone_cities tzc ON tzc.zct_cty_id=ct2.cty_id AND tzc.zct_active=1
			INNER JOIN booking_cab ON bcb_id = bkg_bcb_id AND bcb_active = 1
			WHERE  bcb_active = 1 
			AND bcb_id=  $tripId  ";

		#echo 'SQL MAIN ===>' . $sqlMain;

		$data = DBUtil::queryRow($sqlMain);

		return $data['acptAmount'];
	}

	public static function DirectAccept($bid_amount, $vendorId, $bcb_id, $userInfo)
	{
		$transaction = null;
		try
		{
			/** @var BookingCab $bookingCabModel */
			$bookingCabModel = BookingCab::model()->findByPk($bcb_id);
			$bookingModels	 = $bookingCabModel->bookings;

			$accptVendorAmount	 = $bid_amount;
			$vendorAmt			 = $bookingCabModel->bcb_vendor_amount;
			$accept_bcb_id		 = $bcb_id;
			$bkgAmount			 = BookingInvoice::getBKGAmount($accept_bcb_id);

			if ($vendorAmt > $accptVendorAmount)
			{
				$oldVendorAmt	 = $vendorAmt;
				$vendorAmt		 = $accptVendorAmount;
			}
			else
			{
				Logger::pushTraceLogs();
			}

			if (count($bookingModels) == 0)
			{
				$bookingModels				 = BookingSmartmatch::model()->getBookings($accept_bcb_id);
				$bookingCabModel->bookings	 = $bookingModels;
			}
			$transaction = DBUtil::beginTransaction();

			if ($bookingCabModel->bcb_trip_type == 1 && $bookingModels[0]->bkg_bcb_id != $accept_bcb_id && $bookingModels[1]->bkg_bcb_id != $accept_bcb_id)
			{
				goto skip;
			}

			if ($bookingCabModel->bcb_trip_type == 1 && $bookingModels[0]->bkg_bcb_id != $accept_bcb_id && $bookingModels[1]->bkg_bcb_id != $accept_bcb_id)
			{
				BookingCab::model()->confirmSmartMatch($accept_bcb_id, $bookingModels[0]->bkg_id, $bookingModels[1]->bkg_id);
			}
			$bvrModels = BookingVendorRequest::model()->findByBcbIdAndVendorId($accept_bcb_id, $vendorId);
			if (count($bvrModels) >= 1)
			{
				foreach ($bvrModels as $bvrModel)
				{

					$bvrModel->bvr_assigned		 = 1;
					$bvrModel->bvr_assigned_at	 = new CDbExpression('NOW()');
					$bvrModel->bvr_active		 = 1;
					$bvrModel->bvr_bid_amount	 = $vendorAmt;
					$success					 = $bvrModel->save();
					if (!$success)
					{
						throw new Exception(json_encode($bvrModel->getErrors()), ReturnSet::ERROR_VALIDATION);
					}
				}
			}
			else
			{
				$success = BookingVendorRequest::model()->createRequest($vendorAmt, $accept_bcb_id, $vendorId, 'newAssign');
			}
			$directAcpt	 = 1;
			$assignMode	 = 2;

			$return = $bookingCabModel->assignVendor($accept_bcb_id, $vendorId, $accptVendorAmount, '', $userInfo, $assignMode, null, $directAcpt);
			$bookingCabModel->refresh();
			if ($return->isSuccess() && $success)
			{
				$numRows = BookingVendorRequest::model()->updateListByBcb($accept_bcb_id);
				$success = true;
			}
			skip:
			if (!$return->isSuccess())
			{
				$success = false;
				throw $return->getException();
			}
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $exc)
		{
			DBUtil::rollbackTransaction($transaction);
			ReturnSet::setException($exc);
			throw $exc;
		}

		return $success;
	}

	/**
	 * 
	 * @param array $params
	 * @param int $vendorId
	 * @return boolean
	 * @throws Exception
	 */
	public static function storeGNowRequest($params, $vendorId)
	{
		$success	 = true;
		$bcbId		 = $params['tripId'];
		$bidAmount	 = (isset($params['bidAmount'])) ? $params['bidAmount'] : 0;
		$isAccept	 = $params['isAccept'];
		$bkgId		 = $params['bkgId'];
		$reasonId	 = $params['reasonId'] | 0;

		$transaction = DBUtil::beginTransaction();
		try
		{
			$modelvendor = Vendors::model()->findByPk($vendorId);
			$model		 = BookingVendorRequest::model()->find('bvr_bcb_id=:bcbId AND bvr_vendor_id=:vendorId AND bvr_active=1', ['bcbId' => $bcbId, 'vendorId' => $vendorId]);
			if (!$model)
			{
				$model = new BookingVendorRequest();

				$model->bvr_booking_id		 = (int) $bkgId;
				$model->bvr_bcb_id			 = $bcbId;
				$model->bvr_vendor_id		 = $modelvendor->vnd_id;
				$rating						 = $modelvendor->vendorStats->vrs_vnd_overall_rating;
				$initialRating				 = 4.6;
				$initialScore				 = 8;
				$model->bvr_vendor_rating	 = ($rating == null || $rating == 0) ? $initialRating : $rating;
				$score						 = $modelvendor->vendorStats->vrs_overall_score;
				$model->bvr_vendor_score	 = ($score == null || $score == 0) ? $initialScore : $score;

				$model->bvr_assigned = 0;
			}
			if ($model->bvr_bid_amount > 0)
			{
				if ($model->bvr_is_gozonow == 1 && $bidAmount > $model->bvr_bid_amount)
				{
					throw new Exception('You are exceeding previous offer', ReturnSet::ERROR_REQUEST_CANNOT_PROCEED);
				}
			}
			$model->bvr_is_gozonow = 1;

			if ($isAccept)
			{
				$model->bvr_bid_amount	 = $bidAmount;
				$model->bvr_accepted	 = 1;

				$model->bvr_special_remarks = json_encode([
					'driverId'			 => $params['driverId'],
					'driverMobile'		 => $params['driverMobile'],
					'cabId'				 => $params['cabId'],
					'reachingAtMinutes'	 => $params['reachingAtMinutes'],
					'reachingAtTime'	 => $params['reachingAtTime']
				]);
			}
			else
			{
				$model->bvr_accepted		 = 2;
				$model->bvr_assigned		 = 2;
				$model->bvr_deny_reason_id	 = $reasonId;

				$model->bvr_assigned_at = new CDbExpression('NOW()');
			}
			$model->bvr_accepted_at = new CDbExpression('NOW()');

			if (!$model->save())
			{
				throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
			}
			DBUtil::commitTransaction($transaction);
			$res = BookingVendorRequest::scheduleSMTScore($bcbId, $vendorId, $bidAmount);
			if ($success)
			{
				return $model;
			}
		}
		catch (Exception $e)
		{
			DBUtil::rollbackTransaction($transaction);
			throw new Exception($e->getMessage(), $e->getCode());
			$success = false;
		}
		return $success;
	}

	/**
	 * 
	 * @param type $params
	 * @param type $vendorId
	 * @return boolean
	 * @throws Exception
	 */
	public static function notifiedMultiGNowEntry($vendorArr, $params)
	{
		$success = true;
		foreach ($vendorArr as $vendorId)
		{
			$success = BookingVendorRequest::notifiedGNowEntry($params, $vendorId);
		}
		return $success;
	}

	/**
	 * 
	 * @param type $params
	 * @param type $vendorId
	 * @return boolean
	 * @throws Exception
	 */
	public static function notifiedGNowEntry($params, $vendorId)
	{
		$success	 = true;
		$bcbId		 = $params['tripId'];
		$bidAmount	 = 0;

		$bkgId = $params['bkgId'];

		$transaction = DBUtil::beginTransaction();
		try
		{
			$model = BookingVendorRequest::model()->find('bvr_bcb_id=:bcbId AND bvr_vendor_id=:vendorId AND bvr_active=1', ['bcbId' => $bcbId, 'vendorId' => $vendorId]);
			if (!$model)
			{
				$model = new BookingVendorRequest();

				$modelvendor				 = Vendors::model()->findByPk($vendorId);
				$model->bvr_booking_id		 = (int) $bkgId;
				$model->bvr_bcb_id			 = $bcbId;
				$model->bvr_vendor_id		 = $modelvendor->vnd_id;
				$rating						 = $modelvendor->vendorStats->vrs_vnd_overall_rating;
				$initialRating				 = 4.6;
				$initialScore				 = 8;
				$model->bvr_vendor_rating	 = ($rating == null || $rating == 0) ? $initialRating : $rating;
				$score						 = $modelvendor->vendorStats->vrs_overall_score;
				$model->bvr_vendor_score	 = ($score == null || $score == 0) ? $initialScore : $score;
				$model->bvr_bid_amount		 = $bidAmount;
				$model->bvr_created_at		 = DBUtil::getCurrentTime();
			}
			$model->bvr_last_reminded_at = DBUtil::getCurrentTime();

			if (!$model->save())
			{
				throw new Exception('Bid request not set');
			}
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $e)
		{
			DBUtil::rollbackTransaction($transaction);
			throw new Exception($e->getMessage(), $e->getCode());
			$success = false;
		}
		return $success;
	}

	/**
	 * 
	 * @param int $tripId
	 * @return CDbDataReader
	 */
	public static function getGNowAcceptedData($tripId, $hideExpired = true)
	{
		$params = ['tripId' => $tripId];
		if ($hideExpired)
		{
			$expireOffer = " AND TIMESTAMPDIFF(SECOND,bvr.bvr_accepted_at,NOW()) < 300 ";
		}
		$qry	 = "SELECT `bvr_id`, `bvr_booking_id`, `bvr_bcb_id`, `bvr_vendor_id`, `bvr_vendor_rating`, 
					REPLACE(json_extract(bvr_special_remarks, '$.reachingAtTime'),'\"','') AS reachingAtTime,
					REPLACE(json_extract(bvr_special_remarks, '$.cabId'),'\"','') AS cabId,
					REPLACE(json_extract(bvr_special_remarks, '$.driverId'),'\"','') AS driverId,
					bvr_special_remarks,
					`bvr_bid_amount`, `bvr_created_at`, `bvr_accepted_at`,
					CONCAT('bid_',bvr_id,'_',bvr_bid_amount) bidId,
					(300 - TIMESTAMPDIFF(SECOND,bvr.bvr_accepted_at,NOW())) bidexpiretimeLeft
					FROM   booking_vendor_request bvr
					WHERE  bvr_active = 1 AND bvr_accepted = 1 AND bvr_bid_amount >0 
					AND bvr_special_remarks <> '' AND bvr_bcb_id = :tripId 
					$expireOffer
				GROUP BY bvr_vendor_id,bvr_booking_id";
		$data	 = DBUtil::query($qry, DBUtil::SDB(), $params);

		return $data;
	}

	public static function getGNowManualAcceptedList($tripId, $hideExpired = true)
	{
		$data					 = BookingVendorRequest::getGNowAcceptedData($tripId, $hideExpired);
		$returnSet				 = [];
		$returnSet['success']	 = false;
		$rowCount				 = $data->getRowCount();

		$cachekey = "getGNowManualAcceptedList{$tripId}_{$rowCount}";
//		$returnSet	 = Yii::app()->cache->get($cachekey3);
		//	if ($returnSet == false)
		{
			if ($rowCount == 0)
			{
				$returnSet['message'] = "No bid appeared yet.";
				goto end;
			}
			$returnSet['success']	 = true;
			$result					 = [];
			foreach ($data as $key => $val)
			{
				$bkgId				 = $val['bvr_booking_id'];
				$vendorAmount		 = $val['bvr_bid_amount'];
				$vhcid				 = $val['cabId'];
				$remarks			 = json_decode($val['bvr_special_remarks'], true);
				$driverId			 = $remarks['driverId'];
				$driverMobile		 = $remarks['driverMobile'];
				$vhcRecord			 = Vehicles::getDetailbyid($vhcid);
				$vhcData			 = ($vhcRecord) ? $vhcRecord : [];
				$val['bidAmount']	 = $vendorAmount;
				$vndId				 = $val['bvr_vendor_id'];
				$vndStats			 = Vendors::getArriveTimeStats($vndId);

				$drvCntId		 = ContactProfile::getByEntityId($driverId, UserInfo::TYPE_DRIVER);
				$drvCntDetails	 = Contact::getContactDetails($drvCntId);
				$driverName		 = $drvCntDetails['ctt_first_name'];
				if (empty(trim($driverName)))
				{
					$drvDetails	 = Drivers::getDriverInfo($driverId);
					$driverName	 = $drvDetails['drv_name'];
				}
				$remarks['driverName'] = $driverName;

				$result[$key] = $vndStats + $val + $vhcData + $remarks;
			}
			$returnSet['data'] = $result;
			end:
//			Yii::app()->cache->set($cachekey2, $returnSet, 6000);
		}
		return $returnSet;
	}

	/**
	 * 
	 * @param int $tripId
	 * @return array
	 */
	public static function getGNowAcceptedList($tripId, $viewType = 'html')
	{
		$data					 = BookingVendorRequest::getGNowAcceptedData($tripId);
		$returnSet				 = [];
		$returnSet['success']	 = false;
		$rowCount				 = $data->getRowCount();

		$cachekey	 = "getGNowAcceptedList_{$tripId}_{$rowCount}_{$viewType}";
		$returnSet	 = Yii::app()->cache->get($cachekey);
		if ($returnSet == false)
		{
			if ($rowCount == 0)
			{
				$returnSet['message'] = "No bid appeared yet.";
				goto end;
			}
			$returnSet['success']	 = true;
			$result					 = [];
			foreach ($data as $key => $val)
			{
				$bkgId					 = $val['bvr_booking_id'];
				$vendorAmount			 = $val['bvr_bid_amount'];
				$vhcid					 = $val['cabId'];
				$drvid					 = $val['driverId'];
				$model					 = BookingSub::getModelForGNowFromVendorAmount($bkgId, $vendorAmount);
				$vhcRecord				 = Vehicles::getDetailbyid($vhcid);
				$drvRecord				 = DriverStats::getRatingInfoById($drvid);
				$vhcData				 = ($vhcRecord) ? $vhcRecord : [];
				$drvData				 = ($drvRecord) ? $drvRecord : [];
				$totalAmount			 = $model->bkgInvoice->bkg_total_amount;
				$val['totalCalculated']	 = $totalAmount;
				$vndId					 = $val['bvr_vendor_id'];
				$vndStats				 = Vendors::getArriveTimeStats($vndId);
				$result[$key]			 = $vndStats + $val + $vhcData + $drvData;
			}
			$returnSet['data'] = $result;
			end:
			Yii::app()->cache->set($cachekey2, $returnSet, 6000);
		}
		return $returnSet;
	}

	/**
	 * 
	 * @param int $tripId
	 * @return array
	 */
	public static function getPreferredVendorbyBooking($tripId)
	{
		$params	 = ['tripId' => $tripId];
		$qry	 = "SELECT bvr_vendor_id,bvr_booking_id,bvr_bid_amount,bvr_is_preferred_vendor ,
					bvr_app_notification,
					bvr_special_remarks
					FROM booking_vendor_request bvr
					WHERE  bvr_active = 1 AND bvr_accepted = 1 
					  AND bvr_special_remarks <> '' AND bvr_is_preferred_vendor = 1 AND bvr_bcb_id = :tripId";
		$data	 = DBUtil::queryRow($qry, DBUtil::MDB(), $params);

		return $data;
	}

	public function updatePreferredVendor()
	{
		$this->bvr_is_preferred_vendor	 = 1;
		$success						 = $this->save();
		return $success;
	}

	public static function cancelPreferredVendor($bkgId)
	{
		$params	 = ['bkgId' => $bkgId];
		$sql	 = "UPDATE booking_vendor_request
				SET  bvr_is_preferred_vendor = 0 
				WHERE bvr_booking_id=:bkgId AND bvr_accepted = 1  AND bvr_active = 1";
		$success = DBUtil::command($sql)->execute($params);

		return $success;
	}

	public static function deactivateOffer($vendorId, $tripId)
	{
		$params	 = ['vendorId' => $vendorId, 'tripId' => $tripId];
		$sql	 = "UPDATE booking_vendor_request
				SET bvr_active=0 ,bvr_accepted =0 
				WHERE bvr_vendor_id=:vendorId AND bvr_bcb_id=:tripId AND bvr_active = 1";
		$success = DBUtil::command($sql)->execute($params);

		return $success;
	}

	/**
	 * 
	 * @param type $vndId
	 * @param type $limit
	 * @param type $filter
	 * @return CDbDataReader
	 */
	public static function getBidStatusByVendor($vndId, $limit = 20, $filter = null)
	{

		$params	 = ['vndId' => $vndId];
		$where	 = '';
		if ($filter != null)
		{
			if ($filter->isNew == 1)
			{
				$where = " AND bvr.bvr_bid_amount=0 AND bvr.bvr_accepted = 0  
 			AND bkg.bkg_status = 2 ";
			}
		}
		$qry	 = "SELECT bvr.bvr_id ,bvr.bvr_bcb_id,bvr.bvr_booking_id,
					bcb.bcb_vendor_id,bvr.bvr_is_gozonow,bkg_is_gozonow,
					bvr.bvr_accepted,bvr.bvr_created_at, bvr.bvr_assigned,bvr.bvr_accepted_at,
					bkg.bkg_booking_id,scv.scv_id,bkg.bkg_status,bvr.bvr_vendor_id,
					bvr_bid_amount,bkg.bkg_pickup_date,bkg_booking_type,bkg_pickup_address,
					bkg.bkg_id,bkg.bkg_bcb_id,
					bkg.bkg_from_city_id from_city_id,
					bkg.bkg_to_city_id to_city_id,
					bkg.bkg_pickup_address from_address,
					bkg.bkg_drop_address to_address,
					bkgFromCity.cty_name as from_city_name,	
					bkgToCity.cty_name as to_city_name,
					scv.scv_label,vct.vct_label,vct.vct_desc,bcb.bcb_end_time,bkg.bkg_trip_duration 
					FROM booking_vendor_request bvr
					INNER JOIN booking bkg ON bkg.bkg_id = bvr.bvr_booking_id 
						AND bkg.bkg_status IN (2,3,5,6,7,9)  
					INNER JOIN booking_pref bpr ON bpr.bpr_bkg_id = bkg.bkg_id
					INNER JOIN booking_cab bcb ON bcb.bcb_id = bvr.bvr_bcb_id
					INNER JOIN cities bkgFromCity ON (bkgFromCity.cty_id=bkg.bkg_from_city_id) AND (bkgFromCity.cty_active = 1)
					INNER JOIN cities bkgToCity ON (bkgToCity.cty_id=bkg.bkg_to_city_id) AND (bkgToCity.cty_active = 1)
					INNER JOIN svc_class_vhc_cat scv ON ( scv.scv_id=bkg.bkg_vehicle_type_id)
					INNER JOIN service_class scc ON (scc.scc_id = scv.scv_scc_id)
					INNER JOIN vehicle_category vct ON (vct.vct_id=scv.scv_vct_id)
					WHERE bvr.bvr_active = 1 
						AND  DATE_SUB(NOW(),INTERVAL 1 MONTH) < bvr.bvr_created_at 
						AND bvr.bvr_vendor_id = :vndId
						AND bvr.bvr_bcb_id = bkg.bkg_bcb_id 
						AND bpr.bkg_is_gozonow != 0 $where
					GROUP BY bkg.bkg_id	
					ORDER BY bkg.bkg_pickup_date DESC
					LIMIT 0,$limit
					";
		$data	 = DBUtil::query($qry, DBUtil::SDB(), $params);
		return $data;
	}

	/**
	 * This function is used to broadcast messages	 * 
	 * @param string $msg	 
	 */
	public static function broadcastMessage($msg)
	{
		$elephant = new \ElephantIO\Client(new Version4X('http://localhost:3000', []));
		//$elephant->of('/vendors');
		$elephant->initialize();
		$elephant->emit('broadcast', ['message' => $msg]);
		$elephant->close();
	}

	/**
	 * 
	 * @param type $tripId
	 */
	public static function sendGozoNowData($tripId)
	{
		$result				 = BookingVendorRequest::getGNowAcceptedList($tripId);
		$lastKey			 = array_key_last($result['data']);
		$newRow				 = $result['data'][$lastKey];
		$newRow["type"]		 = "Vendor";
		$newRow["isGozoNow"] = true;
		$newRow["tripId"]	 = $tripId;
		$newRow["tripDate"]	 = DateTimeFormat::DateTimeToLocale($newRow['reachingAtTime']);
		$newRow["totalBids"] = count($result['data']);
		BookingVendorRequest::broadcastMessage(json_encode($newRow));
	}

	public static function controlBidTimer($bkgId)
	{

		$bkgModel	 = Booking::model()->findByPk($bkgId);
		$tripId		 = $bkgModel->bkg_bcb_id;
		$dataexist	 = BookingVendorRequest::getPreferredVendorbyBooking($tripId);

		if (!$dataexist)
		{
			$formatDateTime				 = 'Y-m-d H:i:s';
			$data						 = BookingVendorRequest::getGNowAcceptedData($tripId);
			$rowCount					 = $data->getRowCount();
			$minBidCntNextLevel			 = 3;
			$bkgModel->bkg_pickup_date	 = '2022-03-19 12:30:00';
			$bkgModel->bkg_create_date	 = Filter::getDBDateTime(); //'2022-03-10 19:41:00';
			$step1Duration				 = 2;
			$step2Duration				 = 5;
			$step1Date					 = date($formatDateTime, strtotime($bkgModel->bkg_create_date . "+ {$step1Duration} MINUTE"));
			$step2Date					 = date($formatDateTime, strtotime($bkgModel->bkg_create_date . "+ {$step2Duration} MINUTE"));
		}
	}

	/**
	 * 
	 * @param array $bidrows
	 * @param string $timerLogJson
	 * @return type
	 */
	public static function getBidTimerStat($bidrows, $timerLogJson)
	{

		$timerLog	 = json_decode($timerLogJson, true);
		$timerCount	 = $timerLog['count'];

		$createDate = $timerLog['startTime'];

		$timerDurations = BookingVendorRequest::getBidTimerDurations();

		$step1ASecDuration	 = $timerDurations['1A']['duration'];
		$step1BSecDuration	 = $timerDurations['1B']['duration'] + $step1ASecDuration;
		$step2ASecDuration	 = $timerDurations['2A']['duration'];
		$step1CSecDuration	 = $timerDurations['1C']['duration'];
		$step2CSecDuration	 = $timerDurations['2C']['duration'];

		$firstBidTime	 = null;
		$thirdBidTime	 = null;
		$formatDateTime	 = 'Y-m-d H:i:s';

		$bidCount = count($bidrows);
		if ($bidCount > 0)
		{
			$firstBidTime = $bidrows[0]['bvr_created_at'];
			if ($bidCount > 2)
			{
				$thirdBidTime = $bidrows[2]['bvr_created_at'];
//					$thirdBidTime	 = date($formatDateTime, strtotime($createDate . ' + 60 second'));
			}
		}


		$createDiffSecs = -1 * Filter::getTimeDiffinSeconds($createDate);

		if ($firstBidTime != null)
		{
			$firstBidTimeDiffSecs = -1 * Filter::getTimeDiffinSeconds($firstBidTime);
		}

		if ($thirdBidTime != null)
		{
			$thirdBidTimeDiffSecs = -1 * Filter::getTimeDiffinSeconds($thirdBidTime);
		}

		$timerRunning		 = 'invalid';
		$durationRemaining	 = 0;
		$stepValidation		 = '';
//		$timerCount			 = 2;
		if ($timerCount >= 2)
		{
//			if ($bidCount < 3 && $createDiffSecs <= $step1CSecDuration)
			if ($createDiffSecs <= $step1CSecDuration)
			{//O-2 bid : 1st Timer : 1 lap
				$timerRunning		 = 'timer2';
				$durationRemaining	 = $step2CSecDuration - $createDiffSecs;
				$stepValidation		 = '1_3_1';
			}
			if ($createDiffSecs > $step1CSecDuration)
			{//O bid : no Timer : 2 lap
				$timerRunning		 = 'sorry';
				$durationRemaining	 = 0;
				$stepValidation		 = '0_0_0';
			}

			if ($bidCount > 0 && $bidCount < 3 && $createDiffSecs > $step1CSecDuration && $firstBidTimeDiffSecs > 0)
			{//3 >= bid : 1st Timer : 1 lap
//			$firstBidTimeDiffSecs	 = -1 * Filter::getTimeDiffinSeconds($firstBidTime);
				$firstBidDurationdSec = $createDiffSecs - $firstBidTimeDiffSecs;

				if ($firstBidDurationdSec <= $step1CSecDuration && $createDiffSecs > $step1CSecDuration)
				{

					$timerRunning		 = 'timer2';
					$durationRemaining	 = ($step1ASecDuration + $step2ASecDuration) - $createDiffSecs;
					$stepValidation		 = '1_1_2';
				}
			}

			if ($bidCount >= 3 && $thirdBidTimeDiffSecs > 0)
			{//3 >= bid : 1st Timer : 1 lap
				$thirdBidDurationdSec = $createDiffSecs - $thirdBidTimeDiffSecs;
				if ($thirdBidDurationdSec < $step1CSecDuration)
				{
					$timerRunning		 = 'timer2';
					$durationRemaining	 = ($thirdBidDurationdSec + $step2ASecDuration) - $createDiffSecs;
					$stepValidation		 = '1_1_2';
				}
			}


			goto skiprest;
		}



		if ($bidCount < 3 && $createDiffSecs <= $step1ASecDuration)
		{//O-2 bid : 1st Timer : 1 lap
			$timerRunning		 = 'timer1';
			$durationRemaining	 = $step1ASecDuration - $createDiffSecs;
			$stepValidation		 = '1_1_1';
		}
		if ($bidCount == 0 && $createDiffSecs > $step1ASecDuration && $createDiffSecs <= $step1BSecDuration)
		{//O bid : 1st Timer : 2 lap
			$timerRunning		 = 'timer1';
			$durationRemaining	 = $step1BSecDuration - $createDiffSecs;
			$stepValidation		 = '1_2_1';
		}

		if ($bidCount == 0 && $createDiffSecs > $step1BSecDuration)
		{//O bid : no Timer : 2 lap
			$timerRunning		 = 'sorry';
			$durationRemaining	 = 0;
			$stepValidation		 = '0_0_0';
		}
///////////////////////
		if ($bidCount > 0 && $bidCount < 3 && $createDiffSecs > $step1ASecDuration && $firstBidTimeDiffSecs > 0)
		{//3 >= bid : 1st Timer : 1 lap
//			$firstBidTimeDiffSecs	 = -1 * Filter::getTimeDiffinSeconds($firstBidTime);
			$firstBidDurationdSec = $createDiffSecs - $firstBidTimeDiffSecs;

			if ($firstBidDurationdSec <= $step1ASecDuration && $createDiffSecs <= $step1ASecDuration)
			{

				$timerRunning		 = 'timer1';
				$durationRemaining	 = $step1ASecDuration - $createDiffSecs;
				$stepValidation		 = '1_2_1';
			}
			if ($firstBidDurationdSec <= $step1ASecDuration && $createDiffSecs > $step1ASecDuration)
			{

				$timerRunning		 = 'timer2';
				$durationRemaining	 = ($step1ASecDuration + $step2ASecDuration) - $createDiffSecs;
				$stepValidation		 = '1_1_2';
			}

			if ($firstBidDurationdSec >= $step1ASecDuration && $firstBidDurationdSec < $step1BSecDuration && $createDiffSecs <= $step1BSecDuration)
			{
				$timerRunning		 = 'timer1';
				$durationRemaining	 = $step1BSecDuration - $createDiffSecs;
				$stepValidation		 = '1_2_1';
			}

			if ($firstBidDurationdSec >= $step1ASecDuration && $firstBidDurationdSec < $step1BSecDuration && $createDiffSecs > $step1BSecDuration)
			{
				$timerRunning		 = 'timer2';
				$durationRemaining	 = ($step1BSecDuration + $step2ASecDuration) - $createDiffSecs;
				$stepValidation		 = '1_1_2';
			}
		}
		if ($bidCount >= 3 && $thirdBidTimeDiffSecs > 0)
		{//3 >= bid : 1st Timer : 1 lap
			if ($createDiffSecs < $step1ASecDuration)
			{
				
			}
			$thirdBidDurationdSec = $createDiffSecs - $thirdBidTimeDiffSecs;
			if ($thirdBidDurationdSec < $step1ASecDuration)
			{
				$timerRunning		 = 'timer2';
				$durationRemaining	 = ($thirdBidDurationdSec + $step2ASecDuration) - $createDiffSecs;
				$stepValidation		 = '1_1_2';
			}
		}

		skiprest:
		$durationRemaining = ( $durationRemaining < 0) ? 0 : $durationRemaining;

		$res = ['timerRunning' => $timerRunning, 'durationRemaining' => $durationRemaining, 'stepValidation' => $stepValidation];
		return $res;
	}

	/**
	 * 
	 * @param int $vndId
	 * @return int
	 */
	public static function countGnowBid($vndId)
	{
		$params	 = ['vndId' => $vndId];
		$sql	 = "SELECT  count(bvr.bvr_id)  FROM `booking_vendor_request` bvr 
				INNER JOIN booking_pref bpr ON bpr.bpr_bkg_id= bvr.bvr_booking_id AND bpr.bkg_is_gozonow<>0 
					AND bvr.bvr_accepted = 1 AND bvr.bvr_bid_amount > 0 AND bvr.bvr_active=1 
				WHERE bvr.bvr_vendor_id = :vndId";
		$data	 = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
		return $data;
	}

	/**
	 * 
	 * @return array
	 */
	public static function getBidTimerDurations()
	{
		$step1ADuration		 = 3;
		$step1ASecDuration	 = $step1ADuration * 60;

		$step1BDuration		 = 3;
		$step1BSecDuration	 = $step1BDuration * 60;

		$step2ADuration		 = 5;
		$step2ASecDuration	 = $step2ADuration * 60;

		$step1CDuration		 = 5;
		$step1CSecDuration	 = $step1CDuration * 60;

		$step2CDuration		 = 5;
		$step2CSecDuration	 = $step2CDuration * 60;
		//1st Timer : 1st lap
		$data['1A']			 = ['step' => '1A', 'duration' => $step1ASecDuration];
		//1st Timer : 2nd lap
		$data['1B']			 = ['step' => '1B', 'duration' => $step1BSecDuration];
		//2st Timer : 1st lap
		$data['2A']			 = ['step' => '2A', 'duration' => $step2ASecDuration];
		//1st Timer : requested lap
		$data['1C']			 = ['step' => '1C', 'duration' => $step1CSecDuration];
		//2ndt Timer : requested lap
		$data['2C']			 = ['step' => '2C', 'duration' => $step2CSecDuration];
		return $data;
	}

	/**
	 * 
	 * @param int $tripid
	 * @return int
	 */
	public static function gnowOfferList($tripid)
	{
		$params	 = ['tripId' => $tripid];
		$sql	 = "SELECT  *  FROM `booking_vendor_request` bvr 
				INNER JOIN booking_pref bpr ON bpr.bpr_bkg_id= bvr.bvr_booking_id AND bpr.bkg_is_gozonow<>0 
					AND bvr.bvr_accepted = 1 AND bvr.bvr_bid_amount > 0 AND bvr.bvr_active=1 
				WHERE bvr.bvr_bcb_id = :tripId";
		$data	 = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
		return $data;
	}

	/**
	 * calculate vendor Dependency at the time of booking
	 * @param type $vendorId
	 * return dependency score
	 */
	public static function calcVendorDependency($vendorId)
	{

		$days	 = array(7, 30, 60);
		$sql	 = "";
		$score	 = 0.8;
		foreach ($days as $day)
		{
			$result				 = VendorStats::getVendorServingStats($vendorId, $day);
			$assignedTrip		 = $result['bookingAssigned'];
			$directAcceptedTrip	 = $result['bookingDirectAccept'];
			$bidAcceptedTrip	 = $result['bookingBidAccept'];
			$manualAcceptedTrip	 = $result['bookingManualAccept'];
			$servedTrip			 = $result['bookingServed'];
			$cancelTrip			 = $result['bookingCancelled'];
			$directCanceltrip	 = $result['bookingDirectCancelled'];
			$bidCanceltrip		 = $result['bookingBidCancelled'];
			$manualCanceltrip	 = $result['bookingManualCancelled'];
			$gnowCanceltrip		 = $result['bookingGNowCancelled'];

			$servingRatio = $servedTrip / max([$assignedTrip, 1]);

			if ($directAcceptedTrip >= 5 || $directCanceltrip > 2)
			{
				$score = 1 - (($directCanceltrip * 1.35) / ($directAcceptedTrip - $directCanceltrip));
				break;
			}
			else if ($bidAcceptedTrip > 10 || $bidCanceltrip > 5)
			{
				$score = 1 - ($bidCanceltrip / ($bidAcceptedTrip - $bidCanceltrip));
				break;
			}
			else if ($assignedTrip > 15 || $cancelTrip > 10)
			{
				$score = 1 - ($cancelTrip / ($assignedTrip - $cancelTrip));
				break;
			}
		}


		return $score;
	}

	/**
	 * 
	 * @param int $tripid
	 * @param int $vndId
	 * @return array
	 */
	public static function getGNowDeactivatedListbyVendor($tripid, $vndId)
	{
		$params	 = ['tripId' => $tripid, 'vndId' => $vndId];
		$sql	 = "SELECT  *  FROM `booking_vendor_request` bvr 
				INNER JOIN booking_pref bpr ON bpr.bpr_bkg_id= bvr.bvr_booking_id				
					AND bpr.bkg_is_gozonow<>0 AND bvr.bvr_accepted = 0			
					AND bvr.bvr_bid_amount > 0 AND bvr.bvr_active=0 
				WHERE bvr.bvr_bcb_id = :tripId AND bvr.bvr_vendor_id = :vndId 
				ORDER BY bvr.bvr_bid_amount ASC";
		$data	 = DBUtil::queryRow($sql, DBUtil::SDB(), $params);
		return $data;
	}

	/**
	 * 
	 * @param int $tripid
	 * @param int $vndId
	 * @return int
	 */
	public static function getMinimumGNowOfferAmountbyVendor($tripid, $vndId)
	{
		$params	 = ['tripId' => $tripid, 'vndId' => $vndId];
		$sql	 = "SELECT  bvr_bid_amount  FROM `booking_vendor_request` bvr 
				INNER JOIN booking_pref bpr ON bpr.bpr_bkg_id= bvr.bvr_booking_id				
					AND bpr.bkg_is_gozonow<>0  			
					AND bvr.bvr_bid_amount > 0  
				WHERE bvr.bvr_bcb_id = :tripId AND bvr.bvr_vendor_id = :vndId 
				ORDER BY bvr.bvr_bid_amount ASC";
		$data	 = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
		return $data;
	}

	public static function calculateBidAmount($vndId, $tripId)
	{
		$params	 = ['vndId' => $vndId, 'tripId' => $tripId];
		$sql	 = "SELECT  bvr_bid_amount  FROM `booking_vendor_request` bvr 
					WHERE bvr.bvr_bcb_id = :tripId AND bvr.bvr_bid_amount > 0 AND bvr.bvr_active=1 
					AND bvr.bvr_vendor_id = :vndId ";
		$data	 = DBUtil::queryRow($sql, DBUtil::SDB(), $params);
		return $data;
	}

	/**
	 * 
	 * @param type $vndId
	 * @return type
	 */
	public static function getMaxAssignDate($vndId)
	{
		$params	 = ['vndId' => $vndId];
		$sql	 = "SELECT MAX(`bvr_assigned_at`) as max_assign_date  FROM `booking_vendor_request` WHERE `bvr_vendor_id`=:vndId AND bvr_assigned=1";
		$data	 = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
		return $data;
	}

	/**
	 * 
	 * @param int $bcbId
	 * @return Array
	 */
	public static function getRecordCountByBkg($bkgId)
	{
		$params	 = ['bkgId' => $bkgId];
		$sql	 = "SELECT  count(bvr.bvr_id) 
					FROM booking_vendor_request bvr 
					WHERE bvr.bvr_booking_id=:bkgId ";
		$rows	 = DBUtil::queryScalar($sql, DBUtil::MDB(), $params);
		return $rows;
	}

	/**
	 *  
	 * @param int $bkgId
	 * @return Object
	 */
	public static function getRejectedVendorDetails($bkgId)
	{
		$params			 = ['bkgId' => $bkgId];
		$sql			 = "SELECT 	DISTINCT(bvr.bvr_vendor_id),v2.vnd_name,cp.phn_phone_no
							FROM booking_vendor_request bvr
							INNER JOIN vendors v1 ON  v1.vnd_id = bvr.bvr_vendor_id
							INNER JOIN vendors v2 ON  v1.vnd_ref_code = v2.vnd_id
							LEFT JOIN contact_profile cr ON cr.cr_is_vendor=v2.vnd_id AND cr.cr_status=1												
							LEFT JOIN contact_phone cp ON cp.phn_contact_id = cr.cr_contact_id AND (cp.phn_is_verified=1 OR cp.phn_is_primary=1)  AND cp.phn_active=1
							WHERE bvr.bvr_booking_id =:bkgId AND bvr.bvr_accepted=2 GROUP BY v2.vnd_id";
		$command		 = DBUtil::command($sql, DBUtil::SDB());
		$command->params = $params;
		$count			 = DBUtil::queryScalar("SELECT COUNT(1) FROM ({$command->getText()} ) temp", DBUtil::SDB(), $command->params);
		$dataProvider	 = new CSqlDataProvider($command, [
			"params"		 => $params,
			"totalItemCount" => $count,
			"params"		 => $command->params,
			'db'			 => DBUtil::SDB(),
			'pagination'	 => array('pageSize' => 50)
		]);
		return $dataProvider;
	}

	public static function getBidRange($maxBidAmount, $maxAllowableVendorAmount)
	{
		$diffAmt	 = ($maxAllowableVendorAmount - $maxBidAmount);
		$mainArray	 = array();
		array_push($mainArray, array("text" => '95%+ chance', 'val' => " Lower <==", 'color' => "#0BDA51"));
		array_push($mainArray, array("text" => '85% chance', 'val' => " " . ($maxBidAmount + ($diffAmt * 0.20)) . " <== ", 'color' => "#33E770"));
		array_push($mainArray, array("text" => '70% chance', 'val' => " ==> " . ($maxBidAmount + ($diffAmt * 0.50)), 'color' => "#A0F2BC"));
		array_push($mainArray, array("text" => '55% chance', 'val' => " ==> " . ($maxBidAmount + ($diffAmt * 0.80)), 'color' => "#FFB070"));
		array_push($mainArray, array("text" => '40% chance', 'val' => ' ==> Higher', 'color' => "#F46262"));
		return $mainArray;
	}

	/**
	 * add snooze time in booking vendor request
	 * @param type $tripId
	 * @param type $snoozeTime
	 * @param type $vendorId
	 */
	public static function addSnoozeTime($tripId, $snoozeTime, $vendorId)
	{
		$params = ["tripId" => $tripId, "vendorId" => $vendorId, "snoozeTime" => $snoozeTime, "notifyStatus" => 0];

		$sql	 = "UPDATE booking_vendor_request
					SET bvr_snooze_time = :snoozeTime,
					bvr_notification_sent = :notifyStatus
					WHERE bvr_vendor_id=:vendorId AND bvr_bcb_id=:tripId AND bvr_active = 1";
		$success = DBUtil::command($sql)->execute($params);

		return $success;
	}

	/**
	 * list of gnow booking where snoozed time over need to send notification again
	 * @return type
	 */
	public static function listSnoozeBooking()
	{
		$sql = "SELECT bvr.bvr_booking_id, bvr.bvr_id, bvr.bvr_bcb_id,bvr.bvr_vendor_id
				FROM `booking_vendor_request` bvr
				INNER JOIN booking bkg ON bkg.bkg_id = bvr.bvr_booking_id AND bkg.bkg_status = 2 AND bkg.bkg_pickup_date >now()
				INNER JOIN booking_cab bcb ON bcb.bcb_id = bvr.bvr_bcb_id 
				WHERE (bvr.bvr_snooze_time < now() && `bvr_snooze_time` IS NOT NULL) AND bvr.bvr_notification_sent = 0";

		$data = DBUtil::query($sql, DBUtil::SDB());

		return $data;
	}

	public static function getPendingBookingRequest($vendorId, $pageCount = 0, $filterModel, $offSetCount = 20)
	{
		$vndInfo = VendorPref::getInfoById($vendorId);

		$row		 = AccountTransDetails::getTotTransByVndId($vendorId);
		$carTypeRes	 = VehicleTypes::vendorCabType($vendorId);
		$carType	 = $carTypeRes['vhcLabelId'];
		$car_arr	 = explode(",", $carType);

		$carValArr	 = BookingVendorRequest::carAccess($car_arr);
		$carVal		 = implode(",", $carValArr);
		#print_r($carVal);exit;

		$totTrans			 = ($row['totTrans'] > 0) ? $row['totTrans'] : 0;
		$vndIsGozonowEnabled = ($row['vnp_gozonow_enabled'] < 2) ? 1 : 0;
		$vndIsFreeze		 = ($row['vnd_is_freeze'] > 0) ? $row['vnd_is_freeze'] : 0;
		$vndCodFreeze		 = ($row['vnd_cod_freeze'] > 0) ? $row['vnd_cod_freeze'] : 0;

		$vndBoostEnable	 = ($vndInfo['vnp_boost_enabled'] > 0) ? $vndInfo['vnp_boost_enabled'] : 0;
		$vendorStatus	 = $vndInfo['vnd_active'];

		$vndStatInfo = VendorStats::model()->fetchMetric($vendorId);

		$vndRating		 = ($vndStatInfo['vrs_vnd_overall_rating'] == null) ? 0 : $vndStatInfo['vrs_vnd_overall_rating'];
		$vndStickyScr	 = ($vndStatInfo['vrs_sticky_score'] == null) ? 4 : $vndStatInfo['vrs_sticky_score'];
		$vndPenaltyCount = $vndStatInfo['vrs_penalty_count'];
		$vndDriverApp	 = $vndStatInfo['vrs_driver_app_used'];
		$vndDependency	 = ($vndStatInfo['vrs_dependency'] == null || $vndStatInfo['vrs_dependency'] == '') ? 0 : $vndStatInfo['vrs_dependency'];
		$vndBoostPercent = ($vndStatInfo['vrs_boost_percentage'] == null) ? 0 : $vndStatInfo['vrs_boost_percentage'];

		$acptBidPercent	 = ($vndBoostEnable > 0) ? (5 - $vndBoostPercent * 0.01 * 2) : 5;
		$zones			 = implode(",", $filterModel->zones);
		$query			 = "";
		if ($zones != "")
		{
			$sqlServiceZone	 = $zones;
			$query			 = "AND (zct.zct_zon_id IN ($sqlServiceZone) AND zct.zct_cty_id NOT IN (" . $vndInfo['vnp_excluded_cities'] . "))";
		}
		else
		{
			$sqlServiceZone	 = "SELECT hsz_service_id FROM home_service_zones WHERE hsz_home_id IN ({$vndInfo['vnp_home_zone']})";
			$query			 = "AND ((zct.zct_zon_id IN ($sqlServiceZone) AND zct.zct_cty_id NOT IN (" . $vndInfo['vnp_excluded_cities'] . ")) OR zct.zct_zon_id IN (" . $vndInfo['vnp_home_zone'] . "))";
		}
		if ($vndIsFreeze > 0 && $row['vnp_low_rating_freeze'] <= 0 && $row['vnp_doc_pending_freeze'] <= 0 && $row['vnp_manual_freeze'] <= 0)
		{
			$vndIsFreeze = 0;
		}
		if ($vndInfo['vnd_cat_type'] == 1)
		{
			// Check DCO bookings to be excluded if he is already assigned with another trip at the same time
			$sql			 = "SELECT GROUP_CONCAT(bkgnew.bkg_id)
					FROM   booking_cab bcb
					INNER JOIN booking ON  bcb.bcb_id = booking.bkg_bcb_id AND bkg_status IN (3, 5, 6, 7) AND bcb_vendor_id = $vendorId AND (bcb_start_time > NOW() OR bcb_end_time > NOW())
					INNER JOIN booking_cab bcbnew ON (bcbnew.bcb_start_time BETWEEN bcb.bcb_start_time AND bcb.bcb_end_time OR bcbnew.bcb_end_time BETWEEN bcb.bcb_start_time AND bcb.bcb_end_time)
					INNER JOIN booking bkgnew ON bkgnew.bkg_bcb_id = bcbnew.bcb_id AND bkgnew.bkg_status = 2 AND bkgnew.bkg_from_city_id 
					INNER JOIN zone_cities zct ON zct.zct_cty_id = bkgnew.bkg_from_city_id
					WHERE  bcb.bcb_active = 1 AND bcbnew.bcb_active = 1 $query";
			$excludeBookings = DBUtil::querySCalar($sql, DBUtil::SDB());
		}
		$excludeBookings = ($excludeBookings == null || $excludeBookings == '') ? -1 : $excludeBookings;

		/** According to discussion on 24/08/22 with AK & KG in pending list all types of booking will not shown except service added with vendor* */
		if ($vndInfo['vnp_oneway'] != 1)
		{
			$condSelectProfile .= " AND booking.bkg_booking_type NOT IN(1,14)";
		}
		if ($vndInfo['vnp_round_trip'] != 1)
		{
			$condSelectProfile .= " AND booking.bkg_booking_type NOT IN(2)";
		}
		if ($vndInfo['vnp_multi_trip'] != 1)
		{
			$condSelectProfile .= " AND booking.bkg_booking_type NOT IN(3)";
		}
		if ($vndInfo['vnp_airport'] != 1)
		{
			$condSelectProfile .= " AND booking.bkg_booking_type NOT IN(4,12)";
		}
		if ($vndInfo['vnp_package'] != 1)
		{
			$condSelectProfile .= " AND booking.bkg_booking_type NOT IN(5)";
		}
		if ($vndInfo['vnp_flexxi'] != 1)
		{
			$condSelectProfile .= " AND booking.bkg_booking_type NOT IN(6)";
		}
		if ($vndInfo['vnp_daily_rental'] != 1)
		{
			$condSelectProfile .= " AND booking.bkg_booking_type NOT IN(9,10,11)";
		}

		$tiers		 = [];
		$serviceTier = $filterModel->tierList;
		foreach ($serviceTier as $service)
		{
			if ($service->name == 'select')
			{
				$tiers[] = 4;
				$tiers[] = 5;
			}
			else if ($service->name == 'value')
			{
				$tiers[] = 1;
				$tiers[] = 6;
			}
			else
			{
				$tiers[] = $service->id;
			}
		}
		$serviceTierList = $filterModel->tiers;
		foreach ($serviceTierList as $service)
		{
			if ($service == 3)
			{
				$tiers[] = 4;
				$tiers[] = 5;
			}
			else if ($service == 1)
			{
				$tiers[] = 1;
				$tiers[] = 6;
			}
			else
			{
				$tiers[] = $service;
			}
		}
		$tier_string = implode(",", array_unique($tiers));

		if ($tier_string != "")
		{
			$condSelectTierCheck		 = " AND service_class.scc_id IN ($tier_string) ";
			$condSelectTierCheckMatched	 = " AND scc.scc_id IN ($tier_string) ";
		}
		else
		{
			$condSelectTierCheck		 = " AND service_class.scc_id IN ({$vndInfo['vnp_is_allowed_tier']}) ";
			$condSelectTierCheckMatched	 = " AND scc.scc_id IN ({$vndInfo['vnp_is_allowed_tier']}) ";
		}


		$search_qry		 = '';
		$search_txt		 = $filterModel->searchTxt;
		$allowedZones	 = implode(',', array_filter(explode(',', $vndInfo['vnp_accepted_zone'] . ',' . $vndInfo['vnp_home_zone'])));
		if ($search_txt != '')
		{
			$search_txt	 = addslashes($search_txt);
			$search_qry	 = " AND
				(
					vehicle_category.vct_label LIKE '%$search_txt%'
					OR bcb_id LIKE '%$search_txt%'
					OR booking.bkg_id LIKE '%$search_txt%'
					OR booking.bkg_booking_id LIKE '%$search_txt%'
					OR  booking.bkg_route_city_names LIKE '%$search_txt%'
				)";
		}

		$search_qry .= "  AND ((fzc.zct_zon_id IN ({$sqlServiceZone}) OR tzc.zct_zon_id IN ({$sqlServiceZone}))
							AND (fzc.zct_cty_id NOT IN ({$vndInfo['vnp_excluded_cities']}) OR tzc.zct_cty_id NOT IN ({$vndInfo['vnp_excluded_cities']}))
							AND  booking.bkg_id NOT IN($excludeBookings)	
						)";

		if ($filterModel->sort == 'newestBooking')
		{
			$sortCond = "ORDER BY bkgIds DESC,isGozoNow DESC";
		}
		if ($filterModel->sort == 'earliestBooking')
		{
			$sortCond = "ORDER BY bkg_pickup_date ASC";
		}

		$limitCond	 = "LIMIT $pageCount, $offSetCount";
		$date		 = $filterModel->date;
		if (!empty($filterModel))
		{
			$bidStatus	 = $filterModel->bidStatus;
			$filter_qry	 = "";
			if ($bidStatus == 1)
			{
				$isBid		 = true;
				$filter_qry	 .= " AND (bvr_id IS NOT NULL AND bvr_accepted = 1 AND bvr_vendor_id = $vendorId)";
			}
			$serviceType = $filterModel->serviceType;
			if ($serviceType == 'local')
			{
				$filter_qry .= " AND booking.bkg_booking_type IN(4,9,10,11,12,14,15,16)";
			}
			if ($serviceType == 'outstation')
			{
				$filter_qry .= " AND booking.bkg_booking_type IN(1,2,3,5,6,7,8)";
			}
			if ($serviceType == 'all')
			{
				$filter_qry .= " AND booking.bkg_booking_type IN(1,2,3,5,6,7,8,4,9,10,11,12,14,15,16)";
			}

			if ($date != "")
			{
				$fromDate	 = $date . " 00:00:00";
				$toDate		 = $date . " 23:59:59";
				$filter_qry	 .= " AND booking.bkg_pickup_date BETWEEN '$fromDate' AND '$toDate' ";
			}
			else
			{
				$filter_qry .= " AND booking.bkg_pickup_date > DATE_SUB(NOW(), INTERVAL 1 DAY) ";
			}
		}

		$val = '"';

		$acceptBidPercent	 = "GetVendorAcceptMargin2('{$vndInfo['vnp_home_zone']}', '{$vndInfo['vnp_accepted_zone']}', GROUP_CONCAT(DISTINCT fzc.zct_zon_id), GROUP_CONCAT(DISTINCT tzc.zct_zon_id), $vndBoostEnable, $vndBoostPercent, IFNULL(bkg_critical_score,0.65),IFNULL(btr_is_dem_sup_misfire,0))";
		$acceptableAmount	 = "ROUND(booking_cab.bcb_vendor_amount * 0.01 * $acceptBidPercent)";
		/* $lowSMTAmount is used when smt score is less than 0 */
		$lowSMTAmount		 = "ROUND(booking_cab.bcb_vendor_amount * 0.01 * ($acceptBidPercent - 5))";

		$calculateSMTSql = "CalculateSMT(bcb_vendor_amount + SUM(bkg_gozo_amount),booking_cab.bcb_vendor_amount,
					    $acceptableAmount, $vndRating, $vndStickyScr, $vndPenaltyCount, $vndDriverApp, $vndDependency, $vndBoostPercent)";

		$isAcceptAllowed			 = "IsDirectAcceptAllowed('{$vndInfo['vnp_home_zone']}', GROUP_CONCAT(DISTINCT fzc.zct_zon_id), GROUP_CONCAT(DISTINCT tzc.zct_zon_id), bkg_manual_assignment, $calculateSMTSql, bkg_critical_score, MIN(booking.bkg_pickup_date), GREATEST(IFNULL(bcb_bid_start_time, MAX(bkg_confirm_datetime)), MAX(bkg_confirm_datetime)))";
		$validateAcceptableAmountSQL = "IF(bkg_critical_assignment=1 OR bkg_manual_assignment=1 OR booking.bkg_booking_type = 12, ROUND(booking_cab.bcb_vendor_amount * 0.98), IF($calculateSMTSql>=0, $acceptableAmount, $lowSMTAmount))";

		$calRecomendedAmount = "IF(bkg_critical_assignment=1 OR bkg_manual_assignment=1 , ROUND(booking_cab.bcb_vendor_amount * 0.98), ROUND(booking_cab.bcb_vendor_amount * 0.98))";

		//$showBookingCnd = "AND (booking.bkg_status=2 OR (booking.bkg_status IN (3,5) AND booking_cab.bcb_vendor_id <> $vendorId AND booking.bkg_pickup_date > DATE_ADD(NOW(),INTERVAL 6 hour) ))";
		$showBookingCnd = " AND booking.bkg_status=2 AND (booking.bkg_reconfirm_flag=1 OR booking_pref.bkg_is_gozonow=1 ) ";

		// Temporary Table
		$randomNumber	 = rand();
		$createTempTable = "tmpbvr_{$vendorId}_{$randomNumber}";
		DBUtil::dropTempTable($createTempTable);

		$sqlTemp	 = " (INDEX index_tmpbvr (bvr_bcb_id)) (
						SELECT bvr_id, bvr_accepted, bvr_vendor_id, bvr_bcb_id, bvr_bid_amount FROM booking 
						INNER JOIN booking_vendor_request ON bvr_bcb_id = bkg_bcb_id 
						AND bvr_active = 1 AND bkg_status = 2 
						AND bvr_accepted <> 2 
						AND bvr_vendor_id = $vendorId $filter_qry 
					) ";
		DBUtil::createTempTable($createTempTable, $sqlTemp);
		$sqlTemp11	 = " (
						SELECT bvr_id, bvr_accepted, bvr_vendor_id, bvr_bcb_id, bvr_bid_amount FROM booking 
						INNER JOIN booking_vendor_request ON bvr_bcb_id = bkg_bcb_id 
						AND bvr_active = 1 AND bkg_status = 2 
						AND bvr_accepted <> 2 
						AND bvr_vendor_id = $vendorId $filter_qry 
					) ";

		$sqlMain = "SET STATEMENT max_statement_time=10 FOR
			SELECT  
			    IF(booking_pref.bkg_cng_allowed =1 AND (bkgaddinfo.bkg_num_large_bag < 2  OR bkgaddinfo.bkg_num_large_bag >1 ) ,1,0 ) AS is_cng_allowed,
			    IF(booking.bkg_booking_type IN(4,9,10,11,12,15), 'local', 'outstation')AS businesstype,					
			    IF(booking.bkg_flexxi_type IN(1,2),true,false) isFlexxi,
			    bcb_id,booking.bkg_create_date,booking.bkg_trip_distance,
				booking.bkg_trip_duration,booking.bkg_booking_type,booking.bkg_status,
				GROUP_CONCAT(booking.bkg_route_city_names) as bkg_route_name,
			    GROUP_CONCAT(DISTINCT bkg_id) bkgIds,
			    GROUP_CONCAT(DISTINCT bkg_booking_id) bkgBookingIds,
				CONCAT('{',ct1.cty_id,':\"',ct1.cty_name,'\",',GROUP_CONCAT( CONCAT(ct2.cty_id,':\"',ct2.cty_name,'\"') SEPARATOR ','),'}') AS routeName,
			    trim(replace(replace(replace(replace(`bkg_route_city_names`,'[',''),']',''),'$val,',' -'),'$val','')) AS bkg_route_name,vehicle_types.vht_make,vehicle_types.vht_model,  
			    bcb_bid_start_time, btr.btr_manual_assign_date, btr.btr_critical_assign_date,
			    GREATEST(COALESCE(bcb_bid_start_time,0), COALESCE(btr_manual_assign_date,0), COALESCE(btr_critical_assign_date,0)) as booking_priority_date,
			    CASE bkg_booking_type WHEN 1 THEN IF(bcb_trip_type=1,'MATCHED','ONE WAY') WHEN 2 THEN 'ROUND TRIP' WHEN 3 THEN 'MULTI WAY' WHEN 4 THEN 'ONE WAY' WHEN 5 THEN 'PACKAGE' WHEN 8 THEN 'PACKAGE' WHEN 9 THEN 'DAY RENTAL 4hr-40km' WHEN 10 THEN 'DAY RENTAL 8hr-80km' WHEN 11 THEN 'DAY RENTAL 12hr-120km' WHEN 12 THEN 'Airport Packages'  WHEN 15 THEN 'Local Transfer' ELSE 'SHARED' END AS booking_type,
			    IF(booking.bkg_booking_type = 12 OR ($isAcceptAllowed AND booking.bkg_reconfirm_flag=1 AND bkg_block_autoassignment=0), IF(bkg_status IN (3,5), 1,IF($vendorStatus=2,1,0)),1) AS is_biddable,
			    IF(booking.bkg_agent_id > 0, 1, 0) AS is_agent,
			     vehicle_category.vct_label AS cab_model,
			    $calRecomendedAmount AS recommended_vendor_amount,
				service_class.scc_id as cab_lavel_id,
			    service_class.scc_label AS cab_lavel,
				 biv.bkg_promo1_id,biv.bkg_promo1_code,biv.bkg_promo2_id,biv.bkg_promo2_code,biv.bkg_discount_amount,biv.bkg_discount_amount,
			    booking_cab.bcb_vendor_amount AS max_bid_amount,
			    (booking_cab.bcb_vendor_amount * 0.7) AS min_bid_amount,
			    IF(booking_cab.bcb_trip_type > 0, 1, 0) AS is_matched,
			    IF(vehicle_category.vct_id IN(5, 6),1,0) AS is_assured,
				IF(bkgaddinfo.bkg_no_person > 0,bkgaddinfo.bkg_no_person,vehicle_category.vct_capacity) AS seatingCapacity,
                IF(bkgaddinfo.bkg_num_large_bag > 0,bkgaddinfo.bkg_num_large_bag,vehicle_category.vct_big_bag_capacity) AS bigBagCapacity,
                IF(bkgaddinfo.bkg_num_small_bag > 0,bkgaddinfo.bkg_num_small_bag,vehicle_category.vct_small_bag_capacity) AS bagCapacity,
			    MIN(booking.bkg_pickup_date) bkg_pickup_date,
			    bkg_return_date,
				IF(bkg_is_gozonow = 1, 1, 0) AS isGozoNow,
				bkg_is_gozonow, 
			    bcb_end_time trip_completion_time,
			    biv.bkg_total_amount,
				SUM(biv.bkg_total_amount) totalTripAmount,
				biv.bkg_quoted_vendor_amount as quoteVendorAmt,
			    $calculateSMTSql AS smtScore,
			    $validateAcceptableAmountSQL AS acptAmount,
			    IFNULL(bvr_bid_amount,0) AS bvr_bid_amount,
			    (CASE WHEN (($vndIsFreeze = 1) AND ($totTrans > 0) AND biv.bkg_advance_amount <=(biv.bkg_total_amount * 0.3)) THEN '1' WHEN($vndIsFreeze = 1) THEN '2' ELSE '0' END) AS payment_due,
					(
                    CASE (CASE WHEN (($vndIsFreeze = 1) AND ($totTrans > 0) AND biv.bkg_advance_amount <=(biv.bkg_total_amount * 0.3)) THEN '1' WHEN($vndIsFreeze = 1) THEN '2' ELSE '0' END) WHEN '1' THEN CONCAT(
                        'Your amount due is ',
                        ABS($totTrans),
                        '. Please send payment immediately'
                    ) WHEN '2' THEN 'Your Gozo Account is temporarily frozen. Please contact your Account Manager or Gozo Team to have it resolved.' WHEN '0' THEN ''
                    END
					) AS payment_msg,bkg_night_pickup_included,bkg_night_drop_included 
			FROM booking
			INNER JOIN booking_cab ON bcb_id = bkg_bcb_id AND bcb_active = 1
			INNER JOIN booking_invoice biv ON biv.biv_bkg_id = booking.bkg_id
			 
			INNER JOIN booking_add_info bkgaddinfo ON  booking.bkg_id = bkgaddinfo.bad_bkg_id
			INNER JOIN booking_pref ON bpr_bkg_id = booking.bkg_id
			INNER JOIN booking_trail btr ON btr_bkg_id = booking.bkg_id
			INNER JOIN svc_class_vhc_cat scv ON scv.scv_id = booking.bkg_vehicle_type_id
			LEFT JOIN vehicle_types ON vehicle_types.vht_id = scv.scv_model AND scv.scv_model > 0 
			INNER JOIN vehicle_category ON vehicle_category.vct_id = scv.scv_vct_id
			INNER JOIN service_class ON service_class.scc_id = scv.scv_scc_id
			INNER JOIN booking_route ON brt_bkg_id = booking.bkg_id
			INNER JOIN cities ct1 ON ct1.cty_id = booking_route.brt_from_city_id
			INNER JOIN cities ct2 ON ct2.cty_id = booking_route.brt_to_city_id			 	
			INNER JOIN zone_cities fzc ON fzc.zct_cty_id=ct1.cty_id AND fzc.zct_active=1
			INNER JOIN zone_cities tzc ON tzc.zct_cty_id=ct2.cty_id AND tzc.zct_active=1					
			 
			LEFT JOIN $createTempTable bvrTemp ON bvrTemp.bvr_bcb_id = bkg_bcb_id  
			WHERE  bcb_active = 1  
			 $filter_qry $condSelectProfile $showBookingCnd $search_qry 
			GROUP BY bcb_id $sortCond  $limitCond";
//echo $sqlMain; exit;
		Logger::info('SQL MAIN ===>' . $sqlMain);
		$data	 = DBUtil::query($sqlMain, DBUtil::SDB());
//		var_dump($data);
		DBUtil::dropTempTable($createTempTable);
		return $data;
	}

	/**
	 * 
	 * @param type $tripId
	 * @param type $vendorId
	 * @param type $userInfo
	 * @return \ReturnSet
	 */
	public static function denyTripByVendor($tripId, $vendorId, $userInfo)
	{
		$returnSet	 = new ReturnSet();
		$success	 = false;
		$bvrModels	 = BookingVendorRequest::model()->findByBcbIdAndVendorId($tripId, $vendorId);
		if (count($bvrModels) >= 1)
		{
			foreach ($bvrModels as $bvrModel)
			{
				$bvrModel->bvr_bid_amount	 = 0;
				$bvrModel->bvr_accepted		 = 2;
				$bvrModel->bvr_assigned		 = 2;
				$bvrModel->bvr_assigned_at	 = new CDbExpression('NOW()');
				$bvrModel->bvr_accepted_at	 = new CDbExpression('NOW()');
				$success					 = $bvrModel->save();
				if (!$success)
				{
					$errors = $bvrModel->getErrors();
					$returnSet->setErrors($errors);
					return $returnSet;
				}
			}
		}
		else
		{
			$success = BookingVendorRequest::model()->createRequest(0, $tripId, $vendorId, 'deny');
			if (!$success)
			{
				$error = 'Bid deny failed';
				$returnSet->setMessage($error);
				return $returnSet;
			}
		}
		if ($success)
		{
			$bcabModel	 = BookingCab::model()->findByPk($tripId);
			$bModels	 = $bcabModel->bookings;
			$eventId	 = BookingLog::BID_DENY;
			$desc		 = "Bid denied by vendor.";
			foreach ($bModels as $bModel)
			{
				BookingLog::model()->createLog($bModel->bkg_id, $desc, $userInfo, $eventId);
			}
			$returnSet->setMessage("Request processed successfully");
		}
		$ntlId = NotificationLog::getIdForGozonow($vendorId, $tripId);
		if ($ntlId > 0)
		{
			$ntlDataArr	 = ['id' => $ntlId, 'isRead' => 1];
			$resultData	 = NotificationLog::updateReadNotification($ntlDataArr);
		}
		$returnSet->setStatus($success);
		return $returnSet;
	}

	public static function acceptTripByVendor($tripId, $vendorId, $bidAmount, $userInfo, $isDirectAccept = false, $bidAction = 1, $validatemessage = "")
	{
		$returnSet				 = new ReturnSet();
		$success				 = false;
		$cabModel				 = BookingCab::model()->findByPk($tripId);
		$bModels				 = $cabModel->bookings;
		$bModel1				 = $bModels[0];
		$vendorModel			 = Vendors::model()->findByPk($vendorId);
		//if bid amount same or lower than accepted amount start here
		$directAcptAmount		 = 0;
		$directAcptAmount		 = BookingVendorRequest::getDirectAcceptAmount($vendorId, $tripId);
		$dependency				 = $vendorModel->vendorStats->vrs_dependency;
		$calculateDependency	 = ($dependency == '') ? 0 : $dependency;
		$errorMessage			 = [];
		$calculateLockedAmount	 = 0;
		$securityAmountFlag		 = 0;
		$lAmount				 = 0;
		$transaction			 = DBUtil::beginTransaction();
		try
		{
			$criticalityScore	 = $bModel1->bkgPref->bkg_critical_score;
			$dependencyStatus	 = VendorStats::checkDependency($criticalityScore, $vendorId);
			if (!$dependencyStatus && $isDirectAccept)
			{
				$errorMessage[] = "Dependability score low. Direct accept not available for you. To improve dependability score, do not refuse booking after you accept.";
				throw new Exception(CJSON::encode($errorMessage), ReturnSet::ERROR_VALIDATION);
			}

			if ($bidAmount > $directAcptAmount && $isDirectAccept)
			{
				$errorMessage[] = "Direct accept is not available as the bid amount is higher than the required amount.";
				throw new Exception(CJSON::encode($errorMessage), ReturnSet::ERROR_VALIDATION);
			}
			//check security amount 

			$calculateLockedAmount = BookingInvoice::calculateLockAmount($bModel1->bkg_id, $bidAmount, $vendorId);
			if ($calculateLockedAmount > 25)
			{
				$securityAmountFlag	 = 1;
				$lAmount			 = ceil($calculateLockedAmount / 25) * 25;
				$lAmount			 = max($lAmount, 100);
			}
			$directAcceptMode = $isDirectAccept;
			if ($bidAmount <= $directAcptAmount && $dependencyStatus && $calculateLockedAmount < 25 && $directAcceptMode)
			{
				$isDirectAccept = true;
			}
			else
			{
				$isDirectAccept = false;
			}
			if ($isDirectAccept)
			{
				$status = BookingVendorRequest::DirectAccept($bidAmount, $vendorId, $tripId, $userInfo);
				if ($status == true)
				{
					foreach ($bModels as $bModel)
					{
						$eventId = BookingLog::VENDOR_ASSIGNED;
						$message = "The booking is assigned to you";
						$desc	 = "Vendor accept amount: ₹" . $directAcptAmount . " Vendor bid amount: ₹" . $bidAmount . ". Booking is direct accepted ";
						$res	 = BookingLog::model()->createLog($bModel->bkg_id, $desc, $userInfo, $eventId);
					}
					//modify assign logic for cab and driver
					//check vendor is dco or not
					$dco = $vendorModel->vnd_is_dco;
					if ($dco == 1)
					{
						$success = BookingVendorRequest::assignDefaultCabDriverForVendor($tripId, $vendorId);

						if ($success == false)
						{
							$errorMessage[] = "Unable to accept";
							throw new Exception(CJSON::encode($errorMessage), ReturnSet::ERROR_VALIDATION);
						}
					}
				}
			}
			else
			{
				$data = array("securityFlag"	 => $securityAmountFlag,
					"securityAmount" => $lAmount);

				if ($directAcceptMode == true && $calculateLockedAmount > 25 && $bidAction == 2)
				{
					$message		 = "Oops! Your payment is overdue. Please settle your Gozo accounts ASAP.";
					$errorMessage[]	 = $message;
					$returnSet->setStatus(false);
					$returnSet->setMessage($message);
					$returnSet->setErrors($errorMessage);
					$returnSet->setData($data);

					return $returnSet;
				}
				$result = BookingVendorRequest::model()->createRequest($bidAmount, $tripId, $vendorId);
				if ($result)
				{
					$vendorStat				 = VendorStats::model()->getbyVendorId($vendorId);
					$vendorStat->vrs_tot_bid = $vendorStat->vrs_tot_bid + 1;
					$vendorStat->save();
					if ($validatemessage != "")
					{
						$validatemessage = "Your chance of winning it is very less because " . $validatemessage;
					}
					$eventId = BookingLog::BID_SET;
					$desc	 = "Bid of ₹" . $bidAmount . " provided. " . $validatemessage;

					$message = "Your bid is accepted." . $validatemessage;
					if ($calculateLockedAmount > 25)
					{
						$message = "Your bid has been accepted but your chance of winning it is very less due to your low account balance. Please pay $lAmount to increase your chance of winning this bid..";
						$returnSet->setData($data);
					}
					foreach ($bModels as $bModel)
					{
						$res = BookingLog::model()->createLog($bModel->bkg_id, $desc, $userInfo, $eventId);
					}
				}
			}

			DBUtil::commitTransaction($transaction);
			$bidRequest = \Beans\vendor\BidRequest::setData($bModels[0]->bkg_id, $tripId, $vendorId, $bidAmount, $bModels[0]->bkg_pickup_date, $bModels[0]->bkgInvoice->bkg_toll_tax, $bModels[0]->bkgInvoice->bkg_state_tax, $bModels[0]->bkg_trip_distance, $bModels[0]->bkgPref->bpr_row_identifier);
			IRead::setVendorBidRequest($bidRequest);
		}
		catch (Exception $e)
		{
			Logger::exception($e);
			DBUtil::rollbackTransaction($transaction);
			throw $e;
		}
		$returnSet->setStatus(true);
		$returnSet->setMessage($message);

		return $returnSet;
	}

	public static function assignDefaultCabDriverForVendor($tripId, $vndId)
	{
		$bcabModel		 = BookingCab::model()->findByPk($tripId);
		$bModels		 = $bcabModel->bookings;
		$bModel1		 = $bModels[0];
		$cttId			 = \ContactProfile::getByVndId($vndId);
		$vehicleTypeId	 = $bModel1->bkg_vehicle_type_id;
		$drvData		 = \Drivers::getDefaultByContact($cttId);
		$cabId			 = \Vehicles::getPrefferedByContact($cttId, $vehicleTypeId);
		if (!empty($drvData))
		{
			$drvphone					 = $drvData['drv_phone'];
			$driverId					 = $drvData['drv_id'];
			$bcabModel->bcb_driver_phone = $drvphone;
			$bcabModel->bcb_driver_id	 = $driverId;
		}


		$bcabModel->bcb_cab_id	 = $cabId;
		$cab_type				 = $bModel1->bkgSvcClassVhcCat->scv_vct_id;

		$userInfo	 = UserInfo::getInstance();
		$success	 = $bcabModel->assignCabDriver($cabId, $driverId, $cab_type, $userInfo);
		if (!$success)
		{
			$errors = $bcabModel->getErrors();
//			$errorStr	 = stripslashes($errors[key($errors)][0]);
			throw new Exception(CJSON::encode($errors), ReturnSet::ERROR_VALIDATION);
		}
		return $success;
	}

	public static function showBidTime($tripId, $vndId)
	{
		$params	 = ["tripId" => $tripId, "vendorId" => $vndId];
		$sql	 = "SELECT bvr_created_at FROM `booking_vendor_request` WHERE bvr_bcb_id=:tripId AND bvr_vendor_id=:vendorId";
		$bidTime = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
		return $bidTime;
	}

	public static function getAssignDate($bcbId, $vendorId)
	{
		$sql = "SELECT 	bvr_assigned_at FROM `booking_vendor_request` WHERE `bvr_bcb_id` = :bvr_bcb_id AND `bvr_vendor_id` = :bvr_vendor_id";
		$row = DBUtil::queryScalar($sql, DBUtil::SDB(), ['bvr_bcb_id' => $bcbId, 'bvr_vendor_id' => $vendorId]);
		return $row;
	}

	/**
	 * 
	 * @param type $bcbId
	 * @return type
	 */
	public static function getOfferCountByTrip($bcbId)
	{
		$params	 = ['bcbId' => $bcbId];
		$sql	 = "SELECT  DISTINCT count(bvr.bvr_id) 
					FROM booking_vendor_request bvr 
					WHERE bvr.bvr_bcb_id=:bcbId AND bvr_bid_amount > 0 AND bvr_accepted = 1 AND bvr_active = 1";
		$rows	 = DBUtil::queryScalar($sql, DBUtil::MDB(), $params);
		return $rows;
	}

	public static function notifyVendorOnPaymentInitiated($bcbId)
	{
		$dataExist = BookingVendorRequest::getPreferredVendorbyBooking($bcbId);
		if ($dataExist)
		{
			$vndId	 = $dataExist['bvr_vendor_id'];
			$message = 'Customer has initiated the payment for the Trip ID: ' . $bcbId . ', the booking will show up in your queue when payment is completed';
			$title	 = 'Waiting for customer payment';
			$success = AppTokens::notifyVendorGnowBooking($vndId, $bcbId, $message, $title);
		}
	}

	public static function assignmentValidation($bcbId, $vendorId)
	{
		$vendorModel		 = Vendors::model()->findByPk($vendorId);
		$dependencyScore	 = $vendorModel->vendorStats->vrs_dependency;
		$codFreeze			 = $vendorModel->vendorPrefs->vnp_cod_freeze;
		$isApproveCar		 = $isApproveDriver	 = $isDocApprove		 = $isApproveBooking	 = false;
		$bookingCabModel	 = BookingCab::model()->findByPk($bcbId);
		$bookingModels		 = $bookingCabModel->bookings;
		$noDirectAcpt		 = 0;
		if ($vendorModel->vnd_active == 4)
		{
			$pendingApproval = true;
		}
		if ($pendingApproval == true)
		{
			$noDirectAcpt = 1;
		}
		if (($vendorModel->vnd_active == 1) || ($vendorModel->vendorPrefs->vnp_is_orientation > 0))
		{
			$isDocApprove = true;
		}
		$isApproveCar	 = ($vendorModel->vendorStats->vrs_approve_car_count > 0) ? true : false;
		$isApproveDriver = ($vendorModel->vendorStats->vrs_approve_driver_count > 0) ? true : false;

		if ($isDocApprove == false)
		{
			$noDirectAcpt = 1;
		}
		if ($isApproveCar == false || $isApproveDriver == false || $vendorModel->vnd_active == 2)
		{
			$noDirectAcpt = 1;
		}
		foreach ($bookingModels as $bModel)
		{
			$booking_class	 = $bModel->bkgSvcClassVhcCat->scc_ServiceClass->scc_id;
			$bookingType	 = $bModel->bkg_booking_type;
			if (!Vehicles::checkVehicleclass($vendorId, $booking_class))
			{
				$noDirectAcpt = 1;
			}
			$dataCount = VendorPref::checkApprovedService($vendorId, $bookingType);
			if ($dataCount < 1)
			{
				if (($bookingType == 4 || $bookingType == 12)) // all vendor can able to bid for airport
				{
					goto skip;
				}

				$noDirectAcpt = 1;
			}
			skip:

			$check_availability = Vehicles::checkVehicleAvailability($vendorId, $bookingCabModel->bcb_start_time, $bookingCabModel->bcb_end_time, $bModel->bkg_from_city_id, $bModel->bkg_to_city_id, $bModel->bkgSvcClassVhcCat->scv_vct_id);
			if ($check_availability != "")
			{
				$noDirectAcpt = 1;
			}
			$isVendorUnassigned = BookingLog::isVendorUnAssigned($vendorId, $bModel->bkg_id);
			if ($isVendorUnassigned)
			{
				$noDirectAcpt = 1;
			}
		}
		return $noDirectAcpt;
	}

	public function validateCondition($tripId, $bidAmount, $entityId, $source = "bid", $bidAction = 1)
	{
		$allowDirectAccept	 = true;
		$cabModel			 = BookingCab::model()->findByPk($tripId);
		$bModels			 = $cabModel->bookings;
		foreach ($bModels as $bModel)
		{
			//$err[]	 = $this->reconfirmValidation($bModel);
			$err[]	 = $this->minMaxValidation($cabModel, $bidAmount);
			$err[]	 = $this->unassignValidation($entityId, $bModel);
			$err[]	 = $this->approveServiceValidation($bModel, $entityId);
			$err[]	 = $this->assignStatusValidation($bModel);

			$res = $this->cashBookingValidation($entityId, $bModel->bkg_id);
			if (!$res)
			{
				$allowDirectAccept	 = false;
				$message			 = "Sorry! You do not have permission to accept cash booking.";
			}
			$criticalityScore	 = $bModel->bkgPref->bkg_critical_score;
			$result				 = $this->vendorProfileValidation($entityId, $criticalityScore);

			if (!empty($result))
			{
				$allowDirectAccept	 = false;
				$message			 = implode(",\n", $result);
			}
			$reconfirmError = $this->reconfirmValidation($bModel);
			if ($reconfirmError)
			{
				$allowDirectAccept = false;
				if ($bidAction == 2)
				{
					$message = $reconfirmError;
				}
			}
		}
		$errors = array_filter($err);
		if (!empty($errors))
		{
			throw new Exception(json_encode($errors), ReturnSet::ERROR_VALIDATION);
		}
		$result = ['allowDirectAccept' => $allowDirectAccept, 'message' => $message];

		return $result;
	}

	public function cashBookingValidation($entityId, $bkgId)
	{
		$vendorModel		 = Vendors::model()->findByPk($entityId);
		$vndCodFreeze		 = $vendorModel->vendorPrefs->vnp_cod_freeze;
		$cashBkgValidation	 = BookingInvoice::checkCODBkg($bkgId, $vndCodFreeze);
		return $cashBkgValidation;
	}

	public function assignStatusValidation($bModel)
	{
		if ($bModel->bkg_status != 2 & $bModel->bkg_reconfirm_flag == 1)
		{
			$error = "Oops! This booking is already assigned.";
			return $error;
			//break;
		}
	}

	public function unassignValidation($entityId, $bModel)
	{
		$isVendorUnassigned = BookingLog::isVendorUnAssigned($entityId, $bModel->bkg_id);
		if ($isVendorUnassigned)
		{
			$error = "Sorry were unassigned from / denied this trip before. So you cannot bid on it again.";
			return $error;
		}
	}

	public function reconfirmValidation($bModel)
	{
		if ($bModel->bkg_reconfirm_flag <> 1 || $bModel->bkgPref->bkg_block_autoassignment == 1)
		{

			$error = "Sorry! you are unable to accept this booking directly";
			return $error;
		}
	}

	public function minMaxValidation($bookingCabModel, $bidAmount)
	{
		$arrAllowedBids = $bookingCabModel->getMinMaxAllowedBidAmount();

		if (($bidAmount < $arrAllowedBids['minBid'] || ($bidAmount > $arrAllowedBids['maxBid'] && $arrAllowedBids['maxBid'] > 0)) || ( $bidAmount < $arrAllowedBids['minBid'] ))
		{

			$error = "Bid amount out of range (too low or too high)";
			return $error;
		}
	}

	public function approveServiceValidation($bModel, $entityId)
	{
		$bookingType = $bModel->bkg_booking_type;
		$dataCount	 = VendorPref::checkApprovedService($entityId, $bookingType);
		if ($dataCount < 1)
		{
			if ($bookingType == 4 || $bookingType == 12)
			{
				$statModel		 = VendorStats::model()->getbyVendorId($entityId);
				$dependencyScore = $statModel->vrs_dependency;
				if ($dependencyScore >= 60)
				{
					goto skip;
				}
				$error = "You do not have permission to serve this booking. ";
				return $error;
			}
			else
			{
				$error = "You do not have permission to serve this booking.";
				return $error;
			}
		}
		skip:
	}

	public function vendorProfileValidation($entityId, $criticalityScore)
	{
		$isApproveCar		 = $isApproveDriver	 = $isDocApprove		 = $isApproveBooking	 = $pendingApproval	 = false;

		$vendorModel	 = Vendors::model()->findByPk($entityId);
		$isApproveCar	 = ($vendorModel->vendorStats->vrs_approve_car_count > 0) ? true : false;
		$isApproveDriver = ($vendorModel->vendorStats->vrs_approve_driver_count > 0) ? true : false;
		$error			 = array();
		if ($vendorModel->vnd_active == 4)
		{
			$pendingApproval = true;
		}
		if ($pendingApproval == true)
		{
			$error[] = "Your account is not approve yet. Direct accept not available for you.";
			goto skip;
		}
		if (($vendorModel->vnd_active == 1) || ($vendorModel->vendorPrefs->vnp_is_orientation > 0))
		{
			$isDocApprove = true;
		}

		if ($isDocApprove == false)
		{
			$error[] = "Check documents. Your documents are missing or not yet approved.";
		}
		if ($isApproveCar == false)
		{
			$error[] = "Get 1 car approved before we can send you business.";
		}
		if ($isApproveDriver == false)
		{

			$error[] = "Get 1 driver approved before we can send you business.";
		}
		$dependencyStatus = VendorStats::checkDependency($criticalityScore, $entityId);

		if (!$dependencyStatus)
		{
			$error[] = "Dependability score low. Direct accept not available for you. To improve dependability score, do not refuse booking after you accept.";
		}
		if ($vendorModel->vendorPrefs->vnp_low_rating_freeze == 1 || $vendorModel->vendorPrefs->vnp_doc_pending_freeze == 1 || $vendorModel->vendorPrefs->vnp_manual_freeze == 1)
		{
			$error[] = "Your account is freezed. Cannot doing direct accept.";
		}
		skip:
		return $error;
	}

	/**
	 * 
	 * @param int $bkgId
	 * @return array
	 */
	public static function getGNowLastOfferData($bkgId)
	{
		$params	 = ['bkgId' => $bkgId];
		$qry	 = "SELECT `bvr_id`, `bvr_booking_id`, `bvr_bcb_id`, `bvr_vendor_id`, `bvr_vendor_rating`, 
					REPLACE(json_extract(bvr_special_remarks, '$.reachingAtTime'),'\"','') AS reachingAtTime,
					REPLACE(json_extract(bvr_special_remarks, '$.cabId'),'\"','') AS cabId,
					#REPLACE(json_extract(bvr_special_remarks, '$.driverId'),'\"','') AS driverId,
					bvr_special_remarks,
					`bvr_bid_amount`, `bvr_created_at`, `bvr_accepted_at`,
					CONCAT('bid_',bvr_id,'_',bvr_bid_amount) bidId,
					(300 - TIMESTAMPDIFF(SECOND,bvr.bvr_accepted_at,NOW())) bidexpiretimeLeft
					FROM   booking_vendor_request bvr
					WHERE  bvr_active = 1 AND bvr_accepted = 1 AND bvr_bid_amount >0 
					AND bvr_special_remarks <> '' AND bvr_booking_id = :bkgId 
				GROUP BY bvr_vendor_id,bvr_booking_id
				ORDER BY bvr_id DESC LIMIT 1";
		$val	 = DBUtil::queryRow($qry, DBUtil::SDB(), $params);

		return $val;
	}

	/**
	 * 
	 * @param int $tripId
	 * @return array
	 */
	public static function getGNowLastOffer($bkgId)
	{
		$val = BookingVendorRequest::getGNowLastOfferData($bkgId);
		if ($val)
		{
			$vendorAmount			 = $val['bvr_bid_amount'];
			$vhcid					 = $val['cabId'];
			//$drvid					 = $val['driverId'];
			$model					 = BookingSub::getModelForGNowFromVendorAmount($bkgId, $vendorAmount);
			$vhcRecord				 = Vehicles::getDetailbyid($vhcid);
			//$drvRecord				 = DriverStats::getRatingInfoById($drvid);
			$vhcData				 = ($vhcRecord) ? $vhcRecord : [];
			$drvData				 = ($drvRecord) ? $drvRecord : [];
			$totalAmount			 = $model->bkgInvoice->bkg_total_amount;
			$val['totalCalculated']	 = $totalAmount;
			$vndId					 = $val['bvr_vendor_id'];
			$vndStats				 = Vendors::getArriveTimeStats($vndId);
			$result					 = $vndStats + $val + $vhcData + $drvData;
		}
		return $result;
	}

	public function vendorBiddingAmount($bcbId, $vendorId)
	{

		$params = ['bcbId' => $bcbId, 'vendorId' => $vendorId];

		$sql = "SELECT `bvr_bid_amount` FROM `booking_vendor_request` WHERE `bvr_bcb_id`=$bcbId AND bvr_bid_amount>0 AND bvr_vendor_id=$vendorId";

		$rows = DBUtil::queryScalar($sql, DBUtil::MDB(), $params);
		#print_r($rows);
		return $rows;
	}

	public static function getAcceptedBidId($bcbId, $vendorId)
	{
		$sql	 = "SELECT bvr_id FROM booking_vendor_request WHERE bvr_bcb_id=$bcbId AND bvr_vendor_id=$vendorId AND bvr_accepted=1 AND  bvr_active=1 ";
		$result	 = DBUtil::queryScalar($sql);
		return $result;
	}
}
