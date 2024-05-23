<?php

/**
 * This is the model class for table "promo_users".
 *
 * The followings are the available columns in table 'promo_users':
 * @property integer $pru_id
 * @property integer $pru_promo_id
 * @property integer $pru_user_id
 * @property integer $pru_use_max
 * @property integer $pru_auto_apply
 * @property string $pru_valid_from
 * @property string $pru_valid_upto
 * @property integer $pru_next_trip_apply
 * @property integer $pru_active
 * @property integer $pru_ref_id
 * @property integer $pru_ref_type
 * @property string $pru_modified
 * @property string $pru_created
 */
class PromoUsers extends CActiveRecord
{

    public $pru_valid_from_date, $pru_valid_from_time, $pru_valid_upto_date, $pru_valid_upto_time, $search;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'promo_users';
    }

    public static $userCategoty = ['0' => 'User', '1' => 'Agent', '2' => 'Facebook Login User', '3' => 'Golden User'];

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('pru_promo_id, pru_ref_id', 'required'),
            array('pru_promo_id, pru_ref_id,pru_ref_type, pru_use_max, pru_auto_apply, pru_next_trip_apply, pru_active', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('pru_id, pru_promo_id, pru_ref_id,pru_ref_type, pru_use_max, pru_auto_apply, pru_valid_from, pru_valid_upto, pru_next_trip_apply, pru_active, pru_modified, pru_created', 'safe', 'on' => 'search'),
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
            'pru_id'              => 'Pru',
            'pru_promo_id'        => 'Promo',
            'pru_user_id'         => 'User',
            'pru_use_max'         => 'Use Max',
            'pru_auto_apply'      => 'Auto Apply',
            'pru_valid_from'      => 'Valid From',
            'pru_valid_upto'      => 'Valid Upto',
            'pru_next_trip_apply' => 'Next Trip Apply',
            'pru_active'          => 'Active',
            'pru_modified'        => 'Modified',
            'pru_created'         => 'Created',
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

        $criteria->compare('pru_id', $this->pru_id);
        $criteria->compare('pru_promo_id', $this->pru_promo_id);
        $criteria->compare('pru_user_id', $this->pru_user_id);
        $criteria->compare('pru_use_max', $this->pru_use_max);
        $criteria->compare('pru_auto_apply', $this->pru_auto_apply);
        $criteria->compare('pru_valid_from', $this->pru_valid_from, true);
        $criteria->compare('pru_valid_upto', $this->pru_valid_upto, true);
        $criteria->compare('pru_next_trip_apply', $this->pru_next_trip_apply);
        $criteria->compare('pru_active', $this->pru_active);
        $criteria->compare('pru_modified', $this->pru_modified, true);
        $criteria->compare('pru_created', $this->pru_created, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return PromoUsers the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function getByPromoUserId($promoId, $refId)
    {
        return $this->find('pru_promo_id=:prm AND pru_ref_id=:ref', ['prm' => $promoId, 'ref' => $refId]);
    }

    public function incrementCounter($promoid, $refId)
    {
        $promoUserModel = PromoUsers::model()->getByPromoUserId($promoid, $refId);
        if ($promoUserModel)
        {
            if ($promoUserModel->pru_use_max > 0)
            {
                $promoUserModel->pru_used_counter = $promoUserModel->pru_used_counter + 1;
                $promoUserModel->save();
            }
        }
    }

    public function decrementCounter($promoid, $userid)
    {
        $promoUserModel = PromoUsers::model()->getByPromoUserId($promoid, $userid);
        if ($promoUserModel)
        {
            if ($promoUserModel->pru_use_max > 0)
            {
                $promoUserModel->pru_used_counter = $promoUserModel->pru_used_counter - 1;
                $promoUserModel->save();
            }
        }
    }

    public function getUserAvailability($refId, $promoId, $refType)
    {
        $sql = "SELECT COUNT(1) 
				FROM promo_users
				WHERE
				pru_ref_id=" . $refId . "
				AND pru_promo_id=" . $promoId . "
				AND pru_ref_type=" . $refType . "
				AND pru_active=1";
        $res = DBUtil::command($sql)->queryScalar();
        return $res;
    }

    public function getUserApplicable($refId, $promoId, $imEfect, $autoApply, $nextTripApplicable, $refType)
    {
        $res1 = PromoUsers::getUserAvailability($refId, $promoId, $refType);
        if ($res1 == 0 && $nextTripApplicable == 1)
        {
            return true;
        }
        else
        {
            $where = " AND pru_valid_from <= CURRENT_TIMESTAMP AND pru_valid_upto >= CURRENT_TIMESTAMP";
            if ($imEfect == 1)
            {
                $where .= " AND (pru_use_max > pru_used_counter OR pru_use_max=0)";
            }
            if ($autoApply == 1)
            {
                $where .= " AND pru_auto_apply=1";
            }
            if ($nextTripApplicable == 1)
            {
                $where .= " AND pru_next_trip_apply=1";
            }
            $sql = "SELECT COUNT(1) 
				FROM promo_users
				WHERE
				pru_ref_id=" . $refId . "
				AND pru_ref_type=" . $refType . "
				AND pru_promo_id=" . $promoId . "
				$where
				AND pru_active=1
				";
            $res = DBUtil::command($sql)->queryScalar();
            if ($res == 0)
            {
                return false;
            }
            else
            {
                return true;
            }
        }
    }

    public function checkPromoAndUser($promoId, $refId)
    {
        $sql = "SELECT pru_id
				FROM promo_users
				WHERE
				pru_next_trip_apply=1 
				AND pru_use_max=1 
				AND pru_active=1 
				AND pru_auto_apply=1
				AND pru_ref_id=" . $refId . "
				AND pru_ref_type=0
				AND pru_promo_id=" . $promoId;
        $res = DBUtil::command($sql)->queryScalar();
        if ($res > 0)
        {
            return $res;
        }
        else
        {
            return 0;
        }
    }

    public function getNextTripApplyPromo($refId)
    {
        $sql = "SELECT pru_promo_id FROM promo_users 
				LEFT JOIN promos ON prm_id=pru_promo_id
				WHERE pru_ref_id=$refId AND pru_ref_type=0 AND pru_next_trip_apply=1 AND pru_use_max=1 AND pru_used_counter=0
				AND pru_active=1 AND pru_auto_apply=1 AND prm_applicable_nexttrip=1";
        $res = DBUtil::command($sql)->queryScalar();
        if ($res > 0)
        {
            return $res;
        }
        else
        {
            return 0;
        }
    }

    /** 
     * 
     * @param integer $promoId
     * @param integer $refId
     * @param integer $refType
     * @param integer $autoApply
     * @param string $validFrom
     * @param string $validUpto
     * @param integer $maxUse
     * @param type $nextTrip
     * @return \PromoUsers
     * @throws Exception
     */
    public static function addUser($promoId, $refId, $refType = 0, $autoApply = 0, $validFrom = '', $validUpto = '', $maxUse = 1, $nextTrip = 0)
    {
        $promoUserModel                      = new PromoUsers();
        $promoUserModel->pru_use_max         = $maxUse;
        $promoUserModel->pru_auto_apply      = $autoApply;
        $promoUserModel->pru_promo_id        = $promoId;
        $promoUserModel->pru_ref_id          = $refId;
        $promoUserModel->pru_active          = 1;
        $promoUserModel->pru_ref_type        = $refType;
        $promoUserModel->pru_created         = new CDbExpression('NOW()');
        $promoUserModel->pru_next_trip_apply = $nextTrip;
        $promoUserModel->pru_valid_from      = $validFrom; //DateTimeFormat::DatePickerToDate(date('Y-m-d H:i:s')) . " 00:00:00";
        $promoUserModel->pru_valid_upto      = $validUpto; //DateTimeFormat::DatePickerToDate(date('Y-m-d H:i:s', strtotime('+1 year'))) . " 00:00:00";
        $success  = $promoUserModel->save();
        if(!$success)
        {
            throw new Exception(json_encode($promoUserModel->getErrors()), 1);
        }
        return $promoUserModel;
    }

    public function getRefType($promoId, $userId, $bkgId)
    {
        if ($userId == '' || $userId == 0)
        {
            $userId = -1;
        }
        if ($bkgId == '' || $bkgId == 0)
        {
            $bkgId = -1;
        }
        $sql = "SELECT IF(pru_ref_type=1,pru_ref_id=$bkgId,pru_ref_id=$userId) ref,pru_ref_type,pru_ref_id FROM promo_users WHERE pru_promo_id=$promoId AND pru_active=1 HAVING ref=1 ORDER BY pru_ref_type DESC";
        return DBUtil::queryAll($sql);
    }
	/**
	 * 
	 * @param type $promoId
	 * @param type $refId |userID
	 * @param type $refTYpe |if userID then 0
	 * @return type int |pru_promo_id
	 */
	public static function checkPromobyUser($promoId, $refId, $refTYpe)
	{
		$params	 = ['pru_promo_id' => $promoId, 'pru_ref_id' => $refId, 'pru_ref_type' => $refTYpe];

		$sql	 = "SELECT pru_id,pru_valid_upto FROM promo_users 
					WHERE 1 AND (NOW() BETWEEN pru_valid_from AND pru_valid_upto)  
					AND pru_next_trip_apply=1 AND (pru_use_max=0 OR pru_use_max > pru_used_counter) 
					AND pru_active=1 AND pru_auto_apply=1 AND pru_ref_id=:pru_ref_id 
					AND pru_ref_type=:pru_ref_type AND pru_promo_id=:pru_promo_id";
		$promoRow	 = DBUtil::queryRow($sql, DBUtil::SDB(), $params);
		return $promoRow;
	}

}
