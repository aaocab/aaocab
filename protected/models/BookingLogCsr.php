<?php

/**
 * This is the model class for table "booking_log_csr".
 *
 * The followings are the available columns in table 'booking_log_csr':
 * @property integer $blc_id
 * @property integer $blc_bkg_id
 * @property integer $blc_admin_id
 * @property integer $blc_event_id
 * @property integer $blc_count
 * @property string $blc_create_date
 * @property string $blc_modified_date
 * @property integer $blc_active
 */
class BookingLogCsr extends CActiveRecord
{

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'booking_log_csr';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('blc_bkg_id, blc_admin_id', 'required'),
            array('blc_bkg_id, blc_admin_id, blc_event_id, blc_count, blc_active', 'numerical', 'integerOnly' => true),
            array('blc_create_date, blc_modified_date', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('blc_id, blc_bkg_id, blc_admin_id, blc_event_id, blc_count, blc_create_date, blc_modified_date, blc_active', 'safe', 'on' => 'search'),
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
            'blc_id'            => 'Blc',
            'blc_bkg_id'        => 'Blc Bkg',
            'blc_admin_id'      => 'Blc Admin',
            'blc_event_id'      => 'Blc Event',
            'blc_count'         => 'Blc Count',
            'blc_create_date'   => 'Blc Create Date',
            'blc_modified_date' => 'Blc Modified Date',
            'blc_active'        => 'Blc Active',
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

        $criteria->compare('blc_id', $this->blc_id);
        $criteria->compare('blc_bkg_id', $this->blc_bkg_id);
        $criteria->compare('blc_admin_id', $this->blc_admin_id);
        $criteria->compare('blc_event_id', $this->blc_event_id);
        $criteria->compare('blc_count', $this->blc_count);
        $criteria->compare('blc_create_date', $this->blc_create_date, true);
        $criteria->compare('blc_modified_date', $this->blc_modified_date, true);
        $criteria->compare('blc_active', $this->blc_active);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return BookingLogCsr the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function getbyAdminId($bkgId, $csrId, $eventId)
    {
        $criteria = new CDbCriteria;
        $criteria->compare('blc_bkg_id', $bkgId);
        $criteria->compare('blc_admin_id', $csrId);
        $criteria->compare('blc_event_id', $eventId);
        $model    = $this->find($criteria);
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
     * This function is used update csr event details
     * @param integer $bkgId
     * @param integer $csrId
     * @param integer $eventId
     * @return boolean
     */
    public static function updateCsrDetails($bkgId, $csrId, $eventId)
    {
        $success = false;
        try
        {
            $model = BookingLogCsr::model()->getbyAdminId($bkgId, $csrId, $eventId);
            if (!$model)
            {
                $model                    = new BookingLogCsr();
                $model->blc_bkg_id        = $bkgId;
                $model->blc_admin_id      = $csrId;
                $model->blc_event_id      = $eventId;
                $model->blc_count         = 1;
                $model->blc_create_date   = DBUtil::getCurrentTime();
                $model->blc_modified_date = DBUtil::getCurrentTime();
                $model->blc_active        = 1;
            }
            else
            {
                $model->blc_count         = $model->blc_count + 1;
                $model->blc_modified_date = DBUtil::getCurrentTime();
                $model->blc_active        = 1;
            }
            if ($model->save())
            {
                $success = true;
            }
        }
        catch (Exception $ex)
        {
            Logger::error($ex->getMessage());
        }

        return $success;
    }

}
