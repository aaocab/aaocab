<?php

/**
 * This is the model class for table "topbookingcities".
 *
 * The followings are the available columns in table 'topbookingcities':
 * @property integer $tbc_id
 * @property integer $tbc_cty_id
 * @property integer $tbc_vendor_cnt
 * @property integer $tbc_driver_cnt
 * @property double $tbc_lat
 * @property double $tbc_lng
 * @property string $tbc_create_date
 * @property integer $tbc_status
 */
class Topbookingcities extends CActiveRecord
{

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'topbookingcities';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('tbc_cty_id, tbc_vendor_cnt, tbc_driver_cnt, tbc_lat, tbc_lng', 'required'),
            array('tbc_cty_id, tbc_vendor_cnt, tbc_driver_cnt, tbc_status', 'numerical', 'integerOnly' => true),
            array('tbc_lat, tbc_lng', 'numerical'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('tbc_id, tbc_cty_id, tbc_vendor_cnt, tbc_driver_cnt, tbc_lat, tbc_lng, tbc_create_date, tbc_status', 'safe', 'on' => 'search'),
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
            'tbc_id'               => 'Tbc',
            'tbc_cty_id'           => 'Tbc Cty',
            'tbc_cty_display_name' => 'Tbc Cty Display Name',
            'tbc_vendor_cnt'       => 'Tbc Vendor Cnt',
            'tbc_driver_cnt'       => 'Tbc Driver Cnt',
            'tbc_lat'              => 'Tbc Lat',
            'tbc_lng'              => 'Tbc Lng',
            'tbc_create_date'      => 'Tbc Create Date',
            'tbc_status'           => 'Tbc Status',
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

        $criteria->compare('tbc_id', $this->tbc_id);
        $criteria->compare('tbc_cty_id', $this->tbc_cty_id);
        $criteria->compare('tbc_cty_display_name', $this->tbc_cty_display_name, true);
        $criteria->compare('tbc_vendor_cnt', $this->tbc_vendor_cnt);
        $criteria->compare('tbc_driver_cnt', $this->tbc_driver_cnt);
        $criteria->compare('tbc_lat', $this->tbc_lat);
        $criteria->compare('tbc_lng', $this->tbc_lng);
        $criteria->compare('tbc_create_date', $this->tbc_create_date, true);
        $criteria->compare('tbc_status', $this->tbc_status);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Topbookingcities the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * This function is used for saving all vendor/driver count for given latitude/longtutude
     * @param type $latitude
     * @param type $longtitude
     * @return type boolean 
     */
    public static function add($cityid, $ctylat, $ctylong, $cntVendor, $cntDriver)
    {
        $success = false;
        $model                  = new Topbookingcities();
        $model->tbc_cty_id      = $cityid;
        $model->tbc_vendor_cnt  = $cntVendor;
        $model->tbc_driver_cnt  = $cntDriver;
        $model->tbc_lat         = $ctylat;
        $model->tbc_lng         = $ctylong;
        $model->tbc_create_date = DBUtil::getCurrentTime();
        $model->tbc_status      = 1;
        if ($model->validate())
        {
            if ($model->save())
            {
                $success = true;
            }
            else
            {
                $getErrors = json_encode($model->getErrors());
            }
        }
        else
        {
            $getErrors = json_encode($model->getErrors());
        }
        return $success;
    }

}
