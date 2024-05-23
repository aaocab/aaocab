<?php

/**
 * This is the model class for table "booking_smartmatch".
 *
 * The followings are the available columns in table 'booking_smartmatch':
 * @property integer $bsm_id
 * @property integer $bsm_bcb_id
 * @property integer $bsm_upbooking_id
 * @property integer $bsm_downbooking_id
 * @property integer $bsm_matchscore
 * @property integer $bsm_vendor_amt_original
 * @property integer $bsm_vendor_amt_matched
 * @property integer $bsm_margin_original
 * @property integer $bsm_margin_matched
 * @property integer $bsm_up_vehicle_type
 * @property integer $bsm_down_vehicle_type
 * @property integer $bsm_ismatched
 * @property integer $bsm_gozo_amount_original
 * @property integer $bsm_gozo_amount_matched
 * @property integer $bsm_trip_amount
 * @property integer $bsm_active
 * @property string $bsm_modified
 * @property string $bsm_created
 */
class BookingSmartmatch extends CActiveRecord
{

    public $bcbTypeMatched = [], $up_bkg_booking_id, $down_bkg_booking_id, $matchedTripId;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'booking_smartmatch';
    }

    public function defaultScope()
    {
        $ta  = $this->getTableAlias(false, false);
        $arr = array(
            'condition' => $ta . ".bsm_active = 1 ",
        );
        return $arr;
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('bsm_upbooking_id, bsm_downbooking_id', 'required'),
            array('bsm_bcb_id, bsm_upbooking_id, bsm_downbooking_id, bsm_vendor_amt_original, bsm_vendor_amt_matched, bsm_margin_original, bsm_margin_matched, bsm_ismatched, bsm_gozo_amount_original, bsm_gozo_amount_matched, bsm_trip_amount, bsm_active', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('bsm_id, bsm_bcb_id, bsm_upbooking_id, bsm_downbooking_id, bsm_matchscore, bsm_vendor_amt_original, bsm_vendor_amt_matched, bsm_margin_original, bsm_margin_matched, bsm_ismatched, bsm_gozo_amount_original, bsm_gozo_amount_matched, bsm_trip_amount, bsm_active, bsm_modified, bsm_created,bsm_up_vehicle_type,bsm_down_vehicle_type', 'safe', 'on' => 'search'),
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
            'bsm_id'                   => 'Bsm',
            'bsm_bcb_id'               => 'Bsm Bcb',
            'bsm_upbooking_id'         => 'Bsm Upbooking',
            'bsm_downbooking_id'       => 'Bsm Downbooking',
            'bsm_matchscore'           => 'Bsm Matchscore',
            'bsm_vendor_amt_original'  => 'Bsm Vendor Amt Original',
            'bsm_vendor_amt_matched'   => 'Bsm Vendor Amt Matched',
            'bsm_margin_original'      => 'Bsm Margin Original',
            'bsm_margin_matched'       => 'Bsm Margin Matched',
            'bsm_up_vehicle_type'      => 'bsm_up_vehicle_type',
            'bsm_ismatched'            => 'Bsm Ismatched',
            'bsm_gozo_amount_original' => 'Bsm Gozo Amount Original',
            'bsm_gozo_amount_matched'  => 'Bsm Gozo Amount Matched',
            'bsm_trip_amount'          => 'Bsm Trip Amount',
            'bsm_active'               => 'Bsm Active',
            'bsm_modified'             => 'Bsm Modified',
            'bsm_created'              => 'Bsm Created',
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

        $criteria->compare('bsm_id', $this->bsm_id);
        $criteria->compare('bsm_bcb_id', $this->bsm_bcb_id);
        $criteria->compare('bsm_upbooking_id', $this->bsm_upbooking_id);
        $criteria->compare('bsm_downbooking_id', $this->bsm_downbooking_id);
        $criteria->compare('bsm_matchscore', $this->bsm_matchscore);
        $criteria->compare('bsm_vendor_amt_original', $this->bsm_vendor_amt_original);
        $criteria->compare('bsm_vendor_amt_matched', $this->bsm_vendor_amt_matched);
        $criteria->compare('bsm_margin_original', $this->bsm_margin_original);
        $criteria->compare('bsm_margin_matched', $this->bsm_margin_matched);
        $criteria->compare('bsm_ismatched', $this->bsm_ismatched);
        $criteria->compare('bsm_gozo_amount_original', $this->bsm_gozo_amount_original);
        $criteria->compare('bsm_gozo_amount_matched', $this->bsm_gozo_amount_matched);
        $criteria->compare('bsm_trip_amount', $this->bsm_trip_amount);
        $criteria->compare('bsm_active', $this->bsm_active);
        $criteria->compare('bsm_modified', $this->bsm_modified, true);
        $criteria->compare('bsm_created', $this->bsm_created, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return BookingSmartmatch the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function getMatchBooking($bcbId)
    {
        $sql = "SELECT bsm_upbooking_id,bsm_downbooking_id FROM booking_smartmatch WHERE bsm_bcb_id=$bcbId AND bsm_active=1";
        $res = DBUtil::queryRow($sql);
        return $res;
    }

    public function getBookings($bcbId)
    {
        $result = $this->getMatchBooking($bcbId);
        if ($result && count($result) > 0)
        {
            $bkgIds = $result['bsm_upbooking_id'] . ',' . $result['bsm_downbooking_id'];
            $sql    = "SELECT * FROM booking WHERE bkg_id IN ($bkgIds) AND bkg_active = 1";
            return Booking::model()->findAllBySql($sql);
        }
        else
        {
            return 0;
        }
    }
	 public function getBookingsList($bcbId)
	{
		 $sql_query = "SELECT bcb_bkg_id1 FROM booking_cab WHERE bcb_id = $bcbId";
		  $res = DBUtil::queryRow($sql_query);
		  $bookingIds = $res['bcb_bkg_id1'];
		  $sql    = "SELECT * FROM booking WHERE bkg_id IN ($bookingIds) AND bkg_active = 1";
		 return Booking::model()->findAllBySql($sql);

	}

    public function getToBeSmartMatchList($bcbTypeMatched = 0, $upBkgId = '', $downBkgId = '', $matchedTripId = '')
    {

        $matchedList = " AND bsm.bsm_ismatched IN($bcbTypeMatched)";

        $sql = "Select bsm.bsm_id, bsm.bsm_bcb_id, bkg1.bkg_id as up_bkg_id, bkg1.bkg_booking_id AS up_bkg_booking_id,
				biv1.bkg_advance_amount AS bkg1_advance_amount, bkg1.bkg_bcb_id AS up_bkg_bcb_id, bkg1.bkg_status as up_bkg_status,
				bkg1.bkg_from_city_id as up_bkg_from_city_id, bkg1.bkg_to_city_id AS up_bkg_to_city_id,
				vct1.vct_label as up_vht_make,
				bkg1.bkg_pickup_date as up_bkg_pickup_date,
				bkg1.bkg_reconfirm_flag as up_booking_confirm,
				cityFrom1.cty_name as up_bkg_from_city, cityTo1.cty_name as up_bkg_to_city,
				biv1.bkg_total_amount as bkg1_total_amount, vendors.vnd_name,
				bkg2.bkg_id as down_bkg_id, bkg2.bkg_booking_id AS down_bkg_booking_id,
				biv2.bkg_advance_amount AS bkg2_advance_amount, bkg2.bkg_bcb_id As down_bkg_bcb_id,
				bkg2.bkg_status as down_bkg_status, bkg2.bkg_from_city_id as down_bkg_from_city_id,
				bkg2.bkg_to_city_id AS down_bkg_to_city_id, vct2.vct_label as down_vht_make,
				bkg2.bkg_pickup_date as down_bkg_pickup_date,
				cityFrom2.cty_name as down_bkg_from_city, cityTo2.cty_name as down_bkg_to_city,
				bkg2.bkg_reconfirm_flag as dn_booking_confirm,
				biv2.bkg_total_amount as bkg2_total_amount, bsm.bsm_matchscore AS MatchScore, vndcity.cty_name as vendor_city,
				vrs.vrs_total_trips as vendor_total_trip, vrs.vrs_vnd_overall_rating as vendor_rating,
				IF(bkg1.bkg_bcb_id = bkg2.bkg_bcb_id OR bsm.bsm_ismatched = 1, 1, 0) as bcbTypeMatched
				from booking_smartmatch bsm
				INNER JOIN booking_cab ON bsm.bsm_bcb_id=bcb_id AND bcb_trip_type = 1 AND bcb_active=1
				LEFT JOIN booking bkg1 ON bkg1.bkg_id=bsm.bsm_upbooking_id
				LEFT JOIN booking_invoice biv1 ON biv1.biv_bkg_id=bkg1.bkg_id
				LEFT JOIN cities cityTo1 ON cityTo1.cty_id=bkg1.bkg_to_city_id
				LEFT JOIN cities cityFrom1 ON cityFrom1.cty_id=bkg1.bkg_from_city_id
				INNER JOIN svc_class_vhc_cat scv1 ON scv1.scv_id = bsm.bsm_up_vehicle_type
				INNER JOIN service_class sc1 ON sc1.scc_id = scv1.scv_scc_id
				INNER JOIN vehicle_category vct1 ON vct1.vct_id = scv1.scv_vct_id
				LEFT JOIN booking bkg2 ON bkg2.bkg_id=bsm.bsm_downbooking_id
				LEFT JOIN booking_invoice biv2 ON biv2.biv_bkg_id=bkg2.bkg_id
				INNER JOIN svc_class_vhc_cat scv2 ON scv2.scv_id = bsm.bsm_down_vehicle_type
				INNER JOIN service_class sc2 ON sc2.scc_id = scv2.scv_scc_id
				LEFT JOIN vehicle_category vct2 ON vct2.vct_id = scv2.scv_vct_id
				LEFT JOIN vendors ON vendors.vnd_id=booking_cab.bcb_vendor_id and vendors.vnd_id = vendors.vnd_ref_code
				LEFT JOIN contact_profile cp on cp.cr_is_vendor = vendors.vnd_id and cp.cr_status =1
				LEFT JOIN contact ctt ON ctt.ctt_id = cp.cr_contact_id and ctt.ctt_active =1 and ctt.ctt_id = ctt.ctt_ref_code
				LEFT JOIN vendor_stats vrs ON vrs.vrs_vnd_id = vendors.vnd_id
				LEFT JOIN cities vndcity ON ctt.ctt_city=vndcity.cty_id
				LEFT JOIN cities cityTo2 ON cityTo2.cty_id=bkg2.bkg_to_city_id
				LEFT JOIN cities cityFrom2 ON cityFrom2.cty_id=bkg2.bkg_from_city_id
				Where bkg1.bkg_status IN (2,3,5) AND bkg2.bkg_status IN (2,3,5) $matchedList";
        if ($upBkgId != '')
        {
            $sql .= " AND bkg1.bkg_booking_id ='$upBkgId'";
        }
        if ($downBkgId != '')
        {
            $sql .= " AND bkg2.bkg_booking_id ='$downBkgId'";
        }
        if ($matchedTripId != '')
        {
            $sql .= " AND bsm.bsm_bcb_id LIKE '%$matchedTripId%'";
        }
        $sql .= " ORDER BY bsm_modified DESC";

        $count        = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc")->queryScalar();
        $dataprovider = new CSqlDataProvider($sql, [
            'totalItemCount' => $count,
            'sort'           => ['attributes'   => ['MatchScore', 'up_bkg_id', 'up_bkg_booking_id', 'up_bkg_pickup_date',
                    'down_bkg_booking_id', 'up_bkg_bcb_id', 'down_bkg_id', 'down_bkg_from_city_id', 'up_bkg_to_city_id',
                    'down_bkg_to_city_id', 'up_bkg_pickup_date', 'down_bkg_pickup_date'],
                'defaultOrder' => 'MatchScore DESC, up_bkg_pickup_date ASC, up_bkg_id ASC, down_bkg_pickup_date ASC'], 'pagination'     => false,
        ]);
        return $dataprovider;
    }

    public function deactivateAllPreMatchedBooking($upbkgId, $downbkgId, $bsmId)
    {
        $sql = "UPDATE booking_smartmatch SET bsm_ismatched = 1 WHERE bsm_active = 1 AND bsm_id = $bsmId";
        $res = DBUtil::command($sql)->execute();
        return $res1;
    }

    public function activateAllPreMatchedBooking()
    {
        $sql = "UPDATE booking_smartmatch SET bsm_ismatched = 0 
				WHERE bsm_active = 1 
				AND ((bsm_upbooking_id IN (" . $this->bsm_upbooking_id . "," . $this->bsm_downbooking_id . "))
				OR (bsm_downbooking_id IN (" . $this->bsm_upbooking_id . "," . $this->bsm_downbooking_id . ")) AND bsm_id <> " . $this->bsm_id . ")";
        $res = DBUtil::command($sql)->execute();
        return $res;
    }

    public static function smartMatchCount()
    {
        $returnSet = Yii::app()->cache->get('smartMatchCount');
        if ($returnSet === false)
        {
            $sql       = "SELECT COUNT(*)
                        FROM booking_smartmatch bsm
                        JOIN booking_cab ON bsm.bsm_bcb_id=bcb_id AND bcb_trip_type = 1 AND bcb_active=1 AND bsm.bsm_ismatched IN(0)
                        JOIN booking bkg1 ON bkg1.bkg_id=bsm.bsm_upbooking_id  AND bkg1.bkg_status IN (2,3,5) 
                        JOIN svc_class_vhc_cat scv1 ON scv1.scv_id =  bsm.bsm_up_vehicle_type
                        JOIN service_class sc1 ON sc1.scc_id = scv1.scv_scc_id
                        JOIN vehicle_category vct1 ON  vct1.vct_id = scv1.scv_vct_id
                        JOIN booking bkg2 ON bkg2.bkg_id=bsm.bsm_downbooking_id and bkg2.bkg_status IN (2,3,5) 
                        WHERE  1 LIMIT 0,1 ";
            $returnSet = DBUtil::queryScalar($sql, DBUtil::SDB(), [], 600, CacheDependency::Type_DashBoard);
            Yii::app()->cache->set('smartMatchCount', $returnSet, 600);
        }
        return $returnSet;
    }

}
