<?php

/**
 * This is the model class for table "price_rule_log".
 *
 * The followings are the available columns in table 'price_rule_log':
 * @property string $prl_id
 * @property integer $prl_ref_type
 * @property integer $prl_ref_id
 * @property integer $prl_prr_id
 * @property string $prl_desc
 * @property string $prl_create_date
 */
class PriceRuleLog extends CActiveRecord
{

    const Admin           = 4;
    const REF_MATCH_FOUND = 201;

    public $recipient_arr = ['1' => 'Consumer', '2' => 'Vendor', '3' => 'Driver', '4' => 'Admin', '5' => 'Agent'];

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'price_rule_log';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('prl_ref_id, prl_prr_id, prl_desc, prl_create_date', 'required'),
            array('prl_ref_id', 'required', 'on' => 'update_ref'),
            array('prl_ref_type, prl_ref_id, prl_prr_id', 'numerical', 'integerOnly' => true),
            array('prl_desc', 'length', 'max' => 2000),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('prl_id, prl_ref_type, prl_ref_id, prl_prr_id, prl_desc, prl_create_date', 'safe', 'on' => 'search'),
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
            'prl_id'          => 'Prl',
            'prl_ref_type'    => 'Prl Ref Type',
            'prl_ref_id'      => 'Prl Ref',
            'prl_prr_id'      => 'Prl Prr',
            'prl_desc'        => 'Prl Desc',
            'prl_create_date' => 'Prl Create Date',
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

        $criteria->compare('prl_id', $this->prl_id, true);
        $criteria->compare('prl_ref_type', $this->prl_ref_type);
        $criteria->compare('prl_ref_id', $this->prl_ref_id);
        $criteria->compare('prl_prr_id', $this->prl_prr_id);
        $criteria->compare('prl_desc', $this->prl_desc, true);
        $criteria->compare('prl_create_date', $this->prl_create_date, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return PriceRuleLog the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function addLog($oldData = false, $newData, $prrId, UserInfo $userInfo = null, $params = false)
    {
        $success      = false;
        $priceRuleLog = new PriceRuleLog();

        if ($userInfo == "")
        {
            $userInfo = UserInfo::model();
        }
        $user_id = $userInfo->userId;

        unset($newData[22]);
        unset($oldData[22]);

        $getDifference = array_diff_assoc($oldData, $newData);

        if (count($getDifference) > 0)
        {
            $newcomm = array();
            while (count($newcomm) >= 50)
            {
                array_pop($newcomm);
            }
            array_unshift($newcomm, array(0 => $getDifference));

            $desc = CJSON::encode($newcomm);
        }

        $priceRuleLog->prl_prr_id      = $prrId;
        $priceRuleLog->prl_ref_id      = $user_id;
        $priceRuleLog->prl_ref_type    = 4;
        $priceRuleLog->prl_create_date = new CDbExpression('NOW()');
        $priceRuleLog->prl_desc        = $desc;

        if ($priceRuleLog->validate())
        {
            $priceRuleLog->save();
            $success = true;
        }
       
        return $success;
    }

	public static function getDataById($prrId)
	{
		$pageSize = 25;
        if ($viewType == 1)
        {
            $pageSize = 20;
        }
        $sql  = "SELECT (CASE price_rule_log.prl_ref_type
                    WHEN 10 THEN 'System'
                    WHEN 4 THEN CONCAT(admins.adm_fname,' ',admins.adm_lname)
                    ELSE '' END
                ) as name,
				(CASE price_rule_log.prl_ref_type WHEN 1 then 'Consumers'
                    WHEN 2 THEN 'Vendor'
                    WHEN 3 then 'Driver'
                    WHEN 4 then 'Admin'
                    WHEN 5 then 'Agent'
                    WHEN 10 then 'System'
                    ELSE '' END) as type,
				 prl_prr_id, prl_desc, prl_create_date
                FROM `price_rule_log`
				LEFT JOIN `admins` ON admins.adm_id=price_rule_log.prl_ref_id
                WHERE price_rule_log.prl_prr_id = $prrId";
        $count        = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc")->queryScalar();
        $dataprovider = new CSqlDataProvider($sql, [
            'totalItemCount' => $count,
            'sort'           => ['attributes'   => ['prl_desc'],
                'defaultOrder' => 'prl_create_date  DESC'], 'pagination' => ['pageSize' => $pageSize],
        ]);
        return $dataprovider;
	}

}
