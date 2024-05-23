<?php

/**
 * This is the model class for table "booking_stats".
 *
 * The followings are the available columns in table 'booking_stats':
 * @property integer $bks_id
 * @property integer $bks_bkg_id
 * @property integer $bks_vehicle_type_id
 * @property integer $bks_state_id
 * @property integer $bks_city_id
 * @property integer $bks_source_region
 * @property integer $bks_bkg_status
 * @property string $bks_create_date
 * @property string $bks_pickup_date
 * @property string $bks_cancel_date
 * @property string $bks_fassignment_date
 * @property string $bks_lassignment_date
 * @property double $bks_diff_create_pickup
 * @property double $bks_diff_cancel_pickup
 * @property double $bks_diff_create_cancel
 * @property double $bks_diff_pickup_fassignment
 * @property double $bks_diff_lassignment_pickup
 * @property string $bks_added_date
 * @property string $bks_modified_date
 * @property integer $bks_active
 * @property double $bks_diff_create_fassignment
 * @property double $bks_diff_lassignment_create
 * @property string $bks_demsup_misfire_date
 * @property integer $bks_msource_zone
 * @property integer $bks_mdestination_zone
 * @property integer $bks_is_pending
 * @property integer $bks_is_complete
 * @property integer $bks_is_cancelled
 * @property integer $bks_is_quote
 * @property integer $bks_is_direct
 * @property integer $bks_is_manual
 * @property integer $bks_is_auto
 * @property integer $bks_bid_count
 * @property string $bks_create_pickup_bins
 * @property string $bks_cancel_pickup_bins
 * @property string $bks_create_cancel_bins
 * @property string $bks_pickup_fassignment_bins
 * @property string $bks_lassignment_pickup_bins
 * @property string $bks_create_fassignment_bins
 * @property string $bks_lassignment_create_bins
 * @property integer $bks_l1_deny_count
 * @property integer $bks_l2_deny_count
 * @property string $bks_row_identifier
 * @property string $bks_zone_identifier
 * @property string $bks_city_identifier
 * @property integer $bks_zone_type
 * @property integer $bks_is_local
 * @property string $bks_created_at
 * @property string $bks_modified_at
 * @property double $bks_travel_time
 * The followings are the available model relations:
 * @property Booking $bksBkg
 * @property SvcClassVhcCat $bksSvcClassVhcCat
 * @property Cities $bkgFromCity
 * @property States $bksState
 * 
 */
class BookingStats extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'booking_stats';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('bks_bkg_id, bks_added_date, bks_modified_date', 'required'),
			array('bks_bkg_id, bks_vehicle_type_id, bks_state_id, bks_city_id, bks_source_region, bks_bkg_status, bks_active, bks_msource_zone, bks_mdestination_zone, bks_is_pending, bks_is_complete, bks_is_cancelled, bks_is_quote, bks_is_direct, bks_is_manual, bks_is_auto, bks_bid_count, bks_l1_deny_count, bks_l2_deny_count, bks_zone_type, bks_is_local', 'numerical', 'integerOnly' => true),
			array('bks_diff_create_pickup, bks_diff_cancel_pickup, bks_diff_create_cancel, bks_diff_pickup_fassignment, bks_diff_lassignment_pickup, bks_diff_create_fassignment, bks_diff_lassignment_create, bks_travel_time', 'numerical'),
			array('bks_create_pickup_bins, bks_cancel_pickup_bins, bks_create_cancel_bins, bks_pickup_fassignment_bins, bks_lassignment_pickup_bins, bks_create_fassignment_bins, bks_lassignment_create_bins', 'length', 'max' => 255),
			array('bks_row_identifier, bks_zone_identifier', 'length', 'max' => 23),
			array('bks_city_identifier', 'length', 'max' => 22),
			array('bks_create_date, bks_pickup_date, bks_cancel_date, bks_fassignment_date, bks_lassignment_date, bks_demsup_misfire_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('bks_id, bks_bkg_id, bks_vehicle_type_id, bks_state_id, bks_city_id, bks_source_region, bks_bkg_status, bks_create_date, bks_pickup_date, bks_cancel_date, bks_fassignment_date, bks_lassignment_date, bks_diff_create_pickup, bks_diff_cancel_pickup, bks_diff_create_cancel, bks_diff_pickup_fassignment, bks_diff_lassignment_pickup, bks_added_date, bks_modified_date, bks_active, bks_diff_create_fassignment, bks_diff_lassignment_create, bks_demsup_misfire_date, bks_msource_zone, bks_mdestination_zone, bks_is_pending, bks_is_complete, bks_is_cancelled, bks_is_quote, bks_is_direct, bks_is_manual, bks_is_auto, bks_bid_count, bks_create_pickup_bins, bks_cancel_pickup_bins, bks_create_cancel_bins, bks_pickup_fassignment_bins, bks_lassignment_pickup_bins, bks_create_fassignment_bins, bks_lassignment_create_bins, bks_l1_deny_count, bks_l2_deny_count, bks_row_identifier, bks_zone_identifier, bks_city_identifier, bks_zone_type, bks_is_local, bks_created_at, bks_modified_at, bks_travel_time,bks_va_norm_km,bks_va_norm_hr', 'safe', 'on' => 'search'),
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
			'bksBkg'			 => array(self::BELONGS_TO, 'Booking', 'bks_bkg_id'),
			'bksFromCity'		 => array(self::BELONGS_TO, 'Cities', 'bks_city_id'),
			'bksState'			 => array(self::BELONGS_TO, 'States', 'bks_state_id'),
			'bksSvcClassVhcCat'	 => array(self::BELONGS_TO, 'SvcClassVhcCat', 'bks_vehicle_type_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'bks_id'						 => 'Bks',
			'bks_bkg_id'					 => 'Booking Id',
			'bks_vehicle_type_id'			 => 'Booking Vehicle Type Id',
			'bks_state_id'					 => 'State Id',
			'bks_city_id'					 => 'City Id',
			'bks_source_region'				 => '1=>North, 2=>West,3=>Central, 4=>South,5=>East, 6=>North East,7=>Kerala',
			'bks_bkg_status'				 => 'Booking Status',
			'bks_create_date'				 => 'Booking Create Date',
			'bks_pickup_date'				 => 'Booking Pickup Date',
			'bks_cancel_date'				 => 'Booking Cancel Date',
			'bks_fassignment_date'			 => 'Vendor First Assignment Date',
			'bks_lassignment_date'			 => 'Vendor Lasr Assignment Date',
			'bks_diff_create_pickup'		 => 'Hours between create & pickup (pickup - create) Hrs',
			'bks_diff_cancel_pickup'		 => 'Hours between cancel & pickup (pickup - cancel)',
			'bks_diff_create_cancel'		 => 'Hours between create & cancel (cancel-create)',
			'bks_diff_pickup_fassignment'	 => 'Hours between pickup & first_asssignment',
			'bks_diff_lassignment_pickup'	 => 'Hours between pickup & last assignment',
			'bks_added_date'				 => 'Bks Added Date',
			'bks_modified_date'				 => 'Bks Modified Date',
			'bks_active'					 => 'Bks Active',
			'bks_diff_create_fassignment'	 => 'Bks Diff Create Fassignment',
			'bks_diff_lassignment_create'	 => 'Bks Diff Lassignment Create',
			'bks_demsup_misfire_date'		 => 'Bks Demsup Misfire Date',
			'bks_msource_zone'				 => 'Bks Msource Zone',
			'bks_mdestination_zone'			 => 'Bks Mdestination Zone',
			'bks_is_pending'				 => 'Bks Is Pending',
			'bks_is_complete'				 => 'Bks Is Complete',
			'bks_is_cancelled'				 => 'Bks Is Cancelled',
			'bks_is_quote'					 => 'Bks Is Quote',
			'bks_is_direct'					 => 'Bks Is Direct',
			'bks_is_manual'					 => 'Bks Is Manual',
			'bks_is_auto'					 => 'Bks Is Auto',
			'bks_bid_count'					 => 'This will count all bids placed for a particular booking id',
			'bks_create_pickup_bins'		 => 'bins should be Bin ID 0 to 12 -> 0.5, 12-24 -> 1, 02-04 days -> 4, 4-10day -> 10, 10+day -> 11',
			'bks_cancel_pickup_bins'		 => 'Bks Cancel Pickup Bins',
			'bks_create_cancel_bins'		 => 'Bks Create Cancel Bins',
			'bks_pickup_fassignment_bins'	 => 'Bks Pickup Fassignment Bins',
			'bks_lassignment_pickup_bins'	 => 'Bks Lassignment Pickup Bins',
			'bks_create_fassignment_bins'	 => 'Bks Create Fassignment Bins',
			'bks_lassignment_create_bins'	 => 'Bks Lassignment Create Bins',
			'bks_l1_deny_count'				 => 'Bks L1 Deny Count',
			'bks_l2_deny_count'				 => 'Bks L2 Deny Count',
			'bks_row_identifier'			 => 'dzs_regionid-dzs_fromzoneid-dzs_tozoneid-dzs_svc_id-dzs_booking_type',
			'bks_zone_identifier'			 => 'Bks Zone Identifier',
			'bks_city_identifier'			 => 'Bks City Identifier',
			'bks_zone_type'					 => 'Bks Zone Type',
			'bks_is_local'					 => '0=>"Not Defined", 1=>"Local", 2=>"Out Station"',
			'bks_created_at'				 => 'tells you when it was created',
			'bks_modified_at'				 => 'tells you when it was modified',
			'bks_travel_time'				 => 'Time In mintue',
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

		$criteria->compare('bks_id', $this->bks_id);
		$criteria->compare('bks_bkg_id', $this->bks_bkg_id);
		$criteria->compare('bks_vehicle_type_id', $this->bks_vehicle_type_id);
		$criteria->compare('bks_state_id', $this->bks_state_id);
		$criteria->compare('bks_city_id', $this->bks_city_id);
		$criteria->compare('bks_source_region', $this->bks_source_region);
		$criteria->compare('bks_bkg_status', $this->bks_bkg_status);
		$criteria->compare('bks_create_date', $this->bks_create_date, true);
		$criteria->compare('bks_pickup_date', $this->bks_pickup_date, true);
		$criteria->compare('bks_cancel_date', $this->bks_cancel_date, true);
		$criteria->compare('bks_fassignment_date', $this->bks_fassignment_date, true);
		$criteria->compare('bks_lassignment_date', $this->bks_lassignment_date, true);
		$criteria->compare('bks_diff_create_pickup', $this->bks_diff_create_pickup);
		$criteria->compare('bks_diff_cancel_pickup', $this->bks_diff_cancel_pickup);
		$criteria->compare('bks_diff_create_cancel', $this->bks_diff_create_cancel);
		$criteria->compare('bks_diff_pickup_fassignment', $this->bks_diff_pickup_fassignment);
		$criteria->compare('bks_diff_lassignment_pickup', $this->bks_diff_lassignment_pickup);
		$criteria->compare('bks_added_date', $this->bks_added_date, true);
		$criteria->compare('bks_modified_date', $this->bks_modified_date, true);
		$criteria->compare('bks_active', $this->bks_active);
		$criteria->compare('bks_diff_create_fassignment', $this->bks_diff_create_fassignment);
		$criteria->compare('bks_diff_lassignment_create', $this->bks_diff_lassignment_create);
		$criteria->compare('bks_demsup_misfire_date', $this->bks_demsup_misfire_date, true);
		$criteria->compare('bks_msource_zone', $this->bks_msource_zone);
		$criteria->compare('bks_mdestination_zone', $this->bks_mdestination_zone);
		$criteria->compare('bks_is_pending', $this->bks_is_pending);
		$criteria->compare('bks_is_complete', $this->bks_is_complete);
		$criteria->compare('bks_is_cancelled', $this->bks_is_cancelled);
		$criteria->compare('bks_is_quote', $this->bks_is_quote);
		$criteria->compare('bks_is_direct', $this->bks_is_direct);
		$criteria->compare('bks_is_manual', $this->bks_is_manual);
		$criteria->compare('bks_is_auto', $this->bks_is_auto);
		$criteria->compare('bks_bid_count', $this->bks_bid_count);
		$criteria->compare('bks_create_pickup_bins', $this->bks_create_pickup_bins, true);
		$criteria->compare('bks_cancel_pickup_bins', $this->bks_cancel_pickup_bins, true);
		$criteria->compare('bks_create_cancel_bins', $this->bks_create_cancel_bins, true);
		$criteria->compare('bks_pickup_fassignment_bins', $this->bks_pickup_fassignment_bins, true);
		$criteria->compare('bks_lassignment_pickup_bins', $this->bks_lassignment_pickup_bins, true);
		$criteria->compare('bks_create_fassignment_bins', $this->bks_create_fassignment_bins, true);
		$criteria->compare('bks_lassignment_create_bins', $this->bks_lassignment_create_bins, true);
		$criteria->compare('bks_l1_deny_count', $this->bks_l1_deny_count);
		$criteria->compare('bks_l2_deny_count', $this->bks_l2_deny_count);
		$criteria->compare('bks_row_identifier', $this->bks_row_identifier, true);
		$criteria->compare('bks_zone_identifier', $this->bks_zone_identifier, true);
		$criteria->compare('bks_city_identifier', $this->bks_city_identifier, true);
		$criteria->compare('bks_zone_type', $this->bks_zone_type);
		$criteria->compare('bks_is_local', $this->bks_is_local);
		$criteria->compare('bks_reated_at', $this->created_at, true);
		$criteria->compare('bks_modified_at', $this->modified_at, true);
		$criteria->compare('bks_travel_time', $this->bks_travel_time);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BookingStats the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getByBooking($bkg_id)
	{
		$criteria	 = new CDbCriteria;
		$criteria->compare('bks_bkg_id', $bkg_id);
		$model		 = $this->find($criteria);
		if ($model)
		{
			return $model;
		}
		else
		{
			return false;
		}
	}

	/**
	 * 
	 * @param type booking Objects
	 * @return boolean
	 */
	public function updateAttr($modelBookingStats)
	{
		$success = false;
		$model	 = $this->getByBooking($modelBookingStats['bks_bkg_id']);
		if (!$model)
		{
			$model = new BookingStats();
		}
		$model->bks_bkg_id = $modelBookingStats['bks_bkg_id'];
		if ($modelBookingStats['bks_vehicle_type_id'] != '' && $modelBookingStats['bks_vehicle_type_id'] != NULL)
		{
			$model->bks_vehicle_type_id = $modelBookingStats['bks_vehicle_type_id'];
		}
		if ($modelBookingStats['bks_state_id'] != '' && $modelBookingStats['bks_state_id'] != NULL)
		{
			$model->bks_state_id = $modelBookingStats['bks_state_id'];
		}
		if ($modelBookingStats['bks_city_id'] != '' && $modelBookingStats['bks_city_id'] != NULL)
		{
			$model->bks_city_id = $modelBookingStats['bks_city_id'];
		}
		if ($modelBookingStats['bks_source_region'] != '' && $modelBookingStats['bks_source_region'] != NULL)
		{
			$model->bks_source_region = $modelBookingStats['bks_source_region'];
		}
		if ($modelBookingStats['bks_bkg_status'] != '' && $modelBookingStats['bks_bkg_status'] != NULL)
		{
			$model->bks_bkg_status = $modelBookingStats['bks_bkg_status'];
		}
		if ($modelBookingStats['bks_create_date'] != '' && $modelBookingStats['bks_create_date'] != NULL)
		{
			$model->bks_create_date = $modelBookingStats['bks_create_date'];
		}
		if ($modelBookingStats['bks_pickup_date'] != '' && $modelBookingStats['bks_pickup_date'] != NULL)
		{
			$model->bks_pickup_date = $modelBookingStats['bks_pickup_date'];
		}
		if ($modelBookingStats['bks_cancel_date'] != '' && $modelBookingStats['bks_cancel_date'] != NULL)
		{
			$model->bks_cancel_date = $modelBookingStats['bks_cancel_date'];
		}
		if ($modelBookingStats['bks_fassignment_date'] != '' && $modelBookingStats['bks_fassignment_date'] != NULL)
		{
			$model->bks_fassignment_date = $modelBookingStats['bks_fassignment_date'];
		}
		if ($modelBookingStats['bks_lassignment_date'] != '' && $modelBookingStats['bks_lassignment_date'] != NULL)
		{
			$model->bks_lassignment_date = $modelBookingStats['bks_lassignment_date'];
		}
		if ($modelBookingStats['bks_diff_create_pickup'] != '' && $modelBookingStats['bks_diff_create_pickup'] != NULL)
		{
			$model->bks_diff_create_pickup = $modelBookingStats['bks_diff_create_pickup'];
		}
		if ($modelBookingStats['bks_diff_cancel_pickup'] != '' && $modelBookingStats['bks_diff_cancel_pickup'] != NULL)
		{
			$model->bks_diff_cancel_pickup = $modelBookingStats['bks_diff_cancel_pickup'];
		}
		if ($modelBookingStats['bks_diff_create_cancel'] != '' && $modelBookingStats['bks_diff_create_cancel'] != NULL)
		{
			$model->bks_diff_create_cancel = $modelBookingStats['bks_diff_create_cancel'];
		}
		if ($modelBookingStats['bks_diff_pickup_fassignment'] != '' && $modelBookingStats['bks_diff_pickup_fassignment'] != NULL)
		{
			$model->bks_diff_pickup_fassignment = $modelBookingStats['bks_diff_pickup_fassignment'];
		}
		if ($modelBookingStats['bks_diff_lassignment_pickup'] != '' && $modelBookingStats['bks_diff_lassignment_pickup'] != NULL)
		{
			$model->bks_diff_lassignment_pickup = $modelBookingStats['bks_diff_lassignment_pickup'];
		}
		if ($modelBookingStats['bks_diff_create_fassignment'] != '' && $modelBookingStats['bks_diff_create_fassignment'] != NULL)
		{
			$model->bks_diff_create_fassignment = $modelBookingStats['bks_diff_create_fassignment'];
		}
		if ($modelBookingStats['bks_diff_lassignment_create'] != '' && $modelBookingStats['bks_diff_lassignment_create'] != NULL)
		{
			$model->bks_diff_lassignment_create = $modelBookingStats['bks_diff_lassignment_create'];
		}
		if ($modelBookingStats['bks_added_date'] != '' && $modelBookingStats['bks_added_date'] != NULL)
		{
			$model->bks_added_date = $modelBookingStats['bks_added_date'];
		}
		if ($modelBookingStats['bks_modified_date'] != '' && $modelBookingStats['bks_modified_date'] != NULL)
		{
			$model->bks_modified_date = $modelBookingStats['bks_modified_date'];
		}
		if ($modelBookingStats['bks_active'] != '' && $modelBookingStats['bks_active'] != NULL)
		{
			$model->bks_active = $modelBookingStats['bks_active'];
		}

		if ($modelBookingStats['bks_demsup_misfire_date'] != '' && $modelBookingStats['bks_demsup_misfire_date'] != NULL)
		{
			$model->bks_demsup_misfire_date = $modelBookingStats['bks_demsup_misfire_date'];
		}
		if ($modelBookingStats['bks_msource_zone'] != '' && $modelBookingStats['bks_msource_zone'] != NULL)
		{
			$model->bks_msource_zone = $modelBookingStats['bks_msource_zone'];
		}
		if ($modelBookingStats['bks_mdestination_zone'] != '' && $modelBookingStats['bks_mdestination_zone'] != NULL)
		{
			$model->bks_mdestination_zone = $modelBookingStats['bks_mdestination_zone'];
		}
		if ($modelBookingStats['bks_is_pending'] != '' && $modelBookingStats['bks_is_pending'] != NULL)
		{
			$model->bks_is_pending = $modelBookingStats['bks_is_pending'];
		}
		if ($modelBookingStats['bks_is_complete'] != '' && $modelBookingStats['bks_is_complete'] != NULL)
		{
			$model->bks_is_complete = $modelBookingStats['bks_is_complete'];
		}
		if ($modelBookingStats['bks_is_cancelled'] != '' && $modelBookingStats['bks_is_cancelled'] != NULL)
		{
			$model->bks_is_cancelled = $modelBookingStats['bks_is_cancelled'];
		}
		if ($modelBookingStats['bks_is_quote'] != '' && $modelBookingStats['bks_is_quote'] != NULL)
		{
			$model->bks_is_quote = $modelBookingStats['bks_is_quote'];
		}
		if ($modelBookingStats['bks_is_direct'] != '' && $modelBookingStats['bks_is_direct'] != NULL)
		{
			$model->bks_is_direct = $modelBookingStats['bks_is_direct'];
		}
		if ($modelBookingStats['bks_is_manual'] != '' && $modelBookingStats['bks_is_manual'] != NULL)
		{
			$model->bks_is_manual = $modelBookingStats['bks_is_manual'];
		}
		if ($modelBookingStats['bks_is_auto'] != '' && $modelBookingStats['bks_is_auto'] != NULL)
		{
			$model->bks_is_auto = $modelBookingStats['bks_is_auto'];
		}
		if ($modelBookingStats['bks_bid_count'] != '' && $modelBookingStats['bks_bid_count'] != NULL)
		{
			$model->bks_bid_count = $modelBookingStats['bks_bid_count'];
		}

		if ($modelBookingStats['bks_create_pickup_bins'] != '' && $modelBookingStats['bks_create_pickup_bins'] != NULL)
		{
			$model->bks_create_pickup_bins = $modelBookingStats['bks_create_pickup_bins'];
		}

		if ($modelBookingStats['bks_cancel_pickup_bins'] != '' && $modelBookingStats['bks_cancel_pickup_bins'] != NULL)
		{
			$model->bks_cancel_pickup_bins = $modelBookingStats['bks_cancel_pickup_bins'];
		}

		if ($modelBookingStats['bks_create_cancel_bins'] != '' && $modelBookingStats['bks_create_cancel_bins'] != NULL)
		{
			$model->bks_create_cancel_bins = $modelBookingStats['bks_create_cancel_bins'];
		}

		if ($modelBookingStats['bks_pickup_fassignment_bins'] != '' && $modelBookingStats['bks_pickup_fassignment_bins'] != NULL)
		{
			$model->bks_pickup_fassignment_bins = $modelBookingStats['bks_pickup_fassignment_bins'];
		}

		if ($modelBookingStats['bks_lassignment_pickup_bins'] != '' && $modelBookingStats['bks_lassignment_pickup_bins'] != NULL)
		{
			$model->bks_lassignment_pickup_bins = $modelBookingStats['bks_lassignment_pickup_bins'];
		}

		if ($modelBookingStats['bks_create_fassignment_bins'] != '' && $modelBookingStats['bks_create_fassignment_bins'] != NULL)
		{
			$model->bks_create_fassignment_bins = $modelBookingStats['bks_create_fassignment_bins'];
		}

		if ($modelBookingStats['bks_lassignment_create_bins'] != '' && $modelBookingStats['bks_lassignment_create_bins'] != NULL)
		{
			$model->bks_lassignment_create_bins = $modelBookingStats['bks_lassignment_create_bins'];
		}

		if ($modelBookingStats['bks_l1_deny_count'] != '' && $modelBookingStats['bks_l1_deny_count'] != NULL)
		{
			$model->bks_l1_deny_count = $modelBookingStats['bks_l1_deny_count'];
		}
		if ($modelBookingStats['bks_l2_deny_count'] != '' && $modelBookingStats['bks_l2_deny_count'] != NULL)
		{
			$model->bks_l2_deny_count = $modelBookingStats['bks_l2_deny_count'];
		}
		if ($modelBookingStats['bks_row_identifier'] != '' && $modelBookingStats['bks_row_identifier'] != NULL)
		{
			$model->bks_row_identifier = $modelBookingStats['bks_row_identifier'];
		}

		if ($model->validate() && $model->save())
		{
			$success = true;
		}
		return $success;
	}

	/*
	 * This function is used to get all booking stats realted data
	 * return query Objects 
	 */

	public function getAllBookingStats($fromDate = null, $toDate = null)
	{
		if ($fromDate != null && $toDate != null)
		{
			$fromDate	 = $fromDate . " 00:00:00";
			$toDate		 = $toDate . " 23:59:59";
			$where		 = " AND btr_mark_complete_date BETWEEN '$fromDate' AND '$toDate' ";
		}
		else
		{
			$where = ' AND btr_mark_complete_date BETWEEN CONCAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY)," 00:00:00") AND CONCAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY)," 23:59:50") ';
		}
		$sql = "SELECT 
                temp.*,
                CONCAT('7','',LPAD(temp.bks_source_region,2,'0'),'',LPAD(temp.bkg_from_city_id,8,'0'),'',LPAD(temp.bkg_to_city_id,8,'0')) AS bks_city_identifier, 
                CASE
                    WHEN bks_diff_create_pickup >= 0 AND bks_diff_create_pickup < 12 THEN '00-12'
                    WHEN bks_diff_create_pickup >= 12 AND bks_diff_create_pickup < 24 THEN '12-24'
                    WHEN bks_diff_create_pickup >= 24 AND bks_diff_create_pickup < 96 THEN 'D02-D04'
                    WHEN bks_diff_create_pickup >= 96 AND bks_diff_create_pickup < 240 THEN 'D04-D10'
                    WHEN bks_diff_create_pickup >= 240 THEN 'D10+'
                END  AS bks_create_pickup_bins,
                CASE
                    WHEN bks_diff_cancel_pickup >= 0 AND bks_diff_cancel_pickup < 12 THEN '00-12'
                    WHEN bks_diff_cancel_pickup >= 12 AND bks_diff_cancel_pickup < 24 THEN '12-24'
                    WHEN bks_diff_cancel_pickup >= 24 AND bks_diff_cancel_pickup < 96 THEN 'D02-D04'
                    WHEN bks_diff_cancel_pickup >= 96 AND bks_diff_cancel_pickup < 240 THEN 'D04-D10'
                    WHEN bks_diff_cancel_pickup >= 240 THEN 'D10+'
                END  AS bks_cancel_pickup_bins,

                CASE
                    WHEN bks_diff_create_cancel >= 0 AND bks_diff_create_cancel < 12 THEN '00-12'
                    WHEN bks_diff_create_cancel >= 12 AND bks_diff_create_cancel < 24 THEN '12-24'
                    WHEN bks_diff_create_cancel >= 24 AND bks_diff_create_cancel < 96 THEN 'D02-D04'
                    WHEN bks_diff_create_cancel >= 96 AND bks_diff_create_cancel < 240 THEN 'D04-D10'
                    WHEN bks_diff_create_cancel >= 240 THEN 'D10+'
                END  AS bks_create_cancel_bins,

                CASE
                    WHEN bks_diff_pickup_fassignment >= 0 AND bks_diff_pickup_fassignment < 12 THEN '00-12'
                    WHEN bks_diff_pickup_fassignment >= 12 AND bks_diff_pickup_fassignment < 24 THEN '12-24'
                    WHEN bks_diff_pickup_fassignment >= 24 AND bks_diff_pickup_fassignment < 96 THEN 'D02-D04'
                    WHEN bks_diff_pickup_fassignment >= 96 AND bks_diff_pickup_fassignment < 240 THEN 'D04-D10'
                    WHEN bks_diff_pickup_fassignment >= 240 THEN 'D10+'
                END  AS bks_pickup_fassignment_bins,

                CASE
                    WHEN bks_diff_lassignment_pickup >= 0 AND bks_diff_lassignment_pickup < 12 THEN '00-12'
                    WHEN bks_diff_lassignment_pickup >= 12 AND bks_diff_lassignment_pickup < 24 THEN '12-24'
                    WHEN bks_diff_lassignment_pickup >= 24 AND bks_diff_lassignment_pickup < 96 THEN 'D02-D04'
                    WHEN bks_diff_lassignment_pickup >= 96 AND bks_diff_lassignment_pickup < 240 THEN 'D04-D10'
                    WHEN bks_diff_lassignment_pickup >= 240 THEN 'D10+'
                END  AS bks_lassignment_pickup_bins,

                CASE
                    WHEN bks_diff_create_fassignment >= 0 AND bks_diff_create_fassignment < 12 THEN '00-12'
                    WHEN bks_diff_create_fassignment >= 12 AND bks_diff_create_fassignment < 24 THEN '12-24'
                    WHEN bks_diff_create_fassignment >= 24 AND bks_diff_create_fassignment < 96 THEN 'D02-D04'
                    WHEN bks_diff_create_fassignment >= 96 AND bks_diff_create_fassignment < 240 THEN 'D04-D10'
                    WHEN bks_diff_create_fassignment >= 240 THEN 'D10+'
                END  AS bks_create_fassignment_bins,

                CASE
                    WHEN bks_diff_lassignment_create >= 0 AND bks_diff_lassignment_create < 12 THEN '00-12'
                    WHEN bks_diff_lassignment_create >= 12 AND bks_diff_lassignment_create < 24 THEN '12-24'
                    WHEN bks_diff_lassignment_create >= 24 AND bks_diff_lassignment_create < 96 THEN 'D02-D04'
                    WHEN bks_diff_lassignment_create >= 96 AND bks_diff_lassignment_create < 240 THEN 'D04-D10'
                    WHEN bks_diff_lassignment_create >= 240 THEN 'D10+'
                END  AS bks_lassignment_create_bins
                FROM
                (
                    SELECT 
                    bkg_id AS bks_bkg_id,  
					((bcb_vendor_amount  - (IF(`bkg_is_toll_tax_included`=1,`bkg_toll_tax`,0)) - (IF(`bkg_is_state_tax_included`=1,`bkg_state_tax`,0)) - (IF(`bkg_is_airport_fee_included`=1,`bkg_airport_entry_fee`,0))) / (bkg_trip_distance)) AS VA_per_km,
					(bcb_vendor_amount - (IF(`bkg_is_toll_tax_included`=1,`bkg_toll_tax`,0)) - (IF(`bkg_is_state_tax_included`=1,`bkg_state_tax`,0)) - (IF(`bkg_is_airport_fee_included`=1,`bkg_airport_entry_fee`,0)))  AS VA_normalized_amount,
                    bkg_vehicle_type_id AS bks_vehicle_type_id,
                    bkg_status AS bks_bkg_status,
                    states.stt_zone AS bks_source_region,
                    states.stt_id AS bks_state_id,
                    cty_id AS bks_city_id, 
                    bkg_from_city_id, 
                    bkg_to_city_id,
                    bpr_zone_type AS bks_zone_type,
                    bpr_row_identifier AS bks_row_identifier,
                    bpr_zone_identifier AS bks_zone_identifier,
                    ROUND((TIMESTAMPDIFF(MINUTE, bkg_create_date ,bkg_pickup_date)/60),2) AS bks_diff_create_pickup,
                    IF(btr_cancel_date IS NULL,0,ROUND((TIMESTAMPDIFF(MINUTE, btr_cancel_date ,bkg_pickup_date)/60),2)) AS bks_diff_cancel_pickup,
                    IF(btr_cancel_date IS NULL,0,ROUND((TIMESTAMPDIFF(MINUTE, bkg_create_date ,btr_cancel_date)/60),2)) AS bks_diff_create_cancel,
                    IF(blg1.blg_created IS NULL,0,ROUND((TIMESTAMPDIFF(MINUTE, blg1.blg_created, bkg_pickup_date)/60),2)) AS bks_diff_pickup_fassignment,
                    IF(blg.blg_created IS NULL,0,ROUND((TIMESTAMPDIFF(MINUTE, blg.blg_created, bkg_pickup_date)/60),2)) AS bks_diff_lassignment_pickup,
                    IF(blg1.blg_created IS NULL,0,ROUND((TIMESTAMPDIFF(MINUTE, bkg_create_date, blg1.blg_created)/60),2)) AS bks_diff_create_fassignment,
                    IF(blg.blg_created IS NULL,0,ROUND((TIMESTAMPDIFF(MINUTE, bkg_create_date ,blg.blg_created)/60),2)) AS bks_diff_lassignment_create,
                    bkg_create_date AS bks_create_date,
                    bkg_pickup_date AS bks_pickup_date,
                    btr_cancel_date AS bks_cancel_date,
                    blg1.blg_created AS bks_fassignment_date,
                    blg.blg_created AS bks_lassignment_date,
                    IF(bkg_status IN (2,3,5),1,0) AS bks_is_pending,
                    IF(bkg_status IN (6,7),1,0) AS bks_is_complete,
                    IF(bkg_status IN (9),1,0) AS bks_is_cancelled,
                    IF(bkg_status IN (10),1,0) AS bks_is_quote,
                    IF(bkg_assign_mode=2,1,0) AS bks_is_direct,
                    IF(bkg_assign_mode=0,1,0) AS bks_is_manual,
                    IF(bkg_assign_mode=1,1,0) AS bks_is_auto,
                    IF(bkg_booking_type is NOT NULL,IF(bkg_booking_type IN (4,9,10,11,12,15),1,2),0) AS bks_is_local
                    FROM   booking
                    JOIN booking_cab ON booking.bkg_bcb_id = booking_cab.bcb_id AND bkg_active = 1 AND booking_cab.bcb_active = 1
                    JOIN booking_trail ON booking_trail.btr_bkg_id = bkg_id
                    JOIN booking_pref ON booking_pref.bpr_bkg_id = bkg_id
					JOIN booking_invoice ON booking_invoice.biv_bkg_id = bkg_id
                    JOIN `cities` ON cty_id = bkg_from_city_id AND cities.cty_active = 1
                    JOIN `states` ON cty_state_id = states.stt_id AND states.stt_active = '1'
                    JOIN booking_log AS blg1 ON bkg_id = blg1.blg_booking_id AND blg1.blg_id = (SELECT MIN(blg_id) FROM `booking_log` WHERE `blg_event_id` = 7 AND blg_booking_id = bkg_id AND blg_active= 1 AND blg_vendor_assigned_id > 0 ORDER BY blg_id ASC LIMIT 0, 1)
                    JOIN booking_log AS blg  ON bkg_id = blg.blg_booking_id AND blg.blg_id = (SELECT MAX(blg_id)  FROM  `booking_log`  WHERE  `blg_event_id` = 7 AND blg_booking_id = bkg_id AND blg_active =1 AND blg_vendor_assigned_id > 0 ORDER BY blg_id DESC LIMIT    0, 1)
                    WHERE  1 $where  AND bkg_status IN (2, 3, 5, 6, 7, 9)
                ) temp WHERE 1 ";
		return DBUtil::query($sql, DBUtil::SDB());
	}

	public static function getMinPickupdate()
	{
		$sql = "SELECT bks_pickup_date FROM `booking_stats` WHERE `booking_stats`.`bks_pickup_date`>'2020-12-31'  ORDER BY `booking_stats`.`bks_pickup_date` ASC LIMIT 0,1";
		return DBUtil::queryScalar($sql, DBUtil::SDB());
	}

}
